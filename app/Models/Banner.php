<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';

    protected $fillable = [
        'tieude',
        'hinhanh',
        'duongdan',
        'vitri',
        'thutu',
        'kichhoat',
    ];

    protected function casts(): array
    {
        return [
            'kichhoat' => 'boolean',
        ];
    }
}