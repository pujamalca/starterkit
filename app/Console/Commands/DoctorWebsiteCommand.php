<?php

namespace App\Console\Commands;

use App\Services\Doctor\DoctorService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(name: 'starterkit:doctor', description: 'Jalankan health check dan analitik cepat untuk Starter Kit.')]
class DoctorWebsiteCommand extends Command
{
    public function handle(DoctorService $doctor): int
    {
        $this->output->writeln('<info>Doctor Website - Health Check & Analytics</info>');

        try {
            $report = $doctor->run();
        } catch (Throwable $throwable) {
            $this->components->error("Gagal menjalankan pemeriksaan: {$throwable->getMessage()}");

            return self::FAILURE;
        }

        $this->line('Waktu pemeriksaan: '.$report['timestamp']->toDateTimeString());
        $this->newLine();

        $this->renderChecksTable($report['checks']);
        $this->newLine();

        $this->renderAnalyticsTable($report['analytics']);
        $this->newLine();

        $this->renderVersionsTable($report['versions']);

        $this->newLine();
        $this->info('Pemeriksaan selesai.');

        return self::SUCCESS;
    }

    protected function renderChecksTable(array $checks): void
    {
        $table = new Table($this->output);
        $table->setHeaders(['Komponen', 'Status', 'Detail']);

        foreach ($checks as $key => $check) {
            $status = strtoupper((string) ($check['status'] ?? 'unknown'));
            $detail = collect($check)
                ->forget(['status'])
                ->map(fn ($value, $attribute) => sprintf('%s: %s', Str::headline((string) $attribute), (string) ($value ?? 'N/A')))
                ->implode(PHP_EOL);

            $table->addRow([Str::headline($key), $status, $detail ?: 'N/A']);
        }

        $table->render();
    }

    protected function renderAnalyticsTable(array $analytics): void
    {
        $this->output->writeln('<comment>Ringkasan Analitik</comment>');

        $table = new Table($this->output);
        $table->setRows(
            collect($analytics)
                ->map(fn ($value, $key) => [Str::headline((string) $key), (string) $value])
                ->all()
        );
        $table->setStyle('compact');
        $table->render();
    }

    protected function renderVersionsTable(array $versions): void
    {
        $this->output->writeln('<comment>Versi Dependensi</comment>');

        $table = new Table($this->output);
        $table->setRows(
            collect($versions)
                ->map(fn ($value, $key) => [Str::headline((string) $key), (string) $value])
                ->all(),
        );
        $table->setStyle('compact');
        $table->render();
    }
}
