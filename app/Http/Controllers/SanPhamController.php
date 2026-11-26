<?php

namespace App\Http\Controllers;

use App\Exports\SanPhamExport;
use App\Imports\SanPhamImport;
use App\Models\HangSanXuat;
use App\Models\LoaiSanPham;
use App\Models\SanPham;
use App\Models\SanPhamHinhAnh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SanPhamController extends Controller
{
    public function getDanhSach(Request $request)
    {
        $query = SanPham::with(['LoaiSanPham', 'HangSanXuat'])
            ->withCount('SanPhamHinhAnh');

        if ($request->filled('tukhoa')) {
            $tukhoa = trim($request->tukhoa);
            $query->where(function ($q) use ($tukhoa) {
                $q->where('tensanpham', 'like', '%' . $tukhoa . '%')
                    ->orWhere('masanpham', 'like', '%' . $tukhoa . '%')
                    ->orWhere('cpu', 'like', '%' . $tukhoa . '%');
            });
        }

        if ($request->filled('loaisanpham_id')) {
            $query->where('loaisanpham_id', $request->loaisanpham_id);
        }

        if ($request->filled('hangsanxuat_id')) {
            $query->where('hangsanxuat_id', $request->hangsanxuat_id);
        }

        $sanpham = $query->orderByDesc('id')->paginate(10)->withQueryString();
        $loaisanpham = LoaiSanPham::orderBy('tenloai')->get();
        $hangsanxuat = HangSanXuat::orderBy('tenhang')->get();

        return view('admin.sanpham.danhsach', compact('sanpham', 'loaisanpham', 'hangsanxuat'));
    }

    public function getThem()
    {
        $loaisanpham = LoaiSanPham::orderBy('tenloai')->get();
        $hangsanxuat = HangSanXuat::orderBy('tenhang')->get();

        return view('admin.sanpham.them', compact('loaisanpham', 'hangsanxuat'));
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'loaisanpham_id' => ['required', 'integer', 'exists:loaisanpham,id'],
            'hangsanxuat_id' => ['required', 'integer', 'exists:hangsanxuat,id'],
            'masanpham' => ['required', 'string', 'max:50', 'unique:sanpham,masanpham'],
            'tensanpham' => ['required', 'string', 'max:255', 'unique:sanpham,tensanpham'],
            'soluong' => ['required', 'integer', 'min:0'],
            'dongia' => ['required', 'integer', 'min:0'],
            'giakhuyenmai' => ['nullable', 'integer', 'min:0', 'lt:dongia'],
            'hinhanh' => ['nullable', 'image', 'max:4096'],
            'hinhanh_phu.*' => ['nullable', 'image', 'max:4096'],
            'cpu' => ['required', 'string', 'max:150'],
            'ram' => ['required', 'string', 'max:100'],
            'ocung' => ['required', 'string', 'max:150'],
            'carddohoa' => ['required', 'string', 'max:150'],
            'manhinh' => ['required', 'string', 'max:150'],
            'hedieuhanh' => ['nullable', 'string', 'max:100'],
            'pin' => ['nullable', 'string', 'max:100'],
            'trongluong' => ['nullable', 'numeric', 'min:0', 'max:99.99'],
            'mausac' => ['nullable', 'string', 'max:50'],
            'baohanh' => ['required', 'integer', 'min:1', 'max:60'],
            'motasanpham' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($request->tensanpham, '-');

        $path = null;
        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');
            $filename = $slug . '-' . time() . '.' . $file->extension();
            $path = $file->storeAs('sanpham', $filename, 'public');
        }

        $orm = new SanPham();
        $orm->loaisanpham_id = $request->loaisanpham_id;
        $orm->hangsanxuat_id = $request->hangsanxuat_id;
        $orm->masanpham = strtoupper(trim($request->masanpham));
        $orm->tensanpham = trim($request->tensanpham);
        $orm->tensanpham_slug = $slug;
        $orm->soluong = $request->soluong;
        $orm->dongia = $request->dongia;
        $orm->giakhuyenmai = $request->filled('giakhuyenmai') ? $request->giakhuyenmai : null;
        $orm->hinhanh = $path;
        $orm->cpu = $request->cpu;
        $orm->ram = $request->ram;
        $orm->ocung = $request->ocung;
        $orm->carddohoa = $request->carddohoa;
        $orm->manhinh = $request->manhinh;
        $orm->hedieuhanh = $request->hedieuhanh;
        $orm->pin = $request->pin;
        $orm->trongluong = $request->filled('trongluong') ? $request->trongluong : null;
        $orm->mausac = $request->mausac;
        $orm->baohanh = $request->baohanh;
        $orm->motasanpham = $request->motasanpham;
        $orm->noibat = $request->boolean('noibat');
        $orm->trangthai = $request->boolean('trangthai');
        $orm->save();

        // Lưu album ảnh phụ (nếu có)
        if ($request->hasFile('hinhanh_phu')) {
            $thutu = 1;
            foreach ($request->file('hinhanh_phu') as $filePhu) {
                $tenAnhPhu = $slug . '-gallery-' . $thutu . '-' . time() . '.' . $filePhu->extension();
                $duongDanPhu = $filePhu->storeAs('sanpham/gallery', $tenAnhPhu, 'public');

                SanPhamHinhAnh::create([
                    'sanpham_id' => $orm->id,
                    'hinhanh' => $duongDanPhu,
                    'thutu' => $thutu++,
                ]);
            }
        }

        return redirect()->route('admin.sanpham')
            ->with('success', 'Đã thêm laptop "' . $orm->tensanpham . '" thành công.');
    }

    public function getSua($id)
    {
        $sanpham = SanPham::with(['SanPhamHinhAnh' => function ($q) {
            $q->orderBy('thutu');
        }])->findOrFail($id);

        $loaisanpham = LoaiSanPham::orderBy('tenloai')->get();
        $hangsanxuat = HangSanXuat::orderBy('tenhang')->get();

        return view('admin.sanpham.sua', compact('sanpham', 'loaisanpham', 'hangsanxuat'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'loaisanpham_id' => ['required', 'integer', 'exists:loaisanpham,id'],
            'hangsanxuat_id' => ['required', 'integer', 'exists:hangsanxuat,id'],
            'masanpham' => ['required', 'string', 'max:50', 'unique:sanpham,masanpham,' . $id],
            'tensanpham' => ['required', 'string', 'max:255', 'unique:sanpham,tensanpham,' . $id],
            'soluong' => ['required', 'integer', 'min:0'],
            'dongia' => ['required', 'integer', 'min:0'],
            'giakhuyenmai' => ['nullable', 'integer', 'min:0', 'lt:dongia'],
            'hinhanh' => ['nullable', 'image', 'max:4096'],
            'hinhanh_phu.*' => ['nullable', 'image', 'max:4096'],
            'cpu' => ['required', 'string', 'max:150'],
            'ram' => ['required', 'string', 'max:100'],
            'ocung' => ['required', 'string', 'max:150'],
            'carddohoa' => ['required', 'string', 'max:150'],
            'manhinh' => ['required', 'string', 'max:150'],
            'hedieuhanh' => ['nullable', 'string', 'max:100'],
            'pin' => ['nullable', 'string', 'max:100'],
            'trongluong' => ['nullable', 'numeric', 'min:0', 'max:99.99'],
            'mausac' => ['nullable', 'string', 'max:50'],
            'baohanh' => ['required', 'integer', 'min:1', 'max:60'],
            'motasanpham' => ['nullable', 'string'],
        ]);

        $orm = SanPham::findOrFail($id);
        $slug = Str::slug($request->tensanpham, '-');

        if ($request->hasFile('hinhanh')) {
            if ($orm->hinhanh && Storage::disk('public')->exists($orm->hinhanh)) {
                Storage::disk('public')->delete($orm->hinhanh);
            }
            $file = $request->file('hinhanh');
            $filename = $slug . '-' . time() . '.' . $file->extension();
            $orm->hinhanh = $file->storeAs('sanpham', $filename, 'public');
        }

        $orm->loaisanpham_id = $request->loaisanpham_id;
        $orm->hangsanxuat_id = $request->hangsanxuat_id;
        $orm->masanpham = strtoupper(trim($request->masanpham));
        $orm->tensanpham = trim($request->tensanpham);
        $orm->tensanpham_slug = $slug;
        $orm->soluong = $request->soluong;
        $orm->dongia = $request->dongia;
        $orm->giakhuyenmai = $request->filled('giakhuyenmai') ? $request->giakhuyenmai : null;
        $orm->cpu = $request->cpu;
        $orm->ram = $request->ram;
        $orm->ocung = $request->ocung;
        $orm->carddohoa = $request->carddohoa;
        $orm->manhinh = $request->manhinh;
        $orm->hedieuhanh = $request->hedieuhanh;
        $orm->pin = $request->pin;
        $orm->trongluong = $request->filled('trongluong') ? $request->trongluong : null;
        $orm->mausac = $request->mausac;
        $orm->baohanh = $request->baohanh;
        $orm->motasanpham = $request->motasanpham;
        $orm->noibat = $request->boolean('noibat');
        $orm->trangthai = $request->boolean('trangthai');
        $orm->save();

        // Thêm ảnh phụ mới (giữ nguyên các ảnh phụ cũ)
        if ($request->hasFile('hinhanh_phu')) {
            $thutu = (int) $orm->SanPhamHinhAnh()->max('thutu') + 1;
            foreach ($request->file('hinhanh_phu') as $filePhu) {
                $tenAnhPhu = $slug . '-gallery-' . $thutu . '-' . time() . '.' . $filePhu->extension();
                $duongDanPhu = $filePhu->storeAs('sanpham/gallery', $tenAnhPhu, 'public');

                SanPhamHinhAnh::create([
                    'sanpham_id' => $orm->id,
                    'hinhanh' => $duongDanPhu,
                    'thutu' => $thutu++,
                ]);
            }
        }

        return redirect()->route('admin.sanpham')
            ->with('success', 'Đã cập nhật laptop "' . $orm->tensanpham . '" thành công.');
    }

    public function getXoaAnhPhu($id)
    {
        $anhPhu = SanPhamHinhAnh::findOrFail($id);
        $sanphamId = $anhPhu->sanpham_id;

        if ($anhPhu->hinhanh && Storage::disk('public')->exists($anhPhu->hinhanh)) {
            Storage::disk('public')->delete($anhPhu->hinhanh);
        }

        $anhPhu->delete();

        return redirect()->route('admin.sanpham.sua', ['id' => $sanphamId])
            ->with('success', 'Đã xóa ảnh phụ khỏi album sản phẩm.');
    }

    public function getXoa($id)
    {
        $orm = SanPham::with('SanPhamHinhAnh')->findOrFail($id);

        $daPhatSinhGiaoDich = $orm->DonHang_ChiTiet()->exists()
            || $orm->PhieuNhap_ChiTiet()->exists()
            || $orm->HoaDon_ChiTiet()->exists();

        if ($daPhatSinhGiaoDich) {
            return redirect()->route('admin.sanpham')
                ->with('error', 'Không thể xóa "' . $orm->tensanpham . '" vì đã có dữ liệu trong đơn hàng, phiếu nhập hoặc hóa đơn. Bạn hãy chuyển sang trạng thái Ngừng kinh doanh.');
        }

        if ($orm->hinhanh && Storage::disk('public')->exists($orm->hinhanh)) {
            Storage::disk('public')->delete($orm->hinhanh);
        }

        foreach ($orm->SanPhamHinhAnh as $anhPhu) {
            if ($anhPhu->hinhanh && Storage::disk('public')->exists($anhPhu->hinhanh)) {
                Storage::disk('public')->delete($anhPhu->hinhanh);
            }
        }

        $orm->delete();

        return redirect()->route('admin.sanpham')
            ->with('success', 'Đã xóa sản phẩm "' . $orm->tensanpham . '".');
    }

    public function postNhap(Request $request)
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:4096'],
        ]);

        Excel::import(new SanPhamImport(), $request->file('file_excel'));

        return redirect()->route('admin.sanpham')
            ->with('success', 'Đã nhập danh sách Sản phẩm từ Excel thành công.');
    }

    public function getXuat()
    {
        return Excel::download(new SanPhamExport(), 'danh-sach-laptop.xlsx');
    }
}