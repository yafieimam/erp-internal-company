<?php

namespace App\Http\Controllers;

use App\ModelKota;
use App\ModelProvinsi;
use App\ModelLeads;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Response;
use Notification;
use PDF;

class SuratPenawaranController extends Controller
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

    public function viewPageSuratPengenalanSales(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('surat_pengenalan_sales');
        }
    }

    public function viewPageSuratPenawaranSales(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('surat_penawaran_sales');
        }
    }

    public function viewSuratPengenalanSalesTable(Request $request){
        if(request()->ajax()){
        	if(!empty($request->from_date)){
        		$surat = DB::table('surat_pengenalan as sp')->select("sp.nomor_surat_pengenalan as nomor", "sp.tanggal", "leads.nama", "company.nama_perusahaan as company")->join("leads", "leads.leadid", "=", "sp.leadid")->join("company", "company.kode_perusahaan", "=", "sp.company")->where('sp.id_sales', Session::get('id_user_admin'))->whereBetween('sp.tanggal', array($request->from_date, $request->to_date))->get();
        	}else{
        		$surat = DB::table('surat_pengenalan as sp')->select("sp.nomor_surat_pengenalan as nomor", "sp.tanggal", "leads.nama", "company.nama_perusahaan as company")->join("leads", "leads.leadid", "=", "sp.leadid")->join("company", "company.kode_perusahaan", "=", "sp.company")->where('sp.id_sales', Session::get('id_user_admin'))->get();
        	}

            return datatables()->of($surat)->addColumn('action', 'button/action_button_surat_pengenalan_sales')->rawColumns(['action'])->make(true);
        }
        return view('surat_pengenalan_sales');
    }

    public function inputSuratPengenalanSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal_surat = date('ym');

        $data_surat = DB::table('surat_pengenalan')->select('nomor_surat_pengenalan')->where('nomor_surat_pengenalan', 'like', 'SRPENGENALAN' . $tanggal_surat . '%')->orderBy('nomor_surat_pengenalan', 'asc')->distinct()->get();

        if($data_surat){
            $surat_count = $data_surat->count();
            if($surat_count > 0){
                $num = (int) substr($data_surat[$data_surat->count() - 1]->nomor_surat_pengenalan, 17);
                if($surat_count != $num){
                    $kode_surat = ++$data_surat[$data_surat->count() - 1]->nomor_surat_pengenalan;
                }else{
                    if($surat_count < 9){
                        $kode_surat = "SRPENGENALAN" . $tanggal_surat . "-000" . ($surat_count + 1);
                    }else if($surat_count >= 9 && $surat_count < 99){
                        $kode_surat = "SRPENGENALAN" . $tanggal_surat . "-00" . ($surat_count + 1);
                    }else if($surat_count >= 99 && $surat_count < 999){
                        $kode_surat = "SRPENGENALAN" . $tanggal_surat . "-0" . ($surat_count + 1);
                    }else{
                        $kode_surat = "SRPENGENALAN" . $tanggal_surat . "-" . ($surat_count + 1);
                    }
                }
            }else{
                $kode_surat = "SRPENGENALAN" . $tanggal_surat . "-0001";
            }
        }else{
            $kode_surat = "SRPENGENALAN" . $tanggal_surat . "-0001";
        }

        if($request->new_nama != NULL || $request->new_nama != ''){
        	$data_kota = ModelKota::where('id_kota', $request->new_city)->first();
        	$data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
        	$nama_user = strtoupper(str_replace(' ', '', $request->new_nama));
        	$kode_nama = substr($nama_user, 0, 5);

        	$kode_cust = 'LEAD' . $data_provinsi->kode . $data_kota->kode . $kode_nama;
        	$data_custid = ModelLeads::where('leadid', 'like', '%' . $kode_cust . '%')->orderBy('leadid', 'asc')->get();

        	if($data_custid){
        		$data_count = $data_custid->count();
        		if($data_count < 9){
        			$kode_cust = $kode_cust . "0" . ($data_count + 1);
        		}else{
        			$kode_cust = $kode_cust . ($data_count + 1);
        		}
        	}else{
        		$kode_cust = $kode_cust . "01";
        	}

        	$data_cust =  new ModelLeads();
        	$data_cust->leadid = $kode_cust;
        	$data_cust->nama = $request->new_nama;
        	$data_cust->company = $request->company;
        	$data_cust->address = $request->new_alamat;
        	$data_cust->phone = $request->new_telepon;
        	$data_cust->city = $request->new_city;
        	$data_cust->nama_cp = $request->new_nama_cp;
        	$data_cust->jabatan_cp = $request->new_jabatan_cp;
        	$data_cust->bidang_usaha = $request->new_bidang_usaha;
        	$data_cust->created_by = Session::get('id_user_admin');
        	$data_cust->updated_by = Session::get('id_user_admin');
        	$data_cust->status = 1;
        	$data_cust->save();

        	date_default_timezone_set('Asia/Jakarta');
        	$data = DB::table('surat_pengenalan')->insert(["nomor_surat_pengenalan" => $kode_surat, "company" => $request->company, "leadid" => $kode_cust, "tanggal" => date('Y-m-d'), "id_sales" => Session::get('id_user_admin'), "status" => 1, "created_at" => date("Y-m-d H:i:s"), "created_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        	date_default_timezone_set('Asia/Jakarta');
        	DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_cust, 'status' => 1, 'action' => 'Data Leads ' . $kode_cust . ' Dibuat Melalui Surat Pengenalan']);
        }else{
        	if($request->exist_nama_cp != NULL || $request->exist_nama_cp != ''){
                DB::table('leads')->where('leadid', $request->get('leads'))->update(["nama_cp" => $request->exist_nama_cp, "jabatan_cp" => $request->exist_jabatan_cp, "bidang_usaha" => $request->exist_bidang_usaha]);
            }

            date_default_timezone_set('Asia/Jakarta');
        	$data = DB::table('surat_pengenalan')->insert(["nomor_surat_pengenalan" => $kode_surat, "company" => $request->company, "leadid" => $request->get('leads'), "tanggal" => date('Y-m-d'), "id_sales" => Session::get('id_user_admin'), "status" => 1, "created_at" => date("Y-m-d H:i:s"), "created_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_surat_pengenalan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Buat Surat Pengenalan No. ' . $kode_surat]);

        return Response()->json($arr);
    }

    public function printSuratPengenalanSales($nomor_surat_pengenalan){
        $val_nomor = Crypt::decrypt($nomor_surat_pengenalan);

        $data = DB::table('surat_pengenalan as sp')->select("sp.nomor_surat_pengenalan as nomor", "sp.tanggal", "leads.nama", "leads.nama_cp", "leads.jabatan_cp", "leads.bidang_usaha", "company.nama_perusahaan as company", "sp.status", "users.nama_admin", "user_class.name as jabatan_admin", "leads.address", "kota.name as nama_kota", "user_type.type as dep_admin")->join("leads", "leads.leadid", "=", "sp.leadid")->join("company", "company.kode_perusahaan", "=", "sp.company")->join("users", "users.id_user", "=", "sp.id_sales")->join("kota", "kota.id_kota", "=", "leads.city")->join("user_class", "user_class.id", "=", "users.id_user_class")->join("user_type", "user_type.id_customer_type", "=", "users.id_customer_type")->first();

        $pdf = PDF::loadView('print_surat_pengenalan', ['data' => $data])->setPaper('f4', 'portrait')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewSuratPenawaranSalesTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $surat = DB::table('surat_penawaran as sp')->select("sp.nomor_surat_penawaran as nomor", "sp.tanggal", "leads.nama", "company.nama_perusahaan as company", "sp.status")->join("leads", "leads.leadid", "=", "sp.leadid")->join("company", "company.kode_perusahaan", "=", "sp.company")->where('sp.id_sales', Session::get('id_user_admin'))->whereBetween('sp.tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $surat = DB::table('surat_penawaran as sp')->select("sp.nomor_surat_penawaran as nomor", "sp.tanggal", "leads.nama", "company.nama_perusahaan as company", "sp.status")->join("leads", "leads.leadid", "=", "sp.leadid")->join("company", "company.kode_perusahaan", "=", "sp.company")->where('sp.id_sales', Session::get('id_user_admin'))->get();
            }

            return datatables()->of($surat)->addColumn('action', 'button/action_button_surat_penawaran_sales')->rawColumns(['action'])->make(true);
        }
        return view('surat_penawaran_sales');
    }

    public function inputSuratPenawaranSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $bln = $array_bln[date('n')];

        $nomor_surat = '/SP/SM/'.$bln.'/'.date('Y');

        $data_surat = DB::table('surat_penawaran')->select('nomor_surat_penawaran')->where('nomor_surat_penawaran', 'like', '%' . $nomor_surat)->orderBy('nomor_surat_penawaran', 'asc')->distinct()->get();

        if($data_surat){
            $surat_count = $data_surat->count();
            if($surat_count > 0){
                $num = (int) substr($data_surat[$data_surat->count() - 1]->nomor_surat_penawaran, 0, 3);
                if($surat_count != $num){
                    $kode_surat = ++$data_surat[$data_surat->count() - 1]->nomor_surat_penawaran;
                }else{
                    if($surat_count < 9){
                        $kode_surat = "00" . ($surat_count + 1) . $nomor_surat;
                    }else if($surat_count >= 9 && $surat_count < 99){
                        $kode_surat = "0" . ($surat_count + 1) . $nomor_surat;
                    }else{
                        $kode_surat = ($surat_count + 1) . $nomor_surat;
                    }
                }
            }else{
                $kode_surat = "001" . $nomor_surat;
            }
        }else{
            $kode_surat = "001" . $nomor_surat;
        }

        if($request->new_nama != NULL || $request->new_nama != ''){
            $data_kota = ModelKota::where('id_kota', $request->new_city)->first();
            $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
            $nama_user = strtoupper(str_replace(' ', '', $request->new_nama));
            $kode_nama = substr($nama_user, 0, 5);

            $kode_cust = 'LEAD' . $data_provinsi->kode . $data_kota->kode . $kode_nama;
            $data_custid = ModelLeads::where('leadid', 'like', '%' . $kode_cust . '%')->orderBy('leadid', 'asc')->get();

            if($data_custid){
                $data_count = $data_custid->count();
                if($data_count < 9){
                    $kode_cust = $kode_cust . "0" . ($data_count + 1);
                }else{
                    $kode_cust = $kode_cust . ($data_count + 1);
                }
            }else{
                $kode_cust = $kode_cust . "01";
            }

            $data_cust =  new ModelLeads();
            $data_cust->leadid = $kode_cust;
            $data_cust->nama = $request->new_nama;
            $data_cust->company = $request->company;
            $data_cust->address = $request->new_alamat;
            $data_cust->phone = $request->new_telepon;
            $data_cust->city = $request->new_city;
            $data_cust->nama_cp = $request->new_nama_cp;
            $data_cust->jabatan_cp = $request->new_jabatan_cp;
            $data_cust->bidang_usaha = $request->new_bidang_usaha;
            $data_cust->created_by = Session::get('id_user_admin');
            $data_cust->updated_by = Session::get('id_user_admin');
            $data_cust->status = 1;
            $data_cust->save();

            date_default_timezone_set('Asia/Jakarta');
            $data = DB::table('surat_penawaran')->insert(["nomor_surat_penawaran" => $kode_surat, "company" => $request->company, "leadid" => $kode_cust, "tanggal" => date('Y-m-d'), "id_sales" => Session::get('id_user_admin'), "status" => 1, "created_at" => date("Y-m-d H:i:s"), "created_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_cust, 'status' => 1, 'action' => 'Data Leads ' . $kode_cust . ' Dibuat Melalui Surat Pengenalan']);
        }else{
            if($request->exist_nama_cp != NULL || $request->exist_nama_cp != ''){
                DB::table('leads')->where('leadid', $request->get('leads'))->update(["nama_cp" => $request->exist_nama_cp, "jabatan_cp" => $request->exist_jabatan_cp, "bidang_usaha" => $request->exist_bidang_usaha]);
            }

            date_default_timezone_set('Asia/Jakarta');
            $data = DB::table('surat_penawaran')->insert(["nomor_surat_penawaran" => $kode_surat, "company" => $request->company, "leadid" => $request->get('leads'), "tanggal" => date('Y-m-d'), "id_sales" => Session::get('id_user_admin'), "status" => 1, "created_at" => date("Y-m-d H:i:s"), "created_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_surat_penawaran')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Buat Surat Penawaran No. ' . $kode_surat]);

        return Response()->json($arr);
    }

    public function updateSuratPenawaranSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal_surat = date('ym');

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('surat_penawaran')->where("nomor_surat_penawaran", $request->nomor_surat)->update(["minimum_order" => $request->minimum_order, "pembayaran" => $request->pembayaran, "status" => 2, "updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        $number = count($request->tipe);

        for($i = 0; $i < $number; $i++){
            if(!empty($request->tipe[$i])){
                $data_surat = DB::table('surat_penawaran_produk')->select('nomor_surat_penawaran_produk')->where('nomor_surat_penawaran_produk', 'like', 'SRPDET' . $tanggal_surat . '%')->orderBy('nomor_surat_penawaran_produk', 'asc')->distinct()->get();

                if($data_surat){
                    $surat_count = $data_surat->count();
                    if($surat_count > 0){
                        $num = (int) substr($data_surat[$data_surat->count() - 1]->nomor_surat_penawaran_produk, 11);
                        if($surat_count != $num){
                            $kode_surat = ++$data_surat[$data_surat->count() - 1]->nomor_surat_penawaran_produk;
                        }else{
                            if($surat_count < 9){
                                $kode_surat = "SRPDET" . $tanggal_surat . "-000" . ($surat_count + 1);
                            }else if($surat_count >= 9 && $surat_count < 99){
                                $kode_surat = "SRPDET" . $tanggal_surat . "-00" . ($surat_count + 1);
                            }else if($surat_count >= 99 && $surat_count < 999){
                                $kode_surat = "SRPDET" . $tanggal_surat . "-0" . ($surat_count + 1);
                            }else{
                                $kode_surat = "SRPDET" . $tanggal_surat . "-" . ($surat_count + 1);
                            }
                        }
                    }else{
                        $kode_surat = "SRPDET" . $tanggal_surat . "-0001";
                    }
                }else{
                    $kode_surat = "SRPDET" . $tanggal_surat . "-0001";
                }

                $data_produk = DB::table('surat_penawaran_produk')->insert(["nomor_surat_penawaran_produk" => $kode_surat, "nomor_surat_penawaran" => $request->nomor_surat, "tipe" => $request->tipe[$i], "packaging" => $request->packaging[$i], "harga_kirim" => $request->harga_kirim[$i]]);
            }
        }
        
        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_surat_penawaran')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Produk dan Harga Surat Pesanan No. ' . $request->nomor_surat]);

        return Response()->json($arr);
    }

    public function printSuratPenawaranSales($nomor_surat_penawaran){
        $val_nomor = Crypt::decrypt($nomor_surat_penawaran);

        $data = DB::table('surat_penawaran as sp')->select("sp.nomor_surat_penawaran as nomor", "sp.tanggal", "sp.minimum_order", "sp.pembayaran", "leads.nama", "leads.nama_cp", "leads.jabatan_cp", "leads.bidang_usaha", "company.nama_perusahaan as company", "sp.status", "users.nama_admin", "user_class.name as jabatan_admin", "leads.address", "kota.name as nama_kota", "user_type.type as dep_admin")->join("leads", "leads.leadid", "=", "sp.leadid")->join("company", "company.kode_perusahaan", "=", "sp.company")->join("users", "users.id_user", "=", "sp.id_sales")->join("kota", "kota.id_kota", "=", "leads.city")->join("user_class", "user_class.id", "=", "users.id_user_class")->join("user_type", "user_type.id_customer_type", "=", "users.id_customer_type")->where('sp.nomor_surat_penawaran', $val_nomor)->first();

        $data_produk = DB::table('surat_penawaran_produk as spp')->select("spp.nomor_surat_penawaran_produk as nomor_produk", "spp.tipe", "spp.packaging", "spp.harga_kirim")->where('spp.nomor_surat_penawaran', $val_nomor)->get();

        $pdf = PDF::loadView('print_surat_penawaran', ['data' => $data, 'data_produk' => $data_produk])->setPaper('a4', 'portrait')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewSuratPenawaranSales($nomor_surat_penawaran){
        $val_nomor = $this->decrypt($nomor_surat_penawaran);

        $data = DB::table('surat_penawaran as sp')->select("sp.nomor_surat_penawaran as nomor", "sp.tanggal", "leads.nama as customers", "sp.minimum_order", "sp.pembayaran")->join("leads", "leads.leadid", "=", "sp.leadid")->where('sp.nomor_surat_penawaran', $val_nomor)->first();

        return Response()->json($data);
    }

    public function viewDetailSuratPenawaranSales($nomor_surat_penawaran){
        $val_nomor = $this->decrypt($nomor_surat_penawaran);

        $data = DB::table('surat_penawaran_produk as spp')->select("spp.nomor_surat_penawaran_produk as nomor_produk", "spp.tipe", "spp.packaging", "spp.harga_kirim")->where('spp.nomor_surat_penawaran', $val_nomor)->get();

        return Response()->json($data);
    }

    public function deleteDetailSuratPenawaranSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        DB::table('surat_penawaran_produk')->where('nomor_surat_penawaran_produk', $request->get('nomor'))->delete();

        return Response()->json($arr);
    }

    public function editSuratPenawaranSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal_surat = date('ym');

        $data = DB::table('surat_penawaran')->where("nomor_surat_penawaran", $request->edit_nomor_surat)->update(["minimum_order" => $request->edit_minimum_order, "pembayaran" => $request->edit_pembayaran, "updated_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin')]);

        $number = count($request->edit_tipe);

        for($i = 0; $i < $number; $i++){
            if(!empty($request->edit_tipe[$i])){
                if(!empty($request->edit_nomor_produk[$i])){
                    $data_produk = DB::table('surat_penawaran_produk')->where("nomor_surat_penawaran_produk", $request->edit_nomor_produk[$i])->update(["tipe" => $request->edit_tipe[$i], "packaging" => $request->edit_packaging[$i], "harga_kirim" => $request->edit_harga_kirim[$i]]);
                }else{
                    $data_surat = DB::table('surat_penawaran_produk')->select('nomor_surat_penawaran_produk')->where('nomor_surat_penawaran_produk', 'like', 'SRPDET' . $tanggal_surat . '%')->orderBy('nomor_surat_penawaran_produk', 'asc')->distinct()->get();

                    if($data_surat){
                        $surat_count = $data_surat->count();
                        if($surat_count > 0){
                            $num = (int) substr($data_surat[$data_surat->count() - 1]->nomor_surat_penawaran_produk, 11);
                            if($surat_count != $num){
                                $kode_surat = ++$data_surat[$data_surat->count() - 1]->nomor_surat_penawaran_produk;
                            }else{
                                if($surat_count < 9){
                                    $kode_surat = "SRPDET" . $tanggal_surat . "-000" . ($surat_count + 1);
                                }else if($surat_count >= 9 && $surat_count < 99){
                                    $kode_surat = "SRPDET" . $tanggal_surat . "-00" . ($surat_count + 1);
                                }else if($surat_count >= 99 && $surat_count < 999){
                                    $kode_surat = "SRPDET" . $tanggal_surat . "-0" . ($surat_count + 1);
                                }else{
                                    $kode_surat = "SRPDET" . $tanggal_surat . "-" . ($surat_count + 1);
                                }
                            }
                        }else{
                            $kode_surat = "SRPDET" . $tanggal_surat . "-0001";
                        }
                    }else{
                        $kode_surat = "SRPDET" . $tanggal_surat . "-0001";
                    }

                    $data_produk = DB::table('surat_penawaran_produk')->insert(["nomor_surat_penawaran_produk" => $kode_surat, "nomor_surat_penawaran" => $request->edit_nomor_surat, "tipe" => $request->edit_tipe[$i], "packaging" => $request->edit_packaging[$i], "harga_kirim" => $request->edit_harga_kirim[$i]]);
                }
            }
        }
        
        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_surat_penawaran')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 3, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Produk dan Harga Surat Pesanan No. ' . $request->edit_nomor_surat]);

        return Response()->json($arr);
    }
}
