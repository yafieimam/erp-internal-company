@extends('layouts.app_admin')

@section('title')
<title>CALENDAR - PT. DWI SELO GIRI MAS</title>
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
<link rel="stylesheet" href="{{asset('lte/plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/fullcalendar-bootstrap/main.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" media="print">
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/fullcalendar-daygrid/main.min.css')}}" media="print">
<link rel="stylesheet" href="{{asset('lte/plugins/fullcalendar-timegrid/main.min.css')}}" media="print">
<link rel="stylesheet" href="{{asset('lte/plugins/fullcalendar-bootstrap/main.min.css')}}" media="print"> -->
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/daterangepicker/daterangepicker.css')}}"> -->
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}"> -->
<style type="text/css">
  #schedule_table tbody tr:hover{
    cursor: pointer;
  }
  .fc-event{
    cursor: pointer;
  }
  @media only screen and (max-width: 768px) {
    .fc-toolbar .fc-left, .fc-toolbar .fc-center, .fc-toolbar .fc-right {
      text-align: center;
      margin-bottom: 10px;
    }
    .fc-toolbar.fc-header-toolbar {
      display: inline-block;
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
          <h1 class="m-0 text-dark">Calendar</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Produksi</li>
            <li class="breadcrumb-item"><a href="{{ url('produksi/rencana_produksi') }}">Rencana Produksi</a></li>
            <li class="breadcrumb-item">Calendar</li>
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
            <div class="col-12">
              <div id="calendar"></div>
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
  <script src="{{asset('lte/plugins/fullcalendar/main.min.js')}}"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script> -->
  <script src="{{asset('lte/plugins/fullcalendar-daygrid/main.min.js')}}"></script>
  <script src="{{asset('lte/plugins/fullcalendar-timegrid/main.min.js')}}"></script>
  <script src="{{asset('lte/plugins/fullcalendar-interaction/main.min.js')}}"></script>
  <script src="{{asset('lte/plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
  <script type="text/javascript" src="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js"></script>
  <script type="text/javascript" src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
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
  $(document).ready(function(){
    var Calendar = FullCalendar.Calendar;
    var calendarEl = document.getElementById('calendar');
    var calendar = new Calendar(calendarEl, {
      height: "auto",
      customButtons: {
        printBtn: {
          text: 'Print',
          click: function() {
            window.print();
          }
        }
      },
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid'],
      header    : {
        left  : 'prev,next today printBtn',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      //Random default events
      events    : [
        @foreach($data as $sa)
        {
          title          : '{{ $sa->nama_mesin }}',
          start          : '{{ $sa->tanggal_rencana }}',
          backgroundColor: '#fa9657', //red
          borderColor    : '#fa9657', //red
          allDay         : true
        },
        @endforeach
      ],
      // eventRender: function(event, element) {                                          
      //   element.find('span.fc-event-title').html(element.find('span.fc-event-title').text());                   
      // }
      // eventRender : function(event, element) {
      //   $(element).each( function(val, obj) {
      //     vale = $(obj).find('span.fc-title').text().replace('<', '<').replace('>', '>');
      //     $(obj).find('span.fc-title').html(vale);
      //   });
      // }
      eventRender : function(info) {
        $(info.el).each( function(val, obj) {
          vale = $(obj).find('span.fc-title').text().replace(/&lt;/g, '<b').replace(/&gt;/g, 'r>');
          $(obj).find('span.fc-title').html(vale);
        });
      }
    });

    calendar.render();

  });
</script>

@endsection
