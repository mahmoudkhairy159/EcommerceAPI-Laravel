<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'admins' => 'c,r,u,d',
            'vendors' => 'c,r,u,d',
            'brands' => 'c,r,u,d',
            'carts' => 'c,r,u,d',
            'cart_products' => 'c,r,u,d',
            'categories' => 'c,r,u,d',
            'orders' => 'c,r,u,d',
            'order_products' => 'c,r,u,d',
            'permissions' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'products' => 'c,r,u,d',
            'product_images' => 'c,r,u,d',
            'product_reviews' => 'r,d',
            'services' => 'c,r,u,d',
            'settings' => 'c,r,u,d',
            'related_products' => 'c,r,u,d',
            'product_accessories'=>'c,r,u,d',
            'related_services' => 'c,r,u,d',
            'wishlists' => 'c,r,u,d',
            'wishlist_products' => 'c,r,u,d',
            'contact_messages' => 'c,r,u,d',
            'assets' => 'r,u',
            'pages' => 'r,u,d',
            'hero_sliders' => 'c,r,u,d',
            'banners' => 'c,r,u,d',
            'flash_sales' => 'r,u',
            'flash_sale_Products' => 'c,r,u,d',
            'coupons' => 'c,r,u,d',
            'shipping_rules' => 'c,r,u,d',
            'user_addresses' => 'c,r,u,d',
            'paypal_settings' => 'r,u',
            'stripe_settings' => 'r,u',
            'transactions'=>'r',
        ],
        'user' => [
            'profile' => 'r,u',
        ],
        'vendor' => [
            'profile' => 'r,u',
        ],
        // 'role_name' => [
        //     'module_1_name' => 'c,r,u,d',
        // ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
