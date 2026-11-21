<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoaDon;
use App\Models\ChiTietHoaDon;
use App\Models\DanhMuc;
use App\Models\SanPham;
use App\Models\Ban;
use App\Models\KhuVucBan;
use App\Models\NguyenLieu;
use App\Models\ChiTietPhieuNhap;
use App\Models\NhanVien;
use App\Models\CaLamViec;
use App\Models\ChucVu;
use App\Models\KhachHang;
use App\Models\PhanCongCaLam;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongKeController extends Controller
{
    public function doanhThu7NgayGanNhat(Request $request)
    {
        $soNgay = (int)$request->input('so_ngay', 7);
        if ($soNgay <= 0) $soNgay = 7;

        $query = HoaDon::join('chi_tiet_hoa_dons', 'hoa_dons.id', '=', 'chi_tiet_hoa_dons.id_hoa_don')
            ->where('hoa_dons.tinh_trang', 1)
            ->select(
                DB::raw('DATE(hoa_dons.created_at) AS ngay'),
                DB::raw('SUM(chi_tiet_hoa_dons.so_luong * chi_tiet_hoa_dons.don_gia) AS tong_doanh_thu')
            );

        if ($request->filled('tu_ngay')) {
            $query->whereDate('hoa_dons.created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('hoa_dons.created_at', '<=', $request->den_ngay);
        }

        if ($request->filled('tu_ngay') || $request->filled('den_ngay')) {
            $data = $query->groupBy(DB::raw('DATE(hoa_dons.created_at)'))
                ->orderBy('ngay', 'asc')
                ->get();
        } else {
            $data = $query->groupBy(DB::raw('DATE(hoa_dons.created_at)'))
                ->orderBy('ngay', 'desc')
                ->take($soNgay)
                ->get()
                ->sortBy('ngay')
                ->values();
        }

        return response()->json([
            'status' => 1,
            'data'   => [
                'labels'   => $data->pluck('ngay'),
                'datasets' => [
                    [
                        'label'           => 'Doanh thu 7 ngày gần nhất',
                        'data'            => $data->pluck('tong_doanh_thu'),
                        'backgroundColor' => '#0d6efd',
                        'borderRadius'    => 8,
                    ]
                ]
            ],
            'list'   => $data,
        ]);
    }

    public function doanhThuTheoDanhMucDashboard(Request $request)
    {
        $filter = $request->input('filter', 'tat_ca');
        $query = ChiTietHoaDon::join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', '=', 'hoa_dons.id')
            ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
            ->join('danh_mucs', 'san_phams.id_danh_muc', '=', 'danh_mucs.id')
            ->select('danh_mucs.ten_danh_muc', DB::raw('SUM(chi_tiet_hoa_dons.so_luong * chi_tiet_hoa_dons.don_gia) AS tong_doanh_thu'))
            ->where('hoa_dons.tinh_trang', 1);

        if ($filter == 'thang_nay') {
            $query->whereMonth('hoa_dons.created_at', Carbon::now()->month);
        } elseif ($filter == 'thang_truoc') {
            $query->whereMonth('hoa_dons.created_at', Carbon::now()->subMonth()->month);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('hoa_dons.created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('hoa_dons.created_at', '<=', $request->den_ngay);
        }

        $data = $query->groupBy('danh_mucs.id', 'danh_mucs.ten_danh_muc')
            ->orderBy('tong_doanh_thu', 'desc')
            ->get();

        return response()->json([
            'status' => 1,
            'data'   => [
                'labels'   => $data->pluck('ten_danh_muc'),
                'datasets' => [
                    [
                        'label'           => 'Doanh thu theo danh mục',
                        'data'            => $data->pluck('tong_doanh_thu'),
                        'backgroundColor' => ['#F54927', '#1FFF2E', '#1FF0FF', '#FFD700', '#9B59B6', '#FF69B4', '#3498DB', '#E67E22'],
                        'borderRadius'    => 8,
                    ]
                ]
            ],
            'list'   => $data,
        ]);
    }

    public function doanhThuTheoThang(Request $request)
    {
        $year = (int)$request->input('year', 2026);
        $data = HoaDon::select(
            DB::raw('MONTH(created_at) AS thang'),
            DB::raw('SUM(tong_tien) AS tong_doanh_thu')
        )
            ->where('hoa_dons.tinh_trang', 1)
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('thang', 'asc')
            ->get();

        if ($data->isEmpty()) {
            $latestYear = HoaDon::where('tinh_trang', 1)->max(DB::raw('YEAR(created_at)'));
            if ($latestYear) {
                $data = HoaDon::select(
                    DB::raw('MONTH(created_at) AS thang'),
                    DB::raw('SUM(tong_tien) AS tong_doanh_thu')
                )
                    ->where('hoa_dons.tinh_trang', 1)
                    ->whereYear('created_at', $latestYear)
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->orderBy('thang', 'asc')
                    ->get();
            }
        }

        $labels = $data->pluck('thang')->map(function ($thang) {
            return 'Tháng ' . $thang;
        });

        return response()->json([
            'status' => 1,
            'data'   => [
                'labels'   => $labels,
                'datasets' => [
                    [
                        'label'           => 'Doanh thu theo tháng',
                        'data'            => $data->pluck('tong_doanh_thu'),
                        'backgroundColor' => '#3498DB',
                        'borderRadius'    => 8,
                    ]
                ]
            ],
            'list'   => $data,
        ]);
    }

    public function hoaDonGanNhat(Request $request)
    {
        $data = HoaDon::join('bans', 'bans.id', '=', 'hoa_dons.id_ban')
            ->select(
                'hoa_dons.ma_hoa_don',
                'bans.ten_ban',
                'hoa_dons.tong_tien',
                'hoa_dons.tinh_trang',
                DB::raw("DATE_FORMAT(hoa_dons.created_at, '%d/%m/%Y %H:%i') as gio_tao")
            )
            ->orderBy('hoa_dons.created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'status' => 1,
            'data'   => $data,
        ]);
    }

    public function phanCongCaLamHomNay(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');
        $data = PhanCongCaLam::join('nhan_viens', 'phan_cong_ca_lams.id_nhan_vien', '=', 'nhan_viens.id')
            ->join('chuc_vus', 'nhan_viens.id_chuc_vu', '=', 'chuc_vus.id')
            ->join('ca_lam_viecs', 'phan_cong_ca_lams.id_ca_lam_viec', '=', 'ca_lam_viecs.id')
            ->select('nhan_viens.ho_va_ten', 'chuc_vus.ten_chuc_vu', 'ca_lam_viecs.ten_ca', 'phan_cong_ca_lams.tinh_trang')
            ->whereDate('phan_cong_ca_lams.created_at', $today)
            ->get();

        if ($data->isEmpty()) {
            $latestDate = PhanCongCaLam::max(DB::raw('DATE(created_at)'));
            if ($latestDate) {
                $data = PhanCongCaLam::join('nhan_viens', 'phan_cong_ca_lams.id_nhan_vien', '=', 'nhan_viens.id')
                    ->join('chuc_vus', 'nhan_viens.id_chuc_vu', '=', 'chuc_vus.id')
                    ->join('ca_lam_viecs', 'phan_cong_ca_lams.id_ca_lam_viec', '=', 'ca_lam_viecs.id')
                    ->select('nhan_viens.ho_va_ten', 'chuc_vus.ten_chuc_vu', 'ca_lam_viecs.ten_ca', 'phan_cong_ca_lams.tinh_trang')
                    ->whereDate('phan_cong_ca_lams.created_at', $latestDate)
                    ->get();
            } else {
                $data = NhanVien::join('chuc_vus', 'nhan_viens.id_chuc_vu', '=', 'chuc_vus.id')
                    ->select('nhan_viens.ho_va_ten', 'chuc_vus.ten_chuc_vu', DB::raw("'Ca Sáng' as ten_ca"), DB::raw('1 as tinh_trang'))
                    ->take(5)
                    ->get();
            }
        }

        return response()->json([
            'status' => 1,
            'data'   => $data,
        ]);
    }

    public function doanhThuTheoDanhMuc(Request $request)
    {
        $query = DanhMuc::join('san_phams', 'san_phams.id_danh_muc', '=', 'danh_mucs.id')
            ->join('chi_tiet_hoa_dons', 'san_phams.id', '=', 'chi_tiet_hoa_dons.id_san_pham')
            ->join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', '=', 'hoa_dons.id')
            ->select('danh_mucs.ten_danh_muc', DB::raw('SUM(chi_tiet_hoa_dons.so_luong * chi_tiet_hoa_dons.don_gia) as tong_doanh_thu'))
            ->where('hoa_dons.tinh_trang', 1);

        $tuNgay = $request->tu_ngay;
        $denNgay = $request->den_ngay;

        if (!$request->filled('tu_ngay') && !$request->filled('den_ngay')) {
            $latestDate = HoaDon::where('tinh_trang', 1)->max(DB::raw('DATE(created_at)'));
            if ($latestDate) {
                $tuNgay = $latestDate;
                $denNgay = $latestDate;
                $query->whereDate('hoa_dons.created_at', $latestDate);
            }
        } else {
            if ($request->filled('tu_ngay')) {
                $query->whereDate('hoa_dons.created_at', '>=', $request->tu_ngay);
            }
            if ($request->filled('den_ngay')) {
                $query->whereDate('hoa_dons.created_at', '<=', $request->den_ngay);
            }
        }

        $data = $query->groupBy('danh_mucs.id', 'danh_mucs.ten_danh_muc')->get();

        return response()->json([
            'status'   => 1,
            'tu_ngay'  => $tuNgay,
            'den_ngay' => $denNgay,
            'data'   => [
                'labels'   => $data->pluck('ten_danh_muc'),
                'datasets' => [
                    [
                        'label'           => 'Doanh thu theo danh mục',
                        'data'            => $data->pluck('tong_doanh_thu'),
                        'backgroundColor' => ['#F54927', '#1FFF2E', '#1FF0FF', '#FFD700', '#9B59B6', '#FF69B4', '#3498DB', '#E67E22'],
                        'borderRadius'    => 8,
                    ]
                ]
            ],
            'list'   => $data,
        ]);
    }

    public function doanhThuTheoKhuVuc(Request $request)
    {
        $query = KhuVucBan::join('bans', 'bans.id_khu_vuc_ban', '=', 'khu_vuc_bans.id')
            ->join('hoa_dons', 'hoa_dons.id_ban', '=', 'bans.id')
            ->select('khu_vuc_bans.ten_khu_vuc', DB::raw('SUM(hoa_dons.tong_tien) as tong_doanh_thu'))
            ->where('hoa_dons.tinh_trang', 1);

        $tuNgay = $request->tu_ngay;
        $denNgay = $request->den_ngay;

        if (!$request->filled('tu_ngay') && !$request->filled('den_ngay')) {
            $latestDate = HoaDon::where('tinh_trang', 1)->max(DB::raw('DATE(created_at)'));
            if ($latestDate) {
                $tuNgay = $latestDate;
                $denNgay = $latestDate;
                $query->whereDate('hoa_dons.created_at', $latestDate);
            }
        } else {
            if ($request->filled('tu_ngay')) {
                $query->whereDate('hoa_dons.created_at', '>=', $request->tu_ngay);
            }
            if ($request->filled('den_ngay')) {
                $query->whereDate('hoa_dons.created_at', '<=', $request->den_ngay);
            }
        }

        $data = $query->groupBy('khu_vuc_bans.ten_khu_vuc')->get();

        return response()->json([
            'status'   => 1,
            'tu_ngay'  => $tuNgay,
            'den_ngay' => $denNgay,
            'data'   => [
                'labels'   => $data->pluck('ten_khu_vuc'),
                'datasets' => [
                    [
                        'label'           => 'Doanh thu theo khu vực',
                        'data'            => $data->pluck('tong_doanh_thu'),
                        'backgroundColor' => ['#3498DB', '#9B59B6', '#1FFF2E', '#FFD700'],
                        'borderRadius'    => 8,
                    ]
                ]
            ],
            'list'   => $data,
        ]);
    }

    public function nguyenLieuDaNhap(Request $request)
    {
        $query = NguyenLieu::join('chi_tiet_phieu_nhaps', 'chi_tiet_phieu_nhaps.id_nguyen_lieu', '=', 'nguyen_lieus.id')
            ->select('nguyen_lieus.ten_nguyen_lieu', 'nguyen_lieus.don_vi_tinh', DB::raw('SUM(chi_tiet_phieu_nhaps.so_luong) as tong_so_luong_nhap'));

        $tuNgay = $request->tu_ngay;
        $denNgay = $request->den_ngay;

        if (!$request->filled('tu_ngay') && !$request->filled('den_ngay')) {
            $latestDate = ChiTietPhieuNhap::max(DB::raw('DATE(created_at)'));
            if ($latestDate) {
                $tuNgay = $latestDate;
                $denNgay = $latestDate;
                $query->whereDate('chi_tiet_phieu_nhaps.created_at', $latestDate);
            }
        } else {
            if ($request->filled('tu_ngay')) {
                $query->whereDate('chi_tiet_phieu_nhaps.created_at', '>=', $request->tu_ngay);
            }
            if ($request->filled('den_ngay')) {
                $query->whereDate('chi_tiet_phieu_nhaps.created_at', '<=', $request->den_ngay);
            }
        }

        $data = $query->groupBy('nguyen_lieus.id', 'nguyen_lieus.ten_nguyen_lieu', 'nguyen_lieus.don_vi_tinh')->get();

        return response()->json([
            'status'   => 1,
            'tu_ngay'  => $tuNgay,
            'den_ngay' => $denNgay,
            'data'   => [
                'labels'   => $data->pluck('ten_nguyen_lieu'),
                'datasets' => [
                    [
                        'label'           => 'Tổng số lượng nhập',
                        'data'            => $data->pluck('tong_so_luong_nhap'),
                        'backgroundColor' => ['#1FF0FF', '#FF69B4', '#FFD700', '#3498DB'],
                        'borderRadius'    => 8,
                    ]
                ]
            ],
            'list'   => $data,
        ]);
    }

    public function thongKeSanPhamDaBan(Request $request)
    {
        $query = SanPham::join('chi_tiet_hoa_dons', 'san_phams.id', '=', 'chi_tiet_hoa_dons.id_san_pham')
            ->join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', '=', 'hoa_dons.id')
            ->select('san_phams.ten_san_pham', DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_so_luong_ban'))
            ->where('hoa_dons.tinh_trang', 1);

        $tuNgay = $request->tu_ngay;
        $denNgay = $request->den_ngay;

        if (!$request->filled('tu_ngay') && !$request->filled('den_ngay')) {
            $latestDate = HoaDon::where('tinh_trang', 1)->max(DB::raw('DATE(created_at)'));
            if ($latestDate) {
                $tuNgay = $latestDate;
                $denNgay = $latestDate;
                $query->whereDate('hoa_dons.created_at', $latestDate);
            }
        } else {
            if ($request->filled('tu_ngay')) {
                $query->whereDate('hoa_dons.created_at', '>=', $request->tu_ngay);
            }
            if ($request->filled('den_ngay')) {
                $query->whereDate('hoa_dons.created_at', '<=', $request->den_ngay);
            }
        }

        $data = $query->groupBy('san_phams.id', 'san_phams.ten_san_pham')->get();

        return response()->json([
            'status'   => 1,
            'tu_ngay'  => $tuNgay,
            'den_ngay' => $denNgay,
            'data'   => [
                'labels'   => $data->pluck('ten_san_pham'),
                'datasets' => [
                    [
                        'label'           => 'Tổng số lượng bán',
                        'data'            => $data->pluck('tong_so_luong_ban'),
                        'backgroundColor' => ['#F54927', '#1FFF2E', '#1FF0FF', '#FFD700', '#9B59B6', '#FF69B4', '#3498DB', '#E67E22'],
                        'borderRadius'    => 8,
                    ]
                ]
            ],
            'list'   => $data,
        ]);
    }

    public function thongKeDoanhThuBanTheoNgay(Request $request)
    {
        $query = Ban::join('hoa_dons', 'hoa_dons.id_ban', '=', 'bans.id')
            ->select('bans.ten_ban', DB::raw('SUM(hoa_dons.tong_tien) as tong_doanh_thu'))
            ->where('hoa_dons.tinh_trang', 1);

        $tuNgay = $request->tu_ngay;
        $denNgay = $request->den_ngay;

        if (!$request->filled('tu_ngay') && !$request->filled('den_ngay')) {
            $latestDate = HoaDon::where('tinh_trang', 1)->max(DB::raw('DATE(created_at)'));
            if ($latestDate) {
                $tuNgay = $latestDate;
                $denNgay = $latestDate;
                $query->whereDate('hoa_dons.created_at', $latestDate);
            }
        } else {
            if ($request->filled('tu_ngay')) {
                $query->whereDate('hoa_dons.created_at', '>=', $request->tu_ngay);
            }
            if ($request->filled('den_ngay')) {
                $query->whereDate('hoa_dons.created_at', '<=', $request->den_ngay);
            }
        }

        $data = $query->groupBy('bans.id', 'bans.ten_ban')->get();

        return response()->json([
            'status'   => 1,
            'tu_ngay'  => $tuNgay,
            'den_ngay' => $denNgay,
            'data'   => [
                'labels'   => $data->pluck('ten_ban'),
                'datasets' => [
                    [
                        'label'           => 'Doanh thu theo bàn',
                        'data'            => $data->pluck('tong_doanh_thu'),
                        'backgroundColor' => ['#3498DB', '#1FFF2E', '#FFD700', '#F54927', '#9B59B6', '#FF69B4'],
                        'borderRadius'    => 8,
                    ]
                ]
            ],
            'list'   => $data,
        ]);
    }

    public function thongKeKpiDashboard(Request $request)
    {
        $ngay = $request->input('tu_ngay');
        if (!$ngay) {
            $latestDate = HoaDon::where('tinh_trang', 1)->max(DB::raw('DATE(created_at)'));
            $ngay = $latestDate ? $latestDate : Carbon::today()->format('Y-m-d');
        }

        $doanh_thu_hom_nay = HoaDon::where('tinh_trang', 1)->whereDate('created_at', $ngay)->sum('tong_tien');
        $hoa_don_hom_nay = HoaDon::where('tinh_trang', 1)->whereDate('created_at', $ngay)->count();
        $ban_dang_phuc_vu = HoaDon::where('tinh_trang', 0)->whereNotNull('id_ban')->distinct('id_ban')->count('id_ban');
        $tong_so_ban = Ban::count();

        $khach_hang_moi = KhachHang::whereDate('created_at', $ngay)->count();
        if ($khach_hang_moi == 0) {
            $khach_hang_moi = KhachHang::count();
        }

        return response()->json([
            'status'             => 1,
            'ngay'               => $ngay,
            'doanh_thu_hom_nay'  => (float)$doanh_thu_hom_nay,
            'hoa_don_hom_nay'    => (int)$hoa_don_hom_nay,
            'ban_dang_phuc_vu'   => (int)$ban_dang_phuc_vu,
            'tong_so_ban'        => (int)$tong_so_ban,
            'khach_hang_moi'     => (int)$khach_hang_moi,
        ]);
    }

    public function nguyenLieuDaXuat(Request $request)
    {
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');
        $search = $request->input('search');

        $query = ChiTietHoaDon::join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', '=', 'hoa_dons.id')
            ->join('cong_thuc_mons', 'chi_tiet_hoa_dons.id_san_pham', '=', 'cong_thuc_mons.id_san_pham')
            ->join('nguyen_lieus', 'cong_thuc_mons.id_nguyen_lieu', '=', 'nguyen_lieus.id')
            ->where('hoa_dons.tinh_trang', 1)
            ->select(
                'nguyen_lieus.id',
                'nguyen_lieus.ten_nguyen_lieu',
                'nguyen_lieus.don_vi_tinh',
                'nguyen_lieus.so_luong_ton',
                DB::raw('SUM(chi_tiet_hoa_dons.so_luong * cong_thuc_mons.so_luong) as tong_so_luong_xuat')
            );

        if ($tuNgay) {
            $query->whereDate('hoa_dons.created_at', '>=', $tuNgay);
        }
        if ($denNgay) {
            $query->whereDate('hoa_dons.created_at', '<=', $denNgay);
        }
        if ($search) {
            $query->where('nguyen_lieus.ten_nguyen_lieu', 'like', '%' . $search . '%');
        }

        $data = $query->groupBy('nguyen_lieus.id', 'nguyen_lieus.ten_nguyen_lieu', 'nguyen_lieus.don_vi_tinh', 'nguyen_lieus.so_luong_ton')
            ->get();

        foreach ($data as $item) {
            $item->tong_so_luong_xuat = round((float)$item->tong_so_luong_xuat, 2);

            $tongNhapQuery = DB::table('chi_tiet_phieu_nhaps')
                ->join('phieu_nhap_khos', 'chi_tiet_phieu_nhaps.id_phieu_nhap', '=', 'phieu_nhap_khos.id')
                ->where('phieu_nhap_khos.tinh_trang', 1)
                ->where('chi_tiet_phieu_nhaps.id_nguyen_lieu', $item->id);

            if ($tuNgay) {
                $tongNhapQuery->whereDate('phieu_nhap_khos.created_at', '>=', $tuNgay);
            }
            if ($denNgay) {
                $tongNhapQuery->whereDate('phieu_nhap_khos.created_at', '<=', $denNgay);
            }

            $sumNhap = $tongNhapQuery->sum('chi_tiet_phieu_nhaps.so_luong');
            $item->tong_so_luong_nhap = $sumNhap > 0 ? round((float)$sumNhap, 2) : round((float)$item->so_luong_ton, 2);
            $item->con_lai = round($item->tong_so_luong_nhap - $item->tong_so_luong_xuat, 2);

            $donGiaTB = DB::table('chi_tiet_phieu_nhaps')
                ->where('id_nguyen_lieu', $item->id)
                ->avg('don_gia');

            $donGiaFinal = $donGiaTB ? round($donGiaTB) : 0;
            $item->don_gia_tb = $donGiaFinal;
            $item->tong_gia_tri_xuat = round($item->tong_so_luong_xuat * $donGiaFinal);
        }

        return response()->json([
            'status' => 1,
            'data'   => $data
        ]);
    }

    public function chiTietXuatKhoNguyenLieu(Request $request)
    {
        $idNguyenLieu = $request->input('id_nguyen_lieu');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        $nguyenLieu = DB::table('nguyen_lieus')->find($idNguyenLieu);

        $query = DB::table('chi_tiet_hoa_dons')
            ->join('hoa_dons', 'chi_tiet_hoa_dons.id_hoa_don', '=', 'hoa_dons.id')
            ->join('san_phams', 'chi_tiet_hoa_dons.id_san_pham', '=', 'san_phams.id')
            ->join('cong_thuc_mons', function ($join) use ($idNguyenLieu) {
                $join->on('chi_tiet_hoa_dons.id_san_pham', '=', 'cong_thuc_mons.id_san_pham')
                    ->where('cong_thuc_mons.id_nguyen_lieu', '=', $idNguyenLieu);
            })
            ->where('hoa_dons.tinh_trang', 1)
            ->select(
                'hoa_dons.id as id_hoa_don',
                'hoa_dons.created_at as thoi_gian_ban',
                'san_phams.ten_san_pham',
                'cong_thuc_mons.so_luong as dinh_luong',
                'chi_tiet_hoa_dons.so_luong as so_luong_ban',
                DB::raw('(chi_tiet_hoa_dons.so_luong * cong_thuc_mons.so_luong) as tong_xuat')
            );

        if ($tuNgay) {
            $query->whereDate('hoa_dons.created_at', '>=', $tuNgay);
        }
        if ($denNgay) {
            $query->whereDate('hoa_dons.created_at', '<=', $denNgay);
        }

        $list = $query->orderBy('hoa_dons.created_at', 'desc')->get();

        foreach ($list as $val) {
            $val->dinh_luong = round((float)$val->dinh_luong, 3);
            $val->tong_xuat = round((float)$val->tong_xuat, 3);
        }

        return response()->json([
            'status'     => 1,
            'nguyen_lieu' => $nguyenLieu,
            'data'       => $list
        ]);
    }
}
