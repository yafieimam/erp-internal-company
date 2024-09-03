<?php

namespace App\Http\Controllers;
use App\ModelCustomers;
use App\ModelUser;
use App\ModelOrders;
use App\ModelOrdersProduk;
use App\ModelKota;
use App\ModelProduk;
use App\ModelAlamatHistory;
use App\ModelHistoryOrders;
use App\ModelProductionOrder;
use App\ModelProductionOrderDetail;
use App\Notifications\NotifNewOrders;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Response;
use Notification;
use PDF;

class OrdersController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';

    public function index(Request $request){
        $orders = DB::table('temp_order as tmp')->select("prd.nama_produk as produk", "tmp.quantity as quantity", "tmp.custid as custid", "tmp.kode_produk as kode_produk")->join("products as prd", "prd.kode_produk", "=", "tmp.kode_produk")->where('tmp.custid', $request->custid)->get();

        return datatables()->of($orders)->addColumn('action', 'button/action_button_orders')->rawColumns(['action'])->make(true);

        return view('form_order_en');
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

    public function printSuratPesanan($nomor_sj){
        $val_nomor_sj = $this->decrypt($nomor_sj);

        $data = DB::table('order_receipt as ord')->select('ord.nomor_order_receipt', 'ord.tanggal_order', 'cus.custname as custname_order', 'cus.address as address_order', 'ord.custid', 'orprd.custname_receive', 'kota.name as kota_receive', 'ord.keterangan_order', 'orprd.custalamat_receive', 'ord.nomor_po', 'cus.crd', 'orprd.id_alamat')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('customers as cus', 'cus.custid', '=', 'ord.custid')->join('kota', 'kota.id_kota', '=', 'orprd.custkota_receive')->where('ord.nomor_order_receipt', $val_nomor_sj)->groupBy('orprd.id_alamat')->get();

        $products = DB::table('order_receipt_produk as ord')->select('prd.nama_produk', 'prd.kode_produk', 'ord.tanggal_kirim', 'ord.qty', 'ord.harga_satuan', 'ord.discount', 'ord.ppn', 'ord.total_price', 'ord.keterangan_quotation', 'ord.id_alamat')->join('products as prd', 'prd.kode_produk', '=', 'ord.kode_produk')->where('ord.nomor_order_receipt', $val_nomor_sj)->get();

        $pdf = PDF::loadView('print_surat_pesanan', ['data' => $data, 'products' => $products, 'discount' => $products[0]->discount, 'ppn' => $products[0]->ppn, 'data_count' => $data->count()])->setPaper('a5', 'portrait');
        return $pdf->stream();
        // return view('print_surat_pesanan');
    }

    public function printSuratPesananSeveral($custid, $tanggal_order){
        $products = DB::table('order_receipt as ord')->select('prd.nama_produk', 'ord.tanggal_kirim', 'ord.qty', 'ord.harga_satuan', 'ord.total_price', 'ord.nomor_order_receipt', 'ord.custalamat_receive', 'kota.name as kota_receive')->join('products as prd', 'prd.kode_produk', '=', 'ord.kode_produk')->join('kota', 'kota.id_kota', '=', 'ord.custkota_receive')->where('ord.several_address', 1)->where('ord.custid', $custid)->where('ord.tanggal_order', $tanggal_order)->get();

        $data = DB::table('order_receipt as ord')->select('ord.nomor_order_receipt', 'ord.tanggal_order', 'cus.custname as custname_order', 'cus.address as address_order', 'ord.custid', 'ord.custname_receive', 'ord.keterangan_order', 'ord.keterangan_quotation', 'ord.nomor_po', 'cus.crd')->join('customers as cus', 'cus.custid', '=', 'ord.custid')->where('ord.nomor_order_receipt', $products[0]->nomor_order_receipt)->first();

        $pdf = PDF::loadView('print_surat_pesanan_gabung', ['data' => $data, 'products' => $products])->setPaper('a5', 'portrait');
        return $pdf->stream();
        // return view('print_surat_pesanan');
    }

    public function en(){
        $custid = Session::get('custid');
    	$customer = ModelCustomers::select('custname', 'address', 'phone', 'city', 'kota.name as name_city', 'npwp', 'crd')->join('kota', 'kota.id_kota', '=', 'customers.city')->where('custid', $custid)->first();

        $alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive', 'kota.name as name_city')->join('kota', 'kota.id_kota', '=', 'history_alamat_receive.city_receive')->where('custid_order', $custid)->where('main_address', 1)->first();

        if(!Session::get('change_address')){
            $alamat_data = ModelAlamatHistory::where('custid_order', $custid)->where('choosen', 1)->get();

            if(count($alamat_data) > 1){
                Session::put('several_address', TRUE);
            }else{
                Session::put('several_address', FALSE);
                ModelAlamatHistory::where('custid_order', $custid)->where('main_address', 1)->update(['choosen' => 1]);

                ModelAlamatHistory::where('custid_order', $custid)->where('main_address', 0)->update(['choosen' => 0]);
                
                Session::put('kode_alamat',$alamat->id_alamat_receive);
                Session::put('name_receive',$alamat->custname_receive);
                Session::put('address_receive',$alamat->address_receive);
                Session::put('city_receive',$alamat->city_receive);
                Session::put('name_city_receive',$alamat->name_city);
                Session::put('phone_receive',$alamat->phone_receive);
            }

            $data_tmp = DB::table('temp_order')->select('kode_produk', 'quantity')->where('custid', $custid)->get();
            foreach($data_tmp as $tmp){
                $data_prd = DB::table('products')->select('saldo', 'weight')->where('kode_produk', $tmp->kode_produk)->first();
                DB::table('products')->where('kode_produk', $tmp->kode_produk)->update(['saldo' => ($data_prd->saldo + ($tmp->quantity / $data_prd->weight))]);
            }

            DB::table('temp_order')->where('custid', $custid)->delete();
        }

        Session::forget('change_address');

        $products = ModelProduk::select("kode_produk", "nama_produk")->get();

        $recommendation_produk = DB::table('history_orders as ho')->select('ho.kode_produk', 'prod.nama_produk')->join('products as prod', 'prod.kode_produk', '=', 'ho.kode_produk')->where('ho.custid', $custid)->where('ho.alamat_receive_history', Session::get('kode_alamat'))->groupBy('ho.kode_produk')->orderBy('ho.id', 'ASC')->get();

        $recommendation_qty = DB::table('history_orders as ho')->select(DB::raw('ho.quantity'))->where('ho.custid', $custid)->where('ho.alamat_receive_history', Session::get('kode_alamat'))->groupBy('ho.quantity')->get();

        $alamat_several = ModelAlamatHistory::select('id_alamat_receive', 'address_receive', 'kota.name as name_city')->join('kota', 'kota.id_kota', '=', 'history_alamat_receive.city_receive')->where('custid_order', $custid)->where('choosen', 1)->get();

    	return view('form_order_en')->with(['customer' => $customer, 'products' => $products, 'rekomendasi_produk' => $recommendation_produk, 'rekomendasi_qty' => $recommendation_qty, 'alamat_several' => $alamat_several]);
    }

    public function id(){
    	$custid = Session::get('custid');
        $customer = ModelCustomers::select('custname', 'address', 'phone', 'city', 'kota.name as name_city', 'npwp', 'crd')->join('kota', 'kota.id_kota', '=', 'customers.city')->where('custid', $custid)->first();

        $alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive', 'kota.name as name_city')->join('kota', 'kota.id_kota', '=', 'history_alamat_receive.city_receive')->where('custid_order', $custid)->where('main_address', 1)->first();

        if(!Session::get('change_address')){
            $alamat_data = ModelAlamatHistory::where('custid_order', $custid)->where('choosen', 1)->get();

            if(count($alamat_data) > 1){
                Session::put('several_address', TRUE);
            }else{
                Session::put('several_address', FALSE);
                ModelAlamatHistory::where('custid_order', $custid)->where('main_address', 1)->update(['choosen' => 1]);

                ModelAlamatHistory::where('custid_order', $custid)->where('main_address', 0)->update(['choosen' => 0]);
                
                Session::put('kode_alamat',$alamat->id_alamat_receive);
                Session::put('name_receive',$alamat->custname_receive);
                Session::put('address_receive',$alamat->address_receive);
                Session::put('city_receive',$alamat->city_receive);
                Session::put('name_city_receive',$alamat->name_city);
                Session::put('phone_receive',$alamat->phone_receive);
            }

            $data_tmp = DB::table('temp_order')->select('kode_produk', 'quantity')->where('custid', $custid)->get();
            foreach($data_tmp as $tmp){
                $data_prd = DB::table('products')->select('saldo', 'weight')->where('kode_produk', $tmp->kode_produk)->first();
                DB::table('products')->where('kode_produk', $tmp->kode_produk)->update(['saldo' => ($data_prd->saldo + ($tmp->quantity / $data_prd->weight))]);
            }

            DB::table('temp_order')->where('custid', $custid)->delete();
        }

        Session::forget('change_address');

        $products = ModelProduk::select("kode_produk", "nama_produk")->get();

        $recommendation_produk = DB::table('history_orders as ho')->select('ho.kode_produk', 'prod.nama_produk')->join('products as prod', 'prod.kode_produk', '=', 'ho.kode_produk')->where('ho.custid', $custid)->where('ho.alamat_receive_history', Session::get('kode_alamat'))->groupBy('ho.kode_produk')->orderBy('ho.id', 'ASC')->get();

        $recommendation_qty = DB::table('history_orders as ho')->select(DB::raw('ho.quantity'))->where('ho.custid', $custid)->where('ho.alamat_receive_history', Session::get('kode_alamat'))->groupBy('ho.quantity')->get();

        $alamat_several = ModelAlamatHistory::select('id_alamat_receive', 'address_receive', 'kota.name as name_city')->join('kota', 'kota.id_kota', '=', 'history_alamat_receive.city_receive')->where('custid_order', $custid)->where('choosen', 1)->get();

        return view('form_order_id')->with(['customer' => $customer, 'products' => $products, 'rekomendasi_produk' => $recommendation_produk, 'rekomendasi_qty' => $recommendation_qty, 'alamat_several' => $alamat_several]);
    }

    public function showSeveralProducts(Request $request){
        $orders = DB::table('temp_order as tmp')->select("tmp.address_receive as alamat", "tmp.delivery_date as tgl_kirim", "prd.nama_produk as produk", "tmp.quantity as quantity", "tmp.custid as custid", "tmp.kode_produk as kode_produk")->join("products as prd", "prd.kode_produk", "=", "tmp.kode_produk")->where('tmp.custid', $request->custid)->get();

        return datatables()->of($orders)->addColumn('action', 'button/action_button_several_orders')->rawColumns(['action'])->make(true);

        return view('form_order_en');
    }

    public function showDelivdateProducts(Request $request){
        $orders = DB::table('temp_order as tmp')->select("tmp.delivery_date as tgl_kirim", "prd.nama_produk as produk", "tmp.quantity as quantity", "tmp.custid as custid", "tmp.kode_produk as kode_produk")->join("products as prd", "prd.kode_produk", "=", "tmp.kode_produk")->where('tmp.custid', $request->custid)->get();

        return datatables()->of($orders)->addColumn('action', 'button/action_button_delivdate_orders')->rawColumns(['action'])->make(true);

        return view('form_order_en');
    }

    public function getProducts(){
        $products = ModelProduk::select("kode_produk", "nama_produk")->get();

        return Response()->json($products);
    }

    public function getHistoryOrders($custid, $id_alamat){
        $val_custid = $this->decrypt($custid);
        $val_id_alamat = $this->decrypt($id_alamat);

        $recommendation_produk = DB::table('history_orders as ho')->select('ho.kode_produk', 'prod.nama_produk')->join('products as prod', 'prod.kode_produk', '=', 'ho.kode_produk')->where('ho.custid', $val_custid)->where('ho.alamat_receive_history', $val_id_alamat)->groupBy('ho.kode_produk')->orderBy('ho.id', 'ASC')->get();

        $recommendation_qty = DB::table('history_orders as ho')->select(DB::raw('ho.quantity'))->where('ho.custid', $val_custid)->where('ho.alamat_receive_history', $val_id_alamat)->groupBy('ho.quantity')->get();

        return Response()->json(array(
            'rekomendasi_produk' => $recommendation_produk, 
            'rekomendasi_qty' => $recommendation_qty
        ));
    }

    public function detailProductsEN($kode_produk){
        $val_kode_produk = $this->decrypt($kode_produk);

    	$products = DB::table('products as prod')->select('prod.kode_produk_customer as kode_produk_customer', 'prod.kode_produk as kode_produk', 'prod.nama_produk as nama_produk', 'prod.deskripsi as deskripsi', 'prod.physical_appearance as physical_appearance', 'prod.whiteness as whiteness', 'prod.residue as residue', 'prod.mean_particle_diameter as mean_particle_diameter', 'prod.moisture as moisture', 'sp.name as standard_packaging', 'prod.weight as weight', 'prod.mesh as mesh', 'prod.harga as harga', 'prod.gambar as gambar')->join('standard_packaging as sp', 'sp.id_standard_packaging', '=', 'prod.standard_packaging')->where("kode_produk", $val_kode_produk)->first();
    	return view('orders_detail_en')->with('produk', $products);
    }

    public function detailProductsID($kode_produk){
        $val_kode_produk = $this->decrypt($kode_produk);

    	$products = DB::table('products as prod')->select('prod.kode_produk_customer as kode_produk_customer', 'prod.kode_produk as kode_produk', 'prod.nama_produk as nama_produk', 'prod.deskripsi as deskripsi', 'prod.physical_appearance as physical_appearance', 'prod.whiteness as whiteness', 'prod.residue as residue', 'prod.mean_particle_diameter as mean_particle_diameter', 'prod.moisture as moisture', 'sp.name as standard_packaging', 'prod.weight as weight', 'prod.mesh as mesh', 'prod.harga as harga', 'prod.gambar as gambar')->join('standard_packaging as sp', 'sp.id_standard_packaging', '=', 'prod.standard_packaging')->where("kode_produk", $val_kode_produk)->first();
    	return view('orders_detail_id')->with('produk', $products);
    }

    public function orderDetailProcessEN(Request $request){
        $this->validate($request, [
            'kode_produk' => 'required',
            'custid' => 'required',
            'kuantitas' => 'required|numeric|min:8',
        ]);

        $produk = ModelProduk::select('harga')->where('kode_produk', $request->kode_produk)->first();

        $total = $produk->harga * $request->kuantitas;

        $data =  new ModelOrders();
        $data->kode_produk = $request->kode_produk;
        $data->custid = $request->custid;
        $data->kuantitas = $request->kuantitas;
        $data->tanggal_order = date('Y-m-d');
        $data->total_harga = $total;
        $data->status = 1;
        $data->save();

        $orders = DB::table('orders as ord')->select('ord.kode_produk as kode_produk', 'prod.kode_produk_customer as kode_produk_customer', 'prod.nama_produk as nama_produk', 'prod.harga as harga', 'prod.gambar as gambar', 'ord.kuantitas as kuantitas', 'ord.tanggal_order as tanggal_order', 'ord.total_harga as total_harga', 'cus.custid as custid', 'cus.custname as custname', 'cus.address as address', 'kota.name as city', 'cus.phone as phone', 'cus.wraddress as wraddress')->join('products as prod', 'prod.kode_produk', '=', 'ord.kode_produk')->join('customers as cus', 'cus.custid', '=', 'ord.custid')->join('kota', 'kota.id_kota', '=', 'cus.city')->where("ord.kode_produk", $request->kode_produk)->where("ord.custid", $request->custid)->first();
        return view('checkout_en')->with('data_order', $orders);
    }

    public function orderDetailProcessID(Request $request){
        $this->validate($request, [
            'kode_produk' => 'required',
            'custid' => 'required',
            'kuantitas' => 'required|numeric|min:8',
        ]);

        $produk = ModelProduk::select('harga')->where('kode_produk', $request->kode_produk)->first();

        $total = $produk->harga * $request->kuantitas;

        $data =  new ModelOrders();
        $data->kode_produk = $request->kode_produk;
        $data->custid = $request->custid;
        $data->kuantitas = $request->kuantitas;
        $data->tanggal_order = date('Y-m-d');
        $data->total_harga = $total;
        $data->status = 1;
        $data->save();

        $orders = DB::table('orders as ord')->select('ord.kode_produk as kode_produk', 'prod.kode_produk_customer as kode_produk_customer', 'prod.nama_produk as nama_produk', 'prod.harga as harga', 'prod.gambar as gambar', 'ord.kuantitas as kuantitas', 'ord.tanggal_order as tanggal_order', 'ord.total_harga as total_harga', 'cus.custid as custid', 'cus.custname as custname', 'cus.address as address', 'kota.name as city', 'cus.phone as phone', 'cus.wraddress as wraddress')->join('products as prod', 'prod.kode_produk', '=', 'ord.kode_produk')->join('customers as cus', 'cus.custid', '=', 'ord.custid')->join('kota', 'kota.id_kota', '=', 'cus.city')->where("ord.kode_produk", $request->kode_produk)->where("ord.custid", $request->custid)->first();
        return view('checkout_id')->with('data_order', $orders);
    }

    public function checkoutEN(){
    	return view('checkout_en');
    }

    public function checkoutID(){
        return view('checkout_id');
    }

    public function ordersListEN(Request $request){
        return view('orders_list_en');
    }

    public function ordersListID(Request $request){
        return view('orders_list_id');
    }

    public function getStatusOrderEN($status){
        $custid = Session::get('custid');

        $all = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $new = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 1)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $confirm = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 2)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $produksi = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 3)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $delivery = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 4)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $transit = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 5)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $arrive = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 6)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        if ($status == 'new') {
            return view('orders_list_status_en')->with('data', $new);
        } else if($status == 'confirm') {
            return view('orders_list_status_en')->with('data', $confirm);
        } else if($status == 'produksi') {
            return view('orders_list_status_en')->with('data', $produksi);
        } else if($status == 'delivery') {
            return view('orders_list_status_en')->with('data', $delivery);
        }else if($status == 'transit') {
            return view('orders_list_status_en')->with('data', $transit);
        }else if($status == 'arrive') {
            return view('orders_list_status_en')->with('data', $arrive);
        }else {
            return view('orders_list_status_en')->with('data', $all);
        }
    }

    public function getStatusOrderID($status){
        $custid = Session::get('custid');

        $all = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $new = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 1)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $confirm = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 2)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $produksi = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 3)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $delivery = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 4)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $transit = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 5)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $arrive = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 6)->where('ord.custid', $custid)->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        if ($status == 'new') {
            return view('orders_list_status_id')->with('data', $new);
        } else if($status == 'confirm') {
            return view('orders_list_status_id')->with('data', $confirm);
        } else if($status == 'produksi') {
            return view('orders_list_status_id')->with('data', $produksi);
        } else if($status == 'delivery') {
            return view('orders_list_status_id')->with('data', $delivery);
        }else if($status == 'transit') {
            return view('orders_list_status_id')->with('data', $transit);
        }else if($status == 'arrive') {
            return view('orders_list_status_id')->with('data', $arrive);
        }else {
            return view('orders_list_status_id')->with('data', $all);
        }
    }

    public function getStatusOrderFilterEN($status, $from_date, $to_date){
        $custid = Session::get('custid');

        $all = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $new = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 1)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $confirm = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 2)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $produksi = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 3)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $delivery = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 4)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $transit = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 5)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $arrive = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " and ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 6)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        if ($status == 'new') {
            return view('orders_list_status_en')->with('data', $new);
        } else if($status == 'confirm') {
            return view('orders_list_status_en')->with('data', $confirm);
        } else if($status == 'produksi') {
            return view('orders_list_status_en')->with('data', $produksi);
        } else if($status == 'delivery') {
            return view('orders_list_status_en')->with('data', $delivery);
        }else if($status == 'transit') {
            return view('orders_list_status_en')->with('data', $transit);
        }else if($status == 'arrive') {
            return view('orders_list_status_en')->with('data', $arrive);
        }else {
            return view('orders_list_status_en')->with('data', $all);
        }
    }

    public function getStatusOrderFilterID($status, $from_date, $to_date){
        $custid = Session::get('custid');

        $all = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $new = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 1)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $confirm = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 2)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $produksi = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 3)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $delivery = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 4)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $transit = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 5)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        $arrive = DB::table('order_receipt as ord')->select('ord.custid', 'ord.nomor_order_receipt', DB::raw('group_concat(prd.nama_produk separator " dan ") as produk'), 'ord.tanggal_order', 'ord.status_order', DB::raw('sum(ordp.qty) as tonase'))->join('order_receipt_produk as ordp', 'ordp.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('products as prd', 'prd.kode_produk', '=', 'ordp.kode_produk')->where('ord.status_order', 6)->where('ord.custid', $custid)->whereBetween('ord.tanggal_order', array($from_date, $to_date))->groupBy('ord.nomor_order_receipt')->orderBy('ord.tanggal_order', 'DESC')->paginate(5);

        if ($status == 'new') {
            return view('orders_list_status_id')->with('data', $new);
        } else if($status == 'confirm') {
            return view('orders_list_status_id')->with('data', $confirm);
        } else if($status == 'produksi') {
            return view('orders_list_status_id')->with('data', $produksi);
        } else if($status == 'delivery') {
            return view('orders_list_status_id')->with('data', $delivery);
        }else if($status == 'transit') {
            return view('orders_list_status_id')->with('data', $transit);
        }else if($status == 'arrive') {
            return view('orders_list_status_id')->with('data', $arrive);
        }else {
            return view('orders_list_status_id')->with('data', $all);
        }
    }

    public function statusOrderSpecific(Request $request){
        if(request()->ajax()){
            $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.nomor_po", "ord.status_order", "ord.custid")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.nomor_order_receipt", $request->nomor)->groupBy('ord.nomor_order_receipt')->get();

            return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_orders');
    }

    public function statusOrderSemua(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->whereBetween('ord.tanggal_order', array($request->from_date, $request->to_date))->groupBy('ord.nomor_order_receipt')->get();
            }else{
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->groupBy('ord.nomor_order_receipt')->get();
            }

            return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_orders');
    }

    public function statusOrderNew(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 1)->whereBetween('ord.tanggal_order', array($request->from_date, $request->to_date))->groupBy('ord.nomor_order_receipt')->get();
            }else{
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 1)->groupBy('ord.nomor_order_receipt')->get();
            }

            return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_orders');
    }

    public function statusOrderConfirmation(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 2)->whereBetween('ord.tanggal_order', array($request->from_date, $request->to_date))->groupBy('ord.nomor_order_receipt')->get();
            }else{
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 2)->groupBy('ord.nomor_order_receipt')->get();
            }

            return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_orders');
    }

    public function statusOrderProduksi(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 3)->whereBetween('ord.tanggal_order', array($request->from_date, $request->to_date))->groupBy('ord.nomor_order_receipt')->get();
            }else{
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 3)->groupBy('ord.nomor_order_receipt')->get();
            }

            return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_orders');
    }

    public function statusOrderDelivery(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 4)->whereBetween('ord.tanggal_order', array($request->from_date, $request->to_date))->groupBy('ord.nomor_order_receipt')->get();
            }else{
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 4)->groupBy('ord.nomor_order_receipt')->get();
            }

            return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_orders');
    }

    public function statusOrderTransit(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 5)->whereBetween('ord.tanggal_order', array($request->from_date, $request->to_date))->groupBy('ord.nomor_order_receipt')->get();
            }else{
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 5)->groupBy('ord.nomor_order_receipt')->get();
            }

            return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_orders');
    }

    public function statusOrderArrive(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 6)->whereBetween('ord.tanggal_order', array($request->from_date, $request->to_date))->groupBy('ord.nomor_order_receipt')->get();
            }else{
                $orders = DB::table('order_receipt as ord')->select("ord.nomor_order_receipt", "ord.tanggal_order", "cus.custname as custname", "ord.status_order", "ord.custid", "ord.nomor_po")->join("customers as cus", "cus.custid", "=", "ord.custid")->where("ord.status_order", 6)->groupBy('ord.nomor_order_receipt')->get();
            }

            return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_sales')->rawColumns(['action'])->make(true);
        }
        return view('input_orders');
    }

    public function getAddressListEN(){
        $custid = Session::get('custid');

        $address = DB::table('history_alamat_receive as har')->select('har.id_alamat_receive', 'har.custname_receive', 'har.address_receive', 'har.phone_receive', 'har.city_receive', 'kota.name as name_city', 'har.main_address', 'har.choosen')->join('kota', 'kota.id_kota', '=', 'har.city_receive')->where('har.custid_order', $custid)->groupBy('har.id_alamat_receive')->orderBy('har.main_address', 'DESC')->orderBy('har.id_alamat_receive', 'ASC')->distinct()->paginate(2);

        return view('address_list_en')->with('data', $address);
    }

    public function getAddressListSeveralEN(){
        $custid = Session::get('custid');

        $address = DB::table('history_alamat_receive as har')->select('har.id_alamat_receive', 'har.custname_receive', 'har.address_receive', 'har.phone_receive', 'har.city_receive', 'kota.name as name_city', 'har.main_address', 'har.choosen')->join('kota', 'kota.id_kota', '=', 'har.city_receive')->where('har.custid_order', $custid)->groupBy('har.id_alamat_receive')->orderBy('har.main_address', 'DESC')->orderBy('har.id_alamat_receive', 'ASC')->distinct()->paginate(2);

        return view('address_list_several_en')->with('data', $address);
    }

    public function getAddressListID(){
        $custid = Session::get('custid');

        $address = DB::table('history_alamat_receive as har')->select('har.id_alamat_receive', 'har.custname_receive', 'har.address_receive', 'har.phone_receive', 'har.city_receive', 'kota.name as name_city', 'har.main_address', 'har.choosen')->join('kota', 'kota.id_kota', '=', 'har.city_receive')->where('har.custid_order', $custid)->groupBy('har.id_alamat_receive')->orderBy('har.main_address', 'DESC')->orderBy('har.id_alamat_receive', 'ASC')->paginate(2);

        return view('address_list_id')->with('data', $address);
    }

    public function getAddressListSeveralID(){
        $custid = Session::get('custid');

        $address = DB::table('history_alamat_receive as har')->select('har.id_alamat_receive', 'har.custname_receive', 'har.address_receive', 'har.phone_receive', 'har.city_receive', 'kota.name as name_city', 'har.main_address', 'har.choosen')->join('kota', 'kota.id_kota', '=', 'har.city_receive')->where('har.custid_order', $custid)->groupBy('har.id_alamat_receive')->orderBy('har.main_address', 'DESC')->orderBy('har.id_alamat_receive', 'ASC')->distinct()->paginate(2);

        return view('address_list_several_en')->with('data', $address);
    }

    public function getAddressListSales($custid){
        $val_custid = $this->decrypt($custid);

        $address = DB::table('history_alamat_receive as har')->select('har.id_alamat_receive', 'har.custname_receive', 'har.address_receive', 'har.phone_receive', 'har.city_receive', 'kota.name as name_city', 'har.main_address', 'har.choosen', 'har.custid_order')->join('kota', 'kota.id_kota', '=', 'har.city_receive')->where('har.custid_order', $val_custid)->groupBy('har.id_alamat_receive')->orderBy('har.main_address', 'DESC')->orderBy('har.id_alamat_receive', 'ASC')->distinct()->paginate(2);

        return view('address_list_sales')->with('data', $address);
    }

    public function getNewAddressListSales($custid){
        $val_custid = $this->decrypt($custid);

        $address = DB::table('history_alamat_receive as har')->select('har.id_alamat_receive', 'har.custname_receive', 'har.address_receive', 'har.phone_receive', 'har.city_receive', 'kota.name as name_city', 'har.main_address', 'har.choosen', 'har.custid_order')->join('kota', 'kota.id_kota', '=', 'har.city_receive')->where('har.custid_order', $val_custid)->groupBy('har.id_alamat_receive')->orderBy('har.main_address', 'DESC')->orderBy('har.id_alamat_receive', 'ASC')->distinct()->paginate(2);

        return view('address_list_new_sales')->with('data', $address);
    }

    public function getEditAddressListSales($custid, $nomor_sj_produk){
        $val_custid = $this->decrypt($custid);
        $val_nomor_sj_produk = $this->decrypt($nomor_sj_produk);

        $address = DB::table('history_alamat_receive as har')->select('har.id_alamat_receive', 'har.custname_receive', 'har.address_receive', 'har.phone_receive', 'har.city_receive', 'kota.name as name_city', 'har.main_address', 'har.choosen', 'har.custid_order')->join('kota', 'kota.id_kota', '=', 'har.city_receive')->where('har.custid_order', $val_custid)->groupBy('har.id_alamat_receive')->orderBy('har.main_address', 'DESC')->orderBy('har.id_alamat_receive', 'ASC')->distinct()->paginate(2);

        return view('address_list_edit_sales', ['data' => $address, 'nomor_sj_produk' => $val_nomor_sj_produk]);
    }

    public function getAddressListSeveralSales($custid){
        $val_custid = $this->decrypt($custid);

        $address = DB::table('history_alamat_receive as har')->select('har.id_alamat_receive', 'har.custname_receive', 'har.address_receive', 'har.phone_receive', 'har.city_receive', 'kota.name as name_city', 'har.main_address', 'har.choosen', 'har.custid_order')->join('kota', 'kota.id_kota', '=', 'har.city_receive')->where('har.custid_order', $val_custid)->groupBy('har.id_alamat_receive')->orderBy('har.main_address', 'DESC')->orderBy('har.id_alamat_receive', 'ASC')->distinct()->paginate(2);

        return view('address_list_several_sales')->with('data', $address);
    }

    public function getDetailOrderProducts($nomor_sj){

        $val_nomor_sj = $this->decrypt($nomor_sj);

        $data = DB::table('order_receipt_produk as or')->select('or.nomor_order_receipt_produk', 'or.kode_produk', 'prd.nama_produk as nama_produk', 'or.qty')->join('products as prd', 'prd.kode_produk', '=', 'or.kode_produk')->where('or.nomor_order_receipt', $val_nomor_sj)->get();

        return Response()->json($data);
    }

    public function getDetailOrder($nomor_sj, $nomor_sj_produk){

        $val_nomor_sj = $this->decrypt($nomor_sj);
        $val_nomor_sj_produk = $this->decrypt($nomor_sj_produk);
        $arrayForTable = [];

        $data = DB::table('order_receipt as or')->select('cus.custname as nama_cust_order', 'cus.address as alamat_cust_order', 'cus.phone as phone_cust_order', 'kota_a.name as city_cust_order', 'cus.npwp as npwp_cust_order', 'orprd.custname_receive as nama_cust_receive', 'orprd.custalamat_receive as alamat_cust_receive', 'orprd.phone_receive as phone_cust_receive', 'kota_b.name as city_cust_receive', 'orprd.tanggal_kirim', 'or.status_order', 'orprd.ekspedisi', 'eks.nama_ekspedisi', 'orprd.total_price', 'or.keterangan_order', 'orprd.keterangan_quotation', 'orprd.id_alamat', 'cus.crd', 'or.nomor_po', 'or.several_address', 'or.tanggal_order', 'orprd.status as status_prd', 'orprd.nomor_order_receipt_produk')->join("order_receipt_produk as orprd", "orprd.nomor_order_receipt", "=", "or.nomor_order_receipt")->join("customers as cus", "cus.custid", "=", "or.custid")->join("kota as kota_a", "kota_a.id_kota", "=", "cus.city")->join("kota as kota_b", "kota_b.id_kota", "=", "orprd.custkota_receive")->join("history_alamat_receive as har", "har.custid_order", "=", "or.custid")->leftJoin("ekspedisi as eks", "eks.kode_ekspedisi", "=", "orprd.ekspedisi")->where('or.nomor_order_receipt', $val_nomor_sj)->where('orprd.nomor_order_receipt_produk', $val_nomor_sj_produk)->first();

        $data_referensi = DB::table('rencana_produksi_referensi')->select('nomor_rencana_produksi')->where('nomor_referensi', $val_nomor_sj)->get();

        foreach($data_referensi as $data_referensi){
            $arrayForTable[] = $data_referensi->nomor_rencana_produksi;
        }

        return Response()->json(['data' => $data, 'referensi' => $arrayForTable]);
    }

    public function getDetailOrderCust($nomor_sj, $nomor_sj_produk){
        $val_nomor_sj = $this->decrypt($nomor_sj);
        $val_nomor_sj_produk = $this->decrypt($nomor_sj_produk);

        $data = DB::table('order_receipt as or')->select('cus.custname as nama_cust_order', 'cus.address as alamat_cust_order', 'cus.phone as phone_cust_order', 'kota_a.name as city_cust_order', 'cus.npwp as npwp_cust_order', 'orprd.custname_receive as nama_cust_receive', 'orprd.custalamat_receive as alamat_cust_receive', 'orprd.phone_receive as phone_cust_receive', 'kota_b.name as city_cust_receive', 'orprd.tanggal_kirim', 'or.status_order', 'orprd.ekspedisi', 'eks.nama_ekspedisi', 'orprd.total_price', 'or.keterangan_order', 'orprd.keterangan_quotation', 'orprd.id_alamat', 'cus.crd', 'or.nomor_po', 'or.several_address', 'or.tanggal_order', 'orprd.status as status_prd', 'orprd.nomor_order_receipt_produk')->join("order_receipt_produk as orprd", "orprd.nomor_order_receipt", "=", "or.nomor_order_receipt")->join("customers as cus", "cus.custid", "=", "or.custid")->join("kota as kota_a", "kota_a.id_kota", "=", "cus.city")->join("kota as kota_b", "kota_b.id_kota", "=", "orprd.custkota_receive")->join("history_alamat_receive as har", "har.custid_order", "=", "or.custid")->leftJoin("ekspedisi as eks", "eks.kode_ekspedisi", "=", "orprd.ekspedisi")->where('or.nomor_order_receipt', $val_nomor_sj)->where('orprd.nomor_order_receipt_produk', $val_nomor_sj_produk)->first();

        return Response()->json($data);
    }

    public function getDropdownOrderSalesEkspedisi($custid, $nomor_sj, $id_alamat, $nomor_sj_produk){
        $val_custid = $this->decrypt($custid);
        $val_nomor_sj = $this->decrypt($nomor_sj);
        $val_id_alamat = $this->decrypt($id_alamat);
        $val_nomor_sj_produk = $this->decrypt($nomor_sj_produk);

        $produk = DB::table('order_receipt as ord')->select('orprd.kode_produk')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.custid', $val_custid)->where('ord.nomor_order_receipt', $val_nomor_sj)->where('orprd.nomor_order_receipt_produk', $val_nomor_sj_produk)->first();


        $data = DB::table('ekspedisi as eks')->select('eks.kode_ekspedisi', 'eks.nama_ekspedisi', 'eks.harga_pokok', 'eks.harga_stapel', 'eks.harga_ekspedisi_total', 'eks.harga_ekspedisi_kg', 'eks.total_harga_kg', 'eks.diskon', 'eks.ppn', 'eks.keterangan')->where('eks.custid', $val_custid)->where('eks.alamat_kirim', $val_id_alamat)->where('eks.kode_produk', $produk->kode_produk)->get();

        return Response()->json($data);
    }

    public function getDropdownNewProductsEkspedisi($custid, $id_alamat, $kode_produk){
        $val_custid = $this->decrypt($custid);
        $val_kode_produk = $this->decrypt($kode_produk);
        $val_id_alamat = $this->decrypt($id_alamat);

        $data = DB::table('ekspedisi as eks')->select('eks.kode_ekspedisi', 'eks.nama_ekspedisi', 'eks.harga_pokok', 'eks.harga_stapel', 'eks.harga_ekspedisi_total', 'eks.harga_ekspedisi_kg', 'eks.total_harga_kg', 'eks.diskon', 'eks.ppn', 'eks.keterangan')->where('eks.custid', $val_custid)->where('eks.alamat_kirim', $val_id_alamat)->where('eks.kode_produk', $val_kode_produk)->get();

        return Response()->json($data);
    }

    public function getDetailOrderEdit($nomor_sj, $nomor_sj_produk){
        $val_nomor_sj = $this->decrypt($nomor_sj);
        $val_nomor_sj_produk = $this->decrypt($nomor_sj_produk);

        $data = DB::table('order_receipt as ord')->select('orprd.kode_produk', 'orprd.ekspedisi as kode_ekspedisi', 'eks.nama_ekspedisi', 'orprd.id_alamat', 'orprd.tanggal_kirim', 'orprd.kode_produk', 'orprd.qty', 'orprd.discount', 'orprd.ppn', 'orprd.staple_cost', 'orprd.ekspedisi_cost', 'orprd.harga_satuan', 'orprd.additional_price', 'orprd.total_price', 'orprd.keterangan_quotation')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->join('ekspedisi as eks', 'eks.kode_ekspedisi', '=', 'orprd.ekspedisi')->where('ord.nomor_order_receipt', $val_nomor_sj)->where('orprd.nomor_order_receipt_produk', $val_nomor_sj_produk)->first();

        return Response()->json($data);
    }

    public function getDropdownInputOrderSalesEkspedisi($custid, $id_alamat){
        $produk = DB::table('order_receipt as ord')->select('ord.kode_produk')->where('ord.custid', $custid)->where('ord.nomor_order_receipt', $nomor_sj)->first();


        $data = DB::table('ekspedisi as eks')->select('eks.kode_ekspedisi', 'eks.nama_ekspedisi', 'eks.harga_pokok', 'eks.harga_stapel', 'eks.harga_ekspedisi_total', 'eks.harga_ekspedisi_kg', 'eks.total_harga_kg', 'eks.diskon', 'eks.ppn', 'eks.keterangan')->where('eks.custid', $custid)->where('eks.alamat_kirim', $id_alamat)->where('eks.kode_produk', $produk->kode_produk)->get();

        return Response()->json($data);
    }

    public function getProductsOrder(Request $request){
        $data = DB::table('order_receipt_produk as or')->select('prod.nama_produk as nama_produk', 'or.qty')->join("products as prod", "prod.kode_produk", "=", "or.kode_produk")->where('or.nomor_order_receipt', $request->nomor_sj)->get();

        return datatables()->of($data)->make(true);
    }

    public function getProductsOrderAdmin(Request $request){
        $data = DB::table('order_receipt_produk as or')->select('prod.nama_produk as nama_produk', 'or.qty', 'or.harga_satuan as harga', 'or.total_price as total')->join("products as prod", "prod.kode_produk", "=", "or.kode_produk")->where('or.nomor_order_receipt', $request->nomor_sj)->get();

        return datatables()->of($data)->make(true);
    }

    public function getProductsOrderAdminEdit(Request $request){
        $data = DB::table('order_receipt_produk as or')->select('or.nomor_order_receipt_produk', 'prod.nama_produk as nama_produk', 'or.qty', 'or.harga_satuan as harga', 'or.total_price as total')->join("products as prod", "prod.kode_produk", "=", "or.kode_produk")->where('or.nomor_order_receipt', $request->nomor_sj)->get();

        return datatables()->of($data)->addColumn('action', 'button/action_button_edit_orders_sales')->rawColumns(['action'])->make(true);
    }

    public function chooseAddress(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        Session::forget('kode_alamat');
        Session::forget('name_receive');
        Session::forget('address_receive');
        Session::forget('city_receive');
        Session::forget('name_city_receive');
        Session::forget('phone_receive');

        $custid = Session::get('custid');

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive', 'main_address', 'choosen')->where('custid_order', $custid)->where('id_alamat_receive', $request->get('addressid'))->first();

        Session::put('kode_alamat', $request->get('addressid'));
        Session::put('name_receive', $data_alamat->custname_receive);
        Session::put('address_receive', $data_alamat->address_receive);
        Session::put('city_receive', $data_alamat->city_receive);
        Session::put('phone_receive', $data_alamat->phone_receive);

        $data = ModelKota::where('id_kota', $data_alamat->city_receive)->first();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
            Session::put('name_city_receive', $data->name);
        }

        ModelAlamatHistory::where('custid_order', $custid)->where('choosen', 1)->update(['choosen' => 0]);

        ModelAlamatHistory::where('custid_order', $custid)->where('id_alamat_receive', $request->get('addressid'))->update(['choosen' => 1]);

        Session::put('change_address', TRUE);

        return Response()->json($arr);
    }

    public function chooseAddressSeveral(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $custid = Session::get('custid');

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive', 'main_address', 'choosen')->where('custid_order', $custid)->where('id_alamat_receive', $request->get('addressid'))->first();

        $data = ModelKota::where('id_kota', $data_alamat->city_receive)->first();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
            Session::put('name_city_receive', $data->name);
        }

        ModelAlamatHistory::where('custid_order', $custid)->where('id_alamat_receive', $request->get('addressid'))->update(['choosen' => 1]);

        $alamat = ModelAlamatHistory::where('custid_order', $custid)->where('choosen', 1)->get();

        if(count($alamat) > 1){
            Session::put('several_address', TRUE);
            Session::forget('kode_alamat');
            Session::forget('name_receive');
            Session::forget('address_receive');
            Session::forget('city_receive');
            Session::forget('name_city_receive');
            Session::forget('phone_receive');
        }else{
            Session::put('several_address', FALSE);
            Session::put('kode_alamat', $request->get('addressid'));
            Session::put('name_receive', $data_alamat->custname_receive);
            Session::put('address_receive', $data_alamat->address_receive);
            Session::put('city_receive', $data_alamat->city_receive);
            Session::put('phone_receive', $data_alamat->phone_receive);
        }

        return Response()->json($arr);
    }

    public function cancelAddress(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $custid = Session::get('custid');

        ModelAlamatHistory::where('custid_order', $custid)->where('id_alamat_receive', $request->get('addressid'))->update(['choosen' => 0]);

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive', 'main_address', 'choosen')->where('custid_order', $custid)->where('id_alamat_receive', $request->get('addressid'))->first();

        if($data_alamat){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        $alamat = ModelAlamatHistory::where('custid_order', $custid)->where('choosen', 1)->get();

        if(count($alamat) > 1){
            Session::put('several_address', TRUE);
            Session::forget('kode_alamat');
            Session::forget('name_receive');
            Session::forget('address_receive');
            Session::forget('city_receive');
            Session::forget('name_city_receive');
            Session::forget('phone_receive');
        }else{
            Session::put('several_address', FALSE);
            Session::put('kode_alamat', $request->get('addressid'));
            Session::put('name_receive', $data_alamat->custname_receive);
            Session::put('address_receive', $data_alamat->address_receive);
            Session::put('city_receive', $data_alamat->city_receive);
            Session::put('phone_receive', $data_alamat->phone_receive);
        }

        return Response()->json($data_alamat);
    }

    public function deleteAddress(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $custid = Session::get('custid');

        $data = ModelAlamatHistory::where('custid_order', $custid)->where('id_alamat_receive', $request->get('addressid'))->delete();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        Session::put('change_address', TRUE);

        return Response()->json($arr);
    }

    public function primaryAddress(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $custid = Session::get('custid');

        ModelAlamatHistory::where('custid_order', $custid)->where('main_address', 1)->update(['main_address' => 0]);

        $data = ModelAlamatHistory::where('custid_order', $custid)->where('id_alamat_receive', $request->get('addressid'))->update(['main_address' => 1]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function chooseAddressSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('choosen', 1)->update(['choosen' => 0]);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('id_alamat_receive', $request->get('addressid'))->update(['choosen' => 1]);

        $data_alamat = ModelAlamatHistory::select('custid_order', 'id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive', 'main_address', 'choosen')->where('custid_order', $request->get('custid'))->where('id_alamat_receive', $request->get('addressid'))->first();

        if($data_alamat){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($data_alamat);
    }

    public function chooseAddressSalesEdit(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive')->where('id_alamat_receive', $request->get('addressid'))->first();

        ModelOrdersProduk::where('nomor_order_receipt_produk', $request->get('nomor_sj_produk'))->update(['id_alamat' => $data_alamat->id_alamat_receive, 'custname_receive' => $data_alamat->custname_receive, 'custalamat_receive' => $data_alamat->address_receive, 'custkota_receive' => $data_alamat->city_receive, 'phone_receive' => $data_alamat->phone_receive]);

        $data = ModelOrdersProduk::where('nomor_order_receipt_produk', $request->get('nomor_sj_produk'))->first();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($data);
    }

    public function chooseAddressSeveralSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('id_alamat_receive', $request->get('addressid'))->update(['choosen' => 1]);

        $data_alamat = ModelAlamatHistory::select('custid_order', 'id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive', 'main_address', 'choosen')->where('custid_order', $request->get('custid'))->where('id_alamat_receive', $request->get('addressid'))->first();

        if($data_alamat){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($data_alamat);
    }

    public function cancelAddressSeveralSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('id_alamat_receive', $request->get('addressid'))->update(['choosen' => 0]);

        $data_alamat = ModelAlamatHistory::select('custid_order', 'id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive', 'main_address', 'choosen')->where('custid_order', $request->get('custid'))->where('id_alamat_receive', $request->get('addressid'))->first();

        if($data_alamat){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($data_alamat);
    }

    public function deleteAddressSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $custid = Session::get('custid');

        $data = ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('id_alamat_receive', $request->get('addressid'))->delete();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function primaryAddressSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $custid = Session::get('custid');

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 1)->update(['main_address' => 0]);

        $data = ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('id_alamat_receive', $request->get('addressid'))->update(['main_address' => 1]);

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function changeAddress(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        Session::forget('kode_alamat');
        Session::forget('name_receive');
        Session::forget('address_receive');
        Session::forget('city_receive');
        Session::forget('name_city_receive');
        Session::forget('phone_receive');

        $this->validate($request, [
            'name_receive' => 'required',
            'address_receive' => 'required',
            'city_receive' => 'required',
            'phone_receive' => 'required',
        ]);

        $tanggal_history = date('Y');
        $custid = Session::get('custid');

        ModelAlamatHistory::where('custid_order', $custid)->where('choosen', 1)->update(['choosen' => 0]);

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
        $alamat->custid_order = $custid;
        $alamat->custname_receive = $request->name_receive;
        $alamat->address_receive = $request->address_receive;
        $alamat->city_receive = $request->city_receive;
        $alamat->phone_receive = $request->phone_receive;
        $alamat->main_address = 0;
        $alamat->choosen = 1;
        $alamat->save();

        Session::put('kode_alamat', $kode_alamat);
        Session::put('name_receive', $request->name_receive);
        Session::put('address_receive', $request->address_receive);
        Session::put('city_receive', $request->city_receive);
        Session::put('phone_receive', $request->phone_receive);

        $data = ModelKota::where('id_kota', $request->city_receive)->first();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
            Session::put('name_city_receive', $data->name);
        }

        Session::put('change_address', TRUE);

        return Response()->json($arr);
    }

    public function changeAddressAdmin(Request $request){
        $tanggal_history = date('Y');

        $count_alamat = ModelAlamatHistory::select('id_alamat_receive')->where('custid_order', $request->new_address_custid)->count();

        if($count_alamat != 0){
            ModelAlamatHistory::where('custid_order', $request->new_address_custid)->where('choosen', 1)->update(['choosen' => 0]);
        }

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
        $alamat->custid_order = $request->new_address_custid;
        $alamat->custname_receive = $request->new_address_name;
        $alamat->address_receive = $request->new_address_alamat;
        $alamat->city_receive = $request->new_address_kota;
        $alamat->phone_receive = $request->new_address_telepon;
        if($count_alamat != 0){
            $alamat->main_address = 0;
        }else{
            $alamat->main_address = 1;
        }
        $alamat->choosen = 1;
        $alamat->save();

        $data = ModelAlamatHistory::where('custid_order', $request->new_address_custid)->where('choosen', 1)->first();

        return Response()->json($data);
    }

    public function changeAddressAdminEdit(Request $request){
        $tanggal_history = date('Y');

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
                        $kode_alamat = "HA" . $tanggal_history . "-" . ($alamat_count + 1);
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
        $alamat->custid_order = $request->edit_new_address_custid;
        $alamat->custname_receive = $request->edit_new_address_name;
        $alamat->address_receive = $request->edit_new_address_alamat;
        $alamat->city_receive = $request->edit_new_address_kota;
        $alamat->phone_receive = $request->edit_new_address_telepon;
        $alamat->main_address = 0;
        $alamat->choosen = 0;
        $alamat->save();

        ModelOrdersProduk::where('nomor_order_receipt_produk', $request->edit_new_address_nomor_sj_produk)->update(['id_alamat' => $kode_alamat, 'custname_receive' => $request->edit_new_address_name, 'custalamat_receive' => $request->edit_new_address_alamat, 'custkota_receive' => $request->edit_new_address_kota, 'phone_receive' => $request->edit_new_address_telepon]);

        $data = ModelOrdersProduk::where('nomor_order_receipt_produk', $request->edit_new_address_nomor_sj_produk)->first();

        return Response()->json($data);
    }

    public function addProducts(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if($request->select_address_products == null || $request->select_address_products == ''){
            $data = DB::table('temp_order')->insert(["custid" => $request->custid, "kode_produk" => $request->products, "quantity" => $request->quantity]);
            $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->products)->first();
            DB::table('products')->where('kode_produk', $request->products)->update(['saldo' => ($data_prd->saldo - ($request->quantity / $data_prd->weight))]);

            $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

            if($cek){
                DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->quantity / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->quantity / $data_prd->weight))]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->quantity / $data_prd->weight)) . ' Sak']);
            }else{
                DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->quantity / $data_prd->weight))]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->quantity / $data_prd->weight)) . ' Sak']);
            }
        }else{
            $data = DB::table('temp_order')->insert(["address_receive" => $request->select_address_products, "delivery_date" => $request->tanggal_kirim_products, "custid" => $request->custid, "kode_produk" => $request->products, "quantity" => $request->quantity]);
            $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->products)->first();
            DB::table('products')->where('kode_produk', $request->products)->update(['saldo' => ($data_prd->saldo - ($request->quantity / $data_prd->weight))]);

            $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

            if($cek){
                DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->quantity / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->quantity / $data_prd->weight))]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->quantity / $data_prd->weight)) . ' Sak']);
            }else{
                DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->quantity / $data_prd->weight))]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->quantity / $data_prd->weight)) . ' Sak']);
            }
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($request->products);
    }

    public function addProductsSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        if($request->input_add_quantity == null || $request->input_add_quantity == ''){
            if($request->select_address_products == null || $request->select_address_products == ''){
                $data = DB::table('temp_order')->insert(["delivery_date" => $request->input_tanggal_kirim_products, "custid" => $request->input_custid, "kode_produk" => $request->input_add_products, "quantity" => $request->select_add_quantity]);
                $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->input_add_products)->first();
                DB::table('products')->where('kode_produk', $request->input_add_products)->update(['saldo' => ($data_prd->saldo - ($request->select_add_quantity / $data_prd->weight))]);

                $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

                if($cek){
                    DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->select_add_quantity / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->select_add_quantity / $data_prd->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->select_add_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->select_add_quantity / $data_prd->weight)) . ' Sak']);
                }else{
                    DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->select_add_quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->select_add_quantity / $data_prd->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->select_add_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->select_add_quantity / $data_prd->weight)) . ' Sak']);
                }
            }else{
                $data = DB::table('temp_order')->insert(["address_receive" => $request->select_address_products, "delivery_date" => $request->input_tanggal_kirim_products, "custid" => $request->input_custid, "kode_produk" => $request->input_add_products, "quantity" => $request->select_add_quantity]);
                $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->input_add_products)->first();
                DB::table('products')->where('kode_produk', $request->input_add_products)->update(['saldo' => ($data_prd->saldo - ($request->select_add_quantity / $data_prd->weight))]);

                $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

                if($cek){
                    DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->select_add_quantity / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->select_add_quantity / $data_prd->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->select_add_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->select_add_quantity / $data_prd->weight)) . ' Sak']);
                }else{
                    DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->select_add_quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->select_add_quantity / $data_prd->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->select_add_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->select_add_quantity / $data_prd->weight)) . ' Sak']);
                }
            }
        }else{
            if($request->select_address_products == null || $request->select_address_products == ''){
                $data = DB::table('temp_order')->insert(["delivery_date" => $request->input_tanggal_kirim_products, "custid" => $request->input_custid, "kode_produk" => $request->input_add_products, "quantity" => $request->input_add_quantity]);
                $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->input_add_products)->first();
                DB::table('products')->where('kode_produk', $request->input_add_products)->update(['saldo' => ($data_prd->saldo - ($request->input_add_quantity / $data_prd->weight))]);

                $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

                if($cek){
                    DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->input_add_quantity / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->input_add_quantity / $data_prd->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->input_add_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->input_add_quantity / $data_prd->weight)) . ' Sak']);
                }else{
                    DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->input_add_quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->input_add_quantity / $data_prd->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->input_add_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->input_add_quantity / $data_prd->weight)) . ' Sak']);
                }
            }else{
                $data = DB::table('temp_order')->insert(["address_receive" => $request->select_address_products, "delivery_date" => $request->input_tanggal_kirim_products, "custid" => $request->input_custid, "kode_produk" => $request->input_add_products, "quantity" => $request->input_add_quantity]);
                $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->input_add_products)->first();
                DB::table('products')->where('kode_produk', $request->input_add_products)->update(['saldo' => ($data_prd->saldo - ($request->input_add_quantity / $data_prd->weight))]);

                $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

                if($cek){
                    DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->input_add_quantity / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->input_add_quantity / $data_prd->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->input_add_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->input_add_quantity / $data_prd->weight)) . ' Sak']);
                }else{
                    DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->input_add_quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->input_add_quantity / $data_prd->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->input_add_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->input_add_quantity / $data_prd->weight)) . ' Sak']);
                }
            }
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json(['address' => $request->select_address_products, 'output' => $request->input_add_quantity, 'kode_produk' => $request->input_add_products]);
    }

    public function addNewProducts(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_order')->insert(["custid" => $request->new_custid, "kode_produk" => $request->new_products, "quantity" => $request->new_quantity]);

        $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->new_products)->first();
        DB::table('products')->where('kode_produk', $request->new_products)->update(['saldo' => ($data_prd->saldo - ($request->new_quantity / $data_prd->weight))]);

        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

        if($cek){
            DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->new_quantity / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->new_quantity / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->new_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->new_quantity / $data_prd->weight)) . ' Sak']);
        }else{
            DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->new_quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->new_quantity / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->new_quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->new_quantity / $data_prd->weight)) . ' Sak']);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($request->new_products);
    }

    public function addProductsRekomendasi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = DB::table('temp_order')->insert(["custid" => $request->get('custid'), "kode_produk" => $request->get('produk'), "quantity" => $request->get('quantity')]);

        $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->get('produk'))->first();
        DB::table('products')->where('kode_produk', $request->get('produk'))->update(['saldo' => ($data_prd->saldo - ($request->get('quantity') / $data_prd->weight))]);

        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

        if($cek){
            DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->get('quantity') / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->get('quantity') / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->get('quantity') / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->get('quantity') / $data_prd->weight)) . ' Sak']);
        }else{
            DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->get('quantity') / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->get('quantity') / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->get('quantity') / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->get('quantity') / $data_prd->weight)) . ' Sak']);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteProducts(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->get('kode_produk'))->first();
        $data_tmp = DB::table('temp_order')->select('quantity')->where('kode_produk', $request->get('kode_produk'))->first();
        DB::table('products')->where('kode_produk', $request->get('kode_produk'))->update(['saldo' => ($data_prd->saldo + ($data_tmp->quantity / $data_prd->weight))]);
        $validasi = DB::table('temp_order')->where('custid', $request->get('custid'))->where('kode_produk', $request->get('kode_produk'))->delete();

        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

        DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman - ($data_tmp->quantity / $data_prd->weight)), 'saldo' => ($cek->saldo + ($data_tmp->quantity / $data_prd->weight))]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($data_tmp->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($data_tmp->quantity / $data_prd->weight)) . ' Sak']);

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($request->get('kode_produk'));
    }

    public function deleteProductsEdit(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = DB::table('order_receipt_produk')->where('nomor_order_receipt_produk', $request->get('nomor_sj_produk'))->delete();

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Produk Dengan Kode ' . $request->get('nomor_sj_produk')]);

        return Response()->json($arr);
    }

    public function editProductsEdit(Request $request){
        $edit_data = DB::table('order_receipt_produk')->select('kode_produk', 'qty')->where('nomor_order_receipt_produk', $request->get('nomor_sj_produk'))->first();

        return Response()->json($edit_data);
    }

    public function saveProductsEdit(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->get('produk'))->first();
        $data_ord = DB::table('order_receipt_produk')->select('qty')->where('nomor_order_receipt_produk', $request->get('nomor_sj_produk'))->first();
        DB::table('products')->where('kode_produk', $request->get('produk'))->update(['saldo' => (($data_prd->saldo + ($data_ord->qty / $data_prd->weight)) - ($request->get('qty') / $data_prd->weight))]);
        $edit_data = DB::table('order_receipt_produk')->where('nomor_order_receipt_produk', $request->get('nomor_sj_produk'))->update(['kode_produk' => $request->get('produk'), 'qty' => $request->get('qty')]);

        if($edit_data){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

        if($cek){
            DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => (($cek->pengiriman - ($data_ord->qty / $data_prd->weight)) + ($request->get('qty') / $data_prd->weight)), 'saldo' => (($cek->saldo + ($data_ord->qty / $data_prd->weight)) - ($request->get('qty') / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($data_ord->qty / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo + ($data_ord->qty / $data_prd->weight)) . ' Sak']);
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->get('qty') / $data_prd->weight) . ' Sak. Total Saldo = ' . (($cek->saldo + ($data_ord->qty / $data_prd->weight)) - ($request->get('qty') / $data_prd->weight)) . ' Sak']);
        }else{
            DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->get('qty') / $data_prd->weight), 'saldo' => (($data_prd->saldo + ($data_ord->qty / $data_prd->weight)) - ($request->get('qty') / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($data_ord->qty / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo + ($data_ord->qty / $data_prd->weight)) . ' Sak']);
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->get('qty') / $data_prd->weight) . ' Sak. Total Saldo = ' . (($data_prd->saldo + ($data_ord->qty / $data_prd->weight)) - ($request->get('qty') / $data_prd->weight)) . ' Sak']);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Produk ' . $request->get('produk') . ' Dengan Kode ' . $request->get('nomor_sj_produk')]);

        return Response()->json($arr);
    }

    public function deleteProductsSeveral(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->get('kode_produk'))->first();
        $data_tmp = DB::table('temp_order')->select('quantity')->where('kode_produk', $request->get('kode_produk'))->first();
        DB::table('products')->where('kode_produk', $request->get('kode_produk'))->update(['saldo' => ($data_prd->saldo + ($data_tmp->quantity / $data_prd->weight))]);
        $validasi = DB::table('temp_order')->where('custid', $request->get('custid'))->where('address_receive', $request->get('id_alamat'))->where('kode_produk', $request->get('kode_produk'))->delete();

        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

        if($cek){
            DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman - ($data_tmp->quantity / $data_prd->weight)), 'saldo' => ($cek->saldo + ($data_tmp->quantity / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($data_tmp->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo + ($data_tmp->quantity / $data_prd->weight)) . ' Sak']);
        }else{
            DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($data_tmp->quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo + ($data_tmp->quantity / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($data_tmp->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo + ($data_tmp->quantity / $data_prd->weight)) . ' Sak']);
        }

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($request->get('kode_produk'));
    }

    public function deleteProductsDelivdate(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->get('kode_produk'))->first();
        $data_tmp = DB::table('temp_order')->select('quantity')->where('kode_produk', $request->get('kode_produk'))->first();
        DB::table('products')->where('kode_produk', $request->get('kode_produk'))->update(['saldo' => ($data_prd->saldo + ($data_tmp->quantity / $data_prd->weight))]);
        $validasi = DB::table('temp_order')->where('custid', $request->get('custid'))->where('delivery_date', $request->get('tgl_kirim'))->where('kode_produk', $request->get('kode_produk'))->delete();

        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

        if($cek){
            DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman - ($data_tmp->quantity / $data_prd->weight)), 'saldo' => ($cek->saldo + ($data_tmp->quantity / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($data_tmp->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo + ($data_tmp->quantity / $data_prd->weight)) . ' Sak']);
        }else{
            DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($data_tmp->quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo + ($data_tmp->quantity / $data_prd->weight))]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($data_tmp->quantity / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo + ($data_tmp->quantity / $data_prd->weight)) . ' Sak']);
        }

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($request->get('kode_produk'));
    }

    public function productsOrderSeveralSales(Request $request){
        $orders = DB::table('temp_order as tmp')->select("tmp.address_receive as alamat", "tmp.delivery_date as tgl_kirim", "prd.nama_produk as produk", "tmp.quantity as quantity", "tmp.custid as custid", "tmp.kode_produk as kode_produk")->join("products as prd", "prd.kode_produk", "=", "tmp.kode_produk")->where('tmp.custid', $request->custid)->get();

        return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_several')->rawColumns(['action'])->make(true);

        return view('form_order_en');
    }

    public function productsOrderDelivdateSales(Request $request){
        $orders = DB::table('temp_order as tmp')->select("tmp.delivery_date as tgl_kirim", "prd.nama_produk as produk", "tmp.quantity as quantity", "tmp.custid as custid", "tmp.kode_produk as kode_produk")->join("products as prd", "prd.kode_produk", "=", "tmp.kode_produk")->where('tmp.custid', $request->custid)->get();

        return datatables()->of($orders)->addColumn('action', 'button/action_button_orders_delivdate')->rawColumns(['action'])->make(true);

        return view('form_order_en');
    }

    public function chooseCustomerOrder($custid){
        $val_custid = $this->decrypt($custid);

        $customer = ModelCustomers::select('custname as nama_cust_order', 'address as alamat_cust_order', 'phone as phone_cust_order', 'city as city_cust_order', 'kota.name as name_city_cust_order', 'npwp as npwp_cust_order', 'crd as crd_cust_order')->leftJoin('kota', 'kota.id_kota', '=', 'customers.city')->where('custid', $val_custid)->first();

        $alamat = ModelAlamatHistory::select('id_alamat_receive as kode_alamat', 'custname_receive as nama_cust_receive', 'address_receive as alamat_cust_receive', 'phone_receive as phone_cust_receive', 'city_receive', 'kota.name as city_cust_receive')->join('kota', 'kota.id_kota', '=', 'history_alamat_receive.city_receive')->where('custid_order', $val_custid)->where('choosen', 1)->get();

        $delete = DB::table('temp_order as to')->where('custid', $val_custid)->count();

        if($delete > 0){
            $data_tmp = DB::table('temp_order')->select('kode_produk', 'quantity')->where('custid', $val_custid)->get();
            foreach($data_tmp as $tmp){
                $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $tmp->kode_produk)->first();
                DB::table('products')->where('kode_produk', $tmp->kode_produk)->update(['saldo' => ($data_prd->saldo + ($tmp->quantity / $data_prd->weight))]);

                $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

                if($cek){
                    DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($tmp->quantity / $data_prd->weight)), 'saldo' => ($cek->saldo - ($tmp->quantity / $data_prd->weight))]);
                }else{
                    DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($tmp->quantity / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($tmp->quantity / $data_prd->weight))]);
                }
            }
            DB::table('temp_order')->where('custid', $val_custid)->delete();
        }

        return Response()->json(['data_customer' => $customer, 'data_alamat' => $alamat]);
    }

    public function addOrders(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $products = DB::table('temp_order as to')->select('to.quantity as quantity', 'to.kode_produk as kode_produk')->where('custid', $request->get('custid'))->get();

        $data_orders = ModelOrders::select('nomor_order_receipt')->where('nomor_order_receipt', 'like', 'ORD' . $tanggal . '%')->orderBy('nomor_order_receipt', 'asc')->distinct()->get();

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive')->where('custid_order', $request->get('custid'))->where('choosen', 1)->first();

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
                        $kode_ord = "ORD-" . $tanggal . ($data_count + 1);
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
        $data->tanggal_order = date('Y-m-d');
        $data->custid = $request->get('custid');
        $data->nomor_po = $request->get('nomor_po');
        $data->status_order = 1;
        $data->order_created_by = 8;
        $data->keterangan_order = $request->keterangan_order;
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
                        $kode_po = "PRORD-" . $tanggal . ($dataprdo_count + 1);
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
        $data_po->nomor_po = $request->get('nomor_po');
        $data_po->custid = $request->get('custid');
        $data_po->tanggal_order = date('Y-m-d');
        $data_po->dropdown_rencana_produksi = 1;
        $data_po->save();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        foreach($products as $prod){
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
                            $kode_ord_prd = "ORPRD-" . $tanggal . ($data_count_prd + 1);
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
            $data_prd->id_alamat = $data_alamat->id_alamat_receive;
            $data_prd->custname_receive = $request->get('name');
            $data_prd->custalamat_receive = $request->get('address');
            $data_prd->custkota_receive = $request->get('city');
            $data_prd->phone_receive = $request->get('phone');
            $data_prd->tanggal_kirim = $request->get('tanggal_kirim');
            $data_prd->kode_produk = $prod->kode_produk;
            $data_prd->qty = $prod->quantity;
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
                            $kode_po_dt = "PRORDT-" . $tanggal . ($dataprdo_dt_count + 1);
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
            $data_podt->tanggal_kirim = $request->get('tanggal_kirim');
            $data_podt->kode_produk = $prod->kode_produk;
            $data_podt->qty = $prod->quantity;
            $data_podt->save();

            $history = new ModelHistoryOrders();
            $history->nomor_order = $kode_ord;
            $history->nomor_po = $request->get('nomor_po');
            $history->custid = $request->get('custid');
            $history->alamat_receive_history = $data_alamat->id_alamat_receive;
            $history->kode_produk = $prod->kode_produk;
            $history->quantity = $prod->quantity;
            $history->save();
        }

        DB::table('temp_order')->where('custid', $request->get('custid'))->delete();

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 1)->update(['choosen' => 1]);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 0)->update(['choosen' => 0]);

        $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

        $new_data_notif = DB::table('order_receipt as ord')->select('ord.nomor_order_receipt as nomor_order_receipt', 'ord.custid as custid', 'cust.custname as custname_order')->join('customers as cust', 'cust.custid', '=', 'ord.custid')->where('nomor_order_receipt', $kode_ord)->first();

        Notification::send($user_notif, new NotifNewOrders($new_data_notif));

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $request->get('custid'), 'status' => 1, 'action' => 'Customer ' . $request->get('custid') . ' Input Order Nomor ' . $kode_ord]);

        return Response()->json($arr);
    }

    public function addOrdersSeveral(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $products = DB::table('temp_order as to')->select('to.address_receive as address_receive', 'to.delivery_date as delivery_date', 'to.quantity as quantity', 'to.kode_produk as kode_produk')->where('custid', $request->get('custid'))->get();

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
                        $kode_ord = "ORD-" . $tanggal . ($data_count + 1);
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
        $data->tanggal_order = date('Y-m-d');
        $data->custid = $request->get('custid');
        $data->nomor_po = $request->get('nomor_po');
        $data->status_order = 1;
        $data->order_created_by = 8;
        $data->keterangan_order = $request->keterangan_order;
        $data->several_address = 1;
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
                        $kode_po = "PRORD-" . $tanggal . ($dataprdo_count + 1);
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
        $data_po->nomor_po = $request->get('nomor_po');
        $data_po->custid = $request->get('custid');
        $data_po->tanggal_order = date('Y-m-d');
        $data_po->dropdown_rencana_produksi = 1;
        $data_po->save();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        foreach($products as $prod){
            $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

            $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive')->where('id_alamat_receive', $prod->address_receive)->first();

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
                            $kode_ord_prd = "ORPRD-" . $tanggal . ($data_count_prd + 1);
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
            $data_prd->id_alamat = $data_alamat->id_alamat_receive;
            $data_prd->custname_receive = $data_alamat->custname_receive;
            $data_prd->custalamat_receive = $data_alamat->address_receive;
            $data_prd->custkota_receive = $data_alamat->city_receive;
            $data_prd->phone_receive = $data_alamat->phone_receive;
            $data_prd->tanggal_kirim = $prod->delivery_date;
            $data_prd->kode_produk = $prod->kode_produk;
            $data_prd->qty = $prod->quantity;
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
                            $kode_po_dt = "PRORDT-" . $tanggal . ($dataprdo_dt_count + 1);
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
            $data_podt->tanggal_kirim = $prod->delivery_date;
            $data_podt->kode_produk = $prod->kode_produk;
            $data_podt->qty = $prod->quantity;
            $data_podt->save();

            $history = new ModelHistoryOrders();
            $history->nomor_order = $kode_ord;
            $history->nomor_po = $request->get('nomor_po');
            $history->custid = $request->get('custid');
            $history->alamat_receive_history = $data_alamat->id_alamat_receive;
            $history->kode_produk = $prod->kode_produk;
            $history->quantity = $prod->quantity;
            $history->save();

            if($data){
                $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
            }
        }

        DB::table('temp_order')->where('custid', $request->get('custid'))->delete();

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 1)->update(['choosen' => 1]);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 0)->update(['choosen' => 0]);

        $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

        $new_data_notif = DB::table('order_receipt as ord')->select('ord.nomor_order_receipt as nomor_order_receipt', 'ord.custid as custid', 'cust.custname as custname_order')->join('customers as cust', 'cust.custid', '=', 'ord.custid')->where('nomor_order_receipt', $kode_ord)->first();

        Notification::send($user_notif, new NotifNewOrders($new_data_notif));

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $request->get('custid'), 'status' => 1, 'action' => 'Customer ' . $request->get('custid') . ' Input Order Nomor ' . $kode_ord]);

        return Response()->json($arr);
    }

    public function addOrdersDelivdate(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $products = DB::table('temp_order as to')->select('to.delivery_date', 'to.quantity', 'to.kode_produk')->where('custid', $request->get('custid'))->get();

        $data_orders = ModelOrders::select('nomor_order_receipt')->where('nomor_order_receipt', 'like', 'ORD' . $tanggal . '%')->orderBy('nomor_order_receipt', 'asc')->distinct()->get();

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive')->where('custid_order', $request->get('custid'))->where('choosen', 1)->first();

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
                        $kode_ord = "ORD-" . $tanggal . ($data_count + 1);
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
        $data->tanggal_order = date('Y-m-d');
        $data->custid = $request->get('custid');
        $data->nomor_po = $request->get('nomor_po');
        $data->status_order = 1;
        $data->order_created_by = 8;
        $data->keterangan_order = $request->keterangan_order;
        $data->several_address = 2;
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
                        $kode_po = "PRORD-" . $tanggal . ($dataprdo_count + 1);
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
        $data_po->nomor_po = $request->get('nomor_po');
        $data_po->custid = $request->get('custid');
        $data_po->tanggal_order = date('Y-m-d');
        $data_po->dropdown_rencana_produksi = 1;
        $data_po->save();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        foreach($products as $prod){
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
                            $kode_ord_prd = "ORPRD-" . $tanggal . ($data_count_prd + 1);
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
            $data_prd->id_alamat = $data_alamat->id_alamat_receive;
            $data_prd->custname_receive = $request->get('name');
            $data_prd->custalamat_receive = $request->get('address');
            $data_prd->custkota_receive = $request->get('city');
            $data_prd->phone_receive = $request->get('phone');
            $data_prd->tanggal_kirim = $prod->delivery_date;
            $data_prd->kode_produk = $prod->kode_produk;
            $data_prd->qty = $prod->quantity;
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
                            $kode_po_dt = "PRORDT-" . $tanggal . ($dataprdo_dt_count + 1);
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
            $data_podt->tanggal_kirim = $prod->delivery_date;
            $data_podt->kode_produk = $prod->kode_produk;
            $data_podt->qty = $prod->quantity;
            $data_podt->save();

            $history = new ModelHistoryOrders();
            $history->nomor_order = $kode_ord;
            $history->nomor_po = $request->get('nomor_po');
            $history->custid = $request->get('custid');
            $history->alamat_receive_history = $data_alamat->id_alamat_receive;
            $history->kode_produk = $prod->kode_produk;
            $history->quantity = $prod->quantity;
            $history->save();
        }

        DB::table('temp_order')->where('custid', $request->get('custid'))->delete();

        if($request->get('city_order') != NULL || $request->get('city_order') != ''){
            ModelCustomers::where('custid', $request->get('custid'))->update(['city' => $request->get('city_order')]);
        }

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 1)->update(['choosen' => 1]);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 0)->update(['choosen' => 0]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $request->get('custid'), 'status' => 1, 'action' => 'Customer ' . $request->get('custid') . ' Input Order Nomor ' . $kode_ord]);

        return Response()->json($arr);
    }

    public function addOrdersAdmin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $products = DB::table('temp_order as to')->select('to.quantity as quantity', 'to.kode_produk as kode_produk')->where('custid', $request->get('custid'))->get();

        $data_orders = ModelOrders::select('nomor_order_receipt')->where('nomor_order_receipt', 'like', 'ORD' . $tanggal . '%')->orderBy('nomor_order_receipt', 'asc')->distinct()->get();

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive')->where('custid_order', $request->get('custid'))->where('choosen', 1)->first();

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
        $data->tanggal_order = date('Y-m-d');
        $data->custid = $request->get('custid');
        $data->nomor_po = $request->get('nomor_po');
        $data->status_order = 1;
        $data->order_created_by = Session::get('id_user_admin');
        $data->keterangan_order = $request->keterangan_order;
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
        $data_po->nomor_po = $request->get('nomor_po');
        $data_po->custid = $request->get('custid');
        $data_po->tanggal_order = date('Y-m-d');
        $data_po->dropdown_rencana_produksi = 1;
        $data_po->save();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        foreach($products as $prod){
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
            $data_prd->id_alamat = $data_alamat->id_alamat_receive;
            $data_prd->custname_receive = $data_alamat->custname_receive;
            $data_prd->custalamat_receive = $data_alamat->address_receive;
            $data_prd->custkota_receive = $data_alamat->city_receive;
            $data_prd->phone_receive = $data_alamat->phone_receive;
            $data_prd->tanggal_kirim = $request->get('tanggal_kirim');
            $data_prd->kode_produk = $prod->kode_produk;
            $data_prd->qty = $prod->quantity;
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
            $data_podt->tanggal_kirim = $request->get('tanggal_kirim');
            $data_podt->kode_produk = $prod->kode_produk;
            $data_podt->qty = $prod->quantity;
            $data_podt->save();
            

            $history = new ModelHistoryOrders();
            $history->nomor_order = $kode_ord;
            $history->nomor_po = $request->get('nomor_po');
            $history->custid = $request->get('custid');
            $history->alamat_receive_history = $data_alamat->id_alamat_receive;
            $history->kode_produk = $prod->kode_produk;
            $history->quantity = $prod->quantity;
            $history->save();
        }

        DB::table('temp_order')->where('custid', $request->get('custid'))->delete();

        if($request->get('city_order') != NULL || $request->get('city_order') != ''){
            ModelCustomers::where('custid', $request->get('custid'))->update(['city' => $request->get('city_order')]);
        }

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 1)->update(['choosen' => 1]);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 0)->update(['choosen' => 0]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Order Nomor ' . $kode_ord]);

        return Response()->json($arr);
    }

    public function addOrdersSeveralAdmin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $products = DB::table('temp_order as to')->select('to.address_receive', 'to.delivery_date', 'to.quantity as quantity', 'to.kode_produk as kode_produk')->where('custid', $request->get('custid'))->get();

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
        $data->tanggal_order = date('Y-m-d');
        $data->custid = $request->get('custid');
        $data->nomor_po = $request->get('nomor_po');
        $data->status_order = 1;
        $data->order_created_by = Session::get('id_user_admin');
        $data->keterangan_order = $request->keterangan_order;
        $data->several_address = 1;
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
        $data_po->nomor_po = $request->get('nomor_po');
        $data_po->custid = $request->get('custid');
        $data_po->tanggal_order = date('Y-m-d');
        $data_po->dropdown_rencana_produksi = 1;
        $data_po->save();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        foreach($products as $prod){
            $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

            $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive')->where('id_alamat_receive', $prod->address_receive)->first();

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
            $data_prd->id_alamat = $data_alamat->id_alamat_receive;
            $data_prd->custname_receive = $data_alamat->custname_receive;
            $data_prd->custalamat_receive = $data_alamat->address_receive;
            $data_prd->custkota_receive = $data_alamat->city_receive;
            $data_prd->phone_receive = $data_alamat->phone_receive;
            $data_prd->tanggal_kirim = $prod->delivery_date;
            $data_prd->kode_produk = $prod->kode_produk;
            $data_prd->qty = $prod->quantity;
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
            $data_podt->tanggal_kirim = $prod->delivery_date;
            $data_podt->kode_produk = $prod->kode_produk;
            $data_podt->qty = $prod->quantity;
            $data_podt->save();

            $history = new ModelHistoryOrders();
            $history->nomor_order = $kode_ord;
            $history->nomor_po = $request->get('nomor_po');
            $history->custid = $request->get('custid');
            $history->alamat_receive_history = $data_alamat->id_alamat_receive;
            $history->kode_produk = $prod->kode_produk;
            $history->quantity = $prod->quantity;
            $history->save();
        }

        DB::table('temp_order')->where('custid', $request->get('custid'))->delete();

        if($request->get('city_order') != NULL || $request->get('city_order') != ''){
            ModelCustomers::where('custid', $request->get('custid'))->update(['city' => $request->get('city_order')]);
        }

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 1)->update(['choosen' => 1]);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 0)->update(['choosen' => 0]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Order Nomor ' . $kode_ord]);

        return Response()->json($arr);
    }

    public function addOrdersDelivdateAdmin(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');

        $products = DB::table('temp_order as to')->select('to.delivery_date', 'to.quantity', 'to.kode_produk')->where('custid', $request->get('custid'))->get();

        $data_orders = ModelOrders::select('nomor_order_receipt')->where('nomor_order_receipt', 'like', 'ORD' . $tanggal . '%')->orderBy('nomor_order_receipt', 'asc')->distinct()->get();

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive')->where('custid_order', $request->get('custid'))->where('choosen', 1)->first();

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
        $data->tanggal_order = date('Y-m-d');
        $data->custid = $request->get('custid');
        $data->nomor_po = $request->get('nomor_po');
        $data->status_order = 1;
        $data->order_created_by = Session::get('id_user_admin');
        $data->keterangan_order = $request->keterangan_order;
        $data->several_address = 2;
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
        $data_po->nomor_po = $request->get('nomor_po');
        $data_po->custid = $request->get('custid');
        $data_po->tanggal_order = date('Y-m-d');
        $data_po->dropdown_rencana_produksi = 1;
        $data_po->save();

        if($data){
            $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
        }

        foreach($products as $prod){
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
            $data_prd->id_alamat = $data_alamat->id_alamat_receive;
            $data_prd->custname_receive = $data_alamat->custname_receive;
            $data_prd->custalamat_receive = $data_alamat->address_receive;
            $data_prd->custkota_receive = $data_alamat->city_receive;
            $data_prd->phone_receive = $data_alamat->phone_receive;
            $data_prd->tanggal_kirim = $prod->delivery_date;
            $data_prd->kode_produk = $prod->kode_produk;
            $data_prd->qty = $prod->quantity;
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
            $data_podt->tanggal_kirim = $request->delivery_date;
            $data_podt->kode_produk = $prod->kode_produk;
            $data_podt->qty = $prod->quantity;
            $data_podt->save();

            $history = new ModelHistoryOrders();
            $history->nomor_order = $kode_ord;
            $history->nomor_po = $request->get('nomor_po');
            $history->custid = $request->get('custid');
            $history->alamat_receive_history = $data_alamat->id_alamat_receive;
            $history->kode_produk = $prod->kode_produk;
            $history->quantity = $prod->quantity;
            $history->save();
        }

        DB::table('temp_order')->where('custid', $request->get('custid'))->delete();

        if($request->get('city_order') != NULL || $request->get('city_order') != ''){
            ModelCustomers::where('custid', $request->get('custid'))->update(['city' => $request->get('city_order')]);
        }

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 1)->update(['choosen' => 1]);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 0)->update(['choosen' => 0]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Order Nomor ' . $kode_ord]);

        return Response()->json($arr);
    }

    public function validasiOrdersKonfirmasi(Request $request){
        $diskon = $request->diskon_add;
        $ppn = $request->ppn_add;
        $stapel = $request->stapel_add;
        $ekspedisi = $request->ekspedisi_add;
        $pokok = $request->pokok_add;

        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $cek_ekspedisi = DB::table('ekspedisi as eks')->select('kode_ekspedisi')->where('kode_ekspedisi', $request->get('ekspedisi'))->first();

        if(!$cek_ekspedisi){
            $products = DB::table('order_receipt as ord')->select('orprd.qty as quantity', 'ord.custid', 'orprd.id_alamat', 'ord.keterangan_order', 'orprd.kode_produk')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.nomor_order_receipt', $request->nomor_order_receipt)->where('orprd.nomor_order_receipt_produk', $request->nomor_order_receipt_produk)->first();

            if($request->get('ekspedisi') == 'EKSBUANA'){
                $nama_ekspedisi = 'Buana';
            }else if($request->get('ekspedisi') == 'EKSGJAYA'){
                $nama_ekspedisi = 'Gunawan Jaya';
            }else if($request->get('ekspedisi') == 'EKSRAPI'){
                $nama_ekspedisi = 'Rapi';
            }else if($request->get('ekspedisi') == 'EKSSJAYA'){
                $nama_ekspedisi = 'Sumber Jaya';
            }else if($request->get('ekspedisi') == 'EKSTRANS'){
                $nama_ekspedisi = 'Transindo';
            }else if($request->get('ekspedisi') == 'EKSLOKAL'){
                $nama_ekspedisi = 'Kirim Lingkup Area DSGM';
            }else if($request->get('ekspedisi') == 'EKSDSGM'){
                $nama_ekspedisi = 'Ambil Sendiri';
            }else if($request->get('ekspedisi') == 'EKSPRISMA'){
                $nama_ekspedisi = 'CV Prisma';
            }else if($request->get('ekspedisi') == 'EKSNIAGA'){
                $nama_ekspedisi = 'Niaga Jaya';
            }

            $total_add_kg = 0;
            $total_add_kg = ($stapel + $ekspedisi) / $products->quantity;
            $total_add_sebelum = $pokok + $total_add_kg;
            $harga_ongkir_kg = $pokok + $total_add_kg;
            $total_price = $products->quantity * $harga_ongkir_kg;
            $total_price = $total_price - (($total_price * $diskon) / 100);
            $total_ppn = ($ppn * $total_price) / 100;
            $total_price = $total_price + $total_ppn;

            $eks_code = DB::table('ekspedisi as eks')->select('kode_ekspedisi')->where('kode_ekspedisi', 'like', $request->get('ekspedisi') . '-%')->orderBy('kode_ekspedisi', 'ASC')->distinct()->get();

            if($eks_code){
                $eks_count = $eks_code->count();
                if($eks_count > 0){
                    $num = (int) substr($eks_code[$eks_code->count() - 1]->kode_ekspedisi, 9);
                    if($eks_count != $num){
                        $ekspedisi_code = ++$eks_code[$eks_code->count() - 1]->kode_ekspedisi;
                    }else{
                        if($eks_count < 9){
                            $ekspedisi_code = $request->get('ekspedisi') . "-000" . ($eks_count + 1);
                        }else if($eks_count >= 9 && $eks_count < 99){
                            $ekspedisi_code = $request->get('ekspedisi') . "-00" . ($eks_count + 1);
                        }else if($eks_count >= 99 && $eks_count < 999){
                            $ekspedisi_code = $request->get('ekspedisi') . "-0" . ($eks_count + 1);
                        }else{
                            $ekspedisi_code = $request->get('ekspedisi') . "-" . ($eks_count + 1);
                        }
                    }
                }else{
                    $ekspedisi_code = $request->get('ekspedisi') . "-0001";
                }
            }else{
                $ekspedisi_code = $request->get('ekspedisi') . "-0001";
            }

            $add_ekspedisi = DB::table('ekspedisi')->insert(['kode_ekspedisi' => $ekspedisi_code, 'nama_ekspedisi' => $nama_ekspedisi, 'custid' => $products->custid, 'alamat_kirim' => $products->id_alamat, 'kode_produk' => $products->kode_produk, 'quantity' => $products->quantity, 'harga_pokok' => $request->pokok_add, 'harga_stapel' => $request->stapel_add, 'harga_ekspedisi_total' => $request->ekspedisi_add, 'harga_ekspedisi_kg' => $total_add_kg, 'total_harga_kg' => $harga_ongkir_kg, 'diskon' => $request->diskon_add, 'ppn' => $request->ppn_add, 'keterangan' => $products->keterangan_order]);

            $validasi = ModelOrdersProduk::where('nomor_order_receipt_produk', $request->nomor_order_receipt_produk)->update(['ekspedisi' => $ekspedisi_code, 'status' => 2, 'discount' => $request->diskon_add, 'ppn' => $request->ppn_add, 'staple_cost' => $request->stapel_add, 'ekspedisi_cost' => $request->ekspedisi_add, 'harga_satuan' => $harga_ongkir_kg, 'additional_price' => $total_add_kg, 'total_price' => $total_price, 'keterangan_quotation' => $request->keterangan_quotation, 'tanggal_quotation' => date('Y-m-d'), 'tanggal_kirim' => $request->tanggal_kirim, 'sales_val_quotation' => Session::get('id_user_admin')]);

            $history = ModelHistoryOrders::where('nomor_order', $request->nomor_order_receipt)->where('custid', $products->custid)->where('alamat_receive_history', $products->id_alamat)->update(['kode_ekspedisi' => $ekspedisi_code, 'harga_pokok' => $request->pokok_add, 'harga_stapel' => $request->stapel_add, 'harga_ekspedisi' => $total_add_kg, 'total_harga_kg' => $harga_ongkir_kg, 'diskon' => $request->diskon_add, 'ppn' => $request->ppn_add, 'keterangan' => $products->keterangan_order]);

            if($validasi){
                $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
            }

            $cek_status = ModelOrdersProduk::where('nomor_order_receipt', $request->nomor_order_receipt)->where('status', 1)->get();

            if($cek_status->count() == 0){
                ModelOrders::where('nomor_order_receipt', $request->nomor_order_receipt)->update(['status_order' => 2]);
            }
        }else{
            $products = DB::table('order_receipt as ord')->select('orprd.qty as quantity', 'ord.custid', 'orprd.id_alamat', 'ord.keterangan_order', 'orprd.kode_produk')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.nomor_order_receipt', $request->nomor_order_receipt)->where('orprd.nomor_order_receipt_produk', $request->nomor_order_receipt_produk)->first();

            $total_add_kg = ($stapel + $ekspedisi) / $products->quantity;
            $total_add_sebelum = $pokok + $total_add_kg;
            $harga_ongkir_kg = $pokok + $total_add_kg;
            $total_price = $products->quantity * $request->harga_ongkir_add;
            $total_price = $total_price - (($total_price * $diskon) / 100);
            $total_ppn = ($ppn * $total_price) / 100;
            $total_price = $total_price + $total_ppn;

            $validasi = ModelOrdersProduk::where('nomor_order_receipt_produk', $request->nomor_order_receipt_produk)->update(['ekspedisi' => $request->ekspedisi, 'status' => 2, 'discount' => $request->diskon_add, 'ppn' => $request->ppn_add, 'staple_cost' => $request->stapel_add, 'ekspedisi_cost' => $request->ekspedisi_add, 'harga_satuan' => $request->harga_ongkir_add, 'additional_price' => $total_add_kg, 'total_price' => $total_price, 'keterangan_quotation' => $request->keterangan_quotation, 'tanggal_quotation' => date('Y-m-d'), 'tanggal_kirim' => $request->tanggal_kirim, 'sales_val_quotation' => Session::get('id_user_admin')]);

            $history = ModelHistoryOrders::where('nomor_order', $request->nomor_order_receipt)->where('custid', $products->custid)->where('alamat_receive_history', $products->id_alamat)->update(['kode_ekspedisi' => $request->ekspedisi, 'harga_pokok' => $request->pokok_add, 'harga_stapel' => $request->stapel_add, 'harga_ekspedisi' => $total_add_kg, 'total_harga_kg' => $harga_ongkir_kg, 'diskon' => $request->diskon_add, 'ppn' => $request->ppn_add, 'keterangan' => $products->keterangan_order]);

            if($validasi){
                $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
            }

            $cek_status = ModelOrdersProduk::where('nomor_order_receipt', $request->nomor_order_receipt)->where('status', 1)->get();

            if($cek_status->count() == 0){
                ModelOrders::where('nomor_order_receipt', $request->nomor_order_receipt)->update(['status_order' => 2]);
            }
        }

        $dataprd = DB::table('order_receipt as ord')->select('orprd.nomor_order_receipt_produk', 'ord.several_address')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.nomor_order_receipt', $request->nomor_order_receipt)->where('orprd.nomor_order_receipt_produk', $request->nomor_order_receipt_produk)->first();

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Input Add Price pada Order Nomor ' . $request->nomor_order_receipt]);

        return Response()->json($dataprd);
    }

    public function validasiOrdersEdit(Request $request){

        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $cek_ekspedisi = DB::table('ekspedisi as eks')->select('kode_ekspedisi')->where('kode_ekspedisi', $request->get('edit_ekspedisi'))->first();

        if(!$cek_ekspedisi){
            $products = DB::table('order_receipt as ord')->select('orprd.qty as quantity', 'ord.custid', 'orprd.id_alamat', 'ord.keterangan_order', 'orprd.kode_produk')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.nomor_order_receipt', $request->edit_nomor_order_receipt)->where('orprd.nomor_order_receipt_produk', $request->edit_nomor_order_receipt_produk)->first();

            if($request->get('edit_ekspedisi') == 'EKSBUANA'){
                $nama_ekspedisi = 'Buana';
            }else if($request->get('edit_ekspedisi') == 'EKSGJAYA'){
                $nama_ekspedisi = 'Gunawan Jaya';
            }else if($request->get('edit_ekspedisi') == 'EKSRAPI'){
                $nama_ekspedisi = 'Rapi';
            }else if($request->get('edit_ekspedisi') == 'EKSSJAYA'){
                $nama_ekspedisi = 'Sumber Jaya';
            }else if($request->get('edit_ekspedisi') == 'EKSTRANS'){
                $nama_ekspedisi = 'Transindo';
            }else if($request->get('edit_ekspedisi') == 'EKSLOKAL'){
                $nama_ekspedisi = 'Kirim Lingkup Area DSGM';
            }else if($request->get('edit_ekspedisi') == 'EKSDSGM'){
                $nama_ekspedisi = 'Ambil Sendiri';
            }else if($request->get('edit_ekspedisi') == 'EKSPRISMA'){
                $nama_ekspedisi = 'CV Prisma';
            }else if($request->get('edit_ekspedisi') == 'EKSNIAGA'){
                $nama_ekspedisi = 'Niaga Jaya';
            }

            $eks_code = DB::table('ekspedisi as eks')->select('kode_ekspedisi')->where('kode_ekspedisi', 'like', $request->get('edit_ekspedisi') . '-%')->orderBy('kode_ekspedisi', 'ASC')->distinct()->get();

            if($eks_code){
                $eks_count = $eks_code->count();
                if($eks_count > 0){
                    $num = (int) substr($eks_code[$eks_code->count() - 1]->kode_ekspedisi, 9);
                    if($eks_count != $num){
                        $ekspedisi_code = ++$eks_code[$eks_code->count() - 1]->kode_ekspedisi;
                    }else{
                        if($eks_count < 9){
                            $ekspedisi_code = $request->get('edit_ekspedisi') . "-000" . ($eks_count + 1);
                        }else if($eks_count >= 9 && $eks_count < 99){
                            $ekspedisi_code = $request->get('edit_ekspedisi') . "-00" . ($eks_count + 1);
                        }else if($eks_count >= 99 && $eks_count < 999){
                            $ekspedisi_code = $request->get('edit_ekspedisi') . "-0" . ($eks_count + 1);
                        }else{
                            $ekspedisi_code = $request->get('edit_ekspedisi') . "-" . ($eks_count + 1);
                        }
                    }
                }else{
                    $ekspedisi_code = $request->get('edit_ekspedisi') . "-0001";
                }
            }else{
                $ekspedisi_code = $request->get('edit_ekspedisi') . "-0001";
            }

            $add_ekspedisi = DB::table('ekspedisi')->insert(['kode_ekspedisi' => $ekspedisi_code, 'nama_ekspedisi' => $nama_ekspedisi, 'custid' => $products->custid, 'alamat_kirim' => $products->id_alamat, 'kode_produk' => $products->kode_produk, 'quantity' => $products->quantity, 'harga_pokok' => ($request->edit_pokok_add - $request->edit_harga_ongkir_add), 'harga_stapel' => $request->edit_stapel_add, 'harga_ekspedisi_total' => $request->edit_ekspedisi_add, 'harga_ekspedisi_kg' => $request->edit_harga_ongkir_add, 'total_harga_kg' => $request->edit_pokok_add, 'diskon' => $request->edit_diskon_add, 'ppn' => $request->edit_ppn_add, 'keterangan' => $request->edit_keterangan_quotation]);

            $validasi = ModelOrdersProduk::where('nomor_order_receipt_produk', $request->edit_nomor_order_receipt_produk)->update(['ekspedisi' => $ekspedisi_code, 'discount' => $request->edit_diskon_add, 'ppn' => $request->edit_ppn_add, 'staple_cost' => $request->edit_stapel_add, 'ekspedisi_cost' => $request->edit_ekspedisi_add, 'harga_satuan' => $request->edit_pokok_add, 'additional_price' => $request->edit_harga_ongkir_add, 'total_price' => $request->edit_total_price_add, 'keterangan_quotation' => $request->edit_keterangan_quotation, 'tanggal_kirim' => $request->edit_tanggal_kirim]);

            $history = ModelHistoryOrders::where('nomor_order', $request->edit_nomor_order_receipt)->where('custid', $products->custid)->where('alamat_receive_history', $products->id_alamat)->update(['nomor_order' => $request->edit_nomor_order, 'kode_ekspedisi' => $ekspedisi_code, 'harga_pokok' => ($request->edit_pokok_add - $request->edit_harga_ongkir_add), 'harga_stapel' => $request->edit_stapel_add, 'harga_ekspedisi' => $request->edit_harga_ongkir_add, 'total_harga_kg' => $request->edit_pokok_add, 'diskon' => $request->edit_diskon_add, 'ppn' => $request->edit_ppn_add, 'keterangan' => $request->edit_keterangan]);

            if($validasi){
                $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
            }

            $cek_status = ModelOrdersProduk::where('nomor_order_receipt', $request->edit_nomor_order_receipt)->where('status', 1)->get();

            if($cek_status->count() == 0){
                ModelOrders::where('nomor_order_receipt', $request->edit_nomor_order_receipt)->update(['status_order' => 2]);
            }
        }else{
            $products = DB::table('order_receipt as ord')->select('orprd.qty as quantity', 'ord.custid', 'orprd.id_alamat', 'ord.keterangan_order', 'orprd.kode_produk')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.nomor_order_receipt', $request->edit_nomor_order_receipt)->where('orprd.nomor_order_receipt_produk', $request->edit_nomor_order_receipt_produk)->first();

            $validasi = ModelOrdersProduk::where('nomor_order_receipt_produk', $request->edit_nomor_order_receipt_produk)->update(['discount' => $request->edit_diskon_add, 'ppn' => $request->edit_ppn_add, 'staple_cost' => $request->edit_stapel_add, 'ekspedisi_cost' => $request->edit_ekspedisi_add, 'harga_satuan' => $request->edit_pokok_add, 'additional_price' => $request->edit_harga_ongkir_add, 'total_price' => $request->edit_total_price_add, 'keterangan_quotation' => $request->edit_keterangan_quotation, 'tanggal_kirim' => $request->edit_tanggal_kirim]);

            $history = ModelHistoryOrders::where('nomor_order', $request->edit_nomor_order_receipt)->where('custid', $products->custid)->where('alamat_receive_history', $products->id_alamat)->update(['nomor_order' => $request->edit_nomor_order, 'kode_ekspedisi' => $request->edit_ekspedisi, 'harga_pokok' => ($request->edit_pokok_add - $request->edit_harga_ongkir_add), 'harga_stapel' => $request->edit_stapel_add, 'harga_ekspedisi' => $request->edit_harga_ongkir_add, 'total_harga_kg' => $request->edit_pokok_add, 'diskon' => $request->edit_diskon_add, 'ppn' => $request->edit_ppn_add, 'keterangan' => $request->edit_keterangan]);

            if($validasi){
                $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
            }
        }

        DB::table('order_receipt')->where('nomor_order_receipt', $request->edit_nomor_order_receipt)->update(['nomor_order_receipt' => $request->edit_nomor_order, 'nomor_po' => $request->edit_nomor_po, 'keterangan_order' => $request->edit_keterangan]);

        DB::table('order_receipt_produk')->where('nomor_order_receipt', $request->edit_nomor_order_receipt)->update(['nomor_order_receipt' => $request->edit_nomor_order]);

        $dataprd = DB::table('order_receipt as ord')->select('orprd.nomor_order_receipt_produk', 'ord.several_address')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.nomor_order_receipt', $request->edit_nomor_order_receipt)->where('orprd.nomor_order_receipt_produk', $request->edit_nomor_order_receipt_produk)->first();



        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Order Nomor ' . $request->edit_nomor_order]);

        return Response()->json($dataprd);
    }

    public function validasiOrders(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = ModelOrders::where('nomor_order_receipt', $request->get('nomor_sj'))->update([
                        'status_order' => 3
                    ]);

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function cancelOrders(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = ModelOrders::where('nomor_order_receipt', $request->get('nomor_sj'))->update([
                        'status_order' => 8
                    ]);

        if($validasi){
            $arr = array('msg' => 'Data Has Been Canceled', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function addNewProductsSales(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $diskon = $request->diskon_add;
        $ppn = $request->ppn_add;
        $stapel = $request->stapel_add;
        $ekspedisi = $request->ekspedisi_add;
        $pokok = $request->pokok_add;

        $tanggal = date('ym');

        $data_alamat = ModelAlamatHistory::select('id_alamat_receive', 'custname_receive', 'address_receive', 'phone_receive', 'city_receive')->where('custid_order', $request->get('custid'))->where('choosen', 1)->first();

        $data_orders_prd = ModelOrdersProduk::select('nomor_order_receipt_produk')->where('nomor_order_receipt_produk', 'like', 'ORPRD' . $tanggal . '%')->orderBy('nomor_order_receipt_produk', 'asc')->distinct()->get();

        $data_orders = ModelOrders::select('nomor_po')->where('nomor_order_receipt', $request->get('nomor_order_receipt'))->first();

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
                        $kode_ord_prd = "ORPRD-" . $tanggal . ($data_count_prd + 1);
                    }
                }
            }else{
                $kode_ord_prd = "ORPRD" . $tanggal . "-000001";
            }
        }else{
            $kode_ord_prd = "ORPRD" . $tanggal . "-000001";
        }

        if($request->get('quantity') == null || $request->get('quantity') == ''){
            $data_prd = new ModelOrdersProduk();
            $data_prd->nomor_order_receipt_produk = $kode_ord_prd;
            $data_prd->nomor_order_receipt = $request->get('nomor_order_receipt');
            $data_prd->id_alamat = $data_alamat->id_alamat_receive;
            $data_prd->custname_receive = $data_alamat->custname_receive;
            $data_prd->custalamat_receive = $data_alamat->address_receive;
            $data_prd->custkota_receive = $data_alamat->city_receive;
            $data_prd->phone_receive = $data_alamat->phone_receive;
            $data_prd->tanggal_kirim = $request->get('tanggal_kirim');
            $data_prd->kode_produk = $request->get('products');
            $data_prd->qty = $request->get('quantity_select');
            $data_prd->status = 1;
            $data_prd->save();

            $history = new ModelHistoryOrders();
            $history->nomor_order = $request->get('nomor_order_receipt');
            $history->nomor_po = $data_orders->nomor_po;
            $history->custid = $request->get('custid');
            $history->alamat_receive_history = $data_alamat->id_alamat_receive;
            $history->kode_produk = $request->get('products');
            $history->quantity = $request->get('quantity_select');
            $history->save();

            $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->get('products'))->first();
            DB::table('products')->where('kode_produk', $request->get('products'))->update(['saldo' => ($data_prd->saldo - ($request->get('quantity_select') / $data_prd->weight))]);

            $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

            if($cek){
                DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->get('quantity_select') / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->get('quantity_select') / $data_prd->weight))]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->get('quantity_select') / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->get('quantity_select') / $data_prd->weight)) . ' Sak']);
            }else{
                DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->get('quantity_select') / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->get('quantity_select') / $data_prd->weight))]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->get('quantity_select') / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->get('quantity_select') / $data_prd->weight)) . ' Sak']);
            }
        }else{
            $data_prd = new ModelOrdersProduk();
            $data_prd->nomor_order_receipt_produk = $kode_ord_prd;
            $data_prd->nomor_order_receipt = $request->get('nomor_order_receipt');
            $data_prd->id_alamat = $data_alamat->id_alamat_receive;
            $data_prd->custname_receive = $data_alamat->custname_receive;
            $data_prd->custalamat_receive = $data_alamat->address_receive;
            $data_prd->custkota_receive = $data_alamat->city_receive;
            $data_prd->phone_receive = $data_alamat->phone_receive;
            $data_prd->tanggal_kirim = $request->get('tanggal_kirim');
            $data_prd->kode_produk = $request->get('products');
            $data_prd->qty = $request->get('quantity');
            $data_prd->status = 1;
            $data_prd->save();

            $history = new ModelHistoryOrders();
            $history->nomor_order = $request->get('nomor_order_receipt');
            $history->nomor_po = $data_orders->nomor_po;
            $history->custid = $request->get('custid');
            $history->alamat_receive_history = $data_alamat->id_alamat_receive;
            $history->kode_produk = $request->get('products');
            $history->quantity = $request->get('quantity');
            $history->save();

            $data_prd = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $request->get('products'))->first();
            DB::table('products')->where('kode_produk', $request->get('products'))->update(['saldo' => ($data_prd->saldo - ($request->get('quantity') / $data_prd->weight))]);

            $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->first();

            if($cek){
                DB::table('inventaris_produksi')->where('jenis_produk', $data_prd->jenis_produk)->where('tanggal', date('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($request->get('quantity') / $data_prd->weight)), 'saldo' => ($cek->saldo - ($request->get('quantity') / $data_prd->weight))]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->get('quantity') / $data_prd->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($request->get('quantity') / $data_prd->weight)) . ' Sak']);
            }else{
                DB::table('inventaris_produksi')->insert(['tanggal' => date('Y-m-d'), 'jenis_produk' => $data_prd->jenis_produk, 'produksi' => 0, 'pengiriman' => ($request->get('quantity') / $data_prd->weight), 'saldo' => ($data_prd->saldo - ($request->get('quantity') / $data_prd->weight))]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data_prd->jenis_produk . ' = ' . ($request->get('quantity') / $data_prd->weight) . ' Sak. Total Saldo = ' . ($data_prd->saldo - ($request->get('quantity') / $data_prd->weight)) . ' Sak']);
            }
        }

        $cek_ekspedisi = DB::table('ekspedisi as eks')->select('kode_ekspedisi')->where('kode_ekspedisi', $request->get('ekspedisi'))->first();

        if(!$cek_ekspedisi){
            $products = DB::table('order_receipt as ord')->select('orprd.qty as quantity', 'ord.custid', 'orprd.id_alamat', 'ord.keterangan_order', 'orprd.kode_produk')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.nomor_order_receipt', $request->get('nomor_order_receipt'))->where('orprd.nomor_order_receipt_produk', $kode_ord_prd)->first();

            $data_prd_saldo = DB::table('products')->select('saldo', 'weight')->where('kode_produk', $products->kode_produk)->first();
            DB::table('products')->where('kode_produk', $products->kode_produk)->update(['saldo' => ($data_prd_saldo->saldo - ($products->quantity / $data_prd_saldo->weight))]);

            if($request->get('ekspedisi') == 'EKSBUANA'){
                $nama_ekspedisi = 'Buana';
            }else if($request->get('ekspedisi') == 'EKSGJAYA'){
                $nama_ekspedisi = 'Gunawan Jaya';
            }else if($request->get('ekspedisi') == 'EKSRAPI'){
                $nama_ekspedisi = 'Rapi';
            }else if($request->get('ekspedisi') == 'EKSSJAYA'){
                $nama_ekspedisi = 'Sumber Jaya';
            }else if($request->get('ekspedisi') == 'EKSTRANS'){
                $nama_ekspedisi = 'Transindo';
            }else if($request->get('ekspedisi') == 'EKSLOKAL'){
                $nama_ekspedisi = 'Kirim Lingkup Area DSGM';
            }else if($request->get('ekspedisi') == 'EKSDSGM'){
                $nama_ekspedisi = 'Ambil Sendiri';
            }else if($request->get('ekspedisi') == 'EKSPRISMA'){
                $nama_ekspedisi = 'CV Prisma';
            }else if($request->get('ekspedisi') == 'EKSNIAGA'){
                $nama_ekspedisi = 'Niaga Jaya';
            }

            $total_add_kg = 0;
            $total_add_kg = ($stapel + $ekspedisi) / $products->quantity;
            $total_add_kg = $total_add_kg - (($diskon * $pokok) / 100);
            $harga_ongkir_kg = $pokok + $total_add_kg;
            $total_price = $products->quantity * ceil($harga_ongkir_kg);
            $total_ppn = ($ppn * $total_price) / 100;
            $total_price = $total_price + $total_ppn;

            $eks_code = DB::table('ekspedisi as eks')->select('kode_ekspedisi')->where('kode_ekspedisi', 'like', $request->get('ekspedisi') . '-%')->orderBy('kode_ekspedisi', 'ASC')->distinct()->get();

            if($eks_code){
                $eks_count = $eks_code->count();
                if($eks_count > 0){
                    $num = (int) substr($eks_code[$eks_code->count() - 1]->kode_ekspedisi, 9);
                    if($eks_count != $num){
                        $ekspedisi_code = ++$eks_code[$eks_code->count() - 1]->kode_ekspedisi;
                    }else{
                        if($eks_count < 9){
                            $ekspedisi_code = $request->get('ekspedisi') . "-000" . ($eks_count + 1);
                        }else if($eks_count >= 9 && $eks_count < 99){
                            $ekspedisi_code = $request->get('ekspedisi') . "-00" . ($eks_count + 1);
                        }else if($eks_count >= 99 && $eks_count < 999){
                            $ekspedisi_code = $request->get('ekspedisi') . "-0" . ($eks_count + 1);
                        }else{
                            $ekspedisi_code = $request->get('ekspedisi') . "-" . ($eks_count + 1);
                        }
                    }
                }else{
                    $ekspedisi_code = $request->get('ekspedisi') . "-0001";
                }
            }else{
                $ekspedisi_code = $request->get('ekspedisi') . "-0001";
            }

            $add_ekspedisi = DB::table('ekspedisi')->insert(['kode_ekspedisi' => $ekspedisi_code, 'nama_ekspedisi' => $nama_ekspedisi, 'custid' => $products->custid, 'alamat_kirim' => $products->id_alamat, 'kode_produk' => $products->kode_produk, 'quantity' => $products->quantity, 'harga_pokok' => $request->get('pokok_add'), 'harga_stapel' => $request->get('stapel_add'), 'harga_ekspedisi_total' => $request->get('ekspedisi_add'), 'harga_ekspedisi_kg' => $total_add_kg, 'total_harga_kg' => $harga_ongkir_kg, 'diskon' => $request->get('diskon_add'), 'ppn' => $request->get('ppn_add'), 'keterangan' => $request->get('keterangan_quotation')]);

            $validasi = ModelOrdersProduk::where('nomor_order_receipt_produk', $kode_ord_prd)->update(['ekspedisi' => $ekspedisi_code, 'status' => 2, 'discount' => $request->get('diskon_add'), 'ppn' => $request->get('ppn_add'), 'staple_cost' => $request->get('stapel_add'), 'ekspedisi_cost' => $request->get('ekspedisi_add'), 'harga_satuan' => $harga_ongkir_kg, 'additional_price' => $total_add_kg, 'total_price' => $total_price, 'keterangan_quotation' => $request->get('keterangan_quotation'), 'tanggal_quotation' => date('Y-m-d'), 'tanggal_kirim' => $request->get('tanggal_kirim'), 'sales_val_quotation' => Session::get('id_user_admin')]);

            $history = ModelHistoryOrders::where('nomor_order', $request->get('nomor_order_receipt'))->where('custid', $products->custid)->where('alamat_receive_history', $products->id_alamat)->update(['kode_ekspedisi' => $ekspedisi_code, 'harga_pokok' => $request->get('pokok_add'), 'harga_stapel' => $request->get('stapel_add'), 'harga_ekspedisi' => $total_add_kg, 'total_harga_kg' => $harga_ongkir_kg, 'diskon' => $request->get('diskon_add'), 'ppn' => $request->get('ppn_add'), 'keterangan' => $products->keterangan_order]);

            if($validasi){
                $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
            }
        }else{
            $products = DB::table('order_receipt as ord')->select('orprd.qty as quantity', 'ord.custid', 'orprd.id_alamat', 'ord.keterangan_order', 'orprd.kode_produk')->join('order_receipt_produk as orprd', 'orprd.nomor_order_receipt', '=', 'ord.nomor_order_receipt')->where('ord.nomor_order_receipt', $request->get('nomor_order_receipt'))->where('orprd.nomor_order_receipt_produk', $kode_ord_prd)->first();

            $data_prd_saldo = DB::table('products')->select('saldo', 'weight')->where('kode_produk', $products->kode_produk)->first();
            DB::table('products')->where('kode_produk', $products->kode_produk)->update(['saldo' => ($data_prd_saldo->saldo - ($products->quantity / $data_prd_saldo->weight))]);

            $total_price = $products->quantity * $request->get('harga_ongkir_add');
            $total_ppn = ($ppn * $total_price) / 100;
            $total_price = $total_price + $total_ppn;
            $total_add_kg = ($stapel + $ekspedisi) / $products->quantity;

            $validasi = ModelOrdersProduk::where('nomor_order_receipt_produk', $kode_ord_prd)->update(['ekspedisi' => $request->get('ekspedisi'), 'status' => 2, 'discount' => $request->get('diskon_add'), 'ppn' => $request->get('ppn_add'), 'staple_cost' => $request->get('stapel_add'), 'ekspedisi_cost' => $request->get('ekspedisi_add'), 'harga_satuan' => $request->get('harga_ongkir_add'), 'additional_price' => $total_add_kg, 'total_price' => $total_price, 'keterangan_quotation' => $request->get('keterangan_quotation'), 'tanggal_quotation' => date('Y-m-d'), 'tanggal_kirim' => $request->get('tanggal_kirim'), 'sales_val_quotation' => Session::get('id_user_admin')]);

            $history = ModelHistoryOrders::where('nomor_order', $request->get('nomor_order_receipt'))->where('custid', $products->custid)->where('alamat_receive_history', $products->id_alamat)->update(['kode_ekspedisi' => $request->get('ekspedisi'), 'harga_pokok' => $request->get('pokok_add'), 'harga_stapel' => $request->get('stapel_add'), 'harga_ekspedisi' => $total_add_kg, 'total_harga_kg' => $request->get('harga_ongkir_add'), 'diskon' => $request->get('diskon_add'), 'ppn' => $request->get('ppn_add'), 'keterangan' => $products->keterangan_order]);

            if($validasi){
                $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
            }
        }

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 1)->update(['choosen' => 1]);

        ModelAlamatHistory::where('custid_order', $request->get('custid'))->where('main_address', 0)->update(['choosen' => 0]);

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 2, 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Order Nomor ' . $request->get('nomor_order_receipt')]);

        return Response()->json($arr);
    }

    public function getSaldo($kode_produk){

        $val_kode_produk = $this->decrypt($kode_produk);
        
        $data = DB::table('products')->select(DB::raw("(saldo * weight) as saldo"))->where('kode_produk', $val_kode_produk)->first();

        return Response()->json($data);
    }

    public function deleteOrders(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data = ModelOrders::where('nomor_order_receipt', $request->nomor)->delete();
        ModelOrdersProduk::where('nomor_order_receipt', $request->nomor)->delete();

        if($data){
            $arr = array('msg' => 'Data Successfully Deleted', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_order')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Order Nomor ' . $request->nomor]);

        return Response()->json($arr);
    }

}
