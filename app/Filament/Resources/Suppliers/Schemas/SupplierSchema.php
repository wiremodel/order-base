<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Core supplier details and contact information')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        TextInput::make('name')
                            ->label('Supplier Name')
                            ->required()
                            ->autofocus()
                            ->maxLength(255)
                            ->placeholder('Enter supplier company name')
                            ->prefixIcon('heroicon-o-building-office-2'),
                        Toggle::make('is_active')
                            ->label('Active Supplier')
                            ->columnStart(1)
                            ->default(true)
                            ->helperText('Inactive suppliers will not appear in selection lists'),
                    ]),

                Section::make('Contact Details')
                    ->description('How to reach this supplier')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('contact_person')
                                    ->label('Primary Contact')
                                    ->maxLength(255)
                                    ->placeholder('Contact person name')
                                    ->prefixIcon('heroicon-o-user'),
                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('supplier@example.com')
                                    ->prefixIcon('heroicon-o-envelope'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(255)
                                    ->placeholder('(555) 123-4567')
                                    ->prefixIcon('heroicon-o-phone'),
                                Textarea::make('address')
                                    ->label('Business Address')
                                    ->maxLength(65535)
                                    ->placeholder('Full business address including city, state, and postal code')
                                    ->rows(3),
                            ]),
                    ]),

                Section::make('Additional Information')
                    ->description('Notes and other relevant details')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Textarea::make('notes')
                            ->label('Internal Notes')
                            ->maxLength(65535)
                            ->placeholder('Payment terms, delivery preferences, special instructions, etc.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
