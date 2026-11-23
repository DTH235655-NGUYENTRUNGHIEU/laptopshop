<?php

namespace App\Imports;

use App\Models\HangSanXuat;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class HangSanXuatImport implements ToModel
{
    public function model(array $row): Model|null
    {
        return new HangSanXuat([
            //
        ]);
    }
}
