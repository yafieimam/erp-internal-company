@extends('layouts.app_en')

@section('title')
<title>FILL DATA - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
  <link rel="stylesheet" href="{{asset('app-assets/fonts/material-icon/css/material-design-iconic-font.min.css')}}">
  <link rel="stylesheet" href="{{asset('app-assets/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/util.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/select2/select2.min.css')}}">
  <style type="text/css">
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 50px;
    }
  </style>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/fill_data') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/fill_data') }}"><span class="">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/fill_data') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/fill_data') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="main">

  <div class="container">
    <div class="signup-content">
      <div class="signup-form p-t-50">
        <span class="login100-form-title wow fadeInDown">Fill Data</span>
        @if ($errors->any())
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        <form method="post" class="register-form" id="register-form" action="{{ url('/customerAddEn') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-row">
            <div class="form-group">
              <input type="hidden" name="usersid" id="usersid" value="{{ Session::get('userid') }}">
              <input type="hidden" name="id_customer_type" id="id_customer_type" value="{{ Session::get('tipe_user') }}">

              <div class="form-input validate-input wow fadeInDown" data-validate = "Name is required">
                <label for="name" class="required">Name</label>
                <input class="input-baru" type="text" name="name" id="name" placeholder="Name" value="{{ Session::get('name') }}" />
              </div>
              <div class="form-input validate-input wow fadeInDown" data-validate = "Address is required">
                <label for="address" class="required">Address</label>
                <textarea class="textarea-baru" rows="3" name="address" id="address" placeholder="Address">{{ Session::get('address') }}</textarea>
              </div>
              <div class="form-input wow fadeInDown">
                <label for="nik">NIK</label>
                <input class="input-baru" type="text" name="nik" id="nik" placeholder="NIK" value="{{ Session::get('nik') }}" />
              </div>
              <div class="form-input wow fadeInDown" id="input-image-ktp" style="display: none; visibility: visible;">
                <label for="upload_image_ktp">Upload Image KTP</label>
                <input class="input-baru" type="file" name="upload_image_ktp" id="upload_image_ktp" placeholder="Upload Image"/>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <div class="form-input validate-input wow fadeInDown" data-validate = "Phone is required">
                    <label for="phone" class="required">Phone</label>
                    <input class="input-baru" type="text" name="phone" id="phone" placeholder="Phone" value="{{ Session::get('phone') }}" />
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-input validate-input wow fadeInDown" data-validate = "Fax is required">
                    <label for="fax" class="required">Fax</label>
                    <input class="input-baru" type="text" name="fax" id="fax" placeholder="Fax" value="{{ Session::get('fax') }}" />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="form-select validate-input wow fadeInUp" data-validate = "City is required">
                <div class="label-flex">
                  <label for="city" class="required">Address City</label>
                </div>
                <div class="select-list" style="margin-bottom: 0px;">
                  <select class="city" name="city" id="city"></select>
                </div>
              </div>
              <div class="form-input validate-input wow fadeInDown" data-validate = "Warehouse Address is required">
                <label for="wraddress" class="required">Warehouse Address</label>
                <textarea class="textarea-baru" rows="3" name="wraddress" id="wraddress" placeholder="Warehouse Address">{{ Session::get('wraddress') }}</textarea>
              </div>
              <div class="form-radio">
                <div class="label-flex">
                  <label for="npwp_cek">Input NPWP</label>
                </div>
                <div class="form-radio-group">            
                  <div class="form-radio-item" onclick="npwpYes()">
                    <input type="radio" name="input_npwp" id="npwp_yes" value="yes" checked>
                    <label for="npwp_yes">Use NPWP</label>
                    <span class="check"></span>
                  </div>
                  <div class="form-radio-item" onclick="npwpNo()">
                    <input type="radio" name="input_npwp" id="npwp_no" value="no">
                    <label for="npwp_no">Don't Use NPWP</label>
                    <span class="check"></span>
                  </div>
                </div>
              </div>
              <div class="form-input wow fadeInDown" id="input-npwp">
                <label for="npwp" class="required">NPWP</label>
                <input class="input-baru" type="text" name="npwp" id="npwp" placeholder="NPWP" value="{{ Session::get('npwp') }}" />
              </div>
              <div class="form-input wow fadeInDown" id="input-image-npwp">
                <label for="upload_image_npwp">Upload Image NPWP</label>
                <input class="input-baru" type="file" name="upload_image_npwp" id="upload_image_npwp" placeholder="Upload Image" />
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
    var baseurl = "{{ url('/en/fil_data') }}";
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

  <script type="text/javascript">
    function npwpYes(){ 
      $('#input-npwp').show();
      $('#input-image-npwp').show();
    }

    function npwpNo() {
      $('#input-npwp').hide();
      $('#input-image-npwp').hide();
    }

    document.getElementById('input-image-ktp').style.visibility = 'visible';

    $('#nik').keyup(function() {
      if($(this).val().length == 0){
        $('#input-image-ktp').hide();
      }else{
        $('#input-image-ktp').css('visibility','visible'); 
        $('#input-image-ktp').show();
      }
    }).keyup();

    $('.city').select2({
      placeholder: 'Address City',
      allowClear: true,
      ajax: {
        url: '/dropdown_city',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return {
                text: item.name,
                id: item.id_kota
              }
            })
          };
        },
        cache: true
      }
    });
  </script>

  <script type="text/javascript">
    $(".city").on("select2:open", function() {
        $(".select2-search__field").attr("placeholder", "Search City Here...");
    });
    $(".city").on("select2:close", function() {
        $(".select2-search__field").attr("placeholder", null);
    });
  </script>
@endsection