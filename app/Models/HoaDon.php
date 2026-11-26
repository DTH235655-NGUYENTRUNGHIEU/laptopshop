<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HoaDon extends Model
{
    protected $table = 'hoadon';

    protected $fillable = [
        'sohoadon',
        'donhang_id',
        'user_id',
        'nhanvien_id',
        'ngaylaphoadon',
        'ten_khachhang',
        'sodienthoai',
        'diachi',
        'ten_congty',
        'masothue',
        'email_nhanhoadon',
        'tamtinh',
        'tiengiam',
        'thue_vat_phantram',
        'tienthue_vat',
        'tongthanhtoan',
        'phuongthuc_thanhtoan',
        'trangthai',
        'ghichu',
    ];

    protected function casts(): array
    {
        return [
            'ngaylaphoadon' => 'datetime',
        ];
    }

    public function DonHang(): BelongsTo
    {
        return $this->belongsTo(DonHang::class, 'donhang_id', 'id');
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function NhanVien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nhanvien_id', 'id');
    }

    public function HoaDon_ChiTiet(): HasMany
    {
        return $this->hasMany(HoaDon_ChiTiet::class, 'hoadon_id', 'id');
    }
}