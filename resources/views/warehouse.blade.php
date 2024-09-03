@extends('layouts.app_admin')

@section('title')
<title>WAREHOUSE - PT. DWI SELO GIRI MAS</title>
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
  #warehouse_table tbody tr:hover{
    cursor: pointer;
  }

  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
    }
    .warehouse-btn {
      margin-bottom: 10px;
    }
    .save-btn-in {
      width: 100%;
    }
    .lihat-table {
      overflow-x: auto;
    }
    .radio-control {
      padding-left: 0 !important;
    }
    #dynamic_field_rencana_produksi th {
      display: none;
    }
    #dynamic_field_rencana_produksi td {
      display:inline-block;
        padding:5px;
        width:100%;
    }
    #dynamic_field_spek_rencana_produksi th {
      display: none;
    }
    #dynamic_field_spek_rencana_produksi td {
      display:inline-block;
        padding:5px;
        width:100%;
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
          <h1 class="m-0 text-dark">Warehouse</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Produksi</li>
            <li class="breadcrumb-item">Warehouse</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  @endsection

  @section('content')
  <div class="loading" style="display: none;">Loading&#8230;</div>
  <div class="row"> 
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-3">
              <button type="button" name="btn_input_warehouse" id="btn_input_warehouse" class="btn btn-block btn-primary warehouse-btn">Input Warehouse</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="warehouse_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Total Produk</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="modal_input_warehouse">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Warehouse</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" class="warehouse_form" id="warehouse_form" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Kode Warehouse</label>
                <input class="form-control" type="text" name="kode_warehouse" id="kode_warehouse" placeholder="Kode Warehouse" />
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Nama Warehouse</label>
                <input class="form-control" type="text" name="nama_warehouse" id="nama_warehouse" placeholder="Nama Warehouse" />
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Deskripsi"></textarea>
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

  <div class="modal fade" id="modal_edit_warehouse">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Warehouse</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" class="edit_warehouse_form" id="edit_warehouse_form" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Kode Warehouse</label>
                <input class="form-control" type="text" name="edit_kode_warehouse" id="edit_kode_warehouse" placeholder="Kode Warehouse" readonly />
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Nama Warehouse</label>
                <input class="form-control" type="text" name="edit_nama_warehouse" id="edit_nama_warehouse" placeholder="Nama Warehouse" />
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" rows="3" name="edit_deskripsi" id="edit_deskripsi" placeholder="Deskripsi"></textarea>
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

  <div class="modal fade" id="modal_view_warehouse">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data Warehouse</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12 lihat-table">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Kode Warehouse</th>
                  <td id="td_kode_warehouse"></td>
                </tr>
                <tr>
                  <th>Nama Warehouse</th>
                  <td id="td_nama_warehouse"></td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td id="td_description"></td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td id="td_status"></td>
                </tr>
              </thead>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>

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
    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#warehouse_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("rencana_produksi/view_rencana_produksi_table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_rencana_produksi',
         name:'nomor_rencana_produksi'
       },
       {
         data:'tanggal_rencana',
         name:'tanggal_rencana'
       },
       {
         data:'total_sak',
         name:'total_sak',
         render: $.fn.dataTable.render.number('.', " Sak", ',')
       },
       {
         data:'total_tonase',
         name:'total_tonase',
         render: $.fn.dataTable.render.number('.', " Ton", ',')
       },
       {
         data:'action',
         name:'action',
         width:'15%'
       }
       ]
     });

    function load_data_rencana_produksi(from_date = '', to_date = '')
     {
      table = $('#rencana_produksi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("rencana_produksi/view_rencana_produksi_table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_rencana_produksi',
         name:'nomor_rencana_produksi'
       },
       {
         data:'tanggal_rencana',
         name:'tanggal_rencana'
       },
       {
         data:'total_sak',
         name:'total_sak',
         render: $.fn.dataTable.render.number('.', " Sak", ',')
       },
       {
         data:'total_tonase',
         name:'total_tonase',
         render: $.fn.dataTable.render.number('.', " Ton", ',')
       },
       {
         data:'action',
         name:'action',
         width:'15%'
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

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#rencana_produksi_table').DataTable().destroy();
        load_data_rencana_produksi(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#rencana_produksi_table').DataTable().destroy();
      load_data_rencana_produksi();
    });

    $('body').on('click', '#btn_input_rencana_produksi', function (event) {
      event.preventDefault();

      $(".loading").show();
      setTimeout(function() {
        $(".loading").hide();
        $('#modal_input_rencana_produksi').modal();
      }, 6000);

      var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      $.each(arr_mesin, function(k, v) {
        var isLastElement = k == arr_mesin.length -1;
        
        for(var i = 2; i <= window['c_rencana_'+v]; i++){
          $('#row_data_rencana_'+v+i).remove();
        }

        window['c_rencana_'+v] = 1;

        var url_jenis_produk = "{{ url('get_jenis_produk') }}";
        $.get(url_jenis_produk, function (data) {
          $('#select_jenis_produk_'+v+window['c_rencana_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
          $.each(data, function(l, m) {
            $('#select_jenis_produk_'+v+window['c_rencana_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
          });
        })

        $("#select_jenis_produk_"+v+window['c_rencana_'+v]).change(function() {
          var val = $(this).val();
          var url_weight = "{{ url('get_weight/kode_produk') }}";
          url_weight = url_weight.replace('kode_produk', enc(val.toString()));
          $.get(url_weight, function (data) {
            $('#weight_'+v+window['c_rencana_'+v]).val(data.weight);
            var a = $("#jumlah_sak_"+v+window['c_rencana_'+v]).val();
            if(a != null || a != ''){
              var b = data.weight;
              var total = a * b;
              $("#jumlah_tonase_"+v+window['c_rencana_'+v]).val(total);
            }
          })
        });

        $('#jumlah_sak_'+v+window['c_rencana_'+v]).on('keyup', function(){
          if($("#weight_"+v+window['c_rencana_'+v]).val() != null || $("#weight_"+v+window['c_rencana_'+v]).val() != ''){
            var a = $("#jumlah_sak_"+v+window['c_rencana_'+v]).val();
            var b = $("#weight_"+v+window['c_rencana_'+v]).val();
            var total = a * b;
            $("#jumlah_tonase_"+v+window['c_rencana_'+v]).val(total);
          }
        });

        $('#add_data_rencana_'+v).unbind().click(function(){
          $('#add_data_rencana_'+v).hide();
          window['c_rencana_'+v]++;
          $('#mesin_'+v).attr('rowspan', window['c_rencana_'+v]);
          $('#dynamic_field_rencana_produksi_'+v).append('<tr id="row_data_rencana_'+v+window['c_rencana_'+v]+'"><td><select name="jenis_produk_'+v+'[]" class="form-control jenis_produk_list_'+v+'" id="select_jenis_produk_'+v+window['c_rencana_'+v]+'"></select></td><td><input type="text" name="jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="jumlah_sak_'+v+window['c_rencana_'+v]+'" class="form-control jumlah_sak_list_'+v+'" /><input type="hidden" name="weight_'+v+'[]" id="weight_'+v+window['c_rencana_'+v]+'" class="form-control weight_list_'+v+'" /></td><td><input type="text" name="jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="jumlah_tonase_'+v+window['c_rencana_'+v]+'" class="form-control jumlah_tonase_list_'+v+'" /></td><td><button type="button" name="data_rencana_remove_'+v+'" id="'+window['c_rencana_'+v]+'" class="btn btn-danger btn_remove_rencana_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
          var url_jenis_produk = "{{ url('get_jenis_produk') }}";
          $.get(url_jenis_produk, function (data) {
            $('#select_jenis_produk_'+v+window['c_rencana_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
            $.each(data, function(l, m) {
              $('#select_jenis_produk_'+v+window['c_rencana_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
            });
          })

          $("#select_jenis_produk_"+v+window['c_rencana_'+v]).change(function() {
            var val = $(this).val();
            var url_weight = "{{ url('get_weight/kode_produk') }}";
            url_weight = url_weight.replace('kode_produk', enc(val.toString()));
            $.get(url_weight, function (data) {
              $('#weight_'+v+window['c_rencana_'+v]).val(data.weight);
              var a = $("#jumlah_sak_"+v+window['c_rencana_'+v]).val();
              if(a != null || a != ''){
                var b = data.weight;
                var total = a * b;
                $("#jumlah_tonase_"+v+window['c_rencana_'+v]).val(total);
              }
            })
          });

          $('#jumlah_sak_'+v+window['c_rencana_'+v]).on('keyup', function(){
            if($("#weight_"+v+window['c_rencana_'+v]).val() != null || $("#weight_"+v+window['c_rencana_'+v]).val() != ''){
              var a = $("#jumlah_sak_"+v+window['c_rencana_'+v]).val();
              var b = $("#weight_"+v+window['c_rencana_'+v]).val();
              var total = a * b;
              $("#jumlah_tonase_"+v+window['c_rencana_'+v]).val(total);
            }
          });

          setTimeout(function(){
            $('#add_data_rencana_'+v).show();
          }, 1000);
        });

        $(document).on('click', '.btn_remove_rencana_produksi_'+v, function(){  
          var button_id = $(this).attr("id");   
          $('#row_data_rencana_'+v+button_id+'').remove();
          window['c_rencana_'+v]--;
        });
      });

      // $("#referensi").on("input", function(e) {
      //   e.stopImmediatePropagation();
      //   var value = $(this).val();

      //   var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      //   $.each(arr_mesin, function(k, v) {
      //     $('#select_jenis_produk_'+v+1).val('').trigger('change');
      //     $('#weight_'+v+1).val('');
      //     $('#jumlah_sak_'+v+1).val('');
      //     $('#jumlah_tonase_'+v+1).val('');
      //     for(var a = 2; a <= window['c_rencana_'+v]; a++){
      //       $('#row_data_rencana_'+v+a).remove();
      //     }

      //     window['c_rencana_'+v] = 0;

      //   });

      //   var url_detail_ref = "{{ url('get_detail_ref_rencana_produksi/nomor_order_receipt') }}";
      //   url_detail_ref = url_detail_ref.replace("nomor_order_receipt", enc(value.toString()));
      //   $.get(url_detail_ref, function (data_dt) {
      //     if(typeof data_dt !== 'undefined' && data_dt.length > 0){
      //       $('#show_custid').html("Customer : " + data_dt[0].custname);
      //       $('#show_tanggal_order').html("Tanggal Order : " + data_dt[0].tanggal_order);
      //       for(let i = 0; i < data_dt.length; i++){
      //         window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]++;

      //         if(window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]] == 1){

      //           var url_jenis_produk = "{{ url('get_jenis_produk') }}";
      //           $.get(url_jenis_produk, function (data) {
      //             $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
      //             $.each(data, function(l, m) {
      //               $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
      //             });

      //             $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jenis_produk).trigger('change');
      //           })

      //           $('#weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].weight);
      //           $('#jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_sak);
      //           $('#jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_tonase);
      //         }else{
      //           $('#mesin_'+arr_mesin[data_dt[i].mesin - 1]).attr('rowspan', window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]);
      //           $('#dynamic_field_rencana_produksi_'+arr_mesin[data_dt[i].mesin - 1]).append('<tr id="row_data_rencana_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'"><td><select name="jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+'[]" class="form-control jenis_produk_list_'+arr_mesin[data_dt[i].mesin - 1]+'" id="select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'"></select></td><td><input type="text" name="jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+'[]" placeholder="Jumlah Sak" id="jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control jumlah_sak_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /><input type="hidden" name="weight_'+arr_mesin[data_dt[i].mesin - 1]+'[]" id="weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control weight_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /></td><td><input type="text" name="jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+'[]" placeholder="Jumlah Tonase" id="jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control jumlah_tonase_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /></td><td><button type="button" name="data_rencana_remove_'+arr_mesin[data_dt[i].mesin - 1]+'" id="'+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="btn btn-danger btn_remove_rencana_produksi_'+arr_mesin[data_dt[i].mesin - 1]+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');

      //           var url_jenis_produk = "{{ url('get_jenis_produk') }}";
      //           $.get(url_jenis_produk, function (data) {
      //             $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
      //             $.each(data, function(l, m) {
      //               $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
      //             });

      //             $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jenis_produk).trigger('change');
      //           })

      //           $('#weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].weight);
      //           $('#jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_sak);
      //           $('#jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_tonase);
      //         }
      //       }
      //     }
      //   })
      // });
    });

    function getHighest(obj){
      keys = Object.keys(obj);
      if(!keys.length)
        return 0;

      max = obj[keys[0]].length;
      for(i=1; i<keys.length; i++ )
      {
        if(obj[keys[i]].length > max)
          max = obj[keys[i]].length;
      }
      return max;
    }

    $('body').on('click', '#input-data-spek-rencana-produksi', function () {
      var nomor = $(this).data("id");

      $('#nomor_rencana_produksi_spek').val(nomor);

      $('#modal_input_spek_rencana_produksi').modal();
    });

    $('body').on('click', '#edit-data', function (event) {
      event.preventDefault();

      var nomor = $(this).data("id");
      $('#edit_nomor_rencana_produksi').val(nomor);
      $(this).prop("disabled", true);
      $(this).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
      );
      $(".loading").show();
      setTimeout(function() {
        $("#edit-data").prop("disabled", false);
        $("#edit-data").html('<i class="fa fa-edit"></i>');
        $(".loading").hide();
        $('#modal_edit_rencana_produksi').modal();
      }, 12000);

      var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      var url_data = "{{ url('rencana_produksi/detail_rencana/nomor') }}";
      url_data = url_data.replace('nomor', enc(nomor.toString()));
      $.get(url_data, function (data_prd) {
        console.log(data_prd['data_referensi']);
        $('#edit_tanggal_rencana').val(data_prd['data_ren'][0].tanggal_rencana);
        // $('#edit_referensi').val(['A', 'B']).trigger('change');
        $.each(data_prd['data_referensi'], function(k, v) {
          var $newOption = $("<option selected='selected'></option>").val(v).text(v);
          $("#edit_referensi").append($newOption).trigger('change');
        });
        $('#edit_keterangan').val(data_prd['data_ren'][0].keterangan);
        $.each(arr_mesin, function(k, v) {
          for(var i = 2; i <= window['edit_c_rencana_'+v]; i++){
            $('#edit_row_data_rencana_'+v+i).remove();
          }

          var url_jenis_produk = "{{ url('get_jenis_produk') }}";
          $.get(url_jenis_produk, function (data) {
            $('#edit_select_jenis_produk_'+v+1).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
            $.each(data, function(l, m) {
              $('#edit_select_jenis_produk_'+v+1).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
            });
            if(data_prd[v].length > 0){
              $('#edit_jenis_produk_lama_'+v+1).val(data_prd[v][0].jenis_produk);
              $('#edit_select_jenis_produk_'+v+1).val(data_prd[v][0].jenis_produk).trigger('change');
            }
          })
          if(data_prd[v].length > 0){
            $('#edit_jumlah_sak_'+v+1).val(data_prd[v][0].jumlah_sak);
            $('#edit_jumlah_tonase_'+v+1).val(data_prd[v][0].jumlah_tonase);
            $('#edit_nomor_rencana_produksi_detail_'+v+1).val(data_prd[v][0].nomor_rencana_produksi_detail);
          }

          $("#edit_select_jenis_produk_"+v+1).change(function() {
            var val = $(this).val();
            var url_weight = "{{ url('get_weight/kode_produk') }}";
            url_weight = url_weight.replace('kode_produk', enc(val.toString()));
            $.get(url_weight, function (data) {
              $('#edit_weight_'+v+1).val(data.weight);
              var a = $("#edit_jumlah_sak_"+v+1).val();
              if(a != null || a != ''){
                var b = data.weight;
                var total = a * b;
                $("#edit_jumlah_tonase_"+v+1).val(total);
              }
            })
          });

          $('#edit_jumlah_sak_'+v+1).on('keyup', function(){
            if($("#edit_weight_"+v+1).val() != null || $("#edit_weight_"+v+1).val() != ''){
              var a = $("#edit_jumlah_sak_"+v+1).val();
              var b = $("#edit_weight_"+v+1).val();
              var total = a * b;
              $("#edit_jumlah_tonase_"+v+1).val(total);
            }
          });

          $('#edit_add_data_rencana_'+v).unbind().click(function(){
            $('#edit_add_data_rencana_'+v).hide();
            window['edit_c_rencana_'+v]++;
            $('#edit_mesin_'+v).attr('rowspan', window['edit_c_rencana_'+v]);
            $('#edit_dynamic_field_rencana_produksi_'+v).append('<tr id="edit_row_data_rencana_'+v+window['edit_c_rencana_'+v]+'"><td><select name="edit_jenis_produk_'+v+'[]" class="form-control edit_jenis_produk_list_'+v+'" id="edit_select_jenis_produk_'+v+window['edit_c_rencana_'+v]+'"></select><input type="hidden" name="edit_jenis_produk_lama_'+v+'[]" id="edit_jenis_produk_lama_'+v+window['edit_c_rencana_'+v]+'" class="form-control edit_jenis_produk_lama_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_'+v+window['edit_c_rencana_'+v]+'" class="form-control edit_jumlah_sak_list_'+v+'" /><input type="hidden" name="edit_weight_'+v+'[]" id="edit_weight_'+v+window['edit_c_rencana_'+v]+'" class="form-control edit_weight_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_'+v+window['edit_c_rencana_'+v]+'" class="form-control edit_jumlah_tonase_list_'+v+'" /></td><td><button type="button" name="edit_data_rencana_remove_'+v+'" id="'+window['edit_c_rencana_'+v]+'" class="btn btn-danger edit_btn_remove_rencana_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
            var url_jenis_produk = "{{ url('get_jenis_produk') }}";
            $.get(url_jenis_produk, function (data) {
              $('#edit_select_jenis_produk_'+v+window['edit_c_rencana_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
              $.each(data, function(l, m) {
                $('#edit_select_jenis_produk_'+v+window['edit_c_rencana_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
              });
            })

            $("#edit_select_jenis_produk_"+v+window['edit_c_rencana_'+v]).change(function() {
              var val = $(this).val();
              $('#edit_jenis_produk_lama_'+v+window['edit_c_rencana_'+v]).val(val);
              var url_weight = "{{ url('get_weight/kode_produk') }}";
              url_weight = url_weight.replace('kode_produk', enc(val.toString()));
              $.get(url_weight, function (data) {
                $('#edit_weight_'+v+window['edit_c_rencana_'+v]).val(data.weight);
                var a = $("#edit_jumlah_sak_"+v+window['edit_c_rencana_'+v]).val();
                if(a != null || a != ''){
                  var b = data.weight;
                  var total = a * b;
                  $("#edit_jumlah_tonase_"+v+window['edit_c_rencana_'+v]).val(total);
                }
              })
            });

            $('#edit_jumlah_sak_'+v+window['edit_c_rencana_'+v]).on('keyup', function(){
              if($("#edit_weight_"+v+window['edit_c_rencana_'+v]).val() != null || $("#edit_weight_"+v+window['edit_c_rencana_'+v]).val() != ''){
                var a = $("#edit_jumlah_sak_"+v+window['edit_c_rencana_'+v]).val();
                var b = $("#edit_weight_"+v+window['edit_c_rencana_'+v]).val();
                var total = a * b;
                $("#edit_jumlah_tonase_"+v+window['edit_c_rencana_'+v]).val(total);
              }
            });

            setTimeout(function(){
              $('#edit_add_data_rencana_'+v).show();
            }, 1000);
          });

          $(document).on('click', '.edit_btn_remove_rencana_produksi_'+v, function(){  
            var button_id = $(this).attr("id");   
            var nomor = $("#edit_nomor_rencana_produksi_detail_"+v+button_id). val();

            $.ajax({
              type: "GET",
              url: "{{ url('rencana_produksi/detail/delete') }}",
              data: { 'nomor' : nomor },
              success: function (data) {
                alert("Data Deleted");
              },
              error: function (data) {
                console.log('Error:', data);
              }
            });
            $('#edit_row_data_rencana_'+v+button_id).remove();
            window['edit_c_rencana_'+v]--;
          });
        });
        
        $.each(arr_mesin, function(k, v) {
          if(data_prd[v].length > 0){
            window['edit_c_rencana_'+v] = data_prd[v].length;
          }else{
            window['edit_c_rencana_'+v] = 1;
          }
          $('#edit_mesin_'+v).attr('rowspan', window['edit_c_rencana_'+v]);
          for(let i = 1; i < window['edit_c_rencana_'+v]; i++){
            $('#edit_dynamic_field_rencana_produksi_'+v).append('<tr id="edit_row_data_rencana_'+v+(i+1)+'"><td><select name="edit_jenis_produk_'+v+'[]" class="form-control edit_jenis_produk_list_'+v+'" id="edit_select_jenis_produk_'+v+(i+1)+'"></select><input type="hidden" name="edit_jenis_produk_lama_'+v+'[]" id="edit_jenis_produk_lama_'+v+(i+1)+'" class="form-control edit_jenis_produk_lama_list_'+v+'" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_'+v+(i+1)+'" id="edit_nomor_rencana_produksi_detail_'+v+(i+1)+'" class="form-control edit_nomor_rencana_produksi_detail_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_'+v+(i+1)+'" class="form-control edit_jumlah_sak_list_'+v+'" /><input type="hidden" name="edit_weight_'+v+'[]" id="edit_weight_'+v+(i+1)+'" class="form-control edit_weight_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_'+v+(i+1)+'" class="form-control edit_jumlah_tonase_list_'+v+'" /></td><td><button type="button" name="edit_data_rencana_remove_'+v+'" id="'+(i+1)+'" class="btn btn-danger edit_btn_remove_rencana_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');

            var url_jenis_produk = "{{ url('get_jenis_produk') }}";
            $.get(url_jenis_produk, function (data) {
              $('#edit_select_jenis_produk_'+v+(i+1)).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
              $.each(data, function(l, m) {
                $('#edit_select_jenis_produk_'+v+(i+1)).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
              });
              $('#edit_jenis_produk_lama_'+v+(i+1)).val(data_prd[v][i].jenis_produk);
              $('#edit_select_jenis_produk_'+v+(i+1)).val(data_prd[v][i].jenis_produk).trigger('change');
            })
            $('#edit_jumlah_sak_'+v+(i+1)).val(data_prd[v][i].jumlah_sak);
            $('#edit_jumlah_tonase_'+v+(i+1)).val(data_prd[v][i].jumlah_tonase);
            $('#edit_nomor_rencana_produksi_detail_'+v+(i+1)).val(data_prd[v][i].nomor_rencana_produksi_detail);
          }
        });
      });
    });

    $('body').on('click', '#edit-data-spek-rencana-produksi', function () {
      var nomor = $(this).data("id");

      $('#edit_nomor_rencana_produksi_spek').val(nomor);

      $('#modal_edit_spek_rencana_produksi').modal();

      var url_spek = "{{ url('rencana_produksi/detail_spek_rencana/nomor') }}";
      url_spek = url_spek.replace('nomor', enc(nomor.toString()));
      $.get(url_spek, function (data_spek) {
        $('#edit_spek_rpm_sa1').val((data_spek.sa[0].rpm == null ? '' : data_spek.sa[0].rpm));
        $('#edit_spek_rpm_sb1').val((data_spek.sb[0].rpm == null ? '' : data_spek.sb[0].rpm));
        $('#edit_spek_rpm_mixer1').val((data_spek.mixer[0].rpm == null ? '' : data_spek.mixer[0].rpm));
        $('#edit_spek_rpm_ra1').val((data_spek.ra[0].rpm == null ? '' : data_spek.ra[0].rpm));
        $('#edit_spek_rpm_rb1').val((data_spek.rb[0].rpm == null ? '' : data_spek.rb[0].rpm));
        $('#edit_spek_rpm_rc1').val((data_spek.rc[0].rpm == null ? '' : data_spek.rc[0].rpm));
        $('#edit_spek_rpm_rd1').val((data_spek.rd[0].rpm == null ? '' : data_spek.rd[0].rpm));
        $('#edit_spek_rpm_re1').val((data_spek.re[0].rpm == null ? '' : data_spek.re[0].rpm));
        $('#edit_spek_rpm_rf1').val((data_spek.rf[0].rpm == null ? '' : data_spek.rf[0].rpm));
        $('#edit_spek_rpm_rg1').val((data_spek.rg[0].rpm == null ? '' : data_spek.rg[0].rpm));

        $('#edit_spek_particle_size_sa1').val((data_spek.sa[0].particle_size == null ? '' : data_spek.sa[0].particle_size));
        $('#edit_spek_particle_size_sb1').val((data_spek.sb[0].particle_size == null ? '' : data_spek.sb[0].particle_size));
        $('#edit_spek_particle_size_mixer1').val((data_spek.mixer[0].particle_size == null ? '' : data_spek.mixer[0].particle_size));
        $('#edit_spek_particle_size_ra1').val((data_spek.ra[0].particle_size == null ? '' : data_spek.ra[0].particle_size));
        $('#edit_spek_particle_size_rb1').val((data_spek.rb[0].particle_size == null ? '' : data_spek.rb[0].particle_size));
        $('#edit_spek_particle_size_rc1').val((data_spek.rc[0].particle_size == null ? '' : data_spek.rc[0].particle_size));
        $('#edit_spek_particle_size_rd1').val((data_spek.rd[0].particle_size == null ? '' : data_spek.rd[0].particle_size));
        $('#edit_spek_particle_size_re1').val((data_spek.re[0].particle_size == null ? '' : data_spek.re[0].particle_size));
        $('#edit_spek_particle_size_rf1').val((data_spek.rf[0].particle_size == null ? '' : data_spek.rf[0].particle_size));
        $('#edit_spek_particle_size_rg1').val((data_spek.rg[0].particle_size == null ? '' : data_spek.rg[0].particle_size));

        $('#edit_spek_ssa_sa1').val((data_spek.sa[0].ssa == null ? '' : data_spek.sa[0].ssa));
        $('#edit_spek_ssa_sb1').val((data_spek.sb[0].ssa == null ? '' : data_spek.sb[0].ssa));
        $('#edit_spek_ssa_mixer1').val((data_spek.mixer[0].ssa == null ? '' : data_spek.mixer[0].ssa));
        $('#edit_spek_ssa_ra1').val((data_spek.ra[0].ssa == null ? '' : data_spek.ra[0].ssa));
        $('#edit_spek_ssa_rb1').val((data_spek.rb[0].ssa == null ? '' : data_spek.rb[0].ssa));
        $('#edit_spek_ssa_rc1').val((data_spek.rc[0].ssa == null ? '' : data_spek.rc[0].ssa));
        $('#edit_spek_ssa_rd1').val((data_spek.rd[0].ssa == null ? '' : data_spek.rd[0].ssa));
        $('#edit_spek_ssa_re1').val((data_spek.re[0].ssa == null ? '' : data_spek.re[0].ssa));
        $('#edit_spek_ssa_rf1').val((data_spek.rf[0].ssa == null ? '' : data_spek.rf[0].ssa));
        $('#edit_spek_ssa_rg1').val((data_spek.rg[0].ssa == null ? '' : data_spek.rg[0].ssa));

        $('#edit_spek_whiteness_sa1').val((data_spek.sa[0].whiteness == null ? '' : data_spek.sa[0].whiteness));
        $('#edit_spek_whiteness_sb1').val((data_spek.sb[0].whiteness == null ? '' : data_spek.sb[0].whiteness));
        $('#edit_spek_whiteness_mixer1').val((data_spek.mixer[0].whiteness == null ? '' : data_spek.mixer[0].whiteness));
        $('#edit_spek_whiteness_ra1').val((data_spek.ra[0].whiteness == null ? '' : data_spek.ra[0].whiteness));
        $('#edit_spek_whiteness_rb1').val((data_spek.rb[0].whiteness == null ? '' : data_spek.rb[0].whiteness));
        $('#edit_spek_whiteness_rc1').val((data_spek.rc[0].whiteness == null ? '' : data_spek.rc[0].whiteness));
        $('#edit_spek_whiteness_rd1').val((data_spek.rd[0].whiteness == null ? '' : data_spek.rd[0].whiteness));
        $('#edit_spek_whiteness_re1').val((data_spek.re[0].whiteness == null ? '' : data_spek.re[0].whiteness));
        $('#edit_spek_whiteness_rf1').val((data_spek.rf[0].whiteness == null ? '' : data_spek.rf[0].whiteness));
        $('#edit_spek_whiteness_rg1').val((data_spek.rg[0].whiteness == null ? '' : data_spek.rg[0].whiteness));

        $('#edit_spek_residue_sa1').val((data_spek.sa[0].residue == null ? '' : data_spek.sa[0].residue));
        $('#edit_spek_residue_sb1').val((data_spek.sb[0].residue == null ? '' : data_spek.sb[0].residue));
        $('#edit_spek_residue_mixer1').val((data_spek.mixer[0].residue == null ? '' : data_spek.mixer[0].residue));
        $('#edit_spek_residue_ra1').val((data_spek.ra[0].residue == null ? '' : data_spek.ra[0].residue));
        $('#edit_spek_residue_rb1').val((data_spek.rb[0].residue == null ? '' : data_spek.rb[0].residue));
        $('#edit_spek_residue_rc1').val((data_spek.rc[0].residue == null ? '' : data_spek.rc[0].residue));
        $('#edit_spek_residue_rd1').val((data_spek.rd[0].residue == null ? '' : data_spek.rd[0].residue));
        $('#edit_spek_residue_re1').val((data_spek.re[0].residue == null ? '' : data_spek.re[0].residue));
        $('#edit_spek_residue_rf1').val((data_spek.rf[0].residue == null ? '' : data_spek.rf[0].residue));
        $('#edit_spek_residue_rg1').val((data_spek.rg[0].residue == null ? '' : data_spek.rg[0].residue));

        $('#edit_spek_moisture_sa1').val((data_spek.sa[0].moisture == null ? '' : data_spek.sa[0].moisture));
        $('#edit_spek_moisture_sb1').val((data_spek.sb[0].moisture == null ? '' : data_spek.sb[0].moisture));
        $('#edit_spek_moisture_mixer1').val((data_spek.mixer[0].moisture == null ? '' : data_spek.mixer[0].moisture));
        $('#edit_spek_moisture_ra1').val((data_spek.ra[0].moisture == null ? '' : data_spek.ra[0].moisture));
        $('#edit_spek_moisture_rb1').val((data_spek.rb[0].moisture == null ? '' : data_spek.rb[0].moisture));
        $('#edit_spek_moisture_rc1').val((data_spek.rc[0].moisture == null ? '' : data_spek.rc[0].moisture));
        $('#edit_spek_moisture_rd1').val((data_spek.rd[0].moisture == null ? '' : data_spek.rd[0].moisture));
        $('#edit_spek_moisture_re1').val((data_spek.re[0].moisture == null ? '' : data_spek.re[0].moisture));
        $('#edit_spek_moisture_rf1').val((data_spek.rf[0].moisture == null ? '' : data_spek.rf[0].moisture));
        $('#edit_spek_moisture_rg1').val((data_spek.rg[0].moisture == null ? '' : data_spek.rg[0].moisture));
      })
    });

    $('body').on('click', '#view-data', function () {
      var nomor = $(this).data("id");

      $('#modal_lihat_rencana_produksi').modal();

      var url = "{{ url('rencana_produksi/detail_rencana/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $('#td_tanggal_rencana').html('');
      var obj = {};
      var obj2 = {};
      $('#table_rencana1').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">SA</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">SB</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">Mixer</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RA</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RB</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '</tr>'
      );
      $('#table_rencana2').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RC</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RD</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RE</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RF</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RG</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '</tr>'
      );
      $.get(url, function (data) {
        $('#td_tanggal_rencana').html(data.data_ren[0].tanggal_rencana);
        $('#td_referensi').html(data.data_referensi.join("; "));
        obj[0] = data.sa;
        obj[1] = data.sb;
        obj[2] = data.mixer;
        obj[3] = data.ra;
        obj[4] = data.rb;
        obj2[0] = data.rc;
        obj2[1] = data.rd;
        obj2[2] = data.re;
        obj2[3] = data.rf;
        obj2[4] = data.rg;
        // obj.push(data.sa, data.sb, data.mixer, data.ra, data.rb, data.rc, data.rd, data.re, data.rf, data.rg, data.coating);

        for(var i = 0; i < getHighest(obj); i++){
          $('#table_rencana1').append(
            '<tr>'
          );
          if(data.sa[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.sa[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.sa[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.sb[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.sb[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.sb[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.mixer[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.mixer[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.mixer[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.ra[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.ra[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.ra[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.rb[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rb[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rb[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          $('#table_rencana1').append(
            '</tr>'+
            '</thead>'
          );
        }

        for(var i = 0; i < getHighest(obj2); i++){
          $('#table_rencana2').append(
            '<tr>'
          );
          if(data.rc[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rc[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rc[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.rd[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rd[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rd[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.re[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.re[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.re[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.rf[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rf[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rf[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.rg[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rg[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rg[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          $('#table_rencana2').append(
            '</tr>'+
            '</thead>'
          );
        }
      })
      
      var url_spek = "{{ url('rencana_produksi/detail_spek_rencana/nomor') }}";
      url_spek = url_spek.replace('nomor', enc(nomor.toString()));
      var obj_spek = {};
      $('#table_spek_rencana').html(
        '<thead>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Mesin</th>'+
        '<th style="vertical-align: top; text-align: center;">SA</th>'+
        '<th style="vertical-align: top; text-align: center;">SB</th>'+
        '<th style="vertical-align: top; text-align: center;">Mixer</th>'+
        '<th style="vertical-align: top; text-align: center;">RA</th>'+
        '<th style="vertical-align: top; text-align: center;">RB</th>'+
        '<th style="vertical-align: top; text-align: center;">RC</th>'+
        '<th style="vertical-align: top; text-align: center;">RD</th>'+
        '<th style="vertical-align: top; text-align: center;">RE</th>'+
        '<th style="vertical-align: top; text-align: center;">RF</th>'+
        '<th style="vertical-align: top; text-align: center;">RG</th>'+
        '</tr>'
      );
      $.get(url_spek, function (data_spek) {
        $('#table_spek_rencana').append(
          '<tr><td style="vertical-align: top; text-align: center;">RPM</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].rpm == null || data_spek.sa[0].rpm == '' ? '-' : data_spek.sa[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].rpm == null || data_spek.sb[0].rpm == '' ? '-' : data_spek.sb[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].rpm == null || data_spek.mixer[0].rpm == '' ? '-' : data_spek.mixer[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].rpm == null || data_spek.ra[0].rpm == '' ? '-' : data_spek.ra[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].rpm == null || data_spek.rb[0].rpm == '' ? '-' : data_spek.rb[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].rpm == null || data_spek.rc[0].rpm == '' ? '-' : data_spek.rc[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].rpm == null || data_spek.rd[0].rpm == '' ? '-' : data_spek.rd[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].rpm == null || data_spek.re[0].rpm == '' ? '-' : data_spek.re[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].rpm == null || data_spek.rf[0].rpm == '' ? '-' : data_spek.rf[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].rpm == null || data_spek.rg[0].rpm == '' ? '-' : data_spek.rg[0].rpm) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">Particle Size</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].particle_size == null || data_spek.sa[0].particle_size == '' ? '-' : data_spek.sa[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].particle_size == null || data_spek.sb[0].particle_size == '' ? '-' : data_spek.sb[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].particle_size == null || data_spek.mixer[0].particle_size == '' ? '-' : data_spek.mixer[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].particle_size == null || data_spek.ra[0].particle_size == '' ? '-' : data_spek.ra[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].particle_size == null || data_spek.rb[0].particle_size == '' ? '-' : data_spek.rb[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].particle_size == null || data_spek.rc[0].particle_size == '' ? '-' : data_spek.rc[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].particle_size == null || data_spek.rd[0].particle_size == '' ? '-' : data_spek.rd[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].particle_size == null || data_spek.re[0].particle_size == '' ? '-' : data_spek.re[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].particle_size == null || data_spek.rf[0].particle_size == '' ? '-' : data_spek.rf[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].particle_size == null || data_spek.rg[0].particle_size == '' ? '-' : data_spek.rg[0].particle_size) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">SSA</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].ssa == null || data_spek.sa[0].ssa == '' ? '-' : data_spek.sa[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].ssa == null || data_spek.sb[0].ssa == '' ? '-' : data_spek.sb[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].ssa == null || data_spek.mixer[0].ssa == '' ? '-' : data_spek.mixer[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].ssa == null || data_spek.ra[0].ssa == '' ? '-' : data_spek.ra[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].ssa == null || data_spek.rb[0].ssa == '' ? '-' : data_spek.rb[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].ssa == null || data_spek.rc[0].ssa == '' ? '-' : data_spek.rc[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].ssa == null || data_spek.rd[0].ssa == '' ? '-' : data_spek.rd[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].ssa == null || data_spek.re[0].ssa == '' ? '-' : data_spek.re[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].ssa == null || data_spek.rf[0].ssa == '' ? '-' : data_spek.rf[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].ssa == null || data_spek.rg[0].ssa == '' ? '-' : data_spek.rg[0].ssa) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">Whiteness</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].whiteness == null || data_spek.sa[0].whiteness == '' ? '-' : data_spek.sa[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].whiteness == null || data_spek.sb[0].whiteness == '' ? '-' : data_spek.sb[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].whiteness == null || data_spek.mixer[0].whiteness == '' ? '-' : data_spek.mixer[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].whiteness == null || data_spek.ra[0].whiteness == '' ? '-' : data_spek.ra[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].whiteness == null || data_spek.rb[0].whiteness == '' ? '-' : data_spek.rb[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].whiteness == null || data_spek.rc[0].whiteness == '' ? '-' : data_spek.rc[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].whiteness == null || data_spek.rd[0].whiteness == '' ? '-' : data_spek.rd[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].whiteness == null || data_spek.re[0].whiteness == '' ? '-' : data_spek.re[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].whiteness == null || data_spek.rf[0].whiteness == '' ? '-' : data_spek.rf[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].whiteness == null || data_spek.rg[0].whiteness == '' ? '-' : data_spek.rg[0].whiteness) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">Residue</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].residue == null || data_spek.sa[0].residue == '' ? '-' : data_spek.sa[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].residue == null || data_spek.sb[0].residue == '' ? '-' : data_spek.sb[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].residue == null || data_spek.mixer[0].residue == '' ? '-' : data_spek.mixer[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].residue == null || data_spek.ra[0].residue == '' ? '-' : data_spek.ra[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].residue == null || data_spek.rb[0].residue == '' ? '-' : data_spek.rb[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].residue == null || data_spek.rc[0].residue == '' ? '-' : data_spek.rc[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].residue == null || data_spek.rd[0].residue == '' ? '-' : data_spek.rd[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].residue == null || data_spek.re[0].residue == '' ? '-' : data_spek.re[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].residue == null || data_spek.rf[0].residue == '' ? '-' : data_spek.rf[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].residue == null || data_spek.rg[0].residue == '' ? '-' : data_spek.rg[0].residue) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">Moisture</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].moisture == null || data_spek.sa[0].moisture == '' ? '-' : data_spek.sa[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].moisture == null || data_spek.sb[0].moisture == '' ? '-' : data_spek.sb[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].moisture == null || data_spek.mixer[0].moisture == '' ? '-' : data_spek.mixer[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].moisture == null || data_spek.ra[0].moisture == '' ? '-' : data_spek.ra[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].moisture == null || data_spek.rb[0].moisture == '' ? '-' : data_spek.rb[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].moisture == null || data_spek.rc[0].moisture == '' ? '-' : data_spek.rc[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].moisture == null || data_spek.rd[0].moisture == '' ? '-' : data_spek.rd[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].moisture == null || data_spek.re[0].moisture == '' ? '-' : data_spek.re[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].moisture == null || data_spek.rf[0].moisture == '' ? '-' : data_spek.rf[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].moisture == null || data_spek.rg[0].moisture == '' ? '-' : data_spek.rg[0].moisture) +'</td></tr>'
          );
      })
    });

    $('#rencana_produksi_form').validate({
      rules: {
        tanggal_rencana: {
          required: true,
        },
      },
      messages: {
        tanggal_rencana: {
          required: "Tanggal Rencana is Required",
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
        var myform = document.getElementById("rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#rencana_produksi_form').trigger("reset");
            var oTable = $('#rencana_produksi_table').dataTable();
            oTable.fnDraw(false);
            var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];
            $.each(arr_mesin, function(k, v) {
              for(var i = 2; i <= window['c_rencana_'+v]; i++){
                $('#row_data_rencana_'+v+i).remove();
              }
            });
            $("#modal_input_rencana_produksi").modal('hide');
            $("#modal_input_rencana_produksi").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#spek_rencana_produksi_form').validate({
      rules: {
        nomor_rencana_produksi_spek: {
          required: true,
        },
      },
      messages: {
        nomor_rencana_produksi_spek: {
          required: "Nomor Rencana Produksi is Required",
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
        var myform = document.getElementById("spek_rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/spek/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#spek_rencana_produksi_form').trigger("reset");
            var oTable = $('#rencana_produksi_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_input_spek_rencana_produksi").modal('hide');
            $("#modal_input_spek_rencana_produksi").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#edit_rencana_produksi_form').validate({
      rules: {
        edit_tanggal_rencana: {
          required: true,
        },
      },
      messages: {
        edit_tanggal_rencana: {
          required: "Tanggal Rencana is Required",
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
        var myform = document.getElementById("edit_rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_rencana_produksi_form').trigger("reset");
            var oTable = $('#laporan_produksi_table').dataTable();
            oTable.fnDraw(false);
            var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];
            $.each(arr_mesin, function(k, v) {
              for(var i = 2; i <= window['edit_c_rencana_'+v]; i++){
                $('#edit_row_data_rencana_'+v+i).remove();
              }
            });
            $("#modal_edit_rencana_produksi").modal('hide');
            $("#modal_edit_rencana_produksi").trigger('click');
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#edit_spek_rencana_produksi_form').validate({
      rules: {
        edit_nomor_rencana_produksi_spek: {
          required: true,
        },
      },
      messages: {
        edit_nomor_rencana_produksi_spek: {
          required: "Nomor Rencana Produksi is Required",
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
        var myform = document.getElementById("edit_spek_rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/spek/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_spek_rencana_produksi_form').trigger("reset");
            var oTable = $('#rencana_produksi_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_edit_spek_rencana_produksi").modal('hide');
            $("#modal_edit_spek_rencana_produksi").trigger('click');
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('.referensi').select2({
      multiple: true,
      placeholder: {
        id: "-1",
        text: '--- Please select a reference number ---',
        selected:'selected'
      },
      allowClear: true,
      ajax: {
        url: '/dropdown/rencana_produksi/referensi',
        data: function (params) {
          return {
            q: params.term,
          };
        },
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.nomor_order_receipt,
                id: item.nomor_order_receipt
              }
            })
          };
        },
        cache: true
      }
    });

    $('.edit_referensi').select2({
      multiple: true,
      placeholder: {
        id: "-1",
        text: '--- Please select a reference number ---',
        selected:'selected'
      },
      allowClear: true,
      ajax: {
        url: '/dropdown/rencana_produksi/referensi',
        data: function (params) {
          return {
            q: params.term,
          };
        },
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.nomor_order_receipt,
                id: item.nomor_order_receipt
              }
            })
          };
        },
        cache: true
      }
    });
  });
</script>

@endsection
