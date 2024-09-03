@extends('layouts.app_admin')

@section('title')
<title>INVENTARIS BATU- PT. DWI SELO GIRI MAS</title>
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
  #inventaris_table tbody tr:hover{
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
          <h1 class="m-0 text-dark">Inventaris Batu</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Raw Material</li>
            <li class="breadcrumb-item">Inventaris Batu</li>
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
          <div class="row">
            <div class="col-2 ">
              <a href="#" class="btn btn-primary" id="btn-save-excel" style="width: 100%;"><i class="fa fa-download"></i> File Excel</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="inventaris_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Dolomit</th>
                <th>Afkir</th>
                <th>Kapur Air</th>
                <th>Kapur</th>
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
            <table class="table table-bordered table-hover" id="table_inventaris" style="width: 100%; font-size: 12px;">
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
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var c_rencana = 0;
    var table = $('#inventaris_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("raw_material/inventaris_batu/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order:[],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'tanggal',
           name:'tanggal',
           className: 'dt-center'
         },
         {
           data:'stok_dolomit',
           name:'stok_dolomit',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'stok_afkir',
           name:'stok_afkir',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'stok_kapur_air',
           name:'stok_kapur_air',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'stok_kapur',
           name:'stok_kapur',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'action',
           name:'action',
           className: 'dt-center',
           width:'10%'
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

    function load_data_inventaris(from_date = '', to_date = '')
     {
      table_a = $('#inventaris_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("raw_material/inventaris_batu/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order:[],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'tanggal',
           name:'tanggal',
           className: 'dt-center'
         },
         {
           data:'stok_dolomit',
           name:'stok_dolomit',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'stok_afkir',
           name:'stok_afkir',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'stok_kapur_air',
           name:'stok_kapur_air',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'stok_kapur',
           name:'stok_kapur',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'action',
           name:'action',
           className: 'dt-center',
           width:'10%'
         }
       ]
      });
     }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#inventaris_table').DataTable().destroy();
        load_data_inventaris(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#inventaris_table').DataTable().destroy();
      load_data_inventaris();
    });

    $('body').on('click', '#btn-save-excel', function () {
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');

      var url = '{{ url("raw_material/inventaris_batu/excel/from_date/to_date") }}';
      url = url.replace('from_date', enc(from_date.toString()));
      url = url.replace('to_date', enc(to_date.toString()));
      $('#btn-save-excel').attr('href', url);
      window.location = url;
    });

    $('body').on('click', '#view-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("title_lihat_inventaris").innerHTML = "Inventaris Tgl " + tanggal;
      var url = "{{ url('raw_material/inventaris_batu/detail/tanggal') }}";
      url = url.replace('tanggal', enc(tanggal.toString()));

      $('#table_inventaris').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">Dolomit</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">Afkir</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">Kapur Air</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center; background-color:#9ecbff;">Kapur</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Masuk</th>'+
        '<th style="vertical-align: top; text-align: center;">Keluar</th>'+
        '<th style="vertical-align: top; text-align: center;">Masuk</th>'+
        '<th style="vertical-align: top; text-align: center;">Keluar</th>'+
        '<th style="vertical-align: top; text-align: center;">Masuk</th>'+
        '<th style="vertical-align: top; text-align: center;">Keluar</th>'+
        '<th style="vertical-align: top; text-align: center;">Masuk</th>'+
        '<th style="vertical-align: top; text-align: center;">Keluar</th>'+
        '</tr>'
      );
      $.get(url, function (data) {
        $('#table_inventaris').append(
          '<tr>'
        );
        if(data.dolomit){
          $('#table_inventaris').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.dolomit.masuk +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.dolomit.keluar +'</td>'
          );
        }else{
          $('#table_inventaris').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.afkir){
          $('#table_inventaris').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.afkir.masuk +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.afkir.keluar +'</td>'
          );
        }else{
          $('#table_inventaris').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.kapur_air){
          $('#table_inventaris').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.kapur_air.masuk +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.kapur_air.keluar +'</td>'
          );
        }else{
          $('#table_inventaris').append(
            '<td></td>'+
            '<td></td>'
          );
        }
        if(data.kapur){
          $('#table_inventaris').append(
            '<td style="vertical-align: top; text-align: center;">'+ data.kapur.masuk +'</td>'+
            '<td style="vertical-align: top; text-align: center;">'+ data.kapur.keluar +'</td>'
          );
        }else{
          $('#table_inventaris').append(
            '<td></td>'+
            '<td></td>'
          );
        }
      })
    });
  });
</script>

@endsection
