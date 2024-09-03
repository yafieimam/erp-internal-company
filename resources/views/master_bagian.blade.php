@extends('layouts.app_admin')

@section('title')
<title>BAGIAN - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Data Bagian</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item">Bagian</li>
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
          <table id="data_bagian_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Company</th>
                <th>Unit</th>
                <th>Nama</th>
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
              <div class="col-12">
                <div class="form-group">
                  <label for="nama">Nama Bagian</label>
                  <input class="form-control" type="text" name="nama" id="nama" placeholder="Nama Bagian" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="kode">Kode Bagian</label>
                  <input class="form-control" type="text" name="kode" id="kode" placeholder="Kode Bagian" />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="company">Perusahaan</label>
                  <select id="company" name="company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="unit">Unit</label>
                  <select id="unit" name="unit" class="form-control" style="width: 100%;">
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
                  <td>Perusahaan</td>
                  <td>:</td>
                  <td id="td_company"></td>
                </tr>
                <tr>
                  <td>Unit</td>
                  <td>:</td>
                  <td id="td_unit"></td>
                </tr>
                <tr>
                  <td>Nama</td>
                  <td>:</td>
                  <td id="td_nama"></td>
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
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_nama">Nama Bagian</label>
                  <input class="form-control" type="text" name="edit_nama" id="edit_nama" placeholder="Nama Bagian" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_kode">Kode Bagian</label>
                  <input class="form-control" type="hidden" name="edit_kode_lama" id="edit_kode_lama" />
                  <input class="form-control" type="text" name="edit_kode" id="edit_kode" placeholder="Kode Bagian" />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_company">Perusahaan</label>
                  <select id="edit_company" name="edit_company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_unit">Unit</label>
                  <select id="edit_unit" name="edit_unit" class="form-control" style="width: 100%;">
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

    var table = $('#data_bagian_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_bagian/table") }}',
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
           data:'company',
           name:'company'
         },
         {
           data:'unit',
           name:'unit'
         },
         {
           data:'nama',
           name:'nama'
         },
         {
           data:'action',
           name:'action'
         }
       ]
     });

    function load_data_bagian()
    {
      table = $('#data_bagian_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_bagian/table") }}',
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
           data:'company',
           name:'company'
         },
         {
           data:'unit',
           name:'unit'
         },
         {
           data:'nama',
           name:'nama'
         },
         {
           data:'action',
           name:'action'
         }
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

    $('body').on('click', '#btn_input_data', function () {
      $('#unit').attr("disabled","disabled");

      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })
      $("#company").change(function() {
        var val = $(this).val();

        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(val.toString()));
        $.get(url_unit, function (data) {
          if(data.length == 0){
            $('#unit').children().remove().end();
            $('#unit').attr("disabled","disabled");
          }else{
            $('#unit').removeAttr('disabled');
            $('#unit').children().remove().end().append('<option value="" selected>Pilih Unit</option>');
            $.each(data, function(k, v) {
              $('#unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
            });
          }
        })
      });
    });

    $('body').on('click', '#view-data', function () {
      var kode = $(this).data("id");
      var url = "{{ url('master_bagian/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#td_kode').html('');
      $('#td_nama').html('');
      $('#td_company').html('');
      $('#td_unit').html('');
      $('#td_deskripsi').html('');
      $.get(url, function (data) {
        $('#td_kode').html(data.kode);
        $('#td_nama').html(data.nama);
        $('#td_company').html(data.company);
        $('#td_unit').html(data.unit);
        if(data.description == null || data.description == ''){
          $("#td_deskripsi").html('---');
        }else{
          $("#td_deskripsi").html(data.description);
        }
      })
    });

    $("#edit_company").change(function(e) {
      e.stopImmediatePropagation();
      var val = $(this).val();
      var url_unit = "{{ url('get_unit/company') }}";
      url_unit = url_unit.replace('company', enc(val.toString()));
      $.get(url_unit, function (data_unit) {
        if(data_unit.length == 0){
          $('#edit_unit').children().remove().end();
          $('#edit_unit').attr("disabled","disabled");
        }else{
          $('#edit_unit').removeAttr('disabled');
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
        }
      })
    });

    $('body').on('click', '#edit-data', function () {
      var kode = $(this).data("id");
      $('#edit_unit').attr("disabled","disabled");

      var url_company = "{{ url('get_company') }}";
      $.get(url_company, function (data) {
        $('#edit_company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#edit_company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })
      var url = "{{ url('master_bagian/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#edit_kode_lama').val('');
      $('#edit_kode').val('');
      $('#edit_nama').val('');
      $('#edit_description').html('');
      // $('#edit_company').val('').trigger('change');
      // $('#edit_unit').val('').trigger('change');
      $.get(url, function (data) {
        $('#edit_kode_lama').val(data.kode);
        $('#edit_kode').val(data.kode);
        $('#edit_nama').val(data.nama);
        $('#edit_company').val(data.kode_perusahaan);
        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(data.kode_perusahaan.toString()));
        $.get(url_unit, function (data_unit) {
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
          $('#edit_unit').removeAttr('disabled');
          $('#edit_unit').val(data.kode_unit).trigger('change');
        })
        $("#edit_description").html(data.description);
      })
    });

    $('body').on('click', '#delete-data', function () {
      var kode = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('master_bagian/delete') }}",
          data: { 'kode' : kode },
          success: function (data) {
            var oTable = $('#data_bagian_table').dataTable();
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
        company: {
          required: true,
        },
      },
      messages: {
        kode: {
          required: "Kode Bagian Harus Diisi",
        },
        nama: {
          required: "Nama Bagian Harus Diisi",
        },
        company: {
          required: "Perusahaan Harus Diisi",
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
          url:"{{ url('master_bagian/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_bagian_table').dataTable();
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
        edit_company: {
          required: true,
        },
      },
      messages: {
        edit_kode: {
          required: "Kode Bagian Harus Diisi",
        },
        edit_nama: {
          required: "Nama Bagian Harus Diisi",
        },
        edit_company: {
          required: "Perusahaan Harus Diisi",
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
          url:"{{ url('master_bagian/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_bagian_table').dataTable();
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