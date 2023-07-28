<?php

namespace EscolaLms\CsvUsers\Http\Resources;

use EscolaLms\Auth\Traits\ResourceExtandable;
use EscolaLms\CsvUsers\Models\Group;
use EscolaLms\CsvUsers\Models\User;
use EscolaLms\ModelFields\Enum\MetaFieldVisibilityEnum;
use EscolaLms\ModelFields\Facades\ModelFields;
use Illuminate\Http\Resources\Json\JsonResource;

class UserExportResource extends JsonResource
{
    public function __construct(User $group)
    {
        parent::__construct($group);
    }

    public function getResource(): Group
    {
        return $this->resource;
    }

    public function toArray($request): array
    {
        return array_merge(
            [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'first_name' => $this->resource->first_name,
                'last_name' => $this->resource->last_name,
                'email' => $this->resource->email,
                'password' => '',
                'is_active' => $this->resource->is_active,
                'created_at' => $this->resource->created_at,
                'email_verified' => $this->resource->email_verified,
                'roles' => $this->resource->roles ? array_map(function ($role) {
                    return $role['name'];
                }, $this->resource->roles->toArray()) : [],
            ],
            ModelFields::getExtraAttributesValues($this->resource, MetaFieldVisibilityEnum::ADMIN),
            ModelFields::getExtraAttributesValues($this->resource, MetaFieldVisibilityEnum::PUBLIC),
        );
    }
}
