@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-pen-nib text-primary me-1"></i> Viết bài mới
    </div>
    <div class="card-body">
        <form action="{{ route('admin.baiviet.them') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label" for="chude_id">Chủ đề <span class="text-danger">*</span></label>
                    <select class="form-select @error('chude_id') is-invalid @enderror" id="chude_id" name="chude_id" required>
                        <option value="">-- Chọn chủ đề --</option>
                        @foreach($chude as $cd)
                            <option value="{{ $cd->id }}" {{ old('chude_id') == $cd->id ? 'selected' : '' }}>
                                {{ $cd->tenchude }}
                            </option>
                        @endforeach
                    </select>
                    @error('chude_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label" for="tieude">Tiêu đề bài viết <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tieude') is-invalid @enderror" id="tieude" name="tieude" value="{{ old('tieude') }}" required />
                    @error('tieude') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="hinhanh">Ảnh đại diện bài viết</label>
                    <input type="file" class="form-control @error('hinhanh') is-invalid @enderror" id="hinhanh" name="hinhanh" accept="image/*" />
                    @error('hinhanh') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6 d-flex align-items-end gap-4 pb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="kiemduyet" name="kiemduyet" value="1" {{ old('kiemduyet', 1) ? 'checked' : '' }} />
                        <label class="form-check-label" for="kiemduyet">Đã kiểm duyệt</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="kichhoat" name="kichhoat" value="1" {{ old('kichhoat', 1) ? 'checked' : '' }} />
                        <label class="form-check-label" for="kichhoat">Hiển thị công khai</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label" for="tomtat">Tóm tắt ngắn</label>
                    <textarea class="form-control @error('tomtat') is-invalid @enderror" id="tomtat" name="tomtat" rows="3">{{ old('tomtat') }}</textarea>
                    @error('tomtat') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="noidung">Nội dung chi tiết <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('noidung') is-invalid @enderror" id="noidung" name="noidung" rows="10">{{ old('noidung') }}</textarea>
                    @error('noidung') <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Đăng bài</button>
            <a href="{{ route('admin.baiviet') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#noidung'))
        .catch(error => console.error(error));
</script>
@endsection