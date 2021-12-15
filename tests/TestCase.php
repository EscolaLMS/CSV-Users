<?php

namespace EscolaLms\CsvUsers\Tests;

use EscolaLms\Auth\EscolaLmsAuthServiceProvider;
use EscolaLms\Auth\Tests\Models\Client;
use EscolaLms\Core\Tests\TestCase as CoreTestCase;
use EscolaLms\CsvUsers\Database\Seeders\CsvUsersPermissionSeeder;
use EscolaLms\CsvUsers\EscolaLmsCsvUsersServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends CoreTestCase
{
    protected $response;

    protected function setUp(): void
    {
        parent::setUp();
        Passport::useClientModel(Client::class);
        $this->seed(CsvUsersPermissionSeeder::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            ...parent::getPackageProviders($app),
            EscolaLmsAuthServiceProvider::class,
            PermissionServiceProvider::class,
            PassportServiceProvider::class,
            EscolaLmsCsvUsersServiceProvider::class,
        ];
    }

    public function assertApiSuccess()
    {
        $this->response->assertJson(['success' => true]);
    }
}
