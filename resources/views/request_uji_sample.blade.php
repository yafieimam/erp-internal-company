@extends('layouts.app_admin')

@section('title')
<title>UJI KOMPETITOR - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
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
          <h1 class="m-0 text-dark">Uji Kompetitor</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Uji Kompetitor</li>
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
          <div class="row">
            <div class="col-8">
              <div class="form-group">
                <label>Date range:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" placeholder="Filter Date" class="form-control float-right" id="filter_tanggal">
                </div>
              </div>
            </div>
            <div class="col-2">
              <button type="button" name="filter" id="filter" class="btn btn-block btn-primary filter-btn">Filter</button>
            </div>
            <div class="col-2">
              <button type="button" name="refresh" id="refresh" class="btn btn-block btn-info filter-btn">Refresh</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          @if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10)
          <div class="row">
            <div class="col-4">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
            </div>
          </div>
          @endif
        </div>
        <div id="dialog-confirm"></div>
        <div class="card-body">
          <table id="data_uji_sample_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Customers</th>
                <th>Merk</th>
                <th>Type</th>
                <th>Status</th>
                <th width="15%"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog">
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
              <div class="col-12">
                <div class="form-group">
                  <label>Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="tanggal" id="tanggal" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="customers">Customers</label>
                  <select id="customers" name="customers" class="form-control select2 customers" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="new_customers">New Customers</label>
                  <input class="form-control" type="text" name="new_customers" id="new_customers" placeholder="New Customers" />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="city">Kota</label>
                  <select id="city" name="city" class="form-control select2 city" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="new_nama_cp">PIC</label>
                  <input class="form-control" type="text" name="new_nama_cp" id="new_nama_cp" placeholder="PIC" />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="new_jabatan_cp">Jabatan PIC</label>
                  <input class="form-control" type="text" name="new_jabatan_cp" id="new_jabatan_cp" placeholder="Jabatan PIC" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="bidang_usaha">Bidang Usaha</label>
                  <input class="form-control" type="text" name="bidang_usaha" id="bidang_usaha" placeholder="Bidang Usaha" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="merk">Merk</label>
                  <input class="form-control" type="text" name="merk" id="merk" placeholder="Merk" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="tipe">Tipe</label>
                  <input class="form-control" type="text" name="tipe" id="tipe" placeholder="Tipe" />
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

  <div class="modal fade" id="modal_view_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 lihat-table">
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td>Nomor</td>
                  <td>:</td>
                  <td id="td_nomor"></td>
                  <td>Tanggal</td>
                  <td>:</td>
                  <td id="td_tanggal"></td>
                </tr>
                <tr>
                  <td>Customers</td>
                  <td>:</td> 
                  <td id="td_customers"></td>
                  <td>Bidang Usaha</td>
                  <td>:</td>
                  <td id="td_bidang_usaha"></td>
                </tr>
                <tr>
                  <td>Merk</td>
                  <td>:</td>
                  <td id="td_merk"></td>
                  <td>Tipe</td>
                  <td>:</td>
                  <td id="td_tipe"></td>
                </tr>
                <tr>
                  <td>Analisa</td>
                  <td>:</td>
                  <td id="td_analisa"></td>
                  <td>Solusi</td>
                  <td>:</td>
                  <td id="td_solusi"></td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>:</td>
                  <td id="td_status"></td>
                  <td>Lampiran</td>
                  <td>:</td>
                  <td id="td_lampiran"></td>
                </tr>
                <tr>
                  <td>Komentar Produksi</td>
                  <td>:</td>
                  <td colspan="4" id="td_komentar_produksi"></td>
                </tr>
              </table>
              <h5>Data Uji Sample Lab : </h5>
              <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center; width: 40%;" colspan="2"></th>
                    <th style="vertical-align : middle; text-align: center;">Kompetitor</th>
                    <th id="head_view_data_dsgm" style="vertical-align : middle; text-align: center;">DSGM</th> 
                    <!-- <th style="vertical-align : middle; text-align: center;">Komplain</th>   -->                
                  </tr>
                </thead>
                <tbody id="tbody_view">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_data">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="edit_form" id="edit_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="edit_tanggal" id="edit_tanggal" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                  <input class="form-control" type="hidden" name="edit_nomor" id="edit_nomor" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_customers">Customers</label>
                  <select id="edit_customers" name="edit_customers" class="form-control select2 edit_customers" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_merk">Merk</label>
                  <input class="form-control" type="text" name="edit_merk" id="edit_merk" placeholder="Merk" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_tipe">Tipe</label>
                  <input class="form-control" type="text" name="edit_tipe" id="edit_tipe" placeholder="Tipe" />
                </div>
              </div>
            </div>

            <table id="edit_table_harga" style="width: 100%; font-size: 14px; display: none;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="vertical-align : middle; text-align: center; width: 40%;"></th>
                  <th style="vertical-align : middle; text-align: center;">Kompetitor</th>
                  <th id="edit_head_view_data_dsgm"style="vertical-align : middle; text-align: center;">DSGM</th>
                  <!-- <th style="vertical-align : middle; text-align: center;">Komplain</th> -->
                </tr>
              </thead>
              <tbody>
                <tr id="edit_tr_input_harga">
                  <th style="vertical-align : middle; text-align: center;">Harga</th>
                  <td><input class="form-control" type="text" name="edit_harga_kompetitor" id="edit_harga_kompetitor" placeholder="Harga" /></td>
                  <td><input class="form-control" type="text" name="edit_harga_dsgm[]" id="edit_harga_dsgm1" placeholder="Harga" /><input class="form-control" type="hidden" name="edit_jenis_produk_dsgm[]" id="edit_jenis_produk_dsgm1" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_harga_komplain" id="edit_harga_komplain" placeholder="Harga" /></td> -->
                </tr>
                <tr id="edit_tr_input_kelas">
                  <th style="vertical-align : middle; text-align: center;">Kelas Harga</th>
                  <td>
                    <select id="edit_kelas_kompetitor" name="edit_kelas_kompetitor" class="form-control">
                      <option value="Pabrik" selected>Pabrik</option>
                      <option value="Retail">Retail</option>
                      <option value="User">User</option>
                      <option value="Distributor">Distributor</option>
                    </select>
                  </td>
                  <td><input class="form-control" type="hidden" name="edit_kelas_dsgm[]" id="edit_kelas_dsgm1" /></td>
                  <!-- <td><input class="form-control" type="hidden" name="edit_kelas_komplain" id="edit_kelas_komplain" /></td> -->
                </tr>
              </tbody>
            </table>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-edit">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_update_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Data Harga</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="update_form" id="update_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="vertical-align : middle; text-align: center; width: 30%;"></th>
                  <th style="vertical-align : middle; text-align: center;">Kompetitor</th>
                  <th id="update_head_view_data_dsgm" style="vertical-align : middle; text-align: center;">DSGM</th>
                  <!-- <th style="vertical-align : middle; text-align: center;">Komplain</th> -->
                  <input class="form-control" type="hidden" name="update_nomor" id="update_nomor"/>
                </tr>
              </thead>
              <tbody>
                <tr id="tr_update_harga">
                  <th style="vertical-align : middle; text-align: center;">Harga</th>
                  <td><input class="form-control" type="text" name="harga_kompetitor" id="harga_kompetitor" placeholder="Harga" /></td>
                  <td><input class="form-control" type="text" name="harga_dsgm[]" id="harga_dsgm1" placeholder="Harga" /><input class="form-control" type="hidden" name="jenis_produk_dsgm[]" id="jenis_produk_dsgm1" /></td>
                  <!-- <td><input class="form-control" type="text" name="harga_komplain" id="harga_komplain" placeholder="Harga" /></td> -->
                </tr>
                <tr id="tr_update_kelas">
                  <th style="vertical-align : middle; text-align: center;">Kelas Harga</th>
                  <td>
                    <select id="kelas_kompetitor" name="kelas_kompetitor" class="form-control">
                      <option value="Pabrik" selected>Pabrik</option>
                      <option value="Retail">Retail</option>
                      <option value="User">User</option>
                      <option value="Distributor">Distributor</option>
                    </select>
                  </td>
                  <td><input class="form-control" type="hidden" name="kelas_dsgm[]" id="kelas_dsgm1" /></td>
                  <!-- <td><input class="form-control" type="hidden" name="kelas_komplain" id="kelas_komplain" /></td> -->
                </tr>
              </tbody>
            </table>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-update">Save changes</button>
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
  <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
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

    $('#tanggal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var c_pembanding = 0;
    var edit_c_pembanding = 0;

    var table = $('#data_uji_sample_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("uji_sample/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc'], [0,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'nomor',
           name:'nomor',
           className:'dt-center'
         },
         {
           data:'tanggal',
           name:'tanggal',
           className:'dt-center'
         },
         {
           data:'custname',
           name:'custname',
           className:'dt-center'
         },
         {
           data:'merk',
           name:'merk',
           className:'dt-center'
         },
         {
           data:'tipe',
           name:'tipe',
           className:'dt-center'
         },
         {
           data:'status',
           name:'status',
           className:'dt-center'
         },
         {
           data:'action',
           name:'action',
           className:'dt-center'
         }
       ]
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

    function load_data_uji_sample(from_date = '', to_date = '')
    {
      table = $('#data_uji_sample_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("uji_sample/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc'], [0,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'nomor',
           name:'nomor',
           className:'dt-center'
         },
         {
           data:'tanggal',
           name:'tanggal',
           className:'dt-center'
         },
         {
           data:'custname',
           name:'custname',
           className:'dt-center'
         },
         {
           data:'merk',
           name:'merk',
           className:'dt-center'
         },
         {
           data:'tipe',
           name:'tipe',
           className:'dt-center'
         },
         {
           data:'status',
           name:'status',
           className:'dt-center'
         },
         {
           data:'action',
           name:'action',
           className:'dt-center'
         }
       ]
     });
    }

    function validasiUjiSample() {
      var nomor = $('#validasi-data').data("id");

      $("#dialog-confirm").html("Uji Sample Divalidasi?");

      // console.log("Yes");

      $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "Validasi Uji Sample",
        height: 250,
        width: 400,
        buttons: {
          "Validasi": function() {
            $(this).dialog('close');
            callbackValidasiQuotation(true, nomor);
          },
          "Tidak": function() {
            $(this).dialog('close');
            // callbackValidasiQuotation(false, nomor);
          }
        }
      });
    };

    $('body').on('click', '#validasi-data', validasiUjiSample);

    function callbackValidasiQuotation(value, nomor) {
      if(value) {
        $.ajax({
          type: "GET",
          url: "{{ url('uji_sample/validasi') }}",
          data: { 'nomor' : nomor },
          success: function (data) {
            var oTable = $('#data_uji_sample_table').dataTable(); 
            oTable.fnDraw(false);
            alert("Data Validated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#data_uji_sample_table').DataTable().destroy();
        load_data_uji_sample(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#data_uji_sample_table').DataTable().destroy();
      load_data_uji_sample();
    });

    $('body').on('click', '#btn_input_data', function () {
      $('#customers').on('select2:select', function (e) {
        $('#new_customers').attr("disabled", true);
        $('#city').attr("disabled", true);
        $('#new_nama_cp').attr("disabled", true);
        $('#new_jabatan_cp').attr("disabled", true);
        $('#bidang_usaha').attr("disabled", true);
      });

      $('#customers').on('select2:unselect', function (e) {
        $('#new_customers').attr("disabled", false);
        $('#city').attr("disabled", false);
        $('#new_nama_cp').attr("disabled", false);
        $('#new_jabatan_cp').attr("disabled", false);
        $('#bidang_usaha').attr("disabled", false);
      });
    });

    $('body').on('click', '#view-data', function () {
      var nomor = $(this).data("id");
      var url = "{{ url('uji_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $('#td_nomor').html('');
      $('#td_tanggal').html('');
      $('#td_customers').html('');
      $('#td_bidang_usaha').html('');
      $('#td_merk').html('');
      $('#td_tipe').html('');
      $('#td_status').html('');
      $('#td_lampiran').html('');
      $('#td_solusi').html('');
      $('#td_analisa').html('');
      $('#td_komentar_produksi').html('');
      $.get(url, function (data) {
        $('#td_nomor').html(data.data_uji.nomor);
        $('#td_tanggal').html(data.data_uji.tanggal);
        $('#td_customers').html(data.data_uji.custname);
        $('#td_merk').html(data.data_uji.merk);
        $('#td_tipe').html(data.data_uji.tipe);
        $('#td_status').html(data.data_uji.status);
        if(data.data_uji.analisa){
          $('#td_analisa').html(data.data_uji.analisa);
        }else{
          $('#td_analisa').html('--');
        }
        if(data.data_uji.bidang_usaha){
          $('#td_bidang_usaha').html(data.data_uji.bidang_usaha);
        }else{
          $('#td_bidang_usaha').html('--');
        }
        if(data.data_uji.solusi){
          $('#td_solusi').html(data.data_uji.solusi);
        }else{
          $('#td_solusi').html('--');
        }
        if(data.data_uji.komentar_produksi){
          $('#td_komentar_produksi').html(data.data_uji.komentar_produksi);
        }else{
          $('#td_komentar_produksi').html('--');
        }
        if(data.data_uji.lampiran){
          $('#td_lampiran').html('<a target="_blank" href="' + '../data_file/' + data.data_uji.lampiran + '">Lihat Lampiran</a>');
        }else{
          $('#td_lampiran').html('--');
        }
      })

      var url_lab = "{{ url('lab/uji_sample/view/nomor') }}";
      url_lab = url_lab.replace('nomor', enc(nomor.toString()));
      $("#tbody_view").empty();
      $.get(url_lab, function (data) {
        $('#head_view_data_dsgm').attr('colspan', data[1]);
        $.each(data[0], function(k, v) {
          if(k == 'CIE86'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" rowspan="2">Whiteness</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(j) == 0  || parseFloat(j) == null ? '-': (parseFloat(j) % 1 === 0 ? parseFloat(j).toFixed(1) : parseFloat(j)))+'</td>'
              ); 
            });
          }else if(k == 'ISO2470'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(j) == 0  || parseFloat(j) == null ? '-': (parseFloat(j) % 1 === 0 ? parseFloat(j).toFixed(1) : parseFloat(j)))+'</td>'
              ); 
            });
          }else if(k == 'SSA'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" colspan="2">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(j == 0  || j == null ? '-': j)+'</td>'
              ); 
            });
          }else if(k == 'Kalsium'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" colspan="2">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              if(j == null || j == ''){
                $('#view_data_'+k).append(
                  '<td style="vertical-align : middle; text-align: center;">-</td>'
                );
              }else{
                $('#view_data_'+k).append(
                  '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(j) == 0  || parseFloat(j) == null ? '-': (parseFloat(j) % 1 === 0 ? parseFloat(j).toFixed(1) : parseFloat(j)))+'</td>'
                ); 
              }
            });
          }else if(k == 'Harga'){
            return;
          }else if(k == 'Kelas'){
            return;
          }else if(k == 'Produk'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" colspan="2">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(j == null ? '-': j)+'</td>'
              ); 
            });
          }else{
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" colspan="2">'+k+'</td>'+
              '</tr>'
            );
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(j) == 0  || parseFloat(j) == null ? '-': (parseFloat(j) % 1 === 0 ? parseFloat(j).toFixed(1) : parseFloat(j)))+'</td>'
              ); 
            });
          }
        });
      })
    });

    $('body').on('click', '#update-data', function () {
      var nomor = $(this).data("id");
      $('#update_nomor').val(nomor);

      for(var i = 2; i <= window['c_pembanding']; i++){
        $('#td_input_harga'+i).remove();
        $('#td_input_kelas'+i).remove();
      }

      var url = "{{ url('lab/uji_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $("#tbody_view").empty();
      $.get(url, function (data) {
        window['c_pembanding'] = data[1];
          $('#update_head_view_data_dsgm').attr('colspan', data[1]);
          $('#jenis_produk_dsgm1').val(data[0].Produk[1]);
          for(var i = 2; i <= data[1]; i++){
            $('#tr_update_harga').append('<td id="td_input_harga'+i+'"><input class="form-control" type="text" name="harga_dsgm[]" id="harga_dsgm'+i+'" placeholder="Harga" /><input class="form-control" type="hidden" name="jenis_produk_dsgm[]" id="jenis_produk_dsgm'+i+'" value="'+data[0].Produk[i]+'" /></td>');
            $('#tr_update_kelas').append('<td id="td_input_kelas'+i+'"><input class="form-control" type="hidden" name="kelas_dsgm[]" id="kelas_dsgm'+i+'" /></td>');
          }
      })
    });

    $('body').on('click', '#edit-data', function () {
      var nomor = $(this).data("id");
      var arr_produk = ['kompetitor', 'dsgm'];

      for(var i = 2; i <= window['edit_c_pembanding']; i++){
        $('#edit_td_input_harga'+i).remove();
        $('#edit_td_input_kelas'+i).remove();
      }

      var url = "{{ url('uji_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $('#edit_nomor').val(nomor);
      $('#edit_tanggal').val('');
      $('#edit_customers').val('').trigger('change');
      $('#edit_merk').val('');
      $('#edit_tipe').val('');
      $.get(url, function (data) {
        $('#edit_tanggal').val(data.data_uji.tanggal);
        $("#edit_customers").append('<option value="' + data.data_uji.customers + '">' + data.data_uji.custname + '</option>');
        $("#edit_customers").val(data.data_uji.customers);
        $("#edit_customers").trigger('change');
        $('#edit_merk').val(data.data_uji.merk);
        $('#edit_tipe').val(data.data_uji.tipe);
        window['edit_c_pembanding'] = data.data_count;
        $('#edit_head_view_data_dsgm').attr('colspan', data.data_count);
        if(data.data_harga['1'][0].harga == null){
          $('#edit_table_harga').hide();
          $('#edit_harga_' + arr_produk[0]).val('');
        }else{
          $('#edit_table_harga').show();
          $('#edit_harga_' + arr_produk[0]).val(data.data_harga['1'][0].harga);
        }
        if(data.data_harga['1'][0].kelas == null){
          $('#edit_kelas_' + arr_produk[0]).val('');
        }else{
          $('#edit_kelas_' + arr_produk[0]).val(data.data_harga['1'][0].kelas);
        }

        $('#edit_harga_dsgm1').val(data.data_harga['2'][0].harga);
        $('#edit_jenis_produk_dsgm1').val(data.data_harga['2'][0].jenis_produk);
        $('#edit_kelas_dsgm1').val('');
        for(var i = 2; i <= data.data_count; i++){
          $('#edit_tr_input_harga').append('<td id="edit_td_input_harga'+i+'"><input class="form-control" type="text" name="edit_harga_dsgm[]" id="edit_harga_dsgm'+i+'" placeholder="Harga" value="'+data.data_harga['2'][i-1].harga+'"/><input class="form-control" type="text" name="edit_jenis_produk_dsgm[]" id="edit_jenis_produk_dsgm'+i+'" value="'+data.data_harga['2'][i-1].jenis_produk+'" /></td>');
          $('#edit_tr_input_kelas').append('<td id="edit_td_input_kelas'+i+'"><input class="form-control" type="hidden" name="edit_kelas_dsgm[]" id="edit_kelas_dsgm'+i+'" value="" /></td>');
        }
      })
    });

    $('body').on('click', '#delete-data', function () {
      var nomor = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('uji_sample/delete') }}",
          data: { 'nomor' : nomor },
          success: function (data) {
            var oTable = $('#data_uji_sample_table').dataTable();
            oTable.fnDraw(false);
            alert("Data Deleted");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#input_form').validate({
      rules: {
        customers: {
          required: function(element) {
            return $("#new_customers").val() == '';
          }
        },
        new_customers: {
          required: function(element) {
            return $("#customers").val() == '';
          }
        },
        city: {
          required: function(element) {
            return $("#customers").val() == '';
          }
        },
        tanggal: {
          required: true,
        },
        merk: {
          required: true,
        },
        tipe: {
          required: true,
        },
      },
      messages: {
        customers: {
          required: "Customers Harus Diisi",
        },
        new_customers: {
          required: "Customer Baru Harus Diisi",
        },
        city: {
          required: "Kota Harus Diisi",
        },
        tanggal: {
          required: "Tanggal Harus Diisi",
        },
        merk: {
          required: "Merk Harus Diisi",
        },
        tipe: {
          required: "Tipe Harus Diisi",
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
          url:"{{ url('uji_sample/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_uji_sample_table').dataTable();
            oTable.fnDraw(false);
            $('#customers').val('').trigger('change');
            $('#city').val('').trigger('change');
            alert("Data Successfully Added");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });

    $('#edit_form').validate({
      rules: {
        edit_customers: {
          required: true,
        },
        edit_tanggal: {
          required: true,
        },
        edit_merk: {
          required: true,
        },
        edit_tipe: {
          required: true,
        },
      },
      messages: {
        edit_customers: {
          required: "Customers Harus Diisi",
        },
        edit_tanggal: {
          required: "Tanggal Harus Diisi",
        },
        edit_merk: {
          required: "Merk Harus Diisi",
        },
        edit_tipe: {
          required: "Tipe Harus Diisi",
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
          url:"{{ url('uji_sample/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_uji_sample_table').dataTable();
            oTable.fnDraw(false);
            $('#edit_customers').val('').trigger('change');
            $('#edit_city').val('').trigger('change');
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });

    $('#update_form').validate({
      rules: {
        update_nomor: {
          required: true,
        },
      },
      messages: {
        update_nomor: {
          required: "Nomor Harus Diisi",
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
        var myform = document.getElementById("update_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('uji_sample/input/harga') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#update_form').trigger("reset");
            $('#modal_update_data').modal('hide');
            $("#modal_update_data").trigger('click');
            var oTable = $('#data_uji_sample_table').dataTable();
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
  $(document).ready(function(){
    $('.customers').select2({
      dropdownParent: $('#modal_input_data .modal-content'),
      placeholder: 'Pilih Customers',
      allowClear: true,
      ajax: {
        url: '/autocomplete',
        data: function (params) {
          var company = 'DSGM';
          return {
            q: params.term,
            company: company
          };
        },
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.custname,
                id: item.custid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.edit_customers').select2({
      dropdownParent: $('#modal_edit_data .modal-content'),
      placeholder: 'Pilih Customers',
      allowClear: true,
      ajax: {
        url: '/autocomplete',
        data: function (params) {
          var company = 'DSGM';
          return {
            q: params.term,
            company: company
          };
        },
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.custname,
                id: item.custid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.city').select2({
      dropdownParent: $('#modal_input_data .modal-content'),
      placeholder: 'Pilih Kota',
      allowClear: true,
      ajax: {
        url: '/dropdown_city',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.name,
                id: item.id_kota
              }
            })
          };
        },
        cache: true
      }
    });
  });
</script>

<script type="text/javascript">
  $(".customers").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".customers").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".edit_customers").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".edit_customers").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".city").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Kota / Kabupaten...");
  });
  $(".city").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>

@endsection