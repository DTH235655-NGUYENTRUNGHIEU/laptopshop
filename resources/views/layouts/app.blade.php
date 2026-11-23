<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Trang quản trị') - {{ config('app.name', 'LaptopShop') }}</title>

    <!-- Bootstrap 5 & Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @yield('css')
</head>
<body class="bg-light">
    <div id="app" class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="{{ Route::has('admin.home') ? route('admin.home') : url('/') }}">
                    <i class="fa-solid fa-laptop text-warning me-1"></i> {{ config('app.name', 'LaptopShop') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar (Chỉ hiển thị menu quản trị khi đã đăng nhập) -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                            <!-- Nhóm 1: Sản phẩm & Kho -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-fw fa-boxes-stacked"></i> Sản phẩm & Kho
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.loaisanpham') ? route('admin.loaisanpham') : '#' }}"><i class="fa-solid fa-fw fa-diagram-project"></i> Loại sản phẩm</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.hangsanxuat') ? route('admin.hangsanxuat') : '#' }}"><i class="fa-solid fa-fw fa-copyright"></i> Hãng sản xuất</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.sanpham') ? route('admin.sanpham') : '#' }}"><i class="fa-solid fa-fw fa-laptop"></i> Danh sách Laptop</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.nhacungcap') ? route('admin.nhacungcap') : '#' }}"><i class="fa-solid fa-fw fa-truck-field"></i> Nhà cung cấp</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.phieunhap') ? route('admin.phieunhap') : '#' }}"><i class="fa-solid fa-fw fa-file-import"></i> Phiếu nhập kho</a></li>
                                </ul>
                            </li>

                            <!-- Nhóm 2: Bán hàng & Hóa đơn -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-fw fa-cart-shopping"></i> Bán hàng
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.tinhtrang') ? route('admin.tinhtrang') : '#' }}"><i class="fa-solid fa-fw fa-list-check"></i> Tình trạng đơn</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.donhang') ? route('admin.donhang') : '#' }}"><i class="fa-solid fa-fw fa-receipt"></i> Đơn hàng</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.hoadon') ? route('admin.hoadon') : '#' }}"><i class="fa-solid fa-fw fa-file-invoice-dollar"></i> Hóa đơn & Bảo hành</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.magiamgia') ? route('admin.magiamgia') : '#' }}"><i class="fa-solid fa-fw fa-ticket"></i> Mã giảm giá</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.danhgia') ? route('admin.danhgia') : '#' }}"><i class="fa-solid fa-fw fa-star"></i> Đánh giá sản phẩm</a></li>
                                </ul>
                            </li>

                            <!-- Nhóm 3: Nội dung & CSKH -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-fw fa-newspaper"></i> Nội dung & CSKH
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.chude') ? route('admin.chude') : '#' }}"><i class="fa-solid fa-fw fa-folder-tree"></i> Chủ đề tin tức</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.baiviet') ? route('admin.baiviet') : '#' }}"><i class="fa-solid fa-fw fa-pen-to-square"></i> Bài viết</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.binhluanbaiviet') ? route('admin.binhluanbaiviet') : '#' }}"><i class="fa-solid fa-fw fa-comments"></i> Bình luận bài viết</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.banner') ? route('admin.banner') : '#' }}"><i class="fa-solid fa-fw fa-images"></i> Banner / Slider</a></li>
                                    <li><a class="dropdown-item" href="{{ Route::has('admin.lienhe') ? route('admin.lienhe') : '#' }}"><i class="fa-solid fa-fw fa-envelope"></i> Liên hệ & Tư vấn</a></li>
                                </ul>
                            </li>

                            <!-- Nhóm 4: Tài khoản -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ Route::has('admin.nguoidung') ? route('admin.nguoidung') : '#' }}">
                                    <i class="fa-solid fa-fw fa-users"></i> Tài khoản
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}" target="_blank">
                                <i class="fa-solid fa-fw fa-globe"></i> Xem trang web
                            </a>
                        </li>

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fa-solid fa-fw fa-right-to-bracket"></i> Đăng nhập
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fa-solid fa-fw fa-user-plus"></i> Đăng ký
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-fw fa-circle-user"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-fw fa-right-from-bracket"></i> Đăng xuất
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Nội dung chính -->
        <main class="container-fluid py-4">
            {{-- Hiển thị thông báo Flash Session (Thành công / Lỗi / Cảnh báo) --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-xmark me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <div class="container-fluid">
            <hr class="shadow-sm" />
            <footer class="text-center text-muted pb-3 small">
                Bản quyền © {{ date('Y') }} bởi <strong>{{ config('app.name', 'LaptopShop') }}</strong>.
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @yield('javascript')
</body>
</html>