<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/** @mixin \Illuminate\Http\Request */
class StoreUserBalanceRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'asset_id' => ['required', 'exists:assets,id'],
            'available' => ['required', 'numeric', 'min:0'],
            'frozen' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
