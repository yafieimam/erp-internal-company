<?php

namespace App\Http\Controllers;
use App\ModelProduk;
use App\ModelOmsetSales;
use App\ModelOmsetProduksi;
use App\ModelDeliveryOrders;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use File;
use Response;
use App\Imports\OmsetSalesImport;
use App\Imports\PenagihanImport;
use Excel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
Use Exception;
use App\Exports\OmsetAllExport;
use App\Exports\OmsetCustomersExport;
use App\Exports\OmsetGroupExport;

class OmsetController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';

    public function index(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $omset_sales = DB::select("select delo.tanggal_do as tanggal, count(distinct delo.custid) as jumlah_customer, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset, sum(delo.qty / 1000) as jumlah_tonase from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where (delo.tanggal_do between ? and ?) and cus.groupid is null group by delo.tanggal_do", [$request->from_date, $request->to_date]);
            }else{
                $omset_sales = DB::select("select delo.tanggal_do as tanggal, count(distinct delo.custid) as jumlah_customer, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset, sum(delo.qty / 1000) as jumlah_tonase from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where month(delo.tanggal_do) = ? and year(delo.tanggal_do) = ? and cus.groupid is null group by delo.tanggal_do", [$currentMonth, $currentYear]);
            }

            return datatables()->of($omset_sales)->addColumn('action', 'button/action_button_omset_customer')->rawColumns(['action'])->make(true);
        }
        return view('input_omset_sls');
    }   

    public function decrypt($encryptedString){
        $json = json_decode(base64_decode($encryptedString), true);

        try {
            $salt = hex2bin($json["salt"]);
            $iv = hex2bin($json["iv"]);
        } catch (Exception $e) {
            return null;
        }

        $cipherText = base64_decode($json['ciphertext']);

        $iterations = intval(abs($json['iterations']));
        if ($iterations <= 0) {
            $iterations = 999;
        }
        $hashKey = hash_pbkdf2('sha512', env('MIX_APP_KEY'), $salt, $iterations, ($this->encryptMethodLength() / 4));
        unset($iterations, $json, $salt);

        $decrypted= openssl_decrypt($cipherText , $this->encryptMethod, hex2bin($hashKey), OPENSSL_RAW_DATA, $iv);
        unset($cipherText, $hashKey, $iv);

        return $decrypted;
    }

    protected function encryptMethodLength(){
        $number = filter_var($this->encryptMethod, FILTER_SANITIZE_NUMBER_INT);

        return intval(abs($number));
    }

    public function create(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::table('delivery_orders as do')->select("do.tanggal_do as tanggal", "cus.custname as custid", "do.itemid as itemid", "do.qty as tonase", DB::raw("(CASE WHEN do.ppn = 0 THEN (do.sub_amount - do.diskon) ELSE (do.sub_amount - do.diskon) + (do.percent_ppn * (do.sub_amount - do.diskon) / 100) END) as omset"))->join('customers as cus', 'cus.custid', '=', 'do.custid')->whereNull('cus.groupid')->whereDate('do.tanggal_do', $request->tanggal)->get();


            return datatables()->of($omset_sales_detail)->make(true);
        }
    }

    public function detailOmsetCustomerEdit(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::table('delivery_orders as do')->select("do.nosj", "do.tanggal_do as tanggal", "do.custid", "cus.custname as customer", "do.itemid as itemid", "do.top", "do.tanggal_jatuh_tempo", "do.noinv", "do.dpp", "do.ppn", "do.amount", "do.qty", "do.price", "do.sub_amount", "do.diskon")->join('customers as cus', 'cus.custid', '=', 'do.custid')->whereNull('cus.groupid')->whereDate('do.tanggal_do', $request->tanggal)->get();

            return datatables()->of($omset_sales_detail)->make(true);
        }
    }

    public function omsetAll(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $omset_sales = DB::select("select delo.tanggal_do as tanggal, count(distinct delo.custid) as jumlah_customer, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset, sum(delo.qty / 1000) as jumlah_tonase from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid where (delo.tanggal_do between ? and ?) group by delo.tanggal_do", [$request->from_date, $request->to_date]);
            }else{
                $omset_sales = DB::select("select delo.tanggal_do as tanggal, count(distinct delo.custid) as jumlah_customer, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset, sum(delo.qty / 1000) as jumlah_tonase from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid where month(delo.tanggal_do) = ? and year(delo.tanggal_do) = ? group by delo.tanggal_do", [$currentMonth, $currentYear]);
            }

            return datatables()->of($omset_sales)->addColumn('action', 'button/action_button_omset_all')->rawColumns(['action'])->make(true);
        }
        return view('input_omset_sls');
    }   

    public function detailOmsetAll(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::table('delivery_orders as do')->select("do.tanggal_do as tanggal", "cus.custname as custid", "do.itemid as itemid", "do.qty as tonase", DB::raw("(CASE WHEN do.ppn = 0 THEN (do.sub_amount - do.diskon) ELSE (do.sub_amount - do.diskon) + (do.percent_ppn * (do.sub_amount - do.diskon) / 100) END) as omset"), "cus.groupid")->join('customers as cus', 'cus.custid', '=', 'do.custid')->whereDate('do.tanggal_do', $request->tanggal)->get();

            return datatables()->of($omset_sales_detail)->make(true);
        }
    }

    public function detailOmsetAllEdit(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::table('delivery_orders as do')->select("do.nosj", "do.tanggal_do as tanggal", "do.custid", "cus.custname as customer", "do.itemid as itemid", "do.top", "do.tanggal_jatuh_tempo", "do.noinv", "do.dpp", "do.ppn", "do.amount", "do.qty", "do.price", "do.sub_amount", "do.diskon")->join('customers as cus', 'cus.custid', '=', 'do.custid')->whereDate('do.tanggal_do', $request->tanggal)->get();

            return datatables()->of($omset_sales_detail)->make(true);
        }
    }

    public function detailOmsetAllMesh(Request $request){
        $omset_sales_detail = DB::select("select (select sum(qty)/1000 from delivery_orders where itemname like '250%' and tanggal_do = delo.tanggal_do) as 250_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like '250%' and delor.tanggal_do = delo.tanggal_do) as 250_value, (select sum(qty)/1000 from delivery_orders where itemname like '325%' and tanggal_do = delo.tanggal_do) as 325_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like '325%' and delor.tanggal_do = delo.tanggal_do) as 325_value, (select sum(qty)/1000 from delivery_orders where itemname like '500%' and tanggal_do = delo.tanggal_do) as 500_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like '500%' and delor.tanggal_do = delo.tanggal_do) as 500_value, (select sum(qty)/1000 from delivery_orders where itemid IN('MS00800A','MS00800B','MS00800D','MS00800J') and tanggal_do = delo.tanggal_do) as 800_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemid IN('MS00800A','MS00800B','MS00800D','MS00800J') and delor.tanggal_do = delo.tanggal_do) as 800_value, (select sum(qty)/1000 from delivery_orders where itemid IN('MS01200C') and tanggal_do = delo.tanggal_do) as 1200_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemid IN('MS01200C') and delor.tanggal_do = delo.tanggal_do) as 1200_value, (select sum(qty)/1000 from delivery_orders where itemid IN('MS01200J') and tanggal_do = delo.tanggal_do) as 1200_j_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemid IN('MS01200J') and delor.tanggal_do = delo.tanggal_do) as 1200_j_value, (select sum(qty)/1000 from delivery_orders where itemname like '1500%' and tanggal_do = delo.tanggal_do) as 1500_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like '1500%' and delor.tanggal_do = delo.tanggal_do) as 1500_value, (select sum(qty)/1000 from delivery_orders where itemname like '2000%' and tanggal_do = delo.tanggal_do) as 2000_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like '2000%' and delor.tanggal_do = delo.tanggal_do) as 2000_value, (select sum(qty)/1000 from delivery_orders where itemname like '6000%' and tanggal_do = delo.tanggal_do) as 6000_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like '6000%' and delor.tanggal_do = delo.tanggal_do) as 6000_value, (select sum(qty)/1000 from delivery_orders where itemname like 'DCB-25%' and tanggal_do = delo.tanggal_do) as dcb25_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like 'DCB-25%' and delor.tanggal_do = delo.tanggal_do) as dcb25_value, (select sum(qty)/1000 from delivery_orders where itemname like 'DCD-25%' and tanggal_do = delo.tanggal_do) as dcd25_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like 'DCD-25%' and delor.tanggal_do = delo.tanggal_do) as dcd25_value, (select sum(qty)/1000 from delivery_orders where itemname like 'DCD-50%' and tanggal_do = delo.tanggal_do) as dcd50_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like 'DCD-50%' and delor.tanggal_do = delo.tanggal_do) as dcd50_value, (select sum(qty)/1000 from delivery_orders where itemname like 'DCD-800%' and tanggal_do = delo.tanggal_do) as dcd800_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like 'DCD-800%' and delor.tanggal_do = delo.tanggal_do) as dcd800_value, (select sum(qty)/1000 from delivery_orders where itemid IN('MS12003C') and tanggal_do = delo.tanggal_do) as sss_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemid IN('MS12003C') and delor.tanggal_do = delo.tanggal_do) as sss_value, (select sum(qty)/1000 from delivery_orders where itemname like '%2002%' and tanggal_do = delo.tanggal_do) as 2002_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemname like '%2002%' and delor.tanggal_do = delo.tanggal_do) as 2002_value, (select sum(qty)/1000 from delivery_orders where itemid IN('MSSW800C','MSSW800D','MSSW800J') and tanggal_do = delo.tanggal_do) as swaa_tonase, (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) from delivery_orders as delor left join (select (sum(delord.sub_amount) - sum(delord.diskon)) as sum1, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn = 0 group by delord.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delord.sub_amount) - sum(delord.diskon)) + (delord.percent_ppn * (sum(delord.sub_amount) - sum(delord.diskon)) / 100) as sum2, delord.nosj, delord.itemid from delivery_orders as delord where delord.ppn > 0 group by delord.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid where delor.itemid IN('MSSW800C','MSSW800D','MSSW800J') and delor.tanggal_do = delo.tanggal_do) as swaa_value from delivery_orders delo where delo.tanggal_do = ? group by delo.tanggal_do", [$request->tanggal]);
        
        return Response()->json($omset_sales_detail);
    }  

    public function detailOmsetCustomerMesh(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::select("select (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like '250%') as 250_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like '250%') as 250_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like '325%') as 325_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like '325%') as 325_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like '500%') as 500_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like '500%') as 500_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemid IN('MS00800A','MS00800B','MS00800D','MS00800J')) as 800_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemid IN('MS00800A','MS00800B','MS00800D','MS00800J')) as 800_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemid IN('MS01200C')) as 1200_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemid IN('MS01200C')) as 1200_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemid IN('MS01200J')) as 1200_j_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemid IN('MS01200J')) as 1200_j_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like '1500%') as 1500_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like '1500%') as 1500_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like '2000%') as 2000_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like '2000%') as 2000_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like '6000%') as 6000_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like '6000%') as 6000_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like 'DCB-25%') as dcb25_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like 'DCB-25%') as dcb25_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like 'DCD-25%') as dcd25_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like 'DCD-25%') as dcd25_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like 'DCD-50%') as dcd50_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like 'DCD-50%') as dcd50_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like 'DCD-800%') as dcd800_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like 'DCD-800%') as dcd800_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemid IN('MS12003C')) as sss_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemid IN('MS12003C')) as sss_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemname like '%2002%') as 2002_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemname like '%2002%') as 2002_value, (select sum(delo.qty) / 1000 from delivery_orders delo, customers cus where delo.tanggal_do = deloo.tanggal_do and cus.custid = delo.custid and cus.groupid is null and delo.itemid IN('MSSW800C','MSSW800D','MSSW800J')) as swaa_tonase, (select (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) as delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) as delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid join customers as cus on cus.custid = delo.custid where delo.tanggal_do = deloo.tanggal_do and cus.groupid is null and delo.itemid IN('MSSW800C','MSSW800D','MSSW800J')) as swaa_value from delivery_orders deloo where deloo.tanggal_do = ? group by deloo.tanggal_do", [$request->tanggal]);

            return datatables()->of($omset_sales_detail)->make(true);
        }
    }
    public function detailOmsetGroupMesh(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::select("select (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like '250%') as '250_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like '250%') as '250_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like '325%') as '325_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like '325%') as '325_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like '500%') as '500_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like '500%') as '500_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemid IN('MS00800A','MS00800B','MS00800D','MS00800J')) as '800_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemid IN('MS00800A','MS00800B','MS00800D','MS00800J')) as '800_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemid IN('MS01200C')) as '1200_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemid IN('MS01200C')) as '1200_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemid IN('MS01200J')) as '1200_j_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemid IN('MS01200J')) as '1200_j_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like '1500%') as '1500_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like '1500%') as '1500_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like '2000%') as '2000_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like '2000%') as '2000_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like '6000%') as '6000_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like '6000%') as '6000_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like 'DCB-25%') as 'dcb25_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like 'DCB-25%') as 'dcb25_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like 'DCD-25%') as 'dcd25_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like 'DCD-25%') as 'dcd25_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like 'DCD-50%') as 'dcd50_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like 'DCD-50%') as 'dcd50_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like 'DCD-800%') as 'dcd800_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like 'DCD-800%') as 'dcd800_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemid IN('MS12003C')) as 'sss_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemid IN('MS12003C')) as 'sss_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemname like '%2002%') as '2002_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemname like '%2002%') as '2002_value', (select sum((select delo.qty from customer_group_detail cgd where cgd.custid = delo.custid))/1000 from delivery_orders delo where delo.tanggal_do = deloo.tanggal_do and delo.itemid IN('MSSW800C','MSSW800D','MSSW800J')) as 'swaa_tonase', (select (coalesce(sum(delor2.sum1), 0) + coalesce(sum(delor3.sum2), 0)) as total_omset from delivery_orders delor left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delor2 on delor.nosj=delor2.nosj and delor.itemid = delor2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delor3 on delor.nosj=delor3.nosj and delor.itemid = delor3.itemid join customer_group_detail as cgd on cgd.custid = delor.custid where delor.tanggal_do = deloo.tanggal_do and delor.itemid IN('MSSW800C','MSSW800D','MSSW800J')) as 'swaa_value' from delivery_orders deloo where deloo.tanggal_do = ? group by deloo.tanggal_do", [$request->tanggal]);

            return datatables()->of($omset_sales_detail)->make(true);
        }
    }  


    public function omsetGroup(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $omset_sales = DB::select("select deloo.tanggal_do as tanggal, count(distinct cgd.groupid) as jumlah_customer, (sum(deloo.qty) / 1000) as jumlah_tonase, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset from delivery_orders as deloo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on deloo.nosj=delo2.nosj and deloo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on deloo.nosj=delo3.nosj and deloo.itemid = delo3.itemid join customer_group_detail as cgd on cgd.custid = deloo.custid where (deloo.tanggal_do between ? and ?) group by deloo.tanggal_do", [$request->from_date, $request->to_date]);
            }else{
                $omset_sales = DB::select("select deloo.tanggal_do as tanggal, count(distinct cgd.groupid) as jumlah_customer, (sum(deloo.qty) / 1000) as jumlah_tonase, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset from delivery_orders as deloo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on deloo.nosj=delo2.nosj and deloo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on deloo.nosj=delo3.nosj and deloo.itemid = delo3.itemid join customer_group_detail as cgd on cgd.custid = deloo.custid where month(deloo.tanggal_do) = ? and year(deloo.tanggal_do) = ? group by deloo.tanggal_do", [$currentMonth, $currentYear]);
            }

            return datatables()->of($omset_sales)->addColumn('action', 'button/action_button_omset_group')->rawColumns(['action'])->make(true);
        }
        return view('input_omset_sls');
    }   

    public function detailOmsetGroup(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::select("select deloo.tanggal_do as tanggal, cgd.groupid, (sum(deloo.qty) / 1000) as jumlah_tonase, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset from delivery_orders deloo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on deloo.nosj=delo2.nosj and deloo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (delor.percent_ppn * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on deloo.nosj=delo3.nosj and deloo.itemid = delo3.itemid join customer_group_detail as cgd on cgd.custid = deloo.custid where deloo.tanggal_do = ? group by deloo.tanggal_do, cgd.groupid", [$request->tanggal]);

            return datatables()->of($omset_sales_detail)->make(true);
        }
    }

    public function detailOmsetGroupEdit(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::table('delivery_orders as do')->select("do.nosj", "cgd.groupid", "do.custid", "do.tanggal_do as tanggal", "cus.custname as customer", "do.itemid as itemid", "do.top", "do.tanggal_jatuh_tempo", "do.noinv", "do.dpp", "do.ppn", "do.amount", "do.qty", "do.price", "do.sub_amount", "do.diskon")->join('customers as cus', 'cus.custid', '=', 'do.custid')->join('customer_group_detail as cgd', 'cgd.custid', '=', 'cus.custid')->whereDate('do.tanggal_do', $request->tanggal)->get();

            return datatables()->of($omset_sales_detail)->make(true);
        }
    }

    public function detailOmsetGroupPerCustID(Request $request){
        if(request()->ajax()){
            $omset_sales_detail = DB::select("select deloo.custname, deloo.itemid, deloo.qty as jumlah_tonase, (CASE WHEN deloo.ppn = 0 THEN (deloo.sub_amount - deloo.diskon) ELSE (deloo.sub_amount - deloo.diskon) + (deloo.percent_ppn * (deloo.sub_amount - deloo.diskon) / 100) END) as total_omset from delivery_orders as deloo join customer_group_detail as cgd on cgd.custid = deloo.custid where deloo.tanggal_do = ? and cgd.groupid = ?", [$request->tanggal, $request->groupid]);

            return datatables()->of($omset_sales_detail)->make(true);
        }
    }   

    public function omset_sales(){
    	$produk_data =ModelProduk::select('kode_produk','nama_produk')->get();
    	return view('input_omset_sales', compact('produk_data'));
    }

    public function omset_produksi(){
    	$produk_data =ModelProduk::select('kode_produk','nama_produk')->get();
    	return view('input_omset_produksi', compact('produk_data'));
    }

    public function inputOmsetSales(Request $request){
        $data =  new ModelDeliveryOrders();
        $data->itemid = $request->produk;
        $data->tanggal_do = $request->tanggal_omset;
        $data->custid = $request->get('customer');
        $data->qty = $request->jml_tonase;
        $data->amount = $request->jml_omset;
        $data->save();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Menambahkan Data Omset No. ' . $data->id]);

        return redirect('sales/omset')->with('alert','Sukses Menambahkan Data');
    }

    public function inputOmsetProduksi(Request $request){
    	Session::forget('jml_customer');
        Session::forget('jml_tonase_mesin');
        Session::forget('jml_tonase');
        Session::forget('jml_omset');
        Session::forget('whiteness');
        Session::forget('residue');
        Session::forget('mean_particle_diameter');
        Session::forget('moisture');
        Session::forget('mesh');
        Session::forget('weight');
        Session::put('jml_customer', $request->jml_customer);
        Session::put('jml_tonase_mesin', $request->jml_tonase_mesin);
        Session::put('jml_tonase', $request->jml_tonase);
        Session::put('jml_omset', $request->jml_omset);
        Session::put('whiteness', $request->whiteness);
        Session::put('residue', $request->residue);
        Session::put('mean_particle_diameter', $request->mean_particle_diameter);
        Session::put('moisture', $request->moisture);
        Session::put('mesh', $request->mesh);
        Session::put('weight', $request->weight);

        $this->validate($request, [
            'produk' => 'required',
            'tanggal_produksi' => 'required',
            'jml_customer' => 'required',
            'jml_tonase_mesin' => 'required',
            'jml_tonase' => 'required',
            'jml_omset' => 'required',
            'whiteness' => 'required',
            'residue' => 'required',
            'mean_particle_diameter' => 'required',
            'moisture' => 'required',
            'mesh' => 'required',
            'standard_packaging' => 'required',
            'weight' => 'required',
        ]);

        $data =  new ModelOmsetProduksi();
        $data->kode_produk = $request->produk;
        $data->tanggal_produksi = $request->tanggal_produksi;
        $data->jumlah_customer = $request->jml_customer;
        $data->jumlah_tonase_per_mesin = $request->jml_tonase_mesin;
        $data->jumlah_tonase = $request->jml_tonase;
        $data->total_omset = $request->jml_omset;
        $data->whiteness = $request->whiteness;
        $data->residue = $request->residue;
        $data->mean_particle_diameter = $request->mean_particle_diameter;
        $data->moisture = $request->moisture;
        $data->standard_packaging = $request->standard_packaging;
        $data->weight = $request->weight;
        $data->mesh = $request->mesh;
        $data->save();

        Session::forget('jml_customer');
        Session::forget('jml_tonase_mesin');
        Session::forget('jml_tonase');
        Session::forget('jml_omset');
        Session::forget('whiteness');
        Session::forget('residue');
        Session::forget('mean_particle_diameter');
        Session::forget('moisture');
        Session::forget('mesh');
        Session::forget('weight');

        return redirect('input_omset_produksi')->with('alert','Sukses Menambahkan Data');
    }

    public function view_omset_sales(){
        return view('view_omset_sales');
    }

    public function view_omset_produksi(){
        return view('view_omset_produksi');
    }

    public function import_excel(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new OmsetSalesImport;
        $import_penagihan = new PenagihanImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        Excel::import($import_penagihan, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        if($import->getDuplikat() == 1){
            return redirect('sales/omset')->with('alert','Data Duplikat, Data Sudah Ada');
        }else{
            if($import->getKosong() == 1){
                return redirect('sales/omset')->with('alert','Nomor Invoice Kosong. Silahkan Upload Ulang Setelah Ditambahkan Nomor Invoicenya');
            }else{
                return redirect('sales/omset')->with('alert','Sukses Menambahkan Data');
            }
        }
    }

    public function excelOmsetAll($from_date, $to_date){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        $nama_file = 'Omset All Dwi Selo Giri Mas.xlsx';

        return Excel::download(new OmsetAllExport($val_from_date, $val_to_date), $nama_file);
    }

    public function excelOmsetCustomers($from_date, $to_date){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        $nama_file = 'Omset Customers Dwi Selo Giri Mas.xlsx';

        return Excel::download(new OmsetCustomersExport($val_from_date, $val_to_date), $nama_file);
    }

    public function excelOmsetGroup($from_date, $to_date){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        $nama_file = 'Omset Customer Group Dwi Selo Giri Mas.xlsx';

        return Excel::download(new OmsetGroupExport($val_from_date, $val_to_date), $nama_file);
    }

    public function saveDetailOmsetAllEdit(Request $request){
        $nosj = $request->get('nosj');
        $itemid = $request->get('itemid');
        $noinv = $request->get('noinv');
        $top = $request->get('top');
        $tanggal = $request->get('tanggal_jatuh_tempo');
        $dpp = $request->get('dpp');
        $ppn = $request->get('ppn');
        $amount = $request->get('amount');
        $qty = $request->get('qty');
        $price = $request->get('price');
        $diskon = $request->get('diskon');
        $sub_amount = $request->get('sub_amount');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            foreach($itemid[$nomor] as $item) {
                DB::table('delivery_orders')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item]]);

                DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
                
                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Melakukan Edit Data Omset No. ' . $nomor]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Update Data Omset']);
            }
        }

        return Response()->json();
    }

    public function saveDetailOmsetCustomerEdit(Request $request){
        $nosj = $request->get('nosj');
        $itemid = $request->get('itemid');
        $noinv = $request->get('noinv');
        $top = $request->get('top');
        $tanggal = $request->get('tanggal_jatuh_tempo');
        $dpp = $request->get('dpp');
        $ppn = $request->get('ppn');
        $amount = $request->get('amount');
        $qty = $request->get('qty');
        $price = $request->get('price');
        $diskon = $request->get('diskon');
        $sub_amount = $request->get('sub_amount');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            foreach($itemid[$nomor] as $item) {
                DB::table('delivery_orders')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item]]);

                DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
                
                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Melakukan Edit Data Omset No. ' . $nomor]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Update Data Omset']);
            }
        }

        return Response()->json();
    }

    public function saveDetailOmsetGroupEdit(Request $request){
        $nosj = $request->get('nosj');
        $itemid = $request->get('itemid');
        $noinv = $request->get('noinv');
        $top = $request->get('top');
        $tanggal = $request->get('tanggal_jatuh_tempo');
        $dpp = $request->get('dpp');
        $ppn = $request->get('ppn');
        $amount = $request->get('amount');
        $qty = $request->get('qty');
        $price = $request->get('price');
        $diskon = $request->get('diskon');
        $sub_amount = $request->get('sub_amount');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            foreach($itemid[$nomor] as $item) {
                DB::table('delivery_orders')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item]]);

                DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
                
                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Melakukan Edit Data Omset No. ' . $nomor]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Update Data Omset']);
            }
        }

        return Response()->json();
    }
}
