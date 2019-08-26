<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderCntToCourierRouter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('courier_company_courier_router', 'order_cnt')) {
            Schema::table('courier_company_courier_router', function (Blueprint $table) {
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
        Schema::table('courier_company_courier_router', function (Blueprint $table) {
            //
        });
    }
}