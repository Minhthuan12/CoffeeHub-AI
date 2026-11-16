<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHoaDonRequest extends FormRequest
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
            'id' => 'required|integer|exists:hoa_dons,id',
            'ma_hoa_don' => 'required|string|max:255',
            'tong_tien' => 'required|integer|min:0',
            'tien_truoc_giam' => 'nullable|integer|min:0',
            'so_tien_giam' => 'nullable|integer|min:0',
            'phuong_thuc_thanh_toan' => 'nullable|integer|in:1,2',
            'tinh_trang' => 'required|integer',
            'id_ban' => 'required|integer|exists:bans,id',
            'id_khach_hang' => 'required|integer|exists:khach_hangs,id',
            'id_phan_cong_ca_lam' => 'required|integer|exists:phan_cong_ca_lams,id',
            'id_voucher' => 'nullable|integer|exists:vouchers,id',
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
            'ma_hoa_don' => 'Mã hóa đơn',
            'tong_tien' => 'Tổng tiền',
            'tien_truoc_giam' => 'Tiền trước giảm',
            'so_tien_giam' => 'Số tiền giảm',
            'phuong_thuc_thanh_toan' => 'Phương thức thanh toán',
            'tinh_trang' => 'Tình trạng',
            'id_ban' => 'Mã bàn',
            'id_khach_hang' => 'Mã khách hàng',
            'id_phan_cong_ca_lam' => 'Mã phân công ca làm',
            'id_voucher' => 'Mã voucher',
        ];
    }
}
