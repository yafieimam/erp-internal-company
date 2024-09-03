@extends('layouts.app_en')

@section('title')
<title>FORM ORDER - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/> -->
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
<link href="{{asset('app-assets/css/main_orders.css')}}" rel="stylesheet" media="screen"/>
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendor/select2/select2.min.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/form_order/bootstrap.min.css')}}">
<style type="text/css">
  .show_saldo {
    width: 100%;
    margin-top: .25rem;
    font-size: 80%;
    color: #dc3545;
  }
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-sm-"] {
      flex: none !important; 
      max-width: 100% !important;
      padding-left: 0;
      padding-right: 0;
      margin-bottom: 10px;
    }
    .container {
      margin-top: 0px !important;
    }
    #btn_send_several_address, #btn_choose_another_address, #btn_choose_more_delivery_date, #btn_cancel_more_delivery_date {
      width: 100%;
      margin: 10px 0px 0 0 !important;
    }
    /*.product-information {
      padding-bottom: 0px;
      padding-top: 0px;
    }*/
    .div-btn-orders {
      padding-right: 25px; 
      padding-left: 25px;
    }
    .modal_history_address, .modal_choose_address {
      width: auto !important;
    }
    .select2-container .select2-selection--single, .select2-search--dropdown, .select2-search--dropdown {
      width: 100%;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      margin-right: 0px;
    }
    .list-address {
      padding-bottom: 50px !important;
    }
  }
</style>
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
<!-- <form action="" method="post">
{{ csrf_field() }} -->
<section>
    <div class="container">
      <span class="login100-form-title wow fadeInDown">Form Order</span>
      <div class="row">
        <div class="col-sm-12 padding-right">
          <div class="product-details"><!--product-details-->
            <div class="col-sm-6">
              <div class="view-product">
                <div class="product-information" style="padding-bottom: 74px;"><!--/product-information-->
                  <h2>Customer Order</h2>
                  <hr>
                  <h2>{{ $customer->custname }}</h2>
                  <p><b>Phone:</b> {{ $customer->phone }}</p>
                  <p align="justify"><b>Address:</b> {{ $customer->address }}</p>
                  <p><b>City:</b> {{ $customer->name_city }}</p>
                  <p><b>NPWP:</b> {{ $customer->npwp }}</p>
                  @if($customer->crd == 1)
                  <p><b>Credit Payment:</b> {{ $customer->crd }} Day Cash / Transfer</p>
                  @else
                  <p><b>Credit Payment:</b> {{ $customer->crd }} Days</p>
                  @endif
                </div><!--/product-information-->
              </div>
            </div>
            <div class="col-sm-6">
              <div class="view-product">
                <div class="product-information"><!--/product-information-->
                  <input type="hidden" name="kode_alamat" id="kode_alamat" value="{{ Session::get('kode_alamat') }}">
                  <input type="hidden" name="name" id="name" value="{{ Session::get('name_receive') }}">
                  <input type="hidden" name="phone" id="phone" value="{{ Session::get('phone_receive') }}">
                  <input type="hidden" name="address" id="address" value="{{ Session::get('address_receive') }}">
                  <input type="hidden" name="city" id="city" value="{{ Session::get('city_receive') }}">
                  <h2>Customer Receive</h2>
                  <hr>
                  <p id="input_several_address"></p>
                  <div id="div_view_address_several"></div>
                  <div id="div_view_address">
                    <h2>{{ Session::get('name_receive') }}</h2>
                    <p><b>Phone:</b> {{ Session::get('phone_receive') }}</p>
                    <p align="justify"><b>Address:</b> {{ Session::get('address_receive') }}</p>
                    <p><b>City:</b> {{ Session::get('name_city_receive') }}</p>
                  </div>
                  <a href="#" id="btn_send_several_address" data-toggle="modal" data-target="#modal_several_address" class="btn btn-fefault cart checkout float-right" style="margin: 20px 10px 0 0; color:#fff;">
                    Send To Several Addresses
                  </a>
                  <a href="#" id="btn_choose_another_address" data-toggle="modal" data-target="#modal_history_address" class="btn btn-fefault cart checkout float-right" style="margin: 20px 10px 0 0; color:#fff;">
                    Choose Another Address
                  </a>
                  <a href="#" id="btn_choose_more_delivery_date" class="btn btn-fefault cart checkout float-right" style="margin: 20px 10px 0 0; color:#fff;">
                    Choose More Delivery Date
                  </a>
                  <a href="#" id="btn_cancel_more_delivery_date" class="btn btn-danger cart checkout float-right" style="margin: 20px 10px 0 0; color:#fff; display: none;">
                    Cancel More Delivery Date
                  </a>
                </div><!--/product-information-->
              </div>
            </div>
          </div><!--/product-details-->
          <div class="product-details"><!--product-details-->
            <div class="col-sm-12">
              <div class="view-product">
                <div class="product-information"><!--/product-information-->
                  <div class="col-sm-6" id="div_delivery_date">
                    <div class="form-group validate-input" data-validate = "Delivery Date is required">
                      <label for="tanggal_kirim" class="required">Delivery Date</label>
                      <input class="form-control" type="text" name="tanggal_kirim" id="tanggal_kirim" placeholder="Delivery Date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-start-date="0d" autocomplete="off" data-date-autoclose="true"/>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="nomor_po">PO Number</label>
                      <input class="form-control" type="text" name="nomor_po" id="nomor_po" placeholder="PO Number" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form id="productsform" name="productsform" method="post" action="javascript:void(0)">
          <div class="product-details"><!--product-details-->
            <div class="col-sm-12">
              <div class="view-product">
                <div class="product-information"><!--/product-information-->
                  <div class="row">
                    <div class="col-sm-6" id="div_select_address" style="display: none;">
                      <div class="form-group">
                        <div class="form-select">
                          <div class="label-flex">
                            <label for="products" class="required">To Address?</label>
                          </div>
                          <div style="margin-bottom: 0px;">
                            <select class="dropdown-baru" name="select_address_products" id="select_address_products">
                              <?php $i = 0; ?>
                              @foreach($alamat_several as $alamat)
                              <?php $i++; ?>
                              @if ($loop->first)
                              <option class="tipeName" value="{{ $alamat->id_alamat_receive }}" selected> Address {{ $i }} </option>
                              @else
                              <option class="tipeName" value="{{ $alamat->id_alamat_receive }}"> Address {{ $i }} </option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6" id="div_delivery_date_products" style="display: none;">
                      <div class="form-group">
                        <label for="tanggal_kirim" class="required">Delivery Date</label>
                        <input class="form-control" type="text" name="tanggal_kirim_products" id="tanggal_kirim_products" placeholder="Delivery Date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-start-date="0d" autocomplete="off" data-date-autoclose="true"/>
                      </div>
                    </div>
                  </div>
                  <?php
                  if(!$rekomendasi_produk->isEmpty()){
                  ?>
                  <div id="div-exist-products">
                    <div class="col-sm-5">
                      <input class="form-control" type="hidden" name="custid" id="custid" value="{{ Session::get('custid') }}" />
                      <div class="form-group">
                        <div class="form-select">
                          <div class="label-flex">
                            <label for="products" class="required">Products</label>
                          </div>
                          <div style="margin-bottom: 0px;">
                            <select class="dropdown-baru" name="products" id="products">
                              <option class="tipeName" value="">Products</option>
                              @foreach($rekomendasi_produk as $rekom)
                              @if ($loop->first)
                              <option class="tipeName" value="{{ $rekom->kode_produk }}" selected> {{ $rekom->nama_produk }}</option>
                              @else
                              <option class="tipeName" value="{{ $rekom->kode_produk }}"> {{ $rekom->nama_produk }}</option>
                              @endif
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                  }else{
                  ?>
                  <div class="col-sm-5">
                    <input class="form-control" type="hidden" name="custid" id="custid" value="{{ Session::get('custid') }}" />
                    <div class="form-group">
                      <div class="form-select">
                        <div class="label-flex">
                          <label for="products" class="required">Products</label>
                        </div>
                        <div style="margin-bottom: 0px;">
                          <select class="dropdown-baru" name="products" id="products">
                            <option class="tipeName" value="" selected>Products</option>
                            @foreach($products as $produk)
                            <option class="tipeName" value="{{ $produk->kode_produk }}"> {{ $produk->nama_produk }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                  }
                  if(!$rekomendasi_qty->isEmpty()){
                  ?>
                  <div id="div-exist-qty">
                    <div class="col-sm-3">
                      <div class="form-group">
                        <div class="form-select">
                          <div class="label-flex">
                            <label for="quantity" class="required">Quantity (KG)</label>
                          </div>
                          <div style="margin-bottom: 0px;">
                            <select class="dropdown-baru" name="quantity" id="quantity">
                              <option class="tipeName" value="">Quantity (KG)</option>
                              @foreach($rekomendasi_qty as $rekom)
                              @if ($loop->first)
                              <option class="tipeName" value="{{ $rekom->quantity }}" selected> {{ $rekom->quantity }}</option>
                              @else
                              <option class="tipeName" value="{{ $rekom->quantity }}"> {{ $rekom->quantity }}</option>
                              @endif
                              @endforeach
                            </select>
                            <span class="show_saldo" id="show_saldo"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group">
                        <label for="btn-products"></label>
                        <input class="btn btn-success" type="submit" name="btn-products" id="btn-products" value="Add" style="margin-top: 5px;"/>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="form-group">
                        <label for="btn-products"></label>
                        <input class="btn btn-primary" type="button" name="btn-new-products" id="btn-new-products" value="New Products" style="margin-top: 5px;"/>
                      </div>
                    </div>
                  </div>
                  <?php
                  }else{
                  ?>
                  <div class="col-sm-5">
                    <div class="form-group">
                      <label for="quantity" class="required">Quantity (KG)</label>
                      <input class="form-control" type="text" name="quantity" id="quantity" placeholder="Quantity (KG)" />
                      <span class="show_saldo" id="show_saldo"></span>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label for="btn-products"></label>
                      <input class="btn btn-success" type="submit" name="btn-products" id="btn-products" value="Add" style="margin-top: 5px;"/>
                    </div>
                  </div>
                  <?php
                  }
                  ?>
                  </form>
                  <div id="div-new-products" style="display: none;">
                    <form id="newproductsform" name="newproductsform" method="post" action="javascript:void(0)">
                      <div class="col-sm-5">
                        <input class="form-control" type="hidden" name="new_custid" id="new_custid" value="{{ Session::get('custid') }}" />
                        <div class="form-group">
                          <div class="form-select">
                            <div class="label-flex">
                              <label for="new_products" class="required">Products</label>
                            </div>
                            <div style="margin-bottom: 0px;">
                              <select class="dropdown-baru" name="new_products" id="new_products">
                                <option class="tipeName" value="" selected>Products</option>
                                @foreach($products as $produk)
                                <option class="tipeName" value="{{ $produk->kode_produk }}"> {{ $produk->nama_produk }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="new_quantity" class="required">Quantity (KG)</label>
                          <input class="form-control" type="text" name="new_quantity" id="new_quantity" placeholder="Quantity (KG)" />
                          <span class="show_saldo" id="new_show_saldo" style="display: none;"></span>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label for="new-btn-products"></label>
                          <input class="btn btn-success" type="submit" name="new-btn-products" id="new-btn-products" value="Add" style="margin-top: 5px;"/>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label for="new-btn-products"></label>
                          <input class="btn btn-danger" type="button" name="cancel-btn-products" id="cancel-btn-products" value="Cancel" style="margin-top: 5px;"/>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div id="div_input_order_products_table">
                    <table id="products_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Products</th>
                          <th>Quantity (KG)</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div id="div_input_order_products_several_table" style="display: none;">
                    <table id="products_several_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Address</th>
                          <th>Deliv Date</th>
                          <th>Products</th>
                          <th>Amount (KG)</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div id="div_input_order_products_delivdate_table" style="display: none;">
                    <table id="products_delivdate_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Deliv Date</th>
                          <th>Products</th>
                          <th>Amount (KG)</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="product-details"><!--product-details-->
            <div class="col-sm-12">
              <div class="view-product">
                <div class="product-information"><!--/product-information-->
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="keterangan_order">Other Information</label>
                      <textarea class="form-control" rows="3" name="keterangan_order" id="keterangan_order" placeholder="Other Information"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="product-details"><!--product-details-->
            <div class="col-sm-4"></div>
            <div class="col-sm-4 div-btn-orders">
              <input class="btn btn-success" type="button" name="btn-orders" id="btn-orders" value="Orders" style="margin-top: 5px; height: 40px;"/>
              <input class="btn btn-success" type="button" name="btn-several-orders" id="btn-several-orders" value="Orders" style="margin-top: 5px; height: 40px; display: none;"/>
              <input class="btn btn-success" type="button" name="btn-delivdate-orders" id="btn-delivdate-orders" value="Orders" style="margin-top: 5px; height: 40px; display: none;"/>
            </div>
            <div class="col-sm-4"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
<!-- </form> -->

  <!-- Modal -->
  <div class="modal fade" id="modal_choose_address">
    <div class="modal-dialog modal_choose_address" style="width: 500px;">
      <div class="modal-content">
        <div class="modal-header">
          <span class="login100-form-title-popup">Add New Address</span>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          @if ($errors->any())
          <div class="alert alert-danger" style="width: 40%; margin-left: 30%; margin-top: 20px;">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <form id="addressform" name="addressform" method="post" action="javascript:void(0)">
            {{ csrf_field() }}
            <div class="form-group validate-input" data-validate = "Name is required">
              <label for="name" class="required">Name</label>
              <input class="form-control" type="text" name="name_receive" id="name_receive" placeholder="Name" />
            </div>
            <div class="form-group validate-input" data-validate = "Address is required">
              <label for="address" class="required">Address</label>
              <textarea class="form-control" rows="3" name="address_receive" id="address_receive" placeholder="Address"></textarea>
            </div>
            <div class="form-select validate-input" data-validate = "City is required">
              <div class="label-flex">
                <label for="city" class="required">City</label>
              </div>
              <div class="select-list" style="margin-bottom: 0px;">
                <select class="city" name="city_receive" id="city_receive" data-width="100%"></select>
              </div>
            </div>
            <div class="form-group validate-input" data-validate = "Phone is required">
              <label for="phone" class="required">Phone</label>
              <input class="form-control" type="text" name="phone_receive" id="phone_receive" placeholder="Phone" />
            </div>
            <input type="submit" class="btn btn-success btn-block" id="submit-address" placeholder="Submit">
            <input type="reset" class="btn btn-primary btn-block" placeholder="Reset">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-default pull-left" data-dismiss="modal" data-toggle="modal" data-target="#modal_history_address"><i class="fa fa-remove"></i> Cancel</button>
        </div>
      </div>
      
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modal_history_address">
    <div class="modal-dialog modal_history_address" style="width: 600px;">
      <div class="modal-content">
        <div class="modal-header">
          <span class="login100-form-title-popup">Choose Another Address</span>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#modal_choose_address" class="btn btn-outline-secondary btn-lg btn-block" style="border: 2px dashed #868e96;">Add New Address</button>
          <div id="div-address-list">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_several_address">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Send To Several Address</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="div-address-list-several">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_input_orders">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('en/orders') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>

<script src="{{asset('app-assets/js/bootstrap.min.js')}}"></script>
@endsection

@section('script_login')
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<script src="{{asset('app-assets/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('app-assets/js/main_orders.js')}}"></script>
<script src="{{asset('app-assets/js/main_baru.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    let key = "{{ env('MIX_APP_KEY') }}";

    var custid = $('#custid').val();

    function encryptMethodLength_func() {
      var encryptMethod = 'AES-256-CBC';
      var aesNumber = encryptMethod.match(/\d+/)[0];
      return parseInt(aesNumber);
    }

    function enc(plainText){
      var iv = CryptoJS.lib.WordArray.random(16);

      var salt = CryptoJS.lib.WordArray.random(256);
      var iterations = 999;
      var encryptMethodLength = (encryptMethodLength_func()/4);
      var hashKey = CryptoJS.PBKDF2(key, salt, {'hasher': CryptoJS.algo.SHA512, 'keySize': (encryptMethodLength/8), 'iterations': iterations});

      var encrypted = CryptoJS.AES.encrypt(plainText, hashKey, {'mode': CryptoJS.mode.CBC, 'iv': iv});
      var encryptedString = CryptoJS.enc.Base64.stringify(encrypted.ciphertext);

      var output = {
        'ciphertext': encryptedString,
        'iv': CryptoJS.enc.Hex.stringify(iv),
        'salt': CryptoJS.enc.Hex.stringify(salt),
        'iterations': iterations
      };

      return CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(output)));
    }

    var i = 0;
    <?php foreach( $alamat_several as $schedule){ ?>
      i++;
      $('#div_view_address_several').append('<p><b>Address ' + (i) + ' : </b> {{ $schedule->address_receive }}, {{ $schedule->name_city }}</p>');
    <?php } ?>

    $('#show_saldo').html('').show();
    $('#new_show_saldo').html('').hide();
    var select_produk = $('#products').val();
    if(select_produk != null || select_produk != ''){
      var url_saldo = "{{ url('get_saldo/kode_produk') }}";
      url_saldo = url_saldo.replace('kode_produk', enc(select_produk));
      $.get(url_saldo, function (data_saldo) {
        $('#show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
      })
    }
    var several = '{{ Session::get("several_address") }}';
    if(several == 1){
      $('#div_view_address').hide();
      $('#btn_choose_another_address').hide();
      $('#btn_choose_more_delivery_date').hide();
      $('#div_view_address_several').show();
      $('#btn-orders').hide();
      $('#btn-several-orders').show();
      $('#div_input_order_products_table').hide();
      $('#div_input_order_products_several_table').show();
      $('#div_input_order_products_delivdate_table').hide();
      $('#div_delivery_date').hide();
      $('#div_delivery_date_products').show();
      $('#div_select_address').show();
      $('#input_several_address').html('<h2>Orders Delivered To More Than 1 Address</h2>');
      $('#products_several_table').DataTable().destroy();
      $('#products_several_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Add products you want to buy"
        },
        ajax: {
          url:'{{ url("show_several_products") }}',
          data:{custid:custid}
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'alamat',
         name:'alamat'
        },
        {
         data:'tgl_kirim',
         name:'tgl_kirim'
        },
        {
         data:'produk',
         name:'produk'
       },
       {
         data:'quantity',
         name:'quantity',
         render: $.fn.dataTable.render.number('.', " KG", ','),
         width: '30%'
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       },
       ]
     });
    }else{
      $('#btn_choose_another_address').show();
      $('#btn_choose_more_delivery_date').show();
      $('#div_view_address').show();
      $('#div_view_address_several').hide();
      $('#btn-orders').show();
      $('#btn-several-orders').hide();
      $('#div_input_order_products_table').show();
      $('#div_input_order_products_several_table').hide();
      $('#div_input_order_products_delivdate_table').hide();
      $('#div_delivery_date').show();
      $('#div_delivery_date_products').hide();
      $('#input_several_address').html('');
      $('#products_table').DataTable().destroy();
      $('#products_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Add products you want to buy"
        },
        ajax: {
          url:'{{ route("orders.index") }}',
          data:{custid:custid}
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'produk',
         name:'produk'
       },
       {
         data:'quantity',
         name:'quantity',
         render: $.fn.dataTable.render.number('.', " KG", ','),
         width: '30%'
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       },
       ]
     });

      $('#btn_choose_more_delivery_date').click(function (e) {
        $('#btn_choose_another_address').show();
        $('#btn_send_several_address').hide();
        $('#btn_choose_more_delivery_date').hide();
        $('#btn_cancel_more_delivery_date').show();
        $('#div_delivery_date').hide();
        $('#div_delivery_date_products').show();
        $('#btn-orders').hide();
        $('#btn-several-orders').hide();
        $('#btn-delivdate-orders').show();
        $('#div_input_order_products_table').hide();
        $('#div_input_order_products_several_table').hide();
        $('#div_input_order_products_delivdate_table').show();
        $('#products_delivdate_table').DataTable().destroy();
        load_data_delivdate_products(custid);
      });

      $('#btn_cancel_more_delivery_date').click(function (e) {
        $('#btn_choose_another_address').show();
        $('#btn_send_several_address').show();
        $('#btn_choose_more_delivery_date').show();
        $('#btn_cancel_more_delivery_date').hide();
        $('#div_delivery_date').show();
        $('#div_delivery_date_products').hide();
        $('#btn-orders').show();
        $('#btn-several-orders').hide();
        $('#btn-delivdate-orders').hide();
        $('#div_input_order_products_table').show();
        $('#div_input_order_products_several_table').hide();
        $('#div_input_order_products_delivdate_table').hide();
        $('#products_table').DataTable().destroy();
        load_data_products(custid);
      });
    }

    $("#products").change(function() {
      var select_produk = $(this).val();
      var url_saldo = "{{ url('get_saldo/kode_produk') }}";
      url_saldo = url_saldo.replace('kode_produk', enc(select_produk));
      $.get(url_saldo, function (data_saldo) {
        $('#show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
      })
    });

    $("#new_products").change(function() {
      var select_produk = $(this).val();
      var url_saldo = "{{ url('get_saldo/kode_produk') }}";
      url_saldo = url_saldo.replace('kode_produk', enc(select_produk));
      $.get(url_saldo, function (data_saldo) {
        $('#new_show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
      })
    });

    function load_data_products(custid=''){
      $('#products_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Add products you want to buy"
        },
        ajax: {
          url:'{{ route("orders.index") }}',
          data:{custid:custid}
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'produk',
         name:'produk'
       },
       {
         data:'quantity',
         name:'quantity',
         render: $.fn.dataTable.render.number('.', " KG", ','),
         width: '30%'
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       },
       ]
     });
    }

    function load_data_delivdate_products(custid=''){
      $('#products_delivdate_table').DataTable({
        processing: true,
        serverSide: true,
        language: {
          emptyTable: "Add products you want to buy"
        },
        ajax: {
          url:'{{ url("show_delivdate_products") }}',
          data:{custid:custid}
        },
        dom: 'tr',
        sort: false,
        columns: [
        {
         data:'tgl_kirim',
         name:'tgl_kirim'
        },
        {
         data:'produk',
         name:'produk'
       },
       {
         data:'quantity',
         name:'quantity',
         render: $.fn.dataTable.render.number('.', " KG", ','),
         width: '30%'
       },
       {
         data:'action',
         name:'action',
         width: '5%'
       },
       ]
     });
    }

    $('body').on('click', '#btn-orders', function () {
      var name_receive = document.getElementById("name").value;
      var phone_receive = document.getElementById("phone").value;
      var address_receive = document.getElementById("address").value;
      var city_receive = document.getElementById("city").value;
      var tanggal_kirim = document.getElementById("tanggal_kirim").value;
      var keterangan_order = document.getElementById("keterangan_order").value;
      var kode_alamat = document.getElementById("kode_alamat").value;
      var nomor_po = document.getElementById("nomor_po").value;

      var count = $("#products_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Please add the products you want to buy");
      }else{
        if((tanggal_kirim == null || tanggal_kirim == "")){
          alert("Delivery Date must not be empty");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('add_orders') }}",
            data: { 'custid' : custid, 'kode_alamat' : kode_alamat, 'name' : name_receive, 'phone' : phone_receive, 'address' : address_receive, 'city' : city_receive, 'tanggal_kirim' : tanggal_kirim, 'keterangan_order' : keterangan_order, 'nomor_po' : nomor_po },
            success:function(data){
              alert("Orders Successful");
              window.location = "{{ url('en/orders/list') }}";
            },
            error: function (data) {
              console.log('Error:', data);
              alert("Something Goes Wrong. Please Try Again");
            }
          });
        }
      }
    });

    $('body').on('click', '#btn-several-orders', function () {
      var keterangan_order = document.getElementById("keterangan_order").value;
      var kode_alamat = document.getElementById("kode_alamat").value;
      var nomor_po = document.getElementById("nomor_po").value;

      var count = $("#products_several_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Please add the products you want to buy");
      }else{
        $.ajax({
          type:"GET",
          url:"{{ url('add_orders_several') }}",
          data: { 'custid' : custid, 'kode_alamat' : kode_alamat, 'keterangan_order' : keterangan_order, 'nomor_po' : nomor_po },
          success:function(data){
            alert("Orders Successful");
            window.location = "{{ url('en/orders/list') }}";
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-delivdate-orders', function () {
      var name_receive = document.getElementById("name").value;
      var phone_receive = document.getElementById("phone").value;
      var address_receive = document.getElementById("address").value;
      var city_receive = document.getElementById("city").value;
      var keterangan_order = document.getElementById("keterangan_order").value;
      var kode_alamat = document.getElementById("kode_alamat").value;
      var nomor_po = document.getElementById("nomor_po").value;

      var count = $("#products_delivdate_table").dataTable().fnSettings().aoData.length;
      if (count == 0)
      {
        alert("Please add the products you want to buy");
      }else{
        $.ajax({
          type:"GET",
          url:"{{ url('add_orders_delivdate') }}",
          data: { 'custid' : custid, 'kode_alamat' : kode_alamat, 'name' : name_receive, 'phone' : phone_receive, 'address' : address_receive, 'city' : city_receive, 'keterangan_order' : keterangan_order, 'nomor_po' : nomor_po },
          success:function(data){
            alert("Orders Successful");
            window.location = "{{ url('en/orders/list') }}";
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-new-products', function () {
      $('#show_saldo').html('').hide();
      $('#new_show_saldo').html('').show();
      $('#div-exist-products').hide("slide", {direction: "up"}, 200);
      $('#div-exist-qty').hide("slide", {direction: "up"}, 200);
      $('#div-new-products').show("slide", {direction: "down"}, 200);
    });

    $('body').on('click', '#cancel-btn-products', function () {
      $('#show_saldo').html('').show();
      $('#new_show_saldo').html('').hide();
      var select_produk = $('#products').val();
      if(select_produk != null || select_produk != ''){
        var url_saldo = "{{ url('get_saldo/kode_produk') }}";
        url_saldo = url_saldo.replace('kode_produk', enc(select_produk));
        $.get(url_saldo, function (data_saldo) {
          $('#show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
        })
      }
      $('#div-new-products').hide("slide", {direction: "up"}, 200);
      $('#div-exist-qty').show("slide", {direction: "down"}, 200);
      $('#div-exist-products').show("slide", {direction: "down"}, 200);
    });

    // $('body').on('click', '#btn-recommendation-yes', function () {
    //   var produk_rekomendasi = document.getElementById("produk_rekomendasi").value;
    //   var quantity_rekomendasi = document.getElementById("quantity_rekomendasi").value;

    //   if(quantity_rekomendasi == ''){
    //     alert("Quantity Must Not Empty");
    //   }else{
    //     $.ajax({
    //       type:"GET",
    //       url:"{{ url('add_products_rekomendasi') }}",
    //       data: { 'custid' : custid, 'produk' : produk_rekomendasi, 'quantity' : quantity_rekomendasi },
    //       success:function(data){
    //         var oTable = $('#products_table').dataTable();
    //         oTable.fnDraw(false);
    //         $('#rekomendasi-produk').hide("slide", {direction: "up"}, 1000);
    //       },
    //       error: function (data) {
    //         console.log('Error:', data);
    //         var oTable = $('#products_table').dataTable();
    //         oTable.fnDraw(false);
    //         alert("Something Goes Wrong. Please Try Again");
    //       }
    //     });
    //   }
    // });

    $(document).on('show.bs.modal', '#modal_history_address', function (e) {
      function getPaginationSelectedPage(url) {
        var chunks = url.split('?');
        var baseUrl = chunks[0];
        var querystr = chunks[1].split('&');
        var pg = 1;
        for (i in querystr) {
          var qs = querystr[i].split('=');
          if (qs[0] == 'page') {
            pg = qs[1];
            break;
          }
        }
        return pg;
      }

      $('#div-address-list').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var pg = getPaginationSelectedPage($(this).attr('href'));

        $.ajax({
          url: '/list/en/address',
          data: { page: pg },
          success: function(data) {
            $('#div-address-list').html(data);
            $('html').animate({ scrollTop: 0 }, 'slow');
          }
        });
      });

      $('#div-address-list').load('/list/en/address?page=1');
    });

    $(document).on('show.bs.modal', '#modal_several_address', function (e) {
      function getPaginationSelectedPage(url) {
        var chunks = url.split('?');
        var baseUrl = chunks[0];
        var querystr = chunks[1].split('&');
        var pg = 1;
        for (i in querystr) {
          var qs = querystr[i].split('=');
          if (qs[0] == 'page') {
            pg = qs[1];
            break;
          }
        }
        return pg;
      }

      $('#div-address-list-several').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var pg = getPaginationSelectedPage($(this).attr('href'));

        $.ajax({
          url: '/list/en/address/several',
          data: { page: pg },
          success: function(data) {
            $('#div-address-list-several').html(data);
            $('html').animate({ scrollTop: 0 }, 'slow');
          }
        });
      });

      $('#div-address-list-several').load('/list/en/address/several?page=1');
    });

    $(document).on('hidden.bs.modal', '#modal_several_address', function (e) {
      location.reload(true);
    });

    $('body').on('click', '#btn-choose-address', function () {
      var addressid = $(this).data("id");
      $.ajax({
        type:"GET",
        url:"{{ url('address/choose') }}",
        data: { 'addressid' : addressid },
        success:function(data){
          location.reload(true);
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#btn-cancel-address', function () {
      var addressid = $(this).data("id");
      $.ajax({
        type:"GET",
        url:"{{ url('address/cancel') }}",
        data: { 'addressid' : addressid },
        success:function(data){
          $('#div-address-list-several').load('/list/en/address/several?page=1');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#btn-choose-address-several', function () {
      var addressid = $(this).data("id");
      $.ajax({
        type:"GET",
        url:"{{ url('address/choose/several') }}",
        data: { 'addressid' : addressid },
        success:function(data){
          $('#div-address-list-several').load('/list/en/address/several?page=1');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#btn-delete-address', function () {
      var addressid = $(this).data("id");
      if(confirm("Delete this address?")){
        $.ajax({
          type: "GET",
          url: "{{ url('address/delete') }}",
          data: { 'addressid' : addressid },
          success: function (data) {
            $('#div-address-list').load('/list/en/address?page=1');
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-products', function () {
      if(several == 1){
        if($('#select_address_products').val() == null || $('#select_address_products').val() == ''){
          alert('Choose Address First');
        }else if($('#tanggal_kirim_products').val() == null || $('#tanggal_kirim_products').val() == ''){
          alert('Delivery Date is Required');
        }
      }
    });

    $('body').on('click', '#make_primary_address', function () {
      var addressid = $(this).data("id");
      $.ajax({
        type:"GET",
        url:"{{ url('address/primary') }}",
        data: { 'addressid' : addressid },
        success:function(data){
          $('#div-address-list').load('/list/en/address?page=1');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });
  });
</script>

<script type="text/javascript">
  let key = "{{ env('MIX_APP_KEY') }}";

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function encryptMethodLength_func() {
    var encryptMethod = 'AES-256-CBC';
    var aesNumber = encryptMethod.match(/\d+/)[0];
    return parseInt(aesNumber);
  }

  function enc(plainText){
    var iv = CryptoJS.lib.WordArray.random(16);

    var salt = CryptoJS.lib.WordArray.random(256);
    var iterations = 999;
    var encryptMethodLength = (encryptMethodLength_func()/4);
    var hashKey = CryptoJS.PBKDF2(key, salt, {'hasher': CryptoJS.algo.SHA512, 'keySize': (encryptMethodLength/8), 'iterations': iterations});

    var encrypted = CryptoJS.AES.encrypt(plainText, hashKey, {'mode': CryptoJS.mode.CBC, 'iv': iv});
    var encryptedString = CryptoJS.enc.Base64.stringify(encrypted.ciphertext);

    var output = {
      'ciphertext': encryptedString,
      'iv': CryptoJS.enc.Hex.stringify(iv),
      'salt': CryptoJS.enc.Hex.stringify(salt),
      'iterations': iterations
    };

    return CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(output)));
  }

  if ($("#addressform").length > 0) {
    $("#addressform").validate({
      rules: {
        name_receive: {
          required: true,
        },
        address_receive: {
          required: true,
        },
        city_receive: {
          required: true,
        },
        phone_receive: {
          required: true,
        },
      },
      messages: {
        name_receive: {
          required: "Name is required",
        },
        address_receive: {
          required: "Address is required",
        },
        city_receive: {
          required: "City is required",
        },
        phone_receive: {
          required: "Phone is required",
        },  
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },
      submitHandler: function(form) {

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        var myform = document.getElementById("addressform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('change_address') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            location.reload(true);
          },
          error: function (data) {
            console.log('Error:', data);
            location.reload(true);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    })
  }

  if ($("#productsform").length > 0) {
    $("#productsform").validate({
      rules: {
        products: {
          required: true,
        },
        quantity: {
          required: true,
        },
        custid: {
          required: true,
        },
      },
      messages: {
        products: {
          required: "Product is required",
        },
        quantity: {
          required: "Quantity is required",
        }, 
        custid: {
          required: "Custid is required",
        },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },
      submitHandler: function(form) {

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        var myform = document.getElementById("productsform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('add_products') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            // $('#productsform').trigger("reset");
            var oTable = $('#products_table').dataTable();
            oTable.fnDraw(false);
            var oTableb = $('#products_several_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#products_delivdate_table').dataTable();
            oTablec.fnDraw(false);
            $('#show_saldo').html('').show();
            $('#new_show_saldo').html('').hide();
            var url_saldo = "{{ url('get_saldo/kode_produk') }}";
            url_saldo = url_saldo.replace('kode_produk', enc(data));
            $.get(url_saldo, function (data_saldo) {
              $('#show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
            })
          },
          error: function (data) {
            console.log('Error:', data);
            // $('#productsform').trigger("reset");
            var oTable = $('#products_table').dataTable();
            oTable.fnDraw(false);
            var oTableb = $('#products_several_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#products_delivdate_table').dataTable();
            oTablec.fnDraw(false);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    })
  }

  if ($("#newproductsform").length > 0) {
    $("#newproductsform").validate({
      rules: {
        new_products: {
          required: true,
        },
        new_quantity: {
          required: true,
        },
        new_custid: {
          required: true,
        },
      },
      messages: {
        new_products: {
          required: "Product is required",
        },
        new_quantity: {
          required: "Quantity is required",
        }, 
        new_custid: {
          required: "Custid is required",
        },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },
      submitHandler: function(form) {

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        var myform = document.getElementById("newproductsform");
        var formdata = new FormData(myform);

        $.ajax({
          type:'POST',
          url:"{{ url('add_new_products') }}",
          data: formdata,
          processData: false,
          contentType: false,
          success:function(data){
            // $('#newproductsform').trigger("reset");
            var oTable = $('#products_table').dataTable();
            oTable.fnDraw(false);
            var oTableb = $('#products_several_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#products_delivdate_table').dataTable();
            oTablec.fnDraw(false);
            $('#show_saldo').html('').hide();
            $('#new_show_saldo').html('').show();
            var url_saldo = "{{ url('get_saldo/kode_produk') }}";
            url_saldo = url_saldo.replace('kode_produk', enc(data));
            $.get(url_saldo, function (data_saldo) {
              $('#new_show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
            })
          },
          error: function (data) {
            console.log('Error:', data);
            // $('#newproductsform').trigger("reset");
            var oTable = $('#products_table').dataTable();
            oTable.fnDraw(false);
            var oTableb = $('#products_several_table').dataTable();
            oTableb.fnDraw(false);
            var oTablec = $('#products_delivdate_table').dataTable();
            oTablec.fnDraw(false);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    })
  }
</script>

<script type="text/javascript">
  $(document).ready(function(){
    let key = "{{ env('MIX_APP_KEY') }}";

    function encryptMethodLength_func() {
      var encryptMethod = 'AES-256-CBC';
      var aesNumber = encryptMethod.match(/\d+/)[0];
      return parseInt(aesNumber);
    }

    function enc(plainText){
      var iv = CryptoJS.lib.WordArray.random(16);

      var salt = CryptoJS.lib.WordArray.random(256);
      var iterations = 999;
      var encryptMethodLength = (encryptMethodLength_func()/4);
      var hashKey = CryptoJS.PBKDF2(key, salt, {'hasher': CryptoJS.algo.SHA512, 'keySize': (encryptMethodLength/8), 'iterations': iterations});

      var encrypted = CryptoJS.AES.encrypt(plainText, hashKey, {'mode': CryptoJS.mode.CBC, 'iv': iv});
      var encryptedString = CryptoJS.enc.Base64.stringify(encrypted.ciphertext);

      var output = {
        'ciphertext': encryptedString,
        'iv': CryptoJS.enc.Hex.stringify(iv),
        'salt': CryptoJS.enc.Hex.stringify(salt),
        'iterations': iterations
      };

      return CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(output)));
    }

    $('body').on('click', '#delete-products', function () {
      var custid = $(this).data("id");
      var kode_produk = $(this).data("produk");
      if(confirm("Delete this products?")){
        $.ajax({
          type: "GET",
          url: "{{ url('products/delete') }}",
          data: { 'custid' : custid, 'kode_produk' : kode_produk },
          success: function (data) {
            var oTable = $('#products_table').dataTable(); 
            oTable.fnDraw(false);
            output = document.getElementById("show_saldo").innerHTML;
            output_new = document.getElementById("new_show_saldo").innerHTML;
            if((output_new != null || output_new != '') && (output == null || output == '')){
              $('#new_show_saldo').html('').show();
              $('#show_saldo').html('').hide();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data));
              $.get(url_saldo, function (data_saldo) {
                $('#new_show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else{
              $('#new_show_saldo').html('').hide();
              $('#show_saldo').html('').show();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#delete-products-several', function () {
      var custid = $(this).data("id");
      var kode_produk = $(this).data("produk");
      var id_alamat = $(this).data("address");
      if(confirm("Delete this products?")){
        $.ajax({
          type: "GET",
          url: "{{ url('products/delete/several') }}",
          data: { 'custid' : custid, 'kode_produk' : kode_produk, 'id_alamat' : id_alamat },
          success: function (data) {
            var oTable = $('#products_several_table').dataTable(); 
            oTable.fnDraw(false);
            output = document.getElementById("show_saldo").innerHTML;
            output_new = document.getElementById("new_show_saldo").innerHTML;
            if((output_new != null || output_new != '') && (output == null || output == '')){
              $('#new_show_saldo').html('').show();
              $('#show_saldo').html('').hide();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data));
              $.get(url_saldo, function (data_saldo) {
                $('#new_show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else{
              $('#new_show_saldo').html('').hide();
              $('#show_saldo').html('').show();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#delete-products-delivdate', function () {
      var custid = $(this).data("id");
      var kode_produk = $(this).data("produk");
      var tgl_kirim = $(this).data("tanggal");
      if(confirm("Delete this products?")){
        $.ajax({
          type: "GET",
          url: "{{ url('products/delete/delivdate') }}",
          data: { 'custid' : custid, 'kode_produk' : kode_produk, 'tgl_kirim' : tgl_kirim },
          success: function (data) {
            var oTable = $('#products_delivdate_table').dataTable(); 
            oTable.fnDraw(false);
            output = document.getElementById("show_saldo").innerHTML;
            output_new = document.getElementById("new_show_saldo").innerHTML;
            if((output_new != null || output_new != '') && (output == null || output == '')){
              $('#new_show_saldo').html('').show();
              $('#show_saldo').html('').hide();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data));
              $.get(url_saldo, function (data_saldo) {
                $('#new_show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else{
              $('#new_show_saldo').html('').hide();
              $('#show_saldo').html('').show();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo').html('Remaining Stock : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });
  });
</script>

<script type="text/javascript">
  $('.city').select2({
    placeholder: 'City',
    width: 'resolve',
    dropdownAutoWidth: true,
    allowClear: true,
    ajax: {
      url: '/dropdown_city',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results:  $.map(data, function (item) {
            return {
              text: item.name,
              id: item.id_kota
            }
          })
        };
      },
      cache: true
    }
  });
</script>

<script type="text/javascript">
  $(".city").on("select2:open", function() {
    $(".select2-search__field").attr("placeholder", "Search City Here...");
  });
  $(".city").on("select2:close", function() {
    $(".select2-search__field").attr("placeholder", null);
  });
</script>

<script>
  var msg = '{{ Session::get('alert') }}';
  var exist = '{{ Session::has('alert') }}';
  if(exist){
    alert(msg);
  }
</script>
@endsection