<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users|max:225',
            'name' => 'required|string',
            'user_catalogue_id' => 'required|integer|gt:0',
            'password' => 'required|string|min:6',
            're_password' => 'required|string|same:password',
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'Chưa nhập email',
            'email.email' => 'Email chưa đúng định dạng. VD: 123@gmail.com',
            'email.unique' => 'Email đã tồn tại.',
            'email.string' => 'Email phải là dạng kí tự.',
            'email.max' => 'Email tối đa 225 kí tự.',
            'name.required' => 'Chưa nhập tên.',
            'name.string' => 'Tên phải là dạng ký tự.',
            'user_catalogue_id.gt' => 'Chưa chọn nhsom thành viên.',
            'password.required' => 'Chưa nhập Password.',
            're_password.required' => 'Chưa nhập ô nhập lại Password.',
            're_password.same' => 'Password không khớp.',
        ];
    }
}
