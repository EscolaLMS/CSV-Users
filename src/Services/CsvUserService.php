<?php

namespace EscolaLms\CsvUsers\Services;

use EscolaLms\Auth\Dtos\UserFilterCriteriaDto;
use EscolaLms\Auth\Repositories\Contracts\UserRepositoryContract;
use EscolaLms\CsvUsers\Events\EscolaLmsImportedNewUserTemplateEvent;
use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use Illuminate\Database\Eloquent\Model;
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

    public function saveUserFromImport(Collection $data, string $returnUrl): Model
    {
        if ($user = $this->userRepository->findByEmail($data->get('email'))) {
            $user = $this->userRepository->update($data->toArray(), $user->getKey());
        } else {
            $data['is_active'] = true;

            $user = $this->userRepository->create($data->toArray());
            $user->markEmailAsVerified();
            event(new EscolaLmsImportedNewUserTemplateEvent($user, $returnUrl));
        }

        $user->syncRoles($data->get('roles'));
        $user->syncPermissions($data->get('permissions'));

        return $user;
    }
}
