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
        Schema::table('authorization_modalities', function (Blueprint $table) {
            $table->date('last_session_date')->nullable()->after('authorization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authorization_modalities', function (Blueprint $table) {
            $table->dropColumn('last_session_date');
        });
    }
};
