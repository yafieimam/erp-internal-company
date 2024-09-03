@extends('layouts.app_admin')

@section('title')
<title>PARAMETER KPI - PT. DWI SELO GIRI MAS</title>
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
  #btn-edit, #btn-new  {
    right: 20%;
    position: absolute;
    top: 40px;
    width: 60%;
    height: 40px;
  }
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
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
    #btn-edit, #btn-new  {
      position: unset;
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
          <h1 class="m-0 text-dark">Data Parameter KPI</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item">Parameter KPI</li>
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
            <div class="col-4">
              <button type="button" name="btn_input_data" id="btn_input_data" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_data">Input Data</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table id="data_parameter_kpi_table" style="width: 100%;" class="table table-bordered table-hover responsive">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Perusahaan</th>
                <th>Unit</th>
                <th>Bagian</th>
                <th width="13%"></th>
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
          <h4 class="modal-title">Input Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="company">Perusahaan</label>
                <select id="company" name="company" class="form-control" style="width: 100%;">
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="unit">Unit</label>
                <select id="unit" name="unit" class="form-control" style="width: 100%;">
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="bagian">Bagian</label>
                <select id="bagian" name="bagian" class="form-control" style="width: 100%;">
                </select>
              </div>
            </div>
          </div>
          <div class="row"> 
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <form method="post" class="input_form" id="input_form" action="javascript:void(0)" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="indikator">Indikator</label>
                          <textarea class="form-control" rows="2" name="indikator" id="indikator" placeholder="Indikator"></textarea>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label for="persentase">Persentase (%)</label>
                          <input class="form-control" type="text" name="persentase" id="persentase" placeholder="Persentase (%)" />
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="description">Deskripsi</label>
                          <textarea class="form-control" rows="2" name="description" id="description" placeholder="Deskripsi"></textarea>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <label>&nbsp</label>
                          <input class="form-control btn btn-success" type="submit" name="btn-add" id="btn-add" value="Add"/>
                        </div>
                      </div>
                    </div>
                  </form>
                  <table id="data_parameter_kpi_detail_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                    <thead>
                      <tr>
                        <th>Indikator</th>
                        <th>Persentase (%)</th>
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
          <button type="submit" class="btn btn-primary" id="btn-save-input">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal_view_data">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 lihat-table">
              <table class="table" style="border: none;" id="lihat-table">
                <tr>
                  <td style="border: none;">Kode</td>
                  <td style="border: none;">:</td>
                  <td id="td_kode" style="border: none;"></td>
                  <td style="border: none;">Perusahaan</td>
                  <td style="border: none;">:</td> 
                  <td id="td_company" style="border: none;"></td>
                </tr>
                <tr>
                  <td style="border: none;">Unit</td>
                  <td style="border: none;">:</td>
                  <td id="td_unit" style="border: none;"></td>
                  <td style="border: none;">Bagian</td>
                  <td style="border: none;">:</td>
                  <td id="td_bagian" style="border: none;"></td>
                </tr>
              </table>

              <table class="table table-bordered" id="lihat-table-detail">
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

  <div class="modal fade" id="modal_edit_data">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="edit_company">Perusahaan</label>
                <input class="form-control" type="hidden" name="edit_kode" id="edit_kode" />
                <select id="edit_company" name="edit_company" class="form-control" style="width: 100%;">
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="edit_unit">Unit</label>
                <select id="edit_unit" name="edit_unit" class="form-control" style="width: 100%;">
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="edit_bagian">Bagian</label>
                <select id="edit_bagian" name="edit_bagian" class="form-control" style="width: 100%;">
                </select>
              </div>
            </div>
          </div>
          <div class="row"> 
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-4">
                      <div class="form-group">
                        <label for="edit_indikator">Indikator</label>
                        <input class="form-control" type="hidden" name="edit_kode_detail" id="edit_kode_detail" />
                        <textarea class="form-control" rows="2" name="edit_indikator" id="edit_indikator" placeholder="Indikator"></textarea>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="edit_persentase">Persentase (%)</label>
                        <input class="form-control" type="text" name="edit_persentase" id="edit_persentase" placeholder="Persentase (%)" />
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label for="edit_description">Deskripsi</label>
                        <textarea class="form-control" rows="2" name="edit_description" id="edit_description" placeholder="Deskripsi"></textarea>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label>&nbsp</label>
                        <button type="button" class="btn btn-success" id="btn-new">Add</button>
                        <button type="button" class="btn btn-success" id="btn-edit" style="display: none;">Edit</button>
                      </div>
                    </div>
                  </div>
                  <table id="data_edit_parameter_kpi_detail_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                    <thead>
                      <tr>
                        <th>Indikator</th>
                        <th>Persentase (%)</th>
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
          <button type="submit" class="btn btn-primary" id="btn-save-edit">Save changes</button>
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

<script>
  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>

<script type="text/javascript">
  $(document).ready(function () {
    let key = "{{ env('MIX_APP_KEY') }}";

    var table = $('#data_parameter_kpi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_parameter_kpi/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'kode',
           name:'kode'
         },
         {
           data:'company',
           name:'company'
         },
         {
           data:'unit',
           name:'unit'
         },
         {
           data:'bagian',
           name:'bagian'
         },
         {
           data:'action',
           name:'action'
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

    function load_data_parameter_kpi()
    {
      table = $('#data_parameter_kpi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_parameter_kpi/table") }}',
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'kode',
           name:'kode'
         },
         {
           data:'company',
           name:'company'
         },
         {
           data:'unit',
           name:'unit'
         },
         {
           data:'bagian',
           name:'bagian'
         },
         {
           data:'action',
           name:'action'
         }
       ]
     });
    }

    function load_data_parameter_kpi_detail(id_user = '')
    {
      table = $('#data_parameter_kpi_detail_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_parameter_kpi/detail/table") }}',
          data:{id_user:id_user},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'indikator',
           name:'indikator'
         },
         {
           data:'persentase',
           name:'persentase',
           render: $.fn.dataTable.render.number('.', "%", ','),
           width: '15%'
         },
         {
           data:'action',
           name:'action',
           width: '5%'
         }
       ]
     });
    }

    function load_data_edit_parameter_kpi_detail(kode = '')
    {
      table = $('#data_edit_parameter_kpi_detail_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("master_parameter_kpi/detail/edit/table") }}',
          data:{kode:kode},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'tr',
        sort: false,
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
         {
           data:'indikator',
           name:'indikator'
         },
         {
           data:'persentase',
           name:'persentase',
           render: $.fn.dataTable.render.number('.', "%", ','),
           width: '15%'
         },
         {
           data:'action',
           name:'action',
           width: '10%'
         }
       ]
     });
    }

    $('body').on('click', '#btn_input_data', function () {
      $('#unit').attr("disabled","disabled");
      $('#bagian').attr("disabled","disabled");

      var id_user = "{{ Session::get('id_user_admin') }}";
      var url = "{{ url('get_company') }}";
      $.get(url, function (data) {
        $('#company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })
      $("#company").change(function() {
        var val = $(this).val();

        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(val.toString()));
        $.get(url_unit, function (data) {
          if(data.length == 0){
            $('#unit').children().remove().end();
            $('#unit').attr("disabled","disabled");
            $('#bagian').children().remove().end();
            $('#bagian').attr("disabled","disabled");
          }else{
            $('#unit').removeAttr('disabled');
            $('#unit').children().remove().end().append('<option value="" selected>Pilih Unit</option>');
            $.each(data, function(k, v) {
              $('#unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
            });
          }
        })
      });
      $("#unit").change(function() {
        var val = $(this).val();
        var company = $('#company').val();

        var url_bagian = "{{ url('get_bagian/company/unit') }}";
        url_bagian = url_bagian.replace('company', enc(company.toString()));
        url_bagian = url_bagian.replace('unit', enc(val.toString()));
        $.get(url_bagian, function (data) {
          if(data.length == 0){
            $('#bagian').children().remove().end();
            $('#bagian').attr("disabled","disabled");
          }else{
            $('#bagian').removeAttr('disabled');
            $('#bagian').children().remove().end().append('<option value="" selected>Pilih Bagian</option>');
            $.each(data, function(k, v) {
              $('#bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
            });
          }
        })
      });
      $('#data_parameter_kpi_detail_table').DataTable().destroy();
      load_data_parameter_kpi_detail(id_user);
    });

    $('body').on('click', '#btn-save-input', function () {
      var company = $("#company").val();
      var unit = $("#unit").val();
      var bagian = $("#bagian").val();

      var count = $("#data_parameter_kpi_detail_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Anda Belum Menambahkan Parameter KPI");
      }else{
        if((company == null || company == "")){
          alert("Perusahaan Harus Diisi");
        }else if((bagian == null || bagian == "")){
          alert("Bagian Harus Diisi");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('master_parameter_kpi/input') }}",
            data: { 'company' : company, 'unit' : unit, 'bagian' : bagian },
            success:function(data){
              alert("Data Successfully Added");
              $('#modal_input_data').modal('hide');
              $("#modal_input_data").trigger('click');
              var oTable = $('#data_parameter_kpi_table').dataTable();
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

    $('body').on('click', '#view-data', function () {
      var kode = $(this).data("id");
      var url = "{{ url('master_parameter_kpi/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#td_kode').html('');
      $('#td_company').html('');
      $('#td_unit').html('');
      $('#td_bagian').html('');
      $('#lihat-table-detail').html('');
      $.get(url, function (data) {
        $('#td_kode').html(data.kode);
        $('#td_company').html(data.company);
        $('#td_unit').html(data.unit);
        $('#td_bagian').html(data.bagian);
        var url_detail = "{{ url('master_parameter_kpi/detail/view/id') }}";
        url_detail = url_detail.replace('id', enc(kode.toString()));
        $.get(url_detail, function (data_detail) {
          $('#lihat-table-detail').append(
              '<tr>'+
              '<th>Indikator</th>'+
              '<th>Persentase</th>'+
              '<th>Description</th>'+
              '</tr>'
            );
          $.each(data_detail, function(k, v) {
            $('#lihat-table-detail').append(
              '<tr>'+
              '<td>'+ v.indikator +'</td>'+
              '<td>'+ v.persentase +'</td>'+
              '<td>'+ v.description +'</td>'+
              '</tr>'
            );
          });
        })
      })
    });

    $("#edit_company").change(function(e) {
      e.stopImmediatePropagation();
      var val = $(this).val();
      var url_unit = "{{ url('get_unit/company') }}";
      url_unit = url_unit.replace('company', enc(val.toString()));
      $.get(url_unit, function (data_unit) {
        if(data_unit.length == 0){
          $('#edit_unit').children().remove().end();
          $('#edit_unit').attr("disabled","disabled");
          $('#edit_bagian').children().remove().end();
          $('#edit_bagian').attr("disabled","disabled");
        }else{
          $('#edit_unit').removeAttr('disabled');
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
        }
      })
    });

    $("#edit_unit").change(function(e) {
      e.stopImmediatePropagation();
      var val = $(this).val();
      var company = $('#edit_company').val();
      var url_bagian = "{{ url('get_bagian/company/unit') }}";
      url_bagian = url_bagian.replace('company', enc(company.toString()));
      url_bagian = url_bagian.replace('unit', enc(val.toString()));
      $.get(url_bagian, function (data) {
        if(data.length == 0){
          $('#edit_bagian').children().remove().end();
          $('#edit_bagian').attr("disabled","disabled");
        }else{
          $('#edit_bagian').removeAttr('disabled');
          $('#edit_bagian').children().remove().end().append('<option value="">Pilih Bagian</option>');
          $.each(data, function(k, v) {
            $('#edit_bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
          });
        }
      })
    });

    $('body').on('click', '#edit-data', function () {
      var kode = $(this).data("id");
      $('#edit_unit').attr("disabled","disabled");
      $('#edit_bagian').attr("disabled","disabled");

      var url_company = "{{ url('get_company') }}";
      $.get(url_company, function (data) {
        $('#edit_company').children().remove().end().append('<option value="" selected>Pilih Perusahaan</option>');
        $.each(data, function(k, v) {
          $('#edit_company').append('<option value="' + v.kode_perusahaan + '">' + v.nama_perusahaan + '</option>');
        });
      })
      var url_unit = "{{ url('get_unit') }}";
      $.get(url_unit, function (data) {
        $('#edit_unit').children().remove().end().append('<option value="" selected>Pilih Unit</option>');
        $.each(data, function(k, v) {
          $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
        });
      })
      var url_bagian = "{{ url('get_bagian') }}";
      $.get(url_bagian, function (data) {
        $('#edit_bagian').children().remove().end().append('<option value="" selected>Pilih Bagian</option>');
        $.each(data, function(k, v) {
          $('#edit_bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
        });
      })
      var url = "{{ url('master_parameter_kpi/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#edit_kode').val('');
      // $('#edit_company').val('').trigger('change');
      // $('#edit_unit').val('').trigger('change');
      // $('#edit_bagian').val('').trigger('change');
      $.get(url, function (data) {
        $('#edit_kode').val(data.kode);
        $('#edit_company').val(data.kode_perusahaan);
        var url_unit = "{{ url('get_unit/company') }}";
        url_unit = url_unit.replace('company', enc(data.kode_perusahaan.toString()));
        $.get(url_unit, function (data_unit) {
          $('#edit_unit').children().remove().end().append('<option value="">Pilih Unit</option>');
          $.each(data_unit, function(k, v) {
            $('#edit_unit').append('<option value="' + v.kode_unit + '">' + v.nama_unit + '</option>');
          });
          $('#edit_unit').removeAttr('disabled');
          $('#edit_unit').val(data.kode_unit);
        })
        var url_bagian = "{{ url('get_bagian/company/unit') }}";
        url_bagian = url_bagian.replace('company', enc(data.kode_perusahaan.toString()));
        url_bagian = url_bagian.replace('unit', enc(data.kode_unit.toString()));
        $.get(url_bagian, function (data_bag) {
          $('#edit_bagian').children().remove().end().append('<option value="">Pilih Bagian</option>');
          $.each(data_bag, function(k, v) {
            $('#edit_bagian').append('<option value="' + v.kode_bagian + '">' + v.nama_bagian + '</option>');
          });
          $('#edit_bagian').removeAttr('disabled');
          $('#edit_bagian').val(data.kode_bagian).trigger('change');
        })
        $('#data_edit_parameter_kpi_detail_table').DataTable().destroy();
        load_data_edit_parameter_kpi_detail(data.kode);
      })
    });

    $('body').on('click', '#edit-data-detail', function () {
      var kode = $(this).data("id");
      var url = "{{ url('master_parameter_kpi/detail/edit/view/id') }}";
      url = url.replace('id', enc(kode.toString()));
      $('#edit_kode_detail').val('');
      $('#edit_indikator').html('');
      $('#edit_persentase').val('');
      $('#edit_description').html('');
      $.get(url, function (data) {
        $('#edit_kode_detail').val(data.kode);
        $('#edit_indikator').html(data.indikator);
        $('#edit_persentase').val(data.persentase);
        $('#edit_description').html(data.description);
        $('#btn-edit').show();
        $('#btn-new').hide();
      })
    });

    $('body').on('click', '#btn-new', function () {
      var kode = $("#edit_kode").val();
      var indikator = $("#edit_indikator").val();
      var persentase = $("#edit_persentase").val();
      var description = $("#edit_description").val();

      if((indikator == null || indikator == "")){
        alert("Indikator Harus Diisi");
      }else if((persentase == null || persentase == "")){
        alert("Persentase Harus Diisi");
      }else{
        $.ajax({
          type:"GET",
          url:"{{ url('master_parameter_kpi/detail/edit/input') }}",
          data: { 'kode' : kode, 'indikator' : indikator, 'persentase' : persentase, 'description' : description },
          success:function(data){
            $("#edit_kode_detail").val('');
            $("#edit_indikator").val('');
            $("#edit_persentase").val('');
            $("#edit_description").val('');
            var oTable = $('#data_edit_parameter_kpi_detail_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-edit', function () {
      var kode = $("#edit_kode_detail").val();
      var indikator = $("#edit_indikator").val();
      var persentase = $("#edit_persentase").val();
      var description = $("#edit_description").val();

      if((indikator == null || indikator == "")){
        alert("Indikator Harus Diisi");
      }else if((persentase == null || persentase == "")){
        alert("Persentase Harus Diisi");
      }else{
        $.ajax({
          type:"GET",
          url:"{{ url('master_parameter_kpi/detail/edit') }}",
          data: { 'kode' : kode, 'indikator' : indikator, 'persentase' : persentase, 'description' : description },
          success:function(data){
            $("#edit_kode_detail").val('');
            $("#edit_indikator").val('');
            $("#edit_persentase").val('');
            $("#edit_description").val('');
            var oTable = $('#data_edit_parameter_kpi_detail_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-save-edit', function () {
      var kode = $("#edit_kode").val();
      var company = $("#edit_company").val();
      var unit = $("#edit_unit").val();
      var bagian = $("#edit_bagian").val();

      var count = $("#data_edit_parameter_kpi_detail_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Anda Belum Menambahkan Parameter KPI");
      }else{
        if((company == null || company == "")){
          alert("Perusahaan Harus Diisi");
        }else if((bagian == null || bagian == "")){
          alert("Bagian Harus Diisi");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('master_parameter_kpi/edit') }}",
            data: { 'kode' : kode, 'company' : company, 'unit' : unit, 'bagian' : bagian },
            success:function(data){
              alert("Data Successfully Updated");
              $('#modal_edit_data').modal('hide');
              $("#modal_edit_data").trigger('click');
              var oTable = $('#data_parameter_kpi_table').dataTable();
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

    $('body').on('click', '#delete-data', function () {
      var kode = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('master_parameter_kpi/delete') }}",
          data: { 'kode' : kode },
          success: function (data) {
            var oTable = $('#data_parameter_kpi_table').dataTable();
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

    $('body').on('click', '#delete-data-detail', function () {
      var indikator = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('master_parameter_kpi/detail/delete') }}",
          data: { 'indikator' : indikator },
          success: function (data) {
            var oTable = $('#data_parameter_kpi_detail_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#delete-edit-data-detail', function () {
      var kode = $(this).data("id");
      if(confirm("Data Dihapus?")){
        $.ajax({
          type: "GET",
          url: "{{ url('master_parameter_kpi/detail/edit/delete') }}",
          data: { 'kode' : kode },
          success: function (data) {
            var oTable = $('#data_edit_parameter_kpi_detail_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('#input_form').validate({
      rules: {
        indikator: {
          required: true,
        },
        persentase: {
          required: true,
        },
      },
      messages: {
        indikator: {
          required: "Indikator Harus Diisi",
        },
        persentase: {
          required: "Persentase Harus Diisi",
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
        var myform = document.getElementById("input_form");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('master_parameter_kpi/detail/input') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            $('#input_form').trigger("reset");
            var oTable = $('#data_parameter_kpi_detail_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong, Please Try Again");
          }
        });
      }
    });
  });
</script>

@endsection