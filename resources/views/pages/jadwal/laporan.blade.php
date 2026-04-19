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
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                        {{ strtoupper(date('F', mktime(0, 0, 0, $m, 1))) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small font-weight-bold">TAHUN</label>
                            <input type="number" name="tahun" class="form-control form-control-sm"
                                value="{{ $tahun }}">
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
                    <div class="d-flex align-items-center justify-content-center border-bottom border-dark pb-3"
                        style="border-bottom: 3px double #000 !important;">
                        <div class="px-3" style="flex: 0 0 10%;">
                            <img src="{{ asset('assets') }}/logo.jpeg" style="width: 80px; height: auto;">
                        </div>
                        <div style="flex: 1;">
                            <h2 class="mb-0 font-weight-bold">DEWAN PERWAKILAN RAKYAT DAERAH</h2>
                            <h2 class="mb-0 font-weight-bold">KABUPATEN SIDOARJO</h2>
                            <p class="mb-0 small">Jalan Sultan Agung Nomor 39, Sidoarjo</p>
                            <p class="mb-0 small">Telepon (031) 8921955 - 8965218, faksimili (031) 8925396</p>
                            <p class="mb-0 small font-italic">Laman: www.dprd-sidoarjo.go.id, Pos-el:
                                dprd@dprd-sidoarjo.go.id</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <h4 class="font-weight-bold text-uppercase mb-1">
                        RAPAT BADAN MUSYAWARAH DPRD KABUPATEN SIDOARJO MEMBAHAS<br>
                        JADWAL RENCANA KERJA DPRD KABUPATEN SIDOARJO
                    </h4>
                    <h5 class="font-weight-bold text-uppercase">
                        BULAN {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} TAHUN {{ $tahun }}
                    </h5>
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
                                    @foreach ($listJadwal as $index => $item)
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
                    <div style="width: 350px;">
                        <p class="mb-1 text-left">Sidoarjo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p class="mb-5 text-left text-uppercase font-weight-bold">SEKRETARIS DPRD KABUPATEN SIDOARJO,</p>
                        <br><br>
                        <p class="mb-0 font-weight-bold text-decoration-underline text-uppercase">NAMA SEKRETARIS DEWAN</p>
                        <p class="mb-0">--------------</p>
                        <p class="mb-0">NIP. --------------</p>
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

        .custom-table th,
        .custom-table td {
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

            .navbar,
            .sidebar,
            .footer,
            .d-print-none,
            .settings-panel {
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
