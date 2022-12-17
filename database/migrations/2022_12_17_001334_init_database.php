<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitDatabase extends Migration
{
    public function up()
    {
        Schema::dropIfExists('division');

        Schema::create('division', function (Blueprint $table) {
            $table->bigIncrements('divi_IdDivision');
            $table->bigInteger('disu_IdDivisionSuperior');

            $table->string('divi_Nombre', 45)->unique();

            $table->integer('divi_Nivel');
            $table->integer('divi_Colaborador_Cantidad');

            $table->string('divi_Embajador_Nombre');
        });
    }


    public function down()
    {
         Schema::dropIfExists('division');
    }
}
