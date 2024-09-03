@forelse($data as $data_status)
<div class="col-sm-12 border" style="padding: 0; margin-bottom: 20px;">
  <?php
  if($data_status->status_order == 1){
  ?>
  <div class="info-orders" style="background-color: #fdffdb;">Pesanan Baru</div>
  <?php
  }else if($data_status->status_order == 2){
  ?>
  <div class="info-orders" style="background-color: #fdffdb;">Konfirmasi</div>
  <?php
  }else if($data_status->status_order == 3){
  ?>
  <div class="info-orders" style="background-color: #fdffdb;">Produksi</div>
  <?php
  }else if($data_status->status_order == 4){
  ?>
  <div class="info-orders" style="background-color: #fdffdb;">Pengiriman</div>
  <?php
  }else if($data_status->status_order == 5){
  ?>
  <div class="info-orders" style="background-color: #fdffdb;">Sedang Transit</div>
  <?php
  }else if($data_status->status_order == 6){
  ?>
  <div class="info-orders" style="background-color: #e7ffe3;">Pesanan Tiba</div>
  <?php
  }
  ?>
  <ul>
    <li><a href=""><i class="fa fa-calendar-o"></i>{{ $data_status->tanggal_order }}</a></li>
    <li><a href=""><i class="fa fa-tag"></i>{{ $data_status->nomor_order_receipt }}</a></li>
  </ul>
  <hr>
  <div class="list-products">
    <p class="produk-orders" align="justify">{{ $data_status->produk }}</p>
    <p class="total-orders">Total Pembayaran:</p>
    <p style="color: #ff930f;">{{ number_format($data_status->tonase,0,',','.') }} KG
      <a class="float-right" href="#" data-id="{{ $data_status->custid }}" data-sj="{{ $data_status->nomor_order_receipt }}" data-toggle="modal" data-target="#modal_detail_order" id="detail_order">Lebih Detail <i class="fa fa-arrow-circle-right"></i></a>
    </p>
  </div>
</div>
@empty
<h3 align="center" style="position: absolute; top: 50%; left: 50%;">Tidak Ada Data</h3>
@endforelse

<?php echo $data->appends(request()->input())->links(); ?>