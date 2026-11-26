<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DanhGia extends Model
{
    protected $table = 'danhgia';

    protected $fillable = [
        'sanpham_id',
        'user_id',
        'sosao',
        'noidung',
        'kiemduyet',
    ];

    protected function casts(): array
    {
        return [
            'kiemduyet' => 'boolean',
        ];
    }

    public function SanPham(): BelongsTo
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id', 'id');
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}