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
                <a href="{{ url('id/profile') }}">
                    <p>HALO, {{ explode(' ',trim(strtoupper(Session::get('name'))))[0] }}</p>
                </a>
            </div>

            <div class="login">
                <a href="#" data-toggle="dropdown" id="dropdownSettings" aria-haspopup="true" aria-expanded="false">
                    <p>PENGATURAN <i class="fa fa-caret-down"></i></p>
                </a>
                <ul aria-labelledby="dropdownSettings" class="dropdown-menu border-1 shadow">
                  <li><a href="{{ url('id/update/profile') }}" class="dropdown-item">Edit Akun</a></li>
                  <li><a href="{{ url('id/edit_password') }}" class="dropdown-item">Edit Password</a></li>
                </ul>
            </div>

            <div class="login">
                <a href="{{ url('/logoutId') }}">
                    <p>KELUAR</p>
                </a>
            </div>

            @yield('nav')
            
            <?php
            }else{
            ?>
            <div class="login">
                <a href="{{ url('id/contact') }}">
                    <p>KONTAK</p>
                </a>
            </div>

            <div class="login">
                <a href="{{ url('id/login') }}" data-toggle="tooltip" title="login untuk masuk">
                    <p>MASUK</p>
                </a>
                <!-- <span class="tiploginid">Login untuk masuk</span> -->
            </div>

            <div class="login">
                <a href="{{ url('id/register') }}" data-toggle="tooltip" title="Daftar untuk buat akun">
                    <p>DAFTAR</p>
                </a>
                <!-- <span class="tipregisterid">Daftar untuk buat akun</span> -->
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
                    <a href="{{ url('id') }}">
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
              <li class="list-inline-item"><a href="{{ url('id') }}">Beranda</a></li>
              <li class="list-inline-item"><a href="{{ url('id/about') }}">Tentang Kami</a></li>
              <li class="list-inline-item"><a href="{{ url('id/products') }}">Produk</a></li>
              <div class="dropdown"><li class="list-inline-item"><a href="#">Order <i class="fa fa-caret-down"></i></a></li>
              <div class="dropdown-content">
                <a href="{{ url('id/orders') }}">Form Pesanan</a>
                <a href="{{ url('id/orders/list') }}">List Pesanan</a>
                <a href="{{ url('id/form_complaint') }}">Form Komplain</a>
              </div>
              </div>
              <li class="list-inline-item"><a href="{{ url('id/quality') }}">Kualitas</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">Events</a></li> -->
              <li class="list-inline-item"><a href="{{ url('id/career') }}">Karir</a></li>
              <li class="list-inline-item"><a href="{{ url('id/contact') }}">Kontak</a></li>
              <?php
              }else{
              ?>
              <li class="list-inline-item"><a href="{{ url('id') }}">Beranda</a></li>
              <li class="list-inline-item"><a href="{{ url('id/about') }}">Tentang Kami</a></li>
              <li class="list-inline-item"><a href="{{ url('id/products') }}">Produk</a></li>
              <li class="list-inline-item"><a href="{{ url('id/quality') }}">Kualitas</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">Events</a></li> -->
              <li class="list-inline-item"><a href="{{ url('id/career') }}">Karir</a></li>
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
          <a href="{{ url('id') }}">
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
              <li class="list-inline-item"><a href="{{ url('id') }}">BERANDA</a></li>
              <li class="list-inline-item"><a href="{{ url('id/about') }}">TENTANG KAMI</a></li>
              <li class="list-inline-item"><a href="{{ url('id/products') }}">PRODUK</a></li>
              <div class="dropdown"><li class="list-inline-item"><a href="#">ORDER <i class="fa fa-caret-down"></i></a></li>
              <div class="dropdown-content">
                <a href="{{ url('id/orders') }}">FORM PESANAN</a>
                <a href="{{ url('id/orders/list') }}">LIST PESANAN</a>
                <a href="{{ url('id/form_complaint') }}">FORM KOMPLAIN</a>
              </div>
              </div>
              <li class="list-inline-item"><a href="{{ url('id/quality') }}">KUALITAS</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">EVENTS</a></li> -->
              <li class="list-inline-item"><a href="{{ url('id/career') }}">KARIR</a></li>
              <li class="list-inline-item"><a href="{{ url('id/contact') }}">KONTAK</a></li>
              <li class="list-inline-item"><a href="{{ url('id/profile') }}">PROFIL</a></li>
              <li class="list-inline-item"><a href="{{ url('/logoutId') }}">KELUAR</a></li>
              <?php
              }else{
              ?>
              <li class="list-inline-item"><a href="{{ url('id') }}">BERANDA</a></li>
              <li class="list-inline-item"><a href="{{ url('id/about') }}">TENTANG KAMI</a></li>
              <li class="list-inline-item"><a href="{{ url('id/products') }}">PRODUK</a></li>
              <li class="list-inline-item"><a href="{{ url('id/quality') }}">KUALITAS</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">EVENTS</a></li> -->
              <li class="list-inline-item"><a href="{{ url('id/career') }}">KARIR</a></li>
              <li class="list-inline-item"><a href="{{ url('id/contact') }}">KONTAK</a></li>
              <li class="list-inline-item"><a href="{{ url('id/login') }}">MASUK</a></li>
              <li class="list-inline-item"><a href="{{ url('id/register') }}">DAFTAR</a></li>
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
  <a class="navbar-brand" href="{{ url('id') }}"><img src="{{asset('app-assets/asset/images/logo-header.png')}}" alt="" class="img img-fluid"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
              <?php
              if(Session::get('login')){
              ?>
              <li class="list-inline-item"><a href="{{ url('id') }}">BERANDA</a></li>
              <li class="list-inline-item"><a href="{{ url('id/about') }}">TENTANG KAMI</a></li>
              <li class="list-inline-item"><a href="{{ url('id/products') }}">PRODUK</a></li>
              <li class="list-inline-item"><div class="dropdown"><a href="#">ORDER <i class="fa fa-caret-down"></i></a>
              <div class="dropdown-content" style="position: relative; margin-top: 10px;">
                <a href="{{ url('id/orders') }}">FORM PESANAN</a>
                <a href="{{ url('id/orders/list') }}">LIST PESANAN</a>
                <a href="{{ url('id/form_complaint') }}">FORM KOMPLAIN</a>
              </div>
              </div>
              </li>
              <li class="list-inline-item"><a href="{{ url('id/quality') }}">KUALITAS</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">EVENTS</a></li> -->
              <li class="list-inline-item"><a href="{{ url('id/career') }}">KARIR</a></li>
              <li class="list-inline-item"><a href="{{ url('id/contact') }}">KONTAK</a></li>
              <li class="list-inline-item"><a href="{{ url('id/profile') }}">PROFIL</a></li>
              <li class="list-inline-item"><a href="{{ url('/logoutId') }}">KELUAR</a></li>
              <?php
              }else{
              ?>
              <li class="list-inline-item"><a href="{{ url('id') }}">BERANDA</a></li>
              <li class="list-inline-item"><a href="{{ url('id/about') }}">TENTANG KAMI</a></li>
              <li class="list-inline-item"><a href="{{ url('id/products') }}">PRODUK</a></li>
              <li class="list-inline-item"><a href="{{ url('id/quality') }}">KUALITAS</a></li>
              <!-- <li class="list-inline-item"><a href="/en/home/news">EVENTS</a></li> -->
              <li class="list-inline-item"><a href="{{ url('id/career') }}">KARIR</a></li>
              <li class="list-inline-item"><a href="{{ url('id/contact') }}">KONTAK</a></li>
              <li class="list-inline-item"><a href="{{ url('id/login') }}">MASUK</a></li>
              <li class="list-inline-item"><a href="{{ url('id/register') }}">DAFTAR</a></li>
              <?php
              }
              ?>
        @yield('nav_footer')
    </ul>
  </div>
  </nav>
</header>