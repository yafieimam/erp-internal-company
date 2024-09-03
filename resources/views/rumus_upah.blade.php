@extends('layouts.app_admin')

@section('title')
<title>RUMUS UPAH - PT. DWI SELO GIRI MAS</title>
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
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
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
          <h1 class="m-0 text-dark">Rumus Upah</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Administrasi</li>
            <li class="breadcrumb-item">Rumus Upah</li>
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
            <div class="col-4">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="rumus_upah_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Upah Pokok</th>
                <th>Bonus Mingguan</th>
                <th>Bonus Bulanan</th>
                <th width="13%"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="input_form" id="input_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="kode_upah">Kode Upah</label>
                  <input class="form-control" type="text" name="kode_upah" id="kode_upah" placeholder="Kode Upah" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="upah_pokok">Upah Pokok</label>
                  <input class="form-control" type="text" name="upah_pokok" id="upah_pokok" placeholder="Upah Pokok" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="bonus_mingguan">Bonus Mingguan</label>
                  <input class="form-control" type="text" name="bonus_mingguan" id="bonus_mingguan" placeholder="Bonus Mingguan" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="bonus_bulanan">Bonus Bulanan</label>
                  <input class="form-control" type="text" name="bonus_bulanan" id="bonus_bulanan" placeholder="Bonus Bulanan" />
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><h5>Formula / Rumus : </h5></div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="rumus_upah">Upah</label>
                      <textarea class="form-control" rows="3" name="rumus_upah" id="rumus_upah" placeholder="Upah"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="rumus_lembur">Lembur</label>
                      <textarea class="form-control" rows="3" name="rumus_lembur" id="rumus_lembur" placeholder="Lembur"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="rumus_uang_makan">Uang Makan</label>
                      <textarea class="form-control" rows="3" name="rumus_uang_makan" id="rumus_uang_makan" placeholder="Uang Makan"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="rumus_tunjangan_jabatan">Tunjangan Jabatan</label>
                      <textarea class="form-control" rows="3" name="rumus_tunjangan_jabatan" id="rumus_tunjangan_jabatan" placeholder="Tunjangan Jabatan"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="rumus_bonus">Bonus</label>
                      <textarea class="form-control" rows="3" name="rumus_bonus" id="rumus_bonus" placeholder="Bonus"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="rumus_premi">Premi</label>
                      <textarea class="form-control" rows="3" name="rumus_premi" id="rumus_premi" placeholder="Premi"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="rumus_tunjangan_lain">Tunjangan Lain</label>
                      <textarea class="form-control" rows="3" name="rumus_tunjangan_lain" id="rumus_tunjangan_lain" placeholder="Tunjangan Lain"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-input">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_view_data">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td>Kode Upah</td>
                  <td>:</td>
                  <td id="td_kode_upah"></td>
                </tr>
                <tr>
                  <td>Upah Pokok</td>
                  <td>:</td>
                  <td id="td_upah_pokok"></td>
                </tr>
                <tr>
                  <td>Bonus Mingguan</td>
                  <td>:</td>
                  <td id="td_bonus_mingguan"></td>
                </tr>
                <tr>
                  <td>Bonus Bulanan</td>
                  <td>:</td>
                  <td id="td_bonus_bulanan"></td>
                </tr>
              </table>
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td colspan="3">Formula / Rumus : </td>
                </tr>
                <tr>
                  <td>Upah</td>
                  <td>:</td>
                  <td id="td_rumus_upah"></td>
                </tr>
                <tr>
                  <td>Lembur</td>
                  <td>:</td>
                  <td id="td_rumus_lembur"></td>
                </tr>
                <tr>
                  <td>Uang Makan</td>
                  <td>:</td>
                  <td id="td_rumus_uang_makan"></td>
                </tr>
                <tr>
                  <td>Tunjangan Jabatan</td>
                  <td>:</td>
                  <td id="td_rumus_tunjangan_jabatan"></td>
                </tr>
                <tr>
                  <td>Bonus</td>
                  <td>:</td>
                  <td id="td_rumus_bonus"></td>
                </tr>
                <tr>
                  <td>Premi</td>
                  <td>:</td>
                  <td id="td_rumus_premi"></td>
                </tr>
                <tr>
                  <td>Tunjangan Lain</td>
                  <td>:</td>
                  <td id="td_rumus_tunjangan_lain"></td>
                </tr>
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
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="edit_form" id="edit_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_kode_upah">Kode Upah</label>
                  <input class="form-control" type="text" name="edit_kode_upah" id="edit_kode_upah" placeholder="Kode Upah" />
                  <input class="form-control" type="hidden" name="edit_kode_upah_lama" id="edit_kode_upah_lama" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_upah_pokok">Upah Pokok</label>
                  <input class="form-control" type="text" name="edit_upah_pokok" id="edit_upah_pokok" placeholder="Upah Pokok" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_bonus_mingguan">Bonus Mingguan</label>
                  <input class="form-control" type="text" name="edit_bonus_mingguan" id="edit_bonus_mingguan" placeholder="Bonus Mingguan" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="edit_bonus_bulanan">Bonus Bulanan</label>
                  <input class="form-control" type="text" name="edit_bonus_bulanan" id="edit_bonus_bulanan" placeholder="Bonus Bulanan" />
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><h5>Formula / Rumus : </h5></div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="edit_rumus_upah">Upah</label>
                      <textarea class="form-control" rows="3" name="edit_rumus_upah" id="edit_rumus_upah" placeholder="Upah"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="edit_rumus_lembur">Lembur</label>
                      <textarea class="form-control" rows="3" name="edit_rumus_lembur" id="edit_rumus_lembur" placeholder="Lembur"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="edit_rumus_uang_makan">Uang Makan</label>
                      <textarea class="form-control" rows="3" name="edit_rumus_uang_makan" id="edit_rumus_uang_makan" placeholder="Uang Makan"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="edit_rumus_tunjangan_jabatan">Tunjangan Jabatan</label>
                      <textarea class="form-control" rows="3" name="edit_rumus_tunjangan_jabatan" id="edit_rumus_tunjangan_jabatan" placeholder="Tunjangan Jabatan"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="edit_rumus_bonus">Bonus</label>
                      <textarea class="form-control" rows="3" name="edit_rumus_bonus" id="edit_rumus_bonus" placeholder="Bonus"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="edit_rumus_premi">Premi</label>
                      <textarea class="form-control" rows="3" name="edit_rumus_premi" id="edit_rumus_premi" placeholder="Premi"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="edit_rumus_tunjangan_lain">Tunjangan Lain</label>
                      <textarea class="form-control" rows="3" name="edit_rumus_tunjangan_lain" id="edit_rumus_tunjangan_lain" placeholder="Tunjangan Lain"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-edit">Save changes</button>
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
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#rumus_upah_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("rumus_upah/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'kode_upah',
           name:'kode_upah',
           className:'dt-center'
         },
         {
           data:'upah_pokok',
           name:'upah_pokok',
           className:'dt-center',
           render: $.fn.dataTable.render.number( '.', ',', 4, 'Rp ')
         },
         {
           data:'bonus_mingguan',
           name:'bonus_mingguan',
           className:'dt-center',
           render: $.fn.dataTable.render.number( '.', ',', 4, 'Rp ')
         },
         {
           data:'bonus_bulanan',
           name:'bonus_bulanan',
           className:'dt-center',
           render: $.fn.dataTable.render.number( '.', ',', 4, 'Rp ')
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

    function load_rumus_upah()
    {
      table = $('#rumus_upah_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("rumus_upah/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'kode_upah',
           name:'kode_upah',
           className:'dt-center'
         },
         {
           data:'upah_pokok',
           name:'upah_pokok',
           className:'dt-center',
           render: $.fn.dataTable.render.number( '.', ',', 4, 'Rp ')
         },
         {
           data:'bonus_mingguan',
           name:'bonus_mingguan',
           className:'dt-center',
           render: $.fn.dataTable.render.number( '.', ',', 4, 'Rp ')
         },
         {
           data:'bonus_bulanan',
           name:'bonus_bulanan',
           className:'dt-center',
           render: $.fn.dataTable.render.number( '.', ',', 4, 'Rp ')
         },
         {
           data:'action',
           name:'action',
           className:'dt-center'
         }
       ]
     });
    }

    $('body').on('click', '#view-data', function () {
      var kode = $(this).data("id");
      var url = "{{ url('rumus_upah/view/kode') }}";
      url = url.replace('kode', enc(kode.toString()));
      $('#td_kode_upah').html('');
      $('#td_upah_pokok').html('');
      $('#td_bonus_mingguan').html('');
      $('#td_bonus_bulanan').html('');
      $('#td_rumus_upah').html('');
      $('#td_rumus_lembur').html('');
      $('#td_rumus_uang_makan').html('');
      $('#td_rumus_tunjangan_jabatan').html('');
      $('#td_rumus_bonus').html('');
      $('#td_rumus_premi').html('');
      $('#td_rumus_tunjangan_lain').html('');
      $.get(url, function (data) {
        $('#td_kode_upah').html(data.kode_upah);
        $('#td_upah_pokok').html(data.upah_pokok);
        $('#td_bonus_mingguan').html(data.bonus_mingguan);
        $('#td_bonus_bulanan').html(data.bonus_bulanan);
        $('#td_rumus_upah').html(data.rumus_upah);
        $('#td_rumus_lembur').html(data.rumus_lembur);
        $('#td_rumus_uang_makan').html(data.rumus_uang_makan);
        $('#td_rumus_tunjangan_jabatan').html(data.rumus_tunjangan_jabatan);
        $('#td_rumus_bonus').html(data.rumus_bonus);
        $('#td_rumus_premi').html(data.rumus_premi);
        $('#td_rumus_tunjangan_lain').html(data.rumus_tunjangan_lain);
      })
    });

    $('body').on('click', '#edit-data', function () {
      var kode = $(this).data("id");
      var url = "{{ url('rumus_upah/view/kode') }}";
      url = url.replace('kode', enc(kode.toString()));
      $('#edit_kode_upah').val('');
      $('#edit_kode_upah_lama').val('');
      $('#edit_upah_pokok').val('');
      $('#edit_bonus_mingguan').val('');
      $('#edit_bonus_bulanan').val('');
      $('#edit_rumus_upah').html('');
      $('#edit_rumus_lembur').html('');
      $('#edit_rumus_uang_makan').html('');
      $('#edit_rumus_tunjangan_jabatan').html('');
      $('#edit_rumus_bonus').html('');
      $('#edit_rumus_premi').html('');
      $('#edit_rumus_tunjangan_lain').html('');
      $.get(url, function (data) {
        $('#edit_kode_upah').val(data.kode_upah);
        $('#edit_kode_upah_lama').val(data.kode_upah);
        $('#edit_upah_pokok').val(data.upah_pokok);
        $('#edit_bonus_mingguan').val(data.bonus_mingguan);
        $('#edit_bonus_bulanan').val(data.bonus_bulanan);
        $('#edit_rumus_upah').html(data.rumus_upah);
        $('#edit_rumus_lembur').html(data.rumus_lembur);
        $('#edit_rumus_uang_makan').html(data.rumus_uang_makan);
        $('#edit_rumus_tunjangan_jabatan').html(data.rumus_tunjangan_jabatan);
        $('#edit_rumus_bonus').html(data.rumus_bonus);
        $('#edit_rumus_premi').html(data.rumus_premi);
        $('#edit_rumus_tunjangan_lain').html(data.rumus_tunjangan_lain);
      })
    });

    $('body').on('click', '#delete-data', function () {
      var kode = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('rumus_upah/delete') }}",
          data: { 'kode' : kode },
          success: function (data) {
            var oTable = $('#rumus_upah_table').dataTable();
            oTable.fnDraw(false);
            alert("Data Deleted");
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
        kode_upah: {
          required: true,
        },
        upah_pokok: {
          required: true,
        },
        bonus_mingguan: {
          required: true,
        },
        bonus_bulanan: {
          required: true,
        },
      },
      messages: {
        kode_upah: {
          required: "Kode Upah Harus Diisi",
        },
        upah_pokok: {
          required: "Upah Pokok Harus Diisi",
        },
        bonus_mingguan: {
          required: "Bonus Mingguan Harus Diisi",
        },
        bonus_bulanan: {
          required: "Bonus Bulanan Harus Diisi",
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
          url:"{{ url('rumus_upah/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#rumus_upah_table').dataTable();
            oTable.fnDraw(false);
            alert("Data Successfully Added");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });

    $('#edit_form').validate({
      rules: {
        edit_kode_upah: {
          required: true,
        },
        edit_upah_pokok: {
          required: true,
        },
        edit_bonus_mingguan: {
          required: true,
        },
        edit_bonus_bulanan: {
          required: true,
        },
      },
      messages: {
        edit_kode_upah: {
          required: "Kode Upah Harus Diisi",
        },
        edit_upah_pokok: {
          required: "Upah Pokok Harus Diisi",
        },
        edit_bonus_mingguan: {
          required: "Bonus Mingguan Harus Diisi",
        },
        edit_bonus_bulanan: {
          required: "Bonus Bulanan Harus Diisi",
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
          url:"{{ url('rumus_upah/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#rumus_upah_table').dataTable();
            oTable.fnDraw(false);
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });
  });
</script>

@endsection