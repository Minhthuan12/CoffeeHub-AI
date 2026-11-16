<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DatMatKhauMoiRequest extends FormRequest
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
        $rules = [
            'hash_reset'  => 'required',
        ];

        if ($this->has('password')) {
            $rules['password'] = 'required|min:5|max:100';
            $rules['re_password'] = 'required|same:password';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'hash_reset.required'  => 'Vui lòng cung cấp mã bí mật.',
            'password.required'    => 'Vui lòng nhập mật khẩu mới.',
            'password.min'         => 'Mật khẩu phải chứa ít nhất 5 ký tự.',
            'password.max'         => 'Mật khẩu không được vượt quá 100 ký tự.',
            're_password.required' => 'Vui lòng xác nhận mật khẩu mới.',
            're_password.same'     => 'Mật khẩu xác nhận không trùng khớp.',
        ];
    }
}
