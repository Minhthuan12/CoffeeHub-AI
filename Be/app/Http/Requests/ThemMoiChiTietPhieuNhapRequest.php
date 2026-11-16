<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiChiTietPhieuNhapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'so_luong' => 'required|integer|min:1',
            'don_gia' => 'required|integer|min:0',
            'thanh_tien' => 'required|integer|min:0',
            'id_nguyen_lieu' => 'required|integer|exists:nguyen_lieus,id',
            'id_phieu_nhap' => 'required|integer|exists:phieu_nhap_khos,id',
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
            'so_luong' => 'Số lượng',
            'don_gia' => 'Đơn giá',
            'thanh_tien' => 'Thành tiền',
            'id_nguyen_lieu' => 'Mã nguyên liệu',
            'id_phieu_nhap' => 'Mã phiếu nhập',
        ];
    }
}
