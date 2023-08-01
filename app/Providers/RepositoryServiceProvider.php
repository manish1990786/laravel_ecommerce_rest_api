<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\Order\OrderRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Interfaces\Product\ProductRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Interfaces\Customer\CustomerRepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Interfaces\PaymentProviders\SuperPaymentProviderInterface;
use App\Repositories\PaymentProviders\SuperPaymentProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(SuperPaymentProviderInterface::class, SuperPaymentProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
