@extends('layouts.app')

@section('title', 'Jadwal Teknisi')
<meta http-equiv="Cache-Control" content="no-store" />
@section('content')

<main class="app-main">

{{-- HEADER & BREADCRUMBS --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">📅 Manajemen Jadwal Teknisi</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Jadwal Teknisi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        {{-- ALERT MESSAGES --}}
        @if (isset($errorMessage))
            <div class="alert alert-danger mx-4">
                {{ $errorMessage }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        {{-- CARD: JADWAL TEKNISI (UTAMA) --}}
        <div class="card m-4 p-0">
            <div class="card-header">
                <h3 class="card-title">Daftar Jadwal Teknisi</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#createJadwal">
                        <i class="fas fa-calendar-plus"></i> Buat Jadwal Otomatis
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteWeekModal">
                        <i class="fas fa-trash-alt"></i> Hapus Jadwal per Minggu
                    </button>
                </div>
            </div>

            <div class="card-body table-responsive">
                
                {{-- FILTER FORM --}}
                <form action="{{ route('admin.shift_schedules.index') }}" method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="technician_id" class="form-label">Pilih Teknisi</label>
                            {{-- Asumsi ada variabel $technicians yang berisi daftar teknisi --}}
                            <select name="technician_id" class="form-control">
                                <option value="">Semua Teknisi</option>
                                @isset($technicians)
                                    @foreach($technicians as $technician)
                                        <option value="{{ $technician->id }}" {{ request('technician_id') == $technician->id ? 'selected' : '' }}>
                                            {{ $technician->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter Jadwal</button>
                            <a href="{{ route('admin.shift_schedules.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
                
                {{-- TABEL JADWAL --}}
                <table class="table table-bordered table-striped mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Teknisi</th>
                            <th>Shift</th>
                            <th>Tanggal</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule) 
                        <tr class="align-middle">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$schedule->user->name ?? 'N/A'}}</td>
                            <td>{{$schedule->shift->name ?? 'N/A'}} ({{ $schedule->shift->start_time ?? '' }} - {{ $schedule->shift->end_time ?? '' }})</td>
                            <td>{{ formatTanggal($schedule->date) }}</td>
                            <td>
                                <form action="{{ route('admin.shift_schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus Jadwal"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($schedules->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada jadwal ditemukan.</td>
                            </tr>
                        @endif
                    </tbody>
                </table> 
            </div>
        </div>
        
        {{-- CARD: DAFTAR SHIFT TIME --}}
        <div class="card m-4 p-0">
            <div class="card-header">
                <h3 class="card-title">Daftar Waktu Shift Kerja</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createShift">
                        <i class="fas fa-plus"></i> Tambah Shift
                    </button>
                </div>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Mulai Shift</th>
                            <th>Akhir Shift</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shifts as $shift) 
                        <tr class="align-middle">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$shift->name}}</td>
                            <td>{{$shift->start_time}}</td>
                            <td>{{$shift->end_time}}</td>
                            <td>
                                <form action="{{ route('admin.shift_times.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus shift ini?');">
                                    @csrf
                                    @method('DELETE')
                                    {{-- Tambahkan tombol Edit di sini jika diperlukan --}}
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus Shift"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($shifts->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada waktu shift yang terdaftar.</td>
                            </tr>
                        @endif
                    </tbody>
                </table> 
            </div>
        </div>

    </div>
</div>


</main>

{{-- MODAL TAMBAH SHIFT --}}
@component('components.modal', [
'id' => 'createShift',
'title' => 'Tambah Shift Baru',
])
@slot('slot')
<form action="{{ route('admin.shift_times.store') }}" method="POST">
@csrf
<div class="mb-3">
<label for="name" class="form-label">Nama Shift</label>
<input type="text" name="name" class="form-control" required>
</div>
<div class="mb-3">
<label for="start_time" class="form-label">Mulai Shift</label>
<input type="time" name="start_time" class="form-control" required>
</div>
<div class="mb-3">
<label for="end_time" class="form-label">Akhir Shift</label>
<input type="time" name="end_time" class="form-control" required>
</div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan Shift</button>
        </div>
    </form>
@endslot


@endcomponent

{{-- MODAL BUAT JADWAL OTOMATIS --}}
@component('components.modal', [
'id' => 'createJadwal',
'title' => 'Buat Jadwal Otomatis Mingguan',
])
@slot('slot')
<form action="{{ route('admin.shift_schedules.auto_generate') }}" method="POST">
@csrf
<div class="mb-3">
<label for="week_start" class="form-label">Tanggal Mulai Minggu (Tanggal Senin)</label>
<input type="date" name="week_start" class="form-control" required>
<small class="text-muted">Jadwal akan dibuat untuk 7 hari, mulai dari tanggal yang dipilih.</small>
</div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Generate Jadwal</button>
        </div>
    </form>
@endslot


@endcomponent

{{-- MODAL HAPUS JADWAL PER MINGGU --}}
@component('components.modal', [
'id' => 'deleteWeekModal',
'title' => 'Hapus Jadwal Per Minggu',
])
@slot('slot')
<p class="text-danger"><strong>PERINGATAN:</strong> Tindakan ini akan menghapus semua jadwal shift dalam rentang tanggal yang Anda tentukan.</p>
<form action="{{ route('admin.shift_schedules.delete_by_week') }}" method="POST">
@csrf
@method('DELETE')
<div class="mb-3">
<label for="delete_week_start" class="form-label">Tanggal Mulai Minggu yang Dihapus</label>
<input type="date" name="week_start" class="form-control" required>
</div>
<div class="mb-3">
<label for="delete_week_end" class="form-label">Tanggal Akhir Minggu yang Dihapus</label>
<input type="date" name="week_end" class="form-control" required>
</div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus Jadwal</button>
        </div>
    </form>
@endslot


@endcomponent

@endsection