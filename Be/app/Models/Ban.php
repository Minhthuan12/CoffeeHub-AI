<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ban extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_TAM_TAT = 0;
    public const TRANG_THAI_HOAT_DONG = 1;

    protected $table = 'bans';

    protected $fillable = [
        'ten_ban',
        'tinh_trang',
        'id_khu_vuc_ban',
    ];
}
