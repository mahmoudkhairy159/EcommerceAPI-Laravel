<?php

if (!function_exists('core')) {
    /**
     * Core helper.
     *
     * @return App\Helpers\Helpers\Core
     */
    function core()
    {
        return app()->make(App\Helpers\Core::class);
    }
}
