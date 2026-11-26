<?php

namespace App\Http\Controllers;

use App\Models\BinhLuanBaiViet;
use Illuminate\Http\Request;

class BinhLuanBaiVietController extends Controller
{
    public function getDanhSach()
    {
        $binhluanbaiviet = BinhLuanBaiViet::with(['BaiViet', 'User'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.binhluanbaiviet.danhsach', compact('binhluanbaiviet'));
    }

    public function getSua($id)
    {
        $binhluanbaiviet = BinhLuanBaiViet::with(['BaiViet', 'User'])->findOrFail($id);
        return view('admin.binhluanbaiviet.sua', compact('binhluanbaiviet'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'noidungbinhluan' => ['required', 'string', 'min:5'],
        ]);

        $orm = BinhLuanBaiViet::findOrFail($id);
        $orm->noidungbinhluan = trim($request->noidungbinhluan);
        $orm->kiemduyet = $request->boolean('kiemduyet');
        $orm->kichhoat = $request->boolean('kichhoat');
        $orm->save();

        return redirect()->route('admin.binhluanbaiviet')
            ->with('success', 'Đã cập nhật nội dung bình luận thành công.');
    }

    public function getKiemDuyet($id)
    {
        $orm = BinhLuanBaiViet::findOrFail($id);
        $orm->kiemduyet = !$orm->kiemduyet;
        $orm->save();

        return redirect()->route('admin.binhluanbaiviet')
            ->with('success', 'Đã thay đổi trạng thái kiểm duyệt bình luận.');
    }

    public function getKichHoat($id)
    {
        $orm = BinhLuanBaiViet::findOrFail($id);
        $orm->kichhoat = !$orm->kichhoat;
        $orm->save();

        return redirect()->route('admin.binhluanbaiviet')
            ->with('success', 'Đã thay đổi trạng thái hiển thị bình luận.');
    }

    public function getXoa($id)
    {
        $orm = BinhLuanBaiViet::findOrFail($id);
        $orm->delete();

        return redirect()->route('admin.binhluanbaiviet')
            ->with('success', 'Đã xóa bình luận.');
    }
}