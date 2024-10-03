<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuth;




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
Route::post('/verify', [UserController::class, 'verifyLogin']);
Route::get('/logout', [UserController::class, 'logout']);

//category route
Route::get('/category-list', [CategoryController::class, 'categoryList']);

//profile route
Route::post('/create-update-profile', [ProfileController::class, 'createUpdateProfile'])->middleware([ApiAuth::class]);
Route::get('/get-profile', [ProfileController::class, 'getProfile'])->middleware([ApiAuth::class]);

//product review
Route::post('/create-update-review', [ProductController::class, 'createUpdateReview'])->middleware([ApiAuth::class]);
Route::get('/get-products', [ProductController::class, 'getProducts']);


//product wish
Route::get('/create-update-wish/{product_id}', [ProductController::class, 'createUpdateWish'])->middleware([ApiAuth::class]);
Route::get('/get-wish-list', [ProductController::class, 'getWishList'])->middleware([ApiAuth::class]);
Route::delete('/delete-wish-list/{id}', [ProductController::class, 'deleteWishList'])->middleware([ApiAuth::class]);

//product cart
Route::post('/create-update-cart', [ProductController::class, 'createUpdateCart'])->middleware([ApiAuth::class]);
Route::get('/get-cart-list', [ProductController::class, 'getCartList'])->middleware([ApiAuth::class]);
Route::delete('/remove-cart/{id}', [ProductController::class, 'deleteCartList'])->middleware([ApiAuth::class]);

//invoice route
Route::post('/create-invoice', [InvoiceController::class, 'createInvoice'])->middleware([ApiAuth::class]);
Route::get('get-invoice', [InvoiceController::class, 'getInvoice'])->middleware([ApiAuth::class]);
Route::get('get-invoice-product/{id}', [InvoiceController::class, 'getInvoiceProduct'])->middleware([ApiAuth::class]);
Route::get('complete-order/{invoice_id}', [InvoiceController::class, 'completeOrder'])->middleware([ApiAuth::class]);

//policy route
Route::get("/policy-by-type/{type}", [PolicyController::class, 'policyByType']);

//payment
Route::post("/payment-success", [InvoiceController::class, 'apiPaymentSuccess']);
Route::post("/payment-cancel", [InvoiceController::class, 'apiPaymentCancel']);
Route::post("/payment-fail", [InvoiceController::class, 'apiPaymentFail']);

Route::post("/payment-ipn", [InvoiceController::class, 'paymentIPN']);

Route::get("/success", [InvoiceController::class, 'successInfo'])->name("success_url");
