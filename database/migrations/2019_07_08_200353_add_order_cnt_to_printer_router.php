<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderCntToPrinterRouter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('printer_company_printer_router', 'order_cnt')) {
            Schema::table('printer_company_printer_router', function (Blueprint $table) {
                //
                $table->integer('order_cnt')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('printer_company_printer_router', function (Blueprint $table) {
            //
        });
    }
}