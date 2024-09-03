<?php

namespace App\Http\Controllers;
use App\ModelComplaintCust;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class ComplaintLainValController extends Controller
{
    public function index(Request $request){
        if(request()->ajax()){
        	if(!empty($request->from_date)){
            	$complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->where('com.status', '!=', 9)->whereIn('com.divisi', [2, 4, 6, 7, 8])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
            	$complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->where('com.status', '!=', 9)->whereIn('com.divisi', [2, 4, 6, 7, 8])->whereDate('com.tanggal_complaint', Carbon::now()->toDateString())->get();
            }

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_validate_complaint_lainnya')->rawColumns(['action'])->make(true);
        }
        return view('validation_complaint_lainnya');
    }

    public function validasi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->update([
                        'status' => 9
                    ]);

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function show($nomor_complaint){   
        $data  = DB::table('complaint_lainnya as com')->select('com.nomor_complaint as nomor_complaint', 'com.divisi as divisi', 'com.analisa as analisa', 'com.solusi as solusi', 'com.lampiran as lampiran')->where('com.nomor_complaint', $nomor_complaint)->first();
     
        return Response()->json($data);
    }
}
