@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Cập nhật loại sản phẩm</div>
    <div class="card-body">
        <form action="{{ route('admin.loaisanpham.sua', ['id' => $loaisanpham->id]) }}" method="post">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="tenloai">Tên loại sản phẩm <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('tenloai') is-invalid @enderror" id="tenloai" name="tenloai" value="{{ old('tenloai', $loaisanpham->tenloai) }}" required />
                @error('tenloai')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="mota">Mô tả</label>
                <textarea class="form-control @error('mota') is-invalid @enderror" id="mota" name="mota" rows="3">{{ old('mota', $loaisanpham->mota) }}</textarea>
                @error('mota')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Cập nhật</button>
            <a href="{{ route('admin.loaisanpham') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection