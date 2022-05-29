<?php

namespace App\Http\Requests\Manage;

use App\Http\Controllers\Manage\General\ImportController;
use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportForm extends FormRequest
{
    use NotifiesOnValidationFail;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'importer' => ['required', Rule::in(array_keys(ImportController::$importers))],

            'host' => ['required'],
            'port' => ['required', 'integer'],
            'database' => ['required'],
            'username' => ['required'],
            'password' => ['nullable']
        ];
    }
}