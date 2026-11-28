<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Biên nhận bảo hành {{ $phieu->maphieu }} - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" />
    <style>
        body { background: #f5f5f5; font-size: 14px; }
        .paper { max-width: 760px; margin: 24px auto; background: #fff; padding: 32px 38px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); }
        @media print {
            body { background: #fff; }
            .paper { margin: 0; padding: 0; box-shadow: none; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="container text-center mt-3 no-print">
        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">In biên nhận / Lưu PDF</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()">Đóng</button>
    </div>

    <div class="paper">
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
            <div>
                <h4 class="fw-bold text-primary mb-1">{{ config('app.name', 'LaptopShop') }}</h4>
                <div class="small">Trung tâm Kỹ thuật & Bảo hành Laptop Chính Hãng</div>
                <div class="small text-muted">18 Ung Văn Khiêm, TP. Long Xuyên, An Giang | Hotline: 0909 123 456</div>
            </div>
            <div class="text-end">
                <h5 class="fw-bold text-uppercase mb-1">Biên nhận bảo hành</h5>
                <div>Mã phiếu: <strong>{{ $phieu->maphieu }}</strong></div>
                <div class="small">Tiếp nhận: {{ $phieu->ngaytiepnhan ? $phieu->ngaytiepnhan->format('d/m/Y H:i') : '-' }}</div>
            </div>
        </div>

        <table class="table table-bordered table-sm mb-3">
            <tbody>
                <tr>
                    <th width="26%" class="table-light">Khách hàng</th>
                    <td width="34%">{{ $phieu->ten_khachhang }}</td>
                    <th width="18%" class="table-light">Điện thoại</th>
                    <td>{{ $phieu->sodienthoai }}</td>
                </tr>
                <tr>
                    <th class="table-light">Thiết bị Laptop</th>
                    <td colspan="3" class="fw-semibold">{{ $phieu->tensanpham }}</td>
                </tr>
                <tr>
                    <th class="table-light">Serial Number (S/N)</th>
                    <td class="font-monospace">{{ $phieu->serial_number }}</td>
                    <th class="table-light">Trả dự kiến</th>
                    <td>{{ $phieu->ngaytra_dukien ? $phieu->ngaytra_dukien->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <th class="table-light">Phụ kiện kèm theo</th>
                    <td colspan="3">{{ $phieu->phukien_kemtheo ?: 'Chỉ nhận thân máy' }}</td>
                </tr>
                <tr>
                    <th class="table-light">Tình trạng lỗi</th>
                    <td colspan="3">{{ $phieu->mota_loi }}</td>
                </tr>
                <tr>
                    <th class="table-light">Hướng xử lý / Kết quả</th>
                    <td colspan="3">{{ $phieu->ketqua_xuly ?: 'Đang kiểm tra kỹ thuật' }}</td>
                </tr>
                <tr>
                    <th class="table-light">Trạng thái</th>
                    <td><strong>{{ $trangThaiMap[$phieu->trangthai]['label'] ?? $phieu->trangthai }}</strong></td>
                    <th class="table-light">Chi phí phát sinh</th>
                    <td class="fw-bold">{{ number_format($phieu->chiphi_phatsinh, 0, ',', '.') }}đ</td>
                </tr>
            </tbody>
        </table>

        <div class="small text-muted mb-4">
            * Quý khách vui lòng giữ phiếu này hoặc dùng mã phiếu <strong>{{ $phieu->maphieu }}</strong> để tra cứu tiến độ tại website và mang theo khi nhận lại máy.
        </div>

        <div class="row text-center mt-4 pt-2 small">
            <div class="col-6">
                <strong>Khách hàng bàn giao máy</strong>
                <div class="text-muted">(Ký, ghi rõ họ tên)</div>
            </div>
            <div class="col-6">
                <strong>Kỹ thuật viên tiếp nhận</strong>
                <div class="text-muted">({{ $phieu->NhanVien->name ?? 'Ký, ghi rõ họ tên' }})</div>
            </div>
        </div>
    </div>
</body>
</html>