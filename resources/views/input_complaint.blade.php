@extends('layouts.app_admin')

@section('title')
<title>COMPLAINT - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
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
  .modal {
    overflow-y: auto !important;
  }
  div.dataTables_length {
    margin-top: 5px;
    margin-right: 1em;
  }
  #save_input_produksi, #save_new_edit_produksi, #save_edit_produksi  {
    right: 20%;
    position: absolute;
    top: 30px;
    width: 60%;
    height: 40px;
  }

  #save_input_komitmen_sls, #save_input_komitmen_lny, #save_new_edit_komitmen_sls, #save_edit_komitmen_sls, #save_new_edit_komitmen_lny, #save_edit_komitmen_lny {
    right: 20%;
    position: absolute;
    top: 23%;
    width: 60%;
    height: 30%;
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
    .complaint-btn {
      margin-bottom: 10px;
    }
    #save_input_produksi, #save_new_edit_produksi, #save_edit_produksi  {
      position: unset;
      width: 100%;
    }

    #save_input_komitmen_sls, #save_input_komitmen_lny, #save_new_edit_komitmen_sls, #save_edit_komitmen_sls, #save_new_edit_komitmen_lny, #save_edit_komitmen_lny {
      position: unset;
      width: 100%;
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
          <h1 class="m-0 text-dark">Complaint</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Complaint</li>
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
            <?php
            if(Session::get('tipe_user') == 1 || Session::get('tipe_user') == 2){
            ?>
            <div class="col-3">
              <button type="button" name="btn_input_manual" id="btn_input_manual" class="btn btn-block btn-primary complaint-btn" data-toggle="modal" data-target="#modal_input_complaint">Input Complaint</button>
            </div>
            <?php
            }else{
            ?>
            <div class="col-3"></div>
            <?php
            }
            ?>
            <div class="col-6">
              <div class="form-group">
                <select id="tipe_complaint" name="tipe_complaint" class="form-control">
                  <option value="5" selected>Konfirmasi Complaint</option>
                  <option value="3">Complaint Sales</option>
                  <option value="4">Complaint Lainnya</option>
                </select>
              </div>
            </div>
            <div class="col-3">
              <button type="button" name="btn_list_komitmen" id="btn_list_komitmen" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_list_komitmen">List of Actions</button>
            </div>
          </div>
        </div>
        <div class="card-body" id="konfirmasi_complaint">
          <table id="konfirmasi_complaint_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
            <tr>
                <th class="not-mobile">No Complaint</th>
                <th>Tanggal</th>
                <th>Cust ID</th>
                <th>Nama</th>
                <th>No Surat Jalan</th>
                <th>Complaint</th>
                <th>Lampiran</th>
                <th>Action</th>
            </tr>
           </thead>
          </table>
        </div>
       <div class="card-body" id="complaint_sales" style="display: none;">
          <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <?php
              if(Session::get('login_admin')){
                if(Session::get('tipe_user') == 1){
            ?>
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#semua_sales_admin" role="tab" aria-controls="custom-content-home-profile" aria-selected="true">Semua</a>
            </li>
            <?php
              }else{
            ?>
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#semua_sales" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Semua</a>
            </li>
            <?php
              }
            }
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#proses_sales" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Proses</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#diproses_sales" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Sedang Diproses</a>
            </li>
            <?php
              if(Session::get('login_admin')){
                if(Session::get('tipe_user') == 1){
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#valid_sales" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Validasi</a>
            </li>
            <?php
              }
            }
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#selesai_sales" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Selesai</a>
            </li>
          </ul>
          <div class="tab-content" id="custom-content-below-tabContent">
           <?php
            if(Session::get('login_admin')){
              if(Session::get('tipe_user') == 1){
            ?>
           <div class="tab-pane fade show active" id="semua_sales_admin" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="complaint_sales_semua_admin_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <?php
            }else{
           ?>
           <div class="tab-pane fade show active" id="semua_sales" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="complaint_sales_semua_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <?php
              }
            }
           ?>
           <div class="tab-pane fade" id="proses_sales" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
             <table id="complaint_sales_proses_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <div class="tab-pane fade" id="diproses_sales" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
             <table id="complaint_sales_diproses_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <div class="tab-pane fade" id="valid_sales" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
             <table id="complaint_sales_valid_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <div class="tab-pane fade" id="selesai_sales" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
             <table id="complaint_sales_selesai_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
         </div>
       </div>
       <div class="card-body" id="complaint_lainnya" style="display: none;">
          <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
            <?php
              if(Session::get('login_admin')){
                if(Session::get('tipe_user') == 1){
            ?>
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#semua_lainnya_admin" role="tab" aria-controls="custom-content-home-profile" aria-selected="true">Semua</a>
            </li>
            <?php
              }else{
            ?>
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#semua_lainnya" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Semua</a>
            </li>
            <?php
              }
            }
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#proses_lainnya" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Proses</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#diproses_lainnya" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Sedang Diproses</a>
            </li>
            <?php
              if(Session::get('login_admin')){
                if(Session::get('tipe_user') == 1){
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#valid_lainnya" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Validasi</a>
            </li>
            <?php
              }
            }
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#selesai_lainnya" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Selesai</a>
            </li>
          </ul>
          <div class="tab-content" id="custom-content-below-tabContent">
           <?php
            if(Session::get('login_admin')){
              if(Session::get('tipe_user') == 1){
            ?>
           <div class="tab-pane fade show active" id="semua_lainnya_admin" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="complaint_lainnya_semua_admin_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <?php
            }else{
           ?>
           <div class="tab-pane fade show active" id="semua_lainnya" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="complaint_lainnya_semua_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <?php
              }
            }
           ?>
           <div class="tab-pane fade" id="proses_lainnya" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
             <table id="complaint_lainnya_proses_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <div class="tab-pane fade" id="diproses_lainnya" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
             <table id="complaint_lainnya_diproses_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <div class="tab-pane fade" id="valid_lainnya" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
             <table id="complaint_lainnya_valid_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
           </div>
           <div class="tab-pane fade" id="selesai_lainnya" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
             <table id="complaint_lainnya_selesai_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th class="not-mobile">No Complaint</th>
                    <th>Tanggal</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>No Surat Jalan</th>
                    <th>Complaint</th>
                    <th>Divisi</th>
                    <th>Status</th>
                    <th>Lampiran</th>
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

  <div class="modal fade" id="modal_input_complaint">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Complaint</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @if ($errors->any())
          <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form method="post" class="input_complaint_form" id="input_complaint_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="custid">Customer</label>
                  <select id="custid" name="custid" class="form-control select2 customer" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="nomor_surat_jalan">Nomor Surat jalan</label>
                  <input class="form-control" type="text" name="nomor_surat_jalan" id="nomor_surat_jalan" placeholder="Nomor Surat Jalan" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label>Tanggal Order</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" name="tanggal_order" id="tanggal_order" autocomplete="off" placeholder="Tanggal Order">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="complaint">Complaint</label>
                  <textarea class="form-control" rows="3" name="complaint" id="complaint" placeholder="Complaint"></textarea>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="upload_file">Upload File</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="upload_file" name="upload_file">
                      <label class="custom-file-label" for="upload_file">Choose file</label>
                    </div>
                  </div>
                </div>
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="sales_order">Sales Yang Memesan</label>
                  <input class="form-control" type="text" name="sales_order" id="sales_order" placeholder="Sales Yang Memesan" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="supervisor_sls">Supervisor Sales</label>
                  <input class="form-control" type="text" name="supervisor_sls" id="supervisor_sls" placeholder="Supervisor Sales" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="pelapor">Pelapor</label>
                  <input class="form-control" type="text" name="pelapor" id="pelapor" placeholder="Pelapor" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="jumlah_karung">Jumlah Karung</label>
                  <input class="form-control" type="text" name="jumlah_karung" id="jumlah_karung" placeholder="Jumlah Karung" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="quantity">Quantity</label>
                  <input class="form-control" type="text" name="quantity" id="quantity" placeholder="Quantity" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="jumlah_kg_sak">&nbsp</label>
                  <select id="jumlah_kg_sak" name="jumlah_kg_sak" class="form-control">
                    <option value="" selected>Jumlah KG / Sak</option>
                    <option value="20">20 KG</option>
                    <option value="25">25 KG</option>
                    <option value="30">30 KG</option>
                    <option value="40">40 KG</option>
                    <option value="50">50 KG</option>
                    <option value="800">800 KG</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="jenis_karung">&nbsp</label>
                  <select id="jenis_karung" name="jenis_karung" class="form-control">
                    <option value="" selected>Jenis Karung</option>
                    <option value="1">Woven Bag</option>
                    <option value="2">Paper Bag</option>
                    <option value="3">Jumbo Bag</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="berat_timbangan">Berat Timbangan</label>
                  <input class="form-control" type="text" name="berat_timbangan" id="berat_timbangan" placeholder="Berat Timbangan" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="unit_berat_timbangan">&nbsp</label>
                  <select id="unit_berat_timbangan" name="unit_berat_timbangan" class="form-control">
                    <option value="" selected>Unit Satuan</option>
                    <option value="1">KG</option>
                    <option value="2">TON</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="berat_aktual">Berat Perhitungan</label>
                  <input class="form-control" type="text" name="berat_aktual" id="berat_aktual" placeholder="Berat Perhitungan" readonly/>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="unit_berat_aktual">&nbsp</label>
                  <select id="unit_berat_aktual" name="unit_berat_aktual" class="form-control" disabled>
                    <option value="" selected>Unit Satuan</option>
                    <option value="1">KG</option>
                    <option value="2">TON</option>
                  </select>
                  <input class="form-control" type="hidden" name="input_unit_berat_aktual" id="input_unit_berat_aktual"/>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Logistik</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label>Tanggal Pengiriman</label>

                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="tanggal_pengiriman" id="tanggal_pengiriman" autocomplete="off" placeholder="Tanggal Pengiriman">
                          </div>
                          <!-- /.input group -->
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="area_log">Area</label>
                          <select id="area_log" name="area_log" class="form-control">
                            <option value="" selected>Area</option>
                            <option value="1">Area 1</option>
                            <option value="2">Area 2</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="supervisor_log">Supervisor</label>
                          <input class="form-control" type="text" name="supervisor_log" id="supervisor_log" placeholder="Supervisor" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="nama_supir">Nama Supir</label>
                          <input class="form-control" type="text" name="nama_supir" id="nama_supir" placeholder="Nama Supir" />
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="nama_kernet">Nama Kernet</label>
                          <input class="form-control" type="text" name="nama_kernet" id="nama_kernet" placeholder="Nama Kernet" />
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="no_kendaraan">No Kendaraan</label>
                          <input class="form-control" type="text" name="no_kendaraan" id="no_kendaraan" placeholder="No Kendaraan" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="nama_kuli">Nama Kuli</label>
                          <table class="table" id="dynamic_field_kuli" style="border: none;">  
                            <tr>  
                              <td><input type="text" name="nama_kuli[]" placeholder="Nama Kuli" class="form-control nama_kuli_list" /></td>  
                              <td><button type="button" name="add_kuli" id="add_kuli" class="btn btn-success">Add More</button></td>  
                            </tr>  
                          </table> 
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="nama_stapel">Nama Stapel</label>
                          <table class="table" id="dynamic_field_stapel" style="border: none;">  
                            <tr>  
                              <td><input type="text" name="nama_stapel[]" placeholder="Nama Stapel" class="form-control nama_stapel_list" /></td>  
                              <td><button type="button" name="add_stapel" id="add_stapel" class="btn btn-success">Add More</button></td>  
                            </tr>  
                          </table> 
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="pengiriman">Pengiriman</label>
                          <select id="pengiriman" name="pengiriman" class="form-control">
                            <option value="" selected>Pengiriman</option>
                            <option value="1">DSGM</option>
                            <option value="2">Ambil Sendiri</option>
                            <option value="3">Ekspedisi</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="jenis_kendaraan">Jenis Kendaraan</label>
                          <select id="jenis_kendaraan" name="jenis_kendaraan" class="form-control">
                            <option value="" selected>Jenis Kendaraan</option>
                            <option value="1">Engkel</option>
                            <option value="2">Colt Diesel</option>
                            <option value="3">Fuso</option>
                            <option value="4">Tronton</option>
                            <option value="5">Trailer</option>
                            <option value="6">Kontainer</option>
                            <option value="7">Lainnya</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group" id="divpengiriman">
                          <input type="text" class="form-control" id="pengiriman_lain" name="pengiriman_lain" placeholder="Pengiriman Lainnya" style="display: none;">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group" id="divjeniskendaraan">
                          <input type="text" class="form-control" id="jenis_kendaraan_lain" name="jenis_kendaraan_lain" placeholder="Jenis Kendaraan Lainnya" style="display: none;">
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
                  <div class="card-header">
                    <h4>Produksi</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="no_lot">No Lot</label>
                          <input class="form-control" type="text" name="no_lot" id="no_lot" placeholder="No Lot" />
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label>Tanggal Produksi</label>

                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name="tanggal_produksi" id="tanggal_produksi" autocomplete="off" placeholder="Tanggal Produksi">
                          </div>  
                          <!-- /.input group -->
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label>Products</label>
                          <select id="produk" name="produk" class="form-control">
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="area_prd">Area</label>
                          <select id="area_prd" name="area_prd" class="form-control">
                            <option value="" selected>Area</option>
                            <option value="1">Area 1</option>
                            <option value="2">Area 2</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="mesin">Mesin</label>
                          <input class="form-control" type="text" name="mesin" id="mesin" placeholder="Mesin" />
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="supervisor_prd">Supervisor</label>
                          <input class="form-control" type="text" name="supervisor_prd" id="supervisor_prd" placeholder="Supervisor" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label for="petugas">Petugas</label>
                          <table class="table" id="dynamic_field_petugas" style="border: none;">
                            <tr>  
                              <td><input type="text" name="petugas[]" placeholder="Petugas" class="form-control petugas_list" /></td>  
                              <td><button type="button" name="add_petugas" id="add_petugas" class="btn btn-success">Add More</button></td>
                            </tr>  
                          </table> 
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="bermasalah">Bermasalah?</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="bermasalah" name="input_bermasalah" value="Ya" checked>
                            <label for="bermasalah" class="custom-control-label">Bermasalah</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="label-flex">
                            <label for="tidak_bermasalah">&nbsp</label>
                          </div>
                          <div class="custom-control custom-radio" style="margin-top: 5px;">
                            <input class="form-control custom-control-input" type="radio" id="tidak_bermasalah" name="input_bermasalah" value="Tidak">
                            <label for="tidak_bermasalah" class="custom-control-label">Tidak Bermasalah</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <button type="button" class="btn btn-success" id="save_input_produksi">Save</button>
                        </div>
                      </div>
                    </div>
                    <table id="list_no_lot_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                      <thead>
                        <tr>
                          <th class="not-mobile">No</th>
                          <th>No Lot</th>
                          <th class="not-mobile">Tgl Produksi</th>
                          <th>Produk</th>
                          <th>Area</th>
                          <th>Mesin</th>
                          <th>Bermasalah</th>
                          <th class="min-mobile"></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="save_input_complaint">Save changes</button>
        </div>
      </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_konfirmasi_complaint">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Pilih Divisi</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="konfirmasiform" id="konfirmasiform" action="javascript:void(0)">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="nomor_complaint_konf" id="nomor_complaint_konf" />
        </div>
        <div class="row">
          <div class="col-4"></div>
          <div class="col-5">
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="checkbox_produksi" id="checkbox_produksi" value="1">
                <label for="checkbox_produksi" class="custom-control-label">Divisi Produksi</label>
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="checkbox_logistik" id="checkbox_logistik" value="2">
                <label for="checkbox_logistik" class="custom-control-label">Divisi Logistik</label>
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="checkbox_timbangan" id="checkbox_timbangan" value="4">
                <label for="checkbox_timbangan" class="custom-control-label">Divisi Timbangan</label>
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="checkbox_warehouse" id="checkbox_warehouse" value="5">
                <label for="checkbox_warehouse" class="custom-control-label">Divisi Warehouse</label>
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="checkbox_lainnya" id="checkbox_lainnya" value="7">
                <label for="checkbox_lainnya" class="custom-control-label">Divisi Lainnya</label>
              </div>
            </div>
          </div>
          <div class="col-3"></div>
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

<div class="modal fade" id="modal_lihat_complaint_produksi">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Lihat Data Complaint</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#lihat_data_complaint_flow" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data Complaint</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#lihat_complaint_produksi_flow" role="tab" aria-controls="custom-content-below-home" aria-selected="false">Div Produksi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#lihat_complaint_logistik_flow" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Div Logistik</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#lihat_complaint_sales_flow" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Div Sales</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#lihat_complaint_timbangan_flow" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Div Timbangan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#lihat_complaint_warehouse_flow" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Div Warehouse</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#lihat_complaint_lab_flow" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Div Lab</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#lihat_complaint_lainnya_flow" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Div Lainnya</a>
          </li>
        </ul>
        <div class="tab-content tab-content-lihat" id="custom-content-below-tabContent">
          <div class="tab-pane fade show active" id="lihat_data_complaint_flow" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
            <div class="row">
             <div class="col-lg-12 lihat-table">
              <table class="table table-bordered table-hover">
               <thead>
                <tr>
                 <th colspan="4" style="text-align: center;">Data Complaint dan Pengiriman</th>
               </tr>
                <tr>
                 <th>Nomor Complaint</th>
                 <td id="td_nomor_complaint_com"></td>
                 <th>Tanggal Complaint</th>
                 <td id="td_tanggal_complaint_com"></td>
               </tr>
               <tr>
                 <th>Customer</th>
                 <td id="td_customer_com"></td>
                 <th>Nomor Surat Jalan</th>
                 <td id="td_nomor_surat_jalan_com"></td>
               </tr>
               <tr>
                 <th>Tanggal Order</th>
                 <td id="td_tanggal_order_com"></td>
                 <th style="vertical-align: top;">Complaint</th>
                 <td id="td_complaint_com"></td>
               </tr>
               <tr>
                <th>Supervisor Sales</th>
                <td id="td_supervisor_sls_com"></td>
                <th>Sales Yang Memesan</th>
                <td id="td_sales_order_com"></td>
              </tr>
              <tr>
                <th style="vertical-align: top;">Lampiran</th>
                <td id="td_lampiran_com"></td>
                <th>Pelapor</th>
                <td id="td_pelapor_com"></td>
              </tr>
               <tr>
                <th>Jumlah Karung</th>
                <td id="td_jumlah_karung_com"></td>
                <th>Quantity</th>
                <td id="td_quantity_com"></td>
              </tr>
              <tr>
                <th>Jumlah KG / Sak</th>
                <td id="td_jumlah_kg_sak_com"></td>
                <th>Jenis Karung</th>
                <td id="td_jenis_karung_com"></td>
              </tr>
              <tr>
                <th>Berat Timbangan</th>
                <td id="td_berat_timbangan_com"></td>
                <th>Unit Berat Timbangan</th>
                <td id="td_unit_berat_timbangan_com"></td>
              </tr>
              <tr>
                <th>Berat Perhitungan</th>
                <td id="td_berat_aktual_com"></td>
                <th>Unit Berat Perhitungan</th>
                <td id="td_unit_berat_aktual_com"></td>
              </tr>
              <tr>
                <th>Tanggal Pengiriman</th>
                <td id="td_tanggal_pengiriman_com"></td>
                <th>No Kendaraan</th>
                <td id="td_no_kendaraan_com"></td>
              </tr>
              <tr>
                <th>Area</th>
                <td id="td_area_com"></td>
                <th>Supervisor Logistik</th>
                <td id="td_supervisor_com"></td>
              </tr>
              <tr>
                <th>Pengiriman</th>
                <td id="td_pengiriman_com"></td>
                <th>Pengiriman Lain</th>
                <td id="td_pengiriman_lain_com"></td>
              </tr>
              <tr>
                <th>Nama Supir</th>
                <td id="td_nama_supir_com"></td>
                <th>Nama Kernet</th>
                <td id="td_nama_kernet_com"></td>
              </tr>
              <tr>
                <th style="vertical-align: top;">Nama Kuli</th>
                <td id="td_nama_kuli_com"></td>
                <th style="vertical-align: top;">Nama Stapel</th>
                <td id="td_nama_stapel_com"></td>
              </tr>
              <tr>
                <th>Jenis Kendaraan</th>
                <td id="td_jenis_kendaraan_com"></td>
                <th>Jenis Kendaraan Lain</th>
                <td id="td_jenis_kendaraan_lain_com"></td>
              </tr>
            </thead>
          </table>
          <div id="td_lihat_data_produksi"></div>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="lihat_complaint_produksi_flow" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
      <div class="row">
       <div class="col-lg-12 lihat-table">
        <table class="table table-bordered table-hover">
         <thead>
          <tr>
           <th>Nomor Complaint</th>
           <td id="td_nomor_complaint_prd"></td>
           <th style="vertical-align: top;">Evaluasi</th>
           <td id="td_evaluasi_prd"></td>
         </tr>
         <tr>
           <th style="vertical-align: top;">Solusi Internal</th>
           <td id="td_solusi_internal_prd"></td>
           <th style="vertical-align: top;">Lampiran</th>
           <td id="td_lampiran_prd"></td>
         </tr>
         <tr style="display: none;">
           <th style="vertical-align: top;">Solusi Customer</th>
           <td id="td_solusi_customer_prd"></td>
         </tr>
      </thead>
    </table>
    <div id="td_lihat_komitmen_produksi"></div>
  </div>
</div>
</div>
<div class="tab-pane fade" id="lihat_complaint_logistik_flow" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
  <div class="row">
   <div class="col-lg-12 lihat-table">
    <table class="table table-bordered table-hover">
     <thead>
      <tr>
       <th>Nomor Complaint</th>
       <td id="td_nomor_complaint_log"></td>
       <th style="vertical-align: top;">Evaluasi</th>
       <td id="td_evaluasi_log"></td>
    </tr>
    <tr>
     <th style="vertical-align: top;">Solusi Internal</th>
     <td id="td_solusi_internal_log"></td>
     <th style="vertical-align: top;">Lampiran</th>
     <td id="td_lampiran_log"></td>
    </tr>
    <tr style="display: none;">
     <th style="vertical-align: top;">Solusi Customer</th>
     <td id="td_solusi_customer_log"></td>
    </tr>
 </thead>
</table>
<div id="td_lihat_komitmen_logistik"></div>
</div>
</div>
</div>
<div class="tab-pane fade" id="lihat_complaint_sales_flow" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
      <div class="row">
       <div class="col-lg-12 lihat-table">
        <table class="table table-bordered table-hover">
         <thead>
         <tr>
           <th>Nomor Complaint</th>
           <td id="td_nomor_complaint_sls"></td>
           <th style="vertical-align: top;">Evaluasi</th>
           <td id="td_evaluasi_sls"></td>
         </tr>
         <tr>
           <th style="vertical-align: top;">Solusi Internal</th>
           <td id="td_solusi_internal_sls"></td>
           <th style="vertical-align: top;">Lampiran</th>
           <td id="td_lampiran_sls"></td>
         </tr>
         <tr>
           <th style="vertical-align: top;">Solusi Customer</th>
           <td id="td_solusi_customer_sls"></td>
           <th>Tanggal Customer Setuju</th>
           <td id="td_tanggal_customer_sls"></td>
         </tr>
       </thead>
     </table>
     <div id="td_lihat_komitmen_sales"></div>
   </div>
 </div>
</div>
<div class="tab-pane fade" id="lihat_complaint_timbangan_flow" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
      <div class="row">
       <div class="col-lg-12 lihat-table">
        <table class="table table-bordered table-hover">
         <thead>
          <tr>
           <th>Nomor Complaint</th>
           <td id="td_nomor_complaint_timbang"></td>
           <th style="vertical-align: top;">Evaluasi</th>
           <td id="td_evaluasi_timbang"></td>
         </tr>
         <tr>
           <th style="vertical-align: top;">Solusi Internal</th>
           <td id="td_solusi_internal_timbang"></td>
           <th style="vertical-align: top;">Lampiran</th>
           <td id="td_lampiran_timbang"></td>
         </tr>
         <tr style="display: none;">
           <th style="vertical-align: top;">Solusi Customer</th>
           <td id="td_solusi_customer_timbang"></td>
         </tr>
       </thead>
     </table>
     <div id="td_lihat_komitmen_timbangan"></div>
   </div>
 </div>
</div>
<div class="tab-pane fade" id="lihat_complaint_warehouse_flow" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
      <div class="row">
       <div class="col-lg-12 lihat-table">
        <table class="table table-bordered table-hover">
         <thead>
          <tr>
           <th>Nomor Complaint</th>
           <td id="td_nomor_complaint_ware"></td>
           <th style="vertical-align: top;">Evaluasi</th>
           <td id="td_evaluasi_ware"></td>
         </tr>
         <tr>
           <th style="vertical-align: top;">Solusi Internal</th>
           <td id="td_solusi_internal_ware"></td>
           <th style="vertical-align: top;">Lampiran</th>
           <td id="td_lampiran_ware"></td>
         </tr>
         <tr style="display: none;">
           <th style="vertical-align: top;">Solusi Customer</th>
           <td id="td_solusi_customer_ware"></td>
         </tr>
       </thead>
     </table>
     <div id="td_lihat_komitmen_warehouse"></div>
   </div>
 </div>
</div>
<div class="tab-pane fade" id="lihat_complaint_lab_flow" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
  <div class="row">
   <div class="col-lg-12 lihat-table">
    <table class="table table-bordered table-hover">
     <thead>
      <tr>
       <th>Nomor Complaint</th>
       <td id="td_nomor_complaint_lab"></td>
       <th style="vertical-align: top;">Lampiran</th>
       <td id="td_lampiran_lab"></td>
     </tr>
     <tr>
       <th style="vertical-align: top;">Suggestion</th>
       <td colspan="3" id="td_suggestion_lab"></td>
     </tr>
     <tr style="display: none;">
       <th style="vertical-align: top;">Solusi Customer</th>
       <td id="td_solusi_customer_lab"></td>
     </tr>
  </thead>
</table>
<div id="td_lihat_lab"></div>
</div>
</div>
</div>
<div class="tab-pane fade" id="lihat_complaint_lainnya_flow" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
  <div class="row">
   <div class="col-lg-12 lihat-table">
    <table class="table table-bordered table-hover">
     <thead>
      <tr>
       <th>Nomor Complaint</th>
       <td id="td_nomor_complaint_lny"></td>
       <th>Divisi</th>
       <td id="td_divisi_lny"></td>
     </tr>
     <tr>
       <th style="vertical-align: top;">Evaluasi</th>
       <td id="td_evaluasi_lny"></td>
       <th style="vertical-align: top;">Solusi Internal</th>
       <td id="td_solusi_internal_lny"></td>
     </tr>
     <tr>
        <th style="vertical-align: top;">Lampiran</th>
        <td colspan="3" id="td_lampiran_lny"></td>
      </tr>
     <tr style="display: none;">
       <th style="vertical-align: top;">Solusi Customer</th>
       <td id="td_solusi_customer_lny"></td>
     </tr>
   </thead>
 </table>
 <div id="td_lihat_komitmen_lainnya"></div>
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

<!--   <div class="modal fade" id="modal_lihat_complaint_logistik">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Complaint Logistik</h4>
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
               <th>Nomor Complaint</th>
               <td id="td_nomor_complaint_log"></td>
              </tr>
              <tr>
               <th>Tanggal Pengiriman</th>
               <td id="td_tanggal_pengiriman_log"></td>
              </tr>
              <tr>
               <th>Area</th>
               <td id="td_area_log"></td>
              </tr>
              <tr>
               <th>Supervisor</th>
               <td id="td_supervisor_log"></td>
              </tr>
              <tr>
               <th>Pengiriman</th>
               <td id="td_pengiriman_log"></td>
              </tr>
              <tr>
               <th>Pengiriman Lain</th>
               <td id="td_pengiriman_lain_log"></td>
              </tr>
              <tr>
               <th>Nama Supir</th>
               <td id="td_nama_supir_log"></td>
              </tr>
              <tr>
               <th>No Kendaraan</th>
               <td id="td_no_kendaraan_log"></td>
              </tr>
              <tr>
               <th>Jenis Kendaraan</th>
               <td id="td_jenis_kendaraan_log"></td>
              </tr>
              <tr>
               <th>Jenis Kendaraan Lain</th>
               <td id="td_jenis_kendaraan_lain_log"></td>
              </tr>
              <tr>
               <th>Analisa</th>
               <td id="td_analisa_log"></td>
              </tr>
              <tr>
               <th>Solusi</th>
               <td id="td_solusi_log"></td>
              </tr>
              <tr>
               <th>Lampiran</th>
               <td id="td_lampiran_log"></td>
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
  </div>
</div>

<div class="modal fade" id="modal_lihat_complaint_lainnya">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Complaint Lainnya</h4>
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
               <th>Nomor Complaint</th>
               <td id="td_nomor_complaint_lny"></td>
              </tr>
              <tr>
               <th>Divisi</th>
               <td id="td_divisi_lny"></td>
              </tr>
              <tr>
               <th>Analisa</th>
               <td id="td_analisa_lny"></td>
              </tr>
              <tr>
               <th>Solusi</th>
               <td id="td_solusi_lny"></td>
              </tr>
              <tr>
               <th>Lampiran</th>
               <td id="td_lampiran_lny"></td>
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
  </div>
</div> -->

<div class="modal fade" id="modal_list_komitmen">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">List of Actions</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs nav-tabs-list" id="custom-content-below-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#lihat_list_komitmen_sales" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Divisi Sales</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#lihat_list_komitmen_lainnya" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Divisi Lainnya</a>
          </li>
        </ul>
        <div class="tab-content tab-content-list" id="custom-content-below-tabContent">
          <div class="tab-pane fade show active" id="lihat_list_komitmen_sales" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
            <table id="list_komitmen_sales_table" style="width: 100%;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nomor Complaint</th>
                  <th>Komitmen</th>
                  <th>Selesai Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>
            </table>
          </div>
          <div class="tab-pane fade" id="lihat_list_komitmen_lainnya" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
            <table id="list_komitmen_lainnya_table" style="width: 100%;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nomor Complaint</th>
                  <th>Komitmen</th>
                  <th>Selesai Tanggal</th>
                  <th>Status</th>
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

<div class="modal fade" id="modal_proses_complaint_sales">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Proses Complaint Sales</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="salesform" id="salesform" action="javascript:void(0)" enctype="multipart/form-data">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="nomor_complaint_sls" id="nomor_complaint_sls" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="custid_sls" id="custid_sls" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="no_divisi_sls" id="no_divisi_sls" />
        </div>
        <div id="show_data_sales">
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5>Data Complaint dan Pengiriman</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label>Tanggal Order</label>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="tanggal_order_proc_sls" id="tanggal_order_proc_sls" autocomplete="off" placeholder="Tanggal Order" readonly>
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="sales_order_proc_sls">Sales Yang Memesan</label>
                      <input class="form-control" type="text" name="sales_order_proc_sls" id="sales_order_proc_sls" placeholder="Sales Yang Memesan" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="supervisor_sls_proc_sls">Supervisor Sales</label>
                      <input class="form-control" type="text" name="supervisor_sls_proc_sls" id="supervisor_sls_proc_sls" placeholder="Supervisor Sales" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="pelapor_proc_sls">Pelapor</label>
                      <input class="form-control" type="text" name="pelapor_proc_sls" id="pelapor_proc_sls" placeholder="Pelapor" readonly/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label>Tanggal Pengiriman</label>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="tanggal_pengiriman_proc_sls" id="tanggal_pengiriman_proc_sls" autocomplete="off" placeholder="Tanggal Pengiriman" readonly>
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="area_log_proc_sls">Area</label>
                      <select id="area_log_proc_sls" name="area_log_proc_sls" class="form-control" disabled>
                        <option value="" selected>Area</option>
                        <option value="1">Area 1</option>
                        <option value="2">Area 2</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="nama_supir_proc_sls">Nama Supir</label>
                      <input class="form-control" type="text" name="nama_supir_proc_sls" id="nama_supir_proc_sls" placeholder="Nama Supir" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="nama_kernet_proc_sls">Nama Kernet</label>
                      <input class="form-control" type="text" name="nama_kernet_proc_sls" id="nama_kernet_proc_sls" placeholder="Nama Kernet" readonly/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="nama_kuli_proc_sls">Nama Kuli</label>
                      <table class="table" id="dynamic_field_kuli_proc_sls" style="border: none;">  
                        <tr>  
                          <td><input type="text" name="nama_kuli_proc_sls[]" placeholder="Nama Kuli" class="form-control nama_kuli_proc_sls_list" readonly/></td> 
                        </tr>  
                      </table> 
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="nama_stapel_proc_sls">Nama Stapel</label>
                      <table class="table" id="dynamic_field_stapel_proc_sls" style="border: none;">  
                        <tr>  
                          <td><input type="text" name="nama_stapel_proc_sls[]" placeholder="Nama Stapel" class="form-control nama_stapel_proc_sls_list" readonly/></td>  
                        </tr>  
                      </table> 
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label for="no_kendaraan_proc_sls">No Kendaraan</label>
                      <input type="text" class="form-control" id="no_kendaraan_proc_sls" name="no_kendaraan_proc_sls" placeholder="No Kendaraan" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="pengiriman_proc_sls">Pengiriman</label>
                      <select id="pengiriman_proc_sls" name="pengiriman_proc_sls" class="form-control" disabled>
                        <option value="" selected>Pengiriman</option>
                        <option value="1">DSGM</option>
                        <option value="2">Ambil Sendiri</option>
                        <option value="3">Ekspedisi</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="jenis_kendaraan_proc_sls">Jenis Kendaraan</label>
                      <select id="jenis_kendaraan_proc_sls" name="jenis_kendaraan_proc_sls" class="form-control" disabled>
                        <option value="" selected>Jenis Kendaraan</option>
                        <option value="1">Engkel</option>
                        <option value="2">Colt Diesel</option>
                        <option value="3">Fuso</option>
                        <option value="4">Tronton</option>
                        <option value="5">Trailer</option>
                        <option value="6">Kontainer</option>
                        <option value="7">Lainnya</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="supervisor_log_proc_sls">Supervisor</label>
                      <input class="form-control" type="text" name="supervisor_log_proc_sls" id="supervisor_log_proc_sls" placeholder="Supervisor" readonly/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3"></div>
                  <div class="col-3">
                    <div class="form-group" id="divpengiriman_proc_sls">
                      <input type="text" class="form-control" id="pengiriman_lain_proc_sls" name="pengiriman_lain_proc_sls" placeholder="Pengiriman Lainnya" style="display: none;" readonly>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group" id="divjeniskendaraan_proc_sls">
                      <input type="text" class="form-control" id="jenis_kendaraan_lain_proc_sls" name="jenis_kendaraan_lain_proc_sls" placeholder="Jenis Kendaraan Lainnya" style="display: none;" readonly>
                    </div>
                  </div>
                  <div class="col-3"></div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label for="jumlah_karung_proc_sls">Jumlah Karung</label>
                      <input class="form-control" type="text" name="jumlah_karung_proc_sls" id="jumlah_karung_proc_sls" placeholder="Jumlah Karung" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="quantity_proc_sls">Quantity</label>
                      <input class="form-control" type="text" name="quantity_proc_sls" id="quantity_proc_sls" placeholder="Quantity" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="jumlah_kg_sak_proc_sls">&nbsp</label>
                      <select id="jumlah_kg_sak_proc_sls" name="jumlah_kg_sak_proc_sls" class="form-control" disabled>
                        <option value="" selected>Jumlah KG / Sak</option>
                        <option value="20">20 KG</option>
                        <option value="25">25 KG</option>
                        <option value="30">30 KG</option>
                        <option value="40">40 KG</option>
                        <option value="50">50 KG</option>
                        <option value="800">800 KG</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="jenis_karung_proc_sls">&nbsp</label>
                      <select id="jenis_karung_proc_sls" name="jenis_karung_proc_sls" class="form-control" disabled>
                        <option value="" selected>Jenis Karung</option>
                        <option value="1">Woven Bag</option>
                        <option value="2">Paper Bag</option>
                        <option value="3">Jumbo Bag</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label for="berat_timbangan_proc_sls">Berat Timbangan</label>
                      <input class="form-control" type="text" name="berat_timbangan_proc_sls" id="berat_timbangan_proc_sls" placeholder="Berat Timbangan" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="unit_berat_timbangan_proc_sls">&nbsp</label>
                      <select id="unit_berat_timbangan_proc_sls" name="unit_berat_timbangan_proc_sls" class="form-control" disabled>
                        <option value="" selected>Unit Satuan</option>
                        <option value="1">KG</option>
                        <option value="2">TON</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="berat_aktual_proc_sls">Berat Perhitungan</label>
                      <input class="form-control" type="text" name="berat_aktual_proc_sls" id="berat_aktual_proc_sls" placeholder="Berat Perhitungan" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="unit_berat_aktual_proc_sls">&nbsp</label>
                      <select id="unit_berat_aktual_proc_sls" name="unit_berat_aktual_proc_sls" class="form-control" disabled>
                        <option value="" selected>Unit Satuan</option>
                        <option value="1">KG</option>
                        <option value="2">TON</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="evaluasi_sls">Evaluasi</label>
              <textarea class="form-control" rows="3" name="evaluasi_sls" id="evaluasi_sls" placeholder="Evaluasi"></textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="solusi_internal_sls">Solusi Internal</label>
              <textarea class="form-control" rows="3" name="solusi_internal_sls" id="solusi_internal_sls" placeholder="Solusi Internal"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="upload_file_sls">Lampiran</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="upload_file_sls" name="upload_file_sls">
                  <label class="custom-file-label" for="upload_file_sls">Choose file</label>
                </div>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="solusi_customer_sls">Solusi Customer</label>
              <textarea class="form-control" rows="3" name="solusi_customer_sls" id="solusi_customer_sls" placeholder="Solusi Customer"></textarea>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label>Tanggal Customer Menyetujui</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="tanggal_customer" id="tanggal_customer" autocomplete="off" placeholder="Tanggal Customer Menyetujui">
              </div>
              <!-- /.input group -->
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-5">
            <div class="form-group">
              <label for="komitmen_sls">Tindakan / Komitmen</label>
              <textarea class="form-control" rows="3" name="komitmen_sls" id="komitmen_sls" placeholder="Tindakan / Komitmen"></textarea>
            </div>
          </div>
          <div class="col-5">
            <div class="form-group">
              <label>Selesai Tanggal</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="selesai_tanggal_komitmen_sls" id="selesai_tanggal_komitmen_sls" autocomplete="off" placeholder="Selesai Tanggal">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label>&nbsp</label>
              <button type="button" class="btn btn-success" id="save_input_komitmen_sls">Save</button>
            </div>
          </div>
        </div>
        <table id="list_komitmen_sls_table" style="width: 100%;" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Tanggal Selesai</th>
              <th>Komitmen</th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn_save_sls">Save changes</button>
      </div>
    </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_proses_complaint_lainnya">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Proses Complaint Lainnya</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="lainnyaform" id="lainnyaform" action="javascript:void(0)" enctype="multipart/form-data">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="nomor_complaint_lny" id="nomor_complaint_lny" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="custid_lny" id="custid_lny" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="no_divisi_lny" id="no_divisi_lny" />
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="divisi_lny">Divisi</label>
              <input class="form-control" type="text" name="divisi_lny" id="divisi_lny" placeholder="Divisi" />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="upload_file_lny">Lampiran</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="upload_file_lny" name="upload_file_lny">
                  <label class="custom-file-label" for="upload_file_lny">Choose file</label>
                </div>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="evaluasi_lny">Evaluasi</label>
              <textarea class="form-control" rows="3" name="evaluasi_lny" id="evaluasi_lny" placeholder="Evaluasi"></textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="solusi_internal_lny">Solusi Internal</label>
              <textarea class="form-control" rows="3" name="solusi_internal_lny" id="solusi_internal_lny" placeholder="Solusi Internal"></textarea>
            </div>
          </div>
          <div class="col-6" style="display: none;">
            <div class="form-group">
              <label for="solusi_customer_lny">Solusi Customer</label>
              <textarea class="form-control" rows="5" name="solusi_customer_lny" id="solusi_customer_lny" placeholder="Solusi Customer"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-5">
            <div class="form-group">
              <label for="komitmen_lny">Tindakan / Komitmen</label>
              <textarea class="form-control" rows="3" name="komitmen_lny" id="komitmen_lny" placeholder="Tindakan / Komitmen"></textarea>
            </div>
          </div>
          <div class="col-5">
            <div class="form-group">
              <label>Finish Date Komitmen</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="selesai_tanggal_komitmen_lny" id="selesai_tanggal_komitmen_lny" autocomplete="off" placeholder="Selesai Tanggal">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label>&nbsp</label>
              <button type="button" class="btn btn-success" id="save_input_komitmen_lny">Save</button>
            </div>
          </div>
        </div>
        <table id="list_komitmen_lny_table" style="width: 100%;" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Tanggal Selesai</th>
              <th>Komitmen</th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="btn_tidak_terlibat_lny" data-dismiss="modal" data-toggle="modal" data-target="#modal_alasan_tidak_terlibat">Tidak Terlibat</button>
        <button type="button" class="btn btn-success" id="btn_terlibat_lny" style="display: none;">Terlibat</button>
        <button type="submit" class="btn btn-primary" id="btn_save_lny">Save</button>
      </div>
    </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_alasan_tidak_terlibat">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Alasan Tidak Terlibat?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="alasantidakterlibatform" id="alasantidakterlibatform" action="javascript:void(0)">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="nomor_complaint_alasan" id="nomor_complaint_alasan" />
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="alasan_tidak_terlibat">Alasan Tidak Terlibat</label>
              <textarea class="form-control" rows="3" name="alasan_tidak_terlibat" id="alasan_tidak_terlibat" placeholder="Alasan Tidak Terlibat"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_proses_complaint_lainnya">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_edit_complaint">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Complaint</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="editform" id="editform" action="javascript:void(0)" enctype="multipart/form-data">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="edit_nomor_complaint" id="edit_nomor_complaint" />
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label>Tanggal Complaint</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="edit_tanggal_complaint" id="edit_tanggal_complaint" autocomplete="off" placeholder="Tanggal Complaint" disabled>
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="edit_custid">Customer</label>
              <select id="edit_custid" name="edit_custid" class="form-control select2 customer-edit" style="width: 100%;">
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="edit_nomor_surat_jalan">Nomor Surat jalan</label>
              <input class="form-control" type="text" name="edit_nomor_surat_jalan" id="edit_nomor_surat_jalan" placeholder="Nomor Surat Jalan" />
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="edit_sales_order">Sales Yang Memesan</label>
              <input class="form-control" type="text" name="edit_sales_order" id="edit_sales_order" placeholder="Sales Yang Memesan" />
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label>Tanggal Order</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="edit_tanggal_order" id="edit_tanggal_order" autocomplete="off" placeholder="Tanggal Order">
              </div>
              <!-- /.input group -->
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="edit_supervisor_sls">Supervisor Sales</label>
              <input class="form-control" type="text" name="edit_supervisor_sls" id="edit_supervisor_sls" placeholder="Supervisor Sales" />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="edit_pelapor">Pelapor</label>
              <input class="form-control" type="text" name="edit_pelapor" id="edit_pelapor" placeholder="Pelapor" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-5">
            <div class="form-group">
              <label for="edit_txt_complaint">Complaint</label>
              <textarea class="form-control" rows="3" name="edit_txt_complaint" id="edit_txt_complaint" placeholder="Complaint"></textarea>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="edit_upload_file">Upload File</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="edit_upload_file" name="edit_upload_file">
                  <label class="custom-file-label" for="edit_upload_file">Choose file</label>
                </div>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
          </div>
          <div class="col-3">
            <div class="form-group">
              <div class="label-flex">
                <label>&nbsp</label>
              </div>
              <div class="custom-control custom-radio radio-control">
                <a target="_blank" id="lihat_lampiran" class="btn btn-primary save-btn-in">Lihat Lampiran</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <div class="form-group">
              <label for="edit_jumlah_karung">Jumlah Karung</label>
              <input class="form-control" type="text" name="edit_jumlah_karung" id="edit_jumlah_karung" placeholder="Jumlah Karung" />
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="edit_quantity">Quantity</label>
              <input class="form-control" type="text" name="edit_quantity" id="edit_quantity" placeholder="Quantity" />
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="edit_jumlah_kg_sak">&nbsp</label>
              <select id="edit_jumlah_kg_sak" name="edit_jumlah_kg_sak" class="form-control">
                <option value="" selected>Jumlah KG / Sak</option>
                <option value="20">20 KG</option>
                <option value="25">25 KG</option>
                <option value="30">30 KG</option>
                <option value="40">40 KG</option>
                <option value="50">50 KG</option>
                <option value="800">800 KG</option>
              </select>
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="edit_jenis_karung">&nbsp</label>
              <select id="edit_jenis_karung" name="edit_jenis_karung" class="form-control">
                <option value="" selected>Jenis Karung</option>
                <option value="1">Woven Bag</option>
                <option value="2">Paper Bag</option>
                <option value="3">Jumbo Bag</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <div class="form-group">
              <label for="edit_berat_timbangan">Berat Timbangan</label>
              <input class="form-control" type="text" name="edit_berat_timbangan" id="edit_berat_timbangan" placeholder="Berat Timbangan" />
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="edit_unit_berat_timbangan">&nbsp</label>
              <select id="edit_unit_berat_timbangan" name="edit_unit_berat_timbangan" class="form-control">
                <option value="" selected>Unit Satuan</option>
                <option value="1">KG</option>
                <option value="2">TON</option>
              </select>
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="edit_berat_aktual">Berat Perhitungan</label>
              <input class="form-control" type="text" name="edit_berat_aktual" id="edit_berat_aktual" placeholder="Berat Perhitungan" readonly/>
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="edit_unit_berat_aktual">&nbsp</label>
              <select id="edit_unit_berat_aktual" name="edit_unit_berat_aktual" class="form-control" disabled>
                <option value="" selected>Unit Satuan</option>
                <option value="1">KG</option>
                <option value="2">TON</option>
              </select>
              <input class="form-control" type="hidden" name="edit_input_unit_berat_aktual" id="edit_input_unit_berat_aktual"/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Logistik</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label>Tanggal Pengiriman</label>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="edit_tanggal_pengiriman" id="edit_tanggal_pengiriman" autocomplete="off" placeholder="Tanggal Pengiriman">
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_area_log">Area</label>
                      <select id="edit_area_log" name="edit_area_log" class="form-control">
                        <option value="" selected>Area</option>
                        <option value="1">Area 1</option>
                        <option value="2">Area 2</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_supervisor_log">Supervisor</label>
                      <input class="form-control" type="text" name="edit_supervisor_log" id="edit_supervisor_log" placeholder="Supervisor" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_nama_supir">Nama Supir</label>
                      <input class="form-control" type="text" name="edit_nama_supir" id="edit_nama_supir" placeholder="Nama Supir" />
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_nama_kernet">Nama Kernet</label>
                      <input class="form-control" type="text" name="edit_nama_kernet" id="edit_nama_kernet" placeholder="Nama Kernet" />
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_no_kendaraan">No Kendaraan</label>
                      <input class="form-control" type="text" name="edit_no_kendaraan" id="edit_no_kendaraan" placeholder="No Kendaraan" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="edit_nama_kuli">Nama Kuli</label>
                      <table class="table" id="edit_dynamic_field_kuli" style="border: none;">  
                        <tr>  
                          <td><input type="text" name="edit_nama_kuli[]" placeholder="Nama Kuli" class="form-control edit_nama_kuli_list" /></td>  
                          <td><button type="button" name="edit_add_kuli" id="edit_add_kuli" class="btn btn-success">Add More</button></td>  
                        </tr>  
                      </table> 
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="edit_nama_stapel">Nama Stapel</label>
                      <table class="table" id="edit_dynamic_field_stapel" style="border: none;">  
                        <tr>  
                          <td><input type="text" name="edit_nama_stapel[]" placeholder="Nama Stapel" class="form-control edit_nama_stapel_list" /></td>  
                          <td><button type="button" name="edit_add_stapel" id="edit_add_stapel" class="btn btn-success">Add More</button></td>  
                        </tr>  
                      </table> 
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="edit_pengiriman">Pengiriman</label>
                      <select id="edit_pengiriman" name="edit_pengiriman" class="form-control">
                        <option value="" selected>Pengiriman</option>
                        <option value="1">DSGM</option>
                        <option value="2">Ambil Sendiri</option>
                        <option value="3">Ekspedisi</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="edit_jenis_kendaraan">Jenis Kendaraan</label>
                      <select id="edit_jenis_kendaraan" name="edit_jenis_kendaraan" class="form-control">
                        <option value="" selected>Jenis Kendaraan</option>
                        <option value="1">Engkel</option>
                        <option value="2">Colt Diesel</option>
                        <option value="3">Fuso</option>
                        <option value="4">Tronton</option>
                        <option value="5">Trailer</option>
                        <option value="6">Kontainer</option>
                        <option value="7">Lainnya</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group" id="edit_divpengiriman">
                      <input type="text" class="form-control" id="edit_pengiriman_lain" name="edit_pengiriman_lain" placeholder="Pengiriman Lainnya" style="display: none;">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group" id="edit_divjeniskendaraan">
                      <input type="text" class="form-control" id="edit_jenis_kendaraan_lain" name="edit_jenis_kendaraan_lain" placeholder="Jenis Kendaraan Lainnya" style="display: none;">
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
              <div class="card-header">
                <h4>Produksi</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_no_lot">No Lot</label>
                      <input class="form-control" type="text" name="edit_no_lot" id="edit_no_lot" placeholder="No Lot" />
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Tanggal Produksi</label>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" name="edit_tanggal_produksi" id="edit_tanggal_produksi" autocomplete="off" placeholder="Tanggal Produksi">
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Products</label>
                      <select id="edit_produk" name="edit_produk" class="form-control">
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <input class="form-control" type="hidden" name="edit_no_lot_lama" id="edit_no_lot_lama" placeholder="No Lot" />
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_area_prd">Area</label>
                      <select id="edit_area_prd" name="edit_area_prd" class="form-control">
                        <option value="" selected>Area</option>
                        <option value="1">Area 1</option>
                        <option value="2">Area 2</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_mesin">Mesin</label>
                      <input class="form-control" type="text" name="edit_mesin" id="edit_mesin" placeholder="Mesin" />
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_supervisor_prd">Supervisor</label>
                      <input class="form-control" type="text" name="edit_supervisor_prd" id="edit_supervisor_prd" placeholder="Supervisor" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="edit_petugas">Petugas</label>
                      <table class="table" id="edit_dynamic_field_petugas" style="border: none;">  
                        <tr>  
                          <td><input type="text" name="edit_petugas[]" placeholder="Petugas" class="form-control edit_petugas_list" /></td>  
                          <td><button type="button" name="edit_add_petugas" id="edit_add_petugas" class="btn btn-success">Add More</button></td>  
                        </tr>  
                      </table> 
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <div class="label-flex">
                        <label for="edit_bermasalah">Bermasalah?</label>
                      </div>
                      <div class="custom-control custom-radio" style="margin-top: 5px;">
                        <input class="form-control custom-control-input" type="radio" id="edit_bermasalah" name="edit_input_bermasalah" value="Ya" checked>
                        <label for="edit_bermasalah" class="custom-control-label">Bermasalah</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <div class="label-flex">
                        <label for="edit_tidak_bermasalah">&nbsp</label>
                      </div>
                      <div class="custom-control custom-radio" style="margin-top: 5px;">
                        <input class="form-control custom-control-input" type="radio" id="edit_tidak_bermasalah" name="edit_input_bermasalah" value="Tidak">
                        <label for="edit_tidak_bermasalah" class="custom-control-label">Tidak Bermasalah</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label>&nbsp</label>
                      <button type="button" class="btn btn-success" id="save_new_edit_produksi">Save</button>
                      <button type="button" class="btn btn-success" id="save_edit_produksi" style="display: none;">Edit Save</button>
                    </div>
                  </div>
                </div>
                <table id="edit_list_no_lot_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                  <thead>
                    <tr>
                      <th class="not-mobile">No</th>
                      <th>No Lot</th>
                      <th>Tgl Produksi</th>
                      <th>Produk</th>
                      <th>Area</th>
                      <th>Mesin</th>
                      <th>Bermasalah</th>
                      <th class="min-mobile"></th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save_edit_complaint">Save changes</button>
      </div>
    </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_edit_complaint_sales">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Complaint Sales</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="editsalesform" id="editsalesform" action="javascript:void(0)" enctype="multipart/form-data">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="edit_nomor_complaint_sls" id="edit_nomor_complaint_sls" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="edit_custid_sls" id="edit_custid_sls" />
        </div>
        <div class="row">
          <div class="col-5">
            <div class="form-group">
              <label for="edit_evaluasi_sls">Evaluasi</label>
              <textarea class="form-control" rows="3" name="edit_evaluasi_sls" id="edit_evaluasi_sls" placeholder="Evaluasi"></textarea>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="edit_upload_file_sls">Lampiran</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="edit_upload_file_sls" name="edit_upload_file_sls">
                  <label class="custom-file-label" for="edit_upload_file_sls">Choose file</label>
                </div>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
          </div>
          <div class="col-3">
            <div class="form-group">
              <div class="label-flex">
                <label>&nbsp</label>
              </div>
              <div class="custom-control custom-radio radio-control">
                <a target="_blank" id="lihat_lampiran_sls" class="btn btn-primary save-btn-in">Lihat Lampiran</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="edit_solusi_internal_sls">Solusi Internal</label>
              <textarea class="form-control" rows="3" name="edit_solusi_internal_sls" id="edit_solusi_internal_sls" placeholder="Solusi Internal"></textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="edit_solusi_customer_sls">Solusi Customer</label>
              <textarea class="form-control" rows="3" name="edit_solusi_customer_sls" id="edit_solusi_customer_sls" placeholder="Solusi Customer"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label>Tanggal Customer Menyetujui</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="edit_tanggal_customer" id="edit_tanggal_customer" autocomplete="off" placeholder="Tanggal Customer Menyetujui">
              </div>
              <!-- /.input group -->
            </div>
          </div>
        </div>
        <div class="row" id="div_edit_komitmen_sls">
          <div class="col-5">
            <div class="form-group">
              <label for="edit_komitmen_sls">Tindakan / Komitmen</label>
              <textarea class="form-control" rows="3" name="edit_komitmen_sls" id="edit_komitmen_sls" placeholder="Tindakan / Komitmen"></textarea>
            </div>
          </div>
          <div class="col-5">
            <div class="form-group">
              <label>Finish Date Komitmen</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="edit_selesai_tanggal_komitmen_sls" id="edit_selesai_tanggal_komitmen_sls" autocomplete="off" placeholder="Finish Date Komitmen">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <textarea class="form-control" name="edit_komitmen_lama_sls" id="edit_komitmen_lama_sls" style="display: none;"></textarea>
          <input type="hidden" class="form-control" name="edit_selesai_tanggal_komitmen_lama_sls" id="edit_selesai_tanggal_komitmen_lama_sls" autocomplete="off">
          <div class="col-2">
            <div class="form-group">
              <label>&nbsp</label>
              <button type="button" class="btn btn-success" id="save_new_edit_komitmen_sls">Save</button>
              <button type="button" class="btn btn-success" id="save_edit_komitmen_sls" style="display: none;">Edit</button>
            </div>
          </div>
        </div>
        <table id="edit_list_komitmen_sls_table" style="width: 100%;" class="table table-bordered table-hover responsive">
          <thead>
            <tr>
              <th>Tanggal Selesai</th>
              <th>Komitmen</th>
              <th></th>
            </tr>
          </thead>
        </table>
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

<div class="modal fade" id="modal_edit_complaint_lainnya">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Complaint Lainnya</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="editlainnyaform" id="editlainnyaform" action="javascript:void(0)" enctype="multipart/form-data">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="edit_nomor_complaint_lny" id="edit_nomor_complaint_lny" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="edit_custid_lny" id="edit_custid_lny" />
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="edit_divisi_lny">Divisi</label>
              <input class="form-control" type="text" name="edit_divisi_lny" id="edit_divisi_lny" placeholder="Divisi" />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="edit_upload_file_lny">Lampiran</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="edit_upload_file_lny" name="edit_upload_file_lny">
                  <label class="custom-file-label" for="edit_upload_file_lny">Choose file</label>
                </div>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <div class="label-flex">
                <label>&nbsp</label>
              </div>
              <div class="custom-control custom-radio radio-control">
                <a target="_blank" id="lihat_lampiran_lny" class="btn btn-primary save-btn-in">Lihat Lampiran</a>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="edit_evaluasi_lny">Evaluasi</label>
              <textarea class="form-control" rows="3" name="edit_evaluasi_lny" id="edit_evaluasi_lny" placeholder="Evaluasi"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="edit_solusi_internal_lny">Solusi Internal</label>
              <textarea class="form-control" rows="5" name="edit_solusi_internal_lny" id="edit_solusi_internal_lny" placeholder="Solusi Internal"></textarea>
            </div>
          </div>
          <div class="col-6" style="display: none;">
            <div class="form-group">
              <label for="edit_solusi_customer_lny">Solusi Customer</label>
              <textarea class="form-control" rows="5" name="edit_solusi_customer_lny" id="edit_solusi_customer_lny" placeholder="Solusi Customer"></textarea>
            </div>
          </div>
        </div>
        <div class="row" id="div_edit_komitmen_lny">
          <div class="col-5">
            <div class="form-group">
              <label for="edit_komitmen_lny">Tindakan / Komitmen</label>
              <textarea class="form-control" rows="3" name="edit_komitmen_lny" id="edit_komitmen_lny" placeholder="Tindakan / Komitmen"></textarea>
            </div>
          </div>
          <div class="col-5">
            <div class="form-group">
              <label>Finish Date Komitmen</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="edit_selesai_tanggal_komitmen_lny" id="edit_selesai_tanggal_komitmen_lny" autocomplete="off" placeholder="Finish Date Komitmen">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <textarea class="form-control" name="edit_komitmen_lama_lny" id="edit_komitmen_lama_lny" style="display: none;"></textarea>
          <input type="hidden" class="form-control" name="edit_selesai_tanggal_komitmen_lama_lny" id="edit_selesai_tanggal_komitmen_lama_lny" autocomplete="off">
          <div class="col-2">
            <div class="form-group">
              <label>&nbsp</label>
              <button type="button" class="btn btn-success" id="save_new_edit_komitmen_lny">Save</button>
              <button type="button" class="btn btn-success" id="save_edit_komitmen_lny" style="display: none;">Edit</button>
            </div>
          </div>
        </div>
        <table id="edit_list_komitmen_lny_table" style="width: 100%;" class="table table-bordered table-hover responsive">
          <thead>
            <tr>
              <th>Tanggal Selesai</th>
              <th>Komitmen</th>
              <th></th>
            </tr>
          </thead>
        </table>
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

<div class="modal fade" id="modal_validasi_complaint">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Validasi Complaint?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input class="form-control" type="hidden" name="val_nomor_complaint" id="val_nomor_complaint" />
        <table id="list_of_action_table" style="width: 100%;" class="table table-bordered table-hover responsive">
          <thead>
            <tr>
              <th>No</th>
              <th>Divisi</th>
              <th>Komitmen</th>
              <th>Selesai Tanggal</th>
            </tr>
          </thead>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="validate_complaint" class="btn btn-primary">Validasi</button>
      </div>
    </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_logbook">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="title_modal_logbook"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="logbook_complaint_table" style="width: 100%;" class="table table-bordered table-hover responsive">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Divisi</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    $(document).ready(function(){
      let key = "{{ env('MIX_APP_KEY') }}";

      var target = $('.nav-tabs a').attr("href");

      var tipe_user = "{{ Session::get('tipe_user')}}";

      var any_nomor = "{{ $any_nomor ?? '' }}";

      if(any_nomor.length > 10){
        encryptStr = CryptoJS.enc.Base64.parse(any_nomor);
        let encryptData = encryptStr.toString(CryptoJS.enc.Utf8);
        encryptData = JSON.parse(encryptData);
        let iv = CryptoJS.enc.Base64.parse(encryptData.iv);
        var decrypted = CryptoJS.AES.decrypt(encryptData.value,  CryptoJS.enc.Utf8.parse(key.substr(7)), {
          iv : iv,
          mode: CryptoJS.mode.CBC,
          padding: CryptoJS.pad.Pkcs7
        });
        decrypted = CryptoJS.enc.Utf8.stringify(decrypted);
        any_nomor = decrypted;
      }

      var type = "{{ $tipe_complaint ?? '' }}";

      if(type.length > 10){
        encryptStr = CryptoJS.enc.Base64.parse(type);
        let encryptData = encryptStr.toString(CryptoJS.enc.Utf8);
        encryptData = JSON.parse(encryptData);
        let iv = CryptoJS.enc.Base64.parse(encryptData.iv);
        var decrypted = CryptoJS.AES.decrypt(encryptData.value,  CryptoJS.enc.Utf8.parse(key.substr(7)), {
          iv : iv,
          mode: CryptoJS.mode.CBC,
          padding: CryptoJS.pad.Pkcs7
        });
        decrypted = CryptoJS.enc.Utf8.stringify(decrypted);
        type = decrypted;
      }

      var table = $('#konfirmasi_complaint_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ route("complaintkonf.index") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        },
        data : function( d ) {
          d.nomor = any_nomor;
        }
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });

      if(any_nomor != '' && type == 3){
        table.ajax.url('{{ url("complaint/specific") }}').load();
        $('#tipe_complaint').find('option:selected').remove().end();
      }else if(any_nomor != '' && type == 6){
        table.ajax.url('{{ url("complaint_sales/specific") }}').load();
        $('#tipe_complaint').find('option:selected').remove().end();
      }else if(any_nomor != '' && type == 10){
        table.ajax.url('{{ url("complaint_lainnya/specific") }}').load();
        $('#tipe_complaint').find('option:selected').remove().end();
      }

      $("#tipe_complaint").change(function() {
        if ($(this).val() == 3) {
          $('#complaint_sales').show();
          $('#complaint_lainnya').hide();
          $('#konfirmasi_complaint').hide();
          if(tipe_user == 1){
            $('#complaint_sales_semua_admin_table').DataTable().destroy();
            load_data_sales_semua_admin();
          }else{
            $('#complaint_sales_semua_table').DataTable().destroy();
            load_data_sales_semua();
          }
          $('.nav-tabs a').on('shown.bs.tab', function (e) {
            target = $(e.target).attr("href");
            if(target == '#semua_sales'){
              $('#complaint_sales_semua_table').DataTable().destroy();
              load_data_sales_semua();
            }else if(target == '#semua_sales_admin'){
              $('#complaint_sales_semua_admin_table').DataTable().destroy();
              load_data_sales_semua_admin();
            }else if(target == '#proses_sales'){
              $('#complaint_sales_proses_table').DataTable().destroy();
              load_data_sales_proses();
            }else if(target == '#diproses_sales'){
              $('#complaint_sales_diproses_table').DataTable().destroy();
              load_data_sales_diproses();
            }else if(target == '#valid_sales'){
              $('#complaint_sales_valid_table').DataTable().destroy();
              load_data_sales_valid();
            }else if(target == '#selesai_sales'){
              $('#complaint_sales_selesai_table').DataTable().destroy();
              load_data_sales_selesai();
            }
          });
        }else if ($(this).val() == 4) {
          $('#complaint_sales').hide();
          $('#complaint_lainnya').show();
          $('#konfirmasi_complaint').hide();
          if(tipe_user == 1){
            $('#complaint_lainnya_semua_admin_table').DataTable().destroy();
            load_data_lainnya_semua_admin();
          }else{
            $('#complaint_lainnya_semua_table').DataTable().destroy();
            load_data_lainnya_semua();
          }
          $('.nav-tabs a').on('shown.bs.tab', function (e) {
            target = $(e.target).attr("href");
            if(target == '#semua_lainnya'){
              $('#complaint_lainnya_semua_table').DataTable().destroy();
              load_data_lainnya_semua();
            }else if(target == '#semua_lainnya_admin'){
              $('#complaint_lainnya_semua_admin_table').DataTable().destroy();
              load_data_lainnya_semua_admin();
            }else if(target == '#proses_lainnya'){
              $('#complaint_lainnya_proses_table').DataTable().destroy();
              load_data_lainnya_proses();
            }else if(target == '#diproses_lainnya'){
              $('#complaint_lainnya_diproses_table').DataTable().destroy();
              load_data_lainnya_diproses();
            }else if(target == '#valid_lainnya'){
              $('#complaint_lainnya_valid_table').DataTable().destroy();
              load_data_lainnya_valid();
            }else if(target == '#selesai_lainnya'){
              $('#complaint_lainnya_selesai_table').DataTable().destroy();
              load_data_lainnya_selesai();
            }
          });
        }else if ($(this).val() == 5) {
          $('#complaint_sales').hide();
          $('#complaint_lainnya').hide();
          $('#konfirmasi_complaint').show();
          $('#konfirmasi_complaint_table').DataTable().destroy();
          load_data_konfirmasi();
        }else {
          $('#complaint_sales').hide();
          $('#complaint_lainnya').hide();
          $('#konfirmasi_complaint').hide();
        }
      });

     // function load_data_produksi(from_date = '', to_date = '')
     // {
     //  table = $('#complaint_produksi_table').DataTable({
     //   processing: true,
     //   serverSide: true,
     //   ajax: {
     //    url:'{{ route("complaintprod.index") }}',
     //    data:{from_date:from_date, to_date:to_date}
     //   },
     //   dom: 'Bfrtip',
     //   buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
     //   columns: [
     //    {
     //     data:'nomor_complaint',
     //     name:'nomor_complaint'
     //    },
     //    {
     //     data:'tanggal_complaint',
     //     name:'tanggal_complaint'
     //    },
     //    {
     //     data:'custid',
     //     name:'custid'
     //    },
     //    {
     //     data:'nama_customer',
     //     name:'nama_customer'
     //    },
     //    {
     //     data:'nomor_surat_jalan',
     //     name:'nomor_surat_jalan',
     //     defaultContent: '<i>--</i>'
     //    },
     //    {
     //     data:'complaint',
     //     name:'complaint'
     //    },
     //    {
     //     data:'divisi',
     //     name:'divisi'
     //    },
     //    {
     //     data:'status',
     //     name:'status'
     //    },
     //    {
     //     data:'lampiran',
     //     name:'lampiran',
     //     defaultContent: '<i>--</i>',
     //     'render' : function(data, type, row, meta){
     //        if(data === null){
     //          data = '--';  
     //        }else{
              
     //          data = '<a target="_blank" href="' + 'data_file/' + data + '">Lihat Lampiran</a>';
     //        }
     //        return data;
     //     }
     //    },
     //    {
     //     data:'action',
     //     name:'action'
     //    }
     //   ]
     //  });
     // }

      function load_data_komitmen_lainnya()
      {
        table = $('#list_komitmen_lainnya_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("lainnya_complaint/list_komitmen") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
         data:'DT_RowIndex',
         name:'DT_RowIndex'
         },
         {
           data:'nomor_complaint',
           name:'nomor_complaint'
         },
         {
           data:'komitmen',
           name:'komitmen'
         },
         {
           data:'selesai_tanggal_komitmen',
           name:'selesai_tanggal_komitmen'
         },
         {
           data:'status',
           name:'status'
         }
        ]
        });
      }

      function load_data_komitmen_sales()
      {
        table = $('#list_komitmen_sales_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("sales_complaint/list_komitmen") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
         data:'DT_RowIndex',
         name:'DT_RowIndex'
         },
         {
           data:'nomor_complaint',
           name:'nomor_complaint'
         },
         {
           data:'komitmen',
           name:'komitmen'
         },
         {
           data:'selesai_tanggal_komitmen',
           name:'selesai_tanggal_komitmen'
         },
         {
           data:'status',
           name:'status'
         }
        ]
        });
      }

     function load_data_sales_semua(from_date = '', to_date = '')
     {
      table = $('#complaint_sales_semua_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/sales/semua") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[7, 'desc'], [1,'desc']],
       createdRow: function(row, data, dataIndex){
          if(data.status == 'Done; '){
            $(row).attr('style', 'background-color:#b8fbff');
          }else{
            $(row).attr('style', 'background-color:#edf7b0');
          }
       },
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_sales_semua_admin(from_date = '', to_date = '')
     {
      table = $('#complaint_sales_semua_admin_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/sales/semua_admin") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       createdRow: function(row, data, dataIndex){
          if(data.status == 'Done; '){
            $(row).attr('style', 'background-color:#b8fbff');
          }else{
            $(row).attr('style', 'background-color:#edf7b0');
          }
       },
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_sales_proses(from_date = '', to_date = '')
     {
      table = $('#complaint_sales_proses_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/sales/proses") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_sales_diproses(from_date = '', to_date = '')
     {
      table = $('#complaint_sales_diproses_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/sales/diproses") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_sales_valid(from_date = '', to_date = '')
     {
      table = $('#complaint_sales_valid_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/sales/valid") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_sales_selesai(from_date = '', to_date = '')
     {
      table = $('#complaint_sales_selesai_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/sales/selesai") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_lainnya_semua(from_date = '', to_date = '')
     {
      table = $('#complaint_lainnya_semua_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/lainnya/semua") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[7, 'desc'], [1,'desc']],
       createdRow: function(row, data, dataIndex){
          if(data.status == 'Done; '){
            $(row).attr('style', 'background-color:#b8fbff');
          }else{
            $(row).attr('style', 'background-color:#edf7b0');
          }
       },
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_lainnya_semua_admin(from_date = '', to_date = '')
     {
      table = $('#complaint_lainnya_semua_admin_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/lainnya/semua_admin") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       createdRow: function(row, data, dataIndex){
          if(data.status == 'Done; '){
            $(row).attr('style', 'background-color:#b8fbff');
          }else{
            $(row).attr('style', 'background-color:#edf7b0');
          }
       },
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_lainnya_proses(from_date = '', to_date = '')
     {
      table = $('#complaint_lainnya_proses_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/lainnya/proses") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_lainnya_diproses(from_date = '', to_date = '')
     {
      table = $('#complaint_lainnya_diproses_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/lainnya/diproses") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_lainnya_valid(from_date = '', to_date = '')
     {
      table = $('#complaint_lainnya_valid_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/lainnya/valid") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_lainnya_selesai(from_date = '', to_date = '')
     {
      table = $('#complaint_lainnya_selesai_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/lainnya/selesai") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'divisi',
         name:'divisi'
        },
        {
         data:'status',
         name:'status'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_konfirmasi(from_date = '', to_date = '')
     {
      table = $('#konfirmasi_complaint_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ route("complaintkonf.index") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[1,'desc']],
       columns: [
        {
         data:'nomor_complaint',
         name:'nomor_complaint'
        },
        {
         data:'tanggal_complaint',
         name:'tanggal_complaint'
        },
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'nama_customer',
         name:'nama_customer'
        },
        {
         data:'nomor_surat_jalan',
         name:'nomor_surat_jalan',
         defaultContent: '<i>--</i>'
        },
        {
         data:'complaint',
         name:'complaint'
        },
        {
         data:'lampiran',
         name:'lampiran',
         defaultContent: '<i>--</i>',
         'render' : function(data, type, row, meta){
            if(data === null){
              data = '--';  
            }else{
              
              data = '<a target="_blank" href="' + '../data_file/' + data + '">Lihat Lampiran</a>';
            }
            return data;
         }
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if($('#tipe_complaint').val() == 3){
          if(tipe_user == 1){
            $('#complaint_sales_semua_admin_table').DataTable().destroy();
            load_data_sales_semua_admin(from_date, to_date);
          }else{
            $('#complaint_sales_semua_table').DataTable().destroy();
            load_data_sales_semua(from_date, to_date);
          }
          if(target == '#semua_sales'){
            $('#complaint_sales_semua_table').DataTable().destroy();
            load_data_sales_semua(from_date, to_date);
          }else if(target == '#semua_sales_admin'){
            $('#complaint_sales_semua_admin_table').DataTable().destroy();
            load_data_sales_semua_admin(from_date, to_date);
          }else if(target == '#proses_sales'){
            $('#complaint_sales_proses_table').DataTable().destroy();
            load_data_sales_proses(from_date, to_date);
          }else if(target == '#diproses_sales'){
            $('#complaint_sales_diproses_table').DataTable().destroy();
            load_data_sales_diproses(from_date, to_date);
          }else if(target == '#valid_sales'){
            $('#complaint_sales_valid_table').DataTable().destroy();
            load_data_sales_valid(from_date, to_date);
          }else if(target == '#selesai_sales'){
            $('#complaint_sales_selesai_table').DataTable().destroy();
            load_data_sales_selesai(from_date, to_date);
          }
        }else if($('#tipe_complaint').val() == 4){
          if(tipe_user == 1){
            $('#complaint_lainnya_semua_admin_table').DataTable().destroy();
            load_data_lainnya_semua_admin(from_date, to_date);
          }else{
            $('#complaint_lainnya_semua_table').DataTable().destroy();
            load_data_lainnya_semua(from_date, to_date);
          }
          if(target == '#semua_lainnya'){
            $('#complaint_lainnya_semua_table').DataTable().destroy();
            load_data_lainnya_semua(from_date, to_date);
          }else if(target == '#semua_lainnya_admin'){
            $('#complaint_lainnya_semua_admin_table').DataTable().destroy();
            load_data_lainnya_semua_admin(from_date, to_date);
          }else if(target == '#proses_lainnya'){
            $('#complaint_lainnya_proses_table').DataTable().destroy();
            load_data_lainnya_proses(from_date, to_date);
          }else if(target == '#diproses_lainnya'){
            $('#complaint_lainnya_diproses_table').DataTable().destroy();
            load_data_lainnya_diproses(from_date, to_date);
          }else if(target == '#valid_lainnya'){
            $('#complaint_lainnya_valid_table').DataTable().destroy();
            load_data_lainnya_valid(from_date, to_date);
          }else if(target == '#selesai_lainnya'){
            $('#complaint_lainnya_selesai_table').DataTable().destroy();
            load_data_lainnya_selesai(from_date, to_date);
          }
        }else if($('#tipe_complaint').val() == 4){
          $('#konfirmasi_complaint_table').DataTable().destroy();
          load_data_konfirmasi(from_date, to_date);
        }
     }
     else
     {
       alert('Both Date is required');
     }
   });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if($('#tipe_complaint').val() == 3){
        if(tipe_user == 1){
          $('#complaint_sales_semua_admin_table').DataTable().destroy();
          load_data_sales_semua_admin();
        }else{
          $('#complaint_sales_semua_table').DataTable().destroy();
          load_data_sales_semua();
        }
        if(target == '#semua_sales'){
          $('#complaint_sales_semua_table').DataTable().destroy();
          load_data_sales_semua();
        }else if(target == '#semua_sales_admin'){
          $('#complaint_sales_semua_admin_table').DataTable().destroy();
          load_data_sales_semua_admin();
        }else if(target == '#proses_sales'){
          $('#complaint_sales_proses_table').DataTable().destroy();
          load_data_sales_proses();
        }else if(target == '#diproses_sales'){
          $('#complaint_sales_diproses_table').DataTable().destroy();
          load_data_sales_diproses();
        }else if(target == '#valid_sales'){
          $('#complaint_sales_valid_table').DataTable().destroy();
          load_data_sales_valid();
        }else if(target == '#selesai_sales'){
          $('#complaint_sales_selesai_table').DataTable().destroy();
          load_data_sales_selesai();
        }
      }else if($('#tipe_complaint').val() == 4){
        if(tipe_user == 1){
            $('#complaint_lainnya_semua_admin_table').DataTable().destroy();
            load_data_lainnya_semua_admin();
          }else{
            $('#complaint_lainnya_semua_table').DataTable().destroy();
            load_data_lainnya_semua();
          }
        if(target == '#semua_lainnya'){
          $('#complaint_lainnya_semua_table').DataTable().destroy();
          load_data_lainnya_semua();
        }else if(target == '#semua_lainnya_admin'){
          $('#complaint_lainnya_semua_admin_table').DataTable().destroy();
          load_data_lainnya_semua_admin();
        }else if(target == '#proses_lainnya'){
          $('#complaint_lainnya_proses_table').DataTable().destroy();
          load_data_lainnya_proses();
        }else if(target == '#diproses_lainnya'){
          $('#complaint_lainnya_diproses_table').DataTable().destroy();
          load_data_lainnya_diproses();
        }else if(target == '#valid_lainnya'){
          $('#complaint_lainnya_valid_table').DataTable().destroy();
          load_data_lainnya_valid();
        }else if(target == '#selesai_lainnya'){
          $('#complaint_lainnya_selesai_table').DataTable().destroy();
          load_data_lainnya_selesai();
        }
      }else if($('#tipe_complaint').val() == 4){
        $('#konfirmasi_complaint_table').DataTable().destroy();
        load_data_konfirmasi();
      }
    });

    $('body').on('click', '#btn_list_komitmen', function (e) {
      $("ul.nav-tabs-list li a.nav-link").removeClass("active");
      $("ul.nav-tabs-list li:nth-of-type(1) a.nav-link").addClass("active").show();
      $(".tab-content-list .tab-pane").removeClass("active");
      $(".tab-content-list .tab-pane:nth-of-type(1)").addClass("active show in").show();
      var target_list = $('.nav-tabs-list a').attr("href");
      $('#list_komitmen_sales_table').DataTable().destroy();
      load_data_komitmen_sales();
      $('.nav-tabs-list a').off('shown.bs.tab').on('shown.bs.tab', function (e) {
        target_list = $(this).attr("href");
        if(target_list == '#lihat_list_komitmen_sales'){
          $('#list_komitmen_sales_table').DataTable().destroy();
          load_data_komitmen_sales();
        }else if(target_list == '#lihat_list_komitmen_lainnya'){
          $('#list_komitmen_lainnya_table').DataTable().destroy();
          load_data_komitmen_lainnya();
        }
      });
    });

  });
</script>

<script type="text/javascript">
  $(function () {
    $('#filter_tanggal').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $('#tanggal_pengiriman').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#tanggal_produksi').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal_pengiriman').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal_produksi').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#tanggal_order').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal_order').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#tanggal_customer').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_tanggal_customer').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#selesai_tanggal_komitmen_sls').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#selesai_tanggal_komitmen_lny').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_selesai_tanggal_komitmen_sls').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_selesai_tanggal_komitmen_lny').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function(event){
    let key = "{{ env('MIX_APP_KEY') }}";

    var count = 0;
    var td_count = 0;
    var td_count_prd = 0;
    var td_count_log = 0;
    var td_count_sls = 0;
    var td_count_timbang = 0;
    var td_count_ware = 0;
    var td_count_lny = 0;
    var td_count_lab = 0;
    var c_kuli = 0;
    var c_stapel = 0;
    var c_petugas = 0;
    var c_edit_kuli = 0;
    var c_edit_stapel = 0;
    var c_edit_petugas = 0;

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

    function load_data_komitmen(no_complaint = '')
      {
        table = $('#list_of_action_table').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
          url:'{{ url("validasi/list_komitmen") }}',
          data:{no_complaint:no_complaint},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        columns: [
         {
         data:'DT_RowIndex',
         name:'DT_RowIndex'
         },
         {
           data:'divisi',
           name:'divisi'
         },
         {
           data:'komitmen',
           name:'komitmen'
         },
         {
           data:'selesai_tanggal_komitmen',
           name:'selesai_tanggal_komitmen'
         }
        ]
        });
      }

      function load_komitmen_sls(no_complaint = '')
      {
        table = $('#list_komitmen_sls_table').DataTable({
         processing: true,
         serverSide: true,
         language: {
            emptyTable: "Tambahkan Data Komitmen"
         },
         ajax: {
          url:'{{ url("list_komitmen_temp/sales") }}',
          data:{no_complaint:no_complaint},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        columns: [
         {
           data:'selesai_tanggal_komitmen',
           name:'selesai_tanggal_komitmen'
         },
         {
           data:'komitmen',
           name:'komitmen'
         },
         {
         data:'action',
         name:'action',
         width: '5%'
         }
        ]
        });
      }

      function load_edit_komitmen_sls(no_complaint = '')
      {
        table = $('#edit_list_komitmen_sls_table').DataTable({
         processing: true,
         serverSide: true,
         language: {
            emptyTable: "Tambahkan Data Komitmen"
         },
         ajax: {
          url:'{{ url("edit_list_komitmen_sales") }}',
          data:{no_complaint:no_complaint},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        columns: [
         {
           data:'selesai_tanggal_komitmen',
           name:'selesai_tanggal_komitmen'
         },
         {
           data:'komitmen',
           name:'komitmen'
         },
         {
         data:'action',
         name:'action',
         width: '5%'
         }
        ]
        });
      }

      function load_komitmen_lny(no_complaint = '')
      {
        table = $('#list_komitmen_lny_table').DataTable({
         processing: true,
         serverSide: true,
         language: {
            emptyTable: "Tambahkan Data Komitmen"
         },
         ajax: {
          url:'{{ url("list_komitmen_temp/lainnya") }}',
          data:{no_complaint:no_complaint},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        columns: [
         {
           data:'selesai_tanggal_komitmen',
           name:'selesai_tanggal_komitmen'
         },
         {
           data:'komitmen',
           name:'komitmen'
         },
         {
         data:'action',
         name:'action',
         width: '5%'
         }
        ]
        });
      }

      function load_edit_komitmen_lny(no_complaint = '')
      {
        table = $('#edit_list_komitmen_lny_table').DataTable({
         processing: true,
         serverSide: true,
         language: {
            emptyTable: "Tambahkan Data Komitmen"
         },
         ajax: {
          url:'{{ url("edit_list_komitmen_lainnya") }}',
          data:{no_complaint:no_complaint},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        columns: [
         {
           data:'selesai_tanggal_komitmen',
           name:'selesai_tanggal_komitmen'
         },
         {
           data:'komitmen',
           name:'komitmen'
         },
         {
         data:'action',
         name:'action',
         width: '5%'
         }
        ]
        });
      }

      function load_logbook(nomor_complaint=''){
        table = $('#logbook_complaint_table').DataTable({
          processing: true,
          serverSide: true,
          lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
          ajax: {
            url:'{{ url("logbook/complaint_table") }}',
            data:{nomor_complaint:nomor_complaint},
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
          },
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          order: [[0,'desc']],
          searching: false,
          columns: [
          {
            data:'tanggal',
            name:'tanggal',
            width:'15%'
          },
          {
            data:'divisi',
            name:'divisi'
          },
          {
            data:'action',
            name:'action'
          }
          ]
        });
      }

      function load_data_no_lot(custid = '')
      {
        table = $('#list_no_lot_table').DataTable({
         processing: true,
         serverSide: true,
         language: {
            emptyTable: "Tambahkan Data Produksi"
          },
         ajax: {
          url:'{{ url("show_data_comp_produksi") }}',
          data:{custid:custid},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        columns: [
         {
         data:'DT_RowIndex',
         name:'DT_RowIndex',
         width:'5%'
         },
         {
           data:'no_lot',
           name:'no_lot'
         },
         {
           data:'tanggal_produksi',
           name:'tanggal_produksi'
         },
         {
           data:'nama_produk',
           name:'nama_produk'
         },
         {
           data:'area',
           name:'area'
         },
         {
           data:'mesin',
           name:'mesin'
         },
         {
           data:'bermasalah',
           name:'bermasalah'
         },
         {
           data:'action',
           name:'action',
           width:'5%'
         }
        ]
        });
      }

      function load_data_edit_no_lot(no_complaint = '')
      {
        table = $('#edit_list_no_lot_table').DataTable({
         processing: true,
         serverSide: true,
         language: {
            emptyTable: "Tambahkan Data Produksi"
          },
         ajax: {
          url:'{{ url("edit_show_data_comp_produksi") }}',
          data:{no_complaint:no_complaint},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        columns: [
         {
         data:'DT_RowIndex',
         name:'DT_RowIndex',
         width:'5%'
         },
         {
           data:'no_lot',
           name:'no_lot'
         },
         {
           data:'tanggal_produksi',
           name:'tanggal_produksi'
         },
         {
           data:'nama_produk',
           name:'nama_produk'
         },
         {
           data:'area',
           name:'area'
         },
         {
           data:'mesin',
           name:'mesin'
         },
         {
           data:'bermasalah',
           name:'bermasalah'
         },
         {
           data:'action',
           name:'action',
           width:'5%'
         }
        ]
        });
      }

      function save_data(){
        $('#input_complaint_form').validate({
          rules: {
            custid: {
              required: true,
            },
            complaint: {
              required: true,
            },
            nomor_surat_jalan: {
              required: true,
            },
            tanggal_order: {
              required: true,
            },
            sales_order: {
              required: true,
            },
            supervisor_sls: {
              required: true,
            },
            jumlah_karung: {
              required: true,
            },
            quantity: {
              required: true,
            },
            jumlah_kg_sak: {
              required: true,
            },
            jenis_karung: {
              required: true,
            },
            berat_timbangan: {
              required: true,
            },
            unit_berat_timbangan: {
              required: true,
            },
            berat_aktual: {
              required: true,
            },
            unit_berat_aktual: {
              required: true,
            },
            input_unit_berat_aktual: {
              required: true,
            },
            upload_file: {
              extension: "jpg,jpeg,pdf",
              filesize: 2,
            },
          },
          messages: {
            custid: {
              required: "Customer Harus Diisi",
            },
            complaint: {
              required: "Complaint Harus Diisi",
            },
            nomor_surat_jalan: {
              required: "Nomor Surat Jalan Harus Diisi",
            },
            tanggal_order: {
              required: "Tanggal Order Harus Diisi",
            },
            sales_order: {
              required: "Sales Yang Memesan Harus Diisi",
            },
            supervisor_sls: {
              required: "Supervisor Sales Harus Diisi",
            },
            jumlah_karung: {
              required: "Jumlah Karung Harus Diisi",
            },
            quantity: {
              required: "Quantity Harus Diisi",
            },
            jumlah_kg_sak: {
              required: "Jumlah KG / Sak Harus Diisi",
            },
            jenis_karung: {
              required: "Jenis Karung Harus Diisi",
            },
            berat_timbangan: {
              required: "Berat Timbangan Harus Diisi",
            },
            unit_berat_timbangan: {
              required: "Satuan Unit Harus Diisi",
            },
            berat_aktual: {
              required: "Berat Perhitungan Harus Diisi",
            },
            unit_berat_aktual: {
              required: "Satuan Unit Harus Diisi",
            },
            input_unit_berat_aktual: {
              required: "Satuan Unit Harus Diisi",
            },
            upload_file: {
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
            var myform = document.getElementById("input_complaint_form");
            var formdata = new FormData(myform);

            $.ajax({
              type:'POST',
              url:"{{ url('/complaintAdmin') }}",
              data: formdata,
              processData: false,
              contentType: false,
              success:function(data){
                $('#input_complaint_form').trigger("reset");
                $('[name="nama_kuli[]"]').val('');
                $('#row_kuli1').remove();
                $('#row_kuli2').remove();
                $('#row_kuli3').remove();
                c_kuli = 2;
                $('[name="nama_stapel[]"]').val('');
                $('#row_stapel1').remove();
                $('#row_stapel2').remove();
                $('#row_stapel3').remove();
                $('#row_stapel4').remove();
                $('#row_stapel5').remove();
                c_stapel = 2;
                var oTabled = $('#konfirmasi_complaint_table').dataTable();
                oTabled.fnDraw(false);
                $("#modal_input_complaint").modal('hide');
                $("#modal_input_complaint").trigger('click');
                alert("Data Successfully Stored");
              },
              error: function (data) {
                console.log('Error:', data);
                $('#input_complaint_form').trigger("reset");
                var oTabled = $('#konfirmasi_complaint_table').dataTable();
                oTabled.fnDraw(false);
                $("#modal_input_complaint").modal('hide');
                $("#modal_input_complaint").trigger('click');
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        });
      }

      function edit_data(){
        $('#editform').validate({
          rules: {
            edit_custid: {
              required: true,
            },
            edit_complaint: {
              required: true,
            },
            edit_nomor_surat_jalan: {
              required: true,
            },
            edit_tanggal_order: {
              required: true,
            },
            edit_sales_order: {
              required: true,
            },
            edit_supervisor_sls: {
              required: true,
            },
            edit_jumlah_karung: {
              required: true,
            },
            edit_quantity: {
              required: true,
            },
            edit_jumlah_kg_sak: {
              required: true,
            },
            edit_jenis_karung: {
              required: true,
            },
            edit_berat_timbangan: {
              required: true,
            },
            edit_unit_berat_timbangan: {
              required: true,
            },
            edit_berat_aktual: {
              required: true,
            },
            edit_unit_berat_aktual: {
              required: true,
            },
            edit_input_unit_berat_aktual: {
              required: true,
            },
            edit_upload_file: {
              extension: "jpg,jpeg,pdf",
              filesize: 2,
            },
          },
          messages: {
            edit_custid: {
              required: "Customer Harus Diisi",
            },
            edit_complaint: {
              required: "Complaint Harus Diisi",
            },
            edit_nomor_surat_jalan: {
              required: "Nomor Surat Jalan Harus Diisi",
            },
            edit_tanggal_order: {
              required: "Tanggal Order Harus Diisi",
            },
            edit_sales_order: {
              required: "Sales Yang Memesan Harus Diisi",
            },
            edit_supervisor_sls: {
              required: "Supervisor Sales Harus Diisi",
            },
            edit_jumlah_karung: {
              required: "Jumlah Karung Harus Diisi",
            },
            edit_quantity: {
              required: "Quantity Harus Diisi",
            },
            edit_jumlah_kg_sak: {
              required: "Jumlah KG / Sak Harus Diisi",
            },
            edit_jenis_karung: {
              required: "Jenis Karung Harus Diisi",
            },
            edit_berat_timbangan: {
              required: "Berat Timbangan Harus Diisi",
            },
            edit_unit_berat_timbangan: {
              required: "Satuan Unit Harus Diisi",
            },
            edit_berat_aktual: {
              required: "Berat Perhitungan Harus Diisi",
            },
            edit_unit_berat_aktual: {
              required: "Satuan Unit Harus Diisi",
            },
            edit_input_unit_berat_aktual: {
              required: "Satuan Unit Harus Diisi",
            },
            edit_upload_file: {
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
            var myform = document.getElementById("editform");
            var formdata = new FormData(myform);

            $.ajax({
              type:'POST',
              url:"{{ url('/complaint/edit') }}",
              data: formdata,
              processData: false,
              contentType: false,
              success:function(data){
                $('#editform').trigger("reset");
                $('[name="edit_nama_kuli[]"]').val('');
                $('#edit_row_kuli1').remove();
                $('#edit_row_kuli2').remove();
                $('#edit_row_kuli3').remove();
                c_edit_kuli = 2;
                $('[name="edit_nama_stapel[]"]').val('');
                $('#edit_row_stapel1').remove();
                $('#edit_row_stapel2').remove();
                $('#edit_row_stapel3').remove();
                $('#edit_row_stapel4').remove();
                $('#edit_row_stapel5').remove();
                c_edit_stapel = 2;
                var oTablea = $('#complaint_sales_semua_table').dataTable();
                oTablea.fnDraw(false);
                var oTableb = $('#complaint_sales_proses_table').dataTable();
                oTableb.fnDraw(false);
                var oTablec = $('#complaint_sales_semua_admin_table').dataTable();
                oTablec.fnDraw(false);
                var oTabled = $('#konfirmasi_complaint_table').dataTable();
                oTabled.fnDraw(false);
                $("#modal_edit_complaint").modal('hide');
                $("#modal_edit_complaint").trigger('click');
                alert("Data Successfully Updated");
              },
              error: function (data) {
                console.log('Error:', data);
                $('#editform').trigger("reset");
                var oTablea = $('#complaint_sales_semua_table').dataTable();
                oTablea.fnDraw(false);
                var oTableb = $('#complaint_sales_proses_table').dataTable();
                oTableb.fnDraw(false);
                var oTablec = $('#complaint_sales_semua_admin_table').dataTable();
                oTablec.fnDraw(false);
                var oTabled = $('#konfirmasi_complaint_table').dataTable();
                oTabled.fnDraw(false);
                $("#modal_edit_complaint").modal('hide');
                $("#modal_edit_complaint").trigger('click');
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        });
      }

    $('body').on('click', '#btn_input_manual', function () {
      var url = "{{ url('get_products') }}";
      $.get(url, function (data_products) {
        $('#produk').children().remove().end().append('<option value="" selected>Choose Products</option>');
        $.each(data_products, function(k, v) {
          $('#produk').append('<option value="' + v.kode_produk + '">' + v.nama_produk + '</option>');
        });
      })

      if($('#custid').val() == '' || $('#custid').val() == null){
        $('#list_no_lot_table').DataTable().destroy();
        load_data_no_lot();
      }else{
        var custid = $('#custid').val();
        $('#list_no_lot_table').DataTable().destroy();
        load_data_no_lot(custid);
      }

      $("#pengiriman").change(function() {
        if ($(this).val() == 3) {
          $('#pengiriman_lain').show();
        }else{
          $('#pengiriman_lain').hide();
        }
      });

      $("#jenis_kendaraan").change(function() {
        if ($(this).val() == 7) {
          $('#jenis_kendaraan_lain').show();
        }else{
          $('#jenis_kendaraan_lain').hide();
        }
      });

      $("#jumlah_kg_sak").change(function() {
        if($(this).val() == 800){
          $("#jenis_karung").val('3').trigger('change');
        }
        if($("#jumlah_karung").val() != null || $("#jumlah_karung").val() != ''){
          if($("#unit_berat_timbangan").val() == 2){
            var a = $("#jumlah_karung").val();
            var b = $("#jumlah_kg_sak").val();
            var total = a * b;
            $("#berat_aktual").val(total / 1000);
          }else{
            var a = $("#jumlah_karung").val();
            var b = $("#jumlah_kg_sak").val();
            var total = a * b;
            $("#berat_aktual").val(total);
          }
        }
      });

      $('#jumlah_karung').on('keyup', function(){
        if($("#jumlah_kg_sak").val() != null || $("#jumlah_kg_sak").val() != ''){
          if($("#unit_berat_timbangan").val() == 2){
            var a = $("#jumlah_karung").val();
            var b = $("#jumlah_kg_sak").val();
            var total = a * b;
            $("#berat_aktual").val(total / 1000);
          }else{
            var a = $("#jumlah_karung").val();
            var b = $("#jumlah_kg_sak").val();
            var total = a * b;
            $("#berat_aktual").val(total);
          }
        }
      });

      $("#unit_berat_timbangan").change(function() {
        var val = $(this).val();
        var amount = $("#berat_aktual").val();
        var a = $("#jumlah_karung").val();
        var b = $("#jumlah_kg_sak").val();
        var total = a * b;
        if(val == 2){
          amount = amount / 1000;
          $("#berat_aktual").val(amount);
        }else{
          if(amount == total / 1000){
            amount = amount * 1000;
            $("#berat_aktual").val(amount);
          }
        }
        $("#unit_berat_aktual").val(val).trigger('change');
        $("#input_unit_berat_aktual").val(val);
      });

      c_kuli=2;  
      $('#add_kuli').click(function(){  
        if(c_kuli < 4){
          $('#dynamic_field_kuli').append('<tr id="row_kuli'+c_kuli+'"><td><input type="text" name="nama_kuli[]" placeholder="Nama Kuli" class="form-control nama_kuli_list" /></td><td><button type="button" name="kuli_remove" id="'+c_kuli+'" class="btn btn-danger btn_remove_kuli">X</button></td></tr>');  
          c_kuli++;
        }
      });

      $(document).on('click', '.btn_remove_kuli', function(){  
        var button_id = $(this).attr("id");   
        $('#row_kuli'+button_id+'').remove();
        c_kuli--;
      });

      c_stapel=2;  
      $('#add_stapel').click(function(){  
        if(c_stapel < 6){
          $('#dynamic_field_stapel').append('<tr id="row_stapel'+c_stapel+'"><td><input type="text" name="nama_stapel[]" placeholder="Nama Stapel" class="form-control nama_stapel_list" /></td><td><button type="button" name="stapel_remove" id="'+c_stapel+'" class="btn btn-danger btn_remove_stapel">X</button></td></tr>');  
          c_stapel++;  
        }
      });

      $(document).on('click', '.btn_remove_stapel', function(){  
        var button_id = $(this).attr("id");   
        $('#row_stapel'+button_id+'').remove();  
        c_stapel--;
      });

      c_petugas=2;  
      $('#add_petugas').click(function(){    
        if(c_petugas < 6){
          $('#dynamic_field_petugas').append('<tr id="row_petugas'+c_petugas+'"><td><input type="text" name="petugas[]" placeholder="Petugas" class="form-control petugas_list" /></td><td><button type="button" name="petugas_remove" id="'+c_petugas+'" class="btn btn-danger btn_remove_petugas">X</button></td></tr>');  
          c_petugas++;
        }
      });

      $(document).on('click', '.btn_remove_petugas', function(){  
        var button_id = $(this).attr("id");   
        $('#row_petugas'+button_id+'').remove();  
        c_petugas--;
      });
    });

    $('#custid').on("select2:selecting", function(e) {
      var data = e.params.args.data;
      $('#list_no_lot_table').DataTable().destroy();
      load_data_no_lot(data.id);
    });

    $('body').on('click', '#save_input_complaint', function () {
      save_data();
    });

    $('body').on('click', '#save_edit_complaint', function () {
      edit_data();
    });

    $('body').on('click', '#save_input_produksi', function () {
      if($('#custid').val() == '' || $('#custid').val() == null){
        alert('Isi Customers Terlebih Dahulu');
      }else{
        var tanggal_produksi = $('#tanggal_produksi').val();
        var no_lot = $('#no_lot').val();
        var mesin = $('#mesin').val();
        var supervisor = $('#supervisor_prd').val();
        var petugas = $('#dynamic_field_petugas :input').serializeArray();
        var area = $('#area_prd').val();
        var kode_produk = $('#produk').val();
        var bermasalah = $("input[name='input_bermasalah']:checked").val();
        var custid = $('#custid').val();

        $.ajax({
          type: "GET",
          url: "{{ url('save_data_comp_produksi') }}",
          data: { 'tanggal_produksi' : tanggal_produksi, 'no_lot' : no_lot, 'mesin' : mesin, 'supervisor' : supervisor, 'petugas' : petugas, 'area' : area, 'bermasalah' : bermasalah, 'custid' : custid, 'kode_produk' : kode_produk },
          success: function (data) {
            $('#tanggal_produksi').val('');
            $('#no_lot').val('');
            $('#mesin').val('');
            $('#supervisor_prd').val('');
            $('[name="petugas[]"]').val('');
            $('#row_petugas1').remove();
            $('#row_petugas2').remove();
            $('#row_petugas3').remove();
            $('#row_petugas4').remove();
            $('#row_petugas5').remove();
            c_petugas = 2;
            $('#area_prd').val('').trigger('change');
            $("#bermasalah").prop("checked", true);
            var url = "{{ url('get_products') }}";
            $.get(url, function (data_products) {
              $('#produk').children().remove().end().append('<option value="" selected>Choose Products</option>');
              $.each(data_products, function(k, v) {
                $('#produk').append('<option value="' + v.kode_produk + '">' + v.nama_produk + '</option>');
              });
            })
            var oTable = $('#list_no_lot_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {  
            var oTable = $('#list_no_lot_table').dataTable();
            oTable.fnDraw(false);
            console.log('Error:', data);
            alert("Isi Semua Data Produksi, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
          }
        });
      }
    });

    $('body').on('click', '#delete-data-produksi', function () {
      var custid = $(this).data("id");
      var no_lot = $(this).data("nolot");
      if(confirm("Delete this data?")){
        $.ajax({
          type: "GET",
          url: "{{ url('delete_data_comp_produksi') }}",
          data: { 'custid' : custid, 'no_lot' : no_lot },
          success: function (data) {
            var oTable = $('#list_no_lot_table').dataTable(); 
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#proses_complaint_sales', function () {
      var complaint = $(this).data('id');
      var custid_prd = $(this).data('custid');
      var divisi = $(this).data('divisi');
      $('#nomor_complaint_sls').val(complaint);
      $('#custid_sls').val(custid_prd);
      $('#no_divisi_sls').val(divisi);

      $('#list_komitmen_sls_table').DataTable().destroy();
      load_komitmen_sls(complaint);
      var url = "{{ url('sales_proses/show/no_complaint') }}";
      url = url.replace('no_complaint', enc(complaint.toString()));
      $('#tanggal_pengiriman_proc_sls').val('');
      $('#area_log_proc_sls').val('').trigger('change');
      $('#no_kendaraan_proc_sls').val('');
      $('#nama_supir_proc_sls').val('');
      $('#nama_kernet_proc_sls').val('');
      $('#pengiriman_proc_sls').val('').trigger('change');
      $('#jenis_kendaraan_proc_sls').val('').trigger('change');
      $('#supervisor_log_proc_sls').val('');
      $('#pengiriman_lain_proc_sls').val('');
      $('#jenis_kendaraan_lain_proc_sls').val('');
      $('#jumlah_karung_proc_sls').val('');
      $('#quantity_proc_sls').val('');
      $('#jumlah_kg_sak_proc_sls').val('').trigger('change');
      $('#jenis_karung_proc_sls').val('').trigger('change');
      $('#berat_timbangan_proc_sls').val('');
      $('#unit_berat_timbangan_proc_sls').val('').trigger('change');
      $('#berat_aktual_proc_sls').val('');
      $('#unit_berat_aktual_proc_sls').val('').trigger('change');
      $('#pengiriman_lain_proc_sls').hide();
      $('#jenis_kendaraan_lain_proc_sls').hide();
      $('[name="nama_kuli_proc_sls[]"]').val('');
      $('#row_kuli_proc_sls1').remove();
      $('#row_kuli_proc_sls2').remove();
      $('#row_kuli_proc_sls3').remove();
      $('[name="nama_stapel_proc_sls[]"]').val('');
      $('#row_stapel_proc_sls1').remove();
      $('#row_stapel_proc_sls2').remove();
      $('#row_stapel_proc_sls3').remove();
      $('#row_stapel_proc_sls4').remove();
      $('#row_stapel_proc_sls5').remove();
      $.get(url, function (data) {
        count = data.data_produksi.length;
        for(var i = 1; i <= count; i++){
          $('#show_data_sales').append('<div id="show_data_sales_' + i + '"></div>');
          $('#show_data_sales_' + i).html(
            '<div class="row">'+
            '<div class="col-12">'+
              '<div class="card">'+
                '<div class="card-header">'+
                  '<h5>Data Produksi ' + i + '</h5>'+
                '</div>'+
                '<div class="card-body">'+
                  '<div class="row">'+
                    '<div class="col-4">'+
                      '<div class="form-group">'+
                        '<label for="no_lot_proc_sls' + i + '">No Lot</label>'+
                        '<input type="text" class="form-control" name="no_lot_proc_sls' + i + '" id="no_lot_proc_sls' + i + '" placeholder="No Lot" readonly>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-4">'+
                      '<div class="form-group">'+
                        '<label>Tanggal Produksi</label>'+
                        '<div class="input-group">'+
                          '<div class="input-group-prepend">'+
                            '<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>'+
                          '</div>'+
                          '<input type="text" class="form-control" name="tanggal_produksi_proc_sls' + i + '" id="tanggal_produksi_proc_sls' + i + '" autocomplete="off" placeholder="Tanggal Produksi" readonly>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-4">'+
                      '<div class="form-group">'+
                        '<label for="select_produk_prd_proc_sls' + i + '">Produk</label>'+
                        '<select id="select_produk_prd_proc_sls' + i + '" name="select_produk_prd_proc_sls' + i + '" class="form-control" disabled>'+
                        '</select>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                  '<div class="row">'+
                    '<div class="col-3">'+
                      '<div class="form-group">'+
                        '<label for="mesin_proc_sls' + i + '">Mesin</label>'+
                        '<input type="text" class="form-control" id="mesin_proc_sls' + i + '" name="mesin_proc_sls' + i + '" placeholder="Mesin" readonly>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-3">'+
                        '<div class="form-group">'+
                          '<label for="petugas_proc_sls">Petugas</label>'+
                          '<table class="table" id="dynamic_field_petugas_proc_sls" style="border: none;">'+
                            '<tr>'+
                              '<td><input type="text" name="petugas_proc_sls[]" placeholder="Petugas" class="form-control petugas_proc_sls_list" readonly/></td>'+
                            '</tr>'+
                          '</table>'+
                        '</div>'+
                      '</div>'+
                    '<div class="col-3">'+
                      '<div class="form-group">'+
                        '<label for="supervisor_prd_proc_sls' + i + '">Supervisor</label>'+
                        '<input class="form-control" type="text" name="supervisor_prd_proc_sls' + i + '" id="supervisor_prd_proc_sls' + i + '" placeholder="Supervisor" readonly/>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-3">'+
                      '<div class="form-group">'+
                        '<label for="area_prd_proc_sls' + i + '">Area</label>'+
                        '<select id="area_prd_proc_sls' + i + '" name="area_prd_proc_sls' + i + '" class="form-control" disabled>'+
                          '<option value="" selected>Area</option>'+
                          '<option value="1">Area 1</option>'+
                          '<option value="2">Area 2</option>'+
                        '</select>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
              '</div>'+
            '</div>'+
          '</div>'
            );
          if(data.data_produksi[i-1].tanggal_produksi == '' || data.data_produksi[i-1].tanggal_produksi == null){
            $('#tanggal_produksi_proc_sls' + i).val('');
          }else{
            $('#tanggal_produksi_proc_sls' + i).val(data.data_produksi[i-1].tanggal_produksi);
          }
          $('#no_lot_proc_sls' + i).val(data.data_produksi[i-1].no_lot);
          $('#mesin_proc_sls' + i).val(data.data_produksi[i-1].mesin);
          $('#supervisor_prd_proc_sls' + i).val(data.data_produksi[i-1].supervisor);
          $('#petugas_proc_sls' + i).val(data.data_produksi[i-1].petugas);
          if(data.data_produksi[i-1].area == '' || data.data_produksi[i-1].area == null){
            $('#area_prd_proc_sls' + i).val('');
          }else{
            $('#area_prd_proc_sls' + i).val(data.data_produksi[i-1].area);
          }
          if(data.data_produksi[i-1].kode_produk == '' || data.data_produksi[i-1].kode_produk == null){
            $('#select_produk_prd_proc_sls' + i).append('<option value="" selected>Choose Products</option>');
          }else{
            $('#select_produk_prd_proc_sls' + i).append('<option value="' + data.data_produksi[i-1].kode_produk + '">' + data.data_produksi[i-1].nama_produk + '</option>');
          }
          if(data.data_produksi[i-1].petugas1 != null){
            $('[name="petugas_proc_sls[]"]').val(data.data_produksi[i-1].petugas1);
            // $('#edit_petugas[]').val(data.petugas1);
          }
          if(data.data_produksi[i-1].petugas2 != null){
            $('#dynamic_field_petugas_proc_sls').append('<tr id="row_petugas_proc_sls2"><td><input type="text" name="petugas_proc_sls[]" placeholder="Petugas" class="form-control petugas_proc_sls_list" value="'+data.data_produksi[i-1].petugas2+'" readonly/></td></tr>');
          }
          if(data.data_produksi[i-1].petugas3 != null){
            $('#dynamic_field_petugas_proc_sls').append('<tr id="row_petugas_proc_sls3"><td><input type="text" name="petugas_proc_sls[]" placeholder="Petugas" class="form-control petugas_proc_sls_list" value="'+data.data_produksi[i-1].petugas3+'" readonly/></td></tr>');
          }
          if(data.data_produksi[i-1].petugas4 != null){
            $('#dynamic_field_petugas_proc_sls').append('<tr id="row_petugas_proc_sls4"><td><input type="text" name="petugas_proc_sls[]" placeholder="Petugas" class="form-control petugas_proc_sls_list" value="'+data.data_produksi[i-1].petugas4+'" readonly/></td></tr>');
          }
          if(data.data_produksi[i-1].petugas5 != null){
            $('#dynamic_field_petugas_proc_sls').append('<tr id="row_petugas_proc_sls5"><td><input type="text" name="petugas_proc_sls[]" placeholder="Petugas" class="form-control petugas_proc_sls_list" value="'+data.data_produksi[i-1].petugas5+'" readonly/></td></tr>');
          }
        }
        if(data.data_hitung.tanggal_order == '' || data.data_hitung.tanggal_order == null){
          $('#tanggal_order_proc_sls').val('');
        }else{
          $('#tanggal_order_proc_sls').val(data.data_hitung.tanggal_order);
        }
        if(data.data_hitung.tanggal_pengiriman == '' || data.data_hitung.tanggal_pengiriman == null){
          $('#tanggal_pengiriman_proc_sls').val('');
        }else{
          $('#tanggal_pengiriman_proc_sls').val(data.data_hitung.tanggal_pengiriman);
        }
        if(data.data_hitung.area == '' || data.data_hitung.area == null){
          $('#area_log_proc_sls').val('').trigger('change');
        }else{
          $('#area_log_proc_sls').val(data.data_hitung.area).trigger('change');
        }
        $('#no_kendaraan_proc_sls').val(data.data_hitung.no_kendaraan);
        $('#nama_supir_proc_sls').val(data.data_hitung.nama_supir);
        $('#nama_kernet_proc_sls').val(data.data_hitung.nama_kernet);
        if(data.data_hitung.pengiriman == '' || data.data_hitung.pengiriman == null){
          $('#pengiriman_proc_sls').val('').trigger('change');
        }else{
          $('#pengiriman_proc_sls').val(data.data_hitung.pengiriman).trigger('change');
        }
        if(data.data_hitung.jenis_kendaraan == '' || data.data_hitung.jenis_kendaraan == null){
          $('#jenis_kendaraan_proc_sls').val('').trigger('change');
        }else{
          $('#jenis_kendaraan_proc_sls').val(data.data_hitung.jenis_kendaraan).trigger('change');
        }
        $('#supervisor_log_proc_sls').val(data.data_hitung.supervisor);
        if(data.data_hitung.pengiriman_lain == '' || data.data_hitung.pengiriman_lain == null){
          $('#pengiriman_lain_proc_sls').hide();
        }else{
          $('#pengiriman_lain_proc_sls').show();
          $('#pengiriman_lain_proc_sls').val(data.data_hitung.pengiriman_lain);
        }
        if(data.data_hitung.jenis_kendaraan_lain == '' || data.data_hitung.jenis_kendaraan_lain == null){
          $('#jenis_kendaraan_lain_proc_sls').hide();
        }else{
          $('#jenis_kendaraan_lain_proc_sls').show();
          $('#jenis_kendaraan_lain_proc_sls').val(data.data_hitung.jenis_kendaraan_lain);
        }
        $('#jumlah_karung_proc_sls').val(data.data_hitung.jumlah_karung);
        $('#quantity_proc_sls').val(data.data_hitung.quantity);
        $('#sales_order_proc_sls').val(data.data_hitung.sales_order);
        $('#supervisor_sls_proc_sls').val(data.data_hitung.supervisor_sales);
        $('#pelapor_proc_sls').val(data.data_hitung.pelapor);
        if(data.data_hitung.jumlah_kg_sak == '' || data.data_hitung.jumlah_kg_sak == null){
          $('#jumlah_kg_sak_proc_sls').val('').trigger('change');
        }else{
          $('#jumlah_kg_sak_proc_sls').val(data.data_hitung.jumlah_kg_sak).trigger('change');
        }
        if(data.data_hitung.jenis_karung == '' || data.data_hitung.jenis_karung == null){
          $('#jenis_karung_proc_sls').val('').trigger('change');
        }else{
          $('#jenis_karung_proc_sls').val(data.data_hitung.jenis_karung).trigger('change');
        }
        $('#berat_timbangan_proc_sls').val(data.data_hitung.berat_timbangan);
        if(data.data_hitung.unit_berat_timbangan == '' || data.data_hitung.unit_berat_timbangan == null){
          $('#unit_berat_timbangan_proc_sls').val('').trigger('change');
        }else{
          $('#unit_berat_timbangan_proc_sls').val(data.data_hitung.unit_berat_timbangan).trigger('change');
        }
        $('#berat_aktual_proc_sls').val(data.data_hitung.berat_aktual);
        if(data.data_hitung.unit_berat_aktual == '' || data.data_hitung.unit_berat_aktual == null){
          $('#unit_berat_aktual_proc_sls').val('').trigger('change');
        }else{
          $('#unit_berat_aktual_proc_sls').val(data.data_hitung.unit_berat_aktual).trigger('change');
        }
        if(data.data_hitung.kuli1 != null){
          $('[name="nama_kuli_proc_sls[]"]').val(data.data_hitung.kuli1);
          // $('#edit_nama_kuli[]').val(data.kuli1);
        }
        if(data.data_hitung.kuli2 != null){
          $('#dynamic_field_kuli_proc_sls').append('<tr id="row_kuli_proc_sls2"><td><input type="text" name="nama_kuli_proc_sls[]" placeholder="Nama Kuli" class="form-control nama_kuli_proc_sls_list" value="'+data.data_hitung.kuli2+'" readonly/></td></tr>');
        }
        if(data.data_hitung.kuli3 != null){
          $('#dynamic_field_kuli_proc_sls').append('<tr id="row_kuli_proc_sls3"><td><input type="text" name="nama_kuli_proc_sls[]" placeholder="Nama Kuli" class="form-control nama_kuli_proc_sls_list" value="'+data.data_hitung.kuli3+'" readonly/></td></tr>');
        }
        if(data.data_hitung.stapel1 != null){
          $('[name="nama_stapel_proc_sls[]"]').val(data.data_hitung.stapel1);
          // $('#edit_nama_stapel[]').val(data.stapel1);
        }
        if(data.data_hitung.stapel2 != null){
          $('#dynamic_field_stapel_proc_sls').append('<tr id="row_stapel_proc_sls2"><td><input type="text" name="nama_stapel_proc_sls[]" placeholder="Nama Stapel" class="form-control nama_stapel_proc_sls_list" value="'+data.data_hitung.stapel2+'" readonly/></td></tr>');
        }
        if(data.data_hitung.stapel3 != null){
          $('#dynamic_field_stapel_proc_sls').append('<tr id="row_stapel_proc_sls3"><td><input type="text" name="nama_stapel_proc_sls[]" placeholder="Nama Stapel" class="form-control nama_stapel_proc_sls_list" value="'+data.data_hitung.stapel3+'" readonly/></td></tr>');
        }
        if(data.data_hitung.stapel4 != null){
          $('#dynamic_field_stapel_proc_sls').append('<tr id="row_stapel_proc_sls4"><td><input type="text" name="nama_stapel_proc_sls[]" placeholder="Nama Stapel" class="form-control nama_stapel_proc_sls_list" value="'+data.data_hitung.stapel4+'" readonly/></td></tr>');
        }
        if(data.data_hitung.stapel5 != null){
          $('#dynamic_field_stapel_proc_sls').append('<tr id="row_stapel_proc_sls5"><td><input type="text" name="nama_stapel_proc_sls[]" placeholder="Nama Stapel" class="form-control nama_stapel_proc_sls_list" value="'+data.data_hitung.stapel5+'" readonly/></td></tr>');
        }
      })
    });

    $('body').on('click', '#save_input_komitmen_sls', function () {
      var selesai_tanggal_komitmen = $('#selesai_tanggal_komitmen_sls').val();
      var komitmen = $('#komitmen_sls').val();
      var nomor_complaint = $('#nomor_complaint_sls').val();

      $.ajax({
        type: "GET",
        url: "{{ url('save_data_komitmen_sls') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint },
        success: function (data) {
          $('#selesai_tanggal_komitmen_sls').val('');
          $('#komitmen_sls').val('');
          var oTable = $('#list_komitmen_sls_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {  
          var oTable = $('#list_komitmen_sls_table').dataTable();
          oTable.fnDraw(false);
          console.log('Error:', data);
          alert("Isi Tanggal dan Komitmen, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
        }
      });
    });

    $('body').on('click', '#delete-komitmen', function () {
      var nomor_complaint = $(this).data("id");
      var divisi = $(this).data("divisi");
      var selesai_tanggal_komitmen = $(this).data("tanggal");
      var komitmen = $(this).data("komitmen");

      if(confirm("Delete this data?")){
        $.ajax({
          type: "GET",
          url: "{{ url('delete_data_komitmen') }}",
          data: { 'nomor_complaint' : nomor_complaint, 'divisi' : divisi, 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen },
          success: function (data) {
            var oTable = $('#list_komitmen_sls_table').dataTable(); 
            oTable.fnDraw(false);
            var oTableb = $('#list_komitmen_lny_table').dataTable(); 
            oTableb.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn_tidak_terlibat_sls', function () {
      var complaint = $('#nomor_complaint_sls').val();
      var custid = $('#custid_sls').val();
      var divisi = $('#no_divisi_sls').val();

      if(confirm("Apakah Anda Yakin?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint_sales/tidak_terlibat/') }}",
          data: { 'nomor_complaint' : complaint, 'divisi' : divisi },
          success: function (data) {
            var oTablea = $('#complaint_sales_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_sales_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_sales_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_sales_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_sales_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_sales_diproses_table').dataTable();
            oTablef.fnDraw(false);
            $("#modal_proses_complaint_sales").modal('hide');
            $("#modal_proses_complaint_sales").trigger('click');
            alert("Updated Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn_terlibat_sls', function () {
      var complaint = $('#nomor_complaint_sls').val();
      var custid = $('#custid_sls').val();
      var divisi = $('#no_divisi_sls').val();

      if(confirm("Apakah Anda Yakin?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint_sales/terlibat/') }}",
          data: { 'nomor_complaint' : complaint, 'divisi' : divisi },
          success: function (data) {
            var oTablea = $('#complaint_sales_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_sales_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_sales_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_sales_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_sales_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_sales_diproses_table').dataTable();
            oTablef.fnDraw(false);
            $("#modal_proses_complaint_sales").modal('hide');
            $("#modal_proses_complaint_sales").trigger('click');
            alert("Updated Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn_tidak_terlibat_lny', function () {
      var complaint = $('#nomor_complaint_lny').val();
      $('#nomor_complaint_alasan').val(complaint);
    });

    $('body').on('click', '#btn_terlibat_lny', function () {
      var complaint = $('#nomor_complaint_lny').val();
      var custid = $('#custid_lny').val();
      var divisi = $('#no_divisi_lny').val();

      if(confirm("Apakah Anda Yakin?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint_lainnya/terlibat/') }}",
          data: { 'nomor_complaint' : complaint, 'divisi' : divisi },
          success: function (data) {
            var oTablea = $('#complaint_lainnya_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_lainnya_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_lainnya_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_lainnya_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_lainnya_diproses_table').dataTable();
            oTablef.fnDraw(false);
            $("#modal_proses_complaint_lainnya").modal('hide');
            $("#modal_proses_complaint_lainnya").trigger('click');
            alert("Updated Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#proses_complaint_lainnya', function () {
      var complaint = $(this).data('id');
      var custid_prd = $(this).data('custid');
      var divisi = $(this).data('divisi');
      $('#nomor_complaint_lny').val(complaint);
      $('#custid_lny').val(custid_prd);
      $('#no_divisi_lny').val(divisi);

      $('#list_komitmen_lny_table').DataTable().destroy();
      load_komitmen_lny(complaint);

      if(divisi == 1){
        $('#lainnyaform input[type="text"]'). prop("disabled", true);
        $('#lainnyaform input[type="file"]'). prop("disabled", true);
        $('#lainnyaform textarea'). prop("disabled", true);
        $('#btn_save_lny').hide();
        $('#btn_tidak_terlibat_lny').hide();
        $('#btn_terlibat_lny').show();
      }else{
        $('#salesform input[type="text"]'). prop("disabled", false);
        $('#salesform input[type="file"]'). prop("disabled", false);
        $('#salesform textarea'). prop("disabled", false);
        $('#btn_save_sls').show();
        $('#btn_tidak_terlibat_sls').show();
        $('#btn_terlibat_sls').hide();
        $('#lainnyaform input[type="text"]'). prop("disabled", false);
        $('#lainnyaform input[type="file"]'). prop("disabled", false);
        $('#lainnyaform textarea'). prop("disabled", false);
        $('#btn_save_lny').show();
        $('#btn_tidak_terlibat_lny').show();
        $('#btn_terlibat_lny').hide();
      }
    });

    $('body').on('click', '#save_input_komitmen_lny', function () {
      var selesai_tanggal_komitmen = $('#selesai_tanggal_komitmen_lny').val();
      var komitmen = $('#komitmen_lny').val();
      var nomor_complaint = $('#nomor_complaint_lny').val();

      $.ajax({
        type: "GET",
        url: "{{ url('save_data_komitmen_lny') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint },
        success: function (data) {
          $('#selesai_tanggal_komitmen_lny').val('');
          $('#komitmen_lny').val('');
          var oTable = $('#list_komitmen_lny_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {  
          var oTable = $('#list_komitmen_lny_table').dataTable();
          oTable.fnDraw(false);
          console.log('Error:', data);
          alert("Isi Tanggal dan Komitmen, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
        }
      });
    });

    $('body').on('click', '#konfirmasi_divisi_complaint', function () {
      var complaint = $(this).data('id');
      $('#nomor_complaint_konf').val(complaint);
    });

    $('#modal_lihat_complaint_produksi').on('hidden.bs.modal', function () {
      for(var i = 1; i <= td_count; i++){
        $('#td_lihat_data_produksi_' + i).remove();
      }
      for(var i = 1; i <= td_count_prd; i++){
        $('#td_lihat_komitmen_produksi_' + i).remove();
      }
      for(var i = 1; i <= td_count_log; i++){
        $('#td_lihat_komitmen_logistik_' + i).remove();
      }
      for(var i = 1; i <= td_count_sls; i++){
        $('#td_lihat_komitmen_sales_' + i).remove();
      }
      for(var i = 1; i <= td_count_timbang; i++){
        $('#td_lihat_komitmen_timbangan_' + i).remove();
      }
      for(var i = 1; i <= td_count_ware; i++){
        $('#td_lihat_komitmen_warehouse_' + i).remove();
      }
      for(var i = 1; i <= td_count_lny; i++){
        $('#td_lihat_komitmen_lainnya_' + i).remove();
      }
      for(var i = 1; i <= td_count_lab; i++){
        $('#td_lihat_lab_' + i).remove();
      }
    });

    $('body').on('click', '#lihat_complaint_produksi', function (e) {
      $("ul.nav-tabs-lihat li a.nav-link").removeClass("active");
      $("ul.nav-tabs-lihat li:nth-of-type(1) a.nav-link").addClass("active").show();
      $(".tab-content-lihat .tab-pane").removeClass("active");
      $(".tab-content-lihat .tab-pane:nth-of-type(1)").addClass("active show in").show();
      var target_lihat = $('.nav-tabs-lihat a').attr("href");
      var url = "{{ url('data_complaint_show/no_complaint') }}";
      var nomor_complaint = "";
      var nomor_complaint = $(this).data('id');
      url = url.replace('no_complaint', enc(nomor_complaint.toString()));
      $('#td_nomor_complaint_com').html('');
      $('#td_nomor_surat_jalan_com').html('');
      $('#td_tanggal_complaint_com').html('');
      $('#td_customer_com').html('');
      $('#td_tanggal_order_com').html('');
      $('#td_complaint_com').html('');
      $('#td_lampiran_com').html('');
      $('#td_sales_order_com').html('');
      $('#td_supervisor_sls_com').html('');
      $('#td_pelapor_com').html('');
      $('#td_jumlah_karung_com').html('');
      $('#td_quantity_com').html('');
      $('#td_jenis_karung_com').html('');
      $('#td_jumlah_kg_sak_com').html('');
      $('#td_berat_timbangan_com').html('');
      $('#td_unit_berat_timbangan_com').html('');
      $('#td_berat_aktual_com').html('');
      $('#td_unit_berat_aktual_com').html('');
      $('#td_tanggal_pengiriman_com').html('');
      $('#td_area_com').html('');
      $('#td_supervisor_com').html('');
      $('#td_pengiriman_com').html('');
      $('#td_pengiriman_lain_com').html('');
      $('#td_nama_supir_com').html('');
      $('#td_nama_kernet_com').html('');
      $('#td_nama_kuli_com').html('');
      $('#td_nama_stapel_com').html('');
      $('#td_no_kendaraan_com').html('');
      $('#td_jenis_kendaraan_com').html('');
      $('#td_jenis_kendaraan_lain_com').html('');
      $('#td_nomor_complaint_prd').html('');
      $('#td_evaluasi_prd').html('');
      $('#td_solusi_internal_prd').html('');
      $('#td_solusi_customer_prd').html('');
      $('#td_lampiran_prd').html('');
      $('#td_nomor_complaint_log').html('');
      $('#td_evaluasi_log').html('');
      $('#td_solusi_internal_log').html('');
      $('#td_solusi_customer_log').html('');
      $('#td_lampiran_log').html('');
      $('#td_nomor_complaint_sls').html('');
      $('#td_tanggal_customer_sls').html('');
      $('#td_evaluasi_sls').html('');
      $('#td_solusi_internal_sls').html('');
      $('#td_solusi_customer_sls').html('');
      $('#td_lampiran_sls').html('');
      $('#td_nomor_complaint_lab').html('');
      $('#td_suggestion_lab').html('');
      $('#td_lampiran_lab').html('');
      $('#td_nomor_complaint_timbang').html('');
      $('#td_evaluasi_timbang').html('');
      $('#td_solusi_internal_timbang').html('');
      $('#td_solusi_customer_timbang').html('');
      $('#td_lampiran_timbang').html('');
      $('#td_nomor_complaint_ware').html('');
      $('#td_evaluasi_ware').html('');
      $('#td_solusi_internal_ware').html('');
      $('#td_solusi_customer_ware').html('');
      $('#td_lampiran_ware').html('');
      $('#td_nomor_complaint_lny').html('');
      $('#td_divisi_lny').html('');
      $('#td_evaluasi_lny').html('');
      $('#td_solusi_internal_lny').html('');
      $('#td_solusi_customer_lny').html('');
      $('#td_lampiran_lny').html('');
      $.get(url, function (data) {
        td_count = data.data_produksi.length;
        for(var i = 1; i <= td_count; i++){
          $('#td_lihat_data_produksi').append('<table id="td_lihat_data_produksi_' + i + '" class="table table-bordered table-hover"></table>');
          $('#td_lihat_data_produksi_' + i).html(
              '<thead>'+
               '<tr>'+
                 '<th colspan="4" style="text-align: center;">Data Produksi ' + i + '</th>'+
               '</tr>'+
               '<tr>'+
                 '<th>No Lot ' + i + '</th>'+
                 '<td id="td_no_lot_com' + i + '"></td>'+
                 '<th>Tanggal Produksi ' + i + '</th>'+
                 '<td id="td_tanggal_produksi_com' + i + '"></td>'+
               '</tr>'+
               '<tr>'+
                 '<th>Mesin ' + i + '</th>'+
                 '<td id="td_mesin_com' + i + '"></td>'+
                 '<th>Area ' + i + '</th>'+
                 '<td id="td_area_com' + i + '"></td>'+
               '</tr>'+
               '<tr>'+
                 '<th>Produk ' + i + '</th>'+
                 '<td id="td_produk_com' + i + '"></td>'+
                 '<th>Supervisor ' + i + '</th>'+
                 '<td id="td_supervisor_com' + i + '"></td>'+
               '</tr>'+
               '<tr>'+
                 '<th style="vertical-align: top;">Petugas ' + i + '</th>'+
                 '<td id="td_petugas_com' + i + '"></td>'+
                 '<th>Bermasalah ' + i + '?</th>'+
                 '<td id="td_bermasalah_com' + i + '"></td>'+
               '</tr>'+
              '</thead>'
          );
          $('#td_tanggal_produksi_com' + i).html(data.data_produksi[i-1].tanggal_produksi);
          $('#td_no_lot_com' + i).html(data.data_produksi[i-1].no_lot);
          $('#td_mesin_com' + i).html(data.data_produksi[i-1].mesin);
          $('#td_area_com' + i).html(data.data_produksi[i-1].area);
          $('#td_produk_com' + i).html(data.data_produksi[i-1].nama_produk);
          $('#td_supervisor_com' + i).html(data.data_produksi[i-1].supervisor);
          if(data.data_produksi[i-1].petugas1 != null){
            $('#td_petugas_com' + i).html('1. ' + data.data_produksi[i-1].petugas1);
          }
          if(data.data_produksi[i-1].petugas2 != null){
            $('#td_petugas_com' + i).append('<br>2. ' + data.data_produksi[i-1].petugas2);
          }
          if(data.data_produksi[i-1].petugas3 != null){
            $('#td_petugas_com' + i).append('<br>3. ' + data.data_produksi[i-1].petugas3);
          }
          if(data.data_produksi[i-1].petugas4 != null){
            $('#td_petugas_com' + i).append('<br>4. ' + data.data_produksi[i-1].petugas4);
          }
          if(data.data_produksi[i-1].petugas5 != null){
            $('#td_petugas_com' + i).append('<br>5. ' + data.data_produksi[i-1].petugas5);
          }
          $('#td_bermasalah_com' + i).html(data.data_produksi[i-1].bermasalah);
        }
        $('#td_nomor_complaint_com').html(data.data_complaint.nomor_complaint);
        $('#td_nomor_surat_jalan_com').html(data.data_complaint.nomor_surat_jalan);
        $('#td_tanggal_complaint_com').html(data.data_complaint.tanggal_complaint);
        $('#td_customer_com').html(data.data_complaint.nama_customer);
        $('#td_tanggal_order_com').html(data.data_complaint.tanggal_order);
        $('#td_complaint_com').html(data.data_complaint.complaint);
        $('#td_lampiran_com').html('<a target="_blank" href="' + '../data_file/' + data.data_complaint.lampiran + '">Lihat Lampiran</a>');
        $('#td_sales_order_com').html(data.data_complaint.sales_order);
        $('#td_supervisor_sls_com').html(data.data_complaint.supervisor_sales);
        $('#td_pelapor_com').html(data.data_complaint.pelapor);
        $('#td_tanggal_pengiriman_com').html(data.data_complaint.tanggal_pengiriman);
        $('#td_area_com').html(data.data_complaint.area);
        $('#td_supervisor_com').html(data.data_complaint.supervisor);
        $('#td_pengiriman_com').html(data.data_complaint.pengiriman);
        $('#td_pengiriman_lain_com').html(data.data_complaint.pengiriman_lain);
        $('#td_nama_supir_com').html(data.data_complaint.nama_supir);
        $('#td_nama_kernet_com').html(data.data_complaint.nama_kernet);
        $('#td_no_kendaraan_com').html(data.data_complaint.no_kendaraan);
        $('#td_jenis_kendaraan_com').html(data.data_complaint.jenis_kendaraan);
        $('#td_jenis_kendaraan_lain_com').html(data.data_complaint.jenis_kendaraan_lain);
        $('#td_jumlah_karung_com').html(data.data_complaint.jumlah_karung);
        $('#td_quantity_com').html(data.data_complaint.quantity);
        $('#td_jumlah_kg_sak_com').html(data.data_complaint.jumlah_kg_sak);
        $('#td_jenis_karung_com').html(data.data_complaint.jenis_karung);
        $('#td_berat_timbangan_com').html(data.data_complaint.berat_timbangan);
        $('#td_unit_berat_timbangan_com').html(data.data_complaint.unit_berat_timbangan);
        $('#td_berat_aktual_com').html(data.data_complaint.berat_aktual);
        $('#td_unit_berat_aktual_com').html(data.data_complaint.unit_berat_aktual);
        if(data.data_complaint.kuli1 != null){
          $('#td_nama_kuli_com').html('1. ' + data.data_complaint.kuli1);
        }
        if(data.data_complaint.kuli2 != null){
          $('#td_nama_kuli_com').append('<br>2. ' + data.data_complaint.kuli2);
        }
        if(data.data_complaint.kuli3 != null){
          $('#td_nama_kuli_com').append('<br>3. ' + data.data_complaint.kuli3);
        }
        if(data.data_complaint.stapel1 != null){
          $('#td_nama_stapel_com').html('1. ' + data.data_complaint.stapel1);
        }
        if(data.data_complaint.stapel2 != null){
          $('#td_nama_stapel_com').append('<br>2. ' + data.data_complaint.stapel2);
        }
        if(data.data_complaint.stapel3 != null){
          $('#td_nama_stapel_com').append('<br>3. ' + data.data_complaint.stapel3);
        }
        if(data.data_complaint.stapel4 != null){
          $('#td_nama_stapel_com').append('<br>4. ' + data.data_complaint.stapel4);
        }
        if(data.data_complaint.stapel5 != null){
          $('#td_nama_stapel_com').append('<br>5. ' + data.data_complaint.stapel5);
        }
      })
      $('.nav-tabs-lihat a').off('shown.bs.tab').on('shown.bs.tab', function (e) {
        target_lihat = $(this).attr("href");
        if(target_lihat == '#lihat_data_complaint_flow'){
          url = "";
          url = "{{ url('data_complaint_show/no_complaint') }}";
          url = url.replace('no_complaint', enc(nomor_complaint.toString()));
          $.get(url, function (data) {
            td_count = data.data_produksi.length;
            for(var i = 1; i <= td_count; i++){
              $('#td_lihat_data_produksi').append('<table id="td_lihat_data_produksi_' + i + '" class="table table-bordered table-hover"></table>');
              $('#td_lihat_data_produksi_' + i).html(
                '<thead>'+
                '<tr>'+
                '<th colspan="4" style="text-align: center;">Data Produksi ' + i + '</th>'+
                '</tr>'+
                '<tr>'+
                '<th>No Lot ' + i + '</th>'+
                '<td id="td_no_lot_com' + i + '"></td>'+
                '<th>Tanggal Produksi ' + i + '</th>'+
                '<td id="td_tanggal_produksi_com' + i + '"></td>'+
                '</tr>'+
                '<tr>'+
                '<th>Mesin ' + i + '</th>'+
                '<td id="td_mesin_com' + i + '"></td>'+
                '<th>Area ' + i + '</th>'+
                '<td id="td_area_com' + i + '"></td>'+
                '</tr>'+
                '<tr>'+
                '<th>Produk ' + i + '</th>'+
                '<td id="td_produk_com' + i + '"></td>'+
                '<th>Supervisor ' + i + '</th>'+
                '<td id="td_supervisor_com' + i + '"></td>'+
                '</tr>'+
                '<tr>'+
                '<th style="vertical-align: top;">Petugas ' + i + '</th>'+
                '<td id="td_petugas_com' + i + '"></td>'+
                '<th>Bermasalah ' + i + '?</th>'+
                '<td id="td_bermasalah_com' + i + '"></td>'+
                '</tr>'+
                '</thead>'
                );
              $('#td_tanggal_produksi_com' + i).html(data.data_produksi[i-1].tanggal_produksi);
              $('#td_no_lot_com' + i).html(data.data_produksi[i-1].no_lot);
              $('#td_mesin_com' + i).html(data.data_produksi[i-1].mesin);
              $('#td_area_com' + i).html(data.data_produksi[i-1].area);
              $('#td_produk_com' + i).html(data.data_produksi[i-1].nama_produk);
              $('#td_supervisor_com' + i).html(data.data_produksi[i-1].supervisor);
              if(data.data_produksi[i-1].petugas1 != null){
                $('#td_petugas_com' + i).html('1. ' + data.data_produksi[i-1].petugas1);
              }
              if(data.data_produksi[i-1].petugas2 != null){
                $('#td_petugas_com' + i).append('<br>2. ' + data.data_produksi[i-1].petugas2);
              }
              if(data.data_produksi[i-1].petugas3 != null){
                $('#td_petugas_com' + i).append('<br>3. ' + data.data_produksi[i-1].petugas3);
              }
              if(data.data_produksi[i-1].petugas4 != null){
                $('#td_petugas_com' + i).append('<br>4. ' + data.data_produksi[i-1].petugas4);
              }
              if(data.data_produksi[i-1].petugas5 != null){
                $('#td_petugas_com' + i).append('<br>5. ' + data.data_produksi[i-1].petugas5);
              }
              $('#td_bermasalah_com' + i).html(data.data_produksi[i-1].bermasalah);
            }
            $('#td_nomor_complaint_com').html(data.data_complaint.nomor_complaint);
            $('#td_nomor_surat_jalan_com').html(data.data_complaint.nomor_surat_jalan);
            $('#td_tanggal_complaint_com').html(data.data_complaint.tanggal_complaint);
            $('#td_customer_com').html(data.data_complaint.nama_customer);
            $('#td_tanggal_order_com').html(data.data_complaint.tanggal_order);
            $('#td_complaint_com').html(data.data_complaint.complaint);
            $('#td_lampiran_com').html('<a target="_blank" href="' + '../data_file/' + data.data_complaint.lampiran + '">Lihat Lampiran</a>');
            $('#td_sales_order_com').html(data.data_complaint.sales_order);
            $('#td_supervisor_sls_com').html(data.data_complaint.supervisor_sales);
            $('#td_pelapor_com').html(data.data_complaint.pelapor);
            $('#td_tanggal_pengiriman_com').html(data.data_complaint.tanggal_pengiriman);
            $('#td_area_com').html(data.data_complaint.area);
            $('#td_supervisor_com').html(data.data_complaint.supervisor);
            $('#td_pengiriman_com').html(data.data_complaint.pengiriman);
            $('#td_pengiriman_lain_com').html(data.data_complaint.pengiriman_lain);
            $('#td_nama_supir_com').html(data.data_complaint.nama_supir);
            $('#td_nama_kernet_com').html(data.data_complaint.nama_kernet);
            $('#td_no_kendaraan_com').html(data.data_complaint.no_kendaraan);
            $('#td_jenis_kendaraan_com').html(data.data_complaint.jenis_kendaraan);
            $('#td_jenis_kendaraan_lain_com').html(data.data_complaint.jenis_kendaraan_lain);
            $('#td_jumlah_karung_com').html(data.data_complaint.jumlah_karung);
            $('#td_quantity_com').html(data.data_complaint.quantity);
            $('#td_jumlah_kg_sak_com').html(data.data_complaint.jumlah_kg_sak);
            $('#td_jenis_karung_com').html(data.data_complaint.jenis_karung);
            $('#td_berat_timbangan_com').html(data.data_complaint.berat_timbangan);
            $('#td_unit_berat_timbangan_com').html(data.data_complaint.unit_berat_timbangan);
            $('#td_berat_aktual_com').html(data.data_complaint.berat_aktual);
            $('#td_unit_berat_aktual_com').html(data.data_complaint.unit_berat_aktual);
            if(data.data_complaint.kuli1 != null){
              $('#td_nama_kuli_com').html('1. ' + data.data_complaint.kuli1);
            }
            if(data.data_complaint.kuli2 != null){
              $('#td_nama_kuli_com').append('<br>2. ' + data.data_complaint.kuli2);
            }
            if(data.data_complaint.kuli3 != null){
              $('#td_nama_kuli_com').append('<br>3. ' + data.data_complaint.kuli3);
            }
            if(data.data_complaint.stapel1 != null){
              $('#td_nama_stapel_com').html('1. ' + data.data_complaint.stapel1);
            }
            if(data.data_complaint.stapel2 != null){
              $('#td_nama_stapel_com').append('<br>2. ' + data.data_complaint.stapel2);
            }
            if(data.data_complaint.stapel3 != null){
              $('#td_nama_stapel_com').append('<br>3. ' + data.data_complaint.stapel3);
            }
            if(data.data_complaint.stapel4 != null){
              $('#td_nama_stapel_com').append('<br>4. ' + data.data_complaint.stapel4);
            }
            if(data.data_complaint.stapel5 != null){
              $('#td_nama_stapel_com').append('<br>5. ' + data.data_complaint.stapel5);
            }
          })
        }else if(target_lihat == '#lihat_complaint_produksi_flow'){
          url = "";
          url = "{{ route('complaintprod.show', 'no_complaint') }}";
          url = url.replace('no_complaint', enc(nomor_complaint.toString()));
          $.get(url, function (data) {
            td_count_prd = data.data_komitmen.length;
            for(var i = 1; i <= td_count_prd; i++){
              $('#td_lihat_komitmen_produksi').append('<table id="td_lihat_komitmen_produksi_' + i + '" class="table table-bordered table-hover"></table>');
              $('#td_lihat_komitmen_produksi_' + i).html(
                '<thead>'+
                '<tr>'+
                '<th colspan="4" style="text-align: center;">Data Komitmen ' + i + '</th>'+
                '</tr>'+
                '<tr>'+
                '<th>Komitmen ' + i + '</th>'+
                '<td id="td_komitmen_prd' + i + '"></td>'+
                '<th>Selesai Tanggal ' + i + '</th>'+
                '<td id="td_selesai_tanggal_komitmen_prd' + i + '"></td>'+
                '</tr>'+
                '</thead>'
                );
              $('#td_selesai_tanggal_komitmen_prd' + i).html(data.data_komitmen[i-1].selesai_tanggal_komitmen);
              $('#td_komitmen_prd' + i).html(data.data_komitmen[i-1].komitmen);
            }
            $('#td_nomor_complaint_prd').html(data.data_hitung.nomor_complaint);
            $('#td_evaluasi_prd').html(data.data_hitung.evaluasi);
            $('#td_solusi_internal_prd').html(data.data_hitung.solusi_internal);
            $('#td_solusi_customer_prd').html(data.data_hitung.solusi_customer);
            $('#td_lampiran_prd').html('<a target="_blank" href="' + '../data_file/' + data.data_hitung.lampiran + '">Lihat Lampiran</a>');
          })
        }else if(target_lihat == '#lihat_complaint_logistik_flow'){
          url = "";
          url = "{{ route('complaintlog.show', 'no_complaint') }}";
          url = url.replace('no_complaint', enc(nomor_complaint.toString()));
          $.get(url, function (data) {
            td_count_log = data.data_komitmen.length;
            for(var i = 1; i <= td_count_log; i++){
              $('#td_lihat_komitmen_logistik').append('<table id="td_lihat_komitmen_logistik_' + i + '" class="table table-bordered table-hover"></table>');
              $('#td_lihat_komitmen_logistik_' + i).html(
                '<thead>'+
                '<tr>'+
                '<th colspan="4" style="text-align: center;">Data Komitmen ' + i + '</th>'+
                '</tr>'+
                '<tr>'+
                '<th>Komitmen ' + i + '</th>'+
                '<td id="td_komitmen_log' + i + '"></td>'+
                '<th>Selesai Tanggal ' + i + '</th>'+
                '<td id="td_selesai_tanggal_komitmen_log' + i + '"></td>'+
                '</tr>'+
                '</thead>'
                );
              $('#td_selesai_tanggal_komitmen_log' + i).html(data.data_komitmen[i-1].selesai_tanggal_komitmen);
              $('#td_komitmen_log' + i).html(data.data_komitmen[i-1].komitmen);
            }
            $('#td_nomor_complaint_log').html(data.data_complaint.nomor_complaint);
            $('#td_evaluasi_log').html(data.data_complaint.evaluasi);
            $('#td_solusi_internal_log').html(data.data_complaint.solusi_internal);
            $('#td_solusi_customer_log').html(data.data_complaint.solusi_customer);
            $('#td_lampiran_log').html('<a target="_blank" href="' + '../data_file/' + data.data_complaint.lampiran + '">Lihat Lampiran</a>');
          })
        }else if(target_lihat == '#lihat_complaint_sales_flow'){
          url = "";
          url = "{{ route('complaintsales.show', 'no_complaint') }}";
          url = url.replace('no_complaint', enc(nomor_complaint.toString()));
          $.get(url, function (data) {
            td_count_sls = data.data_komitmen.length;
            for(var i = 1; i <= td_count_sls; i++){
              $('#td_lihat_komitmen_sales').append('<table id="td_lihat_komitmen_sales_' + i + '" class="table table-bordered table-hover"></table>');
              $('#td_lihat_komitmen_sales_' + i).html(
                '<thead>'+
                '<tr>'+
                '<th colspan="4" style="text-align: center;">Data Komitmen ' + i + '</th>'+
                '</tr>'+
                '<tr>'+
                '<th>Komitmen ' + i + '</th>'+
                '<td id="td_komitmen_sls' + i + '"></td>'+
                '<th>Selesai Tanggal ' + i + '</th>'+
                '<td id="td_selesai_tanggal_komitmen_sls' + i + '"></td>'+
                '</tr>'+
                '</thead>'
                );
              $('#td_selesai_tanggal_komitmen_sls' + i).html(data.data_komitmen[i-1].selesai_tanggal_komitmen);
              $('#td_komitmen_sls' + i).html(data.data_komitmen[i-1].komitmen);
            }
            $('#td_nomor_complaint_sls').html(data.data_hitung.nomor_complaint);
            $('#td_tanggal_customer_sls').html(data.data_hitung.tanggal_customer_setuju);
            $('#td_evaluasi_sls').html(data.data_hitung.evaluasi);
            $('#td_solusi_internal_sls').html(data.data_hitung.solusi_internal);
            $('#td_solusi_customer_sls').html(data.data_hitung.solusi_customer);
            $('#td_lampiran_sls').html('<a target="_blank" href="' + '../data_file/' + data.data_hitung.lampiran + '">Lihat Lampiran</a>');
          })
        }else if(target_lihat == '#lihat_complaint_timbangan_flow'){
          url = "";
          url = "{{ route('complainttimbang.show', 'no_complaint') }}";
          url = url.replace('no_complaint', enc(nomor_complaint.toString()));
          $.get(url, function (data) {
            td_count_timbang = data.data_komitmen.length;
            for(var i = 1; i <= td_count_timbang; i++){
              $('#td_lihat_komitmen_timbangan').append('<table id="td_lihat_komitmen_timbangan_' + i + '" class="table table-bordered table-hover"></table>');
              $('#td_lihat_komitmen_timbangan_' + i).html(
                '<thead>'+
                '<tr>'+
                '<th colspan="4" style="text-align: center;">Data Komitmen ' + i + '</th>'+
                '</tr>'+
                '<tr>'+
                '<th>Komitmen ' + i + '</th>'+
                '<td id="td_komitmen_timbang' + i + '"></td>'+
                '<th>Selesai Tanggal ' + i + '</th>'+
                '<td id="td_selesai_tanggal_komitmen_timbang' + i + '"></td>'+
                '</tr>'+
                '</thead>'
                );
              $('#td_selesai_tanggal_komitmen_timbang' + i).html(data.data_komitmen[i-1].selesai_tanggal_komitmen);
              $('#td_komitmen_timbang' + i).html(data.data_komitmen[i-1].komitmen);
            }
            $('#td_nomor_complaint_timbang').html(data.data_complaint.nomor_complaint);
            $('#td_evaluasi_timbang').html(data.data_complaint.evaluasi);
            $('#td_solusi_internal_timbang').html(data.data_complaint.solusi_internal);
            $('#td_solusi_customer_timbang').html(data.data_complaint.solusi_customer);
            $('#td_lampiran_timbang').html('<a target="_blank" href="' + '../data_file/' + data.data_complaint.lampiran + '">Lihat Lampiran</a>');
          })
        }else if(target_lihat == '#lihat_complaint_warehouse_flow'){
          url = "";
          url = "{{ route('complaintware.show', 'no_complaint') }}";
          url = url.replace('no_complaint', enc(nomor_complaint.toString()));
          $.get(url, function (data) {
            td_count_ware = data.data_komitmen.length;
            for(var i = 1; i <= td_count_ware; i++){
              $('#td_lihat_komitmen_warehouse').append('<table id="td_lihat_komitmen_warehouse_' + i + '" class="table table-bordered table-hover"></table>');
              $('#td_lihat_komitmen_warehouse_' + i).html(
                '<thead>'+
                '<tr>'+
                '<th colspan="4" style="text-align: center;">Data Komitmen ' + i + '</th>'+
                '</tr>'+
                '<tr>'+
                '<th>Komitmen ' + i + '</th>'+
                '<td id="td_komitmen_ware' + i + '"></td>'+
                '<th>Selesai Tanggal ' + i + '</th>'+
                '<td id="td_selesai_tanggal_komitmen_ware' + i + '"></td>'+
                '</tr>'+
                '</thead>'
                );
              $('#td_selesai_tanggal_komitmen_ware' + i).html(data.data_komitmen[i-1].selesai_tanggal_komitmen);
              $('#td_komitmen_ware' + i).html(data.data_komitmen[i-1].komitmen);
            }
            $('#td_nomor_complaint_ware').html(data.data_complaint.nomor_complaint);
            $('#td_evaluasi_ware').html(data.data_complaint.evaluasi);
            $('#td_solusi_internal_ware').html(data.data_complaint.solusi_internal);
            $('#td_solusi_customer_ware').html(data.data_complaint.solusi_customer);
            $('#td_lampiran_ware').html('<a target="_blank" href="' + '../data_file/' + data.data_complaint.lampiran + '">Lihat Lampiran</a>');
          })
        }else if(target_lihat == '#lihat_complaint_lab_flow'){
          url = "";
          url = "{{ route('complaintlab.show', 'no_complaint') }}";
          url = url.replace('no_complaint', enc(nomor_complaint.toString()));
          $.get(url, function (data) {
            td_count_lab = data.data_lab.length;
            for(var i = 1; i <= td_count_lab; i++){
              $('#td_lihat_lab').append('<table id="td_lihat_lab_' + i + '" class="table table-bordered table-hover"></table><div id="td_lihat_quality_lab"></div>');
              $('#td_lihat_lab_' + i).html(
                '<thead>'+
                '<tr>'+
                '<th colspan="6" style="text-align: center;">Data Lab ' + i + '</th>'+
                '</tr>'+
                '<tr>'+
                '<th>No Sample ' + i + '</th>'+
                '<td colspan="2" id="td_no_sample_data_lab' + i + '"></td>'+
                '<th>No Lot ' + i + '</th>'+
                '<td colspan="2" id="td_no_lot_data_lab' + i + '"></td>'+
                '</tr>'+
                '<tr>'+
                '<th>Keterangan ' + i + '</th>'+
                '<td colspan="5" id="td_keterangan_lab' + i + '"></td>'+
                '</tr>'
                );
              $('#td_no_lot_data_lab' + i).html(data.data_lab[i-1].no_lot);
              $('#td_no_sample_data_lab' + i).html(data.data_lab[i-1].nomor_sample_lab);
              $('#td_keterangan_lab' + i).html(data.data_lab[i-1].keterangan);
                for(var j = 1; j <= data.data_quality_lab.length; j++){
                  if(data.data_quality_lab[j-1].nomor_sample_lab == data.data_lab[i-1].nomor_sample_lab){
                    $('#td_lihat_lab_' + i).append(
                      '<tr>'+
                      '<th colspan="6" id="td_quality_name_lab' + j + '"></th>'+
                      '</tr>'+
                      '<th>No Lot ' + j + '</th>'+
                      '<td id="td_no_lot_quality_lab' + j + '"></td>'+
                      '<th>Metode / Mesin ' + j + '</th>'+
                      '<td id="td_metode_mesin_lab' + j + '"></td>'+
                      '<th>Hasil ' + j + '</th>'+
                      '<td id="td_hasil_lab' + j + '"></td>'+
                      '</tr>'+
                      '</thead>'
                    );
                    if(data.data_quality_lab[j-1].quality_name == 8){
                      $('#td_quality_name_lab' + j).html(data.data_quality_lab[j-1].quality_name_lainnya);
                    }else{
                      $('#td_quality_name_lab' + j).html(data.data_quality_lab[j-1].quality);
                    }
                    $('#td_no_lot_quality_lab' + j).html(data.data_quality_lab[j-1].no_lot);
                    $('#td_metode_mesin_lab' + j).html(data.data_quality_lab[j-1].metode_mesin);
                    $('#td_hasil_lab' + j).html(data.data_quality_lab[j-1].hasil + ' ' + data.data_quality_lab[j-1].satuan);
                  }
                }
            }
            $('#td_nomor_complaint_lab').html(data.data_hitung.nomor_complaint);
            $('#td_suggestion_lab').html(data.data_hitung.suggestion);
            $('#td_lampiran_lab').html('<a target="_blank" href="' + '../data_file/' + data.data_hitung.lampiran + '">Lihat Lampiran</a>');
          })
        }else if(target_lihat == '#lihat_complaint_lainnya_flow'){
          url = "";
          url = "{{ route('complaintlain.show', 'no_complaint') }}";
          url = url.replace('no_complaint', enc(nomor_complaint.toString()));
          $.get(url, function (data) {
            td_count_lny = data.data_komitmen.length;
            for(var i = 1; i <= td_count_lny; i++){
              $('#td_lihat_komitmen_lainnya').append('<table id="td_lihat_komitmen_lainnya_' + i + '" class="table table-bordered table-hover"></table>');
              $('#td_lihat_komitmen_lainnya_' + i).html(
                '<thead>'+
                '<tr>'+
                '<th colspan="4" style="text-align: center;">Data Komitmen ' + i + '</th>'+
                '</tr>'+
                '<tr>'+
                '<th>Komitmen ' + i + '</th>'+
                '<td id="td_komitmen_lny' + i + '"></td>'+
                '<th>Selesai Tanggal ' + i + '</th>'+
                '<td id="td_selesai_tanggal_komitmen_lny' + i + '"></td>'+
                '</tr>'+
                '</thead>'
                );
              $('#td_selesai_tanggal_komitmen_lny' + i).html(data.data_komitmen[i-1].selesai_tanggal_komitmen);
              $('#td_komitmen_lny' + i).html(data.data_komitmen[i-1].komitmen);
            }
            $('#td_nomor_complaint_lny').html(data.data_complaint.nomor_complaint);
            $('#td_divisi_lny').html(data.data_complaint.divisi);
            $('#td_evaluasi_lny').html(data.data_complaint.evaluasi);
            $('#td_solusi_internal_lny').html(data.data_complaint.solusi_internal);
            $('#td_solusi_customer_lny').html(data.data_complaint.solusi_customer);
            $('#td_lampiran_lny').html('<a target="_blank" href="' + '../data_file/' + data.data_complaint.lampiran + '">Lihat Lampiran</a>');
          })
        }
      });
    });

    $('body').on('click', '#edit_complaint', function () {
      var complaint = $(this).data('id');
      $('#edit_nomor_complaint').val(complaint);
      var url = "{{ url('complaint/show/edit/no_complaint') }}";
      url = url.replace('no_complaint', enc(complaint.toString()));

      $("#edit_pengiriman").change(function() {
        if ($(this).val() == 3) {
          $('#edit_pengiriman_lain').show();
        }else{
          $('#edit_pengiriman_lain').hide();
        }
      });
      $("#edit_jenis_kendaraan").change(function() {
        if ($(this).val() == 7) {
          $('#edit_jenis_kendaraan_lain').show();
        }else{
          $('#edit_jenis_kendaraan_lain').hide();
        }
      });
      $("#edit_jumlah_kg_sak").change(function() {
        if($(this).val() == 800){
          $("#edit_jenis_karung").val('3').trigger('change');
        }
        if($("#edit_jumlah_karung").val() != null || $("#edit_jumlah_karung").val() != ''){
          if($("#edit_unit_berat_timbangan").val() == 2){
            var a = $("#edit_jumlah_karung").val();
            var b = $("#edit_jumlah_kg_sak").val();
            var total = a * b;
            $("#edit_berat_aktual").val(total / 1000);
          }else{
            var a = $("#edit_jumlah_karung").val();
            var b = $("#edit_jumlah_kg_sak").val();
            var total = a * b;
            $("#edit_berat_aktual").val(total);
          }
        }
      });
      $('#edit_jumlah_karung').on('keyup', function(){
        if($("#edit_jumlah_kg_sak").val() != null || $("#edit_jumlah_kg_sak").val() != ''){
          if($("#edit_unit_berat_timbangan").val() == 2){
            var a = $("#edit_jumlah_karung").val();
            var b = $("#edit_jumlah_kg_sak").val();
            var total = a * b;
            $("#edit_berat_aktual").val(total / 1000);
          }else{
            var a = $("#edit_jumlah_karung").val();
            var b = $("#edit_jumlah_kg_sak").val();
            var total = a * b;
            $("#edit_berat_aktual").val(total);
          }
        }
      });
      $("#edit_unit_berat_timbangan").change(function() {
        var val = $(this).val();
        var amount = $("#edit_berat_aktual").val();
        var a = $("#edit_jumlah_karung").val();
        var b = $("#edit_jumlah_kg_sak").val();
        var total = a * b;
        if(val == 2){
          amount = amount / 1000;
          $("#edit_berat_aktual").val(amount);
        }else{
          if(amount == total / 1000){
            amount = amount * 1000;
            $("#edit_berat_aktual").val(amount);
          }
        }
        $("#edit_unit_berat_aktual").val(val).trigger('change');
        $("#edit_input_unit_berat_aktual").val(val);
      });

      c_edit_petugas=2;
      $('#edit_add_petugas').click(function(){    
        if(c_edit_petugas < 6){
          $('#edit_dynamic_field_petugas').append('<tr id="edit_row_petugas'+c_edit_petugas+'"><td><input type="text" name="edit_petugas[]" placeholder="Petugas" class="form-control edit_petugas_list" /></td><td><button type="button" name="edit_petugas_remove" id="'+c_edit_petugas+'" class="btn btn-danger edit_btn_remove_petugas">X</button></td></tr>');  
          c_edit_petugas++;
        }
      });

      $(document).on('click', '.edit_btn_remove_petugas', function(){  
        var button_id = $(this).attr("id");   
        $('#edit_row_petugas'+button_id+'').remove();  
        c_edit_petugas--;
      });

      var url_prod = "{{ url('get_products') }}";
      $.get(url_prod, function (data_products) {
        $('#edit_produk').children().remove().end().append('<option value="" selected>Choose Products</option>');
        $.each(data_products, function(k, v) {
          $('#edit_produk').append('<option value="' + v.kode_produk + '">' + v.nama_produk + '</option>');
        });
      })
      $('#edit_tanggal_complaint').val('');
      $('#edit_tanggal_order').val('');
      $('#edit_nomor_surat_jalan').val('');
      $('#edit_txt_complaint').html('');
      $('#edit_sales_order').val('');
      $('#edit_supervisor_sls').val('');
      $('#edit_pelapor').val('');
      $('#edit_upload_file').val('');
      $('#edit_custid').val('');
      $("#edit_custid").trigger('change');
      $('#edit_tanggal_pengiriman').val('');
      $('#edit_area_log').val('').trigger('change');
      $('#edit_no_kendaraan').val('');
      $('#edit_nama_supir').val('');
      $('#edit_nama_kernet').val('');
      $('#edit_pengiriman').val('').trigger('change');
      $('#edit_jenis_kendaraan').val('').trigger('change');
      $('#edit_supervisor_log').val('');
      $('#edit_pengiriman_lain').val('');
      $('#edit_jenis_kendaraan_lain').val('');
      $('#edit_jumlah_karung').val('');
      $('#edit_quantity').val('');
      $('#edit_jumlah_kg_sak').val('').trigger('change');
      $('#edit_jenis_karung').val('').trigger('change');
      $('#edit_berat_timbangan').val('');
      $('#edit_unit_berat_timbangan').val('').trigger('change');
      $('#edit_berat_aktual').val('');
      $('#edit_unit_berat_aktual').val('').trigger('change');
      $('#edit_pengiriman_lain').hide();
      $('#edit_jenis_kendaraan_lain').hide();
      $('#edit_tanggal_produksi').val('');
      $('#edit_no_lot').val('');
      $('#edit_mesin').val('');
      $('#edit_supervisor_prd').val('');
      $('#edit_petugas').val('');
      $('#edit_area_prd').val('');
      $('[name="edit_nama_kuli[]"]').val('');
      $('#edit_row_kuli1').remove();
      $('#edit_row_kuli2').remove();
      $('#edit_row_kuli3').remove();
      $('[name="edit_nama_stapel[]"]').val('');
      $('#edit_row_stapel1').remove();
      $('#edit_row_stapel2').remove();
      $('#edit_row_stapel3').remove();
      $('#edit_row_stapel4').remove();
      $('#edit_row_stapel5').remove();
      $('[name="edit_petugas[]"]').val('');
      $('#edit_row_petugas1').remove();
      $('#edit_row_petugas2').remove();
      $('#edit_row_petugas3').remove();
      $('#edit_row_petugas4').remove();
      $('#edit_row_petugas5').remove();
      $.get(url, function (data) {
        $('#edit_list_no_lot_table').DataTable().destroy();
        load_data_edit_no_lot(data.nomor_complaint);
        $('#edit_tanggal_complaint').val(data.tanggal_complaint);
        $('#edit_nomor_surat_jalan').val(data.nomor_surat_jalan);
        $('#edit_txt_complaint').html(data.complaint);
        $('#edit_sales_order').val(data.sales_order);
        $('#edit_supervisor_sls').val(data.supervisor_sales);
        $('#edit_pelapor').val(data.pelapor);
        if(data.custid){
          $("#edit_custid").append('<option value="' + data.custid + '">' + data.custname + '</option>');
          $("#edit_custid").val(data.custid);
          $("#edit_custid").trigger('change');
        }else{
          $('#edit_custid').val('');
          $("#edit_custid").trigger('change');
        }

        c_edit_kuli=1;
        if(data.kuli1 != null){
          c_edit_kuli=2;
          $('[name="edit_nama_kuli[]"]').val(data.kuli1);
          // $('#edit_nama_kuli[]').val(data.kuli1);
        }

        if(data.kuli2 != null){
          c_edit_kuli=3;
          $('#edit_dynamic_field_kuli').append('<tr id="edit_row_kuli2"><td><input type="text" name="edit_nama_kuli[]" placeholder="Nama Kuli" class="form-control edit_nama_kuli_list" value="'+data.kuli2+'"/></td><td><button type="button" name="edit_kuli_remove" id="2" class="btn btn-danger edit_btn_remove_kuli">X</button></td></tr>');
        }

        if(data.kuli3 != null){
          c_edit_kuli=4;
          $('#edit_dynamic_field_kuli').append('<tr id="edit_row_kuli3"><td><input type="text" name="edit_nama_kuli[]" placeholder="Nama Kuli" class="form-control edit_nama_kuli_list" value="'+data.kuli3+'"/></td><td><button type="button" name="edit_kuli_remove" id="3" class="btn btn-danger edit_btn_remove_kuli">X</button></td></tr>');
        }

        $('#edit_add_kuli').click(function(){  
          if(c_edit_kuli < 4){
            $('#edit_dynamic_field_kuli').append('<tr id="edit_row_kuli'+c_edit_kuli+'"><td><input type="text" name="edit_nama_kuli[]" placeholder="Nama Kuli" class="form-control edit_nama_kuli_list" /></td><td><button type="button" name="edit_kuli_remove" id="'+c_edit_kuli+'" class="btn btn-danger edit_btn_remove_kuli">X</button></td></tr>');  
            c_edit_kuli++;
          }
        });

        $(document).on('click', '.edit_btn_remove_kuli', function(){  
          var button_id = $(this).attr("id");   
          $('#edit_row_kuli'+button_id+'').remove();
          c_edit_kuli--;
        });

        c_edit_stapel=1;
        if(data.stapel1 != null){
          c_edit_stapel=2;
          $('[name="edit_nama_stapel[]"]').val(data.stapel1);
          // $('#edit_nama_stapel[]').val(data.stapel1);
        }

        if(data.stapel2 != null){
          c_edit_stapel=3;
          $('#edit_dynamic_field_stapel').append('<tr id="edit_row_stapel2"><td><input type="text" name="edit_nama_stapel[]" placeholder="Nama Stapel" class="form-control edit_nama_stapel_list" value="'+data.stapel2+'"/></td><td><button type="button" name="edit_stapel_remove" id="2" class="btn btn-danger edit_btn_remove_stapel">X</button></td></tr>');
        }

        if(data.stapel3 != null){
          c_edit_stapel=4;
          $('#edit_dynamic_field_stapel').append('<tr id="edit_row_stapel3"><td><input type="text" name="edit_nama_stapel[]" placeholder="Nama Stapel" class="form-control edit_nama_stapel_list" value="'+data.stapel3+'"/></td><td><button type="button" name="edit_stapel_remove" id="3" class="btn btn-danger edit_btn_remove_stapel">X</button></td></tr>');
        }

        if(data.stapel4 != null){
          c_edit_stapel=5;
          $('#edit_dynamic_field_stapel').append('<tr id="edit_row_stapel4"><td><input type="text" name="edit_nama_stapel[]" placeholder="Nama Stapel" class="form-control edit_nama_stapel_list" value="'+data.stapel4+'"/></td><td><button type="button" name="edit_stapel_remove" id="4" class="btn btn-danger edit_btn_remove_stapel">X</button></td></tr>');
        }

        if(data.stapel5 != null){
          c_edit_stapel=6;
          $('#edit_dynamic_field_stapel').append('<tr id="edit_row_stapel5"><td><input type="text" name="edit_nama_stapel[]" placeholder="Nama Stapel" class="form-control edit_nama_stapel_list" value="'+data.stapel5+'"/></td><td><button type="button" name="edit_stapel_remove" id="5" class="btn btn-danger edit_btn_remove_stapel">X</button></td></tr>');
        }

        $('#edit_add_stapel').click(function(){  
          if(c_edit_stapel < 6){
            $('#edit_dynamic_field_stapel').append('<tr id="edit_row_stapel'+c_edit_stapel+'"><td><input type="text" name="edit_nama_stapel[]" placeholder="Nama Stapel" class="form-control edit_nama_stapel_list" /></td><td><button type="button" name="edit_stapel_remove" id="'+c_edit_stapel+'" class="btn btn-danger edit_btn_remove_stapel">X</button></td></tr>');  
            c_edit_stapel++;
          }
        });

        $(document).on('click', '.edit_btn_remove_stapel', function(){  
          var button_id = $(this).attr("id");   
          $('#edit_row_stapel'+button_id+'').remove();
          c_edit_stapel--;
        });

        if(data.lampiran == null){
          $('#lihat_lampiran').html('No Lampiran');
          $('#lihat_lampiran').addClass('disabled');
          $('#lihat_lampiran').attr('href', '#');
        }else{
          $('#lihat_lampiran').html('Lihat Lampiran');
          $('#lihat_lampiran').removeClass('disabled');
          $('#lihat_lampiran').attr('href', '../data_file/' + data.lampiran);
        }
        if(data.tanggal_pengiriman == '' || data.tanggal_pengiriman == null){
          $('#edit_tanggal_pengiriman').val('');
        }else{
          $('#edit_tanggal_pengiriman').val(data.tanggal_pengiriman);
        }
        if(data.tanggal_order == '' || data.tanggal_order == null){
          $('#edit_tanggal_order').val('');
        }else{
          $('#edit_tanggal_order').val(data.tanggal_order);
        }
        if(data.area == '' || data.area == null){
          $('#edit_area_log').val('').trigger('change');
        }else{
          $('#edit_area_log').val(data.area).trigger('change');
        }
        $('#edit_no_kendaraan').val(data.no_kendaraan);
        $('#edit_nama_supir').val(data.nama_supir);
        $('#edit_nama_kernet').val(data.nama_kernet);
        if(data.pengiriman == '' || data.pengiriman == null){
          $('#edit_pengiriman').val('').trigger('change');
        }else{
          $('#edit_pengiriman').val(data.pengiriman).trigger('change');
        }
        if(data.jenis_kendaraan == '' || data.jenis_kendaraan == null){
          $('#edit_jenis_kendaraan').val('').trigger('change');
        }else{
          $('#edit_jenis_kendaraan').val(data.jenis_kendaraan).trigger('change');
        }
        $('#edit_supervisor_log').val(data.supervisor);
        if(data.pengiriman_lain == '' || data.pengiriman_lain == null){
          $('#edit_pengiriman_lain').hide();
        }else{
          $('#edit_pengiriman_lain').show();
          $('#edit_pengiriman_lain').val(data.pengiriman_lain);
        }
        if(data.jenis_kendaraan_lain == '' || data.jenis_kendaraan_lain == null){
          $('#edit_jenis_kendaraan_lain').hide();
        }else{
          $('#edit_jenis_kendaraan_lain').show();
          $('#edit_jenis_kendaraan_lain').val(data.jenis_kendaraan_lain);
        }
        $('#edit_jumlah_karung').val(data.jumlah_karung);
        $('#edit_quantity').val(data.quantity);
        if(data.jumlah_kg_sak == '' || data.jumlah_kg_sak == null){
          $('#edit_jumlah_kg_sak').val('').trigger('change');
        }else{
          $('#edit_jumlah_kg_sak').val(data.jumlah_kg_sak).trigger('change');
        }
        if(data.jenis_karung == '' || data.jenis_karung == null){
          $('#edit_jenis_karung').val('').trigger('change');
        }else{
          $('#edit_jenis_karung').val(data.jenis_karung).trigger('change');
        }
        $('#edit_berat_timbangan').val(data.berat_timbangan);
        if(data.unit_berat_timbangan == '' || data.unit_berat_timbangan == null){
          $('#edit_unit_berat_timbangan').val('').trigger('change');
        }else{
          $('#edit_unit_berat_timbangan').val(data.unit_berat_timbangan).trigger('change');
        }
        $('#edit_berat_aktual').val(data.berat_aktual);
        if(data.unit_berat_aktual == '' || data.unit_berat_aktual == null){
          $('#edit_unit_berat_aktual').val('').trigger('change');
        }else{
          $('#edit_unit_berat_aktual').val(data.unit_berat_aktual).trigger('change');
        }
      })
    });
    
    $('body').on('click', '#edit-data-produksi', function () {
      var no_complaint = $(this).data("id");
      var no_lot = $(this).data("nolot");
      var url = "{{ url('complaint_data_produksi/show/no_complaint/no_lot') }}";
      url = url.replace('no_complaint', enc(no_complaint.toString()));
      url = url.replace('no_lot', enc(no_lot.toString()));
      $('#edit_no_lot_lama').val(no_lot);
      $('#edit_tanggal_produksi').val('');
      $('#edit_no_lot').val('');
      $('#edit_mesin').val('');
      $('#edit_supervisor_prd').val('');
      $('[name="edit_petugas[]"]').val('');
      $('#edit_row_petugas1').remove();
      $('#edit_row_petugas2').remove();
      $('#edit_row_petugas3').remove();
      $('#edit_row_petugas4').remove();
      $('#edit_row_petugas5').remove();
      $('#edit_area_prd').val('');
      $('#edit_produk').val('');
      $('#save_edit_produksi').show();
      $('#save_new_edit_produksi').hide();
      $.get(url, function (data) {
        if(data.tanggal_produksi == '' || data.tanggal_produksi == null){
          $('#edit_tanggal_produksi').val('');
        }else{
          $('#edit_tanggal_produksi').val(data.tanggal_produksi);
        }
        $('#edit_no_lot').val(data.no_lot);
        $('#edit_mesin').val(data.mesin);
        $('#edit_supervisor_prd').val(data.supervisor);
        $('#edit_petugas').val(data.petugas);
        if(data.area == '' || data.area == null){
          $('#edit_area_prd').val('');
        }else{
          $('#edit_area_prd').val(data.area);
        }

        if(data.petugas1 != null){
          c_edit_petugas=2;
          $('[name="edit_petugas[]"]').val(data.petugas1);
          // $('#edit_petugas[]').val(data.petugas1);
        }

        if(data.petugas2 != null){
          c_edit_petugas=3;
          $('#edit_dynamic_field_petugas').append('<tr id="edit_row_petugas2"><td><input type="text" name="edit_petugas[]" placeholder="Petugas" class="form-control edit_petugas_list" value="'+data.petugas2+'"/></td><td><button type="button" name="edit_petugas_remove" id="2" class="btn btn-danger edit_btn_remove_petugas">X</button></td></tr>');
        }

        if(data.petugas3 != null){
          c_edit_petugas=4;
          $('#edit_dynamic_field_petugas').append('<tr id="edit_row_petugas3"><td><input type="text" name="edit_petugas[]" placeholder="Petugas" class="form-control edit_petugas_list" value="'+data.petugas3+'"/></td><td><button type="button" name="edit_petugas_remove" id="3" class="btn btn-danger edit_btn_remove_petugas">X</button></td></tr>');
        }

        if(data.petugas4 != null){
          c_edit_petugas=5;
          $('#edit_dynamic_field_petugas').append('<tr id="edit_row_petugas4"><td><input type="text" name="edit_petugas[]" placeholder="Petugas" class="form-control edit_petugas_list" value="'+data.petugas4+'"/></td><td><button type="button" name="edit_petugas_remove" id="4" class="btn btn-danger edit_btn_remove_petugas">X</button></td></tr>');
        }

        if(data.petugas5 != null){
          c_edit_petugas=6;
          $('#edit_dynamic_field_petugas').append('<tr id="edit_row_petugas5"><td><input type="text" name="edit_petugas[]" placeholder="Petugas" class="form-control edit_petugas_list" value="'+data.petugas5+'"/></td><td><button type="button" name="edit_petugas_remove" id="5" class="btn btn-danger edit_btn_remove_petugas">X</button></td></tr>');
        }

        // $('#edit_add_petugas').click(function(){  
        //   if(petugas < 6){
        //     $('#edit_dynamic_field_petugas').append('<tr id="edit_row_petugas'+petugas+'"><td><input type="text" name="edit_petugas[]" placeholder="Petugas" class="form-control edit_petugas_list" /></td><td><button type="button" name="edit_petugas_remove" id="'+petugas+'" class="btn btn-danger edit_btn_remove_petugas">X</button></td></tr>');  
        //     petugas++;
        //   }
        // });

        // $(document).on('click', '.edit_btn_remove_petugas', function(){  
        //   var button_id = $(this).attr("id");   
        //   $('#edit_row_petugas'+button_id+'').remove();
        //   petugas--;
        // });

        if(data.kode_produk == '' || data.kode_produk == null){
          $('#edit_produk').val('');
        }else{
          $('#edit_produk').val(data.kode_produk);
        }
        if(data.bermasalah == 'Ya'){
          $("#edit_bermasalah").prop("checked", true);
        }else{
          $("#edit_tidak_bermasalah").prop("checked", true);
        }
      })
    });

    $('body').on('click', '#edit-delete-data-produksi', function () {
      var no_complaint = $(this).data("id");
      var no_lot = $(this).data("nolot");
      if(confirm("Delete this data?")){
        $.ajax({
          type: "GET",
          url: "{{ url('edit_delete_data_comp_produksi') }}",
          data: { 'no_complaint' : no_complaint, 'no_lot' : no_lot },
          success: function (data) {
            var oTable = $('#edit_list_no_lot_table').dataTable(); 
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#save_new_edit_produksi', function () {
      if($('#edit_custid').val() == '' || $('#edit_custid').val() == null){
        alert('Isi Customers Terlebih Dahulu');
      }else{
        var tanggal_produksi = $('#edit_tanggal_produksi').val();
        var no_lot = $('#edit_no_lot').val();
        var mesin = $('#edit_mesin').val();
        var supervisor = $('#edit_supervisor_prd').val();
        var petugas = $('#edit_dynamic_field_petugas :input').serializeArray();
        var area = $('#edit_area_prd').val();
        var kode_produk = $('#edit_produk').val();
        var bermasalah = $("input[name='edit_input_bermasalah']:checked").val();
        var custid = $('#edit_custid').val();
        var nomor_complaint = $('#edit_nomor_complaint').val();
        $.ajax({
          type: "GET",
          url: "{{ url('save_data_comp_produksi_edit') }}",
          data: { 'nomor_complaint' : nomor_complaint, 'tanggal_produksi' : tanggal_produksi, 'no_lot' : no_lot, 'kode_produk' : kode_produk, 'mesin' : mesin, 'supervisor' : supervisor, 'petugas' : petugas, 'area' : area, 'bermasalah' : bermasalah, 'custid' : custid },
          success: function (data) {
            $('#edit_tanggal_produksi').val('');
            $('#edit_no_lot').val('');
            $('#edit_mesin').val('');
            $('#edit_supervisor_prd').val('');
            $('[name="edit_petugas[]"]').val('');
            $('#edit_row_petugas1').remove();
            $('#edit_row_petugas2').remove();
            $('#edit_row_petugas3').remove();
            $('#edit_row_petugas4').remove();
            $('#edit_row_petugas5').remove();
            c_edit_petugas = 2;
            $('#edit_area_prd').val('').trigger('change');
            $('#edit_produk').val('').trigger('change');
            $("#edit_bermasalah").prop("checked", true);
            $('#save_edit_produksi').hide();
            $('#save_new_edit_produksi').show();
            var oTable = $('#edit_list_no_lot_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            var oTable = $('#edit_list_no_lot_table').dataTable();
            oTable.fnDraw(false);
            console.log('Error:', data);
            alert("Isi Semua Data Produksi, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
          }
        });
      }
    });

    $('body').on('click', '#save_edit_produksi', function () {
      if($('#edit_custid').val() == '' || $('#edit_custid').val() == null){
        alert('Isi Customers Terlebih Dahulu');
      }else{
        var tanggal_produksi = $('#edit_tanggal_produksi').val();
        var no_lot = $('#edit_no_lot').val();
        var no_lot_lama = $('#edit_no_lot_lama').val();
        var mesin = $('#edit_mesin').val();
        var supervisor = $('#edit_supervisor_prd').val();
        var petugas = $('#edit_dynamic_field_petugas :input').serializeArray();
        var area = $('#edit_area_prd').val();
        var kode_produk = $('#edit_produk').val();
        var bermasalah = $("input[name='edit_input_bermasalah']:checked").val();
        var custid = $('#edit_custid').val();
        var nomor_complaint = $('#edit_nomor_complaint').val();
        $.ajax({
          type: "GET",
          url: "{{ url('save_edit_data_comp_produksi') }}",
          data: { 'nomor_complaint' : nomor_complaint, 'tanggal_produksi' : tanggal_produksi, 'no_lot' : no_lot, 'kode_produk' : kode_produk, 'mesin' : mesin, 'supervisor' : supervisor, 'petugas' : petugas, 'area' : area, 'bermasalah' : bermasalah, 'custid' : custid, 'no_lot_lama' : no_lot_lama },
          success: function (data) {
            $('#edit_tanggal_produksi').val('');
            $('#edit_no_lot').val('');
            $('#edit_no_lot_lama').val('');
            $('#edit_mesin').val('');
            $('#edit_supervisor_prd').val('');
            $('[name="edit_petugas[]"]').val('');
            $('#edit_row_petugas1').remove();
            $('#edit_row_petugas2').remove();
            $('#edit_row_petugas3').remove();
            $('#edit_row_petugas4').remove();
            $('#edit_row_petugas5').remove();
            c_edit_petugas = 2;
            $('#edit_area_prd').val('').trigger('change');
            $('#edit_produk').val('').trigger('change');
            $("#edit_bermasalah").prop("checked", true);
            $('#save_edit_produksi').hide();
            $('#save_new_edit_produksi').show();
            var oTable = $('#edit_list_no_lot_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            var oTable = $('#edit_list_no_lot_table').dataTable();
            oTable.fnDraw(false);
            console.log('Error:', data);
            alert("Isi Semua Data Produksi, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
          }
        });
      }
    });

    $('body').on('click', '#edit_complaint_sales', function () {
      var complaint = $(this).data('id');
      var custid_sls = $(this).data('custid');
      $('#edit_nomor_complaint_sls').val(complaint);
      $('#edit_custid_sls').val(custid_sls);
      var url = "{{ url('complaint_sales/show/edit/no_complaint') }}";
      url = url.replace('no_complaint', enc(complaint.toString()));
      $('#edit_tanggal_customer').val('');
      $('#edit_upload_file_sls').html('');
      $('#edit_evaluasi_sls').html('');
      $('#edit_solusi_customer_sls').html('');
      $('#edit_solusi_internal_sls').html('');
      $('#edit_komitmen_sls').html('');
      $('#edit_selesai_tanggal_komitmen_sls').html('');
      $.get(url, function (data) {
        $('#edit_tanggal_customer').val(data.data_complaint.tanggal_customer_setuju);
        $('#edit_evaluasi_sls').html(data.data_complaint.evaluasi);
        $('#edit_solusi_customer_sls').html(data.data_complaint.solusi_customer);
        $('#edit_solusi_internal_sls').html(data.data_complaint.solusi_internal);
        $('#edit_list_komitmen_sls_table').DataTable().destroy();
        load_edit_komitmen_sls(complaint);
        if(data.data_complaint.lampiran == null){
          $('#lihat_lampiran_sls').html('No Lampiran');
          $('#lihat_lampiran_sls').addClass('disabled');
          $('#lihat_lampiran_sls').attr('href', '#');
        }else{
          $('#lihat_lampiran_sls').html('Lihat Lampiran');
          $('#lihat_lampiran_sls').removeClass('disabled');
          $('#lihat_lampiran_sls').attr('href', '../data_file/' + data.lampiran);
        }
      })
    });

    $('body').on('click', '#save_new_edit_komitmen_sls', function () {
      var selesai_tanggal_komitmen = $('#edit_selesai_tanggal_komitmen_sls').val();
      var komitmen = $('#edit_komitmen_sls').val();
      var nomor_complaint = $('#edit_nomor_complaint_sls').val();

      $.ajax({
        type: "GET",
        url: "{{ url('save_new_edit_komitmen_sls') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint },
        success: function (data) {
          $('#edit_selesai_tanggal_komitmen_sls').val('');
          $('#edit_komitmen_sls').val('');
          var oTable = $('#edit_list_komitmen_sls_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {  
          var oTable = $('#edit_list_komitmen_sls_table').dataTable();
          oTable.fnDraw(false);
          console.log('Error:', data);
          alert("Isi Tanggal dan Komitmen, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
        }
      });
    });

    $('body').on('click', '#edit-komitmen', function () {
      var no_complaint = $(this).data("id");
      var divisi = $(this).data("divisi");
      var selesai_tanggal_komitmen = $(this).data("tanggal");
      var komitmen = $(this).data("komitmen");
      var url = "{{ url('complaint_komitmen/show/no_complaint/divisi/selesai_tanggal_komitmen/komitmen_txt') }}";
      url = url.replace('no_complaint', enc(no_complaint.toString()));
      url = url.replace('divisi', enc(divisi.toString()));
      url = url.replace('selesai_tanggal_komitmen', enc(selesai_tanggal_komitmen.toString()));
      url = url.replace('komitmen_txt', enc(komitmen.toString()));
      if(divisi == 3){
        $('#edit_komitmen_sls').val('');
        $('#edit_selesai_tanggal_komitmen_sls').val('');
        $('#edit_komitmen_lama_sls').val('');
        $('#edit_selesai_tanggal_komitmen_lama_sls').val('');
        $('#save_edit_komitmen_sls').show();
        $('#save_new_edit_komitmen_sls').hide();
      }else if(divisi == 4){
        $('#edit_komitmen_lny').val('');
        $('#edit_selesai_tanggal_komitmen_lny').val('');
        $('#edit_komitmen_lama_lny').val('');
        $('#edit_selesai_tanggal_komitmen_lama_lny').val('');
        $('#save_edit_komitmen_lny').show();
        $('#save_new_edit_komitmen_lny').hide();
      }
      $.get(url, function (data) {
        if(data.divisi == 3){
          $('#edit_selesai_tanggal_komitmen_sls').val(data.selesai_tanggal_komitmen);
          $('#edit_komitmen_sls').val(data.komitmen);
          $('#edit_selesai_tanggal_komitmen_lama_sls').val(data.selesai_tanggal_komitmen);
          $('#edit_komitmen_lama_sls').val(data.komitmen);
        }else if(data.divisi == 4){
          $('#edit_selesai_tanggal_komitmen_lny').val(data.selesai_tanggal_komitmen);
          $('#edit_komitmen_lny').val(data.komitmen);
          $('#edit_selesai_tanggal_komitmen_lama_lny').val(data.selesai_tanggal_komitmen);
          $('#edit_komitmen_lama_lny').val(data.komitmen);
        }
      })
    });

    $('body').on('click', '#save_edit_komitmen_sls', function () {
      var selesai_tanggal_komitmen = $('#edit_selesai_tanggal_komitmen_sls').val();
      var komitmen = $('#edit_komitmen_sls').val();
      var selesai_tanggal_komitmen_lama = $('#edit_selesai_tanggal_komitmen_lama_sls').val();
      var komitmen_lama = $('#edit_komitmen_lama_sls').val();
      var nomor_complaint = $('#edit_nomor_complaint_sls').val();
      $.ajax({
        type: "GET",
        url: "{{ url('update_edit_komitmen_sls') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint, 'selesai_tanggal_komitmen_lama' : selesai_tanggal_komitmen_lama, 'komitmen_lama' : komitmen_lama },
        success: function (data) {
          $('#edit_komitmen_sls').val('');
          $('#edit_selesai_tanggal_komitmen_sls').val('');
          $('#edit_komitmen_lama_sls').val('');
          $('#edit_selesai_tanggal_komitmen_lama_sls').val('');
          $('#save_edit_komitmen_sls').hide();
          $('#save_new_edit_komitmen_sls').show();
          var oTable = $('#edit_list_komitmen_sls_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {
          var oTable = $('#edit_list_komitmen_sls_table').dataTable();
          oTable.fnDraw(false);
          console.log('Error:', data);
          alert("Isi Tanggal dan Komitmen, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
        }
      });
    });

    $('body').on('click', '#edit-delete-komitmen', function () {
      var nomor_complaint = $(this).data("id");
      var divisi = $(this).data("divisi");
      var selesai_tanggal_komitmen = $(this).data("tanggal");
      var komitmen = $(this).data("komitmen");

      if(confirm("Delete this data?")){
        $.ajax({
          type: "GET",
          url: "{{ url('delete_edit_data_komitmen') }}",
          data: { 'nomor_complaint' : nomor_complaint, 'divisi' : divisi, 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen },
          success: function (data) {
            var oTable = $('#edit_list_komitmen_sls_table').dataTable(); 
            oTable.fnDraw(false);
            var oTableb = $('#edit_list_komitmen_lny_table').dataTable(); 
            oTableb.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#edit_complaint_lainnya', function () {
      var complaint = $(this).data('id');
      var custid_lny = $(this).data('custid');
      $('#edit_nomor_complaint_lny').val(complaint);
      $('#edit_custid_lny').val(custid_lny);
      var url = "{{ url('complaint_lainnya/show/edit/no_complaint') }}";
      url = url.replace('no_complaint', enc(complaint.toString()));
      $('#edit_divisi_lny').val('');
      $('#edit_upload_file_lny').html('');
      $('#edit_evaluasi_lny').html('');
      $('#edit_solusi_customer_lny').html('');
      $('#edit_solusi_internal_lny').html('');
      $('#edit_komitmen_lny').html('');
      $('#edit_selesai_tanggal_komitmen_lny').html('');
      $.get(url, function (data) {
        $('#edit_divisi_lny').val(data.data_complaint.divisi);
        $('#edit_evaluasi_lny').html(data.data_complaint.evaluasi);
        $('#edit_solusi_customer_lny').html(data.data_complaint.solusi_customer);
        $('#edit_solusi_internal_lny').html(data.data_complaint.solusi_internal);
        $('#edit_list_komitmen_lny_table').DataTable().destroy();
        load_edit_komitmen_lny(complaint);
        if(data.data_complaint.lampiran == null){
          $('#lihat_lampiran_lny').html('No Lampiran');
          $('#lihat_lampiran_lny').addClass('disabled');
          $('#lihat_lampiran_lny').attr('href', '#');
        }else{
          $('#lihat_lampiran_lny').html('Lihat Lampiran');
          $('#lihat_lampiran_lny').removeClass('disabled');
          $('#lihat_lampiran_lny').attr('href', '../data_file/' + data.lampiran);
        }
      })
    });

    $('body').on('click', '#save_new_edit_komitmen_lny', function () {
      var selesai_tanggal_komitmen = $('#edit_selesai_tanggal_komitmen_lny').val();
      var komitmen = $('#edit_komitmen_lny').val();
      var nomor_complaint = $('#edit_nomor_complaint_lny').val();

      $.ajax({
        type: "GET",
        url: "{{ url('save_new_edit_komitmen_lny') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint },
        success: function (data) {
          $('#edit_selesai_tanggal_komitmen_lny').val('');
          $('#edit_komitmen_lny').val('');
          var oTable = $('#edit_list_komitmen_lny_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {  
          var oTable = $('#edit_list_komitmen_lny_table').dataTable();
          oTable.fnDraw(false);
          console.log('Error:', data);
          alert("Isi Tanggal dan Komitmen, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
        }
      });
    });

    $('body').on('click', '#save_edit_komitmen_lny', function () {
      var selesai_tanggal_komitmen = $('#edit_selesai_tanggal_komitmen_lny').val();
      var komitmen = $('#edit_komitmen_lny').val();
      var selesai_tanggal_komitmen_lama = $('#edit_selesai_tanggal_komitmen_lama_lny').val();
      var komitmen_lama = $('#edit_komitmen_lama_lny').val();
      var nomor_complaint = $('#edit_nomor_complaint_lny').val();
      $.ajax({
        type: "GET",
        url: "{{ url('update_edit_komitmen_lny') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint, 'selesai_tanggal_komitmen_lama' : selesai_tanggal_komitmen_lama, 'komitmen_lama' : komitmen_lama },
        success: function (data) {
          $('#edit_komitmen_lny').val('');
          $('#edit_selesai_tanggal_komitmen_lny').val('');
          $('#edit_komitmen_lama_lny').val('');
          $('#edit_selesai_tanggal_komitmen_lama_lny').val('');
          $('#save_edit_komitmen_lny').hide();
          $('#save_new_edit_komitmen_lny').show();
          var oTable = $('#edit_list_komitmen_lny_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {
          var oTable = $('#edit_list_komitmen_lny_table').dataTable();
          oTable.fnDraw(false);
          console.log('Error:', data);
          alert("Isi Tanggal dan Komitmen, Tidak Boleh Ada Yang Kosong atau Terjadi Error.");
        }
      });
    });

    $('body').on('click', '#tolak_complaint', function () {
      var nomor_complaint = $(this).data("id");
      if(confirm("Complaint Ditolak dan Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint/reject/') }}",
          data: { 'nomor_complaint' : nomor_complaint },
          success: function (data) {
            var oTablea = $('#konfirmasi_complaint_table').dataTable();
            oTablea.fnDraw(false);
            alert("Data Rejected Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#rollback_complaint_produksi', function () {
      var nomor_complaint = $(this).data("id");
      if(confirm("Complaint Dirollback?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint_produksi/rollback/') }}",
          data: { 'nomor_complaint' : nomor_complaint },
          success: function (data) {
            var oTablea = $('#complaint_produksi_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_produksi_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_produksi_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_produksi_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_produksi_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_produksi_diproses_table').dataTable();
            oTablef.fnDraw(false);
            alert("Rollback Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#rollback_complaint_logistik', function () {
      var nomor_complaint = $(this).data("id");
      if(confirm("Complaint Dirollback?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint_logistik/rollback/') }}",
          data: { 'nomor_complaint' : nomor_complaint },
          success: function (data) {
            var oTablea = $('#complaint_logistik_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_logistik_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_logistik_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_logistik_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_logistik_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_logistik_diproses_table').dataTable();
            oTablef.fnDraw(false);
            alert("Rollback Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#rollback_complaint_sales', function () {
      var nomor_complaint = $(this).data("id");
      if(confirm("Complaint Dirollback?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint_sales/rollback/') }}",
          data: { 'nomor_complaint' : nomor_complaint },
          success: function (data) {
            var oTablea = $('#complaint_sales_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_sales_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_sales_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_sales_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_sales_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_sales_diproses_table').dataTable();
            oTablef.fnDraw(false);
            alert("Rollback Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#rollback_complaint_lainnya', function () {
      var nomor_complaint = $(this).data("id");
      if(confirm("Complaint Dirollback?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint_lainnya/rollback/') }}",
          data: { 'nomor_complaint' : nomor_complaint },
          success: function (data) {
            var oTablea = $('#complaint_lainnya_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_lainnya_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_lainnya_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_lainnya_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_lainnya_diproses_table').dataTable();
            oTablef.fnDraw(false);
            alert("Rollback Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#rollback_validate_complaint', function () {
      var nomor_complaint = $(this).data("id");
      if(confirm("Validasi Complaint Dirollback?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint/rollback/validasi') }}",
          data: { 'nomor_complaint' : nomor_complaint },
          success: function (data) {
            var oTablea = $('#complaint_produksi_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_produksi_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_produksi_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_produksi_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_produksi_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_produksi_diproses_table').dataTable();
            oTablef.fnDraw(false);
            var oTableaa = $('#complaint_logistik_semua_table').dataTable();
            oTableaa.fnDraw(false);
            var oTablebb = $('#complaint_logistik_proses_table').dataTable();
            oTablebb.fnDraw(false);
            var oTablecc = $('#complaint_logistik_valid_table').dataTable();
            oTablecc.fnDraw(false);
            var oTabledd = $('#complaint_logistik_selesai_table').dataTable();
            oTabledd.fnDraw(false);
            var oTableee = $('#complaint_logistik_semua_admin_table').dataTable();
            oTableee.fnDraw(false);
            var oTableff = $('#complaint_logistik_diproses_table').dataTable();
            oTableff.fnDraw(false);
            var oTableaaa = $('#complaint_sales_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTablebbb = $('#complaint_sales_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTableccc = $('#complaint_sales_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTableddd = $('#complaint_sales_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTableeee = $('#complaint_sales_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablefff = $('#complaint_sales_diproses_table').dataTable();
            oTablef.fnDraw(false);
            var oTableaaaa = $('#complaint_lainnya_semua_table').dataTable();
            oTableaaaa.fnDraw(false);
            var oTablebbbb = $('#complaint_lainnya_proses_table').dataTable();
            oTablebbbb.fnDraw(false);
            var oTablecccc = $('#complaint_lainnya_valid_table').dataTable();
            oTablecccc.fnDraw(false);
            var oTabledddd = $('#complaint_lainnya_selesai_table').dataTable();
            oTabledddd.fnDraw(false);
            var oTableeeee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTableeeee.fnDraw(false);
            var oTableffff = $('#complaint_lainnya_diproses_table').dataTable();
            oTableffff.fnDraw(false);
            alert("Rollback Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#validate_complaint_sales', function () {
      var nomor_complaint = $(this).data("id");
      $('#val_nomor_complaint').val(nomor_complaint);
      $('#list_of_action_table').DataTable().destroy();
      load_data_komitmen(nomor_complaint);
    });

    $('body').on('click', '#btn-logbook', function () {
      var nomor_complaint = $(this).data("id");
      $('#title_modal_logbook').html("Logbook Complaint No " + nomor_complaint);
      $('#logbook_complaint_table').DataTable().destroy();
      load_logbook(nomor_complaint);
    });

    $('body').on('click', '#validate_complaint', function () {
      var nomor_complaint = $('#val_nomor_complaint').val();
      if(confirm("Data Ini Akan Diubah Status Menjadi Done?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint/validasi/') }}",
          data: { 'nomor_complaint' : nomor_complaint },
          success: function (data) {
            var oTableaaa = $('#complaint_sales_semua_table').dataTable();
            oTableaaa.fnDraw(false);
            var oTablebbb = $('#complaint_sales_proses_table').dataTable();
            oTablebbb.fnDraw(false);
            var oTableccc = $('#complaint_sales_valid_table').dataTable();
            oTableccc.fnDraw(false);
            var oTableddd = $('#complaint_sales_selesai_table').dataTable();
            oTableddd.fnDraw(false);
            var oTableeee = $('#complaint_sales_semua_admin_table').dataTable();
            oTableeee.fnDraw(false);
            var oTablefff = $('#complaint_sales_diproses_table').dataTable();
            oTablefff.fnDraw(false);
            var oTableaaaa = $('#complaint_lainnya_semua_table').dataTable();
            oTableaaaa.fnDraw(false);
            var oTablebbbb = $('#complaint_lainnya_proses_table').dataTable();
            oTablebbbb.fnDraw(false);
            var oTablecccc = $('#complaint_lainnya_valid_table').dataTable();
            oTablecccc.fnDraw(false);
            var oTabledddd = $('#complaint_lainnya_selesai_table').dataTable();
            oTabledddd.fnDraw(false);
            var oTableeeee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTableeeee.fnDraw(false);
            var oTableffff = $('#complaint_lainnya_diproses_table').dataTable();
            oTableffff.fnDraw(false);
            $("#modal_validasi_complaint").modal('hide');
            $("#modal_validasi_complaint").trigger('click');
            alert("Data Validated Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#validate_complaint_lainnya', function () {
      var nomor_complaint = $(this).data("id");
      $('#val_nomor_complaint').val(nomor_complaint);
      $('#list_of_action_table').DataTable().destroy();
      load_data_komitmen(nomor_complaint);
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    $('#alasantidakterlibatform').validate({
      rules: {
        nomor_complaint_alasan: {
          required: true,
        },
      },
      messages: {
        nomor_complaint_alasan: {
          required: "Nomor Complaint is required",
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
        var myform = document.getElementById("alasantidakterlibatform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url: "{{ url('complaint_lainnya/tidak_terlibat') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success: function (data) {
            $('#alasantidakterlibatform').trigger("reset");
            var oTablea = $('#complaint_lainnya_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_lainnya_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_lainnya_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_lainnya_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_lainnya_diproses_table').dataTable();
            oTablef.fnDraw(false);
            $('#modal_alasan_tidak_terlibat').modal('hide');
            $("#modal_alasan_tidak_terlibat").trigger('click');
            alert("Data Changed Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#alasantidakterlibatform').trigger("reset");
            var oTablea = $('#complaint_lainnya_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_lainnya_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_lainnya_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_lainnya_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            var oTablef = $('#complaint_lainnya_diproses_table').dataTable();
            oTablef.fnDraw(false);
            $('#modal_alasan_tidak_terlibat').modal('hide');
            $("#modal_alasan_tidak_terlibat").trigger('click');
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#salesform').validate({
      rules: {
        nomor_complaint_sls: {
          required: true,
        },
        custid_sls: {
          required: true,
        },
        tanggal_customer: {
          required: true,
        }, 
        solusi_customer_sls: {
          required: true,
        },
        upload_file_sls: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        nomor_complaint_sls: {
          required: "Nomor Complaint is required",
        },
        custid_sls: {
          required: "Custid is required",
        },
        tanggal_customer: {
          required: "Tanggal Customer Menyetujui is required",
        },  
        solusi_customer_sls: {
          required: "Solusi Customer is required",
        },
        upload_file_sls: {
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
        var myform = document.getElementById("salesform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/complaint_sales/store') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#salesform').trigger("reset");
            var oTablea = $('#complaint_sales_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_sales_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_sales_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_sales_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_sales_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            $("#modal_proses_complaint_sales").modal('hide');
            $("#modal_proses_complaint_sales").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#salesform').trigger("reset");
            var oTablea = $('#complaint_sales_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_sales_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_sales_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_sales_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_sales_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            $("#modal_proses_complaint_sales").modal('hide');
            $("#modal_proses_complaint_sales").trigger('click');
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#editsalesform').validate({
      rules: {
        edit_nomor_complaint_sls: {
          required: true,
        },
        edit_custid_sls: {
          required: true,
        },
        edit_tanggal_customer: {
          required: true,
        },
        edit_solusi_customer_sls: {
          required: true,
        },
        edit_upload_file_sls: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        edit_nomor_complaint_sls: {
          required: "Nomor Complaint is required",
        },
        edit_custid_sls: {
          required: "Custid is required",
        },
        edit_tanggal_customer: {
          required: "Tanggal Customer Menyetujui is required",
        },  
        edit_solusi_customer_sls: {
          required: "Solusi Customer is required",
        },
        edit_upload_file_sls: {
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
        var myform = document.getElementById("editsalesform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/complaint_sales/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#editsalesform').trigger("reset");
            var oTablea = $('#complaint_sales_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_sales_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_sales_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_sales_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_sales_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            $("#modal_edit_complaint_sales").modal('hide');
            $("#modal_edit_complaint_sales").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#editsalesform').trigger("reset");
            var oTablea = $('#complaint_sales_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_sales_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_sales_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_sales_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_sales_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            $("#modal_edit_complaint_sales").modal('hide');
            $("#modal_edit_complaint_sales").trigger('click');
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#lainnyaform').validate({
      rules: {
        nomor_complaint_lny: {
          required: true,
        },
        custid_lny: {
          required: true,
        },
        divisi_lny: {
          required: true,
        },  
        solusi_internal_lny: {
          required: true,
        },
        upload_file_lny: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        nomor_complaint_lny: {
          required: "Nomor Complaint is required",
        },
        custid_lny: {
          required: "Custid is required",
        },
        divisi_lny: {
          required: "Divisi is required",
        },  
        solusi_internal_lny: {
          required: "Solusi Internal is required",
        },
        upload_file_lny: {
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
        var myform = document.getElementById("lainnyaform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/complaint_lainnya/store') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#lainnyaform').trigger("reset");
            var oTablea = $('#complaint_lainnya_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_lainnya_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_lainnya_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_lainnya_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            $("#modal_proses_complaint_lainnya").modal('hide');
            $("#modal_proses_complaint_lainnya").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#lainnyaform').trigger("reset");
            var oTablea = $('#complaint_lainnya_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_lainnya_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_lainnya_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_lainnya_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            $("#modal_proses_complaint_lainnya").modal('hide');
            $("#modal_proses_complaint_lainnya").trigger('click');
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#editlainnyaform').validate({
      rules: {
        edit_nomor_complaint_lny: {
          required: true,
        },
        edit_custid_lny: {
          required: true,
        },
        edit_divisi_lny: {
          required: true,
        },  
        edit_solusi_internal_lny: {
          required: true,
        },  
        edit_upload_file_lny: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        edit_nomor_complaint_lny: {
          required: "Nomor Complaint is required",
        },
        edit_custid_lny: {
          required: "Custid is required",
        },
        edit_divisi_lny: {
          required: "Divisi is required",
        },  
        edit_solusi_internal_lny: {
          required: "Solusi Internal is required",
        },  
        edit_upload_file_lny: {
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
        var myform = document.getElementById("editlainnyaform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/complaint_lainnya/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#editlainnyaform').trigger("reset");
            var oTablea = $('#complaint_lainnya_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_lainnya_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_lainnya_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_lainnya_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            $("#modal_edit_complaint_lainnya").modal('hide');
            $("#modal_edit_complaint_lainnya").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#editlainnyaform').trigger("reset");
            var oTablea = $('#complaint_lainnya_semua_table').dataTable();
            oTablea.fnDraw(false);
            var oTableb = $('#complaint_lainnya_proses_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#complaint_lainnya_valid_table').dataTable();
            oTablec.fnDraw(false);
            var oTabled = $('#complaint_lainnya_selesai_table').dataTable();
            oTabled.fnDraw(false);
            var oTablee = $('#complaint_lainnya_semua_admin_table').dataTable();
            oTablee.fnDraw(false);
            $("#modal_edit_complaint_lainnya").modal('hide');
            $("#modal_edit_complaint_lainnya").trigger('click');
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#konfirmasiform').validate({
      rules: {
        nomor_complaint_konf: {
          required: true,
        },
      },
      messages: {
        nomor_complaint_konf: {
          required: "Nomor Complaint is required",
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
        var myform = document.getElementById("konfirmasiform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/konfirmasi_complaint/store') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#konfirmasiform').trigger("reset");
            var oTable = $('#konfirmasi_complaint_table').dataTable();
            oTable.fnDraw(false);
            $('#modal_konfirmasi_complaint').modal('hide');
            $("#modal_konfirmasi_complaint").trigger('click');
            alert("Division Successfully Changed");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#konfirmasiform').trigger("reset");
            var oTable = $('#konfirmasi_complaint_table').dataTable();
            oTable.fnDraw(false);
            $('#modal_konfirmasi_complaint').modal('hide');
            $("#modal_konfirmasi_complaint").trigger('click');
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
      dropdownParent: $('#modal_input_complaint .modal-content'),
      placeholder: 'Customer',
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

    $('.customer-edit').select2({
      dropdownParent: $('#modal_edit_complaint .modal-content'),
      placeholder: 'Customer',
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

<script>
  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>

<script type="text/javascript">
  $(".customer").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".customer").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
  $(".customer-edit").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search Customer Here...");
  });
  $(".customer-edit").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
@endsection
