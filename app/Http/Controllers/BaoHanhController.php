<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\HoaDon_ChiTiet;
use App\Models\PhieuBaoHanh;
use App\Models\User;
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
        $user = User::findOrFail(Auth::id());
        $userId = $user->id;
        $sdtKhach = trim((string) ($user->sodienthoai ?? ''));

        // Lấy tất cả thiết bị thuộc hóa đơn hợp lệ của khách (theo user_id, đơn hàng của khách, hoặc khớp SĐT)
        $thietBiDaMua = HoaDon_ChiTiet::with(['HoaDon', 'PhieuBaoHanh'])
            ->whereHas('HoaDon', function ($q) use ($userId, $sdtKhach) {
                $q->where('trangthai', '!=', 'da_huy')
                    ->where(function ($sub) use ($userId, $sdtKhach) {
                        $sub->where('user_id', $userId)
                            ->orWhereHas('DonHang', function ($dh) use ($userId) {
                                $dh->where('user_id', $userId);
                            });
                        if ($sdtKhach !== '') {
                            $sub->orWhere('sodienthoai', $sdtKhach);
                        }
                    });
            })
            ->orderByDesc('id')
            ->get();

        // Liệt kê đơn hàng chưa hủy nhưng cửa hàng chưa bấm Xuất hóa đơn
        $donHangChuaXuatHD = DonHang::with('TinhTrang')
            ->where('user_id', $userId)
            ->where('tinhtrang_id', '!=', 6)
            ->whereDoesntHave('HoaDon', function ($q) {
                $q->where('trangthai', '!=', 'da_huy');
            })
            ->orderByDesc('created_at')
            ->get();

        $dsHdctId = $thietBiDaMua->pluck('id')->all();
        $dsSerial = $thietBiDaMua->pluck('serial_number')->filter()->unique()->values()->all();

        // Đồng bộ 2 chiều: hiện cả phiếu khách gửi lẫn phiếu Admin lập theo user_id / SĐT / hóa đơn / Serial
        $phieuBaoHanh = PhieuBaoHanh::with(['HoaDon_ChiTiet.HoaDon', 'NhanVien'])
            ->where(function ($q) use ($userId, $sdtKhach, $dsHdctId, $dsSerial) {
                $q->where('user_id', $userId);
                if ($sdtKhach !== '') {
                    $q->orWhere('sodienthoai', $sdtKhach);
                }
                if (!empty($dsHdctId)) {
                    $q->orWhereIn('hoadon_chitiet_id', $dsHdctId);
                }
                if (!empty($dsSerial)) {
                    $q->orWhereIn('serial_number', $dsSerial);
                }
            })
            ->orderByDesc('ngaytiepnhan')
            ->paginate(8);

        $trangThaiMap = PhieuBaoHanh::danhSachTrangThai();

        return view('user.baohanh', compact(
            'thietBiDaMua',
            'donHangChuaXuatHD',
            'phieuBaoHanh',
            'trangThaiMap'
        ));
    }

    public function postKhachHangYeuCau(Request $request)
    {
        $request->validate([
            'hoadon_chitiet_id' => ['required', 'integer', 'exists:hoadon_chitiet,id'],
            'mota_loi' => ['required', 'string', 'min:10', 'max:1000'],
            'phukien_kemtheo' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::findOrFail(Auth::id());
        $userId = $user->id;
        $sdtKhach = trim((string) ($user->sodienthoai ?? ''));

        $hdct = HoaDon_ChiTiet::with('HoaDon')
            ->where('id', $request->hoadon_chitiet_id)
            ->whereHas('HoaDon', function ($q) use ($userId, $sdtKhach) {
                $q->where('trangthai', '!=', 'da_huy')
                    ->where(function ($sub) use ($userId, $sdtKhach) {
                        $sub->where('user_id', $userId)
                            ->orWhereHas('DonHang', function ($dh) use ($userId) {
                                $dh->where('user_id', $userId);
                            });
                        if ($sdtKhach !== '') {
                            $sub->orWhere('sodienthoai', $sdtKhach);
                        }
                    });
            })
            ->firstOrFail();

        $dangXuLy = PhieuBaoHanh::where('hoadon_chitiet_id', $hdct->id)
            ->whereIn('trangthai', ['cho_tiep_nhan', 'dang_kiem_tra', 'gui_hang', 'da_sua_xong'])
            ->exists();

        if ($dangXuLy) {
            return redirect()->route('user.baohanh')
                ->with('warning', 'Thiết bị này hiện đang có một phiếu bảo hành chưa hoàn tất.');
        }

        $maPhieu = $this->sinhMaPhieu();
        $serial = !empty($hdct->serial_number)
            ? strtoupper(trim((string) $hdct->serial_number))
            : ('SN-HD' . $hdct->hoadon_id . '-' . $hdct->id);

        PhieuBaoHanh::create([
            'maphieu' => $maPhieu,
            'hoadon_chitiet_id' => $hdct->id,
            'sanpham_id' => $hdct->sanpham_id,
            'user_id' => $userId,
            'nhanvien_id' => null,
            'serial_number' => $serial,
            'tensanpham' => $hdct->tensanpham_luu,
            'ten_khachhang' => $hdct->HoaDon->ten_khachhang ?? $user->name,
            'sodienthoai' => $hdct->HoaDon->sodienthoai ?? ($user->sodienthoai ?? ''),
            'ngaytiepnhan' => now(),
            'ngaytra_dukien' => now()->addDays(7)->toDateString(),
            'mota_loi' => trim($request->mota_loi),
            'phukien_kemtheo' => $request->phukien_kemtheo,
            'chiphi_phatsinh' => 0,
            'trangthai' => 'cho_tiep_nhan',
        ]);

        return redirect()->route('user.baohanh')
            ->with('success', 'Đã gửi yêu cầu bảo hành mã "' . $maPhieu . '". Kỹ thuật viên sẽ tiếp nhận và cập nhật tiến độ ngay trên trang này.');
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

        $serial = strtoupper(trim($request->serial_number));
        $sdt = trim($request->sodienthoai);
        $hdctId = $request->filled('hoadon_chitiet_id') ? (int) $request->hoadon_chitiet_id : null;
        $sanphamId = null;
        $userId = null;

        // Tự động khớp dòng hóa đơn theo ID đã chọn hoặc theo Serial Number
        $hdct = $hdctId
            ? HoaDon_ChiTiet::with('HoaDon.DonHang')->find($hdctId)
            : HoaDon_ChiTiet::with('HoaDon.DonHang')
                ->where('serial_number', $serial)
                ->whereHas('HoaDon', function ($q) {
                    $q->where('trangthai', '!=', 'da_huy');
                })
                ->orderByDesc('id')
                ->first();

        if ($hdct) {
            $hdctId = $hdct->id;
            $sanphamId = $hdct->sanpham_id;
            $userId = $hdct->HoaDon->user_id ?? ($hdct->HoaDon->DonHang->user_id ?? null);
        }

        // Nếu vẫn chưa gắn được user_id (nhập tay hoặc hóa đơn tại quầy), tự khớp theo SĐT khách hàng
        if (!$userId && $sdt !== '') {
            $userId = User::where('sodienthoai', $sdt)->value('id');
        }

        $orm = PhieuBaoHanh::create([
            'maphieu' => strtoupper(trim($request->maphieu)),
            'hoadon_chitiet_id' => $hdctId,
            'sanpham_id' => $sanphamId,
            'user_id' => $userId,
            'nhanvien_id' => Auth::id(),
            'serial_number' => $serial,
            'tensanpham' => trim($request->tensanpham),
            'ten_khachhang' => trim($request->ten_khachhang),
            'sodienthoai' => $sdt,
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
            ->with('success', 'Đã lập phiếu tiếp nhận bảo hành "' . $orm->maphieu . '" và đồng bộ sang tài khoản khách hàng.');
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

        $orm = PhieuBaoHanh::with('HoaDon_ChiTiet.HoaDon')->findOrFail($id);

        // Tự động bổ sung user_id nếu phiếu cũ chưa được gắn tài khoản khách
        if (!$orm->user_id) {
            $orm->user_id = $orm->HoaDon_ChiTiet->HoaDon->user_id
                ?? User::where('sodienthoai', $orm->sodienthoai)->value('id');
        }

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