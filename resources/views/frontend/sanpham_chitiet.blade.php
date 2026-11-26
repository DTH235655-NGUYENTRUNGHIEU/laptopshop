@extends('layouts.frontend')

@section('title', $sanpham->tensanpham)

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => $sanpham->LoaiSanPham->tenloai_slug ?? 'laptop']) }}">
                    {{ $sanpham->LoaiSanPham->tenloai ?? 'Laptop' }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $sanpham->tensanpham }}</li>
        </ol>
    </nav>

    <!-- Khối chính: Ảnh + Thông tin mua hàng -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- Gallery hình ảnh -->
                <div class="col-lg-5">
                    <div class="border rounded-3 bg-white d-flex align-items-center justify-content-center p-3 mb-2" style="height: 340px;">
                        @if($sanpham->hinhanh && file_exists(public_path('storage/' . $sanpham->hinhanh)))
                            <img id="mainProductImg" src="{{ asset('public/storage/' . $sanpham->hinhanh) }}" alt="{{ $sanpham->tensanpham }}" class="img-fluid" style="max-height: 310px; object-fit: contain;" />
                        @else
                            <div class="text-center text-muted">
                                <i class="fa-solid fa-laptop fa-6x opacity-25 mb-2"></i>
                                <div>{{ $sanpham->tensanpham }}</div>
                            </div>
                        @endif
                    </div>

                    @if($sanpham->SanPhamHinhAnh->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @if($sanpham->hinhanh && file_exists(public_path('storage/' . $sanpham->hinhanh)))
                                <img src="{{ asset('public/storage/' . $sanpham->hinhanh) }}"
                                     class="img-thumbnail thumb-switch"
                                     style="width: 72px; height: 56px; object-fit: cover; cursor: pointer;"
                                     alt="Ảnh chính" />
                            @endif
                            @foreach($sanpham->SanPhamHinhAnh as $anh)
                                @if(file_exists(public_path('storage/' . $anh->hinhanh)))
                                    <img src="{{ asset('public/storage/' . $anh->hinhanh) }}"
                                         class="img-thumbnail thumb-switch"
                                         style="width: 72px; height: 56px; object-fit: cover; cursor: pointer;"
                                         alt="Ảnh phụ" />
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Thông tin chi tiết & nút mua -->
                <div class="col-lg-7">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary">{{ $sanpham->HangSanXuat->tenhang ?? '' }}</span>
                        <span class="badge bg-light text-dark border">SKU: {{ $sanpham->masanpham }}</span>
                        <span class="badge {{ $sanpham->soluong > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $sanpham->soluong > 0 ? 'Còn hàng (' . $sanpham->soluong . ' máy)' : 'Tạm hết hàng' }}
                        </span>
                    </div>

                    <h1 class="h3 fw-bold mb-2">{{ $sanpham->tensanpham }}</h1>

                    <div class="mb-3 small">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-{{ $i <= round($diemTrungBinh) ? 'solid text-warning' : 'regular text-muted' }} fa-star"></i>
                        @endfor
                        <span class="ms-1 text-muted">
                            {{ $diemTrungBinh > 0 ? $diemTrungBinh . '/5' : 'Chưa có đánh giá' }}
                            ({{ $sanpham->DanhGia->count() }} nhận xét)
                        </span>
                    </div>

                    <div class="bg-light rounded-3 p-3 mb-3">
                        @if($sanpham->giakhuyenmai)
                            <div class="d-flex align-items-baseline gap-3">
                                <span class="fs-2 fw-bold text-danger">{{ number_format($sanpham->giakhuyenmai, 0, ',', '.') }}đ</span>
                                <span class="fs-5 text-muted text-decoration-line-through">{{ number_format($sanpham->dongia, 0, ',', '.') }}đ</span>
                                <span class="badge bg-danger">Tiết kiệm {{ number_format($sanpham->dongia - $sanpham->giakhuyenmai, 0, ',', '.') }}đ</span>
                            </div>
                        @else
                            <div class="fs-2 fw-bold text-danger">{{ number_format($sanpham->dongia, 0, ',', '.') }}đ</div>
                        @endif
                        <div class="small text-muted mt-1">
                            <i class="fa-solid fa-shield-halved text-success me-1"></i> Bảo hành chính hãng <strong>{{ $sanpham->baohanh }} tháng</strong> theo Serial Number
                        </div>
                    </div>

                    <!-- Tóm tắt cấu hình nổi bật -->
                    <ul class="list-unstyled small row row-cols-1 row-cols-md-2 g-2 mb-4">
                        <li class="col"><i class="fa-solid fa-microchip text-primary fa-fw"></i> <strong>CPU:</strong> {{ $sanpham->cpu }}</li>
                        <li class="col"><i class="fa-solid fa-memory text-primary fa-fw"></i> <strong>RAM:</strong> {{ $sanpham->ram }}</li>
                        <li class="col"><i class="fa-solid fa-hard-drive text-primary fa-fw"></i> <strong>Ổ cứng:</strong> {{ $sanpham->ocung }}</li>
                        <li class="col"><i class="fa-solid fa-display text-primary fa-fw"></i> <strong>VGA:</strong> {{ $sanpham->carddohoa }}</li>
                        <li class="col-12"><i class="fa-solid fa-tv text-primary fa-fw"></i> <strong>Màn hình:</strong> {{ $sanpham->manhinh }}</li>
                    </ul>

                    @if($sanpham->soluong > 0)
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sanpham->tensanpham_slug, 'muangay' => 1]) }}" class="btn btn-danger btn-lg px-4 fw-semibold">
                                <i class="fa-solid fa-bolt me-1"></i> Mua ngay
                            </a>
                            <a href="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sanpham->tensanpham_slug]) }}" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fa-solid fa-cart-plus me-1"></i> Thêm vào giỏ hàng
                            </a>
                        </div>
                    @else
                        <button class="btn btn-secondary btn-lg px-4" disabled>Sản phẩm tạm hết hàng</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mô tả chi tiết & Bảng 10 thông số kỹ thuật -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Đặc điểm nổi bật & Mô tả sản phẩm</div>
                <div class="card-body">
                    @if($sanpham->motasanpham)
                        {!! $sanpham->motasanpham !!}
                    @else
                        <p class="text-muted mb-0">Thông tin bài viết đánh giá chi tiết cho mẫu {{ $sanpham->tensanpham }} đang được cập nhật.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Thông số kỹ thuật chi tiết</div>
                <div class="card-body p-0">
                    <table class="table table-striped table-sm mb-0 small">
                        <tbody>
                            <tr><th width="36%" class="ps-3">Vi xử lý (CPU)</th><td class="pe-3">{{ $sanpham->cpu }}</td></tr>
                            <tr><th class="ps-3">Bộ nhớ RAM</th><td class="pe-3">{{ $sanpham->ram }}</td></tr>
                            <tr><th class="ps-3">Ổ cứng</th><td class="pe-3">{{ $sanpham->ocung }}</td></tr>
                            <tr><th class="ps-3">Card đồ họa</th><td class="pe-3">{{ $sanpham->carddohoa }}</td></tr>
                            <tr><th class="ps-3">Màn hình</th><td class="pe-3">{{ $sanpham->manhinh }}</td></tr>
                            <tr><th class="ps-3">Hệ điều hành</th><td class="pe-3">{{ $sanpham->hedieuhanh ?: 'Đang cập nhật' }}</td></tr>
                            <tr><th class="ps-3">Dung lượng pin</th><td class="pe-3">{{ $sanpham->pin ?: 'Đang cập nhật' }}</td></tr>
                            <tr><th class="ps-3">Trọng lượng</th><td class="pe-3">{{ $sanpham->trongluong ? $sanpham->trongluong . ' kg' : 'Đang cập nhật' }}</td></tr>
                            <tr><th class="ps-3">Màu sắc</th><td class="pe-3">{{ $sanpham->mausac ?: 'Đang cập nhật' }}</td></tr>
                            <tr><th class="ps-3">Bảo hành</th><td class="pe-3">{{ $sanpham->baohanh }} tháng chính hãng</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Đánh giá của khách hàng -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white fw-bold">
            <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá & Nhận xét ({{ $sanpham->DanhGia->count() }})
        </div>
        <div class="card-body">
            @auth
                <form action="{{ route('user.danhgia', ['sanpham_id' => $sanpham->id]) }}" method="post" class="border rounded-3 p-3 bg-light mb-4">
                    @csrf
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-auto fw-semibold small">Đánh giá của bạn:</div>
                        <div class="col-auto">
                            <select name="sosao" class="form-select form-select-sm" required>
                                <option value="5">5 sao — Rất hài lòng</option>
                                <option value="4">4 sao — Hài lòng</option>
                                <option value="3">3 sao — Bình thường</option>
                                <option value="2">2 sao — Chưa tốt</option>
                                <option value="1">1 sao — Rất tệ</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <textarea name="noidung" rows="2" class="form-control form-control-sm" placeholder="Chia sẻ trải nghiệm thực tế của bạn về mẫu laptop này..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-paper-plane me-1"></i> Gửi đánh giá
                    </button>
                </form>
            @else
                <div class="alert alert-light border small mb-4">
                    Vui lòng <a href="{{ route('login') }}" class="fw-semibold">đăng nhập</a> để gửi đánh giá cho sản phẩm này.
                </div>
            @endauth

            @forelse($sanpham->DanhGia as $dg)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="me-2">{{ $dg->User->name ?? 'Khách hàng' }}</strong>
                            @for($s = 1; $s <= 5; $s++)
                                <i class="fa-{{ $s <= $dg->sosao ? 'solid text-warning' : 'regular text-muted' }} fa-star small"></i>
                            @endfor
                        </div>
                        <span class="small text-muted">{{ $dg->created_at ? $dg->created_at->format('d/m/Y H:i') : '' }}</span>
                    </div>
                    <p class="mb-0 mt-1 small">{{ $dg->noidung }}</p>
                </div>
            @empty
                <p class="text-muted small mb-0">Chưa có đánh giá nào cho sản phẩm này.</p>
            @endforelse
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    @if($sanPhamLienQuan->count() > 0)
        <section class="mb-4">
            <h2 class="h5 fw-bold mb-3">Laptop tương tự có thể bạn quan tâm</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
                @foreach($sanPhamLienQuan as $sp)
                    @include('frontend._product_card', ['sp' => $sp])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@section('javascript')
<script>
    const mainImg = document.getElementById('mainProductImg');
    document.querySelectorAll('.thumb-switch').forEach((img) => {
        img.addEventListener('click', function () {
            if (mainImg) {
                mainImg.src = this.src;
            }
        });
    });
</script>
@endsection