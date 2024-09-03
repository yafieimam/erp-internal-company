<?php

namespace App\Http\Controllers;

use App\ModelUser;
use App\ModelProduk;
use App\ModelKota;
use App\ModelProvinsi;
use App\ModelCustomers;
use App\ModelLeads;
use App\ModelKompetitor;
use App\ModelQuotation;
use App\ModelQuotationProduk;
use App\ModelOrders;
use App\ModelOrdersProduk;
use App\ModelProductionOrder;
use App\ModelAlamatHistory;
use App\ModelProductionOrderDetail;
use App\ModelHistoryOrders;

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
use App\Exports\CustomersFollowUpExport;
use App\Imports\PermintaanSampleDetailImport;
Use Exception;
use Response;
use Mail;
use File;
use Notification;
use PDF;
use Excel;

class AdminController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';

    public function index(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $omset_sales = DB::table('omset_penjualan as sales')->select("products.nama_produk as produk", "sales.tanggal_penjualan as tanggal", DB::raw('sum(sales.jumlah_customer) as jumlah_customer'), DB::raw('sum(sales.jumlah_tonase) as jumlah_tonase'), DB::raw('sum(sales.total_omset) as total_omset'))->join("products", "products.kode_produk", "=", "sales.kode_produk")->whereBetween('sales.tanggal_penjualan', array($request->from_date, $request->to_date))->groupBy('produk', 'tanggal_penjualan')->get();
            }else{
                $omset_sales = DB::table('omset_penjualan as sales')->select("products.nama_produk as produk", "sales.tanggal_penjualan as tanggal", DB::raw('sum(sales.jumlah_customer) as jumlah_customer'), DB::raw('sum(sales.jumlah_tonase) as jumlah_tonase'), DB::raw('sum(sales.total_omset) as total_omset'))->join("products", "products.kode_produk", "=", "sales.kode_produk")->whereDate('sales.tanggal_penjualan', Carbon::now()->toDateString())->groupBy('produk', 'tanggal_penjualan')->get();
            }

            return datatables()->of($omset_sales)->make(true);
        }
        return view('view_omset_sales');
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

    public function home_admin(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $reminder = DB::table('customers_visit as vis')->select("vis.tanggal_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as nama"), "vis.perihal", "vis.keterangan")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where('vis.id_user', Session::get('id_user_admin'))->where(function ($query) { $query->where('vis.status', 1)->orWhere('vis.status', 2); })->whereRaw("DATE(vis.tanggal_schedule) = CURDATE()")->get();

            // $follow_up = DB::table('customers_visit as vis')->select("vis.tanggal_schedule", DB::raw("ADDDATE(vis.tanggal_schedule, vis.range_follow_up) as tanggal_next"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as nama"), "vis.follow_up", "vis.range_follow_up")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where('vis.id_user', Session::get('id_user_admin'))->where('vis.status', 3)->havingRaw("ADDDATE(vis.tanggal_schedule, vis.range_follow_up) <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("ADDDATE(vis.tanggal_schedule, vis.range_follow_up) > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->get();

            $rem_order = DB::table('delivery_orders as delo')->select('delo.custid', 'delo.custname as nama_customer', DB::raw('max(delo.tanggal_do) as tanggal_terakhir'))->join('cust_aktif as akt', 'akt.custid', '=', 'delo.custid')->where('akt.aktif', 1)->groupBy('delo.custid')->havingRaw('tanggal_terakhir <= NOW() - INTERVAL 3 MONTH')->get();

            if(Session::get('tipe_user') == 1 || Session::get('tipe_user') == 19){
                if(Session::get('tipe_user') == 1){
                    $remind_project_warning = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 19)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                    $remind_project_danger = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 19)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();
                }else if(Session::get('tipe_user') == 19){
                    $remind_project_warning = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', Session::get('id_user_admin'))->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                    $remind_project_danger = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', Session::get('id_user_admin'))->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();
                }

                $remind_project_warning_sales = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where(function ($query) { $query->where('kanban_task_project.tipe_user', 2)->orWhere('kanban_task_project.tipe_user', 10); })->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger_sales = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where(function ($query) { $query->where('kanban_task_project.tipe_user', 2)->orWhere('kanban_task_project.tipe_user', 10); })->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_warning_produksi = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 3)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger_produksi = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 3)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_danger_hrd = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 5)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_warning_hrd = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 5)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger_logistik = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 6)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_warning_logistik = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 6)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger_timbangan = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 7)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_warning_timbangan = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 7)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger_warehouse = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 8)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_warning_warehouse = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 8)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger_lab = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 9)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_warning_lab = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 9)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger_teknik = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 16)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_warning_teknik = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 16)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger_raw_material = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 18)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();

                $remind_project_warning_raw_material = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', 18)->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();
            }else if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
                $remind_project_warning = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where(function ($query) { $query->where('kanban_task_project.tipe_user', 2)->orWhere('kanban_task_project.tipe_user', 10); })->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where(function ($query) { $query->where('kanban_task_project.tipe_user', 2)->orWhere('kanban_task_project.tipe_user', 10); })->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();
            }else{
                $remind_project_warning = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', Session::get('id_user_admin'))->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->distinct()->get();

                $remind_project_danger = DB::table('kanban_task_item')->select('title_name', 'deskripsi', 'deadline_date')->leftJoin('kanban_task_board_item', 'kanban_task_board_item.itemId', '=', 'kanban_task_item.id')->leftJoin('kanban_task_board', 'kanban_task_board.id', '=', 'kanban_task_board_item.boardId')->leftJoin('kanban_task_project', 'kanban_task_board.projectId', '=', 'kanban_task_project.id')->where('kanban_task_project.tipe_user', Session::get('id_user_admin'))->havingRaw("deadline_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->havingRaw("deadline_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)")->distinct()->get();
            }

            if(Session::get('tipe_user') == 1){
                $rem_complaint = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.nama_customer', 'com.tanggal_complaint', 'com.updated_at', DB::raw("concat(if(div.produksi = 2, concat('Produksi; '), ''), if(div.logistik = 2, concat('Logistik; '), ''), if(div.sales = 2, concat('Sales; '), ''), if(div.timbangan = 2, concat('Timbangan; '), ''), if(div.warehouse = 2, concat('Warehouse; '), ''), if(div.lab = 2, concat('Lab; '), ''), if(div.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"))->join('divisi_complaint as div', 'div.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->where('stat.validasi', '=', 1)->where('stat.validasi', '!=', 2)->whereRaw("com.updated_at <= NOW() - INTERVAL 2 DAY")->get();
            }else if(Session::get('tipe_user') == 2){
                $rem_complaint = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.nama_customer', 'com.tanggal_complaint', 'com.updated_at', DB::raw("concat(if(div.produksi = 2, concat('Produksi; '), ''), if(div.logistik = 2, concat('Logistik; '), ''), if(div.sales = 2, concat('Sales; '), ''), if(div.timbangan = 2, concat('Timbangan; '), ''), if(div.warehouse = 2, concat('Warehouse; '), ''), if(div.lab = 2, concat('Lab; '), ''), if(div.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), DB::raw("1 as no_div_sales"))->join('divisi_complaint as div', 'div.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->where(function($query) { $query->where('stat.sales', 0)->orWhere('stat.lainnya', 0)->orWhere('stat.validasi', 1); })->where(function($query) { $query->where('div.sales', 2)->orWhere('div.lainnya', 2); })->where('stat.validasi', '!=', 2)->whereRaw("com.updated_at <= NOW() - INTERVAL 2 DAY")->get();
            }else if(Session::get('tipe_user') == 3){
                $rem_complaint = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.nama_customer', 'com.tanggal_complaint', 'com.updated_at', DB::raw("concat(if(div.produksi = 2, concat('Produksi; '), ''), if(div.logistik = 2, concat('Logistik; '), ''), if(div.sales = 2, concat('Sales; '), ''), if(div.timbangan = 2, concat('Timbangan; '), ''), if(div.warehouse = 2, concat('Warehouse; '), ''), if(div.lab = 2, concat('Lab; '), ''), if(div.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"))->join('divisi_complaint as div', 'div.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->where(function($query) { $query->where('stat.produksi', 0)->orWhere('stat.validasi', 1); })->where('div.produksi', 2)->where('stat.validasi', '!=', 2)->whereRaw("com.updated_at <= NOW() - INTERVAL 2 DAY")->get();
            }else if(Session::get('tipe_user') == 6){
                $rem_complaint = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.nama_customer', 'com.tanggal_complaint', 'com.updated_at', DB::raw("concat(if(div.produksi = 2, concat('Produksi; '), ''), if(div.logistik = 2, concat('Logistik; '), ''), if(div.sales = 2, concat('Sales; '), ''), if(div.timbangan = 2, concat('Timbangan; '), ''), if(div.warehouse = 2, concat('Warehouse; '), ''), if(div.lab = 2, concat('Lab; '), ''), if(div.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), DB::raw("2 as no_div_sales"))->join('divisi_complaint as div', 'div.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->where(function($query) { $query->where('stat.logistik', 0)->orWhere('stat.validasi', 1); })->where('div.logistik', 2)->where('stat.validasi', '!=', 2)->whereRaw("com.updated_at <= NOW() - INTERVAL 2 DAY")->get();
            }else if(Session::get('tipe_user') == 7){
                $rem_complaint = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.nama_customer', 'com.tanggal_complaint', 'com.updated_at', DB::raw("concat(if(div.produksi = 2, concat('Produksi; '), ''), if(div.logistik = 2, concat('Logistik; '), ''), if(div.sales = 2, concat('Sales; '), ''), if(div.timbangan = 2, concat('Timbangan; '), ''), if(div.warehouse = 2, concat('Warehouse; '), ''), if(div.lab = 2, concat('Lab; '), ''), if(div.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"))->join('divisi_complaint as div', 'div.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->where(function($query) { $query->where('stat.timbangan', 0)->orWhere('stat.validasi', 1); })->where('div.timbangan', 2)->where('stat.validasi', '!=', 2)->whereRaw("com.updated_at <= NOW() - INTERVAL 2 DAY")->get();
            }else if(Session::get('tipe_user') == 8){
                $rem_complaint = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.nama_customer', 'com.tanggal_complaint', 'com.updated_at', DB::raw("concat(if(div.produksi = 2, concat('Produksi; '), ''), if(div.logistik = 2, concat('Logistik; '), ''), if(div.sales = 2, concat('Sales; '), ''), if(div.timbangan = 2, concat('Timbangan; '), ''), if(div.warehouse = 2, concat('Warehouse; '), ''), if(div.lab = 2, concat('Lab; '), ''), if(div.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"))->join('divisi_complaint as div', 'div.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->where(function($query) { $query->where('stat.warehouse', 0)->orWhere('stat.validasi', 1); })->where('div.warehouse', 2)->where('stat.validasi', '!=', 2)->whereRaw("com.updated_at <= NOW() - INTERVAL 2 DAY")->get();
            }else if(Session::get('tipe_user') == 9){
                $rem_complaint = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.nama_customer', 'com.tanggal_complaint', 'com.updated_at', DB::raw("concat(if(div.produksi = 2, concat('Produksi; '), ''), if(div.logistik = 2, concat('Logistik; '), ''), if(div.sales = 2, concat('Sales; '), ''), if(div.timbangan = 2, concat('Timbangan; '), ''), if(div.warehouse = 2, concat('Warehouse; '), ''), if(div.lab = 2, concat('Lab; '), ''), if(div.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), 'com.divisi as no_div')->join('divisi_complaint as div', 'div.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->where(function($query) { $query->where('stat.lab', 0)->orWhere('stat.validasi', 1); })->where('div.lab', 2)->where('stat.validasi', '!=', 2)->whereRaw("com.updated_at <= NOW() - INTERVAL 2 DAY")->get();
            }else if(Session::get('tipe_user') == 10){
                $rem_complaint = DB::table('complaint_customer as com')->select('com.nomor_complaint', 'com.nama_customer', 'com.tanggal_complaint', 'com.updated_at', DB::raw("concat(if(div.produksi = 2, concat('Produksi; '), ''), if(div.logistik = 2, concat('Logistik; '), ''), if(div.sales = 2, concat('Sales; '), ''), if(div.timbangan = 2, concat('Timbangan; '), ''), if(div.warehouse = 2, concat('Warehouse; '), ''), if(div.lab = 2, concat('Lab; '), ''), if(div.lainnya = 2, concat('Lainnya; '), '')) as divisi"), DB::raw("if(stat.validasi = 2, concat('Done; '), concat(if(stat.baca = 1, concat('Read; '), ''), if(stat.produksi = 2, concat('Produksi; '), ''), if(stat.logistik = 2, concat('Logistik; '), ''), if(stat.sales = 2, concat('Sales; '), ''), if(stat.timbangan = 2, concat('Timbangan; '), ''), if(stat.warehouse = 2, concat('Warehouse; '), ''), if(stat.lab = 2, concat('Lab; '), ''), if(stat.lainnya = 2, concat('Lainnya; '), ''))) as status"), DB::raw("1 as no_div_sales"))->join('divisi_complaint as div', 'div.nomor_complaint', '=', 'com.nomor_complaint')->join('status_complaint as stat', 'stat.nomor_complaint', '=', 'com.nomor_complaint')->where(function($query) { $query->where('stat.sales', 0)->orWhere('stat.lainnya', 0)->orWhere('stat.validasi', 1); })->where(function($query) { $query->where('div.sales', 2)->orWhere('div.lainnya', 2); })->where('stat.validasi', '!=', 2)->whereRaw("com.updated_at <= NOW() - INTERVAL 2 DAY")->get();
            }else{
                $rem_complaint = collect([]);
            }

            if(Session::get('tipe_user') == 3){
                $notif_uji_sample = DB::table('uji_sample as uji')->select('uji.nomor_uji_sample', 'uji.tanggal', 'uji.merk', 'uji.tipe', 'uji.status', 'uji.tanggal_pengujian_sample')->where(function($query) { $query->where('uji.status', 2)->orWhere('uji.status', 3); })->whereNull('uji.analisa')->whereRaw("uji.tanggal_pengujian_sample <= NOW() - INTERVAL 1 DAY")->get();
            }else if(Session::get('tipe_user') == 9){
                $notif_uji_sample = DB::table('uji_sample as uji')->select('uji.nomor_uji_sample', 'uji.tanggal', 'uji.merk', 'uji.tipe', 'uji.status', 'uji.tanggal_pengujian_sample')->where('uji.status', 1)->whereRaw("uji.tanggal <= NOW() - INTERVAL 5 DAY")->get();
            }else{
                $notif_uji_sample = collect([]);
            }

            if(Session::get('tipe_user') == 1 || Session::get('tipe_user') == 19){
                return view('admin', ['reminder' => $reminder, 'reminder_complaint' => $rem_complaint, 'reminder_project_warning' => $remind_project_warning, 'reminder_project_danger' => $remind_project_danger, 'reminder_project_warning_sales' => $remind_project_warning_sales, 'reminder_project_danger_sales' => $remind_project_danger_sales, 'reminder_project_warning_produksi' => $remind_project_warning_produksi, 'reminder_project_danger_produksi' => $remind_project_danger_produksi, 'reminder_project_warning_hrd' => $remind_project_warning_hrd, 'reminder_project_danger_hrd' => $remind_project_danger_hrd, 'reminder_project_warning_logistik' => $remind_project_warning_logistik, 'reminder_project_danger_logistik' => $remind_project_danger_logistik, 'reminder_project_warning_timbangan' => $remind_project_warning_timbangan, 'reminder_project_danger_timbangan' => $remind_project_danger_timbangan, 'reminder_project_warning_warehouse' => $remind_project_warning_warehouse, 'reminder_project_danger_warehouse' => $remind_project_danger_warehouse, 'reminder_project_warning_lab' => $remind_project_warning_lab, 'reminder_project_danger_lab' => $remind_project_danger_lab, 'reminder_project_warning_teknik' => $remind_project_warning_teknik, 'reminder_project_danger_teknik' => $remind_project_danger_teknik, 'reminder_project_warning_raw_material' => $remind_project_warning_raw_material, 'reminder_project_danger_raw_material' => $remind_project_danger_raw_material]);
            }else{
                return view('admin', ['reminder' => $reminder, 'reminder_complaint' => $rem_complaint, 'reminder_project_warning' => $remind_project_warning, 'reminder_project_danger' => $remind_project_danger, 'notif_uji_sample' => $notif_uji_sample]);
            }
        }
    }

    public function omset_sls(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $produk_data =ModelProduk::select('kode_produk','nama_produk')->get();
            return view('input_omset_sls', compact('produk_data'));
        }
    }

    public function viewHRD(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('input_hrd');
        }
    }

    public function viewFileSales(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_sales');
        }
    }

    public function viewFileProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_produksi');
        }
    }

    public function viewFileLogistik(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_logistik');
        }
    }

    public function viewFileTimbangan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_timbangan');
        }
    }

    public function viewFileWarehouse(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_warehouse');
        }
    }

    public function viewFileLab(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_lab');
        }
    }

    public function viewFileAccounting(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_accounting');
        }
    }

    public function viewFileIT(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_it');
        }
    }

    public function viewFilePurchase(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_purchase');
        }
    }

    public function viewFileSecurity(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_security');
        }
    }

    public function viewFileTeknik(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('file_manager_teknik');
        }
    }

    public function lihatCalendarCustomerVisit(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $calendar = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", "vis.tipe_customer as no_tipe_customers", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as nama"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custid WHEN vis.tipe_customer = 2 THEN lead.leadid WHEN vis.tipe_customer = 3 THEN komp.kompid END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", "vis.keterangan", "vis.offline", "vis.company", "vis.tanggal_input", "vis.tanggal_done", "stat.name as status", "vis.alasan_suspend", "vis.status as no_status", "vis.waktu_schedule")->join("status_cust_visit as stat", "stat.id", "=", "vis.status")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where('id_user', Session::get('id_user_admin'))->get();
            return view('lihat_calendar_customer_visit', ['data_schedule' => $calendar]);
        }
    }

    public function adminSales(){
        return view('sales_admin');
    }

    public function customers_view($nomor = null){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $id_user = Session::get('id_user_admin');
            $user = ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
            $user->unreadNotifications->where('type', 'App\Notifications\NotifNewCustomers')->markAsRead();
            if(!empty($nomor)){
                try{
                    $val_nomor = Crypt::decrypt($nomor);
                } catch (DecryptException $e) {
                    $val_nomor = $nomor;
                }
                return view('input_customers', ['any_nomor' => $val_nomor]);
            }else{
                return view('input_customers');
            }
        }
    }

    public function customers_group_view(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            DB::table('temp_customer_group')->where('id_user', Session::get('id_user_admin'))->delete();

            return view('input_customer_group');
        }
    }

    public function indexCustomersSpecific(Request $request){
        $customers_list = DB::table('users as us')->select("cust.custid as custid", "cust.custname as name")->rightJoin("customers as cust", "cust.custid", "=", "us.custid")->where('us.custid', $request->nomor)->get();

        return datatables()->of($customers_list)->addIndexColumn()->make(true);
    }

    public function orders_view($nomor = null){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $id_user = Session::get('id_user_admin');
            $user = ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
            $user->unreadNotifications->where('type', 'App\Notifications\NotifNewOrders')->markAsRead();
            if(!empty($nomor)){
                try{
                    $val_nomor = Crypt::decrypt($nomor);
                } catch (DecryptException $e) {
                    $val_nomor = $nomor;
                }
                return view('input_orders', ['any_nomor' => $val_nomor]);
            }else{
                return view('input_orders');
            }
        }
    }

    public function viewListStaffProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('list_staff_produksi');
        }
    }

    public function viewListResume(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $id_user = Session::get('id_user_admin');
            $user = ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
            $user->unreadNotifications->where('type', 'App\Notifications\NotifNewResume')->markAsRead();
            return view('list_resume');
        }
    }

    public function viewListContactUs(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $id_user = Session::get('id_user_admin');
            $user = ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
            $user->unreadNotifications->where('type', 'App\Notifications\NotifNewContactUs')->markAsRead();
            return view('list_contact_us');
        }
    }

    public function adminProduksi(){
        return view('produksi_admin');
    }

    public function getTotalSales(){
        $currentMonth = date('m');
        $currentYear = date('Y');

        $data = DB::select("select sum(dodo.total_omset) as omset from (select delo.tanggal_do as tanggal, count(distinct delo.custid) as jumlah_customer, sum(delo.qty) / 1000 as jumlah_tonase, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total_omset from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (10 * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid where month(delo.tanggal_do) = ? and year(delo.tanggal_do) = ? group by delo.tanggal_do) as dodo", [$currentMonth, $currentYear]);

        return Response()->json($data);
    }

    public function getOmsetValueSales(){
        // $currentYear = date('Y');

        $data = DB::select("select YEAR(dodo.tanggal) as tahun, MONTH(dodo.tanggal) AS bulan, sum(dodo.total) as omset from (select delo.tanggal_do as tanggal, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (10 * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid group by delo.tanggal_do) dodo where dodo.tanggal between '1970-01-01' and ? group by year(dodo.tanggal), month(dodo.tanggal)", [date('Y-m-d')]);

        return Response()->json($data);
    }

    public function getOmsetValueSalesLine(){
        // $currentYear = date('Y');

        $data = DB::select("select dodo.tanggal as tanggal, sum(dodo.total) as omset from (select delo.tanggal_do as tanggal, (coalesce(sum(delo2.sum1), 0) + coalesce(sum(delo3.sum2), 0)) as total from delivery_orders as delo left join (select (sum(delor.sub_amount) - sum(delor.diskon)) as sum1, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn = 0 group by delor.nosj) delo2 on delo.nosj=delo2.nosj and delo.itemid = delo2.itemid left join (select (sum(delor.sub_amount) - sum(delor.diskon)) + (10 * (sum(delor.sub_amount) - sum(delor.diskon)) / 100) as sum2, delor.nosj, delor.itemid from delivery_orders as delor where delor.ppn > 0 group by delor.nosj) delo3 on delo.nosj=delo3.nosj and delo.itemid = delo3.itemid group by delo.tanggal_do) dodo where dodo.tanggal between '1970-01-01' and ? group by dodo.tanggal", [date('Y-m-d')]);

        return Response()->json($data);
    }

    public function getOmsetDPPSales(){
        // $currentYear = date('Y');

        $data = DB::select("select YEAR(dodo.tanggal) as tahun, MONTH(dodo.tanggal) AS bulan, sum(dodo.dpp) as omset from (select delo.tanggal_do as tanggal, delo.dpp from delivery_orders as delo group by delo.nosj) dodo where dodo.tanggal between '1970-01-01' and ? group by year(dodo.tanggal), month(dodo.tanggal)", [date('Y-m-d')]);

        return Response()->json($data);
    }

    public function getOmsetDPPSalesLine(){
        // $currentYear = date('Y');

        $data = DB::select("select dodo.tanggal as tanggal, sum(dodo.dpp) as omset from (select delo.tanggal_do as tanggal, delo.dpp from delivery_orders as delo group by delo.nosj) dodo where dodo.tanggal between '1970-01-01' and ? group by dodo.tanggal", [date('Y-m-d')]);

        return Response()->json($data);
    }

    public function getOmsetTonaseSales(){
        // $currentYear = date('Y');

        $data = DB::select("select YEAR(dodo.tanggal) as tahun, MONTH(dodo.tanggal) AS bulan, sum(dodo.kuantitas) / 1000 as tonase from (select do.tanggal_do as tanggal, sum(do.qty) as kuantitas from delivery_orders do group by do.tanggal_do) dodo where dodo.tanggal between '1970-01-01' and ? group by year(dodo.tanggal), month(dodo.tanggal)", [date('Y-m-d')]);

        return Response()->json($data);
    }

    public function getTransaksiSales(){
        // $currentYear = date('Y');

        $data = DB::select("select YEAR(dod.tanggal) as tahun, MONTH(dod.tanggal) AS bulan, sum(dod.jual) as penjualan from (select do.tanggal_do as tanggal, count(distinct do.nosj) as jual from delivery_orders as do group by do.tanggal_do) dod where dod.tanggal between '1970-01-01' and ? group by year(dod.tanggal), month(dod.tanggal)", [date('Y-m-d')]);

        return Response()->json($data);
    }

    public function getComplaintSales(){
        // $currentYear = date('Y');

        $data = DB::select("select YEAR(dod.tanggal) as tahun, MONTH(dod.tanggal) AS bulan, sum(dod.comp) as complaint from (select do.tanggal_do as tanggal, count(DISTINCT co.nomor_complaint) as comp from delivery_orders as do left join complaint_customer as co on do.tanggal_do = co.tanggal_complaint group by do.tanggal_do UNION select co.tanggal_complaint as tanggal, count(DISTINCT co.nomor_complaint) as comp from delivery_orders as do right join complaint_customer as co on do.tanggal_do = co.tanggal_complaint group by do.tanggal_do) dod where dod.tanggal between '1970-01-01' and ? group by year(dod.tanggal), month(dod.tanggal)", [date('Y-m-d')]);

        return Response()->json($data);
    }

    public function editPassword(){
        return view('setting_password');
    }

    public function editProfile(){
        $email = Session::get('email');
        $customer = DB::table('users as us')->select('us.email as email', 'type.type as tipe_customer')->join('user_type as type', 'type.id_customer_type', '=', 'us.id_customer_type')->where("us.email", $email)->first();

        return view('setting_profile')->with('customer', $customer);
    }

    public function editProfileProcess(Request $request){
        $this->validate($request, [
            'email' => 'required|min:4|email|unique:users',
        ]);

        $email = Session::get('email');
        $data = ModelUser::where('email', $email)->update(['email' => $request->email]);

        if($data){
            Session::forget('email');
            Session::put('email',$request->email);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 4, 'action' => 'User ' . $request->email . ' Melakukan Perubahan Data']);

            return redirect('settings/profile')->with('alert','Profile is Successfully Updated');
        }else{
            return redirect('settings/profile')->with('alert','Something Wrong, Please Try Again Later');
        }
    }

    public function editPasswordProcess(Request $request){
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_pass' => 'required|same:new_password',
        ]);

        $old_password = $request->old_password;
        $email = Session::get('email');
        $new_password = bcrypt($request->new_password);

        $data = ModelUser::where('email', $email)->first();
        if($request->old_password == $request->new_password){
            return redirect('settings/password')->with('alert','Old Password and New Password Must Not be Same');
        }else if(Hash::check($old_password,$data->password)){
            ModelUser::where('email', $email)->update([
                    'password' => $new_password
                ]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $email, 'status_user' => 3, 'action' => 'User ' . $email . ' Melakukan Penggantian Password']);

            return redirect('homepage')->with('alert','Password is Successfully Updated');
        }else{
            return redirect('settings/password')->with('alert','Old Password is Wrong');
        }
    }

    public function inputFollowupAdmin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal_history = date('ym');

        $data_followup = DB::table('follow_up_customer')->select('nomor_followup')->where('nomor_followup', 'like', 'FOU' . $tanggal_history . '%')->orderBy('nomor_followup', 'asc')->distinct()->get();

        if($data_followup){
            $followup_count = $data_followup->count();
            if($followup_count > 0){
                $num = (int) substr($data_followup[$data_followup->count() - 1]->nomor_followup, 8);
                if($followup_count != $num){
                    $kode_followup = ++$data_followup[$data_followup->count() - 1]->nomor_followup;
                }else{
                    if($followup_count < 9){
                        $kode_followup = "FOU" . $tanggal_history . "-00000" . ($followup_count + 1);
                    }else if($followup_count >= 9 && $followup_count < 99){
                        $kode_followup = "FOU" . $tanggal_history . "-0000" . ($followup_count + 1);
                    }else if($followup_count >= 99 && $followup_count < 999){
                        $kode_followup = "FOU" . $tanggal_history . "-000" . ($followup_count + 1);
                    }else if($followup_count >= 999 && $followup_count < 9999){
                        $kode_followup = "FOU" . $tanggal_history . "-00" . ($followup_count + 1);
                    }else if($followup_count >= 9999 && $followup_count < 99999){
                        $kode_followup = "FOU" . $tanggal_history . "-0" . ($followup_count + 1);
                    }else{
                        $kode_followup = "FOU-" . $tanggal_history . ($followup_count + 1);
                    }
                }
            }else{
                $kode_followup = "FOU" . $tanggal_history . "-000001";
            }
        }else{
            $kode_followup = "FOU" . $tanggal_history . "-000001";
        }

        $data = DB::table('follow_up_customer')->insert(["nomor_followup" => $kode_followup, "tanggal" => $request->tanggal_followup, "custid" => $request->get('customer'), "aktivitas" => $request->aktivitas, "informasi" => $request->informasi, "created_by" => Session::get('id_user_admin'), "tanggal_input" => date('Y-m-d')]);

        if($data){
            $arr = array('msg' => 'Successfully Store Data', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function inputScheduleSalesOffline(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal_schedule = date('ym');

        $data_schedule = DB::table('customers_visit')->select('id_schedule')->where('id_schedule', 'like', 'FLWUP' . $tanggal_schedule . '%')->orderBy('id_schedule', 'asc')->distinct()->get();

        if($data_schedule){
            $schedule_count = $data_schedule->count();
            if($schedule_count > 0){
                $num = (int) substr($data_schedule[$data_schedule->count() - 1]->id_schedule, 10);
                if($schedule_count != $num){
                    $kode_schedule = ++$data_schedule[$data_schedule->count() - 1]->id_schedule;
                }else{
                    if($schedule_count < 9){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-00000" . ($schedule_count + 1);
                    }else if($schedule_count >= 9 && $schedule_count < 99){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-0000" . ($schedule_count + 1);
                    }else if($schedule_count >= 99 && $schedule_count < 999){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-000" . ($schedule_count + 1);
                    }else if($schedule_count >= 999 && $schedule_count < 9999){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-00" . ($schedule_count + 1);
                    }else if($schedule_count >= 9999 && $schedule_count < 99999){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-0" . ($schedule_count + 1);
                    }else{
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-" . ($schedule_count + 1);
                    }
                }
            }else{
                $kode_schedule = "FLWUP" . $tanggal_schedule . "-000001";
            }
        }else{
            $kode_schedule = "FLWUP" . $tanggal_schedule . "-000001";
        }

        $cek_data = DB::table('customers_visit')->select('order_sort')->where('tanggal_schedule', date('Y-m-d', strtotime($request->tanggal_jadwal)))->where('id_user', Session::get('id_user_admin'))->orderBy('order_sort', 'asc')->distinct()->get();

        if($cek_data->count() > 0){
            $sort = ++$cek_data[$cek_data->count() - 1]->order_sort;
        }else{
            $sort = 1;
        }

        if($request->new_nama_customers != NULL || $request->new_nama_customers != ''){
            if($request->tipe_customers == 1){
                $data_kota = ModelKota::where('id_kota', $request->new_city)->first();
                $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
                $nama_user = strtoupper(str_replace(' ', '', $request->new_nama_customers));
                $kode_nama = substr($nama_user, 0, 5);

                $kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;
                $data_custid = ModelCustomers::where('custid', 'like', '%' . $kode_cust . '%')->orderBy('custid', 'asc')->get();

                if($data_custid){
                    $data_count = $data_custid->count();
                    if($data_count < 9){
                        $kode_cust = $kode_cust . "0" . ($data_count + 1);
                    }else{
                        $kode_cust = $kode_cust . ($data_count + 1);
                    }
                }else{
                    $kode_cust = $kode_cust . "01";
                }

                $data_cust =  new ModelCustomers();
                $data_cust->custid = $kode_cust;
                $data_cust->custname = $request->new_nama_customers;
                $data_cust->company = $request->company;
                $data_cust->address = $request->new_alamat;
                $data_cust->phone = $request->new_telepon;
                $data_cust->city = $request->new_city;
                $data_cust->created_by = Session::get('id_user_admin');
                $data_cust->updated_by = Session::get('id_user_admin');
                $data_cust->save();

                $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "company" => $request->company, "tipe_customer" => $request->tipe_customers, "customers" => $kode_cust, "perihal" => $request->perihal, "offline" => 1, "keterangan" => $request->keterangan, "tanggal_schedule" => $request->tanggal_jadwal, "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Melalui Customers Follow Up']);
            }else if($request->tipe_customers == 2){
                $data_kota = ModelKota::where('id_kota', $request->new_city)->first();
                $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
                $nama_user = strtoupper(str_replace(' ', '', $request->new_nama_customers));
                $kode_nama = substr($nama_user, 0, 5);

                $kode_cust = 'LEAD' . $data_provinsi->kode . $data_kota->kode . $kode_nama;
                $data_custid = ModelLeads::where('leadid', 'like', '%' . $kode_cust . '%')->orderBy('leadid', 'asc')->get();

                if($data_custid){
                    $data_count = $data_custid->count();
                    if($data_count < 9){
                        $kode_cust = $kode_cust . "0" . ($data_count + 1);
                    }else{
                        $kode_cust = $kode_cust . ($data_count + 1);
                    }
                }else{
                    $kode_cust = $kode_cust . "01";
                }

                $data_cust =  new ModelLeads();
                $data_cust->leadid = $kode_cust;
                $data_cust->nama = $request->new_nama_customers;
                $data_cust->company = $request->company;
                $data_cust->address = $request->new_alamat;
                $data_cust->phone = $request->new_telepon;
                $data_cust->city = $request->new_city;
                $data_cust->created_by = Session::get('id_user_admin');
                $data_cust->updated_by = Session::get('id_user_admin');
                $data_cust->status = 1;
                $data_cust->save();

                $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "company" => $request->company, "tipe_customer" => $request->tipe_customers, "customers" => $kode_cust, "perihal" => $request->perihal, "offline" => 1, "keterangan" => $request->keterangan, "tanggal_schedule" => $request->tanggal_jadwal, "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_cust, 'status' => 1, 'action' => 'Data Leads ' . $kode_cust . ' Dibuat Melalui Customers Follow Up']);
            }else if($request->tipe_customers == 3){
                $data_kota = ModelKota::where('id_kota', $request->new_city)->first();
                $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
                $nama_user = strtoupper(str_replace(' ', '', $request->new_nama_customers));
                $kode_nama = substr($nama_user, 0, 5);

                $kode_cust = 'KOMP' . $data_provinsi->kode . $data_kota->kode . $kode_nama;
                $data_custid = ModelKompetitor::where('kompid', 'like', '%' . $kode_cust . '%')->orderBy('kompid', 'asc')->get();

                if($data_custid){
                    $data_count = $data_custid->count();
                    if($data_count < 9){
                        $kode_cust = $kode_cust . "0" . ($data_count + 1);
                    }else{
                        $kode_cust = $kode_cust . ($data_count + 1);
                    }
                }else{
                    $kode_cust = $kode_cust . "01";
                }

                $data_cust =  new ModelKompetitor();
                $data_cust->kompid = $kode_cust;
                $data_cust->nama = $request->new_nama_customers;
                $data_cust->company = $request->company;
                $data_cust->address = $request->new_alamat;
                $data_cust->phone = $request->new_telepon;
                $data_cust->city = $request->new_city;
                $data_cust->status = 1;
                $data_cust->created_by = Session::get('id_user_admin');
                $data_cust->updated_by = Session::get('id_user_admin');
                $data_cust->save();

                $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "company" => $request->company, "tipe_customer" => $request->tipe_customers, "customers" => $kode_cust, "perihal" => $request->perihal, "offline" => 1, "keterangan" => $request->keterangan, "tanggal_schedule" => $request->tanggal_jadwal, "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_cust, 'status' => 1, 'action' => 'Data Kompetitor ' . $kode_cust . ' Dibuat Melalui Customers Follow Up']);
            }
        }else{
            $kode_cust = null;
            if($request->tipe_customers == 1){
                $kode_cust = $request->get('customers');
            }else if($request->tipe_customers == 2){
                $kode_cust = $request->get('leads');
            }else if($request->tipe_customers == 3){
                $kode_cust = $request->get('kompetitor');
            }

            $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "company" => $request->company, "tipe_customer" => $request->tipe_customers, "customers" => $kode_cust, "perihal" => $request->perihal, "offline" => 1, "keterangan" => $request->keterangan, "tanggal_schedule" => $request->tanggal_jadwal, "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit No. ' . $kode_schedule]);

        return Response()->json($arr);
    }

    public function inputScheduleSalesOnline(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal_schedule = date('ym');

        $data_schedule = DB::table('customers_visit')->select('id_schedule')->where('id_schedule', 'like', 'FLWUP' . $tanggal_schedule . '%')->orderBy('id_schedule', 'asc')->distinct()->get();

        if($data_schedule){
            $schedule_count = $data_schedule->count();
            if($schedule_count > 0){
                $num = (int) substr($data_schedule[$data_schedule->count() - 1]->id_schedule, 10);
                if($schedule_count != $num){
                    $kode_schedule = ++$data_schedule[$data_schedule->count() - 1]->id_schedule;
                }else{
                    if($schedule_count < 9){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-00000" . ($schedule_count + 1);
                    }else if($schedule_count >= 9 && $schedule_count < 99){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-0000" . ($schedule_count + 1);
                    }else if($schedule_count >= 99 && $schedule_count < 999){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-000" . ($schedule_count + 1);
                    }else if($schedule_count >= 999 && $schedule_count < 9999){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-00" . ($schedule_count + 1);
                    }else if($schedule_count >= 9999 && $schedule_count < 99999){
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-0" . ($schedule_count + 1);
                    }else{
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-" . ($schedule_count + 1);
                    }
                }
            }else{
                $kode_schedule = "FLWUP" . $tanggal_schedule . "-000001";
            }
        }else{
            $kode_schedule = "FLWUP" . $tanggal_schedule . "-000001";
        }

        $cek_data = DB::table('customers_visit')->select('order_sort')->where('tanggal_schedule', $request->tanggal_jadwal)->where('id_user', Session::get('id_user_admin'))->orderBy('order_sort', 'asc')->distinct()->get();

        if($cek_data->count() > 0){
            $sort = ++$cek_data[$cek_data->count() - 1]->order_sort;
        }else{
            $sort = 1;
        }

        if($request->new_nama_customers_online != NULL || $request->new_nama_customers_online != ''){
            if($request->tipe_customers_online == 1){
                $data_kota = ModelKota::where('id_kota', $request->new_city_online)->first();
                $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
                $nama_user = strtoupper(str_replace(' ', '', $request->new_nama_customers_online));
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
                $data_cust->custname = $request->new_nama_customers_online;
                $data_cust->company = $request->company_online;
                $data_cust->address = $request->new_alama_onlinet;
                $data_cust->phone = $request->new_telepon_online;
                $data_cust->city = $request->new_city_online;
                $data_cust->created_by = Session::get('id_user_admin');
                $data_cust->updated_by = Session::get('id_user_admin');
                $data_cust->save();

                $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "company" => $request->company_online, "tipe_customer" => $request->tipe_customers_online, "customers" => $kode_cust, "perihal" => $request->perihal_online, "offline" => 0, "tanggal_schedule" => $request->tanggal_jadwal_online, "tanggal_done" => $request->tanggal_jadwal_online, "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 5]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin Melalui Customers Visit']);
            }else if($request->tipe_customers_online == 2){
                $data_kota = ModelKota::where('id_kota', $request->new_city_online)->first();
                $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
                $nama_user = strtoupper(str_replace(' ', '', $request->new_nama_customers_online));
                $kode_nama = substr($nama_user, 0, 5);

                $kode_cust = 'LEAD' . $data_provinsi->kode . $data_kota->kode . $kode_nama;
                $data_custid = ModelLeads::where('leadid', 'like', '%' . $kode_cust . '%')->orderBy('leadid', 'asc')->get();

                if($data_custid){
                    $data_count = $data_custid->count();
                    if($data_count > 0){
                        $num = (int) substr($data_custid[$data_custid->count() - 1]->leadid, 14);
                        if($data_count != $num){
                            $kode_cust = ++$data_custid[$data_custid->count() - 1]->leadid;
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

                $data_cust =  new ModelLeads();
                $data_cust->leadid = $kode_cust;
                $data_cust->nama = $request->new_nama_customers_online;
                $data_cust->company = $request->company_online;
                $data_cust->address = $request->new_alamat_online;
                $data_cust->phone = $request->new_telepon_online;
                $data_cust->city = $request->new_city_online;
                $data_cust->status = 1;
                $data_cust->created_by = Session::get('id_user_admin');
                $data_cust->updated_by = Session::get('id_user_admin');
                $data_cust->save();

                $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "company" => $request->company_online, "tipe_customer" => $request->tipe_customers_online, "customers" => $kode_cust, "perihal" => $request->perihal_online, "offline" => 0, "tanggal_schedule" => $request->tanggal_jadwal_online, "tanggal_done" => $request->tanggal_jadwal_online, "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 5]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_cust, 'status' => 1, 'action' => 'Data Leads ' . $kode_cust . ' Dibuat Oleh Admin']);
            }else if($request->tipe_customers_online == 3){
                $data_kota = ModelKota::where('id_kota', $request->new_city_online)->first();
                $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
                $nama_user = strtoupper(str_replace(' ', '', $request->new_nama_customers_online));
                $kode_nama = substr($nama_user, 0, 5);

                $kode_cust = 'KOMP' . $data_provinsi->kode . $data_kota->kode . $kode_nama;
                $data_custid = ModelKompetitor::where('kompid', 'like', '%' . $kode_cust . '%')->orderBy('kompid', 'asc')->get();

                if($data_custid){
                    $data_count = $data_custid->count();
                    if($data_count > 0){
                        $num = (int) substr($data_custid[$data_custid->count() - 1]->kompid, 15);
                        if($data_count != $num){
                            $kode_cust = ++$data_custid[$data_custid->count() - 1]->kompid;
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

                $data_cust =  new ModelKompetitor();
                $data_cust->kompid = $kode_cust;
                $data_cust->nama = $request->new_nama_customers_online;
                $data_cust->company = $request->company_online;
                $data_cust->address = $request->new_alamat_online;
                $data_cust->phone = $request->new_telepon_online;
                $data_cust->city = $request->new_city_online;
                $data_cust->created_by = Session::get('id_user_admin');
                $data_cust->updated_by = Session::get('id_user_admin');
                $data_cust->save();

                $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "company" => $request->company_online, "tipe_customer" => $request->tipe_customers_online, "customers" => $kode_cust, "perihal" => $request->perihal_online, "offline" => 0, "tanggal_schedule" => $request->tanggal_jadwal_online, "tanggal_done" => $request->tanggal_jadwal_online, "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 5]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_cust, 'status' => 1, 'action' => 'Data Kompetitor ' . $kode_cust . ' Dibuat Oleh Admin']);
            }
        }else{
            $kode_cust = null;
            if($request->tipe_customers_online == 1){
                $kode_cust = $request->get('customers_online');
            }else if($request->tipe_customers_online == 2){
                $kode_cust = $request->get('leads_online');
            }else if($request->tipe_customers_online == 3){
                $kode_cust = $request->get('kompetitor_online');
            }

            $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "company" => $request->company_online, "tipe_customer" => $request->tipe_customers_online, "customers" => $kode_cust, "perihal" => $request->perihal_online, "offline" => 0, "tanggal_schedule" => $request->tanggal_jadwal_online, "tanggal_done" => $request->tanggal_jadwal_online, "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 5]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 5, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit dan Langsung Realisasi Case List No. ' . $kode_schedule]);

        return Response()->json($arr);
    }

    public function inputCustScheduleSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal_schedule = date('ym');

        $data_schedule = DB::table('customers_visit')->select('id_schedule')->where('id_schedule', 'like', 'CVST' . $tanggal_schedule . '%')->orderBy('id_schedule', 'asc')->distinct()->get();

        if($data_schedule){
            $schedule_count = $data_schedule->count();
            if($schedule_count > 0){
                $num = (int) substr($data_schedule[$data_schedule->count() - 1]->id_schedule, 9);
                if($schedule_count != $num){
                    $kode_schedule = ++$data_schedule[$data_schedule->count() - 1]->id_schedule;
                }else{
                    if($schedule_count < 9){
                        $kode_schedule = "CVST" . $tanggal_schedule . "-00000" . ($schedule_count + 1);
                    }else if($schedule_count >= 9 && $schedule_count < 99){
                        $kode_schedule = "CVST" . $tanggal_schedule . "-0000" . ($schedule_count + 1);
                    }else if($schedule_count >= 99 && $schedule_count < 999){
                        $kode_schedule = "CVST" . $tanggal_schedule . "-000" . ($schedule_count + 1);
                    }else if($schedule_count >= 999 && $schedule_count < 9999){
                        $kode_schedule = "CVST" . $tanggal_schedule . "-00" . ($schedule_count + 1);
                    }else if($schedule_count >= 9999 && $schedule_count < 99999){
                        $kode_schedule = "CVST" . $tanggal_schedule . "-0" . ($schedule_count + 1);
                    }else{
                        $kode_schedule = "CVST-" . $tanggal_schedule . ($schedule_count + 1);
                    }
                }
            }else{
                $kode_schedule = "CVST" . $tanggal_schedule . "-000001";
            }
        }else{
            $kode_schedule = "CVST" . $tanggal_schedule . "-000001";
        }

        $data = DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => Session::get('id_user_admin'), "nama_schedule" => $request->name_schedule, "customers" => $request->get('customers_schedule'), "perihal" => $request->perihal, "keterangan" => $request->keterangan_schedule, "tanggal_schedule" => $request->tanggal_jadwal, "tanggal_input" => date('Y-m-d'), "status" => 1]);

        if($data){
            $arr = array('msg' => 'Successfully Store Data', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Customer Visit No. ' . $kode_schedule]);

        return Response()->json($arr);
    }

    public function prosesScheduleSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data_exist = DB::table('customers_visit')->select('tanggal_schedule')->where('id_schedule', $request->proses_id_schedule)->first();

        if($request->proses_status == 2){
            $data = DB::table('customers_visit')->where("id_schedule", $request->proses_id_schedule)->update(["alasan_suspend" => $request->alasan_suspend, "tanggal_schedule" => $request->proses_tanggal_jadwal, "status" => $request->proses_status]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Ubah Data Customer Visit No. ' . $request->proses_id_schedule . ' Menjadi Suspend']);

        }else if($request->proses_status == 3){
            if($request->follow_up == 1){
                $data = DB::table('customers_visit')->where("id_schedule", $request->proses_id_schedule)->update(["result" => $request->result, "follow_up" => $request->follow_up, "range_follow_up" => $request->range_follow_up, "status" => $request->proses_status, "tanggal_schedule" => $data_exist->tanggal_schedule, "tanggal_done" => date('Y-m-d')]);
            }else if($request->follow_up == 2){
                $data = DB::table('customers_visit')->where("id_schedule", $request->proses_id_schedule)->update(["result" => $request->result, "follow_up" => $request->follow_up, "status" => $request->proses_status, "tanggal_schedule" => $data_exist->tanggal_schedule, "tanggal_done" => date('Y-m-d')]);
            }
            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 3, 'action' => 'User ' . Session::get('id_user_admin') . ' Ubah Data Customer Visit No. ' . $request->proses_id_schedule . ' Menjadi Done']);
        }

        if($data){
            $arr = array('msg' => 'Successfully Update Data', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function editScheduleSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        
        $kode_cust = null;
        if($request->edit_tipe_customers == 1){
            $kode_cust = $request->get('edit_customers');
        }else if($request->edit_tipe_customers == 2){
            $kode_cust = $request->get('edit_leads');
        }else if($request->edit_tipe_customers == 3){
            $kode_cust = $request->get('edit_kompetitor');
        }

        $data = DB::table('customers_visit')->where("id_schedule", $request->edit_id_schedule)->update(["company" => $request->edit_company, "tipe_customer" => $request->edit_tipe_customers, "customers" => $kode_cust, "perihal" => $request->edit_perihal, "permintaan_sample" => $request->edit_permintaan_sample, "nomor_penawaran" => $request->edit_penawaran, "offline" => $request->edit_offline, "keterangan" => $request->edit_keterangan, "tanggal_schedule" => $request->edit_tanggal_jadwal, "alasan_suspend" => $request->edit_alasan_suspend]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 4, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Customer Visit No. ' . $request->edit_id_schedule . ' Dengan Status 1 / 2']);

        return Response()->json($arr);
    }

    public function viewScheduleSales(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule as jadwal", "vis.customers as custid", "vis.tipe_customer as tipe", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", "stat.name as status", "vis.status as no_status", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"), DB::raw("IF(vis.tanggal_schedule <= NOW() - INTERVAL 1 DAY, 'YES', NULL) as tampil_proses"))->join("status_cust_visit as stat", "stat.id", "=", "vis.status")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where("vis.id_user", Session::get('id_user_admin'))->whereBetween('vis.tanggal_schedule', array($request->from_date, $request->to_date))->get();
            }else{
                $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule as jadwal", "vis.customers as custid", "vis.tipe_customer as tipe", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", "stat.name as status", "vis.status as no_status", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"), DB::raw("IF(vis.tanggal_schedule <= NOW() - INTERVAL 1 DAY, 'YES', NULL) as tampil_proses"))->join("status_cust_visit as stat", "stat.id", "=", "vis.status")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where("vis.id_user", Session::get('id_user_admin'))->orderByRaw("DATE(vis.tanggal_schedule)=DATE(NOW()) DESC, IF(DATE(vis.tanggal_schedule)=DATE(NOW()),vis.tanggal_schedule,DATE(NULL)) DESC, vis.tanggal_schedule DESC")->get();
            }

            return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_customers_visit')->rawColumns(['action'])->make(true);
        }
    }

    public function scheduleRoutePlanTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $schedule = DB::table('customers_visit')->select('tanggal_schedule', DB::raw("count(distinct id_schedule) as jumlah_data"))->where(function ($query) { $query->where('status', 1)->orWhere('status', 2); })->where("id_user", Session::get('id_user_admin'))->whereBetween('tanggal_schedule', array($request->from_date, $request->to_date))->groupBy('tanggal_schedule')->get();
            }else{
                $schedule = DB::table('customers_visit')->select('tanggal_schedule', DB::raw("count(distinct id_schedule) as jumlah_data"))->where(function ($query) { $query->where('status', 1)->orWhere('status', 2); })->where("id_user", Session::get('id_user_admin'))->groupBy('tanggal_schedule')->get();
            }

            return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_schedule_route_plan')->rawColumns(['action'])->make(true);
        }
    }

    public function scheduleRoutePlanSortingTable(Request $request){
        if(request()->ajax()){
            $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.address WHEN vis.tipe_customer = 2 THEN lead.address WHEN vis.tipe_customer = 3 THEN komp.address END as alamat"), "vis.perihal", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"), 'vis.waktu_schedule', 'vis.order_sort')->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where(function ($query) { $query->where('vis.status', 1)->orWhere('vis.status', 2); })->where("vis.id_user", Session::get('id_user_admin'))->where('vis.tanggal_schedule', $request->tanggal)->get();

            return datatables()->of($schedule)->make(true);
        }
    }

    public function saveScheduleRoutePlanSorting(Request $request){
        DB::table('customers_visit')->where('id_schedule', $request->id_schedule)->update(['order_sort' => $request->newData]);

        return Response()->json();
    }

    public function saveScheduleRoutePlan(Request $request){
        $tanggal_schedule = date('ym');

        $id_schedule = $request->get('id_schedule');
        $waktu_schedule = $request->get('waktu_schedule');

        foreach($id_schedule as $nomor) {
            if($waktu_schedule[$nomor] != null || $waktu_schedule[$nomor] != ''){
                DB::table('customers_visit')->where("id_schedule", $nomor)->update(["waktu_schedule" => $waktu_schedule[$nomor], "status" => 5]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 5, 'action' => 'User ' . Session::get('id_user_admin') . ' Ubah Data Customer Visit No. ' . $nomor . ' Sorting dan Insert Waktu Schedule']);
            }
        }

        return Response()->json();
    }

    public function scheduleJadwalFollowUpTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $schedule = DB::table('customers_visit')->select('tanggal_schedule', DB::raw("count(distinct id_schedule) as jumlah_data"))->where(function ($query) { $query->where('status', 3)->orWhere('status', 5); })->where("id_user", Session::get('id_user_admin'))->whereBetween('tanggal_schedule', array($request->from_date, $request->to_date))->groupBy('tanggal_schedule')->get();
            }else{
                $schedule = DB::table('customers_visit')->select('tanggal_schedule', DB::raw("count(distinct id_schedule) as jumlah_data"))->where(function ($query) { $query->where('status', 3)->orWhere('status', 5); })->where("id_user", Session::get('id_user_admin'))->groupBy('tanggal_schedule')->get();
            }

            return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_schedule_jadwal_followup')->rawColumns(['action'])->make(true);
        }
    }

    public function printScheduleJadwalFollowUp($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.address WHEN vis.tipe_customer = 2 THEN lead.address WHEN vis.tipe_customer = 3 THEN komp.address END as alamat"), "vis.perihal", "vis.keterangan", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"), 'vis.waktu_schedule', 'vis.order_sort')->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where(function ($query) { $query->where('vis.status', 3)->orWhere('vis.status', 5); })->where("vis.id_user", Session::get('id_user_admin'))->where('vis.tanggal_schedule', $val_tanggal)->orderBy('vis.order_sort', 'asc')->get();

        $pdf = PDF::loadView('print_jadwal_followup', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function scheduleRoutePlanEditTable(Request $request){
        if(request()->ajax()){
            $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.address WHEN vis.tipe_customer = 2 THEN lead.address WHEN vis.tipe_customer = 3 THEN komp.address END as alamat"), "vis.perihal", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"), 'vis.waktu_schedule', 'vis.order_sort', 'vis.alasan_suspend')->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where(function ($query) { $query->where('vis.status', 3)->orWhere('vis.status', 5); })->where("vis.id_user", Session::get('id_user_admin'))->where('vis.tanggal_schedule', $request->tanggal)->get();

            return datatables()->of($schedule)->make(true);
        }
    }

    public function saveScheduleRoutePlanEdit(Request $request){
        DB::table('customers_visit')->where('id_schedule', $request->id_schedule)->update(['order_sort' => $request->newData]);

        return Response()->json();
    }

    public function editScheduleRoutePlan(Request $request){
        $tanggal_schedule = date('ym');

        $id_schedule = $request->get('id_schedule');
        $suspend = $request->get('suspend');
        $tanggal_schedule = $request->get('edit_jadwal');
        $alasan_suspend = $request->get('alasan_suspend');

        foreach($id_schedule as $nomor) {
            if(is_array($suspend) && array_key_exists($nomor,$suspend)){
                $cek_sort = DB::table('customers_visit')->select('order_sort', 'tanggal_schedule')->where('id_schedule', $nomor)->first();

                DB::table('customers_visit')->where('tanggal_schedule', $cek_sort->tanggal_schedule)->where('order_sort', '>', $cek_sort->order_sort)->update(['order_sort' => DB::raw('order_sort-1')]);

                $cek_data = DB::table('customers_visit')->select('order_sort')->where('tanggal_schedule', date('Y-m-d', strtotime($tanggal_schedule[$nomor])))->where('id_user', Session::get('id_user_admin'))->orderBy('order_sort', 'asc')->distinct()->get();

                if($cek_data->count() > 0){
                    $sort = ++$cek_data[$cek_data->count() - 1]->order_sort;
                }else{
                    $sort = 1;
                }

                DB::table('customers_visit')->where("id_schedule", $nomor)->update(["tanggal_schedule" => $tanggal_schedule[$nomor], "waktu_schedule" => null, "alasan_suspend" => $alasan_suspend[$nomor], "order_sort" => $sort, "status" => 2]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Ubah Data Customer Visit No. ' . $nomor . ' Menjadi Suspend']);
            }
        }

        return Response()->json();
    }

    public function scheduleRealisasiFollowUpTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule as jadwal", "vis.customers as custid", "vis.tipe_customer as tipe", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", "stat.name as status", "vis.status as no_status", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"))->join("status_cust_visit as stat", "stat.id", "=", "vis.status")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where(function ($query) { $query->where('vis.status', '>=', 5)->orWhere('vis.status', 3); })->where("vis.id_user", Session::get('id_user_admin'))->whereBetween('vis.tanggal_schedule', array($request->from_date, $request->to_date))->get();
            }else{
                $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule as jadwal", "vis.customers as custid", "vis.tipe_customer as tipe", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", "stat.name as status", "vis.status as no_status", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"))->join("status_cust_visit as stat", "stat.id", "=", "vis.status")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where(function ($query) { $query->where('vis.status', '>=', 5)->orWhere('vis.status', 3); })->where("vis.id_user", Session::get('id_user_admin'))->orderByRaw("DATE(vis.tanggal_schedule)=DATE(NOW()) DESC, IF(DATE(vis.tanggal_schedule)=DATE(NOW()),vis.tanggal_schedule,DATE(NULL)) DESC, vis.tanggal_schedule DESC")->get();
            }

            return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_schedule_realisasi_followup')->rawColumns(['action'])->make(true);
        }
    }   

    public function scheduleRealisasiFollowUpDetailTable(Request $request){
        if(request()->ajax()){
            $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.address WHEN vis.tipe_customer = 2 THEN lead.address WHEN vis.tipe_customer = 3 THEN komp.address END as alamat"), "vis.perihal", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"), 'vis.waktu_schedule', 'vis.result', 'vis.range_follow_up')->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where(function ($query) { $query->where('vis.status', '>=', 5)->orWhere('vis.status', 3); })->where("vis.id_user", Session::get('id_user_admin'))->where('vis.tanggal_schedule', $request->tanggal)->get();

            return datatables()->of($schedule)->make(true);
        }
    }

    public function saveScheduleRealisasiFollowUp(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal_schedule = date('ym');

        $data_schedule = DB::table('customers_visit_detail')->select('id_schedule_detail')->where('id_schedule_detail', 'like', 'FLWUPDT' . $tanggal_schedule . '%')->orderBy('id_schedule_detail', 'asc')->distinct()->get();

        if($data_schedule){
            $schedule_count = $data_schedule->count();
            if($schedule_count > 0){
                $num = (int) substr($data_schedule[$data_schedule->count() - 1]->id_schedule_detail, 12);
                if($schedule_count != $num){
                    $kode_schedule = ++$data_schedule[$data_schedule->count() - 1]->id_schedule_detail;
                }else{
                    if($schedule_count < 9){
                        $kode_schedule = "FLWUPDT" . $tanggal_schedule . "-00000" . ($schedule_count + 1);
                    }else if($schedule_count >= 9 && $schedule_count < 99){
                        $kode_schedule = "FLWUPDT" . $tanggal_schedule . "-0000" . ($schedule_count + 1);
                    }else if($schedule_count >= 99 && $schedule_count < 999){
                        $kode_schedule = "FLWUPDT" . $tanggal_schedule . "-000" . ($schedule_count + 1);
                    }else if($schedule_count >= 999 && $schedule_count < 9999){
                        $kode_schedule = "FLWUPDT" . $tanggal_schedule . "-00" . ($schedule_count + 1);
                    }else if($schedule_count >= 9999 && $schedule_count < 99999){
                        $kode_schedule = "FLWUPDT" . $tanggal_schedule . "-0" . ($schedule_count + 1);
                    }else{
                        $kode_schedule = "FLWUPDT" . $tanggal_schedule . "-" . ($schedule_count + 1);
                    }
                }
            }else{
                $kode_schedule = "FLWUPDT" . $tanggal_schedule . "-000001";
            }
        }else{
            $kode_schedule = "FLWUPDT" . $tanggal_schedule . "-000001";
        }

        DB::table('customers_visit_detail')->insert(["id_schedule_detail" => $kode_schedule, "id_schedule" => $request->id_schedule_question, "kegiatan" => $request->kegiatan_question]);

        if($request->pic_data_customer != null || $request->pic_data_customer != ''){
            if($request->tipe_customer_question == 1){
                DB::table('customers')->where('custid', $request->custid_question)->update(["nama_cp" => $request->pic_data_customer, "jabatan_cp" => $request->jabatan_data_customer, "bidang_usaha" => $request->bidang_usaha_data_customer, "telepon_cp" => $request->telepon_data_customer]);
            }else if($request->tipe_customer_question == 2){
                DB::table('leads')->where('leadid', $request->custid_question)->update(["nama_cp" => $request->pic_data_customer, "jabatan_cp" => $request->jabatan_data_customer, "bidang_usaha" => $request->bidang_usaha_data_customer, "telepon_cp" => $request->telepon_data_customer]);
            }else if($request->tipe_customer_question == 3){
                DB::table('kompetitor')->where('kompid', $request->custid_question)->update(["nama_cp" => $request->pic_data_customer, "jabatan_cp" => $request->jabatan_data_customer, "bidang_usaha" => $request->bidang_usaha_data_customer, "telepon_cp" => $request->telepon_data_customer]);
            }

            DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["pusat_cabang" => $request->pusat_cabang_data_customer]);
        }

        if($request->kegiatan_question == 1){
            DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["company_profile" => $request->input_company_profile, "pengenalan_produk" => $request->input_pengenalan_produk, "sumber_kenal_dsgm" => $request->sumber_kenal_dsgm, "nomor_surat_pengenalan" => $request->nomor_surat_pengenalan, "nama_janji_visit" => $request->nama_janji_visit, "pic_janji_visit" => $request->pic_janji_visit, "jabatan_janji_visit" => $request->jabatan_janji_visit, "alamat_janji_visit" => $request->alamat_janji_visit, "tanggal_janji_visit" => $request->tanggal_janji_visit, "hasil_visit" => $request->hasil_visit_question]);

            DB::table('customers_visit')->where('id_schedule', $request->id_schedule_question)->update(["status" => 8]);

        }else if($request->kegiatan_question == 2){
            DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["company_profile" => $request->input_company_profile, "pengenalan_produk" => $request->input_pengenalan_produk, "sumber_kenal_dsgm" => $request->sumber_kenal_dsgm, "nomor_surat_pengenalan" => $request->nomor_surat_pengenalan, "hasil_visit" => $request->hasil_visit_question, "bisnis_perusahaan" => $request->bisnis_latar_belakang, "owner_perusahaan" => $request->owner_latar_belakang, "tahun_berdiri_perusahaan" => $request->tahun_berdiri_latar_belakang, "jenis_usaha_perusahaan" => $request->jenis_usaha_latar_belakang, "jangkauan_wilayah_perusahaan" => $request->jangkauan_wilayah_latar_belakang, "top_cust_perusahaan" => $request->top_latar_belakang, "tipe_kalsium" => $request->tipe_penggunaan_kalsium, "qty_kalsium" => $request->qty_penggunaan_kalsium, "kegunaan_kalsium" => $request->kegunaan_penggunaan_kalsium, "merk_kompetitor" => $request->merk_kompetitor_penggunaan_kalsium, "harga_kompetitor" => $request->harga_kompetitor_penggunaan_kalsium, "pengiriman_kalsium" => $request->pengiriman_penggunaan_kalsium, "pembayaran_supplier" => $request->pembayaran_penggunaan_kalsium, "tipe_sample" => $request->tipe_permintaan_sample, "qty_sample" => $request->qty_permintaan_sample, "feedback_uji_sample" => $request->feedback_permintaan_sample, "penawaran_harga" => $request->tawar_penawaran_harga, "nego_harga" => $request->nego_penawaran_harga, "nominal_harga" => $request->nominal_penawaran_harga, "nomor_penawaran" => $request->nomor_penawaran_harga, "pembayaran" => $request->pembayaran_penawaran_harga, "pengiriman" => $request->pengiriman_penawaran_harga, "dokumen_pengiriman" => $request->dokumen_penawaran_harga]);

            if($request->pembayaran_penawaran_harga == 'yes'){
                DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["cash_top" => $request->cash_top_pembayaran]);

                if($request->cash_top_pembayaran == 2){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["tempat_tukar_tt" => $request->tempat_tt_pembayaran, "jadwal_tukar_tt" => $request->jadwal_tt_pembayaran, "jadwal_pembayaran" => $request->jadwal_pembayaran, "metode_pembayaran" => $request->metode_pembayaran, "pic_penagihan" => $request->pic_pembayaran]);
                }
            }

            if($request->tawar_penawaran_harga == 'yes'){
                DB::table('customers_visit')->where('id_schedule', $request->id_schedule_question)->update(["order_yes" => 1]);

                DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_po" => $request->po_dokumen_cust]);

                if($request->ktp_dokumen_cust == 'yes' && $request->hasFile('image_ktp_dokumen_cust')){
                    $file_ktp = $request->file('image_ktp_dokumen_cust');
                    $nama_file_ktp = time()."_KTP_".$request->custid_question."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_ktp" => $request->no_ktp_dokumen_cust, "image_ktp" => $nama_file_ktp]);
                }else if($request->ktp_dokumen_cust == 'yes' && !$request->hasFile('image_ktp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_ktp" => $request->no_ktp_dokumen_cust]);
                }

                if($request->npwp_dokumen_cust == 'yes' && $request->hasFile('image_npwp_dokumen_cust')){
                    $file_npwp = $request->file('image_npwp_dokumen_cust');
                    $nama_file_npwp = time()."_NPWP_".$request->custid_question."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_npwp" => $request->no_npwp_dokumen_cust, "image_npwp" => $nama_file_npwp]);
                }else if($request->npwp_dokumen_cust == 'yes' && !$request->hasFile('image_npwp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_npwp" => $request->no_npwp_dokumen_cust]);
                }

                if($request->siup_dokumen_cust == 'yes' && $request->hasFile('image_siup_dokumen_cust')){
                    $file_siup = $request->file('image_siup_dokumen_cust');
                    $nama_file_siup = time()."_SIUP_".$request->custid_question."_".$file_siup->getClientOriginalName();
                    $tujuan_upload_siup = 'data_file';
                    $file_siup->move($tujuan_upload_siup, $nama_file_siup);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_siup" => $request->no_siup_dokumen_cust, "image_siup" => $nama_file_siup]);
                }else if($request->siup_dokumen_cust == 'yes' && !$request->hasFile('image_siup_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_siup" => $request->no_siup_dokumen_cust]);
                }

                if($request->tdp_dokumen_cust == 'yes' && $request->hasFile('image_tdp_dokumen_cust')){
                    $file_tdp = $request->file('image_tdp_dokumen_cust');
                    $nama_file_tdp = time()."_TDP_".$request->custid_question."_".$file_tdp->getClientOriginalName();
                    $tujuan_upload_tdp = 'data_file';
                    $file_tdp->move($tujuan_upload_tdp, $nama_file_tdp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_tdp" => $request->no_tdp_dokumen_cust, "image_tdp" => $nama_file_tdp]);
                }else if($request->tdp_dokumen_cust == 'yes' && !$request->hasFile('image_tdp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_tdp" => $request->no_tdp_dokumen_cust]);
                }

                $cek_data_poin = DB::table('sales_poin')->select('id_sales')->where('id_sales', Session::get('id_user_admin'))->first();
                if(!$cek_data_poin){
                    DB::table('sales_poin')->insert(["id_sales" => Session::get('id_user_admin'), "approach_poin" => 0, "order_poin" => 0, "deal_poin" => 0, "paid_poin" => 0]);
                }

                $cek_data_penawaran = DB::table('surat_penawaran')->select('nomor_surat_penawaran')->where('nomor_surat_penawaran', $request->nomor_penawaran_harga)->first();

                if($cek_data_penawaran){
                    $data_poin =  DB::table('sales_poin')->select('approach_poin')->where('id_sales', Session::get('id_user_admin'))->first();
                    DB::table('sales_poin')->where("id_sales", Session::get("id_user_admin"))->update(["approach_poin" => $data_poin->approach_poin + 1]);
                }
            }

            DB::table('customers_visit')->where('id_schedule', $request->id_schedule_question)->update(["status" => 3]);

        }else if($request->kegiatan_question == 3){
            DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["company_profile" => $request->input_company_profile, "pengenalan_produk" => $request->input_pengenalan_produk, "sumber_kenal_dsgm" => $request->sumber_kenal_dsgm, "nomor_surat_pengenalan" => $request->nomor_surat_pengenalan, "hasil_visit" => $request->hasil_visit_question, "bisnis_perusahaan" => $request->bisnis_latar_belakang, "owner_perusahaan" => $request->owner_latar_belakang, "tahun_berdiri_perusahaan" => $request->tahun_berdiri_latar_belakang, "jenis_usaha_perusahaan" => $request->jenis_usaha_latar_belakang, "jangkauan_wilayah_perusahaan" => $request->jangkauan_wilayah_latar_belakang, "top_cust_perusahaan" => $request->top_latar_belakang, "tipe_kalsium" => $request->tipe_penggunaan_kalsium, "qty_kalsium" => $request->qty_penggunaan_kalsium, "kegunaan_kalsium" => $request->kegunaan_penggunaan_kalsium, "merk_kompetitor" => $request->merk_kompetitor_penggunaan_kalsium, "harga_kompetitor" => $request->harga_kompetitor_penggunaan_kalsium, "pengiriman_kalsium" => $request->pengiriman_penggunaan_kalsium, "pembayaran_supplier" => $request->pembayaran_penggunaan_kalsium, "tipe_sample" => $request->tipe_permintaan_sample, "qty_sample" => $request->qty_permintaan_sample, "feedback_uji_sample" => $request->feedback_permintaan_sample, "penawaran_harga" => $request->tawar_penawaran_harga, "nego_harga" => $request->nego_penawaran_harga, "nominal_harga" => $request->nominal_penawaran_harga, "nomor_penawaran" => $request->nomor_penawaran_harga, "pembayaran" => $request->pembayaran_penawaran_harga, "pengiriman" => $request->pengiriman_penawaran_harga, "dokumen_pengiriman" => $request->dokumen_penawaran_harga]);

            if($request->pembayaran_penawaran_harga == 'yes'){
                DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["cash_top" => $request->cash_top_pembayaran]);

                if($request->cash_top_pembayaran == 2){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["tempat_tukar_tt" => $request->tempat_tt_pembayaran, "jadwal_tukar_tt" => $request->jadwal_tt_pembayaran, "jadwal_pembayaran" => $request->jadwal_pembayaran, "metode_pembayaran" => $request->metode_pembayaran, "pic_penagihan" => $request->pic_pembayaran]);
                }
            }

            if($request->tawar_penawaran_harga == 'yes'){
                DB::table('customers_visit')->where('id_schedule', $request->id_schedule_question)->update(["order_yes" => 1]);

                DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_po" => $request->po_dokumen_cust]);

                if($request->ktp_dokumen_cust == 'yes' && $request->hasFile('image_ktp_dokumen_cust')){
                    $file_ktp = $request->file('image_ktp_dokumen_cust');
                    $nama_file_ktp = time()."_KTP_".$request->custid_question."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_ktp" => $request->no_ktp_dokumen_cust, "image_ktp" => $nama_file_ktp]);
                }else if($request->ktp_dokumen_cust == 'yes' && !$request->hasFile('image_ktp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_ktp" => $request->no_ktp_dokumen_cust]);
                }

                if($request->npwp_dokumen_cust == 'yes' && $request->hasFile('image_npwp_dokumen_cust')){
                    $file_npwp = $request->file('image_npwp_dokumen_cust');
                    $nama_file_npwp = time()."_NPWP_".$request->custid_question."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_npwp" => $request->no_npwp_dokumen_cust, "image_npwp" => $nama_file_npwp]);
                }else if($request->npwp_dokumen_cust == 'yes' && !$request->hasFile('image_npwp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_npwp" => $request->no_npwp_dokumen_cust]);
                }

                if($request->siup_dokumen_cust == 'yes' && $request->hasFile('image_siup_dokumen_cust')){
                    $file_siup = $request->file('image_siup_dokumen_cust');
                    $nama_file_siup = time()."_SIUP_".$request->custid_question."_".$file_siup->getClientOriginalName();
                    $tujuan_upload_siup = 'data_file';
                    $file_siup->move($tujuan_upload_siup, $nama_file_siup);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_siup" => $request->no_siup_dokumen_cust, "image_siup" => $nama_file_siup]);
                }else if($request->siup_dokumen_cust == 'yes' && !$request->hasFile('image_siup_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_siup" => $request->no_siup_dokumen_cust]);
                }

                if($request->tdp_dokumen_cust == 'yes' && $request->hasFile('image_tdp_dokumen_cust')){
                    $file_tdp = $request->file('image_tdp_dokumen_cust');
                    $nama_file_tdp = time()."_TDP_".$request->custid_question."_".$file_tdp->getClientOriginalName();
                    $tujuan_upload_tdp = 'data_file';
                    $file_tdp->move($tujuan_upload_tdp, $nama_file_tdp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_tdp" => $request->no_tdp_dokumen_cust, "image_tdp" => $nama_file_tdp]);
                }else if($request->tdp_dokumen_cust == 'yes' && !$request->hasFile('image_tdp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_tdp" => $request->no_tdp_dokumen_cust]);
                }

                $cek_data_poin = DB::table('sales_poin')->select('id_sales')->where('id_sales', Session::get('id_user_admin'))->first();
                if(!$cek_data_poin){
                    DB::table('sales_poin')->insert(["id_sales" => Session::get('id_user_admin'), "approach_poin" => 0, "order_poin" => 0, "deal_poin" => 0, "paid_poin" => 0]);
                }

                $cek_data_penawaran = DB::table('surat_penawaran')->select('nomor_surat_penawaran')->where('nomor_surat_penawaran', $request->nomor_penawaran_harga)->first();

                if($cek_data_penawaran){
                    $data_poin =  DB::table('sales_poin')->select('approach_poin')->where('id_sales', Session::get('id_user_admin'))->first();
                    DB::table('sales_poin')->where("id_sales", Session::get("id_user_admin"))->update(["approach_poin" => $data_poin->approach_poin + 1]);
                }
            }

            DB::table('customers_visit')->where('id_schedule', $request->id_schedule_question)->update(["status" => 3]);

        }else if($request->kegiatan_question == 4){
            DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["hasil_visit" => $request->hasil_visit_question, "bisnis_perusahaan" => $request->bisnis_latar_belakang, "owner_perusahaan" => $request->owner_latar_belakang, "tahun_berdiri_perusahaan" => $request->tahun_berdiri_latar_belakang, "jenis_usaha_perusahaan" => $request->jenis_usaha_latar_belakang, "jangkauan_wilayah_perusahaan" => $request->jangkauan_wilayah_latar_belakang, "top_cust_perusahaan" => $request->top_latar_belakang, "tipe_kalsium" => $request->tipe_penggunaan_kalsium, "qty_kalsium" => $request->qty_penggunaan_kalsium, "kegunaan_kalsium" => $request->kegunaan_penggunaan_kalsium, "merk_kompetitor" => $request->merk_kompetitor_penggunaan_kalsium, "harga_kompetitor" => $request->harga_kompetitor_penggunaan_kalsium, "pengiriman_kalsium" => $request->pengiriman_penggunaan_kalsium, "pembayaran_supplier" => $request->pembayaran_penggunaan_kalsium, "tipe_sample" => $request->tipe_permintaan_sample, "qty_sample" => $request->qty_permintaan_sample, "feedback_uji_sample" => $request->feedback_permintaan_sample, "penawaran_harga" => $request->tawar_penawaran_harga, "nego_harga" => $request->nego_penawaran_harga, "nominal_harga" => $request->nominal_penawaran_harga, "nomor_penawaran" => $request->nomor_penawaran_harga, "pembayaran" => $request->pembayaran_penawaran_harga, "pengiriman" => $request->pengiriman_penawaran_harga, "dokumen_pengiriman" => $request->dokumen_penawaran_harga]);

            if($request->pembayaran_penawaran_harga == 'yes'){
                DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["cash_top" => $request->cash_top_pembayaran]);

                if($request->cash_top_pembayaran == 2){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["tempat_tukar_tt" => $request->tempat_tt_pembayaran, "jadwal_tukar_tt" => $request->jadwal_tt_pembayaran, "jadwal_pembayaran" => $request->jadwal_pembayaran, "metode_pembayaran" => $request->metode_pembayaran, "pic_penagihan" => $request->pic_pembayaran]);
                }
            }

            if($request->tawar_penawaran_harga == 'yes'){
                DB::table('customers_visit')->where('id_schedule', $request->id_schedule_question)->update(["order_yes" => 1]);

                DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_po" => $request->po_dokumen_cust]);

                if($request->ktp_dokumen_cust == 'yes' && $request->hasFile('image_ktp_dokumen_cust')){
                    $file_ktp = $request->file('image_ktp_dokumen_cust');
                    $nama_file_ktp = time()."_KTP_".$request->custid_question."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_ktp" => $request->no_ktp_dokumen_cust, "image_ktp" => $nama_file_ktp]);
                }else if($request->ktp_dokumen_cust == 'yes' && !$request->hasFile('image_ktp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_ktp" => $request->no_ktp_dokumen_cust]);
                }

                if($request->npwp_dokumen_cust == 'yes' && $request->hasFile('image_npwp_dokumen_cust')){
                    $file_npwp = $request->file('image_npwp_dokumen_cust');
                    $nama_file_npwp = time()."_NPWP_".$request->custid_question."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_npwp" => $request->no_npwp_dokumen_cust, "image_npwp" => $nama_file_npwp]);
                }else if($request->npwp_dokumen_cust == 'yes' && !$request->hasFile('image_npwp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_npwp" => $request->no_npwp_dokumen_cust]);
                }

                if($request->siup_dokumen_cust == 'yes' && $request->hasFile('image_siup_dokumen_cust')){
                    $file_siup = $request->file('image_siup_dokumen_cust');
                    $nama_file_siup = time()."_SIUP_".$request->custid_question."_".$file_siup->getClientOriginalName();
                    $tujuan_upload_siup = 'data_file';
                    $file_siup->move($tujuan_upload_siup, $nama_file_siup);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_siup" => $request->no_siup_dokumen_cust, "image_siup" => $nama_file_siup]);
                }else if($request->siup_dokumen_cust == 'yes' && !$request->hasFile('image_siup_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_siup" => $request->no_siup_dokumen_cust]);
                }

                if($request->tdp_dokumen_cust == 'yes' && $request->hasFile('image_tdp_dokumen_cust')){
                    $file_tdp = $request->file('image_tdp_dokumen_cust');
                    $nama_file_tdp = time()."_TDP_".$request->custid_question."_".$file_tdp->getClientOriginalName();
                    $tujuan_upload_tdp = 'data_file';
                    $file_tdp->move($tujuan_upload_tdp, $nama_file_tdp);

                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_tdp" => $request->no_tdp_dokumen_cust, "image_tdp" => $nama_file_tdp]);
                }else if($request->tdp_dokumen_cust == 'yes' && !$request->hasFile('image_tdp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule_detail', $kode_schedule)->update(["nomor_tdp" => $request->no_tdp_dokumen_cust]);
                }

                $cek_data_poin = DB::table('sales_poin')->select('id_sales')->where('id_sales', Session::get('id_user_admin'))->first();
                if(!$cek_data_poin){
                    DB::table('sales_poin')->insert(["id_sales" => Session::get('id_user_admin'), "approach_poin" => 0, "order_poin" => 0, "deal_poin" => 0, "paid_poin" => 0]);
                }

                $cek_data_penawaran = DB::table('surat_penawaran')->select('nomor_surat_penawaran')->where('nomor_surat_penawaran', $request->nomor_penawaran_harga)->first();

                if($cek_data_penawaran){
                    $data_poin =  DB::table('sales_poin')->select('approach_poin')->where('id_sales', Session::get('id_user_admin'))->first();
                    DB::table('sales_poin')->where("id_sales", Session::get("id_user_admin"))->update(["approach_poin" => $data_poin->approach_poin + 1]);
                }
            }

            DB::table('customers_visit')->where('id_schedule', $request->id_schedule_question)->update(["status" => 3]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 3, 'action' => 'User ' . Session::get('id_user_admin') . ' Realisasi Case List Data Customer Visit No. ' . $request->id_schedule_question]);

        return Response()->json();
    }

    public function editScheduleRealisasiFollowUp(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if($request->edit_kegiatan_question == 1){
            DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["kegiatan" => $request->edit_kegiatan_question, "company_profile" => $request->edit_company_profile, "pengenalan_produk" => $request->edit_pengenalan_produk, "sumber_kenal_dsgm" => $request->edit_sumber_kenal_dsgm, "nomor_surat_pengenalan" => $request->edit_nomor_surat_pengenalan, "nama_janji_visit" => $request->edit_nama_janji_visit, "pic_janji_visit" => $request->edit_pic_janji_visit, "jabatan_janji_visit" => $request->edit_jabatan_janji_visit, "alamat_janji_visit" => $request->edit_alamat_janji_visit, "tanggal_janji_visit" => $request->edit_tanggal_janji_visit, "hasil_visit" => $request->edit_hasil_visit_question]);
        }else if($request->edit_kegiatan_question == 2){
            DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["kegiatan" => $request->edit_kegiatan_question, "company_profile" => $request->edit_company_profile, "pengenalan_produk" => $request->edit_pengenalan_produk, "sumber_kenal_dsgm" => $request->edit_sumber_kenal_dsgm, "nomor_surat_pengenalan" => $request->edit_nomor_surat_pengenalan, "hasil_visit" => $request->edit_hasil_visit_question, "bisnis_perusahaan" => $request->edit_bisnis_latar_belakang, "owner_perusahaan" => $request->edit_owner_latar_belakang, "tahun_berdiri_perusahaan" => $request->edit_tahun_berdiri_latar_belakang, "jenis_usaha_perusahaan" => $request->edit_jenis_usaha_latar_belakang, "jangkauan_wilayah_perusahaan" => $request->edit_jangkauan_wilayah_latar_belakang, "top_cust_perusahaan" => $request->edit_top_latar_belakang, "tipe_kalsium" => $request->edit_tipe_penggunaan_kalsium, "qty_kalsium" => $request->edit_qty_penggunaan_kalsium, "kegunaan_kalsium" => $request->edit_kegunaan_penggunaan_kalsium, "merk_kompetitor" => $request->edit_merk_kompetitor_penggunaan_kalsium, "harga_kompetitor" => $request->edit_harga_kompetitor_penggunaan_kalsium, "pengiriman_kalsium" => $request->edit_pengiriman_penggunaan_kalsium, "pembayaran_supplier" => $request->edit_pembayaran_penggunaan_kalsium, "tipe_sample" => $request->edit_tipe_permintaan_sample, "qty_sample" => $request->edit_qty_permintaan_sample, "feedback_uji_sample" => $request->edit_feedback_permintaan_sample, "penawaran_harga" => $request->edit_tawar_penawaran_harga, "nego_harga" => $request->edit_nego_penawaran_harga, "nominal_harga" => $request->edit_nominal_penawaran_harga, "nomor_penawaran" => $request->edit_nomor_penawaran_harga, "pembayaran" => $request->edit_pembayaran_penawaran_harga, "pengiriman" => $request->edit_pengiriman_penawaran_harga, "dokumen_pengiriman" => $request->edit_dokumen_penawaran_harga]);

            if($request->edit_pembayaran_penawaran_harga == 'yes'){
                DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["cash_top" => $request->edit_cash_top_pembayaran]);

                if($request->edit_cash_top_pembayaran == 2){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["tempat_tukar_tt" => $request->edit_tempat_tt_pembayaran, "jadwal_tukar_tt" => $request->edit_jadwal_tt_pembayaran, "jadwal_pembayaran" => $request->edit_jadwal_pembayaran, "metode_pembayaran" => $request->edit_metode_pembayaran, "pic_penagihan" => $request->edit_pic_pembayaran]);
                }
            }

            if($request->edit_tawar_penawaran_harga == 'yes'){
                DB::table('customers_visit')->where('id_schedule', $request->edit_id_schedule_question)->update(["order_yes" => 1]);

                DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_po" => $request->edit_po_dokumen_cust]);

                if($request->edit_ktp_dokumen_cust == 'yes' && $request->hasFile('edit_image_ktp_dokumen_cust')){
                    $data_foto_ktp =  DB::table('customers_visit_detail')->select('image_ktp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_ktp->image_ktp);

                    $file_ktp = $request->file('edit_image_ktp_dokumen_cust');
                    $nama_file_ktp = time()."_KTP_".$request->edit_custid_question."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_ktp" => $request->edit_no_ktp_dokumen_cust, "image_ktp" => $nama_file_ktp]);
                }else if($request->edit_ktp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_ktp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_ktp" => $request->edit_no_ktp_dokumen_cust]);
                }

                if($request->edit_npwp_dokumen_cust == 'yes' && $request->hasFile('edit_image_npwp_dokumen_cust')){
                    $data_foto_npwp =  DB::table('customers_visit_detail')->select('image_npwp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_npwp->image_npwp);

                    $file_npwp = $request->file('edit_image_npwp_dokumen_cust');
                    $nama_file_npwp = time()."_NPWP_".$request->edit_custid_question."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_npwp" => $request->edit_no_npwp_dokumen_cust, "image_npwp" => $nama_file_npwp]);
                }else if($request->edit_npwp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_npwp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_npwp" => $request->edit_no_npwp_dokumen_cust]);
                }

                if($request->edit_siup_dokumen_cust == 'yes' && $request->hasFile('edit_image_siup_dokumen_cust')){
                    $data_foto_siup =  DB::table('customers_visit_detail')->select('image_siup')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_siup->image_siup);

                    $file_siup = $request->file('edit_image_siup_dokumen_cust');
                    $nama_file_siup = time()."_SIUP_".$request->edit_custid_question."_".$file_siup->getClientOriginalName();
                    $tujuan_upload_siup = 'data_file';
                    $file_siup->move($tujuan_upload_siup, $nama_file_siup);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_siup" => $request->edit_no_siup_dokumen_cust, "image_siup" => $nama_file_siup]);
                }else if($request->edit_siup_dokumen_cust == 'yes' && !$request->hasFile('edit_image_siup_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_siup" => $request->edit_no_siup_dokumen_cust]);
                }

                if($request->edit_tdp_dokumen_cust == 'yes' && $request->hasFile('edit_image_tdp_dokumen_cust')){
                    $data_foto_tdp =  DB::table('customers_visit_detail')->select('image_tdp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_tdp->image_tdp);

                    $file_tdp = $request->file('edit_image_tdp_dokumen_cust');
                    $nama_file_tdp = time()."_TDP_".$request->edit_custid_question."_".$file_tdp->getClientOriginalName();
                    $tujuan_upload_tdp = 'data_file';
                    $file_tdp->move($tujuan_upload_tdp, $nama_file_tdp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_tdp" => $request->edit_no_tdp_dokumen_cust, "image_tdp" => $nama_file_tdp]);
                }else if($request->edit_tdp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_tdp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_tdp" => $request->edit_no_tdp_dokumen_cust]);
                }
            }
        }else if($request->edit_kegiatan_question == 3){
            DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["kegiatan" => $request->edit_kegiatan_question, "company_profile" => $request->edit_company_profile, "pengenalan_produk" => $request->edit_pengenalan_produk, "sumber_kenal_dsgm" => $request->edit_sumber_kenal_dsgm, "nomor_surat_pengenalan" => $request->edit_nomor_surat_pengenalan, "hasil_visit" => $request->edit_hasil_visit_question, "bisnis_perusahaan" => $request->edit_bisnis_latar_belakang, "owner_perusahaan" => $request->edit_owner_latar_belakang, "tahun_berdiri_perusahaan" => $request->edit_tahun_berdiri_latar_belakang, "jenis_usaha_perusahaan" => $request->edit_jenis_usaha_latar_belakang, "jangkauan_wilayah_perusahaan" => $request->edit_jangkauan_wilayah_latar_belakang, "top_cust_perusahaan" => $request->edit_top_latar_belakang, "tipe_kalsium" => $request->edit_tipe_penggunaan_kalsium, "qty_kalsium" => $request->edit_qty_penggunaan_kalsium, "kegunaan_kalsium" => $request->edit_kegunaan_penggunaan_kalsium, "merk_kompetitor" => $request->edit_merk_kompetitor_penggunaan_kalsium, "harga_kompetitor" => $request->edit_harga_kompetitor_penggunaan_kalsium, "pengiriman_kalsium" => $request->edit_pengiriman_penggunaan_kalsium, "pembayaran_supplier" => $request->edit_pembayaran_penggunaan_kalsium, "tipe_sample" => $request->edit_tipe_permintaan_sample, "qty_sample" => $request->edit_qty_permintaan_sample, "feedback_uji_sample" => $request->edit_feedback_permintaan_sample, "penawaran_harga" => $request->edit_tawar_penawaran_harga, "nego_harga" => $request->edit_nego_penawaran_harga, "nominal_harga" => $request->edit_nominal_penawaran_harga, "nomor_penawaran" => $request->edit_nomor_penawaran_harga, "pembayaran" => $request->edit_pembayaran_penawaran_harga, "pengiriman" => $request->edit_pengiriman_penawaran_harga, "dokumen_pengiriman" => $request->edit_dokumen_penawaran_harga]);

            if($request->edit_pembayaran_penawaran_harga == 'yes'){
                DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["cash_top" => $request->edit_cash_top_pembayaran]);

                if($request->edit_cash_top_pembayaran == 2){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["tempat_tukar_tt" => $request->edit_tempat_tt_pembayaran, "jadwal_tukar_tt" => $request->edit_jadwal_tt_pembayaran, "jadwal_pembayaran" => $request->edit_jadwal_pembayaran, "metode_pembayaran" => $request->edit_metode_pembayaran, "pic_penagihan" => $request->edit_pic_pembayaran]);
                }
            }

            if($request->edit_tawar_penawaran_harga == 'yes'){
                DB::table('customers_visit')->where('id_schedule', $request->edit_id_schedule_question)->update(["order_yes" => 1]);

                DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_po" => $request->edit_po_dokumen_cust]);

                if($request->edit_ktp_dokumen_cust == 'yes' && $request->hasFile('edit_image_ktp_dokumen_cust')){
                    $data_foto_ktp =  DB::table('customers_visit_detail')->select('image_ktp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_ktp->image_ktp);

                    $file_ktp = $request->file('edit_image_ktp_dokumen_cust');
                    $nama_file_ktp = time()."_KTP_".$request->edit_custid_question."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_ktp" => $request->edit_no_ktp_dokumen_cust, "image_ktp" => $nama_file_ktp]);
                }else if($request->edit_ktp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_ktp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_ktp" => $request->edit_no_ktp_dokumen_cust]);
                }

                if($request->edit_npwp_dokumen_cust == 'yes' && $request->hasFile('edit_image_npwp_dokumen_cust')){
                    $data_foto_npwp =  DB::table('customers_visit_detail')->select('image_npwp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_npwp->image_npwp);

                    $file_npwp = $request->file('edit_image_npwp_dokumen_cust');
                    $nama_file_npwp = time()."_NPWP_".$request->edit_custid_question."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_npwp" => $request->edit_no_npwp_dokumen_cust, "image_npwp" => $nama_file_npwp]);
                }else if($request->edit_npwp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_npwp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_npwp" => $request->edit_no_npwp_dokumen_cust]);
                }

                if($request->edit_siup_dokumen_cust == 'yes' && $request->hasFile('edit_image_siup_dokumen_cust')){
                    $data_foto_siup =  DB::table('customers_visit_detail')->select('image_siup')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_siup->image_siup);

                    $file_siup = $request->file('edit_image_siup_dokumen_cust');
                    $nama_file_siup = time()."_SIUP_".$request->edit_custid_question."_".$file_siup->getClientOriginalName();
                    $tujuan_upload_siup = 'data_file';
                    $file_siup->move($tujuan_upload_siup, $nama_file_siup);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_siup" => $request->edit_no_siup_dokumen_cust, "image_siup" => $nama_file_siup]);
                }else if($request->edit_siup_dokumen_cust == 'yes' && !$request->hasFile('edit_image_siup_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_siup" => $request->edit_no_siup_dokumen_cust]);
                }

                if($request->edit_tdp_dokumen_cust == 'yes' && $request->hasFile('edit_image_tdp_dokumen_cust')){
                    $data_foto_tdp =  DB::table('customers_visit_detail')->select('image_tdp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_tdp->image_tdp);

                    $file_tdp = $request->file('edit_image_tdp_dokumen_cust');
                    $nama_file_tdp = time()."_TDP_".$request->edit_custid_question."_".$file_tdp->getClientOriginalName();
                    $tujuan_upload_tdp = 'data_file';
                    $file_tdp->move($tujuan_upload_tdp, $nama_file_tdp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_tdp" => $request->edit_no_tdp_dokumen_cust, "image_tdp" => $nama_file_tdp]);
                }else if($request->edit_tdp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_tdp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_tdp" => $request->edit_no_tdp_dokumen_cust]);
                }
            }
        }else if($request->edit_kegiatan_question == 4){
            DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["kegiatan" => $request->edit_kegiatan_question, "hasil_visit" => $request->edit_hasil_visit_question, "bisnis_perusahaan" => $request->edit_bisnis_latar_belakang, "owner_perusahaan" => $request->edit_owner_latar_belakang, "tahun_berdiri_perusahaan" => $request->edit_tahun_berdiri_latar_belakang, "jenis_usaha_perusahaan" => $request->edit_jenis_usaha_latar_belakang, "jangkauan_wilayah_perusahaan" => $request->edit_jangkauan_wilayah_latar_belakang, "top_cust_perusahaan" => $request->edit_top_latar_belakang, "tipe_kalsium" => $request->edit_tipe_penggunaan_kalsium, "qty_kalsium" => $request->edit_qty_penggunaan_kalsium, "kegunaan_kalsium" => $request->edit_kegunaan_penggunaan_kalsium, "merk_kompetitor" => $request->edit_merk_kompetitor_penggunaan_kalsium, "harga_kompetitor" => $request->edit_harga_kompetitor_penggunaan_kalsium, "pengiriman_kalsium" => $request->edit_pengiriman_penggunaan_kalsium, "pembayaran_supplier" => $request->edit_pembayaran_penggunaan_kalsium, "tipe_sample" => $request->edit_tipe_permintaan_sample, "qty_sample" => $request->edit_qty_permintaan_sample, "feedback_uji_sample" => $request->edit_feedback_permintaan_sample, "penawaran_harga" => $request->edit_tawar_penawaran_harga, "nego_harga" => $request->edit_nego_penawaran_harga, "nominal_harga" => $request->edit_nominal_penawaran_harga, "nomor_penawaran" => $request->edit_nomor_penawaran_harga, "pembayaran" => $request->edit_pembayaran_penawaran_harga, "pengiriman" => $request->edit_pengiriman_penawaran_harga, "dokumen_pengiriman" => $request->edit_dokumen_penawaran_harga]);

            if($request->edit_pembayaran_penawaran_harga == 'yes'){
                DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["cash_top" => $request->edit_cash_top_pembayaran]);

                if($request->edit_cash_top_pembayaran == 2){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["tempat_tukar_tt" => $request->edit_tempat_tt_pembayaran, "jadwal_tukar_tt" => $request->edit_jadwal_tt_pembayaran, "jadwal_pembayaran" => $request->edit_jadwal_pembayaran, "metode_pembayaran" => $request->edit_metode_pembayaran, "pic_penagihan" => $request->edit_pic_pembayaran]);
                }
            }

            if($request->edit_tawar_penawaran_harga == 'yes'){
                DB::table('customers_visit')->where('id_schedule', $request->edit_id_schedule_question)->update(["order_yes" => 1]);

                DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_po" => $request->edit_po_dokumen_cust]);

                if($request->edit_ktp_dokumen_cust == 'yes' && $request->hasFile('edit_image_ktp_dokumen_cust')){
                    $data_foto_ktp =  DB::table('customers_visit_detail')->select('image_ktp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_ktp->image_ktp);

                    $file_ktp = $request->file('edit_image_ktp_dokumen_cust');
                    $nama_file_ktp = time()."_KTP_".$request->edit_custid_question."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_ktp" => $request->edit_no_ktp_dokumen_cust, "image_ktp" => $nama_file_ktp]);
                }else if($request->edit_ktp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_ktp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_ktp" => $request->edit_no_ktp_dokumen_cust]);
                }

                if($request->edit_npwp_dokumen_cust == 'yes' && $request->hasFile('edit_image_npwp_dokumen_cust')){
                    $data_foto_npwp =  DB::table('customers_visit_detail')->select('image_npwp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_npwp->image_npwp);

                    $file_npwp = $request->file('edit_image_npwp_dokumen_cust');
                    $nama_file_npwp = time()."_NPWP_".$request->edit_custid_question."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_npwp" => $request->edit_no_npwp_dokumen_cust, "image_npwp" => $nama_file_npwp]);
                }else if($request->edit_npwp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_npwp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_npwp" => $request->edit_no_npwp_dokumen_cust]);
                }

                if($request->edit_siup_dokumen_cust == 'yes' && $request->hasFile('edit_image_siup_dokumen_cust')){
                    $data_foto_siup =  DB::table('customers_visit_detail')->select('image_siup')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_siup->image_siup);

                    $file_siup = $request->file('edit_image_siup_dokumen_cust');
                    $nama_file_siup = time()."_SIUP_".$request->edit_custid_question."_".$file_siup->getClientOriginalName();
                    $tujuan_upload_siup = 'data_file';
                    $file_siup->move($tujuan_upload_siup, $nama_file_siup);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_siup" => $request->edit_no_siup_dokumen_cust, "image_siup" => $nama_file_siup]);
                }else if($request->edit_siup_dokumen_cust == 'yes' && !$request->hasFile('edit_image_siup_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_siup" => $request->edit_no_siup_dokumen_cust]);
                }

                if($request->edit_tdp_dokumen_cust == 'yes' && $request->hasFile('edit_image_tdp_dokumen_cust')){
                    $data_foto_tdp =  DB::table('customers_visit_detail')->select('image_tdp')->where('id_schedule', $request->edit_id_schedule_question)->first();
                    File::delete('data_file/' . $data_foto_tdp->image_tdp);

                    $file_tdp = $request->file('edit_image_tdp_dokumen_cust');
                    $nama_file_tdp = time()."_TDP_".$request->edit_custid_question."_".$file_tdp->getClientOriginalName();
                    $tujuan_upload_tdp = 'data_file';
                    $file_tdp->move($tujuan_upload_tdp, $nama_file_tdp);

                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_tdp" => $request->edit_no_tdp_dokumen_cust, "image_tdp" => $nama_file_tdp]);
                }else if($request->edit_tdp_dokumen_cust == 'yes' && !$request->hasFile('edit_image_tdp_dokumen_cust')){
                    DB::table('customers_visit_detail')->where('id_schedule', $request->edit_id_schedule_question)->update(["nomor_tdp" => $request->edit_no_tdp_dokumen_cust]);
                }
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 7, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Realisasi Case List Data Customer Visit No. ' . $request->edit_id_schedule_question]);

        return Response()->json();
    }

    public function customersVisitQuotationTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_done", "vis.customers as custid", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.status", "vis.nomor_quotation")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where(function ($query) { $query->where('vis.status', 3)->orWhere('vis.status', '>', 5); })->where('vis.order_yes', 1)->where("vis.id_user", Session::get('id_user_admin'))->whereBetween('vis.tanggal_done', array($request->from_date, $request->to_date))->get();
            }else{
                $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_done", "vis.customers as custid", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.status", "vis.nomor_quotation")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where(function ($query) { $query->where('vis.status', 3)->orWhere('vis.status', '>', 5); })->where('vis.order_yes', 1)->where("vis.id_user", Session::get('id_user_admin'))->orderByRaw("DATE(vis.tanggal_done)=DATE(NOW()) DESC, IF(DATE(vis.tanggal_done)=DATE(NOW()),vis.tanggal_done,DATE(NULL)) DESC, vis.tanggal_done ASC")->get();
            }

            return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_customers_visit_quotation')->rawColumns(['action'])->make(true);
        }
    }

    public function quotationProductsTable(Request $request){
        $orders = DB::table('temp_quotation as tmp')->select("prd.nama_produk as produk", "tmp.quantity as quantity", "tmp.custid as custid", "tmp.kode_produk as kode_produk")->join("products as prd", "prd.kode_produk", "=", "tmp.kode_produk")->where('id_user', Session::get('id_user_admin'))->where('tmp.custid', $request->custid)->get();

        return datatables()->of($orders)->addColumn('action', 'button/action_button_orders')->rawColumns(['action'])->make(true);
    }

    public function saveQuotationAddProductsTable(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_quotation')->insert(["id_user" => Session::get('id_user_admin'), "custid" => $request->quote_custid_prd, "kode_produk" => $request->quote_add_products, "quantity" => $request->quote_add_quantity]);        

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json();
    }

    public function saveQuotationCustomersVisit(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $products = DB::table('temp_quotation')->select('quantity', 'kode_produk')->where('id_user', Session::get('id_user_admin'))->where('custid', $request->get('quote_custid'))->get();

        $data_quotation = ModelQuotation::select('nomor_quotation')->where('nomor_quotation', 'like', 'QUO' . $tanggal . '%')->orderBy('nomor_quotation', 'asc')->distinct()->get();

        if($data_quotation){
            $data_count = $data_quotation->count();
            if($data_count > 0){
                $num = (int) substr($data_quotation[$data_quotation->count() - 1]->nomor_quotation, 8);
                if($data_count != $num){
                    $kode_qoute = ++$data_quotation[$data_quotation->count() - 1]->nomor_quotation;
                }else{
                    if($data_count < 9){
                        $kode_quote = "QUO" . $tanggal . "-000" . ($data_count + 1);
                    }else if($data_count >= 9 && $data_count < 99){
                        $kode_quote = "QUO" . $tanggal . "-00" . ($data_count + 1);
                    }else if($data_count >= 99 && $data_count < 999){
                        $kode_quote = "QUO" . $tanggal . "-0" . ($data_count + 1);
                    }else{
                        $kode_quote = "QUO" . $tanggal . "-" . ($data_count + 1);
                    }
                }
            }else{
                $kode_quote = "QUO" . $tanggal . "-0001";
            }
        }else{
            $kode_quote = "QUO" . $tanggal . "-0001";
        }

        $data =  new ModelQuotation();
        $data->nomor_quotation = $kode_quote;
        $data->id_schedule = $request->get('quote_id_schedule');
        $data->tanggal_quotation = date('Y-m-d');
        $data->custid = $request->get('quote_custid');
        $data->custname_receive = $request->get('quote_nama_cust_receive');
        $data->custalamat_receive = $request->quote_alamat_cust_receive;
        $data->custkota_receive = $request->get('quote_kota_cust_receive');
        $data->phone_receive = $request->get('quote_telepon_cust_receive');
        $data->nomor_po = $request->get('quote_nomor_po');
        $data->status_quotation = 1;
        $data->quotation_created_by = Session::get('id_user_admin');
        $data->keterangan_quotation = $request->quote_keterangan;
        $data->save();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        foreach($products as $prod){
            $data_quotation_prd = ModelQuotationProduk::select('nomor_quotation_produk')->where('nomor_quotation_produk', 'like', 'QUOPRD' . $tanggal . '%')->orderBy('nomor_quotation_produk', 'asc')->distinct()->get();

            if($data_quotation_prd){
                $data_count_prd = $data_quotation_prd->count();
                if($data_count_prd > 0){
                    $num = (int) substr($data_quotation_prd[$data_quotation_prd->count() - 1]->nomor_quotation_produk, 11);
                    if($data_count_prd != $num){
                        $kode_quote_prd = ++$data_quotation_prd[$data_quotation_prd->count() - 1]->nomor_quotation_produk;
                    }else{
                        if($data_count_prd < 9){
                            $kode_quote_prd = "QUOPRD" . $tanggal . "-00000" . ($data_count_prd + 1);
                        }else if($data_count_prd >= 9 && $data_count_prd < 99){
                            $kode_quote_prd = "QUOPRD" . $tanggal . "-0000" . ($data_count_prd + 1);
                        }else if($data_count_prd >= 99 && $data_count_prd < 999){
                            $kode_quote_prd = "QUOPRD" . $tanggal . "-000" . ($data_count_prd + 1);
                        }else if($data_count_prd >= 999 && $data_count_prd < 9999){
                            $kode_quote_prd = "QUOPRD" . $tanggal . "-00" . ($data_count_prd + 1);
                        }else if($data_count_prd >= 9999 && $data_count_prd < 99999){
                            $kode_quote_prd = "QUOPRD" . $tanggal . "-0" . ($data_count_prd + 1);
                        }else{
                            $kode_quote_prd = "QUOPRD" . $tanggal . "-" . ($data_count_prd + 1);
                        }
                    }
                }else{
                    $kode_quote_prd = "QUOPRD" . $tanggal . "-000001";
                }
            }else{
                $kode_quote_prd = "QUOPRD" . $tanggal . "-000001";
            }

            $data_prd = new ModelQuotationProduk();
            $data_prd->nomor_quotation_produk = $kode_quote_prd;
            $data_prd->nomor_quotation = $kode_quote;
            $data_prd->tanggal_kirim = $request->get('quote_tanggal_kirim');
            $data_prd->kode_produk = $prod->kode_produk;
            $data_prd->qty = $prod->quantity;
            $data_prd->status = 1;
            $data_prd->save();
        }

        DB::table('temp_quotation')->where('id_user', Session::get('id_user_admin'))->where('custid', $request->get('quote_custid'))->delete();

        DB::table('customers_visit')->where("id_schedule", $request->get('quote_id_schedule'))->update(["nomor_quotation" => $kode_quote, "status" => 6]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_quotation')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Quotation Nomor ' . $kode_quote]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 6, 'action' => 'User ' . Session::get('id_user_admin') . ' Inpur Data Quotation Customer Visit No. ' . $request->get('quote_id_schedule') . ' Close and Order']);

        return Response()->json($arr);
    }

    public function printQuotationCustomersVisit($nomor_quotation){
        $val_nomor_quotation = Crypt::decrypt($nomor_quotation);

        $data = DB::table('quotation as quote')->select('quote.nomor_quotation', 'quote.tanggal_quotation', 'leads.nama as custname_quotation', 'leads.address as address_quotation', 'quote.custid', 'quote.custname_receive', 'kota.name as kota_receive', 'quote.keterangan_quotation', 'quote.custalamat_receive', 'quote.nomor_po', 'leads.crd')->join('leads', 'leads.leadid', '=', 'quote.custid')->join('kota', 'kota.id_kota', '=', 'quote.custkota_receive')->where('quote.nomor_quotation', $val_nomor_quotation)->get();

        $products = DB::table('quotation_produk as quote')->select('prd.nama_produk', 'prd.kode_produk', 'quote.tanggal_kirim', 'quote.qty')->join('products as prd', 'prd.kode_produk', '=', 'quote.kode_produk')->where('quote.nomor_quotation', $val_nomor_quotation)->get();

        $pdf = PDF::loadView('print_form_quotation', ['data' => $data, 'products' => $products, 'data_count' => $data->count()])->setPaper('a5', 'portrait');
        return $pdf->stream();
        // return view('print_surat_pesanan');
    }

    public function customersVisitQuotationValidasiTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $quotation = DB::table('quotation as quote')->select("quote.nomor_quotation", "quote.tanggal_quotation", "quote.custid", "lead.nama as custname_order")->leftJoin("leads as lead", "lead.leadid", "=", "quote.custid")->where('quote.status_quotation', 1)->whereBetween('quote.tanggal_quotation', array($request->from_date, $request->to_date))->get();
            }else{
                $quotation = DB::table('quotation as quote')->select("quote.nomor_quotation", "quote.tanggal_quotation", "quote.custid", "lead.nama as custname_order")->leftJoin("leads as lead", "lead.leadid", "=", "quote.custid")->where('quote.status_quotation', 1)->get();
            }

            return datatables()->of($quotation)->addIndexColumn()->addColumn('action', 'button/action_button_customers_visit_quotation_validasi')->rawColumns(['action'])->make(true);
        }
    }

    public function customersVisitQuotationValidTable(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');
        $tanggal_history = date('Y');

        $data_valid = DB::table('quotation')->where('nomor_quotation', $request->get('nomor_quotation'))->update(["status_quotation" => 2]);  

        $data_quotation = DB::table('quotation')->where('nomor_quotation', $request->get('nomor_quotation'))->first();

        $data_quotation_products = DB::table('quotation_produk')->where('nomor_quotation', $request->get('nomor_quotation'))->get();

        $data_leads = DB::table('leads')->where('leadid', $data_quotation->custid)->first();

        $data_kota = ModelKota::where('id_kota', $data_leads->city)->first();
        $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
        $nama_user = strtoupper(str_replace(' ', '', $data_leads->nama));
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
        $data_cust->id_sales = $data_leads->id_sales;
        $data_cust->custname = $data_leads->nama;
        $data_cust->company = $data_leads->company;
        $data_cust->address = $data_leads->address;
        $data_cust->city = $data_leads->city;
        $data_cust->wraddress = $data_leads->wraddress;
        $data_cust->crd = $data_leads->crd;
        $data_cust->phone = $data_leads->phone;
        $data_cust->fax = $data_leads->fax;
        $data_cust->npwp = $data_leads->npwp;
        $data_cust->image_npwp = $data_leads->image_npwp;
        $data_cust->nik = $data_leads->nik;
        $data_cust->image_nik = $data_leads->image_nik;
        $data_cust->nama_cp = $data_leads->nama_cp;
        $data_cust->jabatan_cp = $data_leads->jabatan_cp;
        $data_cust->bidang_usaha = $data_leads->bidang_usaha;
        $data_cust->created_by = Session::get('id_user_admin');
        $data_cust->updated_by = Session::get('id_user_admin');
        $data_cust->save();

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive')->where('id_alamat_receive', 'like', 'HA' . $tanggal_history . '%')->orderBy('id_alamat_receive', 'asc')->distinct()->get();

        if($data_alamat){
            $alamat_count = $data_alamat->count();
            if($alamat_count > 0){
                $num = (int) substr($data_alamat[$data_alamat->count() - 1]->id_alamat_receive, 7);
                if($alamat_count != $num){
                    $kode_alamat = ++$data_alamat[$data_alamat->count() - 1]->id_alamat_receive;
                }else{
                    if($alamat_count < 9){
                        $kode_alamat = "HA" . $tanggal_history . "-00000" . ($alamat_count + 1);
                    }else if($alamat_count >= 9 && $alamat_count < 99){
                        $kode_alamat = "HA" . $tanggal_history . "-0000" . ($alamat_count + 1);
                    }else if($alamat_count >= 99 && $alamat_count < 999){
                        $kode_alamat = "HA" . $tanggal_history . "-000" . ($alamat_count + 1);
                    }else if($alamat_count >= 999 && $alamat_count < 9999){
                        $kode_alamat = "HA" . $tanggal_history . "-00" . ($alamat_count + 1);
                    }else if($alamat_count >= 9999 && $alamat_count < 99999){
                        $kode_alamat = "HA" . $tanggal_history . "-0" . ($alamat_count + 1);
                    }else{
                        $kode_alamat = "HA-" . $tanggal_history . ($alamat_count + 1);
                    }
                }
            }else{
                $kode_alamat = "HA" . $tanggal_history . "-000001";
            }
        }else{
            $kode_alamat = "HA" . $tanggal_history . "-000001";
        }

        $alamat =  new ModelAlamatHistory();
        $alamat->id_alamat_receive = $kode_alamat;
        $alamat->custid_order = $kode_cust;
        $alamat->custname_receive = $data_quotation->custname_receive;
        $alamat->address_receive = $data_quotation->custalamat_receive;
        $alamat->city_receive = $data_quotation->custkota_receive;
        $alamat->phone_receive = $data_quotation->phone_receive;
        $alamat->main_address = 1;
        $alamat->choosen = 1;
        $alamat->save();

        $data_orders = ModelOrders::select('nomor_order_receipt')->where('nomor_order_receipt', 'like', 'ORD' . $tanggal . '%')->orderBy('nomor_order_receipt', 'asc')->distinct()->get();

        if($data_orders){
            $data_count = $data_orders->count();
            if($data_count > 0){
                $num = (int) substr($data_orders[$data_orders->count() - 1]->nomor_order_receipt, 8);
                if($data_count != $num){
                    $kode_ord = ++$data_orders[$data_orders->count() - 1]->nomor_order_receipt;
                }else{
                    if($data_count < 9){
                        $kode_ord = "ORD" . $tanggal . "-000" . ($data_count + 1);
                    }else if($data_count >= 9 && $data_count < 99){
                        $kode_ord = "ORD" . $tanggal . "-00" . ($data_count + 1);
                    }else if($data_count >= 99 && $data_count < 999){
                        $kode_ord = "ORD" . $tanggal . "-0" . ($data_count + 1);
                    }else{
                        $kode_ord = "ORD" . $tanggal . "-" . ($data_count + 1);
                    }
                }
            }else{
                $kode_ord = "ORD" . $tanggal . "-0001";
            }
        }else{
            $kode_ord = "ORD" . $tanggal . "-0001";
        }

        $data =  new ModelOrders();
        $data->nomor_order_receipt = $kode_ord;
        $data->tanggal_order = $data_quotation->tanggal_quotation;
        $data->custid = $kode_cust;
        $data->nomor_po = $data_quotation->nomor_po;
        $data->status_order = 1;
        $data->order_created_by = $data_quotation->quotation_created_by;
        $data->keterangan_order = $data_quotation->keterangan_quotation;
        $data->save();

        $dataprd_orders = ModelProductionOrder::select('nomor_production_order')->where('nomor_production_order', 'like', 'PRORD' . $tanggal . '%')->orderBy('nomor_production_order', 'asc')->distinct()->get();

        if($dataprd_orders){
            $dataprdo_count = $dataprd_orders->count();
            if($dataprdo_count > 0){
                $num = (int) substr($dataprd_orders[$dataprd_orders->count() - 1]->nomor_production_order, 10);
                if($dataprdo_count != $num){
                    $kode_po = ++$dataprd_orders[$dataprd_orders->count() - 1]->nomor_production_order;
                }else{
                    if($dataprdo_count < 9){
                        $kode_po = "PRORD" . $tanggal . "-00000" . ($dataprdo_count + 1);
                    }else if($dataprdo_count >= 9 && $dataprdo_count < 99){
                        $kode_po = "PRORD" . $tanggal . "-0000" . ($dataprdo_count + 1);
                    }else if($dataprdo_count >= 99 && $dataprdo_count < 999){
                        $kode_po = "PRORD" . $tanggal . "-000" . ($dataprdo_count + 1);
                    }else if($dataprdo_count >= 999 && $dataprdo_count < 9999){
                        $kode_po = "PRORD" . $tanggal . "-00" . ($dataprdo_count + 1);
                    }else if($dataprdo_count >= 9999 && $dataprdo_count < 99999){
                        $kode_po = "PRORD" . $tanggal . "-0" . ($dataprdo_count + 1);
                    }else{
                        $kode_po = "PRORD" . $tanggal . "-" . ($dataprdo_count + 1);
                    }
                }
            }else{
                $kode_po = "PRORD" . $tanggal . "-000001"; 
            }
        }else{
            $kode_po = "PRORD" . $tanggal . "-000001";
        }

        $data_po =  new ModelProductionOrder();
        $data_po->nomor_production_order = $kode_po;
        $data_po->nomor_order_receipt = $kode_ord;
        $data_po->nomor_po = $data_quotation->nomor_po;
        $data_po->custid = $kode_cust;
        $data_po->tanggal_order = $data_quotation->tanggal_quotation;
        $data_po->dropdown_rencana_produksi = 1;
        $data_po->save();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        foreach($data_quotation_products as $prod){
            $data_orders_prd = ModelOrdersProduk::select('nomor_order_receipt_produk')->where('nomor_order_receipt_produk', 'like', 'ORPRD' . $tanggal . '%')->orderBy('nomor_order_receipt_produk', 'asc')->distinct()->get();

            if($data_orders_prd){
                $data_count_prd = $data_orders_prd->count();
                if($data_count_prd > 0){
                    $num = (int) substr($data_orders_prd[$data_orders_prd->count() - 1]->nomor_order_receipt_produk, 10);
                    if($data_count_prd != $num){
                        $kode_ord_prd = ++$data_orders_prd[$data_orders_prd->count() - 1]->nomor_order_receipt_produk;
                    }else{
                        if($data_count_prd < 9){
                            $kode_ord_prd = "ORPRD" . $tanggal . "-00000" . ($data_count_prd + 1);
                        }else if($data_count_prd >= 9 && $data_count_prd < 99){
                            $kode_ord_prd = "ORPRD" . $tanggal . "-0000" . ($data_count_prd + 1);
                        }else if($data_count_prd >= 99 && $data_count_prd < 999){
                            $kode_ord_prd = "ORPRD" . $tanggal . "-000" . ($data_count_prd + 1);
                        }else if($data_count_prd >= 999 && $data_count_prd < 9999){
                            $kode_ord_prd = "ORPRD" . $tanggal . "-00" . ($data_count_prd + 1);
                        }else if($data_count_prd >= 9999 && $data_count_prd < 99999){
                            $kode_ord_prd = "ORPRD" . $tanggal . "-0" . ($data_count_prd + 1);
                        }else{
                            $kode_ord_prd = "ORPRD" . $tanggal . "-" . ($data_count_prd + 1);
                        }
                    }
                }else{
                    $kode_ord_prd = "ORPRD" . $tanggal . "-000001";
                }
            }else{
                $kode_ord_prd = "ORPRD" . $tanggal . "-000001";
            }

            $data_prd = new ModelOrdersProduk();
            $data_prd->nomor_order_receipt_produk = $kode_ord_prd;
            $data_prd->nomor_order_receipt = $kode_ord;
            $data_prd->id_alamat = $kode_alamat;
            $data_prd->custname_receive = $data_quotation->custname_receive;
            $data_prd->custalamat_receive = $data_quotation->custalamat_receive;
            $data_prd->custkota_receive = $data_quotation->custkota_receive;
            $data_prd->phone_receive = $data_quotation->phone_receive;
            $data_prd->tanggal_kirim = $data_quotation->tanggal_quotation;
            $data_prd->kode_produk = $prod->kode_produk;
            $data_prd->qty = $prod->qty;
            $data_prd->status = 1;
            $data_prd->save();

            $dataprd_orders_dt = ModelProductionOrderDetail::select('nomor_production_order_detail')->where('nomor_production_order_detail', 'like', 'PRORDT' . $tanggal . '%')->orderBy('nomor_production_order_detail', 'asc')->distinct()->get();

            if($dataprd_orders_dt){
                $dataprdo_dt_count = $dataprd_orders_dt->count();
                if($dataprdo_dt_count > 0){
                    $num = (int) substr($dataprd_orders_dt[$dataprd_orders_dt->count() - 1]->nomor_production_order_detail, 11);
                    if($dataprdo_dt_count != $num){
                        $kode_po_dt = ++$dataprd_orders_dt[$dataprd_orders_dt->count() - 1]->nomor_production_order_detail;
                    }else{
                        if($dataprdo_dt_count < 9){
                            $kode_po_dt = "PRORDT" . $tanggal . "-00000" . ($dataprdo_dt_count + 1);
                        }else if($dataprdo_dt_count >= 9 && $dataprdo_dt_count < 99){
                            $kode_po_dt = "PRORDT" . $tanggal . "-0000" . ($dataprdo_dt_count + 1);
                        }else if($dataprdo_dt_count >= 99 && $dataprdo_dt_count < 999){
                            $kode_po_dt = "PRORDT" . $tanggal . "-000" . ($dataprdo_dt_count + 1);
                        }else if($dataprdo_dt_count >= 999 && $dataprdo_dt_count < 9999){
                            $kode_po_dt = "PRORDT" . $tanggal . "-00" . ($dataprdo_dt_count + 1);
                        }else if($dataprdo_dt_count >= 9999 && $dataprdo_dt_count < 99999){
                            $kode_po_dt = "PRORDT" . $tanggal . "-0" . ($dataprdo_dt_count + 1);
                        }else{
                            $kode_po_dt = "PRORDT" . $tanggal . "-" . ($dataprdo_dt_count + 1);
                        }
                    }
                }else{
                    $kode_po_dt = "PRORDT" . $tanggal . "-000001";
                }
            }else{
                $kode_po_dt = "PRORDT" . $tanggal . "-000001";
            }

            $data_podt =  new ModelProductionOrderDetail();
            $data_podt->nomor_production_order_detail = $kode_po_dt;
            $data_podt->nomor_production_order = $kode_po;
            $data_podt->tanggal_kirim = $data_quotation->tanggal_quotation;
            $data_podt->kode_produk = $prod->kode_produk;
            $data_podt->qty = $prod->qty;
            $data_podt->save();
            

            $history = new ModelHistoryOrders();
            $history->nomor_order = $kode_ord;
            $history->nomor_po = $data_quotation->nomor_po;
            $history->custid = $kode_cust;
            $history->alamat_receive_history = $kode_alamat;
            $history->kode_produk = $prod->kode_produk;
            $history->quantity = $prod->qty;
            $history->save();
        }

        $data_poin =  DB::table('sales_poin')->select('approach_poin', 'order_poin')->where('id_sales', Session::get('id_user_admin'))->first();
        DB::table('sales_poin')->where("id_sales", Session::get("id_user_admin"))->update(["approach_poin" => $data_poin->approach_poin - 1, "order_poin" => $data_poin->order_poin + 1]);

        DB::table('temp_quotation')->where('id_user', Session::get('id_user_admin'))->where('custid', $data_quotation->custid)->delete();

        DB::table('leads')->where('leadid', $data_quotation->custid)->update(['status' => 2]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Convert dari Data Leads']);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Order From Quotation Nomor ' . $kode_ord]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_quotation')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Validasi Quotation Nomor ' . $request->get('nomor_quotation')]);      

        if($data_valid){
            $arr = array('msg' => 'Data Successfully Validated', 'status' => true);
        }

        return Response()->json();
    }

    public function customersVisitQuotationRejectTable(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data_valid = DB::table('quotation')->where('nomor_quotation', $request->get('nomor_quotation'))->update(["status_quotation" => 3]);  

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_quotation')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 3, 'action' => 'User ' . Session::get('id_user_admin') . ' Reject Quotation Nomor ' . $request->get('nomor_quotation')]);      

        if($data_valid){
            $arr = array('msg' => 'Data Successfully Rejected', 'status' => true);
        }

        return Response()->json();
    }

    public function schedulePoinDeliveryTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $schedule = DB::table('customers_visit as vis')->select('vis.tanggal_schedule', DB::raw("count(distinct vis.id_schedule) as jumlah_data"))->join('quotation as quo', 'quo.id_schedule', '=', 'vis.id_schedule')->where('vis.status', 6)->where('quo.status_quotation', 2)->where("vis.id_user", Session::get('id_user_admin'))->whereBetween('vis.tanggal_schedule', array($request->from_date, $request->to_date))->groupBy('vis.tanggal_schedule')->get();
            }else{
                $schedule = DB::table('customers_visit as vis')->select('vis.tanggal_schedule', DB::raw("count(distinct vis.id_schedule) as jumlah_data"))->join('quotation as quo', 'quo.id_schedule', '=', 'vis.id_schedule')->where('vis.status', 6)->where('quo.status_quotation', 2)->where("vis.id_user", Session::get('id_user_admin'))->groupBy('vis.tanggal_schedule')->get();
            }

            return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_schedule_poin_delivery')->rawColumns(['action'])->make(true);
        }
    }

    public function schedulePoinDeliveryDetailTable(Request $request){
        if(request()->ajax()){
            $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"))->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->leftJoin('quotation as quo', 'quo.id_schedule', '=', 'vis.id_schedule')->where('vis.status', 6)->where('quo.status_quotation', 2)->where("vis.id_user", Session::get('id_user_admin'))->where('vis.tanggal_schedule', $request->tanggal)->get();

            return datatables()->of($schedule)->addIndexColumn()->make(true);
        }
    }

    public function saveSchedulePoinDelivery(Request $request){
        $tanggal_schedule = date('ym');

        $id_schedule = $request->get('id_schedule');
        $delivery = $request->get('delivery');

        foreach($id_schedule as $nomor) {
            if(is_array($delivery) && array_key_exists($nomor,$delivery)){
                DB::table('customers_visit')->where("id_schedule", $nomor)->update(["tanggal_poin_delivery" => date('Y-m-d'), "status" => 8]);

                $data_poin =  DB::table('sales_poin')->select('order_poin', 'deal_poin')->where('id_sales', Session::get('id_user_admin'))->first();
                DB::table('sales_poin')->where("id_sales", Session::get("id_user_admin"))->update(["order_poin" => $data_poin->order_poin - 1, "deal_poin" => $data_poin->deal_poin + 1]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 8, 'action' => 'User ' . Session::get('id_user_admin') . ' Tambah Poin Delivery Customer Visit No. ' . $nomor]);
            }
        }

        return Response()->json();
    }

    public function schedulePoinPaidTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $schedule = DB::table('customers_visit')->select('tanggal_schedule', DB::raw("count(distinct id_schedule) as jumlah_data"))->where('status', 8)->where("id_user", Session::get('id_user_admin'))->whereBetween('tanggal_schedule', array($request->from_date, $request->to_date))->groupBy('tanggal_schedule')->get();
            }else{
                $schedule = DB::table('customers_visit')->select('tanggal_schedule', DB::raw("count(distinct id_schedule) as jumlah_data"))->where('status', 8)->where("id_user", Session::get('id_user_admin'))->groupBy('tanggal_schedule')->get();
            }

            return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_schedule_poin_paid')->rawColumns(['action'])->make(true);
        }
    }

    public function schedulePoinPaidDetailTable(Request $request){
        if(request()->ajax()){
            $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"))->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where('vis.status', 8)->where("vis.id_user", Session::get('id_user_admin'))->where('vis.tanggal_schedule', $request->tanggal)->get();

            return datatables()->of($schedule)->addIndexColumn()->make(true);
        }
    }

    public function saveSchedulePoinPaid(Request $request){
        $tanggal_schedule = date('ym');

        $id_schedule = $request->get('id_schedule');
        $paid = $request->get('paid');

        foreach($id_schedule as $nomor) {
            if(is_array($paid) && array_key_exists($nomor,$paid)){
                DB::table('customers_visit')->where("id_schedule", $nomor)->update(["tanggal_poin_paid" => date('Y-m-d'), "status" => 9]);

                $data_poin =  DB::table('sales_poin')->select('deal_poin', 'paid_poin')->where('id_sales', Session::get('id_user_admin'))->first();
                DB::table('sales_poin')->where("id_sales", Session::get("id_user_admin"))->update(["deal_poin" => $data_poin->deal_poin - 1, "paid_poin" => $data_poin->paid_poin + 1]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 9, 'action' => 'User ' . Session::get('id_user_admin') . ' Tambah Poin Paid Customer Visit No. ' . $nomor]);
            }
        }

        return Response()->json();
    }

    public function scheduleDoneTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"))->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where('vis.status', 9)->where("vis.id_user", Session::get('id_user_admin'))->whereBetween('tanggal_schedule', array($request->from_date, $request->to_date))->get();
            }else{
                $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", DB::raw("CASE WHEN vis.offline = 1 THEN 'Ya' WHEN vis.offline = 0 THEN 'Tidak' END as offline"))->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where('vis.status', 9)->where("vis.id_user", Session::get('id_user_admin'))->get();
            }

            return datatables()->of($schedule)->addIndexColumn()->addColumn('action', 'button/action_button_schedule_done')->rawColumns(['action'])->make(true);
        }
    }

    public function printScheduleDone($id_schedule){
        $val_id_schedule = Crypt::decrypt($id_schedule);

        $data = DB::table('customers_visit as vis')->select('vis.id_schedule', 'vis.keterangan', 'vis.alasan_suspend', 'vis.nomor_quotation', 'vis.tanggal_input', 'vis.tanggal_schedule', 'vis.waktu_schedule', 'vis.tanggal_poin_delivery', 'vis.tanggal_poin_paid', DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as customer"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", DB::raw("CASE WHEN vis.offline = 1 THEN 'Offine' WHEN vis.offline = 0 THEN 'Online' END as offline"), DB::raw("CASE WHEN vis.order_yes = 1 THEN 'Ya' WHEN vis.order_yes = 0 THEN 'Tidak' END as order_yes"))->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->leftJoin("company as com", "com.kode_perusahaan", "=", "vis.company")->where('vis.id_schedule', $val_id_schedule)->first();

        $detail = DB::table('customers_visit_detail as det')->select("det.id_schedule_detail", "det.id_schedule", "keg.name as kegiatan", "det.kegiatan as no_kegiatan", "det.pusat_cabang", "det.company_profile", "det.pengenalan_produk", "det.sumber_kenal_dsgm", "det.nomor_surat_pengenalan", "det.nama_janji_visit", "det.pic_janji_visit", "det.jabatan_janji_visit", "det.alamat_janji_visit", "det.tanggal_janji_visit", "det.bisnis_perusahaan", "det.owner_perusahaan", "det.tahun_berdiri_perusahaan", "det.jenis_usaha_perusahaan", "det.jangkauan_wilayah_perusahaan", "det.top_cust_perusahaan", "det.tipe_kalsium", "det.qty_kalsium", "det.kegunaan_kalsium", "det.merk_kompetitor", "det.harga_kompetitor", "det.pengiriman_kalsium", "sup.name as pembayaran_supplier", "det.pembayaran_supplier as no_pembayaran_supplier", "det.tipe_sample", "det.qty_sample", "det.feedback_uji_sample", "det.penawaran_harga", "det.nego_harga", "det.nominal_harga", "det.nomor_penawaran", "det.pembayaran", "det.pengiriman", "det.dokumen_pengiriman", "bayar.name as cash_top", "det.cash_top as no_cash_top", "det.tempat_tukar_tt", "det.jadwal_tukar_tt", "det.jadwal_pembayaran", "det.metode_pembayaran", "det.pic_penagihan", "det.nomor_po", "det.nomor_ktp", "det.nomor_npwp", "det.nomor_siup", "det.nomor_tdp", "det.image_ktp", "det.image_npwp", "det.image_siup", "det.image_tdp", "det.hasil_visit")->leftJoin("tbl_kegiatan_visit as keg", "keg.id", "=", "det.kegiatan")->leftJoin("tbl_pembayaran_supplier as sup", "sup.id", "=", "det.pembayaran_supplier")->leftJoin("tbl_pembayaran_supplier as bayar", "bayar.id", "=", "det.cash_top")->where("det.id_schedule", $val_id_schedule)->first();

        $pdf = PDF::loadView('print_form_customers_visit', ['data' => $data, 'detail' => $detail])->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function viewDetailCustomersScheduleSales($custid, $tipe){
        $val_custid = $this->decrypt($custid);
        $val_tipe = $this->decrypt($tipe);

        if($val_tipe == 1){
            $customers = DB::table('customers')->select("nama_cp", "jabatan_cp", "bidang_usaha")->where("custid", $val_custid)->first();
        }else if($val_tipe == 2){
            $customers = DB::table('leads')->select("nama_cp", "jabatan_cp", "bidang_usaha")->where("leadid", $val_custid)->first();
        }else if($val_tipe == 3){
            $customers = DB::table('kompetitor')->select("nama_cp", "jabatan_cp", "bidang_usaha")->where("kompid", $val_custid)->first();
        }
        
        return Response()->json($customers);
    }

    public function viewDetailLeadsScheduleSales($leadid){
        $val_leadid = $this->decrypt($leadid);

        $leads = DB::table('leads')->select("nama_cp", "jabatan_cp", "bidang_usaha")->where("leadid", $val_leadid)->first();
        
        return Response()->json($leads);
    }

    public function viewDetailKompetitorScheduleSales($kompid){
        $val_kompid = $this->decrypt($kompid);

        $kompetitor = DB::table('kompetitor')->select("nama_cp", "jabatan_cp", "bidang_usaha")->where("kompid", $val_kompid)->first();
        
        return Response()->json($kompetitor);
    }

    public function viewDetailScheduleSales($id_schedule){
        $val_id_schedule = $this->decrypt($id_schedule);

        $schedule = DB::table('customers_visit as vis')->select("vis.id_schedule", "vis.tanggal_schedule", "vis.tipe_customer as no_tipe_customers", DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custname WHEN vis.tipe_customer = 2 THEN lead.nama WHEN vis.tipe_customer = 3 THEN komp.nama END as nama"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN cus.custid WHEN vis.tipe_customer = 2 THEN lead.leadid WHEN vis.tipe_customer = 3 THEN komp.kompid END as customers"), DB::raw("CASE WHEN vis.tipe_customer = 1 THEN 'Customers' WHEN vis.tipe_customer = 2 THEN 'Leads' WHEN vis.tipe_customer = 3 THEN 'Kompetitor' END as tipe_customers"), "vis.perihal", "vis.keterangan", "vis.offline", "vis.company", "vis.tanggal_input", "vis.tanggal_done", "stat.name as status", "vis.alasan_suspend", "vis.status as no_status", "vis.waktu_schedule")->join("status_cust_visit as stat", "stat.id", "=", "vis.status")->leftJoin("customers as cus", "cus.custid", "=", "vis.customers")->leftJoin("leads as lead", "lead.leadid", "=", "vis.customers")->leftJoin("kompetitor as komp", "komp.kompid", "=", "vis.customers")->where("vis.id_schedule", $val_id_schedule)->first();
        
        return Response()->json($schedule);
    }

    public function viewDetailQuestionSales($id_schedule){
        $val_id_schedule = $this->decrypt($id_schedule);

        $schedule = DB::table('customers_visit_detail as det')->select("det.id_schedule_detail", "det.id_schedule", "keg.name as kegiatan", "det.kegiatan as no_kegiatan", "det.pusat_cabang", "det.company_profile", "det.pengenalan_produk", "det.sumber_kenal_dsgm", "det.nomor_surat_pengenalan", "det.nama_janji_visit", "det.pic_janji_visit", "det.jabatan_janji_visit", "det.alamat_janji_visit", "det.tanggal_janji_visit", "det.bisnis_perusahaan", "det.owner_perusahaan", "det.tahun_berdiri_perusahaan", "det.jenis_usaha_perusahaan", "det.jangkauan_wilayah_perusahaan", "det.top_cust_perusahaan", "det.tipe_kalsium", "det.qty_kalsium", "det.kegunaan_kalsium", "det.merk_kompetitor", "det.harga_kompetitor", "det.pengiriman_kalsium", "sup.name as pembayaran_supplier", "det.pembayaran_supplier as no_pembayaran_supplier", "det.tipe_sample", "det.qty_sample", "det.feedback_uji_sample", "det.penawaran_harga", "det.nego_harga", "det.nominal_harga", "det.nomor_penawaran", "det.pembayaran", "det.pengiriman", "det.dokumen_pengiriman", "bayar.name as cash_top", "det.cash_top as no_cash_top", "det.tempat_tukar_tt", "det.jadwal_tukar_tt", "det.jadwal_pembayaran", "det.metode_pembayaran", "det.pic_penagihan", "det.nomor_po", "det.nomor_ktp", "det.nomor_npwp", "det.nomor_siup", "det.nomor_tdp", "det.image_ktp", "det.image_npwp", "det.image_siup", "det.image_tdp", "det.hasil_visit")->leftJoin("tbl_kegiatan_visit as keg", "keg.id", "=", "det.kegiatan")->leftJoin("tbl_pembayaran_supplier as sup", "sup.id", "=", "det.pembayaran_supplier")->leftJoin("tbl_pembayaran_supplier as bayar", "bayar.id", "=", "det.cash_top")->where("det.id_schedule", $val_id_schedule)->first();
        
        return Response()->json($schedule);
    }

    public function excelLaporanBulananCustomerVisit($from_date, $to_date){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        return Excel::download(new CustomersFollowUpExport($val_from_date, $val_to_date), 'Data Customers Follow Up.xlsx');
    }

    public function historyCustomer(Request $request){
        if(request()->ajax()){
            $history_order = DB::table('order_receipt')->select("tanggal_order as tanggal", DB::raw("'Order' as history"), "nomor_order_receipt as nomor", DB::raw("'1' as kode_history"))->where('custid', $request->custid);
            $history_complaint = DB::table('complaint_customer')->select("tanggal_complaint as tanggal", DB::raw("'Complaint' as history"), "nomor_complaint as nomor", DB::raw("'6' as kode_history"))->where('custid', $request->custid);
            $history = DB::table('follow_up_customer')->select("tanggal", DB::raw("'Follow Up' as history"), "nomor_followup as nomor", DB::raw("'11' as kode_history"))->where('custid', $request->custid)->union($history_order)->union($history_complaint)->get();

            return datatables()->of($history)->make(true);
        }
    }  

    public function historySales(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $history_order = DB::table('order_receipt')->select("tanggal_order as tanggal", DB::raw("'Create Order' as history"), "nomor_order_receipt as nomor", DB::raw("'1' as kode_history"))->where('order_created_by', $request->id_user_admin)->whereBetween('tanggal_order', array($request->from_date, $request->to_date))->orderBy("tanggal_order", "DESC");
                $history_customer = DB::table('customers')->select("created_at as tanggal", DB::raw("'Create Customer' as history"), "custid as nomor", DB::raw("'2' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('created_at', array($request->from_date, $request->to_date))->orderBy("created_at", "DESC");
                $history_complaint_cust = DB::table('complaint_customer')->select("tanggal_complaint as tanggal", DB::raw("'Create Complaint' as history"), "nomor_complaint as nomor", DB::raw("'3' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_complaint', array($request->from_date, $request->to_date))->orderBy("tanggal_complaint", "DESC");
                $history_complaint_prod = DB::table('complaint_produksi')->select("tanggal_input as tanggal", DB::raw("'Process Produksi Complaint' as history"), "nomor_complaint as nomor", DB::raw("'4' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->orderBy("tanggal_input", "DESC");
                $history_complaint_log = DB::table('complaint_logistik')->select("tanggal_input as tanggal", DB::raw("'Process Logistik Complaint' as history"), "nomor_complaint as nomor", DB::raw("'5' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->orderBy("tanggal_input", "DESC");
                $history_complaint_sales = DB::table('complaint_sales')->select("tanggal_input as tanggal", DB::raw("'Process Sales Complaint' as history"), "nomor_complaint as nomor", DB::raw("'6' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->orderBy("tanggal_input", "DESC");
                $history_complaint_timbang = DB::table('complaint_timbangan')->select("tanggal_input as tanggal", DB::raw("'Process Timbangan Complaint' as history"), "nomor_complaint as nomor", DB::raw("'7' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->orderBy("tanggal_input", "DESC");
                $history_complaint_ware = DB::table('complaint_warehouse')->select("tanggal_input as tanggal", DB::raw("'Process Warehouse Complaint' as history"), "nomor_complaint as nomor", DB::raw("'8' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->orderBy("tanggal_input", "DESC");
                $history_complaint_lab = DB::table('complaint_lab')->select("tanggal_input as tanggal", DB::raw("'Process Lab Complaint' as history"), "nomor_complaint as nomor", DB::raw("'9' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->orderBy("tanggal_input", "DESC");
                $history_complaint_lain = DB::table('complaint_lainnya')->select("tanggal_input as tanggal", DB::raw("'Process Lain-lain Complaint' as history"), "nomor_complaint as nomor", DB::raw("'10' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->orderBy("tanggal_input", "DESC");
                $history = DB::table('follow_up_customer')->select("tanggal_input as tanggal", DB::raw("'Create Follow Up' as history"), "nomor_followup as nomor", DB::raw("'11' as kode_history"))->where('created_by', $request->id_user_admin)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->orderBy("tanggal_input", "DESC")->union($history_order)->union($history_customer)->union($history_complaint_cust)->union($history_complaint_prod)->union($history_complaint_log)->union($history_complaint_sales)->union($history_complaint_timbang)->union($history_complaint_ware)->union($history_complaint_lab)->union($history_complaint_lain)->get();
            }else{
                $history_order = DB::table('order_receipt')->select("tanggal_order as tanggal", DB::raw("'Create Order' as history"), "nomor_order_receipt as nomor", DB::raw("'1' as kode_history"))->where('order_created_by', $request->id_user_admin)->orderBy("tanggal_order", "DESC");
                $history_customer = DB::table('customers')->select("created_at as tanggal", DB::raw("'Create Customer' as history"), "custid as nomor", DB::raw("'2' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("created_at", "DESC");
                $history_complaint_cust = DB::table('complaint_customer')->select("tanggal_complaint as tanggal", DB::raw("'Create Complaint' as history"), "nomor_complaint as nomor", DB::raw("'3' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_complaint", "DESC");
                $history_complaint_prod = DB::table('complaint_produksi')->select("tanggal_input as tanggal", DB::raw("'Process Produksi Complaint' as history"), "nomor_complaint as nomor", DB::raw("'4' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_input", "DESC");
                $history_complaint_log = DB::table('complaint_logistik')->select("tanggal_input as tanggal", DB::raw("'Process Logistik Complaint' as history"), "nomor_complaint as nomor", DB::raw("'5' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_input", "DESC");
                $history_complaint_sales = DB::table('complaint_sales')->select("tanggal_input as tanggal", DB::raw("'Process Sales Complaint' as history"), "nomor_complaint as nomor", DB::raw("'6' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_input", "DESC");
                $history_complaint_timbang = DB::table('complaint_timbangan')->select("tanggal_input as tanggal", DB::raw("'Process Timbangan Complaint' as history"), "nomor_complaint as nomor", DB::raw("'7' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_input", "DESC");
                $history_complaint_ware = DB::table('complaint_warehouse')->select("tanggal_input as tanggal", DB::raw("'Process Warehouse Complaint' as history"), "nomor_complaint as nomor", DB::raw("'8' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_input", "DESC");
                $history_complaint_lab = DB::table('complaint_lab')->select("tanggal_input as tanggal", DB::raw("'Process Lab Complaint' as history"), "nomor_complaint as nomor", DB::raw("'9' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_input", "DESC");
                $history_complaint_lain = DB::table('complaint_lainnya')->select("tanggal_input as tanggal", DB::raw("'Process Lain-lain Complaint' as history"), "nomor_complaint as nomor", DB::raw("'10' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_input", "DESC");
                $history = DB::table('follow_up_customer')->select("tanggal_input as tanggal", DB::raw("'Create Follow Up' as history"), "nomor_followup as nomor", DB::raw("'11' as kode_history"))->where('created_by', $request->id_user_admin)->orderBy("tanggal_input", "DESC")->union($history_order)->union($history_customer)->union($history_complaint_cust)->union($history_complaint_prod)->union($history_complaint_log)->union($history_complaint_sales)->union($history_complaint_timbang)->union($history_complaint_ware)->union($history_complaint_lab)->union($history_complaint_lain)->get();
            }

            return datatables()->of($history)->make(true);
        }
    }  

    public function processFormContactEN(Request $request){
        $this->validate($request, [
            'form_contact_name' => 'required',
            'form_contact_email' => 'required|email',
            'g-recaptcha-response' => 'required|recaptcha',
        ],
        [
            'form_contact_name.required' => 'Name is Required',
            'form_contact_email.required' => 'Email is Required',
            'form_contact_email.email' => 'Format Email is Wrong',
            'g-recaptcha-response.required' => 'ReCaptcha is Required',
            'g-recaptcha-response.recaptcha' => 'ReCaptcha is Wrong',
        ]);

        try{
            $cek_data = DB::table('contact_us')->where('name', $request->form_contact_name)->where('email', $request->form_contact_email)->where('company', $request->form_contact_company)->where('phone', $request->form_contact_phone)->where('message', $request->form_contact_message)->first();

            if(!$cek_data){
                $input_data = DB::table('contact_us')->insertGetId(['name' => $request->form_contact_name, 'company' => $request->form_contact_company, 'email' => $request->form_contact_email, 'phone' => $request->form_contact_phone, 'message' => $request->form_contact_message, 'status' => 1, 'tanggal_input' => date('Y-m-d')]);

                if(!empty($input_data)){
                    $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

                    $new_data_notif = DB::table('contact_us')->select('name', 'company', 'email')->where('id', $input_data)->first();

                    Notification::send($user_notif, new NotifNewContactUs($new_data_notif));

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_contact_us')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nama' => $request->form_contact_name, 'status' => 0, 'action' => $request->form_contact_name . ' Memasukkan Data Contact Us No. ' . $input_data]);

                    return redirect()->back()->with('alert','Thank You For Contacting Us, We Will Contact You As Soon As Possible');
                }else{
                    return redirect()->back()->with('alert','Somethings Wrong, Please Try Again');
                }
            }else{
                return redirect()->back()->with('alert',"Your Message is Already in Our System. Please Wait.");
            }

            // Mail::send('email_contact_us', ['nama' => $request->form_contact_name, 'pesan' => $request->form_contact_message, 'company' => $request->form_contact_company, 'phone' => $request->form_contact_phone, 'email' => $request->form_contact_email], function ($message) use ($request)
            // {
            //     $message->subject('[PT. Dwi Selo Giri Mas] Contact from ' . $request->form_contact_email);
            //     $message->from('no-reply@dwiselogirimas.com', 'PT. Dwi Selo Giri Mas');
            //     $message->to($request->form_contact_email);
            // });
            
            
        }
        catch (Exception $e){
            return response (['status' => false,'errors' => $e->getMessage()]);
        }
    }  

    public function processFormContactID(Request $request){
        $this->validate($request, [
            'form_contact_name' => 'required',
            'form_contact_email' => 'required|email',
            'g-recaptcha-response' => 'required|recaptcha',
        ],
        [
            'form_contact_name.required' => 'Nama Harus Diisi',
            'form_contact_email.required' => 'Email Harus Diisi',
            'form_contact_email.email' => 'Format Email Salah',
            'g-recaptcha-response.required' => 'ReCaptcha Harus Dicentang',
            'g-recaptcha-response.recaptcha' => 'ReCaptcha Salah',
        ]);

        try{
            $cek_data = DB::table('contact_us')->where('name', $request->form_contact_name)->where('email', $request->form_contact_email)->where('company', $request->form_contact_company)->where('phone', $request->form_contact_phone)->where('message', $request->form_contact_message)->first();

            if(!$cek_data){
                $input_data = DB::table('contact_us')->insertGetId(['name' => $request->form_contact_name, 'company' => $request->form_contact_company, 'email' => $request->form_contact_email, 'phone' => $request->form_contact_phone, 'message' => $request->form_contact_message, 'status' => 1, 'tanggal_input' => date('Y-m-d')]);
                if(!empty($input_data)){
                    $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

                    $new_data_notif = DB::table('contact_us')->select('name', 'company', 'email')->where('id', $input_data)->first();

                    Notification::send($user_notif, new NotifNewContactUs($new_data_notif));

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_contact_us')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nama' => $request->form_contact_name, 'status' => 0, 'action' => $request->form_contact_name . ' Memasukkan Data Contact Us No. ' . $input_data]);

                    return redirect()->back()->with('alert','Terima Kasih Telah Menghubungi Kami, Kami akan Menghubungi Anda Sesegera Mungkin');
                }else{
                    return redirect()->back()->with('alert','Terjadi Kesalahan, Silahkan Coba Lagi');
                }
            }else{
                return redirect()->back()->with('alert','Pesan Anda Sudah Ada di Sistem Kami. Mohon Menunggu.');
            }

            // Mail::send('email_contact_us', ['nama' => $request->form_contact_name, 'pesan' => $request->form_contact_message, 'company' => $request->form_contact_company, 'phone' => $request->form_contact_phone, 'email' => $request->form_contact_email], function ($message) use ($request)
            // {
            //     $message->subject('[PT. Dwi Selo Giri Mas] Contact from ' . $request->form_contact_email);
            //     $message->from('no-reply@dwiselogirimas.com', 'PT. Dwi Selo Giri Mas');
            //     $message->to($request->form_contact_email);
            // });
        }
        catch (Exception $e){
            return response (['status' => false,'errors' => $e->getMessage()]);
        }
    }  

    public function processFormCareerEN(Request $request){
        $this->validate($request, [
            'form_career_name' => 'required',
            'form_career_email' => 'required|email',
            'form_career_phone' => 'required',
            'form_career_position' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
            'form_career_file' => 'required|file|mimes:pdf,docx,doc|max:2048',
        ],
        [
            'form_career_name.required' => 'Name is Required',
            'form_career_email.required' => 'Email is Required',
            'form_career_position.required' => 'Position is Required',
            'form_career_phone.required' => 'Phone is Required',
            'form_career_email.email' => 'Format Email is Wrong',
            'g-recaptcha-response.required' => 'ReCaptcha is Required',
            'g-recaptcha-response.recaptcha' => 'ReCaptcha is Wrong',
            'form_career_file.required' => 'Attach File is Required',
            'form_career_file.mimes' => 'Format File Only PDF, DOCX, DOC',
            'form_career_file.max' => 'Maximum Size File is 2 MB',
        ]);

        try{
            $cek_data = DB::table('career_form')->where('name', $request->form_career_name)->where('email', $request->form_career_email)->where('phone', $request->form_career_phone)->where('position', $request->form_career_position)->first();

            if(!$cek_data){
                $file_career = $request->file('form_career_file');
                $nama_file_career = time().date('Y-m-d')."_Career_".$request->form_career_name."_".$request->form_contact_position."_".$file_career->getClientOriginalName();
                $tujuan_upload_career = 'document_career';
                $file_career->move($tujuan_upload_career, $nama_file_career);

                $input_data = DB::table('career_form')->insertGetId(['name' => $request->form_career_name, 'position' => $request->form_career_position, 'email' => $request->form_career_email, 'phone' => $request->form_career_phone, 'file_career' => $nama_file_career, 'status' => 1, 'tanggal_input' => date('Y-m-d')]);
                if(!empty($input_data)){
                    $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 5)->first();

                    $new_data_notif = DB::table('career_form')->select('name', 'position', 'email')->where('id', $input_data)->first();

                    Notification::send($user_notif, new NotifNewResume($new_data_notif));

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_resume_cv')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nama' => $request->form_career_name, 'status' => 0, 'action' => $request->form_career_name . ' Memasukkan Data CV Resume No. ' . $input_data]);

                    return redirect()->back()->with('alert','Thank You For Send Your Resume, We Will Contact You If You Meet Our Requirements');
                }else{
                    return redirect()->back()->with('alert','Somethings Wrong, Please Try Again');
                }
            }else{
                return redirect()->back()->with('alert',"Your Resume is Already in Our System. Please Wait For Confirmation From Us");
            }

            // Mail::send('email_career', ['nama' => $request->form_career_name, 'file_career' => $nama_file_career, 'position' => $request->form_career_position, 'phone' => $request->form_career_phone, 'email' => $request->form_career_email], function ($message) use ($request)
            // {
            //     $message->subject('Hi, Career from ' . $request->form_career_email);
            //     $message->from('no-reply@dwiselogirimas.com', 'PT. Dwi Selo Giri Mas');
            //     $message->to($request->form_career_email);
            // });
            
            
        }
        catch (Exception $e){
            return response (['status' => false,'errors' => $e->getMessage()]);
        }
    }  

    public function processFormCareerID(Request $request){
        $this->validate($request, [
            'form_career_name' => 'required',
            'form_career_email' => 'required|email',
            'form_career_phone' => 'required',
            'form_career_position' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
            'form_career_file' => 'required|file|mimes:pdf,docx,doc|max:2048',
        ],
        [
            'form_career_name.required' => 'Nama Harus Diisi',
            'form_career_email.required' => 'Email Harus Diisi',
            'form_career_position.required' => 'Posisi Harus Diisi',
            'form_career_phone.required' => 'Telepon Harus Diisi',
            'form_career_email.email' => 'Format Email Salah',
            'g-recaptcha-response.required' => 'ReCaptcha Harus Dicentang',
            'g-recaptcha-response.recaptcha' => 'ReCaptcha Salah',
            'form_career_file.required' => 'Anda Harus Upload Resume',
            'form_career_file.mimes' => 'Format File Hanya PDF, DOCX, DOC',
            'form_career_file.max' => 'Maksimum Ukuran File 2 MB',
        ]);

        try{
             $cek_data = DB::table('career_form')->where('name', $request->form_career_name)->where('email', $request->form_career_email)->where('phone', $request->form_career_phone)->where('position', $request->form_career_position)->first();

            if(!$cek_data){
                $file_career = $request->file('form_career_file');
                $nama_file_career = time().date('Y-m-d')."_Career_".$request->form_career_name."_".$request->form_contact_position."_".$file_career->getClientOriginalName();
                $tujuan_upload_career = 'document_career';
                $file_career->move($tujuan_upload_career, $nama_file_career);

                $input_data = DB::table('career_form')->insertGetId(['name' => $request->form_career_name, 'position' => $request->form_career_position, 'email' => $request->form_career_email, 'phone' => $request->form_career_phone, 'file_career' => $nama_file_career, 'status' => 1, 'tanggal_input' => date('Y-m-d')]);

                if(!empty($input_data)){
                    $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 5)->first();

                    $new_data_notif = DB::table('career_form')->select('name', 'position', 'email')->where('id', $input_data)->first();

                    Notification::send($user_notif, new NotifNewResume($new_data_notif));

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_resume_cv')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nama' => $request->form_career_name, 'status' => 0, 'action' => $request->form_career_name . ' Memasukkan Data CV Resume No. ' . $input_data]);

                    return redirect()->back()->with('alert','Terima Kasih Telah Mengirimkan Resume Anda, Kami Akan Menghubungi Anda Apabila Anda Memenuhi Kriteria Kami');
                }else{
                    return redirect()->back()->with('alert','Terjadi Kesalahan. Silahkan Coba Lagi');
                }
            }else{
                return redirect()->back()->with('alert','Resume Anda Sudah Ada di Sistem Kami. Mohon Tunggu Konfirmasi Dari Kami');
            }

            // Mail::send('email_career', ['nama' => $request->form_career_name, 'file_career' => $nama_file_career, 'position' => $request->form_career_position, 'phone' => $request->form_career_phone, 'email' => $request->form_career_email], function ($message) use ($request)
            // {
            //     $message->subject('Hi, Career from ' . $request->form_career_email);
            //     $message->from('no-reply@dwiselogirimas.com', 'PT. Dwi Selo Giri Mas');
            //     $message->to($request->form_career_email);
            // });
        }
        catch (Exception $e){
            return response (['status' => false,'errors' => $e->getMessage()]);
        }
    }

    public function uploadFileHRD(Request $request){
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function deleteFileHRD(Request $request) 
    {
        $file = $request->upload_file;
        File::delete(storage_path('tmp/uploads') . '/' . $file);
    }

    public function uploadHRD(Request $request){
        
    }

    public function salesSchedule(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $reminder = DB::table('schedule_sales')->select('perihal', 'keterangan', 'tanggal_schedule', 'status')->where('status', 1)->whereRaw("tanggal_schedule <= NOW() + INTERVAL 1 DAY")->get();

            DB::table('temp_quotation')->where('id_user', Session::get('id_user_admin'))->delete();

            return view('input_schedule', ['reminder' => $reminder]);
        }
    }

    public function salesHistory(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('input_history');
        }
    }

    public function getTotalProduksiOne(){
        $from_date = date('Y-m-01');
        $to_date = date('Y-m-14');
        $data = DB::table('laporan_hasil_produksi as lhp')->select(DB::raw("sum(lhpd.jumlah_tonase) as total_produksi"))->join('laporan_hasil_produksi_detail as lhpd', 'lhpd.nomor_laporan_produksi', '=', 'lhp.nomor_laporan_produksi')->whereBetween('lhp.tanggal_laporan_produksi', array($from_date, $to_date))->get();

        return Response()->json($data);
    }

    public function getTotalProduksiTwo(){
        $from_date = date('Y-m-15');
        $to_date = date('Y-m-31');
        $data = DB::table('laporan_hasil_produksi as lhp')->select(DB::raw("sum(lhpd.jumlah_tonase) as total_produksi"))->join('laporan_hasil_produksi_detail as lhpd', 'lhpd.nomor_laporan_produksi', '=', 'lhp.nomor_laporan_produksi')->whereBetween('lhp.tanggal_laporan_produksi', array($from_date, $to_date))->get();

        return Response()->json($data);
    }

    public function getTotalProduksiChart(){
        $data = DB::table('laporan_hasil_produksi as lhp')->select(DB::raw("year(lhp.tanggal_laporan_produksi) as tahun"), DB::raw("month(lhp.tanggal_laporan_produksi) as bulan"), DB::raw("sum(lhpd.jumlah_tonase) as total_produksi"))->join('laporan_hasil_produksi_detail as lhpd', 'lhpd.nomor_laporan_produksi', '=', 'lhp.nomor_laporan_produksi')->whereBetween('lhp.tanggal_laporan_produksi', array("1970-01-01", date('Y-m-d')))->groupBy('tahun')->groupBy('bulan')->get();

        return Response()->json($data);
    }

    public function getRataProduksiChart(){
        $data = DB::select("select year(lh.tanggal_laporan_produksi) as tahun, month(lh.tanggal_laporan_produksi) as bulan, avg(lh.jumlah_tonase) as rata_produksi from (select lhp.tanggal_laporan_produksi, sum(lhpd.jumlah_tonase) as jumlah_tonase from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi group by lhp.tanggal_laporan_produksi) as lh where (lh.tanggal_laporan_produksi between '1970-01-01' and ?) group by tahun and bulan", [date('Y-m-d')]);

        return Response()->json($data);
    }

    public function getDataRekapProduksi(){
        $arr_date = [];
        $month = date('m');
        $year = date('Y');

        $week = date("W", strtotime($year . "-" . $month ."-01")); // weeknumber of first day of month

        array_push($arr_date, date("Y-m-d", strtotime($year . "-" . $month ."-01"))); // first day of month
        $unix = strtotime($year."W".$week ."+1 week");
        While(date("m", $unix) == $month){ // keep looping/output of while it's correct month
            array_push($arr_date, date("Y-m-d", $unix-86400)); // Sunday of previous week
            array_push($arr_date, date("Y-m-d", $unix)); // this week's monday
            $unix = $unix + (86400*7);
        }
        array_push($arr_date, date("Y-m-d", strtotime("last day of ".$year . "-" . $month))); //echo last day of month

        $query_sa = "select 'SA' as mesin, ";
        $query_sb = "select 'SB' as mesin, ";
        $query_mixer = "select 'Mixer' as mesin, ";
        $query_ra = "select 'RA' as mesin, ";
        $query_rb = "select 'RB' as mesin, ";
        $query_rc = "select 'RC' as mesin, ";
        $query_rd = "select 'RD' as mesin, ";
        $query_re = "select 'RE' as mesin, ";
        $query_rf = "select 'RF' as mesin, ";
        $query_rg = "select 'RG' as mesin, ";
        $query_coating = "select 'Coating' as mesin, ";
        $query_total = "select 'Total' as mesin, ";
        for($a = 1; $a <= 11; $a++){
            $j = 1;
            for($i = 0; $i < count($arr_date); $i+=2){
                if($a == 1){
                    ${'week_sa' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 2){
                    ${'week_sb' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 3){
                    ${'week_mixer' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 4){
                    ${'week_ra' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 5){
                    ${'week_rb' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 6){
                    ${'week_rc' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 7){
                    ${'week_rd' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 8){
                    ${'week_re' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 9){
                    ${'week_rf' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 10){
                    ${'week_rg' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }else if($a == 11){
                    ${'week_coating' . $j} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhpd.mesin = " . $a . " and lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$j;
                }
                $j++;
            }
        }

        $k=1;
        for($i = 0; $i < count($arr_date); $i+=2){
            ${'week_total' . $k} = "(select sum(lhpd.jumlah_tonase) from laporan_hasil_produksi lhp join laporan_hasil_produksi_detail lhpd on lhpd.nomor_laporan_produksi = lhp.nomor_laporan_produksi where lhp.tanggal_laporan_produksi between '" . $arr_date[$i] . "' and '" . $arr_date[$i+1] . "') as week".$k;
            $k++;
        }

        for($i = 1; $i < $j; $i++){
            if($i == $j - 1){
                $query_sa .= ${'week_sa' . $i};
                $query_sb .= ${'week_sb' . $i};
                $query_mixer .= ${'week_mixer' . $i};
                $query_ra .= ${'week_ra' . $i};
                $query_rb .= ${'week_rb' . $i};
                $query_rc .= ${'week_rc' . $i};
                $query_rd .= ${'week_rd' . $i};
                $query_re .= ${'week_re' . $i};
                $query_rf .= ${'week_rf' . $i};
                $query_rg .= ${'week_rg' . $i};
                $query_coating .= ${'week_coating' . $i};
                $query_total .= ${'week_total' . $i};
            }else{
                $query_sa .= ${'week_sa' . $i} . ", ";
                $query_sb .= ${'week_sb' . $i} . ", ";
                $query_mixer .= ${'week_mixer' . $i} . ", ";
                $query_ra .= ${'week_ra' . $i} . ", ";
                $query_rb .= ${'week_rb' . $i} . ", ";
                $query_rc .= ${'week_rc' . $i} . ", ";
                $query_rd .= ${'week_rd' . $i} . ", ";
                $query_re .= ${'week_re' . $i} . ", ";
                $query_rf .= ${'week_rf' . $i} . ", ";
                $query_rg .= ${'week_rg' . $i} . ", ";
                $query_coating .= ${'week_coating' . $i} . ", ";
                $query_total .= ${'week_total' . $i} . ", ";
            }
        }

        $sa = DB::select($query_sa);
        $sb = DB::select($query_sb);
        $mixer = DB::select($query_mixer);
        $ra = DB::select($query_ra);
        $rb = DB::select($query_rb);
        $rc = DB::select($query_rc);
        $rd = DB::select($query_rd);
        $re = DB::select($query_re);
        $rf = DB::select($query_rf);
        $rg = DB::select($query_rg);
        $coating = DB::select($query_coating);
        $total = DB::select($query_total);

        return Response()->json(['sa' => $sa, 'sb' => $sb, 'mixer' => $mixer, 'ra' => $ra, 'rb' => $rb, 'rc' => $rc, 'rd' => $rd, 're' => $re, 'rf' => $rf, 'rg' => $rg, 'coating' => $coating, 'total' => $total]);
    }

    public function getRencanaProduksiHariIni(){
        $curdate = date('Y-m-d');
        $data = DB::table('rencana_produksi as ren')->select('det.mesin as no_mesin', 'mes.name as mesin', 'det.jenis_produk', 'det.jumlah_sak', 'det.jumlah_tonase')->join('rencana_produksi_detail as det', 'det.nomor_rencana_produksi', '=', 'ren.nomor_rencana_produksi')->join('tbl_mesin as mes', 'mes.id', '=', 'det.mesin')->where('ren.id_user', Session::get('id_user_admin'))->where('ren.tanggal_rencana', $curdate)->groupBy('ren.tanggal_rencana')->groupBy('det.mesin')->get();

        return Response()->json($data);
    }

    public function getListProdukKurang(){
        $data = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('saldo', '<', 0)->groupBy('jenis_produk')->get();

        return Response()->json($data);
    }

    public function listResumeTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('career_form')->select('id', 'name', 'email', 'position', 'phone', 'file_career as resume')->where('status', 1)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('career_form')->select('id', 'name', 'email', 'position', 'phone', 'file_career as resume')->where('status', 1)->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_list_resume')->rawColumns(['action'])->make(true);
        }
    }

    public function listResumeValidasi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $validasi = DB::table("career_form")->where("id", $request->get("id"))->update(["status" => 2]);

        if($validasi){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_resume_cv')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'Admin ' . Session::get('id_user_admin') . ' Memvalidasi Data CV Resume No. ' . $request->get("id")]);

        return Response()->json($arr);
    }

    public function listContactUsTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('contact_us')->select('id', 'name', 'email', 'company', 'phone', 'message')->where('status', 1)->whereBetween('tanggal_input', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('contact_us')->select('id', 'name', 'email', 'company', 'phone', 'message')->where('status', 1)->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_list_contact_us')->rawColumns(['action'])->make(true);
        }
    }

    public function listContactUsValidasi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $validasi = DB::table("contact_us")->where("id", $request->get("id"))->update(["status" => 2]);

        if($validasi){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_contact_us')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'Admin ' . Session::get('id_user_admin') . ' Memvalidasi Data Contact Us No. ' . $request->get("id")]);

        return Response()->json($arr);
    }

    public function listContactUsLihatPesan(Request $request){
        $data = DB::table("contact_us")->select("email", "message")->where("id", $request->get("id"))->first();

        return Response()->json($data);
    }

    public function listStaffProduksiTable(Request $request){
        $list = DB::table("karyawan")->select('nomor_karyawan', 'nama_karyawan', 'nomor_hp', 'bagian.nama_bagian as bagian')->join('bagian', 'bagian.kode_bagian', '=', 'karyawan.kode_bagian')->where("karyawan.kode_bagian", "LIKE", "PRD%")->get();

        return datatables()->of($list)->make(true);
    }

    public function listMandorTable(Request $request){
        $list = DB::table("karyawan")->select('nomor_karyawan', 'nama_karyawan', 'nomor_hp', 'bagian.nama_bagian as bagian')->join('bagian', 'bagian.kode_bagian', '=', 'karyawan.kode_bagian')->where("karyawan.kode_bagian", "LIKE", "MND%")->get();

        return datatables()->of($list)->make(true);
    }

    public function listStaffProduksiShowEdit($id){
        $data = DB::table("karyawan")->select('nomor_karyawan', 'nama_karyawan', 'nomor_hp', 'bagian.nama_bagian as bagian', 'sp.address', 'kota.name as city_name', 'sp.city')->join('kota', 'kota.id_kota', '=', 'sp.city')->where("sp.id_staff_produksi", $id)->first();

        return Response()->json($data);
    }

    public function getViewAbsensi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('absensi');
        }
    }

    public function getViewPelanggaran(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('pelanggaran');
        }
    }

    public function getViewHariBesar(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('hari_besar');
        }
    }

    public function getViewLembur(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('lembur');
        }
    }

    public function getViewKonfigurasiUpah(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('konfigurasi_upah');
        }
    }

    public function getViewRumusUpah(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('rumus_upah');
        }
    }

    public function getViewPayroll(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('payroll');
        }
    }

    public function jumlahPelanggaran(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $pelanggaran = DB::table('pelanggaran')->select('nik', DB::raw("count(jenis_pelanggaran) as jumlah_pelanggaran"))->whereBetween('tanggal', array($request->from_date, $request->to_date))->groupBy('nik')->get();
            }else{
                $pelanggaran = DB::table('pelanggaran')->select('nik', DB::raw("count(jenis_pelanggaran) as jumlah_pelanggaran"))->whereRaw('MONTH(tanggal) = ?',[$currentMonth])->whereRaw('YEAR(tanggal) = ?',[$currentYear])->groupBy('nik')->get();
            }

            return datatables()->of($pelanggaran)->addIndexColumn()->make(true);
        }
        return view('pelanggaran');
    }

    public function jumlahDetailPelanggaran(Request $request){
        if(request()->ajax()){    
            $pelanggaran = DB::table('pelanggaran')->select('tanggal', 'tbl.name as pelanggaran', 'alasan_pelanggaran')->join('tbl_jenis_pelanggaran as tbl', 'tbl.id', '=', 'pelanggaran.jenis_pelanggaran')->where('nik', $request->nik)->get();

            return datatables()->of($pelanggaran)->addIndexColumn()->make(true);
        }
        return view('pelanggaran');
    } 

    public function viewHariBesarTable(Request $request){
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('hari_besar')->select('id', 'tanggal', 'hari_libur')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('hari_besar')->select('id', 'tanggal', 'hari_libur')->whereRaw('YEAR(tanggal) = ?',[$currentYear])->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_list_hari_besar')->rawColumns(['action'])->make(true);
        }
        return view('hari_besar');
    }

    public function inputHariBesar(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('hari_besar')->insert(['tanggal' => $request->tanggal, 'hari_libur' => $request->hari_libur]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_hari_besar')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Hari Besar Tanggal ' . $request->tanggal]);

        return Response()->json($arr);
    }

    public function deleteHariBesar(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('hari_besar')->where('id', $request->get('id'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_hari_besar')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Hari Besar Tanggal ' . $request->tanggal]);

        return Response()->json($arr);
    }

    public function viewHariBesar($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('hari_besar')->where('id', $val_id)->first();

        return Response()->json($data);
    }

    public function editHariBesar(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('hari_besar')->where('id', $request->edit_id)->update(['tanggal' => $request->edit_tanggal, 'hari_libur' => $request->edit_hari_libur]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_hari_besar')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Hari Besar Tanggal ' . $request->tanggal]);

        return Response()->json($arr);
    }

    public function viewLemburTable(Request $request){
        $currentDate = date('Y-m-d');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('lembur')->select('id', 'tanggal', 'karyawan.nama_karyawan as karyawan', 'nomor_surat_lembur', DB::raw('jumlah_jam / 60 as jumlah_jam'), 'keterangan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'lembur.karyawan')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('lembur')->select('id', 'tanggal', 'karyawan.nama_karyawan as karyawan', 'nomor_surat_lembur', DB::raw('jumlah_jam / 60 as jumlah_jam'), 'keterangan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'lembur.karyawan')->where('tanggal', $currentDate)->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_list_lembur')->rawColumns(['action'])->make(true);
        }
        return view('lembur');
    }

    public function inputLembur(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('lembur')->insert(['tanggal' => $request->tanggal, 'nomor_surat_lembur' => $request->nomor_surat_lembur, 'karyawan' => $request->karyawan, 'jumlah_jam' => $request->jumlah_jam * 60, 'keterangan' => $request->keterangan]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_lembur')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Lembur Nomor ' . $request->nomor_surat_lembur]);

        return Response()->json($arr);
    }

    public function deleteLembur(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('lembur')->where('id', $request->get('id'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_lembur')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Lembur Dengan ID ' . $request->get('id')]);

        return Response()->json($arr);
    }

    public function viewLembur($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('lembur')->select('id','nomor_surat_lembur', 'tanggal', 'karyawan as nomor_karyawan', DB::raw('jumlah_jam / 60 as jumlah_jam'), 'keterangan', 'karyawan.nama_karyawan as karyawan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'lembur.karyawan')->where('id', $val_id)->first();

        return Response()->json($data);
    }

    public function editLembur(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('lembur')->where('id', $request->edit_id)->update(['tanggal' => $request->edit_tanggal, 'nomor_surat_lembur' => $request->edit_nomor_surat_lembur, 'karyawan' => $request->edit_karyawan, 'jumlah_jam' => $request->edit_jumlah_jam * 60, 'keterangan' => $request->edit_keterangan]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_lembur')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Lembur Dengan ID ' . $request->edit_id]);

        return Response()->json($arr);
    }

    public function viewKonfigurasiUpah($company){
        $val_company = $this->decrypt($company);

        $data = DB::table('konfigurasi_upah')->where('kode_perusahaan', $val_company)->first();

        return Response()->json($data);
    }

    public function editKonfigurasiUpah(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('konfigurasi_upah')->where('kode_perusahaan', $request->perusahaan)->update(['upah_minimum' => str_replace(',', '.', $request->besar_upah_minimum), 'astek' => str_replace(',', '.', $request->astek), 'ptkp_menikah' => str_replace(',', '.', $request->ptkp_menikah), 'ptkp_anak' => str_replace(',', '.', $request->ptkp_anak), 'jaminan_kesehatan' => str_replace(',', '.', $request->jaminan_kesehatan), 'jaminan_kecelakaan_kerja' => str_replace(',', '.', $request->jaminan_kecelakaan_kerja), 'bpjs' => str_replace(',', '.', $request->bpjs), 'persentase_pph21' => str_replace(',', '.', $request->persentase_pph21), 'limit_max_biaya_jabatan' => str_replace(',', '.', $request->limit_max_biaya_jabatan), 'uang_makan' => str_replace(',', '.', $request->uang_makan), 'jumlah_jam_kerja_paruh_hari' => $request->jam_kerja_paruh_waktu, 'jumlah_jam_kerja_satu_hari' => $request->jam_kerja_satu_hari, 'jadwal_pembayaran_gaji' => $request->jadwal_pembayaran_gaji, 'jangka_bayar' => $request->jangka_bayar, 'sistem_premi' => $request->sistem_premi, 'sistem_upah_lembur' => $request->sistem_upah_lembur, 'sistem_pembulatan' => $request->sistem_pembulatan, 'batas_pembulatan' => str_replace(',', '.', $request->batas_pembulatan), 'direktori_backup_data' => $request->direktori_backup_data, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_konfigurasi_upah')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Konfigurasi Upah Perusahaan ' . $request->perusahaan]);

        return Response()->json($request->perusahaan);
    }

    public function inputKonfigurasiUpah(Request $request){

        $cek_data = DB::table('konfigurasi_upah')->select('kode_konfigurasi')->where('kode_perusahaan', $request->input_perusahaan)->first();

        if($cek_data){
            $arr = 1;
        }else{
            $arr = 0;

            $data_konfigurasi = DB::table('konfigurasi_upah')->select('kode_konfigurasi')->where('kode_konfigurasi', 'like', 'KONF%')->orderBy('kode_konfigurasi', 'asc')->distinct()->get();

            if($data_konfigurasi){
                $konfigurasi_count = $data_konfigurasi->count();
                if($konfigurasi_count > 0){
                    $num = (int) substr($data_konfigurasi[$data_konfigurasi->count() - 1]->kode_konfigurasi, 5);
                    if($konfigurasi_count != $num){
                        $kode_konfigurasi = ++$data_konfigurasi[$data_konfigurasi->count() - 1]->kode_konfigurasi;
                    }else{
                        if($konfigurasi_count < 9){
                            $kode_konfigurasi = "KONF-0" . ($konfigurasi_count + 1);
                        }else{
                            $kode_konfigurasi = "KONF-" . ($konfigurasi_count + 1);
                        }
                    }
                }else{
                    $kode_konfigurasi = "KONF-01";
                }
            }else{
                $kode_konfigurasi = "KONF-01";
            }

            date_default_timezone_set('Asia/Jakarta');
            $data = DB::table('konfigurasi_upah')->insert(['kode_konfigurasi' => $kode_konfigurasi, 'kode_perusahaan' => $request->input_perusahaan, 'upah_minimum' => str_replace(',', '.', $request->input_besar_upah_minimum), 'astek' => str_replace(',', '.', $request->input_astek), 'ptkp_menikah' => str_replace(',', '.', $request->input_ptkp_menikah), 'ptkp_anak' => str_replace(',', '.', $request->input_ptkp_anak), 'jaminan_kesehatan' => str_replace(',', '.', $request->input_jaminan_kesehatan), 'jaminan_kecelakaan_kerja' => str_replace(',', '.', $request->input_jaminan_kecelakaan_kerja), 'bpjs' => str_replace(',', '.', $request->input_bpjs), 'persentase_pph21' => str_replace(',', '.', $request->input_persentase_pph21), 'limit_max_biaya_jabatan' => str_replace(',', '.', $request->input_limit_max_biaya_jabatan), 'uang_makan' => str_replace(',', '.', $request->input_uang_makan), 'jumlah_jam_kerja_paruh_hari' => $request->input_jam_kerja_paruh_waktu, 'jumlah_jam_kerja_satu_hari' => $request->input_jam_kerja_satu_hari, 'jadwal_pembayaran_gaji' => $request->input_jadwal_pembayaran_gaji, 'jangka_bayar' => $request->input_jangka_bayar, 'sistem_premi' => $request->input_sistem_premi, 'sistem_upah_lembur' => $request->input_sistem_upah_lembur, 'sistem_pembulatan' => $request->input_sistem_pembulatan, 'batas_pembulatan' => str_replace(',', '.', $request->input_batas_pembulatan), 'direktori_backup_data' => $request->input_direktori_backup_data, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"), 'created_by' => Session::get('id_user_admin'), 'updated_by' => Session::get('id_user_admin')]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_konfigurasi_upah')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Konfigurasi Upah Perusahaan ' . $request->input_perusahaan]);
        }

        return Response()->json(['kode' => $arr, 'perusahaan' => $request->input_perusahaan]);
    }

    public function getCompanyKonfigurasi(){
        $data = DB::table('company')->join('konfigurasi_upah', 'konfigurasi_upah.kode_perusahaan', '=', 'company.kode_perusahaan')->get();

        return Response()->json($data);
    }

    public function viewRumusUpahTable(Request $request){
        if(request()->ajax()){
            $list = DB::table('rumus_upah')->get();

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_list_rumus_upah')->rawColumns(['action'])->make(true);
        }

        return view('rumus_upah');
    }

    public function viewRumusUpah($kode){
        $val_kode = $this->decrypt($kode);

        $data = DB::table('rumus_upah')->where('kode_upah', $val_kode)->first();

        return Response()->json($data);
    }

    public function inputRumusUpah(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('rumus_upah')->insert(['kode_upah' => $request->kode_upah, 'upah_pokok' => str_replace(',', '.', $request->upah_pokok), 'bonus_mingguan' => str_replace(',', '.', $request->bonus_mingguan), 'bonus_bulanan' => str_replace(',', '.', $request->bonus_bulanan), 'rumus_upah' => $request->rumus_upah, 'rumus_lembur' => $request->rumus_lembur, 'rumus_uang_makan' => $request->rumus_uang_makan, 'rumus_tunjangan_jabatan' => $request->rumus_tunjangan_jabatan, 'rumus_bonus' => $request->rumus_bonus, 'rumus_premi' => $request->rumus_premi, 'rumus_tunjangan_lain' => $request->rumus_tunjangan_lain]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_rumus_upah')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Rumus Upah Dengan Kode ' . $request->kode_upah]);

        return Response()->json($arr);
    }

    public function deleteRumusUpah(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('rumus_upah')->where('kode_upah', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_rumus_upah')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Rumus Upah Dengan Kode ' . $request->get('kode_upah')]);

        return Response()->json($arr);
    }

    public function editRumusUpah(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('rumus_upah')->where('kode_upah', $request->edit_kode_upah_lama)->update(['kode_upah' => $request->edit_kode_upah, 'upah_pokok' => str_replace(',', '.', $request->edit_upah_pokok), 'bonus_mingguan' => str_replace(',', '.', $request->edit_bonus_mingguan), 'bonus_bulanan' => str_replace(',', '.', $request->edit_bonus_bulanan), 'rumus_upah' => $request->edit_rumus_upah, 'rumus_lembur' => $request->edit_rumus_lembur, 'rumus_uang_makan' => $request->edit_rumus_uang_makan, 'rumus_tunjangan_jabatan' => $request->edit_rumus_tunjangan_jabatan, 'rumus_bonus' => $request->edit_rumus_bonus, 'rumus_premi' => $request->edit_rumus_premi, 'rumus_tunjangan_lain' => $request->edit_rumus_tunjangan_lain]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_rumus_upah')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Rumus Upah Dengan Kode ' . $request->edit_kode_upah]);

        return Response()->json($arr);
    }

    public function getKodeUpah(){
        $data = DB::table('rumus_upah')->get();

        return Response()->json($data);
    }

    public function showPayroll(Request $request){
        $split = explode(' - ', $request->tanggal);

        $data_karyawan = DB::table('karyawan')->where('nomor_karyawan', $request->karyawan)->join('company', 'company.kode_perusahaan', '=', 'karyawan.kode_perusahaan')->join('unit', 'unit.kode_unit', '=', 'karyawan.kode_unit')->join('bagian', 'bagian.kode_bagian', '=', 'karyawan.kode_bagian')->join('shift', 'shift.kode_shift', '=', 'karyawan.kode_shift')->first();

        $data_upah = DB::table('transaksi_upah')->select(DB::raw('sum(upah) as upah'))->where('nik', $request->karyawan)->whereBetween('tanggal', array($split[0], $split[1]))->get();

        $data_lembur = DB::table('transaksi_lembur')->select(DB::raw('sum(lembur) / 60 as lembur'), DB::raw('sum(uang_lembur) as uang_lembur'))->where('nik', $request->karyawan)->whereBetween('tanggal', array($split[0], $split[1]))->get();

        $data_bonus = DB::table('transaksi_bonus')->select(DB::raw('sum(bonus) as bonus'))->where('nik', $request->karyawan)->whereBetween('tanggal', array($split[0], $split[1]))->get();

        $data_pelanggaran = DB::table('pelanggaran')->select(DB::raw('count(*) as jumlah_pelanggaran'), DB::raw('sum(pelanggaran) as pelanggaran'))->where('nik', $request->karyawan)->whereBetween('tanggal', array($split[0], $split[1]))->get();

        $data_premi = DB::table('transaksi_premi')->select(DB::raw('sum(premi) as premi'))->where('nik', $request->karyawan)->whereRaw('YEAR(tanggal) BETWEEN ? AND ?', array(date('Y', strtotime($split[0])), date('Y', strtotime($split[1]))))->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', array(date('m', strtotime($split[0])), date('m', strtotime($split[1]))))->get();

        return Response()->json(['karyawan' => $data_karyawan, 'upah' => $data_upah, 'lembur' => $data_lembur, 'bonus' => $data_bonus, 'pelanggaran' => $data_pelanggaran, 'premi' => $data_premi]);
    }

    public function getViewAdminIzin(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('admin_izin');
        }
    }

    public function getViewAdminCuti(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('admin_cuti');
        }
    }

    public function viewIzinTable(Request $request){
        $currentDate = date('Y-m-d');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('izin')->select('id', 'tanggal', 'karyawan.nama_karyawan as karyawan', 'nomor_surat_izin', 'status', 'alasan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'izin.karyawan')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('izin')->select('id', 'tanggal', 'karyawan.nama_karyawan as karyawan', 'nomor_surat_izin', 'status', 'alasan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'izin.karyawan')->where('tanggal', $currentDate)->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_list_izin')->rawColumns(['action'])->make(true);
        }
        return view('admin_izin');
    }

    public function inputIzin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('izin')->insert(['tanggal' => $request->tanggal, 'nomor_surat_izin' => $request->nomor_surat_izin, 'karyawan' => $request->karyawan, 'status' => $request->status, 'alasan' => $request->alasan]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_izin')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => $request->status, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Izin Nomor ' . $request->nomor_surat_izin]);

        return Response()->json($arr);
    }

    public function deleteIzin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('izin')->where('id', $request->get('id'))->first();

        $hapus = DB::table('izin')->where('id', $request->get('id'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_izin')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => $data->status, 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Izin Dengan ID ' . $request->get('id')]);

        return Response()->json($arr);
    }

    public function viewIzin($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('izin')->select('id','nomor_surat_izin', 'tanggal', 'karyawan as nomor_karyawan', 'status', 'alasan', 'karyawan.nama_karyawan as karyawan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'izin.karyawan')->where('id', $val_id)->first();

        return Response()->json($data);
    }

    public function editIzin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('izin')->where('id', $request->edit_id)->update(['tanggal' => $request->edit_tanggal, 'nomor_surat_izin' => $request->edit_nomor_surat_izin, 'karyawan' => $request->edit_karyawan, 'status' => $request->edit_status, 'alasan' => $request->edit_alasan]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_izin')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => $request->edit_status, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Izin Dengan ID ' . $request->edit_id]);

        return Response()->json($arr);
    }

    public function viewCutiTable(Request $request){
        $currentDate = date('Y-m-d');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('cuti')->select('id', 'tanggal', 'karyawan.nama_karyawan as karyawan', 'nomor_surat_cuti', 'keterangan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'cuti.karyawan')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('cuti')->select('id', 'tanggal', 'karyawan.nama_karyawan as karyawan', 'nomor_surat_cuti', 'keterangan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'cuti.karyawan')->where('tanggal', $currentDate)->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_list_cuti')->rawColumns(['action'])->make(true);
        }
        return view('admin_cuti');
    }

    public function inputCuti(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('cuti')->insert(['tanggal' => $request->tanggal, 'nomor_surat_cuti' => $request->nomor_surat_cuti, 'karyawan' => $request->karyawan, 'keterangan' => $request->keterangan]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_cuti')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Cuti Nomor ' . $request->nomor_surat_cuti]);

        return Response()->json($arr);
    }

    public function deleteCuti(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('cuti')->where('id', $request->get('id'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_cuti')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Cuti Dengan ID ' . $request->get('id')]);

        return Response()->json($arr);
    }

    public function viewCuti($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('cuti')->select('id','nomor_surat_cuti', 'tanggal', 'karyawan as nomor_karyawan', 'keterangan', 'karyawan.nama_karyawan as karyawan')->join('karyawan', 'karyawan.nomor_karyawan', '=', 'cuti.karyawan')->where('id', $val_id)->first();

        return Response()->json($data);
    }

    public function editCuti(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('cuti')->where('id', $request->edit_id)->update(['tanggal' => $request->edit_tanggal, 'nomor_surat_cuti' => $request->edit_nomor_surat_cuti, 'karyawan' => $request->edit_karyawan, 'keterangan' => $request->edit_keterangan]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_cuti')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Cuti Dengan ID ' . $request->edit_id]);

        return Response()->json($arr);
    }

    public function viewPageRequestUjiSample(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('request_uji_sample');
        }
    }

    public function inputRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $bln = $array_bln[date('n', strtotime($request->tanggal))];

        $kode_uji_sample = "/02/SAMPLE/" . $bln . "/" . date('Y');

        $data_uji = DB::table('uji_sample')->select('nomor_uji_sample')->where('nomor_uji_sample', 'like', '%' . $kode_uji_sample . '%')->orderBy('nomor_uji_sample', 'asc')->distinct()->get();

        if($data_uji){
            $uji_count = $data_uji->count();
            if($uji_count > 0){
                $num = (int) substr($data_uji[$data_uji->count() - 1]->nomor_uji_sample, 0, 2);
                if($uji_count != $num){
                    $kode_uji_sample = ++$data_uji[$data_uji->count() - 1]->nomor_uji_sample;
                }else{
                    if($uji_count < 9){
                        $kode_uji_sample = "0" . ($uji_count + 1) . "" . $kode_uji_sample;
                    }else{
                        $kode_uji_sample = ($uji_count + 1) . "" . $kode_uji_sample;
                    }
                }
            }else{
                $kode_uji_sample = "01" . $kode_uji_sample;
            }
        }else{
            $kode_uji_sample = "01" . $kode_uji_sample;
        }

        if(isset($request->new_customers)){
            $data_kota = ModelKota::where('id_kota', $request->city)->first();
            $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
            $nama_user = strtoupper(str_replace(' ', '', $request->new_customers));
            $kode_nama = substr($nama_user, 0, 5);

            $kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;
            $data_custid = ModelCustomers::where('custid', 'like', '%' . $kode_cust . '%')->orderBy('custid', 'asc')->get();

            if($data_custid){
                $data_count = $data_custid->count();
                if($data_count < 9){
                    $kode_cust = $kode_cust . "0" . ($data_count + 1);
                }else{
                    $kode_cust = $kode_cust . ($data_count + 1);
                }
            }else{
                $kode_cust = $kode_cust . "01";
            }

            $data_cust =  new ModelCustomers();
            $data_cust->custid = $kode_cust;
            $data_cust->company = 'DSGM';
            $data_cust->custname = $request->new_customers;
            $data_cust->city = $request->city;
            $data_cust->nama_cp = $request->new_nama_cp;
            $data_cust->jabatan_cp = $request->new_jabatan_cp;
            $data_cust->bidang_usaha = $request->bidang_usaha;
            $data_cust->save();

            $data = DB::table('uji_sample')->insert(['nomor_uji_sample' => $kode_uji_sample, 'tanggal' => $request->tanggal, 'customers' => $kode_cust, 'merk' => $request->merk, 'tipe' => $request->tipe, 'status' => 1]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin Melalui Request Uji Sample']);
        }else{
            $data = DB::table('uji_sample')->insert(['nomor_uji_sample' => $kode_uji_sample, 'tanggal' => $request->tanggal, 'customers' => $request->customers, 'merk' => $request->merk, 'tipe' => $request->tipe, 'status' => 1]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Input Data Uji Sample Nomor ' . $kode_uji_sample]);

        return Response()->json($arr);
    }

    public function viewRequestUjiSampleTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('uji_sample')->select('nomor_uji_sample as nomor', 'tanggal', 'cus.custname', 'merk', 'tipe', 'status')->join('customers as cus', 'cus.custid', '=', 'uji_sample.customers')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('uji_sample')->select('uji_sample.nomor_uji_sample as nomor', 'tanggal', 'cus.custname', 'merk', 'tipe', 'status')->join('customers as cus', 'cus.custid', '=', 'uji_sample.customers')->get();  
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_request_uji_sample')->rawColumns(['action'])->make(true);
        }
        return view('request_uji_sample');
    }

    public function viewRequestUjiSample($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data_uji = DB::table('uji_sample')->select('uji_sample.nomor_uji_sample as nomor', 'tanggal', 'cus.custname', 'cus.bidang_usaha', 'merk', 'tipe', 'status', 'uji_sample.customers', 'analisa', 'solusi', 'lampiran', 'komentar_produksi')->join('customers as cus', 'cus.custid', '=', 'uji_sample.customers')->where('uji_sample.nomor_uji_sample', $val_nomor)->first();

        $data = DB::table('uji_sample_detail as uji_det')->select('uji_det.produk', 'uji_det.harga', 'uji_det.kelas', 'uji_det.jenis_produk')->join("uji_sample as uji", "uji.nomor_uji_sample", "=", "uji_det.nomor_uji_sample")->where('uji_det.nomor_uji_sample', $val_nomor)->orderBy('uji_det.produk', 'asc')->get();

        $count_data = DB::table('uji_sample_detail as uji_det')->where('uji_det.produk', 2)->where('uji_det.nomor_uji_sample', $val_nomor)->orderBy('uji_det.produk', 'asc')->get();

        $arrayForTable = [];
        foreach($data as $data){
            $temp = [];
            $temp['harga'] = $data->harga;
            $temp['kelas'] = $data->kelas;
            $temp['jenis_produk'] = $data->jenis_produk;
            if(!isset($arrayForTable[$data->produk])){
                $arrayForTable[$data->produk] = [];
            }
            $arrayForTable[$data->produk][] = $temp;
        }
        
        return Response()->json(['data_uji' => $data_uji, 'data_harga' => $arrayForTable, 'data_count' => count($count_data)]);
    }

    public function viewProduksiRequestUjiSample($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('uji_sample_detail as uji_det')->select('uji_det.produk', 'uji_det.jenis_produk', 'uji_det.kalsium', 'uji_det.ssa', 'uji_det.d50', 'uji_det.d98', 'uji_det.cie86', 'uji_det.iso2470', 'uji_det.moisture', 'uji_det.residue')->join("uji_sample as uji", "uji.nomor_uji_sample", "=", "uji_det.nomor_uji_sample")->where('uji_det.produk', 2)->where('uji_det.nomor_uji_sample', $val_nomor)->orderBy('uji_det.produk', 'asc')->get();

        $arrayForTable = [];
        foreach($data as $data){
            if(!isset($arrayForTable['Produk'])){
                $arrayForTable['Produk'] = [];
                $arrayForTable['Produk'][] = $data->jenis_produk;
            }else{
                $arrayForTable['Produk'][] = $data->jenis_produk;
            }
            if(!isset($arrayForTable['Kalsium'])){
                $arrayForTable['Kalsium'] = [];
                $arrayForTable['Kalsium'][] = $data->kalsium;
            }else{
                $arrayForTable['Kalsium'][] = $data->kalsium;
            }
            if(!isset($arrayForTable['SSA'])){
                $arrayForTable['SSA'] = [];
                $arrayForTable['SSA'][] = $data->ssa;
            }else{
                $arrayForTable['SSA'][] = $data->ssa;
            }
            if(!isset($arrayForTable['D50'])){
                $arrayForTable['D50'] = [];
                $arrayForTable['D50'][] = $data->d50;
            }else{
                $arrayForTable['D50'][] = $data->d50;
            }
            if(!isset($arrayForTable['D98'])){
                $arrayForTable['D98'] = [];
                $arrayForTable['D98'][] = $data->d98;
            }else{
                $arrayForTable['D98'][] = $data->d98;
            }
            if(!isset($arrayForTable['CIE86'])){
                $arrayForTable['CIE86'] = [];
                $arrayForTable['CIE86'][] = $data->cie86;
            }else{
                $arrayForTable['CIE86'][] = $data->cie86;
            }
            if(!isset($arrayForTable['ISO2470'])){
                $arrayForTable['ISO2470'] = [];
                $arrayForTable['ISO2470'][] = $data->iso2470;
            }else{
                $arrayForTable['ISO2470'][] = $data->iso2470;
            }
            if(!isset($arrayForTable['Moisture'])){
                $arrayForTable['Moisture'] = [];
                $arrayForTable['Moisture'][] = $data->moisture;
            }else{
                $arrayForTable['Moisture'][] = $data->moisture;
            }
            if(!isset($arrayForTable['Residue'])){
                $arrayForTable['Residue'] = [];
                $arrayForTable['Residue'][] = $data->residue;
            }else{
                $arrayForTable['Residue'][] = $data->residue;
            }
        }
        
        return Response()->json($arrayForTable);
    }

    public function editRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data_produk = ['kompetitor', 'dsgm'];

        $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->edit_nomor)->update(['tanggal' => $request->edit_tanggal, 'customers' => $request->edit_customers, 'merk' => $request->edit_merk, 'tipe' => $request->edit_tipe]);

        for($i = 1; $i <= count($data_produk); $i++){
            if($i == 1){
                if(isset($request->{"edit_harga_" . $data_produk[$i - 1]}) || !empty($request->{"edit_harga_" . $data_produk[$i - 1]})){
                    $data_lab = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('produk', $i)->update(["harga" => $request->{"edit_harga_" . $data_produk[$i - 1]}, "kelas" => $request->{"edit_kelas_" . $data_produk[$i - 1]}]);
                }
            }else{
                $number = count($request->{"edit_harga_" . $data_produk[$i - 1]});
                for($j = 0; $j < $number; $j++){
                    if(isset($request->{"edit_harga_" . $data_produk[$i - 1]}[$j]) || !empty($request->{"edit_harga_" . $data_produk[$i - 1]}[$j])){
                        $data_lab = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('jenis_produk', $request->{"edit_jenis_produk_" . $data_produk[$i - 1]}[$j])->update(["harga" => $request->{"edit_harga_" . $data_produk[$i - 1]}[$j], "kelas" => $request->{"edit_kelas_" . $data_produk[$i - 1]}[$j]]);
                    }
                }
            }
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Edit Data Uji Sample Nomor ' . $request->edit_nomor]);

        return Response()->json($arr);
    }

    public function deleteRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('uji_sample')->where('nomor_uji_sample', $request->get('nomor'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Delete Data Uji Sample Nomor ' . $request->get('nomor')]);

        return Response()->json($arr);
    }

    public function validasiRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('uji_sample')->where('nomor_uji_sample', $request->get('nomor'))->update(["status" => 4, "tanggal_validasi_sales" => date('Y-m-d')]);

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 4, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Validasi Data Uji Sample Nomor ' . $request->get('nomor')]);

        return Response()->json($arr);
    }

    public function viewPageProduksiRequestUjiSample(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('produksi_request_uji_sample');
        }
    }

    public function viewProduksiRequestUjiSampleTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('uji_sample')->select('nomor_uji_sample as nomor', 'tanggal', 'cus.custname', 'merk', 'tipe', 'status', 'status_uji_data_produksi')->join('customers as cus', 'cus.custid', '=', 'uji_sample.customers')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('uji_sample')->select('nomor_uji_sample as nomor', 'tanggal', 'cus.custname', 'merk', 'tipe', 'status', 'status_uji_data_produksi')->join('customers as cus', 'cus.custid', '=', 'uji_sample.customers')->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_produksi_request_uji_sample')->rawColumns(['action'])->make(true);
        }
        return view('produksi_request_uji_sample');
    }

    public function viewPageLabRequestUjiSample(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('lab_request_uji_sample');
        }
    }

    public function viewLabRequestUjiSampleTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('uji_sample')->select('nomor_uji_sample as nomor', 'tanggal', 'status', 'status_uji_data_lab')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('uji_sample')->select('nomor_uji_sample as nomor', 'tanggal', 'status', 'status_uji_data_lab')->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_lab_request_uji_sample')->rawColumns(['action'])->make(true);
        }
        return view('lab_request_uji_sample');
    }

    public function inputLabRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $cek_data_status = DB::table('uji_sample')->select('status_uji_data_produksi')->where('nomor_uji_sample', $request->nomor)->first();

        $data_produk = ['kompetitor'];

        // $cek_data_dsgm = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->nomor)->where('produk', 2)->first();

        for($i = 1; $i <= count($data_produk); $i++){
            if(isset($request->{"cie86_" . $data_produk[$i - 1]}) || !empty($request->{"cie86_" . $data_produk[$i - 1]})){
                $cek_data_lab = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail')->where('nomor_uji_sample_detail', 'like', 'SAMPLE-DET' . $tanggal . '%')->orderBy('nomor_uji_sample_detail', 'asc')->distinct()->get();

                if(!isset($request->{"kalsium_" . $data_produk[$i - 1]}) || empty($request->{"kalsium_" . $data_produk[$i - 1]})){
                    $temp_kalsium = null;
                }else{
                    $temp_kalsium = str_replace(',', '.', $request->{"kalsium_" . $data_produk[$i - 1]});
                }

                if(!isset($request->{"ssa_" . $data_produk[$i - 1]}) || empty($request->{"ssa_" . $data_produk[$i - 1]})){
                    $request->{"ssa_" . $data_produk[$i - 1]} = 0;
                }

                if(!isset($request->{"d50_" . $data_produk[$i - 1]}) || empty($request->{"d50_" . $data_produk[$i - 1]})){
                    $request->{"d50_" . $data_produk[$i - 1]} = 0;
                }

                if(!isset($request->{"d98_" . $data_produk[$i - 1]}) || empty($request->{"d98_" . $data_produk[$i - 1]})){
                    $request->{"d98_" . $data_produk[$i - 1]} = 0;
                }

                if(!isset($request->{"cie86_" . $data_produk[$i - 1]}) || empty($request->{"cie86_" . $data_produk[$i - 1]})){
                    $request->{"cie86_" . $data_produk[$i - 1]} = 0;
                }

                if(!isset($request->{"iso2470_" . $data_produk[$i - 1]}) || empty($request->{"iso2470_" . $data_produk[$i - 1]})){
                    $request->{"iso2470_" . $data_produk[$i - 1]} = 0;
                }

                if(!isset($request->{"moisture_" . $data_produk[$i - 1]}) || empty($request->{"moisture_" . $data_produk[$i - 1]})){
                    $request->{"moisture_" . $data_produk[$i - 1]} = 0;
                }

                if(!isset($request->{"residue_" . $data_produk[$i - 1]}) || empty($request->{"residue_" . $data_produk[$i - 1]})){
                    $request->{"residue_" . $data_produk[$i - 1]} = 0;
                }

                if($cek_data_lab){
                    $data_lab_count = $cek_data_lab->count();
                    if($data_lab_count > 0){
                        $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail, 15);
                        if($data_lab_count != $num){
                            $nomor_uji_sample_detail = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail;
                        }else{
                            if($data_lab_count < 9){
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-000" . ($data_lab_count + 1);
                            }else if($data_lab_count >= 9 && $data_lab_count < 99){
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-00" . ($data_lab_count + 1);
                            }else if($data_lab_count >= 99 && $data_lab_count < 999){
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0" . ($data_lab_count + 1);
                            }else{
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-" . ($data_lab_count + 1);
                            }
                        }
                    }else{
                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                    }
                }else{
                    $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                }

                if($i == 1){
                    $data_merk = DB::table('uji_sample')->select('merk', 'tipe')->where('nomor_uji_sample', $request->nomor)->first();

                    $data_lab = DB::table('uji_sample_detail')->insert(["nomor_uji_sample_detail" => $nomor_uji_sample_detail, "nomor_uji_sample" => $request->nomor, "produk" => $i, "jenis_produk" => $data_merk->merk . ' ' . $data_merk->tipe, "kalsium" => $temp_kalsium, "ssa" => $request->{"ssa_" . $data_produk[$i - 1]}, "d50" => str_replace(',', '.', $request->{"d50_" . $data_produk[$i - 1]}), "d98" => str_replace(',', '.', $request->{"d98_" . $data_produk[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"cie86_" . $data_produk[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"iso2470_" . $data_produk[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"moisture_" . $data_produk[$i - 1]}), "residue" => str_replace(',', '.', $request->{"residue_" . $data_produk[$i - 1]})]);
                }else{
                    $data_lab = DB::table('uji_sample_detail')->insert(["nomor_uji_sample_detail" => $nomor_uji_sample_detail, "nomor_uji_sample" => $request->nomor, "produk" => $i, "jenis_produk" => $request->{"produk_" . $data_produk[$i - 1]}, "kalsium" => $temp_kalsium, "ssa" => $request->{"ssa_" . $data_produk[$i - 1]}, "d50" => str_replace(',', '.', $request->{"d50_" . $data_produk[$i - 1]}), "d98" => str_replace(',', '.', $request->{"d98_" . $data_produk[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"cie86_" . $data_produk[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"iso2470_" . $data_produk[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"moisture_" . $data_produk[$i - 1]}), "residue" => str_replace(',', '.', $request->{"residue_" . $data_produk[$i - 1]})]);
                }
            }
        }

        if($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $nama_file = time()."_Lampiran_Uji_Sample_".$request->nomor."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->nomor)->update(['tanggal_analisa' => date('Y-m-d'), 'analisa' => $request->analisa, 'solusi' => $request->solusi, 'lampiran' => $nama_file]);
        }else{
            $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->nomor)->update(['tanggal_analisa' => date('Y-m-d'), 'analisa' => $request->analisa, 'solusi' => $request->solusi]);
        }
        
        if($cek_data_status->status_uji_data_produksi == 0){
            $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->nomor)->update(['tanggal_pengujian_sample' => date('Y-m-d'), 'status' => 1, 'status_uji_data_lab' => 1]);
        }else{
            $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->nomor)->update(['tanggal_pengujian_sample' => date('Y-m-d'), 'status' => 2, 'status_uji_data_lab' => 1]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Lab Update Analisa dan Input Data Pengujian Sample Nomor ' . $request->nomor]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function inputProduksiRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $cek_data_status = DB::table('uji_sample')->select('status_uji_data_lab')->where('nomor_uji_sample', $request->nomor)->first();

        $data_produk = ['dsgm'];

        // $cek_data_dsgm = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->nomor)->where('produk', 2)->first();

        for($i = 1; $i <= count($data_produk); $i++){
            $number = count($request->produk_pembanding);

            for($j = 0; $j < $number; $j++){
                if(isset($request->{"cie86_" . $data_produk[$i - 1]}[$j]) || !empty($request->{"cie86_" . $data_produk[$i - 1]}[$j])){
                    $cek_data_lab = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail')->where('nomor_uji_sample_detail', 'like', 'SAMPLE-DET' . $tanggal . '%')->orderBy('nomor_uji_sample_detail', 'asc')->distinct()->get();

                    if(!isset($request->{"kalsium_" . $data_produk[$i - 1]}[$j]) || empty($request->{"kalsium_" . $data_produk[$i - 1]}[$j])){
                        $temp_kalsium = null;
                    }else{
                        $temp_kalsium = str_replace(',', '.', $request->{"kalsium_" . $data_produk[$i - 1]}[$j]);
                    }

                    if(!isset($request->{"ssa_" . $data_produk[$i - 1]}[$j]) || empty($request->{"ssa_" . $data_produk[$i - 1]}[$j])){
                        $request->{"ssa_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"d50_" . $data_produk[$i - 1]}[$j]) || empty($request->{"d50_" . $data_produk[$i - 1]}[$j])){
                        $request->{"d50_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"d98_" . $data_produk[$i - 1]}[$j]) || empty($request->{"d98_" . $data_produk[$i - 1]}[$j])){
                        $request->{"d98_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"cie86_" . $data_produk[$i - 1]}[$j]) || empty($request->{"cie86_" . $data_produk[$i - 1]}[$j])){
                        $request->{"cie86_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"iso2470_" . $data_produk[$i - 1]}[$j]) || empty($request->{"iso2470_" . $data_produk[$i - 1]}[$j])){
                        $request->{"iso2470_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"moisture_" . $data_produk[$i - 1]}[$j]) || empty($request->{"moisture_" . $data_produk[$i - 1]}[$j])){
                        $request->{"moisture_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"residue_" . $data_produk[$i - 1]}[$j]) || empty($request->{"residue_" . $data_produk[$i - 1]}[$j])){
                        $request->{"residue_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if($cek_data_lab){
                        $data_lab_count = $cek_data_lab->count();
                        if($data_lab_count > 0){
                            $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail, 15);
                            if($data_lab_count != $num){
                                $nomor_uji_sample_detail = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail;
                            }else{
                                if($data_lab_count < 9){
                                    $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-000" . ($data_lab_count + 1);
                                }else if($data_lab_count >= 9 && $data_lab_count < 99){
                                    $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-00" . ($data_lab_count + 1);
                                }else if($data_lab_count >= 99 && $data_lab_count < 999){
                                    $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0" . ($data_lab_count + 1);
                                }else{
                                    $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-" . ($data_lab_count + 1);
                                }
                            }
                        }else{
                            $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                        }
                    }else{
                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                    }

                    
                    $data_lab = DB::table('uji_sample_detail')->insert(["nomor_uji_sample_detail" => $nomor_uji_sample_detail, "nomor_uji_sample" => $request->nomor, "produk" => 2, "jenis_produk" => $request->produk_pembanding[$j], "kalsium" => $temp_kalsium, "ssa" => $request->{"ssa_" . $data_produk[$i - 1]}[$j], "d50" => str_replace(',', '.', $request->{"d50_" . $data_produk[$i - 1]}[$j]), "d98" => str_replace(',', '.', $request->{"d98_" . $data_produk[$i - 1]}[$j]), "cie86" => str_replace(',', '.', $request->{"cie86_" . $data_produk[$i - 1]}[$j]), "iso2470" => str_replace(',', '.', $request->{"iso2470_" . $data_produk[$i - 1]}[$j]), "moisture" => str_replace(',', '.', $request->{"moisture_" . $data_produk[$i - 1]}[$j]), "residue" => str_replace(',', '.', $request->{"residue_" . $data_produk[$i - 1]}[$j])]);
                }
            }
        }

        if($cek_data_status->status_uji_data_lab == 0){
            $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->nomor)->update(['komentar_produksi' => $request->komentar, 'tanggal_pengujian_sample' => date('Y-m-d'), 'status' => 1, 'status_uji_data_produksi' => 1]);
        }else{
            $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->nomor)->update(['komentar_produksi' => $request->komentar, 'tanggal_pengujian_sample' => date('Y-m-d'), 'status' => 2, 'status_uji_data_produksi' => 1]);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Produksi Update dan Input Data Pengujian Sample Nomor ' . $request->nomor]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function viewUjiSampleProdukKompetitor($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('uji_sample')->select('merk', 'tipe')->where('nomor_uji_sample', $val_nomor)->first();

        return Response()->json($data);
    }

    public function viewLabRequestUjiSample($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('uji_sample_detail as uji_det')->select('uji_det.produk', 'uji_det.jenis_produk', 'uji_det.kalsium', 'uji_det.ssa', 'uji_det.d50', 'uji_det.d98', 'uji_det.cie86', 'uji_det.iso2470', 'uji_det.moisture', 'uji_det.residue', 'uji_det.harga', 'uji_det.kelas')->join("uji_sample as uji", "uji.nomor_uji_sample", "=", "uji_det.nomor_uji_sample")->where('uji_det.nomor_uji_sample', $val_nomor)->orderBy('uji_det.produk', 'asc')->get();

        $count_data = DB::table('uji_sample_detail as uji_det')->where('uji_det.produk', 2)->where('uji_det.nomor_uji_sample', $val_nomor)->orderBy('uji_det.produk', 'asc')->get();

        $arrayForTable = [];
        foreach($data as $data){
            if(!isset($arrayForTable['Produk'])){
                $arrayForTable['Produk'] = [];
                $arrayForTable['Produk'][] = $data->jenis_produk;
            }else{
                $arrayForTable['Produk'][] = $data->jenis_produk;
            }
            if(!isset($arrayForTable['Kalsium'])){
                $arrayForTable['Kalsium'] = [];
                $arrayForTable['Kalsium'][] = $data->kalsium;
            }else{
                $arrayForTable['Kalsium'][] = $data->kalsium;
            }
            if(!isset($arrayForTable['SSA'])){
                $arrayForTable['SSA'] = [];
                $arrayForTable['SSA'][] = $data->ssa;
            }else{
                $arrayForTable['SSA'][] = $data->ssa;
            }
            if(!isset($arrayForTable['D50'])){
                $arrayForTable['D50'] = [];
                $arrayForTable['D50'][] = $data->d50;
            }else{
                $arrayForTable['D50'][] = $data->d50;
            }
            if(!isset($arrayForTable['D98'])){
                $arrayForTable['D98'] = [];
                $arrayForTable['D98'][] = $data->d98;
            }else{
                $arrayForTable['D98'][] = $data->d98;
            }
            if(!isset($arrayForTable['CIE86'])){
                $arrayForTable['CIE86'] = [];
                $arrayForTable['CIE86'][] = $data->cie86;
            }else{
                $arrayForTable['CIE86'][] = $data->cie86;
            }
            if(!isset($arrayForTable['ISO2470'])){
                $arrayForTable['ISO2470'] = [];
                $arrayForTable['ISO2470'][] = $data->iso2470;
            }else{
                $arrayForTable['ISO2470'][] = $data->iso2470;
            }
            if(!isset($arrayForTable['Moisture'])){
                $arrayForTable['Moisture'] = [];
                $arrayForTable['Moisture'][] = $data->moisture;
            }else{
                $arrayForTable['Moisture'][] = $data->moisture;
            }
            if(!isset($arrayForTable['Residue'])){
                $arrayForTable['Residue'] = [];
                $arrayForTable['Residue'][] = $data->residue;
            }else{
                $arrayForTable['Residue'][] = $data->residue;
            }
            if(!isset($arrayForTable['Harga'])){
                $arrayForTable['Harga'] = [];
                $arrayForTable['Harga'][] = $data->harga;
            }else{
                $arrayForTable['Harga'][] = $data->harga;
            }
            if(!isset($arrayForTable['Kelas'])){
                $arrayForTable['Kelas'] = [];
                $arrayForTable['Kelas'][] = $data->kelas;
            }else{
                $arrayForTable['Kelas'][] = $data->kelas;
            }
        }

        return Response()->json([$arrayForTable, count($count_data)]);
    }

    public function viewLabRequestUjiSampleDetail($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('uji_sample_detail as uji_det')->select('uji_det.produk', 'uji_det.jenis_produk', 'uji_det.kalsium', 'uji_det.ssa', 'uji_det.d50', 'uji_det.d98', 'uji_det.cie86', 'uji_det.iso2470', 'uji_det.moisture', 'uji_det.residue', 'uji_det.harga')->join("uji_sample as uji", "uji.nomor_uji_sample", "=", "uji_det.nomor_uji_sample")->where('uji_det.nomor_uji_sample', $val_nomor)->where('uji_det.produk', 1)->orderBy('uji_det.produk', 'asc')->get();

        $data_analisa = DB::table('uji_sample')->select('analisa', 'solusi', 'lampiran')->where('nomor_uji_sample', $val_nomor)->first();

        $arrayForTable = [];
        foreach($data as $data){
            $temp = [];
            $temp['jenis_produk'] = $data->jenis_produk;
            $temp['produk'] = $data->produk;
            $temp['kalsium'] = $data->kalsium;
            $temp['ssa'] = $data->ssa;
            $temp['d50'] = $data->d50;
            $temp['d98'] = $data->d98;
            $temp['cie86'] = $data->cie86;
            $temp['iso2470'] = $data->iso2470;
            $temp['moisture'] = $data->moisture;
            $temp['residue'] = $data->residue;
            $temp['harga'] = $data->harga;
            if(!isset($arrayForTable[$data->produk])){
                $arrayForTable[$data->produk] = [];
            }
            $arrayForTable[$data->produk][] = $temp;
        }

        return Response()->json([$arrayForTable, $data_analisa]);
    }

    public function editLabRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_produk = ['kompetitor'];

        for($i = 1; $i <= count($data_produk); $i++){
            if($i == 2){
                if(isset($request->{"edit_cie86_" . $data_produk[$i - 1]}) || !empty($request->{"edit_cie86_" . $data_produk[$i - 1]})){
                    if(!isset($request->{"edit_kalsium_" . $data_produk[$i - 1]}) || empty($request->{"edit_kalsium_" . $data_produk[$i - 1]})){
                        $temp_kalsium = null;
                    }else{
                        $temp_kalsium = str_replace(',', '.', $request->{"edit_d50_" . $data_produk[$i - 1]});
                    }

                    $cek_data_exist = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('produk', $i)->first();

                    $data_lab = DB::table('uji_sample_detail')->where('nomor_uji_sample_detail', $cek_data_exist->nomor_uji_sample_detail)->update(["jenis_produk" => $request->{"edit_produk_" . $data_produk[$i - 1]}, "kalsium" => $temp_kalsium]);
                }
            }else{
                if(isset($request->{"edit_cie86_" . $data_produk[$i - 1]}) || !empty($request->{"edit_cie86_" . $data_produk[$i - 1]})){
                    if(!isset($request->{"edit_kalsium_" . $data_produk[$i - 1]}) || empty($request->{"edit_kalsium_" . $data_produk[$i - 1]})){
                        $temp_kalsium = null;
                    }else{
                        $temp_kalsium = str_replace(',', '.', $request->{"edit_kalsium_" . $data_produk[$i - 1]});
                    }

                    if(!isset($request->{"edit_ssa_" . $data_produk[$i - 1]}) || empty($request->{"edit_ssa_" . $data_produk[$i - 1]})){
                        $request->{"edit_ssa_" . $data_produk[$i - 1]} = 0;
                    }

                    if(!isset($request->{"edit_d50_" . $data_produk[$i - 1]}) || empty($request->{"edit_d50_" . $data_produk[$i - 1]})){
                        $request->{"edit_d50_" . $data_produk[$i - 1]} = 0;
                    }

                    if(!isset($request->{"edit_d98_" . $data_produk[$i - 1]}) || empty($request->{"edit_d98_" . $data_produk[$i - 1]})){
                        $request->{"edit_d98_" . $data_produk[$i - 1]} = 0;
                    }

                    if(!isset($request->{"edit_cie86_" . $data_produk[$i - 1]}) || empty($request->{"edit_cie86_" . $data_produk[$i - 1]})){
                        $request->{"edit_cie86_" . $data_produk[$i - 1]} = 0;
                    }

                    if(!isset($request->{"edit_iso2470_" . $data_produk[$i - 1]}) || empty($request->{"edit_iso2470_" . $data_produk[$i - 1]})){
                        $request->{"edit_iso2470_" . $data_produk[$i - 1]} = 0;
                    }

                    if(!isset($request->{"edit_moisture_" . $data_produk[$i - 1]}) || empty($request->{"edit_moisture_" . $data_produk[$i - 1]})){
                        $request->{"edit_moisture_" . $data_produk[$i - 1]} = 0;
                    }

                    if(!isset($request->{"edit_residue_" . $data_produk[$i - 1]}) || empty($request->{"edit_residue_" . $data_produk[$i - 1]})){
                        $request->{"edit_residue_" . $data_produk[$i - 1]} = 0;
                    }

                    $cek_data_exist = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('produk', $i)->first();

                    if($cek_data_exist){
                        if($i == 1){
                            $data_lab = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('produk', $i)->update(["kalsium" => $temp_kalsium, "ssa" => $request->{"edit_ssa_" . $data_produk[$i - 1]}, "d50" => str_replace(',', '.', $request->{"edit_d50_" . $data_produk[$i - 1]}), "d98" => str_replace(',', '.', $request->{"edit_d98_" . $data_produk[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"edit_cie86_" . $data_produk[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"edit_iso2470_" . $data_produk[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"edit_moisture_" . $data_produk[$i - 1]}), "residue" => str_replace(',', '.', $request->{"edit_residue_" . $data_produk[$i - 1]})]);
                        }else{
                            $data_lab = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('produk', $i)->update(["jenis_produk" => $request->{"edit_produk_" . $data_produk[$i - 1]}, "kalsium" => $temp_kalsium, "ssa" => $request->{"edit_ssa_" . $data_produk[$i - 1]}, "d50" => str_replace(',', '.', $request->{"edit_d50_" . $data_produk[$i - 1]}), "d98" => str_replace(',', '.', $request->{"edit_d98_" . $data_produk[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"edit_cie86_" . $data_produk[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"edit_iso2470_" . $data_produk[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"edit_moisture_" . $data_produk[$i - 1]}), "residue" => str_replace(',', '.', $request->{"edit_residue_" . $data_produk[$i - 1]})]);
                        }
                    }else{
                        $cek_data_lab = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail')->where('nomor_uji_sample_detail', 'like', 'SAMPLE-DET' . $tanggal . '%')->orderBy('nomor_uji_sample_detail', 'asc')->distinct()->get();

                        if($cek_data_lab){
                            $data_lab_count = $cek_data_lab->count();
                            if($data_lab_count > 0){
                                $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail, 15);
                                if($data_lab_count != $num){
                                    $nomor_uji_sample_detail = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail;
                                }else{
                                    if($data_lab_count < 9){
                                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-000" . ($data_lab_count + 1);
                                    }else if($data_lab_count >= 9 && $data_lab_count < 99){
                                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-00" . ($data_lab_count + 1);
                                    }else if($data_lab_count >= 99 && $data_lab_count < 999){
                                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0" . ($data_lab_count + 1);
                                    }else{
                                        $nomor_uji_sample_detail = "SAMPLE-DET-" . $tanggal . ($data_lab_count + 1);
                                    }
                                }
                            }else{
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                            }
                        }else{
                            $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                        }

                        if($i == 1){
                            $data_merk = DB::table('uji_sample')->select('merk', 'tipe')->where('nomor_uji_sample', $request->nomor)->first();

                            $data_lab = DB::table('uji_sample_detail')->insert(["nomor_uji_sample_detail" => $nomor_uji_sample_detail, "nomor_uji_sample" => $request->edit_nomor, "produk" => $i, "jenis_produk" => $data_merk->merk . ' ' . $data_merk->tipe, "kalsium" => $temp_kalsium, "ssa" => $request->{"edit_ssa_" . $data_produk[$i - 1]}, "d50" => str_replace(',', '.', $request->{"edit_d50_" . $data_produk[$i - 1]}), "d98" => str_replace(',', '.', $request->{"edit_d98_" . $data_produk[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"edit_cie86_" . $data_produk[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"edit_iso2470_" . $data_produk[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"edit_moisture_" . $data_produk[$i - 1]}), "residue" => str_replace(',', '.', $request->{"edit_residue_" . $data_produk[$i - 1]})]);
                        }else{
                            $data_lab = DB::table('uji_sample_detail')->insert(["nomor_uji_sample_detail" => $nomor_uji_sample_detail, "nomor_uji_sample" => $request->edit_nomor, "produk" => $i, "jenis_produk" => $request->{"edit_produk_" . $data_produk[$i - 1]}, "kalsium" => $temp_kalsium, "ssa" => $request->{"edit_ssa_" . $data_produk[$i - 1]}, "d50" => str_replace(',', '.', $request->{"edit_d50_" . $data_produk[$i - 1]}), "d98" => str_replace(',', '.', $request->{"edit_d98_" . $data_produk[$i - 1]}), "cie86" => str_replace(',', '.', $request->{"edit_cie86_" . $data_produk[$i - 1]}), "iso2470" => str_replace(',', '.', $request->{"edit_iso2470_" . $data_produk[$i - 1]}), "moisture" => str_replace(',', '.', $request->{"edit_moisture_" . $data_produk[$i - 1]}), "residue" => str_replace(',', '.', $request->{"edit_residue_" . $data_produk[$i - 1]})]);
                        }
                    }
                }
            }
        }

        if($request->hasFile('edit_lampiran')) {
            $data_lampiran = DB::table('uji_sample')->select('lampiran')->where('nomor_uji_sample', $request->edit_nomor)->first();
            File::delete('data_file/' . $data_lampiran->lampiran);

            $file = $request->file('edit_lampiran');
            $nama_file = time()."_Lampiran_Uji_Sample_".$request->edit_nomor."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->edit_nomor)->update(['analisa' => $request->edit_analisa, 'solusi' => $request->edit_solusi, 'lampiran' => $nama_file]);
        }else{
            $data = DB::table('uji_sample')->where('nomor_uji_sample', $request->edit_nomor)->update(['analisa' => $request->edit_analisa, 'solusi' => $request->edit_solusi]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Produksi Edit Analisa Data Uji Sample Nomor ' . $request->edit_update_nomor]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Lab Edit Data Pengujian Sample Nomor ' . $request->edit_nomor]);

        if($data_lab){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function editProduksiRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $tanggal = date('ym');

        $data_produk = ['dsgm'];

        DB::table('uji_sample')->where('nomor_uji_sample', $request->edit_nomor)->update(["komentar_produksi" => $request->edit_komentar]);

        for($i = 1; $i <= count($data_produk); $i++){
            $number = count($request->edit_produk_pembanding_lama);
            for($j = 0; $j < $number; $j++){
                if(isset($request->{"edit_cie86_" . $data_produk[$i - 1]}[$j]) || !empty($request->{"edit_cie86_" . $data_produk[$i - 1]}[$j])){
                    if(!isset($request->{"edit_kalsium_" . $data_produk[$i - 1]}[$j]) || empty($request->{"edit_kalsium_" . $data_produk[$i - 1]}[$j])){
                        $temp_kalsium = null;
                    }else{
                        $temp_kalsium = str_replace(',', '.', $request->{"edit_kalsium_" . $data_produk[$i - 1]}[$j]);
                    }

                    if(!isset($request->{"edit_ssa_" . $data_produk[$i - 1]}[$j]) || empty($request->{"edit_ssa_" . $data_produk[$i - 1]}[$j])){
                        $request->{"edit_ssa_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"edit_d50_" . $data_produk[$i - 1]}[$j]) || empty($request->{"edit_d50_" . $data_produk[$i - 1]}[$j])){
                        $request->{"edit_d50_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"edit_d98_" . $data_produk[$i - 1]}[$j]) || empty($request->{"edit_d98_" . $data_produk[$i - 1]}[$j])){
                        $request->{"edit_d98_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"edit_cie86_" . $data_produk[$i - 1]}[$j]) || empty($request->{"edit_cie86_" . $data_produk[$i - 1]}[$j])){
                        $request->{"edit_cie86_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"edit_iso2470_" . $data_produk[$i - 1]}[$j]) || empty($request->{"edit_iso2470_" . $data_produk[$i - 1]}[$j])){
                        $request->{"edit_iso2470_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"edit_moisture_" . $data_produk[$i - 1]}[$j]) || empty($request->{"edit_moisture_" . $data_produk[$i - 1]}[$j])){
                        $request->{"edit_moisture_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    if(!isset($request->{"edit_residue_" . $data_produk[$i - 1]}[$j]) || empty($request->{"edit_residue_" . $data_produk[$i - 1]}[$j])){
                        $request->{"edit_residue_" . $data_produk[$i - 1]}[$j] = 0;
                    }

                    $cek_data_exist = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('jenis_produk', $request->edit_produk_pembanding_lama[$j])->first();

                    if($cek_data_exist){
                        $data_lab = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('produk', 2)->where("jenis_produk", $request->edit_produk_pembanding_lama[$j])->update(["jenis_produk" => $request->edit_produk_pembanding[$j], "kalsium" => $temp_kalsium, "ssa" => $request->{"edit_ssa_" . $data_produk[$i - 1]}[$j], "d50" => str_replace(',', '.', $request->{"edit_d50_" . $data_produk[$i - 1]}[$j]), "d98" => str_replace(',', '.', $request->{"edit_d98_" . $data_produk[$i - 1]}[$j]), "cie86" => str_replace(',', '.', $request->{"edit_cie86_" . $data_produk[$i - 1]}[$j]), "iso2470" => str_replace(',', '.', $request->{"edit_iso2470_" . $data_produk[$i - 1]}[$j]), "moisture" => str_replace(',', '.', $request->{"edit_moisture_" . $data_produk[$i - 1]}[$j]), "residue" => str_replace(',', '.', $request->{"edit_residue_" . $data_produk[$i - 1]}[$j])]);
                    }else{
                        $cek_data_lab = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail')->where('nomor_uji_sample_detail', 'like', 'SAMPLE-DET' . $tanggal . '%')->orderBy('nomor_uji_sample_detail', 'asc')->distinct()->get();

                        if($cek_data_lab){
                            $data_lab_count = $cek_data_lab->count();
                            if($data_lab_count > 0){
                                $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail, 15);
                                if($data_lab_count != $num){
                                    $nomor_uji_sample_detail = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail;
                                }else{
                                    if($data_lab_count < 9){
                                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-000" . ($data_lab_count + 1);
                                    }else if($data_lab_count >= 9 && $data_lab_count < 99){
                                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-00" . ($data_lab_count + 1);
                                    }else if($data_lab_count >= 99 && $data_lab_count < 999){
                                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0" . ($data_lab_count + 1);
                                    }else{
                                        $nomor_uji_sample_detail = "SAMPLE-DET-" . $tanggal . ($data_lab_count + 1);
                                    }
                                }
                            }else{
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                            }
                        }else{
                            $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                        }

                        
                        $data_lab = DB::table('uji_sample_detail')->insert(["nomor_uji_sample_detail" => $nomor_uji_sample_detail, "nomor_uji_sample" => $request->edit_nomor, "produk" => 2, "jenis_produk" => $request->edit_produk_pembanding_lama[$j], "kalsium" => $temp_kalsium, "ssa" => $request->{"edit_ssa_" . $data_produk[$i - 1]}[$j], "d50" => str_replace(',', '.', $request->{"edit_d50_" . $data_produk[$i - 1]}[$j]), "d98" => str_replace(',', '.', $request->{"edit_d98_" . $data_produk[$i - 1]}[$j]), "cie86" => str_replace(',', '.', $request->{"edit_cie86_" . $data_produk[$i - 1]}[$j]), "iso2470" => str_replace(',', '.', $request->{"edit_iso2470_" . $data_produk[$i - 1]}[$j]), "moisture" => str_replace(',', '.', $request->{"edit_moisture_" . $data_produk[$i - 1]}[$j]), "residue" => str_replace(',', '.', $request->{"edit_residue_" . $data_produk[$i - 1]}[$j])]);
                    }
                }
            }
        }

        DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->edit_nomor)->where('produk', 2)->whereNotIn('jenis_produk', $request->edit_produk_pembanding_lama)->delete();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Produksi Edit Data Pengujian Sample Nomor ' . $request->edit_nomor]);

        if($data_lab){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function viewUjiSampleLabBatchLaporanHarian($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $data = DB::table('laporan_hasil_produksi')->select('jam_waktu')->join('laporan_hasil_produksi_lab', 'laporan_hasil_produksi_lab.nomor_laporan_produksi', '=', 'laporan_hasil_produksi.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->distinct()->get();
        
        return Response()->json($data);
    }

    public function cekUjiSampleLabDataLaporanHarian($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('uji_sample_detail')->where('nomor_uji_sample', $val_nomor)->where('produk', 2)->first();
        
        return Response()->json($data);
    }

    public function viewUjiSampleLabDataLaporanHarian(Request $request){
        if(request()->ajax()){
            $data = DB::table('laporan_hasil_produksi_lab')->select(DB::raw("(select nomor_uji_sample from uji_sample where nomor_uji_sample = '$request->nomor_uji') as nomor_uji_sample"), 'nomor_laporan_produksi_lab', 'tbl_mesin.name as nama_mesin', 'rpm', 'mesh', 'ssa', 'd50', 'd98', 'cie86', 'iso2470', 'moisture', 'residue')->join('laporan_hasil_produksi', 'laporan_hasil_produksi_lab.nomor_laporan_produksi', '=', 'laporan_hasil_produksi.nomor_laporan_produksi')->join('tbl_mesin', 'tbl_mesin.id', '=', 'laporan_hasil_produksi_lab.mesin')->where('tanggal_laporan_produksi', $request->tanggal)->where('jam_waktu', $request->waktu)->get();

            return datatables()->of($data)->make(true);
        }
        return view('permintaan_sample_lab');
    }

    public function cariUjiSampleLab(Request $request){
        $nomor_uji = $request->get('nomor_uji');
        $nomor_lab = $request->get('nomor_lab');
        $dipilih = $request->get('dipilih');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nomor_lab as $no) {
            if(is_array($dipilih) && array_key_exists($no,$dipilih)){
                $tanggal = date('ym');

                $cek_data_lab = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail')->where('nomor_uji_sample_detail', 'like', 'SAMPLE-DET' . $tanggal . '%')->orderBy('nomor_uji_sample_detail', 'asc')->distinct()->get();

                if($cek_data_lab){
                    $data_lab_count = $cek_data_lab->count();
                    if($data_lab_count > 0){
                        $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail, 15);
                        if($data_lab_count != $num){
                            $nomor_uji_sample_detail = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_uji_sample_detail;
                        }else{
                            if($data_lab_count < 9){
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-000" . ($data_lab_count + 1);
                            }else if($data_lab_count >= 9 && $data_lab_count < 99){
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-00" . ($data_lab_count + 1);
                            }else if($data_lab_count >= 99 && $data_lab_count < 999){
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0" . ($data_lab_count + 1);
                            }else{
                                $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-" . ($data_lab_count + 1);
                            }
                        }
                    }else{
                        $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                    }
                }else{
                    $nomor_uji_sample_detail = "SAMPLE-DET" . $tanggal . "-0001";
                }

                $data_lab = DB::table('laporan_hasil_produksi_lab')->where('nomor_laporan_produksi_lab', $no)->first();

                $data = DB::table('uji_sample_detail')->insert(['nomor_uji_sample_detail' => $nomor_uji_sample_detail, 'nomor_uji_sample' => $nomor_uji[$no], 'nomor_laporan_lab' => $no, 'produk' => 2, 'ssa' => $data_lab->ssa, 'd50' => str_replace(',', '.', $data_lab->d50), 'd98' => str_replace(',', '.', $data_lab->d98), 'cie86' => str_replace(',', '.', $data_lab->cie86), 'iso2470' => str_replace(',', '.', $data_lab->iso2470), 'moisture' => str_replace(',', '.', $data_lab->moisture), 'residue' => str_replace(',', '.', $data_lab->residue)]);

                if($data){
                    $arr = array('msg' => 'Data Successfully Added', 'status' => true);
                }
            }
        }

        return Response()->json();
    }

    public function cariEditUjiSampleLab(Request $request){
        $nomor_uji = $request->get('edit_nomor_uji');
        $nomor_lab = $request->get('edit_nomor_lab');
        $dipilih = $request->get('edit_dipilih');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nomor_lab as $no) {
            if(is_array($dipilih) && array_key_exists($no,$dipilih)){
                $tanggal = date('ym');

                $data_uji = DB::table('uji_sample_detail')->where('nomor_uji_sample', $nomor_uji[$no])->where('produk', 2)->first();

                $data_lab = DB::table('laporan_hasil_produksi_lab')->where('nomor_laporan_produksi_lab', $no)->first();

                $data = DB::table('uji_sample_detail')->where('nomor_uji_sample_detail', $data_uji->nomor_uji_sample_detail)->update(['nomor_laporan_lab' => $no, 'ssa' => $data_lab->ssa, 'd50' => str_replace(',', '.', $data_lab->d50), 'd98' => str_replace(',', '.', $data_lab->d98), 'cie86' => str_replace(',', '.', $data_lab->cie86), 'iso2470' => str_replace(',', '.', $data_lab->iso2470), 'moisture' => str_replace(',', '.', $data_lab->moisture), 'residue' => str_replace(',', '.', $data_lab->residue)]);

                if($data){
                    $arr = array('msg' => 'Data Successfully Added', 'status' => true);
                }
            }
        }

        return Response()->json();
    }

    public function inputHargaRequestUjiSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $cek_data = DB::table('uji_sample')->select('status')->where('nomor_uji_sample', $request->update_nomor)->first();

        DB::table('uji_sample')->where('nomor_uji_sample', $request->update_nomor)->update(['status' => 3]);

        $data_produk = ['kompetitor', 'dsgm'];

        for($i = 1; $i <= count($data_produk); $i++){
            if($i == 1){
                if(isset($request->{"harga_" . $data_produk[$i - 1]}) || !empty($request->{"harga_" . $data_produk[$i - 1]})){
                    $data_harga = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->update_nomor)->where('produk', $i)->update(["harga" => $request->{"harga_" . $data_produk[$i - 1]}, "kelas" => $request->{"kelas_" . $data_produk[$i - 1]}]);
                }
            }else{
                $number = count($request->{"harga_" . $data_produk[$i - 1]});
                for($j = 0; $j < $number; $j++){
                    if(isset($request->{"harga_" . $data_produk[$i - 1]}[$j]) || !empty($request->{"harga_" . $data_produk[$i - 1]}[$j])){
                        $data_harga = DB::table('uji_sample_detail')->where('nomor_uji_sample', $request->update_nomor)->where('jenis_produk', $request->{"jenis_produk_" . $data_produk[$i - 1]}[$j])->update(["harga" => $request->{"harga_" . $data_produk[$i - 1]}[$j], "kelas" => $request->{"kelas_" . $data_produk[$i - 1]}[$j]]);
                    }
                }
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_uji_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 3, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Update Harga Data Pengujian Sample Nomor ' . $request->update_nomor]);

        if($data_harga){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function printRequestUjiSample($nomor){
        $val_nomor = Crypt::decrypt($nomor);

        $data = DB::table('uji_sample')->select('nomor_uji_sample', 'tanggal', 'cus.custname', 'cus.bidang_usaha', 'merk', 'tipe', 'analisa', 'solusi', 'lampiran', 'tanggal_validasi_sales', 'tanggal_pengujian_sample', 'tanggal_analisa', 'komentar_produksi')->join('customers as cus', 'cus.custid', '=', 'uji_sample.customers')->where('nomor_uji_sample', $val_nomor)->first();

        $detail = DB::table('uji_sample_detail')->select('nomor_uji_sample_detail', 'produk', 'jenis_produk', 'kalsium', 'ssa', 'd50', 'd98', 'cie86', 'iso2470', 'moisture', 'residue', 'harga', 'kelas')->where('nomor_uji_sample', $val_nomor)->orderBy('produk', 'asc')->get();

        $data_count = DB::table('uji_sample_detail')->select('jenis_produk')->where('nomor_uji_sample', $val_nomor)->where('produk', 2)->get();

        $pdf = PDF::loadView('print_form_uji_sample', ['data' => $data, 'detail' => $detail, 'data_count' => count($data_count)])->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function viewPagePermintaanSample(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('permintaan_sample');
        }
    }

    public function inputPermintaanSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $bln = $array_bln[date('n', strtotime($request->tanggal))];

        $kode_permintaan_sample = "/03/SAMPLE/" . $bln . "/" . date('Y');

        $data_permintaan = DB::table('permintaan_sample')->select('nomor_permintaan_sample')->where('nomor_permintaan_sample', 'like', '%' . $kode_permintaan_sample . '%')->orderBy('nomor_permintaan_sample', 'asc')->distinct()->get();

        if($data_permintaan){
            $permintaan_count = $data_permintaan->count();
            if($permintaan_count > 0){
                $num = (int) substr($data_permintaan[$data_permintaan->count() - 1]->nomor_permintaan_sample, 0, 2);
                if($permintaan_count != $num){
                    $kode_permintaan_sample = ++$data_permintaan[$data_permintaan->count() - 1]->nomor_permintaan_sample;
                }else{
                    if($permintaan_count < 9){
                        $kode_permintaan_sample = "0" . ($permintaan_count + 1) . "" . $kode_permintaan_sample;
                    }else{
                        $kode_permintaan_sample = ($permintaan_count + 1) . "" . $kode_permintaan_sample;
                    }
                }
            }else{
                $kode_permintaan_sample = "01" . $kode_permintaan_sample;
            }
        }else{
            $kode_permintaan_sample = "01" . $kode_permintaan_sample;
        }

        if(isset($request->new_customers)){
            $data_kota = ModelKota::where('id_kota', $request->city)->first();
            $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
            $nama_user = strtoupper(str_replace(' ', '', $request->new_customers));
            $kode_nama = substr($nama_user, 0, 5);

            $kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;
            $data_custid = ModelCustomers::where('custid', 'like', '%' . $kode_cust . '%')->orderBy('custid', 'asc')->get();

            if($data_custid){
                $data_count = $data_custid->count();
                if($data_count < 9){
                    $kode_cust = $kode_cust . "0" . ($data_count + 1);
                }else{
                    $kode_cust = $kode_cust . ($data_count + 1);
                }
            }else{
                $kode_cust = $kode_cust . "01";
            }

            $data_cust =  new ModelCustomers();
            $data_cust->custid = $kode_cust;
            $data_cust->custname = $request->new_customers;
            $data_cust->city = $request->city;
            $data_cust->save();

            $data = DB::table('permintaan_sample')->insert(['nomor_permintaan_sample' => $kode_permintaan_sample, 'tanggal' => $request->tanggal, 'customers' => $kode_cust, 'status' => 1]);
        }else{
            $data = DB::table('permintaan_sample')->insert(['nomor_permintaan_sample' => $kode_permintaan_sample, 'tanggal' => $request->tanggal, 'customers' => $request->customers, 'status' => 1]);
        }

        $batu_ketak = $request->input('checkbox_batu_ketak');
        $batu_kapur = $request->input('checkbox_batu_kapur');
        $batu_lainnya = $request->input('checkbox_batu_lainnya');

        DB::table('permintaan_sample_barang')->insert(["nomor_permintaan_sample" => $kode_permintaan_sample]);

        if($batu_ketak){
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['batu_ketak' => 1]);
        }

        if($batu_kapur){
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['batu_kapur' => 1]);
        }

        if($batu_lainnya){
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['lainnya' => 1, 'text_lainnya' => $request->batu_lainnya]);
        }

        $mesh_250 = $request->input('checkbox_mesh_250');
        $mesh_325 = $request->input('checkbox_mesh_325');
        $mesh_500 = $request->input('checkbox_mesh_500');
        $mesh_800_u1 = $request->input('checkbox_mesh_800_u1');
        $mesh_800_u2 = $request->input('checkbox_mesh_800_u2');
        $mesh_800_swaa = $request->input('checkbox_mesh_800_swaa');
        $mesh_1200 = $request->input('checkbox_mesh_1200');
        $mesh_1500 = $request->input('checkbox_mesh_1500');
        $mesh_2000 = $request->input('checkbox_mesh_2000');
        $mesh_6000 = $request->input('checkbox_mesh_6000');
        $mesh_2002c = $request->input('checkbox_mesh_2002c');
        $mesh_lainnya = $request->input('checkbox_mesh_lainnya');

        DB::table('permintaan_sample_mesh')->insert(["nomor_permintaan_sample" => $kode_permintaan_sample]);

        if($mesh_250){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_250' => 1]);
        }

        if($mesh_325){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_325' => 1]);
        }

        if($mesh_500){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_500' => 1]);
        }

        if($mesh_800_u1){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_800_u1' => 1]);
        }

        if($mesh_800_u2){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_800_u2' => 1]);
        }

        if($mesh_800_swaa){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_800_swaa' => 1]);
        }

        if($mesh_1200){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_1200' => 1]);
        }

        if($mesh_1500){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_1500' => 1]);
        }

        if($mesh_2000){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_2000' => 1]);
        }

        if($mesh_6000){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_6000' => 1]);
        }

        if($mesh_2002c){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_2002c' => 1]);
        }

        if($mesh_lainnya){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['mesh_lainnya' => 1, 'text_lainnya' => $request->mesh_lainnya]);
        }

        $qty_1kg = $request->input('checkbox_qty_1kg');
        $qty_3kg = $request->input('checkbox_qty_3kg');
        $qty_5kg = $request->input('checkbox_qty_5kg');
        $qty_lainnya = $request->input('checkbox_qty_lainnya');

        DB::table('permintaan_sample_quantity')->insert(["nomor_permintaan_sample" => $kode_permintaan_sample]);

        if($qty_1kg){
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['qty_1kg' => 1]);
        }

        if($qty_3kg){
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['qty_3kg' => 1]);
        }

        if($qty_5kg){
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['qty_5kg' => 1]);
        }

        if($qty_lainnya){
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['qty_lainnya' => 1]);
        }

        $kirim_sendiri = $request->input('checkbox_kirim_sendiri');
        $ekspedisi = $request->input('checkbox_ekspedisi');

        DB::table('permintaan_sample_kirim')->insert(["nomor_permintaan_sample" => $kode_permintaan_sample, 'nama_ekspedisi' => $request->nama_ekspedisi, 'nomor_resi' => $request->nomor_resi]);

        if($kirim_sendiri){
            DB::table('permintaan_sample_kirim')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['ambil_sendiri' => 1]);
        }

        if($ekspedisi){
            DB::table('permintaan_sample_kirim')->where('nomor_permintaan_sample', $kode_permintaan_sample)->update(['ekspedisi' => 1]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Input Data Permintaan Sample Nomor ' . $kode_permintaan_sample]);

        return Response()->json($arr);
    }

    public function viewPermintaanSampleTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('permintaan_sample')->select('nomor_permintaan_sample as nomor', 'tanggal', 'cus.custname', 'status')->join('customers as cus', 'cus.custid', '=', 'permintaan_sample.customers')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('permintaan_sample')->select('nomor_permintaan_sample as nomor', 'tanggal', 'cus.custname', 'status')->join('customers as cus', 'cus.custid', '=', 'permintaan_sample.customers')->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_permintaan_sample')->rawColumns(['action'])->make(true);
        }
        return view('permintaan_sample');
    }

    public function viewPermintaanSample($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('permintaan_sample as minta')->select('minta.nomor_permintaan_sample as nomor', 'minta.tanggal', 'cus.custname', 'minta.status', 'minta.customers', 'minta.respon_customers', 'minta.lampiran_sales', 'minta.analisa', 'minta.solusi', 'minta.lampiran_produksi', 'barang.batu_ketak', 'barang.batu_kapur', 'barang.lainnya as batu_lainnya', 'barang.text_lainnya as nama_batu_lainnya', 'kirim.ambil_sendiri', 'kirim.ekspedisi', 'kirim.nama_ekspedisi', 'kirim.nomor_resi', 'qty.qty_1kg', 'qty.qty_3kg', 'qty.qty_5kg', 'qty.qty_lainnya', 'qty.text_lainnya as nama_qty_lainnya', 'mesh.mesh_250', 'mesh.mesh_325', 'mesh.mesh_500', 'mesh.mesh_800_u1', 'mesh.mesh_800_u2', 'mesh.mesh_800_swaa', 'mesh.mesh_1200', 'mesh.mesh_1500', 'mesh.mesh_2000', 'mesh.mesh_6000', 'mesh.mesh_2002c', 'mesh.mesh_lainnya', 'mesh.text_lainnya as nama_mesh_lainnya', 'minta.status')->join('customers as cus', 'cus.custid', '=', 'minta.customers')->leftJoin('permintaan_sample_barang as barang', 'barang.nomor_permintaan_sample', '=', 'minta.nomor_permintaan_sample')->leftJoin('permintaan_sample_kirim as kirim', 'kirim.nomor_permintaan_sample', '=', 'minta.nomor_permintaan_sample')->leftJoin('permintaan_sample_mesh as mesh', 'mesh.nomor_permintaan_sample', '=', 'minta.nomor_permintaan_sample')->leftJoin('permintaan_sample_quantity as qty', 'qty.nomor_permintaan_sample', '=', 'minta.nomor_permintaan_sample')->where('minta.nomor_permintaan_sample', $val_nomor)->first();
        
        return Response()->json($data);
    }

    public function deletePermintaanSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->get('nomor'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Delete Data Permintaan Sample Nomor ' . $request->get('nomor')]);

        return Response()->json($arr);
    }

    public function editPermintaanSample(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if($request->hasFile('edit_lampiran')) {
            $data_lampiran = DB::table('permintaan_sample')->select('lampiran_sales')->where('nomor_permintaan_sample', $request->edit_nomor)->first();
            File::delete('data_file/' . $data_lampiran->lampiran);

            $file = $request->file('edit_lampiran');
            $nama_file = time()."_Lampiran_Permintaan_Sample_".$request->edit_nomor."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            
            $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['tanggal' => $request->edit_tanggal, 'customers' => $request->edit_customers, 'respon_customers' => $request->edit_respon, 'lampiran_sales' => $nama_file]);
        }else{
            $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['tanggal' => $request->edit_tanggal, 'customers' => $request->edit_customers, 'respon_customers' => $request->edit_respon]);
        }

        $batu_ketak = $request->input('edit_checkbox_batu_ketak');
        $batu_kapur = $request->input('edit_checkbox_batu_kapur');
        $batu_lainnya = $request->input('edit_checkbox_batu_lainnya');

        $cek_barang = DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $request->edit_nomor)->first();

        if(!$cek_barang){
            DB::table('permintaan_sample_barang')->insert(["nomor_permintaan_sample" => $request->edit_nomor]);
        }

        if($batu_ketak){
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['batu_ketak' => 1]);
        }else{
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['batu_ketak' => 0]);
        }

        if($batu_kapur){
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['batu_kapur' => 1]);
        }else{
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['batu_kapur' => 0]);
        }

        if($batu_lainnya){
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['lainnya' => 1, 'text_lainnya' => $request->batu_lainnya]);
        }else{
            DB::table('permintaan_sample_barang')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['lainnya' => 0, 'text_lainnya' => $request->batu_lainnya]);
        }

        $mesh_250 = $request->input('edit_checkbox_mesh_250');
        $mesh_325 = $request->input('edit_checkbox_mesh_325');
        $mesh_500 = $request->input('edit_checkbox_mesh_500');
        $mesh_800_u1 = $request->input('edit_checkbox_mesh_800_u1');
        $mesh_800_u2 = $request->input('edit_checkbox_mesh_800_u2');
        $mesh_800_swaa = $request->input('edit_checkbox_mesh_800_swaa');
        $mesh_1200 = $request->input('edit_checkbox_mesh_1200');
        $mesh_1500 = $request->input('edit_checkbox_mesh_1500');
        $mesh_2000 = $request->input('edit_checkbox_mesh_2000');
        $mesh_6000 = $request->input('edit_checkbox_mesh_6000');
        $mesh_2002c = $request->input('edit_checkbox_mesh_2002c');
        $mesh_lainnya = $request->input('edit_checkbox_mesh_lainnya');

        $cek_mesh = DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->first();

        if(!$cek_mesh){
            DB::table('permintaan_sample_mesh')->insert(["nomor_permintaan_sample" => $request->edit_nomor]);
        }

        if($mesh_250){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_250' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_250' => 0]);
        }

        if($mesh_325){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_325' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_325' => 0]);
        }

        if($mesh_500){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_500' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_500' => 0]); 
        }

        if($mesh_800_u1){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_800_u1' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_800_u1' => 0]);
        }

        if($mesh_800_u2){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_800_u2' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_800_u2' => 0]);
        }

        if($mesh_800_swaa){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_800_swaa' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_800_swaa' => 0]);
        }

        if($mesh_1200){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_1200' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_1200' => 0]);
        }

        if($mesh_1500){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_1500' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_1500' => 0]);
        }

        if($mesh_2000){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_2000' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_2000' => 0]);
        }

        if($mesh_6000){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_6000' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_6000' => 0]);
        }

        if($mesh_2002c){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_2002c' => 1]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_2002c' => 0]);
        }

        if($mesh_lainnya){
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_lainnya' => 1, 'text_lainnya' => $request->mesh_lainnya]);
        }else{
            DB::table('permintaan_sample_mesh')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['mesh_lainnya' => 0, 'text_lainnya' => $request->mesh_lainnya]);
        }

        $qty_1kg = $request->input('edit_checkbox_qty_1kg');
        $qty_3kg = $request->input('edit_checkbox_qty_3kg');
        $qty_5kg = $request->input('edit_checkbox_qty_5kg');
        $qty_lainnya = $request->input('edit_checkbox_qty_lainnya');

        $cek_qty = DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->first();

        if(!$cek_qty){
            DB::table('permintaan_sample_quantity')->insert(["nomor_permintaan_sample" => $request->edit_nomor]);
        }

        if($qty_1kg){
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['qty_1kg' => 1]);
        }else{
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['qty_1kg' => 0]);
        }

        if($qty_3kg){
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['qty_3kg' => 1]);
        }else{
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['qty_3kg' => 0]);
        }

        if($qty_5kg){
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['qty_5kg' => 1]);
        }else{
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['qty_5kg' => 0]);
        }

        if($qty_lainnya){
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['qty_lainnya' => 1]);
        }else{
            DB::table('permintaan_sample_quantity')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['qty_lainnya' => 0]);
        }

        $kirim_sendiri = $request->input('edit_checkbox_kirim_sendiri');
        $ekspedisi = $request->input('edit_checkbox_ekspedisi');

        $cek_kirim = DB::table('permintaan_sample_kirim')->where('nomor_permintaan_sample', $request->edit_nomor)->first();

        if(!$cek_kirim){
            DB::table('permintaan_sample_kirim')->insert(["nomor_permintaan_sample" => $request->edit_nomor]);
        }

        DB::table('permintaan_sample_kirim')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['nama_ekspedisi' => $request->edit_nama_ekspedisi, 'nomor_resi' => $request->edit_nomor_resi]);

        if($kirim_sendiri){
            DB::table('permintaan_sample_kirim')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['ambil_sendiri' => 1]);
        }else{
            DB::table('permintaan_sample_kirim')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['ambil_sendiri' => 0]);
        }

        if($ekspedisi){
            DB::table('permintaan_sample_kirim')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['ekspedisi' => 1]);
        }else{
            DB::table('permintaan_sample_kirim')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['ekspedisi' => 0]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Edit Data Permintaan Sample Nomor ' . $request->edit_nomor]);

        return Response()->json($arr);
    }

    public function viewPagePermintaanSampleLab(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('permintaan_sample_lab');
        }
    }

    public function viewPermintaanSampleLabTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('permintaan_sample')->select('nomor_permintaan_sample as nomor', 'tanggal', 'status')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('permintaan_sample')->select('nomor_permintaan_sample as nomor', 'tanggal', 'status')->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_permintaan_sample_lab')->rawColumns(['action'])->make(true);
        }
        return view('permintaan_sample_lab');
    }

    public function inputPermintaanSampleLab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $number = count($request->input_mesh);

        for($i = 0; $i < $number; $i++){
            if(!empty($request->input_mesh[$i])){
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

                $data = DB::table('permintaan_sample_detail')->insert(['nomor_permintaan_sample_detail' => $kode_permintaan_sample_detail, 'nomor_permintaan_sample' => $request->input_nomor, 'mesh' => $request->input_mesh[$i], 'ssa' => $request->input_ssa[$i], 'd50' => str_replace(',', '.', $request->input_d50[$i]), 'd98' => str_replace(',', '.', $request->input_d98[$i]), 'cie86' => str_replace(',', '.', $request->input_cie86[$i]), 'iso2470' => str_replace(',', '.', $request->input_iso2470[$i]), 'moisture' => str_replace(',', '.', $request->input_moisture[$i]), 'residue' => str_replace(',', '.', $request->input_residue[$i])]);
            }
        }

        $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->input_nomor)->update(['status' => 2]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Lab Input Data Lab Permintaan Sample Nomor ' . $request->input_nomor]);

        return Response()->json($arr);
    }

    public function viewPermintaanSampleLab($nomor){
        $val_nomor = $this->decrypt($nomor);

        $data = DB::table('permintaan_sample_detail')->select('nomor_permintaan_sample_detail as nomor', 'nomor_permintaan_sample', 'mesh', 'ssa', 'd50', 'd98', 'cie86', 'iso2470', 'moisture', 'residue')->where('nomor_permintaan_sample', $val_nomor)->get();
        
        return Response()->json($data);
    }

    public function deleteDetailPermintaanSampleLab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('permintaan_sample_detail')->where('nomor_permintaan_sample_detail', $request->get('nomor'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function editPermintaanSampleLab(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $number = count($request->edit_mesh);

        for($i = 0; $i < $number; $i++){
            if(!empty($request->edit_mesh[$i])){
                if(!empty($request->edit_nomor_detail[$i])){
                    $data = DB::table('permintaan_sample_detail')->where('nomor_permintaan_sample_detail', $request->edit_nomor_detail[$i])->update(['mesh' => $request->edit_mesh[$i], 'ssa' => $request->edit_ssa[$i], 'd50' => str_replace(',', '.', $request->edit_d50[$i]), 'd98' => str_replace(',', '.', $request->edit_d98[$i]), 'cie86' => str_replace(',', '.', $request->edit_cie86[$i]), 'iso2470' => str_replace(',', '.', $request->edit_iso2470[$i]), 'moisture' => str_replace(',', '.', $request->edit_moisture[$i]), 'residue' => str_replace(',', '.', $request->edit_residue[$i])]);
                }else{
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

                    $data = DB::table('permintaan_sample_detail')->insert(['nomor_permintaan_sample_detail' => $kode_permintaan_sample_detail, 'nomor_permintaan_sample' => $request->edit_nomor, 'mesh' => $request->edit_mesh[$i], 'ssa' => $request->edit_ssa[$i], 'd50' => str_replace(',', '.', $request->edit_d50[$i]), 'd98' => str_replace(',', '.', $request->edit_d98[$i]), 'cie86' => str_replace(',', '.', $request->edit_cie86[$i]), 'iso2470' => str_replace(',', '.', $request->edit_iso2470[$i]), 'moisture' => str_replace(',', '.', $request->edit_moisture[$i]), 'residue' => str_replace(',', '.', $request->edit_residue[$i])]);
                }
            }
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Lab Edit Data Lab Permintaan Sample Nomor ' . $request->edit_nomor]);

        return Response()->json($arr);
    }

    public function viewPermintaanSampleLabBatchLaporanHarian($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $data = DB::table('laporan_hasil_produksi')->select('jam_waktu')->join('laporan_hasil_produksi_lab', 'laporan_hasil_produksi_lab.nomor_laporan_produksi', '=', 'laporan_hasil_produksi.nomor_laporan_produksi')->where('tanggal_laporan_produksi', $val_tanggal)->distinct()->get();
        
        return Response()->json($data);
    }

    public function viewPermintaanSampleLabDataLaporanHarian(Request $request){
        if(request()->ajax()){
            $data = DB::table('laporan_hasil_produksi_lab')->select(DB::raw("(select nomor_permintaan_sample from permintaan_sample where nomor_permintaan_sample = '$request->nomor_pm') as nomor_permintaan_sample"), 'nomor_laporan_produksi_lab', 'tbl_mesin.name as nama_mesin', 'rpm', 'mesh', 'ssa', 'd50', 'd98', 'cie86', 'iso2470', 'moisture', 'residue')->join('laporan_hasil_produksi', 'laporan_hasil_produksi_lab.nomor_laporan_produksi', '=', 'laporan_hasil_produksi.nomor_laporan_produksi')->join('tbl_mesin', 'tbl_mesin.id', '=', 'laporan_hasil_produksi_lab.mesin')->where('tanggal_laporan_produksi', $request->tanggal)->where('jam_waktu', $request->waktu)->get();

            return datatables()->of($data)->make(true);
        }
        return view('permintaan_sample_lab');
    }

    public function cariPermintaanSampleLab(Request $request){
        $nomor_pm = $request->get('nomor_pm');
        $nomor_lab = $request->get('nomor_lab');
        $dipilih = $request->get('dipilih');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nomor_lab as $no) {
            if(is_array($dipilih) && array_key_exists($no,$dipilih)){
                $tanggal = date('ym');

                DB::table('permintaan_sample')->where('nomor_permintaan_sample', $nomor_pm[$no])->update(['status' => 2]);

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

                $data_lab = DB::table('laporan_hasil_produksi_lab')->where('nomor_laporan_produksi_lab', $no)->first();

                $data = DB::table('permintaan_sample_detail')->insert(['nomor_permintaan_sample_detail' => $kode_permintaan_sample_detail, 'nomor_permintaan_sample' => $nomor_pm[$no], 'nomor_laporan_lab' => $no, 'mesh' => $data_lab->mesh, 'ssa' => $data_lab->ssa, 'd50' => str_replace(',', '.', $data_lab->d50), 'd98' => str_replace(',', '.', $data_lab->d98), 'cie86' => str_replace(',', '.', $data_lab->cie86), 'iso2470' => str_replace(',', '.', $data_lab->iso2470), 'moisture' => str_replace(',', '.', $data_lab->moisture), 'residue' => str_replace(',', '.', $data_lab->residue)]);

                if($data){
                    $arr = array('msg' => 'Data Successfully Added', 'status' => true);
                }

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' / Lab Input Data Lab Permintaan Sample Nomor ' . $nomor_pm[$no]]);
            }
        }

        return Response()->json();
    }

    public function uploadPermintaanSampleLab(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new PermintaanSampleDetailImport($request->upload_nomor);
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        return redirect('lab/permintaan_sample')->with('alert','Sukses Menambahkan Data');
    }

    public function updatePermintaanSampleRespon(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $nama_file = time()."_Lampiran_Permintaan_Sample_".$request->update_nomor."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->update_nomor)->update(['respon_customers' => $request->respon, 'lampiran_sales' => $nama_file, 'status' => 4]);
        }else{
            $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->update_nomor)->update(['respon_customers' => $request->respon, 'status' => 4]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 4, 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Update Data Respon Customers Permintaan Sample Nomor ' . $request->update_nomor]);

        return Response()->json($arr);
    }

    public function viewPagePermintaanSampleProduksi(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('produksi_permintaan_sample');
        }
    }

    public function viewPermintaanSampleProduksiTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $list = DB::table('permintaan_sample')->select('nomor_permintaan_sample as nomor', 'tanggal', 'cus.custname', 'status')->join('customers as cus', 'cus.custid', '=', 'permintaan_sample.customers')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $list = DB::table('permintaan_sample')->select('nomor_permintaan_sample as nomor', 'tanggal', 'cus.custname', 'status')->join('customers as cus', 'cus.custid', '=', 'permintaan_sample.customers')->get();
            }

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_permintaan_sample_produksi')->rawColumns(['action'])->make(true);
        }
        return view('produksi_permintaan_sample');
    }

    public function updatePermintaanSampleAnalisa(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $nama_file = time()."_Lampiran_Permintaan_Sample_".$request->update_nomor."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->update_nomor)->update(['analisa' => $request->analisa, 'solusi' => $request->solusi, 'lampiran_produksi' => $nama_file, 'status' => 3]);
        }else{
            $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->update_nomor)->update(['analisa' => $request->analisa, 'solusi' => $request->solusi, 'status' => 3]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 3, 'action' => 'User ' . Session::get('id_user_admin') . ' / Produksi Update Data Analisa Permintaan Sample Nomor ' . $request->update_nomor]);

        return Response()->json($arr);
    }

    public function editPermintaanSampleAnalisa(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if($request->hasFile('edit_lampiran')) {
            $data_lampiran = DB::table('permintaan_sample')->select('lampiran_produksi')->where('nomor_permintaan_sample', $request->edit_nomor)->first();
            File::delete('data_file/' . $data_lampiran->lampiran);

            $file = $request->file('edit_lampiran');
            $nama_file = time()."_Lampiran_Permintaan_Sample_".$request->edit_nomor."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['analisa' => $request->edit_analisa, 'solusi' => $request->edit_solusi, 'lampiran_produksi' => $nama_file]);
        }else{
            $data = DB::table('permintaan_sample')->where('nomor_permintaan_sample', $request->edit_nomor)->update(['analisa' => $request->edit_analisa, 'solusi' => $request->edit_solusi]);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_permintaan_sample')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 3, 'action' => 'User ' . Session::get('id_user_admin') . ' / Produksi Edit Data Analisa Permintaan Sample Nomor ' . $request->edit_nomor]);

        return Response()->json($arr);
    }

    public function printPermintaanSample($nomor){
        $val_nomor = Crypt::decrypt($nomor);

        $data = DB::table('permintaan_sample')->select('nomor_permintaan_sample', 'tanggal', 'customers', 'cus.custname', 'respon_customers', 'lampiran_sales', 'analisa', 'solusi', 'lampiran_produksi')->join('customers as cus', 'cus.custid', '=', 'permintaan_sample.customers')->where('nomor_permintaan_sample', $val_nomor)->first();

        $detail = DB::table('permintaan_sample_detail')->select('nomor_permintaan_sample_detail', 'nomor_laporan_lab', 'ssa', 'd50', 'd98', 'cie86', 'iso2470', 'moisture', 'residue')->where('nomor_permintaan_sample', $val_nomor)->first();

        $pdf = PDF::loadView('print_form_permintaan_sample', ['data' => $data, 'detail' => $detail])->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function viewCustomerGroupTable(Request $request){
        if(request()->ajax()){
            $list = DB::table('customer_group')->select('customer_group.groupid', 'nama_group', DB::raw("count(distinct id_customer_group_detail) as jumlah_anggota"))->leftJoin('customer_group_detail as det', 'det.groupid', '=', 'customer_group.groupid')->groupBy('customer_group.groupid')->get();

            return datatables()->of($list)->addIndexColumn()->addColumn('action', 'button/action_button_customer_group')->rawColumns(['action'])->make(true);
        }
        return view('input_customer_group');
    }

    public function viewDetailCustCustomerGroupTable(Request $request){
        if(request()->ajax()){
            $list = DB::table('temp_customer_group')->select('custid', 'custname')->where('id_user', Session::get('id_user_admin'))->get();

            return datatables()->of($list)->addColumn('action', 'button/action_button_customer_group_detail')->rawColumns(['action'])->make(true);
        }
        return view('input_customer_group');
    }

    public function addDetailCustCustomerGroup(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');

        $cek_data = DB::table('customers')->select('custname')->where('custid', $request->customers)->first();

        $data = DB::table('temp_customer_group')->insert(['id_user' => Session::get('id_user_admin'), 'custid' => $request->customers, 'custname' => $cek_data->custname]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteDetailCustCustomerGroup(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('temp_customer_group')->where('custid', $request->get('custid'))->delete();

        if($data){
            $arr = array('msg' => 'Data Successfully Deleted', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function inputCustomerGroup(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data_detail = DB::table('temp_customer_group')->select('custid', 'custname')->where('id_user', Session::get('id_user_admin'))->get();

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('customer_group')->insert(['groupid' => $request->get('kode_group'), 'nama_group' => $request->get('nama_group'), 'created_at' => date("Y-m-d H:i:s"), 'created_by' => Session::get('id_user_admin'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

        foreach($data_detail as $det){
            DB::table('customer_group_detail')->insert(['groupid' => $request->get('kode_group'), 'custid' => $det->custid, 'custname' => $det->custname]);

            DB::table('customers')->where('custid', $det->custid)->update(['groupid' => $request->get('kode_group')]);

            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $det->custid, 'status_user' => 4, 'action' => 'User ' . $det->custid . ' Melakukan Perubahan Data Group ID oleh Sales']);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        DB::table('temp_customer_group')->where('id_user', Session::get('id_user_admin'))->delete();

        date_default_timezone_set('Asia/Jakarta');
         DB::table('logbook_customer_group')->insert(['tanggal' => date("Y-m-d H:i:s"), 'kode' => $request->get('kode_group'), 'id_user' => Session::get('id_user_admin'), 'action' => 'User Sales / ID ' . Session::get('id_user_admin') . ' Insert Data Customer Group']);

        return Response()->json($arr);
    }

    public function viewDataCustomerGroup($groupid){
        $val_groupid = $this->decrypt($groupid);
        
        $data = DB::table('customer_group')->select('groupid', 'nama_group')->where('groupid', $val_groupid)->first();

        return Response()->json($data);
    }

    public function viewDataDetailCustCustomerGroup($groupid){
        $val_groupid = $this->decrypt($groupid);
        
        $data = DB::table('customer_group_detail')->select('custid', 'custname')->where('groupid', $val_groupid)->get();

        return Response()->json($data);
    }

    public function viewEditDetailCustCustomerGroupTable(Request $request){
        if(request()->ajax()){
            $list = DB::table('customer_group_detail')->select('groupid', 'custid', 'custname')->where('groupid', $request->groupid)->get();

            return datatables()->of($list)->addColumn('action', 'button/action_button_customer_group_detail_edit')->rawColumns(['action'])->make(true);
        }
        return view('input_customer_group');
    }

    public function editDetailCustCustomerGroup(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');

        $cek_data = DB::table('customers')->select('custname')->where('custid', $request->edit_customers)->first();

        $data = DB::table('customer_group_detail')->insert(['groupid' => $request->edit_kode_cust_group_det, 'custid' => $request->edit_customers, 'custname' => $cek_data->custname]);

        DB::table('customers')->where('custid', $request->edit_customers)->update(['groupid' => $request->edit_kode_cust_group_det]);

        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->edit_customers, 'status_user' => 4, 'action' => 'User ' . $request->edit_customers . ' Melakukan Perubahan Data Group ID oleh Sales']);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function editCustomerGroup(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('customer_group')->where('groupid', $request->get('kode_group_lama'))->update(['groupid' => $request->get('kode_group'), 'nama_group' => $request->get('nama_group'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

        DB::table('customer_group_detail')->where('groupid', $request->get('kode_group_lama'))->update(['groupid' => $request->get('kode_group')]);

        DB::table('customers')->where('groupid', $request->get('kode_group_lama'))->update(['groupid' => $request->get('kode_group')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteEditDetailCustCustomerGroup(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('customer_group_detail')->where('custid', $request->get('custid'))->where('groupid', $request->get('groupid'))->delete();

        DB::table('customers')->where('custid', $request->get('custid'))->update(['groupid' => NULL]);

        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->get('custid'), 'status_user' => 4, 'action' => 'User ' . $request->get('custid') . ' Melakukan Perubahan Data Group ID oleh Sales']);

        if($data){
            $arr = array('msg' => 'Data Successfully Deleted', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteCustomerGroup(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('customer_group')->where('groupid', $request->get('groupid'))->delete();

        $cek_data = DB::table('customer_group_detail')->select('custid')->where('groupid', $request->get('groupid'))->get();

        foreach($cek_data as $cek){
            DB::table('customers')->where('custid', $cek->custid)->update(['groupid' => NULL]);

            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $cek->custid, 'status_user' => 4, 'action' => 'User ' . $cek->custid . ' Melakukan Perubahan Data Group ID oleh Sales']);
        }

        $data_det = DB::table('customer_group_detail')->where('groupid', $request->get('groupid'))->delete();

        if($data){
            $arr = array('msg' => 'Data Successfully Deleted', 'status' => true);
        }

        return Response()->json($arr);
    }
}
