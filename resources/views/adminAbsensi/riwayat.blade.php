
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
              <div class="col-sm-6"><h3 class="mb-0"><i class="fas fa-history me-2"> </i>Riwayat Absensi</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Riwayat Absensi</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
        <div class="card m-2 p-0">
      <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Riwayat Absensi Teknisi</h5>
            </div>

    <!-- Filter -->
    <form action="{{ route('admin.riwayat.index') }}" method="GET" class="row g-2 mb-4 p-2">
        <div class="col-md-3">
            <select name="user_id" class="form-select">
                <option value="">-- Semua Teknisi --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="date" value="{{ request('date') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i class="fas fa-search"></i> Filter</button>
        </div>
    </form>

    <!-- Tabel Riwayat -->
    <div class="table-responsive p-2">
        <table class="table table-striped table-hover align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Teknisi</th>
                    <th>Shift</th>
                    <th>Tanggal</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status Lokasi</th>
                    <th>Alasan</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $index => $absen)
                    <tr>
                        <td>{{ $attendances->firstItem() + $index }}</td>
                        <td class="text-start">{{ $absen->user->name ?? '-' }}</td>
                        <td>
                            @if($absen->shift)
                                <span class="badge bg-{{ $absen->shift->id == 1 ? 'primary' : 'success' }}">
                                    Shift {{ $absen->shift->id }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Tidak Ada</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($absen->check_in)->translatedFormat('d M Y') }}</td>
                        <td>{{ $absen->check_in ? \Carbon\Carbon::parse($absen->check_in)->format('H:i') : '-' }}</td>
                        <td>{{ $absen->check_out ? \Carbon\Carbon::parse($absen->check_out)->format('H:i') : '-' }}</td>
                        <td>
                            @if($absen->check_in_lat && $absen->check_in_lng)
                                <a href="https://www.google.com/maps?q={{ $absen->check_in_lat }},{{ $absen->check_in_lng }}" 
                                   target="_blank" class="btn btn-sm btn-outline-info">
                                   Lihat Lokasi
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    
                        
                        <td>{{ $absen->reason ?? '-' }}</td>
                       
                        


                        <td>
                            @if($absen->photo_url)
                                <a href="{{ asset($absen->photo_url) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-image"></i> Lihat
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">Tidak ada data absen</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $attendances->links() }}
    </div>
</div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      @endsection
 