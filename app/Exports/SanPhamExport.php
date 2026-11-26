<?php

namespace App\Exports;

use App\Models\SanPham;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SanPhamExport implements FromCollection, WithHeadings, WithCustomStartCell, WithMapping
{
    public function headings(): array
    {
        return [
            'loaisanpham_id',
            'hangsanxuat_id',
            'masanpham',
            'tensanpham',
            'tensanpham_slug',
            'soluong',
            'dongia',
            'giakhuyenmai',
            'hinhanh',
            'cpu',
            'ram',
            'ocung',
            'carddohoa',
            'manhinh',
            'hedieuhanh',
            'pin',
            'trongluong',
            'mausac',
            'baohanh',
            'noibat',
            'trangthai',
        ];
    }

    public function map($row): array
    {
        return [
            $row->loaisanpham_id,
            $row->hangsanxuat_id,
            $row->masanpham,
            $row->tensanpham,
            $row->tensanpham_slug,
            $row->soluong,
            $row->dongia,
            $row->giakhuyenmai,
            $row->hinhanh,
            $row->cpu,
            $row->ram,
            $row->ocung,
            $row->carddohoa,
            $row->manhinh,
            $row->hedieuhanh,
            $row->pin,
            $row->trongluong,
            $row->mausac,
            $row->baohanh,
            $row->noibat ? 1 : 0,
            $row->trangthai ? 1 : 0,
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function collection(): Collection
    {
        return SanPham::orderBy('id')->get();
    }
}