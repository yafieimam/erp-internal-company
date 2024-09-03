<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomersFollowUpExport implements FromView, ShouldAutoSize, WithTitle
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
    		$data = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", "vis.tipe_customer as no_tipe_customers", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN non.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as nama"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.bidang_usaha WHEN vis.tipe_customer = 2 THEN non.bidang_usaha WHEN vis.tipe_customer = 3 THEN komp.bidang_usaha END as bidang_usaha"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custid WHEN vis.tipe_customer = 2 THEN non.noncustid WHEN vis.tipe_customer = 3 THEN komp.kompid END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Non Customers' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), DB::raw("CASE WHEN vis.status = 1 THEN vis.keterangan WHEN vis.status = 2 THEN vis.alasan_suspend WHEN vis.status = 3 THEN vis.result END as ket_follow_up"), "vis.perihal", "vis.keterangan", "vis.offline", "vis.company", "vis.tanggal_input", "vis.tanggal_done", "stat.name as status", "vis.alasan_suspend", "vis.status as no_status")->join("status_cust_visit as stat", "stat.id", "=", "vis.status")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("non_customers as non", "non.noncustid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->whereRaw('MONTH(tanggal_schedule) = ?',[$currentMonth])->whereRaw('YEAR(tanggal_schedule) = ?',[$currentYear])->get();
    	}else{
    		$data = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", "vis.tipe_customer as no_tipe_customers", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN non.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as nama"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.bidang_usaha WHEN vis.tipe_customer = 2 THEN non.bidang_usaha WHEN vis.tipe_customer = 3 THEN komp.bidang_usaha END as bidang_usaha"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custid WHEN vis.tipe_customer = 2 THEN non.noncustid WHEN vis.tipe_customer = 3 THEN komp.kompid END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Non Customers' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), DB::raw("CASE WHEN vis.status = 1 THEN vis.keterangan WHEN vis.status = 2 THEN vis.alasan_suspend WHEN vis.status = 3 THEN vis.result END as ket_follow_up"), "vis.perihal", "vis.keterangan", "vis.offline", "vis.company", "vis.tanggal_input", "vis.tanggal_done", "stat.name as status", "vis.alasan_suspend", "vis.status as no_status")->join("status_cust_visit as stat", "stat.id", "=", "vis.status")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("non_customers as non", "non.noncustid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->whereBetween('tanggal_schedule', array($this->from_date, $this->to_date))->get();
    	}

    	$arrayForTable = [];
    	$count_terbesar = 0;
        foreach($data as $data){
        	$temp = [];
            $temp['tanggal'] = $data->tanggal_schedule;
            $temp['offline'] = $data->offline;
            $temp['ket_follow_up'] = $data->ket_follow_up;
            $temp['follow_up'] = $data->follow_up;
            $temp['penawaran'] = $data->nomor_penawaran;
            if(!isset($arrayForTable[$data->customers])){
                $arrayForTable[$data->customers] = [];
                $arrayForTable[$data->customers]['nama'] = $data->nama;
                $arrayForTable[$data->customers]['bidang_usaha'] = $data->bidang_usaha;
            }
            $arrayForTable[$data->customers][] = $temp;
        }

        foreach($arrayForTable as $key => $value){
        	if($count_terbesar < count($value) - 2){
        		$count_terbesar = count($value) - 2;
        	}
        }
        
        return view('excel_customers_follow_up', [
            'data' => $arrayForTable, 'from_date' => $this->from_date, 'to_date' => $this->to_date, 'count_terbesar' => $count_terbesar
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Data Customers Follow Up';
    }
}
