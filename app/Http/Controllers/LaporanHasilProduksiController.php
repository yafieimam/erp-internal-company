<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Exports\HasilLabExport;
use App\Exports\TrendInformasiMesinExport;
use App\Exports\HasilLabPeriodicSheetExport;
use App\Exports\TeknikMasalahMesinExport;
use App\Exports\LaporanTotalProduksiExport;
use App\Exports\LaporanRataProduksiExport;
use App\Exports\LaporanMasalahMesinSheetExport;
use Response;
use PDF;
use Excel;
use File;
use App\Imports\LaporanHasilProduksiImport;
use App\Imports\LaporanHasilLabImport;

class LaporanHasilProduksiController extends Controller
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
    
    public function viewPageLaporanProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('laporan_hasil_produksi');
        }
    }

    public function viewPageLaporanTotalProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('laporan_total_produksi');
        }
    }

    public function viewPageLaporanRataProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('laporan_rata_produksi');
        }
    }

    public function viewPageLaporanHasilLab(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('laporan_hasil_lab');
        }
    }

    public function viewPageTeknikProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('teknik_input_rpm');
        }
    }

    public function viewPageTeknikMasalahMesin(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('teknik_masalah_mesin');
        }
    }

    public function viewPageTeknikLaporanMasalahMesin(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('teknik_laporan_masalah_mesin');
        }
    }

    public function indexLaporanProduksi(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $laporan_produksi_table = DB::select("select lap1.nomor_laporan_produksi, lap1.tanggal_laporan_produksi, (select sum(det.jumlah_sak) from laporan_hasil_produksi as lap2 left join laporan_hasil_produksi_detail as det on det.nomor_laporan_produksi = lap2.nomor_laporan_produksi where lap2.tanggal_laporan_produksi = lap1.tanggal_laporan_produksi) as total_sak, (select sum(det.jumlah_tonase) / 1000 from laporan_hasil_produksi as lap2 left join laporan_hasil_produksi_detail as det on det.nomor_laporan_produksi = lap2.nomor_laporan_produksi where lap2.tanggal_laporan_produksi = lap1.tanggal_laporan_produksi) as total_tonase, (select count(distinct det.nomor_laporan_produksi) from laporan_hasil_produksi as lap2 left join laporan_hasil_produksi_detail as det on det.nomor_laporan_produksi = lap2.nomor_laporan_produksi where lap2.tanggal_laporan_produksi = lap1.tanggal_laporan_produksi) as jumlah_data, sum(case when mes.nomor_laporan_produksi_mesin is null then 0 else 1 end) as jumlah_masalah from laporan_hasil_produksi as lap1 left join laporan_hasil_produksi_mesin as mes on mes.nomor_laporan_produksi = lap1.nomor_laporan_produksi where (lap1.tanggal_laporan_produksi between ? and ?) group by lap1.tanggal_laporan_produksi", [$request->from_date, $request->to_date]);
            }else{
                $laporan_produksi_table = DB::select("select lap1.nomor_laporan_produksi, lap1.tanggal_laporan_produksi, (select sum(det.jumlah_sak) from laporan_hasil_produksi as lap2 left join laporan_hasil_produksi_detail as det on det.nomor_laporan_produksi = lap2.nomor_laporan_produksi where lap2.tanggal_laporan_produksi = lap1.tanggal_laporan_produksi) as total_sak, (select sum(det.jumlah_tonase) / 1000 from laporan_hasil_produksi as lap2 left join laporan_hasil_produksi_detail as det on det.nomor_laporan_produksi = lap2.nomor_laporan_produksi where lap2.tanggal_laporan_produksi = lap1.tanggal_laporan_produksi) as total_tonase, (select count(distinct det.nomor_laporan_produksi) from laporan_hasil_produksi as lap2 left join laporan_hasil_produksi_detail as det on det.nomor_laporan_produksi = lap2.nomor_laporan_produksi where lap2.tanggal_laporan_produksi = lap1.tanggal_laporan_produksi) as jumlah_data, sum(case when mes.nomor_laporan_produksi_mesin is null then 0 else 1 end) as jumlah_masalah from laporan_hasil_produksi as lap1 left join laporan_hasil_produksi_mesin as mes on mes.nomor_laporan_produksi = lap1.nomor_laporan_produksi where month(lap1.tanggal_laporan_produksi) = ? and year(lap1.tanggal_laporan_produksi) = ? group by lap1.tanggal_laporan_produksi", [$currentMonth, $currentYear]);
            }

            return datatables()->of($laporan_produksi_table)->addIndexColumn()->addColumn('action', 'button/action_button_laporan_hasil_produksi')->rawColumns(['action'])->make(true);
        }
        return view('laporan_hasil_produksi');
    }

    public function getWeight($kode_produk){
        $val_kode_produk = $this->decrypt($kode_produk);

        $data = DB::table('products')->select('weight')->where('jenis_produk', $val_kode_produk)->first();

        return Response()->json($data);
    }

    public function saveLaporanProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('laporan_hasil_produksi')->where("tanggal_laporan_produksi", $request->tanggal_laporan_produksi)->update(["updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        DB::table('laporan_hasil_produksi')->where("nomor_laporan_produksi", $request->nomor_laporan_produksi)->update(["referensi" => $request->referensi]);

        for($j = 1; $j <= count($data_mesin); $j++){
            $number = count($request->{"jenis_produk_" . $data_mesin[$j - 1]});

            for($i = 0; $i < $number; $i++){
                if(!empty($request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i])){
                    $cek_data_detail = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

                    if($cek_data_detail){
                        $data_detail_count = $cek_data_detail->count();
                        if($data_detail_count > 0){
                            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
                            if($data_detail_count != $num){
                                $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
                            }else{
                                if($data_detail_count < 9){
                                    $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
                                }else if($data_detail_count >= 9 && $data_detail_count < 99){
                                    $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
                                }else if($data_detail_count >= 99 && $data_detail_count < 999){
                                    $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
                                }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                                    $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
                                }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                                    $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
                                }else{
                                    $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
                                }
                            }
                        }else{
                            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
                        }
                    }else{
                        $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
                    }

                    $data = DB::table('laporan_hasil_produksi_detail')->insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $request->nomor_laporan_produksi, "mesin" => $j, "jenis_produk" => $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i], "jumlah_sak" => $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i], "jumlah_tonase" => $request->{"jumlah_tonase_" . $data_mesin[$j - 1]}[$i]]);

                    $data_cek = DB::table('products')->select('saldo')->where('jenis_produk', $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i])->first();
                    $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i])->where('tanggal', $request->tanggal_laporan_produksi)->first();

                    if($cek){
                        DB::table('inventaris_produksi')->where('jenis_produk', $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i])->where('tanggal', $request->tanggal_laporan_produksi)->update(['produksi' => ($cek->produksi + $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i]), 'saldo' => ($cek->saldo + $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);
                        DB::table('products')->where('jenis_produk', $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i])->update(['saldo' => ($cek->saldo + $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i] . ' = ' . $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i] . ' Sak. Total Saldo = ' . ($cek->saldo + $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i]) . ' Sak']);
                    }else{
                        DB::table('inventaris_produksi')->insert(['tanggal' => $request->tanggal_laporan_produksi, 'jenis_produk' => $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i], 'produksi' => $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);
                        DB::table('products')->where('jenis_produk', $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i])->update(['saldo' => ($data_cek->saldo + $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i] . ' = ' . $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i]) . ' Sak']);
                    }
                }
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Produksi Input Laporan Hasil Produksi Tanggal ' . $request->tanggal_laporan_produksi]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function viewDataLaporanProduksi($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $data = DB::table('laporan_hasil_produksi')->select('referensi')->where('tanggal_laporan_produksi', $val_tanggal)->first();

        return Response()->json($data);
    }

    public function viewLaporanProduksi($tanggal){
        $val_tanggal = $this->decrypt($tanggal);
        
        $sa = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 1)->get();

        $sb = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 2)->get();

        $mixer = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 3)->get();

        $ra = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 4)->get();

        $rb = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 5)->get();

        $rc = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 6)->get();

        $rd = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 7)->get();

        $re = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 8)->get();

        $rf = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 9)->get();

        $rg = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 10)->get();

        return Response()->json(['sa' => $sa, 'sb' => $sb, 'mixer' => $mixer, 'ra' => $ra, 'rb' => $rb, 'rc' => $rc, 'rd' => $rd, 're' => $re, 'rf' => $rf, 'rg' => $rg]);
    }

    public function editLaporanProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('laporan_hasil_produksi')->where("tanggal_laporan_produksi", $request->edit_tanggal_laporan_produksi)->update(["updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        DB::table('laporan_hasil_produksi')->where("nomor_laporan_produksi", $request->edit_nomor_laporan_produksi)->update(["referensi" => $request->edit_referensi]);

        for($j = 1; $j <= count($data_mesin); $j++){
            $number = count($request->{"edit_jenis_produk_" . $data_mesin[$j - 1]});

            for($i = 0; $i < $number; $i++){
                if(!empty($request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])){

                    $cek_data_exist = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi', $request->edit_nomor_laporan_produksi)->where('mesin', $j)->where('jenis_produk', $request->{"edit_jenis_produk_lama_" . $data_mesin[$j - 1]}[$i])->first();

                    if($cek_data_exist){
                        $data = DB::table('laporan_hasil_produksi_detail')->where('nomor_laporan_produksi', $request->edit_nomor_laporan_produksi)->where('mesin', $j)->where('jenis_produk', $request->{"edit_jenis_produk_lama_" . $data_mesin[$j - 1]}[$i])->update(["jenis_produk" => $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i], "jumlah_sak" => $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i], "jumlah_tonase" => $request->{"edit_jumlah_tonase_" . $data_mesin[$j - 1]}[$i]]);

                        $cek_lama = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $request->{"edit_jenis_produk_lama_" . $data_mesin[$j - 1]}[$i])->where('tanggal', $request->edit_tanggal_laporan_produksi)->first();

                        DB::table('inventaris_produksi')->where('jenis_produk', $request->{"edit_jenis_produk_lama_" . $data_mesin[$j - 1]}[$i])->where('tanggal', $request->edit_tanggal_laporan_produksi)->update(['produksi' => ($cek_lama->produksi - $request->{"edit_jumlah_sak_lama_" . $data_mesin[$j - 1]}[$i]), 'saldo' => ($cek_lama->saldo - $request->{"edit_jumlah_sak_lama_" . $data_mesin[$j - 1]}[$i])]);
                        DB::table('products')->where('jenis_produk', $request->{"edit_jenis_produk_lama_" . $data_mesin[$j - 1]}[$i])->update(['saldo' => ($cek_lama->saldo + $request->{"edit_jumlah_sak_lama_" . $data_mesin[$j - 1]}[$i])]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $request->{"edit_jenis_produk_lama_" . $data_mesin[$j - 1]}[$i] . ' = ' . $request->{"edit_jumlah_sak_lama_" . $data_mesin[$j - 1]}[$i] . ' Sak. Total Saldo = ' . ($cek_lama->saldo - $request->{"edit_jumlah_sak_lama_" . $data_mesin[$j - 1]}[$i]) . ' Sak']);

                        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])->where('tanggal', $request->edit_tanggal_laporan_produksi)->first();

                        DB::table('inventaris_produksi')->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])->where('tanggal', $request->edit_tanggal_laporan_produksi)->update(['produksi' => ($cek->produksi + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i]), 'saldo' => ($cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);
                        DB::table('products')->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])->update(['saldo' => ($cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i] . ' = ' . $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i] . ' Sak. Total Saldo = ' . ($cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i]) . ' Sak']);
                    }else{
                        $cek_data_detail = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

                        if($cek_data_detail){
                            $data_detail_count = $cek_data_detail->count();
                            if($data_detail_count > 0){
                                $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
                                if($data_detail_count != $num){
                                    $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
                                }else{
                                    if($data_detail_count < 9){
                                        $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
                                    }else if($data_detail_count >= 9 && $data_detail_count < 99){
                                        $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
                                    }else if($data_detail_count >= 99 && $data_detail_count < 999){
                                        $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
                                    }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                                        $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
                                    }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                                        $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
                                    }else{
                                        $nomor_laporan_produksi_detail = "LHPD-" . $tanggal . ($data_detail_count + 1);
                                    }
                                }
                            }else{
                                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
                            }
                        }else{
                            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
                        }

                        $data = DB::table('laporan_hasil_produksi_detail')->insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $request->edit_nomor_laporan_produksi, "mesin" => $j, "jenis_produk" => $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i], "jumlah_sak" => $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i], "jumlah_tonase" => $request->{"edit_jumlah_tonase_" . $data_mesin[$j - 1]}[$i]]);

                        $data_cek = DB::table('products')->select('saldo')->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])->first();
                        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])->where('tanggal', $request->edit_tanggal_laporan_produksi)->first();

                        if($cek){
                            DB::table('inventaris_produksi')->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])->where('tanggal', $request->edit_tanggal_laporan_produksi)->update(['produksi' => ($cek->produksi + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i]), 'saldo' => ($cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);
                            DB::table('products')->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])->update(['saldo' => ($cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i] . ' = ' . $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i] . ' Sak. Total Saldo = ' . ($cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i]) . ' Sak']);
                        }else{
                            DB::table('inventaris_produksi')->insert(['tanggal' => $request->edit_tanggal_laporan_produksi, 'jenis_produk' => $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i], 'produksi' => $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);
                            DB::table('products')->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])->update(['saldo' => ($data_cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i])]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i] . ' = ' . $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i]) . ' Sak']);
                        }
                    }
                }
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Produksi Edit Data Laporan Hasil Produksi Tanggal ' . $request->edit_tanggal_laporan_produksi]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteLaporanProduksiDetail(Request $request){
        $data = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi', 'jenis_produk', 'jumlah_sak')->where('nomor_laporan_produksi_detail', $request->get('nomor'))->first();

        $data_lap = DB::table('laporan_hasil_produksi')->select('tanggal_laporan_produksi')->where('nomor_laporan_produksi', $data->nomor_laporan_produksi)->first();

        $data_prd = DB::table('products')->select('saldo')->where('jenis_produk', $data->jenis_produk)->first();

        DB::table('products')->where('jenis_produk', $data->jenis_produk)->update(['saldo' => ($data_prd->saldo - $data->jumlah_sak)]);

        $cek = DB::table('inventaris_produksi')->select('produksi', 'saldo')->where('jenis_produk', $data->jenis_produk)->where('tanggal', $data_lap->tanggal_laporan_produksi)->first();

        DB::table('inventaris_produksi')->where('jenis_produk', $data->jenis_produk)->where('tanggal', $data_lap->tanggal_laporan_produksi)->update(['produksi' => ($cek->produksi - $data->jumlah_sak), 'saldo' => ($cek->saldo - $data->jumlah_sak)]);

        $hapus = DB::table('laporan_hasil_produksi_detail')->where('nomor_laporan_produksi_detail', $request->get('nomor'))->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Produksi Delete Produk ' . $data->jenis_produk . 'Pada Data Nomor ' . $data->nomor_laporan_produksi]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data->jenis_produk . ' = ' . $data->jumlah_sak . ' Sak. Total Saldo = ' . ($cek->saldo - $data->jumlah_sak) . ' Sak']);
    }

    public function detailLaporanProduksiMesin(Request $request){
        $laporan_produksi_detail = DB::select("select (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 1 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as sa_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 1 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as sa_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 2 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as sb_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 2 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as sb_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 3 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as mixer_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 3 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as mixer_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 4 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as ra_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 4 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as ra_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 5 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rb_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 5 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rb_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 6 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rc_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 6 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rc_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 7 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rd_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 7 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rd_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 8 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as re_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 8 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as re_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 9 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rf_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 9 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rf_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 10 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rg_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 10 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as rg_jumlah_tonase, (select sum(jumlah_sak) from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 11 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as coating_jumlah_sak, (select sum(jumlah_tonase) / 1000 from laporan_hasil_produksi_detail join laporan_hasil_produksi on laporan_hasil_produksi.nomor_laporan_produksi = laporan_hasil_produksi_detail.nomor_laporan_produksi where mesin = 11 and tanggal_laporan_produksi = laph.tanggal_laporan_produksi) as coating_jumlah_tonase from laporan_hasil_produksi_detail lap join laporan_hasil_produksi as laph on laph.nomor_laporan_produksi = lap.nomor_laporan_produksi where laph.tanggal_laporan_produksi = ?", [$request->tanggal]);
        
        return Response()->json($laporan_produksi_detail);
    }  

    public function detailLaporanProduksi($tanggal_laporan_produksi){
        $val_tanggal_laporan_produksi = $this->decrypt($tanggal_laporan_produksi);

        $aa40 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'AA40')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $aa25 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'AA25')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $aa20 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'AA20')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $bb40 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'BB40')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $cc50 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'CC50')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $dd50 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'DD50')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $ssf25 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'SSF25')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $sw30 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'SW30')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $sw40 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'SW40')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $sf30 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'SF30')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $ss30 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'SS30')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $sss30 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'SSS30')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $ac30 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'AC30')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $nl25 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'NL25')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $jaa = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'JAA')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $jsw = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'JSW')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $jpac = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'JPAC')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $polos40 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'POLOS40')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $kdcc = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'KDCC')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $dcb25 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'DCB25')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $dcd50 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'DCD50')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $dcd25 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'DCD25')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $dce50 = DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'DCE50')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $jdcd= DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'JDCD')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $jdd= DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'JDD')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $jbb= DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'JBB')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        $jss= DB::table('laporan_hasil_produksi_detail')->select('mesin', 'jumlah_sak')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('jenis_produk', 'JSS')->where('tanggal_laporan_produksi', $val_tanggal_laporan_produksi)->get();

        return Response()->json(['aa40' => $aa40, 'bb40' => $bb40, 'dcb25' => $dcb25, 'aa20' => $aa20, 'aa25' => $aa25, 'cc50' => $cc50, 'dd50' => $dd50, 'dcd50' => $dcd50, 'dcd25' => $dcd25, 'dce50' => $dce50, 'ssf25' => $ssf25, 'sw30' => $sw30, 'sw40' => $sw40, 'sf30' => $sf30, 'ss30' => $ss30, 'sss30' => $sss30, 'ac30' => $ac30, 'nl25' => $nl25, 'jaa' => $jaa, 'jsw' => $jsw, 'jpac' => $jpac, 'kdcc' => $kdcc, 'polos40' => $polos40, 'jdcd' => $jdcd, 'jdd' => $jdd, 'jbb' => $jbb, 'jss' => $jss]);
    }

    public function printLaporanProduksi($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::table('laporan_hasil_produksi')->select('nomor_laporan_produksi', 'tanggal_laporan_produksi', 'tanggal_input')->where('tanggal_laporan_produksi', $val_tanggal)->get();

        $sa = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 1)->get();

        $sb = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 2)->get();

        $mixer = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 3)->get();

        $ra = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 4)->get();

        $rb = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 5)->get();

        $rc = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 6)->get();

        $rd = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 7)->get();

        $re = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 8)->get();

        $rf = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 9)->get();

        $rg = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 10)->get();

        $coating = DB::table('laporan_hasil_produksi_detail')->select('nomor_laporan_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 11)->get();

        $pdf = PDF::loadView('print_laporan_hasil_produksi', ['data' => $data, 'sa' => $sa, 'sb' => $sb, 'mixer' => $mixer, 'ra' => $ra, 'rb' => $rb, 'rc' => $rc, 'rd' => $rd, 're' => $re, 'rf' => $rf, 'rg' => $rg, 'coating' => $coating])->setPaper('a5', 'portrait')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewLaporanHasilLabTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $laporan_produksi_table = DB::table('laporan_hasil_produksi as lap')->select('lap.nomor_laporan_produksi', 'lap.tanggal_laporan_produksi', DB::raw('count(distinct lab.nomor_laporan_produksi) as jumlah_pengujian'), DB::raw('count(distinct det.nomor_laporan_produksi) as jumlah_data'))->leftJoin("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->leftJoin("laporan_hasil_produksi_detail as det", "det.nomor_laporan_produksi", "=", "lap.nomor_laporan_produksi")->whereBetween('lap.tanggal_laporan_produksi', array($request->from_date, $request->to_date))->groupBy('lap.tanggal_laporan_produksi')->get();
            }else{
                $laporan_produksi_table = DB::table('laporan_hasil_produksi as lap')->select('lap.nomor_laporan_produksi', 'lap.tanggal_laporan_produksi', DB::raw('count(distinct lab.nomor_laporan_produksi) as jumlah_pengujian'), DB::raw('count(distinct det.nomor_laporan_produksi) as jumlah_data'))->leftJoin("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->leftJoin("laporan_hasil_produksi_detail as det", "det.nomor_laporan_produksi", "=", "lap.nomor_laporan_produksi")->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->groupBy('lap.tanggal_laporan_produksi')->get();
            }

            return datatables()->of($laporan_produksi_table)->addIndexColumn()->addColumn('action', 'button/action_button_laporan_hasil_lab')->rawColumns(['action'])->make(true);
        }
        return view('laporan_hasil_lab');
    }

    public function getSpesifikasiMesin(){
        $data_a = DB::table('tbl_spesifikasi_mesin')->select('whiteness', 'moisture', 'residue_max')->where('mesin', 1)->first();
        $data_b = DB::table('tbl_spesifikasi_mesin')->select('whiteness', 'moisture', 'residue_max')->where('mesin', 4)->first();
        $data_c = DB::table('tbl_spesifikasi_mesin')->select('whiteness', 'moisture', 'residue_max')->where('mesin', 6)->first();

        return Response()->json(['data_a' => $data_a, 'data_b' => $data_b, 'data_c' => $data_c]);
    }

    public function inputLaporanHasilLab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $cek_data_laporan = DB::table('laporan_hasil_produksi')->select('nomor_laporan_produksi')->where('nomor_laporan_produksi', 'like', 'LHP' . $tanggal . '%')->orderBy('nomor_laporan_produksi', 'asc')->distinct()->get();

        if($cek_data_laporan){
            $data_laporan_count = $cek_data_laporan->count();
            if($data_laporan_count > 0){
                $num = (int) substr($cek_data_laporan[$cek_data_laporan->count() - 1]->nomor_laporan_produksi, 8);
                if($data_laporan_count != $num){
                    $nomor_laporan_produksi = ++$cek_data_laporan[$cek_data_laporan->count() - 1]->nomor_laporan_produksi;
                }else{
                    if($data_laporan_count < 9){
                        $nomor_laporan_produksi = "LHP" . $tanggal . "-000" . ($data_laporan_count + 1);
                    }else if($data_laporan_count >= 9 && $data_laporan_count < 99){
                        $nomor_laporan_produksi = "LHP" . $tanggal . "-00" . ($data_laporan_count + 1);
                    }else if($data_laporan_count >= 99 && $data_laporan_count < 999){
                        $nomor_laporan_produksi = "LHP" . $tanggal . "-0" . ($data_laporan_count + 1);
                    }else{
                        $nomor_laporan_produksi = "LHP" . $tanggal . "-" . ($data_laporan_count + 1);
                    }
                }
            }else{
                $nomor_laporan_produksi = "LHP" . $tanggal . "-0001";
            }
        }else{
            $nomor_laporan_produksi = "LHP" . $tanggal . "-0001";
        }

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('laporan_hasil_produksi')->insert(["nomor_laporan_produksi" => $nomor_laporan_produksi, "tanggal_laporan_produksi" => $request->tanggal, "tanggal_input" => date('Y-m-d'), "referensi" => $request->referensi, "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "created_by" => Session::get('id_user_admin'), "updated_by" => Session::get('id_user_admin')]);

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        for($i = 1; $i <= count($data_mesin); $i++){
            if(isset($request->{"moisture_" . $data_mesin[$i - 1]}) || !empty($request->{"moisture_" . $data_mesin[$i - 1]})){
                $cek_data_lab = DB::table('laporan_hasil_produksi_lab')->select('nomor_laporan_produksi_lab')->where('nomor_laporan_produksi_lab', 'like', 'LHPL' . $tanggal . '%')->orderBy('nomor_laporan_produksi_lab', 'asc')->distinct()->get();

                if(!isset($request->{"standar_d50_" . $data_mesin[$i - 1]}) || empty($request->{"standar_d50_" . $data_mesin[$i - 1]})){
                    $request->{"standar_d50_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"hasil_d50_" . $data_mesin[$i - 1]}) || empty($request->{"hasil_d50_" . $data_mesin[$i - 1]})){
                    $request->{"hasil_d50_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"standar_d98_" . $data_mesin[$i - 1]}) || empty($request->{"standar_d98_" . $data_mesin[$i - 1]})){
                    $request->{"standar_d98_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"hasil_d98_" . $data_mesin[$i - 1]}) || empty($request->{"hasil_d98_" . $data_mesin[$i - 1]})){
                    $request->{"hasil_d98_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"cie86_" . $data_mesin[$i - 1]}) || empty($request->{"cie86_" . $data_mesin[$i - 1]})){
                    $request->{"cie86_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"iso2470_" . $data_mesin[$i - 1]}) || empty($request->{"iso2470_" . $data_mesin[$i - 1]})){
                    $request->{"iso2470_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"moisture_" . $data_mesin[$i - 1]}) || empty($request->{"moisture_" . $data_mesin[$i - 1]})){
                    $request->{"moisture_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"residue_" . $data_mesin[$i - 1]}) || empty($request->{"residue_" . $data_mesin[$i - 1]})){
                    $request->{"residue_" . $data_mesin[$i - 1]} = 0;
                }

                if($cek_data_lab){
                    $data_lab_count = $cek_data_lab->count();
                    if($data_lab_count > 0){
                        $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_laporan_produksi_lab, 9);
                        if($data_lab_count != $num){
                            $nomor_laporan_produksi_lab = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_laporan_produksi_lab;
                        }else{
                            if($data_lab_count < 9){
                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-00000" . ($data_lab_count + 1);
                            }else if($data_lab_count >= 9 && $data_lab_count < 99){
                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-0000" . ($data_lab_count + 1);
                            }else if($data_lab_count >= 99 && $data_lab_count < 999){
                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-000" . ($data_lab_count + 1);
                            }else if($data_lab_count >= 999 && $data_lab_count < 9999){
                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-00" . ($data_lab_count + 1);
                            }else if($data_lab_count >= 9999 && $data_lab_count < 99999){
                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-0" . ($data_lab_count + 1);
                            }else{
                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-" . ($data_lab_count + 1);
                            }
                        }
                    }else{
                        $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-000001";
                    }
                }else{
                    $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-000001";
                }

                $data_lab = DB::table('laporan_hasil_produksi_lab')->insert(["nomor_laporan_produksi_lab" => $nomor_laporan_produksi_lab, "nomor_laporan_produksi" => $nomor_laporan_produksi, "jam_waktu" => $request->jam_waktu, "mesin" => $i, "mesh" => $request->{"mesh_" . $data_mesin[$i - 1]}, "std_ssa" => $request->{"standar_ssa_" . $data_mesin[$i - 1]}, "ssa" => $request->{"hasil_ssa_" . $data_mesin[$i - 1]}, "std_d50" => str_replace(',', '.', $request->{"standar_d50_" . $data_mesin[$i - 1]}), "d50" => str_replace(',', '.', $request->{"hasil_d50_" . $data_mesin[$i - 1]}), "std_d98" => str_replace(',', '.', $request->{"standar_d98_" . $data_mesin[$i - 1]}), "d98" => str_replace(',', '.', $request->{"hasil_d98_" . $data_mesin[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"cie86_" . $data_mesin[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"iso2470_" . $data_mesin[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"moisture_" . $data_mesin[$i - 1]}), "residue" => str_replace(',', '.', $request->{"residue_" . $data_mesin[$i - 1]})]);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Lab Input Laporan Hasil Lab Nomor ' . $nomor_laporan_produksi]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function viewLaporanHasilLab($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $data_lab = DB::table('laporan_hasil_produksi as lap')->select('lab.jam_waktu', 'tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.std_ssa', 'lab.ssa', 'lab.std_d50', 'lab.d50', 'lab.std_d98', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lap.tanggal_laporan_produksi', $val_tanggal)->orderBy('lab.mesin', 'asc')->orderBy('lab.jam_waktu', 'asc')->get();

        $arrayForTable = [];
        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

        for($i = 0; $i < count($arr_mesin); $i++){
            $arrayForTable[$arr_mesin[$i]] = [];
        }
        foreach($data_lab as $data_lab){
            $temp = [];
            $temp['jam_waktu'] = $data_lab->jam_waktu;
            $temp['mesh'] = $data_lab->mesh;
            $temp['rpm'] = $data_lab->rpm;
            $temp['std_ssa'] = $data_lab->std_ssa;
            $temp['ssa'] = $data_lab->ssa;
            $temp['std_d50'] = $data_lab->std_d50;
            $temp['d50'] = $data_lab->d50;
            $temp['std_d98'] = $data_lab->std_d98;
            $temp['d98'] = $data_lab->d98;
            $temp['cie86'] = $data_lab->cie86;
            $temp['iso2470'] = $data_lab->iso2470;
            $temp['moisture'] = $data_lab->moisture;
            $temp['spek_whiteness'] = $data_lab->spek_whiteness;
            $temp['spek_moisture'] = $data_lab->spek_moisture;
            $temp['spek_residue'] = $data_lab->spek_residue;
            $temp['standart_residue'] = $data_lab->residue_max;
            $temp['residue'] = $data_lab->residue;
            $arrayForTable[$data_lab->mesin][] = $temp;
        }

        return Response()->json($arrayForTable);
    }

    public function printLaporanHasilLab($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data_count = DB::table('laporan_hasil_produksi as lap')->select('lap.nomor_laporan_produksi', 'lap.tanggal_laporan_produksi', 'lap.referensi', DB::raw('count(distinct lab.nomor_laporan_produksi) as jumlah_pengujian'))->leftJoin("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->where('lap.tanggal_laporan_produksi', $val_tanggal)->groupBy('lap.tanggal_laporan_produksi')->get();

        $data_lab = DB::table('laporan_hasil_produksi as lap')->select('lab.jam_waktu', 'tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.std_ssa', 'lab.ssa', 'lab.std_d50', 'lab.d50', 'lab.std_d98', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lap.tanggal_laporan_produksi', $val_tanggal)->orderBy('lab.mesin', 'asc')->orderBy('lab.jam_waktu', 'asc')->get();

        $data_a = DB::table('tbl_spesifikasi_mesin')->select('whiteness', 'moisture', 'residue_max')->where('mesin', 1)->first();
        $data_b = DB::table('tbl_spesifikasi_mesin')->select('whiteness', 'moisture', 'residue_max')->where('mesin', 4)->first();
        $data_c = DB::table('tbl_spesifikasi_mesin')->select('whiteness', 'moisture', 'residue_max')->where('mesin', 6)->first();

        $arrayForTable = [];
        foreach($data_lab as $data_lab){
            $temp = [];
            $temp['jam_waktu'] = $data_lab->jam_waktu;
            $temp['mesh'] = $data_lab->mesh;
            $temp['rpm'] = $data_lab->rpm;
            $temp['std_ssa'] = $data_lab->std_ssa;
            $temp['ssa'] = $data_lab->ssa;
            $temp['std_d50'] = $data_lab->std_d50;
            $temp['d50'] = $data_lab->d50;
            $temp['std_d98'] = $data_lab->std_d98;
            $temp['d98'] = $data_lab->d98;
            $temp['cie86'] = $data_lab->cie86;
            $temp['iso2470'] = $data_lab->iso2470;
            $temp['moisture'] = $data_lab->moisture;
            $temp['spek_whiteness'] = $data_lab->spek_whiteness;
            $temp['spek_moisture'] = $data_lab->spek_moisture;
            $temp['spek_residue'] = $data_lab->spek_residue;
            $temp['standart_residue'] = $data_lab->residue_max;
            $temp['residue'] = $data_lab->residue;
            if(!isset($arrayForTable[$data_lab->mesin])){
                $arrayForTable[$data_lab->mesin] = [];
            }
            $arrayForTable[$data_lab->mesin][] = $temp;
        }

        $pdf = PDF::loadView('print_laporan_hasil_lab', ['tanggal' => $val_tanggal, 'referensi' => $data_count[0]->referensi, 'data_lab' => $arrayForTable, 'data_spek_a' => $data_a, 'data_spek_b' => $data_b, 'data_spek_c' => $data_c])->setPaper('a5', 'landscape');
        return $pdf->stream();
    }

    public function viewBatchLaporanHasilLab($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $data = DB::table('laporan_hasil_produksi')->select('nomor_laporan_produksi', 'tanggal_laporan_produksi', 'referensi')->where('tanggal_laporan_produksi', $val_tanggal)->get();

        return Response()->json($data);
    }

    public function viewBatchReferensiLaporanHasilLab($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('laporan_hasil_produksi')->select('referensi')->where('nomor_laporan_produksi', $val_nomor)->get();

        return Response()->json($data);
    }

    public function viewDetailBatchLaporanHasilLab($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('laporan_hasil_produksi_lab')->select('jam_waktu', 'mesin', 'mesh', 'rpm', 'std_ssa', 'ssa', 'std_d50', 'd50', 'std_d98', 'd98', 'cie86', 'iso2470', 'moisture', 'residue')->where('nomor_laporan_produksi', $val_nomor)->get();

        return Response()->json($data);
    }

    public function editLaporanHasilLab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('laporan_hasil_produksi')->where("nomor_laporan_produksi", $request->edit_nomor)->update(["tanggal_laporan_produksi" => $request->edit_tanggal, "referensi" => $request->edit_referensi, "updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        for($i = 1; $i <= count($data_mesin); $i++){
            if(isset($request->{"edit_moisture_" . $data_mesin[$i - 1]}) || !empty($request->{"edit_moisture_" . $data_mesin[$i - 1]})){

                if(!isset($request->{"edit_standar_d50_" . $data_mesin[$i - 1]}) || empty($request->{"edit_standar_d50_" . $data_mesin[$i - 1]})){
                    $request->{"edit_standar_d50_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"edit_hasil_d50_" . $data_mesin[$i - 1]}) || empty($request->{"edit_hasil_d50_" . $data_mesin[$i - 1]})){
                    $request->{"edit_hasil_d50_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"edit_standar_d98_" . $data_mesin[$i - 1]}) || empty($request->{"edit_standar_d98_" . $data_mesin[$i - 1]})){
                    $request->{"edit_standar_d98_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"edit_hasil_d98_" . $data_mesin[$i - 1]}) || empty($request->{"edit_hasil_d98_" . $data_mesin[$i - 1]})){
                    $request->{"edit_hasil_d98_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"edit_cie86_" . $data_mesin[$i - 1]}) || empty($request->{"edit_cie86_" . $data_mesin[$i - 1]})){
                    $request->{"edit_cie86_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"edit_iso2470_" . $data_mesin[$i - 1]}) || empty($request->{"edit_iso2470_" . $data_mesin[$i - 1]})){
                    $request->{"edit_iso2470_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"edit_moisture_" . $data_mesin[$i - 1]}) || empty($request->{"edit_moisture_" . $data_mesin[$i - 1]})){
                    $request->{"edit_moisture_" . $data_mesin[$i - 1]} = 0;
                }

                if(!isset($request->{"edit_residue_" . $data_mesin[$i - 1]}) || empty($request->{"edit_residue_" . $data_mesin[$i - 1]})){
                    $request->{"edit_residue_" . $data_mesin[$i - 1]} = 0;
                }

                $cek_data_exist = DB::table('laporan_hasil_produksi_lab')->select('nomor_laporan_produksi_lab')->where('nomor_laporan_produksi', $request->edit_nomor)->where('mesin', $i)->first();

                if($cek_data_exist){
                    $data_lab = DB::table('laporan_hasil_produksi_lab')->where('nomor_laporan_produksi', $request->edit_nomor)->where('mesin', $i)->update(["jam_waktu" => $request->edit_jam_waktu, "mesh" => $request->{"edit_mesh_" . $data_mesin[$i - 1]}, "std_ssa" => $request->{"edit_standar_ssa_" . $data_mesin[$i - 1]}, "ssa" => $request->{"edit_hasil_ssa_" . $data_mesin[$i - 1]}, "std_d50" => str_replace(',', '.', $request->{"edit_standar_d50_" . $data_mesin[$i - 1]}), "d50" => str_replace(',', '.', $request->{"edit_hasil_d50_" . $data_mesin[$i - 1]}), "std_d98" => str_replace(',', '.', $request->{"edit_standar_d98_" . $data_mesin[$i - 1]}), "d98" => str_replace(',', '.', $request->{"edit_hasil_d98_" . $data_mesin[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"edit_cie86_" . $data_mesin[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"edit_iso2470_" . $data_mesin[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"edit_moisture_" . $data_mesin[$i - 1]}), "residue" => str_replace(',', '.', $request->{"edit_residue_" . $data_mesin[$i - 1]})]);
                }else{
                    $cek_data_lab = DB::table('laporan_hasil_produksi_lab')->select('nomor_laporan_produksi_lab')->where('nomor_laporan_produksi_lab', 'like', 'LHPL' . $tanggal . '%')->orderBy('nomor_laporan_produksi_lab', 'asc')->distinct()->get();

                    if($cek_data_lab){
                        $data_lab_count = $cek_data_lab->count();
                        if($data_lab_count > 0){
                            $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_laporan_produksi_lab, 9);
                            if($data_lab_count != $num){
                                $nomor_laporan_produksi_lab = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_laporan_produksi_lab;
                            }else{
                                if($data_lab_count < 9){
                                    $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-00000" . ($data_lab_count + 1);
                                }else if($data_lab_count >= 9 && $data_lab_count < 99){
                                    $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-0000" . ($data_lab_count + 1);
                                }else if($data_lab_count >= 99 && $data_lab_count < 999){
                                    $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-000" . ($data_lab_count + 1);
                                }else if($data_lab_count >= 999 && $data_lab_count < 9999){
                                    $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-00" . ($data_lab_count + 1);
                                }else if($data_lab_count >= 9999 && $data_lab_count < 99999){
                                    $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-0" . ($data_lab_count + 1);
                                }else{
                                    $nomor_laporan_produksi_lab = "LHPL-" . $tanggal . ($data_lab_count + 1);
                                }
                            }
                        }else{
                            $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-000001";
                        }
                    }else{
                        $nomor_laporan_produksi_lab = "LHPL" . $tanggal . "-000001";
                    }

                    $data_lab = DB::table('laporan_hasil_produksi_lab')->insert(["nomor_laporan_produksi_lab" => $nomor_laporan_produksi_lab, "nomor_laporan_produksi" => $request->edit_nomor, "jam_waktu" => $request->edit_jam_waktu, "mesin" => $i, "mesh" => $request->{"edit_mesh_" . $data_mesin[$i - 1]}, "std_ssa" => $request->{"edit_standar_ssa_" . $data_mesin[$i - 1]}, "ssa" => $request->{"edit_hasil_ssa_" . $data_mesin[$i - 1]}, "std_d50" => str_replace(',', '.', $request->{"edit_standar_d50_" . $data_mesin[$i - 1]}), "d50" => str_replace(',', '.', $request->{"edit_hasil_d50_" . $data_mesin[$i - 1]}), "std_d98" => str_replace(',', '.', $request->{"edit_standar_d98_" . $data_mesin[$i - 1]}), "d98" => str_replace(',', '.', $request->{"edit_hasil_d98_" . $data_mesin[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"edit_cie86_" . $data_mesin[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"edit_iso2470_" . $data_mesin[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"edit_moisture_" . $data_mesin[$i - 1]}), "residue" => str_replace(',', '.', $request->{"edit_residue_" . $data_mesin[$i - 1]})]);
                }
            }else{
                $cek_data_exist = DB::table('laporan_hasil_produksi_lab')->select('nomor_laporan_produksi_lab')->where('nomor_laporan_produksi', $request->edit_nomor)->where('mesin', $i)->first();

                if($cek_data_exist){
                    $data_lab = DB::table('laporan_hasil_produksi_lab')->where('nomor_laporan_produksi', $request->edit_nomor)->where('mesin', $i)->where('jam_waktu', $request->edit_jam_waktu)->delete();
                }
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Lab Melakukan Edit Data Laporan Hasil Lab Nomor ' . $request->edit_nomor]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json(['nomor_laporan_produksi' => $request->edit_nomor, 'tanggal' => $request->edit_tanggal]);
    }

    public function viewTeknikProduksiTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $teknik_produksi_table = DB::table('laporan_hasil_produksi as lap')->select('lap.nomor_laporan_produksi', 'lap.tanggal_laporan_produksi', DB::raw('count(distinct lab.nomor_laporan_produksi) as jumlah_data_spek_mesin'), DB::raw("SUM(CASE WHEN lab.rpm is null THEN 0 ELSE 1 END) AS jumlah_rpm"), DB::raw('count(distinct det.nomor_laporan_produksi) as jumlah_data'))->leftJoin("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->leftJoin("laporan_hasil_produksi_detail as det", "det.nomor_laporan_produksi", "=", "lap.nomor_laporan_produksi")->whereBetween('lap.tanggal_laporan_produksi', array($request->from_date, $request->to_date))->groupBy('lap.tanggal_laporan_produksi')->get();
            }else{
                $teknik_produksi_table = DB::table('laporan_hasil_produksi as lap')->select('lap.nomor_laporan_produksi', 'lap.tanggal_laporan_produksi', DB::raw('count(distinct lab.nomor_laporan_produksi) as jumlah_data_spek_mesin'), DB::raw("SUM(CASE WHEN lab.rpm is null THEN 0 ELSE 1 END) AS jumlah_rpm"), DB::raw('count(distinct det.nomor_laporan_produksi) as jumlah_data'))->leftJoin("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->leftJoin("laporan_hasil_produksi_detail as det", "det.nomor_laporan_produksi", "=", "lap.nomor_laporan_produksi")->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->groupBy('lap.tanggal_laporan_produksi')->get();
            }

            return datatables()->of($teknik_produksi_table)->addIndexColumn()->addColumn('action', 'button/action_button_teknik_produksi')->rawColumns(['action'])->make(true);
        }
        return view('teknik_produksi');
    }

    public function inputTeknikProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('laporan_hasil_produksi')->where("nomor_laporan_produksi", $request->nomor)->update(["updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        for($i = 1; $i <= count($data_mesin); $i++){
            if(isset($request->{"rpm_" . $data_mesin[$i - 1]}) || !empty($request->{"rpm_" . $data_mesin[$i - 1]})){
                
                $data_lab = DB::table('laporan_hasil_produksi_lab')->where('nomor_laporan_produksi', $request->nomor)->where('mesin', $i)->update(["rpm" => $request->{"rpm_" . $data_mesin[$i - 1]}]);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Teknik Melakukan Input RPM Laporan Hasil Lab Nomor ' . $request->nomor]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json(['nomor_laporan_produksi' => $request->nomor, 'tanggal' => $request->tanggal]);
    }

    public function editTeknikProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('laporan_hasil_produksi')->where("nomor_laporan_produksi", $request->edit_nomor)->update(["updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        for($i = 1; $i <= count($data_mesin); $i++){
            if(isset($request->{"edit_rpm_" . $data_mesin[$i - 1]}) || !empty($request->{"edit_rpm_" . $data_mesin[$i - 1]})){
                
                $data_lab = DB::table('laporan_hasil_produksi_lab')->where('nomor_laporan_produksi', $request->edit_nomor)->where('mesin', $i)->update(["rpm" => $request->{"edit_rpm_" . $data_mesin[$i - 1]}]);
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Teknik Melakukan Edit RPM Laporan Hasil Lab Nomor ' . $request->edit_nomor]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json(['nomor_laporan_produksi' => $request->edit_nomor, 'tanggal' => $request->edit_tanggal]);
    }

    public function excelHasilLab($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $nama_file = 'Hasil Lab ' . $val_tanggal . '.xlsx';

        return Excel::download(new HasilLabExport($val_tanggal), $nama_file);
    }

    public function excelHasilLabPeriodic($from_date, $to_date){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        $nama_file = 'Hasil Lab Periodic From ' . $val_from_date . ' - ' . $val_to_date . '.xlsx';

        return Excel::download(new HasilLabPeriodicSheetExport($val_from_date, $val_to_date), $nama_file);
    }

    public function excelTrendInformasiMesin(Request $request){
        $nama_file = 'Trend Informasi Mesin Selama ' . $request->periode . ' Bulan.xlsx';

        return Excel::download(new TrendInformasiMesinExport($request->periode, $request->mesh, $request->rpm), $nama_file);
    }

    public function excelHasilTeknikMasalahMesin($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $nama_file = 'Hasil Lab dan Teknik ' . $val_tanggal . '.xlsx';

        return Excel::download(new TeknikMasalahMesinExport($val_tanggal), $nama_file);
    }

    public function viewTeknikMasalahMesinTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $teknik_masalah_table = DB::table('laporan_hasil_produksi as lap')->select('lap.nomor_laporan_produksi', 'lap.tanggal_laporan_produksi', DB::raw("SUM(CASE WHEN lap_mesin.nomor_laporan_produksi_mesin is null THEN 0 ELSE 1 END) AS jumlah_masalah"), DB::raw('count(distinct det.nomor_laporan_produksi) as jumlah_data'))->leftJoin("laporan_hasil_produksi_mesin as lap_mesin", "lap_mesin.nomor_laporan_produksi", "=", "lap.nomor_laporan_produksi")->leftJoin("laporan_hasil_produksi_detail as det", "det.nomor_laporan_produksi", "=", "lap.nomor_laporan_produksi")->whereBetween('lap.tanggal_laporan_produksi', array($request->from_date, $request->to_date))->groupBy('lap.tanggal_laporan_produksi')->get();
            }else{
                $teknik_masalah_table = DB::table('laporan_hasil_produksi as lap')->select('lap.nomor_laporan_produksi', 'lap.tanggal_laporan_produksi', DB::raw("SUM(CASE WHEN lap_mesin.nomor_laporan_produksi_mesin is null THEN 0 ELSE 1 END) AS jumlah_masalah"), DB::raw('count(distinct det.nomor_laporan_produksi) as jumlah_data'))->leftJoin("laporan_hasil_produksi_mesin as lap_mesin", "lap_mesin.nomor_laporan_produksi", "=", "lap.nomor_laporan_produksi")->leftJoin("laporan_hasil_produksi_detail as det", "det.nomor_laporan_produksi", "=", "lap.nomor_laporan_produksi")->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->groupBy('lap.tanggal_laporan_produksi')->get();
            }

            return datatables()->of($teknik_masalah_table)->addIndexColumn()->addColumn('action', 'button/action_button_teknik_masalah_mesin')->rawColumns(['action'])->make(true);
        }
        return view('teknik_masalah_mesin');
    }

    public function inputTeknikMasalahMesin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('laporan_hasil_produksi')->where("tanggal_laporan_produksi", $request->tanggal_laporan_produksi)->update(["updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        for($j = 1; $j <= count($data_mesin); $j++){
            $number = count($request->{"masalah_" . $data_mesin[$j - 1]});

            for($i = 0; $i < $number; $i++){
                if(!empty($request->{"masalah_" . $data_mesin[$j - 1]}[$i])){
                    $cek_data_mesin = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin')->where('nomor_laporan_produksi_mesin', 'like', 'LHPM' . $tanggal . '%')->orderBy('nomor_laporan_produksi_mesin', 'asc')->distinct()->get();

                    if($cek_data_mesin){
                        $data_mesin_count = $cek_data_mesin->count();
                        if($data_mesin_count > 0){
                            $num = (int) substr($cek_data_mesin[$cek_data_mesin->count() - 1]->nomor_laporan_produksi_mesin, 9);
                            if($data_mesin_count != $num){
                                $nomor_laporan_produksi_mesin = ++$cek_data_mesin[$cek_data_mesin->count() - 1]->nomor_laporan_produksi_mesin;
                            }else{
                                if($data_mesin_count < 9){
                                    $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-00000" . ($data_mesin_count + 1);
                                }else if($data_mesin_count >= 9 && $data_mesin_count < 99){
                                    $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-0000" . ($data_mesin_count + 1);
                                }else if($data_mesin_count >= 99 && $data_mesin_count < 999){
                                    $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-000" . ($data_mesin_count + 1);
                                }else if($data_mesin_count >= 999 && $data_mesin_count < 9999){
                                    $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-00" . ($data_mesin_count + 1);
                                }else if($data_mesin_count >= 9999 && $data_mesin_count < 99999){
                                    $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-0" . ($data_mesin_count + 1);
                                }else{
                                    $nomor_laporan_produksi_mesin = "LHPM-" . $tanggal . ($data_mesin_count + 1);
                                }
                            }
                        }else{
                            $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-000001";
                        }
                    }else{
                        $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-000001";
                    }

                    $data = DB::table('laporan_hasil_produksi_mesin')->insert(["nomor_laporan_produksi_mesin" => $nomor_laporan_produksi_mesin, "nomor_laporan_produksi" => $request->nomor_laporan_produksi, "mesin" => $j, "jam_awal" => $request->{"jam_awal_" . $data_mesin[$j - 1]}[$i], "jam_akhir" => $request->{"jam_akhir_" . $data_mesin[$j - 1]}[$i], "masalah" => $request->{"masalah_" . $data_mesin[$j - 1]}[$i], "kategori" => $request->{"kategori_" . $data_mesin[$j - 1]}[$i]]);
                }
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Teknik Input Masalah Mesin Tanggal ' . $request->tanggal_laporan_produksi]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function viewTeknikMasalahMesin($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $sa = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 1)->get();

        $sb = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 2)->get();

        $mixer = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 3)->get();

        $ra = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 4)->get();

        $rb = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 5)->get();

        $rc = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 6)->get();

        $rd = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 7)->get();

        $re = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 8)->get();

        $rf = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 9)->get();

        $rg = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin', 'jam_awal', 'jam_akhir', 'masalah', 'kategori')->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_mesin.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->where('mesin', 10)->get();

        return Response()->json(['sa' => $sa, 'sb' => $sb, 'mixer' => $mixer, 'ra' => $ra, 'rb' => $rb, 'rc' => $rc, 'rd' => $rd, 're' => $re, 'rf' => $rf, 'rg' => $rg]);
    }

    public function viewDataMasalahMesin($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $data_tonase = DB::table('laporan_hasil_produksi as lap')->select(DB::raw("sum(det.jumlah_tonase) as produksi_tonase"), 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_detail as det", "lap.nomor_laporan_produksi", "=", "det.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'det.mesin')->where('lap.tanggal_laporan_produksi', $val_tanggal)->orderBy('det.mesin', 'asc')->groupBy('det.mesin')->get();

        $data_masalah = DB::table('laporan_hasil_produksi as lap')->select('mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('lap.tanggal_laporan_produksi', $val_tanggal)->orderBy('mes.mesin', 'asc')->get();

        $arrayForTableA = [];
        $arrayForTableB = [];
        $arrayForTableC = [];
        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

        for($i = 0; $i < count($arr_mesin); $i++){
            $arrayForTableA[$arr_mesin[$i]] = [];
            $arrayForTableB[$arr_mesin[$i]] = [];
            $arrayForTableC[$arr_mesin[$i]] = [];
        }

        foreach($data_masalah as $data_masalah){
            if($data_masalah->kategori == 1){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableA[$data_masalah->mesin]['masalah'][] = $temp;
            }else if($data_masalah->kategori == 2){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableB[$data_masalah->mesin]['masalah'][] = $temp;
            }else if($data_masalah->kategori == 3){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableC[$data_masalah->mesin]['masalah'][] = $temp;
            }
        }

        foreach($data_tonase as $data_tonase){
            $arrayForTableA[$data_tonase->mesin]['tonase'] = $data_tonase->produksi_tonase;
            $arrayForTableB[$data_tonase->mesin]['tonase'] = $data_tonase->produksi_tonase;
            $arrayForTableC[$data_tonase->mesin]['tonase'] = $data_tonase->produksi_tonase;
        }

        return Response()->json(['major' => $arrayForTableA, 'minor' => $arrayForTableB, 'lain' => $arrayForTableC]);
    }

    public function editTeknikMasalahMesin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('laporan_hasil_produksi')->where("tanggal_laporan_produksi", $request->edit_tanggal_laporan_produksi)->update(["updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        for($j = 1; $j <= count($data_mesin); $j++){
            $number = count($request->{"edit_masalah_" . $data_mesin[$j - 1]});

            for($i = 0; $i < $number; $i++){
                if(!empty($request->{"edit_masalah_" . $data_mesin[$j - 1]}[$i])){
                    if(!empty($request->{"edit_nomor_laporan_produksi_mesin_" . $data_mesin[$j - 1]}[$i])){
                        $data = DB::table('laporan_hasil_produksi_mesin')->where('nomor_laporan_produksi_mesin', $request->{"edit_nomor_laporan_produksi_mesin_" . $data_mesin[$j - 1]}[$i])->update(["jam_awal" => $request->{"edit_jam_awal_" . $data_mesin[$j - 1]}[$i], "jam_akhir" => $request->{"edit_jam_akhir_" . $data_mesin[$j - 1]}[$i], "masalah" => $request->{"edit_masalah_" . $data_mesin[$j - 1]}[$i], "kategori" => $request->{"edit_kategori_" . $data_mesin[$j - 1]}[$i]]);
                    }else{
                        $cek_data_mesin = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi_mesin')->where('nomor_laporan_produksi_mesin', 'like', 'LHPM' . $tanggal . '%')->orderBy('nomor_laporan_produksi_mesin', 'asc')->distinct()->get();

                        if($cek_data_mesin){
                            $data_mesin_count = $cek_data_mesin->count();
                            if($data_mesin_count > 0){
                                $num = (int) substr($cek_data_mesin[$cek_data_mesin->count() - 1]->nomor_laporan_produksi_mesin, 9);
                                if($data_mesin_count != $num){
                                    $nomor_laporan_produksi_mesin = ++$cek_data_mesin[$cek_data_mesin->count() - 1]->nomor_laporan_produksi_mesin;
                                }else{
                                    if($data_mesin_count < 9){
                                        $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-00000" . ($data_mesin_count + 1);
                                    }else if($data_mesin_count >= 9 && $data_mesin_count < 99){
                                        $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-0000" . ($data_mesin_count + 1);
                                    }else if($data_mesin_count >= 99 && $data_mesin_count < 999){
                                        $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-000" . ($data_mesin_count + 1);
                                    }else if($data_mesin_count >= 999 && $data_mesin_count < 9999){
                                        $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-00" . ($data_mesin_count + 1);
                                    }else if($data_mesin_count >= 9999 && $data_mesin_count < 99999){
                                        $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-0" . ($data_mesin_count + 1);
                                    }else{
                                        $nomor_laporan_produksi_mesin = "LHPM-" . $tanggal . ($data_mesin_count + 1);
                                    }
                                }
                            }else{
                                $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-000001";
                            }
                        }else{
                            $nomor_laporan_produksi_mesin = "LHPM" . $tanggal . "-000001";
                        }

                        $data = DB::table('laporan_hasil_produksi_mesin')->insert(["nomor_laporan_produksi_mesin" => $nomor_laporan_produksi_mesin, "nomor_laporan_produksi" => $request->edit_nomor_laporan_produksi, "mesin" => $j, "jam_awal" => $request->{"edit_jam_awal_" . $data_mesin[$j - 1]}[$i], "jam_akhir" => $request->{"edit_jam_akhir_" . $data_mesin[$j - 1]}[$i], "masalah" => $request->{"edit_masalah_" . $data_mesin[$j - 1]}[$i], "kategori" => $request->{"edit_kategori_" . $data_mesin[$j - 1]}[$i]]);

                    }
                }
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Teknik Edit Data Masalah Mesin Tanggal ' . $request->edit_tanggal_laporan_produksi]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function viewDataLaporanTotalProduksi(Request $request){
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');
        $data_produksi = DB::table('laporan_hasil_produksi as lap')->select('tbl_mesin.name as mesin', DB::raw("sum(jumlah_tonase) AS total"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join("laporan_hasil_produksi_detail as det", "lap.nomor_laporan_produksi", "=", "det.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'det.mesin')->whereBetween('lap.tanggal_laporan_produksi', array($start_date, $end_date))->groupBy('det.mesin', 'new_date')->orderBy('det.mesin', 'asc')->orderBy('new_date', 'asc')->get();

        $array = [];
        $tahun = date("Y");
        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];
        $arr_bulan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $arr_tahun = [$tahun - 2, $tahun - 1, $tahun];

        for($i = 0; $i < count($arr_mesin); $i++){
            $array[$arr_mesin[$i]] = [];
            for($j = 0; $j < count($arr_tahun); $j++){
                $array[$arr_mesin[$i]][$arr_tahun[$j]] = [];
                for($k = 0; $k < count($arr_bulan); $k++){
                    $array[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]] = null;
                }
            }
        }

        foreach($data_produksi as $data_produksi){
            $array[$data_produksi->mesin][$data_produksi->tahun][$data_produksi->bulan] = $data_produksi->total;
        }

        // dd($array);

        return Response()->json($array);
    }

    public function deleteDetailTeknikMasalahMesin(Request $request){
        $data = DB::table('laporan_hasil_produksi_mesin')->select('nomor_laporan_produksi')->where('nomor_laporan_produksi_mesin', $request->get('nomor'))->first();

        $hapus = DB::table('laporan_hasil_produksi_mesin')->where('nomor_laporan_produksi_mesin', $request->get('nomor'))->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Teknik Delete Data Masalah Mesin Nomor Detail : ' . $request->get('nomor') . ' Dan Data Laporan Produksi Nomor ' . $data->nomor_laporan_produksi]);
    }

    public function grafikDataLaporanTotalProduksi(Request $request){
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');

        $sa = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 1)->groupBy('mesin', 'new_date')->get();

        $sb = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 2)->groupBy('mesin', 'new_date')->get();

        $mixer = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 3)->groupBy('mesin', 'new_date')->get();

        $ra = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 4)->groupBy('mesin', 'new_date')->get();

        $rb = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 5)->groupBy('mesin', 'new_date')->get();

        $rc = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 6)->groupBy('mesin', 'new_date')->get();

        $rd = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 7)->groupBy('mesin', 'new_date')->get();

        $re = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 8)->groupBy('mesin', 'new_date')->get();

        $rf = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 9)->groupBy('mesin', 'new_date')->get();

        $rg = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 10)->groupBy('mesin', 'new_date')->get();

        $total = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->groupBy('new_date')->get();

        return Response()->json(['SA' => $sa, 'SB' => $sb, 'Mixer' => $mixer, 'RA' => $ra, 'RB' => $rb, 'RC' => $rc, 'RD' => $rd, 'RE' => $re, 'RF' => $rf, 'RG' => $rg, 'Total' => $total]);
    }

    public function viewDataLaporanRataProduksi(Request $request){
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');
        $data_produksi = DB::table('laporan_hasil_produksi as lap')->select('tbl_mesin.name as mesin', DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) AS total"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join("laporan_hasil_produksi_detail as det", "lap.nomor_laporan_produksi", "=", "det.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'det.mesin')->whereBetween('lap.tanggal_laporan_produksi', array($start_date, $end_date))->groupBy('det.mesin', 'new_date')->orderBy('det.mesin', 'asc')->orderBy('new_date', 'asc')->get();

        $array = [];
        $tahun = date("Y");
        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];
        $arr_bulan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $arr_tahun = [$tahun - 2, $tahun - 1, $tahun];

        for($i = 0; $i < count($arr_mesin); $i++){
            $array[$arr_mesin[$i]] = [];
            for($j = 0; $j < count($arr_tahun); $j++){
                $array[$arr_mesin[$i]][$arr_tahun[$j]] = [];
                for($k = 0; $k < count($arr_bulan); $k++){
                    $array[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]] = null;
                }
            }
        }

        foreach($data_produksi as $data_produksi){
            $array[$data_produksi->mesin][$data_produksi->tahun][$data_produksi->bulan] = $data_produksi->total;
        }

        return Response()->json($array);
    }

    public function grafikDataLaporanRataProduksi(Request $request){
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');

        $sa = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 1)->groupBy('mesin', 'new_date')->get();

        $sb = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 2)->groupBy('mesin', 'new_date')->get();

        $mixer = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 3)->groupBy('mesin', 'new_date')->get();

        $ra = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 4)->groupBy('mesin', 'new_date')->get();

        $rb = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 5)->groupBy('mesin', 'new_date')->get();

        $rc = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 6)->groupBy('mesin', 'new_date')->get();

        $rd = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 7)->groupBy('mesin', 'new_date')->get();

        $re = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 8)->groupBy('mesin', 'new_date')->get();

        $rf = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 9)->groupBy('mesin', 'new_date')->get();

        $rg = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->where('mesin', 10)->groupBy('mesin', 'new_date')->get();

        $total = DB::table('laporan_hasil_produksi_detail')->select(DB::raw("sum(jumlah_tonase) / count(distinct(tanggal_laporan_produksi)) as tonase"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y-%m') AS new_date"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join('laporan_hasil_produksi', 'laporan_hasil_produksi.nomor_laporan_produksi', '=', 'laporan_hasil_produksi_detail.nomor_laporan_produksi')->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->groupBy('new_date')->get();

        return Response()->json(['SA' => $sa, 'SB' => $sb, 'Mixer' => $mixer, 'RA' => $ra, 'RB' => $rb, 'RC' => $rc, 'RD' => $rd, 'RE' => $re, 'RF' => $rf, 'RG' => $rg, 'Total' => $total]);
    }

    public function excelLaporanTotalProduksi(){
        $start_date = date('Y', strtotime('-2 years'));
        $end_date = date('Y');

        $nama_file = 'Laporan Total Produksi Tahun ' . $start_date . ' - ' . $end_date . '.xlsx';

        return Excel::download(new LaporanTotalProduksiExport, $nama_file);
    }

    public function excelLaporanRataProduksi(){
        $start_date = date('Y', strtotime('-2 years'));
        $end_date = date('Y');

        $nama_file = 'Laporan Rata-Rata Produksi Tahun ' . $start_date . ' - ' . $end_date . '.xlsx';

        return Excel::download(new LaporanRataProduksiExport, $nama_file);
    }

    public function viewDataLaporanMasalahMesin($mesin, $from_date, $to_date){
        $currentMonth = date('m');
        $currentYear = date('Y');
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);
        $val_mesin = $this->decrypt($mesin);

        $arrayForTable = [];
        $arrayForTableA = [];
        $arrayForTableB = [];
        $arrayForTableC = [];

        $tanggal_dari = date('Y-m-01', strtotime($val_from_date));
        $tanggal_ke = date('Y-m-31', strtotime($val_to_date));

        if($val_from_date == ''){
            $data_lab = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'lab.jam_waktu', 'tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.ssa', 'lab.d50', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lab.mesin', $val_mesin)->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->orderBy('lap.tanggal_laporan_produksi', 'asc')->orderBy('lab.jam_waktu', 'asc')->get();

            $data_tonase = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', DB::raw("sum(det.jumlah_tonase) as produksi_tonase"), 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_detail as det", "lap.nomor_laporan_produksi", "=", "det.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'det.mesin')->where('det.mesin', $val_mesin)->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->orderBy('lap.tanggal_laporan_produksi', 'asc')->groupBy('lap.tanggal_laporan_produksi')->get();

            $data_masalah = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('mes.mesin', $val_mesin)->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->orderBy('lap.tanggal_laporan_produksi', 'asc')->get();

            for($i = 1; $i <=  date('t'); $i++)
            {
                $arrayForTable[date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                $arrayForTableA[date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                $arrayForTableB[date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                $arrayForTableC[date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
            }
        }else{
            $data_lab = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'lab.jam_waktu', 'tbl_mesin.name as mesin', 'lab.mesh', 'lab.rpm', 'lab.ssa', 'lab.d50', 'lab.d98', 'lab.cie86', 'lab.iso2470', 'lab.moisture', 'lab.residue', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join("laporan_hasil_produksi_lab as lab", "lap.nomor_laporan_produksi", "=", "lab.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->where('lab.mesin', $val_mesin)->whereBetween('tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->orderBy('lap.tanggal_laporan_produksi', 'asc')->orderBy('lab.jam_waktu', 'asc')->get();

            $data_tonase = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', DB::raw("sum(det.jumlah_tonase) as produksi_tonase"), 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_detail as det", "lap.nomor_laporan_produksi", "=", "det.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'det.mesin')->where('det.mesin', $val_mesin)->whereBetween('tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->orderBy('lap.tanggal_laporan_produksi', 'asc')->groupBy('lap.tanggal_laporan_produksi')->get();

            $data_masalah = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin')->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('mes.mesin', $val_mesin)->whereBetween('tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->orderBy('lap.tanggal_laporan_produksi', 'asc')->get();

            $d1 = strtotime($val_from_date);
            $d2 = strtotime($val_to_date);
            $min_date = min($d1, $d2);
            $max_date = max($d1, $d2);
            $count_month = 0;

            while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
                $count_month++;
            }

            for($j = 0; $j <= $count_month; $j++)
            {
                $tanggal = date('Y-m', strtotime($val_from_date. ' +'.$j.' month'));
                for($i = 1; $i <= date('t', strtotime($tanggal)); $i++)
                {
                    $arrayForTable[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                    $arrayForTableA[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                    $arrayForTableB[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                    $arrayForTableC[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                }
            }
        }

        foreach($data_lab as $data_lab){
            $temp = [];
            $temp['jam_waktu'] = $data_lab->jam_waktu;
            $temp['mesh'] = $data_lab->mesh;
            $temp['rpm'] = $data_lab->rpm;
            $temp['ssa'] = $data_lab->ssa;
            $temp['d50'] = $data_lab->d50;
            $temp['d98'] = $data_lab->d98;
            $temp['cie86'] = $data_lab->cie86;
            $temp['iso2470'] = $data_lab->iso2470;
            $temp['moisture'] = $data_lab->moisture;
            $temp['spek_whiteness'] = $data_lab->spek_whiteness;
            $temp['spek_moisture'] = $data_lab->spek_moisture;
            $temp['spek_residue'] = $data_lab->spek_residue;
            $temp['residue'] = $data_lab->residue;
            $arrayForTable[$data_lab->tanggal][] = $temp;
        }

        foreach($data_masalah as $data_masalah){
            if($data_masalah->kategori == 1){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableA[$data_masalah->tanggal]['masalah'][] = $temp;
            }else if($data_masalah->kategori == 2){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableB[$data_masalah->tanggal]['masalah'][] = $temp;
            }else if($data_masalah->kategori == 3){
                $temp = [];
                $temp['jam_awal'] = $data_masalah->jam_awal;
                $temp['jam_akhir'] = $data_masalah->jam_akhir;
                $temp['masalah'] = $data_masalah->masalah;
                $arrayForTableC[$data_masalah->tanggal]['masalah'][] = $temp;
            }
        }

        foreach($data_tonase as $data_tonase){
            $arrayForTableA[$data_tonase->tanggal]['tonase'] = $data_tonase->produksi_tonase;
        }

        return Response()->json(['data_lab' => $arrayForTable, 'data_masalah_a' => $arrayForTableA, 'data_masalah_b' => $arrayForTableB, 'data_masalah_c' => $arrayForTableC]);
    }

    public function excelTeknikLaporanMasalahMesin($from_date, $to_date){
        $currentMonth = date('F');
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        if($val_from_date == $val_to_date){
            $nama_file = 'Laporan Masalah Mesin Bulan ' . $currentMonth . '.xlsx';
        }else{
            $nama_file = 'Laporan Masalah Mesin Bulan ' . $val_from_date . ' - ' . $val_to_date . '.xlsx';
        }

        return Excel::download(new LaporanMasalahMesinSheetExport($val_from_date, $val_to_date), $nama_file);
    }

    public function grafikDataLaporanMasalahMesinMajor(Request $request){
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        $d1 = strtotime($start_date);
        $d2 = strtotime($end_date);
        $min_date = min($d1, $d2);
        $max_date = max($d1, $d2);
        $count_month = 0;

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $count_month++;
        }

        for($k = 1; $k <= count($data_mesin); $k++){
            ${$data_mesin[$k - 1]} = [];

            for($j = 0; $j <= $count_month; $j++)
            {
                $tanggal = date('Y-m', strtotime($start_date. ' +'.$j.' month'));
                for($i = 1; $i <= date('t', strtotime($tanggal)); $i++)
                {
                    ${$data_mesin[$k - 1]}[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                }
            }
        }

        for($j = 1; $j <= count($data_mesin); $j++){

            ${"data_" . $data_mesin[$j - 1]} = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin', DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('mes.mesin', $j)->where('kategori', 1)->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->orderBy('lap.tanggal_laporan_produksi', 'asc')->get();

            foreach(${"data_" . $data_mesin[$j - 1]} as $data){
                $temp = [];
                $temp['jam_awal'] = $data->jam_awal;
                $temp['jam_akhir'] = $data->jam_akhir;
                $temp['masalah'] = $data->masalah;
                $temp['tahun'] = $data->tahun;
                $temp['bulan'] = $data->bulan;
                ${$data_mesin[$j - 1]}[$data->tanggal]['masalah'][] = $temp;
            }
        }

        return Response()->json(['SA' => $sa, 'SB' => $sb, 'Mixer' => $mixer, 'RA' => $ra, 'RB' => $rb, 'RC' => $rc, 'RD' => $rd, 'RE' => $re, 'RF' => $rf, 'RG' => $rg]);
    }

    public function grafikDataLaporanMasalahMesinMinor(Request $request){
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        $d1 = strtotime($start_date);
        $d2 = strtotime($end_date);
        $min_date = min($d1, $d2);
        $max_date = max($d1, $d2);
        $count_month = 0;

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $count_month++;
        }

        for($k = 1; $k <= count($data_mesin); $k++){
            ${$data_mesin[$k - 1]} = [];

            for($j = 0; $j <= $count_month; $j++)
            {
                $tanggal = date('Y-m', strtotime($start_date. ' +'.$j.' month'));
                for($i = 1; $i <= date('t', strtotime($tanggal)); $i++)
                {
                    ${$data_mesin[$k - 1]}[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                }
            }
        }

        for($j = 1; $j <= count($data_mesin); $j++){

            ${"data_" . $data_mesin[$j - 1]} = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin', DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('mes.mesin', $j)->where('kategori', 2)->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->orderBy('lap.tanggal_laporan_produksi', 'asc')->get();

            foreach(${"data_" . $data_mesin[$j - 1]} as $data){
                $temp = [];
                $temp['jam_awal'] = $data->jam_awal;
                $temp['jam_akhir'] = $data->jam_akhir;
                $temp['masalah'] = $data->masalah;
                $temp['tahun'] = $data->tahun;
                $temp['bulan'] = $data->bulan;
                ${$data_mesin[$j - 1]}[$data->tanggal]['masalah'][] = $temp;
            }
        }

        return Response()->json(['SA' => $sa, 'SB' => $sb, 'Mixer' => $mixer, 'RA' => $ra, 'RB' => $rb, 'RC' => $rc, 'RD' => $rd, 'RE' => $re, 'RF' => $rf, 'RG' => $rg]);
    }

    public function grafikDataLaporanMasalahMesinLain(Request $request){
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        $d1 = strtotime($start_date);
        $d2 = strtotime($end_date);
        $min_date = min($d1, $d2);
        $max_date = max($d1, $d2);
        $count_month = 0;

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $count_month++;
        }

        for($k = 1; $k <= count($data_mesin); $k++){
            ${$data_mesin[$k - 1]} = [];

            for($j = 0; $j <= $count_month; $j++)
            {
                $tanggal = date('Y-m', strtotime($start_date. ' +'.$j.' month'));
                for($i = 1; $i <= date('t', strtotime($tanggal)); $i++)
                {
                    ${$data_mesin[$k - 1]}[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                }
            }
        }

        for($j = 1; $j <= count($data_mesin); $j++){

            ${"data_" . $data_mesin[$j - 1]} = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin', DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('mes.mesin', $j)->where('kategori', 3)->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->orderBy('lap.tanggal_laporan_produksi', 'asc')->get();

            foreach(${"data_" . $data_mesin[$j - 1]} as $data){
                $temp = [];
                $temp['jam_awal'] = $data->jam_awal;
                $temp['jam_akhir'] = $data->jam_akhir;
                $temp['masalah'] = $data->masalah;
                $temp['tahun'] = $data->tahun;
                $temp['bulan'] = $data->bulan;
                ${$data_mesin[$j - 1]}[$data->tanggal]['masalah'][] = $temp;
            }
        }

        return Response()->json(['SA' => $sa, 'SB' => $sb, 'Mixer' => $mixer, 'RA' => $ra, 'RB' => $rb, 'RC' => $rc, 'RD' => $rd, 'RE' => $re, 'RF' => $rf, 'RG' => $rg]);
    }

    public function grafikDataLaporanMasalahMesinTotal(Request $request){
        $start_date = date('Y-1-1', strtotime('-2 years'));
        $end_date = date('Y-12-31');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];
        $data_kategori = ['major', 'minor', 'lain'];

        $d1 = strtotime($start_date);
        $d2 = strtotime($end_date);
        $min_date = min($d1, $d2);
        $max_date = max($d1, $d2);
        $count_month = 0;

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $count_month++;
        }

        for($k = 1; $k <= count($data_mesin); $k++){
            ${$data_mesin[$k - 1]} = [];
            for($m = 1; $m <= count($data_kategori); $m++){
                ${$data_kategori[$m - 1]} = [];

                for($j = 0; $j <= $count_month; $j++)
                {
                    $tanggal = date('Y-m', strtotime($start_date. ' +'.$j.' month'));
                    for($i = 1; $i <= date('t', strtotime($tanggal)); $i++)
                    {
                        ${$data_kategori[$m - 1]}[date('Y', strtotime($tanggal)) . "-" . date('m', strtotime($tanggal)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT)] = [];
                    }
                }

                ${$data_mesin[$k - 1]}[$m - 1] = ${$data_kategori[$m - 1]};
            }
        }

        for($j = 1; $j <= count($data_mesin); $j++){
            for($m = 1; $m <= count($data_kategori); $m++){
                ${"data_" . $data_kategori[$m - 1]} = DB::table('laporan_hasil_produksi as lap')->select('lap.tanggal_laporan_produksi as tanggal', 'mes.jam_awal', 'mes.jam_akhir', 'mes.masalah', 'mes.kategori', 'tbl_mesin.name as mesin', DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%Y') AS tahun"), DB::raw("DATE_FORMAT(tanggal_laporan_produksi, '%c') AS bulan"))->join("laporan_hasil_produksi_mesin as mes", "lap.nomor_laporan_produksi", "=", "mes.nomor_laporan_produksi")->join('tbl_mesin', 'tbl_mesin.id', '=', 'mes.mesin')->where('mes.mesin', $j)->where('kategori', $m)->whereBetween('tanggal_laporan_produksi', array($start_date, $end_date))->orderBy('lap.tanggal_laporan_produksi', 'asc')->get();

                foreach(${"data_" . $data_kategori[$m - 1]} as $data){
                    $temp = [];
                    $temp['jam_awal'] = $data->jam_awal;
                    $temp['jam_akhir'] = $data->jam_akhir;
                    $temp['masalah'] = $data->masalah;
                    $temp['tahun'] = $data->tahun;
                    $temp['bulan'] = $data->bulan;
                    ${$data_mesin[$j - 1]}[$m - 1][$data->tanggal]['masalah'][] = $temp;
                }
            }
        }

        return Response()->json(['SA' => $sa, 'SB' => $sb, 'Mixer' => $mixer, 'RA' => $ra, 'RB' => $rb, 'RC' => $rc, 'RD' => $rd, 'RE' => $re, 'RF' => $rf, 'RG' => $rg]);
    }

    public function uploadExcelLaporanProduksi(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new LaporanHasilProduksiImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        return redirect('produksi/laporan_hasil_produksi')->with('alert','Sukses Menambahkan Data');
    }

    public function uploadLaporanHasilLab(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new LaporanHasilLabImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Lab Input Laporan Hasil Lab Nomor ' . $import->getNomorLaporan1()]);
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Lab Input Laporan Hasil Lab Nomor ' . $import->getNomorLaporan2()]);
        
        if($import->getDuplikat() == 0){
            return redirect('lab/laporan_lab')->with('alert','Data Duplikat, Data Sudah Ada');
        }else{
            return redirect('lab/laporan_lab')->with('alert','Sukses Menambahkan Data');
        }
    }

    public function getDetailReferensi($nomor_rencana_produksi){
        $val_nomor_rencana_produksi = $this->decrypt($nomor_rencana_produksi);

        $detail = DB::table('rencana_produksi_detail')->select("mesin", "rencana_produksi_detail.jenis_produk", "products.weight", "jumlah_sak", "jumlah_tonase")->join('products', 'products.jenis_produk', '=', 'rencana_produksi_detail.jenis_produk')->where("nomor_rencana_produksi", $val_nomor_rencana_produksi)->get();

        return Response()->json($detail);
    }

    public function checkNomorRencanaReferensi($nomor_rencana_produksi){
        $val_nomor_rencana_produksi = $this->decrypt($nomor_rencana_produksi);
        $varTrue = false;

        $detail = DB::table('rencana_produksi')->select("nomor_rencana_produksi")->where("nomor_rencana_produksi", $val_nomor_rencana_produksi)->first();

        if(isset($detail->nomor_rencana_produksi)){
            $varTrue = true;
        }

        return Response()->json($varTrue);
    }
}
