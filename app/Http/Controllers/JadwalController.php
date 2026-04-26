<?php

namespace App\Http\Controllers;

use App\Models\Akd;
use App\Models\JadwalBulanan;
use App\Models\JadwalDetail;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalBulanan::with(['user', 'approver'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        return view('pages.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $akds = Akd::all();
        $kegiatans = Kegiatan::all();
        return view('pages.jadwal.create', compact('akds', 'kegiatans'));
    }

    public function edit($id)
    {
        $jadwal = JadwalBulanan::with('details.akd')->findOrFail($id);

        // Sesuai Flowchart: Hanya jadwal 'ditolak' yang bisa direvisi oleh Admin
        if ($jadwal->status !== 'ditolak') {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Hanya jadwal dengan status ditolak yang dapat direvisi.');
        }

        $akds = Akd::all();
        return view('pages.jadwal.edit', compact('jadwal', 'akds'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $error = $this->checkBusinessRules($request);
        if ($error) return back()->with('error', $error)->withInput();

        DB::transaction(function () use ($request) {
            $jadwal = JadwalBulanan::create([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'dibuat_oleh' => auth()->id(),
                'status' => 'draft'
            ]);

            foreach ($request->details as $detail) {
                // Pastikan array ini memiliki key yang sesuai dengan kolom tabel jadwal_details
                $jadwal->details()->create([
                    'akd_id'      => $detail['akd_id'] ?: null, // Simpan null jika kosong
                    'kegiatan_id' => $detail['kegiatan_id'],
                    'tipe_kunjungan' => $detail['tipe_kunjungan'],
                    'tujuan'      => $detail['tujuan'],
                    'tgl_mulai'   => $detail['tgl_mulai'],
                    'tgl_selesai' => $detail['tgl_selesai'],
                ]);
            }
        });

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dibuat secara otomatis.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalBulanan::findOrFail($id);
        $this->validateRequest($request);

        // Aturan Bisnis: Cek Kuota & Bentrok (Kecuali ID jadwal ini sendiri)
        $error = $this->checkBusinessRules($request, $id);
        if ($error) return back()->with('error', $error)->withInput();

        DB::transaction(function () use ($request, $jadwal) {
            // Reset status ke Draft dan hapus catatan lama
            $jadwal->update([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => 'draft',
                'catatan_banmus' => null
            ]);

            // Hapus detail lama dan masukkan hasil revisi
            $jadwal->details()->delete();
            foreach ($request->details as $detail) {
                $jadwal->details()->create($detail);
            }
        });

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil direvisi dan diajukan kembali.');
    }

    public function show($id)
    {
        $jadwal = JadwalBulanan::with(['details.akd', 'user', 'approver', 'details.kegiatanDetail'])->findOrFail($id);
        return view('pages.jadwal.show', compact('jadwal'));
    }

    public function approve(Request $request, $id)
    {
        $jadwal = JadwalBulanan::findOrFail($id);

        if (auth()->user()->role !== 'bamus') {
            abort(403);
        }

        $status = $request->status; // 'disetujui' atau 'ditolak'

        $jadwal->update([
            'status' => $status,
            'catatan_banmus' => $request->catatan,
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        $msg = ($status == 'disetujui') ? 'Jadwal telah FINAL dan Terkunci.' : 'Jadwal ditolak. Admin harus revisi.';
        return redirect()->route('petugas.jadwal.index')->with('success', $msg);
    }

    // --- LOGIKA HELPER ---

    private function validateRequest($request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
            'details' => 'required|array|min:1',
            'details.*.akd_id' => 'nullable|exists:akds,id', // Diubah jadi nullable
            'details.*.kegiatan_id' => 'required|exists:kegiatans,id', // Wajib ada dari master
            'details.*.tipe_kunjungan' => 'required|in:DP,LP',
            'details.*.tgl_mulai' => 'required|date',
            'details.*.tujuan' => 'required|string|max:255',
            'details.*.tgl_selesai' => 'required|date|after_or_equal:details.*.tgl_mulai',
        ]);
    }

    private function checkBusinessRules($request, $excludeJadwalId = null)
    {
        $inputDetails = $request->details;
        $quotaInRequest = [];

        foreach ($inputDetails as $detail) {
            // Jika Non-AKD, lewati pengecekan kuota kunker
            if (empty($detail['akd_id'])) {
                continue;
            }

            $akd = Akd::findOrFail($detail['akd_id']);
            $start = $detail['tgl_mulai'];
            $end = $detail['tgl_selesai'];
            $tipe = $detail['tipe_kunjungan'];

            // 1. Validasi Durasi (DP=2 hari, LP=3 hari)
            $durasi = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
            $wajib = ($tipe == 'DP') ? 2 : 3;
            if ($durasi != $wajib) {
                return "Durasi {$akd->nama_akd} ({$tipe}) harus {$wajib} hari.";
            }

            $key = $akd->id . '_' . $tipe;
            $quotaInRequest[$key] = ($quotaInRequest[$key] ?? 0) + 1;


            $bentrok = JadwalDetail::where('akd_id', $akd->id)
                ->where(function ($query) use ($start, $end) {
                    $query->where(function ($q) use ($start, $end) {
                        $q->whereBetween('tgl_mulai', [$start, $end])
                            ->orWhereBetween('tgl_selesai', [$start, $end]);
                    });
                })
                ->whereHas('jadwalBulanan', function ($q) use ($excludeJadwalId) {
                    $q->where('status', 'disetujui');
                    if ($excludeJadwalId) $q->where('id', '!=', $excludeJadwalId);
                })->first();

            if ($bentrok) {
                return "Bentrok! {$akd->nama_akd} memiliki jadwal FINAL pada {$bentrok->tgl_mulai} s/d {$bentrok->tgl_selesai}.";
            }
        }
        return null;
    }


    public function laporan(Request $request)
    {
        $bulan = (int) ($request->bulan ?? date('m'));
        $tahun = (int) ($request->tahun ?? date('Y'));

        $details = JadwalDetail::with(['akd', 'jadwalBulanan.approver'])
            ->whereHas('jadwalBulanan', function ($q) use ($bulan, $tahun) {
                $q->where('status', 'disetujui')
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun);
            })
            ->get()
            ->groupBy('akd.nama_akd');

        return view('pages.jadwal.laporan', compact('details', 'bulan', 'tahun'));
    }
}
