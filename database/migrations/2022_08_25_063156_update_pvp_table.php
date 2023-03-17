<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pvp', function ($table) {
            $table->integer('attackerDmg')->default(0)->change();
            $table->integer('defenderDmg')->default(0)->change();
            $table->integer('rounds')->default(0)->change();
            $table->integer('winner')->nullable()->change();
            $table->integer('looser')->nullable()->change();
            $table->integer('attackerBuffId')->nullable()->change();
            $table->integer('defenderBuffId')->nullable()->change();
            $table->integer('attackerBuffDuration')->default(0)->change();
            $table->integer('defenderBuffDuration')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
