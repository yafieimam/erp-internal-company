@extends('layouts.app_admin')

@section('title')
<title>OMSET SALES - PT. DWI SELO GIRI MAS</title>
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
  #omset_all_table tbody tr:hover, #omset_customers_table tbody tr:hover, #omset_group_table tbody tr:hover{
    cursor: pointer;
  }
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
          <h1 class="m-0 text-dark">Omset Sales</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Omset Sales</li>
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
            <div class="col-6">
              <select id="omset_group" name="omset_group" class="form-control">
                <option value="3" selected>Omset Customers Group and No Group (ALL)</option>
                <option value="1">Omset Customers No Group</option>
                <option value="2">Omset Customer Group</option>
              </select>
            </div>
          </div>
        </div>
        <div class="card-body detail-table" id="div_omset_all">
          <table id="omset_all_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th class="min-mobile"></th>
                <th class="min-mobile"></th>
                <th class="min-mobile">Tanggal</th>
                <th class="not-mobile">Total Customer dan Group</th>
                <th class="not-mobile">Total Tonase (TON)</th>
                <th class="min-mobile">Total Omset</th>
                <th class="not-mobile"></th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="card-body detail-table" id="div_omset_customers" style="display: none;">
          <table id="omset_customers_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th class="min-mobile"></th>
                <th class="min-mobile"></th>
                <th class="min-mobile">Tanggal</th>
                <th class="not-mobile">Total Customer</th>
                <th class="not-mobile">Total Tonase (TON)</th>
                <th class="min-mobile">Total Omset</th>
                <th class="not-mobile"></th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="card-body detail-table" id="div_omset_group" style="display: none;">
          <table id="omset_group_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th class="min-mobile"></th>
                <th class="min-mobile"></th>
                <th class="min-mobile">Tanggal</th>
                <th class="not-mobile">Total Group</th>
                <th class="not-mobile">Total Tonase (TON)</th>
                <th class="min-mobile">Total Omset</th>
                <th class="not-mobile"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_omset_all">
    <div class="modal-dialog modal-xl" id="uk_modal_omset_all">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="detail_judul_all"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="div_detail_omset_all">
            <table id="detail_omset_all_table" style="width: 100%;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th class="min-mobile">Customer</th>
                  <th class="not-mobile">Group</th>
                  <th class="not-mobile">Kode Produk</th>
                  <th class="not-mobile">Quantity (KG)</th>
                  <th class="not-mobile">Amount</th>
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

  <div class="modal fade" id="modal_omset_customer">
    <div class="modal-dialog modal-lg" id="uk_modal_omset_customer">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="detail_judul_customer"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="div_detail_omset_customer">
            <table id="detail_omset_customer_table" style="width: 100%;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th class="min-mobile">Customer</th>
                  <th class="not-mobile">Kode Produk</th>
                  <th class="not-mobile">Quantity (KG)</th>
                  <th class="not-mobile">Amount</th>
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

  <div class="modal fade" id="modal_omset_group">
    <div class="modal-dialog modal-lg" id="uk_modal_omset_group">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="detail_judul_group"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="div_detail_omset_group">
            <table id="detail_omset_group_table" style="width: 100%;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th class="min-mobile"></th>
                  <th class="min-mobile"></th>
                  <th class="min-mobile">Group</th>
                  <th class="not-mobile">Total Tonase (Tonase)</th>
                  <th class="not-mobile">Total Amount</th>
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
        <form method="post" class="upload-form" id="upload-form" action="{{ url('/uploadExcelOmsetSales') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_omset_sales.xlsx')}}" target="_blank">here</a>.</span>
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
          <form method="post" class="input_manual_form" id="input_manual_form" action="{{ url('/inputOmsetSales') }}">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="produk">Produk</label>
                  <select id="produk" name="produk" class="form-control">
                    <option value="" selected>Produk</option>
                    @foreach($produk_data as $produk)
                    <option value="{{ $produk->kode_produk }}"> {{ $produk->nama_produk }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="customer">Customer</label>
                  <select id="customer" name="customer" class="form-control select2 customer" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label>Tanggal</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="tanggal_omset" id="tanggal_omset" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="jml_tonase">Jumlah Tonase</label>
                  <input type="text" name="jml_tonase" class="form-control" id="jml_tonase" placeholder="Jumlah Tonase">
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="jml_omset">Total Omset</label>
                  <input type="text" name="jml_omset" class="form-control" id="jml_omset" placeholder="Total Omset">
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

  <div class="modal fade" id="modal_edit_omset_all">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_edit_omset_all"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="edit_data_omset_all_table" style="width: 100%; font-size: 9px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No SJ</th>
                  <th>Customer</th>
                  <th>Produk</th>
                  <th>No Invoice</th>
                  <th>TOP</th>
                  <th>Tgl Jatuh Tempo</th>
                  <th>DPP</th>
                  <th>PPN</th>
                  <th>Amount</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Diskon</th>
                  <th>Sub Amount</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-edit-data-all" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_omset_customer">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_edit_omset_customer"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="edit_data_omset_customer_table" style="width: 100%; font-size: 9px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No SJ</th>
                  <th>Customer</th>
                  <th>Produk</th>
                  <th>No Invoice</th>
                  <th>TOP</th>
                  <th>Tgl Jatuh Tempo</th>
                  <th>DPP</th>
                  <th>PPN</th>
                  <th>Amount</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Diskon</th>
                  <th>Sub Amount</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-edit-data-customer" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_omset_group">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_edit_omset_group"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="edit_data_omset_group_table" style="width: 100%; font-size: 9px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No SJ</th>
                  <th>Group ID</th>
                  <th>Customer</th>
                  <th>Produk</th>
                  <th>No Invoice</th>
                  <th>TOP</th>
                  <th>Tgl Jatuh Tempo</th>
                  <th>DPP</th>
                  <th>PPN</th>
                  <th>Amount</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Diskon</th>
                  <th>Sub Amount</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-edit-data-group" class="btn btn-primary">Save changes</button>
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

     var table = $('#omset_all_table').DataTable({
       processing: true,
       serverSide: true,
       responsive: {
          details: {
            type: 'column'
          }
        },
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("omset_all") }}',
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
          defaultContent:''
        },
        {
         data:'tanggal',
         name:'tanggal'
        },
        {
         data:'jumlah_customer',
         name:'jumlah_customer'
        },
        {
         data:'jumlah_tonase',
         name:'jumlah_tonase',
         render: $.fn.dataTable.render.number( '.', ',', 2),
         defaultContent: '<i>--</i>'
        },
        {
         data:'total_omset',
         name:'total_omset',
         render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp '),
         defaultContent: '<i>--</i>'
        },
        {
         data:'action',
         name:'action',
         width: '10%',
         className:'dt-center'
        }
       ]
      });

     $("#omset_group").change(function() {
        if ($(this).val() == 1) {
          $('#div_omset_customers').show();
          $('#div_omset_group').hide();
          $('#div_omset_all').hide();
          $('#omset_customers_table').DataTable().destroy();
          load_omset_customers();
        }else if ($(this).val() == 2) {
          $('#div_omset_customers'). hide();
          $('#div_omset_group').show();
          $('#div_omset_all').hide();
          $('#omset_group_table').DataTable().destroy();
          load_omset_group();
        }else if ($(this).val() == 3) {
          $('#div_omset_customers'). hide();
          $('#div_omset_group').hide();
          $('#div_omset_all').show();
          $('#omset_all_table').DataTable().destroy();
          load_omset_all();
        }else {
          $('#div_omset_customers').hide();
          $('#div_omset_group').hide();
          $('#div_omset_all').hide();
        }
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

     function load_omset_all(from_date = '', to_date = '')
     {
      table = $('#omset_all_table').DataTable({
       processing: true,
       serverSide: true,
       responsive: {
          details: {
            type: 'column'
          }
        },
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("omset_all") }}',
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
        },
        {
         data:'tanggal',
         name:'tanggal'
        },
        {
         data:'jumlah_customer',
         name:'jumlah_customer'
        },
        {
         data:'jumlah_tonase',
         name:'jumlah_tonase',
         render: $.fn.dataTable.render.number('.', ',', 2),
         defaultContent: '<i>--</i>'
        },
        {
         data:'total_omset',
         name:'total_omset',
         render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp '),
         defaultContent: '<i>--</i>'
        },
        {
         data:'action',
         name:'action',
         width: '10%',
         className:'dt-center'
        }
       ]
      }); 
     }

     function load_omset_customers(from_date = '', to_date = '')
     {
      table = $('#omset_customers_table').DataTable({
       processing: true,
       serverSide: true,
       responsive: {
          details: {
            type: 'column'
          }
        },
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ route("omset.index") }}',
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
            var url = '{{ url("omset_customers/excel/from_date/to_date") }}';
            url = url.replace('from_date', enc(from_date.toString()));
            url = url.replace('to_date', enc(to_date.toString()));
            window.location = url;
          }        
       }
       ],
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
        },
        {
         data:'tanggal',
         name:'tanggal'
        },
        {
         data:'jumlah_customer',
         name:'jumlah_customer'
        },
        {
         data:'jumlah_tonase',
         name:'jumlah_tonase',
         render: $.fn.dataTable.render.number( '.', ',', 2),
         defaultContent: '<i>--</i>'
        },
        {
         data:'total_omset',
         name:'total_omset',
         render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp '),
         defaultContent: '<i>--</i>'
        },
        {
         data:'action',
         name:'action',
         width: '10%',
         className:'dt-center'
        }
       ]
      }); 
     }

     function load_omset_group(from_date = '', to_date = '')
     {
      table = $('#omset_group_table').DataTable({
       processing: true,
       serverSide: true,
       responsive: {
          details: {
            type: 'column'
          }
        },
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("omset_group") }}',
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
            var url = '{{ url("omset_group/excel/from_date/to_date") }}';
            url = url.replace('from_date', enc(from_date.toString()));
            url = url.replace('to_date', enc(to_date.toString()));
            window.location = url;
          }        
       }
       ],
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
        },
        {
         data:'tanggal',
         name:'tanggal'
        },
        {
         data:'jumlah_customer',
         name:'jumlah_customer'
        },
        {
         data:'jumlah_tonase',
         name:'jumlah_tonase',
         render: $.fn.dataTable.render.number('.', ',', 2),
         defaultContent: '<i>--</i>'
        },
        {
         data:'total_omset',
         name:'total_omset',
         render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp '),
         defaultContent: '<i>--</i>'
        },
        {
         data:'action',
         name:'action',
         width: '10%',
         className:'dt-center'
        }
       ]
      }); 
     }

     function load_detail_all(tgl_detail=''){
      table = $('#detail_omset_all_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("detail_omset_all") }}',
          data:{tanggal:tgl_detail},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        searching: false,
        columns: [
          {
            data:'custid',
            name:'custid'
          },
          {
            data:'groupid',
            name:'groupid',
            defaultContent: '<i>--</i>'
          },
          {
            data:'itemid',
            name:'itemid'
          },
          {
            data:'tonase',
            name:'tonase',
            render: $.fn.dataTable.render.number('.', ',', 2),
            defaultContent: '<i>--</i>'
          },
          {
            data:'omset',
            name:'omset',
            render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp '),
            defaultContent: '<i>--</i>'
          }
        ]
      });
    }

     function load_detail_customer(tgl_detail=''){
      table = $('#detail_omset_customer_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ route("omset.create") }}',
          data:{tanggal:tgl_detail}
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        searching: false,
        columns: [
          {
            data:'custid',
            name:'custid'
          },
          {
            data:'itemid',
            name:'itemid'
          },
          {
            data:'tonase',
            name:'tonase',
            render: $.fn.dataTable.render.number( '.', ',', 2),
            defaultContent: '<i>--</i>'
          },
          {
            data:'omset',
            name:'omset',
            render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp '),
            defaultContent: '<i>--</i>'
          }
        ]
      });
    }

    function load_detail_group(tgl_detail=''){
      table = $('#detail_omset_group_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("detail_omset_group") }}',
          data:{tanggal:tgl_detail}
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        searching: false,
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
            defaultContent:''
          },
          {
            data:'groupid',
            name:'groupid'
          },
          {
            data:'jumlah_tonase',
            name:'jumlah_tonase',
            render: $.fn.dataTable.render.number( '.', ',', 2),
            defaultContent: '<i>--</i>'
          },
          {
            data:'total_omset',
            name:'total_omset',
            render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp '),
            defaultContent: '<i>--</i>'
          }
        ]
      });
    }

    function load_data_edit_omset_all(tanggal = '')
    {
      table = $('#edit_data_omset_all_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("detail_omset_all/edit") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lrtip',
        columns: [
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html();
          }
        },
        {
          data:'customer',
          name:'customer',
          className:'dt-center'
        },
        {
          data:'itemid',
          name:'itemid',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="itemid[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="' + $('<div />').text(row.itemid).html() + '">' + $('<div />').text(row.itemid).html();
          }
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="noinv[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="No Invoice" value="' + $('<div />').text(row.noinv).html() + '">';
          }
        },
        {
          data:'top',
          name:'top',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="top[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="TOP" value="' + $('<div />').text(row.top).html() + '">';
          }
        },
        {
          data:'tanggal_jatuh_tempo',
          name:'tanggal_jatuh_tempo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Tgl Jatuh Tempo" value="' + $('<div />').text(row.tanggal_jatuh_tempo).html() + '">';
          }
        },
        {
          data:'dpp',
          name:'dpp',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="dpp[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="DPP" value="' + $('<div />').text(row.dpp).html() + '">';
          }
        },
        {
          data:'ppn',
          name:'ppn',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="ppn[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="PPN" value="' + $('<div />').text(row.ppn).html() + '">';
          }
        },
        {
          data:'amount',
          name:'amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Amount" value="' + $('<div />').text(row.amount).html() + '">';
          }
        },
        {
          data:'qty',
          name:'qty',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="qty[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Qty" value="' + $('<div />').text(row.qty).html() + '">';
          }
        },
        {
          data:'price',
          name:'price',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="price[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Price" value="' + $('<div />').text(row.price).html() + '">';
          }
        },
        {
          data:'diskon',
          name:'diskon',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="diskon[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Diskon" value="' + $('<div />').text(row.diskon).html() + '">';
          }
        },
        {
          data:'sub_amount',
          name:'sub_amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="sub_amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Sub Amount" value="' + $('<div />').text(row.sub_amount).html() + '">';
          }
        }
        ]
      });
    }

    function load_data_edit_omset_customer(tanggal = '')
    {
      table = $('#edit_data_omset_customer_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("detail_omset_customer/edit") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lrtip',
        columns: [
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html();
          }
        },
        {
          data:'customer',
          name:'customer',
          className:'dt-center'
        },
        {
          data:'itemid',
          name:'itemid',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="itemid[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="' + $('<div />').text(row.itemid).html() + '">' + $('<div />').text(row.itemid).html();
          }
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="noinv[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="No Invoice" value="' + $('<div />').text(row.noinv).html() + '">';
          }
        },
        {
          data:'top',
          name:'top',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="top[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="TOP" value="' + $('<div />').text(row.top).html() + '">';
          }
        },
        {
          data:'tanggal_jatuh_tempo',
          name:'tanggal_jatuh_tempo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Tgl Jatuh Tempo" value="' + $('<div />').text(row.tanggal_jatuh_tempo).html() + '">';
          }
        },
        {
          data:'dpp',
          name:'dpp',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="dpp[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="DPP" value="' + $('<div />').text(row.dpp).html() + '">';
          }
        },
        {
          data:'ppn',
          name:'ppn',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="ppn[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="PPN" value="' + $('<div />').text(row.ppn).html() + '">';
          }
        },
        {
          data:'amount',
          name:'amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Amount" value="' + $('<div />').text(row.amount).html() + '">';
          }
        },
        {
          data:'qty',
          name:'qty',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="qty[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Qty" value="' + $('<div />').text(row.qty).html() + '">';
          }
        },
        {
          data:'price',
          name:'price',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="price[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Price" value="' + $('<div />').text(row.price).html() + '">';
          }
        },
        {
          data:'diskon',
          name:'diskon',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="diskon[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Diskon" value="' + $('<div />').text(row.diskon).html() + '">';
          }
        },
        {
          data:'sub_amount',
          name:'sub_amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="sub_amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Sub Amount" value="' + $('<div />').text(row.sub_amount).html() + '">';
          }
        }
        ]
      });
    }

    function load_data_edit_omset_group(tanggal = '')
    {
      table = $('#edit_data_omset_group_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("detail_omset_group/edit") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lrtip',
        columns: [
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="nosj[' + $('<div />').text(row.nosj).html() + ']" value="' + $('<div />').text(row.nosj).html() + '">' + $('<div />').text(row.nosj).html();
          }
        },
        {
          data:'groupid',
          name:'groupid',
          className:'dt-center'
        },
        {
          data:'customer',
          name:'customer',
          className:'dt-center'
        },
        {
          data:'itemid',
          name:'itemid',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="itemid[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" value="' + $('<div />').text(row.itemid).html() + '">' + $('<div />').text(row.itemid).html();
          }
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="noinv[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="No Invoice" value="' + $('<div />').text(row.noinv).html() + '">';
          }
        },
        {
          data:'top',
          name:'top',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="top[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="TOP" value="' + $('<div />').text(row.top).html() + '">';
          }
        },
        {
          data:'tanggal_jatuh_tempo',
          name:'tanggal_jatuh_tempo',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_jatuh_tempo[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Tgl Jatuh Tempo" value="' + $('<div />').text(row.tanggal_jatuh_tempo).html() + '">';
          }
        },
        {
          data:'dpp',
          name:'dpp',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="dpp[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="DPP" value="' + $('<div />').text(row.dpp).html() + '">';
          }
        },
        {
          data:'ppn',
          name:'ppn',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="ppn[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="PPN" value="' + $('<div />').text(row.ppn).html() + '">';
          }
        },
        {
          data:'amount',
          name:'amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Amount" value="' + $('<div />').text(row.amount).html() + '">';
          }
        },
        {
          data:'qty',
          name:'qty',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="qty[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Qty" value="' + $('<div />').text(row.qty).html() + '">';
          }
        },
        {
          data:'price',
          name:'price',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="price[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Price" value="' + $('<div />').text(row.price).html() + '">';
          }
        },
        {
          data:'diskon',
          name:'diskon',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="diskon[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Diskon" value="' + $('<div />').text(row.diskon).html() + '">';
          }
        },
        {
          data:'sub_amount',
          name:'sub_amount',
          className:'dt-center',
          render: function ( data, type, row)
          {
            return '<input type="text" name="sub_amount[' + $('<div />').text(row.nosj).html() + '][' + $('<div />').text(row.itemid).html() + ']" style="width:100%;" placeholder="Sub Amount" value="' + $('<div />').text(row.sub_amount).html() + '">';
          }
        }
        ]
      });
    }

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

    function formatGroup(d){
      var isi = '<table border="0" style="width: 100%;">'+
      '<tr>'+
      '<td width="50%">Customer</td>'+
      '<td width="15%">Kode Produk</td>'+
      '<td width="15%">Quantity</td>'+
      '<td width="20%">Amount</td>'+
      '</tr>';
      $.each(d, function(k, v) {
        isi += '<tr>'+
        '<td width="50%">' + v.custname + '</td>'+
        '<td width="15%">' + v.itemid + '</td>'+
        '<td width="15%">' + $.fn.dataTable.render.number('.', '', 0, '').display(v.jumlah_tonase) + ' KG</td>'+
        '<td width="20%">' + $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(v.total_omset) + '</td>'+
        '</tr>';
      });
      isi += '</table>';
      return isi;
    }

    $('body').on('click', '#view-data-all', function () {
      var tanggal = $(this).data("id");

      document.getElementById("detail_judul_all").innerHTML = "Detail Omset Tanggal " + tanggal;
      $('#detail_omset_all_table').DataTable().destroy();
      load_detail_all(tanggal);
    });

    $('#omset_all_table').on( 'click', 'tbody td.details-control', function () {
      table = $('#omset_all_table').DataTable();
      var tr = $(this).closest('tr');
      var row = table.row( tr );
      $.ajax({
        type: "GET",
        url: "{{ url('detail_omset_all_mesh') }}",
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

    $('body').on('click', '#edit-data-all', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_edit_omset_all").innerHTML = "Edit Omset Tanggal " + tanggal;
      $('#edit_data_omset_all_table').DataTable().destroy();
      load_data_edit_omset_all(tanggal);
    });

    $('body').on('click', '#view-data-customer', function () {
      var tanggal = $(this).data("id");

      document.getElementById("detail_judul_customer").innerHTML = "Detail Omset Tanggal " + tanggal;
      $('#detail_omset_customer_table').DataTable().destroy();
      load_detail_customer(tanggal);
    });

    $('#omset_customers_table').on( 'click', 'tbody td.details-control', function () {
      table = $('#omset_customers_table').DataTable();
      var tr = $(this).parent().closest('tr');
      var row = table.row( tr );
      $.ajax({
        type: "GET",
        url: "{{ url('detail_omset_customer_mesh') }}",
        data: { 'tanggal' : row.data()['tanggal'] },
        success: function (data) {
          if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
          }else {
            row.child( format(data.data) ).show();
            tr.addClass('shown');
          }
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#edit-data-customer', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_edit_omset_customer").innerHTML = "Edit Omset Tanggal " + tanggal;
      $('#edit_data_omset_customer_table').DataTable().destroy();
      load_data_edit_omset_customer(tanggal);
    });

    $('body').on('click', '#view-data-group', function () {
      var tanggal = $(this).data("id");

      document.getElementById("detail_judul_group").innerHTML = "Detail Omset Tanggal " + tanggal;
      $('#detail_omset_group_table').DataTable().destroy();
      load_detail_group(tanggal);
    });

    $('#omset_group_table').on( 'click', 'tbody td.details-control', function () {
      table = $('#omset_group_table').DataTable();
      var tr = $(this).closest('tr');
      var row = table.row( tr );
      $.ajax({
        type: "GET",
        url: "{{ url('detail_omset_group_mesh') }}",
        data: { 'tanggal' : row.data()['tanggal'] },
        success: function (data) {
          if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
          }else {
            row.child( format(data.data) ).show();
            tr.addClass('shown');
          }
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('#detail_omset_group_table').on( 'click', 'tbody td.details-control', function () {
      table = $('#detail_omset_group_table').DataTable();
      var tr = $(this).closest('tr');
      var row = table.row( tr );
      $.ajax({
        type: "GET",
        url: "{{ url('detail_omset_group_per_custid') }}",
        data: { 'tanggal' : row.data()['tanggal'], 'groupid' : row.data()['groupid'] },
        success: function (data) {
          if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
          }else {
            row.child( formatGroup(data.data) ).show();
            tr.addClass('shown');
          }
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#edit-data-group', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_edit_omset_group").innerHTML = "Edit Omset Tanggal " + tanggal;
      $('#edit_data_omset_group_table').DataTable().destroy();
      load_data_edit_omset_group(tanggal);
    });

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if($('#omset_group').val() == 1){
          $('#omset_customers_table').DataTable().destroy();
          load_omset_customers(from_date, to_date);
        }else if($('#omset_group').val() == 2){
          $('#omset_group_table').DataTable().destroy();
          load_omset_group(from_date, to_date);
        }else if($('#omset_group').val() == 3){
          $('#omset_all_table').DataTable().destroy();
          load_omset_all(from_date, to_date);
        }
     }
     else
     {
       alert('Both Date is required');
     }
   });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if($('#omset_group').val() == 1){
        $('#omset_customers_table').DataTable().destroy();
        load_omset_customers();
      }else if($('#omset_group').val() == 2){
        $('#omset_group_table').DataTable().destroy();
        load_omset_group();
      }else if($('#omset_group').val() == 3){
        $('#omset_all_table').DataTable().destroy();
        load_omset_all();
      }
    });

    $('#btn-edit-data-all').click( function() {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-data-all').data('token'),
        },
        type: "GET",
        url: '{{ url("detail_omset_all/edit/save") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#omset_all_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_edit_omset_all').modal('hide');
          $("#modal_edit_omset_all").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-edit-data-customer').click( function() {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-data-customer').data('token'),
        },
        type: "POST",
        url: '{{ url("detail_omset_customer/edit/save") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#omset_customers_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_edit_omset_customer').modal('hide');
          $("#modal_edit_omset_customer").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-edit-data-group').click( function() {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-data-group').data('token'),
        },
        type: "POST",
        url: '{{ url("detail_omset_group/edit/save") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          var oTable = $('#omset_group_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_edit_omset_group').modal('hide');
          $("#modal_edit_omset_group").trigger('click');
          alert('Data Successfully Updated');
        }
      });
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

    $('#tanggal_omset').flatpickr({
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
        produk: {
          required: true,
        },
        tanggal_omset: {
          required: true,
        },
        customer: {
          required: true,
        },
        jml_tonase: {
          required: true,
        },
        jml_omset: {
          required: true,
        },
      },
      messages: {
        produk: {
          required: "Produk harus diisi",
        },
        tanggal_omset: {
          required: "Tanggal harus diisi",
        },
        customer: {
          required: "Customer harus diisi",
        },
        jml_tonase: {
          required: "Jumlah Tonase harus diisi",
        },
        jml_omset: {
          required: "Total Omset harus diisi",
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
    $('.customer').select2({
      placeholder: 'Customer',
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
  $(".customer").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".customer").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
@endsection
