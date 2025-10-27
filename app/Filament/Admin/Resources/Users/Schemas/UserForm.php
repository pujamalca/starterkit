<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('username')
                    ->label('Username')
                    ->maxLength(50),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Phone')
                    ->maxLength(20),
                TextInput::make('avatar')
                    ->label('Avatar URL')
                    ->maxLength(255),
                Textarea::make('bio')
                    ->label('Bio')
                    ->rows(3),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->minLength(8)
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->helperText('Kosongkan saat mengedit untuk mempertahankan password sekarang.'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                DateTimePicker::make('last_login_at')
                    ->label('Last login at')
                    ->disabled(),
                TextInput::make('last_login_ip')
                    ->label('Last login IP')
                    ->maxLength(45)
                    ->disabled(),
                KeyValue::make('preferences')
                    ->label('Preferences')
                    ->keyLabel('Key')
                    ->valueLabel('Value')
                    ->addable()
                    ->reorderable(),
                KeyValue::make('metadata')
                    ->label('Metadata')
                    ->keyLabel('Key')
                    ->valueLabel('Value')
                    ->addable()
                    ->reorderable(),
                Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->visible(fn (): bool => auth()->user()?->can('manage-roles') ?? false)
                    ->helperText('Atur role dan permission pengguna.'),
            ]);
    }
}
