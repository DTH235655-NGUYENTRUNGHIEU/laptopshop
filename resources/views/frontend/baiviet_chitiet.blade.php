@extends('layouts.frontend')

@section('title', $baiviet->tieude)

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.baiviet') }}">Tin tức</a></li>
            @if($baiviet->ChuDe)
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.baiviet.chude', ['tenchude_slug' => $baiviet->ChuDe->tenchude_slug]) }}">
                        {{ $baiviet->ChuDe->tenchude }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active">Chi tiết</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <article class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="mb-2">
                        <a href="{{ route('frontend.baiviet.chude', ['tenchude_slug' => $baiviet->ChuDe->tenchude_slug ?? 'tin-tuc']) }}"
                           class="badge bg-primary text-decoration-none">
                            {{ $baiviet->ChuDe->tenchude ?? 'Tin tức' }}
                        </a>
                    </div>

                    <h1 class="h3 fw-bold mb-3">{{ $baiviet->tieude }}</h1>

                    <div class="d-flex flex-wrap gap-3 small text-muted border-bottom pb-3 mb-4">
                        <span><i class="fa-regular fa-user me-1"></i>Tác giả: <strong>{{ $baiviet->User->name ?? 'Admin' }}</strong></span>
                        <span><i class="fa-regular fa-calendar me-1"></i>{{ $baiviet->created_at ? $baiviet->created_at->format('d/m/Y H:i') : '' }}</span>
                        <span><i class="fa-regular fa-eye me-1"></i>{{ $baiviet->luotxem }} lượt xem</span>
                        <span><i class="fa-regular fa-comments me-1"></i>{{ $baiviet->BinhLuanBaiViet->count() }} bình luận</span>
                    </div>

                    @if($baiviet->hinhanh && file_exists(public_path('storage/' . $baiviet->hinhanh)))
                        <div class="mb-4 text-center">
                            <img src="{{ asset('public/storage/' . $baiviet->hinhanh) }}" alt="{{ $baiviet->tieude }}" class="img-fluid rounded-3" />
                        </div>
                    @endif

                    @if($baiviet->tomtat)
                        <p class="fw-semibold fs-6 text-secondary bg-light border-start border-4 border-primary p-3 rounded-end mb-4">
                            {{ $baiviet->tomtat }}
                        </p>
                    @endif

                    <div class="article-content lh-lg">
                        {!! $baiviet->noidung !!}
                    </div>
                </div>
            </article>

            <!-- Khối Bình luận bài viết -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="fa-solid fa-comments text-primary me-1"></i>
                    Bình luận bạn đọc ({{ $baiviet->BinhLuanBaiViet->count() }})
                </div>
                <div class="card-body">
                    @auth
                        <form action="{{ route('user.binhluan', ['baiviet_id' => $baiviet->id]) }}" method="post" class="mb-4">
                            @csrf
                            <div class="mb-2">
                                <label for="noidungbinhluan" class="form-label small fw-semibold">Viết bình luận của bạn</label>
                                <textarea name="noidungbinhluan" id="noidungbinhluan" rows="3" class="form-control form-control-sm @error('noidungbinhluan') is-invalid @enderror" placeholder="Đặt câu hỏi hoặc chia sẻ ý kiến về bài viết..." required>{{ old('noidungbinhluan') }}</textarea>
                                @error('noidungbinhluan')
                                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-paper-plane me-1"></i> Gửi bình luận
                            </button>
                        </form>
                    @else
                        <div class="alert alert-light border small mb-4">
                            Vui lòng <a href="{{ route('login') }}" class="fw-semibold">đăng nhập</a> để tham gia bình luận bài viết.
                        </div>
                    @endauth

                    @forelse($baiviet->BinhLuanBaiViet as $bl)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold small">
                                    <i class="fa-solid fa-circle-user text-secondary me-1"></i>
                                    {{ $bl->User->name ?? 'Khách' }}
                                </span>
                                <span class="text-muted small">{{ $bl->created_at ? $bl->created_at->format('d/m/Y H:i') : '' }}</span>
                            </div>
                            <p class="small mb-0">{{ $bl->noidungbinhluan }}</p>
                        </div>
                    @empty
                        <p class="small text-muted mb-0">Chưa có bình luận nào. Hãy là người đầu tiên chia sẻ ý kiến!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar bài viết cùng chuyên mục -->
        <aside class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Bài viết cùng chuyên mục</div>
                <div class="card-body d-grid gap-3">
                    @forelse($baiVietLienQuan as $lq)
                        @php
                            $urlLq = route('frontend.baiviet.chitiet', [
                                'tenchude_slug' => $lq->ChuDe->tenchude_slug ?? 'tin-tuc',
                                'tieude_slug' => $lq->tieude_slug . '-' . $lq->id . '.html',
                            ]);
                        @endphp
                        <div class="d-flex gap-3 border-bottom pb-3">
                            <div class="flex-shrink-0">
                                @if($lq->hinhanh && file_exists(public_path('storage/' . $lq->hinhanh)))
                                    <img src="{{ asset('public/storage/' . $lq->hinhanh) }}" alt="{{ $lq->tieude }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;" />
                                @else
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 60px;">
                                        <i class="fa-solid fa-newspaper"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <a href="{{ $urlLq }}" class="small fw-semibold text-dark text-decoration-none d-block mb-1">
                                    {{ \Illuminate\Support\Str::limit($lq->tieude, 65) }}
                                </a>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    {{ $lq->created_at ? $lq->created_at->format('d/m/Y') : '' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="small text-muted">Không có bài viết liên quan khác.</div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection