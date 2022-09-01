<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInRestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rest_logs', function (Blueprint $table) {
            $table->longText('header')->after('res_payload')->nullable();
            $table->longText('res_header')->after('header')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rest_logs', function (Blueprint $table) {
            $table->dropColumn('header', 'res_header');
        });
    }
}
