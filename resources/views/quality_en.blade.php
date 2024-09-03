@extends('layouts.app_en')

@section('title')
<title>QUALITY - PT. DWI SELO GIRI MAS</title>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/quality') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/quality') }}"><span class="">IN</span></a>
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
              <li class="breadcrumb-item"><a href="{{ url('en') }}">HOME</a></li>
              <li class="breadcrumb-item"><a href="{{ url('en/quality') }}">QUALITY</a></li>
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

  <div class="prelative container">
    <div class="content-text text-center pt-5 wow fadeInUp">
            <span><b>achieving the highest STANDARD</b></span>
      <h3>A process towards quality perfections</h3>
      <h5>Strict process and quality control at Dwi Selo Girimas is critical for us to consistently achieve the highest standards across our Calcium Carbonate products. QC is applied at every stage within our processing operations throughout raw calcium material sourcing and selection, calcium screening and testing on the laboratory, product packing and delivery.</h5>
      <p>Our Quality Control system is integrated and handed over per division carefully so that we can offer full integrity and traceability for all our calcium carbonate products. This quality assurance model that we’ve developed, will inspects and verifies all our products, making sure that all our processes are free from defects and flaws, attaining the highest level of calcium carbonate you demand.</p>
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
                                <h4>Material Sourcing</h4>
                <p>Dwi Selo Giri Mas with it's decades of experience, has all the knowledge of where to find the best resource materials for calcium carbonate. For the best outcome, we can't sacrifice quality, therefore we will only source from the best.</p>
                              </div>
              <div class="car-item d-none data1">
                                <h4>Oven & Crush</h4>
                <p>In the making, we put the screened calcium materials for oven and crush process. All process are machine-automated in order to avoid human errors and all the automation system are being monitored all the time.</p>
                              </div>
              <div class="car-item d-none data2">
                                <h4>Final Screening</h4>
                <p>Before product packing, we always perform batch-checking to confirm and ensure every batch result is perfect.</p>
                              </div>
              <div class="car-item d-none data3">
                                <h4>Dispatch</h4>
                <p>Our quality process continues until the goods are received by the customer, therefore we are very concerned about the loading process in each of our fleets.</p>
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
                    <!-- <h4 class="titles">Why is quality so important for a Calcium Carbonate </h4> -->
          <div class="pictures py-3"><img src="{{asset('app-assets/asset/images/prog_ban_proc_ss1.jpg')}}" alt="" class="img img-fluid"></div>
          <div class="infos py-1">
            <p>As we know that calcium carbonate plays an important role to our customers as one of the main / core material for their production, when a calcium carbonate fails to meet the desired quality this will affect our customer’s production and end products, such as a bad fuel will destroy a vehicle that run it.</p>
            <div class="clear"></div>
          </div>
                  </div>
      </div>
      <div class="col-md-30">
        <div class="items d-block mx-auto">
                    <!-- <h4 class="titles">What’s the secret behind our high quality products</h4> -->
          <div class="pictures py-3"><img src="{{asset('app-assets/asset/images/prog_ban_proc_ss2.jpg')}}" alt="" class="img img-fluid"></div>
          <div class="infos py-1">
            <p>There are no secrets behind every Calcium Carbonate products that we produce. It’s just a result of getting the best raw materials and putting a strict quality policy on each step of our product processing till the very end.</p>
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
    var baseurl = "{{ url('/en/quality') }}";
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