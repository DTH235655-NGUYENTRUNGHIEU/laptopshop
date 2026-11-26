@extends('layouts.frontend')

@section('title', 'Giới thiệu về LaptopShop')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giới thiệu</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 p-lg-5">
            <h1 class="h3 fw-bold text-primary mb-3">Về hệ thống bán lẻ {{ config('app.name', 'LaptopShop') }}</h1>
            <p class="lead fs-6 text-secondary mb-4">
                LaptopShop chuyên cung cấp các dòng máy tính xách tay chính hãng dành cho sinh viên, nhân viên văn phòng, lập trình viên, kiến trúc sư đồ họa và game thủ chuyên nghiệp.
            </p>

            <div class="row g-3 text-center mb-4">
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 bg-light">
                        <div class="fs-2 fw-bold text-primary">{{ $tongLaptop }}+</div>
                        <div class="small text-muted">Mẫu Laptop đang kinh doanh</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 bg-light">
                        <div class="fs-2 fw-bold text-success">{{ $tongHang }}</div>
                        <div class="small text-muted">Thương hiệu đối tác chính hãng</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 bg-light">
                        <div class="fs-2 fw-bold text-danger">100%</div>
                        <div class="small text-muted">Xuất hóa đơn VAT & Bảo hành theo S/N</div>
                    </div>
                </div>
            </div>

            <h2 class="h5 fw-bold mb-2">Giá trị cốt lõi</h2>
            <ul class="small lh-lg mb-4">
                <li><strong>Minh bạch cấu hình:</strong> Công khai chi tiết 10 thông số kỹ thuật từ CPU, RAM, SSD đến màn hình và trọng lượng thực tế.</li>
                <li><strong>Quản lý bảo hành điện tử:</strong> Mỗi máy xuất kho đều lưu kèm Serial Number / Service Tag trên hóa đơn bán hàng.</li>
                <li><strong>Hỗ trợ kỹ thuật trọn đời:</strong> Vệ sinh máy, cài đặt phần mềm học tập và hỗ trợ nâng cấp linh kiện miễn phí công lắp.</li>
            </ul>

            <a href="{{ route('frontend.sanpham') }}" class="btn btn-primary me-2">Xem danh mục Laptop</a>
            <a href="{{ route('frontend.lienhe') }}" class="btn btn-outline-primary">Liên hệ tư vấn</a>
        </div>
    </div>
</div>
@endsection