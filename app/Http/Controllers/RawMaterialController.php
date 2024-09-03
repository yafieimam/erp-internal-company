<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Exports\HasilLabExport;
use App\Exports\TeknikMasalahMesinExport;
use App\Exports\LaporanTotalProduksiExport;
use App\Exports\LaporanRataProduksiExport;
use App\Exports\PotonganBatuSheetExport;
use App\Exports\InventarisBatuExport;
use Response;
use PDF;
use Excel;
use File;
use App\Imports\PurchaseBatuImport;

class RawMaterialController extends Controller
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

    public function viewPageItemBatu(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('raw_material_item_batu');
        }
    }

    public function viewPageVendorBatu(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('raw_material_vendor_batu');
        }
    }

    public function viewPagePurchaseBatu(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('raw_material_purchase_batu');
        }
    }

    public function viewPageLaporanBatu(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('raw_material_laporan_batu');
        }
    }

    public function viewPagePotonganBatu(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            $data_vendor = DB::table('vendor_batu')->select('vendorid', 'nama_vendor')->get();

            return view('raw_material_potongan_batu', ['data_vendor' => $data_vendor]);
        }
    }

    public function viewPageInventarisBatu(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('raw_material_inventaris_batu');
        }
    }

    public function viewItemBatuTable(Request $request){
        $list = DB::table('item_batu')->select('kode_batu', 'nama_batu')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_item_batu')->rawColumns(['action'])->make(true);
    }

    public function inputItemBatu(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('item_batu')->insert(['kode_batu' => $request->kode, 'nama_batu' => $request->nama, 'stok' => 0, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_item_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Item Batu Dengan Kode ' . $request->kode]);

        return Response()->json($arr);
    }

    public function deleteItemBatu(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('item_batu')->where('kode_batu', $request->get('kode'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_item_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Item Batu Dengan Kode ' . $request->get('kode')]);

        return Response()->json($arr);
    }

    public function viewItemBatu($kode){
        $val_kode = $this->decrypt($kode);

        $data = DB::table('item_batu')->where('kode_batu', $val_kode)->first();

        return Response()->json($data);
    }

    public function editItemBatu(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('item_batu')->where('kode_batu', $request->edit_kode_lama)->update(['kode_batu' => $request->edit_kode, 'nama_batu' => $request->edit_nama, 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_item_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Item Batu Dengan Kode ' . $request->edit_kode]);

        return Response()->json($arr);
    }

    public function viewVendorBatuTable(Request $request){
        $list = DB::table('vendor_batu')->select('vendorid', 'nama_vendor')->get();

        return datatables()->of($list)->addColumn('action', 'button/action_button_list_vendor_batu')->rawColumns(['action'])->make(true);
    }

    public function inputVendorBatu(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('vendor_batu')->insert(['vendorid' => $request->id, 'nama_vendor' => $request->nama, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_vendor_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Input Data Vendor Batu Dengan ID ' . $request->id]);

        return Response()->json($arr);
    }

    public function deleteVendorBatu(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $hapus = DB::table('vendor_batu')->where('vendorid', $request->get('id'))->delete();

        if($hapus){
            $arr = array('msg' => 'Data Deleted Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_vendor_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Delete Data Vendor Batu Dengan ID ' . $request->get('id')]);

        return Response()->json($arr);
    }

    public function viewVendorBatu($id){
        $val_id = $this->decrypt($id);

        $data = DB::table('vendor_batu')->where('vendorid', $val_id)->first();

        return Response()->json($data);
    }

    public function editVendorBatu(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('vendor_batu')->where('vendorid', $request->edit_id_lama)->update(['vendorid' => $request->edit_id, 'nama_vendor' => $request->edit_nama, 'updated_at' => date("Y-m-d H:i:s")]);

        if($data){
            $arr = array('msg' => 'Data Successfully Updated', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_data_vendor_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' Edit Data Vendor Batu Dengan ID ' . $request->edit_id]);

        return Response()->json($arr);
    }

    public function uploadExcelPurchaseBatu(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new PurchaseBatuImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        if($import->getDuplikat() == 0){
            return redirect('raw_material/purchase_batu')->with('alert','Data Duplikat, Data Sudah Ada');
        }else{
            return redirect('raw_material/purchase_batu')->with('alert','Sukses Menambahkan Data');
        }
    }

    public function viewPurchaseBatuTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $purchase_batu = DB::table('purchase_batu')->select('tanggal', DB::raw("count(distinct(grno)) as total_purchase"), DB::raw("sum(qpur) as total_tonase"))->whereBetween('tanggal', array($request->from_date, $request->to_date))->groupBy('tanggal')->get();
            }else{
                $purchase_batu = DB::table('purchase_batu')->select('tanggal', DB::raw("count(distinct(grno)) as total_purchase"), DB::raw("sum(qpur) as total_tonase"))->whereRaw('MONTH(tanggal) = ?',[$currentMonth])->whereRaw('YEAR(tanggal) = ?',[$currentYear])->groupBy('tanggal')->get();
            }

            return datatables()->of($purchase_batu)->addColumn('action', 'button/action_button_purchase_batu')->rawColumns(['action'])->make(true);
        }
        return view('raw_material_purchase_batu');
    }  

    public function viewPurchaseBatuDetailTable(Request $request){
        if(request()->ajax()){
            $purchase_batu_detail = DB::table('purchase_batu')->select("grno", "tanggal", "nama_vendor as vendor", "nama_batu as item_batu", "qpur as tonase")->whereDate('tanggal', $request->tanggal)->get();

            return datatables()->of($purchase_batu_detail)->make(true);
        }
    } 

    public function inputPurchaseBatu(Request $request){
    	$arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

    	$data_vendor = DB::table('vendor_batu')->select("nama_vendor")->where('vendorid', $request->vendor)->first();
    	$data_item = DB::table('item_batu')->select("nama_batu", "stok")->where('kode_batu', $request->batu)->first();

    	$data_cek = DB::table('purchase_batu')->select('grno', 'kode_batu', 'qpur')->where('grno', $request->grno)->get();

    	if($data_cek->count() > 0){
    		if($data_cek[0]->qpur > $request->tonase){
    			$batu_afkir = $request->tonase;
    		}else{
    			$batu_afkir = $data_cek[0]->qpur;
    		}

    		$total_batu = $data_cek[0]->qpur + $request->tonase;

    		$potongan_batu = ($batu_afkir / $total_batu) * 100;
    		$potongan_batu = round($potongan_batu);

    		DB::table('potongan_batu')->insert(["grno" => $request->grno, "tanggal" => $request->tanggal, "vendorid" => $request->vendor, "nama_vendor" => $data_vendor->nama_vendor, "potongan_batu" => $potongan_batu]);
    	}

    	date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('purchase_batu')->insert(['grno' => $request->grno, 'tanggal' => $request->tanggal, 'vendorid' => $request->vendor, 'nama_vendor' => $data_vendor->nama_vendor, 'qpur' => $request->tonase, 'sat' => "KG", 'kode_batu' => $request->batu, 'nama_batu' => $data_item->nama_batu]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        $cek = DB::table('inventaris_batu')->select('tanggal', 'kode_batu', 'masuk', 'keluar', 'stok')->where('tanggal', $request->tanggal)->where('kode_batu', $request->batu)->first();

        if($cek){
        	DB::table('inventaris_batu')->where('kode_batu', $request->batu)->where('tanggal', $request->tanggal)->update(['masuk' => ($cek->masuk + $request->tonase), 'stok' => ($cek->stok + $request->tonase)]);
        	DB::table('item_batu')->where('kode_batu', $request->batu)->update(['stok' => ($data_item->stok + $request->tonase)]);

        	date_default_timezone_set('Asia/Jakarta');
        	DB::table('logbook_inventaris_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Stok Batu ' . $request->batu . ' = ' . $request->tonase . ' Kg. Total Stok = ' . ($cek->stok + $request->tonase) . ' Kg']);
        }else{
        	DB::table('inventaris_batu')->insert(['tanggal' => $request->tanggal, 'kode_batu' => $request->batu, 'masuk' => $request->tonase, 'keluar' => 0, 'stok' => ($data_item->stok + $request->tonase)]);
        	DB::table('item_batu')->where('kode_batu', $request->batu)->update(['stok' => ($data_item->stok + $request->tonase)]);

        	date_default_timezone_set('Asia/Jakarta');
        	DB::table('logbook_inventaris_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Stok Batu ' . $request->batu . ' = ' . $request->tonase . ' Kg. Total Stok = ' . ($data_item->stok + $request->tonase) . ' Kg']);
        }

        date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_purchase_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Divisi Batu Menambahkan Data Purchase Batu No. ' . $request->grno]);

        return Response()->json($arr);
    }

    public function dropdownVendorBatu(Request $request){
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = DB::table("vendor_batu")->select("vendorid","nama_vendor")
                    ->where('nama_vendor','LIKE',"%$search%")
                    ->get();
        }else{
            $data = DB::table("vendor_batu")->select("vendorid","nama_vendor")
                    ->get();
        }
        return response()->json($data);
    }

    public function dropdownItemBatu(){
        $data = DB::table('item_batu')->get();

        return Response()->json($data);
    }

    public function viewPotonganBatuAllTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $potongan_batu = DB::table('potongan_batu')->select('tanggal', DB::raw("sum(total_batu) as total_batu_semua"), DB::raw("sum(total_batu_afkir) as total_batu_afkir"), DB::raw("(sum(total_batu) - sum(total_batu_afkir)) as total_batu_non_afkir"), DB::raw("avg(potongan_batu) as rata_rata_potongan"))->whereBetween('tanggal', array($request->from_date, $request->to_date))->groupBy('tanggal')->get();
            }else{
                $potongan_batu = DB::table('potongan_batu')->select('tanggal', DB::raw("sum(total_batu) as total_batu_semua"), DB::raw("sum(total_batu_afkir) as total_batu_afkir"), DB::raw("(sum(total_batu) - sum(total_batu_afkir)) as total_batu_non_afkir"), DB::raw("avg(potongan_batu) as rata_rata_potongan"))->whereRaw('MONTH(tanggal) = ?',[$currentMonth])->whereRaw('YEAR(tanggal) = ?',[$currentYear])->groupBy('tanggal')->get();
            }

            return datatables()->of($potongan_batu)->addColumn('action', 'button/action_button_potongan_batu_all')->rawColumns(['action'])->make(true);
        }
        return view('raw_material_potongan_batu');
    }  

    public function viewPotonganBatuTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $potongan_batu = DB::table('potongan_batu')->select('vendorid', 'tanggal', DB::raw("sum(total_batu) as total_batu_semua"), DB::raw("sum(total_batu_afkir) as total_batu_afkir"), DB::raw("(sum(total_batu) - sum(total_batu_afkir)) as total_batu_non_afkir"), DB::raw("avg(potongan_batu) as rata_rata_potongan"))->where('vendorid', $request->vendorid)->whereBetween('tanggal', array($request->from_date, $request->to_date))->groupBy('tanggal')->get();
            }else{
                $potongan_batu = DB::table('potongan_batu')->select('vendorid', 'tanggal', DB::raw("sum(total_batu) as total_batu_semua"), DB::raw("sum(total_batu_afkir) as total_batu_afkir"), DB::raw("(sum(total_batu) - sum(total_batu_afkir)) as total_batu_non_afkir"), DB::raw("avg(potongan_batu) as rata_rata_potongan"))->where('vendorid', $request->vendorid)->whereRaw('MONTH(tanggal) = ?',[$currentMonth])->whereRaw('YEAR(tanggal) = ?',[$currentYear])->groupBy('tanggal')->get();
            }

            return datatables()->of($potongan_batu)->addColumn('action', 'button/action_button_potongan_batu')->rawColumns(['action'])->make(true);
        }
        return view('raw_material_potongan_batu');
    }  

    public function viewVendorBatuAll(){
        $data = DB::table('vendor_batu')->select('vendorid', 'nama_vendor')->get();

        return Response()->json($data);
    }

    public function viewPotonganBatuDetailAllTable(Request $request){
        if(request()->ajax()){
            $potongan_batu_detail = DB::table('potongan_batu')->select("grno", "nama_vendor as vendor", "total_batu_afkir as total_afkir", "total_batu as total_semua", "potongan_batu")->whereDate('tanggal', $request->tanggal)->get();

            return datatables()->of($potongan_batu_detail)->make(true);
        }
    } 

    public function viewPotonganBatuDetailTable(Request $request){
        if(request()->ajax()){
            $potongan_batu_detail = DB::table('potongan_batu')->select("grno", "nama_vendor as vendor", "total_batu_afkir as total_afkir", "total_batu as total_semua", "potongan_batu")->where('vendorid', $request->vendorid)->whereDate('tanggal', $request->tanggal)->get();

            return datatables()->of($potongan_batu_detail)->make(true);
        }
    } 

    public function viewInventarisBatuTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $inventaris_table = DB::table('inventaris_batu as inv')->select('inv.tanggal', DB::raw("(select stok from inventaris_batu where kode_batu = 'BATU-002' and tanggal = inv.tanggal) as stok_dolomit"), DB::raw("(select stok from inventaris_batu where kode_batu = 'BATU-001' and tanggal = inv.tanggal) as stok_afkir"), DB::raw("(select stok from inventaris_batu where kode_batu = 'BATU-003' and tanggal = inv.tanggal) as stok_kapur_air"), DB::raw("(select stok from inventaris_batu where kode_batu = 'BATU-004' and tanggal = inv.tanggal) as stok_kapur"), DB::raw("'1' as kode"))->whereBetween('inv.tanggal', array($request->from_date, $request->to_date))->groupBy('inv.tanggal')->get();
            }else{
                $union_inventaris = DB::table('inventaris_batu')->select(DB::raw("'Saldo Awal' as tanggal"), DB::raw("(select inv_a.stok from inventaris_batu inv_a where inv_a.kode_batu = 'BATU-002' and inv_a.tanggal = (select max(tanggal) from inventaris_batu where kode_batu = inv_a.kode_batu and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as stok_dolomit"), DB::raw("(select inv_a.stok from inventaris_batu inv_a where inv_a.kode_batu = 'BATU-001' and inv_a.tanggal = (select max(tanggal) from inventaris_batu where kode_batu = inv_a.kode_batu and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as stok_afkir"), DB::raw("(select inv_a.stok from inventaris_batu inv_a where inv_a.kode_batu = 'BATU-003' and inv_a.tanggal = (select max(tanggal) from inventaris_batu where kode_batu = inv_a.kode_batu and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as stok_kapur_air"), DB::raw("(select inv_a.stok from inventaris_batu inv_a where inv_a.kode_batu = 'BATU-004' and inv_a.tanggal = (select max(tanggal) from inventaris_batu where kode_batu = inv_a.kode_batu and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as stok_kapur"), DB::raw("'0' as kode"))->distinct();

                $inventaris_table = DB::table('inventaris_batu as inv')->select('inv.tanggal', DB::raw("(select stok from inventaris_batu where kode_batu = 'BATU-002' and tanggal = inv.tanggal) as stok_dolomit"), DB::raw("(select stok from inventaris_batu where kode_batu = 'BATU-001' and tanggal = inv.tanggal) as stok_afkir"), DB::raw("(select stok from inventaris_batu where kode_batu = 'BATU-003' and tanggal = inv.tanggal) as stok_kapur_air"), DB::raw("(select stok from inventaris_batu where kode_batu = 'BATU-004' and tanggal = inv.tanggal) as stok_kapur"), DB::raw("'1' as kode"))->whereRaw('MONTH(inv.tanggal) = ?',[$currentMonth])->whereRaw('YEAR(inv.tanggal) = ?',[$currentYear])->groupBy('inv.tanggal')->unionAll($union_inventaris)->orderBy('kode', 'desc')->orderBy('tanggal', 'desc')->get();
            }

            return datatables()->of($inventaris_table)->addIndexColumn()->addColumn('action', 'button/action_button_inventaris_batu')->rawColumns(['action'])->make(true);
        }

        return view('raw_material_inventaris_batu');
    }

    public function viewInventarisBatuDetail($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $dolomit = DB::table('inventaris_batu')->select('masuk', 'keluar')->where('kode_batu', 'BATU-002')->where('tanggal', $val_tanggal)->first();

        $afkir = DB::table('inventaris_batu')->select('masuk', 'keluar')->where('kode_batu', 'BATU-001')->where('tanggal', $val_tanggal)->first();

        $kapur_air = DB::table('inventaris_batu')->select('masuk', 'keluar')->where('kode_batu', 'BATU-003')->where('tanggal', $val_tanggal)->first();

        $kapur = DB::table('inventaris_batu')->select('masuk', 'keluar')->where('kode_batu', 'BATU-004')->where('tanggal', $val_tanggal)->first();

        return Response()->json(['dolomit' => $dolomit, 'afkir' => $afkir, 'kapur' => $kapur, 'kapur_air' => $kapur_air]);
    }

    public function printInventarisBatu($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::table('inventaris_batu as inv')->select('inv.kode_batu', 'item.nama_batu', 'inv.stok', 'inv.masuk', 'inv.keluar')->join('item_batu as item', 'item.kode_batu', '=', 'inv.kode_batu')->where('inv.tanggal', $val_tanggal)->get();

        $pdf = PDF::loadView('print_inventaris_batu', ['tanggal' => $val_tanggal, 'data' => $data])->setPaper('a5', 'portrait')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function excelPotonganBatu($from_date, $to_date){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        return Excel::download(new PotonganBatuSheetExport($val_from_date, $val_to_date), 'Data Potongan Batu.xlsx');
    }

    public function excelInventarisBatu($from_date, $to_date){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        return Excel::download(new InventarisBatuExport($val_from_date, $val_to_date), 'Data Inventaris Batu.xlsx');
    }
}
