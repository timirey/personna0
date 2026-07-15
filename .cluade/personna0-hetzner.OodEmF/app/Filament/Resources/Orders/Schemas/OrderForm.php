<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Support\Money;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        $statuses = Collection::make(OrderStatus::cases())
            ->mapWithKeys(fn (OrderStatus $status) => [$status->value => $status->getLabel()])
            ->all();

        return $schema
            ->components([
                TextInput::make('reference')
                    ->label(__('admin.fields.reference'))
                    ->disabled(),

                Select::make('status')
                    ->label(__('admin.fields.status'))
                    ->options($statuses)
                    ->required()
                    ->native(false),

                TextInput::make('customer_name')
                    ->label(__('admin.fields.customer'))
                    ->required()
                    ->maxLength(120),

                TextInput::make('customer_phone')
                    ->label(__('admin.fields.phone'))
                    ->required()
                    ->maxLength(40),

                Repeater::make('items')
                    ->label(__('admin.order.items'))
                    ->relationship()
                    ->schema([
                        TextInput::make('product_name')
                            ->label(__('admin.fields.product'))
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('size')
                            ->label(__('admin.fields.sizes')),

                        TextInput::make('qty')
                            ->label(__('admin.fields.qty'))
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        TextInput::make('unit_price')
                            ->label(__('admin.fields.unit_price'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),

                        TextInput::make('line_total')
                            ->label(__('admin.fields.line_total'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                TextInput::make('total')
                    ->label(__('admin.fields.total'))
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->prefix(Money::currency()),

                Textarea::make('admin_notes')
                    ->label(__('admin.fields.notes'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
