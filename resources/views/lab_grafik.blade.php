@extends('layouts.app_admin')

@section('title')
<title>GRAFIK EVALUASI - PT. DWI SELO GIRI MAS</title>
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
  .filter-btn {
    margin-top: 32px;
  }
  ::-webkit-scrollbar {
    display: block !important;
  }

  ::-webkit-scrollbar-thumb {
    border-radius: 10px;
    background-color: rgba(0, 0, 0, .5);
    box-shadow: 0 0 1px rgba(255, 255, 255, .5);
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
          <h1 class="m-0 text-dark">Grafik Evaluasi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Lab</li>
            <li class="breadcrumb-item">Grafik Evaluasi</li>
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
              <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
              </ul>
            </div>
            <div class="col-2">
              <a href="{{ url('lab/grafik/excel') }}" class="btn btn-primary" id="btn-save-excel" style="width: 100%;">Download Excel</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-content-below-tabContent">
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.5/chartjs-plugin-annotation.js"></script>

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

    load_data_grafik_evaluasi('', '');

    function load_data_grafik_evaluasi(from_date = '', to_date = '')
    {
      $('#custom-content-below-tab').html('');
      $('#custom-content-below-tabContent').html('');
      var url = "{{ url('lab/grafik/view/data/from_date/to_date') }}";
      url = url.replace('from_date', enc(from_date.toString()));
      url = url.replace('to_date', enc(to_date.toString()));
      $.get(url, function (data) {
        console.log(Object.keys(data).length);
        if(Object.keys(data).length > 0){
          var first = true;
          $.each(data, function(k, v) {
            if(first){
              first = false;
              $('#custom-content-below-tab').append(
                '<li class="nav-item">'+
                '<a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#mesh_' + k + '" role="tab" aria-controls="custom-content-below-home" aria-selected="true">' + k + '</a>'+
                '</li>'
              );
              $('#custom-content-below-tabContent').append(
                '<div class="tab-pane fade show active" id="mesh_' + k + '" role="tabpanel" aria-labelledby="custom-content-below-home-tab">'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_ssa">'+
                '</div>'+
                '</div>'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_d50">'+
                '</div>'+
                '</div>'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_white">'+
                '</div>'+
                '</div>'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_moisture">'+
                '</div>'+
                '</div>'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_residue">'+
                '</div>'+
                '</div>'+
                '</div>'
              );
            }else{
              $('#custom-content-below-tab').append(
                '<li class="nav-item">'+
                '<a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#mesh_' + k + '" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">' + k + '</a>'+
                '</li>'
              );
              $('#custom-content-below-tabContent').append(
                '<div class="tab-pane fade" id="mesh_' + k + '" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_ssa">'+
                '</div>'+
                '</div>'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_d50">'+
                '</div>'+
                '</div>'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_white">'+
                '</div>'+
                '</div>'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_moisture">'+
                '</div>'+
                '</div>'+
                '<div style="width:100%; overflow-x: auto;overflow-y:hidden;">'+
                '<div style="width: 6000px;" id="div_mesh_' + k + '_residue">'+
                '</div>'+
                '</div>'+
                '</div>'
              );
            }
          });

          $.each(data, function(k, v) {
              $.each(v, function(k2, v2) {
                $('#div_mesh_'+ k + '_ssa').append(
                  '<div class="chart" id="chartMesh' + k + '_' + v2["no_mesin"] + '_SSA" style="min-height: 250px; height: 250px; max-height: 250px; width: 500px; float: left;">'+
                  '<canvas id="chartValueMesh'+ k +'_' + v2["no_mesin"] + '_SSA" style="min-height: 250px; height: 250px; max-height: 250px; width: 0;"></canvas>'+
                  '</div>'
                );
              });
              $.each(v, function(k2, v2) {
                $('#div_mesh_'+ k + '_d50').append(
                  '<div class="chart" id="chartMesh' + k + '_' + v2["no_mesin"] + '_D50" style="min-height: 250px; height: 250px; max-height: 250px; width: 500px; float: left;">'+
                  '<canvas id="chartValueMesh'+ k +'_' + v2["no_mesin"] + '_D50" style="min-height: 250px; height: 250px; max-height: 250px; width: 0;"></canvas>'+
                  '</div>'
                );
              });
              $.each(v, function(k2, v2) {
                $('#div_mesh_'+ k + '_white').append(
                  '<div class="chart" id="chartMesh' + k + '_' + v2["no_mesin"] + '_WHITE" style="min-height: 250px; height: 250px; max-height: 250px; width: 500px; float: left;">'+
                  '<canvas id="chartValueMesh'+ k +'_' + v2["no_mesin"] + '_WHITE" style="min-height: 250px; height: 250px; max-height: 250px; width: 0;"></canvas>'+
                  '</div>'
                );
              });
              $.each(v, function(k2, v2) {
                $('#div_mesh_'+ k + '_moisture').append(
                  '<div class="chart" id="chartMesh' + k + '_' + v2["no_mesin"] + '_MOISTURE" style="min-height: 250px; height: 250px; max-height: 250px; width: 500px; float: left;">'+
                  '<canvas id="chartValueMesh'+ k +'_' + v2["no_mesin"] + '_MOISTURE" style="min-height: 250px; height: 250px; max-height: 250px; width: 0;"></canvas>'+
                  '</div>'
                );
              });
              $.each(v, function(k2, v2) {
                $('#div_mesh_'+ k + '_residue').append(
                  '<div class="chart" id="chartMesh' + k + '_' + v2["no_mesin"] + '_RESIDUE" style="min-height: 250px; height: 250px; max-height: 250px; width: 500px; float: left;">'+
                  '<canvas id="chartValueMesh'+ k +'_' + v2["no_mesin"] + '_RESIDUE" style="min-height: 250px; height: 250px; max-height: 250px; width: 0;"></canvas>'+
                  '</div>'
                );
              });
          });
        }
      })

      var url_grafik = "{{ url('lab/grafik/get_data/from_date/to_date') }}";
      url_grafik = url_grafik.replace('from_date', enc(from_date.toString()));
      url_grafik = url_grafik.replace('to_date', enc(to_date.toString()));
      $.get(url_grafik, function (data) {
        $.each(data, function(k, v) {
          $.each(v, function(m, y) {
            $.ajax({
              type: "GET",
              url: "{{ url('lab/grafik/get_data_detail') }}",
              data:{ mesh:k, mesin:y.no_mesin, from_date:from_date, to_date:to_date},
              success: function (data) {
                // console.log(data);

                var data_ssa = new Array(31).fill(0);
                var data_d50 = new Array(31).fill(0);
                var data_whiteness = new Array(31).fill(0);
                var data_moisture = new Array(31).fill(0);
                var data_residue = new Array(31).fill(0);

                for(var i in data) {
                  data_ssa.splice(moment(data[i].tanggal_laporan_produksi).format('DD') - 1, 1, data[i].ssa);
                  data_ssa.join();
                  data_d50.splice(moment(data[i].tanggal_laporan_produksi).format('DD') - 1, 1, data[i].d50);
                  data_d50.join();
                  data_whiteness.splice(moment(data[i].tanggal_laporan_produksi).format('DD') - 1, 1, data[i].cie86);
                  data_whiteness.join();
                  data_moisture.splice(moment(data[i].tanggal_laporan_produksi).format('DD') - 1, 1, data[i].moisture);
                  data_moisture.join();
                  data_residue.splice(moment(data[i].tanggal_laporan_produksi).format('DD') - 1, 1, data[i].residue);
                  data_residue.join();
                }

                var daysInMonth = [];

                var monthDate = moment().startOf('month'); 

                for(let i=0;i<monthDate.daysInMonth();i++){
                  let newDay=monthDate.clone().add(i,'days');
                  daysInMonth.push(newDay.format('DD')); 
                }

                var chartdata_SSA = {
                  labels: daysInMonth,
                  datasets : [
                  {
                    label: 'SSA',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: data_ssa
                  },
                  ]
                };

                var chartdata_D50 = {
                  labels: daysInMonth,
                  datasets : [
                  {
                    label: 'D50',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: data_d50
                  },
                  ]
                };

                var chartdata_WHITE = {
                  labels: daysInMonth,
                  datasets : [
                  {
                    label: 'Whiteness',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: data_whiteness
                  },
                  ]
                };

                var chartdata_MOISTURE = {
                  labels: daysInMonth,
                  datasets : [
                  {
                    label: 'Moisture',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: data_moisture
                  },
                  ]
                };

                var chartdata_RESIDUE = {
                  labels: daysInMonth,
                  datasets : [
                  {
                    label: 'Residue',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: data_residue
                  },
                  ]
                };

                var barChartCanvas_SSA = $('#chartValueMesh'+ k + '_' + y.no_mesin + '_SSA').get(0).getContext('2d');
                var barChartCanvas_D50 = $('#chartValueMesh'+ k + '_' + y.no_mesin + '_D50').get(0).getContext('2d');
                var barChartCanvas_WHITENESS = $('#chartValueMesh'+ k + '_' + y.no_mesin + '_WHITE').get(0).getContext('2d');
                var barChartCanvas_MOISTURE = $('#chartValueMesh'+ k + '_' + y.no_mesin + '_MOISTURE').get(0).getContext('2d');
                var barChartCanvas_RESIDUE = $('#chartValueMesh'+ k + '_' + y.no_mesin + '_RESIDUE').get(0).getContext('2d');

                var barChartOptions_SSA = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
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
                      barPercentage: 0.4,
                      ticks: {
                        autoSkip: true,
                        maxTicksLimit: 10
                      },
                      gridLines: {
                        display: false
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Mesh ' + k + ' (SSA) Mesin ' + y.mesin
                  },
                  legend: {
                    display: false
                  },
                  annotation: {
                    annotations: [{
                      type: 'line',
                      mode: 'horizontal',
                      scaleID: 'y-axis-0',
                      value: (k == '250' ? '2500' : (k == '325' ? '5500' : (k == '500' ? '7000' : (k == '800' ? '8000' : (k == '1200' ? '11500' : (k == '1500' ? '12000' : (k == '2000' ? '13500' : (k == '6000' ? '14000' : '0')))))))),
                      borderColor: 'red',
                      borderWidth: 2
                    }]
                  }
                }

                var barChartOptions_D50 = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
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
                      barPercentage: 0.4,
                      ticks: {
                        autoSkip: true,
                        maxTicksLimit: 10
                      },
                      gridLines: {
                        display: false
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Mesh ' + k + ' (D50) Mesin ' + y.mesin
                  },
                  legend: {
                    display: false
                  },
                  annotation: {
                    annotations: [{
                      type: 'line',
                      mode: 'horizontal',
                      scaleID: 'y-axis-0',
                      value: (k == '250' ? '9.0' : (k == '325' ? '7.5' : (k == '500' ? '6.5' : (k == '800' ? '2.8' : (k == '1200' ? '2.4' : (k == '1500' ? '2.2' : (k == '2000' ? '2.0' : (k == '6000' ? '1.8' : '0')))))))),
                      borderColor: 'red',
                      borderWidth: 2
                    }]
                  }
                }

                var barChartOptions_WHITENESS = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
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
                      barPercentage: 0.4,
                      ticks: {
                        autoSkip: true,
                        maxTicksLimit: 10
                      },
                      gridLines: {
                        display: false
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Mesh ' + k + ' (Whiteness) Mesin ' + y.mesin
                  },
                  legend: {
                    display: false
                  },
                  annotation: {
                    annotations: [{
                      type: 'line',
                      mode: 'horizontal',
                      scaleID: 'y-axis-0',
                      value: (k == '250' ? '83.5' : (k == '325' ? '90.5' : (k == '500' ? '91.5' : (k == '800' ? '92.5' : (k == '1200' ? '93.0' : (k == '1500' ? '93.0' : (k == '2000' ? '93.5' : (k == '6000' ? '93.5' : '0')))))))),
                      borderColor: 'red',
                      borderWidth: 2
                    }]
                  }
                }

                var barChartOptions_MOISTURE = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
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
                      barPercentage: 0.4,
                      ticks: {
                        autoSkip: true,
                        maxTicksLimit: 10
                      },
                      gridLines: {
                        display: false
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Mesh ' + k + ' (Moisture) Mesin ' + y.mesin
                  },
                  legend: {
                    display: false
                  },
                  annotation: {
                    annotations: [{
                      type: 'line',
                      mode: 'horizontal',
                      scaleID: 'y-axis-0',
                      value: '0.4',
                      borderColor: 'red',
                      borderWidth: 2
                    }]
                  }
                }

                var barChartOptions_RESIDUE = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
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
                      barPercentage: 0.4,
                      ticks: {
                        autoSkip: true,
                        maxTicksLimit: 10
                      },
                      gridLines: {
                        display: false
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Mesh ' + k + ' (Residue) Mesin ' + y.mesin
                  },
                  legend: {
                    display: false
                  },
                  annotation: {
                    annotations: [{
                      type: 'line',
                      mode: 'horizontal',
                      scaleID: 'y-axis-0',
                      value: (k == '250' ? '8.00' : (k == '325' ? '0.10' : (k == '500' ? '0.05' : (k == '800' ? '0.01' : (k == '1200' ? '0.01' : (k == '1500' ? '0.01' : (k == '2000' ? '0.01' : (k == '6000' ? '0.01' : '0')))))))),
                      borderColor: 'red',
                      borderWidth: 2
                    }]
                  }
                }

                var barChart_SSA = new Chart(barChartCanvas_SSA, {
                  type: 'bar', 
                  data: chartdata_SSA,
                  options: barChartOptions_SSA
                });

                var barChart_D50 = new Chart(barChartCanvas_D50, {
                  type: 'bar', 
                  data: chartdata_D50,
                  options: barChartOptions_D50
                });

                var barChart_WHITENESS = new Chart(barChartCanvas_WHITENESS, {
                  type: 'bar', 
                  data: chartdata_WHITE,
                  options: barChartOptions_WHITENESS
                });

                var barChart_MOISTURE = new Chart(barChartCanvas_MOISTURE, {
                  type: 'bar', 
                  data: chartdata_MOISTURE,
                  options: barChartOptions_MOISTURE
                });

                var barChart_RESIDUE = new Chart(barChartCanvas_RESIDUE, {
                  type: 'bar', 
                  data: chartdata_RESIDUE,
                  options: barChartOptions_RESIDUE
                });
              },
              error: function (data) {
                console.log('Error:', data);
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          });
        });
      })
    }

    $('#filter').click(function(){
      var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM');
      var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM');
      if(from_date != '' &&  to_date != '')
      {
        load_data_grafik_evaluasi(from_date, to_date);
      }
      else
      {
        alert('Both Date is required');
      }
    });
  });
</script>

@endsection