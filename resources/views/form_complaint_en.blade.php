@extends('layouts.app_en')

@section('title')
<title>COMPLAINT - PT. DWI SELO GIRI MAS</title>
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
    }
  </style>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/form_complaint') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/form_complaint') }}"><span class="">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/form_complaint') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/form_complaint') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="main">

        <div class="container">
            <div class="signup-content">
                <div class="signup-form p-t-50">
                    <span class="login100-form-title wow fadeInDown">Form Complaint</span>
                      @if ($errors->any())
                        <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
                          <ul>
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                          </ul>
                        </div>
                      @endif
                    <form method="post" class="register-form" id="register-form" action="{{ url('/complaintEn') }}" enctype="multipart/form-data">
                      {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group">
                              <input type="hidden" name="custid" id="custid" value="{{ Session::get('custid') }}">

                                <div class="form-input validate-input wow fadeInDown" data-validate = "Name is required">
                                    <label for="name" class="required">Name</label>
                                    <input class="input-baru" type="text" name="name" id="name" placeholder="Name" value="{{ Session::get('name') }}" readonly/>
                                </div>
                                <div class="form-input wow fadeInDown">
                                    <label for="nomor_surat_jalan">Delivery Orders Number</label>
                                    <input class="input-baru" type="text" name="nomor_surat_jalan" id="nomor_surat_jalan" placeholder="Delivery Orders Number" value="{{ Session::get('nomor_surat_jalan') }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-input wow fadeInDown">
                                    <label for="upload_file">Upload File / Image</label>
                                    <input class="input-baru" type="file" name="upload_file" id="upload_file" placeholder="Upload File" />
                                </div>
                                <div class="form-input validate-input wow fadeInDown" data-validate = "Complaint is required">
                                    <label for="complaint" class="required">Complaint</label>
                                    <textarea class="textarea-baru" rows="5" name="complaint" id="complaint" placeholder="Complaint">{{ Session::get('complaint') }}</textarea>
                                </div>
                            </div>
                          </div>
                        <div class="form-submit wow fadeInUp">
                            <input type="submit" value="Submit" class="submit" id="submit" name="submit" />
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
    var baseurl = "{{ url('/en/form_complaint') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
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
@endsection