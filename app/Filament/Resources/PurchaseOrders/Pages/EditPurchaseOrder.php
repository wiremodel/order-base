<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Filament\Resources\PurchaseOrders\Widgets\HighValuePurchaseOrder;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            HighValuePurchaseOrder::class,
        ];
    }

    protected function afterSave(): void
    {
        if (HighValuePurchaseOrder::isNeededToShow($this->record)) {
            $this->dispatch('open-modal', id: HighValuePurchaseOrder::ID);
        }
    }
}
