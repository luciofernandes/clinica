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
        Schema::create('authorization_modalities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authorization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modality_id')->constrained()->cascadeOnDelete(); // nova referência

            $table->string('quantity_type'); // sessões, por semana, horas/semana
            $table->integer('quantity');
            $table->decimal('unit_value', 10, 2)->nullable();
            $table->decimal('total_value', 10, 2)->nullable();
            $table->string('external_enrollment_number')->nullable();
            // Tracking
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authorization_modalities');
    }
};
