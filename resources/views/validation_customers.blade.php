@extends('layouts.app_en')

@section('title')
<title>VALIDASI CUSTOMERS - PT. DWI SELO GIRI MAS</title>
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
  <div class="title-view"><h2>Validasi Customers</h2></div>

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
    <table class="table table-bordered table-striped" style="width:100%" id="customers_table">
           <thead>
            <tr>
                <th>Customers ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Tipe Customer</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
           </thead>
       </table>
   </div>
</div>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/validation_customers') }}";
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
      $('#customers_table').DataTable({
       processing: true,
       serverSide: true,
       ajax: {
        url:'{{ route("customers.index") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'Bfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       columns: [
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'name',
         name:'name'
        },
        {
         data:'email',
         name:'email'
        },
        {
         data:'tipe_customer',
         name:'tipe_customer'
        },
        {
         data:'address',
         name:'address'
        },
        {
         data:'city',
         name:'city'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'created_at',
         name:'created_at'
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
       $('#customers_table').DataTable().destroy();
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
      $('#customers_table').DataTable().destroy();
      load_data();
     });
   });
  </script>

  <script>
    $(document).ready(function(){
      $('body').on('click', '#validate-customers', function () {
        var custid = $(this).data("id");
        if(confirm("Data Customers Divalidasi?")){
          $.ajax({
              type: "GET",
              url: "{{ url('validasi_user') }}",
              data: { 'custid' : custid },
              success: function (data) {
                var oTable = $('#customers_table').dataTable(); 
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