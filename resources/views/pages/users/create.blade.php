@extends('templates.admin')

@section('content')
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah User</h4>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="admin">Admin</option>
                            <option value="bamus">Banmus</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">Simpan</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
