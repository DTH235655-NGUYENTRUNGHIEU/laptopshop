@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Thêm chủ đề bài viết</div>
    <div class="card-body">
        <form action="{{ route('admin.chude.them') }}" method="post">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="tenchude">Tên chủ đề <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('tenchude') is-invalid @enderror" id="tenchude" name="tenchude" value="{{ old('tenchude') }}" placeholder="VD: Đánh giá Laptop, Thủ thuật Windows..." required />
                @error('tenchude')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu mới</button>
            <a href="{{ route('admin.chude') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection