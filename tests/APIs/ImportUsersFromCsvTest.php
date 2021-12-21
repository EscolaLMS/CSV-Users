<?php

namespace EscolaLms\CsvUsers\Tests\APIs;

use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\CsvUsers\Enums\CsvUserPermissionsEnum;
use EscolaLms\CsvUsers\Import\UsersImport;
use EscolaLms\CsvUsers\Tests\Models\User;
use EscolaLms\CsvUsers\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
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

        Excel::assertImported('users.csv');

        $tutor = $this->makeInstructor();
        $response = $this->actingAs($tutor, 'api')->postJson('/api/admin/csv/users', [
            'file' => UploadedFile::fake()->create('users.csv'),
        ]);
        $response->assertForbidden();

        $tutor->givePermissionTo(CsvUserPermissionsEnum::CSV_USERS_IMPORT);

        $response = $this->actingAs($tutor, 'api')->postJson('/api/admin/csv/users', [
            'file' => UploadedFile::fake()->create('users.csv'),
        ]);
        $response->assertOk();

        Excel::assertImported('users.csv');
    }

    public function testUsersImport(): void
    {
        $importData = $this->prepareImportData();

        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin, 'api')->postJson('/api/admin/csv/users', [
            'file' => UploadedFile::fake()->create('users.csv'),
        ]);
        $response->assertOk();

        Excel::assertImported('users.csv', function(UsersImport $import) use ($importData) {
            $import->collection($importData);
            return true;
        });

        $importData->each(function($user) {
            $this->assertDatabaseHas('users', [
                'email' => $user->get('email'),
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
            ]);

            $dbUser = User::where('email', $user->get('email'))->firstOrFail();
            $this->assertEqualsCanonicalizing($dbUser->roles->pluck('name')->toArray(), $user->get('roles'));
            $this->assertEqualsCanonicalizing($dbUser->permissions->pluck('name')->toArray(), $user->get('permissions'));
        });
    }

    public function testUsersImportValidation(): void
    {
        $invalidImportData = collect();

        for ($i = 0; $i < $this->faker->numberBetween(1, 5); $i++) {
            $invalidImportData->push(collect([
                'test' => $this->faker->text(15),
            ]));
        }

        $admin = $this->makeAdmin();
        $response = $this->actingAs($admin, 'api')->postJson('/api/admin/csv/users', [
            'file' => UploadedFile::fake()->create('users.csv'),
        ]);
        $response->assertOk();

        Excel::assertImported('users.csv', function(UsersImport $import) use ($invalidImportData) {
            $validator = Validator::make($invalidImportData->toArray(), $import->rules());
            $this->assertTrue($validator->fails());
            return true;
        });
    }

    private function prepareImportData(): Collection
    {
        $admin = $this->makeAdmin();

        $adminData = collect([
            'email' => $admin->email,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => json_encode($admin->roles->pluck('name')),
        ]);

        $studentData = collect([
            'email' => $this->faker->email,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'roles' => json_encode([UserRole::STUDENT, UserRole::TUTOR]),
            'permissions' => json_encode([CsvUserPermissionsEnum::CSV_USERS_IMPORT]),
        ]);

        $importData = collect([$adminData, $studentData]);

        for ($i = 0; $i < $this->faker->numberBetween(1, 10); $i++) {
            $importData->push(collect([
                'email' => $this->faker->email,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
            ]));
        }

        return $importData;
    }
}
