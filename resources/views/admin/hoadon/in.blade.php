<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>In Hóa Đơn {{ $hoadon->sohoadon }} - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" />
    <style>
        body { background: #f5f5f5; font-size: 14px; }
        .invoice-paper {
            max-width: 820px;
            margin: 24px auto;
            background: #fff;
            padding: 36px 42px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }
        @media print {
            body { background: #fff; }
            .invoice-paper { margin: 0; padding: 0; box-shadow: none; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="container text-center mt-3 no-print">
        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">In hóa đơn / Lưu PDF</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">Đóng cửa sổ</button>
    </div>

    <div class="invoice-paper">
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
            <div>
                <h4 class="fw-bold text-primary mb-1">{{ config('app.name', 'LaptopShop') }}</h4>
                <div class="small">Hệ thống bán lẻ Laptop Chính Hãng</div>
                <div class="small text-muted">Địa chỉ: 18 Ung Văn Khiêm, TP. Long Xuyên, An Giang</div>
                <div class="small text-muted">Hotline: 0909 123 456 | Email: contact@laptopshop.vn</div>
            </div>
            <div class="text-end">
                <h5 class="fw-bold text-uppercase mb-1">Hóa đơn bán hàng & Bảo hành</h5>
                <div>Số hóa đơn: <strong>{{ $hoadon->sohoadon }}</strong></div>
                <div class="small">Ngày lập: {{ $hoadon->ngaylaphoadon ? $hoadon->ngaylaphoadon->format('d/m/Y H:i') : '-' }}</div>
                @if($hoadon->trangthai === 'da_huy')
                    <span class="badge bg-danger mt-1">HÓA ĐƠN ĐÃ HỦY</span>
                @endif
            </div>
        </div>

        <div class="row mb-3 small">
            <div class="col-7">
                <div><strong>Khách hàng:</strong> {{ $hoadon->ten_khachhang }}</div>
                <div><strong>Điện thoại:</strong> {{ $hoadon->sodienthoai }}</div>
                <div><strong>Địa chỉ:</strong> {{ $hoadon->diachi }}</div>
                @if($hoadon->ten_congty)
                    <div><strong>Đơn vị (VAT):</strong> {{ $hoadon->ten_congty }}</div>
                    <div><strong>Mã số thuế:</strong> {{ $hoadon->masothue }}</div>
                @endif
            </div>
            <div class="col-5 text-end">
                <div><strong>Nhân viên bán:</strong> {{ $hoadon->NhanVien->name ?? '-' }}</div>
                <div><strong>Thanh toán:</strong> {{ $hoadon->phuongthuc_thanhtoan }}</div>
                <div><strong>Mã đơn hàng:</strong> {{ $hoadon->DonHang->madonhang ?? 'Tại cửa hàng' }}</div>
            </div>
        </div>

        <table class="table table-bordered table-sm align-middle mb-3">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center">STT</th>
                    <th>Tên Laptop</th>
                    <th width="20%">Serial Number</th>
                    <th width="14%" class="text-center">Hạn bảo hành</th>
                    <th width="6%" class="text-center">SL</th>
                    <th width="14%" class="text-end">Đơn giá</th>
                    <th width="15%" class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hoadon->HoaDon_ChiTiet as $ct)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $ct->tensanpham_luu }}</td>
                        <td class="font-monospace small">{{ $ct->serial_number ?: '-' }}</td>
                        <td class="text-center small">
                            {{ $ct->baohanh_thang }}T
                            @if($ct->han_baohanh)
                                ({{ $ct->han_baohanh->format('d/m/Y') }})
                            @endif
                        </td>
                        <td class="text-center">{{ $ct->soluong }}</td>
                        <td class="text-end">{{ number_format($ct->dongia, 0, ',', '.') }}đ</td>
                        <td class="text-end">{{ number_format($ct->thanhtien, 0, ',', '.') }}đ</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-end">Tạm tính:</td>
                    <td class="text-end">{{ number_format($hoadon->tamtinh, 0, ',', '.') }}đ</td>
                </tr>
                @if($hoadon->tiengiam > 0)
                    <tr>
                        <td colspan="6" class="text-end">Giảm giá:</td>
                        <td class="text-end">-{{ number_format($hoadon->tiengiam, 0, ',', '.') }}đ</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="6" class="text-end">Thuế VAT ({{ $hoadon->thue_vat_phantram }}%):</td>
                    <td class="text-end">{{ number_format($hoadon->tienthue_vat, 0, ',', '.') }}đ</td>
                </tr>
                <tr>
                    <td colspan="6" class="text-end fw-bold">TỔNG TIỀN THANH TOÁN:</td>
                    <td class="text-end fw-bold">{{ number_format($hoadon->tongthanhtoan, 0, ',', '.') }}đ</td>
                </tr>
            </tfoot>
        </table>

        @if($hoadon->ghichu)
            <div class="small mb-4"><strong>Ghi chú:</strong> {{ $hoadon->ghichu }}</div>
        @endif

        <div class="row text-center mt-5 pt-2 small">
            <div class="col-6">
                <strong>Người mua hàng</strong>
                <div class="text-muted">(Ký, ghi rõ họ tên)</div>
            </div>
            <div class="col-6">
                <strong>Đại diện cửa hàng</strong>
                <div class="text-muted">(Ký, đóng dấu)</div>
            </div>
        </div>
    </div>
</body>
</html>