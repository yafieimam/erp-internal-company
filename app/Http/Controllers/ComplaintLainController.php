<?php

namespace App\Http\Controllers;
use App\ModelComplaintLain;
use App\ModelComplaintCust;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class ComplaintLainController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';
    
    public function index(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
        	if(!empty($request->from_date)){
            	$complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [2, 4, 6, 7, 8])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
            	$complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [2, 4, 6, 7, 8])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
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
        if($request->hasFile('upload_file_lny')) {
            $file = $request->file('upload_file_lny');
            $nama_file = time()."_".$request->custid_lny."_".$request->nomor_complaint_lny."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data_komitmen = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_lny)->where('divisi', 6)->first();

            if($data_komitmen){
                $data_komitmen_get = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_lny)->where('divisi', 6)->get();

                foreach($data_komitmen_get as $data){
                    $komitmen = DB::table('list_of_action_complaint')->insert(['id_user' => $data->id_user, 'nomor_complaint' => $data->nomor_complaint, 'divisi' => $data->divisi, 'komitmen' => $data->komitmen, 'selesai_tanggal_komitmen' => $data->selesai_tanggal_komitmen, 'status' => 1]);
                }
            }

            $complaint=ModelComplaintLain::insert(["nomor_complaint" => $request->nomor_complaint_lny, "divisi" => $request->divisi_lny, "evaluasi" => $request->evaluasi_lny, "solusi_internal" => $request->solusi_internal_lny, "solusi_customer" => $request->solusi_customer_lny, "lampiran" => $nama_file, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

            if($complaint){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lny)->update(['baca' => 0, 'lainnya' => 2]);

                $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_lny)->first();

                $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_lny)->first();

                if($stat->hasil == $div->hasil){
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lny)->update(['validasi' => 1]);
                }else{
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lny)->update(['validasi' => 0]);
                }
            }
        }else{
            $data_komitmen = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_lny)->where('divisi', 6)->first();

            if($data_komitmen){
                $data_komitmen_get = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_lny)->where('divisi', 6)->get();

                foreach($data_komitmen_get as $data){
                    $komitmen = DB::table('list_of_action_complaint')->insert(['id_user' => $data->id_user, 'nomor_complaint' => $data->nomor_complaint, 'divisi' => $data->divisi, 'komitmen' => $data->komitmen, 'selesai_tanggal_komitmen' => $data->selesai_tanggal_komitmen, 'status' => 1]);
                }
            }
            
            $complaint=ModelComplaintLain::insert(["nomor_complaint" => $request->nomor_complaint_lny, "divisi" => $request->divisi_lny, "evaluasi" => $request->evaluasi_lny, "solusi_internal" => $request->solusi_internal_lny, "solusi_customer" => $request->solusi_customer_lny, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

            if($complaint){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lny)->update(['baca' => 0, 'lainnya' => 2]);

                $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_lny)->first();

                $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_lny)->first();

                if($stat->hasil == $div->hasil){
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lny)->update(['validasi' => 1]);
                }else{
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lny)->update(['validasi' => 0]);
                }
            }
        }

        DB::table('temp_list_action')->where('divisi', 6)->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->nomor_complaint_lny, 'divisi' => 7, 'action' => 'Divisi Lainnya Berhasil Memproses dan Memasukkan Data pada Complaint Nomor ' . $request->nomor_complaint_lny]);
        ModelComplaintCust::where('nomor_complaint', $request->nomor_complaint_lny)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function show($nomor_complaint){   

        $val_nomor_complaint = $this->decrypt($nomor_complaint);

        $data = DB::table('complaint_lainnya as com')->select('com.nomor_complaint as nomor_complaint', 'com.divisi as divisi', 'com.evaluasi as evaluasi', 'com.solusi_internal as solusi_internal', 'com.solusi_customer as solusi_customer', 'com.lampiran as lampiran')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $komitmen = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $nomor_complaint)->where('divisi', 6)->get();
     
        return Response()->json(["data_complaint" => $data, "data_komitmen" => $komitmen]);
    }

    public function complaintTdkTerlibat(Request $request){
        $no_complaint = $request->nomor_complaint_alasan;
        DB::table('divisi_complaint')->where('nomor_complaint', $no_complaint)->update(['lainnya' => 1, 'alasan_lainnya' => $request->alasan_tidak_terlibat]);

        $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        if($stat->hasil == $div->hasil){
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 1]);
        }else{
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 0]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $no_complaint, 'divisi' => 4, 'action' => 'Divisi Lainnya Merubah Status Menjadi Tidak Terlibat Pada Complaint Nomor ' . $no_complaint . ' Dengan Alasan : "' . $request->alasan_tidak_terlibat . '"']);
        ModelComplaintCust::where('nomor_complaint', $no_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);
    }

    public function complaintTerlibat(Request $request){
        $no_complaint = $request->get('nomor_complaint');
        DB::table('divisi_complaint')->where('nomor_complaint', $no_complaint)->update(['lainnya' => 2, 'alasan_lainnya' => null]);

        $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        if($stat->hasil == $div->hasil){
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 1]);
        }else{
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 0]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $no_complaint, 'divisi' => 7, 'action' => 'Divisi Lainnya Merubah Status Menjadi Terlibat Pada Complaint Nomor ' . $no_complaint]);
        ModelComplaintCust::where('nomor_complaint', $no_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);
    }
}
