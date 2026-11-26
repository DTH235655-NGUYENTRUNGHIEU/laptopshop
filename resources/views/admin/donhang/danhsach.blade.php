@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-file-invoice text-primary me-1"></i>
            Danh sách Đơn hàng ({{ $donhang->total() }} đơn)
        </span>
        <a href="{{ route('admin.hoadon') }}" class="btn btn-outline-success btn-sm">
            <i class="fa-solid fa-receipt"></i> Xem danh sách Hóa đơn
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.donhang') }}" method="get" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="tukhoa" class="form-control form-control-sm" value="{{ request('tukhoa') }}" placeholder="Tìm theo mã đơn, tên người nhận, số điện thoại..." />
            </div>
            <div class="col-md-4">
                <select name="tinhtrang_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả tình trạng --</option>
                    @foreach($tinhtrang as $tt)
                        <option value="{{ $tt->id }}" {{ request('tinhtrang_id') == $tt->id ? 'selected' : '' }}>
                            {{ $tt->tinhtrang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Lọc đơn
                </button>
                <a href="{{ route('admin.donhang') }}" class="btn btn-outline-secondary btn-sm" title="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="14%">Mã đơn / Ngày đặt</th>
                        <th width="42%">Thông tin giao hàng & Sản phẩm</th>
                        <th width="14%" class="text-end">Thanh toán</th>
                        <th width="12%" class="text-center">Tình trạng</th>
                        <th width="8%" class="text-center">Hóa đơn</th>
                        <th width="3%" class="text-center">Sửa</th>
                        <th width="3%" class="text-center">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donhang as $value)
                        <tr>
                            <td class="text-center">{{ $donhang->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-bold font-monospace text-primary">{{ $value->madonhang }}</div>
                                <div class="small text-muted">{{ $value->created_at ? $value->created_at->format('d/m/Y H:i') : '-' }}</div>
                                <div class="small text-muted">TK: {{ $value->User->name ?? 'Khách' }}</div>
                            </td>
                            <td class="small">
                                <div><strong>Người nhận:</strong> {{ $value->hoten_nguoinhan }} — <strong>{{ $value->dienthoaigiaohang }}</strong></div>
                                <div class="text-muted mb-1"><strong>Địa chỉ:</strong> {{ $value->diachigiaohang }}</div>
                                @if($value->ghichu)
                                    <div class="text-info mb-1"><i class="fa-solid fa-note-sticky"></i> {{ $value->ghichu }}</div>
                                @endif
                                <table class="table table-bordered table-sm mb-0 bg-white">
                                    <tbody>
                                        @foreach($value->DonHang_ChiTiet as $ct)
                                            <tr>
                                                <td>{{ $ct->SanPham->tensanpham ?? 'Sản phẩm đã xóa' }}</td>
                                                <td width="10%" class="text-center">x{{ $ct->soluongban }}</td>
                                                <td width="26%" class="text-end">{{ number_format($ct->dongiaban * $ct->soluongban, 0, ',', '.') }}đ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                            <td class="text-end small">
                                @if($value->tiengiam > 0)
                                    <div class="text-success">Giảm: -{{ number_format($value->tiengiam, 0, ',', '.') }}đ</div>
                                @endif
                                <div class="fw-bold text-danger fs-6">{{ number_format($value->tongtien, 0, ',', '.') }}đ</div>
                                <div class="mt-1">
                                    <span class="badge bg-light text-dark border">{{ $value->phuongthuc_thanhtoan }}</span>
                                    @if($value->trangthai_thanhtoan)
                                        <span class="badge bg-success">Đã TT</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Chưa TT</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeClass = match ((int) $value->tinhtrang_id) {
                                        1 => 'bg-warning text-dark',
                                        2, 3 => 'bg-info text-dark',
                                        4 => 'bg-primary',
                                        5 => 'bg-success',
                                        6 => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $value->TinhTrang->tinhtrang ?? 'Không rõ' }}</span>
                            </td>
                            <td class="text-center">
                                @if($value->HoaDon)
                                    <a href="{{ route('admin.hoadon.chitiet', ['id' => $value->HoaDon->id]) }}"
                                       class="btn btn-outline-success btn-sm py-0 px-2"
                                       title="Xem hóa đơn {{ $value->HoaDon->sohoadon }}">
                                        <i class="fa-solid fa-receipt"></i> Xem HĐ
                                    </a>
                                @elseif((int) $value->tinhtrang_id !== 6)
                                    <a href="{{ route('admin.hoadon.lap', ['donhang_id' => $value->id]) }}"
                                       class="btn btn-primary btn-sm py-0 px-2"
                                       title="Xuất hóa đơn VAT & Serial">
                                        <i class="fa-solid fa-file-circle-plus"></i> Xuất HĐ
                                    </a>
                                @else
                                    <span class="text-muted small">Đã hủy</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.donhang.sua', ['id' => $value->id]) }}" class="text-primary" title="Cập nhật đơn">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                @if(!$value->HoaDon)
                                    <a href="{{ route('admin.donhang.xoa', ['id' => $value->id]) }}"
                                       class="text-danger"
                                       title="Xóa đơn hàng"
                                       onclick="return confirm('Bạn có chắc muốn xóa đơn hàng {{ $value->madonhang }} không?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                @else
                                    <span class="text-muted" title="Đã xuất hóa đơn, không thể xóa"><i class="fa-solid fa-lock"></i></span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Không tìm thấy đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $donhang->links() }}
        </div>
    </div>
</div>
@endsection