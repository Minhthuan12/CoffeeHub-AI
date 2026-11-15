<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhachHang extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_TAM_TAT = 0;
    public const TRANG_THAI_HOAT_DONG = 1;

    protected $table = 'khach_hangs';

    protected $fillable = [
        'email',
        'ho_va_ten',
        'so_dien_thoai',
        'tinh_trang',
    ];
}
