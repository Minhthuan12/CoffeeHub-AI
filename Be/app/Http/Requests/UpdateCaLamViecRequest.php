<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCaLamViecRequest extends FormRequest
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
            'id' => 'required|integer|exists:ca_lam_viecs,id',
            'ten_ca' => 'required|string|max:255',
            'gio_bat_dau' => 'required|string',
            'gio_ket_thuc' => 'required|string',
            'tinh_trang' => 'required|integer',
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
        ];
    }

    public function attributes(): array
    {
        return [
            'ten_ca' => 'Tên ca',
            'gio_bat_dau' => 'Giờ bắt đầu',
            'gio_ket_thuc' => 'Giờ kết thúc',
            'tinh_trang' => 'Tình trạng',
        ];
    }
}
