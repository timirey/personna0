<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Support\Money;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('admin.fields.name'))
                    ->required()
                    ->maxLength(150)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set) {
                        $set('slug', Str::slug((string) $state));
                    }),

                TextInput::make('slug')
                    ->label(__('admin.fields.slug'))
                    ->required()
                    ->maxLength(160)
                    ->unique(ignoreRecord: true)
                    ->helperText(__('admin.help.slug')),

                Select::make('category_id')
                    ->label(__('admin.fields.category'))
                    ->required()
                    ->native(false)
                    ->options(fn () => Category::query()->active()->orderBy('sort')->get()->pluck('name', 'id')),

                Textarea::make('description')
                    ->label(__('admin.fields.description'))
                    ->rows(5)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label(__('admin.fields.price'))
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->prefix(Money::currency()),

                TextInput::make('stock')
                    ->label(__('admin.fields.stock'))
                    ->numeric()
                    ->minValue(0)
                    ->helperText(__('admin.help.stock')),

                CheckboxList::make('sizes')
                    ->label(__('admin.fields.sizes'))
                    ->options([
                        'XS' => 'XS', 'S' => 'S', 'M' => 'M',
                        'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL',
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('gallery')
                    ->label(__('admin.fields.gallery'))
                    ->collection('gallery')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label(__('admin.fields.active_visible'))
                    ->default(true),
            ]);
    }
}
