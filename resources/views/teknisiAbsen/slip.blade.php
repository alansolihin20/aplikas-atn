@extends('layouts.teknisi')

@section('title', 'Slip Gaji Saya')

@section('content')
<main class="app-main">
    {{-- HEADER KONTEN (Breadcrumb) --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Slip Gaji Saya</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Slip Gaji</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    {{-- BODY KONTEN --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-list-alt me-2"></i> Daftar Slip Gaji Tersedia</h5>
                    </div>
                    <div class="card-body">

                        @if($slips->isEmpty())
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i> Belum ada data slip gaji yang tercatat saat ini.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            {{-- Kolom Periode dibuat rata tengah --}}
                                            <th class="text-center"><i class="fas fa-calendar-alt me-1"></i> Periode</th>
                                            {{-- Kolom Gaji Bersih dibuat rata tengah --}}
                                            <th class="text-center"><i class="fas fa-money-bill-wave me-1"></i> Gaji Bersih</th>
                                            <th><i class="fas fa-tools me-1"></i> Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($slips as $index => $slip)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            
                                            {{-- Data Periode dibuat rata tengah --}}
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ ucfirst($slip->periode) }}</span>
                                            </td>
                                            
                                            {{-- Data Gaji Bersih dibuat rata tengah --}}
                                            <td class="text-center">
                                                <span class="badge bg-success p-2">
                                                    Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            
                                            {{-- Data Aksi sudah rata tengah --}}
                                            <td class="text-center">

                                            @if(!$slip->is_received)
                                                <form action="{{ route('teknisi.slip.terima', $slip->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success" title="Saya sudah menerima gaji"
                                                        onclick="return confirm('Yakin sudah menerima gaji ini?')">
                                                        <i class="fas fa-check"></i> Terima
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Diterima
                                                </span>
                                                <div class="text-muted small">
                                                    {{ $slip->received_at ? formatTanggal($slip->received_at) : '' }}
                                                </div>
                                            @endif

                                            <a href="{{ route('teknisi.slip.pdf', $slip->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-pdf me-1"></i> PDF
                                            </a>

                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailSlip{{ $slip->id }}">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </button>
                                        </td>


                                        </tr>

                                        {{-- Modal Detail Slip --}}
                                        <div class="modal fade" id="detailSlip{{ $slip->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i> Rincian Gaji - {{ ucfirst($slip->periode) }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Gaji Pokok:
                                                                <span class="fw-bold">Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Insentif Harian:
                                                                <span class="fw-bold">Rp {{ number_format($slip->insentif_harian, 0, ',', '.') }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                                                <strong>Gaji Bruto:</strong>
                                                                <strong class="text-primary">Rp {{ number_format($slip->gaji_bruto, 0, ',', '.') }}</strong>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center text-danger">
                                                                Potongan BPJS TK:
                                                                <span class="fw-bold">Rp {{ number_format($slip->bpjs_tk, 0, ',', '.') }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center text-danger">
                                                                Potongan BPJS Kes:
                                                                <span class="fw-bold">Rp {{ number_format($slip->bpjs_kes, 0, ',', '.') }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center text-danger">
                                                                Potongan Pinjaman:
                                                                <span class="fw-bold">Rp {{ number_format($slip->pinjaman, 0, ',', '.') }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center bg-warning text-dark mt-2">
                                                                <strong>TOTAL GAJI BERSIH:</strong>
                                                                <strong class="text-dark fs-5">Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}</strong>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Tutup</button>
                                                        <a href="{{ route('teknisi.slip.pdf', $slip->id) }}" class="btn btn-primary"><i class="fas fa-download me-1"></i> Unduh PDF</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection