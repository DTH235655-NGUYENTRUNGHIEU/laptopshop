<?php

namespace App\Http\Controllers;

use App\Models\HoaDon_ChiTiet;
use App\Models\PhieuBaoHanh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaoHanhController extends Controller
{
    // =========================================================================
    // 1. FRONTEND: TRA CỨU BẢO HÀNH ĐIỆN TỬ THEO SERIAL / SĐT / MÃ PHIẾU
    // =========================================================================
    public function getTraCuu(Request $request)
    {
        $tukhoa = trim((string) $request->input('tukhoa', ''));
        $ketQuaMay = collect();
        $ketQuaPhieu = collect();

        if ($tukhoa !== '') {
            $ketQuaMay = HoaDon_ChiTiet::with(['HoaDon', 'SanPham'])
                ->whereHas('HoaDon', function ($q) {
                    $q->where('trangthai', '!=', 'da_huy');
                })
                ->where(function ($q) use ($tukhoa) {
                    $q->where('serial_number', 'like', '%' . $tukhoa . '%')
                        ->orWhereHas('HoaDon', function ($hd) use ($tukhoa) {
                            $hd->where('sohoadon', 'like', '%' . $tukhoa . '%')
                                ->orWhere('sodienthoai', 'like', '%' . $tukhoa . '%');
                        });
                })
                ->orderByDesc('id')
                ->get();

            $ketQuaPhieu = PhieuBaoHanh::with(['HoaDon_ChiTiet.HoaDon', 'NhanVien'])
                ->where(function ($q) use ($tukhoa) {
                    $q->where('maphieu', 'like', '%' . $tukhoa . '%')
                        ->orWhere('serial_number', 'like', '%' . $tukhoa . '%')
                        ->orWhere('sodienthoai', 'like', '%' . $tukhoa . '%');
                })
                ->orderByDesc('ngaytiepnhan')
                ->get();
        }

        $trangThaiMap = PhieuBaoHanh::danhSachTrangThai();

        return view('frontend.baohanh_tracuu', compact('tukhoa', 'ketQuaMay', 'ketQuaPhieu', 'trangThaiMap'));
    }

    // =========================================================================
    // 2. KHÁCH HÀNG: XEM THIẾT BỊ BẢO HÀNH & GỬI YÊU CẦU BẢO HÀNH ONLINE
    // =========================================================================
    public function getKhachHangBaoHanh()
    {
        $userId = Auth::id();

        $thietBiDaMua = HoaDon_ChiTiet::with('HoaDon')
            ->whereHas('HoaDon', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->where('trangthai', '!=', 'da_huy');
            })
            ->whereNotNull('serial_number')
            ->orderByDesc('id')
            ->get();

        $phieuBaoHanh = PhieuBaoHanh::with(['HoaDon_ChiTiet.HoaDon', 'NhanVien'])
            ->where('user_id', $userId)
            ->orderByDesc('ngaytiepnhan')
            ->paginate(8);

        $trangThaiMap = PhieuBaoHanh::danhSachTrangThai();

        return view('user.baohanh', compact('thietBiDaMua', 'phieuBaoHanh', 'trangThaiMap'));
    }

    public function postKhachHangYeuCau(Request $request)
    {
        $request->validate([
            'hoadon_chitiet_id' => ['required', 'integer', 'exists:hoadon_chitiet,id'],
            'mota_loi' => ['required', 'string', 'min:10', 'max:1000'],
            'phukien_kemtheo' => ['nullable', 'string', 'max:255'],
        ]);

        $hdct = HoaDon_ChiTiet::with('HoaDon')
            ->where('id', $request->hoadon_chitiet_id)
            ->whereHas('HoaDon', function ($q) {
                $q->where('user_id', Auth::id())
                    ->where('trangthai', '!=', 'da_huy');
            })
            ->firstOrFail();

        $dangXuLy = PhieuBaoHanh::where('hoadon_chitiet_id', $hdct->id)
            ->whereIn('trangthai', ['cho_tiep_nhan', 'dang_kiem_tra', 'gui_hang', 'da_sua_xong'])
            ->exists();

        if ($dangXuLy) {
            return redirect()->route('user.baohanh')
                ->with('warning', 'Máy mang số Serial "' . $hdct->serial_number . '" hiện đang có một phiếu bảo hành chưa hoàn tất.');
        }

        $maPhieu = $this->sinhMaPhieu();

        PhieuBaoHanh::create([
            'maphieu' => $maPhieu,
            'hoadon_chitiet_id' => $hdct->id,
            'sanpham_id' => $hdct->sanpham_id,
            'user_id' => Auth::id(),
            'nhanvien_id' => null,
            'serial_number' => strtoupper(trim((string) $hdct->serial_number)),
            'tensanpham' => $hdct->tensanpham_luu,
            'ten_khachhang' => $hdct->HoaDon->ten_khachhang ?? Auth::user()->name,
            'sodienthoai' => $hdct->HoaDon->sodienthoai ?? (Auth::user()->sodienthoai ?? ''),
            'ngaytiepnhan' => now(),
            'ngaytra_dukien' => now()->addDays(7)->toDateString(),
            'mota_loi' => trim($request->mota_loi),
            'phukien_kemtheo' => $request->phukien_kemtheo,
            'chiphi_phatsinh' => 0,
            'trangthai' => 'cho_tiep_nhan',
        ]);

        return redirect()->route('user.baohanh')
            ->with('success', 'Đã gửi yêu cầu bảo hành mã "' . $maPhieu . '". Bạn vui lòng mang máy kèm số Serial đến cửa hàng hoặc chờ kỹ thuật viên liên hệ.');
    }

    // =========================================================================
    // 3. QUẢN TRỊ (ADMIN / STAFF): QUẢN LÝ PHIẾU BẢO HÀNH
    // =========================================================================
    public function getDanhSach(Request $request)
    {
        $query = PhieuBaoHanh::with(['HoaDon_ChiTiet.HoaDon', 'NhanVien']);

        if ($request->filled('tukhoa')) {
            $tukhoa = trim($request->tukhoa);
            $query->where(function ($q) use ($tukhoa) {
                $q->where('maphieu', 'like', '%' . $tukhoa . '%')
                    ->orWhere('serial_number', 'like', '%' . $tukhoa . '%')
                    ->orWhere('ten_khachhang', 'like', '%' . $tukhoa . '%')
                    ->orWhere('sodienthoai', 'like', '%' . $tukhoa . '%');
            });
        }

        if ($request->filled('trangthai')) {
            $query->where('trangthai', $request->trangthai);
        }

        $phieubaohanh = $query
            ->orderByRaw("FIELD(trangthai, 'cho_tiep_nhan', 'dang_kiem_tra', 'gui_hang', 'da_sua_xong', 'da_tra_khach', 'tu_choi')")
            ->orderByDesc('ngaytiepnhan')
            ->paginate(10)
            ->withQueryString();

        $trangThaiMap = PhieuBaoHanh::danhSachTrangThai();

        return view('admin.baohanh.danhsach', compact('phieubaohanh', 'trangThaiMap'));
    }

    public function getThem()
    {
        $maPhieuGoiY = $this->sinhMaPhieu();

        // Lấy danh sách các máy đã xuất hóa đơn hợp lệ để nhân viên chọn nhanh theo Serial
        $danhSachMayDaBan = HoaDon_ChiTiet::with('HoaDon')
            ->whereHas('HoaDon', function ($q) {
                $q->where('trangthai', '!=', 'da_huy');
            })
            ->orderByDesc('id')
            ->get();

        $trangThaiMap = PhieuBaoHanh::danhSachTrangThai();

        return view('admin.baohanh.them', compact('maPhieuGoiY', 'danhSachMayDaBan', 'trangThaiMap'));
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'maphieu' => ['required', 'string', 'max:30', 'unique:phieubaohanh,maphieu'],
            'hoadon_chitiet_id' => ['nullable', 'integer', 'exists:hoadon_chitiet,id'],
            'serial_number' => ['required', 'string', 'max:100'],
            'tensanpham' => ['required', 'string', 'max:255'],
            'ten_khachhang' => ['required', 'string', 'max:150'],
            'sodienthoai' => ['required', 'string', 'max:20'],
            'ngaytiepnhan' => ['required', 'date'],
            'ngaytra_dukien' => ['nullable', 'date'],
            'mota_loi' => ['required', 'string', 'min:5'],
            'phukien_kemtheo' => ['nullable', 'string', 'max:255'],
            'ketqua_xuly' => ['nullable', 'string'],
            'chiphi_phatsinh' => ['required', 'integer', 'min:0'],
            'trangthai' => ['required', 'in:cho_tiep_nhan,dang_kiem_tra,gui_hang,da_sua_xong,da_tra_khach,tu_choi'],
        ]);

        $sanphamId = null;
        $userId = null;

        if ($request->filled('hoadon_chitiet_id')) {
            $hdct = HoaDon_ChiTiet::with('HoaDon')->find($request->hoadon_chitiet_id);
            if ($hdct) {
                $sanphamId = $hdct->sanpham_id;
                $userId = $hdct->HoaDon->user_id ?? null;
            }
        }

        $orm = PhieuBaoHanh::create([
            'maphieu' => strtoupper(trim($request->maphieu)),
            'hoadon_chitiet_id' => $request->filled('hoadon_chitiet_id') ? $request->hoadon_chitiet_id : null,
            'sanpham_id' => $sanphamId,
            'user_id' => $userId,
            'nhanvien_id' => Auth::id(),
            'serial_number' => strtoupper(trim($request->serial_number)),
            'tensanpham' => trim($request->tensanpham),
            'ten_khachhang' => trim($request->ten_khachhang),
            'sodienthoai' => trim($request->sodienthoai),
            'ngaytiepnhan' => $request->ngaytiepnhan,
            'ngaytra_dukien' => $request->filled('ngaytra_dukien') ? $request->ngaytra_dukien : null,
            'ngaytra_thucte' => $request->trangthai === 'da_tra_khach' ? now() : null,
            'mota_loi' => trim($request->mota_loi),
            'phukien_kemtheo' => $request->phukien_kemtheo,
            'ketqua_xuly' => $request->ketqua_xuly,
            'chiphi_phatsinh' => (int) $request->chiphi_phatsinh,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('admin.baohanh')
            ->with('success', 'Đã lập phiếu tiếp nhận bảo hành "' . $orm->maphieu . '" thành công.');
    }

    public function getSua(int $id)
    {
        $phieu = PhieuBaoHanh::with(['HoaDon_ChiTiet.HoaDon', 'NhanVien'])->findOrFail($id);
        $trangThaiMap = PhieuBaoHanh::danhSachTrangThai();

        return view('admin.baohanh.sua', compact('phieu', 'trangThaiMap'));
    }

    public function postSua(Request $request, int $id)
    {
        $request->validate([
            'ngaytra_dukien' => ['nullable', 'date'],
            'ngaytra_thucte' => ['nullable', 'date'],
            'mota_loi' => ['required', 'string', 'min:5'],
            'phukien_kemtheo' => ['nullable', 'string', 'max:255'],
            'ketqua_xuly' => ['nullable', 'string'],
            'chiphi_phatsinh' => ['required', 'integer', 'min:0'],
            'trangthai' => ['required', 'in:cho_tiep_nhan,dang_kiem_tra,gui_hang,da_sua_xong,da_tra_khach,tu_choi'],
        ]);

        $orm = PhieuBaoHanh::findOrFail($id);
        $orm->nhanvien_id = Auth::id();
        $orm->ngaytra_dukien = $request->filled('ngaytra_dukien') ? $request->ngaytra_dukien : null;
        $orm->mota_loi = trim($request->mota_loi);
        $orm->phukien_kemtheo = $request->phukien_kemtheo;
        $orm->ketqua_xuly = $request->ketqua_xuly;
        $orm->chiphi_phatsinh = (int) $request->chiphi_phatsinh;
        $orm->trangthai = $request->trangthai;

        if ($request->filled('ngaytra_thucte')) {
            $orm->ngaytra_thucte = $request->ngaytra_thucte;
        } elseif ($request->trangthai === 'da_tra_khach' && !$orm->ngaytra_thucte) {
            $orm->ngaytra_thucte = now();
        }

        $orm->save();

        return redirect()->route('admin.baohanh')
            ->with('success', 'Đã cập nhật tiến độ phiếu bảo hành "' . $orm->maphieu . '".');
    }

    public function getInPhieu(int $id)
    {
        $phieu = PhieuBaoHanh::with(['HoaDon_ChiTiet.HoaDon', 'NhanVien'])->findOrFail($id);
        $trangThaiMap = PhieuBaoHanh::danhSachTrangThai();

        return view('admin.baohanh.in', compact('phieu', 'trangThaiMap'));
    }

    public function getXoa(int $id)
    {
        $orm = PhieuBaoHanh::findOrFail($id);
        $ma = $orm->maphieu;
        $orm->delete();

        return redirect()->route('admin.baohanh')
            ->with('success', 'Đã xóa phiếu bảo hành "' . $ma . '".');
    }

    private function sinhMaPhieu(): string
    {
        $stt = PhieuBaoHanh::whereDate('created_at', today())->count() + 1;
        do {
            $ma = 'BH' . date('Ymd') . '-' . str_pad((string) $stt, 3, '0', STR_PAD_LEFT);
            $stt++;
        } while (PhieuBaoHanh::where('maphieu', $ma)->exists());

        return $ma;
    }
}