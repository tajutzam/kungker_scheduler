<?php

namespace App\Http\Controllers;

use App\Models\Akd;
use App\Models\JadwalBulanan;
use App\Models\JadwalDetail;
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
        return view('pages.jadwal.create', compact('akds'));
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

        // Aturan Bisnis: Cek Kuota & Bentrok
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
                $jadwal->details()->create($detail);
            }
        });

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil disusun (Status: Draft).');
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
        $jadwal = JadwalBulanan::with(['details.akd', 'user', 'approver'])->findOrFail($id);
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
            'details.*.akd_id' => 'required|exists:akds,id',
            'details.*.tipe_kunjungan' => 'required|in:DP,LP',
            'details.*.tgl_mulai' => 'required|date',
            'details.*.kegiatan' => 'required|string',
            'details.*.tgl_selesai' => 'required|date|after_or_equal:details.*.tgl_mulai',
        ]);
    }

    private function checkBusinessRules($request, $excludeJadwalId = null)
    {
        $inputDetails = $request->details;
        $quotaInRequest = [];

        foreach ($inputDetails as $detail) {
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

            // 2. Validasi Kuota (Komisi=2, Non=1)
            $key = $akd->id . '_' . $tipe;
            $quotaInRequest[$key] = ($quotaInRequest[$key] ?? 0) + 1;

            // Ambil kuota yang sudah terpakai di database untuk periode ini
            $alreadyUsed = JadwalDetail::where('akd_id', $akd->id)
                ->where('tipe_kunjungan', $tipe)
                ->whereHas('jadwalBulanan', function ($q) use ($request, $excludeJadwalId) {
                    $q->where('bulan', $request->bulan)
                        ->where('tahun', $request->tahun)
                        ->where('status', '!=', 'ditolak');
                    if ($excludeJadwalId) $q->where('id', '!=', $excludeJadwalId);
                })->count();

            $limit = ($akd->kategori == 'komisi') ? 2 : 1;
            if (($alreadyUsed + $quotaInRequest[$key] - 1) >= $limit) {
                return "Kuota {$tipe} untuk {$akd->nama_akd} sudah habis (Maks: {$limit}).";
            }

            // 3. Validasi Bentrok Tanggal (Overlap)
            $bentrok = JadwalDetail::where('akd_id', $akd->id)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('tgl_mulai', [$start, $end])
                        ->orWhereBetween('tgl_selesai', [$start, $end])
                        ->orWhere(function ($q) use ($start, $end) {
                            $q->where('tgl_mulai', '<=', $start)->where('tgl_selesai', '>=', $end);
                        });
                })
                ->whereHas('jadwalBulanan', function ($q) use ($excludeJadwalId) {
                    $q->where('status', 'disetujui'); // Bentrok jika menabrak jadwal FINAL
                    if ($excludeJadwalId) $q->where('id', '!=', $excludeJadwalId);
                })
                ->first();

            if ($bentrok) {
                return "Bentrok! {$akd->nama_akd} memiliki jadwal FINAL pada {$bentrok->tgl_mulai} s/d {$bentrok->tgl_selesai}.";
            }
        }
        return null;
    }


    public function laporan(Request $request)
    {
        // Filter pencarian (opsional)
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

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
