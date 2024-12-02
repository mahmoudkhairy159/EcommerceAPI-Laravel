<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BrandImageController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderProductController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductAccessoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ReferenceCustomerController;
use App\Http\Controllers\Admin\RelatedProductController;
use App\Http\Controllers\Admin\RelatedServiceController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WishlistController;
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

Route::name('admin-api.')->group(function () {
    // Auth routes
    Route::controller(AuthController::class)->name('auth.')->prefix('/auth')->group(function () {
        Route::post('/login', 'login')->name('login');
        Route::post('/logout', 'logout')->name('logout');
        Route::post('/refresh-token', 'refresh')->name('refresh-token');
        Route::post('/update-info', 'update')->name('update-info');
        Route::get('/get-info', 'get')->name('get-info');
    });
    // Auth routes
// Permissions routes
    Route::controller(PermissionController::class)->name('permissions.')->prefix('/permissions')->group(function () {
        Route::get('/', 'index')->name('index');
    });
// Permissions routes

// Roles routes
    Route::apiResource('roles', RoleController::class);
// Roles routes

    // Admins routes
    Route::apiResource('admins', AdminController::class);
    // Admins routes

    // Users routes
    Route::get('/users/slugs/{slug}', [UserController::class, 'showBySlug'])->name('users.showBySlug');
    Route::post('/users/{id}/change-status', [UserController::class, 'changeStatus'])->name('changeStatus');
    Route::apiResource('users', UserController::class);

    // Users routes

    // SETTINGS
    Route::controller(SettingController::class)->name('settings.')
        ->prefix('settings')->group(function () {
        Route::get('', 'index')->name('index');
        Route::put('/update', 'update')->name('update');
    });
    // SETTINGS
    //brands routes
    /***********Trashed brands SoftDeletes**************/
    Route::controller(BrandController::class)->prefix('brands')->as('brands.')->group(function () {
        Route::get('trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed brands SoftDeletes**************/
    Route::controller(BrandController::class)->prefix('brands')->name('brands.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
        Route::put('/{id}/update-rank', 'updateRank')->name('updateRank');

    });
    Route::apiResource('brands', BrandController::class);
    //brands routes

    //services routes
    /***********Trashed services SoftDeletes**************/
    Route::controller(ServiceController::class)->prefix('services')->as('services.')->group(function () {
        Route::get('trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed services SoftDeletes**************/
    Route::controller(ServiceController::class)->prefix('services')->name('services.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/get-without-pagination', 'getWithoutPagination')->name('getWithoutPagination');
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
        Route::put('/{id}/update-rank', 'updateRank')->name('updateRank');

    });
    Route::apiResource('services', ServiceController::class);
    //services routes
    //categories routes
    /***********Trashed categories SoftDeletes**************/
    Route::controller(CategoryController::class)->prefix('categories')->as('categories.')->group(function () {
        Route::get('trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed categories SoftDeletes**************/
    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/get-without-pagination', 'getWithoutPagination')->name('getWithoutPagination');
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
        Route::put('/{id}/update-rank', 'updateRank')->name('updateRank');

    });
    Route::apiResource('categories', CategoryController::class);
    //categories routes

    // products routes
    Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/statistics', 'getStatistics')->name('getStatistics');
        Route::get('/{id}/statistics', 'getStatisticsById')->name('getStatisticsById');
        Route::get('/low-quantity-alert-products-count', 'getLowQuantityAlertProductsCount')->name('getLowQuantityAlertProductsCount');
        Route::get('/{id}/favorite-customers-count', 'getFavoriteCustomersCountByProductId')->name('getFavoriteCustomersCountByProductId');
        Route::get('/banner', 'getProductsBanner')->name('getProductsBanner');
        Route::post('/banner', 'createProductsBanner')->name('createProductsBanner');
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
        Route::post('/{id}/update-featured-status', 'updateFeaturedStatus')->name('updateFeaturedStatus');
        Route::put('/{id}/update-rank', 'updateRank')->name('updateRank');
        Route::post('/{id}/delete-image', 'deleteImage')->name('deleteImage');
    });
    Route::apiResource('products', ProductController::class);
    // products routes

    // product-images routes
    Route::controller(ProductImageController::class)->prefix('product-images')->as('product-images.')->group(function () {
        Route::get('/product/{id}', 'getByProductId')->name('getByProductId');
    });
    Route::apiResource('product-images', ProductImageController::class)->except(['index']);
    // product-images routes
    // brand-images routes
    Route::controller(BrandImageController::class)->prefix('brand-images')->as('brand-images.')->group(function () {
        Route::get('/brand/{id}', 'getByBrandId')->name('getByBrandId');
    });
    Route::apiResource('brand-images', BrandImageController::class)->except(['index']);
// brand-images routes
    // product-accessories routes
    Route::controller(ProductAccessoryController::class)->prefix('product-accessories')->as('related-products.')->group(function () {
        Route::get('/product/{id}', 'getProductAccessories')->name('getProductAccessories');
    });
    Route::apiResource('product-accessories', ProductAccessoryController::class)
        ->only(['store', 'update', 'destroy']);
    // product-accessories routes
    // related-products routes
    Route::controller(RelatedProductController::class)->prefix('related-products')->as('related-products.')->group(function () {
        Route::get('/product/{id}', 'getRelatedProducts')->name('getRelatedProducts');
    });
    Route::apiResource('related-products', RelatedProductController::class)
        ->only(['store', 'update', 'destroy']);
    // related-products routes

    // related-services routes
    Route::controller(RelatedServiceController::class)->prefix('related-services')->as('related-services.')->group(function () {
        Route::get('/product/{id}', 'getRelatedServices')->name('getRelatedServices');
    });
    Route::apiResource('related-services', RelatedServiceController::class)
        ->only(['store', 'update', 'destroy']);
    // related-services routes

    // cart routes
    Route::controller(CartController::class)->name('carts.')->prefix('carts')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/user/{userId}', 'viewUserCart')->name('viewUserCart');
        Route::put('/user/{userId}', 'updateUserCart')->name('updateUserCart');
        Route::delete('/remove/user/{userId}/product/{productId}', 'removeFromCart')->name('removeFromCart');

    });
    // cart routes
    // orders routes
    Route::controller(OrderController::class)->prefix('orders')->as('orders.')->group(function () {
        Route::get('/user/{id}', 'getByUserId')->name('getByUserId');
        Route::put('/{id}/change-status', 'changeStatus')->name('changeStatus');
    });
    Route::apiResource('orders', OrderController::class);
// orders routes

//order-products
    Route::controller(OrderProductController::class)->prefix('order-products')->as('order-products.')->group(function () {
        Route::get('/order/{id}', 'getByOrderId')->name('getByOrderId');
    });
    Route::apiResource('order-products', OrderProductController::class)->except(['index']);
//order-products

// reviews routes
    /***********Trashed reviews SoftDeletes**************/
    Route::controller(ReviewController::class)->prefix('reviews')->as('reviews.')->group(function () {
        Route::get('/trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('/force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('/restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed reviews SoftDeletes**************/
    Route::controller(ReviewController::class)->name('reviews.')->prefix('/reviews')->group(function () {
        Route::get('/product/{product_id}', 'getByProductId')->name('getByProductId');
        Route::get('/service/{service_id}', 'getByServiceId')->name('getByServiceId');
        Route::get('/user/{user_id}', 'getByUserId')->name('getByUserId');
    });
    Route::apiResource('reviews', ReviewController::class)->only(['show', 'destroy']);
    // reviews routes

    // wishlist routes
    Route::controller(WishlistController::class)->name('wishlists.')->prefix('wishlists')->group(function () {
        Route::get('/user/{userId}', 'viewUserWishlist')->name('viewUserWishlist');
    });
    // wishlist routes

    //reference customers routes
    Route::controller(ReferenceCustomerController::class)->prefix('reference-customers')->name('reference-customers.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
    });
    Route::apiResource('reference-customers', ReferenceCustomerController::class);
    //reference customers routes
    //Contact Messages EndPoint
    Route::apiResource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    //Contact Messages EndPoint
//Pages
    Route::apiResource('pages', PageController::class)->only(['index', 'show', 'update']);
//Pages
//page sections routes
    Route::controller(PageSectionController::class)->prefix('page-sections')->name('page-sections.')->group(function () {
        Route::get('/{page_id}', 'index')->name('index');
    });
    Route::apiResource('page-sections', PageSectionController::class)->except(['index']);
//page sections routes

//assets
    Route::controller(AssetController::class)->prefix('assets')->name('assets.')->group(function () {
        Route::get('/page/{page_id}', 'index')->name('index');
        Route::put('/{asset_name}', 'update')->name('update');
    });
//assets
    //hero-sliders
    Route::apiResource('hero-sliders', HeroSliderController::class);
    //hero-sliders
//banners
    Route::apiResource('banners', BannerController::class);
//bannerss
//mails
    Route::controller(MailController::class)->prefix('mails')->name('mails.')->group(function () {
        Route::post('/send', 'send')->name('send');
    });
//mails
});
