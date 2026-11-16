<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ChiTietPhanQuyen;

class checkLoginMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $id_chuc_nang = null): Response
    {
        $user = Auth::guard("sanctum")->user();
        if ($user) {
            $id_quyen = $user->id_chuc_vu;
            $check_quyen = ChiTietPhanQuyen::where('id_quyen', $id_quyen)
                                           ->where("id_chuc_nang", $id_chuc_nang)
                                           ->first();

            if (!$check_quyen && $user->is_master != 1) {
                return response()->json([
                    'message' => 'Bạn không có quyền thực hiện chức năng này',
                    'status' => 0,
                ]);
            }
            return $next($request);
        } else {
            return response()->json([
                "status" => 0,
                "messages" => "Bạn chưa đăng nhập"
            ], 200);
        }
    }
}
