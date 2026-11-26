@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-file-circle-plus text-primary me-1"></i> Lập phiếu nhập kho mới
    </div>
    <div class="card-body">
        <form action="{{ route('admin.phieunhap.them') }}" method="post">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label" for="maphieunhap">Mã phiếu nhập <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase @error('maphieunhap') is-invalid @enderror" id="maphieunhap" name="maphieunhap" value="{{ old('maphieunhap', $maPhieuGoiY) }}" required />
                    @error('maphieunhap') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-5">
                    <label class="form-label" for="nhacungcap_id">Nhà cung cấp <span class="text-danger">*</span></label>
                    <select class="form-select @error('nhacungcap_id') is-invalid @enderror" id="nhacungcap_id" name="nhacungcap_id" required>
                        <option value="">-- Chọn nhà phân phối --</option>
                        @foreach($nhacungcap as $ncc)
                            <option value="{{ $ncc->id }}" {{ old('nhacungcap_id') == $ncc->id ? 'selected' : '' }}>
                                {{ $ncc->ten_nhacungcap }}
                            </option>
                        @endforeach
                    </select>
                    @error('nhacungcap_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ngaynhap">Ngày giờ nhập <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('ngaynhap') is-invalid @enderror" id="ngaynhap" name="ngaynhap" value="{{ old('ngaynhap', date('Y-m-d\TH:i')) }}" required />
                    @error('ngaynhap') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="ghichu">Ghi chú lô hàng</label>
                    <input type="text" class="form-control" id="ghichu" name="ghichu" value="{{ old('ghichu') }}" placeholder="VD: Lô hàng nhập tháng 11, đầy đủ tem seal..." />
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-primary mb-0">Danh sách Laptop nhập kho</h6>
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnAddRow">
                    <i class="fa-solid fa-plus"></i> Thêm dòng sản phẩm
                </button>
            </div>

            @if($errors->has('sanpham_id.*') || $errors->has('soluong_nhap.*') || $errors->has('gianhap.*'))
                <div class="alert alert-danger py-2 small">
                    Vui lòng kiểm tra lại: mọi dòng phải chọn sản phẩm, số lượng tối thiểu 1 và giá nhập tối thiểu 1.000đ.
                </div>
            @endif

            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm align-middle mb-0" id="tblChiTiet">
                    <thead class="table-light">
                        <tr>
                            <th width="44%">Mẫu Laptop <span class="text-danger">*</span></th>
                            <th width="14%" class="text-center">Số lượng <span class="text-danger">*</span></th>
                            <th width="20%" class="text-end">Giá nhập / máy (VNĐ) <span class="text-danger">*</span></th>
                            <th width="17%" class="text-end">Thành tiền</th>
                            <th width="5%" class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="bodyChiTiet"></tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Tổng giá trị phiếu nhập:</td>
                            <td class="text-end fw-bold text-danger fs-6" id="tongTienDisplay">0đ</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Lưu phiếu & Cộng tồn kho
            </button>
            <a href="{{ route('admin.phieunhap') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>

<!-- Template HTML dòng sản phẩm -->
<template id="rowTemplate">
    <tr>
        <td>
            <select name="sanpham_id[]" class="form-select form-select-sm select-sp" required>
                <option value="">-- Chọn Laptop --</option>
                @foreach($sanpham as $sp)
                    <option value="{{ $sp->id }}" data-gia-goiy="{{ (int) ($sp->dongia * 0.8) }}">
                        [{{ $sp->masanpham }}] {{ $sp->tensanpham }} (Tồn: {{ $sp->soluong }})
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="soluong_nhap[]" class="form-control form-control-sm text-center input-sl" min="1" value="1" required />
        </td>
        <td>
            <input type="number" name="gianhap[]" class="form-control form-control-sm text-end input-gia" min="1000" step="1000" value="10000000" required />
        </td>
        <td class="text-end fw-semibold cell-thanhtien">10.000.000đ</td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm btn-remove" title="Xóa dòng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@section('javascript')
<script>
    const tbody = document.getElementById('bodyChiTiet');
    const template = document.getElementById('rowTemplate');
    const tongTienDisplay = document.getElementById('tongTienDisplay');

    function formatVND(num) {
        return Number(num || 0).toLocaleString('vi-VN') + 'đ';
    }

    function tinhTong() {
        let tong = 0;
        tbody.querySelectorAll('tr').forEach((tr) => {
            const sl = parseInt(tr.querySelector('.input-sl').value, 10) || 0;
            const gia = parseInt(tr.querySelector('.input-gia').value, 10) || 0;
            const thanhTien = sl * gia;
            tr.querySelector('.cell-thanhtien').textContent = formatVND(thanhTien);
            tong += thanhTien;
        });
        tongTienDisplay.textContent = formatVND(tong);
    }

    function themDong() {
        const clone = template.content.cloneNode(true);
        const tr = clone.querySelector('tr');

        const selectSp = tr.querySelector('.select-sp');
        const inputGia = tr.querySelector('.input-gia');

        selectSp.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.dataset.giaGoiy) {
                inputGia.value = Math.round(Number(opt.dataset.giaGoiy) / 10000) * 10000;
                tinhTong();
            }
        });

        tr.querySelector('.input-sl').addEventListener('input', tinhTong);
        inputGia.addEventListener('input', tinhTong);
        tr.querySelector('.btn-remove').addEventListener('click', function () {
            if (tbody.querySelectorAll('tr').length > 1) {
                tr.remove();
                tinhTong();
            }
        });

        tbody.appendChild(tr);
        tinhTong();
    }

    document.getElementById('btnAddRow').addEventListener('click', themDong);
    themDong();
</script>
@endsection