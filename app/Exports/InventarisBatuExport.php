<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventarisBatuExport implements FromView, WithTitle
{
    public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function view(): View
    {
    	$currentMonth = date('m');
        $currentYear = date('Y');

    	if($this->from_date == $this->to_date){
    		$data = DB::table('inventaris_batu')->select('tanggal', 'inventaris_batu.kode_batu', 'item_batu.nama_batu', 'masuk', 'keluar', 'inventaris_batu.stok')->join('item_batu', 'item_batu.kode_batu', '=', 'inventaris_batu.kode_batu')->whereRaw('MONTH(tanggal) = ?',[$currentMonth])->whereRaw('YEAR(tanggal) = ?',[$currentYear])->get();
    	}else{
    		$data = DB::table('inventaris_batu')->select('tanggal', 'inventaris_batu.kode_batu', 'item_batu.nama_batu', 'masuk', 'keluar', 'inventaris_batu.stok')->join('item_batu', 'item_batu.kode_batu', '=', 'inventaris_batu.kode_batu')->whereBetween('tanggal', array($this->from_date, $this->to_date))->get();
    	}

    	$data_item = DB::table('item_batu')->select('kode_batu', 'nama_batu')->get();

    	$arrayForTable = [];

    	foreach($data as $value){
	    	foreach($data_item as $batu){
	            $arrayForTable[$value->tanggal][$batu->kode_batu] = [];
	        }
	    }

        foreach($data as $data){
        	$temp = [];
            $temp['masuk'] = $data->masuk;
            $temp['keluar'] = $data->keluar;
            $temp['stok'] = $data->stok;
            $arrayForTable[$data->tanggal][$data->kode_batu][] = $temp;
        }

        return view('excel_inventaris_batu', [
            'data' => $arrayForTable,
            'data_item' => $data_item,
            'count_batu' => $data_item->count()
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Data Inventaris Batu';
    }
}
