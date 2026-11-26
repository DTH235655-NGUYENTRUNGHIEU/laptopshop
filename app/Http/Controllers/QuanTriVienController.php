<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\HoaDon;
use App\Models\LienHe;
use App\Models\SanPham;
use App\Models\User;

class QuanTriVienController extends Controller
{
    public function getHome()
    {
        $tongDoanhThu = HoaDon::where('trangthai', 'da_thanh_toan')->sum('tongthanhtoan');
        $tongDonHang = DonHang::count();
        $donHangChoDuyet = DonHang::where('tinhtrang_id', 1)->count();
        $tongSanPham = SanPham::count();
        $tongKhachHang = User::where('role', 'user')->count();
        $lienHeChuaXuLy = LienHe::where('trangthai', 'chua_xuly')->count();

        // Doanh thu 12 tháng trong năm hiện tại (từ Hóa đơn đã thanh toán)
        $namHienTai = (int) date('Y');
        $duLieuThang = HoaDon::selectRaw('MONTH(ngaylaphoadon) as thang, SUM(tongthanhtoan) as doanhthu')
            ->whereYear('ngaylaphoadon', $namHienTai)
            ->where('trangthai', 'da_thanh_toan')
            ->groupByRaw('MONTH(ngaylaphoadon)')
            ->pluck('doanhthu', 'thang');

        $doanhThuTheoThang = [];
        for ($i = 1; $i <= 12; $i++) {
            $doanhThuTheoThang[] = (int) ($duLieuThang[$i] ?? 0);
        }

        $donHangMoiNhat = DonHang::with(['User', 'TinhTrang'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $sanPhamSapHet = SanPham::with('HangSanXuat')
            ->where('soluong', '<=', 10)
            ->orderBy('soluong')
            ->take(5)
            ->get();

        return view('admin.home', compact(
            'tongDoanhThu',
            'tongDonHang',
            'donHangChoDuyet',
            'tongSanPham',
            'tongKhachHang',
            'lienHeChuaXuLy',
            'namHienTai',
            'doanhThuTheoThang',
            'donHangMoiNhat',
            'sanPhamSapHet'
        ));
    }
}