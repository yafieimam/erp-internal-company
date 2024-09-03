<?php

namespace App\Imports;

use App\ModelPermintaanSampleDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PermintaanSampleDetailImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function __construct($nomor_permintaan_sample)
    {
        $this->nomor_permintaan_sample = $nomor_permintaan_sample;
    }

    public function model(array $row)
    {
    	$tanggal = date('ym');

    	$data_permintaan = DB::table('permintaan_sample_detail')->select('nomor_permintaan_sample_detail')->where('nomor_permintaan_sample_detail', 'like', 'PMSAMPLE-DET' . $tanggal . '%')->orderBy('nomor_permintaan_sample_detail', 'asc')->distinct()->get();

    	if($data_permintaan){
    		$permintaan_count = $data_permintaan->count();
    		if($permintaan_count > 0){
    			$num = (int) substr($data_permintaan[$data_permintaan->count() - 1]->nomor_permintaan_sample_detail, 17);
    			if($permintaan_count != $num){
    				$kode_permintaan_sample_detail = ++$data_permintaan[$data_permintaan->count() - 1]->nomor_permintaan_sample_detail;
    			}else{
    				if($permintaan_count < 9){
    					$kode_permintaan_sample_detail = "PMSAMPLE-DET" . $tanggal . "-000" . ($permintaan_count + 1);
    				}else if($permintaan_count >= 9 && $permintaan_count < 99){
    					$kode_permintaan_sample_detail = "PMSAMPLE-DET" . $tanggal . "-00" . ($permintaan_count + 1);
    				}else if($permintaan_count >= 99 && $permintaan_count < 999){
    					$kode_permintaan_sample_detail = "PMSAMPLE-DET" . $tanggal . "-0" . ($permintaan_count + 1);
    				}else{
    					$kode_permintaan_sample_detail = "PMSAMPLE-DET-" . $tanggal . ($permintaan_count + 1);
    				}
    			}
    		}else{
    			$kode_permintaan_sample_detail = "PMSAMPLE-DET" . $tanggal . "-0001";
    		}
    	}else{
    		$kode_permintaan_sample_detail = "PMSAMPLE-DET" . $tanggal . "-0001";
    	}

    	date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Lab Input Data Lab Permintaan Sample Nomor ' . $this->nomor_permintaan_sample . ' Melalui Excel']);

        DB::table('permintaan_sample')->where('nomor_permintaan_sample', $this->nomor_permintaan_sample)->update(['status' => 2]);

    	return new ModelPermintaanSampleDetail([
    		'nomor_permintaan_sample_detail' => $kode_permintaan_sample_detail,
    		'nomor_permintaan_sample' => $this->nomor_permintaan_sample,
    		'mesh' => $row[0],
    		'ssa' => $row[1],
    		'd50' => str_replace(',', '.', $row[2]),
    		'd98' => $row[3],
    		'cie86' => $row[4],
    		'iso2470' => $row[5],
    		'moisture' => $row[6],
    		'residue' => $row[7],
    	]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
