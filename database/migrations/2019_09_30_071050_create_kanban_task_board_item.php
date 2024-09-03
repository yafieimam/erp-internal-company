<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKanbanTaskBoardItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('kanban.database_connection'))->create('kanban_task_board_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('boardId')->unsigned();
            $table->integer('itemId')->unsigned();
            $table->tinyInteger('active');
            $table->dateTime('entered_at')->nullable();
            $table->dateTime('left_at')->nullable();
            $table->integer('item_order')->default(0);
            $table->integer('archived')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('kanban.database_connection'))->dropIfExists('kanban_task_board_item');
    }
}
