<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Collection;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        $statuses = Collection::make(OrderStatus::cases())
            ->mapWithKeys(fn (OrderStatus $status) => [$status->value => $status->getLabel()])
            ->all();

        return [
            Action::make('changeStatus')
                ->label(__('admin.fields.status'))
                ->icon('heroicon-o-arrow-path')
                ->schema([
                    Select::make('status')
                        ->label(__('admin.fields.status'))
                        ->options($statuses)
                        ->required(),
                ])
                ->fillForm(fn (Order $record) => ['status' => $record->status->value])
                ->action(fn (array $data, Order $record) => $record->update(['status' => $data['status']])),
        ];
    }
}
