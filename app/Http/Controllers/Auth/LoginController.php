<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
        $this->middleware('auth:web')->only('logout');
    }

    protected function guard()
    {
        return Auth::guard('web');
    }

    public function logout(Request $request)
    {
        // Chỉ đăng xuất guard web, KHÔNG gọi session()->invalidate() để giữ nguyên phiên Admin ở tab khác
        Auth::guard('web')->logout();
        $request->session()->forget('voucher');

        return redirect()->route('frontend.home')
            ->with('success', 'Đã đăng xuất tài khoản khách hàng.');
    }
}