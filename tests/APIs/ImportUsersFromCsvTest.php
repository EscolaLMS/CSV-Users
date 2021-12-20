<?php

namespace EscolaLms\CsvUsers\Tests\APIs;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\CsvUsers\Import\UsersImport;
use EscolaLms\CsvUsers\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ImportUsersFromCsvTest extends TestCase
{
    use CreatesUsers, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Excel::fake();
    }

    public function testUnauthorizedAccessToImportUsersFromCsv(): void
    {
        $response = $this->postJson('/api/admin/csv/users');
        $response->assertUnauthorized();
    }

    public function testAccessToImportUsersFromCSV(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'api')->postJson('/api/admin/csv/users', [
            'file' => UploadedFile::fake()->create('users.csv'),
        ]);
        $response->assertOk();

        Excel::assertImported('users.csv', function(UsersImport $import) {
            return true;
        });
    }

    public function testImportUsersFromCsv(): void
    {
        // todo
    }
}
