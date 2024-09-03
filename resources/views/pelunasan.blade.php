@extends('layouts.app_admin')

@section('title')
<title>PELUNASAN - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Pelunasan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Penagihan</li>
            <li class="breadcrumb-item">Pelunasan</li>
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
                  <input type="text" placeholder="Filter Date" class="form-control float-right" id="filter_tanggal" autocomplete="off">
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
            <div class="col-9">
              <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#data_ar" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data AR</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#list_sudah_lunas" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">List Sudah Lunas</a>
                </li>
              </ul>
            </div>
            <div class="col-3">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_data">Buat AR</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="data_ar" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_ar_table" style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No AR</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="list_sudah_lunas" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_list_sudah_lunas_table" style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No SJ</th>
                    <th>No Inv</th>
                    <th>Customers</th>
                    <th>Tgl Lunas</th>
                    <th>Pembayaran</th>
                    <th>No Ref</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Buat AR</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="nomor_ar">Nomor AR</label>
                <input class="form-control" type="text" name="nomor_ar" id="nomor_ar" placeholder="Nomor AR" />
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Tanggal</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="tanggal" id="tanggal" autocomplete="off" placeholder="Tanggal">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="nomor_referensi">Nomor Referensi</label>
                <input class="form-control" type="text" name="nomor_referensi" id="nomor_referensi" placeholder="Nomor Referensi" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="keterangan_pelunasan">Keterangan</label>
                <textarea class="form-control" rows="3" name="keterangan_pelunasan" id="keterangan_pelunasan" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="total_nominal">Total Nominal</label>
                <input class="form-control" type="text" name="total_nominal" id="total_nominal" placeholder="Total Nominal" readonly="true" />
              </div>
            </div>
          </div>
          <form method="post" class="input_form" id="input_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Entry Data Invoice</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="nomor_invoice">Nomor Invoice</label>
                          <input class="form-control" type="text" name="nomor_invoice" id="nomor_invoice" placeholder="Nomor Invoice" />
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="customers">Customers</label>
                          <input class="form-control" type="text" name="customers" id="customers" disabled="true" />
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="tagihan">Tagihan</label>
                          <input class="form-control" type="text" name="tagihan" id="tagihan" disabled="true" />
                          <input class="form-control" type="hidden" name="nominal_tagihan" id="nominal_tagihan" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="nominal">Nominal</label>
                          <input class="form-control" type="text" name="nominal" id="nominal" placeholder="Nominal" />
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="other">Other Amount</label>
                          <input class="form-control" type="text" name="other" id="other" placeholder="Other Amount"/>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="acc_other">Account Other</label>
                          <select id="acc_other" name="acc_other" class="form-control">
                            <option value="" selected>Choose Account Other</option>
                            <option value="7106">Pembulatan</option>
                            <option value="410103">Potongan Penjualan</option>
                            <option value="1000">Pajak Nol (0)</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-primary" type="submit" name="btn-add-invoice" id="btn-add-invoice" value="Add"/>
                        </div>
                      </div>
                    </div>
                    <table id="input_invoice_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>No Inv</th>
                          <th>Customers</th>
                          <th>Nominal</th>
                          <th>Other</th>
                          <th>Acc-Other</th>
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

  <div class="modal fade" id="modal_view_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 lihat-table">
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td>Nomor AR</td>
                  <td>:</td>
                  <td id="td_nomor_ar"></td>
                  <td>Nomor Referensi</td>
                  <td>:</td>
                  <td id="td_nomor_referensi"></td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td>:</td> 
                  <td id="td_tanggal"></td>
                  <td>Keterangan</td>
                  <td>:</td>
                  <td id="td_keterangan"></td>
                </tr>
                <tr>
                  <td>Total Nominal</td>
                  <td>:</td> 
                  <td colspan="4" id="td_total_nominal"></td>
                </tr>
              </table>
              <h5>Data Invoice : </h5>
              <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center; width: 40%;">No Invoice</th>
                    <th style="vertical-align : middle; text-align: center;">Customers</th>
                    <th style="vertical-align : middle; text-align: center;">Nominal</th> 
                    <th style="vertical-align : middle; text-align: center;">Other</th> 
                    <th style="vertical-align : middle; text-align: center;">Acc-Other</th>                  
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

  <div class="modal fade" id="modal_edit_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit AR</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="edit_nomor_ar">Nomor AR</label>
                <input class="form-control" type="text" name="edit_nomor_ar" id="edit_nomor_ar" placeholder="Nomor AR" />
                <input class="form-control" type="hidden" name="edit_nomor_ar_lama" id="edit_nomor_ar_lama" />
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Tanggal</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="edit_tanggal" id="edit_tanggal" autocomplete="off" placeholder="Tanggal">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="edit_nomor_referensi">Nomor Referensi</label>
                <input class="form-control" type="text" name="edit_nomor_referensi" id="edit_nomor_referensi" placeholder="Nomor Referensi" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="edit_keterangan_pelunasan">Keterangan</label>
                <textarea class="form-control" rows="3" name="edit_keterangan_pelunasan" id="edit_keterangan_pelunasan" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="edit_total_nominal">Total Nominal</label>
                <input class="form-control" type="text" name="edit_total_nominal" id="edit_total_nominal" placeholder="Total Nominal" readonly="true" />
              </div>
            </div>
          </div>
          <form method="post" class="edit_form" id="edit_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Entry Data Invoice</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="edit_nomor_invoice">Nomor Invoice</label>
                          <input class="form-control" type="text" name="edit_nomor_invoice" id="edit_nomor_invoice" placeholder="Nomor Invoice" />
                          <input class="form-control" type="hidden" name="edit_nomor_ar_inv" id="edit_nomor_ar_inv" />
                          <input class="form-control" type="hidden" name="edit_nomor_referensi_inv" id="edit_nomor_referensi_inv" />
                          <input class="form-control" type="hidden" name="edit_keterangan_pelunasan_inv" id="edit_keterangan_pelunasan_inv" />
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="edit_customers">Customers</label>
                          <input class="form-control" type="text" name="edit_customers" id="edit_customers" disabled="true" />
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="edit_tagihan">Tagihan</label>
                          <input class="form-control" type="text" name="edit_tagihan" id="edit_tagihan" disabled="true" />
                          <input class="form-control" type="hidden" name="edit_nominal_tagihan" id="edit_nominal_tagihan" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="edit_nominal">Nominal</label>
                          <input class="form-control" type="text" name="edit_nominal" id="edit_nominal" />
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="edit_other">Other Amount</label>
                          <input class="form-control" type="text" name="edit_other" id="edit_other" placeholder="Other Amount"/>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_acc_other">Account Other</label>
                          <select id="edit_acc_other" name="edit_acc_other" class="form-control">
                            <option value="" selected>Choose Account Other</option>
                            <option value="7106">Pembulatan</option>
                            <option value="410103">Potongan Penjualan</option>
                            <option value="1000">Pajak Nol (0)</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-primary" type="submit" name="btn-edit-invoice" id="btn-edit-invoice" value="Add"/>
                        </div>
                      </div>
                    </div>
                    <table id="edit_invoice_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>No Inv</th>
                          <th>Customers</th>
                          <th>Nominal</th>
                          <th>Other</th>
                          <th>Acc-Other</th>
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
    $('#filter_tanggal').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $('#tanggal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal').flatpickr({
      allowInput: true,
      disableMobile: true
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#data_ar_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("penagihan/pelunasan/noar/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          data:'noar',
          name:'noar',
          width:'8%',
          className:'dt-center'
        },
        {
          data:'tanggal',
          name:'tanggal',
          className:'dt-center',
          width:'8%',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'total_nominal',
          name:'total_nominal',
          className:'dt-center',
          width:'10%',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
        },
        {
         data:'action',
         name:'action',
         width: '10%',
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

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#data_pelunasan'){
        $('#data_pelunasan_table').DataTable().destroy();
        load_data_pelunasan();
      }else if(target == '#list_sudah_lunas'){
        $('#data_list_sudah_lunas_table').DataTable().destroy();
        load_data_list_sudah_lunas();
      }
    });

    function load_data_pelunasan(from_date = '', to_date = '')
    {
      table = $('#data_pelunasan_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/pelunasan/noar/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[0,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          data:'noar',
          name:'noar',
          width:'8%',
          className:'dt-center'
        },
        {
          data:'tanggal',
          name:'tanggal',
          className:'dt-center',
          width:'8%',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'total_nominal',
          name:'total_nominal',
          className:'dt-center',
          width:'10%',
          render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ')
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

    function load_data_list_sudah_lunas(from_date = '', to_date = '')
    {
      table = $('#data_list_sudah_lunas_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("penagihan/list_sudah_lunas/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[3,'desc'], [0, 'desc']],
        dom: 'lBfrtip',
        columns: [
        {
          data:'nosj',
          name:'nosj',
          className:'dt-center',
          width:'10%'
        },
        {
          data:'noinv',
          name:'noinv',
          className:'dt-center',
          width:'10%',
          defaultContent:'--'
        },
        {
          data:'custname',
          name:'custname',
          className:'dt-center'
        },
        {
          data:'tanggal_pelunasan',
          name:'tanggal_pelunasan',
          className:'dt-center',
          width:'10%',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:null,
          name:null,
          className: 'dt-center',
          width:'15%',
          render: function ( data, type, row)
          {
            if($('<div />').text(row.metode_pembayaran).html() == null || $('<div />').text(row.metode_pembayaran).html() == ''){
              return '--';
            }else{
              if($('<div />').text(row.metode_pembayaran).html() == 1){
                return $('<div />').text(row.nama_metode_pembayaran).html() + "<br>" + "Rp " + $('<div />').text(row.nominal_bayar).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }else{
                return $('<div />').text(row.nama_metode_pembayaran).html() + "<br>" + "No. " + $('<div />').text(row.nomor_metode_pembayaran).html() + "<br>" + "Rp " + $('<div />').text(row.nominal_bayar).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
          }
        },
        {
          data:'nomor_referensi',
          name:'nomor_referensi',
          className:'dt-center',
          width:'12%'
        },
        {
          data:'keterangan_pelunasan',
          name:'keterangan_pelunasan',
          className:'dt-center',
          width:'15%'
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
            $(win.document.body).find('h1').html('List Data Sudah Lunas');
          }
        }
        ]
      });
    }

    function load_data_invoice()
    {
      $('#input_invoice_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Table Kosong. Silahkan Masukkan Data."
        },
        ajax: {
          url:'{{ url("penagihan/pelunasan/view/invoice/table") }}'
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'noinv',
         name:'noinv',
         width: '15%'
       },
       {
         data:'customers',
         name:'customers'
       },
       {
         data:'nominal',
         name:'nominal',
         render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
         width: '20%'
       },
       {
         data:'other',
         name:'other',
         width: '10%',
         render: function ( data, type, row)
         {
          if($('<div />').text(row.other).html() == 0){
            return '--';
          }else{
            return "Rp " + $('<div />').text(row.other).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }
         }
       },
       {
         data:'acc_other',
         name:'acc_other',
         width: '15%',
         render: function ( data, type, row)
         {
          if($('<div />').text(row.acc_other).html() == null || $('<div />').text(row.acc_other).html() == '' || $('<div />').text(row.acc_other).html() == 1000){
            return '--';
          }else{
            return $('<div />').text(row.acc_other).html();
          }
         }
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       }
       ]
     });
    }

    function load_data_edit_invoice(noar = '')
    {
      $('#edit_invoice_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Table Kosong. Silahkan Masukkan Data."
        },
        ajax: {
          url:'{{ url("penagihan/pelunasan/edit/invoice/table") }}',
          data:{noar:noar}
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'noinv',
         name:'noinv',
         width: '15%'
       },
       {
         data:'custname',
         name:'custname'
       },
       {
         data:'nominal',
         name:'nominal',
         render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp '),
         width: '20%'
       },
       {
         data:'other',
         name:'other',
         width: '10%',
         render: function ( data, type, row)
         {
          if($('<div />').text(row.other).html() == 0){
            return '--';
          }else{
            return "Rp " + $('<div />').text(row.other).html().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }
         }
       },
       {
         data:'acc_other',
         name:'acc_other',
         width: '15%',
         render: function ( data, type, row)
         {
          if($('<div />').text(row.acc_other).html() == null || $('<div />').text(row.acc_other).html() == '' || $('<div />').text(row.acc_other).html() == 1000){
            return '--';
          }else{
            return $('<div />').text(row.acc_other).html();
          }
         }
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       }
       ]
     });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if(target == '#data_pelunasan'){
          $('#data_pelunasan_table').DataTable().destroy();
          load_data_pelunasan(from_date, to_date);
        }else if(target == '#list_sudah_lunas'){
          $('#data_list_sudah_lunas_table').DataTable().destroy();
          load_data_list_sudah_lunas(from_date, to_date);
        }
      }
      else
      {
        alert('Both Date is required');
      }
    });

    function isEmpty(obj) {
      for(var prop in obj) {
        if(obj.hasOwnProperty(prop)) {
          return false;
        }
      }

      return JSON.stringify(obj) === JSON.stringify({});
    }

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(target == '#data_pelunasan'){
        $('#data_pelunasan_table').DataTable().destroy();
        load_data_pelunasan();
      }else if(target == '#list_sudah_lunas'){
        $('#data_list_sudah_lunas_table').DataTable().destroy();
        load_data_list_sudah_lunas();
      }
    });

    $('body').on('click', '#btn_input_data', function () {
      $('#input_invoice_table').DataTable().destroy();
      load_data_invoice();

      $('#nomor_invoice').on('keyup',function(e){
        var val = $(this).val();
        
        if(val.toString().length > 10){
          var url = "{{ url('penagihan/pelunasan/view/invoice/detail/noinv') }}";
          url = url.replace('noinv', enc(val.toString()));
          $.get(url, function (data) {
            if(isEmpty(data[0])){
              $('#customers').val('Tidak Ada Data');
              $('#nominal').val('');
              $('#tagihan').val('-');
              $('#nominal_tagihan').val('');
            }else{
              $('#tagihan').val('Rp ' + data[0].tagihan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00');
              if(data[0].sisa_bayar == 0){
                $('#customers').val(data[0].customer);
                $('#nominal').val(data[0].tagihan);
                $('#nominal_tagihan').val(data[0].tagihan);
                $('#other').val('0');
              }else{
                $('#customers').val(data[0].customer);
                $('#nominal').val(data[0].sisa_bayar);
                $('#nominal_tagihan').val(data[0].sisa_bayar);
                $('#other').val('0');
              }
            }
          });
        }else{
          $('#customers').val('Tidak Ada Data');
          $('#nominal').val('');
          $('#tagihan').val('-');
          $('#nominal_tagihan').val('');
        }
      });

      $('#nominal').on('keyup',function(e){
        var val = $(this).val();
        var tagihan = $('#nominal_tagihan').val();

        var hitung = 0;
        hitung = tagihan - val;
        $('#other').val(hitung);
      });
    });

    $('body').on('click', '#delete-invoice', function () {
      var noinv = $(this).data("id");
      if(confirm("Delete this data?")){
        $.ajax({
          type: "GET",
          url: "{{ url('penagihan/pelunasan/delete/invoice') }}",
          data: { 'noinv' : noinv },
          success: function (data) {
            var oTable = $('#input_invoice_table').dataTable(); 
            oTable.fnDraw(false);

            var url_total = "{{ url('penagihan/pelunasan/sum/invoice') }}";
            $.get(url_total, function (data_total) {
              $('#total_nominal').val(data_total[0].total);
            })
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-save-input', function () {
      var noar = document.getElementById("nomor_ar").value;
      var no_referensi = document.getElementById("nomor_referensi").value;
      var keterangan = document.getElementById("keterangan_pelunasan").value;
      var total_nominal = document.getElementById("total_nominal").value;
      var tanggal = document.getElementById("tanggal").value;

      var count = $("#input_invoice_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Tambahkan Data Invoice Terlebih Dahulu.");
      }else{
        if(noar == null || noar == ""){
          alert("Nomor AR Tidak Boleh Kosong");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('penagihan/pelunasan/input/invoice') }}",
            data: { 'nomor_ar' : noar, 'nomor_referensi' : no_referensi, 'keterangan_pelunasan' : keterangan, 'total_nominal' : total_nominal, 'tanggal' : tanggal },
            success:function(data){
              alert("Data Successfully Created");
              $('#modal_input_data').modal('hide');
              $("#modal_input_data").trigger('click');
              $('#nomor_ar').val('');
              $('#nomor_referensi').val('');
              $('#tanggal').val('');
              $('#keterangan_pelunasan').html('');
              $('#total_nominal').val('');
              var oTable = $('#data_ar_table').dataTable();
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
      var noar = $(this).data("id");
      var url = "{{ url('penagihan/pelunasan/view/noar/no_noar') }}";
      url = url.replace('no_noar', enc(noar.toString()));
      $('#td_nomor_ar').html('');
      $('#td_tanggal').html('');
      $('#td_nomor_referensi').html('');
      $('#td_keterangan').html('');
      $('#td_total_nominal').html('');
      $.get(url, function (data) {
        $('#td_nomor_ar').html(data.noar);
        $('#td_tanggal').html(moment(data.tanggal).format('DD MMM YYYY'));
        $('#td_nomor_referensi').html(data.no_referensi);
        $('#td_keterangan').html(data.keterangan);
        $('#td_total_nominal').html('Rp ' + data.total_nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00');
      })

      var url_det = "{{ url('penagihan/pelunasan/view/invoice/noar') }}";
      url_det = url_det.replace('noar', enc(noar.toString()));
      $("#tbody_view").empty();
      $.get(url_det, function (data) {
        if(data.length == 0){
          $('#tbody_view').append(
            '<tr>'+
            '<td style="vertical-align : middle; text-align: center;" colspan="5">Belum Ada Data</td>'+
            '</tr>'
          );
        }
        $.each(data, function(k, v) {
          $('#tbody_view').append(
            '<tr>'+
            '<td style="vertical-align : middle; text-align: center;">'+ v.noinv +'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+ v.custname +'</td>'+
            '<td style="vertical-align : middle; text-align: center;">Rp '+ v.nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") +',00</td>'+
            '<td style="vertical-align : middle; text-align: center;">Rp '+ v.other.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") +',00</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+ v.acc_other +'</td>'+
            '</tr>'
            );
        });
      })
    });

    $('body').on('click', '#edit-data', function () {
      var noar = $(this).data("id");
      var url = "{{ url('penagihan/pelunasan/view/noar/no_noar') }}";
      url = url.replace('no_noar', enc(noar.toString()));
      $('#edit_nomor_ar').val('');
      $('#edit_nomor_ar_lama').val('');
      $('#edit_tanggal').val('');
      $('#edit_nomor_referensi').val('');
      $('#edit_keterangan_pelunasan').html('');
      $('#edit_nominal').val('');
      $('#edit_nomor_invoice').val('');
      $('#edit_customers').val('');
      $('#edit_nominal').val('');
      $('#edit_nomor_ar_inv').val('');
      $('#edit_nomor_referensi_inv').val('');
      $('#edit_keterangan_pelunasan_inv').val('');
      $.get(url, function (data) {
        $('#edit_nomor_ar').val(data.noar);
        $('#edit_nomor_ar_lama').val(data.noar);
        $('#edit_nomor_ar_inv').val(data.noar);
        $('#edit_tanggal').val(data.tanggal);
        $('#edit_nomor_referensi').val(data.no_referensi);
        $('#edit_nomor_referensi_inv').val(data.no_referensi);
        $('#edit_keterangan_pelunasan').html(data.keterangan);
        $('#edit_keterangan_pelunasan_inv').val(data.keterangan);
        $('#edit_total_nominal').val(data.total_nominal);
      })

      $('#edit_invoice_table').DataTable().destroy();
      load_data_edit_invoice(noar);

      $('#edit_nomor_invoice').on('keyup',function(e){
        var val = $(this).val();
        
        if(val.toString().length > 10){
          var url = "{{ url('penagihan/pelunasan/view/invoice/detail/noinv') }}";
          url = url.replace('noinv', enc(val.toString()));
          $.get(url, function (data) {
            if(isEmpty(data[0])){
              $('#edit_customers').val('Tidak Ada Data');
              $('#edit_nominal').val('');
              $('#edit_tagihan').val('-');
              $('#edit_nominal_tagihan').val('');
            }else{
              $('#edit_tagihan').val('Rp ' + data[0].tagihan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',00');
              if(data[0].sisa_bayar == 0){
                $('#edit_customers').val(data[0].customer);
                $('#edit_nominal').val(data[0].tagihan);
                $('#edit_nominal_tagihan').val(data[0].tagihan);
                $('#edit_other').val('0');
              }else{
                $('#edit_customers').val(data[0].customer);
                $('#edit_nominal').val(data[0].sisa_bayar);
                $('#edit_nominal_tagihan').val(data[0].sisa_bayar);
                $('#edit_other').val('0');
              }
            }
          });
        }else{
          $('#edit_customers').val('Tidak Ada Data');
          $('#edit_nominal').val('');
          $('#edit_tagihan').val('-');
          $('#edit_nominal_tagihan').val('');
        }
      });

      $('#edit_nominal').on('keyup',function(e){
        var val = $(this).val();
        var tagihan = $('#edit_nominal_tagihan').val();

        var hitung = 0;
        hitung = tagihan - val;
        $('#edit_other').val(hitung);
      });

      $('#edit_nomor_ar').on('input',function(e){
        var val = $(this).val();
        $('#edit_nomor_ar_inv').val(val);
      });

      $('#edit_nomor_referensi').on('input',function(e){
        var val = $(this).val();
        $('#edit_nomor_referensi_inv').val(val);
      });

      $('#edit_keterangan_pelunasan').on('input',function(e){
        var val = $(this).val();
        $('#edit_keterangan_pelunasan_inv').val(val);
      });
    });

    $('body').on('click', '#delete-edit-invoice', function () {
      var noinv = $(this).data("id");
      var noar = $(this).data("idd");
      if(confirm("Delete this data?")){
        $.ajax({
          type: "GET",
          url: "{{ url('penagihan/pelunasan/delete/edit/invoice') }}",
          data: { 'noinv' : noinv, 'noar' : noar },
          success: function (data) {
            var oTable = $('#edit_invoice_table').dataTable(); 
            oTable.fnDraw(false);

            var noar = $('#edit_nomor_ar_inv').val();
            var url_total = "{{ url('penagihan/pelunasan/sum/edit/invoice/noar') }}";
            url_total = url_total.replace('noar', enc(noar.toString()));
            $.get(url_total, function (data_total) {
              $('#edit_total_nominal').val(data_total.total);
            })
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-save-edit', function () {
      var noar = document.getElementById("edit_nomor_ar").value;
      var noar_lama = document.getElementById("edit_nomor_ar_lama").value;
      var no_referensi = document.getElementById("edit_nomor_referensi").value;
      var keterangan = document.getElementById("edit_keterangan_pelunasan").value;
      var total_nominal = document.getElementById("edit_total_nominal").value;
      var tanggal = document.getElementById("edit_tanggal").value;

      var count = $("#edit_invoice_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Tambahkan Data Invoice Terlebih Dahulu.");
      }else{
        if(noar == null || noar == ""){
          alert("Nomor AR Tidak Boleh Kosong");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('penagihan/pelunasan/edit/ar') }}",
            data: { 'nomor_ar' : noar, 'nomor_ar_lama' : noar_lama, 'nomor_referensi' : no_referensi, 'keterangan_pelunasan' : keterangan, 'total_nominal' : total_nominal, 'tanggal' : tanggal },
            success:function(data){
              alert("Data Successfully Updated");
              $('#modal_edit_data').modal('hide');
              $("#modal_edit_data").trigger('click');
              $('#edit_nomor_ar').val('');
              $('#edit_nomor_ar_lama').val('');
              $('#edit_nomor_ar_inv').val('');
              $('#edit_nomor_referensi').val('');
              $('#edit_nomor_referensi_inv').val('');
              $('#edit_keterangan_pelunasan').html('');
              $('#edit_keterangan_pelunasan_inv').html('');
              $('#edit_tanggal').val('');
              var oTable = $('#data_ar_table').dataTable();
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

    $('#input_form').validate({
      rules: {
        nomor_invoice: {
          required: true,
        },
      },
      messages: {
        nomor_invoice: {
          required: "Nomor Invoice Harus Diisi",
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
          url:"{{ url('penagihan/pelunasan/add/invoice') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            var oTable = $('#input_invoice_table').dataTable();
            oTable.fnDraw(false);

            var url_total = "{{ url('penagihan/pelunasan/sum/invoice') }}";
            $.get(url_total, function (data_total) {
              $('#total_nominal').val(data_total[0].total);
            })
          },
          error: function (data) {
            console.log('Error:', data);
            var oTable = $('#input_invoice_table').dataTable();
            oTable.fnDraw(false);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });

    $('#edit_form').validate({
      rules: {
        edit_nomor_invoice: {
          required: true,
        },
      },
      messages: {
        edit_nomor_invoice: {
          required: "Nomor Invoice Harus Diisi",
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
          url:"{{ url('penagihan/pelunasan/edit/invoice') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            var oTable = $('#edit_invoice_table').dataTable();
            oTable.fnDraw(false);

            var noar = $('#edit_nomor_ar_inv').val();
            var url_total = "{{ url('penagihan/pelunasan/sum/edit/invoice/noar') }}";
            url_total = url_total.replace('noar', enc(noar.toString()));
            $.get(url_total, function (data_total) {
              $('#edit_total_nominal').val(data_total.total);
            })
          },
          error: function (data) {
            console.log('Error:', data);
            var oTable = $('#edit_invoice_table').dataTable();
            oTable.fnDraw(false);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });
  });
</script>

@endsection