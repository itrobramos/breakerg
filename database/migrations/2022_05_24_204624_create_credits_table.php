<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('saleId')->unsigned();
            $table->bigInteger('clientId')->unsigned();
            $table->decimal('initialPayment',12,2)->nullable();
            $table->decimal('credit',12,2)->nullable();
            $table->decimal('total',12,2)->nullable();
            $table->decimal('currentCredit',12,2)->nullable();

            $table->date('beginDate');
            $table->date('endDate');

            $table->foreign('saleId')->references('id')->on('sales');
            $table->foreign('clientId')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits');
    }
}
