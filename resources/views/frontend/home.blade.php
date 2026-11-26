@extends('layouts.frontend')

@section('title', 'Trang chủ - Laptop Chính Hãng')

@section('content')
<!-- Hero Banner Slider -->
<section class="container pt-3 mb-4">
    @if($sliders->count() > 0)
        <div id="heroCarousel" class="carousel slide rounded-4 overflow-hidden shadow-sm" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($sliders as $idx => $sl)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($sliders as $idx => $sl)
                    <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                        @if($sl->hinhanh && file_exists(public_path('storage/' . $sl->hinhanh)))
                            <a href="{{ $sl->duongdan ? url($sl->duongdan) : route('frontend.sanpham') }}">
                                <img src="{{ asset('public/storage/' . $sl->hinhanh) }}" class="d-block w-100" alt="{{ $sl->tieude }}" style="max-height: 400px; object-fit: cover;" />
                            </a>
                        @else
                            <div class="p-5 text-white d-flex align-items-center" style="min-height: 320px; background: linear-gradient(120deg, #0d6efd 0%, #0b2545 100%);">
                                <div class="col-lg-8">
                                    <span class="badge bg-warning text-dark mb-2">Khuyến mãi đặc biệt</span>
                                    <h1 class="display-6 fw-bold mb-3">{{ $sl->tieude }}</h1>
                                    <p class="lead opacity-75 mb-4">Ưu đãi sinh viên giảm ngay đến 500.000đ, tặng balo chống sốc & chuột không dây.</p>
                                    <a href="{{ $sl->duongdan ? url($sl->duongdan) : route('frontend.sanpham') }}" class="btn btn-warning fw-semibold px-4">
                                        Khám phá ngay <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @if($sliders->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    @else
        <div class="p-5 rounded-4 text-white shadow-sm" style="background: linear-gradient(120deg, #0d6efd 0%, #0b2545 100%);">
            <h1 class="display-6 fw-bold">Laptop Chính Hãng — Giá Tốt Cho Sinh Viên & Doanh Nghiệp</h1>
            <p class="lead opacity-75">Đa dạng Gaming, Văn phòng, Đồ họa & MacBook. Hỗ trợ xuất hóa đơn đỏ VAT.</p>
            <a href="{{ route('frontend.sanpham') }}" class="btn btn-warning fw-semibold">Xem tất cả Laptop</a>
        </div>
    @endif
</section>

<!-- Cam kết dịch vụ -->
<section class="container mb-4">
    <div class="row g-3 text-center">
        <div class="col-6 col-md-3">
            <div class="bg-white border rounded-3 p-3 h-100 shadow-sm">
                <i class="fa-solid fa-award text-primary fa-lg mb-1"></i>
                <div class="fw-semibold small">Chính hãng 100%</div>
                <div class="text-muted" style="font-size: 0.78rem;">Nguyên seal, bảo hành hãng</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white border rounded-3 p-3 h-100 shadow-sm">
                <i class="fa-solid fa-file-invoice-dollar text-success fa-lg mb-1"></i>
                <div class="fw-semibold small">Xuất hóa đơn VAT</div>
                <div class="text-muted" style="font-size: 0.78rem;">Lưu Serial Number từng máy</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white border rounded-3 p-3 h-100 shadow-sm">
                <i class="fa-solid fa-rotate text-warning fa-lg mb-1"></i>
                <div class="fw-semibold small">Lỗi 1 đổi 1 (15 ngày)</div>
                <div class="text-muted" style="font-size: 0.78rem;">An tâm sử dụng thực tế</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white border rounded-3 p-3 h-100 shadow-sm">
                <i class="fa-solid fa-screwdriver-wrench text-info fa-lg mb-1"></i>
                <div class="fw-semibold small">Hỗ trợ nâng cấp RAM/SSD</div>
                <div class="text-muted" style="font-size: 0.78rem;">Miễn phí công lắp & cài đặt</div>
            </div>
        </div>
    </div>
</section>

<!-- Dải Thương hiệu Laptop -->
<section class="container mb-5">
    <div class="d-flex flex-wrap gap-2 justify-content-center">
        @foreach($hangsanxuat as $hang)
            <a href="{{ route('frontend.sanpham.hang', ['tenhang_slug' => $hang->tenhang_slug]) }}"
               class="btn bg-white border rounded-pill px-3 py-2 text-dark fw-semibold text-decoration-none brand-pill d-inline-flex align-items-center gap-2">
                @if($hang->hinhanh && file_exists(public_path('storage/' . $hang->hinhanh)))
                    <img src="{{ asset('public/storage/' . $hang->hinhanh) }}" alt="{{ $hang->tenhang }}" style="height: 20px; width: auto; object-fit: contain;" />
                @endif
                <span>{{ $hang->tenhang }}</span>
                <span class="badge bg-light text-secondary border rounded-pill">{{ $hang->san_pham_count }}</span>
            </a>
        @endforeach
    </div>
</section>

<!-- Laptop Nổi bật -->
@if($sanPhamNoiBat->count() > 0)
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h2 class="h4 fw-bold mb-0"><i class="fa-solid fa-fire text-danger me-1"></i> Laptop nổi bật</h2>
        <a href="{{ route('frontend.sanpham') }}" class="text-decoration-none small fw-semibold">
            Xem tất cả <i class="fa-solid fa-chevron-right ms-1"></i>
        </a>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
        @foreach($sanPhamNoiBat as $sp)
            @include('frontend._product_card', ['sp' => $sp])
        @endforeach
    </div>
</section>
@endif

<!-- Laptop theo từng phân khúc -->
@foreach($loaisanpham as $loai)
    @if($loai->SanPham->count() > 0)
        <section class="container mb-5">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h2 class="h4 fw-bold mb-0">{{ $loai->tenloai }}</h2>
                <a href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => $loai->tenloai_slug]) }}" class="text-decoration-none small fw-semibold">
                    Xem thêm {{ $loai->tenloai }} <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
                @foreach($loai->SanPham as $sp)
                    @include('frontend._product_card', ['sp' => $sp, 'loaiSlug' => $loai->tenloai_slug])
                @endforeach
            </div>
        </section>
    @endif
@endforeach

<!-- Tin tức công nghệ mới nhất -->
@if($baiVietMoi->count() > 0)
<section class="container mb-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h2 class="h4 fw-bold mb-0"><i class="fa-solid fa-newspaper text-primary me-1"></i> Tin tức & Tư vấn chọn Laptop</h2>
        <a href="{{ route('frontend.baiviet') }}" class="text-decoration-none small fw-semibold">
            Xem tất cả tin <i class="fa-solid fa-chevron-right ms-1"></i>
        </a>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-3">
        @foreach($baiVietMoi as $bv)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm product-card">
                    @if($bv->hinhanh && file_exists(public_path('storage/' . $bv->hinhanh)))
                        <img src="{{ asset('public/storage/' . $bv->hinhanh) }}" class="card-img-top" alt="{{ $bv->tieude }}" style="height: 180px; object-fit: cover;" />
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 180px;">
                            <i class="fa-solid fa-newspaper fa-3x opacity-25"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="small text-muted mb-1">
                            <span class="badge bg-primary-subtle text-primary">{{ $bv->ChuDe->tenchude ?? 'Tin tức' }}</span>
                            <span class="ms-1">{{ $bv->created_at ? $bv->created_at->format('d/m/Y') : '' }}</span>
                        </div>
                        <h3 class="h6 fw-bold">
                            <a href="{{ route('frontend.baiviet.chitiet', ['tenchude_slug' => $bv->ChuDe->tenchude_slug ?? 'tin-tuc', 'tieude_slug' => $bv->tieude_slug]) }}" class="text-dark text-decoration-none">
                                {{ $bv->tieude }}
                            </a>
                        </h3>
                        <p class="small text-muted mb-0 mt-auto">{{ \Illuminate\Support\Str::limit($bv->tomtat, 100) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
@endsection