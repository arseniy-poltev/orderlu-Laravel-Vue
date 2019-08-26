<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourierRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_routers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('continent');
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            // $table->integer('courier_company_id');
            // $table->integer('percent')->default(100);
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
        Schema::dropIfExists('courier_routers');
    }
}