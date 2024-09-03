<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CustomerVisitImport implements WithMultipleSheets 
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
            0 => new CustomerVisitFirstImport(),
            1 => new CustomerVisitSecondImport(),
        ];
    }
}
