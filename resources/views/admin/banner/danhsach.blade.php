@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fa-solid fa-images text-primary me-1"></i> Danh sách Banner quảng cáo</span>
        <a href="{{ route('admin.banner.them') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Thêm banner mới
        </a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th width="4%" class="text-center">#</th>
                    <th width="18%" class="text-center">Hình ảnh</th>
                    <th width="26%">Tiêu đề & Liên kết</th>
                    <th width="14%" class="text-center">Vị trí hiển thị</th>
                    <th width="8%" class="text-center">Thứ tự</th>
                    <th width="10%" class="text-center">Hiển thị</th>
                    <th width="5%" class="text-center">Sửa</th>
                    <th width="5%" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banner as $value)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            @if($value->hinhanh && file_exists(public_path('storage/' . $value->hinhanh)))
                                <img src="{{ asset('public/storage/' . $value->hinhanh) }}" alt="{{ $value->tieude }}" class="img-thumbnail p-0" style="width: 140px; height: 60px; object-fit: cover;" />
                            @else
                                <div class="bg-light border rounded d-inline-flex align-items-center justify-content-center text-muted small" style="width: 140px; height: 60px;">
                                    Chưa có file ảnh
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $value->tieude }}</div>
                            @if($value->duongdan)
                                <div class="small text-muted">Link: <code>{{ $value->duongdan }}</code></div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($value->vitri === 'slider_chinh')
                                <span class="badge bg-primary">Slider chính</span>
                            @elseif($value->vitri === 'banner_phu')
                                <span class="badge bg-info text-dark">Banner phụ</span>
                            @else
                                <span class="badge bg-warning text-dark">Popup khuyến mãi</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold">{{ $value->thutu }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.banner.kichhoat', ['id' => $value->id]) }}" title="Ẩn/Hiện banner">
                                @if($value->kichhoat)
                                    <i class="fa-solid fa-circle-check text-success fa-lg"></i>
                                @else
                                    <i class="fa-solid fa-circle-xmark text-danger fa-lg"></i>
                                @endif
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.banner.sua', ['id' => $value->id]) }}" class="text-primary" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.banner.xoa', ['id' => $value->id]) }}"
                               class="text-danger"
                               title="Xóa"
                               onclick="return confirm('Bạn có muốn xóa banner {{ $value->tieude }} không?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Chưa có banner nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection