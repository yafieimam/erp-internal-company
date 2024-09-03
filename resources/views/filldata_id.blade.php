@extends('layouts.app_id')

@section('title')
<title>ISI DATA - PT. DWI SELO GIRI MAS</title>
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
                  <a href="{{ url('en/fill_data') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/fill_data') }}"><span class="active">IN</span></a>
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
        <span class="login100-form-title wow fadeInDown">Isi Data</span>
        @if ($errors->any())
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        <form method="post" class="register-form" id="register-form" action="{{ url('/customerAddId') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-row">
            <div class="form-group">
              <input type="hidden" name="usersid" id="usersid" value="{{ Session::get('userid') }}">
              <input type="hidden" name="id_customer_type" id="id_customer_type" value="{{ Session::get('tipe_user') }}">

              <div class="form-input validate-input wow fadeInDown" data-validate = "Nama harus diisi">
                <label for="name" class="required">Nama</label>
                <input class="input-baru" type="text" name="name" id="name" placeholder="Nama" value="{{ Session::get('name') }}" />
              </div>
              <div class="form-input validate-input wow fadeInDown" data-validate = "Alamat harus diisi">
                <label for="address" class="required">Alamat</label>
                <textarea class="textarea-baru" rows="3" name="address" id="address" placeholder="Alamat">{{ Session::get('address') }}</textarea>
              </div>
              <div class="form-input wow fadeInDown">
                <label for="nik">NIK</label>
                <input class="input-baru" type="text" name="nik" id="nik" placeholder="NIK" value="{{ Session::get('nik') }}" />
              </div>
              <div class="form-input wow fadeInDown" id="input-image-ktp" style="display: none; visibility: visible;">
                <label for="upload_image_ktp">Unggah Foto KTP</label>
                <input class="input-baru" type="file" name="upload_image_ktp" id="upload_image_ktp" placeholder="Unggah Foto"/>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <div class="form-input validate-input wow fadeInDown" data-validate = "Telepon harus diisi">
                    <label for="phone" class="required">Telepon</label>
                    <input class="input-baru" type="text" name="phone" id="phone" placeholder="Telepon" value="{{ Session::get('phone') }}" />
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-input validate-input wow fadeInDown" data-validate = "Faks harus diisi">
                    <label for="fax" class="required">Faks</label>
                    <input class="input-baru" type="text" name="fax" id="fax" placeholder="Faks" value="{{ Session::get('fax') }}" />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="form-select validate-input wow fadeInUp" data-validate = "Alamat Kota harus diisi">
                <div class="label-flex">
                  <label for="city" class="required">Alamat Kota</label>
                </div>
                <div class="select-list" style="margin-bottom: 0px;">
                  <select class="city" name="city" id="city"></select>
                </div>
              </div>
              <div class="form-input validate-input wow fadeInDown" data-validate = "Alamat Warehouse harus diisi">
                <label for="wraddress" class="required">Alamat Warehouse</label>
                <textarea class="textarea-baru" rows="3" name="wraddress" id="wraddress" placeholder="Alamat Warehouse">{{ Session::get('wraddress') }}</textarea>
              </div>
              <div class="form-radio">
                <div class="label-flex">
                  <label for="npwp_cek">Input NPWP</label>
                </div>
                <div class="form-radio-group">            
                  <div class="form-radio-item" onclick="npwpYes()">
                    <input type="radio" name="input_npwp" id="npwp_yes" value="yes" checked>
                    <label for="npwp_yes">Pakai NPWP</label>
                    <span class="check"></span>
                  </div>
                  <div class="form-radio-item" onclick="npwpNo()">
                    <input type="radio" name="input_npwp" id="npwp_no" value="no">
                    <label for="npwp_no">Tidak Pakai NPWP</label>
                    <span class="check"></span>
                  </div>
                </div>
              </div>
              <div class="form-input wow fadeInDown" id="input-npwp">
                <label for="npwp" class="required">NPWP</label>
                <input class="input-baru" type="text" name="npwp" id="npwp" placeholder="NPWP" value="{{ Session::get('npwp') }}" />
              </div>
              <div class="form-input wow fadeInDown" id="input-image-npwp">
                <label for="upload_image_npwp">Unggah Foto NPWP</label>
                <input class="input-baru" type="file" name="upload_image_npwp" id="upload_image_npwp" placeholder="Unggah Foto" />
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
    var baseurl = "{{ url('/id/fill_data') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
  <script src="{{asset('app-assets/vendor/select2/select2.min.js')}}"></script>
  <script src="{{asset('app-assets/js/main_baru.js')}}"></script>
  <script src="{{asset('app-assets/js/main.js')}}"></script>

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
      placeholder: 'Kota',
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

  <script>
    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>

  <script type="text/javascript">
    $(".city").on("select2:open", function() {
        $(".select2-search__field").attr("placeholder", "Cari Kota Disini...");
    });
    $(".city").on("select2:close", function() {
        $(".select2-search__field").attr("placeholder", null);
    });
  </script>
@endsection