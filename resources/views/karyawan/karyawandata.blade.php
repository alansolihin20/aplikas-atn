
      @extends('layouts.app')

      @section('title', 'Karyawan')
<meta http-equiv="Cache-Control" content="no-store" />
      @section('content')
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Karyawan</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Teknisi</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
         <div class="card m-4 p-0">
        <div class="card-header"><h3 class="card-title">Karyawan</h3></div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <!-- Scrollable modal -->
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createKaryawan">
                <i class="fas fa-plus"></i>
            Tambah Karyawan
            </button>

            <!-- Modal Tambah -->
            
            @component('components.modal', [
                'id' => 'createKaryawan',
                'title' => 'Tambah Karyawan',
            ])
               @slot('slot')
                <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
                    </div>
                    <div class="mb-3">
                        <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                        <input type="number" step="0.01" class="form-control" id="gaji_pokok" name="gaji_pokok" required>
                    </div>
                    <div class="mb-3">
                        <label class="block">Tunjangan</label>
                        <input type="number" class="form-control" step="0.01" name="tunjangan"  required>
                    </div>
                    <div class="mb-3">
                        <label class="block">Foto</label>
                        <input type="file" class="form-control" name="foto"  required>
                    </div>
                   
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
               @endslot
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @endcomponent

               


           
            <table class="table table-bordered">
            <thead>
                <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Nik</th>
                <th>Jabatan</th>
                <th>Tanggal Masuk</th>
                <th>Gaji Pokok</th>
                <th>Tunjangan</th>
                <th>Status</th>
                <th>Foto</th>
                <th>Aksi</th>
                </tr>
            </thead>
            @foreach ($karyawans as $karyawan) 
            <tbody>
                <tr class="align-middle">
                <td>{{$loop->iteration}}</td>
                <td>{{$karyawan->name}}</td>
                <td>{{$karyawan->nik}}</td>
                <td>{{$karyawan->jabatan}}</td>
                <td>{{ formatTanggal($karyawan->tanggal_masuk) }}</td>
                <td>{{ formatRupiah($karyawan->gaji_pokok) }}</td>
                <td>{{ formatRupiah($karyawan->tunjangan) }}</td>
                <td>{{$karyawan->status}}</td>
                <td>
                    @if($karyawan->foto)
                    <img src="{{ asset('karyawan/' . $karyawan->foto) }}" alt="Foto Karyawan" width="50">
                    @else
                    N/A
                    @endif
                </td>
                
                   <td>
                      <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus Karyawan ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                      </form>


                      <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKaryawan{{ $karyawan->id }}">
                    <i class="fas fa-edit"></i>
                  </button>

                  @component('components.modal', [
                    'id' => 'editKaryawan' . $karyawan->id, // Supaya modal-nya unik per kategori
                    'title' => 'Edit Karyawan',
                  ])
                @slot('slot')
                    <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST">
    @csrf
    @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" value="{{ $karyawan->user->name }}" readonly>
            </div>

            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" class="form-control" value="{{ $karyawan->nik }}" readonly>
            </div>

            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" required value="{{ $karyawan->jabatan }}">
            </div>

            <div class="mb-3">
                <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                <input type="number" step="0.01" class="form-control" id="gaji_pokok" name="gaji_pokok" required value="{{ $karyawan->gaji_pokok }}">
            </div>

            <div class="mb-3">
                <label for="tunjangan" class="form-label">Tunjangan</label>
                <input type="number" step="0.01" class="form-control" id="tunjangan" name="tunjangan" value="{{ $karyawan->tunjangan }}">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="aktif" {{ $karyawan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $karyawan->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      @endsection
 