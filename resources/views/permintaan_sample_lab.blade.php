@extends('layouts.app_admin')

@section('title')
<title>PERMINTAAN SAMPLE - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Permintaan Sample</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Lab</li>
            <li class="breadcrumb-item">Permintaan Sample</li>
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
        <div class="card-body">
          <table id="data_permintaan_sample_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th width="13%"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog modal-xl">
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
            <input class="form-control" type="hidden" name="input_nomor" id="input_nomor" />
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive" id="dynamic_field_permintaan_sample">
              <tr>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Mesh</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">SSA</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">D-50</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">D-98</th>
                <th style="text-align: center; vertical-align: middle;" colspan="2">Whiteness</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Moisture</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Residue</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2" width="5%"></th>
              </tr>
              <tr>
                <th style="text-align: center; vertical-align: middle;">CIE 86</th>
                <th style="text-align: center; vertical-align: middle;">ISO 2470</th>
              </tr>
              <tr>
                <td><input class="form-control input_mesh_list" type="text" name="input_mesh[]" id="input_mesh1" placeholder="Mesh" /></td>
                <td><input class="form-control input_ssa_list" type="text" name="input_ssa[]" id="input_ssa1" placeholder="SSA" /></td>
                <td><input class="form-control input_d50_list" type="text" name="input_d50[]" id="input_d501" placeholder="D-50" /></td>
                <td><input class="form-control input_d98_list" type="text" name="input_d98[]" id="input_d981" placeholder="D-98" /></td>
                <td><input class="form-control input_cie86_list" type="text" name="input_cie86[]" id="input_cie861" placeholder="CIE 86" /></td>
                <td><input class="form-control input_iso2470_list" type="text" name="input_iso2470[]" id="input_iso24701" placeholder="ISO 2470" /></td>
                <td><input class="form-control input_moisture_list" type="text" name="input_moisture[]" id="input_moisture1" placeholder="Moisture" /></td>
                <td><input class="form-control input_residue_list" type="text" name="input_residue[]" id="input_residue1" placeholder="Residue" /></td>
                <td style="vertical-align : middle; text-align: center;"><button type="button" name="add_data" id="add_data" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
              </tr>
            </table>
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
              <table style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">SSA</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">D-50</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">D-98</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">Whiteness</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
                  </tr>
                  <tr>
                    <th style="text-align: center; vertical-align: middle;">CIE 86</th>
                    <th style="text-align: center; vertical-align: middle;">ISO 2470</th>
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

  <div class="modal fade" id="modal_upload_excel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Excel</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" class="upload-form" id="upload-form" action="{{ url('lab/permintaan_sample/upload') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
                <input class="form-control" type="hidden" name="upload_nomor" id="upload_nomor" />
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_permintaan_sample_lab.xlsx')}}" target="_blank">here</a>.</span>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_data">
    <div class="modal-dialog modal-xl">
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
            <input class="form-control" type="hidden" name="edit_nomor" id="edit_nomor" />
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive" id="edit_dynamic_field_permintaan_sample">
              <tr>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Mesh</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">SSA</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">D-50</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">D-98</th>
                <th style="text-align: center; vertical-align: middle;" colspan="2">Whiteness</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Moisture</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">Residue</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2" width="5%"></th>
              </tr>
              <tr>
                <th style="text-align: center; vertical-align: middle;">CIE 86</th>
                <th style="text-align: center; vertical-align: middle;">ISO 2470</th>
              </tr>
              <tr>
                <td><input class="form-control edit_mesh_list" type="text" name="edit_mesh[]" id="edit_mesh1" placeholder="Mesh" /><input class="form-control edit_nomor_detail_list" type="hidden" name="edit_nomor_detail[]" id="edit_nomor_detail1" /></td>
                <td><input class="form-control edit_ssa_list" type="text" name="edit_ssa[]" id="edit_ssa1" placeholder="SSA" /></td>
                <td><input class="form-control edit_d50_list" type="text" name="edit_d50[]" id="edit_d501" placeholder="D-50" /></td>
                <td><input class="form-control edit_d98_list" type="text" name="edit_d98[]" id="edit_d981" placeholder="D-98" /></td>
                <td><input class="form-control edit_cie86_list" type="text" name="edit_cie86[]" id="edit_cie861" placeholder="CIE 86" /></td>
                <td><input class="form-control edit_iso2470_list" type="text" name="edit_iso2470[]" id="edit_iso24701" placeholder="ISO 2470" /></td>
                <td><input class="form-control edit_moisture_list" type="text" name="edit_moisture[]" id="edit_moisture1" placeholder="Moisture" /></td>
                <td><input class="form-control edit_residue_list" type="text" name="edit_residue[]" id="edit_residue1" placeholder="Residue" /></td>
                <td style="vertical-align : middle; text-align: center;"><button type="button" name="edit_add_data" id="edit_add_data" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
              </tr>
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

  <div class="modal fade" id="modal_cari_data">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Pilih Data dari Laporan Harian</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
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
            <div class="col-6">
              <div class="form-group">
                <label for="pilih_batch">Pilih Batch</label>
                <select id="pilih_batch" name="pilih_batch" class="form-control" disabled>
                </select>
              </div>
            </div>
          </div>
          <table id="data_laporan_harian_table" style="width: 100%; font-size: 13px;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th style="vertical-align : middle; text-align: center;" rowspan="2"></th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesin</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">SSA</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">D-50</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">D-98</th>
                <th style="text-align: center;" colspan="2">Whiteness</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
              </tr>
              <tr>
                <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-save-cari" class="btn btn-primary">Save changes</button>
        </div>
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

    $('#tanggal').flatpickr({
      allowInput: true,
      disableMobile: true
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var c_permintaan = 0;
    var edit_c_permintaan = 0;

    var table = $('#data_permintaan_sample_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("lab/permintaan_sample/table") }}',
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

    function load_data_permintaan_sample(from_date = '', to_date = '')
    {
      table = $('#data_permintaan_sample_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("lab/permintaan_sample/table") }}',
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
           data:'action',
           name:'action',
           className:'dt-center'
         }
       ]
     });
    }

    function load_data_permintaan_sample_cari_data(nomor_pm = '', tanggal = '', waktu = '')
    {
      table = $('#data_laporan_harian_table').DataTable({
         processing: true,
         serverSide: true,
         bFilter: false,
         bInfo: false,
         bPaginate: false,
         ajax: {
          url:'{{ url("lab/permintaan_sample/view/data/laporan_harian") }}',
          data:{nomor_pm:nomor_pm, tanggal:tanggal, waktu:waktu},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        columns: [
         {
           data:null,
           name:null,
           className:'dt-center',
           render: function ( data, type, row)
           {
            return '<input type="hidden" name="nomor_pm[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="' + $('<div />').text(row.nomor_permintaan_sample).html() + '"><input type="hidden" name="nomor_lab[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + '"><input type="checkbox" name="dipilih[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="1">';
           }
         },
         {
           data:'nama_mesin',
           name:'nama_mesin',
           className:'dt-center'
         },
         {
           data:'mesh',
           name:'mesh',
           className:'dt-center'
         },
         {
           data:'ssa',
           name:'ssa',
           className:'dt-center'
         },
         {
           data:'d50',
           name:'d50',
           className:'dt-center'
         },
         {
           data:'d98',
           name:'d98',
           className:'dt-center'
         },
         {
           data:'cie86',
           name:'cie86',
           className:'dt-center'
         },
         {
           data:'iso2470',
           name:'iso2470',
           className:'dt-center'
         },
         {
           data:'moisture',
           name:'moisture',
           className:'dt-center'
         },
         {
           data:'residue',
           name:'residue',
           className:'dt-center'
         }
       ]
     });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#data_permintaan_sample_table').DataTable().destroy();
        load_data_permintaan_sample(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#data_permintaan_sample_table').DataTable().destroy();
      load_data_permintaan_sample();
    });

    $('body').on('click', '#input-data', function () {
      var nomor = $(this).data("id");
      $('#input_nomor').val(nomor);

      for(var i = 2; i <= window['c_permintaan']; i++){
        $('#row_data'+i).remove();
      }

      window['c_permintaan'] = 1;

      $('#add_data').unbind().click(function(){
        window['c_permintaan']++;
        $('#dynamic_field_permintaan_sample').append('<tr id="row_data'+window['c_permintaan']+'"><td><input class="form-control input_mesh_list" type="text" name="input_mesh[]" id="input_mesh'+window['c_permintaan']+'" placeholder="Mesh" /></td><td><input class="form-control input_ssa_list" type="text" name="input_ssa[]" id="input_ssa'+window['c_permintaan']+'" placeholder="SSA" /></td><td><input class="form-control input_d50_list" type="text" name="input_d50[]" id="input_d50'+window['c_permintaan']+'" placeholder="D-50" /></td><td><input class="form-control input_d98_list" type="text" name="input_d98[]" id="input_d98'+window['c_permintaan']+'" placeholder="D-98" /></td><td><input class="form-control input_cie86_list" type="text" name="input_cie86[]" id="input_cie86'+window['c_permintaan']+'" placeholder="CIE 86" /></td><td><input class="form-control input_iso2470_list" type="text" name="input_iso2470[]" id="input_iso2470'+window['c_permintaan']+'" placeholder="ISO 2470" /></td><td><input class="form-control input_moisture_list" type="text" name="input_moisture[]" id="input_moisture'+window['c_permintaan']+'" placeholder="Moisture" /></td><td><input class="form-control input_residue_list" type="text" name="input_residue[]" id="input_residue'+window['c_permintaan']+'" placeholder="Residue" /></td><td><button type="button" name="data_remove" id="'+window['c_permintaan']+'" class="btn btn-danger btn_remove_permintaan"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
      });

      $(document).on('click', '.btn_remove_permintaan', function(){  
        var button_id = $(this).attr("id");   
        $('#row_data'+button_id+'').remove();
        window['c_permintaan']--;
      });
    });

    $('body').on('click', '#view-data', function () {
      var nomor = $(this).data("id");
      var url = "{{ url('lab/permintaan_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $("#tbody_view").empty();
      $.get(url, function (data) {
        $.each(data, function(k, v) {
          $('#tbody_view').append(
            '<tr>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.mesh+'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.ssa+'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.d50+'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.d98+'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.cie86+'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.iso2470+'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.moisture+'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.residue+'</td>'+
            '</tr>'
            );
        });
      })
    });

    $('body').on('click', '#btn-save-cari', function () {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-cari').data('token'),
        },
        type: "POST",
        url: '{{ url("lab/permintaan_sample/cari") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#data_permintaan_sample_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_cari_data').modal('hide');
          $("#modal_cari_data").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('body').on('click', '#cari-data', function () {
      var nomor = $(this).data("id");
      $('#tanggal').change(function () {
        var tanggal = $(this).val();
        var url = "{{ url('lab/permintaan_sample/view/batch/laporan_harian/tanggal') }}";
        url = url.replace('tanggal', enc(tanggal.toString()));
        $.get(url, function (data) {
          if(data.length > 0){
            
            $('#pilih_batch').attr('disabled', false);
            $('#pilih_batch').children().remove().end().append('<option value="'+data[0].jam_waktu+'" selected>'+moment(data[0].jam_waktu, "HH:mm").format("HH:mm")+'</option>');
            $.each(data.slice(1), function(l, m) {
              $('#pilih_batch').append('<option value="' + m.jam_waktu + '">' + moment(m.jam_waktu, "HH:mm").format("HH:mm") + '</option>');
            });
            $('#data_laporan_harian_table').DataTable().destroy();
            load_data_permintaan_sample_cari_data(nomor, tanggal, data[0].jam_waktu);
          }else{
            $('#pilih_batch').attr('disabled', true);
            $('#pilih_batch').children().remove().end().append('<option value="" selected>Tidak Ada Data</option>');
          }
        })
      });

      $('#pilih_batch').change(function () {
        var waktu = $(this).val();
        var tanggal = $('#tanggal').val();
        $('#data_laporan_harian_table').DataTable().destroy();
        load_data_permintaan_sample_cari_data(nomor, tanggal, waktu)
      });
    });

    $('body').on('click', '#upload-data', function () {
      var nomor = $(this).data("id");
      $('#upload_nomor').val(nomor);
    });

    $('body').on('click', '#edit-data', function () {
      var nomor = $(this).data("id");
      var url = "{{ url('lab/permintaan_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $.get(url, function (data) {
        $('#edit_nomor').val(nomor);

        for(var i = 2; i <= window['edit_c_permintaan']; i++){
          $('#edit_row_data'+i).remove();
        }

        if(data.length > 0){
          $('#edit_nomor_detail'+1).val(data[0].nomor);
          $('#edit_mesh'+1).val(data[0].mesh);
          $('#edit_ssa'+1).val(data[0].ssa);
          $('#edit_d50'+1).val(data[0].d50);
          $('#edit_d98'+1).val(data[0].d98);
          $('#edit_cie86'+1).val(data[0].cie86);
          $('#edit_iso2470'+1).val(data[0].iso2470);
          $('#edit_moisture'+1).val(data[0].moisture);
          $('#edit_residue'+1).val(data[0].residue);
        }

        $('#edit_add_data').unbind().click(function(){
          window['edit_c_permintaan']++;
          $('#edit_dynamic_field_permintaan_sample').append('<tr id="edit_row_data'+window['edit_c_permintaan']+'"><td><input class="form-control edit_mesh_list" type="text" name="edit_mesh[]" id="edit_mesh'+window['edit_c_permintaan']+'" placeholder="Mesh" /><input class="form-control edit_nomor_detail_list" type="hidden" name="edit_nomor_detail[]" id="edit_nomor_detail'+window['edit_c_permintaan']+'" /></td><td><input class="form-control edit_ssa_list" type="text" name="edit_ssa[]" id="edit_ssa'+window['edit_c_permintaan']+'" placeholder="SSA" /></td><td><input class="form-control edit_d50_list" type="text" name="edit_d50[]" id="edit_d50'+window['edit_c_permintaan']+'" placeholder="D-50" /></td><td><input class="form-control edit_d98_list" type="text" name="edit_d98[]" id="edit_d98'+window['edit_c_permintaan']+'" placeholder="D-98" /></td><td><input class="form-control edit_cie86_list" type="text" name="edit_cie86[]" id="edit_cie86'+window['edit_c_permintaan']+'" placeholder="CIE 86" /></td><td><input class="form-control edit_iso2470_list" type="text" name="edit_iso2470[]" id="edit_iso2470'+window['edit_c_permintaan']+'" placeholder="ISO 2470" /></td><td><input class="form-control edit_moisture_list" type="text" name="edit_moisture[]" id="edit_moisture'+window['edit_c_permintaan']+'" placeholder="Moisture" /></td><td><input class="form-control edit_residue_list" type="text" name="edit_residue[]" id="edit_residue'+window['edit_c_permintaan']+'" placeholder="Residue" /></td><td><button type="button" name="edit_data_remove" id="'+window['edit_c_permintaan']+'" class="btn btn-danger edit_btn_remove_permintaan"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
        });

        $(document).on('click', '.edit_btn_remove_permintaan', function(){  
          var button_id = $(this).attr("id");   
          var nomor_detail = $("#edit_nomor_detail"+button_id). val();

          $.ajax({
            type: "GET",
            url: "{{ url('lab/permintaan_sample/detail/delete') }}",
            data: { 'nomor' : nomor_detail },
            success: function (data) {
              alert("Data Deleted");
            },
            error: function (data) {
              console.log('Error:', data);
            }
          });
          $('#edit_row_data'+button_id).remove();
          window['edit_c_permintaan']--;
        });

        if(data.length > 0){
          window['edit_c_permintaan'] = data.length;
        }else{
          window['edit_c_permintaan'] = 1;
        }

        for(let i = 1; i < window['edit_c_permintaan']; i++){
          $('#edit_dynamic_field_permintaan_sample').append('<tr id="edit_row_data'+(i+1)+'"><td><input class="form-control edit_mesh_list" type="text" name="edit_mesh[]" id="edit_mesh'+(i+1)+'" placeholder="Mesh" /><input class="form-control edit_nomor_detail_list" type="hidden" name="edit_nomor_detail[]" id="edit_nomor_detail'+(i+1)+'" /></td><td><input class="form-control edit_ssa_list" type="text" name="edit_ssa[]" id="edit_ssa'+(i+1)+'" placeholder="SSA" /></td><td><input class="form-control edit_d50_list" type="text" name="edit_d50[]" id="edit_d50'+(i+1)+'" placeholder="D-50" /></td><td><input class="form-control edit_d98_list" type="text" name="edit_d98[]" id="edit_d98'+(i+1)+'" placeholder="D-98" /></td><td><input class="form-control edit_cie86_list" type="text" name="edit_cie86[]" id="edit_cie86'+(i+1)+'" placeholder="CIE 86" /></td><td><input class="form-control edit_iso2470_list" type="text" name="edit_iso2470[]" id="edit_iso2470'+(i+1)+'" placeholder="ISO 2470" /></td><td><input class="form-control edit_moisture_list" type="text" name="edit_moisture[]" id="edit_moisture'+(i+1)+'" placeholder="Moisture" /></td><td><input class="form-control edit_residue_list" type="text" name="edit_residue[]" id="edit_residue'+(i+1)+'" placeholder="Residue" /></td><td><button type="button" name="edit_data_remove" id="'+(i+1)+'" class="btn btn-danger edit_btn_remove_permintaan"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');

          $('#edit_nomor_detail'+(i+1)).val(data[i].nomor);
          $('#edit_mesh'+(i+1)).val(data[i].mesh);
          $('#edit_ssa'+(i+1)).val(data[i].ssa);
          $('#edit_d50'+(i+1)).val(data[i].d50);
          $('#edit_d98'+(i+1)).val(data[i].d98);
          $('#edit_cie86'+(i+1)).val(data[i].cie86);
          $('#edit_iso2470'+(i+1)).val(data[i].iso2470);
          $('#edit_moisture'+(i+1)).val(data[i].moisture);
          $('#edit_residue'+(i+1)).val(data[i].residue);
        }
      })
    });

    $('#input_form').validate({
      rules: {
        input_nomor: {
          required: true,
        },
      },
      messages: {
        input_nomor: {
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
        var myform = document.getElementById("input_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('lab/permintaan_sample/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            for(var i = 2; i <= window['c_permintaan']; i++){
              $('#row_data'+i).remove();
            }
            var oTable = $('#data_permintaan_sample_table').dataTable();
            oTable.fnDraw(false);
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
        edit_nomor: {
          required: true,
        },
      },
      messages: {
        edit_nomor: {
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
        var myform = document.getElementById("edit_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('lab/permintaan_sample/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            for(var i = 2; i <= window['edit_c_permintaan']; i++){
              $('#edit_row_data'+i).remove();
            }
            var oTable = $('#data_permintaan_sample_table').dataTable();
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