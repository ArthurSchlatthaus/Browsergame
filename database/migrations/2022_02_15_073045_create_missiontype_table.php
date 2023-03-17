<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissiontypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mission_types');
        Schema::create('mission_types', function (Blueprint $table) {
            $table->id();
            $table->integer('time');
            $table->integer('monster1');
            $table->integer('monster2');
            $table->integer('monster3');
            $table->integer('monster4');
            $table->integer('monster5');
            $table->integer('monster6');
            $table->integer('monster7');
            $table->integer('monster8');
            $table->integer('monster9');
            $table->integer('monster10');
            $table->integer('gold');
            $table->integer('exp');
            $table->integer('item1')->default(0);
            $table->integer('item2')->default(0);
            $table->integer('item3')->default(0);
            $table->integer('item4')->default(0);
            $table->integer('item5')->default(0);
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
        Schema::dropIfExists('missiontype');
    }
}
