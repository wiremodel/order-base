<?php

namespace App\Filament\Resources\Suppliers;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Suppliers\RelationManagers\PurchaseOrdersRelationManager;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string | \UnitEnum | null $navigationGroup = 'Purchasing';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Core supplier details and contact information')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Grid::make()
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('contact_person')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PurchaseOrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuppliers::route('/'),
            'create' => CreateSupplier::route('/create'),
            'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }
}
