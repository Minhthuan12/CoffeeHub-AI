<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiDanhMucRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_danh_muc' => 'required|string|max:255',
            'slug_danh_muc' => 'required|string|max:255',
            'icon_danh_muc' => 'required|string|max:255',
            'tinh_trang' => 'required|integer',
            'id_danh_muc_cha' => 'nullable|integer|exists:danh_mucs,id',
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
            'ten_danh_muc' => 'Tên danh mục',
            'slug_danh_muc' => 'Slug danh mục',
            'icon_danh_muc' => 'Icon danh mục',
            'tinh_trang' => 'Tình trạng',
            'id_danh_muc_cha' => 'Danh mục cha',
        ];
    }
}
