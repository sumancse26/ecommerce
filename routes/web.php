<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenAuthentication;
use Illuminate\Support\Facades\Route;

//page route
Route::get('/', [HomeController::class, 'index']);
Route::get('by-category', [CategoryController::class, 'categoryPage']);
Route::get('by-brand', [BrandController::class, 'brandPage']);
Route::get('by-policy', [PolicyController::class, 'policyPage']);
Route::get('product-details', [ProductController::class, 'productDtlPage']);
Route::get('login-page', [UserController::class, 'loginPage']);
Route::get('verify-page', [UserController::class, 'verifyPage']);
Route::get('wish-page', [ProductController::class, 'wishListPage']);
Route::get('cart-page', [ProductController::class, 'cartListPage']);
Route::get('profile', [ProfileController::class, 'profilePage']);


//product route
Route::get('/brand-list', [BrandController::class, 'brandList']);
Route::get('/product-list-by-category/{category_id}', [ProductController::class, 'productListByCategory']);
Route::get('/product-list-by-brand/{brand_id}', [ProductController::class, 'productListByBrand']);
Route::get('/product-list-by-remarks/{remarks}', [ProductController::class, 'productListByRemarks']);
Route::get('/product-detail-by-id/{id}', [ProductController::class, 'productDetailsById']);
Route::get('/review-list-by-product/{product_id}', [ProductController::class, 'reviewListByProduct']);
Route::get('/product-list-by-slider', [ProductController::class, 'productListBySlider']);


//user route
Route::get('/login', [UserController::class, 'login']);
Route::get('/verify', [UserController::class, 'verifyLogin']);
Route::get('/logout', [UserController::class, 'logout']);

//category route
Route::get('/category-list', [CategoryController::class, 'categoryList']);

//profile route
Route::post('/create-update-profile', [ProfileController::class, 'createUpdateProfile'])->middleware([TokenAuthentication::class]);
Route::get('/get-profile', [ProfileController::class, 'getProfile'])->middleware([TokenAuthentication::class]);

//product review
Route::post('/create-update-review', [ProductController::class, 'createUpdateReview'])->middleware([TokenAuthentication::class]);


//product wish
Route::get('/create-update-wish/{product_id}', [ProductController::class, 'createUpdateWish'])->middleware([TokenAuthentication::class]);
Route::get('/get-wish-list', [ProductController::class, 'getWishList'])->middleware([TokenAuthentication::class]);
Route::delete('/delete-wish-list/{id}', [ProductController::class, 'deleteWishList'])->middleware([TokenAuthentication::class]);

//product cart
Route::post('/create-update-cart', [ProductController::class, 'createUpdateCart'])->middleware([TokenAuthentication::class]);
Route::get('/get-cart-list', [ProductController::class, 'getCartList'])->middleware([TokenAuthentication::class]);
Route::delete('/remove-cart/{id}', [ProductController::class, 'deleteCartList'])->middleware([TokenAuthentication::class]);



//invoice route
Route::post('/create-invoice', [InvoiceController::class, 'createInvoice'])->middleware([TokenAuthentication::class]);
Route::get('get-invoice', [InvoiceController::class, 'getInvoice'])->middleware([TokenAuthentication::class]);
Route::get('get-invoice-product/{id}', [InvoiceController::class, 'getInvoiceProduct'])->middleware([TokenAuthentication::class]);

//policy route
Route::get("/policy-by-type/{type}", [PolicyController::class, 'policyByType']);

//payment
// Route::post("/payment-success", [InvoiceController::class, 'paymentSuccess']);
// Route::post("/payment-cancel", [InvoiceController::class, 'paymentCancel']);
// Route::post("/payment-fail", [InvoiceController::class, 'paymentFail']);
