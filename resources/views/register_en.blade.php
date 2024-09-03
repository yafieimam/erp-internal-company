@extends('layouts.app_en')

@section('title')
<title>REGISTER - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
  <link rel="stylesheet" href="{{asset('app-assets/fonts/material-icon/css/material-design-iconic-font.min.css')}}">
  <link rel="stylesheet" href="{{asset('app-assets/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/util.css')}}">
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/register') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/register') }}"><span class="">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/register') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/register') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="main">

  <div class="container">
    <div class="signup-content">
      <div class="signup-form p-t-50">
        <span class="login100-form-title wow fadeInDown">Sign Up</span>
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
            <div class="form-group" style="width: 50%;">
            <div class="form-input validate-input wow fadeInDown" data-validate = "Email is required / Wrong Email Format">
              <label for="email" class="required">Email</label>
              <input class="input-baru" type="text" name="email" id="email" placeholder="Email" value="{{ Session::get('email') }}" />
            </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <div class="form-input validate-input wow fadeInDown" data-validate = "Password is required">
                <label for="password" class="required">Password</label>
                <input class="input-baru" type="password" name="password" id="password" placeholder="Password" />
              </div>
            </div>
            <div class="form-group">
              <div class="form-input validate-input wow fadeInUp" data-validate = "Confirm Password is required">
                <label for="confirm_pass" class="required">Confirm Password</label>
                <input class="input-baru" type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm Password" />
              </div>
            </div>
          </div>
          <div class="form-submit wow fadeInUp">
            <div class="form-row" style="margin-left: 0px;">Already Have an Account? <a href="{{ url('en/login') }}">&nbspLogin here</a></div>
            <input type="submit" value="Submit" class="submit" id="submit" name="submit" onclick="javascript: form.action='{{ url('/registerPostEn') }}';" />
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
    var baseurl = "{{ url('/en/register') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
  <script src="{{asset('app-assets/js/main_baru.js')}}"></script>
  <script src="{{asset('app-assets/js/main.js')}}"></script>

  <script>
    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>
@endsection