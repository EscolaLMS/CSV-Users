<?php

namespace EscolaLms\CsvUsers;

use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use EscolaLms\CsvUsers\Services\CsvUserService;
use Illuminate\Support\ServiceProvider;

/**
 * SWAGGER_VERSION
 */
class EscolaLmsCsvUsersServiceProvider extends ServiceProvider
{
    public $singletons = [
        CsvUserServiceContract::class => CsvUserService::class,
    ];

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
    }
}
