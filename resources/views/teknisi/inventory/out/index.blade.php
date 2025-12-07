@extends('layouts.app')

@section('title', 'Barang Keluar')
<meta http-equiv="Cache-Control" content="no-store" />
@section('content')

<main class="app-main">

{{-- HEADER & BREADCRUMBS --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">📤 Riwayat Barang Keluar</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Barang Keluar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="app-content">
    <div class="container-fluid">

        {{-- CARD: DAFTAR BARANG KELUAR --}}
        <div class="card m-4 p-0">
            <div class="card-header">
                <h3 class="card-title">Data Pengeluaran Barang (Usage/Outflow)</h3>
            </div>

            <div class="card-body table-responsive">
                {{-- TABEL BARANG KELUAR --}}
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Keperluan/Catatan</th>
                            <th>Tanggal Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outflows as $o)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $o->item->name }}</td>
                            <td>{{ $o->qty }}</td>
                            <td>{{ $o->note }}</td>
                            <td>{{ $o->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endforeach
                        @if($outflows->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada riwayat barang keluar.</td>
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