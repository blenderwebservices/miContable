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
        Schema::table('cfdis', function (Blueprint $table) {
            $table->dateTime('emission_date')->nullable()->after('uuid');
            $table->string('payment_method')->nullable()->after('type');
            $table->string('payment_form')->nullable()->after('payment_method');
            $table->string('currency', 3)->default('MXN')->after('payment_form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cfdis', function (Blueprint $table) {
            $table->dropColumn(['emission_date', 'payment_method', 'payment_form', 'currency']);
        });
    }
};
