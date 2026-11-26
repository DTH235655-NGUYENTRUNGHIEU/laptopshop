<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhieuNhap extends Model
{
    protected $table = 'phieunhap';

    protected $fillable = [
        'maphieunhap',
        'nhacungcap_id',
        'nhanvien_id',
        'ngaynhap',
        'tongtien',
        'ghichu',
    ];

    protected function casts(): array
    {
        return [
            'ngaynhap' => 'datetime',
        ];
    }

    public function NhaCungCap(): BelongsTo
    {
        return $this->belongsTo(NhaCungCap::class, 'nhacungcap_id', 'id');
    }

    public function NhanVien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nhanvien_id', 'id');
    }

    public function PhieuNhap_ChiTiet(): HasMany
    {
        return $this->hasMany(PhieuNhap_ChiTiet::class, 'phieunhap_id', 'id');
    }
}