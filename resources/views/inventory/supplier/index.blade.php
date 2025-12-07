@extends('layouts.app')
{{-- Mengikuti struktur Barang Keluar: Title dan Meta --}}
@section('title', 'Daftar Supplier') 
<meta http-equiv="Cache-Control" content="no-store" /> 

@section('content')
<main class="app-main">

{{-- HEADER & BREADCRUMBS: Replikasi struktur Barang Keluar --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            {{-- Mengubah Judul --}}
            <div class="col-sm-6"><h3 class="mb-0">📦 Daftar Supplier</h3></div> 
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    {{-- Mengubah Breadcrumb aktif --}}
                    <li class="breadcrumb-item active">Supplier</li> 
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CONTENT: Replikasi struktur Barang Keluar --}}
<div class="app-content">
    <div class="container-fluid">

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
            {{-- Perhatian: mx-4 mungkin tidak diperlukan di sini jika card menggunakan m-4 --}}
            <div class="alert alert-success mx-4">
                {{ session('success') }}
            </div>
        @endif
        @if (isset($errorMessage))
            <div class="alert alert-danger mx-4">
                {{ $errorMessage }}
            </div>
        @endif
        
        <div class="row">
            <div class="col-md-12">

                {{-- CARD: DAFTAR SUPPLIER --}}
                <div class="card m-4 p-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Data Supplier</h3>
                        {{-- TOMBOL TRIGGER MODAL TAMBAH SUPPLIER --}}
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="fas fa-plus"></i> Tambah Supplier
                        </button>
                    </div>

                    <div class="card-body table-responsive">
                        {{-- TABEL SUPPLIER --}}
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th>Telegram Chat ID</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $s)
                                    <tr>
                                        <td>{{ $s->name }}</td>
                                        <td>{{ $s->phone }}</td>
                                        <td>{{ $s->address }}</td>
                                        <td>{{ $s->telegram_chat_id }}</td>
                                        <td>
                                            <form action="{{ route('suppliers.destroy', $s->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($suppliers->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data supplier.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
</main>

{{-- ================================================================= --}}
{{-- MODAL UNTUK TAMBAH SUPPLIER --}}
{{-- ================================================================= --}}
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">Tambah Supplier Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Isi Form: Dipindahkan dari card sebelumnya --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Supplier</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama supplier" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Telepon">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="Alamat">
                    </div>
                    <div class="mb-3">
                        <label for="telegram_chat_id" class="form-label">Telegram Chat ID</label>
                        <input type="text" name="telegram_chat_id" id="telegram_chat_id" class="form-control" placeholder="Telegram Chat ID">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection