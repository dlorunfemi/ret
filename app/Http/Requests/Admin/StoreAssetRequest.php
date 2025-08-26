<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/** @mixin \Illuminate\Http\Request */
class StoreAssetRequest extends FormRequest
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
            'symbol' => ['required', 'string', 'max:20', 'unique:assets,symbol'],
            'name' => ['required', 'string', 'max:100'],
            'precision' => ['required', 'integer', 'min:0', 'max:18'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
