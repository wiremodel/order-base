<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;

class OrderItemSchema
{
    public static function get(): array
    {
        return [
            Grid::make(12)
                ->schema([
                    TextInput::make('quantity')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->default(1)
                        ->step(1)
                        ->prefixIcon('heroicon-m-calculator')
                        ->columnSpan(3)
                        ->label('Qty')
                        ->live(debounce: 500)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $unitCost = floatval($get('unit_cost') ?? 0);
                            $quantity = intval($state ?? 0);
                            $set('total_cost', number_format($quantity * $unitCost, 2, '.', ''));
                        }),

                    Select::make('menu_item_id')
                        ->relationship('menuItem', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->prefixIcon('heroicon-m-cube')
                        ->placeholder('Select item...')
                        ->columnSpan(9)
                        ->label('Item'),

                    TextInput::make('unit_cost')
                        ->numeric()
                        ->required()
                        ->prefix('$')
                        ->step(0.01)
                        ->minValue(0)
                        ->prefixIcon('heroicon-m-currency-dollar')
                        ->columnSpan(6)
                        ->label('Unit Cost')
                        ->live(debounce: 500)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $quantity = intval($get('quantity') ?? 0);
                            $unitCost = floatval($state ?? 0);
                            $set('total_cost', number_format($quantity * $unitCost, 2, '.', ''));
                        }),
                    TextInput::make('total_cost')
                        ->numeric()
                        ->disabled()
                        ->prefix('$')
                        ->dehydrated()
                        ->prefixIcon('heroicon-m-calculator')
                        ->columnSpan(6)
                        ->label('Total')
                        ->extraAttributes(['class' => 'font-semibold']),
                    Actions::make([

                    ])
                        ->columnSpan(2),
                ]),
            Textarea::make('notes')
                ->placeholder('Item notes...')
                ->rows(2)
                ->columnSpanFull()
                ->hiddenLabel(),
        ];
    }
}
