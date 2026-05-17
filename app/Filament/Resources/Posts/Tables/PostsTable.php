<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('')
                    ->width(60)
                    ->height(40)
                    ->extraImgAttributes(['class' => 'rounded object-cover']),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'gray',
                        default     => 'gray',
                    }),
                IconColumn::make('featured')
                    ->boolean()
                    ->label('⭐'),
                TextColumn::make('published_at')
                    ->label('Published')
                    ->date('M j, Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Published']),
                SelectFilter::make('author')
                    ->relationship('author', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-globe-alt')
                        ->color('success')
                        ->modalHeading('Publish selected posts')
                        ->modalDescription('Set the publish date for all selected posts. They will be marked as "published".')
                        ->form([
                            DateTimePicker::make('published_at')
                                ->label('Publish date & time')
                                ->default(now())
                                ->required()
                                ->native(false),
                        ])
                        ->action(function ($records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'status'       => 'published',
                                    'published_at' => $data['published_at'],
                                ]);
                            }
                            Notification::make()
                                ->title(count($records) . ' post(s) published')
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('replicate')
                        ->label('Clone')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Clone selected posts')
                        ->modalDescription('Each post will be cloned as a draft with "(Copy)" appended to the title.')
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $clone = $record->replicate(['id', 'created_at', 'updated_at']);
                                $baseSlug = $record->slug . '-copy';
                                $slug = $baseSlug;
                                $i = 1;
                                while (\App\Models\Post::where('slug', $slug)->exists()) {
                                    $slug = $baseSlug . '-' . $i++;
                                }
                                $clone->title = $record->title . ' (Copy)';
                                $clone->slug = $slug;
                                $clone->status = 'draft';
                                $clone->published_at = null;
                                $clone->featured = false;
                                $clone->save();
                                // re-attach categories
                                $clone->categories()->sync($record->categories->pluck('id'));
                            }
                            Notification::make()->title('Cloned successfully')->success()->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}


