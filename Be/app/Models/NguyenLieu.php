<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NguyenLieu extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_TAM_TAT = 0;
    public const TRANG_THAI_HOAT_DONG = 1;

    protected $table = 'nguyen_lieus';

    protected $fillable = [
        'ten_nguyen_lieu',
        'don_vi_tinh',
        'so_luong_ton',
        'muc_canh_bao',
        'gia_nhap',
        'tinh_trang',
        'id_nha_cung_cap',
    ];
}
