<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('authorization_modalities', function (Blueprint $table) {
            $table->string('matricula_id')->nullable()->after('id'); // ou ajuste o tipo conforme necessário
        });
    }

    public function down()
    {
        Schema::table('authorization_modalities', function (Blueprint $table) {
            $table->dropColumn('matricula_id');
        });
    }

};
