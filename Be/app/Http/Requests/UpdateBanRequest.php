<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBanRequest extends FormRequest
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
            'id' => 'required|integer|exists:bans,id',
            'ten_ban' => 'required|string|max:255',
            'tinh_trang' => 'required|integer',
            'id_khu_vuc_ban' => 'required|integer|exists:khu_vuc_bans,id',
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
            'ten_ban' => 'Tên bàn',
            'tinh_trang' => 'Tình trạng',
            'id_khu_vuc_ban' => 'Mã khu vực bàn',
        ];
    }
}
