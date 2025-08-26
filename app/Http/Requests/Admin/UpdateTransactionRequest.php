<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/** @mixin \Illuminate\Http\Request */
class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) (Auth::user()?->role === 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'exists:users,id'],
            'asset_id' => ['sometimes', 'exists:assets,id'],
            'asset_network_id' => ['nullable', 'exists:asset_networks,id'],
            'type' => ['sometimes', 'in:deposit,withdrawal,adjustment'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'in:pending,approved,rejected,completed'],
            'tx_hash' => ['nullable', 'string', 'max:255'],
            'confirmed_at' => ['nullable', 'date'],
        ];
    }
}
