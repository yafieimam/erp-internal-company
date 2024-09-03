<div class="row">
  @forelse($data as $data_status)
  <div class="col-md-4">
    <?php
    if($data_status->status_order == 1){
    ?>
    <div class="card card-warning collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Confirmation</h3>
    <?php
    }else if($data_status->status_order == 2){
    ?>
    <div class="card card-warning collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Process</h3>
    <?php
    }else if($data_status->status_order == 3){
    ?>
    <div class="card card-warning collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Produksi</h3>
    <?php
    }else if($data_status->status_order == 4){
    ?>
    <div class="card card-warning collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Ship</h3>
    <?php
    }else if($data_status->status_order == 5){
    ?>
    <div class="card card-success collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Arrive</h3>
    <?php
    }else if($data_status->status_order == 6){
    ?>
    <div class="card card-danger collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Complain</h3>
    <?php
    }else if($data_status->status_order == 7){
    ?>
    <div class="card card-success collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Complete</h3>
    <?php
    }else if($data_status->status_order == 8){
    ?>
    <div class="card card-danger collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Cancel</h3>
    <?php
    }else if($data_status->status_order == 9){
    ?>
    <div class="card card-danger collapsed-card">
      <div class="card-header">
        <h3 class="card-title">{{ $data_status->nomor_order_receipt }} - Replace</h3>
    <?php
    }
    ?>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <p>{{ $data_status->nama_customer }}</p>
        <ul>
          <?php 
          $myString = $data_status->produk;
          $myArray = explode(',', $myString);
          foreach($myArray as $produk) {
          ?>
          <li>{{ $produk }}</li>
          <?php
          }
          ?>
        </ul>
        <p>Tonase : {{ $data_status->tonase }} TON</p>
        <p>Delivery Date : {{ $data_status->tanggal_kirim }}</p>
        <p>Total : {{ number_format($data_status->total,2,',','.') }}</p>
      </div>
      <!-- /.card-body -->
      <a href="#" data-id="{{ $data_status->custid }}" data-sj="{{ $data_status->nomor_order_receipt }}" data-toggle="modal" data-target="#modal_detail_order" id="detail_order" class="card-footer" style="text-align: center;">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
  @empty
  <h3 align="center" style="position: absolute; top: 60%; left: 45%;">No Data</h3>
  @endforelse
</div>

<?php echo $data->appends(request()->input())->links(); ?>