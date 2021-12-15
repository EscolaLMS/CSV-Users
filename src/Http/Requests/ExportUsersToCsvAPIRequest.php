<?php

namespace EscolaLms\CsvUsers\Http\Requests;

use EscolaLms\Auth\Http\Requests\Admin\UsersListRequest;
use EscolaLms\Auth\Models\User;

class ExportUsersToCsvAPIRequest extends UsersListRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('export', User::class);
    }
}
