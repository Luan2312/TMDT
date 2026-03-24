<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'canonical' => 'required|unique:routers,canonical, '.$this->id.',post_id',
            'post_catalogue_id' => 'gt:0',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Chưa nhập tiêu đề',
            'canonical.required' => 'Chưa nhập ô đường dẫn',
            'canonical.unique' => 'Đường dẫn đã tồn tại',
            'post_catalogue_id.gt' => 'Chưa nhập ô danh mục cha',
        ];
    }
}
