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
        Schema::create('pvp', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('attackerId');
            $table->integer('defenderId');
            $table->integer('attackerDmg');
            $table->integer('defenderDmg');
            $table->integer('attackerHp');
            $table->integer('defenderHp');
            $table->integer('attackerSp');
            $table->integer('defenderSp');
            $table->integer('rounds');
            $table->integer('winner');
            $table->integer('looser');
            $table->integer('attackerBuffId');
            $table->integer('defenderBuffId');
            $table->integer('attackerBuffDuration');
            $table->integer('defenderBuffDuration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pvp');
    }
};
