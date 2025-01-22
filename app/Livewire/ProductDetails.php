<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class ProductDetails extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;

    public $product;
    public $comments;

    public function mount($code, $slug)
    {

        $this->product = Product::withRelations()->where('code', $code)->firstOrFail();
        $this->loadComments();
    }

     public function loadComments()
    {
        $this->comments = Comment::where('product_id', $this->product->id)
            ->where('farmer_id', $this->product->farmer->id)
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();
    }
    public function render()
    {
        return view('livewire.product-details', [
            'product' => $this->product,
            'comments' => $this->comments,
        ]);
    }

    public function addToCartAction(): Action
    {
        return Action::make('addToCart')
            ->label('Add To cart')
            ->icon('heroicon-o-shopping-cart')
            ->size('xl')
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
    public function addMessageAction(): Action
    {
        return Action::make('addMessage')
            ->label('Message')
            ->size('xl')
            ->modalHeading('Add New Message')
            ->color('primary')
            ->icon('heroicon-o-chat-bubble-bottom-center-text')
            ->form([
                Textarea::make('content')
                    ->required()
                    ->label('Message Content')
                    ->columnSpanFull()
                    ->helperText('Write your message to the product owner here.')
                    ->rows(5),
            ])
            ->action(function (array $arguments, array $data) {
    
                try {
                    $record = Product::findOrFail($arguments['record']);
                    DB::beginTransaction();
                    $productId = $record->id;
                    $farmerId = $record->farmer->id;
                   $buyerId = Auth::user()->id;  
                    
                    // Assuming $arguments['product_id'], $arguments['buyer_id'], and $arguments['farmer_id'] are provided
                    $comment = Comment::create([
                        'product_id' => $productId,
                        'buyer_id' => $buyerId,
                        'farmer_id' => $farmerId,
                        'content' => $data['content'],
                    ]);
    
                    DB::commit();
                    $this->loadComments();
                    $this->dialog()->success(
                        title: 'Message Sent',
                        description: 'Your message has been successfully sent to the product owner.'
                    );
    
                } catch (\Exception $e) {
                    DB::rollBack();
    
                    $this->dialog()->error(
                        title: 'Error',
                        description: 'Failed to send the message. Please try again later. ' . $e->getMessage()
                    );
                }
            });
    }


    public function deleteMessageAction(): Action
{
    return Action::make('deleteMessage')
        ->label('Delete Message')
        ->iconButton()
        ->color('danger')
        ->icon('heroicon-o-trash')
        ->requiresConfirmation()
        ->modalHeading('Confirm Delete')
        ->modalDescription('Are you sure you want to delete this message? This action cannot be undone.')
        ->action(function (array $arguments) {
            try {
                $commentId = $arguments['record'];

                DB::beginTransaction();

                $comment = Comment::findOrFail($commentId);
                $comment->delete();

                DB::commit();

                // Reload the comments to update the UI dynamically
                $this->loadComments();

                $this->dialog()->success(
                    title: 'Message Deleted',
                    description: 'The message has been successfully deleted.'
                );
            } catch (\Exception $e) {
                DB::rollBack();

                $this->dialog()->error(
                    title: 'Error',
                    description: 'Failed to delete the message. Please try again later. ' . $e->getMessage()
                );
            }
        });
}

    
}
