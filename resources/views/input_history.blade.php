@extends('layouts.app_admin')

@section('title')
<title>SALES HISTORY - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/daterangepicker/daterangepicker.css')}}"> -->
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}"> -->
<style type="text/css">
  #sales_history_table tbody tr:hover{
    cursor: pointer;
  }
  .form-group-agent {
    margin-right: 20px;
    margin-bottom: 0 !important;
    /*margin-top: 1rem;
    margin-left: 1rem;
*/  }
  .modal {
    overflow-y: auto !important;
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
          <h1 class="m-0 text-dark">History</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">History</li>
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
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <table id="sales_history_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>Tanggal</th>
                    <th>History</th>
                    <th>Nomor</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_lihat_followup">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_lihat_followup"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12">
            <table class="table table-bordered" style="border: none;">
             <thead>
               <tr>
                 <th>Nomor Follow Up : </th>
                 <td id="td_no_followup"></td>
               </tr>
               <tr>
                 <th>Tanggal : </th>
                 <td id="td_tanggal_followup"></td>
               </tr>
               <tr>
                 <th>Nama Customer : </th>
                 <td id="td_nama_followup"></td>
               </tr>
               <tr>
                 <th>Aktivitas : </th>
                 <td id="td_aktivitas_followup"></td>
               </tr>
               <tr>
                 <th>Informasi : </th>
                 <td id="td_informasi_followup"></td>
               </tr>
             </thead>
           </table>
         </div>
       </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_lihat_history">Close</button>
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
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!-- <script src="{{asset('lte/plugins/daterangepicker/daterangepicker.js')}}"></script> -->
<!--   <script src="{{asset('lte/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script> -->
  <script>
    $(document).ready(function(){
      var id_user_admin = "{{ Session::get('id_user_admin')}}";
      let key = "{{ env('MIX_APP_KEY') }}";

      var table = $('#sales_history_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("history_sales") }}',
          data:{id_user_admin:id_user_admin},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[1,'desc']],
        searching: false,
        createdRow: function (row, data, dataIndex) {
          if(data['kode_history'] == 8){
            $('td', row).attr('data-dismiss', 'modal');
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_followup');
            $('td', row).eq(0).removeAttr('data-dismiss');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
          }
        },
        columns: [
          {
            className: 'control',
            orderable: false,
            targets: 0,
            defaultContent:''
          },
          {
            data:'tanggal',
            name:'tanggal'
          },
          {
            data:'history',
            name:'history'
          },
          {
            data:'nomor',
            name:'nomor'
          }
        ]
      });

      function load_history(from_date='', to_date='', id_user_admin=''){
        table = $('#sales_history_table').DataTable({
          processing: true,
          serverSide: true,
          responsive: {
            details: {
              type: 'column'
            }
          },
          lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
          ajax: {
            url:'{{ url("history_sales") }}',
            data:{from_date:from_date, to_date:to_date, id_user_admin:id_user_admin},
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
          },
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          order: [[0,'desc']],
          searching: false,
          createdRow: function (row, data, dataIndex) {
            if(data['kode_history'] == 8){
              $('td', row).attr('data-dismiss', 'modal');
              $('td', row).attr('data-toggle', 'modal');
              $('td', row).attr('data-target', '#modal_lihat_followup');
              $('td', row).eq(0).removeAttr('data-dismiss');
              $('td', row).eq(0).removeAttr('data-toggle');
              $('td', row).eq(0).removeAttr('data-target');
            }
          },
          columns: [
          {
            className: 'control',
            orderable: false,
            targets: 0,
            defaultContent:''
          },
          {
            data:'tanggal',
            name:'tanggal'
          },
          {
            data:'history',
            name:'history'
          },
          {
            data:'nomor',
            name:'nomor'
          }
          ]
        });
      }

      function enc(plainText){
        let iv = CryptoJS.lib.WordArray.random(16),
        key_key = CryptoJS.enc.Utf8.parse(key.substr(7));
        let options = {
          iv: iv,
          mode: CryptoJS.mode.CBC,
          padding: CryptoJS.pad.Pkcs7
        };
        let encrypted = CryptoJS.AES.encrypt(plainText, key_key, options);
        encrypted = encrypted.toString();
        iv = CryptoJS.enc.Base64.stringify(iv);
        let result = {
          iv: iv,
          value: encrypted,
          mac: CryptoJS.HmacSHA256(iv + encrypted, key_key).toString()
        }
        result = JSON.stringify(result);
        result = CryptoJS.enc.Utf8.parse(result);
        return CryptoJS.enc.Base64.stringify(result);
      }

      function encryptMethodLength_func() {
        var encryptMethod = 'AES-256-CBC';
        var aesNumber = encryptMethod.match(/\d+/)[0];
        return parseInt(aesNumber);
      }

      function enkrip(plainText){
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

      $('#sales_history_table').on( 'click', 'tbody td:not(:first-child)', function () {
        var history = table.row(this).data();
        var tipe_history = history['kode_history'];
        var nomor = history['nomor'];
        if(tipe_history == 1){
          var url = "{{ url('sales/orders/any_number') }}";
          url = url.replace('any_number', enc(nomor));
          window.location.href = url;
        }else if(tipe_history == 2){
          var url = "{{ url('sales/customers/any_number') }}";
          url = url.replace('any_number', enc(nomor));
          window.location.href = url;
        }else if(tipe_history == 3){
          var url = "{{ url('sales/complaint/any_number/type') }}";
          url = url.replace('any_number', enc(nomor));
          url = url.replace('type', enc(tipe_history));
          window.location.href = url;
        }else if(tipe_history == 6){
          var url = "{{ url('sales/complaint/any_number/type') }}";
          url = url.replace('any_number', enc(nomor));
          url = url.replace('type', enc(tipe_history));
          window.location.href = url;
        }else if(tipe_history == 10){
          var url = "{{ url('sales/complaint/any_number/type') }}";
          url = url.replace('any_number', enc(nomor));
          url = url.replace('type', enc(tipe_history));
          window.location.href = url;
        }else if(tipe_history == 11){
          document.getElementById("judul_lihat_followup").innerHTML = "Follow Up " + nomor;
          var url = "{{ url('detail_followup/nomor_followup') }}";
          url = url.replace('nomor_followup', enkrip(nomor));
          $('#td_no_followup').html('');
          $('#td_nama_followup').html('');
          $('#td_tanggal_followup').html('');
          $('#td_aktifitas_followup').html('');
          $('#td_informasi_followup').html('');
          $.get(url, function (data) {
            $('#td_no_followup').html(data.nomor);
            $('#td_nama_followup').html(data.nama);
            $('#td_tanggal_followup').html(data.tanggal);
            $('#td_aktivitas_followup').html(data.aktivitas);
            $('#td_informasi_followup').html(data.informasi);
          })
        }
      });

      $('#filter').click(function(){
        var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
        if(from_date != '' &&  to_date != '')
        {
          $('#sales_history_table').DataTable().destroy();
          load_history(from_date, to_date, id_user_admin);
        }
        else
        {
         alert('Both Date is required');
       }
     });

      $('#refresh').click(function(){
        $('#filter_tanggal').val('');
        $('#sales_history_table').DataTable().destroy();
        load_history(id_user_admin);
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
  });
</script>
  @endsection