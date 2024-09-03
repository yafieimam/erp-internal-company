<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;

class MasterController extends Controller
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

    public function getViewCompany(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('master_company');
        }
    }

    public function getViewUnit(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('master_unit');
        }
    }

    public function getViewShift(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('master_shift');
        }
    }

    public function getViewBagian(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('master_bagian');
        }
    }

    public function getViewKaryawan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('master_karyawan');
        }
    }

    public function getViewParameterKPI(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
        	DB::table('temp_param_kpi')->where('id_user', Session::get('id_user_admin'))->delete();
            return view('master_parameter_kpi');
        }
    }

    public function getViewKPIKaryawan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('master_kpi_karyawan');
        }
    }

    public function viewCompanyTable(Request $request){
        $list = DB::table('company')->select('kode_perusahaan as kode', 'nama_perusahaan as nama', 'bentuk_perusahaan', 'alamat_perusahaan as alamat', 'status_perusahaan as status', 'description')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_company')->rawColumns(['action'])->make(true);
    }

    public function inputCompany(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('company')->insert(['kode_perusahaan' => $request->kode, 'nama_perusahaan' => $request->nama, 'bentuk_perusahaan' => $request->bentuk_perusahaan, 'alamat_perusahaan' => $request->alamat, 'status_perusahaan' => $request->status, 'description' => $request->description, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_company')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Company Dengan Kode ' . $request->kode]);

        return Response()->json($arr);
    }

    public function deleteCompany(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('company')->where('kode_perusahaan', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_company')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Company Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewCompany($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('company')->where('kode_perusahaan', $val_id)->first();

        return Response()->json($data);
    }

    public function editCompany(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('company')->where('kode_perusahaan', $request->edit_kode_lama)->update(['kode_perusahaan' => $request->edit_kode, 'nama_perusahaan' => $request->edit_nama, 'bentuk_perusahaan' => $request->edit_bentuk_perusahaan, 'alamat_perusahaan' => $request->edit_alamat, 'status_perusahaan' => $request->edit_status, 'description' => $request->edit_description, 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_company')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Company Dengan Kode ' . $request->edit_kode]);

        return Response()->json($arr);
    }

    public function viewUnitTable(Request $request){
        $list = DB::table('unit')->select('kode_unit as kode', 'kode_perusahaan as company', 'nama_unit as nama', 'description')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_unit')->rawColumns(['action'])->make(true);
    }

    public function inputUnit(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('unit')->insert(['kode_unit' => $request->kode, 'kode_perusahaan' => $request->company, 'nama_unit' => $request->nama, 'description' => $request->description, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_unit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Unit Dengan Kode ' . $request->kode]);

        return Response()->json($arr);
    }

    public function deleteUnit(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('unit')->where('kode_unit', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_unit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Unit Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewUnit($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('unit')->select('kode_unit as kode', 'nama_unit as nama', 'unit.description', 'com.nama_perusahaan as company', 'unit.kode_perusahaan')->join('company as com', 'com.kode_perusahaan', '=', 'unit.kode_perusahaan')->where('kode_unit', $val_id)->first();

        return Response()->json($data);
    }

    public function editUnit(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('unit')->where('kode_unit', $request->edit_kode_lama)->update(['kode_unit' => $request->edit_kode, 'nama_unit' => $request->edit_nama, 'kode_perusahaan' => $request->edit_company, 'description' => $request->edit_description, 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_unit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Unit Dengan Kode ' . $request->edit_kode]);

        return Response()->json($arr);
    }

    public function getCompany(){
        $data = DB::table('company')->get();

        return Response()->json($data);
    }

    public function getUnit($company){
        $val_company = $this->decrypt($company);

        $data = DB::table('unit')->where('kode_perusahaan', $val_company)->get();

        return Response()->json($data);
    }

    public function getShift(){
        $data = DB::table('shift')->get();

        return Response()->json($data);
    }

    public function getBagian($company, $unit){
        $val_company = $this->decrypt($company);
        $val_unit = $this->decrypt($unit);

        $data = DB::table('bagian')->where('kode_perusahaan', $val_company)->where('kode_unit', $val_unit)->get();

        return Response()->json($data);
    }

    public function getJabatan(){
        $data = DB::table('user_class')->get();

        return Response()->json($data);
    }

    public function viewShiftTable(Request $request){
        $list = DB::table('shift')->select('kode_shift as kode', 'nama_shift as nama', 'jam_masuk', 'jam_keluar', 'description')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_shift')->rawColumns(['action'])->make(true);
    }

    public function inputShift(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	$cek_data = DB::table('shift')->select('kode_shift')->where('kode_shift', 'like', 'JK%')->orderBy('kode_shift', 'asc')->distinct()->get();

        if($cek_data){
            $data_count = $cek_data->count();
            if($data_count > 0){
            	$num = (int) substr($cek_data[$cek_data->count() - 1]->kode_shift, 3);
            	if($data_count != $num){
            		$kode = ++$cek_data[$cek_data->count() - 1]->kode_shift;
            	}else{
            		if($data_count < 9){
            			$kode = "JK-000" . ($data_count + 1);
            		}else if($data_count >= 9 && $data_count < 99){
            			$kode = "JK-00" . ($data_count + 1);
            		}else if($data_count >= 99 && $data_count < 999){
            			$kode = "JK-0" . ($data_count + 1);
            		}else{
            			$kode = "JK-" . ($data_count + 1);
            		}
            	}
            }else{
            	$kode = "JK-0001";
            }
        }else{
            $kode = "JK-0001";
        }

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('shift')->insert(['kode_shift' => $kode, 'nama_shift' => $request->nama, 'jam_masuk' => $request->jam_masuk, 'jam_keluar' => $request->jam_keluar, 'description' => $request->description, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_shift')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Shift Dengan Kode ' . $kode]);

        return Response()->json($arr);
    }

    public function deleteShift(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('shift')->where('kode_shift', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_shift')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Shift Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewShift($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('shift')->where('kode_shift', $val_id)->first();

        return Response()->json($data);
    }

    public function editShift(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('shift')->where('kode_shift', $request->edit_kode)->update(['nama_shift' => $request->edit_nama, 'jam_masuk' => $request->edit_jam_masuk, 'jam_keluar' => $request->edit_jam_keluar, 'description' => $request->edit_description, 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_shift')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Shift Dengan Kode ' . $request->edit_kode]);

        return Response()->json($arr);
    }

    public function viewBagianTable(Request $request){
        $list = DB::table('bagian')->select('bagian.kode_bagian as kode', 'bagian.kode_perusahaan as company', 'bagian.nama_bagian as nama', 'bagian.description', 'unit.nama_unit as unit')->join('unit', 'unit.kode_unit', '=', 'bagian.kode_unit')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_bagian')->rawColumns(['action'])->make(true);
    }

    public function inputBagian(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('bagian')->insert(['kode_bagian' => $request->kode, 'kode_perusahaan' => $request->company, 'kode_unit' => $request->unit, 'nama_bagian' => $request->nama, 'description' => $request->description, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_bagian')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Bagian Dengan Kode ' . $request->kode]);

        return Response()->json($arr);
    }

    public function deleteBagian(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('bagian')->where('kode_bagian', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_bagian')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Bagian Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewBagian($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('bagian')->select('kode_bagian as kode', 'nama_bagian as nama', 'bagian.description', 'com.nama_perusahaan as company', 'bagian.kode_perusahaan', 'unit.nama_unit as unit', 'bagian.kode_unit')->join('company as com', 'com.kode_perusahaan', '=', 'bagian.kode_perusahaan')->join('unit', 'unit.kode_unit', '=', 'bagian.kode_unit')->where('kode_bagian', $val_id)->first();

        return Response()->json($data);
    }

    public function editBagian(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('bagian')->where('kode_bagian', $request->edit_kode_lama)->update(['kode_bagian' => $request->edit_kode, 'nama_bagian' => $request->edit_nama, 'kode_perusahaan' => $request->edit_company, 'kode_unit' => $request->edit_unit, 'description' => $request->edit_description, 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_bagian')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Bagian Dengan Kode ' . $request->edit_kode]);

        return Response()->json($arr);
    }

    public function viewKaryawanTable(Request $request){
        $list = DB::table('karyawan')->select('karyawan.nomor_karyawan as nomor', 'karyawan.kode_perusahaan as company', 'karyawan.nama_karyawan as nama', 'bagian.nama_bagian as bagian', 'karyawan.alamat', 'karyawan.status_karyawan as status')->leftJoin('bagian', 'bagian.kode_bagian', '=', 'karyawan.kode_bagian')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_karyawan')->rawColumns(['action'])->make(true);
    }

    public function inputKaryawan(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	$cek_data = DB::table('karyawan')->select('nomor_karyawan')->where('nomor_karyawan', 'like', 'KRYWN%')->orderBy('nomor_karyawan', 'asc')->distinct()->get();

        if($cek_data){
            $data_count = $cek_data->count();
            if($data_count > 0){
            	$num = (int) substr($cek_data[$cek_data->count() - 1]->nomor_karyawan, 5);
            	if($data_count != $num){
            		$kode = ++$cek_data[$cek_data->count() - 1]->nomor_karyawan;
            	}else{
            		if($data_count < 9){
            			$kode = "KRYWN000" . ($data_count + 1);
            		}else if($data_count >= 9 && $data_count < 99){
            			$kode = "KRYWN00" . ($data_count + 1);
            		}else if($data_count >= 99 && $data_count < 999){
            			$kode = "KRYWN0" . ($data_count + 1);
            		}else{
            			$kode = "KRYWN" . ($data_count + 1);
            		}
            	}
            }else{
            	$kode = "KRYWN0001";
            }
        }else{
            $kode = "KRYWN0001";
        }

        DB::table('hari_kerja_karyawan')->insert(['nomor_karyawan' => $kode]);

        if(isset($request->hari_kerja_senin)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $kode)->update(['senin' => 1]);
        }

        if(isset($request->hari_kerja_selasa)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $kode)->update(['selasa' => 1]);
        }

        if(isset($request->hari_kerja_rabu)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $kode)->update(['rabu' => 1]);
        }

        if(isset($request->hari_kerja_kamis)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $kode)->update(['kamis' => 1]);
        }

        if(isset($request->hari_kerja_jumat)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $kode)->update(['jumat' => 1]);
        }

        if(isset($request->hari_kerja_sabtu)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $kode)->update(['sabtu' => 1]);
        }

        if(isset($request->hari_kerja_minggu)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $kode)->update(['minggu' => 1]);
        }

    	if($request->hasFile('photo')) {
    		$file_photo = $request->file('photo');
    		$nama_file_photo = time()."_".$request->nama."_".$file_photo->getClientOriginalName();
    		$tujuan_upload = 'data_file';
    		$file_photo->move($tujuan_upload, $nama_file_photo);

    		date_default_timezone_set('Asia/Jakarta');
    		$data = DB::table('karyawan')->insert(['nomor_karyawan' => $kode, 'nama_karyawan' => $request->nama, 'kode_perusahaan' => $request->company, 'kode_unit' => $request->unit, 'kode_bagian' => $request->bagian, 'kode_shift' => $request->shift, 'jenis_kelamin' => $request->jenis_kelamin, 'tanggal_lahir' => $request->tanggal_lahir, 'alamat' => $request->alamat, 'kelas_pendidikan' => $request->pendidikan, 'kelas_pribadi' => $request->kelas_pribadi, 'kelas_jabatan' => $request->jabatan, 'ket_kelas_pribadi' => $request->ket_kelas_pribadi, 'ket_jabatan' => $request->ket_jabatan, 'jumlah_tanggungan' => $request->jumlah_tanggungan, 'status_pernikahan' => $request->menikah, 'status_karyawan' => $request->status_karyawan, 'nomor_hp' => $request->nomor_hp, 'tanggal_mulai_bekerja' => $request->tanggal_mulai_bekerja, 'photo' => $nama_file_photo, 'golongan_upah' => $request->golongan_upah, 'jumlah_jam_kerja' => $request->jumlah_jam_kerja, 'status_periode' => $request->status_periode, 'pph10' => $request->pph, 'astek' => $request->astek, 'hitungan_pph' => $request->hitungan_pph, 'tunjangan_masa_kerja' => $request->tunjangan_masa_kerja, 'perhitungan_lembur' => $request->perhitungan_lembur, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);
    	}else{
    		date_default_timezone_set('Asia/Jakarta');
    		$data = DB::table('karyawan')->insert(['nomor_karyawan' => $kode, 'nama_karyawan' => $request->nama, 'kode_perusahaan' => $request->company, 'kode_unit' => $request->unit, 'kode_bagian' => $request->bagian, 'kode_shift' => $request->shift, 'jenis_kelamin' => $request->jenis_kelamin, 'tanggal_lahir' => $request->tanggal_lahir, 'alamat' => $request->alamat, 'kelas_pendidikan' => $request->pendidikan, 'kelas_pribadi' => $request->kelas_pribadi, 'kelas_jabatan' => $request->jabatan, 'ket_kelas_pribadi' => $request->ket_kelas_pribadi, 'ket_jabatan' => $request->ket_jabatan, 'jumlah_tanggungan' => $request->jumlah_tanggungan, 'status_pernikahan' => $request->menikah, 'status_karyawan' => $request->status_karyawan, 'nomor_hp' => $request->nomor_hp, 'tanggal_mulai_bekerja' => $request->tanggal_mulai_bekerja, 'golongan_upah' => $request->golongan_upah, 'jumlah_jam_kerja' => $request->jumlah_jam_kerja, 'status_periode' => $request->status_periode, 'pph10' => $request->pph, 'astek' => $request->astek, 'hitungan_pph' => $request->hitungan_pph, 'tunjangan_masa_kerja' => $request->tunjangan_masa_kerja, 'perhitungan_lembur' => $request->perhitungan_lembur, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);
    	}

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_karyawan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Karyawan Dengan NIK ' . $kode]);

        return Response()->json($arr);
    }

    public function deleteKaryawan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('karyawan')->where('nomor_karyawan', $request->get('nomor'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_karyawan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Karyawan Dengan NIK ' . $request->get('nomor')]);

        return Response()->json($arr);
    }

    public function viewKaryawan($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('karyawan')->select('karyawan.nomor_karyawan as nomor', 'nama_karyawan as nama', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'kelas_pribadi', 'status_pernikahan', 'jumlah_tanggungan', 'status_karyawan', 'nomor_hp', 'tanggal_mulai_bekerja', 'photo', 'com.nama_perusahaan as company', 'unit.nama_unit as unit', 'bagian.nama_bagian as bagian', 'shift.nama_shift as shift', 'pend.name as pendidikan', 'class.name as jabatan', 'karyawan.ket_kelas_pribadi', 'karyawan.ket_jabatan', 'karyawan.kode_perusahaan', 'karyawan.kode_unit', 'karyawan.kode_bagian', 'karyawan.kode_shift', 'karyawan.kelas_pendidikan', 'karyawan.kelas_jabatan', 'karyawan.golongan_upah', 'karyawan.jumlah_jam_kerja', 'karyawan.status_periode', 'karyawan.pph10', 'karyawan.astek', 'karyawan.hitungan_pph', 'karyawan.tunjangan_masa_kerja', 'karyawan.perhitungan_lembur', 'hkk.senin', 'hkk.selasa', 'hkk.rabu', 'hkk.kamis', 'hkk.jumat', 'hkk.sabtu', 'hkk.minggu', DB::raw("concat(if(hkk.senin = 1, concat('Senin; '), ''), if(hkk.selasa = 1, concat('Selasa; '), ''), if(hkk.rabu = 1, concat('Rabu; '), ''), if(hkk.kamis = 1, concat('Kamis; '), ''), if(hkk.jumat = 1, concat('Jumat; '), ''), if(hkk.sabtu = 1, concat('Sabtu; '), ''), if(hkk.minggu = 1, concat('Minggu; '), '')) as hari_kerja"))->leftJoin('hari_kerja_karyawan as hkk', 'hkk.nomor_karyawan', '=', 'karyawan.nomor_karyawan')->join('company as com', 'com.kode_perusahaan', '=', 'karyawan.kode_perusahaan')->join('unit', 'unit.kode_unit', '=', 'karyawan.kode_unit')->join('bagian', 'bagian.kode_bagian', '=', 'karyawan.kode_bagian')->join('shift', 'shift.kode_shift', '=', 'karyawan.kode_shift')->join('tbl_pendidikan as pend', 'pend.id', '=', 'karyawan.kelas_pendidikan')->join('user_class as class', 'class.id', '=', 'karyawan.kelas_jabatan')->where('karyawan.nomor_karyawan', $val_id)->first();

        return Response()->json($data);
    }

    public function editKaryawan(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if(isset($request->edit_hari_kerja_senin)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['senin' => 1]);
        }else{
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['senin' => 0]);
        }

        if(isset($request->edit_hari_kerja_selasa)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['selasa' => 1]);
        }else{
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['selasa' => 0]);
        }

        if(isset($request->edit_hari_kerja_rabu)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['rabu' => 1]);
        }else{
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['rabu' => 0]);
        }

        if(isset($request->edit_hari_kerja_kamis)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['kamis' => 1]);
        }else{
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['kamis' => 0]);
        }

        if(isset($request->edit_hari_kerja_jumat)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['jumat' => 1]);
        }else{
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['jumat' => 0]);
        }

        if(isset($request->edit_hari_kerja_sabtu)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['sabtu' => 1]);
        }else{
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['sabtu' => 0]);
        }

        if(isset($request->edit_hari_kerja_minggu)){
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['minggu' => 1]);
        }else{
            DB::table('hari_kerja_karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['minggu' => 0]);
        }

    	if($request->hasFile('edit_photo')) {
    		$data_foto = DB::table('karyawan')->select('photo')->where('nomor_karyawan', $request->edit_nomor)->first();
            File::delete('data_file/' . $data_foto->photo);

    		$file_photo = $request->file('edit_photo');
    		$nama_file_photo = time()."_".$request->nama."_".$file_photo->getClientOriginalName();
    		$tujuan_upload = 'data_file';
    		$file_photo->move($tujuan_upload, $nama_file_photo);

    		date_default_timezone_set('Asia/Jakarta');
        	$data = DB::table('karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['nama_karyawan' => $request->edit_nama, 'kode_perusahaan' => $request->edit_company, 'kode_unit' => $request->edit_unit, 'kode_bagian' => $request->edit_bagian, 'kode_shift' => $request->edit_shift, 'jenis_kelamin' => $request->edit_jenis_kelamin, 'tanggal_lahir' => $request->edit_tanggal_lahir, 'alamat' => $request->edit_alamat, 'kelas_pendidikan' => $request->edit_pendidikan, 'kelas_pribadi' => $request->edit_kelas_pribadi, 'kelas_jabatan' => $request->edit_jabatan, 'ket_kelas_pribadi' => $request->edit_ket_kelas_pribadi, 'ket_jabatan' => $request->edit_ket_jabatan, 'jumlah_tanggungan' => $request->edit_jumlah_tanggungan, 'status_pernikahan' => $request->edit_menikah, 'status_karyawan' => $request->edit_status_karyawan, 'nomor_hp' => $request->edit_nomor_hp, 'tanggal_mulai_bekerja' => $request->edit_tanggal_mulai_bekerja, 'photo' => $nama_file_photo, 'golongan_upah' => $request->edit_golongan_upah, 'jumlah_jam_kerja' => $request->edit_jumlah_jam_kerja, 'status_periode' => $request->edit_status_periode, 'pph10' => $request->edit_pph, 'astek' => $request->edit_astek, 'hitungan_pph' => $request->edit_hitungan_pph, 'tunjangan_masa_kerja' => $request->edit_tunjangan_masa_kerja, 'perhitungan_lembur' => $request->edit_perhitungan_lembur, 'updated_at' => date("Y-m-d H:i:s")]);
    	}else{
    		date_default_timezone_set('Asia/Jakarta');
        	$data = DB::table('karyawan')->where('nomor_karyawan', $request->edit_nomor)->update(['nama_karyawan' => $request->edit_nama, 'kode_perusahaan' => $request->edit_company, 'kode_unit' => $request->edit_unit, 'kode_bagian' => $request->edit_bagian, 'kode_shift' => $request->edit_shift, 'jenis_kelamin' => $request->edit_jenis_kelamin, 'tanggal_lahir' => $request->edit_tanggal_lahir, 'alamat' => $request->edit_alamat, 'kelas_pendidikan' => $request->edit_pendidikan, 'kelas_pribadi' => $request->edit_kelas_pribadi, 'kelas_jabatan' => $request->edit_jabatan, 'ket_kelas_pribadi' => $request->edit_ket_kelas_pribadi, 'ket_jabatan' => $request->edit_ket_jabatan, 'jumlah_tanggungan' => $request->edit_jumlah_tanggungan, 'status_pernikahan' => $request->edit_menikah, 'status_karyawan' => $request->edit_status_karyawan, 'nomor_hp' => $request->edit_nomor_hp, 'tanggal_mulai_bekerja' => $request->edit_tanggal_mulai_bekerja, 'golongan_upah' => $request->edit_golongan_upah, 'jumlah_jam_kerja' => $request->edit_jumlah_jam_kerja, 'status_periode' => $request->edit_status_periode, 'pph10' => $request->edit_pph, 'astek' => $request->edit_astek, 'hitungan_pph' => $request->edit_hitungan_pph, 'tunjangan_masa_kerja' => $request->edit_tunjangan_masa_kerja, 'perhitungan_lembur' => $request->edit_perhitungan_lembur, 'updated_at' => date("Y-m-d H:i:s")]);
    	}

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_karyawan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Karyawan Dengan NIK ' . $request->edit_nomor]);

        return Response()->json($arr);
    }

    public function viewParameterKPITable(Request $request){
        $list = DB::table('parameter_kpi')->select('kode_parameter_kpi as kode', 'parameter_kpi.kode_perusahaan as company', 'bagian.nama_bagian as bagian', 'unit.nama_unit as unit')->join('unit', 'unit.kode_unit', '=', 'parameter_kpi.kode_unit')->join('bagian', 'bagian.kode_bagian', '=', 'parameter_kpi.kode_bagian')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_bagian')->rawColumns(['action'])->make(true);
    }

    public function inputParameterKPI(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	$cek_data = DB::table('parameter_kpi')->select('kode_parameter_kpi')->where('kode_parameter_kpi', 'like', 'KPIPRM%')->orderBy('kode_parameter_kpi', 'asc')->distinct()->get();

        if($cek_data){
            $data_count = $cek_data->count();
            if($data_count > 0){
	            $num = (int) substr($cek_data[$cek_data->count() - 1]->kode_parameter_kpi, 6);
	            if($data_count != $num){
	            	$kode = ++$cek_data[$cek_data->count() - 1]->kode_parameter_kpi;
	            }else{
		            if($data_count < 9){
		                $kode = "KPIPRM-000" . ($data_count + 1);
		            }else if($data_count >= 9 && $data_count < 99){
		                $kode = "KPIPRM-00" . ($data_count + 1);
		            }else if($data_count >= 99 && $data_count < 999){
		                $kode = "KPIPRM-0" . ($data_count + 1);
		            }else{
		                $kode = "KPIPRM-" . ($data_count + 1);
		            }
		        }
		    }else{
		    	$kode = "KPIPRM-0001";
		    }
        }else{
            $kode = "KPIPRM-0001";
        }

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('parameter_kpi')->insert(['kode_parameter_kpi' => $kode, 'kode_perusahaan' => $request->get('company'), 'kode_unit' => $request->get('unit'), 'kode_bagian' => $request->get('bagian'), 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        $details = DB::table('temp_param_kpi')->select('indikator', 'persentase', 'description')->where('id_user', Session::get('id_user_admin'))->get();

        foreach($details as $det){
        	$cek_details = DB::table('parameter_kpi_detail')->select('kode_parameter_kpi_detail')->where('kode_parameter_kpi_detail', 'like', 'KPIPRMDT%')->orderBy('kode_parameter_kpi_detail', 'asc')->distinct()->get();

        	if($cek_details){
        		$details_count = $cek_details->count();
        		if($details_count > 0){
		            $num = (int) substr($cek_details[$cek_details->count() - 1]->kode_parameter_kpi_detail, 8);
		            if($details_count != $num){
		            	$kode_details = ++$cek_details[$cek_details->count() - 1]->kode_parameter_kpi_detail;
		            }else{
		            	if($details_count < 9){
		            		$kode_details = "KPIPRMDT-0000" . ($details_count + 1);
		            	}else if($details_count >= 9 && $details_count < 99){
		            		$kode_details = "KPIPRMDT-000" . ($details_count + 1);
		            	}else if($details_count >= 99 && $details_count < 999){
		            		$kode_details = "KPIPRMDT-00" . ($details_count + 1);
		            	}else if($details_count >= 999 && $details_count < 9999){
		            		$kode_details = "KPIPRMDT-0" . ($details_count + 1);
		            	}else{
		            		$kode_details = "KPIPRMDT-" . ($details_count + 1);
		            	}
		            }
		        }else{
		        	$kode_details = "KPIPRMDT-00001";
		        }
        	}else{
        		$kode_details = "KPIPRMDT-00001";
        	}

        	$data_details = DB::table('parameter_kpi_detail')->insert(['kode_parameter_kpi_detail' => $kode_details, 'kode_parameter_kpi' => $kode, 'indikator' => $det->indikator, 'persentase' => $det->persentase, 'description' => $det->description]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_data_parameter_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Detail Parameter KPI Dengan Kode ' . $kode_details]);
        }

        DB::table('temp_param_kpi')->where('id_user', Session::get('id_user_admin'))->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_parameter_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Parameter KPI Dengan Kode ' . $kode]);

        return Response()->json($arr);
    }

    public function deleteParameterKPI(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('parameter_kpi')->where('kode_parameter_kpi', $request->get('kode'))->delete();

        $hapus = DB::table('parameter_kpi_detail')->where('kode_parameter_kpi', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_parameter_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Parameter KPI Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewParameterKPI($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('parameter_kpi')->select('kode_parameter_kpi as kode', 'com.nama_perusahaan as company', 'parameter_kpi.kode_perusahaan', 'unit.nama_unit as unit', 'parameter_kpi.kode_unit', 'bagian.nama_bagian as bagian', 'parameter_kpi.kode_bagian')->join('company as com', 'com.kode_perusahaan', '=', 'parameter_kpi.kode_perusahaan')->join('unit', 'unit.kode_unit', '=', 'parameter_kpi.kode_unit')->join('bagian', 'bagian.kode_bagian', '=', 'parameter_kpi.kode_bagian')->where('kode_parameter_kpi', $val_id)->first();

        return Response()->json($data);
    }

    public function editParameterKPI(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('parameter_kpi')->where('kode_parameter_kpi', $request->get('kode'))->update(['kode_perusahaan' => $request->get('company'), 'kode_unit' => $request->get('unit'), 'kode_bagian' => $request->get('bagian'), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_parameter_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Parameter KPI Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewParameterKPIDetailTable(Request $request){
        $list = DB::table('temp_param_kpi')->select('indikator', 'persentase')->where('id_user', $request->id_user)->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_parameter_kpi_detail')->rawColumns(['action'])->make(true);
    }

    public function viewEditParameterKPIDetailTable(Request $request){
        $list = DB::table('parameter_kpi_detail')->select('kode_parameter_kpi_detail as kode', 'indikator', 'persentase')->where('kode_parameter_kpi', $request->kode)->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_edit_parameter_kpi_detail')->rawColumns(['action'])->make(true);
    }

    public function inputParameterKPIDetail(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('temp_param_kpi')->insert(['id_user' => Session::get('id_user_admin'), 'indikator' => $request->indikator, 'persentase' => $request->persentase, 'description' => $request->description]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function inputEditParameterKPIDetail(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	$cek_details = DB::table('parameter_kpi_detail')->select('kode_parameter_kpi_detail')->where('kode_parameter_kpi_detail', 'like', 'KPIPRMDT%')->orderBy('kode_parameter_kpi_detail', 'asc')->distinct()->get();

    	if($cek_details){
    		$details_count = $cek_details->count();
    		if($details_count > 0){
    			$num = (int) substr($cek_details[$cek_details->count() - 1]->kode_parameter_kpi_detail, 8);
    			if($details_count != $num){
    				$kode_details = ++$cek_details[$cek_details->count() - 1]->kode_parameter_kpi_detail;
    			}else{
    				if($details_count < 9){
    					$kode_details = "KPIPRMDT-0000" . ($details_count + 1);
    				}else if($details_count >= 9 && $details_count < 99){
    					$kode_details = "KPIPRMDT-000" . ($details_count + 1);
    				}else if($details_count >= 99 && $details_count < 999){
    					$kode_details = "KPIPRMDT-00" . ($details_count + 1);
    				}else if($details_count >= 999 && $details_count < 9999){
    					$kode_details = "KPIPRMDT-0" . ($details_count + 1);
    				}else{
    					$kode_details = "KPIPRMDT-" . ($details_count + 1);
    				}
    			}
    		}else{
    			$kode_details = "KPIPRMDT-00001";
    		}
    	}else{
    		$kode_details = "KPIPRMDT-00001";
    	}

        $data = DB::table('parameter_kpi_detail')->insert(['kode_parameter_kpi_detail' => $kode_details, 'kode_parameter_kpi' => $request->get('kode'), 'indikator' => $request->get('indikator'), 'persentase' => $request->get('persentase'), 'description' => $request->get('description')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_parameter_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Detail Parameter KPI Dengan Kode ' . $kode_details]);

        return Response()->json($arr);
    }

    public function deleteParameterKPIDetail(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('temp_param_kpi')->where('indikator', $request->get('indikator'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteEditParameterKPIDetail(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('parameter_kpi_detail')->where('kode_parameter_kpi_detail', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_parameter_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Detail Parameter KPI Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewParameterKPIDetail($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('parameter_kpi_detail')->select('kode_parameter_kpi_detail as kode', 'indikator', 'persentase', 'description')->where('kode_parameter_kpi', $val_id)->get();

        return Response()->json($data);
    }

    public function viewEditParameterKPIDetail($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('parameter_kpi_detail')->select('kode_parameter_kpi_detail as kode', 'indikator', 'persentase', 'description')->where('kode_parameter_kpi_detail', $val_id)->first();

        return Response()->json($data);
    }

    public function editParameterKPIDetail(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('parameter_kpi_detail')->where('kode_parameter_kpi_detail', $request->get('kode'))->update(['indikator' => $request->get('indikator'), 'persentase' => $request->get('persentase'), 'description' => $request->get('description')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_parameter_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Detail Parameter KPI Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function dataKaryawan(Request $request){
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = DB::table("karyawan")->select("nomor_karyawan","nama_karyawan")
                    ->where('nama_karyawan','LIKE',"%$search%")
                    ->get();
        }else{
            $data = DB::table("karyawan")->select("nomor_karyawan","nama_karyawan")
                    ->get();
        }
        return response()->json($data);
    }

    public function viewKPIKaryawanTable(Request $request){
        $list = DB::table('kpi_karyawan')->select('kode_kpi_karyawan as kode', 'kpi_karyawan.kode_perusahaan as company', 'bagian.nama_bagian as bagian', 'unit.nama_unit as unit', 'karyawan.nama_karyawan as karyawan', 'periode')->join('unit', 'unit.kode_unit', '=', 'kpi_karyawan.kode_unit')->join('bagian', 'bagian.kode_bagian', '=', 'kpi_karyawan.kode_bagian')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'kpi_karyawan.nomor_karyawan')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_kpi_karyawan')->rawColumns(['action'])->make(true);
    }

    public function inputKPIKaryawan(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $split = explode(' - ', $request->periode_kpi);

    	$tanggal = date('ym');

    	$cek_data = DB::table('kpi_karyawan')->select('kode_kpi_karyawan')->where('kode_kpi_karyawan', 'like', 'KPI%')->orderBy('kode_kpi_karyawan', 'asc')->distinct()->get();

        if($cek_data){
            $data_count = $cek_data->count();
            if($data_count > 0){
	            $num = (int) substr($cek_data[$cek_data->count() - 1]->kode_kpi_karyawan, 7);
	            if($data_count != $num){
	            	$kode = ++$cek_data[$cek_data->count() - 1]->kode_kpi_karyawan;
	            }else{
		            if($data_count < 9){
		                $kode = "KPI" . $tanggal . "0000" . ($data_count + 1);
		            }else if($data_count >= 9 && $data_count < 99){
		                $kode = "KPI" . $tanggal . "000" . ($data_count + 1);
		            }else if($data_count >= 99 && $data_count < 999){
		                $kode = "KPI" . $tanggal . "00" . ($data_count + 1);
		            }else if($data_count >= 999 && $data_count < 9999){
		                $kode = "KPI" . $tanggal . "0" . ($data_count + 1);
		            }else{
		                $kode = "KPI" . $tanggal . ($data_count + 1);
		            }
		        }
		    }else{
		    	$kode = "KPI" . $tanggal . "00001";
		    }
        }else{
            $kode = "KPI" . $tanggal . "00001";
        }

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kpi_karyawan')->insert(['kode_kpi_karyawan' => $kode, 'kode_perusahaan' => $request->company, 'kode_unit' => $request->unit, 'kode_bagian' => $request->bagian, 'kode_parameter_kpi' => $request->kode_kpi, 'periode' => $request->periode, 'from_periode_kpi' => $split[0], 'to_periode_kpi' => $split[1], 'nomor_karyawan' => $request->karyawan, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        $number = count($request->kode_parameter_kpi);

        for($i=0; $i<$number; $i++){
        	$cek_details = DB::table('kpi_karyawan_detail')->select('kode_kpi_karyawan_detail')->where('kode_kpi_karyawan_detail', 'like', 'KPIDT%')->orderBy('kode_kpi_karyawan_detail', 'asc')->distinct()->get();

        	if($cek_details){
        		$details_count = $cek_details->count();
        		if($details_count > 0){
		            $num = (int) substr($cek_details[$cek_details->count() - 1]->kode_kpi_karyawan_detail, 8);
		            if($details_count != $num){
		            	$kode_details = ++$cek_details[$cek_details->count() - 1]->kode_kpi_karyawan_detail;
		            }else{
		            	if($details_count < 9){
		            		$kode_details = "KPIDT" . $tanggal . "00000" . ($details_count + 1);
		            	}else if($details_count >= 9 && $details_count < 99){
		            		$kode_details = "KPIDT" . $tanggal . "0000" . ($details_count + 1);
		            	}else if($details_count >= 99 && $details_count < 999){
		            		$kode_details = "KPIDT" . $tanggal . "000" . ($details_count + 1);
		            	}else if($details_count >= 999 && $details_count < 9999){
		            		$kode_details = "KPIDT" . $tanggal . "00" . ($details_count + 1);
		            	}else if($details_count >= 9999 && $details_count < 99999){
		            		$kode_details = "KPIDT" . $tanggal . "0" . ($details_count + 1);
		            	}else{
		            		$kode_details = "KPIDT" . $tanggal . ($details_count + 1);
		            	}
		            }
		        }else{
		        	$kode_details = "KPIDT" . $tanggal . "000001";
		        }
        	}else{
        		$kode_details = "KPIDT" . $tanggal . "000001";
        	}

        	$data_details = DB::table('kpi_karyawan_detail')->insert(['kode_kpi_karyawan_detail' => $kode_details, 'kode_kpi_karyawan' => $kode, 'kode_parameter_kpi_detail' => $request->kode_parameter_kpi[$i], 'pengali' => $request->pengali[$i], 'hari_kerja' => $request->hari_kerja[$i], 'penilaian' => $request->penilaian[$i], 'total_bonus' => $request->total_bonus[$i]]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_data_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Detail KPI Dengan Kode ' . $kode_details]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data KPI Dengan Kode ' . $kode]);

        return Response()->json($arr);
    }

    public function deleteKPIKaryawan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('kpi_karyawan')->where('kode_kpi_karyawan', $request->get('kode'))->delete();

        $hapus = DB::table('kpi_karyawan_detail')->where('kode_kpi_karyawan', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data KPI Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewKPIKaryawan($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('kpi_karyawan')->select('kode_kpi_karyawan as kode', 'com.nama_perusahaan as company', 'kpi_karyawan.kode_perusahaan', 'unit.nama_unit as unit', 'kpi_karyawan.kode_unit', 'bagian.nama_bagian as bagian', 'kpi_karyawan.kode_bagian', 'periode', 'kpi_karyawan.nomor_karyawan', 'karyawan.nama_karyawan as karyawan', 'kpi_karyawan.from_periode_kpi', 'kpi_karyawan.to_periode_kpi')->join('company as com', 'com.kode_perusahaan', '=', 'kpi_karyawan.kode_perusahaan')->join('unit', 'unit.kode_unit', '=', 'kpi_karyawan.kode_unit')->join('bagian', 'bagian.kode_bagian', '=', 'kpi_karyawan.kode_bagian')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'kpi_karyawan.nomor_karyawan')->where('kode_kpi_karyawan', $val_id)->first();

        return Response()->json($data);
    }

    public function editKPIKaryawan(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $split = explode(' - ', $request->edit_periode_kpi);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('kpi_karyawan')->where('kode_kpi_karyawan', $request->edit_kode)->update(['nomor_karyawan' => $request->edit_karyawan, 'periode' => $request->edit_periode, 'from_periode_kpi' => $split[0], 'to_periode_kpi' => $split[1], 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        $number = count($request->edit_kode_parameter_kpi);

        for($i=0; $i<$number; $i++){
        	$data_details = DB::table('kpi_karyawan_detail')->where('kode_kpi_karyawan_detail', $request->edit_kode_parameter_kpi[$i])->update(['pengali' => $request->edit_pengali[$i], 'hari_kerja' => $request->edit_hari_kerja[$i], 'penilaian' => $request->edit_penilaian[$i], 'total_bonus' => $request->edit_total_bonus[$i]]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_data_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Detail KPI Dengan Kode ' . $request->edit_kode_parameter_kpi[$i]]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_kpi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data KPI Dengan Kode ' . $request->edit_kode]);

        return Response()->json($arr);
    }

    public function getKPIKaryawanDetail(Request $request){
        $company = $this->decrypt($request->company);
        $unit = $this->decrypt($request->unit);
        $bagian = $this->decrypt($request->bagian);

        if(($company == NULL || $company == '') || ($unit == NULL || $unit == '') || ($bagian == NULL || $bagian == '')){
        	$list = array();
        }else{
        	$list = DB::table('parameter_kpi_detail')->select('kode_parameter_kpi_detail as kode', 'parameter_kpi_detail.kode_parameter_kpi as kode_kpi', 'indikator', 'persentase', 'description')->join('parameter_kpi as param', 'param.kode_parameter_kpi', '=', 'parameter_kpi_detail.kode_parameter_kpi')->where('param.kode_perusahaan', $company)->where('param.kode_unit', $unit)->where('param.kode_bagian', $bagian)->get();
        }

        return Response()->json($list);
    }

    public function viewKPIKaryawanDetail($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('kpi_karyawan_detail')->select('kode_kpi_karyawan_detail as kode', 'param.indikator', 'param.persentase', 'param.description', 'pengali', 'hari_kerja', 'penilaian', 'total_bonus')->join('parameter_kpi_detail as param', 'param.kode_parameter_kpi_detail', '=', 'kpi_karyawan_detail.kode_parameter_kpi_detail')->where('kode_kpi_karyawan', $val_id)->get();

        return Response()->json($data);
    }

}
