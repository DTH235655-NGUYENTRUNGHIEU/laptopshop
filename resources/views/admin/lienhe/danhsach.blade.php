@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-envelope-open-text text-primary me-1"></i>
        Danh sách Yêu cầu Liên hệ & Tư vấn ({{ $lienhe->total() }})
    </div>

    <div class="card-body">
        <form action="{{ route('admin.lienhe') }}" method="get" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="tukhoa" class="form-control form-control-sm" value="{{ request('tukhoa') }}" placeholder="Tìm theo tên khách, email, tiêu đề..." />
            </div>
            <div class="col-md-4">
                <select name="trangthai" class="form-select form-select-sm">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="chua_xuly" {{ request('trangthai') === 'chua_xuly' ? 'selected' : '' }}>Chưa xử lý</option>
                    <option value="dang_xuly" {{ request('trangthai') === 'dang_xuly' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="da_xuly" {{ request('trangthai') === 'da_xuly' ? 'selected' : '' }}>Đã xử lý</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Lọc
                </button>
                <a href="{{ route('admin.lienhe') }}" class="btn btn-outline-secondary btn-sm" title="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="20%">Khách hàng</th>
                        <th>Tiêu đề & Nội dung yêu cầu</th>
                        <th width="13%" class="text-center">Ngày gửi</th>
                        <th width="11%" class="text-center">Trạng thái</th>
                        <th width="6%" class="text-center">Xử lý</th>
                        <th width="5%" class="text-center">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lienhe as $value)
                        <tr>
                            <td class="text-center">{{ $lienhe->firstItem() + $loop->index }}</td>
                            <td class="small">
                                <div class="fw-semibold">{{ $value->hoten }}</div>
                                <div class="text-muted">{{ $value->email }}</div>
                                @if($value->sodienthoai)
                                    <div class="text-muted">{{ $value->sodienthoai }}</div>
                                @endif
                            </td>
                            <td class="small">
                                <div class="fw-semibold text-primary">{{ $value->tieude }}</div>
                                <div class="text-muted">{{ \Illuminate\Support\Str::limit($value->noidung, 110) }}</div>
                                @if($value->phanhoi_admin)
                                    <div class="text-success mt-1"><i class="fa-solid fa-reply"></i> <strong>Phản hồi:</strong> {{ \Illuminate\Support\Str::limit($value->phanhoi_admin, 90) }}</div>
                                @endif
                            </td>
                            <td class="text-center small">{{ $value->created_at ? $value->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-center">
                                @if($value->trangthai === 'chua_xuly')
                                    <span class="badge bg-danger">Chưa xử lý</span>
                                @elseif($value->trangthai === 'dang_xuly')
                                    <span class="badge bg-warning text-dark">Đang xử lý</span>
                                @else
                                    <span class="badge bg-success">Đã xử lý</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.lienhe.sua', ['id' => $value->id]) }}" class="btn btn-outline-primary btn-sm py-0 px-2" title="Xem & phản hồi">
                                    <i class="fa-solid fa-reply"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.lienhe.xoa', ['id' => $value->id]) }}"
                                   class="text-danger"
                                   title="Xóa"
                                   onclick="return confirm('Bạn có muốn xóa yêu cầu liên hệ này không?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có yêu cầu liên hệ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $lienhe->links() }}
        </div>
    </div>
</div>
@endsection