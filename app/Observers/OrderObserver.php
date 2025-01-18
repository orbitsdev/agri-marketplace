<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $regionCode = 'PH'; // Example: Static region code
        $datePart = now()->format('Ymd'); // Current date (e.g., 20250107)
        $farmerId = str_pad($order->farmer_id, 3, '0', STR_PAD_LEFT); // Farmer ID padded to 3 digits
        $sequential = Order::whereDate('created_at', now())
            ->where('farmer_id', $order->farmer_id)
            ->count() + 1; // Count today's orders for this farmer + 1

        $order->order_number = "{$regionCode}-{$datePart}-F{$farmerId}-" . str_pad($sequential, 3, '0', STR_PAD_LEFT);
     
        $a = $order->load('items'); // Ensure the items relationship is loaded
    $order->total = $order->items->sum(function($item){
        return $item->quantity * $item->price_per_unit;
    });
    $order->saveQuietly();
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $order->load('items'); // Ensure the items relationship is loaded
        dd($order);
        $a = $order->total = $order->items->sum(function($item){
            return $item->quantity * $item->price_per_unit;
        });
    $order->saveQuietly();
    dd($a);

    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
