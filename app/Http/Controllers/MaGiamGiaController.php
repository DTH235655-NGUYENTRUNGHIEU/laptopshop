<?php

namespace App\Http\Controllers;

use App\Models\MaGiamGia;
use Illuminate\Http\Request;

class MaGiamGiaController extends Controller
{
    public function getDanhSach()
    {
        $magiamgia = MaGiamGia::withCount('DonHang')->orderByDesc('id')->get();
        return view('admin.magiamgia.danhsach', compact('magiamgia'));
    }

    public function getThem()
    {
        return view('admin.magiamgia.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'macode' => ['required', 'string', 'max:50', 'unique:magiamgia,macode'],
            'loai' => ['required', 'in:fixed,percent'],
            'giatri' => ['required', 'integer', 'min:1'],
            'donhang_toithieu' => ['required', 'integer', 'min:0'],
            'soluong' => ['required', 'integer', 'min:0'],
            'ngaybatdau' => ['nullable', 'date'],
            'ngayketthuc' => ['nullable', 'date', 'after_or_equal:ngaybatdau'],
        ]);

        if ($request->loai === 'percent' && (int) $request->giatri > 100) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['giatri' => 'Giá trị giảm theo phần trăm không được vượt quá 100%.']);
        }

        $orm = new MaGiamGia();
        $orm->macode = strtoupper(trim($request->macode));
        $orm->loai = $request->loai;
        $orm->giatri = $request->giatri;
        $orm->donhang_toithieu = $request->donhang_toithieu;
        $orm->soluong = $request->soluong;
        $orm->ngaybatdau = $request->filled('ngaybatdau') ? $request->ngaybatdau : null;
        $orm->ngayketthuc = $request->filled('ngayketthuc') ? $request->ngayketthuc : null;
        $orm->kichhoat = $request->boolean('kichhoat');
        $orm->save();

        return redirect()->route('admin.magiamgia')
            ->with('success', 'Đã thêm mã giảm giá "' . $orm->macode . '" thành công.');
    }

    public function getSua($id)
    {
        $magiamgia = MaGiamGia::findOrFail($id);
        return view('admin.magiamgia.sua', compact('magiamgia'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'macode' => ['required', 'string', 'max:50', 'unique:magiamgia,macode,' . $id],
            'loai' => ['required', 'in:fixed,percent'],
            'giatri' => ['required', 'integer', 'min:1'],
            'donhang_toithieu' => ['required', 'integer', 'min:0'],
            'soluong' => ['required', 'integer', 'min:0'],
            'ngaybatdau' => ['nullable', 'date'],
            'ngayketthuc' => ['nullable', 'date', 'after_or_equal:ngaybatdau'],
        ]);

        if ($request->loai === 'percent' && (int) $request->giatri > 100) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['giatri' => 'Giá trị giảm theo phần trăm không được vượt quá 100%.']);
        }

        $orm = MaGiamGia::findOrFail($id);
        $orm->macode = strtoupper(trim($request->macode));
        $orm->loai = $request->loai;
        $orm->giatri = $request->giatri;
        $orm->donhang_toithieu = $request->donhang_toithieu;
        $orm->soluong = $request->soluong;
        $orm->ngaybatdau = $request->filled('ngaybatdau') ? $request->ngaybatdau : null;
        $orm->ngayketthuc = $request->filled('ngayketthuc') ? $request->ngayketthuc : null;
        $orm->kichhoat = $request->boolean('kichhoat');
        $orm->save();

        return redirect()->route('admin.magiamgia')
            ->with('success', 'Đã cập nhật mã giảm giá "' . $orm->macode . '" thành công.');
    }

    public function getKichHoat($id)
    {
        $orm = MaGiamGia::findOrFail($id);
        $orm->kichhoat = !$orm->kichhoat;
        $orm->save();

        return redirect()->route('admin.magiamgia')
            ->with('success', 'Đã chuyển trạng thái mã "' . $orm->macode . '".');
    }

    public function getXoa($id)
    {
        $orm = MaGiamGia::findOrFail($id);

        if ($orm->DonHang()->count() > 0) {
            return redirect()->route('admin.magiamgia')
                ->with('error', 'Không thể xóa vì mã "' . $orm->macode . '" đã được sử dụng trong đơn hàng. Bạn có thể tắt kích hoạt thay vì xóa.');
        }

        $orm->delete();

        return redirect()->route('admin.magiamgia')
            ->with('success', 'Đã xóa mã giảm giá "' . $orm->macode . '".');
    }
}