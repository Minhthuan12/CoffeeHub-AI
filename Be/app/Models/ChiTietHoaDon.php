<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    protected $table = 'chi_tiet_hoa_dons';

    protected $fillable = [
        'id_chi_tiet_cha',
        'so_luong',
        'don_gia',
        'ghi_chu',
        'id_hoa_don',
        'id_san_pham',
    ];
}
