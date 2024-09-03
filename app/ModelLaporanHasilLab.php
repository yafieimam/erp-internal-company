<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelLaporanHasilLab extends Model
{
    protected $table = 'laporan_hasil_produksi_lab';
    public $timestamps = false;

    protected $fillable = ['nomor_laporan_produksi_lab', 'nomor_laporan_produksi','jam_waktu','mesin','rpm','mesh','std_ssa','ssa', 'std_d50', 'd50', 'std_d98', 'd98', 'cie86', 'iso2470', 'moisture', 'residue'];
}
