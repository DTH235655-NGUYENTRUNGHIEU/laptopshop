<?php

namespace App\Http\Controllers;

use App\Models\NhaCungCap;
use App\Models\PhieuNhap;
use App\Models\PhieuNhap_ChiTiet;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PhieuNhapController extends Controller
{
    public function getDanhSach()
    {
        $phieunhap = PhieuNhap::with(['NhaCungCap', 'NhanVien'])
            ->withCount('PhieuNhap_ChiTiet')
            ->orderByDesc('ngaynhap')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.phieunhap.danhsach', compact('phieunhap'));
    }

    public function getThem()
    {
        $nhacungcap = NhaCungCap::orderBy('ten_nhacungcap')->get();
        $sanpham = SanPham::with('HangSanXuat')->orderBy('tensanpham')->get();

        $demHomNay = PhieuNhap::whereDate('created_at', today())->count() + 1;
        $maPhieuGoiY = 'PN' . date('Ymd') . '-' . str_pad((string) $demHomNay, 3, '0', STR_PAD_LEFT);

        return view('admin.phieunhap.them', compact('nhacungcap', 'sanpham', 'maPhieuGoiY'));
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'maphieunhap' => ['required', 'string', 'max:30', 'unique:phieunhap,maphieunhap'],
            'nhacungcap_id' => ['required', 'integer', 'exists:nhacungcap,id'],
            'ngaynhap' => ['required', 'date'],
            'ghichu' => ['nullable', 'string'],
            'sanpham_id' => ['required', 'array', 'min:1'],
            'sanpham_id.*' => ['required', 'integer', 'exists:sanpham,id'],
            'soluong_nhap' => ['required', 'array', 'min:1'],
            'soluong_nhap.*' => ['required', 'integer', 'min:1'],
            'gianhap' => ['required', 'array', 'min:1'],
            'gianhap.*' => ['required', 'integer', 'min:1000'],
        ], [
            'sanpham_id.required' => 'Vui lòng chọn ít nhất một mẫu laptop cần nhập kho.',
            'soluong_nhap.*.min' => 'Số lượng nhập mỗi dòng tối thiểu phải là 1.',
            'gianhap.*.min' => 'Giá nhập mỗi dòng phải từ 1.000đ trở lên.',
        ]);

        $phieuNhap = DB::transaction(function () use ($request) {
            $pn = new PhieuNhap();
            $pn->maphieunhap = strtoupper(trim($request->maphieunhap));
            $pn->nhacungcap_id = $request->nhacungcap_id;
            $pn->nhanvien_id = Auth::id();
            $pn->ngaynhap = $request->ngaynhap;
            $pn->tongtien = 0;
            $pn->ghichu = $request->ghichu;
            $pn->save();

            $tongTien = 0;
            $dsSanPham = $request->input('sanpham_id', []);
            $dsSoLuong = $request->input('soluong_nhap', []);
            $dsGiaNhap = $request->input('gianhap', []);

            foreach ($dsSanPham as $i => $spId) {
                $soLuong = (int) $dsSoLuong[$i];
                $giaNhap = (int) $dsGiaNhap[$i];
                $thanhTien = $soLuong * $giaNhap;
                $tongTien += $thanhTien;

                PhieuNhap_ChiTiet::create([
                    'phieunhap_id' => $pn->id,
                    'sanpham_id' => $spId,
                    'soluong_nhap' => $soLuong,
                    'gianhap' => $giaNhap,
                    'thanhtien' => $thanhTien,
                ]);

                // Tự động cộng tồn kho sản phẩm
                SanPham::where('id', $spId)->increment('soluong', $soLuong);
            }

            $pn->tongtien = $tongTien;
            $pn->save();

            return $pn;
        });

        return redirect()->route('admin.phieunhap.chitiet', ['id' => $phieuNhap->id])
            ->with('success', 'Đã lập phiếu nhập "' . $phieuNhap->maphieunhap . '" và tự động cộng tồn kho thành công.');
    }

    public function getChiTiet($id)
    {
        $phieunhap = PhieuNhap::with([
            'NhaCungCap',
            'NhanVien',
            'PhieuNhap_ChiTiet.SanPham.HangSanXuat',
        ])->findOrFail($id);

        return view('admin.phieunhap.chitiet', compact('phieunhap'));
    }

    public function getXoa($id)
    {
        $phieunhap = PhieuNhap::with('PhieuNhap_ChiTiet')->findOrFail($id);

        // Gộp tổng số lượng theo từng sản phẩm trong phiếu để kiểm tra tồn kho trước khi hoàn tác
        $canTru = [];
        foreach ($phieunhap->PhieuNhap_ChiTiet as $ct) {
            $canTru[$ct->sanpham_id] = ($canTru[$ct->sanpham_id] ?? 0) + $ct->soluong_nhap;
        }

        try {
            DB::transaction(function () use ($phieunhap, $canTru) {
                foreach ($canTru as $spId => $slTru) {
                    $sp = SanPham::where('id', $spId)->lockForUpdate()->first();
                    if (!$sp || $sp->soluong < $slTru) {
                        $tenSp = $sp ? $sp->tensanpham : ('ID #' . $spId);
                        throw new \RuntimeException('Không thể xóa phiếu vì sản phẩm "' . $tenSp . '" hiện chỉ còn ' . ($sp->soluong ?? 0) . ' máy (đã bán bớt từ lô nhập này).');
                    }
                    $sp->decrement('soluong', $slTru);
                }

                $phieunhap->delete();
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.phieunhap')
                ->with('error', $e->getMessage());
        }

        return redirect()->route('admin.phieunhap')
            ->with('success', 'Đã xóa phiếu nhập "' . $phieunhap->maphieunhap . '" và hoàn trừ lại số lượng tồn kho.');
    }
}