<?php

namespace App\Filament\Resources\PurchaseOrders\Widgets;

use App\Filament\Resources\PurchaseOrders\Widgets\HighValuePurchaseOrder;
use App\Models\PurchaseOrder;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class HighValuePurchaseOrder extends Widget
{
    public const ID = 'high-value-purchase-order-modal';

    public const TRIGGER = 'trigger-high-value-purchase-order-modal';

    protected static ?int $sort = 99;

    protected static bool $isLazy = false;

    public bool $shouldTriggerOnMount = false;

    public ?Model $record = null;

    protected string $view = 'filament.resources.purchase-order-resource.widgets.high-value-purchase-order';

    public function mount(): void
    {
        if (Session::has(HighValuePurchaseOrder::TRIGGER)) {
            $this->shouldTriggerOnMount = true;
        }
    }

    public static function isNeededToShow(PurchaseOrder $purchaseOrder): bool
    {
        $purchaseOrder->refresh();

        if ($purchaseOrder->total_amount > 5_000_00) {
            return true;
        }

        return false;
    }
}
