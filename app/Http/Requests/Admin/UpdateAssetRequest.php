<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin \Illuminate\Http\Request
 *
 * @method mixed route($name = null, $default = null)
 */
class UpdateAssetRequest extends FormRequest
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
        $route = $this->route();
        $param = method_exists($route, 'parameter') ? $route->parameter('asset') : null;
        $assetId = is_object($param) ? ($param->id ?? null) : (is_numeric($param) ? (int) $param : null);

        return [
            'symbol' => ['required', 'string', 'max:20', 'unique:assets,symbol,'.$assetId],
            'name' => ['required', 'string', 'max:100'],
            'precision' => ['required', 'integer', 'min:0', 'max:18'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
