@extends('layouts.frontend')

@section('title', 'Tài khoản của tôi')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Tài khoản của tôi</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('user._sidebar')
        </aside>

        <div class="col-lg-9">
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="text-muted small">Tổng đơn hàng</div>
                        <div class="fs-4 fw-bold text-primary">{{ $tongDonHang }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="text-muted small">Đang xử lý</div>
                        <div class="fs-4 fw-bold text-warning">{{ $donChoXuLy }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="text-muted small">Đã giao</div>
                        <div class="fs-4 fw-bold text-success">{{ $donHoanThanh }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="text-muted small">Tổng chi tiêu</div>
                        <div class="fs-6 fw-bold text-danger mt-1">{{ number_format($tongChiTieu, 0, ',', '.') }}đ</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Đơn hàng gần đây</span>
                    <a href="{{ route('user.donhang') }}" class="small text-decoration-none">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th class="text-end">Tổng tiền</th>
                                    <th class="text-center">Tình trạng</th>
                                    <th class="text-center pe-3">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donHangGanDay as $dh)
                                    <tr>
                                        <td class="ps-3 fw-bold font-monospace text-primary">{{ $dh->madonhang }}</td>
                                        <td>{{ $dh->created_at ? $dh->created_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td class="text-end fw-semibold text-danger">{{ number_format($dh->tongtien, 0, ',', '.') }}đ</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $dh->TinhTrang->tinhtrang ?? 'Không rõ' }}</span>
                                        </td>
                                        <td class="text-center pe-3">
                                            <a href="{{ route('user.donhang.chitiet', ['id' => $dh->id]) }}" class="btn btn-outline-primary btn-sm py-0 px-2">
                                                Xem
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Bạn chưa đặt đơn hàng nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection