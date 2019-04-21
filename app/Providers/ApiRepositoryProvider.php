<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ApiRepositoryProvider extends ServiceProvider
{

    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Contract\UserInterface','App\Repositories\Eloquent\UserRepository');
        $this->app->bind('App\Repositories\Contract\DepartmentInterface','App\Repositories\Eloquent\DepartmentRepository');
        $this->app->bind('App\Repositories\Contract\EmployeeInterface','App\Repositories\Eloquent\EmployeeRepository');
    }

    /**
     * provides the application services.
     *
     * @return [Array]
     */
    public function provides()
    {

    }
}
