<?php

namespace App\Imports;

use App\Models\HangSanXuat;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HangSanXuatImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            if (empty($row['tenhang'])) {
                continue;
            }

            $tenhang = trim((string) $row['tenhang']);
            $slug = !empty($row['tenhang_slug'])
                ? trim((string) $row['tenhang_slug'])
                : Str::slug($tenhang, '-');

            HangSanXuat::updateOrCreate(
                ['tenhang_slug' => $slug],
                [
                    'tenhang' => $tenhang,
                    'hinhanh' => $row['hinhanh'] ?? null,
                    'quocgia' => $row['quocgia'] ?? null,
                ]
            );
        }
    }
}