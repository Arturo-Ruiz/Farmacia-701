<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ProductsImport implements ToModel, WithUpserts
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'id' => $row[0],
            'category_id' => $row[2],
            'tax_id' => $row[4],
            'name' => $row[1],
            'laboratory' => $row[3] ?? null,
            'price' => $row[7],
            'stock' => $row[5],
            'img' => $row[6] ?? null,
            'medical_prescription' => ($row[8] === "S"),
        ]);
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'id';
    }
}
