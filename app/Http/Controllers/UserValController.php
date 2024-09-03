<?php

namespace App\Http\Controllers;
use App\ModelCustomers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;

class UserValController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';
    
    public function index(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $customers_val = DB::table('users as us')->select("cus.custid as custid", "cus.custname as name", "us.email as email", "cus.address as address", "kota.name as city", "us.status as status", "us.created_at as created_at", "cus.npwp as npwp", "cus.nik as nik", "cus.image_npwp as image_npwp", "cus.image_nik as image_nik")->join("customers as cus", "cus.custid", "=", "us.custid")->join("kota", "kota.id_kota", "=", "cus.city")->where('us.status', '!=', 2)->whereBetween('us.created_at', array($request->from_date, $request->to_date))->get();
            }else{
                $customers_val = DB::table('users as us')->select("cus.custid as custid", "cus.custname as name", "us.email as email", "cus.address as address", "kota.name as city", "us.status as status", "us.created_at as created_at", "cus.npwp as npwp", "cus.nik as nik", "cus.image_npwp as image_npwp", "cus.image_nik as image_nik")->join("customers as cus", "cus.custid", "=", "us.custid")->join("kota", "kota.id_kota", "=", "cus.city")->where('us.status', '!=', 2)->get();
            }

            return datatables()->of($customers_val)->addColumn('action', 'button/action_button_customersval')->rawColumns(['action'])->make(true);
        }
        return view('input_customers');
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

    public function create(Request $request){
        if(request()->ajax()){
            if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
                if(!empty($request->from_date)){
                    $customers_list1 = DB::connection('mysql2')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->whereBetween('cust.created_at', array($request->from_date, $request->to_date))->orderBy('name', 'asc')->get();

                    $customers_list2 = DB::connection('mysql')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->whereBetween('cust.created_at', array($request->from_date, $request->to_date))->orderBy('name', 'asc')->get();

                    $customers_list = $customers_list1->merge($customers_list2);
                }else{
                    $customers_list1 = DB::connection('mysql2')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->orderBy('name', 'asc')->get();

                    $customers_list2 = DB::connection('mysql')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->orderBy('name', 'asc')->get();

                    $customers_list = $customers_list1->merge($customers_list2);
                }
            }else{
                if(!empty($request->from_date)){
                    $customers_list1 = DB::connection('mysql2')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('id_sales', Session::get('id_user_admin'))->whereBetween('cust.created_at', array($request->from_date, $request->to_date))->orderBy('name', 'asc')->get();

                    $customers_list2 = DB::connection('mysql')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('id_sales', Session::get('id_user_admin'))->whereBetween('cust.created_at', array($request->from_date, $request->to_date))->orderBy('name', 'asc')->get();

                    $customers_list = $customers_list1->merge($customers_list2);
                }else{
                    $customers_list1 = DB::connection('mysql2')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('id_sales', Session::get('id_user_admin'))->orderBy('name', 'asc')->get();

                    $customers_list2 = DB::connection('mysql')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('id_sales', Session::get('id_user_admin'))->orderBy('name', 'asc')->get();

                    $customers_list = $customers_list1->merge($customers_list2);
                }
            }

            return datatables()->of($customers_list)->addIndexColumn()->make(true);
        }
        return view('input_customers');
    }

    public function viewDataDetailSemuaCustomers($custid, $company){   

        $val_custid = $this->decrypt($custid);
        $val_company = $this->decrypt($company);

        if($val_company == 'DSGM'){
            $data = DB::table('customers as cust')->select('cust.custid as custid', 'cust.custname as custname', 'cust.company', 'cust.groupid', 'cg.nama_group', 'us.email as email', 'tipe.type as tipe_customer', 'cust.crd as crd', 'cust.custlimit as custlimit', 'cust.address as address', 'cust.wraddress as wraddress', 'kota.name as city', 'cust.city as city_name', 'com.nama_perusahaan', 'cust.phone as phone', 'cust.fax as fax', 'cust.spv as spv', 'cust.sls as sls', 'cust.telesls as telesls', 'cust.npwp as npwp', 'cust.image_npwp as image_npwp', 'cust.nik as nik', 'cust.image_nik as image_nik', 'cust.created_at as created_at', 'emp.name as created_by', 'cust.nama_cp', 'cust.jabatan_cp', 'cust.bidang_usaha', 'cust.telepon_cp', 'akt.aktif as aktif', 'cust.updated_at as updated_at', DB::raw('(select prod.nama_produk from products prod, history_orders ho where prod.kode_produk = ho.kode_produk and ho.custid = cust.custid group by ho.kode_produk order by sum(ho.quantity) desc limit 0,1) as produk_pesanan'))->leftJoin("users as us", "cust.custid", "=", "us.custid")->leftJoin("user_type as tipe", "tipe.id_customer_type", "=", "us.id_customer_type")->leftJoin("company as com", "com.kode_perusahaan", "=", "cust.company")->leftJoin("kota", "kota.id_kota", "=", "cust.city")->leftJoin("employee as emp", "emp.employeeid", "=", "cust.created_by")->leftJoin("cust_aktif as akt", "akt.custid", "=", "cust.custid")->leftJoin('customer_group as cg', 'cg.groupid', 'cust.groupid')->where('cust.custid', $val_custid)->first();
        }else if($val_company == 'IMJ'){
            $data = DB::connection('mysql2')->table('customers as cust')->select('cust.custid as custid', 'cust.custname as custname', 'cust.company', 'cust.groupid', 'cg.nama_group', 'us.email as email', 'tipe.type as tipe_customer', 'cust.address as address', 'kota.name as city', 'cust.city as city_name', 'com.nama_perusahaan', 'cust.phone as phone', 'cust.fax as fax', 'cust.npwp as npwp', 'cust.image_npwp as image_npwp', 'cust.nik as nik', 'cust.image_nik as image_nik', 'cust.created_at as created_at', 'emp.name as created_by', 'cust.nama_cp', 'cust.jabatan_cp', 'cust.bidang_usaha', 'cust.telepon_cp', 'akt.aktif as aktif', 'cust.updated_at as updated_at', DB::raw('(select prod.nama_produk from products prod, history_orders ho where prod.kode_produk = ho.kode_produk and ho.custid = cust.custid group by ho.kode_produk order by sum(ho.quantity) desc limit 0,1) as produk_pesanan'))->leftJoin("users as us", "cust.custid", "=", "us.custid")->leftJoin("user_type as tipe", "tipe.id_customer_type", "=", "us.id_customer_type")->leftJoin("company as com", "com.kode_perusahaan", "=", "cust.company")->leftJoin("kota", "kota.id_kota", "=", "cust.city")->leftJoin("employee as emp", "emp.employeeid", "=", "cust.created_by")->leftJoin("cust_aktif as akt", "akt.custid", "=", "cust.custid")->leftJoin('customer_group as cg', 'cg.groupid', 'cust.groupid')->where('cust.custid', $val_custid)->first();
        }

        return Response()->json($data);
    }

    public function viewDataDetailDSGMCustomers($custid){   

        $val_custid = $this->decrypt($custid);

        
        $data = DB::table('customers as cust')->select('cust.custid as custid', 'cust.custname as custname', 'cust.company', 'cust.groupid', 'cg.nama_group', 'us.email as email', 'tipe.type as tipe_customer', 'cust.crd as crd', 'cust.custlimit as custlimit', 'cust.address as address', 'cust.wraddress as wraddress', 'kota.name as city', 'cust.city as city_name', 'com.nama_perusahaan', 'cust.phone as phone', 'cust.fax as fax', 'cust.spv as spv', 'cust.sls as sls', 'cust.telesls as telesls', 'cust.npwp as npwp', 'cust.image_npwp as image_npwp', 'cust.nik as nik', 'cust.image_nik as image_nik', 'cust.created_at as created_at', 'emp.name as created_by', 'cust.nama_cp', 'cust.jabatan_cp', 'cust.bidang_usaha', 'cust.telepon_cp', 'akt.aktif as aktif', 'cust.updated_at as updated_at', DB::raw('(select prod.nama_produk from products prod, history_orders ho where prod.kode_produk = ho.kode_produk and ho.custid = cust.custid group by ho.kode_produk order by sum(ho.quantity) desc limit 0,1) as produk_pesanan'))->leftJoin("users as us", "cust.custid", "=", "us.custid")->leftJoin("user_type as tipe", "tipe.id_customer_type", "=", "us.id_customer_type")->leftJoin("company as com", "com.kode_perusahaan", "=", "cust.company")->leftJoin("kota", "kota.id_kota", "=", "cust.city")->leftJoin("employee as emp", "emp.employeeid", "=", "cust.created_by")->leftJoin("cust_aktif as akt", "akt.custid", "=", "cust.custid")->leftJoin('customer_group as cg', 'cg.groupid', 'cust.groupid')->where('cust.custid', $val_custid)->first();

        return Response()->json($data);
    }

    public function viewDataDetailIMJCustomers($custid){   

        $val_custid = $this->decrypt($custid);

        $data = DB::connection('mysql2')->table('customers as cust')->select('cust.custid as custid', 'cust.custname as custname', 'cust.company', 'cust.groupid', 'cg.nama_group', 'us.email as email', 'tipe.type as tipe_customer', 'cust.address as address', 'kota.name as city', 'cust.city as city_name', 'com.nama_perusahaan', 'cust.phone as phone', 'cust.fax as fax', 'cust.npwp as npwp', 'cust.image_npwp as image_npwp', 'cust.nik as nik', 'cust.image_nik as image_nik', 'cust.created_at as created_at', 'emp.name as created_by', 'cust.nama_cp', 'cust.jabatan_cp', 'cust.bidang_usaha', 'cust.telepon_cp', 'akt.aktif as aktif', 'cust.updated_at as updated_at', DB::raw('(select prod.nama_produk from products prod, history_orders ho where prod.kode_produk = ho.kode_produk and ho.custid = cust.custid group by ho.kode_produk order by sum(ho.quantity) desc limit 0,1) as produk_pesanan'))->leftJoin("users as us", "cust.custid", "=", "us.custid")->leftJoin("user_type as tipe", "tipe.id_customer_type", "=", "us.id_customer_type")->leftJoin("company as com", "com.kode_perusahaan", "=", "cust.company")->leftJoin("kota", "kota.id_kota", "=", "cust.city")->leftJoin("employee as emp", "emp.employeeid", "=", "cust.created_by")->leftJoin("cust_aktif as akt", "akt.custid", "=", "cust.custid")->leftJoin('customer_group as cg', 'cg.groupid', 'cust.groupid')->where('cust.custid', $val_custid)->first();

        return Response()->json($data);
    }

    public function addCustAktif(Request $request){   
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $aktif = $request->get('aktif');

        if($request->get('company') == 'DSGM'){
            $cek_aktif = DB::table('cust_aktif')->select('custid')->where('custid', $request->get('custid'))->first();
            if($cek_aktif){
                $add_aktif = DB::table('cust_aktif')->where('custid', $request->get('custid'))->update(['aktif' => $aktif]);
            }else{
                $add_aktif = DB::table('cust_aktif')->insert(['custid' => $request->get('custid'), 'aktif' => $aktif]);
            }

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->get('custid'), 'status_user' => 5, 'action' => 'Admin Melakukan Penambahan Status Cust Aktif pada User ' . $request->get('custid')]);
        }else if($request->get('company') == 'IMJ'){
            $cek_aktif = DB::connection('mysql2')->table('cust_aktif')->select('custid')->where('custid', $request->get('custid'))->first();
            if($cek_aktif){
                $add_aktif = DB::connection('mysql2')->table('cust_aktif')->where('custid', $request->get('custid'))->update(['aktif' => $aktif]);
            }else{
                $add_aktif = DB::connection('mysql2')->table('cust_aktif')->insert(['custid' => $request->get('custid'), 'aktif' => $aktif]);
            }
            
            date_default_timezone_set('Asia/Jakarta');
            DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->get('custid'), 'status_user' => 5, 'action' => 'Admin Melakukan Penambahan Status Cust Aktif pada User ' . $request->get('custid')]);
        }

        

        if($add_aktif){
            $arr = array('msg' => 'Successfully Updated Aktif', 'status' => true);
        }

        return Response()->json($add_aktif);
    }

    public function viewDataDSGMTable(Request $request){
        if(request()->ajax()){
            if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
                if(!empty($request->from_date)){
                    $customers_list = DB::connection('mysql')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->whereBetween('cust.created_at', array($request->from_date, $request->to_date))->orderBy('name', 'asc')->get();
                }else{
                    $customers_list = DB::connection('mysql')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->orderBy('name', 'asc')->get();
                }
            }else{
                if(!empty($request->from_date)){
                    $customers_list = DB::connection('mysql')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('id_sales', Session::get('id_user_admin'))->whereBetween('cust.created_at', array($request->from_date, $request->to_date))->orderBy('name', 'asc')->get();
                }else{
                    $customers_list = DB::connection('mysql')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('id_sales', Session::get('id_user_admin'))->orderBy('name', 'asc')->get();
                }
            }

            return datatables()->of($customers_list)->addIndexColumn()->make(true);
        }
        return view('input_customers');
    }

    public function viewDataIMJTable(Request $request){
        if(request()->ajax()){
            if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
                if(!empty($request->from_date)){
                    $customers_list = DB::connection('mysql2')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->whereBetween('cust.created_at', array($request->from_date, $request->to_date))->orderBy('name', 'asc')->get();
                }else{
                    $customers_list = DB::connection('mysql2')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->orderBy('name', 'asc')->get();
                }
            }else{
                if(!empty($request->from_date)){
                    $customers_list = DB::connection('mysql2')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('id_sales', Session::get('id_user_admin'))->whereBetween('cust.created_at', array($request->from_date, $request->to_date))->orderBy('name', 'asc')->get();
                }else{
                    $customers_list = DB::connection('mysql2')->table('users as us')->select("cust.custid as custid", "cust.custname as name", "cust.company")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('id_sales', Session::get('id_user_admin'))->orderBy('name', 'asc')->get();
                }
            }

            return datatables()->of($customers_list)->addIndexColumn()->make(true);
        }
        return view('input_customers');
    }
}
