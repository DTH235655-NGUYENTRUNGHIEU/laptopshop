@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-file-circle-plus text-primary me-1"></i> Lập phiếu tiếp nhận bảo hành tại cửa hàng
    </div>
    <div class="card-body">
        <form action="{{ route('admin.baohanh.them') }}" method="post">
            @csrf

            <div class="mb-3 p-3 border rounded bg-light">
                <label class="form-label fw-semibold small" for="hoadon_chitiet_id">
                    Chọn nhanh máy đã xuất hóa đơn tại cửa hàng (tự điền Serial, Khách hàng & Hạn bảo hành)
                </label>
                <select class="form-select form-select-sm" id="hoadon_chitiet_id" name="hoadon_chitiet_id">
                    <option value="">-- Nhập thủ công hoặc chọn từ danh sách máy đã bán --</option>
                    @foreach($danhSachMayDaBan as $may)
                        @php
                            $conHanText = ($may->han_baohanh && $may->han_baohanh->endOfDay()->isFuture())
                                ? 'Còn BH đến ' . $may->han_baohanh->format('d/m/Y')
                                : 'Hết hạn BH';
                        @endphp
                        <option value="{{ $may->id }}"
                                data-serial="{{ $may->serial_number }}"
                                data-ten="{{ $may->tensanpham_luu }}"
                                data-khach="{{ $may->HoaDon->ten_khachhang ?? '' }}"
                                data-sdt="{{ $may->HoaDon->sodienthoai ?? '' }}"
                                {{ old('hoadon_chitiet_id') == $may->id ? 'selected' : '' }}>
                            [S/N: {{ $may->serial_number ?: 'Chưa có' }}] {{ $may->tensanpham_luu }} — KH: {{ $may->HoaDon->ten_khachhang ?? '-' }} ({{ $conHanText }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label" for="maphieu">Mã phiếu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase @error('maphieu') is-invalid @enderror" id="maphieu" name="maphieu" value="{{ old('maphieu', $maPhieuGoiY) }}" required />
                    @error('maphieu') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="serial_number">Serial Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase font-monospace @error('serial_number') is-invalid @enderror" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required />
                    @error('serial_number') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="tensanpham">Tên Laptop <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tensanpham') is-invalid @enderror" id="tensanpham" name="tensanpham" value="{{ old('tensanpham') }}" required />
                    @error('tensanpham') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ten_khachhang">Tên khách hàng <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ten_khachhang') is-invalid @enderror" id="ten_khachhang" name="ten_khachhang" value="{{ old('ten_khachhang') }}" required />
                    @error('ten_khachhang') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="sodienthoai">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('sodienthoai') is-invalid @enderror" id="sodienthoai" name="sodienthoai" value="{{ old('sodienthoai') }}" required />
                    @error('sodienthoai') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="ngaytiepnhan">Ngày tiếp nhận <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('ngaytiepnhan') is-invalid @enderror" id="ngaytiepnhan" name="ngaytiepnhan" value="{{ old('ngaytiepnhan', date('Y-m-d\TH:i')) }}" required />
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="ngaytra_dukien">Dự kiến trả</label>
                    <input type="date" class="form-control" id="ngaytra_dukien" name="ngaytra_dukien" value="{{ old('ngaytra_dukien', date('Y-m-d', strtotime('+7 days'))) }}" />
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="phukien_kemtheo">Phụ kiện nhận kèm</label>
                    <input type="text" class="form-control" id="phukien_kemtheo" name="phukien_kemtheo" value="{{ old('phukien_kemtheo') }}" placeholder="VD: Máy + Sạc zin + Túi chống sốc" />
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="chiphi_phatsinh">Chi phí phát sinh (VNĐ)</label>
                    <input type="number" min="0" step="1000" class="form-control" id="chiphi_phatsinh" name="chiphi_phatsinh" value="{{ old('chiphi_phatsinh', 0) }}" required />
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="trangthai">Trạng thái xử lý</label>
                    <select class="form-select" id="trangthai" name="trangthai" required>
                        @foreach($trangThaiMap as $key => $info)
                            <option value="{{ $key }}" {{ old('trangthai', 'dang_kiem_tra') === $key ? 'selected' : '' }}>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label" for="mota_loi">Mô tả lỗi khách báo <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('mota_loi') is-invalid @enderror" id="mota_loi" name="mota_loi" rows="3" required>{{ old('mota_loi') }}</textarea>
                    @error('mota_loi') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="ketqua_xuly">Ghi chú kỹ thuật / Hướng xử lý</label>
                    <textarea class="form-control" id="ketqua_xuly" name="ketqua_xuly" rows="2">{{ old('ketqua_xuly') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu phiếu bảo hành</button>
            <a href="{{ route('admin.baohanh') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const selMay = document.getElementById('hoadon_chitiet_id');
    selMay.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (opt && opt.value) {
            document.getElementById('serial_number').value = opt.dataset.serial || '';
            document.getElementById('tensanpham').value = opt.dataset.ten || '';
            document.getElementById('ten_khachhang').value = opt.dataset.khach || '';
            document.getElementById('sodienthoai').value = opt.dataset.sdt || '';
        }
    });
</script>
@endsection