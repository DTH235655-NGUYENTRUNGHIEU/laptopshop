<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhieuBaoHanh extends Model
{
    protected $table = 'phieubaohanh';

    protected $fillable = [
        'maphieu',
        'hoadon_chitiet_id',
        'sanpham_id',
        'user_id',
        'nhanvien_id',
        'serial_number',
        'tensanpham',
        'ten_khachhang',
        'sodienthoai',
        'ngaytiepnhan',
        'ngaytra_dukien',
        'ngaytra_thucte',
        'mota_loi',
        'phukien_kemtheo',
        'ketqua_xuly',
        'chiphi_phatsinh',
        'trangthai',
    ];

    protected function casts(): array
    {
        return [
            'ngaytiepnhan' => 'datetime',
            'ngaytra_dukien' => 'date',
            'ngaytra_thucte' => 'datetime',
        ];
    }

    public static function danhSachTrangThai(): array
    {
        return [
            'cho_tiep_nhan' => ['label' => 'Chờ tiếp nhận', 'badge' => 'bg-warning text-dark'],
            'dang_kiem_tra' => ['label' => 'Đang kiểm tra', 'badge' => 'bg-info text-dark'],
            'gui_hang' => ['label' => 'Đã gửi trung tâm hãng', 'badge' => 'bg-primary'],
            'da_sua_xong' => ['label' => 'Đã sửa xong - Chờ trả', 'badge' => 'bg-success'],
            'da_tra_khach' => ['label' => 'Đã trả máy cho khách', 'badge' => 'bg-secondary'],
            'tu_choi' => ['label' => 'Từ chối bảo hành', 'badge' => 'bg-danger'],
        ];
    }

    public function HoaDon_ChiTiet(): BelongsTo
    {
        return $this->belongsTo(HoaDon_ChiTiet::class, 'hoadon_chitiet_id', 'id');
    }

    public function SanPham(): BelongsTo
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id', 'id');
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function NhanVien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nhanvien_id', 'id');
    }
}