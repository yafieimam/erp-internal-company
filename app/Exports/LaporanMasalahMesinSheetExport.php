<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;

class LaporanMasalahMesinSheetExport implements WithMultipleSheets
{
	public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function sheets(): array
    {
    	$sheets = [];

    	$data = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG', 'Grafik Major', 'Grafik Minor', 'Grafik Lain', 'Grafik Total'];

    	foreach($data as $key => $value){
    		$sheets[] = new LaporanMasalahMesinExport(($key + 1), $value, $this->from_date, $this->to_date);
    	}
        
        return $sheets;
    }
}
