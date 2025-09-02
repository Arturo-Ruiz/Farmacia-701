<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    public function model(array $row)
    {

        $product = Product::find($row[0]);

        if ($row[8] === "S") {
            $medical_prescription = true;
        } else {
            $medical_prescription = false;
        }

        if ($product) {
            $product->update([
                'category_id' => $row[2],
                'tax_id' => $row[4],
                'name' => $row[1],
                'price' => $row[7],
                'stock' => $row[5],
                'img' => $row[6] ?? null,
                'medical_prescription' => $medical_prescription,
            ]);
            return null; 
        }

        return new Product([
            'id' => $row[0],
            'category_id' => $row[2],
            'tax_id' => $row[4],
            'name' => $row[1],
            'price' => $row[7],
            'stock' => $row[5],
            'img' => $row[6] ?? null,
            'medical_prescription' => $medical_prescription,
            'sales' => 0,
        ]);
    }
}
