<?php

namespace EscolaLms\CsvUsers\Services\Contracts;

use EscolaLms\Auth\Dtos\UserFilterCriteriaDto;
use Illuminate\Support\Collection;

interface CsvUserServiceContract
{
    public function getDataToExport(UserFilterCriteriaDto $userFilterCriteriaDto): Collection;
}
