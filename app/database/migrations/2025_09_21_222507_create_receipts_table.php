<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('mes_ref')->nullable();
            $table->string('ano_ref')->nullable();
            $table->string('descricao')->nullable();
            $table->date('data')->nullable();
            $table->date('data_pagorec')->nullable();
            $table->string('operacao')->nullable();
            $table->string('deb_cred')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->decimal('valor_pagorec', 10, 2)->nullable();
            $table->string('num_recibo')->nullable();
            $table->string('pago')->nullable();
            $table->string('forma_pagamento')->nullable();
            $table->string('cliente_nome')->nullable();
            $table->string('cliente_cpf')->nullable();
            $table->string('fornecedor_nome')->nullable();
            $table->string('fornecedor_numdoc')->nullable();
            $table->string('nome_professor')->nullable();
            $table->text('obs')->nullable();
            // Dados auxiliares para facilitar análises
            $table->integer('mes')->nullable();
            $table->integer('ano')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
