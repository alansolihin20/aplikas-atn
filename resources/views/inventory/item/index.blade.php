@extends('layouts.app')

@section('title', 'Master Barang')
<meta http-equiv="Cache-Control" content="no-store" />
@section('content')

<main class="app-main">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">📦 Master Data Barang</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="card m-4 p-0">
            <div class="card-header">
                <h3 class="card-title">Manajemen Barang</h3>
            </div>

            <div class="card-body table-responsive">

                {{-- ALERT SUCCESS --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- BUTTON TAMBAH BARANG --}}
                <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addBarang">
                    <i class="fas fa-plus"></i> Tambah Barang
                </button>

                {{-- 🔹 Modal Tambah Barang --}}
                @component('components.modal', [
                    'id' => 'addBarang',
                    'title' => 'Tambah Barang',
                ])
                @slot('slot')

                <form action="{{ route('items.store') }}" method="POST">
                    @csrf

                    <div class="mb-2">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" class="form-control" min="0">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">SKU</label>
                        <input type="number" name="sku" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" placeholder="pcs/box/roll">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" name="description" class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>

                @endslot
                @endcomponent

                {{-- TABLE ITEM --}}
                <table class="table table-bordered mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>SKU</th>
                            <th>Stok</th>
                            <th>Unit</th>
                            <th>Deskripsi</th>
                            <th width="80px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $i)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $i->name }}</td>
                            <td>{{ $i->sku }}</td>
                            <td>{{ $i->stock }}</td>
                            <td>{{ $i->unit }}</td>
                            <td>{{ $i->description }}</td>
                            <td>
                                <form action="{{ route('items.destroy', $i->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus barang ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</main>

@endsection
