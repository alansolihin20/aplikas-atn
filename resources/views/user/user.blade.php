
      @extends('layouts.app')

      @section('title', 'Pemasukan')
<meta http-equiv="Cache-Control" content="no-store" />
      @section('content')
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">User List</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">User List</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>

        

        
          @component('components.modal', [
          'id' => 'userModal',
          'title' => 'Tambah User',
        ])

        @slot('slot')
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="" disabled selected>Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">Teknisi</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        @endslot

        @endcomponent

         
        <div class="card m-4 p-0">
        <div class="card-header"><h3 class="card-title">User List</h3></div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">

          <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#userModal">
                <i class="fas fa-plus"></i>
            Tambah User
          </button>

    

            <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            @foreach ($users as $user)
            <tbody>
                <tr class="align-middle">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>

                    <td>
                      <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus User ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                      </form>


                      <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUser{{ $user->id }}">
                    <i class="fas fa-edit"></i>
                  </button>

                  @component('components.modal', [
                    'id' => 'editUser' . $user->id, // Supaya modal-nya unik per kategori
                    'title' => 'Edit User',
                  ])
                @slot('slot')
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"  required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach (['admin', 'teknisi'] as $roleOption)
                                    <option value="{{ $roleOption }}" {{ $user->role === $roleOption ? 'selected' : '' }}>
                                        {{ ucfirst($roleOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
                @endslot
            @endcomponent 
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>

        </div>


           
                
        <!--end::App Content-->
      </main>
      @endsection
 