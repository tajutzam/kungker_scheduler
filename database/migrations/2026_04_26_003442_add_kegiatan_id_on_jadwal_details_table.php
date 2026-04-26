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
        Schema::table('jadwal_details', function (Blueprint $table) {
            $table->foreignId('kegiatan_id')
                  ->nullable()
                  ->constrained('kegiatans')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_details', function (Blueprint $table) {
            $table->dropForeign(['kegiatan_id']);
            $table->dropColumn('kegiatan_id');
        });
    }
};
