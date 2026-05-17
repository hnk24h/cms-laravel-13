<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('is_menu')
                    ->label('Menu')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-minus-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                TextColumn::make('menu_group')
                    ->label('Group')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('color')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_menu')
                    ->label('In menu')
                    ->trueLabel('Show in menu')
                    ->falseLabel('Not in menu'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('replicate')
                        ->label('Clone')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Clone selected categories')
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $clone = $record->replicate(['id', 'created_at', 'updated_at']);
                                $baseSlug = $record->slug . '-copy';
                                $slug = $baseSlug;
                                $i = 1;
                                while (\App\Models\Category::where('slug', $slug)->exists()) {
                                    $slug = $baseSlug . '-' . $i++;
                                }
                                $clone->title = $record->title . ' (Copy)';
                                $clone->slug = $slug;
                                $clone->save();
                            }
                            Notification::make()->title('Cloned successfully')->success()->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
