<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'canonical' => 'required|unique:permissions',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Chưa nhập Quyền',
            'canonical.required' => 'Chưa nhập từ khóa Quyền.',
            'canonical.unique' => 'Từ khóa đã tồn tại.',
        ];
    }
}
