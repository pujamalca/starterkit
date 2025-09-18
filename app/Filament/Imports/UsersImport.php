<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;

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
                ->rules(['nullable', 'min:8']),
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = new User();

        // Hash password jika ada
        if (!empty($this->data['password'])) {
            $this->data['password'] = Hash::make($this->data['password']);
        }

        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your users import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}