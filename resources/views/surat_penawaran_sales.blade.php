@extends('layouts.app_admin')

@section('title')
<title>SURAT PENAWARAN - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Surat Penawaran</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Surat Penawaran</li>
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
                  <input type="text" placeholder="Filter Date" class="form-control float-right" id="filter_tanggal">
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
          <table id="data_penawaran_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Company</th>
                <th>Customers</th>
                <th></th>
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
                  <label for="company">Perusahaan</label>
                  <select id="company" name="company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <button type="button" class="btn btn-primary" id="btn_new_leads" style="width: 100%;">New Leads</button>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <button type="button" class="btn btn-primary" id="btn_exist_leads" style="width: 100%;">Exist Leads</button>
                </div>
              </div>
            </div>
            <div class="row" id="select_leads" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label for="leads">Leads</label>
                  <select id="leads" name="leads" class="form-control select2 leads" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div id="new_leads" style="display: none;">
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label for="new_nama">Nama</label>
                    <input type="text" class="form-control" name="new_nama" id="new_nama" placeholder="Nama">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="new_city">Kota</label>
                    <select id="new_city" name="new_city" class="form-control select2 city" style="width: 100%;">
                    </select>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="new_alamat">Alamat</label>
                    <textarea class="form-control" rows="2" name="new_alamat" id="new_alamat" placeholder="Alamat"></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-3">
                  <div class="form-group">
                    <label for="new_telepon">Telepon</label>
                    <input type="text" class="form-control" name="new_telepon" id="new_telepon" placeholder="Telepon">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="new_nama_cp">PIC</label>
                    <input type="text" class="form-control" name="new_nama_cp" id="new_nama_cp" placeholder="PIC">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="new_jabatan_cp">Jabatan PIC</label>
                    <input type="text" class="form-control" name="new_jabatan_cp" id="new_jabatan_cp" placeholder="Jabatan PIC">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="new_bidang_usaha">Bidang Usaha</label>
                    <input type="text" class="form-control" name="new_bidang_usaha" id="new_bidang_usaha" placeholder="Bidang Usaha">
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_nama_cp" style="display: none;">
              <div class="col-4">
                <div class="form-group">
                  <label for="exist_nama_cp">PIC</label>
                  <input type="text" class="form-control" name="exist_nama_cp" id="exist_nama_cp" placeholder="PIC">
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="exist_jabatan_cp">Jabatan PIC</label>
                  <input type="text" class="form-control" name="exist_jabatan_cp" id="exist_jabatan_cp" placeholder="Jabatan PIC">
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="exist_bidang_usaha">Bidang Usaha</label>
                  <input type="text" class="form-control" name="exist_bidang_usaha" id="exist_bidang_usaha" placeholder="Bidang Usaha">
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

  <div class="modal fade" id="modal_update_data">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="update_form" id="update_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Minimum Order</label>
                  <input type="text" class="form-control" name="minimum_order" id="minimum_order" placeholder="Minimum Order">
                  <input type="hidden" class="form-control" name="nomor_surat" id="nomor_surat">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>Pembayaran</label>
                  <input type="text" class="form-control" name="pembayaran" id="pembayaran" placeholder="Pembayaran">
                </div>
              </div>
            </div>
            <div class="row">
            <div class="col-12">
              <div class="form-group">
                <table class="table table-bordered" id="dynamic_field_data">  
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" width="40%">Type</th>
                    <th style="vertical-align : middle; text-align: center;" width="30%">Packaging</th>
                    <th style="vertical-align : middle; text-align: center;" width="30%">Harga Kirim / KG</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                  </tr>
                  <tr>  
                    <td><input type="text" name="tipe[]" placeholder="Type" id="tipe1" class="form-control tipe" /></td>
                    <td><input type="text" name="packaging[]" placeholder="Packaging" id="packaging1" class="form-control packaging" /></td>
                    <td><input type="text" name="harga_kirim[]" placeholder="Harga Kirim / KG" id="harga_kirim1" class="form-control harga_kirim" /></td>  
                    <td style="vertical-align : middle; text-align: center;"><button type="button" name="add_data" id="add_data" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>  
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-update">Save changes</button>
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
                  <td>Nomor</td>
                  <td>:</td>
                  <td id="td_nomor"></td>
                  <td>Customers</td>
                  <td>:</td>
                  <td id="td_customers"></td>
                </tr>
                <tr>
                  <td>Minimum Order</td>
                  <td>:</td>
                  <td id="td_minimum_order"></td>
                  <td>Pembayaran</td>
                  <td>:</td>
                  <td id="td_pembayaran"></td>
                </tr>
              </table>
              <h5>List Produk dan Harga : </h5>
              <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">Tipe</th>
                    <th style="vertical-align : middle; text-align: center;">Packaging</th>
                    <th style="vertical-align : middle; text-align: center;">Harga Kirim</th> 
                  </tr>
                </thead>
                <tbody id="tbody_view">
                </tbody>
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
                  <label>Minimum Order</label>
                  <input type="text" class="form-control" name="edit_minimum_order" id="edit_minimum_order" placeholder="Minimum Order">
                  <input type="hidden" class="form-control" name="edit_nomor_surat" id="edit_nomor_surat">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>Pembayaran</label>
                  <input type="text" class="form-control" name="edit_pembayaran" id="edit_pembayaran" placeholder="Pembayaran">
                </div>
              </div>
            </div>
            <div class="row">
            <div class="col-12">
              <div class="form-group">
                <table class="table table-bordered" id="edit_dynamic_field_data">  
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" width="40%">Type</th>
                    <th style="vertical-align : middle; text-align: center;" width="30%">Packaging</th>
                    <th style="vertical-align : middle; text-align: center;" width="30%">Harga Kirim / KG</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                  </tr>
                  <tr>  
                    <td><input type="text" name="edit_tipe[]" placeholder="Type" id="edit_tipe1" class="form-control tipe" /><input type="hidden" name="edit_nomor_produk[]" id="edit_nomor_produk1" class="form-control nomor_produk" /></td>
                    <td><input type="text" name="edit_packaging[]" placeholder="Packaging" id="edit_packaging1" class="form-control packaging" /></td>
                    <td><input type="text" name="edit_harga_kirim[]" placeholder="Harga Kirim / KG" id="edit_harga_kirim1" class="form-control harga_kirim" /></td>  
                    <td style="vertical-align : middle; text-align: center;"><button type="button" name="edit_add_data" id="edit_add_data" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>  
                </table>
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

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var c_data = 0;
    var edit_c_data = 0;

    var table = $('#data_penawaran_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("surat_penawaran/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'nomor',
           name:'nomor',
           className:'dt-center',
           width:'20%'
         },
         {
           data:'tanggal',
           name:'tanggal',
           className:'dt-center',
           width:'15%'
         },
         {
           data:'company',
           name:'company',
           className:'dt-center',
           width:'20%'
         },
         {
           data:'nama',
           name:'nama',
           className:'dt-center'
         },
         {
           data:'action',
           name:'action',
           className:'dt-center',
           width:'15%'
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

    function load_data_penawaran(from_date = '', to_date = '')
    {
      table = $('#data_penawaran_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("surat_penawaran/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'nomor',
           name:'nomor',
           className:'dt-center',
           width:'20%'
         },
         {
           data:'tanggal',
           name:'tanggal',
           className:'dt-center',
           width:'15%'
         },
         {
           data:'company',
           name:'company',
           className:'dt-center',
           width:'20%'
         },
         {
           data:'nama',
           name:'nama',
           className:'dt-center'
         },
         {
           data:'action',
           name:'action',
           className:'dt-center',
           width:'15%'
         }
       ]
     });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#data_penawaran_table').DataTable().destroy();
        load_data_penawaran(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#data_penawaran_table').DataTable().destroy();
      load_data_penawaran();
    });

    $('body').on('click', '#btn_input_data', function () {
      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      $('#btn_new_leads').click(function(){
        $('#new_leads').show();
        $('#select_leads').hide();
      });

      $('#btn_exist_leads').click(function(){
        $('#new_leads').hide();
        $('#select_leads').show();
      });
    });

    $('body').on('click', '#update-data', function () {
      var nomor = $(this).data("id");

      $('#nomor_surat').val(nomor);

      for(var i = 2; i <= window['c_data']; i++){
        $('#row_data'+i).remove();
      }

      window['c_data'] = 1;

      $('#add_data').unbind().click(function(){
        window['c_data']++;
        $('#dynamic_field_data').append('<tr id="row_data'+window['c_data']+'"><td><input type="text" name="tipe[]" placeholder="Type" id="tipe'+window['c_data']+'" class="form-control tipe" /></td><td><input type="text" name="packaging[]" placeholder="Packaging" id="packaging'+window['c_data']+'" class="form-control packaging" /></td><td><input type="text" name="harga_kirim[]" placeholder="Harga Kirim / KG" id="harga_kirim'+window['c_data']+'" class="form-control harga_kirim" /></td><td><button type="button" name="data_remove" id="'+window['c_data']+'" class="btn btn-danger btn_remove_data"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
      });

      $(document).on('click', '.btn_remove_data', function(){  
        var button_id = $(this).attr("id");   
        $('#row_data'+button_id+'').remove();
        window['c_data']--;
      });
    });

    $('body').on('click', '#view-data', function () {
      var nomor = $(this).data("id");
      var url_data = "{{ url('surat_penawaran/view/nomor') }}";
      url_data = url_data.replace('nomor', enc(nomor.toString()));
      $('#td_nomor').html('');
      $('#td_customers').html('');
      $('#td_minimum_order').html('');
      $('#td_pembayaran').html('');
      $.get(url_data, function (data) {
        $('#td_nomor').html(data.nomor);
        $('#td_customers').html(data.customers);
        $('#td_minimum_order').html(data.minimum_order + ' TON');
        $('#td_pembayaran').html(data.pembayaran);
      })

      var url = "{{ url('surat_penawaran/view/detail/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $("#tbody_view").empty();
      $.get(url, function (data) {
        $.each(data, function(k, v) {
          $('#tbody_view').append(
            '<tr>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.tipe+'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+v.packaging+' Kg/zak</td>'+
            '<td style="vertical-align : middle; text-align: center;">Rp. '+v.harga_kirim+',-/kg + ppn</td>'+
            '</tr>'
          ); 
        });
      })
    });

    $('body').on('click', '#edit-data', function () {
      var nomor = $(this).data("id");

      $('#edit_nomor_surat').val(nomor);
      var url_data = "{{ url('surat_penawaran/view/nomor') }}";
      url_data = url_data.replace('nomor', enc(nomor.toString()));
      $('#edit_minimum_order').val('');
      $('#edit_pembayaran').val('');
      $.get(url_data, function (data) {
        $('#edit_minimum_order').val(data.minimum_order);
        $('#edit_pembayaran').val(data.pembayaran);
      })

      for(var i = 2; i <= window['edit_c_data']; i++){
        $('#edit_row_data'+i).remove();
      }

      var url = "{{ url('surat_penawaran/view/detail/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $.get(url, function (data) {
        if(data.length > 0){
          $('#edit_packaging1').val(data[0].packaging);
          $('#edit_tipe1').val(data[0].tipe);
          $('#edit_harga_kirim1').val(data[0].harga_kirim);
          $('#edit_nomor_produk1').val(data[0].nomor_produk);
        }

        $('#edit_add_data').unbind().click(function(){
          window['edit_c_data']++;
          $('#edit_dynamic_field_data').append('<tr id="edit_row_data'+window['edit_c_data']+'"><td><input type="text" name="edit_tipe[]" placeholder="Type" id="edit_tipe'+window['edit_c_data']+'" class="form-control tipe" /><input type="hidden" name="edit_nomor_produk[]" id="edit_nomor_produk'+window['edit_c_data']+'" class="form-control nomor_produk" /></td><td><input type="text" name="edit_packaging[]" placeholder="Packaging" id="edit_packaging'+window['edit_c_data']+'" class="form-control packaging" /></td><td><input type="text" name="edit_harga_kirim[]" placeholder="Harga Kirim / KG" id="edit_harga_kirim'+window['edit_c_data']+'" class="form-control harga_kirim" /></td><td><button type="button" name="edit_data_remove" id="'+window['edit_c_data']+'" class="btn btn-danger edit_btn_remove_data"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
        });

        $(document).on('click', '.edit_btn_remove_data', function(){  
          var button_id = $(this).attr("id");   
          var nomor = $("#edit_nomor_produk"+button_id).val();

          if(nomor != null || nomor != ''){
            $.ajax({
              type: "GET",
              url: "{{ url('surat_penawaran/detail/delete') }}",
              data: { 'nomor' : nomor },
              success: function (data) {
                alert("Data Deleted");
              },
              error: function (data) {
                console.log('Error:', data);
              }
            });
          }

          $('#edit_row_data'+button_id+'').remove();
          window['edit_c_data']--;
        });

        if(data.length > 0){
          window['edit_c_data'] = data.length;
        }else{
          window['edit_c_data'] = 1;
        }
        for(let i = 1; i < window['edit_c_data']; i++){
          $('#edit_dynamic_field_data').append('<tr id="edit_row_data'+(i+1)+'"><td><input type="text" name="edit_tipe[]" placeholder="Type" id="edit_tipe'+(i+1)+'" class="form-control tipe" /><input type="hidden" name="edit_nomor_produk[]" id="edit_nomor_produk'+(i+1)+'" class="form-control nomor_produk" /></td><td><input type="text" name="edit_packaging[]" placeholder="Packaging" id="edit_packaging'+(i+1)+'" class="form-control packaging" /></td><td><input type="text" name="edit_harga_kirim[]" placeholder="Harga Kirim / KG" id="edit_harga_kirim'+(i+1)+'" class="form-control harga_kirim" /></td><td><button type="button" name="edit_data_remove" id="'+(i+1)+'" class="btn btn-danger edit_btn_remove_data"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');

          $('#edit_tipe'+(i+1)).val(data[i].tipe);
          $('#edit_packaging'+(i+1)).val(data[i].packaging);
          $('#edit_harga_kirim'+(i+1)).val(data[i].harga_kirim);
          $('#edit_nomor_produk'+(i+1)).val(data[i].nomor_produk);
        }
      })
    });

    $('#leads').on("select2:selecting", function(e) { 
      var data = e.params.args.data;
      var url = "{{ url('sales/schedule/detail_leads/leadid') }}";
      url = url.replace('leadid', enc(data.id.toString()));
      $.get(url, function (data) {
        if(data.nama_cp == null || data.jabatan_cp == null || data.bidang_usaha == null){
          $('#div_nama_cp').show();
        }else{
          $('#div_nama_cp').hide();
        }
      })
    });

    $('#input_form').validate({
      rules: {
        company: {
          required: true,
        },
      },
      messages: {
        company: {
          required: "Perusahaan is required",
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
          url:"{{ url('surat_penawaran/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_penawaran_table').dataTable();
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

    $('#update_form').validate({
      rules: {
        nomor_surat: {
          required: true,
        },
        minimum_order: {
          required: true,
        },
        pembayaran: {
          required: true,
        },
      },
      messages: {
        nomor_surat: {
          required: "Nomor Surat is required",
        },
        minimum_order: {
          required: "Minimum Order is required",
        },
        pembayaran: {
          required: "Pembayaran is required",
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
        var myform = document.getElementById("update_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('surat_penawaran/update') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#update_form').trigger("reset");
            $('#modal_update_data').modal('hide');
            $("#modal_update_data").trigger('click');
            var oTable = $('#data_penawaran_table').dataTable();
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

    $('#edit_form').validate({
      rules: {
        edit_nomor_surat: {
          required: true,
        },
        edit_minimum_order: {
          required: true,
        },
        edit_pembayaran: {
          required: true,
        },
      },
      messages: {
        edit_nomor_surat: {
          required: "Nomor Surat is required",
        },
        edit_minimum_order: {
          required: "Minimum Order is required",
        },
        edit_pembayaran: {
          required: "Pembayaran is required",
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
          url:"{{ url('surat_penawaran/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_penawaran_table').dataTable();
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
    $('.leads').select2({
      dropdownParent: $('#modal_input_data .modal-content'),
      placeholder: 'Leads',
      allowClear: true,
      ajax: {
        url: '/autocomplete_leads',
        data: function (params) {
          var company = $('#company').val();
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
                text: item.nama,
                id: item.leadid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.city').select2({
      dropdownParent: $('#modal_input_data .modal-content'),
      placeholder: 'Pilih Kota',
      allowClear: true,
      ajax: {
        url: '/dropdown_city',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.name,
                id: item.id_kota
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
  $(".leads").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Leads Data Here...");
  });
  $(".leads").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".city").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Kota / Kabupaten...");
  });
  $(".city").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>

@endsection