<?php

namespace App\Http\Controllers;

use App\Mail\DatHangThanhCongEmail;
use App\Models\BaiViet;
use App\Models\BinhLuanBaiViet;
use App\Models\DanhGia;
use App\Models\DonHang;
use App\Models\DonHang_ChiTiet;
use App\Models\MaGiamGia;
use App\Models\SanPham;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class KhachHangController extends Controller
{
    public function getHome()
    {
        $user = User::findOrFail(Auth::id());

        $tongDonHang = DonHang::where('user_id', $user->id)->count();
        $donChoXuLy = DonHang::where('user_id', $user->id)->whereIn('tinhtrang_id', [1, 2, 3, 4])->count();
        $donHoanThanh = DonHang::where('user_id', $user->id)->where('tinhtrang_id', 5)->count();
        $tongChiTieu = DonHang::where('user_id', $user->id)->where('tinhtrang_id', '!=', 6)->sum('tongtien');

        $donHangGanDay = DonHang::with(['TinhTrang', 'HoaDon'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('user.home', compact(
            'user',
            'tongDonHang',
            'donChoXuLy',
            'donHoanThanh',
            'tongChiTieu',
            'donHangGanDay'
        ));
    }

    // =========================================================================
    // ĐẶT HÀNG & THANH TOÁN
    // =========================================================================
    public function getDatHang()
    {
        if (Cart::count() <= 0) {
            return redirect()->route('frontend.giohang')
                ->with('warning', 'Giỏ hàng của bạn đang trống, vui lòng chọn sản phẩm trước khi đặt hàng.');
        }

        $user = User::findOrFail(Auth::id());
        $tamTinh = 0;
        foreach (Cart::content() as $item) {
            $tamTinh += (int) $item->price * (int) $item->qty;
        }

        $voucher = session('voucher');
        $tienGiam = 0;

        if ($voucher) {
            $ma = MaGiamGia::find($voucher['id']);
            if (!$ma || !$ma->kichhoat || $ma->soluong <= 0 || $tamTinh < $ma->donhang_toithieu) {
                session()->forget('voucher');
                $voucher = null;
            } else {
                $tienGiam = $ma->loai === 'percent'
                    ? (int) round($tamTinh * $ma->giatri / 100)
                    : (int) min($ma->giatri, $tamTinh);
                $voucher['tiengiam'] = $tienGiam;
                session()->put('voucher.tiengiam', $tienGiam);
            }
        }

        $tongCong = max(0, $tamTinh - $tienGiam);

        return view('user.dathang', compact('user', 'tamTinh', 'tienGiam', 'tongCong', 'voucher'));
    }

    public function postDatHang(Request $request)
    {
        if (Cart::count() <= 0) {
            return redirect()->route('frontend.giohang')
                ->with('warning', 'Giỏ hàng của bạn đang trống.');
        }

        $request->validate([
            'hoten_nguoinhan' => ['required', 'string', 'max:150'],
            'dienthoaigiaohang' => ['required', 'string', 'max:20'],
            'diachigiaohang' => ['required', 'string', 'max:255'],
            'phuongthuc_thanhtoan' => ['required', 'in:COD,CHUYENKHOAN,VNPAY,MOMO'],
            'ghichu' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $donhang = DB::transaction(function () use ($request) {
                $tamTinh = 0;
                $cartItems = Cart::content();

                // Kiểm tra tồn kho thực tế của từng sản phẩm trước khi tạo đơn
                foreach ($cartItems as $item) {
                    $sp = SanPham::where('id', $item->id)->lockForUpdate()->first();
                    if (!$sp || !$sp->trangthai) {
                        throw new RuntimeException('Sản phẩm "' . $item->name . '" hiện đã ngừng kinh doanh.');
                    }
                    if ($sp->soluong < (int) $item->qty) {
                        throw new RuntimeException('Sản phẩm "' . $sp->tensanpham . '" chỉ còn ' . $sp->soluong . ' máy trong kho.');
                    }
                    $tamTinh += (int) $item->price * (int) $item->qty;
                }

                // Xác thực và trừ lượt sử dụng mã giảm giá (nếu có)
                $magiamgiaId = null;
                $tienGiam = 0;
                $voucher = session('voucher');

                if ($voucher) {
                    $ma = MaGiamGia::where('id', $voucher['id'])->lockForUpdate()->first();
                    $bayGio = now();
                    $hopLe = $ma
                        && $ma->kichhoat
                        && $ma->soluong > 0
                        && $tamTinh >= $ma->donhang_toithieu
                        && (!$ma->ngaybatdau || $bayGio->gte($ma->ngaybatdau))
                        && (!$ma->ngayketthuc || $bayGio->lte($ma->ngayketthuc));

                    if ($hopLe) {
                        $magiamgiaId = $ma->id;
                        $tienGiam = $ma->loai === 'percent'
                            ? (int) round($tamTinh * $ma->giatri / 100)
                            : (int) min($ma->giatri, $tamTinh);
                        $ma->decrement('soluong');
                    }
                }

                $tongTien = max(0, $tamTinh - $tienGiam);

                // Sinh mã đơn hàng duy nhất theo ngày
                $stt = DonHang::whereDate('created_at', today())->count() + 1;
                do {
                    $maDonHang = 'DH' . date('Ymd') . '-' . str_pad((string) $stt, 3, '0', STR_PAD_LEFT);
                    $stt++;
                } while (DonHang::where('madonhang', $maDonHang)->exists());

                $dh = DonHang::create([
                    'madonhang' => $maDonHang,
                    'user_id' => Auth::id(),
                    'tinhtrang_id' => 1, // Chờ xác nhận
                    'magiamgia_id' => $magiamgiaId,
                    'hoten_nguoinhan' => trim($request->hoten_nguoinhan),
                    'dienthoaigiaohang' => trim($request->dienthoaigiaohang),
                    'diachigiaohang' => trim($request->diachigiaohang),
                    'ghichu' => $request->ghichu,
                    'phuongthuc_thanhtoan' => $request->phuongthuc_thanhtoan,
                    'trangthai_thanhtoan' => false,
                    'tiengiam' => $tienGiam,
                    'tongtien' => $tongTien,
                ]);

                foreach ($cartItems as $item) {
                    DonHang_ChiTiet::create([
                        'donhang_id' => $dh->id,
                        'sanpham_id' => $item->id,
                        'soluongban' => (int) $item->qty,
                        'dongiaban' => (int) $item->price,
                    ]);
                }

                // Cập nhật SĐT / địa chỉ mặc định nếu hồ sơ khách đang trống
                $user = User::find(Auth::id());
                if ($user) {
                    $canLuu = false;
                    if (empty($user->sodienthoai)) {
                        $user->sodienthoai = trim($request->dienthoaigiaohang);
                        $canLuu = true;
                    }
                    if (empty($user->diachi)) {
                        $user->diachi = trim($request->diachigiaohang);
                        $canLuu = true;
                    }
                    if ($canLuu) {
                        $user->save();
                    }
                }

                return $dh;
            });
        } catch (RuntimeException $e) {
            return redirect()->route('frontend.giohang')->with('error', $e->getMessage());
        }

        // Gửi email xác nhận đặt hàng (không làm gián đoạn đơn nếu lỗi máy chủ mail)
        try {
            $donhang->load(['DonHang_ChiTiet.SanPham', 'MaGiamGia', 'User']);
            $emailKhach = Auth::user()->email ?? null;
            if ($emailKhach) {
                Mail::to($emailKhach)->send(new DatHangThanhCongEmail($donhang));
            }
        } catch (Throwable $e) {
            Log::warning('Không gửi được email xác nhận đơn hàng ' . $donhang->madonhang . ': ' . $e->getMessage());
        }

        Cart::destroy();
        session()->forget('voucher');
        session()->put('donhang_moi_id', $donhang->id);

        return redirect()->route('user.dathangthanhcong');
    }

    public function getDatHangThanhCong()
    {
        $donhangId = session()->pull('donhang_moi_id');
        $donhang = null;

        if ($donhangId) {
            $donhang = DonHang::with(['DonHang_ChiTiet.SanPham', 'MaGiamGia'])
                ->where('id', $donhangId)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('user.dathangthanhcong', compact('donhang'));
    }

    // =========================================================================
    // LỊCH SỬ ĐƠN HÀNG & HỦY ĐƠN
    // =========================================================================
    public function getDonHang()
    {
        $donhang = DonHang::with(['TinhTrang', 'HoaDon', 'DonHang_ChiTiet.SanPham'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(8);

        return view('user.donhang', compact('donhang'));
    }

    public function getDonHang_ChiTiet(int $id)
    {
        $donhang = DonHang::with([
            'TinhTrang',
            'MaGiamGia',
            'DonHang_ChiTiet.SanPham',
            'HoaDon.HoaDon_ChiTiet',
        ])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.donhang_chitiet', compact('donhang'));
    }

    public function postHuyDonHang(int $id)
    {
        $donhang = DonHang::with('HoaDon')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ((int) $donhang->tinhtrang_id !== 1 || $donhang->HoaDon) {
            return redirect()->route('user.donhang.chitiet', ['id' => $donhang->id])
                ->with('error', 'Chỉ có thể hủy đơn hàng khi đang ở trạng thái "Chờ xác nhận" và chưa xuất hóa đơn.');
        }

        DB::transaction(function () use ($donhang) {
            $donhang->tinhtrang_id = 6; // Đã hủy
            $donhang->save();

            if ($donhang->magiamgia_id) {
                MaGiamGia::where('id', $donhang->magiamgia_id)->increment('soluong');
            }
        });

        return redirect()->route('user.donhang.chitiet', ['id' => $donhang->id])
            ->with('success', 'Đã hủy đơn hàng ' . $donhang->madonhang . ' thành công.');
    }

    // =========================================================================
    // ĐÁNH GIÁ SẢN PHẨM & BÌNH LUẬN BÀI VIẾT
    // =========================================================================
    public function postDanhGia(Request $request, int $sanpham_id)
    {
        $sanpham = SanPham::findOrFail($sanpham_id);

        $request->validate([
            'sosao' => ['required', 'integer', 'min:1', 'max:5'],
            'noidung' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        DanhGia::create([
            'sanpham_id' => $sanpham->id,
            'user_id' => Auth::id(),
            'sosao' => (int) $request->sosao,
            'noidung' => trim($request->noidung),
            'kiemduyet' => true,
        ]);

        return redirect()->back()
            ->with('success', 'Cảm ơn bạn đã gửi đánh giá cho "' . $sanpham->tensanpham . '"!');
    }

    public function postBinhLuan(Request $request, int $baiviet_id)
    {
        $baiviet = BaiViet::findOrFail($baiviet_id);

        $request->validate([
            'noidungbinhluan' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        BinhLuanBaiViet::create([
            'baiviet_id' => $baiviet->id,
            'user_id' => Auth::id(),
            'noidungbinhluan' => trim($request->noidungbinhluan),
            'kiemduyet' => false, // Chờ Admin duyệt tại /admin/binhluanbaiviet
            'kichhoat' => true,
        ]);

        return redirect()->back()
            ->with('success', 'Đã gửi bình luận! Bình luận của bạn sẽ hiển thị sau khi quản trị viên kiểm duyệt.');
    }

    // =========================================================================
    // HỒ SƠ CÁ NHÂN & ĐỔI MẬT KHẨU
    // =========================================================================
    public function getHoSo()
    {
        $user = User::findOrFail(Auth::id());
        return view('user.hoso', compact('user'));
    }

    public function postHoSo(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'sodienthoai' => ['nullable', 'string', 'max:20'],
            'diachi' => ['nullable', 'string', 'max:255'],
        ]);

        $user->name = trim($request->name);
        $user->username = $request->filled('username') ? trim($request->username) : $user->username;
        $user->sodienthoai = $request->sodienthoai;
        $user->diachi = $request->diachi;
        $user->save();

        return redirect()->route('user.hoso')
            ->with('success', 'Đã cập nhật thông tin hồ sơ cá nhân thành công.');
    }

    public function postDoiMatKhau(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
        ]);

        $user = User::findOrFail(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.hoso')
            ->with('success', 'Đã đổi mật khẩu tài khoản thành công.');
    }
}