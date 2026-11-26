@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-boxes-stacked text-primary me-1"></i> Danh sách Phiếu nhập kho</span>
        <a href="{{ route('admin.phieunhap.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Lập phiếu nhập mới
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="15%">Mã phiếu</th>
                    <th width="25%">Nhà cung cấp</th>
                    <th width="16%">Nhân viên lập</th>
                    <th width="14%" class="text-center">Ngày nhập</th>
                    <th width="7%" class="text-center">Dòng</th>
                    <th width="10%" class="text-end">Tổng tiền</th>
                    <th width="4%" class="text-center">Xem</th>
                    <th width="4%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($phieunhap as $value)
                    <tr>
                        <td class="text-center">{{ $phieunhap->firstItem() + $loop->index }}</td>
                        <td><code class="fw-bold">{{ $value->maphieunhap }}</code></td>
                        <td class="fw-semibold">{{ $value->NhaCungCap->ten_nhacungcap ?? '-' }}</td>
                        <td>{{ $value->NhanVien->name ?? '-' }}</td>
                        <td class="text-center small">{{ $value->ngaynhap ? $value->ngaynhap->format('d/m/Y H:i') : '-' }}</td>
                        <td class="text-center"><span class="badge bg-info text-dark">{{ $value->phieu_nhap__chi_tiet_count }}</span></td>
                        <td class="text-end fw-bold text-danger">{{ number_format($value->tongtien, 0, ',', '.') }}đ</td>
                        <td class="text-center">
                            <a href="{{ route('admin.phieunhap.chitiet', ['id' => $value->id]) }}" class="text-primary" title="Xem chi tiết">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.phieunhap.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa & hoàn kho"
                               onclick="return confirm('Xóa phiếu {{ $value->maphieunhap }} sẽ tự động trừ lại số lượng tồn kho tương ứng. Bạn có chắc chắn không?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">Chưa có phiếu nhập kho nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3 d-flex justify-content-end">
            {{ $phieunhap->links() }}
        </div>
    </div>
</div>
@endsection