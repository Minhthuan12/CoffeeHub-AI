<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePhieuNhapKhoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:phieu_nhap_khos,id',
            'tong_tien' => 'required|integer|min:0',
            'ghi_chu' => 'nullable|string|max:255',
            'id_nhan_vien' => 'required|integer|exists:nhan_viens,id',
        ];
    }

    public function messages(): array
    {
        return [
            "id.required" => "ID không được để trống.",
            "id.integer" => "ID phải là số nguyên.",
            "id.exists" => "ID không tồn tại.",
            'required' => ':attribute không được để trống.',
            'integer' => ':attribute phải là số nguyên.',
            'numeric' => ':attribute phải là số.',
            'email' => ':attribute không đúng định dạng email.',
            'date' => ':attribute phải là ngày hợp lệ.',
            'string' => ':attribute phải là chuỗi.',
            'exists' => ':attribute không tồn tại.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'regex' => ':attribute không đúng định dạng.',
            'min' => ':attribute phải tối thiểu :min.',
        ];
    }

    public function attributes(): array
    {
        return [
            'tong_tien' => 'Tổng tiền',
            'ghi_chu' => 'Ghi chú',
            'id_nhan_vien' => 'Mã nhân viên',
        ];
    }
}
