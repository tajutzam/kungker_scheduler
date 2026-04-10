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
                            <p class="card-description">Sistem otomatis menentukan kuota berdasarkan kategori AKD
                                (Flowchart).</p>
                        </div>
                        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-light btn-icon-text border">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.jadwal.store') }}" method="POST" id="formJadwal">
                        @csrf

                        <div class="bg-light p-4 rounded mb-4 shadow-sm border-left border-info">
                            <h6 class="font-weight-bold mb-3 text-info">
                                <i class="mdi mdi-clock-outline mr-1"></i> 1. Pilih Tahun & Bulan
                            </h6>
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
                            <h6 class="font-weight-bold text-primary mb-0">
                                <i class="mdi mdi-vector-arrange-below mr-1"></i> 2. Sebar Jadwal Kunker Otomatis
                            </h6>
                            <button type="button" class="btn btn-success btn-sm shadow-sm" id="addDetail">
                                <i class="mdi mdi-plus"></i> Tambah Baris AKD
                            </button>
                        </div>

                        <div class="table-responsive border rounded shadow-sm">
                            <table class="table table-hover mb-0" id="detailTable">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th width="20%">Nama AKD</th>
                                        <th width="15%">Tipe (Kuota)</th>
                                        <th>Tujuan</th>
                                        <th>Kegiatan</th>
                                        <th width="12%">Tgl Mulai</th>
                                        <th width="12%">Tgl Selesai</th>
                                        <th width="50px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="detail-row">
                                        <td>
                                            <select name="details[0][akd_id]" class="form-control akd-select" required>
                                                <option value="">-- Pilih AKD --</option>
                                                @foreach ($akds as $akd)
                                                    <option value="{{ $akd->id }}"
                                                        data-kategori="{{ $akd->kategori }}">
                                                        {{ $akd->nama_akd }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="details[0][tipe_kunjungan]" class="form-control tipe-select"
                                                required>
                                                <option value="">-- Pilih AKD dulu --</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="details[0][tujuan]" class="form-control"
                                                placeholder="Lokasi" required></td>
                                        <td><input type="text" name="details[0][kegiatan]" class="form-control"
                                                placeholder="Nama Kegiatan" required></td>
                                        <td><input type="date" name="details[0][tgl_mulai]"
                                                class="form-control tgl-mulai" required></td>
                                        <td><input type="date" name="details[0][tgl_selesai]"
                                                class="form-control tgl-selesai" readonly required></td>
                                        <td class="text-center text-muted"><i class="mdi mdi-lock"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5 d-flex justify-content-end" style="gap: 15px;">
                            <button type="reset" class="btn btn-light btn-lg px-4 border">Reset Form</button>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
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
        let detailIdx = 1;

        function calculateEndDate(row) {
            const startDateVal = row.find('.tgl-mulai').val();
            const typeVal = row.find('.tipe-select').val();

            if (startDateVal && typeVal) {
                let startDate = new Date(startDateVal);


                let daysToAdd = (typeVal === 'DP') ? 1 : 2;

                let endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + daysToAdd);

                let yyyy = endDate.getFullYear();
                let mm = String(endDate.getMonth() + 1).padStart(2, '0');
                let dd = String(endDate.getDate()).padStart(2, '0');

                // Mengisi input tgl-selesai di baris yang bersangkutan
                row.find('.tgl-selesai').val(`${yyyy}-${mm}-${dd}`);
            }
        }

        // 2. Fungsi Otomatisasi Tipe & Kuota saat AKD dipilih
        function handleAkdChange(row) {
            const akdOption = row.find('.akd-select option:selected');
            const kategori = akdOption.data('kategori');
            const tipeSelect = row.find('.tipe-select');

            if (!kategori) {
                tipeSelect.html('<option value="">-- Pilih AKD dulu --</option>');
                return;
            }

            let quotaLabel = (kategori === 'komisi') ? '2 Jatah' : '1 Jatah';

            tipeSelect.html(`
            <option value="DP">Dalam Prov (2 Hari) - [${quotaLabel}]</option>
            <option value="LP">Luar Prov (3 Hari) - [${quotaLabel}]</option>
        `);

            calculateEndDate(row);
        }

        // Event Listeners
        $(document).on('change', '.akd-select', function() {
            handleAkdChange($(this).closest('tr'));
        });

        $(document).on('change', '.tgl-mulai, .tipe-select', function() {
            calculateEndDate($(this).closest('tr'));
        });

        // 3. Tambah Baris Dinamis
        $('#addDetail').on('click', function() {
            let newRow = `
            <tr class="detail-row animated fadeIn">
                <td>
                    <select name="details[${detailIdx}][akd_id]" class="form-control akd-select" required>
                        <option value="">-- Pilih AKD --</option>
                        @foreach ($akds as $akd)
                            <option value="{{ $akd->id }}" data-kategori="{{ $akd->kategori }}">
                                {{ $akd->nama_akd }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="details[${detailIdx}][tipe_kunjungan]" class="form-control tipe-select" required>
                        <option value="">-- Pilih AKD dulu --</option>
                    </select>
                </td>
                <td><input type="text" name="details[${detailIdx}][tujuan]" class="form-control" placeholder="Lokasi" required></td>
                <td><input type="text" name="details[${detailIdx}][kegiatan]" class="form-control" placeholder="Nama Kegiatan" required></td>
                <td><input type="date" name="details[${detailIdx}][tgl_mulai]" class="form-control tgl-mulai" required></td>
                <td><input type="date" name="details[${detailIdx}][tgl_selesai]" class="form-control tgl-selesai" readonly required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                        <i class="mdi mdi-trash-can-outline"></i>
                    </button>
                </td>
            </tr>
        `;
            $('#detailTable tbody').append(newRow);
            detailIdx++;
        });

        // 4. LOGIKA VALIDASI KUOTA KETAT (Sesuai Flowchart)
        $('#formJadwal').on('submit', function(e) {
            let quotaCount = {};
            let isValid = true;
            let msg = "";

            $('.detail-row').each(function() {
                let akdOption = $(this).find('.akd-select option:selected');
                let akdId = akdOption.val();
                let akdName = akdOption.text().trim();
                let kategori = akdOption.data('kategori');
                let tipe = $(this).find('.tipe-select').val();

                if (!akdId || !tipe) return;

                let key = akdId + '_' + tipe;
                quotaCount[key] = (quotaCount[key] || 0) + 1;

                let limit = (kategori === 'komisi') ? 2 : 1;

                if (quotaCount[key] > limit) {
                    isValid = false;
                    msg =
                        `<b>Gagal Simpan!</b><br>AKD <b>${akdName}</b> berkategori <b>${kategori.toUpperCase()}</b>.<br>Kuota <b>${tipe}</b> maksimal <b>${limit}</b> kali dalam sebulan.`;
                    return false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Kuota Terlampaui',
                    html: msg,
                    confirmButtonColor: '#3085d6'
                });
            }
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    </script>
@endpush
