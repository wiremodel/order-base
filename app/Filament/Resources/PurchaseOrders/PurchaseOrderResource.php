<?php

namespace App\Filament\Resources\PurchaseOrders;

use App\Filament\Resources\PurchaseOrders\Schemas\PurchaseOrderForm;
use App\Filament\Resources\PurchaseOrders\Tables\PurchaseOrdersTable;
use Filament\Schemas\Schema;
use App\Filament\Resources\PurchaseOrders\Pages\ListPurchaseOrders;
use App\Filament\Resources\PurchaseOrders\Pages\CreatePurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Pages\EditPurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Widgets\HighValuePurchaseOrder;
use App\Models\PurchaseOrder;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string | \UnitEnum | null $navigationGroup = 'Purchasing';

    public static function form(Schema $schema): Schema
    {
        return PurchaseOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        PurchaseOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            HighValuePurchaseOrder::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseOrders::route('/'),
            'create' => CreatePurchaseOrder::route('/create'),
            'edit' => EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
