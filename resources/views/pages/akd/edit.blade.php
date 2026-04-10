@extends('templates.admin')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Data AKD</h4>
                <p class="card-description"> Perbarui informasi Alat Kelengkapan Dewan. </p>

                <form class="forms-sample" action="{{ route('admin.akd.update', $akd->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama_akd">Nama AKD</label>
                        <input type="text" name="nama_akd" class="form-control" id="nama_akd"
                            value="{{ old('nama_akd', $akd->nama_akd) }}" placeholder="Contoh: Komisi A" required>
                        @error('nama_akd')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="deskripsi" rows="4"
                            placeholder="Keterangan mengenai AKD...">{{ old('deskripsi', $akd->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-secondary mr-2">Perbarui Data</button>
                    <a href="{{ route('admin.akd.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
