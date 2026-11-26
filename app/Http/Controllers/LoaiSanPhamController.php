<?php

namespace App\Http\Controllers;

use App\Models\LoaiSanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoaiSanPhamController extends Controller
{
    public function getDanhSach()
    {
        $loaisanpham = LoaiSanPham::withCount('SanPham')->orderBy('id')->get();
        return view('admin.loaisanpham.danhsach', compact('loaisanpham'));
    }

    public function getThem()
    {
        return view('admin.loaisanpham.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'tenloai' => ['required', 'string', 'max:255', 'unique:loaisanpham,tenloai'],
            'mota' => ['nullable', 'string'],
        ]);

        $orm = new LoaiSanPham();
        $orm->tenloai = $request->tenloai;
        $orm->tenloai_slug = Str::slug($request->tenloai, '-');
        $orm->mota = $request->mota;
        $orm->save();

        return redirect()->route('admin.loaisanpham')
            ->with('success', 'Đã thêm loại sản phẩm "' . $orm->tenloai . '" thành công.');
    }

    public function getSua($id)
    {
        $loaisanpham = LoaiSanPham::findOrFail($id);
        return view('admin.loaisanpham.sua', compact('loaisanpham'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tenloai' => ['required', 'string', 'max:255', 'unique:loaisanpham,tenloai,' . $id],
            'mota' => ['nullable', 'string'],
        ]);

        $orm = LoaiSanPham::findOrFail($id);
        $orm->tenloai = $request->tenloai;
        $orm->tenloai_slug = Str::slug($request->tenloai, '-');
        $orm->mota = $request->mota;
        $orm->save();

        return redirect()->route('admin.loaisanpham')
            ->with('success', 'Đã cập nhật loại sản phẩm "' . $orm->tenloai . '" thành công.');
    }

    public function getXoa($id)
    {
        $orm = LoaiSanPham::findOrFail($id);

        if ($orm->SanPham()->count() > 0) {
            return redirect()->route('admin.loaisanpham')
                ->with('error', 'Không thể xóa vì loại "' . $orm->tenloai . '" đang có sản phẩm.');
        }

        $orm->delete();

        return redirect()->route('admin.loaisanpham')
            ->with('success', 'Đã xóa loại sản phẩm "' . $orm->tenloai . '".');
    }
}