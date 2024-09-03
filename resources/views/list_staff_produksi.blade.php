@extends('layouts.app_admin')

@section('title')
<title>LIST STAFF PRODUKSI - PT. DWI SELO GIRI MAS</title>
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
  .modal { overflow: auto !important; }
  #list_staff_produksi_table tbody tr:hover, #list_mandor_table tbody tr:hover{
    cursor: pointer;
  }
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
    }
    .save-btn-in {
      width: 100%;
    }
    .lihat-table {
      overflow-x: auto;
    }
    .radio-control {
      padding-left: 0 !important;
    }
    #lihat-staff-table td, #lihat-staff-table th {
      display:inline-block;
        padding:5px;
        width:100%;
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
          <h1 class="m-0 text-dark">List Staff Produksi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Produksi</li>
            <li class="breadcrumb-item">List Staff</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  @endsection

  @section('content')
  <div class="row"> 
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-profile-tab" data-toggle="pill" href="#tab_staff_produksi" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Staff Produksi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#tab_mandor" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Mandor</a>
            </li>
          </ul>
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="tab_staff_produksi" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
              <table id="list_staff_produksi_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Telpon</th>
                    <th>Bagian</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="tab_mandor" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="list_mandor_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Telpon</th>
                    <th>Bagian</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_lihat_staff">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_lihat_staff"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12 lihat-table">
            <table class="table" style="border: none;" id="lihat-staff-table">
              <tr>
                <td>Nomor</td>
                <td>:</td>
                <td id="td_nomor"></td>
                <td>Nama</td>
                <td>:</td>
                <td id="td_nama"></td>
              </tr>
              <tr>
                <td>Perusahaan</td>
                <td>:</td> 
                <td id="td_company"></td>
                <td>Unit</td>
                <td>:</td>
                <td id="td_unit"></td>
              </tr>
              <tr>
                <td>Bagian</td>
                <td>:</td>
                <td id="td_bagian"></td>
                <td>Shift</td>
                <td>:</td>
                <td id="td_shift"></td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td id="td_jenis_kelamin"></td>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td id="td_tanggal_lahir"></td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>:</td>
                <td id="td_alamat"></td>
                <td>Pendidikan</td>
                <td>:</td>
                <td id="td_pendidikan"></td>
              </tr>
              <tr>
                <td>Kelas Pribadi</td>
                <td>:</td>
                <td id="td_kelas_pribadi"></td>
                <td>Jabatan</td>
                <td>:</td>
                <td id="td_jabatan"></td>
              </tr>
              <tr>
                <td>Status Pernikahan</td>
                <td>:</td>
                <td id="td_status_pernikahan"></td>
                <td>Jumlah Tanggungan</td>
                <td>:</td>
                <td id="td_jumlah_tanggungan"></td>
              </tr>
              <tr>
                <td>Status Karyawan</td>
                <td>:</td>
                <td id="td_status_karyawan"></td>
                <td>Nomor HP</td>
                <td>:</td>
                <td id="td_nomor_hp"></td>
              </tr>
              <tr>
                <td>Tanggal Mulai Bekerja</td>
                <td>:</td>
                <td id="td_tanggal_mulai_bekerja"></td>
                <td>Foto</td>
                <td>:</td>
                <td id="td_foto"></td>
              </tr>
            </table>
         </div>
       </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" id="btn-edit" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_edit_staff">Edit</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_staff">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data Staff</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="edit_form" id="edit_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_nama">Nama Karyawan</label>
                  <input class="form-control" type="hidden" name="edit_nomor" id="edit_nomor" />
                  <input class="form-control" type="text" name="edit_nama" id="edit_nama" placeholder="Nama Bagian" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_company">Perusahaan</label>
                  <select id="edit_company" name="edit_company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_unit">Unit</label>
                  <select id="edit_unit" name="edit_unit" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_bagian">Bagian</label>
                  <select id="edit_bagian" name="edit_bagian" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_shift">Shift</label>
                  <select id="edit_shift" name="edit_shift" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                  <select id="edit_jenis_kelamin" name="edit_jenis_kelamin" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Jenis Kelamin</option>
                    <option value="L">Laki-Laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                  <input class="form-control" type="text" name="edit_tanggal_lahir" id="edit_tanggal_lahir" placeholder="Tanggal Lahir" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_alamat">Alamat</label>
                  <textarea class="form-control" rows="3" name="edit_alamat" id="edit_alamat" placeholder="Alamat"></textarea>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_pendidikan">Pendidikan</label>
                  <select id="edit_pendidikan" name="edit_pendidikan" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Pendidikan</option>
                    <option value="8">S2 / S3</option>
                    <option value="7">Diploma IV / S1</option>
                    <option value="6">Diploma III</option>
                    <option value="5">Diploma I / II</option>
                    <option value="4">SMK</option>
                    <option value="3">SMA / MA / Sederajat</option>
                    <option value="2">SMP / MTs / Sederajat</option>
                    <option value="1">SD / MI / Sederajat</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_kelas_pribadi">Kelas Pribadi</label>
                  <input class="form-control" type="text" name="edit_kelas_pribadi" id="edit_kelas_pribadi" placeholder="Kelas Pribadi" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_jabatan">Jabatan</label>
                  <select id="edit_jabatan" name="edit_jabatan" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_menikah">Status Pernikahan</label>
                  <select id="edit_menikah" name="edit_menikah" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status Pernikahan</option>
                    <option value="Y">Sudah Menikah</option>
                    <option value="N">Belum Menikah</option>
                    <option value="C">Cerai</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_jumlah_tanggungan">Jumlah Tanggungan</label>
                  <input class="form-control" type="text" name="edit_jumlah_tanggungan" id="edit_jumlah_tanggungan" placeholder="Jumlah Tanggungan" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_status_karyawan">Status Karyawan</label>
                  <select id="edit_status_karyawan" name="edit_status_karyawan" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status Karyawan</option>
                    <option value="A">Aktif</option>
                    <option value="NA">Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_nomor_hp">Nomor HP</label>
                  <input class="form-control" type="text" name="edit_nomor_hp" id="edit_nomor_hp" placeholder="Nomor HP" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_tanggal_mulai_bekerja">Tanggal Mulai Bekerja</label>
                  <input class="form-control" type="text" name="edit_tanggal_mulai_bekerja" id="edit_tanggal_mulai_bekerja" placeholder="Tanggal Mulai Bekerja" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_photo">Foto</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="edit_photo" name="edit_photo">
                      <label class="custom-file-label" for="edit_photo">Choose file</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <div class="custom-control custom-radio radio-control">
                    <a target="_blank" id="lihat_foto" class="btn btn-primary save-btn-in">Lihat Foto</a>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_lihat_staff">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-edit">Save changes</button>
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
  $.fn.modal.Constructor.prototype.enforceFocus = function () {};

  $(function () {
    $('#filter_tanggal').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var target = $('.nav-tabs a.nav-link.active').attr("href");

    var table = $('#list_staff_produksi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("list_staff_produksi/view") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
          $('td', row).attr('data-toggle', 'modal');
          $('td', row).attr('data-target', '#modal_lihat_staff');
          $('td', row).eq(0).removeAttr('data-toggle');
          $('td', row).eq(0).removeAttr('data-target');
        },
        columns: [
        {
         data:'nomor_karyawan',
         name:'nomor_karyawan'
       },
       {
         data:'nama_karyawan',
         name:'nama_karyawan'
       },
       {
         data:'nomor_hp',
         name:'nomor_hp'
       },
       {
         data:'bagian',
         name:'bagian'
       }
       ]
     });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#tab_staff_produksi'){
        $('#list_staff_produksi_table').DataTable().destroy();
        load_data_staff_produksi();
      }else if(target == '#tab_mandor'){
        $('#list_mandor_table').DataTable().destroy();
        load_data_mandor();
      }
    });

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

    function load_data_staff_produksi()
     {
      table = $('#list_staff_produksi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("list_staff_produksi/view") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
          $('td', row).attr('data-toggle', 'modal');
          $('td', row).attr('data-target', '#modal_lihat_staff');
          $('td', row).eq(0).removeAttr('data-toggle');
          $('td', row).eq(0).removeAttr('data-target');
        },
        columns: [
        {
         data:'nomor_karyawan',
         name:'nomor_karyawan'
       },
       {
         data:'nama_karyawan',
         name:'nama_karyawan'
       },
       {
         data:'nomor_hp',
         name:'nomor_hp'
       },
       {
         data:'bagian',
         name:'bagian'
       }
       ]
      });
     }

     function load_data_mandor()
     {
      table = $('#list_mandor_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("list_mandor/view") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
          $('td', row).attr('data-toggle', 'modal');
          $('td', row).attr('data-target', '#modal_lihat_staff');
          $('td', row).eq(0).removeAttr('data-toggle');
          $('td', row).eq(0).removeAttr('data-target');
        },
        columns: [
        {
         data:'nomor_karyawan',
         name:'nomor_karyawan'
       },
       {
         data:'nama_karyawan',
         name:'nama_karyawan'
       },
       {
         data:'nomor_hp',
         name:'nomor_hp'
       },
       {
         data:'bagian',
         name:'bagian'
       }
       ]
      });
     }
     
    $('#list_staff_produksi_table').on( 'click', 'tbody tr', function () {
      var staff = table.row(this).data();
      $('#judul_lihat_staff').html('');
      $('#judul_lihat_staff').html('Lihat Data ' + staff.nama_karyawan);
      var url = "{{ url('master_karyawan/view/id') }}";
      url = url.replace('id', enc(staff.nomor_karyawan.toString()));
      $('#td_nomor').html('');
      $('#td_nama').html('');
      $('#td_company').html('');
      $('#td_unit').html('');
      $('#td_bagian').html('');
      $('#td_shift').html('');
      $('#td_jenis_kelamin').html('');
      $('#td_tanggal_lahir').html('');
      $('#td_alamat').html('');
      $('#td_pendidikan').html('');
      $('#td_kelas_pribadi').html('');
      $('#td_jabatan').html('');
      $('#td_status_pernikahan').html('');
      $('#td_status_karyawan').html('');
      $('#td_jumlah_tanggungan').html('');
      $('#td_nomor_hp').html('');
      $('#td_tanggal_mulai_bekerja').html('');
      $('#td_foto').html('');
      $.get(url, function (data) {
        $('#td_nomor').html(data.nomor);
        $('#td_nama').html(data.nama);
        $('#td_company').html(data.company);
        $('#td_unit').html(data.unit);
        $('#td_bagian').html(data.bagian);
        $('#td_shift').html(data.shift);
        if(data.jenis_kelamin == "L"){
          $('#td_jenis_kelamin').html('Laki-Laki');
        }else if(data.jenis_kelamin == "P"){
          $('#td_jenis_kelamin').html('Perempuan');
        }
        $('#td_tanggal_lahir').html(data.tanggal_lahir);
        $('#td_alamat').html(data.alamat);
        $('#td_pendidikan').html(data.pendidikan);
        $('#td_kelas_pribadi').html(data.kelas_pribadi);
        $('#td_jabatan').html(data.jabatan);
        $('#td_status_pernikahan').html(data.status_pernikahan);
        if(data.status_pernikahan == "Y"){
          $('#td_status_pernikahan').html('Sudah Menikah');
        }else if(data.status_pernikahan == "N"){
          $('#td_status_pernikahan').html('Belum Menikah');
        }else if(data.status_pernikahan == "C"){
          $('#td_status_pernikahan').html('Cerai');
        }
        if(data.status_karyawan == "A"){
          $('#td_status_karyawan').html('Aktif');
        }else if(data.status_karyawan == "NA"){
          $('#td_status_karyawan').html('Tidak Aktif');
        }
        if(data.jumlah_tanggungan == null || data.jumlah_tanggungan == ''){
          $("#td_jumlah_tanggungan").html('---');
        }else{
          $("#td_jumlah_tanggungan").html(data.jumlah_tanggungan);
        }
        $('#td_nomor_hp').html(data.nomor_hp);
        $('#td_tanggal_mulai_bekerja').html(data.tanggal_mulai_bekerja);
        if(data.photo == null || data.photo == ''){
          $("#td_foto").html('---');
        }else{
          $("#td_foto").html('<a target="_blank" href="' + '../data_file/' + data.photo + '">Lihat Foto</a>');
        }
        $('#btn-edit').data('id', data.nomor);
      })
    });

    $('#list_mandor_table').on( 'click', 'tbody tr', function () {
      var staff = table.row(this).data();
      $('#judul_lihat_staff').html('');
      $('#judul_lihat_staff').html('Lihat Data ' + staff.nama_karyawan);
      var url = "{{ url('master_karyawan/view/id') }}";
      url = url.replace('id', enc(staff.nomor_karyawan.toString()));
      $('#td_nomor').html('');
      $('#td_nama').html('');
      $('#td_company').html('');
      $('#td_unit').html('');
      $('#td_bagian').html('');
      $('#td_shift').html('');
      $('#td_jenis_kelamin').html('');
      $('#td_tanggal_lahir').html('');
      $('#td_alamat').html('');
      $('#td_pendidikan').html('');
      $('#td_kelas_pribadi').html('');
      $('#td_jabatan').html('');
      $('#td_status_pernikahan').html('');
      $('#td_status_karyawan').html('');
      $('#td_jumlah_tanggungan').html('');
      $('#td_nomor_hp').html('');
      $('#td_tanggal_mulai_bekerja').html('');
      $('#td_foto').html('');
      $.get(url, function (data) {
        $('#td_nomor').html(data.nomor);
        $('#td_nama').html(data.nama);
        $('#td_company').html(data.company);
        $('#td_unit').html(data.unit);
        $('#td_bagian').html(data.bagian);
        $('#td_shift').html(data.shift);
        if(data.jenis_kelamin == "L"){
          $('#td_jenis_kelamin').html('Laki-Laki');
        }else if(data.jenis_kelamin == "P"){
          $('#td_jenis_kelamin').html('Perempuan');
        }
        $('#td_tanggal_lahir').html(data.tanggal_lahir);
        $('#td_alamat').html(data.alamat);
        $('#td_pendidikan').html(data.pendidikan);
        $('#td_kelas_pribadi').html(data.kelas_pribadi);
        $('#td_jabatan').html(data.jabatan);
        $('#td_status_pernikahan').html(data.status_pernikahan);
        if(data.status_pernikahan == "Y"){
          $('#td_status_pernikahan').html('Sudah Menikah');
        }else if(data.status_pernikahan == "N"){
          $('#td_status_pernikahan').html('Belum Menikah');
        }else if(data.status_pernikahan == "C"){
          $('#td_status_pernikahan').html('Cerai');
        }
        if(data.status_karyawan == "A"){
          $('#td_status_karyawan').html('Aktif');
        }else if(data.status_karyawan == "NA"){
          $('#td_status_karyawan').html('Tidak Aktif');
        }
        if(data.jumlah_tanggungan == null || data.jumlah_tanggungan == ''){
          $("#td_jumlah_tanggungan").html('---');
        }else{
          $("#td_jumlah_tanggungan").html(data.jumlah_tanggungan);
        }
        $('#td_nomor_hp').html(data.nomor_hp);
        $('#td_tanggal_mulai_bekerja').html(data.tanggal_mulai_bekerja);
        if(data.photo == null || data.photo == ''){
          $("#td_foto").html('---');
        }else{
          $("#td_foto").html('<a target="_blank" href="' + '../data_file/' + data.photo + '">Lihat Foto</a>');
        }
        $('#btn-edit').data('id', data.nomor);
      })
    });

    $("#edit_company").change(function(e) {
      e.stopImmediatePropagation();
      var val = $(this).val();
      var url_unit = "{{ url('get_unit/company') }}";
      url_unit = url_unit.replace('company', enc(val.toString()));
      $.get(url_unit, function (data_unit) {
        if(data_unit.length == 0){
          $('#edit_unit').children().remove().end();
          $('#edit_unit').attr("disabled","disabled");
          $('#edit_bagian').children().remove().end();
          $('#edit_bagian').attr("disabled","disabled");
        }else{
          $('#edit_unit').removeAttr('disabled');
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
        }
      })
    });

    $("#edit_unit").change(function(e) {
      e.stopImmediatePropagation();
      var val = $(this).val();
      var company = $('#edit_company').val();
      var url_bagian = "{{ url('get_bagian/company/unit') }}";
      url_bagian = url_bagian.replace('company', enc(company.toString()));
      url_bagian = url_bagian.replace('unit', enc(val.toString()));   
      $.get(url_bagian, function (data) {
        if(data.length == 0){
          $('#edit_bagian').children().remove().end();
          $('#edit_bagian').attr("disabled","disabled");
        }else{
          $('#edit_bagian').removeAttr('disabled');
          $('#edit_bagian').children().remove().end().append('<option value="">Pilih Bagian</option>');
          $.each(data, function(k, v) {
            $('#edit_bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
          });
        }
      })
    });

    $('#btn-edit').click(function (e) {
      var kode = $(this).data("id");
      $('#edit_unit').attr("disabled","disabled");
      $('#edit_bagian').attr("disabled","disabled");

      var url_company = "{{ url('get_company') }}";
      $.get(url_company, function (data) {
        $('#edit_company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#edit_company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })
      var url_shift = "{{ url('get_shift') }}";
      $.get(url_shift, function (data) {
        $('#edit_shift').children().remove().end().append('<option value="" selected>Pilih Shift</option>');
        $.each(data, function(k, v) {
          $('#edit_shift').append('<option value="' + v.kode_shift + '">' + v.nama_shift + ' (' + v.jam_masuk + ' - ' + v.jam_keluar + ')</option>');
        });
      })
      var url_jabatan = "{{ url('get_jabatan') }}";
      $.get(url_jabatan, function (data) {
        $('#edit_jabatan').children().remove().end().append('<option value="" selected>Pilih Jabatan</option>');
        $.each(data, function(k, v) {
          $('#edit_jabatan').append('<option value="' + v.id + '">' + v.name + '</option>');
        });
      })
      $("#edit_menikah").change(function() {
        var val = $(this).val();

        if(val == "Y" || val == "C"){
          $("#edit_jumlah_tanggungan").prop('disabled', false);
        }else if(val == "N"){
          $("#edit_jumlah_tanggungan").prop('disabled', true);
        }else{
          $("#edit_jumlah_tanggungan").prop('disabled', false);
        }
      });
      var url = "{{ url('master_karyawan/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#edit_nomor').val('');
      $('#edit_nama').val('');
      $('#edit_tanggal_lahir').val('');
      $('#edit_alamat').html('');
      $('#edit_kelas_pribadi').val('');
      $('#edit_jumlah_tanggungan').val('');
      $('#edit_nomor_hp').val('');
      $('#edit_tanggal_mulai_bekerja').val('');
      $('#edit_photo').val('');
      // $('#edit_company').val('').trigger('change');
      // $('#edit_unit').val('').trigger('change');
      // $('#edit_bagian').val('').trigger('change');
      $('#edit_shift').val('').trigger('change');
      $('#edit_jenis_kelamin').val('').trigger('change');
      $('#edit_pendidikan').val('').trigger('change');
      $('#edit_jabatan').val('').trigger('change');
      $('#edit_menikah').val('').trigger('change');
      $('#edit_status_karyawan').val('').trigger('change');
      $.get(url, function (data) {
        $('#edit_nomor').val(data.nomor);
        $('#edit_nama').val(data.nama);
        $('#edit_tanggal_lahir').val(data.tanggal_lahir);
        $('#edit_kelas_pribadi').val(data.kelas_pribadi);
        $('#edit_jumlah_tanggungan').val(data.jumlah_tanggungan);
        $('#edit_tanggal_mulai_bekerja').val(data.tanggal_mulai_bekerja);
        $('#edit_nomor_hp').val(data.nomor_hp);
        $('#edit_company').val(data.kode_perusahaan);
        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(data.kode_perusahaan.toString()));
        $.get(url_unit, function (data_unit) {
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
          $('#edit_unit').removeAttr('disabled');
          $('#edit_unit').val(data.kode_unit);
        })
        var url_bagian = "{{ url('get_bagian/company/unit') }}";
        url_bagian = url_bagian.replace('company', enc(data.kode_perusahaan.toString()));
        url_bagian = url_bagian.replace('unit', enc(data.kode_unit.toString()));
        $.get(url_bagian, function (data_bag) {
          $('#edit_bagian').children().remove().end().append('<option value="">Pilih Bagian</option>');
          $.each(data_bag, function(k, v) {
            $('#edit_bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
          });
          $('#edit_bagian').removeAttr('disabled');
          $('#edit_bagian').val(data.kode_bagian).trigger('change');
        })
        $('#edit_shift').val(data.kode_shift).trigger('change');
        $('#edit_pendidikan').val(data.kelas_pendidikan).trigger('change');
        $('#edit_jabatan').val(data.kelas_jabatan).trigger('change');
        $('#edit_jenis_kelamin').val(data.jenis_kelamin).trigger('change');
        $('#edit_menikah').val(data.status_pernikahan).trigger('change');
        $('#edit_status_karyawan').val(data.status_karyawan).trigger('change');
        $("#edit_alamat").html(data.alamat);
        if(data.photo == null){
          $('#lihat_foto').html('No Photo');
          $('#lihat_foto').addClass('disabled');
          $('#lihat_foto').attr('href', '#');
        }else{
          $('#lihat_foto').html('Lihat Foto');
          $('#lihat_foto').removeClass('disabled');
          $('#lihat_foto').attr('href', '../data_file/' + data.photo);
        }
      })
    });

    $('#edit_form').validate({
      rules: {
        edit_nama: {
          required: true,
        },
        edit_company: {
          required: true,
        },
        edit_bagian: {
          required: true,
        },
        edit_shift: {
          required: true,
        },
        edit_jenis_kelamin: {
          required: true,
        },
        edit_tanggal_lahir: {
          required: true,
        },
        edit_alamat: {
          required: true,
        },
        edit_pendidikan: {
          required: true,
        },
        edit_kelas_pribadi: {
          required: true,
        },
        edit_jabatan: {
          required: true,
        },
        edit_menikah: {
          required: true,
        },
        edit_status_karyawan: {
          required: true,
        },
        edit_nomor_hp: {
          required: true,
        },
        edit_tanggal_mulai_bekerja: {
          required: true,
        },
      },
      messages: {
        edit_nama: {
          required: "Nama Karyawan Harus Diisi",
        },
        edit_company: {
          required: "Perusahaan Harus Diisi",
        },
        edit_bagian: {
          required: "Bagian Harus Diisi",
        },
        edit_shift: {
          required: "Shift Harus Diisi",
        },
        edit_jenis_kelamin: {
          required: "Jenis Kelamin Harus Diisi",
        },
        edit_tanggal_lahir: {
          required: "Tanggal Lahir Harus Diisi",
        },
        edit_alamat: {
          required: "Alamat Harus Diisi",
        },
        edit_pendidikan: {
          required: "Pendidikan Harus Diisi",
        },
        edit_kelas_pribadi: {
          required: "Kelas Pribadi Harus Diisi",
        },
        edit_jabatan: {
          required: "Jabatan Harus Diisi",
        },
        edit_menikah: {
          required: "Status Pernikahan Harus Diisi",
        },
        edit_status_karyawan: {
          required: "Status Karyawan Harus Diisi",
        },
        edit_nomor_hp: {
          required: "Nomor HP Harus Diisi",
        },
        edit_tanggal_mulai_bekerja: {
          required: "Tanggal Mulai Bekerja Harus Diisi",
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
          url:"{{ url('master_karyawan/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_staff').modal('hide');
            $("#modal_edit_staff").trigger('click');
            var oTable = $('#list_staff_produksi_table').dataTable();
            oTable.fnDraw(false);
            var oTable = $('#list_mandor_table').dataTable();
            oTable.fnDraw(false);
            alert("Data Successfully Updated");
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

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>

@endsection