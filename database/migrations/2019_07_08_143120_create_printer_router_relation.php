<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrinterRouterRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printer_company_printer_router', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('printer_company_id');
            $table->integer('printer_router_id');
            $table->integer('percent');
            // $table->integer('order_cnt')->default(0);
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
        Schema::dropIfExists('printer_router_relation');
    }
}