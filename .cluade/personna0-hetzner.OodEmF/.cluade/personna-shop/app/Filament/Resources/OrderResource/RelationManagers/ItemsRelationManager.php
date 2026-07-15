<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Items';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_name')->label('Product'),
                Tables\Columns\TextColumn::make('size')->placeholder('—'),
                Tables\Columns\TextColumn::make('qty')->label('Qty'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2) . ' ' . config('shop.currency')),
                Tables\Columns\TextColumn::make('line_total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2) . ' ' . config('shop.currency')),
            ])
            ->paginated(false);
    }
}
