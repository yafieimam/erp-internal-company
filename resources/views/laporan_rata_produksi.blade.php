@extends('layouts.app_admin')

@section('title')
<title>LAPORAN RATA-RATA PRODUKSI - PT. DWI SELO GIRI MAS</title>
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
          <h1 class="m-0 text-dark">Laporan Rata-Rata Produksi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Produksi</li>
            <li class="breadcrumb-item">Laporan Rata-Rata Produksi</li>
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
        <div class="card-header">
          <div class="row">
            <div class="col-10">
              <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#data_perbandingan" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Data Perbandingan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#grafik_perbandingan" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Grafik Perbandingan</a>
                </li>
              </ul>
            </div>
            <div class="col-2">
              <a href="{{ url('laporan_rata_produksi/excel') }}" class="btn btn-primary" id="btn-save-excel" style="width: 100%;">Download Excel</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade show active" id="data_perbandingan" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
              <table style="width: 100%; font-size: 12px;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th style="width: 7%; vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">Tahun</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Januari</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Februari</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Maret</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">April</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Mei</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Juni</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Juli</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Agustus</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">September</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Oktober</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">November</th>
                    <th style="width: 7%; vertical-align : middle; text-align: center;">Desember</th>
                  </tr>
                </thead>
                <tbody id="tbody_view">
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="grafik_perbandingan" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
              <div class="chart">
                <canvas id="chartRataProduksiTotal" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiSA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiSB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiMixer" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiRA" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiRB" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiRC" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiRD" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiRE" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiRF" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="chart">
                <canvas id="chartRataProduksiRG" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
  $(document).ready(function () {

    load_data_perbandingan();

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
      target = $(e.target).attr("href");
      if(target == '#data_perbandingan'){
        load_data_perbandingan();
      }else if(target == '#grafik_perbandingan'){
        load_grafik_perbandingan();
      }
    });

    function numberWithCommas(x) {
      var parts = x.toString().split(".");
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      return parts.join(",");
    }

    function load_data_perbandingan()
    {
      var url = "{{ url('laporan_rata_produksi/view/data') }}";
      $("#tbody_view").empty();

      var tanggal = new Date();
      var tahun = tanggal.getFullYear();
      var arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];
      var arr_bulan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
      var arr_tahun = [tahun - 2, tahun - 1, tahun];

      var total = [];

      for(var j = 0; j < arr_tahun.length; j++){
        total[arr_tahun[j]] = [];
        for(var k = 0; k < arr_bulan.length; k++){
          total[arr_tahun[j]][arr_bulan[k]] = 0;
        }
      }

      $.get(url, function (data) {
        $.each(arr_mesin, function(km, vm) {
          $('#tbody_view').append(
            '<tr id="tr_mesin_'+vm+'">'+
            '<td style="vertical-align : middle; text-align: center;" rowspan="'+arr_tahun.length+'">'+vm+'</td>'+
            '</tr>'
          );
          $.each(arr_tahun, function(kt, vt) {
            if(kt == 0){
              $('#tr_mesin_'+vm).append(
                '<td style="vertical-align : middle; text-align: center;">'+vt+'</td>'
              );

              $.each(arr_bulan, function(kb, vb) {
                if(data[vm][vt][vb] ==  null){
                  $('#tr_mesin_'+vm).append(
                    '<td style="vertical-align : middle; text-align: center;">-</td>'
                  );
                }else{
                  total[vt][vb] += parseInt(data[vm][vt][vb]);
                  $('#tr_mesin_'+vm).append(
                    '<td style="vertical-align : middle; text-align: center;">'+numberWithCommas(parseFloat(data[vm][vt][vb]).toFixed(2))+'</td>'
                  );
                }
              });
            }else{
              $('#tbody_view').append(
                '<tr id="tr_tahun_'+vm+'_'+vt+'">'+
                '<td style="vertical-align : middle; text-align: center;">'+vt+'</td>'+
                '</tr>'
              );

              $.each(arr_bulan, function(kb, vb) {
                if(data[vm][vt][vb] ==  null){
                  $('#tr_tahun_'+vm+'_'+vt).append(
                    '<td style="vertical-align : middle; text-align: center;">-</td>'
                  );
                }else{
                  total[vt][vb] += parseInt(data[vm][vt][vb]);
                  $('#tr_tahun_'+vm+'_'+vt).append(
                    '<td style="vertical-align : middle; text-align: center;">'+numberWithCommas(parseFloat(data[vm][vt][vb]).toFixed(2))+'</td>'
                  );
                }
              });
            }
          });
        });
        $('#tbody_view').append(
          '<tr id="tr_total">'+
          '<td style="vertical-align : middle; text-align: center;" rowspan="'+arr_tahun.length+'">Total</td>'+
          '</tr>'
        );
        $.each(arr_tahun, function(kt, vt) {
          if(kt == 0){
            $('#tr_total').append(
              '<td style="vertical-align : middle; text-align: center;">'+vt+'</td>'
            );

            $.each(arr_bulan, function(kb, vb) {
              if(total[vt][vb] ==  null){
                $('#tr_total').append(
                  '<td style="vertical-align : middle; text-align: center;">-</td>'
                );
              }else{
                $('#tr_total').append(
                  '<td style="vertical-align : middle; text-align: center;">'+numberWithCommas(parseFloat(total[vt][vb]).toFixed(2))+'</td>'
                );
              }
            });
          }else{
            $('#tbody_view').append(
              '<tr id="tr_tahun_'+vt+'">'+
              '<td style="vertical-align : middle; text-align: center;">'+vt+'</td>'+
              '</tr>'
            );

            $.each(arr_bulan, function(kb, vb) {
              if(total[vt][vb] ==  null){
                $('#tr_tahun_'+vt).append(
                  '<td style="vertical-align : middle; text-align: center;">-</td>'
                  );
              }else{
                $('#tr_tahun_'+vt).append(
                  '<td style="vertical-align : middle; text-align: center;">'+numberWithCommas(parseFloat(total[vt][vb]).toFixed(2))+'</td>'
                  );
              }
            });
          }
        });
      })
    }

    function load_grafik_perbandingan()
    {
      var arr_mesin = ['Total', 'SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

      $.ajax({
        type: "GET",
        url: "{{ url('laporan_rata_produksi/grafik/data') }}",
        success: function (data) {
          $.each(arr_mesin, function(k, v) {
            var rata1 = new Array(12).fill(0);
            var rata2 = new Array(12).fill(0);
            var rata3 = new Array(12).fill(0);

            for(var i in data[v]) {
              if(data[v][i].tahun == '{{ date("Y") }}'){
                rata1.splice(data[v][i].bulan - 1, 0, data[v][i].tonase);
                rata1.join();
              }else if(data[v][i].tahun == "{{ date('Y', strtotime('-1 year')) }}"){
                rata2.splice(data[v][i].bulan - 1, 0, data[v][i].tonase);
                rata2.join();
              }else if(data[v][i].tahun == "{{ date('Y', strtotime('-2 year')) }}"){
                rata3.splice(data[v][i].bulan - 1, 0, data[v][i].tonase);
                rata3.join();
              }
            }

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
                data: rata3                                                
              },
              {
                label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                backgroundColor: 'rgba(30,144,255,0.9)',
                borderColor: 'rgba(30,144,255,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(30,144,255,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(30,144,255,0.9)',
                data: rata2
              },
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: rata1
              },
              ]
            };

            var barChartCanvas = $('#chartRataProduksi'+v).get(0).getContext('2d');
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
                    value = parseFloat(value).toFixed(2).toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
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
                text: 'Rata-Rata Produksi (' + v + ')'
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
  });
</script>

@endsection
