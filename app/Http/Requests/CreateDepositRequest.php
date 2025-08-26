<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateDepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'integer', 'exists:assets,id'],
            'asset_network_id' => ['nullable', 'integer', 'exists:asset_networks,id'],
            'amount' => ['required', 'numeric', 'min:0.00000001'],
            'address' => ['nullable', 'string', 'max:255'],
            'tx_hash' => ['nullable', 'string', 'max:255'],
        ];
    }
}
