@extends('layouts.app')
@section('title', 'Barang Keluar')
<meta http-equiv="Cache-Control" content="no-store"/>
@section('content')

<main class="app-main">

    <!-- HEADER -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">📤 Barang Keluar</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Barang Keluar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="app-content">
        <div class="card m-4">

            <div class="card-header">
                <h3 class="card-title">Data Pengeluaran Barang</h3>
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

                <!-- BUTTON -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalOut">
                    <i class="fas fa-plus me-1"></i> Catat Barang Keluar
                </button>

                <!-- ========== MODAL TAMBAH PENGELUARAN ========== -->
                @component('components.modal', ['id'=>'modalOut', 'title'=>'Catat Pengeluaran Barang'])
                @slot('slot')
                
                <form action="{{ route('itemOut.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <select name="item_id" class="form-select" required>
                            <option value="">-- pilih barang --</option>
                            @foreach($items as $i)
                                <option value="{{ $i->id }}">
                                    {{ $i->name }} (stok {{ $i->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah Keluar</label>
                        <input type="number" name="qty" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label>Keperluan / Tujuan</label>
                        <input type="text" name="purpose" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>

                </form>
                
                @endslot
                @endcomponent
                <!-- ============================================= -->

                <!-- TABLE -->
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Tujuan</th>
                            <th>Dikeluarkan Oleh</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>

                    @foreach($outs as $o)
                    <tr>
                        <td>{{ $o->item->name }}</td>
                        <td>{{ $o->qty }}</td>
                        <td>{{ $o->purpose }}</td>
                        <td>{{ $o->user->name ?? '-' }}</td>
                        <td>{{ $o->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach

                </table>

            </div>
        </div>
    </div>

</main>
@endsection
