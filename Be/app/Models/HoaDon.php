<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HoaDon extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_CHUA_THANH_TOAN = 0;
    public const TRANG_THAI_DA_THANH_TOAN = 1;

    protected $table = 'hoa_dons';

    protected $fillable = [
        'ma_hoa_don',
        'tong_tien',
        'tien_truoc_giam',
        'so_tien_giam',
        'phuong_thuc_thanh_toan',
        'tinh_trang',
        'id_ban',
        'id_khach_hang',
        'id_phan_cong_ca_lam',
        'id_voucher',

    ];
}
