<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServerTasksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_tasks', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('server');
			$table->string('command');
            $table->integer('increment');
            $table->boolean('active');
            $table->timestamp('start');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('servers');
    }

}
