<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalDetail extends Model
{
    use HasFactory;

    protected $table = 'jadwal_details';

    protected $fillable = [
        'jadwal_id',
        'akd_id',
        'tujuan',
        'tgl_mulai',
        'tgl_selesai',
        'kegiatan',
        'kegiatan_id',
        'tipe_kunjungan'
    ];


    public function kegiatanDetail(){
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    /**
     * Kembali ke Header Jadwal Bulanan
     */
    public function jadwalBulanan(): BelongsTo
    {
        return $this->belongsTo(JadwalBulanan::class, 'jadwal_id');
    }

    /**
     * Aturan Bisnis: Mengidentifikasi AKD yang melakukan kunjungan [cite: 5, 9]
     */
    public function akd(): BelongsTo
    {
        return $this->belongsTo(Akd::class, 'akd_id');
    }
}
