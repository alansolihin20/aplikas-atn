@extends('layouts.app')
@section('title', 'Data Slip Gaji')
<meta http-equiv="Cache-Control" content="no-store" />
@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Slip Gaji Teknisi</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Slip Gaji Teknisi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content py-4">
        <div class="container-fluid">

            {{-- Form Input Slip Gaji --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Input Slip Gaji Teknisi</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.slipgaji.store') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-3">
                            <label>Teknisi</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">Pilih Teknisi</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Periode</label>
                            <input type="date" name="periode" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Gaji Pokok</label>
                            <input type="number" name="gaji_pokok" value="2200000" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Insentif Harian</label>
                            <input type="number" name="insentif_harian" value="10000" class="form-control" required>
                        </div>
                        <div class="col-md-1">
                            <label>Hari</label>
                            <input type="number" name="hari_kerja" class="form-control" value="26" required>
                        </div>
                        <div class="col-md-2">
                            <label>BPJS TK</label>
                            <input type="number" name="bpjs_tk" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="col-md-2">
                            <label>BPJS Kes</label>
                            <input type="number" name="bpjs_kes" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="col-md-2">
                            <label>Pinjaman</label>
                            <input type="number" name="pinjaman" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary px-4">Simpan Slip Gaji</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Riwayat Slip Gaji --}}
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Riwayat Slip Gaji</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Periode</th>
                                <th>Pokok</th>
                                <th>Bruto</th>
                                <th>BPJS TK</th>
                                <th>BPJS Kes</th>
                                <th>Pinjaman</th>
                                <th>Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($slips as $slip)
                                <tr>
                                    <td>{{ $slip->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($slip->periode)->translatedFormat('F Y') }}</td>
                                    <td>Rp{{ number_format($slip->gaji_pokok, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($slip->gaji_bruto, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($slip->bpjs_tk, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($slip->bpjs_kes, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($slip->pinjaman, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-success">Rp{{ number_format($slip->gaji_bersih, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-muted">Belum ada data slip gaji.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>

{{-- Tambahan CSS agar footer ngangkat --}}
@push('styles')
<style>
    main.app-main {
        min-height: calc(100vh - 100px); /* jaga agar footer tidak menempel */
        display: flex;
        flex-direction: column;
    }
    .app-content {
        flex: 1;
    }
</style>
@endpush

@endsection
