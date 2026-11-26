@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-list-check text-primary me-1"></i> Danh sách Tình trạng đơn hàng</span>
        <a href="{{ route('admin.tinhtrang.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Thêm mới
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="8%" class="text-center">#</th>
                    <th>Tên tình trạng</th>
                    <th width="14%" class="text-center">Số đơn hàng</th>
                    <th width="8%" class="text-center">Sửa</th>
                    <th width="8%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tinhtrang as $value)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $value->tinhtrang }}</td>
                        <td class="text-center"><span class="badge bg-info text-dark">{{ $value->don_hang_count }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.tinhtrang.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.tinhtrang.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa"
                               onclick="return confirm('Bạn có muốn xóa tình trạng {{ $value->tinhtrang }} không?')">
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