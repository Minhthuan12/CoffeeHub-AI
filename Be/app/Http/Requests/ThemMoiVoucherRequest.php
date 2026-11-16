<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ma_voucher' => 'required|string|max:255',
            'thoi_gian_bat_dau' => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after_or_equal:thoi_gian_bat_dau',
            'loai_giam' => 'required|integer',
            'so_giam_gia' => 'required|numeric|min:0',
            'so_tien_toi_da' => 'nullable|integer|min:0',
            'don_hang_toi_thieu' => 'nullable|integer|min:0',
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
            'ma_voucher' => 'Mã voucher',
            'thoi_gian_bat_dau' => 'Thời gian bắt đầu',
            'thoi_gian_ket_thuc' => 'Thời gian kết thúc',
            'loai_giam' => 'Loại giảm',
            'so_giam_gia' => 'Số giảm giá',
            'so_tien_toi_da' => 'Số tiền tối đa',
            'don_hang_toi_thieu' => 'Đơn hàng tối thiểu',
        ];
    }
}
