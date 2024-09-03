<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PotonganBatuExport implements FromView, WithTitle
{
    public function __construct($vendor, $from_date, $to_date)
    {
        $this->vendor = $vendor;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function view(): View
    {
    	$currentMonth = date('m');
        $currentYear = date('Y');

    	if($this->vendor == 'Semua'){
    		if($this->from_date == $this->to_date){
    			$data = DB::table('purchase_batu')->select('purchase_batu.grno', 'purchase_batu.tanggal', 'purchase_batu.nama_vendor', 'qpur', 'sat', 'nama_batu', 'potongan_batu')->join('potongan_batu', 'potongan_batu.grno', '=', 'purchase_batu.grno')->whereRaw('MONTH(purchase_batu.tanggal) = ?',[$currentMonth])->whereRaw('YEAR(purchase_batu.tanggal) = ?',[$currentYear])->get();
    		}else{
    			$data = DB::table('purchase_batu')->select('purchase_batu.grno', 'purchase_batu.tanggal', 'purchase_batu.nama_vendor', 'qpur', 'sat', 'nama_batu', 'potongan_batu')->join('potongan_batu', 'potongan_batu.grno', '=', 'purchase_batu.grno')->whereBetween('purchase_batu.tanggal', array($this->from_date, $this->to_date))->get();
    		}
    	}else{
    		if($this->from_date == $this->to_date){
    			$data = DB::table('purchase_batu')->select('purchase_batu.grno', 'purchase_batu.tanggal', 'purchase_batu.nama_vendor', 'qpur', 'sat', 'nama_batu', 'potongan_batu')->join('potongan_batu', 'potongan_batu.grno', '=', 'purchase_batu.grno')->where('purchase_batu.vendorid', $this->vendor)->whereRaw('MONTH(purchase_batu.tanggal) = ?',[$currentMonth])->whereRaw('YEAR(purchase_batu.tanggal) = ?',[$currentYear])->get();
    		}else{
    			$data = DB::table('purchase_batu')->select('purchase_batu.grno', 'purchase_batu.tanggal', 'purchase_batu.nama_vendor', 'qpur', 'sat', 'nama_batu', 'potongan_batu')->join('potongan_batu', 'potongan_batu.grno', '=', 'purchase_batu.grno')->where('purchase_batu.vendorid', $this->vendor)->whereBetween('purchase_batu.tanggal', array($this->from_date, $this->to_date))->get();
    		}
    	}

    	$judul = "Semua";
    	if($this->vendor != 'Semua'){
    		$data_judul = DB::table('vendor_batu')->select('nama_vendor')->where('vendorid', $this->vendor)->first();
    		$judul = $data_judul->nama_vendor;
    	}

        return view('excel_potongan_batu', [
            'data' => $data,
            'judul' => $judul
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
    	$judul = "Semua";
    	if($this->vendor != 'Semua'){
    		$data_judul = DB::table('vendor_batu')->select('nama_vendor')->where('vendorid', $this->vendor)->first();
    		$judul = $data_judul->nama_vendor;
    	}
        return $judul;
    }
}
