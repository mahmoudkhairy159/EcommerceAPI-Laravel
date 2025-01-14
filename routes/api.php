<?php

use App\Http\Controllers\Api\AppSettingsController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\SocialiteController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\BrandImageController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\FlashSaleController;
use App\Http\Controllers\Api\FlashSaleProductController;
use App\Http\Controllers\Api\Gateways\PaypalController;
use App\Http\Controllers\Api\Gateways\StripeController;
use App\Http\Controllers\Api\HeroSliderController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderProductController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PageSectionController;
use App\Http\Controllers\Api\ProductAccessoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\ProductVariantItemController;
use App\Http\Controllers\Api\RelatedProductController;
use App\Http\Controllers\Api\RelatedServiceController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::name('user-api.')->group(function () {

    //app-settings
    Route::controller(AppSettingsController::class)->group(function () {
        Route::get('/app-settings', 'index');
    });
    //app-settings
    // Auth routes
    Route::group(['prefix' => '/auth', 'name' => 'auth.'], function () {
        Route::controller(SocialiteController::class)->as('socialite.')->group(function () {
            Route::get('login/{provider}', 'redirect')->name('redirect');
            Route::get('login/{provider}/callback', 'callback')->name('callback');
            Route::post('/social-login', 'login')->name('login');

        });

        Route::post('/register', [RegisterController::class, 'create'])->name('create');

        Route::controller(LoginController::class)->group(function () {
            Route::post('/login', 'login')->name('login');
            Route::post('/refresh-token', 'refresh')->name('refresh-token');
        });

        Route::controller(VerificationController::class)->prefix('verification')->group(function () {
            Route::post('/verify', 'verify')->name('verification.verify');
            Route::get('/resend', 'resend')->name('verification.resend');
        });
        Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
        // forgot Password

        Route::controller(ForgotPasswordController::class)->group(function () {
            Route::post('/forgot-password', 'forgot')->name('forgot-password');
            Route::post('/forgot-password/resend-otp-code', 'resendCode')->name('forgot-password.resend-otp-code');
        });
        // forgot Password

        // Reset Password

        Route::controller(ResetPasswordController::class)->group(function () {
            Route::post('/reset-password', 'reset')->name('reset');
            Route::post('/verify-otp-code', 'verify')->name('verify');
        });
        // Reset Password

    });

    //users routes
    Route::group(['prefix' => '/users', 'name' => 'users.'], function () {

        Route::controller(UserController::class)->group(function () {
            Route::get('', 'index')->name('index');
            Route::post('/update-info', 'update')->name('update');
            Route::post('/update-user-image', 'updateUserProfileImage')->name('updateUserProfileImage');
            Route::post('/delete-user-image', 'deleteUserProfileImage')->name('deleteUserProfileImage');
            Route::post('/change-account-activity', 'changeAccountActivity')->name('changeAccountActivity');
            Route::post('/update-general-Preferences', 'updateGeneralPreferences')->name('updateGeneralPreferences');
            Route::post('/change-password', 'changePassword')->name('changePassword');
            Route::get('/get', 'get')->name('get');
            Route::get('/{id}', 'getOneByUserId')->name('getOneByUserId');
            Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        });
    });
    //users routes
    //Brands
    Route::controller(BrandController::class)->prefix('brands')->name('brands.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/featured', 'getFeatured')->name('getFeatured');
    });
    Route::apiResource('brands', BrandController::class)->only(['index', 'show']);
    //Brands
    //services
    Route::controller(ServiceController::class)->prefix('services')->name('services.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/featured', 'getFeaturedServices')->name('getFeaturedServices');
        Route::get('/get-without-pagination', 'getWithoutPagination')->name('getWithoutPagination');

    });
    Route::apiResource('services', ServiceController::class)->only(['index', 'show']);
    //services
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
    // products routes
    Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/featured', 'getFeaturedProducts')->name('getFeaturedProducts');

    });
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    // products routes

    // product-images routes
    Route::controller(ProductImageController::class)->prefix('product-images')->as('product-images.')->group(function () {
        Route::get('/product/{id}', 'getByProductId')->name('getByProductId');
    });
    Route::apiResource('product-images', ProductImageController::class)->only(['show']);
    // product-images routes
    // product-variants routes
    Route::controller(ProductVariantController::class)->prefix('product-variants')->as('product-variants.')->group(function () {
        Route::get('/product/{id}', 'getByProductId')->name('getByProductId');
    });
    Route::apiResource('product-variants', ProductVariantController::class)->only(['show']);
    // product-variants routes

    // product-variant-items routes
    Route::controller(ProductVariantItemController::class)->prefix('product-variant-items')->as('product-variant-items.')->group(function () {
        Route::get('/product-variant/{id}', 'getByProductVariantId')->name('getByProductVariantId');
    });
    Route::apiResource('product-variant-items', ProductVariantItemController::class)->only(['show']);
    // product-variant-items routes
    // brand-images routes
    Route::controller(BrandImageController::class)->prefix('brand-images')->as('brand-images.')->group(function () {
        Route::get('/brand/{id}', 'getByBrandId')->name('getByBrandId');
    });
    Route::apiResource('brand-images', BrandImageController::class)->only(['show']);
    // brand-images routes
    // related-products routes
    Route::controller(RelatedProductController::class)->prefix('related-products')->as('related-products.')->group(function () {
        Route::get('/product/{id}', 'getRelatedProducts')->name('getRelatedProducts');
    });
    // related-products routes
    // product-accessories routes
    Route::controller(ProductAccessoryController::class)->prefix('product-accessories')->as('related-products.')->group(function () {
        Route::get('/product/{id}', 'getProductAccessories')->name('getProductAccessories');
    });
    // product-accessories routes
    // related-services routes
    Route::controller(RelatedServiceController::class)->prefix('related-services')->as('related-services.')->group(function () {
        Route::get('/product/{id}', 'getRelatedServices')->name('getRelatedServices');
    });
    // related-services routes
//  cart routes
    Route::controller(CartController::class)
        ->name('carts.')
        ->prefix('carts')
        ->group(function () {
            // Display the cart
            Route::get('/products-cart', 'viewCart')->name('view');

            // Add a product to the cart
            Route::post('/products-cart', 'addToCart')->name('add');

            // Update the quantity of a specific product in the cart
            Route::put('/products-cart/{id}', 'updateProductCart')->name('update');

            // Remove a specific product from the cart
            Route::delete('/products-cart/{id}', 'removeFromCart')->name('remove');

            // Clear the entire cart
            Route::delete('/clear', 'clearCart')->name('clear');

        });

    //  cart routes

    // orders routes
    Route::apiResource('orders', OrderController::class);
    // orders routes
//order-products
    Route::controller(OrderProductController::class)->prefix('order-products')->as('order-products.')->group(function () {
        Route::get('/order/{id}', 'getByOrderId')->name('getByOrderId');
    });
    Route::apiResource('order-products', OrderProductController::class)->only(['update', 'destroy']);
    //order-products

    // reviews routes
    Route::controller(ReviewController::class)->name('reviews.')->prefix('/reviews')->group(function () {
        Route::get('/product/{product_id}', 'getByProductId')->name('getByProductId');
        Route::get('/service/{service_id}', 'getByServiceId')->name('getByServiceId');
        Route::get('/user/{user_id}', 'getByUserId')->name('getByUserId');
    });
    Route::apiResource('reviews', ReviewController::class)->except(['index']);
    // reviews routes

    // wishlists routes
    Route::controller(WishlistController::class)->name('wishlists.')->prefix('wishlists')->group(function () {
        Route::get('', 'viewWishlist')->name('viewWishlist');
        Route::post('', 'addToWishlist')->name('addToWishlist');
        Route::delete('/remove/{productId}', 'removeFromWishlist')->name('removeFromWishlist');
    });
    // wishlists routes


    //Contact Messages EndPoint
    Route::apiResource('contact-messages', ContactMessageController::class)->only(['store', 'show']);
    //Contact Messages EndPoint
    //Pages
    Route::apiResource('pages', PageController::class)->only(['show']);
    //Pages
    //page sections routes
    Route::controller(PageSectionController::class)->prefix('page-sections')->name('page-sections.')->group(function () {
        Route::get('/{page_id}', 'index')->name('index');
    });
    //page sections routes

    //hero-sliders
    Route::apiResource('hero-sliders', HeroSliderController::class)->only(['index', 'show']);
    //hero-sliders

    //banners
    Route::apiResource('banners', BannerController::class)->only(['index', 'show']);
    //banners


    //paypal payment gateway
    Route::controller(PaypalController::class)->prefix('paypal')->as('paypal.')->group(function () {
        Route::post('/create-payment', 'createPayment')->name('createPayment');
        Route::post('/capture-payment', 'capturePayment')->name('capturePayment');
        Route::get('/success', 'success')->name('success');
        Route::get('/cancel', 'cancel')->name('cancel');


    });
    //end paypal payment gateway

    //stripe payment gateway
    Route::controller(StripeController::class)->prefix('stripe')->as('stripe.')->group(function () {
        Route::post('/create-payment', 'createPayment')->name('createPayment');
        Route::post('/capture-payment', 'capturePayment')->name('capturePayment');
        Route::get('/success', 'success')->name('success');
        Route::get('/cancel', 'cancel')->name('cancel');


    });
    //end stripe payment gateway

    //Vendors
    Route::controller(VendorController::class)->prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/slugs/{slug}', 'showBySlug')->name('showBySlug');
        Route::get('/featured', 'getFeatured')->name('getFeatured');
    });
    Route::apiResource('vendors', VendorController::class)->only(['index', 'show']);
    //Vendors

    //flash-sales routes
    Route::controller(FlashSaleProductController::class)->prefix('flash-sale-products')->name('flash-sale-products.')->group(function () {
        Route::get('/show-at-home', 'getShowAtHome')->name('getShowAtHome');
    });
    Route::apiResource('flash-sales', FlashSaleController::class)->only('index');
    Route::apiResource('flash-sale-products', FlashSaleProductController::class)->only('index');
    //flash-sales routes


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
    //user-addresses
    Route::apiResource('user-addresses', UserAddressController::class);
    //user-addresses

    // coupons routes
    Route::controller(CouponController::class)->name('coupons.')->prefix('coupons')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('/apply', 'apply')->name('apply');
    });
    // coupons routes

});
