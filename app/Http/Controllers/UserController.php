<?php

namespace App\Http\Controllers;
use App\ModelUser;
use App\ModelCustomers;
use App\ModelKota;
use App\ModelNpwp;
use App\ModelProvinsi;
use App\ModelComplaintCust;
use App\ModelAlamatHistory;
use App\ModelLeads;
use App\ModelKompetitor;
use App\Notifications\NotifNewCustomers;
use App\Notifications\NotifNewComplaint;
use App\Imports\CustomersImport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Notification;
use File;
use Excel;

class UserController extends Controller
{
    protected $encryptMethod = 'AES-256-CBC';
    
    public function index(){
        if(!Session::get('login')){
            return redirect('en/login')->with('alert','You Must Login First');
        }
        else{
            return view('/');
        }
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

    public function testNotification(){
        $data = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

        $new = ModelCustomers::select('custid', 'custname')->where('custid', 'JTMSDAYAFIE01')->first();

        // $data->notify(new NotifNewCustomers);

        Notification::send($data, new NotifNewCustomers($new));
    }

    public function loginPostEN(Request $request){

        $email = $request->email;
        $password = $request->password;

        Session::forget('email');
        Session::put('email', $request->email);

        $data = ModelUser::where('email',$email)->first();
        if($data){ //apakah email tersebut ada atau tidak
            if(Hash::check($password,$data->password)){
                if($data->id_customer_type != 4){
                    if($data->status == 2){
                        Session::forget('email');
                        Session::put('email',$data->email);
                        Session::put('nama_admin',$data->nama_admin);
                        Session::put('tipe_user',$data->id_customer_type);
                        Session::put('id_user_admin',$data->id_user);
                        Session::put('id_user_class',$data->id_user_class);
                        Session::put('login_admin',TRUE);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_login')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $data->id_user, 'status_login' => 1, 'action' => 'Akun ' . $data->nama_admin . ' Melakukan Login ke Website']);

                        return redirect('/homepage')->with('alert','Login Successful');
                    }else{
                        return redirect('en/login')->with('alert','The Account Has Not Been Validated\nPlease Wait For Further Confirmation');
                    }
                }else{
                    $data2 = ModelCustomers::where('custid', $data->custid)->first();
                    if($data2){
                        if($data->status == 2){
                            Session::forget('email');
                            Session::put('name',$data2->custname);
                            Session::put('email',$data->email);
                            Session::put('custid',$data2->custid);
                            Session::put('tipe_user',$data->id_customer_type);
                            Session::put('login',TRUE);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_login')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $data2->custid, 'status_login' => 1, 'action' => 'Akun ' . $data2->custname . ' Melakukan Login ke Website']);

                            return redirect('/')->with('alert','Login Successful');
                        }else{
                            return redirect('en/login')->with('alert','The Account Has Not Been Validated\nPlease Wait For Further Confirmation');
                        }
                    }else{
                        Session::forget('email');
                        ModelUser::where('email', $email)->where('id_customer_type', 4)->delete();

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 6, 'action' => 'Akun Dengan Email ' . $request->email . ' Dihapus oleh Admin Karena Login Tanpa memiliki Data']);

                        return redirect('en/login')->with('alert','There is No Customers Data\nPlease Sign Up');
                    }
                }
            }
            else{
                return redirect('en/login')->with('alert','Wrong Email or Password\nIf You Do Not Have an Account, Please Sign Up');
            }
        }
        else{
            Session::forget('email');
            ModelUser::where('email', $email)->where('id_customer_type', 4)->delete();

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 6, 'action' => 'Akun Dengan Email ' . $request->email . ' Dihapus oleh Admin Karena Login Tanpa Memiliki Data']);

            return redirect('en/login')->with('alert','Wrong Email or Password\nIf You Do Not Have an Account, Please Sign Up');
        }
    }

    public function logoutEN(){
        date_default_timezone_set('Asia/Jakarta');
        if(Session::get('id_user_admin')){
            DB::table('logbook_login')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status_login' => 0, 'action' => 'Akun ' . Session::get('nama_admin') . ' Melakukan Logout dari Website']);
        }else if(Session::get('custid')){
            DB::table('logbook_login')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('custid'), 'status_login' => 0, 'action' => 'Akun ' . Session::get('name') . ' Melakukan Logout dari Website']);
        }

        Session::flush();
        return redirect('/')->with('alert','You Have Logged Out');
    }

    public function registerPostEN(Request $request){
        Session::forget('email');
        Session::put('email', $request->email);

        $this->validate($request, [
            'email' => 'required|min:4|email|unique:users',
            'password' => 'required',
            'confirm_pass' => 'required|same:password',
        ]);

        $data =  new ModelUser();
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->id_customer_type = 4;
        $data->status = 1;
        $data->save();
        $data2 = ModelUser::select("id_user", "id_customer_type")->where('email', $request->email)->first();
        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 0, 'action' => 'User Dengan Email ' . $request->email . ' Baru Saja Membuat Akun Baru']);
        if($data2){
            Session::forget('email');
            Session::put('userid', $data2->id_user);
            Session::put('tipe_user', $data2->id_customer_type);
            return redirect('en/fill_data');
        }else{
            return redirect('en/register')->with('alert','Error\nEmail and Password Not Registered');
        }
    }

    public function loginPostID(Request $request){

        $email = $request->email;
        $password = $request->password;

        Session::forget('email');
        Session::put('email', $request->email);

        $data = ModelUser::where('email',$email)->first();
        if($data){ //apakah email tersebut ada atau tidak
            if(Hash::check($password,$data->password)){
                if($data->id_customer_type != 4){
                    if($data->status == 2){
                        Session::forget('email');
                        Session::put('email',$data->email);
                        Session::put('nama_admin',$data->nama_admin);
                        Session::put('tipe_user',$data->id_customer_type);
                        Session::put('id_user_admin',$data->id_user);
                        Session::put('id_user_class',$data->id_user_class);
                        Session::put('login_admin',TRUE);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_login')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $data->id_user, 'status_login' => 1, 'action' => 'Akun ' . $data->nama_admin . ' Melakukan Login ke Website']);

                        return redirect('/homepage')->with('alert','Login Berhasil');
                    }else{
                        return redirect('en/login')->with('alert','Akun Belum Divalidasi\nSilahkan Tunggu Konfirmasi Lebih Lanjut');
                    }
                }else{
                    $data2 = ModelCustomers::where('custid', $data->custid)->first();
                    if($data2){
                        if($data->status == 2){
                            Session::forget('email');
                            Session::put('name',$data2->custname);
                            Session::put('email',$data->email);
                            Session::put('custid',$data2->custid);
                            Session::put('tipe_user',$data->id_customer_type);
                            Session::put('login',TRUE);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_login')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $data2->custid, 'status_login' => 1, 'action' => 'Akun ' . $data2->custname . ' Melakukan Login ke Website']);

                            return redirect('id')->with('alert','Login Berhasil');
                        }else{
                            return redirect('en/login')->with('alert','Akun Belum Divalidasi\nSilahkan Tunggu Konfirmasi Lebih Lanjut');
                        }
                    }else{
                        Session::forget('email');
                        ModelUser::where('email', $email)->where('id_customer_type', 4)->delete();

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 6, 'action' => 'Akun Dengan Email ' . $request->email . ' Dihapus oleh Admin Karena Login Tanpa Memiliki Data']);

                        return redirect('id/login')->with('alert','Data Customer Tidak Ada\nSilahkan Buat Akun');
                    }
                }
            }
            else{
                return redirect('id/login')->with('alert','Email atau Password Salah\nJika Belum Memiliki Akun, Silahkan Daftar Terlebih Dahulu');
            }
        }
        else{
            Session::forget('email');
            ModelUser::where('email', $email)->where('id_customer_type', 4)->delete();

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 6, 'action' => 'Akun Dengan Email ' . $request->email . ' Dihapus oleh Admin Karena Login Tanpa Memiliki Data']);

            return redirect('id/login')->with('alert','Email atau Password Salah\nJika Belum Memiliki Akun, Silahkan Daftar Terlebih Dahulu');
        }
    }

    public function logoutID(){
        date_default_timezone_set('Asia/Jakarta');
        if(Session::get('id_user_admin')){
            DB::table('logbook_login')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status_login' => 0, 'action' => 'Akun ' . Session::get('nama_admin') . ' Melakukan Logout dari Website']);
        }else if(Session::get('custid')){
            DB::table('logbook_login')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('custid'), 'status_login' => 0, 'action' => 'Akun ' . Session::get('name') . ' Melakukan Logout dari Website']);
        }
        
        Session::flush();
        return redirect('id')->with('alert','Anda Telah Keluar');
    }

    public function registerPostID(Request $request){
        Session::forget('email');
        Session::put('email', $request->email);

        $this->validate($request, [
            'email' => 'required|min:4|email|unique:users',
            'password' => 'required',
            'confirm_pass' => 'required|same:password',
        ]);

        $data =  new ModelUser();
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->id_customer_type = 4;
        $data->status = 1;
        $data->save();
        $data2 = ModelUser::select("id_user", "id_customer_type")->where('email', $request->email)->first();
        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 0, 'action' => 'User Dengan Email ' . $request->email . ' Baru Saja Membuat Akun Baru']);
        if($data2){
            Session::forget('email');
            Session::put('userid', $data2->id_user);
            Session::put('tipe_user', $data2->id_customer_type);
            return redirect('id/fill_data');
        }else{
            return redirect('id/register')->with('alert','Error\nEmail dan Password belum terdaftar');
        }
    }

    // public function updatePostEN(Request $request){
    //     $this->validate($request, [
    //         'email' => 'required|min:4|email|unique:users',
    //         'password' => 'required',
    //         'confirm_pass' => 'required|same:password',
    //     ]);

    //     $data =  new ModelUser();
    //     $data->id_customer = $request->get('itemName');        
    //     $data->email = $request->email;
    //     $data->password = bcrypt($request->password);
    //     $data->save();

    //     $data2 = ModelUser::select("id_user")->where('id_customer', $request->get('itemName'))->first();

    //     ModelCustomers::where('id_customer', $request->get('itemName'))->update([
    //         'usersid' => $data2->id_user
    //     ]);

    //     return redirect('en/login')->with('alert','Update Data Successful');
    // }

    // public function updatePostID(Request $request){
    //     $this->validate($request, [
    //         'email' => 'required|min:4|email|unique:users',
    //         'password' => 'required',
    //         'confirm_pass' => 'required|same:password',
    //     ]);

    //     $data =  new ModelUser();
    //     $data->id_customer = $request->get('itemName');        
    //     $data->email = $request->email;
    //     $data->password = bcrypt($request->password);
    //     $data->save();

    //     $data2 = ModelUser::select("id_user")->where('id_customer', $request->get('itemName'))->first();

    //     ModelCustomers::where('id_customer', $request->get('itemName'))->update([
    //         'usersid' => $data2->id_user
    //     ]);

    //     return redirect('id/login')->with('alert','Berhasil Menambahkan Data');
    // }

    // public function search(Request $request){
    //     if($request->ajax()){
    //         $output="";
    //         $customers=ModelCustomers::where('custname','LIKE','%'.$request->search."%")->get();
    //         if($customers){
    //             foreach ($customers as $key => $customer) {
    //                 $output.=
    //                 '<tr onclick="window.location.assign(http://www.google.com);">'.
    //                 '<td></td>'.
    //                 '<td>'.$customer->custid.'</td>'.
    //                 '<td>'.$customer->custname.'</td>'.
    //                 '<td>'.$customer->address.'</td>'.
    //                 '<td>'.$customer->city.'</td>'.
    //                 '</tr>';
    //             }
    //             return Response($output);
    //         }
    //     }
    // }

    public function customerAddEN(Request $request){
        Session::flush();
        Session::put('name', $request->name);
        Session::put('address', $request->address);
        Session::put('wraddress', $request->wraddress);
        Session::put('phone', $request->phone);
        Session::put('nik', $request->nik);
        Session::put('npwp', $request->npwp);
        Session::put('fax', $request->fax);
        Session::put('userid', $request->usersid);
        Session::put('tipe_user', $request->id_customer_type);

        $this->validate($request, [
            'usersid' => 'required',
            'id_customer_type' => 'required',
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'wraddress' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'input_npwp' => 'required|in:yes,no',
            'npwp' => 'required_if:input_npwp,yes',
            'nik' => 'required_if:input_npwp,no',
            'upload_image_ktp' => 'required_with:nik',
            'upload_image_npwp' => 'required_if:input_npwp,yes',
        ]);

        $data_kota = ModelKota::where('id_kota', $request->get('city'))->first();
        $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
        $nama_user = strtoupper(str_replace(' ', '', $request->name));
        $kode_nama = substr($nama_user, 0, 5);

        $kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;
        $data_custid = ModelCustomers::where('custid', 'like', '%' . $kode_cust . '%')->orderBy('custid', 'asc')->get();

        $tanggal_history = date('Y');

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

        if($request->input_npwp == 'yes'){

            if ($request->hasFile('upload_image_ktp')) {

                $npwp = substr($request->npwp, 13, 3);
                $kota = ModelNpwp::select("id_kota")->where([
                    ['nomor_npwp', '=', $npwp],
                    ['id_kota', '=', $request->get('city')],
                ])->first();

                $file_npwp = $request->file('upload_image_npwp');
                $nama_file_npwp = time()."_NPWP_".$kode_cust."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                $file_ktp = $request->file('upload_image_ktp');
                $nama_file_ktp = time()."_KTP_".$kode_cust."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                if($kota){
                    $data =  new ModelCustomers();
                    $data->custid = $kode_cust;
                    $data->usersid = $request->usersid;
                    $data->custname = $request->name;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->wraddress = $request->wraddress;
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = $request->npwp;
                    $data->image_npwp = $nama_file_npwp;
                    $data->nik = $request->nik;
                    $data->image_nik = $nama_file_ktp;
                    $data->created_by = 8;
                    $data->updated_by = 8;
                    $data->save();

                    $data2 = ModelCustomers::select("custid")->where('usersid', $request->usersid)->first();

                    ModelUser::where('id_user', $request->usersid)->update([
                        'custid' => $data2->custid
                    ]);

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
                    $alamat->custname_receive = $request->name;
                    $alamat->address_receive = $request->address;
                    $alamat->city_receive = $request->get('city');
                    $alamat->phone_receive = $request->phone;
                    $alamat->main_address = 1;
                    $alamat->choosen = 1;
                    $alamat->save();

                    Session::flush();

                    $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

                    $new_data_notif = ModelCustomers::select('custid', 'custname')->where('custid', $kode_cust)->first();

                    Notification::send($user_notif, new NotifNewCustomers($new_data_notif));

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 0, 'action' => 'User ' . $kode_cust . ' Telah Melakukan Fill Data User']);

                    return redirect('en/login')->with('alert','Register and Fill Data Successful\nPlease Wait For The Account Validation');
                }else{
                    return redirect('en/fill_data')->with('alert','NPWP City and Address City Must Be The Same');
                }
            }else{
                $npwp = substr($request->npwp, 13, 3);
                $kota = ModelNpwp::select("id_kota")->where([
                    ['nomor_npwp', '=', $npwp],
                    ['id_kota', '=', $request->get('city')],
                ])->first();

                $file_npwp = $request->file('upload_image_npwp');
                $nama_file_npwp = time()."_NPWP_".$kode_cust."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                if($kota){
                    $data =  new ModelCustomers();
                    $data->custid = $kode_cust;
                    $data->usersid = $request->usersid;
                    $data->custname = $request->name;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->wraddress = $request->wraddress;
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = $request->npwp;
                    $data->image_npwp = $nama_file_npwp;
                    $data->nik = $request->nik;
                    $data->created_by = 8;
                    $data->updated_by = 8;
                    $data->save();

                    $data2 = ModelCustomers::select("custid")->where('usersid', $request->usersid)->first();

                    ModelUser::where('id_user', $request->usersid)->update([
                        'custid' => $data2->custid
                    ]);

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
                    $alamat->custname_receive = $request->name;
                    $alamat->address_receive = $request->address;
                    $alamat->city_receive = $request->get('city');
                    $alamat->phone_receive = $request->phone;
                    $alamat->main_address = 1;
                    $alamat->choosen = 1;
                    $alamat->save();

                    Session::flush();

                    $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

                    $new_data_notif = ModelCustomers::select('custid', 'custname')->where('custid', $kode_cust)->first();

                    Notification::send($user_notif, new NotifNewCustomers($new_data_notif));

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 0, 'action' => 'User ' . $kode_cust . ' Telah Melakukan Fill Data User']);

                    return redirect('en/login')->with('alert','Register and Fill Data Successful\nPlease Wait For The Account Validation');
                }else{
                    return redirect('en/fill_data')->with('alert','NPWP City and Address City Must Be The Same');
                }
            }
        }else{
            $file_ktp = $request->file('upload_image_ktp');
            $nama_file_ktp = time()."_KTP_".$kode_cust."_".$file_ktp->getClientOriginalName();
            $tujuan_upload_ktp = 'data_file';
            $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

            $data =  new ModelCustomers();
            $data->custid = $kode_cust;
            $data->usersid = $request->usersid;
            $data->custname = $request->name;
            $data->address = $request->address;
            $data->city = $request->get('city');
            $data->wraddress = $request->wraddress;
            $data->phone = $request->phone;
            $data->fax = $request->fax;
            $data->npwp = "00.000.000.0-000.000";
            $data->nik = $request->nik;
            $data->image_nik = $nama_file_ktp;
            $data->created_by = 8;
            $data->updated_by = 8;
            $data->save();

            $data2 = ModelCustomers::select("custid")->where('usersid', $request->usersid)->first();

            ModelUser::where('id_user', $request->usersid)->update([
                'custid' => $data2->custid
            ]);

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
            $alamat->custname_receive = $request->name;
            $alamat->address_receive = $request->address;
            $alamat->city_receive = $request->get('city');
            $alamat->phone_receive = $request->phone;
            $alamat->main_address = 1;
            $alamat->choosen = 1;
            $alamat->save();

            Session::flush();

            $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

            $new_data_notif = ModelCustomers::select('custid', 'custname')->where('custid', $kode_cust)->first();

            Notification::send($user_notif, new NotifNewCustomers($new_data_notif));

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 0, 'action' => 'User ' . $kode_cust . ' Telah Melakukan Fill Data User']);

            return redirect('en/login')->with('alert','Register and Fill Data Successful\nPlease Wait For The Account Validation');
        }
    }

    public function customerAddID(Request $request){
        Session::flush();
        Session::put('name', $request->name);
        Session::put('address', $request->address);
        Session::put('wraddress', $request->wraddress);
        Session::put('phone', $request->phone);
        Session::put('nik', $request->nik);
        Session::put('npwp', $request->npwp);
        Session::put('fax', $request->fax);
        Session::put('userid', $request->usersid);
        Session::put('tipe_user', $request->id_customer_type);

        $this->validate($request, [
            'usersid' => 'required',
            'id_customer_type' => 'required',
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'wraddress' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'input_npwp' => 'required|in:yes,no',
            'npwp' => 'required_if:input_npwp,yes',
            'nik' => 'required_if:input_npwp,no',
            'upload_image_ktp' => 'required_with:nik',
            'upload_image_npwp' => 'required_if:input_npwp,yes',
        ]);

        $data_kota = ModelKota::where('id_kota', $request->get('city'))->first();
        $data_provinsi = ModelProvinsi::where('id_provinsi', $data_kota->id_provinsi)->first();
        $nama_user = strtoupper(str_replace(' ', '', $request->name));
        $kode_nama = substr($nama_user, 0, 5);

        $kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;
        $data_custid = ModelCustomers::where('custid', 'like', '%' . $kode_cust . '%')->orderBy('custid', 'asc')->get();

        $tanggal_history = date('Y');

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

        if($request->input_npwp == 'yes'){

            if ($request->hasFile('upload_image_ktp')) {

                $npwp = substr($request->npwp, 13, 3);
                $kota = ModelNpwp::select("id_kota")->where([
                    ['nomor_npwp', '=', $npwp],
                    ['id_kota', '=', $request->get('city')],
                ])->first();

                $file_npwp = $request->file('upload_image_npwp');
                $nama_file_npwp = time()."_NPWP_".$kode_cust."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                $file_ktp = $request->file('upload_image_ktp');
                $nama_file_ktp = time()."_KTP_".$kode_cust."_".$file_ktp->getClientOriginalName();
                $tujuan_upload_ktp = 'data_file';
                $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                if($kota){
                    $data =  new ModelCustomers();
                    $data->custid = $kode_cust;
                    $data->usersid = $request->usersid;
                    $data->custname = $request->name;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->wraddress = $request->wraddress;
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = $request->npwp;
                    $data->image_npwp = $nama_file_npwp;
                    $data->nik = $request->nik;
                    $data->image_nik = $nama_file_ktp;
                    $data->created_by = 8;
                    $data->updated_by = 8;
                    $data->save();

                    $data2 = ModelCustomers::select("custid")->where('usersid', $request->usersid)->first();

                    ModelUser::where('id_user', $request->usersid)->update([
                        'custid' => $data2->custid
                    ]);

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
                    $alamat->custname_receive = $request->name;
                    $alamat->address_receive = $request->address;
                    $alamat->city_receive = $request->get('city');
                    $alamat->phone_receive = $request->phone;
                    $alamat->main_address = 1;
                    $alamat->choosen = 1;
                    $alamat->save();

                    Session::flush();

                    $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

                    $new_data_notif = ModelCustomers::select('custid', 'custname')->where('custid', $kode_cust)->first();

                    Notification::send($user_notif, new NotifNewCustomers($new_data_notif));

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 0, 'action' => 'User ' . $kode_cust . ' Telah Melakukan Fill Data User']);

                    return redirect('en/login')->with('alert','Pendaftaran dan Isi Data Berhasil\nSilahkan Tunggu Validasi Akun');

                }else{
                    return redirect('en/fill_data')->with('alert','Kota NPWP dan Kota Alamat Harus Sama');
                }

            }else{
                $npwp = substr($request->npwp, 13, 3);
                $kota = ModelNpwp::select("id_kota")->where([
                    ['nomor_npwp', '=', $npwp],
                    ['id_kota', '=', $request->get('city')],
                ])->first();

                $file_npwp = $request->file('upload_image_npwp');
                $nama_file_npwp = time()."_NPWP_".$kode_cust."_".$file_npwp->getClientOriginalName();
                $tujuan_upload_npwp = 'data_file';
                $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                if($kota){
                    $data =  new ModelCustomers();
                    $data->custid = $kode_cust;
                    $data->usersid = $request->usersid;
                    $data->custname = $request->name;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->wraddress = $request->wraddress;
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = $request->npwp;
                    $data->image_npwp = $nama_file_npwp;
                    $data->nik = $request->nik;
                    $data->created_by = 8;
                    $data->updated_by = 8;
                    $data->save();

                    $data2 = ModelCustomers::select("custid")->where('usersid', $request->usersid)->first();

                    ModelUser::where('id_user', $request->usersid)->update([
                        'custid' => $data2->custid
                    ]);

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
                    $alamat->custname_receive = $request->name;
                    $alamat->address_receive = $request->address;
                    $alamat->city_receive = $request->get('city');
                    $alamat->phone_receive = $request->phone;
                    $alamat->main_address = 1;
                    $alamat->choosen = 1;
                    $alamat->save();

                    Session::flush();

                    $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

                    $new_data_notif = ModelCustomers::select('custid', 'custname')->where('custid', $kode_cust)->first();

                    Notification::send($user_notif, new NotifNewCustomers($new_data_notif));

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 0, 'action' => 'User ' . $kode_cust . ' Telah Melakukan Fill Data User']);

                    return redirect('en/login')->with('alert','Pendaftaran dan Isi Data Berhasil\nSilahkan Tunggu Validasi Akun');

                }else{
                    return redirect('en/fill_data')->with('alert','Kota NPWP dan Kota Alamat Harus Sama');
                }
            }
        }else{
            $file_ktp = $request->file('upload_image_ktp');
            $nama_file_ktp = time()."_KTP_".$kode_cust."_".$file_ktp->getClientOriginalName();
            $tujuan_upload_ktp = 'data_file';
            $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

            $data =  new ModelCustomers();
            $data->custid = $kode_cust;
            $data->usersid = $request->usersid;
            $data->custname = $request->name;
            $data->address = $request->address;
            $data->city = $request->get('city');
            $data->wraddress = $request->wraddress;
            $data->phone = $request->phone;
            $data->fax = $request->fax;
            $data->npwp = "00.000.000.0-000.000";
            $data->nik = $request->nik;
            $data->image_nik = $nama_file_ktp;
            $data->created_by = 8;
            $data->updated_by = 8;
            $data->save();

            $data2 = ModelCustomers::select("custid")->where('usersid', $request->usersid)->first();

            ModelUser::where('id_user', $request->usersid)->update([
                'custid' => $data2->custid
            ]);

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
            $alamat->custname_receive = $request->name;
            $alamat->address_receive = $request->address;
            $alamat->city_receive = $request->get('city');
            $alamat->phone_receive = $request->phone;
            $alamat->main_address = 1;
            $alamat->choosen = 1;
            $alamat->save();

            Session::flush();

            $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

            $new_data_notif = ModelCustomers::select('custid', 'custname')->where('custid', $kode_cust)->first();

            Notification::send($user_notif, new NotifNewCustomers($new_data_notif));

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 0, 'action' => 'User ' . $kode_cust . ' Telah Melakukan Fill Data User']);

            return redirect('en/login')->with('alert','Pendaftaran dan Isi Data Berhasil\nSilahkan Tunggu Validasi Akun');
        }
    }

    public function inputCustomerAdmin(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'company' => 'required',
            'address' => 'required',
            'city' => 'required',
            'input_npwp' => 'required|in:yes,no',
            'npwp' => 'required_if:input_npwp,yes',
            'upload_image_npwp' => 'required_if:input_npwp,yes',
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

        if(isset($request->custid)){
            $kode_cust = $request->custid;
        }else{
            $kode_cust = $data_provinsi->kode . $data_kota->kode . $kode_nama;

            if($request->company == 'DSGM'){
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
            }else if($request->company == 'IMJ'){
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
            }
        }

        if($request->input_npwp == 'yes'){

            if($request->hasFile('upload_image_ktp')) {
                if($request->company == 'DSGM'){
                    $npwp = substr($request->npwp, 13, 3);
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city')],
                    ])->first();

                    $file_npwp = $request->file('upload_image_npwp');
                    $nama_file_npwp = time()."_NPWP_".$kode_cust."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    $file_ktp = $request->file('upload_image_ktp');
                    $nama_file_ktp = time()."_KTP_".$kode_cust."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    if($kota){
                        $data =  new ModelCustomers();
                        $data->custid = $kode_cust;
                        $data->id_sales = $sales_id;
                        $data->custname = $request->name;
                        $data->company = $request->company;
                        $data->address = $request->address;
                        $data->city = $request->get('city');
                        $data->wraddress = $request->wraddress;
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
                        $data->telepon_cp = $request->telepon_cp;
                        $data->created_by = Session::get('id_user_admin');
                        $data->updated_by = Session::get('id_user_admin');
                        $data->save();

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
                        $alamat->custname_receive = $request->name;
                        $alamat->address_receive = $request->wraddress;
                        $alamat->city_receive = $request->get('city');
                        $alamat->phone_receive = $request->phone;
                        $alamat->main_address = 1;
                        $alamat->choosen = 1;
                        $alamat->save();

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin']);

                        $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        File::delete('data_file/' . $nama_file_npwp);
                        File::delete('data_file/' . $nama_file_ktp);

                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }else if($request->company == 'IMJ'){
                    $npwp = substr($request->npwp, 13, 3);
                    $kota = DB::connection('mysql2')->table('npwp_table')->select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city')],
                    ])->first();

                    $file_npwp = $request->file('upload_image_npwp');
                    $file_path_npwp = $file_npwp->getPathname();
                    $file_mime_npwp = $file_npwp->getMimeType('image');
                    $file_uploaded_name_npwp = $file_npwp->getClientOriginalName();

                    $file_ktp = $request->file('upload_image_ktp');
                    $file_path_ktp = $file_ktp->getPathname();
                    $file_mime_ktp = $file_ktp->getMimeType('image');
                    $file_uploaded_name_ktp = $file_ktp->getClientOriginalName();
                    
                    $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust';
                    $client = new \GuzzleHttp\Client();

                    try {
                        $response = $client->request("POST", $uploadFileUrl, [
                            'multipart' => [
                                [
                                    'name' => 'uploaded_file_npwp',
                                    'filename' => $file_uploaded_name_npwp,
                                    'Mime-Type'=> $file_mime_npwp,
                                    'contents' => fopen($file_path_npwp, 'r'),
                                ],
                                [
                                    'name' => 'uploaded_file_ktp',
                                    'filename' => $file_uploaded_name_ktp,
                                    'Mime-Type'=> $file_mime_ktp,
                                    'contents' => fopen($file_path_ktp, 'r'),
                                ],
                                [
                                    'name' => 'kode_cust',
                                    'contents' => $kode_cust,
                                ],
                                [
                                    'name' => 'input_npwp',
                                    'contents' => $request->input_npwp,
                                ]
                            ],
                        ]);
                    } catch (Exception $e) {

                    }

                    $code   = $response->getStatusCode();
                    $response   = $response->getBody();
                    $responseData = json_decode($response, true);

                    if($kota){
                        $data =  new ModelCustomers();
                        $data->setConnection('mysql2');
                        $data->custid = $kode_cust;
                        $data->id_sales = $sales_id;
                        $data->custname = $request->name;
                        $data->company = $request->company;
                        $data->address = $request->address;
                        $data->city = $request->get('city');
                        $data->phone = $request->phone;
                        $data->fax = $request->fax;
                        $data->npwp = $request->npwp;
                        $data->image_npwp = $responseData['data']['uploadedFileNameNPWP'];
                        $data->nik = $request->nik;
                        $data->image_nik = $responseData['data']['uploadedFileNameKTP'];
                        $data->nama_cp = $request->nama_cp;
                        $data->jabatan_cp = $request->jabatan_cp;
                        $data->bidang_usaha = $request->bidang_usaha;
                        $data->telepon_cp = $request->telepon_cp;
                        $data->created_by = Session::get('id_user_admin');
                        $data->updated_by = Session::get('id_user_admin');
                        $data->save();

                        $data_alamat = DB::connection('mysql2')->table('history_alamat_receive')->select('id_alamat_receive')->where('id_alamat_receive', 'like', 'HA' . $tanggal_history . '%')->orderBy('id_alamat_receive', 'asc')->distinct()->get();

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

                        $alamat = new ModelAlamatHistory();
                        $alamat->setConnection('mysql2');
                        $alamat->id_alamat_receive = $kode_alamat;
                        $alamat->custid_order = $kode_cust;
                        $alamat->custname_receive = $request->name;
                        $alamat->address_receive = $request->address;
                        $alamat->city_receive = $request->get('city');
                        $alamat->phone_receive = $request->phone;
                        $alamat->main_address = 1;
                        $alamat->choosen = 1;
                        $alamat->save();

                        date_default_timezone_set('Asia/Jakarta');
                        DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin']);

                        $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $uploadFileUrlDel = 'http://admin.rkmortar.com/api/delete-file-cust';
                        $clientDel = new \GuzzleHttp\Client();

                        try {
                            $responseDel = $clientDel->request("POST", $uploadFileUrlDel, [
                                'multipart' => [
                                    [
                                        'name' => 'nama_file_npwp',
                                        'contents' => $responseData['data']['uploadedFileNameNPWP'],
                                    ],
                                    [
                                        'name' => 'nama_file_ktp',
                                        'contents' => $responseData['data']['uploadedFileNameKTP'],
                                    ],
                                    [
                                        'name' => 'kode_cust',
                                        'contents' => $kode_cust,
                                    ]
                                ],
                            ]);
                        } catch (Exception $e) {

                        }

                        $codeDel   = $responseDel->getStatusCode();
                        $responseDel   = $responseDel->getBody();
                        $responseDataDel = json_decode($responseDel, true);

                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }else{
                if($request->company == 'DSGM'){
                    $npwp = substr($request->npwp, 13, 3);
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city')],
                    ])->first();

                    $file_npwp = $request->file('upload_image_npwp');
                    $nama_file_npwp = time()."_NPWP_".$kode_cust."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    if($kota){
                        $data =  new ModelCustomers();
                        $data->custid = $kode_cust;
                        $data->id_sales = $sales_id;
                        $data->custname = $request->name;
                        $data->company = $request->company;
                        $data->address = $request->address;
                        $data->city = $request->get('city');
                        $data->wraddress = $request->wraddress;
                        $data->phone = $request->phone;
                        $data->crd = $request->crd;
                        $data->fax = $request->fax;
                        $data->npwp = $request->npwp;
                        $data->image_npwp = $nama_file_npwp;
                        $data->nik = $request->nik;
                        $data->nama_cp = $request->nama_cp;
                        $data->jabatan_cp = $request->jabatan_cp;
                        $data->bidang_usaha = $request->bidang_usaha;
                        $data->telepon_cp = $request->telepon_cp;
                        $data->created_by = Session::get('id_user_admin');
                        $data->updated_by = Session::get('id_user_admin');
                        $data->save();

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
                        $alamat->custname_receive = $request->name;
                        $alamat->address_receive = $request->wraddress;
                        $alamat->city_receive = $request->get('city');
                        $alamat->phone_receive = $request->phone;
                        $alamat->main_address = 1;
                        $alamat->choosen = 1;
                        $alamat->save();

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin']);

                        $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        File::delete('data_file/' . $nama_file_npwp);

                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }else if($request->company == 'IMJ'){
                    $npwp = substr($request->npwp, 13, 3);
                    $kota = ModelNpwp::select("id_kota")->where([
                        ['nomor_npwp', '=', $npwp],
                        ['id_kota', '=', $request->get('city')],
                    ])->first();

                    $file_npwp = $request->file('upload_image_npwp');
                    $file_path = $file_npwp->getPathname();
                    $file_mime = $file_npwp->getMimeType('image');
                    $file_uploaded_name = $file_npwp->getClientOriginalName();
                    
                    $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust';
                    $client = new \GuzzleHttp\Client();

                    try {
                        $response = $client->request("POST", $uploadFileUrl, [
                            'multipart' => [
                                [
                                    'name' => 'uploaded_file_npwp',
                                    'filename' => $file_uploaded_name,
                                    'Mime-Type'=> $file_mime,
                                    'contents' => fopen($file_path, 'r'),
                                ],
                                [
                                    'name' => 'kode_cust',
                                    'contents' => $kode_cust,
                                ],
                                [
                                    'name' => 'input_npwp',
                                    'contents' => $request->input_npwp,
                                ]
                            ],
                        ]);
                    } catch (Exception $e) {

                    }

                    $code   = $response->getStatusCode();
                    $response   = $response->getBody();
                    $responseData = json_decode($response, true);

                    if($kota){
                        $data =  new ModelCustomers();
                        $data->setConnection('mysql2');
                        $data->custid = $kode_cust;
                        $data->id_sales = $sales_id;
                        $data->custname = $request->name;
                        $data->company = $request->company;
                        $data->address = $request->address;
                        $data->city = $request->get('city');
                        $data->phone = $request->phone;
                        $data->fax = $request->fax;
                        $data->npwp = $request->npwp;
                        $data->image_npwp = $responseData['data']['uploadedFileNameNPWP'];
                        $data->nik = $request->nik;
                        $data->nama_cp = $request->nama_cp;
                        $data->jabatan_cp = $request->jabatan_cp;
                        $data->bidang_usaha = $request->bidang_usaha;
                        $data->telepon_cp = $request->telepon_cp;
                        $data->created_by = Session::get('id_user_admin');
                        $data->updated_by = Session::get('id_user_admin');
                        $data->save();

                        $data_alamat = DB::connection('mysql2')->table('history_alamat_receive')->select('id_alamat_receive')->where('id_alamat_receive', 'like', 'HA' . $tanggal_history . '%')->orderBy('id_alamat_receive', 'asc')->distinct()->get();

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
                        $alamat->setConnection('mysql2');
                        $alamat->id_alamat_receive = $kode_alamat;
                        $alamat->custid_order = $kode_cust;
                        $alamat->custname_receive = $request->name;
                        $alamat->address_receive = $request->address;
                        $alamat->city_receive = $request->get('city');
                        $alamat->phone_receive = $request->phone;
                        $alamat->main_address = 1;
                        $alamat->choosen = 1;
                        $alamat->save();

                        date_default_timezone_set('Asia/Jakarta');
                        DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin']);

                        $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $uploadFileUrlDel = 'http://admin.rkmortar.com/api/delete-file-cust';
                        $clientDel = new \GuzzleHttp\Client();

                        try {
                            $responseDel = $clientDel->request("POST", $uploadFileUrlDel, [
                                'multipart' => [
                                    [
                                        'name' => 'nama_file_npwp',
                                        'contents' => $responseData['data']['uploadedFileNameNPWP'],
                                    ],
                                    [
                                        'name' => 'kode_cust',
                                        'contents' => $kode_cust,
                                    ],
                                    [
                                        'name' => 'input_npwp',
                                        'contents' => $request->input_npwp,
                                    ]
                                ],
                            ]);
                        } catch (Exception $e) {

                        }

                        $codeDel   = $responseDel->getStatusCode();
                        $responseDel   = $responseDel->getBody();
                        $responseDataDel = json_decode($responseDel, true);

                        $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                        return Response()->json($arr);
                    }
                }
            }
        }else{
            if($request->hasFile('upload_image_ktp')) {
                if($request->company == 'DSGM'){
                    $file_ktp = $request->file('upload_image_ktp');
                    $nama_file_ktp = time()."_KTP_".$kode_cust."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    $data =  new ModelCustomers();
                    $data->custid = $kode_cust;
                    $data->id_sales = $sales_id;
                    $data->custname = $request->name;
                    $data->company = $request->company;
                    $data->address = $request->address;
                    $data->crd = $request->crd;
                    $data->city = $request->get('city');
                    $data->wraddress = $request->wraddress;
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = "00.000.000.0-000.000";
                    $data->nik = $request->nik;
                    $data->image_nik = $nama_file_ktp;
                    $data->nama_cp = $request->nama_cp;
                    $data->jabatan_cp = $request->jabatan_cp;
                    $data->bidang_usaha = $request->bidang_usaha;
                    $data->telepon_cp = $request->telepon_cp;
                    $data->created_by = Session::get('id_user_admin');
                    $data->updated_by = Session::get('id_user_admin');
                    $data->save();

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
                    $alamat->custname_receive = $request->name;
                    $alamat->address_receive = $request->wraddress;
                    $alamat->city_receive = $request->get('city');
                    $alamat->phone_receive = $request->phone;
                    $alamat->main_address = 1;
                    $alamat->choosen = 1;
                    $alamat->save();

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin']);

                    $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else if($request->company == 'IMJ'){
                    $file_ktp = $request->file('upload_image_ktp');
                    $file_path = $file_ktp->getPathname();
                    $file_mime = $file_ktp->getMimeType('image');
                    $file_uploaded_name = $file_ktp->getClientOriginalName();
                    
                    $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust';
                    $client = new \GuzzleHttp\Client();

                    try {
                        $response = $client->request("POST", $uploadFileUrl, [
                            'multipart' => [
                                [
                                    'name' => 'uploaded_file_ktp',
                                    'filename' => $file_uploaded_name,
                                    'Mime-Type'=> $file_mime,
                                    'contents' => fopen($file_path, 'r'),
                                ],
                                [
                                    'name' => 'kode_cust',
                                    'contents' => $kode_cust,
                                ],
                                [
                                    'name' => 'input_npwp',
                                    'contents' => $request->input_npwp,
                                ]
                            ],
                        ]);
                    } catch (Exception $e) {

                    }

                    $code   = $response->getStatusCode();
                    $response   = $response->getBody();
                    $responseData = json_decode($response, true);

                    $data =  new ModelCustomers();
                    $data->setConnection('mysql2');
                    $data->custid = $kode_cust;
                    $data->id_sales = $sales_id;
                    $data->custname = $request->name;
                    $data->company = $request->company;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = "00.000.000.0-000.000";
                    $data->nik = $request->nik;
                    $data->image_nik = $responseData['data']['uploadedFileNameKTP'];
                    $data->nama_cp = $request->nama_cp;
                    $data->jabatan_cp = $request->jabatan_cp;
                    $data->bidang_usaha = $request->bidang_usaha;
                    $data->telepon_cp = $request->telepon_cp;
                    $data->created_by = Session::get('id_user_admin');
                    $data->updated_by = Session::get('id_user_admin');
                    $data->save();

                    $data_alamat = DB::connection('mysql2')->table('history_alamat_receive')->select('id_alamat_receive')->where('id_alamat_receive', 'like', 'HA' . $tanggal_history . '%')->orderBy('id_alamat_receive', 'asc')->distinct()->get();

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
                    $alamat->setConnection('mysql2');
                    $alamat->id_alamat_receive = $kode_alamat;
                    $alamat->custid_order = $kode_cust;
                    $alamat->custname_receive = $request->name;
                    $alamat->address_receive = $request->address;
                    $alamat->city_receive = $request->get('city');
                    $alamat->phone_receive = $request->phone;
                    $alamat->main_address = 1;
                    $alamat->choosen = 1;
                    $alamat->save();

                    date_default_timezone_set('Asia/Jakarta');
                    DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin']);

                    $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                }
            }else{
                if($request->company == 'DSGM'){
                    $data =  new ModelCustomers();
                    $data->custid = $kode_cust;
                    $data->id_sales = $sales_id;
                    $data->custname = $request->name;
                    $data->company = $request->company;
                    $data->address = $request->address;
                    $data->crd = $request->crd;
                    $data->city = $request->get('city');
                    $data->wraddress = $request->wraddress;
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = "00.000.000.0-000.000";
                    $data->nik = $request->nik;
                    $data->nama_cp = $request->nama_cp;
                    $data->jabatan_cp = $request->jabatan_cp;
                    $data->bidang_usaha = $request->bidang_usaha;
                    $data->telepon_cp = $request->telepon_cp;
                    $data->created_by = Session::get('id_user_admin');
                    $data->updated_by = Session::get('id_user_admin');
                    $data->save();

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
                    $alamat->custname_receive = $request->name;
                    $alamat->address_receive = $request->wraddress;
                    $alamat->city_receive = $request->get('city');
                    $alamat->phone_receive = $request->phone;
                    $alamat->main_address = 1;
                    $alamat->choosen = 1;
                    $alamat->save();

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin']);

                    $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else if($request->company == 'IMJ'){
                    $data =  new ModelCustomers();
                    $data->setConnection('mysql2');
                    $data->custid = $kode_cust;
                    $data->id_sales = $sales_id;
                    $data->custname = $request->name;
                    $data->company = $request->company;
                    $data->address = $request->address;
                    $data->city = $request->get('city');
                    $data->phone = $request->phone;
                    $data->fax = $request->fax;
                    $data->npwp = "00.000.000.0-000.000";
                    $data->nik = $request->nik;
                    $data->nama_cp = $request->nama_cp;
                    $data->jabatan_cp = $request->jabatan_cp;
                    $data->bidang_usaha = $request->bidang_usaha;
                    $data->telepon_cp = $request->telepon_cp;
                    $data->created_by = Session::get('id_user_admin');
                    $data->updated_by = Session::get('id_user_admin');
                    $data->save();

                    $data_alamat = DB::connection('mysql2')->table('history_alamat_receive')->select('id_alamat_receive')->where('id_alamat_receive', 'like', 'HA' . $tanggal_history . '%')->orderBy('id_alamat_receive', 'asc')->distinct()->get();

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
                    $alamat->setConnection('mysql2');
                    $alamat->id_alamat_receive = $kode_alamat;
                    $alamat->custid_order = $kode_cust;
                    $alamat->custname_receive = $request->name;
                    $alamat->address_receive = $request->address;
                    $alamat->city_receive = $request->get('city');
                    $alamat->phone_receive = $request->phone;
                    $alamat->main_address = 1;
                    $alamat->choosen = 1;
                    $alamat->save();

                    date_default_timezone_set('Asia/Jakarta');
                    DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $kode_cust, 'status_user' => 1, 'action' => 'User ' . $kode_cust . ' Dibuat Oleh Admin']);

                    $arr = array('msg' => 'Pendaftaran dan Isi Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }
            }
        }
    }    

    public function dataAjax(Request $request){
        $data = [];

        if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10 || Session::get('tipe_user') == 17){
            if($request->has('q')){
                $search = $request->q;
                $data = ModelCustomers::select("custid","custname")
                ->where('company', $request->company)
                ->where('custname','LIKE',"%$search%")
                ->get();
            }else{
                $data = ModelCustomers::select("custid","custname")
                ->where('company', $request->company)
                ->get();
            }
        }else{
            if($request->has('q')){
                $search = $request->q;
                $data = ModelCustomers::select("custid","custname")
                ->where('company', $request->company)
                ->where('custname','LIKE',"%$search%")
                ->where('id_sales', Session::get('id_user_admin'))
                ->get();
            }else{
                $data = ModelCustomers::select("custid","custname")
                ->where('company', $request->company)
                ->where('id_sales', Session::get('id_user_admin'))
                ->get();
            }
        }
        return response()->json($data);
    }

    public function dataAjaxLeads(Request $request){
        $data = [];

        if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
            if($request->has('q')){
                $search = $request->q;
                $data = ModelLeads::select("leadid","nama")
                        ->where('nama','LIKE',"%$search%")
                        ->get();
            }else{
                $data = ModelLeads::select("leadid","nama")
                        ->get();
            }
        }else{
            if($request->has('q')){
                $search = $request->q;
                $data = ModelLeads::select("leadid","nama")
                        ->where('nama','LIKE',"%$search%")
                        ->where('id_sales', Session::get('id_user_admin'))
                        ->get();
            }else{
                $data = ModelLeads::select("leadid","nama")
                        ->where('id_sales', Session::get('id_user_admin'))
                        ->get();
            }
        }
        return response()->json($data);
    }

    public function dataAjaxKompetitor(Request $request){
        $data = [];

        if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
            if($request->has('q')){
                $search = $request->q;
                $data = ModelKompetitor::select("kompid","nama")
                        ->where('nama','LIKE',"%$search%")
                        ->get();
            }else{
                $data = ModelKompetitor::select("kompid","nama")
                        ->get();
            }
        }else{
            if($request->has('q')){
                $search = $request->q;
                $data = ModelKompetitor::select("kompid","nama")
                        ->where('nama','LIKE',"%$search%")
                        ->where('id_sales', Session::get('id_user_admin'))
                        ->get();
            }else{
                $data = ModelKompetitor::select("kompid","nama")
                        ->where('id_sales', Session::get('id_user_admin'))
                        ->get();
            }
        }
        return response()->json($data);
    }

    public function dataKota(Request $request){
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = ModelKota::select("id_kota","name")
                    ->where('name','LIKE',"%$search%")
                    ->get();
        }else{
            $data = ModelKota::select("id_kota","name")
                    ->get();
        }
        return response()->json($data);
    }

    public function fillDataEN(){
        if(!Session::get('login')){
            if(!Session::get('login_admin')){
                return view('filldata_en');
            }else{
                return redirect('/home')->with('alert','You Are Already Login');
            }
            return view('filldata_en');
        }else{
            return redirect('/')->with('alert','You Are Already Login');
        }
        // return view('filldata_en');
    }

    public function fillDataID(){
        if(!Session::get('login')){
            if(!Session::get('login_admin')){
                return view('filldata_id');
            }else{
                return redirect('/home')->with('alert','You Are Already Login');
            }
            return view('filldata_id');
        }else{
            return redirect('/')->with('alert','Anda Sudah Login');
        }
    }

    public function uploadExcelCustomers(Request $request) 
    {
        $this->validate($request, [
            'upload_excel' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('upload_excel');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_excel',$nama_file);
        $import = new CustomersImport;
        Excel::import($import, public_path('/file_excel/'.$nama_file));
        File::delete('file_excel/'.$nama_file);
        if($import->getDuplikat() == 0){
            return redirect('sales/customers')->with('alert','Data Duplikat, Data Sudah Ada');
        }else{
            return redirect('sales/customers')->with('alert','Sukses Menambahkan Data');
        }
    }

    public function complaintEN(){
        return view('form_complaint_en');
    }

    public function complaintID(){
        return view('form_complaint_id');
    }

    public function complaintProcessEN(Request $request){
        Session::forget('nomor_surat_jalan');
        Session::forget('complaint');
        Session::put('nomor_surat_jalan', $request->nomor_surat_jalan);
        Session::put('complaint', $request->complaint);

        $this->validate($request, [
            'custid' => 'required',
            'name' => 'required',
            'complaint' => 'required',
            'upload_file' => 'file|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $tanggal = date('ym');
        // $str_tanggal = $tanggal->format('Y-m');
        $data_complaint = ModelComplaintCust::where('nomor_complaint', 'like', $tanggal . '%')->orderBy('nomor_complaint', 'asc')->get();

        if($data_complaint){
            $data_count = $data_complaint->count();
            if($data_count > 0){
                $num = (int) substr($data_complaint[$data_complaint->count() - 1]->nomor_complaint, 4);
                if($data_count != $num){
                    $kode_complaint = ++$data_complaint[$data_complaint->count() - 1]->nomor_complaint;
                }else{
                    if($data_count < 9){
                        $kode_complaint = $tanggal . "00000" . ($data_count + 1);
                    }else if($data_count >= 9 && $data_count < 99){
                        $kode_complaint = $tanggal . "0000" . ($data_count + 1);
                    }else if($data_count >= 99 && $data_count < 999){
                        $kode_complaint = $tanggal . "000" . ($data_count + 1);
                    }else if($data_count >= 999 && $data_count < 9999){
                        $kode_complaint = $tanggal . "00" . ($data_count + 1);
                    }else if($data_count >= 9999 && $data_count < 99999){
                        $kode_complaint = $tanggal . "0" . ($data_count + 1);
                    }else{
                        $kode_complaint = $tanggal . ($data_count + 1);
                    }
                }
            }else{
               $kode_complaint = $tanggal . "000001"; 
            }
        }else{
            $kode_complaint = $tanggal . "000001";
        }

        if ($request->hasFile('upload_file')) {

            $file = $request->file('upload_file');
            $nama_file = time()."_".$request->custid."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data =  new ModelComplaintCust();
            $data->nomor_complaint = $kode_complaint;
            $data->tanggal_complaint = date('Y-m-d');
            $data->custid = $request->custid;
            $data->nama_customer = $request->name;
            $data->nomor_surat_jalan = $request->nomor_surat_jalan;
            $data->complaint = $request->complaint;
            $data->lampiran = $nama_file;
            $data->created_by = 8;
            $data->save();
            
            Session::forget('nomor_surat_jalan');
            Session::forget('complaint');

            $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

            $new_data_notif = ModelComplaintCust::select('nomor_complaint', 'custid', 'nama_customer')->where('nomor_complaint', $kode_complaint)->first();

            Notification::send($user_notif, new NotifNewComplaint($new_data_notif));

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $kode_complaint, 'divisi' => 9, 'action' => $request->name . ' Membuat Complaint Baru dengan Nomor ' . $kode_complaint]);

            return redirect('en/form_complaint')->with('alert','Complaint successfully sent \nPlease wait for further confirmation from PT. Dwi Selo Giri Mas');
        }else{
            $data =  new ModelComplaintCust();
            $data->nomor_complaint = $kode_complaint;
            $data->tanggal_complaint = date('Y-m-d');
            $data->custid = $request->custid;
            $data->nama_customer = $request->name;
            $data->nomor_surat_jalan = $request->nomor_surat_jalan;
            $data->complaint = $request->complaint;
            $data->created_by = 8;
            $data->save();
            
            Session::forget('nomor_surat_jalan');
            Session::forget('complaint');

            $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

            $new_data_notif = ModelComplaintCust::select('nomor_complaint', 'custid', 'nama_customer')->where('nomor_complaint', $kode_complaint)->first();

            Notification::send($user_notif, new NotifNewComplaint($new_data_notif));

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $kode_complaint, 'divisi' => 9, 'action' => $request->name . ' Membuat Complaint Baru dengan Nomor ' . $kode_complaint]);

            return redirect('en/form_complaint')->with('alert','Complaint successfully sent \nPlease wait for further confirmation from PT. Dwi Selo Giri Mas');
        }
    }

    public function complaintProcessID(Request $request){
        Session::forget('nomor_surat_jalan');
        Session::forget('complaint');
        Session::put('nomor_surat_jalan', $request->nomor_surat_jalan);
        Session::put('complaint', $request->complaint);

        $this->validate($request, [
            'custid' => 'required',
            'name' => 'required',
            'complaint' => 'required',
            'upload_file' => 'file|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $tanggal = date('ym');
        // $str_tanggal = $tanggal->format('Y-m');
        $data_complaint = ModelComplaintCust::where('nomor_complaint', 'like', $tanggal . '%')->orderBy('nomor_complaint', 'asc')->get();

        if($data_complaint){
            $data_count = $data_complaint->count();
            if($data_count > 0){
                $num = (int) substr($data_complaint[$data_complaint->count() - 1]->nomor_complaint, 4);
                if($data_count != $num){
                    $kode_complaint = ++$data_complaint[$data_complaint->count() - 1]->nomor_complaint;
                }else{
                    if($data_count < 9){
                        $kode_complaint = $tanggal . "00000" . ($data_count + 1);
                    }else if($data_count >= 9 && $data_count < 99){
                        $kode_complaint = $tanggal . "0000" . ($data_count + 1);
                    }else if($data_count >= 99 && $data_count < 999){
                        $kode_complaint = $tanggal . "000" . ($data_count + 1);
                    }else if($data_count >= 999 && $data_count < 9999){
                        $kode_complaint = $tanggal . "00" . ($data_count + 1);
                    }else if($data_count >= 9999 && $data_count < 99999){
                        $kode_complaint = $tanggal . "0" . ($data_count + 1);
                    }else{
                        $kode_complaint = $tanggal . ($data_count + 1);
                    }
                }
            }else{
               $kode_complaint = $tanggal . "000001"; 
            }
        }else{
            $kode_complaint = $tanggal . "000001";
        }

        if ($request->hasFile('upload_file')) {

            $file = $request->file('upload_file');
            $nama_file = time()."_".$request->custid."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data =  new ModelComplaintCust();
            $data->nomor_complaint = $kode_complaint;
            $data->tanggal_complaint = date('Y-m-d');
            $data->custid = $request->custid;
            $data->nama_customer = $request->name;
            $data->nomor_surat_jalan = $request->nomor_surat_jalan;
            $data->complaint = $request->complaint;
            $data->lampiran = $nama_file;
            $data->created_by = 8;
            $data->save();
            
            Session::forget('nomor_surat_jalan');
            Session::forget('complaint');

            $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

            $new_data_notif = ModelComplaintCust::select('nomor_complaint', 'custid', 'nama_customer')->where('nomor_complaint', $kode_complaint)->first();

            Notification::send($user_notif, new NotifNewComplaint($new_data_notif));

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $kode_complaint, 'divisi' => 9, 'action' => $request->name . ' Membuat Complaint Baru dengan Nomor ' . $kode_complaint]);

            return redirect('en/form_complaint')->with('alert','Komplain berhasil dikirim \nSilahkan menunggu konfirmasi dari PT. Dwi Selo Giri Mas');
        }else{
            $data =  new ModelComplaintCust();
            $data->nomor_complaint = $kode_complaint;
            $data->tanggal_complaint = date('Y-m-d');
            $data->custid = $request->custid;
            $data->nama_customer = $request->name;
            $data->nomor_surat_jalan = $request->nomor_surat_jalan;
            $data->complaint = $request->complaint;
            $data->created_by = 8;
            $data->save();
            
            Session::forget('nomor_surat_jalan');
            Session::forget('complaint');

            $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

            $new_data_notif = ModelComplaintCust::select('nomor_complaint', 'custid', 'nama_customer')->where('nomor_complaint', $kode_complaint)->first();

            Notification::send($user_notif, new NotifNewComplaint($new_data_notif));

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $kode_complaint, 'divisi' => 9, 'action' => $request->name . ' Membuat Complaint Baru dengan Nomor ' . $kode_complaint]);

            return redirect('en/form_complaint')->with('alert','Komplain berhasil dikirim \nSilahkan menunggu konfirmasi dari PT. Dwi Selo Giri Mas');
        }
    }

    public function complaintAdminProcess(Request $request){
        $this->validate($request, [
            'custid' => 'required',
            'complaint' => 'required',
            'upload_file' => 'max:2048'
        ]);

        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);

        $tanggal = date('ym');
        // $str_tanggal = $tanggal->format('Y-m');
        $data_complaint = ModelComplaintCust::where('nomor_complaint', 'like', $tanggal . '%')->orderBy('nomor_complaint', 'asc')->get();

        if($data_complaint){
            $data_count = $data_complaint->count();
            if($data_count > 0){
                $num = (int) substr($data_complaint[$data_complaint->count() - 1]->nomor_complaint, 4);
                if($data_count != $num){
                    $kode_complaint = ++$data_complaint[$data_complaint->count() - 1]->nomor_complaint;
                }else{
                    if($data_count < 9){
                        $kode_complaint = $tanggal . "00000" . ($data_count + 1);
                    }else if($data_count >= 9 && $data_count < 99){
                        $kode_complaint = $tanggal . "0000" . ($data_count + 1);
                    }else if($data_count >= 99 && $data_count < 999){
                        $kode_complaint = $tanggal . "000" . ($data_count + 1);
                    }else if($data_count >= 999 && $data_count < 9999){
                        $kode_complaint = $tanggal . "00" . ($data_count + 1);
                    }else if($data_count >= 9999 && $data_count < 99999){
                        $kode_complaint = $tanggal . "0" . ($data_count + 1);
                    }else{
                        $kode_complaint = $tanggal . ($data_count + 1);
                    }
                }
            }else{
               $kode_complaint = $tanggal . "000001"; 
            }
        }else{
            $kode_complaint = $tanggal . "000001";
        }

        $data_cust = ModelCustomers::where('custid', $request->get('custid'))->first();

        $produksi = DB::table('temp_no_lot')->select('no_lot', 'custid', 'tanggal_produksi', 'kode_produk', 'mesin', 'area', 'supervisor', 'petugas1', 'petugas2', 'petugas3', 'petugas4', 'petugas5', 'bermasalah')->where('custid', $request->get('custid'))->get();

        if ($request->hasFile('upload_file')) {

            $file = $request->file('upload_file');
            $nama_file = time()."_".$request->get('custid')."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);

            $data =  new ModelComplaintCust();
            $data->nomor_complaint = $kode_complaint;
            $data->tanggal_complaint = date('Y-m-d');
            $data->custid = $request->get('custid');
            $data->nama_customer = $data_cust->custname;
            $data->nomor_surat_jalan = $request->nomor_surat_jalan;
            $data->tanggal_order = $request->tanggal_order;
            $data->complaint = $request->complaint;
            $data->sales_order = $request->sales_order;
            $data->supervisor_sales = $request->supervisor_sls;
            $data->pelapor = $request->pelapor;
            $data->lampiran = $nama_file;
            $data->jumlah_karung = $request->jumlah_karung;
            $data->quantity = $request->quantity;
            $data->jumlah_kg_sak = $request->jumlah_kg_sak;
            $data->jenis_karung = $request->jenis_karung;
            $data->berat_timbangan = $request->berat_timbangan;
            $data->unit_berat_timbangan = $request->unit_berat_timbangan;
            $data->berat_aktual = $request->berat_aktual;
            $data->unit_berat_aktual = $request->input_unit_berat_aktual;
            $data->tanggal_pengiriman = $request->tanggal_pengiriman;
            $data->area = $request->area_log;
            $data->nama_supir = $request->nama_supir;
            $data->nama_kernet = $request->nama_kernet;
            $data->no_kendaraan = $request->no_kendaraan;
            $data->supervisor = $request->supervisor_log;
            $data->pengiriman = $request->pengiriman;
            $data->pengiriman_lain = $request->pengiriman_lain;
            $data->jenis_kendaraan = $request->jenis_kendaraan;
            $data->jenis_kendaraan_lain = $request->jenis_kendaraan_lain;
            $data->created_by = Session::get('id_user_admin');
            $data->save();

            $number_kuli = count($request->nama_kuli);

            for($i=0; $i<$number_kuli; $i++){
                DB::table('complaint_customer')->where('nomor_complaint', $kode_complaint)->update(['kuli'.($i+1) => $request->nama_kuli[$i]]);
            }

            $number_stapel = count($request->nama_stapel);

            for($i=0; $i<$number_stapel; $i++){
                DB::table('complaint_customer')->where('nomor_complaint', $kode_complaint)->update(['stapel'.($i+1) => $request->nama_stapel[$i]]);
            }

            foreach($produksi as $prod){
                $data_produksi = DB::table('data_complaint_produksi')->insert(["nomor_complaint" => $kode_complaint, "custid" => $prod->custid, "no_lot" => $prod->no_lot, "tanggal_produksi" => $prod->tanggal_produksi, "kode_produk" => $prod->kode_produk, "mesin" => $prod->mesin, "area" => $prod->area, "supervisor" => $prod->supervisor, "petugas1" => $prod->petugas1, "petugas2" => $prod->petugas2, "petugas3" => $prod->petugas3, "petugas4" => $prod->petugas4, "petugas5" => $prod->petugas5, "bermasalah" => $prod->bermasalah]);

                if($data_produksi){
                    $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                }else{
                    $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
                }
            }

            DB::table('temp_no_lot')->where('custid', $request->get('custid'))->delete();

            $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

            $new_data_notif = ModelComplaintCust::select('nomor_complaint', 'custid', 'nama_customer')->where('nomor_complaint', $kode_complaint)->first();

            Notification::send($user_notif, new NotifNewComplaint($new_data_notif));

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $kode_complaint, 'divisi' => 3, 'action' => 'Sales Membuat Complaint Baru dengan Nomor ' . $kode_complaint]);

            return Response()->json($arr);
        }else{
            $data =  new ModelComplaintCust();
            $data->nomor_complaint = $kode_complaint;
            $data->tanggal_complaint = date('Y-m-d');
            $data->custid = $request->get('custid');
            $data->nama_customer = $data_cust->custname;
            $data->nomor_surat_jalan = $request->nomor_surat_jalan;
            $data->tanggal_order = $request->tanggal_order;
            $data->complaint = $request->complaint;
            $data->sales_order = $request->sales_order;
            $data->supervisor_sales = $request->supervisor_sls;
            $data->pelapor = $request->pelapor;
            $data->jumlah_karung = $request->jumlah_karung;
            $data->quantity = $request->quantity;
            $data->jumlah_kg_sak = $request->jumlah_kg_sak;
            $data->jenis_karung = $request->jenis_karung;
            $data->berat_timbangan = $request->berat_timbangan;
            $data->unit_berat_timbangan = $request->unit_berat_timbangan;
            $data->berat_aktual = $request->berat_aktual;
            $data->unit_berat_aktual = $request->input_unit_berat_aktual;
            $data->tanggal_pengiriman = $request->tanggal_pengiriman;
            $data->area = $request->area_log;
            $data->nama_supir = $request->nama_supir;
            $data->nama_kernet = $request->nama_kernet;
            $data->no_kendaraan = $request->no_kendaraan;
            $data->supervisor = $request->supervisor_log;
            $data->pengiriman = $request->pengiriman;
            $data->pengiriman_lain = $request->pengiriman_lain;
            $data->jenis_kendaraan = $request->jenis_kendaraan;
            $data->jenis_kendaraan_lain = $request->jenis_kendaraan_lain;
            $data->created_by = Session::get('id_user_admin');
            $data->save();

            $number_kuli = count($request->nama_kuli);

            for($i=0; $i<$number_kuli; $i++){
                DB::table('complaint_customer')->where('nomor_complaint', $kode_complaint)->update(['kuli'.($i+1) => $request->nama_kuli[$i]]);
            }

            $number_stapel = count($request->nama_stapel);

            for($i=0; $i<$number_stapel; $i++){
                DB::table('complaint_customer')->where('nomor_complaint', $kode_complaint)->update(['stapel'.($i+1) => $request->nama_stapel[$i]]);
            }

            foreach($produksi as $prod){
                $data_produksi = DB::table('data_complaint_produksi')->insert(["nomor_complaint" => $kode_complaint, "custid" => $prod->custid, "no_lot" => $prod->no_lot, "tanggal_produksi" => $prod->tanggal_produksi, "kode_produk" => $prod->kode_produk, "mesin" => $prod->mesin, "area" => $prod->area, "supervisor" => $prod->supervisor, "petugas1" => $prod->petugas1, "petugas2" => $prod->petugas2, "petugas3" => $prod->petugas3, "petugas4" => $prod->petugas4, "petugas5" => $prod->petugas5, "bermasalah" => $prod->bermasalah]);

                if($data_produksi){
                    $arr = array('msg' => 'Data Successfully Stored', 'status' => true);
                }else{
                    $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
                }
            }

            DB::table('temp_no_lot')->where('custid', $request->get('custid'))->delete();

            $user_notif = ModelUser::select('id_user as id')->where('id_customer_type', 2)->first();

            $new_data_notif = ModelComplaintCust::select('nomor_complaint', 'custid', 'nama_customer')->where('nomor_complaint', $kode_complaint)->first();

            Notification::send($user_notif, new NotifNewComplaint($new_data_notif));

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_complaint')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nomor_complaint' => $kode_complaint, 'divisi' => 3, 'action' => 'Sales Membuat Complaint Baru dengan Nomor ' . $kode_complaint]);

            return Response()->json($arr);
        }
    }

    public function validasi(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        $validasi = ModelUser::where('custid', $request->get('custid'))->update([
                        'status' => 2
                    ]);

        if($validasi){
            $arr = array('msg' => 'Data Validated Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->get('custid'), 'status_user' => 1, 'action' => 'User ' . $request->get('custid') . ' Divalidasi oleh Admin']);

        return Response()->json($arr);
    }

    public function reject(Request $request){
        $arr = array('msg' => 'Something Goes Wrong. Please Try Again Later', 'status' => false);
        ModelCustomers::where('custid', $request->get('custid'))->delete();
        $reject = ModelUser::where('custid', $request->get('custid'))->delete();

        if($reject){
            $arr = array('msg' => 'Data Rejected Successfully', 'status' => true);
        }

        date_default_timezone_set('Asia/Jakarta');
        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->get('custid'), 'status_user' => 2, 'action' => 'User ' . $request->get('custid') . ' Direject oleh Admin dan Telah Dihapus']);

        return Response()->json($arr);
    }

    public function validasiCustomers(){
        return view('validation_customers');
    }

    public function profileEN(){
        $custid = Session::get('custid');
        $customer = DB::table('customers as cus')->select('cus.custid as custid', 'cus.custname as custname', 'cus.address as address', 'kota.name as city', 'cus.phone as phone', 'cus.wraddress as wraddress', 'cus.npwp as npwp', 'us.email as email', 'type.type as tipe_customer')->join('users as us', 'us.custid', '=', 'cus.custid')->join('user_type as type', 'type.id_customer_type', '=', 'us.id_customer_type')->join('kota', 'kota.id_kota', '=', 'cus.city')->where("cus.custid", $custid)->first();

        return view('profile_en')->with('customer', $customer);
    }

    public function profileID(){
        $custid = Session::get('custid');
        $customer = DB::table('customers as cus')->select('cus.custid as custid', 'cus.custname as custname', 'cus.address as address', 'kota.name as city', 'cus.phone as phone', 'cus.wraddress as wraddress', 'cus.npwp as npwp', 'us.email as email', 'type.type as tipe_customer')->join('users as us', 'us.custid', '=', 'cus.custid')->join('user_type as type', 'type.id_customer_type', '=', 'us.id_customer_type')->join('kota', 'kota.id_kota', '=', 'cus.city')->where("cus.custid", $custid)->first();

        return view('profile_id')->with('customer', $customer);
    }

    public function editPasswordEN(){
        return view('edit_password_en');
    }

    public function editPasswordID(){
        return view('edit_password_id');
    }

    public function editPasswordProcessEN(Request $request){
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
            return redirect('en/edit_password')->with('alert','Old Password and New Password Must Not be Same');
        }else if(Hash::check($old_password,$data->password)){
            ModelUser::where('email', $email)->update([
                    'password' => $new_password
                ]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $email, 'status_user' => 3, 'action' => 'User ' . $email . ' Melakukan Penggantian Password']);

            return redirect('en')->with('alert','Password is Successfully Updated');
        }else{
            return redirect('en/edit_password')->with('alert','Old Password is Wrong');
        }
    }

    public function editPasswordProcessID(Request $request){
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
            return redirect('id/edit_password')->with('alert','Password Lama dan Baru Tidak Boleh Sama');
        }else if(Hash::check($old_password,$data->password)){
            ModelUser::where('email', $email)->update([
                    'password' => $new_password
                ]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $email, 'status_user' => 3, 'action' => 'User ' . $email . ' Melakukan Penggantian Password']);

            return redirect('id')->with('alert','Password Berhasil Diubah');
        }else{
            return redirect('id/edit_password')->with('alert','Password Lama Salah');
        }
    }

    public function updateProfileEN(){
        $custid = Session::get('custid');
        $customer = DB::table('customers as cus')->select('cus.custname as custname', 'cus.address as address', 'kota.name as city', 'cus.city as kode_city', 'cus.phone as phone', 'cus.fax as fax', 'cus.wraddress as wraddress', 'cus.npwp as npwp', 'us.email as email')->join('users as us', 'us.custid', '=', 'cus.custid')->join('kota', 'kota.id_kota', '=', 'cus.city')->where("cus.custid", $custid)->first();

        return view('update_profile_en')->with('customer', $customer);
    }

    public function updateProfileID(){
        $custid = Session::get('custid');
        $customer = DB::table('customers as cus')->select('cus.custname as custname', 'cus.address as address', 'kota.name as city', 'cus.city as kode_city', 'cus.phone as phone', 'cus.fax as fax', 'cus.wraddress as wraddress', 'cus.npwp as npwp', 'us.email as email')->join('users as us', 'us.custid', '=', 'cus.custid')->join('kota', 'kota.id_kota', '=', 'cus.city')->where("cus.custid", $custid)->first();

        return view('update_profile_id')->with('customer', $customer);
    }

    public function updateProfileProcessEN(Request $request){
        $custid = Session::get('custid');

        $this->validate($request, [
            'name' => 'required',
            'id_customer_type' => 'required',
            'email' => ['required','min:4','email', Rule::unique('users')->ignore($custid, 'custid')],
            'address' => 'required',
            'wraddress' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'npwp' => 'required_if:id_customer_type,4,5',
        ]);

        if($request->id_customer_type == 4 || $request->id_customer_type == 5){
            $npwp = substr($request->npwp, 13, 3);
            $kota = ModelNpwp::select("id_kota")->where([
                ['nomor_npwp', '=', $npwp],
                ['id_kota', '=', $request->get('city')],
            ])->first();

            if($kota){
                $user = ModelUser::where('custid', $custid)->update([
                    'email' => $request->email
                ]);

                $customer = ModelCustomers::where('custid', $custid)->update([
                    'custname' => $request->name, 'address' => $request->address, 'wraddress' => $request->wraddress, 'city' => $request->get('city'), 'phone' => $request->phone, 'fax' => $request->fax, 'npwp' => $request->npwp
                ]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 4, 'action' => 'User ' . $request->email . ' Melakukan Perubahan Data']);

                return redirect('en/profile')->with('alert','Data Updated Successfully');
            }else{
                return redirect('en/update/profile')->with('alert','NPWP City and Address City Must Be Same');
            }
        }else{
            $user = ModelUser::where('custid', $custid)->update([
                'email' => $request->email
            ]);

            $customer = ModelCustomers::where('custid', $custid)->update([
                'custname' => $request->name, 'address' => $request->address, 'wraddress' => $request->wraddress, 'city' => $request->get('city'), 'phone' => $request->phone, 'fax' => $request->fax
            ]);

            date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 4, 'action' => 'User ' . $request->email . ' Melakukan Perubahan Data']);

            return redirect('en/profile')->with('alert','Data Updated Successfully');
        }
    }

    public function updateProfileProcessID(Request $request){
        $custid = Session::get('custid');

        $this->validate($request, [
            'name' => 'required',
            'id_customer_type' => 'required',
            'email' => ['required','min:4','email', Rule::unique('users')->ignore($custid, 'custid')],
            'address' => 'required',
            'wraddress' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'npwp' => 'required_if:id_customer_type,4,5',
        ]);

        if($request->id_customer_type == 4 || $request->id_customer_type == 5){
            $npwp = substr($request->npwp, 13, 3);
            $kota = ModelNpwp::select("id_kota")->where([
                ['nomor_npwp', '=', $npwp],
                ['id_kota', '=', $request->get('city')],
            ])->first();

            if($kota){
                $user = ModelUser::where('custid', $custid)->update([
                    'email' => $request->email
                ]);

                $customer = ModelCustomers::where('custid', $custid)->update([
                    'custname' => $request->name, 'address' => $request->address, 'wraddress' => $request->wraddress, 'city' => $request->get('city'), 'phone' => $request->phone, 'fax' => $request->fax, 'npwp' => $request->npwp
                ]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 4, 'action' => 'User ' . $request->email . ' Melakukan Perubahan Data']);

                return redirect('id/profile')->with('alert','Data Berhasil Diupdate');
            }else{
                return redirect('id/update/profile')->with('alert','Kota NPWP dan Kota Asal Harus Sama');
            }
        }else{
            $user = ModelUser::where('custid', $custid)->update([
                'email' => $request->email
            ]);

            $customer = ModelCustomers::where('custid', $custid)->update([
                'custname' => $request->name, 'address' => $request->address, 'wraddress' => $request->wraddress, 'city' => $request->get('city'), 'phone' => $request->phone, 'fax' => $request->fax
            ]);

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->email, 'status_user' => 4, 'action' => 'User ' . $request->email . ' Melakukan Perubahan Data']);

            return redirect('id/profile')->with('alert','Data Berhasil Diupdate');
        }
    }

    public function showEditCustomerAdmin($customerID, $company){   

        $val_customerID = $this->decrypt($customerID);
        $val_company = $this->decrypt($company);

        if($val_company == 'DSGM'){
            $data  = DB::table('customers as cust')->select('cust.custid as custid', 'cust.custname as custname', 'us.email as email', 'tipe.type as tipe_customer', 'cust.crd as crd', 'cust.custlimit as custlimit', 'cust.address as address', 'cust.wraddress as wraddress', 'kota.name as city', 'cust.city as city_name', 'cust.phone as phone', 'cust.fax as fax', 'cust.spv as spv', 'cust.sls as sls', 'cust.telesls as telesls', 'cust.npwp as npwp', 'cust.image_npwp as image_npwp', 'cust.nik as nik', 'cust.image_nik as image_nik', 'cust.created_at as created_at', 'emp.name as created_by', 'cust.agent as agent', 'cust.nama_cp', 'cust.jabatan_cp', 'cust.company', 'cust.bidang_usaha', 'cust.telepon_cp', 'cust.updated_at as updated_at', 'cust.custid_agent as custid_agent', 'cust_agent.custname as custname_agent', DB::raw('(select prod.nama_produk from products prod, history_orders ho where prod.kode_produk = ho.kode_produk and ho.custid = cust.custid group by ho.kode_produk order by sum(ho.quantity) desc limit 0,1) as produk_pesanan'))->leftJoin("users as us", "cust.custid", "=", "us.custid")->leftJoin("user_type as tipe", "tipe.id_customer_type", "=", "us.id_customer_type")->leftJoin("kota", "kota.id_kota", "=", "cust.city")->leftJoin("customers as cust_agent", "cust_agent.custid", "=", "cust.custid_agent")->leftJoin("employee as emp", "emp.employeeid", "=", "cust.created_by")->where('cust.custid', $val_customerID)->first();
        }else if($val_company == 'IMJ'){
            $data  = DB::connection('mysql2')->table('customers as cust')->select('cust.custid as custid', 'cust.custname as custname', 'us.email as email', 'tipe.type as tipe_customer', 'cust.address as address', 'kota.name as city', 'cust.city as city_name', 'cust.phone as phone', 'cust.fax as fax', 'cust.npwp as npwp', 'cust.image_npwp as image_npwp', 'cust.nik as nik', 'cust.image_nik as image_nik', 'cust.created_at as created_at', 'emp.name as created_by', 'cust.nama_cp', 'cust.jabatan_cp', 'cust.company', 'cust.bidang_usaha', 'cust.telepon_cp', 'cust.updated_at as updated_at', DB::raw('(select prod.nama_produk from products prod, history_orders ho where prod.kode_produk = ho.kode_produk and ho.custid = cust.custid group by ho.kode_produk order by sum(ho.quantity) desc limit 0,1) as produk_pesanan'))->leftJoin("users as us", "cust.custid", "=", "us.custid")->leftJoin("user_type as tipe", "tipe.id_customer_type", "=", "us.id_customer_type")->leftJoin("kota", "kota.id_kota", "=", "cust.city")->leftJoin("employee as emp", "emp.employeeid", "=", "cust.created_by")->where('cust.custid', $val_customerID)->first();
        }

        return Response()->json($data);
    }

    public function editCustomerAdmin(Request $request){
        $this->validate($request, [
            'custid_edit' => 'required',
            'name_edit' => 'required',
            'address_edit' => 'required',
            'city_edit' => 'required',
            'upload_image_ktp_edit' => 'nullable|file|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'upload_image_npwp_edit' => 'nullable|file|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($request->hasFile('upload_image_ktp_edit') && $request->hasFile('upload_image_npwp_edit')) {
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($request->company_edit == 'DSGM'){
                    if($npwp == '000'){
                        $data_foto = ModelCustomers::select('image_npwp', 'image_nik')->where('custid', $request->custid_old_edit)->first();
                        File::delete('data_file/' . $data_foto->image_npwp);
                        File::delete('data_file/' . $data_foto->image_nik);

                        $file_npwp = $request->file('upload_image_npwp_edit');
                        $nama_file_npwp = time()."_NPWP_".$request->custid_edit."_".$file_npwp->getClientOriginalName();
                        $tujuan_upload_npwp = 'data_file';
                        $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                        $file_ktp = $request->file('upload_image_ktp_edit');
                        $nama_file_ktp = time()."_KTP_".$request->custid_edit."_".$file_ktp->getClientOriginalName();
                        $tujuan_upload_ktp = 'data_file';
                        $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                        ModelCustomers::where("custid", $request->custid_old_edit)->update([
                            "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $kota = ModelNpwp::select("id_kota")->where([
                            ['nomor_npwp', '=', $npwp],
                            ['id_kota', '=', $request->get('city_edit')],
                        ])->first();

                        $data_foto = ModelCustomers::select('image_npwp', 'image_nik')->where('custid', $request->custid_old_edit)->first();
                        File::delete('data_file/' . $data_foto->image_npwp);
                        File::delete('data_file/' . $data_foto->image_nik);

                        $file_npwp = $request->file('upload_image_npwp_edit');
                        $nama_file_npwp = time()."_NPWP_".$request->custid_edit."_".$file_npwp->getClientOriginalName();
                        $tujuan_upload_npwp = 'data_file';
                        $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                        $file_ktp = $request->file('upload_image_ktp_edit');
                        $nama_file_ktp = time()."_KTP_".$request->custid_edit."_".$file_ktp->getClientOriginalName();
                        $tujuan_upload_ktp = 'data_file';
                        $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                        if($kota){
                            ModelCustomers::where("custid", $request->custid_old_edit)->update([
                                "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                            ]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                            $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                            return Response()->json($arr);
                        }else{
                            $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                            return Response()->json($arr);
                        }
                    }
                }else if($request->company_edit == 'IMJ'){
                    if($npwp == '000'){
                        $file_npwp = $request->file('upload_image_npwp_edit');
                        $file_path_npwp = $file_npwp->getPathname();
                        $file_mime_npwp = $file_npwp->getMimeType('image');
                        $file_uploaded_name_npwp = $file_npwp->getClientOriginalName();

                        $file_ktp = $request->file('upload_image_ktp_edit');
                        $file_path_ktp = $file_ktp->getPathname();
                        $file_mime_ktp = $file_ktp->getMimeType('image');
                        $file_uploaded_name_ktp = $file_ktp->getClientOriginalName();

                        $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                        $client = new \GuzzleHttp\Client();

                        try {
                            $response = $client->request("POST", $uploadFileUrl, [
                                'multipart' => [
                                    [
                                        'name' => 'uploaded_file_npwp',
                                        'filename' => $file_uploaded_name_npwp,
                                        'Mime-Type'=> $file_mime_npwp,
                                        'contents' => fopen($file_path_npwp, 'r'),
                                    ],
                                    [
                                        'name' => 'uploaded_file_ktp',
                                        'filename' => $file_uploaded_name_ktp,
                                        'Mime-Type'=> $file_mime_ktp,
                                        'contents' => fopen($file_path_ktp, 'r'),
                                    ],
                                    [
                                        'name' => 'kode_cust',
                                        'contents' => $request->custid_edit,
                                    ],
                                    [
                                        'name' => 'kode_cust_lama',
                                        'contents' => $request->custid_old_edit,
                                    ]
                                ],
                            ]);
                        } catch (Exception $e) {

                        }

                        $code   = $response->getStatusCode();
                        $response   = $response->getBody();
                        $responseData = json_decode($response, true);

                        DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                            "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $responseData['data']['uploadedFileNameNPWP'], "image_nik" => $responseData['data']['uploadedFileNameKTP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $kota = ModelNpwp::select("id_kota")->where([
                            ['nomor_npwp', '=', $npwp],
                            ['id_kota', '=', $request->get('city_edit')],
                        ])->first();

                        $file_npwp = $request->file('upload_image_npwp_edit');
                        $file_path_npwp = $file_npwp->getPathname();
                        $file_mime_npwp = $file_npwp->getMimeType('image');
                        $file_uploaded_name_npwp = $file_npwp->getClientOriginalName();

                        $file_ktp = $request->file('upload_image_ktp_edit');
                        $file_path_ktp = $file_ktp->getPathname();
                        $file_mime_ktp = $file_ktp->getMimeType('image');
                        $file_uploaded_name_ktp = $file_ktp->getClientOriginalName();

                        $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                        $client = new \GuzzleHttp\Client();

                        try {
                            $response = $client->request("POST", $uploadFileUrl, [
                                'multipart' => [
                                    [
                                        'name' => 'uploaded_file_npwp',
                                        'filename' => $file_uploaded_name_npwp,
                                        'Mime-Type'=> $file_mime_npwp,
                                        'contents' => fopen($file_path_npwp, 'r'),
                                    ],
                                    [
                                        'name' => 'uploaded_file_ktp',
                                        'filename' => $file_uploaded_name_ktp,
                                        'Mime-Type'=> $file_mime_ktp,
                                        'contents' => fopen($file_path_ktp, 'r'),
                                    ],
                                    [
                                        'name' => 'kode_cust',
                                        'contents' => $request->custid_edit,
                                    ],
                                    [
                                        'name' => 'kode_cust_lama',
                                        'contents' => $request->custid_old_edit,
                                    ]
                                ],
                            ]);
                        } catch (Exception $e) {

                        }

                        $code   = $response->getStatusCode();
                        $response   = $response->getBody();
                        $responseData = json_decode($response, true);

                        if($kota){
                            DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                                "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $responseData['data']['uploadedFileNameNPWP'], "image_nik" => $responseData['data']['uploadedFileNameKTP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                            ]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                            $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                            return Response()->json($arr);
                        }else{
                            $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                            return Response()->json($arr);
                        }
                    }
                }
            }else{
                if($request->company_edit == 'DSGM'){
                    $data_foto = ModelCustomers::select('image_npwp', 'image_nik')->where('custid', $request->custid_old_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->custid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->custid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    ModelCustomers::where("custid", $request->custid_old_edit)->update([
                        "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else if($request->company_edit == 'IMJ'){
                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $file_path_npwp = $file_npwp->getPathname();
                    $file_mime_npwp = $file_npwp->getMimeType('image');
                    $file_uploaded_name_npwp = $file_npwp->getClientOriginalName();

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $file_path_ktp = $file_ktp->getPathname();
                    $file_mime_ktp = $file_ktp->getMimeType('image');
                    $file_uploaded_name_ktp = $file_ktp->getClientOriginalName();

                    $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                    $client = new \GuzzleHttp\Client();

                    try {
                        $response = $client->request("POST", $uploadFileUrl, [
                            'multipart' => [
                                [
                                    'name' => 'uploaded_file_npwp',
                                    'filename' => $file_uploaded_name_npwp,
                                    'Mime-Type'=> $file_mime_npwp,
                                    'contents' => fopen($file_path_npwp, 'r'),
                                ],
                                [
                                    'name' => 'uploaded_file_ktp',
                                    'filename' => $file_uploaded_name_ktp,
                                    'Mime-Type'=> $file_mime_ktp,
                                    'contents' => fopen($file_path_ktp, 'r'),
                                ],
                                [
                                    'name' => 'kode_cust',
                                    'contents' => $request->custid_edit,
                                ],
                                [
                                    'name' => 'kode_cust_lama',
                                    'contents' => $request->custid_old_edit,
                                ]
                            ],
                        ]);
                    } catch (Exception $e) {

                    }

                    $code   = $response->getStatusCode();
                    $response   = $response->getBody();
                    $responseData = json_decode($response, true);

                    DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                        "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "nik" => $request->nik_edit, "image_npwp" => $responseData['data']['uploadedFileNameNPWP'], "image_nik" => $responseData['data']['uploadedFileNameKTP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }
            }
        }else if($request->hasFile('upload_image_npwp_edit')){
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($request->company_edit == 'DSGM'){
                    if($npwp == '000'){
                        $data_foto = ModelCustomers::select('image_npwp')->where('custid', $request->custid_old_edit)->first();
                        File::delete('data_file/' . $data_foto->image_npwp);

                        $file_npwp = $request->file('upload_image_npwp_edit');
                        $nama_file_npwp = time()."_NPWP_".$request->custid_edit."_".$file_npwp->getClientOriginalName();
                        $tujuan_upload_npwp = 'data_file';
                        $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                        ModelCustomers::where("custid", $request->custid_old_edit)->update([
                            "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $kota = ModelNpwp::select("id_kota")->where([
                            ['nomor_npwp', '=', $npwp],
                            ['id_kota', '=', $request->get('city_edit')],
                        ])->first();

                        $data_foto = ModelCustomers::select('image_npwp')->where('custid', $request->custid_old_edit)->first();
                        File::delete('data_file/' . $data_foto->image_npwp);

                        $file_npwp = $request->file('upload_image_npwp_edit');
                        $nama_file_npwp = time()."_NPWP_".$request->custid_edit."_".$file_npwp->getClientOriginalName();
                        $tujuan_upload_npwp = 'data_file';
                        $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                        if($kota){
                            ModelCustomers::where("custid", $request->custid_old_edit)->update([
                                "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                            ]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                            $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                            return Response()->json($arr);
                        }else{
                            $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                            return Response()->json($arr);
                        }
                    }
                }else if($request->company_edit == 'IMJ'){
                    if($npwp == '000'){
                        $file_npwp = $request->file('upload_image_npwp_edit');
                        $file_path_npwp = $file_npwp->getPathname();
                        $file_mime_npwp = $file_npwp->getMimeType('image');
                        $file_uploaded_name_npwp = $file_npwp->getClientOriginalName();

                        $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                        $client = new \GuzzleHttp\Client();

                        try {
                            $response = $client->request("POST", $uploadFileUrl, [
                                'multipart' => [
                                    [
                                        'name' => 'uploaded_file_npwp',
                                        'filename' => $file_uploaded_name_npwp,
                                        'Mime-Type'=> $file_mime_npwp,
                                        'contents' => fopen($file_path_npwp, 'r'),
                                    ],
                                    [
                                        'name' => 'kode_cust',
                                        'contents' => $request->custid_edit,
                                    ],
                                    [
                                        'name' => 'kode_cust_lama',
                                        'contents' => $request->custid_old_edit,
                                    ]
                                ],
                            ]);
                        } catch (Exception $e) {

                        }

                        $code   = $response->getStatusCode();
                        $response   = $response->getBody();
                        $responseData = json_decode($response, true);

                        DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                            "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $responseData['data']['uploadedFileNameNPWP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $kota = ModelNpwp::select("id_kota")->where([
                            ['nomor_npwp', '=', $npwp],
                            ['id_kota', '=', $request->get('city_edit')],
                        ])->first();

                        $file_npwp = $request->file('upload_image_npwp_edit');
                        $file_path_npwp = $file_npwp->getPathname();
                        $file_mime_npwp = $file_npwp->getMimeType('image');
                        $file_uploaded_name_npwp = $file_npwp->getClientOriginalName();

                        $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                        $client = new \GuzzleHttp\Client();

                        try {
                            $response = $client->request("POST", $uploadFileUrl, [
                                'multipart' => [
                                    [
                                        'name' => 'uploaded_file_npwp',
                                        'filename' => $file_uploaded_name_npwp,
                                        'Mime-Type'=> $file_mime_npwp,
                                        'contents' => fopen($file_path_npwp, 'r'),
                                    ],
                                    [
                                        'name' => 'kode_cust',
                                        'contents' => $request->custid_edit,
                                    ],
                                    [
                                        'name' => 'kode_cust_lama',
                                        'contents' => $request->custid_old_edit,
                                    ]
                                ],
                            ]);
                        } catch (Exception $e) {

                        }

                        $code   = $response->getStatusCode();
                        $response   = $response->getBody();
                        $responseData = json_decode($response, true);

                        if($kota){
                            DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                                "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_npwp" => $responseData['data']['uploadedFileNameNPWP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                            ]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                            $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                            return Response()->json($arr);
                        }else{
                            $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                            return Response()->json($arr);
                        }
                    }
                }
            }else{
                if($request->company_edit == 'DSGM'){
                    $data_foto = ModelCustomers::select('image_npwp')->where('custid', $request->custid_old_edit)->first();
                    File::delete('data_file/' . $data_foto->image_npwp);

                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $nama_file_npwp = time()."_NPWP_".$request->custid_edit."_".$file_npwp->getClientOriginalName();
                    $tujuan_upload_npwp = 'data_file';
                    $file_npwp->move($tujuan_upload_npwp, $nama_file_npwp);

                    ModelCustomers::where("custid", $request->custid_old_edit)->update([
                        "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_npwp" => $nama_file_npwp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else if($request->company_edit == 'IMJ'){
                    $file_npwp = $request->file('upload_image_npwp_edit');
                    $file_path_npwp = $file_npwp->getPathname();
                    $file_mime_npwp = $file_npwp->getMimeType('image');
                    $file_uploaded_name_npwp = $file_npwp->getClientOriginalName();

                    $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                    $client = new \GuzzleHttp\Client();

                    try {
                        $response = $client->request("POST", $uploadFileUrl, [
                            'multipart' => [
                                [
                                    'name' => 'uploaded_file_npwp',
                                    'filename' => $file_uploaded_name_npwp,
                                    'Mime-Type'=> $file_mime_npwp,
                                    'contents' => fopen($file_path_npwp, 'r'),
                                ],
                                [
                                    'name' => 'kode_cust',
                                    'contents' => $request->custid_edit,
                                ],
                                [
                                    'name' => 'kode_cust_lama',
                                    'contents' => $request->custid_old_edit,
                                ]
                            ],
                        ]);
                    } catch (Exception $e) {

                    }

                    $code   = $response->getStatusCode();
                    $response   = $response->getBody();
                    $responseData = json_decode($response, true);

                    DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                        "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "nik" => $request->nik_edit, "image_npwp" => $responseData['data']['uploadedFileNameNPWP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }
            }
        }else if($request->hasFile('upload_image_ktp_edit')){
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($request->company_edit == 'DSGM'){
                    if($npwp == '000'){
                        $data_foto = ModelCustomers::select('image_nik')->where('custid', $request->custid_old_edit)->first();
                        File::delete('data_file/' . $data_foto->image_nik);

                        $file_ktp = $request->file('upload_image_ktp_edit');
                        $nama_file_ktp = time()."_KTP_".$request->custid_edit."_".$file_ktp->getClientOriginalName();
                        $tujuan_upload_ktp = 'data_file';
                        $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                        ModelCustomers::where("custid", $request->custid_old_edit)->update([
                            "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $kota = ModelNpwp::select("id_kota")->where([
                            ['nomor_npwp', '=', $npwp],
                            ['id_kota', '=', $request->get('city_edit')],
                        ])->first();

                        $data_foto = ModelCustomers::select('image_nik')->where('custid', $request->custid_old_edit)->first();
                        File::delete('data_file/' . $data_foto->image_nik);

                        $file_ktp = $request->file('upload_image_ktp_edit');
                        $nama_file_ktp = time()."_KTP_".$request->custid_edit."_".$file_ktp->getClientOriginalName();
                        $tujuan_upload_ktp = 'data_file';
                        $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                        if($kota){
                            ModelCustomers::where("custid", $request->custid_old_edit)->update([
                                "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                            ]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                            $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                            return Response()->json($arr);
                        }else{
                            $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                            return Response()->json($arr);
                        }
                    }
                }else if($request->company_edit == 'IMJ'){
                    if($npwp == '000'){
                        $file_ktp = $request->file('upload_image_ktp_edit');
                        $file_path_ktp = $file_ktp->getPathname();
                        $file_mime_ktp = $file_ktp->getMimeType('image');
                        $file_uploaded_name_ktp = $file_ktp->getClientOriginalName();

                        $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                        $client = new \GuzzleHttp\Client();

                        try {
                            $response = $client->request("POST", $uploadFileUrl, [
                                'multipart' => [
                                    [
                                        'name' => 'uploaded_file_ktp',
                                        'filename' => $file_uploaded_name_ktp,
                                        'Mime-Type'=> $file_mime_ktp,
                                        'contents' => fopen($file_path_ktp, 'r'),
                                    ],
                                    [
                                        'name' => 'kode_cust',
                                        'contents' => $request->custid_edit,
                                    ],
                                    [
                                        'name' => 'kode_cust_lama',
                                        'contents' => $request->custid_old_edit,
                                    ]
                                ],
                            ]);
                        } catch (Exception $e) {

                        }

                        $code   = $response->getStatusCode();
                        $response   = $response->getBody();
                        $responseData = json_decode($response, true);

                        DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                            "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_nik" => $responseData['data']['uploadedFileNameKTP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $kota = ModelNpwp::select("id_kota")->where([
                            ['nomor_npwp', '=', $npwp],
                            ['id_kota', '=', $request->get('city_edit')],
                        ])->first();

                        $file_ktp = $request->file('upload_image_ktp_edit');
                        $file_path_ktp = $file_ktp->getPathname();
                        $file_mime_ktp = $file_ktp->getMimeType('image');
                        $file_uploaded_name_ktp = $file_ktp->getClientOriginalName();

                        $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                        $client = new \GuzzleHttp\Client();

                        try {
                            $response = $client->request("POST", $uploadFileUrl, [
                                'multipart' => [
                                    [
                                        'name' => 'uploaded_file_ktp',
                                        'filename' => $file_uploaded_name_ktp,
                                        'Mime-Type'=> $file_mime_ktp,
                                        'contents' => fopen($file_path_ktp, 'r'),
                                    ],
                                    [
                                        'name' => 'kode_cust',
                                        'contents' => $request->custid_edit,
                                    ],
                                    [
                                        'name' => 'kode_cust_lama',
                                        'contents' => $request->custid_old_edit,
                                    ]
                                ],
                            ]);
                        } catch (Exception $e) {

                        }

                        $code   = $response->getStatusCode();
                        $response   = $response->getBody();
                        $responseData = json_decode($response, true);

                        if($kota){
                            DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                                "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "image_nik" => $responseData['data']['uploadedFileNameKTP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                            ]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                            $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                            return Response()->json($arr);
                        }else{
                            $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                            return Response()->json($arr);
                        }
                    }
                }
            }else{
                if($request->company_edit == 'DSGM'){
                    $data_foto = ModelCustomers::select('image_nik')->where('custid', $request->custid_old_edit)->first();
                    File::delete('data_file/' . $data_foto->image_nik);

                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $nama_file_ktp = time()."_KTP_".$request->custid_edit."_".$file_ktp->getClientOriginalName();
                    $tujuan_upload_ktp = 'data_file';
                    $file_ktp->move($tujuan_upload_ktp, $nama_file_ktp);

                    ModelCustomers::where("custid", $request->custid_old_edit)->update([
                        "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "nik" => $request->nik_edit, "image_nik" => $nama_file_ktp, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else if($request->company_edit == 'IMJ'){
                    $file_ktp = $request->file('upload_image_ktp_edit');
                    $file_path_ktp = $file_ktp->getPathname();
                    $file_mime_ktp = $file_ktp->getMimeType('image');
                    $file_uploaded_name_ktp = $file_ktp->getClientOriginalName();

                    $uploadFileUrl = 'http://admin.rkmortar.com/api/upload-file-cust-edit';
                    $client = new \GuzzleHttp\Client();

                    try {
                        $response = $client->request("POST", $uploadFileUrl, [
                            'multipart' => [
                                [
                                    'name' => 'uploaded_file_ktp',
                                    'filename' => $file_uploaded_name_ktp,
                                    'Mime-Type'=> $file_mime_ktp,
                                    'contents' => fopen($file_path_ktp, 'r'),
                                ],
                                [
                                    'name' => 'kode_cust',
                                    'contents' => $request->custid_edit,
                                ],
                                [
                                    'name' => 'kode_cust_lama',
                                    'contents' => $request->custid_old_edit,
                                ]
                            ],
                        ]);
                    } catch (Exception $e) {

                    }

                    $code   = $response->getStatusCode();
                    $response   = $response->getBody();
                    $responseData = json_decode($response, true);

                    DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                        "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "nik" => $request->nik_edit, "image_nik" => $responseData['data']['uploadedFileNameKTP'], "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }
            }
        }else{
            if($request->npwp_edit != null){
                $npwp = substr($request->npwp_edit, 13, 3);

                if($request->company_edit == 'DSGM'){
                    if($npwp == '000'){
                        ModelCustomers::where("custid", $request->custid_old_edit)->update([
                            "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "crd" => $request->crd_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $kota = ModelNpwp::select("id_kota")->where([
                            ['nomor_npwp', '=', $npwp],
                            ['id_kota', '=', $request->get('city_edit')],
                        ])->first();

                        if($kota){
                            ModelCustomers::where("custid", $request->custid_old_edit)->update([
                                "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "crd" => $request->crd_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                            ]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                            $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                            return Response()->json($arr);
                        }else{
                            $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                            return Response()->json($arr);
                        }
                    }
                }else if($request->company_edit == 'IMJ'){
                    if($npwp == '000'){
                        DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                            "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                        ]);

                        date_default_timezone_set('Asia/Jakarta');
                        DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                        $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                        return Response()->json($arr);
                    }else{
                        $kota = ModelNpwp::select("id_kota")->where([
                            ['nomor_npwp', '=', $npwp],
                            ['id_kota', '=', $request->get('city_edit')],
                        ])->first();

                        if($kota){
                            DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                                "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "npwp" => $request->npwp_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                            ]);

                            date_default_timezone_set('Asia/Jakarta');
                            DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                            $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                            return Response()->json($arr);
                        }else{
                            $arr = array('msg' => 'Kota NPWP dan Kota Alamat Harus Sama', 'status' => false);
                            return Response()->json($arr);
                        }
                    }
                }
            }else{
                if($request->company_edit == 'DSGM'){
                    ModelCustomers::where("custid", $request->custid_old_edit)->update([
                        "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "wraddress" => $request->wraddress_edit, "crd" => $request->crd_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }else if($request->company_edit == 'IMJ'){
                    DB::connection('mysql2')->table('customers')->where("custid", $request->custid_old_edit)->update([
                        "custname" => $request->name_edit, "custid" => $request->custid_edit, "company" => $request->company_edit, "address" => $request->address_edit, "city" => $request->get('city_edit'), "phone" => $request->phone_edit, "fax" => $request->fax_edit, "nik" => $request->nik_edit, "nama_cp" => $request->nama_cp_edit, "jabatan_cp" => $request->jabatan_cp_edit, "bidang_usaha" => $request->bidang_usaha_edit, "telepon_cp" => $request->telepon_cp_edit, "updated_at" => date('Y-m-d')
                    ]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::connection('mysql2')->table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $request->custid_edit, 'status_user' => 4, 'action' => 'User ' . $request->custid_edit . ' Melakukan Perubahan Data oleh Admin']);

                    $arr = array('msg' => 'Update Data Berhasil', 'status' => true);
                    return Response()->json($arr);
                }
            }
        }
    }    

    public function getDetailFollowUp($nomor_followup){

        $val_nomor_followup = $this->decrypt($nomor_followup);

        $data  = DB::table('follow_up_customer as folu')->select('folu.nomor_followup as nomor', 'cust.custname as nama', 'folu.tanggal as tanggal', 'folu.aktivitas as aktivitas', 'folu.informasi as informasi')->join("customers as cust", "cust.custid", "=", "folu.custid")->where('folu.nomor_followup', $val_nomor_followup)->first();

        return Response()->json($data);
    }

    public function pembagianSalesCustomers(Request $request){
        if(request()->ajax()){
            $pembagian = ModelCustomers::select('custid', 'id_sales', 'custname as nama', 'com.nama_perusahaan as company', 'customers.company as kode_company', 'address', 'kota.name as city')->leftJoin('kota', 'kota.id_kota', '=', 'customers.city')->leftJoin('company as com', 'com.kode_perusahaan', '=', 'customers.company')->whereNull('id_sales')->skip(0)->take(100)->get();

            return datatables()->of($pembagian)->make(true);
        }
        return view('input_kompetitor');
    }

    public function savePembagianSalesCustomers(Request $request){
        $tanggal_schedule = date('ym');

        $sales = $request->get('sales');
        $custid = $request->get('custid');
        $pilih_sales = $request->get('pilih_sales');
        $offline = $request->get('offline');
        $perihal = $request->get('perihal');
        $tanggal_kunjungan = $request->get('tanggal_kunjungan');
        $kode_company = $request->get('kode_company');

        foreach($custid as $nomor) {
            if(is_array($pilih_sales) && array_key_exists($nomor,$pilih_sales)){
                ModelCustomers::where('custid', $nomor)->update(['id_sales' => $sales, 'updated_at' => date("Y-m-d H:i:s"), 'updated_by' => Session::get('id_user_admin')]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_user')->insert(['tanggal' => date("Y-m-d H:i:s"), 'email' => $nomor, 'status_user' => 4, 'action' => ' User ' . $nomor . ' Telah Melakukan Pembagian Sales oleh Admin']);

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
                        DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => $sales, "company" => $kode_company[$nomor], "tipe_customer" => 1, "customers" => $nomor, "perihal" => $perihal[$nomor], "offline" => 1, "tanggal_schedule" => $tanggal_kunjungan[$nomor], "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);
                    }else{
                        DB::table('customers_visit')->insert(["id_schedule" => $kode_schedule, "id_user" => $sales, "company" => $kode_company[$nomor], "tipe_customer" => 1, "customers" => $nomor, "perihal" => $perihal[$nomor], "offline" => 0, "tanggal_schedule" => $tanggal_kunjungan[$nomor], "tanggal_input" => date('Y-m-d'), "order_sort" => $sort, "status" => 1]);
                    }

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_customer_visit')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => $sales, 'status' => 1, 'action' => 'User ' . $sales . ' Input Data Customer Visit No. ' . $kode_schedule . ' Melalui Pembagian Sales']);
                }
            }
        }

        return Response()->json();
    }
}
