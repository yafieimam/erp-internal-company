@extends('layouts.app_admin')

@section('title')
<title>KARTU PIUTANG - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Kartu Piutang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Penagihan</li>
            <li class="breadcrumb-item">Kartu Piutang</li>
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
        <div class="card-header">
          <div class="row">
            <div class="col-9">
              <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#semua" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Total Semua</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-profile-tab" data-toggle="pill" href="#belum_lunas" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Belum Lunas</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#sudah_lunas" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Sudah Lunas</a>
                </li>
              </ul>
            </div>
            <div class="col-3">
              <button type="button" name="btn_print_kartu_piutang" id="btn_print_kartu_piutang" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_print_kartu_piutang">Print Kartu Piutang</button>
            </div>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade" id="semua" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_semua_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Customers</th>
                    <th>Saldo</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade show active" id="belum_lunas" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_belum_lunas_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Customers</th>
                    <th>Saldo</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="sudah_lunas" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_sudah_lunas_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Customers</th>
                    <th>Saldo</th>
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

  <div class="modal fade" id="modal_print_kartu_piutang">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Print Kartu Piutang</h4>
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

  <div class="modal fade" id="modal_view_data_semua">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_view_data_semua"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="detail_data_semua_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th>Tanggal</th>
                  <th>No SJ</th>
                  <th>No AR</th>
                  <th>No Inv</th>
                  <th>Keterangan</th>
                  <th>Diff Date</th>
                  <th>Sub Amount</th>
                  <th>Total Amount</th>
                  <th>Saldo</th>
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

  <div class="modal fade" id="modal_view_data_belum_lunas">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_view_data_belum_lunas"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="detail_data_belum_lunas_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th>Tanggal</th>
                  <th>No SJ</th>
                  <th>No Inv</th>
                  <th>Produk</th>
                  <th>Sub Amount</th>
                  <th>Total Amount</th>
                  <th>Saldo</th>
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

  <div class="modal fade" id="modal_view_data_sudah_lunas">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_view_data_sudah_lunas"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="detail_data_sudah_lunas_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th>Tanggal</th>
                  <th>No SJ</th>
                  <th>No AR</th>
                  <th>No Inv</th>
                  <th>Keterangan</th>
                  <th>Diff Date</th>
                  <th>Sub Amount</th>
                  <th>Total Amount</th>
                  <th>Saldo</th>
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

    var table = $('#data_belum_lunas_table').DataTable({
      processing: true,
      serverSide: true,
      lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
      ajax: {
        url:'{{ url("penagihan/kartu_piutang/belum_lunas/table") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
      },
      order: [[1,'asc']],
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
       data:'customers',
       name:'customers',
       className:'dt-center'
      },
      {
       data:'saldo',
       name:'saldo',
       className:'dt-center',
       render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
       width: '20%'
      },
      {
       data:'action',
       name:'action',
       className:'dt-center',
       width: '5%'
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

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#semua'){
        $('#data_semua_table').DataTable().destroy();
        load_data_semua();
      }else if(target == '#belum_lunas'){
        $('#data_belum_lunas_table').DataTable().destroy();
        load_data_belum_lunas();
      }else if(target == '#sudah_lunas'){
        $('#data_sudah_lunas_table').DataTable().destroy();
        load_data_sudah_lunas();
      }
    });

    function load_data_semua()
    {
      table = $('#data_semua_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/kartu_piutang/semua/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'asc']],
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
          data:'customers',
          name:'customers',
          className:'dt-center'
        },
        {
          data:'saldo',
          name:'saldo',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width: '20%'
        },
        {
         data:'action',
         name:'action',
         className:'dt-center',
         width: '5%'
        }
        ]
      });
    }

    function load_data_belum_lunas()
    {
      table = $('#data_belum_lunas_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/kartu_piutang/belum_lunas/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'asc']],
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
          data:'customers',
          name:'customers',
          className:'dt-center'
        },
        {
          data:'saldo',
          name:'saldo',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width: '20%'
        },
        {
         data:'action',
         name:'action',
         className:'dt-center',
         width: '5%'
        }
        ]
      });
    }

    function load_data_sudah_lunas()
    {
      table = $('#data_sudah_lunas_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/kartu_piutang/sudah_lunas/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'asc']],
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
          data:'customers',
          name:'customers',
          className:'dt-center'
        },
        {
          data:'saldo',
          name:'saldo',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width: '20%'
        },
        {
         data:'action',
         name:'action',
         className:'dt-center',
         width: '5%'
        }
        ]
      });
    }

    function load_data_detail_semua(custid = '')
    {
      table = $('#detail_data_semua_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/kartu_piutang/semua/detail/table") }}',
          data:{custid:custid},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
          className:'details-control',
          orderable:false,
          searchable:false,
          data:null,
          defaultContent:'',
          width: '5%'
        },
        {
          data:'tanggal',
          name:'tanggal',
          className:'dt-center',
          render: function ( data, type, row ) {
            if($('<div />').text(row.lunas).html() == 1){
              return '&#10003; ' + moment(data).format('DD MMM YYYY');
            }else{
              return moment(data).format('DD MMM YYYY');
            }
          },
          width:'12%'
        },
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'noar',
          name:'noar',
          className:'dt-center',
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'keterangan',
          name:'keterangan',
          className:'dt-center',
          defaultContent:'--'
        },
        {
          data:'selisih_hari',
          name:'selisih_hari',
          className:'dt-center',
          width:'10%',
          render: function(data, type, row)
          {
            if(data == null){
              return "--";
            }else{
              return Math.round(data) + " Hari";
            }
          }
        },
        {
          data:'sub_nominal',
          name:'sub_nominal',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'total_nominal',
          name:'total_nominal',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'10%'
        },
        {
          data:'saldo',
          name:'saldo',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'10%'
        },
        ]
      });
    }

    function load_data_detail_belum_lunas(custid = '')
    {
      table = $('#detail_data_belum_lunas_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/kartu_piutang/belum_lunas/detail/table") }}',
          data:{custid:custid},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
          className:'details-control',
          orderable:false,
          searchable:false,
          data:null,
          defaultContent:'',
          width: '5%'
        },
        {
          data:'tanggal_do',
          name:'tanggal_do',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          },
          width:'10%'
        },
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width:'10%'
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'itemname',
          name:'itemname',
          className:'dt-center',
          // render: function ( data, type, row)
          // {
          //   var str_word = $('<div />').text(row.itemname).html();
          //   str_word = str_word.replace(new RegExp('SP CaCO3', 'g'), '');
          //   return str_word;
          // },
          width:'30%'
        },
        {
          data:null,
          name:null,
          className:'dt-center',
          render: function ( data, type, row)
          {
            var total = $('<div />').text(row.qty).html() * $('<div />').text(row.price).html();
            return 'Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          },
          width:'10%'
        },
        {
          data:'tagihan',
          name:'tagihan',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'10%'
        },
        {
          data:'saldo',
          name:'saldo',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'10%'
        },
        ]
      });
    }

    function load_data_detail_sudah_lunas(custid = '')
    {
      table = $('#detail_data_sudah_lunas_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/kartu_piutang/sudah_lunas/detail/table") }}',
          data:{custid:custid},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
          className:'details-control',
          orderable:false,
          searchable:false,
          data:null,
          defaultContent:'',
          width: '5%'
        },
        {
          data:'tanggal',
          name:'tanggal',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY') ;
          },
          width:'10%'
        },
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'noar',
          name:'noar',
          className:'dt-center',
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'keterangan',
          name:'keterangan',
          className:'dt-center',
          defaultContent:'--'
        },
        {
          data:'selisih_hari',
          name:'selisih_hari',
          className:'dt-center',
          width:'10%',
          render: function(data, type, row)
          {
            return Math.round(data) + " Hari";
          }
        },
        {
          data:'sub_nominal',
          name:'sub_nominal',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'total_nominal',
          name:'total_nominal',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'10%'
        },
        {
          data:'saldo',
          name:'saldo',
          className:'dt-center',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
          width:'10%'
        },
        ]
      });
    }

    $('body').on('click', '#view-data-semua', function () {
      var custid = $(this).data("id");

      document.getElementById("judul_modal_view_data_semua").innerHTML = "Data Customers " + custid;
      $('#detail_data_semua_table').DataTable().destroy();
      load_data_detail_semua(custid);
    });

    $('body').on('click', '#view-data-belum-lunas', function () {
      var custid = $(this).data("id");

      document.getElementById("judul_modal_view_data_belum_lunas").innerHTML = "Data Customers " + custid;
      $('#detail_data_belum_lunas_table').DataTable().destroy();
      load_data_detail_belum_lunas(custid);
    });

    $('body').on('click', '#view-data-sudah-lunas', function () {
      var custid = $(this).data("id");

      document.getElementById("judul_modal_view_data_sudah_lunas").innerHTML = "Data Customers " + custid;
      $('#detail_data_sudah_lunas_table').DataTable().destroy();
      load_data_detail_sudah_lunas(custid);
    });

    function formatBelumLunas(d){
      Object.keys(d[0]).forEach(function(key) {
        if(d[0][key] === null) {
          d[0][key] = 0;
        }
      });
      return '<table border="0" style="width: 100%;">'+
      '<tr>'+
      '<td width="20%">Quantity :</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['qty'])+' TON</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Harga Per KG :</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['price'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Discount :</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['diskon'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Pajak :</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['pajak'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Biaya Lain-lain :</td>'+
      '<td>--</td>'+
      '</tr>'+
      '</table>';
    }

    function formatSudahLunas(d){
      var isi = '<table border="0" style="width: 100%;">'+
      '<tr>'+
      '<td width="10%">Tanggal</td>'+
      '<td width="10%">No SJ</td>'+
      '<td width="10%">No Inv</td>'+
      '<td width="30%">Produk</td>'+
      '<td width="10%">Diff Date</td>'+
      '<td width="10%">Sub Amount</td>'+
      '<td width="10%">Total Amount</td>'+
      '</tr>';
      $.each(d, function(k, v) {
        isi += '<tr>'+
        '<td width="10%">' + v.tanggal_do + '</td>'+
        '<td width="10%">' + v.nosj + '</td>'+
        '<td width="10%">' + v.noinv + '</td>'+
        '<td width="30%">' + v.itemname + '</td>'+
        '<td width="10%">' + Math.round(v.selisih_hari) + ' Hari</td>'+
        '<td width="10%">' + $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display((v.qty * v.price)) + '</td>'+
        '<td width="10%">' + $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(v.tagihan) + '</td>'+
        '</tr>';
      });
      isi += '</table>';
      return isi;
    }

    $('#detail_data_semua_table').on( 'click', 'tbody td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row( tr );

      if(row.data()['noar']){
        $.ajax({
          type: "GET",
          url: "{{ url('penagihan/kartu_piutang/sudah_lunas/detail/control/table') }}",
          data: { 'noar' : row.data()['noar']},
          success: function (data) {
            if ( row.child.isShown() ) {
              row.child.hide();
              tr.removeClass('shown');
            }else {
              row.child( formatSudahLunas(data) ).show();
              tr.addClass('shown');
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }else{
        $.ajax({
          type: "GET",
          url: "{{ url('penagihan/kartu_piutang/belum_lunas/detail/control/table') }}",
          data: { 'nosj' : row.data()['nosj'], 'itemid' :  row.data()['itemid']},
          success: function (data) {
            if ( row.child.isShown() ) {
              row.child.hide();
              tr.removeClass('shown');
            }else {
              row.child( formatBelumLunas(data) ).show();
              tr.addClass('shown');
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#detail_data_belum_lunas_table').on( 'click', 'tbody td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row( tr );
      $.ajax({
        type: "GET",
        url: "{{ url('penagihan/kartu_piutang/belum_lunas/detail/control/table') }}",
        data: { 'nosj' : row.data()['nosj'], 'itemid' :  row.data()['itemid']},
        success: function (data) {
          if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
          }else {
            row.child( formatBelumLunas(data) ).show();
            tr.addClass('shown');
          }
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('#detail_data_sudah_lunas_table').on( 'click', 'tbody td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row( tr );

      console.log(row.data());

      if(row.data()['noar']){
        $.ajax({
          type: "GET",
          url: "{{ url('penagihan/kartu_piutang/sudah_lunas/detail/control/table') }}",
          data: { 'noar' : row.data()['noar']},
          success: function (data) {
            if ( row.child.isShown() ) {
              row.child.hide();
              tr.removeClass('shown');
            }else {
              row.child( formatSudahLunas(data) ).show();
              tr.addClass('shown');
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }else{
        $.ajax({
          type: "GET",
          url: "{{ url('penagihan/kartu_piutang/belum_lunas/detail/control/table') }}",
          data: { 'nosj' : row.data()['nosj'], 'itemid' :  row.data()['itemid']},
          success: function (data) {
            if ( row.child.isShown() ) {
              row.child.hide();
              tr.removeClass('shown');
            }else {
              row.child( formatBelumLunas(data) ).show();
              tr.addClass('shown');
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-download-excel', function () {
      var from_date = $('#tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      var customers = document.getElementById("customers").value;

      var url_excel = "{{ url('penagihan/kartu_piutang/excel/from_date/to_date/custid') }}";
      url_excel = url_excel.replace('from_date', enc(from_date.toString()));
      url_excel = url_excel.replace('to_date', enc(to_date.toString()));
      url_excel = url_excel.replace('custid', enc(customers.toString()));
      window.open(url_excel, '_blank');
    });

    $('body').on('click', '#btn-print-pdf', function () {
      var from_date = $('#tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      var customers = document.getElementById("customers").value;

      $.ajax({
        type: "GET",
        url: "{{ url('penagihan/kartu_piutang/print') }}",
        data: { 'from_date' : from_date, 'to_date' : to_date, 'customers' : customers },
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
      dropdownParent: $('#modal_print_kartu_piutang .modal-content'),
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