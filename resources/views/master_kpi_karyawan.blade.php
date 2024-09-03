@extends('layouts.app_admin')

@section('title')
<title>KPI KARYAWAN - PT. DWI SELO GIRI MAS</title>
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
  #btn-edit, #btn-new  {
    right: 20%;
    position: absolute;
    top: 40px;
    width: 60%;
    height: 40px;
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
    #btn-edit, #btn-new  {
      position: unset;
      width: 100%;
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
          <h1 class="m-0 text-dark">Data KPI Karyawan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item">KPI Karyawan</li>
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
          <table id="data_kpi_karyawan_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Perusahaan</th>
                <th>Unit</th>
                <th>Bagian</th>
                <th>Karyawan</th>
                <th>Periode</th>
                <th width="13%"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="input_form" id="input_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="company">Perusahaan</label>
                  <select id="company" name="company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="unit">Unit</label>
                  <select id="unit" name="unit" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="bagian">Bagian</label>
                  <select id="bagian" name="bagian" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="karyawan">Karyawan</label>
                  <select id="karyawan" name="karyawan" class="form-control select2 karyawan" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="periode">Periode</label>
                  <input class="form-control" type="hidden" name="kode_kpi" id="kode_kpi" />
                  <select id="periode" name="periode" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Periode</option>
                    <option value="15">15 Hari</option>
                    <option value="30">30 Hari</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="periode_kpi">Range Periode KPI</label>
                  <input class="form-control" type="text" name="periode_kpi" id="periode_kpi" placeholder="Range Periode KPI" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table id="data_kpi_karyawan_detail_table" style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                      <tr>
                        <th>Indikator</th>
                        <th style="width: 10%;">Persen (%)</th>
                        <th>Deskripsi</th>
                        <th style="width: 10%;">Pengali</th>
                        <th style="width: 10%;">Hari Kerja</th>
                        <th style="width: 10%;">Penilaian</th>
                        <th style="width: 10%;">Total</th>
                      </tr>
                      <tr>
                        <td colspan="7" id="td_empty" style="vertical-align: top; text-align: center;">Tidak Ada Data, Masukkan Parameter KPI Terlebih Dahulu</td>
                      </tr>
                    </table>
                  </div>
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
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 lihat-table">
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td style="border: none;">Kode</td>
                  <td style="border: none;">:</td>
                  <td id="td_kode" style="border: none;"></td>
                  <td style="border: none;">Perusahaan</td>
                  <td style="border: none;">:</td> 
                  <td id="td_company" style="border: none;"></td>
                </tr>
                <tr>
                  <td style="border: none;">Unit</td>
                  <td style="border: none;">:</td>
                  <td id="td_unit" style="border: none;"></td>
                  <td style="border: none;">Bagian</td>
                  <td style="border: none;">:</td>
                  <td id="td_bagian" style="border: none;"></td>
                </tr>
                <tr>
                  <td style="border: none;">Karyawan</td>
                  <td style="border: none;">:</td>
                  <td id="td_karyawan" style="border: none;"></td>
                  <td style="border: none;">Periode</td>
                  <td style="border: none;">:</td>
                  <td id="td_periode" style="border: none;"></td>
                </tr>
                <tr>
                  <td style="border: none;">Periode KPI</td>
                  <td style="border: none;">:</td>
                  <td id="td_periode_kpi" colspan="4" style="border: none;"></td>
                </tr>
              </table>

              <table class="table table-bordered" id="lihat-table-detail" style="width: 100%; font-size: 14px;">
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
    <div class="modal-dialog modal-xl">
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
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_company">Perusahaan</label>
                  <select id="edit_company" name="edit_company" class="form-control" style="width: 100%;" disabled>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_unit">Unit</label>
                  <select id="edit_unit" name="edit_unit" class="form-control" style="width: 100%;" disabled>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_bagian">Bagian</label>
                  <select id="edit_bagian" name="edit_bagian" class="form-control" style="width: 100%;" disabled>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_karyawan">Karyawan</label>
                  <select id="edit_karyawan" name="edit_karyawan" class="form-control select2 edit_karyawan" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_periode">Periode</label>
                  <input class="form-control" type="hidden" name="edit_kode" id="edit_kode" />
                  <select id="edit_periode" name="edit_periode" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Periode</option>
                    <option value="15">15 Hari</option>
                    <option value="30">30 Hari</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_periode_kpi">Range Periode KPI</label>
                  <input class="form-control" type="text" name="edit_periode_kpi" id="edit_periode_kpi" placeholder="Range Periode KPI" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <table id="data_edit_kpi_karyawan_detail_table" style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                      <tr>
                        <th>Indikator</th>
                        <th style="width: 10%;">Persen (%)</th>
                        <th>Deskripsi</th>
                        <th style="width: 10%;">Pengali</th>
                        <th style="width: 10%;">Hari Kerja</th>
                        <th style="width: 10%;">Penilaian</th>
                        <th style="width: 10%;">Total</th>
                      </tr>
                    </table>
                  </div>
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
  $(function () {
    $('#periode_kpi').daterangepicker({
      autoUpdateInput: false,
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $('#edit_periode_kpi').daterangepicker({
      autoUpdateInput: false,
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $('#periode_kpi').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#periode_kpi').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });

    $('#edit_periode_kpi').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#edit_periode_kpi').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var c_data = 0;
    var table = $('#data_kpi_karyawan_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_kpi_karyawan/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'company',
           name:'company'
         },
         {
           data:'unit',
           name:'unit'
         },
         {
           data:'bagian',
           name:'bagian'
         },
         {
           data:'karyawan',
           name:'karyawan'
         },
         {
           data:'periode',
           name:'periode'
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

    function load_data_kpi_karyawan()
    {
      table = $('#data_kpi_karyawan_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_kpi_karyawan/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'company',
           name:'company'
         },
         {
           data:'unit',
           name:'unit'
         },
         {
           data:'bagian',
           name:'bagian'
         },
         {
           data:'karyawan',
           name:'karyawan'
         },
         {
           data:'periode',
           name:'periode'
         },
         {
           data:'action',
           name:'action'
         }
       ]
     });
    }

    $('body').on('click', '#btn_input_data', function () {
      $('#unit').attr("disabled","disabled");
      $('#bagian').attr("disabled","disabled");

      var company = $('#company').val();
      var unit = $('#unit').val();
      var bagian = $('#bagian').val();

      for(var i = 1; i <= c_data; i++){
        $('#row_data_kpi'+i).remove();
      }

      $('#td_empty').show();

      c_data = 1;

      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      $("#company").change(function(e) {
        e.stopImmediatePropagation();

        var val = $(this).val();
        unit = $('#unit').val();
        bagian = $('#bagian').val();

        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(val.toString()));
        $.get(url_unit, function (data) {
          if(data.length == 0){
            $('#unit').children().remove().end();
            $('#unit').attr("disabled","disabled");
            $('#bagian').children().remove().end();
            $('#bagian').attr("disabled","disabled");
          }else{
            $('#unit').removeAttr('disabled');
            $('#unit').children().remove().end().append('<option value="" selected>Pilih Unit</option>');
            $.each(data, function(k, v) {
              $('#unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
            });
          }
        })

        if(val == null || val == ''){
          $('#td_empty').show();
        }

        for(var i = 1; i <= c_data; i++){
          $('#row_data_kpi'+i).remove();
        }

        var url_detail = "{{ url('get_parameter_kpi/company/unit/bagian') }}";
        url_detail = url_detail.replace("company", enc(val.toString()));
        url_detail = url_detail.replace("unit", enc(unit.toString()));
        url_detail = url_detail.replace("bagian", enc(bagian.toString()));
        $.get(url_detail, function (data) {
          $('#kode_kpi').val(data[0].kode_kpi);
          c_data = data.length;

          if(data.length != 0){
            $('#td_empty').hide();
          }

          // console.log(c_data);
          for(let i = 1; i <= c_data; i++){
            $('#data_kpi_karyawan_detail_table').append('<tr id="row_data_kpi'+i+'"><td>'+data[i-1].indikator+'</td><td>'+data[i-1].persentase+'%<input type="hidden" name="persentase[]" id="persentase'+i+'" class="form-control persentase_list" value="'+data[i-1].persentase+'"/></td><td>'+data[i-1].description+'</td><td><input type="text" name="pengali[]" id="pengali'+i+'" placeholder="Pengali" class="form-control pengali_list" /><input type="hidden" name="kode_parameter_kpi[]" id="kode_parameter_kpi'+i+'" class="form-control kode_parameter_kpi_list" value="'+data[i-1].kode+'"/></td><td><input type="text" name="hari_kerja[]" id="hari_kerja'+i+'" placeholder="Hari" class="form-control hari_kerja_list" /></td><td><input type="text" name="penilaian[]" id="penilaian'+i+'" placeholder="Nilai" class="form-control penilaian_list" /></td><td><input type="text" name="total_bonus[]" id="total_bonus'+i+'" placeholder="Total" class="form-control total_bonus_list" readonly/></td></tr>');

            $('#pengali'+i).on('keyup', function(){
              if(($("#penilaian"+i).val() != null || $("#penilaian"+i).val() != '') || ($("#hari_kerja"+i).val() != null || $("#hari_kerja"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });

            $('#penilaian'+i).on('keyup', function(){
              if(($("#pengali"+i).val() != null || $("#pengali"+i).val() != '') || ($("#hari_kerja"+i).val() != null || $("#hari_kerja"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });

            $('#hari_kerja'+i).on('keyup', function(){
              if(($("#penilaian"+i).val() != null || $("#penilaian"+i).val() != '') || ($("#pengali"+i).val() != null || $("#pengali"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });
          }
        })

      });

      $("#unit").change(function(e) {
        e.stopImmediatePropagation();

        var val = $(this).val();
        company = $('#company').val();
        bagian = $('#bagian').val();

        var url_bagian = "{{ url('get_bagian/company/unit') }}";
        url_bagian = url_bagian.replace('company', enc(company.toString()));
        url_bagian = url_bagian.replace('unit', enc(val.toString()));
        $.get(url_bagian, function (data) {
          if(data.length == 0){
            $('#bagian').children().remove().end();
            $('#bagian').attr("disabled","disabled");
          }else{
            $('#bagian').removeAttr('disabled');
            $('#bagian').children().remove().end().append('<option value="" selected>Pilih Bagian</option>');
            $.each(data, function(k, v) {
              $('#bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
            });
          }
        })

        if(val == null || val == ''){
          $('#td_empty').show();
        }

        for(var i = 1; i <= c_data; i++){
          $('#row_data_kpi'+i).remove();
        }

        var url_detail = "{{ url('get_parameter_kpi/company/unit/bagian') }}";
        url_detail = url_detail.replace("company", enc(company.toString()));
        url_detail = url_detail.replace("unit", enc(val.toString()));
        url_detail = url_detail.replace("bagian", enc(bagian.toString()));
        $.get(url_detail, function (data) {
          $('#kode_kpi').val(data[0].kode_kpi);
          c_data = data.length;

          if(data.length != 0){
            $('#td_empty').hide();
          }

          // console.log(c_data);
          for(let i = 1; i <= c_data; i++){
            $('#data_kpi_karyawan_detail_table').append('<tr id="row_data_kpi'+i+'"><td>'+data[i-1].indikator+'</td><td>'+data[i-1].persentase+'%<input type="hidden" name="persentase[]" id="persentase'+i+'" class="form-control persentase_list" value="'+data[i-1].persentase+'"/></td><td>'+data[i-1].description+'</td><td><input type="text" name="pengali[]" id="pengali'+i+'" placeholder="Pengali" class="form-control pengali_list" /><input type="hidden" name="kode_parameter_kpi[]" id="kode_parameter_kpi'+i+'" class="form-control kode_parameter_kpi_list" value="'+data[i-1].kode+'"/></td><td><input type="text" name="hari_kerja[]" id="hari_kerja'+i+'" placeholder="Hari" class="form-control hari_kerja_list" /></td><td><input type="text" name="penilaian[]" id="penilaian'+i+'" placeholder="Nilai" class="form-control penilaian_list" /></td><td><input type="text" name="total_bonus[]" id="total_bonus'+i+'" placeholder="Total" class="form-control total_bonus_list" readonly/></td></tr>');

            $('#pengali'+i).on('keyup', function(){
              if(($("#penilaian"+i).val() != null || $("#penilaian"+i).val() != '') || ($("#hari_kerja"+i).val() != null || $("#hari_kerja"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });

            $('#penilaian'+i).on('keyup', function(){
              if(($("#pengali"+i).val() != null || $("#pengali"+i).val() != '') || ($("#hari_kerja"+i).val() != null || $("#hari_kerja"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });

            $('#hari_kerja'+i).on('keyup', function(){
              if(($("#penilaian"+i).val() != null || $("#penilaian"+i).val() != '') || ($("#pengali"+i).val() != null || $("#pengali"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });
          }
        })
      });

      $("#bagian").change(function(e) {
        e.stopImmediatePropagation();

        var val = $(this).val();
        unit = $('#unit').val();
        company = $('#company').val();

        if(val == null || val == ''){
          $('#td_empty').show();
        }

        for(var i = 1; i <= c_data; i++){
          $('#row_data_kpi'+i).remove();
        }

        var url_detail = "{{ url('get_parameter_kpi/company/unit/bagian') }}";
        url_detail = url_detail.replace("company", enc(company.toString()));
        url_detail = url_detail.replace("unit", enc(unit.toString()));
        url_detail = url_detail.replace("bagian", enc(val.toString()));
        $.get(url_detail, function (data) {
          console.log(data);
          $('#kode_kpi').val(data[0].kode_kpi);
          c_data = data.length;

          if(data.length != 0){
            $('#td_empty').hide();
          }
          // console.log(c_data);
          for(let i = 1; i <= c_data; i++){
            $('#data_kpi_karyawan_detail_table').append('<tr id="row_data_kpi'+i+'"><td>'+data[i-1].indikator+'</td><td>'+data[i-1].persentase+'%<input type="hidden" name="persentase[]" id="persentase'+i+'" class="form-control persentase_list" value="'+data[i-1].persentase+'"/></td><td>'+data[i-1].description+'</td><td><input type="text" name="pengali[]" id="pengali'+i+'" placeholder="Pengali" class="form-control pengali_list" /><input type="hidden" name="kode_parameter_kpi[]" id="kode_parameter_kpi'+i+'" class="form-control kode_parameter_kpi_list" value="'+data[i-1].kode+'"/></td><td><input type="text" name="hari_kerja[]" id="hari_kerja'+i+'" placeholder="Hari" class="form-control hari_kerja_list" /></td><td><input type="text" name="penilaian[]" id="penilaian'+i+'" placeholder="Nilai" class="form-control penilaian_list" /></td><td><input type="text" name="total_bonus[]" id="total_bonus'+i+'" placeholder="Total" class="form-control total_bonus_list" readonly/></td></tr>');

            $('#pengali'+i).on('keyup', function(){
              if(($("#penilaian"+i).val() != null || $("#penilaian"+i).val() != '') || ($("#hari_kerja"+i).val() != null || $("#hari_kerja"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });

            $('#penilaian'+i).on('keyup', function(){
              if(($("#pengali"+i).val() != null || $("#pengali"+i).val() != '') || ($("#hari_kerja"+i).val() != null || $("#hari_kerja"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });

            $('#hari_kerja'+i).on('keyup', function(){
              if(($("#penilaian"+i).val() != null || $("#penilaian"+i).val() != '') || ($("#pengali"+i).val() != null || $("#pengali"+i).val() != '')){
                var a = $("#persentase"+i).val();
                var b = $("#pengali"+i).val();
                var c = $("#penilaian"+i).val();
                var total = a * b * c;
                $("#total_bonus"+i).val(total);
              }
            });
          }
        })
      });
    });

    $('body').on('click', '#view-data', function () {
      var kode = $(this).data("id");
      var url = "{{ url('master_kpi_karyawan/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#td_kode').html('');
      $('#td_company').html('');
      $('#td_unit').html('');
      $('#td_bagian').html('');
      $('#td_karyawan').html('');
      $('#td_periode').html('');
      $('#lihat-table-detail').html('');
      $.get(url, function (data) {
        $('#td_kode').html(data.kode);
        $('#td_company').html(data.company);
        $('#td_unit').html(data.unit);
        $('#td_bagian').html(data.bagian);
        $('#td_karyawan').html(data.karyawan);
        $('#td_periode').html(data.periode);
        $('#td_periode_kpi').html(data.from_periode_kpi + ' - ' + data.to_periode_kpi);
        var url_detail = "{{ url('master_kpi_karyawan/detail/view/id') }}";
        url_detail = url_detail.replace('id', enc(kode.toString()));
        $.get(url_detail, function (data_detail) {
          $('#lihat-table-detail').append(
              '<tr>'+
              '<th>Indikator</th>'+
              '<th style="width: 10%;">Persentase</th>'+
              '<th>Description</th>'+
              '<th style="width: 10%;">Pengali</th>'+
              '<th style="width: 10%;">Hari Kerja</th>'+
              '<th style="width: 10%;">Penilaian</th>'+
              '<th style="width: 10%;">Total Bonus</th>'+
              '</tr>'
            );
          $.each(data_detail, function(k, v) {
            $('#lihat-table-detail').append(
              '<tr>'+
              '<td>'+ v.indikator +'</td>'+
              '<td>'+ v.persentase +'</td>'+
              '<td>'+ v.description +'</td>'+
              '<td>'+ v.pengali +'</td>'+
              '<td>'+ v.hari_kerja +'</td>'+
              '<td>'+ v.penilaian +'</td>'+
              '<td>'+ v.total_bonus +'</td>'+
              '</tr>'
            );
          });
        })
      })
    });

    $('body').on('click', '#edit-data', function () {
      var kode = $(this).data("id");

      for(var i = 1; i <= c_data; i++){
        $('#row_edit_data_kpi'+i).remove();
      }

      c_data = 1;

      var url_company = "{{ url('get_company') }}";
      $.get(url_company, function (data) {
        $('#edit_company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#edit_company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })
      var url = "{{ url('master_kpi_karyawan/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#edit_kode').val('');
      $('#edit_periode').val('');
      // $('#edit_company').val('').trigger('change');
      // $('#edit_unit').val('').trigger('change');
      // $('#edit_bagian').val('').trigger('change');
      $('#edit_karyawan').val('').trigger('change');
      $.get(url, function (data) {
        $('#edit_kode').val(data.kode);
        $('#edit_periode').val(data.periode).trigger('change');
        $('#edit_company').val(data.kode_perusahaan);
        $('#edit_periode_kpi').val(data.from_periode_kpi + ' - ' + data.to_periode_kpi);
        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(data.kode_perusahaan.toString()));
        $.get(url_unit, function (data_unit) {
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
          $('#edit_unit').val(data.kode_unit);
        })
        var url_bagian = "{{ url('get_bagian/company/unit') }}";
        url_bagian = url_bagian.replace('company', enc(data.kode_perusahaan.toString()));
        url_bagian = url_bagian.replace('unit', enc(data.kode_unit.toString()));
        $.get(url_bagian, function (data_bag) {
          $('#edit_bagian').children().remove().end().append('<option value="">Pilih Bagian</option>');
          $.each(data_bag, function(k, v) {
            $('#edit_bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
          });
          $('#edit_bagian').val(data.kode_bagian);
        })
        $("#edit_karyawan").append('<option value="' + data.nomor_karyawan + '">' + data.karyawan + '</option>');
        $("#edit_karyawan").val(data.nomor_karyawan);
        $("#edit_karyawan").trigger('change');
      })

      var url_detail = "{{ url('master_kpi_karyawan/detail/view/id') }}";
      url_detail = url_detail.replace('id', enc(kode.toString()));
      $.get(url_detail, function (data) {
        c_data = data.length;

        for(let i = 1; i <= c_data; i++){
          $('#data_edit_kpi_karyawan_detail_table').append('<tr id="row_edit_data_kpi'+i+'"><td>'+data[i-1].indikator+'</td><td>'+data[i-1].persentase+'%<input type="hidden" name="edit_persentase[]" id="edit_persentase'+i+'" class="form-control edit_persentase_list" value="'+data[i-1].persentase+'"/></td><td>'+data[i-1].description+'</td><td><input type="text" name="edit_pengali[]" id="edit_pengali'+i+'" placeholder="Pengali" class="form-control edit_pengali_list" value="'+data[i-1].pengali+'" /><input type="hidden" name="edit_kode_parameter_kpi[]" id="edit_kode_parameter_kpi'+i+'" class="form-control edit_kode_parameter_kpi_list" value="'+data[i-1].kode+'"/></td><td><input type="text" name="edit_hari_kerja[]" id="edit_hari_kerja'+i+'" placeholder="Hari" class="form-control edit_hari_kerja_list" value="'+data[i-1].hari_kerja+'" /></td><td><input type="text" name="edit_penilaian[]" id="edit_penilaian'+i+'" placeholder="Nilai" class="form-control edit_penilaian_list" value="'+data[i-1].penilaian+'" /></td><td><input type="text" name="edit_total_bonus[]" id="edit_total_bonus'+i+'" placeholder="Total" class="form-control edit_total_bonus_list" value="'+data[i-1].total_bonus+'" readonly/></td></tr>');

          $('#edit_pengali'+i).on('keyup', function(){
            if(($("#edit_penilaian"+i).val() != null || $("#edit_penilaian"+i).val() != '') || ($("#edit_hari_kerja"+i).val() != null || $("#edit_hari_kerja"+i).val() != '')){
              var a = $("#edit_persentase"+i).val();
              var b = $("#edit_pengali"+i).val();
              var c = $("#edit_penilaian"+i).val();
              var total = a * b * c;
              $("#edit_total_bonus"+i).val(total);
            }
          });

          $('#edit_penilaian'+i).on('keyup', function(){
            if(($("#edit_pengali"+i).val() != null || $("#edit_pengali"+i).val() != '') || ($("#edit_hari_kerja"+i).val() != null || $("#edit_hari_kerja"+i).val() != '')){
              var a = $("#edit_persentase"+i).val();
              var b = $("#edit_pengali"+i).val();
              var c = $("#edit_penilaian"+i).val();
              var total = a * b * c;
              $("#edit_total_bonus"+i).val(total);
            }
          });

          $('#edit_hari_kerja'+i).on('keyup', function(){
            if(($("#edit_penilaian"+i).val() != null || $("#edit_penilaian"+i).val() != '') || ($("#edit_pengali"+i).val() != null || $("#edit_pengali"+i).val() != '')){
              var a = $("#edit_persentase"+i).val();
              var b = $("#edit_pengali"+i).val();
              var c = $("#edit_penilaian"+i).val();
              var total = a * b * c;
              $("#edit_total_bonus"+i).val(total);
            }
          });

          $('body').on('click', '#btn-save-edit', function (e) {
            if(($("#edit_penilaian"+i).val() == null || $("#edit_penilaian"+i).val() == '')){
              alert('Data Penilaian Harus Diisi');
              e.preventDefault();
            }else if(($("#edit_pengali"+i).val() == null || $("#edit_pengali"+i).val() == '')){
              alert('Data Pengali Harus Diisi');
              e.preventDefault();
            }else if(($("#edit_hari_kerja"+i).val() == null || $("#edit_hari_kerja"+i).val() == '')){
              alert('Data Hari Kerja Harus Diisi');
              e.preventDefault();
            }
          });
        }
      })

    });

    $('body').on('click', '#delete-data', function () {
      var kode = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('master_kpi_karyawan/delete') }}",
          data: { 'kode' : kode },
          success: function (data) {
            var oTable = $('#data_kpi_karyawan_table').dataTable();
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
        company: {
          required: true,
        },
        bagian: {
          required: true,
        },
        karyawan: {
          required: true,
        },
        periode: {
          required: true,
        },
        periode_kpi: {
          required: true,
        },
      },
      messages: {
        edit_company: {
          required: "Perusahaan Harus Diisi",
        },
        bagian: {
          required: "Bagian Harus Diisi",
        },
        karyawan: {
          required: "Karyawan Harus Diisi",
        },
        periode: {
          required: "Periode Harus Diisi",
        },
        periode_kpi: {
          required: "Range Periode Harus Diisi",
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
          url:"{{ url('master_kpi_karyawan/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_kpi_karyawan_table').dataTable();
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
        edit_karyawan: {
          required: true,
        },
        edit_periode: {
          required: true,
        },
        edit_periode_kpi: {
          required: true,
        },
      },
      messages: {
        edit_karyawan: {
          required: "Karyawan Harus Diisi",
        },
        edit_periode: {
          required: "Periode Harus Diisi",
        },
        edit_periode_kpi: {
          required: "Range Periode Harus Diisi",
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
          url:"{{ url('master_kpi_karyawan/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_kpi_karyawan_table').dataTable();
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