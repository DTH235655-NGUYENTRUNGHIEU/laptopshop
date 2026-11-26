@extends('layouts.frontend')

@section('title', 'Giỏ hàng của bạn')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.sanpham') }}">Laptop</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <h1 class="h4 fw-bold mb-4">
        <i class="fa-solid fa-cart-shopping text-primary me-1"></i>
        Giỏ hàng của bạn ({{ \Gloudemans\Shoppingcart\Facades\Cart::count() }} sản phẩm)
    </h1>

    <div class="row g-4">
        <!-- Danh sách sản phẩm trong giỏ -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <form action="{{ route('frontend.giohang.capnhat') }}" method="post">
                        @csrf
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th class="ps-3" width="44%">Sản phẩm</th>
                                        <th class="text-end" width="16%">Đơn giá</th>
                                        <th class="text-center" width="18%">Số lượng</th>
                                        <th class="text-end" width="16%">Thành tiền</th>
                                        <th class="text-center pe-3" width="6%">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\Gloudemans\Shoppingcart\Facades\Cart::content() as $value)
                                        @php
                                            $imgPath = $value->options->image ?? null;
                                            $loaiSlug = $value->options->loai_slug ?? 'laptop';
                                            $spSlug = $value->options->slug ?? '';
                                            $sku = $value->options->masanpham ?? '';
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="border rounded bg-white p-1 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 68px; height: 54px;">
                                                        @if($imgPath && file_exists(public_path('storage/' . $imgPath)))
                                                            <img src="{{ asset('public/storage/' . $imgPath) }}" alt="{{ $value->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
                                                        @else
                                                            <i class="fa-solid fa-laptop text-muted fa-lg"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if($spSlug)
                                                            <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $loaiSlug, 'tensanpham_slug' => $spSlug]) }}" class="fw-semibold text-dark text-decoration-none small">
                                                                {{ $value->name }}
                                                            </a>
                                                        @else
                                                            <div class="fw-semibold small">{{ $value->name }}</div>
                                                        @endif
                                                        @if($sku)
                                                            <div class="text-muted" style="font-size: 0.78rem;">SKU: <code>{{ $sku }}</code></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end small fw-medium">
                                                {{ number_format($value->price, 0, ',', '.') }}đ
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group input-group-sm justify-content-center" style="max-width: 112px; margin: 0 auto;">
                                                    <a href="{{ route('frontend.giohang.giam', ['row_id' => $value->rowId]) }}" class="btn btn-outline-secondary" title="Giảm">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </a>
                                                    <input type="number" name="qty[{{ $value->rowId }}]" class="form-control text-center px-1" min="1" max="10" value="{{ $value->qty }}" />
                                                    <a href="{{ route('frontend.giohang.tang', ['row_id' => $value->rowId]) }}" class="btn btn-outline-secondary" title="Tăng">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-danger small">
                                                {{ number_format($value->price * $value->qty, 0, ',', '.') }}đ
                                            </td>
                                            <td class="text-center pe-3">
                                                <a href="{{ route('frontend.giohang.xoa', ['row_id' => $value->rowId]) }}"
                                                   class="text-danger"
                                                   title="Xóa sản phẩm"
                                                   onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('frontend.sanpham') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục chọn Laptop
                            </a>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fa-solid fa-rotate me-1"></i> Cập nhật số lượng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Khung Mã giảm giá & Tóm tắt đơn -->
        <div class="col-lg-4">
            <!-- Mã giảm giá -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold small">
                    <i class="fa-solid fa-ticket text-warning me-1"></i> Mã giảm giá (Voucher)
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.giohang.magiamgia') }}" method="post">
                        @csrf
                        @if($voucher)
                            <div class="alert alert-success py-2 px-3 small d-flex justify-content-between align-items-center mb-0">
                                <div>
                                    Đang dùng mã: <strong class="font-monospace">{{ $voucher['macode'] }}</strong>
                                    <div class="text-muted">Giảm {{ number_format($voucher['tiengiam'], 0, ',', '.') }}đ</div>
                                </div>
                                <button type="submit" name="huy_ma" value="1" class="btn btn-outline-danger btn-sm py-0 px-2" title="Gỡ mã">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        @else
                            <div class="input-group input-group-sm">
                                <input type="text" name="macode" class="form-control text-uppercase @error('macode') is-invalid @enderror" placeholder="Nhập mã (VD: SINHVIEN500K)" required />
                                <button type="submit" class="btn btn-dark">Áp dụng</button>
                            </div>
                            @error('macode')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text small">Gợi ý mã mẫu: <code>SINHVIEN500K</code>, <code>GAMING5</code>, <code>NEWUSER300K</code></div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Tổng tiền & Nút đặt hàng -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Tóm tắt đơn hàng</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-semibold">{{ number_format($tamTinh, 0, ',', '.') }}đ</span>
                    </div>

                    @if($tienGiam > 0)
                        <div class="d-flex justify-content-between mb-2 small text-success">
                            <span>Giảm giá voucher:</span>
                            <span class="fw-semibold">-{{ number_format($tienGiam, 0, ',', '.') }}đ</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="text-success fw-semibold">Miễn phí</span>
                    </div>

                    <hr class="my-3" />

                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <span class="fw-bold">Tổng thanh toán:</span>
                        <span class="fs-4 fw-bold text-danger">{{ number_format($tongCong, 0, ',', '.') }}đ</span>
                    </div>

                    <div class="d-grid gap-2">
                        @auth
                            <a href="{{ route('user.dathang') }}" class="btn btn-danger btn-lg fw-semibold">
                                Tiến hành đặt hàng <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-semibold">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng nhập để đặt hàng
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection