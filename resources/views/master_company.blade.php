@extends('layouts.app_admin')

@section('title')
<title>COMPANY - PT. DWI SELO GIRI MAS</title>
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
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
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
          <h1 class="m-0 text-dark">Data Company</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item">Company</li>
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
            <div class="col-4">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="data_company_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Bentuk</th>
                <th>Alamat</th>
                <th>Status</th>
                <th width="13%"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog modal-lg">
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
              <div class="col-6">
                <div class="form-group">
                  <label for="kode">Kode Perusahaan</label>
                  <input class="form-control" type="text" name="kode" id="kode" placeholder="Kode Perusahaan" />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="nama">Nama Perusahaan</label>
                  <input class="form-control" type="text" name="nama" id="nama" placeholder="Nama Perusahaan" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="bentuk_perusahaan">Bentuk Perusahaan</label>
                  <select id="bentuk_perusahaan" name="bentuk_perusahaan" class="form-control" style="width: 100%;">
                    <option value="PT" selected>PT</option>
                    <option value="CV">CV</option>
                    <option value="NV">NV</option>
                    <option value="Group">Group</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="alamat">Alamat Perusahaan</label>
                  <textarea class="form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat Perusahaan"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="status">Status Perusahaan</label>
                  <select id="status" name="status" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status</option>
                    <option value="A">Aktif</option>
                    <option value="NA">Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="description">Deskripsi</label>
                  <textarea class="form-control" rows="3" name="description" id="description" placeholder="Deskripsi"></textarea>
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
                  <td>Kode</td>
                  <td>:</td>
                  <td id="td_kode"></td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td>:</td>
                  <td id="td_nama"></td>
                </tr>
                <tr>
                  <td>Bentuk</td>
                  <td>:</td>
                  <td id="td_bentuk"></td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>:</td>
                  <td id="td_alamat"></td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>:</td>
                  <td id="td_status"></td>
                </tr>
                <tr>
                  <td>Deskripsi</td>
                  <td>:</td>
                  <td id="td_deskripsi"></td>
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
    <div class="modal-dialog modal-lg">
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
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_kode">Kode Perusahaan</label>
                  <input class="form-control" type="hidden" name="edit_kode_lama" id="edit_kode_lama" />
                  <input class="form-control" type="text" name="edit_kode" id="edit_kode" placeholder="Kode Perusahaan" />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_nama">Nama Perusahaan</label>
                  <input class="form-control" type="text" name="edit_nama" id="edit_nama" placeholder="Nama Perusahaan" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_bentuk_perusahaan">Bentuk Perusahaan</label>
                  <select id="edit_bentuk_perusahaan" name="edit_bentuk_perusahaan" class="form-control" style="width: 100%;">
                    <option value="PT" selected>PT</option>
                    <option value="CV">CV</option>
                    <option value="NV">NV</option>
                    <option value="Group">Group</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_alamat">Alamat Perusahaan</label>
                  <textarea class="form-control" rows="3" name="edit_alamat" id="edit_alamat" placeholder="Alamat Perusahaan"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_status">Status Perusahaan</label>
                  <select id="edit_status" name="edit_status" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status</option>
                    <option value="A">Aktif</option>
                    <option value="NA">Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_description">Deskripsi</label>
                  <textarea class="form-control" rows="3" name="edit_description" id="edit_description" placeholder="Deskripsi"></textarea>
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
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#data_company_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_company/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'kode',
           name:'kode'
         },
         {
           data:'nama',
           name:'nama'
         },
         {
           data:'bentuk_perusahaan',
           name:'bentuk_perusahaan'
         },
         {
           data:'alamat',
           name:'alamat'
         },
         {
           data:'status',
           name:'status'
         },
         {
           data:'action',
           name:'action'
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

    function load_data_company()
    {
      table = $('#data_company_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_company/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'kode',
           name:'kode'
         },
         {
           data:'nama',
           name:'nama'
         },
         {
           data:'bentuk_perusahaan',
           name:'bentuk_perusahaan'
         },
         {
           data:'alamat',
           name:'alamat'
         },
         {
           data:'status',
           name:'status'
         },
         {
           data:'action',
           name:'action'
         }
       ]
     });
    }

    $('body').on('click', '#view-data', function () {
      var kode = $(this).data("id");
      var url = "{{ url('master_company/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#td_kode').html('');
      $('#td_nama').html('');
      $('#td_bentuk').html('');
      $('#td_alamat').html('');
      $('#td_status').html('');
      $('#td_deskripsi').html('');
      $.get(url, function (data) {
        $('#td_kode').html(data.kode_perusahaan);
        $('#td_nama').html(data.nama_perusahaan);
        $('#td_bentuk').html(data.bentuk_perusahaan);
        $('#td_alamat').html(data.alamat_perusahaan);
        if(data.status_perusahaan == 'A'){
          $("#td_status").html('Aktif');
        }else{
          $("#td_status").html('Tidak Aktif');
        }
        if(data.description == null || data.description == ''){
          $("#td_deskripsi").html('---');
        }else{
          $("#td_deskripsi").html(data.description);
        }
      })
    });

    $('body').on('click', '#edit-data', function () {
      var kode = $(this).data("id");
      var url = "{{ url('master_company/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#edit_kode_lama').val('');
      $('#edit_kode').val('');
      $('#edit_nama').val('');
      $('#edit_bentuk_perusahaan').val('').trigger('change');
      $('#edit_alamat').html('');
      $('#edit_description').html('');
      $('#edit_status').val('').trigger('change');
      $.get(url, function (data) {
        $('#edit_kode_lama').val(data.kode_perusahaan);
        $('#edit_kode').val(data.kode_perusahaan);
        $('#edit_nama').val(data.nama_perusahaan);
        $('#edit_bentuk_perusahaan').val(data.bentuk_perusahaan).trigger('change');
        $('#edit_alamat').html(data.alamat_perusahaan);
        $('#edit_status').val(data.status_perusahaan).trigger('change');
        $("#edit_description").html(data.description);
      })
    });

    $('body').on('click', '#delete-data', function () {
      var kode = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('master_company/delete') }}",
          data: { 'kode' : kode },
          success: function (data) {
            var oTable = $('#data_company_table').dataTable();
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
        kode: {
          required: true,
        },
        nama: {
          required: true,
        },
        bentuk_perusahaan: {
          required: true,
        },
        alamat: {
          required: true,
        },
        status: {
          required: true,
        },
      },
      messages: {
        kode: {
          required: "Kode Perusahaan Harus Diisi",
        },
        nama: {
          required: "Nama Perusahaan Harus Diisi",
        },
        bentuk_perusahaan: {
          required: "Bentuk Perusahaan Harus Diisi",
        },
        alamat: {
          required: "Alamat Perusahaan Harus Diisi",
        },
        status: {
          required: "Status Perusahaan Harus Diisi",
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
          url:"{{ url('master_company/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_company_table').dataTable();
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
        edit_kode: {
          required: true,
        },
        edit_nama: {
          required: true,
        },
        edit_bentuk_perusahaan: {
          required: true,
        },
        edit_alamat: {
          required: true,
        },
        edit_status: {
          required: true,
        },
      },
      messages: {
        edit_kode: {
          required: "Kode Perusahaan Harus Diisi",
        },
        edit_nama: {
          required: "Nama Perusahaan Harus Diisi",
        },
        edit_bentuk_perusahaan: {
          required: "Bentuk Perusahaan Harus Diisi",
        },
        edit_alamat: {
          required: "Alamat Perusahaan Harus Diisi",
        },
        edit_status: {
          required: "Status Perusahaan Harus Diisi",
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
          url:"{{ url('master_company/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_company_table').dataTable();
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

@endsection