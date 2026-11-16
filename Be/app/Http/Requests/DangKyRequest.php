<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DangKyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_va_ten'     => 'required|string|max:255',
            'email'         => 'required|email|string|max:255|unique:nhan_viens,email',
            'so_dien_thoai' => 'required|string|regex:/^(0)[0-9]{9}$/',
            'password'      => 'required|string|min:6|max:255',
            're_password'   => 'required|string|same:password',
            'id_chuc_vu'    => 'nullable|integer|exists:chuc_vus,id',
        ];
    }

    public function messages(): array
    {
        return [
            'required'      => ':attribute không được để trống.',
            'integer'       => ':attribute phải là số nguyên.',
            'email'         => ':attribute không đúng định dạng email.',
            'string'        => ':attribute phải là chuỗi.',
            'exists'        => ':attribute không tồn tại trên hệ thống.',
            'max'           => ':attribute không được vượt quá :max ký tự.',
            'min'           => ':attribute phải có tối thiểu :min ký tự.',
            'unique'        => ':attribute đã được sử dụng.',
            'regex'         => ':attribute không đúng định dạng số điện thoại Việt Nam (10 số, bắt đầu bằng 0).',
            'same'          => ':attribute không trùng khớp với Mật khẩu.',
        ];
    }

    public function attributes(): array
    {
        return [
            'ho_va_ten'     => 'Họ và tên',
            'email'         => 'Email',
            'so_dien_thoai' => 'Số điện thoại',
            'password'      => 'Mật khẩu',
            're_password'   => 'Xác nhận mật khẩu',
            'id_chuc_vu'    => 'Chức vụ',
        ];
    }
}
