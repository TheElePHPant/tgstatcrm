<?php

namespace App\Providers;

use App\Models\Administrator;
use App\Models\Channel;
use App\Models\Client;
use App\Models\Transaction;
use App\Observers\AdministratorObserver;
use App\Observers\ChannelObserver;
use App\Observers\ClientObserver;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Channel::observe(ChannelObserver::class);
        Client::observe(ClientObserver::class);
        Administrator::observe(AdministratorObserver::class);
    }
}
