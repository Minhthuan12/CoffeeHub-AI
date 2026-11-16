<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiNhaCungCapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_nha_cung_cap' => 'required|string|max:255',
            'email' => 'nullable|email|string|max:255',
            'nguoi_lien_he' => 'required|string|regex:/^[a-zA-Z\sàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ]+$/|max:255',
            'so_dien_thoai' => 'nullable|string|regex:/^(0)[0-9]{9}$/',
            'dia_chi' => 'nullable|string|max:255',
            'tinh_trang' => 'nullable|integer',
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
            'ten_nha_cung_cap' => 'Tên nhà cung cấp',
            'email' => 'Email',
            'nguoi_lien_he' => 'Người liên hệ',
            'so_dien_thoai' => 'Số điện thoại',
            'dia_chi' => 'Địa chỉ',
            'tinh_trang' => 'Tình trạng',
        ];
    }
}
