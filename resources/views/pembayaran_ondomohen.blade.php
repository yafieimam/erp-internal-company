@extends('layouts.app_admin')

@section('title')
<title>PEMBAYARAN - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Pembayaran</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Cashier</li>
            <li class="breadcrumb-item">Pembayaran</li>
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
                  <input type="text" placeholder="Filter Date" class="form-control float-right" id="filter_tanggal" autocomplete="off">
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
          <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#data_pembayaran" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data Pembayaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#list_payment_receive" role="tab" aria-controls="custom-content-below-home" aria-selected="false">List Payment Receive</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#list_belum_bayar" role="tab" aria-controls="custom-content-below-home" aria-selected="false">List Belum Bayar</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#list_belum_lunas" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">List Belum Lunas</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="data_pembayaran" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_pembayaran_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jumlah Data</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="list_payment_receive" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_list_payment_receive_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nomor SJ</th>
                    <th>Customers</th>
                    <th>Tanggal Bayar</th>
                    <th>Jenis Pembayaran</th>
                    <th>Nominal</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="list_belum_bayar" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_list_belum_bayar_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nomor SJ</th>
                    <th>Customers</th>
                    <th>Tgl Penyerahan Dokumen</th>
                    <th>Tgl Tagih</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="list_belum_lunas" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_list_belum_lunas_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nomor SJ</th>
                    <th>Customers</th>
                    <th>Bayar</th>
                    <th>Sisa Tagihan</th>
                    <th>Tanggal Bayar Terakhir</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_update_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_update_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="data_pembayaran_detail_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No SJ</th>
                  <th>Customer</th>
                  <th>Total Tagihan (Rp)</th>
                  <th>Pembayaran</th>
                  <th>Sisa (Rp)</th>
                  <th>Total (Rp)</th>
                  <th>Tgl Tagih</th>
                  <th>Tgl Bayar</th>
                  <th>Pembayaran</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-save-data" class="btn btn-primary">Save changes</button>
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
    var target = $('.nav-tabs a.nav-link.active').attr("href");

    var table = $('#data_pembayaran_table').DataTable({
      processing: true,
      serverSide: true,
      lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
      ajax: {
        url:'{{ url("penagihan/ondomohen/pembayaran/table") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
      },
      order: [[1,'desc']],
      dom: 'lBfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      columns: [
      {
       data:'DT_RowIndex',
       name:'DT_RowIndex',
       width: '5%',
       className:'dt-center'
      },
      {
       data:'tanggal_tagih_cust',
       name:'tanggal_tagih_cust',
       className:'dt-center'
      },
      {
       data:'jumlah_sj',
       name:'jumlah_sj',
       className:'dt-center',
       width:'20%'
      },
      {
       data:'action',
       name:'action',
       width: '10%',
       className:'dt-center'
      }
      ]
    });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#data_pembayaran'){
        $('#data_pembayaran_table').DataTable().destroy();
        load_data_pembayaran();
      }else if(target == '#list_payment_receive'){
        $('#data_list_payment_receive_table').DataTable().destroy();
        load_data_list_payment_receive();
      }else if(target == '#list_belum_bayar'){
        $('#data_list_belum_bayar_table').DataTable().destroy();
        load_data_list_belum_bayar();
      }else if(target == '#list_belum_lunas'){
        $('#data_list_belum_lunas_table').DataTable().destroy();
        load_data_list_belum_lunas();
      }else if(target == '#list_sudah_lunas'){
        $('#data_list_sudah_lunas_table').DataTable().destroy();
        load_data_list_sudah_lunas();
      }
    });
    
    function load_data_pembayaran(from_date = '', to_date = '')
    {
      table = $('#data_pembayaran_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/ondomohen/pembayaran/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
          data:'tanggal_tagih_cust',
          name:'tanggal_tagih_cust',
          className:'dt-center'
        },
        {
          data:'jumlah_sj',
          name:'jumlah_sj',
          className:'dt-center',
          width:'20%'
        },
        {
          data:'action',
          name:'action',
          width: '10%',
          className:'dt-center'
        }
        ]
      });
    }

    function load_data_list_payment_receive(from_date = '', to_date = '')
    {
      table = $('#data_list_payment_receive_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/ondomohen/list_payment_receive/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc']],
        dom: 'lBfrtip',
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width:'15%'
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'tanggal_bayar',
          name:'tanggal_bayar',
          className:'dt-center',
          width:'20%'
        },
        {
          data:null,
          name:null,
          className: 'dt-center',
          width:'20%',
          render: function ( data, type, row)
          {
            return $('<div />').text(row.nama_metode_pembayaran).html() + "<br>" + "No. " + $('<div />').text(row.nomor_metode_pembayaran).html();
          }
        },
        {
          data:'nominal_bayar',
          name:'nominal_bayar',
          className:'dt-center',
          width:'15%',
          render: $.fn.dataTable.render.number( '.', ',', 0)
        },
        ],
        buttons: [
        {
          extend: 'copy'
        },
        {
          extend: 'csv'
        },
        {
          extend: 'excel'
        },
        {
          extend: 'pdf'
        },
        {
          extend: 'print',
          customize: function ( win ) {
            $(win.document.body).css('margin', '30px');
            $(win.document.body).find('h1').css('margin-bottom', '10px');
            $(win.document.body).find('h1').css('text-align', 'center');
            $(win.document.body).find('h1').html('List Payment Receive');
          }
        }
        ]
      });
    }

    function load_data_list_belum_bayar()
    {
      table = $('#data_list_belum_bayar_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/list_belum_bayar/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc']],
        dom: 'lBfrtip',
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width:'12%'
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'tanggal_terima_dokumen_cust',
          name:'tanggal_terima_dokumen_cust',
          className:'dt-center',
          defaultContent:'---',
          width:'20%'
        },
        {
          data:'tanggal_tagih_cust',
          name:'tanggal_tagih_cust',
          className:'dt-center',
          defaultContent:'---',
          width:'15%'
        }
        ],
        buttons: [
        {
          extend: 'copy'
        },
        {
          extend: 'csv'
        },
        {
          extend: 'excel'
        },
        {
          extend: 'pdf'
        },
        {
          extend: 'print',
          customize: function ( win ) {
            $(win.document.body).css('margin', '30px');
            $(win.document.body).find('h1').css('margin-bottom', '10px');
            $(win.document.body).find('h1').css('text-align', 'center');
            $(win.document.body).find('h1').html('List Data Belum Bayar');
          }
        }
        ]
      });
    }

    function load_data_list_belum_lunas()
    {
      table = $('#data_list_belum_lunas_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/list_belum_lunas/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc']],
        dom: 'lBfrtip',
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width:'15%'
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'hitung_bayar',
          name:'hitung_bayar',
          className:'dt-center',
          width:'10%',
          render: function (data, type, row)
          {
            return data + "x";
          }
        },
        {
          data:'sisa',
          name:'sisa',
          className: 'dt-center',
          width:'15%',
          render: $.fn.dataTable.render.number( '.', ',', 0)
        },
        {
          data:'tanggal_bayar_cashier',
          name:'tanggal_bayar_cashier',
          className:'dt-center',
          width:'20%'
        }
        ],
        buttons: [
        {
          extend: 'copy'
        },
        {
          extend: 'csv'
        },
        {
          extend: 'excel'
        },
        {
          extend: 'pdf'
        },
        {
          extend: 'print',
          customize: function ( win ) {
            $(win.document.body).css('margin', '30px');
            $(win.document.body).find('h1').css('margin-bottom', '10px');
            $(win.document.body).find('h1').css('text-align', 'center');
            $(win.document.body).find('h1').html('List Data Belum Lunas');
          }
        }
        ]
      });
    }

    function load_data_pembayaran_detail(tanggal = '')
    {
      table = $('#data_pembayaran_detail_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("penagihan/ondomohen/pembayaran/detail/table") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lrtip',
        columns: [
        {
          data:'nosj',
          name:'nosj',
          className: 'dt-center',
          width:'10%',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html();
          }
        },
        {
          data:'customer',
          name:'customer',
          width:'15%',
          className: 'dt-center'
        },
        {
          data:'tagihan',
          name:'tagihan',
          className: 'dt-center',
          width:'10%',
          render: $.fn.dataTable.render.number( '.', ',', 0)
        },
        {
          data:null,
          name:null,
          className: 'dt-center',
          width:'15%',
          render: function ( data, type, row)
          {
            return $('<div />').text(row.nama_metode_pembayaran).html() + "<br>" + "No. " + $('<div />').text(row.nomor_metode_pembayaran).html() + "<br>" + "Rp " + $('<div />').text(row.nominal_bayar).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }
        },
        {
          data:'sisa',
          name:'sisa',
          className: 'dt-center',
          width:'8%',
          render: $.fn.dataTable.render.number( '.', ',', 0)
        },
        {
          data:'total',
          name:'total',
          className: 'dt-center',
          width:'8%',
          render: $.fn.dataTable.render.number( '.', ',', 0)
        },
        {
          data:'tanggal_tagih_cust',
          name:'tanggal_tagih_cust',
          width:'10%',
          className: 'dt-center'
        },
        {
          data:'tanggal_bayar_cashier',
          name:'tanggal_bayar_cashier',
          className:'dt-center',
          width:'10%',
          render: function ( data, type, row)
          {
            return '<input type="date" name="bayar[' + $('<div />').text(row.nosj).html() + ']"">';
          }
        },
        {
          data:null,
          name:null,
          className:'dt-center',
          width:'20%',
          render: function ( data, type, row)
          {
            if ($('<div />').text(row.metode_pembayaran).html() == 2 || $('<div />').text(row.metode_pembayaran).html() == 3) {
              $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').show();
              $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').show();
              $('[name="txt_no[' + $('<div />').text(row.nosj).html() + ']"]').show();
            }else{
              $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').hide();
              $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').hide();
            }

            $('[name="bayar[' + $('<div />').text(row.nosj).html() + ']"]').change(function(){
              if ($(this).val() == $('<div />').text(row.tanggal_tagih_cust).html()) {
                $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').val($('<div />').text(row.nomor_metode_pembayaran).html());
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').val($('<div />').text(row.nominal_bayar).html());
              }else{
                $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').val('');
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').val('');
              }
            });

            return '<label name="txt_no[' + $('<div />').text(row.nosj).html() + ']">No : </label> <input type="text" name="nomor[' + $('<div />').text(row.nosj).html() + ']" style="width:80%; display: none;" placeholder="Nomor">'+
            '<label>Rp : </label> <input type="text" name="nominal[' + $('<div />').text(row.nosj).html() + ']" style="width:80%; margin-top: 10px;" placeholder="Nominal">';
          }
        }
        ]
      });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if(target == '#data_pembayaran'){
          $('#data_pembayaran_table').DataTable().destroy();
          load_data_pembayaran(from_date, to_date);
        }else if(target == '#list_payment_receive'){
          $('#data_list_payment_receive_table').DataTable().destroy();
          load_data_list_payment_receive(from_date, to_date);
        }else if(target == '#list_sudah_lunas'){
          $('#data_list_sudah_lunas_table').DataTable().destroy();
          load_data_list_sudah_lunas(from_date, to_date);
        }
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(target == '#data_pembayaran'){
        $('#data_pembayaran_table').DataTable().destroy();
        load_data_pembayaran();
      }else if(target == '#list_payment_receive'){
        $('#data_list_payment_receive_table').DataTable().destroy();
        load_data_list_payment_receive();
      }else if(target == '#list_sudah_lunas'){
        $('#data_list_sudah_lunas_table').DataTable().destroy();
        load_data_list_sudah_lunas();
      }
    });

    $('body').on('click', '#update-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_update_data").innerHTML = "Data Tanggal " + tanggal;
      $('#data_pembayaran_detail_table').DataTable().destroy();
      load_data_pembayaran_detail(tanggal);
    });

    $('#btn-save-data').click( function() {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-data').data('token'),
        },
        type: "GET",
        url: '{{ url("penagihan/ondomohen/pembayaran/save") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(data){
          var oTable = $('#data_pembayaran_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_update_data').modal('hide');
          $("#modal_update_data").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });
  });
</script>

@endsection