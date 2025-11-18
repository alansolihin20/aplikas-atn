@extends('layouts.app')

@section('title', 'Jadwal Teknisi')

{{-- Menambahkan meta tag yang diperlukan --}}
@section('meta')
<meta http-equiv="Cache-Control" content="no-store" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<main class="app-main">
<!--begin::App Content Header (Menambahkan kembali bagian header halaman)-->
<div class="app-content-header">
<div class="container-fluid">
<div class="row">
<div class="col-sm-6">
<h3 class="mb-0">Jadwal Teknisi</h3>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-end">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Jadwal Teknisi</li>
</ol>
</div>
</div>
</div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-week me-2"></i>Jadwal Shift Mingguan
                </h4>
                @if(isset($startOfWeek) && isset($endOfWeek))
                    <span class="fw-light">
                        {{ $startOfWeek->translatedFormat('d M') }} - {{ $endOfWeek->translatedFormat('d M Y') }}
                    </span>
                @endif
            </div>

            <div class="card-body">
                @if($users->isEmpty())
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-info-circle me-1"></i> Belum ada data teknisi yang terdaftar.
                    </div>
                @else
                    <div class="table-responsive">
                        {{-- Menambahkan kelas table-sm untuk membuat tabel lebih ringkas --}}
                        <table class="table table-sm table-bordered table-hover align-middle text-center mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-start" style="min-width: 150px;">Nama Teknisi</th>
                                    @foreach (range(0, 6) as $i)
                                        @php
                                            $date = $startOfWeek->copy()->addDays($i);
                                        @endphp
                                        {{-- Perbaikan Responsivitas: Membagi Hari dan Tanggal menjadi dua baris --}}
                                        <th style="min-width: 70px;">
                                            <div class="fw-bold">{{ $date->translatedFormat('D') }}</div>
                                            <small class="fw-normal">{{ $date->translatedFormat('d') }}</small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        {{-- Kolom Nama Teknisi --}}
                                        <td class="text-start fw-semibold">{{ $user->name }}</td>

                                        @foreach (range(0, 6) as $i)
                                            @php
                                                $dateString = $startOfWeek->copy()->addDays($i)->toDateString();
                                                $userSchedules = $schedules->get($user->id, collect());
                                                $schedule = $userSchedules->firstWhere('date', $dateString);
                                            @endphp

                                            {{-- Kolom Jadwal Per Hari --}}
                                            <td class="editable-shift" data-schedule-id="{{ $schedule->id ?? '' }}">
                                                @if ($schedule && $schedule->shift)
                                                    <span class="badge bg-{{ 
                                                        $schedule->shift->id == 1 ? 'primary' : 
                                                        ($schedule->shift->id == 2 ? 'success' : 'info') 
                                                    }}">
                                                        {{ $schedule->shift->name ?? 'Shift ' . $schedule->shift->id }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        {{ substr($schedule->shift->start_time, 0, 5) }} - {{ substr($schedule->shift->end_time, 0, 5) }}
                                                    </small>
                                                @else
                                                    <span class="text-muted badge bg-danger">LIBUR</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::App Content-->
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('.editable-shift').forEach(cell => {
        cell.addEventListener('click', async function () {
            const scheduleId = this.dataset.scheduleId;
            if (!scheduleId) return;

            // Tampilkan pilihan shift (popup kecil)
            const newShift = prompt("Masukkan ID Shift baru (misal: 1 atau 2):");
            if (!newShift) return;

            try {
                const res = await fetch("{{ route('shift.update') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        schedule_id: scheduleId,
                        shift_id: newShift
                    })
                });

                const data = await res.json();
                if (data.success) {
                    alert("Shift berhasil diubah!");
                    window.location.reload(); // refresh tabel biar update
                } else {
                    alert("Gagal mengubah shift!");
                }
            } catch (err) {
                console.error(err);
                alert("Terjadi kesalahan koneksi.");
            }
        });
    });
});
</script>
@endpush

</main>
@endsection