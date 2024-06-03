<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController\ProductController;
use App\Http\Controllers\AdminController\SettingController;
use App\Http\Controllers\AdminController\ShippingController;
use App\Http\Controllers\AdminController\OrderController;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Support\Facades\Session;

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

// landing page
Route::get('/', function(){
    $products   = Product::orderBy('id', 'desc')->where('status', 1)->get();
    $shippings  = Shipping::where('status', 1)->get();
    $cart       = session()->get('cart', []);
    return view('lan_page.page', compact('products', 'shippings', 'cart'));
});


// admin dashboard
Route::group(['prefix' => '/admin', 'middleware' => 'auth'], function(){

    Route::get('/dashboard', function(){
        return view('backend.pages.dashboard');
    })->name('dashboard');

    // Products
    Route::controller(ProductController::class)->group(function(){
        Route::group(['prefix' => '/product',], function(){
            Route::get('/manage', 'manage')->name('manage.product');
            Route::get('/add', 'add')->name('add.product');
            Route::post('/store', 'store')->name('store.product');
            Route::get('/edit/{id}', 'edit')->name('edit.product');
            Route::get('/show/{slug}', 'show')->name('show.product');
            Route::put('/update/{id}', 'update')->name('update.product');
            Route::put('/update-status/{id}', 'activeStatus')->name('update.status.product');
            Route::delete('/delete/{id}', 'destroy')->name('delete.product');
        });
    });

    // settings
    Route::controller(SettingController::class)->group(function(){
        Route::group(['prefix' => '/settings',], function(){
            Route::get('/manage', 'manage')->name('manage.settings');
            Route::post('/update', 'update')->name('update.settings');
        });
    });

    // shipping
    Route::controller(ShippingController::class)->group(function(){
        Route::group(['prefix' => '/shipping',], function(){
            Route::get('/manage', 'manage')->name('manage.shipping');
            Route::post('/add', 'add')->name('add.shipping');
            Route::put('/update-status/{id}', 'activeStatus')->name('update.status.shipping');
            Route::delete('/delete/{id}', 'destroy')->name('delete.shipping');
        });
    });

    // order
    Route::controller(OrderController::class)->group(function(){
        Route::group(['prefix' => '/order',], function(){
            Route::get('/manage', 'manage')->name('manage.order');
            Route::get('/details/{id}', 'details')->name('details.order');
            Route::post('/update-status/{id}', 'update')->name('update.status');
            Route::delete('/delete/{id}', 'destroy');
            Route::get('/today', 'today')->name('today.order');
            Route::get('/month', 'month')->name('month.order');
            Route::get('/year', 'year')->name('year.order');
            // others monthly expenses
            Route::get('/monthly-order/{month}', 'monthlyOrder')->name('monthly.order');
            Route::get('/monthly-day-order', 'monthlyDayOrder')->name('monthly.day.order');
            // pending orders
            Route::get('/pending-order', 'pending')->name('pending');
            //invoice
            Route::get('/order-invoice/{id}', 'order_invoice')->name('order.invoice');
        });
    });

    // profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/admin-info/{id}', [ProfileController::class, 'adminInfo'])->name('admin.info');
    
});
// place order
Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order');
//cart
Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/get', [OrderController::class, 'getCart'])->name('cart.get');
Route::post('/cart/remove', [OrderController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update', [OrderController::class, 'updateCart'])->name('cart.update');

// success order
Route::get('/success-order/{order_id}', [OrderController::class, 'successOrder'])->name('success.order');

require __DIR__.'/auth.php';


Route::get('/c-clean', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    session()->flush();
    return "All cache cleared from " . env('APP_NAME');
});