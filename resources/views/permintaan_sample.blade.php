@extends('layouts.app_admin')

@section('title')
<title>PERMINTAAN SAMPLE - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Permintaan Sample</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Permintaan Sample</li>
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
        <div class="card-header">
          <div class="row">
            <div class="col-4">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="data_permintaan_sample_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Customers</th>
                <th>Status</th>
                <th width="15%"></th>
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
              <div class="col-3">
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
              <div class="col-3">
                <div class="form-group">
                  <label for="customers">Customers</label>
                  <select id="customers" name="customers" class="form-control select2 customers" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="new_customers">New Customers</label>
                  <input class="form-control" type="text" name="new_customers" id="new_customers" placeholder="New Customers" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="city">Kota</label>
                  <select id="city" name="city" class="form-control select2 city" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="city">Jenis Barang</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_batu_ketak" id="checkbox_batu_ketak" value="1">
                    <label for="checkbox_batu_ketak" class="custom-control-label">Batu Ketak</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_batu_kapur" id="checkbox_batu_kapur" value="2">
                    <label for="checkbox_batu_kapur" class="custom-control-label">Batu Kapur</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_batu_lainnya" id="checkbox_batu_lainnya" value="3">
                    <label for="checkbox_batu_lainnya" class="custom-control-label">Lainnya</label>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="batu_lainnya">&nbsp</label>
                  <input class="form-control" type="text" name="batu_lainnya" id="batu_lainnya" placeholder="Lainnya" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1">
                <div class="form-group">
                  <label for="city">Jenis Mesh</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_250" id="checkbox_mesh_250" value="1">
                    <label for="checkbox_mesh_250" class="custom-control-label">250</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_325" id="checkbox_mesh_325" value="2">
                    <label for="checkbox_mesh_325" class="custom-control-label">325</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_500" id="checkbox_mesh_500" value="3">
                    <label for="checkbox_mesh_500" class="custom-control-label">500</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_800_u1" id="checkbox_mesh_800_u1" value="4">
                    <label for="checkbox_mesh_800_u1" class="custom-control-label">800-U1</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_800_u2" id="checkbox_mesh_800_u2" value="5">
                    <label for="checkbox_mesh_800_u2" class="custom-control-label">800-U2</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_800_swaa" id="checkbox_mesh_800_swaa" value="6">
                    <label for="checkbox_mesh_800_swaa" class="custom-control-label">800-SWAA</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_1200" id="checkbox_mesh_1200" value="7">
                    <label for="checkbox_mesh_1200" class="custom-control-label">1200</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_1500" id="checkbox_mesh_1500" value="8">
                    <label for="checkbox_mesh_1500" class="custom-control-label">1500</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_2000" id="checkbox_mesh_2000" value="9">
                    <label for="checkbox_mesh_2000" class="custom-control-label">2000</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_6000" id="checkbox_mesh_6000" value="10">
                    <label for="checkbox_mesh_6000" class="custom-control-label">6000</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_2002c" id="checkbox_mesh_2002c" value="11">
                    <label for="checkbox_mesh_2002c" class="custom-control-label">2002C</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_mesh_lainnya" id="checkbox_mesh_lainnya" value="12">
                    <label for="checkbox_mesh_lainnya" class="custom-control-label">Lainnya</label>
                  </div>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="mesh_lainnya">&nbsp;</label>
                  <input class="form-control" type="text" name="mesh_lainnya" id="mesh_lainnya" placeholder="Lainnya" />
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">Jenis Mesh</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_qty_1kg" id="checkbox_qty_1kg" value="1">
                    <label for="checkbox_qty_1kg" class="custom-control-label">1 KG</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_qty_3kg" id="checkbox_qty_3kg" value="2">
                    <label for="checkbox_qty_3kg" class="custom-control-label">3 KG</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_qty_5kg" id="checkbox_qty_5kg" value="3">
                    <label for="checkbox_qty_5kg" class="custom-control-label">5 KG</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_qty_lainnya" id="checkbox_qty_lainnya" value="4">
                    <label for="checkbox_qty_lainnya" class="custom-control-label">Lainnya</label>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="qty_lainnya">Lainnya</label>
                  <input class="form-control" type="text" name="qty_lainnya" id="qty_lainnya" placeholder="Lainnya" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="city">Kirim</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_kirim_sendiri" id="checkbox_kirim_sendiri" value="1">
                    <label for="checkbox_kirim_sendiri" class="custom-control-label">Ambil / Kirim Sendiri</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="checkbox_ekspedisi" id="checkbox_ekspedisi" value="2">
                    <label for="checkbox_ekspedisi" class="custom-control-label">Ekspedisi</label>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="nama_ekspedisi">Nama Ekspedisi</label>
                  <input class="form-control" type="text" name="nama_ekspedisi" id="nama_ekspedisi" placeholder="Nama Ekspedisi" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="nomor_resi">Nomor Resi</label>
                  <input class="form-control" type="text" name="nomor_resi" id="nomor_resi" placeholder="Nomor Resi" />
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
                  <td>Tanggal</td>
                  <td>:</td>
                  <td id="td_tanggal"></td>
                </tr>
                <tr>
                  <td>Customers</td>
                  <td>:</td> 
                  <td id="td_customers"></td>
                  <td>Jenis Barang</td>
                  <td>:</td>
                  <td id="td_jenis_barang"></td>
                </tr>
                <tr>
                  <td>Quantity</td>
                  <td>:</td>
                  <td id="td_quantity"></td>
                  <td>Jenis Mesh</td>
                  <td>:</td>
                  <td id="td_jenis_mesh"></td>
                </tr>
                <tr>
                  <td>Pengiriman</td>
                  <td>:</td>
                  <td id="td_pengiriman"></td>
                  <td>Nomor Resi</td>
                  <td>:</td>
                  <td id="td_nomor_resi"></td>
                </tr>
                <tr>
                  <td>Respon Customers</td>
                  <td>:</td>
                  <td id="td_respon"></td>
                  <td>Analisa</td>
                  <td>:</td>
                  <td id="td_analisa"></td>
                </tr>
                <tr>
                  <td>Solusi</td>
                  <td>:</td>
                  <td id="td_solusi"></td>
                  <td>Lampiran Sales</td>
                  <td>:</td>
                  <td id="td_lampiran_sales"></td>
                </tr>
                <tr>
                  <td>Lampiran Produksi</td>
                  <td>:</td>
                  <td id="td_lampiran_produksi"></td>
                  <td>Status</td>
                  <td>:</td>
                  <td id="td_status"></td>
                </tr>
              </table>
              <h5>Data Lab : </h5>
              <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
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
          <h4 class="modal-title">Edit Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="edit_form" id="edit_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="edit_tanggal" id="edit_tanggal" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                  <input class="form-control" type="hidden" name="edit_nomor" id="edit_nomor" />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_customers">Customers</label>
                  <select id="edit_customers" name="edit_customers" class="form-control select2 edit_customers" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="city">Jenis Barang</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_batu_ketak" id="edit_checkbox_batu_ketak" value="1">
                    <label for="edit_checkbox_batu_ketak" class="custom-control-label">Batu Ketak</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_batu_kapur" id="edit_checkbox_batu_kapur" value="2">
                    <label for="edit_checkbox_batu_kapur" class="custom-control-label">Batu Kapur</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_batu_lainnya" id="edit_checkbox_batu_lainnya" value="3">
                    <label for="edit_checkbox_batu_lainnya" class="custom-control-label">Lainnya</label>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_batu_lainnya">&nbsp</label>
                  <input class="form-control" type="text" name="edit_batu_lainnya" id="edit_batu_lainnya" placeholder="Lainnya" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1">
                <div class="form-group">
                  <label for="city">Jenis Mesh</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_250" id="edit_checkbox_mesh_250" value="1">
                    <label for="edit_checkbox_mesh_250" class="custom-control-label">250</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_325" id="edit_checkbox_mesh_325" value="2">
                    <label for="edit_checkbox_mesh_325" class="custom-control-label">325</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_500" id="edit_checkbox_mesh_500" value="3">
                    <label for="edit_checkbox_mesh_500" class="custom-control-label">500</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_800_u1" id="edit_checkbox_mesh_800_u1" value="4">
                    <label for="edit_checkbox_mesh_800_u1" class="custom-control-label">800-U1</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_800_u2" id="edit_checkbox_mesh_800_u2" value="5">
                    <label for="edit_checkbox_mesh_800_u2" class="custom-control-label">800-U2</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_800_swaa" id="edit_checkbox_mesh_800_swaa" value="6">
                    <label for="edit_checkbox_mesh_800_swaa" class="custom-control-label">800-SWAA</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_1200" id="edit_checkbox_mesh_1200" value="7">
                    <label for="edit_checkbox_mesh_1200" class="custom-control-label">1200</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_1500" id="edit_checkbox_mesh_1500" value="8">
                    <label for="edit_checkbox_mesh_1500" class="custom-control-label">1500</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_2000" id="edit_checkbox_mesh_2000" value="9">
                    <label for="edit_checkbox_mesh_2000" class="custom-control-label">2000</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_6000" id="edit_checkbox_mesh_6000" value="10">
                    <label for="edit_checkbox_mesh_6000" class="custom-control-label">6000</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_2002c" id="edit_checkbox_mesh_2002c" value="11">
                    <label for="edit_checkbox_mesh_2002c" class="custom-control-label">2002C</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_mesh_lainnya" id="edit_checkbox_mesh_lainnya" value="12">
                    <label for="edit_checkbox_mesh_lainnya" class="custom-control-label">Lainnya</label>
                  </div>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="edit_mesh_lainnya">&nbsp;</label>
                  <input class="form-control" type="text" name="edit_mesh_lainnya" id="edit_mesh_lainnya" placeholder="Lainnya" />
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">Jenis Mesh</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_qty_1kg" id="edit_checkbox_qty_1kg" value="1">
                    <label for="edit_checkbox_qty_1kg" class="custom-control-label">1 KG</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_qty_3kg" id="edit_checkbox_qty_3kg" value="2">
                    <label for="edit_checkbox_qty_3kg" class="custom-control-label">3 KG</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_qty_5kg" id="edit_checkbox_qty_5kg" value="3">
                    <label for="edit_checkbox_qty_5kg" class="custom-control-label">5 KG</label>
                  </div>
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_qty_lainnya" id="edit_checkbox_qty_lainnya" value="4">
                    <label for="edit_checkbox_qty_lainnya" class="custom-control-label">Lainnya</label>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_qty_lainnya">Lainnya</label>
                  <input class="form-control" type="text" name="edit_qty_lainnya" id="edit_qty_lainnya" placeholder="Lainnya" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="city">Kirim</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_kirim_sendiri" id="edit_checkbox_kirim_sendiri" value="1">
                    <label for="edit_checkbox_kirim_sendiri" class="custom-control-label">Ambil / Kirim Sendiri</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="city">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="edit_checkbox_ekspedisi" id="edit_checkbox_ekspedisi" value="2">
                    <label for="edit_checkbox_ekspedisi" class="custom-control-label">Ekspedisi</label>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_nama_ekspedisi">Nama Ekspedisi</label>
                  <input class="form-control" type="text" name="edit_nama_ekspedisi" id="edit_nama_ekspedisi" placeholder="Nama Ekspedisi" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_nomor_resi">Nomor Resi</label>
                  <input class="form-control" type="text" name="edit_nomor_resi" id="edit_nomor_resi" placeholder="Nomor Resi" />
                </div>
              </div>
            </div>
            <div class="row" id="div_respon_customers" style="display: none;">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_respon">Respon Customers</label>
                  <textarea class="form-control" rows="3" name="edit_respon" id="edit_respon" placeholder="Respon Customers"></textarea>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="edit_lampiran">Lampiran</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="edit_lampiran" name="edit_lampiran">
                      <label class="custom-file-label" for="edit_lampiran">Lampiran</label>
                    </div>
                  </div>
                </div>
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
              </div>
              <div class="col-2">
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
          <h4 class="modal-title">Input Respon Customers</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="update_form" id="update_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="respon">Respon Customers</label>
                  <textarea class="form-control" rows="3" name="respon" id="respon" placeholder="Respon Customers"></textarea>
                </div>
              </div>
            </div>
            <input class="form-control" type="hidden" name="update_nomor" id="update_nomor" />
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
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
              </div>
            </div>
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

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#data_permintaan_sample_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("permintaan_sample/table") }}',
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
           data:'custname',
           name:'custname',
           className:'dt-center'
         },
         {
           data:'status',
           name:'status',
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

    function load_data_permintaan_sample(from_date = '', to_date = '')
    {
      table = $('#data_permintaan_sample_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("permintaan_sample/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[1,'desc'], [1,'desc']],
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
           data:'custname',
           name:'custname',
           className:'dt-center'
         },
         {
           data:'status',
           name:'status',
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

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#data_permintaan_sample_table').DataTable().destroy();
        load_data_permintaan_sample(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#data_permintaan_sample_table').DataTable().destroy();
      load_data_permintaan_sample();
    });

    $('body').on('click', '#btn_input_data', function () {
      $('#customers').on('select2:select', function (e) {
        $('#new_customers').attr("disabled", true);
        $('#city').attr("disabled", true);
      });

      $('#customers').on('select2:unselect', function (e) {
        $('#new_customers').attr("disabled", false);
        $('#city').attr("disabled", false);
      });
    });

    function isEmptyObj(object) { 
      for (var key in object) { 
        if (object.hasOwnProperty(key)) { 
          return false; 
        } 
      } 

      return true; 
    } 

    $('body').on('click', '#view-data', function () {
      var nomor = $(this).data("id");
      var barang = '';
      var kirim = '';
      var quantity = '';
      var mesh = '';
      var url = "{{ url('permintaan_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $('#td_nomor').html('');
      $('#td_tanggal').html('');
      $('#td_customers').html('');
      $('#td_jenis_barang').html('');
      $('#td_jenis_mesh').html('');
      $('#td_quantity').html('');
      $('#td_pengiriman').html('');
      $('#td_nomor_resi').html('');
      $('#td_analisa').html('');
      $('#td_solusi').html('');
      $('#td_status').html('');
      $.get(url, function (data) {
        $('#td_nomor').html(data.nomor);
        $('#td_tanggal').html(data.tanggal);
        $('#td_customers').html(data.custname);
        if(data.batu_ketak == 1 || data.batu_ketak == '1'){
          barang = barang.concat('Batu Ketak; ') ;
        }
        if(data.batu_kapur == 1 || data.batu_kapur == '1'){
          barang = barang.concat('Batu Kapur; ') ;
        }
        if(data.batu_lainnya == 1 || data.batu_lainnya == '1'){
          if(data.nama_batu_lainnya == null || data.nama_batu_lainnya == ''){
            barang = barang.concat('Lainnya; ') ;
          }else{
            barang = barang.concat(data.nama_batu_lainnya + '; ') ;
          }
        }
        $('#td_jenis_barang').html(barang);
        if(data.mesh_250 == 1 || data.mesh_250 == '1'){
          mesh = mesh.concat('250; ') ;
        }
        if(data.mesh_325 == 1 || data.mesh_325 == '1'){
          mesh = mesh.concat('325; ') ;
        }
        if(data.mesh_500 == 1 || data.mesh_500 == '1'){
          mesh = mesh.concat('500; ') ;
        }
        if(data.mesh_800_u1 == 1 || data.mesh_800_u1 == '1'){
          mesh = mesh.concat('800-U1; ') ;
        }
        if(data.mesh_800_u2 == 1 || data.mesh_800_u2 == '1'){
          mesh = mesh.concat('800-U2; ') ;
        }
        if(data.mesh_800_swaa == 1 || data.mesh_800_swaa == '1'){
          mesh = mesh.concat('800-SWAA; ') ;
        }
        if(data.mesh_1200 == 1 || data.mesh_1200 == '1'){
          mesh = mesh.concat('1200; ') ;
        }
        if(data.mesh_1500 == 1 || data.mesh_1500 == '1'){
          mesh = mesh.concat('1500; ') ;
        }
        if(data.mesh_2000 == 1 || data.mesh_2000 == '1'){
          mesh = mesh.concat('2000; ') ;
        }
        if(data.mesh_6000 == 1 || data.mesh_6000 == '1'){
          mesh = mesh.concat('6000; ') ;
        }
        if(data.mesh_2002c == 1 || data.mesh_2002c == '1'){
          mesh = mesh.concat('2002C; ') ;
        }
        if(data.mesh_lainnya == 1 || data.mesh_lainnya == '1'){
          if(data.nama_mesh_lainnya == null || data.nama_mesh_lainnya == ''){
            mesh = mesh.concat('Lainnya; ') ;
          }else{
            mesh = mesh.concat(data.nama_mesh_lainnya + '; ') ;
          }
        }
        $('#td_jenis_mesh').html(mesh);
        if(data.qty_1kg == 1 || data.qty_1kg == '1'){
          quantity = quantity.concat('1 KG; ') ;
        }
        if(data.qty_3kg == 1 || data.qty_3kg == '1'){
          quantity = quantity.concat('3 KG; ') ;
        }
        if(data.qty_5kg == 1 || data.qty_5kg == '1'){
          quantity = quantity.concat('5 KG; ') ;
        }
        if(data.qty_lainnya == 1 || data.qty_lainnya == '1'){
          if(data.nama_qty_lainnya == null || data.nama_qty_lainnya == ''){
            quantity = quantity.concat('Lainnya; ') ;
          }else{
            quantity = quantity.concat(data.nama_qty_lainnya + '; ') ;
          }
        }
        $('#td_quantity').html(quantity);
        if(data.ambil_sendiri == 1 || data.ambil_sendiri == '1'){
          kirim = kirim.concat('Ambil / Kirim Sendiri; ') ;
        }
        if(data.ekspedisi == 1 || data.ekspedisi == '1'){
          if(data.nama_ekspedisi == null || data.nama_ekspedisi == ''){
            kirim = kirim.concat('Lainnya; ') ;
          }else{
            kirim = kirim.concat(data.nama_ekspedisi + '; ') ;
          }
        }
        $('#td_pengiriman').html(kirim);
        if(data.nomor_resi){
          $('#td_nomor_resi').html(data.nomor_resi);
        }else{
          $('#td_nomor_resi').html('--');
        }
        if(data.respon_customers){
          $('#td_respon').html(data.respon_customers);
        }else{
          $('#td_respon').html('--');
        }
        if(data.analisa){
          $('#td_analisa').html(data.analisa);
        }else{
          $('#td_analisa').html('--');
        }
        if(data.solusi){
          $('#td_solusi').html(data.solusi);
        }else{
          $('#td_solusi').html('--');
        }
        if(data.lampiran_sales){
          $('#td_lampiran_sales').html('<a target="_blank" href="' + '../data_file/' + data.lampiran_sales + '">Lihat Lampiran</a>');
        }else{
          $('#td_lampiran_sales').html('--');
        }
        if(data.lampiran_produksi){
          $('#td_lampiran_produksi').html('<a target="_blank" href="' + '../data_file/' + data.lampiran_produksi + '">Lihat Lampiran</a>');
        }else{
          $('#td_lampiran_produksi').html('--');
        }
        $('#td_status').html(data.status);
      })

      var url_lab = "{{ url('lab/permintaan_sample/view/nomor') }}";
      url_lab = url_lab.replace('nomor', enc(nomor.toString()));
      $("#tbody_view").empty();
      $.get(url_lab, function (data) {
        if(isEmptyObj(data)){
          $('#tbody_view').append(
            '<tr>'+
            '<td style="vertical-align : middle; text-align: center;">Belum Ada Data</td>'+
            '</tr>'
            );
        }else{
          $('#tbody_view').append(
            '<tr>'+
            '<th style="vertical-align : middle; text-align: center;">Mesh</th>'+
            '<th style="vertical-align : middle; text-align: center;">SSA</th>'+
            '<th style="vertical-align : middle; text-align: center;">D-50</th>'+
            '<th style="vertical-align : middle; text-align: center;">D-98</th>'+
            '<th style="vertical-align : middle; text-align: center;">CIE 86</th>'+
            '<th style="vertical-align : middle; text-align: center;">ISO 2470</th>'+
            '<th style="vertical-align : middle; text-align: center;">Moisture</th>'+
            '<th style="vertical-align : middle; text-align: center;">Residue</th>'+
            '</tr>'
            );
          $.each(data, function(k, v) {
            $('#tbody_view').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;">'+v.mesh+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+v.ssa+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+v.d50+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+v.d98+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+v.cie86+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+v.iso2470+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+v.moisture+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+v.residue+'</td>'+
              '</tr>'
              );
          });
        }
      })
    });

    $('body').on('click', '#update-data', function () {
      var nomor = $(this).data("id");
      $('#update_nomor').val(nomor);
    });

    $('body').on('click', '#edit-data', function () {
      var nomor = $(this).data("id");
      var url = "{{ url('permintaan_sample/view/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $('#edit_nomor').val(nomor);
      $('#edit_tanggal').val('');
      $('#edit_customers').val('').trigger('change');
      $.get(url, function (data) {
        $('#edit_tanggal').val(data.tanggal);
        $("#edit_customers").append('<option value="' + data.customers + '">' + data.custname + '</option>');
        $("#edit_customers").val(data.customers);
        $("#edit_customers").trigger('change');
        if(data.batu_ketak == 1 || data.batu_ketak == '1'){
          $('#edit_checkbox_batu_ketak').prop('checked', true);
        }else{
          $('#edit_checkbox_batu_ketak').prop('checked', false);
        }
        if(data.batu_kapur == 1 || data.batu_kapur == '1'){
          $('#edit_checkbox_batu_kapur').prop('checked', true);
        }else{
          $('#edit_checkbox_batu_kapur').prop('checked', false);
        }
        if(data.batu_lainnya == 1 || data.batu_lainnya == '1'){
          $('#edit_checkbox_batu_lainnya').prop('checked', true);
        }else{
          $('#edit_checkbox_batu_lainnya').prop('checked', false);
        }
        if(data.mesh_250 == 1 || data.mesh_250 == '1'){
          $('#edit_checkbox_mesh_250').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_250').prop('checked', false);
        }
        if(data.mesh_325 == 1 || data.mesh_325 == '1'){
          $('#edit_checkbox_mesh_325').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_325').prop('checked', false);
        }
        if(data.mesh_500 == 1 || data.mesh_500 == '1'){
          $('#edit_checkbox_mesh_500').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_500').prop('checked', false);
        }
        if(data.mesh_800_u1 == 1 || data.mesh_800_u1 == '1'){
          $('#edit_checkbox_mesh_800_u1').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_800_u1').prop('checked', false);
        }
        if(data.mesh_800_u2 == 1 || data.mesh_800_u2 == '1'){
          $('#edit_checkbox_mesh_800_u2').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_800_u2').prop('checked', false);
        }
        if(data.mesh_800_swaa == 1 || data.mesh_800_swaa == '1'){
          $('#edit_checkbox_mesh_800_swaa').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_800_swaa').prop('checked', false);
        }
        if(data.mesh_1200 == 1 || data.mesh_1200 == '1'){
          $('#edit_checkbox_mesh_1200').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_1200').prop('checked', false);
        }
        if(data.mesh_1500 == 1 || data.mesh_1500 == '1'){
          $('#edit_checkbox_mesh_1500').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_1500').prop('checked', false);
        }
        if(data.mesh_2000 == 1 || data.mesh_2000 == '1'){
          $('#edit_checkbox_mesh_2000').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_2000').prop('checked', false);
        }
        if(data.mesh_6000 == 1 || data.mesh_6000 == '1'){
          $('#edit_checkbox_mesh_6000').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_6000').prop('checked', false);
        }
        if(data.mesh_2002c == 1 || data.mesh_2002c == '1'){
          $('#edit_checkbox_mesh_2002c').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_2002c').prop('checked', false);
        }
        if(data.mesh_lainnya == 1 || data.mesh_lainnya == '1'){
          $('#edit_checkbox_mesh_lainnya').prop('checked', true);
        }else{
          $('#edit_checkbox_mesh_lainnya').prop('checked', false);
        }
        if(data.qty_1kg == 1 || data.qty_1kg == '1'){
          $('#edit_checkbox_qty_1kg').prop('checked', true);
        }else{
          $('#edit_checkbox_qty_1kg').prop('checked', false);
        }
        if(data.qty_3kg == 1 || data.qty_3kg == '1'){
          $('#edit_checkbox_qty_3kg').prop('checked', true);
        }else{
          $('#edit_checkbox_qty_3kg').prop('checked', false);
        }
        if(data.qty_5kg == 1 || data.qty_5kg == '1'){
          $('#edit_checkbox_qty_5kg').prop('checked', true);
        }else{
          $('#edit_checkbox_qty_5kg').prop('checked', false);
        }
        if(data.qty_lainnya == 1 || data.qty_lainnya == '1'){
          $('#edit_checkbox_qty_lainnya').prop('checked', true);
        }else{
          $('#edit_checkbox_qty_lainnya').prop('checked', false);
        }
        if(data.ambil_sendiri == 1 || data.ambil_sendiri == '1'){
          $('#edit_checkbox_kirim_sendiri').prop('checked', true);
        }else{
          $('#edit_checkbox_kirim').prop('checked', false);
        }
        if(data.ekspedisi == 1 || data.ekspedisi == '1'){
          $('#edit_checkbox_ekspedisi').prop('checked', true);
        }else{
          $('#edit_checkbox_ekspedisi').prop('checked', false);
        }
        if(data.status > 3){
          $('#edit_respon').html(data.respon_customers);
          $('#div_respon_customers').show();
        }else{
          $('#div_respon_customers').hide();
        }
        $('#edit_batu_lainnya').val(data.nama_batu_lainnya);
        $('#edit_mesh_lainnya').val(data.nama_mesh_lainnya);
        $('#edit_qty_lainnya').val(data.nama_qty_lainnya);
        $('#edit_nama_ekspedisi').val(data.nama_ekspedisi);
        $('#edit_nomor_resi').val(data.nomor_resi);
        if(data.lampiran_sales == null){
          $('#lihat_lampiran').html('Tidak Ada');
          $('#lihat_lampiran').addClass('disabled');
          $('#lihat_lampiran').attr('href', '#');
        }else{
          $('#lihat_lampiran').html('Lihat');
          $('#lihat_lampiran').removeClass('disabled');
          $('#lihat_lampiran').attr('href', '../data_file/' + data.lampiran_sales);
        }
      })
    });

    $('body').on('click', '#delete-data', function () {
      var nomor = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('permintaan_sample/delete') }}",
          data: { 'nomor' : nomor },
          success: function (data) {
            var oTable = $('#data_permintaan_sample_table').dataTable();
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
        customers: {
          required: function(element) {
            return $("#new_customers").val() == '';
          }
        },
        new_customers: {
          required: function(element) {
            return $("#customers").val() == '';
          }
        },
        city: {
          required: function(element) {
            return $("#customers").val() == '';
          }
        },
        tanggal: {
          required: true,
        },
        jenis_barang: {
          required: true,
        },
        jenis_mesh: {
          required: true,
        },
        quantity: {
          required: true,
        },
        pengiriman: {
          required: true,
        },
      },
      messages: {
        customers: {
          required: "Customers Harus Diisi",
        },
        new_customers: {
          required: "Customer Baru Harus Diisi",
        },
        city: {
          required: "Kota Harus Diisi",
        },
        tanggal: {
          required: "Tanggal Harus Diisi",
        },
        jenis_barang: {
          required: "Jenis Barang Harus Diisi",
        },
        jenis_mesh: {
          required: "Jenis Mesh Harus Diisi",
        },
        quantity: {
          required: "Quantity Harus Diisi",
        },
        pengiriman: {
          required: "Pengiriman Harus Diisi",
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
          url:"{{ url('permintaan_sample/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            $('#modal_input_data').modal('hide');
            $("#modal_input_data").trigger('click');
            var oTable = $('#data_permintaan_sample_table').dataTable();
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
        edit_customers: {
          required: true,
        },
        edit_tanggal: {
          required: true,
        },
        edit_jenis_barang: {
          required: true,
        },
        edit_jenis_mesh: {
          required: true,
        },
        edit_quantity: {
          required: true,
        },
        edit_pengiriman: {
          required: true,
        },
        edit_lampiran: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        edit_customers: {
          required: "Customers Harus Diisi",
        },
        edit_tanggal: {
          required: "Tanggal Harus Diisi",
        },
        edit_jenis_barang: {
          required: "Jenis Barang Harus Diisi",
        },
        edit_jenis_mesh: {
          required: "Jenis Mesh Harus Diisi",
        },
        edit_quantity: {
          required: "Quantity Harus Diisi",
        },
        edit_pengiriman: {
          required: "Pengiriman Harus Diisi",
        },
        edit_lampiran: {
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
          url:"{{ url('permintaan_sample/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_form').trigger("reset");
            $('#modal_edit_data').modal('hide');
            $("#modal_edit_data").trigger('click');
            var oTable = $('#data_permintaan_sample_table').dataTable();
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

    $('#update_form').validate({
      rules: {
        respon: {
          required: true,
        },
        lampiran: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        respon: {
          required: "Respon Customers Harus Diisi",
        },
        lampiran: {
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
        var myform = document.getElementById("update_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('permintaan_sample/update/respon') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#update_form').trigger("reset");
            $('#modal_update_data').modal('hide');
            $("#modal_update_data").trigger('click');
            var oTable = $('#data_permintaan_sample_table').dataTable();
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
  $(document).ready(function(){
    $('.customers').select2({
      dropdownParent: $('#modal_input_data .modal-content'),
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
      dropdownParent: $('#modal_edit_data .modal-content'),
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

    $('.city').select2({
      dropdownParent: $('#modal_input_data .modal-content'),
      placeholder: 'Pilih Kota',
      allowClear: true,
      ajax: {
        url: '/dropdown_city',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.name,
                id: item.id_kota
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

  $(".city").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Kota / Kabupaten...");
  });
  $(".city").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>

@endsection