<?php

namespace App\Imports;

use App\ModelCustomers;
use App\ModelKota;
use App\ModelProvinsi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CustomersImport implements ToModel, WithStartRow
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
        $kode_nama = strtoupper(substr($nama_user, 0, 5));

        $tanggal_history = date('Y');

        $kode_cust = $data_provinsi->kode . $data_city->kode . $kode_nama;
        $data_custid = ModelCustomers::where('custid', 'like', '%' . $kode_cust . '%')->orderBy('custid', 'asc')->get();

        if($data_custid){
        	$data_count = $data_custid->count();
        	if($data_count > 0){
        		$num = (int) substr($data_custid[$data_custid->count() - 1]->custid, 11);
        		if($data_count != $num){
        			$kode_cust = ++$data_custid[$data_custid->count() - 1]->custid;
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

        if(isset($row[12])){
            $data_npwp = $row[12];
        }else{
            $data_npwp = "00.000.000.0-000.000";
        }

        if(!ModelCustomers::where('custid', '=', $kode_cust)->exists()) {
            $this->duplikat = 1;

            date_default_timezone_set('Asia/Jakarta');
        	DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'Data Customers ' . $kode_cust . ' Dari Upload Excel']);

            if($row[2] == 'DSGM'){
                $data = new ModelCustomers();
                $data->custid = $kode_cust;
                $data->id_sales = $sales_id;
                $data->custname = preg_replace('/_x([0-9a-fA-F]{4})_/', '', $row[1]);
                $data->company = $row[2];
                $data->crd = $row[3];
                $data->custlimit = $row[4];
                $data->address = $row[5];
                $data->city = $data_kota;
                $data->phone = $row[7];
                $data->fax = $row[8];
                $data->spv = $row[9];
                $data->sls = $row[10];
                $data->telesls = $row[11];
                $data->npwp = $data_npwp;
                $data->nik = $row[13];
                $data->wraddress = $row[14];
                $data->nama_cp = $row[15];
                $data->jabatan_cp = $row[16];
                $data->bidang_usaha = $row[17];
                $data->created_at = date("Y-m-d H:i:s");
                $data->updated_at = date("Y-m-d H:i:s");
                $data->created_by = Session::get('id_user_admin');
                $data->updated_by = Session::get('id_user_admin');

                return $data;
            }else if($row[2] == 'IMJ'){
                $data = new ModelCustomers();
                $data->setConnection('mysql2');
                $data->custid = $kode_cust;
                $data->id_sales = $sales_id;
                $data->custname = preg_replace('/_x([0-9a-fA-F]{4})_/', '', $row[1]);
                $data->company = $row[2];
                $data->address = $row[5];
                $data->city = $data_kota;
                $data->phone = $row[7];
                $data->fax = $row[8];
                $data->npwp = $data_npwp;
                $data->nik = $row[13];
                $data->nama_cp = $row[15];
                $data->jabatan_cp = $row[16];
                $data->bidang_usaha = $row[17];
                $data->created_at = date("Y-m-d H:i:s");
                $data->updated_at = date("Y-m-d H:i:s");
                $data->created_by = Session::get('id_user_admin');
                $data->updated_by = Session::get('id_user_admin');
                
                return $data;
            }

            // return new ModelCustomers([
            //     'custid' => $kode_cust,
            //     'id_sales' => $sales_id,
            //     'custname' => preg_replace('/_x([0-9a-fA-F]{4})_/', '', $row[1]),
            //     'company' => $row[2],
            //     'crd' => $row[3],
            //     'custlimit' => $row[4],
            //     'address' => $row[5],
            //     'city' => $data_kota,
            //     'phone' => $row[7],
            //     'fax' => $row[8],
            //     'spv' => $row[9],
            //     'sls' => $row[10],
            //     'telesls' => $row[11],
            //     'npwp' => $row[12],
            //     'nik' => $row[13],
            //     'wraddress' => $row[14],
            //     'nama_cp' => $row[15],
            //     'jabatan_cp' => $row[16],
            //     'bidang_usaha' => $row[17],
            //     'created_at' => date("Y-m-d H:i:s"),
            //     'updated_at' => date("Y-m-d H:i:s"),
            //     'created_by' => Session::get('id_user_admin'),
            //     'updated_by' => Session::get('id_user_admin'),
            // ]);
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
