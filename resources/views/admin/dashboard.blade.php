@extends('templates.admin')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Selamat Datang, {{ auth()->user()->name }}</h3>
                <h6 class="font-weight-normal mb-0">Sistem Informasi Penjadwalan Kunjungan Kerja DPRD</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <p class="mb-4">Total AKD Terdaftar</p>
                <h3 class="mb-2">{{ $stats['total_akd'] }}</h3>
                <p>Alat Kelengkapan Dewan</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-success text-white">
            <div class="card-body">
                <p class="mb-4">Jadwal Final (Bulan Ini)</p>
                <h3 class="mb-2">{{ $stats['jadwal_final'] }}</h3>
                <p>Status: Terkunci</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <p class="mb-4">Menunggu Persetujuan</p>
                <h3 class="mb-2">{{ $stats['jadwal_draft'] }}</h3>
                <p>Status: Draft/Proses</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <p class="mb-4">Jadwal Ditolak</p>
                <h3 class="mb-2">{{ $stats['jadwal_ditolak'] }}</h3>
                <p>Memerlukan Revisi</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <p class="card-title">Agenda Kunker Terdekat</p>
                    <a href="{{ route('admin.jadwal.laporan') }}" class="text-info small">Lihat Laporan</a>
                </div>
                <p class="font-weight-500">Daftar keberangkatan kunker yang sudah disetujui dalam waktu dekat.</p>
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>AKD</th>
                                <th>Tujuan</th>
                                <th>Tanggal Mulai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agendaMendatang as $agenda)
                            <tr>
                                <td class="font-weight-bold text-primary">{{ $agenda->akd->nama_akd }}</td>
                                <td>{{ $agenda->tujuan }}</td>
                                <td>{{ \Carbon\Carbon::parse($agenda->tgl_mulai)->format('d M Y') }}</td>
                                <td><label class="badge badge-success">Final</label></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">Tidak ada agenda dalam waktu dekat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Proporsi Kunjungan Kerja</p>
                <p class="text-muted">Perbandingan Kunker Dalam Provinsi vs Luar Provinsi</p>
                <canvas id="kunkerChart"></canvas>
                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Dalam Provinsi (DP)</span>
                        <span class="font-weight-bold">{{ $kunkerDP }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Luar Provinsi (LP)</span>
                        <span class="font-weight-bold">{{ $kunkerLP }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('kunkerChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Dalam Provinsi', 'Luar Provinsi'],
            datasets: [{
                data: [{{ $kunkerDP }}, {{ $kunkerLP }}],
                backgroundColor: ['#4B49AC', '#FFC100'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: { display: false }
        }
    });
</script>
@endpush
