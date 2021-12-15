<?php

namespace EscolaLms\CsvUsers\Tests\APIs;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\CsvUsers\Export\UsersExport;
use EscolaLms\CsvUsers\Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;

class ExportUsersToCsvTest extends TestCase
{
    use CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        Excel::fake();
    }

    public function testAccessToExportUsers(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'api')->json(
            'GET',
            '/api/admin/csv/users'
        );
        $response->assertOk();
    }

    public function testExportUsersWithCriteria(): void
    {
        $date = now();

        $user = $this->makeStudent([
            'first_name' => 'Jan'
        ]);

        $user2 = $this->makeStudent();

        $admin = $this->makeAdmin([
            'first_name' => 'Jan'
        ]);

        $response = $this->actingAs($admin, 'api')->json(
            'GET',
            '/api/admin/csv/users'
        );

        $response->assertOk();

        Excel::assertDownloaded('users.csv', function(UsersExport $export) {
            $key = array_search(__('First Name'), $export->headings());
            return $export->collection()->contains($key, 'Jan');
        });
    }
}
