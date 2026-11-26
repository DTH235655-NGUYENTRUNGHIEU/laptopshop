<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NhaCungCap extends Model
{
    protected $table = 'nhacungcap';

    protected $fillable = [
        'ten_nhacungcap',
        'sodienthoai',
        'email',
        'diachi',
        'masothue',
        'ghichu',
    ];

    public function PhieuNhap(): HasMany
    {
        return $this->hasMany(PhieuNhap::class, 'nhacungcap_id', 'id');
    }
}