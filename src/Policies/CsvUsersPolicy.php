<?php

namespace EscolaLms\CsvUsers\Policies;

use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Core\Models\User;
use EscolaLms\CsvUsers\Enums\CsvUserPermissionsEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class CsvUsersPolicy
{
    use HandlesAuthorization;

    public function export(User $user): bool
    {
        if ($user->hasRole(UserRole::ADMIN)) {
            return true;
        }

        if ($user->can(CsvUserPermissionsEnum::CSV_USERS_EXPORT)) {
            return true;
        }

        return false;
    }
}
