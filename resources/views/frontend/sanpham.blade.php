@extends('layouts.frontend')

@section('title', $title)

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.sanpham') }}">Laptop</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Bộ lọc -->
        <aside class="col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="fa-solid fa-filter text-primary me-1"></i> Bộ lọc Laptop
                </div>
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="get">
                        @if(request('tukhoa'))
                            <input type="hidden" name="tukhoa" value="{{ request('tukhoa') }}" />
                        @endif

                        @if(!$loaiHienTai)
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Phân khúc nhu cầu</label>
                                <select name="loai" class="form-select form-select-sm">
                                    <option value="">-- Tất cả phân khúc --</option>
                                    @foreach($dsLoai as $loai)
                                        <option value="{{ $loai->id }}" {{ request('loai') == $loai->id ? 'selected' : '' }}>
                                            {{ $loai->tenloai }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if(!$hangHienTai)
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Thương hiệu</label>
                                <select name="hang" class="form-select form-select-sm">
                                    <option value="">-- Tất cả thương hiệu --</option>
                                    @foreach($dsHang as $hang)
                                        <option value="{{ $hang->id }}" {{ request('hang') == $hang->id ? 'selected' : '' }}>
                                            {{ $hang->tenhang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Khoảng giá</label>
                            <select name="mucgia" class="form-select form-select-sm">
                                <option value="">-- Mọi mức giá --</option>
                                <option value="duoi-15" {{ request('mucgia') === 'duoi-15' ? 'selected' : '' }}>Dưới 15 triệu</option>
                                <option value="15-25" {{ request('mucgia') === '15-25' ? 'selected' : '' }}>Từ 15 - 25 triệu</option>
                                <option value="25-35" {{ request('mucgia') === '25-35' ? 'selected' : '' }}>Từ 25 - 35 triệu</option>
                                <option value="tren-35" {{ request('mucgia') === 'tren-35' ? 'selected' : '' }}>Trên 35 triệu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Sắp xếp theo</label>
                            <select name="sapxep" class="form-select form-select-sm">
                                <option value="moi-nhat" {{ request('sapxep', 'moi-nhat') === 'moi-nhat' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="gia-tang" {{ request('sapxep') === 'gia-tang' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                <option value="gia-giam" {{ request('sapxep') === 'gia-giam' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                <option value="ten-az" {{ request('sapxep') === 'ten-az' ? 'selected' : '' }}>Tên A - Z</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-check me-1"></i> Áp dụng bộ lọc
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">Xóa bộ lọc</a>
                        </div>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Lưới sản phẩm -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 fw-bold mb-0">{{ $title }}</h1>
                <span class="text-muted small">Hiển thị {{ $sanpham->count() }} / {{ $sanpham->total() }} sản phẩm</span>
            </div>

            @if($sanpham->count() > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3">
                    @foreach($sanpham as $sp)
                        @include('frontend._product_card', ['sp' => $sp])
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $sanpham->links() }}
                </div>
            @else
                <div class="bg-white border rounded-3 p-5 text-center text-muted">
                    <i class="fa-solid fa-box-open fa-3x opacity-25 mb-3"></i>
                    <p class="mb-2">Không tìm thấy mẫu laptop nào phù hợp với điều kiện lọc hiện tại.</p>
                    <a href="{{ route('frontend.sanpham') }}" class="btn btn-primary btn-sm">Xem toàn bộ Laptop</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection