<?php

namespace App\Providers;

use App\Interfaces\ContactsRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\ContactsRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ContactsRepositoryInterface::class, ContactsRepository::class);
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
