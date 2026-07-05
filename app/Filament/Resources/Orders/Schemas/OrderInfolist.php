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
                TextEntry::make('reference')->weight('bold'),
                TextEntry::make('status')->badge(),
                TextEntry::make('customer_name'),
                TextEntry::make('customer_phone')->copyable(),
                TextEntry::make('locale')->label('Language'),
                TextEntry::make('created_at')->dateTime('d M Y, H:i'),
                TextEntry::make('total')->formatStateUsing(fn ($state) => Money::format($state)),

                RepeatableEntry::make('items')
                    ->label(__('shop.checkout.summary'))
                    ->schema([
                        TextEntry::make('product_name'),
                        TextEntry::make('size')->placeholder('—'),
                        TextEntry::make('qty'),
                        TextEntry::make('line_total')->formatStateUsing(fn ($state) => Money::format($state)),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}
