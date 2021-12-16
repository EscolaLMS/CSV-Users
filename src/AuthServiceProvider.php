<?php

namespace EscolaLms\CsvUsers;

use EscolaLms\CsvUsers\Models\User;
use EscolaLms\CsvUsers\Policies\CsvUsersPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => CsvUsersPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes();
        }
    }
}
