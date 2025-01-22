<?php

use App\Http\Controllers\Vendor\AppSettingsController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\Auth\AuthController;
use App\Http\Controllers\Vendor\BrandController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\CityController;
use App\Http\Controllers\Vendor\CountryController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\ProductImageController;
use App\Http\Controllers\Vendor\ProductReviewController;
use App\Http\Controllers\Vendor\ProductVariantController;
use App\Http\Controllers\Vendor\ProductVariantItemController;
use App\Http\Controllers\Vendor\StateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::name('vendor-api.')->group(function () {
    //app-settings
    Route::controller(AppSettingsController::class)->group(function () {
        Route::get('/app-settings', 'index');
    });
    //app-settings
    // Auth routes
    Route::controller(AuthController::class)->name('auth.')->prefix('/auth')->group(function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/logout', 'logout')->name('logout');
        Route::post('/refresh-token', 'refresh')->name('refresh-token');
        Route::post('/update-info', 'update')->name('update-info');
        Route::get('/get-info', 'get')->name('get-info');
    });
    // Auth routes
    //categories
    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/get-without-pagination', 'getWithoutPagination')->name('getWithoutPagination');
        Route::get('/parents', 'getMainCategories')->name('getMainCategories');
        Route::get('/parent/{id}', 'getByParentId')->name('getByParentId');
        Route::get('/tree-structure', 'getTreeStructure')->name('getTreeStructure');
    });
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    //categories
    //Brands
    Route::controller(BrandController::class)->prefix('brands')->name('brands.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/featured', 'getFeatured')->name('getFeatured');
    });
    Route::apiResource('brands', BrandController::class)->only(['index', 'show']);
    //Brands
    // products routes
    /***********Trashed products SoftDeletes**************/
    Route::controller(ProductController::class)->prefix('products')->as('products.')->group(function () {
        Route::get('/trashed/vendor/{id}', 'getOnlyTrashedByVendorId')->name('getOnlyTrashedByVendorId');
        Route::delete('/force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('/restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed products SoftDeletes**************/
    Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/vendor/{id}', 'getAllByVendorId')->name('getAllByVendorId');
        Route::get('/statistics/vendor/{id}', 'getStatisticsByVendorId')->name('getStatisticsByVendorId');
        Route::get('/{id}/statistics', 'getStatisticsById')->name('getStatisticsById');
        Route::get('/low-quantity-alert-products-count', 'getLowQuantityAlertProductsCount')->name('getLowQuantityAlertProductsCount');
        Route::get('/{id}/favorite-customers-count', 'getFavoriteCustomersCountByProductId')->name('getFavoriteCustomersCountByProductId');
        Route::put('/{id}/change-status', 'changeStatus')->name('changeStatus');
        Route::put('/{id}/change-approval-status', 'changeApprovalStatus')->name('changeApprovalStatus');
        Route::put('/{id}/update-product-type', 'updateProductType')->name('updateProductType');
        Route::put('/{id}/update-serial', 'updateSerial')->name('updateSerial');
        Route::delete('/{id}/delete-image', 'deleteImage')->name('deleteImage');
    });
    Route::apiResource('products', ProductController::class)->except('index');
    // products routes
    // product-images routes
    Route::controller(ProductImageController::class)->prefix('product-images')->as('product-images.')->group(function () {
        Route::get('/product/{id}', 'getByProductId')->name('getByProductId');
    });
    Route::apiResource('product-images', ProductImageController::class)->except(['index']);
    // product-images routes
    // product-variants routes
    Route::controller(ProductVariantController::class)->prefix('product-variants')->as('product-variants.')->group(function () {
        Route::get('/product/{id}', 'getByProductId')->name('getByProductId');
    });
    Route::apiResource('product-variants', ProductVariantController::class)->except(['index']);
    // product-variants routes
    // product-variant-items routes
    Route::controller(ProductVariantItemController::class)->prefix('product-variant-items')->as('product-variant-items.')->group(function () {
        Route::get('/product-variant/{id}', 'getByProductVariantId')->name('getByProductVariantId');
    });
    Route::apiResource('product-variant-items', ProductVariantItemController::class)->except(['index']);
    // product-variant-items routes




    // Countries routes
    Route::apiResource('countries', CountryController::class)->only(['index', 'show']);
    // Countries routes


    // States routes
    Route::controller(StateController::class)->name('states.')->prefix('/states')->group(function () {
        Route::get('/country/{country_id}', 'getByCountryId')->name('getByCountryId');
        Route::get('/{id}', 'show')->name('show');
    });
    // States routes


    // cities routes
    Route::controller(CityController::class)->name('cities.')->prefix('/cities')->group(function () {
        Route::get('/country/{country_id}', 'getByCountryId')->name('getByCountryId');
        Route::get('/{id}', 'show')->name('show');
    });
    // cities routes

    // orders routes
    Route::controller(OrderController::class)->prefix('orders')->as('orders.')->group(function () {
        Route::get('/status/{status}', 'getAllByStatus')->name('getAllByStatus');
        Route::get('/user/{id}', 'getByUserId')->name('getByUserId');
        Route::put('/{id}/change-status', 'changeStatus')->name('changeStatus');
    });
    Route::apiResource('orders', OrderController::class)->except(['store', 'update','destroy']);
    // orders routes

    // productReviews routes

    Route::controller(ProductReviewController::class)->name('product-reviews.')->prefix('product-reviews')->group(function () {
        Route::get('/product/{productId}', 'getByProductId')->name('getByProductId');
        Route::get('/vendor/{vendorId}', 'getByVendorId')->name('getByVendorId');
        Route::get('/user/{userId}', 'getByUserId')->name('getByUserId');
    });
    Route::apiResource('product-reviews', ProductReviewController::class)->only(['show']);
    // productReviews routes


});
