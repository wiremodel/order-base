<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PurchaseOrderResource;
use App\Filament\Resources\PurchaseOrderResource\Widgets\HighValuePurchaseOrder;

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
