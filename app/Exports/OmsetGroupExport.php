<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class OmsetGroupExport implements FromView
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
    		$omset_sales = DB::select("select deloo.tanggal_do as tanggal, cgd.groupid, (sum(deloo.qty) / 1000) as jumlah_tonase, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset from delivery_orders deloo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on deloo.nosj=delo2.nosj and deloo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (10 * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on deloo.nosj=delo3.nosj and deloo.itemid = delo3.itemid join customer_group_detail as cgd on cgd.custid = deloo.custid where month(deloo.tanggal_do) = ? and year(deloo.tanggal_do) = ? group by deloo.tanggal_do, cgd.groupid", [$currentMonth, $currentYear]);
    	}else{
    		$omset_sales = DB::select("select deloo.tanggal_do as tanggal, cgd.groupid, (sum(deloo.qty) / 1000) as jumlah_tonase, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset from delivery_orders deloo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on deloo.nosj=delo2.nosj and deloo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (10 * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on deloo.nosj=delo3.nosj and deloo.itemid = delo3.itemid join customer_group_detail as cgd on cgd.custid = deloo.custid where (deloo.tanggal_do between ? and ?) group by deloo.tanggal_do, cgd.groupid", [$this->from_date, $this->to_date]);
    	}

    	$arrayForTable = [];
    	foreach($omset_sales as $omset){
    		$temp = [];
            $temp['tanggal'] = $omset->tanggal;
            $temp['groupid'] = $omset->groupid;
            $temp['jumlah_tonase'] = $omset->jumlah_tonase;
            $temp['total_omset'] = $omset->total_omset;
            $arrayForTable[] = $temp;
    	}

    	// dd($arrayForTable);

        return view('excel_omset_group', [
            'data' => $arrayForTable, 'from_date' => $this->from_date, 'to_date' => $this->to_date
        ]);
    }
}
