<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFightBuff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fight', function (Blueprint $table) {
            $table->integer('buffId')->default(0);
            $table->integer('buffDuration')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fight', function (Blueprint $table) {
            $table->dropColumn('buffId');
            $table->dropColumn('buffDuration');
        });
    }
}
