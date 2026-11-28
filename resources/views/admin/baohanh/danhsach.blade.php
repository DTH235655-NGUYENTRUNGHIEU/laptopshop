@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-screwdriver-wrench text-primary me-1"></i>
            Quản lý Phiếu bảo hành ({{ $phieubaohanh->total() }} phiếu)
        </span>
        <a href="{{ route('admin.baohanh.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Tiếp nhận máy bảo hành
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.baohanh') }}" method="get" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="tukhoa" class="form-control form-control-sm" value="{{ request('tukhoa') }}" placeholder="Tìm mã phiếu, số Serial, tên khách, SĐT..." />
            </div>
            <div class="col-md-4">
                <select name="trangthai" class="form-select form-select-sm">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach($trangThaiMap as $key => $info)
                        <option value="{{ $key }}" {{ request('trangthai') === $key ? 'selected' : '' }}>{{ $info['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Lọc
                </button>
                <a href="{{ route('admin.baohanh') }}" class="btn btn-outline-secondary btn-sm" title="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="13%">Mã phiếu / Ngày nhận</th>
                        <th width="16%">Khách hàng</th>
                        <th width="27%">Thiết bị / Serial / Lỗi</th>
                        <th width="12%" class="text-center">Hạn trả dự kiến</th>
                        <th width="10%" class="text-end">Chi phí</th>
                        <th width="10%" class="text-center">Trạng thái</th>
                        <th width="8%" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($phieubaohanh as $value)
                        @php
                            $tt = $trangThaiMap[$value->trangthai] ?? ['label' => $value->trangthai, 'badge' => 'bg-secondary'];
                        @endphp
                        <tr>
                            <td class="text-center">{{ $phieubaohanh->firstItem() + $loop->index }}</td>
                            <td class="small">
                                <div class="fw-bold font-monospace text-primary">{{ $value->maphieu }}</div>
                                <div class="text-muted">{{ $value->ngaytiepnhan ? $value->ngaytiepnhan->format('d/m/Y H:i') : '-' }}</div>
                            </td>
                            <td class="small">
                                <div class="fw-semibold">{{ $value->ten_khachhang }}</div>
                                <div class="text-muted">{{ $value->sodienthoai }}</div>
                            </td>
                            <td class="small">
                                <div class="fw-semibold">{{ $value->tensanpham }}</div>
                                <div>S/N: <code>{{ $value->serial_number }}</code></div>
                                <div class="text-muted">{{ \Illuminate\Support\Str::limit($value->mota_loi, 80) }}</div>
                            </td>
                            <td class="text-center small">
                                {{ $value->ngaytra_dukien ? $value->ngaytra_dukien->format('d/m/Y') : '-' }}
                                @if($value->ngaytra_thucte)
                                    <div class="text-success">Trả: {{ $value->ngaytra_thucte->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td class="text-end fw-semibold small {{ $value->chiphi_phatsinh > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $value->chiphi_phatsinh > 0 ? number_format($value->chiphi_phatsinh, 0, ',', '.') . 'đ' : 'Miễn phí' }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $tt['badge'] }}">{{ $tt['label'] }}</span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('admin.baohanh.sua', ['id' => $value->id]) }}" class="btn btn-outline-primary btn-sm py-0 px-2" title="Xử lý / Cập nhật">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('admin.baohanh.in', ['id' => $value->id]) }}" target="_blank" class="btn btn-outline-dark btn-sm py-0 px-2" title="In biên nhận">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                                <a href="{{ route('admin.baohanh.xoa', ['id' => $value->id]) }}"
                                   class="btn btn-outline-danger btn-sm py-0 px-2"
                                   title="Xóa"
                                   onclick="return confirm('Xóa phiếu bảo hành {{ $value->maphieu }}?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Chưa có phiếu bảo hành nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $phieubaohanh->links() }}
        </div>
    </div>
</div>
@endsection