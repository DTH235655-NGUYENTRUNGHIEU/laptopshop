@extends('layouts.app')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-uppercase opacity-75">Tổng doanh thu (HĐ)</div>
                        <h4 class="fw-bold mb-0 mt-1">{{ number_format($tongDoanhThu, 0, ',', '.') }}đ</h4>
                    </div>
                    <i class="fa-solid fa-sack-dollar fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-uppercase opacity-75">Đơn hàng ({{ $donHangChoDuyet }} chờ duyệt)</div>
                        <h4 class="fw-bold mb-0 mt-1">{{ $tongDonHang }}</h4>
                    </div>
                    <i class="fa-solid fa-cart-shopping fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-uppercase opacity-75">Tổng mẫu Laptop</div>
                        <h4 class="fw-bold mb-0 mt-1">{{ $tongSanPham }}</h4>
                    </div>
                    <i class="fa-solid fa-laptop fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-uppercase opacity-75">Khách hàng / Liên hệ mới</div>
                        <h4 class="fw-bold mb-0 mt-1">{{ $tongKhachHang }} / {{ $lienHeChuaXuLy }}</h4>
                    </div>
                    <i class="fa-solid fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">
                <i class="fa-solid fa-chart-column text-primary me-1"></i>
                Biểu đồ doanh thu năm {{ $namHienTai }} (VNĐ)
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="110" data-revenue="{{ json_encode($doanhThuTheoThang) }}"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">
                <i class="fa-solid fa-triangle-exclamation text-danger me-1"></i>
                Laptop tồn kho thấp (dưới hoặc bằng 10)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Sản phẩm</th>
                                <th class="text-end pe-3">Tồn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sanPhamSapHet as $sp)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold small">{{ $sp->tensanpham }}</div>
                                        <span class="text-muted small">{{ $sp->HangSanXuat->tenhang ?? '' }}</span>
                                    </td>
                                    <td class="text-end pe-3 align-middle">
                                        <span class="badge {{ $sp->soluong <= 5 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                            {{ $sp->soluong }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Tồn kho đều ở mức an toàn.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-clock-rotate-left text-primary me-1"></i>
        5 đơn hàng gần nhất
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Mã đơn</th>
                        <th>Người nhận</th>
                        <th>Điện thoại</th>
                        <th>Ngày đặt</th>
                        <th class="text-end">Tổng tiền</th>
                        <th class="text-center pe-3">Tình trạng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donHangMoiNhat as $dh)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $dh->madonhang }}</td>
                            <td>{{ $dh->hoten_nguoinhan }}</td>
                            <td>{{ $dh->dienthoaigiaohang }}</td>
                            <td>{{ $dh->created_at ? $dh->created_at->format('d/m/Y H:i') : '' }}</td>
                            <td class="text-end fw-semibold text-danger">{{ number_format($dh->tongtien, 0, ',', '.') }}đ</td>
                            <td class="text-center pe-3">
                                <span class="badge bg-secondary">{{ $dh->TinhTrang->tinhtrang ?? 'Không rõ' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    const doanhThuTheoThang = JSON.parse(ctx.dataset.revenue || '[]');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Th1','Th2','Th3','Th4','Th5','Th6','Th7','Th8','Th9','Th10','Th11','Th12'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: doanhThuTheoThang,
                backgroundColor: 'rgba(13, 110, 253, 0.75)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => value.toLocaleString('vi-VN') + 'đ'
                    }
                }
            }
        }
    });
</script>
@endsection