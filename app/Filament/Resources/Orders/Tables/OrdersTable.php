<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Support\Money;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        $statuses = Collection::make(OrderStatus::cases())
            ->mapWithKeys(fn (OrderStatus $status) => [$status->value => $status->getLabel()])
            ->all();

        return $table
            ->columns([
                TextColumn::make('reference')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('customer_name')
                    ->searchable(),

                TextColumn::make('customer_phone')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('total')
                    ->formatStateUsing(fn ($state) => Money::format($state))
                    ->sortable(),

                SelectColumn::make('status')
                    ->options($statuses)
                    ->selectablePlaceholder(false),

                TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options($statuses),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
