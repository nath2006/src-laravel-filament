<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            // ======================= SECTION 1: INFORMASI UTAMA =====================================
            Section::make("Informasi Utama")
            ->description('Judul, slug, kategori, dan penulis berita')
            ->icon(\Filament\Support\Icons\Heroicon::OutlinedInformationCircle)
            ->schema([
                TextInput::make('title')
                    ->label('Judul Berita')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn (string $operation, $state, Set $set) => $operation === 'create'
                        ? $set('slug', Str::slug($state)) : null
                    )
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name') //otomatis ngequery category, tampilkan kolom name
                    ->searchable()  //dropdown tapi bisa diketika
                    ->preload() //muat semua opsi saat halaman di buka
                    ->required()
                    ->createOptionForm(fn (Schema $schema) => \App\Filament\Resources\Categories\Schemas\CategoryForm::configure($schema)),

                Select::make('user_id')
                    ->label('Penulis')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->default(Auth::id()) //defaultnya = user yang saat ini login
                    ->required(),
            ])
            ->columns(2),

            // ======================= SECTION 2: KONTEN =====================================
            Section::make('Konten')
                ->description('Ringkasan dan isi lengkap berita')
                ->schema([
                    Textarea::make('excerpt')
                        ->label('Ringkasan')
                        ->maxLength(255)
                        ->rows(3)
                        ->helperText('Tampil di kartu berita dan hasil pencarian Google.')
                        ->columnSpanFull(),
                    RichEditor::make('content')
                        ->label('Isi Berita')
                        ->required()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'h2', 'h3', 'blockquote',
                            'bulletList', 'orderedList',
                            'link', 'undo', 'redo'
                        ])
                        ->columnSpanFull()
                ]),
 // ══════════════════ SECTION 3: MEDIA & PUBLIKASI ══════════════════
                Section::make('Media & Publikasi')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()                                 // validasi: harus file gambar
                            ->directory('posts/thumbnails')           // subfolder di storage/app/public
                            ->imageEditor()                           // aktifkan crop/rotate di browser
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('675')
                            ->maxSize(2048)                           // KB → 2 MB
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft'     => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->native(false)                           // pakai dropdown Filament, bukan <select> bawaan browser
                            ->required(),

                        Toggle::make('is_featured')
                            ->label('Featured News')
                            ->helperText('Tampil di slider halaman depan.')
                            ->default(false),

                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->seconds(false)                          // sembunyikan detik
                            ->displayFormat('d/m/Y H:i')              // format yang dilihat user
                            ->timezone('Asia/Jakarta')                // ← lihat Langkah 4
                            ->default(now())
                            ->helperText('Waktu Indonesia Barat (WIB).'),
                    ])
                    ->columns(2),


            ]);
    }
}
