@extends('layouts.frontend')

@section('title', 'Liên hệ tư vấn')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Liên hệ</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Thông tin cửa hàng -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h1 class="h4 fw-bold mb-3">Liên hệ {{ config('app.name', 'LaptopShop') }}</h1>
                    <p class="small text-muted mb-4">
                        Cần tư vấn chọn laptop theo ngành học, nâng cấp RAM/SSD hoặc báo giá xuất hóa đơn VAT cho doanh nghiệp? Hãy gửi yêu cầu để kỹ thuật viên hỗ trợ bạn ngay.
                    </p>

                    <div class="d-grid gap-3 small">
                        <div class="d-flex gap-3">
                            <div class="text-primary fs-5"><i class="fa-solid fa-location-dot fa-fw"></i></div>
                            <div>
                                <div class="fw-semibold">Địa chỉ showroom</div>
                                <div class="text-muted">18 Ung Văn Khiêm, Phường Đông Xuyên, TP. Long Xuyên, An Giang</div>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="text-primary fs-5"><i class="fa-solid fa-phone-volume fa-fw"></i></div>
                            <div>
                                <div class="fw-semibold">Hotline tư vấn & Bảo hành</div>
                                <div class="text-muted">0909 123 456 (8:00 – 21:00 hằng ngày)</div>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="text-primary fs-5"><i class="fa-solid fa-envelope fa-fw"></i></div>
                            <div>
                                <div class="fw-semibold">Email hỗ trợ & Báo giá VAT</div>
                                <div class="text-muted">contact@laptopshop.vn</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form gửi yêu cầu liên hệ -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Gửi yêu cầu tư vấn / hỗ trợ</div>
                <div class="card-body p-4">
                    <form action="{{ route('frontend.lienhe') }}" method="post">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" for="hoten">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('hoten') is-invalid @enderror" id="hoten" name="hoten" value="{{ old('hoten', Auth::user()->name ?? '') }}" required />
                                @error('hoten') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" for="email">Địa chỉ Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required />
                                @error('email') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label small fw-semibold" for="sodienthoai">Số điện thoại</label>
                                <input type="text" class="form-control @error('sodienthoai') is-invalid @enderror" id="sodienthoai" name="sodienthoai" value="{{ old('sodienthoai', Auth::user()->sodienthoai ?? '') }}" />
                                @error('sodienthoai') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-md-7">
                                <label class="form-label small fw-semibold" for="tieude">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tieude') is-invalid @enderror" id="tieude" name="tieude" value="{{ old('tieude') }}" placeholder="VD: Tư vấn laptop học CNTT tầm 20 triệu..." required />
                                @error('tieude') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold" for="noidung">Nội dung chi tiết <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('noidung') is-invalid @enderror" id="noidung" name="noidung" rows="4" placeholder="Mô tả nhu cầu sử dụng hoặc câu hỏi của bạn..." required>{{ old('noidung') }}</textarea>
                                @error('noidung') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa-solid fa-paper-plane me-1"></i> Gửi yêu cầu liên hệ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection