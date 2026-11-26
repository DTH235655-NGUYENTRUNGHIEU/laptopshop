<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\TinhTrang;
use Illuminate\Http\Request;

class DonHangController extends Controller
{
    public function getDanhSach(Request $request)
    {
        $query = DonHang::with([
            'User',
            'TinhTrang',
            'MaGiamGia',
            'DonHang_ChiTiet.SanPham',
            'HoaDon',
        ]);

        if ($request->filled('tukhoa')) {
            $tukhoa = trim($request->tukhoa);
            $query->where(function ($q) use ($tukhoa) {
                $q->where('madonhang', 'like', '%' . $tukhoa . '%')
                    ->orWhere('hoten_nguoinhan', 'like', '%' . $tukhoa . '%')
                    ->orWhere('dienthoaigiaohang', 'like', '%' . $tukhoa . '%');
            });
        }

        if ($request->filled('tinhtrang_id')) {
            $query->where('tinhtrang_id', $request->tinhtrang_id);
        }

        $donhang = $query->orderByDesc('created_at')->orderByDesc('id')->paginate(10)->withQueryString();
        $tinhtrang = TinhTrang::orderBy('id')->get();

        return view('admin.donhang.danhsach', compact('donhang', 'tinhtrang'));
    }

    public function getSua($id)
    {
        $donhang = DonHang::with([
            'User',
            'TinhTrang',
            'MaGiamGia',
            'DonHang_ChiTiet.SanPham',
            'HoaDon',
        ])->findOrFail($id);

        $tinhtrang = TinhTrang::orderBy('id')->get();

        return view('admin.donhang.sua', compact('donhang', 'tinhtrang'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tinhtrang_id' => ['required', 'integer', 'exists:tinhtrang,id'],
            'hoten_nguoinhan' => ['required', 'string', 'max:150'],
            'dienthoaigiaohang' => ['required', 'string', 'max:20'],
            'diachigiaohang' => ['required', 'string', 'max:255'],
            'phuongthuc_thanhtoan' => ['required', 'in:COD,CHUYENKHOAN,VNPAY,MOMO'],
            'ghichu' => ['nullable', 'string'],
        ]);

        $orm = DonHang::with('HoaDon')->findOrFail($id);

        if ((int) $request->tinhtrang_id === 6 && $orm->HoaDon && $orm->HoaDon->trangthai !== 'da_huy') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đơn hàng này đã xuất hóa đơn ' . $orm->HoaDon->sohoadon . '. Vui lòng hủy hóa đơn trước khi chuyển đơn sang Đã hủy.');
        }

        $orm->tinhtrang_id = $request->tinhtrang_id;
        $orm->hoten_nguoinhan = $request->hoten_nguoinhan;
        $orm->dienthoaigiaohang = $request->dienthoaigiaohang;
        $orm->diachigiaohang = $request->diachigiaohang;
        $orm->phuongthuc_thanhtoan = $request->phuongthuc_thanhtoan;
        $orm->trangthai_thanhtoan = $request->boolean('trangthai_thanhtoan');
        $orm->ghichu = $request->ghichu;
        $orm->save();

        return redirect()->route('admin.donhang')
            ->with('success', 'Đã cập nhật đơn hàng "' . $orm->madonhang . '" thành công.');
    }

    public function getXoa($id)
    {
        $orm = DonHang::with('HoaDon')->findOrFail($id);

        if ($orm->HoaDon) {
            return redirect()->route('admin.donhang')
                ->with('error', 'Không thể xóa đơn "' . $orm->madonhang . '" vì đã liên kết với hóa đơn ' . $orm->HoaDon->sohoadon . '.');
        }

        $orm->delete();

        return redirect()->route('admin.donhang')
            ->with('success', 'Đã xóa đơn hàng "' . $orm->madonhang . '".');
    }
}