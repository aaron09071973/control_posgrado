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
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id('id_bitacora');
            $table->unsignedBigInteger('id_usuario')->nullable(); // Foreign key to users
            $table->string('accion'); // CREAR, ACTUALIZAR, ELIMINAR
            $table->string('tabla_afectada'); // e.g. Posgrado, Alumno
            $table->string('registro_id')->nullable(); // Record ID affected
            $table->json('detalles')->nullable(); // JSON of changes or payload
            $table->timestamps();

            // Depending on the DB constraints, we can add a foreign key if users table is innodb
            // But since the DB might be existing without strict FKs, we can just leave it as unsignedBigInteger
            // Let's add it carefully
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
