@foreach($data as $data_address)
<div class="row"> 
<div class="col-12">
<div class="card">
    <div class="card-body">
        <div class="row"> 
            <div class="col-8">
                <p><h4>{{ $data_address->custname_receive }}</h4></p>
            </div>
            <div class="col-4"></div>
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
            <?php
            if($data_address->choosen == 1){
                ?>
                <div class="col-4">
                    <a class="btn btn-success btn-address" href="#" disabled>Choosen <i class="fa fa-arrow-circle-right"></i></a>
                    <a class="btn btn-danger btn-address" href="#" data-id="{{ $data_address->id_alamat_receive }}" data-cust="{{ $data_address->custid_order }}" id="btn-cancel-address-several">Cancel <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <?php
            }else{
                ?>
                <div class="col-2"></div>
                <div class="col-2">
                    <a class="btn btn-primary btn-address" href="#" data-id="{{ $data_address->id_alamat_receive }}" data-cust="{{ $data_address->custid_order }}" id="btn-choose-address-several">Choose <i class="fa fa-arrow-circle-right"></i></a>
                </div>
                <?php
            }
            ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endforeach

<?php echo $data->appends(request()->input())->links(); ?>