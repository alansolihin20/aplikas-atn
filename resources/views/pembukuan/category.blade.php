
      @extends('layouts.app')

      @section('title', 'Kategori Transaksi')
<meta http-equiv="Cache-Control" content="no-store" />
      @section('content')
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Kategori Transaksi</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Admin</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
     
        <div class="card m-4 p-0">
        <div class="card-header"><h3 class="card-title">Kategori</h3></div>
        <!-- /.card-header -->
        <div class="card-body">

            <!-- Scrollable modal -->
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i>
            Tambah Kategori
            </button>

            <!-- Modal Tambah -->
            
            @component('components.modal', [
                'id' => 'createModal',
                'title' => 'Tambah Kategori',
            ])
               @slot('slot')
                <form action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type Transaksi</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="" disabled selected>Pilih Type Transaksi</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
               @endslot
            
            @endcomponent

               


           
            <table class="table table-bordered">
            <thead>
                <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Type Transaksi</th>
                <th>Action</th>
                </tr>
            </thead>
            @foreach ($categories as $category) 
            <tbody>
                <tr class="align-middle">
                <td>{{$loop->iteration}}</td>
                <td>{{$category->name}}</td>
                <td>{{$category->type}}</td>
                <td>
                  <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                        <i class="fas fa-trash"></i>
                    </button>
                  </form>

                  <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $category->id }}">
                    <i class="fas fa-edit"></i>
                  </button>

                  @component('components.modal', [
                    'id' => 'editModal-' . $category->id, // Supaya modal-nya unik per kategori
                    'title' => 'Edit Kategori',
                ])
                @slot('slot')
                    <form action="{{ route('category.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name-{{ $category->id }}" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="name-{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="type-{{ $category->id }}" class="form-label">Type Transaksi</label>
                            <select class="form-select" id="type-{{ $category->id }}" name="type" required>
                                <option value="income" {{ $category->type == 'income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ $category->type == 'expense' ? 'selected' : '' }}>Expense</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                @endslot
            @endcomponent 
                </td>
                </tr>
            </tbody>

            @endforeach
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
            </ul>
        </div>
        </div>


           
                
        <!--end::App Content-->
      </main>
      @endsection
 