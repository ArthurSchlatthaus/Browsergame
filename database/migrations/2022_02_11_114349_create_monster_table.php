<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonsterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('monsters');
        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('level')->default('1');
            $table->integer('exp')->default('1');
            $table->integer('rank')->default('1');
            $table->integer('gold')->default('100');
            $table->integer('str')->default('1');
            $table->integer('int')->default('1');
            $table->integer('dex')->default('1');
            $table->integer('vit')->default('1');
            $table->integer('aw')->default('25');
            $table->integer('def')->default('25');
            $table->integer('hp')->default('1000');
            $table->integer('sp')->default('500');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monster');
    }
}
