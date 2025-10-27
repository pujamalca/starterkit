<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(50)
                ->reactive()
                ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),
            Textarea::make('description')
                ->label('Deskripsi')
                ->rows(3),
            Toggle::make('is_system')
                ->label('Role Sistem')
                ->default(false)
                ->disabled(),
            CheckboxList::make('permissions')
                ->label('Permissions')
                ->relationship('permissions', 'name')
                ->columns(2)
                ->searchable()
                ->helperText('Pilih hak akses yang dimiliki role ini.'),
        ]);
    }
}

