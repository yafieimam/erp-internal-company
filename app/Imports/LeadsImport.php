<?php

namespace App\Imports;

use App\ModelLeads;
use App\ModelKota;
use App\ModelProvinsi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LeadsImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $duplikat = 0;

    public function model(array $row)
    {
    	$search = $row[6];
        $kota = ModelKota::select("id_kota", "name")->where('name','LIKE',"$search")->first();
        $data_kota = null;

        if(empty($kota)){
        	$kota2 = ModelKota::select("id_kota", "name")->where('name','LIKE',"%$search%")->first();
        	$data_kota = $kota2->id_kota;
        }else{
        	$data_kota = $kota->id_kota;
        }

        if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
            $sales_id = NULL;
        }else{
            $sales_id = Session::get('id_user_admin');
        }

        $data_city = ModelKota::where('id_kota', $data_kota)->first();
        $data_provinsi = ModelProvinsi::where('id_provinsi', $data_city->id_provinsi)->first();
        $nama_user = explode(" ", $row[1]);
        $omit_words = array('PT.','PT','.','CV.','CV','FA.','FA','C.V.','P.D','P.T.');  
		$nama_user = array_diff($nama_user,$omit_words);
		$nama_user = implode("", $nama_user);
        $kode_nama = substr($nama_user, 0, 5);

        $tanggal_history = date('Y');

        $kode_cust = 'LEAD' . $data_provinsi->kode . $data_city->kode . $kode_nama;
        $data_leadid = ModelLeads::where('leadid', 'like', '%' . $kode_cust . '%')->orderBy('leadid', 'asc')->get();

        if($data_leadid){
        	$data_count = $data_leadid->count();
        	if($data_count > 0){
        		$num = (int) substr($data_leadid[$data_leadid->count() - 1]->leadid, 15);
        		if($data_count != $num){
        			$kode_cust = ++$data_leadid[$data_leadid->count() - 1]->leadid;
        		}else{
        			if($data_count < 9){
        				$kode_cust = $kode_cust . "0" . ($data_count + 1);
        			}else{
        				$kode_cust = $kode_cust . ($data_count + 1);
        			}
        		}
        	}else{
        		$kode_cust = $kode_cust . "01";
        	}
        }else{
        	$kode_cust = $kode_cust . "01";
        }

        if(!ModelLeads::where('leadid', '=', $kode_cust)->exists()) {
            $this->duplikat = 1;

            date_default_timezone_set('Asia/Jakarta');
        	DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_cust, 'status' => 2, 'action' => 'Data Leads ' . $kode_cust . ' Dari Upload Excel']);

            return new ModelLeads([
            	'leadid' => $kode_cust,
                'id_sales' => $sales_id,
                'nama' => preg_replace('/_x([0-9a-fA-F]{4})_/', '', $row[1]),
                'company' => $row[2],
                'crd' => $row[3],
                'custlimit' => $row[4],
                'address' => $row[5],
                'city' => $data_kota,
                'phone' => $row[7],
                'fax' => $row[8],
                'spv' => $row[9],
                'sls' => $row[10],
                'telesls' => $row[11],
                'npwp' => $row[12],
                'nik' => $row[13],
                'wraddress' => $row[14],
                'nama_cp' => $row[15],
                'jabatan_cp' => $row[16],
                'bidang_usaha' => $row[17],
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'created_by' => Session::get('id_user_admin'),
                'updated_by' => Session::get('id_user_admin'),
            ]);
        }else{
            $this->duplikat = 0;
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
}
