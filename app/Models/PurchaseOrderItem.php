<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'menu_item_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'integer',
        'total_cost' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($item) {
            $item->total_cost = $item->quantity * $item->unit_cost;
        });

        static::saved(function ($item) {
            $item->purchaseOrder?->calculateTotal();
        });

        static::deleted(function ($item) {
            $item->purchaseOrder?->calculateTotal();
        });
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    protected function unitCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function totalCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
