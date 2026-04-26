@extends('templates.admin')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title text-warning font-weight-bold">
                            <i class="mdi mdi-refresh mr-2"></i>Revisi Jadwal Kunker
                        </h4>
                        <p class="card-description">
                            Silakan perbaiki jadwal yang ditolak. <br>
                            <span class="text-danger font-weight-bold">Alasan Penolakan:</span> {{ $jadwal->catatan_banmus ?? '-' }}
                        </p>
                    </div>
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-light btn-icon-text border">
                        <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" id="formJadwal">
                    @csrf
                    @method('PUT')

                    <div class="bg-light p-4 rounded mb-4 shadow-sm border-left border-warning">
                        <h6 class="font-weight-bold mb-3 text-warning">
                            <i class="mdi mdi-clock-outline mr-1"></i> 1. Informasi Periode
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mb-0">
                                    <label class="col-sm-3 col-form-label font-weight-bold">Bulan</label>
                                    <div class="col-sm-9">
                                        <select name="bulan" class="form-control" required>
                                            @foreach(range(1, 12) as $m)
                                                <option value="{{ $m }}" {{ $jadwal->bulan == $m ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row mb-0">
                                    <label class="col-sm-3 col-form-label font-weight-bold">Tahun</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="tahun" class="form-control" value="{{ $jadwal->tahun }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-weight-bold text-primary mb-0">
                            <i class="mdi mdi-vector-arrange-below mr-1"></i> 2. Revisi Detail Jadwal
                        </h6>
                        <button type="button" class="btn btn-secondary btn-sm shadow-sm" id="addDetail">
                            <i class="mdi mdi-plus"></i> Tambah Baris Baru
                        </button>
                    </div>

                    <div class="table-responsive border rounded shadow-sm">
                        <table class="table table-hover mb-0" id="detailTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="20%">Nama AKD</th>
                                    <th width="15%">Tipe</th>
                                    <th>Tujuan</th>
                                    <th>Kegiatan</th>
                                    <th width="12%">Tgl Mulai</th>
                                    <th width="12%">Tgl Selesai</th>
                                    <th width="50px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwal->details as $index => $detail)
                                <tr class="detail-row">
                                    <td>
                                        <select name="details[{{ $index }}][akd_id]" class="form-control akd-select">
                                            <option value="">-- Non-AKD / Umum --</option>
                                            @foreach($akds as $akd)
                                                <option value="{{ $akd->id }}" data-kategori="{{ $akd->kategori }}"
                                                    {{ $detail->akd_id == $akd->id ? 'selected' : '' }}>
                                                    {{ $akd->nama_akd }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="details[{{ $index }}][tipe_kunjungan]" class="form-control tipe-select" required>
                                            <option value="DP" {{ $detail->tipe_kunjungan == 'DP' ? 'selected' : '' }}>Dalam Prov</option>
                                            <option value="LP" {{ $detail->tipe_kunjungan == 'LP' ? 'selected' : '' }}>Luar Prov</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="details[{{ $index }}][tujuan]" class="form-control" value="{{ $detail->tujuan }}" required></td>
                                    <td>
                                        <select name="details[{{ $index }}][kegiatan_id]" class="form-control" required>
                                            <option value="">-- Pilih Kegiatan --</option>
                                            @foreach($kegiatans as $keg)
                                                <option value="{{ $keg->id }}" {{ $detail->kegiatan_id == $keg->id ? 'selected' : '' }}>
                                                    {{ $keg->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="date" name="details[{{ $index }}][tgl_mulai]" class="form-control tgl-mulai" value="{{ $detail->tgl_mulai }}" required></td>
                                    <td><input type="date" name="details[{{ $index }}][tgl_selesai]" class="form-control tgl-selesai" value="{{ $detail->tgl_selesai }}" readonly required></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-secondary remove-row"><i class="mdi mdi-trash-can"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 d-flex justify-content-end" style="gap: 15px;">
                        <button type="submit" class="btn btn-secondary btn-lg px-5 shadow">
                            <i class="mdi mdi-send mr-2"></i>Simpan Revisi & Ajukan Kembali
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let detailIdx = {{ count($jadwal->details) }};

    function formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }

    function calculateEndDate(row) {
        const tglMulai = row.find('.tgl-mulai').val();
        const tipe = row.find('.tipe-select').val();

        if (tglMulai && tipe) {
            let start = new Date(tglMulai);
            let days = (tipe === 'DP') ? 1 : 2;
            let end = new Date(start);
            end.setDate(start.getDate() + days);
            row.find('.tgl-selesai').val(formatDate(end));
        }
    }

    $(document).on('change', '.tgl-mulai, .tipe-select', function() {
        calculateEndDate($(this).closest('tr'));
    });

    $('#addDetail').on('click', function() {
        let newRow = `
            <tr class="detail-row">
                <td>
                    <select name="details[${detailIdx}][akd_id]" class="form-control akd-select">
                        <option value="">-- Non-AKD / Umum --</option>
                        @foreach($akds as $akd)
                            <option value="{{ $akd->id }}" data-kategori="{{ $akd->kategori }}">{{ $akd->nama_akd }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="details[${detailIdx}][tipe_kunjungan]" class="form-control tipe-select" required>
                        <option value="DP">Dalam Prov</option>
                        <option value="LP">Luar Prov</option>
                    </select>
                </td>
                <td><input type="text" name="details[${detailIdx}][tujuan]" class="form-control" required></td>
                <td>
                    <select name="details[${detailIdx}][kegiatan_id]" class="form-control" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach($kegiatans as $keg)
                            <option value="{{ $keg->id }}">{{ $keg->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="date" name="details[${detailIdx}][tgl_mulai]" class="form-control tgl-mulai" required></td>
                <td><input type="date" name="details[${detailIdx}][tgl_selesai]" class="form-control tgl-selesai" readonly required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-secondary remove-row"><i class="mdi mdi-trash-can"></i></button>
                </td>
            </tr>
        `;
        $('#detailTable tbody').append(newRow);
        detailIdx++;
    });

    $(document).on('click', '.remove-row', function() { $(this).closest('tr').remove(); });

    $('#formJadwal').on('submit', function(e) {
        let quota = {};
        let isValid = true;
        let msg = "";

        $('.detail-row').each(function() {
            let opt = $(this).find('.akd-select option:selected');
            let akdId = opt.val();

            // Jika Non-AKD (akdId kosong), tidak perlu cek kuota
            if(!akdId) return;

            let kat = opt.data('kategori');
            let tipe = $(this).find('.tipe-select').val();
            let key = akdId + '_' + tipe;
            quota[key] = (quota[key] || 0) + 1;

            let limit = (kat === 'komisi') ? 2 : 1;
            if(quota[key] > limit) {
                isValid = false;
                msg = `Kuota ${tipe} untuk ${opt.text()} Maksimal ${limit} kali!`;
                return false;
            }
        });

        if(!isValid) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Melebihi Kuota', text: msg });
        }
    });
</script>
@endpush
