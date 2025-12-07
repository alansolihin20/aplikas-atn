@extends('layouts.app')

@section('title', 'Request Barang')
<meta http-equiv="Cache-Control" content="no-store" />
@section('content')

<main class="app-main">

{{-- HEADER & BREADCRUMBS (Tidak berubah) --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">📝 Request Barang Inventory</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Request Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="app-content">
    <div class="container-fluid">

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
            <div class="alert alert-success mx-4">
                {{ session('success') }}
            </div>
        @endif
        @if (isset($errorMessage))
            <div class="alert alert-danger mx-4">
                {{ $errorMessage }}
            </div>
        @endif

        {{-- CARD: RIWAYAT REQUEST --}}
        <div class="card m-4 p-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Riwayat Request Saya</h3>
                {{-- TOMBOL TRIGGER MODAL REQUEST --}}
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#requestModal">
                    <i class="fas fa-plus"></i> Buat Request Baru
                </button>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th>Tanggal Request</th>
                            {{-- Tambah Kolom Aksi jika ada tombol View Detail atau Approve Admin --}}
                            <th>Aksi</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $r)
                        <tr>
                            <td>{{ $r->item->name }}</td>
                            <td>{{ $r->qty }}</td>
                            <td>{{ $r->note }}</td>
                            <td>
                                @php
                                    // Menggunakan status yang didefinisikan di Controller Anda
                                    $badge_class = '';
                                    if ($r->status == 'pending') { $badge_class = 'bg-warning'; }
                                    elseif ($r->status == 'sent_to_supplier') { $badge_class = 'bg-info'; }
                                    elseif ($r->status == 'supplier_approved') { $badge_class = 'bg-success'; }
                                    else { $badge_class = 'bg-secondary'; }
                                @endphp
                                <span class="badge {{ $badge_class }}">{{ ucfirst(str_replace('_', ' ', $r->status)) }}</span>
                            </td>
                            <td>{{ $r->created_at->format('d M Y H:i') }}</td>
                            <td>
                                {{-- Tombol untuk Admin/User untuk mengirim ke supplier, hanya jika status 'pending' --}}
                                @if(Auth::check() && Auth::user()->hasRole('admin') && $r->status == 'pending')
                                    <form action="{{ route('itemRequests.sendToSupplier', $r->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-info" title="Kirim ke Supplier">
                                            <i class="fas fa-truck"></i> Kirim
                                        </button>
                                    </form>
                                @endif
                                {{-- Tombol untuk Admin/Supervisor untuk Approve dari Supplier --}}
                                @if(Auth::check() && Auth::user()->hasRole('admin') && $r->status == 'sent_to_supplier')
                                    <form action="{{ route('itemRequests.approveSupplier', $r->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Approve Supplier">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if($requests->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada riwayat permintaan barang.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</main>

{{-- ================================================================= --}}
{{-- MODAL UNTUK REQUEST BARANG (Group Request) --}}
{{-- ================================================================= --}}
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Buat Permintaan Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('itemRequests.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    
                    {{-- 1. FORM ADD ITEM TO TABLE --}}
                    <div class="card p-3 mb-4 bg-light">
                        <h6>Tambah Item Permintaan</h6>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <select id="item_id_select" class="form-control" required>
                                    <option value="" data-stock="0">-- Pilih Barang --</option>
                                    @foreach($items as $i)
                                        <option value="{{ $i->id }}" data-name="{{ $i->name }}" data-stock="{{ $i->stock ?? 0 }}">
                                            {{ $i->name }} (Stok: {{ $i->stock ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input type="number" id="qty_input" class="form-control" min="1" placeholder="Qty" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" id="note_input" class="form-control" placeholder="Catatan per item (opsional)">
                            </div>
                        </div>
                        <button type="button" id="addItemButton" class="btn btn-secondary btn-sm">
                            <i class="fas fa-plus"></i> Tambah ke Daftar
                        </button>
                    </div>

                    {{-- 2. LIST ITEMS IN TABLE --}}
                    <h6>Daftar Barang yang Diminta:</h6>
                    <table class="table table-bordered table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Barang</th>
                                <th>Qty</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="requestTableBody">
                            {{-- Items will be added here by JavaScript --}}
                        </tbody>
                    </table>
                    <p id="emptyMessage" class="text-center text-muted mt-2">Daftar permintaan masih kosong.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    {{-- Tombol submit akan mengirim semua data yang ada di tabel --}}
                    <button type="submit" id="submitRequestBtn" class="btn btn-primary" disabled>
                        <i class="fas fa-paper-plane"></i> Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function(){

    let itemCounter = 0;
    const addBtn = document.getElementById("addItemButton");
    const tbody = document.getElementById("requestTableBody");
    const submitBtn = document.getElementById("submitRequestBtn");
    const emptyMsg = document.getElementById("emptyMessage");

    function updateStatus(){
        const count = tbody.querySelectorAll("tr").length;
        submitBtn.disabled = (count === 0);
        emptyMsg.style.display = count === 0 ? "block" : "none";
    }

    addBtn.addEventListener("click", function(){
        const select = document.getElementById("item_id_select");
        const qty = document.getElementById("qty_input").value;
        const note = document.getElementById("note_input").value;
        const itemId = select.value;
        const itemName = select.options[select.selectedIndex].dataset.name;

        if(itemId && qty > 0){
            itemCounter++;

            let row = document.createElement("tr");
            row.id = `row-${itemCounter}`;
            row.innerHTML = `
                <td>${itemName}
                    <input type="hidden" name="items[${itemCounter}][item_id]" value="${itemId}">
                </td>
                <td>${qty}
                    <input type="hidden" name="items[${itemCounter}][qty]" value="${qty}">
                </td>
                <td>${note ?? ''}
                    <input type="hidden" name="items[${itemCounter}][note]" value="${note}">
                </td>
                <td><button type="button" class="btn btn-danger btn-sm deleteRow" data-id="${itemCounter}">Hapus</button></td>
            `;

            tbody.appendChild(row);

            select.value = "";
            document.getElementById("qty_input").value = "";
            document.getElementById("note_input").value = "";

            updateStatus();
        }else{
            alert("Pilih barang + qty harus valid!");
        }
    });

    document.addEventListener("click", function(e){
        if(e.target.classList.contains("deleteRow")){
            let id = e.target.dataset.id;
            document.getElementById(`row-${id}`).remove();
            updateStatus();
        }
    });

    updateStatus();
});
</script>
@endpush
