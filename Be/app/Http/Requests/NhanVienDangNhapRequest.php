<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NhanVienDangNhapRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password'=> 'required|min:5|max:100'
        ];

    }
    public function messages():array
    {
       return [
            // Thông báo cho trường Email
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email'    => 'Địa chỉ email không đúng định dạng (Ví dụ: abc@gmail.com).',

            // Thông báo cho trường Mật khẩu
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải chứa ít nhất 5 ký tự.',
            'password.max'      => 'Mật khẩu không được vượt quá 100 ký tự.',
        ];
    }
}
