<?php

namespace App\Http\Controllers;

use App\Models\BaiViet;
use App\Models\Banner;
use App\Models\ChuDe;
use App\Models\HangSanXuat;
use App\Models\LienHe;
use App\Models\LoaiSanPham;
use App\Models\MaGiamGia;
use App\Models\SanPham;
use App\Models\User;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'staff'], true)) {
            return redirect()->route('admin.home');
        }

        return redirect()->route('frontend.home');
    }

    // =========================================================================
    // TRANG CHỦ FRONTEND
    // =========================================================================
    public function getHome()
    {
        $sliders = Banner::where('kichhoat', 1)
            ->where('vitri', 'slider_chinh')
            ->orderBy('thutu')
            ->get();

        $bannerPhu = Banner::where('kichhoat', 1)
            ->where('vitri', 'banner_phu')
            ->orderBy('thutu')
            ->take(2)
            ->get();

        $hangsanxuat = HangSanXuat::withCount(['SanPham' => function ($q) {
            $q->where('trangthai', 1);
        }])->orderBy('id')->get();

        $sanPhamNoiBat = SanPham::with(['LoaiSanPham', 'HangSanXuat'])
            ->where('trangthai', 1)
            ->where('noibat', 1)
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $loaisanpham = LoaiSanPham::with(['SanPham' => function ($q) {
            $q->with('HangSanXuat')
                ->where('trangthai', 1)
                ->orderByDesc('id')
                ->take(4);
        }])->orderBy('id')->get();

        $baiVietMoi = BaiViet::with('ChuDe')
            ->where('kichhoat', 1)
            ->where('kiemduyet', 1)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('frontend.home', compact(
            'sliders',
            'bannerPhu',
            'hangsanxuat',
            'sanPhamNoiBat',
            'loaisanpham',
            'baiVietMoi'
        ));
    }

    // =========================================================================
    // DANH SÁCH, BỘ LỌC & TÌM KIẾM SẢN PHẨM
    // =========================================================================
    public function getSanPham(Request $request, $tenloai_slug = '')
    {
        $query = SanPham::with(['LoaiSanPham', 'HangSanXuat'])->where('trangthai', 1);
        $title = 'Tất cả Laptop';
        $loaiHienTai = null;

        if (!empty($tenloai_slug)) {
            $loaiHienTai = LoaiSanPham::where('tenloai_slug', $tenloai_slug)->firstOrFail();
            $query->where('loaisanpham_id', $loaiHienTai->id);
            $title = $loaiHienTai->tenloai;
        }

        return $this->renderDanhSachSanPham($request, $query, $title, $loaiHienTai, null);
    }

    public function getSanPhamTheoHang(Request $request, $tenhang_slug)
    {
        $hangHienTai = HangSanXuat::where('tenhang_slug', $tenhang_slug)->firstOrFail();
        $query = SanPham::with(['LoaiSanPham', 'HangSanXuat'])
            ->where('trangthai', 1)
            ->where('hangsanxuat_id', $hangHienTai->id);

        $title = 'Laptop ' . $hangHienTai->tenhang;

        return $this->renderDanhSachSanPham($request, $query, $title, null, $hangHienTai);
    }

    public function getTimKiem(Request $request)
    {
        $tukhoa = trim((string) $request->input('tukhoa', ''));
        $query = SanPham::with(['LoaiSanPham', 'HangSanXuat'])->where('trangthai', 1);

        if ($tukhoa !== '') {
            $query->where(function ($q) use ($tukhoa) {
                $q->where('tensanpham', 'like', '%' . $tukhoa . '%')
                    ->orWhere('masanpham', 'like', '%' . $tukhoa . '%')
                    ->orWhere('cpu', 'like', '%' . $tukhoa . '%')
                    ->orWhere('ram', 'like', '%' . $tukhoa . '%')
                    ->orWhere('carddohoa', 'like', '%' . $tukhoa . '%')
                    ->orWhereHas('HangSanXuat', function ($h) use ($tukhoa) {
                        $h->where('tenhang', 'like', '%' . $tukhoa . '%');
                    });
            });
        }

        $title = $tukhoa !== '' ? 'Kết quả tìm kiếm: "' . $tukhoa . '"' : 'Tất cả Laptop';

        return $this->renderDanhSachSanPham($request, $query, $title, null, null);
    }

    private function renderDanhSachSanPham(Request $request, $query, string $title, $loaiHienTai, $hangHienTai)
    {
        // Lọc bổ sung theo hãng / loại từ thanh sidebar
        if (!$loaiHienTai && $request->filled('loai')) {
            $query->where('loaisanpham_id', (int) $request->loai);
        }

        if (!$hangHienTai && $request->filled('hang')) {
            $query->where('hangsanxuat_id', (int) $request->hang);
        }

        // Lọc theo tầm giá (dựa trên giá thực tế: ưu tiên giakhuyenmai nếu có)
        if ($request->filled('mucgia')) {
            match ($request->mucgia) {
                'duoi-15' => $query->whereRaw('COALESCE(giakhuyenmai, dongia) < 15000000'),
                '15-25' => $query->whereRaw('COALESCE(giakhuyenmai, dongia) BETWEEN 15000000 AND 25000000'),
                '25-35' => $query->whereRaw('COALESCE(giakhuyenmai, dongia) BETWEEN 25000000 AND 35000000'),
                'tren-35' => $query->whereRaw('COALESCE(giakhuyenmai, dongia) > 35000000'),
                default => null,
            };
        }

        // Sắp xếp
        match ($request->input('sapxep', 'moi-nhat')) {
            'gia-tang' => $query->orderByRaw('COALESCE(giakhuyenmai, dongia) ASC'),
            'gia-giam' => $query->orderByRaw('COALESCE(giakhuyenmai, dongia) DESC'),
            'ten-az' => $query->orderBy('tensanpham', 'asc'),
            default => $query->orderByDesc('id'),
        };

        $sanpham = $query->paginate(9)->withQueryString();
        $dsLoai = LoaiSanPham::orderBy('id')->get();
        $dsHang = HangSanXuat::orderBy('tenhang')->get();

        return view('frontend.sanpham', compact(
            'sanpham',
            'title',
            'loaiHienTai',
            'hangHienTai',
            'dsLoai',
            'dsHang'
        ));
    }

    // =========================================================================
    // CHI TIẾT LAPTOP
    // =========================================================================
    public function getSanPham_ChiTiet($tenloai_slug = '', $tensanpham_slug = '')
    {
        $sanpham = SanPham::with([
            'LoaiSanPham',
            'HangSanXuat',
            'SanPhamHinhAnh' => function ($q) {
                $q->orderBy('thutu');
            },
            'DanhGia' => function ($q) {
                $q->where('kiemduyet', 1)->with('User')->orderByDesc('created_at');
            },
        ])
            ->where('tensanpham_slug', $tensanpham_slug)
            ->where('trangthai', 1)
            ->firstOrFail();

        $diemTrungBinh = $sanpham->DanhGia->count() > 0
            ? round($sanpham->DanhGia->avg('sosao'), 1)
            : 0;

        $sanPhamLienQuan = SanPham::with(['LoaiSanPham', 'HangSanXuat'])
            ->where('trangthai', 1)
            ->where('id', '!=', $sanpham->id)
            ->where(function ($q) use ($sanpham) {
                $q->where('loaisanpham_id', $sanpham->loaisanpham_id)
                    ->orWhere('hangsanxuat_id', $sanpham->hangsanxuat_id);
            })
            ->orderByDesc('id')
            ->take(4)
            ->get();

        return view('frontend.sanpham_chitiet', compact(
            'sanpham',
            'diemTrungBinh',
            'sanPhamLienQuan'
        ));
    }

    // =========================================================================
    // GIỎ HÀNG & MÃ GIẢM GIÁ (VOUCHER)
    // =========================================================================
    public function getGioHang()
    {
        if (Cart::count() <= 0) {
            session()->forget('voucher');
            return view('frontend.giohangrong');
        }

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
                session()->put('voucher.tiengiam', $tienGiam);
                $voucher['tiengiam'] = $tienGiam;
            }
        }

        $tongCong = max(0, $tamTinh - $tienGiam);

        return view('frontend.giohang', compact('tamTinh', 'tienGiam', 'tongCong', 'voucher'));
    }

    public function getGioHang_Them(Request $request, $tensanpham_slug = '')
    {
        $sanpham = SanPham::with('LoaiSanPham')
            ->where('tensanpham_slug', $tensanpham_slug)
            ->where('trangthai', 1)
            ->firstOrFail();

        if ($sanpham->soluong <= 0) {
            return redirect()->back()->with('error', 'Mẫu laptop "' . $sanpham->tensanpham . '" hiện đang tạm hết hàng.');
        }

        $giaBan = $sanpham->giakhuyenmai ?? $sanpham->dongia;

        Cart::add([
            'id' => $sanpham->id,
            'name' => $sanpham->tensanpham,
            'price' => (int) $giaBan,
            'qty' => 1,
            'weight' => 0,
            'options' => [
                'image' => $sanpham->hinhanh,
                'slug' => $sanpham->tensanpham_slug,
                'loai_slug' => $sanpham->LoaiSanPham->tenloai_slug ?? 'laptop',
                'masanpham' => $sanpham->masanpham,
            ],
        ]);

        if ($request->boolean('muangay')) {
            return redirect()->route('frontend.giohang');
        }

        return redirect()->back()->with('success', 'Đã thêm "' . $sanpham->tensanpham . '" vào giỏ hàng.');
    }

    public function getGioHang_Xoa($row_id)
    {
        try {
            Cart::remove($row_id);
        } catch (Exception $e) {
            // Bỏ qua nếu row_id không còn tồn tại
        }

        return redirect()->route('frontend.giohang')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function getGioHang_Giam($row_id)
    {
        try {
            $row = Cart::get($row_id);
            if ($row->qty > 1) {
                Cart::update($row_id, $row->qty - 1);
            }
        } catch (Exception $e) {
            //
        }

        return redirect()->route('frontend.giohang');
    }

    public function getGioHang_Tang($row_id)
    {
        try {
            $row = Cart::get($row_id);
            $sp = SanPham::find($row->id);
            $gioiHan = $sp ? min(10, (int) $sp->soluong) : 10;

            if ($row->qty < $gioiHan) {
                Cart::update($row_id, $row->qty + 1);
            } else {
                return redirect()->route('frontend.giohang')
                    ->with('warning', 'Đã đạt giới hạn số lượng tồn kho cho sản phẩm "' . $row->name . '".');
            }
        } catch (Exception $e) {
            //
        }

        return redirect()->route('frontend.giohang');
    }

    public function postGioHang_CapNhat(Request $request)
    {
        $dsSoLuong = $request->input('qty', []);
        foreach ($dsSoLuong as $rowId => $sl) {
            $slInt = max(1, min(10, (int) $sl));
            try {
                $row = Cart::get($rowId);
                $sp = SanPham::find($row->id);
                if ($sp && $slInt > $sp->soluong) {
                    $slInt = max(1, (int) $sp->soluong);
                }
                Cart::update($rowId, $slInt);
            } catch (Exception $e) {
                //
            }
        }

        return redirect()->route('frontend.giohang')->with('success', 'Đã cập nhật số lượng giỏ hàng.');
    }

    public function postApDungMaGiamGia(Request $request)
    {
        if ($request->boolean('huy_ma')) {
            session()->forget('voucher');
            return redirect()->route('frontend.giohang')->with('success', 'Đã gỡ bỏ mã giảm giá.');
        }

        $request->validate([
            'macode' => ['required', 'string', 'max:50'],
        ]);

        $code = strtoupper(trim($request->macode));
        $ma = MaGiamGia::where('macode', $code)->first();

        if (!$ma || !$ma->kichhoat) {
            return redirect()->route('frontend.giohang')->with('error', 'Mã giảm giá "' . $code . '" không tồn tại hoặc đã ngừng áp dụng.');
        }

        if ($ma->soluong <= 0) {
            return redirect()->route('frontend.giohang')->with('error', 'Mã giảm giá "' . $code . '" đã hết lượt sử dụng.');
        }

        $bayGio = now();
        if (($ma->ngaybatdau && $bayGio->lt($ma->ngaybatdau)) || ($ma->ngayketthuc && $bayGio->gt($ma->ngayketthuc))) {
            return redirect()->route('frontend.giohang')->with('error', 'Mã giảm giá "' . $code . '" chưa đến hạn hoặc đã hết hạn.');
        }

        $tamTinh = 0;
        foreach (Cart::content() as $item) {
            $tamTinh += (int) $item->price * (int) $item->qty;
        }

        if ($tamTinh < $ma->donhang_toithieu) {
            return redirect()->route('frontend.giohang')->with(
                'error',
                'Đơn hàng cần tối thiểu ' . number_format($ma->donhang_toithieu, 0, ',', '.') . 'đ để dùng mã ' . $code . '.'
            );
        }

        $tienGiam = $ma->loai === 'percent'
            ? (int) round($tamTinh * $ma->giatri / 100)
            : (int) min($ma->giatri, $tamTinh);

        session()->put('voucher', [
            'id' => $ma->id,
            'macode' => $ma->macode,
            'loai' => $ma->loai,
            'giatri' => $ma->giatri,
            'tiengiam' => $tienGiam,
        ]);

        return redirect()->route('frontend.giohang')
            ->with('success', 'Đã áp dụng mã "' . $ma->macode . '" — giảm ' . number_format($tienGiam, 0, ',', '.') . 'đ.');
    }

    // =========================================================================
    // TIN TỨC & BÀI VIẾT
    // =========================================================================
    public function getBaiViet($tenchude_slug = '')
    {
        $dsChuDe = ChuDe::withCount(['BaiViet' => function ($q) {
            $q->where('kichhoat', 1)->where('kiemduyet', 1);
        }])->orderBy('id')->get();

        if (empty($tenchude_slug)) {
            $title = 'Tất cả tin tức công nghệ';
            $baiviet = BaiViet::with(['ChuDe', 'User'])
                ->where('kichhoat', 1)
                ->where('kiemduyet', 1)
                ->orderByDesc('created_at')
                ->paginate(6);
        } else {
            $chude = ChuDe::where('tenchude_slug', $tenchude_slug)->firstOrFail();
            $title = $chude->tenchude;
            $baiviet = BaiViet::with(['ChuDe', 'User'])
                ->where('kichhoat', 1)
                ->where('kiemduyet', 1)
                ->where('chude_id', $chude->id)
                ->orderByDesc('created_at')
                ->paginate(6);
        }

        return view('frontend.baiviet', compact('title', 'baiviet', 'dsChuDe', 'tenchude_slug'));
    }

    public function getBaiViet_ChiTiet($tenchude_slug = '', $tieude_slug = '')
    {
        $query = BaiViet::with([
            'ChuDe',
            'User',
            'BinhLuanBaiViet' => function ($q) {
                $q->where('kiemduyet', 1)->where('kichhoat', 1)->with('User')->orderByDesc('created_at');
            },
        ])->where('kichhoat', 1)->where('kiemduyet', 1);

        if (Str::endsWith($tieude_slug, '.html')) {
            $base = Str::beforeLast($tieude_slug, '.html');
            $id = (int) Str::afterLast($base, '-');
            $baiviet = $query->where('id', $id)->firstOrFail();
        } else {
            $baiviet = $query->where('tieude_slug', $tieude_slug)->firstOrFail();
        }

        // Tăng lượt xem (chống cộng dồn trong cùng phiên session)
        $sessionKey = 'da_xem_baiviet_' . $baiviet->id;
        if (!session()->has($sessionKey)) {
            $baiviet->increment('luotxem');
            session()->put($sessionKey, true);
        }

        $baiVietLienQuan = BaiViet::with('ChuDe')
            ->where('kichhoat', 1)
            ->where('kiemduyet', 1)
            ->where('chude_id', $baiviet->chude_id)
            ->where('id', '!=', $baiviet->id)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('frontend.baiviet_chitiet', compact('baiviet', 'baiVietLienQuan'));
    }

    // =========================================================================
    // LIÊN HỆ, GIỚI THIỆU & XÁC THỰC KHÁCH HÀNG / GOOGLE
    // =========================================================================
    public function getLienHe()
    {
        return view('frontend.lienhe');
    }

    public function postLienHe(Request $request)
    {
        $request->validate([
            'hoten' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'sodienthoai' => ['nullable', 'string', 'max:20'],
            'tieude' => ['required', 'string', 'max:255'],
            'noidung' => ['required', 'string', 'min:10'],
        ]);

        LienHe::create([
            'hoten' => trim($request->hoten),
            'email' => trim($request->email),
            'sodienthoai' => $request->sodienthoai,
            'tieude' => trim($request->tieude),
            'noidung' => trim($request->noidung),
            'trangthai' => 'chua_xuly',
        ]);

        return redirect()->route('frontend.lienhe')
            ->with('success', 'Cảm ơn bạn đã liên hệ! Nhân viên tư vấn LaptopShop sẽ phản hồi trong thời gian sớm nhất.');
    }

    public function getGioiThieu()
    {
        $tongLaptop = SanPham::where('trangthai', 1)->count();
        $tongHang = HangSanXuat::count();
        return view('frontend.gioithieu', compact('tongLaptop', 'tongHang'));
    }

    public function getDangKy()
    {
        return redirect()->route('register');
    }

    public function getDangNhap()
    {
        return redirect()->route('login');
    }

    public function getGoogleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function getGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->stateless()
                ->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('warning', 'Lỗi xác thực Google. Vui lòng thử lại.');
        }

        $existingUser = User::where('email', $googleUser->email)->first();

        if ($existingUser) {
            Auth::login($existingUser, true);
            return in_array($existingUser->role, ['admin', 'staff'], true)
                ? redirect()->route('admin.home')
                : redirect()->route('frontend.home');
        }

        $newUser = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'username' => Str::before($googleUser->email, '@') . '_' . rand(100, 999),
            'role' => 'user',
            'password' => Hash::make(Str::random(16)),
        ]);

        Auth::login($newUser, true);
        return redirect()->route('frontend.home')
            ->with('success', 'Đăng nhập bằng Google thành công!');
    }
}