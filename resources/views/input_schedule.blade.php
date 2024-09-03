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
  #schedule_table tbody tr:hover{
    cursor: pointer;
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
                  <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#list_data" role="tab" aria-controls="custom-content-below-home" aria-selected="false">List Data</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#route_plan" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Route Plan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#jadwal_followup" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Jadwal Follow Up</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#realisasi_followup" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Realisasi Follow Up</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#quotation" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Quotation</a>
                </li>
                @if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10)
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#validasi_quotation" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Validasi</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#poin_delivery" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Delivery Poin</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#poin_paid" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Paid Poin</a>
                </li>
                @endif
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#status_done" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Done</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div id="dialog-confirm"></div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="list_data" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="schedule_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Jadwal</th>
                    <th>Tipe Cust</th>
                    <th>Customers</th>
                    <th>Perihal</th>
                    <th>Offline?</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="route_plan" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="route_plan_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jumlah Data</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="jadwal_followup" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="jadwal_followup_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jumlah Data</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="realisasi_followup" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="realisasi_followup_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Jadwal</th>
                    <th>Tipe Cust</th>
                    <th>Customers</th>
                    <th>Perihal</th>
                    <th>Offline?</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="quotation" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="quotation_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Tipe Cust</th>
                    <th>Customers</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            @if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10)
            <div class="tab-pane fade" id="validasi_quotation" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="validasi_quotation_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No Quote</th>
                    <th>Tanggal</th>
                    <th>Nama Customers</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="poin_delivery" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="poin_delivery_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jumlah Data</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="poin_paid" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="poin_paid_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jumlah Data</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            @endif
            <div class="tab-pane fade" id="status_done" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="status_done_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Tipe Cust</th>
                    <th>Customers</th>
                    <th>Offline?</th>
                    <th>Perihal</th>
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
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Buat Jadwal Kunjungan Offline</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="input_schedule_offline_form" id="input_schedule_offline_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="tanggal_jadwal" id="tanggal_jadwal" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="company">Perusahaan</label>
                  <select id="company" name="company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="tipe_customers">Tipe Customers</label>
                  <select id="tipe_customers" name="tipe_customers" class="form-control">
                    <option value="1" selected>Customers</option>
                    <option value="2">Leads</option>
                    <option value="3">Kompetitor</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <button type="button" class="btn btn-primary" id="btn_new_customer" style="width: 100%;">New Cust</button>
                </div>
              </div>
              <div class="col-3">
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
            <div class="row" id="select_leads" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label for="leads">Leads</label>
                  <select id="leads" name="leads" class="form-control select2 leads" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row" id="select_kompetitor" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label for="kompetitor">Kompetitor</label>
                  <select id="kompetitor" name="kompetitor" class="form-control select2 kompetitor" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div id="new_customers" style="display: none;">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="new_nama_customers">Nama Customer</label>
                    <input type="text" class="form-control" name="new_nama_customers" id="new_nama_customers" placeholder="Nama Customer">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="new_city">Kota</label>
                    <select id="new_city" name="new_city" class="form-control select2 city" style="width: 100%;">
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-8">
                  <div class="form-group">
                    <label for="new_alamat">Alamat</label>
                    <textarea class="form-control" rows="2" name="new_alamat" id="new_alamat" placeholder="Alamat"></textarea>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="new_telepon">Telepon</label>
                    <input type="text" class="form-control" name="new_telepon" id="new_telepon" placeholder="Telepon">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
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
              <div class="col-6">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
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

  <div class="modal fade" id="modal_make_schedule_online">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Buat Jadwal Kunjungan Online</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="input_schedule_online_form" id="input_schedule_online_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="tanggal_jadwal_online" id="tanggal_jadwal_online" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="company_online">Perusahaan</label>
                  <select id="company_online" name="company_online" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="tipe_customers_online">Tipe Customers</label>
                  <select id="tipe_customers_online" name="tipe_customers_online" class="form-control">
                    <option value="1" selected>Customers</option>
                    <option value="2">Leads</option>
                    <option value="3">Kompetitor</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <button type="button" class="btn btn-primary" id="btn_new_customer_online" style="width: 100%;">New Cust</button>
                </div>
              </div>
              <div class="col-3">
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
            <div class="row" id="select_leads_online" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label for="leads_online">Leads</label>
                  <select id="leads_online" name="leads_online" class="form-control select2 leads_online" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row" id="select_kompetitor_online" style="display: none;">
              <div class="col-12">
                <div class="form-group">
                  <label for="kompetitor_online">Kompetitor</label>
                  <select id="kompetitor_online" name="kompetitor_online" class="form-control select2 kompetitor_online" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div id="new_customers_online" style="display: none;">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="new_nama_customers_online">Nama Customer</label>
                    <input type="text" class="form-control" name="new_nama_customers_online" id="new_nama_customers_online" placeholder="Nama Customer">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="new_city_online">Kota</label>
                    <select id="new_city_online" name="new_city_online" class="form-control select2 city_online" style="width: 100%;">
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-8">
                  <div class="form-group">
                    <label for="new_alamat_online">Alamat</label>
                    <textarea class="form-control" rows="2" name="new_alamat_online" id="new_alamat_online" placeholder="Alamat"></textarea>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="new_telepon_online">Telepon</label>
                    <input type="text" class="form-control" name="new_telepon_online" id="new_telepon_online" placeholder="Telepon">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
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

  <div class="modal fade" id="modal_isi_question">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Isi Question Visit</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="input_question_form" id="input_question_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-3"></div>
              <div class="col-6">
                <div class="form-group">
                  <label for="kegiatan_question">Pilih Kegiatan</label>
                  <select id="kegiatan_question" name="kegiatan_question" class="form-control">
                    <option value="1" selected>Telp Untuk Visit</option>
                    <option value="2">Telp Tidak Diperkenankan Visit</option>
                    <option value="3">Visit Tanpa Telp</option>
                    <option value="4">Visit Setelah Telp</option>
                  </select>
                </div>
              </div>
              <input type="hidden" class="form-control" name="custid_question" id="custid_question">
              <input type="hidden" class="form-control" name="id_schedule_question" id="id_schedule_question">
              <input type="hidden" class="form-control" name="tipe_customer_question" id="tipe_customer_question">
              <div class="col-3"></div>
            </div>
            <div class="row" id="div_data_customer">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Data Customer</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="pic_data_customer">PIC</label>
                          <input type="text" class="form-control" name="pic_data_customer" id="pic_data_customer" placeholder="PIC">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="jabatan_data_customer">Jabatan</label>
                          <input type="text" class="form-control" name="jabatan_data_customer" id="jabatan_data_customer" placeholder="Jabatan">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="bidang_usaha_data_customer">Bidang Usaha</label>
                          <input type="text" class="form-control" name="bidang_usaha_data_customer" id="bidang_usaha_data_customer" placeholder="Bidang Usaha">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="telepon_data_customer">Telepon</label>
                          <input type="text" class="form-control" name="telepon_data_customer" id="telepon_data_customer" placeholder="Telepon">
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pusat_data_customer">Pusat / Cabang?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pusat_data_customer" name="pusat_cabang_data_customer" value="1">
                            <label for="pusat_data_customer" class="custom-control-label">Pusat</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="cabang_data_customer">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="cabang_data_customer" name="pusat_cabang_data_customer" value="2" checked>
                            <label for="cabang_data_customer" class="custom-control-label">Cabang</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_pengenalan">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Pengenalan DSGM</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="company_profile_yes">Company Profile</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="company_profile_yes" name="input_company_profile" value="yes">
                            <label for="company_profile_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="company_profile_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="company_profile_no" name="input_company_profile" value="no" checked>
                            <label for="company_profile_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pengenalan_produk_yes">Pengenalan Produk</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pengenalan_produk_yes" name="input_pengenalan_produk" value="yes">
                            <label for="pengenalan_produk_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pengenalan_produk_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pengenalan_produk_no" name="input_pengenalan_produk" value="no" checked>
                            <label for="pengenalan_produk_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="sumber_kenal_dsgm">Sumber Kenal DSGM</label>
                          <input type="text" class="form-control" name="sumber_kenal_dsgm" id="sumber_kenal_dsgm" placeholder="Sumber Kenal DSGM">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="nomor_surat_pengenalan">No Surat Pengenalan</label>
                          <input type="text" class="form-control" name="nomor_surat_pengenalan" id="nomor_surat_pengenalan" placeholder="No Surat Pengenalan">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_janji_visit">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Janji Visit</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="nama_janji_visit">Nama</label>
                          <input type="text" class="form-control" name="nama_janji_visit" id="nama_janji_visit" placeholder="Nama">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="pic_janji_visit">PIC</label>
                          <input type="text" class="form-control" name="pic_janji_visit" id="pic_janji_visit" placeholder="PIC">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="jabatan_janji_visit">Jabatan</label>
                          <input type="text" class="form-control" name="jabatan_janji_visit" id="jabatan_janji_visit" placeholder="Jabatan">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="alamat_janji_visit">Alamat</label>
                          <textarea class="form-control" rows="3" name="alamat_janji_visit" id="alamat_janji_visit" placeholder="Alamat"></textarea>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label>Tanggal</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="tanggal_janji_visit" id="tanggal_janji_visit" autocomplete="off" placeholder="Tanggal">
                          </div>
                          <!-- /.input group -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_latar_belakang" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Latar Belakang Customer</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="bisnis_latar_belakang">Bisnis Customer</label>
                          <input type="text" class="form-control" name="bisnis_latar_belakang" id="bisnis_latar_belakang" placeholder="Bisnis Customer">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="owner_latar_belakang">Owner</label>
                          <input type="text" class="form-control" name="owner_latar_belakang" id="owner_latar_belakang" placeholder="Owner">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="tahun_berdiri_latar_belakang">Tahun Berdiri</label>
                          <input type="text" class="form-control" name="tahun_berdiri_latar_belakang" id="tahun_berdiri_latar_belakang" placeholder="Tahun Berdiri">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="jenis_usaha_latar_belakang">Jenis Usaha</label>
                          <input type="text" class="form-control" name="jenis_usaha_latar_belakang" id="jenis_usaha_latar_belakang" placeholder="Jenis Usaha">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="jangkauan_wilayah_latar_belakang">Jangkauan Wilayah</label>
                          <input type="text" class="form-control" name="jangkauan_wilayah_latar_belakang" id="jangkauan_wilayah_latar_belakang" placeholder="Jangkauan Wilayah">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="top_latar_belakang">TOP Yang Diberikan ke Cust</label>
                          <input type="text" class="form-control" name="top_latar_belakang" id="top_latar_belakang" placeholder="TOP Yang Diberikan ke Cust">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_penggunaan_kalsium" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Penggunaan Kalsium</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="tipe_penggunaan_kalsium">Tipe CaCO3</label>
                          <select id="tipe_penggunaan_kalsium" name="tipe_penggunaan_kalsium" class="form-control" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label for="qty_penggunaan_kalsium">Qty CaCO3 (KG)</label>
                          <input type="text" class="form-control" name="qty_penggunaan_kalsium" id="qty_penggunaan_kalsium" placeholder="Qty">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="kegunaan_penggunaan_kalsium">Kegunaan CaCO3</label>
                          <textarea class="form-control" rows="3" name="kegunaan_penggunaan_kalsium" id="kegunaan_penggunaan_kalsium" placeholder="Kegunaan CaCO3"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="merk_kompetitor_penggunaan_kalsium">Merk Kompetitor</label>
                          <input type="text" class="form-control" name="merk_kompetitor_penggunaan_kalsium" id="merk_kompetitor_penggunaan_kalsium" placeholder="Merk Kompetitor">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="harga_kompetitor_penggunaan_kalsium">Harga Kompetitor</label>
                          <input type="text" class="form-control" name="harga_kompetitor_penggunaan_kalsium" id="harga_kompetitor_penggunaan_kalsium" placeholder="Harga Kompetitor">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="pengiriman_penggunaan_kalsium">Pengiriman</label>
                          <input type="text" class="form-control" name="pengiriman_penggunaan_kalsium" id="pengiriman_penggunaan_kalsium" placeholder="Pengiriman">
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pembayaran_penggunaan_kalsium_cash">Pembayaran Supplier</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pembayaran_penggunaan_kalsium_cash" name="pembayaran_penggunaan_kalsium" value="1">
                            <label for="pembayaran_penggunaan_kalsium_cash" class="custom-control-label">Cash</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pembayaran_penggunaan_kalsium_top">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pembayaran_penggunaan_kalsium_top" name="pembayaran_penggunaan_kalsium" value="2" checked>
                            <label for="pembayaran_penggunaan_kalsium_top" class="custom-control-label">TOP</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_permintaan_sample" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Permintaan Sample</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="tipe_permintaan_sample">Tipe Sample</label>
                          <select id="tipe_permintaan_sample" name="tipe_permintaan_sample" class="form-control" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label for="qty_permintaan_sample">Qty Sample (KG)</label>
                          <input type="text" class="form-control" name="qty_permintaan_sample" id="qty_permintaan_sample" placeholder="Qty">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="feedback_permintaan_sample">Feedback Setelah Uji Sample</label>
                          <textarea class="form-control" rows="3" name="feedback_permintaan_sample" id="feedback_permintaan_sample" placeholder="Feedback Setelah Uji Sample"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_penawaran_harga" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Penawaran Harga</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="tawar_penawaran_harga_yes">Penawaran Harga</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="tawar_penawaran_harga_yes" name="tawar_penawaran_harga" value="yes">
                            <label for="tawar_penawaran_harga_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="tawar_penawaran_harga_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="tawar_penawaran_harga_no" name="tawar_penawaran_harga" value="no" checked>
                            <label for="tawar_penawaran_harga_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="nego_penawaran_harga_yes">Nego Harga</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="nego_penawaran_harga_yes" name="nego_penawaran_harga" value="yes">
                            <label for="nego_penawaran_harga_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="nego_penawaran_harga_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="nego_penawaran_harga_no" name="nego_penawaran_harga" value="no" checked>
                            <label for="nego_penawaran_harga_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="nominal_penawaran_harga">Nominal Harga</label>
                          <input type="text" class="form-control" name="nominal_penawaran_harga" id="nominal_penawaran_harga" placeholder="Nominal Harga">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4" id="div_nomor_penawaran_harga" style="display: none;">
                        <div class="form-group">
                          <label for="nomor_penawaran_harga">Nomor Penawaran</label>
                          <input type="text" class="form-control" name="nomor_penawaran_harga" id="nomor_penawaran_harga" placeholder="Nomor Penawaran">
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pembayaran_penawaran_harga_yes">Pembayaran</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pembayaran_penawaran_harga_yes" name="pembayaran_penawaran_harga" value="yes">
                            <label for="pembayaran_penawaran_harga_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pembayaran_penawaran_harga_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pembayaran_penawaran_harga_no" name="pembayaran_penawaran_harga" value="no" checked>
                            <label for="pembayaran_penawaran_harga_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pengiriman_penawaran_harga_yes">Pengiriman</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pengiriman_penawaran_harga_yes" name="pengiriman_penawaran_harga" value="yes">
                            <label for="pengiriman_penawaran_harga_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="pengiriman_penawaran_harga_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="pengiriman_penawaran_harga_no" name="pengiriman_penawaran_harga" value="no" checked>
                            <label for="pengiriman_penawaran_harga_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="dokumen_penawaran_harga">Dokumen Untuk Pengiriman</label>
                          <input type="text" class="form-control" name="dokumen_penawaran_harga" id="dokumen_penawaran_harga" placeholder="Dokumen Untuk Pengiriman">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_pembayaran" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Pembayaran</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="cash_pembayaran">Cash / TOP?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="cash_pembayaran" name="cash_top_pembayaran" value="1">
                            <label for="cash_pembayaran" class="custom-control-label">Cash</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="top_pembayaran">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="top_pembayaran" name="cash_top_pembayaran" value="2" checked>
                            <label for="top_pembayaran" class="custom-control-label">TOP</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-4" id="div_tempat_tt">
                        <div class="form-group">
                          <label for="tempat_tt_pembayaran">Tempat Tukar TT</label>
                          <input type="text" class="form-control" name="tempat_tt_pembayaran" id="tempat_tt_pembayaran" placeholder="Tempat Tukar TT">
                        </div>
                      </div>
                      <div class="col-4" id="div_jadwal_tt">
                        <div class="form-group">
                          <label>Jadwal Tukar TT</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="jadwal_tt_pembayaran" id="jadwal_tt_pembayaran" autocomplete="off" placeholder="Jadwal Tukar TT">
                          </div>
                          <!-- /.input group -->
                        </div>
                      </div>
                    </div>
                    <div class="row" id="div_detail_pembayaran">
                      <div class="col-4">
                        <div class="form-group">
                          <label>Jadwal Pembayaran</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="jadwal_pembayaran" id="jadwal_pembayaran" autocomplete="off" placeholder="Jadwal Pembayaran">
                          </div>
                          <!-- /.input group -->
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="metode_pembayaran">Metode Pembayaran</label>
                          <select id="metode_pembayaran" name="metode_pembayaran" class="form-control">
                            <option value="2" selected>Cek</option>
                            <option value="3">Giro</option>
                            <option value="4">Transfer</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="pic_pembayaran">PIC Untuk Penagihan</label>
                          <input type="text" class="form-control" name="pic_pembayaran" id="pic_pembayaran" placeholder="PIC Untuk Penagihan">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_po_dokumen" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>PO dan Dokumen Customer</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="po_dokumen_cust">PO</label>
                          <input type="text" class="form-control" name="po_dokumen_cust" id="po_dokumen_cust" placeholder="PO">
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="ktp_dokumen_cust_yes">KTP ?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="ktp_dokumen_cust_yes" name="ktp_dokumen_cust" value="yes">
                            <label for="ktp_dokumen_cust_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="ktp_dokumen_cust_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="ktp_dokumen_cust_no" name="ktp_dokumen_cust" value="no" checked>
                            <label for="ktp_dokumen_cust_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="npwp_dokumen_cust_yes">NPWP ?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="npwp_dokumen_cust_yes" name="npwp_dokumen_cust" value="yes">
                            <label for="npwp_dokumen_cust_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="npwp_dokumen_cust_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="npwp_dokumen_cust_no" name="npwp_dokumen_cust" value="no" checked>
                            <label for="npwp_dokumen_cust_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="siup_dokumen_cust_yes">SIUP ?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="siup_dokumen_cust_yes" name="siup_dokumen_cust" value="yes">
                            <label for="siup_dokumen_cust_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="siup_dokumen_cust_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="siup_dokumen_cust_no" name="siup_dokumen_cust" value="no" checked>
                            <label for="siup_dokumen_cust_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="tdp_dokumen_cust_yes">TDP ?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="tdp_dokumen_cust_yes" name="tdp_dokumen_cust" value="yes">
                            <label for="tdp_dokumen_cust_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="tdp_dokumen_cust_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="tdp_dokumen_cust_no" name="tdp_dokumen_cust" value="no" checked>
                            <label for="tdp_dokumen_cust_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3" id="div_no_ktp" style="display: none;">
                        <div class="form-group">
                          <label for="no_ktp_dokumen_cust">Nomor KTP</label>
                          <input type="text" class="form-control" name="no_ktp_dokumen_cust" id="no_ktp_dokumen_cust" placeholder="Nomor KTP">
                        </div>
                      </div>
                      <div class="col-3" id="div_no_npwp" style="display: none;">
                        <div class="form-group">
                          <label for="no_npwp_dokumen_cust">Nomor NPWP</label>
                          <input type="text" class="form-control" name="no_npwp_dokumen_cust" id="no_npwp_dokumen_cust" placeholder="Nomor NPWP">
                        </div>
                      </div>
                      <div class="col-3" id="div_no_siup" style="display: none;">
                        <div class="form-group">
                          <label for="no_siup_dokumen_cust">Nomor SIUP</label>
                          <input type="text" class="form-control" name="no_siup_dokumen_cust" id="no_siup_dokumen_cust" placeholder="Nomor SIUP">
                        </div>
                      </div>
                      <div class="col-3" id="div_no_tdp" style="display: none;">
                        <div class="form-group">
                          <label for="no_tdp_dokumen_cust">Nomor TDP</label>
                          <input type="text" class="form-control" name="no_tdp_dokumen_cust" id="no_tdp_dokumen_cust" placeholder="Nomor TDP">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3" id="div_image_ktp" style="display: none;">
                        <div class="form-group">
                          <label for="image_ktp_dokumen_cust">Upload Image KTP</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="image_ktp_dokumen_cust" name="image_ktp_dokumen_cust">
                              <label class="custom-file-label" for="image_ktp_dokumen_cust">Choose Image</label>
                            </div>
                          </div>
                        </div>
                        <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
                      </div>
                      <div class="col-3" id="div_image_npwp" style="display: none;">
                        <div class="form-group">
                          <label for="image_npwp_dokumen_cust">Upload Image NPWP</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="image_npwp_dokumen_cust" name="image_npwp_dokumen_cust">
                              <label class="custom-file-label" for="image_npwp_dokumen_cust">Choose Image</label>
                            </div>
                          </div>
                        </div>
                        <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
                      </div>
                      <div class="col-3" id="div_image_siup" style="display: none;">
                        <div class="form-group">
                          <label for="image_siup_dokumen_cust">Upload Image SIUP</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="image_siup_dokumen_cust" name="image_siup_dokumen_cust">
                              <label class="custom-file-label" for="image_siup_dokumen_cust">Choose Image</label>
                            </div>
                          </div>
                        </div>
                        <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
                      </div>
                      <div class="col-3" id="div_image_tdp" style="display: none;">
                        <div class="form-group">
                          <label for="image_tdp_dokumen_cust">Upload Image TDP</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="image_tdp_dokumen_cust" name="image_tdp_dokumen_cust">
                              <label class="custom-file-label" for="image_tdp_dokumen_cust">Choose Image</label>
                            </div>
                          </div>
                        </div>
                        <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_hasil_visit">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Hasil Visit / Follow Up</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label for="hasil_visit_question">Hasil Visit</label>
                          <textarea class="form-control" rows="3" name="hasil_visit_question" id="hasil_visit_question" placeholder="Hasil Visit"></textarea>
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
          <button type="submit" class="btn btn-primary">Save changes</button>
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
               <th>Perusahaan</th>
               <td id="td_company"></td>
               <th>Tipe Customers</th>
               <td id="td_tipe_customers"></td>
             </tr>
             <tr>
               <th>Customers</th>
               <td id="td_customers"></td>
               <th>Perihal</th>
               <td id="td_perihal"></td>
               <th>Offline?</th>
               <td id="td_offline"></td>
             </tr>
             <tr>
               <th>Keterangan</th>
               <td id="td_keterangan"></td>
               <th>Tanggal Schedule</th>
               <td id="td_tanggal_schedule"></td>
               <th>Alasan Suspend</th>
               <td id="td_alasan_suspend"></td>
             </tr>
             <tr>
              <th>Tanggal Input</th>
               <td id="td_tanggal_input"></td>
               <th>Tanggal Done</th>
               <td id="td_tanggal_done"></td>
               <th>Status</th>
               <td id="td_status"></td>
             </tr>
           </thead>
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

  <div class="modal fade" id="modal_view_question">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title_lihat_detail_question"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Kegiatan</th>
                    <td id="td_question_kegiatan"></td>
                    <th>Hasil Visit</th>
                    <td id="td_question_hasil_visit"></td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div class="row" id="div_view_pengenalan_dsgm">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Pengenalan DSGM</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Company Profile</th>
                            <td id="td_question_company_profile"></td>
                            <th>Pengenalan Produk</th>
                            <td id="td_question_pengenalan_produk"></td>
                          </tr>
                          <tr>
                            <th>Sumber Kenal DSGM</th>
                            <td id="td_question_sumber_kenal_dsgm"></td>
                            <th>Nomor Surat Pengenalan</th>
                            <td id="td_question_nomor_surat_pengenalan"></td>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" id="div_view_janji_visit">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Janji Visit</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Nama</th>
                            <td id="td_question_nama_janji"></td>
                            <th>PIC</th>
                            <td id="td_question_pic_janji"></td>
                            <th>Jabatan</th>
                            <td id="td_question_jabatan_janji"></td>
                          </tr>
                          <tr>
                            <th>Alamat</th>
                            <td id="td_question_alamat_janji" colspan="3"></td>
                            <th>Tanggal</th>
                            <td id="td_question_tanggal_janji"></td>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" id="div_view_latar_belakang">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Latar Belakang Cust</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Bisnis Cust</th>
                            <td id="td_question_bisnis_cust"></td>
                            <th>Owner</th>
                            <td id="td_question_owner"></td>
                            <th>Tahun Berdiri</th>
                            <td id="td_question_tahun_berdiri"></td>
                          </tr>
                          <tr>
                            <th>Jenis Usaha</th>
                            <td id="td_question_jenis_usaha"></td>
                            <th>Jangkauan Wilayah</th>
                            <td id="td_question_jangkauan_wilayah"></td>
                            <th>TOP ke Cust</th>
                            <td id="td_question_top_cust"></td>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" id="div_view_penggunaan_kalsium">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Penggunaan Kalsium</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Tipe CaCO3</th>
                            <td id="td_question_tipe_penggunaan_kalsium"></td>
                            <th>Qty CaCO3</th>
                            <td id="td_question_qty_penggunaan_kalsium"></td>
                            <th>Kegunaan CaCO3</th>
                            <td id="td_question_kegunaan_penggunaan_kalsium"></td>
                          </tr>
                          <tr>
                            <th>Merk Kompetitor</th>
                            <td id="td_question_merk_kompetitor"></td>
                            <th>Harga Kompetitor</th>
                            <td id="td_question_harga_kompetitor"></td>
                            <th>Pengiriman</th>
                            <td id="td_question_pengiriman_penggunaan_kalsium"></td>
                          </tr>
                          <tr>
                            <th>Pembayaran Supplier</th>
                            <td id="td_question_pembayaran_supplier" colspan="5"></td>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" id="div_view_permintaan_sample">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Permintaan Sample</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Tipe Sample</th>
                            <td id="td_question_tipe_permintaan_sample"></td>
                            <th>Qty Sample</th>
                            <td id="td_question_qty_permintaan_sample"></td>
                            <th>Feedback Uji Sample</th>
                            <td id="td_question_feedback_permintaan_sample"></td>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" id="div_view_penawaran_harga">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Penawaran Harga</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Penawaran Harga</th>
                            <td id="td_question_penawaran_harga"></td>
                            <th>Nego Harga</th>
                            <td id="td_question_nego_harga"></td>
                            <th>Nominal Harga</th>
                            <td id="td_question_nominal_harga"></td>
                          </tr>
                          <tr>
                            <th>Nomor Penawaran</th>
                            <td id="td_question_nomor_penawaran"></td>
                            <th>Pembayaran</th>
                            <td id="td_question_pembayaran"></td>
                            <th>Pengiriman</th>
                            <td id="td_question_pengiriman"></td>
                          </tr>
                          <tr>
                            <th>Dokumen Pengiriman</th>
                            <td id="td_question_dokumen_pengiriman" colspan="5"></td>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" id="div_view_pembayaran">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Pembayaran</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Tipe Pembayaran</th>
                            <td id="td_question_tipe_pembayaran"></td>
                            <th>Tempat Tukar TT</th>
                            <td id="td_question_tempat_tukar_tt"></td>
                            <th>Jadwal Tukar TT</th>
                            <td id="td_question_jadwal_tukar_tt"></td>
                          </tr>
                          <tr>
                            <th>Jadwal Pembayaran</th>
                            <td id="td_question_jadwal_pembayaran"></td>
                            <th>Metode Pembayaran</th>
                            <td id="td_question_metode_pembayaran"></td>
                            <th>PIC Penagihan</th>
                            <td id="td_question_pic_penagihan"></td>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" id="div_po_dokumen_cust">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>PO dan Dokumen Cust</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>PO</th>
                            <td id="td_question_nomor_po"></td>
                            <th>KTP</th>
                            <td id="td_question_nomor_ktp"></td>
                            <th>NPWP</th>
                            <td id="td_question_nomor_npwp"></td>
                          </tr>
                          <tr>
                            <th>SIUP</th>
                            <td id="td_question_nomor_siup"></td>
                            <th>TDP</th>
                            <td id="td_question_nomor_tdp"></td>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
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

  <div class="modal fade" id="modal_proses_schedule">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Schedule Process</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="proses_schedule_form" id="proses_schedule_form" action="javascript:void(0)">
            {{ csrf_field() }}
          <input class="form-control" type="hidden" name="proses_id_schedule" id="proses_id_schedule" />
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="proses_status">Status Visit</label>
                <select id="proses_status" name="proses_status" class="form-control">
                  <option value="" selected>Status Visit</option>
                  <option value="3">Selesai</option>
                  <option value="2">Ditunda</option>
                </select>
              </div>
            </div>
          </div>
          <div id="div_result" style="display: none;">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Result</label>
                  <textarea class="form-control" rows="3" name="result" id="result" placeholder="Result"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="follow_up">Perlu Follow Up?</label>
                  <select id="follow_up" name="follow_up" class="form-control">
                    <option value="1" selected>Ya</option>
                    <option value="2">Tidak</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row" id="div_range_follow_up">
              <div class="col-12">
                <div class="form-group">
                  <label for="range_follow_up">Range Follow Up</label>
                  <select id="range_follow_up" name="range_follow_up" class="form-control">
                    <option value="7" selected>7 Hari</option>
                    <option value="14">14 Hari</option>
                    <option value="30">30 hari</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div id="div_suspend" style="display: none;">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Ganti Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="proses_tanggal_jadwal" id="proses_tanggal_jadwal" placeholder="Ganti Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Alasan Ditunda</label>
                  <textarea class="form-control" rows="3" name="alasan_suspend" id="alasan_suspend" placeholder="Alasan Ditunda"></textarea>
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

  <div class="modal fade" id="modal_edit_schedule">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Jadwal Kunjungan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="edit_schedule_form" id="edit_schedule_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="edit_tanggal_jadwal" id="edit_tanggal_jadwal" autocomplete="off" placeholder="Tanggal">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
              <input type="hidden" class="form-control" name="edit_id_schedule" id="edit_id_schedule">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_company">Perusahaan</label>
                  <select id="edit_company" name="edit_company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_tipe_customers">Tipe Customers</label>
                  <select id="edit_tipe_customers" name="edit_tipe_customers" class="form-control">
                    <option value="1" selected>Customers</option>
                    <option value="2">Leads</option>
                    <option value="3">Kompetitor</option>
                  </select>
                </div>
              </div>
              <div class="col-6" id="edit_select_customers" style="display: none;">
                <div class="form-group">
                  <label for="edit_customers">Customer</label>
                  <select id="edit_customers" name="edit_customers" class="form-control select2 edit_customer" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-6" id="edit_select_leads" style="display: none;">
                <div class="form-group">
                  <label for="edit_leads">Leads</label>
                  <select id="edit_leads" name="edit_leads" class="form-control select2 edit_leads" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-6" id="edit_select_kompetitor" style="display: none;">
                <div class="form-group">
                  <label for="edit_kompetitor">Kompetitor</label>
                  <select id="edit_kompetitor" name="edit_kompetitor" class="form-control select2 edit_kompetitor" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
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
              <div class="col-6">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control" rows="3" name="edit_keterangan" id="edit_keterangan" placeholder="Keterangan"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_offline">Offline / Online?</label>
                  <select id="edit_offline" name="edit_offline" class="form-control">
                    <option value="1" selected>Offline</option>
                    <option value="0">Online</option>
                  </select>
                </div>
              </div>
              <div class="col-6" id="div_alasan_suspend" style="display: none;">
                <div class="form-group">
                  <label>Alasan Suspend</label>
                  <textarea class="form-control" rows="3" name="edit_alasan_suspend" id="edit_alasan_suspend" placeholder="Alasan Suspend"></textarea>
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

  <div class="modal fade" id="modal_edit_question">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Question Visit</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" class="edit_question_form" id="edit_question_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-3"></div>
              <div class="col-6">
                <div class="form-group">
                  <label for="edit_kegiatan_question">Pilih Kegiatan</label>
                  <select id="edit_kegiatan_question" name="edit_kegiatan_question" class="form-control">
                    <option value="1" selected>Telp Untuk Visit</option>
                    <option value="2">Telp Tidak Diperkenankan Visit</option>
                    <option value="3">Visit Tanpa Telp</option>
                    <option value="4">Visit Setelah Telp</option>
                  </select>
                </div>
              </div>
              <input type="hidden" class="form-control" name="edit_id_schedule_question" id="edit_id_schedule_question">
              <input type="hidden" class="form-control" name="edit_custid_question" id="edit_custid_question">
              <div class="col-3"></div>
            </div>
            <div class="row" id="div_edit_pengenalan">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Pengenalan DSGM</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_company_profile_yes">Company Profile</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_company_profile_yes" name="edit_company_profile" value="yes">
                            <label for="edit_company_profile_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_company_profile_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_company_profile_no" name="edit_company_profile" value="no" checked>
                            <label for="edit_company_profile_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_pengenalan_produk_yes">Pengenalan Produk</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_pengenalan_produk_yes" name="edit_pengenalan_produk" value="yes">
                            <label for="edit_pengenalan_produk_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_pengenalan_produk_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_pengenalan_produk_no" name="edit_pengenalan_produk" value="no" checked>
                            <label for="edit_pengenalan_produk_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_sumber_kenal_dsgm">Sumber Kenal DSGM</label>
                          <input type="text" class="form-control" name="edit_sumber_kenal_dsgm" id="edit_sumber_kenal_dsgm" placeholder="Sumber Kenal DSGM">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_nomor_surat_pengenalan">No Surat Pengenalan</label>
                          <input type="text" class="form-control" name="edit_nomor_surat_pengenalan" id="edit_nomor_surat_pengenalan" placeholder="No Surat Pengenalan">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_edit_janji_visit">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Janji Visit</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_nama_janji_visit">Nama</label>
                          <input type="text" class="form-control" name="edit_nama_janji_visit" id="edit_nama_janji_visit" placeholder="Nama">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_pic_janji_visit">PIC</label>
                          <input type="text" class="form-control" name="edit_pic_janji_visit" id="edit_pic_janji_visit" placeholder="PIC">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_jabatan_janji_visit">Jabatan</label>
                          <input type="text" class="form-control" name="edit_jabatan_janji_visit" id="edit_jabatan_janji_visit" placeholder="Jabatan">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="edit_alamat_janji_visit">Alamat</label>
                          <textarea class="form-control" rows="3" name="edit_alamat_janji_visit" id="edit_alamat_janji_visit" placeholder="Alamat"></textarea>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label>Tanggal</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="edit_tanggal_janji_visit" id="edit_tanggal_janji_visit" autocomplete="off" placeholder="Tanggal">
                          </div>
                          <!-- /.input group -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_edit_latar_belakang" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Latar Belakang Customer</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_bisnis_latar_belakang">Bisnis Customer</label>
                          <input type="text" class="form-control" name="edit_bisnis_latar_belakang" id="edit_bisnis_latar_belakang" placeholder="Bisnis Customer">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_owner_latar_belakang">Owner</label>
                          <input type="text" class="form-control" name="edit_owner_latar_belakang" id="edit_owner_latar_belakang" placeholder="Owner">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_tahun_berdiri_latar_belakang">Tahun Berdiri</label>
                          <input type="text" class="form-control" name="edit_tahun_berdiri_latar_belakang" id="edit_tahun_berdiri_latar_belakang" placeholder="Tahun Berdiri">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_jenis_usaha_latar_belakang">Jenis Usaha</label>
                          <input type="text" class="form-control" name="edit_jenis_usaha_latar_belakang" id="edit_jenis_usaha_latar_belakang" placeholder="Jenis Usaha">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_jangkauan_wilayah_latar_belakang">Jangkauan Wilayah</label>
                          <input type="text" class="form-control" name="edit_jangkauan_wilayah_latar_belakang" id="edit_jangkauan_wilayah_latar_belakang" placeholder="Jangkauan Wilayah">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_top_latar_belakang">TOP Yang Diberikan ke Cust</label>
                          <input type="text" class="form-control" name="edit_top_latar_belakang" id="edit_top_latar_belakang" placeholder="TOP Yang Diberikan ke Cust">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_edit_penggunaan_kalsium" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Penggunaan Kalsium</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_tipe_penggunaan_kalsium">Tipe CaCO3</label>
                          <select id="edit_tipe_penggunaan_kalsium" name="edit_tipe_penggunaan_kalsium" class="form-control" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label for="edit_qty_penggunaan_kalsium">Qty CaCO3 (KG)</label>
                          <input type="text" class="form-control" name="edit_qty_penggunaan_kalsium" id="edit_qty_penggunaan_kalsium" placeholder="Qty">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="edit_kegunaan_penggunaan_kalsium">Kegunaan CaCO3</label>
                          <textarea class="form-control" rows="3" name="edit_kegunaan_penggunaan_kalsium" id="edit_kegunaan_penggunaan_kalsium" placeholder="Kegunaan CaCO3"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="edit_merk_kompetitor_penggunaan_kalsium">Merk Kompetitor</label>
                          <input type="text" class="form-control" name="edit_merk_kompetitor_penggunaan_kalsium" id="edit_merk_kompetitor_penggunaan_kalsium" placeholder="Merk Kompetitor">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="edit_harga_kompetitor_penggunaan_kalsium">Harga Kompetitor</label>
                          <input type="text" class="form-control" name="edit_harga_kompetitor_penggunaan_kalsium" id="edit_harga_kompetitor_penggunaan_kalsium" placeholder="Harga Kompetitor">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="edit_pengiriman_penggunaan_kalsium">Pengiriman</label>
                          <input type="text" class="form-control" name="edit_pengiriman_penggunaan_kalsium" id="edit_pengiriman_penggunaan_kalsium" placeholder="Pengiriman">
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_pembayaran_penggunaan_kalsium_cash">Pembayaran Supplier</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_pembayaran_penggunaan_kalsium_cash" name="edit_pembayaran_penggunaan_kalsium" value="1">
                            <label for="edit_pembayaran_penggunaan_kalsium_cash" class="custom-control-label">Cash</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_pembayaran_penggunaan_kalsium_top">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_pembayaran_penggunaan_kalsium_top" name="edit_pembayaran_penggunaan_kalsium" value="2" checked>
                            <label for="edit_pembayaran_penggunaan_kalsium_top" class="custom-control-label">TOP</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_edit_permintaan_sample" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Permintaan Sample</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_tipe_permintaan_sample">Tipe Sample</label>
                          <select id="edit_tipe_permintaan_sample" name="edit_tipe_permintaan_sample" class="form-control" style="width: 100%;">
                          </select>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label for="edit_qty_permintaan_sample">Qty Sample (KG)</label>
                          <input type="text" class="form-control" name="edit_qty_permintaan_sample" id="edit_qty_permintaan_sample" placeholder="Qty">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="edit_feedback_permintaan_sample">Feedback Setelah Uji Sample</label>
                          <textarea class="form-control" rows="3" name="edit_feedback_permintaan_sample" id="edit_feedback_permintaan_sample" placeholder="Feedback Setelah Uji Sample"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_edit_penawaran_harga" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Penawaran Harga</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_tawar_penawaran_harga_yes">Penawaran Harga</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_tawar_penawaran_harga_yes" name="edit_tawar_penawaran_harga" value="yes">
                            <label for="edit_tawar_penawaran_harga_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_tawar_penawaran_harga_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_tawar_penawaran_harga_no" name="edit_tawar_penawaran_harga" value="no" checked>
                            <label for="edit_tawar_penawaran_harga_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_nego_penawaran_harga_yes">Nego Harga</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_nego_penawaran_harga_yes" name="edit_nego_penawaran_harga" value="yes">
                            <label for="edit_nego_penawaran_harga_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_nego_penawaran_harga_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_nego_penawaran_harga_no" name="edit_nego_penawaran_harga" value="no" checked>
                            <label for="edit_nego_penawaran_harga_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_nominal_penawaran_harga">Nominal Harga</label>
                          <input type="text" class="form-control" name="edit_nominal_penawaran_harga" id="edit_nominal_penawaran_harga" placeholder="Nominal Harga">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4" id="div_edit_nomor_penawaran_harga" style="display: none;">
                        <div class="form-group">
                          <label for="edit_nomor_penawaran_harga">Nomor Penawaran</label>
                          <input type="text" class="form-control" name="edit_nomor_penawaran_harga" id="edit_nomor_penawaran_harga" placeholder="Nomor Penawaran">
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_pembayaran_penawaran_harga_yes">Pembayaran</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_pembayaran_penawaran_harga_yes" name="edit_pembayaran_penawaran_harga" value="yes">
                            <label for="edit_pembayaran_penawaran_harga_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_pembayaran_penawaran_harga_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_pembayaran_penawaran_harga_no" name="edit_pembayaran_penawaran_harga" value="no" checked>
                            <label for="edit_pembayaran_penawaran_harga_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_pengiriman_penawaran_harga_yes">Pengiriman</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_pengiriman_penawaran_harga_yes" name="edit_pengiriman_penawaran_harga" value="yes">
                            <label for="edit_pengiriman_penawaran_harga_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_pengiriman_penawaran_harga_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_pengiriman_penawaran_harga_no" name="edit_pengiriman_penawaran_harga" value="no" checked>
                            <label for="edit_pengiriman_penawaran_harga_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_dokumen_penawaran_harga">Dokumen Untuk Pengiriman</label>
                          <input type="text" class="form-control" name="edit_dokumen_penawaran_harga" id="edit_dokumen_penawaran_harga" placeholder="Dokumen Untuk Pengiriman">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_edit_pembayaran" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Pembayaran</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_cash_pembayaran">Cash / TOP?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_cash_pembayaran" name="edit_cash_top_pembayaran" value="1">
                            <label for="edit_cash_pembayaran" class="custom-control-label">Cash</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_top_pembayaran">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_top_pembayaran" name="edit_cash_top_pembayaran" value="2" checked>
                            <label for="edit_top_pembayaran" class="custom-control-label">TOP</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-4" id="div_edit_tempat_tt">
                        <div class="form-group">
                          <label for="edit_tempat_tt_pembayaran">Tempat Tukar TT</label>
                          <input type="text" class="form-control" name="edit_tempat_tt_pembayaran" id="edit_tempat_tt_pembayaran" placeholder="Tempat Tukar TT">
                        </div>
                      </div>
                      <div class="col-4" id="div_edit_jadwal_tt">
                        <div class="form-group">
                          <label>Jadwal Tukar TT</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="edit_jadwal_tt_pembayaran" id="edit_jadwal_tt_pembayaran" autocomplete="off" placeholder="Jadwal Tukar TT">
                          </div>
                          <!-- /.input group -->
                        </div>
                      </div>
                    </div>
                    <div class="row" id="div_edit_detail_pembayaran">
                      <div class="col-4">
                        <div class="form-group">
                          <label>Jadwal Pembayaran</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="edit_jadwal_pembayaran" id="edit_jadwal_pembayaran" autocomplete="off" placeholder="Jadwal Pembayaran">
                          </div>
                          <!-- /.input group -->
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_metode_pembayaran">Metode Pembayaran</label>
                          <select id="edit_metode_pembayaran" name="edit_metode_pembayaran" class="form-control">
                            <option value="2" selected>Cek</option>
                            <option value="3">Giro</option>
                            <option value="4">Transfer</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_pic_pembayaran">PIC Untuk Penagihan</label>
                          <input type="text" class="form-control" name="edit_pic_pembayaran" id="edit_pic_pembayaran" placeholder="PIC Untuk Penagihan">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_edit_po_dokumen" style="display: none;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>PO dan Dokumen Customer</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="edit_po_dokumen_cust">PO</label>
                          <input type="text" class="form-control" name="edit_po_dokumen_cust" id="edit_po_dokumen_cust" placeholder="PO">
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_ktp_dokumen_cust_yes">KTP ?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_ktp_dokumen_cust_yes" name="edit_ktp_dokumen_cust" value="yes">
                            <label for="edit_ktp_dokumen_cust_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_ktp_dokumen_cust_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_ktp_dokumen_cust_no" name="edit_ktp_dokumen_cust" value="no" checked>
                            <label for="edit_ktp_dokumen_cust_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_npwp_dokumen_cust_yes">NPWP ?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_npwp_dokumen_cust_yes" name="edit_npwp_dokumen_cust" value="yes">
                            <label for="edit_npwp_dokumen_cust_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_npwp_dokumen_cust_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_npwp_dokumen_cust_no" name="edit_npwp_dokumen_cust" value="no" checked>
                            <label for="edit_npwp_dokumen_cust_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_siup_dokumen_cust_yes">SIUP ?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_siup_dokumen_cust_yes" name="edit_siup_dokumen_cust" value="yes">
                            <label for="edit_siup_dokumen_cust_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_siup_dokumen_cust_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_siup_dokumen_cust_no" name="edit_siup_dokumen_cust" value="no" checked>
                            <label for="edit_siup_dokumen_cust_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_tdp_dokumen_cust_yes">TDP ?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_tdp_dokumen_cust_yes" name="edit_tdp_dokumen_cust" value="yes">
                            <label for="edit_tdp_dokumen_cust_yes" class="custom-control-label">Ya</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="edit_tdp_dokumen_cust_no">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="edit_tdp_dokumen_cust_no" name="edit_tdp_dokumen_cust" value="no" checked>
                            <label for="edit_tdp_dokumen_cust_no" class="custom-control-label">Tidak</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3" id="div_edit_no_ktp" style="display: none;">
                        <div class="form-group">
                          <label for="edit_no_ktp_dokumen_cust">Nomor KTP</label>
                          <input type="text" class="form-control" name="edit_no_ktp_dokumen_cust" id="edit_no_ktp_dokumen_cust" placeholder="Nomor KTP">
                        </div>
                      </div>
                      <div class="col-3" id="div_edit_no_npwp" style="display: none;">
                        <div class="form-group">
                          <label for="edit_no_npwp_dokumen_cust">Nomor NPWP</label>
                          <input type="text" class="form-control" name="edit_no_npwp_dokumen_cust" id="edit_no_npwp_dokumen_cust" placeholder="Nomor NPWP">
                        </div>
                      </div>
                      <div class="col-3" id="div_edit_no_siup" style="display: none;">
                        <div class="form-group">
                          <label for="edit_no_siup_dokumen_cust">Nomor SIUP</label>
                          <input type="text" class="form-control" name="edit_no_siup_dokumen_cust" id="edit_no_siup_dokumen_cust" placeholder="Nomor SIUP">
                        </div>
                      </div>
                      <div class="col-3" id="div_edit_no_tdp" style="display: none;">
                        <div class="form-group">
                          <label for="edit_no_tdp_dokumen_cust">Nomor TDP</label>
                          <input type="text" class="form-control" name="edit_no_tdp_dokumen_cust" id="edit_no_tdp_dokumen_cust" placeholder="Nomor TDP">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3" id="div_edit_image_ktp" style="display: none;">
                        <div class="form-group">
                          <label for="edit_image_ktp_dokumen_cust">Upload Image KTP</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="edit_image_ktp_dokumen_cust" name="edit_image_ktp_dokumen_cust">
                              <label class="custom-file-label" for="edit_image_ktp_dokumen_cust">Choose Image</label>
                            </div>
                          </div>
                        </div>
                        <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
                      </div>
                      <div class="col-3" id="div_edit_image_npwp" style="display: none;">
                        <div class="form-group">
                          <label for="edit_image_npwp_dokumen_cust">Upload Image NPWP</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="edit_image_npwp_dokumen_cust" name="edit_image_npwp_dokumen_cust">
                              <label class="custom-file-label" for="edit_image_npwp_dokumen_cust">Choose Image</label>
                            </div>
                          </div>
                        </div>
                        <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
                      </div>
                      <div class="col-3" id="div_edit_image_siup" style="display: none;">
                        <div class="form-group">
                          <label for="edit_image_siup_dokumen_cust">Upload Image SIUP</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="edit_image_siup_dokumen_cust" name="edit_image_siup_dokumen_cust">
                              <label class="custom-file-label" for="edit_image_siup_dokumen_cust">Choose Image</label>
                            </div>
                          </div>
                        </div>
                        <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
                      </div>
                      <div class="col-3" id="div_edit_image_tdp" style="display: none;">
                        <div class="form-group">
                          <label for="edit_image_tdp_dokumen_cust">Upload Image TDP</label>
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="edit_image_tdp_dokumen_cust" name="edit_image_tdp_dokumen_cust">
                              <label class="custom-file-label" for="edit_image_tdp_dokumen_cust">Choose Image</label>
                            </div>
                          </div>
                        </div>
                        <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3" id="div_view_image_ktp" style="display: none;">
                        <div class="form-group">
                          <a target="_blank" id="lihat_ktp_edit" class="btn btn-primary save-btn-in" style="width: 100%;">Lihat Foto KTP</a>
                        </div>
                      </div>
                      <div class="col-3" id="div_view_image_npwp" style="display: none;">
                        <div class="form-group">
                          <a target="_blank" id="lihat_npwp_edit" class="btn btn-primary save-btn-in" style="width: 100%;">Lihat Foto NPWP</a>
                        </div>
                      </div>
                      <div class="col-3" id="div_view_image_siup" style="display: none;">
                        <div class="form-group">
                          <a target="_blank" id="lihat_siup_edit" class="btn btn-primary save-btn-in" style="width: 100%;">Lihat Foto SIUP</a>
                        </div>
                      </div>
                      <div class="col-3" id="div_view_image_tdp" style="display: none;">
                        <div class="form-group">
                          <a target="_blank" id="lihat_tdp_edit" class="btn btn-primary save-btn-in" style="width: 100%;">Lihat Foto TDP</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="div_edit_hasil_visit">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Hasil Visit / Follow Up</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label for="edit_hasil_visit_question">Hasil Visit</label>
                          <textarea class="form-control" rows="3" name="edit_hasil_visit_question" id="edit_hasil_visit_question" placeholder="Hasil Visit"></textarea>
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
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_quotation">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Buat Quotation</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h5>Customer Receive</h5>
                </div>
                <div class="card-body">
                  <div class="row">
                    <input class="form-control" type="hidden" name="quote_custid" id="quote_custid"/>
                    <input class="form-control" type="hidden" name="quote_id_schedule" id="quote_id_schedule"/>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="quote_nama_cust_receive">Nama</label>
                        <input class="form-control" type="text" name="quote_nama_cust_receive" id="quote_nama_cust_receive" placeholder="Nama" />
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="quote_alamat_cust_receive">Alamat</label>
                        <textarea class="form-control" rows="3" name="quote_alamat_cust_receive" id="quote_alamat_cust_receive" placeholder="Alamat"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="quote_kota_cust_receive">Kota</label>
                        <select id="quote_kota_cust_receive" name="quote_kota_cust_receive" class="form-control select2 city-address" style="width: 100%;">
                        </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="quote_telepon_cust_receive">Telepon</label>
                        <input class="form-control" type="text" name="quote_telepon_cust_receive" id="quote_telepon_cust_receive" placeholder="Telepon" />
                      </div>
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
                    <div class="col-6">
                      <div class="form-group">
                        <label for="quote_tanggal_kirim">Tanggal Kirim</label>
                        <input class="form-control" type="text" name="quote_tanggal_kirim" id="quote_tanggal_kirim" placeholder="Tanggal Kirim" />
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="quote_nomor_po">Nomor PO</label>
                        <input class="form-control" type="text" name="quote_nomor_po" id="quote_nomor_po" placeholder="Nomor PO" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form method="post" class="add_products_form" id="add_products_form" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="row"> 
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <input class="form-control" type="hidden" name="quote_custid_prd" id="quote_custid_prd"/>
                    <div class="row"> 
                      <div class="col-7">
                        <div class="form-group">
                          <label>Products</label>
                          <select id="quote_add_products" name="quote_add_products" class="form-control">
                          </select>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label>Quantity (KG)</label>
                          <input class="form-control" type="text" name="quote_add_quantity" id="quote_add_quantity" placeholder="Quantity (KG)" />
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-success" type="submit" name="btn-products" id="btn-products" value="Add"/>
                        </div>
                      </div>
                    </div>
                    <div>
                      <table id="quote_order_products_table" style="width: 100%;" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Produk</th>
                            <th>Jumlah (KG)</th>
                            <th></th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="quote_keterangan">Keterangan</label>
                        <textarea class="form-control" rows="3" name="quote_keterangan" id="quote_keterangan" placeholder="Keterangan"></textarea>
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
          <button type="submit" class="btn btn-primary" id="btn-orders">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_sorting_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_sorting_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <p style="color: #f00;">* Silahkan Drag dan Drop Data Untuk Mengurutkan, Lalu Klik Save.</p>
            <table id="data_sorting_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>Urutan</th>
                  <th>Tanggal</th>
                  <th>Tipe Cust</th>
                  <th>Customers</th>
                  <th>Perihal</th>
                  <th>Offline?</th>
                  <th>Alamat</th>
                  <th>Waktu</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-save-data" class="btn btn-primary">Save changes</button>
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
          <h4 class="modal-title" id="judul_modal_edit_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <p style="color: #f00;">* Silahkan Drag dan Drop Data Untuk Mengurutkan, Lalu Klik Save.</p>
            <table id="data_edit_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tipe</th>
                  <th>Customers</th>
                  <th>Perihal</th>
                  <th>Offline?</th>
                  <th>Alamat</th>
                  <th>Suspend?</th>
                  <th>Tanggal</th>
                  <th>Alasan</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-edit-data" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_realisasi_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_realisasi_data"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="data_realisasi_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>Tipe</th>
                  <th>Customers</th>
                  <th>Perihal</th>
                  <th>Offline?</th>
                  <th>Done?</th>
                  <th>Hasil</th>
                  <th>Follow Up?</th>
                  <th>Range</th>
                  <th>Penawaran</th>
                  <th>Order</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-realisasi-data" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_poin_delivery">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_poin_delivery"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="data_poin_delivery_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tipe Cust</th>
                  <th>Customers</th>
                  <th>Offline?</th>
                  <th>Perihal</th>
                  <th>Add Poin Delivery?</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-poin-delivery" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_poin_paid">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_modal_poin_paid"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <table id="data_poin_paid_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tipe Cust</th>
                  <th>Customers</th>
                  <th>Offline?</th>
                  <th>Perihal</th>
                  <th>Add Poin Paid?</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button data-token="{{ csrf_token() }}" type="submit" id="btn-poin-paid" class="btn btn-primary">Save changes</button>
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

    $('#tanggal_janji_visit').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#jadwal_tt_pembayaran').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#jadwal_pembayaran').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal_janji_visit').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_jadwal_tt_pembayaran').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_jadwal_pembayaran').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#proses_tanggal_jadwal').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#quote_tanggal_kirim').flatpickr({
      allowInput: true,
      defaultDate: new Date(),
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

    var table = $('#schedule_table').DataTable({
         processing: true,
         serverSide: true,
         responsive: {
          details: {
            type: 'column'
          }
        },
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/view_schedule") }}',
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
            }else if(data.no_status == 3){
              $(row).attr('style', 'background-color:#d4fab6');
            }else if(data.no_status == 5){
              $(row).attr('style', 'background-color:#b6fad1');
            }else if(data.no_status == 6){
              $(row).attr('style', 'background-color:#d4fab6');
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
         data:'tipe_customers',
         name:'tipe_customers',
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
         data:'offline',
         name:'offline',
         className:'dt-center',
         width:'10%'
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
      if(target == '#list_data'){
        $('#schedule_table').DataTable().destroy();
        load_data_schedule();
      }else if(target == '#route_plan'){
        $('#route_plan_table').DataTable().destroy();
        load_data_route_plan();
      }else if(target == '#jadwal_followup'){
        $('#jadwal_followup_table').DataTable().destroy();
        load_data_jadwal_followup();
      }else if(target == '#realisasi_followup'){
        $('#realisasi_followup_table').DataTable().destroy();
        load_data_realisasi_followup();
      }else if(target == '#quotation'){
        $('#quotation_table').DataTable().destroy();
        load_data_quotation();
      }else if(target == '#validasi_quotation'){
        $('#validasi_quotation_table').DataTable().destroy();
        load_data_validasi_quotation();
      }else if(target == '#poin_delivery'){
        $('#poin_delivery_table').DataTable().destroy();
        load_data_poin_delivery();
      }else if(target == '#poin_paid'){
        $('#poin_paid_table').DataTable().destroy();
        load_data_poin_paid();
      }else if(target == '#status_done'){
        $('#status_done_table').DataTable().destroy();
        load_data_done();
      }
    });

    function load_data_schedule(from_date = '', to_date = '')
     {
      table = $('#schedule_table').DataTable({
         processing: true,
         serverSide: true,
         responsive: {
          details: {
            type: 'column'
          }
        },
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/view_schedule") }}',
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
            }else if(data.no_status == 3){
              $(row).attr('style', 'background-color:#d4fab6');
            }else if(data.no_status == 5){
              $(row).attr('style', 'background-color:#b6fad1');
            }else if(data.no_status == 6){
              $(row).attr('style', 'background-color:#d4fab6');
            }
        },
        order: [[7,'desc'],[2,'asc']],
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
         data:'tipe_customers',
         name:'tipe_customers',
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
         data:'offline',
         name:'offline',
         className:'dt-center',
         width:'10%'
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

     function load_data_route_plan(from_date = '', to_date = '')
    {
      table = $('#route_plan_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("schedule/route_plan/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[2,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
          width: '5%',
          className:'dt-center'
        },
        {
          data:'tanggal_schedule',
          name:'tanggal_schedule',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'jumlah_data',
          name:'jumlah_data',
          className:'dt-center'
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

    function load_data_jadwal_followup(from_date = '', to_date = '')
    {
      table = $('#jadwal_followup_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("schedule/jadwal_followup/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[2,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
          width: '5%',
          className:'dt-center'
        },
        {
          data:'tanggal_schedule',
          name:'tanggal_schedule',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'jumlah_data',
          name:'jumlah_data',
          className:'dt-center'
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

    function load_data_realisasi_followup(from_date = '', to_date = '')
    {
      table = $('#realisasi_followup_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("schedule/realisasi_followup/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[2,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
         data:'tipe_customers',
         name:'tipe_customers',
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
         data:'offline',
         name:'offline',
         className:'dt-center',
         width:'10%'
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

    function load_data_quotation(from_date = '', to_date = '')
     {
      table = $('#quotation_table').DataTable({
         processing: true,
         serverSide: true,
         responsive: {
          details: {
            type: 'column'
          }
        },
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/customers_visit/quotation/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
         data:'tanggal_done',
         name:'tanggal_done',
         className:'dt-center',
         width:'12%'
        },
        {
         data:'tipe_customers',
         name:'tipe_customers',
         className:'dt-center',
         width:'12%'
        },
        {
         data:'customers',
         name:'customers',
         className:'dt-center',
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

     function load_data_validasi_quotation(from_date = '', to_date = '')
     {
      table = $('#validasi_quotation_table').DataTable({
         processing: true,
         serverSide: true,
         responsive: {
          details: {
            type: 'column'
          }
        },
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales/customers_visit/quotation/validasi/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
         data:'nomor_quotation',
         name:'nomor_quotation',
         className:'dt-center',
         width:'12%'
        },
        {
         data:'tanggal_quotation',
         name:'tanggal_quotation',
         className:'dt-center',
         width:'12%'
        },
        {
         data:'custname_order',
         name:'custname_order',
         className:'dt-center'
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

    function load_data_poin_delivery(from_date = '', to_date = '')
    {
      table = $('#poin_delivery_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("schedule/poin_delivery/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[2,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
          width: '5%',
          className:'dt-center'
        },
        {
          data:'tanggal_schedule',
          name:'tanggal_schedule',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'jumlah_data',
          name:'jumlah_data',
          className:'dt-center'
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

    function load_data_poin_paid(from_date = '', to_date = '')
    {
      table = $('#poin_paid_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("schedule/poin_paid/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        order: [[2,'desc']],
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
          width: '5%',
          className:'dt-center'
        },
        {
          data:'tanggal_schedule',
          name:'tanggal_schedule',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
          data:'jumlah_data',
          name:'jumlah_data',
          className:'dt-center'
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

    function load_data_done(from_date = '', to_date = '')
    {
      table = $('#status_done_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("schedule/done/table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lrtip',
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
          width: '5%',
          className:'dt-center'
        },
        {
          data:'tanggal_schedule',
          name:'tanggal_schedule',
          className:'dt-center',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          },
          width:'10%'
        },
        {
         data:'tipe_customers',
         name:'tipe_customers',
         className:'dt-center',
         width:'8%'
        },
        {
          data:'customer',
          name:'customer',
          className: 'dt-center',
          width:'15%',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="id_schedule[' + $('<div />').text(row.id_schedule).html() + ']" value="' + $('<div />').text(row.id_schedule).html() + '">' + $('<div />').text(row.customer).html();
          }
        },
        {
          data:'offline',
          name:'offline',
          className: 'dt-center',
          width:'6%'
        },
        {
          data:'perihal',
          name:'perihal',
          className: 'dt-center',
          width:'9%'
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

    function load_data_sorting(tanggal = '')
    {
      table = $('#data_sorting_table').DataTable({
        processing: false,
        serverSide: true,
        paging: false,
        bInfo : false,
        rowReorder: true,
        orderable: false,
        ajax: {
          url:'{{ url("schedule/route_plan/sorting/table") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lrtip',
        columns: [
        {
          data:'order_sort',
          width:'3%',
          className: 'reorder'
        },
        {
          data:'tanggal_schedule',
          name:'tanggal_schedule',
          className: 'dt-center reorder',
          width:'10%',
          render: function ( data, type, full, meta ) {
            return moment(data).format('DD MMM YYYY');
          }
        },
        {
         data:'tipe_customers',
         name:'tipe_customers',
         className:'dt-center reorder',
         width:'10%'
        },
        {
          data:'customer',
          name:'customer',
          className: 'dt-center reorder',
          width:'20%',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="id_schedule[' + $('<div />').text(row.id_schedule).html() + ']" value="' + $('<div />').text(row.id_schedule).html() + '">' + $('<div />').text(row.customer).html();
          }
        },
        {
          data:'perihal',
          name:'perihal',
          className: 'dt-center reorder',
          width:'10%'
        },
        {
          data:'offline',
          name:'offline',
          className: 'dt-center reorder',
          width:'7%'
        },
        {
          data:'alamat',
          name:'alamat',
          className: 'dt-center reorder',
          width:'40%'
        },
        {
          data:'waktu_schedule',
          name:'waktu_schedule',
          className:'dt-center',
          width:'10%',
          render: function ( data, type, row)
          {
            $('[name="waktu_schedule[' + $('<div />').text(row.id_schedule).html() + ']"]').flatpickr({
              allowInput: true,
              enableTime: true,
              noCalendar: true,
              dateFormat: "H:i",
              time_24hr: true,
              disableMobile: true
            });

            return '<input type="text" name="waktu_schedule[' + $('<div />').text(row.id_schedule).html() + ']" value="' + $('<div />').text(row.waktu_schedule).html() + '">';
          }
        }
        ],
        rowReorder: {
          selector : 'td:not(:last-child)',
          dataSrc: 'order_sort',
          update: false
        }
      });
    }

    function load_data_edit(tanggal = '')
    {
      table = $('#data_edit_table').DataTable({
        processing: false,
        serverSide: true,
        paging: false,
        bInfo : false,
        rowReorder: true,
        orderable: false,
        ajax: {
          url:'{{ url("schedule/route_plan/edit/table") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lrtip',
        columns: [
        {
          data:'order_sort',
          className: 'dt-center reorder',
          width:'5%'
        },
        {
         data:'tipe_customers',
         name:'tipe_customers',
         className:'dt-center reorder',
         width:'8%'
        },
        {
          data:'customer',
          name:'customer',
          className: 'dt-center reorder',
          width:'15%',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="id_schedule[' + $('<div />').text(row.id_schedule).html() + ']" value="' + $('<div />').text(row.id_schedule).html() + '">' + $('<div />').text(row.customer).html();
          }
        },
        {
          data:'perihal',
          name:'perihal',
          className: 'dt-center reorder',
          width:'9%'
        },
        {
          data:'offline',
          name:'offline',
          className: 'dt-center reorder',
          width:'6%'
        },
        {
          data:'alamat',
          name:'alamat',
          className: 'dt-center reorder',
          width:'25%'
        },
        {
          data:null,
          name:null,
          className:'dt-center',
          width:'6%',
          render: function ( data, type, row)
          {
            $('[name="suspend[' + $('<div />').text(row.id_schedule).html() + ']"]').change(function(){
              if($(this).is(':checked')) {
                $('[name="edit_jadwal[' + $('<div />').text(row.id_schedule).html() + ']"]').prop("disabled", false);
                $('[name="alasan_suspend[' + $('<div />').text(row.id_schedule).html() + ']"]').prop("disabled", false);
              }else{
                $('[name="edit_jadwal[' + $('<div />').text(row.id_schedule).html() + ']"]').prop("disabled", true);
                $('[name="alasan_suspend[' + $('<div />').text(row.id_schedule).html() + ']"]').prop("disabled", true);
              }
            });

            return '<input type="checkbox" name="suspend[' + $('<div />').text(row.id_schedule).html() + ']" value="1">';
          }
        },
        {
          data:'tanggal_schedule',
          name:'tanggal_schedule',
          className:'dt-center',
          render: function ( data, type, row)
          {
            $('[name="edit_jadwal[' + $('<div />').text(row.id_schedule).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="date" name="edit_jadwal[' + $('<div />').text(row.id_schedule).html() + ']" value="' + $('<div />').text(row.tanggal_schedule).html() + '" style="width:100%;" disabled>';
          },
          width:'10%'
        },
        {
          data:'alasan_suspend',
          name:'alasan_suspend',
          className:'dt-center',
          render: function ( data, type, row)
          {
            if(data == null || data == ''){
              return '<textarea rows="2" name="alasan_suspend[' + $('<div />').text(row.id_schedule).html() + ']" style="width:100%;" disabled></textarea>';
            }else{
              return '<textarea rows="2" name="alasan_suspend[' + $('<div />').text(row.id_schedule).html() + ']" style="width:100%;" disabled>' + $('<div />').text(row.alasan_suspend).html() + '</textarea>';
            }
          },
          width:'25%'
        }
        ],
        rowReorder: {
          selector : 'td:not(:last-child, :nth-child(7), :nth-child(8))',
          dataSrc: 'order_sort',
          update: false
        }
      });
    }

    function load_data_products(custid = '')
    {
      $('#quote_order_products_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Add products you want to buy"
        },
        ajax: {
          url:'{{ url("sales/quotation/products/table") }}',
          data:{custid:custid}
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'produk',
         name:'produk'
       },
       {
         data:'quantity',
         name:'quantity',
         render: $.fn.dataTable.render.number('.', " KG", ','),
         width: '30%'
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       },
       ]
     });
    }

    function validasiQuotation() {
      var nomor_quotation = $('#btn_validasi_quotation').data("id");

      $("#dialog-confirm").html("Quotation Divalidasi?");

      // console.log("Yes");

      $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "Validasi Quotation",
        height: 250,
        width: 400,
        buttons: {
          "Validasi": function() {
            $(this).dialog('close');
            callbackValidasiQuotation(true, nomor_quotation);
          },
          "Tolak": function() {
            $(this).dialog('close');
            callbackValidasiQuotation(false, nomor_quotation);
          }
        }
      });
    };

    $('body').on('click', '#btn_validasi_quotation', validasiQuotation);

    function callbackValidasiQuotation(value, nomor_quotation) {
      if(value) {
        $.ajax({
          type: "GET",
          url: "{{ url('sales/customers_visit/quotation/validasi/valid') }}",
          data: { 'nomor_quotation' : nomor_quotation },
          success: function (data) {
            var oTable = $('#validasi_quotation_table').dataTable(); 
            oTable.fnDraw(false);
            alert("Data Validated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }else{
        $.ajax({
          type: "GET",
          url: "{{ url('sales/customers_visit/quotation/validasi/reject') }}",
          data: { 'nomor_quotation' : nomor_quotation },
          success: function (data) {
            var oTable = $('#validasi_quotation_table').dataTable(); 
            oTable.fnDraw(false);
            alert("Data Rejected");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    }

    function load_data_poin_delivery_detail(tanggal = '')
    {
      table = $('#data_poin_delivery_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("schedule/poin_delivery/detail/table") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lrtip',
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
         data:'tipe_customers',
         name:'tipe_customers',
         className:'dt-center',
         width:'8%'
        },
        {
          data:'customer',
          name:'customer',
          className: 'dt-center',
          width:'15%',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="id_schedule[' + $('<div />').text(row.id_schedule).html() + ']" value="' + $('<div />').text(row.id_schedule).html() + '">' + $('<div />').text(row.customer).html();
          }
        },
        {
          data:'offline',
          name:'offline',
          className: 'dt-center',
          width:'6%'
        },
        {
          data:'perihal',
          name:'perihal',
          className: 'dt-center',
          width:'9%'
        },
        {
          data:null,
          name:null,
          className:'dt-center',
          width:'6%',
          render: function ( data, type, row)
          {
            return '<input type="checkbox" name="delivery[' + $('<div />').text(row.id_schedule).html() + ']" value="1">';
          }
        }
        ]
      });
    }

    function load_data_poin_paid_detail(tanggal = '')
    {
      table = $('#data_poin_paid_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        bInfo : false,
        ajax: {
          url:'{{ url("schedule/poin_paid/detail/table") }}',
          data:{tanggal:tanggal},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lrtip',
        columns: [
        {
          data:'DT_RowIndex',
          name:'DT_RowIndex',
          width: '5%',
          className:'dt-center'
        },
        {
         data:'tipe_customers',
         name:'tipe_customers',
         className:'dt-center',
         width:'8%'
        },
        {
          data:'customer',
          name:'customer',
          className: 'dt-center',
          width:'15%',
          render: function ( data, type, row)
          {
            return '<input type="hidden" name="id_schedule[' + $('<div />').text(row.id_schedule).html() + ']" value="' + $('<div />').text(row.id_schedule).html() + '">' + $('<div />').text(row.customer).html();
          }
        },
        {
          data:'offline',
          name:'offline',
          className: 'dt-center',
          width:'6%'
        },
        {
          data:'perihal',
          name:'perihal',
          className: 'dt-center',
          width:'9%'
        },
        {
          data:null,
          name:null,
          className:'dt-center',
          width:'6%',
          render: function ( data, type, row)
          {
            return '<input type="checkbox" name="paid[' + $('<div />').text(row.id_schedule).html() + ']" value="1">';
          }
        }
        ]
      });
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if(target == '#list_data'){
          $('#schedule_table').DataTable().destroy();
          load_data_schedule(from_date, to_date);
        }else if(target == '#route_plan'){
          $('#route_plan_table').DataTable().destroy();
          load_data_route_plan(from_date, to_date);
        }else if(target == '#jadwal_followup'){
          $('#jadwal_followup_table').DataTable().destroy();
          load_data_jadwal_followup(from_date, to_date);
        }else if(target == '#realisasi_followup'){
          $('#realisasi_followup_table').DataTable().destroy();
          load_data_realisasi_followup(from_date, to_date);
        }else if(target == '#quotation'){
          $('#quotation_table').DataTable().destroy();
          load_data_quotation(from_date, to_date);
        }else if(target == '#validasi_quotation'){
          $('#validasi_quotation_table').DataTable().destroy();
          load_data_validasi_quotation(from_date, to_date);
        }else if(target == '#poin_delivery'){
          $('#poin_delivery_table').DataTable().destroy();
          load_data_poin_delivery(from_date, to_date);
        }else if(target == '#poin_paid'){
          $('#poin_paid_table').DataTable().destroy();
          load_data_poin_paid(from_date, to_date);
        }
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(target == '#list_data'){
        $('#schedule_table').DataTable().destroy();
        load_data_schedule();
      }else if(target == '#route_plan'){
        $('#route_plan_table').DataTable().destroy();
        load_data_route_plan();
      }else if(target == '#jadwal_followup'){
        $('#jadwal_followup_table').DataTable().destroy();
        load_data_jadwal_followup();
      }else if(target == '#realisasi_followup'){
        $('#realisasi_followup_table').DataTable().destroy();
        load_data_realisasi_followup();
      }else if(target == '#quotation'){
        $('#quotation_table').DataTable().destroy();
        load_data_quotation();
      }else if(target == '#validasi_quotation'){
        $('#validasi_quotation_table').DataTable().destroy();
        load_data_validasi_quotation();
      }else if(target == '#poin_delivery'){
        $('#poin_delivery_table').DataTable().destroy();
        load_data_poin_delivery();
      }else if(target == '#poin_paid'){
        $('#poin_paid_table').DataTable().destroy();
        load_data_poin_paid();
      }
    });

    $('body').on('click', '#make_schedule_offline', function () {
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
        $('#select_leads').hide();
        $('#select_kompetitor').hide();
      });

      $('#btn_exist_customer').click(function(){
        var tipe = $('#tipe_customers').val();
        $('#new_customers').hide();

        if(tipe == 1){
          $('#select_customers').show();
          $('#select_leads').hide();
          $('#select_kompetitor').hide();
        }else if(tipe == 2){
          $('#select_leads').show();
           $('#select_customers').hide();
           $('#select_kompetitor').hide();
        }else if(tipe == 3){
          $('#select_kompetitor').show();
          $('#select_customers').hide();
          $('#select_leads').hide();
        }
      });

      $("#tipe_customers").change(function() {
        $('#select_kompetitor').hide();
        $('#select_customers').hide();
        $('#select_leads').hide();
        $('#new_customers').hide();
      });
    });

    $('body').on('click', '#realisasi-data', function () {
      var nomor_visit = $(this).data("id");
      var custid = $(this).data("custid");
      var tipe = $(this).data("tipe");

      $('#custid_question').val(custid);
      $('#id_schedule_question').val(nomor_visit);
      $('#tipe_customer_question').val(tipe);

      var url_pic = "{{ url('sales/schedule/detail_customers/custid/tipe') }}";
      url_pic = url_pic.replace('custid', enc(custid.toString()));
      url_pic = url_pic.replace('tipe', enc(tipe.toString()));
      $.get(url_pic, function (data) {
        if(data.nama_cp == null || data.jabatan_cp == null || data.bidang_usaha == null){
          $("#div_data_customer").show();
        }else{
          $("#div_data_customer").hide();
        }
      })

      var url = "{{ url('get_products') }}";
      $.get(url, function (data_products) {
        $('#tipe_permintaan_sample').children().remove().end().append('<option value="" selected>Choose Products</option>');
        $('#tipe_penggunaan_kalsium').children().remove().end().append('<option value="" selected>Choose Products</option>');
        $.each(data_products, function(k, v) {
          $('#tipe_permintaan_sample').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
          $('#tipe_penggunaan_kalsium').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
        });
      })

      $("#kegiatan_question").change(function() {
        if($(this).val() == 1){
          $("#div_pengenalan").show();
          $("#div_janji_visit").show();
          $("#div_latar_belakang").hide();
          $("#div_penggunaan_kalsium").hide();
          $("#div_permintaan_sample").hide();
          $("#div_penawaran_harga").hide();
        }else if($(this).val() == 2){
          $("#div_pengenalan").show();
          $("#div_janji_visit").hide();
          $("#div_latar_belakang").show();
          $("#div_penggunaan_kalsium").show();
          $("#div_permintaan_sample").show();
          $("#div_penawaran_harga").show();
        }else if($(this).val() == 3){
          $("#div_pengenalan").hide();
          $("#div_janji_visit").hide();
          $("#div_latar_belakang").show();
          $("#div_penggunaan_kalsium").show();
          $("#div_permintaan_sample").show();
          $("#div_penawaran_harga").show();
        }else if($(this).val() == 4){
          $("#div_pengenalan").hide();
          $("#div_janji_visit").hide();
          $("#div_latar_belakang").show();
          $("#div_penggunaan_kalsium").show();
          $("#div_permintaan_sample").show();
          $("#div_penawaran_harga").show();
        }
      });

      $('input:radio[name="tawar_penawaran_harga"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $('#div_nomor_penawaran_harga').show();
          $("#div_po_dokumen").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $('#div_nomor_penawaran_harga').hide();
          $("#div_po_dokumen").hide();
        }
      });

      $('input:radio[name="pembayaran_penawaran_harga"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_pembayaran").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_pembayaran").hide();
        }
      });

      $('input:radio[name="cash_top_pembayaran"]').change( function(){
        if($(this).is(':checked') && $(this).val() == '1') {
          $("#div_tempat_tt").hide();
          $("#div_jadwal_tt").hide();
          $("#div_detail_pembayaran").hide();
        }else if($(this).is(':checked') && $(this).val() == '2'){
          $("#div_tempat_tt").show();
          $("#div_jadwal_tt").show();
          $("#div_detail_pembayaran").show();
        }
      });

      $('input:radio[name="ktp_dokumen_cust"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_no_ktp").show();
          $("#div_image_ktp").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_no_ktp").hide();
          $("#div_image_ktp").hide();
        }
      });

      $('input:radio[name="npwp_dokumen_cust"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_no_npwp").show();
          $("#div_image_npwp").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_no_npwp").hide();
          $("#div_image_npwp").hide();
        }
      });

      $('input:radio[name="siup_dokumen_cust"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_no_siup").show();
          $("#div_image_siup").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_no_siup").hide();
          $("#div_image_siup").hide();
        }
      });

      $('input:radio[name="tdp_dokumen_cust"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_no_tdp").show();
          $("#div_image_tdp").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_no_tdp").hide();
          $("#div_image_tdp").hide();
        }
      });

      $("#image_ktp_dokumen_cust").on("change", function (e) {
        var validExtensions = ['jpg','pdf','jpeg'];

        var files = e.currentTarget.files;
        if(files.length > 0){
          var fileSize = ((files[0].size/1024)/1024).toFixed(4);
          var fileName = files[0].name;
          var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

          console.log(e.currentTarget.value);
          if ($.inArray(fileNameExt, validExtensions) == -1) {
            e.currentTarget.value = '';
            $('#image_ktp_dokumen_cust').next('label').html('Choose file');
            alert("Only these file types are accepted : " + validExtensions.join(', '));
          }
          if(fileSize > 2){
            e.currentTarget.value = '';
            $('#image_ktp_dokumen_cust').next('label').html('Choose file');
            alert('Max File Size is 2 MB');
          }
        }
      });

      $("#image_npwp_dokumen_cust").on("change", function (e) {
        var validExtensions = ['jpg','pdf','jpeg'];

        var files = e.currentTarget.files;
        if(files.length > 0){
          var fileSize = ((files[0].size/1024)/1024).toFixed(4);
          var fileName = files[0].name;
          var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

          console.log(e.currentTarget.value);
          if ($.inArray(fileNameExt, validExtensions) == -1) {
            e.currentTarget.value = '';
            $('#image_npwp_dokumen_cust').next('label').html('Choose file');
            alert("Only these file types are accepted : " + validExtensions.join(', '));
          }
          if(fileSize > 2){
            e.currentTarget.value = '';
            $('#image_npwp_dokumen_cust').next('label').html('Choose file');
            alert('Max File Size is 2 MB');
          }
        }
      });

      $("#image_siup_dokumen_cust").on("change", function (e) {
        var validExtensions = ['jpg','pdf','jpeg'];

        var files = e.currentTarget.files;
        if(files.length > 0){
          var fileSize = ((files[0].size/1024)/1024).toFixed(4);
          var fileName = files[0].name;
          var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

          console.log(e.currentTarget.value);
          if ($.inArray(fileNameExt, validExtensions) == -1) {
            e.currentTarget.value = '';
            $('#image_siup_dokumen_cust').next('label').html('Choose file');
            alert("Only these file types are accepted : " + validExtensions.join(', '));
          }
          if(fileSize > 2){
            e.currentTarget.value = '';
            $('#image_siup_dokumen_cust').next('label').html('Choose file');
            alert('Max File Size is 2 MB');
          }
        }
      });

      $("#image_tdp_dokumen_cust").on("change", function (e) {
        var validExtensions = ['jpg','pdf','jpeg'];

        var files = e.currentTarget.files;
        if(files.length > 0){
          var fileSize = ((files[0].size/1024)/1024).toFixed(4);
          var fileName = files[0].name;
          var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

          console.log(e.currentTarget.value);
          if ($.inArray(fileNameExt, validExtensions) == -1) {
            e.currentTarget.value = '';
            $('#image_tdp_dokumen_cust').next('label').html('Choose file');
            alert("Only these file types are accepted : " + validExtensions.join(', '));
          }
          if(fileSize > 2){
            e.currentTarget.value = '';
            $('#image_tdp_dokumen_cust').next('label').html('Choose file');
            alert('Max File Size is 2 MB');
          }
        }
      });
    });

    $('body').on('click', '#btn-save-excel', function () {
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');

      var url = '{{ url("customer_visit/laporan_bulanan/from_date/to_date") }}';
      url = url.replace('from_date', enc(from_date.toString()));
      url = url.replace('to_date', enc(to_date.toString()));
      $('#btn-save-excel').attr('href', url);
      window.location = url;
    });

    $('body').on('click', '#make_schedule_online', function () {
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
        $('#select_leads_online').hide();
        $('#select_kompetitor_online').hide();
      });

      $('#btn_exist_customer_online').click(function(){
        var tipe = $('#tipe_customers_online').val();
        $('#new_customers_online').hide();

        if(tipe == 1){
          $('#select_customers_online').show();
          $('#select_leads_online').hide();
          $('#select_kompetitor_online').hide();
        }else if(tipe == 2){
          $('#select_leads_online').show();
           $('#select_customers_online').hide();
           $('#select_kompetitor_online').hide();
        }else if(tipe == 3){
          $('#select_kompetitor_online').show();
          $('#select_customers_online').hide();
          $('#select_leads_online').hide();
        }
      });

      $("#tipe_customers_online").change(function() {
        $('#select_kompetitor_online').hide();
        $('#select_customers_online').hide();
        $('#select_leads_online').hide();
        $('#new_customers_online').hide();
      });
    });

    $('body').on('click', '#btn_quotation', function () {
      var id_schedule = $(this).data("id");
      var custid = $(this).data("cust");

      $('#quote_order_products_table').DataTable().destroy();
      load_data_products(custid);

      $('#quote_custid').val(custid);
      $('#quote_id_schedule').val(id_schedule);
      $('#quote_custid_prd').val(custid);

      var url = "{{ url('get_products') }}";
      $.get(url, function (data_products) {
        $('#quote_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
        $.each(data_products, function(k, v) {
          $('#quote_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
        });
      })
    });

    $('body').on('click', '#sorting-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_sorting_data").innerHTML = "Data Tanggal " + tanggal;
      $('#data_sorting_table').DataTable().destroy();
      load_data_sorting(tanggal);

      table.on('row-reorder', function ( e, diff, edit) {
        for (var i = 0; i < diff.length; i++) {
          $(diff[i].node).attr('style', 'background-color:#fffeab');
          var rowData = table.row( diff[i].node ).data();
          $.get('{{ url("schedule/route_plan/sorting/save") }}', {
            oldData: rowData['order_sort'],
            id_schedule : rowData['id_schedule'],
            newData: diff[i].newData
          }, function(data, status) {
            $('#data_sorting_table').DataTable().destroy();
            load_data_sorting(tanggal);
          });
        }
      });
    });

    $('body').on('click', '#edit-data', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_edit_data").innerHTML = "Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#data_edit_table').DataTable().destroy();
      load_data_edit(tanggal);

      table.on('row-reorder', function ( e, diff, edit) {
        for (var i = 0; i < diff.length; i++) {
          $(diff[i].node).attr('style', 'background-color:#fffeab');
          var rowData = table.row( diff[i].node ).data();
          $.get('{{ url("schedule/route_plan/edit/save") }}', {
            oldData: rowData['order_sort'],
            id_schedule : rowData['id_schedule'],
            newData: diff[i].newData
          }, function(data, status) {
            $('#data_edit_table').DataTable().destroy();
            load_data_edit(tanggal);
          });
        }
      });
    });

    $('body').on('click', '#poin-delivery', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_poin_delivery").innerHTML = "Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#data_poin_delivery_table').DataTable().destroy();
      load_data_poin_delivery_detail(tanggal);
    });

    $('body').on('click', '#poin-paid', function () {
      var tanggal = $(this).data("id");

      document.getElementById("judul_modal_poin_paid").innerHTML = "Data Tanggal " + moment(tanggal).format('DD MMM YYYY');
      $('#data_poin_paid_table').DataTable().destroy();
      load_data_poin_paid_detail(tanggal);
    });

    $('body').on('click', '#btn_proses_schedule', function () {
      var id_schedule = $(this).data('id');
      $("#proses_id_schedule").val(id_schedule);

      $("#proses_status").change(function() {
        if($(this).val() == 2){
          $("#div_result").hide();
          $("#div_suspend").show();
        }else if($(this).val() == 3){
          $("#div_result").show();
          $("#div_suspend").hide();
        }else{
          $("#div_result").hide();
          $("#div_suspend").hide();
        }
      });

      $("#follow_up").change(function() {
        if($(this).val() == 1){
          $("#div_range_follow_up").show();
        }else if($(this).val() == 2){
          $("#div_range_follow_up").hide();
        }else{
          $("#div_range_follow_up").hide();
        }
      });
    });

    $('body').on('click', '#btn_view_schedule', function () {
      var nomor = $(this).data("id");

      document.getElementById("title_lihat_detail_schedule").innerHTML = "Detail Schedule No " + nomor;
      var url = "{{ url('sales/detail_schedule/id_schedule') }}";
      url = url.replace('id_schedule', enc(nomor.toString()));
      $('#td_id_schedule').html('');
      $('#td_company').html('');
      $('#td_tipe_customers').html('');
      $('#td_customers').html('');
      $('#td_perihal').html('');
      $('#td_offline').html('');
      $('#td_keterangan').html('');
      $('#td_alasan_suspend').html('');
      $('#td_tanggal_input').html('');
      $('#td_tanggal_schedule').html('');
      $('#td_tanggal_done').html('');
      $('#td_status').html('');
      $.get(url, function (data) {
        $('#td_id_schedule').html(data.id_schedule);
        $('#td_company').html(data.company);
        $('#td_tipe_customers').html(data.tipe_customers);
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
        if(data.alasan_suspend == null){
          $('#td_alasan_suspend').html('----');
        }else{
          $('#td_alasan_suspend').html(data.alasan_suspend);
        }
        $('#td_tanggal_input').html(data.tanggal_input);
        if(data.waktu_schedule == null){
          $('#td_tanggal_schedule').html(data.tanggal_schedule);
        }else{
          $('#td_tanggal_schedule').html(data.tanggal_schedule + ' ' + data.waktu_schedule);
        }
        if(data.tanggal_done == null){
          $('#td_tanggal_done').html('----');
        }else{
          $('#td_tanggal_done').html(data.tanggal_done);
        }
        $('#td_status').html(data.status);
      })
    });

    $('body').on('click', '#btn_view_question', function () {
      var nomor = $(this).data("id");

      document.getElementById("title_lihat_detail_question").innerHTML = "Detail Question No " + nomor;
      var url = "{{ url('sales/detail_question/id_schedule') }}";
      url = url.replace('id_schedule', enc(nomor.toString()));
      $.get(url, function (data) {
        if(data.kegiatan == null || data.kegiatan == ''){
          $('#td_question_kegiatan').html('---');
        }else{
          $('#td_question_kegiatan').html(data.kegiatan);
        }
        if(data.no_kegiatan == 1){
          $('#div_view_janji_visit').show();
        }else{
          $('#div_view_janji_visit').hide();
        }
        if(data.hasil_visit == null || data.hasil_visit == ''){
          $('#td_question_hasil_visit').html('---');
        }else{
          $('#td_question_hasil_visit').html(data.hasil_visit);
        }
        if(data.company_profile == 'yes'){
          $('#td_question_company_profile').html('Iya');
        }else{
          $('#td_question_company_profile').html('Tidak');
        }
        if(data.pengenalan_produk == 'yes'){
          $('#td_question_pengenalan_produk').html('Iya');
        }else{
          $('#td_question_pengenalan_produk').html('Tidak');
        }
        if(data.sumber_kenal_dsgm == null || data.sumber_kenal_dsgm == ''){
          $('#td_question_sumber_kenal_dsgm').html('---');
        }else{
          $('#td_question_sumber_kenal_dsgm').html(data.sumber_kenal_dsgm);
        }
        if(data.nomor_surat_pengenalan == null || data.nomor_surat_pengenalan == ''){
          $('#td_question_nomor_surat_pengenalan').html('---');
        }else{
          $('#td_question_nomor_surat_pengenalan').html(data.nomor_surat_pengenalan);
        }
        if(data.nama_janji_visit == null || data.nama_janji_visit == ''){
          $('#td_question_nama_janji').html('---');
        }else{
          $('#td_question_nama_janji').html(data.nama_janji_visit);
        }
        if(data.pic_janji_visit == null || data.pic_janji_visit == ''){
          $('#td_question_pic_janji').html('---');
        }else{
          $('#td_question_pic_janji').html(data.pic_janji_visit);
        }
        if(data.jabatan_janji_visit == null || data.jabatan_janji_visit == ''){
          $('#td_question_jabatan_janji').html('---');
        }else{
          $('#td_question_jabatan_janji').html(data.jabatan_janji_visit);
        }
        if(data.alamat_janji_visit == null || data.alamat_janji_visit == ''){
          $('#td_question_alamat_janji').html('---');
        }else{
          $('#td_question_alamat_janji').html(data.alamat_janji_visit);
        }
        if(data.tanggal_janji_visit == null || data.tanggal_janji_visit == ''){
          $('#td_question_tanggal_janji').html('---');
        }else{
          $('#td_question_tanggal_janji').html(data.tanggal_janji_visit);
        }
        if(data.bisnis_perusahaan == null || data.bisnis_perusahaan == ''){
          $('#td_question_bisnis_cust').html('---');
        }else{
          $('#td_question_bisnis_cust').html(data.bisnis_perusahaan);
        }
        if(data.owner_perusahaan == null || data.owner_perusahaan == ''){
          $('#td_question_owner').html('---');
        }else{
          $('#td_question_owner').html(data.owner_perusahaan);
        }
        if(data.tahun_berdiri_perusahaan == null || data.tahun_berdiri_perusahaan == ''){
          $('#td_question_tahun_berdiri').html('---');
        }else{
          $('#td_question_tahun_berdiri').html(data.tahun_berdiri_perusahaan);
        }
        if(data.jenis_usaha_perusahaan == null || data.jenis_usaha_perusahaan == ''){
          $('#td_question_jenis_usaha').html('---');
        }else{
          $('#td_question_jenis_usaha').html(data.jenis_usaha_perusahaan);
        }
        if(data.jangkauan_wilayah_perusahaan == null || data.jangkauan_wilayah_perusahaan == ''){
          $('#td_question_jangkauan_wilayah').html('---');
        }else{
          $('#td_question_jangkauan_wilayah').html(data.jangkauan_wilayah_perusahaan);
        }
        if(data.top_cust_perusahaan == null || data.top_cust_perusahaan == ''){
          $('#td_question_top_cust').html('---');
        }else{
          $('#td_question_top_cust').html(data.top_cust_perusahaan);
        }
        if(data.tipe_kalsium == null || data.tipe_kalsium == ''){
          $('#td_question_tipe_penggunaan_kalsium').html('---');
        }else{
          $('#td_question_tipe_penggunaan_kalsium').html(data.tipe_kalsium);
        }
        if(data.qty_kalsium == null || data.qty_kalsium == ''){
          $('#td_question_qty_penggunaan_kalsium').html('---');
        }else{
          $('#td_question_qty_penggunaan_kalsium').html(data.qty_kalsium);
        }
        if(data.kegunaan_kalsium == null || data.kegunaan_kalsium == ''){
          $('#td_question_kegunaan_penggunaan_kalsium').html('---');
        }else{
          $('#td_question_kegunaan_penggunaan_kalsium').html(data.kegunaan_kalsium);
        }
        if(data.merk_kompetitor == null || data.merk_kompetitor == ''){
          $('#td_question_merk_kompetitor').html('---');
        }else{
          $('#td_question_merk_kompetitor').html(data.merk_kompetitor);
        }
        if(data.harga_kompetitor == null || data.harga_kompetitor == ''){
          $('#td_question_harga_kompetitor').html('---');
        }else{
          $('#td_question_harga_kompetitor').html(data.harga_kompetitor);
        }
        if(data.pengiriman_kalsium == null || data.pengiriman_kalsium == ''){
          $('#td_question_pengiriman_penggunaan_kalsium').html('---');
        }else{
          $('#td_question_pengiriman_penggunaan_kalsium').html(data.pengiriman_kalsium);
        }
        if(data.pembayaran_supplier == null || data.pembayaran_supplier == ''){
          $('#td_question_pembayaran_supplier').html('---');
        }else{
          $('#td_question_pembayaran_supplier').html(data.pembayaran_supplier);
        }
        if(data.tipe_sample == null || data.tipe_sample == ''){
          $('#td_question_tipe_permintaan_sample').html('---');
        }else{
          $('#td_question_tipe_permintaan_sample').html(data.tipe_sample);
        }
        if(data.qty_sample == null || data.qty_sample == ''){
          $('#td_question_qty_permintaan_sample').html('---');
        }else{
          $('#td_question_qty_permintaan_sample').html(data.qty_sample);
        }
        if(data.feedback_uji_sample == null || data.feedback_uji_sample == ''){
          $('#td_question_feedback_permintaan_sample').html('---');
        }else{
          $('#td_question_feedback_permintaan_sample').html(data.feedback_uji_sample);
        }
        if(data.penawaran_harga == 'yes'){
          $('#td_question_penawaran_harga').html('Ya');
          $('#div_po_dokumen_cust').show();
        }else{
          $('#td_question_penawaran_harga').html('Tidak');
          $('#div_po_dokumen_cust').hide();
        }
        if(data.nego_harga == 'yes'){
          $('#td_question_nego_harga').html('Ya');
        }else{
          $('#td_question_nego_harga').html('Tidak');
        }
        if(data.nominal_harga == null || data.nominal_harga == ''){
          $('#td_question_nominal_harga').html('---');
        }else{
          $('#td_question_nominal_harga').html(data.nominal_harga);
        }
        if(data.nomor_penawaran == null || data.nomor_penawaran == ''){
          $('#td_question_nomor_penawaran').html('---');
        }else{
          $('#td_question_nomor_penawaran').html(data.nomor_penawaran);
        }
        if(data.pembayaran == 'yes'){
          $('#td_question_pembayaran').html('Ya');
          $('#div_view_pembayaran').show();
        }else{
          $('#td_question_pembayaran').html('Tidak');
          $('#div_view_pembayaran').hide();
        }
        if(data.pengiriman == 'yes'){
          $('#td_question_pengiriman').html('Ya');
        }else{
          $('#td_question_pengiriman').html('Tidak');
        }
        if(data.dokumen_pengiriman == null || data.dokumen_pengiriman == ''){
          $('#td_question_dokumen_pengiriman').html('---');
        }else{
          $('#td_question_dokumen_pengiriman').html(data.dokumen_pengiriman);
        }
        if(data.cash_top == null || data.cash_top == ''){
          $('#td_question_tipe_pembayaran').html('---');
        }else{
          $('#td_question_tipe_pembayaran').html(data.cash_top);
        }
        if(data.tempat_tukar_tt == null || data.tempat_tukar_tt == ''){
          $('#td_question_tempat_tukar_tt').html('---');
        }else{
          $('#td_question_tempat_tukar_tt').html(data.tempat_tukar_tt);
        }
        if(data.jadwal_tukar_tt == null || data.jadwal_tukar_tt == ''){
          $('#td_question_jadwal_tukar_tt').html('---');
        }else{
          $('#td_question_jadwal_tukar_tt').html(data.jadwal_tukar_tt);
        }
        if(data.jadwal_pembayaran == null || data.jadwal_pembayaran == ''){
          $('#td_question_jadwal_pembayaran').html('---');
        }else{
          $('#td_question_jadwal_pembayaran').html(data.jadwal_pembayaran);
        }
        if(data.metode_pembayaran == null || data.metode_pembayaran == ''){
          $('#td_question_metode_pembayaran').html('---');
        }else{
          $('#td_question_metode_pembayaran').html(data.metode_pembayaran);
        }
        if(data.pic_penagihan == null || data.pic_penagihan == ''){
          $('#td_question_pic_penagihan').html('---');
        }else{
          $('#td_question_pic_penagihan').html(data.pic_penagihan);
        }
        if(data.nomor_po == null || data.nomor_po == ''){
          $('#td_question_nomor_po').html('---');
        }else{
          $('#td_question_nomor_po').html(data.nomor_po);
        }
        if(data.nomor_ktp == null || data.nomor_ktp == ''){
          $('#td_question_nomor_ktp').html('---');
        }else{
          if(data.image_ktp){
            $('#td_question_nomor_ktp').html(data.nomor_ktp + ' (<a target="_blank" href="' + '../data_file/' + data.image_ktp + '">Lihat Lampiran</a>)');
          }else{
            $('#td_question_nomor_ktp').html(data.nomor_ktp);
          }
        }
        if(data.nomor_npwp == null || data.nomor_npwp == ''){
          $('#td_question_nomor_npwp').html('---');
        }else{
          if(data.image_npwp){
            $('#td_question_nomor_npwp').html(data.nomor_npwp + ' (<a target="_blank" href="' + '../data_file/' + data.image_npwp + '">Lihat Lampiran</a>)');
          }else{
            $('#td_question_nomor_npwp').html(data.nomor_npwp);
          }
        }
        if(data.nomor_siup == null || data.nomor_siup == ''){
          $('#td_question_nomor_siup').html('---');
        }else{
          if(data.image_siup){
            $('#td_question_nomor_siup').html(data.nomor_siup + ' (<a target="_blank" href="' + '../data_file/' + data.image_siup + '">Lihat Lampiran</a>)');
          }else{
            $('#td_question_nomor_siup').html(data.nomor_siup);
          }
        }
        if(data.nomor_tdp == null || data.nomor_tdp == ''){
          $('#td_question_nomor_tdp').html('---');
        }else{
          if(data.image_tdp){
            $('#td_question_nomor_tdp').html(data.nomor_tdp + ' (<a target="_blank" href="' + '../data_file/' + data.image_tdp + '">Lihat Lampiran</a>)');
          }else{
            $('#td_question_nomor_tdp').html(data.nomor_tdp);
          }
        }
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

      $("#edit_tipe_customers").change(function() {
        if($(this).val() == 1){
          $('#edit_select_customers').show();
          $('#edit_select_leads').hide();
          $('#edit_select_kompetitor').hide();
        }else if($(this).val() == 2){
          $('#edit_select_leads').show();
          $('#edit_select_customers').hide();
          $('#edit_select_kompetitor').hide();
        }else if($(this).val() == 3){
          $('#edit_select_kompetitor').show();
          $('#edit_select_customers').hide();
          $('#edit_select_leads').hide();
        }
      });

      var url = "{{ url('sales/detail_schedule/id_schedule') }}";
      url = url.replace('id_schedule', enc(nomor.toString()));
      $('#edit_id_schedule').val('');
      $('#edit_company').val('');
      $('#edit_tipe_customer').val('');
      $('#edit_customers').val('');
      $('#edit_leads').val('');
      $('#edit_kompetitor').val('');
      $('#edit_perihal').val('');
      $('#edit_offline').val('');
      $('#edit_keterangan').html('');
      $('#edit_alasan_suspend').html('');
      $('#edit_tanggal_jadwal').val('');
      $.get(url, function (data) {
        if(data.no_status == 1){
          $('#div_alasan_suspend').hide();
        }else{
          $('#div_alasan_suspend').show();
        }
        $('#edit_id_schedule').val(nomor);
        $('#edit_tanggal_jadwal').val(data.tanggal_schedule);
        $('#edit_company').val(data.company);
        $('#edit_tipe_customers').val(data.no_tipe_customers).trigger('change');
        $('#edit_perihal').val(data.perihal);
        $('#edit_offline').val(data.offline);
        $('#edit_keterangan').html(data.keterangan);
        $('#edit_alasan_suspend').html(data.alasan_suspend);
        if(data.no_tipe_customers == 1){
          $("#edit_customers").append('<option value="' + data.customers + '">' + data.nama + '</option>');
          $("#edit_customers").val(data.customers);
          $("#edit_customers").trigger('change');
        }else if(data.no_tipe_customers == 2){
          $("#edit_leads").append('<option value="' + data.customers + '">' + data.nama + '</option>');
          $("#edit_leads").val(data.customers);
          $("#edit_leads").trigger('change');
        }else if(data.no_tipe_customers == 3){
          $("#edit_kompetitor").append('<option value="' + data.customers + '">' + data.nama + '</option>');
          $("#edit_kompetitor").val(data.customers);
          $("#edit_kompetitor").trigger('change');
        }
      })
    });

    $('body').on('click', '#edit-realisasi-data', function () {
      var nomor_visit = $(this).data("id");
      var custid = $(this).data("custid");

      $('#edit_custid_question').val(custid);
      $('#edit_id_schedule_question').val(nomor_visit);

      var url_prd = "{{ url('get_products') }}";
      $.get(url_prd, function (data_products) {
        $('#edit_tipe_permintaan_sample').children().remove().end().append('<option value="" selected>Choose Products</option>');
        $('#edit_tipe_penggunaan_kalsium').children().remove().end().append('<option value="" selected>Choose Products</option>');
        $.each(data_products, function(k, v) {
          $('#edit_tipe_permintaan_sample').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
          $('#edit_tipe_penggunaan_kalsium').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
        });
      })

      $("#edit_kegiatan_question").change(function() {
        if($(this).val() == 1){
          $("#div_edit_pengenalan").show();
          $("#div_edit_janji_visit").show();
          $("#div_edit_latar_belakang").hide();
          $("#div_edit_penggunaan_kalsium").hide();
          $("#div_edit_permintaan_sample").hide();
          $("#div_edit_penawaran_harga").hide();
        }else if($(this).val() == 2){
          $("#div_edit_pengenalan").show();
          $("#div_edit_janji_visit").hide();
          $("#div_edit_latar_belakang").show();
          $("#div_edit_penggunaan_kalsium").show();
          $("#div_edit_permintaan_sample").show();
          $("#div_edit_penawaran_harga").show();
        }else if($(this).val() == 3){
          $("#div_edit_pengenalan").hide();
          $("#div_edit_janji_visit").hide();
          $("#div_edit_latar_belakang").show();
          $("#div_edit_penggunaan_kalsium").show();
          $("#div_edit_permintaan_sample").show();
          $("#div_edit_penawaran_harga").show();
        }else if($(this).val() == 4){
          $("#div_edit_pengenalan").hide();
          $("#div_edit_janji_visit").hide();
          $("#div_edit_latar_belakang").show();
          $("#div_edit_penggunaan_kalsium").show();
          $("#div_edit_permintaan_sample").show();
          $("#div_edit_penawaran_harga").show();
        }
      });

      $('input:radio[name="edit_tawar_penawaran_harga"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $('#div_edit_nomor_penawaran_harga').show();
          $("#div_edit_po_dokumen").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $('#div_edit_nomor_penawaran_harga').hide();
          $("#div_edit_po_dokumen").hide();
        }
      });

      $('input:radio[name="edit_pembayaran_penawaran_harga"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_edit_pembayaran").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_edit_pembayaran").hide();
        }
      });

      $('input:radio[name="edit_cash_top_pembayaran"]').change( function(){
        if($(this).is(':checked') && $(this).val() == '1') {
          $("#div_edit_tempat_tt").hide();
          $("#div_edit_jadwal_tt").hide();
          $("#div_edit_detail_pembayaran").hide();
        }else if($(this).is(':checked') && $(this).val() == '2'){
          $("#div_edit_tempat_tt").show();
          $("#div_edit_jadwal_tt").show();
          $("#div_edit_detail_pembayaran").show();
        }
      });

      $('input:radio[name="edit_ktp_dokumen_cust"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_edit_no_ktp").show();
          $("#div_edit_image_ktp").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_edit_no_ktp").hide();
          $("#div_edit_image_ktp").hide();
        }
      });

      $('input:radio[name="edit_npwp_dokumen_cust"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_edit_no_npwp").show();
          $("#div_edit_image_npwp").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_edit_no_npwp").hide();
          $("#div_edit_image_npwp").hide();
        }
      });

      $('input:radio[name="edit_siup_dokumen_cust"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_edit_no_siup").show();
          $("#div_edit_image_siup").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_edit_no_siup").hide();
          $("#div_edit_image_siup").hide();
        }
      });

      $('input:radio[name="edit_tdp_dokumen_cust"]').change( function(){
        if($(this).is(':checked') && $(this).val() == 'yes') {
          $("#div_edit_no_tdp").show();
          $("#div_edit_image_tdp").show();
        }else if($(this).is(':checked') && $(this).val() == 'no'){
          $("#div_edit_no_tdp").hide();
          $("#div_edit_image_tdp").hide();
        }
      });

      $("#edit_image_ktp_dokumen_cust").on("change", function (e) {
        var validExtensions = ['jpg','pdf','jpeg'];

        var files = e.currentTarget.files;
        if(files.length > 0){
          var fileSize = ((files[0].size/1024)/1024).toFixed(4);
          var fileName = files[0].name;
          var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

          console.log(e.currentTarget.value);
          if ($.inArray(fileNameExt, validExtensions) == -1) {
            e.currentTarget.value = '';
            $('#edit_image_ktp_dokumen_cust').next('label').html('Choose file');
            alert("Only these file types are accepted : " + validExtensions.join(', '));
          }
          if(fileSize > 2){
            e.currentTarget.value = '';
            $('#edit_image_ktp_dokumen_cust').next('label').html('Choose file');
            alert('Max File Size is 2 MB');
          }
        }
      });

      $("#edit_image_npwp_dokumen_cust").on("change", function (e) {
        var validExtensions = ['jpg','pdf','jpeg'];

        var files = e.currentTarget.files;
        if(files.length > 0){
          var fileSize = ((files[0].size/1024)/1024).toFixed(4);
          var fileName = files[0].name;
          var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

          console.log(e.currentTarget.value);
          if ($.inArray(fileNameExt, validExtensions) == -1) {
            e.currentTarget.value = '';
            $('#edit_image_npwp_dokumen_cust').next('label').html('Choose file');
            alert("Only these file types are accepted : " + validExtensions.join(', '));
          }
          if(fileSize > 2){
            e.currentTarget.value = '';
            $('#edit_image_npwp_dokumen_cust').next('label').html('Choose file');
            alert('Max File Size is 2 MB');
          }
        }
      });

      $("#edit_image_siup_dokumen_cust").on("change", function (e) {
        var validExtensions = ['jpg','pdf','jpeg'];

        var files = e.currentTarget.files;
        if(files.length > 0){
          var fileSize = ((files[0].size/1024)/1024).toFixed(4);
          var fileName = files[0].name;
          var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

          console.log(e.currentTarget.value);
          if ($.inArray(fileNameExt, validExtensions) == -1) {
            e.currentTarget.value = '';
            $('#edit_image_siup_dokumen_cust').next('label').html('Choose file');
            alert("Only these file types are accepted : " + validExtensions.join(', '));
          }
          if(fileSize > 2){
            e.currentTarget.value = '';
            $('#edit_image_siup_dokumen_cust').next('label').html('Choose file');
            alert('Max File Size is 2 MB');
          }
        }
      });

      $("#edit_image_tdp_dokumen_cust").on("change", function (e) {
        var validExtensions = ['jpg','pdf','jpeg'];

        var files = e.currentTarget.files;
        if(files.length > 0){
          var fileSize = ((files[0].size/1024)/1024).toFixed(4);
          var fileName = files[0].name;
          var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

          console.log(e.currentTarget.value);
          if ($.inArray(fileNameExt, validExtensions) == -1) {
            e.currentTarget.value = '';
            $('#edit_image_tdp_dokumen_cust').next('label').html('Choose file');
            alert("Only these file types are accepted : " + validExtensions.join(', '));
          }
          if(fileSize > 2){
            e.currentTarget.value = '';
            $('#edit_image_tdp_dokumen_cust').next('label').html('Choose file');
            alert('Max File Size is 2 MB');
          }
        }
      });

      var url = "{{ url('sales/detail_question/id_schedule') }}";
      url = url.replace('id_schedule', enc(nomor_visit.toString()));
      $.get(url, function (data) {
        $("#edit_kegiatan_question").val(data.no_kegiatan).trigger('change');
        if(data.company_profile == 'yes'){
          $("#edit_company_profile_yes").prop("checked", true);
        }else{
          $("#edit_company_profile_no").prop("checked", true);
        }
        if(data.pengenalan_produk == 'yes'){
          $("#edit_pengenalan_produk_yes").prop("checked", true);
        }else{
          $("#edit_pengenalan_produk_no").prop("checked", true);
        }
        $("#edit_sumber_kenal_dsgm").val(data.sumber_kenal_dsgm);
        $("#edit_nomor_surat_pengenalan").val(data.nomor_surat_pengenalan);
        $("#edit_nama_janji_visit").val(data.nama_janji_visit);
        $("#edit_pic_janji_visit").val(data.pic_janji_visit);
        $("#edit_jabatan_janji_visit").val(data.jabatan_janji_visit);
        $("#edit_alamat_janji_visit").html(data.alamat_janji_visit);
        $("#edit_tanggal_janji_visit").val(data.tanggal_janji_visit);
        $("#edit_bisnis_latar_belakang").val(data.bisnis_perusahaan);
        $("#edit_owner_latar_belakang").val(data.owner_perusahaan);
        $("#edit_tahun_berdiri_latar_belakang").val(data.tahun_berdiri_perusahaan);
        $("#edit_jenis_usaha_latar_belakang").val(data.jenis_usaha_perusahaan);
        $("#edit_jangkauan_wilayah_latar_belakang").val(data.jangkauan_wilayah_perusahaan);
        $("#edit_top_latar_belakang").val(data.top_cust_perusahaan);
        $("#edit_tipe_penggunaan_kalsium").val(data.tipe_kalsium);
        $("#edit_qty_penggunaan_kalsium").val(data.qty_kalsium);
        $("#edit_kegunaan_penggunaan_kalsium").html(data.kegunaan_kalsium);
        $("#edit_merk_kompetitor_penggunaan_kalsium").val(data.merk_kompetitor);
        $("#edit_harga_kompetitor_penggunaan_kalsium").val(data.harga_kompetitor);
        $("#edit_pengiriman_penggunaan_kalsium").val(data.pengiriman_kalsium);
        if(data.no_pembayaran_supplier == 1){
          $("#edit_pembayaran_penggunaan_kalsium_cash").prop("checked", true);
        }else{
          $("#edit_pembayaran_penggunaan_kalsium_top").prop("checked", true);
        }
        $("#edit_tipe_permintaan_sample").val(data.tipe_sample);
        $("#edit_qty_permintaan_sample").val(data.qty_sample);
        $("#edit_feedback_permintaan_sample").html(data.feedback_uji_sample);
        if(data.penawaran_harga == 'yes'){
          $("#edit_tawar_penawaran_harga_yes").prop("checked", true).trigger('change');
        }else{
          $("#edit_tawar_penawaran_harga_no").prop("checked", true).trigger('change');
        }
        if(data.nego_harga == 'yes'){
          $("#edit_nego_penawaran_harga_yes").prop("checked", true);
        }else{
          $("#edit_nego_penawaran_harga_no").prop("checked", true);
        }
        $("#edit_nominal_penawaran_harga").val(data.nominal_harga);
        $("#div_edit_nomor_penawaran_harga").val(data.nomor_penawaran);
        if(data.pembayaran == 'yes'){
          $("#edit_pembayaran_penawaran_harga_yes").prop("checked", true).trigger('change');
        }else{
          $("#edit_pembayaran_penawaran_harga_no").prop("checked", true).trigger('change');
        }
        if(data.pengiriman == 'yes'){
          $("#edit_pengiriman_penawaran_harga_yes").prop("checked", true);
        }else{
          $("#edit_pengiriman_penawaran_harga_no").prop("checked", true);
        }
        $("#edit_dokumen_penawaran_harga").val(data.dokumen_pengiriman);
        if(data.no_cash_top == 1){
          $("#edit_cash_pembayaran").prop("checked", true).trigger('change');
        }else{
          $("#edit_top_pembayaran").prop("checked", true).trigger('change');
        }
        $("#edit_tempat_tt_pembayaran").val(data.tempat_tukar_tt);
        $("#edit_jadwal_tt_pembayaran").val(data.jadwal_tukar_tt);
        $("#edit_jadwal_pembayaran").val(data.jadwal_pembayaran);
        $("#edit_metode_pembayaran").val(data.metode_pembayaran);
        $("#edit_pic_pembayaran").val(data.pic_penagihan);
        $("#edit_po_dokumen_cust").val(data.nomor_po);
        if(data.nomor_ktp == null || data.nomor_ktp == ''){
          $("#edit_ktp_dokumen_cust_no").prop("checked", true).trigger('change');
          $("#div_view_image_ktp").hide();
        }else{
          $("#edit_ktp_dokumen_cust_yes").prop("checked", true).trigger('change');
          $("#div_view_image_ktp").show();
        }
        $("#edit_no_ktp_dokumen_cust").val(data.nomor_ktp);
        if(data.nomor_npwp == null || data.nomor_npwp == ''){
          $("#edit_npwp_dokumen_cust_no").prop("checked", true).trigger('change');
          $("#div_view_image_npwp").hide();
        }else{
          $("#edit_npwp_dokumen_cust_yes").prop("checked", true).trigger('change');
          $("#div_view_image_npwp").show();
        }
        $("#edit_no_npwp_dokumen_cust").val(data.nomor_npwp);
        if(data.nomor_siup == null || data.nomor_siup == ''){
          $("#edit_siup_dokumen_cust_no").prop("checked", true).trigger('change');
          $("#div_view_image_siup").hide();
        }else{
          $("#edit_siup_dokumen_cust_yes").prop("checked", true).trigger('change');
          $("#div_view_image_siup").show();
        }
        $("#edit_no_siup_dokumen_cust").val(data.nomor_siup);
        if(data.nomor_tdp == null || data.nomor_tdp == ''){
          $("#edit_tdp_dokumen_cust_no").prop("checked", true).trigger('change');
          $("#div_view_image_tdp").hide();
        }else{
          $("#edit_tdp_dokumen_cust_yes").prop("checked", true).trigger('change');
          $("#div_view_image_tdp").show();
        }
        $("#edit_no_tdp_dokumen_cust").val(data.nomor_tdp);
        $("#edit_hasil_visit_question").html(data.hasil_visit);
        if(data.image_ktp == null){
          $('#lihat_ktp_edit').html('Tidak Ada Foto');
          $('#lihat_ktp_edit').addClass('disabled');
          $('#lihat_ktp_edit').attr('href', '#');
        }else{
          $('#lihat_ktp_edit').html('Lihat Foto KTP');
          $('#lihat_ktp_edit').removeClass('disabled');
          $('#lihat_ktp_edit').attr('href', '../data_file/' + data.image_ktp);
        }
        if(data.image_npwp == null){
          $('#lihat_npwp_edit').html('Tidak Ada Foto');
          $('#lihat_npwp_edit').addClass('disabled');
          $('#lihat_npwp_edit').attr('href', '#');
        }else{
          $('#lihat_npwp_edit').html('Lihat Foto NPWP');
          $('#lihat_npwp_edit').removeClass('disabled');
          $('#lihat_npwp_edit').attr('href', '../data_file/' + data.image_npwp);
        }
        if(data.image_siup == null){
          $('#lihat_siup_edit').html('Tidak Ada Foto');
          $('#lihat_siup_edit').addClass('disabled');
          $('#lihat_siup_edit').attr('href', '#');
        }else{
          $('#lihat_siup_edit').html('Lihat Foto SIUP');
          $('#lihat_siup_edit').removeClass('disabled');
          $('#lihat_siup_edit').attr('href', '../data_file/' + data.image_siup);
        }
        if(data.image_tdp == null){
          $('#lihat_tdp_edit').html('Tidak Ada Foto');
          $('#lihat_tdp_edit').addClass('disabled');
          $('#lihat_tdp_edit').attr('href', '#');
        }else{
          $('#lihat_tdp_edit').html('Lihat Foto TDP');
          $('#lihat_tdp_edit').removeClass('disabled');
          $('#lihat_tdp_edit').attr('href', '../data_file/' + data.image_tdp);
        }
      })
    });

    $('#btn-save-data').click( function() {
      var data = $('#data_sorting_table input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-data').data('token'),
        },
        type: "POST",
        url: '{{ url("schedule/route_plan/save") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(data){
          var oTable = $('#route_plan_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_sorting_data').modal('hide');
          $("#modal_sorting_data").trigger('click');
          alert('Data Successfully Updated');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('#btn-edit-data').click( function() {
      var data = table.$('input, textarea').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-edit-data').data('token'),
        },
        type: "POST",
        url: '{{ url("schedule/route_plan/edit") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(data){
          var oTable = $('#jadwal_followup_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_edit_data').modal('hide');
          $("#modal_edit_data").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-poin-delivery').click( function() {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-poin-delivery').data('token'),
        },
        type: "POST",
        url: '{{ url("schedule/poin_delivery/save") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(data){
          var oTable = $('#poin_delivery_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_poin_delivery').modal('hide');
          $("#modal_poin_delivery").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('#btn-poin-paid').click( function() {
      var data = table.$('input').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-poin-paid').data('token'),
        },
        type: "POST",
        url: '{{ url("schedule/poin_paid/save") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(data){
          var oTable = $('#poin_paid_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_poin_paid').modal('hide');
          $("#modal_poin_paid").trigger('click');
          alert('Data Successfully Updated');
        }
      });
    });

    $('body').on('click', '#btn-orders', function () {
      var quote_tanggal_kirim = document.getElementById("quote_tanggal_kirim").value;
      var quote_nomor_po = document.getElementById("quote_nomor_po").value;
      var quote_keterangan = document.getElementById("quote_keterangan").value;
      var quote_custid = document.getElementById("quote_custid").value;
      var quote_id_schedule = document.getElementById("quote_id_schedule").value;
      var quote_nama_cust_receive = document.getElementById("quote_nama_cust_receive").value;
      var quote_alamat_cust_receive = document.getElementById("quote_alamat_cust_receive").value;
      var quote_kota_cust_receive = document.getElementById("quote_kota_cust_receive").value;
      var quote_telepon_cust_receive = document.getElementById("quote_telepon_cust_receive").value;

      var count = $("#quote_order_products_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Please add the products");
      }else{
        if((quote_tanggal_kirim == null || quote_tanggal_kirim == "")){
          alert("Delivery Date must not be empty");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('sales/customers_visit/quotation/save') }}",
            data: { 'quote_custid' : quote_custid, 'quote_id_schedule' : quote_id_schedule, 'quote_tanggal_kirim' : quote_tanggal_kirim, 'quote_keterangan' : quote_keterangan, 'quote_nomor_po' : quote_nomor_po, 'quote_nama_cust_receive' : quote_nama_cust_receive, 'quote_alamat_cust_receive' : quote_alamat_cust_receive, 'quote_kota_cust_receive' : quote_kota_cust_receive, 'quote_telepon_cust_receive' : quote_telepon_cust_receive },
            success:function(data){
              alert("Orders Successful");
              $('#modal_quotation').modal('hide');
              $("#modal_quotation").trigger('click');
              $('#quote_custid').val('');
              $('#quote_nama_cust_receive').val('');
              $('#quote_alamat_cust_receive').html('');
              $('#quote_kota_cust_receive').val('').trigger('change');
              $('#quote_telepon_cust_receive').val('');
              $('#quote_add_products').children().remove().end();
              $('#quote_add_quantity').val('');
              $('#quote_tanggal_kirim').val('');
              $('#quote_nomor_po').val('');
              $('#quote_keterangan').html('');
              $('#quote_tanggal_kirim').flatpickr({
                allowInput: true,
                defaultDate: new Date(),
                minDate: "today"
              });
              var oTable = $('#quotation_table').dataTable();
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
        tanggal_jadwal: {
          required: true,
        },
        perihal: {
          required: true,
        },
        company: {
          required: true,
        },
        tipe_customers: {
          required: true,
        },
        permintaan_sample: {
          required: true,
        },
      },
      messages: {
        tanggal_jadwal: {
          required: "Tanggal is required",
        },
        company: {
          required: "Perusahaan is required",
        },
        tipe_customers: {
          required: "Tipe Customers is required",
        },
        permintaan_sample: {
          required: "Permintaan Sample is required",
        },
        perihal: {
          required: "Perihal is Required",
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
          url:"{{ url('/sales/schedule_input/offline') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_schedule_offline_form').trigger("reset");
            var oTable = $('#schedule_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_make_schedule_offline").modal('hide');
            $("#modal_make_schedule_offline").trigger('click');
            alert("Data Successfully Stored");
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
        tanggal_jadwal_online: {
          required: true,
        },
        perihal_online: {
          required: true,
        },
        company_online: {
          required: true,
        },
        tipe_customers_online: {
          required: true,
        },
        permintaan_sample_online: {
          required: true,
        },
        result_online: {
          required: true,
        },
        follow_up_online: {
          required: true,
        },
      },
      messages: {
        tanggal_jadwal_online: {
          required: "Tanggal is required",
        },
        company_online: {
          required: "Perusahaan is required",
        },
        tipe_customers_online: {
          required: "Tipe Customers is required",
        },
        permintaan_sample_online: {
          required: "Permintaan Sample is required",
        },
        perihal_online: {
          required: "Perihal is Required",
        },
        result_online: {
          required: "Result is Required",
        },
        follow_up_online: {
          required: "Follow Up is Required",
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
          url:"{{ url('/sales/schedule_input/online') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_schedule_online_form').trigger("reset");
            var oTable = $('#schedule_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_make_schedule_online").modal('hide');
            $("#modal_make_schedule_online").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#input_question_form').validate({
      rules: {
        kegiatan_question: {
          required: true,
        },
      },
      messages: {
        kegiatan_question: {
          required: "Kegiatan Harus Diisi",
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
        var myform = document.getElementById("input_question_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('schedule/realisasi_followup/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_question_form').trigger("reset");
            var oTable = $('#realisasi_followup_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_isi_question").modal('hide');
            $("#modal_isi_question").trigger('click');
            alert("Data Successfully Stored");
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
        proses_id_schedule: {
          required: true,
        },
        proses_status: {
          required: true,
        },
        result: {
          required: function() {
            return $('#proses_status').val() == 3;
          }
        },
        follow_up: {
          required: function() {
            return $('#proses_status').val() == 3;
          }
        },
        proses_tanggal_jadwal: {
          required: function() {
            return $('#proses_status').val() == 2;
          }
        },
        alasan_suspend: {
          required: function() {
            return $('#proses_status').val() == 2;
          }
        },
      },
      messages: {
        proses_id_schedule: {
          required: "ID Schedule is Required",
        },
        proses_status: {
          required: "Status is required",
        },
        result: {
          required: "Result is Required",
        },
        follow_up: {
          required: "Perlu Follow Up is Required",
        },
        proses_tanggal_jadwal: {
          required: "Ganti Tanggal dan Waktu is Required",
        },
        alasan_suspend: {
          required: "Alasan Ditunda is Required",
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
          url:"{{ url('/sales/schedule_proses') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#proses_schedule_form').trigger("reset");
            var oTable = $('#schedule_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_proses_schedule").modal('hide');
            $("#modal_proses_schedule").trigger('click');
            alert("Data Successfully Updated");
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
        edit_tanggal_jadwal: {
          required: true,
        },
        edit_perihal: {
          required: true,
        },
        edit_company: {
          required: true,
        },
        edit_tipe_customers: {
          required: true,
        },
        edit_permintaan_sample: {
          required: true,
        },
        edit_offline: {
          required: true,
        },
      },
      messages: {
        edit_tanggal_jadwal: {
          required: "Tanggal is required",
        },
        edit_company: {
          required: "Perusahaan is required",
        },
        edit_tipe_customers: {
          required: "Tipe Customers is required",
        },
        edit_permintaan_sample: {
          required: "Permintaan Sample is required",
        },
        edit_perihal: {
          required: "Perihal is Required",
        },
        edit_offline: {
          required: "Offline / Online is Required",
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
          url:"{{ url('/sales/schedule_edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_schedule_form').trigger("reset");
            var oTable = $('#schedule_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_edit_schedule").modal('hide');
            $("#modal_edit_schedule").trigger('click');
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#edit_question_form').validate({
      rules: {
        edit_kegiatan_question: {
          required: true,
        },
      },
      messages: {
        edit_kegiatan_question: {
          required: "Kegiatan Harus Diisi",
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
        var myform = document.getElementById("edit_question_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('schedule/realisasi_followup/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_question_form').trigger("reset");
            var oTable = $('#realisasi_followup_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_edit_question").modal('hide');
            $("#modal_edit_question").trigger('click');
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#add_products_form').validate({
        rules: {
          quote_add_products: {
            required: true,
          },
          quote_custid_prd: {
            required: true,
          },
          quote_add_quantity: {
            required: true,
          },
        },
        messages: {
          quote_add_products: {
            required: "Products Harus Diisi",
          },
          quote_add_quantity: {
            required: "Quantity Harus Diisi",
          },
          quote_custid_prd: {
            required: "CustID Harus Diisi",
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
          var myform = document.getElementById("add_products_form");
          var formdata = new FormData(myform);

          
          $.ajax({
            type:'POST',
            url:"{{ url('sales/quotation/products/add/save') }}",
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
              $('#add_products_form').trigger("reset");
              var oTable = $('#quote_order_products_table').dataTable();
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
        url: '/autocomplete',
        data: function (params) {
          var company = $('#company').val();
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

    $('.leads').select2({
      dropdownParent: $('#modal_make_schedule_offline .modal-content'),
      placeholder: 'Leads',
      allowClear: true,
      ajax: {
        url: '/autocomplete_leads',
        data: function (params) {
          var company = $('#company').val();
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
                text: item.nama,
                id: item.leadid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.kompetitor').select2({
      dropdownParent: $('#modal_make_schedule_offline .modal-content'),
      placeholder: 'Kompetitor',
      allowClear: true,
      ajax: {
        url: '/autocomplete_kompetitor',
        data: function (params) {
          var company = $('#company').val();
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
                text: item.nama,
                id: item.kompid
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
        url: '/autocomplete',
        data: function (params) {
          var company = $('#company_online').val();
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

    $('.leads_online').select2({
      dropdownParent: $('#modal_make_schedule_online .modal-content'),
      placeholder: 'Leads',
      allowClear: true,
      ajax: {
        url: '/autocomplete_leads',
        data: function (params) {
          var company = $('#company_online').val();
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
                text: item.nama,
                id: item.leadid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.kompetitor_online').select2({
      dropdownParent: $('#modal_make_schedule_online .modal-content'),
      placeholder: 'Kompetitor',
      allowClear: true,
      ajax: {
        url: '/autocomplete_kompetitor',
        data: function (params) {
          var company = $('#company_online').val();
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
                text: item.nama,
                id: item.kompid
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
        url: '/autocomplete',
        data: function (params) {
          var company = $('#edit_company').val();
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

    $('.edit_leads').select2({
      dropdownParent: $('#modal_edit_schedule .modal-content'),
      placeholder: 'Leads',
      allowClear: true,
      ajax: {
        url: '/autocomplete_leads',
        data: function (params) {
          var company = $('#edit_company').val();
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
                text: item.nama,
                id: item.leadid
              }
            })
          };
        },
        cache: true
      }
    });

    $('.edit_kompetitor').select2({
      dropdownParent: $('#modal_edit_schedule .modal-content'),
      placeholder: 'Kompetitor',
      allowClear: true,
      ajax: {
        url: '/autocomplete_kompetitor',
        data: function (params) {
          var company = $('#edit_company').val();
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
                text: item.nama,
                id: item.kompid
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

  $(".leads").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Leads Data Here...");
  });
  $(".leads").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".kompetitor").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Kompetitor Data Here...");
  });
  $(".kompetitor").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".customer_online").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".customer_online").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".leads_online").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Leads Data Here...");
  });
  $(".leads_online").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".kompetitor_online").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Kompetitor Data Here...");
  });
  $(".kompetitor_online").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".edit_customer").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".edit_customer").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".edit_leads").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Leads Data Here...");
  });
  $(".edit_leads").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });

  $(".edit_kompetitor").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Kompetitor Data Here...");
  });
  $(".edit_kompetitor").on("select2:close", function() {
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
