<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelLeads extends Model
{
	protected $table = 'leads';
	public $timestamps = false;

	protected $fillable = ['leadid','id_sales','nama','company','crd','custlimit','address','city','phone','fax','spv','sls','telesls','npwp','image_npwp','nik','image_nik','wraddress','nama_cp','jabatan_cp','bidang_usaha','status','created_at','created_by','updated_at','updated_by'];
}
