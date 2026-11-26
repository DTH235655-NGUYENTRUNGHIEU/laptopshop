<x-mail::message>
# Xin chào {{ $donhang->hoten_nguoinhan }}!

Cảm ơn bạn đã tin tưởng và đặt mua Laptop tại **{{ config('app.name', 'LaptopShop') }}**.

## Thông tin đơn hàng & giao nhận
- **Mã đơn hàng:** {{ $donhang->madonhang }}
- **Người nhận:** {{ $donhang->hoten_nguoinhan }}
- **Điện thoại:** {{ $donhang->dienthoaigiaohang }}
- **Địa chỉ giao hàng:** {{ $donhang->diachigiaohang }}
- **Phương thức thanh toán:** {{ $donhang->phuongthuc_thanhtoan }}
@if($donhang->ghichu)
- **Ghi chú:** {{ $donhang->ghichu }}
@endif

## Chi tiết sản phẩm đã đặt
<x-mail::table>
| # | Sản phẩm | SL | Đơn giá | Thành tiền |
|:---|:---|:---:|---:|---:|
@php $tamTinh = 0; @endphp
@foreach($donhang->DonHang_ChiTiet as $ct)
@php
    $thanhTien = $ct->soluongban * $ct->dongiaban;
    $tamTinh += $thanhTien;
@endphp
| {{ $loop->iteration }} | {{ $ct->SanPham->tensanpham ?? 'Laptop' }} | {{ $ct->soluongban }} | {{ number_format($ct->dongiaban, 0, ',', '.') }}đ | {{ number_format($thanhTien, 0, ',', '.') }}đ |
@endforeach
</x-mail::table>

- **Tạm tính:** {{ number_format($tamTinh, 0, ',', '.') }}đ
@if($donhang->tiengiam > 0)
- **Giảm giá voucher{{ $donhang->MaGiamGia ? ' (' . $donhang->MaGiamGia->macode . ')' : '' }}:** -{{ number_format($donhang->tiengiam, 0, ',', '.') }}đ
@endif
- **Tổng tiền thanh toán:** **{{ number_format($donhang->tongtien, 0, ',', '.') }}đ**

<x-mail::button :url="route('user.donhang.chitiet', ['id' => $donhang->id])">
Theo dõi đơn hàng của bạn
</x-mail::button>

Trân trọng,<br>
**{{ config('app.name', 'LaptopShop') }}**
</x-mail::message>