@extends('layouts.app_admin')

@section('title')
<title>ABSENSI - PT. DWI SELO GIRI MAS</title>
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
  #data_absensi_table {
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
    .lihat-table {
      overflow-x: auto;
    }
    .radio-control {
      padding-left: 0 !important;
    }
    .save-btn-in {
      width: 100%;
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
          <h1 class="m-0 text-dark">Absensi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Administrasi</li>
            <li class="breadcrumb-item">Absensi</li>
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
            <div class="col-6">
              <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#data_absensi" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data Absensi</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#data_absensi_tidak_lengkap" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Data Absensi Tidak Lengkap</a>
                </li>
              </ul>
            </div>
            <div class="col-3">
              <button type="button" name="btn_upload_excel" id="btn_upload_excel" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_upload_excel">Upload Excel</button>
            </div>
            <div class="col-3">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_manual">Input Manual</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="data_absensi" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_absensi_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jml Karyawan</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="data_absensi_tidak_lengkap" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_absensi_tidak_lengkap_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Karyawan</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
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
        <form method="post" class="upload-form" id="upload-form" action="{{ url('/uploadExcelAbsensi') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_absensi.xlsx')}}" target="_blank">here</a>.</span>
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

  <div class="modal fade" id="modal_input_manual">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Manual</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="input_manual_form" id="input_manual_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="nik">NIK</label>
                  <select id="nik" name="nik" class="form-control select2 nik" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Tanggal</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="tanggal_absensi" id="tanggal_absensi" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="jam_masuk">Jam Masuk</label>
                  <input type="text" name="jam_masuk" class="form-control" id="jam_masuk" autocomplete="off" placeholder="Jam Masuk">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="jam_pulang">Jam Pulang</label>
                  <input type="text" name="jam_pulang" class="form-control" id="jam_pulang" autocomplete="off" placeholder="Jam Pulang">
                </div>
              </div>
            </div>
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
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="edit_form" id="edit_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_karyawan">Karyawan</label>
                  <input type="text" name="edit_karyawan" class="form-control" id="edit_karyawan" autocomplete="off" readonly>
                  <input type="hidden" name="edit_id" class="form-control" id="edit_id" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_tanggal">Tanggal</label>
                  <input type="text" name="edit_tanggal" class="form-control" id="edit_tanggal" autocomplete="off" readonly>
                  <input type="hidden" name="edit_nik" class="form-control" id="edit_nik" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_jam_masuk">Jam Masuk</label>
                  <input type="text" name="edit_jam_masuk" class="form-control" id="edit_jam_masuk" autocomplete="off" placeholder="Jam Masuk">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_jam_pulang">Jam Pulang</label>
                  <input type="text" name="edit_jam_pulang" class="form-control" id="edit_jam_pulang" autocomplete="off" placeholder="Jam Pulang">
                </div>
              </div>
            </div>
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

  <div class="modal fade" id="modal_detail_absen">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="detail_judul_absen"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="div_detail_absen">
            <table id="detail_data_absensi_table" style="width: 100%;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Karyawan</th>
                  <th>Jam Masuk</th>
                  <th>Jam Pulang</th>
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
    $('#filter_tanggal').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $('#tanggal_absensi').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#jam_masuk').flatpickr({
      allowInput: true,
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      disableMobile: true
    });

    $('#jam_pulang').flatpickr({
      allowInput: true,
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      disableMobile: true
    });

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    let key = "{{ env('MIX_APP_KEY') }}";
    var target = $('.nav-tabs a.nav-link.active').attr("href");

    var edit_jam_masuk = $('#edit_jam_masuk').flatpickr({
      allowInput: true,
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      disableMobile: true
    });

    var edit_jam_pulang = $('#edit_jam_pulang').flatpickr({
      allowInput: true,
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      disableMobile: true
    });

    var table = $('#data_absensi_table').DataTable({
      processing: true,
      serverSide: true,
      responsive: {
        details: {
          type: 'column'
        }
      },
      lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
      ajax: {
        url:'{{ url("absensi/table") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
      },
      order: [[1, 'desc']],
      dom: 'lBfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      createdRow: function (row, data, dataIndex) {
        $('td', row).attr('data-toggle', 'modal');
        $('td', row).attr('data-target', '#modal_detail_absen');
        $('td', row).eq(0).removeAttr('data-toggle');
        $('td', row).eq(0).removeAttr('data-target');
      },
      columns: [
      {
        data:'DT_RowIndex',
        name:'DT_RowIndex',
        width: '5%',
        className:'dt-center'
      },
      {
        data:'tanggal_absensi',
        name:'tanggal_absensi',
        className:'dt-center'
      },
      {
        data:'jumlah_karyawan',
        name:'jumlah_karyawan',
        className:'dt-center'
      },
      ]
    });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#data_absensi'){
        $('#data_absensi_table').DataTable().destroy();
        load_data_absensi();
      }else if(target == '#data_absensi_tidak_lengkap'){
        $('#data_absensi_tidak_lengkap_table').DataTable().destroy();
        load_data_absensi_tidak_lengkap();
      }
    });

    function load_data_absensi(from_date = '', to_date = '')
    {
      table = $('#data_absensi_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("absensi/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1, 'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
          $('td', row).attr('data-toggle', 'modal');
          $('td', row).attr('data-target', '#modal_detail_absen');
          $('td', row).eq(0).removeAttr('data-toggle');
          $('td', row).eq(0).removeAttr('data-target');
        },
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
          data:'tanggal_absensi',
          name:'tanggal_absensi',
          className:'dt-center'
        },
        {
          data:'jumlah_karyawan',
          name:'jumlah_karyawan',
          className:'dt-center'
        },
        ]
      });
    }

    function load_data_absensi_tidak_lengkap()
    {
      table = $('#data_absensi_tidak_lengkap_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("absensi/tidak_lengkap/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1, 'asc']],
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
          data:'tanggal_absensi',
          name:'tanggal_absensi',
          width:'15%',
          className:'dt-center'
        },
        {
          data:'karyawan',
          name:'karyawan',
          className:'dt-center'
        },
        {
          data:'jam_masuk',
          name:'jam_masuk',
          className:'dt-center',
          width:'15%',
          defaultContent:'---'
        },
        {
          data:'jam_pulang',
          name:'jam_pulang',
          className:'dt-center',
          width:'15%',
          defaultContent:'---'
        },
        {
          data:'action',
          name:'action',
          width:'10%',
          className:'dt-center'
        },
        ]
      });
    }

    function load_detail_absen(tanggal = '')
    {
      $('#detail_data_absensi_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("absensi/detail/table") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1, 'asc']],
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
          data:'karyawan',
          name:'karyawan',
          className:'dt-center'
        },
        {
          data:'jam_masuk',
          name:'jam_masuk',
          className:'dt-center'
        },
        {
          data:'jam_pulang',
          name:'jam_pulang',
          className:'dt-center',
          defaultContent: '---'
        },
        ]
      });
    }

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

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if(target == '#data_absensi'){
          $('#data_absensi_table').DataTable().destroy();
          load_data_absen(from_date, to_date);
        }
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(target == '#data_absensi'){
        $('#data_absensi_table').DataTable().destroy();
        load_data_absen();
      }
    });

    $('body').on('click', '#edit-data', function () {
      var id = $(this).data("id");
      var url = "{{ url('absensi/tidak_lengkap/view/id_data') }}";
      url = url.replace('id_data', enc(id.toString()));
      $('#edit_id').val('');
      $('#edit_nik').val('');
      $('#edit_karyawan').val('');
      $('#edit_tanggal').val('');
      $('#edit_jam_masuk').val('');
      $('#edit_jam_pulang').val('');
      $.get(url, function (data) {
        $('#edit_id').val(data.id);
        $('#edit_nik').val(data.nik);
        $('#edit_karyawan').val(data.karyawan);
        $('#edit_tanggal').val(data.tanggal_absensi);
        edit_jam_masuk.setDate(data.jam_masuk);
        edit_jam_pulang.setDate(data.jam_pulang);
      })
    });

    $('#data_absensi_table').on( 'click', 'tbody tr', function () {
      var absen = table.row(this).data();
      var tanggal = absen['tanggal_absensi'];

      document.getElementById("detail_judul_absen").innerHTML = "Absensi Tanggal " + tanggal;
      $('#detail_data_absensi_table').DataTable().destroy();
      load_detail_absen(tanggal);
    });

    $('#input_manual_form').validate({
      rules: {
        nik: {
          required: true,
        },
        tanggal_absensi: {
          required: true,
        },
        jam_masuk: {
          required: true,
        },
        jam_pulang: {
          required: true,
        },
      },
      messages: {
        nik: {
          required: "NIK harus diisi",
        },
        tanggal_absensi: {
          required: "Tanggal Absensi harus diisi",
        },
        jam_masuk: {
          required: "Jam Masuk harus diisi",
        },
        jam_pulang: {
          required: "Jam Keluar harus diisi",
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
        var myform = document.getElementById("input_manual_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('absensi/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_manual_form').trigger("reset");
            var oTable = $('#data_absensi_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_input_manual").modal('hide');
            $("#modal_input_manual").trigger('click');
            alert("Data Successfully Added");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#edit_form').validate({
      rules: {
        edit_jam_masuk: {
          required: true,
        },
        edit_jam_pulang: {
          required: true,
        },
      },
      messages: {
        edit_jam_masuk: {
          required: "Jam Masuk harus diisi",
        },
        edit_jam_pulang: {
          required: "Jam Keluar harus diisi",
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
          url:"{{ url('absensi/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            console.log(data);
            $('#edit_form').trigger("reset");
            var oTable = $('#data_absensi_tidak_lengkap_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_edit_data").modal('hide');
            $("#modal_edit_data").trigger('click');
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#upload-form').validate({
      rules: {
        upload_excel: {
          required: true,
          extension: "xlsx",
        },
        
      },
      messages: {
        upload_excel: {
          required: "Upload Excel Harus Diisi",
          extension: "Format File Tidak Didukung. Gunakan Format XLSX",
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
      }
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('.nik').select2({
      placeholder: 'Karyawan',
      allowClear: true,
      ajax: {
        url: '/dropdown_karyawan',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.nama_karyawan,
                id: item.nomor_karyawan
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
  $(".nik").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Karyawan Disini...");
  });
  $(".nik").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>

@endsection