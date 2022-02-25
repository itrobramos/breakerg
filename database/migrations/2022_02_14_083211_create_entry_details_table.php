<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("entryId")->unsigned();
            $table->bigInteger("productId")->unsigned();
            $table->integer('quantity')->default(1);
            $table->decimal('unitPrice',12,2);
            
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
            
            $table->foreign('entryId')->references('id')->on('entries');
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
        Schema::dropIfExists('entry_details');
    }
}
