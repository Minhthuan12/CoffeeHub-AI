<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNguyenLieuRequest extends FormRequest
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
            'id' => 'required|integer|exists:nguyen_lieus,id',
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
