@extends('layouts.app_admin')

@section('title')
<title>CUSTOMERS FOLLOW UP - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css">
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
    .schedule-btn {
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
          <h1 class="m-0 text-dark">Customers Follow Up</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Customers Follow Up</li>
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
            <div class="col-2">
              <button type="button" name="make_schedule_offline" id="make_schedule_offline" class="btn btn-block btn-primary schedule-btn" data-toggle="modal" data-target="#modal_make_schedule_offline">Input Offline Visit</button>
            </div>
            <div class="col-2">
              <button type="button" name="make_schedule_online" id="make_schedule_online" class="btn btn-block btn-primary schedule-btn" data-toggle="modal" data-target="#modal_make_schedule_online">Input Online Visit</button>
            </div>
            <div class="col-2">
              <button type="button" name="upload_excel" id="upload_excel" class="btn btn-block btn-primary schedule-btn" data-toggle="modal" data-target="#modal_upload_excel">Upload Excel</button>
            </div>
            <div class="col-2">
              <a class="btn btn-block btn-primary schedule-btn" href="{{ url('customer_visit/lihat_calendar') }}">Lihat Kalender</a>
            </div>
            <div class="col-2">
              <a class="btn btn-block btn-primary schedule-btn" href="#" id="btn-save-excel">Laporan Bulanan</a>
            </div>
          </div>
          <div class="row" style="margin-top: 20px;">
            <div class="col-12">
              <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#jadwal_followup" role="tab" aria-controls="custom-content-below-home" aria-selected="false">Jadwal Follow Up</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#status_done" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Done</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#status_fail" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Fail</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div id="dialog-confirm"></div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="jadwal_followup" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="jadwal_follow_up_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Jadwal</th>
                    <th>Customers</th>
                    <th>Perihal</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="status_done" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="jadwal_done_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Jadwal</th>
                    <th>Customers</th>
                    <th>Perihal</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="status_fail" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="jadwal_fail_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Jadwal</th>
                    <th>Customers</th>
                    <th>Perihal</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal_make_schedule_offline">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Buat Jadwal Kunjungan Offline</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label>Tanggal</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="tanggal_jadwal" id="tanggal_jadwal" autocomplete="off" placeholder="Tanggal">
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="perihal">Perihal</label>
                <select id="perihal" name="perihal" class="form-control">
                  <option value="" selected>Perihal</option>
                  <option value="Customer Visit">Customer Visit</option>
                  <option value="Customer Complaint">Customer Complaint</option>
                  <option value="Customer Info">Customer Info</option>
                  <option value="Cek Kompetitor">Cek Kompetitor</option>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="penawaran_yes">Penawaran?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="penawaran_yes" name="input_penawaran" value="yes">
                  <label for="penawaran_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="penawaran_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="penawaran_no" name="input_penawaran" value="no" checked>
                  <label for="penawaran_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="sample_yes">Sample?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="sample_yes" name="input_sample" value="yes">
                  <label for="sample_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="sample_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="sample_no" name="input_sample" value="no" checked>
                  <label for="sample_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="order_yes">Order?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="order_yes" name="input_order" value="yes">
                  <label for="order_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="order_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="order_no" name="input_order" value="no" checked>
                  <label for="order_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4" id="div_catatan_penawaran_empty"></div>
            <div class="col-4" id="div_catatan_penawaran" style="display: none;">
              <div class="form-group">
                <label for="catatan_penawaran">Catatan Penawaran</label>
                <input type="text" class="form-control" name="catatan_penawaran" id="catatan_penawaran" placeholder="Catatan Penawaran">
              </div>
            </div>
            <div class="col-4" id="div_catatan_sample_empty"></div>
            <div class="col-4" id="div_catatan_sample" style="display: none;">
              <div class="form-group">
                <label for="catatan_sample">Catatan Sample</label>
                <input type="text" class="form-control" name="catatan_sample" id="catatan_sample" placeholder="Catatan Sample">
              </div>
            </div>
            <div class="col-4" id="div_catatan_order_empty"></div>
            <div class="col-4" id="div_catatan_order" style="display: none;">
              <div class="form-group">
                <label for="catatan_order">Catatan Order</label>
                <input type="text" class="form-control" name="catatan_order" id="catatan_order" placeholder="Catatan Order">
              </div>
            </div>
          </div>
          <form method="post" class="input_schedule_offline_form" id="input_schedule_offline_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Data Perusahaan dan Catatan</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="form-group">
                          <label for="company">Perusahaan</label>
                          <select id="company" name="company" class="form-control" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-5">
                        <div class="form-group">
                          <label>Catatan Perusahaan</label>
                          <textarea class="form-control" rows="3" name="catatan_perusahaan" id="catatan_perusahaan" placeholder="Catatan Perusahaan"></textarea>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-primary" type="submit" name="btn-add-catatan" id="btn-add-catatan" value="Add"/>
                        </div>
                      </div>
                    </div>
                    <table id="input_catatan_perusahaan_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Perusahaan</th>
                          <th>Catatan</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="row"> 
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h5>Entry Data Customer</h5>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <div class="label-flex">
                          <label>&nbsp</label>
                        </div>
                        <button type="button" class="btn btn-primary" id="btn_new_customer" style="width: 100%;">New Cust</button>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <div class="label-flex">
                          <label>&nbsp</label>
                        </div>
                        <button type="button" class="btn btn-primary" id="btn_exist_customer" style="width: 100%;">Exist Cust</button>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="select_customers" style="display: none;">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="customers">Customer</label>
                        <select id="customers" name="customers" class="form-control select2 customer" style="width: 100%;">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div id="new_customers" style="display: none;">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="new_nama_customers">Nama Customer</label>
                          <input type="text" class="form-control" name="new_nama_customers" id="new_nama_customers" placeholder="Nama Customer">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="new_city">Kota</label>
                          <select id="new_city" name="new_city" class="form-control select2 city" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="new_alamat">Alamat</label>
                          <textarea class="form-control" rows="2" name="new_alamat" id="new_alamat" placeholder="Alamat"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="new_telepon">Telepon</label>
                          <input type="text" class="form-control" name="new_telepon" id="new_telepon" placeholder="Telepon">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="new_pic">PIC</label>
                          <input type="text" class="form-control" name="new_pic" id="new_pic" placeholder="PIC">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="new_telepon_pic">Telepon PIC</label>
                          <input type="text" class="form-control" name="new_telepon_pic" id="new_telepon_pic" placeholder="Telepon PIC">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="new_bidang_usaha">Bidang Usaha</label>
                          <input type="text" class="form-control" name="new_bidang_usaha" id="new_bidang_usaha" placeholder="Bidang Usaha">
                        </div>
                      </div>
                    </div>
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

  <div class="modal fade" id="modal_make_schedule_online">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Buat Jadwal Kunjungan Online</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label>Tanggal</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="tanggal_jadwal_online" id="tanggal_jadwal_online" autocomplete="off" placeholder="Tanggal">
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="perihal_online">Perihal</label>
                <select id="perihal_online" name="perihal_online" class="form-control">
                  <option value="" selected>Perihal</option>
                  <option value="Customer Visit">Customer Visit</option>
                  <option value="Customer Complaint">Customer Complaint</option>
                  <option value="Customer Info">Customer Info</option>
                  <option value="Cek Kompetitor">Cek Kompetitor</option>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" rows="3" name="keterangan_online" id="keterangan_online" placeholder="Keterangan"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="penawaran_online_yes">Penawaran?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="penawaran_online_yes" name="input_penawaran_online" value="yes">
                  <label for="penawaran_online_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="penawaran_online_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="penawaran_online_no" name="input_penawaran_online" value="no" checked>
                  <label for="penawaran_online_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="sample_online_yes">Sample?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="sample_online_yes" name="input_sample_online" value="yes">
                  <label for="sample_online_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="sample_online_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="sample_online_no" name="input_sample_online" value="no" checked>
                  <label for="sample_online_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="order_online_yes">Order?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="order_online_yes" name="input_order_online" value="yes">
                  <label for="order_online_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="order_online_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="order_online_no" name="input_order_online" value="no" checked>
                  <label for="order_online_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4" id="div_catatan_penawaran_online_empty"></div>
            <div class="col-4" id="div_catatan_penawaran_online" style="display: none;">
              <div class="form-group">
                <label for="catatan_penawaran_online">Catatan Penawaran</label>
                <input type="text" class="form-control" name="catatan_penawaran_online" id="catatan_penawaran_online" placeholder="Catatan Penawaran">
              </div>
            </div>
            <div class="col-4" id="div_catatan_sample_online_empty"></div>
            <div class="col-4" id="div_catatan_sample_online" style="display: none;">
              <div class="form-group">
                <label for="catatan_sample_online">Catatan Sample</label>
                <input type="text" class="form-control" name="catatan_sample_online" id="catatan_sample_online" placeholder="Catatan Sample">
              </div>
            </div>
            <div class="col-4" id="div_catatan_order_online_empty"></div>
            <div class="col-4" id="div_catatan_order_online" style="display: none;">
              <div class="form-group">
                <label for="catatan_order_online">Catatan Order</label>
                <input type="text" class="form-control" name="catatan_order_online" id="catatan_order_online" placeholder="Catatan Order">
              </div>
            </div>
          </div>
          <form method="post" class="input_schedule_online_form" id="input_schedule_online_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Data Perusahaan dan Catatan</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="form-group">
                          <label for="company_online">Perusahaan</label>
                          <select id="company_online" name="company_online" class="form-control" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-5">
                        <div class="form-group">
                          <label>Catatan Perusahaan</label>
                          <textarea class="form-control" rows="3" name="catatan_perusahaan_online" id="catatan_perusahaan_online" placeholder="Catatan Perusahaan"></textarea>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-primary" type="submit" name="btn-add-catatan-online" id="btn-add-catatan-online" value="Add"/>
                        </div>
                      </div>
                    </div>
                    <table id="input_catatan_perusahaan_online_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Perusahaan</th>
                          <th>Catatan</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="row"> 
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h5>Entry Data Customer</h5>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <div class="label-flex">
                          <label>&nbsp</label>
                        </div>
                        <button type="button" class="btn btn-primary" id="btn_new_customer_online" style="width: 100%;">New Cust</button>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <div class="label-flex">
                          <label>&nbsp</label>
                        </div>
                        <button type="button" class="btn btn-primary" id="btn_exist_customer_online" style="width: 100%;">Exist Cust</button>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="select_customers_online" style="display: none;">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="customers_online">Customer</label>
                        <select id="customers_online" name="customers_online" class="form-control select2 customer_online" style="width: 100%;">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div id="new_customers" style="display: none;">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="new_nama_customers_online">Nama Customer</label>
                          <input type="text" class="form-control" name="new_nama_customers_online" id="new_nama_customers_online" placeholder="Nama Customer">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="new_city_online">Kota</label>
                          <select id="new_city_online" name="new_city_online" class="form-control select2 city_online" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="new_alamat_online">Alamat</label>
                          <textarea class="form-control" rows="2" name="new_alamat_online" id="new_alamat_online" placeholder="Alamat"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="new_telepon_online">Telepon</label>
                          <input type="text" class="form-control" name="new_telepon_online" id="new_telepon_online" placeholder="Telepon">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="new_pic_online">PIC</label>
                          <input type="text" class="form-control" name="new_pic_online" id="new_pic_online" placeholder="PIC">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="new_telepon_pic_online">Telepon PIC</label>
                          <input type="text" class="form-control" name="new_telepon_pic_online" id="new_telepon_pic_online" placeholder="Telepon PIC">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="new_bidang_usaha_online">Bidang Usaha</label>
                          <input type="text" class="form-control" name="new_bidang_usaha_online" id="new_bidang_usaha_online" placeholder="Bidang Usaha">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="btn-save-input-online">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_view_schedule">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title_lihat_detail_schedule"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12">
            <table class="table table-bordered table-hover">
             <thead>
              <tr>
               <th>ID Schedule</th>
               <td id="td_id_schedule"></td>
               <th>Tanggal Schedule</th>
               <td id="td_tanggal_schedule"></td>
             </tr>
             <tr>
               <th>Customers</th>
               <td id="td_customers"></td>
               <th>Perihal</th>
               <td id="td_perihal"></td>
             </tr>
             <tr>
               <th>Offline?</th>
               <td id="td_offline"></td>
               <th>Keterangan</th>
               <td id="td_keterangan"></td>
             </tr>
             <tr>
               <th>Penawaran</th>
               <td id="td_penawaran"></td>
               <th>Catatan Penawaran</th>
               <td id="td_catatan_penawaran"></td>
             </tr>
             <tr>
               <th>Sample</th>
               <td id="td_sample"></td>
               <th>Catatan Sample</th>
               <td id="td_catatan_sample"></td>
             </tr>
             <tr>
               <th>Order</th>
               <td id="td_order"></td>
               <th>Catatan Order</th>
               <td id="td_catatan_order"></td>
             </tr>
             <tr>
               <th>Route Length</th>
               <td id="td_route_length"></td>
               <th>BBM</th>
               <td id="td_bbm"></td>
             </tr>
             <tr>
               <th>Biaya Perjalanan</th>
               <td id="td_biaya_perjalanan"></td>
               <th>Alasan Suspend</th>
               <td id="td_alasan_suspend"></td>
             </tr>
             <tr>
               <th>Alasan Fail</th>
               <td id="td_alasan_fail"></td>
               <th>Tanggal Done</th>
               <td id="td_tanggal_done"></td>
             </tr>
             <tr>
               <th>Alasan Done</th>
               <td id="td_alasan_done"></td>
               <th>Status</th>
               <td id="td_status"></td>
             </tr>
           </thead>
         </table>
         <h5>Data Catatan Perusahaan : </h5>
         <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
          <thead>
            <tr>
              <th style="vertical-align : middle; text-align: center;">Company</th>
              <th style="vertical-align : middle; text-align: center;">Catatan Perusahaan</th>                  
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

  <div class="modal fade" id="modal_edit_schedule">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Jadwal Kunjungan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label>Tanggal</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="edit_tanggal_jadwal" id="edit_tanggal_jadwal" autocomplete="off" placeholder="Tanggal">
                  <input type="hidden" class="form-control" name="edit_id_schedule" id="edit_id_schedule">
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="edit_perihal">Perihal</label>
                <select id="edit_perihal" name="edit_perihal" class="form-control">
                  <option value="" selected>Perihal</option>
                  <option value="Customer Visit">Customer Visit</option>
                  <option value="Customer Complaint">Customer Complaint</option>
                  <option value="Customer Info">Customer Info</option>
                  <option value="Cek Kompetitor">Cek Kompetitor</option>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="edit_offline">Offline ?</label>
                <select id="edit_offline" name="edit_offline" class="form-control">
                  <option value="" selected>Pilih</option>
                  <option value="0">Offline</option>
                  <option value="1">Online</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" rows="3" name="edit_keterangan" id="edit_keterangan" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="col-6" id="div-edit-alasan-suspend" style="display: none;">
              <div class="form-group">
                <label>Alasan Suspend</label>
                <textarea class="form-control" rows="3" name="edit_alasan_suspend" id="edit_alasan_suspend" placeholder="Alasan Suspend"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="edit_penawaran_yes">Penawaran?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="edit_penawaran_yes" name="edit_penawaran" value="yes">
                  <label for="edit_penawaran_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="edit_penawaran_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="edit_penawaran_no" name="edit_penawaran" value="no" checked>
                  <label for="edit_penawaran_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="edit_sample_yes">Sample?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="edit_sample_yes" name="edit_sample" value="yes">
                  <label for="edit_sample_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="edit_sample_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="edit_sample_no" name="edit_sample" value="no" checked>
                  <label for="edit_sample_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="edit_order_yes">Order?</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="edit_order_yes" name="edit_order" value="yes">
                  <label for="edit_order_yes" class="custom-control-label">Ya</label>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <div class="label-flex">
                  <label for="edit_order_no">&nbsp</label>
                </div>
                <div class="custom-control custom-radio" style="margin-top: 5px;">
                  <input class="form-control custom-control-input" type="radio" id="edit_order_no" name="edit_order" value="no" checked>
                  <label for="edit_order_no" class="custom-control-label">Tidak</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4" id="div_edit_catatan_penawaran_empty"></div>
            <div class="col-4" id="div_edit_catatan_penawaran" style="display: none;">
              <div class="form-group">
                <label for="edit_catatan_penawaran">Catatan Penawaran</label>
                <input type="text" class="form-control" name="edit_catatan_penawaran" id="edit_catatan_penawaran" placeholder="Catatan Penawaran">
              </div>
            </div>
            <div class="col-4" id="div_edit_catatan_sample_empty"></div>
            <div class="col-4" id="div_edit_catatan_sample" style="display: none;">
              <div class="form-group">
                <label for="edit_catatan_sample">Catatan Sample</label>
                <input type="text" class="form-control" name="edit_catatan_sample" id="edit_catatan_sample" placeholder="Catatan Sample">
              </div>
            </div>
            <div class="col-4" id="div_edit_catatan_order_empty"></div>
            <div class="col-4" id="div_edit_catatan_order" style="display: none;">
              <div class="form-group">
                <label for="edit_catatan_order">Catatan Order</label>
                <input type="text" class="form-control" name="edit_catatan_order" id="edit_catatan_order" placeholder="Catatan Order">
              </div>
            </div>
          </div>
          <form method="post" class="edit_schedule_form" id="edit_schedule_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Data Perusahaan dan Catatan</h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="form-group">
                          <label for="edit_company">Perusahaan</label>
                          <select id="edit_company" name="edit_company" class="form-control" style="width: 100%;">
                          </select>
                          <input type="hidden" class="form-control" name="edit_id_schedule_det" id="edit_id_schedule_det">
                        </div>
                      </div>
                      <div class="col-5">
                        <div class="form-group">
                          <label>Catatan Perusahaan</label>
                          <textarea class="form-control" rows="3" name="edit_catatan_perusahaan" id="edit_catatan_perusahaan" placeholder="Catatan Perusahaan"></textarea>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-primary" type="submit" name="btn-edit-catatan" id="btn-edit-catatan" value="Add"/>
                        </div>
                      </div>
                    </div>
                    <table id="edit_catatan_perusahaan_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Perusahaan</th>
                          <th>Catatan</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="row"> 
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h5>Entry Data Customer</h5>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="edit_customers">Customer</label>
                        <select id="edit_customers" name="edit_customers" class="form-control select2 edit_customer" style="width: 100%;">
                        </select>
                      </div>
                    </div>
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

  <div class="modal fade" id="modal_upload_excel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Excel</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" class="upload-form" id="upload-form" action="{{ url('/sales/customer_visit/upload_excel') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_customer_visit.xlsx')}}" target="_blank">here</a>.</span>
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

  <div class="modal fade" id="modal_proses_schedule">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Proses Schedule</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" class="proses_schedule_form" id="proses_schedule_form" action="javascript:void(0)" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="proses_status">Status</label>
                  <select id="proses_status" name="proses_status" class="form-control">
                    <option value="" selected>Status</option>
                    <option value="2">Suspend</option>
                    <option value="3">Success</option>
                    <option value="4">Fail</option>
                  </select>
                  <input type="hidden" class="form-control" name="proses_id_schedule" id="proses_id_schedule">
                </div>
              </div>
            </div>
            <div class="row" id="div-tanggal-jadwal" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label>Tanggal Reschedule</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="proses_tanggal_jadwal" id="proses_tanggal_jadwal" autocomplete="off" placeholder="Tanggal Reschedule">
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div-tanggal-done" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label>Tanggal Done</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="proses_tanggal_done" id="proses_tanggal_done" autocomplete="off" placeholder="Tanggal Done">
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div-alasan-suspend" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label>Alasan Suspend</label>
                  <textarea class="form-control" rows="3" name="proses_alasan_suspend" id="proses_alasan_suspend" placeholder="Alasan Suspend"></textarea>
                </div>
              </div>
            </div>
            <div class="row" id="div-alasan-success" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label>Alasan Success</label>
                  <textarea class="form-control" rows="3" name="proses_alasan_success" id="proses_alasan_success" placeholder="Alasan Success"></textarea>
                </div>
              </div>
            </div>
            <div class="row" id="div-alasan-fail" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label>Alasan Fail</label>
                  <textarea class="form-control" rows="3" name="proses_alasan_fail" id="proses_alasan_fail" placeholder="Alasan Fail"></textarea>
                </div>
              </div>
            </div>
            <div id="div-cost-route" style="display: none;">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="route_length">Route Length</label>
                    <input class="form-control" type="text" name="route_length" id="route_length" placeholder="Route Length" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="bbm">BBM</label>
                    <input class="form-control" type="text" name="bbm" id="bbm" placeholder="BBM" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label for="biaya_perjalanan">Biaya Perjalanan</label>
                    <input class="form-control" type="text" name="biaya_perjalanan" id="biaya_perjalanan" placeholder="Biaya Perjalanan" />
                  </div>
                </div>
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
  <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
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
  <script src="https://cdn.datatables.net/rowreorder/1.2.0/js/dataTables.rowReorder.min.js"></script>

<script>
  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>

<script type="text/javascript">
  $.fn.modal.Constructor.prototype.enforceFocus = function () {};

  // $.noConflict();

  $(function () {
    $('#filter_tanggal').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $('.select2').select2();

    var dt = new Date();

    $('#tanggal_jadwal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#tanggal_jadwal_online').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal_jadwal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#proses_tanggal_jadwal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#proses_tanggal_done').flatpickr({
      allowInput: true,
      disableMobile: true
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var target = $('.nav-tabs a.nav-link.active').attr("href");

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

    var table = $('#jadwal_follow_up_table').DataTable({
         processing: true,
         serverSide: true,
         responsive: {
          details: {
            type: 'column'
          }
        },
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/customer_visit/jadwal/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
            if(data.no_status == 1){
              $(row).attr('style', 'background-color:#fffd94');
            }else if(data.no_status == 2){
              $(row).attr('style', 'background-color:#fac870');
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
         data:'DT_RowIndex',
         name:'DT_RowIndex',
         className:'dt-center',
         width:'5%'
       },
       {
         data:'jadwal',
         name:'jadwal',
         className:'dt-center',
         width:'12%'
       },
       {
         data:'customers',
         name:'customers',
         className:'dt-center'
       },
       {
         data:'perihal',
         name:'perihal',
         className:'dt-center',
         width:'17%'
       },
       {
         data:'status',
         name:'status',
         className:'dt-center',
         width:'10%'
       },
       {
         data:'action',
         name:'action',
         className:'dt-center',
         width:'15%'
       }
       ]
     });

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#jadwal_followup'){
        $('#jadwal_follow_up_table').DataTable().destroy();
        load_data_jadwal_followup();
      }else if(target == '#status_done'){
        $('#jadwal_done_table').DataTable().destroy();
        load_data_jadwal_done();
      }else if(target == '#status_fail'){
        $('#jadwal_fail_table').DataTable().destroy();
        load_data_jadwal_fail();
      }
    });

    function load_data_jadwal_followup(from_date = '', to_date = '')
     {
      table = $('#jadwal_follow_up_table').DataTable({
         processing: true,
         serverSide: true,
         responsive: {
          details: {
            type: 'column'
          }
        },
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/customer_visit/jadwal/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        createdRow: function (row, data, dataIndex) {
            if(data.no_status == 1){
              $(row).attr('style', 'background-color:#fffd94');
            }else if(data.no_status == 2){
              $(row).attr('style', 'background-color:#fac870');
            }
        },
        order: [[5,'desc'],[2,'asc']],
        columns: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
         data:'DT_RowIndex',
         name:'DT_RowIndex',
         className:'dt-center',
         width:'5%'
        },
        {
         data:'jadwal',
         name:'jadwal',
         className:'dt-center',
         width:'12%'
        },
        {
         data:'customers',
         name:'customers',
         className:'dt-center',
        },
        {
         data:'perihal',
         name:'perihal',
         className:'dt-center',
         width:'17%'
        },
        {
         data:'status',
         name:'status',
         className:'dt-center',
         width:'10%'
        },
        {
         data:'action',
         name:'action',
         className:'dt-center',
         width:'15%'
        }
        ]
      });
     }

    function load_data_jadwal_done(from_date = '', to_date = '')
     {
      table = $('#jadwal_done_table').DataTable({
         processing: true,
         serverSide: true,
         responsive: {
          details: {
            type: 'column'
          }
        },
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/customer_visit/done/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[2,'asc']],
        columns: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
         data:'DT_RowIndex',
         name:'DT_RowIndex',
         className:'dt-center',
         width:'5%'
        },
        {
         data:'jadwal',
         name:'jadwal',
         className:'dt-center',
         width:'12%'
        },
        {
         data:'customers',
         name:'customers',
         className:'dt-center',
        },
        {
         data:'perihal',
         name:'perihal',
         className:'dt-center',
         width:'17%'
        },
        {
         data:'status',
         name:'status',
         className:'dt-center',
         width:'10%'
        },
        {
         data:'action',
         name:'action',
         className:'dt-center',
         width:'15%'
        }
        ]
      });
     }

    function load_data_jadwal_fail(from_date = '', to_date = '')
     {
      table = $('#jadwal_fail_table').DataTable({
         processing: true,
         serverSide: true,
         responsive: {
          details: {
            type: 'column'
          }
        },
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/customer_visit/fail/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[2,'asc']],
        columns: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
         data:'DT_RowIndex',
         name:'DT_RowIndex',
         className:'dt-center',
         width:'5%'
        },
        {
         data:'jadwal',
         name:'jadwal',
         className:'dt-center',
         width:'12%'
        },
        {
         data:'customers',
         name:'customers',
         className:'dt-center',
        },
        {
         data:'perihal',
         name:'perihal',
         className:'dt-center',
         width:'17%'
        },
        {
         data:'status',
         name:'status',
         className:'dt-center',
         width:'10%'
        },
        {
         data:'action',
         name:'action',
         className:'dt-center',
         width:'15%'
        }
        ]
      });
     }

    function load_data_catatan_perusahaan()
    {
      $('#input_catatan_perusahaan_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Masukkan Data Perusahaan dan Catatan Perusahaan"
        },
        ajax: {
          url:'{{ url("sales/customer_visit/catatan_perusahaan/table") }}'
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'company',
         name:'company'
       },
       {
         data:'catatan_perusahaan',
         name:'catatan_perusahaan'
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       },
       ]
     });
    }

    function load_data_catatan_perusahaan_online()
    {
      $('#input_catatan_perusahaan_online_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Masukkan Data Perusahaan dan Catatan Perusahaan"
        },
        ajax: {
          url:'{{ url("sales/customer_visit/catatan_perusahaan/table") }}'
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'company',
         name:'company'
       },
       {
         data:'catatan_perusahaan',
         name:'catatan_perusahaan'
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       },
       ]
     });
    }

    function load_data_edit_catatan_perusahaan(id_schedule = '')
    {
      $('#edit_catatan_perusahaan_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Masukkan Data Perusahaan dan Catatan Perusahaan"
        },
        ajax: {
          url:'{{ url("sales/customer_visit/catatan_perusahaan/edit/table") }}',
          data:{id_schedule:id_schedule}
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'company',
         name:'company'
       },
       {
         data:'catatan_perusahaan',
         name:'catatan_perusahaan'
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       },
       ]
     });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if(target == '#jadwal_followup'){
          $('#jadwal_follow_up_table').DataTable().destroy();
          load_data_jadwal_followup(from_date, to_date);
        }else if(target == '#status_done'){
          $('#jadwal_done_table').DataTable().destroy();
          load_data_jadwal_done(from_date, to_date);
        }else if(target == '#status_fail'){
          $('#jadwal_fail_table').DataTable().destroy();
          load_data_jadwal_fail(from_date, to_date);
        }
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(target == '#jadwal_followup'){
        $('#jadwal_follow_up_table').DataTable().destroy();
        load_data_jadwal_followup();
      }else if(target == '#status_done'){
        $('#jadwal_done_table').DataTable().destroy();
        load_data_jadwal_done();
      }else if(target == '#status_fail'){
        $('#jadwal_fail_table').DataTable().destroy();
        load_data_jadwal_fail();
      }
    });

    $('body').on('click', '#make_schedule_offline', function () {
      $('#input_catatan_perusahaan_table').DataTable().destroy();
      load_data_catatan_perusahaan();

      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      $('#btn_new_customer').click(function(){
        $('#new_customers').show();
        $('#select_customers').hide();
      });

      $('#btn_exist_customer').click(function(){
        $('#new_customers').hide();
        $('#select_customers').show();
      });

      $('input:radio[name="input_penawaran"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_catatan_penawaran").show();
          $("#div_catatan_penawaran_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_catatan_penawaran_empty").show();
          $("#div_catatan_penawaran").hide();
        }
      });

      $('input:radio[name="input_sample"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_catatan_sample").show();
          $("#div_catatan_sample_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_catatan_sample_empty").show();
          $("#div_catatan_sample").hide();
        }
      });

      $('input:radio[name="input_order"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_catatan_order").show();
          $("#div_catatan_order_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_catatan_order_empty").show();
          $("#div_catatan_order").hide();
        }
      });
    });

    $('body').on('click', '#make_schedule_online', function () {
      $('#input_catatan_perusahaan_online_table').DataTable().destroy();
      load_data_catatan_perusahaan_online();

      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#company_online').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company_online').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      $('#btn_new_customer_online').click(function(){
        $('#new_customers_online').show();
        $('#select_customers_online').hide();
      });

      $('#btn_exist_customer_online').click(function(){
        $('#new_customers_online').hide();
        $('#select_customers_online').show();
      });

      $('input:radio[name="input_penawaran_online"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_catatan_penawaran_online").show();
          $("#div_catatan_penawaran_online_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_catatan_penawaran_online_empty").show();
          $("#div_catatan_penawaran_online").hide();
        }
      });

      $('input:radio[name="input_sample_online"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_catatan_sample_online").show();
          $("#div_catatan_sample_online_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_catatan_sample_online_empty").show();
          $("#div_catatan_sample_online").hide();
        }
      });

      $('input:radio[name="input_order_online"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_catatan_order_online").show();
          $("#div_catatan_order_online_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_catatan_order_online_empty").show();
          $("#div_catatan_order_online").hide();
        }
      });
    });

    $('body').on('click', '#btn_proses_schedule', function () {
      var nomor = $(this).data("id");
      $('#proses_id_schedule').val(nomor);

      $("#proses_status").change(function() {
        if($(this).val() == 2) {
          $('#div-tanggal-jadwal').show();
          $('#div-tanggal-done').hide();
          $('#div-alasan-suspend').show();
          $('#div-alasan-success').hide();
          $('#div-alasan-fail').hide();
        }else if($(this).val() == 3) {
          $('#div-tanggal-jadwal').hide();
          $('#div-tanggal-done').show();
          $('#div-alasan-suspend').hide();
          $('#div-alasan-success').show();
          $('#div-alasan-fail').hide();
        }else if($(this).val() == 4) {
          $('#div-tanggal-jadwal').hide();
          $('#div-tanggal-done').hide();
          $('#div-alasan-suspend').hide();
          $('#div-alasan-success').hide();
          $('#div-alasan-fail').show();
        }else{
          $('#div-tanggal-jadwal').hide();
          $('#div-tanggal-done').hide();
          $('#div-alasan-suspend').hide();
          $('#div-alasan-success').hide();
          $('#div-alasan-fail').hide();
        }
      });
    });

    $('body').on('click', '#btn_view_schedule', function () {
      var nomor = $(this).data("id");

      document.getElementById("title_lihat_detail_schedule").innerHTML = "Detail Schedule No " + nomor;
      var url = "{{ url('sales/customer_visit/view/id_schedule') }}";
      url = url.replace('id_schedule', enc(nomor.toString()));
      $('#td_id_schedule').html('');
      $('#td_customers').html('');
      $('#td_perihal').html('');
      $('#td_offline').html('');
      $('#td_keterangan').html('');
      $('#td_penawaran').html('');
      $('#td_sample').html('');
      $('#td_order').html('');
      $('#td_catatan_penawaran').html('');
      $('#td_catatan_sample').html('');
      $('#td_catatan_order').html('');
      $('#td_biaya_perjalanan').html('');
      $('#td_route_length').html('');
      $('#td_bbm').html('');
      $('#td_tanggal_schedule').html('');
      $('#td_tanggal_done').html('');
      $('#td_alasan_suspend').html('');
      $('#td_alasan_done').html('');
      $('#td_alasan_fail').html('');
      $('#td_status').html('');
      $.get(url, function (data) {
        $('#td_id_schedule').html(data.id_schedule);
        $('#td_customers').html(data.customers);
        $('#td_perihal').html(data.perihal);
        if(data.offline == 1){
          $('#td_offline').html('Ya');
        }else{
          $('#td_offline').html('Tidak');
        }
        if(data.keterangan == null){
          $('#td_keterangan').html('----');
        }else{
          $('#td_keterangan').html(data.keterangan);
        }
        $('#td_tanggal_schedule').html(data.tanggal_schedule);
        if(data.penawaran_yes == 'yes'){
          $('#td_penawaran').html('Ya');
        }else{
          $('#td_penawaran').html('Tidak');
        }
        if(data.sample_yes == 'yes'){
          $('#td_sample').html('Ya');
        }else{
          $('#td_sample').html('Tidak');
        }
        if(data.order_yes == 'yes'){
          $('#td_order').html('Ya');
        }else{
          $('#td_order').html('Tidak');
        }
        if(data.catatan_penawaran == null){
          $('#td_catatan_penawaran').html('----');
        }else{
          $('#td_catatan_penawaran').html(data.keterangan);
        }
        if(data.catatan_sample == null){
          $('#td_catatan_sample').html('----');
        }else{
          $('#td_catatan_sample').html(data.keterangan);
        }
        if(data.catatan_order == null){
          $('#td_catatan_order').html('----');
        }else{
          $('#td_catatan_order').html(data.keterangan);
        }
        $('#td_biaya_perjalanan').html('Rp ' + data.biaya_perjalanan);
        $('#td_route_length').html(data.route_length + ' Km');
        $('#td_bbm').html(data.bbm + ' Ltr');
        if(data.tanggal_done == null){
          $('#td_tanggal_done').html('----');
        }else{
          $('#td_tanggal_done').html(data.tanggal_done);
        }
        if(data.alasan_suspend == null){
          $('#td_alasan_suspend').html('----');
        }else{
          $('#td_alasan_suspend').html(data.alasan_suspend);
        }
        if(data.alasan_done == null){
          $('#td_alasan_done').html('----');
        }else{
          $('#td_alasan_done').html(data.alasan_done);
        }
        if(data.alasan_fail == null){
          $('#td_alasan_fail').html('----');
        }else{
          $('#td_alasan_fail').html(data.alasan_fail);
        }
        $('#td_status').html(data.status);
      })

      var url_det = "{{ url('sales/customer_visit/view/catatan_perusahaan/id_schedule') }}";
      url_det = url_det.replace('id_schedule', enc(nomor.toString()));
      $("#tbody_view").empty();
      $.get(url_det, function (data) {
        if(data.length == 0){
          $('#tbody_view').append(
            '<tr>'+
            '<td style="vertical-align : middle; text-align: center;" colspan="2">No Data</td>'+
            '</tr>'
          );
        }
        $.each(data, function(k, v) {
          $('#tbody_view').append(
            '<tr>'+
            '<td style="vertical-align : middle; text-align: center;">'+ v.company +'</td>'+
            '<td style="vertical-align : middle; text-align: center;">'+ v.catatan_perusahaan +'</td>'+
            '</tr>'
            );
        });
      })
    });

    $('body').on('click', '#btn_edit_schedule', function () {
      var nomor = $(this).data("id");

      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#edit_company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#edit_company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      $('input:radio[name="edit_penawaran"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_edit_catatan_penawaran").show();
          $("#div_edit_catatan_penawaran_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_edit_catatan_penawaran_empty").show();
          $("#div_edit_catatan_penawaran").hide();
        }
      });

      $('input:radio[name="edit_sample"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_edit_catatan_sample").show();
          $("#div_edit_catatan_sample_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_edit_catatan_sample_empty").show();
          $("#div_edit_catatan_sample").hide();
        }
      });

      $('input:radio[name="edit_order"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_edit_catatan_order").show();
          $("#div_edit_catatan_order_empty").hide();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_edit_catatan_order_empty").show();
          $("#div_edit_catatan_order").hide();
        }
      });

      $('#edit_catatan_perusahaan_table').DataTable().destroy();
      load_data_edit_catatan_perusahaan(nomor);

      var url = "{{ url('sales/customer_visit/view/id_schedule') }}";
      url = url.replace('id_schedule', enc(nomor.toString()));
      $('#edit_id_schedule').val('');
      $('#edit_id_schedule_det').val('');
      $('#edit_company').val('');
      $('#edit_customers').val('');
      $('#edit_perihal').val('');
      $('#edit_offline').val('');
      $('#edit_keterangan').html('');
      $('#edit_alasan_suspend').html('');
      $('#edit_tanggal_jadwal').val('');
      $('#edit_catatan_penawaran').val('');
      $('#edit_catatan_sample').val('');
      $('#edit_catatan_order').val('');
      $('#edit_catatan_perusahaan').val('');
      $("#edit_penawaran_no").prop("checked", true);
      $("#edit_sample_no").prop("checked", true);
      $("#edit_order_no").prop("checked", true);
      $.get(url, function (data) {
        $('#edit_id_schedule').val(nomor);
        $('#edit_id_schedule_det').val(nomor);
        $('#edit_tanggal_jadwal').val(data.tanggal_schedule);
        $('#edit_alasan_suspend').html(data.alasan_suspend);
        $('#edit_company').val(data.company);
        $('#edit_perihal').val(data.perihal);
        $('#edit_offline').val(data.offline);
        $('#edit_keterangan').html(data.keterangan);
        if(data.penawaran_yes == 'yes'){
         $("#edit_penawaran_yes").prop("checked", true).trigger("change");
        }else{
          $("#edit_penawaran_no").prop("checked", true).trigger("change");
        }
        if(data.sample_yes == 'yes'){
         $("#edit_sample_yes").prop("checked", true).trigger("change");
        }else{
          $("#edit_sample_no").prop("checked", true).trigger("change");
        }
        if(data.order_yes == 'yes'){
         $("#edit_order_yes").prop("checked", true).trigger("change");
        }else{
          $("#edit_order_no").prop("checked", true).trigger("change");
        }
        $('#edit_catatan_penawaran').val(data.catatan_penawaran);
        $('#edit_catatan_sample').val(data.catatan_sample);  
        $('#edit_catatan_order').val(data.catatan_order);
        var $newOption = $("<option selected='selected'></option>").val(data.custid).text(data.customers)
        $("#edit_customers").append($newOption).trigger('change');
        if(data.no_status == 1){
          $('#div-edit-alasan-suspend').hide();
        }else{
          $('#div-edit-alasan-suspend').show();
        }
      })
    });

    $('body').on('click', '#delete-catatan', function () {
      var company = $(this).data("id");
      if(confirm("Delete this data?")){
        $.ajax({
          type: "GET",
          url: "{{ url('sales/customer_visit/catatan_perusahaan/delete') }}",
          data: { 'company' : company },
          success: function (data) {
            var oTable = $('#input_catatan_perusahaan_table').dataTable(); 
            oTable.fnDraw(false);
            var oTable = $('#input_catatan_perusahaan_online_table').dataTable(); 
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#delete-edit-catatan', function () {
      var id = $(this).data("id");
      if(confirm("Delete this data?")){
        $.ajax({
          type: "GET",
          url: "{{ url('sales/customer_visit/edit/catatan_perusahaan/delete') }}",
          data: { 'id' : id },
          success: function (data) {
            var oTable = $('#edit_catatan_perusahaan_table').dataTable(); 
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
      var tanggal_jadwal = document.getElementById("tanggal_jadwal").value;
      var perihal = document.getElementById("perihal").value;
      var keterangan = document.getElementById("keterangan").value;
      var input_penawaran = $('input[name="input_penawaran"]:checked').val();
      var input_sample = $('input[name="input_sample"]:checked').val();
      var input_order = $('input[name="input_order"]:checked').val();
      var catatan_penawaran = document.getElementById("catatan_penawaran").value;
      var catatan_sample = document.getElementById("catatan_sample").value;
      var catatan_order = document.getElementById("catatan_order").value;
      var customers = document.getElementById("customers").value;
      var new_nama_customers = document.getElementById("new_nama_customers").value;
      var new_city = document.getElementById("new_city").value;
      var new_alamat = document.getElementById("new_alamat").value;
      var new_telepon = document.getElementById("new_telepon").value;
      var new_pic = document.getElementById("new_pic").value;
      var new_telepon_pic = document.getElementById("new_telepon_pic").value;
      var new_bidang_usaha = document.getElementById("new_bidang_usaha").value;

      var count = $("#input_catatan_perusahaan_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Isi Catatan Perusahaan Terlebih Dahulu");
      }else{
        if((tanggal_jadwal == null || tanggal_jadwal == "")){
          alert("Tanggal Harus Diisi");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('sales/customer_visit/save') }}",
            data: { 'tanggal_jadwal' : tanggal_jadwal, 'perihal' : perihal, 'keterangan' : keterangan, 'input_penawaran' : input_penawaran, 'input_sample' : input_sample, 'input_order' : input_order, 'catatan_penawaran' : catatan_penawaran, 'catatan_sample' : catatan_sample, 'catatan_order' : catatan_order, 'customers' : customers, 'new_nama_customers' : new_nama_customers, 'new_city' : new_city, 'new_alamat' : new_alamat, 'new_telepon' : new_telepon, 'new_pic' : new_pic, 'new_telepon_pic' : new_telepon_pic, 'new_bidang_usaha' : new_bidang_usaha },
            success:function(data){
              alert("Data Successfully Created");
              $('#modal_make_schedule_offline').modal('hide');
              $("#modal_make_schedule_offline").trigger('click');
              $('#tanggal_jadwal').val('');
              $('#perihal').val('').trigger('change');
              $('#keterangan').html('');
              $("#penawaran_no").prop("checked", true);
              $("#sample_no").prop("checked", true);
              $("#order_no").prop("checked", true);
              $('#catatan_penawaran').val('');
              $('#catatan_sample').val('');
              $('#catatan_order').val('');
              $('#customers').val('').trigger('change');
              $('#new_nama_customers').val('');
              $('#new_city').val('').trigger('change');
              $('#new_alamat').html('');
              $('#new_telepon').val('');
              $('#new_pic').val('');
              $('#new_telepon_pic').val('');
              $('#new_bidang_usaha').val('');
              var oTable = $('#jadwal_follow_up_table').dataTable();
              oTable.fnDraw(false);
              var oTable = $('#jadwal_done_table').dataTable();
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

    $('body').on('click', '#btn-save-input-online', function () {
      var tanggal_jadwal = document.getElementById("tanggal_jadwal_online").value;
      var perihal = document.getElementById("perihal_online").value;
      var keterangan = document.getElementById("keterangan_online").value;
      var input_penawaran = $('input[name="input_penawaran_online"]:checked').val();
      var input_sample = $('input[name="input_sample_online"]:checked').val();
      var input_order = $('input[name="input_order_online"]:checked').val();
      var catatan_penawaran = document.getElementById("catatan_penawaran_online").value;
      var catatan_sample = document.getElementById("catatan_sample_online").value;
      var catatan_order = document.getElementById("catatan_order_online").value;
      var customers = document.getElementById("customers_online").value;
      var new_nama_customers = document.getElementById("new_nama_customers_online").value;
      var new_city = document.getElementById("new_city_online").value;
      var new_alamat = document.getElementById("new_alamat_online").value;
      var new_telepon = document.getElementById("new_telepon_online").value;
      var new_pic = document.getElementById("new_pic_online").value;
      var new_telepon_pic = document.getElementById("new_telepon_pic_online").value;
      var new_bidang_usaha = document.getElementById("new_bidang_usaha_online").value;

      var count = $("#input_catatan_perusahaan_online_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Isi Catatan Perusahaan Terlebih Dahulu");
      }else{
        if((tanggal_jadwal == null || tanggal_jadwal == "")){
          alert("Tanggal Harus Diisi");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('sales/customer_visit/online/save') }}",
            data: { 'tanggal_jadwal' : tanggal_jadwal, 'perihal' : perihal, 'keterangan' : keterangan, 'input_penawaran' : input_penawaran, 'input_sample' : input_sample, 'input_order' : input_order, 'catatan_penawaran' : catatan_penawaran, 'catatan_sample' : catatan_sample, 'catatan_order' : catatan_order, 'customers' : customers, 'new_nama_customers' : new_nama_customers, 'new_city' : new_city, 'new_alamat' : new_alamat, 'new_telepon' : new_telepon, 'new_pic' : new_pic, 'new_telepon_pic' : new_telepon_pic, 'new_bidang_usaha' : new_bidang_usaha },
            success:function(data){
              alert("Data Successfully Created");
              $('#modal_make_schedule_online').modal('hide');
              $("#modal_make_schedule_online").trigger('click');
              $('#tanggal_jadwal_online').val('');
              $('#perihal_online').val('').trigger('change');
              $('#keterangan_online').html('');
              $("#penawaran_online_no").prop("checked", true);
              $("#sample_online_no").prop("checked", true);
              $("#order_online_no").prop("checked", true);
              $('#catatan_penawaran_online').val('');
              $('#catatan_sample_online').val('');
              $('#catatan_order_online').val('');
              $('#customers_online').val('').trigger('change');
              $('#new_nama_customers_online').val('');
              $('#new_city_online').val('').trigger('change');
              $('#new_alamat_online').html('');
              $('#new_telepon_online').val('');
              $('#new_pic_online').val('');
              $('#new_telepon_pic_online').val('');
              $('#new_bidang_usaha_online').val('');
              var oTable = $('#jadwal_follow_up_table').dataTable();
              oTable.fnDraw(false);
              var oTable = $('#jadwal_done_table').dataTable();
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

    $('body').on('click', '#btn-save-edit', function () {
      var id_schedule = document.getElementById("edit_id_schedule").value;
      var tanggal_jadwal = document.getElementById("edit_tanggal_jadwal").value;
      var perihal = document.getElementById("edit_perihal").value;
      var offline = document.getElementById("edit_offline").value;
      var keterangan = document.getElementById("edit_keterangan").value;
      var alasan_suspend = document.getElementById("edit_alasan_suspend").value;
      var input_penawaran = $('input[name="edit_penawaran"]:checked').val();
      var input_sample = $('input[name="edit_sample"]:checked').val();
      var input_order = $('input[name="edit_order"]:checked').val();
      var catatan_penawaran = document.getElementById("edit_catatan_penawaran").value;
      var catatan_sample = document.getElementById("edit_catatan_sample").value;
      var catatan_order = document.getElementById("edit_catatan_order").value;
      var customers = document.getElementById("edit_customers").value;

      var count = $("#edit_catatan_perusahaan_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Isi Catatan Perusahaan Terlebih Dahulu");
      }else{
        if((tanggal_jadwal == null || tanggal_jadwal == "")){
          alert("Tanggal Harus Diisi");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('sales/customer_visit/edit') }}",
            data: { 'id_schedule' : id_schedule, 'tanggal_jadwal' : tanggal_jadwal, 'perihal' : perihal, 'offline' : offline, 'keterangan' : keterangan, 'input_penawaran' : input_penawaran, 'input_sample' : input_sample, 'input_order' : input_order, 'catatan_penawaran' : catatan_penawaran, 'catatan_sample' : catatan_sample, 'catatan_order' : catatan_order, 'customers' : customers, 'alasan_suspend' : alasan_suspend },
            success:function(data){
              alert("Data Successfully Updated");
              $('#modal_edit_schedule').modal('hide');
              $("#modal_edit_schedule").trigger('click');
              $('#edit_id_schedule').val('');
              $('#edit_tanggal_jadwal').val('');
              $('#edit_perihal').val('').trigger('change');
              $('#edit_offline').val('');
              $('#edit_keterangan').html('');
              $('#edit_alasan_suspend').html('');
              $("#edit_penawaran_no").prop("checked", true);
              $("#edit_sample_no").prop("checked", true);
              $("#edit_order_no").prop("checked", true);
              $('#edit_catatan_penawaran').val('');
              $('#edit_catatan_sample').val('');
              $('#edit_catatan_order').val('');
              $('#edit_customers').val('').trigger('change');
              var oTable = $('#jadwal_follow_up_table').dataTable();
              oTable.fnDraw(false);
              var oTable = $('#jadwal_done_table').dataTable();
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

    $('#input_schedule_offline_form').validate({
      rules: {
        company: {
          required: true,
        },
        catatan_perusahaan: {
          required: true,
        },
      },
      messages: {
        company: {
          required: "Perusahaan is required",
        },
        catatan_perusahaan: {
          required: "Catatan Perusahaan is required",
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
        var myform = document.getElementById("input_schedule_offline_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/sales/customer_visit/catatan_perusahaan/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_schedule_offline_form').trigger("reset");
            var url = "{{ url('get_company') }}";
            $.get(url, function (data) {
              $('#company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
              $.each(data, function(k, v) {
                $('#company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
              });
            })
            var oTable = $('#input_catatan_perusahaan_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#input_schedule_online_form').validate({
      rules: {
        company_online: {
          required: true,
        },
        catatan_perusahaan_online: {
          required: true,
        },
      },
      messages: {
        company_online: {
          required: "Perusahaan is required",
        },
        catatan_perusahaan_online: {
          required: "Catatan Perusahaan is required",
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
        var myform = document.getElementById("input_schedule_online_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/sales/customer_visit/catatan_perusahaan/online/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_schedule_online_form').trigger("reset");
            var url = "{{ url('get_company') }}";
            $.get(url, function (data) {
              $('#company_online').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
              $.each(data, function(k, v) {
                $('#company_online').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
              });
            })
            var oTable = $('#input_catatan_perusahaan_online_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#edit_schedule_form').validate({
      rules: {
        edit_company: {
          required: true,
        },
        edit_catatan_perusahaan: {
          required: true,
        },
      },
      messages: {
        edit_company: {
          required: "Perusahaan is required",
        },
        edit_catatan_perusahaan: {
          required: "Catatan Perusahaan is required",
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
        var myform = document.getElementById("edit_schedule_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/sales/customer_visit/edit/catatan_perusahaan/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_schedule_form').trigger("reset");
            var url = "{{ url('get_company') }}";
            $.get(url, function (data) {
              $('#edit_company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
              $.each(data, function(k, v) {
                $('#edit_company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
              });
            })
            var oTable = $('#edit_catatan_perusahaan_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#proses_schedule_form').validate({
      rules: {
        proses_status: {
          required: true,
        },
      },
      messages: {
        proses_status: {
          required: "Status is required",
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
        var myform = document.getElementById("proses_schedule_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/sales/customer_visit/proses') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            alert("Data Successfully Updated");
            $('#proses_schedule_form').trigger("reset");
            $('#div-tanggal-jadwal').hide();
            $('#div-tanggal-done').hide();
            $('#div-alasan-suspend').hide();
            $('#div-alasan-success').hide();
            $('#div-alasan-fail').hide();
            $('#modal_proses_schedule').modal('hide');
            $("#modal_proses_schedule").trigger('click');
            var oTable = $('#jadwal_follow_up_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('.customer').select2({
      dropdownParent: $('#modal_make_schedule_offline .modal-content'),
      placeholder: 'Customer',
      allowClear: true,
      ajax: {
        url: '/sales/customer_visit/load/data/customers',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.custname + ' (' + item.company + ')',
                id: item.custid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.customer_online').select2({
      dropdownParent: $('#modal_make_schedule_online .modal-content'),
      placeholder: 'Customer',
      allowClear: true,
      ajax: {
        url: '/sales/customer_visit/load/data/customers',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.custname + ' (' + item.company + ')',
                id: item.custid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.edit_customer').select2({
      dropdownParent: $('#modal_edit_schedule .modal-content'),
      placeholder: 'Customer',
      allowClear: true,
      ajax: {
        url: '/sales/customer_visit/load/data/customers',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.custname + ' (' + item.company + ')',
                id: item.custid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.city').select2({
      dropdownParent: $('#modal_make_schedule_offline .modal-content'),
      placeholder: 'Address City',
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

    $('.city_online').select2({
      dropdownParent: $('#modal_make_schedule_online .modal-content'),
      placeholder: 'Address City',
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

    $('.city-address').select2({
        dropdownParent: $('#modal_quotation .modal-content'),
        placeholder: 'Kota',
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
  $(".customer").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".customer").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".customer_online").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".customer_online").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".edit_customer").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".edit_customer").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".city").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Kota / Kabupaten...");
  });
  $(".city").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".city_online").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Kota / Kabupaten...");
  });
  $(".city_online").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".city-address").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Kota / Kabupaten...");
  });
  $(".city-address").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
@endsection
