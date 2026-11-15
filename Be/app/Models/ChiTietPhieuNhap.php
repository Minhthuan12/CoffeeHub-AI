<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuNhap extends Model
{
    protected $table = 'chi_tiet_phieu_nhaps';

    protected $fillable = [
        'so_luong',
        'don_gia',
        'thanh_tien',
        'id_nguyen_lieu',
        'id_phieu_nhap',
    ];
}
