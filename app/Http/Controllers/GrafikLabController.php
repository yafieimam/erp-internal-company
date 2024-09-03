<?php

namespace App\Http\Controllers;

use App\Exports\GrafikLabExport;
use App\Exports\DataGrafikLabExport;
use App\Exports\GrafikEvalLabExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Response;
use Excel;

class GrafikLabController extends Controller
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

    public function viewPageGrafikLab(){
    	$currentMonth = date('m');
        $currentYear = date('Y');
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('lab_grafik');
        }
    }

    public function viewDataGrafikLab($from_date, $to_date){
        $currentMonth = date('m');
        $currentYear = date('Y');
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        $tanggal_dari = date('Y-m-01', strtotime($val_from_date));
        $tanggal_ke = date('Y-m-31', strtotime($val_to_date));

        if($val_from_date == ''){
            $data_lab = DB::table('laporan_hasil_produksi_lab as lab')->select('lab.mesh', 'tbl_mesin.name as mesin', 'lab.mesin as no_mesin')->join('laporan_hasil_produksi as lap', 'lap.nomor_laporan_produksi', '=', 'lab.nomor_laporan_produksi')->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->whereNotNull('lab.mesh')->orderBy('lab.mesh', 'asc')->groupBy('lab.mesh', 'lab.mesin')->get();
        }else{
            $data_lab = DB::table('laporan_hasil_produksi_lab as lab')->select('lab.mesh', 'tbl_mesin.name as mesin', 'lab.mesin as no_mesin')->join('laporan_hasil_produksi as lap', 'lap.nomor_laporan_produksi', '=', 'lab.nomor_laporan_produksi')->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->whereBetween('lap.tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->whereNotNull('lab.mesh')->orderBy('lab.mesh', 'asc')->groupBy('lab.mesh', 'lab.mesin')->get();
        }

        $arrayForTable = [];
        foreach($data_lab as $data_lab){
            $temp = [];
            $temp['mesin'] = $data_lab->mesin;
            $temp['no_mesin'] = $data_lab->no_mesin;
            if(!isset($arrayForTable[$data_lab->mesh])){
                $arrayForTable[$data_lab->mesh] = [];
            }
            $arrayForTable[$data_lab->mesh][] = $temp;
        }

        return Response()->json($arrayForTable);
    }

    public function getDataGrafikLab($from_date, $to_date){
    	$currentMonth = date('m');
        $currentYear = date('Y');
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);

        $tanggal_dari = date('Y-m-01', strtotime($val_from_date));
        $tanggal_ke = date('Y-m-31', strtotime($val_to_date));

        if($val_from_date == ''){
    	   $data_lab = DB::table('laporan_hasil_produksi_lab as lab')->select('lab.mesh', 'tbl_mesin.name as mesin', 'lab.mesin as no_mesin', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join('laporan_hasil_produksi as lap', 'lap.nomor_laporan_produksi', '=', 'lab.nomor_laporan_produksi')->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->whereNotNull('lab.mesh')->orderBy('lab.mesh', 'asc')->groupBy('lab.mesh', 'lab.mesin')->get();
        }else{
            $data_lab = DB::table('laporan_hasil_produksi_lab as lab')->select('lab.mesh', 'tbl_mesin.name as mesin', 'lab.mesin as no_mesin', 'spek.residue_max', 'spek.whiteness_num as spek_whiteness', 'spek.moisture_num as spek_moisture', 'spek.residue_num as spek_residue')->join('laporan_hasil_produksi as lap', 'lap.nomor_laporan_produksi', '=', 'lab.nomor_laporan_produksi')->join('tbl_mesin', 'tbl_mesin.id', '=', 'lab.mesin')->join('tbl_spesifikasi_mesin as spek', 'spek.mesin', '=', 'lab.mesin')->whereBetween('lap.tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->whereNotNull('lab.mesh')->orderBy('lab.mesh', 'asc')->groupBy('lab.mesh', 'lab.mesin')->get();
        }

    	$arrayForTable = [];
    	foreach($data_lab as $data_lab){
    		$temp = [];
    		$temp['mesin'] = $data_lab->mesin;
    		$temp['no_mesin'] = $data_lab->no_mesin;
    		$temp['spek_whiteness'] = $data_lab->spek_whiteness;
    		$temp['spek_moisture'] = $data_lab->spek_moisture;
    		$temp['spek_residue'] = $data_lab->spek_residue;
    		if(!isset($arrayForTable[$data_lab->mesh])){
    			$arrayForTable[$data_lab->mesh] = [];
    		}
    		$arrayForTable[$data_lab->mesh][] = $temp;
    	}

    	return Response()->json($arrayForTable);
    }

    public function getDataDetailGrafikLab(Request $request){
    	$currentMonth = date('m');
        $currentYear = date('Y');

        $tanggal_dari = date('Y-m-01', strtotime($request->from_date));
        $tanggal_ke = date('Y-m-31', strtotime($request->to_date));

        if($request->from_date == ''){
    	   $data_lab = DB::table('laporan_hasil_produksi_lab as lab')->select('lap.tanggal_laporan_produksi', 'lab.ssa', 'lab.d50', 'lab.cie86', 'lab.moisture', 'lab.residue')->join('laporan_hasil_produksi as lap', 'lap.nomor_laporan_produksi', '=', 'lab.nomor_laporan_produksi')->whereRaw('MONTH(lap.tanggal_laporan_produksi) = ?',[$currentMonth])->whereRaw('YEAR(lap.tanggal_laporan_produksi) = ?',[$currentYear])->where('mesh', $request->mesh)->where('mesin', $request->mesin)->where(function ($query) { $query->whereNotNull('lab.ssa')->orWhereNotNull('lab.d50')->orWhereNotNull('lab.cie86')->orWhereNotNull('lab.moisture')->orWhereNotNull('lab.residue'); })->groupBy('lap.tanggal_laporan_produksi')->get();
        }else{
            $data_lab = DB::table('laporan_hasil_produksi_lab as lab')->select('lap.tanggal_laporan_produksi', 'lab.ssa', 'lab.d50', 'lab.cie86', 'lab.moisture', 'lab.residue')->join('laporan_hasil_produksi as lap', 'lap.nomor_laporan_produksi', '=', 'lab.nomor_laporan_produksi')->whereBetween('lap.tanggal_laporan_produksi', array($tanggal_dari, $tanggal_ke))->where('mesh', $request->mesh)->where('mesin', $request->mesin)->where(function ($query) { $query->whereNotNull('lab.ssa')->orWhereNotNull('lab.d50')->orWhereNotNull('lab.cie86')->orWhereNotNull('lab.moisture')->orWhereNotNull('lab.residue'); })->groupBy('lap.tanggal_laporan_produksi')->get();
        }

    	return Response()->json($data_lab);
    }

    public function excelGrafikLab(){
        return Excel::download(new GrafikEvalLabExport, 'Grafik Lab ' . date('m-Y') . '.xlsx');
    }
}
