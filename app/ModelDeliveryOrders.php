<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelDeliveryOrders extends Model
{
    protected $table = 'delivery_orders';
    public $timestamps = false;

    protected $fillable = ['nosj','tanggal_do','top','tanggal_jatuh_tempo','noinv','noso','nopo','sls','custid','custname','dpp','ppn','amount','qty','sat','itemid','itemname','g','price','diskon','sub_amount','hrg_pkk','sub_pkk','percent_ppn'];
}
