<?php

namespace App\Http\Controllers;
use App\ModelComplaintProd;
use App\ModelComplaintCust;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;
use Carbon\Carbon;

class ComplaintLabController extends Controller
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
        $tanggal = date('ym');

        if($request->hasFile('upload_file_lab')) {
            $file = $request->file('upload_file_lab');
            $nama_file = time()."_".$request->custid_lab."_".$request->nomor_complaint_lab."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data_lab_get = DB::table('temp_data_lab')->select('nomor_sample_lab', 'nomor_complaint', 'no_lot', 'keterangan')->where('nomor_complaint', $request->nomor_complaint_lab)->get();

            foreach($data_lab_get as $data){
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

                DB::table('data_complaint_lab')->insert(['nomor_sample_lab' => $kode_quality, 'nomor_complaint' => $data->nomor_complaint, 'no_lot' => $data->no_lot, 'keterangan' => $data->keterangan]);

                $data_lab = DB::table('data_complaint_lab')->select('nomor_sample_lab')->where('nomor_sample_lab', $kode_quality)->first();

                $data_lab_quality = DB::table('temp_quality_detail_lab')->select('nomor_complaint', 'nomor_sample_lab', 'no_lot', 'quality_name', 'quality_name_lainnya', 'metode_mesin', 'hasil', 'satuan')->where('nomor_complaint', $data->nomor_complaint)->where('nomor_sample_lab', $data->nomor_sample_lab)->get();

                foreach($data_lab_quality as $data_quality){
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

                    DB::table('data_complaint_quality_detail_lab')->insert(['nomor_quality_detail_lab' => $kode_quality_lab, 'nomor_complaint' => $data_quality->nomor_complaint, 'nomor_sample_lab' => $data_lab->nomor_sample_lab, 'no_lot' => $data_quality->no_lot, 'quality_name' => $data_quality->quality_name, 'quality_name_lainnya' => $data_quality->quality_name_lainnya, 'metode_mesin' => $data_quality->metode_mesin, 'hasil' => $data_quality->hasil, 'satuan' => $data_quality->satuan]);
                }
            }

            $complaint=DB::table('complaint_lab')->insert(["nomor_complaint" => $request->nomor_complaint_lab, "suggestion" => $request->suggestion, "lampiran" => $nama_file, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

            if($complaint){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lab)->update(['baca' => 0, 'lab' => 2]);

                $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_lab)->first();

                $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_lab)->first();

                if($stat->hasil == $div->hasil){
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lab)->update(['validasi' => 1]);
                }else{
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lab)->update(['validasi' => 0]);
                }
            }
        }else{
            $data_lab_get = DB::table('temp_data_lab')->select('nomor_sample_lab', 'nomor_complaint', 'no_lot', 'keterangan')->where('nomor_complaint', $request->nomor_complaint_lab)->get();

            foreach($data_lab_get as $data){
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

                DB::table('data_complaint_lab')->insert(['nomor_sample_lab' => $kode_quality, 'nomor_complaint' => $data->nomor_complaint, 'no_lot' => $data->no_lot, 'keterangan' => $data->keterangan]);

                $data_lab = DB::table('data_complaint_lab')->select('nomor_sample_lab')->where('nomor_sample_lab', $kode_quality)->first();

                $data_lab_quality = DB::table('temp_quality_detail_lab')->select('nomor_complaint', 'nomor_sample_lab', 'no_lot', 'quality_name', 'quality_name_lainnya', 'metode_mesin', 'hasil', 'satuan')->where('nomor_complaint', $data->nomor_complaint)->where('nomor_sample_lab', $data->nomor_sample_lab)->get();

                foreach($data_lab_quality as $data_quality){
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

                    DB::table('data_complaint_quality_detail_lab')->insert(['nomor_quality_detail_lab' => $kode_quality_lab, 'nomor_complaint' => $data_quality->nomor_complaint, 'nomor_sample_lab' => $data_lab->nomor_sample_lab, 'no_lot' => $data_quality->no_lot, 'quality_name' => $data_quality->quality_name, 'quality_name_lainnya' => $data_quality->quality_name_lainnya, 'metode_mesin' => $data_quality->metode_mesin, 'hasil' => $data_quality->hasil, 'satuan' => $data_quality->satuan]);
                }
            }

            $complaint=DB::table('complaint_lab')->insert(["nomor_complaint" => $request->nomor_complaint_lab, "suggestion" => $request->suggestion, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

            if($complaint){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lab)->update(['baca' => 0, 'lab' => 2]);

                $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_lab)->first();

                $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $request->nomor_complaint_lab)->first();

                if($stat->hasil == $div->hasil){
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lab)->update(['validasi' => 1]);
                }else{
                    DB::table('status_complaint')->where('nomor_complaint', $request->nomor_complaint_lab)->update(['validasi' => 0]);
                }
            }
        }

        DB::table('temp_data_lab')->where('nomor_complaint', $request->nomor_complaint_lab)->delete();
        DB::table('temp_quality_detail_lab')->where('nomor_complaint', $request->nomor_complaint_lab)->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $request->nomor_complaint_lab, 'divisi' => 6, 'action' => 'Divisi Lab Berhasil Memproses dan Memasukkan Data pada Complaint Nomor ' . $request->nomor_complaint_lab]);

        ModelComplaintCust::where('nomor_complaint', $request->nomor_complaint_lab)->update(['updated_at' => date("Y-m-d H:i:s")]);

        return Response()->json($arr);
    }

    public function show($nomor_complaint){   

        $val_nomor_complaint = $this->decrypt($nomor_complaint);
        
        $data  = DB::table('complaint_lab as com')->select('com.nomor_complaint', 'com.suggestion', 'com.lampiran')->where('com.nomor_complaint', $val_nomor_complaint)->first();

        $data_lab = DB::table('data_complaint_lab')->select("nomor_sample_lab", "nomor_complaint", "no_lot", "keterangan")->where('nomor_complaint', $val_nomor_complaint)->get();

        $data_quality_lab = DB::table('data_complaint_quality_detail_lab')->select("nomor_quality_detail_lab", "nomor_sample_lab", "nomor_complaint", "no_lot", "qual.name as quality", "quality_name", "quality_name_lainnya", "metode_mesin", "hasil", "satuan")->join('tbl_quality_lab as qual', 'qual.id', '=', 'data_complaint_quality_detail_lab.quality_name')->where('nomor_complaint', $val_nomor_complaint)->get();
     
        return Response()->json(['data_hitung' => $data, 'data_lab' => $data_lab, 'data_quality_lab' => $data_quality_lab]);
    }

    public function showDataLab($no_complaint){   

        $val_no_complaint = $this->decrypt($no_complaint);

        $data  = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.tanggal_order', 'com.sales_order', 'com.supervisor_sales', 'com.pelapor', 'com.jumlah_karung', 'com.quantity', 'com.jumlah_kg_sak', 'com.jenis_karung', 'com.berat_timbangan', 'com.unit_berat_timbangan', 'com.berat_aktual', 'com.unit_berat_aktual', 'com.alasan_request_lab')->where('com.nomor_complaint', $val_no_complaint)->first();

        $orders = DB::table('data_complaint_produksi as dcp')->select("dcp.nomor_complaint", "dcp.no_lot", "dcp.tanggal_produksi", "dcp.kode_produk", "prd.nama_produk", "dcp.custid", "dcp.mesin", "dcp.area", "dcp.supervisor", "dcp.petugas1", "dcp.petugas2", "dcp.petugas3", "dcp.petugas4", "dcp.petugas5", "dcp.bermasalah")->join("products as prd", "prd.kode_produk", "=", "dcp.kode_produk")->where('dcp.nomor_complaint', $val_no_complaint)->where('dcp.bermasalah', 'Ya')->get();
     
        return Response()->json(['data_produksi' => $orders, 'data_hitung' => $data]);
    }

    public function complaintTdkTerlibat(Request $request){
        $no_complaint = $request->get('nomor_complaint');
        DB::table('divisi_complaint')->where('nomor_complaint', $no_complaint)->update(['lab' => 1]);

        $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        if($stat->hasil == $div->hasil){
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 1]);
        }else{
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 0]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $no_complaint, 'divisi' => 6, 'action' => 'Divisi Lab Merubah Status Menjadi Tidak Terlibat Pada Complaint Nomor ' . $no_complaint]);
        ModelComplaintCust::where('nomor_complaint', $no_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);
    }

    public function complaintTerlibat(Request $request){
        $no_complaint = $request->get('nomor_complaint');
        DB::table('divisi_complaint')->where('nomor_complaint', $no_complaint)->update(['lab' => 2]);

        $stat = DB::table('status_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        $div = DB::table('divisi_complaint')->select(DB::raw("(if(produksi = 2, 2, 0) + if(logistik = 2, 2, 0) + if(sales = 2, 2, 0) + if(timbangan = 2, 2, 0) + if(warehouse = 2, 2, 0) + if(lab = 2, 2, 0) + if(lainnya = 2, 2, 0)) as hasil"))->where('nomor_complaint', $no_complaint)->first();

        if($stat->hasil == $div->hasil){
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 1]);
        }else{
            DB::table('status_complaint')->where('nomor_complaint', $no_complaint)->update(['validasi' => 0]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $no_complaint, 'divisi' => 6, 'action' => 'Divisi Lab Merubah Status Menjadi Terlibat Pada Complaint Nomor ' . $no_complaint]);
        ModelComplaintCust::where('nomor_complaint', $no_complaint)->update(['updated_at' => date("Y-m-d H:i:s")]);
    }
}
