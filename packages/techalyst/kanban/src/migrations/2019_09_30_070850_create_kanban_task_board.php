<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKanbanTaskBoard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('kanban.database_connection'))->create('kanban_task_board', function (Blueprint $table) {
            $table->increments('id');
            $table->string('board_prefixed_id')->nullable();
            $table->text('title');
            $table->integer('board_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->integer('deleted_by')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('kanban.database_connection'))->dropIfExists('kanban_task_board');
    }
}
