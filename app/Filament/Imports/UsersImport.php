<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersImport extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->label('Email Address')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255', 'unique:users,email']),
            ImportColumn::make('email_verified_at')
                ->label('Email Verified At')
                ->rules(['nullable', 'date']),
            ImportColumn::make('password')
                ->label('Password')
                ->rules(['nullable', 'min:8'])
                ->example('password123'),
        ];
    }

    public function resolveRecord(): ?User
    {
        $data = $this->data;

        // Set default password if not provided
        if (empty($data['password'])) {
            $data['password'] = 'password123';
        }

        // Create new user (validation will catch duplicates)
        return new User($data);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $successfulRows = $import->successful_rows;
        $failedRowsCount = $import->getFailedRowsCount();
        $totalRows = $import->total_rows;

        if ($successfulRows === 0 && $failedRowsCount > 0) {
            // All rows failed
            return 'Import failed completely: All ' . number_format($totalRows) . ' ' . str('row')->plural($totalRows) . ' failed to import due to validation errors (duplicate emails, invalid data, etc.).';
        } elseif ($failedRowsCount > 0) {
            // Partial success
            $body = 'Import completed with issues: ' . number_format($successfulRows) . ' ' . str('user')->plural($successfulRows) . ' imported successfully.';
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed (duplicate emails or validation errors).';
            return $body;
        } else {
            // Complete success
            return 'Import completed successfully: ' . number_format($successfulRows) . ' ' . str('user')->plural($successfulRows) . ' imported.';
        }
    }

    public static function getFailedNotificationBody(Import $import): string
    {
        $failedRowsCount = $import->getFailedRowsCount();

        return 'Import failed: ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' could not be imported due to validation errors (duplicate emails, invalid data, etc.).';
    }
}