<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Response;
use PDF;

class RencanaProduksiController extends Controller
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

    public function viewPageRencanaProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('rencana_produksi');
        }
    }

    public function indexRencanaProduksi(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $rencana_produksi_table = DB::table('rencana_produksi as ren')->select('ren.nomor_rencana_produksi', 'ren.status', 'ren.tanggal_rencana', DB::raw('sum(det.jumlah_sak) as total_sak'), DB::raw('sum(det.jumlah_tonase) / 1000 as total_tonase'))->join("rencana_produksi_detail as det", "det.nomor_rencana_produksi", "=", "ren.nomor_rencana_produksi")->join("tbl_mesin as mesin", "mesin.id", "=", "det.mesin")->whereBetween('ren.tanggal_rencana', array($request->from_date, $request->to_date))->groupBy('ren.tanggal_rencana')->get();
            }else{
                $rencana_produksi_table = DB::table('rencana_produksi as ren')->select('ren.nomor_rencana_produksi', 'ren.status', 'ren.tanggal_rencana', DB::raw('sum(det.jumlah_sak) as total_sak'), DB::raw('sum(det.jumlah_tonase) / 1000 as total_tonase'))->join("rencana_produksi_detail as det", "det.nomor_rencana_produksi", "=", "ren.nomor_rencana_produksi")->join("tbl_mesin as mesin", "mesin.id", "=", "det.mesin")->whereRaw('MONTH(ren.tanggal_rencana) = ?',[$currentMonth])->whereRaw('YEAR(ren.tanggal_rencana) = ?',[$currentYear])->groupBy('ren.tanggal_rencana')->get();
            }

            return datatables()->of($rencana_produksi_table)->addIndexColumn()->addColumn('action', 'button/action_button_rencana_produksi')->rawColumns(['action'])->make(true);
        }
        return view('rencana_produksi');
    }

    public function getMesin(){
        $mesin = DB::table('tbl_mesin')->select("id", "name")->get();

        return Response()->json($mesin);
    }

    public function getJenisProduk(){
        $mesin = DB::table('products')->select("jenis_produk")->orderBy("jenis_produk", "asc")->get();

        return Response()->json($mesin);
    }

    public function getReferensi(){
        $referensi = DB::table('production_order')->select("nomor_order_receipt")->where("dropdown_rencana_produksi", 1)->orderBy("nomor_order_receipt", "asc")->get();

        return Response()->json($referensi);
    }

    public function getDetailReferensi($nomor_order_receipt){
        $val_nomor_order_receipt = $this->decrypt($nomor_order_receipt);

        $data_detail = DB::table('production_order')->select("nomor_production_order")->where("nomor_order_receipt", $val_nomor_order_receipt)->first();

        if(isset($data_detail->nomor_production_order)){
            $detail = DB::table('production_order_detail as pod')->select("cus.custname", "po.tanggal_order", "pod.tanggal_kirim", "prd.jenis_produk", DB::raw("(pod.qty / prd.weight) as jumlah_sak"))->join("production_order as po", "po.nomor_production_order", "=", "pod.nomor_production_order")->join("customers as cus", "cus.custid", "=", "po.custid")->join("products as prd", "prd.kode_produk", "=", "pod.kode_produk")->where("pod.nomor_production_order", $data_detail->nomor_production_order)->get();
        }else{
            $detail = [];
        }

        return Response()->json($detail);
    }

    public function saveRencanaProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        $cek_data_rencana = DB::table('rencana_produksi')->select('nomor_rencana_produksi')->where('nomor_rencana_produksi', 'like', 'WOS' . $tanggal . '%')->orderBy('nomor_rencana_produksi', 'asc')->distinct()->get();

        if($cek_data_rencana){
            $data_rencana_count = $cek_data_rencana->count();
            if($data_rencana_count > 0){
                $num = (int) substr($cek_data_rencana[$cek_data_rencana->count() - 1]->nomor_rencana_produksi, 8);
                if($data_rencana_count != $num){
                    $nomor_rencana_produksi = ++$cek_data_rencana[$cek_data_rencana->count() - 1]->nomor_rencana_produksi;
                }else{
                    if($data_rencana_count < 9){
                        $nomor_rencana_produksi = "WOS" . $tanggal . "-000" . ($data_rencana_count + 1);
                    }else if($data_rencana_count >= 9 && $data_rencana_count < 99){
                        $nomor_rencana_produksi = "WOS" . $tanggal . "-00" . ($data_rencana_count + 1);
                    }else if($data_rencana_count >= 99 && $data_rencana_count < 999){
                        $nomor_rencana_produksi = "WOS" . $tanggal . "-0" . ($data_rencana_count + 1);
                    }else{
                        $nomor_rencana_produksi = "WOS" . $tanggal . '-' . ($data_rencana_count + 1);
                    }
                }
            }else{
                $nomor_rencana_produksi = "WOS" . $tanggal . "-0001";
            }
        }else{
            $nomor_rencana_produksi = "WOS" . $tanggal . "-0001";
        }

        $data = DB::table('rencana_produksi')->insert(["nomor_rencana_produksi" => $nomor_rencana_produksi, "id_user" => Session::get('id_user_admin'), "keterangan" => $request->keterangan, "tanggal_rencana" => $request->tanggal_rencana, "tanggal_input" => date('Y-m-d'), 'status' => 1, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), 'created_by' => Session::get('id_user_admin'), 'updated_by' => Session::get('id_user_admin')]);

        if(isset($request->referensi)){
            $number_ref = count($request->referensi);

            for($i = 0; $i < $number_ref; $i++){
                DB::table('production_order')->where('nomor_order_receipt', $request->referensi[$i])->update(['dropdown_rencana_produksi' => 2]);

                $cek_data_referensi = DB::table('rencana_produksi_referensi')->select('nomor_rencana_produksi_referensi')->where('nomor_rencana_produksi_referensi', 'like', 'WOSR' . $tanggal . '%')->orderBy('nomor_rencana_produksi_referensi', 'asc')->distinct()->get();

                if($cek_data_referensi){
                    $data_referensi_count = $cek_data_referensi->count();
                    if($data_referensi_count > 0){
                        $num = (int) substr($cek_data_referensi[$cek_data_referensi->count() - 1]->nomor_rencana_produksi_referensi, 9);
                        if($data_referensi_count != $num){
                            $nomor_rencana_produksi_referensi = ++$cek_data_referensi[$cek_data_referensi->count() - 1]->nomor_rencana_produksi_referensi;
                        }else{
                            if($data_referensi_count < 9){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-00000" . ($data_referensi_count + 1);
                            }else if($data_referensi_count >= 9 && $data_referensi_count < 99){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-0000" . ($data_referensi_count + 1);
                            }else if($data_referensi_count >= 99 && $data_referensi_count < 999){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-000" . ($data_referensi_count + 1);
                            }else if($data_referensi_count >= 999 && $data_referensi_count < 9999){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-00" . ($data_referensi_count + 1);
                            }else if($data_referensi_count >= 9999 && $data_detail_count < 99999){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-0" . ($data_referensi_count + 1);
                            }else{
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . '-' . ($data_referensi_count + 1);
                            }
                        }
                    }else{
                        $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-000001";
                    }
                }else{
                    $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-000001";
                }

                DB::table('rencana_produksi_referensi')->insert(["nomor_rencana_produksi_referensi" => $nomor_rencana_produksi_referensi, "nomor_rencana_produksi" => $nomor_rencana_produksi, "nomor_referensi" => $request->referensi[$i]]);
            }
        }

        for($j = 1; $j <= count($data_mesin); $j++){
            $number = count($request->{"jenis_produk_" . $data_mesin[$j - 1]});

            for($i = 0; $i < $number; $i++){
                if(!empty($request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i])){
                    $cek_data_detail = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail')->where('nomor_rencana_produksi_detail', 'like', 'WOSD' . $tanggal . '%')->orderBy('nomor_rencana_produksi_detail', 'asc')->distinct()->get();

                    if($cek_data_detail){
                        $data_detail_count = $cek_data_detail->count();
                        if($data_detail_count > 0){
                            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_rencana_produksi_detail, 9);
                            if($data_detail_count != $num){
                                $nomor_rencana_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_rencana_produksi_detail;
                            }else{
                                if($data_detail_count < 9){
                                    $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-00000" . ($data_detail_count + 1);
                                }else if($data_detail_count >= 9 && $data_detail_count < 99){
                                    $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-0000" . ($data_detail_count + 1);
                                }else if($data_detail_count >= 99 && $data_detail_count < 999){
                                    $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-000" . ($data_detail_count + 1);
                                }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                                    $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-00" . ($data_detail_count + 1);
                                }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                                    $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-0" . ($data_detail_count + 1);
                                }else{
                                    $nomor_rencana_produksi_detail = "WOSD" . $tanggal . '-' . ($data_detail_count + 1);
                                }
                            }
                        }else{
                            $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-000001";
                        }
                    }else{
                        $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-000001";
                    }

                    DB::table('rencana_produksi_detail')->insert(["nomor_rencana_produksi_detail" => $nomor_rencana_produksi_detail, "nomor_rencana_produksi" => $nomor_rencana_produksi, "mesin" => $j, "jenis_produk" => $request->{"jenis_produk_" . $data_mesin[$j - 1]}[$i], "jumlah_sak" => $request->{"jumlah_sak_" . $data_mesin[$j - 1]}[$i], "jumlah_tonase" => $request->{"jumlah_tonase_" . $data_mesin[$j - 1]}[$i]]);
                }
            }
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_rencana_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Rencana Produksi Dengan Kode ' . $nomor_rencana_produksi]);

        return Response()->json($arr);
    }

    public function saveSpekRencanaProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data = DB::table('rencana_produksi')->where('nomor_rencana_produksi', $request->nomor_rencana_produksi_spek)->update(['status' => 2, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        for($j = 1; $j <= count($data_mesin); $j++){
            $number = count($request->{"spek_rpm_" . $data_mesin[$j - 1]});

            for($i = 0; $i < $number; $i++){
                if(!empty($request->{"spek_rpm_" . $data_mesin[$j - 1]}[$i])){
                    $cek_data_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek')->where('nomor_rencana_produksi_spek', 'like', 'WOSS' . $tanggal . '%')->orderBy('nomor_rencana_produksi_spek', 'asc')->distinct()->get();

                    if($cek_data_spek){
                        $data_spek_count = $cek_data_spek->count();
                        if($data_spek_count > 0){
                            $num = (int) substr($cek_data_spek[$cek_data_spek->count() - 1]->nomor_rencana_produksi_spek, 9);
                            if($data_spek_count != $num){
                                $nomor_rencana_produksi_spek = ++$cek_data_spek[$cek_data_spek->count() - 1]->nomor_rencana_produksi_spek;
                            }else{
                                if($data_spek_count < 9){
                                    $nomor_rencana_produksi_spek = "WOSS" . $tanggal . "-00000" . ($data_spek_count + 1);
                                }else if($data_spek_count >= 9 && $data_spek_count < 99){
                                    $nomor_rencana_produksi_spek = "WOSS" . $tanggal . "-0000" . ($data_spek_count + 1);
                                }else if($data_spek_count >= 99 && $data_spek_count < 999){
                                    $nomor_rencana_produksi_spek = "WOSS" . $tanggal . "-000" . ($data_spek_count + 1);
                                }else if($data_spek_count >= 999 && $data_spek_count < 9999){
                                    $nomor_rencana_produksi_spek = "WOSS" . $tanggal . "-00" . ($data_spek_count + 1);
                                }else if($data_spek_count >= 9999 && $data_spek_count < 99999){
                                    $nomor_rencana_produksi_spek = "WOSS" . $tanggal . "-0" . ($data_spek_count + 1);
                                }else{
                                    $nomor_rencana_produksi_spek = "WOSS" . $tanggal . "-" . ($data_spek_count + 1);
                                }
                            }
                        }else{
                            $nomor_rencana_produksi_spek = "WOSS" . $tanggal . "-000001";
                        }
                    }else{
                        $nomor_rencana_produksi_spek = "WOSS" . $tanggal . "-000001";
                    }

                    DB::table('rencana_produksi_spek')->insert(["nomor_rencana_produksi_spek" => $nomor_rencana_produksi_spek, "nomor_rencana_produksi" => $request->nomor_rencana_produksi_spek, "mesin" => $j, "rpm" => $request->{"spek_rpm_" . $data_mesin[$j - 1]}[$i], "particle_size" => $request->{"spek_particle_size_" . $data_mesin[$j - 1]}[$i], "ssa" => $request->{"spek_ssa_" . $data_mesin[$j - 1]}[$i], "whiteness" => $request->{"spek_whiteness_" . $data_mesin[$j - 1]}[$i], "residue" => $request->{"spek_residue_" . $data_mesin[$j - 1]}[$i], "moisture" => $request->{"spek_moisture_" . $data_mesin[$j - 1]}[$i]]);
                }
            }
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_rencana_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Rencana Produksi Data Spek Dengan Kode ' . $request->nomor_rencana_produksi_spek]);

        return Response()->json($arr);
    }

    public function lihatCalendarRencanaProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $data = DB::select("select rpd.tanggal_rencana, concat('Mesin ', rpd.name_machine, ' : <>', GROUP_CONCAT(rpd.nama_mesin separator '<>')) as nama_mesin from rencana_produksi rp, (select rp2.tanggal_rencana, mes.name as name_machine, concat(rpd2.jenis_produk, ' ', '(', sum(rpd2.jumlah_sak), ')') as nama_mesin from rencana_produksi rp2 join rencana_produksi_detail rpd2 on rp2.nomor_rencana_produksi = rpd2.nomor_rencana_produksi join tbl_mesin mes on mes.id = rpd2.mesin group by rp2.tanggal_rencana, rpd2.mesin, rpd2.jenis_produk) rpd where rp.id_user = ? and rpd.tanggal_rencana = rp.tanggal_rencana group by rpd.name_machine, rp.tanggal_rencana", [Session::get('id_user_admin')]);

            return view('lihat_calendar_rencana_produksi', ['data' => $data]);
        }
    }

    public function detailRencana($nomor_rencana_produksi){
        $val_nomor_rencana_produksi = $this->decrypt($nomor_rencana_produksi);
        $arrayForTable = [];

    	$data = DB::table('rencana_produksi')->select('tanggal_rencana', 'keterangan')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->get();

        $data_referensi = DB::table('rencana_produksi_referensi')->select('nomor_referensi')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->get();

        foreach($data_referensi as $data_referensi){
            $arrayForTable[] = $data_referensi->nomor_referensi;
        }

        $sa = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 1)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $sb = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 2)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $mixer = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 3)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $ra = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 4)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $rb = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 5)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $rc = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 6)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $rd = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 7)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $re = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 8)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $rf = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 9)->groupBy('mesin')->groupBy('jenis_produk')->get();

        $rg = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'mes.name as mesin', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_detail.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 10)->groupBy('mesin')->groupBy('jenis_produk')->get();

        return Response()->json(['data_ren' => $data, 'data_referensi' => $arrayForTable, 'sa' => $sa, 'sb' => $sb, 'mixer' => $mixer, 'ra' => $ra, 'rb' => $rb, 'rc' => $rc, 'rd' => $rd, 're' => $re, 'rf' => $rf, 'rg' => $rg]);
    }

    public function detailSpekRencana($nomor_rencana_produksi){
        $val_nomor_rencana_produksi = $this->decrypt($nomor_rencana_produksi);

        $sa = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 1)->groupBy('mesin')->get();

        $sb = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 2)->groupBy('mesin')->get();

        $mixer = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 3)->groupBy('mesin')->get();

        $ra = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 4)->groupBy('mesin')->get();

        $rb = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 5)->groupBy('mesin')->get();

        $rc = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 6)->groupBy('mesin')->get();

        $rd = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 7)->groupBy('mesin')->get();

        $re = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 8)->groupBy('mesin')->get();

        $rf = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 9)->groupBy('mesin')->get();

        $rg = DB::table('rencana_produksi_spek')->select('mes.name as mesin', 'rpm', 'particle_size', 'ssa', 'whiteness', 'residue', 'moisture')->join('tbl_mesin as mes', 'mes.id', '=', 'rencana_produksi_spek.mesin')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 10)->groupBy('mesin')->get();

        return Response()->json(['sa' => $sa, 'sb' => $sb, 'mixer' => $mixer, 'ra' => $ra, 'rb' => $rb, 'rc' => $rc, 'rd' => $rd, 're' => $re, 'rf' => $rf, 'rg' => $rg]);
    }

    public function printRencanaProduksi($nomor_rencana_produksi){
        $val_nomor_rencana_produksi = Crypt::decrypt($nomor_rencana_produksi);

        $data = DB::table('rencana_produksi')->select('nomor_rencana_produksi', 'rencana_produksi.id_user', 'keterangan', 'tanggal_rencana', 'tanggal_input', 'users.nama_admin')->join('users', 'users.id_user', '=', 'rencana_produksi.id_user')->where('nomor_rencana_produksi', $val_nomor_rencana_produksi)->get();

        $sa = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 1)->get();

        $sb = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 2)->get();

        $mixer = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 3)->get();

        $ra = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 4)->get();

        $rb = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 5)->get();

        $rc = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 6)->get();

        $rd = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 7)->get();

        $re = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 8)->get();

        $rf = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 9)->get();

        $rg = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail', 'jenis_produk', 'jumlah_sak', 'jumlah_tonase')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_detail.nomor_rencana_produksi')->where('rencana_produksi_detail.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 10)->get();

        $sa_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 1)->get();

        $sb_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 2)->get();

        $mixer_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 3)->get();

        $ra_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 4)->get();

        $rb_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 5)->get();

        $rc_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 6)->get();

        $rd_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 7)->get();

        $re_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 8)->get();

        $rf_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 9)->get();

        $rg_spek = DB::table('rencana_produksi_spek')->select('nomor_rencana_produksi_spek', 'rpm', 'particle_size', 'ssa', 'whiteness', 'moisture', 'residue')->join('rencana_produksi', 'rencana_produksi.nomor_rencana_produksi', '=', 'rencana_produksi_spek.nomor_rencana_produksi')->where('rencana_produksi_spek.nomor_rencana_produksi', $val_nomor_rencana_produksi)->where('mesin', 10)->get();

        $pdf = PDF::loadView('print_rencana_produksi', ['data' => $data, 'sa' => $sa, 'sb' => $sb, 'mixer' => $mixer, 'ra' => $ra, 'rb' => $rb, 'rc' => $rc, 'rd' => $rd, 're' => $re, 'rf' => $rf, 'rg' => $rg, 'maxi' => max($sa, $sb, $mixer, $ra, $rb, $rc, $rd, $re, $rf, $rg), 'sa_spek' => $sa_spek, 'sb_spek' => $sb_spek, 'mixer_spek' => $mixer_spek, 'ra_spek' => $ra_spek, 'rb_spek' => $rb_spek, 'rc_spek' => $rc_spek, 'rd_spek' => $rd_spek, 're_spek' => $re_spek, 'rf_spek' => $rf_spek, 'rg_spek' => $rg_spek])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function deleteRencanaProduksiDetail(Request $request){
        $data = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi', 'jenis_produk')->where('nomor_rencana_produksi_detail', $request->get('nomor'))->first();

        $hapus = DB::table('rencana_produksi_detail')->where('nomor_rencana_produksi_detail', $request->get('nomor'))->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_rencana_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Rencana Delete Produk ' . $data->jenis_produk . 'Pada Data Nomor ' . $data->nomor_rencana_produksi]);
    }

    public function editRencanaProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('rencana_produksi')->where("nomor_rencana_produksi", $request->edit_nomor_rencana_produksi)->update(["updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        $data_ref = DB::table('rencana_produksi_referensi')->select("nomor_referensi")->where("nomor_rencana_produksi", $request->edit_nomor_rencana_produksi)->get();

        foreach($data_ref as $data_ref){
            DB::table('production_order')->where('nomor_order_receipt', $data_ref->nomor_referensi)->update(['dropdown_rencana_produksi' => 1]);
        }

        DB::table('rencana_produksi_referensi')->where("nomor_rencana_produksi", $request->edit_nomor_rencana_produksi)->delete();

        if(isset($request->edit_referensi)){
            $number_ref = count($request->edit_referensi);

            for($i = 0; $i < $number_ref; $i++){
                DB::table('production_order')->where('nomor_order_receipt', $request->edit_referensi[$i])->update(['dropdown_rencana_produksi' => 2]);

                $cek_data_referensi = DB::table('rencana_produksi_referensi')->select('nomor_rencana_produksi_referensi')->where('nomor_rencana_produksi_referensi', 'like', 'WOSR' . $tanggal . '%')->orderBy('nomor_rencana_produksi_referensi', 'asc')->distinct()->get();

                if($cek_data_referensi){
                    $data_referensi_count = $cek_data_referensi->count();
                    if($data_referensi_count > 0){
                        $num = (int) substr($cek_data_referensi[$cek_data_referensi->count() - 1]->nomor_rencana_produksi_referensi, 9);
                        if($data_referensi_count != $num){
                            $nomor_rencana_produksi_referensi = ++$cek_data_referensi[$cek_data_referensi->count() - 1]->nomor_rencana_produksi_referensi;
                        }else{
                            if($data_referensi_count < 9){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-00000" . ($data_referensi_count + 1);
                            }else if($data_referensi_count >= 9 && $data_referensi_count < 99){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-0000" . ($data_referensi_count + 1);
                            }else if($data_referensi_count >= 99 && $data_referensi_count < 999){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-000" . ($data_referensi_count + 1);
                            }else if($data_referensi_count >= 999 && $data_referensi_count < 9999){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-00" . ($data_referensi_count + 1);
                            }else if($data_referensi_count >= 9999 && $data_detail_count < 99999){
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-0" . ($data_referensi_count + 1);
                            }else{
                                $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . '-' . ($data_referensi_count + 1);
                            }
                        }
                    }else{
                        $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-000001";
                    }
                }else{
                    $nomor_rencana_produksi_referensi = "WOSR" . $tanggal . "-000001";
                }

                DB::table('rencana_produksi_referensi')->insert(["nomor_rencana_produksi_referensi" => $nomor_rencana_produksi_referensi, "nomor_rencana_produksi" => $request->edit_nomor_rencana_produksi, "nomor_referensi" => $request->edit_referensi[$i]]);
            }
        }

        for($j = 1; $j <= count($data_mesin); $j++){
            $number = count($request->{"edit_jenis_produk_" . $data_mesin[$j - 1]});

            for($i = 0; $i < $number; $i++){
                if(!empty($request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i])){

                    $cek_data_exist = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail')->where('nomor_rencana_produksi', $request->edit_nomor_rencana_produksi)->where('mesin', $j)->where('jenis_produk', $request->{"edit_jenis_produk_lama_" . $data_mesin[$j - 1]}[$i])->first();

                    if($cek_data_exist){
                        $data = DB::table('rencana_produksi_detail')->where('nomor_rencana_produksi', $request->edit_nomor_rencana_produksi)->where('mesin', $j)->where('jenis_produk', $request->{"edit_jenis_produk_lama_" . $data_mesin[$j - 1]}[$i])->update(["jenis_produk" => $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i], "jumlah_sak" => $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i], "jumlah_tonase" => $request->{"edit_jumlah_tonase_" . $data_mesin[$j - 1]}[$i]]);
                    }else{
                        $cek_data_detail = DB::table('rencana_produksi_detail')->select('nomor_rencana_produksi_detail')->where('nomor_rencana_produksi_detail', 'like', 'WOSD' . $tanggal . '%')->orderBy('nomor_rencana_produksi_detail', 'asc')->distinct()->get();

                        if($cek_data_detail){
                            $data_detail_count = $cek_data_detail->count();
                            if($data_detail_count > 0){
                                $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_rencana_produksi_detail, 9);
                                if($data_detail_count != $num){
                                    $nomor_rencana_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_rencana_produksi_detail;
                                }else{
                                    if($data_detail_count < 9){
                                        $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-00000" . ($data_detail_count + 1);
                                    }else if($data_detail_count >= 9 && $data_detail_count < 99){
                                        $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-0000" . ($data_detail_count + 1);
                                    }else if($data_detail_count >= 99 && $data_detail_count < 999){
                                        $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-000" . ($data_detail_count + 1);
                                    }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                                        $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-00" . ($data_detail_count + 1);
                                    }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                                        $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-0" . ($data_detail_count + 1);
                                    }else{
                                        $nomor_rencana_produksi_detail = "WOSD" . $tanggal . '-' . ($data_detail_count + 1);
                                    }
                                }
                            }else{
                                $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-000001";
                            }
                        }else{
                            $nomor_rencana_produksi_detail = "WOSD" . $tanggal . "-000001";
                        }

                        $data = DB::table('rencana_produksi_detail')->insert(["nomor_rencana_produksi_detail" => $nomor_rencana_produksi_detail, "nomor_rencana_produksi" => $request->edit_nomor_rencana_produksi, "mesin" => $j, "jenis_produk" => $request->{"edit_jenis_produk_" . $data_mesin[$j - 1]}[$i], "jumlah_sak" => $request->{"edit_jumlah_sak_" . $data_mesin[$j - 1]}[$i], "jumlah_tonase" => $request->{"edit_jumlah_tonase_" . $data_mesin[$j - 1]}[$i]]);
                    }
                }
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_laporan_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Produksi Edit Data Rencana Produksi Nomor ' . $request->edit_nomor_rencana_produksi]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function editSpekRencanaProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        for($j = 1; $j <= count($data_mesin); $j++){
            $number = count($request->{"edit_spek_rpm_" . $data_mesin[$j - 1]});

            for($i = 0; $i < $number; $i++){
                if(!empty($request->{"edit_spek_rpm_" . $data_mesin[$j - 1]}[$i])){
                    DB::table('rencana_produksi_spek')->where('nomor_rencana_produksi', $request->edit_nomor_rencana_produksi_spek)->where('mesin', $j)->update(["rpm" => $request->{"edit_spek_rpm_" . $data_mesin[$j - 1]}[$i], "particle_size" => $request->{"edit_spek_particle_size_" . $data_mesin[$j - 1]}[$i], "ssa" => $request->{"edit_spek_ssa_" . $data_mesin[$j - 1]}[$i], "whiteness" => $request->{"edit_spek_whiteness_" . $data_mesin[$j - 1]}[$i], "residue" => $request->{"edit_spek_residue_" . $data_mesin[$j - 1]}[$i], "moisture" => $request->{"edit_spek_moisture_" . $data_mesin[$j - 1]}[$i]]);
                }
            }
        }

        $arr = array('msg' => 'Data Successfully Updated', 'status' => true);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_rencana_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Rencana Produksi Data Spek Dengan Kode ' . $request->edit_nomor_rencana_produksi_spek]);

        return Response()->json($arr);
    }

    public function dropdownReferensiRencanaProduksi(Request $request){
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = DB::table('order_receipt')->select("nomor_order_receipt")
            ->where('nomor_order_receipt','LIKE',"%$search%")
            ->get();
        }else{
            $data = DB::table('order_receipt')->select("nomor_order_receipt")
            ->get();
        }
        
        return response()->json($data);
    }

    public function deleteRencanaProduksi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('rencana_produksi')->where('nomor_rencana_produksi', $request->get('nomor'))->delete();

        $hapus_detail = DB::table('rencana_produksi_detail')->where('nomor_rencana_produksi', $request->get('nomor'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_rencana_produksi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Rencana Produksi Dengan Kode ' . $request->get('nomor')]);

        return Response()->json($arr);
    }
}
