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
        Schema::create('simop_api_logs_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint')->nullable();       // Ex: /api/v1/mkesh/callback
            $table->string('method', 10)->nullable();     // POST / GET
            $table->text('headers')->nullable();          // Headers da request
            $table->longText('payload')->nullable();      // Corpo XML ou JSON recebido
            $table->longText('response')->nullable();     // Resposta que a API devolveu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simop_api_logs_transactions');
    }
};
