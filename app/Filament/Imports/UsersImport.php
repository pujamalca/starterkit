<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('email_verified_at')
                ->label('Email Verified At')
                ->rules(['nullable', 'date']),
            ImportColumn::make('password')
                ->label('Password')
                ->rules(['nullable'])
                ->example('defaultpassword123'),
        ];
    }


    public function resolveRecord(): ?User
    {
        Log::info('=== RESOLVE RECORD CALLED ===');
        Log::info('Raw data received:', $this->data);

        $data = $this->data;

        // Handle empty password - set default
        if (!isset($data['password']) || trim((string)($data['password'] ?? '')) === '') {
            Log::info('Password is empty, setting default');
            $data['password'] = 'defaultpassword123';
        } else {
            Log::info('Password provided: ' . strlen($data['password']) . ' characters');
        }

        Log::info('Creating user with data:', $data);

        try {
            $user = new User($data);
            Log::info('User created successfully');
            return $user;
        } catch (\Exception $e) {
            Log::error('Error creating user:', ['error' => $e->getMessage()]);
            throw $e;
        }
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