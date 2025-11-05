@extends('layouts.app')
@section('title', 'Manajemen PPPoE')

@section('content')
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Manajemen PPPoE</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">PPPoE</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="card m-4">
      <h3 class="card-title mx-3 mt-3 mb-1">Daftar PPPoE</h3>
      <div class="card-header d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPppoe">
          <i class="fas fa-plus"></i> Tambah PPPoE
        </button>
        <button type="button" class="btn btn-success me-2" id="syncBtn">
            <i class="fas fa-sync-alt"></i> Sinkronisasi dari Mikrotik
        </button>

        <select id="mikrotikConnection" class="form-select w-auto me-2">
          @foreach($connections as $conn)
            <option value="{{ $conn->id }}">{{ $conn->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="card-body table-responsive">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Username</th>
              <th>Password</th>
              <th>Profile</th>
              <th>Service</th>
              <th>Local Address</th>
              <th>Remote Address</th>
              <th>Komentar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $it)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $it->name }}</td>
              <td>{{ $it->username }}</td>
              <td>{{ $it->password }}</td>
              <td>{{ $it->profile }}</td>
              <td>{{ $it->service }}</td>
              <td>{{ $it->local_address }}</td>
              <td>{{ $it->remote_address }}</td>
              <td>{{ $it->comment }}</td>
              <td>
                <button class="btn btn-sm btn-outline-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#editPppoe"
                  data-item='@json($it)'>
                  <i class="fas fa-edit"></i> Edit
                </button>
                <form action="{{ route('pppoe.destroy', $it->id) }}" method="POST" style="display:inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus PPPoE ini?')">
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
@component('components.modal', ['id' => 'createPppoe', 'title' => 'Tambah PPPoE'])
@slot('slot')
<form action="{{ route('pppoe.store') }}" method="POST">
  @csrf

  @if (isset($mikrotikError) && $mikrotikError)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error Koneksi Mikrotik:</strong> {{ $mikrotikError }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
  <input type="hidden" name="connection_id" value="{{ $connectionId }}">

  <div class="mb-2">
    <label>Nama</label>
    <input type="text" name="name" class="form-control" required>
  </div>

 

  <div class="mb-2">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>

  <div class="mb-2">
    <label>Service</label>
    <input type="text" name="service" class="form-control" value="pppoe">
  </div>

  <div class="mb-2">
    <label>Profile</label>
    <select name="profile" class="form-select">
      @foreach($profiles as $profile)
        <option value="{{ $profile['name'] }}">{{ $profile['name'] }}</option>
      @endforeach
    </select>
  </div>

  <div class="mb-2">
    <label>Local Address</label>
    <input type="text" name="local_address" class="form-control" placeholder="Contoh: 10.10.10.1">
  </div>

  <div class="mb-2">
    <label>Remote Address</label>
    <input type="text" name="remote_address" class="form-control" placeholder="Contoh: 10.10.10.2">
  </div>

  <div class="mb-2">
    <label>Komentar</label>
    <input type="text" name="comment" class="form-control">
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>
@endslot
@endcomponent

{{-- Modal Edit --}}
{{-- Modal Edit --}}
@component('components.modal', ['id' => 'editPppoe', 'title' => 'Edit PPPoE'])
@slot('slot')
<form id="editForm" method="POST">
  @csrf
  @method('PUT')

  <input type="hidden" name="connection_id" value="{{ $connectionId }}">

  <div class="mb-2">
    <label>Nama</label>
    <input type="text" name="name" id="edit_name" class="form-control" required>
  </div>

  <div class="mb-2">
    <label>Username</label>
    <input type="text" name="username" id="edit_username" class="form-control" required>
  </div>

  <div class="mb-2">
    <label>Password</label>
    <input type="password" name="password" id="edit_password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
  </div>

  <div class="mb-2">
    <label>Service</label>
    <input type="text" name="service" id="edit_service" class="form-control">
  </div>

  <div class="mb-2">
    <label>Profile</label>
    <select name="profile" id="edit_profile" class="form-select">
      @foreach($profiles as $profile)
        <option value="{{ $profile['name'] }}">{{ $profile['name'] }}</option>
      @endforeach
    </select>
  </div>

  <div class="mb-2">
    <label>Local Address</label>
    <input type="text" name="local_address" id="edit_local_address" class="form-control" placeholder="Contoh: 10.10.10.1">
  </div>

  <div class="mb-2">
    <label>Remote Address</label>
    <input type="text" name="remote_address" id="edit_remote_address" class="form-control" placeholder="Contoh: 10.10.10.2">
  </div>

  <div class="mb-2">
    <label>Komentar</label>
    <input type="text" name="comment" id="edit_comment" class="form-control">
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
// Modal Edit
document.getElementById('editPppoe').addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  const data = JSON.parse(button.getAttribute('data-item'));
  const form = document.getElementById('editForm');

  // Pakai route helper Laravel
  form.action = `{{ url('admin/pppoe') }}/${data.id}`;

  document.getElementById('edit_name').value = data.name;
  document.getElementById('edit_username').value = data.username;
  document.getElementById('edit_password').value = '';
  document.getElementById('edit_service').value = data.service;
  document.getElementById('edit_profile').value = data.profile;
  document.getElementById('edit_local_address').value = data.local_address;
  document.getElementById('edit_remote_address').value = data.remote_address;
  document.getElementById('edit_comment').value = data.comment;
});


// Sinkronisasi dari Mikrotik
document.getElementById('syncBtn').addEventListener('click', async () => {
    const connectionId = document.getElementById('mikrotikConnection').value;
    try {
        const res = await fetch('{{ route('pppoe.sync') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ connection_id: connectionId })
        });
        const data = await res.json();
        if (data.ok) {
            alert('Sinkronisasi berhasil! Jumlah user: ' + data.count);
            location.reload();
        } else {
            alert('Gagal: ' + data.error);
        }
    } catch (err) {
        alert('Error: ' + err.message);
    }
});

</script>
@endpush
