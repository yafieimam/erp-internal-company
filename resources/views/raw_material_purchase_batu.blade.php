@extends('layouts.app_admin')

@section('title')
<title>PURCHASE BATU - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/daterangepicker/daterangepicker.css')}}"> -->
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}"> -->
<style type="text/css">
  .modal {
    overflow-y: auto !important;
  }
  td.details-control {
    background: url("{{asset('app-assets/images/icons/details_open.png')}}") no-repeat center center;
    cursor: pointer;
  }
  tr.shown td.details-control {
      background: url("{{asset('app-assets/images/icons/details_close.png')}}") no-repeat center center;
  }
  div.dataTables_length {
    margin-top: 5px;
    margin-right: 1em;
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
    .omset-btn {
      margin-bottom: 10px;
    }
    .detail-table {
      overflow-x:auto;
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
          <h1 class="m-0 text-dark">Purchase Batu</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Raw Material</li>
            <li class="breadcrumb-item">Purchase Batu</li>
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
            <div class="col-3">
              <button type="button" name="btn_upload_excel" id="btn_upload_excel" class="btn btn-block btn-primary omset-btn" data-toggle="modal" data-target="#modal_upload_excel">Upload Excel</button>
            </div>
            <div class="col-3">
              <button type="button" name="btn_input_manual" id="btn_input_manual" class="btn btn-block btn-primary omset-btn" data-toggle="modal" data-target="#modal_input_manual">Input Manual</button>
            </div>
          </div>
        </div>
        <div class="card-body detail-table">
          <table id="purchase_batu_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th class="min-mobile"></th>
                <th class="min-mobile"></th>
                <th class="min-mobile">Tanggal</th>
                <th class="min-mobile">Total Purchase</th>
                <th class="min-mobile">Total Tonase (KG)</th>
                <th class="min-mobile"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_view_data">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="detail_judul_purchase_batu"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="div_detail_purchase_batu">
            <table id="detail_purchase_batu_table" style="width: 100%;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th class="not-mobile">GRNO</th>
                  <th class="min-mobile">Vendor</th>
                  <th class="not-mobile">Item Batu</th>
                  <th class="not-mobile">Tonase (KG)</th>
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

  <div class="modal fade" id="modal_upload_excel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Excel</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" class="upload-form" id="upload-form" action="{{ url('raw_material/purchase_batu/upload_excel') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_purchase_batu.xlsx')}}" target="_blank">here</a>.</span>
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
    <div class="modal-dialog modal-lg">
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
              <div class="col-6">
                <div class="form-group">
                  <label for="batu">Jenis Batu</label>
                  <select id="batu" name="batu" class="form-control">
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="vendor">Vendor Batu</label>
                  <select id="vendor" name="vendor" class="form-control select2 vendor" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="grno">GRNO</label>
                  <input type="text" name="grno" class="form-control" id="grno" placeholder="GRNO">
                </div>
              </div>
              <div class="col-4">
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
              <div class="col-4">
                <div class="form-group">
                  <label for="tonase">Tonase</label>
                  <input type="text" name="tonase" class="form-control" id="tonase" placeholder="Tonase">
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
  <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
  <!-- <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.js"></script> -->
  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
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
    $(document).ready(function(){
      let key = "{{ env('MIX_APP_KEY') }}";

     var table = $('#purchase_batu_table').DataTable({
       processing: true,
       serverSide: true,
       responsive: {
          details: {
            type: 'column'
          }
        },
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("raw_material/purchase_batu/table") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
       },
       order: [[2, 'desc']],
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'pdf', 'print',
       {
          text: 'Excel',
          action: function ( e, dt, button, config ) {
            var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
            var url = '{{ url("omset_all/excel/from_date/to_date") }}';
            url = url.replace('from_date', enc(from_date.toString()));
            url = url.replace('to_date', enc(to_date.toString()));
            window.location = url;
          }        
       }
       ],
       columns: [
        {
          className: 'control dt-center',
          orderable: false,
          targets: 0,
          defaultContent:'',
        },
        {
          
          className:'details-control dt-center',
          orderable:false,
          searchable:false,
          data:null,
          defaultContent:''
        },
        {
         data:'tanggal',
         name:'tanggal',
         className:'dt-center'
        },
        {
         data:'total_purchase',
         name:'total_purchase',
         defaultContent: '<i>--</i>',
         className:'dt-center'
        },
        {
         data:'total_tonase',
         name:'total_tonase',
         render: $.fn.dataTable.render.number( '.', ',', 0),
         defaultContent: '<i>--</i>',
         className:'dt-center'
        },
        {
         data:'action',
         name:'action',
         className:'dt-center',
         width:'7%'
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

     function load_purchase_batu(from_date = '', to_date = '')
     {
      table = $('#purchase_batu_table').DataTable({
       processing: true,
       serverSide: true,
       responsive: {
          details: {
            type: 'column'
          }
        },
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("raw_material/purchase_batu/table") }}',
        data:{from_date:from_date, to_date:to_date},
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
       },
       order: [[2, 'desc']],
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'pdf', 'print',
       {
          text: 'Excel',
          action: function ( e, dt, button, config ) {
            var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
            var url = '{{ url("omset_all/excel/from_date/to_date") }}';
            url = url.replace('from_date', enc(from_date.toString()));
            url = url.replace('to_date', enc(to_date.toString()));
            window.location = url;
          }        
       }
       ],
       columns: [
        {
          className: 'control dt-center',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
          className:'details-control dt-center',
          orderable:false,
          searchable:false,
          data:null,
          defaultContent:'',
        },
        {
         data:'tanggal',
         name:'tanggal',
         className:'dt-center'
        },
        {
         data:'total_purchase',
         name:'total_purchase',
         defaultContent: '<i>--</i>',
         className:'dt-center'
        },
        {
         data:'total_tonase',
         name:'total_tonase',
         render: $.fn.dataTable.render.number('.', ',', 0),
         defaultContent: '<i>--</i>',
         className:'dt-center'
        },
        {
         data:'action',
         name:'action',
         className:'dt-center',
         width:'7%'
        }
       ]
      }); 
     }

     function load_detail_purchase_batu(tanggal=''){
      $('#detail_purchase_batu_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("raw_material/purchase_batu/detail/table") }}',
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
            name:'grno'
          },
          {
            data:'vendor',
            name:'vendor'
          },
          {
            data:'item_batu',
            name:'item_batu'
          },
          {
            data:'tonase',
            name:'tonase',
            render: $.fn.dataTable.render.number('.', ',', 0),
            defaultContent: '<i>--</i>'
          }
        ]
      });
    }

    $('body').on('click', '#btn_input_manual', function () {
      var url = "{{ url('raw_material/item_batu/dropdown') }}";
      $.get(url, function (data) {
        $('#batu').children().remove().end().append('<option value="" selected>Pilih Jenis Batu</option>');
        $.each(data, function(k, v) {
          $('#batu').append('<option value="' + v.kode_batu + '">' + v.nama_batu + '</option>');
        });
      })
    });

    function format(d){
      Object.keys(d[0]).forEach(function(key) {
        if(d[0][key] === null) {
          d[0][key] = 0;
        }
      });
      return '<table border="0" style="width: 100%;">'+
      '<tr>'+
      '<td width="14%">Tonase M 250:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['250_tonase'])+' TON</td>'+
      '<td width="14.5%" style="color: blue;">Value M 250:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['250_value'])+'</td>'+
      '<td>Tonase M 2002:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['2002_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value M 2002:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['2002_value'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Tonase M 325:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['325_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value M 325:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['325_value'])+'</td>'+
      '<td width="14%">Tonase M 6000:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['6000_tonase'])+' TON</td>'+
      '<td width="14.5%" style="color: blue;">Value M 6000:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['6000_value'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Tonase M 500:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['500_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value M 500:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['500_value'])+'</td>'+
      '<td>Tonase DCB-25:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['dcb25_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value DCB-25:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['dcb25_value'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Tonase M 800:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['800_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value M 800:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['800_value'])+'</td>'+
      '<td>Tonase DCD-25:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['dcd25_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value DCD-25:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['dcd25_value'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Tonase M 1200:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['1200_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value M 1200:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['1200_value'])+'</td>'+
      '<td>Tonase DCD-50:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['dcd50_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value DCD-50:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['dcd50_value'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Tonase M 1200J:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['1200_j_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value M 1200J:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['1200_j_value'])+'</td>'+
      '<td>Tonase DCD-800:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['dcd800_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value DCD-800:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['dcd800_value'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Tonase M 1500:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['1500_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value M 1500:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['1500_value'])+'</td>'+
      '<td>Tonase SSS:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['sss_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value SSS:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['sss_value'])+'</td>'+
      '</tr>'+
      '<tr>'+
      '<td>Tonase M 2000:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['2000_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value M 2000:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['2000_value'])+'</td>'+
      '<td>Tonase SWAA:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['swaa_tonase'])+' TON</td>'+
      '<td style="color: blue;">Value SWAA:</td>'+
      '<td style="color: blue;">'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(d[0]['swaa_value'])+'</td>'+
      '</tr>'+
      '</table>';
    }

    $('body').on('click', '#view-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("detail_judul_purchase_batu").innerHTML = "Purchase Batu Tanggal " + tanggal;
      $('#detail_purchase_batu_table').DataTable().destroy();
      load_detail_purchase_batu(tanggal);
    });

    $('#purchase_batu_table').on( 'click', 'tbody td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row( tr );
      $.ajax({
        type: "GET",
        url: "{{ url('raw_material/purchase_batu/view/tonase') }}",
        data: { 'tanggal' : row.data()['tanggal'] },
        success: function (data) {
          if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
          }else {
            row.child( format(data) ).show();
            tr.addClass('shown');
          }
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#purchase_batu_table').DataTable().destroy();
        load_purchase_batu(from_date, to_date);
     }
     else
     {
       alert('Both Date is required');
     }
   });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#purchase_batu_table').DataTable().destroy();
      load_purchase_batu();
    });

  });
</script>

<script type="text/javascript">
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

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    $('#input_manual_form').validate({
      rules: {
        batu: {
          required: true,
        },
        tanggal: {
          required: true,
        },
        vendor: {
          required: true,
        },
        tonase: {
          required: true,
        },
        grno: {
          required: true,
        },
      },
      messages: {
        batu: {
          required: "Jenis Batu Harus Diisi",
        },
        tanggal: {
          required: "Tanggal Harus Diisi",
        },
        vendor: {
          required: "Vendor Harus Diisi",
        },
        tonase: {
          required: "Tonase Harus Diisi",
        },
        grno: {
          required: "GRNO Harus Diisi",
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
          url:"{{ url('raw_material/purchase_batu/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_manual_form').trigger("reset");
            var oTable = $('#purchase_batu_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_input_manual").modal('hide');
            $("#modal_input_manual").trigger('click');
            alert("Data Successfully Stored");
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

<script>
  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('.vendor').select2({
      dropdownParent: $('#modal_input_manual .modal-content'),
      placeholder: 'Vendor Batu',
      allowClear: true,
      ajax: {
        url: '/raw_material/vendor_batu/dropdown',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.nama_vendor,
                id: item.vendorid
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
  $(".vendor").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Nama Vendor Batu...");
  });
  $(".vendor").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
@endsection
