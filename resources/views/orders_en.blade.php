@extends('layouts.app_en')

@section('title')
<title>ORDERS - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link href="{{asset('app-assets/css/main_orders.css')}}" rel="stylesheet" media="screen"/>
<!-- <link href="{{asset('app-assets/css/order/bootstrap.min.css')}}" rel="stylesheet" media="screen"/> -->
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/orders') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/orders') }}"><span class="">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/orders') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/orders') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<section>
    <div class="container">
        <span class="login100-form-title wow fadeInDown">Orders</span>
        <div class="col-sm-9 padding-right">
          <div class="features_items"><!--features_items-->
            <h2 class="title text-center">Recommendation For You</h2>
            <div class="col-sm-3">
              <div class="product-image-wrapper">
                <div class="single-products">
                  <div class="productinfo text-center">
                    <img src="{{asset('app-assets/images/product12.jpg')}}" alt="" />
                    <h2>$56</h2>
                    <p>Easy Polo Black Edition</p>
                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                  </div>
                  <div class="product-overlay">
                    <div class="overlay-content">
                      <h2>$56</h2>
                      <p>Easy Polo Black Edition</p>
                      <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!--features_items-->

          <div class="features_items"><!--features_items-->
            <h2 class="title text-center">Products List</h2>
            @foreach($produk as $data)
            <div class="col-sm-3">
              <div class="product-image-wrapper">
                <div class="single-products">
                  <div class="productinfo text-center">
                    <a href="{{ route('order_detail_en', ['kode_produk' => $data->kode_produk_komputer]) }}"><img src="{{asset('app-assets/images/' . $data->gambar)}}" alt="" /> </a>
                    <h2>Rp {{ number_format($data->harga,2,',','.') }}</h2>
                    <p>{{ $data->nama_produk }}</p>
                    <a href="{{ route('order_detail_en', ['kode_produk' => $data->kode_produk_komputer]) }}" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Buy / See Details</a>
                  </div>
                  <div class="product-overlay">
                    <div class="overlay-content">
                      <h2>Rp {{ number_format($data->harga,2,',','.') }}</h2>
                      <p>{{ $data->nama_produk }}</p>
                      <a href="{{ route('order_detail_en', ['kode_produk' => $data->kode_produk_komputer]) }}" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Buy / See Details</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div><!--features_items-->
        </div>
    </div>
  </section>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('/en/orders') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>

<script src="{{asset('app-assets/js/main_orders.js')}}"></script>
@endsection