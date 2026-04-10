<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akd;
use App\Models\JadwalBulanan;
use App\Models\JadwalDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanSekarang = date('m');
        $tahunSekarang = date('Y');

        // Statistik Status
        $stats = [
            'total_akd'     => Akd::count(),
            'jadwal_final'  => JadwalBulanan::where('status', 'disetujui')->where('bulan', $bulanSekarang)->count(),
            'jadwal_draft'  => JadwalBulanan::where('status', 'draft')->count(),
            'jadwal_ditolak'=> JadwalBulanan::where('status', 'ditolak')->count(),
        ];

        // Agenda Mendatang (Agenda yang akan mulai dalam 7 hari ke depan)
        $agendaMendatang = JadwalDetail::with('akd')
            ->where('tgl_mulai', '>=', date('Y-m-d'))
            ->whereHas('jadwalBulanan', function($q) {
                $q->where('status', 'disetujui');
            })
            ->orderBy('tgl_mulai', 'asc')
            ->limit(5)
            ->get();

        // Data untuk Grafik Kuota (Contoh: Menghitung persentase kunker DP vs LP)
        $kunkerDP = JadwalDetail::where('tipe_kunjungan', 'DP')->count();
        $kunkerLP = JadwalDetail::where('tipe_kunjungan', 'LP')->count();

        return view('admin.dashboard', compact('stats', 'agendaMendatang', 'kunkerDP', 'kunkerLP'));
    }
}
