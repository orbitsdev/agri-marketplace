<?php

namespace App\Observers;

use App\Models\OrderItem;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
        //
    }

    /**
     * Handle the OrderItem "updated" event.
     */
    public function updated(OrderItem $orderItem): void
    {
        $this->updateOrderTotal($orderItem);
    }

    /**
     * Handle the OrderItem "deleted" event.
     */
    public function deleted(OrderItem $orderItem): void
    {
        $this->updateOrderTotal($orderItem);
    }

    /**
     * Handle the OrderItem "restored" event.
     */
    public function restored(OrderItem $orderItem): void
    {
        //
    }

    /**
     * Handle the OrderItem "force deleted" event.
     */
    public function forceDeleted(OrderItem $orderItem): void
    {
        //
    }

    protected function updateOrderTotal(OrderItem $orderItem): void
    {
        $order = $orderItem->order;

        if ($order) {
            $order->updateTotal(); // Call the `updateTotal` method in the Order model
        }
    }

}
