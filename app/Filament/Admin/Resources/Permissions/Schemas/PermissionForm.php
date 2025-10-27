<?php

namespace App\Filament\Admin\Resources\Permissions\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(100)
                ->reactive()
                ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true),
            TextInput::make('module')
                ->label('Module')
                ->maxLength(50)
                ->required(),
            Textarea::make('description')
                ->label('Deskripsi')
                ->rows(3),
            TextInput::make('guard_name')
                ->label('Guard')
                ->default('web')
                ->required()
                ->maxLength(50),
            KeyValue::make('metadata')
                ->label('Metadata Tambahan')
                ->nullable()
                ->keyLabel('Kunci')
                ->valueLabel('Nilai'),
            CheckboxList::make('roles')
                ->label('Roles')
                ->relationship('roles', 'name')
                ->columns(2)
                ->searchable()
                ->helperText('Role yang memiliki permission ini.'),
        ]);
    }
}
