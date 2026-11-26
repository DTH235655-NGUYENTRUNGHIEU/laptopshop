<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanPhamHinhAnh extends Model
{
    protected $table = 'sanpham_hinhanh';

    protected $fillable = [
        'sanpham_id',
        'hinhanh',
        'thutu',
    ];

    public function SanPham(): BelongsTo
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id', 'id');
    }
}