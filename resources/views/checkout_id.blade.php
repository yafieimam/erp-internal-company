@extends('layouts.app_id')

@section('title')
<title>CHECKOUT - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link href="{{asset('app-assets/css/main_orders.css')}}" rel="stylesheet" media="screen"/>
<!-- <link href="{{asset('app-assets/css/order/bootstrap.min.css')}}" rel="stylesheet" media="screen"/> -->
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/checkout') }}"><span class="">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/checkout') }}"><span class="active">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/checkout') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/checkout') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<section>
    <div class="container">
      <span class="login100-form-title wow fadeInDown">Checkout</span>
      <div class="row">
        <div class="col-sm-9 padding-right">
          <div class="product-details"><!--product-details-->
            <div class="col-sm-10">
              <div class="view-product">
                <div class="product-information"><!--/product-information-->
                  <h2>Alamat Pengiriman</h2>
                  <hr>
                  <h2>{{ $data_order->custname }} (Primary Address)</h2>
                  <p><b>Telepon:</b> {{ $data_order->phone }}</p>
                  <p align="justify"><b>Alamat:</b> {{ $data_order->address }}</p>
                  <p><b>Kota:</b> {{ $data_order->city }}</p>
                  <button type="button" class="btn btn-fefault cart checkout" style="margin: 20px 10px 0 0;">
                    Pilih Alamat Lain
                  </button>
                  <button type="button" class="btn btn-fefault cart checkout" style="margin: 20px 10px 0 0;">
                    Kirim ke Beberapa Alamat
                  </button>
                </div><!--/product-information-->
              </div>
            </div>
            <div class="col-sm-4">
              <div class="view-product">
                <div class="product-information" style="padding-left: 10px; padding-right: 10px;"><!--/product-information-->
                  <h2>Ringkasan Belanja</h2>
                  <hr>
                  <p><b>Total Harga: {{ number_format($data_order->total_harga,2,',','.') }}</b></p>
                  <hr>
                  <p><b>Total Tagihan: {{ number_format($data_order->total_harga,2,',','.') }}</b></p>
                  <button type="button" class="btn btn-fefault cart" style="width: 100%; margin: 20px 10px 0 0;">
                    Pilih Metode Pembayaran
                  </button>
                </div><!--/product-information-->
              </div>
            </div>
          </div><!--/product-details-->
          <div class="product-details"><!--product-details-->
            <div class="col-sm-10">
              <div class="view-product">
                <div class="product-information"><!--/product-information-->
                  <h2>Pesanan Anda</h2>
                  <hr>
                  <div class="your-product" style="float: left;">
                    <img src="{{asset('app-assets/images/product12.jpg')}}" alt="" />
                  </div>
                  <div style="float: left; width: 40%;">
                    <p><b>{{ $data_order->nama_produk }}</b></p>
                    <p><b>Rp {{ number_format($data_order->harga,2,',','.') }}</b></p>
                    <p><b>{{ $data_order->kuantitas }} TON</b></p>
                  </div>
                  <div style="float: left; margin-top: 20px;">
                    <p><b>Pilih Pengiriman:</b></p>
                    <div class="select-list" style="margin: 0;">
                      <select class="dropdown-baru" style="padding-right: 100px;" name="pengiriman" id="pengiriman">
                        <option class="tipeName" value="" selected>Pilih Pengiriman</option>
                        <option class="tipeName" value="1">DSGM</option>
                        <option class="tipeName" value="2">Ambil Sendiri</option>
                        <option class="tipeName" value="3">Ekspedisi</option>
                      </select>
                    </div>
                    <input type="text" class="form-control" id="pengiriman_lain" name="pengiriman_lain" placeholder="Pengiriman Lainnya" style="display: none; margin-top: 10px;">
                  </div>
                </div><!--/product-information-->
              </div>
            </div>
          </div><!--/product-details-->
        </div>
      </div>
    </div>
  </section>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('id/checkout') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>

<script src="{{asset('app-assets/js/main_orders.js')}}"></script>
<script src="{{asset('app-assets/js/bootstrap.min.js')}}"></script>
@endsection