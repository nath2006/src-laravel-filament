<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CategoryResource extends Resource
{
    /** Model Eloquent yang dikelola Resource ini. */
    protected static ?string $model = Category::class;

    /** Ikon sidebar. v5 memakai enum Heroicon, bukan string 'heroicon-o-...'. */
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    /** Grup menu di sidebar. Tipe UnitEnum agar bisa pakai enum grup kustom. */
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    /** Teks menu sidebar. */
    protected static ?string $navigationLabel = 'Kategori';

    /** Label tunggal — muncul di tombol "New Kategori", judul modal, notifikasi. */
    protected static ?string $modelLabel = 'Kategori';

    /** Label jamak — muncul di judul halaman list. */
    protected static ?string $pluralModelLabel = 'Kategori';

    /** Urutan dalam grup (makin kecil makin atas). */
    protected static ?int $navigationSort = 1;

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
            'index'  => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit'   => EditCategory::route('/{record}/edit'),
        ];
    }

    /** Badge angka di sebelah menu sidebar. */
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
