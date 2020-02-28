<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Validator::extend('alpha_spaces', function ($attribute, $value) {

            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value);

        });

        Validator::extend('alpha_underscore', function ($attribute, $value) {

            return preg_match('/^[\pL_]+$/u', $value);

        });

        Validator::extend('alamat_validation', function ($attribute, $value) {

            return preg_match('/^[\pL\s,0-9.\/:_-]+$/u', $value);

        });

        Validator::extend('no_telp_validation', function ($attribute, $value) {

            return preg_match('/^[0-9]+$/u', $value);

        });

        Validator::extend('latlng_validation', function ($attribute, $value) {

            return preg_match('/^[0-9.-]+$/u', $value);

        });

        Validator::extend('alphanumeric_space', function ($attribute, $value) {

            return preg_match('/^[0-9\pL\s]+$/u', $value);

        });
    }
}
