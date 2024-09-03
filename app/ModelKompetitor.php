<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelKompetitor extends Model
{
	protected $table = 'kompetitor';
	public $timestamps = false;

	protected $fillable = ['kompid','id_sales','nama','company','crd','custlimit','address','city','phone','fax','spv','sls','telesls','npwp','image_npwp','nik','image_nik','wraddress','nama_cp','jabatan_cp','bidang_usaha','created_at','created_by','updated_at','updated_by'];
}
