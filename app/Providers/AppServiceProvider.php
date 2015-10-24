<?php

namespace App\Providers;

use App\Services\SmsRu;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // SmsRu
        $this->app['smsRu'] = $this->app->share(function () {
            return new SmsRu(getenv('SMS_RU_KEY'));
        });
    }
}
