<?php

namespace App\Filament\Admin\Resources\Permissions\Tables;

use App\Filament\Admin\Resources\Permissions\PermissionResource;
use App\Models\Permission;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordUrl(fn (Permission $record): string => PermissionResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('module')
                    ->label('Module')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('guard_name')
                    ->label('Guard'),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state, Permission $record) => self::formatRelatedList($record->roles->pluck('name')))
                    ->tooltip(fn (Permission $record) => $record->roles->pluck('name')->join(', '))
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('module')
                    ->label('Module')
                    ->options(fn () => Permission::query()
                        ->select('module')
                        ->distinct()
                        ->orderBy('module')
                        ->pluck('module', 'module')
                        ->filter()
                        ->toArray()),
            ]);
    }

    protected static function formatRelatedList($items): string
    {
        $items = collect($items);

        $visible = $items->take(3);
        $hiddenCount = max($items->count() - $visible->count(), 0);

        $label = $visible->join(', ');

        if ($hiddenCount > 0) {
            $label .= ' +' . $hiddenCount;
        }

        return $label;
    }
}
