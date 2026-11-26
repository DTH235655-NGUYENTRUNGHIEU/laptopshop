@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-receipt text-primary me-1"></i>
            Danh sách Hóa đơn bán hàng ({{ $hoadon->total() }} hóa đơn)
        </span>
        <a href="{{ route('admin.donhang') }}" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-file-invoice"></i> Xuất HĐ từ Đơn hàng
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.hoadon') }}" method="get" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="tukhoa" class="form-control form-control-sm" value="{{ request('tukhoa') }}" placeholder="Tìm theo số hóa đơn, khách hàng, SĐT, mã số thuế..." />
            </div>
            <div class="col-md-4">
                <select name="trangthai" class="form-select form-select-sm">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="da_thanh_toan" {{ request('trangthai') === 'da_thanh_toan' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="chua_thanh_toan" {{ request('trangthai') === 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="da_huy" {{ request('trangthai') === 'da_huy' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Lọc hóa đơn
                </button>
                <a href="{{ route('admin.hoadon') }}" class="btn btn-outline-secondary btn-sm" title="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="14%">Số hóa đơn</th>
                        <th width="12%">Đơn hàng gốc</th>
                        <th width="22%">Khách hàng / Công ty VAT</th>
                        <th width="13%" class="text-center">Ngày lập</th>
                        <th width="13%" class="text-end">Tổng thanh toán</th>
                        <th width="11%" class="text-center">Trạng thái</th>
                        <th width="11%" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hoadon as $value)
                        <tr>
                            <td class="text-center">{{ $hoadon->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-bold font-monospace text-primary">{{ $value->sohoadon }}</div>
                                <div class="small text-muted">NV: {{ $value->NhanVien->name ?? '-' }}</div>
                            </td>
                            <td class="small">
                                @if($value->DonHang)
                                    <code>{{ $value->DonHang->madonhang }}</code>
                                @else
                                    <span class="badge bg-light text-secondary border">Bán tại quầy</span>
                                @endif
                            </td>
                            <td class="small">
                                <div class="fw-semibold">{{ $value->ten_khachhang }} ({{ $value->sodienthoai }})</div>
                                @if($value->ten_congty)
                                    <div class="text-primary">{{ $value->ten_congty }} — MST: <code>{{ $value->masothue }}</code></div>
                                @endif
                            </td>
                            <td class="text-center small">{{ $value->ngaylaphoadon ? $value->ngaylaphoadon->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-end">
                                <div class="fw-bold text-danger">{{ number_format($value->tongthanhtoan, 0, ',', '.') }}đ</div>
                                <div class="small text-muted">VAT {{ $value->thue_vat_phantram }}%</div>
                            </td>
                            <td class="text-center">
                                @if($value->trangthai === 'da_thanh_toan')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($value->trangthai === 'chua_thanh_toan')
                                    <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.hoadon.chitiet', ['id' => $value->id]) }}" class="btn btn-outline-primary btn-sm py-0 px-2" title="Chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.hoadon.in', ['id' => $value->id]) }}" target="_blank" class="btn btn-outline-dark btn-sm py-0 px-2" title="In hóa đơn">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Chưa có hóa đơn nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $hoadon->links() }}
        </div>
    </div>
</div>
@endsection