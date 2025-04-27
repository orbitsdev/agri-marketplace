<?php

namespace App\Observers;

use App\Http\Controllers\NotificationController;
use App\Models\Order;
use App\Notifications\MessageCreated;
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
        }else{

            if (in_array($order->status, [
                'Confirmed',
                'Shipped',
                'Out for Delivery',
                'Completed',
                'Cancelled',
                'Returned',

            ])) {
                $this->notifyBuyer($order);
            }


        }

        if ($order->status === 'Confirmed') {
            $this->checkProductStockLevels($order);
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

    public function notifyBuyer(Order $order): void
    {
        $farmer = $order->farmer;
        $buyer = $order->buyer;

        $farmerName = $farmer->user->full_name ?? 'Farmer';
        $farmName = $farmer->farm_name ? " ({$farmer->farm_name})" : '';

        switch ($order->status) {
            case 'Confirmed':
                $message = "Your order #{$order->order_number} has been confirmed by {$farmerName}{$farmName}.";
                break;

            case 'Shipped':
                $message = "Good news! Your order #{$order->order_number} has been shipped by {$farmerName}{$farmName}.";
                break;

            case 'Out for Delivery':
                $message = "Your order #{$order->order_number} is currently out for delivery. Please prepare to receive it!";
                break;

            case 'Completed':
                $message = "Your order #{$order->order_number} has been successfully completed. Thank you for shopping with us!";
                break;

            case 'Cancelled':
                $message = "We regret to inform you that your order #{$order->order_number} has been cancelled by {$farmerName}{$farmName}.";
                break;

            case 'Returned':
                $message = "Your order #{$order->order_number} has been returned. Please check your account for updates.";
                break;

            default:
                $message = "There is an update regarding your order #{$order->order_number}.";
        }

        $buyer->notify(new MessageCreated(
            'order_status',
            $message,
            '',
            $farmerName,
            $buyer->full_name,
            $farmer->id,
            $buyer,
            route('order.history', [
                'name' => $buyer->fullNameSlug(),
                'status' => $order->status,
            ])
        ));
    }


    protected function checkProductStockLevels(Order $order): void
{
    foreach ($order->items as $item) { // assuming 'items' relationship exists
        $product = $item->product;

        if (!$product) {
            continue;
        }

        if ($product->quantity <= $product->alert_level) {
            $farmer = $product->farmer;

            if ($farmer && $farmer->user) {
                Notification::make()
                    ->title("Low Stock Alert: {$product->code} - {$product->product_name}")
                    ->body("Your product '{$product->product_name}' ({$product->code}) is running low on stock. Only {$product->quantity} left!")
                    ->sendToDatabase($farmer->user, isEventDispatched: true);
            }
        }
    }
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
