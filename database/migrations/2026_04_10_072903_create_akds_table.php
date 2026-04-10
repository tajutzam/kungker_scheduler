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
        Schema::create('akds', function (Blueprint $table) {
            $table->id();
            $table->string('nama_akd');
            $table->text('deskripsi')->nullable();
            $table->enum('kategori', ['komisi', 'non-komisi'])->default('komisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akds');
    }
};
