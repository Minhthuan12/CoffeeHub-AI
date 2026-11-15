<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhieuNhapKho extends Model
{
    use SoftDeletes;

    protected $table = 'phieu_nhap_khos';

    protected $fillable = [
        'tong_tien',
        'ghi_chu',
        'id_nhan_vien',
        'tinh_trang',
    ];
}
