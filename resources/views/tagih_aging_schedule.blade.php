@extends('layouts.app_admin')

@section('title')
<title>AGING SCHEDULE - PT. DWI SELO GIRI MAS</title>
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
  td.details-control {
    background: url("{{asset('app-assets/images/icons/details_open.png')}}") no-repeat center center;
    cursor: pointer;
  }
  tr.shown td.details-control {
      background: url("{{asset('app-assets/images/icons/details_close.png')}}") no-repeat center center;
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
          <h1 class="m-0 text-dark">Aging Schedule</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Penagihan</li>
            <li class="breadcrumb-item">Aging Schedule</li>
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
            <div class="col-3">
              <button type="button" name="btn_print_aging_schedule" id="btn_print_aging_schedule" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_print_aging_schedule">Print Aging Schedule</button>
            </div>
            <div class="col-5"></div>
            <div class="col-4">
              <div class="form-group">
                <select id="list_group_customers" name="list_group_customers" class="form-control">
                  <option value="1" selected>Dalam Kota</option>
                  <option value="2">Jakarta</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body" id="div_dalam_kota">
          <table id="data_dalam_kota_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Cust ID</th>
                <th>Nama</th>
                <th>Saldo Awal</th>
                <th>Jml Penjualan</th>
                <th>Total Penjualan</th>
                <th>Pembayaran</th>
                <th>Saldo Akhir</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th colspan="2"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="card-body" id="div_jakarta" style="display: none;">
          <table id="data_jakarta_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Cust ID</th>
                <th>Nama</th>
                <th>Saldo Awal</th>
                <th>Jml Penjualan</th>
                <th>Total Penjualan</th>
                <th>Pembayaran</th>
                <th>Saldo Akhir</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th colspan="2"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_print_aging_schedule">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Print Aging Schedule</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
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
                <label for="group_customers">Group Customers</label>
                <select id="group_customers" name="group_customers" class="form-control">
                  <option value="B" selected>Jakarta</option>
                  <option value="T">Dalam Kota</option>
                </select>
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
        </div>
        <div class="modal-footer justify-content-between">
          <a href="#" class="btn btn-primary" id="btn-download-excel">Download Excel</a>
          <button type="button" class="btn btn-primary" id="btn-print-pdf" target="_blank">Print PDF</button>
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

    $('#tanggal').daterangepicker({
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

    var target = $('.nav-tabs a.nav-link.active').attr("href");

    var table = $('#data_dalam_kota_table').DataTable({
      "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        var intVal = function ( i ) {
          return typeof i === 'string' ?
          i.replace(/[\$,]/g, '')*1 :
          typeof i === 'number' ?
          i : 0;
        };

        var saldoAwalTotal = api
        .column( 2 )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );
        
        var jumlahFakTotal = api
        .column( 3 )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );
        
        var nilaiFakTotal = api
        .column( 4 )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );
        
        var pembayaranTotal = api
        .column( 5 )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );
        
        var saldoAkhirTotal = api
        .column( 6 )
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );

        $( api.column( 1 ).footer() ).html('Total');
        $( api.column( 2 ).footer() ).html('Rp ' + saldoAwalTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        $( api.column( 3 ).footer() ).html(jumlahFakTotal);
        $( api.column( 4 ).footer() ).html('Rp ' + nilaiFakTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        $( api.column( 5 ).footer() ).html('Rp ' + pembayaranTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        $( api.column( 6 ).footer() ).html('Rp ' + saldoAkhirTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
      },
      processing: true,
      serverSide: true,
      lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
      iDisplayLength: -1,
      ajax: {
        url:'{{ url("penagihan/aging_schedule/dalam_kota/table") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
      },
      order: [[0,'asc']],
      dom: 'lBfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      columns: [
      {
       data:'custid',
       name:'custid',
       className:'dt-center'
      },
      {
       data:'custname',
       name:'custname',
       className:'dt-center'
      },
      {
       data:'saldo_awal',
       name:'saldo_awal',
       className:'dt-center',
       defaultContent:'0.00',
       render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
      },
      {
       data:'jumlah_fak',
       name:'jumlah_fak',
       className:'dt-center',
       defaultContent:'0'
      },
      {
       data:'nilai_fak',
       name:'nilai_fak',
       className:'dt-center',
       defaultContent:'0.00',
       render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
      },
      {
       data:'pembayaran',
       name:'pembayaran',
       className:'dt-center',
       defaultContent:'0.00',
       render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
      },
      {
       data:'saldo_akhir',
       name:'saldo_akhir',
       className:'dt-center',
       defaultContent:'0.00',
       render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
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

    $("#list_group_customers").change(function() {
      if ($(this).val() == 1) {
        $('#div_dalam_kota').show();
        $('#div_jakarta').hide();
        $('#data_dalam_kota_table').DataTable().destroy();
        load_data_dalam_kota();
      }else if ($(this).val() == 2) {
        $('#div_dalam_kota'). hide();
        $('#div_jakarta').show();
        $('#data_jakarta_table').DataTable().destroy();
        load_data_jakarta();
      }else {
        $('#div_dalam_kota').hide();
        $('#div_jakarta').hide();
      }
    });

    function load_data_dalam_kota(from_date='', to_date='')
    {
      table = $('#data_dalam_kota_table').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;

          var intVal = function ( i ) {
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1 :
            typeof i === 'number' ?
            i : 0;
          };

          var saldoAwalTotal = api
          .column( 2 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
          
          var jumlahFakTotal = api
          .column( 3 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
          
          var nilaiFakTotal = api
          .column( 4 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
          
          var pembayaranTotal = api
          .column( 5 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
          
          var saldoAkhirTotal = api
          .column( 6 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          $( api.column( 1 ).footer() ).html('Total');
          $( api.column( 2 ).footer() ).html('Rp ' + saldoAwalTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
          $( api.column( 3 ).footer() ).html(jumlahFakTotal);
          $( api.column( 4 ).footer() ).html('Rp ' + nilaiFakTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
          $( api.column( 5 ).footer() ).html('Rp ' + pembayaranTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
          $( api.column( 6 ).footer() ).html('Rp ' + saldoAkhirTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        },
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        iDisplayLength: -1,
        ajax: {
          url:'{{ url("penagihan/aging_schedule/dalam_kota/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          data:'custid',
          name:'custid',
          className:'dt-center'
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'saldo_awal',
          name:'saldo_awal',
          className:'dt-center',
          defaultContent:'0.00',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        },
        {
          data:'jumlah_fak',
          name:'jumlah_fak',
          className:'dt-center',
          defaultContent:'0'
        },
        {
          data:'nilai_fak',
          name:'nilai_fak',
          className:'dt-center',
          defaultContent:'0.00',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        },
        {
          data:'pembayaran',
          name:'pembayaran',
          className:'dt-center',
          defaultContent:'0.00',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        },
        {
          data:'saldo_akhir',
          name:'saldo_akhir',
          className:'dt-center',
          defaultContent:'0.00',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        }
        ]
      });
    }

    function load_data_jakarta(from_date='', to_date='')
    {
      table = $('#data_jakarta_table').DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;

          var intVal = function ( i ) {
            return typeof i === 'string' ?
            i.replace(/[\Rp,.]/g, '')*1 :
            typeof i === 'number' ?
            i : 0;
          };

          var saldoAwalTotal = api
          .column( 2 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
          
          var jumlahFakTotal = api
          .column( 3 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
          
          var nilaiFakTotal = api
          .column( 4 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
          
          var pembayaranTotal = api
          .column( 5 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
          
          var saldoAkhirTotal = api
          .column( 6 )
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          $( api.column( 1 ).footer() ).html('Total');
          $( api.column( 2 ).footer() ).html('Rp ' + saldoAwalTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
          $( api.column( 3 ).footer() ).html(jumlahFakTotal);
          $( api.column( 4 ).footer() ).html('Rp ' + nilaiFakTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
          $( api.column( 5 ).footer() ).html('Rp ' + pembayaranTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
          $( api.column( 6 ).footer() ).html('Rp ' + saldoAkhirTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        },
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        iDisplayLength: -1,
        ajax: {
          url:'{{ url("penagihan/aging_schedule/jakarta/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          data:'custid',
          name:'custid',
          className:'dt-center'
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'saldo_awal',
          name:'saldo_awal',
          className:'dt-center',
          defaultContent:'0.00',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        },
        {
          data:'jumlah_fak',
          name:'jumlah_fak',
          className:'dt-center',
          defaultContent:'0'
        },
        {
          data:'nilai_fak',
          name:'nilai_fak',
          className:'dt-center',
          defaultContent:'0.00',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        },
        {
          data:'pembayaran',
          name:'pembayaran',
          className:'dt-center',
          defaultContent:'0.00',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        },
        {
          data:'saldo_akhir',
          name:'saldo_akhir',
          className:'dt-center',
          defaultContent:'0.00',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        }
        ]
      });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if($('#list_group_customers').val() == 1) {
          $('#data_dalam_kota_table').DataTable().destroy();
          load_data_dalam_kota(from_date, to_date);
        }else if ($('#list_group_customers').val() == 2) {
          $('#data_jakarta_table').DataTable().destroy();
          load_data_jakarta(from_date, to_date);
        }
      }
      else
      {
       alert('Both Date is required');
     }
   });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if($('#list_group_customers').val() == 1) {
        $('#data_dalam_kota_table').DataTable().destroy();
        load_data_dalam_kota();
      }else if ($('#list_group_customers').val() == 2) {
        $('#data_jakarta_table').DataTable().destroy();
        load_data_jakarta();
      }
    });

    $('body').on('click', '#btn-download-excel', function () {
      var from_date = $('#tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      var customers = document.getElementById("customers").value;
      var group = document.getElementById("group_customers").value;

      var url_excel = "{{ url('penagihan/aging_schedule/excel/from_date/to_date/group/custid') }}";
      url_excel = url_excel.replace('from_date', enc(from_date.toString()));
      url_excel = url_excel.replace('to_date', enc(to_date.toString()));
      url_excel = url_excel.replace('group', enc(group.toString()));
      url_excel = url_excel.replace('custid', enc(customers.toString()));
      window.open(url_excel, '_blank');
    });

    $('body').on('click', '#btn-print-pdf', function () {
      var from_date = $('#tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      var customers = document.getElementById("customers").value;
      var group = document.getElementById("group_customers").value;

      $.ajax({
        type: "GET",
        url: "{{ url('penagihan/aging_schedule/print') }}",
        data: { 'from_date' : from_date, 'to_date' : to_date, 'customers' : customers, 'group' : group },
        xhrFields: {
          responseType: 'blob'
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(blob, status, xhr) {
          var filename = "";
          var disposition = xhr.getResponseHeader('Content-Disposition');
          if (disposition && disposition.indexOf('attachment') !== -1) {
            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            var matches = filenameRegex.exec(disposition);
            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
          }

          if (typeof window.navigator.msSaveBlob !== 'undefined') {
            window.navigator.msSaveBlob(blob, filename);
          } else {
            var URL = window.URL || window.webkitURL;
            var downloadUrl = URL.createObjectURL(blob);

            if (filename) {
              var a = document.createElement("a");
              if (typeof a.download === 'undefined') {
                window.open(downloadUrl, '_blank');
              } else {
                a.href = downloadUrl;
                a.target = "_blank";
                a.download = filename;
                document.body.appendChild(a);
                a.click();
              }
            } else {
              window.open(downloadUrl, '_blank');
            }

            setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100);
          }
        }
      });
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('.customers').select2({
      dropdownParent: $('#modal_print_aging_schedule .modal-content'),
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
  });
</script>

<script type="text/javascript">
  $(".customers").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".customers").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

@endsection