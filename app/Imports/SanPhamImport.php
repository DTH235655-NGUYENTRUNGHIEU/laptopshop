<?php

namespace App\Imports;

use App\Models\SanPham;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SanPhamImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            if (empty($row['masanpham']) || empty($row['tensanpham'])) {
                continue;
            }

            $masanpham = strtoupper(trim((string) $row['masanpham']));
            $tensanpham = trim((string) $row['tensanpham']);
            $slug = !empty($row['tensanpham_slug'])
                ? trim((string) $row['tensanpham_slug'])
                : Str::slug($tensanpham, '-');

            SanPham::updateOrCreate(
                ['masanpham' => $masanpham],
                [
                    'loaisanpham_id' => (int) ($row['loaisanpham_id'] ?? 1),
                    'hangsanxuat_id' => (int) ($row['hangsanxuat_id'] ?? 1),
                    'tensanpham' => $tensanpham,
                    'tensanpham_slug' => $slug,
                    'soluong' => (int) ($row['soluong'] ?? 0),
                    'dongia' => (int) ($row['dongia'] ?? 0),
                    'giakhuyenmai' => ($row['giakhuyenmai'] !== null && $row['giakhuyenmai'] !== '') ? (int) $row['giakhuyenmai'] : null,
                    'hinhanh' => !empty($row['hinhanh']) ? trim((string) $row['hinhanh']) : null,
                    'cpu' => trim((string) ($row['cpu'] ?? 'Đang cập nhật')),
                    'ram' => trim((string) ($row['ram'] ?? 'Đang cập nhật')),
                    'ocung' => trim((string) ($row['ocung'] ?? 'Đang cập nhật')),
                    'carddohoa' => trim((string) ($row['carddohoa'] ?? 'Onboard')),
                    'manhinh' => trim((string) ($row['manhinh'] ?? 'Đang cập nhật')),
                    'hedieuhanh' => !empty($row['hedieuhanh']) ? trim((string) $row['hedieuhanh']) : null,
                    'pin' => !empty($row['pin']) ? trim((string) $row['pin']) : null,
                    'trongluong' => ($row['trongluong'] !== null && $row['trongluong'] !== '') ? (float) $row['trongluong'] : null,
                    'mausac' => !empty($row['mausac']) ? trim((string) $row['mausac']) : null,
                    'baohanh' => (int) ($row['baohanh'] ?? 12),
                    'noibat' => !empty($row['noibat']) ? 1 : 0,
                    'trangthai' => isset($row['trangthai']) && $row['trangthai'] !== '' ? (int) $row['trangthai'] : 1,
                ]
            );
        }
    }
}