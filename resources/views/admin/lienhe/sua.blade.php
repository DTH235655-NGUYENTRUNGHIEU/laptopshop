@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-reply text-primary me-1"></i> Xem & Xử lý yêu cầu liên hệ
    </div>
    <div class="card-body">
        <div class="border rounded p-3 bg-light mb-4 small">
            <div class="row g-2 mb-2">
                <div class="col-md-4"><strong>Khách hàng:</strong> {{ $lienhe->hoten }}</div>
                <div class="col-md-4"><strong>Email:</strong> {{ $lienhe->email }}</div>
                <div class="col-md-4"><strong>Điện thoại:</strong> {{ $lienhe->sodienthoai ?: '-' }}</div>
            </div>
            <div class="mb-2"><strong>Tiêu đề:</strong> <span class="text-primary fw-semibold">{{ $lienhe->tieude }}</span></div>
            <div><strong>Nội dung khách gửi:</strong></div>
            <div class="bg-white border rounded p-2 mt-1">{{ $lienhe->noidung }}</div>
        </div>

        <form action="{{ route('admin.lienhe.sua', ['id' => $lienhe->id]) }}" method="post">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="trangthai">Trạng thái xử lý <span class="text-danger">*</span></label>
                <select class="form-select @error('trangthai') is-invalid @enderror" id="trangthai" name="trangthai" required>
                    <option value="chua_xuly" {{ old('trangthai', $lienhe->trangthai) === 'chua_xuly' ? 'selected' : '' }}>Chưa xử lý</option>
                    <option value="dang_xuly" {{ old('trangthai', $lienhe->trangthai) === 'dang_xuly' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="da_xuly" {{ old('trangthai', $lienhe->trangthai) === 'da_xuly' ? 'selected' : '' }}>Đã xử lý</option>
                </select>
                @error('trangthai') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="phanhoi_admin">Ghi chú / Nội dung đã phản hồi cho khách</label>
                <textarea class="form-control @error('phanhoi_admin') is-invalid @enderror" id="phanhoi_admin" name="phanhoi_admin" rows="4" placeholder="Nhập tóm tắt nội dung đã tư vấn hoặc giải quyết...">{{ old('phanhoi_admin', $lienhe->phanhoi_admin) }}</textarea>
                @error('phanhoi_admin') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu kết quả xử lý</button>
            <a href="{{ route('admin.lienhe') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection