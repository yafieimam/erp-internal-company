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
            <li class="breadcrumb-item">Admin DSGM</li>
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
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#pembayaran" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Pembayaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#data_sudah_bayar" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Data Sudah Bayar</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="pembayaran" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="pembayaran_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
            <div class="tab-pane fade" id="data_sudah_bayar" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_sudah_bayar_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_send_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_send_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="detail_pembayaran_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>Nomor SJ</th>
                  <th>Customer</th>
                  <th>Tagihan</th>
                  <th>Bayar ?</th>
                  <th>Tanggal Bayar</th>
                  <th>Metode</th>
                  <th>Keterangan</th>
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

  <div class="modal fade" id="modal_view_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_view_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="detail_view_data_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nomor SJ</th>
                  <th>No Invoice</th>
                  <th>Tanggal SJ</th>
                  <th>Customers</th>
                  <th>Tagihan</th>
                  <th>Keterangan</th>
                  <th>Dibayar</th>
                </tr>
              </thead>
            </table>
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

    var table = $('#pembayaran_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("admin/dsgm/pembayaran/table") }}',
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
         data:'tanggal_do',
         name:'tanggal_do',
         className:'dt-center'
       },
       {
         data:'jumlah_sj',
         name:'jumlah_sj',
         className:'dt-center'
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
      if(target == '#pembayaran'){
        $('#pembayaran_table').DataTable().destroy();
        load_data_pembayaran();
      }else if(target == '#data_sudah_bayar'){
        $('#data_sudah_bayar_table').DataTable().destroy();
        load_data_sudah_bayar();
      }
    });

    function load_data_pembayaran(from_date = '', to_date = '')
    {
      table = $('#pembayaran_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("admin/dsgm/pembayaran/table") }}',
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
          data:'tanggal_do',
          name:'tanggal_do',
          className:'dt-center'
        },
        {
          data:'jumlah_sj',
          name:'jumlah_sj',
          className:'dt-center'
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

    function load_data_sudah_bayar(from_date = '', to_date = '')
    {
      table = $('#data_sudah_bayar_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("admin/dsgm/sudah_bayar/table") }}',
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
          data:'tanggal_dibayar_admin',
          name:'tanggal_dibayar_admin',
          className:'dt-center'
        },
        {
          data:'jumlah_sj',
          name:'jumlah_sj',
          className:'dt-center'
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

    function load_data_view(tanggal = '')
    {
      table = $('#detail_view_data_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("admin/dsgm/kirim_trunojoyo/view/table") }}',
          data:{tanggal:tanggal},
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
          data:'nosj',
          name:'nosj',
          className:'dt-center'
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          defaultContent:'--'
        },
        {
          data:'tanggal_do',
          name:'tanggal_do',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'tagihan',
          name:'tagihan',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'9%'
        },
        {
          data:'keterangan',
          name:'keterangan',
          className:'dt-center',
          render: function ( data, type, row)
          {
            if($('<div />').text(row.ket_trunojoyo).html() == 1){
              if($('<div />').text(row.check_dibayar_admin).html() == 1){
                return $('<div />').text(row.keterangan_penagihan).html();
              }else{
                if($('<div />').text(row.check_diserahkan_admin).html() == 1){
                  return $('<div />').text(row.keterangan_penerimaan).html();
                }else{
                  return $('<div />').text(row.keterangan).html();
                }
              }
            }else{
              return '-';
            }
          }
        },
        {
          data:'check_dibayar_admin',
          name:'check_dibayar_admin',
          className:'dt-center',
          width:'7%',
          render: function ( data, type, row)
          {
            if(data == 1){
              return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" checked="checked" readonly>';
            }else{
              return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" readonly>';
            }
          }
        }
        ]
      });
    }

    function load_data_pembayaran_detail(tanggal = '')
    {
      table = $('#detail_pembayaran_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("admin/dsgm/pembayaran/detail/table") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lrtip',
        columns: [
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html();
          },
          width:'9%'
        },
        {
          data:'customer',
          name:'customer',
          className:'dt-center',
          width:'15%'
        },
        {
          data:'tagihan',
          name:'tagihan',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'9%'
        },
        {
          data:'check_dibayar_admin',
          name:'check_dibayar_admin',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="dibayar[' + $('<div />').text(row.nosj).html() + ']"]').change(function(){
              if ($(this).is(':checked')) {
                $('[name="tagih_cust[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
                $('[name="metode[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
                $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
                $('[name="keterangan[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
              }else{
                $('[name="tagih_cust[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", true);
                $('[name="metode[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", true);
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", true);
                $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", true);
              }
            });

            return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1">';
          },
          width:'7%'
        },
        {
          data:'tanggal_tagih_cust',
          name:'tanggal_tagih_cust',
          className:'dt-center',
          width:'12%',
          render: function (data, type, row)
          {
            $('[name="tagih_cust[' + $('<div />').text(row.nosj).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tagih_cust[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.tanggal_tagih_cust).html() + '" disabled>';
          }
        },
        {
          data:'metode_pembayaran',
          name:'metode_pembayaran',
          className:'dt-center',
          width:'14%',
          render: function ( data, type, row)
          {
            $('[name="metode[' + $('<div />').text(row.nosj).html() + ']"]').change(function(){
              if ($(this).val() == 2 || $(this).val() == 3) {
                $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').show();
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').show();
              }else if($(this).val() == 1 || $(this).val() == 4){
                $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').hide();
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').show();
              }else{
                $('[name="nomor[' + $('<div />').text(row.nosj).html() + ']"]').hide();
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').hide();
              }
            });

            $('[name="dibayar[' + $('<div />').text(row.nosj).html() + ']"]').change(function(){
              if ($(this).is(':checked')) {
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').val($('<div />').text(row.tagihan).html());
              }else{
                $('[name="nominal[' + $('<div />').text(row.nosj).html() + ']"]').val('');
              }
            });

            return '<select name="metode[' + $('<div />').text(row.nosj).html() + ']" style="width:100%;" disabled>' +
            '<option value="1">Cash</option>'+
            '<option value="2">Giro</option>'+
            '<option value="3">Cek</option>'+
            '<option value="4">Transfer</option>'+
            '</select>'+
            '<input type="text" name="nomor[' + $('<div />').text(row.nosj).html() + ']" style="width:100%; margin-top: 10px; display: none;" placeholder="Nomor">'+
            '<input type="text" name="nominal[' + $('<div />').text(row.nosj).html() + ']" style="width:100%; margin-top: 10px;" placeholder="Nominal" disabled>';
          }
        },
        {
          data:'keterangan_penagihan',
          name:'keterangan_penagihan',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<textarea rows="2" name="keterangan[' + $('<div />').text(row.nosj).html() + ']" style="width:100%;" disabled>' + $('<div />').text(row.keterangan).html() + '</textarea>';
          },
          width:'18%'
        }
        ]
      });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if(target == '#pembayaran'){
          $('#pembayaran_table').DataTable().destroy();
          load_data_pembayaran(from_date, to_date);
        }else if(target == '#data_sudah_bayar'){
          $('#data_sudah_bayar_table').DataTable().destroy();
          load_data_sudah_bayar(from_date, to_date);
        }
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(target == '#pembayaran'){
        $('#pembayaran_table').DataTable().destroy();
        load_data_pembayaran();
      }else if(target == '#data_sudah_bayar'){
        $('#data_sudah_bayar_table').DataTable().destroy();
        load_data_sudah_bayar();
      }
    });

    $('body').on('click', '#view-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_view_data").innerHTML = "Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#detail_view_data_table').DataTable().destroy();
      load_data_view(tanggal);
    });

    $('body').on('click', '#send-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_send_data").innerHTML = "Data Tanggal " + tanggal;
      $('#detail_pembayaran_table').DataTable().destroy();
      load_data_pembayaran_detail(tanggal);
    });

    $('#btn-save-data').click( function() {
      var data = table.$('input, textarea, select').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-data').data('token'),
        },
        type: "POST",
        url: '{{ url("admin/dsgm/update/pembayaran") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#pembayaran_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_send_data').modal('hide');
          $("#modal_send_data").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });
  });
</script>

@endsection