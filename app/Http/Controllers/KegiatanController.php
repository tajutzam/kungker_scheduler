<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Menampilkan daftar Kegiatan.
     */
    public function index()
    {
        $kegiatans = Kegiatan::all();
        return view('pages.kegiatan.index', compact('kegiatans'));
    }

    /**
     * Menampilkan form tambah Kegiatan.
     */
    public function create()
    {
        return view('pages.kegiatan.create');
    }

    /**
     * Menyimpan data Kegiatan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Kegiatan::create($request->only('name'));

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Data Kegiatan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Kegiatan.
     */
    public function edit(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('pages.kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Memperbarui data Kegiatan.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update($request->only('name'));

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Data Kegiatan berhasil diperbarui.');
    }

    /**
     * Menghapus data Kegiatan.
     */
    public function destroy(string $id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail($id);
            $kegiatan->delete();

            return redirect()->route('admin.kegiatan.index')
                ->with('success', 'Data Kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kegiatan.index')
                ->with('error', 'Gagal menghapus data. Kegiatan mungkin masih terhubung dengan data lain.');
        }
    }
}
