@foreach($data as $data_address)
<div class="row"> 
<div class="col-12">
<div class="card">
    <div class="card-body">
        <div class="row"> 
            <div class="col-8">
                <p><h4>{{ $data_address->custname_receive }}</h4></p>
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
            <div class="col-2"></div>
            <div class="col-2">
                <?php
                if($data_address->choosen == 1){
                    ?>
                    <a class="btn btn-success" href="#" disabled>Choosen <i class="fa fa-arrow-circle-right"></i></a>
                    <?php
                }else{
                    ?>
                    <a class="btn btn-primary" href="#" data-id="{{ $data_address->id_alamat_receive }}" data-cust="{{ $data_address->custid_order }}" id="btn-new-choose-address">Choose <i class="fa fa-arrow-circle-right"></i></a>
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