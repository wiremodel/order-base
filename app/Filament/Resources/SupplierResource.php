<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Purchasing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->description('Core supplier details and contact information')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Supplier Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter supplier company name')
                                    ->prefixIcon('heroicon-o-building-office-2'),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Supplier')
                                    ->default(true)
                                    ->helperText('Inactive suppliers will not appear in selection lists'),
                            ]),
                    ]),

                Forms\Components\Section::make('Contact Details')
                    ->description('How to reach this supplier')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('contact_person')
                                    ->label('Primary Contact')
                                    ->maxLength(255)
                                    ->placeholder('Contact person name')
                                    ->prefixIcon('heroicon-o-user'),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('supplier@example.com')
                                    ->prefixIcon('heroicon-o-envelope'),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(255)
                                    ->placeholder('(555) 123-4567')
                                    ->prefixIcon('heroicon-o-phone'),
                                Forms\Components\Textarea::make('address')
                                    ->label('Business Address')
                                    ->maxLength(65535)
                                    ->placeholder('Full business address including city, state, and postal code')
                                    ->rows(3),
                            ]),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->description('Notes and other relevant details')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Textarea::make('notes')
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PurchaseOrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
