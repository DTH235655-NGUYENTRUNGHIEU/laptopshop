<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getDanhSach()
    {
        $nguoidung = User::orderByRaw("FIELD(role, 'admin', 'staff', 'user')")
            ->orderBy('id')
            ->get();

        return view('admin.nguoidung.danhsach', compact('nguoidung'));
    }

    public function getThem()
    {
        return view('admin.nguoidung.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'username' => ['nullable', 'string', 'max:100', 'unique:users,username'],
            'sodienthoai' => ['nullable', 'string', 'max:20'],
            'diachi' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:admin,staff,user'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $orm = new User();
        $orm->name = $request->name;
        $orm->email = $request->email;
        $orm->username = $request->filled('username')
            ? $request->username
            : Str::before($request->email, '@');
        $orm->sodienthoai = $request->sodienthoai;
        $orm->diachi = $request->diachi;
        $orm->role = $request->role;
        $orm->password = Hash::make($request->password);
        $orm->save();

        return redirect()->route('admin.nguoidung')
            ->with('success', 'Đã thêm tài khoản "' . $orm->email . '" thành công.');
    }

    public function getSua($id)
    {
        $nguoidung = User::findOrFail($id);
        return view('admin.nguoidung.sua', compact('nguoidung'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'username' => ['nullable', 'string', 'max:100', 'unique:users,username,' . $id],
            'sodienthoai' => ['nullable', 'string', 'max:20'],
            'diachi' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:admin,staff,user'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $orm = User::findOrFail($id);

        if ($orm->id === Auth::id() && $request->role !== 'admin') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể tự hạ quyền của tài khoản Quản trị đang đăng nhập.');
        }

        $orm->name = $request->name;
        $orm->email = $request->email;
        $orm->username = $request->filled('username')
            ? $request->username
            : Str::before($request->email, '@');
        $orm->sodienthoai = $request->sodienthoai;
        $orm->diachi = $request->diachi;
        $orm->role = $request->role;

        if ($request->filled('password')) {
            $orm->password = Hash::make($request->password);
        }

        $orm->save();

        return redirect()->route('admin.nguoidung')
            ->with('success', 'Đã cập nhật tài khoản "' . $orm->email . '" thành công.');
    }

    public function getXoa($id)
    {
        $orm = User::findOrFail($id);

        if ($orm->id === Auth::id()) {
            return redirect()->route('admin.nguoidung')
                ->with('error', 'Không thể xóa tài khoản đang đăng nhập.');
        }

        $coDuLieuLienQuan = $orm->DonHang()->exists()
            || $orm->PhieuNhap()->exists()
            || $orm->HoaDon()->exists()
            || $orm->HoaDonLap()->exists()
            || $orm->BaiViet()->exists();

        if ($coDuLieuLienQuan) {
            return redirect()->route('admin.nguoidung')
                ->with('error', 'Không thể xóa vì tài khoản "' . $orm->email . '" đã có dữ liệu đơn hàng, hóa đơn, phiếu nhập hoặc bài viết.');
        }

        $orm->delete();

        return redirect()->route('admin.nguoidung')
            ->with('success', 'Đã xóa tài khoản "' . $orm->email . '".');
    }
}