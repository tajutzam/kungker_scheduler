@extends('templates.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title text-primary">Manajemen User</h4>
                        <p class="card-description">Kelola aktor sistem: Admin dan Banmus.</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-icon-text shadow-sm">
                        <i class="mdi mdi-account-plus btn-icon-prepend"></i> Tambah User
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th class="font-weight-bold">Nama</th>
                                <th class="font-weight-bold">Email</th>
                                <th class="font-weight-bold">Role</th>
                                <th class="text-center font-weight-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="py-3">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <label class="badge badge-info">{{ strtoupper($user->role) }}</label>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center" style="gap: 10px;">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="btn btn-sm btn-warning text-white shadow-sm">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete shadow-sm">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Integrasi SweetAlert2 untuk penghapusan user
    $('.btn-delete').on('click', function(e) {
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Hapus User?',
            text: "User ini tidak akan bisa login kembali setelah dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Warna merah untuk hapus
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
