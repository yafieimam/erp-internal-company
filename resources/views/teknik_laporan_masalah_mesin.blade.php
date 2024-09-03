@extends('layouts.app_admin')

@section('title')
<title>LAPORAN MASALAH MESIN - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Laporan Masalah Mesin</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Teknik</li>
            <li class="breadcrumb-item">Laporan Masalah Mesin</li>
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
                  <input type="text" placeholder="Filter Date" class="form-control float-right" id="filter_tanggal" autocomplete="off">
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
            <div class="col-10">
              <?php
              $arrayMesin = ['sa' => 'SA', 'sb' => 'SB', 'mixer' => 'Mixer', 'ra' => 'RA', 'rb' => 'RB', 'rc' => 'RC', 'rd' => 'RD', 're' => 'RE', 'rf' => 'RF', 'rg' => 'RG'];
              ?>
              <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
                <?php
                $first = true;
                foreach($arrayMesin as $index => $item){
                  if($first){
                ?>
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#laporan_{{ $index }}" role="tab" aria-controls="custom-content-below-home" aria-selected="true">{{ $item }}</a>
                    </li>
                  <?php
                    $first = false;
                  }else{
                  ?>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#laporan_{{ $index }}" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">{{ $item }}</a>
                    </li>
                <?php
                  }
                }
                ?>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#grafik_masalah_mesin_major" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Grafik Major</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#grafik_masalah_mesin_minor" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Grafik Minor</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#grafik_masalah_mesin_lain" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Grafik Lain</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#grafik_masalah_mesin_total" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Grafik Total</a>
                </li>
              </ul>
            </div>
            <div class="col-2">
              <a href="#" class="btn btn-primary" id="btn-save-excel" style="width: 100%;">Download Excel</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <?php
            $first = true;
            foreach($arrayMesin as $index => $item){
              if($first){
            ?>
            <div class="tab-pane fade show active" id="laporan_{{ $index }}" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table id="data_total_{{ $index }}_table" style="width: 60%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">Total Jam Masalah Major</th>
                    <th style="vertical-align : middle; text-align: center;">Total Jam Masalah Minor</th>
                    <th style="vertical-align : middle; text-align: center;">Total Jam Masalah Lain</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="vertical-align : middle; text-align: center;" id="total_jam_masalah_major_{{ $index }}"></td>
                    <td style="vertical-align : middle; text-align: center;" id="total_jam_masalah_minor_{{ $index }}"></td>
                    <td style="vertical-align : middle; text-align: center;" id="total_jam_masalah_lain_{{ $index }}"></td>
                  </tr>
                </tbody>
              </table>

              <table id="data_laporan_{{ $index }}_table" style="width: 100%; font-size: 11px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="width: 13%; vertical-align : middle; text-align: center;" rowspan="2" colspan="2">Tanggal</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">RPM</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">SSA</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">D50</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">D98</th>
                    <th style="width: 6%; text-align: center;" colspan="2">Whiteness</th>
                    <th style="width: 5%; vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                    <th style="width: 4%; vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
                    <th style="width: 5%; vertical-align : middle; text-align: center;" rowspan="2">Tonase</th>
                    <th style="width: 18%; vertical-align : middle; text-align: center;" rowspan="2">Masalah Major</th>
                    <th style="width: 18%; vertical-align : middle; text-align: center;" rowspan="2">Masalah Minor</th>
                    <th style="width: 18%; vertical-align : middle; text-align: center;" rowspan="2">Masalah Lain</th>
                  </tr>
                  <tr>
                    <th style="width: 3%;vertical-align : middle; text-align: center;">CIE 86</th>
                    <th style="width: 3%;vertical-align : middle; text-align: center;">ISO 2470</th>
                  </tr>
                </thead>
                <tbody id="tbody_view_{{ $index }}">
                </tbody>
              </table>
            </div>
            <?php
                $first = false;
              }else{
            ?>
            <div class="tab-pane fade" id="laporan_{{ $index }}" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <table id="data_total_{{ $index }}_table" style="width: 60%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="vertical-align : middle; text-align: center;">Total Jam Masalah Major</th>
                    <th style="vertical-align : middle; text-align: center;">Total Jam Masalah Minor</th>
                    <th style="vertical-align : middle; text-align: center;">Total Jam Masalah Lain</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="vertical-align : middle; text-align: center;" id="total_jam_masalah_major_{{ $index }}"></td>
                    <td style="vertical-align : middle; text-align: center;" id="total_jam_masalah_minor_{{ $index }}"></td>
                    <td style="vertical-align : middle; text-align: center;" id="total_jam_masalah_lain_{{ $index }}"></td>
                  </tr>
                </tbody>
              </table>

              <table id="data_laporan_{{ $index }}_table" style="width: 100%; font-size: 11px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="width: 13%; vertical-align : middle; text-align: center;" rowspan="2" colspan="2">Tanggal</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">RPM</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">SSA</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">D50</th>
                    <th style="width: 3%; vertical-align : middle; text-align: center;" rowspan="2">D98</th>
                    <th style="width: 6%; text-align: center;" colspan="2">Whiteness</th>
                    <th style="width: 5%; vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                    <th style="width: 4%; vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
                    <th style="width: 5%; vertical-align : middle; text-align: center;" rowspan="2">Tonase</th>
                    <th style="width: 18%; vertical-align : middle; text-align: center;" rowspan="2">Masalah Major</th>
                    <th style="width: 18%; vertical-align : middle; text-align: center;" rowspan="2">Masalah Minor</th>
                    <th style="width: 18%; vertical-align : middle; text-align: center;" rowspan="2">Masalah Lain</th>
                  </tr>
                  <tr>
                    <th style="width: 3%;vertical-align : middle; text-align: center;">CIE 86</th>
                    <th style="width: 3%;vertical-align : middle; text-align: center;">ISO 2470</th>
                  </tr>
                </thead>
                <tbody id="tbody_view_{{ $index }}">
                </tbody>
              </table>
            </div>
            <?php
              }
            }
            ?>
            <div class="tab-pane fade" id="grafik_masalah_mesin_major" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <div class="chart">
                <canvas id="chartMasalahMesinMajorSA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorSB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorMixer" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorRA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorRB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorRC" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorRD" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorRE" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorRF" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMajorRG" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
            <div class="tab-pane fade" id="grafik_masalah_mesin_minor" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <div class="chart">
                <canvas id="chartMasalahMesinMinorSA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorSB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorMixer" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorRA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorRB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorRC" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorRD" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorRE" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorRF" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinMinorRG" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
            <div class="tab-pane fade" id="grafik_masalah_mesin_lain" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <div class="chart">
                <canvas id="chartMasalahMesinLainSA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainSB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainMixer" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainRA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainRB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainRC" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainRD" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainRE" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainRF" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinLainRG" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
            <div class="tab-pane fade" id="grafik_masalah_mesin_total" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <div class="chart">
                <canvas id="chartMasalahMesinTotalSA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalSB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalMixer" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalRA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalRB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalRC" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalRD" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalRE" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalRF" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartMasalahMesinTotalRG" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

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
  $(function () {
    $('#filter_tanggal').daterangepicker({
      locale: {
        format: 'YYYY-MM',
      }
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var target = $('.nav-tabs a.nav-link.active').attr("href");

    load_data_laporan_masalah_mesin('', '', 1, 'sa');

    var arrayMesin = ['sa', 'sb', 'mixer', 'ra', 'rb', 'rc', 'rd', 're', 'rf', 'rg'];

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      $.each(arrayMesin, function(k, v) {
        if(target == '#laporan_' + v){
          $("#tbody_view_"+v).empty();
          load_data_laporan_masalah_mesin('', '', (k+1), v);
        }
      });
      if(target == '#grafik_masalah_mesin_major'){
        load_grafik_perbandingan_major();
      }
      if(target == '#grafik_masalah_mesin_minor'){
        load_grafik_perbandingan_minor();
      }
      if(target == '#grafik_masalah_mesin_lain'){
        load_grafik_perbandingan_lain();
      }
      if(target == '#grafik_masalah_mesin_total'){
        load_grafik_perbandingan_total();
      }
    });

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function msToTime(duration) {
      var minutes = Math.floor((duration / (1000 * 60)) % 60),
      hours = Math.floor((duration / (1000 * 60 * 60)));

      // hours = (hours < 10) ? "0" + hours : hours;
      // minutes = (minutes < 10) ? "0" + minutes : minutes;

      return hours + " Jam " + minutes + " Menit";
    }

    function msConvert(duration) {
      var minutes = Math.floor((duration / (1000 * 60)));

      // hours = (hours < 10) ? "0" + hours : hours;
      // minutes = (minutes < 10) ? "0" + minutes : minutes;

      return minutes;
    }

    function msConvertTime(duration) {
      var minutes = Math.floor(duration % 60);
      var hours = Math.floor(duration / 60);

      hours = (hours < 10) ? "0" + hours : hours;
      minutes = (minutes < 10) ? "0" + minutes : minutes;

      return hours + " Jam " + minutes + " Menit";
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

    $('body').on('click', '#btn-save-excel', function () {
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM');

      var url = '{{ url("teknik/laporan_masalah_mesin/excel/from_date/to_date") }}';
      url = url.replace('from_date', enc(from_date.toString()));
      url = url.replace('to_date', enc(to_date.toString()));
      $('#btn-save-excel').attr('href', url);
      window.location = url;
    });

    function load_data_laporan_masalah_mesin(from_date = '', to_date = '', no_mesin = '', nama_mesin = '')
    {
      var url = "{{ url('teknik/laporan_masalah_mesin/view/data/no_mesin/from_date/to_date') }}";
      url = url.replace('no_mesin', enc(no_mesin.toString()));
      url = url.replace('from_date', enc(from_date.toString()));
      url = url.replace('to_date', enc(to_date.toString()));
      $.get(url, function (data) {
        var besar = 0;
        var jam_kecil = null;
        var jam_besar = null;
        var total_major = 0;
        var total_minor = 0;
        var total_lain = 0;
        var ada_lanjutan_major = 0; 
        var ada_lanjutan_minor = 0; 
        var ada_lanjutan_lain = 0; 
        $.each(data.data_lab, function(k, v) {
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

        $.each(data.data_lab, function(k, v) {
          if(besar > 1){
            if(v.length == 0){
                $('#tbody_view_'+nama_mesin).append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+k+'</td>'+
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
                  '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+ (data.data_masalah_a[k].tonase == 0  || data.data_masalah_a[k].tonase == null ? '-': numberWithCommas(data.data_masalah_a[k].tonase))+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_major_'+nama_mesin+k+'"></td>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_minor_'+nama_mesin+k+'"></td>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_lain_'+nama_mesin+k+'"></td>'+
                  '</tr>'
                  );

                $('#tbody_view_'+nama_mesin).append(
                  '<tr>'+
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
          }else{
            if(v.length == 0){
              $('#tbody_view_'+nama_mesin).append(
                '<tr>'+
                '<td style="vertical-align : middle; text-align: center;">'+k+'</td>'+
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
                '<td style="vertical-align : middle; text-align: center;">'+ (data.data_masalah_a[k].tonase == 0  || data.data_masalah_a[k].tonase == null ? '-': numberWithCommas(data.data_masalah_a[k].tonase))+'</td>'+
                '<td style="vertical-align : middle; text-align: center;" id="masalah_major_'+nama_mesin+k+'"></td>'+
                '<td style="vertical-align : middle; text-align: center;" id="masalah_minor_'+nama_mesin+k+'"></td>'+
                '<td style="vertical-align : middle; text-align: center;" id="masalah_lain_'+nama_mesin+k+'"></td>'+
                '</tr>'
                );
            }
          }
          $.each(v, function(m, y) {
            if(m == 0){
              if(v.length < besar){
                if(y.jam_waktu == jam_kecil){
                  $('#tbody_view_'+nama_mesin).append(
                    '<tr>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+k+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                    (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                    (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                    (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                    (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+ (data.data_masalah_a[k].tonase == 0  || data.data_masalah_a[k].tonase == null ? '-': numberWithCommas(data.data_masalah_a[k].tonase))+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_major_'+nama_mesin+k+'"></td>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_minor_'+nama_mesin+k+'"></td>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_lain_'+nama_mesin+k+'"></td>'+
                    '</tr>'
                    );
                  $('#tbody_view_'+nama_mesin).append(
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
                    '</tr>'
                    );
                }else if(y.jam_waktu == jam_besar){
                  $('#tbody_view_'+nama_mesin).append(
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
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2">'+ (data.data_masalah_a[k].tonase == 0  || data.data_masalah_a[k].tonase == null ? '-': numberWithCommas(data.data_masalah_a[k].tonase))+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_major_'+nama_mesin+k+'"></td>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_minor_'+nama_mesin+k+'"></td>'+
                    '<td style="vertical-align : middle; text-align: center;" rowspan="2" id="masalah_lain_'+nama_mesin+k+'"></td>'+
                    '</tr>'
                    );

                  $('#tbody_view_'+nama_mesin).append(
                    '<tr>'+
                    '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                    '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                    (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                    (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                    (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                    (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                    '</tr>'
                    );
                }
              }else{
                $('#tbody_view_'+nama_mesin).append(
                  '<tr>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="'+v.length+'">'+k+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                  (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                  (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                  (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                  (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                  (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                    (parseFloat(y.residue) == 0  || parseFloat(y.residue) == null ? '0.00': parseFloat(y.residue).toFixed(2))+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="'+v.length+'">'+ (data.data_masalah_a[k].tonase == 0  || data.data_masalah_a[k].tonase == null ? '-': numberWithCommas(data.data_masalah_a[k].tonase))+'</td>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="'+v.length+'" id="masalah_major_'+nama_mesin+k+'"></td>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="'+v.length+'" id="masalah_minor_'+nama_mesin+k+'"></td>'+
                  '<td style="vertical-align : middle; text-align: center;" rowspan="'+v.length+'" id="masalah_lain_'+nama_mesin+k+'"></td>'+
                  '</tr>'
                  );         
              } 
            }else{
              $('#tbody_view_'+nama_mesin).append(
                '<tr>'+
                '<td style="vertical-align : middle; text-align: center;">'+moment(y.jam_waktu, "HH:mm").format("HH:mm")+'</td>'+
                '<td style="vertical-align : middle; text-align: center;">'+y.mesh+'</td>'+
                '<td style="vertical-align : middle; text-align: center;">'+(y.rpm == 0  || y.rpm == null ? '-': y.rpm)+'</td>'+
                '<td style="vertical-align : middle; text-align: center;">'+(y.ssa == 0  || y.ssa == null ? '-': y.ssa)+'</td>'+
                '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.d50) == 0  || parseFloat(y.d50) == null ? '0.00': parseFloat(y.d50).toFixed(2))+'</td>'+
                '<td style="vertical-align : middle; text-align: center;">'+(parseFloat(y.d98) == 0  || parseFloat(y.d98) == null ? '0.00': parseFloat(y.d98).toFixed(2))+'</td>'+
                (parseFloat(y.spek_whiteness) > parseFloat(y.cie86) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                (parseFloat(y.cie86) == 0  || parseFloat(y.cie86) == null ? '0.0': parseFloat(y.cie86).toFixed(1))+'</td>'+
                (parseFloat(y.spek_whiteness) > parseFloat(y.iso2470) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                (parseFloat(y.iso2470) == 0  || parseFloat(y.iso2470) == null ? '0.0': parseFloat(y.iso2470).toFixed(1))+'</td>'+
                (parseFloat(y.spek_moisture) < parseFloat(y.moisture) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                (parseFloat(y.moisture) == 0  || parseFloat(y.moisture) == null ? '0.000': parseFloat(y.moisture).toFixed(3))+'</td>'+
                (parseFloat(y.spek_residue) < parseFloat(y.residue) ? '<td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">' : '<td style="vertical-align : middle; text-align: center;">')+
                '</tr>'
                );
            }
          });

          if(!("masalah" in data.data_masalah_a[k])){
            $('#masalah_major_'+nama_mesin+k).append('-');
            if(ada_lanjutan_major == 1){
              total_major += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
            }
          }else{
            $.each(data.data_masalah_a[k].masalah, function(i, j) {
              if(j.jam_awal != null && j.jam_akhir != null){
                total_major += moment(j.jam_akhir, "HH:mm").diff(moment(j.jam_awal, "HH:mm"));
                $('#masalah_major_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ' - ' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else if(j.jam_awal != null && j.jam_akhir == null){
                total_major += moment('24:00', "HH:mm").diff(moment(j.jam_awal, "HH:mm"));
                ada_lanjutan_major = 1;
                $('#masalah_major_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else if(j.jam_akhir != null && j.jam_awal == null){
                if(ada_lanjutan_major == 1){
                  total_major += moment(j.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                  ada_lanjutan_major = 0;
                }
                $('#masalah_major_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else{
                $('#masalah_major_'+nama_mesin+k).append((i+1)+'. '+ j.masalah + '<br>');
              }
            });
          }

          if(!("masalah" in data.data_masalah_b[k])){
            $('#masalah_minor_'+nama_mesin+k).append('-');
            if(ada_lanjutan_minor == 1){
              total_minor += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
            }
          }else{
            $.each(data.data_masalah_b[k].masalah, function(i, j) {
              if(j.jam_awal != null && j.jam_akhir != null){
                total_minor += moment(j.jam_akhir, "HH:mm").diff(moment(j.jam_awal, "HH:mm"));
                $('#masalah_minor_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ' - ' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else if(j.jam_awal != null && j.jam_akhir == null){
                total_minor += moment('24:00', "HH:mm").diff(moment(j.jam_awal, "HH:mm"));
                ada_lanjutan_minor = 1;
                $('#masalah_minor_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else if(j.jam_akhir != null && j.jam_awal == null){
                if(ada_lanjutan_minor == 1){
                  total_minor += moment(j.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                  ada_lanjutan_minor = 0;
                }
                $('#masalah_minor_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else{
                $('#masalah_minor_'+nama_mesin+k).append((i+1)+'. '+ j.masalah + '<br>');
              }
            });
          }
          
          if(!("masalah" in data.data_masalah_c[k])){
            $('#masalah_lain_'+nama_mesin+k).append('-');
            if(ada_lanjutan_lain == 1){
              total_lain += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
            }
          }else{
            $.each(data.data_masalah_c[k].masalah, function(i, j) {
              if(j.jam_awal != null && j.jam_akhir != null){
                total_lain += moment(j.jam_akhir, "HH:mm").diff(moment(j.jam_awal, "HH:mm"));
                $('#masalah_lain_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ' - ' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else if(j.jam_awal != null && j.jam_akhir == null){
                total_lain += moment('24:00', "HH:mm").diff(moment(j.jam_awal, "HH:mm"));
                ada_lanjutan_lain = 1;
                $('#masalah_lain_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_awal, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else if(j.jam_akhir != null && j.jam_awal == null){
                if(ada_lanjutan_lain == 1){
                  total_lain += moment(j.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                  ada_lanjutan_lain = 0;
                }
                $('#masalah_lain_'+nama_mesin+k).append((i+1)+'. '+j.masalah + ' (' + moment(j.jam_akhir, "HH:mm").format("HH:mm") + ')' + '<br>');
              }else{
                $('#masalah_lain_'+nama_mesin+k).append((i+1)+'. '+ j.masalah + '<br>');
              }
            });
          }

          $('#total_jam_masalah_major_'+nama_mesin).html(msToTime(total_major));
          $('#total_jam_masalah_minor_'+nama_mesin).html(msToTime(total_minor));
          $('#total_jam_masalah_lain_'+nama_mesin).html(msToTime(total_lain));
        });
      })
    }

    function load_grafik_perbandingan_major()
    {
      var arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

      $.ajax({
        type: "GET",
        url: "{{ url('teknik/laporan_masalah_mesin/major/grafik/data') }}",
        success: function (data) {
          $.each(arr_mesin, function(k, v) {
            var total1 = new Array(12).fill(0);
            var total2 = new Array(12).fill(0);
            var total3 = new Array(12).fill(0);
            var ada_lanjutan1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var ada_lanjutan2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var ada_lanjutan3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $.each(data[v], function(i, j) {
              var date = new Date(i);
              if(!("masalah" in j)){
                if(date.getUTCFullYear() == '{{ date("Y") }}'){
                  if(ada_lanjutan1[date.getUTCMonth()] == 1){
                    total_nilai1[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }else if(date.getUTCFullYear() == "{{ date('Y', strtotime('-1 year')) }}"){
                  if(ada_lanjutan2[date.getUTCMonth()] == 1){
                    total_nilai2[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }else if(date.getUTCFullYear() == "{{ date('Y', strtotime('-2 year')) }}"){
                  if(ada_lanjutan3[date.getUTCMonth()] == 1){
                    total_nilai3[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }
              }else{
                $.each(j.masalah, function(m, n) {
                  if(n.tahun == '{{ date("Y") }}'){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai1[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      total_nilai1[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan1[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan1[n.bulan - 1] == 1){
                        total_nilai1[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan1[n.bulan - 1] = 0;
                      }
                    }
                  }else if(n.tahun == "{{ date('Y', strtotime('-1 year')) }}"){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai2[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      console.log(n.tahun + ', ' + n.bulan);
                      total_nilai2[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan2[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan2[n.bulan - 1] == 1){
                        total_nilai2[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan2[n.bulan - 1] = 0;
                      }
                    }
                  }else if(n.tahun == "{{ date('Y', strtotime('-2 year')) }}"){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai3[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      total_nilai3[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan3[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan3[n.bulan - 1] == 1){
                        total_nilai3[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan3[n.bulan - 1] = 0;
                      }
                    }
                  }
                });
              }
            });

            $.each(total_nilai1, function(a, b) {
              total1[a] = msConvert(b);
            });

            $.each(total_nilai2, function(a, b) {
              total2[a] = msConvert(b);
            });

            $.each(total_nilai3, function(a, b) {
              total3[a] = msConvert(b);
            });

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                backgroundColor: 'rgba(75,237,57,0.9)',
                borderColor: 'rgba(75,237,57,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(75,237,57,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(75,237,57,0.9)',
                data: total3                                                
              },
              {
                label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                backgroundColor: 'rgba(30,144,255,0.9)',
                borderColor: 'rgba(30,144,255,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(30,144,255,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(30,144,255,0.9)',
                data: total2
              },
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: total1
              },
              ]
            };

            var barChartCanvas = $('#chartMasalahMesinMajor'+v).get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    jam = msConvertTime(value);
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",") + " (" + jam + ")";
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              },
              title: {
                display: true,
                text: 'Masalah Mesin Major (' + v + ')'
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });
          });
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    }

    function load_grafik_perbandingan_minor()
    {
      var arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

      $.ajax({
        type: "GET",
        url: "{{ url('teknik/laporan_masalah_mesin/minor/grafik/data') }}",
        success: function (data) {
          $.each(arr_mesin, function(k, v) {
            var total1 = new Array(12).fill(0);
            var total2 = new Array(12).fill(0);
            var total3 = new Array(12).fill(0);
            var ada_lanjutan1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var ada_lanjutan2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var ada_lanjutan3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $.each(data[v], function(i, j) {
              var date = new Date(i);
              if(!("masalah" in j)){
                if(date.getUTCFullYear() == '{{ date("Y") }}'){
                  if(ada_lanjutan1[date.getUTCMonth()] == 1){
                    total_nilai1[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }else if(date.getUTCFullYear() == "{{ date('Y', strtotime('-1 year')) }}"){
                  if(ada_lanjutan2[date.getUTCMonth()] == 1){
                    total_nilai2[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }else if(date.getUTCFullYear() == "{{ date('Y', strtotime('-2 year')) }}"){
                  if(ada_lanjutan3[date.getUTCMonth()] == 1){
                    total_nilai3[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }
              }else{
                $.each(j.masalah, function(m, n) {
                  if(n.tahun == '{{ date("Y") }}'){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai1[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      total_nilai1[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan1[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan1[n.bulan - 1] == 1){
                        total_nilai1[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan1[n.bulan - 1] = 0;
                      }
                    }
                  }else if(n.tahun == "{{ date('Y', strtotime('-1 year')) }}"){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai2[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      total_nilai2[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan2[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan2[n.bulan - 1] == 1){
                        total_nilai2[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan2[n.bulan - 1] = 0;
                      }
                    }
                  }else if(n.tahun == "{{ date('Y', strtotime('-2 year')) }}"){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai3[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      total_nilai3[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan3[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan3[n.bulan - 1] == 1){
                        total_nilai3[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan3[n.bulan - 1] = 0;
                      }
                    }
                  }
                });
              }
            });

            $.each(total_nilai1, function(a, b) {
              total1[a] = msConvert(b);
            });

            $.each(total_nilai2, function(a, b) {
              total2[a] = msConvert(b);
            });

            $.each(total_nilai3, function(a, b) {
              total3[a] = msConvert(b);
            });

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                backgroundColor: 'rgba(75,237,57,0.9)',
                borderColor: 'rgba(75,237,57,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(75,237,57,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(75,237,57,0.9)',
                data: total3                                                
              },
              {
                label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                backgroundColor: 'rgba(30,144,255,0.9)',
                borderColor: 'rgba(30,144,255,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(30,144,255,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(30,144,255,0.9)',
                data: total2
              },
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: total1
              },
              ]
            };

            var barChartCanvas = $('#chartMasalahMesinMinor'+v).get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    jam = msConvertTime(value);
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",") + " (" + jam + ")";
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              },
              title: {
                display: true,
                text: 'Masalah Mesin Minor (' + v + ')'
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });
          });
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    }

    function load_grafik_perbandingan_lain()
    {
      var arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

      $.ajax({
        type: "GET",
        url: "{{ url('teknik/laporan_masalah_mesin/lain/grafik/data') }}",
        success: function (data) {
          $.each(arr_mesin, function(k, v) {
            var total1 = new Array(12).fill(0);
            var total2 = new Array(12).fill(0);
            var total3 = new Array(12).fill(0);
            var ada_lanjutan1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var ada_lanjutan2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var ada_lanjutan3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            var total_nilai3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $.each(data[v], function(i, j) {
              var date = new Date(i);
              if(!("masalah" in j)){
                if(date.getUTCFullYear() == '{{ date("Y") }}'){
                  if(ada_lanjutan1[date.getUTCMonth()] == 1){
                    total_nilai1[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }else if(date.getUTCFullYear() == "{{ date('Y', strtotime('-1 year')) }}"){
                  if(ada_lanjutan2[date.getUTCMonth()] == 1){
                    total_nilai2[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }else if(date.getUTCFullYear() == "{{ date('Y', strtotime('-2 year')) }}"){
                  if(ada_lanjutan3[date.getUTCMonth()] == 1){
                    total_nilai3[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                  }
                }
              }else{
                $.each(j.masalah, function(m, n) {
                  if(n.tahun == '{{ date("Y") }}'){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai1[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      total_nilai1[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan1[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan1[n.bulan - 1] == 1){
                        total_nilai1[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan1[n.bulan - 1] = 0;
                      }
                    }
                  }else if(n.tahun == "{{ date('Y', strtotime('-1 year')) }}"){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai2[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      total_nilai2[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan2[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan2[n.bulan - 1] == 1){
                        total_nilai2[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan2[n.bulan - 1] = 0;
                      }
                    }
                  }else if(n.tahun == "{{ date('Y', strtotime('-2 year')) }}"){
                    if(n.jam_awal != null && n.jam_akhir != null){
                      total_nilai3[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                    }else if(n.jam_awal != null && n.jam_akhir == null){
                      total_nilai3[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      ada_lanjutan3[n.bulan - 1] = 1;
                    }else if(n.jam_akhir != null && n.jam_awal == null){
                      if(ada_lanjutan3[n.bulan - 1] == 1){
                        total_nilai3[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                        ada_lanjutan3[n.bulan - 1] = 0;
                      }
                    }
                  }
                });
              }
            });

            $.each(total_nilai1, function(a, b) {
              total1[a] = msConvert(b);
            });

            $.each(total_nilai2, function(a, b) {
              total2[a] = msConvert(b);
            });

            $.each(total_nilai3, function(a, b) {
              total3[a] = msConvert(b);
            });

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                backgroundColor: 'rgba(75,237,57,0.9)',
                borderColor: 'rgba(75,237,57,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(75,237,57,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(75,237,57,0.9)',
                data: total3                                                
              },
              {
                label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                backgroundColor: 'rgba(30,144,255,0.9)',
                borderColor: 'rgba(30,144,255,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(30,144,255,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(30,144,255,0.9)',
                data: total2
              },
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: total1
              },
              ]
            };

            var barChartCanvas = $('#chartMasalahMesinLain'+v).get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    jam = msConvertTime(value);
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",") + " (" + jam + ")";
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              },
              title: {
                display: true,
                text: 'Masalah Mesin Lain (' + v + ')'
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });
          });
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    }

    function load_grafik_perbandingan_total()
    {
      var arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];
      var arr_kategori = ['Major', 'Minor', 'Lain'];

      $.ajax({
        type: "GET",
        url: "{{ url('teknik/laporan_masalah_mesin/total/grafik/data') }}",
        success: function (data) {
          $.each(arr_mesin, function(k, v) {
            var total1 = new Array(12).fill(0);
            var total2 = new Array(12).fill(0);
            var total3 = new Array(12).fill(0);
            $.each(arr_kategori, function(a, b) {
              var ada_lanjutan1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
              var ada_lanjutan2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
              var ada_lanjutan3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
              var total_nilai1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
              var total_nilai2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
              var total_nilai3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
              $.each(data[v][a], function(i, j) {
                var date = new Date(i);
                if(!("masalah" in j)){
                  if(date.getUTCFullYear() == '{{ date("Y") }}'){
                    if(ada_lanjutan1[date.getUTCMonth()] == 1){
                      total_nilai1[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                    }
                  }else if(date.getUTCFullYear() == "{{ date('Y', strtotime('-1 year')) }}"){
                    if(ada_lanjutan2[date.getUTCMonth()] == 1){
                      total_nilai2[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                    }
                  }else if(date.getUTCFullYear() == "{{ date('Y', strtotime('-2 year')) }}"){
                    if(ada_lanjutan3[date.getUTCMonth()] == 1){
                      total_nilai3[date.getUTCMonth()] += moment('24:00', "HH:mm").diff(moment('00:00', "HH:mm"));
                    }
                  }
                }else{
                  $.each(j.masalah, function(m, n) {
                    if(n.tahun == '{{ date("Y") }}'){
                      if(n.jam_awal != null && n.jam_akhir != null){
                        total_nilai1[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      }else if(n.jam_awal != null && n.jam_akhir == null){
                        total_nilai1[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                        ada_lanjutan1[n.bulan - 1] = 1;
                      }else if(n.jam_akhir != null && n.jam_awal == null){
                        if(ada_lanjutan1[n.bulan - 1] == 1){
                          total_nilai1[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                          ada_lanjutan1[n.bulan - 1] = 0;
                        }
                      }
                    }else if(n.tahun == "{{ date('Y', strtotime('-1 year')) }}"){
                      if(n.jam_awal != null && n.jam_akhir != null){
                        total_nilai2[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      }else if(n.jam_awal != null && n.jam_akhir == null){
                        total_nilai2[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                        ada_lanjutan2[n.bulan - 1] = 1;
                      }else if(n.jam_akhir != null && n.jam_awal == null){
                        if(ada_lanjutan2[n.bulan - 1] == 1){
                          total_nilai2[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                          ada_lanjutan2[n.bulan - 1] = 0;
                        }
                      }
                    }else if(n.tahun == "{{ date('Y', strtotime('-2 year')) }}"){
                      if(n.jam_awal != null && n.jam_akhir != null){
                        total_nilai3[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                      }else if(n.jam_awal != null && n.jam_akhir == null){
                        total_nilai3[n.bulan - 1] += moment('24:00', "HH:mm").diff(moment(n.jam_awal, "HH:mm"));
                        ada_lanjutan3[n.bulan - 1] = 1;
                      }else if(n.jam_akhir != null && n.jam_awal == null){
                        if(ada_lanjutan3[n.bulan - 1] == 1){
                          total_nilai3[n.bulan - 1] += moment(n.jam_akhir, "HH:mm").diff(moment('00:00', "HH:mm"));
                          ada_lanjutan3[n.bulan - 1] = 0;
                        }
                      }
                    }
                  });
                }
              });
              $.each(total_nilai1, function(c, d) {
                total1[c] += msConvert(d);
              });

              $.each(total_nilai2, function(c, d) {
                total2[c] += msConvert(d);
              });

              $.each(total_nilai3, function(c, d) {
                total3[c] += msConvert(d);
              });
            });
            
            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                backgroundColor: 'rgba(75,237,57,0.9)',
                borderColor: 'rgba(75,237,57,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(75,237,57,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(75,237,57,0.9)',
                data: total3                                                
              },
              {
                label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                backgroundColor: 'rgba(30,144,255,0.9)',
                borderColor: 'rgba(30,144,255,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(30,144,255,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(30,144,255,0.9)',
                data: total2
              },
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: total1
              },
              ]
            };

            var barChartCanvas = $('#chartMasalahMesinTotal'+v).get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    jam = msConvertTime(value);
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",") + " (" + jam + ")";
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              },
              title: {
                display: true,
                text: 'Masalah Mesin Total (' + v + ')'
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });
          });
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });

    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM');
      if(from_date != '' &&  to_date != '')
      {
        $.each(arrayMesin, function(k, v) {
          if(target == '#laporan_' + v){
            $("#tbody_view_"+v).empty();
            load_data_laporan_masalah_mesin(from_date, to_date, (k+1), v);
          }
        });
      }
      else
      {
        alert('Both Date is required');
      }
    });
  });
</script>

@endsection
