<?php

namespace EscolaLms\CsvUsers\Services;

use EscolaLms\Auth\Dtos\UserFilterCriteriaDto;
use EscolaLms\Auth\Repositories\Contracts\UserRepositoryContract;
use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use Illuminate\Support\Collection;

class CsvUserService implements CsvUserServiceContract
{
    private UserRepositoryContract $userRepository;

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getDataToExport(UserFilterCriteriaDto $userFilterCriteriaDto): Collection
    {
        return $this->userRepository->searchByCriteria($userFilterCriteriaDto->toArray());
    }
}
