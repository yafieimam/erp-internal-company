@extends('layouts.app_en')

@section('title')
<title>INPUT OMSET PRODUKSI - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="{{asset('app-assets/fonts/material-icon/css/material-design-iconic-font.min.css')}}">
  <link rel="stylesheet" href="{{asset('app-assets/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/util.css')}}">
@endsection

@section('content')
<div class="main">

        <div class="container">
            <div class="signup-content">
                <div class="signup-form p-t-50">
                    <span class="login100-form-title wow fadeInDown">Input Omset Produksi</span>
                      @if ($errors->any())
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
                          <ul>
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                          </ul>
                        </div>
                      @endif
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
                                <div class="form-row">
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Jumlah Customer harus diisi">
                                    <label for="jml_customer" class="required">Jumlah Customer</label>
                                    <input class="input-baru" type="text" name="jml_customer" id="jml_customer" placeholder="Jumlah Customer" />
                                </div>
                                </div>
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Jumlah Tonase harus diisi">
                                    <label for="jml_tonase" class="required">Jumlah Tonase</label>
                                    <input class="input-baru" type="text" name="jml_tonase" id="jml_tonase" placeholder="Jumlah Tonase" />
                                </div>
                                </div>
                                </div>
                                <div class="form-row">
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Whiteness harus diisi">
                                    <label for="whiteness" class="required">Kualitas</label>
                                    <input class="input-baru" type="text" name="whiteness" id="whiteness" placeholder="Whiteness" />
                                </div>
                                </div>
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Residue harus diisi">
                                    <label for="residue">&nbsp</label>
                                    <input class="input-baru" type="text" name="residue" id="residue" placeholder="Residue" />
                                </div>
                                </div>
                                </div>
                                <div class="form-select validate-input wow fadeInUp" data-validate = "Standard Packaging is required">
                                    <div class="select-list" style="margin-bottom: 0px;">
                                        <select class="dropdown-baru" name="standard_packaging" id="standard_packaging">
                                            <option class="tipeName" value="" selected>Standard Packaging</option>
                                            <option class="tipeName" value="1">Woven Polypropylene Bag</option>
                                            <option class="tipeName" value="2">Paper Bag</option>
                                            <option class="tipeName" value="3">Jumbo Bag</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Tanggal harus diisi">
                                    <label for="tanggal_produksi" class="required">Tanggal</label>
                                    <input class="input-baru" type="text" name="tanggal_produksi" id="tanggal_produksi" placeholder="Tanggal" data-date-format="YYYY-MM-DD" value="{{ Session::get('tanggal_produksi') }}" />
                                </div>
                                <div class="form-row">
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Jumlah Tonase Per Mesin harus diisi">
                                    <label for="jml_tonase_mesin" class="required">Jml Tonase / Mesin</label>
                                    <input class="input-baru" type="text" name="jml_tonase_mesin" id="jml_tonase_mesin" placeholder="Jml Tonase per Mesin" />
                                </div>
                                </div>
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Total Omset harus diisi">
                                    <label for="jml_omset" class="required">Total Omset</label>
                                    <input class="input-baru" type="text" name="jml_omset" id="jml_omset" placeholder="Total Omset" />
                                </div>
                                </div>
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Mean Particle Diameter harus diisi">
                                    <label for="mean_particle_diameter">&nbsp</label>
                                    <input class="input-baru" type="text" name="mean_particle_diameter" id="mean_particle_diameter" placeholder="Mean Particle Diameter" />
                                </div>
                                </div>
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Moisture harus diisi">
                                    <label for="moisture">&nbsp</label>
                                    <input class="input-baru" type="text" name="moisture" id="moisture" placeholder="Moisture" />
                                </div>
                                </div>
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Weight harus diisi">
                                    <input class="input-baru" type="text" name="weight" id="weight" placeholder="Weight" />
                                </div>
                                </div>
                                <div class="form-group">
                                <div class="form-input validate-input wow fadeInDown" data-validate = "mesh harus diisi">
                                    <input class="input-baru" type="text" name="mesh" id="mesh" placeholder="Mesh" />
                                </div>
                                </div>
                                </div>
                            </div>
                          </div>
                        <div class="form-submit wow fadeInUp">
                            <input type="submit" value="Submit" class="submit" id="submit" name="submit" onclick="javascript: form.action='{{ url('/inputOmsetProduksi') }}';" />
                            <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
<!-- <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100 p-t-100 p-b-90">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <form name="form" class="login100-form validate-form flex-sb flex-w" method="post">
          {{ csrf_field() }}
          <span class="login100-form-title p-b-51 wow fadeInDown">
            INPUT OMSET PRODUKSI
          </span>

          <div class="wrap-select100 m-b-16 wow fadeInDown">
            <select class="select100" name="produk" id="produk">
              <option class="tipeName" value="" selected>Produk</option>
              @foreach($produk_data as $produk)
                <option class="tipeName" value="{{ $produk->kode_produk_komputer }}"> {{ $produk->nama_produk }}</option>
              @endforeach
            </select>
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Tanggal Penjualan is required">
            <input class="input100" type="text" name="tanggal_produksi" id="tanggal_produksi" data-date-format="YYYY-MM-DD" placeholder="Tanggal Produksi" value="{{ Session::get('tanggal_produksi') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Jumlah Customer is required">
            <input class="input100" type="text" name="jml_customer" id="jml_customer" placeholder="Jumlah Customer" value="{{ Session::get('jml_customer') }}">
            <span class="focus-input100"></span>
          </div>
          
          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Jumlah Tonase Per Mesin is required">
            <input class="input100" type="text" name="jml_tonase_mesin" id="jml_tonase_mesin" placeholder="Jumlah Tonase Per Mesin" value="{{ Session::get('jml_tonase_mesin') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Jumlah Tonase is required">
            <input class="input100" type="text" name="jml_tonase" id="jml_tonase" placeholder="Jumlah Tonase Produksi" value="{{ Session::get('jml_tonase') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Jumlah Omset is required">
            <input class="input100" type="text" name="jml_omset" id="jml_omset" placeholder="Jumlah Omset Hari Ini" value="{{ Session::get('jml_omset') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Whiteness is required">
            <input class="input100" type="text" name="whiteness" id="whiteness" placeholder="Whiteness" value="{{ Session::get('whiteness') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Residue is required">
            <input class="input100" type="text" name="residue" id="residue" placeholder="Residue on 350 Mesh" value="{{ Session::get('residue') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Mean Particle Diameter is required">
            <input class="input100" type="text" name="mean_particle_diameter" id="mean_particle_diameter" placeholder="Mean Particle Diameter" value="{{ Session::get('mean_particle_diameter') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Moisture is required">
            <input class="input100" type="text" name="moisture" id="moisture" placeholder="Moisture" value="{{ Session::get('moisture') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Mesh is required">
            <input class="input100" type="text" name="mesh" id="mesh" placeholder="Mesh" value="{{ Session::get('mesh') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-select100 m-b-16 wow fadeInDown">
            <select class="select100" name="standard_packaging" id="standard_packaging">
              <option class="tipeName" value="" selected>Standard Packaging</option>
              <option class="tipeName" value="1">Woven Polypropylene Bag</option>
              <option class="tipeName" value="2">Paper Bag</option>
              <option class="tipeName" value="3">Jumbo Bag</option>
            </select>
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Weight is required">
            <input class="input100" type="text" name="weight" id="weight" placeholder="Weight" value="{{ Session::get('weight') }}">
            <span class="focus-input100"></span>
          </div>

          <div class="container-login100-form-btn m-t-17 wow fadeInUp submit1">
            <input type="submit" id="submit1" name="submit1" class="login100-form-btn" onclick="javascript: form.action='{{ url('/inputOmsetProduksi') }}';" value="Save">
          </div>

          <div class="container-login100-form-btn m-t-7 wow fadeInUp resetButton">
            <input type="reset" id="reset" name="reset" class="login100-form-btn" value="Reset">
          </div>
        </form>
      </div>
    </div>
  </div> -->
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/input_omset_produksi') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
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
     $('#tanggal_produksi').datepicker({
      todayBtn:'linked',
      format:'yyyy-mm-dd',
      autoclose:true
     }).datepicker("setDate",'now');
   });
  </script>
@endsection