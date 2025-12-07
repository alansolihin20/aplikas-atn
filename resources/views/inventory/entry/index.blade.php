@extends('layouts.app')
@section('title', 'Barang Masuk')
<meta http-equiv="Cache-Control" content="no-store" />
@section('content')

<main class="app-main">
    <!-- HEADER -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">📥 Barang Masuk</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Barang Masuk</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="app-content">
        <div class="card m-4 p-0">
            <div class="card-header">
                <h3 class="card-title">Daftar Barang Masuk</h3>
            </div>

            <div class="card-body table-responsive">

                <!-- ALERT -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- BUTTON MODAL -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalEntry">
                    <i class="fas fa-plus me-1"></i> Tambah Barang Masuk
                </button>

                <!-- ======================= MODAL ADD DATA ======================= -->
                @component('components.modal', [
                    'id' => 'modalEntry',
                    'title' => 'Tambah Barang Masuk'
                ])
                @slot('slot')
                <form action="{{ route('itemEntry.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Request Pembelian (Approved)</label>
                        <select name="request_id" class="form-select" required>
                            <option value="">-- pilih --</option>
                            @foreach($requests as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->item->name }} — Qty {{ $r->qty }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nama Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- pilih --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <select name="item_id" class="form-select" required>
                            <option value="">-- pilih --</option>
                            @foreach($items as $i)
                                <option value="{{ $i->id }}">{{ $i->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Qty diterima</label>
                        <input type="number" name="qty" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label>Harga per Item</label>
                        <input type="number" name="price_per_item" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                @endslot
                @endcomponent
                <!-- ======================= END MODAL ======================= -->

                <!-- TABLE -->
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Supplier</th>
                            <th>Qty</th>
                            <th>Total Harga</th>
                            <th>Tanggal Masuk</th>
                        </tr>
                    </thead>

                    @foreach($entries as $e)
                        <tr>
                            <td>{{ $e->item->name }}</td>
                            <td>{{ $e->supplier->name }}</td>
                            <td>{{ $e->qty }}</td>
                            <td>{{ number_format($e->total_price) }}</td>
                            <td>{{ $e->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
</main>

@endsection
