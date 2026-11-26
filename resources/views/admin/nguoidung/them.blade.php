@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Thêm tài khoản người dùng</div>
    <div class="card-body">
        <form action="{{ route('admin.nguoidung.them') }}" method="post">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required />
                    @error('name')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Địa chỉ Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required />
                    @error('email')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="username">Tên đăng nhập (để trống tự lấy theo email)</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" />
                    @error('username')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="sodienthoai">Số điện thoại</label>
                    <input type="text" class="form-control @error('sodienthoai') is-invalid @enderror" id="sodienthoai" name="sodienthoai" value="{{ old('sodienthoai') }}" />
                    @error('sodienthoai')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="role">Quyền hạn <span class="text-danger">*</span></label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Khách hàng (user)</option>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Nhân viên (staff)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Quản trị viên (admin)</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="diachi">Địa chỉ</label>
                    <input type="text" class="form-control @error('diachi') is-invalid @enderror" id="diachi" name="diachi" value="{{ old('diachi') }}" />
                    @error('diachi')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required />
                    @error('password')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required />
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu mới</button>
                <a href="{{ route('admin.nguoidung') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection