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
            $table->date('authorization_date')->nullable()->after('authorization_number');
            $table->date('authorization_expiration_date')->nullable()->after('authorization_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            //
        });
    }
};
