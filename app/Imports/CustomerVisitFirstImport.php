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

class CustomerVisitFirstImport implements ToModel, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return Carbon::instance(Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat($format, date('Y-m-d', strtotime($value)));
        }
    }

    public function model(array $row)
    {
    	$penawaran_yes = 'no';
        $sample_yes = 'no';
        $order_yes = 'no';
        $catatan_penawaran = null;
        $catatan_sample = null;
        $catatan_order = null;

        if($row[9] != null || $row[9] != ''){
        	if(strtolower($row[9]) != 'x'){
        		$penawaran_yes = 'yes';
        		$catatan_penawaran = $row[9];
        	}
        }

        if($row[10] != null || $row[10] != ''){
        	if(strtolower($row[10]) != "x"){
        		$sample_yes = 'yes';
        		$catatan_sample = $row[10];
        	}
        }

        if($row[15] != null || $row[15] != ''){
        	if(strtolower($row[15]) != 'x'){
        		$order_yes = 'yes';
        		$catatan_order = $row[15];
        	}
        }

        $dsgm_yes = 0;
        $imj_yes = 0;
        $data_catatan_perusahaan = [];

       	if($row[12] != null || $row[12] != ''){
       		if(strtolower($row[12]) != 'x'){
       			$dsgm_yes = 1;
       		}
       	}

       	if($row[13] != null || $row[13] != ''){
       		if(strtolower($row[13]) != 'x'){
       			$imj_yes = 1;
       		}
       	}

       	if($row[14] != null || $row[14] != ''){
       		if(strtolower($row[14]) != 'x'){
       			$imj_yes = 1;
       		}
       	}

       	if($dsgm_yes == 0 && $imj_yes == 0){
       		$dsgm_yes = 1;
       	}

        $data_cust_dsgm = null;
        $data_cust_imj = null;

        if($dsgm_yes){
        	$search_cust = $row[3];
        	$cust = ModelCustomers::select("custid")->where('custname','LIKE',"$search_cust")->first();

        	if(empty($cust)){
        		$cust2 = ModelCustomers::select("custid")->where('custname','LIKE',"%$search_cust%")->first();

        		if(empty($cust2)){
        			$search = $row[5];
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
        			$nama_user = explode(" ", $row[3]);
        			$omit_words = array('PT.','PT','.','CV.','CV','FA.','FA','C.V.','P.D','P.T.');  
        			$nama_user = array_diff($nama_user,$omit_words);
        			$nama_user = implode("", $nama_user);
        			$kode_nama = substr($nama_user, 0, 5);

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

        			$data_cust_dsgm = $kode_cust;

        			date_default_timezone_set('Asia/Jakarta');
        			DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'Data Customers ' . $kode_cust . ' Dari Upload Excel Customers Visit']);

        			$data = new ModelCustomers();
        			$data->custid = $kode_cust;
        			$data->id_sales = $sales_id;
        			$data->custname = preg_replace('/_x([0-9a-fA-F]{4})_/', '', $row[3]);
        			$data->company = 'DSGM';
        			$data->address = $row[4];
        			$data->city = $data_kota;
        			$data->npwp = "00.000.000.0-000.000";
        			$data->nama_cp = $row[6];
        			$data->telepon_cp = $row[7];
        			$data->bidang_usaha = $row[8];
        			$data->created_at = date("Y-m-d H:i:s");
        			$data->updated_at = date("Y-m-d H:i:s");
        			$data->created_by = Session::get('id_user_admin');
        			$data->updated_by = Session::get('id_user_admin');
        			$data->save();
        		}else{
        			$data_cust_dsgm = $cust2->custid;
        		}
        	}else{
        		$data_cust_dsgm = $cust->custid;
        	}

        	$tanggal_schedule = date('ym');

        	$data_schedule = DB::table('customer_follow_up')->select('id_schedule')->where('id_schedule', 'like', 'FLWUP' . $tanggal_schedule . '%')->orderBy('id_schedule', 'asc')->distinct()->get();

        	if($data_schedule){
        		$schedule_count = $data_schedule->count();
        		if($schedule_count > 0){
        			$num = (int) substr($data_schedule[$data_schedule->count() - 1]->id_schedule, 10);
        			if($schedule_count != $num){
        				$kode_schedule = ++$data_schedule[$data_schedule->count() - 1]->id_schedule;
        			}else{
        				if($schedule_count < 9){
        					$kode_schedule = "FLWUP" . $tanggal_schedule . "-000" . ($schedule_count + 1);
        				}else if($schedule_count >= 9 && $schedule_count < 99){
        					$kode_schedule = "FLWUP" . $tanggal_schedule . "-00" . ($schedule_count + 1);
        				}else if($schedule_count >= 99 && $schedule_count < 999){
        					$kode_schedule = "FLWUP" . $tanggal_schedule . "-0" . ($schedule_count + 1);
        				}else{
        					$kode_schedule = "FLWUP" . $tanggal_schedule . "-" . ($schedule_count + 1);
        				}
        			}
        		}else{
        			$kode_schedule = "FLWUP" . $tanggal_schedule . "-0001";
        		}
        	}else{
        		$kode_schedule = "FLWUP" . $tanggal_schedule . "-0001";
        	}

        	if($row[12] != null || $row[12] != ''){
        		if(strtolower($row[12]) != 'x'){
        			$data_catatan_perusahaan[] = [
        				'id_schedule' => $kode_schedule,
        				'company' => 'DSGM',
        				'catatan_perusahaan' => $row[12],
        			];
        		}
        	}

        	if($row[13] != null || $row[13] != ''){
        		if(strtolower($row[13]) != 'x'){
        			$data_catatan_perusahaan[] = [
        				'id_schedule' => $kode_schedule,
        				'company' => 'BTC',
        				'catatan_perusahaan' => $row[13],
        			];
        		}
        	}

        	if($row[14] != null || $row[14] != ''){
        		if(strtolower($row[14]) != 'x'){
        			$data_catatan_perusahaan[] = [
        				'id_schedule' => $kode_schedule,
        				'company' => 'RKM',
        				'catatan_perusahaan' => $row[14],
        			];
        		}
        	}

        	date_default_timezone_set('Asia/Jakarta');
       		$data = DB::table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $data_cust_dsgm, "perihal" => "Customer Visit", "offline" => 1, "keterangan" => $row[11], "penawaran_yes" => $penawaran_yes, "sample_yes" => $sample_yes, "order_yes" => $order_yes, "catatan_penawaran" => $catatan_penawaran, "catatan_sample" => $catatan_sample, "catatan_order" => $catatan_order, "tanggal_schedule" => $this->transformDate($row[1]), "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);

       		DB::table('customer_follow_up_detail')->insert($data_catatan_perusahaan);

       		date_default_timezone_set('Asia/Jakarta');
			DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit No. ' . $kode_schedule . ' Melalui Upload Excel']);
        }

        $data_catatan_perusahaan = [];

        if($imj_yes){
        	$search_cust = $row[3];
        	$cust = DB::connection('mysql2')->table('customers')->select("custid")->where('custname','LIKE',"$search_cust")->first();

        	$search_cust = $row[3];
        	$cust = DB::connection('mysql2')->table('customers')->select("custid")->where('custname','LIKE',"$search_cust")->first();

        	if(empty($cust)){
        		$cust2 = DB::connection('mysql2')->table('customers')->select("custid")->where('custname','LIKE',"%$search_cust%")->first();

        		if(empty($cust2)){
        			$search = $row[5];
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
        			$nama_user = explode(" ", $row[3]);
        			$omit_words = array('PT.','PT','.','CV.','CV','FA.','FA','C.V.','P.D','P.T.');  
        			$nama_user = array_diff($nama_user,$omit_words);
        			$nama_user = implode("", $nama_user);
        			$kode_nama = substr($nama_user, 0, 5);

        			$tanggal_history = date('Y');

        			$kode_cust = $data_provinsi->kode . $data_city->kode . $kode_nama;
        			$data_custid = DB::connection('mysql2')->table('customers')->where('custid', 'like', '%' . $kode_cust . '%')->orderBy('custid', 'asc')->get();

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

        			$data_cust_imj = $kode_cust;

        			date_default_timezone_set('Asia/Jakarta');
        			DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'Data Customers ' . $kode_cust . ' Dari Upload Excel Customers Visit']);

        			$data = new ModelCustomers();
        			$data->setConnection('mysql2');
        			$data->custid = $kode_cust;
        			$data->id_sales = $sales_id;
        			$data->custname = preg_replace('/_x([0-9a-fA-F]{4})_/', '', $row[3]);
        			$data->company = 'DSGM';
        			$data->address = $row[4];
        			$data->city = $data_kota;
        			$data->npwp = "00.000.000.0-000.000";
        			$data->nama_cp = $row[6];
        			$data->telepon_cp = $row[7];
        			$data->bidang_usaha = $row[8];
        			$data->created_at = date("Y-m-d H:i:s");
        			$data->updated_at = date("Y-m-d H:i:s");
        			$data->created_by = Session::get('id_user_admin');
        			$data->updated_by = Session::get('id_user_admin');
        			$data->save();
        		}else{
        			$data_cust_imj = $cust2->custid;
        		}
        	}else{
        		$data_cust_imj = $cust->custid;
        	}

        	$tanggal_schedule = date('ym');

        	$data_schedule = DB::connection('mysql2')->table('customer_follow_up')->select('id_schedule')->where('id_schedule', 'like', 'FLWUP' . $tanggal_schedule . '%')->orderBy('id_schedule', 'asc')->distinct()->get();

        	if($data_schedule){
        		$schedule_count = $data_schedule->count();
        		if($schedule_count > 0){
        			$num = (int) substr($data_schedule[$data_schedule->count() - 1]->id_schedule, 10);
        			if($schedule_count != $num){
        				$kode_schedule = ++$data_schedule[$data_schedule->count() - 1]->id_schedule;
        			}else{
        				if($schedule_count < 9){
        					$kode_schedule = "FLWUP" . $tanggal_schedule . "-000" . ($schedule_count + 1);
        				}else if($schedule_count >= 9 && $schedule_count < 99){
        					$kode_schedule = "FLWUP" . $tanggal_schedule . "-00" . ($schedule_count + 1);
        				}else if($schedule_count >= 99 && $schedule_count < 999){
        					$kode_schedule = "FLWUP" . $tanggal_schedule . "-0" . ($schedule_count + 1);
        				}else{
        					$kode_schedule = "FLWUP" . $tanggal_schedule . "-" . ($schedule_count + 1);
        				}
        			}
        		}else{
        			$kode_schedule = "FLWUP" . $tanggal_schedule . "-0001";
        		}
        	}else{
        		$kode_schedule = "FLWUP" . $tanggal_schedule . "-0001";
        	}

        	if($row[12] != null || $row[12] != ''){
        		if(strtolower($row[12]) != 'x'){
        			$data_catatan_perusahaan[] = [
        				'id_schedule' => $kode_schedule,
        				'company' => 'DSGM',
        				'catatan_perusahaan' => $row[12],
        			];
        		}
        	}

        	if($row[13] != null || $row[13] != ''){
        		if(strtolower($row[13]) != 'x'){
        			$data_catatan_perusahaan[] = [
        				'id_schedule' => $kode_schedule,
        				'company' => 'BTC',
        				'catatan_perusahaan' => $row[13],
        			];
        		}
        	}

        	if($row[14] != null || $row[14] != ''){
        		if(strtolower($row[14]) != 'x'){
        			$data_catatan_perusahaan[] = [
        				'id_schedule' => $kode_schedule,
        				'company' => 'RKM',
        				'catatan_perusahaan' => $row[14],
        			];
        		}
        	}

        	date_default_timezone_set('Asia/Jakarta');
       		$data = DB::connection('mysql2')->table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $data_cust_imj, "perihal" => "Customer Visit", "offline" => 1, "keterangan" => $row[11], "penawaran_yes" => $penawaran_yes, "sample_yes" => $sample_yes, "order_yes" => $order_yes, "catatan_penawaran" => $catatan_penawaran, "catatan_sample" => $catatan_sample, "catatan_order" => $catatan_order, "tanggal_schedule" => $this->transformDate($row[1]), "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);

       		DB::connection('mysql2')->table('customer_follow_up_detail')->insert($data_catatan_perusahaan);

       		date_default_timezone_set('Asia/Jakarta');
			DB::connection('mysql2')->table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit No. ' . $kode_schedule . ' Melalui Upload Excel']);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
