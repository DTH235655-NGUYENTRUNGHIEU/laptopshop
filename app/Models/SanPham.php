<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SanPham extends Model
{
    protected $table = 'sanpham';

    protected $fillable = [
        'loaisanpham_id',
        'hangsanxuat_id',
        'masanpham',
        'tensanpham',
        'tensanpham_slug',
        'soluong',
        'dongia',
        'giakhuyenmai',
        'hinhanh',
        'cpu',
        'ram',
        'ocung',
        'carddohoa',
        'manhinh',
        'hedieuhanh',
        'pin',
        'trongluong',
        'mausac',
        'baohanh',
        'motasanpham',
        'noibat',
        'trangthai',
    ];

    protected function casts(): array
    {
        return [
            'noibat' => 'boolean',
            'trangthai' => 'boolean',
        ];
    }

    public function LoaiSanPham(): BelongsTo
    {
        return $this->belongsTo(LoaiSanPham::class, 'loaisanpham_id', 'id');
    }

    public function HangSanXuat(): BelongsTo
    {
        return $this->belongsTo(HangSanXuat::class, 'hangsanxuat_id', 'id');
    }

    public function SanPhamHinhAnh(): HasMany
    {
        return $this->hasMany(SanPhamHinhAnh::class, 'sanpham_id', 'id');
    }

    public function DanhGia(): HasMany
    {
        return $this->hasMany(DanhGia::class, 'sanpham_id', 'id');
    }

    public function DonHang_ChiTiet(): HasMany
    {
        return $this->hasMany(DonHang_ChiTiet::class, 'sanpham_id', 'id');
    }

    public function PhieuNhap_ChiTiet(): HasMany
    {
        return $this->hasMany(PhieuNhap_ChiTiet::class, 'sanpham_id', 'id');
    }

    public function HoaDon_ChiTiet(): HasMany
    {
        return $this->hasMany(HoaDon_ChiTiet::class, 'sanpham_id', 'id');
    }
}