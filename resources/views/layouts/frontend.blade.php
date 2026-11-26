<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Trang chủ') - {{ config('app.name', 'LaptopShop') }}</title>

    <!-- Bootstrap 5.3 & FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .product-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.1) !important; }
        .product-thumb { height: 200px; object-fit: contain; background: #fff; padding: 12px; }
        .spec-chip { font-size: 0.75rem; background: #f1f3f5; border-radius: 4px; padding: 2px 6px; display: inline-block; }
        .brand-pill { transition: all 0.2s ease; }
        .brand-pill:hover { border-color: #0d6efd !important; color: #0d6efd !important; background: #eef5ff !important; }
        .footer-link { color: #adb5bd; text-decoration: none; }
        .footer-link:hover { color: #fff; }
    </style>
    @yield('css')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Top bar -->
    <div class="bg-dark text-light py-1 small d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="fa-solid fa-phone-volume text-warning me-1"></i> Hotline tư vấn: <strong>0909 123 456</strong>
                <span class="mx-2 text-secondary">|</span>
                <i class="fa-solid fa-shield-halved text-info me-1"></i> Cam kết 100% Laptop chính hãng, xuất hóa đơn VAT
            </div>
            <div>
                <i class="fa-solid fa-truck-fast text-success me-1"></i> Miễn phí giao hàng toàn quốc
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <header class="sticky-top bg-white border-bottom shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light py-2">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary fs-4 d-flex align-items-center gap-2" href="{{ route('frontend.home') }}">
                    <i class="fa-solid fa-laptop-code"></i>
                    <span>{{ config('app.name', 'LaptopShop') }}</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#frontendNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="frontendNavbar">
                    <!-- Thanh tìm kiếm -->
                    <form action="{{ route('frontend.sanpham.timkiem') }}" method="get" class="d-flex mx-lg-4 my-2 my-lg-0 flex-grow-1" style="max-width: 440px;">
                        <div class="input-group">
                            <input type="search" name="tukhoa" class="form-control form-control-sm" value="{{ request('tukhoa') }}" placeholder="Tìm tên laptop, hãng, CPU, RTX 4060..." />
                            <button class="btn btn-primary btn-sm px-3" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Menu điều hướng -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-medium">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-laptop me-1"></i> Laptop
                            </a>
                            <ul class="dropdown-menu shadow-sm">
                                <li>
                                    <a class="dropdown-item fw-semibold" href="{{ route('frontend.sanpham') }}">
                                        Tất cả Laptop
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><h6 class="dropdown-header">Theo nhu cầu</h6></li>
                                @foreach($navLoaiSanPham as $loai)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => $loai->tenloai_slug]) }}">
                                            {{ $loai->tenloai }}
                                        </a>
                                    </li>
                                @endforeach
                                <li><hr class="dropdown-divider" /></li>
                                <li><h6 class="dropdown-header">Theo thương hiệu</h6></li>
                                @foreach($navHangSanXuat as $hang)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('frontend.sanpham.hang', ['tenhang_slug' => $hang->tenhang_slug]) }}">
                                            Laptop {{ $hang->tenhang }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Tin tức
                            </a>
                            <ul class="dropdown-menu shadow-sm">
                                <li><a class="dropdown-item fw-semibold" href="{{ route('frontend.baiviet') }}">Tất cả tin tức</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                @foreach($navChuDe as $cd)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('frontend.baiviet.chude', ['tenchude_slug' => $cd->tenchude_slug]) }}">
                                            {{ $cd->tenchude }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.gioithieu') }}">Giới thiệu</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.lienhe') }}">Liên hệ</a>
                        </li>
                    </ul>

                    <!-- Giỏ hàng & Tài khoản -->
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('frontend.giohang') }}" class="btn btn-outline-primary btn-sm position-relative">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="d-none d-xl-inline ms-1">Giỏ hàng</span>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ \Gloudemans\Shoppingcart\Facades\Cart::count() }}
                            </span>
                        </a>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng nhập
                            </a>
                        @else
                            <div class="dropdown">
                                <button class="btn btn-light border btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-circle text-primary me-1"></i>
                                    {{ \Illuminate\Support\Str::limit(Auth::user()->name, 16) }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    @if(in_array(Auth::user()->role, ['admin', 'staff'], true))
                                        <li>
                                            <a class="dropdown-item text-primary fw-semibold" href="{{ route('admin.home') }}">
                                                <i class="fa-solid fa-gauge-high fa-fw me-1"></i> Trang Quản trị
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider" /></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.home') }}">
                                            <i class="fa-solid fa-id-card fa-fw me-1"></i> Tài khoản của tôi
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.donhang') }}">
                                            <i class="fa-solid fa-box-open fa-fw me-1"></i> Lịch sử đơn hàng
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('frontend-logout-form').submit();">
                                            <i class="fa-solid fa-right-from-bracket fa-fw me-1"></i> Đăng xuất
                                        </a>
                                        <form id="frontend-logout-form" action="{{ route('logout') }}" method="post" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Thông báo Flash -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                <i class="fa-solid fa-circle-xmark me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Nội dung chính -->
    <main class="flex-grow-1 pb-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light pt-5 pb-3 mt-auto">
        <div class="container">
            <div class="row g-4 pb-4 border-bottom border-secondary">
                <div class="col-lg-4">
                    <h5 class="fw-bold text-white mb-3">
                        <i class="fa-solid fa-laptop-code text-primary me-1"></i> {{ config('app.name', 'LaptopShop') }}
                    </h5>
                    <p class="small text-secondary mb-2">
                        Hệ thống chuyên cung cấp Laptop Gaming, Văn phòng, Đồ họa & MacBook chính hãng. Đầy đủ chứng từ VAT và bảo hành theo Serial Number.
                    </p>
                    <div class="small text-secondary">
                        <div><i class="fa-solid fa-location-dot me-2"></i>18 Ung Văn Khiêm, TP. Long Xuyên, An Giang</div>
                        <div><i class="fa-solid fa-phone me-2"></i>0909 123 456 | contact@laptopshop.vn</div>
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="fw-semibold text-white mb-3">Phân khúc</h6>
                    <ul class="list-unstyled small d-grid gap-2">
                        @foreach($navLoaiSanPham->take(5) as $loai)
                            <li><a class="footer-link" href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => $loai->tenloai_slug]) }}">{{ $loai->tenloai }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-6 col-lg-3">
                    <h6 class="fw-semibold text-white mb-3">Thương hiệu nổi bật</h6>
                    <ul class="list-unstyled small row row-cols-2 g-2">
                        @foreach($navHangSanXuat->take(8) as $hang)
                            <li class="col"><a class="footer-link" href="{{ route('frontend.sanpham.hang', ['tenhang_slug' => $hang->tenhang_slug]) }}">{{ $hang->tenhang }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6 class="fw-semibold text-white mb-3">Hỗ trợ khách hàng</h6>
                    <ul class="list-unstyled small d-grid gap-2">
                        <li><a class="footer-link" href="{{ route('frontend.gioithieu') }}">Giới thiệu cửa hàng</a></li>
                        <li><a class="footer-link" href="{{ route('frontend.baiviet') }}">Tin tức & Thủ thuật Laptop</a></li>
                        <li><a class="footer-link" href="{{ route('frontend.lienhe') }}">Liên hệ tư vấn cấu hình</a></li>
                        <li><a class="footer-link" href="{{ route('frontend.giohang') }}">Kiểm tra giỏ hàng</a></li>
                    </ul>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 small text-secondary">
                <div>Bản quyền © {{ date('Y') }} <strong>{{ config('app.name', 'LaptopShop') }}</strong>. Đồ án Công nghệ Web PHP.</div>
                <div>Laravel 12 • Bootstrap 5 • MySQL</div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @yield('javascript')
</body>
</html>