<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use App\Filament\Resources\PurchaseOrderResource\Widgets\HighValuePurchaseOrder;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
