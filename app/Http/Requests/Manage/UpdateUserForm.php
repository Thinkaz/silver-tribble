<?php

namespace App\Http\Requests\Manage;

use App\Models\Role;
use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserForm extends FormRequest
{
    use NotifiesOnValidationFail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'avatar' => ['required', 'url'],
            'bio' => ['nullable', 'max:2000'],
            'signature' => ['nullable', 'max:2000'],
            'background_img' => ['nullable', 'url'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', Rule::exists(Role::class, 'id')],
        ];
    }
}
