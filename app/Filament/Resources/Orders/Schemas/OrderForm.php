<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
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

                Textarea::make('admin_notes')
                    ->label(__('admin.fields.notes'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
