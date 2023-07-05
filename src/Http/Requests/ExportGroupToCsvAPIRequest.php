<?php

namespace EscolaLms\CsvUsers\Http\Requests;

use EscolaLms\CsvUsers\Enums\ExportFormatEnum;
use EscolaLms\CsvUsers\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportGroupToCsvAPIRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('export', Group::class);
    }

    public function rules()
    {
        return [
            'format' => ['sometimes', 'string', Rule::in(ExportFormatEnum::getValues())]
        ];
    }

    public function getGroup(): Group
    {
        return Group::query()->findOrFail($this->route('group'));
    }
}
