@extends('layouts.app_admin')

@section('title')
<title>LAPORAN HASIL LAB - PT. DWI SELO GIRI MAS</title>
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

  /*@media only screen and (min-width: 992px) {
    .modal-xl {
      max-width: 900px !important;
      margin: 1.75rem auto;
    }
  }*/

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
          <h1 class="m-0 text-dark">Laporan Hasil Lab</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Lab</li>
            <li class="breadcrumb-item">Laporan Hasil Lab</li>
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
            <div class="col-3">
              <button type="button" name="btn_upload_excel" id="btn_upload_excel" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_upload_excel">Upload Excel</button>
            </div>
            <div class="col-3">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
            </div>
            <div class="col-3">
              <button type="button" name="btn_download_excel" id="btn_download_excel" class="btn btn-block btn-primary btn_download_excel">Download Excel Periodic</button>
            </div>
            <div class="col-3">
              <button type="button" name="btn_trend_informasi" id="btn_trend_informasi" class="btn btn-block btn-primary btn_trend_informasi" data-toggle="modal" data-target="#modal_trend_informasi">Download Trend Informasi Mesin</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="data_hasil_lab_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jumlah Pengujian</th>
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
                  <label for="tanggal">Tanggal :</label>
                  <input class="form-control" type="text" name="tanggal" id="tanggal" placeholder="Tanggal" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="jam_waktu">Waktu / Jam :</label>
                  <input class="form-control" type="text" name="jam_waktu" id="jam_waktu" placeholder="Waktu / Jam" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="referensi">Referensi</label>
                  <input type="text" class="form-control" name="referensi" id="referensi" placeholder="Referensi">
                  <small id="validate-referensi"></small>
                </div>
              </div>
            </div>
            <table style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="text-align: center;">Rencana Produksi</th>
                  <th style="text-align: center;">Mesin SA, SB, Mixer</th>
                  <th style="text-align: center;">Mesin RA, RB</th>
                  <th style="text-align: center;">Mesin RC, RD, RE, RF, RG</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Whiteness (%)</th>
                  <td id="whiteness_a" style="vertical-align : middle; text-align: center;"></td>
                  <td id="whiteness_b" style="vertical-align : middle; text-align: center;"></td>
                  <td id="whiteness_c" style="vertical-align : middle; text-align: center;"></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Moisture (%)</th>
                  <td id="moisture_a" style="vertical-align : middle; text-align: center;"></td>
                  <td id="moisture_b" style="vertical-align : middle; text-align: center;"></td>
                  <td id="moisture_c" style="vertical-align : middle; text-align: center;"></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Residue Max (%)</th>
                  <td id="residue_max_a" style="vertical-align : middle; text-align: center;"></td>
                  <td id="residue_max_b" style="vertical-align : middle; text-align: center;"></td>
                  <td id="residue_max_c" style="vertical-align : middle; text-align: center;"></td>
                </tr>
              </tbody>
            </table>
            <table style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesin</th>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                  <th style="text-align: center;" colspan="2">Whiteness</th>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Standar</th>
                  <th style="vertical-align : middle; text-align: center;">Hasil</th>
                  <th style="vertical-align : middle; text-align: center;">Standar</th>
                  <th style="vertical-align : middle; text-align: center;">Hasil</th>
                  <th style="vertical-align : middle; text-align: center;">Standar</th>
                  <th style="vertical-align : middle; text-align: center;">Hasil</th>
                  <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                  <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">SA</th>
                  <td><input class="form-control" type="text" name="mesh_sa" id="mesh_sa" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_sa" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_sa" id="standar_ssa_sa" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_sa" id="hasil_ssa_sa" placeholder="SSA" /></td>
                  <td id="td_standar_d50_sa" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_sa" id="standar_d50_sa" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_sa" id="hasil_d50_sa" placeholder="D-50" /></td>
                  <td id="td_standar_d98_sa" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_sa" id="standar_d98_sa" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_sa" id="hasil_d98_sa" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_sa" id="cie86_sa" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_sa" id="iso2470_sa" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_sa" id="moisture_sa" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_sa" id="residue_sa" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">SB</th>
                  <td><input class="form-control" type="text" name="mesh_sb" id="mesh_sb" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_sb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_sb" id="standar_ssa_sb" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_sb" id="hasil_ssa_sb" placeholder="SSA" /></td>
                  <td id="td_standar_d50_sb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_sb" id="standar_d50_sb" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_sb" id="hasil_d50_sb" placeholder="D-50" /></td>
                  <td id="td_standar_d98_sb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_sb" id="standar_d98_sb" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_sb" id="hasil_d98_sb" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_sb" id="cie86_sb" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_sb" id="iso2470_sb" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_sb" id="moisture_sb" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_sb" id="residue_sb" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Mixer</th>
                  <td><input class="form-control" type="text" name="mesh_mixer" id="mesh_mixer" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_mixer" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_mixer" id="standar_ssa_mixer" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_mixer" id="hasil_ssa_mixer" placeholder="SSA" /></td>
                  <td id="td_standar_d50_mixer" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_mixer" id="standar_d50_mixer" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_mixer" id="hasil_d50_mixer" placeholder="D-50" /></td>
                  <td id="td_standar_d98_mixer" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_mixer" id="standar_d98_mixer" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_mixer" id="hasil_d98_mixer" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_mixer" id="cie86_mixer" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_mixer" id="iso2470_mixer" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_mixer" id="moisture_mixer" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_mixer" id="residue_mixer" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RA</th>
                  <td><input class="form-control" type="text" name="mesh_ra" id="mesh_ra" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_ra" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_ra" id="standar_ssa_ra" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_ra" id="hasil_ssa_ra" placeholder="SSA" /></td>
                  <td id="td_standar_d50_ra" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_ra" id="standar_d50_ra" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_ra" id="hasil_d50_ra" placeholder="D-50" /></td>
                  <td id="td_standar_d98_ra" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_ra" id="standar_d98_ra" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_ra" id="hasil_d98_ra" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_ra" id="cie86_ra" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_ra" id="iso2470_ra" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_ra" id="moisture_ra" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_ra" id="residue_ra" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RB</th>
                  <td><input class="form-control" type="text" name="mesh_rb" id="mesh_rb" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_rb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_rb" id="standar_ssa_rb" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_rb" id="hasil_ssa_rb" placeholder="SSA" /></td>
                  <td id="td_standar_d50_rb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_rb" id="standar_d50_rb" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_rb" id="hasil_d50_rb" placeholder="D-50" /></td>
                  <td id="td_standar_d98_rb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_rb" id="standar_d98_rb" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_rb" id="hasil_d98_rb" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_rb" id="cie86_rb" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_rb" id="iso2470_rb" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_rb" id="moisture_rb" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_rb" id="residue_rb" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RC</th>
                  <td><input class="form-control" type="text" name="mesh_rc" id="mesh_rc" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_rc" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_rc" id="standar_ssa_rc" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_rc" id="hasil_ssa_rc" placeholder="SSA" /></td>
                  <td id="td_standar_d50_rc" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_rc" id="standar_d50_rc" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_rc" id="hasil_d50_rc" placeholder="D-50" /></td>
                  <td id="td_standar_d98_rc" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_rc" id="standar_d98_rc" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_rc" id="hasil_d98_rc" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_rc" id="cie86_rc" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_rc" id="iso2470_rc" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_rc" id="moisture_rc" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_rc" id="residue_rc" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RD</th>
                  <td><input class="form-control" type="text" name="mesh_rd" id="mesh_rd" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_rd"style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_rd" id="standar_ssa_rd" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_rd" id="hasil_ssa_rd" placeholder="SSA" /></td>
                  <td id="td_standar_d50_rd" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_rd" id="standar_d50_rd" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_rd" id="hasil_d50_rd" placeholder="D-50" /></td>
                  <td id="td_standar_d98_rd" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_rd" id="standar_d98_rd" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_rd" id="hasil_d98_rd" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_rd" id="cie86_rd" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_rd" id="iso2470_rd" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_rd" id="moisture_rd" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_rd" id="residue_rd" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RE</th>
                  <td><input class="form-control" type="text" name="mesh_re" id="mesh_re" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_re" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_re" id="standar_ssa_re" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_re" id="hasil_ssa_re" placeholder="SSA" /></td>
                  <td id="td_standar_d50_re" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_re" id="standar_d50_re" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_re" id="hasil_d50_re" placeholder="D-50" /></td>
                  <td id="td_standar_d98_re" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_re" id="standar_d98_re" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_re" id="hasil_d98_re" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_re" id="cie86_re" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_re" id="iso2470_re" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_re" id="moisture_re" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_re" id="residue_re" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RF</th>
                  <td><input class="form-control" type="text" name="mesh_rf" id="mesh_rf" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_rf" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_rf" id="standar_ssa_rf" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_rf" id="hasil_ssa_rf" placeholder="SSA" /></td>
                  <td id="td_standar_d50_rf" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_rf" id="standar_d50_rf" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_rf" id="hasil_d50_rf" placeholder="D-50" /></td>
                  <td id="td_standar_d98_rf" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_rf" id="standar_d98_rf" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_rf" id="hasil_d98_rf" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_rf" id="cie86_rf" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_rf" id="iso2470_rf" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_rf" id="moisture_rf" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_rf" id="residue_rf" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RG</th>
                  <td><input class="form-control" type="text" name="mesh_rg" id="mesh_rg" placeholder="Mesh" /></td>
                  <td id="td_standar_ssa_rg" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_ssa_rg" id="standar_ssa_rg" placeholder="Std SSA" /><input class="form-control" type="text" name="hasil_ssa_rg" id="hasil_ssa_rg" placeholder="SSA" /></td>
                  <td id="td_standar_d50_rg" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d50_rg" id="standar_d50_rg" placeholder="Std D-50" /><input class="form-control" type="text" name="hasil_d50_rg" id="hasil_d50_rg" placeholder="D-50" /></td>
                  <td id="td_standar_d98_rg" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="standar_d98_rg" id="standar_d98_rg" placeholder="Std D-98" /><input class="form-control" type="text" name="hasil_d98_rg" id="hasil_d98_rg" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="cie86_rg" id="cie86_rg" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="iso2470_rg" id="iso2470_rg" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="moisture_rg" id="moisture_rg" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="residue_rg" id="residue_rg" placeholder="Residue" /></td>
                </tr>
              </tbody>
            </table>
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

  <div class="modal fade" id="modal_upload_excel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Excel</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" class="upload-form" id="upload-form" action="{{ url('/laporan_hasil_lab/upload') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_trend_informasi">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Download Trend Informasi Mesin</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" target="_blank" class="trend-informasi-form" id="trend-informasi-form" action="{{ url('/laporan_hasil_lab/trend_informasi/excel') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="periode">Pilih Periode</label>
                  <select id="periode" name="periode" class="form-control" style="width: 100%;">
                    <option value="6">6 Bulan</option>
                    <option value="12" selected>1 Tahun</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="mesh">Pilih Mesh</label>
                  <select id="mesh" name="mesh" class="form-control" style="width: 100%;">
                    <option value="ALL" selected>Semua</option>
                    <option value="250">250</option>
                    <option value="325">325</option>
                    <option value="500">500</option>
                    <option value="800">800</option>
                    <option value="1200">1200</option>
                    <option value="1500">1500</option>
                    <option value="2000">2000</option>
                    <option value="6000">6000</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="rpm">Masukkan RPM</label>
                  <input class="form-control" type="text" name="rpm" id="rpm" placeholder="RPM" />
                  <small>* Kosongkan apabila ingin menampilkan semua RPM</small>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Download Excel</button>
          </div>
        </form>
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
            <div class="col-lg-12">
              <table style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
                <tr>
                  <th>Tanggal : </th>
                  <th id="td_tanggal"></th>
                  <th>Referensi : </th>
                  <th id="td_referensi"></th>
                </tr>
              </table>

              <table style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2" colspan="2">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">RPM</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                    <th style="text-align: center;" colspan="2">Whiteness</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">Residue</th>
                  </tr>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">Standar</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Standar</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Standar</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                    <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                    <th style="vertical-align : middle; text-align: center;">Standart</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
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
          <a href="#" class="btn btn-primary" id="btn-save-excel">Download Excel</a>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_view_data_prd">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title_lihat_laporan_produksi"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12 lihat-table">
            <table class="table table-bordered table-hover" style="width: 100%; font-size: 6px;">
              <thead>
                <tr>
                  <th>Tanggal Laporan Produksi</th>
                  <td id="td_tanggal_laporan"></td>
                  <th>Referensi</th>
                  <td id="td_referensi_laporan"></td>
                </tr>
              </thead>
            </table>
            <table class="table table-bordered table-hover" id="table_laporan" style="font-size: 6px;">
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
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_tanggal">Tanggal :</label>
                  <input class="form-control" type="text" name="edit_tanggal" id="edit_tanggal" placeholder="Tanggal" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_jam_waktu">Waktu / Jam :</label>
                  <input class="form-control" type="text" name="edit_jam_waktu" id="edit_jam_waktu" placeholder="Waktu / Jam" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_referensi">Referensi</label>
                  <input type="text" class="form-control" name="edit_referensi" id="edit_referensi" placeholder="Referensi">
                  <small id="edit-validate-referensi"></small>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_nomor">Pilih Batch :</label>
                  <select id="edit_nomor" name="edit_nomor" class="form-control">
                  </select>
                </div>
              </div>
            </div>
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="text-align: center;">Rencana Produksi</th>
                  <th style="text-align: center;">Mesin SA, SB, Mixer</th>
                  <th style="text-align: center;">Mesin RA, RB</th>
                  <th style="text-align: center;">Mesin RC, RD, RE, RF, RG</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Whiteness (%)</th>
                  <td id="edit_whiteness_a" style="vertical-align : middle; text-align: center;"></td>
                  <td id="edit_whiteness_b" style="vertical-align : middle; text-align: center;"></td>
                  <td id="edit_whiteness_c" style="vertical-align : middle; text-align: center;"></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Moisture (%)</th>
                  <td id="edit_moisture_a" style="vertical-align : middle; text-align: center;"></td>
                  <td id="edit_moisture_b" style="vertical-align : middle; text-align: center;"></td>
                  <td id="edit_moisture_c" style="vertical-align : middle; text-align: center;"></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Residue Max (%)</th>
                  <td id="edit_residue_max_a" style="vertical-align : middle; text-align: center;"></td>
                  <td id="edit_residue_max_b" style="vertical-align : middle; text-align: center;"></td>
                  <td id="edit_residue_max_c" style="vertical-align : middle; text-align: center;"></td>
                </tr>
              </tbody>
            </table>
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesin</th>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                  <th style="text-align: center;" colspan="2">Whiteness</th>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Standar</th>
                  <th style="vertical-align : middle; text-align: center;">Hasil</th>
                  <th style="vertical-align : middle; text-align: center;">Standar</th>
                  <th style="vertical-align : middle; text-align: center;">Hasil</th>
                  <th style="vertical-align : middle; text-align: center;">Standar</th>
                  <th style="vertical-align : middle; text-align: center;">Hasil</th>
                  <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                  <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">SA</th>
                  <td><input class="form-control" type="text" name="edit_mesh_sa" id="edit_mesh_sa" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_sa" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_sa" id="edit_standar_ssa_sa" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_sa" id="edit_hasil_ssa_sa" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_sa" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_sa" id="edit_standar_d50_sa" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_sa" id="edit_hasil_d50_sa" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_sa" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_sa" id="edit_standar_d98_sa" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_sa" id="edit_hasil_d98_sa" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_sa" id="edit_cie86_sa" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_sa" id="edit_iso2470_sa" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_sa" id="edit_moisture_sa" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_sa" id="edit_residue_sa" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">SB</th>
                  <td><input class="form-control" type="text" name="edit_mesh_sb" id="edit_mesh_sb" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_sb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_sb" id="edit_standar_ssa_sb" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_sb" id="edit_hasil_ssa_sb" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_sb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_sb" id="edit_standar_d50_sb" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_sb" id="edit_hasil_d50_sb" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_sb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_sb" id="edit_standar_d98_sb" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_sb" id="edit_hasil_d98_sb" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_sb" id="edit_cie86_sb" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_sb" id="edit_iso2470_sb" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_sb" id="edit_moisture_sb" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_sb" id="edit_residue_sb" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">Mixer</th>
                  <td><input class="form-control" type="text" name="edit_mesh_mixer" id="edit_mesh_mixer" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_mixer" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_mixer" id="edit_standar_ssa_mixer" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_mixer" id="edit_hasil_ssa_mixer" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_mixer" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_mixer" id="edit_standar_d50_mixer" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_mixer" id="edit_hasil_d50_mixer" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_mixer" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_mixer" id="edit_standar_d98_mixer" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_mixer" id="edit_hasil_d98_mixer" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_mixer" id="edit_cie86_mixer" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_mixer" id="edit_iso2470_mixer" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_mixer" id="edit_moisture_mixer" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_mixer" id="edit_residue_mixer" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RA</th>
                  <td><input class="form-control" type="text" name="edit_mesh_ra" id="edit_mesh_ra" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_ra" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_ra" id="edit_standar_ssa_ra" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_ra" id="edit_hasil_ssa_ra" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_ra" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_ra" id="edit_standar_d50_ra" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_ra" id="edit_hasil_d50_ra" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_ra" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_ra" id="edit_standar_d98_ra" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_ra" id="edit_hasil_d98_ra" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_ra" id="edit_cie86_ra" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_ra" id="edit_iso2470_ra" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_ra" id="edit_moisture_ra" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_ra" id="edit_residue_ra" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RB</th>
                  <td><input class="form-control" type="text" name="edit_mesh_rb" id="edit_mesh_rb" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_rb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_rb" id="edit_standar_ssa_rb" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_rb" id="edit_hasil_ssa_rb" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_rb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_rb" id="edit_standar_d50_rb" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_rb" id="edit_hasil_d50_rb" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_rb" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_rb" id="edit_standar_d98_rb" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_rb" id="edit_hasil_d98_rb" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_rb" id="edit_cie86_rb" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_rb" id="edit_iso2470_rb" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_rb" id="edit_moisture_rb" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_rb" id="edit_residue_rb" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RC</th>
                  <td><input class="form-control" type="text" name="edit_mesh_rc" id="edit_mesh_rc" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_rc" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_rc" id="edit_standar_ssa_rc" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_rc" id="edit_hasil_ssa_rc" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_rc" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_rc" id="edit_standar_d50_rc" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_rc" id="edit_hasil_d50_rc" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_rc" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_rc" id="edit_standar_d98_rc" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_rc" id="edit_hasil_d98_rc" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_rc" id="edit_cie86_rc" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_rc" id="edit_iso2470_rc" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_rc" id="edit_moisture_rc" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_rc" id="edit_residue_rc" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RD</th>
                  <td><input class="form-control" type="text" name="edit_mesh_rd" id="edit_mesh_rd" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_rd" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_rd" id="edit_standar_ssa_rd" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_rd" id="edit_hasil_ssa_rd" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_rd" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_rd" id="edit_standar_d50_rd" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_rd" id="edit_hasil_d50_rd" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_rd" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_rd" id="edit_standar_d98_rd" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_rd" id="edit_hasil_d98_rd" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_rd" id="edit_cie86_rd" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_rd" id="edit_iso2470_rd" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_rd" id="edit_moisture_rd" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_rd" id="edit_residue_rd" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RE</th>
                  <td><input class="form-control" type="text" name="edit_mesh_re" id="edit_mesh_re" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_re" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_re" id="edit_standar_ssa_re" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_re" id="edit_hasil_ssa_re" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_re" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_re" id="edit_standar_d50_re" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_re" id="edit_hasil_d50_re" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_re" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_re" id="edit_standar_d98_re" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_re" id="edit_hasil_d98_re" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_re" id="edit_cie86_re" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_re" id="edit_iso2470_re" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_re" id="edit_moisture_re" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_re" id="edit_residue_re" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RF</th>
                  <td><input class="form-control" type="text" name="edit_mesh_rf" id="edit_mesh_rf" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_rf" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_rf" id="edit_standar_ssa_rf" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_rf" id="edit_hasil_ssa_rf" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_rf" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_rf" id="edit_standar_d50_rf" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_rf" id="edit_hasil_d50_rf" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_rf" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_rf" id="edit_standar_d98_rf" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_rf" id="edit_hasil_d98_rf" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_rf" id="edit_cie86_rf" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_rf" id="edit_iso2470_rf" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_rf" id="edit_moisture_rf" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_rf" id="edit_residue_rf" placeholder="Residue" /></td>
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">RG</th>
                  <td><input class="form-control" type="text" name="edit_mesh_rg" id="edit_mesh_rg" placeholder="Mesh" /></td>
                  <td id="td_edit_standar_ssa_rg" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_ssa_rg" id="edit_standar_ssa_rg" placeholder="Std SSA" /><input class="form-control" type="text" name="edit_hasil_ssa_rg" id="edit_hasil_ssa_rg" placeholder="SSA" /></td>
                  <td id="td_edit_standar_d50_rg" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d50_rg" id="edit_standar_d50_rg" placeholder="Std D-50" /><input class="form-control" type="text" name="edit_hasil_d50_rg" id="edit_hasil_d50_rg" placeholder="D-50" /></td>
                  <td id="td_edit_standar_d98_rg" style="vertical-align : middle; text-align: center; font-size: 18px;">-</td>
                  <td><input class="form-control" type="hidden" name="edit_standar_d98_rg" id="edit_standar_d98_rg" placeholder="Std D-98" /><input class="form-control" type="text" name="edit_hasil_d98_rg" id="edit_hasil_d98_rg" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_rg" id="edit_cie86_rg" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_rg" id="edit_iso2470_rg" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_rg" id="edit_moisture_rg" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_rg" id="edit_residue_rg" placeholder="Residue" /></td>
                </tr>
              </tbody>
            </table>
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

    $('#jam_waktu').flatpickr({
      allowInput: true,
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      disableMobile: true
    });

    $('#edit_jam_waktu').flatpickr({
      allowInput: true,
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      disableMobile: true
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#data_hasil_lab_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("laporan_hasil_lab/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[1,'desc']],
        columns: [
         {
           data:'DT_RowIndex',
           name:'DT_RowIndex',
           width: '5%',
           className:'dt-center'
         },
         {
           data:'tanggal_laporan_produksi',
           name:'tanggal_laporan_produksi',
           className:'dt-center'
         },
         {
           data:'jumlah_pengujian',
           name:'jumlah_pengujian',
           className:'dt-center'
         },
         {
           data:'action',
           name:'action',
           width: '20%',
           className:'dt-center'
         }
       ]
     });

    function load_data_hasil_lab(from_date = '', to_date = '')
    {
      table = $('#data_hasil_lab_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("laporan_hasil_lab/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[1,'desc']],
        columns: [
         {
           data:'DT_RowIndex',
           name:'DT_RowIndex',
           width: '5%',
           className:'dt-center'
         },
         {
           data:'tanggal_laporan_produksi',
           name:'tanggal_laporan_produksi',
           className:'dt-center'
         },
         {
           data:'jumlah_pengujian',
           name:'jumlah_pengujian',
           className:'dt-center'
         },
         {
           data:'action',
           name:'action',
           width: '20%',
           className:'dt-center'
         }
       ]
     });
    }

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

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#data_hasil_lab_table').DataTable().destroy();
        load_data_hasil_lab(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#data_hasil_lab_table').DataTable().destroy();
      load_data_hasil_lab();
    });

    $('body').on('click', '#btn_download_excel', function () {
      console.log('Tes');
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');

      var url_excel = "{{ url('laporan_hasil_lab/excel/periodic/from_date/to_date') }}";
      url_excel = url_excel.replace('from_date', enc(from_date.toString()));
      url_excel = url_excel.replace('to_date', enc(to_date.toString()));
      // $("#btn_download_data").attr("href", url_excel);
      window.open(url_excel, '_blank')
    });

    $('body').on('click', '#btn_input_data', function () {
      var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      var url = "{{ url('get_spesifikasi_mesin') }}";
      $.get(url, function (data) {
        $('#whiteness_a').html(data.data_a.whiteness);
        $('#whiteness_b').html(data.data_b.whiteness);
        $('#whiteness_c').html(data.data_c.whiteness);
        $('#moisture_a').html(data.data_a.moisture);
        $('#moisture_b').html(data.data_b.moisture);
        $('#moisture_c').html(data.data_c.moisture);
        $('#residue_max_a').html(data.data_a.residue_max);
        $('#residue_max_b').html(data.data_b.residue_max);
        $('#residue_max_c').html(data.data_c.residue_max);
      })

      $.each(arr_mesin, function(k, v) {
        $('#mesh_'+v).on('keyup', function(){
          if($("#mesh_"+v).val() == 250){
            $("#td_standar_ssa_"+v).html('2.500');
            $("#standar_ssa_"+v).val('2500');
            $("#td_standar_d50_"+v).html('9,00');
            $("#standar_d50_"+v).val('9.00');
            $("#td_standar_d98_"+v).html('125,00');
            $("#standar_d98_"+v).val('125.00');
          }else if($("#mesh_"+v).val() == 325){
            $("#td_standar_ssa_"+v).html('5.500');
            $("#standar_ssa_"+v).val('5500');
            $("#td_standar_d50_"+v).html('8,50');
            $("#standar_d50_"+v).val('8.50');
            $("#td_standar_d98_"+v).html('75,00');
            $("#standar_d98_"+v).val('75.00');
          }else if($("#mesh_"+v).val() == 500){
            $("#td_standar_ssa_"+v).html('7.000');
            $("#standar_ssa_"+v).val('7000');
            $("#td_standar_d50_"+v).html('7,00');
            $("#standar_d50_"+v).val('7.00');
            $("#td_standar_d98_"+v).html('60,00');
            $("#standar_d98_"+v).val('60.00');
          }else if($("#mesh_"+v).val() == 800){
            $("#td_standar_ssa_"+v).html('8.000');
            $("#standar_ssa_"+v).val('8000');
            $("#td_standar_d50_"+v).html('2,50');
            $("#standar_d50_"+v).val('2.50');
            $("#td_standar_d98_"+v).html('40,00');
            $("#standar_d98_"+v).val('40.00');
          }else if($("#mesh_"+v).val() == 1200){
            $("#td_standar_ssa_"+v).html('11.500');
            $("#standar_ssa_"+v).val('11500');
            $("#td_standar_d50_"+v).html('1,70');
            $("#standar_d50_"+v).val('1.70');
            $("#td_standar_d98_"+v).html('30,00');
            $("#standar_d98_"+v).val('30.00');
          }else if($("#mesh_"+v).val() == 1500){
            $("#td_standar_ssa_"+v).html('12.000');
            $("#standar_ssa_"+v).val('12000');
            $("#td_standar_d50_"+v).html('1,60');
            $("#standar_d50_"+v).val('1.60');
            $("#td_standar_d98_"+v).html('25,00');
            $("#standar_d98_"+v).val('25.00');
          }else if($("#mesh_"+v).val() == 2000){
            $("#td_standar_ssa_"+v).html('13.500');
            $("#standar_ssa_"+v).val('13500');
            $("#td_standar_d50_"+v).html('1,40');
            $("#standar_d50_"+v).val('1.40');
            $("#td_standar_d98_"+v).html('23,00');
            $("#standar_d98_"+v).val('23.00');
          }else if($("#mesh_"+v).val() == 6000){
            $("#td_standar_ssa_"+v).html('14.000');
            $("#standar_ssa_"+v).val('14000');
            $("#td_standar_d50_"+v).html('1,20');
            $("#standar_d50_"+v).val('1.20');
            $("#td_standar_d98_"+v).html('21,00');
            $("#standar_d98_"+v).val('21.00');
          }else{
            $("#td_standar_ssa_"+v).html('-');
            $("#standar_ssa_"+v).val('');
            $("#td_standar_d50_"+v).html('-');
            $("#standar_d50_"+v).val('');
            $("#td_standar_d98_"+v).html('-');
            $("#standar_d98_"+v).val('');
          }
        });
      });

      $("#referensi").on("input", function(e) {
        e.stopImmediatePropagation();
        var value = $(this).val();

        var urldata = "{{ url('check/referensi/nomor_rencana_produksi') }}";
        urldata = urldata.replace('nomor_rencana_produksi', enc(value.toString()));
        $.get(urldata, function (dataprd) {
          if(dataprd){
            $("#validate-referensi").css("color","green");
            $('#validate-referensi').html('Data Rencana Produksi Ditemukan');
          }else{
            $("#validate-referensi").css("color","red");
            $('#validate-referensi').html('Data Rencana Produksi Tidak Ditemukan');
          }
        })
      });
    });

    $('body').on('click', '#view-data', function () {
      var tanggal = $(this).data("id");
      var url = "{{ url('laporan_hasil_lab/view/tanggal') }}";
      url = url.replace('tanggal', enc(tanggal.toString()));
      $('#td_tanggal').html(tanggal);

      var urldata = "{{ url('laporan_produksi/data/view/tanggal') }}";
      urldata = urldata.replace('tanggal', enc(tanggal.toString()));
      $.get(urldata, function (dataprd) {
        $('#td_referensi').html(dataprd.referensi);
      })

      var url_excel = "{{ url('laporan_hasil_lab/excel/tanggal') }}";
      url_excel = url_excel.replace('tanggal', enc(tanggal.toString()));
      $("#btn-save-excel").attr("href", url_excel);
      $("#tbody_view").empty();
      $.get(url, function (data) {
        var besar = 0;
        var jam_kecil = null;
        var jam_besar = null;
        $.each(data, function(k, v) {
          if(besar < v.length){
            besar = v.length;
            if(v.length == 2){
              jam_kecil = v[0].jam_waktu;
              jam_besar = v[1].jam_waktu;
            }else if(v.length == 1){
              jam_kecil = v[0].jam_waktu;
            }
          }
        });
        $.each(data, function(k, v) {
          if(besar > 1){
            if(v.length == 0){
              if(k == 'RB'){
                $('#tbody_view').append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+k+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(jam_kecil, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '</tr>'
                  );

                $('#tbody_view').append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(jam_besar, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '</tr>'
                  );

                $('#tbody_view').append(
                  '<tr colspan="15" style="height:20px;"></tr>'
                  );
              }else{
                $('#tbody_view').append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+k+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(jam_kecil, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '</tr>'
                  );

                $('#tbody_view').append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(jam_besar, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '</tr>'
                  );
              }
            }
          }else{
            if(v.length == 0){
              if(k == 'RB'){
                $('#tbody_view').append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(jam_kecil, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '</tr>'
                  );

                $('#tbody_view').append(
                  '<tr colspan="15" style="height:20px;"></tr>'
                  );
              }else{
                $('#tbody_view').append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(jam_kecil, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '<td style="vertical-align : middle; text-align: center;">-</td>'+
                  '</tr>'
                  );
              }
            }
          }
          $.each(v, function(m, y) {
            if(m == 0){
              if(v.length < besar){
                if(y.jam_waktu == jam_kecil){
                  if(k == 'RB'){
                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+k+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                      (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+
                      (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                      (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                      '</tr>'
                    );
                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(jam_besar, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '</tr>'
                    );
                    $('#tbody_view').append(
                      '<tr colspan="15" style="height:20px;"></tr>'
                    );
                  }else{
                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+k+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                      (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+
                      (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                      (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                      '</tr>'
                    );
                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(jam_besar, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '</tr>'
                    );
                  }
                }else if(y.jam_waktu == jam_besar){
                  if(k == 'RB'){
                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+k+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(jam_kecil, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '</tr>'
                    );

                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                      (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+
                      (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                      (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                      '</tr>'
                    );
                    $('#tbody_view').append(
                      '<tr colspan="15" style="height:20px;"></tr>'
                    );
                  }else{
                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+k+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(jam_kecil, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '<td style="vertical-align : middle; text-align: center;">-</td>'+
                      '</tr>'
                    );

                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                      (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+
                      (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                      (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                      '</tr>'
                    );
                  }
                }
              }else{
                if(k == 'RB'){
                  if(v.length == 1){
                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;" rowspan="'+v.length+'">'+k+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                      (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+
                      (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                      (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                      '</tr>'
                    );
                    $('#tbody_view').append(
                      '<tr colspan="15" style="height:20px;"></tr>'
                    );
                  }else{
                    $('#tbody_view').append(
                      '<tr>'+
                      '<td style="vertical-align : middle; text-align: center;" rowspan="'+v.length+'">'+k+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                      (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                      (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                      (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                      '<td style="vertical-align : middle; text-align: center;">'+
                      (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                      (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                      (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                      '</tr>'
                    );
                  }
                }else{
                  $('#tbody_view').append(
                    '<tr>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="'+v.length+'">'+k+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                    (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                    (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                    (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                    (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                    (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                    (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+
                    (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                    (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                    '</tr>'
                  );
                }
              } 
            }else{
              if(k == 'RB'){
                $('#tbody_view').append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                  (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                  (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                  (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                  (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                  (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                  (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+
                  (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                  (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                  '</tr>'
                );

                $('#tbody_view').append(
                  '<tr colspan="15" style="height:20px;"></tr>'
                );
              }else{
                $('#tbody_view').append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(y.std_ssa == 0  || y.std_ssa == null ? '-': y.std_ssa)+'</td>'+
                  (y.std_ssa > y.ssa ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d50) == 0  || parseFloat(y.std_d50) == null ? '0.00': parseFloat(y.std_d50).toFixed(2))+'</td>'+
                  (parseFloat(y.std_d50) < parseFloat(y.d50) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.std_d98) == 0  || parseFloat(y.std_d98) == null ? '0.00': parseFloat(y.std_d98).toFixed(2))+'</td>'+
                  (parseFloat(y.std_d98) < parseFloat(y.d98) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                  (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                  (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                  (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+
                  (parseFloat(y.standart_residue) == 0  || parseFloat(y.standart_residue) == null ? '0.00': parseFloat(y.standart_residue))+'</td>'+
                  (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                  '</tr>'
                );
              }
            }
          });
        });
      })
    });

    $('body').on('click', '#edit-data', function () {
      var tanggal = $(this).data("id");
      var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      $('#edit_jam_waktu').val('');
      $('#edit_referensi').val('');
      $.each(arr_mesin, function(k, v) {
        $('#edit_mesh_' + v).val('');
        $('#edit_standar_ssa_' + v).val('');
        $('#edit_hasil_ssa_' + v).val('');
        $('#edit_standar_d50_' + v).val('');
        $('#edit_hasil_d50_' + v).val('');
        $('#edit_standar_d98_' + v).val('');
        $('#edit_hasil_d98_' + v).val('');
        $('#edit_cie86_' + v).val('');
        $('#edit_iso2470_' + v).val('');
        $('#edit_moisture_' + v).val('');
        $('#edit_residue_' + v).val('');

        $('#edit_mesh_'+v).on('keyup', function(){
          if($("#edit_mesh_"+v).val() == 250){
            $("#td_edit_standar_ssa_"+v).html('2.500');
            $("#edit_standar_ssa_"+v).val('2500');
            $("#td_edit_standar_d50_"+v).html('9,00');
            $("#edit_standar_d50_"+v).val('9.00');
            $("#td_edit_standar_d98_"+v).html('125,00');
            $("#edit_standar_d98_"+v).val('125.00');
          }else if($("#edit_mesh_"+v).val() == 325){
            $("#td_edit_standar_ssa_"+v).html('5.500');
            $("#edit_standar_ssa_"+v).val('5500');
            $("#td_edit_standar_d50_"+v).html('8,50');
            $("#edit_standar_d50_"+v).val('8.50');
            $("#td_edit_standar_d98_"+v).html('75,00');
            $("#edit_standar_d98_"+v).val('75.00');
          }else if($("#edit_mesh_"+v).val() == 500){
            $("#td_edit_standar_ssa_"+v).html('7.000');
            $("#edit_standar_ssa_"+v).val('7000');
            $("#td_edit_standar_d50_"+v).html('7,00');
            $("#edit_standar_d50_"+v).val('7.00');
            $("#td_edit_standar_d98_"+v).html('60,00');
            $("#edit_standar_d98_"+v).val('60.00');
          }else if($("#edit_mesh_"+v).val() == 800){
            $("#td_edit_standar_ssa_"+v).html('8.000');
            $("#edit_standar_ssa_"+v).val('8000');
            $("#td_edit_standar_d50_"+v).html('2,50');
            $("#edit_standar_d50_"+v).val('2.50');
            $("#td_edit_standar_d98_"+v).html('40,00');
            $("#edit_standar_d98_"+v).val('40.00');
          }else if($("#edit_mesh_"+v).val() == 1200){
            $("#td_edit_standar_ssa_"+v).html('11.500');
            $("#edit_standar_ssa_"+v).val('11500');
            $("#td_edit_standar_d50_"+v).html('1,70');
            $("#edit_standar_d50_"+v).val('1.70');
            $("#td_edit_standar_d98_"+v).html('30,00');
            $("#edit_standar_d98_"+v).val('30.00');
          }else if($("#edit_mesh_"+v).val() == 1500){
            $("#td_edit_standar_ssa_"+v).html('12.000');
            $("#edit_standar_ssa_"+v).val('12000');
            $("#td_edit_standar_d50_"+v).html('1,60');
            $("#edit_standar_d50_"+v).val('1.60');
            $("#td_edit_standar_d98_"+v).html('25,00');
            $("#edit_standar_d98_"+v).val('25.00');
          }else if($("#edit_mesh_"+v).val() == 2000){
            $("#td_edit_standar_ssa_"+v).html('13.500');
            $("#edit_standar_ssa_"+v).val('13500');
            $("#td_edit_standar_d50_"+v).html('1,40');
            $("#edit_standar_d50_"+v).val('1.40');
            $("#td_edit_standar_d98_"+v).html('23,00');
            $("#edit_standar_d98_"+v).val('23.00');
          }else if($("#edit_mesh_"+v).val() == 6000){
            $("#td_edit_standar_ssa_"+v).html('14.000');
            $("#edit_standar_ssa_"+v).val('14000');
            $("#td_edit_standar_d50_"+v).html('1,20');
            $("#edit_standar_d50_"+v).val('1.20');
            $("#td_edit_standar_d98_"+v).html('21,00');
            $("#edit_standar_d98_"+v).val('21.00');
          }else{
            $("#td_edit_standar_ssa_"+v).html('-');
            $("#edit_standar_ssa_"+v).val('');
            $("#td_edit_standar_d50_"+v).html('-');
            $("#edit_standar_d50_"+v).val('');
            $("#td_edit_standar_d98_"+v).html('-');
            $("#edit_standar_d98_"+v).val('');
          }
        });
      });

      var url_spek = "{{ url('get_spesifikasi_mesin') }}";
      $.get(url_spek, function (data) {
        $('#edit_whiteness_a').html(data.data_a.whiteness);
        $('#edit_whiteness_b').html(data.data_b.whiteness);
        $('#edit_whiteness_c').html(data.data_c.whiteness);
        $('#edit_moisture_a').html(data.data_a.moisture);
        $('#edit_moisture_b').html(data.data_b.moisture);
        $('#edit_moisture_c').html(data.data_c.moisture);
        $('#edit_residue_max_a').html(data.data_a.residue_max);
        $('#edit_residue_max_b').html(data.data_b.residue_max);
        $('#edit_residue_max_c').html(data.data_c.residue_max);
      })

      $("#edit_referensi").on("input", function(e) {
        e.stopImmediatePropagation();
        var value = $(this).val();

        var urldata = "{{ url('check/referensi/nomor_rencana_produksi') }}";
        urldata = urldata.replace('nomor_rencana_produksi', enc(value.toString()));
        $.get(urldata, function (dataprd) {
          if(dataprd){
            $("#edit-validate-referensi").css("color","green");
            $('#edit-validate-referensi').html('Data Rencana Produksi Ditemukan');
          }else{
            $("#edit-validate-referensi").css("color","red");
            $('#edit-validate-referensi').html('Data Rencana Produksi Tidak Ditemukan');
          }
        })
      });

      var url_batch = "{{ url('laporan_hasil_lab/view/batch/tanggal') }}";
      url_batch = url_batch.replace('tanggal', enc(tanggal.toString()));
      $.get(url_batch, function (data) {
        $('#edit_nomor').children().remove().end();
        $('#edit_tanggal').val(data[0].tanggal_laporan_produksi);
        $('#edit_referensi').val(data[0].referensi).trigger('input');
        $('#edit_nomor').append('<option value="' + data[0].nomor_laporan_produksi + '">Batch 1</option>');
        $.each(data.slice(1), function(k, v) {
          $('#edit_nomor').append('<option value="' + v.nomor_laporan_produksi + '"> Batch ' + (k+2) + '</option>');
        });

        var url_data = "{{ url('laporan_hasil_lab/view/batch/detail/nomor') }}";
        url_data = url_data.replace('nomor', enc(data[0].nomor_laporan_produksi.toString()));
        $.get(url_data, function (data_lab) {
          console.log(data_lab);
          $('#edit_jam_waktu').val(data_lab[0].jam_waktu);
          $.each(data_lab, function(k, v) {
            $('#edit_mesh_' + arr_mesin[v.mesin-1]).val(v.mesh);
            if(v.std_ssa == null || v.std_ssa == 0){
              $("#td_edit_standar_ssa_" + arr_mesin[v.mesin-1]).html('-');
              $('#edit_standar_ssa_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $("#td_edit_standar_ssa_" + arr_mesin[v.mesin-1]).html(v.std_ssa);
              $('#edit_standar_ssa_' + arr_mesin[v.mesin-1]).val(v.std_ssa);
            }
            if(v.ssa == null || v.ssa == 0){
              $('#edit_hasil_ssa_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_hasil_ssa_' + arr_mesin[v.mesin-1]).val(v.ssa);
            }
            if(parseFloat(v.std_d50) == null || parseFloat(v.std_d50) == 0){
              $("#td_edit_standar_d50_" + arr_mesin[v.mesin-1]).html('-');
              $('#edit_standar_d50_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $("#td_edit_standar_d50_" + arr_mesin[v.mesin-1]).html(parseFloat(v.std_d50).toFixed(2));
              $('#edit_standar_d50_' + arr_mesin[v.mesin-1]).val(parseFloat(v.std_d50));
            }
            if(parseFloat(v.d50) == null || parseFloat(v.d50) == 0){
              $('#edit_hasil_d50_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_hasil_d50_' + arr_mesin[v.mesin-1]).val(parseFloat(v.d50));
            }
            if(parseFloat(v.std_d98) == null || parseFloat(v.std_d98) == 0){
              $("#td_edit_standar_d98_" + arr_mesin[v.mesin-1]).html('-');
              $('#edit_standar_d98_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $("#td_edit_standar_d98_" + arr_mesin[v.mesin-1]).html(parseFloat(v.std_d98).toFixed(2));
              $('#edit_standar_d98_' + arr_mesin[v.mesin-1]).val(parseFloat(v.std_d98));
            }
            if(parseFloat(v.d98) == null || parseFloat(v.d98) == 0){
              $('#edit_hasil_d98_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_hasil_d98_' + arr_mesin[v.mesin-1]).val(parseFloat(v.d98));
            }
            if(parseFloat(v.cie86) == null || parseFloat(v.cie86) == 0){
              $('#edit_cie86_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_cie86_' + arr_mesin[v.mesin-1]).val(parseFloat(v.cie86));
            }
            if(parseFloat(v.iso2470) == null || parseFloat(v.iso2470) == 0){
              $('#edit_iso2470_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_iso2470_' + arr_mesin[v.mesin-1]).val(parseFloat(v.iso2470));
            }
            if(parseFloat(v.moisture) == null || parseFloat(v.moisture) == 0){
              $('#edit_moisture_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_moisture_' + arr_mesin[v.mesin-1]).val(parseFloat(v.moisture));
            }
            if(parseFloat(v.residue) == null || parseFloat(v.residue) == 0){
              $('#edit_residue_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_residue_' + arr_mesin[v.mesin-1]).val(parseFloat(v.residue));
            }
          });
        })
      })

      $("#edit_nomor").change(function() {
        var select_nomor = $(this).val();

        $('#edit_jam_waktu').val('');
        $('#edit_referensi').val('');
        $.each(arr_mesin, function(k, v) {
          $('#edit_mesh_' + v).val('');
          $('#edit_standar_ssa_' + v).val('');
          $('#edit_hasil_ssa_' + v).val('');
          $('#edit_standar_d50_' + v).val('');
          $('#edit_hasil_d50_' + v).val('');
          $('#edit_standar_d98_' + v).val('');
          $('#edit_hasil_d98_' + v).val('');
          $('#edit_cie86_' + v).val('');
          $('#edit_iso2470_' + v).val('');
          $('#edit_moisture_' + v).val('');
          $('#edit_residue_' + v).val('');
        });

        var url_batch = "{{ url('laporan_hasil_lab/referensi/view/batch/nomor') }}";
        url_batch = url_batch.replace('nomor', enc(select_nomor.toString()));
        $.get(url_batch, function (data) {
          $('#edit_referensi').val(data[0].referensi).trigger('input');
        })

        var url_data = "{{ url('laporan_hasil_lab/view/batch/detail/nomor') }}";
        url_data = url_data.replace('nomor', enc(select_nomor.toString()));
        $.get(url_data, function (data_lab) {
          $('#edit_jam_waktu').val(data_lab[0].jam_waktu);
          $.each(data_lab, function(k, v) {
            $('#edit_mesh_' + arr_mesin[v.mesin-1]).val(v.mesh);
            if(v.std_ssa == null || v.std_ssa == 0){
              $('#edit_standar_ssa_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_standar_ssa_' + arr_mesin[v.mesin-1]).val(v.std_ssa);
            }
            if(v.ssa == null || v.ssa == 0){
              $('#edit_hasil_ssa_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_hasil_ssa_' + arr_mesin[v.mesin-1]).val(v.ssa);
            }
            if(parseFloat(v.std_d50) == null || parseFloat(v.std_d50) == 0){
              $('#edit_standar_d50_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_standar_d50_' + arr_mesin[v.mesin-1]).val(parseFloat(v.std_d50));
            }
            if(parseFloat(v.d50) == null || parseFloat(v.d50) == 0){
              $('#edit_hasil_d50_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_hasil_d50_' + arr_mesin[v.mesin-1]).val(parseFloat(v.d50));
            }
            if(parseFloat(v.std_d98) == null || parseFloat(v.std_d98) == 0){
              $('#edit_standar_d98_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_standar_d98_' + arr_mesin[v.mesin-1]).val(parseFloat(v.std_d98));
            }
            if(parseFloat(v.d98) == null || parseFloat(v.d98) == 0){
              $('#edit_hasil_d98_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_hasil_d98_' + arr_mesin[v.mesin-1]).val(parseFloat(v.d98));
            }
            if(parseFloat(v.cie86) == null || parseFloat(v.cie86) == 0){
              $('#edit_cie86_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_cie86_' + arr_mesin[v.mesin-1]).val(parseFloat(v.cie86));
            }
            if(parseFloat(v.iso2470) == null || parseFloat(v.iso2470) == 0){
              $('#edit_iso2470_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_iso2470_' + arr_mesin[v.mesin-1]).val(parseFloat(v.iso2470));
            }
            if(parseFloat(v.moisture) == null || parseFloat(v.moisture) == 0){
              $('#edit_moisture_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_moisture_' + arr_mesin[v.mesin-1]).val(parseFloat(v.moisture));
            }
            if(parseFloat(v.residue) == null || parseFloat(v.residue) == 0){
              $('#edit_residue_' + arr_mesin[v.mesin-1]).val('');
            }else{
              $('#edit_residue_' + arr_mesin[v.mesin-1]).val(parseFloat(v.residue));
            }
          });
        })
      });
    });

    $('body').on('click', '#view-data-prd', function () {
      var tanggal = $(this).data("id");

      document.getElementById("title_lihat_laporan_produksi").innerHTML = "Laporan Produksi Tanggal " + tanggal;

      var urldata = "{{ url('laporan_produksi/data/view/tanggal') }}";
      urldata = urldata.replace('tanggal', enc(tanggal.toString()));
      $.get(urldata, function (dataprd) {
        $('#td_referensi_laporan').html(dataprd.referensi);
      })

      var url = "{{ url('laporan_produksi/detail_laporan/tanggal_laporan_produksi') }}";
      url = url.replace('tanggal_laporan_produksi', enc(tanggal.toString()));
      $('#td_tanggal_laporan').html('');
      $('#td_tanggal_laporan').html(tanggal);
      $('#table_laporan').html('');
      $('#table_laporan').html(
        '<thead>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">Mesin</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">AA20</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">AA25</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">AA40</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">BB40</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">CC50</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">DD50</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">SSF25</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">SW30</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">SW40</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">SF30</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">SS30</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">SSS30</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">AC30</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">NL25</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">POLOS40</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">KDCC</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">DCB25</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">DCD25</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">DCD50</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">DCE50</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">JAA</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">JBB</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">JDD</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">JSS</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">JSW</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">JPAC</th>'+
        '<th style="vertical-align: top; text-align: center; background-color:#9ecbff;">JDCD</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">SA</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_sa"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_sa"></th>'+
        
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">SB</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_sb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_sb"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Mixer</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_mixer"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_mixer"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">RA</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_ra"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_ra"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">RB</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_rb"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_rb"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">RC</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_rc"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_rc"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">RD</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_rd"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_rd"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">RE</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_re"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_re"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">RF</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_rf"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_rf"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">RG</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_rg"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_rg"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Coating</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_coating"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_coating"></th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Total</td>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa20_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa25_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_aa40_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_bb40_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_cc50_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dd50_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ssf25_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw30_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sw40_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sf30_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ss30_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_sss30_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_ac30_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_nl25_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_polos40_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_kdcc_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcb25_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd25_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dcd50_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_dce50_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jaa_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jbb_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdd_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jss_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jsw_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jpac_total"></th>'+
        '<td style="vertical-align: top; text-align: center;" id="td_jdcd_total"></th>'+
        '</tr>'+
        '</thead>'
      );

      $.get(url, function (data) {
        var total_aa40 = 0;
        for(var i = 0; i < data.aa40.length; i++){
          total_aa40 += data.aa40[i].jumlah_sak;
          if(data.aa40[i].mesin == 1){
            $('#td_aa40_sa').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 2){
            $('#td_aa40_sb').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 3){
            $('#td_aa40_mixer').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 4){
            $('#td_aa40_ra').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 5){
            $('#td_aa40_rb').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 6){
            $('#td_aa40_rc').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 7){
            $('#td_aa40_rd').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 8){
            $('#td_aa40_re').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 9){
            $('#td_aa40_rf').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 10){
            $('#td_aa40_rg').html(data.aa40[i].jumlah_sak);
          }else if(data.aa40[i].mesin == 11){
            $('#td_aa40_coating').html(data.aa40[i].jumlah_sak);
          }
        }

        var total_aa25 = 0;
        for(var i = 0; i < data.aa25.length; i++){
          total_aa25 += data.aa25[i].jumlah_sak;
          if(data.aa25[i].mesin == 1){
            $('#td_aa25_sa').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 2){
            $('#td_aa25_sb').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 3){
            $('#td_aa25_mixer').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 4){
            $('#td_aa25_ra').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 5){
            $('#td_aa25_rb').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 6){
            $('#td_aa25_rc').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 7){
            $('#td_aa25_rd').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 8){
            $('#td_aa25_re').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 9){
            $('#td_aa25_rf').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 10){
            $('#td_aa25_rg').html(data.aa25[i].jumlah_sak);
          }else if(data.aa25[i].mesin == 11){
            $('#td_aa25_coating').html(data.aa25[i].jumlah_sak);
          }
        }

        var total_aa20 = 0;
        for(var i = 0; i < data.aa20.length; i++){
          total_aa20 += data.aa20[i].jumlah_sak;
          if(data.aa20[i].mesin == 1){
            $('#td_aa20_sa').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 2){
            $('#td_aa20_sb').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 3){
            $('#td_aa20_mixer').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 4){
            $('#td_aa20_ra').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 5){
            $('#td_aa20_rb').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 6){
            $('#td_aa20_rc').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 7){
            $('#td_aa20_rd').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 8){
            $('#td_aa20_re').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 9){
            $('#td_aa20_rf').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 10){
            $('#td_aa20_rg').html(data.aa20[i].jumlah_sak);
          }else if(data.aa20[i].mesin == 11){
            $('#td_aa20_coating').html(data.aa40[i].jumlah_sak);
          }
        }

        var total_bb40 = 0;
        for(var i = 0; i < data.bb40.length; i++){
          total_bb40 += data.bb40[i].jumlah_sak;
          if(data.bb40[i].mesin == 1){
            $('#td_bb40_sa').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 2){
            $('#td_bb40_sb').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 3){
            $('#td_bb40_mixer').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 4){
            $('#td_bb40_ra').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 5){
            $('#td_bb40_rb').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 6){
            $('#td_bb40_rc').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 7){
            $('#td_bb40_rd').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 8){
            $('#td_bb40_re').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 9){
            $('#td_bb40_rf').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 10){
            $('#td_bb40_rg').html(data.bb40[i].jumlah_sak);
          }else if(data.bb40[i].mesin == 11){
            $('#td_bb40_coating').html(data.bb40[i].jumlah_sak);
          }
        }

        var total_cc50 = 0;
        for(var i = 0; i < data.cc50.length; i++){
          total_cc50 += data.cc50[i].jumlah_sak;
          if(data.cc50[i].mesin == 1){
            $('#td_cc50_sa').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 2){
            $('#td_cc50_sb').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 3){
            $('#td_cc50_mixer').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 4){
            $('#td_cc50_ra').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 5){
            $('#td_cc50_rb').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 6){
            $('#td_cc50_rc').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 7){
            $('#td_cc50_rd').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 8){
            $('#td_cc50_re').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 9){
            $('#td_cc50_rf').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 10){
            $('#td_cc50_rg').html(data.cc50[i].jumlah_sak);
          }else if(data.cc50[i].mesin == 11){
            $('#td_cc50_coating').html(data.cc50[i].jumlah_sak);
          }
        }

        var total_dd50 = 0;
        for(var i = 0; i < data.dd50.length; i++){
          total_dd50 += data.dd50[i].jumlah_sak;
          if(data.dd50[i].mesin == 1){
            $('#td_dd50_sa').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 2){
            $('#td_dd50_sb').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 3){
            $('#td_dd50_mixer').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 4){
            $('#td_dd50_ra').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 5){
            $('#td_dd50_rb').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 6){
            $('#td_dd50_rc').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 7){
            $('#td_dd50_rd').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 8){
            $('#td_dd50_re').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 9){
            $('#td_dd50_rf').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 10){
            $('#td_dd50_rg').html(data.dd50[i].jumlah_sak);
          }else if(data.dd50[i].mesin == 11){
            $('#td_dd50_coating').html(data.dd50[i].jumlah_sak);
          }
        }

        var total_ssf25 = 0;
        for(var i = 0; i < data.ssf25.length; i++){
          total_ssf25 += data.ssf25[i].jumlah_sak;
          if(data.ssf25[i].mesin == 1){
            $('#td_ssf25_sa').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 2){
            $('#td_ssf25_sb').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 3){
            $('#td_ssf25_mixer').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 4){
            $('#td_ssf25_ra').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 5){
            $('#td_ssf25_rb').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 6){
            $('#td_ssf25_rc').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 7){
            $('#td_ssf25_rd').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 8){
            $('#td_ssf25_re').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 9){
            $('#td_ssf25_rf').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 10){
            $('#td_ssf25_rg').html(data.ssf25[i].jumlah_sak);
          }else if(data.ssf25[i].mesin == 11){
            $('#td_ssf25_coating').html(data.ssf25[i].jumlah_sak);
          }
        }

        var total_sw30 = 0;
        for(var i = 0; i < data.sw30.length; i++){
          total_sw30 += data.sw30[i].jumlah_sak;
          if(data.sw30[i].mesin == 1){
            $('#td_sw30_sa').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 2){
            $('#td_sw30_sb').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 3){
            $('#td_sw30_mixer').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 4){
            $('#td_sw30_ra').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 5){
            $('#td_sw30_rb').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 6){
            $('#td_sw30_rc').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 7){
            $('#td_sw30_rd').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 8){
            $('#td_sw30_re').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 9){
            $('#td_sw30_rf').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 10){
            $('#td_sw30_rg').html(data.sw30[i].jumlah_sak);
          }else if(data.sw30[i].mesin == 11){
            $('#td_sw30_coating').html(data.sw30[i].jumlah_sak);
          }
        }

        var total_sw40 = 0;
        for(var i = 0; i < data.sw40.length; i++){
          total_sw40 += data.sw40[i].jumlah_sak;
          if(data.sw40[i].mesin == 1){
            $('#td_sw40_sa').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 2){
            $('#td_sw40_sb').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 3){
            $('#td_sw40_mixer').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 4){
            $('#td_sw40_ra').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 5){
            $('#td_sw40_rb').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 6){
            $('#td_sw40_rc').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 7){
            $('#td_sw40_rd').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 8){
            $('#td_sw40_re').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 9){
            $('#td_sw40_rf').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 10){
            $('#td_sw40_rg').html(data.sw40[i].jumlah_sak);
          }else if(data.sw40[i].mesin == 11){
            $('#td_sw40_coating').html(data.sw40[i].jumlah_sak);
          }
        }

        var total_sf30 = 0;
        for(var i = 0; i < data.sf30.length; i++){
          total_sf30 += data.sf30[i].jumlah_sak;
          if(data.sf30[i].mesin == 1){
            $('#td_sf30_sa').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 2){
            $('#td_sf30_sb').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 3){
            $('#td_sf30_mixer').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 4){
            $('#td_sf30_ra').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 5){
            $('#td_sf30_rb').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 6){
            $('#td_sf30_rc').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 7){
            $('#td_sf30_rd').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 8){
            $('#td_sf30_re').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 9){
            $('#td_sf30_rf').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 10){
            $('#td_sf30_rg').html(data.sf30[i].jumlah_sak);
          }else if(data.sf30[i].mesin == 11){
            $('#td_sf30_coating').html(data.sf30[i].jumlah_sak);
          }
        }

        var total_ss30 = 0;
        for(var i = 0; i < data.ss30.length; i++){
          total_ss30 += data.ss30[i].jumlah_sak;
          if(data.ss30[i].mesin == 1){
            $('#td_ss30_sa').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 2){
            $('#td_ss30_sb').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 3){
            $('#td_ss30_mixer').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 4){
            $('#td_ss30_ra').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 5){
            $('#td_ss30_rb').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 6){
            $('#td_ss30_rc').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 7){
            $('#td_ss30_rd').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 8){
            $('#td_ss30_re').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 9){
            $('#td_ss30_rf').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 10){
            $('#td_ss30_rg').html(data.ss30[i].jumlah_sak);
          }else if(data.ss30[i].mesin == 11){
            $('#td_ss30_coating').html(data.ss30[i].jumlah_sak);
          }
        }

        var total_sss30 = 0;
        for(var i = 0; i < data.sss30.length; i++){
          total_sss30 += data.sss30[i].jumlah_sak;
          if(data.sss30[i].mesin == 1){
            $('#td_sss30_sa').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 2){
            $('#td_sss30_sb').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 3){
            $('#td_sss30_mixer').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 4){
            $('#td_sss30_ra').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 5){
            $('#td_sss30_rb').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 6){
            $('#td_sss30_rc').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 7){
            $('#td_sss30_rd').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 8){
            $('#td_sss30_re').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 9){
            $('#td_sss30_rf').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 10){
            $('#td_sss30_rg').html(data.sss30[i].jumlah_sak);
          }else if(data.sss30[i].mesin == 11){
            $('#td_sss30_coating').html(data.sss30[i].jumlah_sak);
          }
        }

        var total_ac30 = 0;
        for(var i = 0; i < data.ac30.length; i++){
          total_ac30 += data.ac30[i].jumlah_sak;
          if(data.ac30[i].mesin == 1){
            $('#td_ac30_sa').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 2){
            $('#td_ac30_sb').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 3){
            $('#td_ac30_mixer').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 4){
            $('#td_ac30_ra').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 5){
            $('#td_ac30_rb').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 6){
            $('#td_ac30_rc').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 7){
            $('#td_ac30_rd').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 8){
            $('#td_ac30_re').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 9){
            $('#td_ac30_rf').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 10){
            $('#td_ac30_rg').html(data.ac30[i].jumlah_sak);
          }else if(data.ac30[i].mesin == 11){
            $('#td_ac30_coating').html(data.ac30[i].jumlah_sak);
          }
        }

        var total_nl25 = 0;
        for(var i = 0; i < data.nl25.length; i++){
          total_nl25 += data.nl25[i].jumlah_sak;
          if(data.nl25[i].mesin == 1){
            $('#td_nl25_sa').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 2){
            $('#td_nl25_sb').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 3){
            $('#td_nl25_mixer').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 4){
            $('#td_nl25_ra').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 5){
            $('#td_nl25_rb').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 6){
            $('#td_nl25_rc').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 7){
            $('#td_nl25_rd').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 8){
            $('#td_nl25_re').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 9){
            $('#td_nl25_rf').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 10){
            $('#td_nl25_rg').html(data.nl25[i].jumlah_sak);
          }else if(data.nl25[i].mesin == 11){
            $('#td_nl25_coating').html(data.nl25[i].jumlah_sak);
          }
        }

        var total_jaa = 0;
        for(var i = 0; i < data.jaa.length; i++){
          total_jaa += data.jaa[i].jumlah_sak;
          if(data.jaa[i].mesin == 1){
            $('#td_jaa_sa').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 2){
            $('#td_jaa_sb').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 3){
            $('#td_jaa_mixer').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 4){
            $('#td_jaa_ra').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 5){
            $('#td_jaa_rb').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 6){
            $('#td_jaa_rc').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 7){
            $('#td_jaa_rd').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 8){
            $('#td_jaa_re').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 9){
            $('#td_jaa_rf').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 10){
            $('#td_jaa_rg').html(data.jaa[i].jumlah_sak);
          }else if(data.jaa[i].mesin == 11){
            $('#td_jaa_coating').html(data.jaa[i].jumlah_sak);
          }
        }

        var total_jsw = 0;
        for(var i = 0; i < data.jsw.length; i++){
          total_jsw += data.jsw[i].jumlah_sak;
          if(data.jsw[i].mesin == 1){
            $('#td_jsw_sa').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 2){
            $('#td_jsw_sb').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 3){
            $('#td_jsw_mixer').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 4){
            $('#td_jsw_ra').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 5){
            $('#td_jsw_rb').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 6){
            $('#td_jsw_rc').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 7){
            $('#td_jsw_rd').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 8){
            $('#td_jsw_re').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 9){
            $('#td_jsw_rf').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 10){
            $('#td_jsw_rg').html(data.jsw[i].jumlah_sak);
          }else if(data.jsw[i].mesin == 11){
            $('#td_jsw_coating').html(data.jsw[i].jumlah_sak);
          }
        }

        var total_jpac = 0;
        for(var i = 0; i < data.jpac.length; i++){
          total_jpac += data.jpac[i].jumlah_sak;
          if(data.jpac[i].mesin == 1){
            $('#td_jpac_sa').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 2){
            $('#td_jpac_sb').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 3){
            $('#td_jpac_mixer').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 4){
            $('#td_jpac_ra').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 5){
            $('#td_jpac_rb').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 6){
            $('#td_jpac_rc').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 7){
            $('#td_jpac_rd').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 8){
            $('#td_jpac_re').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 9){
            $('#td_jpac_rf').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 10){
            $('#td_jpac_rg').html(data.jpac[i].jumlah_sak);
          }else if(data.jpac[i].mesin == 11){
            $('#td_jpac_coating').html(data.jpac[i].jumlah_sak);
          }
        }

        var total_polos40 = 0;
        for(var i = 0; i < data.polos40.length; i++){
          total_polos40 += data.polos40[i].jumlah_sak;
          if(data.polos40[i].mesin == 1){
            $('#td_polos40_sa').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 2){
            $('#td_polos40_sb').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 3){
            $('#td_polos40_mixer').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 4){
            $('#td_polos40_ra').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 5){
            $('#td_polos40_rb').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 6){
            $('#td_polos40_rc').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 7){
            $('#td_polos40_rd').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 8){
            $('#td_polos40_re').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 9){
            $('#td_polos40_rf').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 10){
            $('#td_polos40_rg').html(data.polos40[i].jumlah_sak);
          }else if(data.polos40[i].mesin == 11){
            $('#td_polos40_coating').html(data.polos40[i].jumlah_sak);
          }
        }

        var total_kdcc = 0;
        for(var i = 0; i < data.kdcc.length; i++){
          total_kdcc += data.kdcc[i].jumlah_sak;
          if(data.kdcc[i].mesin == 1){
            $('#td_kdcc_sa').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 2){
            $('#td_kdcc_sb').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 3){
            $('#td_kdcc_mixer').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 4){
            $('#td_kdcc_ra').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 5){
            $('#td_kdcc_rb').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 6){
            $('#td_kdcc_rc').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 7){
            $('#td_kdcc_rd').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 8){
            $('#td_kdcc_re').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 9){
            $('#td_kdcc_rf').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 10){
            $('#td_kdcc_rg').html(data.kdcc[i].jumlah_sak);
          }else if(data.kdcc[i].mesin == 11){
            $('#td_kdcc_coating').html(data.kdcc[i].jumlah_sak);
          }
        }

        var total_dcb25 = 0;
        for(var i = 0; i < data.dcb25.length; i++){
          total_dcb25 += data.dcb25[i].jumlah_sak;
          if(data.dcb25[i].mesin == 1){
            $('#td_dcb25_sa').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 2){
            $('#td_dcb25_sb').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 3){
            $('#td_dcb25_mixer').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 4){
            $('#td_dcb25_ra').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 5){
            $('#td_dcb25_rb').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 6){
            $('#td_dcb25_rc').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 7){
            $('#td_dcb25_rd').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 8){
            $('#td_dcb25_re').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 9){
            $('#td_dcb25_rf').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 10){
            $('#td_dcb25_rg').html(data.dcb25[i].jumlah_sak);
          }else if(data.dcb25[i].mesin == 11){
            $('#td_dcb25_coating').html(data.dcb25[i].jumlah_sak);
          }
        }

        var total_dcd50 = 0;
        for(var i = 0; i < data.dcd50.length; i++){
          total_dcd50 += data.dcd50[i].jumlah_sak;
          if(data.dcd50[i].mesin == 1){
            $('#td_dcd50_sa').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 2){
            $('#td_dcd50_sb').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 3){
            $('#td_dcd50_mixer').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 4){
            $('#td_dcd50_ra').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 5){
            $('#td_dcd50_rb').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 6){
            $('#td_dcd50_rc').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 7){
            $('#td_dcd50_rd').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 8){
            $('#td_dcd50_re').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 9){
            $('#td_dcd50_rf').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 10){
            $('#td_dcd50_rg').html(data.dcd50[i].jumlah_sak);
          }else if(data.dcd50[i].mesin == 11){
            $('#td_dcd50_coating').html(data.dcd50[i].jumlah_sak);
          }
        }

        var total_dce50 = 0;
        for(var i = 0; i < data.dce50.length; i++){
          total_dce50 += data.dce50[i].jumlah_sak;
          if(data.dce50[i].mesin == 1){
            $('#td_dce50_sa').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 2){
            $('#td_dce50_sb').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 3){
            $('#td_dce50_mixer').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 4){
            $('#td_dce50_ra').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 5){
            $('#td_dce50_rb').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 6){
            $('#td_dce50_rc').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 7){
            $('#td_dce50_rd').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 8){
            $('#td_dce50_re').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 9){
            $('#td_dce50_rf').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 10){
            $('#td_dce50_rg').html(data.dce50[i].jumlah_sak);
          }else if(data.dce50[i].mesin == 11){
            $('#td_dce50_coating').html(data.dce50[i].jumlah_sak);
          }
        }

        var total_dcd25 = 0;
        for(var i = 0; i < data.dcd25.length; i++){
          total_dcd25 += data.dcd25[i].jumlah_sak;
          if(data.dcd25[i].mesin == 1){
            $('#td_dcd25_sa').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 2){
            $('#td_dcd25_sb').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 3){
            $('#td_dcd25_mixer').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 4){
            $('#td_dcd25_ra').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 5){
            $('#td_dcd25_rb').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 6){
            $('#td_dcd25_rc').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 7){
            $('#td_dcd25_rd').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 8){
            $('#td_dcd25_re').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 9){
            $('#td_dcd25_rf').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 10){
            $('#td_dcd25_rg').html(data.dcd25[i].jumlah_sak);
          }else if(data.dcd25[i].mesin == 11){
            $('#td_dcd25_coating').html(data.dcd25[i].jumlah_sak);
          }
        }

        var total_jdcd = 0;
        for(var i = 0; i < data.jdcd.length; i++){
          total_jdcd += data.jdcd[i].jumlah_sak;
          if(data.jdcd[i].mesin == 1){
            $('#td_jdcd_sa').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 2){
            $('#td_jdcd_sb').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 3){
            $('#td_jdcd_mixer').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 4){
            $('#td_jdcd_ra').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 5){
            $('#td_jdcd_rb').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 6){
            $('#td_jdcd_rc').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 7){
            $('#td_jdcd_rd').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 8){
            $('#td_jdcd_re').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 9){
            $('#td_jdcd_rf').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 10){
            $('#td_jdcd_rg').html(data.jdcd[i].jumlah_sak);
          }else if(data.jdcd[i].mesin == 11){
            $('#td_jdcd_coating').html(data.jdcd[i].jumlah_sak);
          }
        }

        var total_jdd = 0;
        for(var i = 0; i < data.jdd.length; i++){
          total_jdd += data.jdd[i].jumlah_sak;
          if(data.jdd[i].mesin == 1){
            $('#td_jdd_sa').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 2){
            $('#td_jdd_sb').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 3){
            $('#td_jdd_mixer').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 4){
            $('#td_jdd_ra').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 5){
            $('#td_jdd_rb').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 6){
            $('#td_jdd_rc').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 7){
            $('#td_jdd_rd').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 8){
            $('#td_jdd_re').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 9){
            $('#td_jdd_rf').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 10){
            $('#td_jdd_rg').html(data.jdd[i].jumlah_sak);
          }else if(data.jdd[i].mesin == 11){
            $('#td_jdd_coating').html(data.jdd[i].jumlah_sak);
          }
        }

        var total_jbb = 0;
        for(var i = 0; i < data.jbb.length; i++){
          total_jbb += data.jbb[i].jumlah_sak;
          if(data.jbb[i].mesin == 1){
            $('#td_jbb_sa').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 2){
            $('#td_jbb_sb').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 3){
            $('#td_jbb_mixer').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 4){
            $('#td_jbb_ra').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 5){
            $('#td_jbb_rb').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 6){
            $('#td_jbb_rc').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 7){
            $('#td_jbb_rd').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 8){
            $('#td_jbb_re').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 9){
            $('#td_jbb_rf').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 10){
            $('#td_jbb_rg').html(data.jbb[i].jumlah_sak);
          }else if(data.jbb[i].mesin == 11){
            $('#td_jbb_coating').html(data.jbb[i].jumlah_sak);
          }
        }

        var total_jss = 0;
        for(var i = 0; i < data.jss.length; i++){
          total_jss += data.jss[i].jumlah_sak;
          if(data.jss[i].mesin == 1){
            $('#td_jss_sa').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 2){
            $('#td_jss_sb').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 3){
            $('#td_jss_mixer').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 4){
            $('#td_jss_ra').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 5){
            $('#td_jss_rb').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 6){
            $('#td_jss_rc').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 7){
            $('#td_jss_rd').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 8){
            $('#td_jss_re').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 9){
            $('#td_jss_rf').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 10){
            $('#td_jss_rg').html(data.jss[i].jumlah_sak);
          }else if(data.jss[i].mesin == 11){
            $('#td_jss_coating').html(data.jss[i].jumlah_sak);
          }
        }

        $('#td_aa40_total').html(total_aa40);
        $('#td_aa25_total').html(total_aa25);
        $('#td_aa20_total').html(total_aa20);
        $('#td_bb40_total').html(total_bb40);
        $('#td_cc50_total').html(total_cc50);
        $('#td_dd50_total').html(total_dd50);
        $('#td_ssf25_total').html(total_ssf25);
        $('#td_sw30_total').html(total_sw30);
        $('#td_sw40_total').html(total_sw40);
        $('#td_sf30_total').html(total_sf30);
        $('#td_ss30_total').html(total_ss30);
        $('#td_sss30_total').html(total_sss30);
        $('#td_ac30_total').html(total_ac30);
        $('#td_nl25_total').html(total_nl25);
        $('#td_jaa_total').html(total_jaa);
        $('#td_jsw_total').html(total_jsw);
        $('#td_jpac_total').html(total_jpac);
        $('#td_polos40_total').html(total_polos40);
        $('#td_kdcc_total').html(total_kdcc);
        $('#td_dcb25_total').html(total_dcb25);
        $('#td_dcd50_total').html(total_dcd50);
        $('#td_dce50_total').html(total_dce50);
        $('#td_dcd25_total').html(total_dcd25);
        $('#td_jdcd_total').html(total_jdcd);
        $('#td_jdd_total').html(total_jdd);
        $('#td_jbb_total').html(total_jbb);
        $('#td_jss_total').html(total_jss);
      })
    });

    $('#input_form').validate({
      rules: {
        tanggal: {
          required: true,
        },
        jam_waktu: {
          required: true,
        },
      },
      messages: {
        tanggal: {
          required: "Tanggal Harus Diisi",
        },
        jam_waktu: {
          required: "Jam Waktu Harus Diisi",
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
          url:"{{ url('laporan_hasil_lab/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_hasil_lab_table').dataTable();
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
        edit_tanggal: {
          required: true,
        },
        edit_jam_waktu: {
          required: true,
        },
      },
      messages: {
        edit_tanggal: {
          required: "Tanggal Harus Diisi",
        },
        edit_jam_waktu: {
          required: "Jam Waktu Harus Diisi",
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
          url:"{{ url('laporan_hasil_lab/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#edit_nomor').val(data.nomor_laporan_produksi).trigger('change');
            $('#edit_tanggal').val(data.tanggal);
            var oTable = $('#data_hasil_lab_table').dataTable();
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