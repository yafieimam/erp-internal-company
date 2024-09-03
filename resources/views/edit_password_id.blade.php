@extends('layouts.app_id')

@section('title')
<title>EDIT PASSWORD - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
  <link rel="stylesheet" href="{{asset('app-assets/fonts/material-icon/css/material-design-iconic-font.min.css')}}">
  <link rel="stylesheet" href="{{asset('app-assets/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/util.css')}}">
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
    }
  </style>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/edit_password') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/edit_password') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/edit_password') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/edit_password') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="main">

  <div class="container">
    <div class="signup-content">
      <div class="signup-form p-t-50">
        <span class="login100-form-title wow fadeInDown">Edit Password</span>
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
            <div class="form-group" style="margin: auto;">
              <div class="form-input validate-input wow fadeInDown" data-validate = "Old Password is required">
                <label for="old_password" class="required">Password Lama</label>
                <input class="input-baru" type="password" name="old_password" id="old_password" placeholder="Old Password" />
              </div>
              <div class="form-input validate-input wow fadeInDown" data-validate = "New Password is required">
                <label for="new_password" class="required">Password Baru</label>
                <input class="input-baru" type="password" name="new_password" id="new_password" placeholder="New Password" />
              </div>
              <div class="form-input validate-input wow fadeInUp" data-validate = "Confirm New Password is required">
                <label for="confirm_pass" class="required">Konfirmasi Password Baru</label>
                <input class="input-baru" type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm New Password" />
              </div>
              <input type="submit" value="Submit" class="submit" id="submit" name="submit" onclick="javascript: form.action='{{ url('/editPasswordId') }}';" style="float: right; margin: 0;"/>
            </div>
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
    var baseurl = "{{ url('/id/edit_password') }}";
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