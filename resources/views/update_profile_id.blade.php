@extends('layouts.app_id')

@section('title')
<title>EDIT PROFIL - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
  <link rel="stylesheet" href="{{asset('app-assets/fonts/material-icon/css/material-design-iconic-font.min.css')}}">
  <link rel="stylesheet" href="{{asset('app-assets/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/util.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/select2/select2.min.css')}}">
  <style type="text/css">
    @media only screen and (max-width: 768px) {
      /* For mobile phones: */
      [class*="col-sm-"] {
        flex: none !important; 
        max-width: 100% !important;
        padding-left: 0;
        padding-right: 0;
        margin-bottom: 10px;
      }
      .register-form {
        padding: 50px 0 0;
      }
      .select2-container .select2-selection--single, .select2-search--dropdown, .select2-search--dropdown {
        width: 100%;
      }
      .select2-container--default .select2-selection--single .select2-selection__arrow {
        margin-right: 0px;
      }
      .list-address {
        padding-bottom: 50px !important;
      }
    }
  </style>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/update/profile') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/update/profile') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/update/profile') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/update/profile') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="main">

  <div class="container">
    <div class="signup-content">
      <div class="signup-form p-t-50">
        <span class="login100-form-title wow fadeInDown">Edit Profil</span>
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
              <input type="hidden" name="id_customer_type" id="id_customer_type" value="{{ Session::get('tipe_user') }}">
              <div class="form-input validate-input wow fadeInDown" data-validate = "Nama Harus Diisi">
                <label for="name" class="required">Nama</label>
                <input class="input-baru" type="text" name="name" id="name" placeholder="Nama" value="{{ $customer->custname }}" />
              </div>
              <div class="form-input validate-input wow fadeInDown" data-validate = "Alamat Harus Diisi">
                <label for="address" class="required">Alamat</label>
                <textarea class="textarea-baru" rows="3" name="address" id="address" placeholder="Alamat">{{ $customer->address }}</textarea>
              </div>
              <div class="form-select validate-input wow fadeInUp" data-validate = "Kota Harus Diisi">
                <div class="label-flex">
                  <label for="city" class="required">Kota</label>
                </div>
                <div class="select-list" style="margin-bottom: 0px;">
                  <select class="city" name="city" id="city">
                    <option value="{{ $customer->kode_city }}" selected>{{ $customer->city }}</option>
                  </select>
                </div>
              </div>
              <?php
              if(Session::get('tipe_user') == 4 || Session::get('tipe_user') == 5){
                ?>
                <div class="form-input validate-input wow fadeInDown" data-validate = "NPWP Harus Diisi">
                  <label for="npwp" class="required">NPWP</label>
                  <input class="input-baru" type="text" name="npwp" id="npwp" placeholder="NPWP" value="{{ $customer->npwp }}" />
                </div>
                <?php
              }
              ?>
            </div>
            <div class="form-group">
              <div class="form-input validate-input wow fadeInDown" data-validate = "Email Harus Diisi / Format Email Salah">
                <label for="email" class="required">Email</label>
                <input class="input-baru" type="text" name="email" id="email" placeholder="Email" value="{{ $customer->email }}" />
              </div>
              <div class="form-input validate-input wow fadeInDown" data-validate = "Alamat Warehouse Harus Diisi">
                <label for="wraddress" class="required">Alamat Warehouse</label>
                <textarea class="textarea-baru" rows="3" name="wraddress" id="wraddress" placeholder="Alamat Warehouse">{{ $customer->wraddress }}</textarea>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <div class="form-input validate-input wow fadeInDown" data-validate = "Telepon Harus Diisi">
                    <label for="phone" class="required">Telepon</label>
                    <input class="input-baru" type="text" name="phone" id="phone" placeholder="Telepon" value="{{ $customer->phone }}" />
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-input validate-input wow fadeInDown" data-validate = "Faks Harus Diisi">
                    <label for="fax" class="required">Faks</label>
                    <input class="input-baru" type="text" name="fax" id="fax" placeholder="Faks" value="{{ $customer->fax }}" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-submit wow fadeInUp">
            <input type="submit" value="Submit" class="submit" id="submit" name="submit" onclick="javascript: form.action='{{ url('/updateProfileProcessId') }}';" />
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
    var baseurl = "{{ url('/id/update/profile') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
  <script src="{{asset('app-assets/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('app-assets/js/main_baru.js')}}"></script>
  <script src="{{asset('app-assets/js/main.js')}}"></script>
@endsection