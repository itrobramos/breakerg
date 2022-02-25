<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("saleId")->unsigned();
            $table->bigInteger("productId")->unsigned();
            $table->decimal("quantity",12,2)->default(0);
            $table->decimal("price",12,2)->default(0);
            
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
            $table->foreign('saleId')->references('id')->on('sales');
            $table->foreign('productId')->references('id')->on('products');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_details');
    }
}
