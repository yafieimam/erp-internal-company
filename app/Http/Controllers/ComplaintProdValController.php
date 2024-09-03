<?php

namespace App\Http\Controllers;
use App\ModelComplaintCust;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class ComplaintProdValController extends Controller
{
    public function index(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->where('com.status', '!=', 9)->whereIn('com.divisi', [1, 4, 5, 7])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->where('com.status', '!=', 9)->whereIn('com.divisi', [1, 4, 5, 7])->whereDate('com.tanggal_complaint', Carbon::now()->toDateString())->get();
            }

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_validate_complaint_produksi')->rawColumns(['action'])->make(true);
        }
        return view('validation_complaint_produksi');
    }

    public function show($nomor_complaint){   
        $data  = DB::table('complaint_produksi as com')->select('com.nomor_complaint as nomor_complaint', 'com.tanggal_produksi as tanggal_produksi', 'com.no_lot as no_lot', 'com.mesin as mesin', 'area.name as area', 'com.supervisor as supervisor', 'com.analisa as analisa', 'com.solusi as solusi', 'com.lampiran as lampiran')->join("tbl_area as area", "area.id", "=", "com.area")->where('com.nomor_complaint', $nomor_complaint)->first();
     
        return Response()->json($data);
    }
}
