@extends('templates.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title text-primary">Data Alat Kelengkapan Dewan (AKD)</h4>
                            <p class="card-description">
                                Pengaturan kategori AKD menentukan kuota otomatis pada penjadwalan.
                            </p>
                        </div>
                        <a href="{{ route('admin.akd.create') }}" class="btn btn-primary btn-icon-text shadow-sm">
                            <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah AKD
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-bold text-center" width="5%"> No </th>
                                    <th class="font-weight-bold"> Nama AKD </th>
                                    <th class="font-weight-bold text-center"> Kategori </th>
                                    <th class="font-weight-bold"> Deskripsi </th>
                                    <th class="text-center font-weight-bold"> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($akds as $index => $akd)
                                    <tr>
                                        <td class="text-center"> {{ $index + 1 }} </td>
                                        <td class="text-dark font-weight-bold"> {{ $akd->nama_akd }} </td>
                                        <td class="text-center">
                                            {{-- Penyesuaian Berdasarkan Flowchart --}}
                                            @if($akd->kategori == 'komisi')
                                                <label class="badge badge-info">KOMISI</label>
                                                <br><small class="text-muted">Kuota: 2 DP & 2 LP</small>
                                            @else
                                                <label class="badge badge-dark">NON-KOMISI</label>
                                                <br><small class="text-muted">Kuota: 1 DP & 1 LP</small>
                                            @endif
                                        </td>
                                        <td> {{ Str::limit($akd->deskripsi, 40) ?? '-' }} </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center" style="gap: 8px;">
                                                <a href="{{ route('admin.akd.edit', $akd->id) }}"
                                                    class="btn btn-sm btn-warning text-white shadow-sm" title="Edit Data">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>

                                                <form action="{{ route('admin.akd.destroy', $akd->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger btn-delete shadow-sm"
                                                        title="Hapus Data">
                                                        <i class="mdi mdi-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="mdi mdi-alert-circle-outline mdi-36px text-muted"></i>
                                            <p class="mt-2">Data AKD belum tersedia.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
