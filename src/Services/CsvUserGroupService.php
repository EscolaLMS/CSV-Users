<?php

namespace EscolaLms\CsvUsers\Services;

use EscolaLms\Auth\Dtos\UserFilterCriteriaDto;
use EscolaLms\Auth\EscolaLmsAuthServiceProvider;
use EscolaLms\Auth\Repositories\Contracts\UserGroupRepositoryContract;
use EscolaLms\Auth\Repositories\Contracts\UserRepositoryContract;
use EscolaLms\CsvUsers\Services\Contracts\CsvUserGroupServiceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class CsvUserGroupService implements CsvUserGroupServiceContract
{
    private UserGroupRepositoryContract $groupRepository;

    public function __construct(UserGroupRepositoryContract $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function saveGroupFromImport(Collection $data): Model
    {
        return $this->groupRepository->create($data->toArray());
    }
}
