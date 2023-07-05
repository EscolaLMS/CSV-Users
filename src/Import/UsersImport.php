<?php

namespace EscolaLms\CsvUsers\Import;

use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport extends AbstractUserImport implements ToCollection, WithHeadingRow
{
    public function __construct(string $returnUrl)
    {
        parent::__construct($returnUrl);
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
}
