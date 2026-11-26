<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\QuanTriVienController;
use App\Http\Controllers\LoaiSanPhamController;
use App\Http\Controllers\HangSanXuatController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\NhaCungCapController;
use App\Http\Controllers\PhieuNhapController;
use App\Http\Controllers\TinhTrangController;
use App\Http\Controllers\MaGiamGiaController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\ChuDeController;
use App\Http\Controllers\BaiVietController;
use App\Http\Controllers\BinhLuanBaiVietController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\LienHeController;
use App\Http\Controllers\UserController;

// =========================================================================
// 1. XÁC THỰC & ĐĂNG NHẬP GOOGLE
// =========================================================================
Auth::routes();

Route::get('/login/google', [HomeController::class, 'getGoogleLogin'])->name('google.login');
Route::get('/login/google/callback', [HomeController::class, 'getGoogleCallback'])->name('google.callback');

// Giữ lại route /home mặc định của laravel/ui (tự điều hướng theo quyền)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// =========================================================================
// 2. PHÂN HỆ FRONTEND (CÔNG KHAI CHO KHÁCH VÃNG LAI & NGƯỜI MUA)
// =========================================================================
Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'getHome'])->name('home');

    // Sản phẩm & Tìm kiếm (đặt /tim-kiem trước /{tenloai_slug} để tránh trùng tham số)
    Route::get('/san-pham', [HomeController::class, 'getSanPham'])->name('sanpham');
    Route::get('/san-pham/tim-kiem', [HomeController::class, 'getTimKiem'])->name('sanpham.timkiem');
    Route::get('/san-pham/hang/{tenhang_slug}', [HomeController::class, 'getSanPhamTheoHang'])->name('sanpham.hang');
    Route::get('/san-pham/{tenloai_slug}', [HomeController::class, 'getSanPham'])->name('sanpham.phanloai');
    Route::get('/san-pham/{tenloai_slug}/{tensanpham_slug}', [HomeController::class, 'getSanPham_ChiTiet'])->name('sanpham.chitiet');

    // Giỏ hàng
    Route::get('/gio-hang', [HomeController::class, 'getGioHang'])->name('giohang');
    Route::get('/gio-hang/them/{tensanpham_slug}', [HomeController::class, 'getGioHang_Them'])->name('giohang.them');
    Route::get('/gio-hang/xoa/{row_id}', [HomeController::class, 'getGioHang_Xoa'])->name('giohang.xoa');
    Route::get('/gio-hang/giam/{row_id}', [HomeController::class, 'getGioHang_Giam'])->name('giohang.giam');
    Route::get('/gio-hang/tang/{row_id}', [HomeController::class, 'getGioHang_Tang'])->name('giohang.tang');
    Route::post('/gio-hang/cap-nhat', [HomeController::class, 'postGioHang_CapNhat'])->name('giohang.capnhat');
    Route::post('/gio-hang/ma-giam-gia', [HomeController::class, 'postApDungMaGiamGia'])->name('giohang.magiamgia');

    // Tin tức & Bình luận công khai
    Route::get('/bai-viet', [HomeController::class, 'getBaiViet'])->name('baiviet');
    Route::get('/bai-viet/{tenchude_slug}', [HomeController::class, 'getBaiViet'])->name('baiviet.chude');
    Route::get('/bai-viet/{tenchude_slug}/{tieude_slug}', [HomeController::class, 'getBaiViet_ChiTiet'])->name('baiviet.chitiet');

    // Trang thông tin & Liên hệ
    Route::get('/lien-he', [HomeController::class, 'getLienHe'])->name('lienhe');
    Route::post('/lien-he', [HomeController::class, 'postLienHe']);
    Route::get('/gioi-thieu', [HomeController::class, 'getGioiThieu'])->name('gioithieu');
});

// =========================================================================
// 3. PHÂN HỆ KHÁCH HÀNG (YÊU CẦU ĐĂNG NHẬP)
// =========================================================================
Route::get('/khach-hang/dang-ky', [HomeController::class, 'getDangKy'])->name('user.dangky');
Route::get('/khach-hang/dang-nhap', [HomeController::class, 'getDangNhap'])->name('user.dangnhap');

Route::prefix('khach-hang')->name('user.')->middleware('auth')->group(function () {
    Route::get('/', [KhachHangController::class, 'getHome'])->name('home');
    Route::get('/home', [KhachHangController::class, 'getHome']);

    // Đặt hàng
    Route::get('/dat-hang', [KhachHangController::class, 'getDatHang'])->name('dathang');
    Route::post('/dat-hang', [KhachHangController::class, 'postDatHang']);
    Route::get('/dat-hang-thanh-cong', [KhachHangController::class, 'getDatHangThanhCong'])->name('dathangthanhcong');

    // Lịch sử đơn hàng & Hóa đơn
    Route::get('/don-hang', [KhachHangController::class, 'getDonHang'])->name('donhang');
    Route::get('/don-hang/{id}', [KhachHangController::class, 'getDonHang_ChiTiet'])->name('donhang.chitiet');
    Route::post('/don-hang/huy/{id}', [KhachHangController::class, 'postHuyDonHang'])->name('donhang.huy');

    // Đánh giá sản phẩm & Bình luận bài viết
    Route::post('/danh-gia/{sanpham_id}', [KhachHangController::class, 'postDanhGia'])->name('danhgia');
    Route::post('/binh-luan/{baiviet_id}', [KhachHangController::class, 'postBinhLuan'])->name('binhluan');

    // Hồ sơ cá nhân & Đổi mật khẩu
    Route::get('/ho-so', [KhachHangController::class, 'getHoSo'])->name('hoso');
    Route::post('/ho-so', [KhachHangController::class, 'postHoSo']);
    Route::post('/doi-mat-khau', [KhachHangController::class, 'postDoiMatKhau'])->name('doimatkhau');
});

// =========================================================================
// 4. PHÂN HỆ QUẢN TRỊ (ADMIN & STAFF)
// =========================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    // Dashboard tổng quan
    Route::get('/', [QuanTriVienController::class, 'getHome'])->name('home');
    Route::get('/home', [QuanTriVienController::class, 'getHome']);

    // --- Cửa hàng: Loại sản phẩm ---
    Route::get('/loaisanpham', [LoaiSanPhamController::class, 'getDanhSach'])->name('loaisanpham');
    Route::get('/loaisanpham/them', [LoaiSanPhamController::class, 'getThem'])->name('loaisanpham.them');
    Route::post('/loaisanpham/them', [LoaiSanPhamController::class, 'postThem']);
    Route::get('/loaisanpham/sua/{id}', [LoaiSanPhamController::class, 'getSua'])->name('loaisanpham.sua');
    Route::post('/loaisanpham/sua/{id}', [LoaiSanPhamController::class, 'postSua']);
    Route::get('/loaisanpham/xoa/{id}', [LoaiSanPhamController::class, 'getXoa'])->name('loaisanpham.xoa');

    // --- Cửa hàng: Hãng sản xuất (kèm Excel) ---
    Route::get('/hangsanxuat', [HangSanXuatController::class, 'getDanhSach'])->name('hangsanxuat');
    Route::get('/hangsanxuat/them', [HangSanXuatController::class, 'getThem'])->name('hangsanxuat.them');
    Route::post('/hangsanxuat/them', [HangSanXuatController::class, 'postThem']);
    Route::get('/hangsanxuat/sua/{id}', [HangSanXuatController::class, 'getSua'])->name('hangsanxuat.sua');
    Route::post('/hangsanxuat/sua/{id}', [HangSanXuatController::class, 'postSua']);
    Route::get('/hangsanxuat/xoa/{id}', [HangSanXuatController::class, 'getXoa'])->name('hangsanxuat.xoa');
    Route::post('/hangsanxuat/nhap', [HangSanXuatController::class, 'postNhap'])->name('hangsanxuat.nhap');
    Route::get('/hangsanxuat/xuat', [HangSanXuatController::class, 'getXuat'])->name('hangsanxuat.xuat');

    // --- Cửa hàng: Sản phẩm (kèm Excel & ảnh phụ) ---
    Route::get('/sanpham', [SanPhamController::class, 'getDanhSach'])->name('sanpham');
    Route::get('/sanpham/them', [SanPhamController::class, 'getThem'])->name('sanpham.them');
    Route::post('/sanpham/them', [SanPhamController::class, 'postThem']);
    Route::get('/sanpham/sua/{id}', [SanPhamController::class, 'getSua'])->name('sanpham.sua');
    Route::post('/sanpham/sua/{id}', [SanPhamController::class, 'postSua']);
    Route::get('/sanpham/xoa/{id}', [SanPhamController::class, 'getXoa'])->name('sanpham.xoa');
    Route::get('/sanpham/xoa-anh-phu/{id}', [SanPhamController::class, 'getXoaAnhPhu'])->name('sanpham.xoaanhphu');
    Route::post('/sanpham/nhap', [SanPhamController::class, 'postNhap'])->name('sanpham.nhap');
    Route::get('/sanpham/xuat', [SanPhamController::class, 'getXuat'])->name('sanpham.xuat');

    // --- Cửa hàng: Tình trạng đơn hàng ---
    Route::get('/tinhtrang', [TinhTrangController::class, 'getDanhSach'])->name('tinhtrang');
    Route::get('/tinhtrang/them', [TinhTrangController::class, 'getThem'])->name('tinhtrang.them');
    Route::post('/tinhtrang/them', [TinhTrangController::class, 'postThem']);
    Route::get('/tinhtrang/sua/{id}', [TinhTrangController::class, 'getSua'])->name('tinhtrang.sua');
    Route::post('/tinhtrang/sua/{id}', [TinhTrangController::class, 'postSua']);
    Route::get('/tinhtrang/xoa/{id}', [TinhTrangController::class, 'getXoa'])->name('tinhtrang.xoa');

    // --- Cửa hàng: Mã giảm giá ---
    Route::get('/magiamgia', [MaGiamGiaController::class, 'getDanhSach'])->name('magiamgia');
    Route::get('/magiamgia/them', [MaGiamGiaController::class, 'getThem'])->name('magiamgia.them');
    Route::post('/magiamgia/them', [MaGiamGiaController::class, 'postThem']);
    Route::get('/magiamgia/sua/{id}', [MaGiamGiaController::class, 'getSua'])->name('magiamgia.sua');
    Route::post('/magiamgia/sua/{id}', [MaGiamGiaController::class, 'postSua']);
    Route::get('/magiamgia/xoa/{id}', [MaGiamGiaController::class, 'getXoa'])->name('magiamgia.xoa');
    Route::get('/magiamgia/kichhoat/{id}', [MaGiamGiaController::class, 'getKichHoat'])->name('magiamgia.kichhoat');

    // --- Bán hàng: Đơn hàng ---
    Route::get('/donhang', [DonHangController::class, 'getDanhSach'])->name('donhang');
    Route::get('/donhang/sua/{id}', [DonHangController::class, 'getSua'])->name('donhang.sua');
    Route::post('/donhang/sua/{id}', [DonHangController::class, 'postSua']);
    Route::get('/donhang/xoa/{id}', [DonHangController::class, 'getXoa'])->name('donhang.xoa');

    // --- Bán hàng: Hóa đơn bán hàng (xuất từ đơn hàng + in hóa đơn) ---
    Route::get('/hoadon', [HoaDonController::class, 'getDanhSach'])->name('hoadon');
    Route::get('/hoadon/lap/{donhang_id}', [HoaDonController::class, 'getLapHoaDon'])->name('hoadon.lap');
    Route::post('/hoadon/lap/{donhang_id}', [HoaDonController::class, 'postLapHoaDon']);
    Route::get('/hoadon/chitiet/{id}', [HoaDonController::class, 'getChiTiet'])->name('hoadon.chitiet');
    Route::get('/hoadon/in/{id}', [HoaDonController::class, 'getInHoaDon'])->name('hoadon.in');
    Route::post('/hoadon/huy/{id}', [HoaDonController::class, 'postHuy'])->name('hoadon.huy');

    // --- Kho hàng: Nhà cung cấp ---
    Route::get('/nhacungcap', [NhaCungCapController::class, 'getDanhSach'])->name('nhacungcap');
    Route::get('/nhacungcap/them', [NhaCungCapController::class, 'getThem'])->name('nhacungcap.them');
    Route::post('/nhacungcap/them', [NhaCungCapController::class, 'postThem']);
    Route::get('/nhacungcap/sua/{id}', [NhaCungCapController::class, 'getSua'])->name('nhacungcap.sua');
    Route::post('/nhacungcap/sua/{id}', [NhaCungCapController::class, 'postSua']);
    Route::get('/nhacungcap/xoa/{id}', [NhaCungCapController::class, 'getXoa'])->name('nhacungcap.xoa');

    // --- Kho hàng: Phiếu nhập kho ---
    Route::get('/phieunhap', [PhieuNhapController::class, 'getDanhSach'])->name('phieunhap');
    Route::get('/phieunhap/them', [PhieuNhapController::class, 'getThem'])->name('phieunhap.them');
    Route::post('/phieunhap/them', [PhieuNhapController::class, 'postThem']);
    Route::get('/phieunhap/chitiet/{id}', [PhieuNhapController::class, 'getChiTiet'])->name('phieunhap.chitiet');
    Route::get('/phieunhap/xoa/{id}', [PhieuNhapController::class, 'getXoa'])->name('phieunhap.xoa');

    // --- Nội dung: Chủ đề bài viết ---
    Route::get('/chude', [ChuDeController::class, 'getDanhSach'])->name('chude');
    Route::get('/chude/them', [ChuDeController::class, 'getThem'])->name('chude.them');
    Route::post('/chude/them', [ChuDeController::class, 'postThem']);
    Route::get('/chude/sua/{id}', [ChuDeController::class, 'getSua'])->name('chude.sua');
    Route::post('/chude/sua/{id}', [ChuDeController::class, 'postSua']);
    Route::get('/chude/xoa/{id}', [ChuDeController::class, 'getXoa'])->name('chude.xoa');

    // --- Nội dung: Bài viết ---
    Route::get('/baiviet', [BaiVietController::class, 'getDanhSach'])->name('baiviet');
    Route::get('/baiviet/them', [BaiVietController::class, 'getThem'])->name('baiviet.them');
    Route::post('/baiviet/them', [BaiVietController::class, 'postThem']);
    Route::get('/baiviet/sua/{id}', [BaiVietController::class, 'getSua'])->name('baiviet.sua');
    Route::post('/baiviet/sua/{id}', [BaiVietController::class, 'postSua']);
    Route::get('/baiviet/xoa/{id}', [BaiVietController::class, 'getXoa'])->name('baiviet.xoa');
    Route::get('/baiviet/kiemduyet/{id}', [BaiVietController::class, 'getKiemDuyet'])->name('baiviet.kiemduyet');
    Route::get('/baiviet/kichhoat/{id}', [BaiVietController::class, 'getKichHoat'])->name('baiviet.kichhoat');

    // --- Nội dung: Bình luận bài viết ---
    Route::get('/binhluanbaiviet', [BinhLuanBaiVietController::class, 'getDanhSach'])->name('binhluanbaiviet');
    Route::get('/binhluanbaiviet/sua/{id}', [BinhLuanBaiVietController::class, 'getSua'])->name('binhluanbaiviet.sua');
    Route::post('/binhluanbaiviet/sua/{id}', [BinhLuanBaiVietController::class, 'postSua']);
    Route::get('/binhluanbaiviet/xoa/{id}', [BinhLuanBaiVietController::class, 'getXoa'])->name('binhluanbaiviet.xoa');
    Route::get('/binhluanbaiviet/kiemduyet/{id}', [BinhLuanBaiVietController::class, 'getKiemDuyet'])->name('binhluanbaiviet.kiemduyet');
    Route::get('/binhluanbaiviet/kichhoat/{id}', [BinhLuanBaiVietController::class, 'getKichHoat'])->name('binhluanbaiviet.kichhoat');

    // --- Nội dung: Đánh giá sản phẩm ---
    Route::get('/danhgia', [DanhGiaController::class, 'getDanhSach'])->name('danhgia');
    Route::get('/danhgia/kiemduyet/{id}', [DanhGiaController::class, 'getKiemDuyet'])->name('danhgia.kiemduyet');
    Route::get('/danhgia/xoa/{id}', [DanhGiaController::class, 'getXoa'])->name('danhgia.xoa');

    // --- Giao diện & Hỗ trợ: Banner & Liên hệ ---
    Route::get('/banner', [BannerController::class, 'getDanhSach'])->name('banner');
    Route::get('/banner/them', [BannerController::class, 'getThem'])->name('banner.them');
    Route::post('/banner/them', [BannerController::class, 'postThem']);
    Route::get('/banner/sua/{id}', [BannerController::class, 'getSua'])->name('banner.sua');
    Route::post('/banner/sua/{id}', [BannerController::class, 'postSua']);
    Route::get('/banner/xoa/{id}', [BannerController::class, 'getXoa'])->name('banner.xoa');
    Route::get('/banner/kichhoat/{id}', [BannerController::class, 'getKichHoat'])->name('banner.kichhoat');

    Route::get('/lienhe', [LienHeController::class, 'getDanhSach'])->name('lienhe');
    Route::get('/lienhe/sua/{id}', [LienHeController::class, 'getSua'])->name('lienhe.sua');
    Route::post('/lienhe/sua/{id}', [LienHeController::class, 'postSua']);
    Route::get('/lienhe/xoa/{id}', [LienHeController::class, 'getXoa'])->name('lienhe.xoa');

    // --- Hệ thống: Quản lý tài khoản (CHỈ ADMIN ĐƯỢC PHÉP) ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/nguoidung', [UserController::class, 'getDanhSach'])->name('nguoidung');
        Route::get('/nguoidung/them', [UserController::class, 'getThem'])->name('nguoidung.them');
        Route::post('/nguoidung/them', [UserController::class, 'postThem']);
        Route::get('/nguoidung/sua/{id}', [UserController::class, 'getSua'])->name('nguoidung.sua');
        Route::post('/nguoidung/sua/{id}', [UserController::class, 'postSua']);
        Route::get('/nguoidung/xoa/{id}', [UserController::class, 'getXoa'])->name('nguoidung.xoa');
    });
});