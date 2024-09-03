<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class OmsetCustomersExport implements FromView
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
    		$omset_sales = DB::select("select delo.tanggal_do as tanggal, count(distinct delo.custid) as jumlah_customer, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset, sum(delo.qty / 1000) as jumlah_tonase from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (10 * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where month(delo.tanggal_do) = ? and year(delo.tanggal_do) = ? and cus.groupid is null group by delo.tanggal_do", [$currentMonth, $currentYear]);
    	}else{
    		$omset_sales = DB::select("select delo.tanggal_do as tanggal, count(distinct delo.custid) as jumlah_customer, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset, sum(delo.qty / 1000) as jumlah_tonase from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (10 * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where (delo.tanggal_do between ? and ?) and cus.groupid is null group by delo.tanggal_do", [$this->from_date, $this->to_date]);
    	}

    	$arrayForTable = [];
    	foreach($omset_sales as $omset){
    		$temp = [];
            $temp['tanggal'] = $omset->tanggal;
            $temp['jumlah_customer'] = $omset->jumlah_customer;
            $temp['jumlah_tonase'] = $omset->jumlah_tonase;
            $temp['total_omset'] = $omset->total_omset;
            $arrayForTable[] = $temp;
    	}

        return view('excel_omset_customers', [
            'data' => $arrayForTable, 'from_date' => $this->from_date, 'to_date' => $this->to_date
        ]);
    }
}
