@extends('templates.admin')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Data AKD</h4>
                    <p class="card-description"> Masukkan informasi Alat Kelengkapan Dewan baru. </p>

                    <form class="forms-sample" action="{{ route('admin.akd.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama_akd">Nama AKD</label>
                            <input type="text" name="nama_akd" class="form-control" id="nama_akd"
                                placeholder="Contoh: Komisi A" required>
                        </div>
                        <div class="form-group">
                            <label>Kategori AKD (Untuk Kuota Otomatis)</label>
                            <select name="kategori" class="form-control" required>
                                <option value="komisi">Komisi (Kuota: 2 DP, 2 LP)</option>
                                <option value="non-komisi">Non-Komisi (Kuota: 1 DP, 1 LP)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="deskripsi" rows="4" placeholder="Keterangan mengenai AKD..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-secondary mr-2">Simpan</button>
                        <a href="{{ route('admin.akd.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
