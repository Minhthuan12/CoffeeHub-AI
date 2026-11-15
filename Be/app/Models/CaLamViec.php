<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaLamViec extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_TAM_TAT = 0;
    public const TRANG_THAI_HOAT_DONG = 1;

    protected $table = 'ca_lam_viecs';

    protected $fillable = [
        'ten_ca',
        'gio_bat_dau',
        'gio_ket_thuc',
        'tinh_trang',
    ];
}
