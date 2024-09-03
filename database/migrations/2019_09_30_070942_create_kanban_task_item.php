<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKanbanTaskItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('kanban.database_connection'))->create('kanban_task_item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_prefixed_id')->nullable();

            $table->string('taskname',255)->nullable();
            $table->text('remark')->nullable();
            $table->string('photo',255)->nullable();
            $table->string('name',255)->nullable();
            $table->string('gender',10)->nullable();
            $table->string('country')->nullable();
            $table->string('agency',255)->nullable();
            $table->string('position',255)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('email',255)->nullable();

            $table->text('rendered_html')->nullable();

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
        Schema::connection(config('kanban.database_connection'))->dropIfExists('kanban_task_item');
    }
}
