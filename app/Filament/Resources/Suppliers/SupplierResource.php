<?php

namespace App\Filament\Resources\Suppliers;

use App\Filament\Resources\Suppliers\Schemas\SupplierSchema;
use App\Filament\Resources\Suppliers\Tables\SupplierTable;
use Filament\Schemas\Schema;
use App\Filament\Resources\Suppliers\RelationManagers\PurchaseOrdersRelationManager;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Models\Supplier;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string | \UnitEnum | null $navigationGroup = 'Purchasing';

    public static function form(Schema $schema): Schema
    {
        return SupplierSchema::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierTable::configure($table);
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
