<?php

namespace App\Http\Requests;

use App\Traits\NotifiesOnValidationFail;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    use NotifiesOnValidationFail;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'coupon' => 'nullable|exists:App\Models\Store\Coupon,code',
            'gift' => 'nullable|min:1',
            'custom_price' => 'nullable|numeric',
            'tos' => 'accepted'
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'tos.accepted' => 'You need to accept the TOS before purchasing.'
        ];
    }
}
