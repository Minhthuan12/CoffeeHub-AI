<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNhaCungCapRequest extends FormRequest
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
            'id' => 'required|integer|exists:nha_cung_caps,id',
            'ten_nha_cung_cap' => 'required|string|max:255',
            'email' => 'nullable|email|string|max:255',
            'nguoi_lien_he' => 'required|string|regex:/^[a-zA-Z\sàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ]+$/|max:255',
            'so_dien_thoai' => 'nullable|string|regex:/^(0)[0-9]{9}$/',
            'dia_chi' => 'nullable|string|max:255',
            'tinh_trang' => 'nullable|integer',
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
            'ten_nha_cung_cap' => 'Tên nhà cung cấp',
            'email' => 'Email',
            'nguoi_lien_he' => 'Người liên hệ',
            'so_dien_thoai' => 'Số điện thoại',
            'dia_chi' => 'Địa chỉ',
            'tinh_trang' => 'Tình trạng',
        ];
    }
}
