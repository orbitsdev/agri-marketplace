<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Filament\Actions\Action;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use WireUi\Traits\WireUiActions;

class BuyerDashboard extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;
    use WithPagination;

    public $search = '';
    public $category = ''; // Current category filter

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::all(); // Fetch all categories

        $products = Product::published()
        // hasQuantity()
        ->approveFarmer()
            ->withRelations()
            ->available()
            ->when($this->search, function ($query) {
                $query->where('product_name', 'like', '%' . $this->search . '%')
                      ->orWhere('short_description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->paginate(10);

        return view('livewire.buyer-dashboard', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function addToCartAction(): Action
    {
        return Action::make('addToCart')
            ->color('gray')
            ->size('md')
            ->form([
                TextInput::make('quantity')
                    ->default(1)
                    ->required()
                    ->mask(9999)
                    ->minValue(1)
                    ->maxValue(10000),
            ])
            ->modalHeading(function (array $arguments) {
                $record = Product::find($arguments['record']);
                return $record->nameWithPrice() ?? 'Add Product';
            })
            ->action(function (array $data, array $arguments) {
                $quantity = (int) $data['quantity'];
                $productId = Product::findOrFail($arguments['record'])->id;
                $buyerId = auth()->id();

                try {
                    DB::beginTransaction();

                    $cartItem = Cart::where('buyer_id', $buyerId)
                        ->where('product_id', $productId)
                        ->first();

                    if ($cartItem) {
                        $cartItem->quantity += $quantity;
                        $cartItem->save();
                    } else {
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

                    $this->dialog()->show([
                        'icon' => 'success',
                        'title' => 'Success',
                        'description' => 'The product has been added to your cart successfully!',
                    ]);

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
