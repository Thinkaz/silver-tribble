<?php

namespace App\Http\Requests\Manage;

use App\Rules\HexColor;
use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServerForm extends FormRequest
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
            'name' => 'required',
            'icon' => 'nullable|string',
            'color' => ['required', new HexColor],
            'type' => ['required', Rule::in(array_keys(config('cosmo.games')))],
            'ip' => ['required', 'string'],
            'port' => 'required|integer',
            'image' => 'nullable|url',
            'description' => 'nullable|string'
        ];
    }
}
