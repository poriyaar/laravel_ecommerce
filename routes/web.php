<?php

use App\Models\User;
use App\Models\Product;
use App\Notifications\OTPSms;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\CartController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Home\AddressController;
use App\Http\Controllers\Home\CompareController;
use App\Http\Controllers\Home\PaymentController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Home\WishlistController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Home\UserProfileController;
use App\Http\Controllers\Admin\TransActionController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Home\CommentController as HomeCommentController;
use App\Http\Controllers\Home\ProductController as HomeProductController;
use App\Http\Controllers\Home\CategoryController as HomeCategoryController;

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
    Route::resource('comments', CommentController::class);
    Route::resource('coupons', CouponController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('transactions', TransActionController::class);
    Route::resource('users', UserController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);

    // change comment approved
    Route::get('/comments/{comment}/changeStatus', [CommentController::class, 'changeStatus'])->name('comment.change.status');
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
Route::post('/comments/{product}', [HomeCommentController::class, 'store'])->name('home.comments.store');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('home.about.us');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('home.contact.us');
Route::post('/contact-us/form', [HomeController::class, 'contactUsForm'])->name('home.contact.us.form');


// wishlist
Route::get('/add-to-wishlist/{product}', [WishlistController::class, 'add'])->name('home.wishlist.add');
Route::get('/remove-from-wishlist/{product}', [WishlistController::class, 'remove'])->name('home.wishlist.remove');

// Compare
Route::get('/compare', [CompareController::class, 'index'])->name('home.compare.index');
Route::get('/add-to-compare/{product}', [CompareController::class, 'add'])->name('home.compare.add');
Route::get('/remove-from-compare/{product}', [CompareController::class, 'remove'])->name('home.compare.remove');


// cart
Route::get('/cart', [CartController::class, 'index'])->name('home.cart.index');
Route::post('/add-to-cart', [CartController::class, 'add'])->name('home.cart.add');
Route::put('/cart', [CartController::class, 'update'])->name('home.cart.update');
Route::get('/remove-from-cart/{rowId}', [CartController::class, 'remove'])->name('home.cart.remove');
Route::get('/clear-cart', [CartController::class, 'clear'])->name('home.cart.clear');
Route::post('/check-coupon', [CartController::class, 'checkCoupon'])->name('home.coupons.check');
Route::get('/checkout', [CartController::class, 'checkout'])->name('home.orders.checkout');

// payment
Route::post('/payment', [PaymentController::class, 'payment'])->name('home.payment.index');
Route::get('/payment-verify/{gatewayName}', [PaymentController::class, 'paymentVerify'])->name('home.payment.veryfy');

// login
Route::get('/login/{provider}', [AuthController::class, 'redirectToProvider'])->name('provider.login');
Route::get('/login/{provider}/callback', [AuthController::class, 'handelProviderCallback']);
// OTP
Route::any('/loginotp', [AuthController::class, 'login'])->name('otp.login');
Route::post('/checkOTP', [AuthController::class, 'checkOTP'])->name('check.otp');
Route::post('/resendOTP', [AuthController::class, 'resendOTP'])->name('resend.otp');


Route::prefix('profile')->name('home.')->group(function () {
    Route::get('/', [UserProfileController::class, 'index'])->name('user.profile');
    Route::get('/comments', [HomeCommentController::class, 'usersProfileIndex'])->name('user.profile.comments');
    Route::get('/wishlist', [WishlistController::class, 'usersProfileIndex'])->name('user.profile.wishlist');
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');

    Route::get('/orders', [CartController::class, 'userProfileOrders'])->name('user.profile.orders');

});

Route::get('/get-province-cities-list', [AddressController::class, 'getProvinceCitiesList']);

Route::get('/test', function () {
    // auth()->logout();
    // session()->flush('compareProducts');
    // \Cart::clear();

    // dd(\Cart::getContent());

    // $user = User::find(1);

    // $user->notify(new OTPSms(12345));


});
