@extends('layouts.app_admin')

@section('title')
<title>LAPORAN - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Laporan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Admin DSGM</li>
            <li class="breadcrumb-item">Laporan</li>
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
              <a class="nav-link active" id="custom-content-below-profile-tab" data-toggle="pill" href="#list_sudah_lunas" role="tab" aria-controls="custom-content-below-profile" aria-selected="true">List Sudah Lunas</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="list_sudah_lunas" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_list_sudah_lunas_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nomor SJ</th>
                    <th>Customers</th>
                    <th>Tanggal Pelunasan</th>
                    <th>Pembayaran</th>
                    <th>No Referensi</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
    var table = $('#data_list_sudah_lunas_table').DataTable({
      processing: true,
      serverSide: true,
      lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
      ajax: {
        url:'{{ url("penagihan/list_sudah_lunas/table") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
      },
      order: [[3,'desc']],
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
        data:'tanggal_pelunasan',
        name:'tanggal_pelunasan',
        className:'dt-center',
        width:'15%',
        render: function ( data, type, full, meta ) {
          return moment(data).format('DD MMM YYYY');
        }
      },
      {
        data:null,
        name:null,
        className: 'dt-center',
        width:'15%',
        render: function ( data, type, row)
        {
          if($('<div />').text(row.metode_pembayaran).html() == 1){
            return $('<div />').text(row.nama_metode_pembayaran).html() + "<br>" + "Rp " + $('<div />').text(row.nominal_bayar).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }else{
            return $('<div />').text(row.nama_metode_pembayaran).html() + "<br>" + "No. " + $('<div />').text(row.nomor_metode_pembayaran).html() + "<br>" + "Rp " + $('<div />').text(row.nominal_bayar).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }
        }
      },
      {
        data:'nomor_referensi',
        name:'nomor_referensi',
        className:'dt-center',
        width:'12%'
      },
      {
        data:'keterangan_pelunasan',
        name:'keterangan_pelunasan',
        className:'dt-center',
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
          $(win.document.body).find('h1').html('List Data Sudah Lunas');
        }
      }
      ]
    });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#list_sudah_lunas'){
        $('#data_list_sudah_lunas_table').DataTable().destroy();
        load_data_list_sudah_lunas();
      }
    });

    function load_data_list_sudah_lunas(from_date = '', to_date = '')
    {
      table = $('#data_list_sudah_lunas_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/list_sudah_lunas/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[3,'desc']],
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
          data:'tanggal_pelunasan',
          name:'tanggal_pelunasan',
          className:'dt-center',
          width:'15%',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:null,
          name:null,
          className: 'dt-center',
          width:'15%',
          render: function ( data, type, row)
          {
            if($('<div />').text(row.metode_pembayaran).html() == 1){
              return $('<div />').text(row.nama_metode_pembayaran).html() + "<br>" + "Rp " + $('<div />').text(row.nominal_bayar).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }else{
              return $('<div />').text(row.nama_metode_pembayaran).html() + "<br>" + "No. " + $('<div />').text(row.nomor_metode_pembayaran).html() + "<br>" + "Rp " + $('<div />').text(row.nominal_bayar).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
          }
        },
        {
          data:'nomor_referensi',
          name:'nomor_referensi',
          className:'dt-center',
          width:'12%'
        },
        {
          data:'keterangan_pelunasan',
          name:'keterangan_pelunasan',
          className:'dt-center',
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
            $(win.document.body).find('h1').html('List Data Sudah Lunas');
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
        if(target == '#list_sudah_lunas'){
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
      if(target == '#list_sudah_lunas'){
        $('#data_list_sudah_lunas_table').DataTable().destroy();
        load_data_list_sudah_lunas();
      }
    });
  });
</script>

@endsection