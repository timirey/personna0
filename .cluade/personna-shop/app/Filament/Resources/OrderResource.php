<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function canCreate(): bool
    {
        return false; // orders come from the storefront only
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'new')->count() ?: null;
    }

    protected static array $statuses = [
        'new'       => 'New',
        'confirmed' => 'Confirmed',
        'shipped'   => 'Shipped',
        'done'      => 'Done',
        'cancelled' => 'Cancelled',
    ];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order')
                ->schema([
                    Forms\Components\TextInput::make('reference')->disabled(),
                    Forms\Components\Select::make('status')
                        ->options(static::$statuses)
                        ->required()
                        ->native(false),
                ])
                ->columns(2),

            Forms\Components\Section::make('Customer')
                ->schema([
                    Forms\Components\TextInput::make('customer_name')->disabled(),
                    Forms\Components\TextInput::make('customer_phone')->disabled(),
                    Forms\Components\TextInput::make('customer_email')->disabled(),
                    Forms\Components\TextInput::make('total')
                        ->disabled()
                        ->formatStateUsing(fn ($state) => number_format((float) $state, 2) . ' ' . config('shop.currency')),
                    Forms\Components\Textarea::make('address')->disabled()->columnSpanFull(),
                    Forms\Components\Textarea::make('notes')->disabled()->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->copyable(),

                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2) . ' ' . config('shop.currency'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new'       => 'warning',
                        'confirmed' => 'info',
                        'shipped'   => 'primary',
                        'done'      => 'success',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => static::$statuses[$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(static::$statuses),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view'  => Pages\ViewOrder::route('/{record}'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
