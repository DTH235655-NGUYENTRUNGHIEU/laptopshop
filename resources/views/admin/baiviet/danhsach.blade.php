@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-newspaper text-primary me-1"></i>
            Danh sách Bài viết ({{ $baiviet->total() }} bài)
        </span>
        <a href="{{ route('admin.baiviet.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Viết bài mới
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.baiviet') }}" method="get" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="tukhoa" class="form-control form-control-sm" value="{{ request('tukhoa') }}" placeholder="Tìm theo tiêu đề bài viết..." />
            </div>
            <div class="col-md-4">
                <select name="chude_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả chủ đề --</option>
                    @foreach($chude as $cd)
                        <option value="{{ $cd->id }}" {{ request('chude_id') == $cd->id ? 'selected' : '' }}>
                            {{ $cd->tenchude }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Lọc bài viết
                </button>
                <a href="{{ route('admin.baiviet') }}" class="btn btn-outline-secondary btn-sm" title="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="9%" class="text-center">Ảnh bìa</th>
                        <th width="14%">Chủ đề</th>
                        <th>Thông tin bài viết</th>
                        <th width="6%" class="text-center" title="Kiểm duyệt">Duyệt</th>
                        <th width="6%" class="text-center" title="Hiển thị">Hiện</th>
                        <th width="4%" class="text-center">Sửa</th>
                        <th width="4%" class="text-center">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($baiviet as $value)
                        <tr>
                            <td class="text-center">{{ $baiviet->firstItem() + $loop->index }}</td>
                            <td class="text-center">
                                @if($value->hinhanh && file_exists(public_path('storage/' . $value->hinhanh)))
                                    <img src="{{ asset('public/storage/' . $value->hinhanh) }}" alt="{{ $value->tieude }}" class="img-thumbnail p-0" style="width: 72px; height: 48px; object-fit: cover;" />
                                @else
                                    <div class="bg-light border rounded d-inline-flex align-items-center justify-content-center text-muted" style="width: 72px; height: 48px;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $value->ChuDe->tenchude ?? '-' }}</span></td>
                            <td>
                                <a href="{{ route('admin.baiviet.sua', ['id' => $value->id]) }}" class="fw-semibold text-decoration-none">
                                    {{ $value->tieude }}
                                </a>
                                <div class="small text-muted mt-1">
                                    Tác giả: <strong>{{ $value->User->name ?? '-' }}</strong>
                                    | Ngày đăng: {{ $value->created_at ? $value->created_at->format('d/m/Y H:i') : '-' }}
                                    | <i class="fa-solid fa-eye"></i> {{ $value->luotxem }}
                                    | <i class="fa-solid fa-comments"></i> {{ $value->binh_luan_bai_viet_count }}
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.baiviet.kiemduyet', ['id' => $value->id]) }}" title="Bật/Tắt kiểm duyệt">
                                    @if($value->kiemduyet)
                                        <i class="fa-solid fa-circle-check text-success fa-lg"></i>
                                    @else
                                        <i class="fa-solid fa-circle-xmark text-danger fa-lg"></i>
                                    @endif
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.baiviet.kichhoat', ['id' => $value->id]) }}" title="Ẩn/Hiện bài viết">
                                    @if($value->kichhoat)
                                        <i class="fa-solid fa-eye text-primary fa-lg"></i>
                                    @else
                                        <i class="fa-solid fa-eye-slash text-secondary fa-lg"></i>
                                    @endif
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.baiviet.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.baiviet.xoa', ['id' => $value->id]) }}"
                                   class="text-danger"
                                   title="Xóa"
                                   onclick="return confirm('Bạn có muốn xóa bài viết này không?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Chưa có bài viết nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $baiviet->links() }}
        </div>
    </div>
</div>
@endsection