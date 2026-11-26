<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoaDon_ChiTiet extends Model
{
    protected $table = 'hoadon_chitiet';

    protected $fillable = [
        'hoadon_id',
        'sanpham_id',
        'tensanpham_luu',
        'serial_number',
        'soluong',
        'dongia',
        'thanhtien',
        'baohanh_thang',
        'han_baohanh',
    ];

    protected function casts(): array
    {
        return [
            'han_baohanh' => 'date',
        ];
    }

    public function HoaDon(): BelongsTo
    {
        return $this->belongsTo(HoaDon::class, 'hoadon_id', 'id');
    }

    public function SanPham(): BelongsTo
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id', 'id');
    }
}