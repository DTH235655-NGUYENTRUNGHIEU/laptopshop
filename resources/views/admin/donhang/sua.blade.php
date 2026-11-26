@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold">
            <i class="fa-solid fa-pen-to-square text-primary me-1"></i>
            Cập nhật Đơn hàng: <code>{{ $donhang->madonhang }}</code>
        </span>
        <div>
            @if($donhang->HoaDon)
                <a href="{{ route('admin.hoadon.chitiet', ['id' => $donhang->HoaDon->id]) }}" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-receipt"></i> Xem hóa đơn {{ $donhang->HoaDon->sohoadon }}
                </a>
            @elseif((int) $donhang->tinhtrang_id !== 6)
                <a href="{{ route('admin.hoadon.lap', ['donhang_id' => $donhang->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-file-circle-plus"></i> Lập hóa đơn VAT
                </a>
            @endif
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.donhang.sua', ['id' => $donhang->id]) }}" method="post">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="hoten_nguoinhan">Họ tên người nhận <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('hoten_nguoinhan') is-invalid @enderror" id="hoten_nguoinhan" name="hoten_nguoinhan" value="{{ old('hoten_nguoinhan', $donhang->hoten_nguoinhan) }}" required />
                    @error('hoten_nguoinhan') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="dienthoaigiaohang">Điện thoại giao hàng <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('dienthoaigiaohang') is-invalid @enderror" id="dienthoaigiaohang" name="dienthoaigiaohang" value="{{ old('dienthoaigiaohang', $donhang->dienthoaigiaohang) }}" required />
                    @error('dienthoaigiaohang') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="tinhtrang_id">Tình trạng đơn hàng <span class="text-danger">*</span></label>
                    <select class="form-select @error('tinhtrang_id') is-invalid @enderror" id="tinhtrang_id" name="tinhtrang_id" required>
                        @foreach($tinhtrang as $tt)
                            <option value="{{ $tt->id }}" {{ old('tinhtrang_id', $donhang->tinhtrang_id) == $tt->id ? 'selected' : '' }}>
                                {{ $tt->tinhtrang }}
                            </option>
                        @endforeach
                    </select>
                    @error('tinhtrang_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="diachigiaohang">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('diachigiaohang') is-invalid @enderror" id="diachigiaohang" name="diachigiaohang" value="{{ old('diachigiaohang', $donhang->diachigiaohang) }}" required />
                    @error('diachigiaohang') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="phuongthuc_thanhtoan">Phương thức thanh toán</label>
                    <select class="form-select @error('phuongthuc_thanhtoan') is-invalid @enderror" id="phuongthuc_thanhtoan" name="phuongthuc_thanhtoan">
                        @foreach(['COD' => 'Thanh toán khi nhận hàng (COD)', 'CHUYENKHOAN' => 'Chuyển khoản ngân hàng', 'VNPAY' => 'VNPay', 'MOMO' => 'Ví MoMo'] as $key => $label)
                            <option value="{{ $key }}" {{ old('phuongthuc_thanhtoan', $donhang->phuongthuc_thanhtoan) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="trangthai_thanhtoan" name="trangthai_thanhtoan" value="1" {{ old('trangthai_thanhtoan', $donhang->trangthai_thanhtoan) ? 'checked' : '' }} />
                        <label class="form-check-label fw-semibold" for="trangthai_thanhtoan">Đã thanh toán tiền</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label" for="ghichu">Ghi chú đơn hàng</label>
                    <textarea class="form-control" id="ghichu" name="ghichu" rows="2">{{ old('ghichu', $donhang->ghichu) }}</textarea>
                </div>
            </div>

            <h6 class="text-primary border-bottom pb-2 mb-3">Danh sách sản phẩm trong đơn</h6>
            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th width="16%">Mã SKU</th>
                            <th>Tên Laptop</th>
                            <th width="10%" class="text-center">SL mua</th>
                            <th width="16%" class="text-end">Đơn giá chốt</th>
                            <th width="18%" class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $tamTinh = 0; @endphp
                        @foreach($donhang->DonHang_ChiTiet as $ct)
                            @php
                                $thanhTien = $ct->soluongban * $ct->dongiaban;
                                $tamTinh += $thanhTien;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td><code>{{ $ct->SanPham->masanpham ?? '-' }}</code></td>
                                <td class="fw-semibold">{{ $ct->SanPham->tensanpham ?? 'Sản phẩm đã xóa' }}</td>
                                <td class="text-center">{{ $ct->soluongban }}</td>
                                <td class="text-end">{{ number_format($ct->dongiaban, 0, ',', '.') }}đ</td>
                                <td class="text-end fw-semibold">{{ number_format($thanhTien, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end">Tạm tính:</td>
                            <td class="text-end">{{ number_format($tamTinh, 0, ',', '.') }}đ</td>
                        </tr>
                        @if($donhang->tiengiam > 0)
                            <tr>
                                <td colspan="5" class="text-end text-success">
                                    Giảm giá {{ $donhang->MaGiamGia ? '(' . $donhang->MaGiamGia->macode . ')' : '' }}:
                                </td>
                                <td class="text-end text-success">-{{ number_format($donhang->tiengiam, 0, ',', '.') }}đ</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Tổng cộng đơn hàng:</td>
                            <td class="text-end fw-bold text-danger fs-6">{{ number_format($donhang->tongtien, 0, ',', '.') }}đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi</button>
            <a href="{{ route('admin.donhang') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection