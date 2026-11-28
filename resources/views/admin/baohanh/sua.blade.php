@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-screwdriver-wrench text-primary me-1"></i>
            Cập nhật Phiếu bảo hành: <code>{{ $phieu->maphieu }}</code>
        </span>
        <a href="{{ route('admin.baohanh.in', ['id' => $phieu->id]) }}" target="_blank" class="btn btn-dark btn-sm">
            <i class="fa-solid fa-print"></i> In biên nhận
        </a>
    </div>

    <div class="card-body">
        <div class="border rounded p-3 bg-light small mb-4">
            <div class="row g-2">
                <div class="col-md-6">
                    <div><strong>Thiết bị:</strong> {{ $phieu->tensanpham }}</div>
                    <div><strong>Serial Number:</strong> <code>{{ $phieu->serial_number }}</code></div>
                    @if($phieu->HoaDon_ChiTiet)
                        <div>
                            <strong>Hóa đơn mua:</strong> <code>{{ $phieu->HoaDon_ChiTiet->HoaDon->sohoadon ?? '-' }}</code>
                            — <strong>Hạn BH:</strong> {{ $phieu->HoaDon_ChiTiet->han_baohanh ? $phieu->HoaDon_ChiTiet->han_baohanh->format('d/m/Y') : '-' }}
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div><strong>Khách hàng:</strong> {{ $phieu->ten_khachhang }} ({{ $phieu->sodienthoai }})</div>
                    <div><strong>Ngày tiếp nhận:</strong> {{ $phieu->ngaytiepnhan ? $phieu->ngaytiepnhan->format('d/m/Y H:i') : '-' }}</div>
                    <div><strong>Kỹ thuật viên phụ trách:</strong> {{ $phieu->NhanVien->name ?? 'Chưa phân công' }}</div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.baohanh.sua', ['id' => $phieu->id]) }}" method="post">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label" for="trangthai">Trạng thái bảo hành <span class="text-danger">*</span></label>
                    <select class="form-select" id="trangthai" name="trangthai" required>
                        @foreach($trangThaiMap as $key => $info)
                            <option value="{{ $key }}" {{ old('trangthai', $phieu->trangthai) === $key ? 'selected' : '' }}>{{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="ngaytra_dukien">Ngày trả dự kiến</label>
                    <input type="date" class="form-control" id="ngaytra_dukien" name="ngaytra_dukien" value="{{ old('ngaytra_dukien', $phieu->ngaytra_dukien ? $phieu->ngaytra_dukien->format('Y-m-d') : '') }}" />
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="ngaytra_thucte">Ngày trả thực tế</label>
                    <input type="datetime-local" class="form-control" id="ngaytra_thucte" name="ngaytra_thucte" value="{{ old('ngaytra_thucte', $phieu->ngaytra_thucte ? $phieu->ngaytra_thucte->format('Y-m-d\TH:i') : '') }}" />
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="chiphi_phatsinh">Chi phí (VNĐ)</label>
                    <input type="number" min="0" step="1000" class="form-control" id="chiphi_phatsinh" name="chiphi_phatsinh" value="{{ old('chiphi_phatsinh', $phieu->chiphi_phatsinh) }}" required />
                </div>

                <div class="col-12">
                    <label class="form-label" for="phukien_kemtheo">Phụ kiện nhận kèm</label>
                    <input type="text" class="form-control" id="phukien_kemtheo" name="phukien_kemtheo" value="{{ old('phukien_kemtheo', $phieu->phukien_kemtheo) }}" />
                </div>

                <div class="col-12">
                    <label class="form-label" for="mota_loi">Mô tả lỗi ghi nhận <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('mota_loi') is-invalid @enderror" id="mota_loi" name="mota_loi" rows="3" required>{{ old('mota_loi', $phieu->mota_loi) }}</textarea>
                    @error('mota_loi') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="ketqua_xuly">Kết quả kiểm tra & xử lý của kỹ thuật viên</label>
                    <textarea class="form-control" id="ketqua_xuly" name="ketqua_xuly" rows="3" placeholder="VD: Đã thay mới quạt tản nhiệt chính hãng, tra keo tản nhiệt và test ổn định...">{{ old('ketqua_xuly', $phieu->ketqua_xuly) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu cập nhật</button>
            <a href="{{ route('admin.baohanh') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection