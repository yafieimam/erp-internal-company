<?php

namespace App\Http\Controllers;
use App\ModelComplaintLog;
use App\ModelComplaintCust;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class ComplaintLogController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';

    public function index(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
        	if(!empty($request->from_date)){
            	$complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [3, 5, 6, 7])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
            	$complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [3, 5, 6, 7])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
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

    public function store(Request $request){

        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        if($request->hasFile('upload_file_log')) {
            $file = $request->file('upload_file_log');
            $nama_file = time()."_".$request->custid_log."_".$request->nomor_complaint_log."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data_komitmen = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_log)->where('divisi', 2)->first();

            if($data_komitmen){
                $data_komitmen_get = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_log)->where('divisi', 2)->get();

                foreach($data_komitmen_get as $data){
                    $komitmen = DB::table('list_of_action_complaint')->insert(['id_user' => $data->id_user, 'nomor_complaint' => $data->nomor_complaint, 'divisi' => $data->divisi, 'komitmen' => $data->komitmen, 'selesai_tanggal_komitmen' => $data->selesai_tanggal_komitmen, 'status' => 1]);
                }
            }

            $complaint=ModelComplaintLog::insert(["nomor_complaint" => $request->nomor_complaint_log, "evaluasi" => $request->evaluasi_log, "solusi_internal" => $request->solusi_internal_log, "solusi_customer" => $request->solusi_customer_log, "lampiran" => $nama_file, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

            if($complaint){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_log)->update(['baca' => 0, 'logistik' => 2]);

                $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_log)->first();

                $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_log)->first();

                if($stat->hasil == $div->hasil){
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_log)->update(['validasi' => 1]);
                }else{
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_log)->update(['validasi' => 0]);
                }
            }
        }else{
            $data_komitmen = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_log)->where('divisi', 2)->first();

            if($data_komitmen){
                $data_komitmen_get = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_log)->where('divisi', 2)->get();

                foreach($data_komitmen_get as $data){
                    $komitmen = DB::table('list_of_action_complaint')->insert(['id_user' => $data->id_user, 'nomor_complaint' => $data->nomor_complaint, 'divisi' => $data->divisi, 'komitmen' => $data->komitmen, 'selesai_tanggal_komitmen' => $data->selesai_tanggal_komitmen, 'status' => 1]);
                }
            }

            $complaint=ModelComplaintLog::insert(["nomor_complaint" => $request->nomor_complaint_log, "evaluasi" => $request->evaluasi_log, "solusi_internal" => $request->solusi_internal_log, "solusi_customer" => $request->solusi_customer_log, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

            if($complaint){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_log)->update(['baca' => 0, 'logistik' => 2]);

                $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_log)->first();

                $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_log)->first();

                if($stat->hasil == $div->hasil){
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_log)->update(['validasi' => 1]);
                }else{
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_log)->update(['validasi' => 0]);
                }
            }
        }

        DB::table('temp_list_action')->where('divisi', 2)->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->nomor_complaint_log, 'divisi' => 2, 'action' => 'Divisi Logistik Berhasil Memproses dan Memasukkan Data pada Complaint Nomor ' . $request->nomor_complaint_log]);
        ModelComplaintCust::where('nomor_complaint', $request->nomor_complaint_log)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function show($nomor_complaint){   

        $val_nomor_complaint = $this->decrypt($nomor_complaint);

        $data  = DB::table('complaint_logistik as com')->select('com.nomor_complaint', 'com.evaluasi', 'com.solusi_internal', 'com.solusi_customer', 'com.lampiran')->where('com.nomor_complaint', $val_nomor_complaint)->first();
     
        $komitmen = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $val_nomor_complaint)->where('divisi', 2)->get();
     
        return Response()->json(["data_complaint" => $data, "data_komitmen" => $komitmen]);
    }

    public function showDataLogistik($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.tanggal_order', 'com.sales_order', 'com.supervisor_sales', 'com.pelapor', 'com.tanggal_pengiriman', 'com.area', 'com.supervisor', 'com.pengiriman', 'com.pengiriman_lain', 'com.nama_supir', 'com.nama_kernet', 'com.no_kendaraan', 'com.jenis_kendaraan', 'com.jenis_kendaraan_lain', 'com.jumlah_karung', 'com.quantity', 'com.jumlah_kg_sak', 'com.jenis_karung', 'com.berat_timbangan', 'com.unit_berat_timbangan', 'com.berat_aktual', 'com.unit_berat_aktual', 'com.kuli1', 'com.kuli2', 'com.kuli3', 'com.stapel1', 'com.stapel2', 'com.stapel3', 'com.stapel4', 'com.stapel5')->where('com.nomor_complaint', $val_no_complaint)->first();
     
        return Response()->json($data);
    }

    public function complaintTdkTerlibat(Request $request){
        $no_complaint = $request->nomor_complaint_alasan;
        DB::table('divisi_complaint')->where('nomor_complaint', $no_complaint)->update(['logistik' => 1, 'alasan_logistik' => $request->alasan_tidak_terlibat]);

        $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        if($stat->hasil == $div->hasil){
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 1]);
        }else{
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 0]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $no_complaint, 'divisi' => 2, 'action' => 'Divisi Logistik Merubah Status Menjadi Tidak Terlibat Pada Complaint Nomor ' . $no_complaint . ' Dengan Alasan : "' . $request->alasan_tidak_terlibat . '"']);
        ModelComplaintCust::where('nomor_complaint', $no_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);
    }

    public function complaintTerlibat(Request $request){
        $no_complaint = $request->get('nomor_complaint');
        DB::table('divisi_complaint')->where('nomor_complaint', $no_complaint)->update(['logistik' => 2, 'alasan_logistik' => null]);

        $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        if($stat->hasil == $div->hasil){
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 1]);
        }else{
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 0]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $no_complaint, 'divisi' => 2, 'action' => 'Divisi Logistik Merubah Status Menjadi Terlibat Pada Complaint Nomor ' . $no_complaint]);
        ModelComplaintCust::where('nomor_complaint', $no_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);
    }
}
