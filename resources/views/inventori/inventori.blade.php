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
              <div class="col-sm-6"><h3 class="mb-0">Inventori</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Inventori</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>

        <div class="card m-4 p-0">
        <div class="card-header"><h3 class="card-title">Kategori</h3></div>
        <!-- /.card-header -->
        <div class="card-body">
              <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kode Barang</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Satuan</th>
                    <th scope="col">Harga Beli</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    </tr>
                    <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>
                    <td>@twitter</td>
                    </tr>
                </tbody>
            </table>
        </div>


           
                
        <!--end::App Content-->
      </main>
@endsection