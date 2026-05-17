<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Enums\UserRole;
use App\Models\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $navigationLabel = 'Bài viết';

    protected static ?string $modelLabel = 'bài viết';

    protected static ?string $pluralModelLabel = 'Bài viết';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    // Admin + editor + contributor can manage posts
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole(UserRole::Admin, UserRole::Editor, UserRole::Contributor) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole(UserRole::Admin, UserRole::Editor, UserRole::Contributor) ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->hasRole(UserRole::Admin, UserRole::Editor, UserRole::Contributor) ?? false;
    }

    // Only admin & editor can delete posts
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
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
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
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
