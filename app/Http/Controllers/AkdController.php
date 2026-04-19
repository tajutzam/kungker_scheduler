<?php

namespace App\Http\Controllers;

use App\Models\Akd; // Pastikan model Akd sudah dibuat
use Illuminate\Http\Request;

class AkdController extends Controller
{
    /**
     * Menampilkan daftar AKD.
     * Implementasi KF-01: Sistem dapat mengelola data AKD[cite: 5].
     */
    public function index()
    {
        $akds = Akd::all();
        return view('pages.akd.index', compact('akds'));
    }

    /**
     * Menampilkan form tambah AKD.
     */
    public function create()
    {
        return view('pages.akd.create');
    }

    /**
     * Menyimpan data AKD baru ke database.
     * Memastikan data tersimpan di database terpusat.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_akd' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required'
        ]);

        Akd::create($request->all());

        return redirect()->route('admin.akd.index')
            ->with('success', 'Data AKD berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail AKD jika diperlukan.
     */
    public function show(string $id)
    {
        $akd = Akd::findOrFail($id);
        return view('pages.akd.show', compact('akd'));
    }

    /**
     * Menampilkan form edit AKD.
     */
    public function edit(string $id)
    {
        $akd = Akd::findOrFail($id);
        return view('pages.akd.edit', compact('akd'));
    }

    /**
     * Memperbarui data AKD.
     * Menjaga konsistensi dan keakuratan data.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_akd' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',

        ]);

        $akd = Akd::findOrFail($id);
        $akd->update($request->all());

        return redirect()->route('admin.akd.index')
            ->with('success', 'Data AKD berhasil diperbarui.');
    }

    /**
     * Menghapus data AKD.
     */
    public function destroy(string $id)
    {
        try {
            $akd = Akd::findOrFail($id);
            $akd->delete();

            return redirect()->route('admin.akd.index')
                ->with('success', 'Data AKD berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.akd.index')
                ->with('error', 'Gagal menghapus data. AKD mungkin masih terikat dengan jadwal.');
        }
    }
}
