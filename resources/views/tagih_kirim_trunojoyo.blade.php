@extends('layouts.app_admin')

@section('title')
<title>KIRIM DOKUMEN SET - PT. DWI SELO GIRI MAS</title>
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
  input[type='checkbox'][readonly]{
    pointer-events: none;
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
          <h1 class="m-0 text-dark">Kirim Dokumen Set</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Admin DSGM</li>
            <li class="breadcrumb-item">Kirim Dokumen Set</li>
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
          <div class="row">
            <div class="col-9">
            <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#kirim_semua" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Kirim Semua</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#kirim_ondomohen" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Kirim Ondomohen</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#data_terkirim_trunojoyo" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Data Trunojoyo</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#data_terkirim_ondomohen" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Data Ondomohen</a>
              </li>
            </ul>
          </div>
          <div class="col-3">
            <button type="button" name="btn_upload_excel" id="btn_upload_excel" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_upload_excel">Upload Excel</button>
          </div>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="kirim_semua" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_kirim_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
            <div class="tab-pane fade" id="kirim_ondomohen" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_kirim_ondomohen_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
            <div class="tab-pane fade" id="data_terkirim_trunojoyo" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_terkirim_trunojoyo_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
            <div class="tab-pane fade" id="data_terkirim_ondomohen" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_terkirim_ondomohen_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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

  <div class="modal fade" id="modal_upload_excel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Excel</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" class="upload-form" id="upload-form" action="{{ url('penagihan/upload_excel') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_penagihan.xlsx')}}" target="_blank">here</a>.</span>
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
            <table id="detail_data_kirim_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>Nomor SJ</th>
                  <th>Customer</th>
                  <th>Tagihan</th>
                  <th>TT</th>
                  <th>Bayar</th>
                  <th>Detail Bayar</th>
                  <th>Keterangan</th>
                  <th>Krm Semua</th>
                  <th>Krm Ondo</th>
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

  <div class="modal fade" id="modal_send_ondomohen_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_send_ondomohen_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="detail_data_kirim_ondomohen_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>Nomor SJ</th>
                  <th>Customer</th>
                  <th>Tagihan</th>
                  <th>TT</th>
                  <th>Bayar</th>
                  <th>Detail Bayar</th>
                  <th>Keterangan</th>
                  <th>Krm Truno</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-save-data-ondomohen" class="btn btn-primary">Save changes</button>
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

  <div class="modal fade" id="modal_view_data_ondomohen">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_view_data_ondomohen"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="detail_view_data_ondomohen_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nomor SJ</th>
                  <th>No Invoice</th>
                  <th>Tanggal SJ</th>
                  <th>Customers</th>
                  <th>Tagihan</th>
                  <th>Keterangan</th>
                  <th>Dikirim</th>
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

  <div class="modal fade" id="modal_edit_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_edit_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="edit_trunojoyo_table" style="width: 100%; font-size: 9px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No SJ</th>
                  <th>Customer</th>
                  <th>Produk</th>
                  <th>No Invoice</th>
                  <th>TOP</th>
                  <th>Tgl Jatuh Tempo</th>
                  <th>DPP</th>
                  <th>PPN</th>
                  <th>Amount</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Diskon</th>
                  <th>Sub Amount</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-edit-data" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_ondomohen_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_edit_ondomohen_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="edit_ondomohen_table" style="width: 100%; font-size: 9px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No SJ</th>
                  <th>Customer</th>
                  <th>Produk</th>
                  <th>No Invoice</th>
                  <th>TOP</th>
                  <th>Tgl Jatuh Tempo</th>
                  <th>DPP</th>
                  <th>PPN</th>
                  <th>Amount</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Diskon</th>
                  <th>Sub Amount</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-edit-data-ondomohen" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_data_detail">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_edit_data_detail"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="edit_trunojoyo_detail_table" style="width: 100%; font-size: 8px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No SJ</th>
                  <th>Customer</th>
                  <th>Produk</th>
                  <th>No Invoice</th>
                  <th>TOP</th>
                  <th>Tgl Jatuh Tempo</th>
                  <th>DPP</th>
                  <th>PPN</th>
                  <th>Amount</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Diskon</th>
                  <th>Sub Amount</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-edit-data-detail" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_ondomohen_data_detail">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_edit_ondomohen_data_detail"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="edit_ondomohen_detail_table" style="width: 100%; font-size: 8px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No SJ</th>
                  <th>Customer</th>
                  <th>Produk</th>
                  <th>No Invoice</th>
                  <th>TOP</th>
                  <th>Tgl Jatuh Tempo</th>
                  <th>DPP</th>
                  <th>PPN</th>
                  <th>Amount</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Diskon</th>
                  <th>Sub Amount</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-edit-data-ondomohen-detail" class="btn btn-primary">Save changes</button>
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

    var table = $('#data_kirim_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("admin/dsgm/kirim_trunojoyo/table") }}',
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
         className:'dt-center',
         render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
       },
       {
         data:'jumlah_sj',
         name:'jumlah_sj',
         className:'dt-center'
       },
       {
         data:'action',
         name:'action',
         width: '15%',
         className:'dt-center'
       }
       ]
     });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#kirim_semua'){
        $('#data_kirim_table').DataTable().destroy();
        load_data_kirim();
      }else if(target == '#kirim_ondomohen'){
        $('#data_kirim_ondomohen_table').DataTable().destroy();
        load_data_kirim_ondomohen();
      }else if(target == '#data_terkirim_trunojoyo'){
        $('#data_terkirim_trunojoyo_table').DataTable().destroy();
        load_data_terkirim_trunojoyo();
      }else if(target == '#data_terkirim_ondomohen'){
        $('#data_terkirim_ondomohen_table').DataTable().destroy();
        load_data_terkirim_ondomohen();
      }
    });

    function load_data_kirim(from_date = '', to_date = '')
    {
      table = $('#data_kirim_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("admin/dsgm/kirim_trunojoyo/table") }}',
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
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'jumlah_sj',
          name:'jumlah_sj',
          className:'dt-center'
        },
        {
          data:'action',
          name:'action',
          width: '15%',
          className:'dt-center'
        }
        ]
      });
    }

    function load_data_kirim_ondomohen(from_date = '', to_date = '')
    {
      table = $('#data_kirim_ondomohen_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("admin/dsgm/kirim_ondomohen/table") }}',
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
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'jumlah_sj',
          name:'jumlah_sj',
          className:'dt-center'
        },
        {
          data:'action',
          name:'action',
          width: '15%',
          className:'dt-center'
        }
        ]
      });
    }

    function load_data_terkirim_trunojoyo(from_date = '', to_date = '')
    {
      table = $('#data_terkirim_trunojoyo_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("admin/dsgm/terkirim_trunojoyo/table") }}',
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
          data:'tanggal_kirim_trunojoyo',
          name:'tanggal_kirim_trunojoyo',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'jumlah_sj',
          name:'jumlah_sj',
          className:'dt-center'
        },
        {
          data:'action',
          name:'action',
          width: '15%',
          className:'dt-center'
        }
        ]
      });
    }

    function load_data_terkirim_ondomohen(from_date = '', to_date = '')
    {
      table = $('#data_terkirim_ondomohen_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("admin/dsgm/terkirim_ondomohen/table") }}',
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
          data:'tanggal_kirim_ondomohen',
          name:'tanggal_kirim_ondomohen',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'jumlah_sj',
          name:'jumlah_sj',
          className:'dt-center'
        },
        {
          data:'action',
          name:'action',
          width: '15%',
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

    function load_data_view_ondomohen(tanggal = '')
    {
      table = $('#detail_view_data_ondomohen_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("admin/dsgm/kirim_ondomohen/view/table") }}',
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
            if($('<div />').text(row.ket_ondomohen).html() == 1){
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
          data:'check_dikirim_ondomohen',
          name:'check_dikirim_ondomohen',
          className:'dt-center',
          width:'7%',
          render: function ( data, type, row)
          {
            if($('<div />').text(row.check_dibayar_admin).html() == 1){
              if($('<div />').text(row.metode_pembayaran).html() == 1){
                return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" readonly>';
              }else{
                return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" checked="checked" readonly>';
              }
            }else{
              return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" checked="checked" readonly>';
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
            if($('<div />').text(row.check_dibayar_admin).html() == 1){
              return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" checked="checked" readonly>';
            }else{
              return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" readonly>';
            }
          }
        }
        ]
      });
    }

    function load_data_kirim_detail(tanggal = '')
    {
      table = $('#detail_data_kirim_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("admin/dsgm/kirim_trunojoyo/detail/table") }}',
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
            if($('<div />').text(row.noinv).html() == null || $('<div />').text(row.noinv).html() == ''){
              return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html();
            }else{
              return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html() + '<br> ( ' + $('<div />').text(row.noinv).html() + ' )';
            }
          },
          width:'10%'
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
          data:'check_diserahkan_admin',
          name:'check_diserahkan_admin',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="diserahkan[' + $('<div />').text(row.nosj).html() + ']"]').change(function(){
              if ($(this).is(':checked')) {
                $('[name="dikirim[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", true);
                $('[name="dikirim_ondomohen[' + $('<div />').text(row.nosj).html() + ']"]').prop("readonly", true);
                $('[name="dibayar[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
              }else{
                $('[name="dikirim[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
                $('[name="dikirim_ondomohen[' + $('<div />').text(row.nosj).html() + ']"]').prop("readonly", false);
                $('[name="dibayar[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", true);
                $('[name="dibayar[' + $('<div />').text(row.nosj).html() + ']"]').prop("checked", false).trigger('change');
              }
            });

            return '<input type="checkbox" name="diserahkan[' + $('<div />').text(row.nosj).html() + ']" value="1">';
          },
          width:'7%'
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

            return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" disabled>';
          },
          width:'7%'
        },
        {
          data:'metode_pembayaran',
          name:'metode_pembayaran',
          className:'dt-center',
          width:'17%',
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

            $('[name="tagih_cust[' + $('<div />').text(row.nosj).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tagih_cust[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.tanggal_tagih_cust).html() + '" style="width:100%; margin-bottom: 10px;" placeholder="Tanggal Bayar" disabled> <select name="metode[' + $('<div />').text(row.nosj).html() + ']" style="width:100%;" disabled>' +
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
          data:'keterangan',
          name:'keterangan',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<textarea rows="2" name="keterangan[' + $('<div />').text(row.nosj).html() + ']" style="width:100%;">' + $('<div />').text(row.keterangan).html() + '</textarea><br>'+
            '<div><input type="checkbox" name="ket_trunojoyo[' + $('<div />').text(row.nosj).html() + ']" value="1"> Trunojoyo</div>'+
            '<input type="checkbox" name="ket_ondomohen[' + $('<div />').text(row.nosj).html() + ']" value="1"> Ondomohen';
          },
          width:'18%'
        },
        {
          data:'check_dikirim_trunojoyo',
          name:'check_dikirim_trunojoyo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="checkbox" name="dikirim[' + $('<div />').text(row.nosj).html() + ']" value="1">';
          },
          width:'7%'
        },
        {
          data:'check_dikirim_ondomohen',
          name:'check_dikirim_ondomohen',
          className:'dt-center',
          render: function ( data, type, row)
          {
            if(data == 1){
              return '<input type="checkbox" name="dikirim_ondomohen[' + $('<div />').text(row.nosj).html() + ']" value="1" checked readonly>';
            }else{
              return '<input type="checkbox" name="dikirim_ondomohen[' + $('<div />').text(row.nosj).html() + ']" value="1">';
            }
          },
          width:'7%'
        }
        ]
      });
    }

    function load_data_kirim_ondomohen_detail(tanggal = '')
    {
      table = $('#detail_data_kirim_ondomohen_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("admin/dsgm/kirim_ondomohen/detail/table") }}',
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
            if($('<div />').text(row.noinv).html() == null || $('<div />').text(row.noinv).html() == ''){
              return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html();
            }else{
              return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html() + '<br> ( ' + $('<div />').text(row.noinv).html() + ' )';
            }
          },
          width:'10%'
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
          data:'check_diserahkan_admin',
          name:'check_diserahkan_admin',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="diserahkan[' + $('<div />').text(row.nosj).html() + ']"]').change(function(){
              if ($(this).is(':checked')) {
                $('[name="dikirim[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", true);
                $('[name="dibayar[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
              }else{
                $('[name="dikirim[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", false);
                $('[name="dibayar[' + $('<div />').text(row.nosj).html() + ']"]').prop("disabled", true);
                $('[name="dibayar[' + $('<div />').text(row.nosj).html() + ']"]').prop("checked", false).trigger('change');
              }
            });

            return '<input type="checkbox" name="diserahkan[' + $('<div />').text(row.nosj).html() + ']" value="1">';
          },
          width:'7%'
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

            return '<input type="checkbox" name="dibayar[' + $('<div />').text(row.nosj).html() + ']" value="1" disabled>';
          },
          width:'7%'
        },
        {
          data:'metode_pembayaran',
          name:'metode_pembayaran',
          className:'dt-center',
          width:'17%',
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

            $('[name="tagih_cust[' + $('<div />').text(row.nosj).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tagih_cust[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.tanggal_tagih_cust).html() + '" style="width:100%; margin-bottom: 10px;" placeholder="Tanggal Bayar" disabled> <select name="metode[' + $('<div />').text(row.nosj).html() + ']" style="width:100%;" disabled>' +
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
          data:'keterangan',
          name:'keterangan',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<textarea rows="2" name="keterangan[' + $('<div />').text(row.nosj).html() + ']" style="width:100%;">' + $('<div />').text(row.keterangan).html() + '</textarea><br>'+
            '<div><input type="checkbox" name="ket_trunojoyo[' + $('<div />').text(row.nosj).html() + ']" value="1"> Trunojoyo</div>';
          },
          width:'18%'
        },
        {
          data:'check_dikirim_trunojoyo',
          name:'check_dikirim_trunojoyo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="checkbox" name="dikirim[' + $('<div />').text(row.nosj).html() + ']" value="1">';
          },
          width:'7%'
        }
        ]
      });
    }

    function load_data_edit_trunojoyo(tanggal = '')
    {
      table = $('#edit_trunojoyo_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("admin/dsgm/kirim_trunojoyo/detail/edit/table") }}',
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
          }
        },
        {
          data:'customer',
          name:'customer',
          className:'dt-center'
        },
        {
          data:'itemid',
          name:'itemid',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="itemid[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="' + $('<div />').text(row.itemid).html() + '">' + $('<div />').text(row.itemid).html();
          }
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="noinv[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="No Invoice" value="' + $('<div />').text(row.noinv).html() + '">';
          }
        },
        {
          data:'top',
          name:'top',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="top[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="TOP" value="' + $('<div />').text(row.top).html() + '">';
          }
        },
        {
          data:'tanggal_jatuh_tempo',
          name:'tanggal_jatuh_tempo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Tgl Jatuh Tempo" value="' + $('<div />').text(row.tanggal_jatuh_tempo).html() + '">';
          }
        },
        {
          data:'dpp',
          name:'dpp',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="dpp[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="DPP" value="' + $('<div />').text(row.dpp).html() + '">';
          }
        },
        {
          data:'ppn',
          name:'ppn',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="ppn[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="PPN" value="' + $('<div />').text(row.ppn).html() + '">';
          }
        },
        {
          data:'amount',
          name:'amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Amount" value="' + $('<div />').text(row.amount).html() + '">';
          }
        },
        {
          data:'qty',
          name:'qty',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="qty[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Qty" value="' + $('<div />').text(row.qty).html() + '">';
          }
        },
        {
          data:'price',
          name:'price',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="price[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Price" value="' + $('<div />').text(row.price).html() + '">';
          }
        },
        {
          data:'diskon',
          name:'diskon',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="diskon[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Diskon" value="' + $('<div />').text(row.diskon).html() + '">';
          }
        },
        {
          data:'sub_amount',
          name:'sub_amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="sub_amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Sub Amount" value="' + $('<div />').text(row.sub_amount).html() + '">';
          }
        }
        ]
      });
    }

    function load_data_edit_ondomohen(tanggal = '')
    {
      table = $('#edit_ondomohen_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("admin/dsgm/kirim_ondomohen/detail/edit/table") }}',
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
          }
        },
        {
          data:'customer',
          name:'customer',
          className:'dt-center'
        },
        {
          data:'itemid',
          name:'itemid',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="itemid[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="' + $('<div />').text(row.itemid).html() + '">' + $('<div />').text(row.itemid).html();
          }
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="noinv[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="No Invoice" value="' + $('<div />').text(row.noinv).html() + '">';
          }
        },
        {
          data:'top',
          name:'top',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="top[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="TOP" value="' + $('<div />').text(row.top).html() + '">';
          }
        },
        {
          data:'tanggal_jatuh_tempo',
          name:'tanggal_jatuh_tempo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Tgl Jatuh Tempo" value="' + $('<div />').text(row.tanggal_jatuh_tempo).html() + '">';
          }
        },
        {
          data:'dpp',
          name:'dpp',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="dpp[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="DPP" value="' + $('<div />').text(row.dpp).html() + '">';
          }
        },
        {
          data:'ppn',
          name:'ppn',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="ppn[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="PPN" value="' + $('<div />').text(row.ppn).html() + '">';
          }
        },
        {
          data:'amount',
          name:'amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Amount" value="' + $('<div />').text(row.amount).html() + '">';
          }
        },
        {
          data:'qty',
          name:'qty',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="qty[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Qty" value="' + $('<div />').text(row.qty).html() + '">';
          }
        },
        {
          data:'price',
          name:'price',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="price[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Price" value="' + $('<div />').text(row.price).html() + '">';
          }
        },
        {
          data:'diskon',
          name:'diskon',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="diskon[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Diskon" value="' + $('<div />').text(row.diskon).html() + '">';
          }
        },
        {
          data:'sub_amount',
          name:'sub_amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="sub_amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Sub Amount" value="' + $('<div />').text(row.sub_amount).html() + '">';
          }
        }
        ]
      });
    }

    function load_data_edit_trunojoyo_detail(tanggal = '')
    {
      table = $('#edit_trunojoyo_detail_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("admin/dsgm/terkirim_trunojoyo/detail/edit/table") }}',
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
          }
        },
        {
          data:'customer',
          name:'customer',
          className:'dt-center'
        },
        {
          data:'itemid',
          name:'itemid',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="itemid[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="' + $('<div />').text(row.itemid).html() + '">' + $('<div />').text(row.itemid).html();
          }
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="noinv[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="No Invoice" value="' + $('<div />').text(row.noinv).html() + '">';
          }
        },
        {
          data:'top',
          name:'top',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="top[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="TOP" value="' + $('<div />').text(row.top).html() + '">';
          }
        },
        {
          data:'tanggal_jatuh_tempo',
          name:'tanggal_jatuh_tempo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Tgl Jatuh Tempo" value="' + $('<div />').text(row.tanggal_jatuh_tempo).html() + '">';
          }
        },
        {
          data:'dpp',
          name:'dpp',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="dpp[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="DPP" value="' + $('<div />').text(row.dpp).html() + '">';
          }
        },
        {
          data:'ppn',
          name:'ppn',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="ppn[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="PPN" value="' + $('<div />').text(row.ppn).html() + '">';
          }
        },
        {
          data:'amount',
          name:'amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Amount" value="' + $('<div />').text(row.amount).html() + '">';
          }
        },
        {
          data:'qty',
          name:'qty',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="qty[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Qty" value="' + $('<div />').text(row.qty).html() + '">';
          }
        },
        {
          data:'price',
          name:'price',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="price[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Price" value="' + $('<div />').text(row.price).html() + '">';
          }
        },
        {
          data:'diskon',
          name:'diskon',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="diskon[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Diskon" value="' + $('<div />').text(row.diskon).html() + '">';
          }
        },
        {
          data:'sub_amount',
          name:'sub_amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="sub_amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Sub Amount" value="' + $('<div />').text(row.sub_amount).html() + '">';
          }
        },
        {
          data:'keterangan',
          name:'keterangan',
          className:'dt-center',
          render: function ( data, type, row)
          {
            var strKeterangan = '';

            
            if($('<div />').text(row.check_dibayar_admin).html() == 1){
              strKeterangan += '<textarea rows="2" name="keterangan_penagihan[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;">' + $('<div />').text(row.keterangan_penagihan).html() + '</textarea><br>';
            }else{
              if($('<div />').text(row.check_diserahkan_admin).html() == 1){
                strKeterangan += '<textarea rows="2" name="keterangan_penerimaan[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;">' + $('<div />').text(row.keterangan_penerimaan).html() + '</textarea><br>';
              }else{
                strKeterangan += '<textarea rows="2" name="keterangan[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;">' + $('<div />').text(row.keterangan).html() + '</textarea><br>';
              }
            }

            if($('<div />').text(row.ket_trunojoyo).html() == 1){
              strKeterangan += '<div><input type="checkbox" name="ket_trunojoyo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="1" checked> Trunojoyo</div>';
            }else{
              strKeterangan += '<div><input type="checkbox" name="ket_trunojoyo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="1"> Trunojoyo</div>';
            }
            
            if($('<div />').text(row.ket_ondomohen).html() == 1){
              strKeterangan += '<input type="checkbox" name="ket_ondomohen[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="1" checked> Ondomohen';
            }else{
              strKeterangan += '<input type="checkbox" name="ket_ondomohen[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="1"> Ondomohen';
            }


            return strKeterangan;
          }
        }
        ]
      });
    }

    function load_data_edit_ondomohen_detail(tanggal = '')
    {
      table = $('#edit_ondomohen_detail_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("admin/dsgm/terkirim_ondomohen/detail/edit/table") }}',
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
          }
        },
        {
          data:'customer',
          name:'customer',
          className:'dt-center'
        },
        {
          data:'itemid',
          name:'itemid',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="itemid[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="' + $('<div />').text(row.itemid).html() + '">' + $('<div />').text(row.itemid).html();
          }
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="noinv[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="No Invoice" value="' + $('<div />').text(row.noinv).html() + '">';
          }
        },
        {
          data:'top',
          name:'top',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="top[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="TOP" value="' + $('<div />').text(row.top).html() + '">';
          }
        },
        {
          data:'tanggal_jatuh_tempo',
          name:'tanggal_jatuh_tempo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Tgl Jatuh Tempo" value="' + $('<div />').text(row.tanggal_jatuh_tempo).html() + '">';
          }
        },
        {
          data:'dpp',
          name:'dpp',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="dpp[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="DPP" value="' + $('<div />').text(row.dpp).html() + '">';
          }
        },
        {
          data:'ppn',
          name:'ppn',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="ppn[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="PPN" value="' + $('<div />').text(row.ppn).html() + '">';
          }
        },
        {
          data:'amount',
          name:'amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Amount" value="' + $('<div />').text(row.amount).html() + '">';
          }
        },
        {
          data:'qty',
          name:'qty',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="qty[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Qty" value="' + $('<div />').text(row.qty).html() + '">';
          }
        },
        {
          data:'price',
          name:'price',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="price[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Price" value="' + $('<div />').text(row.price).html() + '">';
          }
        },
        {
          data:'diskon',
          name:'diskon',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="diskon[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Diskon" value="' + $('<div />').text(row.diskon).html() + '">';
          }
        },
        {
          data:'sub_amount',
          name:'sub_amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="sub_amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Sub Amount" value="' + $('<div />').text(row.sub_amount).html() + '">';
          }
        },
        {
          data:'keterangan',
          name:'keterangan',
          className:'dt-center',
          render: function ( data, type, row)
          {
            var strKeterangan = '';

            
            if($('<div />').text(row.check_dibayar_admin).html() == 1){
              strKeterangan += '<textarea rows="2" name="keterangan_penagihan[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;">' + $('<div />').text(row.keterangan_penagihan).html() + '</textarea><br>';
            }else{
              if($('<div />').text(row.check_diserahkan_admin).html() == 1){
                strKeterangan += '<textarea rows="2" name="keterangan_penerimaan[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;">' + $('<div />').text(row.keterangan_penerimaan).html() + '</textarea><br>';
              }else{
                strKeterangan += '<textarea rows="2" name="keterangan[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;">' + $('<div />').text(row.keterangan).html() + '</textarea><br>';
              }
            }

            if($('<div />').text(row.ket_trunojoyo).html() == 1){
              strKeterangan += '<div><input type="checkbox" name="ket_trunojoyo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="1" checked> Trunojoyo</div>';
            }else{
              strKeterangan += '<div><input type="checkbox" name="ket_trunojoyo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="1"> Trunojoyo</div>';
            }
            
            if($('<div />').text(row.ket_ondomohen).html() == 1){
              strKeterangan += '<input type="checkbox" name="ket_ondomohen[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="1" checked> Ondomohen';
            }else{
              strKeterangan += '<input type="checkbox" name="ket_ondomohen[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="1"> Ondomohen';
            }


            return strKeterangan;
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
        if(target == '#kirim_semua'){
          $('#data_kirim_table').DataTable().destroy();
          load_data_kirim(from_date, to_date);
        }else if(target == '#kirim_ondomohen'){
          $('#data_kirim_ondomohen_table').DataTable().destroy();
          load_data_kirim_ondomohen(from_date, to_date);
        }else if(target == '#data_terkirim_trunojoyo'){
          $('#data_terkirim_trunojoyo_table').DataTable().destroy();
          load_data_terkirim_trunojoyo(from_date, to_date);
        }else if(target == '#data_terkirim_ondomohen'){
          $('#data_terkirim_ondomohen_table').DataTable().destroy();
          load_data_terkirim_ondomohen(from_date, to_date);
        }
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(target == '#kirim_semua'){
        $('#data_kirim_table').DataTable().destroy();
        load_data_kirim();
      }else if(target == '#kirim_ondomohen'){
        $('#data_kirim_ondomohen_table').DataTable().destroy();
        load_data_kirim_ondomohen();
      }else if(target == '#data_terkirim_trunojoyo'){
        $('#data_terkirim_trunojoyo_table').DataTable().destroy();
        load_data_terkirim_trunojoyo();
      }else if(target == '#data_terkirim_ondomohen'){
        $('#data_terkirim_ondomohen_table').DataTable().destroy();
        load_data_terkirim_ondomohen();
      }
    });

    $('body').on('click', '#view-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_view_data").innerHTML = "Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#detail_view_data_table').DataTable().destroy();
      load_data_view(tanggal);
    });

    $('body').on('click', '#view-data-ondomohen', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_view_data_ondomohen").innerHTML = "Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#detail_view_data_ondomohen_table').DataTable().destroy();
      load_data_view_ondomohen(tanggal);
    });

    $('body').on('click', '#send-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_send_data").innerHTML = "Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#detail_data_kirim_table').DataTable().destroy();
      load_data_kirim_detail(tanggal);
    });

    $('body').on('click', '#send-data-ondomohen', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_send_ondomohen_data").innerHTML = "Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#detail_data_kirim_ondomohen_table').DataTable().destroy();
      load_data_kirim_ondomohen_detail(tanggal);
    });

    $('body').on('click', '#edit-data-bef', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_edit_data").innerHTML = "Edit Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#edit_trunojoyo_table').DataTable().destroy();
      load_data_edit_trunojoyo(tanggal);
    });

    $('body').on('click', '#edit-data-ondomohen-bef', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_edit_ondomohen_data").innerHTML = "Edit Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#edit_ondomohen_table').DataTable().destroy();
      load_data_edit_ondomohen(tanggal);
    });

    $('body').on('click', '#edit-data-aft', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_edit_data_detail").innerHTML = "Edit Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#edit_trunojoyo_detail_table').DataTable().destroy();
      load_data_edit_trunojoyo_detail(tanggal);
    });

    $('body').on('click', '#edit-data-ondomohen-aft', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_edit_ondomohen_data_detail").innerHTML = "Edit Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#edit_ondomohen_detail_table').DataTable().destroy();
      load_data_edit_ondomohen_detail(tanggal);
    });

    $('#btn-save-data').click( function() {
      var data = table.$('input, textarea, select').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-data').data('token'),
        },
        type: "POST",
        url: '{{ url("admin/dsgm/update/kirim_trunojoyo") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#data_kirim_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_send_data').modal('hide');
          $("#modal_send_data").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-save-data-ondomohen').click( function() {
      var data = table.$('input, textarea, select').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-data-ondomohen').data('token'),
        },
        type: "POST",
        url: '{{ url("admin/dsgm/update/kirim_ondomohen") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#data_kirim_ondomohen_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_send_ondomohen_data').modal('hide');
          $("#modal_send_ondomohen_data").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-edit-data').click( function() {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-data').data('token'),
        },
        type: "POST",
        url: '{{ url("admin/dsgm/edit/kirim_trunojoyo") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#edit_trunojoyo_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_edit_data').modal('hide');
          $("#modal_edit_data").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-edit-data-ondomohen').click( function() {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-data-ondomohen').data('token'),
        },
        type: "POST",
        url: '{{ url("admin/dsgm/edit/kirim_ondomohen") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#edit_ondomohen_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_edit_ondomohen_data').modal('hide');
          $("#modal_edit_ondomohen_data").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-edit-data-detail').click( function() {
      var data = table.$('input, textarea').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-data-detail').data('token'),
        },
        type: "POST",
        url: '{{ url("admin/dsgm/edit/terkirim_trunojoyo") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#edit_trunojoyo_detail_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_edit_data_detail').modal('hide');
          $("#modal_edit_data_detail").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-edit-data-ondomohen-detail').click( function() {
      var data = table.$('input, textarea').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-data-ondomohen-detail').data('token'),
        },
        type: "POST",
        url: '{{ url("admin/dsgm/edit/terkirim_ondomohen") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#edit_ondomohen_detail_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_edit_ondomohen_data_detail').modal('hide');
          $("#modal_edit_ondomohen_data_detail").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
@endsection