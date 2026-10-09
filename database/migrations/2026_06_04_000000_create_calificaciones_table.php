<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('Calificaciones');
        Schema::create('Calificaciones', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->integer('id_alumno');
            $table->integer('id_asignatura');
            $table->string('calificacion', 10)->nullable();
            
            $table->foreign('id_alumno')->references('id_alumno')->on('Alumnos')->onDelete('cascade');
            $table->foreign('id_asignatura')->references('id_asignatura')->on('Asignaturas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Calificaciones');
    }
};
