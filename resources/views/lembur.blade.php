@extends('layouts.app_admin')

@section('title')
<title>LEMBUR - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Data Lembur</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Administrasi</li>
            <li class="breadcrumb-item">Lembur</li>
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
            <div class="col-4">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="data_lembur_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Karyawan</th>
                <th>Jumlah Jam</th>
                <th width="13%"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog">
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
                  <label for="nomor_surat_lembur">Nomor Surat Lembur</label>
                  <input type="text" class="form-control" name="nomor_surat_lembur" id="nomor_surat_lembur" autocomplete="off" placeholder="Nomor Surat Lembur">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="karyawan">Karyawan</label>
                  <select id="karyawan" name="karyawan" class="form-control select2 karyawan" style="width: 100%;">
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
                    <input type="text" class="form-control" name="tanggal" id="tanggal" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="jumlah_jam">Jumlah Jam</label>
                  <input type="text" class="form-control" name="jumlah_jam" id="jumlah_jam" autocomplete="off" placeholder="Jumlah Jam">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="keterangan">Keterangan</label>
                  <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
                </div>
              </div>
            </div>
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
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td>Nomor Surat Lembur</td>
                  <td>:</td>
                  <td id="td_nomor_surat_lembur"></td>
                </tr>
                <tr>
                  <td>Karyawan</td>
                  <td>:</td>
                  <td id="td_karyawan"></td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td>:</td>
                  <td id="td_tanggal"></td>
                </tr>
                <tr>
                  <td>Jumlah Jam</td>
                  <td>:</td>
                  <td id="td_jumlah_jam"></td>
                </tr>
                <tr>
                  <td>Keterangan</td>
                  <td>:</td>
                  <td id="td_keterangan"></td>
                </tr>
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
    <div class="modal-dialog">
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
                  <label for="edit_nomor_surat_lembur">Nomor Surat Lembur</label>
                  <input type="text" class="form-control" name="edit_nomor_surat_lembur" id="edit_nomor_surat_lembur" autocomplete="off" placeholder="Nomor Surat Lembur">
                  <input type="hidden" class="form-control" name="edit_id" id="edit_id">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_karyawan">Karyawan</label>
                  <select id="edit_karyawan" name="edit_karyawan" class="form-control select2 edit_karyawan" style="width: 100%;">
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
                    <input type="text" class="form-control" name="edit_tanggal" id="edit_tanggal" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_jumlah_jam">Jumlah Jam</label>
                  <input type="text" class="form-control" name="edit_jumlah_jam" id="edit_jumlah_jam" autocomplete="off" placeholder="Jumlah Jam">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_keterangan">Keterangan</label>
                  <textarea class="form-control" rows="3" name="edit_keterangan" id="edit_keterangan" placeholder="Keterangan"></textarea>
                </div>
              </div>
            </div>
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

    $('#tanggal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#data_lembur_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("lembur/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1, 'desc']],
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
           data:'tanggal',
           name:'tanggal',
           className:'dt-center'
         },
         {
           data:'karyawan',
           name:'karyawan',
           className:'dt-center'
         },
         {
           data:'jumlah_jam',
           name:'jumlah_jam',
           width:'15%',
           className:'dt-center',
           render: function (data, type, row)
           {
            return Math.round(data) + " Jam";
           }
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

    function load_data_lembur(from_date = '', to_date = '')
    {
      table = $('#data_lembur_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("lembur/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1, 'desc']],
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
           data:'tanggal',
           name:'tanggal',
           className:'dt-center'
         },
         {
           data:'karyawan',
           name:'karyawan',
           className:'dt-center'
         },
         {
           data:'jumlah_jam',
           name:'jumlah_jam',
           width:'15%',
           className:'dt-center',
           render: function (data, type, row)
           {
            return Math.round(data) + " Jam";
           }
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
        $('#data_lembur_table').DataTable().destroy();
        load_data_lembur(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#data_lembur_table').DataTable().destroy();
      load_data_lembur();
    });

    $('body').on('click', '#view-data', function () {
      var id = $(this).data("id");
      var url = "{{ url('lembur/view/id') }}";
      url = url.replace('id', enc(id.toString()));
      $('#td_nomor_surat_lembur').html('');
      $('#td_tanggal').html('');
      $('#td_karyawan').html('');
      $('#td_jumlah_jam').html('');
      $('#td_keterangan').html('');
      $.get(url, function (data) {
        $('#td_nomor_surat_lembur').html(data.nomor_surat_lembur);
        $('#td_tanggal').html(data.tanggal);
        $('#td_karyawan').html(data.karyawan);
        $('#td_jumlah_jam').html(Math.round(data.jumlah_jam) + ' Jam');
        if(data.keterangan == null || data.keterangan == ''){
          $('#td_keterangan').html("---");
        }else{
          $('#td_keterangan').html(data.keterangan);
        }
      })
    });

    $('body').on('click', '#edit-data', function () {
      var id = $(this).data("id");
      var url = "{{ url('lembur/view/id') }}";
      url = url.replace('id', enc(id.toString()));
      $('#edit_id').val(id);
      $('#edit_nomor_surat_lembur').val('');
      $('#edit_tanggal').val('');
      $('#edit_karyawan').val('').trigger('change');
      $('#edit_jumlah_jam').val('');
      $('#edit_keterangan').html('');
      $.get(url, function (data) {
        $('#edit_nomor_surat_lembur').val(data.nomor_surat_lembur);
        $('#edit_tanggal').val(data.tanggal);
        $('#edit_jumlah_jam').val(Math.round(data.jumlah_jam));
        $('#edit_keterangan').html(data.keterangan);
        $("#edit_karyawan").append('<option value="' + data.nomor_karyawan + '">' + data.karyawan + '</option>');
        $("#edit_karyawan").val(data.nomor_karyawan);
        $("#edit_karyawan").trigger('change');
      })
    });

    $('body').on('click', '#delete-data', function () {
      var id = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('lembur/delete') }}",
          data: { 'id' : id },
          success: function (data) {
            var oTable = $('#data_lembur_table').dataTable();
            oTable.fnDraw(false);
            alert("Data Deleted");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#input_form').validate({
      rules: {
        tanggal: {
          required: true,
        },
        nomor_surat_lembur: {
          required: true,
        },
        karyawan: {
          required: true,
        },
        jumlah_jam: {
          required: true,
        },
      },
      messages: {
        tanggal: {
          required: "Tanggal Harus Diisi",
        },
        nomor_surat_lembur: {
          required: "Nomor Surat Lembur Harus Diisi",
        },
        karyawan: {
          required: "Karyawan Harus Diisi",
        },
        jumlah_jam: {
          required: "Jumlah Jam Harus Diisi",
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
          url:"{{ url('lembur/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_lembur_table').dataTable();
            oTable.fnDraw(false);
            alert("Data Successfully Added");
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
        edit_tanggal: {
          required: true,
        },
        edit_nomor_surat_lembur: {
          required: true,
        },
        edit_karyawan: {
          required: true,
        },
        edit_jumlah_jam: {
          required: true,
        },
      },
      messages: {
        edit_tanggal: {
          required: "Tanggal Harus Diisi",
        },
        edit_nomor_surat_lembur: {
          required: "Nomor Surat Lembur Harus Diisi",
        },
        edit_karyawan: {
          required: "Karyawan Harus Diisi",
        },
        edit_jumlah_jam: {
          required: "Jumlah Jam Harus Diisi",
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
          url:"{{ url('lembur/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_lembur_table').dataTable();
            oTable.fnDraw(false);
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
  $(document).ready(function(){
    $('.karyawan').select2({
      dropdownParent: $('#modal_input_data .modal-content'),
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

    $('.edit_karyawan').select2({
      dropdownParent: $('#modal_edit_data .modal-content'),
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
  $(".karyawan").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Nama Karyawan...");
  });
  $(".karyawan").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
  $(".edit_karyawan").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Nama Karyawan...");
  });
  $(".edit_karyawan").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

@endsection