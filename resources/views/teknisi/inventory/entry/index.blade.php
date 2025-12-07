@extends('layouts.app')

@section('title', 'Barang Masuk')
<meta http-equiv="Cache-Control" content="no-store" />
@section('content')

<main class="app-main">

{{-- HEADER & BREADCRUMBS --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">📥 Riwayat Barang Masuk</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Barang Masuk</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="app-content">
    <div class="container-fluid">

        {{-- CARD: DAFTAR BARANG MASUK --}}
        <div class="card m-4 p-0">
            <div class="card-header">
                <h3 class="card-title">Data Penerimaan Barang dari Supplier</h3>
            </div>

            <div class="card-body table-responsive">
                {{-- TABEL BARANG MASUK --}}
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Barang</th>
                            <th>Supplier</th>
                            <th>Qty</th>
                            <th>Total Harga</th>
                            <th>Tanggal Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $e)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $e->item->name }}</td>
                            <td>{{ $e->supplier->name }}</td>
                            <td>{{ $e->qty }}</td>
                            <td>Rp {{ number_format($e->total_price, 0, ',', '.') }}</td>
                            <td>{{ $e->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                        @if($entries->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada riwayat barang masuk.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>


</main>

@endsection