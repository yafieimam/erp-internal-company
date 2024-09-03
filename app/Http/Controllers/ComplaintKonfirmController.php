<?php

namespace App\Http\Controllers;
use App\ModelComplaintCust;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class ComplaintKonfirmController extends Controller
{
    public function index(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
        	if(!empty($request->from_date)){
            	$konfirmasi_complaint = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "com.lampiran as lampiran")->whereNull('com.divisi')->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
            	$konfirmasi_complaint = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "com.lampiran as lampiran")->whereNull('com.divisi')->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($konfirmasi_complaint)->addColumn('action', 'button/action_button_divisicomplaint')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function store(Request $request){

        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        // $divisi = null;
        $prod = $request->input('checkbox_produksi');
        $log = $request->input('checkbox_logistik');
        $tim = $request->input('checkbox_timbangan');
        $ware = $request->input('checkbox_warehouse');
        $lain = $request->input('checkbox_lainnya');

        DB::table('divisi_complaint')->insert(["nomor_complaint" => $request->nomor_complaint_konf]);

        $data_divisi = DB::table('divisi_complaint')->select('id')->where('nomor_complaint', $request->nomor_complaint_konf)->first();

        DB::table('complaint_customer')->where('nomor_complaint', $request->nomor_complaint_konf)->update(['divisi' => $data_divisi->id]);

        if($prod){
            DB::table('divisi_complaint')->where('nomor_complaint', $request->nomor_complaint_konf)->update(['produksi' => 2]);
        }

        if($log){
            DB::table('divisi_complaint')->where('nomor_complaint', $request->nomor_complaint_konf)->update(['logistik' => 2]);
        }

        DB::table('divisi_complaint')->where('nomor_complaint', $request->nomor_complaint_konf)->update(['sales' => 2]);

        if($tim){
            DB::table('divisi_complaint')->where('nomor_complaint', $request->nomor_complaint_konf)->update(['timbangan' => 2]);
        }

        if($ware){
            DB::table('divisi_complaint')->where('nomor_complaint', $request->nomor_complaint_konf)->update(['warehouse' => 2]);
        }

        if($lain){
            DB::table('divisi_complaint')->where('nomor_complaint', $request->nomor_complaint_konf)->update(['lainnya' => 2]);
        }

        DB::table('status_complaint')->insert(["nomor_complaint" => $request->nomor_complaint_konf, "baca" => 1]);

        $data_status = DB::table('status_complaint')->select('id')->where('nomor_complaint', $request->nomor_complaint_konf)->first();

        $konfirmasi = DB::table('complaint_customer')->where('nomor_complaint', $request->nomor_complaint_konf)->update(['status' => $data_status->id, 'tanggal_choose_div' => date('Y-m-d')]);

        if($konfirmasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->nomor_complaint_konf, 'divisi' => 3, 'action' => 'Sales Mengkonfirmasi dan Memilih Divisi pada Complaint Nomor ' . $request->nomor_complaint_konf]);

        return Response()->json($arr);
    }
}
