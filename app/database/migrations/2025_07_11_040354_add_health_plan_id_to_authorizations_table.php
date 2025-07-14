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
        Schema::table('authorizations', function (Blueprint $table) {
            $table->foreignId('health_plan_id')
                ->nullable() // ← ESSENCIAL!
                ->after('patient_id')
                ->constrained('health_plans')
                ->nullOnDelete();

            $table->dropColumn('plan');
        });

    }
};
