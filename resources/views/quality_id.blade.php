@extends('layouts.app_id')

@section('title')
<title>KUALITAS - PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/quality') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/quality') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/quality') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/quality') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<div class="clear"></div>
<div class="yellows_headBottoms"></div>
<div class="clear"></div>
<section class="cover-about blue-blocksall">
  <div class="container cont-fcs produk">
    <div class="cover-image">
        <!-- <img class="w-100 d-block" src="/asset/images/ill-process.jpg" alt=""> -->
        <div class="centered wow fadeInUp">
          <p>
            QUALITY          </p>
        </div>
    </div>
    <div class="row pt-3">
      <div class="col-40">
        <div class="breadcrumb wow fadeInUp">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('id') }}">BERANDA</a></li>
              <li class="breadcrumb-item"><a href="{{ url('id/quality') }}">KUALITAS</a></li>
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

  <div class="prelative container">
    <div class="content-text text-center pt-5 wow fadeInUp">
            <span><b>mencapai STANDAR tertinggi</b></span>
      <h3>Suatu proses menuju kesempurnaan kualitas</h3>
      <h5>Proses dan kontrol kualitas yang ketat di Dwi Selo Girimas sangat penting bagi kami untuk secara konsisten mencapai standar tertinggi di seluruh produk Kalsium Karbonat kami. QC diterapkan pada setiap tahap dalam operasi pemrosesan kami di seluruh sumber dan pemilihan bahan baku kalsium, penyaringan dan pengujian kalsium di laboratorium, pengemasan dan pengiriman produk.</h5>
      <p>Sistem Kontrol Kualitas kami terintegrasi dan diserahkan per divisi dengan hati-hati sehingga kami dapat menawarkan integritas dan keterlacakan penuh untuk semua produk kalsium karbonat kami. Model jaminan kualitas yang kami kembangkan ini, akan memeriksa dan memverifikasi semua produk kami, memastikan bahwa semua proses kami bebas dari cacat dan cacat, mencapai tingkat kalsium karbonat tertinggi yang Anda minta.</p>
            <div class="clear clearfix"></div>
    </div>
  </div>

  <div class="clear clearfix"></div>
</section>


<!-- 
<<<<<<< HEAD
=======
  <div class="inners_backs py-5">
    <div class="prelatife container containers_inside py-4">

      <div id="carouselExa_gal" class="carousel slide" data-ride="carousel">
        <div class="row no-gutters content-text back-white">
          <div class="col-md-30">
            <div class="row no-gutters">
              <div class="col-md-30">
                <div class="lefts_con">
                  <div class="py-5"></div>
                  <ul class="list-unstyled listsn_car_process">
                    <li data-target="#carouselExa_gal" data-slide-to="0" class="data0 active"><a href="#">material sourcing</a></li>
                    <li data-target="#carouselExa_gal" data-slide-to="1" class="data1"><a href="#">oven & crush</a></li>
                    <li data-target="#carouselExa_gal" data-slide-to="2" class="data2"><a href="#">final screening</a></li>
                    <li data-target="#carouselExa_gal" data-slide-to="3" class="data3"><a href="#">dispatch</a></li>
                  </ul>
                  <div class="clear clearfix"></div>
                </div>
              </div>
              <div class="col-md-30">
                <div class="pictures">
                  <div class="carousel-inner">
                    <div class="carousel-item active" data-id="0">
                      <img class="d-block img img-fluid w-100" src="/asset/images/process-material.jpg" alt="">
                    </div>
                    <div class="carousel-item" data-id="1">
                      <img class="d-block img img-fluid w-100" src="/asset/images/process-crush.jpg" alt="">
                    </div>
                    <div class="carousel-item" data-id="2">
                      <img class="d-block img img-fluid w-100" src="/asset/images/process-final-screen.jpg" alt="">
                    </div>
                    <div class="carousel-item" data-id="3">
                      <img class="d-block img img-fluid w-100" src="/asset/images/process-dispatch.jpg" alt="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-30">
            <div class="py-5"></div>
            <div class="description pl-5">
              <div class="car-item d-block data0">
                                <h4>Pemilihan Bahan Baku</h4>
                <p>Dwi Selo Giri Mas telah memiliki pengalaman panjang untuk dapat mengetahui sumber terpercaya yang memiliki kualitas calcium terbaik. Untuk hasil akhir yang baik, maka kami tidak akan mentoleransi kualitas bahan baku.</p>
                              </div>
              <div class="car-item d-none data1">
                                <h4>Oven & Crush</h4>
                <p>Di proses produksi, kami memproses calcium yang telah disortir untuk masuk proses oven & crush. Semua proses dilakukan secara mesin secara otomatis untuk menghindari adanya kesalahan manusia dan semua otomatisasi yang ada selalu tidak luput dari pemeriksaan setiap waktu.</p>
                              </div>
              <div class="car-item d-none data2">
                                  <h4>Sortir Akhir</h4>
                  <p>Sebelum memasuki proses pengemasan, kami selalu melakukan batch-checking, sebagai pengecekan tahap akhir untuk memastikan tiap batch memiliki hasil yang sempurna.</p>
                              </div>
              <div class="car-item d-none data3">
                                  <h4>Pengiriman</h4>
                  <p>Proses kualitas kami berlangsung hingga sampai barang diterima pelanggan, oleh karena itu kami sangat memperhatikan proses muat di tiap armada kami.</p>
                              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="clear"></div>
    </div>
  </div>  
  <div class="py-5"></div>
</section>
>>>>>>> master -->
<script type="text/javascript">
  $(function(){
    var s_leftindicat = $('.inners_backs .lefts_con ul li');
    
    $(s_leftindicat).on('mouseenter', function(){

      if ( $('.inners_backs .lefts_con ul li').hasClass('active') ) {
        $('.inners_backs .lefts_con ul li').removeClass('active');
      }
      $(this).addClass('active');

      var desc_act = $(this).attr('data-slide-to');
      
      $('#carouselExa_gal').carousel(desc_act);

      $('.inners_backs .description .car-item').removeClass('d-block').addClass('d-none');
      $('.inners_backs .description .car-item.data'+desc_act).removeClass('d-none').addClass('d-block');
    });

    $('#carouselExa_gal').on('slid.bs.carousel', function () {
        var currentIndex = $('div.carousel-inner .active').attr('data-id');
        console.log(currentIndex);
        
        var slef_indicat = $('ul.listsn_car_process li');
        if ( $('ul.listsn_car_process li').hasClass('active') ) {
          $('ul.listsn_car_process li').removeClass('active');
        }
        $('ul.listsn_car_process li.data'+currentIndex).addClass('active');
        // console.log(slef_indicat);
        $('.inners_backs .description .car-item').removeClass('d-block').addClass('d-none');
        $('.inners_backs .description .car-item.data'+currentIndex).removeClass('d-none').addClass('d-block');
        
    });

  });
</script>

<section class="block-bottom-process">
  <div class="py-4"></div>
  <div class="py-5"></div>
  <div class="text-center tops_titles pt-2">
    <span>our quality</span>
  </div>
  <!-- <div class="py-3 hide-pc"></div> -->
  <!-- <div class="py-2"></div> -->

  <div class="prelative container containers_inside py-3">
    <div class="title-head-process pb-5 pt-2">
      <p>We are committed to bring the best Calcium Carbonate</p>
    </div>
    <div class="row lists_btm_process">
      <div class="col-md-30 slines">
        <div class="items d-block mx-auto">
                    <!-- <h4 class="titles">Mengapa kualitas sangat penting untuk Kalsium Karbonat</h4> -->
          <div class="pictures py-3"><img src="{{asset('app-assets/asset/images/prog_ban_proc_ss1.jpg')}}" alt="" class="img img-fluid"></div>
          <div class="infos py-1">
            <p>Seperti kita ketahui bahwa kalsium karbonat memainkan peran penting bagi pelanggan kita sebagai salah satu bahan utama / inti untuk produksi mereka, ketika kalsium karbonat gagal memenuhi kualitas yang diinginkan, ini akan mempengaruhi produksi dan produk akhir pelanggan, seperti bahan bakar yang buruk akan menghancurkan kendaraan yang menjalankannya.</p>
            <div class="clear"></div>
          </div>
                  </div>
      </div>
      <div class="col-md-30">
        <div class="items d-block mx-auto">
                    <!-- <h4 class="titles">Apa rahasia di balik produk-produk berkualitas tinggi kami</h4> -->
          <div class="pictures py-3"><img src="{{asset('app-assets/asset/images/prog_ban_proc_ss2.jpg')}}" alt="" class="img img-fluid"></div>
          <div class="infos py-1">
            <p>Kami bangga menyediakan produk kalsium karbonat berkualitas tinggi untuk pasar kami di Surabaya, Jakarta dan di seluruh Indonesia. Kami akan memenuhi spesifikasi Anda dan kami akan memastikan aliran produksi yang stabil dengan mempertahankan prioritas stok kami untuk suply pesanan jangka panjang Anda.</p>
            <div class="clear"></div>
          </div>
                  </div>
      </div>
    </div>
    <div class="py-4 my-2 hide-pc"></div>
    <div class="clear"></div>
  </div>

  <div class="py-5"></div>
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
                    
          <form enctype="multipart/form-data" class="py-5 form-" id="yw0" action="{{ url('id/form-contact/process') }}" method="post">
            {{ csrf_field() }}              
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
    var baseurl = "{{ url('/id/quality') }}";
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