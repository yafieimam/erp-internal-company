<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelPermintaanSampleDetail extends Model
{
    protected $table = 'permintaan_sample_detail';
    public $timestamps = false;

    protected $fillable = ['nomor_permintaan_sample_detail', 'nomor_permintaan_sample', 'nomor_laporan_lab', 'mesh', 'ssa', 'd50', 'd98', 'cie86', 'iso2470', 'moisture', 'residue'];
}
