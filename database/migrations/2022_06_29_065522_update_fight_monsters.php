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
        Schema::table('fight', function (Blueprint $table) {
            $table->renameColumn('monsterId', 'monster1Id');
            $table->renameColumn('monsterHp', 'monster1Hp');
            $table->integer('monster2Id')->default(0);
            $table->integer('monster2Hp')->default(1000);
            $table->integer('monster3Id')->default(0);
            $table->integer('monster3Hp')->default(1000);
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
