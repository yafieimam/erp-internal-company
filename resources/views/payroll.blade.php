@extends('layouts.app_admin')

@section('title')
<title>PAYROLL - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Payroll</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Administrasi</li>
            <li class="breadcrumb-item">Payroll</li>
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
          <h5>Show Data Payroll</h5>
        </div>
        <div class="card-body">
          <form method="post" class="show_form" id="show_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="karyawan">Karyawan</label>
                  <select id="karyawan" name="karyawan" class="form-control select2 karyawan" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="tanggal">Range Tanggal</label>
                  <input class="form-control" type="text" name="tanggal" id="tanggal" placeholder="Range Tanggal" autocomplete="off" />
                </div>
              </div>
            </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary" id="btn-show" style="float: right;">Show Data</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="row" id="div-data" style="display: none;"> 
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h5>Data Karyawan</h5>
        </div>
        <div class="card-body">
          <table style="width: 100%;">
            <tr>
              <th>Nama</th>
              <td>:</td>
              <td id="td_nama"></td>
            </tr>
            <tr>
              <th>Nomor</th>
              <td>:</td>
              <td id="td_nomor"></td>
            </tr>
            <tr>
              <th>Perusahaan</th>
              <td>:</td>
              <td id="td_perusahaan"></td>
            </tr>
            <tr>
              <th>Unit</th>
              <td>:</td>
              <td id="td_unit"></td>
            </tr>
            <tr>
              <th>Bagian</th>
              <td>:</td>
              <td id="td_bagian"></td>
            </tr>
            <tr>
              <th>Shift</th>
              <td>:</td>
              <td id="td_shift"></td>
            </tr>
            <tr>
              <th>Lembur</th>
              <td>:</td>
              <td id="td_lembur"></td>
            </tr>
            <tr>
              <th>Terlambat</th>
              <td>:</td>
              <td id="td_terlambat"></td>
            </tr>
            <tr>
              <th>Jml Pelanggaran</th>
              <td>:</td>
              <td id="td_jumlah_pelanggaran"></td>
            </tr>
            <tr>
              <th>Gol Upah</th>
              <td>:</td>
              <td id="td_golongan_upah"></td>
            </tr>
            <tr>
              <th>Jumlah Jam Kerja</th>
              <td>:</td>
              <td id="td_jumlah_jam_kerja"></td>
            </tr>
            <tr>
              <th>Status Periode</th>
              <td>:</td>
              <td id="td_status_periode"></td>
            </tr>
            <tr>
              <th>PPh 10</th>
              <td>:</td>
              <td id="td_pph10"></td>
            </tr>
            <tr>
              <th>Astek</th>
              <td>:</td>
              <td id="td_astek"></td>
            </tr>
            <tr>
              <th>Hitungan PPh</th>
              <td>:</td>
              <td id="td_hitungan_pph"></td>
            </tr>
            <tr>
              <th>Tunj. Ms Kerja</th>
              <td>:</td>
              <td id="td_tunjangan_masa_kerja"></td>
            </tr>
            <tr>
              <th>Perhtngn Lembur</th>
              <td>:</td>
              <td id="td_perhitungan_lembur"></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h5>Data Upah</h5>
        </div>
        <div class="card-body">
          <table style="width: 100%;">
            <tr>
              <th>Upah</th>
              <td>:</td>
              <td id="td_upah"></td>
            </tr>
            <tr>
              <th>Lembur</th>
              <td>:</td>
              <td id="td_uang_lembur"></td>
            </tr>
            <tr>
              <th>Bonus</th>
              <td>:</td>
              <td id="td_bonus"></td>
            </tr>
            <tr>
              <th>Premi</th>
              <td>:</td>
              <td id="td_premi"></td>
            </tr>
            <tr>
              <th>Pembulatan</th>
              <td>:</td>
              <td id="td_pembulatan"></td>
            </tr>
            <tr>
              <th>Total</th>
              <td>:</td>
              <td id="td_total"></td>
            </tr>
          </table>
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
  $(function () {
    $('#tanggal').daterangepicker({
      autoUpdateInput: false,
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $('#tanggal').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#tanggal').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";
    $('#unit').attr("disabled","disabled");
    $('#bagian').attr("disabled","disabled");

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

    function addCommas(nStr) {
      nStr += '';
      var x = nStr.split('.');
      var x1 = x[0];
      var x2 = x.length > 1 ? ',' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
      }
      return x1 + x2;
    }

    $('#show_form').validate({
      rules: {
        karyawan: {
          required: true,
        },
        tanggal: {
          required: true,
        },
      },
      messages: {
        karyawan: {
          required: "Karyawan Harus Diisi",
        },
        tanggal: {
          required: "Tanggal Harus Diisi",
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
        var myform = document.getElementById("show_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('payroll/show') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            var total = 0;
            var total_sebelum = 0;
            var pembulatan = 0;
            $('#div-data').show();
            $('#td_nama').html(data.karyawan.nama_karyawan);
            $('#td_nomor').html(data.karyawan.nomor_karyawan);
            $('#td_perusahaan').html(data.karyawan.nama_perusahaan);
            $('#td_unit').html(data.karyawan.nama_unit);
            $('#td_bagian').html(data.karyawan.nama_bagian);
            $('#td_shift').html(data.karyawan.jam_masuk + ' - ' + data.karyawan.jam_keluar);
            $('#td_lembur').html(data.lembur[0].lembur + ' Jam');
            $('#td_terlambat').html(data.pelanggaran[0].pelanggaran + ' Menit');
            $('#td_jumlah_pelanggaran').html(data.pelanggaran[0].jumlah_pelanggaran + ' Kali');
            $('#td_golongan_upah').html(data.karyawan.golongan_upah);
            $('#td_jumlah_jam_kerja').html(data.karyawan.jumlah_jam_kerja + ' Jam');
            if(data.karyawan.status_periode == 1){
              $("#td_status_periode").html('Days');
            }else if(data.karyawan.status_periode == 2){
              $("#td_status_periode").html('Weeks');
            }else if(data.karyawan.status_periode == 3){
              $("#td_status_periode").html('Months');
            }else if(data.karyawan.status_periode == 4){
              $("#td_status_periode").html('Years');
            }
            if(data.karyawan.pph10 == 1){
              $("#td_pph10").html('Ya');
            }else if(data.karyawan.pph10 == 0){
              $("#td_pph10").html('Tidak');
            }
            if(data.karyawan.astek == 1){
              $("#td_astek").html('Bulanan');
            }else if(data.karyawan.astek == 2){
              $("#td_astek").html('Dua Mingguan');
            }else if(data.karyawan.astek == 3){
              $("#td_astek").html('Mingguan');
            }else if(data.karyawan.astek == 4){
              $("#td_astek").html('Harian');
            }
            if(data.karyawan.hitungan_pph == 1){
              $("#td_hitungan_pph").html('Bulanan');
            }else if(data.karyawan.hitungan_pph == 2){
              $("#td_hitungan_pph").html('Mingguan');
            }else if(data.karyawan.hitungan_pph == 3){
              $("#td_hitungan_pph").html('Harian');
            }
            if(data.karyawan.tunjangan_masa_kerja == 1){
              $("#td_tunjangan_masa_kerja").html('Ya');
            }else if(data.karyawan.tunjangan_masa_kerja == 0){
              $("#td_tunjangan_masa_kerja").html('Tidak');
            }
            if(data.karyawan.perhitungan_lembur == 1){
              $("#td_perhitungan_lembur").html('Normal');
            }else if(data.karyawan.perhitungan_lembur == 0){
              $("#td_perhitungan_lembur").html('Menurut Masa Kerja');
            }
            $('#td_upah').html('Rp ' + addCommas(parseFloat(data.upah[0].upah).toFixed(2)));
            $('#td_uang_lembur').html('Rp ' + addCommas(parseFloat(data.lembur[0].uang_lembur).toFixed(2)));
            $('#td_bonus').html('Rp ' + addCommas(parseFloat(data.bonus[0].bonus).toFixed(2)));
            $('#td_premi').html('Rp ' + addCommas(parseFloat(data.premi[0].premi).toFixed(2)));
            total += parseFloat(data.upah[0].upah);
            total += parseFloat(data.lembur[0].uang_lembur);
            total += parseFloat(data.bonus[0].bonus);
            total += parseFloat(data.premi[0].premi);
            total_sebelum = total;
            total = Math.ceil(total / 1000) * 1000;
            pembulatan = total - total_sebelum;
            $('#td_total').html('Rp ' + addCommas(total.toFixed(2)));
            $('#td_pembulatan').html('Rp ' + addCommas(pembulatan.toFixed(2)));
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

<script type="text/javascript">
  $(document).ready(function(){
    $('.karyawan').select2({
      placeholder: 'Karyawan',
      allowClear: true,
      ajax: {
        url: '/dropdown_karyawan',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.nama_karyawan,
                id: item.nomor_karyawan
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
  $(".karyawan").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Nama Karyawan...");
  });
  $(".karyawan").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

@endsection