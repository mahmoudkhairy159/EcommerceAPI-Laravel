<?php

return [
    'something-went-wrong' => "something went wrong",
    'data-not-found' => "data not found",
    'unauthorized' => "Unauthorized",
    "admins" => [
        "admins" => "Admins",
        "admin" => "Admin",
        "show" => "Show Admins",
        "create" => "create a Admin",
        "update" => "update a Admin",
        "delete" => "delete a Admin",
        "destroy" => "destroy a Admin",
        "created-successfully" => "Admin created successfully",
        "updated-successfully" => "Admin updated successfully",
        "deleted-successfully" => "Admin deleted successfully",
        "created-failed" => "Admin created failed",
        "updated-failed" => "Admin updated failed",
        "deleted-failed" => "Admin deleted failed",
    ],
    "coupons" => [
        "coupons" => "Coupons",
        "coupon" => "Coupon",
        "show" => "Show Coupon",
        "create" => "create a Coupon",
        "update" => "update a Coupon",
        "delete" => "delete a Coupon",
        "destroy" => "destroy a Coupon",
        "created-successfully" => "Coupon created successfully",
        "updated-successfully" => "Coupon updated successfully",
        "deleted-successfully" => "Coupon deleted successfully",
        "created-failed" => "Coupon created failed",
        "updated-failed" => "Coupon updated failed",
        "deleted-failed" => "Coupon deleted failed",
        "invalid-code"=>"Invalid or expired coupon.",
        "no-longer-available"=>"This coupon is no longer available.",
        "applied-successfully"=>"Coupon applied successfully",
    ],
    "vendors" => [
        "vendors" => "Vendors",
        "admin" => "Vendor",
        "show" => "Show vendors",
        "create" => "create a Vendor",
        "update" => "update a Vendor",
        "delete" => "delete a Vendor",
        "destroy" => "destroy a Vendor",
        "created-successfully" => "Vendor created successfully",
        "updated-successfully" => "Vendor updated successfully",
        "deleted-successfully" => "Vendor deleted successfully",
        "created-failed" => "Vendor created failed",
        "updated-failed" => "Vendor updated failed",
        "deleted-failed" => "Vendor deleted failed",
    ],
    "heroSliders" => [
        "heroSliders" => "heroSliders",
        "heroSlider" => "heroSlider",
        "show" => "Show heroSliders",
        "create" => "create a heroSlider",
        "update" => "update a heroSlider",
        "delete" => "delete a heroSlider",
        "destroy" => "destroy a heroSlider",
        "created-successfully" => "heroSlider created successfully",
        "updated-successfully" => "heroSlider updated successfully",
        "deleted-successfully" => "heroSlider deleted successfully",
        "created-failed" => "heroSlider created failed",
        "updated-failed" => "heroSlider updated failed",
        "deleted-failed" => "heroSlider deleted failed",
    ],
    "settings" => [
        "settings" => "Settings",
        "setting" => "Settings",
        "show" => "Show Settings",
        "create" => "create Settings",
        "update" => "update Settings",
        "delete" => "delete Settings",
        "destroy" => "destroy Settings",
        "created-successfully" => "Settings created successfully",
        "updated-successfully" => "Settings updated successfully",
        "deleted-successfully" => "Settings deleted successfully",
        "created-failed" => "Settings created failed",
        "updated-failed" => "Settings updated failed",
        "deleted-failed" => "Settings deleted failed",
    ],
    "roles" => [
        "roles" => "Roles",
        "role" => "Role",
        "show" => "Show Roles",
        "create" => "create a Role",
        "update" => "update a Role",
        "delete" => "delete a Role",
        "destroy" => "destroy a Role",
        "created-successfully" => "Role created successfully",
        "updated-successfully" => "Role updated successfully",
        "deleted-successfully" => "Role deleted successfully",
        "created-failed" => "Role created failed",
        "updated-failed" => "Role updated failed",
        "deleted-failed" => "Role deleted failed",
    ],

    "users" => [
        "users" => "Users",
        "user" => "User",
        "show" => "Show Users",
        "create" => "create a User",
        "update" => "update a User",
        "delete" => "delete a User",
        "destroy" => "destroy a User",
        "created-successfully" => "User created successfully",
        "updated-successfully" => "User updated successfully",
        "deleted-successfully" => "User deleted successfully",
        "followed-successfully" => "User followed successfully",
        "unFollowed-successfully" => "User unFollowed successfully",
        "created-failed" => "User created failed",
        "updated-failed" => "User updated failed",
        "deleted-failed" => "User deleted failed",
        "followed-failed" => "User followed failed",
        "unFollowed-failed" => "User unFollowed failed",
        "current-password-incorrect" => "User current password incorrect",
    ],
    "userProfiles" => [
        "userProfiles" => "UserProfiles",
        "role" => "UserProfile",
        "show" => "Show UserProfiles",
        "create" => "create a UserProfile",
        "update" => "update a UserProfile",
        "delete" => "delete a UserProfile",
        "destroy" => "destroy a UserProfile",
        "created-successfully" => "UserProfile created successfully",
        "updated-successfully" => "UserProfile updated successfully",
        "deleted-successfully" => "UserProfile deleted successfully",
        "created-failed" => "UserProfile created failed",
        "updated-failed" => "UserProfile updated failed",
        "deleted-failed" => "UserProfile deleted failed",
    ],

    'auth' => [

        'otp' => [

            'your_otp_code_is' => 'Your OTP Code  code is : :otp',
            'otp_code_valid_for_x_minutes' => 'This OTP Code  is valid for :minutes minutes.',
            'otp_email_subject' => 'OTP Code',

        ],
        'register' => [
            'success_register_message' => 'Registration successful, And The Verification code sent via email. ',
        ],
        'login' => [
            'invalid_email_or_password' => 'Invalid Email or Password',
            'your_account_is_blocked' => 'Your Account is blocked',
            'your_account_is_inactive' => 'Your Account is inactive',
            'logged_in_successfully' => 'Logged In Successfully',
            'logged_in_successfully_and_Verification_code_sent' => 'Logged In Successfully And The Verification code sent via email. ',

        ],
        'verification' => [
            'invalid_otp' => 'Invalid otp',
            'valid_otp' => 'Valid otp',
            'verification_failed' => 'Verification Failed',
            'already_verified' => 'Already Verified',
            'verified_successfully' => 'Verification Successfully',
            'cant_resend_verification_otp_code' => 'Cant Resend Verification OTP Code',
            'verification_otp_code_resend_successfully' => 'Verification OTP Code Resend SuccessFully',
        ],
        'forgotPassword' => [
            'user_not_found' => 'User not found with this email address.',
            'otp_code_email_sent_successfully' => 'OTP code for reset password sent successfully',
        ],
        'resetPassword' => [
            'reset-successfully' => 'Password reset successfully',
            'reset-failed' => 'Unable to reset password. Please try again later.y',
        ],

        'logout' => [
            'logout_successfully' => 'User Logged out successfully.',
            'otp_code_email_sent_successfully' => 'OTP code for reset password sent successfully',
        ],

    ],
    "brands" => [
        "brands" => "Brands",
        "brand" => "Brand",
        "show" => "Show Brands",
        "create" => "create Brand",
        "update" => "update Brand",
        "delete" => "delete Brand",
        "destroy" => "destroy Brand",
        "created-successfully" => "Brand created successfully",
        "updated-successfully" => "Brand updated successfully",
        "deleted-successfully" => "Brand deleted successfully",
        "restored-successfully" => "Brand restored successfully",
        "created-failed" => "Brand created failed",
        "updated-failed" => "Brand updated failed",
        "deleted-failed" => "Brand deleted failed",
        "restored-failed" => "Brand restored failed",
    ],
    "categories" => [
        "categories" => "Categories",
        "category" => "Category",
        "show" => "Show Categories",
        "create" => "create Category",
        "update" => "update Category",
        "delete" => "delete Category",
        "destroy" => "destroy Category",
        "created-successfully" => "Category created successfully",
        "updated-successfully" => "Category updated successfully",
        "serial-updated-successfully" => "Category serial updated successfully",
        "deleted-successfully" => "Category deleted successfully",
        "restored-successfully" => "Category restored successfully",
        "created-failed" => "Category created failed",
        "updated-failed" => "Category updated failed",
        "deleted-failed" => "Category deleted failed",
        "restored-failed" => "Category restored failed",
    ],
    "products" => [
        "products" => "Products",
        "product" => "Product",
        "show" => "Show Products",
        "create" => "create Product",
        "update" => "update Product",
        "delete" => "delete Product",
        "destroy" => "destroy Product",
        "created-successfully" => "Product created successfully",
        "updated-successfully" => "Product updated successfully",
        "deleted-successfully" => "Product deleted successfully",
        "restored-successfully" => "Product restored successfully",
        "created-failed" => "Product created failed",
        "updated-failed" => "Product updated failed",
        "deleted-failed" => "Product deleted failed",
        "restored-failed" => "Product restored failed",
    ],
    "productImages" => [
        "productImages" => "ProductImages",
        "product" => "ProductImage",
        "show" => "Show ProductImages",
        "create" => "create ProductImage",
        "update" => "update ProductImage",
        "delete" => "delete ProductImage",
        "destroy" => "destroy ProductImage",
        "created-successfully" => "ProductImage created successfully",
        "updated-successfully" => "ProductImage updated successfully",
        "deleted-successfully" => "ProductImage deleted successfully",
        "restored-successfully" => "ProductImage restored successfully",
        "created-failed" => "ProductImage created failed",
        "updated-failed" => "ProductImage updated failed",
        "deleted-failed" => "ProductImage deleted failed",
        "restored-failed" => "ProductImage restored failed",
    ],

    "relatedProducts" => [
        "relatedProducts" => "RelatedProducts",
        "relatedProduct" => "RelatedProduct",
        "show" => "Show RelatedProducts",
        "create" => "create RelatedProduct",
        "update" => "update RelatedProduct",
        "delete" => "delete RelatedProduct",
        "destroy" => "destroy RelatedProduct",
        "created-successfully" => "RelatedProduct created successfully",
        "updated-successfully" => "RelatedProduct updated successfully",
        "deleted-successfully" => "RelatedProduct deleted successfully",
        "restored-successfully" => "RelatedProduct restored successfully",
        "created-failed" => "RelatedProduct created failed",
        "updated-failed" => "RelatedProduct updated failed",
        "deleted-failed" => "RelatedProduct deleted failed",
        "restored-failed" => "RelatedProduct restored failed",
    ],
    "relatedServices" => [
        "relatedServices" => "RelatedServices",
        "relatedService" => "RelatedService",
        "show" => "Show RelatedServices",
        "create" => "create RelatedService",
        "update" => "update RelatedService",
        "delete" => "delete RelatedService",
        "destroy" => "destroy RelatedService",
        "created-successfully" => "RelatedService created successfully",
        "updated-successfully" => "RelatedService updated successfully",
        "deleted-successfully" => "RelatedService deleted successfully",
        "restored-successfully" => "RelatedService restored successfully",
        "created-failed" => "RelatedService created failed",
        "updated-failed" => "RelatedService updated failed",
        "deleted-failed" => "RelatedService deleted failed",
        "restored-failed" => "RelatedService restored failed",
    ],
    "services" => [
        "services" => "services",
        "services" => "Service",
        "show" => "Show Services",
        "create" => "create Service",
        "update" => "update Service",
        "delete" => "delete Service",
        "destroy" => "destroy Service",
        "created-successfully" => "Service created successfully",
        "updated-successfully" => "Service updated successfully",
        "deleted-successfully" => "Service deleted successfully",
        "restored-successfully" => "Service restored successfully",
        "created-failed" => "Service created failed",
        "updated-failed" => "Service updated failed",
        "deleted-failed" => "Service deleted failed",
        "restored-failed" => "Service restored failed",
    ],
    "carts" => [
        "carts" => "carts",
        "cart" => "Cart",
        "show" => "Show carts",
        "create" => "create a Cart",
        "update" => "update a Cart",
        "delete" => "delete a Cart",
        "destroy" => "destroy a Cart",
        "created-successfully" => "Cart created successfully",
        "updated-successfully" => "Cart updated successfully",
        "deleted-successfully" => "Cart deleted successfully",
        "restored-successfully" => "Cart restored successfully",
        "created-failed" => "Cart created failed",
        "updated-failed" => "Cart updated failed",
        "deleted-failed" => "Cart deleted failed",
        "restored-failed" => "Cart restored failed",
    ],
    "orders" => [
        "orders" => "Orders",
        "order" => "Order",
        "show" => "Show Orders",
        "create" => "create Order",
        "update" => "update Order",
        "delete" => "delete Order",
        "destroy" => "destroy Order",
        "created-successfully" => "Order created successfully",
        "updated-successfully" => "Order updated successfully",
        "deleted-successfully" => "Order deleted successfully",
        "restored-successfully" => "Order restored successfully",
        "created-failed" => "Order created failed",
        "updated-failed" => "Order updated failed",
        "deleted-failed" => "Order deleted failed",
        "restored-failed" => "Order restored failed",
    ],
    "orderProducts" => [
        "orderProducts" => "Order Product",
        "orderProduct" => "Order Product",
        "show" => "Show order Product",
        "create" => "create order Product",
        "update" => "update order Product",
        "delete" => "delete order Product",
        "destroy" => "destroy order Product",
        "created-successfully" => "Order Product created successfully",
        "updated-successfully" => "Order Product updated successfully",
        "deleted-successfully" => "Order Product deleted successfully",
        "restored-successfully" => "Order Product restored successfully",
        "created-failed" => "Order Product created failed",
        "updated-failed" => "Order Product updated failed",
        "deleted-failed" => "Order Product deleted failed",
        "restored-failed" => "Order Product restored failed",
    ],

    "reviews" => [
        "reviews" => "Reviews",
        "Review" => "Review",
        "show" => "Show Reviews",
        "create" => "create a Review",
        "update" => "update a Review",
        "delete" => "delete a Review",
        "destroy" => "destroy a Review",
        "created-successfully" => "Review created successfully",
        "updated-successfully" => "Review updated successfully",
        "deleted-successfully" => "Review deleted successfully",
        "restored-successfully" => "Review restored successfully",
        "created-failed" => "Review created failed",
        "updated-failed" => "Review updated failed",
        "deleted-failed" => "Review deleted failed",
        "restored-failed" => "Review restored failed",
    ],
    "wishlists" => [
        "wishlists" => "wishlists",
        "wishlist" => "Wishlist",
        "show" => "Show carts",
        "create" => "create a Wishlist",
        "update" => "update a Wishlist",
        "delete" => "delete a Wishlist",
        "destroy" => "destroy a Wishlist",
        "created-successfully" => "Wishlist created successfully",
        "updated-successfully" => "Wishlist updated successfully",
        "deleted-successfully" => "Wishlist deleted successfully",
        "restored-successfully" => "Wishlist restored successfully",
        "created-failed" => "Wishlist created failed",
        "updated-failed" => "Wishlist updated failed",
        "deleted-failed" => "Wishlist deleted failed",
        "restored-failed" => "Wishlist restored failed",
    ],

    "contactMessages" => [
        "contactMessages" => "contactMessages",
        "contactMessages" => "contactMessages",
        "show" => "Show carts",
        "create" => "create a Contact Message",
        "update" => "update a Contact Message",
        "delete" => "delete a Contact Message",
        "destroy" => "destroy a Contact Message",
        "created-successfully" => "Contact Message created successfully",
        "updated-successfully" => "Contact Message updated successfully",
        "deleted-successfully" => "Contact Message deleted successfully",
        "restored-successfully" => "Contact Message restored successfully",
        "created-failed" => "Contact Message created failed",
        "updated-failed" => "Contact Message updated failed",
        "deleted-failed" => "Contact Message deleted failed",
        "restored-failed" => "Contact Message restored failed",
    ],
    "roles" => [
        "roles" => "Roles",
        "role" => "Role",
        "show" => "Show Roles",
        "create" => "create a Role",
        "update" => "update a Role",
        "delete" => "delete a Role",
        "destroy" => "destroy a Role",
        "created-successfully" => "Role created successfully",
        "updated-successfully" => "Role updated successfully",
        "deleted-successfully" => "Role deleted successfully",
        "created-failed" => "Role created failed",
        "updated-failed" => "Role updated failed",
        "deleted-failed" => "Role deleted failed",
    ],

    "pages" => [
        "pages" => "Pages",
        "page" => "Page",
        "show" => "Show Pages",
        "create" => "create a Page",
        "update" => "update a Page",
        "delete" => "delete a Page",
        "destroy" => "destroy a Page",
        "created-successfully" => "Page created successfully",
        "updated-successfully" => "Page updated successfully",
        "deleted-successfully" => "Page deleted successfully",
        "created-failed" => "Page created failed",
        "updated-failed" => "Page updated failed",
        "deleted-failed" => "Page deleted failed",
    ],
    "assets" => [
        "assets" => "Assets",
        "asset" => "Asset",
        "show" => "Show Assets",
        "create" => "create a Asset",
        "update" => "update a Asset",
        "delete" => "delete a Asset",
        "destroy" => "destroy a Asset",
        "created-successfully" => "Asset created successfully",
        "updated-successfully" => "Asset updated successfully",
        "deleted-successfully" => "Asset deleted successfully",
        "created-failed" => "Asset created failed",
        "updated-failed" => "Asset updated failed",
        "deleted-failed" => "Asset deleted failed",
    ],
];
