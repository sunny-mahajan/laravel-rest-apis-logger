<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rest_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('method');
            $table->string('url');
            $table->longText('payload');
            $table->longText('res_status');
            $table->longText('res_payload');
            $table->string('duration');
            $table->string('controller');
            $table->string('action');
            $table->string('models');
            $table->string('ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rest_logs');
    }
}
