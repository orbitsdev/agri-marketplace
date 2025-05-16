<?php

use App\Models\User;
use App\Events\TestEvent;
use App\Livewire\CartView;
use App\Livewire\MyAddress;
use App\Livewire\PlaceOrder;
use App\Livewire\OrderHistory;
use App\Livewire\FarmerDetails;
use App\Livewire\BuyerDashboard;
use App\Livewire\ProductDetails;
use App\Livewire\Buyer\EditProfile;
use App\Livewire\FarmerNotApproved;
use App\Livewire\WaitingForApproval;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MessageCreated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Livewire\AccountIsDeactivatedPage;
use App\Http\Controllers\NotificationController;

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
    return redirect()->route('public.products');
});

// Public routes that don't require authentication
Route::get('/products', \App\Livewire\PublicProducts::class)->name('public.products');
Route::get('/public/products/{code}/{slug}', \App\Livewire\PublicProductDetails::class)->name('public.product.details');
Route::get('/public/farmer/{farmerId}', \App\Livewire\PublicFarmerDetails::class)->name('public.farmer.details');

Route::middleware([
    'auth:sanctum',
    // config('jetstream.auth_session'),
    // config('jetstream.auth_session'),
    'verified',
    'account.active'
])->group(function () {

// edit profile
Route::get('/edit/profile{record}', EditProfile::class)->name('edit.profile');

Route::get('/farmer/status', WaitingForApproval::class)->name('farmer.waiting-for-approval')->middleware(['farmer.check.approval']);


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


    Route::get('/reports/monthly-sales', [ReportController::class, 'exportMonthlySales'])->name('reports.monthly-sales');

    Route::get('/reports/yearly-sales', [ReportController::class, 'exportYearlySales'])->name('reports.yearly-sales');

    Route::get('/reports/total-products', [ReportController::class, 'exportTotalProducts'])->name('reports.total-products');

    Route::get('/reports/out-of-stock-products', [ReportController::class, 'exportOutOfStockProducts'])->name('reports.out-of-stock-products');


    Route::get('/reports/total-orders', [ReportController::class, 'exportTotalOrders'])->name('reports.total-orders');

    Route::get('/reports/orders-by-status', [ReportController::class, 'exportOrdersByStatus'])->name('reports.orders-by-status');
    
    // Printable Report Routes
    Route::get('/reports/printable/monthly-sales', [ReportController::class, 'printableMonthlySales'])->name('reports.printable.monthly-sales');
    Route::get('/reports/printable/yearly-sales', [ReportController::class, 'printableYearlySales'])->name('reports.printable.yearly-sales');
    Route::get('/reports/printable/total-products', [ReportController::class, 'printableTotalProducts'])->name('reports.printable.total-products');
    Route::get('/reports/printable/out-of-stock-products', [ReportController::class, 'printableOutOfStockProducts'])->name('reports.printable.out-of-stock-products');
    Route::get('/reports/printable/total-orders', [ReportController::class, 'printableTotalOrders'])->name('reports.printable.total-orders');
    Route::get('/reports/printable/orders-by-status', [ReportController::class, 'printableOrdersByStatus'])->name('reports.printable.orders-by-status');





});


Route::get('/report/farmer-documents/{farmer}', [ReportController::class, 'exportFarmerDocuments'])
->name('export.farmer.documents');

Route::get('/reports/printable/farmer-documents/{farmer}', [ReportController::class, 'printableFarmerDocuments'])
->name('reports.printable.farmer-documents');

Route::get('/reports/farmers-excel', [ReportController::class, 'exportFarmersExcel'])
->name('export.farmers.excel');

Route::get('/reports/printable/farmers', [ReportController::class, 'printableFarmers'])
->name('reports.printable.farmers');

Route::get('/account-deactivated', AccountIsDeactivatedPage::class)
    ->name('account.deactivated')
    ->middleware(['auth', 'redirect.if.active']);



    Route::get('/test-event', function () {
        // Retrieve a user instance (replace 1 with the actual user ID you want to test with)
        $user = Auth::user();

       

        // Trigger the event
        event(new TestEvent($user));
        return 'success';
    });


    Route::get('/test-example', function(){
        $user = Auth::user();

    $user->notify(new MessageCreated(
        'info',
        'New Message',
        'You have received a new message!',
        'Admin',
        $user->name,
        $user->id,
        $user,
        '/dashboard'
    ));

    return 'Notification sent!';
    });
