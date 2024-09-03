<header class="head ">
  <div class="prelative container cont-header mx-auto wow fadeInDown">
    <div class="tops_nheaders mx-auto">
      <div class="row">
        <div class="col-md-30"></div>
        <div class="col-md-30 text-right">
            <div class="box-telp">
                <p>
                  <a href="#"><span><img src="{{asset('app-assets/asset/images/phone-white.png')}}" alt=""></span> 031-8913030</a>
                </p>
            </div>
            <?php
            if(Session::get('login')){
            ?>
            <div class="login">
                <a href="{{ url('en/profile') }}">
                    <p>HELLO, {{ explode(' ',trim(strtoupper(Session::get('name'))))[0] }} {{ explode(' ',trim(strtoupper(Session::get('name'))))[1] }}</p>
                </a>
            </div>

            <div class="login">
                <a href="#" data-toggle="dropdown" id="dropdownSettings" aria-haspopup="true" aria-expanded="false">
                    <p>SETTINGS <i class="fa fa-caret-down"></i></p>
                </a>
                <ul aria-labelledby="dropdownSettings" class="dropdown-menu border-1 shadow">
                  <li><a href="{{ url('en/update/profile') }}" class="dropdown-item">Edit Account</a></li>
                  <li><a href="{{ url('en/edit_password') }}" class="dropdown-item">Edit Password</a></li>
                </ul>
            </div>

            <div class="login">
                <a href="{{ url('/logoutEn') }}">
                    <p>LOG OUT</p>
                </a>
            </div>

            @yield('nav')
            
            <?php
            }else{
            ?>
            <div class="login">
                <a href="{{ url('en/contact') }}">
                    <p>CONTACT</p>
                </a>
            </div>

            <div class="login">
                <a href="{{ url('en/login') }}" data-toggle="tooltip" title="Login to enter the web">
                    <p>LOG IN</p>
                </a>
                <!-- <span class="tiploginen">Login to enter the web</span> -->
            </div>

            <div class="login">
                <a href="{{ url('en/register') }}" data-toggle="tooltip" title="Register to create new account">
                    <p>SIGN UP</p>
                </a>
                <!-- <span class="tipregisteren">Register to create new account</span> -->
            </div>

            @yield('nav')
            <?php
            }
            ?>

        </div>
      </div>
      <div class="clear clearfix"></div>
    </div>
    <div class="bottoms_nheaders">
      <div class="row">
        <div class="col-md-30">
            <div class="logo-header">
                <div class="logo">
                    <a href="{{ url('en') }}">
                      <img src="{{asset('app-assets/asset/images/logo-header.png')}}" alt="" class="img img-fluid">
                    </a>
                </div>
                <div class="lines-nmiddle mx-3"></div>
                <div class="ind-man">
                    <p>INDONESIA MANUFACTURING FACTORY OF <br><span>CALCIUM CARBONATE</span></p>
                </div>
                <div class="since"><img src="{{asset('app-assets/asset/images/since.png')}}" alt=""></div>
            </div>
        </div>
        <div class="col-md-30">
          <div class="menu-block-bottom text-right cmenubot">
            <ul class="list-inline text-right">
              <?php
              if(Session::get('login')){
              ?>
              <li class="list-inline-item"><a href="{{ url('en') }}">Home</a></li>
              <li class="list-inline-item"><a href="{{ url('en/about') }}">About Us</a></li>
              <li class="list-inline-item"><a href="{{ url('en/products') }}">Products</a></li>
              <div class="dropdown"><li class="list-inline-item"><a href="#">Orders <i class="fa fa-caret-down"></i></a></li>
              <div class="dropdown-content">
                <a href="{{ url('en/orders') }}">Form Orders</a>
                <a href="{{ url('en/orders/list') }}">Orders List</a>
                <a href="{{ url('en/form_complaint') }}">Form Complaint</a>
              </div>
              </div>
              <li class="list-inline-item"><a href="{{ url('en/quality') }}">Quality</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">Events</a></li> -->
              <li class="list-inline-item"><a href="{{ url('en/career') }}">Career</a></li>
              <li class="list-inline-item"><a href="{{ url('en/contact') }}">Contact</a></li>
              <?php
              }else{
              ?>
              <li class="list-inline-item"><a href="{{ url('en') }}">Home</a></li>
              <li class="list-inline-item"><a href="{{ url('en/about') }}">About Us</a></li>
              <li class="list-inline-item"><a href="{{ url('en/products') }}">Products</a></li>
              <li class="list-inline-item"><a href="{{ url('en/quality') }}">Quality</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">Events</a></li> -->
              <li class="list-inline-item"><a href="{{ url('en/career') }}">Career</a></li>
              <?php
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="clear clearfix"></div>
    </div>
    <!-- End inners head -->
  </div>
</header>

<section id="myAffix" class="header-affixs affix-top">
  <!-- <div class="clear height-15"></div> -->
  <div class="prelative container cont-header mx-auto">
    <div class="row">
      <div class="col-md-15 col-sm-15">
        <div class="lgo_web_headrs_wb">
          <a href="{{ url('en') }}">
            <img src="{{asset('app-assets/asset/images/logo-header-sticky.png')}}" alt="" class="img img-fluid">
          </a>
        </div>
      </div>
      <div class="col-md-45 col-sm-45">
        <div class="text-right"> 
          <div class="menu-taffix">
            <ul class="list-inline d-inline">
              <?php
              if(Session::get('login')){
              ?>
              <li class="list-inline-item"><a href="{{ url('en') }}">HOME</a></li>
              <li class="list-inline-item"><a href="{{ url('en/about') }}">ABOUT US</a></li>
              <li class="list-inline-item"><a href="{{ url('en/products') }}">PRODUCTS</a></li>
              <div class="dropdown"><li class="list-inline-item"><a href="#">ORDERS <i class="fa fa-caret-down"></i></a></li>
              <div class="dropdown-content">
                <a href="{{ url('en/orders') }}">FORM ORDERS</a>
                <a href="{{ url('en/orders/list') }}">ORDERS LIST</a>
                <a href="{{ url('en/form_complaint') }}">FORM COMPLAINT</a>
              </div>
              </div>
              <li class="list-inline-item"><a href="{{ url('en/quality') }}">QUALITY</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">EVENTS</a></li> -->
              <li class="list-inline-item"><a href="{{ url('en/career') }}">CAREER</a></li>
              <li class="list-inline-item"><a href="{{ url('en/contact') }}">CONTACT</a></li>
              <li class="list-inline-item"><a href="{{ url('en/profile') }}">PROFILE</a></li>
              <li class="list-inline-item"><a href="{{ url('/logoutEn') }}">LOG OUT</a></li>
              <?php
              }else{
              ?>
              <li class="list-inline-item"><a href="{{ url('en') }}">HOME</a></li>
              <li class="list-inline-item"><a href="{{ url('en/about') }}">ABOUT US</a></li>
              <li class="list-inline-item"><a href="{{ url('en/products') }}">PRODUCTS</a></li>
              <li class="list-inline-item"><a href="{{ url('en/quality') }}">QUALITY</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">EVENTS</a></li> -->
              <li class="list-inline-item"><a href="{{ url('en/career') }}">CAREER</a></li>
              <li class="list-inline-item"><a href="{{ url('en/contact') }}">CONTACT</a></li>
              <li class="list-inline-item"><a href="{{ url('en/login') }}" data-toggle="tooltip" title="Login to enter the web">LOG IN</a></li>
              <li class="list-inline-item"><a href="{{ url('en/register') }}" data-toggle="tooltip" title="Register to create new account">SIGN UP</a></li>
              <?php
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
</section>

<header class="header-mobile homepage_head">
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <a class="navbar-brand" href="{{ url('en') }}"><img src="{{asset('app-assets/asset/images/logo-header.png')}}" alt="" class="img img-fluid"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
            <?php
              if(Session::get('login')){
              ?>
              <li class="list-inline-item"><a href="{{ url('en') }}">HOME</a></li>
              <li class="list-inline-item"><a href="{{ url('en/about') }}">ABOUT US</a></li>
              <li class="list-inline-item"><a href="{{ url('en/products') }}">PRODUCTS</a></li>
              <li class="list-inline-item"><div class="dropdown"><a href="#">ORDERS <i class="fa fa-caret-down"></i></a>
              <div class="dropdown-content" style="position: relative; margin-top: 10px;">
                <a href="{{ url('en/orders') }}">FORM ORDERS</a>
                <a href="{{ url('en/orders/list') }}">ORDERS LIST</a>
                <a href="{{ url('en/form_complaint') }}">FORM COMPLAINT</a>
              </div>
              </div>
              </li>
              <li class="list-inline-item"><a href="{{ url('en/quality') }}">QUALITY</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">EVENTS</a></li> -->
              <li class="list-inline-item"><a href="{{ url('en/career') }}">CAREER</a></li>
              <li class="list-inline-item"><a href="{{ url('en/contact') }}">CONTACT</a></li>
              <li class="list-inline-item"><a href="{{ url('en/profile') }}">PROFILE</a></li>
              <li class="list-inline-item"><div class="dropdown"><a href="#">SETTINGS <i class="fa fa-caret-down"></i></a>
              <div class="dropdown-content" style="position: relative; margin-top: 10px;">
                <a href="{{ url('en/update/profile') }}">EDIT PROFILE</a>
                <a href="{{ url('en/edit_password') }}">EDIT PASSWORD</a>
              </div>
              </div>
              </li>
              <li class="list-inline-item"><a href="{{ url('/logoutEn') }}">LOG OUT</a></li>
              <?php
              }else{
              ?>
              <li class="list-inline-item"><a href="{{ url('en') }}">HOME</a></li>
              <li class="list-inline-item"><a href="{{ url('en/about') }}">ABOUT US</a></li>
              <li class="list-inline-item"><a href="{{ url('en/products') }}">PRODUCTS</a></li>
              <li class="list-inline-item"><a href="{{ url('en/quality') }}">QUALITY</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">EVENTS</a></li> -->
              <li class="list-inline-item"><a href="{{ url('en/career') }}">CAREER</a></li>
              <li class="list-inline-item"><a href="{{ url('en/contact') }}">CONTACT</a></li>
              <li class="list-inline-item"><a href="{{ url('en/login') }}" data-toggle="tooltip" title="Login to enter the web">LOG IN</a></li>
              <li class="list-inline-item"><a href="{{ url('en/register') }}" data-toggle="tooltip" title="Register to create new account">SIGN UP</a></li>
              <?php
              }
              ?>
        @yield('nav_footer')
    </ul>
  </div>
  </nav>
</header>