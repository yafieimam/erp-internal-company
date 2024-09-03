@extends('layouts.app_id')

@section('title')
<title>Detail Produk {{ $produk->nama_produk }} - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link href="{{asset('app-assets/css/main_orders.css')}}" rel="stylesheet" media="screen"/>
<!-- <link href="{{asset('app-assets/css/order/bootstrap.min.css')}}" rel="stylesheet" media="screen"/> -->
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ route('order_detail_en', ['kode_produk' => $produk->kode_produk_komputer]) }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ route('order_detail_id', ['kode_produk' => $produk->kode_produk_komputer]) }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ route('order_detail_en', ['kode_produk' => $produk->kode_produk_komputer]) }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ route('order_detail_id', ['kode_produk' => $produk->kode_produk_komputer]) }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<section>
    <div class="container">
      <div class="row">
        <div class="col-sm-9 padding-right">
          <div class="product-details"><!--product-details-->
            <div class="col-sm-5">
              <div class="view-product">
                <img src="{{asset('app-assets/images/' . $produk->gambar)}}" alt="" />
              </div>
            </div>
            <div class="col-sm-7">
              <div class="product-information"><!--/product-information-->
                @if ($errors->any())
                  <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
                <form method="post" action="{{ url('/id/order_detail/process') }}">
                {{ csrf_field() }}
                <h2>{{ $produk->nama_produk }}</h2>
                <p>Kode Produk: {{ $produk->kode_produk_customer }}</p>
                <span>
                  <span>Rp {{ number_format($produk->harga,2,',','.') }}</span>
                  <label>Jumlah Pesan:</label>
                  <input type="hidden" name="kode_produk" id="kode_produk" value="{{ $produk->kode_produk_komputer }}" />
                  <input type="hidden" name="custid" id="custid" value="{{ Session::get('custid') }}" />
                  <input type="number" value="8" />
                  <label style="margin-left: 5px;">TON</label>
                </span>
                <p><b>Stok:</b> 999 TON</p>
                <p><b>Merek:</b> PT. Dwi Selo Giri Mas</p>
                <?php
                if(Session::get('login')){
                ?>
                <button type="submit" class="btn btn-fefault cart" id="checkout" style="width: 80%; margin: 20px 10px 0 0;">
                  <i class="fa fa-shopping-cart"></i>
                    Beli Sekarang
                </button>
                <?php
                }else{
                ?>
                <button type="button"  class="btn btn-fefault cart" style="width: 80%; margin: 20px 10px 0 0;" disabled="disabled">
                    Harus Login untuk Membeli
                </button>
                <?php
                }
                ?>
                </form>
              </div><!--/product-information-->
            </div>
          </div><!--/product-details-->
          <div class="category-tab shop-details-tab"><!--category-tab-->
            <div class="col-sm-12">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#details" data-toggle="tab">Detail Produk</a></li>
                <li><a href="#deskripsi" data-toggle="tab">Deskripsi</a></li>
                <li><a href="#reviews" data-toggle="tab">Ulasan</a></li>
              </ul>
            </div>
            <div class="tab-content">
              <div class="tab-pane fade active in" id="details" style="padding-right: 25px; padding-left: 25px;">
                <div class="col-sm-12">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Tampilan Fisik</th>
                        <td>{{$produk->physical_appearance}}</td>
                      </tr>
                      <tr>
                        <th>Tingkat Keputihan (%)</th>
                        <td>{{$produk->whiteness}}</td>
                      </tr>
                      <tr>
                        <th>Residu pada Mesh 350 (%)</th>
                        <td>{{$produk->residue}}</td>
                      </tr>
                      <tr>
                        <th>Rata-rata Diameter Partikel (micron)</th>
                        <td>{{$produk->mean_particle_diameter}}</td>
                      </tr>
                      <tr>
                        <th>Kelembaban (%)</th>
                        <td>{{$produk->moisture}}</td>
                      </tr>
                      <tr>
                        <th>Mesh</th>
                        <td>{{$produk->mesh}}</td>
                      </tr>
                      <tr>
                        <th>Standar Pengemasan</th>
                        <td>{{$produk->standard_packaging}}</td>
                      </tr>
                      <tr>
                        <th>Berat</th>
                        <td>{{$produk->weight}}</td>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              
              <div class="tab-pane fade" id="deskripsi" style="padding-right: 25px; padding-left: 25px;">
                <div class="col-sm-12" style="padding-bottom: 20px;">
                  <p align="justify">{{ $produk->deskripsi }}</p>
                </div>
              </div>
              
              <div class="tab-pane fade" id="reviews" style="padding-right: 25px; padding-left: 25px;">
                <div class="col-sm-12" style="padding-top: 30px;">
                  <ul>
                    <li><a href=""><i class="fa fa-user"></i>EUGEN</a></li>
                    <li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
                    <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>
                  </ul>
                  <p align="justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
                <div class="col-sm-12" style="padding-top: 30px;">
                  <ul>
                    <li><a href=""><i class="fa fa-user"></i>EUGEN</a></li>
                    <li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
                    <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>
                  </ul>
                  <p align="justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
                <div class="col-sm-12" style="padding-top: 30px;">
                  <ul>
                    <li><a href=""><i class="fa fa-user"></i>EUGEN</a></li>
                    <li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
                    <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>
                  </ul>
                  <p align="justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
              </div>
            </div>
          </div><!--/category-tab-->
        </div>
      </div>
    </div>
  </section>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ route('order_detail_id', ['kode_produk' => $produk->kode_produk_komputer]) }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>

<script src="{{asset('app-assets/js/main_orders.js')}}"></script>
<script src="{{asset('app-assets/js/bootstrap.min.js')}}"></script>
@endsection