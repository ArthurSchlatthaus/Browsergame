<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlayerSkills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->renameColumn('status', 'freeStatusPoints');
            $table->integer('freeSkillPoints')->default(0);
            $table->integer('skill0id')->default(0);
            $table->integer('skill0level')->default(0);
            $table->integer('skill1id')->default(0);
            $table->integer('skill1level')->default(0);
            $table->integer('skill2id')->default(0);
            $table->integer('skill2level')->default(0);
            $table->integer('skill3id')->default(0);
            $table->integer('skill3level')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('freeSkillPoints');
            $table->dropColumn('skill0id');
            $table->dropColumn('skill1id');
            $table->dropColumn('skill2id');
            $table->dropColumn('skill3id');
            $table->dropColumn('skill0level');
            $table->dropColumn('skill1level');
            $table->dropColumn('skill2level');
            $table->dropColumn('skill3level');
        });
    }
}
