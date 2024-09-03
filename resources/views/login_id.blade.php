@extends('layouts.app_id')

@section('title')
<title>MASUK - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/animate/animate.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/css-hamburgers/hamburgers.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/animsition/css/animsition.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/select2/select2.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/util.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/main.css')}}">
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/login') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/login') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/login') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/login') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100 p-t-20">
        <form class="login100-form validate-form flex-sb flex-w" action="{{ url('/loginPostId') }}" method="post">
            {{ csrf_field() }}
          <span class="login100-form-title p-b-51 wow fadeInDown">
            Masuk
          </span>

          
          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Email is required">
            <input class="input100" type="text" name="email" id="email" placeholder="Email" value="{{ Session::get('email') }}">
            <span class="focus-input100"></span>
          </div>
          
          
          <div class="wrap-input100 validate-input m-b-16 wow fadeInDown" data-validate = "Password is required">
            <input class="input100" type="password" name="password" id="password" placeholder="Password">
            <span class="focus-input100"></span>
          </div>

          <div class="container-login100-form-btn m-t-17 wow fadeInUp">
            <input type="submit" name="submit"  class="login100-form-btn" value="Masuk">
          </div>
        </form>
        <div class="flex-sb-m w-full p-t-3 p-b-24 wow fadeInUp">
            <div class="contact100-form-checkbox">
              Belum Memiliki Akun? <a href="{{ url('id/register') }}" class="txt1">Daftar Disini</a>
            </div>
          </div>
      </div>
    </div>
  </div>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/id/login') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
  <script src="{{asset('app-assets/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('app-assets/js/main.js')}}"></script>

  <script>
    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>
@endsection