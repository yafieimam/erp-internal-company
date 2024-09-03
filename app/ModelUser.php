<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ModelUser extends Model
{
	use Notifiable;
	
    protected $table = 'users';
}
