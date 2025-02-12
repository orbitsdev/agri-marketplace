<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use Livewire\Component;
use App\Models\Location;
use App\Models\OrderItem;
use Filament\Actions\Action;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class CartView extends Component  implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;

    public $addresses = [];
    public $regions = [];
    public $provinces = [];
    public $cities = [];
    public $barangays = [];
    public $selectedRegion = null;
    public $selectedProvince = null;
    public $selectedCity = null;



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
            'user' => Auth::user(),
        ]);
    }


    public function checkoutCartAction(): Action
    {
        return Action::make('checkoutCart')
            ->label('Checkout')
            ->size('xl')
            // ->requiresConfirmation()
            // ->modalHeading('Checkout Cart')
            // ->modalDescription('')
            ->action(function () {

                try {
                    DB::beginTransaction();




                    $cartItemsGroupedByFarmer = Cart::isSelected()->groupedByFarmerId(Auth::id());
                    $buyer = Auth::user();
                    $buyerId = $buyer->id;

                    if(!$buyer->hasDefaultLocation()){
                        // please set default location first
                        $this->dialog()->error(
                            title: 'No default location Found',
                            description: 'Please set a default location first before checkout.'

                        );
                        return ;
                    }

                    //
                    $defaultLocation = $buyer->getDefaultLocation();

                    foreach ($cartItemsGroupedByFarmer as $farmerId => $cartItems) {
                        // Calculate the total for the order
                        $orderTotal = $cartItems->sum(fn($item) => $item->quantity * $item->price_per_unit);

                        // Create the order with location details if a default location exists
                        $order = Order::create([
                            'buyer_id' => $buyerId,
                            'farmer_id' => $farmerId,
                            'region' => $defaultLocation->region ?? null,
                            'province' => $defaultLocation->province ?? null,
                            'city_municipality' => $defaultLocation->city_municipality ?? null,
                            'barangay' => $defaultLocation->barangay ?? null,
                            'street' => $defaultLocation->street ?? null,
                            'zip_code' => $defaultLocation->zip_code ?? null,
                            'phone' => $defaultLocation->phone ?? null,
                            'total' => $orderTotal,
                            'status' => Order::PROCESSING,
                            'payment_method' => Order::PAYMENT_COD
                        ]);

                        // Create the order items
                        foreach ($cartItems as $cartItem) {
                            OrderItem::create([
                                'order_id' => $order->id,
                                'product_id' => $cartItem->product_id,
                                'product_name' => $cartItem->product->product_name,
                                'product_description' => $cartItem->product->description,
                                'short_description' => $cartItem->product->short_description,
                                'product_price' => $cartItem->price_per_unit,
                                'product_quantity' => $cartItem->quantity,
                                'quantity' => $cartItem->quantity,
                                'price_per_unit' => $cartItem->price_per_unit,
                                'subtotal' => $cartItem->quantity * $cartItem->price_per_unit,


                            ]);
                        }

                        // Update the order total (if needed, for additional calculations)
                        $order->updateTotal();

                        // Remove the processed cart items
                        Cart::whereIn('id', $cartItems->pluck('id'))->delete();
                    }

                    DB::commit();
                    $this->refreshCart();
                    $this->dispatch('cart.updated');

                    // $this->dialog()->success(
                    //     title: 'Checkout Successful',
                    //     description: 'Your orders have been created successfully, and the cart has been updated!'
                    // );

                    return redirect()->route('place.order', ['name'=> Auth::user()->fullNameSlug()]); // Redirect to the orders page
                } catch (\Exception $e) {
                    DB::rollBack();

                    $this->dialog()->error(
                        title: 'Error',
                        description: 'An error occurred during checkout. Please try again. ' . $e->getMessage()
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

    public function addAddressAction(): Action
    {
        return Action::make('addAddress')
            ->label('New Address')
            ->icon('heroicon-m-plus')
            ->modalHeading('Add New Address')
            ->color('info')
            ->fillForm(function(){
                return [
                
                    'phone' => Auth::user()->phone ?? null,
                
                ];
            })
            ->form(FilamentForm::locationForm())
            ->action(function (array $data) {
                DB::beginTransaction();

                try {
                    if ($data['is_default']) {
                        Location::where('user_id', auth()->id())->where('is_default', true)->update(['is_default' => false]);
                    }

                    // Fetch the selected region, province, city, and barangay data
                    // $region = collect($this->regions)->firstWhere('code', $data['region']);
                    // $province = collect($this->provinces)->firstWhere('code', $data['province']);
                    // $city = collect($this->cities)->firstWhere('code', $data['city']);
                    // $barangay = collect($this->barangays)->firstWhere('code', $data['barangay']);

                    // Create the new address
                    Location::create([
                        'user_id' => auth()->id(),
                        'region' => $data['region'] ?? null,
                        // 'region_code' => $region['code'] ?? null,
                        'province' => $data['province'] ?? null,
                        // 'province_code' => $province['code'] ?? null,
                        'city_municipality' => $data['city_municipality'] ?? null,
                        // 'city_code' => $city['code'] ?? null,
                        'barangay' => $data['barangay'] ?? null,
                        // 'barangay_code' => $barangay['code'] ?? null,
                        'street' => $data['street'],
                        'zip_code' => $data['zip_code'],
                        'is_default' => $data['is_default'],
                    ]);


                    DB::commit();

                    $this->addresses = auth()->user()->getAddresses();
                    $this->refreshCart();
                    // $this->dispatch('cart.updated');

                    $this->dialog()->success(
                        title: 'Address Added',
                        description: 'The new address has been successfully added.'
                    );
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->handleError('Error adding address', $e);
                }
            });
    }
}
