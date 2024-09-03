<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;

class HasilLabPeriodicSheetExport implements WithMultipleSheets
{
    public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new HasilLabPeriodicFirstExport($this->from_date, $this->to_date);
        $sheets[1] = new HasilLabPeriodicSecondExport($this->from_date, $this->to_date);
        
        return $sheets;
    }
}
