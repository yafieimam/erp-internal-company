@extends('layouts.app_admin')

@section('title')
<title>KOMPETITOR - PT. DWI SELO GIRI MAS</title>
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
  #kompetitor_list_table tbody tr:hover{
    cursor: pointer;
  }
  .modal {
    overflow-y: auto !important;
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
    .save-btn-in {
      width: 100%;
    }
    .lihat-table {
      overflow-x: auto;
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
          <h1 class="m-0 text-dark">Data Kompetitor</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Kompetitor</li>
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
            <div class="col-3">
              <button type="button" name="btn_input_manual" id="btn_input_manual" class="btn btn-block btn-primary customer-btn" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
            </div>
            <div class="col-3">
              <button type="button" name="btn_upload_excel" id="btn_upload_excel" class="btn btn-block btn-primary upload-btn" data-toggle="modal" data-target="#modal_upload_excel">Upload Excel</button>
            </div>
            @if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10)
            <div class="col-3">
              <button type="button" name="btn_pembagian_sales" id="btn_pembagian_sales" class="btn btn-block btn-primary pembagian-btn" data-toggle="modal" data-target="#modal_pembagian_sales">Pembagian Sales</button>
            </div>
            @endif
          </div>
        </div>
        <div id="dialog-confirm"></div>
        <div class="card-body">
          <table id="kompetitor_list_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th></th>
                <th>No</th>
                <th>Komp ID</th>
                <th>Nama</th>
                <th>Bidang Usaha</th>
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
          <h4 class="modal-title">Input Data Kompetitor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div id="response-input-data" style="width: 40%; margin-left: 30%; margin-top: 20px;">
          </div>
          
          <form method="post" class="input_data_form" id="input_data_form" action="javascript:void(0)" enctype="multipart/form-data">
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
                  <label for="crd">CRD</label>
                  <input class="form-control" type="text" name="crd" id="crd" placeholder="CRD" />
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
              <div class="col-2">
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input class="form-control" type="text" name="phone" id="phone" placeholder="Phone" />
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="fax">Fax</label>
                  <input class="form-control" type="text" name="fax" id="fax" placeholder="Fax" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="nama_cp">PIC</label>
                  <input class="form-control" type="text" name="nama_cp" id="nama_cp" placeholder="PIC" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="jabatan_cp">Jabatan PIC</label>
                  <input class="form-control" type="text" name="jabatan_cp" id="jabatan_cp" placeholder="Jabatan PIC" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="bidang_usaha">Bidang Usaha</label>
                  <input class="form-control" type="text" name="bidang_usaha" id="bidang_usaha" placeholder="Bidang Usaha" />
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
        <form method="post" class="upload-form" id="upload-form" action="{{ url('/kompetitor/upload_excel') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_competitor.xlsx')}}" target="_blank">here</a>.</span>
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
          <h4 class="modal-title">Pembagian Sales Kompetitor</h4>
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

  <div class="modal fade" id="modal_lihat_data">
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
           <div class="col-lg-12 lihat-table">
            <table class="table table-bordered" style="border: none;" id="lihat-customer-table">
             <thead>
              <tr>
               <th width="35%">ID : </th>
               <td id="td_kompid"></td>
             </tr>
             <tr>
               <th>Nama : </th>
               <td id="td_nama"></td>
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
               <th>CRD : </th>
               <td id="td_crd"></td>
             </tr>
             <tr>
               <th>Alamat : </th>
               <td id="td_address"></td>
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
    <button type="button" id="btn-delete-cust" class="btn btn-danger">Delete</button>
    <button type="button" id="btn-edit-cust" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_edit_data">Edit</button>
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
          <h4 class="modal-title" id="modal-edit-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div id="response-edit-data" style="width: 40%; margin-left: 30%; margin-top: 20px;">
          </div>
          
          <form method="post" class="edit_data_form" id="edit_data_form" action="javascript:void(0)" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input class="form-control" type="hidden" name="kompid_edit" id="kompid_edit" />
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="name_edit">Name</label>
                  <input class="form-control" type="text" name="name_edit" id="name_edit" placeholder="Name" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="crd_edit">CRD</label>
                  <input class="form-control" type="text" name="crd_edit" id="crd_edit" placeholder="CRD" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="company_edit">Perusahaan</label>
                  <select id="company_edit" name="company_edit" class="form-control" style="width: 100%;">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="nama_cp_edit">PIC</label>
                  <input class="form-control" type="text" name="nama_cp_edit" id="nama_cp_edit" placeholder="PIC" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="jabatan_cp_edit">Jabatan PIC</label>
                  <input class="form-control" type="text" name="jabatan_cp_edit" id="jabatan_cp_edit" placeholder="Jabatan PIC" />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label for="bidang_usaha_edit">Bidang Usaha</label>
                  <input class="form-control" type="text" name="bidang_usaha_edit" id="bidang_usaha_edit" placeholder="Bidang Usaha" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="phone_edit">Phone</label>
                  <input class="form-control" type="text" name="phone_edit" id="phone_edit" placeholder="Phone" />
                </div>
              </div>
              <div class="col-4">
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
            </div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label for="address_edit">Address</label>
                  <textarea class="form-control" rows="3" name="address_edit" id="address_edit" placeholder="Address"></textarea>
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
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_lihat_data">Close</button>
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

<script>
  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>

<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    
    $(document).ready(function(){
      let key = "{{ env('MIX_APP_KEY') }}";
      var any_nomor = "{{ $any_nomor ?? '' }}";

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

      var table = $('#kompetitor_list_table').DataTable({
           processing: true,
           serverSide: true,
           responsive: {
            details: {
              type: 'column'
            }
          },
           lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
           ajax: {
            url:'{{ url("kompetitor/list/view/table") }}',
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
          },
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_data');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
          },
          columns: [
          {
            className: 'control dt-center',
            orderable: false,
            targets: 0,
            defaultContent:''
          },
          {
           data:'DT_RowIndex',
           name:'DT_RowIndex',
           className:'dt-center'
         },
         {
           data:'kompid',
           name:'kompid',
           className:'dt-center'
         },
         {
           data:'name',
           name:'name',
           className:'dt-center'
         },
         {
           data:'bidang_usaha',
           name:'bidang_usaha',
           className:'dt-center'
         }
         ]
       });

      if(any_nomor != ''){
        load_data_list_kompetitor();
        table.ajax.url('{{ url("kompetitor/specific/view/table") }}').load();
      }

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

      function load_data_pembagian_sales()
     {
      table = $('#pembagian_sales_table').DataTable({
       processing: true,
       serverSide: true,
       paging: false,
       bInfo : false,
       scrollY: "250px",
       scrollCollapse: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url:'{{ url("kompetitor/pembagian") }}',
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
            return '<input type="hidden" name="kode_company[' + $('<div />').text(row.kompid).html() + ']" value="' + $('<div />').text(row.kode_company).html() + '">' + $('<div />').text(row.company).html();
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
            return '<input type="checkbox" name="pilih_sales[' + $('<div />').text(row.kompid).html() + ']" value="1"><input type="hidden" name="kompid[' + $('<div />').text(row.kompid).html() + ']" value="' + $('<div />').text(row.kompid).html() + '">';
         }
        },
        {
         data:null,
         name:null,
         className:'dt-center',
         width:'5%',
         render: function ( data, type, row)
         {
            return '<input type="checkbox" name="offline[' + $('<div />').text(row.kompid).html() + ']" value="1">';
         }
        },
        {
         data:null,
         name:null,
         className:'dt-center',
         width:'15%',
         render: function ( data, type, row)
         {
            return '<select name="perihal[' + $('<div />').text(row.kompid).html() + ']" style="width:100%;">' +
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
            $('[name="tanggal_kunjungan[' + $('<div />').text(row.kompid).html() + ']"]').flatpickr({
              allowInput: true,
              disableMobile: true
            });

            return '<input type="text" name="tanggal_kunjungan[' + $('<div />').text(row.kompid).html() + ']" style="width:100%; margin-bottom: 10px;" placeholder="Tanggal Kunjungan">';
         }
        }
       ]
      });
     }

     function load_data_list_kompetitor(from_date = '', to_date = '')
     {
      if(any_nomor != ''){
        table = $('#kompetitor_list_table').DataTable({
           processing: true,
           serverSide: true,
           responsive: {
            details: {
              type: 'column'
            }
          },
           lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
           ajax: {
            url:'{{ url("kompetitor/list/view/table") }}',
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            },
            data : function( d ) {
              d.nomor = any_nomor;
            }
          },
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_data');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
          },
          columns: [
          {
            className: 'control dt-center',
            orderable: false,
            targets: 0,
            defaultContent:''
          },
          {
           data:'DT_RowIndex',
           name:'DT_RowIndex',
           className:'dt-center'
         },
         {
           data:'kompid',
           name:'kompid',
           className:'dt-center'
         },
         {
           data:'name',
           name:'name',
           className:'dt-center'
         },
         {
           data:'bidang_usaha',
           name:'bidang_usaha',
           className:'dt-center',
           defaultContent:'--'
         }
         ]
       });
      }else{
          table = $('#kompetitor_list_table').DataTable({
           processing: true,
           serverSide: true,
           responsive: {
            details: {
              type: 'column'
            }
          },
           lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
           ajax: {
            url:'{{ url("kompetitor/list/view/table") }}',
            data:{from_date:from_date, to_date:to_date},
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
          },
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
          createdRow: function (row, data, dataIndex) {
            $('td', row).attr('data-toggle', 'modal');
            $('td', row).attr('data-target', '#modal_lihat_data');
            $('td', row).eq(0).removeAttr('data-toggle');
            $('td', row).eq(0).removeAttr('data-target');
          },
          columns: [
          {
            className: 'control dt-center',
            orderable: false,
            targets: 0,
            defaultContent:''
          },
          {
           data:'DT_RowIndex',
           name:'DT_RowIndex',
           className:'dt-center'
         },
         {
           data:'kompid',
           name:'kompid',
           className:'dt-center'
         },
         {
           data:'name',
           name:'name',
           className:'dt-center'
         },
         {
           data:'bidang_usaha',
           name:'bidang_usaha',
           className:'dt-center',
           defaultContent:'--'
         }
         ]
       });
      }
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

    $('#kompetitor_list_table').on( 'click', 'tbody tr', function () {
      var cust = table.row(this).data();
      var kompid = cust['kompid'];
      document.getElementById("detail_judul").innerHTML = "Detail Kompetitor : " + kompid;
      var url = "{{ url('kompetitor/view/kompid') }}";
      url = url.replace('kompid', enc(kompid.toString()));
      $('#td_kompid').html('');
      $('#td_nama').html('');
      $('#td_crd').html('');
      $('#td_pic').html('');
      $('#td_jabatan_pic').html('');
      $('#td_bidang_usaha').html('');
      $('#td_address').html('');
      $('#td_city').html('');
      $('#td_phone').html('');
      $('#td_fax').html('');
      $('#td_npwp').html('');
      $('#td_nik').html('');
      $('#td_created_at').html('');
      $('#td_created_by').html('');
      $('#td_updated_at').html('');
      $.get(url, function (data) {
        $('#td_kompid').html(data.kompid);
        $('#td_nama').html(data.name);
        $('#td_company').html(data.company);
        if(data.nama_cp){
          $('#td_pic').html(data.nama_cp);
        }else{
          $('#td_pic').html('--');
        }
        if(data.crd){
          $('#td_crd').html(data.crd);
        }else{
          $('#td_crd').html('--');
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
        $('#td_address').html(data.address);
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
        if(data.npwp == null){
          $('#td_npwp').html('--');
        }else if(data.image_npwp == null){
          $('#td_npwp').html(data.npwp);
        }else{
          $('#td_npwp').html(data.npwp + ' ' + '(<a target="_blank" href="' + '../data_file/' + data.image_npwp + '">Lihat Foto</a>)');
        }
        if(data.nik){
          $('#td_nik').html(data.nik + ' ' + '(<a target="_blank" href="' + '../data_file/' + data.image_nik + '">Lihat Foto</a>)');
        }else{
          $('#td_nik').html('--');
        }
        $('#td_created_at').html(data.created_at);
        $('#td_created_by').html(data.created_by);
        $('#td_updated_at').html(data.updated_at);
        $('#btn-edit-cust').data('id', data.kompid);
        $('#btn-delete-cust').data('id', data.kompid);
        $('#btn-history-cust').data('id', data.kompid);
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
    
    $('#btn-edit-cust').click(function (e) {
      var komp_id_edit = $(this).data("id");
      document.getElementById("modal-edit-title").innerHTML = "Edit Kompetitor " + komp_id_edit;

      var url_company = "{{ url('get_company') }}";
      $.get(url_company, function (data) {
        $('#company_edit').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company_edit').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })

      var url = "{{ url('kompetitor/view/kompid') }}";
      url = url.replace('kompid', enc(komp_id_edit.toString()));
      $('#kompid_edit').val('');
      $('#name_edit').val('');
      $('#address_edit').html('');
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
      $('#upload_image_npwp_edit').html('');
      $('#upload_image_ktp_edit').html('');
      $.get(url, function (data) {
        $('#kompid_edit').val(data.kompid);
        $('#name_edit').val(data.name);
        $('#address_edit').html(data.address);
        $('#phone_edit').val(data.phone);
        $('#fax_edit').val(data.fax);
        $('#npwp_edit').val(data.npwp);
        $('#nik_edit').val(data.nik);
        $('#crd_edit').val(data.crd);
        $('#nama_cp_edit').val(data.nama_cp);
        $('#jabatan_cp_edit').val(data.jabatan_cp);
        $('#bidang_usaha_edit').val(data.bidang_usaha);
        $('#company_edit').val(data.company);
        $('#city_edit').html('<option value="' + data.city_name + '" selected>' + data.city + '</option>');
        if(data.image_npwp == null){
          $('#lihat_npwp_edit').html('No NPWP Image');
          $('#lihat_npwp_edit').addClass('disabled');
          $('#lihat_npwp_edit').attr('href', '#');
        }else{
          $('#lihat_npwp_edit').html('Lihat Foto NPWP');
          $('#lihat_npwp_edit').removeClass('disabled');
          $('#lihat_npwp_edit').attr('href', '../data_file/' + data.image_npwp);
        }
        if(data.image_nik == null){
          $('#lihat_ktp_edit').html('No KTP Image');
          $('#lihat_ktp_edit').addClass('disabled');
          $('#lihat_ktp_edit').attr('href', '#');
        }else{
          $('#lihat_ktp_edit').html('Lihat Foto KTP');
          $('#lihat_ktp_edit').removeClass('disabled');
          $('#lihat_ktp_edit').attr('href', '../data_file/' + data.image_nik);
        }
      })
    });

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
      if(from_date != '' &&  to_date != '')
      {
        $('#kompetitor_list_table').DataTable().destroy();
        load_data_list_kompetitor(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#kompetitor_list_table').DataTable().destroy();
      load_data_list_kompetitor(from_date, to_date);
    });

    $('#btn-save-data').click( function() {
      var data = $('#pembagian_sales_table input, #pembagian_sales_table select, #sales').serialize();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('#btn-save-data').data('token'),
        },
        type: "POST",
        url: '{{ url("kompetitor/pembagian/save") }}',
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
  });
</script>

<script type="text/javascript">
  $(function () {
    $('#filter_tanggal').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $('#tanggal_followup').flatpickr({
      allowInput: true,
      disableMobile: true
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

<script type="text/javascript">
  $(document).ready(function(){
    $('body').on('click', '#btn-delete-cust', function () {
      var kompid = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('kompetitor/reject') }}",
          data: { 'kompid' : kompid },
          success: function (data) {
            var oTable = $('#kompetitor_list_table').dataTable(); 
            oTable.fnDraw(false);
            $('#modal_lihat_data').modal('hide');
            $("#modal_lihat_data").trigger('click');
            alert("Data Deleted");
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
    $('.city').select2({
      dropdownParent: $('#modal_input_data .modal-content'),
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
      dropdownParent: $('#modal_edit_data .modal-content'),
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
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    $('#input_data_form').validate({
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
        crd: {
          required: true,
        },
        phone: {
          required: true,
        },
        fax: {
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
        phone: {
          required: "Phone Harus Diisi",
        },
        fax: {
          required: "Fax Harus Diisi",
        },
        city: {
          required: "Address City Harus Diisi",
        },
        crd: {
          required: "CRD Harus Diisi",
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
        var myform = document.getElementById("input_data_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('kompetitor/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            if(data.status == false){
              alert(data.msg);
              $('#response-input-data').removeClass("alert alert-danger");
              $('#response-input-data').hide();
            }else{
              $('#response-input-data').removeClass("alert alert-danger");
              $('#response-input-data').hide();
              $('#input_data_form').trigger("reset");
              var oTable = $('#kompetitor_list_table').dataTable();
              oTable.fnDraw(false);
              $('#modal_input_data').modal('hide');
              $("#modal_input_data").trigger('click');
              alert("Kompetitor Data Successfully Added");
            }
          },
          error: function (data) {
            console.log('Error:', data);
            if( data.status === 422 ) {
              var errors = $.parseJSON(data.responseText);
              $.each(errors, function (key, value) {
                $('#response-input-data').addClass("alert alert-danger");
                if($.isPlainObject(value)) {
                  $.each(value, function (key, value) {                       
                    $('#response-input-data').show().append(value+"<br/>");
                  });
                }else{
                  $('#response-input-data').show().append(value+"<br/>");
                }
              });
            }
          }
        });
      }
    });

    $('#edit_data_form').validate({
      rules: {
        name_edit: {
          required: true,
        },
        address_edit: {
          required: true,
        },
        city_edit: {
          required: true,
        },
        crd_edit: {
          required: true,
        },
        phone_edit: {
          required: true,
        },
        fax_edit: {
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
        address_edit: {
          required: "Address Harus Diisi",
        },
        crd_edit: {
          required: "CRD Harus Diisi",
        },
        phone_edit: {
          required: "Phone Harus Diisi",
        },
        fax_edit: {
          required: "Fax Harus Diisi",
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
        var myform = document.getElementById("edit_data_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('/kompetitor/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            if(data.status == false){
              alert(data.msg);
              $('#response-edit-data').removeClass("alert alert-danger");
              $('#response-edit-data').hide();
            }else{
              $('#response-edit-data').removeClass("alert alert-danger");
              $('#response-edit-data').hide();
              $('#edit_data_form').trigger("reset");
              var oTable = $('#kompetitor_list_table').dataTable();
              oTable.fnDraw(false);
              $('#modal_edit_data').modal('hide');
              $("#modal_edit_data").trigger('click');
              alert("Non Customers Data Successfully Updated");
            }
          },
          error: function (data) {
            console.log('Error:', data);
            if( data.status === 422 ) {
              var errors = $.parseJSON(data.responseText);
              $.each(errors, function (key, value) {
                $('#response-edit-data').addClass("alert alert-danger");
                if($.isPlainObject(value)) {
                  $.each(value, function (key, value) {                       
                    $('#response-edit-data').show().append(value+"<br/>");
                  });
                }else{
                  $('#response-edit-data').show().append(value+"<br/>");
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

<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>

@endsection
