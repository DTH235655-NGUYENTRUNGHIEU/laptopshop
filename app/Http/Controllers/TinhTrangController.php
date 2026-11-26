<?php

namespace App\Http\Controllers;

use App\Models\TinhTrang;
use Illuminate\Http\Request;

class TinhTrangController extends Controller
{
    public function getDanhSach()
    {
        $tinhtrang = TinhTrang::withCount('DonHang')->orderBy('id')->get();
        return view('admin.tinhtrang.danhsach', compact('tinhtrang'));
    }

    public function getThem()
    {
        return view('admin.tinhtrang.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'tinhtrang' => ['required', 'string', 'max:255', 'unique:tinhtrang,tinhtrang'],
        ]);

        $orm = new TinhTrang();
        $orm->tinhtrang = $request->tinhtrang;
        $orm->save();

        return redirect()->route('admin.tinhtrang')
            ->with('success', 'Đã thêm tình trạng "' . $orm->tinhtrang . '" thành công.');
    }

    public function getSua($id)
    {
        $tinhtrang = TinhTrang::findOrFail($id);
        return view('admin.tinhtrang.sua', compact('tinhtrang'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tinhtrang' => ['required', 'string', 'max:255', 'unique:tinhtrang,tinhtrang,' . $id],
        ]);

        $orm = TinhTrang::findOrFail($id);
        $orm->tinhtrang = $request->tinhtrang;
        $orm->save();

        return redirect()->route('admin.tinhtrang')
            ->with('success', 'Đã cập nhật tình trạng "' . $orm->tinhtrang . '" thành công.');
    }

    public function getXoa($id)
    {
        $orm = TinhTrang::findOrFail($id);

        if ($orm->DonHang()->count() > 0) {
            return redirect()->route('admin.tinhtrang')
                ->with('error', 'Không thể xóa vì tình trạng "' . $orm->tinhtrang . '" đang gắn với đơn hàng.');
        }

        $orm->delete();

        return redirect()->route('admin.tinhtrang')
            ->with('success', 'Đã xóa tình trạng "' . $orm->tinhtrang . '".');
    }
}