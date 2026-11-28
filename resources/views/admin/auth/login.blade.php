<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Đăng nhập Quản trị - {{ config('app.name', 'LaptopShop') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>
<body class="bg-dark d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="text-primary mb-2">
                                <i class="fa-solid fa-user-shield fa-3x"></i>
                            </div>
                            <h1 class="h5 fw-bold mb-1">Quản trị {{ config('app.name', 'LaptopShop') }}</h1>
                            <div class="small text-muted">Phiên đăng nhập riêng dành cho Admin & Nhân viên</div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('admin.login.post') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-semibold">Email quản trị</label>
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}"
                                       placeholder="admin@laptopshop.vn" required autofocus />
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small fw-semibold">Mật khẩu</label>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password" required />
                                @error('password')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                                <label class="form-check-label small" for="remember">Ghi nhớ phiên quản trị</label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary fw-semibold">
                                    <i class="fa-solid fa-right-to-bracket me-1"></i> Đăng nhập Quản trị
                                </button>
                            </div>

                            <div class="text-center small">
                                <a href="{{ route('frontend.home') }}" class="text-decoration-none text-muted">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại trang bán hàng
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>