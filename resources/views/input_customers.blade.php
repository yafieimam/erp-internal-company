@extends('layouts.app_admin')

@section('title')
<title>CUSTOMERS - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/daterangepicker/daterangepicker.css')}}"> -->
<!-- <link rel="stylesheet" href="{{asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}"> -->
<style type="text/css">
  #semua_customers_list_table tbody tr:hover, #history_customer_table tbody tr:hover, #dsgm_customers_list_table tbody tr:hover, #imj_customers_list_table tbody tr:hover{
    cursor: pointer;
  }
  .modal {
    overflow-y: auto !important;
  }
  div.dataTables_length {
    margin-top: 5px;
    margin-right: 1em;
  }
  .filter-btn {
    margin-top: 32px;
  }
  .save-btn {
    float: right;
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
    .customer-btn {
      margin-bottom: 10px;
    }
    .save-btn {
      margin-top: 10px;
      float: none;
    }
    .save-btn-in {
      width: 100%;
    }
    .lihat-table {
      overflow-x: auto;
    }
    .radio-control {
      padding-left: 0 !important;
    }
    #lihat-customer-table td, #lihat-customer-table th {
      display:inline-block;
        padding:5px;
        width:100%;
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
          <h1 class="m-0 text-dark">Customers</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Customers</li>
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
              <button type="button" name="btn_upload_excel" id="btn_upload_excel" class="btn btn-block btn-primary omset-btn" data-toggle="modal" data-target="#modal_upload_excel">Upload Excel</button>
            </div>
            <div class="col-2">
              <button type="button" name="btn_input_manual" id="btn_input_manual" class="btn btn-block btn-primary customer-btn" data-toggle="modal" data-target="#modal_input_customer">Input Customer</button>
            </div>
            @if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10)
            <div class="col-2">
              <button type="button" name="btn_pembagian_sales" id="btn_pembagian_sales" class="btn btn-block btn-primary pembagian-btn" data-toggle="modal" data-target="#modal_pembagian_sales">Pembagian Sales</button>
            </div>
            @endif
            <div class="col-6" id="div_select_tipe_customers" style="display: none;">
              <div class="form-group">
                <select id="tipe_customers" name="tipe_customers" class="form-control">
                  <option value="1" selected>Konfirmasi Customers</option>
                  <option value="2">Customers List</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div id="dialog-confirm"></div>
        <div class="card-body" id="konfirmasi_customers">
          <table id="konfirmasi_customers_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
            <tr>
                <th>Customers ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>NPWP</th>
                <th>NIK</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
           </thead>
          </table>
        </div>
        <div class="card-body" id="customers_list" style="display: none;">
          <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#semua_customers" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Semua</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#dsgm_customers" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">DSGM</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#imj_customers" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">IMJ</a>
            </li>
          </ul>
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="semua_customers" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="semua_customers_list_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                    <th>Company</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="dsgm_customers" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="dsgm_customers_list_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="imj_customers" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="imj_customers_list_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Cust ID</th>
                    <th>Nama</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_input_customer">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Customer</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div id="response-input-customers" style="width: 40%; margin-left: 30%; margin-top: 20px;">
          </div>
          
          <form method="post" class="input_customer_form" id="input_customer_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input class="form-control" type="text" name="name" id="name" placeholder="Name" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="custid">Customer ID</label>
                  <input class="form-control" type="text" name="custid" id="custid" placeholder="Customer ID" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="company">Perusahaan</label>
                  <select id="company" name="company" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="crd">CRD</label>
                  <input class="form-control" type="text" name="crd" id="crd" placeholder="CRD" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input class="form-control" type="text" name="phone" id="phone" placeholder="Phone" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="fax">Fax</label>
                  <input class="form-control" type="text" name="fax" id="fax" placeholder="Fax" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="nama_cp">PIC</label>
                  <input class="form-control" type="text" name="nama_cp" id="nama_cp" placeholder="PIC" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="jabatan_cp">Jabatan PIC</label>
                  <input class="form-control" type="text" name="jabatan_cp" id="jabatan_cp" placeholder="Jabatan PIC" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="bidang_usaha">Bidang Usaha</label>
                  <input class="form-control" type="text" name="bidang_usaha" id="bidang_usaha" placeholder="Bidang Usaha" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="telepon_cp">Telepon PIC</label>
                  <input class="form-control" type="text" name="telepon_cp" id="telepon_cp" placeholder="Telepon PIC" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="city">Address City</label>
                  <select id="city" name="city" class="form-control select2 city" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="address">Address</label>
                  <textarea class="form-control" rows="3" name="address" id="address" placeholder="Address"></textarea>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="wraddress">Warehouse Address</label>
                  <textarea class="form-control" rows="3" name="wraddress" id="wraddress" placeholder="Warehouse Address"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <div class="label-flex">
                    <label for="npwp_yes">Input NPWP</label>
                  </div>
                  <div class="custom-control custom-radio" style="margin-top: 5px;">
                    <input class="form-control custom-control-input" type="radio" id="npwp_yes" name="input_npwp" value="yes" checked>
                    <label for="npwp_yes" class="custom-control-label">Use NPWP</label>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <div class="label-flex">
                    <label for="npwp_no">&nbsp</label>
                  </div>
                  <div class="custom-control custom-radio" style="margin-top: 5px;">
                    <input class="form-control custom-control-input" type="radio" id="npwp_no" name="input_npwp" value="no">
                    <label for="npwp_no" class="custom-control-label">Don't Use NPWP</label>
                  </div>
                </div>
              </div>
              <div class="col-4" id="input-npwp">
                <div class="form-group">
                  <label for="npwp">NPWP</label>
                  <input class="form-control" type="text" name="npwp" id="npwp" placeholder="NPWP" />
                </div>
              </div>
              <div class="col-4" id="input-nik">
                <div class="form-group">
                  <label for="nik">NIK</label>
                  <input class="form-control" type="text" name="nik" id="nik" placeholder="NIK" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4"></div>
              <div class="col-4" id="input-image-npwp">
                <div class="form-group">
                  <label for="upload_image_npwp">Upload Image NPWP</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="upload_image_npwp" name="upload_image_npwp">
                      <label class="custom-file-label" for="upload_image_npwp">Choose Image</label>
                    </div>
                  </div>
                </div>
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
              </div>
              <div class="col-4" id="input-image-ktp" style="display: none;">
                <div class="form-group">
                  <label for="upload_image_ktp">Upload Image KTP</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="upload_image_ktp" name="upload_image_ktp">
                      <label class="custom-file-label" for="upload_image_ktp">Choose Image</label>
                    </div>
                  </div>
                </div>
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
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

  <div class="modal fade" id="modal_upload_excel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Excel</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" class="upload-form" id="upload-form" action="{{ url('/custo/upload_excel') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_customers.xlsx')}}" target="_blank">here</a>.</span>
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

  <div class="modal fade" id="modal_pembagian_sales">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Pembagian Sales Customers</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div>
            <div class="row">
              <div class="col-4"></div>
              <div class="col-4">
                <div class="form-group">
                  <label for="sales">Pilih Sales</label>
                  <select id="sales" name="sales" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <table id="pembagian_sales_table" style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Company</th>
                  <th>City</th>
                  <th>Address</th>
                  <th>Choose Sales</th>
                  <th>Offline?</th>
                  <th>Perihal</th>
                  <th>Tanggal</th>
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

  <div class="modal fade" id="modal_lihat_customers">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="detail_judul"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row"> 
            <div class="col-2"></div>
            <div class="col-8">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                      <div class="form-group-aktif float-left">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="aktif" name="apakah_aktif" value="1">
                          <label for="aktif" class="custom-control-label">Aktif</label>
                        </div>
                        <input class="form-control" type="hidden" name="custid_aktif" id="custid_aktif" />
                        <input class="form-control" type="hidden" name="company_aktif" id="company_aktif" />
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-group-aktif float-left">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="nonaktif" name="apakah_aktif" value="0">
                          <label for="nonaktif" class="custom-control-label">Nonaktif</label>
                        </div>
                      </div>
                    </div> 
                    <div class="col-sm-3">
                      <div class="form-group-aktif save-btn">
                        <label>&nbsp</label>
                        <button type="button" class="btn btn-success save-btn-in" id="btn-change-aktif">Save</button>
                      </div>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
            <div class="col-2"></div>
          </div>
          <div class="row">
           <div class="col-lg-12 lihat-table">
            <table class="table table-bordered" style="border: none;" id="lihat-customer-table">
             <thead>
              <tr>
               <td colspan="2" align="center"><button type="button" data-dismiss="modal" data-toggle="modal" data-target="#modal_lihat_history" id="btn-history-cust" class="btn btn-primary save-btn-in">History Customer</button></td>
             </tr>
              <tr>
               <th width="35%">Customer ID : </th>
               <td id="td_custid"></td>
             </tr>
             <tr>
               <th>Nama Customer : </th>
               <td id="td_nama_customer"></td>
             </tr>
             <tr>
               <th>Customer Group: </th>
               <td id="td_customer_group"></td>
             </tr>
             <tr>
               <th>Email : </th>
               <td id="td_email"></td>
             </tr>
             <tr>
               <th>Tipe Customer : </th>
               <td id="td_tipe_customer"></td>
             </tr>
             <tr>
               <th>Produk Yang Sering Dipesan : </th>
               <td id="td_produk_pesanan"></td>
             </tr>
             <tr>
               <th>Perusahaan : </th>
               <td id="td_company"></td>
             </tr>
             <tr>
               <th>PIC : </th>
               <td id="td_pic"></td>
             </tr>
             <tr>
               <th>Jabatan PIC : </th>
               <td id="td_jabatan_pic"></td>
             </tr>
             <tr>
               <th>Bidang Usaha : </th>
               <td id="td_bidang_usaha"></td>
             </tr>
             <tr>
               <th>Telepon PIC : </th>
               <td id="td_telepon_pic"></td>
             </tr>
             <tr>
               <th>CRD : </th>
               <td id="td_crd"></td>
             </tr>
             <tr>
               <th>Limit : </th>
               <td id="td_limit"></td>
             </tr>
             <tr>
               <th>Alamat : </th>
               <td id="td_address"></td>
             </tr>
             <tr>
               <th>Alamat Warehouse : </th>
               <td id="td_warehouse_address"></td>
             </tr>
             <tr>
               <th>Kota : </th>
               <td id="td_city"></td>
             </tr>
             <tr>
               <th>Phone : </th>
               <td id="td_phone"></td>
             </tr>
             <tr>
               <th>Fax : </th>
               <td id="td_fax"></td>
             </tr>
             <tr>
               <th>SPV : </th>
               <td id="td_spv"></td>
             </tr>
             <tr>
               <th>SLS : </th>
               <td id="td_sls"></td>
             </tr>
             <tr>
               <th>Tele SLS : </th>
               <td id="td_telesls"></td>
             </tr>
             <tr>
               <th>NPWP : </th>
               <td id="td_npwp"></td>
             </tr>
             <tr>
               <th>NIK : </th>
               <td id="td_nik"></td>
             </tr>
             <tr>
               <th>Created At : </th>
               <td id="td_created_at"></td>
             </tr>
             <tr>
               <th>Created By : </th>
               <td id="td_created_by"></td>
             </tr>
             <tr>
               <th>Updated At : </th>
               <td id="td_updated_at"></td>
             </tr>
           </thead>
         </table>
       </div>
     </div>
   </div>
   <div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" id="btn-edit-cust" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_edit_customer">Edit</button>
  </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_lihat_history">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="judul_lihat_history"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table id="history_customer_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th></th>
                <th>Tanggal</th>
                <th>History</th>
                <th>Nomor</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_lihat_customers">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_edit_customer">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modal-edit-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div id="response-edit-customers" style="width: 40%; margin-left: 30%; margin-top: 20px;">
          </div>
          
          <form method="post" class="edit_customer_form" id="edit_customer_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input class="form-control" type="hidden" name="custid_old_edit" id="custid_old_edit" />
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="name_edit">Name</label>
                  <input class="form-control" type="text" name="name_edit" id="name_edit" placeholder="Name" />
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="custid_edit">Customer ID</label>
                  <input class="form-control" type="text" name="custid_edit" id="custid_edit" placeholder="Customer ID" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="company_edit">Perusahaan</label>
                  <select id="company_edit" name="company_edit" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="crd_edit">CRD</label>
                  <input class="form-control" type="text" name="crd_edit" id="crd_edit" placeholder="CRD" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <label for="phone_edit">Phone</label>
                  <input class="form-control" type="text" name="phone_edit" id="phone_edit" placeholder="Phone" />
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="fax_edit">Fax</label>
                  <input class="form-control" type="text" name="fax_edit" id="fax_edit" placeholder="Fax" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="city_edit">Address City</label>
                  <select id="city_edit" name="city_edit" class="form-control select2 city-edit" style="width: 100%;">
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="address_edit">Address</label>
                  <textarea class="form-control" rows="3" name="address_edit" id="address_edit" placeholder="Address"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="nama_cp_edit">PIC</label>
                  <input class="form-control" type="text" name="nama_cp_edit" id="nama_cp_edit" placeholder="PIC" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="jabatan_cp_edit">Jabatan PIC</label>
                  <input class="form-control" type="text" name="jabatan_cp_edit" id="jabatan_cp_edit" placeholder="Jabatan PIC" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="bidang_usaha_edit">Bidang Usaha</label>
                  <input class="form-control" type="text" name="bidang_usaha_edit" id="bidang_usaha_edit" placeholder="Bidang Usaha" />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label for="telepon_cp_edit">Telepon PIC</label>
                  <input class="form-control" type="text" name="telepon_cp_edit" id="telepon_cp_edit" placeholder="Telepon PIC" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="wraddress_edit">Warehouse Address</label>
                  <textarea class="form-control" rows="3" name="wraddress_edit" id="wraddress_edit" placeholder="Warehouse Address"></textarea>
                </div>
              </div>
              <div class="col-4" id="input-npwp">
                <div class="form-group">
                  <label for="npwp_edit">NPWP</label>
                  <input class="form-control" type="text" name="npwp_edit" id="npwp_edit" placeholder="NPWP" />
                </div>
              </div>
              <div class="col-4" id="input-nik">
                <div class="form-group">
                  <label for="nik_edit">NIK</label>
                  <input class="form-control" type="text" name="nik_edit" id="nik_edit" placeholder="NIK" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <div class="custom-control custom-radio radio-control">
                    <a target="_blank" id="lihat_npwp_edit" class="btn btn-primary save-btn-in">Lihat Foto NPWP</a>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <div class="label-flex">
                    <label>&nbsp</label>
                  </div>
                  <div class="custom-control custom-radio radio-control">
                    <a target="_blank" id="lihat_ktp_edit" class="btn btn-primary save-btn-in">Lihat Foto KTP</a>
                  </div>
                </div>
              </div>
              <div class="col-4" id="input-image-npwp">
                <div class="form-group">
                  <label for="upload_image_npwp_edit">Change Image NPWP</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="upload_image_npwp_edit" name="upload_image_npwp_edit">
                      <label class="custom-file-label" for="upload_image_npwp_edit">Choose Image</label>
                    </div>
                  </div>
                </div>
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>
              </div>
              <div class="col-4" id="input-image-ktp">
                <div class="form-group">
                  <label for="upload_image_ktp_edit">Change Image KTP</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="upload_image_ktp_edit" name="upload_image_ktp_edit">
                      <label class="custom-file-label" for="upload_image_ktp_edit">Choose Image</label>
                    </div>
                  </div>
                </div>
                <p style="font-weight: 700;">Format File Allowed only .jpg, .jpeg, or .pdf <br>Max Size of File is 2 MB.</p>  
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_lihat_customers">Close</button>
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
  <script type="text/javascript">
    $(function () {
      $('#filter_tanggal').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        }
      });

      var dt = new Date();

      $('#tanggal_jadwal').flatpickr({
        enableTime: true,
        allowInput: true,
        time_24hr: true,
        minDate: "today",
        defaultHour: dt.getHours(),
        defaultMinute: dt.getMinutes(),
        disableMobile: true
      });
    });
  </script>

  <script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    
    $(document).ready(function(){
      let key = "{{ env('MIX_APP_KEY') }}";

      var any_nomor = "{{ $any_nomor ?? '' }}";

      var id_sales = "{{ Session::get('tipe_user') }}";

      var target = $('.nav-tabs a.nav-link.active').attr("href");

      if(any_nomor.length > 13){
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

      var table = $('#konfirmasi_customers_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ route("customers.index") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       columns: [
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'name',
         name:'name'
        },
        {
         data:'email',
         name:'email'
        },
        {
         data:'address',
         name:'address'
        },
        {
         data:'city',
         name:'city'
        },
        {
         data:null,
         render: function(data, type, full, meta){
          if(full['npwp'] == null){
            return '---';
          }else if(full['image_npwp'] == null){
            return full['npwp'];
          }else{
            return full['npwp'] + '<br>' + '<a target="_blank" href="' + '../data_file/' + full['image_npwp'] + '">Lihat NPWP</a>';
          }
         }
        },
        {
         data:null,
         render: function(data, type, full, meta){
          if(full['nik'] == null){
            return '---';
          }else{
            return full['nik'] + '<br>' + '<a target="_blank" href="' + '../data_file/' + full['image_nik'] + '">Lihat KTP</a>';
          }
         }
        },
        {
         data:'created_at',
         name:'created_at'
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });

      if(id_sales == 20){
        $('#div_select_tipe_customers').hide();
        $('#konfirmasi_customers').hide();
        $('#customers_list').show();
        load_data_semua_customers();
      }else{
        $('#div_select_tipe_customers').show();
      }

      if(any_nomor != ''){
        $('#konfirmasi_customers').hide();
        $('#customers_list').show();
        load_data_semua_customers();
        table.ajax.url('{{ url("customers/status/specific") }}').load();
        $('#tipe_customers').find('option:selected').remove().end();
      }

      $("#tipe_customers").change(function() {
        if ($(this).val() == 1) {
          $('#konfirmasi_customers').show();
          $('#customers_list').hide();
          $('#konfirmasi_customers_table').DataTable().destroy();
          load_data_konfirmasi();
        }else if ($(this).val() == 2) {
          $('#konfirmasi_customers'). hide();
          $('#customers_list').show();
          $('#konfirmasi_customers_table').DataTable().destroy();
          $('#semua_customers_list_table').DataTable().destroy();
          load_data_semua_customers();
        }else {
          $('#konfirmasi_customers').hide();
          $('#customers_list').hide();
        }
      });

      $('.nav-tabs a').on('shown.bs.tab', function (e) {
        target = $(e.target).attr("href");
        if(target == '#semua_customers'){
          $('#semua_customers_list_table').DataTable().destroy();
          load_data_semua_customers();
        }else if(target == '#dsgm_customers'){
          $('#dsgm_customers_list_table').DataTable().destroy();
          load_data_dsgm_customers();
        }else if(target == '#imj_customers'){
          $('#imj_customers_list_table').DataTable().destroy();
          load_data_imj_customers();
        }
      });

      $('input[type=radio][name=input_npwp]').change(function() {
        if (this.value == 'yes') {
          $('#input-npwp').show();
          $('#input-image-npwp').show();
        }
        else if (this.value == 'no') {
          $('#input-npwp').hide();
          $('#input-image-npwp').hide();
        }
      });

      $('#nik').keyup(function() {
        if($(this).val().length == 0){
          $('#input-image-ktp').hide();
        }else{
          $('#input-image-ktp').show();
        }
      }).keyup();

      $('#upload_image_ktp').bind('change', function() {
        if(this.files[0].size > 2097152){
          alert("File Size Must Not More Than 2 MB");
          $(this).val('');
        }
      });

      $('#upload_image_npwp').bind('change', function() {
        if(this.files[0].size > 2097152){
          alert("File Size Must Not More Than 2 MB");
          $(this).val('');
        }
      });

      $('#upload_image_ktp_edit').bind('change', function() {
        if(this.files[0].size > 2097152){
          alert("File Size Must Not More Than 2 MB");
          $(this).val('');
        }
      });

      $('#upload_image_npwp_edit').bind('change', function() {
        if(this.files[0].size > 2097152){
          alert("File Size Must Not More Than 2 MB");
          $(this).val('');
        }
      });

     function load_data_konfirmasi(from_date = '', to_date = '')
     {
      table = $('#konfirmasi_customers_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ route("customers.index") }}',
        data:{from_date:from_date, to_date:to_date}
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       columns: [
        {
         data:'custid',
         name:'custid'
        },
        {
         data:'name',
         name:'name'
        },
        {
         data:'email',
         name:'email'
        },
        {
         data:'address',
         name:'address'
        },
        {
         data:'city',
         name:'city'
        },
        {
         data:null,
         render: function(data, type, full, meta){
          if(full['npwp'] == null){
            return '---';
          }else if(full['image_npwp'] == null){
            return full['npwp'];
          }else{
            return full['npwp'] + '<br>' + '<a target="_blank" href="' + '../data_file/' + full['image_npwp'] + '">Lihat NPWP</a>';
          }
         }
        },
        {
         data:null,
         render: function(data, type, full, meta){
          if(full['nik'] == null){
            return '---';
          }else{
            return full['nik'] + '<br>' + '<a target="_blank" href="' + '../data_file/' + full['image_nik'] + '">Lihat KTP</a>';
          }
         }
        },
        {
         data:'created_at',
         name:'created_at'
        },
        {
         data:'action',
         name:'action'
        }
       ]
      });
     }

     function load_data_pembagian_sales()
     {
      table = $('#pembagian_sales_table').DataTable({
       processing: true,
       serverSide: true,
       paging: false,
       bInfo : false,
       scrollY: "250px",
       ajax: {
        url:'{{ url("custo/pembagian") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        }
       },
       dom: 'lrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       columns: [
        {
         data:'nama',
         name:'nama',
         className:'dt-center',
         width:'13%'
        },
        {
         data:'company',
         name:'company',
         className:'dt-center',
         width:'10%',
         render: function ( data, type, row)
         {
            return '<input type="hidden" name="kode_company[' + $('<div />').text(row.custid).html() + ']" value="' + $('<div />').text(row.kode_company).html() + '">' + $('<div />').text(row.company).html();
         }
        },
        {
         data:'city',
         name:'city',
         className:'dt-center',
         width:'13%'
        },
        {
         data:'address',
         name:'address',
         className:'dt-center',
         width:'25%'
        },
        {
         data:null,
         name:null,
         className:'dt-center',
         width:'5%',
         render: function ( data, type, row)
         {
            return '<input type="checkbox" name="pilih_sales[' + $('<div />').text(row.custid).html() + ']" value="1"><input type="hidden" name="custid[' + $('<div />').text(row.custid).html() + ']" value="' + $('<div />').text(row.custid).html() + '">';
         }
        },
        {
         data:null,
         name:null,
         className:'dt-center',
         width:'5%',
         render: function ( data, type, row)
         {
            return '<input type="checkbox" name="offline[' + $('<div />').text(row.custid).html() + ']" value="1">';
         }
        },
        {
         data:null,
         name:null,
         className:'dt-center',
         width:'15%',
         render: function ( data, type, row)
         {
            return '<select name="perihal[' + $('<div />').text(row.custid).html() + ']" style="width:100%;">' +
            '<option value="" selected>Perihal</option>'+
            '<option value="Customer Visit">Customer Visit</option>'+
            '<option value="Customer Complaint">Customer Complaint</option>'+
            '<option value="Customer Info">Customer Info</option>'+
            '<option value="Cek Kompetitor">Cek Kompetitor</option>'+
            '</select>';
         }
        },
        {
         data:null,
         name:null,
         className:'dt-center',
         width:'15%',
         render: function ( data, type, row)
         {
            $('[name="tanggal_kunjungan[' + $('<div />').text(row.custid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_kunjungan[' + $('<div />').text(row.custid).html() + ']" style="width:100%; margin-bottom: 10px;" placeholder="Tanggal Kunjungan">';
         }
        }
       ]
      });
     }

     function load_data_semua_customers(from_date = '', to_date = '')
     {
      if(any_nomor != ''){
        table = $('#semua_customers_list_table').DataTable({
           processing: true,
           serverSide: true,
           responsive: {
            details: {
              type: 'column'
            }
          },
           lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
           ajax: {
            url:'{{ route("customers.create") }}',
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            },
            data : function( d ) {
              d.nomor = any_nomor;
            }
          },
          order: [[3,'asc']],
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_customers');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
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
           name:'DT_RowIndex'
         },
         {
           data:'custid',
           name:'custid'
         },
         {
           data:'name',
           name:'name'
         },
         {
           data:'company',
           name:'company'
         }
         ]
       });
      }else{
          table = $('#semua_customers_list_table').DataTable({
           processing: true,
           serverSide: true,
           responsive: {
            details: {
              type: 'column'
            }
          },
           lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
           ajax: {
            url:'{{ route("customers.create") }}',
            data:{from_date:from_date, to_date:to_date},
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
          },
          order: [[3,'asc']],
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_customers');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
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
           name:'DT_RowIndex'
         },
         {
           data:'custid',
           name:'custid'
         },
         {
           data:'name',
           name:'name'
         },
         {
           data:'company',
           name:'company'
         }
         ]
       });
      }
     }

     function load_data_dsgm_customers(from_date = '', to_date = '')
     {
        table = $('#dsgm_customers_list_table').DataTable({
           processing: true,
           serverSide: true,
           responsive: {
            details: {
              type: 'column'
            }
          },
          lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
          ajax: {
            url:'{{ url("custo/data/dsgm/table") }}',
            data:{from_date:from_date, to_date:to_date},
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
          },
          order: [[3,'asc']],
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_customers');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
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
           name:'DT_RowIndex'
         },
         {
           data:'custid',
           name:'custid'
         },
         {
           data:'name',
           name:'name'
         }
         ]
       });
     }

     function load_data_imj_customers(from_date = '', to_date = '')
     {
        table = $('#imj_customers_list_table').DataTable({
           processing: true,
           serverSide: true,
           responsive: {
            details: {
              type: 'column'
            }
          },
          lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
          ajax: {
            url:'{{ url("custo/data/imj/table") }}',
            data:{from_date:from_date, to_date:to_date},
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
          },
          order: [[3,'asc']],
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_customers');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
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
           name:'DT_RowIndex'
         },
         {
           data:'custid',
           name:'custid'
         },
         {
           data:'name',
           name:'name'
         }
         ]
       });
     }

     function load_history(custid=''){
      table = $('#history_customer_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
          details: {
            type: 'column'
          }
        },
        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        ajax: {
          url:'{{ url("history_customer") }}',
          data:{custid:custid},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        searching: false,
        columns: [
          {
            className: 'control',
            orderable: false,
            targets: 0,
            defaultContent:''
          },
          {
            data:'tanggal',
            name:'tanggal'
          },
          {
            data:'history',
            name:'history'
          },
          {
            data:'nomor',
            name:'nomor'
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

    function enkrip(plainText){
      let iv = CryptoJS.lib.WordArray.random(16),
      key_key = CryptoJS.enc.Utf8.parse(key.substr(7));
      let options = {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
      };
      let encrypted = CryptoJS.AES.encrypt(plainText, key_key, options);
      encrypted = encrypted.toString();
      iv = CryptoJS.enc.Base64.stringify(iv);
      let result = {
        iv: iv,
        value: encrypted,
        mac: CryptoJS.HmacSHA256(iv + encrypted, key_key).toString()
      }
      result = JSON.stringify(result);
      result = CryptoJS.enc.Utf8.parse(result);
      return CryptoJS.enc.Base64.stringify(result);
    }

    $('#semua_customers_list_table').on( 'click', 'tbody tr', function () {
      var cust = table.row(this).data();
      var custid = cust['custid'];
      var company = cust['company'];
      document.getElementById("detail_judul").innerHTML = "Detail Customers with ID : " + custid;
      var url = "{{ url('custo/data/semua/detail/table/custid/company') }}";
      url = url.replace('custid', enc(custid.toString()));
      url = url.replace('company', enc(company.toString()));
      $('#custid_aktif').val('');
      $('#company_aktif').val('');
      $('#td_custid').html('');
      $('#td_nama_customer').html('');
      $('#td_customer_group').html('');
      $('#td_email').html('');
      $('#td_tipe_customer').html('');
      $('#td_produk_pesanan').html('');
      $('#td_crd').html('');
      $('#td_limit').html('');
      $('#td_pic').html('');
      $('#td_jabatan_pic').html('');
      $('#td_bidang_usaha').html('');
      $('#td_telepon_pic').html('');
      $('#td_address').html('');
      $('#td_warehouse_address').html('');
      $('#td_city').html('');
      $('#td_phone').html('');
      $('#td_fax').html('');
      $('#td_spv').html('');
      $('#td_sls').html('');
      $('#td_telesls').html('');
      $('#td_npwp').html('');
      $('#td_nik').html('');
      $('#td_created_at').html('');
      $('#td_created_by').html('');
      $('#td_updated_at').html('');
      $.get(url, function (data) {
        $('#custid_aktif').val(data.custid);
        $('#company_aktif').val(data.company);
        $('#td_custid').html(data.custid);
        $('#td_nama_customer').html(data.custname);
        if(data.groupid == null || data.groupid == ''){
          $('#td_customer_group').html('--');
        }else{
          $('#td_customer_group').html(data.nama_group);
        }
        if(data.aktif == 0){
          $("#nonaktif").prop("checked", true).trigger("click");
        }else if(data.aktif == 1){
          $("#aktif").prop("checked", true).trigger("click");
        }
        if(data.email){
          $('#td_email').html(data.email);
        }else{
          $('#td_email').html('--');
        }
        if(data.tipe_customer){
          $('#td_tipe_customer').html(data.tipe_customer);
        }else{
          $('#td_tipe_customer').html('--');
        }
        if(data.nama_perusahaan){
          $('#td_company').html(data.nama_perusahaan);
        }else{
          $('#td_company').html('--');
        }
        if(data.produk_pesanan){
          $('#td_produk_pesanan').html(data.produk_pesanan);
        }else{
          $('#td_produk_pesanan').html('--');
        }
        if(data.crd){
          $('#td_crd').html(data.crd);
        }else{
          $('#td_crd').html('--');
        }
        if(data.custlimit){
          $('#td_limit').html(data.custlimit);
        }else{
          $('#td_limit').html('--');
        }
        if(data.nama_cp){
          $('#td_pic').html(data.nama_cp);
        }else{
          $('#td_pic').html('--');
        }
        if(data.jabatan_cp){
          $('#td_jabatan_pic').html(data.jabatan_cp);
        }else{
          $('#td_jabatan_pic').html('--');
        }
        if(data.bidang_usaha){
          $('#td_bidang_usaha').html(data.bidang_usaha);
        }else{
          $('#td_bidang_usaha').html('--');
        }
        if(data.telepon_cp){
          $('#td_telepon_pic').html(data.telepon_cp);
        }else{
          $('#td_telepon_pic').html('--');
        }
        $('#td_address').html(data.address);
        $('#td_warehouse_address').html(data.wraddress);
        if(data.city){
          $('#td_city').html(data.city);
        }else{
          $('#td_city').html(data.city_name);
        }
        if(data.phone){
          $('#td_phone').html(data.phone);
        }else{
          $('#td_phone').html('--');
        }
        if(data.fax){
          $('#td_fax').html(data.fax);
        }else{
          $('#td_fax').html('--');
        }
        if(data.spv){
          $('#td_spv').html(data.spv);
        }else{
          $('#td_spv').html('--');
        }
        if(data.sls){
          $('#td_sls').html(data.sls);
        }else{
          $('#td_sls').html('--');
        }
        if(data.telesls){
          $('#td_telesls').html(data.telesls);
        }else{
          $('#td_telesls').html('--');
        }
        if(data.npwp == null){
          $('#td_npwp').html('--');
        }else if(data.image_npwp == null){
          $('#td_npwp').html(data.npwp);
        }else{
          if(data.company == 'DSGM'){
            $('#td_npwp').html(data.npwp + ' ' + '(<a target="_blank" href="' + '../data_file/' + data.image_npwp + '">Lihat Foto</a>)');
          }else if(data.company == 'IMJ'){
            $('#td_npwp').html(data.npwp + ' ' + '(<a target="_blank" href="' + 'http://admin.rkmortar.com/data_file/' + data.image_npwp + '">Lihat Foto</a>)');
          }
        }
        if(data.nik == null){
          $('#td_nik').html('--');
        }else if(data.image_nik == null){
          $('#td_nik').html(data.nik);
        }else{
          if(data.company == 'DSGM'){
            $('#td_nik').html(data.nik + ' ' + '(<a target="_blank" href="' + '../data_file/' + data.image_nik + '">Lihat Foto</a>)');
          }else if(data.company == 'IMJ'){
            $('#td_nik').html(data.nik + ' ' + '(<a target="_blank" href="' + 'http://admin.rkmortar.com/data_file/' + data.image_nik + '">Lihat Foto</a>)');
          }
        }
        $('#td_created_at').html(data.created_at);
        $('#td_created_by').html(data.created_by);
        $('#td_updated_at').html(data.updated_at);
        $('#btn-edit-cust').data('id', data.custid);
        $('#btn-edit-cust').data('com', data.company);
        $('#btn-history-cust').data('id', data.custid);
      })
    });

    $('#dsgm_customers_list_table').on( 'click', 'tbody tr', function () {
      var cust = table.row(this).data();
      var custid = cust['custid'];
      document.getElementById("detail_judul").innerHTML = "Detail Customers with ID : " + custid;
      var url = "{{ url('custo/data/dsgm/detail/table/custid') }}";
      url = url.replace('custid', enc(custid.toString()));
      $('#custid_aktif').val('');
      $('#company_aktif').val('');
      $('#td_custid').html('');
      $('#td_nama_customer').html('');
      $('#td_customer_group').html('');
      $('#td_email').html('');
      $('#td_tipe_customer').html('');
      $('#td_produk_pesanan').html('');
      $('#td_crd').html('');
      $('#td_limit').html('');
      $('#td_pic').html('');
      $('#td_jabatan_pic').html('');
      $('#td_bidang_usaha').html('');
      $('#td_telepon_pic').html('');
      $('#td_address').html('');
      $('#td_warehouse_address').html('');
      $('#td_city').html('');
      $('#td_phone').html('');
      $('#td_fax').html('');
      $('#td_spv').html('');
      $('#td_sls').html('');
      $('#td_telesls').html('');
      $('#td_npwp').html('');
      $('#td_nik').html('');
      $('#td_created_at').html('');
      $('#td_created_by').html('');
      $('#td_updated_at').html('');
      $.get(url, function (data) {
        $('#custid_aktif').val(data.custid);
        $('#company_aktif').val(data.company);
        $('#td_custid').html(data.custid);
        $('#td_nama_customer').html(data.custname);
        if(data.groupid == null || data.groupid == ''){
          $('#td_customer_group').html('--');
        }else{
          $('#td_customer_group').html(data.nama_group);
        }
        if(data.aktif == 0){
          $("#nonaktif").prop("checked", true).trigger("click");
        }else if(data.aktif == 1){
          $("#aktif").prop("checked", true).trigger("click");
        }
        if(data.email){
          $('#td_email').html(data.email);
        }else{
          $('#td_email').html('--');
        }
        if(data.tipe_customer){
          $('#td_tipe_customer').html(data.tipe_customer);
        }else{
          $('#td_tipe_customer').html('--');
        }
        if(data.nama_perusahaan){
          $('#td_company').html(data.nama_perusahaan);
        }else{
          $('#td_company').html('--');
        }
        if(data.produk_pesanan){
          $('#td_produk_pesanan').html(data.produk_pesanan);
        }else{
          $('#td_produk_pesanan').html('--');
        }
        if(data.crd){
          $('#td_crd').html(data.crd);
        }else{
          $('#td_crd').html('--');
        }
        if(data.custlimit){
          $('#td_limit').html(data.custlimit);
        }else{
          $('#td_limit').html('--');
        }
        if(data.nama_cp){
          $('#td_pic').html(data.nama_cp);
        }else{
          $('#td_pic').html('--');
        }
        if(data.jabatan_cp){
          $('#td_jabatan_pic').html(data.jabatan_cp);
        }else{
          $('#td_jabatan_pic').html('--');
        }
        if(data.bidang_usaha){
          $('#td_bidang_usaha').html(data.bidang_usaha);
        }else{
          $('#td_bidang_usaha').html('--');
        }
        if(data.telepon_cp){
          $('#td_telepon_pic').html(data.telepon_cp);
        }else{
          $('#td_telepon_pic').html('--');
        }
        $('#td_address').html(data.address);
        $('#td_warehouse_address').html(data.wraddress);
        if(data.city){
          $('#td_city').html(data.city);
        }else{
          $('#td_city').html(data.city_name);
        }
        if(data.phone){
          $('#td_phone').html(data.phone);
        }else{
          $('#td_phone').html('--');
        }
        if(data.fax){
          $('#td_fax').html(data.fax);
        }else{
          $('#td_fax').html('--');
        }
        if(data.spv){
          $('#td_spv').html(data.spv);
        }else{
          $('#td_spv').html('--');
        }
        if(data.sls){
          $('#td_sls').html(data.sls);
        }else{
          $('#td_sls').html('--');
        }
        if(data.telesls){
          $('#td_telesls').html(data.telesls);
        }else{
          $('#td_telesls').html('--');
        }
        if(data.npwp == null){
          $('#td_npwp').html('--');
        }else if(data.image_npwp == null){
          $('#td_npwp').html(data.npwp);
        }else{
          $('#td_npwp').html(data.npwp + ' ' + '(<a target="_blank" href="' + '../data_file/' + data.image_npwp + '">Lihat Foto</a>)');
        }
        if(data.nik == null){
          $('#td_nik').html('--');
        }else if(data.image_nik == null){
          $('#td_nik').html(data.nik);
        }else{
          $('#td_nik').html(data.nik + ' ' + '(<a target="_blank" href="' + '../data_file/' + data.image_nik + '">Lihat Foto</a>)');
        }
        $('#td_created_at').html(data.created_at);
        $('#td_created_by').html(data.created_by);
        $('#td_updated_at').html(data.updated_at);
        $('#btn-edit-cust').data('id', data.custid);
        $('#btn-edit-cust').data('com', data.company);
        $('#btn-history-cust').data('id', data.custid);
      })
    });

    $('#imj_customers_list_table').on( 'click', 'tbody tr', function () {
      var cust = table.row(this).data();
      var custid = cust['custid'];
      document.getElementById("detail_judul").innerHTML = "Detail Customers with ID : " + custid;
      var url = "{{ url('custo/data/imj/detail/table/custid') }}";
      url = url.replace('custid', enc(custid.toString()));
      $('#custid_aktif').val('');
      $('#company_aktif').val('');
      $('#td_custid').html('');
      $('#td_nama_customer').html('');
      $('#td_customer_group').html('');
      $('#td_email').html('');
      $('#td_tipe_customer').html('');
      $('#td_produk_pesanan').html('');
      $('#td_crd').html('');
      $('#td_limit').html('');
      $('#td_pic').html('');
      $('#td_jabatan_pic').html('');
      $('#td_bidang_usaha').html('');
      $('#td_telepon_pic').html('');
      $('#td_address').html('');
      $('#td_warehouse_address').html('');
      $('#td_city').html('');
      $('#td_phone').html('');
      $('#td_fax').html('');
      $('#td_spv').html('');
      $('#td_sls').html('');
      $('#td_telesls').html('');
      $('#td_npwp').html('');
      $('#td_nik').html('');
      $('#td_created_at').html('');
      $('#td_created_by').html('');
      $('#td_updated_at').html('');
      $.get(url, function (data) {
        $('#custid_aktif').val(data.custid);
        $('#company_aktif').val(data.company);
        $('#td_custid').html(data.custid);
        $('#td_nama_customer').html(data.custname);
        if(data.groupid == null || data.groupid == ''){
          $('#td_customer_group').html('--');
        }else{
          $('#td_customer_group').html(data.nama_group);
        }
        if(data.aktif == 0){
          $("#nonaktif").prop("checked", true).trigger("click");
        }else if(data.aktif == 1){
          $("#aktif").prop("checked", true).trigger("click");
        }
        if(data.email){
          $('#td_email').html(data.email);
        }else{
          $('#td_email').html('--');
        }
        if(data.tipe_customer){
          $('#td_tipe_customer').html(data.tipe_customer);
        }else{
          $('#td_tipe_customer').html('--');
        }
        if(data.nama_perusahaan){
          $('#td_company').html(data.nama_perusahaan);
        }else{
          $('#td_company').html('--');
        }
        if(data.produk_pesanan){
          $('#td_produk_pesanan').html(data.produk_pesanan);
        }else{
          $('#td_produk_pesanan').html('--');
        }
        if(data.crd){
          $('#td_crd').html(data.crd);
        }else{
          $('#td_crd').html('--');
        }
        if(data.custlimit){
          $('#td_limit').html(data.custlimit);
        }else{
          $('#td_limit').html('--');
        }
        if(data.nama_cp){
          $('#td_pic').html(data.nama_cp);
        }else{
          $('#td_pic').html('--');
        }
        if(data.jabatan_cp){
          $('#td_jabatan_pic').html(data.jabatan_cp);
        }else{
          $('#td_jabatan_pic').html('--');
        }
        if(data.bidang_usaha){
          $('#td_bidang_usaha').html(data.bidang_usaha);
        }else{
          $('#td_bidang_usaha').html('--');
        }
        if(data.telepon_cp){
          $('#td_telepon_pic').html(data.telepon_cp);
        }else{
          $('#td_telepon_pic').html('--');
        }
        $('#td_address').html(data.address);
        $('#td_warehouse_address').html(data.wraddress);
        if(data.city){
          $('#td_city').html(data.city);
        }else{
          $('#td_city').html(data.city_name);
        }
        if(data.phone){
          $('#td_phone').html(data.phone);
        }else{
          $('#td_phone').html('--');
        }
        if(data.fax){
          $('#td_fax').html(data.fax);
        }else{
          $('#td_fax').html('--');
        }
        if(data.spv){
          $('#td_spv').html(data.spv);
        }else{
          $('#td_spv').html('--');
        }
        if(data.sls){
          $('#td_sls').html(data.sls);
        }else{
          $('#td_sls').html('--');
        }
        if(data.telesls){
          $('#td_telesls').html(data.telesls);
        }else{
          $('#td_telesls').html('--');
        }
        if(data.npwp == null){
          $('#td_npwp').html('--');
        }else if(data.image_npwp == null){
          $('#td_npwp').html(data.npwp);
        }else{
          $('#td_npwp').html(data.npwp + ' ' + '(<a target="_blank" href="' + 'http://admin.rkmortar.com/data_file/' + data.image_npwp + '">Lihat Foto</a>)');
        }
        if(data.nik == null){
          $('#td_nik').html('--');
        }else if(data.image_nik == null){
          $('#td_nik').html(data.nik);
        }else{
          $('#td_nik').html(data.nik + ' ' + '(<a target="_blank" href="' + 'http://admin.rkmortar.com/data_file/' + data.image_nik + '">Lihat Foto</a>)');
        }
        $('#td_created_at').html(data.created_at);
        $('#td_created_by').html(data.created_by);
        $('#td_updated_at').html(data.updated_at);
        $('#btn-edit-cust').data('id', data.custid);
        $('#btn-edit-cust').data('com', data.company);
        $('#btn-history-cust').data('id', data.custid);
      })
    });

    $('body').on('click', '#btn_input_manual', function () {
      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      $("#company").on('change', function() {
        if($(this).val() == 'IMJ'){
          $('#crd').prop('disabled',true);
          $('#wraddress').prop('disabled',true);
        }else{
          $('#crd').prop('disabled',false);
          $('#wraddress').prop('disabled',false);
        }
      });
    });

    $('body').on('click', '#btn_pembagian_sales', function () {
      $('#pembagian_sales_table').DataTable().destroy();
      load_data_pembagian_sales();

      var url = "{{ url('leads/select/sales') }}";
      $.get(url, function (data) {
        $('#sales').children().remove().end().append('<option value="" selected>Pilih Sales</option>');
        $.each(data, function(k, v) {
          $('#sales').append('<option value="' + v.id_sales + '">' + v.nama_sales + '</option>');
        });
      })
    });

    $('#history_customer_table').on( 'click', 'tbody td:not(:first-child)', function () {
      console.log(table.row(this).data());
      var history = table.row(this).data();
      var tipe_history = history['kode_history'];
      var nomor = history['nomor'];
      if(tipe_history == 1){
        var url = "{{ url('sales/orders/any_number') }}";
        url = url.replace('any_number', enkrip(nomor));
        window.location.href = url;
      }else if(tipe_history == 6){
        var url = "{{ url('sales/complaint/any_number/type') }}";
        url = url.replace('any_number', enkrip(nomor));
        url = url.replace('type', enkrip(tipe_history));
        window.location.href = url;
      }
    });

    $('#modal_lihat_history').on('hidden.bs.modal', function () {
      if(target == '#semua_customers'){
        $('#semua_customers_list_table').DataTable().destroy();
        load_data_semua_customers();
      }else if(target == '#dsgm_customers'){
        $('#dsgm_customers_list_table').DataTable().destroy();
        load_data_dsgm_customers();
      }else if(target == '#imj_customers'){
        $('#imj_customers_list_table').DataTable().destroy();
        load_data_imj_customers();
      }
    });

    $('#btn-history-cust').click(function (e) {
      cust_id_history = $(this).data("id");
      document.getElementById("judul_lihat_history").innerHTML = "History Customer " + cust_id_history;
      $('#history_customer_table').DataTable().destroy();
      load_history(cust_id_history);
    });
    
    $('#btn-edit-cust').click(function (e) {
      cust_id_edit = $(this).data("id");
      company_edit = $(this).data("com");
      document.getElementById("modal-edit-title").innerHTML = "Edit Customer " + cust_id_edit;

      var url_company = "{{ url('get_company') }}";
      $.get(url_company, function (data) {
        $('#company_edit').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company_edit').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      $("#company_edit").on('change', function() {
        if($(this).val() == 'IMJ'){
          $('#crd_edit').prop('disabled',true);
          $('#wraddress_edit').prop('disabled',true);
        }else{
          $('#crd_edit').prop('disabled',false);
          $('#wraddress_edit').prop('disabled',false);
        }
      });

      var url = "{{ url('show/edit_customer_admin/no_customers/company') }}";
      url = url.replace('no_customers', enc(cust_id_edit.toString()));
      url = url.replace('company', enc(company_edit.toString()));
      $('#custid_edit').val('');
      $('#custid_old_edit').val('');
      $('#name_edit').val('');
      $('#address_edit').html('');
      $('#wraddress_edit').html('');
      $('#crd_edit').html('');
      $('#city_edit').html('');
      $('#phone_edit').val('');
      $('#fax_edit').val('');
      $('#npwp_edit').val('');
      $('#nik_edit').val('');
      $('#nama_cp_edit').val('');
      $('#company_edit').val('');
      $('#jabatan_cp_edit').val('');
      $('#bidang_usaha_edit').val('');
      $('#telepon_cp_edit').val('');
      $('#upload_image_npwp_edit').html('');
      $('#upload_image_ktp_edit').html('');
      $.get(url, function (data) {
        $('#custid_edit').val(data.custid);
        $('#custid_old_edit').val(data.custid);
        $('#name_edit').val(data.custname);
        $('#address_edit').html(data.address);
        $('#wraddress_edit').html(data.wraddress);
        $('#phone_edit').val(data.phone);
        $('#fax_edit').val(data.fax);
        $('#npwp_edit').val(data.npwp);
        $('#nik_edit').val(data.nik);
        $('#crd_edit').val(data.crd);
        $('#nama_cp_edit').val(data.nama_cp);
        $('#jabatan_cp_edit').val(data.jabatan_cp);
        $('#bidang_usaha_edit').val(data.bidang_usaha);
        $('#telepon_cp_edit').val(data.telepon_cp);
        $('#company_edit').val(data.company);
        $('#city_edit').html('<option value="' + data.city_name + '" selected>' + data.city + '</option>');
        if(data.image_npwp == null){
          $('#lihat_npwp_edit').html('No NPWP Image');
          $('#lihat_npwp_edit').addClass('disabled');
          $('#lihat_npwp_edit').attr('href', '#');
        }else{
          if(data.company == 'DSGM'){
            $('#lihat_npwp_edit').html('Lihat Foto NPWP');
            $('#lihat_npwp_edit').removeClass('disabled');
            $('#lihat_npwp_edit').attr('href', '../data_file/' + data.image_npwp);
          }else if(data.company == 'IMJ'){
            $('#lihat_npwp_edit').html('Lihat Foto NPWP');
            $('#lihat_npwp_edit').removeClass('disabled');
            $('#lihat_npwp_edit').attr('href', 'http://admin.rkmortar.com/data_file/' + data.image_npwp);
          }
        }
        if(data.image_nik == null){
          $('#lihat_ktp_edit').html('No KTP Image');
          $('#lihat_ktp_edit').addClass('disabled');
          $('#lihat_ktp_edit').attr('href', '#');
        }else{
          if(data.company == 'DSGM'){
            $('#lihat_ktp_edit').html('Lihat Foto KTP');
            $('#lihat_ktp_edit').removeClass('disabled');
            $('#lihat_ktp_edit').attr('href', '../data_file/' + data.image_nik);
          }else if(data.company == 'IMJ'){
            $('#lihat_ktp_edit').html('Lihat Foto KTP');
            $('#lihat_ktp_edit').removeClass('disabled');
            $('#lihat_ktp_edit').attr('href', 'http://admin.rkmortar.com/data_file/' + data.image_nik);
          }
        }
      })
    });

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        if($('#tipe_customers').val() == 1){
          $('#konfirmasi_customers_table').DataTable().destroy();
          load_data_konfirmasi(from_date, to_date);
        }else if($('#tipe_customers').val() == 2){
            if(target == '#semua_customers'){
              $('#semua_customers_list_table').DataTable().destroy();
              load_data_semua_customers(from_date, to_date);
            }else if(target == '#dsgm_customers'){
              $('#dsgm_customers_list_table').DataTable().destroy();
              load_data_dsgm_customers(from_date, to_date);
            }else if(target == '#imj_customers'){
              $('#imj_customers_list_table').DataTable().destroy();
              load_data_imj_customers(from_date, to_date);
            }
        }    
     }
     else
     {
       alert('Both Date is required');
     }
   });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      if($('#tipe_customers').val() == 1){
        $('#konfirmasi_customers_table').DataTable().destroy();
        load_data_konfirmasi();
      }else if($('#tipe_customers').val() == 2){
        if(target == '#semua_customers'){
          $('#semua_customers_list_table').DataTable().destroy();
          load_data_semua_customers();
        }else if(target == '#dsgm_customers'){
          $('#dsgm_customers_list_table').DataTable().destroy();
          load_data_dsgm_customers();
        }else if(target == '#imj_customers'){
          $('#imj_customers_list_table').DataTable().destroy();
          load_data_imj_customers();
        }
      }
    });

    $('#btn-change-aktif').click(function(){
      var custid = $('#custid_aktif').val();
      var company = $('#company_aktif').val();
      var aktif = $('input[name="apakah_aktif"]:checked').val();
      $.ajax({
        type: "GET",
        url: "{{ url('add/cust_aktif') }}",
        data: { 'aktif' : aktif, 'custid' : custid, 'company' : company },
        success: function (data) {
          $("#modal_lihat_customers").modal('hide');
          $("#modal_lihat_customers").trigger('click');

          if(target == '#semua_customers'){
            var oTable = $('#semua_customers_list_table').dataTable();
            oTable.fnDraw(false);
          }else if(target == '#dsgm_customers'){
            var oTable = $('#dsgm_customers_list_table').dataTable();
            oTable.fnDraw(false);
          }else if(target == '#imj_customers'){
            var oTable = $('#imj_customers_list_table').dataTable();
            oTable.fnDraw(false);
          }

          alert("Successfull");
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('#btn-save-data').click( function() {
      var data = $('#pembagian_sales_table input, #pembagian_sales_table select, #sales').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-data').data('token'),
        },
        type: "POST",
        url: '{{ url("custo/pembagian/save") }}',
        dataSrc : 'data',
        dataType: 'JSON',
        data: data,
        async: 'false',
        success: function(data){
          var oTable = $('#pembagian_sales_table').dataTable();
          oTable.fnDraw(false);
          $('#modal_pembagian_sales').modal('hide');
          $("#modal_pembagian_sales").trigger('click');
          alert('Data Successfully Updated');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

  function validasiCustomers() {
    var custid = $('#validate_customers').data("id");

    $("#dialog-confirm").html("Data Customers Divalidasi?");

    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
      resizable: false,
      modal: true,
      title: "Validasi Customers",
      height: 250,
      width: 400,
      buttons: {
        "Validasi": function() {
          $(this).dialog('close');
          callbackValidasiCustomers(true, custid);
        },
        "Tolak": function() {
          $(this).dialog('close');
          callbackValidasiCustomers(false, custid);
        }
      }
    });
  };

  $('body').on('click', '#validate_customers', validasiCustomers);

  function callbackValidasiCustomers(value, custid) {
    if(value) {
      $.ajax({
        type: "GET",
        url: "{{ url('validasi_user') }}",
        data: { 'custid' : custid },
        success: function (data) {
          var oTable = $('#konfirmasi_customers_table').dataTable(); 
          oTable.fnDraw(false);
          alert("Data Validated Successfully");
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    }else{
      $.ajax({
        type: "GET",
        url: "{{ url('reject_user') }}",
        data: { 'custid' : custid },
        success: function (data) {
          var oTable = $('#konfirmasi_customers_table').dataTable(); 
          oTable.fnDraw(false);
          alert("Data Rejected Successfully");
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    }
  }

  $('.customer').select2({
    dropdownParent: $('#modal_lihat_customers .modal-content'),
    placeholder: 'Tidak Memiliki Agent Induk',
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
    dropdownParent: $('#modal_input_customer .modal-content'),
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

  $('.city-edit').select2({
    dropdownParent: $('#modal_edit_customer .modal-content'),
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

  $('#input_customer_form').validate({
    rules: {
      name: {
        required: true,
      },
      company: {
        required: true,
      },
      address: {
        required: true,
      },
      city: {
        required: true,
      },
      npwp: {
        required: '#npwp_yes[value="yes"]:checked',
      },
      upload_image_npwp: {
        required: '#npwp_yes[value="yes"]:checked',
        extension: "jpg|jpeg|pdf",
        maxsize: 2097152,
      },
      upload_image_ktp: {
        extension: "jpg|jpeg|pdf",
        maxsize: 2097152,
      },
    },
    messages: {
      name: {
        required: "Nama Harus Diisi",
      },
      company: {
        required: "Perusahaan Harus Diisi",
      },
      address: {
        required: "Address Harus Diisi",
      },
      wraddress: {
        required: "Warehouse Address Harus Diisi",
      },
      city: {
        required: "Address City Harus Diisi",
      },
      npwp: {
        required: "NPWP Harus Diisi",
      },
      upload_image_npwp: {
        required: "Taxpayer Identification Image is Required",
        extension: "File Format Only JPG, JPEG, or PDF",
        maxsize: "Max File Size is 2 MB"
      },
      upload_image_ktp: {
        extension: "File Format Only JPG, JPEG, or PDF",
        maxsize: "Max File Size is 2 MB"
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
      var myform = document.getElementById("input_customer_form");
      var formdata = new FormData(myform);

      $.ajax({
        type:'POST',
        url:"{{ url('/inputCustomerAdmin') }}",
        data: formdata,
        processData: false,
        contentType: false,
        success:function(data){
          if(data.status == false){
            alert(data.msg);
            $('#response-input-customers').removeClass("alert alert-danger");
            $('#response-input-customers').hide();
          }else{
            $('#response-input-customers').removeClass("alert alert-danger");
            $('#response-input-customers').hide();
            $('#input_customer_form').trigger("reset");

            if(target == '#semua_customers'){
              $('#semua_customers_list_table').DataTable().destroy();
              load_data_semua_customers();
            }else if(target == '#dsgm_customers'){
              $('#dsgm_customers_list_table').DataTable().destroy();
              load_data_dsgm_customers();
            }else if(target == '#imj_customers'){
              $('#imj_customers_list_table').DataTable().destroy();
              load_data_imj_customers();
            }

            $('#modal_input_customer').modal('hide');
            $("#modal_input_customer").trigger('click');
            alert("Customers Successfully Added");
          }
        },
        error: function (data) {
          console.log('Error:', data);
          if( data.status === 422 ) {
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (key, value) {
              $('#response-input-customers').addClass("alert alert-danger");
              if($.isPlainObject(value)) {
                $.each(value, function (key, value) {                       
                  $('#response-input-customers').show().append(value+"<br/>");
                });
              }else{
                $('#response-input-customers').show().append(value+"<br/>");
              }
            });
          }
        }
      });
    }
  });

  $('#edit_customer_form').validate({
    rules: {
      custid_edit: {
        required: true,
      },
      name_edit: {
        required: true,
      },
      address_edit: {
        required: true,
      },
      city_edit: {
        required: true,
      },
      upload_image_npwp_edit: {
        extension: "jpg|jpeg|pdf",
        maxsize: 2097152,
      },
      upload_image_ktp_edit: {
        extension: "jpg|jpeg|pdf",
        maxsize: 2097152,
      },
    },
    messages: {
      name_edit: {
        required: "Nama Harus Diisi",
      },
      custid_edit: {
        required: "Customer ID Harus Diisi",
      },
      address_edit: {
        required: "Address Harus Diisi",
      },
      city_edit: {
        required: "Address City Harus Diisi",
      },
      upload_image_npwp_edit: {
        extension: "File Format Only JPG, JPEG, or PDF",
        maxsize: "Max File Size is 2 MB"
      },
      upload_image_ktp_edit: {
        extension: "File Format Only JPG, JPEG, or PDF",
        maxsize: "Max File Size is 2 MB"
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
      var myform = document.getElementById("edit_customer_form");
      var formdata = new FormData(myform);

      $.ajax({
        type:'POST',
        url:"{{ url('/editCustomerAdmin') }}",
        data: formdata,
        processData: false,
        contentType: false,
        success:function(data){
          if(data.status == false){
            alert(data.msg);
            $('#response-edit-customers').removeClass("alert alert-danger");
            $('#response-edit-customers').hide();
          }else{
            $('#response-edit-customers').removeClass("alert alert-danger");
            $('#response-edit-customers').hide();
            $('#edit_customer_form').trigger("reset");

            if(target == '#semua_customers'){
              $('#semua_customers_list_table').DataTable().destroy();
              load_data_semua_customers();
            }else if(target == '#dsgm_customers'){
              $('#dsgm_customers_list_table').DataTable().destroy();
              load_data_dsgm_customers();
            }else if(target == '#imj_customers'){
              $('#imj_customers_list_table').DataTable().destroy();
              load_data_imj_customers();
            }

            $('#modal_edit_customer').modal('hide');
            $("#modal_edit_customer").trigger('click');
            alert("Customers Data Successfully Updated");
          }
        },
        error: function (data) {
          console.log('Error:', data);
          if( data.status === 422 ) {
            var errors = $.parseJSON(data.responseText);
            $.each(errors, function (key, value) {
              $('#response-edit-customers').addClass("alert alert-danger");
              if($.isPlainObject(value)) {
                $.each(value, function (key, value) {                       
                  $('#response-edit-customers').show().append(value+"<br/>");
                });
              }else{
                $('#response-edit-customers').show().append(value+"<br/>");
              }
            });
          }
        }
      });
    }
  });
});
</script>

<script type="text/javascript">
  $(".customer").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Agent Induk...");
  });
  $(".customer").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
  $(".city").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Kota / Kabupaten...");
  });
  $(".city").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script type="text/javascript">
  $(".city-edit").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Cari Kota / Kabupaten...");
  });
  $(".city-edit").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
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
