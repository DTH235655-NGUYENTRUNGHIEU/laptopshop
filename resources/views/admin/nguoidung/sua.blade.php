@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Cập nhật tài khoản người dùng</div>
    <div class="card-body">
        <form action="{{ route('admin.nguoidung.sua', ['id' => $nguoidung->id]) }}" method="post">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $nguoidung->name) }}" required />
                    @error('name')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Địa chỉ Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $nguoidung->email) }}" required />
                    @error('email')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="username">Tên đăng nhập</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $nguoidung->username) }}" />
                    @error('username')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="sodienthoai">Số điện thoại</label>
                    <input type="text" class="form-control @error('sodienthoai') is-invalid @enderror" id="sodienthoai" name="sodienthoai" value="{{ old('sodienthoai', $nguoidung->sodienthoai) }}" />
                    @error('sodienthoai')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="role">Quyền hạn <span class="text-danger">*</span></label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="user" {{ old('role', $nguoidung->role) === 'user' ? 'selected' : '' }}>Khách hàng (user)</option>
                        <option value="staff" {{ old('role', $nguoidung->role) === 'staff' ? 'selected' : '' }}>Nhân viên (staff)</option>
                        <option value="admin" {{ old('role', $nguoidung->role) === 'admin' ? 'selected' : '' }}>Quản trị viên (admin)</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="diachi">Địa chỉ</label>
                    <input type="text" class="form-control @error('diachi') is-invalid @enderror" id="diachi" name="diachi" value="{{ old('diachi', $nguoidung->diachi) }}" />
                    @error('diachi')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-12">
                    <hr class="my-1" />
                    <small class="text-muted">Chỉ nhập 2 ô bên dưới nếu bạn muốn đổi mật khẩu mới (bỏ trống để giữ mật khẩu cũ).</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password">Mật khẩu mới</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" />
                    @error('password')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password_confirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" />
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Cập nhật</button>
                <a href="{{ route('admin.nguoidung') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection