<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Cambia el campo stock por categoria en la tabla comics
     */
    public function up(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropColumn('stock');
            $table->string('categoria')->after('precio');
        });
    }

    /**
     * Reverse the migrations.
     * Revierte el cambio de categoria a stock
     */
    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropColumn('categoria');
            $table->integer('stock')->after('precio');
        });
    }
};
