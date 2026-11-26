@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Thêm hãng sản xuất</div>
    <div class="card-body">
        <form action="{{ route('admin.hangsanxuat.them') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="tenhang">Tên hãng sản xuất <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('tenhang') is-invalid @enderror" id="tenhang" name="tenhang" value="{{ old('tenhang') }}" required />
                @error('tenhang')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="quocgia">Quốc gia</label>
                <input type="text" class="form-control @error('quocgia') is-invalid @enderror" id="quocgia" name="quocgia" value="{{ old('quocgia') }}" placeholder="VD: Mỹ, Đài Loan, Nhật Bản..." />
                @error('quocgia')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="hinhanh">Logo hãng sản xuất</label>
                <input type="file" class="form-control @error('hinhanh') is-invalid @enderror" id="hinhanh" name="hinhanh" accept="image/*" />
                @error('hinhanh')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu mới</button>
            <a href="{{ route('admin.hangsanxuat') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection