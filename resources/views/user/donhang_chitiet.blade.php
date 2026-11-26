@extends('layouts.frontend')

@section('title', 'Chi tiết đơn hàng ' . $donhang->madonhang)

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.donhang') }}">Lịch sử đơn hàng</a></li>
            <li class="breadcrumb-item active">{{ $donhang->madonhang }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('user._sidebar')
        </aside>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <span class="fw-bold">Đơn hàng <code class="fs-6">{{ $donhang->madonhang }}</code></span>
                        <span class="badge bg-primary ms-2">{{ $donhang->TinhTrang->tinhtrang ?? '' }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        @if((int) $donhang->tinhtrang_id === 1 && !$donhang->HoaDon)
                            <form action="{{ route('user.donhang.huy', ['id' => $donhang->id]) }}" method="post"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng {{ $donhang->madonhang }} không?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fa-solid fa-xmark me-1"></i> Hủy đơn hàng
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('user.donhang') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3 small mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="text-muted text-uppercase fw-semibold mb-1">Thông tin nhận hàng</div>
                                <div><strong>Người nhận:</strong> {{ $donhang->hoten_nguoinhan }}</div>
                                <div><strong>Điện thoại:</strong> {{ $donhang->dienthoaigiaohang }}</div>
                                <div><strong>Địa chỉ:</strong> {{ $donhang->diachigiaohang }}</div>
                                @if($donhang->ghichu)
                                    <div><strong>Ghi chú:</strong> {{ $donhang->ghichu }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="text-muted text-uppercase fw-semibold mb-1">Thanh toán & Thời gian</div>
                                <div><strong>Ngày đặt:</strong> {{ $donhang->created_at ? $donhang->created_at->format('d/m/Y H:i') : '-' }}</div>
                                <div><strong>Phương thức:</strong> {{ $donhang->phuongthuc_thanhtoan }}</div>
                                <div>
                                    <strong>Trạng thái TT:</strong>
                                    @if($donhang->trangthai_thanhtoan)
                                        <span class="badge bg-success">Đã thanh toán</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle small mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="6%" class="text-center">#</th>
                                    <th>Tên Laptop</th>
                                    <th width="10%" class="text-center">SL</th>
                                    <th width="18%" class="text-end">Đơn giá</th>
                                    <th width="18%" class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $tamTinh = 0; @endphp
                                @foreach($donhang->DonHang_ChiTiet as $ct)
                                    @php
                                        $thanhTien = $ct->soluongban * $ct->dongiaban;
                                        $tamTinh += $thanhTien;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ $ct->SanPham->tensanpham ?? 'Sản phẩm' }}</td>
                                        <td class="text-center">{{ $ct->soluongban }}</td>
                                        <td class="text-end">{{ number_format($ct->dongiaban, 0, ',', '.') }}đ</td>
                                        <td class="text-end fw-semibold">{{ number_format($thanhTien, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end">Tạm tính:</td>
                                    <td class="text-end">{{ number_format($tamTinh, 0, ',', '.') }}đ</td>
                                </tr>
                                @if($donhang->tiengiam > 0)
                                    <tr>
                                        <td colspan="4" class="text-end text-success">
                                            Giảm giá {{ $donhang->MaGiamGia ? '(' . $donhang->MaGiamGia->macode . ')' : '' }}:
                                        </td>
                                        <td class="text-end text-success">-{{ number_format($donhang->tiengiam, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Tổng cộng đơn hàng:</td>
                                    <td class="text-end fw-bold text-danger fs-6">{{ number_format($donhang->tongtien, 0, ',', '.') }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Thông tin Hóa đơn & Serial Number bảo hành điện tử (nếu shop đã xuất HĐ) -->
            @if($donhang->HoaDon && $donhang->HoaDon->trangthai !== 'da_huy')
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success-subtle text-success fw-bold small">
                        <i class="fa-solid fa-shield-halved me-1"></i>
                        Thông tin Hóa đơn VAT ({{ $donhang->HoaDon->sohoadon }}) & Bảo hành điện tử theo Serial
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Tên Laptop</th>
                                        <th width="24%">Serial Number (S/N)</th>
                                        <th width="22%" class="text-center">Hạn bảo hành</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donhang->HoaDon->HoaDon_ChiTiet as $hdct)
                                        <tr>
                                            <td class="ps-3 fw-semibold">{{ $hdct->tensanpham_luu }}</td>
                                            <td><code>{{ $hdct->serial_number ?: 'Đang cập nhật' }}</code></td>
                                            <td class="text-center">
                                                {{ $hdct->baohanh_thang }} tháng
                                                @if($hdct->han_baohanh)
                                                    (đến <strong>{{ $hdct->han_baohanh->format('d/m/Y') }}</strong>)
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection