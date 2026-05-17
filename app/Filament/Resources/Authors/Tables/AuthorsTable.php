<?php

namespace App\Filament\Resources\Authors\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuthorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('avatar')
                    ->searchable(),
                TextColumn::make('twitter')
                    ->searchable(),
                TextColumn::make('github')
                    ->searchable(),
                TextColumn::make('website')
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
                //
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
                        ->modalHeading('Clone selected authors')
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $clone = $record->replicate(['id', 'created_at', 'updated_at']);
                                $baseSlug = $record->slug . '-copy';
                                $slug = $baseSlug;
                                $i = 1;
                                while (\App\Models\Author::where('slug', $slug)->exists()) {
                                    $slug = $baseSlug . '-' . $i++;
                                }
                                $clone->name = $record->name . ' (Copy)';
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
