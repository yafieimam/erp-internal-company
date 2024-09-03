@extends('layouts.app_admin')

@section('title')
<title>INVENTARIS - PT. DWI SELO GIRI MAS</title>
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
  #inventaris_a_table tbody tr:hover, #inventaris_b_table tbody tr:hover{
    cursor: pointer;
  }
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
    .save-btn-in {
      width: 100%;
    }
    .lihat-table {
      overflow-x: auto;
    }
    .radio-control {
      padding-left: 0 !important;
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
          <h1 class="m-0 text-dark">Inventaris</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            @if(Session::get('tipe_user') == 3)
            <li class="breadcrumb-item">Produksi</li>
            @elseif(Session::get('tipe_user') == 8)
            <li class="breadcrumb-item">Warehouse</li>
            @endif
            <li class="breadcrumb-item">Inventaris</li>
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
          <table id="inventaris_a_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>AA40</th>
                <th>BB40</th>
                <th>DCB25</th>
                <th>AA20</th>
                <th>AA25</th>
                <th>CC50</th>
                <th>DD50</th>
                <th>DCD50</th>
                <th>DCD25</th>
                <th>DCE50</th>
                <th>SSF25</th>
                <th></th>
              </tr>
            </thead>
          </table>
          <br>
          <table id="inventaris_b_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>SW30</th>
                <th>SW40</th>
                <th>SF30</th>
                <th>SS30</th>
                <th>SSS30</th>
                <th>AC30</th>
                <th>NL25</th>
                <th>JAA</th>
                <th>JSW</th>
                <th>JPAC</th>
                <th>KDCC</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_lihat_inventaris">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title_lihat_inventaris"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12 lihat-table">
            </table>
            <table class="table table-bordered table-hover" id="table_inventaris1" style="width: 100%; font-size: 12px;">
            </table>
            <table class="table table-bordered table-hover" id="table_inventaris2" style="width: 100%; font-size: 12px;">
            </table>
            <table class="table table-bordered table-hover" id="table_inventaris3" style="width: 100%; font-size: 12px;">
            </table>
            <table class="table table-bordered table-hover" id="table_inventaris4" style="width: 100%; font-size: 12px;">
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

    $('#tanggal_rencana').flatpickr({
      allowInput: true,
      static: true,
      disableMobile: true
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var c_rencana = 0;
    var table_a = $('#inventaris_a_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("inventaris/view_inventaris_produksi_a_table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order:[],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_inventaris');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
            $('td', row).eq(12).removeAttr('data-toggle');
            $('td', row).eq(12).removeAttr('data-target');
        },
        columns: [
         {
           data:'tanggal',
           name:'tanggal'
         },
         {
           data:'saldo_aa40',
           name:'saldo_aa40',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_bb40',
           name:'saldo_bb40',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dcb25',
           name:'saldo_dcb25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_aa20',
           name:'saldo_aa20',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_aa25',
           name:'saldo_aa25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_cc50',
           name:'saldo_cc50',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dd50',
           name:'saldo_dd50',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dcd50',
           name:'saldo_dcd50',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dcd25',
           name:'saldo_dcd25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dce50',
           name:'saldo_dce50',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_ssf25',
           name:'saldo_ssf25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'action',
           name:'action',
           width:'5%'
         }
       ]
     });

    var table_b = $('#inventaris_b_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("inventaris/view_inventaris_produksi_b_table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order:[],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_inventaris');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
            $('td', row).eq(12).removeAttr('data-toggle');
            $('td', row).eq(12).removeAttr('data-target');
        },
        columns: [
         {
           data:'tanggal',
           name:'tanggal'
         },
         {
           data:'saldo_sw30',
           name:'saldo_sw30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_sw40',
           name:'saldo_sw40',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_sf30',
           name:'saldo_sf30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_ss30',
           name:'saldo_ss30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_sss30',
           name:'saldo_sss30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_ac30',
           name:'saldo_ac30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_nl25',
           name:'saldo_nl25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_jaa',
           name:'saldo_jaa',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_jsw',
           name:'saldo_jsw',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_jpac',
           name:'saldo_jpac',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_kdcc',
           name:'saldo_kdcc',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'action',
           name:'action',
           width:'5%'
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

    function load_data_inventaris_a(from_date = '', to_date = '')
     {
      table_a = $('#inventaris_a_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("inventaris/view_inventaris_produksi_a_table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order:[],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_inventaris');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
            $('td', row).eq(12).removeAttr('data-toggle');
            $('td', row).eq(12).removeAttr('data-target');
        },
        columns: [
         {
           data:'tanggal',
           name:'tanggal'
         },
         {
           data:'saldo_aa40',
           name:'saldo_aa40',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_bb40',
           name:'saldo_bb40',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dcb25',
           name:'saldo_dcb25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_aa20',
           name:'saldo_aa20',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_aa25',
           name:'saldo_aa25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_cc50',
           name:'saldo_cc50',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dd50',
           name:'saldo_dd50',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dcd50',
           name:'saldo_dcd50',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dcd25',
           name:'saldo_dcd25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_dce50',
           name:'saldo_dce50',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_ssf25',
           name:'saldo_ssf25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'action',
           name:'action',
           width:'5%'
         }
       ]
      });
     }

     function load_data_inventaris_b(from_date = '', to_date = '')
     {
      table_b = $('#inventaris_b_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("inventaris/view_inventaris_produksi_b_table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order:[],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_inventaris');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
            $('td', row).eq(12).removeAttr('data-toggle');
            $('td', row).eq(12).removeAttr('data-target');
        },
        columns: [
         {
           data:'tanggal',
           name:'tanggal'
         },
         {
           data:'saldo_sw30',
           name:'saldo_sw30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_sw40',
           name:'saldo_sw40',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_sf30',
           name:'saldo_sf30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_ss30',
           name:'saldo_ss30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_sss30',
           name:'saldo_sss30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_ac30',
           name:'saldo_ac30',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_nl25',
           name:'saldo_nl25',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_jaa',
           name:'saldo_jaa',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_jsw',
           name:'saldo_jsw',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_jpac',
           name:'saldo_jpac',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'saldo_kdcc',
           name:'saldo_kdcc',
           render: $.fn.dataTable.render.number('.', " Sak", ',')
         },
         {
           data:'action',
           name:'action',
           width:'5%'
         }
       ]
      });
     }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#inventaris_a_table').DataTable().destroy();
        load_data_inventaris_a(from_date, to_date);
        $('#inventaris_b_table').DataTable().destroy();
        load_data_inventaris_b(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#inventaris_a_table').DataTable().destroy();
      load_data_inventaris_a();
      $('#inventaris_b_table').DataTable().destroy();
      load_data_inventaris_b();
    });

    $('#inventaris_a_table, #inventaris_b_table').on( 'click', 'tbody tr', function () {
      if(typeof table_a.row(this).data() !== "undefined"){
        var rencana = table_a.row(this).data();
      }
      
      if(typeof table_b.row(this).data() !== "undefined"){
        var rencana = table_b.row(this).data();
      }

      document.getElementById("title_lihat_inventaris").innerHTML = "Inventaris Tgl " + rencana['tanggal'];
      var url = "{{ url('inventaris/detail/tanggal') }}";
      url = url.replace('tanggal', enc(rencana['tanggal'].toString()));

      $('#table_inventaris1').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">AA40</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">BB40</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">DCB25</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">AA20</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">AA25</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">CC50</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '</tr>'
      );
      $('#table_inventaris2').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">DD50</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">DCD50</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">DCD25</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">DCE50</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">SSF25</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">SW30</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '</tr>'
      );
      $('#table_inventaris3').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">SW40</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">SF30</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">SS30</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">SSS30</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">AC30</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">NL25</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '</tr>'
      );
      $('#table_inventaris4').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">JAA</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">JSW</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">JPAC</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">KDCC</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '<th style="vertical-align: top; text-align: center;">Produksi</th>'+
        '<th style="vertical-align: top; text-align: center;">Pengiriman</th>'+
        '</tr>'
      );
      $.get(url, function (data) {
        $('#table_inventaris1').append(
          '<tr>'
        );
        if(data.aa40){
          $('#table_inventaris1').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.aa40.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.aa40.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris1').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.bb40){
          $('#table_inventaris1').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.bb40.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.bb40.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris1').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.dcb25){
          $('#table_inventaris1').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.dcb25.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.dcb25.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris1').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.aa20){
          $('#table_inventaris1').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.aa20.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.aa20.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris1').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.aa25){
          $('#table_inventaris1').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.aa25.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.aa25.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris1').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.cc50){
          $('#table_inventaris1').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.cc50.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.cc50.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris1').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        $('#table_inventaris1').append(
          '</tr>'
        );
        $('#table_inventaris2').append(
          '<tr>'
        );
        if(data.dd50){
          $('#table_inventaris2').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.dd50.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.dd50.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris2').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.dcd50){
          $('#table_inventaris2').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.dcd50.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.dcd50.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris2').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.dcd25){
          $('#table_inventaris2').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.dcd25.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.dcd25.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris2').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.dce50){
          $('#table_inventaris2').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.dce50.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.dce50.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris2').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.ssf25){
          $('#table_inventaris2').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.ssf25.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.ssf25.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris2').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.sw30){
          $('#table_inventaris2').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.sw30.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.sw30.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris2').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        $('#table_inventaris2').append(
          '</tr>'
        );
        $('#table_inventaris3').append(
          '<tr>'
        );
        if(data.sw40){
          $('#table_inventaris3').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.sw40.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.sw40.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris3').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.sf30){
          $('#table_inventaris3').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.sf30.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.sf30.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris3').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.ss30){
          $('#table_inventaris3').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.ss30.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.ss30.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris3').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.sss30){
          $('#table_inventaris3').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.sss30.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.sss30.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris3').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.ac30){
          $('#table_inventaris3').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.ac30.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.ac30.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris3').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.nl25){
          $('#table_inventaris3').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.nl25.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.nl25.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris3').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        $('#table_inventaris3').append(
          '</tr>'
        );
        $('#table_inventaris4').append(
          '<tr>'
        );
        if(data.jaa){
          $('#table_inventaris4').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.jaa.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.jaa.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris4').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.jsw){
          $('#table_inventaris4').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.jsw.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.jsw.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris4').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.jpac){
          $('#table_inventaris4').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.jpac.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.jpac.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris4').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.kdcc){
          $('#table_inventaris4').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.kdcc.produksi +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.kdcc.pengiriman +'</td>'
          );
        }else{
          $('#table_inventaris4').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        $('#table_inventaris4').append(
          '</tr>'
        );
      })
    });

    $('#rencana_produksi_form').validate({
      rules: {
        tanggal_rencana: {
          required: true,
        },
      },
      messages: {
        tanggal_rencana: {
          required: "Tanggal Rencana is Required",
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
        var myform = document.getElementById("rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#rencana_produksi_form').trigger("reset");
            var oTable = $('#rencana_produksi_table').dataTable();
            oTable.fnDraw(false);
            for(var i = 2; i <= c_rencana; i++){
              $('#row_data_rencana'+i).remove();
            }
            $("#modal_input_rencana_produksi").modal('hide');
            $("#modal_input_rencana_produksi").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });
  });
</script>

@endsection
