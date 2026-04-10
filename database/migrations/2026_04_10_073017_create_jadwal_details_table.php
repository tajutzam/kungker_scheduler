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
        Schema::create('jadwal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_bulanans')->onDelete('cascade');
            $table->foreignId('akd_id')->constrained('akds');
            $table->string('tujuan');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->text('kegiatan');
            $table->enum('tipe_kunjungan', ['DP', 'LP']);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('jadwal_details');
    }
};
