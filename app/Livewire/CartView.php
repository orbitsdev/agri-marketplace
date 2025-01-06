<?php

namespace App\Livewire;

use App\Models\Cart;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use WireUi\Traits\WireUiActions;
class CartView extends Component  implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;

    public $cartItems;
    public $totalSelectedItems = 0;
    public $totalSelectedValue = 0.0;
    public $totalCartItem = 0;

    public $loadingItem = null;
    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {

        $this->cartItems = Cart::groupedByFarmer(Auth::id());

         $this->updateSummary();
    }

    public function updateSummary()
    {
        $this->totalSelectedItems = Cart::selected()
            ->where('buyer_id', Auth::id())
            ->sum('quantity');

        $this->totalSelectedValue = Cart::selected()
            ->where('buyer_id', Auth::id())
            ->sum(DB::raw('quantity * price_per_unit'));

        $this->totalCartItem = Cart::selectedCartItems(Auth::id())->count();
    }

    public function removeItem($cartId)
    {
        try {
            $cartItem = Cart::findOrFail($cartId);
            $cartItem->delete();

            $this->refreshCart();

            $this->dialog()->success(
                title: 'Item Removed',
                description: 'The item has been removed from your cart successfully.'
            );
        } catch (\Exception $e) {
            $this->dialog()->error(
                title: 'Error',
                description: 'Failed to remove the item. Please try again.'
            );
        }
    }

    public function updateQuantity($cartId, $quantity)
    {
        $this->loadingItem = $cartId;

        if ($quantity < 1) {
            $this->dialog()->error(
                title: 'Invalid Quantity',
                description: 'Quantity must be at least 1.'
            );
            $this->loadingItem = null;
            return;
        }

        try {
            $cartItem = Cart::findOrFail($cartId);
            $cartItem->quantity = $quantity;
            $cartItem->save();

            $this->refreshCart();
        } catch (\Exception $e) {
            $this->dialog()->error(
                title: 'Error',
                description: 'Failed to update the quantity. Please try again.'
            );
        } finally {
            $this->loadingItem = null;
        }
    }


public function toggleSelect($cartId)
{
    $this->loadingItem = $cartId;
    try {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->is_selected = !$cartItem->is_selected;
        $cartItem->save();

        $this->refreshCart();
    } catch (\Exception $e) {
        $this->dialog()->error(
            title: 'Error',
            description: 'Failed to update the item selection. Please try again.'
        );
    } finally {
        $this->loadingItem = null;
    }
}


    public function render()
    {

        return view('livewire.cart-view', [
            'cartItems' => $this->cartItems,
            'totalSelectedItems' => $this->totalSelectedItems,
            'totalSelectedValue' => $this->totalSelectedValue,
            'totalCartItem' => $this->totalCartItem,
        ]);
    }


    public function checkoutCartAction(): Action
    {
        return Action::make('checkoutCart')
        ->label('Checkout')
            ->size('xl')
            ->requiresConfirmation()
            ->modalHeading('Checkout Cart')
            ->modalDescription('')
            ->action(function (array $data, ) {



                try {
                    DB::beginTransaction();



                    DB::commit();



                    return redirect()->back()->with('success', 'Product added to cart successfully!');
                } catch (\Exception $e) {
                    DB::rollBack();



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
        ->requiresConfirmation()
        ->modalHeading('Confirm Removal')
        ->modalDescription('Are you sure you want to remove this item from the cart? This action cannot be undone.')
        ->action(function (array $arguments) {

            try {
                $cartId = $arguments['record'];

                DB::beginTransaction();


                $cartItem = Cart::findOrFail($cartId);


                $cartItem->delete();


                $this->refreshCart();

                DB::commit();
                $this->dispatch('cart.updated');
                $this->dialog()->success(
                    title: 'Item Removed',
                    description: 'The item has been successfully removed from your cart.'
                );
            } catch (\Exception $e) {
                DB::rollBack();

                $this->dialog()->error(
                    title: 'Error',
                    description: 'Failed to remove the item. Please try again.'
                );
            }
            });
        }
}
