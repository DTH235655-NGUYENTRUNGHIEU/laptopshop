@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-ticket text-primary me-1"></i> Danh sách Mã giảm giá (Voucher)</span>
        <a href="{{ route('admin.magiamgia.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Thêm mã mới
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="4%" class="text-center">#</th>
                    <th width="14%">Mã Code</th>
                    <th width="13%" class="text-end">Mức giảm</th>
                    <th width="15%" class="text-end">Đơn tối thiểu</th>
                    <th width="9%" class="text-center">Còn lại</th>
                    <th width="9%" class="text-center">Đã dùng</th>
                    <th width="18%" class="text-center">Thời gian áp dụng</th>
                    <th width="8%" class="text-center">Kích hoạt</th>
                    <th width="5%" class="text-center">Sửa</th>
                    <th width="5%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($magiamgia as $value)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><span class="badge bg-dark fs-6 font-monospace">{{ $value->macode }}</span></td>
                        <td class="text-end fw-bold text-danger">
                            @if($value->loai === 'percent')
                                {{ $value->giatri }}%
                            @else
                                {{ number_format($value->giatri, 0, ',', '.') }}đ
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($value->donhang_toithieu, 0, ',', '.') }}đ</td>
                        <td class="text-center">
                            <span class="badge {{ $value->soluong > 0 ? 'bg-success' : 'bg-secondary' }}">{{ $value->soluong }}</span>
                        </td>
                        <td class="text-center"><span class="badge bg-info text-dark">{{ $value->don_hang_count }}</span></td>
                        <td class="text-center small">
                            <div>{{ $value->ngaybatdau ? $value->ngaybatdau->format('d/m/Y H:i') : 'Không giới hạn' }}</div>
                            <div class="text-muted">đến {{ $value->ngayketthuc ? $value->ngayketthuc->format('d/m/Y H:i') : 'Không giới hạn' }}</div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.magiamgia.kichhoat', ['id' => $value->id]) }}" title="Bật / Tắt">
                                @if($value->kichhoat)
                                    <i class="fa-solid fa-circle-check text-success fa-lg"></i>
                                @else
                                    <i class="fa-solid fa-circle-xmark text-danger fa-lg"></i>
                                @endif
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.magiamgia.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.magiamgia.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa"
                               onclick="return confirm('Bạn có muốn xóa mã {{ $value->macode }} không?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-3">Chưa có mã giảm giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection