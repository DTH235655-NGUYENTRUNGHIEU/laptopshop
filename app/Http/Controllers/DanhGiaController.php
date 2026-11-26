<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use Illuminate\Http\Request;

class DanhGiaController extends Controller
{
    public function getDanhSach(Request $request)
    {
        $query = DanhGia::with(['SanPham', 'User']);

        if ($request->filled('sosao')) {
            $query->where('sosao', (int) $request->sosao);
        }

        if ($request->filled('kiemduyet')) {
            $query->where('kiemduyet', (int) $request->kiemduyet);
        }

        $danhgia = $query->orderByDesc('created_at')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.danhgia.danhsach', compact('danhgia'));
    }

    public function getKiemDuyet($id)
    {
        $orm = DanhGia::findOrFail($id);
        $orm->kiemduyet = !$orm->kiemduyet;
        $orm->save();

        return redirect()->route('admin.danhgia')
            ->with('success', 'Đã thay đổi trạng thái duyệt đánh giá sản phẩm.');
    }

    public function getXoa($id)
    {
        $orm = DanhGia::findOrFail($id);
        $orm->delete();

        return redirect()->route('admin.danhgia')
            ->with('success', 'Đã xóa đánh giá sản phẩm.');
    }
}