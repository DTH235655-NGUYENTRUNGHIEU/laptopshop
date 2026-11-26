@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Thêm mã giảm giá mới</div>
    <div class="card-body">
        <form action="{{ route('admin.magiamgia.them') }}" method="post">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="macode">Mã giảm giá <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase @error('macode') is-invalid @enderror" id="macode" name="macode" value="{{ old('macode') }}" placeholder="VD: SALE1TR, GAMING5" required />
                    @error('macode') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="loai">Loại giảm giá <span class="text-danger">*</span></label>
                    <select class="form-select @error('loai') is-invalid @enderror" id="loai" name="loai" required>
                        <option value="fixed" {{ old('loai') === 'fixed' ? 'selected' : '' }}>Giảm tiền cố định (VNĐ)</option>
                        <option value="percent" {{ old('loai') === 'percent' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                    </select>
                    @error('loai') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="giatri">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" min="1" class="form-control @error('giatri') is-invalid @enderror" id="giatri" name="giatri" value="{{ old('giatri') }}" placeholder="VD: 500000 hoặc 5" required />
                    @error('giatri') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="donhang_toithieu">Đơn tối thiểu (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" min="0" step="1000" class="form-control @error('donhang_toithieu') is-invalid @enderror" id="donhang_toithieu" name="donhang_toithieu" value="{{ old('donhang_toithieu', 0) }}" required />
                    @error('donhang_toithieu') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="soluong">Số lượt sử dụng <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control @error('soluong') is-invalid @enderror" id="soluong" name="soluong" value="{{ old('soluong', 50) }}" required />
                    @error('soluong') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="ngaybatdau">Ngày bắt đầu</label>
                    <input type="datetime-local" class="form-control @error('ngaybatdau') is-invalid @enderror" id="ngaybatdau" name="ngaybatdau" value="{{ old('ngaybatdau') }}" />
                    @error('ngaybatdau') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="ngayketthuc">Ngày kết thúc</label>
                    <input type="datetime-local" class="form-control @error('ngayketthuc') is-invalid @enderror" id="ngayketthuc" name="ngayketthuc" value="{{ old('ngayketthuc') }}" />
                    @error('ngayketthuc') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="kichhoat" name="kichhoat" value="1" {{ old('kichhoat', 1) ? 'checked' : '' }} />
                        <label class="form-check-label" for="kichhoat">Kích hoạt mã ngay</label>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu mới</button>
                <a href="{{ route('admin.magiamgia') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection