<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquireController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariantController;
use App\Livewire\Admin\Banner\BannerCreate;
use App\Livewire\Admin\Banner\Bannerpage;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('adminlayout.layout');
// })->name('home');

Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(BannerController::class)->group(function(){
        Route::get('/bannner', 'index')->name('banner');
        Route::get('/bannner/create', 'create')->name('banner.create');
        Route::get('/bannner/edit/{id}', 'edit')->name('banner.edit');
    });

    Route::controller(BrandController::class)->group(function(){
        Route::get('/brand', 'index')->name('brand');
        Route::get('/brand/create', 'create')->name('brand.create');
        Route::get('/brand/edit/{slug}', 'edit')->name('brand.edit');
    });

    Route::controller(CategoriesController::class)->group(function(){
        Route::get('/categories', 'index')->name('categories');
        Route::get('/categories/create', 'create')->name('categories.create');
        Route::get('/categories/edit/{url}', 'edit')->name('categories.edit');
    });

    Route::controller(CategoriesController::class)->group(function(){
        Route::get('/categories', 'index')->name('categories');
        Route::get('/categories/create', 'create')->name('categories.create');
        Route::get('/categories/edit/{url}', 'edit')->name('categories.edit');
    });

    Route::controller(AttributeController::class)->group(function(){
        Route::get('/attribute', 'index')->name('attribute');
        Route::get('/attribute/create', 'create')->name('attribute.create');
        Route::get('/attribute/edit/{slug}', 'edit')->name('attribute.edit');
    });

    Route::controller(VariantController::class)->group(function(){
        Route::get('/variant', 'index')->name('variant');
        Route::get('/variant/create', 'create')->name('variant.create');
        Route::get('/variant/edit/{slug}', 'edit')->name('variant.edit');
    });

    Route::controller(ProductController::class)->group(function(){
        Route::get('/product', 'index')->name('product');
        Route::get('/product/create', 'create')->name('product.create');
        Route::get('/product/edit/{slug}', 'edit')->name('product.edit');
    });

    Route::controller(CouponController::class)->group(function(){
        Route::get('/coupon', 'index')->name('coupon');
        Route::get('/coupon/create', 'create')->name('coupon.create');
        Route::get('/coupon/edit/{slug}', 'edit')->name('coupon.edit');
    });
    Route::controller(UserController::class)->group(function(){
        Route::get('/user', 'index')->name('user');
        Route::get('/newsletter-subscriber', 'subIndex')->name('user.subscriber');
        Route::get('/inquiries', 'inquiriesIndex')->name('inquiries');
    });
    Route::controller(RatingController::class)->group(function(){
        Route::get('/ratings', 'index')->name('rating');
    });
    Route::controller(OrderController::class)->group(function(){
        Route::get('/orders', 'index')->name('orders');
        Route::get('/orders/{id}', 'indexdetail')->name('orders.detail');
    });
    
   
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



    

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');




require __DIR__.'/auth.php';
