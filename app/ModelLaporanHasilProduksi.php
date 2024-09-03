<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelLaporanHasilProduksi extends Model
{
    protected $table = 'laporan_hasil_produksi';
    public $timestamps = false;

    protected $fillable = ['nomor_laporan_produksi','tanggal_laporan_produksi','tanggal_input','created_at','updated_at','created_by','updated_by'];
}
