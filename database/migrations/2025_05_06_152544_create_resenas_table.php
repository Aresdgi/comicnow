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
        Schema::create('resenas', function (Blueprint $table) {
            $table->id('id_resena');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_comic');
            $table->integer('valoracion');
            $table->text('comentario');
            $table->dateTime('fecha');
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
        Schema::dropIfExists('resenas');
    }
};
