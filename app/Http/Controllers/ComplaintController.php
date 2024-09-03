<?php

namespace App\Http\Controllers;
use App\ModelComplaintCust;
use App\ModelUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Response;
use Carbon\Carbon;
use File;
use PDF;

class ComplaintController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';

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

    public function complaint_view($nomor = null, $type = null){
        DB::table('temp_list_action')->where('divisi', 3)->delete();
        DB::table('temp_list_action')->where('divisi', 6)->delete();
        DB::table('temp_no_lot')->delete();
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $id_user = Session::get('id_user_admin');
            $user = ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
            $user->unreadNotifications->where('type', 'App\Notifications\NotifNewComplaint')->markAsRead();
            if(!empty($nomor) && !empty($type)){
                try{
                    $val_nomor = Crypt::decrypt($nomor);
                    $val_type = Crypt::decrypt($type); 
                } catch (DecryptException $e) {
                    $val_nomor = $nomor;
                    $val_type = $type;
                }
                return view('input_complaint', ['any_nomor' => $val_nomor, 'tipe_complaint' => $val_type]);
            }else{
                return view('input_complaint');
            }
        }
    }

    public function complaint_view_produksi($nomor = null, $type = null){
        DB::table('temp_list_action')->where('divisi', 1)->delete();
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            if(!empty($nomor) && !empty($type)){
                try{
                    $val_nomor = Crypt::decrypt($nomor);
                    $val_type = Crypt::decrypt($type); 
                } catch (DecryptException $e) {
                    $val_nomor = $nomor;
                    $val_type = $type;
                }
                return view('produksi_complaint', ['any_nomor' => $val_nomor, 'tipe_complaint' => $val_type]);
            }else{
                return view('produksi_complaint');
            }
        }
    }

    public function complaint_view_logistik($nomor = null, $type = null){
        DB::table('temp_list_action')->where('divisi', 2)->delete();
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            if(!empty($nomor) && !empty($type)){
                try{
                    $val_nomor = Crypt::decrypt($nomor);
                    $val_type = Crypt::decrypt($type); 
                } catch (DecryptException $e) {
                    $val_nomor = $nomor;
                    $val_type = $type;
                }
                return view('logistik_complaint', ['any_nomor' => $val_nomor, 'tipe_complaint' => $val_type]);
            }else{
                return view('logistik_complaint');
            }
        }
    }

    public function complaint_view_timbangan($nomor = null, $type = null){
        DB::table('temp_list_action')->where('divisi', 4)->delete();
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            if(!empty($nomor) && !empty($type)){
                try{
                    $val_nomor = Crypt::decrypt($nomor);
                    $val_type = Crypt::decrypt($type); 
                } catch (DecryptException $e) {
                    $val_nomor = $nomor;
                    $val_type = $type;
                }
                return view('timbangan_complaint', ['any_nomor' => $val_nomor, 'tipe_complaint' => $val_type]);
            }else{
                return view('timbangan_complaint');
            }
        }
    }

    public function complaint_view_warehouse($nomor = null, $type = null){
        DB::table('temp_list_action')->where('divisi', 5)->delete();
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            if(!empty($nomor) && !empty($type)){
                try{
                    $val_nomor = Crypt::decrypt($nomor);
                    $val_type = Crypt::decrypt($type); 
                } catch (DecryptException $e) {
                    $val_nomor = $nomor;
                    $val_type = $type;
                }
                return view('warehouse_complaint', ['any_nomor' => $val_nomor, 'tipe_complaint' => $val_type]);
            }else{
                return view('warehouse_complaint');
            }
        }
    }

    public function complaint_view_lab($nomor = null, $type = null){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            DB::table('temp_data_lab')->delete();
            DB::table('temp_quality_detail_lab')->delete();
            if(!empty($nomor) && !empty($type)){
                try{
                    $val_nomor = Crypt::decrypt($nomor);
                    $val_type = Crypt::decrypt($type); 
                } catch (DecryptException $e) {
                    $val_nomor = $nomor;
                    $val_type = $type;
                }
                return view('lab_complaint', ['any_nomor' => $val_nomor, 'tipe_complaint' => $val_type]);
            }else{
                return view('lab_complaint');
            }
        }
    }

    public function logbookComplaint(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('logbook_complaint');
        }
    }

    public function showDataComplaint($no_complaint){
        $val_no_complaint = $this->decrypt($no_complaint);

        $data = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.tanggal_complaint', 'com.nama_customer', 'com.nomor_surat_jalan', 'com.tanggal_order', 'com.complaint', 'com.sales_order', 'com.supervisor_sales', 'com.pelapor', 'com.lampiran', 'com.quantity', 'jml.name as jumlah_kg_sak', 'jen.name as jenis_karung', 'com.berat_timbangan', 'tim.name as unit_berat_timbangan', 'com.berat_aktual', 'akt.name as unit_berat_aktual', 'com.jumlah_karung', 'com.tanggal_pengiriman', 'are.name as area', 'com.nama_supir', 'com.nama_kernet', 'com.no_kendaraan', 'com.supervisor', 'kirim.name as pengiriman', 'com.pengiriman_lain', 'jeni.name as jenis_kendaraan', 'com.jenis_kendaraan_lain', 'com.kuli1', 'com.kuli2', 'com.kuli3', 'com.stapel1', 'com.stapel2', 'com.stapel3', 'com.stapel4', 'com.stapel5')->join('tbl_jumlah_kg_sak as jml', 'jml.id', '=', 'com.jumlah_kg_sak')->join('tbl_jenis_karung as jen', 'jen.id', '=', 'com.jenis_karung')->join('tbl_unit_berat as tim', 'tim.id', '=', 'com.unit_berat_timbangan')->join('tbl_unit_berat as akt', 'akt.id', '=', 'com.unit_berat_aktual')->join('tbl_area as are', 'are.id', '=', 'com.area')->join('tbl_pengiriman as kirim', 'kirim.id', '=', 'com.pengiriman')->join('tbl_jenis_kendaraan as jeni', 'jeni.id', '=', 'com.jenis_kendaraan')->where('com.nomor_complaint', $val_no_complaint)->first();

        $orders = DB::table('data_complaint_produksi as com')->select("com.nomor_complaint", "com.no_lot", "com.tanggal_produksi", "com.custid", "prd.nama_produk", "com.mesin", "ar.name as area", "com.supervisor", "com.bermasalah", "com.petugas1", "com.petugas2", "com.petugas3", "com.petugas4", "com.petugas5")->join('tbl_area as ar', 'ar.id', '=', 'com.area')->join("products as prd", "prd.kode_produk", "=", "com.kode_produk")->where('com.nomor_complaint', $val_no_complaint)->get();
     
        return Response()->json(["data_complaint" => $data, "data_produksi" => $orders]);
        // return Response()->json($no_complaint);
    }

    public function printFormComplaint($nomor_complaint){
        $val_nomor_complaint = Crypt::decrypt($nomor_complaint);

        $data = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.sales_order', 'com.supervisor_sales', 'com.pelapor', 'com.tanggal_complaint', 'com.nama_customer', 'com.nomor_surat_jalan', 'com.complaint', 'com.lampiran', DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), 'com.tanggal_choose_div', 'com.tanggal_validasi',DB::raw("(if(divi.produksi = 2, 2, 0) + if(divi.logistik = 2, 2, 0) + if(divi.sales = 2, 2, 0) + if(divi.timbangan = 2, 2, 0) + if(divi.warehouse = 2, 2, 0) + if(divi.lab = 2, 2, 0) + if(divi.lainnya = 2, 2, 0)) as no_div"), "com.quantity", "com.jumlah_karung", DB::raw("concat(com.berat_timbangan, ' ', unit1.name) as berat_timbangan"), DB::raw("concat(com.berat_aktual, ' ', unit2.name) as berat_aktual"), DB::raw("concat(jml.name, ' ', jenis.name) as jenis_karung"))->join('divisi_complaint as divi', 'divi.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->join('tbl_jumlah_kg_sak as jml', 'jml.id', '=', 'com.jumlah_kg_sak')->join('tbl_jenis_karung as jenis', 'jenis.id', '=', 'com.jenis_karung')->join('tbl_unit_berat as unit1', 'unit1.id', '=', 'com.unit_berat_timbangan')->join('tbl_unit_berat as unit2', 'unit2.id', '=', 'com.unit_berat_aktual')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $produksi = DB::table('complaint_produksi as com')->select('com.evaluasi', 'com.solusi_internal', 'com.solusi_customer', 'com.lampiran', 'users.nama_admin', 'com.tanggal_input', DB::raw('CONCAT(kelas.name, " ", tipe.type) as jabatan'))->join('users', 'users.id_user', '=', 'com.created_by')->join('user_type as tipe', 'tipe.id_customer_type', '=', 'users.id_customer_type')->join('user_class as kelas', 'kelas.id', '=', 'users.id_user_class')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $komitmen_produksi = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $val_nomor_complaint)->where('divisi', 1)->get();

        $data_produksi = DB::table('data_complaint_produksi as com')->select('com.no_lot', 'com.tanggal_produksi', 'com.supervisor', 'com.mesin', 'prd.nama_produk', 'area.name as area', 'com.petugas1', 'com.petugas2', 'com.petugas3', 'com.petugas4', 'com.petugas5', 'com.bermasalah')->join('tbl_area as area', 'area.id', '=', 'com.area')->join('products as prd', 'prd.kode_produk', '=', 'com.kode_produk')->where('com.bermasalah', 'Ya')->where('com.nomor_complaint', $val_nomor_complaint)->get();

        $data_logistik = DB::table('complaint_customer as com')->select('com.tanggal_pengiriman', 'com.no_kendaraan', 'com.nama_supir', 'area.name as area', 'com.nama_kernet', 'kirim.name as pengiriman', 'com.pengiriman_lain', 'jenis.name as jenis_kendaraan', 'com.jenis_kendaraan_lain', 'com.supervisor', "com.kuli1", "com.kuli2", "com.kuli3", "com.stapel1", "com.stapel2", "com.stapel3", "com.stapel4", "com.stapel5")->join('tbl_area as area', 'area.id', '=', 'com.area')->join('tbl_pengiriman as kirim', 'kirim.id', '=', 'com.pengiriman')->join('tbl_jenis_kendaraan as jenis', 'jenis.id', '=', 'com.jenis_kendaraan')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $logistik = DB::table('complaint_logistik as com')->select('com.evaluasi', 'com.solusi_internal', 'com.solusi_customer', 'com.lampiran', 'users.nama_admin', 'com.tanggal_input', DB::raw('CONCAT(kelas.name, " ", tipe.type) as jabatan'))->join('users', 'users.id_user', '=', 'com.created_by')->join('user_type as tipe', 'tipe.id_customer_type', '=', 'users.id_customer_type')->join('user_class as kelas', 'kelas.id', '=', 'users.id_user_class')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $komitmen_logistik = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $val_nomor_complaint)->where('divisi', 2)->get();

        $sales = DB::table('complaint_sales as com')->select('com.tanggal_customer_setuju', 'com.evaluasi', 'com.solusi_internal', 'com.solusi_customer', 'com.lampiran', 'users.nama_admin', 'com.tanggal_input', DB::raw('CONCAT(kelas.name, " ", tipe.type) as jabatan'))->join('users', 'users.id_user', '=', 'com.created_by')->join('user_type as tipe', 'tipe.id_customer_type', '=', 'users.id_customer_type')->join('user_class as kelas', 'kelas.id', '=', 'users.id_user_class')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $komitmen_sales = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $val_nomor_complaint)->where('divisi', 3)->get();

        $lainnya = DB::table('complaint_lainnya as com')->select('com.divisi', 'com.evaluasi', 'com.solusi_internal', 'com.solusi_customer', 'com.lampiran', 'users.nama_admin', 'com.tanggal_input', DB::raw('CONCAT(kelas.name, " ", tipe.type) as jabatan'))->join('users', 'users.id_user', '=', 'com.created_by')->join('user_type as tipe', 'tipe.id_customer_type', '=', 'users.id_customer_type')->join('user_class as kelas', 'kelas.id', '=', 'users.id_user_class')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $komitmen_lainnya = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $val_nomor_complaint)->where('divisi', 6)->get();

        $timbangan = DB::table('complaint_timbangan as com')->select('com.evaluasi', 'com.solusi_internal', 'com.solusi_customer', 'com.lampiran', 'users.nama_admin', 'com.tanggal_input', DB::raw('CONCAT(kelas.name, " ", tipe.type) as jabatan'))->join('users', 'users.id_user', '=', 'com.created_by')->join('user_type as tipe', 'tipe.id_customer_type', '=', 'users.id_customer_type')->join('user_class as kelas', 'kelas.id', '=', 'users.id_user_class')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $komitmen_timbangan = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $val_nomor_complaint)->where('divisi', 4)->get();

        $warehouse = DB::table('complaint_warehouse as com')->select('com.evaluasi', 'com.solusi_internal', 'com.solusi_customer', 'com.lampiran', 'users.nama_admin', 'com.tanggal_input', DB::raw('CONCAT(kelas.name, " ", tipe.type) as jabatan'))->join('users', 'users.id_user', '=', 'com.created_by')->join('user_type as tipe', 'tipe.id_customer_type', '=', 'users.id_customer_type')->join('user_class as kelas', 'kelas.id', '=', 'users.id_user_class')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $komitmen_warehouse = DB::table('list_of_action_complaint')->select('komitmen', 'selesai_tanggal_komitmen')->where('nomor_complaint', $val_nomor_complaint)->where('divisi', 5)->get();

        $lab = DB::table('complaint_lab as com')->select('com.suggestion', 'com.lampiran', 'users.nama_admin', 'com.tanggal_input', DB::raw('CONCAT(kelas.name, " ", tipe.type) as jabatan'))->join('users', 'users.id_user', '=', 'com.created_by')->join('user_type as tipe', 'tipe.id_customer_type', '=', 'users.id_customer_type')->join('user_class as kelas', 'kelas.id', '=', 'users.id_user_class')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $data_lab = DB::table('data_complaint_lab')->select('nomor_sample_lab', 'nomor_complaint', 'no_lot', 'keterangan')->where('nomor_complaint', $val_nomor_complaint)->get();

        $data_quality_lab = DB::table('data_complaint_quality_detail_lab')->select('nomor_quality_detail_lab', 'nomor_sample_lab', 'nomor_complaint', 'no_lot', 'qual.name as quality', 'quality_name', 'quality_name_lainnya', 'metode_mesin', 'hasil', 'satuan')->join('tbl_quality_lab as qual', 'qual.id', '=', 'data_complaint_quality_detail_lab.quality_name')->where('nomor_complaint', $val_nomor_complaint)->get();

        $pdf = PDF::loadView('print_form_complaint', ['data' => $data, 'data_produksi' => $data_produksi, 'data_logistik' => $data_logistik, 'produksi' => $produksi, 'logistik' => $logistik, 'sales' => $sales, 'lainnya' => $lainnya, 'timbangan' => $timbangan, 'warehouse' => $warehouse, 'lab' => $lab, 'komitmen_produksi' => $komitmen_produksi, 'komitmen_logistik' => $komitmen_logistik, 'komitmen_sales' => $komitmen_sales, 'komitmen_timbangan' => $komitmen_timbangan, 'komitmen_warehouse' => $komitmen_warehouse, 'komitmen_lainnya' => $komitmen_lainnya, 'data_lab' => $data_lab, 'data_quality_lab' => $data_quality_lab])->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function validasiListKomitmen(Request $request){
        if(request()->ajax()){
            $list_komitmen = DB::table('list_of_action_complaint as list')->select(DB::raw("case when list.divisi = 1 then 'Produksi' when list.divisi = 2 then 'Logistik' when list.divisi = 3 then 'Sales' when list.divisi = 4 then 'Timbangan' when list.divisi = 5 then 'Warehouse' when list.divisi = 6 then 'Lainnya' else '' end as divisi"), "list.komitmen", "list.selesai_tanggal_komitmen")->where("list.nomor_complaint", $request->no_complaint)->get();

            return datatables()->of($list_komitmen)->addIndexColumn()->make(true);
        }
        // return Response()->json($list_komitmen);
    }

    public function logbookComplaintTable(Request $request){
        $logbook = DB::table('logbook_complaint as log')->select("log.tanggal", "log.nomor_complaint", "divi.name as divisi", "log.action")->join("divisi_logbook as divi", "divi.id", "=", "log.divisi")->where("log.nomor_complaint", $request->nomor_complaint)->get();

        return datatables()->of($logbook)->make(true);
    }

    public function requestLab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        DB::table('complaint_customer')->where('nomor_complaint', $request->nomor_complaint_req)->update(['alasan_request_lab' => $request->alasan_request_lab]);

        $lab_request = DB::table('divisi_complaint')->where('nomor_complaint', $request->nomor_complaint_req)->update(['lab' => 2]);

        if($lab_request){
            $arr = array('msg' => 'Data Updated Successfully', 'status' => true);
        }

        return Response()->json($lab_request);
    }

    public function noRequestLab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $alasan_request = DB::table('complaint_customer')->where('nomor_complaint', $request->nomor_complaint)->update(['alasan_request_lab' => null]);

        $lab_request = DB::table('divisi_complaint')->where('nomor_complaint', $request->nomor_complaint)->update(['lab' => 0]);

        if($lab_request){
            $arr = array('msg' => 'Data Updated Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function view_complaint_produksi(){
        return view('view_complaint_produksi');
    }

    public function view_complaint_logistik(){
        return view('view_complaint_logistik');
    }

    public function view_complaint_lainnya(){
        return view('view_complaint_lainnya');
    }

    public function validation_complaint_produksi(){
        return view('validation_complaint_produksi');
    }

    public function validation_complaint_logistik(){
        return view('validation_complaint_logistik');
    }

    public function validation_complaint_lainnya(){
        return view('validation_complaint_lainnya');
    }

    public function validasi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        // $validasi = ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->update([
        //                 'status' => 17, 'tanggal_validasi' => date('Y-m-d')
        //             ]);

        $validasi = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update([
                        'validasi' => 2, 'done' => 1
                    ]);

        ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->update([
                        'tanggal_validasi' => date('Y-m-d')
                    ]);

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->get('nomor_complaint'), 'divisi' => 8, 'action' => 'Admin Memvalidasi Complaint Nomor ' . $request->get('nomor_complaint') . ' dan Status Complaint menjadi Done']);
        ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function rejectComplaint(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $reject = ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->delete();

        if($reject){
            $arr = array('msg' => 'Data Rejected Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->get('nomor_complaint'), 'divisi' => 8, 'action' => 'Admin Reject / Menolak Complaint Nomor ' . $request->get('nomor_complaint')]);

        return Response()->json($arr);
    }

    public function rollbackValidasiComplaint(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $reject = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['validasi' => 1]);

        if($reject){
            $arr = array('msg' => 'Rollback Data Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->get('nomor_complaint'), 'divisi' => 8, 'action' => 'Admin Rollback Complaint Nomor ' . $request->get('nomor_complaint') . ' yang Sebelumnya Berstatus Done']);
        ModelComplaintCust::where('nomor_complaint',  $request->get('nomor_complaint'))->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function rollbackComplaintProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data = DB::table('divisi_complaint as div')->select(DB::raw("(div.produksi + div.logistik + div.sales + div.timbangan + div.warehouse + div.lab + div.lainnya) as total"), 'stat.produksi')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'div.nomor_complaint')->where('div.nomor_complaint', $request->get('nomor_complaint'))->first();

        if($data->total == 2 && ($data->produksi == 0 || $data->produksi == 1)){
            $rollback = ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->update(['status' => NULL, 'divisi' => NULL]);
            DB::table('divisi_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->delete();
            DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->delete();
        }else if($data->total == 2 && $data->produksi == 2){
            $rollback = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['produksi' => 0, 'baca' => 1]);
        }else{
            $rollback = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['produksi' => 0]);
        }

        if($rollback){
            $arr = array('msg' => 'Rollback Data Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->get('nomor_complaint'), 'divisi' => 1, 'action' => 'Divisi Produksi Rollback Complaint Nomor ' . $request->get('nomor_complaint')]);
        ModelComplaintCust::where('nomor_complaint',  $request->get('nomor_complaint'))->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function rollbackComplaintLogistik(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data = DB::table('divisi_complaint as div')->select(DB::raw("(div.produksi + div.logistik + div.sales + div.timbangan + div.warehouse + div.lab + div.lainnya) as total"), 'stat.logistik')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'div.nomor_complaint')->where('div.nomor_complaint', $request->get('nomor_complaint'))->first();

        if($data->total == 2 && ($data->logistik == 0 || $data->logistik == 1)){
            $rollback = ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->update(['status' => NULL, 'divisi' => NULL]);
            DB::table('divisi_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->delete();
            DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->delete();
        }else if($data->total == 2 && $data->logistik == 2){
            $rollback = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['logistik' => 0, 'baca' => 1]);
        }else{
            $rollback = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['logistik' => 0]);
        }

        if($rollback){
            $arr = array('msg' => 'Rollback Data Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->get('nomor_complaint'), 'divisi' => 2, 'action' => 'Divisi Logistik Rollback Complaint Nomor ' . $request->get('nomor_complaint')]);
        ModelComplaintCust::where('nomor_complaint',  $request->get('nomor_complaint'))->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function rollbackComplaintSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data = DB::table('divisi_complaint as div')->select(DB::raw("(div.produksi + div.logistik + div.sales + div.timbangan + div.warehouse + div.lab + div.lainnya) as total"), 'stat.sales')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'div.nomor_complaint')->where('div.nomor_complaint', $request->get('nomor_complaint'))->first();

        if($data->total == 2 && ($data->sales == 0 || $data->sales == 1)){
            $rollback = ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->update(['status' => NULL, 'divisi' => NULL]);
            DB::table('divisi_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->delete();
            DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->delete();
        }else if($data->total == 2 && $data->sales == 2){
            $rollback = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['sales' => 0, 'baca' => 1]);
        }else{
            $rollback = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['sales' => 0]);
        }

        if($rollback){
            $arr = array('msg' => 'Rollback Data Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->get('nomor_complaint'), 'divisi' => 3, 'action' => 'Divisi Sales Rollback Complaint Nomor ' . $request->get('nomor_complaint')]);
        ModelComplaintCust::where('nomor_complaint',  $request->get('nomor_complaint'))->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function rollbackComplaintLainnya(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data = DB::table('divisi_complaint as div')->select(DB::raw("(div.produksi + div.logistik + div.sales + div.timbangan + div.warehouse + div.lab + div.lainnya) as total"), 'stat.lainnya')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'div.nomor_complaint')->where('div.nomor_complaint', $request->get('nomor_complaint'))->first();

        if($data->total == 2 && ($data->lainnya == 0 || $data->lainnya == 1)){
            $rollback = ModelComplaintCust::where('nomor_complaint', $request->get('nomor_complaint'))->update(['status' => NULL, 'divisi' => NULL]);
            DB::table('divisi_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->delete();
            DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->delete();
        }else if($data->total == 2 && $data->lainnya == 2){
            $rollback = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['lainnya' => 0, 'baca' => 1]);
        }else{
            $rollback = DB::table('status_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->update(['lainnya' => 0]);
        }

        if($rollback){
            $arr = array('msg' => 'Rollback Data Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->get('nomor_complaint'), 'divisi' => 7, 'action' => 'Divisi Lainnya Rollback Complaint Nomor ' . $request->get('nomor_complaint')]);
        ModelComplaintCust::where('nomor_complaint',  $request->get('nomor_complaint'))->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function indexComplaintSpecific(Request $request){
        $divisi_produksi = array(1, 2);
        $divisi_logistik = array(1, 2);
        $divisi_sales = array(1, 2);
        $divisi_lainnya = array(1, 2);
        $divisi_timbangan = array(1, 2);
        $divisi_warehouse = array(1, 2);
        $divisi_lab = array(1, 2);

        if(request()->ajax()){
            $complaint = DB::table('divisi_complaint')->select("produksi", "logistik", "sales", "timbangan", "warehouse", "lab", "lainnya")->where('nomor_complaint', $request->nomor)->first();

            if(in_array($complaint->produksi, $divisi_produksi)){
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.produksi as div_produksi", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

                return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
            }else if(in_array($complaint->logistik, $divisi_logistik)){
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.logistik as div_logistik", "stat.logistik as stat.logistik", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

                return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
            }else if(in_array($complaint->sales, $divisi_sales)){
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.sales as div_sales", "stat.sales as stat_sales", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

                return datatables()->of($complaint_sales)->addColumn('action', 'button/action_button_complaint_sales')->rawColumns(['action'])->make(true);
            }else if(in_array($complaint->lainnya, $divisi_lainnya)){
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.lainnya as div_lainnya", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

                return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
            }else if(in_array($complaint->timbangan, $divisi_timbangan)){
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.timbangan as div_timbangan", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

                return datatables()->of($complaint_timbangan)->addColumn('action', 'button/action_button_complaint_timbangan')->rawColumns(['action'])->make(true);
            }else if(in_array($complaint->warehouse, $divisi_warehouse)){
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.warehouse as div_warehouse", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

                return datatables()->of($complaint_warehouse)->addColumn('action', 'button/action_button_complaint_warehouse')->rawColumns(['action'])->make(true);
            }else if(in_array($complaint->lab, $divisi_lab)){
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.lab as div_lab", "stat.lab as stat_lab", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

                return datatables()->of($complaint_lab)->addColumn('action', 'button/action_button_complaint_lab')->rawColumns(['action'])->make(true);
            }else{
                $konfirmasi_complaint = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "com.lampiran as lampiran")->where('com.nomor_complaint', $request->nomor)->get();

                return datatables()->of($konfirmasi_complaint)->addColumn('action', 'button/action_button_divisicomplaint')->rawColumns(['action'])->make(true);
            }
        }
        return view('input_complaint');
    }

    public function indexComplaintProduksiSpecific(Request $request){
        if(request()->ajax()){
            $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.produksi as div_produksi", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLogistikSpecific(Request $request){
        if(request()->ajax()){
            $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.logistik as div_logistik", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

            return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
        }
    }

    public function indexComplaintSalesSpecific(Request $request){
        if(request()->ajax()){
            $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.sales as div_sales", "stat.sales as stat_sales", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

            return datatables()->of($complaint_sales)->addColumn('action', 'button/action_button_complaint_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLainnyaSpecific(Request $request){
        if(request()->ajax()){
            $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.lainnya as div_lainnya", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintTimbanganSpecific(Request $request){
        if(request()->ajax()){
            $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.timbangan as div_timbangan", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

            return datatables()->of($complaint_timbangan)->addColumn('action', 'button/action_button_complaint_timbangan')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintWarehouseSpecific(Request $request){
        if(request()->ajax()){
            $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.warehouse as div_warehouse", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

            return datatables()->of($complaint_warehouse)->addColumn('action', 'button/action_button_complaint_warehouse')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLabSpecific(Request $request){
        if(request()->ajax()){
            $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "divi.lab as div_lab", "stat.lab as stat_lab", "stat.validasi as stat_validasi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('com.nomor_complaint', $request->nomor)->get();

            return datatables()->of($complaint_lab)->addColumn('action', 'button/action_button_complaint_lab')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintProduksiSemua(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                // $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [1, 5, 6, 7, 11, 12, 13, 15, 16, 20, 21, 22, 23, 24, 25, 26, 27, 30, 31, 33, 34, 37, 38, 40, 41, 44, 45, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 61, 63, 65, 67, 69, 71, 72, 73, 74, 75, 76, 77, 79, 80])->whereIn('com.status', [1, 3, 4, 5, 9, 10, 11, 15, 17])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.produksi', [1, 2])->whereIn('stat.produksi', [0, 2])->whereIn('stat.validasi', [0, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                // $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [1, 5, 6, 7, 11, 12, 13, 15, 16, 20, 21, 22, 23, 24, 25, 26, 27, 30, 31, 33, 34, 37, 38, 40, 41, 44, 45, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 61, 63, 65, 67, 69, 71, 72, 73, 74, 75, 76, 77, 79, 80])->whereIn('com.status', [1, 3, 4, 5, 9, 10, 11, 15, 17])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->get();
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.produksi', [1, 2])->whereIn('stat.produksi', [0, 2])->whereIn('stat.validasi', [0, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintProduksiSemuaAdmin(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.produksi', [1, 2])->whereIn('stat.produksi', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.produksi', [1, 2])->whereIn('stat.produksi', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintProduksiProses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.produksi', [1, 2])->where('stat.produksi', 0)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.produksi', [1, 2])->where('stat.produksi', 0)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
        }

        return view('input_complaint');
    }

    public function indexComplaintProduksiDiproses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.produksi', 2)->where('stat.produksi', 2)->whereIn('stat.validasi', [0, 1])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.produksi', 2)->where('stat.produksi', 2)->whereIn('stat.validasi', [0, 1])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
        }

        return view('input_complaint');
    }

    public function indexComplaintProduksiValid(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.produksi', 2)->where('stat.validasi', 1)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.produksi', 2)->where('stat.validasi', 1)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
        }

        return view('input_complaint');
    }

    public function indexComplaintProduksiSelesai(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.produksi', 2)->where('stat.validasi', 2)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_produksi = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.produksi as stat_produksi", "stat.validasi as stat_validasi", "divi.produksi as div_produksi")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.produksi', 2)->where('stat.validasi', 2)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_produksi)->addColumn('action', 'button/action_button_complaint_produksi')->rawColumns(['action'])->make(true);
        }

        return view('input_complaint');
    }

    public function indexComplaintLogistikSemua(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                // $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [2, 5, 8, 9, 11, 12, 14, 15, 17, 20, 23, 24, 26, 27, 28, 29, 30, 31, 32, 33, 35, 37, 39, 40, 42, 44, 46, 47, 48, 49, 50, 51, 53, 55, 57, 59, 60, 61, 62, 63, 64, 65, 66, 67, 70, 71, 72, 73, 74, 75, 77, 78, 79, 80])->whereIn('com.status', [1, 2, 4, 5, 7, 8, 11, 14, 17])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();

                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.logistik', [1, 2])->whereIn('stat.logistik', [0, 2])->whereIn('stat.validasi', [0, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                // $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [2, 5, 8, 9, 11, 12, 14, 15, 17, 20, 23, 24, 26, 27, 28, 29, 30, 31, 32, 33, 35, 37, 39, 40, 42, 44, 46, 47, 48, 49, 50, 51, 53, 55, 57, 59, 60, 61, 62, 63, 64, 65, 66, 67, 70, 71, 72, 73, 74, 75, 77, 78, 79, 80])->whereIn('com.status', [1, 2, 4, 5, 7, 8, 11, 14, 17])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->get();

                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.logistik', [1, 2])->whereIn('stat.logistik', [0, 2])->whereIn('stat.validasi', [0, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLogistikSemuaAdmin(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.logistik', [1, 2])->whereIn('stat.logistik', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.logistik', [1, 2])->whereIn('stat.logistik', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLogistikProses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.logistik', [1, 2])->where('stat.logistik', 0)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.logistik', [1, 2])->where('stat.logistik', 0)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLogistikDiproses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.logistik', 2)->where('stat.logistik', 2)->whereIn('stat.validasi', [0, 1])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.logistik', 2)->where('stat.logistik', 2)->whereIn('stat.validasi', [0, 1])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLogistikValid(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.logistik', 2)->where('stat.validasi', 1)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.logistik', 2)->where('stat.validasi', 1)->where('com.status', 16)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLogistikSelesai(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.logistik', 2)->where('stat.validasi', 2)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_logistik = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.logistik as stat_logistik", "stat.validasi as stat_validasi", "divi.logistik as div_logistik")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.logistik', 2)->where('stat.validasi', 2)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_logistik)->addColumn('action', 'button/action_button_complaint_logistik')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintSalesSemua(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                // $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [3, 6, 8, 10, 11, 13, 14, 15, 18, 21, 23, 25, 26, 28, 30, 32, 33, 34, 35, 36, 37, 38, 39, 40, 43, 45, 46, 47, 49, 51, 52, 53, 54, 55, 58, 59, 60, 61, 62, 63, 66, 67, 68, 69, 70, 71, 72, 73, 75, 76, 77, 78, 79, 80])->whereIn('com.status', [1, 2, 3, 5, 6, 8, 10, 13, 17])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.sales', [1, 2])->whereIn('stat.sales', [0, 2])->whereIn('stat.validasi', [0, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                // $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [3, 6, 8, 10, 11, 13, 14, 15, 18, 21, 23, 25, 26, 28, 30, 32, 33, 34, 35, 36, 37, 38, 39, 40, 43, 45, 46, 47, 49, 51, 52, 53, 54, 55, 58, 59, 60, 61, 62, 63, 66, 67, 68, 69, 70, 71, 72, 73, 75, 76, 77, 78, 79, 80])->whereIn('com.status', [1, 2, 3, 5, 6, 8, 10, 13, 17])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->get();
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.sales', [1, 2])->whereIn('stat.sales', [0, 2])->whereIn('stat.validasi', [0, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_sales)->addColumn('action', 'button/action_button_complaint_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintSalesSemuaAdmin(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.sales', [1, 2])->whereIn('stat.sales', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.sales', [1, 2])->whereIn('stat.sales', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_sales)->addColumn('action', 'button/action_button_complaint_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintSalesProses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.sales', [1, 2])->where('stat.sales', 0)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.sales', [1, 2])->where('stat.sales', 0)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_sales)->addColumn('action', 'button/action_button_complaint_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintSalesDiproses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.sales', 2)->where('stat.sales', 2)->whereIn('stat.validasi', [0, 1])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.sales', 2)->where('stat.sales', 2)->whereIn('stat.validasi', [0, 1])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_sales)->addColumn('action', 'button/action_button_complaint_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintSalesValid(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.sales', 2)->where('stat.validasi', 1)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.sales', 2)->where('stat.validasi', 1)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_sales)->addColumn('action', 'button/action_button_complaint_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintSalesSelesai(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.sales', 2)->where('stat.validasi', 2)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_sales = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.sales as stat_sales", "stat.validasi as stat_validasi", "divi.sales as div_sales")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.sales', 2)->where('stat.validasi', 2)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_sales)->addColumn('action', 'button/action_button_complaint_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLainnyaSemua(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                // $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [4, 7, 9, 10, 12, 13, 14, 15, 19, 22, 24, 25, 26, 29, 31, 32, 33, 36, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 50, 51, 54, 55, 56, 57, 58, 59, 62, 63, 65, 66, 67, 68, 69, 70, 71, 73, 74, 75, 76, 77, 78, 79, 80])->whereIn('com.status', [1, 2, 3, 4, 6, 7, 9, 12, 17])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lainnya', [1, 2])->whereIn('stat.lainnya', [0, 2])->whereIn('stat.validasi', [0, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                // $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", "divi.name as divisi", "stat.name as status", "com.lampiran as lampiran", "com.divisi as no_divisi", "com.custid as custid", "com.status as no_status")->join("divisi_complaint as divi", "divi.id", "=", "com.divisi")->join("status_complaint as stat", "stat.id", "=", "com.status")->whereIn('com.divisi', [4, 7, 9, 10, 12, 13, 14, 15, 19, 22, 24, 25, 26, 29, 31, 32, 33, 36, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 50, 51, 54, 55, 56, 57, 58, 59, 62, 63, 65, 66, 67, 68, 69, 70, 71, 73, 74, 75, 76, 77, 78, 79, 80])->whereIn('com.status', [1, 2, 3, 4, 6, 7, 9, 12, 17])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->get();
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lainnya', [1, 2])->whereIn('stat.lainnya', [0, 2])->whereIn('stat.validasi', [0, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLainnyaSemuaAdmin(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lainnya', [1, 2])->whereIn('stat.lainnya', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereIn('com.status', [1, 2, 3, 4, 6, 7, 9, 12, 16, 17])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lainnya', [1, 2])->whereIn('stat.lainnya', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLainnyaProses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lainnya', [1, 2])->where('stat.lainnya', 0)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lainnya', [1, 2])->where('stat.lainnya', 0)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLainnyaDiproses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lainnya', 2)->where('stat.lainnya', 2)->whereIn('stat.validasi', [0, 1])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lainnya', 2)->where('stat.lainnya', 2)->whereIn('stat.validasi', [0, 1])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLainnyaValid(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lainnya', 2)->where('stat.validasi', 1)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lainnya', 2)->where('stat.validasi', 1)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLainnyaSelesai(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lainnya', 2)->where('stat.validasi', 2)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lainnya = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lainnya as stat_lainnya", "stat.validasi as stat_validasi", "divi.lainnya as div_lainnya")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lainnya', 2)->where('stat.validasi', 2)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lainnya)->addColumn('action', 'button/action_button_complaint_lainnya')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintTimbanganSemua(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.timbangan', [1, 2])->whereIn('stat.timbangan', [0, 2])->whereIn('stat.validasi', [0, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.timbangan', [1, 2])->whereIn('stat.timbangan', [0, 2])->whereIn('stat.validasi', [0, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_timbangan)->addColumn('action', 'button/action_button_complaint_timbangan')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintTimbanganSemuaAdmin(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.timbangan', [1, 2])->whereIn('stat.timbangan', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.timbangan', [1, 2])->whereIn('stat.timbangan', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_timbangan)->addColumn('action', 'button/action_button_complaint_timbangan')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintTimbanganProses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.timbangan', [1, 2])->where('stat.timbangan', 0)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.timbangan', [1, 2])->where('stat.timbangan', 0)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_timbangan)->addColumn('action', 'button/action_button_complaint_timbangan')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintTimbanganDiproses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.timbangan', 2)->where('stat.timbangan', 2)->whereIn('stat.validasi', [0, 1])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.timbangan', 2)->where('stat.timbangan', 2)->whereIn('stat.validasi', [0, 1])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_timbangan)->addColumn('action', 'button/action_button_complaint_timbangan')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintTimbanganValid(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.timbangan', 2)->where('stat.validasi', 1)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.timbangan', 2)->where('stat.validasi', 1)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_timbangan)->addColumn('action', 'button/action_button_complaint_timbangan')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintTimbanganSelesai(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.timbangan', 2)->where('stat.validasi', 2)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_timbangan = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.timbangan as stat_timbangan", "stat.validasi as stat_validasi", "divi.timbangan as div_timbangan")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.timbangan', 2)->where('stat.validasi', 2)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_timbangan)->addColumn('action', 'button/action_button_complaint_timbangan')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintWarehouseSemua(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.warehouse', [1, 2])->whereIn('stat.warehouse', [0, 2])->whereIn('stat.validasi', [0, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.warehouse', [1, 2])->whereIn('stat.warehouse', [0, 2])->whereIn('stat.validasi', [0, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_warehouse)->addColumn('action', 'button/action_button_complaint_warehouse')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintWarehouseSemuaAdmin(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.warehouse', [1, 2])->whereIn('stat.warehouse', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.warehouse', [1, 2])->whereIn('stat.warehouse', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_warehouse)->addColumn('action', 'button/action_button_complaint_warehouse')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintWarehouseProses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.warehouse', [1, 2])->where('stat.warehouse', 0)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.warehouse', [1, 2])->where('stat.warehouse', 0)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_warehouse)->addColumn('action', 'button/action_button_complaint_warehouse')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintWarehouseDiproses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.warehouse', 2)->where('stat.warehouse', 2)->whereIn('stat.validasi', [0, 1])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.warehouse', 2)->where('stat.warehouse', 2)->whereIn('stat.validasi', [0, 1])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_warehouse)->addColumn('action', 'button/action_button_complaint_warehouse')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintWarehouseValid(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.warehouse', 2)->where('stat.validasi', 1)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.warehouse', 2)->where('stat.validasi', 1)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_warehouse)->addColumn('action', 'button/action_button_complaint_warehouse')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintWarehouseSelesai(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.warehouse', 2)->where('stat.validasi', 2)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_warehouse = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.warehouse as stat_warehouse", "stat.validasi as stat_validasi", "divi.warehouse as div_warehouse")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.warehouse', 2)->where('stat.validasi', 2)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_warehouse)->addColumn('action', 'button/action_button_complaint_warehouse')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLabSemua(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lab', [1, 2])->whereIn('stat.lab', [0, 2])->whereIn('stat.validasi', [0, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lab', [1, 2])->whereIn('stat.lab', [0, 2])->whereIn('stat.validasi', [0, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lab)->addColumn('action', 'button/action_button_complaint_lab')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLabSemuaAdmin(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lab', [1, 2])->whereIn('stat.lab', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lab', [1, 2])->whereIn('stat.lab', [0, 2])->whereIn('stat.validasi', [0, 1, 2])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lab)->addColumn('action', 'button/action_button_complaint_lab')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLabProses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lab', [1, 2])->where('stat.lab', 0)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->whereIn('divi.lab', [1, 2])->where('stat.lab', 0)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lab)->addColumn('action', 'button/action_button_complaint_lab')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLabDiproses(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lab', 2)->where('stat.lab', 2)->whereIn('stat.validasi', [0, 1])->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lab', 2)->where('stat.lab', 2)->whereIn('stat.validasi', [0, 1])->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lab)->addColumn('action', 'button/action_button_complaint_lab')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLabValid(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lab', 2)->where('stat.validasi', 1)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lab', 2)->where('stat.validasi', 1)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lab)->addColumn('action', 'button/action_button_complaint_lab')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function indexComplaintLabSelesai(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lab', 2)->where('stat.validasi', 2)->whereBetween('com.tanggal_complaint', array($request->from_date, $request->to_date))->get();
            }else{
                $complaint_lab = DB::table('complaint_customer as com')->select("com.nomor_complaint as nomor_complaint", "com.tanggal_complaint as tanggal_complaint", "com.custid as custid", "com.nama_customer as nama_customer", "com.nomor_surat_jalan as nomor_surat_jalan", "com.complaint as complaint", DB::raw("concat(if(divi.produksi = 2, concat('Produksi; '), ''), if(divi.logistik = 2, concat('Logistik; '), ''), if(divi.sales = 2, concat('Sales; '), ''), if(divi.timbangan = 2, concat('Timbangan; '), ''), if(divi.warehouse = 2, concat('Warehouse; '), ''), if(divi.lab = 2, concat('Lab; '), ''), if(divi.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), "com.lampiran as lampiran", "stat.lab as stat_lab", "stat.validasi as stat_validasi", "divi.lab as div_lab")->join("divisi_complaint as divi", "divi.nomor_complaint", "=", "com.nomor_complaint")->join("status_complaint as stat", "stat.nomor_complaint", "=", "com.nomor_complaint")->where('divi.lab', 2)->where('stat.validasi', 2)->whereRaw('MONTH(com.tanggal_complaint) = ?',[$currentMonth])->whereRaw('YEAR(com.tanggal_complaint) = ?',[$currentYear])->get();
            }

            return datatables()->of($complaint_lab)->addColumn('action', 'button/action_button_complaint_lab')->rawColumns(['action'])->make(true);
        }
        return view('input_complaint');
    }

    public function showEditComplaint($no_complaint){ 

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_customer as co')->select('co.nomor_complaint', 'co.tanggal_complaint', 'co.custid', 'cus.custname', 'co.nomor_surat_jalan', 'co.tanggal_order', 'co.complaint', 'co.sales_order', 'co.supervisor_sales', 'co.pelapor', 'co.lampiran', 'co.quantity', 'co.jumlah_kg_sak', 'co.jenis_karung', 'co.jumlah_karung', 'co.berat_timbangan', 'co.unit_berat_timbangan', 'co.berat_aktual', 'co.unit_berat_aktual', 'co.tanggal_pengiriman', 'co.area', 'co.nama_supir', 'co.nama_kernet', 'co.no_kendaraan', 'co.supervisor', 'co.pengiriman', 'co.pengiriman_lain', 'co.jenis_kendaraan', 'co.jenis_kendaraan_lain', 'co.kuli1', 'co.kuli2', 'co.kuli3', 'co.stapel1', 'co.stapel2', 'co.stapel3', 'co.stapel4', 'co.stapel5')->join('customers as cus', 'cus.custid', '=', 'co.custid')->where('co.nomor_complaint', $val_no_complaint)->first();

        return Response()->json($data);
    }

    public function showEditComplaintProduksi($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_produksi')->select('nomor_complaint', 'evaluasi', 'solusi_internal', 'solusi_customer', 'lampiran')->where('nomor_complaint', $val_no_complaint)->first();

        $komitmen = DB::table('list_of_action_complaint')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $val_no_complaint)->where('divisi', 1)->get();

        return Response()->json(["data_complaint" => $data, "data_komitmen" => $komitmen]);
    }

    public function showEditComplaintLogistik($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_logistik')->select('nomor_complaint', 'evaluasi', 'solusi_internal', 'solusi_customer', 'lampiran')->where('nomor_complaint', $val_no_complaint)->first();

        $komitmen = DB::table('list_of_action_complaint')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $val_no_complaint)->where('divisi', 2)->get();

        return Response()->json(["data_complaint" => $data, "data_komitmen" => $komitmen]);
    }

    public function showEditComplaintSales($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_sales')->select('nomor_complaint', 'tanggal_customer_setuju', 'evaluasi', 'solusi_internal', 'solusi_customer', 'lampiran')->where('nomor_complaint', $val_no_complaint)->first();

        $komitmen = DB::table('list_of_action_complaint')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $val_no_complaint)->where('divisi', 3)->get();

        return Response()->json(["data_complaint" => $data, "data_komitmen" => $komitmen]);
    }

    public function showEditComplaintLainnya($no_complaint){  

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_lainnya')->select('nomor_complaint', 'divisi', 'evaluasi', 'solusi_internal', 'solusi_customer', 'lampiran')->where('nomor_complaint', $val_no_complaint)->first();

        $komitmen = DB::table('list_of_action_complaint')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $val_no_complaint)->where('divisi', 6)->get();

        return Response()->json(["data_complaint" => $data, "data_komitmen" => $komitmen]);
    }

    public function showEditComplaintTimbangan($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_timbangan')->select('nomor_complaint', 'evaluasi', 'solusi_internal', 'solusi_customer', 'lampiran')->where('nomor_complaint', $val_no_complaint)->first();

        $komitmen = DB::table('list_of_action_complaint')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $val_no_complaint)->where('divisi', 4)->get();

        return Response()->json(["data_complaint" => $data, "data_komitmen" => $komitmen]);
    }

    public function showEditComplaintWarehouse($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_warehouse')->select('nomor_complaint', 'evaluasi', 'solusi_internal', 'solusi_customer', 'lampiran')->where('nomor_complaint', $val_no_complaint)->first();

        $komitmen = DB::table('list_of_action_complaint')->select('id_user', 'nomor_complaint', 'divisi', 'selesai_tanggal_komitmen', 'komitmen')->where('nomor_complaint', $val_no_complaint)->where('divisi', 5)->get();

        return Response()->json(["data_complaint" => $data, "data_komitmen" => $komitmen]);
    }

    public function showEditComplaintLab($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_lab')->select('nomor_complaint', 'suggestion', 'lampiran')->where('nomor_complaint', $val_no_complaint)->first();

        return Response()->json($data);
    }

    public function editComplaint(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if ($request->hasFile('edit_upload_file')) {
            $data_foto = DB::table('complaint_customer')->select('lampiran')->where('nomor_complaint', $request->edit_nomor_complaint)->first();
            File::delete('data_file/' . $data_foto->lampiran);

            $file = $request->file('edit_upload_file');
            $nama_file = time()."_".$request->edit_custid."_".$request->edit_nomor_complaint."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data_cust = DB::table('customers')->select('custname')->where('custid', $request->edit_custid)->first();

            DB::table('data_complaint_produksi')->where('nomor_complaint', $request->nomor_complaint)->where('custid', '!=', $request->edit_custid)->delete();

            $data = DB::table('complaint_customer')->where('nomor_complaint', $request->edit_nomor_complaint)->update(['nomor_surat_jalan' => $request->edit_nomor_surat_jalan, 'tanggal_order' => $request->edit_tanggal_order, 'complaint' => $request->edit_txt_complaint, 'sales_order' => $request->edit_sales_order, 'supervisor_sales' => $request->edit_supervisor_sls, 'pelapor' => $request->edit_pelapor, 'custid' => $request->edit_custid, 'nama_customer' => $data_cust->custname, 'lampiran' => $nama_file, 'quantity' => $request->edit_quantity, 'jumlah_kg_sak' => $request->edit_jumlah_kg_sak, 'jenis_karung' => $request->edit_jenis_karung, 'berat_timbangan' => $request->edit_berat_timbangan, 'unit_berat_timbangan' => $request->edit_unit_berat_timbangan, 'berat_aktual' => $request->edit_berat_aktual, 'unit_berat_aktual' => $request->edit_input_unit_berat_aktual, 'jumlah_karung' => $request->edit_jumlah_karung, 'tanggal_pengiriman' => $request->edit_tanggal_pengiriman, 'area' => $request->edit_area_log, 'nama_supir' => $request->edit_nama_supir, 'nama_kernet' => $request->edit_nama_kernet, 'no_kendaraan' => $request->edit_no_kendaraan, 'supervisor' => $request->edit_supervisor_log, 'pengiriman' => $request->edit_pengiriman, 'pengiriman_lain' => $request->edit_pengiriman_lain, 'jenis_kendaraan' => $request->edit_jenis_kendaraan, 'jenis_kendaraan_lain' => $request->edit_jenis_kendaraan_lain, 'kuli1' => null, 'kuli2' => null, 'kuli3' => null, 'stapel1' => null, 'stapel2' => null, 'stapel3' => null, 'stapel4' => null, 'stapel5' => null]);

            $number_kuli = count($request->edit_nama_kuli);

            for($i=0; $i<$number_kuli; $i++){
                DB::table('complaint_customer')->where('nomor_complaint', $request->edit_nomor_complaint)->update(['kuli'.($i+1) => $request->edit_nama_kuli[$i]]);
            }

            $number_stapel = count($request->edit_nama_stapel);

            for($i=0; $i<$number_stapel; $i++){
                DB::table('complaint_customer')->where('nomor_complaint', $request->edit_nomor_complaint)->update(['stapel'.($i+1) => $request->edit_nama_stapel[$i]]);
            }

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }else{
            $data_cust = DB::table('customers')->select('custname')->where('custid', $request->edit_custid)->first();

            DB::table('data_complaint_produksi')->where('nomor_complaint', $request->nomor_complaint)->where('custid', '!=', $request->edit_custid)->delete();

            $data = DB::table('complaint_customer')->where('nomor_complaint', $request->edit_nomor_complaint)->update(['nomor_surat_jalan' => $request->edit_nomor_surat_jalan, 'tanggal_order' => $request->edit_tanggal_order, 'complaint' => $request->edit_txt_complaint, 'sales_order' => $request->edit_sales_order, 'supervisor_sales' => $request->edit_supervisor_sls, 'pelapor' => $request->edit_pelapor, 'custid' => $request->edit_custid, 'nama_customer' => $data_cust->custname, 'quantity' => $request->edit_quantity, 'jumlah_kg_sak' => $request->edit_jumlah_kg_sak, 'jenis_karung' => $request->edit_jenis_karung, 'berat_timbangan' => $request->edit_berat_timbangan, 'unit_berat_timbangan' => $request->edit_unit_berat_timbangan, 'berat_aktual' => $request->edit_berat_aktual, 'unit_berat_aktual' => $request->edit_input_unit_berat_aktual, 'jumlah_karung' => $request->edit_jumlah_karung, 'tanggal_pengiriman' => $request->edit_tanggal_pengiriman, 'area' => $request->edit_area_log, 'nama_supir' => $request->edit_nama_supir, 'nama_kernet' => $request->edit_nama_kernet, 'no_kendaraan' => $request->edit_no_kendaraan, 'supervisor' => $request->edit_supervisor_log, 'pengiriman' => $request->edit_pengiriman, 'pengiriman_lain' => $request->edit_pengiriman_lain, 'jenis_kendaraan' => $request->edit_jenis_kendaraan, 'jenis_kendaraan_lain' => $request->edit_jenis_kendaraan_lain, 'kuli1' => null, 'kuli2' => null, 'kuli3' => null, 'stapel1' => null, 'stapel2' => null, 'stapel3' => null, 'stapel4' => null, 'stapel5' => null]);

            $number_kuli = count($request->edit_nama_kuli);

            for($i=0; $i<$number_kuli; $i++){
                DB::table('complaint_customer')->where('nomor_complaint', $request->edit_nomor_complaint)->update(['kuli'.($i+1) => $request->edit_nama_kuli[$i]]);
            }

            $number_stapel = count($request->edit_nama_stapel);

            for($i=0; $i<$number_stapel; $i++){
                DB::table('complaint_customer')->where('nomor_complaint', $request->edit_nomor_complaint)->update(['stapel'.($i+1) => $request->edit_nama_stapel[$i]]);
            }


            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->edit_nomor_complaint, 'divisi' => 3, 'action' => 'Divisi Sales Mengedit Data Complaint Customer Nomor ' . $request->edit_nomor_complaint]);
        ModelComplaintCust::where('nomor_complaint',  $request->edit_nomor_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function editComplaintProduksi(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if ($request->hasFile('edit_upload_file_prd')) {
            $data_foto = DB::table('complaint_produksi')->select('lampiran')->where('nomor_complaint', $request->edit_nomor_complaint_prd)->first();
            File::delete('data_file/' . $data_foto->lampiran);

            $file = $request->file('edit_upload_file_prd');
            $nama_file = time()."_".$request->edit_custid_prd."_".$request->edit_nomor_complaint_prd."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('complaint_produksi')->where('nomor_complaint', $request->edit_nomor_complaint_prd)->update(['evaluasi' => $request->edit_evaluasi_prd, 'solusi_internal' => $request->edit_solusi_internal_prd, 'solusi_customer' => $request->edit_solusi_customer_prd, 'lampiran' => $nama_file]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }else{
            $data = DB::table('complaint_produksi')->where('nomor_complaint', $request->edit_nomor_complaint_prd)->update(['evaluasi' => $request->edit_evaluasi_prd, 'solusi_internal' => $request->edit_solusi_internal_prd, 'solusi_customer' => $request->edit_solusi_customer_prd]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->edit_nomor_complaint_prd, 'divisi' => 1, 'action' => 'Divisi Produksi Mengedit Data pada Complaint Nomor ' . $request->edit_nomor_complaint_prd]);
        ModelComplaintCust::where('nomor_complaint',  $request->edit_nomor_complaint_prd)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function editComplaintLogistik(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        
        if ($request->hasFile('edit_upload_file_log')) {
            $data_foto = DB::table('complaint_logistik')->select('lampiran')->where('nomor_complaint', $request->edit_nomor_complaint_log)->first();
            File::delete('data_file/' . $data_foto->lampiran);

            $file = $request->file('edit_upload_file_log');
            $nama_file = time()."_".$request->edit_custid_log."_".$request->edit_nomor_complaint_log."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('complaint_logistik')->where('nomor_complaint', $request->edit_nomor_complaint_log)->update(['evaluasi' => $request->edit_evaluasi_log, 'solusi_internal' => $request->edit_solusi_internal_log, 'solusi_customer' => $request->edit_solusi_customer_log, 'lampiran' => $nama_file]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }else{
            $data = DB::table('complaint_logistik')->where('nomor_complaint', $request->edit_nomor_complaint_log)->update(['evaluasi' => $request->edit_evaluasi_log, 'solusi_internal' => $request->edit_solusi_internal_log, 'solusi_customer' => $request->edit_solusi_customer_log]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->edit_nomor_complaint_log, 'divisi' => 2, 'action' => 'Divisi Logistik Mengedit Data pada Complaint Nomor ' . $request->edit_nomor_complaint_log]);
        ModelComplaintCust::where('nomor_complaint',  $request->edit_nomor_complaint_log)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function editComplaintSales(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if ($request->hasFile('edit_upload_file_sls')) {
            $data_foto = DB::table('complaint_sales')->select('lampiran')->where('nomor_complaint', $request->edit_nomor_complaint_sls)->first();
            File::delete('data_file/' . $data_foto->lampiran);

            $file = $request->file('edit_upload_file_sls');
            $nama_file = time()."_".$request->edit_custid_sls."_".$request->edit_nomor_complaint_sls."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('complaint_sales')->where('nomor_complaint', $request->edit_nomor_complaint_sls)->update(['tanggal_customer_setuju' => $request->edit_tanggal_customer, 'evaluasi' => $request->edit_evaluasi_sls, 'solusi_internal' => $request->edit_solusi_internal_sls, 'solusi_customer' => $request->edit_solusi_customer_sls, 'lampiran' => $nama_file]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }else{
            $data = DB::table('complaint_sales')->where('nomor_complaint', $request->edit_nomor_complaint_sls)->update(['tanggal_customer_setuju' => $request->edit_tanggal_customer, 'evaluasi' => $request->edit_evaluasi_sls, 'solusi_internal' => $request->edit_solusi_internal_sls, 'solusi_customer' => $request->edit_solusi_customer_sls]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->edit_nomor_complaint_sls, 'divisi' => 3, 'action' => 'Divisi Sales Mengedit Data pada Complaint Nomor ' . $request->edit_nomor_complaint_sls]);
        ModelComplaintCust::where('nomor_complaint',  $request->edit_nomor_complaint_sls)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function editComplaintLainnya(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if ($request->hasFile('edit_upload_file_lny')) {
            $data_foto = DB::table('complaint_lainnya')->select('lampiran')->where('nomor_complaint', $request->edit_nomor_complaint_lny)->first();
            File::delete('data_file/' . $data_foto->lampiran);

            $file = $request->file('edit_upload_file_lny');
            $nama_file = time()."_".$request->edit_custid_lny."_".$request->edit_nomor_complaint_lny."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('complaint_lainnya')->where('nomor_complaint', $request->edit_nomor_complaint_lny)->update(['divisi' => $request->edit_divisi_lny, 'evaluasi' => $request->edit_evaluasi_lny, 'solusi_internal' => $request->edit_solusi_internal_lny, 'solusi_customer' => $request->edit_solusi_customer_lny, 'lampiran' => $nama_file]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }else{
            $data = DB::table('complaint_lainnya')->where('nomor_complaint', $request->edit_nomor_complaint_lny)->update(['divisi' => $request->edit_divisi_lny, 'evaluasi' => $request->edit_evaluasi_lny, 'solusi_internal' => $request->edit_solusi_internal_lny, 'solusi_customer' => $request->edit_solusi_customer_lny]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->edit_nomor_complaint_lny, 'divisi' => 7, 'action' => 'Divisi Lainnya Mengedit Data pada Complaint Nomor ' . $request->edit_nomor_complaint_lny]);
        ModelComplaintCust::where('nomor_complaint',  $request->edit_nomor_complaint_lny)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function editComplaintTimbangan(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        
        if ($request->hasFile('edit_upload_file_timbang')) {
            $data_foto = DB::table('complaint_timbangan')->select('lampiran')->where('nomor_complaint', $request->edit_nomor_complaint_timbang)->first();
            File::delete('data_file/' . $data_foto->lampiran);

            $file = $request->file('edit_upload_file_timbang');
            $nama_file = time()."_".$request->edit_custid_timbang."_".$request->edit_nomor_complaint_timbang."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('complaint_timbangan')->where('nomor_complaint', $request->edit_nomor_complaint_timbang)->update(['evaluasi' => $request->edit_evaluasi_timbang, 'solusi_internal' => $request->edit_solusi_internal_timbang, 'solusi_customer' => $request->edit_solusi_customer_timbang, 'lampiran' => $nama_file]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }else{
            $data = DB::table('complaint_timbangan')->where('nomor_complaint', $request->edit_nomor_complaint_timbang)->update(['evaluasi' => $request->edit_evaluasi_timbang, 'solusi_internal' => $request->edit_solusi_internal_timbang, 'solusi_customer' => $request->edit_solusi_customer_timbang]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->edit_nomor_complaint_timbang, 'divisi' => 4, 'action' => 'Divisi Timbangan Mengedit Data pada Complaint Nomor ' . $request->edit_nomor_complaint_timbang]);
        ModelComplaintCust::where('nomor_complaint',  $request->edit_nomor_complaint_timbang)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function editComplaintWarehouse(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        
        if ($request->hasFile('edit_upload_file_ware')) {
            $data_foto = DB::table('complaint_warehouse')->select('lampiran')->where('nomor_complaint', $request->edit_nomor_complaint_ware)->first();
            File::delete('data_file/' . $data_foto->lampiran);

            $file = $request->file('edit_upload_file_ware');
            $nama_file = time()."_".$request->edit_custid_ware."_".$request->edit_nomor_complaint_ware."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('complaint_warehouse')->where('nomor_complaint', $request->edit_nomor_complaint_ware)->update(['evaluasi' => $request->edit_evaluasi_ware, 'solusi_internal' => $request->edit_solusi_internal_ware, 'solusi_customer' => $request->edit_solusi_customer_ware, 'lampiran' => $nama_file]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }else{
            $data = DB::table('complaint_warehouse')->where('nomor_complaint', $request->edit_nomor_complaint_ware)->update(['evaluasi' => $request->edit_evaluasi_ware, 'solusi_internal' => $request->edit_solusi_internal_ware, 'solusi_customer' => $request->edit_solusi_customer_ware]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->edit_nomor_complaint_ware, 'divisi' => 5, 'action' => 'Divisi Warehouse Mengedit Data pada Complaint Nomor ' . $request->edit_nomor_complaint_ware]);
        ModelComplaintCust::where('nomor_complaint',  $request->edit_nomor_complaint_ware)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function editComplaintLab(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if ($request->hasFile('edit_upload_file_lab')) {
            $data_foto = DB::table('complaint_lab')->select('lampiran')->where('nomor_complaint', $request->edit_nomor_complaint_lab)->first();
            File::delete('data_file/' . $data_foto->lampiran);

            $file = $request->file('edit_upload_file_lab');
            $nama_file = time()."_".$request->edit_custid_lab."_".$request->edit_nomor_complaint_lab."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('complaint_lab')->where('nomor_complaint', $request->edit_nomor_complaint_lab)->update(['suggestion' => $request->edit_suggestion, 'lampiran' => $nama_file]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }else{
            $data = DB::table('complaint_lab')->where('nomor_complaint', $request->edit_nomor_complaint_lab)->update(['suggestion' => $request->edit_suggestion]);

            if($data){
                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->edit_nomor_complaint_lab, 'divisi' => 6, 'action' => 'Divisi Lab Mengedit Data pada Complaint Nomor ' . $request->edit_nomor_complaint_lab]);
        ModelComplaintCust::where('nomor_complaint',  $request->edit_nomor_complaint_lab)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function viewListKomitmenProduksi(Request $request){
        $list_komitmen = DB::table('list_of_action_complaint as list')->select("list.nomor_complaint", "list.komitmen", "list.selesai_tanggal_komitmen", "stat.name as status")->join("status as stat", "stat.id", "=", "list.status")->where("list.id_user", Session::get('id_user_admin'))->where("list.divisi", 1)->orderBy("list.status", "ASC")->orderBy("list.selesai_tanggal_komitmen", "DESC")->get();

        return datatables()->of($list_komitmen)->addIndexColumn()->make(true);
    }

    public function viewListKomitmenLogistik(Request $request){
        $list_komitmen = DB::table('list_of_action_complaint as list')->select("list.nomor_complaint", "list.komitmen", "list.selesai_tanggal_komitmen", "stat.name as status")->join("status as stat", "stat.id", "=", "list.status")->where("list.id_user", Session::get('id_user_admin'))->where("list.divisi", 2)->orderBy("list.status", "ASC")->orderBy("list.selesai_tanggal_komitmen", "DESC")->get();

        return datatables()->of($list_komitmen)->addIndexColumn()->make(true);
        // return Response()->json();
    }

    public function viewListKomitmenSales(Request $request){
        $list_komitmen = DB::table('list_of_action_complaint as list')->select("list.nomor_complaint", "list.komitmen", "list.selesai_tanggal_komitmen", "stat.name as status")->join("status as stat", "stat.id", "=", "list.status")->where("id_user", Session::get('id_user_admin'))->where("divisi", 3)->orderBy("list.status", "ASC")->orderBy("list.selesai_tanggal_komitmen", "DESC")->get();

        return datatables()->of($list_komitmen)->addIndexColumn()->make(true);
        // return Response()->json($list_komitmen);
    }

    public function viewListKomitmenLainnya(Request $request){
        $list_komitmen = DB::table('list_of_action_complaint as list')->select("list.nomor_complaint", "list.komitmen", "list.selesai_tanggal_komitmen", "stat.name as status")->join("status as stat", "stat.id", "=", "list.status")->where("id_user", Session::get('id_user_admin'))->where("divisi", 7)->orderBy("list.status", "ASC")->orderBy("list.selesai_tanggal_komitmen", "DESC")->get();

        return datatables()->of($list_komitmen)->addIndexColumn()->make(true);
        // return Response()->json($list_komitmen);
    }

    public function viewListKomitmenTimbangan(Request $request){
        $list_komitmen = DB::table('list_of_action_complaint as list')->select("list.nomor_complaint", "list.komitmen", "list.selesai_tanggal_komitmen", "stat.name as status")->join("status as stat", "stat.id", "=", "list.status")->where("id_user", Session::get('id_user_admin'))->where("divisi", 4)->orderBy("list.status", "ASC")->orderBy("list.selesai_tanggal_komitmen", "DESC")->get();

        return datatables()->of($list_komitmen)->addIndexColumn()->make(true);
        // return Response()->json($list_komitmen);
    }

    public function viewListKomitmenWarehouse(Request $request){
        $list_komitmen = DB::table('list_of_action_complaint as list')->select("list.nomor_complaint", "list.komitmen", "list.selesai_tanggal_komitmen", "stat.name as status")->join("status as stat", "stat.id", "=", "list.status")->where("id_user", Session::get('id_user_admin'))->where("divisi", 5)->orderBy("list.status", "ASC")->orderBy("list.selesai_tanggal_komitmen", "DESC")->get();

        return datatables()->of($list_komitmen)->addIndexColumn()->make(true);
        // return Response()->json($list_komitmen);
    }

    public function viewListKomitmenLab(Request $request){
        $list_komitmen = DB::table('list_of_action_complaint as list')->select("list.nomor_complaint", "list.komitmen", "list.selesai_tanggal_komitmen", "stat.name as status")->join("status as stat", "stat.id", "=", "list.status")->where("id_user", Session::get('id_user_admin'))->where("divisi", 6)->orderBy("list.status", "ASC")->orderBy("list.selesai_tanggal_komitmen", "DESC")->get();

        return datatables()->of($list_komitmen)->addIndexColumn()->make(true);
        // return Response()->json($list_komitmen);
    }

    public function show_data_produksi(Request $request){
        $orders = DB::table('temp_no_lot as tmp')->select("tmp.no_lot", "tmp.tanggal_produksi", "tmp.custid", "tmp.mesin", "ar.name as area", "prd.nama_produk", "tmp.supervisor", "tmp.bermasalah")->join("tbl_area as ar", "ar.id", "=", "tmp.area")->join("products as prd", "prd.kode_produk", "=", "tmp.kode_produk")->where('tmp.custid', $request->custid)->get();

        return datatables()->of($orders)->addIndexColumn()->addColumn('action', 'button/action_button_complaint')->rawColumns(['action'])->make(true);
    }

    public function save_data_produksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_no_lot')->insert(["custid" => $request->get('custid'), "no_lot" => $request->get('no_lot'), "tanggal_produksi" => $request->get('tanggal_produksi'), "kode_produk" => $request->get('kode_produk'), "mesin" => $request->get('mesin'), "area" => $request->get('area'), "supervisor" => $request->get('supervisor'), "bermasalah" => $request->get('bermasalah')]);

        $number = count($request->get('petugas'));

        for($i=0; $i<$number; $i++){
            $data_petugas = DB::table('temp_no_lot')->where('custid', $request->get('custid'))->where('no_lot', $request->get('no_lot'))->update(["petugas".($i+1) => $request->get('petugas')[$i]['value']]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($request->get('petugas'));
    }

    public function delete_data_produksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('temp_no_lot')->where('custid', $request->get('custid'))->where('no_lot', $request->get('no_lot'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function edit_show_data_produksi(Request $request){
        $orders = DB::table('data_complaint_produksi as tmp')->select("tmp.nomor_complaint", "tmp.no_lot", "tmp.tanggal_produksi", "prd.nama_produk", "tmp.custid", "tmp.mesin", "ar.name as area", "tmp.supervisor", "tmp.bermasalah")->join("tbl_area as ar", "ar.id", "=", "tmp.area")->join("products as prd", "prd.kode_produk", "=", "tmp.kode_produk")->where('tmp.nomor_complaint', $request->no_complaint)->get();

        return datatables()->of($orders)->addIndexColumn()->addColumn('action', 'button/action_button_edit_complaint')->rawColumns(['action'])->make(true);
    }

    public function edit_save_data_produksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('data_complaint_produksi')->insert(["nomor_complaint" => $request->get('edit_nomor_complaint'), "custid" => $request->get('edit_custid'), "no_lot" => $request->get('edit_no_lot'), "tanggal_produksi" => $request->get('edit_tanggal_produksi'), "kode_produk" => $request->get('kode_produk'), "mesin" => $request->get('edit_mesin'), "area" => $request->get('edit_area'), "supervisor" => $request->get('edit_supervisor'), "bermasalah" => $request->get('edit_bermasalah')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function edit_delete_data_produksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('data_complaint_produksi')->where('nomor_complaint', $request->get('no_complaint'))->where('no_lot', $request->get('no_lot'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function dataProduksiShowEdit($no_complaint, $no_lot){

        $val_no_complaint = $this->decrypt($no_complaint);
        $val_no_lot = $this->decrypt($no_lot);

        $data = DB::table('data_complaint_produksi')->select('nomor_complaint', 'tanggal_produksi', 'custid', 'no_lot', 'kode_produk', 'area', 'mesin', 'petugas1', 'petugas2', 'petugas3', 'petugas4', 'petugas5', 'supervisor', 'bermasalah')->where('nomor_complaint', $val_no_complaint)->where('no_lot', $val_no_lot)->first();

        return Response()->json($data);
    }

    public function save_data_produksi_edit(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('data_complaint_produksi')->insert(["nomor_complaint" => $request->get('nomor_complaint'), "custid" => $request->get('custid'), "no_lot" => $request->get('no_lot'), "tanggal_produksi" => $request->get('tanggal_produksi'), "kode_produk" => $request->get('kode_produk'), "mesin" => $request->get('mesin'), "area" => $request->get('area'), "supervisor" => $request->get('supervisor'), "bermasalah" => $request->get('bermasalah')]);

        $number = count($request->get('petugas'));

        for($i=0; $i<$number; $i++){
            $data_petugas = DB::table('data_complaint_produksi')->where('nomor_complaint', $request->get('nomor_complaint'))->where('no_lot', $request->get('no_lot'))->update(["petugas".($i+1) => $request->get('petugas')[$i]['value']]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function save_edit_data_produksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('data_complaint_produksi')->where("nomor_complaint", $request->get('nomor_complaint'))->where("no_lot", $request->get("no_lot_lama"))->update(["no_lot" => $request->get('no_lot'), "tanggal_produksi" => $request->get('tanggal_produksi'), "kode_produk" => $request->get('kode_produk'), "mesin" => $request->get('mesin'), "area" => $request->get('area'), "supervisor" => $request->get('supervisor'), "bermasalah" => $request->get('bermasalah'), "petugas1" => null, "petugas2" => null, "petugas3" => null, "petugas4" => null, "petugas5" => null]);

        $number = count($request->get('petugas'));

        for($i=0; $i<$number; $i++){
            $data_petugas = DB::table('data_complaint_produksi')->where('nomor_complaint', $request->get('nomor_complaint'))->where('no_lot', $request->get('no_lot'))->update(["petugas".($i+1) => $request->get('petugas')[$i]['value']]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function tempListKomitmenProduksi(Request $request){
        $komitmen = DB::table('temp_list_action as tmp')->select("tmp.id_user", "tmp.nomor_complaint", "tmp.selesai_tanggal_komitmen", "tmp.komitmen", "tmp.divisi")->where('tmp.nomor_complaint', $request->no_complaint)->where('tmp.divisi', 1)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function tempListKomitmenLogistik(Request $request){
        $komitmen = DB::table('temp_list_action as tmp')->select("tmp.id_user", "tmp.nomor_complaint", "tmp.selesai_tanggal_komitmen", "tmp.komitmen", "tmp.divisi")->where('tmp.nomor_complaint', $request->no_complaint)->where('tmp.divisi', 2)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function tempListKomitmenSales(Request $request){
        $komitmen = DB::table('temp_list_action as tmp')->select("tmp.id_user", "tmp.nomor_complaint", "tmp.selesai_tanggal_komitmen", "tmp.komitmen", "tmp.divisi")->where('tmp.nomor_complaint', $request->no_complaint)->where('tmp.divisi', 3)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function tempListKomitmenTimbangan(Request $request){
        $komitmen = DB::table('temp_list_action as tmp')->select("tmp.id_user", "tmp.nomor_complaint", "tmp.selesai_tanggal_komitmen", "tmp.komitmen", "tmp.divisi")->where('tmp.nomor_complaint', $request->no_complaint)->where('tmp.divisi', 4)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function tempListKomitmenWarehouse(Request $request){
        $komitmen = DB::table('temp_list_action as tmp')->select("tmp.id_user", "tmp.nomor_complaint", "tmp.selesai_tanggal_komitmen", "tmp.komitmen", "tmp.divisi")->where('tmp.nomor_complaint', $request->no_complaint)->where('tmp.divisi', 5)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function tempListKomitmenLainnya(Request $request){
        $komitmen = DB::table('temp_list_action as tmp')->select("tmp.id_user", "tmp.nomor_complaint", "tmp.selesai_tanggal_komitmen", "tmp.komitmen", "tmp.divisi")->where('tmp.nomor_complaint', $request->no_complaint)->where('tmp.divisi', 6)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function saveListKomitmenProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_list_action')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 1, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveListKomitmenLogistik(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_list_action')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 2, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveListKomitmenSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_list_action')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 3, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveListKomitmenTimbangan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_list_action')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 4, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveListKomitmenWarehouse(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_list_action')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 5, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveListKomitmenLainnya(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_list_action')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 6, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteListKomitmen(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('temp_list_action')->where('nomor_complaint', $request->get('nomor_complaint'))->where('divisi', $request->get('divisi'))->where('selesai_tanggal_komitmen', $request->get('selesai_tanggal_komitmen'))->where('komitmen', $request->get('komitmen'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function editListKomitmenProduksi(Request $request){
        $komitmen = DB::table('list_of_action_complaint')->select("id_user", "nomor_complaint", "selesai_tanggal_komitmen", "komitmen", "divisi")->where('nomor_complaint', $request->no_complaint)->where('divisi', 1)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_edit_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function editListKomitmenLogistik(Request $request){
        $komitmen = DB::table('list_of_action_complaint')->select("id_user", "nomor_complaint", "selesai_tanggal_komitmen", "komitmen", "divisi")->where('nomor_complaint', $request->no_complaint)->where('divisi', 2)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_edit_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function editListKomitmenSales(Request $request){
        $komitmen = DB::table('list_of_action_complaint')->select("id_user", "nomor_complaint", "selesai_tanggal_komitmen", "komitmen", "divisi")->where('nomor_complaint', $request->no_complaint)->where('divisi', 3)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_edit_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function editListKomitmenTimbang(Request $request){
        $komitmen = DB::table('list_of_action_complaint')->select("id_user", "nomor_complaint", "selesai_tanggal_komitmen", "komitmen", "divisi")->where('nomor_complaint', $request->no_complaint)->where('divisi', 4)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_edit_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function editListKomitmenWarehouse(Request $request){
        $komitmen = DB::table('list_of_action_complaint')->select("id_user", "nomor_complaint", "selesai_tanggal_komitmen", "komitmen", "divisi")->where('nomor_complaint', $request->no_complaint)->where('divisi', 5)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_edit_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function editListKomitmenLainnya(Request $request){
        $komitmen = DB::table('list_of_action_complaint')->select("id_user", "nomor_complaint", "selesai_tanggal_komitmen", "komitmen", "divisi")->where('nomor_complaint', $request->no_complaint)->where('divisi', 6)->get();

        return datatables()->of($komitmen)->addIndexColumn()->addColumn('action', 'button/action_button_edit_list_komitmen')->rawColumns(['action'])->make(true);
    }

    public function saveNewEditListKomitmenProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 1, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen'), "status" => 1]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveNewEditListKomitmenLogistik(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 2, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen'), "status" => 1]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveNewEditListKomitmenSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 3, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen'), "status" => 1]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveNewEditListKomitmenTimbangan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 4, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen'), "status" => 1]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveNewEditListKomitmenWarehouse(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 5, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen'), "status" => 1]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function saveNewEditListKomitmenLainnya(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->insert(["id_user" => Session::get('id_user_admin'), "nomor_complaint" => $request->get('nomor_complaint'), "divisi" => 6, "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen'), "komitmen" => $request->get('komitmen'), "status" => 1]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function showEditKomitmen($no_complaint, $divisi, $selesai_tanggal_komitmen, $komitmen){

        $val_no_complaint = $this->decrypt($no_complaint);
        $val_divisi = $this->decrypt($divisi);
        $val_selesai_tanggal_komitmen = $this->decrypt($selesai_tanggal_komitmen);
        $val_komitmen = $this->decrypt($komitmen);

        $data = DB::table('list_of_action_complaint')->select('nomor_complaint', 'id_user', 'selesai_tanggal_komitmen', 'komitmen', 'divisi', 'status')->where('nomor_complaint', $val_no_complaint)->where('divisi', $val_divisi)->where('selesai_tanggal_komitmen', $val_selesai_tanggal_komitmen)->where('komitmen', $val_komitmen)->first();

        return Response()->json($data);
    }

    public function deleteEditListKomitmen(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('list_of_action_complaint')->where('nomor_complaint', $request->get('nomor_complaint'))->where('divisi', $request->get('divisi'))->where('selesai_tanggal_komitmen', $request->get('selesai_tanggal_komitmen'))->where('komitmen', $request->get('komitmen'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function updateEditListKomitmenProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->where("nomor_complaint", $request->get('nomor_complaint'))->where("divisi", 1)->where('komitmen', $request->get('komitmen_lama'))->where('selesai_tanggal_komitmen', $request->get('selesai_tanggal_komitmen_lama'))->update(["komitmen" => $request->get('komitmen'), "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function updateEditListKomitmenLogistik(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->where("nomor_complaint", $request->get('nomor_complaint'))->where("divisi", 2)->where('komitmen', $request->get('komitmen_lama'))->where('selesai_tanggal_komitmen', $request->get('selesai_tanggal_komitmen_lama'))->update(["komitmen" => $request->get('komitmen'), "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function updateEditListKomitmenSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->where("nomor_complaint", $request->get('nomor_complaint'))->where("divisi", 3)->where('komitmen', $request->get('komitmen_lama'))->where('selesai_tanggal_komitmen', $request->get('selesai_tanggal_komitmen_lama'))->update(["komitmen" => $request->get('komitmen'), "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function updateEditListKomitmenTimbangan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->where("nomor_complaint", $request->get('nomor_complaint'))->where("divisi", 4)->where('komitmen', $request->get('komitmen_lama'))->where('selesai_tanggal_komitmen', $request->get('selesai_tanggal_komitmen_lama'))->update(["komitmen" => $request->get('komitmen'), "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function updateEditListKomitmenWarehouse(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->where("nomor_complaint", $request->get('nomor_complaint'))->where("divisi", 5)->where('komitmen', $request->get('komitmen_lama'))->where('selesai_tanggal_komitmen', $request->get('selesai_tanggal_komitmen_lama'))->update(["komitmen" => $request->get('komitmen'), "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function updateEditListKomitmenLainnya(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('list_of_action_complaint')->where("nomor_complaint", $request->get('nomor_complaint'))->where("divisi", 6)->where('komitmen', $request->get('komitmen_lama'))->where('selesai_tanggal_komitmen', $request->get('selesai_tanggal_komitmen_lama'))->update(["komitmen" => $request->get('komitmen'), "selesai_tanggal_komitmen" => $request->get('selesai_tanggal_komitmen')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function getDataQuality(Request $request){
        $orders = DB::table('tbl_quality_lab')->select('id', 'name')->get();

        return Response()->json($orders);
    }

    public function getNomorSample(Request $request){
        $data_lab_arr = DB::table('temp_data_lab')->select('nomor_sample_lab', 'no_lot', 'nomor_complaint', 'keterangan')->orderBy('nomor_sample_lab', 'asc')->distinct()->get();

        return Response()->json($data_lab_arr);
    }

    public function getNomorSampleEdit($nomor_complaint){
        $val_nomor_complaint = $this->decrypt($nomor_complaint);

        $data_lab_arr = DB::table('data_complaint_lab')->select('nomor_sample_lab', 'no_lot', 'nomor_complaint', 'keterangan')->where('nomor_complaint', $val_nomor_complaint)->orderBy('nomor_sample_lab', 'asc')->distinct()->get();

        return Response()->json($data_lab_arr);
    }

    public function show_data_lab(Request $request){
        $orders = DB::table('temp_data_lab as tmp')->select(DB::raw("concat('Sample ', tmp.nomor_sample_lab) as nomor_sample_lab"), "tmp.nomor_sample_lab as no_sample_lab", "tmp.nomor_complaint", "tmp.no_lot", "tmp.keterangan")->where('tmp.nomor_complaint', $request->no_complaint)->get();

        return datatables()->of($orders)->addColumn('action', 'button/action_button_data_lab')->rawColumns(['action'])->make(true);
    }

    public function show_data_quality_lab(Request $request){
        $orders = DB::table('temp_quality_detail_lab as tmp')->select(DB::raw("concat('Sample ', tmp.nomor_sample_lab) as nomor_sample_lab"), "tmp.nomor_sample_lab as no_sample_lab", "tmp.nomor_complaint", "tmp.no_lot", "qual.name as quality", "tmp.metode_mesin as metode", "tmp.hasil", "tmp.satuan", "tmp.quality_name_lainnya as lainnya", "tmp.quality_name")->join('tbl_quality_lab as qual', 'qual.id', '=', 'tmp.quality_name')->where('tmp.nomor_complaint', $request->no_complaint)->get();

        return datatables()->of($orders)->addIndexColumn()->addColumn('action', 'button/action_button_data_quality_lab')->rawColumns(['action'])->make(true);
    }

    public function save_data_lab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data_lab = DB::table('temp_data_lab')->select('nomor_sample_lab')->orderBy('nomor_sample_lab', 'asc')->distinct()->get();

        if($data_lab){
            $lab_count = $data_lab->count();
            $nomor_sample_lab = $lab_count + 1;
        }else{
            $nomor_sample_lab = 1;
        }

        $data = DB::table('temp_data_lab')->insert(["nomor_sample_lab" => $nomor_sample_lab, "no_lot" => $request->get('no_lot'), "nomor_complaint" => $request->get('nomor_complaint'), "keterangan" => $request->get('keterangan')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        $data_lab_arr = DB::table('temp_data_lab')->select('nomor_sample_lab', 'no_lot', 'nomor_complaint', 'keterangan')->orderBy('nomor_sample_lab', 'asc')->distinct()->get();

        return Response()->json($data_lab_arr);
    }

    public function save_data_quality_lab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    
        $data = DB::table('temp_quality_detail_lab')->insert(["nomor_sample_lab" => $request->get('nomor_sample_lab'), "no_lot" => $request->get('no_lot'), "nomor_complaint" => $request->get('nomor_complaint'), "metode_mesin" => $request->get('metode_mesin'), 'hasil' => $request->get('hasil'), 'satuan' => $request->get('satuan'), "quality_name" => $request->get('quality_name'), "quality_name_lainnya" => $request->get('quality_name_lainnya')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function delete_data_lab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('temp_data_lab')->where('nomor_sample_lab', $request->get('nomor_sample_lab'))->where('nomor_complaint', $request->get('nomor_complaint'))->where('no_lot', $request->get('no_lot'))->where('keterangan', $request->get('keterangan'))->delete();

        $validasi_quality = DB::table('temp_quality_detail_lab')->where('nomor_sample_lab', $request->get('nomor_sample_lab'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        $data_lab_arr = DB::table('temp_data_lab')->select('nomor_sample_lab', 'no_lot', 'nomor_complaint', 'keterangan')->orderBy('nomor_sample_lab', 'asc')->distinct()->get();

        return Response()->json($data_lab_arr);
    }

    public function delete_data_quality_lab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('temp_quality_detail_lab')->where('nomor_sample_lab', $request->get('nomor_sample_lab'))->where('nomor_complaint', $request->get('nomor_complaint'))->where('no_lot', $request->get('no_lot'))->where('quality_name', $request->get('quality_name'))->where('metode_mesin', $request->get('metode_mesin'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($validasi);
    }

    public function edit_show_data_lab(Request $request){
        $orders = DB::table('data_complaint_lab')->select('nomor_sample_lab', 'nomor_complaint', 'no_lot', 'keterangan')->where('nomor_complaint', $request->no_complaint)->get();

        return datatables()->of($orders)->addIndexColumn()->addColumn('action', 'button/action_button_edit_data_lab')->rawColumns(['action'])->make(true);
    }

    public function edit_show_data_quality_lab(Request $request){
        $orders = DB::table('data_complaint_quality_detail_lab')->select('nomor_quality_detail_lab', 'nomor_complaint', 'nomor_sample_lab', 'no_lot', 'qual.name as quality', 'quality_name_lainnya as lainnya', 'metode_mesin as metode', 'hasil', 'satuan')->join('tbl_quality_lab as qual', 'qual.id', '=', 'data_complaint_quality_detail_lab.quality_name')->where('nomor_complaint', $request->no_complaint)->get();

        return datatables()->of($orders)->addIndexColumn()->addColumn('action', 'button/action_button_edit_data_quality_lab')->rawColumns(['action'])->make(true);
    }

    public function save_edit_data_lab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('data_complaint_lab')->where("nomor_sample_lab", $request->get('nomor_sample_lab'))->update(["no_lot" => $request->get('no_lot'), "keterangan" => $request->get('keterangan')]);

        if($data){  
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function save_edit_data_quality_lab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('data_complaint_quality_detail_lab')->where("nomor_quality_detail_lab", $request->get('nomor_quality_detail_lab'))->update(["nomor_sample_lab" =>  $request->get('nomor_sample_lab'), "no_lot" => $request->get('no_lot'), "quality_name" => $request->get('quality_name'), "quality_name_lainnya" => $request->get('quality_name_lainnya'), "metode_mesin" => $request->get('metode_mesin'), "hasil" => $request->get('hasil'), "satuan" => $request->get('satuan')]);

        if($data){  
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function edit_delete_data_lab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('data_complaint_lab')->where('nomor_sample_lab', $request->get('nomor_sample_lab'))->delete();

        $validasi_quality = DB::table('data_complaint_quality_detail_lab')->where('nomor_sample_lab', $request->get('nomor_sample_lab'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        $data_lab_arr = DB::table('data_complaint_lab')->select('nomor_sample_lab', 'no_lot', 'nomor_complaint', 'keterangan')->where('nomor_complaint', $request->get('no_complaint'))->orderBy('nomor_sample_lab', 'asc')->distinct()->get();

        return Response()->json($data_lab_arr);
    }

    public function edit_delete_data_quality_lab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('data_complaint_quality_detail_lab')->where('nomor_quality_detail_lab', $request->get('nomor_quality_detail_lab'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function dataLabShowEdit($nomor_sample_lab){
        $val_nomor_sample_lab = $this->decrypt($nomor_sample_lab);

        $data = DB::table('data_complaint_lab')->select('nomor_complaint', 'nomor_sample_lab', 'no_lot', 'keterangan')->where('nomor_sample_lab', $val_nomor_sample_lab)->first();

        return Response()->json($data);
    }

    public function dataQualityLabShowEdit($nomor_quality_detail_lab){
        $val_nomor_quality_detail_lab = $this->decrypt($nomor_quality_detail_lab);

        $data = DB::table('data_complaint_quality_detail_lab')->select('nomor_quality_detail_lab', 'nomor_complaint', 'nomor_sample_lab', 'no_lot', 'quality_name', 'quality_name_lainnya', 'metode_mesin', 'hasil', 'satuan')->where('nomor_quality_detail_lab', $val_nomor_quality_detail_lab)->first();

        return Response()->json($data);
    }

    public function save_data_lab_edit(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $cek_data_lab = DB::table('data_complaint_lab')->select('nomor_sample_lab')->where('nomor_sample_lab', 'like', 'LAB' . $tanggal . '%')->orderBy('nomor_sample_lab', 'asc')->distinct()->get();

        if($cek_data_lab){
            $data_lab_count = $cek_data_lab->count();
            if($data_lab_count > 0){
                $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_sample_lab, 8);
                if($data_lab_count != $num){
                    $kode_quality = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_sample_lab;
                }else{
                    if($data_lab_count < 9){
                        $kode_quality = "LAB" . $tanggal . "-000" . ($data_lab_count + 1);
                    }else if($data_lab_count >= 9 && $data_lab_count < 99){
                        $kode_quality = "LAB" . $tanggal . "-00" . ($data_lab_count + 1);
                    }else if($data_lab_count >= 99 && $data_lab_count < 999){
                        $kode_quality = "LAB" . $tanggal . "-0" . ($data_lab_count + 1);
                    }else{
                        $kode_quality = "LAB-" . $tanggal . ($data_lab_count + 1);
                    }
                }
            }else{
                $kode_quality = "LAB" . $tanggal . "-0001";
            }
        }else{
            $kode_quality = "LAB" . $tanggal . "-0001";
        }

        $data = DB::table('data_complaint_lab')->insert(["nomor_sample_lab" => $kode_quality, "nomor_complaint" => $request->get('nomor_complaint'), "no_lot" => $request->get('no_lot'), "keterangan" => $request->get('keterangan')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        $data_lab_arr = DB::table('data_complaint_lab')->select('nomor_sample_lab', 'no_lot', 'nomor_complaint', 'keterangan')->where('nomor_complaint', $request->get('nomor_complaint'))->orderBy('nomor_sample_lab', 'asc')->distinct()->get();

        return Response()->json($data_lab_arr);
    }

    public function save_data_quality_lab_edit(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $cek_data_quality_lab = DB::table('data_complaint_quality_detail_lab')->select('nomor_quality_detail_lab')->where('nomor_quality_detail_lab', 'like', 'QLAB' . $tanggal . '%')->orderBy('nomor_quality_detail_lab', 'asc')->distinct()->get();

        if($cek_data_quality_lab){
            $data_quality_lab_count = $cek_data_quality_lab->count();
            if($data_quality_lab_count > 0){
                $num = (int) substr($cek_data_quality_lab[$cek_data_quality_lab->count() - 1]->nomor_quality_detail_lab, 9);
                if($data_quality_lab_count != $num){
                    $kode_quality_lab = ++$cek_data_quality_lab[$cek_data_quality_lab->count() - 1]->nomor_quality_detail_lab;
                }else{
                    if($data_quality_lab_count < 9){
                        $kode_quality_lab = "QLAB" . $tanggal . "-000" . ($data_quality_lab_count + 1);
                    }else if($data_quality_lab_count >= 9 && $data_quality_lab_count < 99){
                        $kode_quality_lab = "QLAB" . $tanggal . "-00" . ($data_quality_lab_count + 1);
                    }else if($data_quality_lab_count >= 99 && $data_quality_lab_count < 999){
                        $kode_quality_lab = "QLAB" . $tanggal . "-0" . ($data_quality_lab_count + 1);
                    }else{
                        $kode_quality_lab = "QLAB-" . $tanggal . ($data_quality_lab_count + 1);
                    }
                }
            }else{
                $kode_quality_lab = "QLAB" . $tanggal . "-0001";
            }
        }else{
            $kode_quality_lab = "QLAB" . $tanggal . "-0001";
        }

        $data = DB::table('data_complaint_quality_detail_lab')->insert(["nomor_quality_detail_lab" => $kode_quality_lab, "nomor_sample_lab" => $request->get('nomor_sample_lab'), "nomor_complaint" => $request->get('nomor_complaint'), "no_lot" => $request->get('no_lot'), "quality_name" => $request->get('quality_name'), "quality_name_lainnya" => $request->get('quality_name_lainnya'), "metode_mesin" => $request->get('metode_mesin'), "hasil" => $request->get('hasil'), "satuan" => $request->get('satuan')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }
}
