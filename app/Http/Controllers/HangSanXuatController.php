<?php

namespace App\Http\Controllers;

use App\Exports\HangSanXuatExport;
use App\Imports\HangSanXuatImport;
use App\Models\HangSanXuat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class HangSanXuatController extends Controller
{
    public function getDanhSach()
    {
        $hangsanxuat = HangSanXuat::withCount('SanPham')->orderBy('id')->get();
        return view('admin.hangsanxuat.danhsach', compact('hangsanxuat'));
    }

    public function getThem()
    {
        return view('admin.hangsanxuat.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'tenhang' => ['required', 'string', 'max:255', 'unique:hangsanxuat,tenhang'],
            'quocgia' => ['nullable', 'string', 'max:100'],
            'hinhanh' => ['nullable', 'image', 'max:2048'],
        ]);

        $path = null;
        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');
            $filename = Str::slug($request->tenhang, '-') . '.' . $file->extension();
            $path = $file->storeAs('brands', $filename, 'public');
        }

        $orm = new HangSanXuat();
        $orm->tenhang = $request->tenhang;
        $orm->tenhang_slug = Str::slug($request->tenhang, '-');
        $orm->quocgia = $request->quocgia;
        $orm->hinhanh = $path;
        $orm->save();

        return redirect()->route('admin.hangsanxuat')
            ->with('success', 'Đã thêm hãng sản xuất "' . $orm->tenhang . '" thành công.');
    }

    public function getSua($id)
    {
        $hangsanxuat = HangSanXuat::findOrFail($id);
        return view('admin.hangsanxuat.sua', compact('hangsanxuat'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tenhang' => ['required', 'string', 'max:255', 'unique:hangsanxuat,tenhang,' . $id],
            'quocgia' => ['nullable', 'string', 'max:100'],
            'hinhanh' => ['nullable', 'image', 'max:2048'],
        ]);

        $orm = HangSanXuat::findOrFail($id);

        if ($request->hasFile('hinhanh')) {
            if ($orm->hinhanh && Storage::disk('public')->exists($orm->hinhanh)) {
                Storage::disk('public')->delete($orm->hinhanh);
            }
            $file = $request->file('hinhanh');
            $filename = Str::slug($request->tenhang, '-') . '-' . time() . '.' . $file->extension();
            $orm->hinhanh = $file->storeAs('brands', $filename, 'public');
        }

        $orm->tenhang = $request->tenhang;
        $orm->tenhang_slug = Str::slug($request->tenhang, '-');
        $orm->quocgia = $request->quocgia;
        $orm->save();

        return redirect()->route('admin.hangsanxuat')
            ->with('success', 'Đã cập nhật hãng sản xuất "' . $orm->tenhang . '" thành công.');
    }

    public function getXoa($id)
    {
        $orm = HangSanXuat::findOrFail($id);

        if ($orm->SanPham()->count() > 0) {
            return redirect()->route('admin.hangsanxuat')
                ->with('error', 'Không thể xóa vì hãng "' . $orm->tenhang . '" đang có sản phẩm.');
        }

        if ($orm->hinhanh && Storage::disk('public')->exists($orm->hinhanh)) {
            Storage::disk('public')->delete($orm->hinhanh);
        }

        $orm->delete();

        return redirect()->route('admin.hangsanxuat')
            ->with('success', 'Đã xóa hãng sản xuất "' . $orm->tenhang . '".');
    }

    public function postNhap(Request $request)
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:2048'],
        ]);

        Excel::import(new HangSanXuatImport(), $request->file('file_excel'));

        return redirect()->route('admin.hangsanxuat')
            ->with('success', 'Đã nhập dữ liệu Hãng sản xuất từ Excel thành công.');
    }

    public function getXuat()
    {
        return Excel::download(new HangSanXuatExport(), 'hang-san-xuat.xlsx');
    }
}