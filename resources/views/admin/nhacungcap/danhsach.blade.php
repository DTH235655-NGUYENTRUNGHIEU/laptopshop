@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-truck-field text-primary me-1"></i> Danh sách Nhà cung cấp</span>
        <a href="{{ route('admin.nhacungcap.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Thêm mới
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="22%">Tên nhà cung cấp</th>
                    <th width="12%">Điện thoại</th>
                    <th width="16%">Email</th>
                    <th width="12%">Mã số thuế</th>
                    <th>Địa chỉ</th>
                    <th width="8%" class="text-center">Phiếu nhập</th>
                    <th width="5%" class="text-center">Sửa</th>
                    <th width="5%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nhacungcap as $value)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $value->ten_nhacungcap }}</td>
                        <td>{{ $value->sodienthoai }}</td>
                        <td class="small">{{ $value->email ?? '-' }}</td>
                        <td><code>{{ $value->masothue ?? '-' }}</code></td>
                        <td class="small">{{ $value->diachi }}</td>
                        <td class="text-center"><span class="badge bg-info text-dark">{{ $value->phieu_nhap_count }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.nhacungcap.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.nhacungcap.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa"
                               onclick="return confirm('Bạn có muốn xóa nhà cung cấp {{ $value->ten_nhacungcap }} không?')">
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