<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaGiamGia extends Model
{
    protected $table = 'magiamgia';

    protected $fillable = [
        'macode',
        'loai',
        'giatri',
        'donhang_toithieu',
        'soluong',
        'ngaybatdau',
        'ngayketthuc',
        'kichhoat',
    ];

    protected function casts(): array
    {
        return [
            'ngaybatdau' => 'datetime',
            'ngayketthuc' => 'datetime',
            'kichhoat' => 'boolean',
        ];
    }

    public function DonHang(): HasMany
    {
        return $this->hasMany(DonHang::class, 'magiamgia_id', 'id');
    }
}