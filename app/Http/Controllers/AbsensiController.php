<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use File;
use Response;
Use Exception;
use App\Imports\AbsensiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AbsensiController extends Controller
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

    public function import_excel(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new AbsensiImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        $tanggal = $import->getListDate();
        $karyawan = $import->getListKaryawan();
        foreach($tanggal as $tanggal){
            foreach((array) $karyawan as $karyawan){
                $cek_absensi = DB::table('transaksi_absensi')->where('tanggal_absensi', $tanggal)->where('nik', $karyawan)->exists();

                if(!$cek_absensi){
                    $cek_izin = DB::table('izin')->where('tanggal', $tanggal)->where('karyawan', $karyawan)->exists();
                    $cek_cuti = DB::table('cuti')->where('tanggal', $tanggal)->where('karyawan', $karyawan)->exists();

                    if(!$cek_izin && !$cek_cuti){
                        DB::table('pelanggaran')->insert(['nik' => $karyawan, 'tanggal' => $tanggal, 'jenis_pelanggaran' => 2, 'alasan_pelanggaran' => 'Tidak Masuk Tanpa Keterangan']);
                    }
                }
            }
        }
        if($import->getDuplikat() == 0){
            return redirect('administrasi/absensi')->with('alert','Data Duplikat, Data Sudah Ada');
        }else{
            return redirect('administrasi/absensi')->with('alert','Sukses Menambahkan Data');
        }
    }

    public function jumlahOrangAbsen(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $absen = DB::table('transaksi_absensi')->select('tanggal_absensi', DB::raw("count(nik) as jumlah_karyawan"))->whereBetween('tanggal_absensi', array($request->from_date, $request->to_date))->groupBy('tanggal_absensi')->get();
            }else{
                $absen = DB::table('transaksi_absensi')->select('tanggal_absensi', DB::raw("count(nik) as jumlah_karyawan"))->whereRaw('MONTH(tanggal_absensi) = ?',[$currentMonth])->whereRaw('YEAR(tanggal_absensi) = ?',[$currentYear])->groupBy('tanggal_absensi')->get();
            }

            return datatables()->of($absen)->addIndexColumn()->make(true);
        }
        return view('absensi');
    } 

    public function viewAbsenTidakLengkapTable(Request $request){
        $absen = DB::table('transaksi_absensi')->select('id', 'tanggal_absensi', 'nama_karyawan as karyawan', 'jam_masuk', 'jam_pulang')->leftJoin('karyawan', 'karyawan.nomor_karyawan', '=', 'transaksi_absensi.nik')->where(function ($query) { $query->whereNull('jam_masuk')->orWhereNull('jam_pulang'); })->get();

        return datatables()->of($absen)->addIndexColumn()->addColumn('action', 'button/action_button_absensi_tidak_lengkap')->rawColumns(['action'])->make(true);
    } 

    public function viewDataAbsenTidakLengkap($id){
        $val_id = $this->decrypt($id);

        $absen = DB::table('transaksi_absensi')->select('id', 'nik', 'tanggal_absensi', 'nama_karyawan as karyawan', 'jam_masuk', 'jam_pulang')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'transaksi_absensi.nik')->where('id', $val_id)->first();

        return Response()->json($absen);
    }

    public function jumlahDetailAbsen(Request $request){
        if(request()->ajax()){    
            $absen = DB::table('transaksi_absensi')->select('nik as karyawan', 'jam_masuk', 'jam_pulang')->where('tanggal_absensi', $request->tanggal)->get();

            return datatables()->of($absen)->addIndexColumn()->make(true);
        }
        return view('absensi');
    } 

    public function inputAbsensi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $status_hari = 0;
        $status_minggu = 0;

        if(date('N', strtotime($request->tanggal_absensi)) == 7){
            $status_hari = 1;
            $status_minggu = 1;
        }

        $hari_besar = DB::table('hari_besar')->where('tanggal', $request->tanggal_absensi)->exists();

        if($hari_besar){
            $status_hari = 1;
        }

        $data = DB::table('transaksi_absensi')->insert(["nik" => $request->nik, "tanggal_absensi" => $request->tanggal_absensi, "jam_masuk" => $request->jam_masuk, "jam_pulang" => $request->jam_pulang, "status_hari" => $status_hari]);

        $shift = DB::table('karyawan')->select('shift.jam_masuk', 'shift.jam_keluar')->join('shift', 'shift.kode_shift', '=', 'karyawan.kode_shift')->where('nomor_karyawan', $request->nik)->first();

        $jam_kerja_masuk = strtotime($shift->jam_masuk);
        $jam_kerja_pulang = strtotime($shift->jam_keluar);
        $jam_masuk = strtotime($request->jam_masuk);
        $jam_pulang = strtotime($request->jam_pulang);
        $terlambat = ($jam_masuk - $jam_kerja_masuk) / 60;

        $data_karyawan = DB::table('karyawan')->select('golongan_upah')->where('nomor_karyawan', $request->nik)->first();

        $rumus = DB::table('rumus_upah')->select('upah_pokok', 'rumus_upah', 'rumus_uang_makan', 'rumus_bonus', 'rumus_premi', 'rumus_lembur')->where('kode_upah', $data_karyawan->golongan_upah)->first();

        if($terlambat > 15 && $terlambat < 240){
        	DB::table('pelanggaran')->insert(['nik' => $request->nik, 'tanggal' => $request->tanggal_absensi, 'jenis_pelanggaran' => 1, 'alasan_pelanggaran' => 'Terlambat ' . $terlambat . ' Menit']);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_transaksi_terlambat')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $request->nik, 'action' => 'Karyawan NIK ' . $request->nik . ' Terlambat Sebanyak ' . $terlambat . ' Menit']);
        }else if($terlambat > 240){
        	DB::table('pelanggaran')->insert(['nik' => $request->nik, 'tanggal' => $request->tanggal_absensi, 'jenis_pelanggaran' => 3, 'alasan_pelanggaran' => 'Terlambat Lebih Dari 4 Jam']);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_transaksi_terlambat')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $request->nik, 'action' => 'Karyawan NIK ' . $request->nik . ' Terlambat Sebanyak ' . $terlambat . ' Menit']);
        }

        $rumus_upah = eval($rumus->rumus_upah);
        $total_upah = floatval($kali_upah * $rumus->upah_pokok);
        DB::table('transaksi_upah')->insert(['nik' => $request->nik, 'tanggal' => $request->tanggal_absensi, 'upah' => $total_upah]);

        $rumus_bonus = eval($rumus->rumus_bonus);
        DB::table('transaksi_bonus')->insert(['nik' => $request->nik, 'tanggal' => $request->tanggal_absensi, 'bonus' => $bonus]);

        $cek_pelanggaran = DB::table('pelanggaran')->select('id')->where('nik', $request->nik)->whereRaw('YEAR(tanggal) = ?', [date('Y', strtotime($request->tanggal_absensi))])->whereRaw('MONTH(tanggal) = ?', [date('m', strtotime($request->tanggal_absensi))])->get();
        $count_terlambat = $cek_pelanggaran->count();
        $rumus_premi = eval($rumus->rumus_premi);

        $cek_premi = DB::table('transaksi_premi')->select('id')->where('nik', $request->nik)->whereRaw('YEAR(tanggal) = ?', [date('Y', strtotime($request->tanggal_absensi))])->whereRaw('MONTH(tanggal) = ?', [date('m', strtotime($request->tanggal_absensi))])->first();

        if($cek_premi){
            DB::table('transaksi_premi')->where('id', $cek_premi->id)->update(['premi' => $premi]);
        }else{
            DB::table('transaksi_premi')->insert(['tanggal' => $request->tanggal_absensi, 'nik' => $request->nik, 'premi' => $premi]);
        }

        $lembur = ($jam_pulang - $jam_kerja_pulang) / 60;

        if($lembur > 0){
            $data_lembur = DB::table('lembur')->select('jumlah_jam')->where('karyawan', $request->nik)->where('tanggal', $request->tanggal_absensi)->first();

            if($data_lembur){
                $lembur = (intval($lembur / 60)) * 60;
                if($lembur < $data_lembur->jumlah_jam){
                    $rumus_lembur = eval($rumus->rumus_lembur);
                    DB::table('transaksi_lembur')->insert(['nik' => $request->nik, 'tanggal' => $request->tanggal_absensi, 'lembur' => $lembur, 'uang_lembur' => $uang_lembur]);
                }else if($lembur >= $data_lembur->jumlah_jam){
                    $lembur = $data_lembur->jumlah_jam;
                    $rumus_lembur = eval($rumus->rumus_lembur);
                    DB::table('transaksi_lembur')->insert(['nik' => $request->nik, 'tanggal' => $request->tanggal_absensi, 'lembur' => $data_lembur->jumlah_jam, 'uang_lembur' => $uang_lembur]); 
                }
            }

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_transaksi_lembur')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $request->nik, 'action' => 'Karyawan NIK ' . $request->nik . ' Lembur Sebanyak ' . $lembur . ' Menit']);
        }

        if($data){
            $arr = array('msg' => 'Successfully Store Data', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_transaksi_absensi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'karyawan' => $request->nik, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Absensi Karyawan NIK ' . $request->nik . ' Secara Manual']);

        return Response()->json($arr);
    }

    public function editAbsensi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('transaksi_absensi')->where('id', $request->edit_id)->update(["jam_masuk" => $request->edit_jam_masuk, "jam_pulang" => $request->edit_jam_pulang]);

        $shift = DB::table('karyawan')->select('shift.jam_masuk', 'shift.jam_keluar')->join('shift', 'shift.kode_shift', '=', 'karyawan.kode_shift')->where('nomor_karyawan', $request->edit_nik)->first();

        $jam_kerja_masuk = strtotime($shift->jam_masuk);
        $jam_kerja_pulang = strtotime($shift->jam_keluar);
        $jam_masuk = strtotime($request->edit_jam_masuk);
        $jam_pulang = strtotime($request->edit_jam_pulang);

        $data_karyawan = DB::table('karyawan')->select('golongan_upah')->where('nomor_karyawan', $request->edit_nik)->first();

        $rumus = DB::table('rumus_upah')->select('upah_pokok', 'rumus_upah', 'rumus_uang_makan', 'rumus_bonus', 'rumus_premi', 'rumus_lembur')->where('kode_upah', $data_karyawan->golongan_upah)->first();

        $cek_pelanggaran = DB::table('pelanggaran')->where('nik', $request->edit_nik)->where('tanggal', $request->edit_tanggal)->exists();

        if(!$cek_pelanggaran){
            $terlambat = ($jam_masuk - $jam_kerja_masuk) / 60;

            if($terlambat > 15 && $terlambat < 240){
                DB::table('pelanggaran')->insert(['nik' => $request->edit_nik, 'tanggal' => $request->edit_tanggal, 'jenis_pelanggaran' => 1, 'alasan_pelanggaran' => 'Terlambat ' . $terlambat . ' Menit']);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_transaksi_terlambat')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $request->edit_nik, 'action' => 'Karyawan NIK ' . $request->edit_nik . ' Terlambat Sebanyak ' . $terlambat . ' Menit']);
            }else if($terlambat > 240){
                DB::table('pelanggaran')->insert(['nik' => $request->edit_nik, 'tanggal' => $request->edit_tanggal, 'jenis_pelanggaran' => 3, 'alasan_pelanggaran' => 'Terlambat Lebih Dari 4 Jam']);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_transaksi_terlambat')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $request->edit_nik, 'action' => 'Karyawan NIK ' . $request->edit_nik . ' Terlambat Sebanyak ' . $terlambat . ' Menit']);
            }

            $cek_pelanggaran = DB::table('pelanggaran')->select('id')->where('nik', $request->edit_nik)->whereRaw('YEAR(tanggal) = ?', [date('Y', strtotime($request->edit_tanggal))])->whereRaw('MONTH(tanggal) = ?', [date('m', strtotime($request->edit_tanggal))])->get();
            $count_terlambat = $cek_pelanggaran->count();
            $rumus_premi = eval($rumus->rumus_premi);

            $cek_premi = DB::table('transaksi_premi')->select('id')->where('nik', $request->edit_nik)->whereRaw('YEAR(tanggal) = ?', [date('Y', strtotime($request->edit_tanggal))])->whereRaw('MONTH(tanggal) = ?', [date('m', strtotime($request->edit_tanggal))])->first();

            if($cek_premi){
                DB::table('transaksi_premi')->where('id', $cek_premi->id)->update(['premi' => $premi]);
            }else{
                DB::table('transaksi_premi')->insert(['tanggal' => $request->edit_tanggal, 'nik' => $request->edit_nik, 'premi' => $premi]);
            }
        }

        $cek_lembur = DB::table('transaksi_lembur')->where('nik', $request->edit_nik)->where('tanggal', $request->edit_tanggal)->exists();

        if(!$cek_lembur){
            $lembur = ($jam_pulang - $jam_kerja_pulang) / 60;

            if($lembur > 0){
                $data_lembur = DB::table('lembur')->select('jumlah_jam')->where('karyawan', $request->edit_nik)->where('tanggal', $request->edit_tanggal)->first();
                if($data_lembur){
                    $lembur = (intval($lembur / 60)) * 60;
                    if($lembur < $data_lembur->jumlah_jam){
                        $rumus_lembur = eval($rumus->rumus_lembur);
                        DB::table('transaksi_lembur')->insert(['nik' => $request->edit_nik, 'tanggal' => $request->edit_tanggal, 'lembur' => $lembur, 'uang_lembur' => $uang_lembur]);
                    }else if($lembur >= $data_lembur->jumlah_jam){
                        $lembur = $data_lembur->jumlah_jam;
                        $rumus_lembur = eval($rumus->rumus_lembur);
                        DB::table('transaksi_lembur')->insert(['nik' => $request->edit_nik, 'tanggal' => $request->edit_tanggal, 'lembur' => $data_lembur->jumlah_jam, 'uang_lembur' => $uang_lembur]); 
                    }
                }

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_transaksi_lembur')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $request->edit_nik, 'action' => 'Karyawan NIK ' . $request->edit_nik . ' Lembur Sebanyak ' . $lembur . ' Menit']);
            }
        }

        if($data){
            $arr = array('msg' => 'Successfully Store Data', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_transaksi_absensi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'karyawan' => $request->edit_nik, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Absensi Karyawan NIK ' . $request->edit_nik]);

        return Response()->json($arr);
    }
}
