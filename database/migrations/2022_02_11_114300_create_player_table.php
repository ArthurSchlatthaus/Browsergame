<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('level')->default(1);
            $table->float('exp')->default(0);
            $table->integer('str')->default(1);
            $table->integer('int')->default(1);
            $table->integer('dex')->default(1);
            $table->integer('vit')->default(1);
            $table->integer('gold')->default(0);
            $table->float('aw')->default(25);
            $table->float('def')->default(25);
            $table->float('hp')->default(1000);
            $table->float('maxHp')->default(1000);
            $table->float('sp')->default(500);
            $table->float('maxSp')->default(500);
            $table->float('hpRegeneration')->default(10);
            $table->float('spRegeneration')->default(10);
            $table->integer('status')->default(0);
            $table->integer('race');
            $table->integer('class')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player');
    }
}
