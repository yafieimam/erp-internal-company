<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelAbsensi extends Model
{
    protected $table = 'transaksi_absensi';
    public $timestamps = false;

    protected $fillable = ['nik','tanggal_absensi','jam_masuk','jam_pulang', 'status_hari'];
}
