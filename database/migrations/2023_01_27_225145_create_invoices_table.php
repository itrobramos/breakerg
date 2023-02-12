<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('saleId')->unsigned();
            $table->string('xml')->nullable;
            $table->string('qr')->nullable;
            $table->string('pdf')->nullable;
            $table->string('cadenaOriginal')->nullable;
            $table->boolean('status')->nullable;
            $table->string('rfcReceptor')->nullable();
            $table->string('serie')->nullable();
            $table->string('folio')->nullable();
            $table->dateTime('fecha')->nullable();
            $table->string('formaPago')->nullable();
            $table->string('noCertificado')->nullable();
            $table->string('condicionesDePago')->nullable();
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('total',12,2)->nullable();
            $table->decimal('descuento', 12, 2)->nullable();
            $table->string('moneda')->nullable();
            $table->decimal('tipoCambio',12,2)->nullable();
            $table->string('tipoDeComprobante')->nullable();
            $table->string('metodoPago')->nullable();
            $table->string('lugarExpedicion')->nullable();

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
        Schema::dropIfExists('invoices');
    }
}
