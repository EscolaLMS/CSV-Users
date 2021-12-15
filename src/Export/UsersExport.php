<?php

namespace EscolaLms\CsvUsers\Export;

use EscolaLms\Auth\Enums\GenderType;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    private Collection $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    public function collection(): Collection
    {
        return $this->users
            ->map(fn(Authenticatable $user) => [
                $user->getKey(),
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->age,
                GenderType::getName($user->gender),
                $user->country,
                $user->city,
                $user->street,
                $user->postcode,
                $user->is_active ? __('Active') : __('Inactive'),
                $user->onboarding_completed ? __('Completed') : __('Waiting'),
                $this->getRoles($user),
                $user->created_at,
                $user->updated_at
            ]);
    }

    public function headings(): array
    {
        return [
            __('Sl.no'),
            __('First Name'),
            __('Last Name'),
            __('Email'),
            __('Age'),
            __('Gender'),
            __('Country'),
            __('City'),
            __('Street'),
            __('Postcode'),
            __('Status'),
            __('Onboarding'),
            __('Roles'),
            __('Created at'),
            __('Updated at'),
        ];
    }

    private function getRoles(Authenticatable $user): string
    {
        return implode(', ', $user->getRoleNames()->toArray());
    }
}
