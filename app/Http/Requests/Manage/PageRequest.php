<?php

namespace App\Http\Requests\Manage;

use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
        $uniqueRule = Rule::unique('pages', 'slug');
        if ($this->routeIs('manage.general.pages.update')) {
            $uniqueRule->ignore($this->route('page')->id);
        }

        return [
            'slug' => ['required', 'string', 'alpha_dash', $uniqueRule],
            'title' => ['required', 'string', 'max:100'],
            'content' => ['required'],
        ];
    }
}
