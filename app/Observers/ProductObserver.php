<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $regionCode = 'REG'; // Example static region code
        $farmerId = str_pad($product->farmer_id, 3, '0', STR_PAD_LEFT);
        $randomPart = strtoupper(Str::random(6)); // 6-character random string

        $product->code = "prod-{$regionCode}-{$farmerId}-{$randomPart}";
        $product->save();
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
