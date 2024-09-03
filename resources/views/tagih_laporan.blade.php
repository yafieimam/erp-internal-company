@extends('layouts.app_admin')

@section('title')
<title>LAPORAN - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Laporan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Penagihan</li>
            <li class="breadcrumb-item">Laporan</li>
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
          <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#aging_schedule" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Aging Schedule</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#overdue_paid" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Overdue Paid</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#bad_customers" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Bad Customers</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="aging_schedule" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_aging_schedule_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nomor SJ</th>
                    <th>Customers</th>
                    <th>Telat (Hari)</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="overdue_paid" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_overdue_paid_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nomor SJ</th>
                    <th>Customers</th>
                    <th>Telat (Hari)</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="bad_customers" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_bad_customers_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Customers</th>
                    <th>Rata-rata Tunggakan (Hari)</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
    var target = $('.nav-tabs a.nav-link.active').attr("href");

    var table = $('#data_aging_schedule_table').DataTable({
      processing: true,
      serverSide: true,
      lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
      ajax: {
        url:'{{ url("penagihan/aging_schedule/table") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
      },
      order: [[0,'asc']],
      dom: 'lBfrtip',
      columns: [
      {
       data:'DT_RowIndex',
       name:'DT_RowIndex',
       width: '5%',
       className:'dt-center'
      },
      {
       data:'nosj',
       name:'nosj',
       className:'dt-center',
       width: '15%'
      },
      {
       data:'custname',
       name:'custname',
       className:'dt-center'
      },
      {
       data:'telat_hari',
       name:'telat_hari',
       className:'dt-center',
       width: '20%',
       render: function(data, type, row)
       {
        return data + " Hari";
       }
      }
      ],
      buttons: [
      {
        extend: 'copy'
      },
      {
        extend: 'csv'
      },
      {
        extend: 'excel'
      },
      {
        extend: 'pdf'
      },
      {
        extend: 'print',
        customize: function ( win ) {
          $(win.document.body).css('margin', '30px');
          $(win.document.body).find('h1').css('margin-bottom', '10px');
          $(win.document.body).find('h1').css('text-align', 'center');
          $(win.document.body).find('h1').html('Laporan Aging Schedule');
        }
      }
      ]
    });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#aging_schedule'){
        $('#data_aging_schedule_table').DataTable().destroy();
        load_data_aging_schedule();
      }else if(target == '#overdue_paid'){
        $('#data_overdue_paid_table').DataTable().destroy();
        load_data_overdue_paid();
      }else if(target == '#bad_customers'){
        $('#data_bad_customers_table').DataTable().destroy();
        load_data_bad_customers();
      }
    });

    function load_data_aging_schedule()
    {
      table = $('#data_aging_schedule_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/aging_schedule/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lBfrtip',
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width: '15%'
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'telat_hari',
          name:'telat_hari',
          className:'dt-center',
          width: '20%',
          render: function(data, type, row)
          {
            return data + " Hari";
          }
        }
        ],
        buttons: [
        {
          extend: 'copy'
        },
        {
          extend: 'csv'
        },
        {
          extend: 'excel'
        },
        {
          extend: 'pdf'
        },
        {
          extend: 'print',
          customize: function ( win ) {
            $(win.document.body).css('margin', '30px');
            $(win.document.body).find('h1').css('margin-bottom', '10px');
            $(win.document.body).find('h1').css('text-align', 'center');
            $(win.document.body).find('h1').html('Laporan Aging Schedule');
          }
        }
        ]
      });
    }

    function load_data_overdue_paid()
    {
      table = $('#data_overdue_paid_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/overdue_paid/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
        dom: 'lBfrtip',
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width: '15%'
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'telat_hari',
          name:'telat_hari',
          className:'dt-center',
          width:'20%',
          render: function(data, type, row)
          {
            return data + " Hari";
          }
        }
        ],
        buttons: [
        {
          extend: 'copy'
        },
        {
          extend: 'csv'
        },
        {
          extend: 'excel'
        },
        {
          extend: 'pdf'
        },
        {
          extend: 'print',
          customize: function ( win ) {
            $(win.document.body).css('margin', '30px');
            $(win.document.body).find('h1').css('margin-bottom', '10px');
            $(win.document.body).find('h1').css('text-align', 'center');
            $(win.document.body).find('h1').html('Laporan Overdue Paid');
          }
        }
        ]
      });
    }

    function load_data_bad_customers()
    {
      table = $('#data_bad_customers_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/bad_customers/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'asc']],
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
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'rata_hari',
          name:'rata_hari',
          className:'dt-center',
          width:'30%',
          render: function(data, type, row)
          {
            return Math.round(data) + " Hari";
          }
        }
        ],
        buttons: [
        {
          extend: 'copy'
        },
        {
          extend: 'csv'
        },
        {
          extend: 'excel'
        },
        {
          extend: 'pdf'
        },
        {
          extend: 'print',
          customize: function ( win ) {
            $(win.document.body).css('margin', '30px');
            $(win.document.body).find('h1').css('margin-bottom', '10px');
            $(win.document.body).find('h1').css('text-align', 'center');
            $(win.document.body).find('h1').html('Laporan Bad Customers');
          }
        }
        ]
      });
    }
  });
</script>

@endsection