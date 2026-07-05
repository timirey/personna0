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
                    ->required()
                    ->maxLength(150)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set) {
                        $set('slug', Str::slug((string) $state));
                    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(160)
                    ->unique(ignoreRecord: true)
                    ->helperText('Used in the product URL (shared across languages).'),

                Select::make('category_id')
                    ->label('Category')
                    ->required()
                    ->native(false)
                    ->options(fn () => Category::query()->active()->orderBy('sort')->get()->pluck('name', 'id')),

                Textarea::make('description')
                    ->rows(5)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->prefix(Money::currency()),

                TextInput::make('stock')
                    ->numeric()
                    ->minValue(0)
                    ->helperText('Leave empty for unlimited stock.'),

                CheckboxList::make('sizes')
                    ->options([
                        'XS' => 'XS', 'S' => 'S', 'M' => 'M',
                        'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL',
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('gallery')
                    ->collection('gallery')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),

                TextInput::make('meta_title')
                    ->maxLength(160)
                    ->helperText('SEO title (per language).'),

                Textarea::make('meta_description')
                    ->rows(2)
                    ->maxLength(255)
                    ->helperText('SEO description (per language).')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Active (visible in shop)')
                    ->default(true),
            ]);
    }
}
