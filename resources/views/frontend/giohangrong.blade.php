@extends('layouts.frontend')

@section('title', 'Giỏ hàng trống')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm mx-auto text-center p-5" style="max-width: 560px;">
        <div class="mb-3 text-muted">
            <i class="fa-solid fa-cart-arrow-down fa-4x opacity-25"></i>
        </div>
        <h1 class="h4 fw-bold mb-2">Giỏ hàng của bạn đang trống</h1>
        <p class="text-muted small mb-4">
            Bạn chưa thêm mẫu laptop nào vào giỏ hàng. Hãy khám phá các dòng Laptop Gaming, Văn phòng và MacBook chính hãng đang có ưu đãi tốt nhất.
        </p>
        <div>
            <a href="{{ route('frontend.sanpham') }}" class="btn btn-primary px-4">
                <i class="fa-solid fa-laptop me-1"></i> Khám phá Laptop ngay
            </a>
        </div>
    </div>
</div>
@endsection