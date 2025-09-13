<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Filament\Resources\PurchaseOrders\Widgets\HighValuePurchaseOrder;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function afterCreate(): void
    {
        if (HighValuePurchaseOrder::isNeededToShow($this->record)) {
            Session::flash(HighValuePurchaseOrder::TRIGGER);
        }
    }
}
