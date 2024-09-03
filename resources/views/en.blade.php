@extends('layouts.app_en')

@section('title')
<title>PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id') }}"><span class="">IN</span></a>
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
                                        <p>As a reliable quality calcium carbonate manufacturer,
                                            the only key that drives our company's engine is 
                                        </p>
                                        <h5>our focus & comitment <strong>on your success.</strong></h5>
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
                        <h4>How we’ve become essential part of your business.</h4>
                    <div class="pt-1 pb-5">
                      <p>We make calcium carbonate that you can rely and you can count on. Without you even realizing it, we make your operation run smooth and put your product at a high quality stage.  It’s our legacy of over decades since 1988, built and nurtured on the solid foundation of doing business with ethics and values of partnership.</p>
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
            <p>Calcium Carbonate that we manufacture...</p>
          </div>
          <div class="content pt-4">
            <p>We take pride in providing high quality calcium carbonate products to our market in Surabaya, Jakarta and throuhout Indonesia. We will meet your specification and we will ensure a steady production flow by maintaining our stock priority for your long-term order suply.</p>
          </div>
          <div class="link pt-4">
            <a href="{{ url('en/products') }}">
              <p>our calcium carbonate products</p>
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
            <p>More On Dwi Selo Giri Mas - The Calcium Carbonate Factory</p>
          </div>
    <div class="row">
      <div class="col-md-20 wow fadeInUp">
        <div class="box-content pt-4">
                    <div class="title-box pb-2">
            <h1>About Us</h1>
          </div>
          <img class="w-100" src="{{asset('app-assets/asset/images/design-1_07.png')}}" alt="">
          <div class="content py-4">
            <p>We make products that is essential for your factory to run smooth. We bring benefits in more ways without you even realizing it.</p>
          </div>
          <div class="link">
            <a href="{{ url('en/about') }}">
              <p>read more</p>
            </a> 
          </div>
                  </div>
      
      </div>
      <div class="col-md-20 wow fadeInDown">
        <div class="box-content pt-4">
                    <div class="title-box pb-2">
            <h1>Process & Quality</h1>
          </div>
          <img class="w-100" src="{{asset('app-assets/asset/images/design-1_09.png')}}" alt="">
          <div class="content py-4">
            <p>Our strict quality control is integrated and we offer full integrity and traceability for all our products.</p>
          </div>
          <div class="link">
            <a href="{{ url('en/quality') }}">
              <p>read more</p>
            </a> 
          </div>
                  </div>

      </div>
      <div class="col-md-20 wow fadeInUp">
        <div class="box-content pt-4">
                    <div class="title-box pb-2">
            <h1>Career</h1>
          </div>
          <img class="w-100" src="{{asset('app-assets/asset/images/design-1_11.png')}}" alt="">
          <div class="content py-4">
            <p>Join us and become part of a growing national company with a culture that allows you to bring out the best in you.</p>
          </div>
          <div class="link">
            <a href="{{ url('en/career') }}">
              <p>read more</p>
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
            <p>Contact us for inquiry</p>
          </div>
          <div class="subtitle pt-4 pb-5">
            <p>Our representative staff will respond to you son to discuss so many probabilities and solutions that you may require.</p>
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
                    
          <form enctype="multipart/form-data" class="py-5 form-" id="yw0" action="{{ url('en/form-contact/process') }}" method="post">  {{ csrf_field() }} 
                        <div class="row default">
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Name" name="form_contact_name" id="form_contact_name" type="text" value="{{ old('form_contact_name') }}">                </div>
              </div>
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Company" name="form_contact_company" id="form_contact_company" type="text" value="{{ old('form_contact_company') }}">               </div>
              </div>
            </div>

            <div class="row default">
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Email" name="form_contact_email" id="form_contact_email" type="text" value="{{ old('form_contact_email') }}">               </div>
              </div>
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                <input class="form-control" placeholder="Phone" name="form_contact_phone" id="form_contact_phone" type="text" value="{{ old('form_contact_phone') }}">               </div>
              </div>
            </div>

            <div class="row default">
              <div class="col-md-54 col-sm-54">
                <div class="form-group">
                  <input class="form-control" placeholder="Notes / Messages" name="form_contact_message" id="form_contact_message" type="text" style="width: 100%;" value="{{ old('form_contact_message') }}">               
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
                  <button type="submit">Submit</button>
                  </div>
                </div>
                <div class="col-md-60 hide-mobile">
                  <div class="text-center submit">
                  <button type="submit">Submit</button>
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
    var baseurl = "{{ url('/en') }}";
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