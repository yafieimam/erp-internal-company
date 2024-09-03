<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskBoard extends Model
{
    use SoftDeletes;

    protected $table = 'kanban_task_board';
    protected $primaryKey = 'id';
    protected $fillable = ['board_prefixed_id','title','board_order'];
    protected $dates = ['deleted_at'];

    protected $connection = 'mysql';

    //override the db connection name from config file
    public function getConnectionName()
    {
        $databaseConnection = config('kanban.database_connection');
        if (!empty($databaseConnection)) {
            $this->connection = $databaseConnection;
        }
        return parent::getConnectionName();
    }

    public function items()
    {
        return $this->belongsToMany('App\TaskItem','kanban_task_board_item','boardId','itemId')->withPivot('active','entered_at','left_at','item_order','archived');
    }

    public function activeitems()
    {
        return $this->items()->wherePivot('active','=',1)->wherePivot('archived','=',0)->orderBy('pivot_item_order', 'ASC');
    }
}
