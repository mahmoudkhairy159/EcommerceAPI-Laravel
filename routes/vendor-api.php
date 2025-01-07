<?php

use App\Http\Controllers\Vendor\AppSettingsController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\Auth\AuthController;
use App\Http\Controllers\Vendor\BrandController;
use App\Http\Controllers\Vendor\CategoryController;
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
        Route::get('trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed products SoftDeletes**************/
    Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/statistics', 'getStatistics')->name('getStatistics');
        Route::get('/{id}/statistics', 'getStatisticsById')->name('getStatisticsById');
        Route::get('/low-quantity-alert-products-count', 'getLowQuantityAlertProductsCount')->name('getLowQuantityAlertProductsCount');
        Route::get('/{id}/favorite-customers-count', 'getFavoriteCustomersCountByProductId')->name('getFavoriteCustomersCountByProductId');
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
        Route::post('/{id}/update-featured-status', 'updateFeaturedStatus')->name('updateFeaturedStatus');
        Route::put('/{id}/update-serial', 'updateSerial')->name('updateSerial');
        Route::post('/{id}/delete-image', 'deleteImage')->name('deleteImage');
    });
    Route::apiResource('products', ProductController::class);
    // products routes






});
