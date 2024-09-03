@extends('layouts.app_en')

@section('title')
<title>PROFILE - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link href="{{asset('app-assets/css/style_profile.css')}}" rel="stylesheet" />
<style type="text/css">
  #btn-edit-profile {
    float: right !important;
  }
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-lg-"] {
      flex: none !important; 
      max-width: 100% !important;
    }
    [class*="col-xl-"] {
      flex: none !important; 
      max-width: 100% !important;
    }
    .spad {
      padding: 125px 0 0;
      text-align: center;
    }
    #btn-edit-profile {
      float: none !important;
      width: 100%;
      margin-bottom: 20px;
    }
    .hero-text {
      margin-bottom: 10px;
    }
    .hero-info h2 {
      font-size: 30px !important;
    }
  }
</style>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/profile') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/profile') }}"><span class="">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/profile') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/profile') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<section class="hero-section spad">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-10 offset-xl-1">
          <div class="row">
            <div class="col-lg-6">
              <a href="{{ url('en/update/profile') }}" class="btn btn-success mt-3" id="btn-edit-profile">Edit Profile</a>
            </div>
            <div class="col-lg-6">
              <div class="hero-text">
                <h2>{{ $customer->custname }}</h2>
              </div>
              <div class="hero-info">
                <h2>General Info</h2>
                <ul>
                  <li align="justify"><span>Customer ID </span>{{ $customer->custid }}</li>
                  <li align="justify"><span>Email </span>{{ $customer->email }}</li>
                  <li align="justify"><span>Customer Type </span>{{ $customer->tipe_customer }}</li>
                  <li align="justify"><span>Address </span>{{ $customer->address }}</li>
                  <li align="justify"><span>City </span>{{ $customer->city }}</li>
                  <li align="justify"><span>Phone </span>{{ $customer->phone }}</li>
                  <li align="justify"><span>NPWP </span>{{ $customer->npwp }}</li>
                  <li align="justify"><span>Warehouse Address </span>{{ $customer->wraddress }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('en/profile') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection