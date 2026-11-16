<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemMoiPhanCongCaLamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ghi_chu' => 'nullable|string|max:255',
            'tinh_trang' => 'required|integer',
            'id_nhan_vien' => 'required|integer|exists:nhan_viens,id',
            'id_ca_lam_viec' => 'required|integer|exists:ca_lam_viecs,id',
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
            'ghi_chu' => 'Ghi chú',
            'tinh_trang' => 'Tình trạng',
            'id_nhan_vien' => 'Mã nhân viên',
            'id_ca_lam_viec' => 'Mã ca làm việc',
        ];
    }
}
