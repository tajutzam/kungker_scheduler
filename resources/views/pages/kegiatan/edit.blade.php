@extends('templates.admin')

@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary">Edit Master Kegiatan</h4>
                    <p class="card-description"> Ubah nama jenis kegiatan yang sudah ada. </p>

                    <form class="forms-sample" action="{{ route('admin.kegiatan.update', $kegiatan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nama">Nama Kegiatan</label>
                            <input type="text" name="name" class="form-control" id="nama"
                                value="{{ old('name', $kegiatan->name) }}"
                                placeholder="Contoh: Rapat Paripurna" required autofocus>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-secondary mr-2 shadow-sm">
                                <i class="mdi mdi-refresh mr-1"></i> Perbarui
                            </button>
                            <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
