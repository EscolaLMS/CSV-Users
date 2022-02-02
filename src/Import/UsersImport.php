<?php

namespace EscolaLms\CsvUsers\Import;

use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    private string $returnUrl;

    public function __construct(string $returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    public function collection(Collection $rows): void
    {
        $csvUserService = app(CsvUserServiceContract::class);

        $rows = $this->prepareDataToImport($rows);
        $this->validateData($rows);

        foreach ($rows as $row) {
            $csvUserService->saveUserFromImport($row, $this->returnUrl);
        }
    }

    public function rules(): array
    {
        return [
            '*.email' => 'required',
            '*.first_name' => 'required',
            '*.last_name' => 'required',
            '*.roles' => ['nullable', 'array'],
            '*.roles.*' => ['exists:roles,name'],
            '*.permissions' => ['nullable', 'array'],
            '*.permissions.*' => ['exists:permissions,name'],
        ];
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

            $item->put('is_active', $item->get('is_active') ?? false);
            $item->forget('created_at');
            $item->forget('updated_at');

            $item->put('path_avatar', $item->get('path_avatar') !== null && Storage::exists($item->get('path_avatar'))
                ? $item->get('path_avatar')
                : null
            );

            return $item;
        });
    }

    private function validateData(Collection $data)
    {
        Validator::make($data->toArray(), $this->rules())->validate();
    }
}
