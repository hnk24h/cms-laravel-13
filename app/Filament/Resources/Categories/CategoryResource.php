<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Enums\UserRole;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Danh mục';

    protected static ?string $modelLabel = 'danh mục';

    protected static ?string $pluralModelLabel = 'Danh mục';

    protected static ?int $navigationSort = 2;

    // Only admin & editor can manage categories
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole(UserRole::Admin, UserRole::Editor) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole(UserRole::Admin, UserRole::Editor) ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->hasRole(UserRole::Admin, UserRole::Editor) ?? false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->hasRole(UserRole::Admin, UserRole::Editor) ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->hasRole(UserRole::Admin, UserRole::Editor) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
