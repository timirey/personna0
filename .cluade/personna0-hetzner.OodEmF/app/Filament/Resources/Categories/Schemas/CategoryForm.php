<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('admin.fields.name'))
                    ->required()
                    ->maxLength(120)
                    ->live(debounce: 500)
                    ->afterStateUpdated(function ($state, $set) {
                        $set('slug', Str::slug((string) $state));
                    }),

                Hidden::make('slug')
                    ->required()
                    ->rule('max:140')
                    ->unique(ignoreRecord: true),

                Toggle::make('is_active')
                    ->label(__('admin.fields.active'))
                    ->default(true),
            ]);
    }
}
