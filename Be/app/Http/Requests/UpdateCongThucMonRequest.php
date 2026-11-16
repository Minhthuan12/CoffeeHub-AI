<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCongThucMonRequest extends FormRequest
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
            'id' => 'required|integer|exists:cong_thuc_mons,id',
            'so_luong' => 'required|integer|min:1',
            'don_vi' => 'required|string|max:255',
            'id_san_pham' => 'required|integer|exists:san_phams,id',
            'id_nguyen_lieu' => 'required|integer|exists:nguyen_lieus,id',
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
            'so_luong' => 'Số lượng',
            'don_vi' => 'Đơn vị',
            'id_san_pham' => 'Mã sản phẩm',
            'id_nguyen_lieu' => 'Mã nguyên liệu',
        ];
    }
}
