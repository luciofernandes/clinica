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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modality_id')->constrained()->onDelete('cascade');
            $table->string('professor_nome'); // ou foreignId se tiver relação
            $table->integer('qtd_matriculas');
            $table->integer('qtd_sessoes');
            $table->decimal('valor_comissao', 10, 2);
            $table->string('mes');
            $table->string('ano');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
