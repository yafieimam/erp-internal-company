@extends('layouts.app_en')

@section('title')
<title>VALIDASI COMPLAINT PRODUKSI - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/select2/select2.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/util.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/main.css')}}">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
  <style type="text/css">
    .tops_nheaders{
      height: 40px;
    }
  </style>

@endsection

@section('content')
<div class="container">
  <div class="title-view"><h2>Validasi Complaint Produksi</h2></div>

  <div class="row input-daterange title-view" style="justify-content: center;">
    <div class="col-md-4">
      <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
    </div>
    <div class="col-md-4">
      <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
    </div>
    <div class="col-md-2">
      <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
      <button type="button" name="refresh" id="refresh" class="btn btn-primary">Refresh</button>
    </div>
  </div>
              
  <div class="table-responsive">
    <table class="table table-bordered table-striped" style="width:100%" id="complaint_table">
           <thead>
            <tr>
                <th>No Complaint</th>
                <th>Tanggal</th>
                <th>Cust ID</th>
                <th>Nama</th>
                <th>No Surat Jalan</th>
                <th>Complaint</th>
                <th>Divisi</th>
                <th>Status</th>
                <th>Lampiran</th>
                <th>Action</th>
            </tr>
           </thead>
       </table>
   </div>
</div>

<div class="modal fade" id="modalshow" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 300px;">
        <div class="modal-header">
          <span class="login100-form-title-popup">Lihat Data Complaint</span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12">
            <table class="table table-bordered table-hover">
             <thead>
              <tr>
               <th>Nomor Complaint</th>
               <td id="td_nomor_complaint"></td>
              </tr>
              <tr>
               <th>Tanggal Produksi</th>
               <td id="td_tanggal_produksi"></td>
              </tr>
              <tr>
               <th>No Lot</th>
               <td id="td_no_lot"></td>
              </tr>
              <tr>
               <th>Mesin</th>
               <td id="td_mesin"></td>
              </tr>
              <tr>
               <th>Area</th>
               <td id="td_area"></td>
              </tr>
              <tr>
               <th>Supervisor</th>
               <td id="td_supervisor"></td>
              </tr>
              <tr>
               <th>Analisa</th>
               <td id="td_analisa"></td>
              </tr>
              <tr>
               <th>Solusi</th>
               <td id="td_solusi"></td>
              </tr>
              <tr>
               <th>Lampiran</th>
               <td id="td_lampiran"></td>
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

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/validation_complaint_produksi') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
  <script src="{{asset('app-assets/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('app-assets/js/main.js')}}"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

  <script>
    $(document).ready(function(){
     $('.input-daterange').datepicker({
      todayBtn:'linked',
      format:'yyyy-mm-dd',
      autoclose:true
     });

     load_data();

     function load_data(from_date = '', to_date = '')
     {
      $('#complaint_table').DataTable({
       processing: true,
       serverSide: true,
       ajax: {
        url:'{{ route("complaintprodval.index") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'Bfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + 'data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     $('#filter').click(function(){
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      if(from_date != '' &&  to_date != '')
      {
       $('#complaint_table').DataTable().destroy();
       load_data(from_date, to_date);
      }
      else
      {
       alert('Both Date is required');
      }
     });

     $('#refresh').click(function(){
      $('#from_date').val('');
      $('#to_date').val('');
      $('#complaint_table').DataTable().destroy();
      load_data();
     });
   });
  </script>

  <script>
    $(document).ready(function(){
      $('body').on('click', '#validate-complaint', function () {
        var nomor_complaint = $(this).data("id");
        if(confirm("Data Ini Akan Diubah Status Menjadi Done?")){
          $.ajax({
              type: "GET",
              url: "{{ url('complaint_produksi/validasi/') }}",
              data: { 'nomor_complaint' : nomor_complaint },
              success: function (data) {
                var oTable = $('#complaint_table').dataTable(); 
                oTable.fnDraw(false);
                alert("Data Validated Successfully");
              },
              error: function (data) {
                  console.log('Error:', data);
                  alert("Something Goes Wrong. Please Try Again");
              }
          });
        }
      });

      $('body').on('click', '#lihat-complaint', function () {
        var url = "{{ route('complaintprod.show', 'no_complaint') }}";
        var nomor_complaint = $(this).data('id');
        url = url.replace('no_complaint', nomor_complaint);
        $('#td_nomor_complaint').html('');
        $('#td_tanggal_produksi').html('');
        $('#td_no_lot').html('');
        $('#td_mesin').html('');
        $('#td_area').html('');
        $('#td_supervisor').html('');
        $('#td_analisa').html('');
        $('#td_solusi').html('');
        $('#td_lampiran').html('');
        $.get(url, function (data) {
          $('#modalshow').modal('show');
          $('#td_nomor_complaint').html(data.nomor_complaint);
          $('#td_tanggal_produksi').html(data.tanggal_produksi);
          $('#td_no_lot').html(data.no_lot);
          $('#td_mesin').html(data.mesin);
          $('#td_area').html(data.area);
          $('#td_supervisor').html(data.supervisor);
          $('#td_analisa').html(data.analisa);
          $('#td_solusi').html(data.solusi);
          $('#td_lampiran').html('<a target="_blank" href="' + 'data_file/' + data.lampiran + '">Lihat Lampiran</a>')
        })
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
@endsection