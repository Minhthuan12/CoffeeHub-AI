<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiNhanVienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|string|max:255|unique:nhan_viens,email',
            'ho_va_ten' => 'required|string|regex:/^[a-zA-Z\sàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ]+$/|max:255',
            'password' => 'required|string|min:6|max:255',
            'so_dien_thoai' => 'required|string|regex:/^(0)[0-9]{9}$/',
            'tinh_trang' => 'required|integer',
            'id_chuc_vu' => 'required|integer|exists:chuc_vus,id',
        ];
    }

        public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'integer' => ':attribute phải là số nguyên.',
            'numeric' => ':attribute phải là số.',
            'email' => ':attribute không đúng định dạng email.',
            'date' => ':attribute phải là ngày hợp lệ.',
            'string' => ':attribute phải là chuỗi.',
            'exists' => ':attribute không tồn tại.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'unique' => ':attribute đã tồn tại.',
            'regex' => ':attribute không đúng định dạng.',
            'min' => ':attribute phải tối thiểu :min.',
            'after_or_equal' => ':attribute phải sau hoặc bằng ngày bắt đầu.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'ho_va_ten' => 'Họ và tên',
            'password' => 'Mật khẩu',
            'so_dien_thoai' => 'Số điện thoại',
            'tinh_trang' => 'Tình trạng',
            'id_chuc_vu' => 'Mã chức vụ',
        ];
    }
}
