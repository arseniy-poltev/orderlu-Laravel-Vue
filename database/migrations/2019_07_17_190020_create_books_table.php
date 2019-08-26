<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');

            $table->string('book_name');
            $table->string('book_code');
            $table->string('status')->default('pending');
            $table->string('language');
            $table->string('pdf_url');

            $table->integer('lot_id')->nullable();
            $table->integer('order_id');

            $table->integer('printer_company_id');
            $table->integer('courier_company_id');

            $table->dateTime('assigned_at')->nullable();
            $table->dateTime('printed_at')->nullable();

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
        Schema::dropIfExists('books');
    }
}