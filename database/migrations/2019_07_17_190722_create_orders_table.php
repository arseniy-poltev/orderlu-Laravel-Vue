<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_code');
            $table->integer('book_count');

            //address information
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('zip_code');
            $table->string('street_address');
            $table->string('suite_number');
            //----------------------------
            $table->integer('printer_company_id');
            $table->integer('courier_company_id');
            $table->string('courier_tracking')->nullable();
            $table->string('track_number')->nullable();

            $table->string('web_hook_url')->nullable();

            $table->string('status')->default('pending');
            $table->dateTime('printed_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->dateTime('picked_at')->nullable();
            $table->dateTime('delivered_at')->nullable();

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
        Schema::dropIfExists('orders');
    }
}