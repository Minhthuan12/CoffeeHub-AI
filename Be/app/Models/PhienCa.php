<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhienCa extends Model
{
    use SoftDeletes;

    public const TRANG_THAI_DANG_MO = 1;
    public const TRANG_THAI_DA_CHOT = 2;

    protected $table = 'phien_cas';

    protected $fillable = [
        'ma_phien_ca',
        'id_nhan_vien',
        'id_ca_lam_viec',
        'thoi_gian_mo_ca',
        'thoi_gian_dong_ca',
        'tien_dau_ca',
        'tong_thu_tien_mat',
        'tong_thu_chuyen_khoan',
        'tong_doanh_thu',
        'tien_mat_he_thong',
        'tien_mat_thuc_te',
        'tien_chenh_lech',
        'so_luong_don_hang',
        'so_luong_don_huy',
        'ghi_chu',
        'trang_thai',
    ];

    protected $casts = [
        'thoi_gian_mo_ca'       => 'datetime',
        'thoi_gian_dong_ca'     => 'datetime',
        'tien_dau_ca'           => 'float',
        'tong_thu_tien_mat'     => 'float',
        'tong_thu_chuyen_khoan' => 'float',
        'tong_doanh_thu'        => 'float',
        'tien_mat_he_thong'     => 'float',
        'tien_mat_thuc_te'      => 'float',
        'tien_chenh_lech'       => 'float',
        'so_luong_don_hang'     => 'integer',
        'so_luong_don_huy'      => 'integer',
        'trang_thai'            => 'integer',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'id_nhan_vien');
    }

    public function caLamViec()
    {
        return $this->belongsTo(CaLamViec::class, 'id_ca_lam_viec');
    }

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'id_phien_ca');
    }
}
