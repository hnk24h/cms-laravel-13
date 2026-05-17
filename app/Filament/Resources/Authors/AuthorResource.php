<?php

namespace App\Filament\Resources\Authors;

use App\Filament\Resources\Authors\Pages\CreateAuthor;
use App\Filament\Resources\Authors\Pages\EditAuthor;
use App\Filament\Resources\Authors\Pages\ListAuthors;
use App\Filament\Resources\Authors\Schemas\AuthorForm;
use App\Filament\Resources\Authors\Tables\AuthorsTable;
use App\Enums\UserRole;
use App\Models\Author;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'Tác giả';

    protected static ?string $modelLabel = 'tác giả';

    protected static ?string $pluralModelLabel = 'Tác giả';

    protected static ?int $navigationSort = 3;

    // Only admin & editor can manage authors
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
        return AuthorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthorsTable::configure($table);
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
            'index' => ListAuthors::route('/'),
            'create' => CreateAuthor::route('/create'),
            'edit' => EditAuthor::route('/{record}/edit'),
        ];
    }
}
