<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $table = 'vouchers';

    protected $fillable = [
        'ma_voucher',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'loai_giam',
        'so_giam_gia',
        'so_tien_toi_da',
        'don_hang_toi_thieu',
    ];
}
