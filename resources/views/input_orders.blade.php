@extends('layouts.app_admin')

@section('title')
<title>ORDERS - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2/css/select2.css')}}">
<link rel="stylesheet" href="{{asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style type="text/css">
  #customers_list_table tbody tr:hover{
    cursor: pointer;
  }
  .modal {
    overflow-y: auto !important;
  }
  div.dataTables_length {
    margin-top: 5px;
    margin-right: 1em;
  }
  #edit_new_produk, #btn-edit-products{
    right: 20%;
    position: absolute;
    top: 30px;
    width: 60%;
    height: 40px;
  }
  .show_saldo {
    width: 100%;
    margin-top: .25rem;
    font-size: 80%;
    color: #dc3545;
  }
  .filter-btn {
    margin-top: 32px;
  }
  .primary-address, .delete-address {
      float: right;
    }
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
    }
    .filter-btn {
      margin-top: 0;
      margin-bottom: 10px;
    }
    .primary-address, .delete-address {
      float: none;
    }
    .btn-address {
      width: 100%;
      margin-bottom: 10px;
    }
    #edit_new_produk, #btn-edit-products{
      position: unset;
      width: 100%;
    }
  }
</style>
@endsection

@section('content_nav')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Orders</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Sales</li>
            <li class="breadcrumb-item">Orders</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  @endsection

  @section('content')
  <div class="row"> 
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-8">
              <div class="form-group">
                <label>Date range:</label>

                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                  <input type="text" placeholder="Filter Date" class="form-control float-right" id="filter_tanggal">
                </div>
              </div>
            </div>
            <div class="col-2">
              <button type="button" name="filter" id="filter" class="btn btn-block btn-primary filter-btn">Filter</button>
            </div>
            <div class="col-2">
              <button type="button" name="refresh" id="refresh" class="btn btn-block btn-info filter-btn">Refresh</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-4">
              <button type="button" name="btn_input_orders" id="btn_input_orders" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal_input_orders">Input Orders</button>
            </div>
          </div>
        </div>
        <div class="card-body" id="konfirmasi_customers">
          <ul class="nav nav-tabs nav-tabs-lihat" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-home-tab" data-toggle="pill" href="#semua_orders" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Semua</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-profile-tab" data-toggle="pill" href="#new_orders" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">New Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#confirmation_orders" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Confirmation</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#produksi_orders" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Produksi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#delivery_orders" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Delivery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#transit_orders" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">In Transit</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#arrive_orders" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Arrive</a>
            </li>
          </ul>
          <div class="tab-content" id="custom-content-below-tabContent">
            <div class="tab-pane fade" id="semua_orders" role="tabpanel" aria-labelledby="custom-content-below-home-tab" style="margin-top: 40px;">
              <table id="orders_semua_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No Order</th>
                    <th>Tanggal Order</th>
                    <th>Nama Customer Order</th>
                    <th>Nomor PO</th>
                    <th class="min-mobile">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade show active" id="new_orders" role="tabpanel" aria-labelledby="custom-content-below-profile-tab" style="margin-top: 40px;">
              <table id="orders_new_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No Order</th>
                    <th>Tanggal Order</th>
                    <th>Nama Customer Order</th>
                    <th>Nomor PO</th>
                    <th class="min-mobile">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="confirmation_orders" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
              <table id="orders_confirmation_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No Order</th>
                    <th>Tgl Order</th>
                    <th>Customer</th>
                    <th>Nomor PO</th>
                    <th class="min-mobile">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="produksi_orders" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
              <table id="orders_produksi_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No Order</th>
                    <th>Tgl Order</th>
                    <th>Customer</th>
                    <th>Nomor PO</th>
                    <th class="min-mobile">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="delivery_orders" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
              <table id="orders_delivery_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No Order</th>
                    <th>Tgl Order</th>
                    <th>Customer</th>
                    <th>Nomor PO</th>
                    <th class="min-mobile">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="transit_orders" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
              <table id="orders_transit_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No Order</th>
                    <th>Tgl Order</th>
                    <th>Customer</th>
                    <th>Nomor PO</th>
                    <th class="min-mobile">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class="tab-pane fade" id="arrive_orders" role="tabpanel" aria-labelledby="custom-content-below-messages-tab" style="margin-top: 40px;">
              <table id="orders_arrive_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                <thead>
                  <tr>
                    <th>No Order</th>
                    <th>Tgl Order</th>
                    <th>Customer</th>
                    <th>Nomor PO</th>
                    <th class="min-mobile">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_input_orders">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Input New Orders</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row"> 
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <h5>Customer Order</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="input_customer_order">Pilih Customer Order</label>
                      <select id="input_customer_order" name="input_customer_order" class="form-control select2 customer" style="width: 100%;">
                      </select>
                    </div>
                  </div>
                </div>
                <input class="form-control" type="hidden" name="input_custid_utama" id="input_custid_utama"/>
                <p id="input_nama_customer_order"></p>
                <p id="input_phone_customer_order"></p>
                <p align="justify" id="input_alamat_customer_order"></p>
                <p id="input_kota_customer_order"></p>
                <div class="form-group" id="div_input_city_order" style="display: none;">
                  <select id="input_city_order" name="input_city_order" class="form-control select2 city" style="width: 100%;">
                  </select>
                </div>
                <p id="input_npwp_customer_order"></p>
                <p id="input_crd_pembayaran"></p>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <h5>Customer Receive</h5>
              </div>
              <div class="card-body">
                <input class="form-control" type="hidden" name="input_txt_kode_alamat" id="input_txt_kode_alamat"/>
                <p id="input_several_address"></p>
                <div id="div_view_address"></div>
                <p id="input_nama_customer_receive"></p>
                <p id="input_phone_customer_receive"></p>
                <p align="justify" id="input_alamat_customer_receive"></p>
                <p id="input_kota_customer_receive"></p>
                <div class="row">
                  <div class="col-6" id="div_choose_another_address" style="display: none;">
                    <div class="form-group">
                      <button type="button" name="btn_choose_another_address" id="btn_choose_another_address" class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_choose_address">Choose Another Address</button>
                    </div>
                  </div>
                  <div class="col-6" id="div_send_several_address" style="display: none;">
                    <div class="form-group">
                      <button type="button" name="btn_send_several_address" id="btn_send_several_address" class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_send_several_address">Send To Several Addresses</button>
                    </div>
                  </div>
                  <div class="col-6" id="div_new_address" style="display: none;">
                    <div class="form-group">
                      <button type="button" name="btn_new_address" id="btn_new_address" class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_new_address">Create New Address</button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6" id="div_choose_more_delivery_date" style="display: none;">
                    <div class="form-group">
                      <button type="button" name="btn_choose_more_delivery_date" id="btn_choose_more_delivery_date" class="btn btn-block btn-primary">Choose More Delivery Date</button>
                    </div>
                  </div>
                  <div class="col-6" id="div_cancel_more_delivery_date" style="display: none;">
                    <div class="form-group">
                      <button type="button" name="btn_cancel_more_delivery_date" id="btn_cancel_more_delivery_date" class="btn btn-block btn-danger">Cancel More Delivery Date</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-6" id="div_delivery_date">
                    <div class="form-group">
                      <label>Delivery Date</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input class="form-control" type="text" name="input_tanggal_kirim" id="input_tanggal_kirim" autocomplete="off" placeholder="Choose Delivery Date" />
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="input_nomor_po">Nomor PO</label>
                      <input class="form-control" type="text" name="input_nomor_po" id="input_nomor_po" placeholder="Nomor PO" />
                    </div>
                  </div>
                  <input class="form-control" type="hidden" name="input_kode_produk" id="input_kode_produk" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <form id="productsform" name="productsform" method="post" action="javascript:void(0)">
          {{ csrf_field() }}
          <div class="row"> 
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="col-5">
                    <div class="form-group">
                      <input class="form-control" type="hidden" name="input_custid" id="input_custid"/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6" id="div_select_address" style="display: none;">
                      <div class="form-group">
                        <label>To Address?</label>
                        <select id="select_address_products" name="select_address_products" class="form-control">
                        </select>
                      </div>
                    </div>
                    <div class="col-6" id="div_delivery_date_products" style="display: none;">
                      <div class="form-group">
                        <label>Delivery Date</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                          </div>
                          <input class="form-control" type="text" name="input_tanggal_kirim_products" id="input_tanggal_kirim_products" autocomplete="off" placeholder="Choose Delivery Date" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row"> 
                    <div class="col-5">
                      <div class="form-group">
                        <label>Products</label>
                        <select id="input_add_products" name="input_add_products" class="form-control">
                        </select>
                      </div>
                    </div>
                    <div class="col-3" id="div_input_quantity">
                      <div class="form-group">
                        <label>Quantity (KG)</label>
                        <input class="form-control" type="text" name="input_add_quantity" id="input_add_quantity" placeholder="Quantity (KG)" />
                        <span class="show_saldo" id="show_saldo_input" style="display: none;"></span>
                      </div>
                    </div>
                    <div class="col-3" id="div_select_quantity" style="display: none;">
                      <div class="form-group">
                        <label>Quantity (KG)</label>
                        <select id="select_add_quantity" name="select_add_quantity" class="form-control">
                        </select>
                        <span class="show_saldo" id="show_saldo_select" style="display: none;"></span>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label>&nbsp</label>
                        <input class="form-control btn btn-success" type="submit" name="btn-products" id="btn-products" value="Add"/>
                      </div>
                    </div>
                    <div class="col-2" id="div-btn-new">
                      <div class="form-group">
                        <label>&nbsp</label>
                        <input class="form-control btn btn-primary" type="button" name="btn-new-products" id="btn-new-products" value="New Products"/>
                      </div>
                    </div>
                    <div class="col-2" id="div-btn-cancel" style="display: none;">
                      <div class="form-group">
                        <label>&nbsp</label>
                        <input class="form-control btn btn-danger" type="button" name="btn-cancel" id="btn-cancel" value="Cancel"/>
                      </div>
                    </div>
                  </div>
                  <div id="div_input_order_products_table">
                    <table id="input_order_products_table" style="width: 100%;" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Produk</th>
                          <th>Jumlah (KG)</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div id="div_input_order_products_several_table" style="display: none;">
                    <table id="input_order_products_several_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                      <thead>
                        <tr>
                          <th>Alamat</th>
                          <th>Tgl Kirim</th>
                          <th>Produk</th>
                          <th>Jumlah (KG)</th>
                          <th class="min-mobile"></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div id="div_input_order_products_delivdate_table" style="display: none;">
                    <table id="input_order_products_delivdate_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                      <thead>
                        <tr>
                          <th>Tgl Kirim</th>
                          <th>Produk</th>
                          <th>Jumlah (KG)</th>
                          <th class="min-mobile"></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        <div class="row" id="div-input-keterangan"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control" name="input_keterangan" id="input_keterangan"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-orders">Save changes</button>
        <button type="submit" class="btn btn-primary" id="btn-several-orders" style="display: none;">Save changes</button>
        <button type="submit" class="btn btn-primary" id="btn-delivdate-orders" style="display: none;">Save changes</button>
      </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_detail_order">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="title-detail-order"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row"> 
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <h5>Customer Order</h5>
              </div>
              <div class="card-body">
                <p id="nama_customer_order"></p>
                <p id="phone_customer_order"></p>
                <p align="justify" id="alamat_customer_order"></p>
                <p id="kota_customer_order"></p>
                <p id="npwp_customer_order"></p>
                <p id="crd_pembayaran"></p>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <h5>Customer Receive</h5>
              </div>
              <div class="card-body">
                <p id="nama_customer_receive"></p>
                <p id="phone_customer_receive"></p>
                <p align="justify" id="alamat_customer_receive"></p>
                <p id="kota_customer_receive"></p>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="form-group">
              <label>Products</label>
              <select id="products_add" name="products_add" class="form-control">
              </select>
            </div>
          </div>
        </div>
        <form method="post" class="validasi_adding_price_form" id="validasi_adding_price_form" action="javascript:void(0)">
        {{ csrf_field() }}
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row"> 
                  <div class="col-3">
                    <div class="form-group">
                      <label>Delivery Date</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input class="form-control" type="text" autocomplete="off" name="tanggal_kirim" id="tanggal_kirim" />
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="nomor_po">Nomor PO</label>
                      <input class="form-control" type="text" autocomplete="off" name="nomor_po" id="nomor_po" disabled/>
                    </div>
                  </div>
                  <div class="col-5">
                    <div class="form-group">
                      <label>Ekspedisi</label>
                      <select id="ekspedisi" name="ekspedisi" class="form-control">
                      </select>
                    </div>
                  </div>
                  <input class="form-control" type="hidden" name="kode_produk" id="kode_produk" />
                </div>
                <div class="row" id="div-referensi" style="display: none;">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="nomor_referensi">Nomor Referensi</label>
                      <input class="form-control" type="text" autocomplete="off" name="nomor_referensi" id="nomor_referensi" disabled/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table id="order_products_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Jumlah</th>
                      <th>Harga Satuan</th>
                      <th>Total Harga</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="row" id="div-keterangan"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control" name="keterangan" id="keterangan" readonly></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" id="konfirmasi-orderan" style="display: none;"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <input type="hidden" name="nomor_order_receipt" id="nomor_order_receipt"/>
                <input type="hidden" name="nomor_order_receipt_produk" id="nomor_order_receipt_produk"/>
                <div class="row"> 
                  <div class="col-3">
                    <div class="form-group">
                      <label>Harga Pokok</label>
                      <input class="form-control" type="text" name="pokok_add" id="pokok_add" placeholder="Harga Pokok" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="stapel_add">Total Harga Stapel</label>
                      <input class="form-control" type="text" name="stapel_add" id="stapel_add" placeholder="Total Harga Stapel" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="ekspedisi_add">Total Harga Ekspedisi</label>
                      <input class="form-control" type="text" name="ekspedisi_add" id="ekspedisi_add" placeholder="Total Harga Ekspedisi" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label>Diskon (%)</label>
                      <input class="form-control" type="text" name="diskon_add" id="diskon_add" placeholder="Diskon (%)" />
                    </div>
                  </div>
                  <input class="form-control" type="hidden" name="harga_ongkir_add" id="harga_ongkir_add"/>
                  <div class="col-3">
                    <div class="form-group">
                      <label>PPN (%)</label>
                      <input class="form-control" type="text" name="ppn_add" id="ppn_add" placeholder="PPN (%)" />
                    </div>
                  </div>
                  <div class="col-9">
                    <div class="form-group">
                      <label>Keterangan</label>
                      <textarea class="form-control" name="keterangan_quotation" id="keterangan_quotation" rows="3" placeholder="Keterangan"></textarea>
                    </div>
                  </div>
                </div>
                <!-- <div class="row"> 
                  <div class="col-4"></div>
                  <div class="col-4"></div>
                  <div class="col-4">
                    <div class="form-group row" id="div_total_add" style="display: none;">
                      <label for="total_add" class="col-sm-3 col-form-label">Total</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="total_add" name="total_add" readonly />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row"> --> 
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="submit_validasi_adding_price_form" style="display: none;">Save changes</button>
        <a target="_blank" href="#" class="btn btn-success" id="print_sp" style="display: none;">Print SP</a>
        <!-- <a target="_blank" href="#" class="btn btn-success" id="print_sp_gabung" style="display: none;">Print SP Digabung</a>
        <a target="_blank" href="#" class="btn btn-success" id="print_sp_terpisah" style="display: none;">Print SP Terpisah</a> -->
      </div>
    </form>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_edit_order">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="title-edit-order"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row"> 
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <h5>Customer Order</h5>
              </div>
              <div class="card-body">
                <p id="edit_nama_customer_order"></p>
                <p id="edit_phone_customer_order"></p>
                <p align="justify" id="edit_alamat_customer_order"></p>
                <p id="edit_kota_customer_order"></p>
                <p id="edit_npwp_customer_order"></p>
                <p id="edit_crd_pembayaran"></p>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <h5>Customer Receive</h5>
              </div>
              <div class="card-body">
                <p id="edit_nama_customer_receive"></p>
                <p id="edit_phone_customer_receive"></p>
                <p align="justify" id="edit_alamat_customer_receive"></p>
                <p id="edit_kota_customer_receive"></p>
                <div class="row">
                  <div class="col-6" id="div_choose_another_address">
                    <div class="form-group">
                      <input class="form-control" type="hidden" name="edit_custid" id="edit_custid" />
                      <button type="button" name="btn_edit_choose_another_address" id="btn_edit_choose_another_address" class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_edit_choose_address">Choose Another Address</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-8">
            <div class="form-group">
              <label>Products</label>
              <select id="edit_products_add" name="edit_products_add" class="form-control">
              </select>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label>&nbsp</label>
              <button type="button" class="btn btn-primary" id="edit_new_produk" data-dismiss="modal" data-toggle="modal" data-target="#modal_new_products">Add New Products</button>
            </div>
          </div>
        </div>
        <form method="post" class="edit_validasi_adding_price_form" id="edit_validasi_adding_price_form" action="javascript:void(0)">
        {{ csrf_field() }}
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row"> 
                  <div class="col-6">
                    <div class="form-group">
                      <label>Nomor Order</label>
                      <input class="form-control" type="text" autocomplete="off" name="edit_nomor_order" id="edit_nomor_order" placeholder="Nomor Order" />
                    </div>
                  </div>
                </div>
                <div class="row"> 
                  <div class="col-12">
                    <div class="form-group">
                      <label>Keterangan Order</label>
                      <textarea class="form-control" rows="3" name="edit_keterangan" id="edit_keterangan" placeholder="Keterangan Order"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <input class="form-control" type="hidden" name="edit_kode_alamat" id="edit_kode_alamat"/>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row"> 
                  <div class="col-3">
                    <div class="form-group">
                      <label>Delivery Date</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input class="form-control" type="text" autocomplete="off" name="edit_tanggal_kirim" id="edit_tanggal_kirim" />
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_nomor_po">Nomor PO</label>
                      <input class="form-control" type="text" autocomplete="off" name="edit_nomor_po" id="edit_nomor_po" placeholder="Nomor PO" />
                    </div>
                  </div>
                  <div class="col-5">
                    <div class="form-group">
                      <label>Ekspedisi</label>
                      <select id="edit_ekspedisi" name="edit_ekspedisi" class="form-control">
                      </select>
                    </div>
                  </div>
                  <input class="form-control" type="hidden" name="edit_kode_produk" id="edit_kode_produk" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" id="div_edit_products" style="display: none;"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row"> 
                  <div class="col-6">
                    <div class="form-group">
                      <label>Products</label>
                      <select id="edit_add_products" name="edit_add_products" class="form-control">
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Quantity (KG)</label>
                      <input class="form-control" type="text" name="edit_add_quantity" id="edit_add_quantity" placeholder="Quantity (KG)" />
                      <span class="show_saldo" id="edit_show_saldo_input" style="display: none;"></span>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label>&nbsp</label>
                      <button type="button" class="btn btn-success" id="btn-edit-products">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table id="edit_order_products_table" style="width: 100%;" class="table table-bordered table-hover responsive">
                  <thead>
                    <tr>
                      <th class="min-mobile">Product</th>
                      <th>Jumlah</th>
                      <th>Harga Satuan</th>
                      <th>Total Harga (Include PPN)</th>
                      <th class="min-mobile">Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <input type="hidden" name="edit_nomor_order_receipt" id="edit_nomor_order_receipt"/>
                <input type="hidden" name="edit_nomor_order_receipt_produk" id="edit_nomor_order_receipt_produk"/>
                <div class="row"> 
                  <div class="col-3">
                    <div class="form-group">
                      <label>Harga Satuan Akhir</label>
                      <input class="form-control" type="text" name="edit_pokok_add" id="edit_pokok_add" placeholder="Harga Satuan Akhir (Setelah Perhitungan)" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="edit_stapel_add">Total Harga Stapel</label>
                      <input class="form-control" type="text" name="edit_stapel_add" id="edit_stapel_add" placeholder="Total Harga Stapel" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="edit_ekspedisi_add">Total Harga Ekspedisi</label>
                      <input class="form-control" type="text" name="edit_ekspedisi_add" id="edit_ekspedisi_add" placeholder="Total Harga Ekspedisi" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label>Diskon (%)</label>
                      <input class="form-control" type="text" name="edit_diskon_add" id="edit_diskon_add" placeholder="Diskon (%)" />
                    </div>
                  </div>
                </div>
                <div class="row"> 
                  <div class="col-4">
                    <div class="form-group">
                      <label>PPN (%)</label>
                      <input class="form-control" type="text" name="edit_ppn_add" id="edit_ppn_add" placeholder="PPN (%)" />
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Harga Ongkir (Additional Price)</label>
                      <input class="form-control" type="text" name="edit_harga_ongkir_add" id="edit_harga_ongkir_add" placeholder="Harga Ongkir (Additional Price)" />
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="edit_total_price_add">Total Harga</label>
                      <input class="form-control" type="text" name="edit_total_price_add" id="edit_total_price_add" placeholder="Total Harga" />
                    </div>
                  </div>
                </div>
                <!-- <div class="row"> 
                  <div class="col-4"></div>
                  <div class="col-4"></div>
                  <div class="col-4">
                    <div class="form-group row" id="div_total_add" style="display: none;">
                      <label for="total_add" class="col-sm-3 col-form-label">Total</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="total_add" name="total_add" readonly />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row"> --> 
                  <div class="col-12">
                    <div class="form-group">
                      <label>Keterangan Add Price</label>
                      <textarea class="form-control" name="edit_keterangan_quotation" id="edit_keterangan_quotation" rows="3" placeholder="Keterangan Add Price"></textarea>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="edit_submit_validasi_adding_price_form">Save changes</button>
      </div>
    </form>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_new_products">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Products</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row"> 
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <h5>Customer Order</h5>
              </div>
              <div class="card-body">
                <input class="form-control" type="hidden" name="new_custid_utama" id="new_custid_utama"/>
                <input class="form-control" type="hidden" name="new_nomor_order_receipt" id="new_nomor_order_receipt"/>
                <p id="new_nama_customer_order"></p>
                <p id="new_phone_customer_order"></p>
                <p align="justify" id="new_alamat_customer_order"></p>
                <p id="new_kota_customer_order"></p>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card">
              <div class="card-header">
                <h5>Customer Receive</h5>
              </div>
              <div class="card-body">
                <input class="form-control" type="hidden" name="new_txt_kode_alamat" id="new_txt_kode_alamat"/>
                <p id="new_nama_customer_receive"></p>
                <p id="new_phone_customer_receive"></p>
                <p align="justify" id="new_alamat_customer_receive"></p>
                <p id="new_kota_customer_receive"></p>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <button type="button" name="btn_new_choose_another_address" id="btn_new_choose_another_address" class="btn btn-block btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_new_choose_address">Choose Another Address</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row"> 
                  <div class="col-5">
                    <div class="form-group">
                      <label>Products</label>
                      <select id="new_add_products" name="new_add_products" class="form-control">
                      </select>
                    </div>
                  </div>
                  <div class="col-3" id="new_div_input_quantity">
                    <div class="form-group">
                      <label>Quantity (KG)</label>
                      <input class="form-control" type="text" name="new_add_quantity" id="new_add_quantity" placeholder="Quantity (KG)" />
                      <span class="show_saldo" id="new_show_saldo_input" style="display: none;"></span>
                    </div>
                  </div>
                  <div class="col-3" id="new_div_select_quantity" style="display: none;">
                    <div class="form-group">
                      <label>Quantity (KG)</label>
                      <select id="new_select_add_quantity" name="new_select_add_quantity" class="form-control">
                      </select>
                      <span class="show_saldo" id="new_show_saldo_select" style="display: none;"></span>
                    </div>
                  </div>
                  <div class="col-2" id="div-btn-new-new">
                    <div class="form-group">
                      <label>&nbsp</label>
                      <input class="form-control btn btn-primary" type="button" name="btn-new-new-products" id="btn-new-new-products" value="New Products"/>
                    </div>
                  </div>
                  <div class="col-2" id="div-btn-new-cancel" style="display: none;">
                    <div class="form-group">
                      <label>&nbsp</label>
                      <input class="form-control btn btn-danger" type="button" name="btn-cancel" id="btn-new-cancel" value="Cancel"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label>Delivery Date</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input class="form-control" type="text" name="new_tanggal_kirim" id="new_tanggal_kirim" autocomplete="off" placeholder="Choose Delivery Date" />
                      </div>
                    </div>
                  </div>
                  <input class="form-control" type="hidden" name="new_kode_produk" id="new_kode_produk" />
                  <div class="col-6">
                    <div class="form-group">
                      <label>Ekspedisi</label>
                      <select id="new_ekspedisi" name="new_ekspedisi" class="form-control">
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row"> 
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row"> 
                  <div class="col-3">
                    <div class="form-group">
                      <label>Harga Pokok</label>
                      <input class="form-control" type="text" name="new_pokok_add" id="new_pokok_add" placeholder="Harga Pokok" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="new_stapel_add">Total Harga Stapel</label>
                      <input class="form-control" type="text" name="new_stapel_add" id="new_stapel_add" placeholder="Total Harga Stapel" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="new_ekspedisi_add">Total Harga Ekspedisi</label>
                      <input class="form-control" type="text" name="new_ekspedisi_add" id="new_ekspedisi_add" placeholder="Total Harga Ekspedisi" />
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label>Diskon (%)</label>
                      <input class="form-control" type="text" name="new_diskon_add" id="new_diskon_add" placeholder="Diskon (%)" />
                    </div>
                  </div>
                  <input class="form-control" type="hidden" name="new_harga_ongkir_add" id="new_harga_ongkir_add"/>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-3">
            <div class="form-group">
              <label>PPN (%)</label>
              <input class="form-control" type="text" name="new_ppn_add" id="new_ppn_add" placeholder="PPN (%)" />
            </div>
          </div>
          <div class="col-9">
            <div class="card">
              <div class="card-body">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control" name="new_keterangan_quotation" id="new_keterangan_quotation"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_edit_order">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-save-new-products">Save changes</button>
      </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_new_address">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create New Address</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" class="new_address_form" id="new_address_form" action="javascript:void(0)">
        {{ csrf_field() }}
        <div class="row">
          <input class="form-control" type="hidden" name="new_address_custid" id="new_address_custid"/>
          <div class="col-12">
            <div class="form-group">
              <label>Nama</label>
              <input class="form-control" type="text" name="new_address_name" id="new_address_name" placeholder="Nama" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="new_address_alamat">Alamat</label>
              <input class="form-control" type="text" name="new_address_alamat" id="new_address_alamat" placeholder="Alamat" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="new_address_kota">Kota</label>
              <select id="new_address_kota" name="new_address_kota" class="form-control select2 city-address" style="width: 100%;">
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="new_address_telepon">Telepon</label>
              <input class="form-control" type="text" name="new_address_telepon" id="new_address_telepon" placeholder="Telepon" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_input_orders">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_edit_new_address">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Create New Address</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" class="edit_new_address_form" id="edit_new_address_form" action="javascript:void(0)">
        {{ csrf_field() }}
        <div class="row">
          <input class="form-control" type="hidden" name="edit_new_address_custid" id="edit_new_address_custid"/>
          <input class="form-control" type="hidden" name="edit_new_address_nomor_sj_produk" id="edit_new_address_nomor_sj_produk"/>
          <div class="col-12">
            <div class="form-group">
              <label>Nama</label>
              <input class="form-control" type="text" name="edit_new_address_name" id="edit_new_address_name" placeholder="Nama" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="edit_new_address_alamat">Alamat</label>
              <input class="form-control" type="text" name="edit_new_address_alamat" id="edit_new_address_alamat" placeholder="Alamat" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="edit_new_address_kota">Kota</label>
              <select id="edit_new_address_kota" name="edit_new_address_kota" class="form-control select2 edit-city-address" style="width: 100%;">
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="edit_new_address_telepon">Telepon</label>
              <input class="form-control" type="text" name="edit_new_address_telepon" id="edit_new_address_telepon" placeholder="Telepon" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_edit_order">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_choose_address">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Choose Address</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#modal_new_address" id="btn-new-address-choose" class="btn btn-outline-secondary btn-lg btn-block" style="border: 2px dashed #868e96; margin-bottom: 20px;"><i class="fas fa-plus-circle"></i> Add New Address</button>
          <div id="div-address-list">
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

<div class="modal fade" id="modal_edit_choose_address">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Choose Address</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#modal_edit_new_address" id="btn-edit-new-address-choose" class="btn btn-outline-secondary btn-lg btn-block" style="border: 2px dashed #868e96; margin-bottom: 20px;"><i class="fas fa-plus-circle"></i> Add New Address</button>
          <div id="div-address-list-edit">
          </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#modal_edit_order">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal_new_choose_address">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Choose Address</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div id="new-div-address-list">
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

<div class="modal fade" id="modal_send_several_address">
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

  @section('right_nav')
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->
  @endsection

  @section('script_login')
  <script src="https://code.jquery.com/jquery.js"></script>
  <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="{{asset('lte/plugins/moment/moment.min.js')}}"></script>
  <script src="{{asset('lte/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
  <script src="{{asset('lte/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
  <script src="{{asset('lte/plugins/jquery-validation/additional-methods.min.js')}}"></script>
  <script src="{{asset('lte/plugins/select2/js/select2.full.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

  <script type="text/javascript">
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    $(function () {

      $('#filter_tanggal').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        }
      });

      $('#tanggal_kirim').flatpickr({
        allowInput: true,
        disableMobile: true
      });

      $('#input_tanggal_kirim').flatpickr({
        allowInput: true,
        defaultDate: new Date(),
        minDate: "today",
        disableMobile: true
      });

      $('#edit_tanggal_kirim').flatpickr({
        allowInput: true,
        disableMobile: true
      });

      $('#new_tanggal_kirim').flatpickr({
        allowInput: true,
        defaultDate: new Date(),
        minDate: "today",
        disableMobile: true
      });

      $('#input_tanggal_kirim_products').flatpickr({
        allowInput: true,
        defaultDate: new Date(),
        minDate: "today",
        disableMobile: true
      });
    });

    $('.select2').select2();

    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>

  <script>
    $(document).ready(function(){
      let key = "{{ env('MIX_APP_KEY') }}";

      var target = $('.nav-tabs a.nav-link.active').attr("href");

      var any_nomor = "{{ $any_nomor ?? '' }}";

      if(any_nomor.length > 12){
        encryptStr = CryptoJS.enc.Base64.parse(any_nomor);
        let encryptData = encryptStr.toString(CryptoJS.enc.Utf8);
        encryptData = JSON.parse(encryptData);
        let iv = CryptoJS.enc.Base64.parse(encryptData.iv);
        var decrypted = CryptoJS.AES.decrypt(encryptData.value,  CryptoJS.enc.Utf8.parse(key.substr(7)), {
          iv : iv,
          mode: CryptoJS.mode.CBC,
          padding: CryptoJS.pad.Pkcs7
        });
        decrypted = CryptoJS.enc.Utf8.stringify(decrypted);
        any_nomor = decrypted;
      }

      var table = $('#orders_new_table').DataTable({
       processing: true,
       serverSide: true,
       lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
       ajax: {
        url: '{{ url("orders/status/new") }}',
        error: function(jqXHR, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
        },
        data : function( d ) {
          d.nomor = any_nomor;
        }
       },
       dom: 'lBfrtip',
       buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
       order: [[0,'desc']],
       columns: [
        {
         data:'nomor_order_receipt',
         name:'nomor_order_receipt'
        },
        {
         data:'tanggal_order',
         name:'tanggal_order'
        },
        {
         data:'custname',
         name:'custname'
        },
        {
         data:'nomor_po',
         name:'nomor_po',
         defaultContent: '<i>--</i>'
        },
        {
         data:'action',
         name:'action',
         width:'15%'
        }
       ]
      });

      if(any_nomor != ''){
        table.ajax.url('{{ url("orders/status/specific") }}').load();
        $("ul.nav-tabs-lihat li a.nav-link").removeClass("active");
        $("ul.nav-tabs-lihat li:nth-of-type(1) a.nav-link").addClass("active").show();
      }

      $('.nav-tabs a').on('shown.bs.tab', function (e) {
        target = $(e.target).attr("href");
        if(target == '#semua_orders'){
          $('#orders_semua_table').DataTable().destroy();
          load_data_orders_semua();
        }else if(target == '#new_orders'){
          $('#orders_new_table').DataTable().destroy();
          load_data_orders_new();
        }else if(target == '#confirmation_orders'){
          $('#orders_confirmation_table').DataTable().destroy();
          load_data_orders_confirmation();
        }else if(target == '#produksi_orders'){
          $('#orders_produksi_table').DataTable().destroy();
          load_data_orders_produksi();
        }else if(target == '#delivery_orders'){
          $('#orders_delivery_table').DataTable().destroy();
          load_data_orders_delivery();
        }else if(target == '#transit_orders'){
          $('#orders_transit_table').DataTable().destroy();
          load_data_orders_transit();
        }else if(target == '#arrive_orders'){
          $('#orders_arrive_table').DataTable().destroy();
          load_data_orders_arrive();
        }
      });

      function load_data_orders_semua(from_date = '', to_date = '')
      {
        table = $('#orders_semua_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("orders/status/semua") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        createdRow: function(row, data, dataIndex){
          if(data.status_order == 1){
            $(row).attr('style', 'background-color:#fffd94');
          }else if(data.status_order == 2){
            $(row).attr('style', 'background-color:#d4fab6');
          }
       },
        columns: [
        {
         data:'nomor_order_receipt',
         name:'nomor_order_receipt'
       },
       {
         data:'tanggal_order',
         name:'tanggal_order'
       },
       {
         data:'custname',
         name:'custname'
       },
       {
         data:'nomor_po',
         name:'nomor_po',
         defaultContent: '<i>--</i>'
       },
       {
         data:'action',
         name:'action',
         width:'15%'
       }
       ]
        });
      }

      function load_data_orders_new(from_date = '', to_date = '')
      {
        table = $('#orders_new_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("orders/status/new") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_order_receipt',
         name:'nomor_order_receipt'
       },
       {
         data:'tanggal_order',
         name:'tanggal_order'
       },
       {
         data:'custname',
         name:'custname'
       },
       {
         data:'nomor_po',
         name:'nomor_po',
         defaultContent: '<i>--</i>'
       },
       {
         data:'action',
         name:'action',
         width:'15%'
       }
       ]
        });
      }

      function load_data_orders_confirmation(from_date = '', to_date = '')
      {
        table = $('#orders_confirmation_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("orders/status/confirmation") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_order_receipt',
         name:'nomor_order_receipt'
       },
       {
         data:'tanggal_order',
         name:'tanggal_order'
       },
       {
         data:'custname',
         name:'custname'
       },
       {
         data:'nomor_po',
         name:'nomor_po',
         defaultContent: '<i>--</i>'
       },
       {
         data:'action',
         name:'action',
         width:'15%'
       }
       ]
        });
      }

      function load_data_orders_produksi(from_date = '', to_date = '')
      {
        table = $('#orders_produksi_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("orders/status/produksi") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_order_receipt',
         name:'nomor_order_receipt'
       },
       {
         data:'tanggal_order',
         name:'tanggal_order'
       },
       {
         data:'custname',
         name:'custname'
       },
       {
         data:'nomor_po',
         name:'nomor_po',
         defaultContent: '<i>--</i>'
       },
       {
         data:'action',
         name:'action',
         width:'15%'
       }
       ]
        });
      }

      function load_data_orders_delivery(from_date = '', to_date = '')
      {
        table = $('#orders_delivery_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("orders/status/delivery") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_order_receipt',
         name:'nomor_order_receipt'
       },
       {
         data:'tanggal_order',
         name:'tanggal_order'
       },
       {
         data:'custname',
         name:'custname'
       },
       {
         data:'nomor_po',
         name:'nomor_po',
         defaultContent: '<i>--</i>'
       },
       {
         data:'action',
         name:'action',
         width:'15%'
       }
       ]
        });
      }

      function load_data_orders_transit(from_date = '', to_date = '')
      {
        table = $('#orders_transit_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("orders/status/transit") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_order_receipt',
         name:'nomor_order_receipt'
       },
       {
         data:'tanggal_order',
         name:'tanggal_order'
       },
       {
         data:'custname',
         name:'custname'
       },
       {
         data:'nomor_po',
         name:'nomor_po',
         defaultContent: '<i>--</i>'
       },
       {
         data:'action',
         name:'action',
         width:'15%'
       }
       ]
        });
      }

      function load_data_orders_arrive(from_date = '', to_date = '')
      {
        table = $('#orders_arrive_table').DataTable({
         processing: true,
         serverSide: true,
         lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
         ajax: {
          url:'{{ url("orders/status/arrive") }}',
          data:{from_date:from_date, to_date:to_date},
          error: function(jqXHR, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
          }
        },
        dom: 'lBfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[0,'desc']],
        columns: [
        {
         data:'nomor_order_receipt',
         name:'nomor_order_receipt'
       },
       {
         data:'tanggal_order',
         name:'tanggal_order'
       },
       {
         data:'custname',
         name:'custname'
       },
       {
         data:'nomor_po',
         name:'nomor_po',
         defaultContent: '<i>--</i>'
       },
       {
         data:'action',
         name:'action',
         width:'15%'
       }
       ]
        });
      }

      $('#filter').click(function(){
        var from_date = $('#filter_tanggal').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var to_date = $('#filter_tanggal').data('daterangepicker').endDate.format('YYYY-MM-DD');
        if(from_date != '' &&  to_date != '')
        {
          if(target == '#semua_orders'){
            $('#orders_semua_table').DataTable().destroy();
            load_data_orders_semua(from_date, to_date);
          }else if(target == '#new_orders'){
            $('#orders_new_table').DataTable().destroy();
            load_data_orders_new(from_date, to_date);
          }else if(target == '#confirmation_orders'){
            $('#orders_confirmation_table').DataTable().destroy();
            load_data_orders_confirmation(from_date, to_date);
          }else if(target == '#produksi_orders'){
            $('#orders_produksi_table').DataTable().destroy();
            load_data_orders_produksi(from_date, to_date);
          }else if(target == '#delivery_orders'){
            $('#orders_delivery_table').DataTable().destroy();
            load_data_orders_delivery(from_date, to_date);
          }else if(target == '#transit_orders'){
            $('#orders_transit_table').DataTable().destroy();
            load_data_orders_transit(from_date, to_date);
          }else if(target == '#arrive_orders'){
            $('#orders_arrive_table').DataTable().destroy();
            load_data_orders_arrive(from_date, to_date);
          }
        }
        else
        {
          alert('Both Date is required');
        }
      });

      $('#refresh').click(function(){
        $('#filter_tanggal').val('');
        if(target == '#semua_orders'){
          $('#orders_semua_table').DataTable().destroy();
          load_data_orders_semua();
        }else if(target == '#new_orders'){
          $('#orders_new_table').DataTable().destroy();
          load_data_orders_new();
        }else if(target == '#confirmation_orders'){
          $('#orders_confirmation_table').DataTable().destroy();
          load_data_orders_confirmation();
        }else if(target == '#produksi_orders'){
          $('#orders_produksi_table').DataTable().destroy();
          load_data_orders_produksi();
        }else if(target == '#delivery_orders'){
          $('#orders_delivery_table').DataTable().destroy();
          load_data_orders_delivery();
        }else if(target == '#transit_orders'){
          $('#orders_transit_table').DataTable().destroy();
          load_data_orders_transit();
        }else if(target == '#arrive_orders'){
          $('#orders_arrive_table').DataTable().destroy();
          load_data_orders_arrive();
        }
      });
    });
  </script>

  <script>
    $(document).ready(function(){
      let key = "{{ env('MIX_APP_KEY') }}";

      function load_data_products(custid = '')
      {
        $('#input_order_products_table').DataTable({
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

      function load_data_several_products(custid = '')
      {
        $('#input_order_products_several_table').DataTable({
          processing: true,
          serverSide: true,
          language: {
            emptyTable: "Add products you want to buy"
          },
          ajax: {
            url:'{{ url("order_sales/products/several") }}',
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
      }

      function load_data_delivdate_products(custid = '')
      {
        $('#input_order_products_delivdate_table').DataTable({
          processing: true,
          serverSide: true,
          language: {
            emptyTable: "Add products you want to buy"
          },
          ajax: {
            url:'{{ url("order_sales/products/delivdate") }}',
            data:{custid:custid}
          },
          dom: 'tr',
          sort: false,
          order: [[0,'desc']],
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

      function load_data_detail_products(nomor_sj = ''){
        $('#order_products_table').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: '{{ url("products/order/admin") }}',
            data:{nomor_sj:nomor_sj}
          },
          dom: 'tr',
          sort: false,
          columns: [
          {
           data:'nama_produk',
           name:'nama_produk'
         },
         {
           data:'qty',
           name:'qty',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'harga',
           name:'harga',
           render: $.fn.dataTable.render.number( '.','','', 'Rp '),
           defaultContent: '<i>Belum Ditentukan</i>'
         },
         {
           data:'total',
           name:'total',
           render: $.fn.dataTable.render.number( '.','','', 'Rp '),
           defaultContent: '<i>Belum Ditentukan</i>'
         }
         ]
       });
      }

      function load_data_edit_detail_products(nomor_sj = ''){
        $('#edit_order_products_table').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: '{{ url("products/order/admin/edit") }}',
            data:{nomor_sj:nomor_sj},
            error: function(jqXHR, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
            }
          },
          dom: 'tr',
          sort: false,
          columns: [
          {
           data:'nama_produk',
           name:'nama_produk'
         },
         {
           data:'qty',
           name:'qty',
           render: $.fn.dataTable.render.number('.', " KG", ',')
         },
         {
           data:'harga',
           name:'harga',
           render: $.fn.dataTable.render.number( '.','','', 'Rp '),
           defaultContent: '<i>Belum Ditentukan</i>'
         },
         {
           data:'total',
           name:'total',
           render: $.fn.dataTable.render.number( '.','','', 'Rp '),
           defaultContent: '<i>Belum Ditentukan</i>'
         },
         {
           data:'action',
           name:'action',
           width:'5%'
         }
         ]
       });
      }

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

      $('body').on('click', '#add-price-orders', function () {
        var custid = $(this).data('id');
        var nomor_sj = $(this).data('sj');
        // $('#nomor_complaint').val(complaint);
        var url = "{{ url('detail/order/produk/no_sj') }}";
        url = url.replace('no_sj', enc(nomor_sj.toString()));
        $('#title-detail-order').html('Detail Order ' + nomor_sj);
        $('#nomor_order_receipt').val(nomor_sj);
        $.get(url, function (data_prd) {
          $('#products_add').children().remove().end();
          $('#products_add').append('<option value="' + data_prd[0].nomor_order_receipt_produk + '" selected>' + data_prd[0].nama_produk + ' | (QTY : ' + data_prd[0].qty + ' KG)</option>');
          $.each(data_prd.slice(1), function(k, v) {
            $('#products_add').append('<option value="' + v.nomor_order_receipt_produk + '">' + v.nama_produk + ' | (QTY : ' + data_prd[0].qty + ' KG)</option>');
          });
          $('#print_sp').hide();
          var url_data = "{{ url('detail/order/no_sj/no_sj_produk') }}";
          url_data = url_data.replace('no_sj', enc(nomor_sj.toString()));
          url_data = url_data.replace('no_sj_produk', enc(data_prd[0].nomor_order_receipt_produk.toString()));
          $.get(url_data, function (data) {
            $('#nama_customer_order').html('<h5>' + data.data.nama_cust_order + ' (' + custid + ')</h5>');
            if(data.data.phone_cust_order == null || data.data.phone_cust_order == ''){
              $('#phone_customer_order').html('<b>Phone: </b>-----');
            }else{
              $('#phone_customer_order').html('<b>Phone: </b>' + data.data.phone_cust_order);
            }
            $('#alamat_customer_order').html('<b>Address: </b>' + data.data.alamat_cust_order);
            $('#kota_customer_order').html('<b>City: </b>' + data.data.city_cust_order);
            if(data.data.npwp_cust_order == null || data.data.npwp_cust_order == ''){
              $('#npwp_customer_order').html('<b>NPWP: </b>-----');
            }else{
              $('#npwp_customer_order').html('<b>NPWP: </b>' + data.data.npwp_cust_order);
            }
            $('#nama_customer_receive').html('<h5>' + data.data.nama_cust_receive + '</h5>');
            if(data.data.phone_cust_receive == null || data.data.phone_cust_receive == ''){
              $('#phone_customer_receive').html('<b>Phone: </b>-----');
            }else{
              $('#phone_customer_receive').html('<b>Phone: </b>' + data.data.phone_cust_receive);
            }
            if(data.data.ekspedisi != null || data.data.ekspedisi != ''){
              $('#ekspedisi').children().remove().end().append('<option value="' + data.data.ekspedisi + '" selected disabled>' + data.data.nama_ekspedisi + '</option>');
            }
            if(data.data.crd == 1){
              $('#crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data.data.crd + ' Hari Tunai / Transfer');
            }else{
              $('#crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data.data.crd + ' Hari');
            }
            $('#alamat_customer_receive').html('<b>Address: </b>' + data.data.alamat_cust_receive);
            $('#kota_customer_receive').html('<b>City: </b>' + data.data.city_cust_receive);
            $('#tanggal_kirim').val(data.data.tanggal_kirim);
            $('#nomor_po').val(data.data.nomor_po);
            $('#nomor_order_receipt_produk').val(data_prd[0].nomor_order_receipt_produk);
            $('#pokok_add').val('');
            $('#stapel_add').val('');
            $('#ekspedisi_add').val('');
            $('#diskon_add').val('');
            $('#ppn_add').val("{{ env('PPN_VAL') }}");
            $('#harga_ongkir_add').val('');
            $('#keterangan_quotation').val('');
            if(data.data.status_order == 1){
              if(data.data.status_prd == 1){
                $('#div-keterangan').show();
                $('#div-referensi').hide();
                $('#keterangan').val(data.data.keterangan_order);
                $('#konfirmasi-orderan').show();
                $('#submit_validasi_adding_price_form').show();
                $('#print_sp').hide();
                $('#nomor_referensi').val('');
                var url_ekspedisi = "{{ url('dropdown/order_sales/ekspedisi/custid/nomor_sj/id_alamat/nomor_sj_produk') }}";
                url_ekspedisi = url_ekspedisi.replace('custid', enc(custid.toString()));
                url_ekspedisi = url_ekspedisi.replace('nomor_sj', enc(nomor_sj.toString()));
                url_ekspedisi = url_ekspedisi.replace('id_alamat', enc(data.data.id_alamat.toString()));
                url_ekspedisi = url_ekspedisi.replace('nomor_sj_produk', enc(data_prd[0].nomor_order_receipt_produk.toString()));

                $.get(url_ekspedisi, function (data_ekspedisi) {
                  console.log(data_ekspedisi);
                  if(Object.keys(data_ekspedisi).length !== 0){
                    $('#ekspedisi').children().remove().end().append('<option value="">Pilih Ekspedisi</option>');
                    $('#ekspedisi').append('<option value="' + data_ekspedisi[0].kode_ekspedisi + '" selected>(Data History) ' + data_ekspedisi[0].nama_ekspedisi + '</option>');
                    $.each(data_ekspedisi.slice(1), function(k, v) {
                      $('#ekspedisi').append('<option value="' + v.kode_ekspedisi + '">(Data History) ' + v.nama_ekspedisi + '</option>');
                    });
                    $('#ekspedisi').append('<option value="EKSBUANA">(Buat Data Baru) Buana</option>');
                    $('#ekspedisi').append('<option value="EKSGJAYA">(Buat Data Baru) Gunawan Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSRAPI">(Buat Data Baru) Rapi</option>');
                    $('#ekspedisi').append('<option value="EKSSJAYA">(Buat Data Baru) Sumber Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSTRANS">(Buat Data Baru) Transindo</option>');
                    $('#ekspedisi').append('<option value="EKSPRISMA">(Buat Data Baru) CV Prisma</option>');
                    $('#ekspedisi').append('<option value="EKSNIAGA">(Buat Data Baru) Niaga Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSLOKAL">(Buat Data Baru) Kirim Lingkup Area DSGM</option>');
                    $('#ekspedisi').append('<option value="EKSDSGM">(Buat Data Baru) Ambil Sendiri</option>');
                    $('#pokok_add').val(data_ekspedisi[0].harga_pokok);
                    $('#stapel_add').val(data_ekspedisi[0].harga_stapel);
                    $('#ekspedisi_add').val(data_ekspedisi[0].harga_ekspedisi_total);
                    $('#diskon_add').val(data_ekspedisi[0].diskon);
                    $('#ppn_add').val(data_ekspedisi[0].ppn);
                    $('#harga_ongkir_add').val(data_ekspedisi[0].total_harga_kg);
                    $('#keterangan_quotation').val(data_ekspedisi[0].keterangan);
                    $("#ekspedisi").change(function() {
                      $('#pokok_add').val('');
                      $('#stapel_add').val('');
                      $('#ekspedisi_add').val('');
                      $('#diskon_add').val('');
                      $('#ppn_add').val("{{ env('PPN_VAL') }}");
                      $('#harga_ongkir_add').val('');
                      $('#keterangan_quotation').val('');
                      for(var i = 0; i < data_ekspedisi.length; i++){
                        if($(this).val() == data_ekspedisi[i].kode_ekspedisi){
                          $('#pokok_add').val(data_ekspedisi[i].harga_pokok);
                          $('#stapel_add').val(data_ekspedisi[i].harga_stapel);
                          $('#ekspedisi_add').val(data_ekspedisi[i].harga_ekspedisi_total);
                          $('#diskon_add').val(data_ekspedisi[i].diskon);
                          $('#ppn_add').val(data_ekspedisi[i].ppn);
                          $('#harga_ongkir_add').val(data_ekspedisi[i].total_harga_kg);
                          $('#keterangan_quotation').val(data_ekspedisi[i].keterangan);
                        }
                      }
                    });
                  }else{
                    $('#ekspedisi').children().remove().end().append('<option value="" selected>Pilih Ekspedisi</option>');
                    $('#ekspedisi').append('<option value="EKSBUANA">Buana</option>');
                    $('#ekspedisi').append('<option value="EKSGJAYA">Gunawan Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSRAPI">Rapi</option>');
                    $('#ekspedisi').append('<option value="EKSSJAYA">Sumber Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSTRANS">Transindo</option>');
                    $('#ekspedisi').append('<option value="EKSPRISMA">CV Prisma</option>');
                    $('#ekspedisi').append('<option value="EKSNIAGA">Niaga Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSLOKAL">Kirim Lingkup Area DSGM</option>');
                    $('#ekspedisi').append('<option value="EKSDSGM">Ambil Sendiri</option>');
                  }
                })
              }else{
                $('#div-keterangan').show();
                $('#div-referensi').hide();
                $('#keterangan').val(data.data.keterangan_quotation);
                $('#konfirmasi-orderan').hide();
                $('#submit_validasi_adding_price_form').hide();
                $('#tanggal_kirim').css('pointer-events', 'none');  
                $('#print_sp').hide();
                $('#nomor_referensi').val('');
              }
            }else if(data.data.status_order == 2){
              $('#div-keterangan').show();
              $('#div-referensi').show();
              $('#keterangan').val(data.data.keterangan_quotation);
              $('#konfirmasi-orderan').hide();
              $('#submit_validasi_adding_price_form').hide();
              $('#tanggal_kirim').css('pointer-events', 'none');  
              var url_print = "{{ url('print_surat_pesanan/nomor_sj') }}";
              url_print = url_print.replace('nomor_sj', enc(nomor_sj.toString()));
              $("#print_sp").attr("href", url_print);
              // $('#print_sp').data('sj', nomor_sj);
              $('#print_sp').show();
              $('#nomor_referensi').val(data.referensi.join("; "));
            }else if(data.data.status_order == 3){
              $('#div-keterangan').show();
              $('#div-referensi').show();
              $('#keterangan').val(data.data.keterangan_quotation);
              $('#konfirmasi-orderan').hide();
              $('#submit_validasi_adding_price_form').hide();
              $('#print_sp').hide();
              $('#nomor_referensi').val(data.referensi.join("; "));
            }else{
              $('#div-keterangan').hide();
              $('#div-referensi').hide();
              $('#konfirmasi-orderan').hide();
              $('#submit_validasi_adding_price_form').hide();
              $('#print_sp').hide();
              $('#nomor_referensi').val('');
            }
          })
        })
        $("#products_add").change(function() {
          var select_produk = $(this).val();
          var url_data = "{{ url('detail/order/no_sj/no_sj_produk') }}";
          url_data = url_data.replace('no_sj', enc(nomor_sj.toString()));
          url_data = url_data.replace('no_sj_produk', enc(select_produk.toString()));
          $.get(url_data, function (data) {
            $('#nama_customer_order').html('<h5>' + data.data.nama_cust_order + ' (' + custid + ')</h5>');
            if(data.data.phone_cust_order == null || data.data.phone_cust_order == ''){
              $('#phone_customer_order').html('<b>Phone: </b>-----');
            }else{
              $('#phone_customer_order').html('<b>Phone: </b>' + data.data.phone_cust_order);
            }
            $('#alamat_customer_order').html('<b>Address: </b>' + data.data.alamat_cust_order);
            $('#kota_customer_order').html('<b>City: </b>' + data.data.city_cust_order);
            if(data.data.npwp_cust_order == null || data.data.npwp_cust_order == ''){
              $('#npwp_customer_order').html('<b>NPWP: </b>-----');
            }else{
              $('#npwp_customer_order').html('<b>NPWP: </b>' + data.data.npwp_cust_order);
            }
            $('#nama_customer_receive').html('<h5>' + data.data.nama_cust_receive + '</h5>');
            if(data.data.phone_cust_receive == null || data.data.phone_cust_receive == ''){
              $('#phone_customer_receive').html('<b>Phone: </b>-----');
            }else{
              $('#phone_customer_receive').html('<b>Phone: </b>' + data.data.phone_cust_receive);
            }
            if(data.data.ekspedisi != null || data.data.ekspedisi != ''){
              $('#ekspedisi').children().remove().end().append('<option value="' + data.data.ekspedisi + '" selected disabled>' + data.data.nama_ekspedisi + '</option>');
            }
            if(data.data.crd == 1){
              $('#crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data.data.crd + ' Hari Tunai / Transfer');
            }else{
              $('#crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data.data.crd + ' Hari');
            }
            $('#alamat_customer_receive').html('<b>Address: </b>' + data.data.alamat_cust_receive);
            $('#kota_customer_receive').html('<b>City: </b>' + data.data.city_cust_receive);
            $('#tanggal_kirim').val(data.data.tanggal_kirim);
            $('#nomor_po').val(data.data.nomor_po);
            $('#nomor_order_receipt_produk').val(select_produk);
            $('#pokok_add').val('');
            $('#stapel_add').val('');
            $('#ekspedisi_add').val('');
            $('#diskon_add').val('');
            $('#ppn_add').val("{{ env('PPN_VAL') }}");
            $('#harga_ongkir_add').val('');
            $('#keterangan_quotation').val('');
            if(data.data.status_order == 1){
              if(data.data.status_prd == 1){
                $('#div-keterangan').show();
                $('#div-referensi').hide();
                $('#keterangan').val(data.data.keterangan_order);
                $('#konfirmasi-orderan').show();
                $('#submit_validasi_adding_price_form').show();
                $('#print_sp').hide();
                $('#nomor_referensi').val('');
                var url_ekspedisi = "{{ url('dropdown/order_sales/ekspedisi/custid/nomor_sj/id_alamat/nomor_sj_produk') }}";
                url_ekspedisi = url_ekspedisi.replace('custid', enc(custid.toString()));
                url_ekspedisi = url_ekspedisi.replace('nomor_sj', enc(nomor_sj.toString()));
                url_ekspedisi = url_ekspedisi.replace('id_alamat', enc(data.data.id_alamat.toString()));
                url_ekspedisi = url_ekspedisi.replace('nomor_sj_produk', enc(select_produk.toString()));

                $.get(url_ekspedisi, function (data_ekspedisi) {
                  if(Object.keys(data_ekspedisi).length !== 0){
                    $('#ekspedisi').children().remove().end().append('<option value="">Pilih Ekspedisi</option>');
                    $('#ekspedisi').append('<option value="' + data_ekspedisi[0].kode_ekspedisi + '" selected>(Data History) ' + data_ekspedisi[0].nama_ekspedisi + '</option>');
                    $.each(data_ekspedisi.slice(1), function(k, v) {
                      $('#ekspedisi').append('<option value="' + v.kode_ekspedisi + '">(Data History) ' + v.nama_ekspedisi + '</option>');
                    });
                    $('#ekspedisi').append('<option value="EKSBUANA">(Buat Data Baru) Buana</option>');
                    $('#ekspedisi').append('<option value="EKSGJAYA">(Buat Data Baru) Gunawan Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSRAPI">(Buat Data Baru) Rapi</option>');
                    $('#ekspedisi').append('<option value="EKSSJAYA">(Buat Data Baru) Sumber Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSTRANS">(Buat Data Baru) Transindo</option>');
                    $('#ekspedisi').append('<option value="EKSPRISMA">(Buat Data Baru) CV Prisma</option>');
                    $('#ekspedisi').append('<option value="EKSNIAGA">(Buat Data Baru) Niaga Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSLOKAL">(Buat Data Baru) Kirim Lingkup Area DSGM</option>');
                    $('#ekspedisi').append('<option value="EKSDSGM">(Buat Data Baru) Ambil Sendiri</option>');
                    $('#pokok_add').val(data_ekspedisi[0].harga_pokok);
                    $('#stapel_add').val(data_ekspedisi[0].harga_stapel);
                    $('#ekspedisi_add').val(data_ekspedisi[0].harga_ekspedisi_total);
                    $('#diskon_add').val(data_ekspedisi[0].diskon);
                    $('#ppn_add').val(data_ekspedisi[0].ppn);
                    $('#harga_ongkir_add').val(data_ekspedisi[0].total_harga_kg);
                    $('#keterangan_quotation').val(data_ekspedisi[0].keterangan);
                    $("#ekspedisi").change(function() {
                      $('#pokok_add').val('');
                      $('#stapel_add').val('');
                      $('#ekspedisi_add').val('');
                      $('#diskon_add').val('');
                      $('#ppn_add').val("{{ env('PPN_VAL') }}");
                      $('#harga_ongkir_add').val('');
                      $('#keterangan_quotation').val('');
                      for(var i = 0; i < data_ekspedisi.length; i++){
                        if($(this).val() == data_ekspedisi[i].kode_ekspedisi){
                          $('#pokok_add').val(data_ekspedisi[i].harga_pokok);
                          $('#stapel_add').val(data_ekspedisi[i].harga_stapel);
                          $('#ekspedisi_add').val(data_ekspedisi[i].harga_ekspedisi_total);
                          $('#diskon_add').val(data_ekspedisi[i].diskon);
                          $('#ppn_add').val(data_ekspedisi[i].ppn);
                          $('#harga_ongkir_add').val(data_ekspedisi[i].total_harga_kg);
                          $('#keterangan_quotation').val(data_ekspedisi[i].keterangan);
                        }
                      }
                    });
                  }else{
                    $('#ekspedisi').children().remove().end().append('<option value="" selected>Pilih Ekspedisi</option>');
                    $('#ekspedisi').append('<option value="EKSBUANA">Buana</option>');
                    $('#ekspedisi').append('<option value="EKSGJAYA">Gunawan Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSRAPI">Rapi</option>');
                    $('#ekspedisi').append('<option value="EKSSJAYA">Sumber Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSTRANS">Transindo</option>');
                    $('#ekspedisi').append('<option value="EKSPRISMA">CV Prisma</option>');
                    $('#ekspedisi').append('<option value="EKSNIAGA">Niaga Jaya</option>');
                    $('#ekspedisi').append('<option value="EKSLOKAL">Kirim Lingkup Area DSGM</option>');
                    $('#ekspedisi').append('<option value="EKSDSGM">Ambil Sendiri</option>');
                  }
                })
              }else{
                $('#div-keterangan').show();
                $('#div-referensi').hide();
                $('#keterangan').val(data.data.keterangan_quotation);
                $('#konfirmasi-orderan').hide();
                $('#submit_validasi_adding_price_form').hide();
                $('#tanggal_kirim').css('pointer-events', 'none');  
                $('#print_sp').hide();
                $('#nomor_referensi').val('');
              }
            }else if(data.data.status_order == 2){
              $('#div-keterangan').show();
              $('#div-referensi').show();
              $('#keterangan').val(data.data.keterangan_quotation);
              $('#konfirmasi-orderan').hide();
              $('#submit_validasi_adding_price_form').hide();
              $('#tanggal_kirim').css('pointer-events', 'none');  
              var url_print = "{{ url('print_surat_pesanan/nomor_sj') }}";
              url_print = url_print.replace('nomor_sj', enc(nomor_sj.toString()));
              $("#print_sp").attr("href", url_print);
              // $('#print_sp').data('sj', nomor_sj);
              $('#print_sp').show();
              $('#nomor_referensi').val(data.referensi.join("; "));
            }else if(data.data.status_order == 3){
              $('#div-keterangan').show();
              $('#div-referensi').show();
              $('#keterangan').val(data.data.keterangan_quotation);
              $('#konfirmasi-orderan').hide();
              $('#submit_validasi_adding_price_form').hide();
              $('#print_sp').hide();
              $('#nomor_referensi').val(data.referensi.join("; "));
            }else{
              $('#div-keterangan').hide();
              $('#div-referensi').hide();
              $('#konfirmasi-orderan').hide();
              $('#submit_validasi_adding_price_form').hide();
              $('#print_sp').hide();
              $('#nomor_referensi').val('');
            }
          })
        });
        $('#order_products_table').DataTable().destroy();
        load_data_detail_products(nomor_sj);
      });

      $('body').on('click', '#edit-orders', function () {
        var custid = $(this).data('id');
        var nomor_sj = $(this).data('sj');
        var url_prd = "{{ url('get_products') }}";
        $.get(url_prd, function (data_products) {
          $('#edit_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
          $.each(data_products, function(k, v) {
            $('#edit_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
          });
        })
        $('#div_edit_products').hide();
        $('#edit_show_saldo_input').html('').hide();
        $('#edit_new_produk').data('id', custid);
        $('#edit_new_produk').data('sj', nomor_sj);
        var url = "{{ url('detail/order/produk/no_sj') }}";
        url = url.replace('no_sj', enc(nomor_sj.toString()));
        $('#title-edit-order').html('Edit Order ' + nomor_sj);
        $('#edit_nomor_order').val(nomor_sj);
        $('#edit_nomor_order_receipt').val(nomor_sj);
        $('#input_custid').val(custid);
        $.get(url, function (data_prd) {
          $('#edit_products_add').children().remove().end();
          $('#edit_products_add').append('<option value="' + data_prd[0].nomor_order_receipt_produk + '" selected>' + data_prd[0].nama_produk + ' | (QTY : ' + data_prd[0].qty + ' KG)</option>');
          $.each(data_prd.slice(1), function(k, v) {
            $('#edit_products_add').append('<option value="' + v.nomor_order_receipt_produk + '">' + v.nama_produk + ' | (QTY : ' + data_prd[0].qty + ' KG)</option>');
          });
          var url_data = "{{ url('detail/order/no_sj/no_sj_produk') }}";
          url_data = url_data.replace('no_sj', enc(nomor_sj.toString()));
          url_data = url_data.replace('no_sj_produk', enc(data_prd[0].nomor_order_receipt_produk.toString()));
          $.get(url_data, function (data) {
            $('#edit_nama_customer_order').html('<h5>' + data.data.nama_cust_order + ' (' + custid + ')</h5>');
            if(data.data.phone_cust_order == null || data.data.phone_cust_order == ''){
              $('#edit_phone_customer_order').html('<b>Phone: </b>-----');
            }else{
              $('#edit_phone_customer_order').html('<b>Phone: </b>' + data.data.phone_cust_order);
            }
            $('#edit_alamat_customer_order').html('<b>Address: </b>' + data.data.alamat_cust_order);
            $('#edit_kota_customer_order').html('<b>City: </b>' + data.data.city_cust_order);
            if(data.data.npwp_cust_order == null || data.data.npwp_cust_order == ''){
              $('#edit_npwp_customer_order').html('<b>NPWP: </b>-----');
            }else{
              $('#edit_npwp_customer_order').html('<b>NPWP: </b>' + data.data.npwp_cust_order);
            }
            $('#edit_nama_customer_receive').html('<h5>' + data.data.nama_cust_receive + '</h5>');
            if(data.data.phone_cust_receive == null || data.data.phone_cust_receive == ''){
              $('#edit_phone_customer_receive').html('<b>Phone: </b>-----');
            }else{
              $('#edit_phone_customer_receive').html('<b>Phone: </b>' + data.data.phone_cust_receive);
            }
            if(data.data.ekspedisi != null || data.data.ekspedisi != ''){
              $('#edit_ekspedisi').children().remove().end().append('<option value="' + data.data.ekspedisi + '" selected disabled>' + data.data.nama_ekspedisi + '</option>');
            }
            if(data.data.crd == 1){
              $('#edit_crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data.data.crd + ' Hari Tunai / Transfer');
            }else{
              $('#edit_crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data.data.crd + ' Hari');
            }
            $('#edit_alamat_customer_receive').html('<b>Address: </b>' + data.data.alamat_cust_receive);
            $('#edit_kota_customer_receive').html('<b>City: </b>' + data.data.city_cust_receive);
            $('#edit_tanggal_kirim').val(data.data.tanggal_kirim);
            $('#edit_nomor_po').val(data.data.nomor_po);
            $('#edit_nomor_order_receipt_produk').val(data_prd[0].nomor_order_receipt_produk);
            $('#edit_pokok_add').val('');
            $('#edit_stapel_add').val('');
            $('#edit_ekspedisi_add').val('');
            $('#edit_diskon_add').val('');
            $('#edit_ppn_add').val("{{ env('PPN_VAL') }}");
            $('#edit_harga_ongkir_add').val('');
            $('#edit_total_price_add').val('');
            $('#edit_keterangan_quotation').val('');
            $('#edit_keterangan').val(data.data.keterangan_order);
            $('#btn-edit-new-address-choose').data('id', custid);
            $('#btn-edit-new-address-choose').data('sj', data_prd[0].nomor_order_receipt_produk);
            var url_ekspedisi = "{{ url('edit_order_sales/detail/nomor_sj/nomor_sj_produk') }}";
            url_ekspedisi = url_ekspedisi.replace('nomor_sj', enc(nomor_sj.toString()));
            url_ekspedisi = url_ekspedisi.replace('nomor_sj_produk', enc(data_prd[0].nomor_order_receipt_produk.toString()));
            $.get(url_ekspedisi, function (data_ekspedisi) {
              $('#edit_ekspedisi').children().remove().end().append('<option value="">Pilih Ekspedisi</option>');
              $('#edit_ekspedisi').append('<option value="' + data_ekspedisi.kode_ekspedisi + '" selected>(Current Data) ' + data_ekspedisi.nama_ekspedisi + '</option>');
              $('#edit_ekspedisi').append('<option value="EKSBUANA">(Buat Data Baru) Buana</option>');
              $('#edit_ekspedisi').append('<option value="EKSGJAYA">(Buat Data Baru) Gunawan Jaya</option>');
              $('#edit_ekspedisi').append('<option value="EKSRAPI">(Buat Data Baru) Rapi</option>');
              $('#edit_ekspedisi').append('<option value="EKSSJAYA">(Buat Data Baru) Sumber Jaya</option>');
              $('#edit_ekspedisi').append('<option value="EKSTRANS">(Buat Data Baru) Transindo</option>');
              $('#edit_ekspedisi').append('<option value="EKSPRISMA">(Buat Data Baru) CV Prisma</option>');
              $('#edit_ekspedisi').append('<option value="EKSNIAGA">(Buat Data Baru) Niaga Jaya</option>');
              $('#edit_ekspedisi').append('<option value="EKSLOKAL">(Buat Data Baru) Kirim Lingkup Area DSGM</option>');
              $('#edit_ekspedisi').append('<option value="EKSDSGM">(Buat Data Baru) Ambil Sendiri</option>');
              $('#edit_pokok_add').val(data_ekspedisi.harga_satuan);
              $('#edit_stapel_add').val(data_ekspedisi.staple_cost);
              $('#edit_ekspedisi_add').val(data_ekspedisi.ekspedisi_cost);
              $('#edit_diskon_add').val(data_ekspedisi.discount);
              $('#edit_ppn_add').val(data_ekspedisi.ppn);
              $('#edit_harga_ongkir_add').val(data_ekspedisi.additional_price);
              $('#edit_total_price_add').val(data_ekspedisi.total_price);
              $('#edit_keterangan_quotation').val(data_ekspedisi.keterangan_quotation);
              $("#edit_ekspedisi").change(function() {
                $('#edit_pokok_add').val('');
                $('#edit_stapel_add').val('');
                $('#edit_ekspedisi_add').val('');
                $('#edit_diskon_add').val('');
                $('#edit_ppn_add').val("{{ env('PPN_VAL') }}");
                $('#edit_harga_ongkir_add').val('');
                $('#edit_keterangan_quotation').val('');
                $('#edit_total_price_add').val('');
                if($(this).val() == data_ekspedisi.kode_ekspedisi){
                  $('#edit_pokok_add').val(data_ekspedisi.harga_pokok);
                  $('#edit_stapel_add').val(data_ekspedisi.harga_stapel);
                  $('#edit_ekspedisi_add').val(data_ekspedisi.harga_ekspedisi_total);
                  $('#edit_diskon_add').val(data_ekspedisi.diskon);
                  $('#edit_ppn_add').val(data_ekspedisi.ppn);
                  $('#edit_harga_ongkir_add').val(data_ekspedisi.total_harga_kg);
                  $('#edit_total_price_add').val(data_ekspedisi.total_price);
                  $('#edit_keterangan_quotation').val(data_ekspedisi.keterangan);
                }
              });
            })
          })
          $("#edit_products_add").change(function() {
            var select_produk = $(this).val();
            var url_data = "{{ url('detail/order/no_sj/no_sj_produk') }}";
            url_data = url_data.replace('no_sj', enc(nomor_sj.toString()));
            url_data = url_data.replace('no_sj_produk', enc(select_produk.toString()));
            $.get(url_data, function (data) {
              $('#edit_nama_customer_order').html('<h5>' + data.data.nama_cust_order + ' (' + custid + ')</h5>');
              if(data.data.phone_cust_order == null || data.data.phone_cust_order == ''){
                $('#edit_phone_customer_order').html('<b>Phone: </b>-----');
              }else{
                $('#edit_phone_customer_order').html('<b>Phone: </b>' + data.data.phone_cust_order);
              }
              $('#edit_alamat_customer_order').html('<b>Address: </b>' + data.data.alamat_cust_order);
              $('#edit_kota_customer_order').html('<b>City: </b>' + data.data.city_cust_order);
              if(data.data.npwp_cust_order == null || data.data.npwp_cust_order == ''){
                $('#edit_npwp_customer_order').html('<b>NPWP: </b>-----');
              }else{
                $('#edit_npwp_customer_order').html('<b>NPWP: </b>' + data.data.npwp_cust_order);
              }
              $('#edit_nama_customer_receive').html('<h5>' + data.data.nama_cust_receive + '</h5>');
              if(data.data.phone_cust_receive == null || data.data.phone_cust_receive == ''){
                $('#edit_phone_customer_receive').html('<b>Phone: </b>-----');
              }else{
                $('#edit_phone_customer_receive').html('<b>Phone: </b>' + data.data.phone_cust_receive);
              }
              if(data.data.ekspedisi != null || data.data.ekspedisi != ''){
                $('#edit_ekspedisi').children().remove().end().append('<option value="' + data.data.ekspedisi + '" selected disabled>' + data.data.nama_ekspedisi + '</option>');
              }
              if(data.data.crd == 1){
                $('#edit_crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data.data.crd + ' Hari Tunai / Transfer');
              }else{
                $('#edit_crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data.data.crd + ' Hari');
              }
              $('#edit_alamat_customer_receive').html('<b>Address: </b>' + data.data.alamat_cust_receive);
              $('#edit_kota_customer_receive').html('<b>City: </b>' + data.data.city_cust_receive);
              $('#edit_tanggal_kirim').val(data.data.tanggal_kirim);
              $('#edit_nomor_po').val(data.data.nomor_po);
              $('#edit_nomor_order_receipt_produk').val(select_produk);
              $('#edit_pokok_add').val('');
              $('#edit_stapel_add').val('');
              $('#edit_ekspedisi_add').val('');
              $('#edit_diskon_add').val('');
              $('#edit_ppn_add').val("{{ env('PPN_VAL') }}");
              $('#edit_harga_ongkir_add').val('');
              $('#edit_total_price_add').val('');
              $('#edit_keterangan_quotation').val('');
              $('#edit_keterangan').val(data.data.keterangan_order);
              $('#btn-edit-new-address-choose').data('id', custid);
              $('#btn-edit-new-address-choose').data('sj', select_produk);
              var url_ekspedisi = "{{ url('edit_order_sales/detail/nomor_sj/nomor_sj_produk') }}";
              url_ekspedisi = url_ekspedisi.replace('nomor_sj', enc(nomor_sj.toString()));
              url_ekspedisi = url_ekspedisi.replace('nomor_sj_produk', enc(select_produk.toString()));
              $.get(url_ekspedisi, function (data_ekspedisi) {
                $('#edit_ekspedisi').children().remove().end().append('<option value="">Pilih Ekspedisi</option>');
                $('#edit_ekspedisi').append('<option value="' + data_ekspedisi.kode_ekspedisi + '" selected>(Current Data) ' + data_ekspedisi.nama_ekspedisi + '</option>');
                $('#edit_ekspedisi').append('<option value="EKSBUANA">(Buat Data Baru) Buana</option>');
                $('#edit_ekspedisi').append('<option value="EKSGJAYA">(Buat Data Baru) Gunawan Jaya</option>');
                $('#edit_ekspedisi').append('<option value="EKSRAPI">(Buat Data Baru) Rapi</option>');
                $('#edit_ekspedisi').append('<option value="EKSSJAYA">(Buat Data Baru) Sumber Jaya</option>');
                $('#edit_ekspedisi').append('<option value="EKSTRANS">(Buat Data Baru) Transindo</option>');
                $('#edit_ekspedisi').append('<option value="EKSPRISMA">(Buat Data Baru) CV Prisma</option>');
                $('#edit_ekspedisi').append('<option value="EKSNIAGA">(Buat Data Baru) Niaga Jaya</option>');
                $('#edit_ekspedisi').append('<option value="EKSLOKAL">(Buat Data Baru) Kirim Lingkup Area DSGM</option>');
                $('#edit_ekspedisi').append('<option value="EKSDSGM">(Buat Data Baru) Ambil Sendiri</option>');
                $('#edit_pokok_add').val(data_ekspedisi.harga_satuan);
                $('#edit_stapel_add').val(data_ekspedisi.staple_cost);
                $('#edit_ekspedisi_add').val(data_ekspedisi.ekspedisi_cost);
                $('#edit_diskon_add').val(data_ekspedisi.discount);
                $('#edit_ppn_add').val(data_ekspedisi.ppn);
                $('#edit_harga_ongkir_add').val(data_ekspedisi.additional_price);
                $('#edit_total_price_add').val(data_ekspedisi.total_price);
                $('#edit_keterangan_quotation').val(data_ekspedisi.keterangan_quotation);
                $("#edit_ekspedisi").change(function() {
                  $('#edit_pokok_add').val('');
                  $('#edit_stapel_add').val('');
                  $('#edit_ekspedisi_add').val('');
                  $('#edit_diskon_add').val('');
                  $('#edit_ppn_add').val("{{ env('PPN_VAL') }}");
                  $('#edit_harga_ongkir_add').val('');
                  $('#edit_keterangan_quotation').val('');
                  $('#edit_total_price_add').val('');
                  if($(this).val() == data_ekspedisi.kode_ekspedisi){
                    $('#edit_pokok_add').val(data_ekspedisi.harga_pokok);
                    $('#edit_stapel_add').val(data_ekspedisi.harga_stapel);
                    $('#edit_ekspedisi_add').val(data_ekspedisi.harga_ekspedisi_total);
                    $('#edit_diskon_add').val(data_ekspedisi.diskon);
                    $('#edit_ppn_add').val(data_ekspedisi.ppn);
                    $('#edit_harga_ongkir_add').val(data_ekspedisi.total_harga_kg);
                    $('#edit_total_price_add').val(data_ekspedisi.total_price);
                    $('#edit_keterangan_quotation').val(data_ekspedisi.keterangan);
                  }
                });
              })
            })
          });
        })
        $('#edit_order_products_table').DataTable().destroy();
        load_data_edit_detail_products(nomor_sj);
      });

      $('body').on('click', '#edit_new_produk', function () {
        var custid = $(this).data('id');
        var nomor_sj = $(this).data('sj');
        var url = "{{ url('choose/customer_order/custid') }}";
        url = url.replace('custid', enc(custid.toString()));
        $('#new_custid_utama').val(custid);
        $('#new_nomor_order_receipt').val(nomor_sj);
        $('#new_show_saldo_input').html('').hide();
        $('#new_show_saldo_select').html('').hide();
        $.get(url, function (data_cust) {
          $('#new_nama_customer_order').html('<h5>' + data_cust.data_customer.nama_cust_order + ' (' + custid + ')</h5>');
          if(data_cust.data_customer.phone_cust_order == null || data_cust.data_customer.phone_cust_order == ''){
            $('#new_phone_customer_order').html('<b>Phone: </b>-----');
          }else{
            $('#new_phone_customer_order').html('<b>Phone: </b>' + data_cust.data_customer.phone_cust_order);
          }
          $('#new_alamat_customer_order').html('<b>Address: </b>' + data_cust.data_customer.alamat_cust_order);
          $('#new_kota_customer_order').html('<b>City: </b>' + data_cust.data_customer.name_city_cust_order);
          $('#new_nama_customer_receive').html('<h5>' + data_cust.data_alamat[0].nama_cust_receive + '</h5>');
          if(data_cust.data_alamat[0].phone_cust_receive == null || data_cust.data_alamat[0].phone_cust_receive == ''){
            $('#new_phone_customer_receive').html('<b>Phone: </b>-----');
          }else{
            $('#new_phone_customer_receive').html('<b>Phone: </b>' + data_cust.data_alamat[0].phone_cust_receive);
          }
          $('#new_alamat_customer_receive').html('<b>Address: </b>' + data_cust.data_alamat[0].alamat_cust_receive);
          $('#new_kota_customer_receive').html('<b>City: </b>' + data_cust.data_alamat[0].city_cust_receive);
          $('#new_txt_kode_alamat').val(data_cust.data_alamat[0].kode_alamat);
          var url_ho = "{{ url('get_history_orders/custid/id_alamat') }}";
          url_ho = url_ho.replace('custid', enc(custid.toString()));
          url_ho = url_ho.replace('id_alamat', enc(data_cust.data_alamat[0].kode_alamat.toString()));
          $.get(url_ho, function (data_ho) {
            if(data_ho.rekomendasi_produk.length != 0){
              $('#new_div_select_quantity').show();
              $('#new_div_input_quantity').hide();
              $('#new_show_saldo_input').html('').hide();
              $('#new_show_saldo_select').html('').show();
              $('#new_add_products').children().remove().end().append('<option value="">Choose Products</option>');
              $('#new_add_products').append('<option value="' + data_ho.rekomendasi_produk[0].kode_produk + '" selected>' + data_ho.rekomendasi_produk[0].nama_produk + ' (' + data_ho.rekomendasi_produk[0].kode_produk + ')</option>');
              $.each(data_ho.rekomendasi_produk.slice(1), function(k, v) {
                $('#new_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
              });
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#new_show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
              $('#new_select_add_quantity').children().remove().end().append('<option value="">Choose Quantity (KG)</option>');
              $('#new_select_add_quantity').append('<option value="' + data_ho.rekomendasi_qty[0].quantity + '" selected>' + data_ho.rekomendasi_qty[0].quantity + '</option>');
              $.each(data_ho.rekomendasi_qty.slice(1), function(k, v) {
                $('#new_select_add_quantity').append('<option value="' + v.quantity + '">' + v.quantity + '</option>');
              });
              $("#new_add_products").change(function() {
                var select_produk = $(this).val();
                var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                $.get(url_saldo, function (data_saldo) {
                  $('#new_show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                })
              });
              $('new_kode_produk').val(data_ho.rekomendasi_produk[0].kode_produk);
              $('#btn-new-new-products').prop('disabled', false);
              var url_ekspedisi = "{{ url('dropdown/new_products/ekspedisi/custid/id_alamat/kode_produk') }}";
              url_ekspedisi = url_ekspedisi.replace('custid', enc(custid.toString()));
              url_ekspedisi = url_ekspedisi.replace('id_alamat', enc(data_cust.data_alamat[0].kode_alamat.toString()));
              url_ekspedisi = url_ekspedisi.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));

              $.get(url_ekspedisi, function (data_ekspedisi) {
                if(Object.keys(data_ekspedisi).length !== 0){
                  $('#new_ekspedisi').children().remove().end().append('<option value="">Pilih Ekspedisi</option>');
                  $('#new_ekspedisi').append('<option value="' + data_ekspedisi[0].kode_ekspedisi + '" selected>(Data History) ' + data_ekspedisi[0].nama_ekspedisi + '</option>');
                  $.each(data_ekspedisi.slice(1), function(k, v) {
                    $('#new_ekspedisi').append('<option value="' + v.kode_ekspedisi + '">(Data History) ' + v.nama_ekspedisi + '</option>');
                  });
                  $('#new_ekspedisi').append('<option value="EKSBUANA">(Buat Data Baru) Buana</option>');
                  $('#new_ekspedisi').append('<option value="EKSGJAYA">(Buat Data Baru) Gunawan Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSRAPI">(Buat Data Baru) Rapi</option>');
                  $('#new_ekspedisi').append('<option value="EKSSJAYA">(Buat Data Baru) Sumber Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSTRANS">(Buat Data Baru) Transindo</option>');
                  $('#new_ekspedisi').append('<option value="EKSPRISMA">(Buat Data Baru) CV Prisma</option>');
                  $('#new_ekspedisi').append('<option value="EKSNIAGA">(Buat Data Baru) Niaga Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSLOKAL">(Buat Data Baru) Kirim Lingkup Area DSGM</option>');
                  $('#new_ekspedisi').append('<option value="EKSDSGM">(Buat Data Baru) Ambil Sendiri</option>');
                  $('#new_pokok_add').val(data_ekspedisi[0].harga_pokok);
                  $('#new_stapel_add').val(data_ekspedisi[0].harga_stapel);
                  $('#new_ekspedisi_add').val(data_ekspedisi[0].harga_ekspedisi_total);
                  $('#new_diskon_add').val(data_ekspedisi[0].diskon);
                  $('#new_ppn_add').val(data_ekspedisi[0].ppn);
                  $('#new_harga_ongkir_add').val(data_ekspedisi[0].total_harga_kg);
                  $('#new_keterangan_quotation').val(data_ekspedisi[0].keterangan);
                  $("#new_ekspedisi").change(function() {
                    $('#new_pokok_add').val('');
                    $('#new_stapel_add').val('');
                    $('#new_ekspedisi_add').val('');
                    $('#new_diskon_add').val('');
                    $('#new_ppn_add').val("{{ env('PPN_VAL') }}");
                    $('#new_harga_ongkir_add').val('');
                    $('#new_keterangan_quotation').val('');
                    for(var i = 0; i < data_ekspedisi.length; i++){
                      if($(this).val() == data_ekspedisi[i].kode_ekspedisi){
                        $('#new_pokok_add').val(data_ekspedisi[i].harga_pokok);
                        $('#new_stapel_add').val(data_ekspedisi[i].harga_stapel);
                        $('#new_ekspedisi_add').val(data_ekspedisi[i].harga_ekspedisi_total);
                        $('#new_diskon_add').val(data_ekspedisi[i].diskon);
                        $('#new_ppn_add').val(data_ekspedisi[i].ppn);
                        $('#new_harga_ongkir_add').val(data_ekspedisi[i].total_harga_kg);
                        $('#new_keterangan_quotation').val(data_ekspedisi[i].keterangan);
                      }
                    }
                  });
                }else{
                  $('#new_ekspedisi').children().remove().end().append('<option value="" selected>Pilih Ekspedisi</option>');
                  $('#new_ekspedisi').append('<option value="EKSBUANA">Buana</option>');
                  $('#new_ekspedisi').append('<option value="EKSGJAYA">Gunawan Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSRAPI">Rapi</option>');
                  $('#new_ekspedisi').append('<option value="EKSSJAYA">Sumber Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSTRANS">Transindo</option>');
                  $('#new_ekspedisi').append('<option value="EKSPRISMA">CV Prisma</option>');
                  $('#new_ekspedisi').append('<option value="EKSNIAGA">Niaga Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSLOKAL">Kirim Lingkup Area DSGM</option>');
                  $('#new_ekspedisi').append('<option value="EKSDSGM">Ambil Sendiri</option>');
                }
              })
            }else{
              $('#new_show_saldo_select').html('').hide();
              $('#new_show_saldo_input').html('').show();
              var url = "{{ url('get_products') }}";
              $.get(url, function (data_products) {
                $('#new_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
                $.each(data_products, function(k, v) {
                  $('#new_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                });
              })
              $("#new_add_products").change(function() {
                var select_produk = $(this).val();
                var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                $.get(url_saldo, function (data_saldo) {
                  $('#new_show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                })
              });
              $('#new_select_add_quantity').children().remove().end();
              $('#new_div_select_quantity').hide();
              $('#new_div_input_quantity').show();
              $('#btn-new-new-products').prop('disabled', true);
            }
          })
          $('#new_add_products').change(function(){
            var select_kode_produk = $(this).val();
            $('new_kode_produk').val(select_kode_produk);
            $('#new_pokok_add').val('');
            $('#new_stapel_add').val('');
            $('#new_ekspedisi_add').val('');
            $('#new_diskon_add').val('');
            $('#new_ppn_add').val("{{ env('PPN_VAL') }}");
            $('#new_harga_ongkir_add').val('');
            $('#new_keterangan_quotation').val('');
            var url_ekspedisi = "{{ url('dropdown/new_products/ekspedisi/custid/id_alamat/kode_produk') }}";
            url_ekspedisi = url_ekspedisi.replace('custid', enc(custid.toString()));
            url_ekspedisi = url_ekspedisi.replace('id_alamat', enc(data_cust.data_alamat[0].kode_alamat.toString()));
            url_ekspedisi = url_ekspedisi.replace('kode_produk', enc(select_kode_produk.toString()));
            $.get(url_ekspedisi, function (data_ekspedisi) {
              if(Object.keys(data_ekspedisi).length !== 0){
                $('#new_ekspedisi').children().remove().end().append('<option value="">Pilih Ekspedisi</option>');
                $('#new_ekspedisi').append('<option value="' + data_ekspedisi[0].kode_ekspedisi + '" selected>(Data History) ' + data_ekspedisi[0].nama_ekspedisi + '</option>');
                $.each(data_ekspedisi.slice(1), function(k, v) {
                  $('#new_ekspedisi').append('<option value="' + v.kode_ekspedisi + '">(Data History) ' + v.nama_ekspedisi + '</option>');
                });
                $('#new_ekspedisi').append('<option value="EKSBUANA">(Buat Data Baru) Buana</option>');
                $('#new_ekspedisi').append('<option value="EKSGJAYA">(Buat Data Baru) Gunawan Jaya</option>');
                $('#new_ekspedisi').append('<option value="EKSRAPI">(Buat Data Baru) Rapi</option>');
                $('#new_ekspedisi').append('<option value="EKSSJAYA">(Buat Data Baru) Sumber Jaya</option>');
                $('#new_ekspedisi').append('<option value="EKSTRANS">(Buat Data Baru) Transindo</option>');
                $('#new_ekspedisi').append('<option value="EKSPRISMA">(Buat Data Baru) CV Prisma</option>');
                $('#new_ekspedisi').append('<option value="EKSNIAGA">(Buat Data Baru) Niaga Jaya</option>');
                $('#new_ekspedisi').append('<option value="EKSLOKAL">(Buat Data Baru) Kirim Lingkup Area DSGM</option>');
                $('#new_ekspedisi').append('<option value="EKSDSGM">(Buat Data Baru) Ambil Sendiri</option>');
                $('#new_pokok_add').val(data_ekspedisi[0].harga_pokok);
                $('#new_stapel_add').val(data_ekspedisi[0].harga_stapel);
                $('#new_ekspedisi_add').val(data_ekspedisi[0].harga_ekspedisi_total);
                $('#new_diskon_add').val(data_ekspedisi[0].diskon);
                $('#new_ppn_add').val(data_ekspedisi[0].ppn);
                $('#new_harga_ongkir_add').val(data_ekspedisi[0].total_harga_kg);
                $('#new_keterangan_quotation').val(data_ekspedisi[0].keterangan);
                $("#new_ekspedisi").change(function() {
                  $('#new_pokok_add').val('');
                  $('#new_stapel_add').val('');
                  $('#new_ekspedisi_add').val('');
                  $('#new_diskon_add').val('');
                  $('#new_ppn_add').val("{{ env('PPN_VAL') }}");
                  $('#new_harga_ongkir_add').val('');
                  $('#new_keterangan_quotation').val('');
                  for(var i = 0; i < data_ekspedisi.length; i++){
                    if($(this).val() == data_ekspedisi[i].kode_ekspedisi){
                      $('#new_pokok_add').val(data_ekspedisi[i].harga_pokok);
                      $('#new_stapel_add').val(data_ekspedisi[i].harga_stapel);
                      $('#new_ekspedisi_add').val(data_ekspedisi[i].harga_ekspedisi_total);
                      $('#new_diskon_add').val(data_ekspedisi[i].diskon);
                      $('#new_ppn_add').val(data_ekspedisi[i].ppn);
                      $('#new_harga_ongkir_add').val(data_ekspedisi[i].total_harga_kg);
                      $('#new_keterangan_quotation').val(data_ekspedisi[i].keterangan);
                    }
                  }
                });
              }else{
                $('#new_ekspedisi').children().remove().end().append('<option value="" selected>Pilih Ekspedisi</option>');
                $('#new_ekspedisi').append('<option value="EKSBUANA">Buana</option>');
                $('#new_ekspedisi').append('<option value="EKSGJAYA">Gunawan Jaya</option>');
                $('#new_ekspedisi').append('<option value="EKSRAPI">Rapi</option>');
                $('#new_ekspedisi').append('<option value="EKSSJAYA">Sumber Jaya</option>');
                $('#new_ekspedisi').append('<option value="EKSTRANS">Transindo</option>');
                $('#new_ekspedisi').append('<option value="EKSPRISMA">CV Prisma</option>');
                $('#new_ekspedisi').append('<option value="EKSNIAGA">Niaga Jaya</option>');
                $('#new_ekspedisi').append('<option value="EKSLOKAL">Kirim Lingkup Area DSGM</option>');
                $('#new_ekspedisi').append('<option value="EKSDSGM">Ambil Sendiri</option>');
              }
            })
          });
          $('#btn-new-cancel').click(function (e) {
            $('#div-btn-new-new').show();
            $('#div-btn-new-cancel').hide();
            $('#new_show_saldo_select').html('').show();
            $('#new_show_saldo_input').html('').hide();
            var id_alamat = $('#new_txt_kode_alamat').val();
            var url_ho = "{{ url('get_history_orders/custid/id_alamat') }}";
            url_ho = url_ho.replace('custid', enc(custid.toString()));
            url_ho = url_ho.replace('id_alamat', enc(id_alamat.toString()));
            $.get(url_ho, function (data_ho) {
              $('#new_add_products').children().remove().end().append('<option value="">Choose Products</option>');
              $('#new_add_products').append('<option value="' + data_ho.rekomendasi_produk[0].kode_produk + '" selected>' + data_ho.rekomendasi_produk[0].nama_produk + ' (' + data_ho.rekomendasi_produk[0].kode_produk + ')</option>');
              $.each(data_ho.rekomendasi_produk.slice(1), function(k, v) {
                $('#new_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
              });
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#new_show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
              $('#new_select_add_quantity').children().remove().end().append('<option value="">Choose Quantity (KG)</option>');
              $('#new_div_select_quantity').show();
              $('#new_div_input_quantity').hide();
              $('#new_select_add_quantity').append('<option value="' + data_ho.rekomendasi_qty[0].quantity + '" selected>' + data_ho.rekomendasi_qty[0].quantity + '</option>');
              $.each(data_ho.rekomendasi_qty.slice(1), function(k, v) {
                $('#new_select_add_quantity').append('<option value="' + v.quantity + '">' + v.quantity + '</option>');
              });
              $("#new_add_products").change(function() {
                var select_produk = $(this).val();
                var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                $.get(url_saldo, function (data_saldo) {
                  $('#new_show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                })
              });
              var url_ekspedisi = "{{ url('dropdown/new_products/ekspedisi/custid/id_alamat/kode_produk') }}";
              url_ekspedisi = url_ekspedisi.replace('custid', enc(custid.toString()));
              url_ekspedisi = url_ekspedisi.replace('id_alamat', enc(data_cust.data_alamat[0].kode_alamat.toString()));
              url_ekspedisi = url_ekspedisi.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));

              $.get(url_ekspedisi, function (data_ekspedisi) {
                if(Object.keys(data_ekspedisi).length !== 0){
                  $('#new_ekspedisi').children().remove().end().append('<option value="">Pilih Ekspedisi</option>');
                  $('#new_ekspedisi').append('<option value="' + data_ekspedisi[0].kode_ekspedisi + '" selected>(Data History) ' + data_ekspedisi[0].nama_ekspedisi + '</option>');
                  $.each(data_ekspedisi.slice(1), function(k, v) {
                    $('#new_ekspedisi').append('<option value="' + v.kode_ekspedisi + '">(Data History) ' + v.nama_ekspedisi + '</option>');
                  });
                  $('#new_ekspedisi').append('<option value="EKSBUANA">(Buat Data Baru) Buana</option>');
                  $('#new_ekspedisi').append('<option value="EKSGJAYA">(Buat Data Baru) Gunawan Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSRAPI">(Buat Data Baru) Rapi</option>');
                  $('#new_ekspedisi').append('<option value="EKSSJAYA">(Buat Data Baru) Sumber Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSTRANS">(Buat Data Baru) Transindo</option>');
                  $('#new_ekspedisi').append('<option value="EKSPRISMA">(Buat Data Baru) CV Prisma</option>');
                  $('#new_ekspedisi').append('<option value="EKSNIAGA">(Buat Data Baru) Niaga Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSLOKAL">(Buat Data Baru) Kirim Lingkup Area DSGM</option>');
                  $('#new_ekspedisi').append('<option value="EKSDSGM">(Buat Data Baru) Ambil Sendiri</option>');
                  $('#new_pokok_add').val(data_ekspedisi[0].harga_pokok);
                  $('#new_stapel_add').val(data_ekspedisi[0].harga_stapel);
                  $('#new_ekspedisi_add').val(data_ekspedisi[0].harga_ekspedisi_total);
                  $('#new_diskon_add').val(data_ekspedisi[0].diskon);
                  $('#new_ppn_add').val(data_ekspedisi[0].ppn);
                  $('#new_harga_ongkir_add').val(data_ekspedisi[0].total_harga_kg);
                  $('#new_keterangan_quotation').val(data_ekspedisi[0].keterangan);
                  $("#new_ekspedisi").change(function() {
                    $('#new_pokok_add').val('');
                    $('#new_stapel_add').val('');
                    $('#new_ekspedisi_add').val('');
                    $('#new_diskon_add').val('');
                    $('#new_ppn_add').val("{{ env('PPN_VAL') }}");
                    $('#new_harga_ongkir_add').val('');
                    $('#new_keterangan_quotation').val('');
                    for(var i = 0; i < data_ekspedisi.length; i++){
                      if($(this).val() == data_ekspedisi[i].kode_ekspedisi){
                        $('#new_pokok_add').val(data_ekspedisi[i].harga_pokok);
                        $('#new_stapel_add').val(data_ekspedisi[i].harga_stapel);
                        $('#new_ekspedisi_add').val(data_ekspedisi[i].harga_ekspedisi_total);
                        $('#new_diskon_add').val(data_ekspedisi[i].diskon);
                        $('#new_ppn_add').val(data_ekspedisi[i].ppn);
                        $('#new_harga_ongkir_add').val(data_ekspedisi[i].total_harga_kg);
                        $('#new_keterangan_quotation').val(data_ekspedisi[i].keterangan);
                      }
                    }
                  });
                }else{
                  $('#new_ekspedisi').children().remove().end().append('<option value="" selected>Pilih Ekspedisi</option>');
                  $('#new_ekspedisi').append('<option value="EKSBUANA">Buana</option>');
                  $('#new_ekspedisi').append('<option value="EKSGJAYA">Gunawan Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSRAPI">Rapi</option>');
                  $('#new_ekspedisi').append('<option value="EKSSJAYA">Sumber Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSTRANS">Transindo</option>');
                  $('#new_ekspedisi').append('<option value="EKSPRISMA">CV Prisma</option>');
                  $('#new_ekspedisi').append('<option value="EKSNIAGA">Niaga Jaya</option>');
                  $('#new_ekspedisi').append('<option value="EKSLOKAL">Kirim Lingkup Area DSGM</option>');
                  $('#new_ekspedisi').append('<option value="EKSDSGM">Ambil Sendiri</option>');
                }
              })
            })
          });

          $('#btn-new-new-products').click(function (e) {
            $('#div-btn-new-new').hide();
            $('#div-btn-new-cancel').show();
            $('#new_show_saldo_select').html('').hide();
            $('#new_show_saldo_input').html('').show();
            var url = "{{ url('get_products') }}";
            $.get(url, function (data_products) {
              $('#new_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
              $.each(data_products, function(k, v) {
                $('#new_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
              });
            })

            $("#new_add_products").change(function() {
              var select_produk = $(this).val();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#new_show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            });

            $('#new_select_add_quantity').children().remove().end();
            $('#new_div_select_quantity').hide();
            $('#new_div_input_quantity').show();
          });
        })
      });

      $('body').on('click', '#edit-data-orders', function () {
        var nomor_sj_produk = $(this).data('id');
        $('#div_edit_products').show();
        $.ajax({
          type: "GET",
          url: "{{ url('products/edit/edit') }}",
          data: { 'nomor_sj_produk' : nomor_sj_produk },
          success: function (data) {
            $('#edit_add_products').val(data.kode_produk).trigger('change');
            $('#edit_add_quantity').val(data.qty);
            $('#edit_show_saldo_input').html('').show();
            var url_saldo = "{{ url('get_saldo/kode_produk') }}";
            url_saldo = url_saldo.replace('kode_produk', enc(data.kode_produk.toString()));
            $.get(url_saldo, function (data_saldo) {
              $('#edit_show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
            })
            $("#edit_add_products").change(function() {
              var select_produk = $(this).val();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#edit_show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            });
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      });

      $('body').on('click', '#btn-edit-products', function () {
        var nomor_sj_produk = $('#edit_nomor_order_receipt_produk').val();
        var produk = $('#edit_add_products').val();
        var qty = $('#edit_add_quantity').val();
        $.ajax({
          type: "GET",
          url: "{{ url('products/edit/save') }}",
          data: { 'nomor_sj_produk' : nomor_sj_produk, 'produk' : produk, 'qty' : qty },
          success: function (data) {
            $('#div_edit_products').hide();
            $('#modal_edit_order').modal('hide');
            $("#modal_edit_order").trigger('click');
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      });

      $('body').on('click', '#btn-orders', function () {
        var tanggal_kirim = document.getElementById("input_tanggal_kirim").value;
        var nomor_po = document.getElementById("input_nomor_po").value;
        var keterangan_order = document.getElementById("input_keterangan").value;
        var custid = document.getElementById("input_custid_utama").value;
        var city_order = document.getElementById("input_city_order").value;
        var kode_alamat = document.getElementById("input_txt_kode_alamat").value;


        var count = $("#input_order_products_table").dataTable().fnSettings().aoData.length;
        if (count == 0)
        {
          alert("Please add the products you want to buy");
        }else{
          if((tanggal_kirim == null || tanggal_kirim == "")){
            alert("Delivery Date must not be empty");
          }else if((kode_alamat == null || kode_alamat == "")){
            alert("Please Choose Address For Receiving");
          }else{
            $.ajax({
              type:"GET",
              url:"{{ url('add_orders_admin') }}",
              data: { 'custid' : custid, 'tanggal_kirim' : tanggal_kirim, 'keterangan_order' : keterangan_order, 'city_order' : city_order, 'nomor_po' : nomor_po },
              success:function(data){
                alert("Orders Successful");
                $('#modal_input_orders').modal('hide');
                $("#modal_input_orders").trigger('click');
                $('#input_nama_customer_receive').html('');   
                $('#input_phone_customer_receive').html('');
                $('#input_alamat_customer_receive').html('');
                $('#input_kota_customer_receive').html('');
                $('#input_nama_customer_order').html('');
                $('#input_phone_customer_order').html('');
                $('#input_kota_customer_order').html('');
                $('#input_npwp_customer_order').html('');
                $('#input_alamat_customer_order').html('');
                $('#input_crd_pembayaran').html('');
                $('#input_custid_utama').val('');
                $('#input_nomor_po').val('');
                $('#input_keterangan').val('');
                $('#input_customer_order').val('').trigger('change');
                $('#input_city_order').val('').trigger('change');
                $('#input_add_products').children().remove().end();
                $('#select_add_quantity').children().remove().end();
                $('#div_select_quantity').hide();
                $('#div_input_quantity').show();
                $('#div-btn-new').show();
                $('#div-btn-cancel').hide();
                $('#btn-new-products').prop('disabled', true);
                $("ul.nav-tabs-lihat li a.nav-link").removeClass("active");
                $("ul.nav-tabs-lihat li:nth-of-type(2) a.nav-link").addClass("active").show();
                $('#div_new_address').hide();
                $('#div_choose_another_address').hide();
                $('#div_send_several_address').hide();
                $('#div_choose_more_delivery_date').hide();
                $('#div_cancel_more_delivery_date').hide();
                $('#div_input_city_order').hide();
                $('#div_input_order_products_table').show();
                $('#div_input_order_products_several_table').hide();
                $('#div_input_order_products_delivdate_table').hide();
                $('#btn-orders').show();
                $('#btn-several-orders').hide();
                $('#btn-delivdate-orders').hide();
                $('#div_delivery_date').show();
                $('#div_delivery_date_products').hide();
                $('#input_tanggal_kirim').flatpickr({
                  allowInput: true,
                  defaultDate: new Date(),
                  minDate: "today"
                });

                $('#input_tanggal_kirim_products').flatpickr({
                  allowInput: true,
                  defaultDate: new Date(),
                  minDate: "today"
                });
                var oTable = $('#orders_new_table').dataTable();
                oTable.fnDraw(false);
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
        var nomor_po = document.getElementById("input_nomor_po").value;
        var keterangan_order = document.getElementById("input_keterangan").value;
        var custid = document.getElementById("input_custid_utama").value;
        var city_order = document.getElementById("input_city_order").value;
        var kode_alamat = document.getElementById("input_txt_kode_alamat").value;

        var count = $("#input_order_products_several_table").dataTable().fnSettings().aoData.length;
        if (count == 0)
        {
          alert("Please add the products you want to buy");
        }else{
          if((kode_alamat == null || kode_alamat == "")){
            alert("Please Choose Address For Receiving");
          }else{
            $.ajax({
              type:"GET",
              url:"{{ url('add_orders_several_admin') }}",
              data: { 'custid' : custid, 'keterangan_order' : keterangan_order, 'city_order' : city_order, 'nomor_po' : nomor_po },
              success:function(data){
                alert("Orders Successful");
                $('#modal_input_orders').modal('hide');
                $("#modal_input_orders").trigger('click');
                $('#input_nama_customer_receive').html('');   
                $('#input_phone_customer_receive').html('');
                $('#input_alamat_customer_receive').html('');
                $('#input_kota_customer_receive').html('');
                $('#input_nama_customer_order').html('');
                $('#input_phone_customer_order').html('');
                $('#input_kota_customer_order').html('');
                $('#input_npwp_customer_order').html('');
                $('#input_alamat_customer_order').html('');
                $('#input_crd_pembayaran').html('');
                $('#input_custid_utama').val('');
                $('#input_tanggal_kirim').val('');
                $('#input_nomor_po').val('');
                $('#input_keterangan').val('');
                $('#input_customer_order').val('').trigger('change');
                $('#input_city_order').val('').trigger('change');
                $('#input_add_products').children().remove().end();
                $('#select_add_quantity').children().remove().end();
                $('#div_select_quantity').hide();
                $('#div_input_quantity').show();
                $('#div-btn-new').show();
                $('#div-btn-cancel').hide();
                $('#btn-new-products').prop('disabled', true);
                $("ul.nav-tabs-lihat li a.nav-link").removeClass("active");
                $("ul.nav-tabs-lihat li:nth-of-type(2) a.nav-link").addClass("active").show();
                $('#div_new_address').hide();
                $('#div_choose_another_address').hide();
                $('#div_send_several_address').hide();
                $('#div_choose_more_delivery_date').hide();
                $('#div_cancel_more_delivery_date').hide();
                $('#div_input_city_order').hide();
                $('#div_input_order_products_table').show();
                $('#div_input_order_products_several_table').hide();
                $('#div_input_order_products_delivdate_table').hide();
                $('#btn-orders').show();
                $('#btn-several-orders').hide();
                $('#btn-delivdate-orders').hide();
                $('#div_delivery_date').show();
                $('#div_delivery_date_products').hide();
                $('#input_tanggal_kirim').flatpickr({
                  allowInput: true,
                  defaultDate: new Date(),
                  minDate: "today"
                });

                $('#input_tanggal_kirim_products').flatpickr({
                  allowInput: true,
                  defaultDate: new Date(),
                  minDate: "today"
                });
                var oTable = $('#orders_new_table').dataTable();
                oTable.fnDraw(false);
              },
              error: function (data) {
                console.log('Error:', data);
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        }
      });

      $('body').on('click', '#btn-delivdate-orders', function () {
        var nomor_po = document.getElementById("input_nomor_po").value;
        var keterangan_order = document.getElementById("input_keterangan").value;
        var custid = document.getElementById("input_custid_utama").value;
        var city_order = document.getElementById("input_city_order").value;
        var kode_alamat = document.getElementById("input_txt_kode_alamat").value;

        var count = $("#input_order_products_delivdate_table").dataTable().fnSettings().aoData.length;
        if (count == 0)
        {
          alert("Please add the products you want to buy");
        }else{
          if((kode_alamat == null || kode_alamat == "")){
            alert("Please Choose Address For Receiving");
          }else{
            $.ajax({
              type:"GET",
              url:"{{ url('add_orders_delivdate_admin') }}",
              data: { 'custid' : custid, 'keterangan_order' : keterangan_order, 'city_order' : city_order, 'nomor_po' : nomor_po },
              success:function(data){
                alert("Orders Successful");
                $('#modal_input_orders').modal('hide');
                $("#modal_input_orders").trigger('click');
                $('#input_nama_customer_receive').html('');   
                $('#input_phone_customer_receive').html('');
                $('#input_alamat_customer_receive').html('');
                $('#input_kota_customer_receive').html('');
                $('#input_nama_customer_order').html('');
                $('#input_phone_customer_order').html('');
                $('#input_kota_customer_order').html('');
                $('#input_npwp_customer_order').html('');
                $('#input_alamat_customer_order').html('');
                $('#input_crd_pembayaran').html('');
                $('#input_custid_utama').val('');
                $('#input_tanggal_kirim').val('');
                $('#input_nomor_po').val('');
                $('#input_keterangan').val('');
                $('#input_customer_order').val('').trigger('change');
                $('#input_city_order').val('').trigger('change');
                $('#input_add_products').children().remove().end();
                $('#select_add_quantity').children().remove().end();
                $('#div_select_quantity').hide();
                $('#div_input_quantity').show();
                $('#div-btn-new').show();
                $('#div-btn-cancel').hide();
                $('#btn-new-products').prop('disabled', true);
                $("ul.nav-tabs-lihat li a.nav-link").removeClass("active");
                $("ul.nav-tabs-lihat li:nth-of-type(2) a.nav-link").addClass("active").show();
                $('#div_new_address').hide();
                $('#div_choose_another_address').hide();
                $('#div_send_several_address').hide();
                $('#div_choose_more_delivery_date').hide();
                $('#div_cancel_more_delivery_date').hide();
                $('#div_input_city_order').hide();
                $('#div_input_order_products_table').show();
                $('#div_input_order_products_several_table').hide();
                $('#div_input_order_products_delivdate_table').hide();
                $('#btn-orders').show();
                $('#btn-several-orders').hide();
                $('#btn-delivdate-orders').hide();
                $('#div_delivery_date').show();
                $('#div_delivery_date_products').hide();
                $('#input_tanggal_kirim').flatpickr({
                  allowInput: true,
                  defaultDate: new Date(),
                  minDate: "today"
                });

                $('#input_tanggal_kirim_products').flatpickr({
                  allowInput: true,
                  defaultDate: new Date(),
                  minDate: "today"
                });
                var oTable = $('#orders_new_table').dataTable();
                oTable.fnDraw(false);
              },
              error: function (data) {
                console.log('Error:', data);
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        }
      });

      $('body').on('click', '#btn-save-new-products', function () {
        var tanggal_kirim = $("#new_tanggal_kirim").val();
        var keterangan_quotation = $("#new_keterangan_quotation").val();
        var custid = $("#new_custid_utama").val();
        var kode_alamat = $("#new_txt_kode_alamat").val();
        var nomor_order_receipt = $("#new_nomor_order_receipt").val();
        var products = $("#new_add_products").val();
        var quantity_select = $("#new_select_add_quantity").val();
        var quantity = $("#new_add_quantity").val();
        var ekspedisi = $("#new_ekspedisi").val();
        var pokok_add = $("#new_pokok_add").val();
        var stapel_add = $("#new_stapel_add").val();
        var ekspedisi_add = $("#new_ekspedisi_add").val();
        var diskon_add = $("#new_diskon_add").val();
        var ppn_add = $("#new_ppn_add").val();
        var harga_ongkir_add = $("#new_harga_ongkir_add").val();

        if((tanggal_kirim == null || tanggal_kirim == "")){
          alert("Delivery Date must not be empty");
        }else{
          $.ajax({
            type:"GET",
            url:"{{ url('add_new_products_sales') }}",
            data: { 'custid' : custid, 'tanggal_kirim' : tanggal_kirim, 'keterangan_quotation' : keterangan_quotation, 'kode_alamat' : kode_alamat, 'nomor_order_receipt' : nomor_order_receipt, 'products' : products, 'quantity_select' : quantity_select, 'quantity' : quantity, 'ekspedisi' : ekspedisi, 'pokok_add' : pokok_add, 'stapel_add' : stapel_add, 'ekspedisi_add' : ekspedisi_add, 'diskon_add' : diskon_add, 'ppn_add' : ppn_add, 'harga_ongkir_add' : harga_ongkir_add },
            success:function(data){
              alert("Orders Successful");
              $('#modal_new_products').modal('hide');
              $("#modal_new_products").trigger('click');
              $('#new_nama_customer_receive').html('');   
              $('#new_phone_customer_receive').html('');
              $('#new_alamat_customer_receive').html('');
              $('#new_kota_customer_receive').html('');
              $('#new_nama_customer_order').html('');
              $('#new_phone_customer_order').html('');
              $('#new_kota_customer_order').html('');
              $('#new_alamat_customer_order').html('');
              $('#new_custid_utama').val('');
              $('#new_keterangan_quotation').val('');
              $('#new_add_products').children().remove().end();
              $('#new_select_add_quantity').children().remove().end();
              $('#new_div_select_quantity').hide();
              $('#new_div_input_quantity').show();
              $('#div-btn-new-new').show();
              $('#div-btn-new-cancel').hide();
              $('#btn-new-new-products').prop('disabled', true);
              $('#new_tanggal_kirim').flatpickr({
                allowInput: true,
                defaultDate: new Date(),
                minDate: "today"
              });
              var oTable = $('#orders_new_table').dataTable();
              oTable.fnDraw(false);
            },
            error: function (data) {
              console.log('Error:', data);
              alert("Something Goes Wrong. Please Try Again");
            }
          });
        }
      });

      $('body').on('click', '#btn_input_orders', function () {
        // $('#input_customer_order').select2({
        //   dropdownParent: $('#modal_input_orders')
        // });

        if($('#input_custid_utama').val() == '' || $('#input_custid_utama').val() == null){
          $('#input_order_products_table').DataTable().destroy();
          load_data_products();
        }else{
          var custid = $('#input_custid_utama').val();
          $('#input_order_products_table').DataTable().destroy();
          load_data_products(custid);
        }

        if($('#input_add_products option').length == 0){
          var url = "{{ url('get_products') }}";
          $.get(url, function (data_products) {
            $('#input_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
            $.each(data_products, function(k, v) {
              $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
            });
          })

          $('#show_saldo_input').html('').hide();
          $('#show_saldo_select').html('').hide();
          $('#btn-new-products').prop('disabled', true);
        }
      });

      $('#input_customer_order').on("select2:selecting", function(e) { 
        var data = e.params.args.data;
        var url = "{{ url('choose/customer_order/custid') }}";
        url = url.replace('custid', enc(data.id.toString()));
        $('#input_custid_utama').val(data.id);
        $('#input_custid').val(data.id);
        $('#div_view_address').children().remove();
        $('#div_delivery_date').show();
        $('#div_delivery_date_products').hide();
        $('#div_input_order_products_table').show();
        $('#div_input_order_products_several_table').hide();
        $('#div_input_order_products_delivdate_table').hide();
        $('#btn-orders').show();
        $('#btn-several-orders').hide();
        $('#btn-delivdate-orders').hide();
        $('#show_saldo_select').html('').hide();
        $('#show_saldo_input').html('').hide();
        $.get(url, function (data_cust) {
          $('#input_nama_customer_order').html('<h5>' + data_cust.data_customer.nama_cust_order + ' (' + data.id + ')</h5>');
          if(data_cust.data_customer.phone_cust_order == null || data_cust.data_customer.phone_cust_order == ''){
            $('#input_phone_customer_order').html('<b>Phone: </b>-----');
          }else{
            $('#input_phone_customer_order').html('<b>Phone: </b>' + data_cust.data_customer.phone_cust_order);
          }
          $('#input_alamat_customer_order').html('<b>Address: </b>' + data_cust.data_customer.alamat_cust_order);
          if(data_cust.data_customer.name_city_cust_order == null || data_cust.data_customer.name_city_cust_order == ''){
            $('#input_kota_customer_order').html('<b>City: </b> (' + data_cust.data_customer.city_cust_order + ') Kota Anda Tidak Terdeteksi Oleh Sistem, Silahkan Input Kota Disini');
            $('#div_input_city_order').show();
          }else{
            $('#input_kota_customer_order').html('<b>City: </b>' + data_cust.data_customer.name_city_cust_order);
            $('#div_input_city_order').hide();
          }
          if(data_cust.data_customer.npwp_cust_order == null || data_cust.data_customer.npwp_cust_order == ''){
            $('#input_npwp_customer_order').html('<b>NPWP: </b>-----');
          }else{
            $('#input_npwp_customer_order').html('<b>NPWP: </b>' + data_cust.data_customer.npwp_cust_order);
          }
          if(data_cust.data_customer.crd_cust_order == 1){
            $('#input_crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data_cust.data_customer.crd_cust_order + ' Hari Tunai / Transfer');
          }else{
            $('#input_crd_pembayaran').html('<b>CRD Pembayaran: </b>' + data_cust.data_customer.crd_cust_order + ' Hari');
          }
          if(data_cust.data_alamat.length == 0){
            $('#div_select_quantity').hide();
            $('#div_input_quantity').show();
            $('#show_saldo_select').html('').hide();
            $('#show_saldo_input').html('').hide();
            $('#select_add_quantity').children().remove().end();
            $('#input_order_products_table').DataTable().destroy();
            load_data_products(data.id);
            $('#div_new_address').show();
            $('#div_view_address').hide();
            $('#div_select_address').hide();
            $('#btn_new_address').data('id', data.id);
            $('#input_several_address').html('');
            $('#div_choose_another_address').hide();
            $('#div_send_several_address').hide();
            $('#div_choose_more_delivery_date').hide();
            $('#div_cancel_more_delivery_date').hide();
            $('#input_nama_customer_receive').html('');   
            $('#input_phone_customer_receive').html('');
            $('#input_alamat_customer_receive').html('');
            $('#input_kota_customer_receive').html('');
            $('#input_txt_kode_alamat').val('');
            $('#btn-orders').show();
            $('#btn-several-orders').hide();
            $('#btn-delivdate-orders').hide();
            $('#div_input_order_products_table').show();
            $('#div_input_order_products_several_table').hide();
            $('#div_input_order_products_delivdate_table').hide();
          }else if(data_cust.data_alamat.length == 1){
            $('#div_input_order_products_table').show();
            $('#div_input_order_products_several_table').hide();
            $('#div_input_order_products_delivdate_table').hide();
            $('#btn-orders').show();
            $('#btn-several-orders').hide();
            $('#btn-delivdate-orders').hide();
            $('#input_order_products_table').DataTable().destroy();
            $('#btn-new-address-choose').data('id', data.id);
            var url_ho = "{{ url('get_history_orders/custid/id_alamat') }}";
            url_ho = url_ho.replace('custid', enc(data.id.toString()));
            url_ho = url_ho.replace('id_alamat', enc(data_cust.data_alamat[0].kode_alamat.toString()));
            $.get(url_ho, function (data_ho) {
              if(data_ho.rekomendasi_produk.length != 0){
                $('#div_select_quantity').show();
                $('#div_input_quantity').hide();
                $('#show_saldo_select').html('').show();
                $('#show_saldo_input').html('').hide();
                $('#input_add_products').children().remove().end().append('<option value="">Choose Products</option>');
                $('#input_add_products').append('<option value="' + data_ho.rekomendasi_produk[0].kode_produk + '" selected>' + data_ho.rekomendasi_produk[0].nama_produk + ' (' + data_ho.rekomendasi_produk[0].kode_produk + ')</option>');
                $.each(data_ho.rekomendasi_produk.slice(1), function(k, v) {
                  $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                });
                var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                url_saldo = url_saldo.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));
                $.get(url_saldo, function (data_saldo) {
                  $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                })
                $('#select_add_quantity').children().remove().end().append('<option value="">Choose Quantity (KG)</option>');
                $('#select_add_quantity').append('<option value="' + data_ho.rekomendasi_qty[0].quantity + '" selected>' + data_ho.rekomendasi_qty[0].quantity + '</option>');
                $.each(data_ho.rekomendasi_qty.slice(1), function(k, v) {
                  $('#select_add_quantity').append('<option value="' + v.quantity + '">' + v.quantity + '</option>');
                });
                $("#input_add_products").change(function() {
                  var select_produk = $(this).val();
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                });
                $('#btn-new-products').prop('disabled', false);
              }else{
                $('#show_saldo_select').html('').hide();
                $('#show_saldo_input').html('').show();
                var url = "{{ url('get_products') }}";
                $.get(url, function (data_products) {
                  $('#input_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
                  $.each(data_products, function(k, v) {
                    $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                  });
                })
                $("#input_add_products").change(function() {
                  var select_produk = $(this).val();
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                });
                $('#select_add_quantity').children().remove().end();
                $('#div_select_quantity').hide();
                $('#div_input_quantity').show();
                $('#btn-new-products').prop('disabled', true);
              }
            })
            load_data_products(data.id);
            $('#div_new_address').hide();
            $('#div_view_address').hide();
            $('#div_select_address').hide();
            $('#input_several_address').html('');
            $('#btn_send_several_address').html('Send To Several Addresses');
            $('#div_choose_another_address').show();
            $('#div_send_several_address').show();
            $('#div_choose_more_delivery_date').show();
            $('#div_cancel_more_delivery_date').hide();
            $('#input_nama_customer_receive').html('<h5>' + data_cust.data_alamat[0].nama_cust_receive + '</h5>');
            if(data_cust.data_alamat[0].phone_cust_receive == null || data_cust.data_alamat[0].phone_cust_receive == ''){
              $('#input_phone_customer_receive').html('<b>Phone: </b>-----');
            }else{
              $('#input_phone_customer_receive').html('<b>Phone: </b>' + data_cust.data_alamat[0].phone_cust_receive);
            }
            $('#input_alamat_customer_receive').html('<b>Address: </b>' + data_cust.data_alamat[0].alamat_cust_receive);
            $('#input_kota_customer_receive').html('<b>City: </b>' + data_cust.data_alamat[0].city_cust_receive);
            $('#input_txt_kode_alamat').val(data_cust.data_alamat[0].kode_alamat);

            $('#btn-cancel').click(function (e) {
              $('#div-btn-new').show();
              $('#div-btn-cancel').hide();
              $('#show_saldo_select').html('').show();
              $('#show_saldo_input').html('').hide();
              var custid = $('#input_custid').val();
              var id_alamat = $('#input_txt_kode_alamat').val();
              var url_ho = "{{ url('get_history_orders/custid/id_alamat') }}";
              url_ho = url_ho.replace('custid', enc(custid.toString()));
              url_ho = url_ho.replace('id_alamat', enc(id_alamat.toString()));
              $.get(url_ho, function (data_ho) {
                $('#input_add_products').children().remove().end().append('<option value="">Choose Products</option>');
                $('#input_add_products').append('<option value="' + data_ho.rekomendasi_produk[0].kode_produk + '" selected>' + data_ho.rekomendasi_produk[0].nama_produk + ' (' + data_ho.rekomendasi_produk[0].kode_produk + ')</option>');
                $.each(data_ho.rekomendasi_produk.slice(1), function(k, v) {
                  $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                });
                var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                url_saldo = url_saldo.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));
                $.get(url_saldo, function (data_saldo) {
                  $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                })
                $('#select_add_quantity').children().remove().end().append('<option value="">Choose Quantity (KG)</option>');
                $('#div_select_quantity').show();
                $('#div_input_quantity').hide();
                $('#select_add_quantity').append('<option value="' + data_ho.rekomendasi_qty[0].quantity + '" selected>' + data_ho.rekomendasi_qty[0].quantity + '</option>');
                $.each(data_ho.rekomendasi_qty.slice(1), function(k, v) {
                  $('#select_add_quantity').append('<option value="' + v.quantity + '">' + v.quantity + '</option>');
                });

                $("#input_add_products").change(function() {
                  var select_produk = $(this).val();
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                });
              })
            });

            $('#btn_choose_more_delivery_date').click(function (e) {
              $('#div_choose_another_address').show();
              $('#div_send_several_address').hide();
              $('#div_choose_more_delivery_date').hide();
              $('#div_cancel_more_delivery_date').show();
              $('#div_delivery_date').hide();
              $('#div_delivery_date_products').show();
              $('#btn-orders').hide();
              $('#btn-several-orders').hide();
              $('#btn-delivdate-orders').show();
              $('#div_input_order_products_table').hide();
              $('#div_input_order_products_several_table').hide();
              $('#div_input_order_products_delivdate_table').show();
              $('#input_order_products_delivdate_table').DataTable().destroy();
              load_data_delivdate_products(data.id);
            });

            $('#btn_cancel_more_delivery_date').click(function (e) {
              $('#div_choose_another_address').show();
              $('#div_send_several_address').show();
              $('#div_choose_more_delivery_date').show();
              $('#div_cancel_more_delivery_date').hide();
              $('#div_delivery_date').show();
              $('#div_delivery_date_products').hide();
              $('#btn-orders').show();
              $('#btn-several-orders').hide();
              $('#btn-delivdate-orders').hide();
              $('#div_input_order_products_table').show();
              $('#div_input_order_products_several_table').hide();
              $('#div_input_order_products_delivdate_table').hide();
              $('#input_order_products_table').DataTable().destroy();
              load_data_products(data.id);
            });
          }else{
            $('#btn-orders').hide();
            $('#btn-several-orders').show();
            $('#btn-delivdate-orders').hide();
            $('#div_input_order_products_table').hide();
            $('#div_input_order_products_several_table').show();
            $('#div_input_order_products_delivdate_table').hide();
            $('#input_order_products_several_table').DataTable().destroy();
            load_data_several_products(data.id);
            $('#btn-new-address-choose').data('id', data.id);
            $('#div_new_address').hide();
            $('#div_view_address').show();
            $('#div_delivery_date').hide();
            $('#div_delivery_date_products').show();
            $('#div_choose_another_address').show();
            $('#div_send_several_address').show();
            $('#div_choose_more_delivery_date').hide();
            $('#div_cancel_more_delivery_date').hide();
            $('#div_select_address').show();
            $('#btn_send_several_address').html('Send / See Several Addresses');
            $('#input_several_address').html('<h5>Pesanan Diantar Ke Lebih Dari 1 Alamat</h5>');
            $('#input_nama_customer_receive').html('');   
            $('#input_phone_customer_receive').html('');
            $('#input_alamat_customer_receive').html('');
            $('#input_kota_customer_receive').html('');
            $('#input_txt_kode_alamat').val(data_cust.data_alamat[0].kode_alamat);
            $.each(data_cust.data_alamat, function(k, v) {
              $('#select_address_products').append('<option value="' + v.kode_alamat + '">Address ' + (k+1) + '</option>');
              $('#div_view_address').append('<p><b>Address ' + (k+1) + ' : </b>' + v.alamat_cust_receive + ', ' + v.city_cust_receive + '</p>');
            })
            var url_ho = "{{ url('get_history_orders/custid/id_alamat') }}";
            url_ho = url_ho.replace('custid', enc(data.id.toString()));
            url_ho = url_ho.replace('id_alamat', enc(data_cust.data_alamat[0].kode_alamat.toString()));
            $.get(url_ho, function (data_ho) {
              if(data_ho.rekomendasi_produk.length != 0){
                $('#div_select_quantity').show();
                $('#div_input_quantity').hide();
                $('#show_saldo_select').html('').show();
                $('#show_saldo_input').html('').hide();
                $('#input_add_products').children().remove().end().append('<option value="">Choose Products</option>');
                $('#input_add_products').append('<option value="' + data_ho.rekomendasi_produk[0].kode_produk + '" selected>' + data_ho.rekomendasi_produk[0].nama_produk + ' (' + data_ho.rekomendasi_produk[0].kode_produk + ')</option>');
                $.each(data_ho.rekomendasi_produk.slice(1), function(k, v) {
                  $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                });
                var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                url_saldo = url_saldo.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));
                $.get(url_saldo, function (data_saldo) {
                  $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                })
                $('#select_add_quantity').children().remove().end().append('<option value="">Choose Quantity (KG)</option>');
                // console.log(data_ho.rekomendasi_qty[0].quantity);
                if(data_ho.rekomendasi_qty[0].quantity == null || data_ho.rekomendasi_qty[0].quantity == ""){
                  $('#div_input_quantity').html('').show();
                  $('#div_select_quantity').html('').hide();
                  $('#select_add_quantity').children().remove().end();
                }else{
                  $('#select_add_quantity').append('<option value="' + data_ho.rekomendasi_qty[0].quantity + '" selected>' + data_ho.rekomendasi_qty[0].quantity + '</option>');
                  $.each(data_ho.rekomendasi_qty.slice(1), function(k, v) {
                    $('#select_add_quantity').append('<option value="' + v.quantity + '">' + v.quantity + '</option>');
                  });
                }
                $("#input_add_products").change(function() {
                  var select_produk = $(this).val();
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                });
                $('#btn-new-products').prop('disabled', false);
              }else{
                $('#show_saldo_select').html('').hide();
                $('#show_saldo_input').html('').show();
                var url = "{{ url('get_products') }}";
                $.get(url, function (data_products) {
                  $('#input_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
                  $.each(data_products, function(k, v) {
                    $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                  });
                })
                $("#input_add_products").change(function() {
                  var select_produk = $(this).val();
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                });
                $('#select_add_quantity').children().remove().end();
                $('#div_select_quantity').hide();
                $('#div_input_quantity').show();
                $('#btn-new-products').prop('disabled', true);
              }
            })

            $('#btn-cancel').click(function (e) {
              $('#div-btn-new').show();
              $('#div-btn-cancel').hide();
              $('#show_saldo_select').html('').show();
              $('#show_saldo_input').html('').hide();
              var custid = $('#input_custid').val();
              var id_alamat = $('#input_txt_kode_alamat').val();
              var url_ho = "{{ url('get_history_orders/custid/id_alamat') }}";
              url_ho = url_ho.replace('custid', enc(custid.toString()));
              url_ho = url_ho.replace('id_alamat', enc(id_alamat.toString()));
              $.get(url_ho, function (data_ho) {
                $('#input_add_products').children().remove().end().append('<option value="">Choose Products</option>');
                $('#input_add_products').append('<option value="' + data_ho.rekomendasi_produk[0].kode_produk + '" selected>' + data_ho.rekomendasi_produk[0].nama_produk + ' (' + data_ho.rekomendasi_produk[0].kode_produk + ')</option>');
                $.each(data_ho.rekomendasi_produk.slice(1), function(k, v) {
                  $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                });
                var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                url_saldo = url_saldo.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));
                $.get(url_saldo, function (data_saldo) {
                  $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                })
                $('#select_add_quantity').children().remove().end().append('<option value="">Choose Quantity (KG)</option>');
                if(data_ho.rekomendasi_qty[0].quantity == null || data_ho.rekomendasi_qty[0].quantity == ""){
                  $('#div_input_quantity').show();
                  $('#div_select_quantity').hide();
                  $('#select_add_quantity').children().remove().end();
                }else{
                  $('#div_select_quantity').show();
                  $('#div_input_quantity').hide();
                  $('#select_add_quantity').append('<option value="' + data_ho.rekomendasi_qty[0].quantity + '" selected>' + data_ho.rekomendasi_qty[0].quantity + '</option>');
                  $.each(data_ho.rekomendasi_qty.slice(1), function(k, v) {
                    $('#select_add_quantity').append('<option value="' + v.quantity + '">' + v.quantity + '</option>');
                  });
                }
                $("#input_add_products").change(function() {
                  var select_produk = $(this).val();
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                });
              })
            });

            $("#select_address_products").change(function() {
              var select_address = $(this).val();
              $('#input_txt_kode_alamat').val(select_address);
              var url_ho = "{{ url('get_history_orders/custid/id_alamat') }}";
              url_ho = url_ho.replace('custid', enc(data.id.toString()));
              url_ho = url_ho.replace('id_alamat', enc(select_address.toString()));
              $.get(url_ho, function (data_ho) {
                if(data_ho.rekomendasi_produk.length != 0){
                  $('#div_select_quantity').show();
                  $('#div_input_quantity').hide();  
                  $('#show_saldo_select').html('').show();
                  $('#show_saldo_input').html('').hide();
                  $('#input_add_products').children().remove().end().append('<option value="">Choose Products</option>');
                  $('#input_add_products').append('<option value="' + data_ho.rekomendasi_produk[0].kode_produk + '" selected>' + data_ho.rekomendasi_produk[0].nama_produk + ' (' + data_ho.rekomendasi_produk[0].kode_produk + ')</option>');
                  $.each(data_ho.rekomendasi_produk.slice(1), function(k, v) {
                    $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                  });
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                  $('#select_add_quantity').children().remove().end().append('<option value="">Choose Quantity (KG)</option>');
                  // console.log(data_ho.rekomendasi_qty[0].quantity);
                  if(data_ho.rekomendasi_qty[0].quantity == null || data_ho.rekomendasi_qty[0].quantity == ""){
                    $('#div_input_quantity').show();
                    $('#div_select_quantity').hide();
                    $('#select_add_quantity').children().remove().end();
                  }else{
                    $('#select_add_quantity').append('<option value="' + data_ho.rekomendasi_qty[0].quantity + '" selected>' + data_ho.rekomendasi_qty[0].quantity + '</option>');
                    $.each(data_ho.rekomendasi_qty.slice(1), function(k, v) {
                      $('#select_add_quantity').append('<option value="' + v.quantity + '">' + v.quantity + '</option>');
                    });
                  }
                  $("#input_add_products").change(function() {
                    var select_produk = $(this).val();
                    var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                    url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                    $.get(url_saldo, function (data_saldo) {
                      $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                    })
                  });
                  $('#btn-new-products').prop('disabled', false);
                }else{
                  $('#show_saldo_select').html('').hide();
                  $('#show_saldo_input').html('').show();
                  var url = "{{ url('get_products') }}";
                  $.get(url, function (data_products) {
                    $('#input_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
                    $.each(data_products, function(k, v) {
                      $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                    });
                  })
                  $("#input_add_products").change(function() {
                    var select_produk = $(this).val();
                    var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                    url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                    $.get(url_saldo, function (data_saldo) {
                      $('#show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                    })
                  });
                  $('#select_add_quantity').children().remove().end();
                  $('#div_select_quantity').hide();
                  $('#div_input_quantity').show();
                  $('#btn-new-products').prop('disabled', true);
                }
              })

              $('#btn-cancel').click(function (e) {
                $('#div-btn-new').show();
                $('#div-btn-cancel').hide();
                $('#show_saldo_select').html('').show();
                $('#show_saldo_input').html('').hide();
                var custid = $('#input_custid').val();
                var id_alamat = $('#input_txt_kode_alamat').val();
                var url_ho = "{{ url('get_history_orders/custid/id_alamat') }}";
                url_ho = url_ho.replace('custid', enc(custid.toString()));
                url_ho = url_ho.replace('id_alamat', enc(id_alamat.toString()));
                $.get(url_ho, function (data_ho) {
                  $('#input_add_products').children().remove().end().append('<option value="">Choose Products</option>');
                  $('#input_add_products').append('<option value="' + data_ho.rekomendasi_produk[0].kode_produk + '" selected>' + data_ho.rekomendasi_produk[0].nama_produk + ' (' + data_ho.rekomendasi_produk[0].kode_produk + ')</option>');
                  $.each(data_ho.rekomendasi_produk.slice(1), function(k, v) {
                    $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
                  });
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(data_ho.rekomendasi_produk[0].kode_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                  $('#select_add_quantity').children().remove().end().append('<option value="">Choose Quantity (KG)</option>');
                  if(data_ho.rekomendasi_qty[0].quantity == null || data_ho.rekomendasi_qty[0].quantity == ""){
                    $('#div_input_quantity').show();
                    $('#div_select_quantity').hide();
                    $('#select_add_quantity').children().remove().end();
                  }else{
                    $('#div_select_quantity').show();
                    $('#div_input_quantity').hide();
                    $('#select_add_quantity').append('<option value="' + data_ho.rekomendasi_qty[0].quantity + '" selected>' + data_ho.rekomendasi_qty[0].quantity + '</option>');
                    $.each(data_ho.rekomendasi_qty.slice(1), function(k, v) {
                      $('#select_add_quantity').append('<option value="' + v.quantity + '">' + v.quantity + '</option>');
                    });
                  }
                  $("#input_add_products").change(function() {
                    var select_produk = $(this).val();
                    var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                    url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
                    $.get(url_saldo, function (data_saldo) {
                      $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                    })
                  });
                })
              });
            });
          }          
        })
      });

      $('#btn_new_address').click(function (e) {
        var custid = $(this).data("id");
        $('#new_address_custid').val(custid);
      });

      $('#btn-new-address-choose').click(function (e) {
        var custid = $(this).data("id");
        $('#new_address_custid').val(custid);
      });

      $('#btn-new-products').click(function (e) {
        $('#div-btn-new').hide();
        $('#div-btn-cancel').show();
        $('#show_saldo_select').html('').hide();
        $('#show_saldo_input').html('').show();

        var url = "{{ url('get_products') }}";
        $.get(url, function (data_products) {
          $('#input_add_products').children().remove().end().append('<option value="" selected>Choose Products</option>');
          $.each(data_products, function(k, v) {
            $('#input_add_products').append('<option value="' + v.kode_produk + '">' + v.nama_produk + ' (' + v.kode_produk + ')</option>');
          });
        })

        $("#input_add_products").change(function() {
          var select_produk = $(this).val();
          var url_saldo = "{{ url('get_saldo/kode_produk') }}";
          url_saldo = url_saldo.replace('kode_produk', enc(select_produk.toString()));
          $.get(url_saldo, function (data_saldo) {
            $('#show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
          })
        });

        $('#select_add_quantity').children().remove().end();
        $('#div_select_quantity').hide();
        $('#div_input_quantity').show();
      });

      $('body').on('click', '#delete-products', function () {
      var custid = $(this).data("id");
      var kode_produk = $(this).data("produk");
      if(confirm("Delete this products?")){
        $.ajax({
          type: "GET",
          url: "{{ url('products/delete') }}",
          data: { 'custid' : custid, 'kode_produk' : kode_produk },
          success: function (data) {
            var oTable = $('#input_order_products_table').dataTable(); 
            oTable.fnDraw(false);
            var output_input = null;
            var output_select = null;
            output_input = document.getElementById("show_saldo_input").innerHTML;
            output_select = document.getElementById("show_saldo_select").innerHTML;
            console.log(output_select +''+ output_input);
            if(output_select != null || output_select != ''){
              $('#show_saldo_select').html('').show();
              $('#show_saldo_input').html('').hide();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else if(output_input != null || output_input != ''){
              $('#show_saldo_select').html('').hide();
              $('#show_saldo_input').html('').show();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else{
              $('#show_saldo_select').html('').hide();
              $('#show_saldo_input').html('').hide();
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-edit-new-address-choose', function () {
      var custid = $(this).data("id");
      var nomor_sj_produk = $(this).data("sj");
      $('#edit_new_address_custid').val(custid);
      $('#edit_new_address_nomor_sj_produk').val(nomor_sj_produk);
    });

    $('body').on('click', '#edit-delete-data-orders', function () {
      var nomor_sj_produk = $(this).data("id");
      if(confirm("Delete this products?")){
        $.ajax({
          type: "GET",
          url: "{{ url('products/delete/edit') }}",
          data: { 'nomor_sj_produk' : nomor_sj_produk },
          success: function (data) {
            var oTable = $('#edit_order_products_table').dataTable(); 
            oTable.fnDraw(false);
            $('#modal_edit_order').modal('hide');
            $("#modal_edit_order").trigger('click');
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
            var oTable = $('#input_order_products_several_table').dataTable(); 
            oTable.fnDraw(false);
            var output_input = document.getElementById("show_saldo_input").innerHTML;
            var output_select = document.getElementById("show_saldo_select").innerHTML;
            if(output_select != null || output_select != ''){
              $('#show_saldo_select').html('').show();
              $('#show_saldo_input').html('').hide();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else if(output_input != null || output_input != ''){
              $('#show_saldo_select').html('').hide();
              $('#show_saldo_input').html('').show();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else{
              $('#show_saldo_select').html('').hide();
              $('#show_saldo_input').html('').hide();
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
            var oTable = $('#input_order_products_delivdate_table').dataTable(); 
            oTable.fnDraw(false);
            var output_input = document.getElementById("show_saldo_input").innerHTML;
            var output_select = document.getElementById("show_saldo_select").innerHTML;
            if(output_select != null || output_select != ''){
              $('#show_saldo_select').html('').show();
              $('#show_saldo_input').html('').hide();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else if(output_input != null || output_input != ''){
              $('#show_saldo_select').html('').hide();
              $('#show_saldo_input').html('').show();
              var url_saldo = "{{ url('get_saldo/kode_produk') }}";
              url_saldo = url_saldo.replace('kode_produk', enc(data.toString()));
              $.get(url_saldo, function (data_saldo) {
                $('#show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
              })
            }else{
              $('#show_saldo_select').html('').hide();
              $('#show_saldo_input').html('').hide();
            }
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $(document).on('show.bs.modal', '#modal_choose_address', function (e) {
      var custid = $('#input_custid').val();
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
          url: '/list/sales/address/' + enc(custid.toString()),
          data: { page: pg },
          success: function(data) {
            $('#div-address-list').html(data);
            $('html').animate({ scrollTop: 0 }, 'slow');
          }
        });
      });

      $('#div-address-list').load('/list/sales/address/' + enc(custid.toString()) + '?page=1');
    });

    $(document).on('show.bs.modal', '#modal_new_choose_address', function (e) {
      var custid = $('#new_custid_utama').val();
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
          url: '/list/new_sales/address/' + enc(custid.toString()),
          data: { page: pg },
          success: function(data) {
            $('#new-div-address-list').html(data);
            $('html').animate({ scrollTop: 0 }, 'slow');
          }
        });
      });

      $('#new-div-address-list').load('/list/new_sales/address/' + enc(custid.toString()) + '?page=1');
    });

    $(document).on('show.bs.modal', '#modal_edit_choose_address', function (e) {
      var custid = $('#input_custid').val();
      var nomor_sj_produk = $('#edit_nomor_order_receipt_produk').val();
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
          url: '/list/sales/address/edit/' + enc(custid.toString()) + '/' + enc(nomor_sj_produk.toString()),
          data: { page: pg },
          success: function(data) {
            $('#div-address-list-edit').html(data);
            $('html').animate({ scrollTop: 0 }, 'slow');
          }
        });
      });

      $('#div-address-list-edit').load('/list/sales/address/edit/' + enc(custid.toString()) + '/' + enc(nomor_sj_produk.toString()) + '?page=1');
    });

    $(document).on('show.bs.modal', '#modal_send_several_address', function (e) {
      var custid = $('#input_custid').val();
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
          url: '/list/sales/address/several/' + enc(custid.toString()),
          data: { page: pg },
          success: function(data) {
            $('#div-address-list-several').html(data);
            $('html').animate({ scrollTop: 0 }, 'slow');
          }
        });
      });

      $('#div-address-list-several').load('/list/sales/address/several/' + enc(custid.toString()) + '?page=1');
    });

    $(document).on('hidden.bs.modal', '#modal_send_several_address', function (e) {
      var custid = $('#input_custid').val();
      $('#input_customer_order').val('').trigger('change');
      $('#input_customer_order').val(custid).trigger('change').trigger({
        type: 'select2:selecting',
        params: {
          args: {
            data: {
              id: custid
            }
          } 
        }
      });
    });

    // $(document).on('hidden.bs.modal', '#modal_detail_order', function (e) {
    //   $('#orders_new_table').DataTable().destroy();
    //   load_data_orders_new();
    // })

    $('body').on('click', '#btn-choose-address', function () {
      var addressid = $(this).data("id");
      var custid = $(this).data("cust");
      $.ajax({
        type:"GET",
        url:"{{ url('address/sales/choose') }}",
        data: { 'addressid' : addressid, 'custid' : custid },
        success:function(data){
          $('#modal_choose_address').modal('hide');
          $("#modal_choose_address").trigger('click');
          $("#btn_input_orders").trigger('click');
          $('#input_customer_order').val('').trigger('change');
          $('#input_customer_order').val(data.custid_order).trigger('change').trigger({
            type: 'select2:selecting',
            params: {
              args: {
                data: {
                  id: data.custid_order
                }
              } 
            }
          });
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#btn-new-choose-address', function () {
      var addressid = $(this).data("id");
      var custid = $(this).data("cust");
      $.ajax({
        type:"GET",
        url:"{{ url('address/sales/choose') }}",
        data: { 'addressid' : addressid, 'custid' : custid },
        success:function(data){
          $('#modal_new_choose_address').modal('hide');
          $("#modal_new_choose_address").trigger('click');
          $("#edit_new_produk").trigger('click');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#btn-edit-choose-address', function () {
      var addressid = $(this).data("id");
      var nomor_sj_produk = $(this).data("sj");
      $.ajax({
        type:"GET",
        url:"{{ url('address/sales/choose/edit') }}",
        data: { 'addressid' : addressid, 'nomor_sj_produk' : nomor_sj_produk },
        success:function(data){
          $('#modal_edit_choose_address').modal('hide');
          $("#modal_edit_choose_address").trigger('click');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#btn-choose-address-several', function () {
      var addressid = $(this).data("id");
      var custid = $(this).data("cust");
      $.ajax({
        type:"GET",
        url:"{{ url('address/sales/choose_several') }}",
        data: { 'addressid' : addressid, 'custid' : custid },
        success:function(data){
          $('#div-address-list-several').load('/list/sales/address/several/' + enc(custid.toString()) + '?page=1');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#btn-cancel-address-several', function () {
      var addressid = $(this).data("id");
      var custid = $(this).data("cust");
      $.ajax({
        type:"GET",
        url:"{{ url('address/sales/cancel_several') }}",
        data: { 'addressid' : addressid, 'custid' : custid },
        success:function(data){
          $('#div-address-list-several').load('/list/sales/address/several/' + enc(custid.toString()) + '?page=1');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#btn-delete-address', function () {
      var addressid = $(this).data("id");
      var custid = $(this).data("cust");
      if(confirm("Delete this address?")){
        $.ajax({
          type: "GET",
          url: "{{ url('address/sales/delete') }}",
          data: { 'addressid' : addressid, 'custid' : custid },
          success: function (data) {
            $('#div-address-list').load('/list/sales/address/' + enc(custid.toString()) + '?page=1');
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#delete-orders', function () {
      var nomor = $(this).data("id");
      if(confirm("Hapus Pesanan Ini?")){
        $.ajax({
          type: "GET",
          url: "{{ url('order/delete') }}",
          data: { 'nomor' : nomor },
          success: function (data) {
            alert('Data Deleted');
            var oTable = $('#orders_new_table').dataTable();
            oTable.fnDraw(false);
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#btn-edit-delete-address', function () {
      var addressid = $(this).data("id");
      var custid = $(this).data("cust");
      var nomor_sj_produk = $('#edit_nomor_order_receipt_produk').val();
      if(confirm("Delete this address?")){
        $.ajax({
          type: "GET",
          url: "{{ url('address/sales/delete') }}",
          data: { 'addressid' : addressid, 'custid' : custid },
          success: function (data) {
            $('#div-address-list').load('/list/sales/address/edit/' + enc(custid.toString()) + '/' + enc(nomor_sj_produk.toString()) + '?page=1');
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });

    $('body').on('click', '#make_primary_address', function () {
      var addressid = $(this).data("id");
      var custid = $(this).data("cust");
      $.ajax({
        type:"GET",
        url:"{{ url('address/sales/primary') }}",
        data: { 'addressid' : addressid, 'custid' : custid },
        success:function(data){
          $('#div-address-list').load('/list/sales/address/' + enc(custid.toString()) + '?page=1');
        },
        error: function (data) {
          console.log('Error:', data);
          alert("Something Goes Wrong. Please Try Again");
        }
      });
    });

    $('body').on('click', '#edit_make_primary_address', function () {
      var addressid = $(this).data("id");
      var custid = $(this).data("cust");
      var nomor_sj_produk = $('#edit_nomor_order_receipt_produk').val();
      $.ajax({
        type:"GET",
        url:"{{ url('address/sales/primary') }}",
        data: { 'addressid' : addressid, 'custid' : custid },
        success:function(data){
          $('#div-address-list-edit').load('/list/sales/address/edit/' + enc(custid.toString()) + '/' + enc(nomor_sj_produk.toString()) + '?page=1');
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
    $(document).ready(function () {
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

      $('#validasi_adding_price_form').validate({
        rules: {
          tanggal_kirim: {
            required: true,
          },
          ekspedisi: {
            required: true,
          },
          pokok_add: {
            required: true,
          },
          stapel_add: {
            required: true,
          },
          ekspedisi_add: {
            required: true,
          },
        },
        messages: {
          tanggal_kirim: {
            required: "Tanggal Kirim Harus Diisi",
          },
          ekspedisi: {
            required: "Ekspedisi Harus Diisi",
          },
          pokok_add: {
            required: "Harga Pokok Harus Diisi",
          },
          stapel_add: {
            required: "Harga Stapel Harus Diisi",
          },
          ekspedisi_add: {
            required: "Harga Ekspedisi Harus Diisi",
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
          var myform = document.getElementById("validasi_adding_price_form");
          var formdata = new FormData(myform);

          $.ajax({
            type:'POST',
            url:"{{ url('validasi/orders/konfirmasi') }}",
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
              alert('Data Stored Successfully');
              $('#validasi_adding_price_form').trigger("reset");
              $('#products_add').val(data.nomor_order_receipt_produk).trigger('change');
              if(data.several_address == 0){
                $('#print_sp').show();
                $('#print_sp_terpisah').hide();
                $('#print_sp_gabung').hide();
              }else{
                $('#print_sp').hide();
                $('#print_sp_terpisah').show();
                $('#print_sp_gabung').show();
              }
              var oTable = $('#order_products_table').dataTable();
              oTable.fnDraw(false);
              var oTable = $('#orders_new_table').dataTable();
              oTable.fnDraw(false);
            },
            error: function (data) {
              console.log('Error:', data);
              alert('Something Went Wrong, Please Try Again');
            }
          });
        }
      });

      $('#edit_validasi_adding_price_form').validate({
        rules: {
          edit_tanggal_kirim: {
            required: true,
          },
          edit_ekspedisi: {
            required: true,
          },
          edit_pokok_add: {
            required: true,
          },
          edit_stapel_add: {
            required: true,
          },
          edit_ekspedisi_add: {
            required: true,
          },
          edit_harga_ongkir_add: {
            required: true,
          },
          edit_total_price_add: {
            required: true,
          }
        },
        messages: {
          edit_tanggal_kirim: {
            required: "Tanggal Kirim Harus Diisi",
          },
          edit_ekspedisi: {
            required: "Ekspedisi Harus Diisi",
          },
          edit_pokok_add: {
            required: "Harga Pokok Harus Diisi",
          },
          edit_stapel_add: {
            required: "Harga Stapel Harus Diisi",
          },
          edit_ekspedisi_add: {
            required: "Harga Ekspedisi Harus Diisi",
          },
          edit_harga_ongkir_add: {
            required: "Harga Ongkir Harus Diisi",
          },
          edit_total_price_add: {
            required: "Total Harga Harus Diisi",
          }
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
          var myform = document.getElementById("edit_validasi_adding_price_form");
          var formdata = new FormData(myform);

          $.ajax({
            type:'POST',
            url:"{{ url('validasi/orders/edit') }}",
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
              alert('Data Updated Successfully');
              $('#edit_validasi_adding_price_form').trigger("reset");
              $('#modal_edit_order').modal('hide');
              $("#modal_edit_order").trigger('click');
              var oTable = $('#orders_semua_table').dataTable();
              oTable.fnDraw(false);
              var oTable = $('#orders_confirmation_table').dataTable();
              oTable.fnDraw(false);
            },
            error: function (data) {
              console.log('Error:', data);
              alert('Something Went Wrong, Please Try Again');
            }
          });
        }
      });

      $('#new_address_form').validate({
        rules: {
          new_address_name: {
            required: true,
          },
          new_address_alamat: {
            required: true,
          },
          new_address_telepon: {
            required: true,
          },
          new_address_kota: {
            required: true,
          },
        },
        messages: {
          new_address_name: {
            required: "Nama Harus Diisi",
          },
          new_address_alamat: {
            required: "Alamat Harus Diisi",
          },
          new_address_telepon: {
            required: "Telepon Harus Diisi",
          },
          new_address_kota: {
            required: "Kota Harus Diisi",
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
          var myform = document.getElementById("new_address_form");
          var formdata = new FormData(myform);

          $.ajax({
            type:'POST',
            url:"{{ url('/change_address_admin') }}",
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
              alert('Address Added Successfully');
              $('#new_address_form').trigger("reset");
              $('#modal_new_address').modal('hide');
              $("#modal_new_address").trigger('click');
              $("#btn_input_orders").trigger('click');
              $('#input_customer_order').val('').trigger('change');
              $('#input_customer_order').val(data.custid_order).trigger('change').trigger({
                type: 'select2:selecting',
                params: {
                  args: {
                    data: {
                      id: data.custid_order
                    }
                  } 
                }
              });
            },
            error: function (data) {
              console.log('Error:', data);
            }
          });
        }
      });

      $('#edit_new_address_form').validate({
        rules: {
          edit_new_address_name: {
            required: true,
          },
          edit_new_address_alamat: {
            required: true,
          },
          edit_new_address_telepon: {
            required: true,
          },
          edit_new_address_kota: {
            required: true,
          },
        },
        messages: {
          edit_new_address_name: {
            required: "Nama Harus Diisi",
          },
          edit_new_address_alamat: {
            required: "Alamat Harus Diisi",
          },
          edit_new_address_telepon: {
            required: "Telepon Harus Diisi",
          },
          edit_new_address_kota: {
            required: "Kota Harus Diisi",
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
          var myform = document.getElementById("edit_new_address_form");
          var formdata = new FormData(myform);

          $.ajax({
            type:'POST',
            url:"{{ url('/change_address_admin_edit') }}",
            data: formdata,
            processData: false,
            contentType: false,
            success:function(data){
              alert('Address Added Successfully');
              $('#edit_new_address_form').trigger("reset");
              $('#modal_edit_new_address').modal('hide');
              $("#modal_edit_new_address").trigger('click');
            },
            error: function (data) {
              console.log('Error:', data);
            }
          });
        }
      });

      $('#productsform').validate({
        rules: {
          input_add_products: {
            required: true,
          },
          input_custid: {
            required: true,
          },
          input_add_quantity: {
            required: function(element) {
              return $("#select_add_quantity").has('option').length == 0;
            }
          },
          select_add_quantity: {
            required: function(element) {
              return $("#input_add_quantity").val().length == 0;
            }
          },
        },
        messages: {
          input_add_products: {
            required: "Products Harus Diisi",
          },
          input_add_quantity: {
            required: "Quantity Harus Diisi",
          },
          select_add_quantity: {
            required: "Quantity Harus Diisi",
          },
          input_custid: {
            required: "CustID Harus Diisi",
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
          var myform = document.getElementById("productsform");
          var formdata = new FormData(myform);

          if($('#input_custid').val() == null || $('#input_custid').val() == ''){
            alert('Pilih Customer Order Terlebih Dahulu');
          }else{
            $.ajax({
              type:'POST',
              url:"{{ url('add_products_sales') }}",
              data: formdata,
              processData: false,
              contentType: false,
              success:function(data){
                $('#productsform').trigger("reset");
                $('#select_address_products').val(data.address).trigger('change');
                var oTable = $('#input_order_products_table').dataTable();
                oTable.fnDraw(false);
                var oTableb = $('#input_order_products_several_table').dataTable();
                oTableb.fnDraw(false);
                var oTablec = $('#input_order_products_delivdate_table').dataTable();
                oTablec.fnDraw(false);
                $('#input_tanggal_kirim_products').flatpickr({
                  allowInput: true,
                  defaultDate: new Date(),
                  minDate: "today"
                });
                if(data.output == null || data.output == ''){
                  $('#show_saldo_select').html('').show();
                  $('#show_saldo_input').html('').hide();
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(data.kode_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_select').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                }else{
                  $('#show_saldo_select').html('').hide();
                  $('#show_saldo_input').html('').show();
                  var url_saldo = "{{ url('get_saldo/kode_produk') }}";
                  url_saldo = url_saldo.replace('kode_produk', enc(data.kode_produk.toString()));
                  $.get(url_saldo, function (data_saldo) {
                    $('#show_saldo_input').html('Saldo Tersisa : ' + (data_saldo.saldo).toLocaleString('id-ID') + ' KG');
                  })
                }
              },
              error: function (data) {
                console.log('Error:', data);
                $('#productsform').trigger("reset");
                var oTable = $('#input_order_products_table').dataTable();
                oTable.fnDraw(false);
                var oTableb = $('#input_order_products_several_table').dataTable();
                oTableb.fnDraw(false);
                var oTablec = $('#input_order_products_delivdate_table').dataTable();
                oTablec.fnDraw(false);
                $('#input_tanggal_kirim_products').flatpickr({
                  allowInput: true,
                  defaultDate: new Date(),
                  minDate: "today"
                });
                alert("Something Goes Wrong. Please Try Again");
              }
            });
          }
        }
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
      $('.customer').select2({
        dropdownParent: $('#modal_input_orders .modal-content'),
        placeholder: 'Pilih Customer Order',
        allowClear: true,
        ajax: {
          url: '/autocomplete',
          data: function (params) {
            var company = 'DSGM';
            return {
              q: params.term,
              company: company
            };
          },
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                return {
                  text: item.custname,
                  id: item.custid
                }
              })
            };
          },
          cache: true
        }
      });

      $('.edit-customer').select2({
        dropdownParent: $('#modal_edit_order .modal-content'),
        placeholder: 'Pilih Customer Order',
        allowClear: true,
        ajax: {
          url: '/autocomplete',
          data: function (params) {
            var company = 'DSGM';
            return {
              q: params.term,
              company: company
            };
          },
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                return {
                  text: item.custname,
                  id: item.custid
                }
              })
            };
          },
          cache: true
        }
      });


      $('.city').select2({
        dropdownParent: $('#modal_input_orders .modal-content'),
        placeholder: 'Kota',
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

      $('.city-address').select2({
        dropdownParent: $('#modal_new_address .modal-content'),
        placeholder: 'Kota',
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

      $('.edit-city-address').select2({
        dropdownParent: $('#modal_edit_new_address .modal-content'),
        placeholder: 'Kota',
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
    });
  </script>

  <script type="text/javascript">
    $(".customer").on("select2:open", function() {
      $(".select2-search__field").attr("placeholder", "Search Customer Here...");
    });
    $(".customer").on("select2:close", function() {
      $(".select2-search__field").attr("placeholder", null);
    });
    $(".city").on("select2:open", function() {
      $(".select2-search__field").attr("placeholder", "Search City Here...");
    });
    $(".city").on("select2:close", function() {
      $(".select2-search__field").attr("placeholder", null);
    });
    $(".city-address").on("select2:open", function() {
      $(".select2-search__field").attr("placeholder", "Search City Here...");
    });
    $(".city-address").on("select2:close", function() {
      $(".select2-search__field").attr("placeholder", null);
    });
    $(".edit-city-address").on("select2:open", function() {
      $(".select2-search__field").attr("placeholder", "Search City Here...");
    });
    $(".edit-city-address").on("select2:close", function() {
      $(".select2-search__field").attr("placeholder", null);
    });
  </script>
@endsection
