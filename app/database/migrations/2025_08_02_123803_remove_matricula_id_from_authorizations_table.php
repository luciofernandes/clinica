<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('authorizations', function (Blueprint $table) {
            $table->dropColumn('external_enrollment_link');
        });
    }

    public function down()
    {
        Schema::table('authorizations', function (Blueprint $table) {
            $table->string('external_enrollment_link')->nullable();
        });
    }

};
