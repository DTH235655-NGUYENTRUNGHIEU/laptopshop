<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BaiViet extends Model
{
    protected $table = 'baiviet';

    protected $fillable = [
        'chude_id',
        'user_id',
        'tieude',
        'tieude_slug',
        'hinhanh',
        'tomtat',
        'noidung',
        'luotxem',
        'kiemduyet',
        'kichhoat',
    ];

    protected function casts(): array
    {
        return [
            'kiemduyet' => 'boolean',
            'kichhoat' => 'boolean',
        ];
    }

    public function ChuDe(): BelongsTo
    {
        return $this->belongsTo(ChuDe::class, 'chude_id', 'id');
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function BinhLuanBaiViet(): HasMany
    {
        return $this->hasMany(BinhLuanBaiViet::class, 'baiviet_id', 'id');
    }
}