<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiChucVuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_chuc_vu' => 'required|string|max:255',
            'slug_chuc_vu' => 'required|string|max:255',
            'tinh_trang' => 'required|integer',
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
            'ten_chuc_vu' => 'Tên chức vụ',
            'slug_chuc_vu' => 'Slug chức vụ',
            'tinh_trang' => 'Tình trạng',
        ];
    }
}
