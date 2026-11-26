@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-folder-tree text-primary me-1"></i> Danh sách Chủ đề bài viết</span>
        <a href="{{ route('admin.chude.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Thêm mới
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="6%" class="text-center">#</th>
                    <th width="38%">Tên chủ đề</th>
                    <th width="34%">Slug (URL)</th>
                    <th width="12%" class="text-center">Số bài viết</th>
                    <th width="5%" class="text-center">Sửa</th>
                    <th width="5%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chude as $value)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $value->tenchude }}</td>
                        <td><code>{{ $value->tenchude_slug }}</code></td>
                        <td class="text-center"><span class="badge bg-info text-dark">{{ $value->bai_viet_count }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.chude.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.chude.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa"
                               onclick="return confirm('Bạn có muốn xóa chủ đề {{ $value->tenchude }} không?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Chưa có chủ đề nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection