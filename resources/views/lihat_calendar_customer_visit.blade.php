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
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item"><a href="{{ url('/sales/schedule') }}">Customers Follow Up</a></li>
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

  <div class="modal fade" id="modal_calendar">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detail Schedule</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12">
            <table class="table table-bordered table-hover">
             <thead>
              <tr>
               <th>ID Schedule</th>
               <td id="td_id_schedule"></td>
             </tr>
             <tr>
               <th>Customers</th>
               <td id="td_customers"></td>
             </tr>
             <tr>
               <th>Perihal</th>
               <td id="td_perihal"></td>
             </tr>
             <tr>
               <th>Keterangan</th>
               <td id="td_keterangan"></td>
             </tr>
             <tr>
               <th>Result</th>
               <td id="td_result"></td>
             </tr>
             <tr>
               <th>Alasan Suspend</th>
               <td id="td_alasan_suspend"></td>
             </tr>
             <tr>
               <th>Tanggal Input</th>
               <td id="td_tanggal_input"></td>
             </tr>
             <tr>
               <th>Tanggal Schedule</th>
               <td id="td_tanggal_schedule"></td>
             </tr>
             <tr>
               <th>Tanggal Done</th>
               <td id="td_tanggal_done"></td>
             </tr>
             <tr>
               <th>Status</th>
               <td id="td_status"></td>
             </tr>
           </thead>
         </table>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    var color_jadwal = null;
    var semua_hari = true;
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
        @foreach($data_schedule as $schedule)
          @if($schedule->no_status == 1) 
            color_jadwal = '#fffd94',
          @elseif($schedule->no_status == 2) 
            color_jadwal = '#fac870',
          @elseif($schedule->no_status == 3) 
            color_jadwal = '#d4fab6',
          @elseif($schedule->no_status == 5) 
            color_jadwal = '#b6fad1',
          @endif

          @if($schedule->waktu_schedule == '' || $schedule->waktu_schedule == null)
            semua_hari = true,
          @else
            semua_hari = false,
          @endif
        {
          title          : '{{ $schedule->perihal }} ' + '<br>' + ' {{ str_replace("\n", '', $schedule->nama) }}',
          id_schedule    : '{{ $schedule->id_schedule }}', 
          tanggal_input  : '{{ $schedule->tanggal_input }}',
          perihal        : '{{ $schedule->perihal }}',
          keterangan     : '{{ $schedule->keterangan }}',
          custid         : '{{ str_replace("\n", '', $schedule->nama) }}',
          status         : '{{ $schedule->status }}',
          hasil          : '{{ $schedule->result }}',
          alasan_suspend : '{{ $schedule->alasan_suspend }}',
          waktu_schedule : '{{ $schedule->waktu_schedule }}',
          start          : '{{ date("Y-m-d H:i:s", strtotime("$schedule->tanggal_schedule $schedule->waktu_schedule")) }}',
          backgroundColor: color_jadwal, //red
          borderColor    : color_jadwal, //red
          allDay         : semua_hari
        },
        @endforeach
      ],
      eventRender: function (info) {
        $(info.el).each( function(val, obj) {
          vale = $(obj).find('span.fc-title').text().replace(/&lt;/g, '<b').replace(/&gt;/g, 'r>');
          $(obj).find('span.fc-title').html(vale);
        });
        $(info.el).attr("data-toggle", "modal");
        $(info.el).attr("data-target", "#modal_calendar");
        $('#td_id_schedule').html('');
        $('#td_customers').html('');
        $('#td_perihal').html('');
        $('#td_keterangan').html('');
        $('#td_result').html('');
        $('#td_alasan_suspend').html('');
        $('#td_tanggal_input').html('');
        $('#td_tanggal_schedule').html('');
        $('#td_tanggal_done').html('');
        $('#td_status').html('');
        $(info.el).click(function(){
          $('#td_status').html(info.event.extendedProps.status);
          $('#td_id_schedule').html(info.event.extendedProps.id_schedule);
          $('#td_customers').html(info.event.extendedProps.custid);
          $('#td_perihal').html(info.event.extendedProps.perihal);
          if(info.event.extendedProps.keterangan == null || info.event.extendedProps.keterangan == ''){
            $('#td_keterangan').html('----');
          }else{
            $('#td_keterangan').html(info.event.extendedProps.keterangan);
          }
          if(info.event.extendedProps.result == null || info.event.extendedProps.result == ''){
            $('#td_result').html('----');
          }else{
            $('#td_result').html(info.event.extendedProps.result);
          }
          if(info.event.extendedProps.alasan_suspend == null || info.event.extendedProps.alasan_suspend == ''){
            $('#td_alasan_suspend').html('----');
          }else{
            $('#td_alasan_suspend').html(info.event.extendedProps.alasan_suspend);
          }
          if(info.event.extendedProps.tanggal_done == null || info.event.extendedProps.tanggal_done == ''){
            $('#td_tanggal_done').html('----');
          }else{
            $('#td_tanggal_done').html(info.event.extendedProps.tanggal_done);
          }
          if(info.event.extendedProps.waktu_schedule == null || info.event.extendedProps.waktu_schedule == ''){
            $('#td_tanggal_schedule').html(moment(info.event.start).format('YYYY-MM-DD'));
          }else{
            $('#td_tanggal_schedule').html(moment(info.event.start).format('YYYY-MM-DD HH:mm'));
          }
          $('#td_tanggal_input').html(info.event.extendedProps.tanggal_input);
        });
      }
      // eventMouseover: function(event, jsEvent, view) {
      //   $('.fc-event-inner', this).append('<div id=\"'+event.id+'\" class=\"hover-end\">'+$.fullCalendar.formatDate(event.end, 'h:mmt')+'</div>');
      // },
      // eventMouseout: function(event, jsEvent, view) {
      //   $('#'+event.id).remove();
      // }
      // eventRender: function(info) {
      //   console.log(info);
      //   var tooltip = new Tooltip(info.el, {
      //     title: info.event.extendedProps.description,
      //     placement: 'top',
      //     trigger: 'hover',
      //     container: 'body'
      //   });
      // }
    });

    calendar.render();

  });
</script>

@endsection
