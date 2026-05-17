<?php

namespace App\Filament\Resources\Authors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AuthorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Profile')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) =>
                                $set('slug', str($state)->slug())
                            ),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Textarea::make('bio')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Avatar & Links')
                    ->columnSpan(1)
                    ->schema([
                        FileUpload::make('avatar')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('authors')
                            ->visibility('public'),
                        TextInput::make('twitter')
                            ->prefix('@')
                            ->placeholder('handle'),
                        TextInput::make('github')
                            ->placeholder('username'),
                        TextInput::make('website')
                            ->url()
                            ->placeholder('https://...'),
                    ]),
            ]);
    }
}
