@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Thêm nhà cung cấp</div>
    <div class="card-body">
        <form action="{{ route('admin.nhacungcap.them') }}" method="post">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="ten_nhacungcap">Tên nhà cung cấp <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ten_nhacungcap') is-invalid @enderror" id="ten_nhacungcap" name="ten_nhacungcap" value="{{ old('ten_nhacungcap') }}" required />
                    @error('ten_nhacungcap')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="sodienthoai">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('sodienthoai') is-invalid @enderror" id="sodienthoai" name="sodienthoai" value="{{ old('sodienthoai') }}" required />
                    @error('sodienthoai')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="masothue">Mã số thuế</label>
                    <input type="text" class="form-control @error('masothue') is-invalid @enderror" id="masothue" name="masothue" value="{{ old('masothue') }}" />
                    @error('masothue')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-5">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" />
                    @error('email')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-md-7">
                    <label class="form-label" for="diachi">Địa chỉ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('diachi') is-invalid @enderror" id="diachi" name="diachi" value="{{ old('diachi') }}" required />
                    @error('diachi')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="ghichu">Ghi chú</label>
                    <textarea class="form-control @error('ghichu') is-invalid @enderror" id="ghichu" name="ghichu" rows="3">{{ old('ghichu') }}</textarea>
                    @error('ghichu')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu mới</button>
                <a href="{{ route('admin.nhacungcap') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection