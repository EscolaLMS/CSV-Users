<?php

namespace EscolaLms\CsvUsers\Http\Requests;

use EscolaLms\CsvUsers\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ImportGroupFromCsvAPIRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('import', User::class);
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:csv,txt'],
            'return_url' => ['required', 'string'],
        ];
    }
}
