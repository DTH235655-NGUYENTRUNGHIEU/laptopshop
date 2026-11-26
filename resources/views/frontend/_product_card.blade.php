@php
    $slugLoai = $loaiSlug ?? ($sp->LoaiSanPham->tenloai_slug ?? 'laptop');
    $urlChiTiet = route('frontend.sanpham.chitiet', [
        'tenloai_slug' => $slugLoai,
        'tensanpham_slug' => $sp->tensanpham_slug,
    ]);
    $phanTramGiam = ($sp->giakhuyenmai && $sp->dongia > 0)
        ? round((1 - $sp->giakhuyenmai / $sp->dongia) * 100)
        : 0;
@endphp

<div class="col">
    <div class="card h-100 border-0 shadow-sm product-card position-relative">
        @if($phanTramGiam > 0)
            <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ $phanTramGiam }}%</span>
        @endif
        @if($sp->soluong <= 0)
            <span class="badge bg-secondary position-absolute top-0 end-0 m-2">Hết hàng</span>
        @endif

        <a href="{{ $urlChiTiet }}" class="text-decoration-none">
            @if($sp->hinhanh && file_exists(public_path('storage/' . $sp->hinhanh)))
                <img src="{{ asset('public/storage/' . $sp->hinhanh) }}" class="card-img-top product-thumb" alt="{{ $sp->tensanpham }}" />
            @else
                <div class="product-thumb d-flex flex-column align-items-center justify-content-center text-muted border-bottom">
                    <i class="fa-solid fa-laptop fa-4x opacity-25 mb-2"></i>
                    <span class="small">{{ $sp->HangSanXuat->tenhang ?? 'Laptop' }}</span>
                </div>
            @endif
        </a>

        <div class="card-body d-flex flex-column p-3">
            <div class="small text-muted mb-1">{{ $sp->HangSanXuat->tenhang ?? '' }} • {{ $sp->masanpham }}</div>
            <h3 class="h6 fw-semibold mb-2" style="min-height: 40px;">
                <a href="{{ $urlChiTiet }}" class="text-dark text-decoration-none">
                    {{ \Illuminate\Support\Str::limit($sp->tensanpham, 60) }}
                </a>
            </h3>

            <!-- Thông số tóm tắt -->
            <div class="d-flex flex-wrap gap-1 mb-3">
                <span class="spec-chip"><i class="fa-solid fa-microchip text-primary"></i> {{ \Illuminate\Support\Str::limit($sp->cpu, 22) }}</span>
                <span class="spec-chip">{{ \Illuminate\Support\Str::limit($sp->ram, 14) }}</span>
                <span class="spec-chip">{{ \Illuminate\Support\Str::limit($sp->ocung, 16) }}</span>
            </div>

            <!-- Giá & Nút mua -->
            <div class="mt-auto d-flex justify-content-between align-items-end">
                <div>
                    @if($sp->giakhuyenmai)
                        <div class="fw-bold text-danger fs-6">{{ number_format($sp->giakhuyenmai, 0, ',', '.') }}đ</div>
                        <div class="small text-muted text-decoration-line-through">{{ number_format($sp->dongia, 0, ',', '.') }}đ</div>
                    @else
                        <div class="fw-bold text-danger fs-6">{{ number_format($sp->dongia, 0, ',', '.') }}đ</div>
                    @endif
                </div>

                @if($sp->soluong > 0)
                    <a href="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->tensanpham_slug]) }}"
                       class="btn btn-primary btn-sm" title="Thêm vào giỏ hàng">
                        <i class="fa-solid fa-cart-plus"></i>
                    </a>
                @else
                    <button class="btn btn-secondary btn-sm" disabled><i class="fa-solid fa-ban"></i></button>
                @endif
            </div>
        </div>
    </div>
</div>