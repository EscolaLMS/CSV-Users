<?php

namespace EscolaLms\CsvUsers\Tests\APIs;

use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\CsvUsers\Export\UsersExport;
use EscolaLms\CsvUsers\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Maatwebsite\Excel\Facades\Excel;

class ExportUsersToCsvTest extends TestCase
{
    use CreatesUsers, WithFaker, DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Excel::fake();
    }

    public function testUnauthorizedAccessToExportUsersToCsv(): void
    {
        $response = $this->getJson('/api/admin/csv/users');
        $response->assertUnauthorized();
    }

    public function testAccessToExportUsersToCSV(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'api')->getJson('/api/admin/csv/users');
        $response->assertOk();

        Excel::assertDownloaded('users.csv');
    }

    public function testExportUsersToCsvWithCriteria(): void
    {
        $name = $this->faker->firstName;

        $user = $this->makeStudent([
            'first_name' => $name,
        ]);

        $user2 = $this->makeStudent();

        $admin = $this->makeAdmin([
            'first_name' => $name
        ]);

        $response = $this->actingAs($admin, 'api')->getJson('/api/admin/csv/users');
        $response->assertOk();

        Excel::assertDownloaded('users.csv', function (UsersExport $export) use ($user, $user2, $admin) {
            return $export->collection()->contains('email', $user->email)
                && $export->collection()->contains('email', $user2->email)
                && $export->collection()->contains('email', $admin->email);
        });

        $response = $this->actingAs($admin, 'api')->getJson('/api/admin/csv/users/?search=' . $user->email);
        $response->assertOk();

        Excel::assertDownloaded('users.csv', function (UsersExport $export) use ($user, $user2, $admin) {
            return $export->collection()->contains('email', $user->email)
                && !$export->collection()->contains('email', $user2->email)
                && !$export->collection()->contains('email', $admin->email);
        });

        $response = $this->actingAs($admin)
            ->getJson('/api/admin/csv/users/?search=' . $name . '&role=' . UserRole::ADMIN);
        $response->assertOk();

        Excel::assertDownloaded('users.csv', function (UsersExport $export) use ($user, $user2, $admin) {
            return !$export->collection()->contains('email', $user->email)
                && !$export->collection()->contains('email', $user2->email)
                && $export->collection()->contains('email', $admin->email);
        });

        $response = $this->actingAs($admin)
            ->getJson('/api/admin/csv/users/?search=' . $name. '&role=' . UserRole::STUDENT);
        $response->assertOk();

        Excel::assertDownloaded('users.csv', function (UsersExport $export) use ($user, $user2, $admin) {
            return $export->collection()->contains('email', $user->email)
                && !$export->collection()->contains('email', $user2->email)
                && !$export->collection()->contains('email', $admin->email);
        });
    }
}
