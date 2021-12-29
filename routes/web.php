<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainPageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SaveForLaterController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\CouponsController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::view('/', 'main');
Route::get('/', [MainPageController::class, 'index'])->name('mainPage');

Route::get('/shop',[ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}',[ShopController::class, 'show'])->name('shop.show');

Route::get('/cart',[CartController::class, 'index'])->name('cart.index');
Route::post('/cart',[CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/{product}',[CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/switchToSaveForLater/{product}',[CartController::class, 'switchToSaveForLater'])->name('cart.switchToSaveForLater');

Route::post('/coupon',[CouponsController::class, 'store'])->name('coupon.store');
Route::delete('/coupon',[CouponsController::class, 'destroy'])->name('coupon.destroy');

// AJAX CONTROLLER
Route::patch('/cart/{product}',[CartController::class, 'update'])->name('cart.update');

Route::delete('/saveForLater/{product}',[SaveForLaterController::class, 'destroy'])->name('saveForLater.destroy');
Route::post('/saveForLater/switchToCart/{product}',[SaveForLaterController::class, 'switchToCart'])->name('saveForLater.switchToCart');

Route::get('/checkout',[CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');
Route::post('/checkout',[CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/guestcheckout',[CheckoutController::class, 'index'])->name('guestcheckout.index');

Route::get('/thankyou',[ConfirmationController::class, 'index'])->name('confirmation.index');

Route::get('/empty', function(){
  Cart::instance('saveForLater')->destroy();
});
Route::get('/empty2', function(){
  Cart::destroy();
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/search', [ShopController::class, 'search'])->name('search');

Route::get('/search-algolia', [ShopController::class, 'searchAlgolia'])->name('search-algolia');



// Additional For install application to the server

Route::get('/storage', function (){
 Artisan::call('storage:link');
});

Route::get('/configload', function (){
 Artisan::call('config:cache');
});

Route::get('/routecache', function (){
 Artisan::call('route:cache');
});

Route::get('/routecache', function (){
 Artisan::call('view:cache');
});

