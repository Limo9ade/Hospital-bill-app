<?php

namespace App\Imports;

use App\Models\Test;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TestsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Test([
            'test_name'     => $row['test_name'],      // from "Test Name"
            'test_category' => $row['test_category'],  // from "Test Category"
            'price'         => $row['price_bdt'],      // from "Price (BDT)"
        ]);
    }
}
