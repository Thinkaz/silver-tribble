<?php

namespace App\Http\Requests\Manage;

use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;

class PollForm extends FormRequest
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
            'title' => 'required|string',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'answers' => [
                'required',
                'array',
                function($attribute, $value, $fail) {
                    if (in_array(null, $value, true)) {
                        $fail('Answer inputs can\'t be empty. You need to fill out all answer inputs, or delete them!');
                    }
                }
            ]
        ];
    }
}
