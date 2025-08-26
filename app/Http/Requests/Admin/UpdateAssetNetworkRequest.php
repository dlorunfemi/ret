<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/** @mixin \Illuminate\Http\Request */
class UpdateAssetNetworkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) (Auth::user()?->role === 'admin');
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'exists:assets,id'],
            'network_name' => ['required', 'string', 'max:255'],
            'deposit_address' => ['nullable', 'string', 'max:255'],
            'qr_file' => ['nullable', 'image', 'max:5120'],
            'min_deposit' => ['nullable', 'numeric', 'min:0'],
            'deposit_confirmations' => ['nullable', 'integer', 'min:0'],
            'withdraw_confirmations' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
