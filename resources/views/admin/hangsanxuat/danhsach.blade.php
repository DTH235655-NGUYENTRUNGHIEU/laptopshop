@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-copyright text-primary me-1"></i> Danh sách Hãng sản xuất</span>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.hangsanxuat.them') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Thêm mới
            </a>
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i> Nhập Excel
            </button>
            <a href="{{ route('admin.hangsanxuat.xuat') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Xuất Excel
            </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="12%" class="text-center">Logo</th>
                    <th width="25%">Tên hãng</th>
                    <th width="22%">Slug</th>
                    <th width="18%">Quốc gia</th>
                    <th width="8%" class="text-center">Số SP</th>
                    <th width="5%" class="text-center">Sửa</th>
                    <th width="5%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hangsanxuat as $value)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            @if($value->hinhanh && file_exists(public_path('storage/' . $value->hinhanh)))
                                <img src="{{ asset('storage/' . $value->hinhanh) }}" alt="{{ $value->tenhang }}" style="max-height: 36px; max-width: 80px; object-fit: contain;" />
                            @else
                                <span class="badge bg-light text-secondary border">{{ $value->tenhang }}</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $value->tenhang }}</td>
                        <td><code>{{ $value->tenhang_slug }}</code></td>
                        <td>{{ $value->quocgia ?? '-' }}</td>
                        <td class="text-center"><span class="badge bg-info text-dark">{{ $value->san_pham_count }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.hangsanxuat.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.hangsanxuat.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa"
                               onclick="return confirm('Bạn có muốn xóa hãng {{ $value->tenhang }} không?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nhập Excel -->
<form action="{{ route('admin.hangsanxuat.nhap') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Nhập Hãng sản xuất từ Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="file_excel" class="form-label">Chọn tệp Excel (.xlsx, .xls)</label>
                        <input type="file" class="form-control @error('file_excel') is-invalid @enderror" id="file_excel" name="file_excel" accept=".xlsx,.xls,.csv" required />
                        @error('file_excel')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                    <small class="text-muted">Mẹo: Bấm <strong>Xuất Excel</strong> trước để lấy đúng file mẫu các cột (<code>tenhang</code>, <code>tenhang_slug</code>, <code>hinhanh</code>, <code>quocgia</code>).</small>
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