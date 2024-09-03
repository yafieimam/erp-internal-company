<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskItem extends Model
{
    use SoftDeletes;

    protected $table = 'kanban_task_item';
    protected $primaryKey = 'id';
    protected $fillable = [
        'item_prefixed_id',
        'title_name',
        'deskripsi',
        'deadline_date',
        'rendered_html'
    ];
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

    public function boards()
    {
        return $this->belongsToMany('App\TaskBoard','kanban_task_board_item','itemId','boardId')->withPivot('active','entered_at','left_at','item_order','archived');
    }

    public function activeboards()
    {
        return $this->boards()->wherePivot('active','=',1)->wherePivot('archived','=',0)->orderBy('pivot_item_order', 'ASC');
    }
}
