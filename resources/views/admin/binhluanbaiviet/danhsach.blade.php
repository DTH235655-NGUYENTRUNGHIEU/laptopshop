@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-comments text-primary me-1"></i>
        Danh sách Bình luận bài viết ({{ $binhluanbaiviet->total() }})
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="4%" class="text-center">#</th>
                    <th width="16%">Người bình luận</th>
                    <th>Bài viết & Nội dung bình luận</th>
                    <th width="7%" class="text-center">Duyệt</th>
                    <th width="7%" class="text-center">Hiện</th>
                    <th width="5%" class="text-center">Sửa</th>
                    <th width="5%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($binhluanbaiviet as $value)
                    <tr>
                        <td class="text-center">{{ $binhluanbaiviet->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $value->User->name ?? 'Ẩn danh' }}</div>
                            <div class="small text-muted">{{ $value->created_at ? $value->created_at->format('d/m/Y H:i') : '-' }}</div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-primary">
                                Bài: {{ $value->BaiViet->tieude ?? 'Bài viết đã xóa' }}
                            </div>
                            <div class="small mt-1">{{ $value->noidungbinhluan }}</div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.binhluanbaiviet.kiemduyet', ['id' => $value->id]) }}" title="Bật/Tắt kiểm duyệt">
                                @if($value->kiemduyet)
                                    <i class="fa-solid fa-circle-check text-success fa-lg"></i>
                                @else
                                    <i class="fa-solid fa-circle-xmark text-danger fa-lg"></i>
                                @endif
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.binhluanbaiviet.kichhoat', ['id' => $value->id]) }}" title="Ẩn/Hiện bình luận">
                                @if($value->kichhoat)
                                    <i class="fa-solid fa-eye text-primary fa-lg"></i>
                                @else
                                    <i class="fa-solid fa-eye-slash text-secondary fa-lg"></i>
                                @endif
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.binhluanbaiviet.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.binhluanbaiviet.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa"
                               onclick="return confirm('Bạn có muốn xóa bình luận này không?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Chưa có bình luận nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3 d-flex justify-content-end">
            {{ $binhluanbaiviet->links() }}
        </div>
    </div>
</div>
@endsection