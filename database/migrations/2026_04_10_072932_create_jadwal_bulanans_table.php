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
        Schema::create('jadwal_bulanans', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->enum('status', ['draft', 'disetujui', 'ditolak'])->default('draft');
            $table->text('catatan_banmus')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_bulanans');
    }
};
