<?php

use App\Livewire\CartView;
use App\Livewire\MyAddress;
use App\Livewire\PlaceOrder;
use App\Livewire\OrderHistory;
use App\Livewire\FarmerDetails;
use App\Livewire\BuyerDashboard;
use App\Livewire\ProductDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    // config('jetstream.auth_session'),
    // config('jetstream.auth_session'),
    'verified',
])->group(function () {


    Route::get('/dashboard', function () {

        switch(Auth::user()->role){
            case 'Admin':
                return redirect('/admin');
                break;
                case 'Buyer':
                return redirect()->route('buyer.dashboard',['name'=> Auth::user()->fullNameSlug()]);
                break;
            case 'Farmer':
                return redirect('/farmer');
                break;
            default:
                return redirect('/user-dashboard');
                break;
        }
        // if(Auth::user()->isAdmin()){
        //     return redirect('/admin');
        // }else{
        //     return redirect('/dashboard');
        // }
    })->name('dashboard');
    Route::get('/user-dashboard', function () { return view('dashboard');})->name('user.dashboard');

    Route::prefix('buyer')->name('buyer.')->group(function(){
        Route::get('/{name}', BuyerDashboard::class)->name('dashboard');
    });
    Route::get('/products/{code}/{slug}', ProductDetails::class)->name('product.details');

    //buyer
    Route::get('/{name}/cart', CartView::class)->name('cart.view')->middleware(['ensureNoPendingOrder',]);
    Route::get('/{name}/address', MyAddress::class)->name('address.index');
    Route::get('/{name}/place-order', PlaceOrder::class)->name('place.order')->middleware(['ensureHasDefaultLocation']);
    Route::get('/{name}/order-history', OrderHistory::class)->name('order.history');
    Route::get('/farmer/{farmerId}', FarmerDetails::class)->name('farmer.details');

});
