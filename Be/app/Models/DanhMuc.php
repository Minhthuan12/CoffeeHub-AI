<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhMuc extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_TAM_TAT = 0;
    public const TRANG_THAI_HOAT_DONG = 1;

    protected $table = 'danh_mucs';

    protected $fillable = [
        'ten_danh_muc',
        'slug_danh_muc',
        'icon_danh_muc',
        'tinh_trang',
        'id_danh_muc_cha',
    ];
}
