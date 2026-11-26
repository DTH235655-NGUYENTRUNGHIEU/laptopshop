@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-laptop text-primary me-1"></i>
            Danh sách Laptop ({{ $sanpham->total() }} mẫu)
        </span>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sanpham.them') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Thêm mới
            </a>
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i> Nhập Excel
            </button>
            <a href="{{ route('admin.sanpham.xuat') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Xuất Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Thanh lọc & tìm kiếm -->
        <form action="{{ route('admin.sanpham') }}" method="get" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="tukhoa" class="form-control form-control-sm" value="{{ request('tukhoa') }}" placeholder="Tìm theo mã SKU, tên laptop, CPU..." />
            </div>
            <div class="col-md-3">
                <select name="loaisanpham_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả phân khúc --</option>
                    @foreach($loaisanpham as $loai)
                        <option value="{{ $loai->id }}" {{ request('loaisanpham_id') == $loai->id ? 'selected' : '' }}>
                            {{ $loai->tenloai }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="hangsanxuat_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả hãng --</option>
                    @foreach($hangsanxuat as $hang)
                        <option value="{{ $hang->id }}" {{ request('hangsanxuat_id') == $hang->id ? 'selected' : '' }}>
                            {{ $hang->tenhang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Lọc
                </button>
                <a href="{{ route('admin.sanpham') }}" class="btn btn-outline-secondary btn-sm" title="Xóa bộ lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="9%" class="text-center">Ảnh</th>
                        <th width="27%">Sản phẩm / SKU</th>
                        <th width="23%">Cấu hình chính</th>
                        <th width="14%" class="text-end">Giá bán</th>
                        <th width="6%" class="text-center">Tồn</th>
                        <th width="9%" class="text-center">Trạng thái</th>
                        <th width="4%" class="text-center">Sửa</th>
                        <th width="4%" class="text-center">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sanpham as $value)
                        <tr>
                            <td class="text-center">{{ $sanpham->firstItem() + $loop->index }}</td>
                            <td class="text-center">
                                @if($value->hinhanh && file_exists(public_path('storage/' . $value->hinhanh)))
                                    <img src="{{ asset('public/storage/' . $value->hinhanh) }}" alt="{{ $value->tensanpham }}" class="img-thumbnail p-0" style="width: 68px; height: 50px; object-fit: cover;" />
                                @else
                                    <div class="bg-light border rounded d-inline-flex align-items-center justify-content-center text-muted" style="width: 68px; height: 50px;">
                                        <i class="fa-solid fa-laptop fa-lg"></i>
                                    </div>
                                @endif
                                @if($value->san_pham_hinh_anh_count > 0)
                                    <div class="small text-muted mt-1">+{{ $value->san_pham_hinh_anh_count }} ảnh</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $value->tensanpham }}</div>
                                <div class="small text-muted">
                                    <code>{{ $value->masanpham }}</code>
                                    | {{ $value->HangSanXuat->tenhang ?? '' }}
                                    | {{ $value->LoaiSanPham->tenloai ?? '' }}
                                </div>
                                @if($value->noibat)
                                    <span class="badge bg-warning text-dark mt-1"><i class="fa-solid fa-star"></i> Nổi bật</span>
                                @endif
                            </td>
                            <td class="small">
                                <div><strong>CPU:</strong> {{ $value->cpu }}</div>
                                <div><strong>RAM/SSD:</strong> {{ $value->ram }} / {{ $value->ocung }}</div>
                                <div><strong>VGA:</strong> {{ $value->carddohoa }}</div>
                            </td>
                            <td class="text-end">
                                @if($value->giakhuyenmai)
                                    <div class="fw-bold text-danger">{{ number_format($value->giakhuyenmai, 0, ',', '.') }}đ</div>
                                    <div class="small text-muted text-decoration-line-through">{{ number_format($value->dongia, 0, ',', '.') }}đ</div>
                                @else
                                    <div class="fw-bold text-dark">{{ number_format($value->dongia, 0, ',', '.') }}đ</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $value->soluong <= 5 ? 'bg-danger' : ($value->soluong <= 10 ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $value->soluong }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($value->trangthai)
                                    <span class="badge bg-primary">Đang bán</span>
                                @else
                                    <span class="badge bg-secondary">Ngừng bán</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.sanpham.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.sanpham.xoa', ['id' => $value->id]) }}"
                                   class="text-danger"
                                   title="Xóa"
                                   onclick="return confirm('Bạn có muốn xóa laptop {{ $value->tensanpham }} không?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Không tìm thấy mẫu laptop nào phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $sanpham->links() }}
        </div>
    </div>
</div>

<!-- Modal Nhập Excel -->
<form action="{{ route('admin.sanpham.nhap') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Nhập danh sách Laptop từ Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="file_excel" class="form-label">Chọn tệp Excel (.xlsx, .xls, .csv)</label>
                        <input type="file" class="form-control @error('file_excel') is-invalid @enderror" id="file_excel" name="file_excel" accept=".xlsx,.xls,.csv" required />
                        @error('file_excel')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                    <small class="text-muted">Mẹo: Bấm <strong>Xuất Excel</strong> trước để lấy file mẫu chuẩn 21 cột. Hệ thống tự cập nhật nếu trùng <code>masanpham</code> và thêm mới nếu khác mã.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Nhập dữ liệu</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection