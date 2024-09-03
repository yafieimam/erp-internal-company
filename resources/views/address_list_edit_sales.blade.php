@foreach($data as $data_address)
<div class="row"> 
<div class="col-12">
<div class="card">
    <div class="card-body">
        <div class="row"> 
            <div class="col-8">
                <p><h4>{{ $data_address->custname_receive }}</h4></p>
            </div>
            <div class="col-4">
                <?php
                if($data_address->main_address == 1){
                ?>
                    <p><h5 class="primary-address">(Primary Address)</h5></p>
                <?php
                }else{
                ?>
                    <p><a href="#" data-id="{{ $data_address->id_alamat_receive }}" data-cust="{{ $data_address->custid_order }}" id="edit_make_primary_address"><h5 class="primary-address">Make Primary Address <i class="fa fa-arrow-circle-right"></i></h5></a></p>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="row"> 
            <div class="col-12"><p>{{ $data_address->phone_receive }}</p></div>
        </div>
        <div class="row"> 
            <div class="col-12"><p align="justify">{{ $data_address->address_receive }}</p></div>
        </div>
        <div class="row"> 
            <div class="col-4"><p> {{ $data_address->name_city }} </p></div>
            <div class="col-4"></div>
            <div class="col-2">
                <a class="btn btn-danger btn-address" href="#" data-id="{{ $data_address->id_alamat_receive }}" data-cust="{{ $data_address->custid_order }}" id="btn-edit-delete-address"><i class="fa fa-trash"></i></a>
            </div>
            <div class="col-2">
                <a class="btn btn-primary btn-address" href="#" data-id="{{ $data_address->id_alamat_receive }}" data-sj="{{ $nomor_sj_produk }}" id="btn-edit-choose-address">Choose <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endforeach

<?php echo $data->appends(request()->input())->links(); ?>