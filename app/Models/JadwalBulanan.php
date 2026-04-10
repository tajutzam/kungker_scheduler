<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalBulanan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_bulanans';

    protected $fillable = [
        'bulan',
        'tahun',
        'status', // draft, disetujui, ditolak
        'catatan_banmus',
        'dibuat_oleh',
        'approved_by',
        'approved_at',
    ];

    /**
     * Aturan Bisnis: Satu jadwal dapat memiliki banyak detail jadwal
     */
    public function details(): HasMany
    {
        return $this->hasMany(JadwalDetail::class, 'jadwal_id');
    }

    /**
     * Relasi ke Admin yang menyusun jadwal
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi ke Banmus yang memberikan persetujuan
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
