<?php

namespace App\Http\Requests\Manage;

use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;

class AssignForm extends FormRequest
{
    use NotifiesOnValidationFail;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'package' => ['required', 'exists:packages,id']
        ];
    }
}
