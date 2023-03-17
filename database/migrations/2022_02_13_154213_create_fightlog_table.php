<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFightlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('fightlog');
        Schema::create('fightlog', function (Blueprint $table) {
            $table->id();
            $table->integer('playerId')->default(0);
            $table->integer('monsterId')->default(0);
            $table->integer('avgPlayerDamage')->default(0);
            $table->text('text');
            $table->integer('winnerId')->default(0);
            $table->integer('looserId')->default(0);
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
        Schema::dropIfExists('fightlog');
    }
}
