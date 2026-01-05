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
        Schema::create('prispevek', function (Blueprint $table) {
            $table->id();
            $table->integer('id_uzivatel');
            // Použití unsignedBigInteger je bezpečnější pro vazbu na id() v tabulce users
            $table->unsignedBigInteger('id_uzivatel');
            $table->string('fotka');
            $table->integer('lajky')->default(0);
            $table->integer('id_komentar')->default(0);
            // Pokud odkaz neexistuje, je lepší NULL než 0
            $table->integer('id_komentar')->nullable();
            $table->timestamps(); // Přidá sloupce created_at a updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prispevek');
    }
};
