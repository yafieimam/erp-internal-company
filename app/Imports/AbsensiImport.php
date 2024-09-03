<?php

namespace App\Imports;

use App\ModelAbsensi;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;

class AbsensiImport implements ToModel, WithStartRow
{
    /**
    * @param Collection $collection
    */

    private $duplikat = 0;
    private $list_date = [];
    private $list_karyawan = [];

    public function transformDate($value, $format = 'Y-m-d H:i')
    {
        try {
            return Carbon::instance(Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat($format, date_format(date_create_from_format("d/m/Y H:i", $value), 'Y-m-d H:i'));
        }
    }

    public function model(array $row)
    {
        $status_hari = 0;
        $status_minggu = 0;
    	$tanggalwaktu = explode(' ', $this->transformDate($row[3]));

        if(!in_array($row[0], $this->list_karyawan)){
            array_push($this->list_karyawan, $row[0]);
        }

        $shift = DB::table('karyawan')->select('shift.jam_masuk', 'shift.jam_keluar')->join('shift', 'shift.kode_shift', '=', 'karyawan.kode_shift')->where('nomor_karyawan', $row[0])->first();

        $jam_kerja_masuk = strtotime($shift->jam_masuk);
        $jam_kerja_pulang = strtotime($shift->jam_keluar);

        if(date('N', strtotime($tanggalwaktu[0])) == 7){
            $status_hari = 1;
            $status_minggu = 1;
        }

        $hari_besar = DB::table('hari_besar')->where('tanggal', $tanggalwaktu[0])->exists();

        if($hari_besar){
            $status_hari = 1;
        }

        if($status_hari == 0){
            if(!in_array($tanggalwaktu[0], $this->list_date)){
                array_push($this->list_date, $tanggalwaktu[0]);
            }
        }

        $data_karyawan = DB::table('karyawan')->select('golongan_upah')->where('nomor_karyawan', $row[0])->first();

        $rumus = DB::table('rumus_upah')->select('upah_pokok', 'rumus_upah', 'rumus_uang_makan', 'rumus_bonus', 'rumus_premi', 'rumus_lembur')->where('kode_upah', $data_karyawan->golongan_upah)->first();
    	
    	if($row[6] != "Repeat"){
	        if(!ModelAbsensi::where('nik', $row[0])->where('tanggal_absensi', $tanggalwaktu[0])->exists()) {
	        	
	        	$this->duplikat = 1;

                $jam_masuk = strtotime($tanggalwaktu[1]);
                $terlambat = ($jam_masuk - $jam_kerja_masuk) / 60;
                
                if($terlambat > 15 && $terlambat < 240){
                    DB::table('pelanggaran')->insert(['nik' => $row[0], 'tanggal' => $tanggalwaktu[0], 'jenis_pelanggaran' => 1, 'pelanggaran' => $terlambat, 'alasan_pelanggaran' => 'Terlambat ' . $terlambat . ' Menit']);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_transaksi_terlambat')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $row[0], 'action' => 'Karyawan NIK ' . $row[0] . ' Terlambat Sebanyak ' . $terlambat . ' Menit']);
                }else if($terlambat > 240){
                    DB::table('pelanggaran')->insert(['nik' => $row[0], 'tanggal' => $tanggalwaktu[0], 'jenis_pelanggaran' => 3, 'pelanggaran' => $terlambat, 'alasan_pelanggaran' => 'Terlambat Lebih Dari 4 Jam']);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_transaksi_terlambat')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $row[0], 'action' => 'Karyawan NIK ' . $row[0] . ' Terlambat Sebanyak ' . $terlambat . ' Menit']);
                }

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_transaksi_absensi')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'karyawan' => $row[0], 'action' => 'User ' . Session::get('id_user_admin') . ' Input Absensi Karyawan NIK ' . $row[0] . ' Secara Upload Excel']);

                $rumus_upah = eval($rumus->rumus_upah);
                $total_upah = floatval($kali_upah * $rumus->upah_pokok);
                DB::table('transaksi_upah')->insert(['nik' => $row[0], 'tanggal' => $tanggalwaktu[0], 'upah' => $total_upah]);

                $rumus_bonus = eval($rumus->rumus_bonus);
                DB::table('transaksi_bonus')->insert(['nik' => $row[0], 'tanggal' => $tanggalwaktu[0], 'bonus' => $bonus]);

                $cek_terlambat = DB::table('pelanggaran')->select('id')->where('nik', $row[0])->whereRaw('YEAR(tanggal) = ?', [date('Y', strtotime($tanggalwaktu[0]))])->whereRaw('MONTH(tanggal) = ?', [date('m', strtotime($tanggalwaktu[0]))])->where('jenis_pelanggaran', 1)->get();
                $cek_tanpa_keterangan = DB::table('pelanggaran')->select('id')->where('nik', $row[0])->whereRaw('YEAR(tanggal) = ?', [date('Y', strtotime($tanggalwaktu[0]))])->whereRaw('MONTH(tanggal) = ?', [date('m', strtotime($tanggalwaktu[0]))])->where('jenis_pelanggaran', 2)->get();
                $count_terlambat = $cek_terlambat->count();
                $count_tanpa_keterangan = $cek_tanpa_keterangan->count();
                $rumus_premi = eval($rumus->rumus_premi);

                $cek_premi = DB::table('transaksi_premi')->select('id')->where('nik', $row[0])->whereRaw('YEAR(tanggal) = ?', [date('Y', strtotime($tanggalwaktu[0]))])->whereRaw('MONTH(tanggal) = ?', [date('m', strtotime($tanggalwaktu[0]))])->first();

                if($cek_premi){
                    DB::table('transaksi_premi')->where('id', $cek_premi->id)->update(['premi' => $premi]);
                }else{
                    DB::table('transaksi_premi')->insert(['tanggal' => $tanggalwaktu[0], 'nik' => $row[0], 'premi' => $premi]);
                }

	            return new ModelAbsensi([
	                'nik' => $row[0],
	                'tanggal_absensi' => $tanggalwaktu[0],
	                'jam_masuk' => $tanggalwaktu[1],
                    'status_hari' => $status_hari,
	            ]);

	        }else{
	        	$data = ModelAbsensi::select('jam_masuk', 'jam_pulang', 'status_hari')->where('nik', $row[0])->where('tanggal_absensi', $tanggalwaktu[0])->first();
	        	$start = strtotime($data->jam_masuk);
	        	$end = strtotime($tanggalwaktu[1]);
	        	if((($end - $start) / 60) > 60){
		        	if($data->jam_pulang == null){
		        		$this->duplikat = 1;

                        $jam_pulang = strtotime($tanggalwaktu[1]);

                        $lembur = ($jam_pulang - $jam_kerja_pulang) / 60;

                        if($lembur > 0){
                            $data_lembur = DB::table('lembur')->select('jumlah_jam')->where('karyawan', $row[0])->where('tanggal', $tanggalwaktu[0])->first();

                            if($data_lembur){
                                $lembur = (intval($lembur / 60)) * 60;
                                if($lembur < $data_lembur->jumlah_jam){
                                    $rumus_lembur = eval($rumus->rumus_lembur);

                                    DB::table('transaksi_lembur')->insert(['nik' => $row[0], 'tanggal' => $tanggalwaktu[0], 'lembur' => $lembur, 'uang_lembur' => $uang_lembur]);
                                }else if($lembur >= $data_lembur->jumlah_jam){
                                    $lembur = $data_lembur->jumlah_jam;

                                    $rumus_lembur = eval($rumus->rumus_lembur);

                                    DB::table('transaksi_lembur')->insert(['nik' => $row[0], 'tanggal' => $tanggalwaktu[0], 'lembur' => $data_lembur->jumlah_jam, 'uang_lembur' => $uang_lembur]); 
                                }
                            }

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_transaksi_lembur')->insert(['tanggal' => date("Y-m-d H:i:s"), 'karyawan' => $row[0], 'action' => 'Karyawan NIK ' . $row[0] . ' Lembur Sebanyak ' . $lembur . ' Menit']);
                        }

		        		$tes = ModelAbsensi::where('nik', $row[0])->where('tanggal_absensi', $tanggalwaktu[0])->update(['jam_pulang' => $tanggalwaktu[1]]);
		        	}else{
		        		$this->duplikat = 0;

		        	}
		        }
	        }
	    }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getDuplikat(): int
    {
        return $this->duplikat;
    }

    public function getListDate()
    {
        return $this->list_date;
    }

    public function getListKaryawan()
    {
        return $this->list_karyawan;
    }
}
