<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiNguyenLieuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_nguyen_lieu' => 'required|string|max:255',
            'don_vi_tinh' => 'required|string|max:255',
            'so_luong_ton' => 'required|integer|min:0',
            'muc_canh_bao' => 'required|integer|min:0',
            'gia_nhap' => 'required|integer|min:0',
            'tinh_trang' => 'required|integer',
            'id_nha_cung_cap' => 'required|integer|exists:nha_cung_caps,id',
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
            'ten_nguyen_lieu' => 'Tên nguyên liệu',
            'don_vi_tinh' => 'Đơn vị tính',
            'so_luong_ton' => 'Số lượng tồn',
            'muc_canh_bao' => 'Mức cảnh báo',
            'gia_nhap' => 'Giá nhập',
            'tinh_trang' => 'Tình trạng',
            'id_nha_cung_cap' => 'Mã nhà cung cấp',
        ];
    }
}
