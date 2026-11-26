@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-diagram-project text-primary me-1"></i> Danh sách Loại sản phẩm</span>
        <a href="{{ route('admin.loaisanpham.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Thêm mới
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="25%">Tên loại</th>
                    <th width="22%">Slug</th>
                    <th width="30%">Mô tả</th>
                    <th width="8%" class="text-center">Số SP</th>
                    <th width="5%" class="text-center">Sửa</th>
                    <th width="5%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loaisanpham as $value)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $value->tenloai }}</td>
                        <td><code>{{ $value->tenloai_slug }}</code></td>
                        <td class="small">{{ $value->mota }}</td>
                        <td class="text-center"><span class="badge bg-info text-dark">{{ $value->san_pham_count }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.loaisanpham.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.loaisanpham.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa"
                               onclick="return confirm('Bạn có muốn xóa loại sản phẩm {{ $value->tenloai }} không?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection