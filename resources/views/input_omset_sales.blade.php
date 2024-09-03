@extends('layouts.app_en')

@section('title')
<title>INPUT OMSET SALES - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="{{asset('app-assets/fonts/material-icon/css/material-design-iconic-font.min.css')}}">
  <link rel="stylesheet" href="{{asset('app-assets/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/util.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/select2/select2.min.css')}}">
  <style type="text/css">
    .separator {
      display: flex;
      align-items: center;
      text-align: center;
      color: #1d4990;
      font-size: 20px;
      margin-bottom: 50px;
    }
    .separator::before, .separator::after {
      content: '';
      flex: 1;
      border-bottom: 3px solid #1d4990;
    }
    .separator::before {
      margin-right: .45em;
    }
    .separator::after {
      margin-left: .45em;
    }
  </style>
@endsection

@section('content')
<div class="main">

        <div class="container">
            <div class="signup-content">
                <div class="signup-form p-t-50">
                  @if ($errors->any())
                    <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif
                  <span class="login100-form-title wow fadeInDown">Upload Excel Omset Sales</span>
                  <form method="post" class="upload-form" id="upload-form" action="{{ url('/uploadExcelOmsetSales') }}" enctype="multipart/form-data">
                      {{ csrf_field() }}
                  <div class="form-row" style="justify-content: center; margin-top: 30px;">
                    <div class="form-group">
                      <div class="form-input wow fadeInDown">
                        <label for="upload_excel" class="required">Upload Excel</label>
                        <input class="input-baru" type="file" name="upload_excel" id="upload_excel" placeholder="Upload Excel" />
                      </div>
                      <div class="form-submit wow fadeInUp">
                        <input type="submit" value="Submit" class="submit" id="submit-upload" name="submit-upload" style="margin: 0;" />
                      </div>
                    </div>
                  </div>
                  </form>
                  <div class="separator">Atau Input Manual</div>
                    <span class="login100-form-title wow fadeInDown">Input Omset Sales</span>
                    <form method="post" class="register-form" id="register-form">
                      {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group">
                                <div class="form-select validate-input wow fadeInUp" data-validate = "Produk harus diisi">
                                    <div class="label-flex">
                                        <label for="produk" class="required">Produk</label>
                                    </div>
                                    <div class="select-list" style="margin-bottom: 0px;">
                                        <select class="dropdown-baru" name="produk" id="produk">
                                            <option class="tipeName" value="" selected>Produk</option>
                                            @foreach($produk_data as $produk)
                                              <option class="tipeName" value="{{ $produk->kode_produk_komputer }}"> {{ $produk->nama_produk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-select validate-input wow fadeInUp" data-validate = "Customer is required">
                                    <div class="label-flex">
                                        <label for="customer" class="required">Customer</label>
                                    </div>
                                    <div class="select-list" style="margin-bottom: 0px;">
                                        <select class="customer" name="customer" id="customer"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Tanggal harus diisi">
                                    <label for="tanggal_penjualan" class="required">Tanggal</label>
                                    <input class="input-baru" type="text" name="tanggal_penjualan" id="tanggal_penjualan" placeholder="Tanggal" data-date-format="YYYY-MM-DD" value="{{ Session::get('tanggal_penjualan') }}" />
                                </div>
                                <div class="form-row">
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Jumlah Tonase harus diisi">
                                    <label for="jml_tonase" class="required">Jumlah Tonase</label>
                                    <input class="input-baru" type="text" name="jml_tonase" id="jml_tonase" placeholder="Jumlah Tonase" />
                                </div>
                                </div>
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Total Omset harus diisi">
                                    <label for="jml_omset" class="required">Total Omset</label>
                                    <input class="input-baru" type="text" name="jml_omset" id="jml_omset" placeholder="Total Omset" />
                                </div>
                                </div>
                                </div>
                            </div>
                          </div>
                        <div class="form-submit wow fadeInUp">
                            <input type="submit" value="Submit" class="submit" id="submit" name="submit" onclick="javascript: form.action='{{ url('/inputOmsetSales') }}';" />
                            <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/input_omset_sales') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
  <script src="{{asset('app-assets/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('app-assets/js/main_baru.js')}}"></script>
  <script src="{{asset('app-assets/js/main.js')}}"></script>

  <script>
    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>

  <script>
    $(document).ready(function(){
     $('#tanggal_penjualan').datepicker({
      todayBtn:'linked',
      format:'yyyy-mm-dd',
      autoclose:true
     }).datepicker("setDate",'now');
   });
  </script>

  <script type="text/javascript">
      $('.customer').select2({
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
  </script>

  <script type="text/javascript">
    $(".customer").on("select2:open", function() {
        $(".select2-search__field").attr("placeholder", "Search Customer Here...");
    });
    $(".customer").on("select2:close", function() {
        $(".select2-search__field").attr("placeholder", null);
    });
  </script>
@endsection