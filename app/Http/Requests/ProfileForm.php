<?php

namespace App\Http\Requests;

use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;

class ProfileForm extends FormRequest
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
            'username' => 'required|string|max:100',
            'bio' => 'nullable|max:2000',
            'signature' => 'nullable|max:2000',
            'background_img' => 'nullable|url'
        ];
    }
}
