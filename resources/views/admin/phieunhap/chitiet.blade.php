@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-file-lines text-primary me-1"></i>
            Chi tiết Phiếu nhập kho: <code>{{ $phieunhap->maphieunhap }}</code>
        </span>
        <a href="{{ route('admin.phieunhap') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100 bg-light">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Thông tin Nhà cung cấp</div>
                    <div class="fw-bold">{{ $phieunhap->NhaCungCap->ten_nhacungcap ?? '-' }}</div>
                    <div class="small">SĐT: {{ $phieunhap->NhaCungCap->sodienthoai ?? '-' }} | MST: {{ $phieunhap->NhaCungCap->masothue ?? '-' }}</div>
                    <div class="small text-muted">Địa chỉ: {{ $phieunhap->NhaCungCap->diachi ?? '-' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100 bg-light">
                    <div class="small text-muted text-uppercase fw-semibold mb-1">Thông tin Phiếu nhập</div>
                    <div><strong>Ngày nhập:</strong> {{ $phieunhap->ngaynhap ? $phieunhap->ngaynhap->format('d/m/Y H:i') : '-' }}</div>
                    <div><strong>Nhân viên lập:</strong> {{ $phieunhap->NhanVien->name ?? '-' }}</div>
                    <div><strong>Ghi chú:</strong> {{ $phieunhap->ghichu ?: 'Không có' }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="16%">Mã SKU</th>
                        <th>Tên sản phẩm</th>
                        <th width="12%" class="text-center">SL nhập</th>
                        <th width="18%" class="text-end">Giá vốn nhập</th>
                        <th width="18%" class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($phieunhap->PhieuNhap_ChiTiet as $ct)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td><code>{{ $ct->SanPham->masanpham ?? '-' }}</code></td>
                            <td class="fw-semibold">
                                {{ $ct->SanPham->tensanpham ?? 'Sản phẩm đã xóa' }}
                                <span class="text-muted small">({{ $ct->SanPham->HangSanXuat->tenhang ?? '' }})</span>
                            </td>
                            <td class="text-center fw-bold">{{ $ct->soluong_nhap }}</td>
                            <td class="text-end">{{ number_format($ct->gianhap, 0, ',', '.') }}đ</td>
                            <td class="text-end fw-semibold">{{ number_format($ct->thanhtien, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Tổng giá trị lô hàng nhập:</td>
                        <td class="text-end fw-bold text-danger fs-5">{{ number_format($phieunhap->tongtien, 0, ',', '.') }}đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection