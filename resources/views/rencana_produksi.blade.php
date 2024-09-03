@extends('layouts.app_admin')

@section('title')
<title>RENCANA PRODUKSI - PT. DWI SELO GIRI MAS</title>
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
  #rencana_produksi_table tbody tr:hover{
    cursor: pointer;
  }
  .filter-btn {
    margin-top: 32px;
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
    .rencana-btn {
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
    #dynamic_field_rencana_produksi th {
      display: none;
    }
    #dynamic_field_rencana_produksi td {
      display:inline-block;
        padding:5px;
        width:100%;
    }
    #dynamic_field_spek_rencana_produksi th {
      display: none;
    }
    #dynamic_field_spek_rencana_produksi td {
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
          <h1 class="m-0 text-dark">Rencana Produksi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Produksi</li>
            <li class="breadcrumb-item">Rencana Produksi</li>
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
            <div class="col-5">
              <button type="button" name="btn_input_rencana_produksi" id="btn_input_rencana_produksi" class="btn btn-block btn-primary rencana-btn">Input Rencana Produksi</button>
            </div>
            <div class="col-5">
              <a class="btn btn-block btn-primary rencana-btn" href="{{ url('rencana_produksi/lihat_calendar') }}">Lihat Kalender</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="rencana_produksi_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
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
  
  <div class="modal fade" id="modal_input_rencana_produksi">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Rencana Produksi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" class="rencana_produksi_form" id="rencana_produksi_form" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Tanggal Rencana</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="tanggal_rencana" id="tanggal_rencana" autocomplete="off" placeholder="Tanggal Rencana">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <!-- <div class="col-6">
              <div class="form-group">
                <label for="referensi">Referensi</label>
                <select id="referensi" name="referensi" class="form-control">
                </select>
              </div>
            </div> -->
            <div class="col-6">
              <div class="form-group">
                <label for="referensi">Referensi</label>
                <select id="referensi" name="referensi[]" class="form-control select2 referensi" style="width: 100%;"></select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="col-6" style="font-weight: bold; padding-left: 30px; border-spacing: 15px;">
              <div class="form-group">
                <label>&nbsp</label>
                <p id="show_custid"></p>
                <p id="show_tanggal_order"></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group lihat-table">
                <!-- <table class="table table-bordered" id="dynamic_field_rencana_produksi">  
                  <tr>
                    <th width="35%">Mesin</th>
                    <th width="35%">Jenis Produk</th>
                    <th>Jumlah Sak</th>
                    <th width="11%"></th>
                  </tr>
                  <tr>  
                    <td><select name="mesin[]" id="select_mesin" class="form-control mesin_list"></select></td>
                    <td><select name="jenis_produk[]" id="select_jenis_produk" class="form-control jenis_produk_list"></select></td>
                    <td><input type="text" name="jumlah_sak[]" id="input_jumlah_sak" placeholder="Jumlah Sak" class="form-control jumlah_sak_list" /></td>  
                    <td><button type="button" name="add_data_rencana" id="add_data_rencana" class="btn btn-success">Add More</button></td>  
                  </tr>  
                </table> -->
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_sa">  
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
                    <td style="vertical-align : middle; text-align: center;"><button type="button" name="add_data_rencana_sa" id="add_data_rencana_sa" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>  
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_sb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_sb" rowspan="1">SB</th>
                    <td width="40%"><select name="jenis_produk_sb[]" id="select_jenis_produk_sb1" class="form-control jenis_produk_list_sb"></select></td>
                    <td><input type="text" name="jumlah_sak_sb[]" placeholder="Jumlah Sak" id="jumlah_sak_sb1" class="form-control jumlah_sak_list_sb" /><input type="hidden" name="weight_sb[]" id="weight_sb1" class="form-control weight_list_sb" /></td>
                    <td><input type="text" name="jumlah_tonase_sb[]" placeholder="Jumlah Tonase" id="jumlah_tonase_sb1" class="form-control jumlah_tonase_list_sb" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_sb" id="add_data_rencana_sb" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_mixer">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_mixer" rowspan="1">Mixer</th>
                    <td width="40%"><select name="jenis_produk_mixer[]" id="select_jenis_produk_mixer1" class="form-control jenis_produk_list_mixer"></select></td>
                    <td><input type="text" name="jumlah_sak_mixer[]" placeholder="Jumlah Sak" id="jumlah_sak_mixer1" class="form-control jumlah_sak_list_mixer" /><input type="hidden" name="weight_mixer[]" id="weight_mixer1" class="form-control weight_list_mixer" /></td>
                    <td><input type="text" name="jumlah_tonase_mixer[]" placeholder="Jumlah Tonase" id="jumlah_tonase_mixer1" class="form-control jumlah_tonase_list_mixer" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_mixer" id="add_data_rencana_mixer" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr> 
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_ra">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_ra" rowspan="1">RA</th>
                    <td width="40%"><select name="jenis_produk_ra[]" id="select_jenis_produk_ra1" class="form-control jenis_produk_list_ra"></select></td>
                    <td><input type="text" name="jumlah_sak_ra[]" placeholder="Jumlah Sak" id="jumlah_sak_ra1" class="form-control jumlah_sak_list_ra" /><input type="hidden" name="weight_ra[]" id="weight_ra1" class="form-control weight_list_ra" /></td>
                    <td><input type="text" name="jumlah_tonase_ra[]" placeholder="Jumlah Tonase" id="jumlah_tonase_ra1" class="form-control jumlah_tonase_list_ra" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_ra" id="add_data_rencana_ra" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_rb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rb" rowspan="1">RB</th>
                    <td width="40%"><select name="jenis_produk_rb[]" id="select_jenis_produk_rb1" class="form-control jenis_produk_list_rb"></select></td>
                    <td><input type="text" name="jumlah_sak_rb[]" placeholder="Jumlah Sak" id="jumlah_sak_rb1" class="form-control jumlah_sak_list_rb" /><input type="hidden" name="weight_rb[]" id="weight_rb1" class="form-control weight_list_rb" /></td>
                    <td><input type="text" name="jumlah_tonase_rb[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rb1" class="form-control jumlah_tonase_list_rb" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_rb" id="add_data_rencana_rb" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_rc">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rc" rowspan="1">RC</th>
                    <td width="40%"><select name="jenis_produk_rc[]" id="select_jenis_produk_rc1" class="form-control jenis_produk_list_rc"></select></td>
                    <td><input type="text" name="jumlah_sak_rc[]" placeholder="Jumlah Sak" id="jumlah_sak_rc1" class="form-control jumlah_sak_list_rc" /><input type="hidden" name="weight_rc[]" id="weight_rc1" class="form-control weight_list_rc" /></td>
                    <td><input type="text" name="jumlah_tonase_rc[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rc1" class="form-control jumlah_tonase_list_rc" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_rc" id="add_data_rencana_rc" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_rd">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rd" rowspan="1">RD</th>
                    <td width="40%"><select name="jenis_produk_rd[]" id="select_jenis_produk_rd1" class="form-control jenis_produk_list_rd"></select></td>
                    <td><input type="text" name="jumlah_sak_rd[]" placeholder="Jumlah Sak" id="jumlah_sak_rd1" class="form-control jumlah_sak_list_rd" /><input type="hidden" name="weight_rd[]" id="weight_rd1" class="form-control weight_list_rd" /></td>
                    <td><input type="text" name="jumlah_tonase_rd[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rd1" class="form-control jumlah_tonase_list_rd" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_rd" id="add_data_rencana_rd" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_re">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_re" rowspan="1">RE</th>
                    <td width="40%"><select name="jenis_produk_re[]" id="select_jenis_produk_re1" class="form-control jenis_produk_list_re"></select></td>
                    <td><input type="text" name="jumlah_sak_re[]" placeholder="Jumlah Sak" id="jumlah_sak_re1" class="form-control jumlah_sak_list_re" /><input type="hidden" name="weight_re[]" id="weight_re1" class="form-control weight_list_re" /></td>
                    <td><input type="text" name="jumlah_tonase_re[]" placeholder="Jumlah Tonase" id="jumlah_tonase_re1" class="form-control jumlah_tonase_list_re" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_re" id="add_data_rencana_re" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_rf">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rf" rowspan="1">RF</th>
                    <td width="40%"><select name="jenis_produk_rf[]" id="select_jenis_produk_rf1" class="form-control jenis_produk_list_rf"></select></td>
                    <td><input type="text" name="jumlah_sak_rf[]" placeholder="Jumlah Sak" id="jumlah_sak_rf1" class="form-control jumlah_sak_list_rf" /><input type="hidden" name="weight_rf[]" id="weight_rf1" class="form-control weight_list_rf" /></td>
                    <td><input type="text" name="jumlah_tonase_rf[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rf1" class="form-control jumlah_tonase_list_rf" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_rf" id="add_data_rencana_rf" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_rencana_produksi_rg">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="mesin_rg" rowspan="1">RG</th>
                    <td width="40%"><select name="jenis_produk_rg[]" id="select_jenis_produk_rg1" class="form-control jenis_produk_list_rg"></select></td>
                    <td><input type="text" name="jumlah_sak_rg[]" placeholder="Jumlah Sak" id="jumlah_sak_rg1" class="form-control jumlah_sak_list_rg" /><input type="hidden" name="weight_rg[]" id="weight_rg1" class="form-control weight_list_rg" /></td>
                    <td><input type="text" name="jumlah_tonase_rg[]" placeholder="Jumlah Tonase" id="jumlah_tonase_rg1" class="form-control jumlah_tonase_list_rg" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="add_data_rencana_rg" id="add_data_rencana_rg" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
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

  <div class="modal fade" id="modal_input_spek_rencana_produksi">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Input Spesifikasi Lab Rencana Produksi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" class="spek_rencana_produksi_form" id="spek_rencana_produksi_form" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-12">
              <input type="hidden" class="form-control" name="nomor_rencana_produksi_spek" id="nomor_rencana_produksi_spek">
              <div class="form-group lihat-table-spek">
                <!-- <table class="table table-bordered" id="dynamic_field_rencana_produksi">  
                  <tr>
                    <th width="35%">Mesin</th>
                    <th width="35%">Jenis Produk</th>
                    <th>Jumlah Sak</th>
                    <th width="11%"></th>
                  </tr>
                  <tr>  
                    <td><select name="mesin[]" id="select_mesin" class="form-control mesin_list"></select></td>
                    <td><select name="jenis_produk[]" id="select_jenis_produk" class="form-control jenis_produk_list"></select></td>
                    <td><input type="text" name="jumlah_sak[]" id="input_jumlah_sak" placeholder="Jumlah Sak" class="form-control jumlah_sak_list" /></td>  
                    <td><button type="button" name="add_data_rencana" id="add_data_rencana" class="btn btn-success">Add More</button></td>  
                  </tr>  
                </table> -->
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_sa">  
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" width="10%">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;">RPM</th>
                    <th style="vertical-align : middle; text-align: center;">Particle Size</th>
                    <th style="vertical-align : middle; text-align: center;">SSA</th>
                    <th style="vertical-align : middle; text-align: center;">Whiteness</th>
                    <th style="vertical-align : middle; text-align: center;">Moisture</th>
                    <th style="vertical-align : middle; text-align: center;">Residue</th>
                  </tr>
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_sa" rowspan="1">SA</th>
                    <td><input type="text" name="spek_rpm_sa[]" placeholder="RPM" id="spek_rpm_sa1" class="form-control spek_rpm_list_sa" /></td>
                    <td><input type="text" name="spek_particle_size_sa[]" placeholder="Particle Size" id="spek_particle_size_sa1" class="form-control spek_particle_size_list_sa" /></td> 
                    <td><input type="text" name="spek_ssa_sa[]" placeholder="SSA" id="spek_ssa_sa1" class="form-control spek_ssa_list_sa" /></td> 
                    <td><input type="text" name="spek_whiteness_sa[]" placeholder="Whiteness" id="spek_whiteness_sa1" class="form-control spek_whiteness_list_sa" /></td> 
                    <td><input type="text" name="spek_moisture_sa[]" placeholder="Moisture" id="spek_moisture_sa1" class="form-control spek_moisture_list_sa" /></td> 
                    <td><input type="text" name="spek_residue_sa[]" placeholder="Residue" id="spek_residue_sa1" class="form-control spek_residue_list_sa" /></td>   
                  </tr>  
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_sb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_sb" rowspan="1">SB</th>
                    <td><input type="text" name="spek_rpm_sb[]" placeholder="RPM" id="spek_rpm_sb1" class="form-control spek_rpm_list_sb" /></td>
                    <td><input type="text" name="spek_particle_size_sb[]" placeholder="Particle Size" id="spek_particle_size_sb1" class="form-control spek_particle_size_list_sb" /></td> 
                    <td><input type="text" name="spek_ssa_sb[]" placeholder="SSA" id="spek_ssa_sb1" class="form-control spek_ssa_list_sb" /></td> 
                    <td><input type="text" name="spek_whiteness_sb[]" placeholder="Whiteness" id="spek_whiteness_sb1" class="form-control spek_whiteness_list_sb" /></td> 
                    <td><input type="text" name="spek_moisture_sb[]" placeholder="Moisture" id="spek_moisture_sb1" class="form-control spek_moisture_list_sb" /></td> 
                    <td><input type="text" name="spek_residue_sb[]" placeholder="Residue" id="spek_residue_sb1" class="form-control spek_residue_list_sb" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_mixer">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_mixer" rowspan="1">Mixer</th>
                    <td><input type="text" name="spek_rpm_mixer[]" placeholder="RPM" id="spek_rpm_mixer1" class="form-control spek_rpm_list_mixer" /></td>
                    <td><input type="text" name="spek_particle_size_mixer[]" placeholder="Particle Size" id="spek_particle_size_mixer1" class="form-control spek_particle_size_list_mixer" /></td> 
                    <td><input type="text" name="spek_ssa_mixer[]" placeholder="SSA" id="spek_ssa_mixer1" class="form-control spek_ssa_list_mixer" /></td> 
                    <td><input type="text" name="spek_whiteness_mixer[]" placeholder="Whiteness" id="spek_whiteness_mixer1" class="form-control spek_whiteness_list_mixer" /></td> 
                    <td><input type="text" name="spek_moisture_mixer[]" placeholder="Moisture" id="spek_moisture_mixer1" class="form-control spek_moisture_list_mixer" /></td> 
                    <td><input type="text" name="spek_residue_mixer[]" placeholder="Residue" id="spek_residue_mixer1" class="form-control spek_residue_list_mixer" /></td>  
                  </tr> 
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_ra">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_ra" rowspan="1">RA</th>
                    <td><input type="text" name="spek_rpm_ra[]" placeholder="RPM" id="spek_rpm_ra1" class="form-control spek_rpm_list_ra" /></td>
                    <td><input type="text" name="spek_particle_size_ra[]" placeholder="Particle Size" id="spek_particle_size_ra1" class="form-control spek_particle_size_list_ra" /></td> 
                    <td><input type="text" name="spek_ssa_ra[]" placeholder="SSA" id="spek_ssa_ra1" class="form-control spek_ssa_list_ra" /></td> 
                    <td><input type="text" name="spek_whiteness_ra[]" placeholder="Whiteness" id="spek_whiteness_ra1" class="form-control spek_whiteness_list_ra" /></td> 
                    <td><input type="text" name="spek_moisture_ra[]" placeholder="Moisture" id="spek_moisture_ra1" class="form-control spek_moisture_list_ra" /></td> 
                    <td><input type="text" name="spek_residue_ra[]" placeholder="Residue" id="spek_residue_ra1" class="form-control spek_residue_list_ra" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_rb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_rb" rowspan="1">RB</th>
                    <td><input type="text" name="spek_rpm_rb[]" placeholder="RPM" id="spek_rpm_rb1" class="form-control spek_rpm_list_rb" /></td>
                    <td><input type="text" name="spek_particle_size_rb[]" placeholder="Particle Size" id="spek_particle_size_rb1" class="form-control spek_particle_size_list_rb" /></td> 
                    <td><input type="text" name="spek_ssa_rb[]" placeholder="SSA" id="spek_ssa_rb1" class="form-control spek_ssa_list_rb" /></td> 
                    <td><input type="text" name="spek_whiteness_rb[]" placeholder="Whiteness" id="spek_whiteness_rb1" class="form-control spek_whiteness_list_rb" /></td> 
                    <td><input type="text" name="spek_moisture_rb[]" placeholder="Moisture" id="spek_moisture_rb1" class="form-control spek_moisture_list_rb" /></td> 
                    <td><input type="text" name="spek_residue_rb[]" placeholder="Residue" id="spek_residue_rb1" class="form-control spek_residue_list_rb" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_rc">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_rc" rowspan="1">RC</th>
                    <td><input type="text" name="spek_rpm_rc[]" placeholder="RPM" id="spek_rpm_rc1" class="form-control spek_rpm_list_rc" /></td>
                    <td><input type="text" name="spek_particle_size_rc[]" placeholder="Particle Size" id="spek_particle_size_rc1" class="form-control spek_particle_size_list_rc" /></td> 
                    <td><input type="text" name="spek_ssa_rc[]" placeholder="SSA" id="spek_ssa_rc1" class="form-control spek_ssa_list_rc" /></td> 
                    <td><input type="text" name="spek_whiteness_rc[]" placeholder="Whiteness" id="spek_whiteness_rc1" class="form-control spek_whiteness_list_rc" /></td> 
                    <td><input type="text" name="spek_moisture_rc[]" placeholder="Moisture" id="spek_moisture_rc1" class="form-control spek_moisture_list_rc" /></td> 
                    <td><input type="text" name="spek_residue_rc[]" placeholder="Residue" id="spek_residue_rc1" class="form-control spek_residue_list_rc" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_rd">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_rd" rowspan="1">RD</th>
                    <td><input type="text" name="spek_rpm_rd[]" placeholder="RPM" id="spek_rpm_rd1" class="form-control spek_rpm_list_rd" /></td>
                    <td><input type="text" name="spek_particle_size_rd[]" placeholder="Particle Size" id="spek_particle_size_rd1" class="form-control spek_particle_size_list_rd" /></td> 
                    <td><input type="text" name="spek_ssa_rd[]" placeholder="SSA" id="spek_ssa_rd1" class="form-control spek_ssa_list_rd" /></td> 
                    <td><input type="text" name="spek_whiteness_rd[]" placeholder="Whiteness" id="spek_whiteness_rd1" class="form-control spek_whiteness_list_rd" /></td> 
                    <td><input type="text" name="spek_moisture_rd[]" placeholder="Moisture" id="spek_moisture_rd1" class="form-control spek_moisture_list_rd" /></td> 
                    <td><input type="text" name="spek_residue_rd[]" placeholder="Residue" id="spek_residue_rd1" class="form-control spek_residue_list_rd" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_re">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_re" rowspan="1">RE</th>
                    <td><input type="text" name="spek_rpm_re[]" placeholder="RPM" id="spek_rpm_re1" class="form-control spek_rpm_list_re" /></td>
                    <td><input type="text" name="spek_particle_size_re[]" placeholder="Particle Size" id="spek_particle_size_re1" class="form-control spek_particle_size_list_re" /></td> 
                    <td><input type="text" name="spek_ssa_re[]" placeholder="SSA" id="spek_ssa_re1" class="form-control spek_ssa_list_re" /></td> 
                    <td><input type="text" name="spek_whiteness_re[]" placeholder="Whiteness" id="spek_whiteness_re1" class="form-control spek_whiteness_list_re" /></td> 
                    <td><input type="text" name="spek_moisture_re[]" placeholder="Moisture" id="spek_moisture_re1" class="form-control spek_moisture_list_re" /></td> 
                    <td><input type="text" name="spek_residue_re[]" placeholder="Residue" id="spek_residue_re1" class="form-control spek_residue_list_re" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_rf">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_rf" rowspan="1">RF</th>
                    <td><input type="text" name="spek_rpm_rf[]" placeholder="RPM" id="spek_rpm_rf1" class="form-control spek_rpm_list_rf" /></td>
                    <td><input type="text" name="spek_particle_size_rf[]" placeholder="Particle Size" id="spek_particle_size_rf1" class="form-control spek_particle_size_list_rf" /></td> 
                    <td><input type="text" name="spek_ssa_rf[]" placeholder="SSA" id="spek_ssa_rf1" class="form-control spek_ssa_list_rf" /></td> 
                    <td><input type="text" name="spek_whiteness_rf[]" placeholder="Whiteness" id="spek_whiteness_rf1" class="form-control spek_whiteness_list_rf" /></td> 
                    <td><input type="text" name="spek_moisture_rf[]" placeholder="Moisture" id="spek_moisture_rf1" class="form-control spek_moisture_list_rf" /></td> 
                    <td><input type="text" name="spek_residue_rf[]" placeholder="Residue" id="spek_residue_rf1" class="form-control spek_residue_list_rf" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="dynamic_field_spek_rencana_produksi_rg">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="spek_mesin_rg" rowspan="1">RG</th>
                    <td><input type="text" name="spek_rpm_rg[]" placeholder="RPM" id="spek_rpm_rg1" class="form-control spek_rpm_list_rg" /></td>
                    <td><input type="text" name="spek_particle_size_rg[]" placeholder="Particle Size" id="spek_particle_size_rg1" class="form-control spek_particle_size_list_rg" /></td> 
                    <td><input type="text" name="spek_ssa_rg[]" placeholder="SSA" id="spek_ssa_rg1" class="form-control spek_ssa_list_rg" /></td> 
                    <td><input type="text" name="spek_whiteness_rg[]" placeholder="Whiteness" id="spek_whiteness_rg1" class="form-control spek_whiteness_list_rg" /></td> 
                    <td><input type="text" name="spek_moisture_rg[]" placeholder="Moisture" id="spek_moisture_rg1" class="form-control spek_moisture_list_rg" /></td> 
                    <td><input type="text" name="spek_residue_rg[]" placeholder="Residue" id="spek_residue_rg1" class="form-control spek_residue_list_rg" /></td>  
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

  <div class="modal fade" id="modal_edit_rencana_produksi">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Rencana Produksi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" class="edit_rencana_produksi_form" id="edit_rencana_produksi_form" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label>Tanggal Rencana</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" name="edit_tanggal_rencana" id="edit_tanggal_rencana" autocomplete="off" placeholder="Tanggal Rencana">
                  <input type="hidden" class="form-control" name="edit_nomor_rencana_produksi" id="edit_nomor_rencana_produksi">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <!-- <div class="col-6">
              <div class="form-group">
                <label for="referensi">Referensi</label>
                <select id="referensi" name="referensi" class="form-control">
                </select>
              </div>
            </div> -->
            <div class="col-6">
              <div class="form-group">
                <label for="edit_referensi">Referensi</label>
                <select id="edit_referensi" name="edit_referensi[]" class="form-control select2 edit_referensi" style="width: 100%;"></select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="edit_keterangan">Keterangan</label>
                <textarea class="form-control" rows="3" name="edit_keterangan" id="edit_keterangan" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="col-6" style="font-weight: bold; padding-left: 30px; border-spacing: 15px;">
              <div class="form-group">
                <label>&nbsp</label>
                <p id="show_edit_custid"></p>
                <p id="show_edit_tanggal_order"></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group edit-lihat-table">
                <!-- <table class="table table-bordered" id="dynamic_field_rencana_produksi">  
                  <tr>
                    <th width="35%">Mesin</th>
                    <th width="35%">Jenis Produk</th>
                    <th>Jumlah Sak</th>
                    <th width="11%"></th>
                  </tr>
                  <tr>  
                    <td><select name="mesin[]" id="select_mesin" class="form-control mesin_list"></select></td>
                    <td><select name="jenis_produk[]" id="select_jenis_produk" class="form-control jenis_produk_list"></select></td>
                    <td><input type="text" name="jumlah_sak[]" id="input_jumlah_sak" placeholder="Jumlah Sak" class="form-control jumlah_sak_list" /></td>  
                    <td><button type="button" name="add_data_rencana" id="add_data_rencana" class="btn btn-success">Add More</button></td>  
                  </tr>  
                </table> -->
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_sa">  
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" width="10%">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;" width="40%">Jenis Produk</th>
                    <th style="vertical-align : middle; text-align: center;">Jumlah Sak</th>
                    <th style="vertical-align : middle; text-align: center;">Jumlah Tonase</th>
                    <th style="vertical-align : middle; text-align: center;" width="5%"></th>
                  </tr>
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" id="edit_mesin_sa" rowspan="1">SA</th>
                    <td><select name="edit_jenis_produk_sa[]" id="edit_select_jenis_produk_sa1" class="form-control edit_jenis_produk_list_sa"></select><input type="hidden" name="edit_jenis_produk_lama_sa[]" id="edit_jenis_produk_lama_sa1" class="form-control edit_jumlah_sak_lama_list_sa" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_sa1" id="edit_nomor_rencana_produksi_detail_sa1" class="form-control edit_nomor_rencana_produksi_detail_list_sa" /></td>
                    <td><input type="text" name="edit_jumlah_sak_sa[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_sa1" class="form-control edit_jumlah_sak_list_sa" /><input type="hidden" name="edit_weight_sa[]" id="edit_weight_sa1" class="form-control edit_weight_list_sa" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_sa[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_sa1" class="form-control edit_jumlah_tonase_list_sa" /></td>  
                    <td style="vertical-align : middle; text-align: center;"><button type="button" name="edit_add_data_rencana_sa" id="edit_add_data_rencana_sa" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>  
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_sb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_sb" rowspan="1">SB</th>
                    <td width="40%"><select name="edit_jenis_produk_sb[]" id="edit_select_jenis_produk_sb1" class="form-control edit_jenis_produk_list_sb"></select><input type="hidden" name="edit_jenis_produk_lama_sb[]" id="edit_jenis_produk_lama_sb1" class="form-control edit_jumlah_sak_lama_list_sb" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_sb1" id="edit_nomor_rencana_produksi_detail_sb1" class="form-control edit_nomor_rencana_produksi_detail_list_sb" /></td>
                    <td><input type="text" name="edit_jumlah_sak_sb[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_sb1" class="form-control edit_jumlah_sak_list_sb" /><input type="hidden" name="edit_weight_sb[]" id="edit_weight_sb1" class="form-control edit_weight_list_sb" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_sb[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_sb1" class="form-control edit_jumlah_tonase_list_sb" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_sb" id="edit_add_data_rencana_sb" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_mixer">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_mixer" rowspan="1">Mixer</th>
                    <td width="40%"><select name="edit_jenis_produk_mixer[]" id="edit_select_jenis_produk_mixer1" class="form-control edit_jenis_produk_list_mixer"></select><input type="hidden" name="edit_jenis_produk_lama_mixer[]" id="edit_jenis_produk_lama_mixer1" class="form-control edit_jumlah_sak_lama_list_mixer" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_mixer1" id="edit_nomor_rencana_produksi_detail_mixer1" class="form-control edit_nomor_rencana_produksi_detail_list_mixer" /></td>
                    <td><input type="text" name="edit_jumlah_sak_mixer[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_mixer1" class="form-control edit_jumlah_sak_list_mixer" /><input type="hidden" name="edit_weight_mixer[]" id="edit_weight_mixer1" class="form-control edit_weight_list_mixer" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_mixer[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_mixer1" class="form-control edit_jumlah_tonase_list_mixer" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_mixer" id="edit_add_data_rencana_mixer" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_ra">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_ra" rowspan="1">RA</th>
                    <td width="40%"><select name="edit_jenis_produk_ra[]" id="edit_select_jenis_produk_ra1" class="form-control edit_jenis_produk_list_ra"></select><input type="hidden" name="edit_jenis_produk_lama_ra[]" id="edit_jenis_produk_lama_ra1" class="form-control edit_jumlah_sak_lama_list_ra" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_ra1" id="edit_nomor_rencana_produksi_detail_ra1" class="form-control edit_nomor_rencana_produksi_detail_list_ra" /></td>
                    <td><input type="text" name="edit_jumlah_sak_ra[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_ra1" class="form-control edit_jumlah_sak_list_ra" /><input type="hidden" name="edit_weight_ra[]" id="edit_weight_ra1" class="form-control edit_weight_list_ra" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_ra[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_ra1" class="form-control edit_jumlah_tonase_list_ra" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_ra" id="edit_add_data_rencana_ra" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_rb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rb" rowspan="1">RB</th>
                    <td width="40%"><select name="edit_jenis_produk_rb[]" id="edit_select_jenis_produk_rb1" class="form-control edit_jenis_produk_list_rb"></select><input type="hidden" name="edit_jenis_produk_lama_rb[]" id="edit_jenis_produk_lama_rb1" class="form-control edit_jumlah_sak_lama_list_rb" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_rb1" id="edit_nomor_rencana_produksi_detail_rb1" class="form-control edit_nomor_rencana_produksi_detail_list_rb" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rb[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rb1" class="form-control edit_jumlah_sak_list_rb" /><input type="hidden" name="edit_weight_rb[]" id="edit_weight_rb1" class="form-control edit_weight_list_rb" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rb[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rb1" class="form-control edit_jumlah_tonase_list_rb" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_rb" id="edit_add_data_rencana_rb" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_rc">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rc" rowspan="1">RC</th>
                    <td width="40%"><select name="edit_jenis_produk_rc[]" id="edit_select_jenis_produk_rc1" class="form-control edit_jenis_produk_list_rc"></select><input type="hidden" name="edit_jenis_produk_lama_rc[]" id="edit_jenis_produk_lama_rc1" class="form-control edit_jumlah_sak_lama_list_rc" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_rc1" id="edit_nomor_rencana_produksi_detail_rc1" class="form-control edit_nomor_rencana_produksi_detail_list_rc" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rc[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rc1" class="form-control edit_jumlah_sak_list_rc" /><input type="hidden" name="edit_weight_rc[]" id="edit_weight_rc1" class="form-control edit_weight_list_rc" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rc[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rc1" class="form-control edit_jumlah_tonase_list_rc" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_rc" id="edit_add_data_rencana_rc" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_rd">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rd" rowspan="1">RD</th>
                    <td width="40%"><select name="edit_jenis_produk_rd[]" id="edit_select_jenis_produk_rd1" class="form-control edit_jenis_produk_list_rd"></select><input type="hidden" name="edit_jenis_produk_lama_rd[]" id="edit_jenis_produk_lama_rd1" class="form-control edit_jumlah_sak_lama_list_rd" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_rd1" id="edit_nomor_rencana_produksi_detail_rd1" class="form-control edit_nomor_rencana_produksi_detail_list_rd" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rd[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rd1" class="form-control edit_jumlah_sak_list_rd" /><input type="hidden" name="edit_weight_rd[]" id="edit_weight_rd1" class="form-control edit_weight_list_rd" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rd[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rd1" class="form-control edit_jumlah_tonase_list_rd" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_rd" id="edit_add_data_rencana_rd" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_re">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_re" rowspan="1">RE</th>
                    <td width="40%"><select name="edit_jenis_produk_re[]" id="edit_select_jenis_produk_re1" class="form-control edit_jenis_produk_list_re"></select><input type="hidden" name="edit_jenis_produk_lama_re[]" id="edit_jenis_produk_lama_re1" class="form-control edit_jumlah_sak_lama_list_re" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_re1" id="edit_nomor_rencana_produksi_detail_re1" class="form-control edit_nomor_rencana_produksi_detail_list_re" /></td>
                    <td><input type="text" name="edit_jumlah_sak_re[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_re1" class="form-control edit_jumlah_sak_list_re" /><input type="hidden" name="edit_weight_re[]" id="edit_weight_re1" class="form-control edit_weight_list_re" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_re[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_re1" class="form-control edit_jumlah_tonase_list_re" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_re" id="edit_add_data_rencana_re" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_rf">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rf" rowspan="1">RF</th>
                    <td width="40%"><select name="edit_jenis_produk_rf[]" id="edit_select_jenis_produk_rf1" class="form-control edit_jenis_produk_list_rf"></select><input type="hidden" name="edit_jenis_produk_lama_rf[]" id="edit_jenis_produk_lama_rf1" class="form-control edit_jumlah_sak_lama_list_rf" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_rf1" id="edit_nomor_rencana_produksi_detail_rf1" class="form-control edit_nomor_rencana_produksi_detail_list_rf" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rf[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rf1" class="form-control edit_jumlah_sak_list_rf" /><input type="hidden" name="edit_weight_rf[]" id="edit_weight_rf1" class="form-control edit_weight_list_rf" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rf[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rf1" class="form-control edit_jumlah_tonase_list_rf" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_rf" id="edit_add_data_rencana_rf" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_rencana_produksi_rg">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_mesin_rg" rowspan="1">RG</th>
                    <td width="40%"><select name="edit_jenis_produk_rg[]" id="edit_select_jenis_produk_rg1" class="form-control edit_jenis_produk_list_rg"></select><input type="hidden" name="edit_jenis_produk_lama_rg[]" id="edit_jenis_produk_lama_rg1" class="form-control edit_jumlah_sak_lama_list_rg" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_rg1" id="edit_nomor_rencana_produksi_detail_rg1" class="form-control edit_nomor_rencana_produksi_detail_list_rg" /></td>
                    <td><input type="text" name="edit_jumlah_sak_rg[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_rg1" class="form-control edit_jumlah_sak_list_rg" /><input type="hidden" name="edit_weight_rg[]" id="edit_weight_rg1" class="form-control edit_weight_list_rg" /></td>
                    <td><input type="text" name="edit_jumlah_tonase_rg[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_rg1" class="form-control edit_jumlah_tonase_list_rg" /></td>  
                    <td style="vertical-align : middle; text-align: center;" width="5%"><button type="button" name="edit_add_data_rencana_rg" id="edit_add_data_rencana_rg" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>  
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

  <div class="modal fade" id="modal_edit_spek_rencana_produksi">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Spesifikasi Lab Rencana Produksi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="post" class="edit_spek_rencana_produksi_form" id="edit_spek_rencana_produksi_form" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-12">
              <input type="hidden" class="form-control" name="edit_nomor_rencana_produksi_spek" id="edit_nomor_rencana_produksi_spek">
              <div class="form-group lihat-table-spek">
                <!-- <table class="table table-bordered" id="dynamic_field_rencana_produksi">  
                  <tr>
                    <th width="35%">Mesin</th>
                    <th width="35%">Jenis Produk</th>
                    <th>Jumlah Sak</th>
                    <th width="11%"></th>
                  </tr>
                  <tr>  
                    <td><select name="mesin[]" id="select_mesin" class="form-control mesin_list"></select></td>
                    <td><select name="jenis_produk[]" id="select_jenis_produk" class="form-control jenis_produk_list"></select></td>
                    <td><input type="text" name="jumlah_sak[]" id="input_jumlah_sak" placeholder="Jumlah Sak" class="form-control jumlah_sak_list" /></td>  
                    <td><button type="button" name="add_data_rencana" id="add_data_rencana" class="btn btn-success">Add More</button></td>  
                  </tr>  
                </table> -->
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_sa">  
                  <tr>
                    <th style="vertical-align : middle; text-align: center;" width="10%">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;">RPM</th>
                    <th style="vertical-align : middle; text-align: center;">Particle Size</th>
                    <th style="vertical-align : middle; text-align: center;">SSA</th>
                    <th style="vertical-align : middle; text-align: center;">Whiteness</th>
                    <th style="vertical-align : middle; text-align: center;">Moisture</th>
                    <th style="vertical-align : middle; text-align: center;">Residue</th>
                  </tr>
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_sa" rowspan="1">SA</th>
                    <td><input type="text" name="edit_spek_rpm_sa[]" placeholder="RPM" id="edit_spek_rpm_sa1" class="form-control edit_spek_rpm_list_sa" /></td>
                    <td><input type="text" name="edit_spek_particle_size_sa[]" placeholder="Particle Size" id="edit_spek_particle_size_sa1" class="form-control edit_spek_particle_size_list_sa" /></td> 
                    <td><input type="text" name="edit_spek_ssa_sa[]" placeholder="SSA" id="edit_spek_ssa_sa1" class="form-control edit_spek_ssa_list_sa" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_sa[]" placeholder="Whiteness" id="edit_spek_whiteness_sa1" class="form-control edit_spek_whiteness_list_sa" /></td> 
                    <td><input type="text" name="edit_spek_moisture_sa[]" placeholder="Moisture" id="edit_spek_moisture_sa1" class="form-control edit_spek_moisture_list_sa" /></td> 
                    <td><input type="text" name="edit_spek_residue_sa[]" placeholder="Residue" id="edit_spek_residue_sa1" class="form-control edit_spek_residue_list_sa" /></td>   
                  </tr>  
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_sb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_sb" rowspan="1">SB</th>
                    <td><input type="text" name="edit_spek_rpm_sb[]" placeholder="RPM" id="edit_spek_rpm_sb1" class="form-control edit_spek_rpm_list_sb" /></td>
                    <td><input type="text" name="edit_spek_particle_size_sb[]" placeholder="Particle Size" id="edit_spek_particle_size_sb1" class="form-control edit_spek_particle_size_list_sb" /></td> 
                    <td><input type="text" name="edit_spek_ssa_sb[]" placeholder="SSA" id="edit_spek_ssa_sb1" class="form-control edit_spek_ssa_list_sb" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_sb[]" placeholder="Whiteness" id="edit_spek_whiteness_sb1" class="form-control edit_spek_whiteness_list_sb" /></td> 
                    <td><input type="text" name="edit_spek_moisture_sb[]" placeholder="Moisture" id="edit_spek_moisture_sb1" class="form-control edit_spek_moisture_list_sb" /></td> 
                    <td><input type="text" name="edit_spek_residue_sb[]" placeholder="Residue" id="edit_spek_residue_sb1" class="form-control edit_spek_residue_list_sb" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_mixer">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_mixer" rowspan="1">Mixer</th>
                    <td><input type="text" name="edit_spek_rpm_mixer[]" placeholder="RPM" id="edit_spek_rpm_mixer1" class="form-control edit_spek_rpm_list_mixer" /></td>
                    <td><input type="text" name="edit_spek_particle_size_mixer[]" placeholder="Particle Size" id="edit_spek_particle_size_mixer1" class="form-control edit_spek_particle_size_list_mixer" /></td> 
                    <td><input type="text" name="edit_spek_ssa_mixer[]" placeholder="SSA" id="edit_spek_ssa_mixer1" class="form-control edit_spek_ssa_list_mixer" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_mixer[]" placeholder="Whiteness" id="edit_spek_whiteness_mixer1" class="form-control edit_spek_whiteness_list_mixer" /></td> 
                    <td><input type="text" name="edit_spek_moisture_mixer[]" placeholder="Moisture" id="edit_spek_moisture_mixer1" class="form-control edit_spek_moisture_list_mixer" /></td> 
                    <td><input type="text" name="edit_spek_residue_mixer[]" placeholder="Residue" id="edit_spek_residue_mixer1" class="form-control edit_spek_residue_list_mixer" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_ra">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_ra" rowspan="1">RA</th>
                    <td><input type="text" name="edit_spek_rpm_ra[]" placeholder="RPM" id="edit_spek_rpm_ra1" class="form-control edit_spek_rpm_list_ra" /></td>
                    <td><input type="text" name="edit_spek_particle_size_ra[]" placeholder="Particle Size" id="edit_spek_particle_size_ra1" class="form-control edit_spek_particle_size_list_ra" /></td> 
                    <td><input type="text" name="edit_spek_ssa_ra[]" placeholder="SSA" id="edit_spek_ssa_ra1" class="form-control edit_spek_ssa_list_ra" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_ra[]" placeholder="Whiteness" id="edit_spek_whiteness_ra1" class="form-control edit_spek_whiteness_list_ra" /></td> 
                    <td><input type="text" name="edit_spek_moisture_ra[]" placeholder="Moisture" id="edit_spek_moisture_ra1" class="form-control edit_spek_moisture_list_ra" /></td> 
                    <td><input type="text" name="edit_spek_residue_ra[]" placeholder="Residue" id="edit_spek_residue_ra1" class="form-control edit_spek_residue_list_ra" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_rb">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_rb" rowspan="1">RB</th>
                    <td><input type="text" name="edit_spek_rpm_rb[]" placeholder="RPM" id="edit_spek_rpm_rb1" class="form-control edit_spek_rpm_list_rb" /></td>
                    <td><input type="text" name="edit_spek_particle_size_rb[]" placeholder="Particle Size" id="edit_spek_particle_size_rb1" class="form-control edit_spek_particle_size_list_rb" /></td> 
                    <td><input type="text" name="edit_spek_ssa_rb[]" placeholder="SSA" id="edit_spek_ssa_rb1" class="form-control edit_spek_ssa_list_rb" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_rb[]" placeholder="Whiteness" id="edit_spek_whiteness_rb1" class="form-control edit_spek_whiteness_list_rb" /></td> 
                    <td><input type="text" name="edit_spek_moisture_rb[]" placeholder="Moisture" id="edit_spek_moisture_rb1" class="form-control edit_spek_moisture_list_rb" /></td> 
                    <td><input type="text" name="edit_spek_residue_rb[]" placeholder="Residue" id="edit_spek_residue_rb1" class="form-control edit_spek_residue_list_rb" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_rc">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_rc" rowspan="1">RC</th>
                    <td><input type="text" name="edit_spek_rpm_rc[]" placeholder="RPM" id="edit_spek_rpm_rc1" class="form-control edit_spek_rpm_list_rc" /></td>
                    <td><input type="text" name="edit_spek_particle_size_rc[]" placeholder="Particle Size" id="edit_spek_particle_size_rc1" class="form-control edit_spek_particle_size_list_rc" /></td> 
                    <td><input type="text" name="edit_spek_ssa_rc[]" placeholder="SSA" id="edit_spek_ssa_rc1" class="form-control edit_spek_ssa_list_rc" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_rc[]" placeholder="Whiteness" id="edit_spek_whiteness_rc1" class="form-control edit_spek_whiteness_list_rc" /></td> 
                    <td><input type="text" name="edit_spek_moisture_rc[]" placeholder="Moisture" id="edit_spek_moisture_rc1" class="form-control edit_spek_moisture_list_rc" /></td> 
                    <td><input type="text" name="edit_spek_residue_rc[]" placeholder="Residue" id="edit_spek_residue_rc1" class="form-control edit_spek_residue_list_rc" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_rd">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_rd" rowspan="1">RD</th>
                    <td><input type="text" name="edit_spek_rpm_rd[]" placeholder="RPM" id="edit_spek_rpm_rd1" class="form-control edit_spek_rpm_list_rd" /></td>
                    <td><input type="text" name="edit_spek_particle_size_rd[]" placeholder="Particle Size" id="edit_spek_particle_size_rd1" class="form-control edit_spek_particle_size_list_rd" /></td> 
                    <td><input type="text" name="edit_spek_ssa_rd[]" placeholder="SSA" id="edit_spek_ssa_rd1" class="form-control edit_spek_ssa_list_rd" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_rd[]" placeholder="Whiteness" id="edit_spek_whiteness_rd1" class="form-control edit_spek_whiteness_list_rd" /></td> 
                    <td><input type="text" name="edit_spek_moisture_rd[]" placeholder="Moisture" id="edit_spek_moisture_rd1" class="form-control edit_spek_moisture_list_rd" /></td> 
                    <td><input type="text" name="edit_spek_residue_rd[]" placeholder="Residue" id="edit_spek_residue_rd1" class="form-control edit_spek_residue_list_rd" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_re">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_re" rowspan="1">RE</th>
                    <td><input type="text" name="edit_spek_rpm_re[]" placeholder="RPM" id="edit_spek_rpm_re1" class="form-control edit_spek_rpm_list_re" /></td>
                    <td><input type="text" name="edit_spek_particle_size_re[]" placeholder="Particle Size" id="edit_spek_particle_size_re1" class="form-control edit_spek_particle_size_list_re" /></td> 
                    <td><input type="text" name="edit_spek_ssa_re[]" placeholder="SSA" id="edit_spek_ssa_re1" class="form-control edit_spek_ssa_list_re" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_re[]" placeholder="Whiteness" id="edit_spek_whiteness_re1" class="form-control edit_spek_whiteness_list_re" /></td> 
                    <td><input type="text" name="edit_spek_moisture_re[]" placeholder="Moisture" id="edit_spek_moisture_re1" class="form-control edit_spek_moisture_list_re" /></td> 
                    <td><input type="text" name="edit_spek_residue_re[]" placeholder="Residue" id="edit_spek_residue_re1" class="form-control edit_spek_residue_list_re" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_rf">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_rf" rowspan="1">RF</th>
                    <td><input type="text" name="edit_spek_rpm_rf[]" placeholder="RPM" id="edit_spek_rpm_rf1" class="form-control edit_spek_rpm_list_rf" /></td>
                    <td><input type="text" name="edit_spek_particle_size_rf[]" placeholder="Particle Size" id="edit_spek_particle_size_rf1" class="form-control edit_spek_particle_size_list_rf" /></td> 
                    <td><input type="text" name="edit_spek_ssa_rf[]" placeholder="SSA" id="edit_spek_ssa_rf1" class="form-control edit_spek_ssa_list_rf" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_rf[]" placeholder="Whiteness" id="edit_spek_whiteness_rf1" class="form-control edit_spek_whiteness_list_rf" /></td> 
                    <td><input type="text" name="edit_spek_moisture_rf[]" placeholder="Moisture" id="edit_spek_moisture_rf1" class="form-control edit_spek_moisture_list_rf" /></td> 
                    <td><input type="text" name="edit_spek_residue_rf[]" placeholder="Residue" id="edit_spek_residue_rf1" class="form-control edit_spek_residue_list_rf" /></td>  
                  </tr>
                </table>
                <table class="table table-bordered" id="edit_dynamic_field_spek_rencana_produksi_rg">
                  <tr>  
                    <th style="vertical-align : middle; text-align: center;" width="10%" id="edit_spek_mesin_rg" rowspan="1">RG</th>
                    <td><input type="text" name="edit_spek_rpm_rg[]" placeholder="RPM" id="edit_spek_rpm_rg1" class="form-control edit_spek_rpm_list_rg" /></td>
                    <td><input type="text" name="edit_spek_particle_size_rg[]" placeholder="Particle Size" id="edit_spek_particle_size_rg1" class="form-control edit_spek_particle_size_list_rg" /></td> 
                    <td><input type="text" name="edit_spek_ssa_rg[]" placeholder="SSA" id="edit_spek_ssa_rg1" class="form-control edit_spek_ssa_list_rg" /></td> 
                    <td><input type="text" name="edit_spek_whiteness_rg[]" placeholder="Whiteness" id="edit_spek_whiteness_rg1" class="form-control edit_spek_whiteness_list_rg" /></td> 
                    <td><input type="text" name="edit_spek_moisture_rg[]" placeholder="Moisture" id="edit_spek_moisture_rg1" class="form-control edit_spek_moisture_list_rg" /></td> 
                    <td><input type="text" name="edit_spek_residue_rg[]" placeholder="Residue" id="edit_spek_residue_rg1" class="form-control edit_spek_residue_list_rg" /></td>  
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

  <div class="modal fade" id="modal_lihat_rencana_produksi">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="title_lihat_rencana_produksi">View Data Rencana Produksi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
           <div class="col-lg-12 lihat-table">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Tanggal Rencana</th>
                  <td width="80%" id="td_tanggal_rencana"></td>
                </tr>
                <tr>
                  <th>Nomor Referensi</th>
                  <td width="80%" id="td_referensi"></td>
                </tr>
              </thead>
            </table>
            <table class="table table-bordered table-hover" id="table_rencana1">
            </table>
            <table class="table table-bordered table-hover" id="table_rencana2">
           </table>
           <h4>DATA SPESIFIKASI</h4>
           <table class="table table-bordered table-hover" id="table_spek_rencana">
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

    $('#tanggal_rencana').flatpickr({
      allowInput: true,
      disableMobile: true
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var c_rencana_sa = 0;
    var c_rencana_sb = 0;
    var c_rencana_mixer = 0;
    var c_rencana_ra = 0;
    var c_rencana_rb = 0;
    var c_rencana_rc = 0;
    var c_rencana_rd = 0;
    var c_rencana_re = 0;
    var c_rencana_rf = 0;
    var c_rencana_rg = 0;

    var edit_c_rencana_sa = 0;
    var edit_c_rencana_sb = 0;
    var edit_c_rencana_mixer = 0;
    var edit_c_rencana_ra = 0;
    var edit_c_rencana_rb = 0;
    var edit_c_rencana_rc = 0;
    var edit_c_rencana_rd = 0;
    var edit_c_rencana_re = 0;
    var edit_c_rencana_rf = 0;
    var edit_c_rencana_rg = 0;

    var table = $('#rencana_produksi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("rencana_produksi/view_rencana_produksi_table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_rencana_produksi',
         name:'nomor_rencana_produksi'
       },
       {
         data:'tanggal_rencana',
         name:'tanggal_rencana'
       },
       {
         data:'total_sak',
         name:'total_sak',
         render: $.fn.dataTable.render.number('.', " Sak", ',')
       },
       {
         data:'total_tonase',
         name:'total_tonase',
         render: $.fn.dataTable.render.number('.', " Ton", ',')
       },
       {
         data:'action',
         name:'action',
         width:'20%'
       }
       ]
     });

    function load_data_rencana_produksi(from_date = '', to_date = '')
     {
      table = $('#rencana_produksi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("rencana_produksi/view_rencana_produksi_table") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_rencana_produksi',
         name:'nomor_rencana_produksi'
       },
       {
         data:'tanggal_rencana',
         name:'tanggal_rencana'
       },
       {
         data:'total_sak',
         name:'total_sak',
         render: $.fn.dataTable.render.number('.', " Sak", ',')
       },
       {
         data:'total_tonase',
         name:'total_tonase',
         render: $.fn.dataTable.render.number('.', " Ton", ',')
       },
       {
         data:'action',
         name:'action',
         width:'20%'
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
        $('#rencana_produksi_table').DataTable().destroy();
        load_data_rencana_produksi(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });

    $('#refresh').click(function(){
      $('#filter_tanggal').val('');
      $('#rencana_produksi_table').DataTable().destroy();
      load_data_rencana_produksi();
    });

    $('body').on('click', '#btn_input_rencana_produksi', function (event) {
      event.preventDefault();

      $(".loading").show();
      setTimeout(function() {
        $(".loading").hide();
        $('#modal_input_rencana_produksi').modal();
      }, 6000);

      var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      $.each(arr_mesin, function(k, v) {
        var isLastElement = k == arr_mesin.length -1;
        
        for(var i = 2; i <= window['c_rencana_'+v]; i++){
          $('#row_data_rencana_'+v+i).remove();
        }

        window['c_rencana_'+v] = 1;

        var url_jenis_produk = "{{ url('get_jenis_produk') }}";
        $.get(url_jenis_produk, function (data) {
          $('#select_jenis_produk_'+v+window['c_rencana_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
          $.each(data, function(l, m) {
            $('#select_jenis_produk_'+v+window['c_rencana_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
          });
        })

        $("#select_jenis_produk_"+v+window['c_rencana_'+v]).change(function() {
          var val = $(this).val();
          var url_weight = "{{ url('get_weight/kode_produk') }}";
          url_weight = url_weight.replace('kode_produk', enc(val.toString()));
          $.get(url_weight, function (data) {
            $('#weight_'+v+window['c_rencana_'+v]).val(data.weight);
            var a = $("#jumlah_sak_"+v+window['c_rencana_'+v]).val();
            if(a != null || a != ''){
              var b = data.weight;
              var total = a * b;
              $("#jumlah_tonase_"+v+window['c_rencana_'+v]).val(total);
            }
          })
        });

        $('#jumlah_sak_'+v+window['c_rencana_'+v]).on('keyup', function(){
          if($("#weight_"+v+window['c_rencana_'+v]).val() != null || $("#weight_"+v+window['c_rencana_'+v]).val() != ''){
            var a = $("#jumlah_sak_"+v+window['c_rencana_'+v]).val();
            var b = $("#weight_"+v+window['c_rencana_'+v]).val();
            var total = a * b;
            $("#jumlah_tonase_"+v+window['c_rencana_'+v]).val(total);
          }
        });

        $('#add_data_rencana_'+v).unbind().click(function(){
          $('#add_data_rencana_'+v).hide();
          window['c_rencana_'+v]++;
          $('#mesin_'+v).attr('rowspan', window['c_rencana_'+v]);
          $('#dynamic_field_rencana_produksi_'+v).append('<tr id="row_data_rencana_'+v+window['c_rencana_'+v]+'"><td><select name="jenis_produk_'+v+'[]" class="form-control jenis_produk_list_'+v+'" id="select_jenis_produk_'+v+window['c_rencana_'+v]+'"></select></td><td><input type="text" name="jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="jumlah_sak_'+v+window['c_rencana_'+v]+'" class="form-control jumlah_sak_list_'+v+'" /><input type="hidden" name="weight_'+v+'[]" id="weight_'+v+window['c_rencana_'+v]+'" class="form-control weight_list_'+v+'" /></td><td><input type="text" name="jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="jumlah_tonase_'+v+window['c_rencana_'+v]+'" class="form-control jumlah_tonase_list_'+v+'" /></td><td><button type="button" name="data_rencana_remove_'+v+'" id="'+window['c_rencana_'+v]+'" class="btn btn-danger btn_remove_rencana_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
          var url_jenis_produk = "{{ url('get_jenis_produk') }}";
          $.get(url_jenis_produk, function (data) {
            $('#select_jenis_produk_'+v+window['c_rencana_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
            $.each(data, function(l, m) {
              $('#select_jenis_produk_'+v+window['c_rencana_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
            });
          })

          $("#select_jenis_produk_"+v+window['c_rencana_'+v]).change(function() {
            var val = $(this).val();
            var url_weight = "{{ url('get_weight/kode_produk') }}";
            url_weight = url_weight.replace('kode_produk', enc(val.toString()));
            $.get(url_weight, function (data) {
              $('#weight_'+v+window['c_rencana_'+v]).val(data.weight);
              var a = $("#jumlah_sak_"+v+window['c_rencana_'+v]).val();
              if(a != null || a != ''){
                var b = data.weight;
                var total = a * b;
                $("#jumlah_tonase_"+v+window['c_rencana_'+v]).val(total);
              }
            })
          });

          $('#jumlah_sak_'+v+window['c_rencana_'+v]).on('keyup', function(){
            if($("#weight_"+v+window['c_rencana_'+v]).val() != null || $("#weight_"+v+window['c_rencana_'+v]).val() != ''){
              var a = $("#jumlah_sak_"+v+window['c_rencana_'+v]).val();
              var b = $("#weight_"+v+window['c_rencana_'+v]).val();
              var total = a * b;
              $("#jumlah_tonase_"+v+window['c_rencana_'+v]).val(total);
            }
          });

          setTimeout(function(){
            $('#add_data_rencana_'+v).show();
          }, 1000);
        });

        $(document).on('click', '.btn_remove_rencana_produksi_'+v, function(){  
          var button_id = $(this).attr("id");   
          $('#row_data_rencana_'+v+button_id+'').remove();
          window['c_rencana_'+v]--;
        });
      });

      // $("#referensi").on("input", function(e) {
      //   e.stopImmediatePropagation();
      //   var value = $(this).val();

      //   var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      //   $.each(arr_mesin, function(k, v) {
      //     $('#select_jenis_produk_'+v+1).val('').trigger('change');
      //     $('#weight_'+v+1).val('');
      //     $('#jumlah_sak_'+v+1).val('');
      //     $('#jumlah_tonase_'+v+1).val('');
      //     for(var a = 2; a <= window['c_rencana_'+v]; a++){
      //       $('#row_data_rencana_'+v+a).remove();
      //     }

      //     window['c_rencana_'+v] = 0;

      //   });

      //   var url_detail_ref = "{{ url('get_detail_ref_rencana_produksi/nomor_order_receipt') }}";
      //   url_detail_ref = url_detail_ref.replace("nomor_order_receipt", enc(value.toString()));
      //   $.get(url_detail_ref, function (data_dt) {
      //     if(typeof data_dt !== 'undefined' && data_dt.length > 0){
      //       $('#show_custid').html("Customer : " + data_dt[0].custname);
      //       $('#show_tanggal_order').html("Tanggal Order : " + data_dt[0].tanggal_order);
      //       for(let i = 0; i < data_dt.length; i++){
      //         window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]++;

      //         if(window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]] == 1){

      //           var url_jenis_produk = "{{ url('get_jenis_produk') }}";
      //           $.get(url_jenis_produk, function (data) {
      //             $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
      //             $.each(data, function(l, m) {
      //               $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
      //             });

      //             $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jenis_produk).trigger('change');
      //           })

      //           $('#weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].weight);
      //           $('#jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_sak);
      //           $('#jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_tonase);
      //         }else{
      //           $('#mesin_'+arr_mesin[data_dt[i].mesin - 1]).attr('rowspan', window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]);
      //           $('#dynamic_field_rencana_produksi_'+arr_mesin[data_dt[i].mesin - 1]).append('<tr id="row_data_rencana_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'"><td><select name="jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+'[]" class="form-control jenis_produk_list_'+arr_mesin[data_dt[i].mesin - 1]+'" id="select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'"></select></td><td><input type="text" name="jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+'[]" placeholder="Jumlah Sak" id="jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control jumlah_sak_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /><input type="hidden" name="weight_'+arr_mesin[data_dt[i].mesin - 1]+'[]" id="weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control weight_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /></td><td><input type="text" name="jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+'[]" placeholder="Jumlah Tonase" id="jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="form-control jumlah_tonase_list_'+arr_mesin[data_dt[i].mesin - 1]+'" /></td><td><button type="button" name="data_rencana_remove_'+arr_mesin[data_dt[i].mesin - 1]+'" id="'+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]+'" class="btn btn-danger btn_remove_rencana_produksi_'+arr_mesin[data_dt[i].mesin - 1]+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');

      //           var url_jenis_produk = "{{ url('get_jenis_produk') }}";
      //           $.get(url_jenis_produk, function (data) {
      //             $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
      //             $.each(data, function(l, m) {
      //               $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
      //             });

      //             $('#select_jenis_produk_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jenis_produk).trigger('change');
      //           })

      //           $('#weight_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].weight);
      //           $('#jumlah_sak_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_sak);
      //           $('#jumlah_tonase_'+arr_mesin[data_dt[i].mesin - 1]+window['c_rencana_'+arr_mesin[data_dt[i].mesin - 1]]).val(data_dt[i].jumlah_tonase);
      //         }
      //       }
      //     }
      //   })
      // });
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

    $('body').on('click', '#input-data-spek-rencana-produksi', function () {
      var nomor = $(this).data("id");

      $('#nomor_rencana_produksi_spek').val(nomor);

      $('#modal_input_spek_rencana_produksi').modal();
    });

    $('body').on('click', '#edit-data', function (event) {
      event.preventDefault();

      var nomor = $(this).data("id");
      $('#edit_nomor_rencana_produksi').val(nomor);
      $(this).prop("disabled", true);
      $(this).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
      );
      $(".loading").show();
      setTimeout(function() {
        $("#edit-data").prop("disabled", false);
        $("#edit-data").html('<i class="fa fa-edit"></i>');
        $(".loading").hide();
        $('#modal_edit_rencana_produksi').modal();
      }, 12000);

      var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

      var url_data = "{{ url('rencana_produksi/detail_rencana/nomor') }}";
      url_data = url_data.replace('nomor', enc(nomor.toString()));
      $.get(url_data, function (data_prd) {
        console.log(data_prd['data_referensi']);
        $('#edit_tanggal_rencana').val(data_prd['data_ren'][0].tanggal_rencana);
        // $('#edit_referensi').val(['A', 'B']).trigger('change');
        $.each(data_prd['data_referensi'], function(k, v) {
          var $newOption = $("<option selected='selected'></option>").val(v).text(v);
          $("#edit_referensi").append($newOption).trigger('change');
        });
        $('#edit_keterangan').val(data_prd['data_ren'][0].keterangan);
        $.each(arr_mesin, function(k, v) {
          for(var i = 2; i <= window['edit_c_rencana_'+v]; i++){
            $('#edit_row_data_rencana_'+v+i).remove();
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
            $('#edit_jumlah_sak_'+v+1).val(data_prd[v][0].jumlah_sak);
            $('#edit_jumlah_tonase_'+v+1).val(data_prd[v][0].jumlah_tonase);
            $('#edit_nomor_rencana_produksi_detail_'+v+1).val(data_prd[v][0].nomor_rencana_produksi_detail);
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

          $('#edit_add_data_rencana_'+v).unbind().click(function(){
            $('#edit_add_data_rencana_'+v).hide();
            window['edit_c_rencana_'+v]++;
            $('#edit_mesin_'+v).attr('rowspan', window['edit_c_rencana_'+v]);
            $('#edit_dynamic_field_rencana_produksi_'+v).append('<tr id="edit_row_data_rencana_'+v+window['edit_c_rencana_'+v]+'"><td><select name="edit_jenis_produk_'+v+'[]" class="form-control edit_jenis_produk_list_'+v+'" id="edit_select_jenis_produk_'+v+window['edit_c_rencana_'+v]+'"></select><input type="hidden" name="edit_jenis_produk_lama_'+v+'[]" id="edit_jenis_produk_lama_'+v+window['edit_c_rencana_'+v]+'" class="form-control edit_jenis_produk_lama_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_'+v+window['edit_c_rencana_'+v]+'" class="form-control edit_jumlah_sak_list_'+v+'" /><input type="hidden" name="edit_weight_'+v+'[]" id="edit_weight_'+v+window['edit_c_rencana_'+v]+'" class="form-control edit_weight_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_'+v+window['edit_c_rencana_'+v]+'" class="form-control edit_jumlah_tonase_list_'+v+'" /></td><td><button type="button" name="edit_data_rencana_remove_'+v+'" id="'+window['edit_c_rencana_'+v]+'" class="btn btn-danger edit_btn_remove_rencana_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
            var url_jenis_produk = "{{ url('get_jenis_produk') }}";
            $.get(url_jenis_produk, function (data) {
              $('#edit_select_jenis_produk_'+v+window['edit_c_rencana_'+v]).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
              $.each(data, function(l, m) {
                $('#edit_select_jenis_produk_'+v+window['edit_c_rencana_'+v]).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
              });
            })

            $("#edit_select_jenis_produk_"+v+window['edit_c_rencana_'+v]).change(function() {
              var val = $(this).val();
              $('#edit_jenis_produk_lama_'+v+window['edit_c_rencana_'+v]).val(val);
              var url_weight = "{{ url('get_weight/kode_produk') }}";
              url_weight = url_weight.replace('kode_produk', enc(val.toString()));
              $.get(url_weight, function (data) {
                $('#edit_weight_'+v+window['edit_c_rencana_'+v]).val(data.weight);
                var a = $("#edit_jumlah_sak_"+v+window['edit_c_rencana_'+v]).val();
                if(a != null || a != ''){
                  var b = data.weight;
                  var total = a * b;
                  $("#edit_jumlah_tonase_"+v+window['edit_c_rencana_'+v]).val(total);
                }
              })
            });

            $('#edit_jumlah_sak_'+v+window['edit_c_rencana_'+v]).on('keyup', function(){
              if($("#edit_weight_"+v+window['edit_c_rencana_'+v]).val() != null || $("#edit_weight_"+v+window['edit_c_rencana_'+v]).val() != ''){
                var a = $("#edit_jumlah_sak_"+v+window['edit_c_rencana_'+v]).val();
                var b = $("#edit_weight_"+v+window['edit_c_rencana_'+v]).val();
                var total = a * b;
                $("#edit_jumlah_tonase_"+v+window['edit_c_rencana_'+v]).val(total);
              }
            });

            setTimeout(function(){
              $('#edit_add_data_rencana_'+v).show();
            }, 1000);
          });

          $(document).on('click', '.edit_btn_remove_rencana_produksi_'+v, function(){  
            var button_id = $(this).attr("id");   
            var nomor = $("#edit_nomor_rencana_produksi_detail_"+v+button_id). val();

            $.ajax({
              type: "GET",
              url: "{{ url('rencana_produksi/detail/delete') }}",
              data: { 'nomor' : nomor },
              success: function (data) {
                alert("Data Deleted");
              },
              error: function (data) {
                console.log('Error:', data);
              }
            });
            $('#edit_row_data_rencana_'+v+button_id).remove();
            window['edit_c_rencana_'+v]--;
          });
        });
        
        $.each(arr_mesin, function(k, v) {
          if(data_prd[v].length > 0){
            window['edit_c_rencana_'+v] = data_prd[v].length;
          }else{
            window['edit_c_rencana_'+v] = 1;
          }
          $('#edit_mesin_'+v).attr('rowspan', window['edit_c_rencana_'+v]);
          for(let i = 1; i < window['edit_c_rencana_'+v]; i++){
            $('#edit_dynamic_field_rencana_produksi_'+v).append('<tr id="edit_row_data_rencana_'+v+(i+1)+'"><td><select name="edit_jenis_produk_'+v+'[]" class="form-control edit_jenis_produk_list_'+v+'" id="edit_select_jenis_produk_'+v+(i+1)+'"></select><input type="hidden" name="edit_jenis_produk_lama_'+v+'[]" id="edit_jenis_produk_lama_'+v+(i+1)+'" class="form-control edit_jenis_produk_lama_list_'+v+'" /><input type="hidden" name="edit_nomor_rencana_produksi_detail_'+v+(i+1)+'" id="edit_nomor_rencana_produksi_detail_'+v+(i+1)+'" class="form-control edit_nomor_rencana_produksi_detail_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_sak_'+v+'[]" placeholder="Jumlah Sak" id="edit_jumlah_sak_'+v+(i+1)+'" class="form-control edit_jumlah_sak_list_'+v+'" /><input type="hidden" name="edit_weight_'+v+'[]" id="edit_weight_'+v+(i+1)+'" class="form-control edit_weight_list_'+v+'" /></td><td><input type="text" name="edit_jumlah_tonase_'+v+'[]" placeholder="Jumlah Tonase" id="edit_jumlah_tonase_'+v+(i+1)+'" class="form-control edit_jumlah_tonase_list_'+v+'" /></td><td><button type="button" name="edit_data_rencana_remove_'+v+'" id="'+(i+1)+'" class="btn btn-danger edit_btn_remove_rencana_produksi_'+v+'"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');

            var url_jenis_produk = "{{ url('get_jenis_produk') }}";
            $.get(url_jenis_produk, function (data) {
              $('#edit_select_jenis_produk_'+v+(i+1)).children().remove().end().append('<option value="" selected>Pilih Jenis Produk</option>');
              $.each(data, function(l, m) {
                $('#edit_select_jenis_produk_'+v+(i+1)).append('<option value="' + m.jenis_produk + '">' + m.jenis_produk + '</option>');
              });
              $('#edit_jenis_produk_lama_'+v+(i+1)).val(data_prd[v][i].jenis_produk);
              $('#edit_select_jenis_produk_'+v+(i+1)).val(data_prd[v][i].jenis_produk).trigger('change');
            })
            $('#edit_jumlah_sak_'+v+(i+1)).val(data_prd[v][i].jumlah_sak);
            $('#edit_jumlah_tonase_'+v+(i+1)).val(data_prd[v][i].jumlah_tonase);
            $('#edit_nomor_rencana_produksi_detail_'+v+(i+1)).val(data_prd[v][i].nomor_rencana_produksi_detail);
          }
        });
      });
    });

    $('body').on('click', '#edit-data-spek-rencana-produksi', function () {
      var nomor = $(this).data("id");

      $('#edit_nomor_rencana_produksi_spek').val(nomor);

      $('#modal_edit_spek_rencana_produksi').modal();

      var url_spek = "{{ url('rencana_produksi/detail_spek_rencana/nomor') }}";
      url_spek = url_spek.replace('nomor', enc(nomor.toString()));
      $.get(url_spek, function (data_spek) {
        $('#edit_spek_rpm_sa1').val((data_spek.sa[0].rpm == null ? '' : data_spek.sa[0].rpm));
        $('#edit_spek_rpm_sb1').val((data_spek.sb[0].rpm == null ? '' : data_spek.sb[0].rpm));
        $('#edit_spek_rpm_mixer1').val((data_spek.mixer[0].rpm == null ? '' : data_spek.mixer[0].rpm));
        $('#edit_spek_rpm_ra1').val((data_spek.ra[0].rpm == null ? '' : data_spek.ra[0].rpm));
        $('#edit_spek_rpm_rb1').val((data_spek.rb[0].rpm == null ? '' : data_spek.rb[0].rpm));
        $('#edit_spek_rpm_rc1').val((data_spek.rc[0].rpm == null ? '' : data_spek.rc[0].rpm));
        $('#edit_spek_rpm_rd1').val((data_spek.rd[0].rpm == null ? '' : data_spek.rd[0].rpm));
        $('#edit_spek_rpm_re1').val((data_spek.re[0].rpm == null ? '' : data_spek.re[0].rpm));
        $('#edit_spek_rpm_rf1').val((data_spek.rf[0].rpm == null ? '' : data_spek.rf[0].rpm));
        $('#edit_spek_rpm_rg1').val((data_spek.rg[0].rpm == null ? '' : data_spek.rg[0].rpm));

        $('#edit_spek_particle_size_sa1').val((data_spek.sa[0].particle_size == null ? '' : data_spek.sa[0].particle_size));
        $('#edit_spek_particle_size_sb1').val((data_spek.sb[0].particle_size == null ? '' : data_spek.sb[0].particle_size));
        $('#edit_spek_particle_size_mixer1').val((data_spek.mixer[0].particle_size == null ? '' : data_spek.mixer[0].particle_size));
        $('#edit_spek_particle_size_ra1').val((data_spek.ra[0].particle_size == null ? '' : data_spek.ra[0].particle_size));
        $('#edit_spek_particle_size_rb1').val((data_spek.rb[0].particle_size == null ? '' : data_spek.rb[0].particle_size));
        $('#edit_spek_particle_size_rc1').val((data_spek.rc[0].particle_size == null ? '' : data_spek.rc[0].particle_size));
        $('#edit_spek_particle_size_rd1').val((data_spek.rd[0].particle_size == null ? '' : data_spek.rd[0].particle_size));
        $('#edit_spek_particle_size_re1').val((data_spek.re[0].particle_size == null ? '' : data_spek.re[0].particle_size));
        $('#edit_spek_particle_size_rf1').val((data_spek.rf[0].particle_size == null ? '' : data_spek.rf[0].particle_size));
        $('#edit_spek_particle_size_rg1').val((data_spek.rg[0].particle_size == null ? '' : data_spek.rg[0].particle_size));

        $('#edit_spek_ssa_sa1').val((data_spek.sa[0].ssa == null ? '' : data_spek.sa[0].ssa));
        $('#edit_spek_ssa_sb1').val((data_spek.sb[0].ssa == null ? '' : data_spek.sb[0].ssa));
        $('#edit_spek_ssa_mixer1').val((data_spek.mixer[0].ssa == null ? '' : data_spek.mixer[0].ssa));
        $('#edit_spek_ssa_ra1').val((data_spek.ra[0].ssa == null ? '' : data_spek.ra[0].ssa));
        $('#edit_spek_ssa_rb1').val((data_spek.rb[0].ssa == null ? '' : data_spek.rb[0].ssa));
        $('#edit_spek_ssa_rc1').val((data_spek.rc[0].ssa == null ? '' : data_spek.rc[0].ssa));
        $('#edit_spek_ssa_rd1').val((data_spek.rd[0].ssa == null ? '' : data_spek.rd[0].ssa));
        $('#edit_spek_ssa_re1').val((data_spek.re[0].ssa == null ? '' : data_spek.re[0].ssa));
        $('#edit_spek_ssa_rf1').val((data_spek.rf[0].ssa == null ? '' : data_spek.rf[0].ssa));
        $('#edit_spek_ssa_rg1').val((data_spek.rg[0].ssa == null ? '' : data_spek.rg[0].ssa));

        $('#edit_spek_whiteness_sa1').val((data_spek.sa[0].whiteness == null ? '' : data_spek.sa[0].whiteness));
        $('#edit_spek_whiteness_sb1').val((data_spek.sb[0].whiteness == null ? '' : data_spek.sb[0].whiteness));
        $('#edit_spek_whiteness_mixer1').val((data_spek.mixer[0].whiteness == null ? '' : data_spek.mixer[0].whiteness));
        $('#edit_spek_whiteness_ra1').val((data_spek.ra[0].whiteness == null ? '' : data_spek.ra[0].whiteness));
        $('#edit_spek_whiteness_rb1').val((data_spek.rb[0].whiteness == null ? '' : data_spek.rb[0].whiteness));
        $('#edit_spek_whiteness_rc1').val((data_spek.rc[0].whiteness == null ? '' : data_spek.rc[0].whiteness));
        $('#edit_spek_whiteness_rd1').val((data_spek.rd[0].whiteness == null ? '' : data_spek.rd[0].whiteness));
        $('#edit_spek_whiteness_re1').val((data_spek.re[0].whiteness == null ? '' : data_spek.re[0].whiteness));
        $('#edit_spek_whiteness_rf1').val((data_spek.rf[0].whiteness == null ? '' : data_spek.rf[0].whiteness));
        $('#edit_spek_whiteness_rg1').val((data_spek.rg[0].whiteness == null ? '' : data_spek.rg[0].whiteness));

        $('#edit_spek_residue_sa1').val((data_spek.sa[0].residue == null ? '' : data_spek.sa[0].residue));
        $('#edit_spek_residue_sb1').val((data_spek.sb[0].residue == null ? '' : data_spek.sb[0].residue));
        $('#edit_spek_residue_mixer1').val((data_spek.mixer[0].residue == null ? '' : data_spek.mixer[0].residue));
        $('#edit_spek_residue_ra1').val((data_spek.ra[0].residue == null ? '' : data_spek.ra[0].residue));
        $('#edit_spek_residue_rb1').val((data_spek.rb[0].residue == null ? '' : data_spek.rb[0].residue));
        $('#edit_spek_residue_rc1').val((data_spek.rc[0].residue == null ? '' : data_spek.rc[0].residue));
        $('#edit_spek_residue_rd1').val((data_spek.rd[0].residue == null ? '' : data_spek.rd[0].residue));
        $('#edit_spek_residue_re1').val((data_spek.re[0].residue == null ? '' : data_spek.re[0].residue));
        $('#edit_spek_residue_rf1').val((data_spek.rf[0].residue == null ? '' : data_spek.rf[0].residue));
        $('#edit_spek_residue_rg1').val((data_spek.rg[0].residue == null ? '' : data_spek.rg[0].residue));

        $('#edit_spek_moisture_sa1').val((data_spek.sa[0].moisture == null ? '' : data_spek.sa[0].moisture));
        $('#edit_spek_moisture_sb1').val((data_spek.sb[0].moisture == null ? '' : data_spek.sb[0].moisture));
        $('#edit_spek_moisture_mixer1').val((data_spek.mixer[0].moisture == null ? '' : data_spek.mixer[0].moisture));
        $('#edit_spek_moisture_ra1').val((data_spek.ra[0].moisture == null ? '' : data_spek.ra[0].moisture));
        $('#edit_spek_moisture_rb1').val((data_spek.rb[0].moisture == null ? '' : data_spek.rb[0].moisture));
        $('#edit_spek_moisture_rc1').val((data_spek.rc[0].moisture == null ? '' : data_spek.rc[0].moisture));
        $('#edit_spek_moisture_rd1').val((data_spek.rd[0].moisture == null ? '' : data_spek.rd[0].moisture));
        $('#edit_spek_moisture_re1').val((data_spek.re[0].moisture == null ? '' : data_spek.re[0].moisture));
        $('#edit_spek_moisture_rf1').val((data_spek.rf[0].moisture == null ? '' : data_spek.rf[0].moisture));
        $('#edit_spek_moisture_rg1').val((data_spek.rg[0].moisture == null ? '' : data_spek.rg[0].moisture));
      })
    });

    $('body').on('click', '#view-data', function () {
      var nomor = $(this).data("id");

      $('#modal_lihat_rencana_produksi').modal();

      var url = "{{ url('rencana_produksi/detail_rencana/nomor') }}";
      url = url.replace('nomor', enc(nomor.toString()));
      $('#td_tanggal_rencana').html('');
      var obj = {};
      var obj2 = {};
      $('#table_rencana1').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">SA</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">SB</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">Mixer</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RA</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RB</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '</tr>'
      );
      $('#table_rencana2').html(
        '<thead>'+
        '<tr>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RC</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RD</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RE</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RF</th>'+
        '<th colspan="2" style="vertical-align: top; text-align: center;">RG</th>'+
        '</tr>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '<th style="vertical-align: top; text-align: center;">Jenis</th>'+
        '<th style="vertical-align: top; text-align: center;">Jumlah</th>'+
        '</tr>'
      );
      $.get(url, function (data) {
        $('#td_tanggal_rencana').html(data.data_ren[0].tanggal_rencana);
        $('#td_referensi').html(data.data_referensi.join("; "));
        obj[0] = data.sa;
        obj[1] = data.sb;
        obj[2] = data.mixer;
        obj[3] = data.ra;
        obj[4] = data.rb;
        obj2[0] = data.rc;
        obj2[1] = data.rd;
        obj2[2] = data.re;
        obj2[3] = data.rf;
        obj2[4] = data.rg;
        // obj.push(data.sa, data.sb, data.mixer, data.ra, data.rb, data.rc, data.rd, data.re, data.rf, data.rg, data.coating);

        for(var i = 0; i < getHighest(obj); i++){
          $('#table_rencana1').append(
            '<tr>'
          );
          if(data.sa[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.sa[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.sa[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.sb[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.sb[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.sb[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.mixer[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.mixer[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.mixer[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.ra[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.ra[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.ra[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.rb[i]){
            $('#table_rencana1').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rb[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rb[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana1').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          $('#table_rencana1').append(
            '</tr>'+
            '</thead>'
          );
        }

        for(var i = 0; i < getHighest(obj2); i++){
          $('#table_rencana2').append(
            '<tr>'
          );
          if(data.rc[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rc[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rc[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.rd[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rd[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rd[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.re[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.re[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.re[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.rf[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rf[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rf[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          if(data.rg[i]){
            $('#table_rencana2').append(
              '<td style="vertical-align: top; text-align: center;">'+ data.rg[i].jenis_produk +'</td>'+
              '<td style="vertical-align: top; text-align: center;">'+ data.rg[i].jumlah_sak +'</td>'
            );
          }else{
            $('#table_rencana2').append(
              '<td></td>'+
              '<td></td>'
            );
          }
          $('#table_rencana2').append(
            '</tr>'+
            '</thead>'
          );
        }
      })
      
      var url_spek = "{{ url('rencana_produksi/detail_spek_rencana/nomor') }}";
      url_spek = url_spek.replace('nomor', enc(nomor.toString()));
      var obj_spek = {};
      $('#table_spek_rencana').html(
        '<thead>'+
        '<tr>'+
        '<th style="vertical-align: top; text-align: center;">Mesin</th>'+
        '<th style="vertical-align: top; text-align: center;">SA</th>'+
        '<th style="vertical-align: top; text-align: center;">SB</th>'+
        '<th style="vertical-align: top; text-align: center;">Mixer</th>'+
        '<th style="vertical-align: top; text-align: center;">RA</th>'+
        '<th style="vertical-align: top; text-align: center;">RB</th>'+
        '<th style="vertical-align: top; text-align: center;">RC</th>'+
        '<th style="vertical-align: top; text-align: center;">RD</th>'+
        '<th style="vertical-align: top; text-align: center;">RE</th>'+
        '<th style="vertical-align: top; text-align: center;">RF</th>'+
        '<th style="vertical-align: top; text-align: center;">RG</th>'+
        '</tr>'
      );
      $.get(url_spek, function (data_spek) {
        $('#table_spek_rencana').append(
          '<tr><td style="vertical-align: top; text-align: center;">RPM</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].rpm == null || data_spek.sa[0].rpm == '' ? '-' : data_spek.sa[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].rpm == null || data_spek.sb[0].rpm == '' ? '-' : data_spek.sb[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].rpm == null || data_spek.mixer[0].rpm == '' ? '-' : data_spek.mixer[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].rpm == null || data_spek.ra[0].rpm == '' ? '-' : data_spek.ra[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].rpm == null || data_spek.rb[0].rpm == '' ? '-' : data_spek.rb[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].rpm == null || data_spek.rc[0].rpm == '' ? '-' : data_spek.rc[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].rpm == null || data_spek.rd[0].rpm == '' ? '-' : data_spek.rd[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].rpm == null || data_spek.re[0].rpm == '' ? '-' : data_spek.re[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].rpm == null || data_spek.rf[0].rpm == '' ? '-' : data_spek.rf[0].rpm) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].rpm == null || data_spek.rg[0].rpm == '' ? '-' : data_spek.rg[0].rpm) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">Particle Size</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].particle_size == null || data_spek.sa[0].particle_size == '' ? '-' : data_spek.sa[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].particle_size == null || data_spek.sb[0].particle_size == '' ? '-' : data_spek.sb[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].particle_size == null || data_spek.mixer[0].particle_size == '' ? '-' : data_spek.mixer[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].particle_size == null || data_spek.ra[0].particle_size == '' ? '-' : data_spek.ra[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].particle_size == null || data_spek.rb[0].particle_size == '' ? '-' : data_spek.rb[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].particle_size == null || data_spek.rc[0].particle_size == '' ? '-' : data_spek.rc[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].particle_size == null || data_spek.rd[0].particle_size == '' ? '-' : data_spek.rd[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].particle_size == null || data_spek.re[0].particle_size == '' ? '-' : data_spek.re[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].particle_size == null || data_spek.rf[0].particle_size == '' ? '-' : data_spek.rf[0].particle_size) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].particle_size == null || data_spek.rg[0].particle_size == '' ? '-' : data_spek.rg[0].particle_size) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">SSA</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].ssa == null || data_spek.sa[0].ssa == '' ? '-' : data_spek.sa[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].ssa == null || data_spek.sb[0].ssa == '' ? '-' : data_spek.sb[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].ssa == null || data_spek.mixer[0].ssa == '' ? '-' : data_spek.mixer[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].ssa == null || data_spek.ra[0].ssa == '' ? '-' : data_spek.ra[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].ssa == null || data_spek.rb[0].ssa == '' ? '-' : data_spek.rb[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].ssa == null || data_spek.rc[0].ssa == '' ? '-' : data_spek.rc[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].ssa == null || data_spek.rd[0].ssa == '' ? '-' : data_spek.rd[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].ssa == null || data_spek.re[0].ssa == '' ? '-' : data_spek.re[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].ssa == null || data_spek.rf[0].ssa == '' ? '-' : data_spek.rf[0].ssa) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].ssa == null || data_spek.rg[0].ssa == '' ? '-' : data_spek.rg[0].ssa) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">Whiteness</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].whiteness == null || data_spek.sa[0].whiteness == '' ? '-' : data_spek.sa[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].whiteness == null || data_spek.sb[0].whiteness == '' ? '-' : data_spek.sb[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].whiteness == null || data_spek.mixer[0].whiteness == '' ? '-' : data_spek.mixer[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].whiteness == null || data_spek.ra[0].whiteness == '' ? '-' : data_spek.ra[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].whiteness == null || data_spek.rb[0].whiteness == '' ? '-' : data_spek.rb[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].whiteness == null || data_spek.rc[0].whiteness == '' ? '-' : data_spek.rc[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].whiteness == null || data_spek.rd[0].whiteness == '' ? '-' : data_spek.rd[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].whiteness == null || data_spek.re[0].whiteness == '' ? '-' : data_spek.re[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].whiteness == null || data_spek.rf[0].whiteness == '' ? '-' : data_spek.rf[0].whiteness) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].whiteness == null || data_spek.rg[0].whiteness == '' ? '-' : data_spek.rg[0].whiteness) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">Residue</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].residue == null || data_spek.sa[0].residue == '' ? '-' : data_spek.sa[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].residue == null || data_spek.sb[0].residue == '' ? '-' : data_spek.sb[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].residue == null || data_spek.mixer[0].residue == '' ? '-' : data_spek.mixer[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].residue == null || data_spek.ra[0].residue == '' ? '-' : data_spek.ra[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].residue == null || data_spek.rb[0].residue == '' ? '-' : data_spek.rb[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].residue == null || data_spek.rc[0].residue == '' ? '-' : data_spek.rc[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].residue == null || data_spek.rd[0].residue == '' ? '-' : data_spek.rd[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].residue == null || data_spek.re[0].residue == '' ? '-' : data_spek.re[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].residue == null || data_spek.rf[0].residue == '' ? '-' : data_spek.rf[0].residue) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].residue == null || data_spek.rg[0].residue == '' ? '-' : data_spek.rg[0].residue) +'</td></tr>'+
          '<tr><td style="vertical-align: top; text-align: center;">Moisture</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sa[0].moisture == null || data_spek.sa[0].moisture == '' ? '-' : data_spek.sa[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.sb[0].moisture == null || data_spek.sb[0].moisture == '' ? '-' : data_spek.sb[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.mixer[0].moisture == null || data_spek.mixer[0].moisture == '' ? '-' : data_spek.mixer[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.ra[0].moisture == null || data_spek.ra[0].moisture == '' ? '-' : data_spek.ra[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rb[0].moisture == null || data_spek.rb[0].moisture == '' ? '-' : data_spek.rb[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rc[0].moisture == null || data_spek.rc[0].moisture == '' ? '-' : data_spek.rc[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rd[0].moisture == null || data_spek.rd[0].moisture == '' ? '-' : data_spek.rd[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.re[0].moisture == null || data_spek.re[0].moisture == '' ? '-' : data_spek.re[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rf[0].moisture == null || data_spek.rf[0].moisture == '' ? '-' : data_spek.rf[0].moisture) +'</td>'+
          '<td style="vertical-align: top; text-align: center;">'+ (data_spek.rg[0].moisture == null || data_spek.rg[0].moisture == '' ? '-' : data_spek.rg[0].moisture) +'</td></tr>'
          );
      })
    });

    $('body').on('click', '#delete-data', function () {
      var nomor = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('rencana_produksi/delete') }}",
          data: { 'nomor' : nomor },
          success: function (data) {
            var oTable = $('#rencana_produksi_table').dataTable();
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

    $('#rencana_produksi_form').validate({
      rules: {
        tanggal_rencana: {
          required: true,
        },
      },
      messages: {
        tanggal_rencana: {
          required: "Tanggal Rencana is Required",
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
        var myform = document.getElementById("rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#rencana_produksi_form').trigger("reset");
            var oTable = $('#rencana_produksi_table').dataTable();
            oTable.fnDraw(false);
            var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];
            $.each(arr_mesin, function(k, v) {
              for(var i = 2; i <= window['c_rencana_'+v]; i++){
                $('#row_data_rencana_'+v+i).remove();
              }
            });
            $("#modal_input_rencana_produksi").modal('hide');
            $("#modal_input_rencana_produksi").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#spek_rencana_produksi_form').validate({
      rules: {
        nomor_rencana_produksi_spek: {
          required: true,
        },
      },
      messages: {
        nomor_rencana_produksi_spek: {
          required: "Nomor Rencana Produksi is Required",
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
        var myform = document.getElementById("spek_rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/spek/save') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#spek_rencana_produksi_form').trigger("reset");
            var oTable = $('#rencana_produksi_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_input_spek_rencana_produksi").modal('hide');
            $("#modal_input_spek_rencana_produksi").trigger('click');
            alert("Data Successfully Stored");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#edit_rencana_produksi_form').validate({
      rules: {
        edit_tanggal_rencana: {
          required: true,
        },
      },
      messages: {
        edit_tanggal_rencana: {
          required: "Tanggal Rencana is Required",
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
        var myform = document.getElementById("edit_rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_rencana_produksi_form').trigger("reset");
            var oTable = $('#laporan_produksi_table').dataTable();
            oTable.fnDraw(false);
            var arr_mesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];
            $.each(arr_mesin, function(k, v) {
              for(var i = 2; i <= window['edit_c_rencana_'+v]; i++){
                $('#edit_row_data_rencana_'+v+i).remove();
              }
            });
            $("#modal_edit_rencana_produksi").modal('hide');
            $("#modal_edit_rencana_produksi").trigger('click');
            alert("Data Successfully Updated");
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#edit_spek_rencana_produksi_form').validate({
      rules: {
        edit_nomor_rencana_produksi_spek: {
          required: true,
        },
      },
      messages: {
        edit_nomor_rencana_produksi_spek: {
          required: "Nomor Rencana Produksi is Required",
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
        var myform = document.getElementById("edit_spek_rencana_produksi_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('rencana_produksi/spek/edit') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#edit_spek_rencana_produksi_form').trigger("reset");
            var oTable = $('#rencana_produksi_table').dataTable();
            oTable.fnDraw(false);
            $("#modal_edit_spek_rencana_produksi").modal('hide');
            $("#modal_edit_spek_rencana_produksi").trigger('click');
            alert("Data Successfully Updated");
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
    $('.referensi').select2({
      multiple: true,
      placeholder: {
        id: "-1",
        text: '--- Please select a reference number ---',
        selected:'selected'
      },
      allowClear: true,
      ajax: {
        url: '/dropdown/rencana_produksi/referensi',
        data: function (params) {
          return {
            q: params.term,
          };
        },
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.nomor_order_receipt,
                id: item.nomor_order_receipt
              }
            })
          };
        },
        cache: true
      }
    });

    $('.edit_referensi').select2({
      multiple: true,
      placeholder: {
        id: "-1",
        text: '--- Please select a reference number ---',
        selected:'selected'
      },
      allowClear: true,
      ajax: {
        url: '/dropdown/rencana_produksi/referensi',
        data: function (params) {
          return {
            q: params.term,
          };
        },
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.nomor_order_receipt,
                id: item.nomor_order_receipt
              }
            })
          };
        },
        cache: true
      }
    });
  });
</script>

@endsection
