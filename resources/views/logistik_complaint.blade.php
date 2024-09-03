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
  #save_input_komitmen_log, #save_new_edit_komitmen_log, #save_edit_komitmen_log{
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
    .lihat-table {
      overflow-x: auto;
    }
    #save_input_komitmen_log, #save_new_edit_komitmen_log, #save_edit_komitmen_log{
      position: unset;  
      width: 100%;  
    }
    .save-btn-in {
      width: 100%;
    }
    .radio-control {
      padding-left: 0 !important;
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
            <li class="breadcrumb-item">Logistik</li>
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
            <div class="col-4">
              <button type="button" name="btn_list_komitmen" id="btn_list_komitmen" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_list_komitmen">List of Actions</button>
            </div>
          </div>
        </div>
        <div class="card-body" id="complaint_logistik">
          <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <?php
              if(Session::get('login_admin')){
                if(Session::get('tipe_user') == 1){
            ?>
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#semua_logistik_admin" role="tab" aria-controls="custom-content-home-profile" aria-selected="true">Semua</a>
            </li>
            <?php
              }else{
            ?>
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#semua_logistik" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Semua</a>
            </li>
            <?php
              }
            }
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#proses_logistik" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Proses</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#diproses_logistik" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Sedang Diproses</a>
            </li>
            <?php
              if(Session::get('login_admin')){
                if(Session::get('tipe_user') == 1){
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#valid_logistik" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Validasi</a>
            </li>
            <?php
              }
            }
            ?>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#selesai_logistik" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Selesai</a>
            </li>
          </ul>
          <div class="tab-content" id="custom-content-below-tabContent">
           <?php
            if(Session::get('login_admin')){
              if(Session::get('tipe_user') == 1){
            ?>
           <div class="tab-pane fade show active" id="semua_logistik_admin" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="complaint_logistik_semua_admin_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
           <div class="tab-pane fade show active" id="semua_logistik" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="complaint_logistik_semua_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
           <div class="tab-pane fade" id="proses_logistik" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
             <table id="complaint_logistik_proses_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
           <div class="tab-pane fade" id="diproses_logistik" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
             <table id="complaint_logistik_diproses_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
           <div class="tab-pane fade" id="valid_logistik" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
             <table id="complaint_logistik_valid_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
           <div class="tab-pane fade" id="selesai_logistik" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
             <table id="complaint_logistik_selesai_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
        <table id="list_komitmen_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_proses_complaint_logistik">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Proses Complaint Logistik</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="logistikform" id="logistikform" action="javascript:void(0)" enctype="multipart/form-data">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="nomor_complaint_log" id="nomor_complaint_log" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="custid_log" id="custid_log" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="no_divisi_log" id="no_divisi_log" />
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
                        <input type="text" class="form-control" name="tanggal_order" id="tanggal_order" autocomplete="off" placeholder="Tanggal Order" readonly>
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="sales_order">Sales Yang Memesan</label>
                      <input class="form-control" type="text" name="sales_order" id="sales_order" placeholder="Sales Yang Memesan" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="supervisor_sls">Supervisor Sales</label>
                      <input class="form-control" type="text" name="supervisor_sls" id="supervisor_sls" placeholder="Supervisor Sales" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="pelapor">Pelapor</label>
                      <input class="form-control" type="text" name="pelapor" id="pelapor" placeholder="Pelapor" readonly/>
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
                        <input type="text" class="form-control" name="tanggal_pengiriman" id="tanggal_pengiriman" autocomplete="off" placeholder="Tanggal Pengiriman" readonly>
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="area_log">Area</label>
                      <select id="area_log" name="area_log" class="form-control" disabled>
                        <option value="" selected>Area</option>
                        <option value="1">Area 1</option>
                        <option value="2">Area 2</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="nama_supir">Nama Supir</label>
                      <input class="form-control" type="text" name="nama_supir" id="nama_supir" placeholder="Nama Supir" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="nama_kernet">Nama Kernet</label>
                      <input class="form-control" type="text" name="nama_kernet" id="nama_kernet" placeholder="Nama Kernet" readonly/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="nama_kuli">Nama Kuli</label>
                      <table class="table" id="dynamic_field_kuli" style="border: none;">  
                        <tr>  
                          <td><input type="text" name="nama_kuli[]" placeholder="Nama Kuli" class="form-control nama_kuli_list" readonly/></td>    
                        </tr>  
                      </table> 
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="nama_stapel">Nama Stapel</label>
                      <table class="table" id="dynamic_field_stapel" style="border: none;">  
                        <tr>  
                          <td><input type="text" name="nama_stapel[]" placeholder="Nama Stapel" class="form-control nama_stapel_list" readonly/></td>  
                        </tr>  
                      </table> 
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label for="no_kendaraan">No Kendaraan</label>
                      <input type="text" class="form-control" id="no_kendaraan" name="no_kendaraan" placeholder="No Kendaraan" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="pengiriman">Pengiriman</label>
                      <select id="pengiriman" name="pengiriman" class="form-control" disabled>
                        <option value="" selected>Pengiriman</option>
                        <option value="1">DSGM</option>
                        <option value="2">Ambil Sendiri</option>
                        <option value="3">Ekspedisi</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="jenis_kendaraan">Jenis Kendaraan</label>
                      <select id="jenis_kendaraan" name="jenis_kendaraan" class="form-control" disabled>
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
                      <label for="supervisor_log">Supervisor</label>
                      <input class="form-control" type="text" name="supervisor_log" id="supervisor_log" placeholder="Supervisor" readonly/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3"></div>
                  <div class="col-3">
                    <div class="form-group" id="divpengiriman">
                      <input type="text" class="form-control" id="pengiriman_lain" name="pengiriman_lain" placeholder="Pengiriman Lainnya" style="display: none;" readonly>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group" id="divjeniskendaraan">
                      <input type="text" class="form-control" id="jenis_kendaraan_lain" name="jenis_kendaraan_lain" placeholder="Jenis Kendaraan Lainnya" style="display: none;" readonly>
                    </div>
                  </div>
                  <div class="col-3"></div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label for="jumlah_karung">Jumlah Karung</label>
                      <input class="form-control" type="text" name="jumlah_karung" id="jumlah_karung" placeholder="Jumlah Karung" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="quantity">Quantity</label>
                      <input class="form-control" type="text" name="quantity" id="quantity" placeholder="Quantity" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="jumlah_kg_sak">&nbsp</label>
                      <select id="jumlah_kg_sak" name="jumlah_kg_sak" class="form-control" disabled>
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
                      <select id="jenis_karung" name="jenis_karung" class="form-control" disabled>
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
                      <input class="form-control" type="text" name="berat_timbangan" id="berat_timbangan" placeholder="Berat Timbangan" readonly/>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="unit_berat_timbangan">&nbsp</label>
                      <select id="unit_berat_timbangan" name="unit_berat_timbangan" class="form-control" disabled>
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
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="evaluasi_log">Evaluasi</label>
              <textarea class="form-control" rows="3" name="evaluasi_log" id="evaluasi_log" placeholder="Evaluasi"></textarea>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="solusi_internal_log">Solusi Internal</label>
              <textarea class="form-control" rows="3" name="solusi_internal_log" id="solusi_internal_log" placeholder="Solusi Internal"></textarea>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label for="upload_file_log">Lampiran</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="upload_file_log" name="upload_file_log">
                  <label class="custom-file-label" for="upload_file_log">Choose file</label>
                </div>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
          </div>
          <div class="col-6" style="display: none;">
            <div class="form-group">
              <label for="solusi_customer_log">Solusi Customer</label>
              <textarea class="form-control" rows="5" name="solusi_customer_log" id="solusi_customer_log" placeholder="Solusi Customer"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-5">
            <div class="form-group">
              <label for="komitmen_log">Tindakan / Komitmen</label>
              <textarea class="form-control" rows="3" name="komitmen_log" id="komitmen_log" placeholder="Tindakan / Komitmen"></textarea>
            </div>
          </div>
          <div class="col-5">
            <div class="form-group">
              <label>Finish Date Komitmen</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="selesai_tanggal_komitmen_log" id="selesai_tanggal_komitmen_log" autocomplete="off" placeholder="Selesai Tanggal">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
              <label>&nbsp</label>
              <button type="button" class="btn btn-success save-btn-in" id="save_input_komitmen_log">Save</button>
            </div>
          </div>
        </div>
        <table id="list_komitmen_log_table" style="width: 100%;" class="table table-bordered table-hover responsive">
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
        <button type="button" class="btn btn-danger" id="btn_tidak_terlibat_log" data-dismiss="modal" data-toggle="modal" data-target="#modal_alasan_tidak_terlibat">Tidak Terlibat</button>
        <button type="button" class="btn btn-success" id="btn_terlibat_log" style="display: none;">Terlibat</button>
        <button type="submit" class="btn btn-primary" id="btn_save_log">Save</button>
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
        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_proses_complaint_logistik">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_edit_complaint_logistik">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Complaint Logistik</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" class="editlogistikform" id="editlogistikform" action="javascript:void(0)" enctype="multipart/form-data">
          {{ csrf_field() }}
      <div class="modal-body">
        <div class="form-group">
          <input class="form-control" type="hidden" name="edit_nomor_complaint_log" id="edit_nomor_complaint_log" />
        </div>
        <div class="form-group">
          <input class="form-control" type="hidden" name="edit_custid_log" id="edit_custid_log" />
        </div>
        <div class="row">
          <div class="col-5">
            <div class="form-group">
              <label for="edit_upload_file_log">Ubah Lampiran</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="edit_upload_file_log" name="edit_upload_file_log">
                  <label class="custom-file-label" for="edit_upload_file_log">Choose file</label>
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
                <a target="_blank" id="lihat_lampiran_log" class="btn btn-primary save-btn-in">Lihat Lampiran</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="edit_evaluasi_log">Evaluasi</label>
              <textarea class="form-control" rows="3" name="edit_evaluasi_log" id="edit_evaluasi_log" placeholder="Evaluasi"></textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="edit_solusi_internal_log">Solusi Internal</label>
              <textarea class="form-control" rows="3" name="edit_solusi_internal_log" id="edit_solusi_internal_log" placeholder="Solusi Internal"></textarea>
            </div>
          </div>
        </div>
        <div class="row" id="div_edit_komitmen_log">
          <div class="col-5">
            <div class="form-group">
              <label for="edit_komitmen_log">Tindakan / Komitmen</label>
              <textarea class="form-control" rows="3" name="edit_komitmen_log" id="edit_komitmen_log" placeholder="Tindakan / Komitmen"></textarea>
            </div>
          </div>
          <div class="col-5">
            <div class="form-group">
              <label>Finish Date Komitmen</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" name="edit_selesai_tanggal_komitmen_log" id="edit_selesai_tanggal_komitmen_log" autocomplete="off" placeholder="Finish Date Komitmen">
              </div>
              <!-- /.input group -->
            </div>
          </div>
          <textarea class="form-control" name="edit_komitmen_lama_log" id="edit_komitmen_lama_log" style="display: none;"></textarea>
          <input type="hidden" class="form-control" name="edit_selesai_tanggal_komitmen_lama_log" id="edit_selesai_tanggal_komitmen_lama_log" autocomplete="off">
          <div class="col-2">
            <div class="form-group">
              <label>&nbsp</label>
              <button type="button" class="btn btn-success save-btn-in" id="save_new_edit_komitmen_log">Save</button>
              <button type="button" class="btn btn-success save-btn-in" id="save_edit_komitmen_log" style="display: none;">Edit</button>
            </div>
          </div>
        </div>
        <table id="edit_list_komitmen_log_table" style="width: 100%;" class="table table-bordered table-hover responsive">
          <thead>
            <tr>
              <th>Tanggal Selesai</th>
              <th>Komitmen</th>
              <th></th>
            </tr>
          </thead>
        </table>
        <div class="row">
          <div class="col-6" style="display: none;">
            <div class="form-group">
              <label for="edit_solusi_customer_log">Solusi Customer</label>
              <textarea class="form-control" rows="3" name="edit_solusi_customer_log" id="edit_solusi_customer_log" placeholder="Solusi Customer"></textarea>
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

      var table = $('#complaint_logistik_semua_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/logistik/semua") }}',
        data : function( d ) {
          d.nomor = any_nomor;
        }
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

      if(any_nomor != '' && type == 5){
        table.ajax.url('{{ url("complaint_logistik/specific") }}').load();
      }

      if(tipe_user == 1){
        $('#complaint_logistik_semua_admin_table').DataTable().destroy();
        load_data_logistik_semua_admin();
      }
      $('.nav-tabs a').on('shown.bs.tab', function (e) {
        target = $(e.target).attr("href");
        if(target == '#semua_logistik'){
          $('#complaint_logistik_semua_table').DataTable().destroy();
          load_data_logistik_semua();
        }else if(target == '#semua_logistik_admin'){
          $('#complaint_logistik_semua_admin_table').DataTable().destroy();
          load_data_logistik_semua_admin();
        }else if(target == '#proses_logistik'){
          $('#complaint_logistik_proses_table').DataTable().destroy();
          load_data_logistik_proses();
        }else if(target == '#diproses_logistik'){
          $('#complaint_logistik_diproses_table').DataTable().destroy();
          load_data_logistik_diproses();
        }else if(target == '#valid_logistik'){
          $('#complaint_logistik_valid_table').DataTable().destroy();
          load_data_logistik_valid();
        }else if(target == '#selesai_logistik'){
          $('#complaint_logistik_selesai_table').DataTable().destroy();
          load_data_logistik_selesai();
        }
      });

    function load_data_komitmen()
      {
        table = $('#list_komitmen_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("logistik_complaint/list_komitmen") }}',
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

    function load_data_logistik_semua(from_date = '', to_date = '')
     {
      table = $('#complaint_logistik_semua_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/logistik/semua") }}',
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

     function load_data_logistik_semua_admin(from_date = '', to_date = '')
     {
      table = $('#complaint_logistik_semua_admin_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/logistik/semua_admin") }}',
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

     function load_data_logistik_proses(from_date = '', to_date = '')
     {
      table = $('#complaint_logistik_proses_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/logistik/proses") }}',
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

     function load_data_logistik_diproses(from_date = '', to_date = '')
     {
      table = $('#complaint_logistik_diproses_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/logistik/diproses") }}',
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

     function load_data_logistik_valid(from_date = '', to_date = '')
     {
      table = $('#complaint_logistik_valid_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/logistik/valid") }}',
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

     function load_data_logistik_selesai(from_date = '', to_date = '')
     {
      table = $('#complaint_logistik_selesai_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("complaint/logistik/selesai") }}',
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

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if(tipe_user == 1){
          $('#complaint_logistik_semua_admin_table').DataTable().destroy();
          load_data_logistik_semua_admin(from_date, to_date);
        }else{
          $('#complaint_logistik_semua_table').DataTable().destroy();
          load_data_logistik_semua(from_date, to_date);
        }
        if(target == '#semua_logistik'){
          $('#complaint_logistik_semua_table').DataTable().destroy();
          load_data_logistik_semua(from_date, to_date);
        }else if(target == '#semua_logistik_admin'){
          $('#complaint_logistik_semua_admin_table').DataTable().destroy();
          load_data_logistik_semua_admin(from_date, to_date);
        }else if(target == '#proses_logistik'){
          $('#complaint_logistik_proses_table').DataTable().destroy();
          load_data_logistik_proses(from_date, to_date);
        }else if(target == '#diproses_logistik'){
          $('#complaint_logistik_diproses_table').DataTable().destroy();
          load_data_logistik_diproses(from_date, to_date);
        }else if(target == '#valid_logistik'){
          $('#complaint_logistik_valid_table').DataTable().destroy();
          load_data_logistik_valid(from_date, to_date);
        }else if(target == '#selesai_logistik'){
          $('#complaint_logistik_selesai_table').DataTable().destroy();
          load_data_logistik_selesai(from_date, to_date);
        }
      }
      else
      {
        alert('Both Date is required');
      }  
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if(tipe_user == 1){
        $('#complaint_logistik_semua_admin_table').DataTable().destroy();
        load_data_logistik_semua_admin();
      }else{
        $('#complaint_logistik_semua_table').DataTable().destroy();
        load_data_logistik_semua();
      }
      if(target == '#semua_logistik'){
        $('#complaint_logistik_semua_table').DataTable().destroy();
        load_data_logistik_semua();
      }else if(target == '#semua_logistik_admin'){
        $('#complaint_logistik_semua_admin_table').DataTable().destroy();
        load_data_logistik_semua_admin();
      }else if(target == '#proses_logistik'){
        $('#complaint_logistik_proses_table').DataTable().destroy();
        load_data_logistik_proses();
      }else if(target == '#diproses_logistik'){
        $('#complaint_logistik_diproses_table').DataTable().destroy();
        load_data_logistik_diproses();
      }else if(target == '#valid_logistik'){
        $('#complaint_logistik_valid_table').DataTable().destroy();
        load_data_logistik_valid();
      }else if(target == '#selesai_logistik'){
        $('#complaint_logistik_selesai_table').DataTable().destroy();
        load_data_logistik_selesai();
      }
    });

    $('body').on('click', '#btn_list_komitmen', function () {
      $('#list_komitmen_table').DataTable().destroy();
      load_data_komitmen();
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

    $('#selesai_tanggal_komitmen_log').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('#edit_selesai_tanggal_komitmen_log').flatpickr({
      allowInput: true,
      disableMobile: true
    });

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function(event){
    let key = "{{ env('MIX_APP_KEY') }}";

    var td_count = 0;
    var td_count_prd = 0;
    var td_count_log = 0;
    var td_count_sls = 0;
    var td_count_timbang = 0;
    var td_count_ware = 0;
    var td_count_lny = 0;
    var td_count_lab = 0;

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
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("validasi/list_komitmen") }}',
          data:{no_complaint:no_complaint},
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

      function load_komitmen_log(no_complaint = '')
      {
        table = $('#list_komitmen_log_table').DataTable({
         processing: true,
         serverSide: true,
         language: {
            emptyTable: "Tambahkan Data Komitmen"
         },
         ajax: {
          url:'{{ url("list_komitmen_temp/logistik") }}',
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

      function load_edit_komitmen_log(no_complaint = '')
      {
        table = $('#edit_list_komitmen_log_table').DataTable({
         processing: true,
         serverSide: true,
         language: {
            emptyTable: "Tambahkan Data Komitmen"
         },
         ajax: {
          url:'{{ url("edit_list_komitmen_logistik") }}',
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

    $('body').on('click', '#proses_complaint_logistik', function () {
      var complaint = $(this).data('id');
      var custid_prd = $(this).data('custid');
      var divisi = $(this).data('divisi');
      $('#nomor_complaint_log').val(complaint);
      $('#custid_log').val(custid_prd);
      $('#no_divisi_log').val(divisi);

      $('#list_komitmen_log_table').DataTable().destroy();
      load_komitmen_log(complaint);

      var url = "{{ url('logistik_proses/show/no_complaint') }}";
      url = url.replace('no_complaint', enc(complaint.toString()));
      $('#tanggal_pengiriman').val('');
      $('#tanggal_order').val('');
      $('#area_log').val('').trigger('change');
      $('#no_kendaraan').val('');
      $('#nama_supir').val('');
      $('#nama_kernet').val('');
      $('#pengiriman').val('').trigger('change');
      $('#jenis_kendaraan').val('').trigger('change');
      $('#supervisor_log').val('');
      $('#pengiriman_lain').val('');
      $('#jenis_kendaraan_lain').val('');
      $('#jumlah_karung').val('');
      $('#quantity').val('');
      $('#jumlah_kg_sak').val('').trigger('change');
      $('#jenis_karung').val('').trigger('change');
      $('#berat_timbangan').val('');
      $('#unit_berat_timbangan').val('').trigger('change');
      $('#berat_aktual').val('');
      $('#unit_berat_aktual').val('').trigger('change');
      $('#pengiriman_lain').hide();
      $('#jenis_kendaraan_lain').hide();
      $('#sales_order').val('');
      $('#supervisor_sls').val('');
      $('#pelapor').val('');
      $('[name="nama_kuli[]"]').val('');
      $('#row_kuli1').remove();
      $('#row_kuli2').remove();
      $('#row_kuli3').remove();
      $('[name="nama_stapel[]"]').val('');
      $('#row_stapel1').remove();
      $('#row_stapel2').remove();
      $('#row_stapel3').remove();
      $('#row_stapel4').remove();
      $('#row_stapel5').remove();
      $.get(url, function (data) {
        if(data.tanggal_order == '' || data.tanggal_order == null){
          $('#tanggal_order').val('');
        }else{
          $('#tanggal_order').val(data.tanggal_order);
        }
        if(data.tanggal_pengiriman == '' || data.tanggal_pengiriman == null){
          $('#tanggal_pengiriman').val('');
        }else{
          $('#tanggal_pengiriman').val(data.tanggal_pengiriman);
        }
        if(data.area == '' || data.area == null){
          $('#area_log').val('').trigger('change');
        }else{
          $('#area_log').val(data.area).trigger('change');
        }
        $('#sales_order').val(data.sales_order);
        $('#supervisor_sls').val(data.supervisor_sales);
        $('#pelapor').val(data.pelapor);
        $('#no_kendaraan').val(data.no_kendaraan);
        $('#nama_supir').val(data.nama_supir);
        $('#nama_kernet').val(data.nama_kernet);
        if(data.pengiriman == '' || data.pengiriman == null){
          $('#pengiriman').val('').trigger('change');
        }else{
          $('#pengiriman').val(data.pengiriman).trigger('change');
        }
        if(data.jenis_kendaraan == '' || data.jenis_kendaraan == null){
          $('#jenis_kendaraan').val('').trigger('change');
        }else{
          $('#jenis_kendaraan').val(data.jenis_kendaraan).trigger('change');
        }
        $('#supervisor_log').val(data.supervisor);
        if(data.pengiriman_lain == '' || data.pengiriman_lain == null){
          $('#pengiriman_lain').hide();
        }else{
          $('#pengiriman_lain').show();
          $('#pengiriman_lain').val(data.pengiriman_lain);
        }
        if(data.jenis_kendaraan_lain == '' || data.jenis_kendaraan_lain == null){
          $('#jenis_kendaraan_lain').hide();
        }else{
          $('#jenis_kendaraan_lain').show();
          $('#jenis_kendaraan_lain').val(data.jenis_kendaraan_lain);
        }
        $('#jumlah_karung').val(data.jumlah_karung);
        $('#quantity').val(data.quantity);
        if(data.jumlah_kg_sak == '' || data.jumlah_kg_sak == null){
          $('#jumlah_kg_sak').val('').trigger('change');
        }else{
          $('#jumlah_kg_sak').val(data.jumlah_kg_sak).trigger('change');
        }
        if(data.jenis_karung == '' || data.jenis_karung == null){
          $('#jenis_karung').val('').trigger('change');
        }else{
          $('#jenis_karung').val(data.jenis_karung).trigger('change');
        }
        $('#berat_timbangan').val(data.berat_timbangan);
        if(data.unit_berat_timbangan == '' || data.unit_berat_timbangan == null){
          $('#unit_berat_timbangan').val('').trigger('change');
        }else{
          $('#unit_berat_timbangan').val(data.unit_berat_timbangan).trigger('change');
        }
        $('#berat_aktual').val(data.berat_aktual);
        if(data.unit_berat_aktual == '' || data.unit_berat_aktual == null){
          $('#unit_berat_aktual').val('').trigger('change');
        }else{
          $('#unit_berat_aktual').val(data.unit_berat_aktual).trigger('change');
        }
        if(data.kuli1 != null){
          $('[name="nama_kuli[]"]').val(data.kuli1);
          // $('#edit_nama_kuli[]').val(data.kuli1);
        }
        if(data.kuli2 != null){
          $('#dynamic_field_kuli').append('<tr id="row_kuli2"><td><input type="text" name="nama_kuli[]" placeholder="Nama Kuli" class="form-control nama_kuli_list" value="'+data.kuli2+'" readonly/></td></tr>');
        }
        if(data.kuli3 != null){
          $('#dynamic_field_kuli').append('<tr id="row_kuli3"><td><input type="text" name="nama_kuli[]" placeholder="Nama Kuli" class="form-control nama_kuli_list" value="'+data.kuli3+'" readonly/></td></tr>');
        }
        if(data.stapel1 != null){
          $('[name="nama_stapel[]"]').val(data.stapel1);
          // $('#edit_nama_stapel[]').val(data.stapel1);
        }
        if(data.stapel2 != null){
          $('#dynamic_field_stapel').append('<tr id="row_stapel2"><td><input type="text" name="nama_stapel[]" placeholder="Nama Stapel" class="form-control nama_stapel_list" value="'+data.stapel2+'" readonly/></td></tr>');
        }
        if(data.stapel3 != null){
          $('#dynamic_field_stapel').append('<tr id="row_stapel3"><td><input type="text" name="nama_stapel[]" placeholder="Nama Stapel" class="form-control nama_stapel_list" value="'+data.stapel3+'" readonly/></td></tr>');
        }
        if(data.stapel4 != null){
          $('#dynamic_field_stapel').append('<tr id="row_stapel4"><td><input type="text" name="nama_stapel[]" placeholder="Nama Stapel" class="form-control nama_stapel_list" value="'+data.stapel4+'" readonly/></td></tr>');
        }
        if(data.stapel5 != null){
          $('#dynamic_field_stapel').append('<tr id="row_stapel5"><td><input type="text" name="nama_stapel[]" placeholder="Nama Stapel" class="form-control nama_stapel_list" value="'+data.stapel5+'" readonly/></td></tr>');
        }
      })

      if(divisi == 1){
        $('#logistikform input[type="text"]'). prop("disabled", true);
        $('#logistikform input[type="file"]'). prop("disabled", true);
        $('#logistikform textarea'). prop("disabled", true);
        $('#btn_save_log').hide();
        $('#btn_tidak_terlibat_log').hide();
        $('#btn_terlibat_log').show();
      }else{
        $('#logistikform input[type="text"]'). prop("disabled", false);
        $('#logistikform input[type="file"]'). prop("disabled", false);
        $('#logistikform textarea'). prop("disabled", false);
        $('#btn_save_log').show();
        $('#btn_tidak_terlibat_log').show();
        $('#btn_terlibat_log').hide();
      }
    });

    $('body').on('click', '#save_input_komitmen_log', function () {
      var selesai_tanggal_komitmen = $('#selesai_tanggal_komitmen_log').val();
      var komitmen = $('#komitmen_log').val();
      var nomor_complaint = $('#nomor_complaint_log').val();

      $.ajax({
        type: "GET",
        url: "{{ url('save_data_komitmen_log') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint },
        success: function (data) {
          $('#selesai_tanggal_komitmen_log').val('');
          $('#komitmen_log').val('');
          var oTable = $('#list_komitmen_log_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {  
          var oTable = $('#list_komitmen_log_table').dataTable();
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
            var oTable = $('#list_komitmen_log_table').dataTable(); 
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn_tidak_terlibat_log', function () {
      var complaint = $('#nomor_complaint_log').val();
      $('#nomor_complaint_alasan').val(complaint);
    });

    $('body').on('click', '#btn_terlibat_log', function () {
      var complaint = $('#nomor_complaint_log').val();
      var custid = $('#custid_log').val();
      var divisi = $('#no_divisi_log').val();

      if(confirm("Apakah Anda Yakin?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint_logistik/terlibat/') }}",
          data: { 'nomor_complaint' : complaint, 'divisi' : divisi },
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
            $("#modal_proses_complaint_logistik").modal('hide');
            $("#modal_proses_complaint_logistik").trigger('click');
            alert("Updated Data Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
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

    $('body').on('click', '#edit_complaint_logistik', function () {
      var complaint = $(this).data('id');
      var custid_log = $(this).data('custid');
      $('#edit_nomor_complaint_log').val(complaint);
      $('#edit_custid_log').val(custid_log);
      var url = "{{ url('complaint_logistik/show/edit/no_complaint') }}";
      url = url.replace('no_complaint', enc(complaint.toString()));
      $('#edit_upload_file_log').html('');
      $('#edit_evaluasi_log').html('');
      $('#edit_solusi_customer_log').html('');
      $('#edit_solusi_internal_log').html('');
      $('#edit_komitmen_log').html('');
      $('#edit_selesai_tanggal_komitmen_log').html('');
      $.get(url, function (data) {
        $('#edit_evaluasi_log').html(data.evaluasi);
        $('#edit_solusi_customer_log').html(data.solusi_customer);
        $('#edit_solusi_internal_log').html(data.solusi_internal);
        $('#edit_list_komitmen_log_table').DataTable().destroy();
        load_edit_komitmen_log(complaint);
        if(data.lampiran == null){
          $('#lihat_lampiran_log').html('No Lampiran');
          $('#lihat_lampiran_log').addClass('disabled');
          $('#lihat_lampiran_log').attr('href', '#');
        }else{
          $('#lihat_lampiran_log').html('Lihat Lampiran');
          $('#lihat_lampiran_log').removeClass('disabled');
          $('#lihat_lampiran_log').attr('href', '../data_file/' + data.lampiran);
        }
      })
    });

    $('body').on('click', '#save_new_edit_komitmen_log', function () {
      var selesai_tanggal_komitmen = $('#edit_selesai_tanggal_komitmen_log').val();
      var komitmen = $('#edit_komitmen_log').val();
      var nomor_complaint = $('#edit_nomor_complaint_log').val();

      $.ajax({
        type: "GET",
        url: "{{ url('save_new_edit_komitmen_log') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint },
        success: function (data) {
          $('#edit_selesai_tanggal_komitmen_log').val('');
          $('#edit_komitmen_log').val('');
          var oTable = $('#edit_list_komitmen_log_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {  
          var oTable = $('#edit_list_komitmen_log_table').dataTable();
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
      $('#edit_komitmen_log').val('');
      $('#edit_selesai_tanggal_komitmen_log').val('');
      $('#edit_komitmen_lama_log').val('');
      $('#edit_selesai_tanggal_komitmen_lama_log').val('');
      $('#save_edit_komitmen_log').show();
      $('#save_new_edit_komitmen_log').hide();
      $.get(url, function (data) {
        $('#edit_selesai_tanggal_komitmen_log').val(data.selesai_tanggal_komitmen);
        $('#edit_komitmen_log').val(data.komitmen);
        $('#edit_selesai_tanggal_komitmen_lama_log').val(data.selesai_tanggal_komitmen);
        $('#edit_komitmen_lama_log').val(data.komitmen);
      })
    });

    $('body').on('click', '#save_edit_komitmen_log', function () {
      var selesai_tanggal_komitmen = $('#edit_selesai_tanggal_komitmen_log').val();
      var komitmen = $('#edit_komitmen_log').val();
      var selesai_tanggal_komitmen_lama = $('#edit_selesai_tanggal_komitmen_lama_log').val();
      var komitmen_lama = $('#edit_komitmen_lama_log').val();
      var nomor_complaint = $('#edit_nomor_complaint_log').val();
      $.ajax({
        type: "GET",
        url: "{{ url('update_edit_komitmen_log') }}",
        data: { 'selesai_tanggal_komitmen' : selesai_tanggal_komitmen, 'komitmen' : komitmen, 'nomor_complaint' : nomor_complaint, 'selesai_tanggal_komitmen_lama' : selesai_tanggal_komitmen_lama, 'komitmen_lama' : komitmen_lama },
        success: function (data) {
          $('#edit_komitmen_log').val('');
          $('#edit_selesai_tanggal_komitmen_log').val('');
          $('#edit_komitmen_lama_log').val('');
          $('#edit_selesai_tanggal_komitmen_lama_log').val('');
          $('#save_edit_komitmen_log').hide();
          $('#save_new_edit_komitmen_log').show();
          var oTable = $('#edit_list_komitmen_log_table').dataTable();
          oTable.fnDraw(false);
        },
        error: function (data) {
          var oTable = $('#edit_list_komitmen_log_table').dataTable();
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
            var oTable = $('#edit_list_komitmen_log_table').dataTable(); 
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-logbook', function () {
      var nomor_complaint = $(this).data("id");
      $('#title_modal_logbook').html("Logbook Complaint No " + nomor_complaint);
      $('#logbook_complaint_table').DataTable().destroy();
      load_logbook(nomor_complaint);
    });

    $('body').on('click', '#validate_complaint_logistik', function () {
      var nomor_complaint = $(this).data("id");
      $('#val_nomor_complaint').val(nomor_complaint);
      $('#list_of_action_table').DataTable().destroy();
      load_data_komitmen(nomor_complaint);
    });

    $('body').on('click', '#validate_complaint', function () {
      var nomor_complaint = $('#val_nomor_complaint').val();
      if(confirm("Data Ini Akan Diubah Status Menjadi Done?")){
        $.ajax({
          type: "GET",
          url: "{{ url('complaint/validasi/') }}",
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
          url: "{{ url('complaint_logistik/tidak_terlibat') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success: function (data) {
            $('#alasantidakterlibatform').trigger("reset");
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
            $('#modal_alasan_tidak_terlibat').modal('hide');
            $("#modal_alasan_tidak_terlibat").trigger('click');
            alert("Data Changed Successfully");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#alasantidakterlibatform').trigger("reset");
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
            $('#modal_alasan_tidak_terlibat').modal('hide');
            $("#modal_alasan_tidak_terlibat").trigger('click');
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#logistikform').validate({
      rules: {
        nomor_complaint_log: {
          required: true,
        },
        custid_log: {
          required: true,
        },
        no_divisi_log: {
          required: true,
        },
        solusi_internal_log: {
          required: true,
        },
        upload_file_log: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        nomor_complaint_log: {
          required: "Nomor Complaint is required",
        },
        custid_log: {
          required: "Custid is required",
        },
        no_divisi_log: {
          required: "No Divisi is required",
        },
        solusi_internal_log: {
          required: "Solusi Internal is required",
        },
        upload_file_log: {
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
        var myform = document.getElementById("logistikform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/complaint_logistik/store') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#logistikform').trigger("reset");
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
            $("#modal_proses_complaint_logistik").modal('hide');
            $("#modal_proses_complaint_logistik").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#logistikform').trigger("reset");
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
            $("#modal_proses_complaint_logistik").modal('hide');
            $("#modal_proses_complaint_logistik").trigger('click');
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#editlogistikform').validate({
      rules: {
        edit_nomor_complaint_log: {
          required: true,
        },
        edit_custid_log: {
          required: true,
        },
        edit_solusi_internal_log: {
          required: true,
        },
        edit_upload_file_log: {
          extension: "jpg,jpeg,pdf",
          filesize: 2,
        },
      },
      messages: {
        edit_nomor_complaint_log: {
          required: "Nomor Complaint is required",
        },
        edit_custid_log: {
          required: "Custid is required",
        },
        edit_solusi_internal_log: {
          required: "Solusi Internal is required",
        },  
        edit_upload_file_log: {
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
        var myform = document.getElementById("editlogistikform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/complaint_logistik/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#editlogistikform').trigger("reset");
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
            $("#modal_edit_complaint_logistik").modal('hide');
            $("#modal_edit_complaint_logistik").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            $('#editlogistikform').trigger("reset");
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
            $("#modal_edit_complaint_logistik").modal('hide');
            $("#modal_edit_complaint_logistik").trigger('click');
            alert("Something Goes Wrong. Please Try Again");
          }
        });
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
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
@endsection