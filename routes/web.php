<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\CategoryController as HomeCategoryController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\ProductController as HomeProductController;
use App\Models\User;
use App\Notifications\OTPSms;
use Ghasedak\GhasedakApi;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/categories/{category:slug}', [HomeCategoryController::class, 'show'])->name('home.categories.show');
Route::get('/products/{product:slug}', [HomeProductController::class, 'show'])->name('home.products.show');

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

    // Get Category Attributes
    Route::get('/category-attributes/{category}', [CategoryController::class, 'getCategoryAttributes']);

    // Edit Product Image
    Route::get('/products/{product}/images-edit', [ProductImageController::class, 'edit'])->name('products.images.edit');
    Route::delete('/products/{product}/images-destroy', [ProductImageController::class, 'destroy'])->name('product.image.destroy');
    Route::put('/products/{product}/images-set-primary', [ProductImageController::class, 'setPrimary'])->name('product.image.set_primary');
    Route::post('/products/{product}/images-add', [ProductImageController::class, 'add'])->name('products.images.add');

    // Edit Product Category
    Route::get('/products/{product}/category-edit', [ProductController::class, 'editCategory'])->name('products.category.edit');
    Route::put('/products/{product}/category-update', [ProductController::class, 'updateCategory'])->name('products.category.update');
});

// Route::get('/login/{provider}', [AuthController::class, 'redirectToProvider'])->name('provider.login');
Route::any('/login', [AuthController::class, 'login'])->name('login');
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

Route::get('/test', function () {
    $user = User::find(1);
    $user->notify(new OTPSms(1234));
});
