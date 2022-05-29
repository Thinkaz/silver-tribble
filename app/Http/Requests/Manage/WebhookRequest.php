<?php

namespace App\Http\Requests\Manage;

use App\Models\Webhook;
use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebhookRequest extends FormRequest
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
            'url' => ['required', 'url'],
            'type' => ['required', 'integer', Rule::in(Webhook::TYPE_DISCORD, Webhook::TYPE_CUSTOM)],
            'description' => ['nullable', 'string', 'max:100'],
            'triggers_on' => ['required', 'array'],
            'triggers_on.*' => ['required', 'string', Rule::in(array_keys(Webhook::$triggers))]
        ];
    }
}
