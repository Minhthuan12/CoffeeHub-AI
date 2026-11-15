<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CongThucMon extends Model
{
    use SoftDeletes;

    protected $table = 'cong_thuc_mons';

    protected $fillable = [
        'so_luong',
        'don_vi',
        'id_san_pham',
        'id_nguyen_lieu',
    ];
}
