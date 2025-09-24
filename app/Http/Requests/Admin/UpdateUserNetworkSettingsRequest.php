<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserNetworkSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()?->isAdmin() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'network_settings' => ['required', 'array'],
            'network_settings.*.asset_network_id' => ['required', 'integer', 'exists:asset_networks,id'],
            'network_settings.*.min_deposit' => ['nullable', 'numeric', 'min:0'],
            'network_settings.*.deposit_confirmations' => ['nullable', 'integer', 'min:0'],
            'network_settings.*.withdraw_confirmations' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
