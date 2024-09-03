<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelLaporanHasilProduksiDetail extends Model
{
    protected $table = 'laporan_hasil_produksi_detail';
    public $timestamps = false;

    protected $fillable = ['nomor_laporan_produksi_detail','nomor_laporan_produksi','mesin','jenis_produk','jumlah_sak','jumlah_tonase'];
}
