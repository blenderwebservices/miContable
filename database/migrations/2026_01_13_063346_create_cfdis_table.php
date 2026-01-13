<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cfdis', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('issuer_rfc', 13);
            $table->string('receiver_rfc', 13);
            $table->decimal('total', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->string('xml_path');
            $table->string('pdf_path')->nullable();
            $table->string('type'); // Ingreso, Egreso, Traslado, Nomina, Pago
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cfdis');
    }
};
