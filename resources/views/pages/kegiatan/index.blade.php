@extends('templates.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title text-primary">Master Data Kegiatan</h4>
                            <p class="card-description">
                                Daftar seluruh jenis kegiatan yang tersedia.
                            </p>
                        </div>
                        <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-secondary shadow-sm">
                            <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Kegiatan
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center" width="8%"> No </th>
                                    <th> Nama Kegiatan </th>
                                    <th class="text-center" width="20%"> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kegiatans as $index => $kegiatan)
                                    <tr>
                                        <td class="text-center"> {{ $index + 1 }} </td>
                                        <td class="text-dark"> {{ $kegiatan->name }} </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center" style="gap: 10px;">
                                                <a href="{{ route('admin.kegiatan.edit', $kegiatan->id) }}"
                                                    class="btn btn-sm btn-secondary" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>

                                                <form action="{{ route('admin.kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-secondary btn-delete"
                                                        title="Hapus">
                                                        <i class="mdi mdi-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <i class="mdi mdi-information-outline mdi-36px text-muted"></i>
                                            <p class="mt-2 text-muted">Belum ada data kegiatan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
