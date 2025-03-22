<?php

namespace App\Observers;

use App\Models\Order;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
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

        $order->save();
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->status === 'Pending') {
            $this->notifyFarmer($order);
        }



    }
    protected function notifyFarmer(Order $order): void
    {
        $farmer = $order->farmer;
        $buyer = $order->buyer;

        Notification::make()
            ->title("New Order Received - {$order->order_number}")
            ->body("{$buyer->full_name} has placed an order for a total of PHP " . number_format($order->total, 2) . "")
            ->sendToDatabase($farmer->user,isEventDispatched: true);
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
