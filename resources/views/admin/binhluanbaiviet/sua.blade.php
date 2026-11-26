@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Chỉnh sửa bình luận bài viết</div>
    <div class="card-body">
        <div class="alert alert-light border small mb-3">
            <div><strong>Bài viết:</strong> {{ $binhluanbaiviet->BaiViet->tieude ?? '-' }}</div>
            <div><strong>Người đăng:</strong> {{ $binhluanbaiviet->User->name ?? '-' }} ({{ $binhluanbaiviet->created_at ? $binhluanbaiviet->created_at->format('d/m/Y H:i') : '-' }})</div>
        </div>

        <form action="{{ route('admin.binhluanbaiviet.sua', ['id' => $binhluanbaiviet->id]) }}" method="post">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="noidungbinhluan">Nội dung bình luận <span class="text-danger">*</span></label>
                <textarea class="form-control @error('noidungbinhluan') is-invalid @enderror" id="noidungbinhluan" name="noidungbinhluan" rows="4" required>{{ old('noidungbinhluan', $binhluanbaiviet->noidungbinhluan) }}</textarea>
                @error('noidungbinhluan')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="d-flex gap-4 mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="kiemduyet" name="kiemduyet" value="1" {{ old('kiemduyet', $binhluanbaiviet->kiemduyet) ? 'checked' : '' }} />
                    <label class="form-check-label" for="kiemduyet">Đã kiểm duyệt</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="kichhoat" name="kichhoat" value="1" {{ old('kichhoat', $binhluanbaiviet->kichhoat) ? 'checked' : '' }} />
                    <label class="form-check-label" for="kichhoat">Hiển thị công khai</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Cập nhật</button>
            <a href="{{ route('admin.binhluanbaiviet') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection