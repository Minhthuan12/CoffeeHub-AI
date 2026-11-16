<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
        $userId = Auth::guard('sanctum')->id();

        return [
            'ho_va_ten'     => 'required|string|max:255',
            'email'         => 'required|email|string|max:255|unique:nhan_viens,email,' . $userId,
            'so_dien_thoai' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_va_ten.required' => 'Họ và tên không được để trống.',
            'email.required'     => 'Email không được để trống.',
            'email.email'        => 'Email không đúng định dạng.',
            'email.unique'       => 'Email này đã tồn tại ở tài khoản khác.',
            'string'             => ':attribute phải là chuỗi.',
            'max'                => ':attribute không được vượt quá :max ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'ho_va_ten'     => 'Họ và tên',
            'email'         => 'Email',
            'so_dien_thoai' => 'Số điện thoại',
        ];
    }
}
