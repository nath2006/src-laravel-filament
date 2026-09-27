<?php

namespace App\Filament\Resources\Categories\Schemas;

use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->label("Nama Kategori")
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(
                    //$operation = 'create' | 'edit',
                    //$state     = nilai baru input nama
                    //$set       = helper untuk mengubah filed LAIN dan form

                    fn(string $operation, $state, Set $set) => $operation === 'create'
                    ? $set('slug', Str::slug($state))
                    : null
                ),
                TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->helperText('Otomatis dari nama kategori. Akan di pakai di URL')
            ]);
    }
}
