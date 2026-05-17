<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\ToolbarButtonGroup;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                // ── Writing Zone (3/4 width) ─────────────────────────────────────
                Group::make([

                    // Document identity — title + slug + excerpt, no container chrome
                    Section::make()
                        ->extraAttributes(['class' => 'post-identity-section'])
                        ->schema([
                            TextInput::make('title')
                                ->hiddenLabel()
                                ->placeholder('Tiêu đề bài viết...')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('slug', str($state)->slug())
                                )
                                ->extraInputAttributes(['class' => 'post-title-input'])
                                ->columnSpanFull(),

                            TextInput::make('slug')
                                ->hiddenLabel()
                                ->placeholder('slug-bai-viet')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->prefix('/blog/')
                                ->extraInputAttributes(['class' => 'post-slug-input'])
                                ->columnSpanFull(),

                            Textarea::make('excerpt')
                                ->hiddenLabel()
                                ->placeholder('Tóm tắt bài viết — hiển thị trên trang danh sách và kết quả tìm kiếm...')
                                ->rows(3)
                                ->extraAttributes(['class' => 'post-excerpt-area'])
                                ->columnSpanFull(),
                        ]),

                    // Body editor — main writing surface
                    Section::make()
                        ->extraAttributes(['class' => 'post-body-section'])
                        ->schema([
                            RichEditor::make('body')
                                ->hiddenLabel()
                                ->toolbarButtons([
                                    'bold', 'italic', 'underline', 'strike',
                                    ToolbarButtonGroup::make('Heading', ['h1', 'h2', 'h3', 'h4', 'paragraph'])
                                        ->textualButtons(),
                                    ToolbarButtonGroup::make('Align', ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify']),
                                    'bulletList', 'orderedList', 'blockquote', 'codeBlock',
                                    ToolbarButtonGroup::make('Format', ['subscript', 'superscript', 'code', 'textColor', 'highlight', 'clearFormatting']),
                                    'link', 'attachFiles',
                                    ToolbarButtonGroup::make('Insert', ['table', 'grid', 'gridDelete', 'details', 'horizontalRule']),
                                    'undo', 'redo',
                                ])
                                ->fileAttachmentsDirectory('posts/attachments')
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsVisibility('public')
                                ->columnSpanFull(),
                        ]),

                    // SEO metadata — collapsible, secondary workflow
                    Section::make('SEO & Metadata')
                        ->collapsible()
                        ->collapsed()
                        ->extraAttributes(['class' => 'post-seo-section'])
                        ->schema([
                            TextInput::make('seo_title')
                                ->label('Meta Title')
                                ->maxLength(60)
                                ->helperText('Tối đa 60 ký tự — để trống để dùng tiêu đề bài viết.')
                                ->columnSpanFull(),
                            Textarea::make('seo_description')
                                ->label('Meta Description')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Tối đa 160 ký tự.')
                                ->columnSpanFull(),
                            FileUpload::make('seo_og_image')
                                ->label('OG Image (1200 × 630)')
                                ->image()
                                ->disk('public')
                                ->directory('posts/seo')
                                ->visibility('public')
                                ->columnSpanFull(),
                        ]),

                ])->columnSpan(3),

                // ── Metadata Sidebar (1/4 width) ─────────────────────────────────
                Group::make([

                    Section::make('Xuất bản')
                        ->schema([
                            Select::make('status')
                                ->hiddenLabel()
                                ->options([
                                    'draft'     => 'Bản nháp',
                                    'published' => 'Đã xuất bản',
                                ])
                                ->default('draft')
                                ->required(),
                            DateTimePicker::make('published_at')
                                ->label('Ngày xuất bản')
                                ->native(false),
                            TextInput::make('reading_time')
                                ->label('Thời gian đọc (phút)')
                                ->numeric()
                                ->minValue(1),
                            Toggle::make('featured')
                                ->label('Bài viết nổi bật'),
                        ]),

                    Section::make('Ảnh bìa')
                        ->schema([
                            FileUpload::make('cover_image')
                                ->hiddenLabel()
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('posts/covers')
                                ->visibility('public'),
                        ]),

                    Section::make('Phân loại')
                        ->schema([
                            Select::make('author_id')
                                ->label('Tác giả')
                                ->relationship('author', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('categories')
                                ->label('Danh mục')
                                ->relationship('categories', 'title')
                                ->multiple()
                                ->searchable()
                                ->preload(),
                            TagsInput::make('tags')
                                ->label('Tags')
                                ->separator(','),
                        ]),

                ])->columnSpan(1),
            ]);
    }
}
