<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('items');
        Schema::create('items', function (Blueprint $table) {
            $table->id('vnum');
            $table->string('name')->default('noname');
            $table->integer('type');
            $table->integer('size');
            $table->integer('subtype');
            $table->integer('level')->default(1);
            $table->integer('value0')->default(0);
            $table->integer('value1')->default(0);
            $table->integer('value2')->default(0);
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
        Schema::dropIfExists('item');
    }
}
