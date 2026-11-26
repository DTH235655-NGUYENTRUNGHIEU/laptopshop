<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LienHe extends Model
{
    protected $table = 'lienhe';

    protected $fillable = [
        'hoten',
        'email',
        'sodienthoai',
        'tieude',
        'noidung',
        'trangthai',
        'phanhoi_admin',
    ];
}