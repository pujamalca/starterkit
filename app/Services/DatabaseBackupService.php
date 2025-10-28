<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;
use ZipArchive;

class DatabaseBackupService
{
    public const FORMATS = ['json', 'csv', 'sql'];

    public function createBackup(string $format = 'json'): string
    {
        $format = strtolower($format);

        if (! in_array($format, self::FORMATS, true)) {
            throw new InvalidArgumentException("Format [{$format}] tidak didukung. Gunakan: ".implode(', ', self::FORMATS).'.');
        }

        $connection = config('database.default');
        $config = config("database.connections.{$connection}", []);

        $database = Arr::get($config, 'database', $connection);
        $driver = Arr::get($config, 'driver', 'mysql');

        if ($driver === 'mariadb') {
            $driver = 'mysql';
        }

        $baseName = sprintf('%s_%s', Str::slug((string) $database), now()->format('Ymd_His'));
        $this->ensureBackupDirectory();

        $tableData = $this->collectTableData($connection, $driver);

        return match ($format) {
            'json' => $this->writeJsonBackup($baseName, $connection, $database, $driver, $tableData),
            'csv' => $this->writeCsvBackup($baseName, $tableData),
            'sql' => $this->writeSqlBackup($baseName, $database, $tableData),
        };
    }

    /**
     * @return array<string, array{schema: ?string, columns: array<int, string>, rows: array<int, array<string, mixed>>}>
     */
    protected function collectTableData(string $connection, string $driver): array
    {
        return collect($this->resolveTables($connection, $driver))
            ->mapWithKeys(function (string $table) use ($connection, $driver) {
                $columns = $this->getColumnListing($connection, $table);

                return [
                    $table => [
                        'schema' => $this->resolveCreateStatement($connection, $driver, $table),
                        'columns' => $columns,
                        'rows' => $this->fetchRows($connection, $table, $columns),
                    ],
                ];
            })
            ->all();
    }

    protected function writeJsonBackup(string $baseName, string $connection, string $database, string $driver, array $tableData): string
    {
        $relativePath = "backups/{$baseName}.json";

        $payload = [
            'generated_at' => now()->toIso8601String(),
            'connection' => $connection,
            'database' => $database,
            'driver' => $driver,
            'tables' => $tableData,
        ];

        Storage::disk('local')->put($relativePath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $relativePath;
    }

    protected function writeCsvBackup(string $baseName, array $tableData): string
    {
        $relativePath = "backups/{$baseName}.zip";
        $tempZip = tempnam(sys_get_temp_dir(), 'backup_csv_');

        $zip = new ZipArchive();
        if ($zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Tidak dapat membuat arsip CSV.');
        }

        foreach ($tableData as $table => $data) {
            $handle = fopen('php://temp', 'r+');
            $columns = $data['columns'];

            if (! empty($columns)) {
                fputcsv($handle, $columns);
            }

            foreach ($data['rows'] as $row) {
                $ordered = array_map(fn (string $column) => $row[$column] ?? null, $columns);
                fputcsv($handle, $ordered);
            }

            rewind($handle);
            $zip->addFromString("{$table}.csv", stream_get_contents($handle));
            fclose($handle);
        }

        $zip->close();

        Storage::disk('local')->put($relativePath, file_get_contents($tempZip));
        @unlink($tempZip);

        return $relativePath;
    }

    protected function writeSqlBackup(string $baseName, string $database, array $tableData): string
    {
        $relativePath = "backups/{$baseName}.sql";

        $lines = [
            sprintf('-- Backup basis data untuk %s dibuat pada %s', $database, now()->toIso8601String()),
            '',
        ];

        foreach ($tableData as $table => $data) {
            if (! empty($data['schema'])) {
                $lines[] = $data['schema'].';';
                $lines[] = '';
            }

            foreach ($data['rows'] as $row) {
                if (empty($row)) {
                    continue;
                }

                $columns = array_keys($row);
                $values = array_map([$this, 'quoteValue'], array_values($row));

                $lines[] = sprintf(
                    'INSERT INTO `%s` (%s) VALUES (%s);',
                    $table,
                    implode(', ', array_map(fn (string $column) => "`{$column}`", $columns)),
                    implode(', ', $values)
                );
            }

            $lines[] = '';
        }

        Storage::disk('local')->put($relativePath, implode(PHP_EOL, $lines));

        return $relativePath;
    }

    protected function ensureBackupDirectory(): void
    {
        $disk = Storage::disk('local');

        if (! $disk->exists('backups')) {
            $disk->makeDirectory('backups');
        }
    }

    /**
     * @return array<int, string>
     */
    protected function resolveTables(string $connection, string $driver): array
    {
        $db = DB::connection($connection);

        return match ($driver) {
            'mysql' => collect($db->select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"'))
                ->map(fn ($row) => collect((array) $row)->values()->first())
                ->filter()
                ->values()
                ->all(),
            'pgsql' => collect($db->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'"))
                ->pluck('tablename')
                ->filter()
                ->values()
                ->all(),
            'sqlite' => collect($db->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'"))
                ->pluck('name')
                ->filter()
                ->values()
                ->all(),
            default => throw new RuntimeException("Database driver [{$driver}] is not supported for backup."),
        };
    }

    protected function resolveCreateStatement(string $connection, string $driver, string $table): ?string
    {
        $db = DB::connection($connection);

        return match ($driver) {
            'mysql' => optional($db->selectOne("SHOW CREATE TABLE `{$table}`"))?->{'Create Table'},
            'sqlite' => optional($db->selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ?", [$table]))?->sql,
            default => null,
        };
    }

    protected function fetchRows(string $connection, string $table, array $columns): array
    {
        return DB::connection($connection)
            ->table($table)
            ->get()
            ->map(fn ($row) => $this->orderRowColumns((array) $row, $columns))
            ->all();
    }

    protected function getColumnListing(string $connection, string $table): array
    {
        return DB::connection($connection)->getSchemaBuilder()->getColumnListing($table);
    }

    protected function orderRowColumns(array $row, array $columns): array
    {
        if (empty($columns)) {
            return $row;
        }

        $ordered = [];

        foreach ($columns as $column) {
            $ordered[$column] = $row[$column] ?? null;
        }

        return $ordered;
    }

    protected function quoteValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        $escaped = str_replace(["\\", "'"], ["\\\\", "''"], (string) $value);

        return "'{$escaped}'";
    }
}
