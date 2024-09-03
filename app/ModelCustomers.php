<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelCustomers extends Model
{
    protected $table = 'customers';

    protected $fillable = ['custid','userid','id_sales','custid_agent','groupid','custname','company','crd','custlimit','address','city','phone','fax','spv','sls','telesls','npwp','image_npwp','nik','image_nik','wraddress','nama_cp','jabatan_cp','bidang_usaha','telepon_cp','created_at','created_by','updated_at','updated_by'];
}
