@extends('layouts.frontend')

@section('title', 'Hồ sơ cá nhân & Đổi mật khẩu')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Tài khoản</a></li>
            <li class="breadcrumb-item active">Hồ sơ cá nhân</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('user._sidebar')
        </aside>

        <div class="col-lg-9">
            <!-- Form cập nhật hồ sơ -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    <i class="fa-solid fa-id-card text-primary me-1"></i> Thông tin cá nhân
                </div>
                <div class="card-body">
                    <form action="{{ route('user.hoso') }}" method="post">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" for="name">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required />
                                @error('name') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" for="email">Địa chỉ Email</label>
                                <input type="email" class="form-control bg-light" id="email" value="{{ $user->email }}" readonly />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" for="username">Tên đăng nhập</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" />
                                @error('username') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" for="sodienthoai">Số điện thoại mặc định</label>
                                <input type="text" class="form-control @error('sodienthoai') is-invalid @enderror" id="sodienthoai" name="sodienthoai" value="{{ old('sodienthoai', $user->sodienthoai) }}" />
                                @error('sodienthoai') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold" for="diachi">Địa chỉ giao hàng mặc định</label>
                                <input type="text" class="form-control @error('diachi') is-invalid @enderror" id="diachi" name="diachi" value="{{ old('diachi', $user->diachi) }}" />
                                @error('diachi') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form đổi mật khẩu -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="fa-solid fa-key text-warning me-1"></i> Đổi mật khẩu
                </div>
                <div class="card-body">
                    <form action="{{ route('user.doimatkhau') }}" method="post">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold" for="current_password">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required />
                                @error('current_password') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold" for="password">Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required />
                                @error('password') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold" for="password_confirmation">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required />
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-warning btn-sm px-4 fw-semibold">
                                <i class="fa-solid fa-shield-halved me-1"></i> Cập nhật mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection