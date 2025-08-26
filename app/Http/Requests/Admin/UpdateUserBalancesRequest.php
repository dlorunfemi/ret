<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/** @mixin \Illuminate\Http\Request */
class UpdateUserBalancesRequest extends FormRequest
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
            'balances' => ['required', 'array'],
            'balances.*.asset_id' => ['required', 'integer', 'exists:assets,id'],
            'balances.*.available' => ['required', 'numeric', 'min:0'],
            'balances.*.frozen' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
