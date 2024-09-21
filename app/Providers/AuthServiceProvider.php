<?php

namespace App\Providers;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-panel', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-own-advert', function (User $user, Advert $advert) {
            return $advert->user_id === $user->id;
        });
    }
}
