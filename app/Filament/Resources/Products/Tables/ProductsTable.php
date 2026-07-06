<?php

namespace App\Filament\Resources\Products\Tables;

use App\Support\Money;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('gallery')
                    ->collection('gallery')
                    ->conversion('thumb')
                    ->limit(1)
                    ->label(__('admin.fields.image')),

                TextColumn::make('name')
                    ->label(__('admin.fields.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label(__('admin.fields.category')),

                TextColumn::make('price')
                    ->label(__('admin.fields.price'))
                    ->formatStateUsing(fn ($state) => Money::format($state))
                    ->sortable(),

                TextColumn::make('stock')
                    ->label(__('admin.fields.stock'))
                    ->placeholder('∞')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('admin.fields.active')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label(__('admin.fields.active')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
