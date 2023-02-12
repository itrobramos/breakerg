<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_configuration', function (Blueprint $table) {
            $table->id();
            $table->string('RFC');
            $table->string('cerPEM');
            $table->string('keyPEM');
            $table->string('noCertificado');
            $table->string('lugarExpedicion');
            $table->string('razonSocial');
            $table->string('regimenFiscal');

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
        Schema::dropIfExists('invoice_configuration');
    }
}
