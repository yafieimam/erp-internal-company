@extends('layouts.app_admin')

@section('title')
<title>LAPORAN HASIL PRODUKSI - PT. DWI SELO GIRI MAS</title>
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
  #laporan_produksi_table tbody tr:hover{
    cursor: pointer;
  }
  .filter-btn {
    margin-top: 32px;
  }
  td.details-control {
    background: url("{{asset('app-assets/images/icons/details_open.png')}}") no-repeat center center;
    cursor: pointer;
  }
  tr.shown td.details-control {
      background: url("{{asset('app-assets/images/icons/details_close.png')}}") no-repeat center center;
  }
  
  /* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 999;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  width: 50px;
  height: 50px;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255,255,255,0.5);
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 50px;
  height: 50px;
  margin-top: -0.5em;

  border: 15px solid rgba(33, 150, 243, 1.0);
  border-radius: 100%;
  border-bottom-color: transparent;
  -webkit-animation: spinner 1s linear 0s infinite;
  animation: spinner 1s linear 0s infinite;


}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
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
    #dynamic_field_laporan_produksi th {
      display: none;
    }
    #dynamic_field_laporan_produksi td {
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
          <h1 class="m-0 text-dark">Laporan Hasil Produksi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Produksi</li>
            <li class="breadcrumb-item">Laporan Hasil Produksi</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  @endsection

  @section('content')
  <div class="loading" style="display: none;">Loading&#8230;</div>
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
              <button type="button" name="btn_upload_excel" id="btn_upload_excel" class="btn btn-block btn-primary" >Upload Excel</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="laporan_produksi_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th class="min-mobile" style="width: 5%;"></th>
                <th class="min-mobile" style="width: 5%;"></th>
                <th class="not-mobile">No</th>
                <th>Tanggal</th>
                <th>Total Sak</th>
                <th>Total Tonase</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_upload_excel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Excel</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" class="upload-form" id="upload-form" action="{{ url('laporan_produksi/upload_excel') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload_excel" id="upload_excel">
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
            </div>
            <p style="font-weight: 700;">Format File Allowed only .xlsx and Template must be same with template below.</p>
            <span style="font-weight: 700;">Download file excel template <a href="{{asset('template/excel/template_laporan_hasil_produksi.xlsx')}}" target="_blank">here</a>.</span>
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
  
  <div class="modal fade" id="modal_input_laporan_produksi">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Laporan Harian</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" class="laporan_produksi_form" id="laporan_produksi_form" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Tanggal Laporan Produksi</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="tanggal_laporan_produksi" id="tanggal_laporan_produksi" autocomplete="off" placeholder="Tanggal Laporan Produksi" readonly>
                </div>
                <!-- /.input group -->
                <input type="hidden" class="form-control" name="nomor_laporan_produksi" id="nomor_laporan_produksi">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="referensi">Referensi</label>
                <input type="text" class="form-control" name="referensi" id="referensi" placeholder="Referensi">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_sa">  
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" width="10%">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;" width="40%">Jenis Produk</th>
                    <th style="vertical-align : middle; text-align: center;">Jumlah Sak</th>
                    <th style="vertical-align : middle; text-align: center;">Jumlah Tonase</th>
                    <th style="vertical-align : middle; text-align: center;" width="5%"></th>
                  </tr>
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" id="mesin_sa" rowspan="1">SA</th>
                    <td><select name="jenis_produk_sa[]" id="select_jenis_produk_sa1" class="form-control jenis_produk_list_sa"></select></td>
                    <td><input type="text" name="jumlah_sak_sa[]" placeholder="Jumlah Sak" id="jumlah_sak_sa1" class="form-control jumlah_sak_list_sa" /><input type="hidden" name="weight_sa[]" id="weight_sa1" class="form-control weight_list_sa" /></td>
                    <td><input type="text" name="jumlah_tonase_sa[]" placeholder="Jumlah Tonase" id="jumlah_tonase_sa1" class="form-control jumlah_tonase_list_sa" /></td>  
                    <td style="vertical-align : middle; text-align: center;"><button type="button" name="add_data_laporan_sa" id="add_data_laporan_sa" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>  
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_sb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_sb" rowspan="1">SB</th>
                    <td width="40%"><select name="jenis_produk_sb[]" id="select_jenis_produk_sb1" class="form-control jenis_produk_list_sb"></select></td>
                    <td><input type="text" name="jumlah_sak_sb[]" placeholder="Jumlah Sak" id="jumlah_sak_sb1" class="form-control jumlah_sak_list_sb" /><input type="hidden" name="weight_sb[]" id="weight_sb1" class="form-control weight_list_sb" /></td>
                    <td><input type="text" name="jumlah_tonase_sb[]" placeholder="Jumlah Tonase" id="jumlah_tonase_sb1" class="form-control jumlah_tonase_list_sb" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_sb" id="add_data_laporan_sb" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_mixer">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_mixer" rowspan="1">Mixer</th>
                    <td width="40%"><select name="jenis_produk_mixer[]" id="select_jenis_produk_mixer1" class="form-control jenis_produk_list_mixer"></select></td>
                    <td><input type="text" name="jumlah_sak_mixer[]" placeholder="Jumlah Sak" id="jumlah_sak_mixer1" class="form-control jumlah_sak_list_mixer" /><input type="hidden" name="weight_mixer[]" id="weight_mixer1" class="form-control weight_list_mixer" /></td>
                    <td><input type="text" name="jumlah_tonase_mixer[]" placeholder="Jumlah Tonase" id="jumlah_tonase_mixer1" class="form-control jumlah_tonase_list_mixer" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_mixer" id="add_data_laporan_mixer" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr> 
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_ra">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_ra" rowspan="1">RA</th>
                    <td width="40%"><select name="jenis_produk_ra[]" id="select_jenis_produk_ra1" class="form-control jenis_produk_list_ra"></select></td>
                    <td><input type="text" name="jumlah_sak_ra[]" placeholder="Jumlah Sak" id="jumlah_sak_ra1" class="form-control jumlah_sak_list_ra" /><input type="hidden" name="weight_ra[]" id="weight_ra1" class="form-control weight_list_ra" /></td>
                    <td><input type="text" name="jumlah_tonase_ra[]" placeholder="Jumlah Tonase" id="jumlah_tonase_ra1" class="form-control jumlah_tonase_list_ra" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_ra" id="add_data_laporan_ra" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_rb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rb" rowspan="1">RB</th>
                    <td width="40%"><select name="jenis_produk_rb[]" id="select_jenis_produk_rb1" class="form-control jenis_produk_list_rb"></select></td>
                    <td><input type="text" name="jumlah_sak_rb[]" placeholder="Jumlah Sak" id="jumlah_sak_rb1" class="form-control jumlah_sak_list_rb" /><input type="hidden" name="weight_rb[]" id="weight_rb1" class="form-control weight_list_rb" /></td>
                    <td><input type="text" name="jumlah_tonase_rb[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rb1" class="form-control jumlah_tonase_list_rb" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_rb" id="add_data_laporan_rb" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_rc">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rc" rowspan="1">RC</th>
                    <td width="40%"><select name="jenis_produk_rc[]" id="select_jenis_produk_rc1" class="form-control jenis_produk_list_rc"></select></td>
                    <td><input type="text" name="jumlah_sak_rc[]" placeholder="Jumlah Sak" id="jumlah_sak_rc1" class="form-control jumlah_sak_list_rc" /><input type="hidden" name="weight_rc[]" id="weight_rc1" class="form-control weight_list_rc" /></td>
                    <td><input type="text" name="jumlah_tonase_rc[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rc1" class="form-control jumlah_tonase_list_rc" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_rc" id="add_data_laporan_rc" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_rd">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rd" rowspan="1">RD</th>
                    <td width="40%"><select name="jenis_produk_rd[]" id="select_jenis_produk_rd1" class="form-control jenis_produk_list_rd"></select></td>
                    <td><input type="text" name="jumlah_sak_rd[]" placeholder="Jumlah Sak" id="jumlah_sak_rd1" class="form-control jumlah_sak_list_rd" /><input type="hidden" name="weight_rd[]" id="weight_rd1" class="form-control weight_list_rd" /></td>
                    <td><input type="text" name="jumlah_tonase_rd[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rd1" class="form-control jumlah_tonase_list_rd" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_rd" id="add_data_laporan_rd" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_re">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_re" rowspan="1">RE</th>
                    <td width="40%"><select name="jenis_produk_re[]" id="select_jenis_produk_re1" class="form-control jenis_produk_list_re"></select></td>
                    <td><input type="text" name="jumlah_sak_re[]" placeholder="Jumlah Sak" id="jumlah_sak_re1" class="form-control jumlah_sak_list_re" /><input type="hidden" name="weight_re[]" id="weight_re1" class="form-control weight_list_re" /></td>
                    <td><input type="text" name="jumlah_tonase_re[]" placeholder="Jumlah Tonase" id="jumlah_tonase_re1" class="form-control jumlah_tonase_list_re" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_re" id="add_data_laporan_re" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_rf">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rf" rowspan="1">RF</th>
                    <td width="40%"><select name="jenis_produk_rf[]" id="select_jenis_produk_rf1" class="form-control jenis_produk_list_rf"></select></td>
                    <td><input type="text" name="jumlah_sak_rf[]" placeholder="Jumlah Sak" id="jumlah_sak_rf1" class="form-control jumlah_sak_list_rf" /><input type="hidden" name="weight_rf[]" id="weight_rf1" class="form-control weight_list_rf" /></td>
                    <td><input type="text" name="jumlah_tonase_rf[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rf1" class="form-control jumlah_tonase_list_rf" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_rf" id="add_data_laporan_rf" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_laporan_produksi_rg">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rg" rowspan="1">RG</th>
                    <td width="40%"><select name="jenis_produk_rg[]" id="select_jenis_produk_rg1" class="form-control jenis_produk_list_rg"></select></td>
                    <td><input type="text" name="jumlah_sak_rg[]" placeholder="Jumlah Sak" id="jumlah_sak_rg1" class="form-control jumlah_sak_list_rg" /><input type="hidden" name="weight_rg[]" id="weight_rg1" class="form-control weight_list_rg" /></td>
                    <td><input type="text" name="jumlah_tonase_rg[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rg1" class="form-control jumlah_tonase_list_rg" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_laporan_rg" id="add_data_laporan_rg" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table> 
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

  <div class="modal fade" id="modal_lihat_hasil_lab">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title_lihat_hasil_lab"></h4>
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
                    <th style="vertical-align : middle; text-align: center;">Standart</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Standart</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Standart</th>
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

  <div class="modal fade" id="modal_view_data_mesin">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data Mesin</h4>
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
                  <th id="td_tanggal_mesin"></th>
                  <th>Referensi : </th>
                  <th id="td_referensi_mesin"></th>
                </tr>
              </table>

              <table style="width: 32%; font-size: 11px; float:left;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" colspan="3">Data Keterangan "Major"</th>
                  </tr>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">Mesin</th>
                    <th style="width: 5%; vertical-align : middle; text-align: center;">Tonase (KG)</th>
                    <th style="vertical-align : middle; text-align: center;">Keterangan</th>
                  </tr>
                </thead>
                <tbody id="tbody_view_mesin_major">
                </tbody>
              </table>

              <table style="width: 32%; font-size: 11px; margin-left: 10px; float:left;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" colspan="3">Data Keterangan "Minor"</th>
                  </tr>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">Mesin</th>
                    <th style="width: 5%; vertical-align : middle; text-align: center;">Tonase (KG)</th>
                    <th style="vertical-align : middle; text-align: center;">Keterangan</th>
                  </tr>
                </thead>
                <tbody id="tbody_view_mesin_minor">
                </tbody>
              </table>

              <table style="width: 32%; font-size: 11px; margin-left: 10px; float:left;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" colspan="3">Data Keterangan "Lain-Lain"</th>
                  </tr>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">Mesin</th>
                    <th style="width: 5%; vertical-align : middle; text-align: center;">Tonase (KG)</th>
                    <th style="vertical-align : middle; text-align: center;">Keterangan</th>
                  </tr>
                </thead>
                <tbody id="tbody_view_mesin_lain">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <a href="#" class="btn btn-primary" id="btn-save-excel-mesin">Download Excel</a>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_lihat_laporan_produksi">
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

  <div class="modal fade" id="modal_edit_laporan_produksi">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title_edit_laporan_produksi"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" class="edit_laporan_produksi_form" id="edit_laporan_produksi_form" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Tanggal Laporan Produksi</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="edit_tanggal_laporan_produksi" id="edit_tanggal_laporan_produksi" autocomplete="off" placeholder="Tanggal Laporan Produksi" readonly>
                </div>
                <!-- /.input group -->
                <input type="hidden" class="form-control" name="edit_nomor_laporan_produksi" id="edit_nomor_laporan_produksi">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="edit_referensi">Referensi</label>
                <input type="text" class="form-control" name="edit_referensi" id="edit_referensi" placeholder="Referensi">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_sa">  
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" width="10%">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;" width="40%">Jenis Produk</th>
                    <th style="vertical-align : middle; text-align: center;">Jumlah Sak</th>
                    <th style="vertical-align : middle; text-align: center;">Jumlah Tonase</th>
                    <th style="vertical-align : middle; text-align: center;" width="5%"></th>
                  </tr>
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" id="edit_mesin_sa" rowspan="1">SA</th>
                    <td><select name="edit_jenis_produk_sa[]" id="edit_select_jenis_produk_sa1" class="form-control edit_jenis_produk_list_sa"></select><input type="hidden" name="edit_jenis_produk_lama_sa[]" id="edit_jenis_produk_lama_sa1" class="form-control edit_jumlah_sak_lama_list_sa" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_sa1" id="edit_nomor_laporan_produksi_detail_sa1" class="form-control edit_nomor_laporan_produksi_detail_list_sa" /></td>
                    <td><input type="text" name="edit_jumlah_sak_sa[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_sa1" class="form-control edit_jumlah_sak_list_sa" /><input type="hidden" name="edit_weight_sa[]" id="edit_weight_sa1" class="form-control edit_weight_list_sa" /><input type="hidden" name="edit_jumlah_sak_lama_sa[]" id="edit_jumlah_sak_lama_sa1" class="form-control edit_jumlah_sak_list_lama_sa" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_sa[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_sa1" class="form-control edit_jumlah_tonase_list_sa" /><input type="hidden" name="edit_jumlah_tonase_lama_sa[]" id="edit_jumlah_tonase_lama_sa1" class="form-control edit_jumlah_tonase_list_lama_sa" /></td>  
                    <td style="vertical-align : middle; text-align: center;"><button type="button" name="edit_add_data_laporan_sa" id="edit_add_data_laporan_sa" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>  
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_sb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_sb" rowspan="1">SB</th>
                    <td width="40%"><select name="edit_jenis_produk_sb[]" id="edit_select_jenis_produk_sb1" class="form-control edit_jenis_produk_list_sb"></select><input type="hidden" name="edit_jenis_produk_lama_sb[]" id="edit_jenis_produk_lama_sb1" class="form-control edit_jumlah_sak_lama_list_sb" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_sb1" id="edit_nomor_laporan_produksi_detail_sb1" class="form-control edit_nomor_laporan_produksi_detail_list_sb" /></td>
                    <td><input type="text" name="edit_jumlah_sak_sb[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_sb1" class="form-control edit_jumlah_sak_list_sb" /><input type="hidden" name="edit_weight_sb[]" id="edit_weight_sb1" class="form-control edit_weight_list_sb" /><input type="hidden" name="edit_jumlah_sak_lama_sb[]" id="edit_jumlah_sak_lama_sb1" class="form-control edit_jumlah_sak_list_lama_sb" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_sb[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_sb1" class="form-control edit_jumlah_tonase_list_sb" /><input type="hidden" name="edit_jumlah_tonase_lama_sb[]" id="edit_jumlah_tonase_lama_sb1" class="form-control edit_jumlah_tonase_list_lama_sb" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_sb" id="edit_add_data_laporan_sb" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_mixer">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_mixer" rowspan="1">Mixer</th>
                    <td width="40%"><select name="edit_jenis_produk_mixer[]" id="edit_select_jenis_produk_mixer1" class="form-control edit_jenis_produk_list_mixer"></select><input type="hidden" name="edit_jenis_produk_lama_mixer[]" id="edit_jenis_produk_lama_mixer1" class="form-control edit_jumlah_sak_lama_list_mixer" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_mixer1" id="edit_nomor_laporan_produksi_detail_mixer1" class="form-control edit_nomor_laporan_produksi_detail_list_mixer" /></td>
                    <td><input type="text" name="edit_jumlah_sak_mixer[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_mixer1" class="form-control edit_jumlah_sak_list_mixer" /><input type="hidden" name="edit_weight_mixer[]" id="edit_weight_mixer1" class="form-control edit_weight_list_mixer" /><input type="hidden" name="edit_jumlah_sak_lama_mixer[]" id="edit_jumlah_sak_lama_mixer1" class="form-control edit_jumlah_sak_list_lama_mixer" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_mixer[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_mixer1" class="form-control edit_jumlah_tonase_list_mixer" /><input type="hidden" name="edit_jumlah_tonase_lama_mixer[]" id="edit_jumlah_tonase_lama_mixer1" class="form-control edit_jumlah_tonase_list_lama_mixer" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_mixer" id="edit_add_data_laporan_mixer" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table> 
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_ra">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_ra" rowspan="1">RA</th>
                    <td width="40%"><select name="edit_jenis_produk_ra[]" id="edit_select_jenis_produk_ra1" class="form-control edit_jenis_produk_list_ra"></select><input type="hidden" name="edit_jenis_produk_lama_ra[]" id="edit_jenis_produk_lama_ra1" class="form-control edit_jumlah_sak_lama_list_ra" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_ra1" id="edit_nomor_laporan_produksi_detail_ra1" class="form-control edit_nomor_laporan_produksi_detail_list_ra" /></td>
                    <td><input type="text" name="edit_jumlah_sak_ra[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_ra1" class="form-control edit_jumlah_sak_list_ra" /><input type="hidden" name="edit_weight_ra[]" id="edit_weight_ra1" class="form-control edit_weight_list_ra" /><input type="hidden" name="edit_jumlah_sak_lama_ra[]" id="edit_jumlah_sak_lama_ra1" class="form-control edit_jumlah_sak_list_lama_ra" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_ra[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_ra1" class="form-control edit_jumlah_tonase_list_ra" /><input type="hidden" name="edit_jumlah_tonase_lama_ra[]" id="edit_jumlah_tonase_lama_ra1" class="form-control edit_jumlah_tonase_list_lama_ra" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_ra" id="edit_add_data_laporan_ra" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_rb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rb" rowspan="1">RB</th>
                    <td width="40%"><select name="edit_jenis_produk_rb[]" id="edit_select_jenis_produk_rb1" class="form-control edit_jenis_produk_list_rb"></select><input type="hidden" name="edit_jenis_produk_lama_rb[]" id="edit_jenis_produk_lama_rb1" class="form-control edit_jumlah_sak_lama_list_rb" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_rb1" id="edit_nomor_laporan_produksi_detail_rb1" class="form-control edit_nomor_laporan_produksi_detail_list_rb" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rb[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rb1" class="form-control edit_jumlah_sak_list_rb" /><input type="hidden" name="edit_weight_rb[]" id="edit_weight_rb1" class="form-control edit_weight_list_rb" /><input type="hidden" name="edit_jumlah_sak_lama_rb[]" id="edit_jumlah_sak_lama_rb1" class="form-control edit_jumlah_sak_list_lama_rb" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rb[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rb1" class="form-control edit_jumlah_tonase_list_rb" /><input type="hidden" name="edit_jumlah_tonase_lama_rb[]" id="edit_jumlah_tonase_lama_rb1" class="form-control edit_jumlah_tonase_list_lama_rb" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_rb" id="edit_add_data_laporan_rb" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_rc">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rc" rowspan="1">RC</th>
                    <td width="40%"><select name="edit_jenis_produk_rc[]" id="edit_select_jenis_produk_rc1" class="form-control edit_jenis_produk_list_rc"></select><input type="hidden" name="edit_jenis_produk_lama_rc[]" id="edit_jenis_produk_lama_rc1" class="form-control edit_jumlah_sak_lama_list_rc" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_rc1" id="edit_nomor_laporan_produksi_detail_rc1" class="form-control edit_nomor_laporan_produksi_detail_list_rc" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rc[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rc1" class="form-control edit_jumlah_sak_list_rc" /><input type="hidden" name="edit_weight_rc[]" id="edit_weight_rc1" class="form-control edit_weight_list_rc" /><input type="hidden" name="edit_jumlah_sak_lama_rc[]" id="edit_jumlah_sak_lama_rc1" class="form-control edit_jumlah_sak_list_lama_rc" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rc[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rc1" class="form-control edit_jumlah_tonase_list_rc" /><input type="hidden" name="edit_jumlah_tonase_lama_rc[]" id="edit_jumlah_tonase_lama_rc1" class="form-control edit_jumlah_tonase_list_lama_rc" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_rc" id="edit_add_data_laporan_rc" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_rd">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rd" rowspan="1">RD</th>
                    <td width="40%"><select name="edit_jenis_produk_rd[]" id="edit_select_jenis_produk_rd1" class="form-control edit_jenis_produk_list_rd"></select><input type="hidden" name="edit_jenis_produk_lama_rd[]" id="edit_jenis_produk_lama_rd1" class="form-control edit_jumlah_sak_lama_list_rd" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_rd1" id="edit_nomor_laporan_produksi_detail_rd1" class="form-control edit_nomor_laporan_produksi_detail_list_rd" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rd[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rd1" class="form-control edit_jumlah_sak_list_rd" /><input type="hidden" name="edit_weight_rd[]" id="edit_weight_rd1" class="form-control edit_weight_list_rd" /><input type="hidden" name="edit_jumlah_sak_lama_rd[]" id="edit_jumlah_sak_lama_rd1" class="form-control edit_jumlah_sak_list_lama_rd" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rd[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rd1" class="form-control edit_jumlah_tonase_list_rd" /><input type="hidden" name="edit_jumlah_tonase_lama_rd[]" id="edit_jumlah_tonase_lama_rd1" class="form-control edit_jumlah_tonase_list_lama_rd" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_rd" id="edit_add_data_laporan_rd" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_re">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_re" rowspan="1">RE</th>
                    <td width="40%"><select name="edit_jenis_produk_re[]" id="edit_select_jenis_produk_re1" class="form-control edit_jenis_produk_list_re"></select><input type="hidden" name="edit_jenis_produk_lama_re[]" id="edit_jenis_produk_lama_re1" class="form-control edit_jumlah_sak_lama_list_re" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_re1" id="edit_nomor_laporan_produksi_detail_re1" class="form-control edit_nomor_laporan_produksi_detail_list_re" /></td>
                    <td><input type="text" name="edit_jumlah_sak_re[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_re1" class="form-control edit_jumlah_sak_list_re" /><input type="hidden" name="edit_weight_re[]" id="edit_weight_re1" class="form-control edit_weight_list_re" /><input type="hidden" name="edit_jumlah_sak_lama_re[]" id="edit_jumlah_sak_lama_re1" class="form-control edit_jumlah_sak_list_lama_re" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_re[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_re1" class="form-control edit_jumlah_tonase_list_re" /><input type="hidden" name="edit_jumlah_tonase_lama_re[]" id="edit_jumlah_tonase_lama_re1" class="form-control edit_jumlah_tonase_list_lama_re" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_re" id="edit_add_data_laporan_re" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_rf">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rf" rowspan="1">RF</th>
                    <td width="40%"><select name="edit_jenis_produk_rf[]" id="edit_select_jenis_produk_rf1" class="form-control edit_jenis_produk_list_rf"></select><input type="hidden" name="edit_jenis_produk_lama_rf[]" id="edit_jenis_produk_lama_rf1" class="form-control edit_jumlah_sak_lama_list_rf" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_rf1" id="edit_nomor_laporan_produksi_detail_rf1" class="form-control edit_nomor_laporan_produksi_detail_list_rf" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rf[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rf1" class="form-control edit_jumlah_sak_list_rf" /><input type="hidden" name="edit_weight_rf[]" id="edit_weight_rf1" class="form-control edit_weight_list_rf" /><input type="hidden" name="edit_jumlah_sak_lama_rf[]" id="edit_jumlah_sak_lama_rf1" class="form-control edit_jumlah_sak_list_lama_rf" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rf[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rf1" class="form-control edit_jumlah_tonase_list_rf" /><input type="hidden" name="edit_jumlah_tonase_lama_rf[]" id="edit_jumlah_tonase_lama_rf1" class="form-control edit_jumlah_tonase_list_lama_rf" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_rf" id="edit_add_data_laporan_rf" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_laporan_produksi_rg">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rg" rowspan="1">RG</th>
                    <td width="40%"><select name="edit_jenis_produk_rg[]" id="edit_select_jenis_produk_rg1" class="form-control edit_jenis_produk_list_rg"></select><input type="hidden" name="edit_jenis_produk_lama_rg[]" id="edit_jenis_produk_lama_rg1" class="form-control edit_jumlah_sak_lama_list_rg" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_rg1" id="edit_nomor_laporan_produksi_detail_rg1" class="form-control edit_nomor_laporan_produksi_detail_list_rg" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rg[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rg1" class="form-control edit_jumlah_sak_list_rg" /><input type="hidden" name="edit_weight_rg[]" id="edit_weight_rg1" class="form-control edit_weight_list_rg" /><input type="hidden" name="edit_jumlah_sak_lama_rg[]" id="edit_jumlah_sak_lama_rg1" class="form-control edit_jumlah_sak_list_lama_rg" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rg[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rg1" class="form-control edit_jumlah_tonase_list_rg" /><input type="hidden" name="edit_jumlah_tonase_lama_rg[]" id="edit_jumlah_tonase_lama_rg1" class="form-control edit_jumlah_tonase_list_lama_rg" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_laporan_rg" id="edit_add_data_laporan_rg" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
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

    $('.select2').select2();
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var c_laporan_sa = 0;
    var c_laporan_sb = 0;
    var c_laporan_mixer = 0;
    var c_laporan_ra = 0;
    var c_laporan_rb = 0;
    var c_laporan_rc = 0;
    var c_laporan_rd = 0;
    var c_laporan_re = 0;
    var c_laporan_rf = 0;
    var c_laporan_rg = 0;

    var edit_c_laporan_sa = 0;
    var edit_c_laporan_sb = 0;
    var edit_c_laporan_mixer = 0;
    var edit_c_laporan_ra = 0;
    var edit_c_laporan_rb = 0;
    var edit_c_laporan_rc = 0;
    var edit_c_laporan_rd = 0;
    var edit_c_laporan_re = 0;
    var edit_c_laporan_rf = 0;
    var edit_c_laporan_rg = 0;

    var table = $('#laporan_produksi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("laporan_produksi/view_laporan_produksi_table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[3,'desc']],
        columns: [
        {
          className: 'control dt-center',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
          
          className:'details-control dt-center',
          orderable:false,
          searchable:false,
          data:null,
          defaultContent:'',
        },
        {
         data:'DT_RowIndex',
         name:'DT_RowIndex',
         width: '7%',
         className:'dt-center'
       },
       {
         data:'tanggal_laporan_produksi',
         name:'tanggal_laporan_produksi',
         className:'dt-center'
       },
       {
         data:'total_sak',
         name:'total_sak',
         defaultContent: '---',
         className:'dt-center',
         render: $.fn.dataTable.render.number('.', " Sak", ',')
       },
       {
         data:'total_tonase',
         name:'total_tonase',
         defaultContent: '---',
         className:'dt-center',
         render: $.fn.dataTable.render.number('.', ",", 2, '', ' Ton')
       },
       {
         data:'action',
         name:'action',
         width:'20%',
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

    function load_data_laporan_produksi(from_date = '', to_date = '')
     {
      table = $('#laporan_produksi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("laporan_produksi/view_laporan_produksi_table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[3,'desc']],
        columns: [
        {
          className: 'control dt-center',
          orderable: false,
          targets: 0,
          defaultContent:''
        },
        {
          className:'details-control dt-center',
          orderable:false,
          searchable:false,
          data:null,
          defaultContent:'',
        },
        {
         data:'DT_RowIndex',
         name:'DT_RowIndex',
         width: '7%',
         className:'dt-center'
       },
       {
         data:'tanggal_laporan_produksi',
         name:'tanggal_laporan_produksi',
         className:'dt-center'
       },
       {
         data:'total_sak',
         name:'total_sak',
         defaultContent: '---',
         className:'dt-center',
         render: $.fn.dataTable.render.number('.', " Sak", ',')
       },
       {
         data:'total_tonase',
         name:'total_tonase',
         defaultContent: '---',
         className:'dt-center',
         render: $.fn.dataTable.render.number('.', ",", 2, '', ' Ton')
       },
       {
         data:'action',
         name:'action',
         width:'20%',
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
        $('#laporan_produksi_table').DataTable().destroy();
        load_data_laporan_produksi(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#laporan_produksi_table').DataTable().destroy();
      load_data_laporan_produksi();
    });

    // $('[data-toggle=modal]').on('click', function (e) {
    //   var $target = $($(this).data('target'));
    //   $target.data('triggered',true);
    //   setTimeout(function() {
    //     if($target.data('triggered')) {
    //       $target.modal('show')
    //       .data('triggered',false);
    //     };
    //   }, 3000);
    //   return false;
    // });
    $('body').on('click', '#btn_upload_excel', function () {
      $('#modal_upload_excel').modal();
    });

    $('body').on('click', '#btn_input_laporan_produksi', function (event) {
      event.preventDefault();

      var tanggal = $(this).data("id");
      var nomor = $(this).data("no");
      $('#tanggal_laporan_produksi').val(tanggal);
      $('#nomor_laporan_produksi').val(nomor);
      $(this).prop("disabled", true);
      $(this).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
      );
      $(".loading").show();
      setTimeout(function() {
        $("#btn_input_laporan_produksi").prop("disabled", false);
        $("#btn_input_laporan_produksi").html('<i class="fa fa-edit"></i>');
        $(".loading").hide();
        $('#modal_input_laporan_produksi').modal();
      }, 6000);

      var urldata = "{{ url('laporan_produksi/data/view/tanggal') }}";
      urldata = urldata.replace('tanggal', enc(tanggal.toString()));
      $.get(urldata, function (dataprd) {
        $('#referensi').val(dataprd.referensi);
      });

      var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      $.each(arr_mesin, function(k, v) {
        var isLastElement = k == arr_mesin.length -1;
        
        for(var i = 2; i <= window['c_laporan_'+v]; i++){
          $('#row_data_laporan_'+v+i).remove();
        }

        window['c_laporan_'+v] = 1;

        var url_jenis_produk = "{{ url('get_jenis_produk') }}";
        $.get(url_jenis_produk, function (data) {
          $('#select_jenis_produk_'+v+window['c_laporan_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
          $.each(data, function(l, m) {
            $('#select_jenis_produk_'+v+window['c_laporan_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
          });
        })

        $("#select_jenis_produk_"+v+window['c_laporan_'+v]).change(function() {
          var val = $(this).val();
          var url_weight = "{{ url('get_weight/kode_produk') }}";
          url_weight = url_weight.replace('kode_produk', enc(val.toString()));
          $.get(url_weight, function (data) {
            $('#weight_'+v+window['c_laporan_'+v]).val(data.weight);
            var a = $("#jumlah_sak_"+v+window['c_laporan_'+v]).val();
            if(a != null || a != ''){
              var b = data.weight;
              var total = a * b;
              $("#jumlah_tonase_"+v+window['c_laporan_'+v]).val(total);
            }
          })
        });

        $('#jumlah_sak_'+v+window['c_laporan_'+v]).on('keyup', function(){
          if($("#weight_"+v+window['c_laporan_'+v]).val() != null || $("#weight_"+v+window['c_laporan_'+v]).val() != ''){
            var a = $("#jumlah_sak_"+v+window['c_laporan_'+v]).val();
            var b = $("#weight_"+v+window['c_laporan_'+v]).val();
            var total = a * b;
            $("#jumlah_tonase_"+v+window['c_laporan_'+v]).val(total);
          }
        });

        $('#add_data_laporan_'+v).unbind().click(function(){
          $('#add_data_laporan_'+v).hide();
          window['c_laporan_'+v]++;
          $('#mesin_'+v).attr('rowspan', window['c_laporan_'+v]);
          $('#dynamic_field_laporan_produksi_'+v).append('<tr id="row_data_laporan_'+v+window['c_laporan_'+v]+'"><td><select name="jenis_produk_'+v+'[]" class="form-control jenis_produk_list_'+v+'" id="select_jenis_produk_'+v+window['c_laporan_'+v]+'"></select></td><td><input type="text" name="jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="jumlah_sak_'+v+window['c_laporan_'+v]+'" class="form-control jumlah_sak_list_'+v+'" /><input type="hidden" name="weight_'+v+'[]" id="weight_'+v+window['c_laporan_'+v]+'" class="form-control weight_list_'+v+'" /></td><td><input type="text" name="jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="jumlah_tonase_'+v+window['c_laporan_'+v]+'" class="form-control jumlah_tonase_list_'+v+'" /></td><td><button type="button" name="data_laporan_remove_'+v+'" id="'+window['c_laporan_'+v]+'" class="btn btn-danger btn_remove_laporan_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
          var url_jenis_produk = "{{ url('get_jenis_produk') }}";
          $.get(url_jenis_produk, function (data) {
            $('#select_jenis_produk_'+v+window['c_laporan_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
            $.each(data, function(l, m) {
              $('#select_jenis_produk_'+v+window['c_laporan_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
            });
          })

          $("#select_jenis_produk_"+v+window['c_laporan_'+v]).change(function() {
            var val = $(this).val();
            var url_weight = "{{ url('get_weight/kode_produk') }}";
            url_weight = url_weight.replace('kode_produk', enc(val.toString()));
            $.get(url_weight, function (data) {
              $('#weight_'+v+window['c_laporan_'+v]).val(data.weight);
              var a = $("#jumlah_sak_"+v+window['c_laporan_'+v]).val();
              if(a != null || a != ''){
                var b = data.weight;
                var total = a * b;
                $("#jumlah_tonase_"+v+window['c_laporan_'+v]).val(total);
              }
            })
          });

          $('#jumlah_sak_'+v+window['c_laporan_'+v]).on('keyup', function(){
            if($("#weight_"+v+window['c_laporan_'+v]).val() != null || $("#weight_"+v+window['c_laporan_'+v]).val() != ''){
              var a = $("#jumlah_sak_"+v+window['c_laporan_'+v]).val();
              var b = $("#weight_"+v+window['c_laporan_'+v]).val();
              var total = a * b;
              $("#jumlah_tonase_"+v+window['c_laporan_'+v]).val(total);
            }
          });

          setTimeout(function(){
            $('#add_data_laporan_'+v).show();
          }, 1000);
        });

        $(document).on('click', '.btn_remove_laporan_produksi_'+v, function(){  
          var button_id = $(this).attr("id");   
          $('#row_data_laporan_'+v+button_id+'').remove();
          window['c_laporan_'+v]--;
        });
      });

      $("#referensi").on("input", function(e) {
        e.stopImmediatePropagation();
        var value = $(this).val();

        var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

        $.each(arr_mesin, function(k, v) {
          $('#select_jenis_produk_'+v+1).val('').trigger('change');
          $('#weight_'+v+1).val('');
          $('#jumlah_sak_'+v+1).val('');
          $('#jumlah_tonase_'+v+1).val('');
          for(var a = 2; a <= window['c_laporan_'+v]; a++){
            $('#row_data_laporan_'+v+a).remove();
          }

          window['c_laporan_'+v] = 0;

        });

        var url_detail_ref = "{{ url('get_detail_ref_hasil_produksi/nomor_rencana_produksi') }}";
        url_detail_ref = url_detail_ref.replace("nomor_rencana_produksi", enc(value.toString()));
        $.get(url_detail_ref, function (data_dt) {
          if(typeof data_dt !== 'undefined' && data_dt.length > 0){
            for(let i = 0; i < data_dt.length; i++){
              window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]++;

              if(window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]] == 1){

                var url_jenis_produk = "{{ url('get_jenis_produk') }}";
                $.get(url_jenis_produk, function (data) {
                  $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
                  $.each(data, function(l, m) {
                    $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
                  });

                  $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jenis_produk).trigger('change');
                })

                $('#weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].weight);
                $('#jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_sak);
                $('#jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_tonase);
              }else{
                $('#mesin_'+arr_mesin[data_dt[i].mesin - 1]).attr('rowspan', window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]);
                $('#dynamic_field_laporan_produksi_'+arr_mesin[data_dt[i].mesin - 1]).append('<tr id="row_data_laporan_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]+'"><td><select name="jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+'[]" class="form-control jenis_produk_list_'+arr_mesin[data_dt[i].mesin - 1]+'" id="select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]+'"></select></td><td><input type="text" name="jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+'[]" placeholder="Jumlah Sak" id="jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control jumlah_sak_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /><input type="hidden" name="weight_'+arr_mesin[data_dt[i].mesin - 1]+'[]" id="weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control weight_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /></td><td><input type="text" name="jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+'[]" placeholder="Jumlah Tonase" id="jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control jumlah_tonase_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /></td><td><button type="button" name="data_laporan_remove_'+arr_mesin[data_dt[i].mesin - 1]+'" id="'+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="btn btn-danger btn_remove_laporan_produksi_'+arr_mesin[data_dt[i].mesin - 1]+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');

                var url_jenis_produk = "{{ url('get_jenis_produk') }}";
                $.get(url_jenis_produk, function (data) {
                  $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
                  $.each(data, function(l, m) {
                    $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
                  });

                  $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jenis_produk).trigger('change');
                })

                $('#weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].weight);
                $('#jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_sak);
                $('#jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_laporan_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_tonase);
              }
            }
          }
        })
      });
    });

    function format(d){
      Object.keys(d[0]).forEach(function(key) {
        if(d[0][key] === null) {
          d[0][key] = 0;
        }
      });
      return '<table border="0" style="width: 100%;">'+
      '<tr>'+
      '<th>Nama Mesin</th>'+
      '<th>Jumlah Sak</th>'+
      '<th>Jumlah Tonase</th>'+
      '<th>Nama Mesin</th>'+
      '<th>Jumlah Sak</th>'+
      '<th>Jumlah Tonase</th>'+
      '</tr>'+
      '<tr>'+
      '<td width="14%">SA:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['sa_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['sa_jumlah_tonase'])+' TON</td>'+
      '<td width="14%">SB:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['sb_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number(',', ',', 2).display(d[0]['sb_jumlah_tonase'])+' TON</td>'+
      '</tr>'+
      '<tr>'+
      '<td width="14%">Mixer:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['mixer_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['mixer_jumlah_tonase'])+' TON</td>'+
      '<td width="14%">RA:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['ra_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['ra_jumlah_tonase'])+' TON</td>'+
      '</tr>'+
      '<tr>'+
      '<td width="14%">RB:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['rb_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['rb_jumlah_tonase'])+' TON</td>'+
      '<td width="14%">RC:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['rc_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['rc_jumlah_tonase'])+' TON</td>'+
      '</tr>'+
      '<tr>'+
      '<td width="14%">RD:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['rd_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['rd_jumlah_tonase'])+' TON</td>'+
      '<td width="14%">RE:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['re_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['re_jumlah_tonase'])+' TON</td>'+
      '</tr>'+
      '<tr>'+
      '<td width="14%">RF:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['rf_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['rf_jumlah_tonase'])+' TON</td>'+
      '<td width="14%">RG:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['rg_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['rg_jumlah_tonase'])+' TON</td>'+
      '</tr>'+
      '<tr>'+
      '<td width="14%">Coating:</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',').display(d[0]['coating_jumlah_sak'])+' SAK</td>'+
      '<td>'+$.fn.dataTable.render.number('.', ',', 2).display(d[0]['coating_jumlah_tonase'])+' TON</td>'+
      '<td colspan="3"></td>'+
      '</tr>'+
      '</table>';
    }

    $('#laporan_produksi_table').on( 'click', 'tbody td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row( tr );
      $.ajax({
        type: "GET",
        url: "{{ url('detail_laporan_produksi') }}",
        data: { 'tanggal' : row.data()['tanggal_laporan_produksi'] },
        success: function (data) {
          if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
          }else {
            row.child( format(data) ).show();
            tr.addClass('shown');
          }
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    function getHighest(obj){
      keys = Object.keys(obj);
      if(!keys.length)
        return 0;

      max = obj[keys[0]].length;
      for(i=1; i<keys.length; i++ )
      {
        if(obj[keys[i]].length > max)
          max = obj[keys[i]].length;
      }
      return max;
    }

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    $('body').on('click', '#view-data-lab', function () {
      var tanggal = $(this).data("id");

      $('#modal_lihat_hasil_lab').modal();
      document.getElementById("title_lihat_hasil_lab").innerHTML = "Data Lab Tanggal " + tanggal;
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

    $('body').on('click', '#view-data-mesin', function () {
      var tanggal = $(this).data("id");

      $('#modal_view_data_mesin').modal();
      var url = "{{ url('teknik/masalah_mesin/view/data/tanggal') }}";
      url = url.replace('tanggal', enc(tanggal.toString()));
      $('#td_tanggal_mesin').html(tanggal);

      var urldata = "{{ url('laporan_produksi/data/view/tanggal') }}";
      urldata = urldata.replace('tanggal', enc(tanggal.toString()));
      $.get(urldata, function (dataprd) {
        $('#td_referensi_mesin').html(dataprd.referensi);
      })

      var url_excel = "{{ url('teknik/masalah_mesin/excel/tanggal') }}";
      url_excel = url_excel.replace('tanggal', enc(tanggal.toString()));
      $("#btn-save-excel-mesin").attr("href", url_excel);
      $("#tbody_view_mesin_major").empty();
      $("#tbody_view_mesin_minor").empty();
      $("#tbody_view_mesin_lain").empty();
      $.get(url, function (data) {
        $.each(data.major, function(k, v) {
          if(v.length == 0){
            $('#tbody_view_mesin_major').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+ (data.major[k].tonase == 0  || data.major[k].tonase == null ? '-': numberWithCommas(data.major[k].tonase))+'</td>'+
              '<td style="vertical-align : middle; text-align: center;" id="masalah_major_'+k+'">-</td>'+
              '</tr>'
            ); 
          }else{
            $('#tbody_view_mesin_major').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+ (data.major[k].tonase == 0  || data.major[k].tonase == null ? '-': numberWithCommas(data.major[k].tonase))+'</td>'+
              '<td style="vertical-align : middle; text-align: center;" id="masalah_major_'+k+'"></td>'+
              '</tr>'
            ); 
          }
          $.each(data.major[k].masalah, function(i, j) {
            if(j.jam_awal != null && j.jam_akhir != null){
              $('#masalah_major_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ' - ' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else if(j.jam_awal != null && j.jam_akhir == null){
              $('#masalah_major_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else if(j.jam_akhir != null && j.jam_awal == null){
              $('#masalah_major_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else{
              $('#masalah_major_'+k).append((i+1)+'. '+ j.masalah + '<br>');
            }
          });
        });

        $.each(data.minor, function(k, v) {
          if(v.length == 0){
            $('#tbody_view_mesin_minor').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+ (data.minor[k].tonase == 0  || data.minor[k].tonase == null ? '-': numberWithCommas(data.minor[k].tonase))+'</td>'+
              '<td style="vertical-align : middle; text-align: center;" id="masalah_minor_'+k+'">-</td>'+
              '</tr>'
            ); 
          }else{
            $('#tbody_view_mesin_minor').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+ (data.minor[k].tonase == 0  || data.minor[k].tonase == null ? '-': numberWithCommas(data.minor[k].tonase))+'</td>'+
              '<td style="vertical-align : middle; text-align: center;" id="masalah_minor_'+k+'"></td>'+
              '</tr>'
            ); 
          }
          $.each(data.minor[k].masalah, function(i, j) {
            if(j.jam_awal != null && j.jam_akhir != null){
              $('#masalah_minor_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ' - ' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else if(j.jam_awal != null && j.jam_akhir == null){
              $('#masalah_minor_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else if(j.jam_akhir != null && j.jam_awal == null){
              $('#masalah_minor_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else{
              $('#masalah_minor_'+k).append((i+1)+'. '+ j.masalah + '<br>');
            }
          });
        });

        $.each(data.lain, function(k, v) {
          if(v.length == 0){
            $('#tbody_view_mesin_lain').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+ (data.lain[k].tonase == 0  || data.lain[k].tonase == null ? '-': numberWithCommas(data.lain[k].tonase))+'</td>'+
              '<td style="vertical-align : middle; text-align: center;" id="masalah_lain_'+k+'">-</td>'+
              '</tr>'
            ); 
          }else{
            $('#tbody_view_mesin_lain').append(
              '<tr>'+
              '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
              '<td style="vertical-align : middle; text-align: center;">'+ (data.lain[k].tonase == 0  || data.lain[k].tonase == null ? '-': numberWithCommas(data.lain[k].tonase))+'</td>'+
              '<td style="vertical-align : middle; text-align: center;" id="masalah_lain_'+k+'"></td>'+
              '</tr>'
            ); 
          }
          $.each(data.lain[k].masalah, function(i, j) {
            if(j.jam_awal != null && j.jam_akhir != null){
              $('#masalah_lain_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ' - ' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else if(j.jam_awal != null && j.jam_akhir == null){
              $('#masalah_lain_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else if(j.jam_akhir != null && j.jam_awal == null){
              $('#masalah_lain_'+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
            }else{
              $('#masalah_lain_'+k).append((i+1)+'. '+ j.masalah + '<br>');
            }
          });
        });
      })
    });

    $('body').on('click', '#btn_edit_laporan_produksi', function (event) {
      event.preventDefault();

      var tanggal = $(this).data("id");
      var nomor = $(this).data("no");
      $('#edit_tanggal_laporan_produksi').val(tanggal);
      $('#edit_nomor_laporan_produksi').val(nomor);
      document.getElementById("title_edit_laporan_produksi").innerHTML = "Edit Data Tanggal " + tanggal;
      $(this).prop("disabled", true);
      $(this).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
      );
      $(".loading").show();
      setTimeout(function() {
        $("#btn_edit_laporan_produksi").prop("disabled", false);
        $("#btn_edit_laporan_produksi").html('<i class="fa fa-edit"></i>');
        $(".loading").hide();
        $('#modal_edit_laporan_produksi').modal();
      }, 12000);

      var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      var urldata = "{{ url('laporan_produksi/data/view/tanggal') }}";
      urldata = urldata.replace('tanggal', enc(tanggal.toString()));
      $.get(urldata, function (dataprd) {
        $('#edit_referensi').val(dataprd.referensi);
      })

      var url_data = "{{ url('laporan_produksi/view/tanggal') }}";
      url_data = url_data.replace('tanggal', enc(tanggal.toString()));
      $.get(url_data, function (data_prd) {
        $.each(arr_mesin, function(k, v) {
          for(var i = 2; i <= window['edit_c_laporan_'+v]; i++){
            $('#edit_row_data_laporan_'+v+i).remove();
          }

          var url_jenis_produk = "{{ url('get_jenis_produk') }}";
          $.get(url_jenis_produk, function (data) {
            $('#edit_select_jenis_produk_'+v+1).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
            $.each(data, function(l, m) {
              $('#edit_select_jenis_produk_'+v+1).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
            });
            if(data_prd[v].length > 0){
              $('#edit_jenis_produk_lama_'+v+1).val(data_prd[v][0].jenis_produk);
              $('#edit_select_jenis_produk_'+v+1).val(data_prd[v][0].jenis_produk).trigger('change');
            }
          })
          if(data_prd[v].length > 0){
            $('#edit_jumlah_sak_lama_'+v+1).val(data_prd[v][0].jumlah_sak);
            $('#edit_jumlah_tonase_lama_'+v+1).val(data_prd[v][0].jumlah_tonase);
            $('#edit_jumlah_sak_'+v+1).val(data_prd[v][0].jumlah_sak);
            $('#edit_jumlah_tonase_'+v+1).val(data_prd[v][0].jumlah_tonase);
            $('#edit_nomor_laporan_produksi_detail_'+v+1).val(data_prd[v][0].nomor_laporan_produksi_detail);
          }

          $("#edit_select_jenis_produk_"+v+1).change(function() {
            var val = $(this).val();
            var url_weight = "{{ url('get_weight/kode_produk') }}";
            url_weight = url_weight.replace('kode_produk', enc(val.toString()));
            $.get(url_weight, function (data) {
              $('#edit_weight_'+v+1).val(data.weight);
              var a = $("#edit_jumlah_sak_"+v+1).val();
              if(a != null || a != ''){
                var b = data.weight;
                var total = a * b;
                $("#edit_jumlah_tonase_"+v+1).val(total);
              }
            })
          });

          $('#edit_jumlah_sak_'+v+1).on('keyup', function(){
            if($("#edit_weight_"+v+1).val() != null || $("#edit_weight_"+v+1).val() != ''){
              var a = $("#edit_jumlah_sak_"+v+1).val();
              var b = $("#edit_weight_"+v+1).val();
              var total = a * b;
              $("#edit_jumlah_tonase_"+v+1).val(total);
            }
          });

          $('#edit_add_data_laporan_'+v).unbind().click(function(){
            $('#edit_add_data_laporan_'+v).hide();
            window['edit_c_laporan_'+v]++;
            $('#edit_mesin_'+v).attr('rowspan', window['edit_c_laporan_'+v]);
            $('#edit_dynamic_field_laporan_produksi_'+v).append('<tr id="edit_row_data_laporan_'+v+window['edit_c_laporan_'+v]+'"><td><select name="edit_jenis_produk_'+v+'[]" class="form-control edit_jenis_produk_list_'+v+'" id="edit_select_jenis_produk_'+v+window['edit_c_laporan_'+v]+'"></select><input type="hidden" name="edit_jenis_produk_lama_'+v+'[]" id="edit_jenis_produk_lama_'+v+window['edit_c_laporan_'+v]+'" class="form-control edit_jenis_produk_lama_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_'+v+window['edit_c_laporan_'+v]+'" class="form-control edit_jumlah_sak_list_'+v+'" /><input type="hidden" name="edit_weight_'+v+'[]" id="edit_weight_'+v+window['edit_c_laporan_'+v]+'" class="form-control edit_weight_list_'+v+'" /><input type="hidden" name="edit_jumlah_sak_lama_'+v+'[]" id="edit_jumlah_sak_lama_'+v+window['edit_c_laporan_'+v]+'" class="form-control edit_jumlah_sak_lama_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_'+v+window['edit_c_laporan_'+v]+'" class="form-control edit_jumlah_tonase_list_'+v+'" /><input type="hidden" name="edit_jumlah_tonase_lama_'+v+'[]" id="edit_jumlah_tonase_lama_'+v+window['edit_c_laporan_'+v]+'" class="form-control edit_jumlah_tonase_lama_list_'+v+'" /></td><td><button type="button" name="edit_data_laporan_remove_'+v+'" id="'+window['edit_c_laporan_'+v]+'" class="btn btn-danger edit_btn_remove_laporan_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
            var url_jenis_produk = "{{ url('get_jenis_produk') }}";
            $.get(url_jenis_produk, function (data) {
              $('#edit_select_jenis_produk_'+v+window['edit_c_laporan_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
              $.each(data, function(l, m) {
                $('#edit_select_jenis_produk_'+v+window['edit_c_laporan_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
              });
            })

            $("#edit_select_jenis_produk_"+v+window['edit_c_laporan_'+v]).change(function() {
              var val = $(this).val();
              $('#edit_jenis_produk_lama_'+v+window['edit_c_laporan_'+v]).val(val);
              var url_weight = "{{ url('get_weight/kode_produk') }}";
              url_weight = url_weight.replace('kode_produk', enc(val.toString()));
              $.get(url_weight, function (data) {
                $('#edit_weight_'+v+window['edit_c_laporan_'+v]).val(data.weight);
                var a = $("#edit_jumlah_sak_"+v+window['edit_c_laporan_'+v]).val();
                if(a != null || a != ''){
                  var b = data.weight;
                  var total = a * b;
                  $("#edit_jumlah_tonase_"+v+window['edit_c_laporan_'+v]).val(total);
                }
              })
            });

            $('#edit_jumlah_sak_'+v+window['edit_c_laporan_'+v]).on('keyup', function(){
              if($("#edit_weight_"+v+window['edit_c_laporan_'+v]).val() != null || $("#edit_weight_"+v+window['edit_c_laporan_'+v]).val() != ''){
                var a = $("#edit_jumlah_sak_"+v+window['edit_c_laporan_'+v]).val();
                var b = $("#edit_weight_"+v+window['edit_c_laporan_'+v]).val();
                var total = a * b;
                $("#edit_jumlah_tonase_"+v+window['edit_c_laporan_'+v]).val(total);
              }
            });

            setTimeout(function(){
              $('#edit_add_data_laporan_'+v).show();
            }, 1000);
          });

          $(document).on('click', '.edit_btn_remove_laporan_produksi_'+v, function(){  
            var button_id = $(this).attr("id");   
            var nomor = $("#edit_nomor_laporan_produksi_detail_"+v+button_id). val();

            $.ajax({
              type: "GET",
              url: "{{ url('laporan_produksi/detail/delete') }}",
              data: { 'nomor' : nomor },
              success: function (data) {
                alert("Data Deleted");
              },
              error: function (data) {
                console.log('Error:', data);
              }
            });
            $('#edit_row_data_laporan_'+v+button_id).remove();
            window['edit_c_laporan_'+v]--;
          });
        });
        
        $.each(arr_mesin, function(k, v) {
          if(data_prd[v].length > 0){
            window['edit_c_laporan_'+v] = data_prd[v].length;
          }else{
            window['edit_c_laporan_'+v] = 1;
          }
          $('#edit_mesin_'+v).attr('rowspan', window['edit_c_laporan_'+v]);
          for(let i = 1; i < window['edit_c_laporan_'+v]; i++){
            $('#edit_dynamic_field_laporan_produksi_'+v).append('<tr id="edit_row_data_laporan_'+v+(i+1)+'"><td><select name="edit_jenis_produk_'+v+'[]" class="form-control edit_jenis_produk_list_'+v+'" id="edit_select_jenis_produk_'+v+(i+1)+'"></select><input type="hidden" name="edit_jenis_produk_lama_'+v+'[]" id="edit_jenis_produk_lama_'+v+(i+1)+'" class="form-control edit_jenis_produk_lama_list_'+v+'" /><input type="hidden" name="edit_nomor_laporan_produksi_detail_'+v+(i+1)+'" id="edit_nomor_laporan_produksi_detail_'+v+(i+1)+'" class="form-control edit_nomor_laporan_produksi_detail_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_'+v+(i+1)+'" class="form-control edit_jumlah_sak_list_'+v+'" /><input type="hidden" name="edit_weight_'+v+'[]" id="edit_weight_'+v+(i+1)+'" class="form-control edit_weight_list_'+v+'" /><input type="hidden" name="edit_jumlah_sak_lama_'+v+'[]" id="edit_jumlah_sak_lama_'+v+(i+1)+'" class="form-control edit_jumlah_sak_lama_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_'+v+(i+1)+'" class="form-control edit_jumlah_tonase_list_'+v+'" /><input type="hidden" name="edit_jumlah_tonase_lama_'+v+'[]" id="edit_jumlah_tonase_lama_'+v+(i+1)+'" class="form-control edit_jumlah_tonase_lama_list_'+v+'" /></td><td><button type="button" name="edit_data_laporan_remove_'+v+'" id="'+(i+1)+'" class="btn btn-danger edit_btn_remove_laporan_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');

            var url_jenis_produk = "{{ url('get_jenis_produk') }}";
            $.get(url_jenis_produk, function (data) {
              $('#edit_select_jenis_produk_'+v+(i+1)).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
              $.each(data, function(l, m) {
                $('#edit_select_jenis_produk_'+v+(i+1)).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
              });
              $('#edit_jenis_produk_lama_'+v+(i+1)).val(data_prd[v][i].jenis_produk);
              $('#edit_select_jenis_produk_'+v+(i+1)).val(data_prd[v][i].jenis_produk).trigger('change');
            })
            $('#edit_jumlah_sak_lama_'+v+(i+1)).val(data_prd[v][i].jumlah_sak);
            $('#edit_jumlah_tonase_lama_'+v+(i+1)).val(data_prd[v][i].jumlah_tonase);
            $('#edit_jumlah_sak_'+v+(i+1)).val(data_prd[v][i].jumlah_sak);
            $('#edit_jumlah_tonase_'+v+(i+1)).val(data_prd[v][i].jumlah_tonase);
            $('#edit_nomor_laporan_produksi_detail_'+v+(i+1)).val(data_prd[v][i].nomor_laporan_produksi_detail);
          }
        });
      });
    });

    $('body').on('click', '#view-data', function () {
      var tanggal = $(this).data("id");

      $('#modal_lihat_laporan_produksi').modal();
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

    $('#laporan_produksi_form').validate({
      rules: {
        tanggal_laporan_produksi: {
          required: true,
        },
      },
      messages: {
        tanggal_laporan_produksi: {
          required: "Tanggal Laporan Produksi is Required",
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
        var myform = document.getElementById("laporan_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('laporan_produksi/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#laporan_produksi_form').trigger("reset");
            var oTable = $('#laporan_produksi_table').dataTable();
            oTable.fnDraw(false);
            var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];
            $.each(arr_mesin, function(k, v) {
              for(var i = 2; i <= window['c_laporan_'+v]; i++){
                $('#row_data_laporan_'+v+i).remove();
              }
            });
            $("#modal_input_laporan_produksi").modal('hide');
            $("#modal_input_laporan_produksi").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#edit_laporan_produksi_form').validate({
      rules: {
        edit_tanggal_laporan_produksi: {
          required: true,
        },
      },
      messages: {
        edit_tanggal_laporan_produksi: {
          required: "Tanggal Laporan Produksi is Required",
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
        var myform = document.getElementById("edit_laporan_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('laporan_produksi/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_laporan_produksi_form').trigger("reset");
            var oTable = $('#laporan_produksi_table').dataTable();
            oTable.fnDraw(false);
            var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];
            $.each(arr_mesin, function(k, v) {
              for(var i = 2; i <= window['edit_c_laporan_'+v]; i++){
                $('#edit_row_data_laporan_'+v+i).remove();
              }
            });
            $("#modal_edit_laporan_produksi").modal('hide');
            $("#modal_edit_laporan_produksi").trigger('click');
            alert("Data Successfully Stored");
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
  bsCustomFileInput.init();
});
</script>
@endsection
