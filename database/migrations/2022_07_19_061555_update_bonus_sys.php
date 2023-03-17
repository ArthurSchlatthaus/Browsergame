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
        Schema::table('inventory', function (Blueprint $table) {
            $table->integer('attrType0')->default(0);
            $table->integer('attrValue0')->default(0);
            $table->integer('attrType1')->default(0);
            $table->integer('attrValue1')->default(0);
            $table->integer('attrType2')->default(0);
            $table->integer('attrValue2')->default(0);
            $table->integer('attrType3')->default(0);
            $table->integer('attrValue3')->default(0);
            $table->integer('attrType4')->default(0);
            $table->integer('attrValue4')->default(0);
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
