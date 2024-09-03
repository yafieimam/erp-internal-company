@extends('layouts.app_en')

@section('title')
<title>PRODUCTS - PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/products') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/products') }}"><span class="">IN</span></a>
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
                  <li class="breadcrumb-item"><a href="{{ url('en') }}">HOME</a></li>
                  <li class="breadcrumb-item"><a href="{{ url('en/products') }}">PRODUCTS</a></li>
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
</section>
<section class="cover-above">
    <div class="prelative container">
        <div class="row pt-5 wow fadeInUp">
                        <div class="col-md-60">
                <div class="caption">
                    <div class="text1 mx-auto d-block text-center pb-4">
                        <p>our product as part of your operational</p>
                    </div>
                </div>
            </div>
            <div class="col-md-60">
                <div class="caption">
                    <div class="text2 mx-auto d-block text-center pb-4">
                        <p>Our Calcium Carbonate With You In Mind</p>
                    </div>
                </div>
            </div>
            <div class="col-md-60">
                <div class="caption">
                    <div class="text3 mx-auto d-block text-center pb-4">
                        <p>We take pride in providing high quality calcium carbonate products to our market in Surabaya, Jakarta and throuhout Indonesia. We will meet your specification and we will ensure a steady production flow by maintaining our stock priority for your long-term.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-60">
                <div class="caption">
                    <div class="text4 mx-auto d-block text-center pb-4">
                        <p>PT. DSGM (Dwi Selo Giri Mas) is one of the leading Carbonate manufacture for Ground Calcium Carbonate (GCC) in Indonesia. We obtain the raw material from our mining site of calcite lime stone in Central Java, Indonesia. The lime stone is then grinded into fine powder and classified precisely to attain the precise fineness and quality needed by our customers.</p> <br>
                        <p>Calcium Carbonate is an essential material for the producing of paint industries, chemical industries, ceramics industries, plastic industries, paper industries and so much more. A quality calcium carbonate that meets the requirements will ensure the smooth operation and production of our customers factory. As an important and integral part of the production, calcium carbonate came at a very low price - therefore there are no reasons or options for our customers to choose a lower grade of calcium carbonate. “With you in mind” as our everyday spirit and commitment, we produce the best product possible to guarantee your success.</p>
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
                        <h3>DWI SELO GIRIMAS’ CALCIUM CARBONATES ARE IMPORTANT <br> FOR INDUSTRIAL USAGE AS SHOWN IN THE DIAGRAM BELOW</h3>
            <div class="py-1"></div>
            <p>Scroll down to find more information.</p>
            <div class="py-1"></div>
            <!-- <a href="/en/home/contact" class="btn btn-link btns_tcontact_pr">CONTACT US</a> -->
                    </div>
        <div class="row wow fadeInUp">
            <div class="col-md-60">
                <div class="pt-5"></div>
                <div class="pt-4"></div>
                <div class="image">
                    <img class="mx-auto d-block" id="my_image" src="{{asset('app-assets/asset/images/diagram.png')}}" alt="diagram natriaum carbonate" usemap="#Map">
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
                        <p>Paints & Coatings</p>
                    </div>
                    <div class="isinya">
                        <p>Calcium carbonate enhance coatings performance as known as functional filler. Filler quality can improve decorative paints opacity, brightness, reflectance, workability and more. As well as on industrial coating, filler selection can make huge difference impact on gloss, viscosity and durability.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="adhesives">
                                        <div class="title-content">
                        <p>Adhesives & Sealants</p>
                    </div>
                    <div class="isinya">
                        <p>The basic material in many adhesives and sealants are Calcium Carbonate, which are impoving rheologi, bond strength, and reducing water demand. </p>
                    </div>
                        
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="ceramics-glass">
                                        <div class="title-content">
                        <p>Glass & Ceramics</p>
                    </div>
                    <div class="isinya">
                        <p>Glass are formed from certain types of rocks melt in high temperature and then cool and solidify rapidly. The function of calcium carbonate in glass production for stabilizer, modifies the viscosity and increases the durability. Other usage of calcium carbonate is reduce cost of ceramic production, because calcium carbonate is an economical source of calcium oxide which is needed as a melting agent that can improve mechanical and chemical strength and reduces shrinkage.  </p>
                    </div>    
                                        
                </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="contruction">
                                        <div class="title-content">
                        <p>Construction</p>
                    </div>
                    <div class="isinya">
                        <p>Calcium carbonate is well known for cemen based products, such as asphalt, roofing, tiles, and bricks. Calcium carbonate function in construction as stabilizer, accelerate the hydration of the cement matrix, increasing strength and durability. </p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="rubber">
                                        <div class="title-content">
                        <p>Rubber Industries</p>
                    </div>
                    <div class="isinya">
                        <p>With good selection of calcium carbonate grade, can reduce cost of rubber production and improve mixing effect, and mold release.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="water-treatment">
                                        <div class="title-content">
                        <p>Water Treatment</p>
                    </div>
                    <div class="isinya">
                        <p>In order to increase the quality of natural or treated (e.g. desalinated) water to a non-corrosive level or to a level acceptable for human consumption, needs to adjust the pH value through neutralization, (re)mineralize the water through adding (dissolving) the required amount of calcium, magnesium and carbonate minerals or reduce the water hardness (decarbonization). An unsufficient level of calcium carbonate in treated water makes it corrosive and will cause equipment and structures to deteriorate, often while not fulfilling drinking water quality requirements such as those recommended by the WHO (World Health Organization). A supersaturated solution will likely precipitate calcium carbonate, causing scale, reduced efficiency and eventually leading to system failure.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="fertilizer">
                                        <div class="title-content">
                        <p>Fertilizers</p>
                    </div>
                    <div class="isinya">
                        <p>Calcium and magnesium fertilizers were known used for fertilizers. Fertilizers help to improves soils, plant nutrition and plant protection.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="animal-feed">
                                        <div class="title-content">
                        <p>Animal Feed</p>
                    </div>
                    <div class="isinya">
                        <p>Calcium is an essential mixing element for every animal species. In addition to the basic fodder, the requirements of individual species call for the addition of Calcium carbonate to the feed ratio. The beneficial effect of using calcium carbonate as animal feed is proper development of bones and teeth, regulation of heartbeat and blood clotting, muscle contraction and nerve impulses, enzyme activation and hormone secretion, eggshell formation and quality, and milk production.</p>
                    </div>
                                    </div>
            </div>
            <div class="col-md-60">
                <div class="content" id="fishery">
                                        <div class="title-content">
                        <p>Fishery</p>
                    </div>
                    <div class="isinya">
                        <p>The aim of good pond management is to increase fish production through an improved supply of natural food such as phytoplankton and zooplankton. The supply is usually increased by fertilizing the pond water. Addition of calcium carbonate at pond is called liming, liming can be done when the pH of the pond below 6.5, very muddy, controlling disease and pests, high concentration of organic matter, and total alkalinity below 25 mg/l CaCO3.</p>
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
                                        <h5>WE PRODUCE CALCIUM CARBONATES TO YOUR REQUIREMENTS</h5>
                    <h6>Please enquire - our consultant will discuss your detailed specifications (purity, mesh, etc).</h6>
                    <a href="{{ url('en/contact') }}"><p>CONTACT US</p></a>
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
    var baseurl = "{{ url('/en/products') }}";
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