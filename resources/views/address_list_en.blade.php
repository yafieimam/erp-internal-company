@foreach($data as $data_address)
<div class="col-sm-12 border list-address" style="padding: 0; margin-top: 20px">
  <div class="list-products" style="padding: 10px 15px 10px 15px;">
    <?php
    if($data_address->main_address == 1){
    ?>
    <p class="float-right"><b>(Primary Address)</b></p>
    <?php
    }else{
    ?>
    <a class="float-right" href="#" data-id="{{ $data_address->id_alamat_receive }}" id="make_primary_address">Make Primary Address <i class="fa fa-arrow-circle-right"></i></a>
    <?php
    }
    ?>
    <p class="total-orders"><h4>{{ $data_address->custname_receive }}</h4></p>
    <p class="total-orders">{{ $data_address->phone_receive }}</p>
    <p align="justify">{{ $data_address->address_receive }}</p>
    <p class="total-orders"> {{ $data_address->name_city }}</p>
    <p>
    <?php
    if($data_address->choosen == 1){
    ?>
      <a class="btn btn-success float-right" href="#" style="margin-bottom: 10px; margin-top: 16px; border: 0 none;" disabled>Choosen <i class="fa fa-arrow-circle-right"></i></a>
    <?php
    }else{
    ?>
      <a class="btn btn-primary float-right" href="#" data-id="{{ $data_address->id_alamat_receive }}" id="btn-choose-address" style="margin-bottom: 10px;">Choose <i class="fa fa-arrow-circle-right"></i></a>
    <?php
    }
    ?>
      <a class="btn btn-danger float-right" href="#" data-id="{{ $data_address->id_alamat_receive }}" id="btn-delete-address" style="margin-bottom: 10px; margin-top: 16px; border: 0 none; margin-right: 10px;"><i class="fa fa-trash"></i></a>
    </p>
  </div>
</div>
@endforeach

<?php echo $data->appends(request()->input())->links(); ?>