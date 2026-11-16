<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiChiTietHoaDonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_chi_tiet_cha' => 'nullable|integer|exists:chi_tiet_hoa_dons,id',
            'so_luong' => 'required|integer|min:1',
            'don_gia' => 'required|integer|min:0',
            'ghi_chu' => 'nullable|string',
            'id_hoa_don' => 'required|integer|exists:hoa_dons,id',
            'id_san_pham' => 'required|integer|exists:san_phams,id',
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
            'id_chi_tiet_cha' => 'id chi tiet cha',
            'so_luong' => 'Số lượng',
            'don_gia' => 'Đơn giá',
            'ghi_chu' => 'Ghi chú',
            'id_hoa_don' => 'Mã hóa đơn',
            'id_san_pham' => 'Mã sản phẩm',
        ];
    }
}
