<?php

namespace App\Http\Controllers;

use App\ModelCustomers;
use App\ModelKota;
use App\ModelProvinsi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Notifications\NotifNewResume;
use App\Notifications\NotifNewContactUs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Imports\CustomerVisitImport;
Use Exception;
use Response;
use Mail;
use File;
use Notification;
use PDF;
use Excel;

class CustomerVisitController extends Controller
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

	public function viewPageCustomerVisitSales(){
		if(!Session::get('login_admin')){
			return redirect('/')->with('alert','You Do Not Have an Authorization');
		}else{
			DB::table('temp_catatan_perusahaan')->where('id_user', Session::get('id_user_admin'))->delete();

			return view('input_customer_visit');
		}
	}

	public function viewJadwalTableCustomerVisitSales(Request $request){
		if(request()->ajax()){
			if(!empty($request->from_date)){
				// $schedule1 = DB::connection('mysql2')->table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status", DB::raw("IF(fol.tanggal_schedule <= NOW() - INTERVAL 1 DAY, 'YES', NULL) as tampil_proses"))->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', '<', 3)->whereBetween('fol.tanggal_schedule', array($request->from_date, $request->to_date))->get();

				$schedule = DB::table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status")->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', '<', 3)->whereBetween('fol.tanggal_schedule', array($request->from_date, $request->to_date))->get();

				// $schedule = $schedule1->merge($schedule2);
			}else{
				// $schedule1 = DB::connection('mysql2')->table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status", DB::raw("IF(fol.tanggal_schedule <= NOW() - INTERVAL 1 DAY, 'YES', NULL) as tampil_proses"))->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', '<', 3)->orderByRaw("DATE(fol.tanggal_schedule)=DATE(NOW()) DESC, IF(DATE(fol.tanggal_schedule)=DATE(NOW()),fol.tanggal_schedule,DATE(NULL)) DESC, fol.tanggal_schedule DESC")->get();

				$schedule = DB::table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status")->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', '<', 3)->orderByRaw("DATE(fol.tanggal_schedule)=DATE(NOW()) DESC, IF(DATE(fol.tanggal_schedule)=DATE(NOW()),fol.tanggal_schedule,DATE(NULL)) DESC, fol.tanggal_schedule DESC")->get();

				// $schedule = $schedule1->merge($schedule2);
			}

			return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_customers_visit')->rawColumns(['action'])->make(true);
		}
	}

	public function viewDoneTableCustomerVisitSales(Request $request){
		if(request()->ajax()){
			if(!empty($request->from_date)){
				// $schedule1 = DB::connection('mysql2')->table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status", DB::raw("IF(fol.tanggal_schedule <= NOW() - INTERVAL 1 DAY, 'YES', NULL) as tampil_proses"))->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', 3)->whereBetween('fol.tanggal_schedule', array($request->from_date, $request->to_date))->get();

				$schedule = DB::table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status")->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', 3)->whereBetween('fol.tanggal_schedule', array($request->from_date, $request->to_date))->get();

				// $schedule = $schedule1->merge($schedule2);
			}else{
				// $schedule1 = DB::connection('mysql2')->table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status", DB::raw("IF(fol.tanggal_schedule <= NOW() - INTERVAL 1 DAY, 'YES', NULL) as tampil_proses"))->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', 3)->orderByRaw("DATE(fol.tanggal_schedule)=DATE(NOW()) DESC, IF(DATE(fol.tanggal_schedule)=DATE(NOW()),fol.tanggal_schedule,DATE(NULL)) DESC, fol.tanggal_schedule DESC")->get();

				$schedule = DB::table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status")->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', 3)->orderByRaw("DATE(fol.tanggal_schedule)=DATE(NOW()) DESC, IF(DATE(fol.tanggal_schedule)=DATE(NOW()),fol.tanggal_schedule,DATE(NULL)) DESC, fol.tanggal_schedule DESC")->get();

				// $schedule = $schedule1->merge($schedule2);
			}

			return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_customers_visit')->rawColumns(['action'])->make(true);
		}
	}

	public function viewFailTableCustomerVisitSales(Request $request){
		if(request()->ajax()){
			if(!empty($request->from_date)){
				// $schedule1 = DB::connection('mysql2')->table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status", DB::raw("IF(fol.tanggal_schedule <= NOW() - INTERVAL 1 DAY, 'YES', NULL) as tampil_proses"))->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', 4)->whereBetween('fol.tanggal_schedule', array($request->from_date, $request->to_date))->get();

				$schedule = DB::table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status")->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', 4)->whereBetween('fol.tanggal_schedule', array($request->from_date, $request->to_date))->get();

				// $schedule = $schedule1->merge($schedule2);
			}else{
				// $schedule1 = DB::connection('mysql2')->table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status", DB::raw("IF(fol.tanggal_schedule <= NOW() - INTERVAL 1 DAY, 'YES', NULL) as tampil_proses"))->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', 4)->orderByRaw("DATE(fol.tanggal_schedule)=DATE(NOW()) DESC, IF(DATE(fol.tanggal_schedule)=DATE(NOW()),fol.tanggal_schedule,DATE(NULL)) DESC, fol.tanggal_schedule DESC")->get();

				$schedule = DB::table('customer_follow_up as fol')->select("fol.id_schedule", "fol.tanggal_schedule as jadwal", "fol.customers as custid", "cus.custname as customers", "fol.perihal", "stat.name as status", "fol.status as no_status")->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->leftJoin("customers as cus", "cus.custid", "=", "fol.customers")->where("fol.id_user", Session::get('id_user_admin'))->where('fol.status', 4)->orderByRaw("DATE(fol.tanggal_schedule)=DATE(NOW()) DESC, IF(DATE(fol.tanggal_schedule)=DATE(NOW()),fol.tanggal_schedule,DATE(NULL)) DESC, fol.tanggal_schedule DESC")->get();

				// $schedule = $schedule1->merge($schedule2);
			}

			return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_customers_visit')->rawColumns(['action'])->make(true);
		}
	}

	public function viewCatatanPerusahaanTableCustomerVisitSales(Request $request){
		if(request()->ajax()){
			$schedule = DB::table('temp_catatan_perusahaan')->select("company.nama_perusahaan as company", "temp_catatan_perusahaan.company as kode_perusahaan", "catatan_perusahaan")->join('company', 'company.kode_perusahaan', '=', 'temp_catatan_perusahaan.company')->where('id_user', Session::get('id_user_admin'))->get();

			return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_customer_visit_catatan_perusahaan')->rawColumns(['action'])->make(true);
		}
	}

	public function viewEditCatatanPerusahaanTableCustomerVisitSales(Request $request){
		if(request()->ajax()){
			$schedule = DB::table('customer_follow_up_detail')->select("id", "id_schedule", "company.nama_perusahaan as company", "customer_follow_up_detail.company as kode_perusahaan", "catatan_perusahaan")->join('company', 'company.kode_perusahaan', '=', 'customer_follow_up_detail.company')->where('id_schedule', $request->id_schedule)->get();

			return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_customer_visit_edit_catatan_perusahaan')->rawColumns(['action'])->make(true);
		}
	}

	public function saveCatatanPerusahaanCustomerVisitSales(Request $request){
		$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

		$data = DB::table('temp_catatan_perusahaan')->insert(['id_user' => Session::get('id_user_admin'), 'company' => $request->company, 'catatan_perusahaan' => $request->catatan_perusahaan]);

		if($data){
			$arr = array('msg' => 'Data Successfully Added', 'status' => true);
		}

		return Response()->json($arr);
	}

	public function saveCatatanPerusahaanOnlineCustomerVisitSales(Request $request){
		$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

		$data = DB::table('temp_catatan_perusahaan')->insert(['id_user' => Session::get('id_user_admin'), 'company' => $request->company_online, 'catatan_perusahaan' => $request->catatan_perusahaan_online]);

		if($data){
			$arr = array('msg' => 'Data Successfully Added', 'status' => true);
		}

		return Response()->json($arr);
	}

	public function saveEditCatatanPerusahaanCustomerVisitSales(Request $request){
		$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

		$data = DB::table('customer_follow_up_detail')->insert(['id_schedule' => $request->edit_id_schedule_det, 'company' => $request->edit_company, 'catatan_perusahaan' => $request->edit_catatan_perusahaan]);

		if($data){
			$arr = array('msg' => 'Data Successfully Added', 'status' => true);
		}

		return Response()->json($arr);
	}

	public function deleteCatatanPerusahaanCustomerVisitSales(Request $request){
		$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

		$data = DB::table('temp_catatan_perusahaan')->where('company', $request->get('company'))->where('id_user', Session::get('id_user_admin'))->delete();

		if($data){
			$arr = array('msg' => 'Data Successfully Deleted', 'status' => true);
		}

		return Response()->json($arr);
	}

	public function deleteEditCatatanPerusahaanCustomerVisitSales(Request $request){
		$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

		$data = DB::table('customer_follow_up_detail')->where('id', $request->get('id'))->delete();

		if($data){
			$arr = array('msg' => 'Data Successfully Deleted', 'status' => true);
		}

		return Response()->json($arr);
	}

	public function saveCustomerVisitSales(Request $request){
		$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

		$tanggal_schedule = date('ym');

		$cek_company_dsgm = DB::table('temp_catatan_perusahaan')->select('company')->where('company', 'DSGM')->first();

		if($cek_company_dsgm){

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

			if($request->get('new_nama_customers') != NULL || $request->get('new_nama_customers') != ''){
				$data_kota = ModelKota::where('id_kota', $request->get('new_city'))->first();
				$data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
				$nama_user = strtoupper(str_replace(' ', '', $request->get('new_nama_customers')));
				$kode_nama = substr($nama_user, 0, 5);
				$kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;

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

				$data_cust =  new ModelCustomers();
				$data_cust->custid = $kode_cust;
				$data_cust->custname = $request->get('new_nama_customers');
				$data_cust->company = $cek_company_dsgm->company;
				$data_cust->address = $request->get('new_alamat');
				$data_cust->phone = $request->get('new_telepon');
				$data_cust->city = $request->get('new_city');
				$data_cust->nama_cp = $request->get('new_pic');
				$data_cust->telepon_cp = $request->get('new_telepon_pic');
				$data_cust->bidang_usaha = $request->get('new_bidang_usaha');
				$data_cust->created_by = Session::get('id_user_admin');
				$data_cust->updated_by = Session::get('id_user_admin');
				$data_cust->save();

				date_default_timezone_set('Asia/Jakarta');
				DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Melalui Customers Follow Up']);

				$data = DB::table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $kode_cust, "perihal" => $request->get('perihal'), "offline" => 1, "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "tanggal_schedule" => $request->tanggal_jadwal, "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
			}else{
				$kode_cust = $request->get('customers');

				$cek_customer = DB::table('customers')->select('custid')->where('custid', $kode_cust)->first();

				if(!$cek_customer){
					$data_cust = DB::connection('mysql2')->table('customers')->select(array('custid', 'custname', 'company', 'address', 'city', 'phone', 'fax', 'npwp', 'image_npwp', 'nik', 'image_nik', 'nama_cp', 'jabatan_cp', 'bidang_usaha', 'telepon_cp'))->where('custid', $kode_cust)->first();

					DB::table("customers")->insert(get_object_vars($data_cust));
				}

				$data = DB::table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $kode_cust, "perihal" => $request->get('perihal'), "offline" => 1, "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "tanggal_schedule" => $request->tanggal_jadwal, "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
			}

			$data_detail = DB::table('temp_catatan_perusahaan')->select('company', 'catatan_perusahaan')->where('id_user', Session::get('id_user_admin'))->get();

			foreach($data_detail as $det){
				DB::table('customer_follow_up_detail')->insert(['id_schedule' => $kode_schedule, "company" => $det->company, "catatan_perusahaan" => $det->catatan_perusahaan]);
			}

			date_default_timezone_set('Asia/Jakarta');
			DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit No. ' . $kode_schedule]);
		}

		$cek_company_imj = DB::table('temp_catatan_perusahaan')->select('company')->where(function ($query) { $query->where('company', 'IMJ')->orWhere('company', 'BTC')->orWhere('company', 'RKM'); })->first();

		if($cek_company_imj){
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

			if($request->get('new_nama_customers') != NULL || $request->get('new_nama_customers') != ''){
				$data_kota = ModelKota::where('id_kota', $request->get('new_city'))->first();
				$data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
				$nama_user = strtoupper(str_replace(' ', '', $request->get('new_nama_customers')));
				$kode_nama = substr($nama_user, 0, 5);
				$kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;
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

				$data_cust =  new ModelCustomers();
				$data_cust->setConnection('mysql2');
				$data_cust->custid = $kode_cust;
				$data_cust->custname = $request->get('new_nama_customers');
				$data_cust->company = $cek_company_imj->company;
				$data_cust->address = $request->get('new_alamat');
				$data_cust->phone = $request->get('new_telepon');
				$data_cust->city = $request->get('new_city');
				$data_cust->nama_cp = $request->get('new_pic');
				$data_cust->telepon_cp = $request->get('new_telepon_pic');
				$data_cust->bidang_usaha = $request->get('new_bidang_usaha');
				$data_cust->created_by = Session::get('id_user_admin');
				$data_cust->updated_by = Session::get('id_user_admin');
				$data_cust->save();

				date_default_timezone_set('Asia/Jakarta');
				DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Melalui Customers Follow Up']);

				$data = DB::connection('mysql2')->table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $kode_cust, "perihal" => $request->get('perihal'), "offline" => 1, "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "tanggal_schedule" => $request->tanggal_jadwal, "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
			}else{
				$kode_cust = $request->get('customers');

				$cek_customer = DB::connection('mysql2')->table('customers')->select('custid')->where('custid', $kode_cust)->first();

				if(!$cek_customer){
					$data_cust = DB::table('customers')->select(array('custid', 'custname', 'company', 'address', 'city', 'phone', 'fax', 'npwp', 'image_npwp', 'nik', 'image_nik', 'nama_cp', 'jabatan_cp', 'bidang_usaha', 'telepon_cp'))->where('custid', $kode_cust)->first();

					DB::connection('mysql2')->table("customers")->insert(get_object_vars($data_cust));
				}

				$data = DB::connection('mysql2')->table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $kode_cust, "perihal" => $request->get('perihal'), "offline" => 1, "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "tanggal_schedule" => $request->tanggal_jadwal, "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
			}

			$data_detail = DB::table('temp_catatan_perusahaan')->select('company', 'catatan_perusahaan')->where('id_user', Session::get('id_user_admin'))->get();

			foreach($data_detail as $det){
				DB::connection('mysql2')->table('customer_follow_up_detail')->insert(['id_schedule' => $kode_schedule, "company" => $det->company, "catatan_perusahaan" => $det->catatan_perusahaan]);
			}

			date_default_timezone_set('Asia/Jakarta');
			DB::connection('mysql2')->table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit No. ' . $kode_schedule]);
		}

		$arr = array('msg' => 'Data Successfully Added', 'status' => true);

		DB::table('temp_catatan_perusahaan')->where('id_user', Session::get('id_user_admin'))->delete();

		return Response()->json($arr);
	}

	public function saveCustomerVisitOnlineSales(Request $request){
		$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

		$tanggal_schedule = date('ym');

		$cek_company_dsgm = DB::table('temp_catatan_perusahaan')->select('company')->where('company', 'DSGM')->first();

		if($cek_company_dsgm){

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

			if($request->get('new_nama_customers') != NULL || $request->get('new_nama_customers') != ''){
				$data_kota = ModelKota::where('id_kota', $request->get('new_city'))->first();
				$data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
				$nama_user = strtoupper(str_replace(' ', '', $request->get('new_nama_customers')));
				$kode_nama = substr($nama_user, 0, 5);
				$kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;

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

				$data_cust =  new ModelCustomers();
				$data_cust->custid = $kode_cust;
				$data_cust->custname = $request->get('new_nama_customers');
				$data_cust->company = $cek_company_dsgm->company;
				$data_cust->address = $request->get('new_alamat');
				$data_cust->phone = $request->get('new_telepon');
				$data_cust->city = $request->get('new_city');
				$data_cust->nama_cp = $request->get('new_pic');
				$data_cust->telepon_cp = $request->get('new_telepon_pic');
				$data_cust->bidang_usaha = $request->get('new_bidang_usaha');
				$data_cust->created_by = Session::get('id_user_admin');
				$data_cust->updated_by = Session::get('id_user_admin');
				$data_cust->save();

				date_default_timezone_set('Asia/Jakarta');
				DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Melalui Customers Follow Up']);

				$data = DB::table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $kode_cust, "perihal" => $request->get('perihal'), "offline" => 0, "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "tanggal_schedule" => $request->get('tanggal_jadwal'), "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
			}else{
				$kode_cust = $request->get('customers');

	            $cek_customer = DB::table('customers')->select('custid')->where('custid', $kode_cust)->first();

				if(!$cek_customer){
					$data_cust = DB::connection('mysql2')->table('customers')->select(array('custid', 'custname', 'company', 'address', 'city', 'phone', 'fax', 'npwp', 'image_npwp', 'nik', 'image_nik', 'nama_cp', 'jabatan_cp', 'bidang_usaha', 'telepon_cp'))->where('custid', $kode_cust)->first();

					DB::table("customers")->insert(get_object_vars($data_cust));
				}

				$data = DB::table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $kode_cust, "perihal" => $request->get('perihal'), "offline" => 0, "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "tanggal_schedule" => $request->get('tanggal_jadwal'), "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
			}

			$data_detail = DB::table('temp_catatan_perusahaan')->select('company', 'catatan_perusahaan')->where('id_user', Session::get('id_user_admin'))->get();

			foreach($data_detail as $det){
				DB::table('customer_follow_up_detail')->insert(['id_schedule' => $kode_schedule, "company" => $det->company, "catatan_perusahaan" => $det->catatan_perusahaan]);
			}

			date_default_timezone_set('Asia/Jakarta');
			DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit No. ' . $kode_schedule]);
		}

		$cek_company_imj = DB::table('temp_catatan_perusahaan')->select('company')->where(function ($query) { $query->where('company', 'IMJ')->orWhere('company', 'BTC')->orWhere('company', 'RKM'); })->first();

		if($cek_company_imj){
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

			if($request->get('new_nama_customers') != NULL || $request->get('new_nama_customers') != ''){
				$data_kota = ModelKota::where('id_kota', $request->get('new_city'))->first();
				$data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
				$nama_user = strtoupper(str_replace(' ', '', $request->get('new_nama_customers')));
				$kode_nama = substr($nama_user, 0, 5);
				$kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;
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

				$data_cust =  new ModelCustomers();
				$data_cust->setConnection('mysql2');
				$data_cust->custid = $kode_cust;
				$data_cust->custname = $request->get('new_nama_customers');
				$data_cust->company = $cek_company_imj->company;
				$data_cust->address = $request->get('new_alamat');
				$data_cust->phone = $request->get('new_telepon');
				$data_cust->city = $request->get('new_city');
				$data_cust->nama_cp = $request->get('new_pic');
				$data_cust->telepon_cp = $request->get('new_telepon_pic');
				$data_cust->bidang_usaha = $request->get('new_bidang_usaha');
				$data_cust->created_by = Session::get('id_user_admin');
				$data_cust->updated_by = Session::get('id_user_admin');
				$data_cust->save();

				date_default_timezone_set('Asia/Jakarta');
				DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Melalui Customers Follow Up']);

				$data = DB::connection('mysql2')->table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $kode_cust, "perihal" => $request->get('perihal'), "offline" => 0, "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "tanggal_schedule" => $request->get('tanggal_jadwal'), "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
			}else{
				$kode_cust = $request->get('customers');

				$cek_customer = DB::connection('mysql2')->table('customers')->select('custid')->where('custid', $kode_cust)->first();

				if(!$cek_customer){
					$data_cust = DB::table('customers')->select(array('custid', 'custname', 'company', 'address', 'city', 'phone', 'fax', 'npwp', 'image_npwp', 'nik', 'image_nik', 'nama_cp', 'jabatan_cp', 'bidang_usaha', 'telepon_cp'))->where('custid', $kode_cust)->first();

					DB::connection('mysql2')->table("customers")->insert(get_object_vars($data_cust));
				}

				$data = DB::connection('mysql2')->table('customer_follow_up')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "customers" => $kode_cust, "perihal" => $request->get('perihal'), "offline" => 0, "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "tanggal_schedule" => $request->get('tanggal_jadwal'), "status" => 1, "created_by" => Session::get('id_user_admin'), "created_at" => date("Y-m-d H:i:s"), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
			}

			$data_detail = DB::table('temp_catatan_perusahaan')->select('company', 'catatan_perusahaan')->where('id_user', Session::get('id_user_admin'))->get();

			foreach($data_detail as $det){
				DB::connection('mysql2')->table('customer_follow_up_detail')->insert(['id_schedule' => $kode_schedule, "company" => $det->company, "catatan_perusahaan" => $det->catatan_perusahaan]);
			}

			date_default_timezone_set('Asia/Jakarta');
			DB::connection('mysql2')->table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit No. ' . $kode_schedule]);
		}

		$arr = array('msg' => 'Data Successfully Added', 'status' => true);

		DB::table('temp_catatan_perusahaan')->where('id_user', Session::get('id_user_admin'))->delete();

		return Response()->json($arr);
	}

	public function loadDataCustomerCustomerVisitSales(Request $request){
		$data = [];

		if($request->has('q')){
			$search = $request->q;
			$customers_list1 = DB::connection('mysql2')->table('customers')->select("custid", "custname", "company")->where('custname','LIKE',"%$search%")->orderBy('custname', 'asc')->get();

			$customers_list2 = DB::connection('mysql')->table('customers')->select("custid", "custname", "company")->where('custname','LIKE',"%$search%")->orderBy('custname', 'asc')->get();

			$data = $customers_list1->merge($customers_list2);
		}else{
			$customers_list1 = DB::connection('mysql2')->table('customers')->select("custid", "custname", "company")->orderBy('custname', 'asc')->get();

			$customers_list2 = DB::connection('mysql')->table('customers')->select("custid", "custname", "company")->orderBy('custname', 'asc')->get();

			$data = $customers_list1->merge($customers_list2);
		}

		return response()->json($data);
	}

	public function viewDataCustomerVisitSales($id_schedule){
        $val_id_schedule = $this->decrypt($id_schedule);
        
        $data = DB::table('customer_follow_up as fol')->select('id_schedule', 'tanggal_schedule', 'fol.customers as custid', 'customers.custname as customers', 'perihal', 'offline', 'keterangan', 'penawaran_yes', 'sample_yes', 'order_yes', 'catatan_penawaran', 'catatan_sample', 'catatan_order', 'route_length', 'bbm', 'biaya_perjalanan', 'tanggal_done', 'alasan_suspend', 'alasan_done', 'alasan_fail', "stat.name as status", "fol.status as no_status")->join('customers', 'customers.custid', '=', 'fol.customers')->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->where('id_schedule', $val_id_schedule)->first();

        return Response()->json($data);
    }

    public function viewDataCatatanPerusahaanCustomerVisitSales($id_schedule){
        $val_id_schedule = $this->decrypt($id_schedule);
        
        $data = DB::table('customer_follow_up_detail')->select('company.nama_perusahaan as company', 'catatan_perusahaan')->join('company', 'company.kode_perusahaan', '=', 'customer_follow_up_detail.company')->where('id_schedule', $val_id_schedule)->get();

        return Response()->json($data);
    }

    public function editCustomerVisitSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('customer_follow_up')->where("id_schedule", $request->get('id_schedule'))->update(["customers" => $request->get('customers'), "perihal" => $request->get('perihal'), "offline" => $request->get('offline'), "keterangan" => $request->get('keterangan'), "penawaran_yes" => $request->get('input_penawaran'), "sample_yes" => $request->get('input_sample'), "order_yes" => $request->get('input_order'), "catatan_penawaran" => $request->get('catatan_penawaran'), "catatan_sample" => $request->get('catatan_sample'), "catatan_order" => $request->get('catatan_order'), "alasan_suspend" => $request->get('alasan_suspend'), "tanggal_schedule" => $request->get('tanggal_jadwal'), "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
		DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Customer Visit No. ' . $request->get('id_schedule')]);

        return Response()->json($arr);
    }

    public function uploadExcelCustomerVisitSales(Request $request){
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new CustomerVisitImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        return redirect('sales/customer_visit')->with('alert','Sukses Menambahkan Data');
    }

    public function prosesCustomerVisitSales(Request $request){
		$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

		if($request->proses_status == 2){
			date_default_timezone_set('Asia/Jakarta');
			$data = DB::table('customer_follow_up')->where('id_schedule', $request->proses_id_schedule)->update(['tanggal_schedule' => $request->proses_tanggal_jadwal, 'alasan_suspend' => $request->proses_alasan_suspend, 'status' => $request->proses_status, "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
		}else if($request->proses_status == 3){
			date_default_timezone_set('Asia/Jakarta');
			$data = DB::table('customer_follow_up')->where('id_schedule', $request->proses_id_schedule)->update(['tanggal_done' => $request->proses_tanggal_done, 'alasan_done' => $request->proses_alasan_success, "route_length" => $request->route_length, "bbm" => $request->bbm, "biaya_perjalanan" => $request->biaya_perjalanan, 'status' => $request->proses_status, "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
		}else if($request->proses_status == 4){
			date_default_timezone_set('Asia/Jakarta');
			$data = DB::table('customer_follow_up')->where('id_schedule', $request->proses_id_schedule)->update(['alasan_fail' => $request->proses_alasan_fail, "route_length" => $request->route_length, "bbm" => $request->bbm, "biaya_perjalanan" => $request->biaya_perjalanan, 'status' => $request->proses_status, "updated_by" => Session::get('id_user_admin'), "updated_at" => date("Y-m-d H:i:s")]);
		}

		date_default_timezone_set('Asia/Jakarta');
		DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => $request->proses_status, 'action' => 'User ' . Session::get('id_user_admin') . ' Proses Data Customer Visit No. ' . $request->proses_id_schedule]);

		if($data){
			$arr = array('msg' => 'Data Successfully Updated', 'status' => true);
		}

		return Response()->json($arr);
	}

	public function printCustomerVisitSales($id_schedule){
        $val_id_schedule = Crypt::decrypt($id_schedule);

        $data = DB::table('customer_follow_up as fol')->select('id_schedule', 'tanggal_schedule', 'fol.customers as custid', 'customers.custname as customers', 'perihal', 'offline', 'keterangan', 'penawaran_yes', 'sample_yes', 'order_yes', 'catatan_penawaran', 'catatan_sample', 'catatan_order', 'route_length', 'bbm', 'biaya_perjalanan', 'tanggal_done', 'alasan_suspend', 'alasan_done', 'alasan_fail', "stat.name as status", "fol.status as no_status", 'users.nama_admin as created_by', 'fol.created_at')->join('customers', 'customers.custid', '=', 'fol.customers')->join("status_cust_visit as stat", "stat.id", "=", "fol.status")->join('users', 'users.id_user', '=', 'fol.created_by')->where('id_schedule', $val_id_schedule)->first();

        $detail = DB::table('customer_follow_up_detail')->select('company.nama_perusahaan as company', 'catatan_perusahaan')->join('company', 'company.kode_perusahaan', '=', 'customer_follow_up_detail.company')->where('id_schedule', $val_id_schedule)->get();

        $pdf = PDF::loadView('print_customer_visit', ['data' => $data, 'detail' => $detail])->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
}
