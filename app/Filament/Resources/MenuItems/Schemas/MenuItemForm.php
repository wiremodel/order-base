<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Toggle::make('is_available')
                    ->required()
                    ->default(true),
                TextInput::make('image_url')
                    ->url()
                    ->maxLength(255),
                KeyValue::make('dietary_info')
                    ->label('Dietary Information')
                    ->keyLabel('Type')
                    ->valueLabel('Value')
                    ->columnSpanFull(),
            ]);
    }

}
