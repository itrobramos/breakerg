<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("clientId")->unsigned();
            $table->decimal("total",12,2)->default(0);
            $table->date('date');
            $table->decimal("subtotal",12,2)->default(0);
            $table->string("imageUrl")->nullable();
            $table->bigInteger("userId")->unsigned();

            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
            $table->foreign('clientId')->references('id')->on('clients');
            $table->foreign('userId')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
