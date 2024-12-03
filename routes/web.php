<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Home\CategoryController as HomeCategoryController;
use App\Http\Controllers\Home\ProductController as HomeProductController;

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

Route::get('/admin-panel/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');


Route::prefix('admin-panel/management')->name('admin.')->group(function () {


    Route::resource('brands', BrandController::class);
    Route::resource('attributes', AttributeController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
    Route::resource('products', ProductController::class);
    Route::resource('banners', BannerController::class);

    // get category Attribute Ajax
    Route::get('/category-attributes/{category}', [CategoryController::class, 'getCategoryAttributes']);

    // Edit product Image
    Route::get('/products/{product}/images-edit', [ProductImageController::class, 'edit'])->name('products.image.edit');
    Route::delete('/products/{product}/images-destroy', [ProductImageController::class, 'destroy'])->name('products.image.destroy');
    Route::put('/products/{product}/select-primary-image', [ProductImageController::class, 'setPrimary'])->name('products.image.set_primary');
    Route::post('/products/{product}/add-images', [ProductImageController::class, 'add'])->name('products.images.add');

    // Edit product Image

    Route::get('/products/{product}/category-edit', [ProductController::class, 'editCategory'])->name('products.category.edit');
    Route::post('/products/{product}/category-update', [ProductController::class, 'updateCategory'])->name('products.category.update');
});


Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/categories/{category:slug}', [HomeCategoryController::class, 'show'])->name('home.categories.show');
Route::get('/products/{product:slug}', [HomeProductController::class, 'show'])->name('home.product.show');
