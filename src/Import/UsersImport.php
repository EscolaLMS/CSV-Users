<?php

namespace EscolaLms\CsvUsers\Import;

use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        $csvUserService = app(CsvUserServiceContract::class);

        $rows = $this->prepareDataToImport($rows);
        $this->validateData($rows);

        foreach ($rows as $row) {
            $csvUserService->saveUserFromImport($row);
        }
    }

    private function prepareDataToImport(Collection $data): Collection
    {
        return $data->map(function ($item) {
            $item->put('roles', $item->get('roles') !== null
                ? json_decode($item->get('roles'), true)
                : []
            );

            $item->put('permissions', $item->get('permissions') !== null
                ? json_decode($item->get('permissions'), true)
                : []
            );

            return $item;
        });
    }

    private function validateData(Collection $data)
    {
        Validator::make($data->toArray(), [
            '*.email' => 'required',
            '*.first_name' => 'required',
            '*.last_name' => 'required',
            '*.roles' => ['nullable', 'array'],
            '*.roles.*' => ['exists:roles,name'],
            '*.permissions' => ['nullable', 'array'],
            '*.permissions.*' => ['exists:permissions,name'],
        ])->validate();
    }
}
