<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $user = Auth::guard('admin')->user();

            if (!in_array($user->role, ['admin', 'staff'], true)) {
                Auth::guard('admin')->logout();

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'Tài khoản này không có quyền truy cập trang quản trị.',
                    ]);
            }

            // Chỉ đổi session ID (giữ nguyên phiên khách hàng ở guard web nếu đang mở ở tab khác)
            $request->session()->regenerate();

            return redirect()->intended(route('admin.home'));
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Email hoặc mật khẩu quản trị không chính xác.',
            ]);
    }

    public function logout(Request $request)
    {
        // Chỉ đăng xuất guard admin, KHÔNG invalidate() để không làm văng tab Khách hàng
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login')
            ->with('success', 'Đã đăng xuất khỏi trang quản trị.');
    }
}