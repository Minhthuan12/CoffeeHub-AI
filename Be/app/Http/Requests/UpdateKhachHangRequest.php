<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKhachHangRequest extends FormRequest
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
            'id' => 'required|integer|exists:khach_hangs,id',
            'email' => 'required|email|string|max:255|unique:khach_hangs,email,' .$this->id,
            'ho_va_ten' => 'required|string|regex:/^[a-zA-Z\sàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ]+$/|max:255',
            'so_dien_thoai' => 'required|string|regex:/^(0)[0-9]{9}$/',
            'tinh_trang' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            "id.required" => "ID không được để trống.",
            "id.integer" => "ID phải là số nguyên.",
            "id.exists" => "ID không tồn tại.",
            "email.unique" => "Email đã tồn tại.",
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
            'email' => 'Email',
            'ho_va_ten' => 'Họ và tên',
            'so_dien_thoai' => 'Số điện thoại',
            'tinh_trang' => 'Tình trạng',
        ];
    }
}
