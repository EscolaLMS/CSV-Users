<?php

namespace EscolaLms\CsvUsers\Policies;

use EscolaLms\Auth\Models\User;
use EscolaLms\Core\Enums\UserRole;
use EscolaLms\CsvUsers\Enums\CsvUserPermissionsEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class CsvUserGroupsPolicy
{
    use HandlesAuthorization;

    public function export(User $user): bool
    {
        if ($user->hasRole(UserRole::ADMIN)) {
            return true;
        }

        return $user->can(CsvUserPermissionsEnum::CSV_USER_GROUP_EXPORT);
    }

    public function import(User $user): bool
    {
        if ($user->hasRole(UserRole::ADMIN)) {
            return true;
        }

        return $user->can(CsvUserPermissionsEnum::CSV_USER_GROUP_IMPORT);
    }
}
