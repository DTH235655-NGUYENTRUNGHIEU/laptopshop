@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Cập nhật tình trạng đơn hàng</div>
    <div class="card-body">
        <form action="{{ route('admin.tinhtrang.sua', ['id' => $tinhtrang->id]) }}" method="post">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="tinhtrang">Tên tình trạng <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('tinhtrang') is-invalid @enderror" id="tinhtrang" name="tinhtrang" value="{{ old('tinhtrang', $tinhtrang->tinhtrang) }}" required />
                @error('tinhtrang')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Cập nhật</button>
            <a href="{{ route('admin.tinhtrang') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection