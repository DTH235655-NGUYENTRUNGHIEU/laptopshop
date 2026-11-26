@extends('layouts.frontend')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Tài khoản</a></li>
            <li class="breadcrumb-item active">Lịch sử đơn hàng</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('user._sidebar')
        </aside>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="fa-solid fa-box-open text-primary me-1"></i>
                    Đơn hàng của tôi ({{ $donhang->total() }})
                </div>
                <div class="card-body">
                    @forelse($donhang as $dh)
                        @php
                            $badgeClass = match ((int) $dh->tinhtrang_id) {
                                1 => 'bg-warning text-dark',
                                2, 3 => 'bg-info text-dark',
                                4 => 'bg-primary',
                                5 => 'bg-success',
                                6 => 'bg-danger',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <div class="border rounded-3 p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-2 mb-2">
                                <div class="small">
                                    <strong class="font-monospace text-primary">{{ $dh->madonhang }}</strong>
                                    <span class="text-muted ms-2">{{ $dh->created_at ? $dh->created_at->format('d/m/Y H:i') : '' }}</span>
                                    @if($dh->HoaDon)
                                        <span class="badge bg-success-subtle text-success ms-1">Đã xuất HĐ {{ $dh->HoaDon->sohoadon }}</span>
                                    @endif
                                </div>
                                <span class="badge {{ $badgeClass }}">{{ $dh->TinhTrang->tinhtrang ?? 'Không rõ' }}</span>
                            </div>

                            <div class="small mb-2">
                                @foreach($dh->DonHang_ChiTiet as $ct)
                                    <div class="d-flex justify-content-between py-1">
                                        <span>{{ $ct->SanPham->tensanpham ?? 'Sản phẩm' }} <strong>× {{ $ct->soluongban }}</strong></span>
                                        <span>{{ number_format($ct->dongiaban * $ct->soluongban, 0, ',', '.') }}đ</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                                <div class="small">
                                    Tổng tiền: <strong class="text-danger fs-6">{{ number_format($dh->tongtien, 0, ',', '.') }}đ</strong>
                                    <span class="badge bg-light text-dark border ms-1">{{ $dh->phuongthuc_thanhtoan }}</span>
                                </div>
                                <a href="{{ route('user.donhang.chitiet', ['id' => $dh->id]) }}" class="btn btn-outline-primary btn-sm">
                                    Xem chi tiết & Bảo hành
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <p class="mb-2">Bạn chưa có đơn hàng nào.</p>
                            <a href="{{ route('frontend.sanpham') }}" class="btn btn-primary btn-sm">Mua sắm ngay</a>
                        </div>
                    @endforelse

                    <div class="d-flex justify-content-center">
                        {{ $donhang->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection