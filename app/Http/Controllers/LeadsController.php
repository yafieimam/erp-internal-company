<?php

namespace App\Http\Controllers;

use App\ModelLeads;
use App\ModelKompetitor;
use App\ModelNpwp;
use App\ModelKota;
use App\ModelProvinsi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
Use Exception;
use Response;
use Mail;
use File;
use Notification;
use PDF;
use Excel;
use App\Imports\LeadsImport;
use App\Imports\KompetitorImport;

class LeadsController extends Controller
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

    public function viewPageLeads(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('input_leads');
        }
    }

    public function viewPageKompetitor(){
        if(!Session::get('login_admin')){
            return redirect('/')->with('alert','You Do Not Have an Authorization');
        }else{
            return view('input_kompetitor');
        }
    }

    public function viewLeadsSpecificTable(Request $request){
        if(request()->ajax()){
            $leads = DB::table('leads as lead')->select("lead.leadid", "lead.nama as name", "lead.bidang_usaha")->join("kota", "kota.id_kota", "=", "lead.city")->where('lead.leadid', $request->nomor)->get();

            return datatables()->of($leads)->addIndexColumn()->make(true);
        }
        return view('input_leads');
    }

    public function viewLeadsListTable(Request $request){
        if(request()->ajax()){
            if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
            	if(!empty($request->from_date)){
            		$leads = DB::table('leads as lead')->select("lead.leadid", "lead.nama as name", "lead.bidang_usaha")->join("kota", "kota.id_kota", "=", "lead.city")->where('status', 1)->whereBetween('lead.created_at', array($request->from_date, $request->to_date))->get();
            	}else{
            		$leads = DB::table('leads as lead')->select("lead.leadid", "lead.nama as name", "lead.bidang_usaha")->join("kota", "kota.id_kota", "=", "lead.city")->where('status', 1)->get();
            	}
            }else{
                if(!empty($request->from_date)){
                    $leads = DB::table('leads as lead')->select("lead.leadid", "lead.nama as name", "lead.bidang_usaha")->join("kota", "kota.id_kota", "=", "lead.city")->where('status', 1)->where('id_sales', Session::get('id_user_admin'))->whereBetween('lead.created_at', array($request->from_date, $request->to_date))->get();
                }else{
                    $leads = DB::table('leads as lead')->select("lead.leadid", "lead.nama as name", "lead.bidang_usaha")->join("kota", "kota.id_kota", "=", "lead.city")->where('status', 1)->where('id_sales', Session::get('id_user_admin'))->get();
                }
            }

            return datatables()->of($leads)->addIndexColumn()->make(true);
        }
        return view('input_leads');
    }

    public function inputLeads(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'company' => 'required',
            'address' => 'required',
            'crd' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'input_npwp' => 'required|in:yes,no',
            'npwp' => 'required_if:input_npwp,yes',
            'upload_image_npwp' => 'required_if:input_npwp,yes|nullable|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data_kota = ModelKota::where('id_kota', $request->get('city'))->first();
        $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
        $nama_user = strtoupper(str_replace(' ', '', $request->name));
        $kode_nama = substr($nama_user, 0, 5);

        $tanggal_history = date('Y');

        if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
            $sales_id = NULL;
        }else{
            $sales_id = Session::get('id_user_admin');
        }

        $kode_lead = 'LEAD' . $data_provinsi->kode . $data_kota->kode . $kode_nama;
        $data_leadid = ModelLeads::where('leadid', 'like', '%' . $kode_lead . '%')->orderBy('leadid', 'asc')->get();

        if($data_leadid){
        	$data_count = $data_leadid->count();
        	if($data_count > 0){
        		$num = (int) substr($data_leadid[$data_leadid->count() - 1]->leadid, 14);
        		if($data_count != $num){
        			$kode_lead = ++$data_leadid[$data_leadid->count() - 1]->leadid;
        		}else{
        			if($data_count < 9){
        				$kode_lead = $kode_lead . "0" . ($data_count + 1);
        			}else{
        				$kode_lead = $kode_lead . ($data_count + 1);
        			}
        		}
        	}else{
        		$kode_lead = $kode_lead . "01";
        	}
        }else{
        	$kode_lead = $kode_lead . "01";
        }

        if($request->input_npwp == 'yes'){

            if($request->hasFile('upload_image_ktp')) {

                $npwp = substr($request->npwp, 13, 3);
                $kota = ModelNpwp::select("id_kota")->where([
                    ['nomor_npwp', '=', $npwp],
                    ['id_kota', '=', $request->get('city')],
                ])->first();

                $file_npwp = $request->file('upload_image_npwp');
                $nama_file_npwp = time()."_NPWP_".$kode_lead."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                $file_ktp = $request->file('upload_image_ktp');
                $nama_file_ktp = time()."_KTP_".$kode_lead."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                if($kota){
                    $data =  new ModelLeads();
                    $data->leadid = $kode_lead;
                    $data->id_sales = $sales_id;
                    $data->nama = $request->name;
                    $data->company = $request->company;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->crd = $request->crd;
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = $request->npwp;
                    $data->image_npwp = $nama_file_npwp;
                    $data->nik = $request->nik;
                    $data->image_nik = $nama_file_ktp;
                    $data->nama_cp = $request->nama_cp;
                    $data->jabatan_cp = $request->jabatan_cp;
                    $data->bidang_usaha = $request->bidang_usaha;
                    $data->status = 1;
                    $data->created_by = Session::get('id_user_admin');
                    $data->updated_by = Session::get('id_user_admin');
                    $data->save();

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_lead, 'status' => 2, 'action' => 'Data Leads ' . $kode_lead . ' Dibuat Oleh Admin']);

                    $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                    return Response()->json($arr);

                }else{
                    File::delete('data_file/' . $nama_file_npwp);
                    File::delete('data_file/' . $nama_file_ktp);

                    $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                    return Response()->json($arr);
                }

            }else{
                $npwp = substr($request->npwp, 13, 3);
                $kota = ModelNpwp::select("id_kota")->where([
                    ['nomor_npwp', '=', $npwp],
                    ['id_kota', '=', $request->get('city')],
                ])->first();

                $file_npwp = $request->file('upload_image_npwp');
                $nama_file_npwp = time()."_NPWP_".$kode_lead."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                if($kota){
                    $data =  new ModelLeads();
                    $data->leadid = $kode_lead;
                    $data->id_sales = $sales_id;
                    $data->nama = $request->name;
                    $data->company = $request->company;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->phone = $request->phone;
                    $data->crd = $request->crd;
                    $data->fax = $request->fax;
                    $data->npwp = $request->npwp;
                    $data->image_npwp = $nama_file_npwp;
                    $data->nik = $request->nik;
                    $data->nama_cp = $request->nama_cp;
                    $data->jabatan_cp = $request->jabatan_cp;
                    $data->bidang_usaha = $request->bidang_usaha;
                    $data->status = 1;
                    $data->created_by = Session::get('id_user_admin');
                    $data->updated_by = Session::get('id_user_admin');
                    $data->save();

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_lead, 'status' => 2, 'action' => 'Data Leads ' . $kode_lead . ' Dibuat Oleh Admin']);

                    $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                    return Response()->json($arr);

                }else{
                    File::delete('data_file/' . $nama_file_npwp);

                    $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                    return Response()->json($arr);
                }
            }
        }else{
            if($request->hasFile('upload_image_ktp')) {
                $file_ktp = $request->file('upload_image_ktp');
                $nama_file_ktp = time()."_KTP_".$kode_lead."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                $data =  new ModelLeads();
                $data->leadid = $kode_lead;
                $data->id_sales = $sales_id;
                $data->nama = $request->name;
                $data->company = $request->company;
                $data->address = $request->address;
                $data->crd = $request->crd;
                $data->city = $request->get('city');
                $data->phone = $request->phone;
                $data->fax = $request->fax;
                $data->npwp = "00.000.000.0-000.000";
                $data->nik = $request->nik;
                $data->image_nik = $nama_file_ktp;
                $data->nama_cp = $request->nama_cp;
                $data->jabatan_cp = $request->jabatan_cp;
                $data->bidang_usaha = $request->bidang_usaha;
                $data->status = 1;
                $data->created_by = Session::get('id_user_admin');
                $data->updated_by = Session::get('id_user_admin');
                $data->save();

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_lead, 'status' => 2, 'action' => 'Data Leads ' . $kode_lead . ' Dibuat Oleh Admin']);

                $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }else{
                $data =  new ModelLeads();
                $data->leadid = $kode_lead;
                $data->id_sales = $sales_id;
                $data->nama = $request->name;
                $data->company = $request->company;
                $data->address = $request->address;
                $data->crd = $request->crd;
                $data->city = $request->get('city');
                $data->phone = $request->phone;
                $data->fax = $request->fax;
                $data->npwp = "00.000.000.0-000.000";
                $data->nik = $request->nik;
                $data->nama_cp = $request->nama_cp;
                $data->jabatan_cp = $request->jabatan_cp;
                $data->bidang_usaha = $request->bidang_usaha;
                $data->status = 1;
                $data->created_by = Session::get('id_user_admin');
                $data->updated_by = Session::get('id_user_admin');
                $data->save();

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_lead, 'status' => 2, 'action' => 'Data Leads ' . $kode_lead . ' Dibuat Oleh Admin']);

                $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }
    }

    public function viewLeads($leadid){
    	$val_leadid = $this->decrypt($leadid);

        $leads = DB::table('leads as lead')->select("lead.leadid", "lead.nama as name", "lead.company", "lead.nama_cp", "lead.jabatan_cp", "lead.bidang_usaha", "lead.crd", "lead.phone", "lead.fax", "lead.npwp", "lead.nik", "lead.image_npwp", "lead.image_nik", "lead.address", "kota.name as city", "lead.city as city_name", "lead.created_at", "lead.created_by", "lead.updated_at")->join("kota", "kota.id_kota", "=", "lead.city")->where('lead.leadid', $val_leadid)->first();

        return Response()->json($leads);
    }

    public function editLeads(Request $request){
        $this->validate($request, [
            'name_edit' => 'required',
            'address_edit' => 'required',
            'crd_edit' => 'required',
            'city_edit' => 'required',
            'phone_edit' => 'required',
            'fax_edit' => 'required',
            'upload_image_ktp_edit' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'upload_image_npwp_edit' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('upload_image_ktp_edit') && $request->hasFile('upload_image_npwp_edit')) {
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($npwp == '000'){
                    $data_foto = ModelLeads::select('image_npwp', 'image_nik')->where('leadid', $request->leadid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->leadid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->leadid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    ModelLeads::where("leadid", $request->leadid_edit)->update([
                        "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                	DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else{
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city_edit')],
                    ])->first();

                    $data_foto = ModelLeads::select('image_npwp', 'image_nik')->where('leadid', $request->leadid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->leadid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->leadid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    if($kota){
                        ModelLeads::where("leadid", $request->leadid_edit)->update([
                            "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                		DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                $data_foto = ModelLeads::select('image_npwp', 'image_nik')->where('leadid', $request->leadid_edit)->first();
                File::delete('data_file/' . $data_foto->image_npwp);
                File::delete('data_file/' . $data_foto->image_nik);

                $file_npwp = $request->file('upload_image_npwp_edit');
                $nama_file_npwp = time()."_NPWP_".$request->leadid_edit."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                $file_ktp = $request->file('upload_image_ktp_edit');
                $nama_file_ktp = time()."_KTP_".$request->leadid_edit."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                ModelLeads::where("leadid", $request->leadid_edit)->update([
                    "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                ]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }else if($request->hasFile('upload_image_npwp_edit')){
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($npwp == '000'){
                    $data_foto = ModelLeads::select('image_npwp')->where('leadid', $request->leadid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->leadid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    ModelLeads::where("leadid", $request->leadid_edit)->update([
                        "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                	DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else{
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city_edit')],
                    ])->first();

                    $data_foto = ModelLeads::select('image_npwp')->where('leadid', $request->leadid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->leadid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    if($kota){
                        ModelLeads::where("leadid", $request->leadid_edit)->update([
                            "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                		DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                $data_foto = ModelLeads::select('image_npwp')->where('leadid', $request->leadid_edit)->first();
                File::delete('data_file/' . $data_foto->image_npwp);

                $file_npwp = $request->file('upload_image_npwp_edit');
                $nama_file_npwp = time()."_NPWP_".$request->leadid_edit."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                ModelLeads::where("leadid", $request->leadid_edit)->update([
                    "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                ]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }else if($request->hasFile('upload_image_ktp_edit')){
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($npwp == '000'){
                    $data_foto = ModelLeads::select('image_nik')->where('leadid', $request->leadid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->leadid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    ModelLeads::where("leadid", $request->leadid_edit)->update([
                        "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                	DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else{
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city_edit')],
                    ])->first();

                    $data_foto = ModelLeads::select('image_nik')->where('leadid', $request->leadid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->leadid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    if($kota){
                        ModelLeads::where("leadid", $request->leadid_edit)->update([
                            "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                		DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                $data_foto = ModelLeads::select('image_nik')->where('leadid', $request->leadid_edit)->first();
                File::delete('data_file/' . $data_foto->image_nik);

                $file_ktp = $request->file('upload_image_ktp_edit');
                $nama_file_ktp = time()."_KTP_".$request->leadid_edit."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                ModelLeads::where("leadid", $request->leadid_edit)->update([
                    "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                ]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }else{
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($npwp == '000'){
                    ModelLeads::where("leadid", $request->leadid_edit)->update([
                        "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                	DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else{
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city_edit')],
                    ])->first();

                    if($kota){
                        ModelLeads::where("leadid", $request->leadid_edit)->update([
                            "nama" => $request->name_edit, "address" => $request->address_edit, "crd" => $request->crd_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                		DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                ModelLeads::where("leadid", $request->leadid_edit)->update([
                    "nama" => $request->name_edit, "address" => $request->address_edit, "crd" => $request->crd_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                ]);

                datdate_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->leadid_edit, 'status' => 3, 'action' => 'Data Leads ' . $request->leadid_edit . ' Diubah / Diedit Oleh Admin']);

                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }
    }

    public function rejectLeads(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $reject = ModelLeads::where('leadid', $request->get('leadid'))->delete();

        if($reject){
            $arr = array('msg' => 'Data Rejected Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->get('leadid'), 'status' => 5, 'action' => 'Data Leads ' . $request->get('leadid') . ' Direject / Dihapus Oleh Admin']);

        return Response()->json($arr);
    } 

    public function pembagianSalesLeads(Request $request){
        if(request()->ajax()){
            $pembagian = ModelLeads::select('leadid', 'id_sales', 'nama', 'com.nama_perusahaan as company', 'leads.company as kode_company', 'address', 'kota.name as city')->leftJoin('kota', 'kota.id_kota', '=', 'leads.city')->leftJoin('company as com', 'com.kode_perusahaan', '=', 'leads.company')->where('status', 1)->whereNull('id_sales')->get();

            return datatables()->of($pembagian)->make(true);
        }
        return view('input_leads');
    }

    public function savePembagianSalesLeads(Request $request){
        $tanggal_schedule = date('ym');

        $sales = $request->get('sales');
        $leadid = $request->get('leadid');
        $pilih_sales = $request->get('pilih_sales');
        $offline = $request->get('offline');
        $perihal = $request->get('perihal');
        $tanggal_kunjungan = $request->get('tanggal_kunjungan');
        $kode_company = $request->get('kode_company');

        foreach($leadid as $nomor) {
            if(is_array($pilih_sales) && array_key_exists($nomor,$pilih_sales)){
                ModelLeads::where('leadid', $nomor)->update(['id_sales' => $sales, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_leads')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $nomor, 'status' => 3, 'action' => ' Data Leads ' . $nomor . ' Telah Melakukan Pembagian Sales oleh Admin']);

                if($perihal[$nomor] != '' || $perihal[$nomor] != null){
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
                                    $kode_schedule = "FLWUP-" . $tanggal_schedule . ($schedule_count + 1);
                                }
                            }
                        }else{
                            $kode_schedule = "FLWUP" . $tanggal_schedule . "-000001";
                        }
                    }else{
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-000001";
                    }

                    $cek_data = DB::table('customers_visit')->select('order_sort')->where('tanggal_schedule', date('Y-m-d', strtotime($tanggal_kunjungan[$nomor])))->where('id_user', $sales)->orderBy('order_sort', 'asc')->distinct()->get();

                    if($cek_data->count() > 0){
                        $sort = ++$cek_data[$cek_data->count() - 1]->order_sort;
                    }else{
                        $sort = 1;
                    }

                    if(is_array($offline) && array_key_exists($nomor,$offline)){
                        DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => $sales, "company" => $kode_company[$nomor], "tipe_customer" => 2, "customers" => $nomor, "perihal" => $perihal[$nomor], "offline" => 1, "tanggal_schedule" => $tanggal_kunjungan[$nomor], "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);
                    }else{
                        DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => $sales, "company" => $kode_company[$nomor], "tipe_customer" => 2, "customers" => $nomor, "perihal" => $perihal[$nomor], "offline" => 0, "tanggal_schedule" => $tanggal_kunjungan[$nomor], "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);
                    }

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $sales, 'status' => 1, 'action' => 'User ' . $sales . ' Input Data Customer Visit No. ' . $kode_schedule . ' Melalui Pembagian Sales']);
                }
            }
        }

        return Response()->json();
    }

    public function selectSalesLeads(){
        $data = DB::table('users')->select('id_user as id_sales', 'nama_admin as nama_sales')->where('id_customer_type', 20)->get();

        return Response()->json($data);
    }

    public function viewKompetitorSpecificTable(Request $request){
        if(request()->ajax()){
            $kompetitor = DB::table('kompetitor as kom')->select("kom.kompid", "kom.nama as name", "kom.bidang_usaha")->join("kota", "kota.id_kota", "=", "kom.city")->where('kom.kompid', $request->nomor)->get();

            return datatables()->of($kompetitor)->addIndexColumn()->make(true);
        }
        return view('input_kompetitor');
    }

    public function viewKompetitorListTable(Request $request){
        if(request()->ajax()){
            if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
            	if(!empty($request->from_date)){
            		$kompetitor = DB::table('kompetitor as kom')->select("kom.kompid", "kom.nama as name", "kom.bidang_usaha")->join("kota", "kota.id_kota", "=", "kom.city")->whereBetween('kom.created_at', array($request->from_date, $request->to_date))->get();
            	}else{
            		$kompetitor = DB::table('kompetitor as kom')->select("kom.kompid", "kom.nama as name", "kom.bidang_usaha")->join("kota", "kota.id_kota", "=", "kom.city")->get();
            	}
            }else{
                if(!empty($request->from_date)){
                    $kompetitor = DB::table('kompetitor as kom')->select("kom.kompid", "kom.nama as name", "kom.bidang_usaha")->join("kota", "kota.id_kota", "=", "kom.city")->where('id_sales', Session::get('id_user_admin'))->whereBetween('kom.created_at', array($request->from_date, $request->to_date))->get();
                }else{
                    $kompetitor = DB::table('kompetitor as kom')->select("kom.kompid", "kom.nama as name", "kom.bidang_usaha")->join("kota", "kota.id_kota", "=", "kom.city")->where('id_sales', Session::get('id_user_admin'))->get();
                }
            }

            return datatables()->of($kompetitor)->addIndexColumn()->make(true);
        }
        return view('input_kompetitor');
    }

    public function inputKompetitor(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'company' => 'required',
            'address' => 'required',
            'crd' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'input_npwp' => 'required|in:yes,no',
            'npwp' => 'required_if:input_npwp,yes',
            'upload_image_npwp' => 'required_if:input_npwp,yes|nullable|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
            $sales_id = NULL;
        }else{
            $sales_id = Session::get('id_user_admin');
        }

        $data_kota = ModelKota::where('id_kota', $request->get('city'))->first();
        $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
        $nama_user = strtoupper(str_replace(' ', '', $request->name));
        $kode_nama = substr($nama_user, 0, 5);

        $tanggal_history = date('Y');

        $kode_komp = 'KOMP' . $data_provinsi->kode . $data_kota->kode . $kode_nama;
        $data_kompid = ModelKompetitor::where('kompid', 'like', '%' . $kode_komp . '%')->orderBy('kompid', 'asc')->get();

        if($data_kompid){
        	$data_count = $data_kompid->count();
        	if($data_count > 0){
        		$num = (int) substr($data_kompid[$data_komp->count() - 1]->kompid, 15);
        		if($data_count != $num){
        			$kode_komp = ++$data_kompid[$data_kompid->count() - 1]->kompid;
        		}else{
        			if($data_count < 9){
        				$kode_komp = $kode_komp . "0" . ($data_count + 1);
        			}else{
        				$kode_komp = $kode_komp . ($data_count + 1);
        			}
        		}
        	}else{
        		$kode_komp = $kode_komp . "01";
        	}
        }else{
        	$kode_komp = $kode_komp . "01";
        }

        if($request->input_npwp == 'yes'){

            if($request->hasFile('upload_image_ktp')) {

                $npwp = substr($request->npwp, 13, 3);
                $kota = ModelNpwp::select("id_kota")->where([
                    ['nomor_npwp', '=', $npwp],
                    ['id_kota', '=', $request->get('city')],
                ])->first();

                $file_npwp = $request->file('upload_image_npwp');
                $nama_file_npwp = time()."_NPWP_".$kode_komp."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                $file_ktp = $request->file('upload_image_ktp');
                $nama_file_ktp = time()."_KTP_".$kode_komp."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                if($kota){
                    $data =  new ModelKompetitor();
                    $data->kompid = $kode_komp;
                    $data->id_sales = $sales_id;
                    $data->nama = $request->name;
                    $data->company = $request->company;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->crd = $request->crd;
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = $request->npwp;
                    $data->image_npwp = $nama_file_npwp;
                    $data->nik = $request->nik;
                    $data->image_nik = $nama_file_ktp;
                    $data->nama_cp = $request->nama_cp;
                    $data->jabatan_cp = $request->jabatan_cp;
                    $data->bidang_usaha = $request->bidang_usaha;
                    $data->created_by = Session::get('id_user_admin');
                    $data->updated_by = Session::get('id_user_admin');
                    $data->save();

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_komp, 'status' => 2, 'action' => 'Data Kompetitor ' . $kode_komp . ' Dibuat Oleh Admin']);

                    $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                    return Response()->json($arr);

                }else{
                    File::delete('data_file/' . $nama_file_npwp);
                    File::delete('data_file/' . $nama_file_ktp);

                    $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                    return Response()->json($arr);
                }

            }else{
                $npwp = substr($request->npwp, 13, 3);
                $kota = ModelNpwp::select("id_kota")->where([
                    ['nomor_npwp', '=', $npwp],
                    ['id_kota', '=', $request->get('city')],
                ])->first();

                $file_npwp = $request->file('upload_image_npwp');
                $nama_file_npwp = time()."_NPWP_".$kode_komp."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                if($kota){
                    $data =  new ModelKompetitor();
                    $data->kompid = $kode_komp;
                    $data->id_sales = $sales_id;
                    $data->nama = $request->name;
                    $data->company = $request->company;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->phone = $request->phone;
                    $data->crd = $request->crd;
                    $data->fax = $request->fax;
                    $data->npwp = $request->npwp;
                    $data->image_npwp = $nama_file_npwp;
                    $data->nik = $request->nik;
                    $data->nama_cp = $request->nama_cp;
                    $data->jabatan_cp = $request->jabatan_cp;
                    $data->bidang_usaha = $request->bidang_usaha;
                    $data->created_by = Session::get('id_user_admin');
                    $data->updated_by = Session::get('id_user_admin');
                    $data->save();

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_komp, 'status' => 2, 'action' => 'Data Kompetitor ' . $kode_komp . ' Dibuat Oleh Admin']);

                    $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                    return Response()->json($arr);

                }else{
                    File::delete('data_file/' . $nama_file_npwp);

                    $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                    return Response()->json($arr);
                }
            }
        }else{
            if($request->hasFile('upload_image_ktp')) {
                $file_ktp = $request->file('upload_image_ktp');
                $nama_file_ktp = time()."_KTP_".$kode_komp."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                $data =  new ModelKompetitor();
                $data->kompid = $kode_komp;
                $data->id_sales = $sales_id;
                $data->nama = $request->name;
                $data->company = $request->company;
                $data->address = $request->address;
                $data->crd = $request->crd;
                $data->city = $request->get('city');
                $data->phone = $request->phone;
                $data->fax = $request->fax;
                $data->npwp = "00.000.000.0-000.000";
                $data->nik = $request->nik;
                $data->image_nik = $nama_file_ktp;
                $data->nama_cp = $request->nama_cp;
                $data->jabatan_cp = $request->jabatan_cp;
                $data->bidang_usaha = $request->bidang_usaha;
                $data->created_by = Session::get('id_user_admin');
                $data->updated_by = Session::get('id_user_admin');
                $data->save();

                date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_komp, 'status' => 2, 'action' => 'Data Kompetitor ' . $kode_komp . ' Dibuat Oleh Admin']);

                $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }else{
                $data =  new ModelKompetitor();
                $data->kompid = $kode_komp;
                $data->id_sales = $sales_id;
                $data->nama = $request->name;
                $data->company = $request->company;
                $data->address = $request->address;
                $data->crd = $request->crd;
                $data->city = $request->get('city');
                $data->phone = $request->phone;
                $data->fax = $request->fax;
                $data->npwp = "00.000.000.0-000.000";
                $data->nik = $request->nik;
                $data->nama_cp = $request->nama_cp;
                $data->jabatan_cp = $request->jabatan_cp;
                $data->bidang_usaha = $request->bidang_usaha;
                $data->created_by = Session::get('id_user_admin');
                $data->updated_by = Session::get('id_user_admin');
                $data->save();

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $kode_komp, 'status' => 2, 'action' => 'Data Kompetitor ' . $kode_komp . ' Dibuat Oleh Admin']);

                $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }
    }

    public function viewKompetitor($kompid){
    	$val_kompid = $this->decrypt($kompid);

        $kompetitor = DB::table('kompetitor as kom')->select("kom.kompid", "kom.nama as name", "kom.company", "kom.nama_cp", "kom.jabatan_cp", "kom.bidang_usaha", "kom.crd", "kom.phone", "kom.fax", "kom.npwp", "kom.nik", "kom.image_npwp", "kom.image_nik", "kom.address", "kota.name as city", "kom.city as city_name", "kom.created_at", "kom.created_by", "kom.updated_at")->join("kota", "kota.id_kota", "=", "kom.city")->where('kom.kompid', $val_kompid)->first();

        return Response()->json($kompetitor);
    }

    public function editKompetitor(Request $request){
        $this->validate($request, [
            'name_edit' => 'required',
            'address_edit' => 'required',
            'crd_edit' => 'required',
            'city_edit' => 'required',
            'phone_edit' => 'required',
            'fax_edit' => 'required',
            'upload_image_ktp_edit' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'upload_image_npwp_edit' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('upload_image_ktp_edit') && $request->hasFile('upload_image_npwp_edit')) {
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($npwp == '000'){
                    $data_foto = ModelKompetitor::select('image_npwp', 'image_nik')->where('kompid', $request->kompid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->kompid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->kumpid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                        "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                	DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else{
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city_edit')],
                    ])->first();

                    $data_foto = ModelKompetitor::select('image_npwp', 'image_nik')->where('kompid', $request->kompid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->kompid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->kompid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    if($kota){
                        ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                            "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                		DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                $data_foto = ModelKompetitor::select('image_npwp', 'image_nik')->where('kompid', $request->kompid_edit)->first();
                File::delete('data_file/' . $data_foto->image_npwp);
                File::delete('data_file/' . $data_foto->image_nik);

                $file_npwp = $request->file('upload_image_npwp_edit');
                $nama_file_npwp = time()."_NPWP_".$request->kompid_edit."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                $file_ktp = $request->file('upload_image_ktp_edit');
                $nama_file_ktp = time()."_KTP_".$request->kompid_edit."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                    "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                ]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }else if($request->hasFile('upload_image_npwp_edit')){
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($npwp == '000'){
                    $data_foto = ModelKompetitor::select('image_npwp')->where('kompid', $request->kompid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->kompid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                        "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                	DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else{
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city_edit')],
                    ])->first();

                    $data_foto = ModelKompetitor::select('image_npwp')->where('kompid', $request->kompid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->kompid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    if($kota){
                        ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                            "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                		DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                $data_foto = ModelKompetitor::select('image_npwp')->where('kompid', $request->kompid_edit)->first();
                File::delete('data_file/' . $data_foto->image_npwp);

                $file_npwp = $request->file('upload_image_npwp_edit');
                $nama_file_npwp = time()."_NPWP_".$request->kompid_edit."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                    "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                ]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }else if($request->hasFile('upload_image_ktp_edit')){
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($npwp == '000'){
                    $data_foto = ModelKompetitor::select('image_nik')->where('kompid', $request->kompid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->kompid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                        "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                	DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else{
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city_edit')],
                    ])->first();

                    $data_foto = ModelKompetitor::select('image_nik')->where('kompid', $request->kompid_edit)->first();
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->kompid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    if($kota){
                        ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                            "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                		DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                $data_foto = ModelKompetitor::select('image_nik')->where('kompid', $request->kompid_edit)->first();
                File::delete('data_file/' . $data_foto->image_nik);

                $file_ktp = $request->file('upload_image_ktp_edit');
                $nama_file_ktp = time()."_KTP_".$request->kompid_edit."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                    "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                ]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }else{
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($npwp == '000'){
                    ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                        "nama" => $request->name_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                	DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else{
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city_edit')],
                    ])->first();

                    if($kota){
                        ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                            "nama" => $request->name_edit, "address" => $request->address_edit, "crd" => $request->crd_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                		DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                ModelKompetitor::where("kompid", $request->kompid_edit)->update([
                    "nama" => $request->name_edit, "address" => $request->address_edit, "crd" => $request->crd_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "updated_at" => date('Y-m-d'), "updated_by" => Session::get('id_user_admin')
                ]);

                datdate_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->kompid_edit, 'status' => 3, 'action' => 'Data Kompetitor ' . $request->kompid_edit . ' Diubah / Diedit Oleh Admin']);

                $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                return Response()->json($arr);
            }
        }
    }

    public function rejectKompetitor(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $reject = ModelKompetitor::where('kompid', $request->get('kompid'))->delete();

        if($reject){
            $arr = array('msg' => 'Data Rejected Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $request->get('kompid'), 'status' => 5, 'action' => 'Data Kompetitor ' . $request->get('kompid') . ' Direject / Dihapus Oleh Admin']);

        return Response()->json($arr);
    }

    public function pembagianSalesKompetitor(Request $request){
        if(request()->ajax()){
            $pembagian = ModelKompetitor::select('kompid', 'id_sales', 'nama', 'com.nama_perusahaan as company', 'kompetitor.company as kode_company', 'address', 'kota.name as city')->leftJoin('kota', 'kota.id_kota', '=', 'kompetitor.city')->leftJoin('company as com', 'com.kode_perusahaan', '=', 'kompetitor.company')->whereNull('id_sales')->get();

            return datatables()->of($pembagian)->make(true);
        }
        return view('input_kompetitor');
    }

    public function savePembagianSalesKompetitor(Request $request){
        $tanggal_schedule = date('ym');

        $sales = $request->get('sales');
        $kompid = $request->get('kompid');
        $pilih_sales = $request->get('pilih_sales');
        $offline = $request->get('offline');
        $perihal = $request->get('perihal');
        $tanggal_kunjungan = $request->get('tanggal_kunjungan');
        $kode_company = $request->get('kode_company');

        foreach($kompid as $nomor) {
            if(is_array($pilih_sales) && array_key_exists($nomor,$pilih_sales)){
                ModelKompetitor::where('kompid', $nomor)->update(['id_sales' => $sales, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_kompetitor')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id' => $nomor, 'status' => 3, 'action' => ' Data Kompetitor ' . $nomor . ' Telah Melakukan Pembagian Sales oleh Admin']);

                if($perihal[$nomor] != '' || $perihal[$nomor] != null){
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
                                    $kode_schedule = "FLWUP-" . $tanggal_schedule . ($schedule_count + 1);
                                }
                            }
                        }else{
                            $kode_schedule = "FLWUP" . $tanggal_schedule . "-000001";
                        }
                    }else{
                        $kode_schedule = "FLWUP" . $tanggal_schedule . "-000001";
                    }

                    $cek_data = DB::table('customers_visit')->select('order_sort')->where('tanggal_schedule', date('Y-m-d', strtotime($tanggal_kunjungan[$nomor])))->where('id_user', $sales)->orderBy('order_sort', 'asc')->distinct()->get();

                    if($cek_data->count() > 0){
                        $sort = ++$cek_data[$cek_data->count() - 1]->order_sort;
                    }else{
                        $sort = 1;
                    }

                    if(is_array($offline) && array_key_exists($nomor,$offline)){
                        DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => $sales, "company" => $kode_company[$nomor], "tipe_customer" => 3, "customers" => $nomor, "perihal" => $perihal[$nomor], "offline" => 1, "tanggal_schedule" => $tanggal_kunjungan[$nomor], "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);
                    }else{
                        DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => $sales, "company" => $kode_company[$nomor], "tipe_customer" => 3, "customers" => $nomor, "perihal" => $perihal[$nomor], "offline" => 0, "tanggal_schedule" => $tanggal_kunjungan[$nomor], "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);
                    }

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $sales, 'status' => 1, 'action' => 'User ' . $sales . ' Input Data Customer Visit No. ' . $kode_schedule . ' Melalui Pembagian Sales']);
                }
            }
        }

        return Response()->json();
    }

    public function uploadExcelLeads(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new LeadsImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        if($import->getDuplikat() == 0){
            return redirect('sales/leads')->with('alert','Data Duplikat, Data Sudah Ada');
        }else{
            return redirect('sales/leads')->with('alert','Sukses Menambahkan Data');
        }
    }

    public function uploadExcelKompetitor(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new KompetitorImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        if($import->getDuplikat() == 0){
            return redirect('sales/kompetitor')->with('alert','Data Duplikat, Data Sudah Ada');
        }else{
            return redirect('sales/kompetitor')->with('alert','Sukses Menambahkan Data');
        }
    }
}
