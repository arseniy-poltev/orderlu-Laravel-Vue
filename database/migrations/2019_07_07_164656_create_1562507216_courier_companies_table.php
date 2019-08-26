<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1562507216CourierCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('courier_companies')) {
            Schema::create('courier_companies', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('postmen_id');
                $table->string('logo_url')->nullable();

                $table->timestamps();
                // $table->softDeletes();

                // $table->index(['deleted_at']);
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
        Schema::dropIfExists('courier_companies');
    }
}