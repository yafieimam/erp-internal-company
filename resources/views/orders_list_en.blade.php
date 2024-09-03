@extends('layouts.app_en')

@section('title')
<title>ORDERS LIST - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link href="{{asset('app-assets/css/main_orders.css')}}" rel="stylesheet" media="screen"/>
<link href="{{asset('app-assets/css/order/bootstrap.min.css')}}" rel="stylesheet" media="screen"/>
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/form_order/bootstrap.min.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
<style type="text/css">
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
    .product-information {
      padding-bottom: 0px;
      padding-top: 0px;
    }
    .category-products {
      padding-right: 25px; 
      padding-left: 25px;
    }
  }
</style>
@endsection

@section('nav')
            <div class="bahasa">
                <p>
                  <a href="{{ url('en/orders/list') }}"><span class="active">EN</span></a>
                  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                  <a href="{{ url('id/orders/list') }}"><span class="">IN</span></a>
                </p>
            </div>
@endsection

@section('nav_footer')
      <li class="nav-item">
            <a class="bahasa-mobile" href="{{ url('en/orders/list') }}" title="ENG">English&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;</a>
            <a class="bahasa-mobile ind" href="{{ url('id/orders/list') }}" title="Bahasa">Indonesia</a>
      </li>
@endsection

@section('content')
<section>
  <div class="container">
    <span class="login100-form-title wow fadeInDown">Order List</span>
    <div class="row">
      <div class="col-sm-3">
        <div class="left-sidebar">
          <h2>Order Status</h2>
          <div class="panel-group category-products" id="accordian">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <ul class="nav nav-tabs" style="border-bottom: 0;">
                    <li class="active"><a href="#all" data-toggle="tab">All</a></li>
                    <li><a href="#new" data-toggle="tab">New Orders</a></li>
                    <li><a href="#confirm" data-toggle="tab">Confirmation</a></li>
                    <li><a href="#produksi" data-toggle="tab">Production</a></li>
                    <li><a href="#delivery" data-toggle="tab">Delivery</a></li>
                    <li><a href="#transit" data-toggle="tab">In Transit</a></li>
                    <li><a href="#arrive" data-toggle="tab">Arrive</a></li>
                  </ul>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-9 padding-right">
        <div class="row input-daterange title-view product-information" style="justify-content: center; margin-bottom: 20px; margin-left: 25px; margin-right: 25px; padding-left: 0; padding-right: 0;">
          <div class="col-sm-4">
            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
          </div>
          <div class="col-sm-4">
            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
          </div>
          <div class="col-sm-4">
            <div class="col-sm-6">
              <button type="button" style="margin-top: 0; width: 100%;" name="filter" id="filter" class="btn btn-primary">Filter</button>
            </div>
            <div class="col-sm-6">
              <button type="button" style="margin-top: 0; width: 100%;" name="refresh" id="refresh" class="btn btn-primary">Refresh</button>
            </div>
          </div>
        </div>
        <div class="tab-content">
          <div class="tab-pane fade active in" id="all" style="padding-right: 25px; padding-left: 25px;">
          </div>

          <div class="tab-pane fade" id="new" style="padding-right: 25px; padding-left: 25px;">
          </div>

          <div class="tab-pane fade" id="confirm" style="padding-right: 25px; padding-left: 25px;">
          </div>

          <div class="tab-pane fade" id="produksi" style="padding-right: 25px; padding-left: 25px;">
          </div>

          <div class="tab-pane fade" id="delivery" style="padding-right: 25px; padding-left: 25px;">
          </div>

          <div class="tab-pane fade" id="transit" style="padding-right: 25px; padding-left: 25px;">
          </div>

          <div class="tab-pane fade" id="arrive" style="padding-right: 25px; padding-left: 25px;">
          </div>

        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="modal_detail_order" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <span class="login100-form-title-popup" id="title-detail-order"></span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 padding-right">
              <div class="product-details"><!--product-details-->
                <div class="col-sm-6">
                  <div class="view-product">
                    <div class="product-information" style="padding-bottom: 74px;"><!--/product-information-->
                      <h2>Customer Order</h2>
                      <hr>
                      <h2 id="nama_customer_order"></h2>
                      <p id="phone_customer_order"></p>
                      <p align="justify" id="alamat_customer_order"></p>
                      <p id="kota_customer_order"></p>
                      <p id="npwp_customer_order"></p>
                      <p id="crd_customer_order"></p>
                    </div><!--/product-information-->
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="view-product">
                    <div class="product-information"><!--/product-information-->
                      <h2>Customer Receive</h2>
                      <hr>
                      <h2 id="nama_customer_receive"></h2>
                      <p id="phone_customer_receive"></p>
                      <p align="justify" id="alamat_customer_receive"></p>
                      <p id="kota_customer_receive"></p>
                    </div><!--/product-information-->
                  </div>
                </div>
              </div><!--/product-details-->
              <div class="product-details"><!--product-details-->
                <div class="col-sm-12">
                  <div class="view-product">
                    <div class="product-information"><!--/product-information-->
                      <div class="col-sm-12">
                        <div class="form-group">
                          <div class="form-select">
                            <div class="label-flex">
                              <label>Products</label>
                            </div>
                            <select id="products_add" name="products_add" class="dropdown-baru">
                            </select>
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="product-details"><!--product-details-->
                <div class="col-sm-12">
                  <div class="view-product">
                    <div class="product-information"><!--/product-information-->
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="tanggal_kirim">Delivery Date</label>
                          <input class="form-control" type="text" name="tanggal_kirim" id="tanggal_kirim" readonly/>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="nomor_po">PO Number</label>
                          <input class="form-control" type="text" name="nomor_po" id="nomor_po" placeholder="PO Number" readonly/>
                        </div>
                      </div>
                      <div class="col-sm-5" id="div-ekspedisi" style="display: none;">
                        <div class="form-group">
                          <label for="ekspedisi">Ekspedisi</label>
                          <input class="form-control" type="text" name="ekspedisi" id="ekspedisi" readonly/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="product-details"><!--product-details-->
                <div class="col-sm-12">
                  <div class="view-product">
                    <div class="product-information"><!--/product-information-->
                      <table id="order_products_table" style="width: 100%;" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Products</th>
                            <th>Quantity (KG)</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="product-details"><!--product-details-->
                <div class="col-sm-12">
                  <div class="view-product">
                    <div class="product-information"><!--/product-information-->
                      <div class="col-sm-12" id="div-keterangan">
                        <div class="form-group">
                          <label for="keterangan">Other Information</label>
                          <textarea class="form-control" rows="3" name="keterangan" id="keterangan" readonly></textarea>
                        </div>
                      </div>
                      <div class="col-sm-3"></div>
                      <div class="col-sm-6" id="div-total">
                        <div class="form-group">
                          <label for="total_price">Total Price</label>
                          <input class="form-control" style="text-align: center;" type="text" name="total_price" id="total_price" readonly/>
                        </div>
                      </div>
                      <div class="col-sm-3"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="product-details product-information" id="btn-confirm-orderan" style="display: none;"><!--product-details-->
                <div class="col-sm-3"></div>
                <div class="col-sm-3">
                  <input class="btn btn-danger" type="button" name="btn-orders-cancel" id="btn-orders-cancel" value="Cancel" style="margin-top: 5px; height: 40px;"/>
                </div>
                <div class="col-sm-3">
                  <input class="btn btn-success" type="button" name="btn-orders-confirm" id="btn-orders-confirm" value="Confirm" style="margin-top: 5px; height: 40px;"/>
                </div>
                <div class="col-sm-3"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('footer-script')
<!-- All JS -->
<script type="text/javascript">
    var baseurl = "{{ url('en/orders/list') }}";
    var url_add_cart_action = "/product/addCart";
    var url_edit_cart_action = "/product/edit";
</script>

<script src="{{asset('app-assets/js/main_orders.js')}}"></script>
<script src="{{asset('app-assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript">
  $('#order_list').click(function(){
    alert('Clicked !!');
  });
</script>
@endsection

@section('script_login')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>

<script>
  $(document).ready(function(){
    let key = "{{ env('MIX_APP_KEY') }}";

    $('.input-daterange').datepicker({
      todayBtn:'linked',
      format:'yyyy-mm-dd',
      autoclose:true
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

    $('body').on('click', '#detail_order', function () {
      var custid = $(this).data('id');
      var nomor_sj = $(this).data('sj');
        // $('#nomor_complaint').val(complaint);
        var url = "{{ url('detail/order/produk/no_sj') }}";
        url = url.replace('no_sj', enc(nomor_sj.toString()));
        $.get(url, function (data_prd) {
          $('#products_add').children().remove().end();
          $('#products_add').append('<option value="' + data_prd[0].nomor_order_receipt_produk + '" selected>' + data_prd[0].nama_produk + ' | (QTY : ' + data_prd[0].qty + ' KG)</option>');
          $.each(data_prd.slice(1), function(k, v) {
            $('#products_add').append('<option value="' + v.nomor_order_receipt_produk + '">' + v.nama_produk + ' | (QTY : ' + data_prd[0].qty + ' KG)</option>');
          });
          var url_data = "{{ url('detail/order_cust/no_sj/no_sj_produk') }}";
          url_data = url_data.replace('no_sj', enc(nomor_sj.toString()));
          url_data = url_data.replace('no_sj_produk', enc(data_prd[0].nomor_order_receipt_produk.toString()));
          $('#title-detail-order').html('Detail Order ' + nomor_sj);
          $.get(url_data, function (data) {
            $('#nama_customer_order').html(data.nama_cust_order);
            $('#phone_customer_order').html('<b>Phone: </b>' + data.phone_cust_order);
            $('#alamat_customer_order').html('<b>Address: </b>' + data.alamat_cust_order);
            $('#kota_customer_order').html('<b>City: </b>' + data.city_cust_order);
            $('#npwp_customer_order').html('<b>NPWP: </b>' + data.npwp_cust_order);
            if(data.crd == 1){
              $('#crd_customer_order').html('<b>Credit Payment: </b>' + data.crd + ' Day Cash / Transfer');
            }else{
              $('#crd_customer_order').html('<b>Credit Payment: </b>' + data.crd + ' Days');
            }
            $('#nama_customer_receive').html(data.nama_cust_receive);
            $('#phone_customer_receive').html('<b>Phone: </b>' + data.phone_cust_receive);
            $('#alamat_customer_receive').html('<b>Address: </b>' + data.alamat_cust_receive);
            $('#kota_customer_receive').html('<b>City: </b>' + data.city_cust_receive);
            $('#tanggal_kirim').val(data.tanggal_kirim);
            $('#nomor_po').val(data.nomor_po);
            $('#ekspedisi').val(data.nama_ekspedisi);
            if(data.status_order == 1){
              $('#div-keterangan').show();
              $('#div-total').hide();
              $('#div-ekspedisi').hide();
              $('#btn-confirm-orderan').hide();
              $('#keterangan').val(data.keterangan_order);
            }else if(data.status_order == 2){
              $('#div-keterangan').show();
              $('#div-total').show();
              $('#div-ekspedisi').show();
              $('#btn-confirm-orderan').show();
              $('#keterangan').val(data.keterangan_quotation);
              $('#total_price').val('Rp ' + parseFloat(data.total_price).toLocaleString('id-ID'));
            }else if(data.status_order == 3){
              $('#div-keterangan').show();
              $('#div-total').show();
              $('#div-ekspedisi').show();
              $('#btn-confirm-orderan').hide();
              $('#keterangan').val(data.keterangan_quotation);
              $('#total_price').val('Rp ' + parseFloat(data.total_price).toLocaleString('id-ID'));
            }else{
              $('#div-keterangan').hide();
              $('#div-total').hide();
              $('#div-ekspedisi').hide();
              $('#btn-confirm-orderan').hide();
            }
          })

          $("#products_add").change(function() {
            var select_produk = $(this).val();
            var url_data = "{{ url('detail/order_cust/no_sj/no_sj_produk') }}";
            url_data = url_data.replace('no_sj', enc(nomor_sj.toString()));
            url_data = url_data.replace('no_sj_produk', enc(select_produk.toString()));
            $('#title-detail-order').html('Detail Order ' + enc(nomor_sj.toString()));
            $.get(url_data, function (data) {
              $('#nama_customer_order').html(data.nama_cust_order);
              $('#phone_customer_order').html('<b>Phone: </b>' + data.phone_cust_order);
              $('#alamat_customer_order').html('<b>Address: </b>' + data.alamat_cust_order);
              $('#kota_customer_order').html('<b>City: </b>' + data.city_cust_order);
              $('#npwp_customer_order').html('<b>NPWP: </b>' + data.npwp_cust_order);
              if(data.crd == 1){
                $('#crd_customer_order').html('<b>Credit Payment: </b>' + data.crd + ' Day Cash / Transfer');
              }else{
                $('#crd_customer_order').html('<b>Credit Payment: </b>' + data.crd + ' Days');
              }
              $('#nama_customer_receive').html(data.nama_cust_receive);
              $('#phone_customer_receive').html('<b>Phone: </b>' + data.phone_cust_receive);
              $('#alamat_customer_receive').html('<b>Address: </b>' + data.alamat_cust_receive);
              $('#kota_customer_receive').html('<b>City: </b>' + data.city_cust_receive);
              $('#tanggal_kirim').val(data.tanggal_kirim);
              $('#nomor_po').val(data.nomor_po);
              $('#ekspedisi').val(data.nama_ekspedisi);
              if(data.status_order == 1){
                $('#div-keterangan').show();
                $('#div-total').hide();
                $('#div-ekspedisi').hide();
                $('#btn-confirm-orderan').hide();
                $('#keterangan').val(data.keterangan_order);
              }else if(data.status_order == 2){
                $('#div-keterangan').show();
                $('#div-total').show();
                $('#div-ekspedisi').show();
                $('#btn-confirm-orderan').show();
                $('#keterangan').val(data.keterangan_quotation);
                $('#total_price').val('Rp ' + parseFloat(data.total_price).toLocaleString('id-ID'));
              }else if(data.status_order == 3){
                $('#div-keterangan').show();
                $('#div-total').show();
                $('#div-ekspedisi').show();
                $('#btn-confirm-orderan').hide();
                $('#keterangan').val(data.keterangan_quotation);
                $('#total_price').val('Rp ' + parseFloat(data.total_price).toLocaleString('id-ID'));
              }else{
                $('#div-keterangan').hide();
                $('#div-total').hide();
                $('#div-ekspedisi').hide();
                $('#btn-confirm-orderan').hide();
              }
            })
          });
        })
        var url_products = "{{ url('products/order') }}";
        $('#order_products_table').DataTable().destroy();
        $('#order_products_table').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: url_products,
            data:{nomor_sj:nomor_sj}
          },
          dom: 'tr',
          sort: false,
          columns: [
          {
            data:'nama_produk',
            name:'nama_produk',
            orderable: false,
          },
          {
            data:'qty',
            name:'qty',
            width: '30%'
          }
          ]
        });
        $('body').on('click', '#btn-orders-confirm', function () {
          if(confirm("Confirm Detail Orders Above?")){
            $.ajax({
              type: "GET",
              url: "{{ url('validasi_orders') }}",
              data: { 'nomor_sj' : nomor_sj },
              success: function (data) {
                alert("Orders Successfully Confirm. Your Orders Will Be Produced.");
                $("#modal_detail_order").modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('#all').load('/list/en/all?page=1');
                $('#new').load('/list/en/new?page=1');
                $('#confirm').load('/list/en/confirm?page=1');
                $('#produksi').load('/list/en/produksi?page=1');
                $('#delivery').load('/list/en/delivery?page=1');
                $('#transit').load('/list/en/transit?page=1');
                $('#arrive').load('/list/en/arrive?page=1');
              },
              error: function (data) {
                console.log('Error:', data);
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        });

        $('body').on('click', '#btn-orders-cancel', function () {
          if(confirm("Are You Sure You Want To Cancel Your Orders?")){
            $.ajax({
              type: "GET",
              url: "{{ url('cancel_orders') }}",
              data: { 'nomor_sj' : nomor_sj },
              success: function (data) {
                alert("Your Orders Has Been Canceled. Thank You For Order In PT Dwi Selo Giri Mas");
                $("#modal_detail_order").modal('hide');
              },
              error: function (data) {
                console.log('Error:', data);
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        });
      });
      $('#filter').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(from_date != '' &&  to_date != '')
        {
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

          $('#all').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var pg = getPaginationSelectedPage($(this).attr('href'));

            $.ajax({
              url: '/list/en/all',
              data: { page: pg },
              success: function(data) {
                $('#all').html(data);
                $('html').animate({ scrollTop: 0 }, 'slow');
              }
            });
          });

          $('#new').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var pg = getPaginationSelectedPage($(this).attr('href'));

            $.ajax({
              url: '/list/en/new',
              data: { page: pg },
              success: function(data) {
                $('#new').html(data);
                $('html').animate({ scrollTop: 0 }, 'slow');
              }
            });
          });

          $('#confirm').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var pg = getPaginationSelectedPage($(this).attr('href'));

            $.ajax({
              url: '/list/en/confirm',
              data: { page: pg },
              success: function(data) {
                $('#confirm').html(data);
                $('html').animate({ scrollTop: 0 }, 'slow');
              }
            });
          });

          $('#produksi').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var pg = getPaginationSelectedPage($(this).attr('href'));

            $.ajax({
              url: '/list/en/produksi',
              data: { page: pg },
              success: function(data) {
                $('#produksi').html(data);
                $('html').animate({ scrollTop: 0 }, 'slow');
              }
            });
          });

          $('#delivery').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var pg = getPaginationSelectedPage($(this).attr('href'));

            $.ajax({
              url: '/list/en/delivery',
              data: { page: pg },
              success: function(data) {
                $('#delivery').html(data);
                $('html').animate({ scrollTop: 0 }, 'slow');
              }
            });
          });

          $('#transit').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var pg = getPaginationSelectedPage($(this).attr('href'));

            $.ajax({
              url: '/list/en/transit',
              data: { page: pg },
              success: function(data) {
                $('#transit').html(data);
                $('html').animate({ scrollTop: 0 }, 'slow');
              }
            });
          });

          $('#arrive').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var pg = getPaginationSelectedPage($(this).attr('href'));

            $.ajax({
              url: '/list/en/arrive',
              data: { page: pg },
              success: function(data) {
                $('#arrive').html(data);
                $('html').animate({ scrollTop: 0 }, 'slow');
              }
            });
          });

          // first load
          $('#all').load('/list/en/filter/all/' + from_date + '/' + to_date + '?page=1');
          $('#new').load('/list/en/filter/new/' + from_date + '/' + to_date + '?page=1');
          $('#confirm').load('/list/en/filter/confirm/' + from_date + '/' + to_date + '?page=1');
          $('#produksi').load('/list/en/filter/produksi/' + from_date + '/' + to_date + '?page=1');
          $('#delivery').load('/list/en/filter/delivery/' + from_date + '/' + to_date + '?page=1');
          $('#transit').load('/list/en/filter/transit/' + from_date + '/' + to_date + '?page=1');
          $('#arrive').load('/list/en/filter/arrive/' + from_date + '/' + to_date + '?page=1');
        }
        else
        {
          alert('Both Date is required');
        }
      });

      $('#refresh').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
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

        $('#all').on('click', '.pagination a', function(e) {
          e.preventDefault();
          var pg = getPaginationSelectedPage($(this).attr('href'));

          $.ajax({
            url: '/list/en/all',
            data: { page: pg },
            success: function(data) {
              $('#all').html(data);
              $('html').animate({ scrollTop: 0 }, 'slow');
            }
          });
        });

        $('#new').on('click', '.pagination a', function(e) {
          e.preventDefault();
          var pg = getPaginationSelectedPage($(this).attr('href'));

          $.ajax({
            url: '/list/en/new',
            data: { page: pg },
            success: function(data) {
              $('#new').html(data);
              $('html').animate({ scrollTop: 0 }, 'slow');
            }
          });
        });

        $('#confirm').on('click', '.pagination a', function(e) {
          e.preventDefault();
          var pg = getPaginationSelectedPage($(this).attr('href'));

          $.ajax({
            url: '/list/en/confirm',
            data: { page: pg },
            success: function(data) {
              $('#confirm').html(data);
              $('html').animate({ scrollTop: 0 }, 'slow');
            }
          });
        });

        $('#produksi').on('click', '.pagination a', function(e) {
          e.preventDefault();
          var pg = getPaginationSelectedPage($(this).attr('href'));

          $.ajax({
            url: '/list/en/produksi',
            data: { page: pg },
            success: function(data) {
              $('#produksi').html(data);
              $('html').animate({ scrollTop: 0 }, 'slow');
            }
          });
        });

        $('#delivery').on('click', '.pagination a', function(e) {
          e.preventDefault();
          var pg = getPaginationSelectedPage($(this).attr('href'));

          $.ajax({
            url: '/list/en/delivery',
            data: { page: pg },
            success: function(data) {
              $('#delivery').html(data);
              $('html').animate({ scrollTop: 0 }, 'slow');
            }
          });
        });

        $('#transit').on('click', '.pagination a', function(e) {
          e.preventDefault();
          var pg = getPaginationSelectedPage($(this).attr('href'));

          $.ajax({
            url: '/list/en/transit',
            data: { page: pg },
            success: function(data) {
              $('#transit').html(data);
              $('html').animate({ scrollTop: 0 }, 'slow');
            }
          });
        });

        $('#arrive').on('click', '.pagination a', function(e) {
          e.preventDefault();
          var pg = getPaginationSelectedPage($(this).attr('href'));

          $.ajax({
            url: '/list/en/arrive',
            data: { page: pg },
            success: function(data) {
              $('#arrive').html(data);
              $('html').animate({ scrollTop: 0 }, 'slow');
            }
          });
        });

        // first load
        $('#all').load('/list/en/all?page=1');
        $('#new').load('/list/en/new?page=1');
        $('#confirm').load('/list/en/confirm?page=1');
        $('#produksi').load('/list/en/produksi?page=1');
        $('#delivery').load('/list/en/delivery?page=1');
        $('#transit').load('/list/en/transit?page=1');
        $('#arrive').load('/list/en/arrive?page=1');
      });
    });
  </script>

<script>
  $(function() {

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

    $('#all').on('click', '.pagination a', function(e) {
      e.preventDefault();
      var pg = getPaginationSelectedPage($(this).attr('href'));

      $.ajax({
        url: '/list/en/all',
        data: { page: pg },
        success: function(data) {
          $('#all').html(data);
          $('html').animate({ scrollTop: 0 }, 'slow');
        }
      });
    });

    $('#new').on('click', '.pagination a', function(e) {
      e.preventDefault();
      var pg = getPaginationSelectedPage($(this).attr('href'));

      $.ajax({
        url: '/list/en/new',
        data: { page: pg },
        success: function(data) {
          $('#new').html(data);
          $('html').animate({ scrollTop: 0 }, 'slow');
        }
      });
    });

    $('#confirm').on('click', '.pagination a', function(e) {
      e.preventDefault();
      var pg = getPaginationSelectedPage($(this).attr('href'));

      $.ajax({
        url: '/list/en/confirm',
        data: { page: pg },
        success: function(data) {
          $('#confirm').html(data);
          $('html').animate({ scrollTop: 0 }, 'slow');
        }
      });
    });

    $('#produksi').on('click', '.pagination a', function(e) {
      e.preventDefault();
      var pg = getPaginationSelectedPage($(this).attr('href'));

      $.ajax({
        url: '/list/en/produksi',
        data: { page: pg },
        success: function(data) {
          $('#produksi').html(data);
          $('html').animate({ scrollTop: 0 }, 'slow');
        }
      });
    });

    $('#delivery').on('click', '.pagination a', function(e) {
      e.preventDefault();
      var pg = getPaginationSelectedPage($(this).attr('href'));

      $.ajax({
        url: '/list/en/delivery',
        data: { page: pg },
        success: function(data) {
          $('#delivery').html(data);
          $('html').animate({ scrollTop: 0 }, 'slow');
        }
      });
    });

    $('#transit').on('click', '.pagination a', function(e) {
      e.preventDefault();
      var pg = getPaginationSelectedPage($(this).attr('href'));

      $.ajax({
        url: '/list/en/transit',
        data: { page: pg },
        success: function(data) {
          $('#transit').html(data);
          $('html').animate({ scrollTop: 0 }, 'slow');
        }
      });
    });

    $('#arrive').on('click', '.pagination a', function(e) {
      e.preventDefault();
      var pg = getPaginationSelectedPage($(this).attr('href'));

      $.ajax({
        url: '/list/en/arrive',
        data: { page: pg },
        success: function(data) {
          $('#arrive').html(data);
          $('html').animate({ scrollTop: 0 }, 'slow');
        }
      });
    });

    // first load
    $('#all').load('/list/en/all?page=1');
    $('#new').load('/list/en/new?page=1');
    $('#confirm').load('/list/en/confirm?page=1');
    $('#produksi').load('/list/en/produksi?page=1');
    $('#delivery').load('/list/en/delivery?page=1');
    $('#transit').load('/list/en/transit?page=1');
    $('#arrive').load('/list/en/arrive?page=1');
  });
</script>
@endsection