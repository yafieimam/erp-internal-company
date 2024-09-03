@extends('layouts.app_admin')

@section('title')
<title>UJI KOMPETITOR - PT. DWI SELO GIRI MAS</title>
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
  .modal { overflow: auto !important; }
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
          <h1 class="m-0 text-dark">Uji Kompetitor</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Lab</li>
            <li class="breadcrumb-item">Uji Kompetitor</li>
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
          <table id="data_uji_sample_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_data">
    <div class="modal-dialog modal-lg">
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
            <!-- <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="tanggal" id="tanggal" autocomplete="off" placeholder="Tanggal">
                  </div>                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="pilih_batch">Pilih Batch</label>
                  <select id="pilih_batch" name="pilih_batch" class="form-control" disabled>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <button type="button" class="btn btn-primary" id="btn-lihat-data" style="width: 100%;" disabled="true">Tidak Ada Data</button>
                </div>
              </div>
            </div> -->
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="analisa">Analisa</label>
                  <textarea class="form-control" rows="3" name="analisa" id="analisa" placeholder="Analisa"></textarea>
                </div>
              </div>
              <input class="form-control" type="hidden" name="nomor" id="nomor" />
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="solusi">Solusi</label>
                  <textarea class="form-control" rows="3" name="solusi" id="solusi" placeholder="Solusi"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="lampiran">Lampiran</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="lampiran" name="lampiran">
                      <label class="custom-file-label" for="lampiran">Lampiran</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="vertical-align : middle; text-align: center; width: 40%;" colspan="2"></th>
                  <th style="vertical-align : middle; text-align: center;">Kompetitor</th>
                  <!-- <th style="vertical-align : middle; text-align: center;">DSGM</th> -->
                  <!-- <th style="vertical-align : middle; text-align: center;">Komplain</th> -->
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Produk</th>
                  <td style="vertical-align : middle; text-align: center;" id="produk_kompetitor"></td>
                  <!-- <td style="vertical-align : middle; text-align: center;">
                    <select id="produk_dsgm" name="produk_dsgm" class="form-control"></select>
                  </td>
                  <td style="vertical-align : middle; text-align: center;">
                    <select id="produk_komplain" name="produk_komplain" class="form-control"></select>
                  </td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Kadar Kalsium</th>
                  <td><input class="form-control" type="text" name="kalsium_kompetitor" id="kalsium_kompetitor" placeholder="Kadar Kalsium" /></td>
                  <!-- <td><input class="form-control" type="text" name="kalsium_dsgm" id="kalsium_dsgm" placeholder="Kadar Kalsium" /></td>
                  <td><input class="form-control" type="text" name="kalsium_komplain" id="kalsium_komplain" placeholder="Kadar Kalsium" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Whiteness</th>
                  <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                  <td><input class="form-control" type="text" name="cie86_kompetitor" id="cie86_kompetitor" placeholder="CIE 86" /></td>
                  <!-- <td><input class="form-control" type="text" name="cie86_dsgm" id="cie86_dsgm" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="cie86_komplain" id="cie86_komplain" placeholder="CIE 86" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                  <td><input class="form-control" type="text" name="iso2470_kompetitor" id="iso2470_kompetitor" placeholder="ISO 2470" /></td>
                  <!-- <td><input class="form-control" type="text" name="iso2470_dsgm" id="iso2470_dsgm" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="iso2470_komplain" id="iso2470_komplain" placeholder="ISO 2470" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Moisture</th>
                  <td><input class="form-control" type="text" name="moisture_kompetitor" id="moisture_kompetitor" placeholder="Moisture" /></td>
                  <!-- <td><input class="form-control" type="text" name="moisture_dsgm" id="moisture_dsgm" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="moisture_komplain" id="moisture_komplain" placeholder="Moisture" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                  <td><input class="form-control" type="text" name="ssa_kompetitor" id="ssa_kompetitor" placeholder="SSA" /></td>
                  <!-- <td><input class="form-control" type="text" name="ssa_dsgm" id="ssa_dsgm" placeholder="SSA" /></td>
                  <td><input class="form-control" type="text" name="ssa_komplain" id="ssa_komplain" placeholder="SSA" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                  <td><input class="form-control" type="text" name="d50_kompetitor" id="d50_kompetitor" placeholder="D-50" /></td>
                  <!-- <td><input class="form-control" type="text" name="d50_dsgm" id="d50_dsgm" placeholder="D-50" /></td>
                  <td><input class="form-control" type="text" name="d50_komplain" id="d50_komplain" placeholder="D-50" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                  <td><input class="form-control" type="text" name="d98_kompetitor" id="d98_kompetitor" placeholder="D-98" /></td>
                  <!-- <td><input class="form-control" type="text" name="d98_dsgm" id="d98_dsgm" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="d98_komplain" id="d98_komplain" placeholder="D-98" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Residue</th>
                  <td><input class="form-control" type="text" name="residue_kompetitor" id="residue_kompetitor" placeholder="Residue" /></td>
                  <!-- <td><input class="form-control" type="text" name="residue_dsgm" id="residue_dsgm" placeholder="Residue" /></td>
                  <td><input class="form-control" type="text" name="residue_komplain" id="residue_komplain" placeholder="Residue" /></td> -->
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

  <div class="modal fade" id="modal_cari_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Cari Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="cari_form" id="cari_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
          <div class="row">
            <div class="col-lg-12">
              <table id="data_laporan_harian_table" style="width: 100%; font-size: 13px; display: none;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2"></th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">SSA</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">D-50</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">D-98</th>
                    <th style="text-align: center;" colspan="2">Whiteness</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
                  </tr>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                    <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" class="btn btn-primary" id="btn-save-cari">Save changes</button>
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
            <div class="col-lg-12 lihat-table">
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td>Nomor</td>
                  <td>:</td>
                  <td id="td_nomor"></td>
                  <td>Analisa</td>
                  <td>:</td>
                  <td id="td_analisa"></td>
                </tr>
                <tr>
                  <td>Solusi</td>
                  <td>:</td>
                  <td id="td_solusi"></td>
                  <td>Lampiran</td>
                  <td>:</td>
                  <td id="td_lampiran"></td>
                </tr>
                <tr>
                  <td>Komentar Produksi</td>
                  <td>:</td>
                  <td colspan="4" id="td_komentar_produksi"></td>
                </tr>
              </table>
              <h5>Data Uji Sample Lab : </h5>
              <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center; width: 30%;" colspan="2"></th>
                    <th style="vertical-align : middle; text-align: center;">Kompetitor</th>
                    <th id="head_view_data_dsgm" style="vertical-align : middle; text-align: center;">DSGM</th> 
                    <!-- <th style="vertical-align : middle; text-align: center;">Komplain</th>                   -->
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
    <div class="modal-dialog modal-lg">
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
            <!-- <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="edit_tanggal" id="edit_tanggal" autocomplete="off" placeholder="Tanggal">
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_pilih_batch">Pilih Batch</label>
                  <select id="edit_pilih_batch" name="edit_pilih_batch" class="form-control" disabled>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <button type="button" class="btn btn-warning" id="edit-btn-lihat-data" style="width: 100%;" disabled="true">Tidak Ada Data</button>
                </div>
              </div>
            </div> -->
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="analisa">Analisa</label>
                  <textarea class="form-control" rows="3" name="edit_analisa" id="edit_analisa" placeholder="Analisa"></textarea>
                </div>
              </div>
              <input class="form-control" type="hidden" name="edit_nomor" id="edit_nomor" />
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="solusi">Solusi</label>
                  <textarea class="form-control" rows="3" name="edit_solusi" id="edit_solusi" placeholder="Solusi"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8">
                <div class="form-group">
                  <label for="edit_lampiran">Lampiran</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="edit_lampiran" name="edit_lampiran">
                      <label class="custom-file-label" for="edit_lampiran">Lampiran</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <div class="custom-control custom-radio radio-control">
                    <a target="_blank" id="lihat_lampiran" class="btn btn-primary save-btn-in">Lihat</a>
                  </div>
                </div>
              </div>
            </div>
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th style="vertical-align : middle; text-align: center; width: 40%;" colspan="2"></th>
                  <th style="vertical-align : middle; text-align: center;">Kompetitor</th>
                  <!-- <th style="vertical-align : middle; text-align: center;">DSGM</th> -->
                  <!-- <th style="vertical-align : middle; text-align: center;">Komplain</th> -->
                  <input class="form-control" type="hidden" name="edit_nomor" id="edit_nomor"/>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Produk</th>
                  <td style="vertical-align : middle; text-align: center;" id="edit_produk_kompetitor"></td>
                  <!-- <td style="vertical-align : middle; text-align: center;">
                    <select id="edit_produk_dsgm" name="edit_produk_dsgm" class="form-control"></select>
                  </td> -->
                  <!-- <td style="vertical-align : middle; text-align: center;">
                    <select id="edit_produk_komplain" name="edit_produk_komplain" class="form-control"></select>
                  </td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Kadar Kalsium</th>
                  <td><input class="form-control" type="text" name="edit_kalsium_kompetitor" id="edit_kalsium_kompetitor" placeholder="Kadar Kalsium" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_kalsium_dsgm" id="edit_kalsium_dsgm" placeholder="Kadar Kalsium" /></td> -->
                  <!-- <td><input class="form-control" type="text" name="edit_kalsium_komplain" id="edit_kalsium_komplain" placeholder="Kadar Kalsium" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" rowspan="2">Whiteness</th>
                  <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                  <td><input class="form-control" type="text" name="edit_cie86_kompetitor" id="edit_cie86_kompetitor" placeholder="CIE 86" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_cie86_dsgm" id="edit_cie86_dsgm" placeholder="CIE 86" /></td>
                  <td><input class="form-control" type="text" name="edit_cie86_komplain" id="edit_cie86_komplain" placeholder="CIE 86" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                  <td><input class="form-control" type="text" name="edit_iso2470_kompetitor" id="edit_iso2470_kompetitor" placeholder="ISO 2470" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_iso2470_dsgm" id="edit_iso2470_dsgm" placeholder="ISO 2470" /></td>
                  <td><input class="form-control" type="text" name="edit_iso2470_komplain" id="edit_iso2470_komplain" placeholder="ISO 2470" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Moisture</th>
                  <td><input class="form-control" type="text" name="edit_moisture_kompetitor" id="edit_moisture_kompetitor" placeholder="Moisture" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_moisture_dsgm" id="edit_moisture_dsgm" placeholder="Moisture" /></td>
                  <td><input class="form-control" type="text" name="edit_moisture_komplain" id="edit_moisture_komplain" placeholder="Moisture" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                  <td><input class="form-control" type="text" name="edit_ssa_kompetitor" id="edit_ssa_kompetitor" placeholder="SSA" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_ssa_dsgm" id="edit_ssa_dsgm" placeholder="SSA" /></td>
                  <td><input class="form-control" type="text" name="edit_ssa_komplain" id="edit_ssa_komplain" placeholder="SSA" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                  <td><input class="form-control" type="text" name="edit_d50_kompetitor" id="edit_d50_kompetitor" placeholder="D-50" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_d50_dsgm" id="edit_d50_dsgm" placeholder="D-50" /></td>
                  <td><input class="form-control" type="text" name="edit_d50_komplain" id="edit_d50_komplain" placeholder="D-50" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                  <td><input class="form-control" type="text" name="edit_d98_kompetitor" id="edit_d98_kompetitor" placeholder="D-98" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_d98_dsgm" id="edit_d98_dsgm" placeholder="D-98" /></td>
                  <td><input class="form-control" type="text" name="edit_d98_komplain" id="edit_d98_komplain" placeholder="D-98" /></td> -->
                </tr>
                <tr>
                  <th style="vertical-align : middle; text-align: center;" colspan="2">Residue</th>
                  <td><input class="form-control" type="text" name="edit_residue_kompetitor" id="edit_residue_kompetitor" placeholder="Residue" /></td>
                  <!-- <td><input class="form-control" type="text" name="edit_residue_dsgm" id="edit_residue_dsgm" placeholder="Residue" /></td>
                  <td><input class="form-control" type="text" name="edit_residue_komplain" id="edit_residue_komplain" placeholder="Residue" /></td> -->
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

  <div class="modal fade" id="modal_update_data">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="update_form" id="update_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-update">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_cari_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Cari Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="edit_cari_form" id="edit_cari_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
          <div class="row">
            <div class="col-lg-12">
              <table id="edit_data_laporan_harian_table" style="width: 100%; font-size: 13px; display: none;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2"></th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">SSA</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">D-50</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">D-98</th>
                    <th style="text-align: center;" colspan="2">Whiteness</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                    <th style="vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
                  </tr>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                    <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" class="btn btn-primary" id="btn-edit-cari">Save changes</button>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>

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

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#data_uji_sample_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("lab/uji_sample/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc'], [0,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'nomor',
           name:'nomor',
           className:'dt-center',
         },
         {
           data:'tanggal',
           name:'tanggal',
           className:'dt-center',
         },
         {
           data:'action',
           name:'action',
           className:'dt-center',
           width:'20%'
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

    function load_data_uji_sample(from_date = '', to_date = '')
    {
      table = $('#data_uji_sample_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("lab/uji_sample/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc'], [0,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'nomor',
           name:'nomor',
           className:'dt-center'
         },
         {
           data:'tanggal',
           name:'tanggal',
           className:'dt-center'
         },
         {
           data:'action',
           name:'action',
           className:'dt-center',
           width:'20%'
         }
       ]
     });
    }

    function load_data_cari_data(nomor_uji = '', tanggal = '', waktu = '')
    {
      table = $('#data_laporan_harian_table').DataTable({
         processing: true,
         serverSide: true,
         bFilter: false,
         bInfo: false,
         bPaginate: false,
         ajax: {
          url:'{{ url("lab/uji_sample/view/data/laporan_harian") }}',
          data:{nomor_uji:nomor_uji, tanggal:tanggal, waktu:waktu},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        columns: [
         {
           data:null,
           name:null,
           className:'dt-center',
           render: function ( data, type, row)
           {
            return '<input type="hidden" name="nomor_uji[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="' + $('<div />').text(row.nomor_uji_sample).html() + '"><input type="hidden" name="nomor_lab[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + '"><input type="checkbox" name="dipilih[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="1">';
           }
         },
         {
           data:'nama_mesin',
           name:'nama_mesin',
           className:'dt-center'
         },
         {
           data:'mesh',
           name:'mesh',
           className:'dt-center'
         },
         {
           data:'ssa',
           name:'ssa',
           className:'dt-center'
         },
         {
           data:'d50',
           name:'d50',
           className:'dt-center'
         },
         {
           data:'d98',
           name:'d98',
           className:'dt-center'
         },
         {
           data:'cie86',
           name:'cie86',
           className:'dt-center'
         },
         {
           data:'iso2470',
           name:'iso2470',
           className:'dt-center'
         },
         {
           data:'moisture',
           name:'moisture',
           className:'dt-center'
         },
         {
           data:'residue',
           name:'residue',
           className:'dt-center'
         }
       ]
     });
    }

    function load_data_edit_cari_data(nomor_uji = '', tanggal = '', waktu = '')
    {
      table = $('#edit_data_laporan_harian_table').DataTable({
         processing: true,
         serverSide: true,
         bFilter: false,
         bInfo: false,
         bPaginate: false,
         ajax: {
          url:'{{ url("lab/uji_sample/view/data/laporan_harian") }}',
          data:{nomor_uji:nomor_uji, tanggal:tanggal, waktu:waktu},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        columns: [
         {
           data:null,
           name:null,
           className:'dt-center',
           render: function ( data, type, row)
           {
            return '<input type="hidden" name="edit_nomor_uji[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="' + $('<div />').text(row.nomor_uji_sample).html() + '"><input type="hidden" name="edit_nomor_lab[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + '"><input type="checkbox" name="edit_dipilih[' + $('<div />').text(row.nomor_laporan_produksi_lab).html() + ']" value="1">';
           }
         },
         {
           data:'nama_mesin',
           name:'nama_mesin',
           className:'dt-center'
         },
         {
           data:'mesh',
           name:'mesh',
           className:'dt-center'
         },
         {
           data:'ssa',
           name:'ssa',
           className:'dt-center'
         },
         {
           data:'d50',
           name:'d50',
           className:'dt-center'
         },
         {
           data:'d98',
           name:'d98',
           className:'dt-center'
         },
         {
           data:'cie86',
           name:'cie86',
           className:'dt-center'
         },
         {
           data:'iso2470',
           name:'iso2470',
           className:'dt-center'
         },
         {
           data:'moisture',
           name:'moisture',
           className:'dt-center'
         },
         {
           data:'residue',
           name:'residue',
           className:'dt-center'
         }
       ]
     });
    }

    function isEmpty(obj) {
      for(var prop in obj) {
        if(obj.hasOwnProperty(prop)) {
          return false;
        }
      }

      return JSON.stringify(obj) === JSON.stringify({});
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#data_uji_sample_table').DataTable().destroy();
        load_data_uji_sample(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#data_uji_sample_table').DataTable().destroy();
      load_data_uji_sample();
    });

    $('body').on('click', '#input-data', function () {
      var nomor = $(this).data("id");
      $('#nomor').val(nomor);
      $('#modal_input_data').modal();

      var url = "{{ url('uji_sample/produk/kompetitor/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $.get(url, function (data) {
        $('#produk_kompetitor').html(data.merk + ' ' + data.tipe);
      })

      // var url_jenis_produk = "{{ url('get_jenis_produk') }}";
      // $.get(url_jenis_produk, function (data) {
      //   $('#produk_dsgm').children().remove().end().append('<option value="" selected>Pilih Produk</option>');
      //   $('#produk_komplain').children().remove().end().append('<option value="" selected>Pilih Produk</option>');
      //   $.each(data, function(l, m) {
      //     $('#produk_dsgm').append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
      //     $('#produk_komplain').append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
      //   });
      // })

      // var url = "{{ url('lab/uji_sample/cek/laporan_harian/nomor') }}";
      // url = url.replace('nomor', enc(nomor.toString()));
      // $.get(url, function (data) {
      //   if(!isEmpty(data)){
      //     $('#ssa_dsgm').val(data.ssa);
      //     $('#d50_dsgm').val(data.d50);
      //     $('#d98_dsgm').val(data.d98);
      //     $('#cie86_dsgm').val(data.cie86);
      //     $('#iso2470_dsgm').val(data.iso2470);
      //     $('#moisture_dsgm').val(data.moisture);
      //     $('#residue_dsgm').val(data.residue);
      //   }
      // })

      // $('#tanggal').change(function () {
      //   var tanggal = $(this).val();
      //   var url = "{{ url('lab/uji_sample/view/batch/laporan_harian/tanggal') }}";
      //   url = url.replace('tanggal', enc(tanggal.toString()));
      //   $.get(url, function (data) {
      //     if(data.length > 0){
      //       $('#pilih_batch').attr('disabled', false);
      //       $('#pilih_batch').children().remove().end().append('<option value="'+data[0].jam_waktu+'" selected>'+moment(data[0].jam_waktu, "HH:mm").format("HH:mm")+'</option>');
      //       $.each(data.slice(1), function(l, m) {
      //         $('#pilih_batch').append('<option value="' + m.jam_waktu + '">' + moment(m.jam_waktu, "HH:mm").format("HH:mm") + '</option>');
      //       });

      //       $('#data_laporan_harian_table').show();
      //       $('#btn-lihat-data').html('Lihat Data');
      //       $('#btn-lihat-data').attr('disabled', false);
      //       $('#data_laporan_harian_table').DataTable().destroy();
      //       load_data_cari_data(nomor, tanggal, data[0].jam_waktu);
      //     }else{
      //       $('#pilih_batch').attr('disabled', true);
      //       $('#pilih_batch').children().remove().end().append('<option value="" selected>Tidak Ada Data</option>');
      //       $('#data_laporan_harian_table').hide();
      //       $('#btn-lihat-data').html('Tidak Ada Data');
      //       $('#btn-lihat-data').attr('disabled', true);
      //     }
      //   })
      // });

      // $('#pilih_batch').change(function () {
      //   var waktu = $(this).val();
      //   var tanggal = $('#tanggal').val();
      //   $('#data_laporan_harian_table').DataTable().destroy();
      //   load_data_cari_data(nomor, tanggal, waktu)
      // });

      // $('body').on('click', '#btn-lihat-data', function () {
      //   $('#modal_input_data').modal('hide');
      //   $("#modal_input_data").trigger('click');
      //   $('#modal_cari_data').modal();
      // });
    });

    // $('#modal_cari_data').on('hidden.bs.modal', function () {
    //   var nomor = $('#nomor').val();
    //   var url = "{{ url('lab/uji_sample/cek/laporan_harian/nomor') }}";
    //   url = url.replace('nomor', enc(nomor.toString()));
    //   $.get(url, function (data) {
    //     if(!isEmpty(data)){
    //       $('#ssa_dsgm').val(data.ssa);
    //       $('#d50_dsgm').val(data.d50);
    //       $('#d98_dsgm').val(data.d98);
    //       $('#cie86_dsgm').val(data.cie86);
    //       $('#iso2470_dsgm').val(data.iso2470);
    //       $('#moisture_dsgm').val(data.moisture);
    //       $('#residue_dsgm').val(data.residue);
    //     }
    //   })

    //   $('#modal_input_data').modal();
    // });

    $('body').on('click', '#btn-save-cari', function () {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-cari').data('token'),
        },
        type: "POST",
        url: '{{ url("lab/uji_sample/cari") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          $('#modal_cari_data').modal('hide');
          $("#modal_cari_data").trigger('click');
          $('#modal_input_data').modal();
          alert('Data Successfully Updated');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong, Please Try Again");
        }
      });
    });

    $('body').on('click', '#view-data', function () {
      var nomor = $(this).data("id");
      var url_data = "{{ url('uji_sample/view/nomor') }}";
      url_data = url_data.replace('nomor', enc(nomor.toString()));
      $('#td_nomor').html('');
      $('#td_lampiran').html('');
      $('#td_solusi').html('');
      $('#td_analisa').html('');
      $('#td_komentar_produksi').html('');
      $.get(url_data, function (data) {
        $('#td_nomor').html(data.data_uji.nomor);
        if(data.data_uji.analisa){
          $('#td_analisa').html(data.data_uji.analisa);
        }else{
          $('#td_analisa').html('--');
        }
        if(data.data_uji.solusi){
          $('#td_solusi').html(data.data_uji.solusi);
        }else{
          $('#td_solusi').html('--');
        }
        if(data.data_uji.komentar_produksi){
          $('#td_komentar_produksi').html(data.data_uji.komentar_produksi);
        }else{
          $('#td_komentar_produksi').html('--');
        }
        if(data.data_uji.lampiran){
          $('#td_lampiran').html('<a target="_blank" href="' + '../data_file/' + data.data_uji.lampiran + '">Lihat Lampiran</a>');
        }else{
          $('#td_lampiran').html('--');
        }
      })

      var url = "{{ url('lab/uji_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $('#modal_view_data').modal();
      $("#tbody_view").empty();
      $.get(url, function (data) {
        $('#head_view_data_dsgm').attr('colspan', data[1]);
        $.each(data[0], function(k, v) {
          if(k == 'CIE86'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" rowspan="2">Whiteness</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(j) == 0  || parseFloat(j) == null ? '-': (parseFloat(j) % 1 === 0 ? parseFloat(j).toFixed(1) : parseFloat(j)))+'</td>'
              ); 
            });
          }else if(k == 'ISO2470'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(j) == 0  || parseFloat(j) == null ? '-': (parseFloat(j) % 1 === 0 ? parseFloat(j).toFixed(1) : parseFloat(j)))+'</td>'
              ); 
            });
          }else if(k == 'SSA'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" colspan="2">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(j == 0  || j == null ? '-': j)+'</td>'
              ); 
            });
          }else if(k == 'Kalsium'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" colspan="2">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              if(j == null || j == ''){
                $('#view_data_'+k).append(
                  '<td style="vertical-align : middle; text-align: center;">-</td>'
                );
              }else{
                $('#view_data_'+k).append(
                  '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(j) == 0  || parseFloat(j) == null ? '-': (parseFloat(j) % 1 === 0 ? parseFloat(j).toFixed(1) : parseFloat(j)))+'</td>'
                ); 
              }
            });
          }else if(k == 'Harga'){
            return;
          }else if(k == 'Kelas'){
            return;
          }else if(k == 'Produk'){
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" colspan="2">'+k+'</td>'+
              '</tr>'
            ); 
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(j == null ? '-': j)+'</td>'
              ); 
            });
          }else{
            $('#tbody_view').append(
              '<tr id="view_data_'+k+'">'+
              '<td style="vertical-align : middle; text-align: center;" colspan="2">'+k+'</td>'+
              '</tr>'
            );
            $.each(v, function(i, j) {
              $('#view_data_'+k).append(
                '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(j) == 0  || parseFloat(j) == null ? '-': (parseFloat(j) % 1 === 0 ? parseFloat(j).toFixed(1) : parseFloat(j)))+'</td>'
              ); 
            });
          }
        });
      })
    });

    $('body').on('click', '#edit-data', function () {
      var nomor = $(this).data("id");
      $('#modal_edit_data').modal();
      var arr_produk = ['kompetitor'];

      var url = "{{ url('uji_sample/produk/kompetitor/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $.get(url, function (data) {
        $('#edit_produk_kompetitor').html(data.merk + ' ' + data.tipe);
      })

      // var url_jenis_produk = "{{ url('get_jenis_produk') }}";
      // $.get(url_jenis_produk, function (data) {
      //   $('#edit_produk_dsgm').children().remove().end().append('<option value="" selected>Pilih Produk</option>');
      //   $('#edit_produk_komplain').children().remove().end().append('<option value="" selected>Pilih Produk</option>');
      //   $.each(data, function(l, m) {
      //     $('#edit_produk_dsgm').append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
      //     $('#edit_produk_komplain').append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
      //   });
      // })

      // var url = "{{ url('lab/uji_sample/cek/laporan_harian/nomor') }}";
      // url = url.replace('nomor', enc(nomor.toString()));
      // $.get(url, function (data) {
      //   if(!isEmpty(data)){
      //     $('#edit_ssa_dsgm').val(data.ssa);
      //     $('#edit_d50_dsgm').val(data.d50);
      //     $('#edit_d98_dsgm').val(data.d98);
      //     $('#edit_cie86_dsgm').val(data.cie86);
      //     $('#edit_iso2470_dsgm').val(data.iso2470);
      //     $('#edit_moisture_dsgm').val(data.moisture);
      //     $('#edit_residue_dsgm').val(data.residue);
      //   }
      // })

      // $('#edit_tanggal').change(function () {
      //   var tanggal = $(this).val();
      //   var url = "{{ url('lab/uji_sample/view/batch/laporan_harian/tanggal') }}";
      //   url = url.replace('tanggal', enc(tanggal.toString()));
      //   $.get(url, function (data) {
      //     if(data.length > 0){
      //       $('#edit_pilih_batch').attr('disabled', false);
      //       $('#edit_pilih_batch').children().remove().end().append('<option value="'+data[0].jam_waktu+'" selected>'+moment(data[0].jam_waktu, "HH:mm").format("HH:mm")+'</option>');
      //       $.each(data.slice(1), function(l, m) {
      //         $('#edit_pilih_batch').append('<option value="' + m.jam_waktu + '">' + moment(m.jam_waktu, "HH:mm").format("HH:mm") + '</option>');
      //       });

      //       $('#edit_data_laporan_harian_table').show();
      //       $('#edit-btn-lihat-data').html('Edit Data');
      //       $('#edit-btn-lihat-data').attr('disabled', false);
      //       $('#edit_data_laporan_harian_table').DataTable().destroy();
      //       load_data_edit_cari_data(nomor, tanggal, data[0].jam_waktu);
      //     }else{
      //       $('#edit_pilih_batch').attr('disabled', true);
      //       $('#edit_pilih_batch').children().remove().end().append('<option value="" selected>Tidak Ada Data</option>');
      //       $('#edit_data_laporan_harian_table').hide();
      //       $('#edit-btn-lihat-data').html('Tidak Ada Data');
      //       $('#edit-btn-lihat-data').attr('disabled', true);
      //     }
      //   })
      // });

      // $('#edit_pilih_batch').change(function () {
      //   var waktu = $(this).val();
      //   var tanggal = $('#edit_tanggal').val();
      //   $('#edit_data_laporan_harian_table').DataTable().destroy();
      //   load_data_edit_cari_data(nomor, tanggal, waktu)
      // });

      // $('body').on('click', '#edit-btn-lihat-data', function () {
      //   $('#modal_edit_data').modal('hide');
      //   $("#modal_edit_data").trigger('click');
      //   $('#modal_edit_cari_data').modal();
      // });

      $('#edit_nomor').val(nomor);
      $('#edit_analisa').html('');
      $('#edit_solusi').html('');
      $('#edit_lampiran').val('');
      $.each(arr_produk, function(k, v) {
        $('#edit_kalsium_' + v).val('');
        $('#edit_ssa_' + v).val('');
        $('#edit_d50_' + v).val('');
        $('#edit_d98_' + v).val('');
        $('#edit_cie86_' + v).val('');
        $('#edit_iso2470_' + v).val('');
        $('#edit_moisture_' + v).val('');
        $('#edit_residue_' + v).val('');
      });

      var url_view = "{{ url('lab/uji_sample/view/detail/nomor') }}";
      url_view = url_view.replace('nomor', enc(nomor.toString()));
      $.get(url_view, function (data) {
        $('#edit_analisa').html(data[1].analisa);
        $('#edit_solusi').html(data[1].solusi);
        if(data[1].lampiran == null){
          $('#lihat_lampiran').html('Tidak Ada');
          $('#lihat_lampiran').addClass('disabled');
          $('#lihat_lampiran').attr('href', '#');
        }else{
          $('#lihat_lampiran').html('Lihat');
          $('#lihat_lampiran').removeClass('disabled');
          $('#lihat_lampiran').attr('href', '../data_file/' + data[1].lampiran);
        }
        $.each(data[0], function(k, v) {
          if(v[0].kalsium == null || v[0].kalsium == 0){
            $('#edit_kalsium_' + arr_produk[k-1]).val('');
          }else{
            $('#edit_kalsium_' + arr_produk[k-1]).val(v[0].kalsium);
          }
          if(v[0].ssa == null || v[0].ssa == 0){
            $('#edit_ssa_' + arr_produk[k-1]).val('');
          }else{
            $('#edit_ssa_' + arr_produk[k-1]).val(v[0].ssa);
          }
          if(v[0].d50 == null || v[0].d50 == 0){
            $('#edit_d50_' + arr_produk[k-1]).val('');
          }else{
            $('#edit_d50_' + arr_produk[k-1]).val(v[0].d50);
          }
          if(v[0].d98 == null || v[0].d98 == 0){
            $('#edit_d98_' + arr_produk[k-1]).val('');
          }else{
            $('#edit_d98_' + arr_produk[k-1]).val(v[0].d98);
          }
          if(v[0].cie86 == null || v[0].cie86 == 0){
            $('#edit_cie86_' + arr_produk[k-1]).val('');
          }else{
            $('#edit_cie86_' + arr_produk[k-1]).val(v[0].cie86);
          }
          if(v[0].iso2470 == null || v[0].iso2470 == 0){
            $('#edit_iso2470_' + arr_produk[k-1]).val('');
          }else{
            $('#edit_iso2470_' + arr_produk[k-1]).val(v[0].iso2470);
          }
          if(v[0].moisture == null || v[0].moisture == 0){
            $('#edit_moisture_' + arr_produk[k-1]).val('');
          }else{
            $('#edit_moisture_' + arr_produk[k-1]).val(v[0].moisture);
          }
          if(v[0].residue == null || v[0].residue == 0){
            $('#edit_residue_' + arr_produk[k-1]).val('');
          }else{
            $('#edit_residue_' + arr_produk[k-1]).val(v[0].residue);
          }
        });
      })
    });
    
    $('#modal_edit_cari_data').on('hidden.bs.modal', function () {
      var nomor = $('#edit_nomor').val();
      var url = "{{ url('lab/uji_sample/cek/laporan_harian/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $.get(url, function (data) {
        if(!isEmpty(data)){
          $('#edit_ssa_dsgm').val(data.ssa);
          $('#edit_d50_dsgm').val(data.d50);
          $('#edit_d98_dsgm').val(data.d98);
          $('#edit_cie86_dsgm').val(data.cie86);
          $('#edit_iso2470_dsgm').val(data.iso2470);
          $('#edit_moisture_dsgm').val(data.moisture);
          $('#edit_residue_dsgm').val(data.residue);
        }
      })

      $('#modal_edit_data').modal();
    });

    $('body').on('click', '#btn-edit-cari', function () {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-cari').data('token'),
        },
        type: "POST",
        url: '{{ url("lab/uji_sample/cari/edit") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(){
          $('#modal_edit_cari_data').modal('hide');
          $("#modal_edit_cari_data").trigger('click');
          $('#modal_edit_data').modal();
          alert('Data Successfully Updated');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong, Please Try Again");
        }
      });
    });

    $('#input_form').validate({
      rules: {
        nomor: {
          required: true,
        },
        analisa: {
          required: true,
        },
      },
      messages: {
        nomor: {
          required: "Nomor Harus Diisi",
        },
        analisa: {
          required: "Analisa Harus Diisi",
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
          url:"{{ url('lab/uji_sample/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_uji_sample_table').dataTable();
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

    $('#edit_form').validate({
      rules: {
        edit_nomor: {
          required: true,
        },
        edit_analisa: {
          required: true,
        },
      },
      messages: {
        edit_nomor: {
          required: "Nomor Harus Diisi",
        },
        edit_analisa: {
          required: "Analisa Harus Diisi",
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
          url:"{{ url('lab/uji_sample/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_uji_sample_table').dataTable();
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