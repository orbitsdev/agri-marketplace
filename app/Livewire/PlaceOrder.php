<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FilamentForm;
use App\Models\OrderItem;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class PlaceOrder extends Component implements HasForms, HasActions
{

    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;


    public $groupedProcessing;

    public function mount()
    {
        $this->refreshOrder();
    }

    public function refreshOrder()
    {

        if (!Auth::user()->orders()->byStatus(Order::PROCESSING)->exists()) {
            return redirect()->route('cart.view', ['name' => Auth::user()->fullNameSlug()]);
        }
        $this->groupedProcessing = Order::where('buyer_id', Auth::id())
            ->where('status', Order::PROCESSING)
            ->with(['farmer', 'items.product']) // Eager load the farmer relationship
            ->get()
            ->groupBy(fn($order) => $order->farmer->id)->all(); // Group orders by farmer ID
    }

    public function render()
    {
        return view('livewire.place-order', [
            'groupedProcessing' => $this->groupedProcessing,
        ]);
    }

    public function removeOrderAction(): Action
    {
        return Action::make('removeOrder')
            ->label('Delete Order')
            ->iconButton()
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()

            ->modalHeading('Confirm Delete')
            ->modalDescription('Are you sure you want to delete this order including items ? This action cannot be undone.')
            ->action(function (array $arguments) {

                try {
                    $cartId = $arguments['record'];

                    DB::beginTransaction();


                    $order = Order::findOrFail($cartId);


                    $order->delete();


                    $this->refreshOrder();

                    DB::commit();

                    $this->dialog()->success(
                        title: 'Order deleted',
                        description: 'The order has been successfully removed from your cart.' // Change message to match the action (updated vs. deleted)
                    );

                    //chec if user doesnt have order where status is proccessing then redict to cart

                    if (!Auth::user()->orders()->byStatus(Order::PROCESSING)->exists()) {
                        return redirect()->route('cart.view', ['name' => Auth::user()->fullNameSlug()]);
                    }
                } catch (\Exception $e) {
                    DB::rollBack();

                    $this->dialog()->error(
                        title: 'Error',
                        description: 'Failed to remove the item. Please try again.'
                    );
                }
            });
    }

    public function removeItemAction(): Action
    {
        return Action::make('removeItem')
            ->label('Remove Item')
            ->iconButton()
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->requiresConfirmation()
            ->modalHeading('Confirm Removal')
            ->modalDescription('Are you sure you want to remove this item from the order? This action cannot be undone.')
            ->action(function (array $arguments) {

                try {
                    DB::beginTransaction();

                    $itemId = $arguments['record'];

                    // Find the item to delete
                    $orderItem = OrderItem::findOrFail($itemId);
                    $order = $orderItem->order;

                    // Delete the item
                    $orderItem->delete();

                    // Check if the order has no more items
                    if ($order->items()->count() === 0) {
                        $order->delete();

                        $this->dialog()->info(
                            title: 'Order Removed',
                            description: 'The order has been removed as it no longer contains any items.'
                        );
                    } else {
                        $this->dialog()->success(
                            title: 'Item Removed',
                            description: 'The item has been successfully removed from your order.'
                        );
                    }

                    $this->refreshOrder();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();

                    $this->dialog()->error(
                        title: 'Error',
                        description: 'Failed to remove the item. Please try again.'
                    );
                }
            });
    }



    public function editAddressAction(): EditAction
    {
        return EditAction::make('editAddress')
            ->iconButton()
            ->color('gray')
            ->record(function (array $arguments) {
                return Order::findOrFail($arguments['record']);
            })
            ->fillForm(function (array $arguments) {
                $record = Order::find($arguments['record']);
                return [
                    'region' => $record->region,
                    'province' => $record->province,
                    'city_municipality' => $record->city_municipality,
                    'barangay' => $record->barangay,
                    'street' => $record->street,
                    'zip_code' => $record->zip_code,
                    'payment_method' => $record->payment_method,
                ];
            })
            ->using(function (array $arguments, array $data): Model {

                try {
                    $record = Order::findOrFail($arguments['record']); // It's better to use findOrFail to throw an exception if not found

                    DB::beginTransaction();

                    $record->update($data); // Ensure $data is properly sanitized and validated

                    DB::commit();
                    $this->refreshOrder();
                    $this->dialog()->success(
                        title: 'Order Updated',
                        description: 'The item has been successfully updated in your cart.' // Change message to match the action (updated vs. deleted)
                    );

                    return $record;
                } catch (\Exception $e) {
                    DB::rollBack();

                    $this->dialog()->error(
                        title: 'Error',
                        description: 'Failed to update the item. Please try again.' // Adjusted message to reflect the actual action
                    );


                    \Log::error('Order update failed', ['error' => $e->getMessage()]);


                    throw $e;
                }
            })
            ->icon('heroicon-m-pencil-square')
            ->iconButton()
            ->form(FilamentForm::orderForm())
            ->label('Update Address')
            ->modalHeading('Update Order Details')


        ;
    }

    // create place order action
    public function placeOrderAction(): Action
    {
        return Action::make('placeOrder')
            ->label('Place Order')
            ->size('xl')
            ->color('primary')
            ->icon('heroicon-o-shopping-cart')
            ->requiresConfirmation()
            ->modalHeading('Confirm Order')
            ->modalDescription('Are you sure you want to place this order? This action cannot be undone.')
            ->action(function (array $arguments) {
                try {
                    DB::beginTransaction();

                    $cartId = $arguments['record'];
                    $order = Order::findOrFail($cartId);

                    $unavailableItems = [];
                    $availableItems = [];

                    // Check stock for each item
                    foreach ($order->items as $orderItem) {
                        $product = $orderItem->product;

                        if ($product->quantity < $orderItem->quantity) {
                            $unavailableItems[] = [
                                'product_name' => $product->product_name,
                                'available_stock' => $product->quantity,
                                'required_quantity' => $orderItem->quantity,
                            ];
                        } else {
                            $availableItems[] = $orderItem;
                        }
                    }

                    if (!empty($unavailableItems)) {
                        $warningDetails = $this->formatUnavailableItems($unavailableItems);

                        $this->dialog()->warning(
                            title: 'Some Items Are Unavailable',
                            description: "The following items cannot be included in your order due to insufficient stock:<br><ul style='margin-left: 1em; list-style-type: disc;'>{$warningDetails}</ul><br>Please update your order to proceed."
                        );

                        DB::rollBack();
                        return;
                    }

                    // Deduct stock for available items
                    foreach ($availableItems as $orderItem) {
                        $product = $orderItem->product;

                        $product->quantity -= $orderItem->quantity;
                        $product->save();
                    }

                    // Mark order as placed
                    $order->status = Order::PENDING;
                    $order->save();

                    DB::commit();

                    $this->dialog()->success(
                        title: 'Order Placed',
                        description: 'Your order has been successfully placed.'
                    );

                    return redirect()->route('order.history', ['name' => Auth::user()->fullNameSlug()]);
                } catch (\Exception $e) {
                    DB::rollBack();

                    $this->dialog()->error(
                        title: 'Error',
                        description: 'An error occurred while placing the order. Please try again.' . $e->getMessage(),
                    );
                }
            });
    }

    public function updateOrderForUnavailableItems($orderId, $unavailableItems)
    {
        try {
            $order = Order::findOrFail($orderId);

            foreach ($unavailableItems as $item) {
                $orderItem = $order->items()
                    ->whereHas('product', fn($query) => $query->where('product_name', $item['product_name']))
                    ->first();

                if ($orderItem) {
                    $orderItem->delete(); // Remove unavailable items from the order
                }
            }

            $order->refresh();

            $this->dialog()->success(
                title: 'Order Updated',
                description: 'The order has been updated to exclude unavailable items.'
            );

            $this->refreshOrder(); // Refresh the Livewire data
        } catch (\Exception $e) {
            $this->dialog()->error(
                title: 'Error',
                description: 'Failed to update the order. Please try again.'
            );
        }
    }

    protected function formatUnavailableItems(array $unavailableItems): string
    {
        $formatted = '';

        foreach ($unavailableItems as $item) {
            $formatted .= "<li><strong>{$item['product_name']}</strong>: Available: {$item['available_stock']}, Required: {$item['required_quantity']}</li>";
        }

        return $formatted;
    }


    // create action for update quantity
    public function updateQuantityAction(): Action
    {
        return Action::make('updateQuantity')
            ->label('Update Quantity')
            ->iconButton()
            ->icon('heroicon-o-pencil-square')
            ->color('primary')
            ->form([
                TextInput::make('quantity')
                    ->numeric()
                    ->minValue(1)
                    ->label('Quantity')
                    ->required(),
            ])
            ->action(function (array $arguments, array $data) {
                try {
                    DB::beginTransaction();

                    $orderItem = OrderItem::findOrFail($arguments['record']);

                    $quantity = $data['quantity'];

                    if ($quantity < 1) {
                        $this->dialog()->error(
                            title: 'Invalid Quantity',
                            description: 'Quantity must be greater than 0.'
                        );
                        return;
                    }

                    $orderItem->quantity = $quantity;
                    $orderItem->save();

                    DB::commit();

                    $this->refreshOrder();

                    $this->dialog()->success(
                        title: 'Quantity Updated',
                        description: 'The quantity has been successfully updated in your order.'
                    );
                } catch (\Exception $e) {
                    DB::rollBack();

                    $this->dialog()->error(
                        title: 'Error',
                        description: 'Failed to update the quantity. Please try again.'
                    );
                }
            })
            ->modalHeading('Update Quantity');

    }
}
