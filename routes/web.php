<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashboardController;
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

    
   
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



    

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');




require __DIR__.'/auth.php';
