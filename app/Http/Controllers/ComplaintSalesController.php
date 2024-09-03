<?php

namespace App\Http\Controllers;

use App\ModelComplaintSales;
use App\ModelComplaintCust;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class ComplaintSalesController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';

    public function index(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
        	if(!empty($request->from_date)){
            	$complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [1, 4, 5, 7])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
            	$complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [1, 4, 5, 7])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
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
        if($request->hasFile('upload_file_sls')) {
            $file = $request->file('upload_file_sls');
            $nama_file = time()."_".$request->custid_sls."_".$request->nomor_complaint_sls."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data_komitmen = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_sls)->where('divisi', 3)->first();

            if($data_komitmen){
                $data_komitmen_get = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_sls)->where('divisi', 3)->get();

                foreach($data_komitmen_get as $data){
                    $komitmen = DB::table('list_of_action_complaint')->insert(['id_user' => $data->id_user, 'nomor_complaint' => $data->nomor_complaint, 'divisi' => $data->divisi, 'komitmen' => $data->komitmen, 'selesai_tanggal_komitmen' => $data->selesai_tanggal_komitmen, 'status' => 1]);
                }
            }
            
            $complaint=ModelComplaintSales::insert(["nomor_complaint" => $request->nomor_complaint_sls, "tanggal_customer_setuju" => $request->tanggal_customer, "evaluasi" => $request->evaluasi_sls, "solusi_internal" => $request->solusi_internal_sls, "solusi_customer" => $request->solusi_customer_sls, "lampiran" => $nama_file, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

            if($complaint){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_sls)->update(['baca' => 0, 'sales' => 2]);

                $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_sls)->first();

                $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_sls)->first();

                if($stat->hasil == $div->hasil){
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_sls)->update(['validasi' => 1]);
                }else{
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_sls)->update(['validasi' => 0]);
                }
            }
        }else{
            $data_komitmen = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_sls)->where('divisi', 3)->first();

            if($data_komitmen){
                $data_komitmen_get = DB::table('temp_list_action')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $request->nomor_complaint_sls)->where('divisi', 3)->get();

                foreach($data_komitmen_get as $data){
                    $komitmen = DB::table('list_of_action_complaint')->insert(['id_user' => $data->id_user, 'nomor_complaint' => $data->nomor_complaint, 'divisi' => $data->divisi, 'komitmen' => $data->komitmen, 'selesai_tanggal_komitmen' => $data->selesai_tanggal_komitmen, 'status' => 1]);
                }
            }

            $complaint=ModelComplaintSales::insert(["nomor_complaint" => $request->nomor_complaint_sls, "tanggal_customer_setuju" => $request->tanggal_customer, "evaluasi" => $request->evaluasi_sls, "solusi_internal" => $request->solusi_internal_sls, "solusi_customer" => $request->solusi_customer_sls, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

            if($complaint){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_sls)->update(['baca' => 0, 'sales' => 2]);

                $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_sls)->first();

                $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_sls)->first();

                if($stat->hasil == $div->hasil){
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_sls)->update(['validasi' => 1]);
                }else{
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_sls)->update(['validasi' => 0]);
                }
            }
        }

        DB::table('temp_list_action')->where('divisi', 3)->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->nomor_complaint_sls, 'divisi' => 3, 'action' => 'Divisi Sales Berhasil Memproses dan Memasukkan Data pada Complaint Nomor ' . $request->nomor_complaint_sls]);
        ModelComplaintCust::where('nomor_complaint', $request->nomor_complaint_sls)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function show($nomor_complaint){   

        $val_nomor_complaint = $this->decrypt($nomor_complaint);

        $data  = DB::table('complaint_sales as com')->select('com.nomor_complaint', 'com.tanggal_customer_setuju', 'com.evaluasi', 'com.solusi_internal', 'com.solusi_customer', 'com.lampiran')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $komitmen = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $val_nomor_complaint)->where('divisi', 3)->get();
     
        return Response()->json(['data_hitung' => $data, "data_komitmen" => $komitmen]);
    }

    public function showDataSales($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.tanggal_order', 'com.sales_order', 'com.supervisor_sales', 'com.pelapor', 'com.tanggal_pengiriman', 'com.area', 'com.supervisor', 'com.pengiriman', 'com.pengiriman_lain', 'com.nama_supir', 'com.nama_kernet', 'com.no_kendaraan', 'com.jenis_kendaraan', 'com.jenis_kendaraan_lain', 'com.jumlah_karung', 'com.quantity', 'com.jumlah_kg_sak', 'com.jenis_karung', 'com.berat_timbangan', 'com.unit_berat_timbangan', 'com.berat_aktual', 'com.unit_berat_aktual', 'com.kuli1', 'com.kuli2', 'com.kuli3', 'com.stapel1', 'com.stapel2', 'com.stapel3', 'com.stapel4', 'com.stapel5')->join('divisi_complaint as divi', 'divi.nomor_complaint', '=', 'com.nomor_complaint')->where('com.nomor_complaint', $val_no_complaint)->first();

        $orders = DB::table('data_complaint_produksi as dcp')->select("dcp.nomor_complaint", "dcp.no_lot", "dcp.tanggal_produksi", "dcp.kode_produk", "prd.nama_produk", "dcp.custid", "dcp.mesin", "dcp.area", "dcp.supervisor", "dcp.petugas1", "dcp.petugas2", "dcp.petugas3", "dcp.petugas4", "dcp.petugas5", "dcp.bermasalah")->join("products as prd", "prd.kode_produk", "=", "dcp.kode_produk")->where('dcp.nomor_complaint', $val_no_complaint)->where('dcp.bermasalah', 'Ya')->get();
     
        return Response()->json(['data_produksi' => $orders, 'data_hitung' => $data]);
    }

    public function complaintTdkTerlibat(Request $request){
        $no_complaint = $request->get('nomor_complaint');
        DB::table('divisi_complaint')->where('nomor_complaint', $no_complaint)->update(['sales' => 1]);

        $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        if($stat->hasil == $div->hasil){
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 1]);
        }else{
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 0]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $no_complaint, 'divisi' => 3, 'action' => 'Divisi Sales Merubah Status Menjadi Tidak Terlibat Pada Complaint Nomor ' . $no_complaint]);
        ModelComplaintCust::where('nomor_complaint', $no_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);
    }

    public function complaintTerlibat(Request $request){
        $no_complaint = $request->get('nomor_complaint');
        DB::table('divisi_complaint')->where('nomor_complaint', $no_complaint)->update(['sales' => 2]);

        $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        if($stat->hasil == $div->hasil){
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 1]);
        }else{
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 0]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $no_complaint, 'divisi' => 3, 'action' => 'Divisi Sales Merubah Status Menjadi Terlibat Pada Complaint Nomor ' . $no_complaint]);
        ModelComplaintCust::where('nomor_complaint', $no_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);
    }
}
