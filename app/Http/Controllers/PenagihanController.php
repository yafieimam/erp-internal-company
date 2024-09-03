<?php

namespace App\Http\Controllers;

use App\ModelPenagihan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Response;
use PDF;
use Excel;
use File;
use App\Imports\PenagihanImport;
use App\Exports\KartuPiutangExport;
use App\Exports\AgingScheduleExport;

class PenagihanController extends Controller
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

    public function getViewAdminDSGMKirimDok(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_kirim_trunojoyo');
        }
    }

    public function getViewAdminDSGMPembayaran(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_admin_dsgm_pembayaran');
        }
    }

    public function getViewAdminDSGMKirimBG(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_kirim_cek_trunojoyo');
        }
    }

    public function getViewAdminDSGMLaporan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_laporan_dsgm');
        }
    }

    public function getViewPenagihanKirimCekCollector(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_kirim_cek_collector');
        }
    }

    public function getViewAdminTrunojoyoTerimaDok(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_terima_dsgm');
        }
    }

    public function getViewPenagihanCashierTerimaDok(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_terima_ondomohen');
        }
    }

    public function getViewAdminTrunojoyoTerimaCek(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_terima_cek');
        }
    }

    public function getViewPenagihanTerimaDokumenPelunasan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_terima_dokumen_pelunasan');
        }
    }

    public function getViewAdminTrunojoyoJadwalPenyerahan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_jadwal_penyerahan_dokumen');
        }
    }

    public function getViewAdminTrunojoyoJadwalPenagihan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_jadwal_penagihan');
        }
    }

    public function getViewKartuPiutangPenagihan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_kartu_piutang');
        }
    }

    public function getViewAgingSchedulePenagihan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_aging_schedule');
        }
    }

    public function getViewAdminTrunojoyoDokumenPelunasan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_dokumen_pelunasan');
        }
    }

    public function getViewAdminTrunojoyoLaporan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_laporan_trunojoyo');
        }
    }

    public function getViewPenagihanPelunasan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            DB::table('temp_no_invoice')->where('id_user', Session::get('id_user_admin'))->delete();
            return view('pelunasan');
        }
    }

    public function getViewPenagihanCashierPelunasan(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            DB::table('temp_no_invoice')->where('id_user', Session::get('id_user_admin'))->delete();
            return view('pelunasan_cashier');
        }
    }

    public function getViewJadwalPenyerahanCollector(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_jadwal_penyerahan_dokumen_collector');
        }
    }

    public function getViewJadwalPenagihanCollector(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_jadwal_penagihan_collector');
        }
    }

    public function getViewPembayaran(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('pembayaran');
        }
    }

    public function getViewPenagihanPembayaran(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('pembayaran_ondomohen');
        }
    }

    public function getViewAdminDSGMPenyerahanDokumen(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_penyerahan_dokumen_admin');
        }
    }

    public function getViewCashierTerimaCek(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('tagih_cashier_terima_cek');
        }
    }

    public function viewAdminDSGMKirimTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_trunojoyo', 0)->where('check_diserahkan_admin', 0)->where('check_dikirim_ondomohen', 0)->whereBetween('tanggal_do', array($request->from_date, $request->to_date))->groupBy('tanggal_do')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_trunojoyo', 0)->where('check_diserahkan_admin', 0)->where('check_dikirim_ondomohen', 0)->groupBy('tanggal_do')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_kirim')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    } 

    public function viewAdminDSGMKirimOndomohenTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_trunojoyo', 0)->where('check_diserahkan_admin', 0)->where('check_dikirim_ondomohen', 1)->whereBetween('tanggal_do', array($request->from_date, $request->to_date))->groupBy('tanggal_do')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_trunojoyo', 0)->where('check_diserahkan_admin', 0)->where('check_dikirim_ondomohen', 1)->groupBy('tanggal_do')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_kirim_ondomohen')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }   

    public function viewAdminDSGMTerkirimTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_trunojoyo', DB::raw("count(distinct nosj) as jumlah_sj"))->where(function ($query) { $query->where('check_dikirim_trunojoyo', 1)->orWhere('check_dibayar_admin', 1); })->whereBetween('tanggal_kirim_trunojoyo', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_trunojoyo')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_trunojoyo', DB::raw("count(distinct nosj) as jumlah_sj"))->where(function ($query) { $query->where('check_dikirim_trunojoyo', 1)->orWhere('check_dibayar_admin', 1); })->groupBy('tanggal_kirim_trunojoyo')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terkirim')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }   

    public function viewAdminDSGMTerkirimOndomohenTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"))->where(function ($query) { $query->where('check_dikirim_ondomohen', 1)->orWhere('check_dibayar_admin', 1); })->where('bayar_setelah_tt', 0)->whereBetween('tanggal_kirim_ondomohen', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_ondomohen')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"))->where(function ($query) { $query->where('check_dikirim_ondomohen', 1)->orWhere('check_dibayar_admin', 1); })->where('bayar_setelah_tt', 0)->groupBy('tanggal_kirim_ondomohen')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terkirim_ondomohen')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }   

    public function viewAdminDSGMKirimDetailTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname as customer, pen.keterangan, pen.check_dikirim_trunojoyo, pen.check_dikirim_ondomohen, pen.check_diserahkan_admin, pen.check_dibayar_admin, pen.tanggal_terima_dokumen_cust, pen.tanggal_tagih_cust, pen.metode_pembayaran, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_do = ? and pen.check_dikirim_trunojoyo = 0 and pen.check_diserahkan_admin = 0 and check_dikirim_ondomohen = 0 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }   

    public function viewAdminDSGMKirimOndomohenDetailTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname as customer, pen.keterangan, pen.check_dikirim_trunojoyo, pen.check_dikirim_ondomohen, pen.check_diserahkan_admin, pen.check_dibayar_admin, pen.tanggal_terima_dokumen_cust, pen.tanggal_tagih_cust, pen.metode_pembayaran, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_do = ? and pen.check_dikirim_trunojoyo = 0 and pen.check_diserahkan_admin = 0 and check_dikirim_ondomohen = 1 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }   

    public function viewDataAdminDSGMKirimTrunojoyo(Request $request){
        if(request()->ajax()){
            $data = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname, pen.custid, pen.keterangan, pen.check_dikirim_trunojoyo, pen.tanggal_kirim_trunojoyo, pen.ket_trunojoyo, pen.check_dibayar_admin, pen.keterangan_penagihan, pen.keterangan_penerimaan, pen.check_diserahkan_admin, pen.tanggal_dibayar_admin, pen.bayar_setelah_tt, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_kirim_trunojoyo = ? and (pen.check_dikirim_trunojoyo = 1 or pen.check_dibayar_admin = 1 or pen.check_diserahkan_admin = 1) group by pen.nosj union select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname, pen.custid, pen.keterangan, pen.check_dikirim_trunojoyo, pen.tanggal_kirim_trunojoyo, pen.ket_trunojoyo, pen.check_dibayar_admin, pen.keterangan_penagihan, pen.keterangan_penerimaan, pen.check_diserahkan_admin, pen.tanggal_dibayar_admin, pen.bayar_setelah_tt, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_dibayar_admin = ? and pen.check_dibayar_admin = 1 and pen.bayar_setelah_tt = 1 group by pen.nosj", [$request->tanggal, $request->tanggal]);

            return datatables()->of($data)->addIndexColumn()->make(true);
        }

        return view('tagih_kirim_trunojoyo');
    }

    public function viewDataAdminDSGMKirimOndomohen(Request $request){
        if(request()->ajax()){
            $data = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname, pen.custid, pen.keterangan, pen.check_dikirim_ondomohen, pen.tanggal_kirim_ondomohen, pen.ket_ondomohen, pen.check_dibayar_admin, pen.keterangan_penagihan, pen.keterangan_penerimaan, pen.check_diserahkan_admin, pen.metode_pembayaran, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_kirim_ondomohen = ? and (pen.check_dikirim_ondomohen = 1 or pen.check_dibayar_admin = 1 or pen.check_diserahkan_admin = 1) group by pen.nosj", [$request->tanggal]);

            return datatables()->of($data)->addIndexColumn()->make(true);
        }

        return view('tagih_kirim_trunojoyo');
    }

    public function updateAdminDSGMKirimTrunojoyo(Request $request){
	    $nosj = $request->get('nosj');
	    $keterangan = $request->get('keterangan');
        $ket_trunojoyo = $request->get('ket_trunojoyo');
        $ket_ondomohen = $request->get('ket_ondomohen');
	    $dikirim = $request->get('dikirim');
        $dikirim_ondomohen = $request->get('dikirim_ondomohen');
        $diserahkan = $request->get('diserahkan');
        $dibayar = $request->get('dibayar');
        $tanggal = $request->get('tagih_cust');
        $metode = $request->get('metode');
        $nomor_metode = $request->get('nomor');
        $nominal = $request->get('nominal');

	    date_default_timezone_set('Asia/Jakarta');

	    foreach($nosj as $nomor) {
            if(is_array($diserahkan) && array_key_exists($nomor,$diserahkan)){
                if(is_array($dibayar) && array_key_exists($nomor,$dibayar)){
                    if(is_array($dikirim_ondomohen) && array_key_exists($nomor,$dikirim_ondomohen)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_trunojoyo' => 1, 'check_diserahkan_admin' => 1, 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'tanggal_diserahkan_admin' => date('Y-m-d'), 'check_dibayar_admin' => 1, 'tanggal_dibayar_admin' => date('Y-m-d'), 'tanggal_terima_dokumen_cust' => date('Y-m-d'), 'tanggal_tagih_cust' => date('Y-m-d', strtotime($tanggal[$nomor])), 'metode_pembayaran' => $metode[$nomor], 'nomor_metode_pembayaran' => $nomor_metode[$nomor], 'nominal_bayar' => $nominal[$nomor], 'keterangan_penagihan' => $keterangan[$nomor], 'status_jadwal' => 10, 'status_hadir' => 2, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
                    }else{
                        $date1 = strtr($tanggal[$nomor], '/', '-');
                        DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_trunojoyo' => 1, 'check_diserahkan_admin' => 1, 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'tanggal_diserahkan_admin' => date('Y-m-d'), 'check_dikirim_ondomohen' => 1, 'tanggal_kirim_ondomohen' => date('Y-m-d'), 'check_dibayar_admin' => 1, 'tanggal_dibayar_admin' => date('Y-m-d'), 'tanggal_terima_dokumen_cust' => date('Y-m-d'), 'tanggal_tagih_cust' => date('Y-m-d', strtotime($date1)), 'metode_pembayaran' => $metode[$nomor], 'nomor_metode_pembayaran' => $nomor_metode[$nomor], 'nominal_bayar' => $nominal[$nomor], 'keterangan_penagihan' => $keterangan[$nomor], 'status_jadwal' => 10, 'status_hadir' => 2, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
                    }

                    if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                    }else{
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                    }

                    if(is_array($ket_ondomohen) && array_key_exists($nomor, $ket_ondomohen)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 1]);
                    }else{
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 0]);
                    }
            
                    DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 10, 'action' => 'Penyerahan Dokumen dan Penagihan Langsung melalui Admin DSGM dan diserahkan ke Kasir. Packing List ke Trunojoyo dan Ondomohen']);
                }else{
                    if(is_array($dikirim_ondomohen) && array_key_exists($nomor,$dikirim_ondomohen)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_trunojoyo' => 1, 'check_diserahkan_admin' => 1, 'tanggal_terima_dokumen_cust' => date('Y-m-d'), 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'tanggal_diserahkan_admin' => date('Y-m-d'), 'keterangan_penerimaan' => $keterangan[$nomor], 'status_jadwal' => 9, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                        if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                        }else{
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                        }

                        if(is_array($ket_ondomohen) && array_key_exists($nomor, $ket_ondomohen)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 1]);
                        }else{
                                DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 0]);
                            }

                        DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 9, 'action' => 'Penyerahan Dokumen Langsung melalui Admin DSGM dan Akan Dilakukan Pengisian Data. Packing List ke Trunojoyo']);
                    }else{  
                        DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_trunojoyo' => 1, 'check_diserahkan_admin' => 1, 'tanggal_terima_dokumen_cust' => date('Y-m-d'), 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'tanggal_diserahkan_admin' => date('Y-m-d'), 'check_dikirim_ondomohen' => 1, 'tanggal_kirim_ondomohen' => date('Y-m-d'), 'keterangan_penerimaan' => $keterangan[$nomor], 'status_jadwal' => 9, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                        if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                        }else{
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                        }

                        if(is_array($ket_ondomohen) && array_key_exists($nomor, $ket_ondomohen)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 1]);
                        }else{
                                DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 0]);
                            }

                        DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 9, 'action' => 'Penyerahan Dokumen Langsung melalui Admin DSGM dan Akan Dilakukan Pengisian Data. Packing List ke Trunojoyo dan Ondomohen']);
                    }
                }
            }else{
                if(is_array($dikirim) && array_key_exists($nomor,$dikirim)){
                    if(is_array($dikirim_ondomohen) && array_key_exists($nomor,$dikirim_ondomohen)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['keterangan' => $keterangan[$nomor], 'check_dikirim_trunojoyo' => 1, 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'status_jadwal' => 1, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                        if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                        }else{
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                        }

                        if(is_array($ket_ondomohen) && array_key_exists($nomor, $ket_ondomohen)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 1]);
                        }else{
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 0]);
                        }

                        DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 1, 'action' => 'Update Field Keterangan dan Checklist Kirim Data ke Trunojoyo']);
                    }else{
                        DB::table('penagihan')->where('nosj', $nomor)->update(['keterangan' => $keterangan[$nomor], 'check_dikirim_trunojoyo' => 1, 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'check_dikirim_ondomohen' => 1, 'tanggal_kirim_ondomohen' => date('Y-m-d'), 'status_jadwal' => 1, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                        if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                        }else{
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                        }

                        if(is_array($ket_ondomohen) && array_key_exists($nomor, $ket_ondomohen)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 1]);
                        }else{
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 0]);
                        }

                        DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 1, 'action' => 'Update Field Keterangan dan Checklist Kirim Data ke Trunojoyo dan Ondomohen']);
                    }
                }else{
                    if(is_array($dikirim_ondomohen) && array_key_exists($nomor,$dikirim_ondomohen)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['keterangan' => $keterangan[$nomor], 'check_dikirim_ondomohen' => 1, 'tanggal_kirim_ondomohen' => date('Y-m-d'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                        if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                        }else{
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                        }

                        if(is_array($ket_ondomohen) && array_key_exists($nomor, $ket_ondomohen)){
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 1]);
                        }else{
                            DB::table('penagihan')->where('nosj', $nomor)->update(['ket_ondomohen' => 0]);
                        }

                        DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Update Field Keterangan dan Checklist Kirim Data ke Ondomohen Saja']);
                    }
                }
            }
	    }

	    return Response()->json();
	}

    public function updateAdminDSGMKirimOndomohen(Request $request){
        $nosj = $request->get('nosj');
        $keterangan = $request->get('keterangan');
        $ket_trunojoyo = $request->get('ket_trunojoyo');
        $dikirim = $request->get('dikirim');
        $diserahkan = $request->get('diserahkan');
        $dibayar = $request->get('dibayar');
        $tanggal = $request->get('tagih_cust');
        $metode = $request->get('metode');
        $nomor_metode = $request->get('nomor');
        $nominal = $request->get('nominal');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            if(is_array($diserahkan) && array_key_exists($nomor,$diserahkan)){
                if(is_array($dibayar) && array_key_exists($nomor,$dibayar)){
                    DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_trunojoyo' => 1, 'check_diserahkan_admin' => 1, 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'tanggal_diserahkan_admin' => date('Y-m-d'), 'check_dibayar_admin' => 1, 'tanggal_dibayar_admin' => date('Y-m-d'), 'tanggal_terima_dokumen_cust' => date('Y-m-d'), 'tanggal_tagih_cust' => date('Y-m-d', strtotime($tanggal[$nomor])), 'metode_pembayaran' => $metode[$nomor], 'nomor_metode_pembayaran' => $nomor_metode[$nomor], 'nominal_bayar' => $nominal[$nomor], 'keterangan_penagihan' => $keterangan[$nomor], 'status_jadwal' => 10, 'status_hadir' => 2, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                    if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                    }else{
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                    }
            
                    DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 10, 'action' => 'Penyerahan Dokumen dan Penagihan Langsung melalui Admin DSGM dan diserahkan ke Kasir. Packing List ke Trunojoyo']);
                }else{
                    DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_trunojoyo' => 1, 'check_diserahkan_admin' => 1, 'tanggal_terima_dokumen_cust' => date('Y-m-d'), 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'tanggal_diserahkan_admin' => date('Y-m-d'), 'keterangan_penerimaan' => $keterangan[$nomor], 'status_jadwal' => 9, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                    if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                    }else{
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                    }

                    DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 9, 'action' => 'Penyerahan Dokumen Langsung melalui Admin DSGM dan Akan Dilakukan Pengisian Data. Packing List ke Trunojoyo']);
                }
            }else{
                if(is_array($dikirim) && array_key_exists($nomor,$dikirim)){
                    DB::table('penagihan')->where('nosj', $nomor)->update(['keterangan' => $keterangan[$nomor], 'check_dikirim_trunojoyo' => 1, 'tanggal_kirim_trunojoyo' => date('Y-m-d'), 'status_jadwal' => 1, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                    if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 1]);
                    }else{
                        DB::table('penagihan')->where('nosj', $nomor)->update(['ket_trunojoyo' => 0]);
                    }

                    DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 1, 'action' => 'Update Field Keterangan dan Checklist Kirim Data ke Trunojoyo']);
                }
            }
        }

        return Response()->json();
    }

    public function viewAdminDSGMKirimTrunojoyoDetailEditTable(Request $request){
        if(request()->ajax()){
            $penagihan = ModelPenagihan::select('nosj', 'noinv', 'custname as customer', 'custid', 'itemid', 'top', 'tanggal_jatuh_tempo', 'dpp', 'ppn', 'amount', 'qty', 'price', 'diskon', 'sub_amount')->where('tanggal_do', $request->tanggal)->where('check_dikirim_trunojoyo', 0)->where('check_diserahkan_admin', 0)->where('check_dikirim_trunojoyo', 0)->get();

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }   

    public function viewAdminDSGMKirimOndomohenDetailEditTable(Request $request){
        if(request()->ajax()){
            $penagihan = ModelPenagihan::select('nosj', 'noinv', 'custname as customer', 'custid', 'itemid', 'top', 'tanggal_jatuh_tempo', 'dpp', 'ppn', 'amount', 'qty', 'price', 'diskon', 'sub_amount')->where('tanggal_do', $request->tanggal)->where('check_dikirim_trunojoyo', 0)->where('check_diserahkan_admin', 0)->where('check_dikirim_ondomohen', 0)->get();

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }   

    public function editAdminDSGMKirimTrunojoyo(Request $request){
        $nosj = $request->get('nosj');
        $itemid = $request->get('itemid');
        $noinv = $request->get('noinv');
        $top = $request->get('top');
        $tanggal = $request->get('tanggal_jatuh_tempo');
        $dpp = $request->get('dpp');
        $ppn = $request->get('ppn');
        $amount = $request->get('amount');
        $qty = $request->get('qty');
        $price = $request->get('price');
        $diskon = $request->get('diskon');
        $sub_amount = $request->get('sub_amount');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            foreach($itemid[$nomor] as $item) {
                DB::table('delivery_orders')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item]]);

                DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
                
                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Melakukan Edit Data Omset No. ' . $nomor]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Update Data Omset']);
            }
        }

        return Response()->json();
    }

    public function editAdminDSGMKirimOndomohen(Request $request){
        $nosj = $request->get('nosj');
        $itemid = $request->get('itemid');
        $noinv = $request->get('noinv');
        $top = $request->get('top');
        $tanggal = $request->get('tanggal_jatuh_tempo');
        $dpp = $request->get('dpp');
        $ppn = $request->get('ppn');
        $amount = $request->get('amount');
        $qty = $request->get('qty');
        $price = $request->get('price');
        $diskon = $request->get('diskon');
        $sub_amount = $request->get('sub_amount');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            foreach($itemid[$nomor] as $item) {
                DB::table('delivery_orders')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item]]);

                DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
                
                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Melakukan Edit Data Omset No. ' . $nomor]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Update Data Omset']);
            }
        }

        return Response()->json();
    }    

    public function viewAdminDSGMTerkirimTrunojoyoDetailEditTable(Request $request){
        if(request()->ajax()){
            $data_bayar = DB::table('penagihan')->select('nosj', 'noinv', 'custname as customer', 'custid', 'itemid', 'top', 'tanggal_jatuh_tempo', 'dpp', 'ppn', 'amount', 'qty', 'price', 'diskon', 'sub_amount', 'check_dikirim_trunojoyo', 'check_dibayar_admin', 'check_diserahkan_admin', 'keterangan', 'keterangan_penagihan', 'keterangan_penerimaan', 'ket_trunojoyo', 'ket_ondomohen')->where('tanggal_dibayar_admin', $request->tanggal)->where('check_dibayar_admin', 1)->where('bayar_setelah_tt', 1);

            $data = DB::table('penagihan')->select('nosj', 'noinv', 'custname as customer', 'custid', 'itemid', 'top', 'tanggal_jatuh_tempo', 'dpp', 'ppn', 'amount', 'qty', 'price', 'diskon', 'sub_amount', 'check_dikirim_trunojoyo', 'check_dibayar_admin', 'check_diserahkan_admin', 'keterangan', 'keterangan_penagihan', 'keterangan_penerimaan', 'ket_trunojoyo', 'ket_ondomohen')->where('tanggal_kirim_trunojoyo', $request->tanggal)->where(function ($query) { $query->where('check_dikirim_trunojoyo', 1)->orWhere('check_dibayar_admin', 1)->orWhere('check_diserahkan_admin', 1); })->union($data_bayar)->get();

            return datatables()->of($data)->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }

    public function viewAdminDSGMTerkirimOndomohenDetailEditTable(Request $request){
        if(request()->ajax()){
            $data = DB::table('penagihan')->select('nosj', 'noinv', 'custname as customer', 'custid', 'itemid', 'top', 'tanggal_jatuh_tempo', 'dpp', 'ppn', 'amount', 'qty', 'price', 'diskon', 'sub_amount', 'check_dikirim_trunojoyo', 'check_dibayar_admin', 'check_diserahkan_admin', 'keterangan', 'keterangan_penagihan', 'keterangan_penerimaan', 'ket_trunojoyo', 'ket_ondomohen')->where('tanggal_kirim_ondomohen', $request->tanggal)->where(function ($query) { $query->where('check_dikirim_ondomohen', 1)->orWhere('check_dibayar_admin', 1)->orWhere('check_diserahkan_admin', 1); })->get();

            return datatables()->of($data)->make(true);
        }
        return view('tagih_kirim_trunojoyo');
    }

    public function editAdminDSGMTerkirimTrunojoyo(Request $request){
        $nosj = $request->get('nosj');
        $itemid = $request->get('itemid');
        $noinv = $request->get('noinv');
        $top = $request->get('top');
        $tanggal = $request->get('tanggal_jatuh_tempo');
        $dpp = $request->get('dpp');
        $ppn = $request->get('ppn');
        $amount = $request->get('amount');
        $qty = $request->get('qty');
        $price = $request->get('price');
        $diskon = $request->get('diskon');
        $sub_amount = $request->get('sub_amount');
        $keterangan = $request->get('keterangan');
        $keterangan_penagihan = $request->get('keterangan_penagihan');
        $keterangan_penerimaan = $request->get('keterangan_penerimaan');
        $ket_trunojoyo = $request->get('ket_trunojoyo');
        $ket_ondomohen = $request->get('ket_ondomohen');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            foreach($itemid[$nomor] as $item) {
                DB::table('delivery_orders')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item]]);

                DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                if(is_array($keterangan) && array_key_exists($nomor, $keterangan)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['keterangan' => $keterangan[$nomor][$item]]);
                }

                if(is_array($keterangan_penagihan) && array_key_exists($nomor, $keterangan_penagihan)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['keterangan_penagihan' => $keterangan_penagihan[$nomor][$item]]);
                }

                if(is_array($keterangan_penerimaan) && array_key_exists($nomor, $keterangan_penerimaan)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['keterangan_penerimaan' => $keterangan_penerimaan[$nomor][$item]]);
                }

                if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['ket_trunojoyo' => 1]);
                }else{
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['ket_trunojoyo' => 0]);
                }

                if(is_array($ket_ondomohen) && array_key_exists($nomor, $ket_ondomohen)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['ket_ondomohen' => 1]);
                }else{
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['ket_ondomohen' => 0]);
                }

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Melakukan Edit Data Omset No. ' . $nomor]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Update Data Omset']);
            }
        }

        return Response()->json();
    }

    public function editAdminDSGMTerkirimOndomohen(Request $request){
        $nosj = $request->get('nosj');
        $itemid = $request->get('itemid');
        $noinv = $request->get('noinv');
        $top = $request->get('top');
        $tanggal = $request->get('tanggal_jatuh_tempo');
        $dpp = $request->get('dpp');
        $ppn = $request->get('ppn');
        $amount = $request->get('amount');
        $qty = $request->get('qty');
        $price = $request->get('price');
        $diskon = $request->get('diskon');
        $sub_amount = $request->get('sub_amount');
        $keterangan = $request->get('keterangan');
        $keterangan_penagihan = $request->get('keterangan_penagihan');
        $keterangan_penerimaan = $request->get('keterangan_penerimaan');
        $ket_trunojoyo = $request->get('ket_trunojoyo');
        $ket_ondomohen = $request->get('ket_ondomohen');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            foreach($itemid[$nomor] as $item) {
                DB::table('delivery_orders')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item]]);

                DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['noinv' => $noinv[$nomor][$item], 'top' => $top[$nomor][$item], 'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal[$nomor][$item])), 'dpp' => $dpp[$nomor][$item], 'ppn' => $ppn[$nomor][$item], 'amount' => $amount[$nomor][$item], 'qty' => $qty[$nomor][$item], 'price' => $price[$nomor][$item], 'diskon' => $diskon[$nomor][$item], 'sub_amount' => $sub_amount[$nomor][$item], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                if(is_array($keterangan) && array_key_exists($nomor, $keterangan)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['keterangan' => $keterangan[$nomor][$item]]);
                }

                if(is_array($keterangan_penagihan) && array_key_exists($nomor, $keterangan_penagihan)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['keterangan_penagihan' => $keterangan_penagihan[$nomor][$item]]);
                }

                if(is_array($keterangan_penerimaan) && array_key_exists($nomor, $keterangan_penerimaan)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['keterangan_penerimaan' => $keterangan_penerimaan[$nomor][$item]]);
                }

                if(is_array($ket_trunojoyo) && array_key_exists($nomor, $ket_trunojoyo)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['ket_trunojoyo' => 1]);
                }else{
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['ket_trunojoyo' => 0]);
                }

                if(is_array($ket_ondomohen) && array_key_exists($nomor, $ket_ondomohen)){
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['ket_ondomohen' => 1]);
                }else{
                    DB::table('penagihan')->where('nosj', $nomor)->where('itemid', $item)->update(['ket_ondomohen' => 0]);
                }

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Melakukan Edit Data Omset No. ' . $nomor]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Update Data Omset']);
            }
        }

        return Response()->json();
    }

    public function viewAdminDSGMPembayaranTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_trunojoyo', 1)->where('check_dibayar_admin', 0)->where('check_dibayar', 0)->whereBetween('tanggal_do', array($request->from_date, $request->to_date))->groupBy('tanggal_do')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_trunojoyo', 1)->where('check_dibayar_admin', 0)->where('check_dibayar', 0)->groupBy('tanggal_do')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_admin_dsgm_pembayaran')->rawColumns(['action'])->make(true);
        }
        return view('tagih_admin_dsgm_pembayaran');
    }  

    public function viewAdminDSGMPembayaranDetailTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, pen.check_dibayar_admin, pen.keterangan_penagihan, pen.tanggal_tagih_cust, pen.metode_pembayaran, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_do = ? and pen.check_dikirim_trunojoyo = 1 and pen.check_dibayar_admin = 0 and pen.check_dibayar = 0 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_admin_dsgm_pembayaran');
    }    

    public function viewAdminDSGMSudahBayarTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_dibayar_admin', DB::raw("count(distinct nosj) as jumlah_sj"))->where(function ($query) { $query->where('check_dikirim_trunojoyo', 1)->orWhere('check_dibayar_admin', 1); })->whereBetween('tanggal_dibayar_admin', array($request->from_date, $request->to_date))->groupBy('tanggal_dibayar_admin')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_dibayar_admin', DB::raw("count(distinct nosj) as jumlah_sj"))->where(function ($query) { $query->where('check_dikirim_ondomohen', 1)->orWhere('check_dibayar_admin', 1); })->groupBy('tanggal_dibayar_admin')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_admin_dsgm_sudah_bayar')->rawColumns(['action'])->make(true);
        }
        return view('tagih_admin_dsgm_pembayaran');
    }   

    public function updateAdminDSGMPembayaran(Request $request){
        $nosj = $request->get('nosj');
        $keterangan = $request->get('keterangan');
        $tanggal = $request->get('tagih_cust');
        $metode = $request->get('metode');
        $nomor_metode = $request->get('nomor');
        $nominal = $request->get('nominal');
        $dibayar = $request->get('dibayar');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            if(is_array($dibayar) && array_key_exists($nomor,$dibayar)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_dibayar_admin' => 1, 'bayar_setelah_tt' => 1, 'tanggal_dibayar_admin' => date('Y-m-d'), 'tanggal_tagih_cust' => date('Y-m-d', strtotime($tanggal[$nomor])), 'metode_pembayaran' => $metode[$nomor], 'nomor_metode_pembayaran' => $nomor_metode[$nomor], 'nominal_bayar' => $nominal[$nomor], 'keterangan_penagihan' => $keterangan[$nomor], 'status_jadwal' => 18, 'status_hadir' => 2, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
                    
                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 18, 'action' => 'Pembayaran melalui Admin DSGM setelah TT. Masuk dalam Packing List ke Trunojoyo']);
            }
        }

        return Response()->json();
    }

    public function viewAdminDSGMKirimBGTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_dibayar_admin', DB::raw("count(distinct nosj) as jumlah_sj"))->where('metode_pembayaran', 2)->where('check_dikirim_cek', 0)->where('bayar_setelah_tt', 1)->whereBetween('tanggal_dibayar_admin', array($request->from_date, $request->to_date))->groupBy('tanggal_dibayar_admin')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_dibayar_admin', DB::raw("count(distinct nosj) as jumlah_sj"))->where('metode_pembayaran', 2)->where('check_dikirim_cek', 0)->where('bayar_setelah_tt', 1)->groupBy('tanggal_dibayar_admin')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_kirim_cek')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_cek_trunojoyo');
    }   

    public function viewAdminDSGMTerkirimBGTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_cek', DB::raw("count(distinct nosj) as jumlah_sj"))->where('metode_pembayaran', 2)->where('check_dikirim_cek', 1)->where('bayar_setelah_tt', 1)->whereBetween('tanggal_kirim_cek', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_cek')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_cek', DB::raw("count(distinct nosj) as jumlah_sj"))->where('metode_pembayaran', 2)->where('check_dikirim_cek', 1)->where('bayar_setelah_tt', 1)->groupBy('tanggal_kirim_cek')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terkirim_cek')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_cek_trunojoyo');
    }   

    public function viewAdminDSGMKirimBGDetailTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_tagih_cust, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.check_dikirim_cek, pen.keterangan_kirim_cek, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_dibayar_admin = ? and pen.metode_pembayaran = 2 and pen.check_dikirim_cek = 0 and pen.bayar_setelah_tt = 1 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_kirim_cek_trunojoyo');
    }  

    public function updateAdminDSGMKirimBG(Request $request){
        $nosj = $request->get('nosj');
        $dikirim = $request->get('dikirim');
        $keterangan = $request->get('keterangan');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            if(is_array($dikirim) && array_key_exists($nomor,$dikirim)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_cek' => 1, 'tanggal_kirim_cek' => date('Y-m-d'), 'keterangan_kirim_cek' => $keterangan[$nomor], 'status_jadwal' => 11, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 11, 'action' => 'Update Field Checklist Kirim Penerimaan BG ke Trunojoyo']);
            }
        }

        return Response()->json();
    }

    public function viewPenagihanKirimCekOndomohenTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_terima_cek', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_terima_cek')->where('check_diterima_cek', 1)->where('check_dikirim_cek_ondomohen', 0)->whereBetween('tanggal_terima_cek', array($request->from_date, $request->to_date))->groupBy('tanggal_terima_cek')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_terima_cek', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_terima_cek')->where('check_diterima_cek', 1)->where('check_dikirim_cek_ondomohen', 0)->groupBy('tanggal_terima_cek')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_kirim_cek_ondomohen')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_cek_ondomohen');
    }   

    public function viewPenagihanTerkirimCekOndomohenTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_cek_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_cek_ondomohen', 1)->whereBetween('tanggal_kirim_cek_ondomohen', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_cek_ondomohen')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_cek_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_cek_ondomohen', 1)->groupBy('tanggal_kirim_cek_ondomohen')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terkirim_cek_ondomohen')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_cek_ondomohen');
    }   

    public function viewPenagihanKirimDetailCekOndomohenTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname as customer, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_terima_cek, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.check_dikirim_cek_ondomohen, pen.keterangan_kirim_cek_ondomohen, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_terima_cek = ? and pen.check_diterima_cek = 1 and pen.check_dikirim_cek_ondomohen = 0 and pen.tanggal_terima_cek is not null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_kirim_cek_ondomohen');
    }  

    public function updatePenagihanKirimCekOndomohen(Request $request){
        $nosj = $request->get('nosj');
        $dikirim = $request->get('dikirim');
        $keterangan = $request->get('keterangan');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            if(is_array($dikirim) && array_key_exists($nomor,$dikirim)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_cek_ondomohen' => 1, 'tanggal_kirim_cek_ondomohen' => date('Y-m-d'), 'keterangan_kirim_cek_ondomohen' => $keterangan[$nomor], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 13, 'action' => 'Update Field Checklist Kirim Data Cek / Giro ke Ondomohen']);
            }
        }

        return Response()->json();
    }

    public function viewPenagihanKirimCekCollectorTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 7)->where('metode_pembayaran', '!=', 1)->where('check_dikirim_cek', 0)->whereBetween('tanggal_tagih_cust', array($request->from_date, $request->to_date))->groupBy('tanggal_tagih_cust')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 7)->where('metode_pembayaran', '!=', 1)->where('check_dikirim_cek', 0)->groupBy('tanggal_tagih_cust')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_kirim_cek_collector')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_cek_collector');
    }   

    public function viewPenagihanTerkirimCekCollectorTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_cek', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 11)->where('metode_pembayaran', '!=', 1)->where('check_dikirim_cek', 1)->whereBetween('tanggal_kirim_cek', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_cek')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_cek', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 11)->where('metode_pembayaran', '!=', 1)->where('check_dikirim_cek', 1)->groupBy('tanggal_kirim_cek')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terkirim_cek_collector')->rawColumns(['action'])->make(true);
        }
        return view('tagih_kirim_cek_collector');
    }   

    public function viewPenagihanKirimDetailCekCollectorTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_tagih_cust, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.check_dikirim_cek, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_tagih_cust = ? and pen.status_jadwal = 7 and pen.metode_pembayaran != 1 and pen.check_dikirim_cek = 0 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_kirim_cek_collector');
    }  

    public function updatePenagihanKirimCekCollector(Request $request){
        $nosj = $request->get('nosj');
        $dikirim = $request->get('dikirim');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            if(is_array($dikirim) && array_key_exists($nomor,$dikirim)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_cek' => 1, 'tanggal_kirim_cek' => date('Y-m-d'), 'status_jadwal' => 11, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 11, 'action' => 'Update Field Checklist Kirim Data Cek / Giro ke Trunojoyo']);
            }
        }

        return Response()->json();
    }

    public function viewPenagihanDokumenPelunasanTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_bayar_cashier', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 8)->where('check_dikirim_dok_pelunasan', 0)->whereBetween('tanggal_bayar_cashier', array($request->from_date, $request->to_date))->groupBy('tanggal_bayar_cashier')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_bayar_cashier', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 8)->where('check_dikirim_dok_pelunasan', 0)->groupBy('tanggal_bayar_cashier')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_dokumen_pelunasan')->rawColumns(['action'])->make(true);
        }
        return view('tagih_dokumen_pelunasan');
    }   

    public function viewPenagihanTerkirimDokumenPelunasanTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_dok_pelunasan', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 15)->where('check_dikirim_dok_pelunasan', 1)->whereBetween('tanggal_kirim_dok_pelunasan', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_dok_pelunasan')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_dok_pelunasan', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 15)->where('check_dikirim_dok_pelunasan', 1)->groupBy('tanggal_kirim_dok_pelunasan')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terkirim_dokumen_pelunasan')->rawColumns(['action'])->make(true);
        }
        return view('tagih_dokumen_pelunasan');
    }   

    public function viewPenagihanDetailDokumenPelunasanTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_bayar_cashier, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.check_dikirim_dok_pelunasan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_bayar_cashier = ? and pen.status_jadwal = 8 and pen.check_dikirim_dok_pelunasan = 0 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_kirim_cek_trunojoyo');
    }  

    public function updatePenagihanDokumenPelunasan(Request $request){
        $nosj = $request->get('nosj');
        $dikirim = $request->get('dikirim');

        date_default_timezone_set('Asia/Jakarta');

        foreach($nosj as $nomor) {
            if(is_array($dikirim) && array_key_exists($nomor,$dikirim)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_dikirim_dok_pelunasan' => 1, 'tanggal_kirim_dok_pelunasan' => date('Y-m-d'), 'status_jadwal' => 15, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 15, 'action' => 'Update Field Checklist Kirim Dokumen Pelunasan ke Ondomohen']);
            }
        }

        return Response()->json();
    }

	public function printAdminDSGMKirimTrunojoyo($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname, pen.custid, pen.keterangan, pen.check_dikirim_trunojoyo, pen.tanggal_kirim_trunojoyo, pen.ket_trunojoyo, pen.check_dibayar_admin, pen.metode_pembayaran, pen.keterangan_penagihan, pen.keterangan_penerimaan, pen.check_diserahkan_admin, pen.bayar_setelah_tt, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_kirim_trunojoyo = ? and (pen.check_dikirim_trunojoyo = 1 or pen.check_dibayar_admin = 1 or pen.check_diserahkan_admin = 1) group by pen.nosj", [$val_tanggal]);

        $data_bayar = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname, pen.custid, pen.keterangan_penagihan, pen.tanggal_dibayar_admin, pen.check_dibayar_admin, pen.metode_pembayaran, pen.bayar_setelah_tt, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_dibayar_admin = ? and pen.check_dibayar_admin = 1 and pen.bayar_setelah_tt = 1 group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_packing_list', ['data' => $data, 'data_bayar' => $data_bayar])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function printAdminDSGMKirimOndomohen($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname, pen.custid, pen.keterangan, pen.check_dikirim_ondomohen, pen.tanggal_kirim_ondomohen, pen.ket_ondomohen, pen.check_dibayar_admin, pen.keterangan_penagihan, pen.keterangan_penerimaan, pen.check_diserahkan_admin, pen.metode_pembayaran, bayar_setelah_tt, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_kirim_ondomohen = ? and (pen.check_dikirim_ondomohen = 1 or pen.check_dibayar_admin = 1 or pen.check_diserahkan_admin = 1) group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_packing_list_ondomohen', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function printPenagihanKirimBG($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.custid, pen.custname, pen.tanggal_do, pen.check_dikirim_cek, met.name as metode, pen.tanggal_kirim_cek, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.keterangan_kirim_cek, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_kirim_cek = ? and pen.check_dikirim_cek = 1 group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_packing_list_cek', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function printPenagihanKirimCekOndomohen($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custid, pen.custname, pen.tanggal_do, pen.check_dikirim_cek_ondomohen, met.name as metode, pen.tanggal_kirim_cek_ondomohen, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.keterangan_kirim_cek_ondomohen, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_kirim_cek_ondomohen = ? and pen.check_dikirim_cek_ondomohen = 1 group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_packing_list_cek_ondomohen', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function printPenagihanKirimCekCollector($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.custid, pen.custname, pen.check_dikirim_cek, met.name as metode, pen.tanggal_kirim_cek, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_kirim_cek = ? and pen.status_jadwal = 11 and pen.check_dikirim_cek = 1 and pen.check_diserahkan_admin = 0 group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_packing_list_cek_collector', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function printPenagihanDokumenPelunasan($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.custid, pen.custname, pen.check_dikirim_dok_pelunasan, met.name as metode, pen.tanggal_kirim_dok_pelunasan, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.total_bayar as total, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan, ((coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) - pen.total_bayar) as sisa from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_kirim_dok_pelunasan = ? and pen.status_jadwal = 15 and pen.check_dikirim_dok_pelunasan = 1 group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_packing_list_dokumen_pelunasan', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewPenagihanTerimaTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_trunojoyo', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_trunojoyo', 1)->where('check_diterima_trunojoyo', 0)->whereBetween('tanggal_kirim_trunojoyo', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_trunojoyo')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_trunojoyo', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_trunojoyo', 1)->where('check_diterima_trunojoyo', 0)->groupBy('tanggal_kirim_trunojoyo')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terima')->rawColumns(['action'])->make(true);
        }
        return view('tagih_terima_dsgm');
    }   

    public function viewPenagihanTerimaDetailTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname as customer, pen.keterangan, pen.check_dibayar_admin, pen.keterangan_penerimaan, pen.keterangan_penagihan, pen.ket_trunojoyo, pen.check_diserahkan_admin, pen.check_diterima_trunojoyo, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_kirim_trunojoyo = ? and pen.check_dikirim_trunojoyo = 1 and pen.check_diterima_trunojoyo = 0 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_terima_dsgm');
    }

    public function viewOndomohenTerimaOndomohenTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_ondomohen', 1)->where('check_diterima_ondomohen', 0)->whereBetween('tanggal_kirim_ondomohen', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_ondomohen')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_dikirim_ondomohen', 1)->where('check_diterima_ondomohen', 0)->groupBy('tanggal_kirim_ondomohen')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terima_ondomohen')->rawColumns(['action'])->make(true);
        }
        return view('tagih_terima_ondomohen');
    }   

    public function viewOndomohenTerimaOndomohenDetailTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname as customer, pen.keterangan, pen.ket_ondomohen, pen.check_dibayar_admin, pen.keterangan_penerimaan, pen.keterangan_penagihan, pen.check_diserahkan_admin, pen.check_diterima_ondomohen, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.tanggal_kirim_ondomohen = ? and pen.check_dikirim_ondomohen = 1 and pen.check_diterima_ondomohen = 0 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_terima_ondomohen');
    }

    public function viewPenagihanTerimaCekTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_dibayar_admin', DB::raw("count(distinct nosj) as jumlah_sj"), DB::raw("SUM(CASE WHEN check_diterima_cek = 0 THEN 1 ELSE 0 END) AS jml_cek"))->where('check_dibayar_admin', 1)->where('bayar_setelah_tt', 1)->where('check_diterima_cek', 0)->whereBetween('tanggal_dibayar_admin', array($request->from_date, $request->to_date))->groupBy('tanggal_dibayar_admin')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_dibayar_admin', DB::raw("count(distinct nosj) as jumlah_sj"), DB::raw("SUM(CASE WHEN check_diterima_cek = 0 THEN 1 ELSE 0 END) AS jml_cek"))->where('check_dibayar_admin', 1)->where('bayar_setelah_tt', 1)->where('check_diterima_cek', 0)->groupBy('tanggal_dibayar_admin')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terima_cek')->rawColumns(['action'])->make(true);
        }
        return view('tagih_terima_cek');
    }   

    public function viewPenagihanTerimaDetailCekTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname as customer, pen.tanggal_do, pen.keterangan_penagihan, pen.check_diterima_cek, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_dibayar_admin, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_dibayar_admin = ? and pen.check_dibayar_admin = 1 and pen.bayar_setelah_tt = 1 and pen.check_diterima_cek = 0 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_terima_cek');
    }

    public function viewDataPenagihanTerimaCek(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.keterangan_penagihan, pen.check_diterima_cek, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_terima_cek, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_terima_cek = ? and pen.tanggal_terima_cek is not null and pen.check_diterima_cek = 1 and pen.check_dikirim_cek_ondomohen = 0 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_terima_cek');
    }

    public function viewDataPenagihanKirimCekOndomohen(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.keterangan_kirim_cek_ondomohen, pen.tanggal_kirim_cek_ondomohen, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.check_dikirim_cek_ondomohen, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_kirim_cek_ondomohenm = ? and pen.check_dikirim_cek_ondomohen = 1 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_terima_cek');
    }

    public function printPenagihanTerimaCek($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.keterangan_penagihan, pen.check_diterima_cek, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_terima_cek, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_terima_cek = ? and pen.tanggal_terima_cek is not null and pen.check_diterima_cek = 1 and pen.check_dikirim_cek_ondomohen = 0 group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_packing_list_terima_cek', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewPenagihanTerimaCekOndomohenTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_cek_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"), DB::raw("SUM(CASE WHEN check_diterima_cek_ondomohen = 0 THEN 1 ELSE 0 END) AS jml_cek"))->where('status_jadwal', 13)->whereBetween('tanggal_kirim_cek_ondomohen', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_cek_ondomohen')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_cek_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"), DB::raw("SUM(CASE WHEN check_diterima_cek_ondomohen = 0 THEN 1 ELSE 0 END) AS jml_cek"))->where('status_jadwal', 13)->groupBy('tanggal_kirim_cek_ondomohen')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terima_cek_ondomohen')->rawColumns(['action'])->make(true);
        }
        return view('tagih_cashier_terima_cek');
    }   

    public function viewPenagihanTerimaDetailCekOndomohenTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, pen.check_dikirim_cek_ondomohen, pen.check_diterima_cek_ondomohen, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_kirim_cek_ondomohen, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_kirim_cek_ondomohen = ? and pen.status_jadwal = 13 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_cashier_terima_cek');
    }

    public function viewPenagihanTerimaDokumenPelunasanTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_kirim_dok_pelunasan', DB::raw("count(distinct nosj) as jumlah_sj"), DB::raw("SUM(CASE WHEN check_diterima_dok_pelunasan = 0 THEN 1 ELSE 0 END) AS jml_cek"))->where('status_jadwal', 15)->whereBetween('tanggal_kirim_dok_pelunasan', array($request->from_date, $request->to_date))->groupBy('tanggal_kirim_dok_pelunasan')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_kirim_dok_pelunasan', DB::raw("count(distinct nosj) as jumlah_sj"), DB::raw("SUM(CASE WHEN check_diterima_dok_pelunasan = 0 THEN 1 ELSE 0 END) AS jml_cek"))->where('status_jadwal', 15)->groupBy('tanggal_kirim_dok_pelunasan')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_terima_dokumen_pelunasan')->rawColumns(['action'])->make(true);
        }
        return view('tagih_terima_dokumen_pelunasan');
    }   

    public function viewPenagihanTerimaDetailDokumenPelunasanTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, pen.check_dikirim_dok_pelunasan, pen.check_diterima_dok_pelunasan, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_kirim_dok_pelunasan, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_kirim_dok_pelunasan = ? and pen.status_jadwal = 15 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_terima_dokumen_pelunasan');
    }

    public function updatePenagihanTerima(Request $request){
	    $nosj = $request->get('nosj');
	    $diterima = $request->get('diterima');

	    date_default_timezone_set('Asia/Jakarta');
	    foreach($nosj as $nomor) {
	    	if(is_array($diterima) && array_key_exists($nomor,$diterima)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_diterima_trunojoyo' => 1, 'tanggal_terima_trunojoyo' => date('Y-m-d'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 2, 'action' => ' Trunojoyo Update Checklist Terima Data dari DSGM']);
	    	}
	    }

	    return Response()->json();
	}

    public function updateOndomohenTerimaOndomohen(Request $request){
        $nosj = $request->get('nosj');
        $diterima = $request->get('diterima');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if(is_array($diterima) && array_key_exists($nomor,$diterima)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_diterima_ondomohen' => 1, 'tanggal_terima_ondomohen' => date('Y-m-d'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 2, 'action' => 'Ondomohen Update Checklist Terima Dokumen List dari DSGM']);
            }
        }

        return Response()->json();
    }

    public function updatePenagihanTerimaCek(Request $request){
        $nosj = $request->get('nosj');
        $diterima = $request->get('diterima');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if(is_array($diterima) && array_key_exists($nomor,$diterima)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_diterima_cek' => 1, 'tanggal_terima_cek' => date('Y-m-d'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 12, 'action' => 'Update Checklist Terima Cek / Giro dari DSGM']);
            }
        }

        return Response()->json();
    }

    public function updatePenagihanTerimaCekOndomohen(Request $request){
        $nosj = $request->get('nosj');
        $diterima = $request->get('diterima');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if(is_array($diterima) && array_key_exists($nomor,$diterima)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_diterima_cek_ondomohen' => 1, 'tanggal_terima_cek_ondomohen' => date('Y-m-d'), 'status_jadwal' => 14, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 14, 'action' => 'Update Checklist Terima Cek / Giro dari Trunojoyo']);
            }
        }

        return Response()->json();
    }

    public function updatePenagihanTerimaDokumenPelunasan(Request $request){
        $nosj = $request->get('nosj');
        $diterima = $request->get('diterima');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if(is_array($diterima) && array_key_exists($nomor,$diterima)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_diterima_dok_pelunasan' => 1, 'tanggal_terima_dok_pelunasan' => date('Y-m-d'), 'status_jadwal' => 16, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 16, 'action' => 'Update Checklist Terima Dokumen Pelunasan dari Trunojoyo']);
            }
        }

        return Response()->json();
    }

	public function viewAdminTrunojoyoRencanaPenyerahanTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_diterima_trunojoyo', 1)->where('check_diserahkan_admin', 0)->whereNull('tanggal_jadwal_penyerahan')->whereBetween('tanggal_do', array($request->from_date, $request->to_date))->groupBy('tanggal_do')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_diterima_trunojoyo', 1)->where('check_diserahkan_admin', 0)->whereNull('tanggal_jadwal_penyerahan')->groupBy('tanggal_do')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_rencana_penyerahan')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen');
    }   

    public function viewAdminTrunojoyoPenyerahanFinalTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_jadwal_penyerahan', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_jadwal_penyerahan')->whereNull('tanggal_terima_dokumen_cust')->whereBetween('tanggal_jadwal_penyerahan', array($request->from_date, $request->to_date))->groupBy('tanggal_jadwal_penyerahan')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_jadwal_penyerahan', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_jadwal_penyerahan')->whereNull('tanggal_terima_dokumen_cust')->groupBy('tanggal_jadwal_penyerahan')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_penyerahan_final')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen');
    }   

    public function viewAdminTrunojoyoPenyerahanSelesaiTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_terima_dokumen_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_terima_dokumen_cust')->whereBetween('tanggal_terima_dokumen_cust', array($request->from_date, $request->to_date))->groupBy('tanggal_terima_dokumen_cust')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_terima_dokumen_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_terima_dokumen_cust')->groupBy('tanggal_terima_dokumen_cust')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_penyerahan_selesai')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen');
    }

    public function viewDataAdminTrunojoyoRencanaPenyerahan(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, cus.address as alamat, pen.keterangan, pen.check_diterima_trunojoyo, pen.check_diserahkan_admin, pen.ket_trunojoyo, pen.check_dibayar_admin, pen.keterangan_penerimaan, pen.keterangan_penagihan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_do = ? and pen.check_diterima_trunojoyo = 1 and pen.check_diserahkan_admin = 0 and pen.tanggal_jadwal_penyerahan is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen');
    }

    public function printAdminTrunojoyoRencanaPenyerahan($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custid, pen.custname, pen.tanggal_do, cus.address as alamat, pen.keterangan, pen.check_diterima_trunojoyo, pen.check_diserahkan_admin, pen.ket_trunojoyo, pen.check_dibayar_admin, pen.keterangan_penerimaan, pen.keterangan_penagihan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_do = ? and pen.check_diterima_trunojoyo = 1 and pen.check_diserahkan_admin = 0 and pen.tanggal_jadwal_penyerahan is null group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_rencana_jadwal_penyerahan_dok', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewPenagihanRencanaPenyerahanCollectorTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_diterima_trunojoyo', 1)->where('check_diserahkan_admin', 0)->whereNull('tanggal_jadwal_penyerahan')->whereBetween('tanggal_do', array($request->from_date, $request->to_date))->groupBy('tanggal_do')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_diterima_trunojoyo', 1)->where('check_diserahkan_admin', 0)->whereNull('tanggal_jadwal_penyerahan')->groupBy('tanggal_do')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_rencana_penyerahan_collector')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen_collector');
    }   

    public function viewPenagihanPenyerahanFinalCollectorTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_jadwal_penyerahan', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_jadwal_penyerahan')->whereNull('tanggal_terima_dokumen_cust')->whereBetween('tanggal_jadwal_penyerahan', array($request->from_date, $request->to_date))->groupBy('tanggal_jadwal_penyerahan')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_jadwal_penyerahan', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_jadwal_penyerahan')->whereNull('tanggal_terima_dokumen_cust')->groupBy('tanggal_jadwal_penyerahan')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_penyerahan_final_collector')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen_collector');
    }

    public function viewPenagihanRencanaPenyerahanSortingTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname as customer, pen.tanggal_do, cus.address as alamat, pen.keterangan, pen.tanggal_jadwal_penyerahan, pen.check_diserahkan_admin, pen.ket_trunojoyo, pen.check_dibayar_admin, pen.keterangan_penerimaan, pen.keterangan_penagihan, pen.keterangan_jadwal_penyerahan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_do = ? and pen.check_diterima_trunojoyo = 1 and pen.check_diserahkan_admin = 0 and pen.tanggal_jadwal_penyerahan is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen_collector');
    }

    public function viewPenagihanEditPenyerahanFinalTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname as customer, pen.tanggal_do, cus.address as alamat, pen.keterangan, pen.tanggal_jadwal_penyerahan, pen.check_diserahkan_admin, pen.ket_trunojoyo, pen.check_dibayar_admin, pen.keterangan_penerimaan, pen.keterangan_penagihan, pen.keterangan_jadwal_penyerahan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_jadwal_penyerahan = ? and pen.tanggal_jadwal_penyerahan is not null and pen.tanggal_terima_dokumen_cust is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen_collector');
    }

    public function savePenagihanRencanaPenyerahanJadwal(Request $request){
        $nosj = $request->get('nosj');
        $jadwal = $request->get('jadwal');
        $keterangan = $request->get('keterangan_jadwal');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if($jadwal[$nomor] != null || $jadwal[$nomor] != ''){
                DB::table('penagihan')->where('nosj', $nomor)->update(['tanggal_jadwal_penyerahan' => date('Y-m-d', strtotime($jadwal[$nomor])), 'keterangan_jadwal_penyerahan' => $keterangan[$nomor], 'status_jadwal' => 3, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
            
                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 3, 'action' => 'Update Tanggal Jadwal Penyerahan dari Collector']);
            }
        }

        return Response()->json();
    }

    public function editPenagihanPenyerahanFinalJadwal(Request $request){
        $nosj = $request->get('edit_nosj');
        $jadwal = $request->get('edit_jadwal');
        $keterangan = $request->get('edit_keterangan_jadwal');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if($jadwal[$nomor] != null || $jadwal[$nomor] != ''){
                DB::table('penagihan')->where('nosj', $nomor)->update(['tanggal_jadwal_penyerahan' => date('Y-m-d', strtotime($jadwal[$nomor])), 'keterangan_jadwal_penyerahan' => $keterangan[$nomor], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
            
                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 3, 'action' => 'Edit Tanggal Jadwal Penyerahan dari Collector']);
            }
        }

        return Response()->json();
    }

    public function viewDataAdminTrunojoyoPenyerahanFinal(Request $request){
       if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, cus.address as alamat, pen.tanggal_jadwal_penyerahan, pen.keterangan_jadwal_penyerahan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_jadwal_penyerahan = ? and pen.tanggal_terima_dokumen_cust is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen');
    }

    public function printAdminTrunojoyoPenyerahanFinal($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.custid, cus.address as alamat, pen.tanggal_jadwal_penyerahan, pen.keterangan_jadwal_penyerahan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_jadwal_penyerahan = ? and pen.tanggal_terima_dokumen_cust is null group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_jadwal_penyerahan_final', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewPenagihanPenyerahanFinalUpdateTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, cus.address as alamat, pen.tanggal_terima_dokumen_cust, pen.keterangan_penerimaan, pen.keterangan_jadwal_penyerahan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_jadwal_penyerahan = ? and pen.tanggal_jadwal_penyerahan is not null and pen.tanggal_terima_dokumen_cust is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen_collector');
    }

    public function savePenagihanPenyerahanFinal(Request $request){
        $nosj = $request->get('nosj_update');
        $tanggal = $request->get('terima_dokumen');
        $keterangan = $request->get('keterangan_penerimaan');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if($tanggal[$nomor] != null || $tanggal[$nomor] != ''){
                DB::table('penagihan')->where('nosj', $nomor)->update(['tanggal_terima_dokumen_cust' => date('Y-m-d', strtotime($tanggal[$nomor])), 'keterangan_penerimaan' => $keterangan[$nomor], 'status_jadwal' => 4, 'status_hadir' => 1, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
            
                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 4, 'action' => 'Penyerahan Data ke Customer Selesai, Entry Tanggal Penyerahan Dokumen, dan Keterangan Penerimaan']);
            }
        }

        return Response()->json();
    }

    public function viewDataAdminTrunojoyoPenyerahanSelesai(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, cus.address as alamat, pen.check_diserahkan_admin, pen.tanggal_terima_dokumen_cust, pen.keterangan_penerimaan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_terima_dokumen_cust = ? and pen.tanggal_terima_dokumen_cust is not null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen');
    }

    public function printAdminTrunojoyoPenyerahanSelesai($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custid, pen.custname, cus.address as alamat, pen.check_diserahkan_admin, pen.tanggal_terima_dokumen_cust, pen.keterangan_penerimaan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_terima_dokumen_cust = ? and pen.tanggal_terima_dokumen_cust is not null group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_jadwal_penyerahan_selesai', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewAdminTrunojoyoRencanaPenagihanTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_terima_dokumen_cust')->whereNull('tanggal_jadwal_penagihan')->whereNull('tanggal_tagih_cust')->whereBetween('tanggal_do', array($request->from_date, $request->to_date))->groupBy('tanggal_do')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_terima_dokumen_cust')->whereNull('tanggal_jadwal_penagihan')->whereNull('tanggal_tagih_cust')->groupBy('tanggal_do')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_rencana_penagihan')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penagihan');
    }   

    public function viewAdminTrunojoyoPenagihanFinalTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_jadwal_penagihan', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_jadwal_penagihan')->whereNull('tanggal_tagih_cust')->whereBetween('tanggal_jadwal_penagihan', array($request->from_date, $request->to_date))->groupBy('tanggal_jadwal_penagihan')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_jadwal_penagihan', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_jadwal_penagihan')->whereNull('tanggal_tagih_cust')->groupBy('tanggal_jadwal_penagihan')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_final')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penyerahan_dokumen');
    }   

    public function viewAdminTrunojoyoPenagihanSelesaiTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_tagih_cust')->whereBetween('tanggal_tagih_cust', array($request->from_date, $request->to_date))->groupBy('tanggal_tagih_cust')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_tagih_cust')->groupBy('tanggal_tagih_cust')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_selesai')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penagihan');
    }   

    public function viewRencanaPenagihanCollectorTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_terima_dokumen_cust')->whereNull('tanggal_jadwal_penagihan')->whereBetween('tanggal_do', array($request->from_date, $request->to_date))->groupBy('tanggal_do')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_do', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_terima_dokumen_cust')->whereNull('tanggal_jadwal_penagihan')->groupBy('tanggal_do')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_rencana_penagihan_collector')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penagihan_collector');
    }  

    public function viewPenagihanFinalCollectorTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_jadwal_penagihan', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_jadwal_penagihan')->whereNull('tanggal_tagih_cust')->whereBetween('tanggal_jadwal_penagihan', array($request->from_date, $request->to_date))->groupBy('tanggal_jadwal_penagihan')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_jadwal_penagihan', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_jadwal_penagihan')->whereNull('tanggal_tagih_cust')->groupBy('tanggal_jadwal_penagihan')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_final_collector')->rawColumns(['action'])->make(true);
        }
        return view('tagih_jadwal_penagihan_collector');
    } 

    public function viewDataAdminTrunojoyoRencanaPenagihan(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, cus.address as alamat, pen.hitung_suspend, pen.tanggal_jadwal_penagihan, pen.keterangan_penerimaan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_do = ? and pen.tanggal_terima_dokumen_cust is not null and pen.tanggal_jadwal_penagihan is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penagihan');
    }   

    public function printAdminTrunojoyoRencanaPenagihan($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.custid, pen.tanggal_do, cus.address as alamat, pen.hitung_suspend, pen.keterangan_penerimaan, pen.tanggal_jadwal_penagihan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_do = ? and pen.tanggal_terima_dokumen_cust is not null and pen.tanggal_jadwal_penagihan is null group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_rencana_jadwal_penagihan', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }   

    public function viewRencanaPenagihanSortingTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname as customer, pen.tanggal_do, cus.address as alamat, pen.tanggal_jadwal_penagihan, pen.keterangan_jadwal_penagihan, pen.keterangan_penerimaan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_do = ? and pen.tanggal_terima_dokumen_cust is not null and pen.tanggal_jadwal_penagihan is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_jadwal_penagihan_collector');
    }

    public function viewPenagihanFinalEditTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname as customer, pen.tanggal_do, cus.address as alamat, pen.tanggal_jadwal_penagihan, pen.keterangan_jadwal_penagihan, pen.keterangan_penerimaan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_jadwal_penagihan = ? and pen.tanggal_jadwal_penagihan is not null and pen.tanggal_tagih_cust is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_jadwal_penagihan_collector');
    }

    public function saveRencanaPenagihanJadwal(Request $request){
        $nosj = $request->get('nosj');
        $jadwal = $request->get('jadwal');
        $keterangan = $request->get('keterangan_jadwal');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if($jadwal[$nomor] != null || $jadwal[$nomor] != ''){
                DB::table('penagihan')->where('nosj', $nomor)->update(['tanggal_jadwal_penagihan' => date('Y-m-d', strtotime($jadwal[$nomor])), 'keterangan_jadwal_penagihan' => $keterangan[$nomor], 'status_jadwal' => 6, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
            
                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 6, 'action' => 'Update Tanggal Jadwal Penagihan dari Collector']);
            }
        }

        return Response()->json();
    }

    public function editPenagihanFinal(Request $request){
        $nosj = $request->get('edit_nosj');
        $jadwal = $request->get('edit_jadwal');
        $keterangan = $request->get('edit_keterangan_jadwal');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if($jadwal[$nomor] != null || $jadwal[$nomor] != ''){
                DB::table('penagihan')->where('nosj', $nomor)->update(['tanggal_jadwal_penagihan' => date('Y-m-d', strtotime($jadwal[$nomor])), 'keterangan_jadwal_penagihan' => $keterangan[$nomor], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
            
                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 6, 'action' => 'Edit Tanggal Jadwal Penagihan oleh Collector']);
            }
        }

        return Response()->json();
    }

    public function viewDataAdminTrunojoyoPenagihanFinal(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, cus.address as alamat, pen.tanggal_jadwal_penagihan, pen.keterangan_jadwal_penagihan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_jadwal_penagihan = ? and pen.tanggal_jadwal_penagihan is not null and pen.tanggal_tagih_cust is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penagihan');
    }

    public function printAdminTrunojoyoPenagihanFinal($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.custid, pen.tanggal_do, cus.address as alamat, pen.tanggal_jadwal_penagihan, pen.keterangan_jadwal_penagihan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_jadwal_penagihan = ? and pen.tanggal_jadwal_penagihan is not null and pen.tanggal_tagih_cust is null group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_jadwal_penagihan_final', ['data' => $data])->setPaper('letter', 'portrait')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewPenagihanFinalUpdateTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname as customer, cus.address as alamat, pen.keterangan_penagihan, pen.check_dibayar, pen.metode_pembayaran, pen.nomor_metode_pembayaran, pen.tanggal_tagih_cust, pen.hitung_suspend, pen.keterangan_jadwal_penagihan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_jadwal_penagihan = ? and pen.tanggal_jadwal_penagihan is not null and pen.tanggal_tagih_cust is null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penagihan_collector');
    }

    public function savePenagihanFinal(Request $request){
        $nosj = $request->get('nosj_update');
        $dibayar = $request->get('dibayar');
        $tanggal = $request->get('tagih_cust');
        $metode = $request->get('metode');
        $nomor_metode = $request->get('nomor');
        $nominal = $request->get('nominal');
        $keterangan = $request->get('keterangan_penagihan');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if(is_array($dibayar) && array_key_exists($nomor,$dibayar)){
                $date1 = strtr($tanggal[$nomor], '/', '-');
                DB::table('penagihan')->where('nosj', $nomor)->update(['check_dibayar' => 1, 'tanggal_tagih_cust' => date('Y-m-d', strtotime($date1)), 'metode_pembayaran' => $metode[$nomor], 'nomor_metode_pembayaran' => $nomor_metode[$nomor], 'nominal_bayar' => $nominal[$nomor], 'keterangan_penagihan' => $keterangan[$nomor], 'status_jadwal' => 7, 'status_hadir' => 2, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);
            
                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 7, 'action' => 'Penagihan ke Customer Selesai, Entry Tanggal Tagih, Metode Pembayaran, Nomor Metode Pembayaran, dan Keterangan Penagihan']);
            }
        }

        return Response()->json();
    }

    public function viewDataAdminTrunojoyoPenagihanSelesai(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, cus.address as alamat, pen.check_diserahkan_admin, pen.tanggal_tagih_cust, met.name as nama_metode_pembayaran, pen.keterangan_penagihan, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_tagih_cust = ? and pen.tanggal_tagih_cust is not null group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('tagih_jadwal_penagihan');
    }

    public function printAdminTrunojoyoPenagihanSelesai($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.custid, cus.address as alamat, pen.check_diserahkan_admin, pen.tanggal_tagih_cust, met.name as metode, pen.keterangan_penagihan, pen.nomor_metode_pembayaran, pen.nominal_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_tagih_cust = ? and pen.tanggal_tagih_cust is not null group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_jadwal_penagihan_selesai', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewAgingScheduleTable(){
        $penagihan = ModelPenagihan::select('nosj', 'custid', 'custname', DB::raw("DATEDIFF(CURDATE(), tanggal_jatuh_tempo) as telat_hari"))->whereNotNull('tanggal_jatuh_tempo')->where('status_jadwal', '!=', 8)->whereRaw("DATEDIFF(CURDATE(), tanggal_jatuh_tempo) > 7")->get();

        return datatables()->of($penagihan)->addIndexColumn()->make(true);
    } 

    public function viewOverduePaidTable(){
        $penagihan = ModelPenagihan::select('nosj', 'custid', 'custname', DB::raw("DATEDIFF(CURDATE(), tanggal_jatuh_tempo) as telat_hari"))->whereNotNull('tanggal_jatuh_tempo')->whereRaw("DATEDIFF(CURDATE(), tanggal_jatuh_tempo) > 7")->where('status_jadwal', 8)->get();

        return datatables()->of($penagihan)->addIndexColumn()->make(true);
    } 

    public function viewBadCustomersTable(){
        $penagihan = DB::table('customers as cus')->select('cus.custid', 'cus.custname', DB::raw("AVG(DATEDIFF(CURDATE(), tanggal_jatuh_tempo)) as rata_hari"))->join('penagihan as pen', 'pen.custid', '=', 'cus.custid')->groupBy('cus.custid')->havingRaw("AVG(DATEDIFF(CURDATE(), tanggal_jatuh_tempo)) > 30")->get();

        return datatables()->of($penagihan)->addIndexColumn()->make(true);
    } 

    public function viewPembayaranTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->where(function ($query) { $query->where('status_jadwal', 7)->orWhere('status_jadwal', 10); })->where('metode_pembayaran', 1)->whereBetween('tanggal_tagih_cust', array($request->from_date, $request->to_date))->groupBy('tanggal_tagih_cust')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->where(function ($query) { $query->where('status_jadwal', 7)->orWhere('status_jadwal', 10); })->where('metode_pembayaran', 1)->whereRaw('MONTH(tanggal_tagih_cust) = ?',[$currentMonth])->whereRaw('YEAR(tanggal_tagih_cust) = ?',[$currentYear])->groupBy('tanggal_tagih_cust')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_pembayaran')->rawColumns(['action'])->make(true);
        }
        return view('pembayaran');
    } 

    public function viewPembayaranDetailTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_tagih_cust, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.tanggal_bayar_cashier, pen.total_bayar as total, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan, ((coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) - pen.total_bayar) as sisa from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_tagih_cust = ? and pen.metode_pembayaran = 1 and (pen.status_jadwal = 7 or pen.status_jadwal = 10) group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('pembayaran');
    }

    public function savePembayaran(Request $request){
        $nosj = $request->get('nosj');
        $tanggal = $request->get('bayar');
        $nominal = $request->get('nominal');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if($tanggal[$nomor] != null || $tanggal[$nomor] != ''){
                $data = DB::select("select pen.total_bayar, pen.hitung_bayar, pen.custid, pen.metode_pembayaran, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.nosj = ?", [$nomor]);

                if($data[0]->hitung_bayar == null || $data[0]->hitung_bayar == ''){
                    $hitung = 1;
                }else{
                    $hitung = ++$data[0]->hitung_bayar;
                }

                $total = $data[0]->total_bayar + $nominal[$nomor];

                if($data[0]->tagihan > $total){
                    DB::table('penagihan')->where('nosj', $nomor)->update(['check_dibayar' => 0, 'tanggal_bayar_cashier' => $tanggal[$nomor], 'nominal_bayar_cashier' => $nominal[$nomor], 'total_bayar' => $total, 'hitung_bayar' => $hitung, 'status_jadwal' => 4, 'status_hadir' => 1, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                    DB::table('payment_receive')->insert(['nosj' => $nomor, 'custid' => $data[0]->custid, 'tanggal_bayar' => $tanggal[$nomor], 'metode_pembayaran' => $data[0]->metode_pembayaran, 'nominal_bayar' => $nominal[$nomor]]);

                    DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 4, 'action' => 'Pembayaran Diterima oleh Cashier. Status Tagihan PARTIAL, akan dilakukan penagihan selanjutnya']);
                }else{
                    DB::table('penagihan')->where('nosj', $nomor)->update(['tanggal_bayar_cashier' => $tanggal[$nomor], 'nominal_bayar_cashier' => $nominal[$nomor], 'total_bayar' => $total, 'hitung_bayar' => $hitung, 'status_jadwal' => 8, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                    DB::table('payment_receive')->insert(['nosj' => $nomor, 'custid' => $data[0]->custid, 'tanggal_bayar' => $tanggal[$nomor], 'metode_pembayaran' => $data[0]->metode_pembayaran, 'nominal_bayar' => $nominal[$nomor]]);

                    DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 8, 'action' => 'Pembayaran Diterima oleh Cashier.']);
                }
            }
        }

        return Response()->json();
    }

    public function viewListBelumBayarTable(){
        $penagihan = ModelPenagihan::select('nosj', 'custid', 'custname', 'tanggal_terima_dokumen_cust', 'tanggal_tagih_cust')->where('status_jadwal', '!=', 8)->where('hitung_bayar', 0)->groupBy('nosj')->get();

        return datatables()->of($penagihan)->addIndexColumn()->make(true);
    } 

    public function viewListBelumLunasTable(){
        $penagihan = DB::select("select pen.nosj, pen.custname, pen.custid, pen.tanggal_bayar_cashier, pen.hitung_bayar, ((coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) - pen.total_bayar) as sisa from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.status_jadwal != 8 and pen.hitung_bayar != 0 group by pen.nosj");

        return datatables()->of($penagihan)->addIndexColumn()->make(true);
    } 

    public function viewListSudahLunasTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_pelunasan', 'nosj', 'noinv', 'noar', 'custname', 'nomor_referensi', 'metode_pembayaran', 'met.name as nama_metode_pembayaran', 'nomor_metode_pembayaran', 'nominal_bayar', 'keterangan_pelunasan')->leftJoin('tbl_metode_pembayaran as met', 'met.id', '=', 'penagihan.metode_pembayaran')->whereNotNull('tanggal_pelunasan')->where('lunas', 1)->whereBetween('tanggal_pelunasan', array($request->from_date, $request->to_date))->groupBy('nosj')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_pelunasan', 'nosj', 'noinv', 'custname', 'nomor_referensi', 'metode_pembayaran', 'met.name as nama_metode_pembayaran', 'nomor_metode_pembayaran', 'nominal_bayar', 'keterangan_pelunasan')->leftJoin('tbl_metode_pembayaran as met', 'met.id', '=', 'penagihan.metode_pembayaran')->whereNotNull('tanggal_pelunasan')->where('lunas', 1)->groupBy('nosj')->get();
            }

            return datatables()->of($penagihan)->make(true);
        }
        return view('pembayaran');
    } 

    public function printDataARPelunasan($noar){
        $val_noar = Crypt::decrypt($noar);

        $data = DB::table('pelunasan')->select('noar', 'tanggal', 'keterangan', 'total_nominal', 'user_type')->where('noar', $val_noar)->first();

        $pdf = PDF::loadView('print_form_lunas', ['data' => $data])->setPaper([0, 0, 396.85, 609.44], 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewListPaymentReceiveTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = DB::table('payment_receive as pay')->select('nosj', 'pay.custid', 'cus.custname', 'tanggal_bayar', 'metode_pembayaran', 'met.name as nama_metode_pembayaran', 'nomor_metode_pembayaran', 'nominal_bayar')->join('customers as cus', 'cus.custid', '=', 'pay.custid')->join('tbl_metode_pembayaran as met', 'met.id', '=', 'pay.metode_pembayaran')->where('metode_pembayaran', 1)->whereBetween('tanggal_bayar', array($request->from_date, $request->to_date))->get();
            }else{
                $penagihan = DB::table('payment_receive as pay')->select('nosj', 'pay.custid', 'cus.custname', 'tanggal_bayar', 'metode_pembayaran', 'met.name as nama_metode_pembayaran', 'nomor_metode_pembayaran', 'nominal_bayar')->join('customers as cus', 'cus.custid', '=', 'pay.custid')->join('tbl_metode_pembayaran as met', 'met.id', '=', 'pay.metode_pembayaran')->where('metode_pembayaran', 1)->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('pembayaran');
    }

    public function viewPembayaranOndomohenTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 14)->where('metode_pembayaran', '!=', 1)->whereBetween('tanggal_tagih_cust', array($request->from_date, $request->to_date))->groupBy('tanggal_tagih_cust')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->where('status_jadwal', 14)->where('metode_pembayaran', '!=', 1)->whereRaw('MONTH(tanggal_tagih_cust) = ?',[$currentMonth])->whereRaw('YEAR(tanggal_tagih_cust) = ?',[$currentYear])->groupBy('tanggal_tagih_cust')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_pembayaran')->rawColumns(['action'])->make(true);
        }
        return view('pembayaran_ondomohen');
    } 

    public function viewPembayaranDetailOndomohenTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.custname as customer, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_tagih_cust, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.tanggal_bayar_cashier, pen.total_bayar as total, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan, ((coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) - pen.total_bayar) as sisa from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_tagih_cust = ? and pen.metode_pembayaran != 1 and pen.status_jadwal = 14 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('pembayaran_ondomohen');
    }

    public function viewListPaymentReceiveOndomohenTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = DB::table('payment_receive as pay')->select('nosj', 'pay.custid', 'cus.custname', 'tanggal_bayar', 'metode_pembayaran', 'met.name as nama_metode_pembayaran', 'nomor_metode_pembayaran', 'nominal_bayar')->join('customers as cus', 'cus.custid', '=', 'pay.custid')->join('tbl_metode_pembayaran as met', 'met.id', '=', 'pay.metode_pembayaran')->where('metode_pembayaran', '!=', 1)->whereBetween('tanggal_bayar', array($request->from_date, $request->to_date))->get();
            }else{
                $penagihan = DB::table('payment_receive as pay')->select('nosj', 'pay.custid', 'cus.custname', 'tanggal_bayar', 'metode_pembayaran', 'met.name as nama_metode_pembayaran', 'nomor_metode_pembayaran', 'nominal_bayar')->join('customers as cus', 'cus.custid', '=', 'pay.custid')->join('tbl_metode_pembayaran as met', 'met.id', '=', 'pay.metode_pembayaran')->where('metode_pembayaran', '!=', 1)->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->make(true);
        }
        return view('pembayaran');
    }

    public function savePembayaranOndomohen(Request $request){
        $nosj = $request->get('nosj');
        $tanggal = $request->get('bayar');
        $nomor_metode = $request->get('nomor');
        $nominal = $request->get('nominal');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if($tanggal[$nomor] != null || $tanggal[$nomor] != ''){
                $data = DB::select("select pen.total_bayar, pen.hitung_bayar, pen.custid, pen.metode_pembayaran, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.nosj = ?", [$nomor]);

                if($data[0]->hitung_bayar == null || $data[0]->hitung_bayar == ''){
                    $hitung = 1;
                }else{
                    $hitung = ++$data[0]->hitung_bayar;
                }

                $total = $data[0]->total_bayar + $nominal[$nomor];

                if($data[0]->tagihan > $total){
                    DB::table('penagihan')->where('nosj', $nomor)->update(['check_dibayar' => 0, 'tanggal_bayar_cashier' => $tanggal[$nomor], 'nomor_metode_pembayaran_cashier' => $nomor_metode[$nomor], 'nominal_bayar_cashier' => $nominal[$nomor], 'total_bayar' => $total, 'check_dikirim_cek' => 0, 'check_diterima_cek' => 0, 'tanggal_kirim_cek' => NULL, 'tanggal_terima_cek' => NULL, 'check_dikirim_cek_ondomohen' => 0, 'check_diterima_cek_ondomohen' => 0, 'tanggal_kirim_cek_ondomohen' => NULL, 'tanggal_terima_cek_ondomohen' => NULL, 'hitung_bayar' => $hitung, 'status_jadwal' => 4, 'status_hadir' => 1, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                    DB::table('payment_receive')->insert(['nosj' => $nomor, 'custid' => $data[0]->custid, 'tanggal_bayar' => $tanggal[$nomor], 'metode_pembayaran' => $data[0]->metode_pembayaran, 'nomor_metode_pembayaran' => $nomor_metode[$nomor], 'nominal_bayar' => $nominal[$nomor]]);

                    DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 4, 'action' => 'Pembayaran Diterima oleh Cashier. Status Tagihan PARTIAL, akan dilakukan penagihan selanjutnya']);
                }else{
                    DB::table('penagihan')->where('nosj', $nomor)->update(['tanggal_bayar_cashier' => $tanggal[$nomor], 'nomor_metode_pembayaran_cashier' => $nomor_metode[$nomor], 'nominal_bayar_cashier' => $nominal[$nomor], 'total_bayar' => $total, 'hitung_bayar' => $hitung, 'status_jadwal' => 8, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                    DB::table('payment_receive')->insert(['nosj' => $nomor, 'custid' => $data[0]->custid, 'tanggal_bayar' => $tanggal[$nomor], 'metode_pembayaran' => $data[0]->metode_pembayaran, 'nomor_metode_pembayaran' => $nomor_metode[$nomor], 'nominal_bayar' => $nominal[$nomor]]);

                    DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 8, 'action' => 'Pembayaran Diterima oleh Cashier.']);
                }
            }
        }

        return Response()->json();
    } 

    public function viewNoARPelunasanTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = DB::table('pelunasan')->select('noar', 'tanggal', 'total_nominal', 'user_type')->whereBetween('tanggal', array($request->from_date, $request->to_date))->get();
            }else{
                $penagihan = DB::table('pelunasan')->select('noar', 'tanggal', 'total_nominal', 'user_type')->get();
            }

            return datatables()->of($penagihan)->addColumn('action', 'button/action_button_penagihan_noar_pelunasan')->rawColumns(['action'])->make(true);
        }
        return view('pelunasan');
    }   

    public function cekPelunasanData($nosj){
        $val_nosj = $this->decrypt($nosj);

        $penagihan = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname as customer, pen.metode_pembayaran, pen.nomor_metode_pembayaran, pen.nominal_bayar, pen.ket_ondomohen, pen.keterangan_penagihan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.nosj = ? group by pen.nosj", [$val_nosj]);

        return Response()->json($penagihan);
    }   

    public function viewInvoicePelunasanTable(Request $request){
        
        $penagihan = DB::table('temp_no_invoice')->select('noinv', 'customers', 'nominal', 'other', 'acc_other')->where('id_user', Session::get('id_user_admin'))->get();

        return datatables()->of($penagihan)->addColumn('action', 'button/action_button_penagihan_add_invoice')->rawColumns(['action'])->make(true);
    }

    public function viewDataPelunasan($noar){
        $val_noar = $this->decrypt($noar);
        
        $pelunasan = DB::table('pelunasan')->select('noar', 'no_referensi', 'total_nominal', 'keterangan', 'tanggal')->where('noar', $val_noar)->first();

        return Response()->json($pelunasan);
    }

    public function viewInvoicePelunasan($noar){
        $val_noar = $this->decrypt($noar);
        
        $pelunasan = DB::table('pelunasan_detail')->select('noinv', 'noar', 'custid', 'custname', 'nominal', 'other', 'acc_other')->where('noar', $val_noar)->get();

        return Response()->json($pelunasan);
    }

    public function viewDetailInvoicePelunasan($noinv){
        $val_noinv = $this->decrypt($noinv);

        $val_noinv = strval($val_noinv);

        $penagihan = DB::select("select pen.noinv, pen.custname as customer, pen.sisa_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.noinv = ? group by pen.nosj", [$val_noinv]);

        return Response()->json($penagihan);
    }

    public function inputInvoicePelunasan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $data_detail = DB::table('temp_no_invoice')->select('noinv', 'custid', 'customers', 'nominal', 'other', 'acc_other')->where('id_user', Session::get('id_user_admin'))->get();

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('pelunasan')->insert(['noar' => $request->get('nomor_ar'), 'tanggal' => $request->get('tanggal'), 'no_referensi' => $request->get('nomor_referensi'), 'keterangan' => $request->get('keterangan_pelunasan'), 'total_nominal' => $request->get('total_nominal'), 'user_type' => Session::get('tipe_user'), 'created_at' => date("Y-m-d H:i:s"), 'created_by' => Session::get('id_user_admin'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

        foreach($data_detail as $det){
            DB::table('pelunasan_detail')->insert(['noinv' => $det->noinv, 'noar' => $request->get('nomor_ar'), 'custid' => $det->custid, 'custname' => $det->customers, 'nominal' => $det->nominal, 'other' => $det->other, 'acc_other' => $det->acc_other]);

            $data_tagih = DB::select("select pen.total_bayar, pen.hitung_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.noinv = ?", [$det->noinv]);

            if($data_tagih[0]->hitung_bayar == null || $data_tagih[0]->hitung_bayar == ''){
                $hitung = 1;
            }else{
                $hitung = ++$data_tagih[0]->hitung_bayar;
            }

            $total = $data_tagih[0]->total_bayar + $det->nominal + $det->other;

            $sisa = $data_tagih[0]->tagihan - $total;

            if($data_tagih[0]->tagihan > $total){
                DB::table('penagihan')->where('noinv', 'like', '%' . $det->noinv . '%')->update(['nominal_bayar' => $det->nominal, 'total_bayar' => $total, 'sisa_bayar' => $sisa, 'hitung_bayar' => $hitung, 'tanggal_pelunasan' => $request->get('tanggal'), 'nomor_referensi' => $request->get('nomor_referensi'), 'keterangan_pelunasan' => $request->get('keterangan_pelunasan'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $det->noinv, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 17, 'action' => 'Update Data Tambahkan Nominal Pembayaran, Namun Belum Lunas oleh Ondomohen. Status Tagihan BELUM LUNAS']);
            }else{
                $tes = DB::table('penagihan')->where('noinv', 'like', '%' . $det->noinv . '%')->update(['lunas' => 1, 'nominal_bayar' => $det->nominal, 'total_bayar' => $total, 'sisa_bayar' => $sisa, 'hitung_bayar' => $hitung, 'tanggal_pelunasan' => $request->get('tanggal'), 'nomor_referensi' => $request->get('nomor_referensi'), 'keterangan_pelunasan' => $request->get('keterangan_pelunasan'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $det->noinv, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 17, 'action' => 'Update Data Lunas oleh Ondomohen. Status Tagihan LUNAS']);
            }
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        DB::table('temp_no_invoice')->where('id_user', Session::get('id_user_admin'))->delete();

        return Response()->json($arr);
    }

    public function addInvoicePelunasan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');

        $cek_data = DB::select("select pen.custid, pen.custname, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.noinv = ? group by pen.noinv", [$request->nomor_invoice]);

        if($request->other == null || $request->other == ''){
            $request->other = 0;
        }

        $data = DB::table('temp_no_invoice')->insert(['id_user' => Session::get('id_user_admin'), 'noinv' => $request->nomor_invoice, 'custid' => $cek_data[0]->custid, 'customers' => $cek_data[0]->custname, 'nominal' => $request->nominal, 'other' => $request->other, 'acc_other' => $request->acc_other]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteInvoicePelunasan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('temp_no_invoice')->where('noinv', $request->get('noinv'))->where('id_user', Session::get('id_user_admin'))->delete();

        if($data){
            $arr = array('msg' => 'Data Successfully Deleted', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function sumTotalInvoicePelunasan(Request $request){
        $data = DB::table('temp_no_invoice')->select(DB::raw('sum(nominal) as total'))->where('id_user', Session::get('id_user_admin'))->groupBy('id_user')->get();

        return Response()->json($data);
    }

    public function viewEditInvoicePelunasanTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::table('pelunasan_detail')->select('noinv', 'noar', 'custname', 'nominal', 'other', 'acc_other')->where('noar', $request->noar)->get();

            return datatables()->of($penagihan)->addColumn('action', 'button/action_button_penagihan_edit_invoice')->rawColumns(['action'])->make(true);
        }
    }

    public function editARPelunasan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');
        $data = DB::table('pelunasan')->where('noar', $request->get('nomor_ar_lama'))->update(['noar' => $request->get('nomor_ar'), 'tanggal' => $request->get('tanggal'), 'no_referensi' => $request->get('nomor_referensi'), 'keterangan' => $request->get('keterangan_pelunasan'), 'total_nominal' => $request->get('total_nominal'), 'user_type' => Session::get('tipe_user'), 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

        DB::table('pelunasan_detail')->where('noar', $request->get('nomor_ar_lama'))->update(['noar' => $request->get('nomor_ar')]);

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function deleteEditInvoicePelunasan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');

        $data_tagih = DB::select("select pen.nominal_bayar, pen.total_bayar, pen.sisa_bayar, pen.hitung_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.noinv = ?", [$request->get('noinv')]);

        $data_lunas = DB::table('pelunasan_detail')->select('nominal', 'other')->where('noinv', $request->get('noinv'))->where('noar', $request->get('noar'))->first();


        $hitung = --$data_tagih[0]->hitung_bayar;

        $total = $data_tagih[0]->total_bayar - $data_lunas->nominal - $data_lunas->other;

        $sisa = $data_tagih[0]->sisa_bayar + $data_lunas->nominal + $data_lunas->other;

        if($total > 0){
            DB::table('penagihan')->where('noinv', 'like', '%' . $request->get('noinv') . '%')->update(['lunas' => 0, 'nominal_bayar' => $total, 'total_bayar' => $total, 'sisa_bayar' => $sisa, 'hitung_bayar' => $hitung, 'nomor_referensi' => NULL, 'keterangan_pelunasan' => NULL, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

            DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $request->get('noinv'), 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 17, 'action' => 'Edit Data Lunas oleh Ondomohen. Status Tagihan BELUM LUNAS atau Pengurangan Nominal Pembayaran']);
        }else{
            DB::table('penagihan')->where('noinv', 'like', '%' . $request->get('noinv') . '%')->update(['lunas' => 0, 'nominal_bayar' => NULL, 'total_bayar' => 0, 'sisa_bayar' => 0, 'hitung_bayar' => NULL, 'tanggal_pelunasan' => NULL, 'nomor_referensi' => NULL, 'keterangan_pelunasan' => NULL, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

            DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $request->get('noinv'), 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 17, 'action' => 'Edit Data Lunas oleh Ondomohen. Status Tagihan BELUM LUNAS atau Pengurangan Nominal Pembayaran']);
        }

        $data = DB::table('pelunasan_detail')->where('noinv', $request->get('noinv'))->where('noar', $request->get('noar'))->delete();

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function editInvoicePelunasan(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        date_default_timezone_set('Asia/Jakarta');

        $cek_data = DB::select("select pen.custid, pen.custname, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.noinv = ? group by pen.noinv", [$request->edit_nomor_invoice]);

        $data = DB::table('pelunasan_detail')->insert(['noinv' => $request->edit_nomor_invoice, 'noar' => $request->edit_nomor_ar_inv, 'custid' => $cek_data[0]->custid, 'custname' => $cek_data[0]->custname, 'nominal' => $request->edit_nominal, 'other' => $request->edit_other, 'acc_other' => $request->edit_acc_other]);

        $data_tagih = DB::select("select pen.total_bayar, pen.hitung_bayar, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid where pen.noinv = ?", [$request->edit_nomor_invoice]);

        if($data_tagih[0]->hitung_bayar == null || $data_tagih[0]->hitung_bayar == ''){
            $hitung = 1;
        }else{
            $hitung = ++$data_tagih[0]->hitung_bayar;
        }

        $total = $data_tagih[0]->total_bayar + $request->edit_nominal + $request->edit_other;

        $sisa = $data_tagih[0]->tagihan - $total;

        if($cek_data[0]->tagihan > $total){
            DB::table('penagihan')->where('noinv', 'like', '%' . $request->edit_nomor_invoice . '%')->update(['nominal_bayar' => $request->edit_nominal, 'total_bayar' => $total, 'sisa_bayar' => $sisa, 'hitung_bayar' => $hitung, 'tanggal_pelunasan' => date('Y-m-d'), 'nomor_referensi' => $request->edit_nomor_referensi_inv, 'keterangan_pelunasan' => $request->edit_keterangan_pelunasan_inv, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

            DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $request->edit_nomor_invoice, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 17, 'action' => 'Update Data Tambahkan Nominal Pembayaran, Namun Belum Lunas oleh Ondomohen. Status Tagihan BELUM LUNAS']);
        }else{
            DB::table('penagihan')->where('noinv', 'like', '%' . $request->edit_nomor_invoice . '%')->update(['lunas' => 1, 'nominal_bayar' => $request->edit_nominal, 'total_bayar' => $total, 'sisa_bayar' => $sisa, 'hitung_bayar' => $hitung, 'tanggal_pelunasan' => date('Y-m-d'), 'nomor_referensi' => $request->edit_nomor_referensi_inv, 'keterangan_pelunasan' => $request->edit_keterangan_pelunasan_inv, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

            DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $request->edit_nomor_invoice, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 17, 'action' => 'Update Data Lunas oleh Ondomohen. Status Tagihan LUNAS']);
        }

        if($data){
            $arr = array('msg' => 'Data Successfully Added', 'status' => true);
        }

        return Response()->json($arr);
    }

    public function sumEditTotalInvoicePelunasan($noar){
        $val_noar = $this->decrypt($noar);

        $data = DB::table('pelunasan_detail')->select(DB::raw('sum(nominal) as total'))->where('noar', $val_noar)->groupBy('noar')->first();

        return Response()->json($data);
    }

    public function viewCashierPelunasanTable(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_tagih_cust')->whereNull('tanggal_pelunasan')->where('metode_pembayaran', 1)->whereBetween('tanggal_tagih_cust', array($request->from_date, $request->to_date))->groupBy('tanggal_tagih_cust')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_tagih_cust', DB::raw("count(distinct nosj) as jumlah_sj"))->whereNotNull('tanggal_tagih_cust')->whereNull('tanggal_pelunasan')->where('metode_pembayaran', 1)->groupBy('tanggal_tagih_cust')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_pelunasan')->rawColumns(['action'])->make(true);
        }
        return view('pelunasan');
    }   

    public function viewCashierPelunasanDetailTable(Request $request){
        if(request()->ajax()){
            $penagihan = DB::select("select pen.nosj, pen.noinv, pen.tanggal_do, pen.custname as customer, pen.lunas, pen.metode_pembayaran, met.name as nama_metode_pembayaran, pen.tanggal_tagih_cust, pen.nominal_bayar, pen.nomor_referensi, pen.ket_ondomohen, pen.keterangan_penagihan, pen.keterangan_pelunasan, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pen.tanggal_tagih_cust = ? and pen.tanggal_tagih_cust is not null and pen.tanggal_pelunasan is null and pen.metode_pembayaran = 1 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($penagihan)->make(true);
        }
        return view('pelunasan');
    }   

    public function updateCashierPelunasan(Request $request){
        $nosj = $request->get('nosj');
        $lunas = $request->get('lunas');
        $nomor_referensi = $request->get('nomor_referensi');
        $keterangan = $request->get('keterangan_pelunasan');

        date_default_timezone_set('Asia/Jakarta');
        foreach($nosj as $nomor) {
            if(is_array($lunas) && array_key_exists($nomor,$lunas)){
                DB::table('penagihan')->where('nosj', $nomor)->update(['lunas' => 1, 'tanggal_pelunasan' => date('Y-m-d'), 'nomor_referensi' => $nomor_referensi[$nomor], 'keterangan_pelunasan' => $keterangan[$nomor], 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $nomor, 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 17, 'action' => 'Update Data Lunas oleh Cashier DSGM. Status Tagihan LUNAS']);
            }
        }

        return Response()->json();
    }

    public function viewOndomohenDokumenListTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_terima_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_diterima_ondomohen', 1)->whereBetween('tanggal_terima_ondomohen', array($request->from_date, $request->to_date))->groupBy('tanggal_terima_ondomohen')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_terima_ondomohen', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_diterima_ondomohen', 1)->whereRaw('MONTH(tanggal_terima_ondomohen) = ?',[$currentMonth])->whereRaw('YEAR(tanggal_terima_ondomohen) = ?',[$currentYear])->groupBy('tanggal_terima_ondomohen')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_dokumen_list')->rawColumns(['action'])->make(true);
        }
        return view('tagih_terima_ondomohen');
    }  

    public function printOndomohenDokumenList($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.custid, cus.address as alamat, pen.check_dibayar_admin, pen.check_diserahkan_admin, pen.keterangan, pen.ket_ondomohen, pen.keterangan_penerimaan, pen.keterangan_penagihan, pen.tanggal_terima_ondomohen, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_terima_ondomohen = ? and pen.check_diterima_ondomohen = 1 group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_dokumen_list', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewDataOndomohenDokumenList(Request $request){
        if(request()->ajax()){
            $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.custid, cus.address as alamat, pen.check_dibayar_admin, pen.check_diserahkan_admin, pen.keterangan, pen.ket_ondomohen, pen.keterangan_penerimaan, pen.keterangan_penagihan, pen.tanggal_terima_ondomohen, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_terima_ondomohen = ? and pen.check_diterima_ondomohen = 1 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($data)->addIndexColumn()->make(true);
        }

        return view('tagih_terima_ondomohen');
    }

    public function viewAdminTrunojoyoDokumenListTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = ModelPenagihan::select('tanggal_terima_trunojoyo', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_diterima_trunojoyo', 1)->whereBetween('tanggal_terima_trunojoyo', array($request->from_date, $request->to_date))->groupBy('tanggal_terima_trunojoyo')->get();
            }else{
                $penagihan = ModelPenagihan::select('tanggal_terima_trunojoyo', DB::raw("count(distinct nosj) as jumlah_sj"))->where('check_diterima_trunojoyo', 1)->whereRaw('MONTH(tanggal_terima_trunojoyo) = ?',[$currentMonth])->whereRaw('YEAR(tanggal_terima_trunojoyo) = ?',[$currentYear])->groupBy('tanggal_terima_trunojoyo')->get();
            }

            return datatables()->of($penagihan)->addIndexColumn()->addColumn('action', 'button/action_button_admin_trunojoyo_dokumen_list')->rawColumns(['action'])->make(true);
        }
        return view('tagih_terima_dsgm');
    }  

    public function printAdminTrunojoyoDokumenList($tanggal){
        $val_tanggal = Crypt::decrypt($tanggal);

        $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.custid, cus.address as alamat, pen.check_dibayar_admin, pen.check_diserahkan_admin, pen.keterangan, pen.ket_trunojoyo, pen.keterangan_penerimaan, pen.keterangan_penagihan, pen.tanggal_terima_trunojoyo, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_terima_trunojoyo = ? and pen.check_diterima_trunojoyo = 1 group by pen.nosj", [$val_tanggal]);

        $pdf = PDF::loadView('print_admin_trunojoyo_dokumen_list', ['data' => $data])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function viewDataAdminTrunojoyoDokumenList(Request $request){
        if(request()->ajax()){
            $data = DB::select("select pen.nosj, pen.noinv, pen.custname, pen.tanggal_do, pen.custid, cus.address as alamat, pen.check_dibayar_admin, pen.check_diserahkan_admin, pen.keterangan, pen.ket_trunojoyo, pen.keterangan_penerimaan, pen.keterangan_penagihan, pen.tanggal_terima_trunojoyo, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join customers as cus on cus.custid = pen.custid where pen.tanggal_terima_trunojoyo = ? and pen.check_diterima_trunojoyo = 1 group by pen.nosj", [$request->tanggal]);

            return datatables()->of($data)->addIndexColumn()->make(true);
        }

        return view('tagih_kirim_trunojoyo');
    }

    public function uploadExcelPenagihan(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new PenagihanImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        if($import->getDuplikat() == 1){
            return redirect('admin/dsgm/kirim_dok')->with('alert','Data Duplikat, Data Sudah Ada');
        }else{
            if($import->getKosong() == 1){
                return redirect('admin/dsgm/kirim_dok')->with('alert','Nomor Invoice Kosong. Silahkan Upload Ulang Setelah Ditambahkan Nomor Invoicenya');
            }else{
                return redirect('admin/dsgm/kirim_dok')->with('alert','Sukses Menambahkan Data');
            }
            
        }
    }

    public function viewSemuaKartuPiutangTable(Request $request){
        $data = DB::select("select pen.custid, pen.custname as customers, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as saldo from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.custid from penagihan as pena where pena.ppn = 0 group by pena.custid) pen2 on pen.custid=pen2.custid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.custid from penagihan as pena where pena.ppn > 0 group by pena.custid) pen3 on pen.custid=pen3.custid group by pen.custid");

        return datatables()->of($data)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_kartu_piutang_semua')->rawColumns(['action'])->make(true);
    }

    public function viewBelumLunasKartuPiutangTable(Request $request){
        $data = DB::select("select pen.custid, pen.custname as customers, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as saldo from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.custid from penagihan as pena where pena.ppn = 0 group by pena.custid) pen2 on pen.custid=pen2.custid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.custid from penagihan as pena where pena.ppn > 0 group by pena.custid) pen3 on pen.custid=pen3.custid where pen.lunas = 0 group by pen.custid");

        return datatables()->of($data)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_kartu_piutang_belum_lunas')->rawColumns(['action'])->make(true);
    }

    public function viewSudahLunasKartuPiutangTable(Request $request){
        $data = DB::select("select pen.custid, pen.custname as customers, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as saldo from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.custid from penagihan as pena where pena.ppn = 0 group by pena.custid) pen2 on pen.custid=pen2.custid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.custid from penagihan as pena where pena.ppn > 0 group by pena.custid) pen3 on pen.custid=pen3.custid where pen.lunas = 1 group by pen.custid");

        return datatables()->of($data)->addIndexColumn()->addColumn('action', 'button/action_button_penagihan_kartu_piutang_sudah_lunas')->rawColumns(['action'])->make(true);
    }

    public function viewSemuaDetailKartuPiutangTable(Request $request){
        if(request()->ajax()){
            $data = DB::select("select @lunas:= 0 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, null as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (percent_ppn * (sub_amount - diskon) / 100) END) as tagihan from penagihan as pen where lunas = 0 and custid = ?) as pp join (select @running_total:=0) r union select @lunas:= 1 as lunas, p.tanggal, null as nosj, p.noar, null as noinv, null as itemid, p.keterangan, DATEDIFF(p.tanggal, p.tanggal_do) as selisih_hari, null as sub_nominal, p.total_nominal, @running_total:=@running_total + p.total_nominal AS saldo from (select tanggal, pelunasan.noar, pelunasan.keterangan, total_nominal, pn.tanggal_do from pelunasan left join pelunasan_detail as pd on pd.noar = pelunasan.noar left join penagihan as pn on pn.noinv = pd.noinv where pd.custid = ? and EXISTS (SELECT pl.noar FROM pelunasan as pl left join pelunasan_detail as pd on pl.noar = pd.noar WHERE pd.custid = ?)) as p join (select @running_total:=0) r union select @lunas:= 1 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, DATEDIFF(pp.tanggal_pelunasan, pp.tanggal_do) as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, tanggal_pelunasan, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (percent_ppn * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 1 and custid = ? and not EXISTS (SELECT pll.noar FROM pelunasan as pll left join pelunasan_detail as pdd on pll.noar = pdd.noar WHERE pdd.custid = ?)) as pp join (select @running_total:=0) r", [$request->custid, $request->custid, $request->custid, $request->custid, $request->custid]);

            return datatables()->of($data)->make(true);
        }
    }

    public function viewBelumLunasDetailKartuPiutangTable(Request $request){
        if(request()->ajax()){
            $data = DB::select("select p.tanggal_do, p.nosj, p.itemid, p.noinv, p.itemname, p.qty, p.price, p.tagihan, @running_total:=@running_total + p.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (percent_ppn * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 0 and custid = ?) as p join (select @running_total:=0) r order by p.tanggal_do", [$request->custid]);

            return datatables()->of($data)->make(true);
        }
    }

    public function viewSudahLunasDetailKartuPiutangTable(Request $request){
        if(request()->ajax()){
            $data = DB::select("select p.tanggal, null as nosj, p.noar, null as noinv, null as itemid, p.keterangan, DATEDIFF(p.tanggal, p.tanggal_do) as selisih_hari, null as sub_nominal, p.total_nominal, @running_total:=@running_total + p.total_nominal AS saldo from (select tanggal, pelunasan.noar, pelunasan.keterangan, total_nominal, pn.tanggal_do from pelunasan left join pelunasan_detail as pd on pd.noar = pelunasan.noar left join penagihan as pn on pn.noinv = pd.noinv where pd.custid = ? and EXISTS (SELECT pl.noar FROM pelunasan as pl left join pelunasan_detail as pd on pl.noar = pd.noar WHERE pd.custid = ?)) as p join (select @running_total:=0) r union select pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, DATEDIFF(pp.tanggal_pelunasan, pp.tanggal_do) as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, tanggal_pelunasan, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (percent_ppn * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 1 and custid = ? and not EXISTS (SELECT pll.noar FROM pelunasan as pll left join pelunasan_detail as pdd on pll.noar = pdd.noar WHERE pdd.custid = ?)) as pp join (select @running_total:=0) rr", [$request->custid, $request->custid, $request->custid, $request->custid]);

            return datatables()->of($data)->make(true);
        }
    }

    public function viewBelumLunasDetailControlKartuPiutangTable(Request $request){
        $data = DB::table('penagihan')->select('qty', 'price', 'diskon', DB::raw("(CASE WHEN ppn = 0 THEN 0 ELSE (percent_ppn * (sub_amount - diskon) / 100) END) as pajak"))->where('nosj', $request->nosj)->where('itemid', $request->itemid)->get();

        return Response()->json($data);
    }

    public function viewSudahLunasDetailControlKartuPiutangTable(Request $request){
        $data = DB::table('pelunasan_detail as pd')->select('pd.noinv', 'pn.nosj', 'pn.tanggal_do', 'pn.itemname', 'pn.qty', 'pn.price', DB::raw("(CASE WHEN pn.ppn = 0 THEN (pn.sub_amount - pn.diskon) ELSE (pn.sub_amount - pn.diskon) + (pn.percent_ppn * (pn.sub_amount - pn.diskon) / 100) END) as tagihan"), DB::raw("DATEDIFF(pl.tanggal, pn.tanggal_do) as selisih_hari"))->leftJoin('penagihan as pn', 'pn.noinv', '=', 'pd.noinv')->leftJoin('pelunasan as pl', 'pl.noar', '=', 'pd.noar')->where('pd.noar', $request->noar)->get();

        return Response()->json($data);
    }

    public function printKartuPiutang(Request $request){
        $arrayForTable = [];
        $arrayForTable2 = [];
        $cek_cust = 0;

        if($request->customers == null || $request->customers == ''){
            $cek_cust = 0;
            $data = DB::select("select pen.custid, pen.custname as customers, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as saldo from penagihan as pen left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.custid from penagihan as pena where pena.ppn = 0 group by pena.custid) pen2 on pen.custid=pen2.custid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.custid from penagihan as pena where pena.ppn > 0 group by pena.custid) pen3 on pen.custid=pen3.custid where (pen.tanggal_do between ? and ?) group by pen.custid order by customers", [$request->from_date, $request->to_date]);

            foreach($data as $d){
                $arrayForTable[$d->custid] = [];

                $data2 = DB::select("select @lunas:= 0 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, null as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (percent_ppn * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 0 and custid = ? and (tanggal_do between ? and ?)) as pp join (select @running_total:=0) r union select @lunas:= 1 as lunas, p.tanggal, null as nosj, p.noar, null as noinv, null as itemid, p.keterangan, DATEDIFF(p.tanggal, p.tanggal_do) as selisih_hari, null as sub_nominal, p.total_nominal, @running_total:=@running_total + p.total_nominal AS saldo from (select tanggal, pelunasan.noar, pelunasan.keterangan, total_nominal, pn.tanggal_do from pelunasan left join pelunasan_detail as pd on pd.noar = pelunasan.noar left join penagihan as pn on pn.noinv = pd.noinv where pd.custid = ? and (tanggal between ? and ?) and EXISTS (SELECT pl.noar FROM pelunasan as pl left join pelunasan_detail as pd on pl.noar = pd.noar WHERE pd.custid = ?)) as p join (select @running_total:=0) r union select @lunas:= 1 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, DATEDIFF(pp.tanggal_pelunasan, pp.tanggal_do) as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, tanggal_pelunasan, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (percent_ppn * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 1 and custid = ? and (tanggal_do between ? and ?) and not EXISTS (SELECT pll.noar FROM pelunasan as pll left join pelunasan_detail as pdd on pll.noar = pdd.noar WHERE pdd.custid = ?)) as pp join (select @running_total:=0) r", [$d->custid, $request->from_date, $request->to_date, $d->custid, $request->from_date, $request->to_date, $d->custid, $d->custid, $request->from_date, $request->to_date, $d->custid]);

                foreach($data2 as $d2){
                    $temp = [];
                    $temp['lunas'] = $d2->lunas;
                    $temp['tanggal'] = $d2->tanggal;
                    $temp['nosj'] = $d2->nosj;
                    $temp['noar'] = $d2->noar;
                    $temp['noinv'] = $d2->noinv;
                    $temp['itemid'] = $d2->itemid;
                    $temp['keterangan'] = $d2->keterangan;
                    $temp['selisih_hari'] = $d2->selisih_hari;
                    $temp['sub_nominal'] = $d2->sub_nominal;
                    $temp['total_nominal'] = $d2->total_nominal;
                    $temp['saldo'] = $d2->saldo;
                    $arrayForTable[$d->custid][] = $temp;

                    if($d2->nosj){
                        $arrayForTable2[$d2->nosj] = [];

                        $data3 = DB::table('penagihan')->select('qty', 'price', 'diskon', DB::raw("(CASE WHEN ppn = 0 THEN 0 ELSE (percent_ppn * (sub_amount - diskon) / 100) END) as pajak"))->where('nosj', $d2->nosj)->where('itemid', $d2->itemid)->get();

                        foreach($data3 as $d3){
                            $temp2 = [];
                            $temp2['qty'] = $d3->qty;
                            $temp2['price'] = $d3->price;
                            $temp2['diskon'] = $d3->diskon;
                            $temp2['pajak'] = $d3->pajak;
                            $arrayForTable2[$d2->nosj][] = $temp2;
                        }
                    }

                    if($d2->noar){
                        $arrayForTable2[$d2->noar] = [];

                        $data3 = DB::select("select pd.noinv, pen.nosj, pen.tanggal_do, pen.itemname, pen.qty, pen.price, DATEDIFF(pl.tanggal, pen.tanggal_do) as selisih_hari, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from pelunasan_detail as pd left join penagihan as pen on pen.noinv = pd.noinv left join pelunasan as pl on pl.noar = pd.noar left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pd.noar = ?", [$d2->noar]);

                        foreach($data3 as $d3){
                            $temp2 = [];
                            $temp2['noinv'] = $d3->noinv;
                            $temp2['nosj'] = $d3->nosj;
                            $temp2['tanggal_do'] = $d3->tanggal_do;
                            $temp2['itemname'] = $d3->itemname;
                            $temp2['qty'] = $d3->qty;
                            $temp2['price'] = $d3->price;
                            $temp2['tagihan'] = $d3->tagihan;
                            $temp2['selisih_hari'] = $d3->selisih_hari;
                            $arrayForTable2[$d2->noar][] = $temp2;
                        }
                    }
                }
            }

            $data_cust = [];
        }else{
            $cek_cust = 1;
            $data = DB::select("select @lunas:= 0 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, null as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (percent_ppn * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 0 and custid = ? and (tanggal_do between ? and ?)) as pp join (select @running_total:=0) r union select @lunas:= 1 as lunas, p.tanggal, null as nosj, p.noar, null as noinv, null as itemid, p.keterangan, DATEDIFF(p.tanggal, p.tanggal_do) as selisih_hari, null as sub_nominal, p.total_nominal, @running_total:=@running_total + p.total_nominal AS saldo from (select tanggal, pelunasan.noar, pelunasan.keterangan, total_nominal, pn.tanggal_do from pelunasan left join pelunasan_detail as pd on pd.noar = pelunasan.noar left join penagihan as pn on pn.noinv = pd.noinv where pd.custid = ? and (tanggal between ? and ?) and EXISTS (SELECT pl.noar FROM pelunasan as pl left join pelunasan_detail as pd on pl.noar = pd.noar WHERE pd.custid = ?)) as p join (select @running_total:=0) r union select @lunas:= 1 as lunas, pp.tanggal_do as tanggal, pp.nosj, null as noar, pp.noinv, pp.itemid, pp.itemname as keterangan, DATEDIFF(pp.tanggal_pelunasan, pp.tanggal_do) as selisih_hari, (pp.qty * pp.price) as sub_nominal, pp.tagihan as total_nominal, @running_total:=@running_total + pp.tagihan AS saldo from (select tanggal_do, nosj, itemid, noinv, itemname, qty, price, tanggal_pelunasan, (CASE WHEN ppn = 0 THEN (sub_amount - diskon) ELSE (sub_amount - diskon) + (percent_ppn * (sub_amount - diskon) / 100) END) as tagihan from penagihan where lunas = 1 and custid = ? and (tanggal_do between ? and ?) and not EXISTS (SELECT pll.noar FROM pelunasan as pll left join pelunasan_detail as pdd on pll.noar = pdd.noar WHERE pdd.custid = ?)) as pp join (select @running_total:=0) r", [$request->customers, $request->from_date, $request->to_date, $request->customers, $request->from_date, $request->to_date, $request->customers, $request->customers, $request->from_date, $request->to_date, $request->customers]);

            foreach($data as $d){
                if($d->nosj){
                    $arrayForTable[$d->nosj] = [];

                    $data2 = DB::table('penagihan')->select('qty', 'price', 'diskon', DB::raw("(CASE WHEN ppn = 0 THEN 0 ELSE (percent_ppn * (sub_amount - diskon) / 100) END) as pajak"))->where('nosj', $d->nosj)->where('itemid', $d->itemid)->get();

                    foreach($data2 as $d2){
                        $temp = [];
                        $temp['qty'] = $d2->qty;
                        $temp['price'] = $d2->price;
                        $temp['diskon'] = $d2->diskon;
                        $temp['pajak'] = $d2->pajak;
                        $arrayForTable[$d->nosj][] = $temp;
                    }
                }
                
                if($d->noar){
                    $arrayForTable[$d->noar] = [];

                    $data2 = DB::select("select pd.noinv, pen.nosj, pen.tanggal_do, pen.itemname, pen.qty, pen.price, DATEDIFF(pl.tanggal, pen.tanggal_do) as selisih_hari, (coalesce(sum(pen2.sum1), 0) + coalesce(sum(pen3.sum2), 0)) as tagihan from pelunasan_detail as pd left join penagihan as pen on pen.noinv = pd.noinv left join pelunasan as pl on pl.noar = pd.noar left join (select (sum(pena.sub_amount) - sum(pena.diskon)) as sum1, pena.nosj, pena.itemid from penagihan as pena where pena.ppn = 0 group by pena.nosj) pen2 on pen.nosj=pen2.nosj and pen.itemid = pen2.itemid left join (select (sum(pena.sub_amount) - sum(pena.diskon)) + (pena.percent_ppn * (sum(pena.sub_amount) - sum(pena.diskon)) / 100) as sum2, pena.nosj, pena.itemid from penagihan as pena where pena.ppn > 0 group by pena.nosj) pen3 on pen.nosj=pen3.nosj and pen.itemid = pen3.itemid join tbl_metode_pembayaran as met on met.id = pen.metode_pembayaran where pd.noar = ?", [$d->noar]);

                    foreach($data2 as $d2){
                        $temp = [];
                        $temp['noinv'] = $d2->noinv;
                        $temp['nosj'] = $d2->nosj;
                        $temp['tanggal_do'] = $d2->tanggal_do;
                        $temp['itemname'] = $d2->itemname;
                        $temp['qty'] = $d2->qty;
                        $temp['price'] = $d2->price;
                        $temp['tagihan'] = $d2->tagihan;
                        $temp['selisih_hari'] = $d2->selisih_hari;
                        $arrayForTable[$d->noar][] = $temp;
                    }
                }

            }

            $data_cust = DB::table('customers')->select('custid', 'custname', 'address', 'phone')->where('custid', $request->customers)->first();
        }

        $pdf = PDF::loadView('print_kartu_piutang', ['data' => $data, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'data_cust' => $data_cust, 'data_arr' => $arrayForTable, 'data_arr2' => $arrayForTable2, 'cek_cust' => $cek_cust])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function downloadExcelKartuPiutang($from_date, $to_date, $custid){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);
        $val_custid = $this->decrypt($custid);

        if($val_custid == null || $val_custid == ''){
            $nama_file = 'Laporan Kartu Piutang Tanggal ' . $val_from_date . ' sd ' . $val_to_date . ' All Cust.xlsx';
        }else{
            $nama_file = 'Laporan Kartu Piutang Tanggal ' . $val_from_date . ' sd ' . $val_to_date . ' Cust ' . $val_custid . '.xlsx';
        }

        return Excel::download(new KartuPiutangExport($val_from_date, $val_to_date, $val_custid), $nama_file);
    }

    public function viewAgingScheduleDalamKotaTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        $month_to_date = date('Y-m-31', strtotime($request->to_date . "-1 month"));
        $to_date_awal = date('Y-m-1', strtotime($request->to_date));
        $awal_bulan_ini = date('Y-m-1');

        // print_r($month_to_date);

        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = DB::select("select pen.custid, pen.custname, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen2 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen2.nosj=pena2.nosj and pen2.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen2.nosj=pena3.nosj and pen2.itemid=pena3.itemid where pen2.custid = pen.custid and pen2.lunas = 0 and pen2.hitung_bayar is null and (pen2.tanggal_do between ? and ?) and ((pen2.tanggal_jatuh_tempo between ? and ?) or pen2.tanggal_jatuh_tempo is null) group by pen2.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen3 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen3.nosj=pena2.nosj and pen3.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen3.nosj=pena3.nosj and pen3.itemid=pena3.itemid where pen3.custid = pen.custid and pen3.lunas = 1 and (pen3.tanggal_pelunasan between ? and ?) group by pen3.custid), 0) + COALESCE((select sum(pen4.sisa_bayar) as total from penagihan as pen4 where pen4.custid = pen.custid and pen4.lunas = 0 and pen4.hitung_bayar is not null and (pen4.tanggal_do between ? and ?) and pen4.tanggal_pelunasan < ? and ((pen4.tanggal_jatuh_tempo between ? and ?) or pen4.tanggal_jatuh_tempo is null) group by pen4.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen5 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen5.nosj=pena2.nosj and pen5.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen5.nosj=pena3.nosj and pen5.itemid=pena3.itemid where pen5.custid = pen.custid and pen5.lunas = 0 and pen5.hitung_bayar = 1 and (pen5.tanggal_do between ? and ?) and pen5.tanggal_pelunasan >= ? and ((pen5.tanggal_jatuh_tempo between ? and ?) or pen5.tanggal_jatuh_tempo is null) group by pen5.custid), 0) + COALESCE((select (sum(pen6.sisa_bayar)) from penagihan as pen6 where pen6.custid = pen.custid and pen6.lunas = 0 and pen6.hitung_bayar > 1 and (pen6.tanggal_do between ? and ?) and (pen6.tanggal_pelunasan between ? and ?) and ((pen6.tanggal_jatuh_tempo between ? and ?) or pen6.tanggal_jatuh_tempo is null) group by pen6.custid), 0) + COALESCE((select (sum(peld.nominal + peld.other)) from penagihan as pen7 left join pelunasan_detail as peld on peld.noinv = pen7.noinv and peld.custid = pen7.custid left join pelunasan as pel on pel.noar = peld.noar where pen7.custid = pen.custid and pen7.lunas = 0 and pen7.hitung_bayar > 1 and (pen7.tanggal_do between ? and ?) and (pel.tanggal between ? and ?) and ((pen7.tanggal_jatuh_tempo between ? and ?) or pen7.tanggal_jatuh_tempo is null) group by pen7.custid), 0) as saldo_awal, COALESCE((select count(distinct pen8.nosj) from penagihan as pen8 where pen8.custid = pen.custid and (pen8.tanggal_do between ? and ?) group by pen8.custid), 0) as jumlah_fak, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen9 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen9.nosj=pena2.nosj and pen9.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen9.nosj=pena3.nosj and pen9.itemid=pena3.itemid where pen9.custid = pen.custid and (pen9.tanggal_do between ? and ?) group by pen9.custid), 0) as nilai_fak, COALESCE((select sum(peld.nominal + peld.other) from pelunasan as pel join pelunasan_detail as peld on peld.noar = pel.noar where peld.custid = pen.custid and (pel.tanggal between ? and ?) group by peld.custid), 0) as pembayaran, (select(saldo_awal)) + (select(nilai_fak)) - (select(pembayaran)) as saldo_akhir from penagihan as pen where pen.custid like 'T%' and (pen.tanggal_do between ? and ?) group by pen.custid", [$request->from_date, $month_to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date]);
            }else{
                $penagihan = DB::select("select pen.custid, pen.custname, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen2 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen2.nosj=pena2.nosj and pen2.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen2.nosj=pena3.nosj and pen2.itemid=pena3.itemid where pen2.custid = pen.custid and pen2.lunas = 0 and pen2.hitung_bayar is null and (pen2.tanggal_do between ? and ?) group by pen2.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen3 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen3.nosj=pena2.nosj and pen3.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen3.nosj=pena3.nosj and pen3.itemid=pena3.itemid where pen3.custid = pen.custid and pen3.lunas = 1 and (pen3.tanggal_pelunasan between ? and ?) group by pen3.custid), 0) + COALESCE((select sum(pen4.sisa_bayar) as total from penagihan as pen4 where pen4.custid = pen.custid and pen4.lunas = 0 and pen4.hitung_bayar is not null and (pen4.tanggal_do between ? and ?) and pen4.tanggal_pelunasan < ? group by pen4.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen5 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen5.nosj=pena2.nosj and pen5.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen5.nosj=pena3.nosj and pen5.itemid=pena3.itemid where pen5.custid = pen.custid and pen5.lunas = 0 and pen5.hitung_bayar = 1 and (pen5.tanggal_do between ? and ?) and pen5.tanggal_pelunasan >= ? group by pen5.custid), 0) + COALESCE((select (sum(pen6.sisa_bayar)) from penagihan as pen6 where pen6.custid = pen.custid and pen6.lunas = 0 and pen6.hitung_bayar > 1 and (pen6.tanggal_do between ? and ?) and (pen6.tanggal_pelunasan between ? and ?) group by pen6.custid), 0) + COALESCE((select (sum(peld.nominal + peld.other)) from penagihan as pen7 left join pelunasan_detail as peld on peld.noinv = pen7.noinv and peld.custid = pen7.custid left join pelunasan as pel on pel.noar = peld.noar where pen7.custid = pen.custid and pen7.lunas = 0 and pen7.hitung_bayar > 1 and (pen7.tanggal_do between ? and ?) and (pel.tanggal between ? and ?) group by pen7.custid), 0) as saldo_awal, COALESCE((select count(distinct pen8.nosj) from penagihan as pen8 where pen8.custid = pen.custid and (pen8.tanggal_do between ? and ?) group by pen8.custid), 0) as jumlah_fak, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen9 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen9.nosj=pena2.nosj and pen9.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen9.nosj=pena3.nosj and pen9.itemid=pena3.itemid where pen9.custid = pen.custid and (pen9.tanggal_do between ? and ?) group by pen9.custid), 0) as nilai_fak, COALESCE((select sum(peld.nominal + peld.other) from pelunasan as pel join pelunasan_detail as peld on peld.noar = pel.noar where peld.custid = pen.custid and (pel.tanggal between ? and ?) group by peld.custid), 0) as pembayaran, (select(saldo_awal)) + (select(nilai_fak)) - (select(pembayaran)) as saldo_akhir from penagihan as pen where pen.custid like 'T%' and (pen.tanggal_do between ? and ?) group by pen.custid", [$awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d')]);
            }

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_aging_schedule');
    } 

    public function viewAgingScheduleJakartaTable(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        $month_to_date = date('Y-m-31', strtotime($request->to_date . "-1 month"));
        $to_date_awal = date('Y-m-1', strtotime($request->to_date));
        $awal_bulan_ini = date('Y-m-1');

        if(request()->ajax()){
            if(!empty($request->from_date)){
                $penagihan = DB::select("select pen.custid, pen.custname, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen2 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen2.nosj=pena2.nosj and pen2.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen2.nosj=pena3.nosj and pen2.itemid=pena3.itemid where pen2.custid = pen.custid and pen2.lunas = 0 and pen2.hitung_bayar is null and (pen2.tanggal_do between ? and ?) and ((pen2.tanggal_jatuh_tempo between ? and ?) or pen2.tanggal_jatuh_tempo is null) group by pen2.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen3 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen3.nosj=pena2.nosj and pen3.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen3.nosj=pena3.nosj and pen3.itemid=pena3.itemid where pen3.custid = pen.custid and pen3.lunas = 1 and (pen3.tanggal_pelunasan between ? and ?) group by pen3.custid), 0) + COALESCE((select sum(pen4.sisa_bayar) as total from penagihan as pen4 where pen4.custid = pen.custid and pen4.lunas = 0 and pen4.hitung_bayar is not null and (pen4.tanggal_do between ? and ?) and pen4.tanggal_pelunasan < ? and ((pen4.tanggal_jatuh_tempo between ? and ?) or pen4.tanggal_jatuh_tempo is null) group by pen4.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen5 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen5.nosj=pena2.nosj and pen5.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen5.nosj=pena3.nosj and pen5.itemid=pena3.itemid where pen5.custid = pen.custid and pen5.lunas = 0 and pen5.hitung_bayar = 1 and (pen5.tanggal_do between ? and ?) and pen5.tanggal_pelunasan >= ? and ((pen5.tanggal_jatuh_tempo between ? and ?) or pen5.tanggal_jatuh_tempo is null) group by pen5.custid), 0) + COALESCE((select (sum(pen6.sisa_bayar)) from penagihan as pen6 where pen6.custid = pen.custid and pen6.lunas = 0 and pen6.hitung_bayar > 1 and (pen6.tanggal_do between ? and ?) and (pen6.tanggal_pelunasan between ? and ?) and ((pen6.tanggal_jatuh_tempo between ? and ?) or pen6.tanggal_jatuh_tempo is null) group by pen6.custid), 0) + COALESCE((select (sum(peld.nominal + peld.other)) from penagihan as pen7 left join pelunasan_detail as peld on peld.noinv = pen7.noinv and peld.custid = pen7.custid left join pelunasan as pel on pel.noar = peld.noar where pen7.custid = pen.custid and pen7.lunas = 0 and pen7.hitung_bayar > 1 and (pen7.tanggal_do between ? and ?) and (pel.tanggal between ? and ?) and ((pen7.tanggal_jatuh_tempo between ? and ?) or pen7.tanggal_jatuh_tempo is null) group by pen7.custid), 0) as saldo_awal, COALESCE((select count(distinct pen8.nosj) from penagihan as pen8 where pen8.custid = pen.custid and (pen8.tanggal_do between ? and ?) group by pen8.custid), 0) as jumlah_fak, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen9 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen9.nosj=pena2.nosj and pen9.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen9.nosj=pena3.nosj and pen9.itemid=pena3.itemid where pen9.custid = pen.custid and (pen9.tanggal_do between ? and ?) group by pen9.custid), 0) as nilai_fak, COALESCE((select sum(peld.nominal + peld.other) from pelunasan as pel join pelunasan_detail as peld on peld.noar = pel.noar where peld.custid = pen.custid and (pel.tanggal between ? and ?) group by peld.custid), 0) as pembayaran, (select(saldo_awal)) + (select(nilai_fak)) - (select(pembayaran)) as saldo_akhir from penagihan as pen where pen.custid like 'B%' and (pen.tanggal_do between ? and ?) group by pen.custid", [$request->from_date, $month_to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date]);
            }else{
                $penagihan = DB::select("select pen.custid, pen.custname, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen2 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen2.nosj=pena2.nosj and pen2.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen2.nosj=pena3.nosj and pen2.itemid=pena3.itemid where pen2.custid = pen.custid and pen2.lunas = 0 and pen2.hitung_bayar is null and (pen2.tanggal_do between ? and ?) group by pen2.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen3 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen3.nosj=pena2.nosj and pen3.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen3.nosj=pena3.nosj and pen3.itemid=pena3.itemid where pen3.custid = pen.custid and pen3.lunas = 1 and (pen3.tanggal_pelunasan between ? and ?) group by pen3.custid), 0) + COALESCE((select sum(pen4.sisa_bayar) as total from penagihan as pen4 where pen4.custid = pen.custid and pen4.lunas = 0 and pen4.hitung_bayar is not null and (pen4.tanggal_do between ? and ?) and pen4.tanggal_pelunasan < ? group by pen4.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen5 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen5.nosj=pena2.nosj and pen5.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen5.nosj=pena3.nosj and pen5.itemid=pena3.itemid where pen5.custid = pen.custid and pen5.lunas = 0 and pen5.hitung_bayar = 1 and (pen5.tanggal_do between ? and ?) and pen5.tanggal_pelunasan >= ? group by pen5.custid), 0) + COALESCE((select (sum(pen6.sisa_bayar)) from penagihan as pen6 where pen6.custid = pen.custid and pen6.lunas = 0 and pen6.hitung_bayar > 1 and (pen6.tanggal_do between ? and ?) and (pen6.tanggal_pelunasan between ? and ?) group by pen6.custid), 0) + COALESCE((select (sum(peld.nominal + peld.other)) from penagihan as pen7 left join pelunasan_detail as peld on peld.noinv = pen7.noinv and peld.custid = pen7.custid left join pelunasan as pel on pel.noar = peld.noar where pen7.custid = pen.custid and pen7.lunas = 0 and pen7.hitung_bayar > 1 and (pen7.tanggal_do between ? and ?) and (pel.tanggal between ? and ?) group by pen7.custid), 0) as saldo_awal, COALESCE((select count(distinct pen8.nosj) from penagihan as pen8 where pen8.custid = pen.custid and (pen8.tanggal_do between ? and ?) group by pen8.custid), 0) as jumlah_fak, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen9 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen9.nosj=pena2.nosj and pen9.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen9.nosj=pena3.nosj and pen9.itemid=pena3.itemid where pen9.custid = pen.custid and (pen9.tanggal_do between ? and ?) group by pen9.custid), 0) as nilai_fak, COALESCE((select sum(peld.nominal + peld.other) from pelunasan as pel join pelunasan_detail as peld on peld.noar = pel.noar where peld.custid = pen.custid and (pel.tanggal between ? and ?) group by peld.custid), 0) as pembayaran, (select(saldo_awal)) + (select(nilai_fak)) - (select(pembayaran)) as saldo_akhir from penagihan as pen where pen.custid like 'B%' and (pen.tanggal_do between ? and ?) group by pen.custid", [$awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d'), $awal_bulan_ini, date('Y-m-d')]);
            }

            return datatables()->of($penagihan)->make(true);
        }
        return view('tagih_aging_schedule');
    } 

    public function printAgingSchedule(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        $month_to_date = date('Y-m-31', strtotime($request->to_date . "-1 month"));
        $to_date_awal = date('Y-m-1', strtotime($request->to_date));
        $awal_bulan_ini = date('Y-m-1');

        if($request->customers == null || $request->customers == ''){
            if($request->group == 'T'){
                $data = DB::select("select pen.custid, pen.custname, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen2 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen2.nosj=pena2.nosj and pen2.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen2.nosj=pena3.nosj and pen2.itemid=pena3.itemid where pen2.custid = pen.custid and pen2.lunas = 0 and pen2.hitung_bayar is null and (pen2.tanggal_do between ? and ?) and ((pen2.tanggal_jatuh_tempo between ? and ?) or pen2.tanggal_jatuh_tempo is null) group by pen2.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen3 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen3.nosj=pena2.nosj and pen3.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen3.nosj=pena3.nosj and pen3.itemid=pena3.itemid where pen3.custid = pen.custid and pen3.lunas = 1 and (pen3.tanggal_pelunasan between ? and ?) group by pen3.custid), 0) + COALESCE((select sum(pen4.sisa_bayar) as total from penagihan as pen4 where pen4.custid = pen.custid and pen4.lunas = 0 and pen4.hitung_bayar is not null and (pen4.tanggal_do between ? and ?) and pen4.tanggal_pelunasan < ? and ((pen4.tanggal_jatuh_tempo between ? and ?) or pen4.tanggal_jatuh_tempo is null) group by pen4.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen5 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen5.nosj=pena2.nosj and pen5.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen5.nosj=pena3.nosj and pen5.itemid=pena3.itemid where pen5.custid = pen.custid and pen5.lunas = 0 and pen5.hitung_bayar = 1 and (pen5.tanggal_do between ? and ?) and pen5.tanggal_pelunasan >= ? and ((pen5.tanggal_jatuh_tempo between ? and ?) or pen5.tanggal_jatuh_tempo is null) group by pen5.custid), 0) + COALESCE((select (sum(pen6.sisa_bayar)) from penagihan as pen6 where pen6.custid = pen.custid and pen6.lunas = 0 and pen6.hitung_bayar > 1 and (pen6.tanggal_do between ? and ?) and (pen6.tanggal_pelunasan between ? and ?) and ((pen6.tanggal_jatuh_tempo between ? and ?) or pen6.tanggal_jatuh_tempo is null) group by pen6.custid), 0) + COALESCE((select (sum(peld.nominal + peld.other)) from penagihan as pen7 left join pelunasan_detail as peld on peld.noinv = pen7.noinv and peld.custid = pen7.custid left join pelunasan as pel on pel.noar = peld.noar where pen7.custid = pen.custid and pen7.lunas = 0 and pen7.hitung_bayar > 1 and (pen7.tanggal_do between ? and ?) and (pel.tanggal between ? and ?) and ((pen7.tanggal_jatuh_tempo between ? and ?) or pen7.tanggal_jatuh_tempo is null) group by pen7.custid), 0) as saldo_awal, COALESCE((select count(distinct pen8.nosj) from penagihan as pen8 where pen8.custid = pen.custid and (pen8.tanggal_do between ? and ?) group by pen8.custid), 0) as jumlah_fak, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen9 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen9.nosj=pena2.nosj and pen9.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen9.nosj=pena3.nosj and pen9.itemid=pena3.itemid where pen9.custid = pen.custid and (pen9.tanggal_do between ? and ?) group by pen9.custid), 0) as nilai_fak, COALESCE((select sum(peld.nominal + peld.other) from pelunasan as pel join pelunasan_detail as peld on peld.noar = pel.noar where peld.custid = pen.custid and (pel.tanggal between ? and ?) group by peld.custid), 0) as pembayaran, (select(saldo_awal)) + (select(nilai_fak)) - (select(pembayaran)) as saldo_akhir, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen10 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen10.nosj=pena2.nosj and pen10.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen10.nosj=pena3.nosj and pen10.itemid=pena3.itemid where pen10.custid = pen.custid and pen10.lunas = 0 and pen10.hitung_bayar is null and pen10.tanggal_jatuh_tempo <= ? and pen10.tanggal_jatuh_tempo > ? - interval 2 month group by pen10.custid), 0) + COALESCE((select sum(pen11.sisa_bayar) from penagihan as pen11 where pen11.custid = pen.custid and pen11.lunas = 0 and pen11.hitung_bayar is not null and pen11.tanggal_jatuh_tempo <= ? and pen11.tanggal_jatuh_tempo > ? - interval 2 month group by pen11.custid), 0) as nol_dua_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen12 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen12.nosj=pena2.nosj and pen12.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen12.nosj=pena3.nosj and pen12.itemid=pena3.itemid where pen12.custid = pen.custid and pen12.lunas = 0 and pen12.hitung_bayar is null and pen12.tanggal_jatuh_tempo <= ? - interval 2 month and pen12.tanggal_jatuh_tempo > ? - interval 3 month group by pen12.custid), 0) + COALESCE((select sum(pen13.sisa_bayar) from penagihan as pen13 where pen13.custid = pen.custid and pen13.lunas = 0 and pen13.hitung_bayar is not null and pen13.tanggal_jatuh_tempo <= ? - interval 2 month and pen13.tanggal_jatuh_tempo > ? - interval 3 month group by pen13.custid), 0) as dua_tiga_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen14 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen14.nosj=pena2.nosj and pen14.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen14.nosj=pena3.nosj and pen14.itemid=pena3.itemid where pen14.custid = pen.custid and pen14.lunas = 0 and pen14.hitung_bayar is null and pen14.tanggal_jatuh_tempo <= ? - interval 3 month and pen14.tanggal_jatuh_tempo > ? - interval 4 month group by pen14.custid), 0) + COALESCE((select sum(pen15.sisa_bayar) from penagihan as pen15 where pen15.custid = pen.custid and pen15.lunas = 0 and pen15.hitung_bayar is not null and pen15.tanggal_jatuh_tempo <= ? - interval 3 month and pen15.tanggal_jatuh_tempo > ? - interval 4 month group by pen15.custid), 0) as tiga_empat_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen16 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen16.nosj=pena2.nosj and pen16.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen16.nosj=pena3.nosj and pen16.itemid=pena3.itemid where pen16.custid = pen.custid and pen16.lunas = 0 and pen16.hitung_bayar is null and ((pen16.tanggal_jatuh_tempo <= ? - interval 4 month and pen16.tanggal_jatuh_tempo >= ?) or pen16.tanggal_jatuh_tempo is null) group by pen16.custid), 0) + COALESCE((select sum(pen17.sisa_bayar) from penagihan as pen17 where pen17.custid = pen.custid and pen17.lunas = 0 and pen17.hitung_bayar is not null and ((pen17.tanggal_jatuh_tempo <= ? - interval 4 month and pen17.tanggal_jatuh_tempo >= ?) or pen17.tanggal_jatuh_tempo is null) group by pen17.custid), 0) as lebih_empat_bulan, COALESCE((select (sum(pen18.nominal_bayar)) from penagihan as pen18 where pen18.custid = pen.custid and pen18.lunas = 0 and pen18.tanggal_tagih_cust is not null and pen18.tanggal_jatuh_tempo is not null and pen18.tanggal_tagih_cust > pen18.tanggal_jatuh_tempo and (pen18.tanggal_do between ? and ?) group by pen18.custid), 0) as bg_mundur, (select(saldo_akhir)) as saldo from penagihan as pen where pen.custid like 'T%' and (pen.tanggal_do between ? and ?) group by pen.custid", [$request->from_date, $month_to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->from_date, $request->to_date, $request->from_date, $request->from_date, $request->to_date, $request->from_date, $request->to_date]);
            }else{
                $data = DB::select("select pen.custid, pen.custname, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen2 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen2.nosj=pena2.nosj and pen2.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen2.nosj=pena3.nosj and pen2.itemid=pena3.itemid where pen2.custid = pen.custid and pen2.lunas = 0 and pen2.hitung_bayar is null and (pen2.tanggal_do between ? and ?) and ((pen2.tanggal_jatuh_tempo between ? and ?) or pen2.tanggal_jatuh_tempo is null) group by pen2.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen3 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen3.nosj=pena2.nosj and pen3.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen3.nosj=pena3.nosj and pen3.itemid=pena3.itemid where pen3.custid = pen.custid and pen3.lunas = 1 and (pen3.tanggal_pelunasan between ? and ?) group by pen3.custid), 0) + COALESCE((select sum(pen4.sisa_bayar) as total from penagihan as pen4 where pen4.custid = pen.custid and pen4.lunas = 0 and pen4.hitung_bayar is not null and (pen4.tanggal_do between ? and ?) and pen4.tanggal_pelunasan < ? and ((pen4.tanggal_jatuh_tempo between ? and ?) or pen4.tanggal_jatuh_tempo is null) group by pen4.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen5 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen5.nosj=pena2.nosj and pen5.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen5.nosj=pena3.nosj and pen5.itemid=pena3.itemid where pen5.custid = pen.custid and pen5.lunas = 0 and pen5.hitung_bayar = 1 and (pen5.tanggal_do between ? and ?) and pen5.tanggal_pelunasan >= ? and ((pen5.tanggal_jatuh_tempo between ? and ?) or pen5.tanggal_jatuh_tempo is null) group by pen5.custid), 0) + COALESCE((select (sum(pen6.sisa_bayar)) from penagihan as pen6 where pen6.custid = pen.custid and pen6.lunas = 0 and pen6.hitung_bayar > 1 and (pen6.tanggal_do between ? and ?) and (pen6.tanggal_pelunasan between ? and ?) and ((pen6.tanggal_jatuh_tempo between ? and ?) or pen6.tanggal_jatuh_tempo is null) group by pen6.custid), 0) + COALESCE((select (sum(peld.nominal + peld.other)) from penagihan as pen7 left join pelunasan_detail as peld on peld.noinv = pen7.noinv and peld.custid = pen7.custid left join pelunasan as pel on pel.noar = peld.noar where pen7.custid = pen.custid and pen7.lunas = 0 and pen7.hitung_bayar > 1 and (pen7.tanggal_do between ? and ?) and (pel.tanggal between ? and ?) and ((pen7.tanggal_jatuh_tempo between ? and ?) or pen7.tanggal_jatuh_tempo is null) group by pen7.custid), 0) as saldo_awal, COALESCE((select count(distinct pen8.nosj) from penagihan as pen8 where pen8.custid = pen.custid and (pen8.tanggal_do between ? and ?) group by pen8.custid), 0) as jumlah_fak, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen9 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen9.nosj=pena2.nosj and pen9.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen9.nosj=pena3.nosj and pen9.itemid=pena3.itemid where pen9.custid = pen.custid and (pen9.tanggal_do between ? and ?) group by pen9.custid), 0) as nilai_fak, COALESCE((select sum(peld.nominal + peld.other) from pelunasan as pel join pelunasan_detail as peld on peld.noar = pel.noar where peld.custid = pen.custid and (pel.tanggal between ? and ?) group by peld.custid), 0) as pembayaran, (select(saldo_awal)) + (select(nilai_fak)) - (select(pembayaran)) as saldo_akhir, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen10 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen10.nosj=pena2.nosj and pen10.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen10.nosj=pena3.nosj and pen10.itemid=pena3.itemid where pen10.custid = pen.custid and pen10.lunas = 0 and pen10.hitung_bayar is null and pen10.tanggal_jatuh_tempo <= ? and pen10.tanggal_jatuh_tempo > ? - interval 2 month group by pen10.custid), 0) + COALESCE((select sum(pen11.sisa_bayar) from penagihan as pen11 where pen11.custid = pen.custid and pen11.lunas = 0 and pen11.hitung_bayar is not null and pen11.tanggal_jatuh_tempo <= ? and pen11.tanggal_jatuh_tempo > ? - interval 2 month group by pen11.custid), 0) as nol_dua_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen12 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen12.nosj=pena2.nosj and pen12.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen12.nosj=pena3.nosj and pen12.itemid=pena3.itemid where pen12.custid = pen.custid and pen12.lunas = 0 and pen12.hitung_bayar is null and pen12.tanggal_jatuh_tempo <= ? - interval 2 month and pen12.tanggal_jatuh_tempo > ? - interval 3 month group by pen12.custid), 0) + COALESCE((select sum(pen13.sisa_bayar) from penagihan as pen13 where pen13.custid = pen.custid and pen13.lunas = 0 and pen13.hitung_bayar is not null and pen13.tanggal_jatuh_tempo <= ? - interval 2 month and pen13.tanggal_jatuh_tempo > ? - interval 3 month group by pen13.custid), 0) as dua_tiga_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen14 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen14.nosj=pena2.nosj and pen14.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen14.nosj=pena3.nosj and pen14.itemid=pena3.itemid where pen14.custid = pen.custid and pen14.lunas = 0 and pen14.hitung_bayar is null and pen14.tanggal_jatuh_tempo <= ? - interval 3 month and pen14.tanggal_jatuh_tempo > ? - interval 4 month group by pen14.custid), 0) + COALESCE((select sum(pen15.sisa_bayar) from penagihan as pen15 where pen15.custid = pen.custid and pen15.lunas = 0 and pen15.hitung_bayar is not null and pen15.tanggal_jatuh_tempo <= ? - interval 3 month and pen15.tanggal_jatuh_tempo > ? - interval 4 month group by pen15.custid), 0) as tiga_empat_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen16 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen16.nosj=pena2.nosj and pen16.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen16.nosj=pena3.nosj and pen16.itemid=pena3.itemid where pen16.custid = pen.custid and pen16.lunas = 0 and pen16.hitung_bayar is null and ((pen16.tanggal_jatuh_tempo <= ? - interval 4 month and pen16.tanggal_jatuh_tempo >= ?) or pen16.tanggal_jatuh_tempo is null) group by pen16.custid), 0) + COALESCE((select sum(pen17.sisa_bayar) from penagihan as pen17 where pen17.custid = pen.custid and pen17.lunas = 0 and pen17.hitung_bayar is not null and ((pen17.tanggal_jatuh_tempo <= ? - interval 4 month and pen17.tanggal_jatuh_tempo >= ?) or pen17.tanggal_jatuh_tempo is null) group by pen17.custid), 0) as lebih_empat_bulan, COALESCE((select (sum(pen18.nominal_bayar)) from penagihan as pen18 where pen18.custid = pen.custid and pen18.lunas = 0 and pen18.tanggal_tagih_cust is not null and pen18.tanggal_jatuh_tempo is not null and pen18.tanggal_tagih_cust > pen18.tanggal_jatuh_tempo and (pen18.tanggal_do between ? and ?) group by pen18.custid), 0) as bg_mundur, (select(saldo_akhir)) as saldo from penagihan as pen where pen.custid like 'B%' and (pen.tanggal_do between ? and ?) group by pen.custid", [$request->from_date, $month_to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->from_date, $request->to_date, $request->from_date, $request->from_date, $request->to_date, $request->from_date, $request->to_date]);
            }
        }else{
            $data = DB::select("select pen.custid, pen.custname, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen2 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen2.nosj=pena2.nosj and pen2.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen2.nosj=pena3.nosj and pen2.itemid=pena3.itemid where pen2.custid = pen.custid and pen2.lunas = 0 and pen2.hitung_bayar is null and (pen2.tanggal_do between ? and ?) and ((pen2.tanggal_jatuh_tempo between ? and ?) or pen2.tanggal_jatuh_tempo is null) group by pen2.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen3 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen3.nosj=pena2.nosj and pen3.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen3.nosj=pena3.nosj and pen3.itemid=pena3.itemid where pen3.custid = pen.custid and pen3.lunas = 1 and (pen3.tanggal_pelunasan between ? and ?) group by pen3.custid), 0) + COALESCE((select sum(pen4.sisa_bayar) as total from penagihan as pen4 where pen4.custid = pen.custid and pen4.lunas = 0 and pen4.hitung_bayar is not null and (pen4.tanggal_do between ? and ?) and pen4.tanggal_pelunasan < ? and ((pen4.tanggal_jatuh_tempo between ? and ?) or pen4.tanggal_jatuh_tempo is null) group by pen4.custid), 0) + COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen5 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen5.nosj=pena2.nosj and pen5.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen5.nosj=pena3.nosj and pen5.itemid=pena3.itemid where pen5.custid = pen.custid and pen5.lunas = 0 and pen5.hitung_bayar = 1 and (pen5.tanggal_do between ? and ?) and pen5.tanggal_pelunasan >= ? and ((pen5.tanggal_jatuh_tempo between ? and ?) or pen5.tanggal_jatuh_tempo is null) group by pen5.custid), 0) + COALESCE((select (sum(pen6.sisa_bayar)) from penagihan as pen6 where pen6.custid = pen.custid and pen6.lunas = 0 and pen6.hitung_bayar > 1 and (pen6.tanggal_do between ? and ?) and (pen6.tanggal_pelunasan between ? and ?) and ((pen6.tanggal_jatuh_tempo between ? and ?) or pen6.tanggal_jatuh_tempo is null) group by pen6.custid), 0) + COALESCE((select (sum(peld.nominal + peld.other)) from penagihan as pen7 left join pelunasan_detail as peld on peld.noinv = pen7.noinv and peld.custid = pen7.custid left join pelunasan as pel on pel.noar = peld.noar where pen7.custid = pen.custid and pen7.lunas = 0 and pen7.hitung_bayar > 1 and (pen7.tanggal_do between ? and ?) and (pel.tanggal between ? and ?) and ((pen7.tanggal_jatuh_tempo between ? and ?) or pen7.tanggal_jatuh_tempo is null) group by pen7.custid), 0) as saldo_awal, COALESCE((select count(distinct pen8.nosj) from penagihan as pen8 where pen8.custid = pen.custid and (pen8.tanggal_do between ? and ?) group by pen8.custid), 0) as jumlah_fak, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen9 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen9.nosj=pena2.nosj and pen9.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen9.nosj=pena3.nosj and pen9.itemid=pena3.itemid where pen9.custid = pen.custid and (pen9.tanggal_do between ? and ?) group by pen9.custid), 0) as nilai_fak, COALESCE((select sum(peld.nominal + peld.other) from pelunasan as pel join pelunasan_detail as peld on peld.noar = pel.noar where peld.custid = pen.custid and (pel.tanggal between ? and ?) group by peld.custid), 0) as pembayaran, (select(saldo_awal)) + (select(nilai_fak)) - (select(pembayaran)) as saldo_akhir, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen10 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen10.nosj=pena2.nosj and pen10.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen10.nosj=pena3.nosj and pen10.itemid=pena3.itemid where pen10.custid = pen.custid and pen10.lunas = 0 and pen10.hitung_bayar is null and pen10.tanggal_jatuh_tempo <= ? and pen10.tanggal_jatuh_tempo > ? - interval 2 month group by pen10.custid), 0) + COALESCE((select sum(pen11.sisa_bayar) from penagihan as pen11 where pen11.custid = pen.custid and pen11.lunas = 0 and pen11.hitung_bayar is not null and pen11.tanggal_jatuh_tempo <= ? and pen11.tanggal_jatuh_tempo > ? - interval 2 month group by pen11.custid), 0) as nol_dua_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen12 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen12.nosj=pena2.nosj and pen12.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen12.nosj=pena3.nosj and pen12.itemid=pena3.itemid where pen12.custid = pen.custid and pen12.lunas = 0 and pen12.hitung_bayar is null and pen12.tanggal_jatuh_tempo <= ? - interval 2 month and pen12.tanggal_jatuh_tempo > ? - interval 3 month group by pen12.custid), 0) + COALESCE((select sum(pen13.sisa_bayar) from penagihan as pen13 where pen13.custid = pen.custid and pen13.lunas = 0 and pen13.hitung_bayar is not null and pen13.tanggal_jatuh_tempo <= ? - interval 2 month and pen13.tanggal_jatuh_tempo > ? - interval 3 month group by pen13.custid), 0) as dua_tiga_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen14 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen14.nosj=pena2.nosj and pen14.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen14.nosj=pena3.nosj and pen14.itemid=pena3.itemid where pen14.custid = pen.custid and pen14.lunas = 0 and pen14.hitung_bayar is null and pen14.tanggal_jatuh_tempo <= ? - interval 3 month and pen14.tanggal_jatuh_tempo > ? - interval 4 month group by pen14.custid), 0) + COALESCE((select sum(pen15.sisa_bayar) from penagihan as pen15 where pen15.custid = pen.custid and pen15.lunas = 0 and pen15.hitung_bayar is not null and pen15.tanggal_jatuh_tempo <= ? - interval 3 month and pen15.tanggal_jatuh_tempo > ? - interval 4 month group by pen15.custid), 0) as tiga_empat_bulan, COALESCE((select (coalesce(sum(pena2.sum1), 0) + coalesce(sum(pena3.sum2), 0)) from penagihan as pen16 left join (select (sum(penag.sub_amount) - sum(penag.diskon)) as sum1, penag.nosj, penag.itemid from penagihan as penag where penag.ppn = 0 group by penag.nosj) pena2 on pen16.nosj=pena2.nosj and pen16.itemid=pena2.itemid left join (select (sum(penag.sub_amount) - sum(penag.diskon)) + (penag.percent_ppn * (sum(penag.sub_amount) - sum(penag.diskon)) / 100) as sum2, penag.nosj, penag.itemid from penagihan as penag where penag.ppn > 0 group by penag.nosj) pena3 on pen16.nosj=pena3.nosj and pen16.itemid=pena3.itemid where pen16.custid = pen.custid and pen16.lunas = 0 and pen16.hitung_bayar is null and ((pen16.tanggal_jatuh_tempo <= ? - interval 4 month and pen16.tanggal_jatuh_tempo >= ?) or pen16.tanggal_jatuh_tempo is null) group by pen16.custid), 0) + COALESCE((select sum(pen17.sisa_bayar) from penagihan as pen17 where pen17.custid = pen.custid and pen17.lunas = 0 and pen17.hitung_bayar is not null and ((pen17.tanggal_jatuh_tempo <= ? - interval 4 month and pen17.tanggal_jatuh_tempo >= ?) or pen17.tanggal_jatuh_tempo is null) group by pen17.custid), 0) as lebih_empat_bulan, COALESCE((select (sum(pen18.nominal_bayar)) from penagihan as pen18 where pen18.custid = pen.custid and pen18.lunas = 0 and pen18.tanggal_tagih_cust is not null and pen18.tanggal_jatuh_tempo is not null and pen18.tanggal_tagih_cust > pen18.tanggal_jatuh_tempo and (pen18.tanggal_do between ? and ?) group by pen18.custid), 0) as bg_mundur, (select(saldo_akhir)) as saldo from penagihan as pen where pen.custid = ? and (pen.tanggal_do between ? and ?) group by pen.custid", [$request->from_date, $month_to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $request->from_date, $month_to_date, $to_date_awal, $request->to_date, $request->from_date, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $to_date_awal, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->to_date, $request->from_date, $request->to_date, $request->from_date, $request->from_date, $request->to_date, $request->customers, $request->from_date, $request->to_date]);
        }

        $pdf = PDF::loadView('print_aging_schedule', ['data' => $data, 'from_date' => $request->from_date, 'to_date' => $request->to_date])->setPaper('a4', 'landscape')->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream();
    }

    public function downloadExcelAgingSchedule($from_date, $to_date, $group, $custid){
        $val_from_date = $this->decrypt($from_date);
        $val_to_date = $this->decrypt($to_date);
        $val_custid = $this->decrypt($custid);
        $val_group = $this->decrypt($group);

        if($val_custid == null || $val_custid == ''){
            $nama_file = 'Laporan Aging Schedule Tanggal ' . $val_from_date . ' sd ' . $val_to_date . ' All Cust.xlsx';
        }else{
            $nama_file = 'Laporan Aging Schedule Tanggal ' . $val_from_date . ' sd ' . $val_to_date . ' Cust ' . $val_custid . '.xlsx';
        }

        return Excel::download(new AgingScheduleExport($val_from_date, $val_to_date, $val_custid, $val_group), $nama_file);
    }
}
