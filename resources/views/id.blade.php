@extends('layouts.app_id')

@section('title')
<title>PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<!-- Start fcs -->
<div class="fcs-wrapper outers_fcs_wrapper prelatife wrapper-slide">
    <div class="container cont-fcs">
        <div id="myCarousel_home" class="carousel slide" data-ride="carousel" data-interval="6000" data-touch="true">
                <div class="carousel-inner">
                                        <div class="carousel-item active home-slider-new">
                        <img class="w-100 d-none d-sm-block" src="{{asset('app-assets/images/slide/.tmb/thumb_d29b6-fcs-dsgm-01_adaptiveResize_1834_756.jpg')}}" alt="First slide">
                        <img class="w-100 d-block d-sm-none" src="{{asset('app-assets/images/slide/.tmb/thumb_ace19-dsgm-01-sld_adaptiveResize_774_867.jpg')}}" alt="">
                    </div>
                                        <div class="carousel-item  home-slider-new">
                        <img class="w-100 d-none d-sm-block" src="{{asset('app-assets/images/slide/.tmb/thumb_c125e-fcs-dsgm-02_adaptiveResize_1834_756.jpg')}}" alt="First slide">
                        <img class="w-100 d-block d-sm-none" src="{{asset('app-assets/images/slide/.tmb/thumb_17909-dsgm-02-sld_adaptiveResize_774_867.jpg')}}" alt="">
                    </div>
                                        <div class="carousel-item  home-slider-new">
                        <img class="w-100 d-none d-sm-block" src="{{asset('app-assets/images/slide/.tmb/thumb_fe7ce-fcs-dsgm-03_adaptiveResize_1834_756.jpg')}}" alt="First slide">
                        <img class="w-100 d-block d-sm-none" src="{{asset('app-assets/images/slide/.tmb/thumb_aaf59-dsgm-03-sld_adaptiveResize_774_867.jpg')}}" alt="">
                    </div>
                                        <div class="carousel-item  home-slider-new">
                        <img class="w-100 d-none d-sm-block" src="{{asset('app-assets/images/slide/.tmb/thumb_969d4-fcs-dsgm-04_adaptiveResize_1834_756.jpg')}}" alt="First slide">
                        <img class="w-100 d-block d-sm-none" src="{{asset('app-assets/images/slide/.tmb/thumb_392f5-dsgm-04-sld_adaptiveResize_774_867.jpg')}}" alt="">
                    </div>
                                        <div class="carousel-item  home-slider-new">
                        <img class="w-100 d-none d-sm-block" src="{{asset('app-assets/images/slide/.tmb/thumb_4c81f-fcs-dsgm-05_adaptiveResize_1834_756.jpg')}}" alt="First slide">
                        <img class="w-100 d-block d-sm-none" src="{{asset('app-assets/images/slide/.tmb/thumb_412cf-dsgm-05-sld_adaptiveResize_774_867.jpg')}}" alt="">
                    </div>
                                    </div>
                <ol class="carousel-indicators">
                                        <li data-target="#myCarousel_home" data-slide-to="0" class="active">></li>
                                        <li data-target="#myCarousel_home" data-slide-to="1">></li>
                                        <li data-target="#myCarousel_home" data-slide-to="2">></li>
                                        <li data-target="#myCarousel_home" data-slide-to="3">></li>
                                        <li data-target="#myCarousel_home" data-slide-to="4">></li>
                                    </ol>
                <div class="carousel-caption caption-slider-home mx-auto">
                    <div class="prelatife container mx-auto">
                        <div class="bxsl_tx_fcs">
                            <div class="row no-gutters">
                                <div class="col-md-10"></div>
                                <div class="col-md-40">
                                    <div class="text-slide wow fadeInUp">
                                        <p>Sebagai produsen kalsium karbonat berkualitas yang dapat diandalkan, satu-satunya kunci yang menggerakkan mesin perusahaan kami adalah
                                        </p>
                                        <h5>FOKUS DAN KOMITMEN KAMI PADA <b>KEBERHASILAN ANDA</b>.</h5>
                                    </div>
                                </div>
                                <div class="col-md-10"></div>
                            </div>
                        </div>
                        <div class="clear clearfix"></div>
                    </div>
                </div>
        </div>

    </div>
</div>

<section class="above-slide">
    <div class="prelative container">
        <div class="row">
            <div class="col-md-60 wow fadeInUp">
                <div class="box-scroll">
                    <div class="pt-5"></div>
                    <div class="pt-4"></div>
                        <h4>Bagaimana kami menjadi bagian penting dari bisnis Anda.</h4>
                    <div class="pt-1 pb-5">
                    	<p>Kami membuat kalsium karbonat yang dapat Anda andalkan dan dapat Anda andalkan. Tanpa Anda sadari, kami membuat operasi Anda berjalan mulus dan menempatkan produk Anda pada tahap kualitas tinggi. Ini adalah warisan kami selama puluhan tahun sejak tahun 1988, dibangun dan dipelihara di atas dasar yang kuat untuk melakukan bisnis dengan etika dan nilai-nilai kemitraan.</p>
                    </div>
                    <a href="#">
                        <img id="click" src="{{asset('app-assets/asset/images/scroll-down.png')}}" alt="">
                    </a>
                    <div class="pb-5 hide-pc"></div>
                    <div class="pb-5"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End fcs -->

<section class="home-sec-1">
	<div class="prelative container py-5">
		<div class="row py-3">
			<div class="col-md-30 wow fadeInLeft">
								<div class="box-content">
					<div class="title">
						<p>Kalsium Karbonat yang kami produksi...</p>
					</div>
					<div class="content pt-4">
						<p>Kami bangga menyediakan produk kalsium karbonat berkualitas tinggi untuk pasar kami di Surabaya, Jakarta dan di seluruh Indonesia. Kami akan memenuhi spesifikasi Anda dan kami akan memastikan aliran produksi yang stabil dengan mempertahankan prioritas stok kami untuk suply pesanan jangka panjang Anda.</p>
					</div>
					<div class="link pt-4">
						<a href="{{ url('id/products') }}">
							<p>produk kalsium karbonat kami</p>
						</a>
					</div>
				</div>
							</div>
			<div class="col-md-30 wow fadeInRight">
				<img class="img img-fluid" src="{{asset('app-assets/asset/images/design-1_03.jpg')}}" alt="">
			</div>
		</div>
	</div>
</section>

<section class="home-sec-2" id="div1">
	<div class="py-3 hide-pc"></div>
	<div class="py-3"></div>
	<div class="py-3"></div>
	<div class="prelative container">
		<div class="title-sec wow fadeInUp">
			<p>Tentang Dwi Selo Giri Mas - Pabrik Kalsium Karbonat</p>
		</div>
		<div class="row">
			<div class="col-md-20 wow fadeInUp">
				<div class="box-content pt-4">
					<div class="title-box pb-2">
						<h1>Tentang Kami</h1>
					</div>
					<img class="w-100" src="{{asset('app-assets/asset/images/design-1_07.png')}}" alt="">
					<div class="content py-4">
						<p>Kami membuat produk yang penting bagi pabrik Anda untuk berjalan mulus. Kami membawa manfaat dengan lebih banyak cara tanpa Anda sadari.</p>
					</div>
					<div class="link">
						<a href="{{ url('id/about') }}">
							<p>lebih lanjut</p>
						</a> 
					</div>
									</div>
			
			</div>
			<div class="col-md-20 wow fadeInDown">
				<div class="box-content pt-4">
					<div class="title-box pb-2">
						<h1>Proses & Kualitas</h1>
					</div>
					<img class="w-100" src="{{asset('app-assets/asset/images/design-1_09.png')}}" alt="">
					<div class="content py-4">
						<p>Kontrol kualitas kami yang ketat terintegrasi dan kami menawarkan integritas penuh dan keterlacakan untuk semua produk kami.</p>
					</div>
					<div class="link">
						<a href="{{ url('id/quality') }}">
							<p>lebih lanjut</p>
						</a> 
					</div>
									</div>

			</div>
			<div class="col-md-20 wow fadeInUp">
				<div class="box-content pt-4">
					<div class="title-box pb-2">
						<h1>Karir</h1>
					</div>
					<img class="w-100" src="{{asset('app-assets/asset/images/design-1_11.png')}}" alt="">
					<div class="content py-4">
						<p>Bergabunglah dengan kami dan menjadi bagian dari perusahaan nasional yang berkembang dengan budaya yang memungkinkan Anda untuk mengeluarkan yang terbaik dari diri Anda.</p>
					</div>
					<div class="link">
						<a href="{{ url('id/career') }}">
							<p>lebih lanjut</p>
						</a> 
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="py-3 hide-pc"></div>
	<div class="py-3"></div>
	<div class="py-3"></div>
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
					</div></form>				
				</div>
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
    var baseurl = "{{ url('/id') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>
@endsection

@section('script_login')
  <script>
    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>
@endsection