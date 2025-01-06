<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use Filament\Actions\Action;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use WireUi\Traits\WireUiActions;
class BuyerDashboard extends Component implements  HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;
    use WithPagination;

    public function render()
    {
        $products = Product::hasQuantity()->withRelations()->paginate(perPage: 10);

        return view('livewire.buyer-dashboard', ['products' => $products]);
    }

    public function addToCartAction(): Action
    {
        return Action::make('addToCart')
            // ->color('primary')

            // ->requiresConfirmation()
            ->form([


                TextInput::make('quantity')
                ->default(1)
                ->required()
                ->mask(9999)
                ->minValue(1)       // Minimum value of 1
                ->maxValue(10000),
            ])
            ->modalHeading(function (array $arguments) {
                $record = Product::find($arguments['record']);
                return $record->nameWithPrice()  ?? 'Add Product';
            })
            ->action(function (array $data, array $arguments) {


                $quantity = (int)$data['quantity'];
                $productId = Product::findOrFail($arguments['record'])->id;
                $buyerId = auth()->id();

                try {
                    DB::beginTransaction();

                    // Check if the product is already in the cart for the current buyer
                    $cartItem = Cart::where('buyer_id', $buyerId)
                        ->where('product_id', $productId)
                        ->first();

                    if ($cartItem) {
                        // If it exists, update the quantity
                        $cartItem->quantity += $quantity;
                        $cartItem->save();
                    } else {
                        // If it does not exist, create a new cart record
                        $product = Product::findOrFail($productId);

                        Cart::create([
                            'buyer_id' => $buyerId,
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'price_per_unit' => $product->price,
                        ]);
                    }

                    DB::commit();
                    $this->dispatch('cart.updated');

                    // FilamentForm::notification('Product added to cart successfully!');
                    $this->dialog()->show([
                        'icon' => 'success',
                        'title' => 'Success',
                        'description' => 'The product has been added to your cart successfully!',
                    ]);

                    return redirect()->back()->with('success', 'Product added to cart successfully!');
                } catch (\Exception $e) {
                    DB::rollBack();

                    $this->dialog()->show([
                        'icon' => 'error',
                        'title' => 'Error',
                        'description' => 'Failed to add the product to your cart. Please try again later.',
                    ]);

                }
            });
    }
}
