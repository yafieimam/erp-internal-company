<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelPurchaseBatu extends Model
{
    protected $table = 'purchase_batu';
    public $timestamps = false;

    protected $fillable = ['grno','tanggal','nopo','sls','purtype','vendorid','nama_vendor','basegst','gstvalue','amount','user','qpur','sat','qtystock','produk','kode_batu','nama_batu', 'wrh', 'subitem', 'location', 'from_batu', 'price', 'disc', 'subamount'];
}
