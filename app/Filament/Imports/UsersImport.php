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
            ImportColumn::make('username')
                ->label('Username')
                ->rules(['nullable', 'max:50']),
            ImportColumn::make('email')
                ->label('Email Address')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->label('Phone')
                ->rules(['nullable', 'max:20']),
            ImportColumn::make('avatar')
                ->label('Avatar URL')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('bio')
                ->label('Bio')
                ->rules(['nullable']),
            ImportColumn::make('email_verified_at')
                ->label('Email Verified At')
                ->rules(['nullable', 'date']),
            ImportColumn::make('password')
                ->label('Password')
                ->rules(['nullable'])
                ->example('defaultpassword123'),
            ImportColumn::make('is_active')
                ->label('Active')
                ->rules(['nullable', 'boolean'])
                ->example('1'),
            ImportColumn::make('last_login_at')
                ->label('Last Login At')
                ->rules(['nullable', 'date']),
            ImportColumn::make('last_login_ip')
                ->label('Last Login IP')
                ->rules(['nullable', 'max:45']),
            ImportColumn::make('preferences')
                ->label('Preferences (JSON)')
                ->rules(['nullable']),
            ImportColumn::make('metadata')
                ->label('Metadata (JSON)')
                ->rules(['nullable']),
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

        // Coerce booleans and JSON-like fields if provided as strings
        if (array_key_exists('is_active', $data)) {
            $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }
        foreach (['preferences', 'metadata'] as $jsonField) {
            if (isset($data[$jsonField]) && is_string($data[$jsonField])) {
                $decoded = json_decode($data[$jsonField], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$jsonField] = $decoded;
                }
            }
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
