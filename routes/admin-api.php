<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogCommentController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BrandImageController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\FlashSaleProductController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderProductController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\PaypalSettingController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductAccessoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductVariantItemController;
use App\Http\Controllers\Admin\RelatedProductController;
use App\Http\Controllers\Admin\RelatedServiceController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingRuleController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\StripeSettingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserAddressController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
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
            Route::get('/currency-list', 'getCurrencyList')->name('getCurrencyList');
            Route::get('/timezone-list', action: 'getTimezoneList')->name('getTimezoneList');
            Route::put('/update', 'update')->name('update');
        });
    // SETTINGS
    // Paypal SETTING
    Route::controller(PaypalSettingController::class)->name('paypal-settings.')
        ->prefix('paypal-settings')->group(function () {
            Route::get('', 'index')->name('index');
            Route::put('/update', 'update')->name('update');
        });
    //Paypal  SETTING

    // Stripe SETTING
    Route::controller(StripeSettingController::class)->name('stripe-settings.')
        ->prefix('stripe-settings')->group(function () {
            Route::get('', 'index')->name('index');
            Route::put('/update', 'update')->name('update');
        });
    //Stripe  SETTING
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
        Route::put('/{id}/update-serial', 'updateSerial')->name('updateSerial');

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
        Route::put('/{id}/update-serial', 'updateSerial')->name('updateSerial');

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
        Route::get('/parents', 'getMainCategories')->name('getMainCategories');
        Route::get('/parent/{id}', 'getByParentId')->name('getByParentId');
        Route::get('/tree-structure', 'getTreeStructure')->name('getTreeStructure');
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
        Route::put('/{id}/update-serial', 'updateSerial')->name('updateSerial');
        Route::put('/bulk-update-status', 'bulkUpdateStatus')->name('bulkUpdateStatus');


    });
    Route::apiResource('categories', CategoryController::class);
    //categories routes

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
        Route::get('/pending', 'getAllPendingProducts')->name('getAllPendingProducts');
        Route::get('/vendor/{vendorId}', 'getByVendorId')->name('getByVendorId');
        Route::get('/statistics', 'getStatistics')->name('getStatistics');
        Route::get('/{id}/statistics', 'getStatisticsById')->name('getStatisticsById');
        Route::get('/low-quantity-alert-products-count', 'getLowQuantityAlertProductsCount')->name('getLowQuantityAlertProductsCount');
        Route::get('/{id}/favorite-customers-count', 'getFavoriteCustomersCountByProductId')->name('getFavoriteCustomersCountByProductId');
        Route::put('/{id}/change-status', 'changeStatus')->name('changeStatus');
        Route::put('/{id}/change-approval-status', 'changeApprovalStatus')->name('changeApprovalStatus');
        Route::put('/{id}/update-product-type', 'updateProductType')->name('updateProductType');
        Route::put('/{id}/update-serial', 'updateSerial')->name('updateSerial');
        Route::delete('/{id}/delete-image', 'deleteImage')->name('deleteImage');
    });
    Route::apiResource('products', ProductController::class);
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
        Route::get('/user/{userId}', 'viewUserCart')->name('viewUserCart');
    });
    // cart routes
    // orders routes
    Route::controller(OrderController::class)->prefix('orders')->as('orders.')->group(function () {
        Route::get('/status/{status}', 'getAllByStatus')->name('getAllByStatus');
        Route::get('/user/{id}', 'getByUserId')->name('getByUserId');
        Route::put('/{id}/change-status', 'changeStatus')->name('changeStatus');
    });
    Route::apiResource('orders', OrderController::class)->except(['store', 'update']);
    // orders routes
    Route::apiResource('transactions', TransactionController::class)->only(['index', 'show']);

    //order-products
    Route::controller(OrderProductController::class)->prefix('order-products')->as('order-products.')->group(function () {
        Route::get('/order/{id}', 'getByOrderId')->name('getByOrderId');
    });
    Route::apiResource('order-products', OrderProductController::class)->except(['index']);
    //order-products

    // productReviews routes

    Route::controller(ProductReviewController::class)->name('product-reviews.')->prefix('product-reviews')->group(function () {
        Route::get('/product/{productId}', 'getByProductId')->name('getByProductId');
        Route::get('/vendor/{vendorId}', 'getByVendorId')->name('getByVendorId');
        Route::get('/user/{userId}', 'getByUserId')->name('getByUserId');
    });
    Route::apiResource('product-reviews', ProductReviewController::class)->only(['show', 'destroy']);
    // productReviews routes

    // wishlist routes
    Route::controller(WishlistController::class)->name('wishlists.')->prefix('wishlists')->group(function () {
        Route::get('/user/{userId}', 'viewUserWishlist')->name('viewUserWishlist');
    });
    // wishlist routes


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
    //banners
//mails
    Route::controller(MailController::class)->prefix('mails')->name('mails.')->group(function () {
        Route::post('/send', 'send')->name('send');
    });
    //mails

    // vendors routes
    Route::controller(VendorController::class)->prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
    });
    Route::apiResource('vendors', VendorController::class);
    // vendors routes
    //flash-sales routes
    Route::apiResource('flash-sales', FlashSaleController::class)->only('index', 'update');
    Route::apiResource('flash-sale-products', FlashSaleProductController::class)->except('show');
    //flash-sales routes

    //coupons routes
    Route::controller(CouponController::class)->prefix('coupons')->name('coupons.')->group(function () {
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
    });
    Route::apiResource('coupons', CouponController::class);
    //coupons routes

    //shipping-rules routes
    Route::controller(ShippingRuleController::class)->prefix('shipping-rules')->name('shipping-rules.')->group(function () {
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');
    });
    Route::apiResource('shipping-rules', ShippingRuleController::class);
    //shipping-rules routes


    // Countries routes
    /***********Trashed Countries SoftDeletes**************/
    Route::controller(CountryController::class)->prefix('countries')->as('countries.')->group(function () {
        Route::get('/trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('/force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('/restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed Countries SoftDeletes**************/
    Route::apiResource('countries', CountryController::class);
    // Countries routes

    // States routes
    Route::get('/states/country/{country_id}', [StateController::class, 'getByCountryId'])->name('states.getByCountryId');
    /***********Trashed States SoftDeletes**************/
    Route::controller(StateController::class)->prefix('states')->as('states.')->group(function () {
        Route::get('/trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('/force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('/restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed States SoftDeletes**************/
    Route::apiResource('states', StateController::class);
    // States routes

    // Cities routes
    Route::get('/cities/country/{country_id}', [CityController::class, 'getByCountryId'])->name('cities.getByCountryId');
    Route::get('/cities/state/{state_id}', [CityController::class, 'getByStateId'])->name('cities.getByStateId');
    /***********Trashed Cities SoftDeletes**************/
    Route::controller(CityController::class)->prefix('cities')->as('cities.')->group(function () {
        Route::get('/trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('/force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('/restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed States SoftDeletes**************/
    Route::apiResource('cities', CityController::class);
    // Cities routes
    //user-addresses
    Route::controller(UserAddressController::class)->prefix('user-addresses')->as('user-addresses.')->group(function () {
        Route::get('/user/{id}', 'getAllByUserId')->name('getAllByUserId');
    });
    Route::apiResource('user-addresses', UserAddressController::class);
    //user-addresses

    //advertisements routes
    Route::controller(AdvertisementController::class)->prefix('advertisements')->name('advertisements.')->group(function () {
        Route::post('/{id}/change-status', 'changeStatus')->name('changeStatus');

    });
    Route::apiResource('advertisements', AdvertisementController::class);
    //advertisements routes
    // blogs routes
    /***********Trashed blogs SoftDeletes**************/
    Route::controller(BlogController::class)->prefix('blogs')->as('blogs.')->group(function () {
        Route::get('/trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('/force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('/restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed blogs SoftDeletes**************/

    Route::apiResource('blogs', BlogController::class);
    // blogs routes
     // blogs routes
    /***********Trashed blogs SoftDeletes**************/
    Route::controller(BlogCategoryController::class)->prefix('blog-categories')->as('blog-categories.')->group(function () {
        Route::get('/trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('/force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('/restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed blogs SoftDeletes**************/

    Route::apiResource('blog-categories', BlogCategoryController::class);
    // blogs routes

    // blog_comments routes
    /***********Trashed blog_comments SoftDeletes**************/
    Route::controller(BlogCommentController::class)->prefix('blog-comments')->as('blog-comments.')->group(function () {
        Route::get('/trashed', 'getOnlyTrashed')->name('getOnlyTrashed');
        Route::delete('/force-delete/{id}', 'forceDelete')->name('forceDelete');
        Route::post('/restore/{id}', 'restore')->name('restore');
    });
    /***********Trashed blog_comments SoftDeletes**************/
    Route::controller(BlogCommentController::class)->name('blog-comments.')->prefix('/blog-comments')->group(function () {
        Route::get('/blog/{blog_id}', 'getByBlogId')->name('getByBlogId');
        Route::get('/user/{user_id}', 'getByUserId')->name('getByUserId');
    });
    Route::apiResource('blog-comments', BlogCommentController::class)->only(['show', 'destroy']);
    // blog_comments routes

});
