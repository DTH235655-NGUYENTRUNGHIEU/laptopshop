<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BinhLuanBaiViet extends Model
{
    protected $table = 'binhluanbaiviet';

    protected $fillable = [
        'baiviet_id',
        'user_id',
        'noidungbinhluan',
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

    public function BaiViet(): BelongsTo
    {
        return $this->belongsTo(BaiViet::class, 'baiviet_id', 'id');
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}