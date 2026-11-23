<?php

namespace App\Exports;

use App\Models\HangSanXuat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class HangSanXuatExport implements FromCollection
{
    public function collection(): Collection
    {
        return HangSanXuat::all();
    }
}
