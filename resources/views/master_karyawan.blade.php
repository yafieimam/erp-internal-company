@extends('layouts.app_admin')

@section('title')
<title>KARYAWAN - PT. DWI SELO GIRI MAS</title>
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
    .lihat-table {
      overflow-x: auto;
    }
    .radio-control {
      padding-left: 0 !important;
    }
    .save-btn-in {
      width: 100%;
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
          <h1 class="m-0 text-dark">Data Karyawan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item">Karyawan</li>
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
          <table id="data_karyawan_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Nomor</th>
                <th>Nama</th>
                <th>Perusahaan</th>
                <th>Bagian</th>
                <th>Alamat</th>
                <th>Status</th>
                <th width="13%"></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog modal-xl">
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
              <div class="col-4">
                <div class="form-group">
                  <label for="nama">Nama Karyawan</label>
                  <input class="form-control" type="text" name="nama" id="nama" placeholder="Nama Karyawan" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="company">Perusahaan</label>
                  <select id="company" name="company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="unit">Unit</label>
                  <select id="unit" name="unit" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="bagian">Bagian</label>
                  <select id="bagian" name="bagian" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="shift">Shift</label>
                  <select id="shift" name="shift" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="golongan_upah">Golongan Upah</label>
                  <select id="golongan_upah" name="golongan_upah" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="jenis_kelamin">Jenis Kelamin</label>
                  <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Jenis Kelamin</option>
                    <option value="L">Laki-Laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="tanggal_lahir">Tanggal Lahir</label>
                  <input class="form-control" type="text" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" autocomplete="off" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <textarea class="form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat"></textarea>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="pendidikan">Pendidikan</label>
                  <select id="pendidikan" name="pendidikan" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Pendidikan</option>
                    <option value="8">S2 / S3</option>
                    <option value="7">Diploma IV / S1</option>
                    <option value="6">Diploma III</option>
                    <option value="5">Diploma I / II</option>
                    <option value="4">SMK</option>
                    <option value="3">SMA / MA / Sederajat</option>
                    <option value="2">SMP / MTs / Sederajat</option>
                    <option value="1">SD / MI / Sederajat</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="kelas_pribadi">Kelas Pribadi</label>
                  <input class="form-control" type="text" name="kelas_pribadi" id="kelas_pribadi" placeholder="Kelas Pribadi" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="jabatan">Jabatan</label>
                  <select id="jabatan" name="jabatan" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-4"></div>
              <div class="col-4" id="div_ket_kosong" style="display: none;"></div>
              <div class="col-4" id="div_ket_kelas_pribadi" style="display: none;">
                <div class="form-group">
                  <label for="ket_kelas_pribadi">Keterangan Kelas Pribadi</label>
                  <textarea class="form-control" rows="2" name="ket_kelas_pribadi" id="ket_kelas_pribadi" placeholder="Keterangan Kelas Pribadi"></textarea>
                </div>
              </div>
              <div class="col-4" id="div_ket_jabatan" style="display: none;">
                <div class="form-group">
                  <label for="ket_jabatan">Keterangan Jabatan</label>
                  <textarea class="form-control" rows="2" name="ket_jabatan" id="ket_jabatan" placeholder="Keterangan Jabatan"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="menikah">Status Pernikahan</label>
                  <select id="menikah" name="menikah" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status Pernikahan</option>
                    <option value="Y">Sudah Menikah</option>
                    <option value="N">Belum Menikah</option>
                    <option value="C">Cerai</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="jumlah_tanggungan">Jumlah Tanggungan</label>
                  <input class="form-control" type="text" name="jumlah_tanggungan" id="jumlah_tanggungan" placeholder="Jumlah Tanggungan" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="status_karyawan">Status Karyawan</label>
                  <select id="status_karyawan" name="status_karyawan" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status Karyawan</option>
                    <option value="A">Aktif</option>
                    <option value="NA">Tidak Aktif</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="tanggal_mulai_bekerja">Tanggal Mulai Bekerja</label>
                  <input class="form-control" type="text" name="tanggal_mulai_bekerja" id="tanggal_mulai_bekerja" placeholder="Tanggal Mulai Bekerja" autocomplete="off" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="photo">Foto</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="photo" name="photo">
                      <label class="custom-file-label" for="photo">Choose file</label>
                    </div>
                  </div>
                </div>
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="nomor_hp">Nomor HP</label>
                  <input class="form-control" type="text" name="nomor_hp" id="nomor_hp" placeholder="Nomor HP" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1">
                <div class="form-group">
                  <label for="hari_kerja">Hari Kerja</label>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="hari_kerja_senin" id="hari_kerja_senin" value="1">Senin
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="hari_kerja_selasa" id="hari_kerja_selasa" value="2">Selasa
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="hari_kerja_rabu" id="hari_kerja_rabu" value="3">Rabu
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="hari_kerja_kamis" id="hari_kerja_kamis" value="4">Kamis
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="hari_kerja">&nbsp</label>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="hari_kerja_jumat" id="hari_kerja_jumat" value="5">Jumat
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="hari_kerja_sabtu" id="hari_kerja_sabtu" value="6">Sabtu
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="hari_kerja_minggu" id="hari_kerja_minggu" value="7">Minggu
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="jumlah_jam_kerja">Jumlah Jam Kerja</label>
                  <input class="form-control" type="text" name="jumlah_jam_kerja" id="jumlah_jam_kerja" placeholder="Jumlah Jam Kerja" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="status_periode">Status Periode</label>
                  <select id="status_periode" name="status_periode" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status Periode</option>
                    <option value="1">Days</option>
                    <option value="2">Weeks</option>
                    <option value="3">Months</option>
                    <option value="4">Years</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="pph">PPh 10%</label>
                  <select id="pph" name="pph" class="form-control" style="width: 100%;">
                    <option value="1" selected>Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="astek">Astek</label>
                  <select id="astek" name="astek" class="form-control" style="width: 100%;">
                    <option value="1" selected>Bulanan</option>
                    <option value="2">Dua Mingguan</option>
                    <option value="3">Mingguan</option>
                    <option value="4">Harian</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="hitungan_pph">Hitungan PPh</label>
                  <select id="hitungan_pph" name="hitungan_pph" class="form-control" style="width: 100%;">
                    <option value="1" selected>Bulanan</option>
                    <option value="2">Mingguan</option>
                    <option value="3">Harian</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="tunjangan_masa_kerja">Tunjangan Masa Kerja</label>
                  <select id="tunjangan_masa_kerja" name="tunjangan_masa_kerja" class="form-control" style="width: 100%;">
                    <option value="1" selected>Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="perhitungan_lembur">Perhitungan Lembur</label>
                  <select id="perhitungan_lembur" name="perhitungan_lembur" class="form-control" style="width: 100%;">
                    <option value="1" selected>Normal</option>
                    <option value="2">Menurut Masa Kerja</option>
                  </select>
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
    <div class="modal-dialog modal-lg">
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
                  <td>Nomor</td>
                  <td>:</td>
                  <td id="td_nomor"></td>
                  <td>Nama</td>
                  <td>:</td>
                  <td id="td_nama"></td>
                </tr>
                <tr>
                  <td>Perusahaan</td>
                  <td>:</td> 
                  <td id="td_company"></td>
                  <td>Unit</td>
                  <td>:</td>
                  <td id="td_unit"></td>
                </tr>
                <tr>
                  <td>Bagian</td>
                  <td>:</td>
                  <td id="td_bagian"></td>
                  <td>Shift</td>
                  <td>:</td>
                  <td id="td_shift"></td>
                </tr>
                <tr>
                  <td>Jenis Kelamin</td>
                  <td>:</td>
                  <td id="td_jenis_kelamin"></td>
                  <td>Tanggal Lahir</td>
                  <td>:</td>
                  <td id="td_tanggal_lahir"></td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>:</td>
                  <td id="td_alamat"></td>
                  <td>Pendidikan</td>
                  <td>:</td>
                  <td id="td_pendidikan"></td>
                </tr>
                <tr>
                  <td>Kelas Pribadi</td>
                  <td>:</td>
                  <td id="td_kelas_pribadi"></td>
                  <td>Jabatan</td>
                  <td>:</td>
                  <td id="td_jabatan"></td>
                </tr>
                <tr>
                  <td>Ket Kelas Pribadi</td>
                  <td>:</td>
                  <td id="td_ket_kelas_pribadi"></td>
                  <td>Ket Jabatan</td>
                  <td>:</td>
                  <td id="td_ket_jabatan"></td>
                </tr>
                <tr>
                  <td>Status Pernikahan</td>
                  <td>:</td>
                  <td id="td_status_pernikahan"></td>
                  <td>Jumlah Tanggungan</td>
                  <td>:</td>
                  <td id="td_jumlah_tanggungan"></td>
                </tr>
                <tr>
                  <td>Status Karyawan</td>
                  <td>:</td>
                  <td id="td_status_karyawan"></td>
                  <td>Nomor HP</td>
                  <td>:</td>
                  <td id="td_nomor_hp"></td>
                </tr>
                <tr>
                  <td>Tanggal Mulai Bekerja</td>
                  <td>:</td>
                  <td id="td_tanggal_mulai_bekerja"></td>
                  <td>Foto</td>
                  <td>:</td>
                  <td id="td_foto"></td>
                </tr>
                <tr>
                  <td>Golongan Upah</td>
                  <td>:</td>
                  <td id="td_golongan_upah"></td>
                  <td>Hari Kerja</td>
                  <td>:</td>
                  <td id="td_hari_kerja"></td>
                </tr>
                <tr>
                  <td>Jumlah Jam Kerja</td>
                  <td>:</td>
                  <td id="td_jumlah_jam_kerja"></td>
                  <td>Status Periode</td>
                  <td>:</td>
                  <td id="td_status_periode"></td>
                </tr>
                <tr>
                  <td>PPh 10%</td>
                  <td>:</td>
                  <td id="td_pph"></td>
                  <td>Astek</td>
                  <td>:</td>
                  <td id="td_astek"></td>
                </tr>
                <tr>
                  <td>Hitungan PPh</td>
                  <td>:</td>
                  <td id="td_hitungan_pph"></td>
                  <td>Tunjangan Masa Kerja</td>
                  <td>:</td>
                  <td id="td_tunjangan_masa_kerja"></td>
                </tr>
                <tr>
                  <td>Perhitungan Lembur</td>
                  <td>:</td>
                  <td id="td_perhitungan_lembur"></td>
                  <td colspan="3"></td>
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
    <div class="modal-dialog modal-xl">
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
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_nama">Nama Karyawan</label>
                  <input class="form-control" type="hidden" name="edit_nomor" id="edit_nomor" />
                  <input class="form-control" type="text" name="edit_nama" id="edit_nama" placeholder="Nama Bagian" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_company">Perusahaan</label>
                  <select id="edit_company" name="edit_company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_unit">Unit</label>
                  <select id="edit_unit" name="edit_unit" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_bagian">Bagian</label>
                  <select id="edit_bagian" name="edit_bagian" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_shift">Shift</label>
                  <select id="edit_shift" name="edit_shift" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                  <select id="edit_jenis_kelamin" name="edit_jenis_kelamin" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Jenis Kelamin</option>
                    <option value="L">Laki-Laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                  <input class="form-control" type="text" name="edit_tanggal_lahir" id="edit_tanggal_lahir" placeholder="Tanggal Lahir" autocomplete="off" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_alamat">Alamat</label>
                  <textarea class="form-control" rows="3" name="edit_alamat" id="edit_alamat" placeholder="Alamat"></textarea>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_pendidikan">Pendidikan</label>
                  <select id="edit_pendidikan" name="edit_pendidikan" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Pendidikan</option>
                    <option value="8">S2 / S3</option>
                    <option value="7">Diploma IV / S1</option>
                    <option value="6">Diploma III</option>
                    <option value="5">Diploma I / II</option>
                    <option value="4">SMK</option>
                    <option value="3">SMA / MA / Sederajat</option>
                    <option value="2">SMP / MTs / Sederajat</option>
                    <option value="1">SD / MI / Sederajat</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_kelas_pribadi">Kelas Pribadi</label>
                  <input class="form-control" type="text" name="edit_kelas_pribadi" id="edit_kelas_pribadi" placeholder="Kelas Pribadi" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_jabatan">Jabatan</label>
                  <select id="edit_jabatan" name="edit_jabatan" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_menikah">Status Pernikahan</label>
                  <select id="edit_menikah" name="edit_menikah" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status Pernikahan</option>
                    <option value="Y">Sudah Menikah</option>
                    <option value="N">Belum Menikah</option>
                    <option value="C">Cerai</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4" id="div_edit_ket_kosong" style="display: none;"></div>
              <div class="col-4" id="div_edit_ket_kelas_pribadi" style="display: none;">
                <div class="form-group">
                  <label for="edit_ket_kelas_pribadi">Keterangan Kelas Pribadi</label>
                  <textarea class="form-control" rows="2" name="edit_ket_kelas_pribadi" id="edit_ket_kelas_pribadi" placeholder="Keterangan Kelas Pribadi"></textarea>
                </div>
              </div>
              <div class="col-4" id="div_edit_ket_jabatan" style="display: none;">
                <div class="form-group">
                  <label for="edit_ket_jabatan">Keterangan Jabatan</label>
                  <textarea class="form-control" rows="2" name="edit_ket_jabatan" id="edit_ket_jabatan" placeholder="Keterangan Jabatan"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_jumlah_tanggungan">Jumlah Tanggungan</label>
                  <input class="form-control" type="text" name="edit_jumlah_tanggungan" id="edit_jumlah_tanggungan" placeholder="Jumlah Tanggungan" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_status_karyawan">Status Karyawan</label>
                  <select id="edit_status_karyawan" name="edit_status_karyawan" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status Karyawan</option>
                    <option value="A">Aktif</option>
                    <option value="NA">Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_nomor_hp">Nomor HP</label>
                  <input class="form-control" type="text" name="edit_nomor_hp" id="edit_nomor_hp" placeholder="Nomor HP" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_tanggal_mulai_bekerja">Tanggal Mulai Bekerja</label>
                  <input class="form-control" type="text" name="edit_tanggal_mulai_bekerja" id="edit_tanggal_mulai_bekerja" placeholder="Tanggal Mulai Bekerja" autocomplete="off" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_photo">Foto</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="edit_photo" name="edit_photo">
                      <label class="custom-file-label" for="edit_photo">Choose file</label>
                    </div>
                  </div>
                </div>
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <div class="custom-control custom-radio radio-control">
                    <a target="_blank" id="lihat_foto" class="btn btn-primary save-btn-in">Lihat Foto</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_golongan_upah">Golongan Upah</label>
                  <select id="edit_golongan_upah" name="edit_golongan_upah" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="edit_hari_kerja">Hari Kerja</label>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="edit_hari_kerja_senin" id="edit_hari_kerja_senin" value="1">Senin
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="edit_hari_kerja_selasa" id="edit_hari_kerja_selasa" value="2">Selasa
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="edit_hari_kerja_rabu" id="edit_hari_kerja_rabu" value="3">Rabu
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="edit_hari_kerja_kamis" id="edit_hari_kerja_kamis" value="4">Kamis
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="edit_hari_kerja">&nbsp</label>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="edit_hari_kerja_jumat" id="edit_hari_kerja_jumat" value="5">Jumat
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="edit_hari_kerja_sabtu" id="edit_hari_kerja_sabtu" value="6">Sabtu
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="edit_hari_kerja_minggu" id="edit_hari_kerja_minggu" value="7">Minggu
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="edit_jumlah_jam_kerja">Jumlah Jam Kerja</label>
                  <input class="form-control" type="text" name="edit_jumlah_jam_kerja" id="edit_jumlah_jam_kerja" placeholder="Jumlah Jam Kerja" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_status_periode">Status Periode</label>
                  <select id="edit_status_periode" name="edit_status_periode" class="form-control" style="width: 100%;">
                    <option value="" selected>Pilih Status Periode</option>
                    <option value="1">Days</option>
                    <option value="2">Weeks</option>
                    <option value="3">Months</option>
                    <option value="4">Years</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="edit_pph">PPh 10%</label>
                  <select id="edit_pph" name="edit_pph" class="form-control" style="width: 100%;">
                    <option value="1" selected>Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_astek">Astek</label>
                  <select id="edit_astek" name="edit_astek" class="form-control" style="width: 100%;">
                    <option value="1" selected>Bulanan</option>
                    <option value="2">Dua Mingguan</option>
                    <option value="3">Mingguan</option>
                    <option value="4">Harian</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_hitungan_pph">Hitungan PPh</label>
                  <select id="edit_hitungan_pph" name="edit_hitungan_pph" class="form-control" style="width: 100%;">
                    <option value="1" selected>Bulanan</option>
                    <option value="2">Mingguan</option>
                    <option value="3">Harian</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_tunjangan_masa_kerja">Tunjangan Masa Kerja</label>
                  <select id="edit_tunjangan_masa_kerja" name="edit_tunjangan_masa_kerja" class="form-control" style="width: 100%;">
                    <option value="1" selected>Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_perhitungan_lembur">Perhitungan Lembur</label>
                  <select id="edit_perhitungan_lembur" name="edit_perhitungan_lembur" class="form-control" style="width: 100%;">
                    <option value="1" selected>Normal</option>
                    <option value="2">Menurut Masa Kerja</option>
                  </select>
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
  $(function () {
    $('#tanggal_lahir').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#tanggal_mulai_bekerja').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal_lahir').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal_mulai_bekerja').flatpickr({
      allowInput: true,
      disableMobile: true
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#data_karyawan_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_karyawan/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'nomor',
           name:'nomor'
         },
         {
           data:'nama',
           name:'nama'
         },
         {
           data:'company',
           name:'company'
         },
         {
           data:'bagian',
           name:'bagian'
         },
         {
           data:'alamat',
           name:'alamat'
         },
         {
           data:'status',
           name:'status'
         },
         {
           data:'action',
           name:'action'
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

    function load_data_karyawan()
    {
      table = $('#data_karyawan_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_karyawan/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'nomor',
           name:'nomor'
         },
         {
           data:'nama',
           name:'nama'
         },
         {
           data:'company',
           name:'company'
         },
         {
           data:'bagian',
           name:'bagian'
         },
         {
           data:'alamat',
           name:'alamat'
         },
         {
           data:'status',
           name:'status'
         },
         {
           data:'action',
           name:'action'
         }
       ]
     });
    }

    $('body').on('click', '#btn_input_data', function () {
      $('#unit').attr("disabled","disabled");
      $('#bagian').attr("disabled","disabled");

      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      var url_upah = "{{ url('get_kode_upah') }}";
      $.get(url_upah, function (data) {
        $('#golongan_upah').children().remove().end().append('<option value="" selected>Pilih Golongan Upah</option>');
        $.each(data, function(k, v) {
          $('#golongan_upah').append('<option value="' + v.kode_upah + '">' + v.kode_upah + '</option>');
        });
      })

      $("#jabatan").change(function() {
        var kelas = $('#kelas_pribadi').val();
        if ($(this).val() != '' && kelas == '') {
          $('#div_ket_kosong').show();
          $('#div_ket_jabatan').show();
        }else if($(this).val() != '' && kelas != ''){
          $('#div_ket_kosong').hide();
          $('#div_ket_jabatan').show();
        }else{
          $('#div_ket_kosong').hide();
          $('#div_ket_jabatan').hide();
        }
      });
      $('#kelas_pribadi').on('keyup', function(){
        var jabatan = $('#jabatan').val();
        if($(this).val() != '' && jabatan == ''){
          $('#div_ket_kosong').hide();
          $('#div_ket_kelas_pribadi').show();
        }else if($(this).val() != '' && jabatan != ''){
          $('#div_ket_kosong').hide();
          $('#div_ket_kelas_pribadi').show();
        }else if($(this).val() == '' && jabatan != ''){
          $('#div_ket_kosong').show();
          $('#div_ket_kelas_pribadi').hide();
        }else{
          $('#div_ket_kosong').hide();
          $('#div_ket_kelas_pribadi').hide();
        }
      });
      $("#company").change(function() {
        var val = $(this).val();

        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(val.toString()));
        $.get(url_unit, function (data) {
          if(data.length == 0){
            $('#unit').children().remove().end();
            $('#unit').attr("disabled","disabled");
            $('#bagian').children().remove().end();
            $('#bagian').attr("disabled","disabled");
          }else{
            $('#unit').removeAttr('disabled');
            $('#unit').children().remove().end().append('<option value="" selected>Pilih Unit</option>');
            $.each(data, function(k, v) {
              $('#unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
            });
          }
        })
      });
      $("#unit").change(function() {
        var val = $(this).val();
        var company = $('#company').val();

        var url_bagian = "{{ url('get_bagian/company/unit') }}";
        url_bagian = url_bagian.replace('company', enc(company.toString()));
        url_bagian = url_bagian.replace('unit', enc(val.toString()));
        $.get(url_bagian, function (data) {
          if(data.length == 0){
            $('#bagian').children().remove().end();
            $('#bagian').attr("disabled","disabled");
          }else{
            $('#bagian').removeAttr('disabled');
            $('#bagian').children().remove().end().append('<option value="" selected>Pilih Bagian</option>');
            $.each(data, function(k, v) {
              $('#bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
            });
          }
        })
      });
      var url_shift = "{{ url('get_shift') }}";
      $.get(url_shift, function (data) {
        $('#shift').children().remove().end().append('<option value="" selected>Pilih Shift</option>');
        $.each(data, function(k, v) {
          $('#shift').append('<option value="' + v.kode_shift + '">' + v.nama_shift + ' (' + v.jam_masuk + ' - ' + v.jam_keluar + ')</option>');
        });
      })
      var url_jabatan = "{{ url('get_jabatan') }}";
      $.get(url_jabatan, function (data) {
        $('#jabatan').children().remove().end().append('<option value="" selected>Pilih Jabatan</option>');
        $.each(data, function(k, v) {
          $('#jabatan').append('<option value="' + v.id + '">' + v.name + '</option>');
        });
      })
      $("#menikah").change(function() {
        var val = $(this).val();

        if(val == "Y" || val == "C"){
          $("#jumlah_tanggungan").prop('disabled', false);
        }else if(val == "N"){
          $("#jumlah_tanggungan").prop('disabled', true);
        }else{
          $("#jumlah_tanggungan").prop('disabled', false);
        }
      });
    });

    $('body').on('click', '#view-data', function () {
      var kode = $(this).data("id");
      var url = "{{ url('master_karyawan/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#td_nomor').html('');
      $('#td_nama').html('');
      $('#td_company').html('');
      $('#td_unit').html('');
      $('#td_bagian').html('');
      $('#td_shift').html('');
      $('#td_jenis_kelamin').html('');
      $('#td_tanggal_lahir').html('');
      $('#td_alamat').html('');
      $('#td_pendidikan').html('');
      $('#td_kelas_pribadi').html('');
      $('#td_jabatan').html('');
      $('#td_status_pernikahan').html('');
      $('#td_status_karyawan').html('');
      $('#td_jumlah_tanggungan').html('');
      $('#td_nomor_hp').html('');
      $('#td_tanggal_mulai_bekerja').html('');
      $('#td_foto').html('');
      $('#td_ket_kelas_pribadi').html('');
      $('#td_ket_jabatan').html('');
      $('#td_golongan_upah').html('');
      $('#td_hari_kerja').html('');
      $('#td_jumlah_jam_kerja').html('');
      $('#td_status_periode').html('');
      $('#td_pph').html('');
      $('#td_astek').html('');
      $('#td_hitungan_pph').html('');
      $('#td_tunjangan_masa_kerja').html('');
      $('#td_perhitungan_lembur').html('');
      $.get(url, function (data) {
        $('#td_nomor').html(data.nomor);
        $('#td_nama').html(data.nama);
        $('#td_company').html(data.company);
        $('#td_unit').html(data.unit);
        $('#td_bagian').html(data.bagian);
        $('#td_shift').html(data.shift);
        if(data.jenis_kelamin == "L"){
          $('#td_jenis_kelamin').html('Laki-Laki');
        }else if(data.jenis_kelamin == "P"){
          $('#td_jenis_kelamin').html('Perempuan');
        }
        $('#td_tanggal_lahir').html(data.tanggal_lahir);
        $('#td_alamat').html(data.alamat);
        $('#td_pendidikan').html(data.pendidikan);
        $('#td_kelas_pribadi').html(data.kelas_pribadi);
        $('#td_jabatan').html(data.jabatan);
        $('#td_status_pernikahan').html(data.status_pernikahan);
        if(data.status_pernikahan == "Y"){
          $('#td_status_pernikahan').html('Sudah Menikah');
        }else if(data.status_pernikahan == "N"){
          $('#td_status_pernikahan').html('Belum Menikah');
        }else if(data.status_pernikahan == "C"){
          $('#td_status_pernikahan').html('Cerai');
        }
        if(data.status_karyawan == "A"){
          $('#td_status_karyawan').html('Aktif');
        }else if(data.status_karyawan == "NA"){
          $('#td_status_karyawan').html('Tidak Aktif');
        }
        if(data.jumlah_tanggungan == null || data.jumlah_tanggungan == ''){
          $("#td_jumlah_tanggungan").html('---');
        }else{
          $("#td_jumlah_tanggungan").html(data.jumlah_tanggungan);
        }
        if(data.ket_kelas_pribadi == null || data.ket_kelas_pribadi == ''){
          $("#td_ket_kelas_pribadi").html('---');
        }else{
          $("#td_ket_kelas_pribadi").html(data.ket_kelas_pribadi);
        }
        if(data.ket_jabatan == null || data.ket_jabatan == ''){
          $("#td_ket_jabatan").html('---');
        }else{
          $("#td_ket_jabatan").html(data.ket_jabatan);
        }
        $('#td_nomor_hp').html(data.nomor_hp);
        $('#td_tanggal_mulai_bekerja').html(data.tanggal_mulai_bekerja);
        if(data.photo == null || data.photo == ''){
          $("#td_foto").html('---');
        }else{
          $("#td_foto").html('<a target="_blank" href="' + '../data_file/' + data.photo + '">Lihat Foto</a>');
        }
        $("#td_golongan_upah").html(data.golongan_upah);
        $("#td_hari_kerja").html(data.hari_kerja);
        $("#td_jumlah_jam_kerja").html(data.jumlah_jam_kerja + ' Jam');
        if(data.status_periode == 1){
          $("#td_status_periode").html('Days');
        }else if(data.status_periode == 2){
          $("#td_status_periode").html('Weeks');
        }else if(data.status_periode == 3){
          $("#td_status_periode").html('Months');
        }else if(data.status_periode == 4){
          $("#td_status_periode").html('Years');
        }
        if(data.pph10 == 1){
          $("#td_pph").html('Ya');
        }else if(data.pph10 == 0){
          $("#td_pph").html('Tidak');
        }
        if(data.astek == 1){
          $("#td_astek").html('Bulanan');
        }else if(data.astek == 2){
          $("#td_astek").html('Dua Mingguan');
        }else if(data.astek == 3){
          $("#td_astek").html('Mingguan');
        }else if(data.astek == 4){
          $("#td_astek").html('Harian');
        }
        if(data.hitungan_pph == 1){
          $("#td_hitungan_pph").html('Bulanan');
        }else if(data.hitungan_pph == 2){
          $("#td_hitungan_pph").html('Mingguan');
        }else if(data.hitungan_pph == 3){
          $("#td_hitungan_pph").html('Harian');
        }
        if(data.tunjangan_masa_kerja == 1){
          $("#td_tunjangan_masa_kerja").html('Ya');
        }else if(data.tunjangan_masa_kerja == 0){
          $("#td_tunjangan_masa_kerja").html('Tidak');
        }
        if(data.perhitungan_lembur == 1){
          $("#td_perhitungan_lembur").html('Normal');
        }else if(data.perhitungan_lembur == 0){
          $("#td_perhitungan_lembur").html('Menurut Masa Kerja');
        }
      })
    });

    $("#edit_company").change(function(e) {
      e.stopImmediatePropagation();
      var val = $(this).val();
      var url_unit = "{{ url('get_unit/company') }}";
      url_unit = url_unit.replace('company', enc(val.toString()));
      $.get(url_unit, function (data_unit) {
        if(data_unit.length == 0){
          $('#edit_unit').children().remove().end();
          $('#edit_unit').attr("disabled","disabled");
          $('#edit_bagian').children().remove().end();
          $('#edit_bagian').attr("disabled","disabled");
        }else{
          $('#edit_unit').removeAttr('disabled');
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
        }
      })
    });

    $("#edit_unit").change(function(e) {
      e.stopImmediatePropagation();
      var val = $(this).val();
      var company = $('#edit_company').val();
      var url_bagian = "{{ url('get_bagian/company/unit') }}";
      url_bagian = url_bagian.replace('company', enc(company.toString()));
      url_bagian = url_bagian.replace('unit', enc(val.toString()));
      $.get(url_bagian, function (data) {
        if(data.length == 0){
          $('#edit_bagian').children().remove().end();
          $('#edit_bagian').attr("disabled","disabled");
        }else{
          $('#edit_bagian').removeAttr('disabled');
          $('#edit_bagian').children().remove().end().append('<option value="">Pilih Bagian</option>');
          $.each(data, function(k, v) {
            $('#edit_bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
          });
        }
      })
    });

    $('body').on('click', '#edit-data', function () {
      var kode = $(this).data("id");
      $('#edit_unit').attr("disabled","disabled");
      $('#edit_bagian').attr("disabled","disabled");

      var url_company = "{{ url('get_company') }}";
      $.get(url_company, function (data) {
        $('#edit_company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#edit_company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })
      var url_shift = "{{ url('get_shift') }}";
      $.get(url_shift, function (data) {
        $('#edit_shift').children().remove().end().append('<option value="" selected>Pilih Shift</option>');
        $.each(data, function(k, v) {
          $('#edit_shift').append('<option value="' + v.kode_shift + '">' + v.nama_shift + ' (' + v.jam_masuk + ' - ' + v.jam_keluar + ')</option>');
        });
      })
      var url_jabatan = "{{ url('get_jabatan') }}";
      $.get(url_jabatan, function (data) {
        $('#edit_jabatan').children().remove().end().append('<option value="">Pilih Jabatan</option>');
        $.each(data, function(k, v) {
          $('#edit_jabatan').append('<option value="' + v.id + '">' + v.name + '</option>');
        });
      })
      var url_upah = "{{ url('get_kode_upah') }}";
      $.get(url_upah, function (data) {
        $('#edit_golongan_upah').children().remove().end().append('<option value="" selected>Pilih Golongan Upah</option>');
        $.each(data, function(k, v) {
          $('#edit_golongan_upah').append('<option value="' + v.kode_upah + '">' + v.kode_upah + '</option>');
        });
      })
      $("#edit_jabatan").change(function() {
        var kelas = $('#edit_kelas_pribadi').val();
        if ($(this).val() != '' && kelas == '') {
          $('#div_edit_ket_kosong').show();
          $('#div_edit_ket_jabatan').show();        
        }else if($(this).val() != '' && kelas != ''){
          $('#div_edit_ket_kosong').hide();
          $('#div_edit_ket_jabatan').show();
        }else{
          $('#div_edit_ket_kosong').hide();
          $('#div_edit_ket_jabatan').hide();
        }
      });
      $('#edit_kelas_pribadi').on('keyup', function(){
        var jabatan = $('#edit_jabatan').val();
        if($(this).val() != '' && jabatan == ''){
          $('#div_edit_ket_kosong').hide();
          $('#div_edit_ket_kelas_pribadi').show();
        }else if($(this).val() != '' && jabatan != ''){
          $('#div_edit_ket_kosong').hide();
          $('#div_edit_ket_kelas_pribadi').show();
        }else if($(this).val() == '' && jabatan != ''){
          $('#div_edit_ket_kosong').show();
          $('#div_edit_ket_kelas_pribadi').hide();
        }else{
          $('#div_edit_ket_kosong').hide();
          $('#div_edit_ket_kelas_pribadi').hide();
        }
      });
      $("#edit_menikah").change(function() {
        var val = $(this).val();

        if(val == "Y" || val == "C"){
          $("#edit_jumlah_tanggungan").prop('disabled', false);
        }else if(val == "N"){
          $("#edit_jumlah_tanggungan").prop('disabled', true);
        }else{
          $("#edit_jumlah_tanggungan").prop('disabled', false);
        }
      });
      var url = "{{ url('master_karyawan/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#edit_nomor').val('');
      $('#edit_nama').val('');
      $('#edit_tanggal_lahir').val('');
      $('#edit_alamat').html('');
      $('#edit_kelas_pribadi').val('');
      $('#edit_jumlah_tanggungan').val('');
      $('#edit_nomor_hp').val('');
      $('#edit_tanggal_mulai_bekerja').val('');
      $('#edit_photo').val('');
      // $('#edit_company').val('').trigger('change');
      // $('#edit_unit').val('').trigger('change');
      // $('#edit_bagian').val('').trigger('change');
      $('#edit_shift').val('');
      $('#edit_jenis_kelamin').val('');
      $('#edit_pendidikan').val('');
      $('#edit_jabatan').val('').trigger('change');
      $('#edit_menikah').val('').trigger('change');
      $('#edit_status_karyawan').val('');
      $('#edit_photo').val('');
      $('#edit_golongan_upah').val('');
      $("#edit_hari_kerja_senin"). prop("checked", false);
      $("#edit_hari_kerja_selasa"). prop("checked", false);
      $("#edit_hari_kerja_rabu"). prop("checked", false);
      $("#edit_hari_kerja_kamis"). prop("checked", false);
      $("#edit_hari_kerja_jumat"). prop("checked", false);
      $("#edit_hari_kerja_sabtu"). prop("checked", false);
      $("#edit_hari_kerja_minggu"). prop("checked", false);
      $('#edit_jumlah_jam_kerja').val('');
      $('#edit_status_periode').val('');
      $('#edit_pph').val('');
      $('#edit_astek').val('');
      $('#edit_hitungan_pph').val('');
      $('#edit_tunjangan_masa_kerja').val('');
      $('#edit_perhitungan_lembur').val('');
      $.get(url, function (data) {
        $('#edit_nomor').val(data.nomor);
        $('#edit_nama').val(data.nama);
        $('#edit_tanggal_lahir').val(data.tanggal_lahir);
        $('#edit_jumlah_tanggungan').val(data.jumlah_tanggungan);
        $('#edit_tanggal_mulai_bekerja').val(data.tanggal_mulai_bekerja);
        $('#edit_nomor_hp').val(data.nomor_hp);
        $('#edit_company').val(data.kode_perusahaan);
        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(data.kode_perusahaan.toString()));
        $.get(url_unit, function (data_unit) {
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
          $('#edit_unit').removeAttr('disabled');
          $('#edit_unit').val(data.kode_unit);
        })
        var url_bagian = "{{ url('get_bagian/company/unit') }}";
        url_bagian = url_bagian.replace('company', enc(data.kode_perusahaan.toString()));
        url_bagian = url_bagian.replace('unit', enc(data.kode_unit.toString()));
        $.get(url_bagian, function (data_bag) {
          $('#edit_bagian').children().remove().end().append('<option value="">Pilih Bagian</option>');
          $.each(data_bag, function(k, v) {
            $('#edit_bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
          });
          $('#edit_bagian').removeAttr('disabled');
          $('#edit_bagian').val(data.kode_bagian).trigger('change');
        })
        $('#edit_shift').val(data.kode_shift).trigger('change');
        $('#edit_pendidikan').val(data.kelas_pendidikan).trigger('change');
        $('#edit_jenis_kelamin').val(data.jenis_kelamin).trigger('change');
        $('#edit_menikah').val(data.status_pernikahan).trigger('change');
        $('#edit_status_karyawan').val(data.status_karyawan).trigger('change');
        $("#edit_alamat").html(data.alamat);
        $('#edit_kelas_pribadi').val(data.kelas_pribadi);
        $('#edit_jabatan').val(data.kelas_jabatan).trigger('change');
        if(data.kelas_pribadi == null || data.kelas_pribadi == ''){
          $('#div_edit_ket_kelas_pribadi').hide();
        }else{
          $('#div_edit_ket_kelas_pribadi').show();
          $("#edit_ket_kelas_pribadi").html(data.ket_kelas_pribadi);
        }
        if(data.kelas_jabatan == null || data.kelas_jabatan == ''){
          $('#div_edit_ket_jabatan').hide();
        }else{
          $('#div_edit_ket_jabatan').show();
          $("#edit_ket_jabatan").html(data.ket_jabatan);
        }
        if(data.photo == null){
          $('#lihat_foto').html('No Photo');
          $('#lihat_foto').addClass('disabled');
          $('#lihat_foto').attr('href', '#');
        }else{
          $('#lihat_foto').html('Lihat Foto');
          $('#lihat_foto').removeClass('disabled');
          $('#lihat_foto').attr('href', '../data_file/' + data.photo);
        }
        $('#edit_golongan_upah').val(data.golongan_upah);
        $('#edit_jumlah_jam_kerja').val(data.jumlah_jam_kerja);
        $('#edit_status_periode').val(data.status_periode);
        $('#edit_pph').val(data.pph10);
        $('#edit_astek').val(data.astek);
        $('#edit_hitungan_pph').val(data.hitungan_pph);
        $('#edit_tunjangan_masa_kerja').val(data.tunjangan_masa_kerja);
        $('#edit_perhitungan_lembur').val(data.perhitungan_lembur);
        if(data.senin == 1){
          $("#edit_hari_kerja_senin"). prop("checked", true);
        }else{
          $("#edit_hari_kerja_senin"). prop("checked", false);
        }
        if(data.selasa == 1){
          $("#edit_hari_kerja_selasa"). prop("checked", true);
        }else{
          $("#edit_hari_kerja_selasa"). prop("checked", false);
        }
        if(data.rabu == 1){
          $("#edit_hari_kerja_rabu"). prop("checked", true);
        }else{
          $("#edit_hari_kerja_rabu"). prop("checked", false);
        }
        if(data.kamis == 1){
          $("#edit_hari_kerja_kamis"). prop("checked", true);
        }else{
          $("#edit_hari_kerja_kamis"). prop("checked", false);
        }
        if(data.jumat == 1){
          $("#edit_hari_kerja_jumat"). prop("checked", true);
        }else{
          $("#edit_hari_kerja_jumat"). prop("checked", false);
        }
        if(data.sabtu == 1){
          $("#edit_hari_kerja_sabtu"). prop("checked", true);
        }else{
          $("#edit_hari_kerja_sabtu"). prop("checked", false);
        }
        if(data.minggu == 1){
          $("#edit_hari_kerja_minggu"). prop("checked", true);
        }else{
          $("#edit_hari_kerja_minggu"). prop("checked", false);
        }
      })
    });

    $('body').on('click', '#delete-data', function () {
      var nomor = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('master_karyawan/delete') }}",
          data: { 'nomor' : nomor },
          success: function (data) {
            var oTable = $('#data_karyawan_table').dataTable();
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
        nama: {
          required: true,
        },
        company: {
          required: true,
        },
        bagian: {
          required: true,
        },
        shift: {
          required: true,
        },
        jenis_kelamin: {
          required: true,
        },
        tanggal_lahir: {
          required: true,
        },
        alamat: {
          required: true,
        },
        pendidikan: {
          required: true,
        },
        menikah: {
          required: true,
        },
        status_karyawan: {
          required: true,
        },
        nomor_hp: {
          required: true,
        },
        tanggal_mulai_bekerja: {
          required: true,
        },
        golongan_upah: {
          required: true,
        },
        jumlah_jam_kerja: {
          required: true,
        },
        status_periode: {
          required: true,
        },
        pph: {
          required: true,
        },
        astek: {
          required: true,
        },
        hitungan_pph: {
          required: true,
        },
        tunjangan_masa_kerja: {
          required: true,
        },
        perhitungan_lembur: {
          required: true,
        },
        photo: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        nama: {
          required: "Nama Karyawan Harus Diisi",
        },
        company: {
          required: "Perusahaan Harus Diisi",
        },
        bagian: {
          required: "Bagian Harus Diisi",
        },
        shift: {
          required: "Shift Harus Diisi",
        },
        jenis_kelamin: {
          required: "Jenis Kelamin Harus Diisi",
        },
        tanggal_lahir: {
          required: "Tanggal Lahir Harus Diisi",
        },
        alamat: {
          required: "Alamat Harus Diisi",
        },
        pendidikan: {
          required: "Pendidikan Harus Diisi",
        },
        menikah: {
          required: "Status Pernikahan Harus Diisi",
        },
        status_karyawan: {
          required: "Status Karyawan Harus Diisi",
        },
        nomor_hp: {
          required: "Nomor HP Harus Diisi",
        },
        tanggal_mulai_bekerja: {
          required: "Tanggal Mulai Bekerja Harus Diisi",
        },
        golongan_upah: {
          required: "Golongan Upah Harus Diisi",
        },
        jumlah_jam_kerja: {
          required: "Jumlah Jam Kerja Harus Diisi",
        },
        status_periode: {
          required: "Status Periode Harus Diisi",
        },
        pph: {
          required: "PPh Harus Diisi",
        },
        astek: {
          required: "Astek Harus Diisi",
        },
        hitungan_pph: {
          required: "Hitungan PPh Harus Diisi",
        },
        tunjangan_masa_kerja: {
          required: "Tunjangan Masa Kerja Harus Diisi",
        },
        perhitungan_lembur: {
          required: "Perhitungan Lembur Harus Diisi",
        },
        photo: {
          extension: "File Format Only JPG, JPEG, or PDF",
          filesize: "Max File Size is 2 MB"
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
          url:"{{ url('master_karyawan/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_karyawan_table').dataTable();
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
        edit_nama: {
          required: true,
        },
        edit_company: {
          required: true,
        },
        edit_bagian: {
          required: true,
        },
        edit_shift: {
          required: true,
        },
        edit_jenis_kelamin: {
          required: true,
        },
        edit_tanggal_lahir: {
          required: true,
        },
        edit_alamat: {
          required: true,
        },
        edit_pendidikan: {
          required: true,
        },
        edit_menikah: {
          required: true,
        },
        edit_status_karyawan: {
          required: true,
        },
        edit_nomor_hp: {
          required: true,
        },
        edit_tanggal_mulai_bekerja: {
          required: true,
        },
        edit_golongan_upah: {
          required: true,
        },
        edit_jumlah_jam_kerja: {
          required: true,
        },
        edit_status_periode: {
          required: true,
        },
        edit_pph: {
          required: true,
        },
        edit_astek: {
          required: true,
        },
        edit_hitungan_pph: {
          required: true,
        },
        edit_tunjangan_masa_kerja: {
          required: true,
        },
        edit_perhitungan_lembur: {
          required: true,
        },
        edit_photo: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        edit_nama: {
          required: "Nama Karyawan Harus Diisi",
        },
        edit_company: {
          required: "Perusahaan Harus Diisi",
        },
        edit_bagian: {
          required: "Bagian Harus Diisi",
        },
        edit_shift: {
          required: "Shift Harus Diisi",
        },
        edit_jenis_kelamin: {
          required: "Jenis Kelamin Harus Diisi",
        },
        edit_tanggal_lahir: {
          required: "Tanggal Lahir Harus Diisi",
        },
        edit_alamat: {
          required: "Alamat Harus Diisi",
        },
        edit_pendidikan: {
          required: "Pendidikan Harus Diisi",
        },
        edit_menikah: {
          required: "Status Pernikahan Harus Diisi",
        },
        edit_status_karyawan: {
          required: "Status Karyawan Harus Diisi",
        },
        edit_nomor_hp: {
          required: "Nomor HP Harus Diisi",
        },
        edit_tanggal_mulai_bekerja: {
          required: "Tanggal Mulai Bekerja Harus Diisi",
        },
        edit_golongan_upah: {
          required: "Golongan Upah Harus Diisi",
        },
        edit_jumlah_jam_kerja: {
          required: "Jumlah Jam Kerja Harus Diisi",
        },
        edit_status_periode: {
          required: "Status Periode Harus Diisi",
        },
        edit_pph: {
          required: "PPh Harus Diisi",
        },
        edit_astek: {
          required: "Astek Harus Diisi",
        },
        edit_hitungan_pph: {
          required: "Hitungan PPh Harus Diisi",
        },
        edit_tunjangan_masa_kerja: {
          required: "Tunjangan Masa Kerja Harus Diisi",
        },
        edit_perhitungan_lembur: {
          required: "Perhitungan Lembur Harus Diisi",
        },
        edit_photo: {
          extension: "File Format Only JPG, JPEG, or PDF",
          filesize: "Max File Size is 2 MB"
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
          url:"{{ url('master_karyawan/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_karyawan_table').dataTable();
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

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>

@endsection