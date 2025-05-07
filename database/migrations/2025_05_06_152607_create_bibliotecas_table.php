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
        Schema::create('bibliotecas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_comic');
            $table->decimal('progreso_lectura', 5, 2);
            $table->text('ultimo_marcador');
            $table->primary(['id_usuario', 'id_comic']);
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('id_comic')->references('id_comic')->on('comics');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibliotecas');
    }
};
