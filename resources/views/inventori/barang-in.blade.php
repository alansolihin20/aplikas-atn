@extends('layouts.app')

@section('title', 'Inventori Barang')

@section('content')
     <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Barang Masuk</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Barang Masuk</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>

        @component('components.modal', [
          'id' => 'barangInModal',
          'title' => 'Tambah Barang',
        ])

        @slot('slot')
        <form action="{{ route('barang-in.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="item_id" class="form-label">ID Item</label>
                <input type="text" class="form-control" id="item_id" name="item_id" required>
            </div>
            {{-- <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="category_id" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select> 
            </div> --}}
            
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" value="Pemasukan" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="supplier" class="form-label">supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        @endslot

        @endcomponent

        <div class="card m-4 p-0">
        <div class="card-header"><h3 class="card-title">Inventori</h3></div>
        <!-- /.card-header -->
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#barangInModal">
                <i class="fas fa-plus"></i>
            Tambah Barang
          </button>


            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Item ID</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Supplier</th>
                    <th scope="col">Keterangan</th>
                    </tr>
                </thead>

                @foreach ($barangIns as $barangIn )
                   <tbody>
                    <tr class="align-middle">
                      <td>{{$loop->iteration}}</td>
                      <td>{{$barangIn->item_id}}</td>
                      <td>{{$barangIn->jumlah}}</td>
                      <td>{{formatRupiah($barangIn->harga)}}</td>
                      <td>{{formatTanggal($barangIn->tanggal)}}</td>
                      <td>{{$barangIn->supplier}}</td>
                      <td>{{$barangIn->keterangan}}</td>
                    </tr>
                @endforeach
            </table>
        </div>

            
           
                
        <!--end::App Content-->
      </main>
@endsection