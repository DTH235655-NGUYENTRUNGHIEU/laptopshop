<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\HoaDon;
use App\Models\HoaDon_ChiTiet;
use App\Models\SanPham;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HoaDonController extends Controller
{
    public function getDanhSach(Request $request)
    {
        $query = HoaDon::with(['DonHang', 'NhanVien'])
            ->withCount('HoaDon_ChiTiet');

        if ($request->filled('tukhoa')) {
            $tukhoa = trim($request->tukhoa);
            $query->where(function ($q) use ($tukhoa) {
                $q->where('sohoadon', 'like', '%' . $tukhoa . '%')
                    ->orWhere('ten_khachhang', 'like', '%' . $tukhoa . '%')
                    ->orWhere('sodienthoai', 'like', '%' . $tukhoa . '%')
                    ->orWhere('masothue', 'like', '%' . $tukhoa . '%');
            });
        }

        if ($request->filled('trangthai')) {
            $query->where('trangthai', $request->trangthai);
        }

        $hoadon = $query->orderByDesc('ngaylaphoadon')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.hoadon.danhsach', compact('hoadon'));
    }

    public function getLapHoaDon($donhang_id)
    {
        $donhang = DonHang::with([
            'User',
            'TinhTrang',
            'MaGiamGia',
            'DonHang_ChiTiet.SanPham',
            'HoaDon',
        ])->findOrFail($donhang_id);

        if ($donhang->HoaDon) {
            return redirect()->route('admin.hoadon.chitiet', ['id' => $donhang->HoaDon->id])
                ->with('warning', 'Đơn hàng ' . $donhang->madonhang . ' đã được xuất hóa đơn ' . $donhang->HoaDon->sohoadon . '.');
        }

        if ((int) $donhang->tinhtrang_id === 6) {
            return redirect()->route('admin.donhang')
                ->with('error', 'Không thể xuất hóa đơn cho đơn hàng đã hủy.');
        }

        $demHomNay = HoaDon::whereDate('created_at', today())->count() + 1;
        $soHoaDonGoiY = 'HD' . date('Ymd') . '-' . str_pad((string) $demHomNay, 4, '0', STR_PAD_LEFT);

        // Tách từng chiếc laptop thành 1 dòng riêng để nhập Serial Number bảo hành
        $danhSachMay = [];
        $tamTinh = 0;
        foreach ($donhang->DonHang_ChiTiet as $ct) {
            $sp = $ct->SanPham;
            $tamTinh += $ct->soluongban * $ct->dongiaban;
            for ($k = 1; $k <= $ct->soluongban; $k++) {
                $danhSachMay[] = [
                    'sanpham_id' => $ct->sanpham_id,
                    'masanpham' => $sp->masanpham ?? '-',
                    'tensanpham' => $sp->tensanpham ?? 'Sản phẩm #' . $ct->sanpham_id,
                    'dongia' => $ct->dongiaban,
                    'baohanh' => $sp->baohanh ?? 12,
                    'tonkho' => $sp->soluong ?? 0,
                ];
            }
        }

        return view('admin.hoadon.lap', compact('donhang', 'soHoaDonGoiY', 'danhSachMay', 'tamTinh'));
    }

    public function postLapHoaDon(Request $request, $donhang_id)
    {
        $request->validate([
            'sohoadon' => ['required', 'string', 'max:30', 'unique:hoadon,sohoadon'],
            'ngaylaphoadon' => ['required', 'date'],
            'ten_khachhang' => ['required', 'string', 'max:150'],
            'sodienthoai' => ['required', 'string', 'max:20'],
            'diachi' => ['required', 'string', 'max:255'],
            'ten_congty' => ['nullable', 'string', 'max:255'],
            'masothue' => ['nullable', 'string', 'max:30'],
            'email_nhanhoadon' => ['nullable', 'email', 'max:150'],
            'thue_vat_phantram' => ['required', 'integer', 'min:0', 'max:20'],
            'phuongthuc_thanhtoan' => ['required', 'in:COD,CHUYENKHOAN,VNPAY,MOMO'],
            'trangthai' => ['required', 'in:chua_thanh_toan,da_thanh_toan'],
            'ghichu' => ['nullable', 'string'],
            'serial_number' => ['nullable', 'array'],
            'serial_number.*' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $hoaDon = DB::transaction(function () use ($request, $donhang_id) {
                $donhang = DonHang::with(['DonHang_ChiTiet.SanPham', 'HoaDon'])
                    ->where('id', $donhang_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($donhang->HoaDon) {
                    throw new \RuntimeException('Đơn hàng này đã có hóa đơn ' . $donhang->HoaDon->sohoadon . '.');
                }

                if ((int) $donhang->tinhtrang_id === 6) {
                    throw new \RuntimeException('Không thể xuất hóa đơn cho đơn hàng đã hủy.');
                }

                // Kiểm tra và trừ tồn kho theo từng sản phẩm trong đơn
                $tamTinh = 0;
                $soLuongCanTru = [];
                foreach ($donhang->DonHang_ChiTiet as $ct) {
                    $tamTinh += $ct->soluongban * $ct->dongiaban;
                    $soLuongCanTru[$ct->sanpham_id] = ($soLuongCanTru[$ct->sanpham_id] ?? 0) + $ct->soluongban;
                }

                foreach ($soLuongCanTru as $spId => $slBan) {
                    $sp = SanPham::where('id', $spId)->lockForUpdate()->first();
                    if (!$sp || $sp->soluong < $slBan) {
                        $tenSp = $sp ? $sp->tensanpham : ('ID #' . $spId);
                        throw new \RuntimeException('Không đủ tồn kho cho "' . $tenSp . '" (cần ' . $slBan . ', hiện chỉ còn ' . ($sp->soluong ?? 0) . ').');
                    }
                    $sp->decrement('soluong', $slBan);
                }

                $tienGiam = (int) $donhang->tiengiam;
                $tienSauGiam = max(0, $tamTinh - $tienGiam);
                $vatPhanTram = (int) $request->thue_vat_phantram;
                $tienThueVat = (int) round($tienSauGiam * $vatPhanTram / 100);
                $tongThanhToan = $tienSauGiam + $tienThueVat;
                $ngayLap = Carbon::parse($request->ngaylaphoadon);

                $hd = HoaDon::create([
                    'sohoadon' => strtoupper(trim($request->sohoadon)),
                    'donhang_id' => $donhang->id,
                    'user_id' => $donhang->user_id,
                    'nhanvien_id' => Auth::id(),
                    'ngaylaphoadon' => $ngayLap,
                    'ten_khachhang' => $request->ten_khachhang,
                    'sodienthoai' => $request->sodienthoai,
                    'diachi' => $request->diachi,
                    'ten_congty' => $request->ten_congty,
                    'masothue' => $request->masothue,
                    'email_nhanhoadon' => $request->email_nhanhoadon,
                    'tamtinh' => $tamTinh,
                    'tiengiam' => $tienGiam,
                    'thue_vat_phantram' => $vatPhanTram,
                    'tienthue_vat' => $tienThueVat,
                    'tongthanhtoan' => $tongThanhToan,
                    'phuongthuc_thanhtoan' => $request->phuongthuc_thanhtoan,
                    'trangthai' => $request->trangthai,
                    'ghichu' => $request->ghichu,
                ]);

                // Lưu chi tiết hóa đơn từng máy kèm Serial Number & ngày hết hạn bảo hành
                $serials = $request->input('serial_number', []);
                $index = 0;

                foreach ($donhang->DonHang_ChiTiet as $ct) {
                    $sp = $ct->SanPham;
                    $baoHanhThang = (int) ($sp->baohanh ?? 12);
                    $hanBaoHanh = $ngayLap->copy()->addMonths($baoHanhThang)->toDateString();

                    for ($k = 1; $k <= $ct->soluongban; $k++) {
                        $sn = isset($serials[$index]) && trim((string) $serials[$index]) !== ''
                            ? strtoupper(trim((string) $serials[$index]))
                            : null;

                        HoaDon_ChiTiet::create([
                            'hoadon_id' => $hd->id,
                            'sanpham_id' => $ct->sanpham_id,
                            'tensanpham_luu' => $sp->tensanpham ?? ('Sản phẩm #' . $ct->sanpham_id),
                            'serial_number' => $sn,
                            'soluong' => 1,
                            'dongia' => $ct->dongiaban,
                            'thanhtien' => $ct->dongiaban,
                            'baohanh_thang' => $baoHanhThang,
                            'han_baohanh' => $hanBaoHanh,
                        ]);

                        $index++;
                    }
                }

                // Đồng bộ trạng thái sang đơn hàng gốc
                if ($request->trangthai === 'da_thanh_toan') {
                    $donhang->trangthai_thanhtoan = true;
                }
                if ((int) $donhang->tinhtrang_id === 1) {
                    $donhang->tinhtrang_id = 2; // Tự chuyển sang Đã xác nhận
                }
                $donhang->save();

                return $hd;
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.hoadon.chitiet', ['id' => $hoaDon->id])
            ->with('success', 'Đã xuất hóa đơn "' . $hoaDon->sohoadon . '" và tự động trừ tồn kho thành công.');
    }

    public function getChiTiet($id)
    {
        $hoadon = HoaDon::with([
            'DonHang',
            'User',
            'NhanVien',
            'HoaDon_ChiTiet.SanPham',
        ])->findOrFail($id);

        return view('admin.hoadon.chitiet', compact('hoadon'));
    }

    public function getInHoaDon($id)
    {
        $hoadon = HoaDon::with([
            'DonHang',
            'NhanVien',
            'HoaDon_ChiTiet.SanPham',
        ])->findOrFail($id);

        return view('admin.hoadon.in', compact('hoadon'));
    }

    public function postHuy($id)
    {
        try {
            $soHoaDon = DB::transaction(function () use ($id) {
                $hoadon = HoaDon::with(['HoaDon_ChiTiet', 'DonHang'])
                    ->where('id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($hoadon->trangthai === 'da_huy') {
                    throw new \RuntimeException('Hóa đơn "' . $hoadon->sohoadon . '" đã ở trạng thái hủy trước đó.');
                }

                // Hoàn trả tồn kho cho các máy đã xuất trong hóa đơn
                foreach ($hoadon->HoaDon_ChiTiet as $ct) {
                    SanPham::where('id', $ct->sanpham_id)->increment('soluong', $ct->soluong);
                }

                $hoadon->trangthai = 'da_huy';
                $hoadon->save();

                if ($hoadon->DonHang) {
                    $hoadon->DonHang->trangthai_thanhtoan = false;
                    $hoadon->DonHang->save();
                }

                return $hoadon->sohoadon;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.hoadon.chitiet', ['id' => $id])
                ->with('warning', $e->getMessage());
        }

        return redirect()->route('admin.hoadon.chitiet', ['id' => $id])
            ->with('success', 'Đã hủy hóa đơn "' . $soHoaDon . '" và hoàn trả số lượng tồn kho.');
    }
}