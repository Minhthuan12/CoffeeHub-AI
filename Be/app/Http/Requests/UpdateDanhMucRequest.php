<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDanhMucRequest extends FormRequest
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
            'id' => 'required|integer|exists:danh_mucs,id',
            'ten_danh_muc' => 'required|string|max:255',
            'slug_danh_muc' => 'required|string|max:255',
            'icon_danh_muc' => 'required|string|max:255',
            'tinh_trang' => 'required|integer',
            'id_danh_muc_cha' => 'nullable|integer|exists:danh_mucs,id',
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
            'ten_danh_muc' => 'Tên danh mục',
            'slug_danh_muc' => 'Slug danh mục',
            'icon_danh_muc' => 'Icon danh mục',
            'tinh_trang' => 'Tình trạng',
            'id_danh_muc_cha' => 'Danh mục cha',
        ];
    }
}
