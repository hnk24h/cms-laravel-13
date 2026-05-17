<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('slug', str($state)->slug())
                    ),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                ColorPicker::make('color')
                    ->placeholder('#3b82f6'),
                Toggle::make('is_menu')
                    ->label('Show in navigation menu')
                    ->helperText('Hiện category này lên menu header của frontend')
                    ->live()
                    ->inline(false),
                Select::make('menu_group')
                    ->label('Menu group')
                    ->helperText('Nhóm menu để phân cấp trên frontend (để trống nếu không cần nhóm)')
                    ->options(fn () => Category::query()
                        ->whereNotNull('menu_group')
                        ->where('menu_group', '!=', '')
                        ->distinct()
                        ->pluck('menu_group', 'menu_group')
                        ->toArray()
                    )
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('value')
                            ->label('Tên nhóm mới')
                            ->required(),
                    ])
                    ->createOptionUsing(fn (array $data) => $data['value'])
                    ->visible(fn ($get) => (bool) $get('is_menu'))
                    ->nullable(),
            ]);
    }
}
