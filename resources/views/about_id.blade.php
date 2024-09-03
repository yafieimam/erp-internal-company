@extends('layouts.app_id')

@section('title')
<title>TENTANG KAMI - PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/about') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/about') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/about') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/about') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="clear"></div>
<div class="yellows_headBottoms"></div>
<div class="clear"></div>
<section class="cover-about page-about">
  <div class="container cont-fcs produk">
    <div class="cover-image">
        <!-- <img class="w-100 d-block" src="/asset/images/ill-about.jpg" alt=""> -->
        <div class="centered wow fadeInUp">
          <p>
            TENTANG KAMI          </p>
        </div>
    </div>
    <div class="row pt-3">
      <div class="col-40">
        <div class="breadcrumb wow fadeInUp">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('id') }}">BERANDA</a></li>
              <li class="breadcrumb-item"><a href="{{ url('id/about') }}">TENTANG KAMI</a></li>
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
  <div class="prelative container pt-5 pt-1">
    <div class="row">
      <div class="col-md-30">
        <div class="image wow fadeInUp">
          <img class="img img-fluid" src="{{asset('app-assets/asset/images/design-1-about_03.jpg')}}" alt="">
        </div>
      </div>
      <div class="col-md-30">
        <div class="caption wow fadeInUp">
                    <div class="text1 pt-2 pb-4">
            <p>SIAPA KITA</p>
          </div>
          <div class="text2 pb-4">
            <p>PT. DWI SELO GIRI MAS PRODUSEN KALSIUM KARBONAT</p>
          </div>
          <div class="text3">
            <p>Dwi Selo Giri Mas (DSGM) adalah produsen - pemasok Kalsium Karbonat yang mapan dari Sidoarjo - Surabaya, Jawa Timur. DSGM memiliki rekam jejak yang terbukti sebagai produsen kalsium karbonat sejak tahun 1988. Pasar kami untuk pelanggan tersebar di seluruh Jawa (Jakarta, Surabaya, Gresik, Mojokerto, dan banyak lagi), dan bahkan hingga ke pulau-pulau seperti Kalimantan dan Sulawesi. Kami memiliki perpaduan yang inovatif dan canggih dari mesin-mesin canggih plus tim yang berkualitas dan berpengalaman yang melayani Anda dalam pikiran. DSGM berkomitmen untuk menyediakan pelanggan dengan produk-produk berkualitas yang dikirimkan tepat waktu, fokus dan komitmen kami selalu pada kesuksesan Anda.</p>
          </div>
                  </div>
      </div>
    </div>
  </div>
</section>

<section class="about-sec-1">
  <div class="pt-5 hide-pc"></div>
  <div class="prelative container py-5">
    <div class="row py-3 wow fadeInDown">
      <div class="col-md-30">
        <div class="box-content">
                    <div class="what pb-4">
            <p>Mengapa kita</p>
          </div>
          <div class="title">
            <p>Pengalaman dan reputasi kami adalah salah satu yang terbaik di bidangnya.</p>
          </div>
          <div class="content pt-4">
            <p class="mb-4">Dwi Selo Giri Mas (DSGM) telah merancang fleksibilitas dalam proses kami, ini memungkinkan DSGM untuk memenuhi semua kebutuhan khusus pelanggan untuk menghasilkan bubuk kalsium karbonat yang optimal sesuai kebutuhan. Menjadi terkenal karena reputasi kita sejak tahun 1988 membantu kita untuk sumber bahan baku terbaik dan cepat menanggapi kebutuhan pelanggan. Investasi berjalan yang efisien pada pembuatan yang berkualitas memungkinkan kami untuk mengirimkan produk-produk kalsium karbonat dengan harga yang dapat dikalahkan.</p>
            <p class="mb-4">Kunci kesuksesan produk kami terletak pada kontrol kualitas paha Anda dan protokol aliran produksi kami yang ketat. Jangan ragu untuk menanyakan kebutuhan Anda akan produk kalsium karbonat.</p>
            <p class="mb-4">Kemitraan yang solid kami terbentuk dan diperkuat karena nilai-nilai, tujuan, dan visi yang dijalani karyawan kami. Tujuan utama kami adalah menjaga bisnis tetap berjalan dan itu didukung oleh nilai-nilai kami untuk menjadi lebih baik setiap hari, mencintai sesamanya, melakukan apa yang diperlukan dan memiliki masa depan Anda. Kombinasi tujuan dan nilai-nilai kami dan kualitas produk kami, menjadikan kami pemasok yang kuat; pemasok pilihan Anda dan di mana Anda dapat membeli bubuk kalsium karbonat.</p>
          </div>
          <div class="link pt-2">
            <a href="{{ url('id/products') }}">
              <p>LIHAT PRODUK KARBONAT KALSIUM KAMI</p>
            </a>
          </div>
                  </div>
      </div>
      <div class="col-md-30">
        <img class="img img-fluid" src="{{asset('app-assets/asset/images/design-1-about_07.jpg')}}" alt="">
      </div>
    </div>
  </div>
</section>
<div class="pb-5"></div>
<div class="pb-4 hide-pc"></div>
<div class="pb-5 hide-pc"></div>

<section class="about-sec-2">
  <div class="row no-gutters wow fadeInUp">
    <div class="col-md-60">
      <img src="{{asset('app-assets/asset/images/fulls_about_comp.jpg')}}" alt="" class="img img-fluid w-100">
    </div>
  </div>
  <div class="row no-gutters wow fadeInUp">
    <div class="col-md-30">
      <div class="back-left py-5">
        <div class="box-vm py-5">
          <div class="vision-mission">
                        <p>Visi</p>
                      </div>
          <div class="caption pt-4">
                        <p>Untuk menjadi produsen produk Kalsium Karbonat terkemuka dan terkemuka di Indonesia dan memperluas jangkauan pasar kami ke dunia.</p>
                        <div class="d-none d-sm-block">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-30">
      <div class="back-right py-5">
        <div class="box-vm py-5">
          <div class="vision-mission">
                        <p>Misi</p>
                      </div>
          <div class="caption pt-4">
                        <p>Memenuhi semua kebutuhan pelanggan kami, dan memberikannya dengan nilai tambah untuk menciptakan hubungan obligasi jangka panjang.</p>
            <p>Untuk menghadirkan pengalaman penjualan dan layanan yang lebih baik yang membuat kami berbeda dari yang lain.</p>
                      </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style type="text/css">
  section.about-sec-1 .box-content .content p{
    max-width: 450px;
    text-align: justify;
  }
</style>
<section class="home-sec-4">
  <div class="prelative container pt-5">
    <div class="row">
      <div class="col-md-40">
        <div class="box-section pt-5 wow fadeInDown">
                    <div class="title pt-5">
            <p>Kami ingin mengenal Anda...</p>
          </div>
          <div class="subtitle pt-4 pb-5">
            <p>Staf perwakilan kami akan menanggapi Anda untuk mendiskusikan begitu banyak probabilitas dan solusi yang mungkin Anda perlukan.</p>
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
                    
          <form enctype="multipart/form-data" class="py-5 form-" id="yw0" action="{{ url('id/form-contact/process') }}" method="post">  {{ csrf_field() }}  
                        <div class="row default">
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Nama" name="form_contact_name" id="form_contact_name" type="text" value="{{ old('form_contact_name') }}">    
                </div>
              </div>
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Perusahaan" name="form_contact_company" id="form_contact_company" type="text" value="{{ old('form_contact_company') }}">               
                </div>
              </div>
            </div>

            <div class="row default">
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Email" name="form_contact_email" id="form_contact_email" type="text" value="{{ old('form_contact_email') }}">               
                </div>
              </div>
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                <input class="form-control" placeholder="Telepon" name="form_contact_phone" id="form_contact_phone" type="text" value="{{ old('form_contact_phone') }}">               
              </div>
              </div>
            </div>

            <div class="row default">
              <div class="col-md-54 col-sm-54">
                <div class="form-group">
                  <input class="form-control" placeholder="Catatan / Pesan" name="form_contact_message" id="form_contact_message" type="text" style="width: 100%;" value="{{ old('form_contact_message') }}">               
                </div>
              </div>
            </div>

            <div class="row default">
            <div class="col-md-60">
              <div class="form-group">
            </div>
            <div class="clear"></div>

            <div class="col-md-60">
              <div class="row default">
                <div class="col-md-27 col-sm-27">
                  <div class="d-block picts_captcha" style="margin-left: -15px;">
                    @if(env('GOOGLE_RECAPTCHA_KEY'))
                      <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                    @endif
                  </div>
                </div>
                <div class="col-md-27 col-sm-27 hide-pc">
                  <div class="text-right submit">
                  <button type="submit">Kirim</button>
                  </div>
                </div>
                <div class="col-md-60 hide-mobile">
                  <div class="text-center submit">
                  <button type="submit">Kirim</button>
                  </div>
                </div>
                              
                <div class="py-5 hide-mobile"></div>
                <div class="py-5 hide-mobile"></div>
              </div>

            </div>
            </div>
          </div></form>       </div>
      </div>
      <div class="col-md-20">
        
      </div>
    </div>
  </div>
</section>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/id/about') }}";
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