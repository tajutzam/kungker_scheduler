@extends('templates.admin')

@section('content')
<div class="row d-print-none mb-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.jadwal.laporan') }}" method="GET" class="row align-items-end">
                    <div class="col-md-3">
                        <label class="small font-weight-bold">BULAN</label>
                        <select name="bulan" class="form-control form-control-sm">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ strtoupper(date('F', mktime(0, 0, 0, $m, 1))) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold">TAHUN</label>
                        <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-dark btn-sm px-4">TAMPILKAN DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-5 printable-area">

            <div class="header-kop text-center mb-4">
                <div class="d-flex align-items-center justify-content-center border-bottom border-dark pb-3" style="border-bottom: 3px double #000 !important;">
                    {{-- Ganti src dengan asset logo daerah jika ada --}}
                    <div class="px-3" style="flex: 0 0 10%;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/bc/Garuda_Pancasila_Logo.png" style="width: 70px; height: auto;">
                    </div>
                    <div style="flex: 1;">
                        <h4 class="mb-0 font-weight-bold" style="letter-spacing: 2px;">PEMERINTAH DAERAH PROVINSI / KABUPATEN</h4>
                        <h2 class="mb-0 font-weight-bold">SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH</h2>
                        <p class="mb-0 small">Jalan Raya Pusat Perkantoran Pemerintah No. 01 Telp. (0xxx) xxxxxxx</p>
                        <p class="mb-0 small font-italic">Email: sekretariat@dprd.go.id | Website: www.dprd.go.id</p>
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <h4 class="font-weight-bold text-decoration-underline mb-1">LAPORAN JADWAL KUNJUNGAN KERJA</h4>
                <p class="text-uppercase">PERIODE BULAN {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} TAHUN {{ $tahun }}</p>
            </div>

            @forelse($details as $namaAkd => $listJadwal)
                <div class="mb-4">
                    <div class="bg-dark text-white p-2 px-3 mb-0">
                        <span class="font-weight-bold small">ALAT KELENGKAPAN DEWAN : {{ strtoupper($namaAkd) }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm custom-table">
                            <thead class="text-center bg-light">
                                <tr>
                                    <th width="5%">NO</th>
                                    <th width="35%">URAIAN KEGIATAN</th>
                                    <th width="20%">TUJUAN / LOKASI</th>
                                    <th width="15%">KATEGORI</th>
                                    <th width="25%">WAKTU PELAKSANAAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listJadwal as $index => $item)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle py-2 px-3">{{ $item->kegiatan }}</td>
                                        <td class="align-middle text-center">{{ strtoupper($item->tujuan) }}</td>
                                        <td class="align-middle text-center small font-weight-bold">
                                            {{ $item->tipe_kunjungan == 'DP' ? 'DALAM PROVINSI' : 'LUAR PROVINSI' }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ \Carbon\Carbon::parse($item->tgl_mulai)->translatedFormat('d F') }} s/d
                                            {{ \Carbon\Carbon::parse($item->tgl_selesai)->translatedFormat('d F Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="alert alert-secondary text-center">
                    Data Jadwal Untuk Periode Ini Tidak Ditemukan atau Belum Disahkan.
                </div>
            @endforelse

            <div class="mt-5 d-flex justify-content-end text-center">
                <div style="width: 300px;">
                    <p class="mb-1 text-left">Ditetapkan di: Surabaya</p>
                    <p class="mb-5 text-left text-uppercase">Pada Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <br>
                    <p class="mb-0 font-weight-bold text-decoration-underline text-uppercase">NAMA PEJABAT BERWENANG</p>
                    <p class="mb-0">NIP. 19800000 000000 0 000</p>
                </div>
            </div>

            <div class="mt-5 d-print-none border-top pt-3">
                <button onclick="window.print()" class="btn btn-outline-dark btn-icon-text shadow-sm mr-2">
                    <i class="mdi mdi-printer mr-2"></i>CETAK LAPORAN
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">KEMBALI</a>
            </div>
        </div>
    </div>
</div>

<style>
    .printable-area {
        font-family: "Times New Roman", Times, serif !important;
        color: #000 !important;
    }

    .custom-table {
        border: 1px solid #000 !important;
    }

    .custom-table th, .custom-table td {
        border: 1px solid #000 !important;
        color: #000 !important;
    }

    .custom-table th {
        font-size: 11px !important;
        letter-spacing: 0.5px;
    }

    @media print {
        @page {
            size: A4;
            margin: 1.5cm;
        }
        .navbar, .sidebar, .footer, .d-print-none, .settings-panel {
            display: none !important;
        }
        .content-wrapper {
            background: white !important;
            padding: 0 !important;
        }
        .main-panel {
            width: 100% !important;
        }
        .card {
            box-shadow: none !important;
            padding: 0 !important;
        }
        .header-kop img {
            filter: grayscale(100%);
        }
    }
</style>
@endsection
