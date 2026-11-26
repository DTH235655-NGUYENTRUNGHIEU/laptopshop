@extends('layouts.frontend')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm mx-auto p-4 p-md-5 text-center" style="max-width: 640px;">
        <div class="text-success mb-3">
            <i class="fa-solid fa-circle-check fa-4x"></i>
        </div>
        <h1 class="h3 fw-bold text-success mb-2">Đặt hàng thành công!</h1>
        <p class="text-muted small mb-4">
            Cảm ơn bạn đã mua sắm tại <strong>{{ config('app.name', 'LaptopShop') }}</strong>. Chúng tôi đã gửi email xác nhận đơn hàng và sẽ sớm liên hệ xác nhận giao máy.
        </p>

        @if($donhang)
            <div class="bg-light border rounded-3 p-3 text-start small mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Mã đơn hàng:</span>
                    <strong class="font-monospace text-primary">{{ $donhang->madonhang }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Người nhận:</span>
                    <strong>{{ $donhang->hoten_nguoinhan }} ({{ $donhang->dienthoaigiaohang }})</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Địa chỉ:</span>
                    <span class="text-end">{{ $donhang->diachigiaohang }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Thanh toán:</span>
                    <span>{{ $donhang->phuongthuc_thanhtoan }}</span>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top mt-2">
                    <span class="fw-bold">Tổng thanh toán:</span>
                    <strong class="text-danger fs-6">{{ number_format($donhang->tongtien, 0, ',', '.') }}đ</strong>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-center flex-wrap gap-2">
            @if($donhang)
                <a href="{{ route('user.donhang.chitiet', ['id' => $donhang->id]) }}" class="btn btn-primary">
                    <i class="fa-solid fa-eye me-1"></i> Xem chi tiết đơn hàng
                </a>
            @else
                <a href="{{ route('user.donhang') }}" class="btn btn-primary">
                    <i class="fa-solid fa-box-open me-1"></i> Lịch sử đơn hàng
                </a>
            @endif
            <a href="{{ route('frontend.home') }}" class="btn btn-outline-secondary">Về trang chủ</a>
        </div>
    </div>
</div>
@endsection