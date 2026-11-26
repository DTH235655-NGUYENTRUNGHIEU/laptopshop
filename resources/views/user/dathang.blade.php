@extends('layouts.frontend')

@section('title', 'Thanh toán đơn hàng')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.giohang') }}">Giỏ hàng</a></li>
            <li class="breadcrumb-item active">Thanh toán đơn hàng</li>
        </ol>
    </nav>

    <h1 class="h4 fw-bold mb-4"><i class="fa-solid fa-bag-shopping text-primary me-1"></i> Xác nhận đặt hàng</h1>

    <form action="{{ route('user.dathang') }}" method="post">
        @csrf
        <div class="row g-4">
            <!-- Form thông tin nhận hàng & thanh toán -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-bold">1. Thông tin người nhận hàng</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" for="hoten_nguoinhan">Họ tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('hoten_nguoinhan') is-invalid @enderror" id="hoten_nguoinhan" name="hoten_nguoinhan" value="{{ old('hoten_nguoinhan', $user->name) }}" required />
                                @error('hoten_nguoinhan') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold" for="dienthoaigiaohang">Điện thoại giao hàng <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('dienthoaigiaohang') is-invalid @enderror" id="dienthoaigiaohang" name="dienthoaigiaohang" value="{{ old('dienthoaigiaohang', $user->sodienthoai) }}" placeholder="VD: 0987654321" required />
                                @error('dienthoaigiaohang') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold" for="diachigiaohang">Địa chỉ giao hàng chi tiết <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('diachigiaohang') is-invalid @enderror" id="diachigiaohang" name="diachigiaohang" value="{{ old('diachigiaohang', $user->diachi) }}" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành..." required />
                                @error('diachigiaohang') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold" for="ghichu">Ghi chú đơn hàng (tùy chọn)</label>
                                <textarea class="form-control" id="ghichu" name="ghichu" rows="2" placeholder="VD: Cài sẵn Windows/Office, xuất hóa đơn VAT công ty, giao giờ hành chính...">{{ old('ghichu') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">2. Phương thức thanh toán</div>
                    <div class="card-body d-grid gap-2 small">
                        <label class="border rounded p-3 d-flex align-items-start gap-2" style="cursor: pointer;">
                            <input type="radio" class="form-check-input mt-1" name="phuongthuc_thanhtoan" value="COD" {{ old('phuongthuc_thanhtoan', 'COD') === 'COD' ? 'checked' : '' }} />
                            <div>
                                <div class="fw-semibold">Thanh toán khi nhận hàng (COD)</div>
                                <div class="text-muted">Kiểm tra ngoại hình, số Serial máy rồi mới thanh toán tiền mặt cho nhân viên giao hàng.</div>
                            </div>
                        </label>

                        <label class="border rounded p-3 d-flex align-items-start gap-2" style="cursor: pointer;">
                            <input type="radio" class="form-check-input mt-1" name="phuongthuc_thanhtoan" value="CHUYENKHOAN" {{ old('phuongthuc_thanhtoan') === 'CHUYENKHOAN' ? 'checked' : '' }} />
                            <div>
                                <div class="fw-semibold">Chuyển khoản ngân hàng (VietQR)</div>
                                <div class="text-muted">Vietcombank — STK: <strong>0123456789</strong> — Chủ TK: <strong>CONG TY LAPTOPSHOP</strong>.</div>
                            </div>
                        </label>

                        <label class="border rounded p-3 d-flex align-items-start gap-2" style="cursor: pointer;">
                            <input type="radio" class="form-check-input mt-1" name="phuongthuc_thanhtoan" value="VNPAY" {{ old('phuongthuc_thanhtoan') === 'VNPAY' ? 'checked' : '' }} />
                            <div>
                                <div class="fw-semibold">Thanh toán qua cổng VNPay / Thẻ ATM nội địa</div>
                                <div class="text-muted">Hỗ trợ quét mã QR ngân hàng và thẻ quốc tế Visa/Mastercard.</div>
                            </div>
                        </label>

                        <label class="border rounded p-3 d-flex align-items-start gap-2" style="cursor: pointer;">
                            <input type="radio" class="form-check-input mt-1" name="phuongthuc_thanhtoan" value="MOMO" {{ old('phuongthuc_thanhtoan') === 'MOMO' ? 'checked' : '' }} />
                            <div>
                                <div class="fw-semibold">Thanh toán qua Ví điện tử MoMo</div>
                                <div class="text-muted">Xác nhận thanh toán nhanh chóng qua ứng dụng MoMo.</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Tóm tắt sản phẩm & Đặt hàng -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                        <span>Đơn hàng của bạn ({{ \Gloudemans\Shoppingcart\Facades\Cart::count() }})</span>
                        <a href="{{ route('frontend.giohang') }}" class="small text-decoration-none">Chỉnh sửa</a>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3 mb-3">
                            @foreach(\Gloudemans\Shoppingcart\Facades\Cart::content() as $item)
                                <div class="d-flex justify-content-between align-items-start border-bottom pb-2 small">
                                    <div class="pe-2">
                                        <div class="fw-semibold">{{ $item->name }}</div>
                                        <div class="text-muted">SL: {{ $item->qty }} × {{ number_format($item->price, 0, ',', '.') }}đ</div>
                                    </div>
                                    <div class="fw-semibold text-nowrap">{{ number_format($item->price * $item->qty, 0, ',', '.') }}đ</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Tạm tính:</span>
                            <span>{{ number_format($tamTinh, 0, ',', '.') }}đ</span>
                        </div>

                        @if($tienGiam > 0 && $voucher)
                            <div class="d-flex justify-content-between mb-2 small text-success">
                                <span>Voucher (<code>{{ $voucher['macode'] }}</code>):</span>
                                <span>-{{ number_format($tienGiam, 0, ',', '.') }}đ</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Phí giao hàng:</span>
                            <span class="text-success fw-semibold">Miễn phí</span>
                        </div>

                        <hr class="my-3" />

                        <div class="d-flex justify-content-between align-items-baseline mb-4">
                            <span class="fw-bold">Tổng thanh toán:</span>
                            <span class="fs-4 fw-bold text-danger">{{ number_format($tongCong, 0, ',', '.') }}đ</span>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg fw-semibold">
                                <i class="fa-solid fa-check-circle me-1"></i> Hoàn tất đặt hàng
                            </button>
                        </div>
                        <div class="small text-muted text-center mt-2">
                            Email xác nhận đơn hàng sẽ được gửi về <strong>{{ $user->email }}</strong>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection