@extends('layouts.app_en')

@section('title')
<title>LIHAT OMSET SALES - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
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
    #omset_table tbody tr:hover{
      cursor: pointer;
      background-color: #d7e3e7;
    }
  </style>

@endsection

@section('content')
<div class="container">
  <div class="title-view"><h2>View Omset Sales</h2></div>

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
    <table class="table table-bordered table-striped" style="width: 100%; "id="omset_table">
           <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total Customer</th>
                <th>Total Tonase (TON)</th>
                <th>Total Omset</th>
            </tr>
           </thead>
       </table>
   </div>

   <div class="modal fade" tabindex="-1" id="modal-omset" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content" style="margin-top: 300px;">
        <div class="modal-header">
          <span class="login100-form-title-popup" id="detail_judul"></span>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered table-striped" style="width:100%" id="detail_omset_table">
           <thead>
            <tr>
                <th>Tanggal</th>
                <th>Customer ID</th>
                <th>Kode Produk</th>
                <th>Quantity (KG)</th>
                <th>Amount</th>
            </tr>
           </thead>
          </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
</div>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/view_omset_sales') }}";
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

     var table = $('#omset_table').DataTable({
       processing: true,
       serverSide: true,
       ajax: {
        url:'{{ route("omset.index") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
       },
       dom: 'Bfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       columns: [
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
         name:'jumlah_tonase'
        },
        {
         data:'total_omset',
         name:'total_omset'
        }
       ]
      });

     function load_data(from_date = '', to_date = '')
     {
      table = $('#omset_table').DataTable({
       processing: true,
       serverSide: true,
       ajax: {
        url:'{{ route("omset.index") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'Bfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       columns: [
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
         name:'jumlah_tonase'
        },
        {
         data:'total_omset',
         name:'total_omset'
        }
       ]
      });
     }

     function load_detail(tgl_detail='', id_omset=''){
      $('#detail_omset_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url:'{{ route("omset.create") }}',
          data:{tanggal:tgl_detail, id:id_omset}
        },
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        searching: false,
        columns: [
          {
            data:'tanggal',
            name:'tanggal'
          },
          {
            data:'custid',
            name:'custid'
          },
          {
            data:'kode_produk',
            name:'kode_produk'
          },
          {
            data:'tonase',
            name:'tonase'
          },
          {
            data:'omset',
            name:'omset'
          }
        ]
      });
    }

     $('#omset_table').on( 'click', 'tbody tr', function () {
          console.log(table.row(this).data());
          var omset = table.row(this).data();
          var tgl_detail = omset['tanggal'];
          var id_omset = omset['id'];

          document.getElementById("detail_judul").innerHTML = "Detail Omset Tanggal " + tgl_detail;
          $('#modal-omset').modal('show');
          $('#detail_omset_table').DataTable().destroy();
          load_detail(tgl_detail, id_omset);
      });

     $('#filter').click(function(){
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      if(from_date != '' &&  to_date != '')
      {
       $('#omset_table').DataTable().destroy();
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
      $('#omset_table').DataTable().destroy();
      load_data();
     });
   });
  </script>
@endsection