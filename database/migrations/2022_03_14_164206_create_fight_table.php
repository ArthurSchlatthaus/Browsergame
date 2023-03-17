<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('fight');
        Schema::create('fight', function (Blueprint $table) {
            $table->id();
            $table->integer('playerId');
            $table->integer('monsterId')->default(0);
            $table->integer('monsterHp')->default(1000);
            $table->integer('isActive')->default(0);
            $table->integer('isAutoFight')->default(0);
            $table->integer('playerIsWinner')->default(0);
            $table->integer('rounds')->default(0);
            $table->integer('dmgAvg')->default(0);
            $table->integer('canceled')->default(0);
            $table->timestamp('start_at')->nullable();
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
        Schema::dropIfExists('fight');
    }
}
