@extends('layouts.app_en')

@section('title')
<title>LIHAT COMPLAINT LAINNYA - PT. DWI SELO GIRI MAS</title>
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
    .vertical-alignment-helper {
        display:table;
        height: 100%;
        width: 100%;
    }
    .vertical-align-center {
        /* To center vertically */
        display: table-cell;
        vertical-align: middle;
    }
    .form-row {
    margin: 0 -30px; }

    .form-row .form-group {
      width: 50%;
      padding: 0 30px; }

    textarea {
      box-sizing: border-box;
      border: 1px solid #ccc;
      width: 100%;
      padding: 14px 20px;
      border-radius: 5px;
      -moz-border-radius: 5px;
      -webkit-border-radius: 5px;
      -o-border-radius: 5px;
      -ms-border-radius: 5px;
      font-size: 14px;
      font-family: 'Poppins'; }
      textarea:focus {
        border: 1px solid #1d4990; }

      .error{ color:red; }

    select {
        box-sizing: border-box;
        width: 100%;
        border: 1px solid #ebebeb;
        padding: 8px 10px;
        border-radius: 5px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        -o-border-radius: 5px;
        -ms-border-radius: 5px; }
        select:focus {
          border: 1px solid #1d4990; }

      label.required {
        position: relative; }
        label.required:after {
          content: '*';
          margin-left: 2px;
          color: #b90000; }
  </style>

@endsection

@section('content')
<div class="container">
  <div class="title-view"><h2>View Complaint Lainnya</h2></div>

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

<!-- Modal -->
  <div class="modal fade" tabindex="-1" id="form-complaint" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->

      <div class="modal-content" style="margin-top: 300px;">
        <div class="modal-header">
          <span class="login100-form-title-popup">Form Complaint Lainnya</span>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          @if ($errors->any())
            <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form id="lainnyaform" name="lainnyaform" method="post" enctype="multipart/form-data" action="javascript:void(0)">
            <input class="form-control" type="hidden" name="nomor_complaint" id="nomor_complaint" />
            <input class="form-control" type="hidden" name="custid" id="custid" />
            <input class="form-control" type="hidden" name="no_divisi" id="no_divisi" />
            <div class="form-group validate-input" data-validate = "Divisi Complaint is required">
              <label for="area" class="required">Divisi Complaint</label>
              <select class="dropdown-baru" name="divisi_complaint" id="divisi_complaint">
                <option class="tipeArea" value="" selected>Divisi Complaint</option>
                <option class="tipeArea lny" value="3">Lainnya</option>
              </select>
            </div>
            <div id="form-complaint-lainnya" style="display: none;">
            <div class="form-group validate-input" data-validate = "Divisi is required">
              <label for="divisi" class="required">Divisi</label>
              <input class="form-control" type="text" name="divisi" id="divisi" placeholder="Divisi" />
            </div>
            <div class="form-group validate-input" data-validate = "Analisa is required">
              <label for="analisa" class="required">Analisa</label>
              <textarea class="form-control" name="analisa" id="analisa" placeholder="Analisa"></textarea>
            </div>
            <div class="form-group validate-input" data-validate = "Solusi is required">
              <label for="solusi" class="required">Solusi</label>
              <textarea class="form-control" name="solusi" id="solusi" placeholder="Solusi"></textarea>
            </div>
            <div class="form-group">
              <label for="upload_file">Lampiran</label>
              <input class="input-baru" type="file" name="upload_file" id="upload_file" placeholder="Lampiran" />
            </div>
            <input type="submit" class="btn btn-success btn-block" id="submit-lainnya" placeholder="Submit">
            <input type="reset" class="btn btn-primary btn-block" placeholder="Reset">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        </div>
      </div>
      
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
               <th>Divisi</th>
               <td id="td_divisi"></td>
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
    var baseurl = "{{ url('/view_complaint_lainnya') }}";
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
        url:'{{ route("complaintlain.index") }}',
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
      $('body').on('click', '#proses-complaint', function () {
        var complaint = $(this).data('id');
        var divisi = $(this).data('divisi');
        var custid = $(this).data('custid');
        $('#nomor_complaint').val(complaint);
        $('#custid').val(custid);
        $('#no_divisi').val(divisi);

        if (divisi == 2){
          $('.lny').show();
        }else if (divisi == 4){
          $('.lny').show();
        }else if (divisi == 6){
          $('.lny').show();
        }else if (divisi == 7){
          $('.lny').show();
        }else if (divisi == 8){
          $('.lny').show();
        }else{
          $('.lny').hide();
        }


        $("#divisi_complaint").change(function() {
          if ($(this).val() == 1) {
            $('#form-complaint-lainnya').hide();
          }else if ($(this).val() == 2) {
            $('#form-complaint-lainnya').hide();
          }else if ($(this).val() == 3) {
            $('#form-complaint-lainnya').show();
          }else {
            $('#form-complaint-lainnya').hide();
          }
        });
      });

      $('body').on('click', '#lihat-complaint', function () {
        var url = "{{ route('complaintlain.show', 'no_complaint') }}";
        var nomor_complaint = $(this).data('id');
        url = url.replace('no_complaint', nomor_complaint);
        $('#td_nomor_complaint').html('');
        $('#td_divisi').html('');
        $('#td_analisa').html('');
        $('#td_solusi').html('');
        $('#td_lampiran').html('');
        $.get(url, function (data) {
          $('#modalshow').modal('show');
          $('#td_nomor_complaint').html(data.nomor_complaint);
          $('#td_divisi').html(data.divisi);
          $('#td_analisa').html(data.analisa);
          $('#td_solusi').html(data.solusi);
          $('#td_lampiran').html('<a target="_blank" href="' + 'data_file/' + data.lampiran + '">Lihat Lampiran</a>')
        })
      });
   });
  </script>

  <script type="text/javascript">
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    if($("#lainnyaform").length > 0){
      $("#lainnyaform").validate({
        rules: { 
          divisi: {
            required: true,
          },  
          analisa: {
            required: true,
          },  
          solusi: {
            required: true,
          },    
        },
        messages: {
          divisi: {
            required: "Divisi is required",
          },  
          analisa: {
            required: "Analisa is required",
          },  
          solusi: {
            required: "Solusi is required",
          },   
             
        },
        submitHandler: function(form) {

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      var myform = document.getElementById("lainnyaform");
      var formdata = new FormData(myform);

      $.ajax({
        type:'POST',
        url:"{{ url('/complaint_lainnya/store') }}",
        data: formdata,
        processData: false,
        contentType: false,
        success:function(data){
          $('#lainnyaform').trigger("reset");
          $('#form-complaint-lainnya').hide();
          $('#form-complaint').modal('hide');
          var oTable = $('#complaint_table').dataTable();
          oTable.fnDraw(false);
          alert("Data Successfully Stored");
        },
        error: function (data) {
          console.log('Error:', data);
          $('#lainnyaform').trigger("reset");
          $('#form-complaint-lainnya').hide();
          $('#form-complaint').modal('hide');
          var oTable = $('#complaint_table').dataTable();
          oTable.fnDraw(false);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    }
    })
    }
  </script>

  <script>
    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>
@endsection