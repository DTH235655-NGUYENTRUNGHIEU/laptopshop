<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhieuNhap_ChiTiet extends Model
{
    protected $table = 'phieunhap_chitiet';

    protected $fillable = [
        'phieunhap_id',
        'sanpham_id',
        'soluong_nhap',
        'gianhap',
        'thanhtien',
    ];

    public function PhieuNhap(): BelongsTo
    {
        return $this->belongsTo(PhieuNhap::class, 'phieunhap_id', 'id');
    }

    public function SanPham(): BelongsTo
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id', 'id');
    }
}