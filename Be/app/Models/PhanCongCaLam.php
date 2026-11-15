<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhanCongCaLam extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_TAM_TAT = 0;
    public const TRANG_THAI_HOAT_DONG = 1;

    protected $table = 'phan_cong_ca_lams';

    protected $fillable = [
        'ghi_chu',
        'tinh_trang',
        'id_nhan_vien',
        'id_ca_lam_viec',
        'ngay_lam_viec',
    ];
}
