@extends('layouts.app_id')

@section('title')
<title>KONTAK - PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/contact') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/contact') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/contact') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/contact') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="clear"></div>
<div class="yellows_headBottoms"></div>
<div class="clear"></div>
<section class="cover page-contact">
    <div class="container cont-fcs produk">
        <div class="cover-image">
            <!-- <img class="w-100 d-block" src="/asset/images/ill-contacts.jpg" alt=""> -->
            <div class="centered wow fadeInUp">
              <p>
                KONTAK              </p>
            </div>
        </div>
        <div class="row pt-3">
          <div class="col-40">
            <div class="breadcrumb wow fadeInUp">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('id') }}">BERANDA</a></li>
                  <li class="breadcrumb-item"><a href="{{ url('id/contact') }}">KONTAK</a></li>
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

<section class="contact-sec-1">
  <div class="prelative container">
          <div class="row pt-5 wow fadeInUp">
      <div class="col-md-60">
        <div class="text1 mx-auto d-block text-center pb-4">
          <p>BUTUH INFORMASI LEBIH?</p>
        </div>
      </div>
      <div class="col-md-60">
        <div class="text2 mx-auto d-block text-center pb-4">
          <p>Kami Di Sini Untuk Membantu Anda</p>
        </div>
      </div>
      <div class="col-md-60">
        <div class="text3 mx-auto d-block text-center pb-4">
          <p>Hubungi kami untuk setiap pertanyaan atau informasi terkait produk.</p>
        </div>
      </div>
      <div class="col-md-60">
        <div class="text4 mx-auto d-block text-center pb-5">
          <p>Biarkan tim layanan pelanggan dan spesialis produk kami mempelajari permintaan Anda dan kemudian memberikan jawaban untuk semua pertanyaan Anda tentang produk Kalsium Karbonat yang tepat. Kami mengklaim diri sebagai yang terbaik dalam memahami kebutuhan dan spesifikasi Anda pada aplikasi produk kalsium karbonat yang efisien untuk operasional bisnis Anda.</p>
        </div>
      </div>
    </div>
        <div class="row wow fadeInUp">
      <div class="col-md-60">
        <div class="factory mx-auto d-block text-center pb-4 pt-3 ">
                    <p>Pabrik & Kantor di Indonesia</p>
                  </div>
        <div class="alamat mx-auto d-block text-center pb-4 ">
          <div class="title">
            <p>SIDOARJO</p>
          </div>
          <div class="isi">
            <p>Jl. Raya Tebel No.50-A, Tebel <br>
              Gedangan, Sidoarjo, Jawa Timur 61254 - INDONESIA</p>
          </div>
          <div class="view">
              <a href="https://goo.gl/maps/4FudkGBXZCGxCsqF8">
                        <p>(Lihat di Google Map)</p>
              </a>
                    </div>
        </div>
        <div class="telepon mx-auto d-block text-center pb-4 ">
          <div class="title">
                        <p>Telepon.</p>
                      </div>
          <div class="nomor">
            <p>+62 31 8913030</p>
          </div>
        </div>
        <div class="telepon mx-auto d-block text-center pb-4 ">
          <div class="title">
                        <p>Sales Whatsapp.</p>
                      </div>
          <div class="nomor">
            <!-- <a href="https://wa.me/628883205551"> -->
              <p><span><a href="https://wa.me/628883205551">0888-3205-551</a></span> dan <span><a href="https://wa.me/628113121888">0811-3121-888</a></span></p>
            <!-- </a> -->
          </div>
        </div>
        <!-- <div class="email mx-auto d-block text-center pb-4 ">
          <div class="title">
            <p>Email.</p>
          </div>
          <div class="nomor">
            <p><a href="mailto:info@dwiselogirimas.com">info@dwiselogirimas.com</a></p>
          </div>
        </div> -->
      </div>
    </div>
    <div class="row pt-5 wow fadeInUp">
      <div class="col-md-60">
        <div class="factory mx-auto d-block text-center pb-4 pt-3 ">
                    <p>Pabrik & Kantor di Indonesia</p>
                  </div>
        <div class="alamat mx-auto d-block text-center pb-4 ">
          <div class="title">
            <p>Jakarta Barat</p>
          </div>
          <div class="isi">
            <p>Jl Daan Mogot <br>
            Komplek Pertokoan Green Garden Blok A7 / 16</p>
          </div>
          <!-- <div class="view">
                        <p>(Lihat di Google Map)</p>
                    </div> -->
        </div>
        <div class="telepon mx-auto d-block text-center pb-4 ">
          <div class="title">
                        <p>Telepon.</p>
                      </div>
          <div class="nomor">
            <p>021-5809710</p>
          </div>
        </div>
        <div class="telepon mx-auto d-block text-center pb-4 ">
          <div class="title">
                        <p>Fax.</p>
                      </div>
          <div class="nomor">
            <p>021-5809710</p>
          </div>
        </div>
        <!-- <div class="email mx-auto d-block text-center pb-4 ">
          <div class="title">
            <p>Email.</p>
          </div>
          <div class="nomor">
            <p><a href="mailto:info@dwiselogirimas.com">info@dwiselogirimas.com</a></p>
          </div>
        </div> -->
      </div>
    </div>

  </div>
  <div class="pb-5"></div>
  <div class="pb-2"></div>
</section>

<section class="form-contact">
  <div class="prelative container">
    <div class="py-5"></div>
    <!-- <div class="py-5"></div> -->
    <div class="title wow fadeInUp">
      <p>Form Kontak Online</p>
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

    <form enctype="multipart/form-data" class="py-5 form-" id="yw0" action="{{ url('id/form-contact/process') }}" method="post">   
      {{ csrf_field() }}     
          <div class="row default">
        <div class="col-md-15 col-sm-15">
          <div class="form-group">
            <!-- <label for="exampleInputName">Nama</label> --> 
            <input class="form-control" placeholder="Nama" name="form_contact_name" id="form_contact_name" type="text" value="{{ old('form_contact_name') }}">   
          </div>
        </div>
        <div class="col-md-15 col-sm-15">
          
          <div class="form-group">
            <!-- <label for="exampleInputCompany">Perusahaan</label> -->
            <input class="form-control" placeholder="Perusahaan" name="form_contact_company" id="form_contact_company" type="text" value="{{ old('form_contact_company') }}">              
            </div>
        </div>

        <div class="col-md-15 col-sm-15">
          <div class="form-group">
            <!-- <label for="exampleInputEmail">Email</label> -->
            <input class="form-control" placeholder="Email" name="form_contact_email" id="form_contact_email" type="text" value="{{ old('form_contact_email') }}">          </div>
        </div>
        <div class="col-md-15 col-sm-15">
          <div class="form-group">
          <!-- <label for="exampleInputPhone">Telepon</label> -->
          <input class="form-control" placeholder="Telepon" name="form_contact_phone" id="form_contact_phone" type="text" value="{{ old('form_contact_phone') }}">          </div>
        </div>
      </div>

      <div class="row default no-gutters">
      <div class="col-md-60">
        <div class="form-group">
      </div>
      <div class="clear"></div>

      <div class="row default text-area">
        <div class="col-md-60">
          <textarea class="form-control" placeholder="Catatan / Pesan" name="form_contact_message" id="form_contact_message">{{ old('form_contact_message') }}</textarea>        </div>
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
            <button type="submit" class="">Kirim</button>
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
      </div>
    </div></form>  </div>
  <div class="pb-5"></div>
</section>

<style type="text/css">
  section.contact-sec-1 .email .nomor p a{
    font-size: 15px;
    font-weight: 500;
    color: #fff;
  }

  section.form-contact form .submit button{
    background: #d9d9d9;
    border-radius: 20px;
    cursor: pointer;
    border: 0;
    padding: 10px 70px;
    color: #434343;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 18px;
    text-decoration: none;
  }
  .picts_captcha .g-recaptcha > div{
    display: block;
    margin: 0 auto;
  }
</style>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/id_contact') }}";
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