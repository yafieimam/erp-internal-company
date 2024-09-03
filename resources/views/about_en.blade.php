@extends('layouts.app_en')

@section('title')
<title>ABOUT US - PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/about') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/about') }}"><span class="">IN</span></a>
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
            ABOUT US          </p>
        </div>
    </div>
    <div class="row pt-3">
      <div class="col-40">
        <div class="breadcrumb wow fadeInUp">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('en') }}">HOME</a></li>
              <li class="breadcrumb-item"><a href="{{ url('en/about') }}">ABOUT US</a></li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="col-20">
        <div class="back text-right wow fadeInUp">
          <a href="{{ url('en') }}">BACK</a>
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
            <p>WHO WE ARE</p>
          </div>
          <div class="text2 pb-4">
            <p>PT. DWI SELO GIRI MAS THE CALCIUM CARBONATE MANUFACTURER</p>
          </div>
          <div class="text3">
            <p>Dwi Selo Giri Mas (DSGM) is an established manufacturer - supplier of Calcium Carbonate from Sidoarjo - Surabaya, East Java. DSGM has a proven track record as a calcium carbonate manufacturer since 1988. Our market for customers spread across the Java (Jakarta, Surabaya, Gresik, Mojokerto, and more), and even through out to islands such as Kalimantan and Sulawesi. We have a refreshingly innovative mix of state of the art machinery plus a qualified and experienced team that serves with you in mind. DSGM is committed to providing customers with quality products that are delivered on time, our focus and commitment is always on your success. </p>
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
            <p>WHy us</p>
          </div>
          <div class="title">
            <p>Our experience and reputation is one of the best in the field.</p>
          </div>
          <div class="content pt-4">
            <p class="mb-4">Dwi Selo Giri Mas (DSGM) has designed flexibility into our process, this allows DSGM to cater to all customers specific requirement to produce the optimal calcium carbonate powder as required. Being well known for our reputation since 1988 helps us to source the best raw materials and respond quickly to customer's needs. The efficient running investment on quality manufacturing enables us to deliver our calcium carbonate products at a price to beat.</p>
            <p class="mb-4">The key of our product success lies on our thight quality control and our strict production flow protocols. Feel free to inquire your needs of calcium carbonate products.</p>
            <p class="mb-4">Our solid partnerships are formed and strengthened due to the values, purpose and vision that our employees live by. Our core purpose is to keep businesses running and that is backed by our values of get better every day, love they neighbor, do what it takes and own your future. The combination of our purpose and values and the quality of our products, makes us a strong supplier; your supplier of choice and where you can buy calcium carbonate powder.</p>
          </div>
          <div class="link pt-2">
            <a href="{{ url('en/products') }}">
              <p>VIEW OUR CALCIUM CARBONATE PRODUCTS</p>
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
                        <p>Vision</p>
                      </div>
          <div class="caption pt-4">
                        <p>To be the trusted leader in calcium carbonate industry that surpass our customer's needs</p>
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
                        <p>Mission</p>
                      </div>
          <div class="caption pt-4">
                        <ul>
              <li>We supply high quality of calcium carbonate while stimulating the growth of our communities – team members, customers shareholders, local society and environment.
              </li>
              <li>It is the fundamental value upon we conduct our business and relationships with others. We are honest, open, fair, trustworthy and ethical.
              </li>
              <li>We value our customers and we strive to satisfy their expectations by delivering superior products, services and solutions.
              </li>
              <li>We value every team member for their needs and safety. We treat each other with respect and openness as if they are our own family.
              </li>
              <li>We strive to minimize our impact on the environment while we increase our contribution to the local society.
              </li>
            </ul>
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
                    
          <form enctype="multipart/form-data" class="py-5 form-" id="yw0" action="{{ url('en/form-contact/process') }}" method="post"> 
            {{ csrf_field() }}   
            <div class="row default">
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Name" name="form_contact_name" id="form_contact_name" type="text" value="{{ old('form_contact_name') }}">                
                </div>
              </div>
              <div class="col-md-27 col-sm-27">
                <div class="form-group">
                  <input class="form-control" placeholder="Company" name="form_contact_company" id="form_contact_company" type="text" value="{{ old('form_contact_company') }}">               
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
                <input class="form-control" placeholder="Phone" name="form_contact_phone" id="form_contact_phone" type="text" value="{{ old('form_contact_phone') }}">               
              </div>
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
    var baseurl = "{{ url('/en/about') }}";
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