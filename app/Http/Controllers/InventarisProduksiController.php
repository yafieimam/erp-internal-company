<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Response;
use PDF;

class InventarisProduksiController extends Controller
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

    public function viewPageInventaris(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('inventaris_produksi');
        }
    }

    public function viewPageInventarisWarehouse(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('inventaris_produksi');
        }
    }

    public function indexInventarisA(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $inventaris_table = DB::table('inventaris_produksi as inv')->select('inv.tanggal', DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'AA40' and tanggal = inv.tanggal) as saldo_aa40"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'BB40' and tanggal = inv.tanggal) as saldo_bb40"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DCB25' and tanggal = inv.tanggal) as saldo_dcb25"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'AA20' and tanggal = inv.tanggal) as saldo_aa20"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'AA25' and tanggal = inv.tanggal) as saldo_aa25"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'CC50' and tanggal = inv.tanggal) as saldo_cc50"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DD50' and tanggal = inv.tanggal) as saldo_dd50"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DCD50' and tanggal = inv.tanggal) as saldo_dcd50"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DCD25' and tanggal = inv.tanggal) as saldo_dcd25"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DCE50' and tanggal = inv.tanggal) as saldo_dce50"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SSF25' and tanggal = inv.tanggal) as saldo_ssf25"), DB::raw("'1' as kode"))->whereBetween('inv.tanggal', array($request->from_date, $request->to_date))->groupBy('inv.tanggal')->get();
            }else{
                $union_inventaris = DB::table('inventaris_produksi')->select(DB::raw("'Saldo Awal' as tanggal"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'AA40' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_aa40"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'BB40' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_bb40"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'DCB25' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_dcb25"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'AA20' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_aa20"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'AA25' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_aa25"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'CC50' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_cc50"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'DD50' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_dd50"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'DCD50' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_dcd50"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'DCD25' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_dcd25"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'DCE50' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_dce50"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'SSF25' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_ssf25"), DB::raw("'0' as kode"))->distinct();

                $inventaris_table = DB::table('inventaris_produksi as inv')->select('inv.tanggal', DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'AA40' and tanggal = inv.tanggal) as saldo_aa40"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'BB40' and tanggal = inv.tanggal) as saldo_bb40"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DCB25' and tanggal = inv.tanggal) as saldo_dcb25"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'AA20' and tanggal = inv.tanggal) as saldo_aa20"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'AA25' and tanggal = inv.tanggal) as saldo_aa25"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'CC50' and tanggal = inv.tanggal) as saldo_cc50"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DD50' and tanggal = inv.tanggal) as saldo_dd50"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DCD50' and tanggal = inv.tanggal) as saldo_dcd50"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DCD25' and tanggal = inv.tanggal) as saldo_dcd25"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'DCE50' and tanggal = inv.tanggal) as saldo_dce50"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SSF25' and tanggal = inv.tanggal) as saldo_ssf25"), DB::raw("'1' as kode"))->whereRaw('MONTH(inv.tanggal) = ?',[$currentMonth])->whereRaw('YEAR(inv.tanggal) = ?',[$currentYear])->groupBy('inv.tanggal')->unionAll($union_inventaris)->orderBy('kode', 'desc')->orderBy('tanggal', 'desc')->get();
            }

            return datatables()->of($inventaris_table)->addIndexColumn()->addColumn('action', 'button/action_button_inventaris')->rawColumns(['action'])->make(true);
        }

        return view('inventaris_produksi');
    }

    public function indexInventarisB(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $inventaris_table = DB::table('inventaris_produksi as inv')->select('inv.tanggal', DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SW30' and tanggal = inv.tanggal) as saldo_sw30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SW40' and tanggal = inv.tanggal) as saldo_sw40"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SF30' and tanggal = inv.tanggal) as saldo_sf30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SS30' and tanggal = inv.tanggal) as saldo_ss30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SSS30' and tanggal = inv.tanggal) as saldo_sss30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'AC30' and tanggal = inv.tanggal) as saldo_ac30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'NL25' and tanggal = inv.tanggal) as saldo_nl25"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'JAA' and tanggal = inv.tanggal) as saldo_jaa"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'JSW' and tanggal = inv.tanggal) as saldo_jsw"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'JPAC' and tanggal = inv.tanggal) as saldo_jpac"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'KDCC' and tanggal = inv.tanggal) as saldo_kdcc"), DB::raw("'1' as kode"))->whereBetween('inv.tanggal', array($request->from_date, $request->to_date))->groupBy('inv.tanggal')->get();
            }else{
                $union_inventaris = DB::table('inventaris_produksi')->select(DB::raw("'Saldo Awal' as tanggal"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'SW30' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_sw30"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'SW40' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_sw40"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'SF30' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_sf30"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'SS30' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_ss30"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'SSS30' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_sss30"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'AC30' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_ac30"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'NL25' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_nl25"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'JAA' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_jaa"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'JSW' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_jsw"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'JPAC' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_jpac"), DB::raw("(select inv_a.saldo from inventaris_produksi inv_a where inv_a.jenis_produk = 'KDCC' and inv_a.tanggal = (select max(tanggal) from inventaris_produksi where jenis_produk = inv_a.jenis_produk and month(tanggal) = month(CURDATE() - INTERVAL 1 MONTH))) as saldo_kdcc"), DB::raw("'0' as kode"))->distinct();

                $inventaris_table = DB::table('inventaris_produksi as inv')->select('inv.tanggal', DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SW30' and tanggal = inv.tanggal) as saldo_sw30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SW40' and tanggal = inv.tanggal) as saldo_sw40"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SF30' and tanggal = inv.tanggal) as saldo_sf30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SS30' and tanggal = inv.tanggal) as saldo_ss30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'SSS30' and tanggal = inv.tanggal) as saldo_sss30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'AC30' and tanggal = inv.tanggal) as saldo_ac30"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'NL25' and tanggal = inv.tanggal) as saldo_nl25"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'JAA' and tanggal = inv.tanggal) as saldo_jaa"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'JSW' and tanggal = inv.tanggal) as saldo_jsw"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'JPAC' and tanggal = inv.tanggal) as saldo_jpac"), DB::raw("(select saldo from inventaris_produksi where jenis_produk = 'KDCC' and tanggal = inv.tanggal) as saldo_kdcc"), DB::raw("'1' as kode"))->whereRaw('MONTH(inv.tanggal) = ?',[$currentMonth])->whereRaw('YEAR(inv.tanggal) = ?',[$currentYear])->groupBy('inv.tanggal')->unionAll($union_inventaris)->orderBy('kode', 'desc')->orderBy('tanggal', 'desc')->get();
            }

            return datatables()->of($inventaris_table)->addIndexColumn()->addColumn('action', 'button/action_button_inventaris')->rawColumns(['action'])->make(true);
        }

        return view('inventaris_produksi');
    }

    public function detailInventaris($tanggal){
        $val_tanggal = $this->decrypt($tanggal);

        $aa40 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'AA40')->where('tanggal', $val_tanggal)->first();

        $bb40 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'BB40')->where('tanggal', $val_tanggal)->first();

        $dcb25 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'DCB25')->where('tanggal', $val_tanggal)->first();

        $aa20 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'AA20')->where('tanggal', $val_tanggal)->first();

        $aa25 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'AA25')->where('tanggal', $val_tanggal)->first();

        $cc50 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'CC50')->where('tanggal', $val_tanggal)->first();

        $dd50 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'DD50')->where('tanggal', $val_tanggal)->first();

        $dcd50 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'DCD50')->where('tanggal', $val_tanggal)->first();

        $dcd25 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'DCD25')->where('tanggal', $val_tanggal)->first();

        $dce50 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'DCE50')->where('tanggal', $val_tanggal)->first();

        $ssf25 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'SSF25')->where('tanggal', $val_tanggal)->first();

        $sw30 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'SW30')->where('tanggal', $val_tanggal)->first();

        $sw40 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'SW40')->where('tanggal', $val_tanggal)->first();

        $sf30 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'SF30')->where('tanggal', $val_tanggal)->first();

        $ss30 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'SS30')->where('tanggal', $val_tanggal)->first();

        $sss30 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'SSS30')->where('tanggal', $val_tanggal)->first();

        $ac30 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'AC30')->where('tanggal', $val_tanggal)->first();

        $nl25 = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'NL25')->where('tanggal', $val_tanggal)->first();

        $jaa = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'JAA')->where('tanggal', $val_tanggal)->first();

        $jsw = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'JSW')->where('tanggal', $val_tanggal)->first();

        $jpac = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'JPAC')->where('tanggal', $val_tanggal)->first();

        $kdcc = DB::table('inventaris_produksi')->select('produksi', 'pengiriman')->where('jenis_produk', 'KDCC')->where('tanggal', $val_tanggal)->first();

        return Response()->json(['aa40' => $aa40, 'bb40' => $bb40, 'dcb25' => $dcb25, 'aa20' => $aa20, 'aa25' => $aa25, 'cc50' => $cc50, 'dd50' => $dd50, 'dcd50' => $dcd50, 'dcd25' => $dcd25, 'dce50' => $dce50, 'ssf25' => $ssf25, 'sw30' => $sw30, 'sw40' => $sw40, 'sf30' => $sf30, 'ss30' => $ss30, 'sss30' => $sss30, 'ac30' => $ac30, 'nl25' => $nl25, 'jaa' => $jaa, 'jsw' => $jsw, 'jpac' => $jpac, 'kdcc' => $kdcc]);
    }

    public function printInventaris($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::table('inventaris_produksi')->select('jenis_produk', 'saldo', 'produksi', 'pengiriman')->where('tanggal', $val_tanggal)->get();

        $pdf = PDF::loadView('print_inventaris', ['tanggal' => $val_tanggal, 'data' => $data])->setPaper('a5', 'portrait')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }
}
