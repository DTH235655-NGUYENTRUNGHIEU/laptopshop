@extends('layouts.frontend')

@section('title', $title ?? 'Tin tức công nghệ')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.baiviet') }}">Tin tức</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="h3 fw-bold mb-0">{{ $title }}</h1>

        <!-- Bộ lọc nhanh theo chủ đề -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('frontend.baiviet') }}"
               class="btn btn-sm rounded-pill {{ empty($tenchude_slug) ? 'btn-primary' : 'btn-outline-secondary' }}">
                Tất cả
            </a>
            @foreach($dsChuDe as $cd)
                <a href="{{ route('frontend.baiviet.chude', ['tenchude_slug' => $cd->tenchude_slug]) }}"
                   class="btn btn-sm rounded-pill {{ $tenchude_slug === $cd->tenchude_slug ? 'btn-primary' : 'btn-outline-secondary' }}">
                    {{ $cd->tenchude }} ({{ $cd->bai_viet_count }})
                </a>
            @endforeach
        </div>
    </div>

    @if($baiviet->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($baiviet as $bv)
                @php
                    $urlBaiViet = route('frontend.baiviet.chitiet', [
                        'tenchude_slug' => $bv->ChuDe->tenchude_slug ?? 'tin-tuc',
                        'tieude_slug' => $bv->tieude_slug . '-' . $bv->id . '.html',
                    ]);
                @endphp
                <div class="col">
                    <article class="card h-100 border-0 shadow-sm product-card">
                        <a href="{{ $urlBaiViet }}">
                            @if($bv->hinhanh && file_exists(public_path('storage/' . $bv->hinhanh)))
                                <img src="{{ asset('public/storage/' . $bv->hinhanh) }}" class="card-img-top" alt="{{ $bv->tieude }}" style="height: 200px; object-fit: cover;" />
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                                    <i class="fa-solid fa-newspaper fa-3x opacity-25"></i>
                                </div>
                            @endif
                        </a>

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center gap-2 small text-muted mb-2">
                                <span class="badge bg-primary-subtle text-primary">{{ $bv->ChuDe->tenchude ?? 'Tin tức' }}</span>
                                <span><i class="fa-regular fa-calendar me-1"></i>{{ $bv->created_at ? $bv->created_at->format('d/m/Y') : '' }}</span>
                                <span class="ms-auto"><i class="fa-regular fa-eye me-1"></i>{{ $bv->luotxem }}</span>
                            </div>

                            <h2 class="h6 fw-bold mb-2">
                                <a href="{{ $urlBaiViet }}" class="text-dark text-decoration-none">
                                    {{ $bv->tieude }}
                                </a>
                            </h2>

                            <p class="small text-muted mb-3">{{ \Illuminate\Support\Str::limit($bv->tomtat, 120) }}</p>

                            <div class="mt-auto d-flex justify-content-between align-items-center small">
                                <span class="text-muted"><i class="fa-regular fa-user me-1"></i>{{ $bv->User->name ?? 'Admin' }}</span>
                                <a href="{{ $urlBaiViet }}" class="fw-semibold text-decoration-none">
                                    Đọc tiếp <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $baiviet->links() }}
        </div>
    @else
        <div class="bg-white border rounded-3 p-5 text-center text-muted">
            Chưa có bài viết nào trong chuyên mục này.
        </div>
    @endif
</div>
@endsection