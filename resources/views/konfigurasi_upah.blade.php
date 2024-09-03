@extends('layouts.app_admin')

@section('title')
<title>KONFIGURASI UPAH - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/daterangepicker/daterangepicker.css')}}"> -->
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}"> -->
<style type="text/css">
  .filter-btn {
    margin-top: 32px;
  }
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
    }
    .filter-btn {
      margin-top: 0;
      margin-bottom: 10px;
    }
  }
</style>
@endsection

@section('content_nav')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Konfigurasi Upah</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Administrasi</li>
            <li class="breadcrumb-item">Konfigurasi Upah</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  @endsection

  @section('content')
  <div class="row"> 
    <div class="col-2"></div>
    <div class="col-8">
      <form method="post" class="edit_form" id="edit_form" action="javascript:void(0)" enctype="multipart/form-data">
        {{ csrf_field() }}
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <button type="button" class="btn btn-primary" id="btn-input-data" style="width: 100%;" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="text" class="form-control" name="tanggal" id="tanggal" autocomplete="off" placeholder="Tanggal" value="<?= strftime(date('l, j F Y ', time())); ?>"disabled>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="perusahaan">Perusahaan</label>
                <select id="perusahaan" name="perusahaan" class="form-control" style="width: 100%;">
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="besar_upah_minimum">Besar Upah Minimum</label>
                <input type="text" class="form-control" name="besar_upah_minimum" id="besar_upah_minimum" autocomplete="off" placeholder="Besar Upah Minimum">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="astek">Astek</label>
                <input type="text" class="form-control" name="astek" id="astek" autocomplete="off" placeholder="Astek">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="ptkp_menikah">PTKP (Menikah)</label>
                <input type="text" class="form-control" name="ptkp_menikah" id="ptkp_menikah" autocomplete="off" placeholder="PTKP (Menikah)">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="jaminan_kesehatan">Jaminan Kesehatan (JK)</label>
                <input type="text" class="form-control" name="jaminan_kesehatan" id="jaminan_kesehatan" autocomplete="off" placeholder="Jaminan Kesehatan (JK)">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="ptkp_anak">PTKP (Anak Max 3)</label>
                <input type="text" class="form-control" name="ptkp_anak" id="ptkp_anak" autocomplete="off" placeholder="PTKP (Anak Max 3)">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="jaminan_kecelakaan_kerja">Jaminan Kecelakaan Kerja (JKK)</label>
                <input type="text" class="form-control" name="jaminan_kecelakaan_kerja" id="jaminan_kecelakaan_kerja" autocomplete="off" placeholder="Jaminan Kecelakaan Kerja (JKK)">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="persentase_pph21">Persentase Pph-21</label>
                <input type="text" class="form-control" name="persentase_pph21" id="persentase_pph21" autocomplete="off" placeholder="Persentase Pph-21">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="bpjs">BPJS</label>
                <input type="text" class="form-control" name="bpjs" id="bpjs" autocomplete="off" placeholder="BPJS">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="limit_max_biaya_jabatan">Limit Max Biaya Jabatan</label>
                <input type="text" class="form-control" name="limit_max_biaya_jabatan" id="limit_max_biaya_jabatan" autocomplete="off" placeholder="Limit Max Biaya Jabatan">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="uang_makan">Uang Makan</label>
                <input type="text" class="form-control" name="uang_makan" id="uang_makan" autocomplete="off" placeholder="Uang Makan">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h5>Jumlah Jam Kerja</h5></div>
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="jam_kerja_paruh_waktu">Jam Kerja Paruh Waktu (Jam)</label>
                <input type="text" class="form-control" name="jam_kerja_paruh_waktu" id="jam_kerja_paruh_waktu" autocomplete="off" placeholder="Jam Kerja Paruh Waktu">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="jam_kerja_satu_hari">Jam Kerja Satu Hari (Jam)</label>
                <input type="text" class="form-control" name="jam_kerja_satu_hari" id="jam_kerja_satu_hari" autocomplete="off" placeholder="Jam Kerja Satu Hari">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="jadwal_pembayaran_gaji">Jadwal Pembayaran Gaji</label>
                <select id="jadwal_pembayaran_gaji" name="jadwal_pembayaran_gaji" class="form-control" style="width: 100%;">
                  <option value="1" selected>Minggu</option>
                  <option value="2">Senin</option>
                  <option value="3">Selasa</option>
                  <option value="4">Rabu</option>
                  <option value="5">Kamis</option>
                  <option value="6">Jumat</option>
                  <option value="7">Sabtu</option>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="jangka_bayar">Jangka Bayar</label>
                <select id="jangka_bayar" name="jangka_bayar" class="form-control" style="width: 100%;">
                  <option value="1" selected>Minggu</option>
                  <option value="2">Senin</option>
                  <option value="3">Selasa</option>
                  <option value="4">Rabu</option>
                  <option value="5">Kamis</option>
                  <option value="6">Jumat</option>
                  <option value="7">Sabtu</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="sistem_upah_lembur">Sistem Upah Lembur</label>
                <select id="sistem_upah_lembur" name="sistem_upah_lembur" class="form-control" style="width: 100%;">
                  <option value="1" selected>Formula</option>
                  <option value="2">Lain-lain</option>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="sistem_premi">&nbsp</label>
                <select id="sistem_premi" name="sistem_premi" class="form-control" style="width: 100%;">
                  <option value="1" selected>Premi Dua Minggu</option>
                  <option value="2">Tidak</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="sistem_pembulatan">Sistem Pembulatan</label>
                <select id="sistem_pembulatan" name="sistem_pembulatan" class="form-control" style="width: 100%;">
                  <option value="1" selected>Upper</option>
                  <option value="2">Scientific</option>
                  <option value="3">Lower</option>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="batas_pembulatan">Batas Pembulatan</label>
                <input type="text" class="form-control" name="batas_pembulatan" id="batas_pembulatan" autocomplete="off" placeholder="Batas Pembulatan">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="direktori_backup_data">Direktori Backup Data</label>
                <input type="text" class="form-control" name="direktori_backup_data" id="direktori_backup_data" autocomplete="off" placeholder="Direktori Backup Data">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary" id="btn-save-edit" style="width: 100%;">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </form>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="input_form" id="input_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_perusahaan">Perusahaan</label>
                  <select id="input_perusahaan" name="input_perusahaan" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_besar_upah_minimum">Besar Upah Minimum</label>
                  <input type="text" class="form-control" name="input_besar_upah_minimum" id="input_besar_upah_minimum" autocomplete="off" placeholder="Besar Upah Minimum">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="input_astek">Astek</label>
                  <input type="text" class="form-control" name="input_astek" id="input_astek" autocomplete="off" placeholder="Astek">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_ptkp_menikah">PTKP (Menikah)</label>
                  <input type="text" class="form-control" name="input_ptkp_menikah" id="input_ptkp_menikah" autocomplete="off" placeholder="PTKP (Menikah)">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="input_jaminan_kesehatan">Jaminan Kesehatan (JK)</label>
                  <input type="text" class="form-control" name="input_jaminan_kesehatan" id="input_jaminan_kesehatan" autocomplete="off" placeholder="Jaminan Kesehatan (JK)">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_ptkp_anak">PTKP (Anak Max 3)</label>
                  <input type="text" class="form-control" name="input_ptkp_anak" id="input_ptkp_anak" autocomplete="off" placeholder="PTKP (Anak Max 3)">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="input_jaminan_kecelakaan_kerja">Jaminan Kecelakaan Kerja (JKK)</label>
                  <input type="text" class="form-control" name="input_jaminan_kecelakaan_kerja" id="input_jaminan_kecelakaan_kerja" autocomplete="off" placeholder="Jaminan Kecelakaan Kerja (JKK)">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_persentase_pph21">Persentase Pph-21</label>
                  <input type="text" class="form-control" name="input_persentase_pph21" id="input_persentase_pph21" autocomplete="off" placeholder="Persentase Pph-21">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="input_bpjs">BPJS</label>
                  <input type="text" class="form-control" name="input_bpjs" id="input_bpjs" autocomplete="off" placeholder="BPJS">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_limit_max_biaya_jabatan">Limit Max Biaya Jabatan</label>
                  <input type="text" class="form-control" name="input_limit_max_biaya_jabatan" id="input_limit_max_biaya_jabatan" autocomplete="off" placeholder="Limit Max Biaya Jabatan">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="input_uang_makan">Uang Makan</label>
                  <input type="text" class="form-control" name="input_uang_makan" id="input_uang_makan" autocomplete="off" placeholder="Uang Makan">
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><h5>Jumlah Jam Kerja</h5></div>
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="input_jam_kerja_paruh_waktu">Jam Kerja Paruh Waktu (Jam)</label>
                      <input type="text" class="form-control" name="input_jam_kerja_paruh_waktu" id="input_jam_kerja_paruh_waktu" autocomplete="off" placeholder="Jam Kerja Paruh Waktu">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="input_jam_kerja_satu_hari">Jam Kerja Satu Hari (Jam)</label>
                      <input type="text" class="form-control" name="input_jam_kerja_satu_hari" id="input_jam_kerja_satu_hari" autocomplete="off" placeholder="Jam Kerja Satu Hari">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_jadwal_pembayaran_gaji">Jadwal Pembayaran Gaji</label>
                  <select id="input_jadwal_pembayaran_gaji" name="input_jadwal_pembayaran_gaji" class="form-control" style="width: 100%;">
                    <option value="1" selected>Minggu</option>
                    <option value="2">Senin</option>
                    <option value="3">Selasa</option>
                    <option value="4">Rabu</option>
                    <option value="5">Kamis</option>
                    <option value="6">Jumat</option>
                    <option value="7">Sabtu</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="input_jangka_bayar">Jangka Bayar</label>
                  <select id="input_jangka_bayar" name="input_jangka_bayar" class="form-control" style="width: 100%;">
                    <option value="1" selected>Minggu</option>
                    <option value="2">Senin</option>
                    <option value="3">Selasa</option>
                    <option value="4">Rabu</option>
                    <option value="5">Kamis</option>
                    <option value="6">Jumat</option>
                    <option value="7">Sabtu</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_sistem_upah_lembur">Sistem Upah Lembur</label>
                  <select id="input_sistem_upah_lembur" name="input_sistem_upah_lembur" class="form-control" style="width: 100%;">
                    <option value="1" selected>Formula</option>
                    <option value="2">Lain-lain</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="input_sistem_premi">&nbsp</label>
                  <select id="input_sistem_premi" name="input_sistem_premi" class="form-control" style="width: 100%;">
                    <option value="1" selected>Premi Dua Minggu</option>
                    <option value="2">Tidak</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="input_sistem_pembulatan">Sistem Pembulatan</label>
                  <select id="input_sistem_pembulatan" name="input_sistem_pembulatan" class="form-control" style="width: 100%;">
                    <option value="1" selected>Upper</option>
                    <option value="2">Scientific</option>
                    <option value="3">Lower</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="input_batas_pembulatan">Batas Pembulatan</label>
                  <input type="text" class="form-control" name="input_batas_pembulatan" id="input_batas_pembulatan" autocomplete="off" placeholder="Batas Pembulatan">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="input_direktori_backup_data">Direktori Backup Data</label>
                  <input type="text" class="form-control" name="input_direktori_backup_data" id="input_direktori_backup_data" autocomplete="off" placeholder="Direktori Backup Data">
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-input">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  @endsection

  @section('right_nav')
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->
  @endsection

  @section('script_login')
  <script src="https://code.jquery.com/jquery.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="{{asset('lte/plugins/moment/moment.min.js')}}"></script>
  <script src="{{asset('lte/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
  <script src="{{asset('lte/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
  <script src="{{asset('lte/plugins/jquery-validation/additional-methods.min.js')}}"></script>
  <script src="{{asset('lte/plugins/select2/js/select2.full.min.js')}}"></script>
  <script src="{{asset('lte/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
  <!-- <script src="{{asset('lte/plugins/daterangepicker/daterangepicker.js')}}"></script> -->
<!--   <script src="{{asset('lte/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script> -->

<script>
  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    function encryptMethodLength_func() {
      var encryptMethod = 'AES-256-CBC';
      var aesNumber = encryptMethod.match(/\d+/)[0];
      return parseInt(aesNumber);
    }

    function enc(plainText){
      var iv = CryptoJS.lib.WordArray.random(16);

      var salt = CryptoJS.lib.WordArray.random(256);
      var iterations = 999;
      var encryptMethodLength = (encryptMethodLength_func()/4);
      var hashKey = CryptoJS.PBKDF2(key, salt, {'hasher': CryptoJS.algo.SHA512, 'keySize': (encryptMethodLength/8), 'iterations': iterations});

      var encrypted = CryptoJS.AES.encrypt(plainText, hashKey, {'mode': CryptoJS.mode.CBC, 'iv': iv});
      var encryptedString = CryptoJS.enc.Base64.stringify(encrypted.ciphertext);

      var output = {
        'ciphertext': encryptedString,
        'iv': CryptoJS.enc.Hex.stringify(iv),
        'salt': CryptoJS.enc.Hex.stringify(salt),
        'iterations': iterations
      };

      return CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(output)));
    }

    var url_perusahaan = "{{ url('get_company/konfigurasi') }}";
    $.get(url_perusahaan, function (data) {
      $('#perusahaan').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
      $.each(data, function(k, v) {
        $('#perusahaan').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
      });
      $('#perusahaan').val('DSGM').trigger('change');
    })

    $("#perusahaan").change(function() {
      var val = $(this).val();

      var url_data = "{{ url('konfigurasi_upah/company') }}";
      url_data = url_data.replace('company', enc(val.toString()));
      $.get(url_data, function (data) {
        $('#besar_upah_minimum').val(data.upah_minimum);
        $('#astek').val(data.astek);
        $('#ptkp_menikah').val(data.ptkp_menikah);
        $('#ptkp_anak').val(data.ptkp_anak);
        $('#jaminan_kesehatan').val(data.jaminan_kesehatan);
        $('#jaminan_kecelakaan_kerja').val(data.jaminan_kecelakaan_kerja);
        $('#persentase_pph21').val(data.persentase_pph21);
        $('#bpjs').val(data.bpjs);
        $('#limit_max_biaya_jabatan').val(data.limit_max_biaya_jabatan);
        $('#uang_makan').val(data.uang_makan);
        $('#jam_kerja_paruh_waktu').val(data.jumlah_jam_kerja_paruh_hari);
        $('#jam_kerja_satu_hari').val(data.jumlah_jam_kerja_satu_hari);
        $('#jadwal_pembayaran_gaji').val(data.jadwal_pembayaran_gaji);
        $('#jangka_bayar').val(data.jangka_bayar);
        $('#sistem_upah_lembur').val(data.sistem_upah_lembur);
        $('#sistem_premi').val(data.sistem_premi);
        $('#sistem_pembulatan').val(data.sistem_pembulatan);
        $('#batas_pembulatan').val(data.batas_pembulatan);
        $('#direktori_backup_data').val(data.direktori_backup_data);
      })
    });

    $('body').on('click', '#btn-input-data', function () {
      var url_perusahaan = "{{ url('get_company') }}";
      $.get(url_perusahaan, function (data) {
        $('#input_perusahaan').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#input_perusahaan').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })
    });

    $('#edit_form').validate({
      rules: {
        kode_perusahaan: {
          required: true,
        },
        besar_upah_minimum: {
          required: true,
        },
        astek: {
          required: true,
        },
        ptkp_menikah: {
          required: true,
        },
        ptkp_anak: {
          required: true,
        },
        jaminan_kesehatan: {
          required: true,
        },
        jaminan_kecelakaan_kerja: {
          required: true,
        },
        persentase_pph21: {
          required: true,
        },
        bpjs: {
          required: true,
        },
        limit_max_biaya_jabatan: {
          required: true,
        },
        uang_makan: {
          required: true,
        },
        jam_kerja_paruh_waktu: {
          required: true,
        },
        jam_kerja_satu_hari: {
          required: true,
        },
        jadwal_pembayaran_gaji: {
          required: true,
        },
        jangka_bayar: {
          required: true,
        },
        sistem_upah_lembur: {
          required: true,
        },
        sistem_premi: {
          required: true,
        },
        sistem_pembulatan: {
          required: true,
        },
        batas_pembulatan: {
          required: true,
        },
        direktori_backup_data: {
          required: true,
        },
      },
      messages: {
        kode_perusahaan: {
          required: "Perusahaan Harus Diisi",
        },
        besar_upah_minimum: {
          required: "Besar Upah Minimum Harus Diisi",
        },
        astek: {
          required: "Astek Harus Diisi",
        },
        ptkp_menikah: {
          required: "PTKP (Menikah) Harus Diisi",
        },
        ptkp_anak: {
          required: "PTKP (Anak Max 3) Harus Diisi",
        },
        jaminan_kesehatan: {
          required: "Jaminan Kesehatan (JK) Harus Diisi",
        },
        jaminan_kecelakaan_kerja: {
          required: "Jaminan Kecelakaan Kerja (JKK) Harus Diisi",
        },
        persentase_pph21: {
          required: "Persentase Pph-21 Harus Diisi",
        },
        bpjs: {
          required: "BPJS Harus Diisi",
        },
        limit_max_biaya_jabatan: {
          required: "Limit Max Biaya Jabatan Harus Diisi",
        },
        uang_makan: {
          required: "Uang Makan Harus Diisi",
        },
        jam_kerja_paruh_waktu: {
          required: "Jam Kerja Paruh Waktu Harus Diisi",
        },
        jam_kerja_satu_hari: {
          required: "Jam Kerja Satu Hari Harus Diisi",
        },
        jadwal_pembayaran_gaji: {
          required: "Jadwal Pembayaran Gaji Harus Diisi",
        },
        jangka_bayar: {
          required: "Jangka bayar Harus Diisi",
        },
        sistem_upah_lembur: {
          required: "Sistem Upah Lembur Harus Diisi",
        },
        sistem_premi: {
          required: "Sistem Upah Lembur (Premi) Harus Diisi",
        },
        sistem_pembulatan: {
          required: "Sistem Pembulatan Harus Diisi",
        },
        batas_pembulatan: {
          required: "Batas Pembulatan",
        },
        direktori_backup_data: {
          required: "Direktori Backup Data",
        },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },
      submitHandler: function(form) {
        var myform = document.getElementById("edit_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('konfigurasi_upah/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#perusahaan').val(data).trigger('change');
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });

    $('#input_form').validate({
      rules: {
        input_kode_perusahaan: {
          required: true,
        },
        input_besar_upah_minimum: {
          required: true,
        },
        input_astek: {
          required: true,
        },
        input_ptkp_menikah: {
          required: true,
        },
        input_ptkp_anak: {
          required: true,
        },
        input_jaminan_kesehatan: {
          required: true,
        },
        input_jaminan_kecelakaan_kerja: {
          required: true,
        },
        input_persentase_pph21: {
          required: true,
        },
        input_bpjs: {
          required: true,
        },
        input_limit_max_biaya_jabatan: {
          required: true,
        },
        input_uang_makan: {
          required: true,
        },
        input_jam_kerja_paruh_waktu: {
          required: true,
        },
        input_jam_kerja_satu_hari: {
          required: true,
        },
        input_jadwal_pembayaran_gaji: {
          required: true,
        },
        input_jangka_bayar: {
          required: true,
        },
        input_sistem_upah_lembur: {
          required: true,
        },
        input_sistem_premi: {
          required: true,
        },
        input_sistem_pembulatan: {
          required: true,
        },
        input_batas_pembulatan: {
          required: true,
        },
        input_direktori_backup_data: {
          required: true,
        },
      },
      messages: {
        input_kode_perusahaan: {
          required: "Perusahaan Harus Diisi",
        },
        input_besar_upah_minimum: {
          required: "Besar Upah Minimum Harus Diisi",
        },
        input_astek: {
          required: "Astek Harus Diisi",
        },
        input_ptkp_menikah: {
          required: "PTKP (Menikah) Harus Diisi",
        },
        input_ptkp_anak: {
          required: "PTKP (Anak Max 3) Harus Diisi",
        },
        input_jaminan_kesehatan: {
          required: "Jaminan Kesehatan (JK) Harus Diisi",
        },
        input_jaminan_kecelakaan_kerja: {
          required: "Jaminan Kecelakaan Kerja (JKK) Harus Diisi",
        },
        input_persentase_pph21: {
          required: "Persentase Pph-21 Harus Diisi",
        },
        input_bpjs: {
          required: "BPJS Harus Diisi",
        },
        input_limit_max_biaya_jabatan: {
          required: "Limit Max Biaya Jabatan Harus Diisi",
        },
        input_uang_makan: {
          required: "Uang Makan Harus Diisi",
        },
        input_jam_kerja_paruh_waktu: {
          required: "Jam Kerja Paruh Waktu Harus Diisi",
        },
        input_jam_kerja_satu_hari: {
          required: "Jam Kerja Satu Hari Harus Diisi",
        },
        input_jadwal_pembayaran_gaji: {
          required: "Jadwal Pembayaran Gaji Harus Diisi",
        },
        input_jangka_bayar: {
          required: "Jangka bayar Harus Diisi",
        },
        input_sistem_upah_lembur: {
          required: "Sistem Upah Lembur Harus Diisi",
        },
        input_sistem_premi: {
          required: "Sistem Upah Lembur (Premi) Harus Diisi",
        },
        input_sistem_pembulatan: {
          required: "Sistem Pembulatan Harus Diisi",
        },
        input_batas_pembulatan: {
          required: "Batas Pembulatan",
        },
        input_direktori_backup_data: {
          required: "Direktori Backup Data",
        },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },
      submitHandler: function(form) {
        var myform = document.getElementById("input_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('konfigurasi_upah/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            if(data.kode == 1){
              alert("Data Sudah Ada untuk Perusahaan Tersebut. Silahkan Pilih Perusahaan Lain.");
            }else{
              var url_perusahaan = "{{ url('get_company/konfigurasi') }}";
              $.get(url_perusahaan, function (data) {
                $('#perusahaan').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
                $.each(data, function(k, v) {
                  $('#perusahaan').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
                });
              })
              $('#perusahaan').val(data.perusahaan).trigger('change');
              $('#modal_input_data').modal('hide');
              $("#modal_input_data").trigger('click');
              alert("Data Successfully Added");
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });
  });
</script>

@endsection