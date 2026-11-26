@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-pen-to-square text-primary me-1"></i> Cập nhật Laptop: {{ $sanpham->tensanpham }}
    </div>
    <div class="card-body">
        <form action="{{ route('admin.sanpham.sua', ['id' => $sanpham->id]) }}" method="post" enctype="multipart/form-data">
            @csrf

            <h6 class="text-primary border-bottom pb-2 mb-3">1. Thông tin cơ bản & Giá bán</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="loaisanpham_id">Phân khúc Laptop <span class="text-danger">*</span></label>
                    <select class="form-select @error('loaisanpham_id') is-invalid @enderror" id="loaisanpham_id" name="loaisanpham_id" required>
                        @foreach($loaisanpham as $loai)
                            <option value="{{ $loai->id }}" {{ old('loaisanpham_id', $sanpham->loaisanpham_id) == $loai->id ? 'selected' : '' }}>
                                {{ $loai->tenloai }}
                            </option>
                        @endforeach
                    </select>
                    @error('loaisanpham_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="hangsanxuat_id">Hãng sản xuất <span class="text-danger">*</span></label>
                    <select class="form-select @error('hangsanxuat_id') is-invalid @enderror" id="hangsanxuat_id" name="hangsanxuat_id" required>
                        @foreach($hangsanxuat as $hang)
                            <option value="{{ $hang->id }}" {{ old('hangsanxuat_id', $sanpham->hangsanxuat_id) == $hang->id ? 'selected' : '' }}>
                                {{ $hang->tenhang }}
                            </option>
                        @endforeach
                    </select>
                    @error('hangsanxuat_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="masanpham">Mã SKU sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('masanpham') is-invalid @enderror" id="masanpham" name="masanpham" value="{{ old('masanpham', $sanpham->masanpham) }}" required />
                    @error('masanpham') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="tensanpham">Tên đầy đủ Laptop <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tensanpham') is-invalid @enderror" id="tensanpham" name="tensanpham" value="{{ old('tensanpham', $sanpham->tensanpham) }}" required />
                    @error('tensanpham') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="soluong">Tồn kho <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control @error('soluong') is-invalid @enderror" id="soluong" name="soluong" value="{{ old('soluong', $sanpham->soluong) }}" required />
                    @error('soluong') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="dongia">Giá niêm yết (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" min="0" step="1000" class="form-control @error('dongia') is-invalid @enderror" id="dongia" name="dongia" value="{{ old('dongia', $sanpham->dongia) }}" required />
                    @error('dongia') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="giakhuyenmai">Giá KM (nếu có)</label>
                    <input type="number" min="0" step="1000" class="form-control @error('giakhuyenmai') is-invalid @enderror" id="giakhuyenmai" name="giakhuyenmai" value="{{ old('giakhuyenmai', $sanpham->giakhuyenmai) }}" />
                    @error('giakhuyenmai') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>
            </div>

            <h6 class="text-primary border-bottom pb-2 mb-3">2. Thông số kỹ thuật Laptop</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="cpu">Vi xử lý (CPU) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('cpu') is-invalid @enderror" id="cpu" name="cpu" value="{{ old('cpu', $sanpham->cpu) }}" required />
                    @error('cpu') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ram">Bộ nhớ RAM <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ram') is-invalid @enderror" id="ram" name="ram" value="{{ old('ram', $sanpham->ram) }}" required />
                    @error('ram') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ocung">Ổ cứng <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ocung') is-invalid @enderror" id="ocung" name="ocung" value="{{ old('ocung', $sanpham->ocung) }}" required />
                    @error('ocung') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="carddohoa">Card đồ họa (VGA) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('carddohoa') is-invalid @enderror" id="carddohoa" name="carddohoa" value="{{ old('carddohoa', $sanpham->carddohoa) }}" required />
                    @error('carddohoa') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="manhinh">Màn hình <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('manhinh') is-invalid @enderror" id="manhinh" name="manhinh" value="{{ old('manhinh', $sanpham->manhinh) }}" required />
                    @error('manhinh') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="hedieuhanh">Hệ điều hành</label>
                    <input type="text" class="form-control @error('hedieuhanh') is-invalid @enderror" id="hedieuhanh" name="hedieuhanh" value="{{ old('hedieuhanh', $sanpham->hedieuhanh) }}" />
                    @error('hedieuhanh') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="pin">Pin</label>
                    <input type="text" class="form-control @error('pin') is-invalid @enderror" id="pin" name="pin" value="{{ old('pin', $sanpham->pin) }}" />
                    @error('pin') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="trongluong">Trọng lượng (kg)</label>
                    <input type="number" step="0.01" min="0" class="form-control @error('trongluong') is-invalid @enderror" id="trongluong" name="trongluong" value="{{ old('trongluong', $sanpham->trongluong) }}" />
                    @error('trongluong') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="mausac">Màu sắc</label>
                    <input type="text" class="form-control @error('mausac') is-invalid @enderror" id="mausac" name="mausac" value="{{ old('mausac', $sanpham->mausac) }}" />
                    @error('mausac') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="baohanh">Bảo hành (tháng) <span class="text-danger">*</span></label>
                    <input type="number" min="1" max="60" class="form-control @error('baohanh') is-invalid @enderror" id="baohanh" name="baohanh" value="{{ old('baohanh', $sanpham->baohanh) }}" required />
                    @error('baohanh') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>
            </div>

            <h6 class="text-primary border-bottom pb-2 mb-3">3. Hình ảnh, Mô tả & Hiển thị</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="hinhanh">Ảnh đại diện chính (bỏ trống nếu giữ nguyên)</label>
                    @if($sanpham->hinhanh && file_exists(public_path('storage/' . $sanpham->hinhanh)))
                        <div class="mb-2">
                            <img src="{{ asset('public/storage/' . $sanpham->hinhanh) }}" alt="{{ $sanpham->tensanpham }}" class="img-thumbnail" style="max-height: 110px;" />
                        </div>
                    @endif
                    <input type="file" class="form-control @error('hinhanh') is-invalid @enderror" id="hinhanh" name="hinhanh" accept="image/*" />
                    @error('hinhanh') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="hinhanh_phu">Bổ sung thêm ảnh phụ (chọn nhiều ảnh)</label>
                    <input type="file" class="form-control @error('hinhanh_phu.*') is-invalid @enderror" id="hinhanh_phu" name="hinhanh_phu[]" accept="image/*" multiple />
                    @error('hinhanh_phu.*') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror

                    @if($sanpham->SanPhamHinhAnh->count() > 0)
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($sanpham->SanPhamHinhAnh as $anhPhu)
                                <div class="position-relative border rounded p-1 text-center" style="width: 96px;">
                                    @if(file_exists(public_path('storage/' . $anhPhu->hinhanh)))
                                        <img src="{{ asset('public/storage/' . $anhPhu->hinhanh) }}" alt="Ảnh phụ" style="width: 84px; height: 60px; object-fit: cover;" />
                                    @else
                                        <div class="small text-muted py-3">Chưa có file</div>
                                    @endif
                                    <a href="{{ route('admin.sanpham.xoaanhphu', ['id' => $anhPhu->id]) }}"
                                       class="btn btn-danger btn-sm w-100 mt-1 py-0"
                                       onclick="return confirm('Xóa ảnh phụ này?')">
                                        <i class="fa-solid fa-trash"></i> Xóa
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <label class="form-label" for="motasanpham">Mô tả chi tiết sản phẩm</label>
                    <textarea class="form-control" id="motasanpham" name="motasanpham" rows="6">{{ old('motasanpham', $sanpham->motasanpham) }}</textarea>
                </div>

                <div class="col-12 d-flex gap-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="noibat" name="noibat" value="1" {{ old('noibat', $sanpham->noibat) ? 'checked' : '' }} />
                        <label class="form-check-label" for="noibat">Hiển thị mục Laptop nổi bật</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="trangthai" name="trangthai" value="1" {{ old('trangthai', $sanpham->trangthai) ? 'checked' : '' }} />
                        <label class="form-check-label" for="trangthai">Đang kinh doanh</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Cập nhật</button>
            <a href="{{ route('admin.sanpham') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#motasanpham'))
        .catch(error => console.error(error));
</script>
@endsection