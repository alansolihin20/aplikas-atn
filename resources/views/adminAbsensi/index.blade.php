
      @extends('layouts.app')

      @section('title', 'Karyawan')
<meta http-equiv="Cache-Control" content="no-store" />
      @section('content')
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Jadwal Teknisi</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Jadwal Teknisi</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
         <div class="card m-4 p-0">
        <div class="card-header"><h3 class="card-title">Jadwal Teknisi</h3></div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">

        @if (isset($errorMessage))
        <div class="alert alert-danger">
            {{ $errorMessage }}
        </div>
    @endif


            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <!-- Scrollable modal -->
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createJadwal">
                <i class="fas fa-plus"></i>
            Tambah Jadwal
            </button>

              <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createShift">
                <i class="fas fa-plus"></i>
            Tambah Shift 
            </button>


             @component('components.modal', [
                'id' => 'createShift',
                'title' => 'Tambah Shift',
            ])
               @slot('slot')
                <form action="{{ route('admin.shift_times.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                  <div>
                    <label for="name">Nama Shift</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                  <div>
                    <label for="name">Start Shift</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>
                  <div>
                    <label for="name">End Shift</label>
                    <input type="time" name="end_time" class="form-control" required>
                </div>

                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>


                    <table class="table table-bordered">
            <thead>
                <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Start Shift</th>
                <th>End Shift</th>
                <th>Aksi</th>
                </tr>
            </thead>
            @foreach ($shifts as $shift) 
            <tbody>
                <tr class="align-middle">
                <td>{{$loop->iteration}}</td>
                <td>{{$shift->name}}</td>
                <td>{{$shift->start_time}}</td>
                <td>{{$shift->end_time}}</td>
                </tr>
            @endforeach
            </tbody>
            </table>  
        </div>
          <!--end::Container-->


                </form>
               @endslot
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @endcomponent



            <!-- Modal Tambah -->
            
            @component('components.modal', [
                'id' => 'createJadwal',
                'title' => 'Tambah Jadwal',
            ])
               @slot('slot')
                <form action="{{ route('admin.shift_schedules.auto_generate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                 <div>
                    <label for="week_start">Tanggal Mulai Minggu</label>
                    <input type="date" name="week_start" class="form-control" required>
                </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
               @endslot
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @endcomponent

               


           
            <table class="table table-bordered">
            <thead>
                <tr>
                <th>#</th>
                <th>Teknisi</th>
                <th>Shift</th>
                <th>Tanggal</th>
                <th>Aksi</th>
                </tr>
            </thead>
            @foreach ($schedules as $schedule) 
            <tbody>
                <tr class="align-middle">
                <td>{{$loop->iteration}}</td>
                <td>{{$schedule->user->name ?? ''}}</td>
                <td>{{$schedule->shift->name}}</td>
                <td>{{ formatTanggal($schedule->date) }}</td>
                </tr>
            @endforeach
            </tbody>
            </table>  
        </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      @endsection
 