@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Cập nhật banner quảng cáo</div>
    <div class="card-body">
        <form action="{{ route('admin.banner.sua', ['id' => $banner->id]) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="tieude">Tiêu đề banner <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tieude') is-invalid @enderror" id="tieude" name="tieude" value="{{ old('tieude', $banner->tieude) }}" required />
                    @error('tieude') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="vitri">Vị trí hiển thị <span class="text-danger">*</span></label>
                    <select class="form-select @error('vitri') is-invalid @enderror" id="vitri" name="vitri" required>
                        <option value="slider_chinh" {{ old('vitri', $banner->vitri) === 'slider_chinh' ? 'selected' : '' }}>Slider chính trang chủ</option>
                        <option value="banner_phu" {{ old('vitri', $banner->vitri) === 'banner_phu' ? 'selected' : '' }}>Banner phụ</option>
                        <option value="popup" {{ old('vitri', $banner->vitri) === 'popup' ? 'selected' : '' }}>Popup khuyến mãi</option>
                    </select>
                    @error('vitri') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="thutu">Thứ tự sắp xếp <span class="text-danger">*</span></label>
                    <input type="number" min="0" max="255" class="form-control @error('thutu') is-invalid @enderror" id="thutu" name="thutu" value="{{ old('thutu', $banner->thutu) }}" required />
                    @error('thutu') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="hinhanh">Hình ảnh banner (bỏ trống nếu giữ nguyên)</label>
                    @if($banner->hinhanh && file_exists(public_path('storage/' . $banner->hinhanh)))
                        <div class="mb-2">
                            <img src="{{ asset('public/storage/' . $banner->hinhanh) }}" alt="{{ $banner->tieude }}" class="img-thumbnail" style="max-height: 90px;" />
                        </div>
                    @endif
                    <input type="file" class="form-control @error('hinhanh') is-invalid @enderror" id="hinhanh" name="hinhanh" accept="image/*" />
                    @error('hinhanh') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="duongdan">Đường dẫn đích khi nhấp vào</label>
                    <input type="text" class="form-control @error('duongdan') is-invalid @enderror" id="duongdan" name="duongdan" value="{{ old('duongdan', $banner->duongdan) }}" />
                    @error('duongdan') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="kichhoat" name="kichhoat" value="1" {{ old('kichhoat', $banner->kichhoat) ? 'checked' : '' }} />
                        <label class="form-check-label" for="kichhoat">Hiển thị banner</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Cập nhật</button>
            <a href="{{ route('admin.banner') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection