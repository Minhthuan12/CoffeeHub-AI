<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhaCungCap extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_TAM_TAT = 0;
    public const TRANG_THAI_HOAT_DONG = 1;

    protected $table = 'nha_cung_caps';

    protected $fillable = [
        'ten_nha_cung_cap',
        'email',
        'nguoi_lien_he',
        'so_dien_thoai',
        'dia_chi',
        'tinh_trang',
    ];
}
