@extends('layouts.app_id')

@section('title')
<title>KARIR - PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/career') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/career') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/career') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/career') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="clear"></div>
<div class="yellows_headBottoms"></div>
<div class="clear"></div>
<section class="cover page-career">
    <div class="container cont-fcs produk">
        <div class="cover-image">
            <!-- <img class="w-100 d-block" src="/asset/images/ill-career.jpg" alt=""> -->
            <div class="centered wow fadeInUp">
              <p>
                Karir              </p>
            </div>
        </div>
        <div class="row pt-3">
          <div class="col-40">
            <div class="breadcrumb wow fadeInUp">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('id') }}">BERANDA</a></li>
                  <li class="breadcrumb-item"><a href="{{ url('id/career') }}">KARIR</a></li>
                </ol>
              </nav>
            </div>
          </div>
          <div class="col-20">
            <div class="back text-right wow fadeInUp">
              <a href="{{ url('id') }}">KEMBALI</a>
            </div>
          </div>
        </div>
        <hr class="cover">
    </div>
</section>

<section class="career-sec-1">
  <div class="prelative container">
        <div class="row wow fadeInUp">
      <div class="col-md-60">
        <div class="text1 mx-auto text-center pt-5">
          <p>MASA DEPAN YANG LEBIH BAIK</p>
        </div>
      </div>
      <div class="col-md-60">
        <div class="text2 mx-auto text-center pt-4">
          <p>Apakah Anda Siap Menantang?</p>
        </div>
      </div>
      <div class="col-md-60">
        <div class="text3 mx-auto text-center pt-4">
          <p>Di PT. Dwi Selo Giri Mas, Anda akan menjadi bagian dari perusahaan nasional yang berkembang dengan budaya yang memungkinkan Anda mengeluarkan yang terbaik dari diri Anda.</p>
        </div>
      </div>
      <div class="col-md-60">
        <div class="text4 mx-auto text-center pt-4">
          <p>PT. Dwi Selo Giri Mas akan menawarkan Anda pengalaman kerja yang beragam secara budaya dan kolaboratif. Anda akan bekerja dengan sangat erat dengan beberapa orang terpintar di industri kimia Indonesia dari seluruh negara. Anda akan diberi kesempatan untuk belajar, mengeksplorasi, dan memenuhi tujuan karier Anda. Beri tahu kami bagaimana Anda dapat menciptakan nilai bagi perusahaan kami.</p>
        </div>
      </div>
      <div class="col-md-60">
        <div class="text5 mx-auto text-center pt-4">
          <p>Kirim resume lengkap Anda ke: <br> <a href="#">info@dwiselogirimas.com</a> </p>
        </div>
      </div>
    </div>
        <div class="pb-5"> </div>
    <div class="pb-5"></div>
  </div>
</section>

<section class="form-resume">
  <div class="prelative container">
    <div class="py-5"></div>
    <!-- <div class="py-5"></div> -->
    <div class="title wow fadeInUp">
      <p>Send Your Complete Resume Form</p>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger" style="margin-right: 30%; margin-top: 20px;">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form enctype="multipart/form-data" class="py-5 wow fadeInUp form-" id="yw0" action="{{ url('id/form-career/process') }}" method="post">      
      {{ csrf_field() }} 
      <div class="row default">
        <div class="col-md-15 col-sm-15">
          <div class="form-group">
            <!-- <label for="exampleInputName">Nama</label> -->
            <input class="form-control" placeholder="Nama" name="form_career_name" id="form_career_name" type="text" value="{{ old('form_career_name') }}">        
          </div>
        </div>
        <div class="col-md-15 col-sm-15">
          
          <div class="form-group">
            <!-- <label for="exampleInputCompany">Perusahaan</label> -->
            <input class="form-control" placeholder="Posisi" name="form_career_position" id="form_career_position" type="text" value="{{ old('form_career_position') }}">        
          </div>
        </div>

        <div class="col-md-15 col-sm-15">
          <div class="form-group">
            <!-- <label for="exampleInputEmail">Email</label> -->
            <input class="form-control" placeholder="Email" name="form_career_email" id="form_career_email" type="text" value="{{ old('form_career_email') }}">        
          </div>
        </div>
        <div class="col-md-15 col-sm-15">
          <div class="form-group">
          <!-- <label for="exampleInputPhone">Telepon</label> -->
          <input class="form-control" placeholder="Telepon" name="form_career_phone" id="form_career_phone" type="text" value="{{ old('form_career_phone') }}">        
        </div>
        </div>
      </div>

      <!-- <div class="row default no-gutters">
      <div class="col-md-60">
        <div class="form-group">
      </div>
      <div class="clear"></div>

      <div class="row default text-area">
        <div class="col-md-60">
          <textarea name="" id="" cols="" rows="" placeholder="Notes / Messages"></textarea>
        </div>
      </div> -->


      <div class="row default wow fadeInUp">
        <div class="col-md-60">
          <div class="submit resume">
            <a style="display:block;background:transparent;" href="#">Lampirkan Resume Anda</a>
            <input id="ytform_career_file" type="hidden" value="" name="form_career_file"><input required="required" placeholder="Lampirkan Resume Anda" name="form_career_file" id="form_career_file" type="file">            <p>Batas Maksimal Ukuran File adalah 2 Megabytes</p>
          </div>
        </div>
      </div>

      <div class="row default wow fadeInUp">
        <div class="col-md-60">
          <div class="py-3"></div>
          <div class="mx-auto d-block picts_captcha">
            @if(env('GOOGLE_RECAPTCHA_KEY'))
              <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
            @endif
          </div>
          <div class="submit">
            <button type="submit">KIRIM</button>
          </div>
        </div>
      </div>
      <!-- <div class="col-md-60">
        <div class="row default">
          <div class="col-md-60 col-sm-60">
          </div>
          <div class="col-md-60 col-sm-60 hide-pc">
            <div class="text-right submit">
            <a href="#">Submit</a>
            </div>
          </div>
          <div class="col-md-60 hide-mobile">
            <div class="text-center submit">
            <a href="#">Submit</a>
            </div>
          </div>
                        
          <div class="py-5 hide-mobile"></div>
          <div class="py-5 hide-mobile"></div>
        </div>

      </div> -->
      </form></div>
    </form>  
  <div class="pb-5"></div>
</section>
<style type="text/css">
  section.form-resume form .submit button {
    background: #d9d9d9;
    border-radius: 20px;
    cursor: pointer;
    border: 0;
    padding: 10px 70px;
    color: #434343;
    font-weight: 500;
    font-size: 18px;
    text-decoration: none;
}
.picts_captcha .g-recaptcha > div{
    display: block;
    margin: 0 auto;
  }
   input[type='file'] {
    background: #d9d9d9;
    border-radius: 20px;
    cursor: pointer;
    border: 0;
    padding: 10px 40px;
    color: #434343;
    font-weight: 500;
    font-size: 12px;
    text-decoration: none;
  }
</style>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/id/career') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>

<script>
  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>
@endsection