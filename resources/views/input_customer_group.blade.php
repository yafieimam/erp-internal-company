@extends('layouts.app_admin')

@section('title')
<title>CUSTOMER GROUP - PT. DWI SELO GIRI MAS</title>
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
  #group_list_table tbody tr:hover{
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
  .save-btn {
    float: right;
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
    .customer-btn {
      margin-bottom: 10px;
    }
    .save-btn {
      margin-top: 10px;
      float: none;
    }
    .save-btn-in {
      width: 100%;
    }
    .form-group-agent {
      margin-right: 0;  
    }
    .lihat-table {
      overflow-x: auto;
    }
    .radio-control {
      padding-left: 0 !important;
    }
    #lihat-customer-table td, #lihat-customer-table th {
      display:inline-block;
        padding:5px;
        width:100%;
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
          <h1 class="m-0 text-dark">Customer Group</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Customer Group</li>
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
          <div class="row">
            <div class="col-3">
              <button type="button" name="btn_input_group" id="btn_input_group" class="btn btn-block btn-primary customer-btn" data-toggle="modal" data-target="#modal_input_group">Input Customer Group</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="group_list_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>No</th>
                <th>Group ID</th>
                <th>Nama Group</th>
                <th>Jumlah Anggota</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_group">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Data Customer Group</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="kode_cust_group">Kode Cust Group</label>
                <input class="form-control" type="text" name="kode_cust_group" id="kode_cust_group" placeholder="Kode Cust Group" />
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="nama_cust_group">Nama Cust Group</label>
                <input class="form-control" type="text" name="nama_cust_group" id="nama_cust_group" placeholder="Nama Cust Group" />
              </div>
            </div>
          </div>
          <form method="post" class="input_form" id="input_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Entry Customer</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-10">
                        <div class="form-group">
                          <label for="customers">Customers</label>
                          <select id="customers" name="customers" class="form-control select2 customers" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-primary" type="submit" name="btn-add-cust" id="btn-add-cust" value="Add"/>
                        </div>
                      </div>
                    </div>
                    <table id="input_customer_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Cust ID</th>
                          <th>Cust Name</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btn-save-input">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_view_group">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data Customer Group</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 lihat-table">
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td>Kode Group</td>
                  <td>:</td>
                  <td id="td_kode_group"></td>
                  <td>Nama Group</td>
                  <td>:</td>
                  <td id="td_nama_group"></td>
                </tr>
              </table>
              <h5>Data Customer : </h5>
              <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center; width: 30%;">Cust ID</th>
                    <th style="vertical-align : middle; text-align: center;">Cust Name</th>               
                  </tr>
                </thead>
                <tbody id="tbody_view">
                </tbody>
              </table>
            </div>
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

  <div class="modal fade" id="modal_edit_group">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data Customer Group</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="edit_kode_cust_group">Kode Cust Group</label>
                <input class="form-control" type="text" name="edit_kode_cust_group" id="edit_kode_cust_group" placeholder="Kode Cust Group" />
                <input class="form-control" type="hidden" name="edit_kode_cust_group_lama" id="edit_kode_cust_group_lama" />
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="edit_nama_cust_group">Nama Cust Group</label>
                <input class="form-control" type="text" name="edit_nama_cust_group" id="edit_nama_cust_group" placeholder="Nama Cust Group" />
              </div>
            </div>
          </div>
          <form method="post" class="edit_form" id="edit_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Entry Customer</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-10">
                        <div class="form-group">
                          <label for="edit_customers">Customers</label>
                          <select id="edit_customers" name="edit_customers" class="form-control select2 edit_customers" style="width: 100%;">
                          </select>
                          <input class="form-control" type="hidden" name="edit_kode_cust_group_det" id="edit_kode_cust_group_det" />
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-primary" type="submit" name="btn-edit-cust" id="btn-edit-cust" value="Add"/>
                        </div>
                      </div>
                    </div>
                    <table id="edit_customer_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Cust ID</th>
                          <th>Cust Name</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btn-save-edit">Save changes</button>
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
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    $(function () {
      $('.select2').select2();
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
      let key = "{{ env('MIX_APP_KEY') }}";

      var table = $('#group_list_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/group/customers/table") }}',
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
           data:'groupid',
           name:'groupid',
           className:'dt-center'
          },
          {
           data:'nama_group',
           name:'nama_group',
           className:'dt-center'
          },
          {
           data:'jumlah_anggota',
           name:'jumlah_anggota',
           className:'dt-center'
          },
          {
           data:'action',
           name:'action',
           className:'dt-center'
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

      function load_data_group_list()
      {
        table = $('#group_list_table').DataTable({
          processing: true,
          serverSide: true,
          lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
          ajax: {
            url:'{{ url("sales/group/customers/table") }}',
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
            data:'groupid',
            name:'groupid',
            className:'dt-center'
          },
          {
            data:'nama_group',
            name:'nama_group',
            className:'dt-center'
          },
          {
            data:'jumlah_anggota',
            name:'jumlah_anggota',
            className:'dt-center'
          },
          {
            data:'action',
            name:'action',
            className:'dt-center'
          }
          ]
        });
      }

      function load_data_customer()
      {
        $('#input_customer_table').DataTable({
          processing: true,
          serverSide: true,
          language: {
            emptyTable: "Table Kosong. Silahkan Masukkan Data."
          },
          ajax: {
            url:'{{ url("sales/group/customers/view/cust/table") }}'
          },
          dom: 'tr',
          sort: false,
          columns: [
          {
           data:'custid',
           name:'custid',
           width: '15%'
         },
         {
           data:'custname',
           name:'custname'
         },
         {
           data:'action',
           name:'action',
           width: '5%'
         }
         ]
       });
      }

      function load_data_edit_customer(groupid = '')
      {
        $('#edit_customer_table').DataTable({
          processing: true,
          serverSide: true,
          language: {
            emptyTable: "Table Kosong. Silahkan Masukkan Data."
          },
          ajax: {
            url:'{{ url("sales/group/customers/view/edit/cust/table") }}',
            data:{groupid:groupid}
          },
          dom: 'tr',
          sort: false,
          columns: [
          {
           data:'custid',
           name:'custid',
           width: '15%'
         },
         {
           data:'custname',
           name:'custname'
         },
         {
           data:'action',
           name:'action',
           width: '5%'
         }
         ]
       });
      }

      $('body').on('click', '#btn_input_group', function () {
        $('#input_customer_table').DataTable().destroy();
        load_data_customer();
      });

      $('body').on('click', '#delete-cust', function () {
        var custid = $(this).data("id");
        if(confirm("Delete this data?")){
          $.ajax({
            type: "GET",
            url: "{{ url('sales/group/customers/delete/cust') }}",
            data: { 'custid' : custid },
            success: function (data) {
              var oTable = $('#input_customer_table').dataTable(); 
              oTable.fnDraw(false);
            },
            error: function (data) {
              console.log('Error:', data);
              alert("Something Goes Wrong. Please Try Again");
            }
          });
        }
      });

      $('body').on('click', '#btn-save-input', function () {
        var kode = document.getElementById("kode_cust_group").value;
        var nama_group = document.getElementById("nama_cust_group").value;

        var count = $("#input_customer_table").dataTable().fnSettings().aoData.length;
        if (count == 0)
        {
          alert("Tambahkan Data Customer Terlebih Dahulu.");
        }else{
          if(kode == null || kode == ""){
            alert("Kode Cust Group Tidak Boleh Kosong");
          }else{
            $.ajax({
              type:"GET",
              url:"{{ url('sales/group/customers/input') }}",
              data: { 'kode_group' : kode, 'nama_group' : nama_group },
              success:function(data){
                alert("Data Successfully Created");
                $('#modal_input_group').modal('hide');
                $("#modal_input_group").trigger('click');
                $('#kode_cust_group').val('');
                $('#nama_cust_group').val('');
                var oTable = $('#group_list_table').dataTable();
                oTable.fnDraw(false);
              },
              error: function (data) {
                console.log('Error:', data);
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        }
      });

      $('body').on('click', '#view-data', function () {
        var groupid = $(this).data("id");
        var url = "{{ url('sales/group/customers/view/groupid') }}";
        url = url.replace('groupid', enc(groupid.toString()));
        $('#td_kode_group').html('');
        $('#td_nama_group').html('');
        $.get(url, function (data) {
          $('#td_kode_group').html(data.groupid);
          $('#td_nama_group').html(data.nama_group);
        })

        var url_det = "{{ url('sales/group/customers/view/cust/groupid') }}";
        url_det = url_det.replace('groupid', enc(groupid.toString()));
        $("#tbody_view").empty();
        $.get(url_det, function (data) {
          if(data.length == 0){
            $('#tbody_view').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;" colspan="3">Belum Ada Data</td>'+
              '</tr>'
              );
          }
          $.each(data, function(k, v) {
            $('#tbody_view').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;">'+ v.custid +'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+ v.custname +'</td>'+
              '</tr>'
              );
          });
        })
      });

      $('body').on('click', '#edit-data', function () {
        var groupid = $(this).data("id");
        var url = "{{ url('sales/group/customers/view/groupid') }}";
        url = url.replace('groupid', enc(groupid.toString()));
        $('#edit_kode_cust_group').val('');
        $('#edit_kode_cust_group_lama').val('');
        $('#edit_kode_cust_group_det').val('');
        $('#edit_nama_cust_group').val('');
        $('#edit_customers').val('');
        $.get(url, function (data) {
          $('#edit_kode_cust_group').val(data.groupid);
          $('#edit_kode_cust_group_det').val(data.groupid);
          $('#edit_kode_cust_group_lama').val(data.groupid);
          $('#edit_nama_cust_group').val(data.nama_group);
        })

        $('#edit_customer_table').DataTable().destroy();
        load_data_edit_customer(groupid);
      });

      $('body').on('click', '#btn-save-edit', function () {
        var kode = document.getElementById("edit_kode_cust_group").value;
        var kode_lama = document.getElementById("edit_kode_cust_group_lama").value;
        var nama_group = document.getElementById("edit_nama_cust_group").value;

        var count = $("#edit_customer_table").dataTable().fnSettings().aoData.length;
        if (count == 0)
        {
          alert("Tambahkan Data Customer Terlebih Dahulu.");
        }else{
          if(kode == null || kode == ""){
            alert("Kode Cust Group Tidak Boleh Kosong");
          }else{
            $.ajax({
              type:"GET",
              url:"{{ url('sales/group/customers/edit') }}",
              data: { 'kode_group' : kode, 'kode_group_lama' : kode_lama, 'nama_group' : nama_group },
              success:function(data){
                alert("Data Successfully Updated");
                $('#modal_edit_group').modal('hide');
                $("#modal_edit_group").trigger('click');
                var oTable = $('#group_list_table').dataTable();
                oTable.fnDraw(false);
              },
              error: function (data) {
                console.log('Error:', data);
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        }
      });

      $('body').on('click', '#delete-edit-cust', function () {
        var custid = $(this).data("id");
        var groupid = $(this).data("idd");
        if(confirm("Delete this data?")){
          $.ajax({
            type: "GET",
            url: "{{ url('sales/group/customers/delete/edit/cust') }}",
            data: { 'custid' : custid, 'groupid' : groupid },
            success: function (data) {
              var oTable = $('#edit_customer_table').dataTable(); 
              oTable.fnDraw(false);
            },
            error: function (data) {
              console.log('Error:', data);
              alert("Something Goes Wrong. Please Try Again");
            }
          });
        }
      });

      $('body').on('click', '#delete-data', function () {
        var groupid = $(this).data("id");
        if(confirm("Delete this data?")){
          $.ajax({
            type: "GET",
            url: "{{ url('sales/group/customers/delete') }}",
            data: { 'groupid' : groupid },
            success: function (data) {
              alert("Data Successfully Deleted");
              var oTable = $('#group_list_table').dataTable(); 
              oTable.fnDraw(false);
            },
            error: function (data) {
              console.log('Error:', data);
              alert("Something Goes Wrong. Please Try Again");
            }
          });
        }
      });

      $('#input_form').validate({
        rules: {
          customers: {
            required: true,
          },
        },
        messages: {
          customers: {
            required: "Customers Harus Diisi",
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
          var myform = document.getElementById("input_form");
          var formdata = new FormData(myform);

          $.ajax({
            type:'POST',
            url:"{{ url('sales/group/customers/add/cust') }}",
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
              $('#input_form').trigger("reset");
              $('#customers').val('').trigger('change');
              var oTable = $('#input_customer_table').dataTable();
              oTable.fnDraw(false);
            },
            error: function (data) {
              console.log('Error:', data);
              var oTable = $('#input_customer_table').dataTable();
              oTable.fnDraw(false);
              alert("Something Goes Wrong, Please Try Again");
            }
          });
        }
      });

      $('#edit_form').validate({
        rules: {
          edit_customers: {
            required: true,
          },
        },
        messages: {
          edit_customers: {
            required: "Customers Harus Diisi",
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
          var myform = document.getElementById("edit_form");
          var formdata = new FormData(myform);

          $.ajax({
            type:'POST',
            url:"{{ url('sales/group/customers/edit/cust') }}",
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
              $('#edit_form').trigger("reset");
              $('#edit_customers').val('').trigger('change');
              var oTable = $('#edit_customer_table').dataTable();
              oTable.fnDraw(false);
            },
            error: function (data) {
              console.log('Error:', data);
              var oTable = $('#edit_customer_table').dataTable();
              oTable.fnDraw(false);
              alert("Something Goes Wrong, Please Try Again");
            }
          });
        }
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
      $('.customers').select2({
        dropdownParent: $('#modal_input_group .modal-content'),
        placeholder: 'Pilih Customers',
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

      $('.edit_customers').select2({
        dropdownParent: $('#modal_edit_group .modal-content'),
        placeholder: 'Pilih Customers',
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
    $(".customers").on("select2:open", function() {
      $(".select2-search__field").attr("placeholder", "Search Customer Here...");
    });
    $(".customers").on("select2:close", function() {
      $(".select2-search__field").attr("placeholder", null);
    });

    $(".edit_customers").on("select2:open", function() {
      $(".select2-search__field").attr("placeholder", "Search Customer Here...");
    });
    $(".edit_customers").on("select2:close", function() {
      $(".select2-search__field").attr("placeholder", null);
    });
  </script>
@endsection
