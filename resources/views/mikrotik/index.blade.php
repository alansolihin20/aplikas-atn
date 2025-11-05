@extends('layouts.app')
@section('title', 'Koneksi Mikrotik')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Koneksi Mikrotik</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mikrotik</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="card m-4">
            <div class="card-header d-flex justify-content-between align-items-center">
               
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMikrotik">
                    <i class="fas fa-plus"></i> Tambah Koneksi
                </button>
            </div>

            <div class="card-body table-responsive">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
                @endif

                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Host</th>
                            <th>User</th>
                            <th>SSL</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $it)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $it->name }}</td>
                            <td>{{ $it->host }}:{{ $it->port }}</td>
                            <td>{{ $it->username }}</td>
                            <td>{{ $it->use_ssl ? 'Ya' : 'Tidak' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success" onclick="testConn({{ $it->id }})">
                                    <i class="fas fa-plug"></i> Test
                                </button>
                                <button class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editMikrotik"
                                    data-item='@json($it)'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('mikrotik.destroy', $it->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $items->links() }}
            </div>
        </div>
    </div>
</main>

{{-- Modal Create --}}
@component('components.modal', ['id' => 'createMikrotik', 'title' => 'Tambah Koneksi Mikrotik'])
@slot('slot')
<form action="{{ route('mikrotik.store') }}" method="POST">
    @csrf
    <div class="mb-2">
        <label>Nama Koneksi</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Host</label>
        <input type="text" name="host" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Port</label>
        <input type="number" name="port" class="form-control" value="8728" required>
    </div>
    <div class="mb-2">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" name="use_ssl" class="form-check-input" id="use_ssl_create">
        <label for="use_ssl_create" class="form-check-label">Gunakan SSL</label>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
@endslot
@endcomponent

{{-- Modal Edit --}}
@component('components.modal', ['id' => 'editMikrotik', 'title' => 'Edit Koneksi Mikrotik'])
@slot('slot')
<form id="editForm" method="POST">
    @csrf @method('PUT')
    <div class="mb-2">
        <label>Nama Koneksi</label>
        <input type="text" name="name" id="edit_name" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Host</label>
        <input type="text" name="host" id="edit_host" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Port</label>
        <input type="number" name="port" id="edit_port" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Username</label>
        <input type="text" name="username" id="edit_username" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Password</label>
        <input type="password" name="password" id="edit_password" class="form-control">
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" name="use_ssl" class="form-check-input" id="edit_use_ssl">
        <label for="edit_use_ssl" class="form-check-label">Gunakan SSL</label>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
@endslot
@endcomponent
@endsection

@push('scripts')
<script>
document.getElementById('editMikrotik').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const data = JSON.parse(button.getAttribute('data-item'));
    const form = document.getElementById('editForm');
    form.action = `/mikrotik/${data.id}`;

    document.getElementById('edit_name').value = data.name;
    document.getElementById('edit_host').value = data.host;
    document.getElementById('edit_port').value = data.port;
    document.getElementById('edit_username').value = data.username;
    document.getElementById('edit_use_ssl').checked = data.use_ssl;
});

async function testConn(id) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        const res = await fetch("{{ route('mikrotik.test') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ connection_id: id })
        });
        const data = await res.json();
        alert(data.ok ? '✅ Koneksi berhasil!' : '❌ Gagal: ' + data.error);
    } catch (err) {
        alert('⚠️ Gagal request: ' + err.message);
    }
}
</script>
@endpush
