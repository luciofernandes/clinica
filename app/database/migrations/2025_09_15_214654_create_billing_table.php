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
        Schema::create('billings', function (Blueprint $table) {
                $table->id();
                $table->string('nome')->nullable(); // Nome do paciente
                $table->string('cpf')->nullable();
                $table->date('data_venc')->nullable();
                $table->date('data_pago')->nullable();
                $table->date('data_matricula')->nullable();
                $table->decimal('valor_mes', 10, 2)->nullable();
                $table->decimal('valor_desconto', 10, 2)->nullable();
                $table->decimal('valor_com_desconto', 10, 2)->nullable();
                $table->decimal('valor_pago', 10, 2)->nullable();
                $table->string('modalidade')->nullable();
                $table->string('tipo_matricula')->nullable();
                $table->string('forma_mensalidade')->nullable();
                $table->string('num_recibo')->nullable();
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
        Schema::dropIfExists('billings');
    }
};
