@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-receipt text-primary me-1"></i>
            Chi tiết Hóa đơn: <code>{{ $hoadon->sohoadon }}</code>
            @if($hoadon->trangthai === 'da_thanh_toan')
                <span class="badge bg-success ms-1">Đã thanh toán</span>
            @elseif($hoadon->trangthai === 'chua_thanh_toan')
                <span class="badge bg-warning text-dark ms-1">Chưa thanh toán</span>
            @else
                <span class="badge bg-danger ms-1">Đã hủy</span>
            @endif
        </span>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.hoadon.in', ['id' => $hoadon->id]) }}" target="_blank" class="btn btn-dark btn-sm">
                <i class="fa-solid fa-print"></i> In hóa đơn A4
            </a>
            @if($hoadon->trangthai !== 'da_huy')
                <form action="{{ route('admin.hoadon.huy', ['id' => $hoadon->id]) }}" method="post" class="d-inline"
                      onsubmit="return confirm('Hủy hóa đơn {{ $hoadon->sohoadon }} sẽ tự động cộng hoàn lại tồn kho các máy đã xuất. Bạn có chắc chắn không?')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-ban"></i> Hủy hóa đơn & Hoàn kho
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.hoadon') }}" class="btn btn-secondary btn-sm">Quay lại</a>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100 bg-light small">
                    <div class="text-muted text-uppercase fw-semibold mb-2">Thông tin Khách hàng / VAT</div>
                    <div><strong>Khách hàng:</strong> {{ $hoadon->ten_khachhang }}</div>
                    <div><strong>Điện thoại:</strong> {{ $hoadon->sodienthoai }}</div>
                    <div><strong>Địa chỉ:</strong> {{ $hoadon->diachi }}</div>
                    @if($hoadon->ten_congty)
                        <div class="text-primary mt-1"><strong>Công ty:</strong> {{ $hoadon->ten_congty }}</div>
                        <div class="text-primary"><strong>Mã số thuế:</strong> <code>{{ $hoadon->masothue }}</code></div>
                    @endif
                    @if($hoadon->email_nhanhoadon)
                        <div><strong>Email nhận HĐ:</strong> {{ $hoadon->email_nhanhoadon }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100 bg-light small">
                    <div class="text-muted text-uppercase fw-semibold mb-2">Thông tin Chứng từ</div>
                    <div><strong>Ngày lập:</strong> {{ $hoadon->ngaylaphoadon ? $hoadon->ngaylaphoadon->format('d/m/Y H:i') : '-' }}</div>
                    <div><strong>Nhân viên xuất:</strong> {{ $hoadon->NhanVien->name ?? '-' }}</div>
                    <div>
                        <strong>Đơn hàng liên kết:</strong>
                        @if($hoadon->DonHang)
                            <a href="{{ route('admin.donhang.sua', ['id' => $hoadon->DonHang->id]) }}"><code>{{ $hoadon->DonHang->madonhang }}</code></a>
                        @else
                            <span class="text-muted">Bán trực tiếp tại cửa hàng</span>
                        @endif
                    </div>
                    <div><strong>Thanh toán:</strong> {{ $hoadon->phuongthuc_thanhtoan }}</div>
                    <div><strong>Ghi chú:</strong> {{ $hoadon->ghichu ?: 'Không có' }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>Tên Laptop (lưu thời điểm bán)</th>
                        <th width="18%">Serial Number (S/N)</th>
                        <th width="14%" class="text-center">Bảo hành đến</th>
                        <th width="7%" class="text-center">SL</th>
                        <th width="14%" class="text-end">Đơn giá</th>
                        <th width="15%" class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hoadon->HoaDon_ChiTiet as $ct)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $ct->tensanpham_luu }}</td>
                            <td><code>{{ $ct->serial_number ?: 'Chưa ghi nhận' }}</code></td>
                            <td class="text-center small">
                                {{ $ct->baohanh_thang }} tháng
                                @if($ct->han_baohanh)
                                    <div class="text-muted">({{ $ct->han_baohanh->format('d/m/Y') }})</div>
                                @endif
                            </td>
                            <td class="text-center">{{ $ct->soluong }}</td>
                            <td class="text-end">{{ number_format($ct->dongia, 0, ',', '.') }}đ</td>
                            <td class="text-end fw-semibold">{{ number_format($ct->thanhtien, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="6" class="text-end">Tạm tính:</td>
                        <td class="text-end">{{ number_format($hoadon->tamtinh, 0, ',', '.') }}đ</td>
                    </tr>
                    @if($hoadon->tiengiam > 0)
                        <tr>
                            <td colspan="6" class="text-end text-success">Giảm giá:</td>
                            <td class="text-end text-success">-{{ number_format($hoadon->tiengiam, 0, ',', '.') }}đ</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="6" class="text-end">Thuế VAT ({{ $hoadon->thue_vat_phantram }}%):</td>
                        <td class="text-end">{{ number_format($hoadon->tienthue_vat, 0, ',', '.') }}đ</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-end fw-bold fs-6">Tổng tiền thanh toán:</td>
                        <td class="text-end fw-bold text-danger fs-5">{{ number_format($hoadon->tongthanhtoan, 0, ',', '.') }}đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection