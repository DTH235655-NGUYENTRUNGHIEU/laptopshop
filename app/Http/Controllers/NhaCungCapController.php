<?php

namespace App\Http\Controllers;

use App\Models\NhaCungCap;
use Illuminate\Http\Request;

class NhaCungCapController extends Controller
{
    public function getDanhSach()
    {
        $nhacungcap = NhaCungCap::withCount('PhieuNhap')->orderBy('id')->get();
        return view('admin.nhacungcap.danhsach', compact('nhacungcap'));
    }

    public function getThem()
    {
        return view('admin.nhacungcap.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'ten_nhacungcap' => ['required', 'string', 'max:255'],
            'sodienthoai' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'diachi' => ['required', 'string', 'max:255'],
            'masothue' => ['nullable', 'string', 'max:30'],
            'ghichu' => ['nullable', 'string'],
        ]);

        $orm = new NhaCungCap();
        $orm->ten_nhacungcap = $request->ten_nhacungcap;
        $orm->sodienthoai = $request->sodienthoai;
        $orm->email = $request->email;
        $orm->diachi = $request->diachi;
        $orm->masothue = $request->masothue;
        $orm->ghichu = $request->ghichu;
        $orm->save();

        return redirect()->route('admin.nhacungcap')
            ->with('success', 'Đã thêm nhà cung cấp "' . $orm->ten_nhacungcap . '" thành công.');
    }

    public function getSua($id)
    {
        $nhacungcap = NhaCungCap::findOrFail($id);
        return view('admin.nhacungcap.sua', compact('nhacungcap'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'ten_nhacungcap' => ['required', 'string', 'max:255'],
            'sodienthoai' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'diachi' => ['required', 'string', 'max:255'],
            'masothue' => ['nullable', 'string', 'max:30'],
            'ghichu' => ['nullable', 'string'],
        ]);

        $orm = NhaCungCap::findOrFail($id);
        $orm->ten_nhacungcap = $request->ten_nhacungcap;
        $orm->sodienthoai = $request->sodienthoai;
        $orm->email = $request->email;
        $orm->diachi = $request->diachi;
        $orm->masothue = $request->masothue;
        $orm->ghichu = $request->ghichu;
        $orm->save();

        return redirect()->route('admin.nhacungcap')
            ->with('success', 'Đã cập nhật nhà cung cấp "' . $orm->ten_nhacungcap . '" thành công.');
    }

    public function getXoa($id)
    {
        $orm = NhaCungCap::findOrFail($id);

        if ($orm->PhieuNhap()->count() > 0) {
            return redirect()->route('admin.nhacungcap')
                ->with('error', 'Không thể xóa vì nhà cung cấp "' . $orm->ten_nhacungcap . '" đã có phiếu nhập kho.');
        }

        $orm->delete();

        return redirect()->route('admin.nhacungcap')
            ->with('success', 'Đã xóa nhà cung cấp "' . $orm->ten_nhacungcap . '".');
    }
}