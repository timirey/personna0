<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(150)
                ->live(onBlur: true)
                ->afterStateUpdated(function (string $state, Forms\Set $set) {
                    $set('slug', Str::slug($state));
                }),

            Forms\Components\TextInput::make('slug')
                ->required()
                ->maxLength(160)
                ->unique(ignoreRecord: true)
                ->helperText('Used in the product URL.'),

            Forms\Components\Textarea::make('description')
                ->rows(4)
                ->columnSpanFull(),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required()
                ->minValue(0)
                ->prefix(config('shop.currency')),

            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->minValue(0)
                ->helperText('Leave empty for unlimited stock.'),

            Forms\Components\CheckboxList::make('sizes')
                ->options([
                    'XS' => 'XS', 'S' => 'S', 'M' => 'M',
                    'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL',
                ])
                ->columns(6)
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('image')
                ->image()
                ->imageEditor()
                ->directory('products')
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_active')
                ->label('Active (visible in shop)')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2) . ' ' . config('shop.currency'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->placeholder('∞')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
