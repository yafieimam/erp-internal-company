@extends('layouts.app_id')

@section('title')
<title>PRODUK - PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/products') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/products') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/products') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/products') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="clear"></div>
<div class="yellows_headBottoms"></div>
<div class="clear"></div>
<section class="cover">
    <div class="container cont-fcs produk">
        <div class="cover-image" style="background-image: url('{{asset('app-assets/asset/images/Layer-311.jpg')}}'); background-position: center;">
            <!-- <img class="w-100 d-block" src="/asset/images/ill-process.jpg" alt=""> -->
            <div class="centered wow fadeInUp">
              <p>
                PRODUCTS              </p>
            </div>
        </div>
        <div class="row pt-3">
          <div class="col-40">
            <div class="breadcrumb wow fadeInUp">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('id') }}">BERANDA</a></li>
                  <li class="breadcrumb-item"><a href="{{ url('id/products') }}">PRODUK</a></li>
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
<section class="cover-above">
    <div class="prelative container">
        <div class="row pt-5 wow fadeInUp">
                        <div class="col-md-60">
                <div class="caption">
                    <div class="text1 mx-auto d-block text-center pb-4">
                        <p>Produk kami sebagai bagian dari operasional Anda</p>
                    </div>
                </div>
            </div>
            <div class="col-md-60">
                <div class="caption">
                    <div class="text2 mx-auto d-block text-center pb-4">
                        <p>Kalsium Karbonat Kami Dengan Anda Dalam Pikiran</p>
                    </div>
                </div>
            </div>
            <div class="col-md-60">
                <div class="caption">
                    <div class="text3 mx-auto d-block text-center pb-4">
                        <p>Kami bangga menyediakan produk kalsium karbonat berkualitas tinggi untuk pasar kami di Surabaya, Jakarta dan di seluruh Indonesia. Kami akan memenuhi spesifikasi Anda dan kami akan memastikan aliran produksi yang stabil dengan mempertahankan prioritas stok kami untuk jangka panjang Anda.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-60">
                <div class="caption">
                    <div class="text4 mx-auto d-block text-center pb-4">
                        <p>Kalsium Karbonat adalah bahan penting untuk memproduksi industri cat, industri kimia, industri keramik, industri plastik, industri kertas dan banyak lagi. Kalsium karbonat berkualitas yang memenuhi persyaratan akan memastikan kelancaran operasi dan produksi pabrik pelanggan kami. Sebagai bagian penting dan integral dari produksi, kalsium karbonat dijual dengan harga yang sangat rendah - oleh karena itu tidak ada alasan atau pilihan bagi pelanggan kami untuk memilih kadar kalsium karbonat yang lebih rendah. “Dengan pertimbangan Anda” sebagai semangat dan komitmen kami sehari-hari, kami menghasilkan produk terbaik untuk menjamin kesuksesan Anda.</p>
                    </div>
                </div>
            </div>
                    </div>
    </div>
    <div class="py-5"></div>
    <div class="py-5"></div>
    <div class="py-4"></div>
    <!-- <div class="pb-5"></div> -->
</section>

<section class="produk-sec-1 outersec_prd_2">
    <div class="prelative container pt-5">
        <div class="picts_mid wow fadeInUp">
            <img src="{{asset('app-assets/asset/images/in-mid_salts.jpg')}}" alt="" class="img img-fluid d-block mx-auto">
        </div>
        <div class="title pt-5 mx-auto d-block text-center wow fadeInUp">
                        <h3>KAMI MENGHASILKAN KARBONAT KALSIUM UNTUK PERSYARATAN ANDA</h3>
            <div class="py-1"></div>
            <p>Silakan tanyakan - konsultan kami akan membahas spesifikasi terperinci Anda (kemurnian, mesh, dll).</p>
            <div class="py-1"></div>
            <!-- <a href="" class="btn btn-link btns_tcontact_pr">HUBUNGI KAMI</a> -->
                    </div>
        <div class="row wow fadeInUp">
            <div class="col-md-60">
                <div class="pt-5"></div>
                <div class="pt-4"></div>
                <div class="image">
                    <img class="mx-auto d-block" src="{{asset('app-assets/asset/images/diagram.png')}}" alt="diagram natriaum carbonate" usemap="#Map">
                    <map name="Map" id="Map">
                        <area class="toscroll" data-id="paint-coating" title="paint-coating" href="#paint-coating" shape="poly" coords="6,233,159,262,174,330,38,408">
                        <area class="toscroll" data-id="fishery" title="fishery" href="#fishery" shape="poly" coords="179,337,232,374,181,520,55,418">
                        <area class="toscroll" data-id="ceramics-glass" title="ceramics-glass" href="#ceramics-glass" shape="poly" coords="241,383,302,386,358,522,192,522">
                        <area class="toscroll" data-id="fertilizer" title="rubber" href="#rubber" shape="poly" coords="315,375,367,338,499,418,367,523">
                        <area class="toscroll" data-id="rubber" title="fertilizer" href="#fertilizer" shape="poly" coords="368,328,505,402,540,238,388,261">
                        <area class="toscroll" data-id="adhesives" title="adhesives" href="#adhesives" shape="poly" coords="382,251,538,220,452,72,354,188">
                        <area class="toscroll" data-id="contruction" title="contruction" href="#contruction" shape="poly" coords="343,182,443,63,275,4,279,152">
                        <area class="toscroll" data-id="animal-feed" title="animal-feed" href="#animal-feed" shape="poly" coords="100,68,264,6,268,158,200,187">
                        <area class="toscroll" data-id="water-treatment" title="water-treatment" href="#water-treatment" shape="poly" coords="94,75,193,192,159,250,11,226">
                    </map>
                </div>
                <div class="pb-4"></div>
            </div>
            <div class="col-md-60">
                <div class="content" id="paint-coating">
                                        <div class="title-content">
                        <p>Cat & Pelapis</p>
                    </div>
                    <div class="isinya">
                        <p>Kalsium karbonat meningkatkan kinerja pelapisan yang dikenal sebagai pengisi fungsional. Kualitas pengisi dapat meningkatkan opacity cat dekoratif, kecerahan, reflektansi, kemampuan kerja dan banyak lagi. Seperti halnya pada pelapis industri, pemilihan pengisi dapat membuat dampak besar perbedaan pada gloss, viskositas dan daya tahan.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="adhesives">
                                        <div class="title-content">
                        <p>Perekat & Sealant</p>
                    </div>
                    <div class="isinya">
                        <p>Bahan dasar dalam banyak perekat dan sealant adalah Kalsium Karbonat, yang meningkatkan reologi, kekuatan ikatan, dan mengurangi permintaan air.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="ceramics-glass">
                                        <div class="title-content">
                        <p>Kaca & Keramik</p>
                    </div>
                    <div class="isinya">
                        <p>Kaca terbentuk dari batuan jenis tertentu yang meleleh dalam suhu tinggi dan kemudian dingin dan mengeras dengan cepat. Fungsi kalsium karbonat dalam produksi kaca untuk stabilizer, memodifikasi viskositas dan meningkatkan daya tahan. Penggunaan lain kalsium karbonat adalah mengurangi biaya produksi keramik, karena kalsium karbonat merupakan sumber kalsium oksida yang ekonomis yang diperlukan sebagai zat leleh yang dapat meningkatkan kekuatan mekanik dan kimia serta mengurangi penyusutan.</p>
                    </div>
                                        
                </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="contruction">
                                        <div class="title-content">
                        <p>Konstruksi</p>
                    </div>
                    <div class="isinya">
                        <p>Kalsium karbonat terkenal dengan produk berbasis semen, seperti aspal, atap, ubin, dan batu bata. Fungsi kalsium karbonat dalam konstruksi sebagai penstabil, mempercepat hidrasi matriks semen, meningkatkan kekuatan dan daya tahan.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="rubber">
                                        <div class="title-content">
                        <p>Industri Karet</p>
                    </div>
                    <div class="isinya">
                        <p>Dengan pemilihan tingkat kalsium karbonat yang baik, dapat mengurangi biaya produksi karet dan meningkatkan efek pencampuran, dan pelepasan cetakan.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="water-treatment">
                                        <div class="title-content">
                        <p>Pengolahan air</p>
                    </div>
                    <div class="isinya">
                        <p>Untuk meningkatkan kualitas air alami atau air olahan (mis. Desalinasi) ke tingkat non-korosif atau ke tingkat yang dapat diterima untuk konsumsi manusia, perlu menyesuaikan nilai pH melalui netralisasi, (kembali) mengisolasi air dengan menambahkan (melarutkan) jumlah yang dibutuhkan kalsium, magnesium dan mineral karbonat atau mengurangi kesadahan air (dekarbonisasi). Tingkat kalsium karbonat yang tidak mencukupi dalam air olahan membuatnya korosif dan akan menyebabkan peralatan dan struktur memburuk, seringkali tanpa memenuhi persyaratan kualitas air minum seperti yang direkomendasikan oleh WHO (World Health Organization). Solusi jenuh mungkin akan mengendapkan kalsium karbonat, menyebabkan skala, mengurangi efisiensi dan akhirnya menyebabkan kegagalan sistem.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="fertilizer">
                                        <div class="title-content">
                        <p>Pupuk</p>
                    </div>
                    <div class="isinya">
                        <p>Pupuk kalsium dan magnesium dikenal digunakan untuk pupuk. Pupuk membantu memperbaiki tanah, nutrisi tanaman dan perlindungan tanaman.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="animal-feed">
                                        <div class="title-content">
                        <p>Pakan ternak</p>
                    </div>
                    <div class="isinya">
                        <p>Kalsium adalah unsur pencampuran penting untuk setiap spesies hewan. Selain pakan dasar, persyaratan spesies individu membutuhkan penambahan kalsium karbonat ke dalam rasio pakan. Efek menguntungkan dari menggunakan kalsium karbonat sebagai pakan ternak adalah pengembangan tulang dan gigi yang tepat, pengaturan detak jantung dan pembekuan darah, kontraksi otot dan impuls saraf, aktivasi enzim dan sekresi hormon, pembentukan dan kualitas kulit telur, pembentukan dan kualitas kulit telur, serta produksi susu.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="fishery">
                                        <div class="title-content">
                        <p>Perikanan</p>
                    </div>
                    <div class="isinya">
                        <p>Tujuan dari pengelolaan kolam yang baik adalah untuk meningkatkan produksi ikan melalui peningkatan pasokan makanan alami seperti fitoplankton dan zooplankton. Suplai biasanya meningkat dengan menyuburkan air tambak. Penambahan kalsium karbonat di tambak disebut pengapuran, pengapuran dapat dilakukan ketika pH kolam di bawah 6,5, sangat berlumpur, mengendalikan penyakit dan hama, konsentrasi tinggi bahan organik, dan alkalinitas total di bawah 25 mg / l CaCO3.</p>
                    </div>
                                        <div class="pb-5"></div>
                    <div class="pb-3"></div>
                    <div class="hr-produk"></div>
                </div>
            </div>
        </div>

        <div class="row wow fadeInDown">
            <div class="col-md-60">
                <div class="footer-produk-bawah">
                    <div class="pt-5"></div>
                    <div class="pt-3"></div>
                                        <h5>KAMI MENGHASILKAN KARBONAT KALSIUM UNTUK PERSYARATAN ANDA</h5>
                    <h6>Silakan tanyakan - konsultan kami akan membahas spesifikasi terperinci Anda (kemurnian, mesh, dll).</h6>
                    <a href="{{ url('id/contact') }}"><p>KONTAK</p></a>
                                    </div>
            </div>
        </div>

            </div>
    <div class="pb-5"></div>
    <div class="pb-5"></div>
    <!-- <div class="pb-5"></div> -->
</section>
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
                  <input class="form-control" placeholder="Nama" name="form_contact_name" id="form_contact_name" type="text" value="{{ old('form_contact_name') }}">                </div>
              </div>
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Perusahaan" name="form_contact_company" id="form_contact_company" type="text" value="{{ old('form_contact_company') }}">               </div>
              </div>
            </div>

            <div class="row default">
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Email" name="form_contact_email" id="form_contact_email" type="text" value="{{ old('form_contact_email') }}">               </div>
              </div>
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                <input class="form-control" placeholder="Telepon" name="form_contact_phone" id="form_contact_phone" type="text" value="{{ old('form_contact_phone') }}">               </div>
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
    var baseurl = "{{ url('/id/products') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>

<script>
  $('.toscroll').click(function() {
    var sn_id = $(this).attr('data-id');
    $('html, body').animate({
        scrollTop: $("#"+ sn_id).offset().top - 60
    }, 1000);
  });

  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>
@endsection