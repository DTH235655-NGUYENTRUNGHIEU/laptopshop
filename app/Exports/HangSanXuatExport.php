<?php

namespace App\Exports;

use App\Models\HangSanXuat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HangSanXuatExport implements FromCollection, WithHeadings, WithCustomStartCell, WithMapping
{
    public function headings(): array
    {
        return [
            'tenhang',
            'tenhang_slug',
            'hinhanh',
            'quocgia',
        ];
    }

    public function map($row): array
    {
        return [
            $row->tenhang,
            $row->tenhang_slug,
            $row->hinhanh,
            $row->quocgia,
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function collection(): Collection
    {
        return HangSanXuat::orderBy('id')->get();
    }
}