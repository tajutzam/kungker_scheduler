@extends('templates.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title text-primary mb-0">
                        <i class="mdi mdi-information-variant mr-2"></i>Detail Jadwal: {{ date('F', mktime(0, 0, 0, $jadwal->bulan, 1)) }} {{ $jadwal->tahun }}
                    </h4>
                    <span class="badge {{ $jadwal->status == 'disetujui' ? 'badge-success' : ($jadwal->status == 'ditolak' ? 'badge-danger' : 'badge-warning') }} px-3 py-2">
                        {{ strtoupper($jadwal->status) }}
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered mb-4">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>AKD</th>
                                <th>Tujuan</th>
                                <th>Waktu Kunjungan</th>
                                <th>Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwal->details as $d)
                            <tr>
                                <td class="font-weight-bold">{{ $d->akd->nama_akd }}</td>
                                <td>{{ $d->tujuan }}</td>
                                <td>
                                    <i class="mdi mdi-calendar-range text-muted mr-1"></i>
                                    {{ \Carbon\Carbon::parse($d->tgl_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($d->tgl_selesai)->format('d M Y') }}
                                </td>
                                <td>{{ $d->kegiatan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                {{-- Panel Persetujuan Banmus (KF-05 & KNF-02) --}}
                @if(auth()->user()->role == 'bamus' && $jadwal->status == 'draft')
                <div class="card bg-light border-info mt-4">
                    <div class="card-body">
                        <h5 class="text-info font-weight-bold mb-3">
                            <i class="mdi mdi-checkbox-marked-circle-outline mr-2"></i>Panel Persetujuan Banmus
                        </h5>
                        <form action="{{ route('petugas.jadwal.approve', $jadwal->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold">Catatan / Alasan (Opsional)</label>
                                <textarea name="catatan" class="form-control" rows="3" placeholder="Masukkan catatan jika ada..."></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="status" value="disetujui" class="btn btn-success btn-icon-text">
                                    <i class="mdi mdi-check btn-icon-prepend"></i> Setujui Jadwal
                                </button>
                                <button type="submit" name="status" value="ditolak" class="btn btn-danger btn-icon-text">
                                    <i class="mdi mdi-close btn-icon-prepend"></i> Tolak Jadwal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Status Final & Riwayat Persetujuan (KF-06 & KNF-03) --}}
                @if($jadwal->status != 'draft')
                <div class="alert {{ $jadwal->status == 'disetujui' ? 'alert-success' : 'alert-danger' }} mt-4">
                    <h6 class="font-weight-bold">Riwayat Persetujuan:</h6>
                    <p class="mb-1"><strong>Status Akhir:</strong> {{ strtoupper($jadwal->status) }}</p>
                    <p class="mb-1"><strong>Catatan:</strong> {{ $jadwal->catatan_banmus ?? 'Tidak ada catatan' }}</p>
                    @if($jadwal->approved_at)
                        <small class="text-muted">Diproses pada: {{ \Carbon\Carbon::parse($jadwal->approved_at)->format('d F Y H:i') }}</small>
                    @endif
                </div>
                @endif

                <div class="mt-4">
                    @php
                        $backRoute = auth()->user()->role == 'admin' ? route('admin.jadwal.index') : route('petugas.jadwal.index');
                    @endphp
                    <a href="{{ $backRoute }}" class="btn btn-light border">
                        <i class="mdi mdi-arrow-left mr-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
