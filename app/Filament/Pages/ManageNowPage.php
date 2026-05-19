<?php

namespace App\Filament\Pages;

use App\Models\NowPage;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManageNowPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon  = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Now Page';
    protected static ?string $title           = 'Now Page';
    protected static ?string $slug            = 'now-page';
    protected static ?int    $navigationSort  = 10;

    protected string $view = 'filament.pages.manage-now-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(NowPage::instance()->toArray());
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Tổng quan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('location')
                            ->label('Đang ở đâu')
                            ->placeholder('Tokyo, Japan')
                            ->columnSpan(1),
                        DatePicker::make('content_updated_at')
                            ->label('Cập nhật lần cuối')
                            ->native(false)
                            ->columnSpan(1),
                        Toggle::make('published')
                            ->label('Hiển thị trang')
                            ->columnSpanFull(),
                        Textarea::make('status')
                            ->label('Trạng thái hiện tại')
                            ->placeholder('Dạo này tôi đang...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('🎯 Đang focus vào')
                    ->schema([
                        Repeater::make('focus')
                            ->label('')
                            ->schema([
                                TextInput::make('icon')->label('Icon')->placeholder('💻')->maxLength(4),
                                TextInput::make('text')->label('Nội dung')->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('+ Thêm mục')
                            ->defaultItems(0),
                    ]),

                Section::make('📚 Đang đọc / xem')
                    ->schema([
                        Repeater::make('reading')
                            ->label('')
                            ->schema([
                                TextInput::make('title')->label('Tiêu đề')->required(),
                                TextInput::make('author')->label('Tác giả'),
                                Select::make('type')
                                    ->label('Loại')
                                    ->options([
                                        'book'    => '📖 Sách',
                                        'article' => '📰 Bài viết',
                                        'blog'    => '✍️ Blog',
                                        'video'   => '🎥 Video',
                                        'podcast' => '🎙️ Podcast',
                                    ])
                                    ->default('book'),
                                TextInput::make('url')->label('URL')->url(),
                            ])
                            ->columns(2)
                            ->addActionLabel('+ Thêm mục')
                            ->defaultItems(0),
                    ]),

                Section::make('🛠️ Đang học')
                    ->schema([
                        Repeater::make('learning')
                            ->label('')
                            ->schema([
                                TextInput::make('text')->label('Nội dung')->required()->columnSpanFull(),
                            ])
                            ->addActionLabel('+ Thêm mục')
                            ->defaultItems(0),
                    ]),

                Section::make('🇯🇵 Từ vựng hôm nay')
                    ->description('Tối đa 10 từ mỗi ngày')
                    ->schema([
                        Repeater::make('vocabulary')
                            ->label('')
                            ->schema([
                                TextInput::make('word')
                                    ->label('Từ (漢字/かな)')
                                    ->required(),
                                TextInput::make('reading')
                                    ->label('Cách đọc (hiragana)'),
                                TextInput::make('meaning')
                                    ->label('Nghĩa tiếng Việt')
                                    ->required(),
                                Select::make('type')
                                    ->label('Từ loại')
                                    ->options([
                                        'noun'      => '名詞 - Danh từ',
                                        'verb'      => '動詞 - Động từ',
                                        'adjective' => '形容詞 - Tính từ',
                                        'adverb'    => '副詞 - Trạng từ',
                                        'other'     => 'Khác',
                                    ])
                                    ->default('noun'),
                                TextInput::make('example')
                                    ->label('Câu ví dụ')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->maxItems(10)
                            ->addActionLabel('+ Thêm từ')
                            ->defaultItems(0),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Lưu thay đổi')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        NowPage::updateOrCreate(['id' => 1], $data);

        Notification::make()
            ->title('Đã lưu Now Page')
            ->success()
            ->send();
    }
}
