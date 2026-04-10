@extends('templates.admin')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title text-primary">Daftar Jadwal Kunjungan Kerja</h4>
                <p class="card-description">Sistem dapat mengelola jadwal kunjungan kerja per bulan.</p>
            </div>
            @if(auth()->user()->role == 'admin')
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary btn-icon-text">
                <i class="mdi mdi-calendar-plus btn-icon-prepend"></i> Susun Jadwal Baru
            </a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="bg-light">
                    <tr>
                        <th class="font-weight-bold">Periode</th>
                        <th class="font-weight-bold">Penyusun</th>
                        <th class="font-weight-bold">Disetujui Oleh</th>
                        <th class="font-weight-bold">Status</th>
                        <th class="font-weight-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $j)
                    <tr>
                        <td class="py-3 text-left">
                            <i class="mdi mdi-calendar text-muted me-2"></i>
                            {{ date('F', mktime(0, 0, 0, $j->bulan, 1)) }} {{ $j->tahun }}
                        </td>
                        <td>{{ $j->user->name }}</td>
                        <td>
                            @if($j->approver)
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-account-check text-success mr-1"></i>
                                    <span>{{ $j->approver->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small"><em>Belum diproses</em></span>
                            @endif
                        </td>
                        <td>
                            @php
                                $class = $j->status == 'disetujui' ? 'badge-success' : ($j->status == 'ditolak' ? 'badge-danger' : 'badge-warning');
                            @endphp
                            <span class="badge {{ $class }} px-3 py-2">{{ strtoupper($j->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                {{-- Link Detail (Semua Role) --}}
                                @php
                                    $detailRoute = auth()->user()->role == 'admin'
                                                   ? route('admin.jadwal.show', $j->id)
                                                   : route('petugas.jadwal.show', $j->id);
                                @endphp
                                <a href="{{ $detailRoute }}" class="btn btn-sm btn-info text-white shadow-sm" title="Lihat Detail">
                                    <i class="mdi mdi-eye"></i>
                                </a>

                                {{-- FITUR REVISI: Hanya untuk Admin jika status DITOLAK (Flowchart) --}}
                                @if(auth()->user()->role == 'admin' && $j->status == 'ditolak')
                                <a href="{{ route('admin.jadwal.edit', $j->id) }}" class="btn btn-sm btn-warning text-white shadow-sm" title="Revisi Jadwal">
                                    <i class="mdi mdi-refresh"></i> Revisi
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <small class="text-muted">
                    Showing {{ $jadwals->firstItem() }} to {{ $jadwals->lastItem() }} of {{ $jadwals->total() }} entries
                </small>
            </div>
            <div>
                {{ $jadwals->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
