@extends('layouts.frontend')

@section('title', 'Tra cứu bảo hành điện tử')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Tra cứu bảo hành</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 text-center">
            <i class="fa-solid fa-shield-halved text-primary fa-3x mb-2"></i>
            <h1 class="h4 fw-bold mb-2">Tra cứu Bảo hành Điện tử & Tiến độ Sửa chữa</h1>
            <p class="text-muted small mb-4">
                Nhập <strong>Serial Number / Service Tag</strong> của máy, <strong>Số hóa đơn</strong>, <strong>Mã phiếu bảo hành</strong> hoặc <strong>Số điện thoại</strong> mua hàng để kiểm tra.
            </p>

            <form action="{{ route('frontend.baohanh') }}" method="get" class="mx-auto" style="max-width: 600px;">
                <div class="input-group">
                    <input type="text" name="tukhoa" class="form-control" value="{{ $tukhoa }}" placeholder="VD: R5NRCX04829121B, BH20261125-001 hoặc 0987654321..." required />
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Tra cứu
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($tukhoa !== '')
        <!-- Kết quả 1: Thời hạn bảo hành theo Hóa đơn & Serial -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-laptop-medical text-success me-1"></i>
                Thông tin kích hoạt bảo hành theo Serial ({{ $ketQuaMay->count() }})
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Tên Laptop</th>
                                <th width="16%">Serial Number</th>
                                <th width="15%">Số hóa đơn</th>
                                <th width="13%" class="text-center">Ngày mua</th>
                                <th width="14%" class="text-center">Hạn bảo hành</th>
                                <th width="14%" class="text-center">Tình trạng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ketQuaMay as $may)
                                @php
                                    $conHan = $may->han_baohanh && $may->han_baohanh->endOfDay()->isFuture();
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $may->tensanpham_luu }}</td>
                                    <td><code>{{ $may->serial_number ?: 'Chưa ghi nhận' }}</code></td>
                                    <td><code>{{ $may->HoaDon->sohoadon ?? '-' }}</code></td>
                                    <td class="text-center">{{ $may->HoaDon && $may->HoaDon->ngaylaphoadon ? $may->HoaDon->ngaylaphoadon->format('d/m/Y') : '-' }}</td>
                                    <td class="text-center">
                                        {{ $may->han_baohanh ? $may->han_baohanh->format('d/m/Y') : '-' }}
                                        <div class="text-muted">({{ $may->baohanh_thang }} tháng)</div>
                                    </td>
                                    <td class="text-center">
                                        @if($conHan)
                                            <span class="badge bg-success">Còn bảo hành</span>
                                        @else
                                            <span class="badge bg-secondary">Đã hết hạn</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Không tìm thấy thiết bị nào khớp với từ khóa "{{ $tukhoa }}".</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kết quả 2: Lịch sử phiếu tiếp nhận bảo hành -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-screwdriver-wrench text-primary me-1"></i>
                Lịch sử tiếp nhận & tiến độ sửa chữa ({{ $ketQuaPhieu->count() }})
            </div>
            <div class="card-body">
                @forelse($ketQuaPhieu as $p)
                    @php
                        $tt = $trangThaiMap[$p->trangthai] ?? ['label' => $p->trangthai, 'badge' => 'bg-secondary'];
                    @endphp
                    <div class="border rounded-3 p-3 mb-3 small">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-2 mb-2">
                            <div>
                                <strong class="font-monospace text-primary">{{ $p->maphieu }}</strong>
                                <span class="mx-2 text-muted">|</span>
                                S/N: <code>{{ $p->serial_number }}</code>
                                <span class="mx-2 text-muted">|</span>
                                Tiếp nhận: {{ $p->ngaytiepnhan ? $p->ngaytiepnhan->format('d/m/Y H:i') : '-' }}
                            </div>
                            <span class="badge {{ $tt['badge'] }}">{{ $tt['label'] }}</span>
                        </div>
                        <div class="fw-semibold mb-1">{{ $p->tensanpham }}</div>
                        <div class="text-muted mb-1"><strong>Mô tả lỗi:</strong> {{ $p->mota_loi }}</div>
                        @if($p->ketqua_xuly)
                            <div class="text-success mb-1"><strong>Kết quả xử lý:</strong> {{ $p->ketqua_xuly }}</div>
                        @endif
                        <div class="d-flex flex-wrap gap-3 text-muted mt-2">
                            <span><strong>Dự kiến trả:</strong> {{ $p->ngaytra_dukien ? $p->ngaytra_dukien->format('d/m/Y') : 'Đang cập nhật' }}</span>
                            @if($p->ngaytra_thucte)
                                <span><strong>Ngày trả thực tế:</strong> {{ $p->ngaytra_thucte->format('d/m/Y H:i') }}</span>
                            @endif
                            <span><strong>Chi phí phát sinh:</strong> <strong class="text-danger">{{ number_format($p->chiphi_phatsinh, 0, ',', '.') }}đ</strong></span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0 text-center py-2">Chưa có phiếu tiếp nhận bảo hành nào cho từ khóa này.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
@endsection