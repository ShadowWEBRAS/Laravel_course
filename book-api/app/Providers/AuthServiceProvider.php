<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Rent;
use App\Models\User;
use App\Policies\BookPolicy;
use App\Policies\RentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Book::class => BookPolicy::class,
        Rent::class => RentPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
