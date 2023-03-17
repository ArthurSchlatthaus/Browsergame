<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('missions');
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->integer('playerId');
            $table->integer('missionId');
            $table->timestamp('end_at')->nullable();
            $table->integer('gotreward')->default(0);
            $table->integer('autoFight')->default(1);
            $table->integer('canceled')->default(0);
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
        Schema::dropIfExists('missions');
    }
}
