@extends('layouts.frontend')

@section('title', 'Bảo hành sản phẩm của tôi')

@section('content')
<div class="container pt-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Tài khoản</a></li>
            <li class="breadcrumb-item active">Bảo hành sản phẩm</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('user._sidebar')
        </aside>

        <div class="col-lg-9">
            @if($donHangChuaXuatHD->count() > 0)
                <div class="alert alert-info small mb-4">
                    <div class="fw-semibold mb-1">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Bạn có {{ $donHangChuaXuatHD->count() }} đơn hàng đang chờ cửa hàng xuất hóa đơn & kích hoạt Serial bảo hành:
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach($donHangChuaXuatHD as $dh)
                            <li>
                                Đơn <a href="{{ route('user.donhang.chitiet', ['id' => $dh->id]) }}" class="fw-semibold">{{ $dh->madonhang }}</a>
                                — Trạng thái: <strong>{{ $dh->TinhTrang->tinhtrang ?? '' }}</strong>
                                (sau khi nhân viên xuất hóa đơn VAT, máy sẽ tự động xuất hiện ở mục bên dưới).
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form đăng ký gửi bảo hành cho máy đã xuất hóa đơn -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    <i class="fa-solid fa-paper-plane text-primary me-1"></i>
                    Gửi yêu cầu bảo hành thiết bị đã mua ({{ $thietBiDaMua->count() }} máy đã kích hoạt)
                </div>
                <div class="card-body">
                    @if($thietBiDaMua->count() > 0)
                        <form action="{{ route('user.baohanh.yeucau') }}" method="post">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label small fw-semibold" for="hoadon_chitiet_id">Chọn máy cần bảo hành <span class="text-danger">*</span></label>
                                    <select name="hoadon_chitiet_id" id="hoadon_chitiet_id" class="form-select form-select-sm @error('hoadon_chitiet_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn máy theo số Serial --</option>
                                        @foreach($thietBiDaMua as $tb)
                                            <option value="{{ $tb->id }}" {{ old('hoadon_chitiet_id') == $tb->id ? 'selected' : '' }}>
                                                [S/N: {{ $tb->serial_number ?: ('HD#' . $tb->hoadon_id) }}] {{ $tb->tensanpham_luu }} (Hạn BH: {{ $tb->han_baohanh ? $tb->han_baohanh->format('d/m/Y') : '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hoadon_chitiet_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label small fw-semibold" for="phukien_kemtheo">Phụ kiện dự kiến gửi kèm</label>
                                    <input type="text" name="phukien_kemtheo" id="phukien_kemtheo" class="form-control form-control-sm" value="{{ old('phukien_kemtheo') }}" placeholder="VD: Máy + Sạc zin..." />
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold" for="mota_loi">Mô tả tình trạng lỗi <span class="text-danger">*</span></label>
                                    <textarea name="mota_loi" id="mota_loi" rows="2" class="form-control form-control-sm @error('mota_loi') is-invalid @enderror" placeholder="VD: Máy bị sọc màn hình, quạt kêu to, không nhận sạc..." required>{{ old('mota_loi') }}</textarea>
                                    @error('mota_loi') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary btn-sm px-4">Gửi yêu cầu bảo hành</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-light border small mb-0">
                            Bạn chưa có thiết bị nào được xuất hóa đơn VAT. Khi cửa hàng bấm <strong>Xuất hóa đơn</strong> cho đơn hàng của bạn, máy sẽ tự động hiện tại đây để đăng ký bảo hành.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Danh sách phiếu bảo hành của khách (đồng bộ 2 chiều với Admin) -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="fa-solid fa-clock-rotate-left text-primary me-1"></i>
                    Lịch sử phiếu bảo hành của tôi ({{ $phieuBaoHanh->total() }})
                </div>
                <div class="card-body">
                    @forelse($phieuBaoHanh as $p)
                        @php
                            $tt = $trangThaiMap[$p->trangthai] ?? ['label' => $p->trangthai, 'badge' => 'bg-secondary'];
                        @endphp
                        <div class="border rounded-3 p-3 mb-3 small">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-2 mb-2">
                                <div>
                                    <strong class="font-monospace text-primary">{{ $p->maphieu }}</strong>
                                    <span class="text-muted ms-2">S/N: <code>{{ $p->serial_number }}</code></span>
                                    <span class="text-muted ms-2">{{ $p->ngaytiepnhan ? $p->ngaytiepnhan->format('d/m/Y H:i') : '' }}</span>
                                </div>
                                <span class="badge {{ $tt['badge'] }}">{{ $tt['label'] }}</span>
                            </div>
                            <div class="fw-semibold mb-1">{{ $p->tensanpham }}</div>
                            <div class="text-muted"><strong>Lỗi ghi nhận:</strong> {{ $p->mota_loi }}</div>
                            @if($p->ketqua_xuly)
                                <div class="text-success mt-1"><strong>Phản hồi kỹ thuật:</strong> {{ $p->ketqua_xuly }}</div>
                            @endif
                            <div class="d-flex flex-wrap gap-3 mt-2 text-muted">
                                <span><strong>Kỹ thuật viên:</strong> {{ $p->NhanVien->name ?? 'Chờ phân công' }}</span>
                                <span><strong>Trả dự kiến:</strong> {{ $p->ngaytra_dukien ? $p->ngaytra_dukien->format('d/m/Y') : '-' }}</span>
                                @if($p->ngaytra_thucte)
                                    <span><strong>Đã trả lúc:</strong> {{ $p->ngaytra_thucte->format('d/m/Y H:i') }}</span>
                                @endif
                                <span><strong>Chi phí phát sinh:</strong> <strong class="text-danger">{{ number_format($p->chiphi_phatsinh, 0, ',', '.') }}đ</strong></span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small text-center py-3 mb-0">Bạn chưa có phiếu bảo hành nào.</p>
                    @endforelse

                    <div class="d-flex justify-content-center">
                        {{ $phieuBaoHanh->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection