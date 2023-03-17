<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus', function (Blueprint $table) {
            $table->id();
            $table->enum('apply', ["MAX_HP", "MAX_SP", "VIT", "INT", "STR", "DEX", "HP_REGEN", "SP_REGEN", "ATT_BONUS", "DEF_BONUS"]);
            $table->integer('prob')->default(0);
            $table->integer('min')->default(0);
            $table->integer('max')->default(0);
            $table->integer('weapon')->default(0);
            $table->integer('body')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus');
    }
};
