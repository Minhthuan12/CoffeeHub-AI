<?php

namespace App\Http\Controllers;

use App\Http\Requests\DangKyRequest;
use App\Http\Requests\NhanVienDangNhapRequest;
use App\Http\Requests\ThemMoiNhanVienRequest;
use App\Http\Requests\UpdateNhanVienRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\DoiMatKhauTrangCaNhanRequest;
use App\Http\Requests\DatMatKhauMoiRequest;
use App\Models\NhanVien;
use App\Models\ChucVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Jobs\sendMailJob;

class NhanVienController extends Controller
{
    public function getData()
    {
        $data = NhanVien::query()
            ->join('chuc_vus', 'nhan_viens.id_chuc_vu', '=', 'chuc_vus.id')
            ->select(
                'nhan_viens.*',
                'chuc_vus.ten_chuc_vu'
            )
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getDataActive()
    {
        $data = NhanVien::where('nhan_viens.tinh_trang', 1)
            ->join('chuc_vus', 'nhan_viens.id_chuc_vu', '=', 'chuc_vus.id')
            ->select('nhan_viens.*', 'chuc_vus.ten_chuc_vu')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function store(ThemMoiNhanVienRequest $request)
    {
        $id_chuc_nang = 1;
        NhanVien::create([
            'email' => $request->email,
            'ho_va_ten' => $request->ho_va_ten,
            'password' => Hash::make($request->password),
            'so_dien_thoai' => $request->so_dien_thoai,
            'tinh_trang' => $request->tinh_trang,
            'id_chuc_vu' => $request->id_chuc_vu,
        ]);

        return response()->json([
            'message' => 'Đã tạo mới nhân viên thành công!',
            'status' => 1,
        ]);
    }

    public function destroy(Request $request)
    {
        $login = Auth::guard('sanctum')->user();

        $item = NhanVien::find($request->id);
        if ($item) {
            if ($item->is_master == 1) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Không thể xóa tài khoản Quản trị viên tối cao (is_master)!',
                ]);
            }
            if ($item->id == $login->id) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Không thể tự xóa tài khoản của chính mình đang đăng nhập!',
                ]);
            }

            $item->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Xóa thành công!',
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Không tìm thấy dữ liệu!',
        ]);
    }

    public function update(UpdateNhanVienRequest $request)
    {
        $employee = NhanVien::find($request->id);
        if (!$employee) {
            return response()->json([
                'status' => 0,
                'message' => 'Nhân viên không tồn tại!',
            ]);
        }

        if ($employee->is_master == 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Không thể cập nhật thông tin của tài khoản Quản trị viên tối cao (is_master)!',
            ]);
        }

        $employee->update([
            'email' => $request->email,
            'ho_va_ten' => $request->ho_va_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'tinh_trang' => $request->tinh_trang,
            'id_chuc_vu' => $request->id_chuc_vu,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Đã cập nhật nhân viên thành công!',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $nhanVien = NhanVien::where('id', $request->id)->first();
        if ($nhanVien) {
            if ($nhanVien->tinh_trang == 1) {
                $nhanVien->tinh_trang = 0;
            } else {
                $nhanVien->tinh_trang = 1;
            }
            $nhanVien->save();

            return response()->json([
                'status' => 1,
                'message' => 'Đã thay đổi trạng thái nhân viên thành công!',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Nhân viên không tồn tại!',
            ]);
        }
    }

    public function dangKy(DangKyRequest $request)
    {
        $id_chuc_vu = $request->id_chuc_vu;
        if (!$id_chuc_vu) {
            $chucVu = ChucVu::where('slug_chuc_vu', 'phuc-vu')->where('tinh_trang', 1)->first()
                ?? ChucVu::where('tinh_trang', 1)->first();
            $id_chuc_vu = $chucVu ? $chucVu->id : 1;
        }

        $nhanVien = NhanVien::create([
            'email'         => $request->email,
            'ho_va_ten'     => $request->ho_va_ten,
            'password'      => Hash::make($request->password),
            'so_dien_thoai' => $request->so_dien_thoai,
            'tinh_trang'    => 1,
            'id_chuc_vu'    => $id_chuc_vu,
            'is_master'     => 0,
        ]);

        return response()->json([
            'status'  => 1,
            'message' => 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.',
            'data'    => [
                'id'        => $nhanVien->id,
                'email'     => $nhanVien->email,
                'ho_va_ten' => $nhanVien->ho_va_ten,
            ]
        ], 201);
    }

    public function login(NhanVienDangNhapRequest $request)
    {
        $data = $request->validated();
        $check = Auth::guard('nv')->attempt($data);
        if ($check) {
            $token = Auth::guard('nv')->user()->createToken('token')->plainTextToken;
            return response()->json([
                'status' => 1,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'status' => 0,
            ]);
        }
    }
    public function checkLogin()
    {
        $user = Auth::guard("sanctum")->user();
        if ($user) {
            $info = NhanVien::join('chuc_vus', 'nhan_viens.id_chuc_vu', '=', 'chuc_vus.id')
                ->where('nhan_viens.id', $user->id)
                ->select('nhan_viens.ho_va_ten', 'chuc_vus.ten_chuc_vu')
                ->first();

            return response()->json([
                "status" => 1,
                "user" => $info
            ], 200);
        } else {
            return response()->json([
                "status" => 0
            ], 200);
        }
    }
    public function quenMatKhau(Request $request)
    {
        $tk = NhanVien::where('email', $request->email)->first();

        if ($tk) {
            $data['ho_va_ten'] = $tk->ho_va_ten;
            $data['ma_bi_mat'] = Str::uuid();
            $data['link'] = 'https://ilovecoffee.click/admin/doi-mat-khau';
            $tk->hash_reset = $data['ma_bi_mat'];
            $tk->save();
            sendMailJob::dispatch($tk->email, 'Lấy lại tài khoản trên hệ thống DZ', 'view_mail_quen_mat_khau', $data);

            return response()->json([
                'status' => 1,
                'message' => 'Vui lòng kiểm tra email để lấy lại tài khoản!',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Email không tồn tại trên hệ thống!'
            ]);
        }
    }

    public function datMatKhauMoi(DatMatKhauMoiRequest $request)
    {
        $tk = NhanVien::where('hash_reset', $request->hash_reset)->first();

        if ($tk) {
            $tk->password = bcrypt($request->password);
            $tk->hash_reset = null;
            $tk->save();

            return response()->json([
                'status' => 1,
                'message' => 'Đặt lại mật khẩu mới thành công!'
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Mã bí mật không chính xác hoặc đã hết hạn!'
        ]);
    }

    public function getProfile()
    {
        $user = Auth::guard("sanctum")->user();
        if ($user) {
            $profile = NhanVien::join('chuc_vus', 'nhan_viens.id_chuc_vu', '=', 'chuc_vus.id')
                ->where('nhan_viens.id', $user->id)
                ->select(
                    'nhan_viens.id',
                    'nhan_viens.ho_va_ten',
                    'nhan_viens.email',
                    'nhan_viens.so_dien_thoai',
                    'nhan_viens.tinh_trang',
                    'nhan_viens.id_chuc_vu',
                    'nhan_viens.is_master',
                    'chuc_vus.ten_chuc_vu'
                )
                ->first();

            return response()->json([
                'status' => 1,
                'data' => $profile
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Bạn chưa đăng nhập!'
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::guard("sanctum")->user();
        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'Bạn chưa đăng nhập!'
            ]);
        }

        $employee = NhanVien::find($user->id);
        if (!$employee) {
            return response()->json([
                'status' => 0,
                'message' => 'Không tìm thấy thông tin nhân viên!'
            ]);
        }

        $employee->ho_va_ten = $request->ho_va_ten;
        $employee->email = $request->email;
        $employee->so_dien_thoai = $request->so_dien_thoai;
        $employee->save();

        return response()->json([
            'status' => 1,
            'message' => 'Cập nhật thông tin cá nhân thành công!'
        ]);
    }

    public function changePassword(DoiMatKhauTrangCaNhanRequest $request)
    {
        $user = Auth::guard("sanctum")->user();
        if ($user) {
            return response()->json([
                'status' => 1,
                'mat_khau_cu' => $request->mat_khau_cu,
                'mat_khau_moi' => $request->mat_khau_moi,
                'message' => 'Đổi mật khẩu thành công'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Bạn chưa đăng nhập!'
        ]);
    }
}
