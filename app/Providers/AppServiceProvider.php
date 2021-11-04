<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Blade::directive('money', function ($amount) {
            return "<?php echo '<small>R$</small>' . number_format($amount, 2); ?>";
        });

        Blade::directive('friendlyNumber', function ($friendly_number) {
            if(intval($friendly_number) == 0) return $friendly_number;
            if(strlen($friendly_number) <= 4) return str_pad($friendly_number, 4, "0", STR_PAD_LEFT);
            return $friendly_number;
        });
    }
}
