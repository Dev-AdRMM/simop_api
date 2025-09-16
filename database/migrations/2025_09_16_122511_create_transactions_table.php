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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('wallet'); // Mpesa, Emola, Mkesh
            $table->string('transaction_id')->unique(); // ID da transação no provedor
            $table->string('msisdn'); // número do cliente
            $table->decimal('amount', 12, 2); // valor
            $table->string('status')->default('pending'); // pending, success, failed
            $table->text('provider_response')->nullable(); // XML/JSON do provedor

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
