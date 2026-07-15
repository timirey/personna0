<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Support\Money;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')->label(__('admin.fields.reference'))->weight('bold'),
                TextEntry::make('status')->label(__('admin.fields.status'))->badge(),
                TextEntry::make('customer_name')->label(__('admin.fields.customer')),
                TextEntry::make('customer_phone')->label(__('admin.fields.phone'))->copyable(),
                TextEntry::make('locale')->label(__('admin.fields.language')),
                TextEntry::make('created_at')->label(__('admin.fields.date'))->dateTime('d M Y, H:i'),
                TextEntry::make('total')->label(__('admin.fields.total'))->formatStateUsing(fn ($state) => Money::format($state)),

                TextEntry::make('admin_notes')
                    ->label(__('admin.fields.notes'))
                    ->placeholder('—')
                    ->columnSpanFull(),

                RepeatableEntry::make('items')
                    ->label(__('admin.order.items'))
                    ->schema([
                        TextEntry::make('product_name')->label(__('admin.fields.product')),
                        TextEntry::make('size')->label(__('admin.fields.sizes'))->placeholder('—'),
                        TextEntry::make('qty')->label(__('admin.fields.qty')),
                        TextEntry::make('unit_price')
                            ->label(__('admin.fields.unit_price'))
                            ->formatStateUsing(function ($state, $record) {
                                $price = Money::format($state);
                                if ($record->original_unit_price) {
                                    return Money::format($record->original_unit_price).' → '.$price;
                                }

                                return $price;
                            }),
                        TextEntry::make('line_total')->label(__('admin.fields.line_total'))->formatStateUsing(fn ($state) => Money::format($state)),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),
            ]);
    }
}
