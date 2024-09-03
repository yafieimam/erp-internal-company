<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;

class PotonganBatuSheetExport implements WithMultipleSheets
{
	public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function sheets(): array
    {
    	$sheets = [];

    	$data = DB::table('vendor_batu')->select('vendorid', 'nama_vendor')->get();

    	$sheets[] = new PotonganBatuExport('Semua', $this->from_date, $this->to_date);
    	foreach($data as $data){
    		$sheets[] = new PotonganBatuExport($data->vendorid, $this->from_date, $this->to_date);
    	}

        return $sheets;
    }
}
