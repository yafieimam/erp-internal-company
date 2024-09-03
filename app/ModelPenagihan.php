<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelPenagihan extends Model
{
    protected $table = 'penagihan';
    public $timestamps = false;

    protected $fillable = ['nosj','tanggal_do','top','noinv','noar','noso','nopo','sls','custid','custname','dpp','ppn','amount','qty','sat','itemid','itemname','g','price','diskon','sub_amount','hrg_pkk','sub_pkk','percent_ppn','keterangan','check_dikirim','tanggal_kirim','check_diterima','tanggal_terima','order_sort_dokumen','order_sort_penagihan','tanggal_jadwal_penyerahan','tanggal_jadwal_penagihan','tanggal_terima_dokumen_cust','keterangan_penerimaan','tanggal_tagih_cust','keterangan_penagihan','metode_pembayaran','nomor_metode_pembayaran','tanggal_jatuh_tempo', 'hitung_suspend','status_jadwal','status_hadir','created_at','updated_at','created_by','updated_by'];
}
