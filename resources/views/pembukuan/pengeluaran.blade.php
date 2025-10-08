
      @extends('layouts.app')

      @section('title', 'Buku Besar')
<meta http-equiv="Cache-Control" content="no-store" />
      @section('content')
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Tabel Pengeluaran</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pengeluaran</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>

        @component('components.modal', [
          'id' => 'pengeluaranModal',
          'title' => 'Tambah Pengeluaran',
        ])

        @slot('slot')
        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="transaction_date" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="category_id" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="description">
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe Transaksi</label>
                <input type="text" class="form-control" id="type" name="transaction_type" value="Pengeluaran" readonly>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="amount" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        @endslot

        @endcomponent
        

        
        <div class="card m-4 p-0">
        <div class="card-header"><h3 class="card-title">Pengeluaran</h3></div>
        <!-- /.card-header -->
        <div class="card-body  table-responsive">

           <p>Periode</p>
            <form method="GET" action="{{ route('pengeluaran.index') }}" class="row g-2 mb-3">
                <div class="col-auto">
                    <select name="bulan" class="form-select">
                        @foreach(range(1, 12) as $bln)
                            <option value="{{ str_pad($bln, 2, '0', STR_PAD_LEFT) }}" {{ $bln == $bulan ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $bln)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="tahun" class="form-select">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                </div>
            </form>

          <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#pengeluaranModal">
                <i class="fas fa-plus"></i>
            Tambah Pengeluaran
          </button>

            <h5>Pengeluaran Bulan {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</h5>

            <table class="table table-bordered">
            <thead>
              <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Kategori</th>
                <th rowspan="2">Keterangan</th>
                <th rowspan="2">Tipe Transaksi</th>
                <th rowspan="2">Jumlah</th>
                <th rowspan="2">Aksi</th>
              </tr>
            </thead>
            @foreach ($transactions as $transaction )
              
            <tbody>
              <tr class="align-middle">
                <td>{{ $loop->iteration }}</td>
                    <td>{{ formatTanggal($transaction->transaction_date) }}</td>
                    <td>{{ $transaction->category->name }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->transaction_type }}</td>
                    <td>{{ formatRupiah($transaction->amount) }}</td>
                    <td>
                      <form action="{{ route('pengeluaran.destroy', $transaction->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus Pengeluaran ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                      </form> 

                      <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPengeluaran{{ $transaction->id }}">
                    <i class="fas fa-edit"></i>
                  </button>

                  @component('components.modal', [
                    'id' => 'editPengeluaran' . $transaction->id, // Supaya modal-nya unik per kategori
                    'title' => 'Edit Pengeluaran',
                  ])
                @slot('slot')
                    <form action="{{ route('pengeluaran.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                          <label for="tanggal" class="form-label">Tanggal</label>
                          <input type="date" class="form-control" id="tanggal" name="transaction_date" required value="{{ $transaction->transaction_date }}">
                      </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="category_id" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"  {{ $category->id == $transaction->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select> 
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="description" value="{{ $transaction->description }}">
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Transaksi</label>
                        <input type="text" class="form-control" id="type" name="transaction_type" value="Pengeluaran" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="amount" required value="{{ $transaction->amount }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
                @endslot
            @endcomponent 

                    </td>
              </tr>
            </tbody>
            @endforeach
            </table>

            @php
                $totalPengeluaranBulanIni = $transactions->where('transaction_type', 'expense')->sum('amount');
           @endphp


            <div class="mt-3">
                <p>Total Pengeluaran Bulan Ini: <strong>{{ formatRupiah($totalPengeluaranBulanIni) }}</strong></p>
            </div>

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
 