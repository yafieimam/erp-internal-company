<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\DB;

class GrafikEvalLabExport implements WithMultipleSheets
{
    public function sheets(): array
    {
    	$sheets = [];
    	$currentMonth = date('m');
        $currentYear = date('Y');

    	$data_lab = DB::table('laporan_hasil_produksi_lab as lab')->select('lab.mesh', 'tbl_mesin.name as mesin', 'lab.mesin as no_mesin')->join('laporan_hasil_produksi as lap', 'lap.nomor_laporan_produksi', '=', 'lab.nomor_laporan_produksi')->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->whereNotNull('lab.mesh')->orderBy('lab.mesh', 'asc')->groupBy('lab.mesh')->get();

    	foreach($data_lab as $data_lab){
    		$sheets[] = new DataGrafikLabExport($data_lab->mesh, $data_lab->no_mesin, $data_lab->mesin);
    	}

        return $sheets;
    }
}
