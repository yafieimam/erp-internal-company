@extends('layouts.app_admin')

@section('title')
<title>POTONGAN BATU - PT. DWI SELO GIRI MAS</title>
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
  .nav-tabs-lihat{
    font-size: 14px;
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
          <h1 class="m-0 text-dark">Potongan Batu</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Raw Material</li>
            <li class="breadcrumb-item">Potongan Batu</li>
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
            <div class="col-11">
              <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#vendor_all" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Semua</a>
                </li>
            <?php
            foreach($data_vendor as $item){
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#vendor_{{ $item->vendorid }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">{{ $item->nama_vendor }}</a>
            </li>
            <?php
            }
            ?>
          </ul>
            </div>
            <div class="col-1">
              <a href="#" class="btn btn-primary" id="btn-save-excel" style="width: 100%;"><i class="fa fa-download"></i></a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="vendor_all" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_vendor_all_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Total Batu Non Afkir (KG)</th>
                    <th>Total Batu Afkir (KG)</th>
                    <th>Total Semua (KG)</th>
                    <th>Rata-Rata Potongan (KG)</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <?php
            foreach($data_vendor as $item){
            ?>
            <div class="tab-pane fade" id="vendor_{{ $item->vendorid }}" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_vendor_{{ $item->vendorid }}_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Total Batu Non Afkir (KG)</th>
                    <th>Total Batu Afkir (KG)</th>
                    <th>Total Semua (KG)</th>
                    <th>Rata-Rata Potongan (KG)</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_view_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="detail_judul_potongan_batu"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <table id="detail_potongan_batu_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">GRNO</th>
                    <th class="min-mobile">Vendor</th>
                    <th class="not-mobile">Total Afkir (KG)</th>
                    <th class="not-mobile">Total Semua (KG)</th>
                    <th class="not-mobile">Potongan Batu (KG)</th>
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
    let key = "{{ env('MIX_APP_KEY') }}";
    var target = $('.nav-tabs a.nav-link.active').attr("href");

    var table = $('#data_vendor_all_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("raw_material/potongan_batu/all/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0, 'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'tanggal',
           name:'tanggal',
           className: 'dt-center'
         },
         {
           data:'total_batu_non_afkir',
           name:'total_batu_non_afkir',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'total_batu_afkir',
           name:'total_batu_afkir',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'total_batu_semua',
           name:'total_batu_semua',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'rata_rata_potongan',
           name:'rata_rata_potongan',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 2)
         },
         {
           data:'action',
           name:'action',
           className: 'dt-center',
           width:'7%'
         }
       ]
     });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#vendor_all'){
        $('#data_vendor_all_table').DataTable().destroy();
        load_data_vendor_all();
      }

      var url = "{{ url('raw_material/vendor_batu/view_all') }}";
      $.get(url, function (data) {
        $.each(data, function(k, v) {
          if(target == '#vendor_'+v.vendorid){
            $('#data_vendor_'+v.vendorid+'_table').DataTable().destroy();
            load_data_vendor(v.vendorid);
          }
        });
      })
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

    function load_data_vendor_all(from_date = '', to_date = '')
    {
      table = $('#data_vendor_all_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("raw_material/potongan_batu/all/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0, 'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'tanggal',
           name:'tanggal',
           className: 'dt-center'
         },
         {
           data:'total_batu_non_afkir',
           name:'total_batu_non_afkir',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'total_batu_afkir',
           name:'total_batu_afkir',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'total_batu_semua',
           name:'total_batu_semua',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'rata_rata_potongan',
           name:'rata_rata_potongan',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 2)
         },
         {
           data:'action',
           name:'action',
           className: 'dt-center',
           width:'7%'
         }
       ]
     });
    }

    function load_data_vendor(vendorid = '', from_date = '', to_date = '')
    {
      table = $('#data_vendor_'+vendorid+'_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("raw_material/potongan_batu/table") }}',
          data:{vendorid:vendorid, from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0, 'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'tanggal',
           name:'tanggal',
           className: 'dt-center'
         },
         {
           data:'total_batu_non_afkir',
           name:'total_batu_non_afkir',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'total_batu_afkir',
           name:'total_batu_afkir',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'total_batu_semua',
           name:'total_batu_semua',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 0)
         },
         {
           data:'rata_rata_potongan',
           name:'rata_rata_potongan',
           className: 'dt-center',
           render: $.fn.dataTable.render.number('.', ',', 2)
         },
         {
           data:'action',
           name:'action',
           className: 'dt-center',
           width:'7%'
         }
       ]
     });
    }

    function load_detail_potongan_batu(tanggal=''){
      $('#detail_potongan_batu_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("raw_material/potongan_batu/detail/all/table") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        searching: false,
        columns: [
          {
            data:'grno',
            name:'grno',
            className: 'dt-center'
          },
          {
            data:'vendor',
            name:'vendor',
            className: 'dt-center'
          },
          {
            data:'total_afkir',
            name:'total_afkir',
            className: 'dt-center',
            render: $.fn.dataTable.render.number('.', ',', 0)
          },
          {
            data:'total_semua',
            name:'total_semua',
            className: 'dt-center',
            render: $.fn.dataTable.render.number('.', ',', 0)
          },
          {
            data:'potongan_batu',
            name:'potongan_batu',
            className: 'dt-center'
          }
        ]
      });
    }

    function load_detail_potongan_batu_vendor(vendorid = '', tanggal=''){
      $('#detail_potongan_batu_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("raw_material/potongan_batu/detail/table") }}',
          data:{vendorid:vendorid, tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        searching: false,
        columns: [
          {
            data:'grno',
            name:'grno',
            className: 'dt-center'
          },
          {
            data:'vendor',
            name:'vendor',
            className: 'dt-center'
          },
          {
            data:'total_afkir',
            name:'total_afkir',
            className: 'dt-center',
            render: $.fn.dataTable.render.number('.', ',', 0)
          },
          {
            data:'total_semua',
            name:'total_semua',
            className: 'dt-center',
            render: $.fn.dataTable.render.number('.', ',', 0)
          },
          {
            data:'potongan_batu',
            name:'potongan_batu',
            className: 'dt-center'
          }
        ]
      });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(target == '#vendor_all'){
        $('#data_vendor_all_table').DataTable().destroy();
        load_data_vendor_all(from_date, to_date);
      }

      var url = "{{ url('raw_material/vendor_batu/view_all') }}";
      $.get(url, function (data) {
        $.each(data, function(k, v) {
          if(target == '#vendor_'+v.vendorid){
            $('#data_vendor_'+v.vendorid+'_table').DataTable().destroy();
            load_data_vendor(v.vendorid, from_date, to_date);
          }
        });
      })
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(target == '#vendor_all'){
        $('#data_vendor_all_table').DataTable().destroy();
        load_data_vendor_all();
      }

      var url = "{{ url('raw_material/vendor_batu/view_all') }}";
      $.get(url, function (data) {
        $.each(data, function(k, v) {
          if(target == '#vendor_'+v.vendorid){
            $('#data_vendor_'+v.vendorid+'_table').DataTable().destroy();
            load_data_vendor(v.vendorid);
          }
        });
      })
    });

    $('body').on('click', '#view-data-all', function () {
      var tanggal = $(this).data("id");

      document.getElementById("detail_judul_potongan_batu").innerHTML = "Potongan Batu Tanggal " + tanggal;
      $('#detail_potongan_batu_table').DataTable().destroy();
      load_detail_potongan_batu(tanggal);
    });

    $('body').on('click', '#view-data', function () {
      var tanggal = $(this).data("id");
      var vendor = $(this).data("vendor");

      document.getElementById("detail_judul_potongan_batu").innerHTML = "Potongan Batu Tanggal " + tanggal;
      $('#detail_potongan_batu_table').DataTable().destroy();
      load_detail_potongan_batu_vendor(vendor, tanggal);
    });

    $('body').on('click', '#btn-save-excel', function () {
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');

      var url = '{{ url("raw_material/potongan_batu/excel/from_date/to_date") }}';
      url = url.replace('from_date', enc(from_date.toString()));
      url = url.replace('to_date', enc(to_date.toString()));
      $('#btn-save-excel').attr('href', url);
      window.location = url;
    });
  });
</script>

@endsection