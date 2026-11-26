@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-star text-warning me-1"></i>
        Danh sách Đánh giá sản phẩm ({{ $danhgia->total() }} đánh giá)
    </div>

    <div class="card-body">
        <form action="{{ route('admin.danhgia') }}" method="get" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="sosao" class="form-select form-select-sm">
                    <option value="">-- Tất cả mức sao --</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('sosao') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <select name="kiemduyet" class="form-select form-select-sm">
                    <option value="">-- Tất cả trạng thái duyệt --</option>
                    <option value="1" {{ request('kiemduyet') === '1' ? 'selected' : '' }}>Đã duyệt hiển thị</option>
                    <option value="0" {{ request('kiemduyet') === '0' ? 'selected' : '' }}>Chờ duyệt / Đang ẩn</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Lọc đánh giá
                </button>
                <a href="{{ route('admin.danhgia') }}" class="btn btn-outline-secondary btn-sm" title="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="16%">Khách hàng</th>
                        <th width="24%">Sản phẩm</th>
                        <th width="13%" class="text-center">Số sao</th>
                        <th>Nội dung nhận xét</th>
                        <th width="8%" class="text-center">Duyệt</th>
                        <th width="5%" class="text-center">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($danhgia as $value)
                        <tr>
                            <td class="text-center">{{ $danhgia->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $value->User->name ?? 'Ẩn danh' }}</div>
                                <div class="small text-muted">{{ $value->created_at ? $value->created_at->format('d/m/Y H:i') : '-' }}</div>
                            </td>
                            <td class="small fw-semibold text-primary">
                                {{ $value->SanPham->tensanpham ?? 'Sản phẩm đã xóa' }}
                            </td>
                            <td class="text-center text-nowrap">
                                @for($s = 1; $s <= 5; $s++)
                                    @if($s <= $value->sosao)
                                        <i class="fa-solid fa-star text-warning"></i>
                                    @else
                                        <i class="fa-regular fa-star text-muted"></i>
                                    @endif
                                @endfor
                                <div class="small text-muted">({{ $value->sosao }}/5)</div>
                            </td>
                            <td class="small">{{ $value->noidung ?: 'Khách hàng chỉ chấm sao.' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.danhgia.kiemduyet', ['id' => $value->id]) }}" title="Bật/Tắt kiểm duyệt">
                                    @if($value->kiemduyet)
                                        <i class="fa-solid fa-circle-check text-success fa-lg"></i>
                                    @else
                                        <i class="fa-solid fa-circle-xmark text-danger fa-lg"></i>
                                    @endif
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.danhgia.xoa', ['id' => $value->id]) }}"
                                   class="text-danger"
                                   title="Xóa"
                                   onclick="return confirm('Bạn có muốn xóa đánh giá này không?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có đánh giá sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $danhgia->links() }}
        </div>
    </div>
</div>
@endsection