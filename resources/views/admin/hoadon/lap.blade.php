@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">
        <i class="fa-solid fa-file-circle-plus text-primary me-1"></i>
        Xuất Hóa đơn VAT từ đơn hàng: <code>{{ $donhang->madonhang }}</code>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.hoadon.lap', ['donhang_id' => $donhang->id]) }}" method="post">
            @csrf

            <h6 class="text-primary border-bottom pb-2 mb-3">1. Thông tin chứng từ & Khách hàng</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label" for="sohoadon">Số hóa đơn <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase @error('sohoadon') is-invalid @enderror" id="sohoadon" name="sohoadon" value="{{ old('sohoadon', $soHoaDonGoiY) }}" required />
                    @error('sohoadon') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="ngaylaphoadon">Ngày lập hóa đơn <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('ngaylaphoadon') is-invalid @enderror" id="ngaylaphoadon" name="ngaylaphoadon" value="{{ old('ngaylaphoadon', date('Y-m-d\TH:i')) }}" required />
                    @error('ngaylaphoadon') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="phuongthuc_thanhtoan">Phương thức thanh toán</label>
                    <select class="form-select" id="phuongthuc_thanhtoan" name="phuongthuc_thanhtoan">
                        @foreach(['COD' => 'COD', 'CHUYENKHOAN' => 'Chuyển khoản', 'VNPAY' => 'VNPay', 'MOMO' => 'MoMo'] as $key => $label)
                            <option value="{{ $key }}" {{ old('phuongthuc_thanhtoan', $donhang->phuongthuc_thanhtoan) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="trangthai">Trạng thái hóa đơn</label>
                    <select class="form-select" id="trangthai" name="trangthai">
                        <option value="da_thanh_toan" {{ old('trangthai', 'da_thanh_toan') === 'da_thanh_toan' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="chua_thanh_toan" {{ old('trangthai') === 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ten_khachhang">Tên khách hàng <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ten_khachhang') is-invalid @enderror" id="ten_khachhang" name="ten_khachhang" value="{{ old('ten_khachhang', $donhang->hoten_nguoinhan) }}" required />
                    @error('ten_khachhang') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="sodienthoai">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('sodienthoai') is-invalid @enderror" id="sodienthoai" name="sodienthoai" value="{{ old('sodienthoai', $donhang->dienthoaigiaohang) }}" required />
                    @error('sodienthoai') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-5">
                    <label class="form-label" for="diachi">Địa chỉ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('diachi') is-invalid @enderror" id="diachi" name="diachi" value="{{ old('diachi', $donhang->diachigiaohang) }}" required />
                    @error('diachi') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-5">
                    <label class="form-label" for="ten_congty">Tên công ty (nếu xuất hóa đơn đỏ VAT)</label>
                    <input type="text" class="form-control" id="ten_congty" name="ten_congty" value="{{ old('ten_congty') }}" placeholder="Bỏ trống nếu khách cá nhân" />
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="masothue">Mã số thuế công ty</label>
                    <input type="text" class="form-control" id="masothue" name="masothue" value="{{ old('masothue') }}" />
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="email_nhanhoadon">Email nhận hóa đơn điện tử</label>
                    <input type="email" class="form-control @error('email_nhanhoadon') is-invalid @enderror" id="email_nhanhoadon" name="email_nhanhoadon" value="{{ old('email_nhanhoadon', $donhang->User->email ?? '') }}" />
                    @error('email_nhanhoadon') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>
            </div>

            <h6 class="text-primary border-bottom pb-2 mb-3">2. Nhập Serial Number / Service Tag từng máy xuất kho</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="14%">SKU / Tồn kho</th>
                            <th width="34%">Tên Laptop</th>
                            <th width="22%">Serial Number / Service Tag</th>
                            <th width="10%" class="text-center">Bảo hành</th>
                            <th width="15%" class="text-end">Đơn giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($danhSachMay as $idx => $may)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="small">
                                    <code>{{ $may['masanpham'] }}</code>
                                    <div class="text-muted">Tồn: {{ $may['tonkho'] }}</div>
                                </td>
                                <td class="fw-semibold">{{ $may['tensanpham'] }}</td>
                                <td>
                                    <input type="text" name="serial_number[]" class="form-control form-control-sm text-uppercase font-monospace" value="{{ old('serial_number.' . $idx) }}" placeholder="VD: PF4X9K11-VN" />
                                </td>
                                <td class="text-center">{{ $may['baohanh'] }} tháng</td>
                                <td class="text-end fw-semibold">{{ number_format($may['dongia'], 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row g-3 align-items-start mb-3">
                <div class="col-md-7">
                    <label class="form-label" for="ghichu">Ghi chú xuất kho / bảo hành</label>
                    <textarea class="form-control" id="ghichu" name="ghichu" rows="3" placeholder="VD: Đã kiểm tra ngoại hình, giao kèm sạc zin và phiếu bảo hành...">{{ old('ghichu', $donhang->ghichu) }}</textarea>
                </div>

                <div class="col-md-5">
                    <div class="border rounded p-3 bg-light" id="vatSummary" data-tamtinh="{{ $tamTinh }}" data-tiengiam="{{ (int) $donhang->tiengiam }}">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <strong>{{ number_format($tamTinh, 0, ',', '.') }}đ</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giảm giá voucher:</span>
                            <strong>-{{ number_format($donhang->tiengiam, 0, ',', '.') }}đ</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="thue_vat_phantram" class="mb-0">Thuế VAT (%):</label>
                            <input type="number" min="0" max="20" class="form-control form-control-sm text-end" style="width: 80px;" id="thue_vat_phantram" name="thue_vat_phantram" value="{{ old('thue_vat_phantram', 10) }}" required />
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tiền thuế VAT:</span>
                            <strong id="tienVatDisplay">0đ</strong>
                        </div>
                        <hr class="my-2" />
                        <div class="d-flex justify-content-between fs-5">
                            <span class="fw-bold">Tổng thanh toán:</span>
                            <span class="fw-bold text-danger" id="tongThanhToanDisplay">0đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-check-double"></i> Xác nhận xuất hóa đơn & Trừ kho
            </button>
            <a href="{{ route('admin.donhang') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const vatBox = document.getElementById('vatSummary');
    const vatInput = document.getElementById('thue_vat_phantram');
    const tienVatDisplay = document.getElementById('tienVatDisplay');
    const tongThanhToanDisplay = document.getElementById('tongThanhToanDisplay');

    function capNhatVat() {
        const tamTinh = parseInt(vatBox.dataset.tamtinh, 10) || 0;
        const tienGiam = parseInt(vatBox.dataset.tiengiam, 10) || 0;
        const vatPct = Math.max(0, Math.min(20, parseInt(vatInput.value, 10) || 0));

        const sauGiam = Math.max(0, tamTinh - tienGiam);
        const tienVat = Math.round(sauGiam * vatPct / 100);
        const tong = sauGiam + tienVat;

        tienVatDisplay.textContent = tienVat.toLocaleString('vi-VN') + 'đ';
        tongThanhToanDisplay.textContent = tong.toLocaleString('vi-VN') + 'đ';
    }

    vatInput.addEventListener('input', capNhatVat);
    capNhatVat();
</script>
@endsection