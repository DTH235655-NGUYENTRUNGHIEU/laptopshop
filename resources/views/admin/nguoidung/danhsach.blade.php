@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-users text-primary me-1"></i> Danh sách Tài khoản người dùng</span>
        <a href="{{ route('admin.nguoidung.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-user-plus"></i> Thêm tài khoản
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="18%">Họ và tên</th>
                    <th width="14%">Username</th>
                    <th width="20%">Email</th>
                    <th width="12%">Điện thoại</th>
                    <th width="11%" class="text-center">Quyền hạn</th>
                    <th width="10%" class="text-center">Ngày tạo</th>
                    <th width="5%" class="text-center">Sửa</th>
                    <th width="5%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nguoidung as $value)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $value->name }}</td>
                        <td><code>{{ $value->username ?? '-' }}</code></td>
                        <td>{{ $value->email }}</td>
                        <td>{{ $value->sodienthoai ?? '-' }}</td>
                        <td class="text-center">
                            @if($value->role === 'admin')
                                <span class="badge bg-danger">Quản trị viên</span>
                            @elseif($value->role === 'staff')
                                <span class="badge bg-warning text-dark">Nhân viên</span>
                            @else
                                <span class="badge bg-secondary">Khách hàng</span>
                            @endif
                        </td>
                        <td class="text-center small">{{ $value->created_at ? $value->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.nguoidung.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            @if($value->id !== Auth::id())
                                <a href="{{ route('admin.nguoidung.xoa', ['id' => $value->id]) }}"
                                   class="text-danger"
                                   title="Xóa"
                                   onclick="return confirm('Bạn có muốn xóa tài khoản {{ $value->email }} không?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            @else
                                <span class="text-muted" title="Tài khoản hiện tại"><i class="fa-solid fa-lock"></i></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection