<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiSanPhamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_san_pham' => 'required|string|max:255',
            'slug_san_pham' => 'required|string|max:255',
            'mo_ta' => 'required|string',
            'kich_thuoc' => 'required|string|max:255',
            'gia_ban' => 'required|integer|min:0',
            'hinh_anh' => 'required|string|max:255',
            'tinh_trang' => 'required|integer',
            'id_danh_muc' => 'required|integer|exists:danh_mucs,id',
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
            'ten_san_pham' => 'Tên sản phẩm',
            'slug_san_pham' => 'Slug sản phẩm',
            'mo_ta' => 'Mô tả',
            'kich_thuoc' => 'Kích thước',
            'gia_ban' => 'Giá bán',
            'hinh_anh' => 'Hình ảnh',
            'tinh_trang' => 'Tình trạng',
            'id_danh_muc' => 'id danh muc',
        ];
    }
}
