@extends('templates.admin')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title text-primary font-weight-bold">
                                <i class="mdi mdi-calendar-plus mr-2"></i>Generate Jadwal Kunker
                            </h4>
                            <p class="card-description">Sistem akan mengisi kegiatan otomatis berdasarkan master data
                                kegiatan.</p>
                        </div>
                        <div class="d-flex" style="gap: 10px;">
                            <button type="button" class="btn btn-secondary shadow-sm" id="btnAutoGenerate">
                                <i class="mdi mdi-autorenew mr-1"></i> Auto-Generate Sebulan
                            </button>
                            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-light border">
                                Kembali
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.jadwal.store') }}" method="POST" id="formJadwal">
                        @csrf
                        {{-- Form Periode (Bulan/Tahun) Tetap Sama --}}
                        <div class="bg-light p-4 rounded mb-4 shadow-sm border-left border-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row mb-0">
                                        <label class="col-sm-3 col-form-label font-weight-bold">Bulan</label>
                                        <div class="col-sm-9">
                                            <select name="bulan" class="form-control" required id="selectBulan">
                                                @foreach (range(1, 12) as $m)
                                                    <option value="{{ $m }}"
                                                        {{ date('m') == $m ? 'selected' : '' }}>
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
                                            <input type="number" name="tahun" class="form-control"
                                                value="{{ date('Y') }}" required id="inputTahun">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold text-primary mb-0">Detail Jadwal</h6>
                            <button type="button" class="btn btn-success btn-sm" id="addDetail">
                                <i class="mdi mdi-plus"></i> Tambah Manual
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
                                    {{-- Baris akan diisi oleh JavaScript --}}
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5 d-flex justify-content-end" style="gap: 15px;">
                            <button type="submit" class="btn btn-secondary btn-lg px-5 shadow">
                                <i class="mdi mdi-send mr-2"></i>Simpan & Ajukan Jadwal
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
        let detailIdx = 0;
        const dataAkds = @json($akds);
        const dataKegiatans = @json($kegiatans);

        function formatDate(date) {
            let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }

        function createRow(idx, data = {}) {
            let akdOptions = `<option value="">-- Pilih AKD --</option>`;
            dataAkds.forEach(akd => {
                let selected = (data.akd_id == akd.id) ? 'selected' : '';
                akdOptions +=
                    `<option value="${akd.id}" data-kategori="${akd.kategori}" ${selected}>${akd.nama_akd}</option>`;
            });

            let kegiatanOptions = `<option value="">-- Pilih Kegiatan --</option>`;
            dataKegiatans.forEach(keg => {
                let selected = (data.kegiatan_id == keg.id) ? 'selected' : '';
                kegiatanOptions += `<option value="${keg.id}" ${selected}>${keg.name}</option>`;
            });

            return `
            <tr class="detail-row">
                <td>
                    <select name="details[${idx}][akd_id]" class="form-control akd-select" required>
                        ${akdOptions}
                    </select>
                </td>
                <td>
                    <select name="details[${idx}][tipe_kunjungan]" class="form-control tipe-select" required>
                        <option value="DP" ${data.tipe == 'DP' ? 'selected' : ''}>Dalam Prov</option>
                        <option value="LP" ${data.tipe == 'LP' ? 'selected' : ''}>Luar Prov</option>
                    </select>
                </td>
                <td><input type="text" name="details[${idx}][tujuan]" class="form-control" value="${data.tujuan || ''}" placeholder="Lokasi" required></td>
                <td>
                    <select name="details[${idx}][kegiatan_id]" class="form-control" required>
                        ${kegiatanOptions}
                    </select>
                </td>
                <td><input type="date" name="details[${idx}][tgl_mulai]" class="form-control tgl-mulai" value="${data.tgl_mulai || ''}" required></td>
                <td><input type="date" name="details[${idx}][tgl_selesai]" class="form-control tgl-selesai" value="${data.tgl_selesai || ''}" readonly required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-secondary remove-row"><i class="mdi mdi-trash-can"></i></button>
                </td>
            </tr>`;
        }

        $('#btnAutoGenerate').on('click', function() {
            const bulan = parseInt($('#selectBulan').val());
            const tahun = parseInt($('#inputTahun').val());

            // 1. Hitung jumlah hari dalam bulan tersebut
            const jumlahHari = new Date(tahun, bulan, 0).getDate();

            $('#detailTable tbody').empty();
            detailIdx = 0;

            let currentDay = 1;

            // 2. Loop melalui semua kegiatan master
            dataKegiatans.forEach((keg, index) => {
                if (currentDay > jumlahHari) return; // Berhenti jika bulan sudah penuh

                // Tentukan tanggal mulai
                let tglMulai = new Date(tahun, bulan - 1, currentDay);
                let formattedStart = formatDate(tglMulai);

                // Tentukan tipe (Default DP agar hemat hari, atau selang-seling)
                let tipe = (index % 2 === 0) ? 'DP' : 'LP';
                let durasi = (tipe === 'DP' ? 1 : 2); // Hari tambahannya

                // Hitung tanggal selesai
                let tglSelesai = new Date(tglMulai);
                tglSelesai.setDate(tglMulai.getDate() + durasi);
                let formattedEnd = formatDate(tglSelesai);

                // Ambil AKD secara bergiliran (Round Robin)
                let akd = dataAkds[index % dataAkds.length];

                $('#detailTable tbody').append(createRow(detailIdx, {
                    akd_id: akd ? akd.id : '', // Jika AKD habis, bisa dikosongkan (Non-AKD)
                    kegiatan_id: keg.id,
                    tipe: tipe,
                    tujuan: 'Lokasi Strategis',
                    tgl_mulai: formattedStart,
                    tgl_selesai: formattedEnd
                }));

                detailIdx++;

                // Geser hari ke jadwal berikutnya (misal: mulai lagi setelah tgl selesai)
                currentDay += (durasi + 1);
            });

            Swal.fire('Berhasil', `Jadwal berhasil didistribusikan hingga tanggal ${currentDay - 1}`, 'success');
        });

        // Handler manual jika user mengubah tanggal mulai atau tipe di tabel
        $(document).on('change', '.tgl-mulai, .tipe-select', function() {
            let row = $(this).closest('tr');
            let start = row.find('.tgl-mulai').val();
            let tipe = row.find('.tipe-select').val();
            if (start && tipe) {
                let d = new Date(start);
                d.setDate(d.getDate() + (tipe === 'DP' ? 1 : 2));
                row.find('.tgl-selesai').val(formatDate(d));
            }
        });

        $('#addDetail').on('click', function() {
            $('#detailTable tbody').append(createRow(detailIdx));
            detailIdx++;
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    </script>
@endpush
