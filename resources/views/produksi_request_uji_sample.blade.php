@extends('layouts.app_admin')

@section('title')
<title>UJI KOMPETITOR - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Uji Kompetitor</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Produksi</li>
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
                <th width="13%"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
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
              <div class="col-12">
                <div class="form-group">
                  <label for="komentar">Komentar</label>
                  <textarea class="form-control" rows="3" name="komentar" id="komentar" placeholder="Komentar"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <button type="button" name="add_data_pembanding" id="add_data_pembanding" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Data</button>
                  <button type="button" name="remove_data_pembanding" id="remove_data_pembanding" class="btn btn-danger" style="display: none;"><i class="fa fa-times" aria-hidden="true"></i> Hapus Data</button>
                </div>
              </div>
            </div>
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="vertical-align : middle; text-align: center; width: 30%;" colspan="2"></th>
                  <th id="head_input_data_dsgm" style="vertical-align : middle; text-align: center;">DSGM</th>
                  <input class="form-control" type="hidden" name="nomor" id="nomor"/>
                </tr>
              </thead>
              <tbody>
                <tr id="tr_input_produk">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Produk</th>
                  <td style="vertical-align : middle; text-align: center;">
                    <input class="form-control" type="text" name="produk_pembanding[]" id="produk_pembanding1" placeholder="Produk Pembanding" />
                  </td>
                </tr>
                <tr id="tr_input_kalsium">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Kadar Kalsium</th>
                  <td><input class="form-control" type="text" name="kalsium_dsgm[]" id="kalsium_dsgm1" placeholder="Kadar Kalsium" /></td>
                </tr>
                <tr id="tr_input_cie86">
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Whiteness</th>
                  <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                  <td><input class="form-control" type="text" name="cie86_dsgm[]" id="cie86_dsgm1" placeholder="CIE 86" /></td>
                </tr>
                <tr id="tr_input_iso2470">
                  <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                  <td><input class="form-control" type="text" name="iso2470_dsgm[]" id="iso2470_dsgm1" placeholder="ISO 2470" /></td>
                </tr>
                <tr id="tr_input_moisture">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Moisture</th>
                  <td><input class="form-control" type="text" name="moisture_dsgm[]" id="moisture_dsgm1" placeholder="Moisture" /></td>
                </tr>
                <tr id="tr_input_ssa">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                  <td><input class="form-control" type="text" name="ssa_dsgm[]" id="ssa_dsgm1" placeholder="SSA" /></td>
                </tr>
                <tr id="tr_input_d50">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                  <td><input class="form-control" type="text" name="d50_dsgm[]" id="d50_dsgm1" placeholder="D-50" /></td>
                </tr>
                <tr id="tr_input_d98">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                  <td><input class="form-control" type="text" name="d98_dsgm[]" id="d98_dsgm1" placeholder="D-98" /></td>
                </tr>
                <tr id="tr_input_residue">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Residue</th>
                  <td><input class="form-control" type="text" name="residue_dsgm[]" id="residue_dsgm1" placeholder="Residue" /></td>
                </tr>
              </tbody>
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
    <div class="modal-dialog modal-lg">
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
                    <th style="vertical-align : middle; text-align: center; width: 30%;" colspan="2"></th>
                    <th style="vertical-align : middle; text-align: center;">Kompetitor</th>
                    <th id="head_view_data_dsgm" style="vertical-align : middle; text-align: center;">DSGM</th> 
                    <!-- <th style="vertical-align : middle; text-align: center;">Komplain</th>                   -->
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
                  <label for="edit_komentar">Komentar</label>
                  <textarea class="form-control" rows="3" name="edit_komentar" id="edit_komentar" placeholder="Komentar"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <button type="button" name="edit_add_data_pembanding" id="edit_add_data_pembanding" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Data</button>
                  <button type="button" name="edit_remove_data_pembanding" id="edit_remove_data_pembanding" class="btn btn-danger" style="display: none;"><i class="fa fa-times" aria-hidden="true"></i> Hapus Data</button>
                </div>
              </div>
            </div>
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="vertical-align : middle; text-align: center; width: 30%;" colspan="2"></th>
                  <th id="edit_head_input_data_dsgm" style="vertical-align : middle; text-align: center;">DSGM</th>
                  <input class="form-control" type="hidden" name="edit_nomor" id="edit_nomor"/>
                </tr>
              </thead>
              <tbody>
                <tr id="edit_tr_input_produk">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Produk</th>
                  <td style="vertical-align : middle; text-align: center;">
                    <input class="form-control" type="text" name="edit_produk_pembanding[]" id="edit_produk_pembanding1" placeholder="Produk Pembanding" />
                    <input class="form-control" type="hidden" name="edit_produk_pembanding_lama[]" id="edit_produk_pembanding_lama1" />
                  </td>
                </tr>
                <tr id="edit_tr_input_kalsium">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Kadar Kalsium</th>
                  <td><input class="form-control" type="text" name="edit_kalsium_dsgm[]" id="edit_kalsium_dsgm1" placeholder="Kadar Kalsium" /></td>
                </tr>
                <tr id="edit_tr_input_cie86">
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Whiteness</th>
                  <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                  <td><input class="form-control" type="text" name="edit_cie86_dsgm[]" id="edit_cie86_dsgm1" placeholder="CIE 86" /></td>
                </tr>
                <tr id="edit_tr_input_iso2470">
                  <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                  <td><input class="form-control" type="text" name="edit_iso2470_dsgm[]" id="edit_iso2470_dsgm1" placeholder="ISO 2470" /></td>
                </tr>
                <tr id="edit_tr_input_moisture">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Moisture</th>
                  <td><input class="form-control" type="text" name="edit_moisture_dsgm[]" id="edit_moisture_dsgm1" placeholder="Moisture" /></td>
                </tr>
                <tr id="edit_tr_input_ssa">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                  <td><input class="form-control" type="text" name="edit_ssa_dsgm[]" id="edit_ssa_dsgm1" placeholder="SSA" /></td>
                </tr>
                <tr id="edit_tr_input_d50">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                  <td><input class="form-control" type="text" name="edit_d50_dsgm[]" id="edit_d50_dsgm1" placeholder="D-50" /></td>
                </tr>
                <tr id="edit_tr_input_d98">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                  <td><input class="form-control" type="text" name="edit_d98_dsgm[]" id="edit_d98_dsgm1" placeholder="D-98" /></td>
                </tr>
                <tr id="edit_tr_input_residue">
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Residue</th>
                  <td><input class="form-control" type="text" name="edit_residue_dsgm[]" id="edit_residue_dsgm1" placeholder="Residue" /></td>
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
          url:'{{ url("produksi/uji_sample/table") }}',
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
          url:'{{ url("produksi/uji_sample/table") }}',
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

    $('body').on('click', '#input-data', function () {
      var nomor = $(this).data("id");
      $('#nomor').val(nomor);

      window['c_pembanding'] = 1;

      $('#add_data_pembanding').unbind().click(function(){
        $('#remove_data_pembanding').show();
        window['c_pembanding']++;
        $('#head_input_data_dsgm').attr('colspan', window['c_pembanding']);
        $('#tr_input_produk').append('<td id="td_input_produk'+window['c_pembanding']+'"><input class="form-control" type="text" name="produk_pembanding[]" id="produk_pembanding'+window['c_pembanding']+'"placeholder="Produk Pembanding" /></td>');
        $('#tr_input_kalsium').append('<td id="td_input_kalsium'+window['c_pembanding']+'"><input class="form-control" type="text" name="kalsium_dsgm[]" id="kalsium_dsgm'+window['c_pembanding']+'"placeholder="Kadar Kalsium" /></td>');
        $('#tr_input_cie86').append('<td id="td_input_cie86'+window['c_pembanding']+'"><input class="form-control" type="text" name="cie86_dsgm[]" id="cie86_dsgm'+window['c_pembanding']+'"placeholder="CIE 86" /></td>');
        $('#tr_input_iso2470').append('<td id="td_input_iso2470'+window['c_pembanding']+'"><input class="form-control" type="text" name="iso2470_dsgm[]" id="iso2470_dsgm'+window['c_pembanding']+'"placeholder="ISO 2470" /></td>');
        $('#tr_input_moisture').append('<td id="td_input_moisture'+window['c_pembanding']+'"><input class="form-control" type="text" name="moisture_dsgm[]" id="moisture_dsgm'+window['c_pembanding']+'"placeholder="Moisture" /></td>');
        $('#tr_input_ssa').append('<td id="td_input_ssa'+window['c_pembanding']+'"><input class="form-control" type="text" name="ssa_dsgm[]" id="ssa_dsgm'+window['c_pembanding']+'"placeholder="SSA" /></td>');
        $('#tr_input_d50').append('<td id="td_input_d50'+window['c_pembanding']+'"><input class="form-control" type="text" name="d50_dsgm[]" id="d50_dsgm'+window['c_pembanding']+'"placeholder="D-50" /></td>');
        $('#tr_input_d98').append('<td id="td_input_d98'+window['c_pembanding']+'"><input class="form-control" type="text" name="d98_dsgm[]" id="d98_dsgm'+window['c_pembanding']+'"placeholder="D-98" /></td>');
        $('#tr_input_residue').append('<td id="td_input_residue'+window['c_pembanding']+'"><input class="form-control" type="text" name="residue_dsgm[]" id="residue_dsgm'+window['c_pembanding']+'"placeholder="Residue" /></td>');
      });

      $(document).on('click', '#remove_data_pembanding', function(){  
        $('#td_input_produk'+window['c_pembanding']).remove();
        $('#td_input_kalsium'+window['c_pembanding']).remove();
        $('#td_input_cie86'+window['c_pembanding']).remove();
        $('#td_input_iso2470'+window['c_pembanding']).remove();
        $('#td_input_moisture'+window['c_pembanding']).remove();
        $('#td_input_ssa'+window['c_pembanding']).remove();
        $('#td_input_d50'+window['c_pembanding']).remove();
        $('#td_input_d98'+window['c_pembanding']).remove();
        $('#td_input_residue'+window['c_pembanding']).remove();
        window['c_pembanding']--;

        if(window['c_pembanding'] == 1){
          $('#remove_data_pembanding').hide();
        }
      });
    });

    $('body').on('click', '#validasi-data', function () {
      var nomor = $(this).data("id");
      $('#nomor').val(nomor);
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
        $('#td_bidang_usaha').html(data.data_uji.bidang_usaha);
        $('#td_merk').html(data.data_uji.merk);
        $('#td_tipe').html(data.data_uji.tipe);
        $('#td_status').html(data.data_uji.status);
        if(data.data_uji.analisa){
          $('#td_analisa').html(data.data_uji.analisa);
        }else{
          $('#td_analisa').html('--');
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
        if(data[0].Produk.length == 0){
          $('#tbody_view').append(
            '<tr>'+
            '<td style="vertical-align : middle; text-align: center;" colspan="4">Belum Ada Data</td>'+
            '</tr>'
          );
        }else{
          $('#head_view_data_dsgm').attr('colspan', data[1]);
        }
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

    $('body').on('click', '#edit-data', function () {
      var nomor = $(this).data("id");

      $('#edit_nomor').val(nomor);
      $('#edit_komentar').val('');

      var url_data = "{{ url('uji_sample/view/nomor') }}";
      url_data = url_data.replace('nomor', enc(nomor.toString()));
      $.get(url_data, function (data_prd) {
        $('#edit_komentar').val(data_prd.data_uji.komentar_produksi);
      })

      var url = "{{ url('produksi/uji_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
            $.get(url, function (data) {
        for(var i = 2; i <= window['edit_c_pembanding']; i++){
          $('#edit_td_input_produk'+i).remove();
          $('#edit_td_input_kalsium'+i).remove();
          $('#edit_td_input_cie86'+i).remove();
          $('#edit_td_input_iso2470'+i).remove();
          $('#edit_td_input_moisture'+i).remove();
          $('#edit_td_input_ssa'+i).remove();
          $('#edit_td_input_d50'+i).remove();
          $('#edit_td_input_d98'+i).remove();
          $('#edit_td_input_residue'+i).remove();
        }

        if(data.Produk.length > 0){
          window['edit_c_pembanding'] = data.Produk.length;
          $('#edit_produk_pembanding1').val(data.Produk[0]);
          $('#edit_produk_pembanding_lama1').val(data.Produk[0]);
          $('#edit_kalsium_dsgm1').val(data.Kalsium[0]);
          $('#edit_cie86_dsgm1').val(data.CIE86[0]);
          $('#edit_iso2470_dsgm1').val(data.ISO2470[0]);
          $('#edit_moisture_dsgm1').val(data.Moisture[0]);
          $('#edit_ssa_dsgm1').val(data.SSA[0]);
          $('#edit_d50_dsgm1').val(data.D50[0]);
          $('#edit_d98_dsgm1').val(data.D98[0]);
          $('#edit_residue_dsgm1').val(data.Residue[0]);
          $('#edit_remove_data_pembanding').show();
        }else{
          window['edit_c_pembanding'] = 1;
          $('#edit_remove_data_pembanding').hide();
        }

        $('#edit_head_input_data_dsgm').attr('colspan', window['edit_c_pembanding']);

        for(let i = 1; i < window['edit_c_pembanding']; i++){
          $('#edit_tr_input_produk').append('<td id="edit_td_input_produk'+(i+1)+'"><input class="form-control" type="text" name="edit_produk_pembanding[]" id="edit_produk_pembanding'+(i+1)+'"placeholder="Produk Pembanding" /><input class="form-control" type="hidden" name="edit_produk_pembanding_lama[]" id="edit_produk_pembanding_lama'+(i+1)+'" /></td>');
          $('#edit_tr_input_kalsium').append('<td id="edit_td_input_kalsium'+(i+1)+'"><input class="form-control" type="text" name="edit_kalsium_dsgm[]" id="edit_kalsium_dsgm'+(i+1)+'"placeholder="Kadar Kalsium" /></td>');
          $('#edit_tr_input_cie86').append('<td id="edit_td_input_cie86'+(i+1)+'"><input class="form-control" type="text" name="edit_cie86_dsgm[]" id="edit_cie86_dsgm'+(i+1)+'"placeholder="CIE 86" /></td>');
          $('#edit_tr_input_iso2470').append('<td id="edit_td_input_iso2470'+(i+1)+'"><input class="form-control" type="text" name="edit_iso2470_dsgm[]" id="edit_iso2470_dsgm'+(i+1)+'"placeholder="ISO 2470" /></td>');
          $('#edit_tr_input_moisture').append('<td id="edit_td_input_moisture'+(i+1)+'"><input class="form-control" type="text" name="edit_moisture_dsgm[]" id="edit_moisture_dsgm'+(i+1)+'"placeholder="Moisture" /></td>');
          $('#edit_tr_input_ssa').append('<td id="edit_td_input_ssa'+(i+1)+'"><input class="form-control" type="text" name="edit_ssa_dsgm[]" id="edit_ssa_dsgm'+(i+1)+'"placeholder="SSA" /></td>');
          $('#edit_tr_input_d50').append('<td id="edit_td_input_d50'+(i+1)+'"><input class="form-control" type="text" name="edit_d50_dsgm[]" id="edit_d50_dsgm'+(i+1)+'"placeholder="D-50" /></td>');
          $('#edit_tr_input_d98').append('<td id="edit_td_input_d98'+(i+1)+'"><input class="form-control" type="text" name="edit_d98_dsgm[]" id="edit_d98_dsgm'+(i+1)+'"placeholder="D-98" /></td>');
          $('#edit_tr_input_residue').append('<td id="edit_td_input_residue'+(i+1)+'"><input class="form-control" type="text" name="edit_residue_dsgm[]" id="edit_residue_dsgm'+(i+1)+'"placeholder="Residue" /></td>');

          $('#edit_produk_pembanding'+(i+1)).val(data.Produk[i]);
          $('#edit_produk_pembanding_lama'+(i+1)).val(data.Produk[i]);
          $('#edit_kalsium_dsgm'+(i+1)).val(data.Kalsium[i]);
          $('#edit_cie86_dsgm'+(i+1)).val(data.CIE86[i]);
          $('#edit_iso2470_dsgm'+(i+1)).val(data.ISO2470[i]);
          $('#edit_moisture_dsgm'+(i+1)).val(data.Moisture[i]);
          $('#edit_ssa_dsgm'+(i+1)).val(data.SSA[i]);
          $('#edit_d50_dsgm'+(i+1)).val(data.D50[i]);
          $('#edit_d98_dsgm'+(i+1)).val(data.D98[i]);
          $('#edit_residue_dsgm'+(i+1)).val(data.Residue[i]);
        }
      })

      $('#edit_add_data_pembanding').unbind().click(function(){
        $('#edit_remove_data_pembanding').show();
        window['edit_c_pembanding']++;
        $('#edit_head_input_data_dsgm').attr('colspan', window['edit_c_pembanding']);
        $('#edit_tr_input_produk').append('<td id="edit_td_input_produk'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_produk_pembanding_lama[]" id="edit_produk_pembanding_lama'+window['edit_c_pembanding']+'"placeholder="Produk Pembanding" /></td>');
        $('#edit_tr_input_kalsium').append('<td id="edit_td_input_kalsium'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_kalsium_dsgm[]" id="edit_kalsium_dsgm'+window['edit_c_pembanding']+'"placeholder="Kadar Kalsium" /></td>');
        $('#edit_tr_input_cie86').append('<td id="edit_td_input_cie86'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_cie86_dsgm[]" id="edit_cie86_dsgm'+window['edit_c_pembanding']+'"placeholder="CIE 86" /></td>');
        $('#edit_tr_input_iso2470').append('<td id="edit_td_input_iso2470'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_iso2470_dsgm[]" id="edit_iso2470_dsgm'+window['edit_c_pembanding']+'"placeholder="ISO 2470" /></td>');
        $('#edit_tr_input_moisture').append('<td id="edit_td_input_moisture'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_moisture_dsgm[]" id="edit_moisture_dsgm'+window['edit_c_pembanding']+'"placeholder="Moisture" /></td>');
        $('#edit_tr_input_ssa').append('<td id="edit_td_input_ssa'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_ssa_dsgm[]" id="edit_ssa_dsgm'+window['edit_c_pembanding']+'"placeholder="SSA" /></td>');
        $('#edit_tr_input_d50').append('<td id="edit_td_input_d50'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_d50_dsgm[]" id="edit_d50_dsgm'+window['edit_c_pembanding']+'"placeholder="D-50" /></td>');
        $('#edit_tr_input_d98').append('<td id="edit_td_input_d98'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_d98_dsgm[]" id="edit_d98_dsgm'+window['edit_c_pembanding']+'"placeholder="D-98" /></td>');
        $('#edit_tr_input_residue').append('<td id="edit_td_input_residue'+window['edit_c_pembanding']+'"><input class="form-control" type="text" name="edit_residue_dsgm[]" id="edit_residue_dsgm'+window['edit_c_pembanding']+'"placeholder="Residue" /></td>');
      });

      $(document).on('click', '#edit_remove_data_pembanding', function(){  
        var nomor = $("#edit_nomor_produk"+button_id).val();

        if(nomor != null || nomor != ''){
          $.ajax({
            type: "GET",
            url: "{{ url('surat_penawaran/detail/delete') }}",
            data: { 'nomor' : nomor },
            success: function (data) {
              alert("Data Deleted");
            },
            error: function (data) {
              console.log('Error:', data);
            }
          });
        }

        $('#edit_td_input_produk'+window['edit_c_pembanding']).remove();
        $('#edit_td_input_kalsium'+window['edit_c_pembanding']).remove();
        $('#edit_td_input_cie86'+window['edit_c_pembanding']).remove();
        $('#edit_td_input_iso2470'+window['edit_c_pembanding']).remove();
        $('#edit_td_input_moisture'+window['edit_c_pembanding']).remove();
        $('#edit_td_input_ssa'+window['edit_c_pembanding']).remove();
        $('#edit_td_input_d50'+window['edit_c_pembanding']).remove();
        $('#edit_td_input_d98'+window['edit_c_pembanding']).remove();
        $('#edit_td_input_residue'+window['edit_c_pembanding']).remove();
        window['edit_c_pembanding']--;

        if(window['edit_c_pembanding'] == 1){
          $('#edit_remove_data_pembanding').hide();
        }
      });
    });

    $('#input_form').validate({
      rules: {
        nomor: {
          required: true,
        },
      },
      messages: {
        nomor: {
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
          url:"{{ url('produksi/uji_sample/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_uji_sample_table').dataTable();
            oTable.fnDraw(false);
            for(var i = 2; i <= window['c_pembanding']; i++){
              $('#td_input_produk'+i).remove();
              $('#td_input_kalsium'+i).remove();
              $('#td_input_cie86'+i).remove();
              $('#td_input_iso2470'+i).remove();
              $('#td_input_moisture'+i).remove();
              $('#td_input_ssa'+i).remove();
              $('#td_input_d50'+i).remove();
              $('#td_input_d98'+i).remove();
              $('#td_input_residue'+i).remove();
            }
            $('#remove_data_pembanding').hide();
            alert("Data Successfully Updated");
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
          url:"{{ url('produksi/uji_sample/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_uji_sample_table').dataTable();
            oTable.fnDraw(false);
            for(var i = 2; i <= window['edit_c_pembanding']; i++){
              $('#edit_td_input_produk'+i).remove();
              $('#edit_td_input_kalsium'+i).remove();
              $('#edit_td_input_cie86'+i).remove();
              $('#edit_td_input_iso2470'+i).remove();
              $('#edit_td_input_moisture'+i).remove();
              $('#edit_td_input_ssa'+i).remove();
              $('#edit_td_input_d50'+i).remove();
              $('#edit_td_input_d98'+i).remove();
              $('#edit_td_input_residue'+i).remove();
            }
            $('#edit_remove_data_pembanding').hide();
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